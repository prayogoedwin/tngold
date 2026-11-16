<?php

namespace App\Http\Controllers\HargaEmas;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\HargaEmas\HargaEmasHistory;
use Yajra\DataTables\Facades\DataTables;

// this is not real controller, just a template for dynamic CRUD operations
class HargaEmasHistoryController extends Controller
{
    protected HargaEmasHistory $model;


    public function __construct()
    {
        $this->model = new HargaEmasHistory();
    }


    public function index()
    {
        $structure = (new HargaEmasHistory())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $hargaEmasHistory = HargaEmasHistory::query();
            
            return DataTables::of($hargaEmasHistory)
                ->addIndexColumn()
                ->addColumn('foreign_id', function ($hargaEmasHistory) {
                    return $hargaEmasHistory->hargaEmas ? $hargaEmasHistory->hargaEmas->name : '';
                })
                ->addColumn('actions', function ($hargaEmasHistory) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-harga-emas-history')) {
                        $actions .= '<a href="' . route('harga-emas-history.show', $hargaEmasHistory) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-harga-emas-history')) {
                        $actions .= '<a href="' . route('harga-emas-history.edit', $hargaEmasHistory) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-harga-emas-history')) {
                        $actions .= '<form action="' . route('harga-emas-history.destroy', $hargaEmasHistory) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($hargaEmasHistory) {
                    return $hargaEmasHistory->created_at ? $hargaEmasHistory->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function show(HargaEmasHistory $hargaEmasHistory)
    {
        $structure = $hargaEmasHistory->structure();
        $data = $hargaEmasHistory;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new HargaEmasHistory())->structure();
        // dd($structure);
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $hargaEmasHistory = HargaEmasHistory::create($validatedData);

        return redirect()->route('harga-emas-history.index')->with('success', 'Harga Emas History created successfully.');
    }

    public function edit(HargaEmasHistory $hargaEmasHistory)
    {
        $structure = $hargaEmasHistory->structure();
        $data = $hargaEmasHistory;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, HargaEmasHistory $hargaEmasHistory)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $hargaEmasHistory->update($validatedData);

        return redirect()->route('harga-emas-history.index')->with('success', 'Harga Emas History updated successfully.');
    }

    public function destroy(HargaEmasHistory $hargaEmasHistory)
    {
        $hargaEmasHistory->delete();
        return redirect()->route('harga-emas-history.index')->with('success', 'Harga Emas History deleted successfully.');
    }
}
