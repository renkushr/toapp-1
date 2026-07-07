<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdPartyPortal extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_third_party_portal')
            ->withPivot('published')
            ->withTimestamps();
    }

    public function applications()
    {
        return $this->hasMany(InsuranceApplication::class);
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->where('status', true)->first();
    }
}
