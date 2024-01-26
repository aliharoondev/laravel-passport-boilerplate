<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse{

    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function created(array $data = [], string $message = "Request was successful", int $code = 201) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => []
        ], $code);
    }

    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function success(array $data = [], string $message = "Request was successful", int $code = 200) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => []
        ], $code);
    }

    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function invalid(array $data = [], string $message = "Request contains invalid payload.", int $code = 422) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [],
            'errors' => $data
        ], $code);
    }

    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function notFound(array $data = [], string $message = "Requested resource was not found on server", int $code = 404) : JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [],
            'errors' => $data
        ], $code);
    }

    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function error(array $data = [], string $message = "Something went wrong on server", int $code = 500) : JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => [],
            'errors' => $data
        ], $code);
    }

    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function forbidden(array $data = [], string $message = "You are forbidden.", int $code = 403) : JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => [],
            'errors' => $data
        ], $code);
    }

    /**
     * @param array $data
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function unauthorized(array $data = [], string $message = "You are not authorize.", int $code = 401) : JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => [],
            'errors' => $data
        ], $code);
    }

}
