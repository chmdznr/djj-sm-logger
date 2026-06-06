<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Adds defense-in-depth HTTP response headers to every response.
 *
 * - X-Content-Type-Options: nosniff  — prevents MIME-type sniffing (older
 *   browsers / misconfigurations can be tricked into executing user-uploaded
 *   content as the wrong type).
 * - X-Frame-Options: SAMEORIGIN      — clickjacking protection. The admin
 *   panel uses the same XHR endpoints as the JSON API, so a framing context
 *   would also expose the CSRF surface; SAMEORIGIN keeps the app embeddable
 *   by the admin's own pages but blocks third-party sites.
 * - Referrer-Policy: strict-origin-when-cross-origin — only send the origin
 *   (not the full URL with path/query) to third parties, full URL only on
 *   same-origin. Avoids leaking internal admin URLs to external resources.
 * - Permissions-Policy: geolocation=(), microphone=(), camera=() — opt out
 *   of sensitive browser features the app does not need. Add more as the
 *   app grows.
 *
 * Note: Content-Security-Policy is intentionally NOT set here. The admin
 * layout has a ~115 line inline DataTables init script; a strict CSP would
 * break it. CSP is a follow-up — move the inline block into a partial and
 * add a nonce-based policy then.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=()'
        );

        return $response;
    }
}
