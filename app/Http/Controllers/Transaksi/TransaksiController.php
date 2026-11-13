<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Yajra\DataTables\Facades\DataTables;

// this is not real controller, just a template for dynamic CRUD operations
class TransaksiController extends Controller
{
    protected Transaksi $model;


    public function __construct()
    {
        $this->model = new Transaksi();
    }


    public function index()
    {
        $structure = (new Transaksi())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $transaksis = Transaksi::query();
            
            return DataTables::of($transaksis)
                ->addIndexColumn()
                
                ->addColumn('actions', function ($row) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-transaksis')) {
                        $actions .= '<a href="' . route('transaksis.show', $row) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-transaksis')) {
                        $actions .= '<a href="' . route('transaksis.edit', $row) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-transaksis')) {
                        $actions .= '<form action="' . route('transaksis.destroy', $row) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('tanggal', function ($row) {
                    return $row->tanggal ? $row->tanggal->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function show(Transaksi $transaksi)
    {
        $structure = $transaksi->structure();
        $data = $transaksi;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new Transaksi())->structure();
        // dd($structure);
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $transaksi = Transaksi::create($validatedData);

        return redirect()->route('transaksis.index')->with('success', 'Transaksi created successfully.');
    }

    public function edit(Transaksi $transaksi)
    {
        $structure = $transaksi->structure();
        $data = $transaksi;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $transaksi->update($validatedData);

        return redirect()->route('transaksis.index')->with('success', 'Transaksi updated successfully.');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return redirect()->route('transaksis.index')->with('success', 'Transaksi deleted successfully.');
    }
}
