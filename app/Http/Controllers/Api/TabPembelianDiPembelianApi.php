<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembelian\Pembelian;
use App\Models\Transaksi;
use Illuminate\Http\Request;

use OpenApi\Attributes as OA;

class TabPembelianDiPembelianApi extends Controller
{
    #[OA\Get(
        path: '/tabpembelian',
        operationId: 'getTabPembelianList',
        summary: 'untuk tab pembelian',
        description: 'Retrieves all purchase records. Requires the view-pembelians permission.',
        tags: ['Tab Pembelian'],
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
                                    new OA\Property(property: 'status', type: 'string', example: 'draft/cancelled/completed'),
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
        $per_page = $request->integer('per_page', 10);
        $page = $request->integer('page', 1);

        $user = $request->user();
        $toko_id = $user->tokos->first()->id;

        $pembelians = Pembelian::whereHas('toko', function ($query) use ($toko_id) {
            $query->where('id', $toko_id);
        })->get()->map(function ($pembelian) {
            return [
                'type'       => 'pembelian',
                'title'      => $pembelian->nama_pembeli,
                'kode'       => $pembelian->no_invoice,
                'keterangan' => $pembelian->keterangan,
                'nominal'    => $pembelian->totalHarga(),
                'tanggal'    => $pembelian->tanggal,
            ];
        });

        $transaksis = Transaksi::where('toko_id', $toko_id)
            ->with('transaksiKategori')
            ->get()
            ->map(function ($transaksi) {
                return [
                    'type'       => $transaksi->type == 'IN' ? 'pemasukan' : 'pengeluaran',
                    'title'      => $transaksi->transaksiKategori->name ?? '-',
                    'kode'       => $transaksi->kode,
                    'keterangan' => $transaksi->keterangan,
                    'nominal'    => $transaksi->nominal,
                    'tanggal'    => $transaksi->tanggal,
                ];
            });

        // Gabungkan kedua collection
        $transaksi2 = $transaksis->concat($pembelians)
            ->sortByDesc('tanggal')
            ->values()
            ->toArray();

        

        return $this->apiResponseOk(
            data: $transaksi2,
            message: 'Pembelian retrieved successfully.'
        );
    }
}
