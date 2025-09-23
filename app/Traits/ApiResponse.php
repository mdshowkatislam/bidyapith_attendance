<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponse
{
    public function successResponseWithData($data, $message, $code = Response::HTTP_CREATED)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    public function successMessage($message, $code = Response::HTTP_OK)
    {
        return response()->json(['status' => true, 'message' => $message, 'code' => $code], $code);
    }

    public function errorResponse($message, $code)
    {
        return response()->json(['status' => false, 'message' => $message, 'code' => $code], $code);
    }

    public function successResponse($data, $code = Response::HTTP_OK)
    {
        return response()->json(['status' => true, 'data' => $data], $code);
    }
}
