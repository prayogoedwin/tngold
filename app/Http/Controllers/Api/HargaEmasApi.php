<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HargaEmas\HargaEmas;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class HargaEmasApi extends Controller
{
    protected HargaEmas $model;

    public function __construct()
    {
        $this->model = new HargaEmas();
    }

    #[OA\Get(
        path: '/harga-emas',
        operationId: 'getHargaEmasList',
        summary: 'Get list of gold prices',
        description: 'Retrieves all gold price data. Requires the view-harga-emas permission.',
        tags: ['Harga Emas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'per_page',
                description: 'Number of items per page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 10)
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Harga Emas retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Harga Emas retrieved successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'nama_produk', type: 'string', example: 'Emas Batangan 1 gram'),
                                    new OA\Property(property: 'harga_beli', type: 'number', example: 1250000),
                                    new OA\Property(property: 'harga_jual', type: 'number', example: 1350000),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z')
                                ]
                            )
                        )
                    ]
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
                description: 'Forbidden - missing permission view-harga-emas',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $per_page = $request->integer('per_page', 10);
        $page = $request->integer('page', 1);


        $hargaEmas = HargaEmas::paginate($per_page, ['*'], 'page', $page);


        $data = $hargaEmas->getCollection()
                ->map(
                    fn(HargaEmas $h) =>
                    $h->transformed()
                );

        $output = $hargaEmas->toArray();
        $output['data'] = $data;

        return $this->apiResponseOk(
            data: $output,
            message: 'Harga Emas retrieved successfully.'
        );
    }

    #[OA\Get(
        path: '/harga-emas/{hargaEmas}',
        operationId: 'getHargaEmasDetail',
        summary: 'Get gold price detail',
        description: 'Retrieves a single gold price along with its latest 20 price history records. Requires the show-harga-emas permission.',
        tags: ['Harga Emas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'hargaEmas',
                description: 'ID of the gold price',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Harga Emas detail retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'nama_produk', type: 'string', example: 'Emas Batangan 1 gram'),
                                new OA\Property(property: 'harga_beli', type: 'number', example: 1250000),
                                new OA\Property(property: 'harga_jual', type: 'number', example: 1350000),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z')
                            ]
                        ),
                        new OA\Property(
                            property: 'hargaHistoryData',
                            type: 'array',
                            description: 'Latest 20 price history records',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 100),
                                    new OA\Property(property: 'harga_emas_id', type: 'integer', example: 1),
                                    new OA\Property(property: 'harga_beli', type: 'number', example: 1240000),
                                    new OA\Property(property: 'harga_jual', type: 'number', example: 1340000),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-06-30T00:00:00.000000Z')
                                ]
                            )
                        )
                    ]
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
                description: 'Forbidden - missing permission show-harga-emas',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Harga Emas not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\HargaEmas] 1')
                    ]
                )
            )
        ]
    )]
    public function show(HargaEmas $hargaEmas)
    {
        $structure = $hargaEmas->structure();
        $data = $hargaEmas;

        $hargaHistoryData = $data->hargaEmasHistories()->limit(20)->get();

        return $this->apiResponseOk(
            data: [
                'data' => $data->transformed(),
                'hargaHistoryData' => $hargaHistoryData->map->transformed(),
            ],
            message: 'Harga Emas retrieved successfully.'
        );
    }

    #[OA\Post(
        path: '/harga-emas',
        operationId: 'createHargaEmas',
        summary: 'Create gold price',
        description: 'Creates a new gold price record. Requires the create-harga-emas permission.',
        tags: ['Harga Emas'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['emas_asal', 'kadar', 'satuan', 'jumlah', 'harga_beli'],
                properties: [
                    new OA\Property(property: 'emas_asal', type: 'string', example: 'Antam'),
                    new OA\Property(property: 'kadar', type: 'integer', example: '24'),
                    new OA\Property(property: 'satuan', type: 'string', example: 'gram'),
                    new OA\Property(property: 'jumlah', type: 'number', example: 1),
                    new OA\Property(property: 'harga_beli', type: 'number', example: 1250000)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Harga Emas created successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Harga Emas created successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'emas_asal', type: 'string', example: 'Antam'),
                                new OA\Property(property: 'kadar', type: 'integer', example: '24'),
                                new OA\Property(property: 'satuan', type: 'string', example: 'gram'),
                                new OA\Property(property: 'jumlah', type: 'number', example: 1),
                                new OA\Property(property: 'harga_beli', type: 'number', example: 1250000)
                            ]
                        )
                    ]
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
                description: 'Forbidden - missing permission create-harga-emas',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The nama produk field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'nama_produk',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The nama produk field is required.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $hargaEmas = HargaEmas::create($validatedData);

        return $this->apiResponseOk(
            data: $hargaEmas->transformed(),
            message: 'Harga Emas created successfully.'
        );
    }

    #[OA\Put(
        path: '/harga-emas/{hargaEmas}',
        operationId: 'updateHargaEmas',
        summary: 'Update gold price',
        description: 'Updates an existing gold price record. Requires the edit-harga-emas permission.',
        tags: ['Harga Emas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'hargaEmas',
                description: 'ID of the gold price',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['emas_asal', 'kadar', 'satuan', 'jumlah', 'harga_beli'],
                properties: [
                    new OA\Property(property: 'emas_asal', type: 'string', example: 'Antam'),
                    new OA\Property(property: 'kadar', type: 'integer', example: 24),
                    new OA\Property(property: 'satuan', type: 'string', example: 'gram'),
                    new OA\Property(property: 'jumlah', type: 'number', example: 1),
                    new OA\Property(property: 'harga_beli', type: 'number', example: 1260000)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Harga Emas updated successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Harga Emas updated successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'emas_asal', type: 'string', example: 'Antam'),
                                new OA\Property(property: 'kadar', type: 'integer', example: 24),
                                new OA\Property(property: 'satuan', type: 'string', example: 'gram'),
                                new OA\Property(property: 'jumlah', type: 'number', example: 1),
                                new OA\Property(property: 'harga_beli', type: 'number', example: 1260000),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-07-02T00:00:00.000000Z')
                            ]
                        )
                    ]
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
                description: 'Forbidden - missing permission edit-harga-emas',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Harga Emas not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\HargaEmas] 1')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The harga beli field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'harga_beli',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The harga beli field is required.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, HargaEmas $hargaEmas)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $hargaEmas->update($validatedData);

        return $this->apiResponseOk(
            data: $hargaEmas->transformed(),
            message: 'Harga Emas updated successfully.'
        );
    }

    #[OA\Delete(
        path: '/harga-emas/{hargaEmas}',
        operationId: 'deleteHargaEmas',
        summary: 'Delete gold price',
        description: 'Deletes a gold price record. Requires the delete-harga-emas permission.',
        tags: ['Harga Emas'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'hargaEmas',
                description: 'ID of the gold price',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Harga Emas deleted successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Harga Emas deleted successfully.')
                    ]
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
                description: 'Forbidden - missing permission delete-harga-emas',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Harga Emas not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\HargaEmas] 1')
                    ]
                )
            )
        ]
    )]
    public function destroy(HargaEmas $hargaEmas)
    {
        $hargaEmas->delete();
        return $this->apiResponseOk(
            data: null,
            message: 'Harga Emas deleted successfully.'
        );
    }
}
