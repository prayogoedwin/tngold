<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;


#[OA\Info(
    version: '1.0.0',
    title: 'TNGOLD API Documentation',
    description: 'API backend TNGOLD — auth, '
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST_PEMBELIAN,
    description: 'Pembelian API Server'
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST_INTERNAL,
    description: 'Internal API Server'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
abstract class Controller
{
    //


    //api response helper function
    protected function apiResponseOk($data = null, $message = '', $status = 200)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'status' => true,
            'code' => $status
        ], $status);
    }

    protected function apiResponseError($message = '', $status = 400, $data = null)
    {
        return response()->json([
            'success' => false,
            'data' => $data,
            'message' => $message,
            'status' => false,
            'code' => $status
        ], $status);
    }

    protected function apiResponseNotFound($message = 'Not Found', $status = 404, $data = null)
    {
        return response()->json([
            'success' => false,
            'data' => $data,
            'message' => $message,
            'status' => false,
            'code' => $status
        ], $status);
    }

    protected function apiResponseUnauthorized($message = 'Unauthorized', $status = 401, $data = null)
    {
        return response()->json([
            'success' => false,
            'data' => $data,
            'message' => $message,
            'status' => false,
            'code' => $status
        ], $status);
    }
}
