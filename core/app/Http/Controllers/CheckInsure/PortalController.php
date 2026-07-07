<?php

namespace App\Http\Controllers\CheckInsure;

use App\Models\ThirdPartyPortal;
use Illuminate\Routing\Controller;

abstract class PortalController extends Controller
{
    protected function portal(): ThirdPartyPortal
    {
        return ThirdPartyPortal::findBySlug('check-insure')
            ?? abort(503, 'Check Insure portal is not configured.');
    }

    protected function asset(string $path): string
    {
        return asset('check-insure/' . ltrim($path, '/'));
    }
}
