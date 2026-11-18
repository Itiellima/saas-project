<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Volt\Volt;

test('the password reset e-mail is written in portuguese with the reset link', function () {
    Notification::fake();
    $user = User::factory()->create();

    Volt::test('auth.forgot-password')
        ->set('email', $user->email)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPassword::class, function (ResetPassword $notification) use ($user) {
        $mail = $notification->toMail($user);
        $html = (string) $mail->render();

        expect($mail->subject)->toBe('Redefinição de senha')
            ->and($mail->actionText)->toBe('Redefinir senha')
            ->and($mail->actionUrl)->toContain('/reset-password/' . $notification->token)
            ->and($mail->introLines[0])->toContain('redefinir a senha da sua conta')
            ->and($mail->outroLines)->toContain('Este link expira em 60 minutos.')
            ->and($html)->toContain('Olá!')
            ->and($html)->toContain('Atenciosamente,')
            ->and($html)->toContain('não funcionar, copie e cole o link abaixo')
            ->and($html)->not->toContain('Reset Password')
            ->and($html)->not->toContain('Regards');

        return true;
    });
});

test('the test mail command sends an e-mail', function () {
    Mail::fake();

    $this->artisan('app:test-mail', ['email' => 'dono@oficina.test'])
        ->expectsOutputToContain('E-mail enviado para dono@oficina.test')
        ->assertSuccessful();
});

test('the test mail command reports a failure instead of crashing', function () {
    Mail::shouldReceive('raw')->andThrow(new RuntimeException('Authentication failed'));

    $this->artisan('app:test-mail', ['email' => 'dono@oficina.test'])
        ->expectsOutputToContain('Falha ao enviar: Authentication failed')
        ->assertFailed();
});
