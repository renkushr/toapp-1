<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Agent extends Authenticatable
{
    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function zipCodes()
    {
        return $this->hasMany(AgentZipCode::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function conversations()
    {
        return $this->hasMany(AgentConversation::class);
    }

    public function coversZip(string $zipCode): bool
    {
        $normalized = \App\Support\UsZip::normalize($zipCode);

        if (!$normalized) {
            return false;
        }

        return $this->zipCodes()->where('zip_code', $normalized)->exists();
    }

    public function zipCodeList(): string
    {
        return $this->zipCodes()
            ->orderBy('zip_code')
            ->pluck('zip_code')
            ->implode(', ');
    }
}
