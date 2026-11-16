# Deploy — OficinaOS (Hostinger VPS + Easypanel)

O app roda como imagem Docker construída pelo [Dockerfile](Dockerfile) (PHP 8.3 + Nginx, porta **8080**).
A cada deploy o container roda as migrations, o `storage:link` e os caches do Laravel automaticamente.

## 1. Banco de dados (MySQL)

1. No Easypanel, crie um **projeto** (ex.: `oficinaos`).
2. Dentro dele: **+ Service → MySQL**. Dê um nome (ex.: `db`).
3. Anote o que o Easypanel mostrar: **host interno**, porta, banco, usuário e senha.
   O host interno costuma ser `<projeto>_<serviço>` (ex.: `oficinaos_db`).

## 2. Serviço do app

1. **+ Service → App**. Nome: `app`.
2. **Source → GitHub**: repositório `Itiellima/saas-project`, branch `main`.
3. **Build → Dockerfile** (caminho `Dockerfile`).
4. **Domains**: aponte para a **porta 8080**. Use o domínio temporário `*.easypanel.host`
   até ter um domínio próprio (o Easypanel gera o HTTPS sozinho).

## 3. Variáveis de ambiente

Em **Environment** do serviço `app`, cole e preencha:

```env
APP_NAME=OficinaOS
APP_ENV=production
APP_DEBUG=false
APP_KEY=
APP_URL=https://SEU-DOMINIO
APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=en

LOG_CHANNEL=stderr
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=oficinaos_db
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
CACHE_STORE=database
QUEUE_CONNECTION=database

# E-mail — PENDENTE (sem provedor ainda).
# Enquanto for "log", o "esqueci minha senha" NÃO envia e-mail.
MAIL_MAILER=log
MAIL_SCHEME=
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=
MAIL_FROM_NAME="${APP_NAME}"
```

**APP_KEY**: gere na sua máquina com `php artisan key:generate --show` e cole o valor
(começa com `base64:`). Gere **uma vez** e não troque mais: trocar invalida sessões e dados criptografados.

## 4. Deploy

1. Clique em **Deploy** no serviço `app` (deploy manual — não é automático a cada push).
2. Acompanhe o log do build. Se falhar, copie o log inteiro.
3. Para atualizar depois: `git push` para a `main` → **Deploy** de novo no Easypanel.

## 5. Primeiro acesso

1. Abra `https://SEU-DOMINIO` → landing page.
2. **Criar conta** (`/register`) cria a primeira oficina e o usuário dono.

## 6. Backup do banco

- Destino: **Dropbox**, configurado no Easypanel.
- Configure o backup agendado no serviço `db` (**Backups**), com frequência diária.
- Depois de configurar, **teste uma restauração** pelo menos uma vez — backup que nunca foi restaurado não é garantia.

## 7. Regras de manutenção

- **Nunca** rode o seeder em produção (o `DemoSeeder` já se recusa em `production`, mas não force).
- **Nunca edite migrations antigas** depois do primeiro deploy: mudança de banco = **nova migration**.
- Nunca rode `migrate:fresh` / `migrate:reset` em produção (apaga tudo).
- De tempos em tempos, rode localmente `composer audit` e `npm audit`; se houver alertas,
  atualize (`composer update` / `npm audit fix`), rode os testes e faça um novo deploy.
- `APP_DEBUG` sempre `false` em produção (com `true` o erro mostra senhas e variáveis).

## Pendências

- [ ] Provedor de e-mail (SMTP) para o "esqueci minha senha".
- [ ] Domínio próprio (trocar `APP_URL` e o domínio no Easypanel).
