<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'status',
        'settings'
    ];
}
