<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Returns a response with a success status code (200) and data.
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Returns a response with a specified status code and a message.
     *
     * @param string $message
     * @param int $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($message, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
        ], $code);
    }
}
