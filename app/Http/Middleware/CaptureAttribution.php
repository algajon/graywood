<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaptureAttribution
{
    private array $keys = [
        'utm_source','utm_medium','utm_campaign','utm_term','utm_content',
        'gclid','fbclid'
    ];

    public function handle(Request $request, Closure $next)
    {
        $store = (array) session('attribution', []);
        $sawAny = false;

        foreach ($this->keys as $k) {
            if ($request->filled($k)) {
                $val = $request->query($k);
                $store[$k] = $val;
                cookie()->queue(cookie()->forever($k, $val));
                $sawAny = true;
            }
        }

        if (!isset($store['first_landing'])) {
            $store['first_landing'] = $request->fullUrl();
            $store['first_seen_at'] = now()->toIso8601String();
            $ref = $request->headers->get('referer');
            if ($ref && !Str::contains($ref, $request->getHost())) {
                $store['first_referrer'] = $ref;
            }
        }

        if ($sawAny) {
            $store['last_landing'] = $request->fullUrl();
            $store['last_seen_at'] = now()->toIso8601String();
        }

        if (!$sawAny && empty(session('attribution'))) {
            foreach ($this->keys as $k) {
                if ($request->cookies->has($k)) {
                    $store[$k] = $request->cookies->get($k);
                }
            }
        }

        session(['attribution' => $store]);
        return $next($request);
    }
}
