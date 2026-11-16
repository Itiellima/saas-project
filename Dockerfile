# syntax=docker/dockerfile:1
#
# Imagem de produção do OficinaOS (PHP-FPM + Nginx), usada pelo Easypanel.
# O app escuta na porta 8080. Veja DEPLOY.md.

############################################
# 1) Dependências PHP (sem pacotes de desenvolvimento)
############################################
FROM serversideup/php:8.3-fpm-nginx AS vendor

WORKDIR /var/www/html

COPY --chown=www-data:www-data composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --no-autoloader \
    --prefer-dist


############################################
# 2) CSS/JS (Vite + Tailwind)
############################################
# Debian (glibc): o package-lock.json traz os binários linux-x64-gnu
FROM node:22-bookworm-slim AS assets

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY vite.config.js ./
COPY resources ./resources

# O app.css importa o CSS do Flux, que fica dentro de vendor/
COPY --from=vendor /var/www/html/vendor/livewire/flux ./vendor/livewire/flux

RUN npm run build


############################################
# 3) Imagem final
############################################
FROM serversideup/php:8.3-fpm-nginx

WORKDIR /var/www/html

# Ao iniciar o container: storage:link, migrate --force e caches (config, rotas, views)
ENV AUTORUN_ENABLED=true \
    PHP_OPCACHE_ENABLE=1

COPY --chown=www-data:www-data --from=vendor /var/www/html/vendor ./vendor
COPY --chown=www-data:www-data . .
COPY --chown=www-data:www-data --from=assets /app/public/build ./public/build

# Autoload otimizado + descoberta dos pacotes (post-autoload-dump do composer.json)
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative
