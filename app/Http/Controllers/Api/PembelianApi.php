<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembelian\Pembelian;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\HargaEmas\HargaEmas;
use DataTables;

class PembelianApi extends Controller
{
    protected Pembelian $model;


    public function __construct()
    {
        $this->model = new Pembelian();
    }


    public function index(Request $request)
    {

        $pembelians = Pembelian::all();

        return response()->json([
            'message' => 'Pembelian retrieved successfully.',
            'data' => $pembelians->map->transformed()
        ]);
    }


    public function show(Pembelian $pembelian)
    {
        
        $transaksi = $pembelian->transaksiOne();
        // dd(array_merge($structure, ['data' => $data, 'pembelian' => $pembelian, 'transaksi' => $transaksi]));
        return response()->json([
            'message' => 'Pembelian retrieved successfully.',
            'data' => $pembelian->transformed(),
            'transaksi' => $transaksi->transformed() ?? null,
        ]);
    }

    

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

    public function destroy(Pembelian $pembelian)
    {
        $pembelian->delete();
        return response()->json([
            'message' => 'Pembelian deleted successfully.',
        ]);
    }
}
