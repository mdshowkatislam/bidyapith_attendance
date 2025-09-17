<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

abstract class Controller
{
    protected function apisuccessResponse($message = 'Success', $data = null, $statusCode = Response::HTTP_OK)
    {
        $response = [
            'status' => true,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    protected function apiunsuccessResponse($message = 'Failed', $statusCode = Response::HTTP_BAD_REQUEST)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $statusCode);
    }

    protected function apierrorResponse(\Throwable $e, $message = 'Validation error', $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY)
    {
        return response()->json([
            'status' => false,
            'message' => $message . ' : ' . $e->getMessage(),
        ], $statusCode);
    }
}
