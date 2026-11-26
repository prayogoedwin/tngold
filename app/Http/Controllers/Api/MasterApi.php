<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use OpenApi\Attributes as OA;


class MasterApi extends Controller
{
    #[OA\Get(
        path: '/emas-asals',
        operationId: 'getEmasAsalList',
        summary: 'Get list of Emas Asal',
        description: 'Retrieves all Emas Asal. The response is a plain array without a message or data wrapper. Requires the view-emas-asal permission.',
        tags: ['Master'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Stores retrieved successfully',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'Toko Emas Sejahtera'),
                            new OA\Property(property: 'code', type: 'string', nullable: true, example: 'TK-001'),
                            new OA\Property(property: 'address', type: 'string', nullable: true, example: 'Jl. Pemuda No. 10, Semarang'),
                            new OA\Property(property: 'phone', type: 'string', nullable: true, example: '081234567890')
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - missing permission view-emas-asal',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            )
        ]
    )]
    public function getEmasAsal()
    {
        $emasAsal = \App\Models\HargaEmas\HargaEmasEmasAsal::all();
        return $this->apiResponseOk(
            data: $emasAsal->map->transformed(),
        );
    }
}
