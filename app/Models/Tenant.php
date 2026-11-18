<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Support\BrDocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tenant extends Model
{

    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Slug único a partir do nome: "oficina-do-joao", "oficina-do-joao-2", ...
     */
    /**
     * Dono: o primeiro usuário "admin" da oficina (criado no cadastro).
     */
    public function owner()
    {
        return $this->hasOne(User::class)->where('role', 'admin')->oldestOfMany();
    }

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * Rótulo e cor (classe do Bootstrap) do status, usados nas telas de administração.
     *
     * @return array{label: string, class: string}
     */
    public function statusConfig(): array
    {
        return match ($this->status) {
            'suspended' => ['label' => 'Suspensa', 'class' => 'danger'],
            'cancelled' => ['label' => 'Cancelada', 'class' => 'secondary'],
            default => ['label' => 'Ativa', 'class' => 'success'],
        };
    }

    /**
     * CPF/CNPJ com pontuação (no banco fica só com números).
     */
    public function formattedDocument(): ?string
    {
        return $this->document ? BrDocument::format($this->document) : null;
    }

    /**
     * "CPF 123.456.789-09" ou "CNPJ 11.222.333/0001-81".
     */
    public function documentLabel(): ?string
    {
        return $this->document
            ? trim(BrDocument::type($this->document) . ' ' . $this->formattedDocument())
            : null;
    }

    public function formattedZipCode(): ?string
    {
        return $this->zip_code && strlen($this->zip_code) === 8
            ? substr($this->zip_code, 0, 5) . '-' . substr($this->zip_code, 5)
            : $this->zip_code;
    }

    /**
     * Endereço em uma linha, só com as partes preenchidas.
     * Ex.: "Rua das Flores, 120 - Galpão 2 · Centro · São Paulo/SP · CEP 01001-000"
     */
    public function addressLine(): ?string
    {
        $street = collect([$this->street, $this->number])->filter()->implode(', ');

        if ($street && $this->complement) {
            $street .= ' - ' . $this->complement;
        }

        $city = collect([$this->city, $this->state])->filter()->implode('/');

        $line = collect([
            $street,
            $this->district,
            $city,
            $this->zip_code ? 'CEP ' . $this->formattedZipCode() : null,
        ])->filter()->implode(' · ');

        return $line !== '' ? $line : null;
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    /**
     * A oficina do administrador geral nunca pode ser suspensa (ele ficaria trancado fora).
     */
    public function canBeSuspended(): bool
    {
        return ! $this->users()->where('is_super_admin', true)->exists();
    }

    public static function uniqueDomain(string $name): string
    {
        $base = Str::slug($name) ?: 'oficina';
        $domain = $base;
        $suffix = 2;

        while (static::where('domain', $domain)->exists()) {
            $domain = $base . '-' . $suffix++;
        }

        return $domain;
    }

    protected $fillable = [
        'name',
        'domain',
        'email',
        'phone',
        'logo',
        'plan_id',
        'plan_expires_at',
        'document',
        'zip_code',
        'street',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'status',
        'settings'
    ];
}
