<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class TokoApi extends Controller
{
    protected Toko $model;

    public function __construct()
    {
        $this->model = new Toko();
    }

    #[OA\Get(
        path: '/api/tokos',
        operationId: 'getTokoList',
        summary: 'Get list of stores',
        description: 'Retrieves all stores. The response is a plain array without a message or data wrapper. Requires the view-tokos permission.',
        tags: ['Toko'],
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
                description: 'Forbidden - missing permission view-tokos',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            )
        ]
    )]
    public function index()
    {
        $tokos = Toko::all();
        return $this->apiResponseOk(
            data: $tokos->map->transformed(),
            message: 'Tokos retrieved successfully.'
        );
    }

    #[OA\Get(
        path: '/api/tokos/{toko}',
        operationId: 'getTokoDetail',
        summary: 'Get store detail',
        description: 'Retrieves a single store by its ID. Requires the show-tokos permission.',
        tags: ['Toko'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'toko',
                description: 'ID of the store',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Store retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Toko Emas Sejahtera'),
                                new OA\Property(property: 'code', type: 'string', nullable: true, example: 'TK-001'),
                                new OA\Property(property: 'address', type: 'string', nullable: true, example: 'Jl. Pemuda No. 10, Semarang'),
                                new OA\Property(property: 'phone', type: 'string', nullable: true, example: '081234567890')
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
                description: 'Forbidden - missing permission show-tokos',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Toko not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Toko] 1')
                    ]
                )
            )
        ]
    )]
    public function show(Toko $toko)
    {
        return $this->apiResponseOk(
            data: $toko->transformed(),
            message: 'Toko retrieved successfully.'
        );
    }

    #[OA\Post(
        path: '/api/tokos',
        operationId: 'createToko',
        summary: 'Create store',
        description: 'Creates a new store. Requires the create-tokos permission.',
        tags: ['Toko'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Toko Emas Sejahtera'),
                    new OA\Property(property: 'code', type: 'string', maxLength: 255, nullable: true, example: 'TK-001'),
                    new OA\Property(property: 'address', type: 'string', maxLength: 255, nullable: true, example: 'Jl. Pemuda No. 10, Semarang'),
                    new OA\Property(property: 'phone', type: 'string', maxLength: 255, nullable: true, example: '081234567890')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Toko created successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Toko created successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Toko Emas Sejahtera'),
                                new OA\Property(property: 'code', type: 'string', nullable: true, example: 'TK-001'),
                                new OA\Property(property: 'address', type: 'string', nullable: true, example: 'Jl. Pemuda No. 10, Semarang'),
                                new OA\Property(property: 'phone', type: 'string', nullable: true, example: '081234567890')
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
                description: 'Forbidden - missing permission create-tokos',
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
                        new OA\Property(property: 'message', type: 'string', example: 'The name field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'name',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The name field is required.')
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

        $toko = Toko::create($validatedData);

        return $this->apiResponseOk(
            data: $toko->transformed(),
            message: 'Toko created successfully.'
        );
    }


    #[OA\Put(
        path: '/api/tokos/{toko}',
        operationId: 'updateToko',
        summary: 'Update store',
        description: 'Updates an existing store. Requires the edit-tokos permission.',
        tags: ['Toko'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'toko',
                description: 'ID of the store',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Toko Emas Sejahtera Baru'),
                    new OA\Property(property: 'code', type: 'string', maxLength: 255, nullable: true, example: 'TK-001'),
                    new OA\Property(property: 'address', type: 'string', maxLength: 255, nullable: true, example: 'Jl. Pandanaran No. 5, Semarang'),
                    new OA\Property(property: 'phone', type: 'string', maxLength: 255, nullable: true, example: '081298765432')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Toko updated successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Toko updated successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Toko Emas Sejahtera Baru'),
                                new OA\Property(property: 'code', type: 'string', nullable: true, example: 'TK-001'),
                                new OA\Property(property: 'address', type: 'string', nullable: true, example: 'Jl. Pandanaran No. 5, Semarang'),
                                new OA\Property(property: 'phone', type: 'string', nullable: true, example: '081298765432')
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
                description: 'Forbidden - missing permission edit-tokos',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Toko not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Toko] 1')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The name field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'name',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The name field is required.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, Toko $toko)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $toko->update($validatedData);

        return $this->apiResponseOk(
            data: $toko->transformed(),
            message: 'Toko updated successfully.'
        );
    }

    #[OA\Delete(
        path: '/api/tokos/{toko}',
        operationId: 'deleteToko',
        summary: 'Delete store',
        description: 'Soft deletes a store (the record is kept with a deleted_at timestamp). Requires the delete-tokos permission.',
        tags: ['Toko'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'toko',
                description: 'ID of the store',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Toko deleted successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Toko deleted successfully.')
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
                description: 'Forbidden - missing permission delete-tokos',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Toko not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Toko] 1')
                    ]
                )
            )
        ]
    )]
    public function destroy(Toko $toko)
    {
        $toko->delete();
        return $this->apiResponseOk(
            data: null,
            message: 'Toko deleted successfully.'
        );
    }
}
