<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembelian\Pembelian;
use App\Models\Transaksi;
use OpenApi\Attributes as OA;

class PembelianApi extends Controller
{
    protected Pembelian $model;

    public function __construct()
    {
        $this->model = new Pembelian();
    }

    #[OA\Get(
        path: '/api/pembelians',
        operationId: 'getPembelianList',
        summary: 'Get list of purchases',
        description: 'Retrieves all purchase records. Requires the view-pembelians permission.',
        tags: ['Pembelian'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pembelian retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Pembelian retrieved successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'no_invoice', type: 'string', example: 'INV-PB-0001'),
                                    new OA\Property(property: 'status', type: 'string', example: 'completed'),
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
                description: 'Forbidden - missing permission view-pembelians',
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
        $pembelians = Pembelian::all();

        return response()->json([
            'message' => 'Pembelian retrieved successfully.',
            'data' => $pembelians->map->transformed()
        ]);
    }

    #[OA\Get(
        path: '/api/pembelians/{pembelian}',
        operationId: 'getPembelianDetail',
        summary: 'Get purchase detail',
        description: 'Retrieves a single purchase along with its related transaction (if the purchase has been completed). Requires the show-pembelians permission.',
        tags: ['Pembelian'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'pembelian',
                description: 'ID of the purchase',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pembelian retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Pembelian retrieved successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'no_invoice', type: 'string', example: 'INV-PB-0001'),
                                new OA\Property(property: 'status', type: 'string', example: 'completed'),
                                new OA\Property(
                                    property: 'details',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'emas_asal', type: 'string', example: 'Perhiasan bekas'),
                                            new OA\Property(property: 'kadar', type: 'string', example: '24K'),
                                            new OA\Property(property: 'satuan', type: 'string', example: 'gram'),
                                            new OA\Property(property: 'jumlah', type: 'number', example: 10),
                                            new OA\Property(property: 'harga_per_satuan', type: 'number', example: 1250000),
                                            new OA\Property(property: 'total_harga', type: 'number', example: 12500000)
                                        ]
                                    )
                                ),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z')
                            ]
                        ),
                        new OA\Property(
                            property: 'transaksi',
                            type: 'object',
                            nullable: true,
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 5),
                                new OA\Property(property: 'type', type: 'string', example: 'OUT'),
                                new OA\Property(property: 'transaksi_kategori_id', type: 'integer', example: 4),
                                new OA\Property(property: 'tanggal', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                new OA\Property(property: 'toko_id', type: 'integer', example: 1),
                                new OA\Property(property: 'nominal', type: 'number', example: 12500000),
                                new OA\Property(property: 'keterangan', type: 'string', example: 'Pembelian #INV-PB-0001')
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
                description: 'Forbidden - missing permission show-pembelians',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Pembelian not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Pembelian\\Pembelian] 1')
                    ]
                )
            )
        ]
    )]
    public function show(Pembelian $pembelian)
    {
        $transaksi = $pembelian->transaksiOne();

        return response()->json([
            'message' => 'Pembelian retrieved successfully.',
            'data' => $pembelian->transformed(),
            'transaksi' => $transaksi->transformed() ?? null,
        ]);
    }

    #[OA\Post(
        path: '/api/pembelians',
        operationId: 'createPembelian',
        summary: 'Create purchase',
        description: 'Creates a new purchase with its detail items and attaches it to a store. If the status is "completed", an outgoing transaction (type OUT) is automatically created. Requires the create-pembelians permission.',
        tags: ['Pembelian'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['toko_id', 'details'],
                properties: [
                    new OA\Property(property: 'toko_id', type: 'integer', example: 1),
                    new OA\Property(property: 'no_invoice', type: 'string', example: 'INV-PB-0001'),
                    new OA\Property(property: 'status', type: 'string', example: 'completed'),
                    new OA\Property(
                        property: 'details',
                        type: 'array',
                        minItems: 1,
                        items: new OA\Items(
                            type: 'object',
                            required: ['emas_asal', 'kadar', 'satuan', 'jumlah', 'harga_per_satuan', 'total_harga'],
                            properties: [
                                new OA\Property(property: 'emas_asal', type: 'string', example: 'Perhiasan bekas'),
                                new OA\Property(property: 'kadar', type: 'string', example: '24K'),
                                new OA\Property(property: 'satuan', type: 'string', example: 'gram'),
                                new OA\Property(property: 'jumlah', type: 'number', example: 10),
                                new OA\Property(property: 'harga_per_satuan', type: 'number', example: 1250000),
                                new OA\Property(property: 'total_harga', type: 'number', example: 12500000)
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pembelian created successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Pembelian created successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'no_invoice', type: 'string', example: 'INV-PB-0001'),
                                new OA\Property(property: 'status', type: 'string', example: 'completed'),
                                new OA\Property(
                                    property: 'details',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'emas_asal', type: 'string', example: 'Perhiasan bekas'),
                                            new OA\Property(property: 'kadar', type: 'string', example: '24K'),
                                            new OA\Property(property: 'satuan', type: 'string', example: 'gram'),
                                            new OA\Property(property: 'jumlah', type: 'number', example: 10),
                                            new OA\Property(property: 'harga_per_satuan', type: 'number', example: 1250000),
                                            new OA\Property(property: 'total_harga', type: 'number', example: 12500000)
                                        ]
                                    )
                                ),
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
                description: 'Forbidden - missing permission create-pembelians',
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
                        new OA\Property(property: 'message', type: 'string', example: 'The details field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'details',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The details field is required.')
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
            ...$this->model->getValidationRules(),

            'toko_id' => ['required', 'exists:tokos,id'],

            'details' => ['required', 'array', 'min:1'],
            'details.*.emas_asal' => ['required', 'string'],
            'details.*.kadar' => ['required'],
            'details.*.satuan' => ['required', 'string'],
            'details.*.jumlah' => ['required', 'numeric'],
            'details.*.harga_per_satuan' => ['required', 'numeric'],
            'details.*.total_harga' => ['required', 'numeric'],
        ]);

        $selectedTokoId = $validatedData['toko_id'];

        $pembelian = DB::transaction(function () use ($validatedData, $selectedTokoId) {

            // Simpan data pembelian
            $pembelian = Pembelian::create(
                collect($validatedData)->except('details')->toArray()
            );

            // Simpan semua detail pembelian
            $pembelian->details()->createMany(
                $validatedData['details']
            );

            $pembelian->toko()->attach($selectedTokoId);

            //jika status pembelian adalah 'completed', buat transaksi
            if (isset($validatedData['status']) && $validatedData['status'] === 'completed') {
                // Buat data transaksi terkait pembelian
                $transaksi = Transaksi::create([
                    'type' => 'OUT',
                    'transaksi_kategori_id' => 4, // Ganti dengan ID kategori yang sesuai
                    'tanggal' => now(),
                    'toko_id' => $selectedTokoId,
                    'nominal' => $pembelian->totalHarga(),
                    'keterangan' => 'Pembelian #' . $pembelian->no_invoice,
                ]);

                $pembelian->transaksi()->attach($transaksi->id);
            }
            return $pembelian;
        });

        return response()->json([
            'message' => 'Pembelian created successfully.',
            'data' => $pembelian->transformed(),
        ]);
    }

    #[OA\Put(
        path: '/api/pembelians/{pembelian}',
        operationId: 'updatePembelian',
        summary: 'Update purchase',
        description: 'Updates an existing purchase and replaces all of its detail items. If the status changes from non-completed to "completed", an outgoing transaction (type OUT) is automatically created. Requires the edit-pembelians permission.',
        tags: ['Pembelian'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'pembelian',
                description: 'ID of the purchase',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['toko_id', 'details'],
                properties: [
                    new OA\Property(property: 'toko_id', type: 'integer', example: 1),
                    new OA\Property(property: 'no_invoice', type: 'string', example: 'INV-PB-0001'),
                    new OA\Property(property: 'status', type: 'string', example: 'completed'),
                    new OA\Property(
                        property: 'details',
                        type: 'array',
                        minItems: 1,
                        items: new OA\Items(
                            type: 'object',
                            required: ['emas_asal', 'kadar', 'satuan', 'jumlah', 'harga_per_satuan', 'total_harga'],
                            properties: [
                                new OA\Property(property: 'emas_asal', type: 'string', example: 'Perhiasan bekas'),
                                new OA\Property(property: 'kadar', type: 'string', example: '24K'),
                                new OA\Property(property: 'satuan', type: 'string', example: 'gram'),
                                new OA\Property(property: 'jumlah', type: 'number', example: 10),
                                new OA\Property(property: 'harga_per_satuan', type: 'number', example: 1250000),
                                new OA\Property(property: 'total_harga', type: 'number', example: 12500000)
                            ]
                        )
                    )
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pembelian updated successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Pembelian updated successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'no_invoice', type: 'string', example: 'INV-PB-0001'),
                                new OA\Property(property: 'status', type: 'string', example: 'completed'),
                                new OA\Property(
                                    property: 'details',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 2),
                                            new OA\Property(property: 'emas_asal', type: 'string', example: 'Perhiasan bekas'),
                                            new OA\Property(property: 'kadar', type: 'string', example: '24K'),
                                            new OA\Property(property: 'satuan', type: 'string', example: 'gram'),
                                            new OA\Property(property: 'jumlah', type: 'number', example: 10),
                                            new OA\Property(property: 'harga_per_satuan', type: 'number', example: 1250000),
                                            new OA\Property(property: 'total_harga', type: 'number', example: 12500000)
                                        ]
                                    )
                                ),
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
                description: 'Forbidden - missing permission edit-pembelians',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Pembelian not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Pembelian\\Pembelian] 1')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The details field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'details',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The details field is required.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, Pembelian $pembelian)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules(),

            'toko_id' => ['required', 'exists:tokos,id'],

            'details' => ['required', 'array', 'min:1'],
            'details.*.emas_asal' => ['required', 'string'],
            'details.*.kadar' => ['required'],
            'details.*.satuan' => ['required', 'string'],
            'details.*.jumlah' => ['required', 'numeric'],
            'details.*.harga_per_satuan' => ['required', 'numeric'],
            'details.*.total_harga' => ['required', 'numeric'],
        ]);

        //jika status pembelian sebelumnya bukan 'completed' dan status baru adalah 'completed', perlu buat transaksi
        $perlu_buat_transaksi = $pembelian->status !== 'completed' && isset($validatedData['status']) && $validatedData['status'] === 'completed';

        $selectedTokoId = $validatedData['toko_id'];

        $pembelian = DB::transaction(function () use ($validatedData, $pembelian, $perlu_buat_transaksi, $selectedTokoId) {

            // Simpan data pembelian
            $pembelian->update(
                collect($validatedData)->except('details')->toArray()
            );
            $pembelian = $pembelian->fresh();

            //hapus detail pembelian lama jika ada
            $pembelian->details()->delete();

            // Simpan semua detail pembelian
            $pembelian->details()->createMany(
                $validatedData['details']
            );

            //jika status pembelian adalah 'completed', buat transaksi
            if ($perlu_buat_transaksi) {
                // Buat data transaksi terkait pembelian
                $transaksi = Transaksi::create([
                    'type' => 'OUT',
                    'transaksi_kategori_id' => 4, // Ganti dengan ID kategori yang sesuai
                    'tanggal' => now(),
                    'toko_id' => $selectedTokoId,
                    'nominal' => $pembelian->totalHarga(),
                    'keterangan' => 'Pembelian #' . $pembelian->no_invoice,
                ]);
                $pembelian->transaksi()->attach($transaksi->id);
            }
            return $pembelian;
        });

        return response()->json([
            'message' => 'Pembelian updated successfully.',
            'data' => $pembelian->transformed(),
        ]);
    }

    #[OA\Delete(
        path: '/api/pembelians/{pembelian}',
        operationId: 'deletePembelian',
        summary: 'Delete purchase',
        description: 'Deletes a purchase record. Requires the delete-pembelians permission.',
        tags: ['Pembelian'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'pembelian',
                description: 'ID of the purchase',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Pembelian deleted successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Pembelian deleted successfully.')
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
                description: 'Forbidden - missing permission delete-pembelians',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Pembelian not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\Pembelian\\Pembelian] 1')
                    ]
                )
            )
        ]
    )]
    public function destroy(Pembelian $pembelian)
    {
        $pembelian->delete();
        return response()->json([
            'message' => 'Pembelian deleted successfully.',
        ]);
    }
}
