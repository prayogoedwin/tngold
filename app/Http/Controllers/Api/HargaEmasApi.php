<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HargaEmas\HargaEmas;
use App\Models\HargaEmas\HargaEmasHistory;
use Yajra\DataTables\Facades\DataTables;

class HargaEmasApi extends Controller
{
    protected HargaEmas $model;


    public function __construct()
    {
        $this->model = new HargaEmas();
    }


    public function index()
    {
        $hargaEmas = HargaEmas::all();
        return response()->json([
            'message' => 'Harga Emas retrieved successfully.',
            'data' => $hargaEmas->map->transformed()
        ]);
    }





    public function show(HargaEmas $hargaEmas)
    {
        $structure = $hargaEmas->structure();
        $data = $hargaEmas;

        $hargaHistoryData = $data->hargaEmasHistories()->limit(20)->get();

        return response()->json([
            'data' => $data->transformed(),
            'hargaHistoryData' => $hargaHistoryData->map->transformed(),
        ]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $hargaEmas = HargaEmas::create($validatedData);

        return response()->json([
            'message' => 'Harga Emas created successfully.',
            'data' => $hargaEmas->transformed(),
        ]);
    }


    public function update(Request $request, HargaEmas $hargaEmas)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $hargaEmas->update($validatedData);

        return response()->json([
            'message' => 'Harga Emas updated successfully.',
            'data' => $hargaEmas->transformed(),
        ]);
    }

    public function destroy(HargaEmas $hargaEmas)
    {
        $hargaEmas->delete();
        return response()->json([
            'message' => 'Harga Emas deleted successfully.',
        ]);
    }
}
