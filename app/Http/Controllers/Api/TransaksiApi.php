<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;

class TransaksiApi extends Controller
{
    protected Transaksi $model;


    public function __construct()
    {
        $this->model = new Transaksi();
    }


    public function index(Request $request)
    {
        $request->validate([
            'toko_id' => 'required|integer|exists:tokos,id'
        ]);
        $transaksis = Transaksi::where('toko_id', $request->toko_id)->get();
        return response()->json([
            'message' => 'Transaksis retrieved successfully.',
            'data' => $transaksis->map->transformed()
        ]);
    }

    public function getCurrentSaldo(Request $request)
    {
        $request->validate([
            'toko_id' => 'required|integer|exists:tokos,id'
        ]);


        $saldo = $this->model->currentSaldo($request->toko_id);
        return response()->json(['current_saldo' => $saldo]);
    }

    public function getTotalNominalTransaksiMasuk(Request $request)
    {
        $request->validate([
            'toko_id' => 'required|integer|exists:tokos,id'
        ]);
        
        $total = $this->model->transaksiMasuk()->where('toko_id', $request->toko_id)->sum('nominal');
        return response()->json(['total_nominal_transaksi_masuk' => $total]);
    }

    public function getTotalNominalTransaksiKeluar(Request $request)
    {
        $request->validate([
            'toko_id' => 'required|integer|exists:tokos,id'
        ]);

        $total = $this->model->transaksiKeluar()->where('toko_id', $request->toko_id)->sum('nominal');
        return response()->json(['total_nominal_transaksi_keluar' => $total]);
    }

    public function show(Transaksi $transaksi)
    {
        $data = $transaksi;
        return response()->json([
            'message' => 'Transaksi retrieved successfully.',
            'data' => $data->transformed()
        ]);
    }

    

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $transaksi = Transaksi::create($validatedData);

        return response()->json([
            'message' => 'Transaksi created successfully.',
            'data' => $transaksi->transformed()
        ]);
    }

    

    public function update(Request $request, Transaksi $transaksi)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $transaksi->update($validatedData);

        return response()->json([
            'message' => 'Transaksi updated successfully.',
            'data' => $transaksi->transformed()
        ]);
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return response()->json(['message' => 'Transaksi deleted successfully.']);
    }
}
