<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use OpenApi\Attributes as OA;

class TransaksiApi extends Controller
{
    protected Transaksi $model;

    public function __construct()
    {
        $this->model = new Transaksi();
    }

    #[OA\Get(
        path: '/transaksis',
        operationId: 'getTransaksiList',
        summary: 'Get list of transactions',
        description: 'Retrieves all transactions belonging to a specific store. Requires the view-transaksis permission.',
        tags: ['Transaksi'],
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
                description: 'Transaksis retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Transaksis retrieved successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'toko_id', type: 'integer', example: 1),
                                    new OA\Property(property: 'tipe', type: 'string', example: 'IN'),
                                    new OA\Property(property: 'nominal', type: 'number', example: 500000),
                                    new OA\Property(property: 'keterangan', type: 'string', example: 'Penjualan emas 1 gram'),
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
                description: 'Forbidden - missing permission view-transaksis',
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
                        new OA\Property(property: 'message', type: 'string', example: 'The toko id field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'toko_id',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The toko id field is required.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function indexPembelian(Request $request)
    {
        
        $per_page = $request->integer('per_page', 10);
        $page = $request->integer('page', 1);

        $user = $request->user();
        $toko_id = $user->tokos->first()->id;

        $transaksis = Transaksi::where('toko_id', $toko_id)->paginate($per_page, ['*'], 'page', $page);

        $data = $transaksis->getCollection()
                ->map(
                    fn(Transaksi $t) =>
                    $t->transformed()
                );

        $output = $transaksis->toArray();
        $output['data'] = $data;

        return $this->apiResponseOk(
            data: $output,
            message: 'Transaksis retrieved successfully.'
        );
    }
    public function indexInternal(Request $request)
    {
        
        $per_page = $request->integer('per_page', 10);
        $page = $request->integer('page', 1);

        $transaksis = Transaksi::paginate($per_page, ['*'], 'page', $page);

        $data = $transaksis->getCollection()
                ->map(
                    fn(Transaksi $t) =>
                    $t->transformed()
                );

        $output = $transaksis->toArray();
        $output['data'] = $data;

        return $this->apiResponseOk(
            data: $output,
            message: 'Transaksis retrieved successfully.'
        );
    }

    #[OA\Get(
        path: '/transaksis/current-saldo',
        operationId: 'getTransaksiCurrentSaldo',
        summary: 'Get current balance',
        description: 'Retrieves the current balance (saldo) of a specific store. Requires the view-transaksis permission.',
        tags: ['Transaksi'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'toko_id',
                description: 'ID of the store',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Current saldo retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'current_saldo', type: 'number', example: 15000000)
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
                description: 'Forbidden - missing permission view-transaksis',
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
                        new OA\Property(property: 'message', type: 'string', example: 'The selected toko id is invalid.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'toko_id',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The selected toko id is invalid.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function getCurrentSaldo(Request $request)
    {
        $request->validate([
            'toko_id' => 'required|integer|exists:tokos,id'
        ]);

        $saldo = $this->model->currentSaldo($request->toko_id);
        return $this->apiResponseOk(
            data: ['current_saldo' => $saldo],
            message: 'Current saldo retrieved successfully.'
        );
    }

    #[OA\Get(
        path: '/transaksis/total-nominal-transaksi-masuk',
        operationId: 'getTotalNominalTransaksiMasuk',
        summary: 'Get total incoming transaction amount',
        description: 'Retrieves the total nominal of incoming transactions for a specific store. Requires the view-transaksis permission.',
        tags: ['Transaksi'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'toko_id',
                description: 'ID of the store',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Total incoming transaction amount retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'total_nominal_transaksi_masuk', type: 'number', example: 25000000)
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
                description: 'Forbidden - missing permission view-transaksis',
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
                        new OA\Property(property: 'message', type: 'string', example: 'The selected toko id is invalid.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'toko_id',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The selected toko id is invalid.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function getTotalNominalTransaksiMasuk(Request $request)
    {
        $request->validate([
            'toko_id' => 'required|integer|exists:tokos,id'
        ]);

        $total = $this->model->transaksiMasuk()->where('toko_id', $request->toko_id)->sum('nominal');
        return $this->apiResponseOk(
            data: ['total_nominal_transaksi_masuk' => $total],
            message: 'Total incoming transaction amount retrieved successfully.'
        );
    }

    #[OA\Get(
        path: '/transaksis/total-nominal-transaksi-keluar',
        operationId: 'getTotalNominalTransaksiKeluar',
        summary: 'Get total outgoing transaction amount',
        description: 'Retrieves the total nominal of outgoing transactions for a specific store. Requires the view-transaksis permission.',
        tags: ['Transaksi'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'toko_id',
                description: 'ID of the store',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Total outgoing transaction amount retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'total_nominal_transaksi_keluar', type: 'number', example: 10000000)
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
                description: 'Forbidden - missing permission view-transaksis',
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
                        new OA\Property(property: 'message', type: 'string', example: 'The selected toko id is invalid.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'toko_id',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The selected toko id is invalid.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function getTotalNominalTransaksiKeluar(Request $request)
    {
        $request->validate([
            'toko_id' => 'required|integer|exists:tokos,id'
        ]);

        $total = $this->model->transaksiKeluar()->where('toko_id', $request->toko_id)->sum('nominal');
        return $this->apiResponseOk(
            data: ['total_nominal_transaksi_keluar' => $total],
            message: 'Total outgoing transaction amount retrieved successfully.'
        );
    }

    #[OA\Get(
        path: '/transaksis/{transaksi}',
        operationId: 'getTransaksiDetail',
        summary: 'Get transaction detail',
        description: 'Retrieves a single transaction by its ID. Requires the show-transaksis permission.',
        tags: ['Transaksi'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'transaksi',
                description: 'ID of the transaction',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Transaksi retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Transaksi retrieved successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'toko_id', type: 'integer', example: 1),
                                new OA\Property(property: 'tipe', type: 'string', example: 'IN'),
                                new OA\Property(property: 'nominal', type: 'number', example: 500000),
                                new OA\Property(property: 'keterangan', type: 'string', example: 'Penjualan emas 1 gram'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z')
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
                description: 'Forbidden - missing permission show-transaksis',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Transaksi not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Transaksi] 1')
                    ]
                )
            )
        ]
    )]
    public function show(Transaksi $transaksi)
    {
        $data = $transaksi;
        return $this->apiResponseOk(
            data: $data->transformed(),
            message: 'Transaksi retrieved successfully.'
        );
    }

    #[OA\Post(
        path: '/transaksis',
        operationId: 'createTransaksi',
        summary: 'Create transaction',
        description: 'Creates a new transaction. Requires the create-transaksis permission.',
        tags: ['Transaksi'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['toko_id', 'transaksi_kategori_id', 'type', 'tanggal', 'nominal'],
                properties: [
                    new OA\Property(property: 'toko_id', type: 'integer', example: 1),
                    new OA\Property(property: 'transaksi_kategori_id', type: 'integer', example: 1),
                    new OA\Property(property: 'type', type: 'string', example: 'IN'),
                    new OA\Property(property: 'tanggal', type: 'string', example: '2024-06-28'),
                    new OA\Property(property: 'nominal', type: 'number', example: 500000),
                    new OA\Property(property: 'keterangan', type: 'string', nullable: true, example: 'Penjualan emas 1 gram')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Transaksi created successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Transaksi created successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'toko_id', type: 'integer', example: 1),
                                new OA\Property(property: 'transaksi_kategori_id', type: 'integer', example: 1),
                                new OA\Property(property: 'type', type: 'string', example: 'IN'),
                                new OA\Property(property: 'tanggal', type: 'string', example: '2024-06-28'),
                                new OA\Property(property: 'nominal', type: 'number', example: 500000),
                                new OA\Property(property: 'keterangan', type: 'string', example: 'Penjualan emas 1 gram'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z')
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
                description: 'Forbidden - missing permission create-transaksis',
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
                        new OA\Property(property: 'message', type: 'string', example: 'The nominal field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'nominal',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The nominal field is required.')
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

        $transaksi = Transaksi::create($validatedData);

        return $this->apiResponseOk(
            data: $transaksi->transformed(),
            message: 'Transaksi created successfully.'
        );
    }

    #[OA\Put(
        path: '/transaksis/{transaksi}',
        operationId: 'updateTransaksi',
        summary: 'Update transaction',
        description: 'Updates an existing transaction. Requires the edit-transaksis permission.',
        tags: ['Transaksi'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'transaksi',
                description: 'ID of the transaction',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['toko_id', 'transaksi_kategori_id', 'type', 'tanggal', 'nominal'],
                properties: [
                    new OA\Property(property: 'toko_id', type: 'integer', example: 1),
                    new OA\Property(property: 'transaksi_kategori_id', type: 'integer', example: 1),
                    new OA\Property(property: 'type', type: 'string', example: 'keluar'),
                    new OA\Property(property: 'tanggal', type: 'string', example: '2024-06-28'),
                    new OA\Property(property: 'nominal', type: 'number', example: 750000),
                    new OA\Property(property: 'keterangan', type: 'string', nullable: true, example: 'Pembelian emas 1 gram')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Transaksi updated successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Transaksi updated successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'toko_id', type: 'integer', example: 1),
                                new OA\Property(property: 'transaksi_kategori_id', type: 'integer', example: 1),
                                new OA\Property(property: 'type', type: 'string', example: 'keluar'),
                                new OA\Property(property: 'tanggal', type: 'string', example: '2024-06-28'),    
                                new OA\Property(property: 'nominal', type: 'number', example: 750000),
                                new OA\Property(property: 'keterangan', type: 'string', example: 'Pembelian emas 1 gram'),
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
                description: 'Forbidden - missing permission edit-transaksis',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Transaksi not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Transaksi] 1')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The nominal field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'nominal',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The nominal field is required.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, Transaksi $transaksi)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $transaksi->update($validatedData);

        return $this->apiResponseOk(
            data: $transaksi->transformed(),
            message: 'Transaksi updated successfully.'
        );
    }

    #[OA\Delete(
        path: '/transaksis/{transaksi}',
        operationId: 'deleteTransaksi',
        summary: 'Delete transaction',
        description: 'Deletes a transaction. Requires the delete-transaksis permission.',
        tags: ['Transaksi'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'transaksi',
                description: 'ID of the transaction',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Transaksi deleted successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Transaksi deleted successfully.')
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
                description: 'Forbidden - missing permission delete-transaksis',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Transaksi not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Transaksi] 1')
                    ]
                )
            )
        ]
    )]
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return $this->apiResponseOk(
            data: null,
            message: 'Transaksi deleted successfully.'
        );
    }
}
