<?php

namespace App\Http\Controllers\HargaEmas;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\HargaEmas\HargaEmas;
use App\Models\HargaEmas\HargaEmasHistory;
use Yajra\DataTables\Facades\DataTables;

class HargaEmasController extends Controller
{
    protected HargaEmas $model;


    public function __construct()
    {
        $this->model = new HargaEmas();
    }


    public function index()
    {
        $structure = (new HargaEmas())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $hargaEmas = HargaEmas::query();

            return DataTables::of($hargaEmas)
                ->addIndexColumn()
                ->addColumn('actions', function ($hargaEmas) {
                    $actions = '';

                    if (auth()->user()->hasPermission('show-harga-emas')) {
                        $actions .= '<a href="' . route('harga-emas.show', $hargaEmas) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }

                    if (auth()->user()->hasPermission('edit-harga-emas')) {
                        $actions .= '<a href="' . route('harga-emas.edit', $hargaEmas) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }

                    if (auth()->user()->hasPermission('delete-harga-emas')) {
                        $actions .= '<form action="' . route('harga-emas.destroy', $hargaEmas) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }

                    return $actions;
                })
                ->editColumn('tanggal', function ($hargaEmas) {
                    return $hargaEmas->tanggal ? $hargaEmas->tanggal->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }


    public function historyIndexTable(HargaEmas $hargaEmas, Request $request)
    {
        if ($request->ajax()) {

            // Ambil seluruh histori dan urutkan dari terlama
            $histories = HargaEmasHistory::where('harga_emas_id', $hargaEmas->id)
                ->orderBy('tanggal', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            // Hitung rata-rata seluruh histori
            $average = $histories->avg('harga_beli') ?? 0;

            // Buat lookup selisih berdasarkan ID histori
            $previousPrice = null;

            $diffs = [];

            foreach ($histories as $history) {

                $diffs[$history->id] = [
                    'diffBefore' => $previousPrice !== null
                        ? ($previousPrice != 0
                            ? (($history->harga_beli - $previousPrice) / $previousPrice) * 100
                            : null)
                        : null,

                    'diffAverage' => $average != 0
                        ? (($history->harga_beli - $average) / $average) * 100
                        : null,
                ];

                $previousPrice = $history->harga_beli;
            }

            // Query DataTables
            $query = HargaEmasHistory::where('harga_emas_id', $hargaEmas->id)
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('harga_beli', function ($history) {
                    //rata kanan
                    return '<div class="text-right">' . number_format($history->harga_beli, 0, '.', ',') . '</div>';
                })

                ->addColumn('diffBefore', function ($history) use ($diffs) {
                    $value = $diffs[$history->id]['diffBefore'] ?? null;

                    if ($value === null) {
                        return '';
                    }

                    $formatted = rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
                    $color = $value > 0 ? 'text-green-600' : ($value < 0 ? 'text-red-600' : '');
                    $sign = $value > 0 ? '+' : '';

                    return '<span class="' . $color . '">' . $sign . $formatted . '%</span>';
                })

                ->addColumn('diffAverage', function ($history) use ($diffs) {
                    $value = $diffs[$history->id]['diffAverage'] ?? null;

                    if ($value === null) {
                        return '';
                    }

                    $formatted = rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.');
                    $color = $value > 0 ? 'text-green-600' : ($value < 0 ? 'text-red-600' : '');
                    $sign = $value > 0 ? '+' : '';

                    return '<span class="' . $color . '">' . $sign . $formatted . '%</span>';
                })

                ->addColumn('actions', function ($history) {
                    $actions = '';

                    if (auth()->user()->hasPermission('edit-harga-emas-history')) {
                        $actions .= '<a href="' . route('harga-emas-history.edit', $history) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }

                    if (auth()->user()->hasPermission('delete-harga-emas-history')) {
                        $actions .= '<form action="' . route('harga-emas-history.destroy', $history) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                    </form>';
                    }

                    return $actions;
                })

                ->editColumn('tanggal', function ($history) {
                    return $history->tanggal
                        ? $history->tanggal->format('Y-m-d H:i:s')
                        : '';
                })

                ->rawColumns(['actions', 'diffBefore', 'diffAverage', 'harga_beli'])
                ->make(true);
        }
    }


    public function show(HargaEmas $hargaEmas)
    {
        $structure = $hargaEmas->structure();
        $data = $hargaEmas;

        $hargaHistoryData = $data->hargaEmasHistories()->limit(20)->get();

        return view('hargaemas.show', array_merge($structure, ['data' => $data, 'hargaHistoryData' => $hargaHistoryData]));
    }

    public function create()
    {
        $structure = (new HargaEmas())->structure();
        // dd($structure);
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $hargaEmas = HargaEmas::create($validatedData);

        return redirect()->route('harga-emas.index')->with('success', 'Harga Emas created successfully.');
    }

    public function edit(HargaEmas $hargaEmas)
    {
        $structure = $hargaEmas->structure();
        $data = $hargaEmas;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, HargaEmas $hargaEmas)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $hargaEmas->update($validatedData);

        return redirect()->route('harga-emas.index')->with('success', 'Harga Emas updated successfully.');
    }

    public function destroy(HargaEmas $hargaEmas)
    {
        $hargaEmas->delete();
        return redirect()->route('harga-emas.index')->with('success', 'Harga Emas deleted successfully.');
    }
}
