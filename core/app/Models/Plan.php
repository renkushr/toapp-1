<?php

namespace App\Models;

use App\Traits\GlobalStatus;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use GlobalStatus;

    public function referralRules()
    {
        return $this->hasMany(Referral::class);
    }

    public function thirdPartyPortals()
    {
        return $this->belongsToMany(ThirdPartyPortal::class, 'plan_third_party_portal')
            ->withPivot('published')
            ->withTimestamps();
    }

    public function isPublishedTo(string $portalSlug): bool
    {
        return $this->thirdPartyPortals()
            ->where('slug', $portalSlug)
            ->wherePivot('published', true)
            ->exists();
    }

    public function priceForRange(string $payType, int $range): float
    {
        $column = $payType === 'year' ? "annualprice{$range}" : "monthprice{$range}";

        if (!in_array($column, $this->getFillable() ?: [], true) && !array_key_exists($column, $this->getAttributes())) {
            return (float) ($this->{$column} ?? 0);
        }

        return (float) ($this->getAttribute($column) ?? 0);
    }

    public function monthlyPriceForRange(int $range): float
    {
        return (float) ($this->{"monthprice{$range}"} ?? 0);
    }

    public function annualPriceForRange(int $range): float
    {
        return (float) ($this->{"annualprice{$range}"} ?? 0);
    }
    
}
