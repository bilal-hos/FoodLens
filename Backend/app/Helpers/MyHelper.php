<?php

namespace App\Helpers;

class MyHelper
{
    public static function success($data = [], $message = null, $code = 200)
    {
        return response()->json([
            'status' => True,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function error($data = [], $message = null, $code)
    {
        return response()->json([
            'status' => False,
            'message' => $message,
            'data' => $data,
        ], $code);
    }
}
