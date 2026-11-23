<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TransaksiKategori;

use OpenApi\Attributes as OA;


class TransaksiKategoriApi extends Controller
{
    protected TransaksiKategori $model;


    public function __construct()
    {
        $this->model = new TransaksiKategori();
    }


    #[OA\Get(
        path: '/api/transaksi-kategoris',
        operationId: 'getTransaksiKategoriList',
        summary: 'Get list of transaction categories',
        description: 'Retrieves all transaction categories. The response is a plain array without a message or data wrapper. Requires the view-transaksi-kategoris permission.',
        tags: ['Transaksi Kategori'],
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
        $transaksiKategoris = TransaksiKategori::all();
        return response()->json($transaksiKategoris->map->transformed());
    }

    #[OA\Get(
        path: '/api/transaksi-kategoris/{transaksiKategori}',
        operationId: 'getTransaksiKategoriDetail',
        summary: 'Get transaction category detail',
        description: 'Retrieves a single transaction category by its ID. Requires the show-transaksi-kategoris permission.',
        tags: ['Transaksi Kategori'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'transaksiKategori',
                description: 'ID of the transaction category',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Transaction category retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'Kategori Transaksi 1'),
                                new OA\Property(property: 'code', type: 'string', nullable: true, example: 'KT-001'),
                                new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Deskripsi kategori transaksi 1')
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
                description: 'Forbidden - missing permission show-transaksi-kategoris',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Transaction category not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\TransaksiKategori] 1')
                    ]
                )
            )
        ]
    )]
    public function show(TransaksiKategori $transaksiKategori)
    {
        return response()->json(
            [
                'data' => $transaksiKategori->transformed()
            ]
        );
    }

    #[OA\Post(
        path: '/api/transaksi-kategoris',
        operationId: 'createTransaksiKategori',
        summary: 'Create transaction category',
        description: 'Creates a new transaction category. Requires the create-transaksi-kategoris permission.',
        tags: ['Transaksi Kategori'],
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
                description: 'Transaction category created successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Transaction category created successfully.'),
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
                description: 'Forbidden - missing permission create-transaksi-kategoris',
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

        $transaksiKategori = TransaksiKategori::create($validatedData);

        return response()->json([
            'message' => 'Transaction category created successfully.',
            'data' => $transaksiKategori->transformed()
        ]);
    }

    public function edit(TransaksiKategori $transaksiKategori)
    {
        $structure = $transaksiKategori->structure();
        $data = $transaksiKategori;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    #[OA\Put(
        path: '/api/transaksi-kategoris/{transaksiKategori}',
        operationId: 'updateTransaksiKategori',
        summary: 'Update transaction category',
        description: 'Updates an existing transaction category. Requires the edit-transaksi-kategoris permission.',
        tags: ['Transaksi Kategori'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'transaksiKategori',
                description: 'ID of the transaction category',
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
                description: 'Transaction category updated successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Transaction category updated successfully.'),
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
                description: 'Forbidden - missing permission edit-transaksi-kategoris',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Transaction category not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\TransaksiKategori] 1')
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
    public function update(Request $request, TransaksiKategori $transaksiKategori)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $transaksiKategori->update($validatedData);

        return response()->json([
            'message' => 'Transaction category updated successfully.',
            'data' => $transaksiKategori->transformed()
        ]);
    }

    #[OA\Delete(
        path: '/api/transaksi-kategoris/{transaksiKategori}',
        operationId: 'deleteTransaksiKategori',
        summary: 'Delete transaction category',
        description: 'Soft deletes a transaction category (the record is kept with a deleted_at timestamp). Requires the delete-transaksi-kategoris permission.',
        tags: ['Transaksi Kategori'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'transaksiKategori',
                description: 'ID of the transaction category',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Transaction category deleted successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Transaction category deleted successfully.')
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
                description: 'Forbidden - missing permission delete-transaksi-kategoris',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Transaction category not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\TransaksiKategori] 1')
                    ]
                )
            )
        ]
    )]
    public function destroy(TransaksiKategori $transaksiKategori)
    {
        $transaksiKategori->delete();
        return response()->json([
            'message' => 'Transaction category deleted successfully.'
        ]);
    }
}
