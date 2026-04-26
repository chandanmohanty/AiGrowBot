<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Content-Security-Policy — relaxed to allow inline styles/fonts used by the landing page
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com https://www.youtube.com https://www.youtube-nocookie.com https://www.googletagmanager.com https://cdn.tiny.cloud https://img.youtube.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.tiny.cloud",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com data:",
            "img-src 'self' data: https: blob:",
            "frame-src https://www.youtube.com https://www.youtube-nocookie.com",
            "connect-src 'self' https://www.google-analytics.com https://cdn.tiny.cloud",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self' https://wa.me",
        ]);
        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
