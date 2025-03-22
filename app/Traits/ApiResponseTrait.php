<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponser
{
    // Success Response
    protected function successResponse($data, $message = "Success", $code = Response::HTTP_OK)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    // Error Response
    protected function errorResponse($message, $code)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }
}


