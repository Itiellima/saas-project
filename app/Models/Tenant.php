<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{

    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class);
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
