<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            abort(response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401));
        }
        return null;
    }
}
