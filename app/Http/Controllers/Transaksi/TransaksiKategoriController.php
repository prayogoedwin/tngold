<?php

namespace App\Http\Controllers\Transaksi;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\TransaksiKategori;
use Yajra\DataTables\Facades\DataTables;

// this is not real controller, just a template for dynamic CRUD operations
class TransaksiKategoriController extends Controller
{
    protected TransaksiKategori $model;


    public function __construct()
    {
        $this->model = new TransaksiKategori();
    }


    public function index()
    {
        $structure = (new TransaksiKategori())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $transaksiKategoris = TransaksiKategori::query();
            
            return DataTables::of($transaksiKategoris)
                ->addIndexColumn()
                ->addColumn('foreign_id', function ($transaksiKategori) {
                    return $transaksiKategori->foreign ? $transaksiKategori->foreign->name : '';
                })
                ->addColumn('actions', function ($transaksiKategori) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-transaksi-kategoris')) {
                        $actions .= '<a href="' . route('transaksi-kategoris.show', $transaksiKategori) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-transaksi-kategoris')) {
                        $actions .= '<a href="' . route('transaksi-kategoris.edit', $transaksiKategori) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-transaksi-kategoris')) {
                        $actions .= '<form action="' . route('transaksi-kategoris.destroy', $transaksiKategori) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                ->editColumn('created_at', function ($transaksiKategori) {
                    return $transaksiKategori->created_at ? $transaksiKategori->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function show(TransaksiKategori $transaksiKategori)
    {
        $structure = $transaksiKategori->structure();
        $data = $transaksiKategori;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new TransaksiKategori())->structure();
        // dd($structure);
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $transaksiKategori = TransaksiKategori::create($validatedData);

        return redirect()->route('transaksi-kategoris.index')->with('success', 'Transaksi Kategori created successfully.');
    }

    public function edit(TransaksiKategori $transaksiKategori)
    {
        $structure = $transaksiKategori->structure();
        $data = $transaksiKategori;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, TransaksiKategori $transaksiKategori)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $transaksiKategori->update($validatedData);

        return redirect()->route('transaksi-kategoris.index')->with('success', 'Transaksi Kategori updated successfully.');
    }

    public function destroy(TransaksiKategori $transaksiKategori)
    {
        $transaksiKategori->delete();
        return redirect()->route('transaksi-kategoris.index')->with('success', 'Transaksi Kategori deleted successfully.');
    }
}
