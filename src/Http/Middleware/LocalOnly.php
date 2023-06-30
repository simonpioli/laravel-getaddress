<?php

namespace Szhorvath\GetAddress\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LocalOnly
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response | JsonResponse | RedirectResponse
    {
        $trusted = false;

        $domain = str_replace('https://', '', env('APP_URL'));
        if (env('APP_BASE_DOMAIN')) {
            $domain = env('APP_BASE_DOMAIN');
        }

        if (str_contains($request->headers->get('referer'), $domain)) {
            $trusted = true;
        }
        if (str_contains($request->headers->get('referer'), 'localhost')) {
            $trusted = true;
        }

        if (!$trusted) {
            return response(['errorMessage' => 'Invalid origin', 'code' => 400], 400);
        }

        return $next($request);
    }
}
