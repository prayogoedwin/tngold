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

    #[OA\Get(
        path: '/kadars',
        operationId: 'getKadarList',
        summary: 'Get list of Kadar',
        description: 'Retrieves all Kadar. The response is a plain array without a message or data wrapper. Requires the view-emas-asal permission.',
        tags: ['Master'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Kadars retrieved successfully',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: '24K'),
                            new OA\Property(property: 'code', type: 'string', nullable: true, example: '24K'),
                            new OA\Property(property: 'address', type: 'string', nullable: true, example: ''),
                            new OA\Property(property: 'phone', type: 'string', nullable: true, example: '')
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
    public function getKadar()
    {
        $kadar = \App\Models\HargaEmas\HargaEmasKadar::all();
        return $this->apiResponseOk(
            data: $kadar->map->transformed(),
        );
    }


    #[OA\Get(
        path: '/tipetransaksis',
        operationId: 'getTipeTransaksiList',
        summary: 'Get list of Tipe Transaksi',
        description: 'Retrieves all Tipe Transaksi. The response is a plain array without a message or data wrapper. Requires the view-emas-asal permission.',
        tags: ['Master'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Tipe Transaksis retrieved successfully',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: '24K'),
                            new OA\Property(property: 'code', type: 'string', nullable: true, example: '24K'),
                            new OA\Property(property: 'address', type: 'string', nullable: true, example: ''),
                            new OA\Property(property: 'phone', type: 'string', nullable: true, example: '')
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
    public function getTipeTransaksi()
    {
        $tipes = [
            [
                "id" => 1,
                "name" => "IN"
            ],
            [
                "id" => 2,
                "name" => "OUT"
            ]
        ];
        return $this->apiResponseOk(
            data: $tipes,
        );
    }
}
