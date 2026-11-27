<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChiAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $request->user()?->role?->value === 'admin',
            403,
            'Chỉ admin được dùng chức năng này.'
        );

        return $next($request);
    }
}