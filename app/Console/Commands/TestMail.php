<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Throwable;

/**
 * Envia um e-mail simples para conferir a configuração de SMTP (variáveis MAIL_* do Easypanel).
 * php artisan app:test-mail seu@email.com
 */
class TestMail extends Command
{
    protected $signature = 'app:test-mail {email : Para onde enviar o e-mail de teste}';

    protected $description = 'Envia um e-mail de teste para conferir a configuração de envio';

    public function handle(): int
    {
        $to = $this->argument('email');
        $mailer = config('mail.default');

        $this->line("Enviando por <comment>{$mailer}</comment> ("
            . (config("mail.mailers.{$mailer}.host") ?? 'sem host') . ') '
            . 'como <comment>' . config('mail.from.address') . '</comment>...');

        if (in_array($mailer, ['log', 'array'], true)) {
            $this->warn("MAIL_MAILER está como \"{$mailer}\": o e-mail não sai de verdade, só vai para o log.");
        }

        try {
            Mail::raw(
                "Se você recebeu esta mensagem, o envio de e-mails do " . config('app.name') . " está funcionando.\n\n"
                . "Enviado em " . now()->format('d/m/Y H:i') . '.',
                fn ($message) => $message->to($to)->subject('Teste de envio - ' . config('app.name'))
            );
        } catch (Throwable $e) {
            $this->error('Falha ao enviar: ' . $e->getMessage());
            $this->line('Confira MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD e se o domínio do remetente está verificado no Resend.');

            return self::FAILURE;
        }

        $this->info("E-mail enviado para {$to}. Confira a caixa de entrada (e o spam).");

        return self::SUCCESS;
    }
}
