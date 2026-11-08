<?php

namespace App\Http\Middleware;

use App\Constants\Status;
use App\Models\Language;
use Closure;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $code = $this->getCode();
        session()->put('lang', $code);
        app()->setLocale($code);
        return $next($request);
    }

    public function getCode()
    {
        $supported = array_keys(config('toapp_languages.supported', ['en' => []]));

        if (session()->has('lang')) {
            $sessionLang = session('lang');
            if (in_array($sessionLang, $supported, true)) {
                return $sessionLang;
            }
        }

        try {
            $language = Language::where('is_default', Status::ENABLE)->first();
            if ($language && in_array($language->code, $supported, true)) {
                return $language->code;
            }
        } catch (\Throwable $e) {
            // languages table may be unavailable during install
        }

        return 'en';
    }
}
