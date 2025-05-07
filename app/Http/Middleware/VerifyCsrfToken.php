<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // Remove these exclusions once the issue is fixed
        '/login',
        '/register',
        '/admin/login',
        '/logout'
    ];

    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The cookie options for the CSRF token cookie.
     *
     * @var array
     */
    protected $cookieOptions = [
        'same_site' => 'lax',
    ];
}