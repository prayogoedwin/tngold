<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;


#[OA\Info(
    version: '1.0.0',
    title: 'TNGOLD API Documentation',
    description: 'API backend TNGOLD — auth, '
)]
#[OA\Server(
    url: L5_SWAGGER_CONST_HOST,
    description: 'API Server'
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
}
