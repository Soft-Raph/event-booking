<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected function ok(mixed $data=null, string $message='OK', array $extra=[])
    {
        return response()->json([
                'success' => true,
                'message' => $message,
                'data'    => $data,
                'timestamp' => now()->toDateTimeString()
            ] + $extra, 200);
    }
}
