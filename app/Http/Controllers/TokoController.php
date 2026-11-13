<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Yajra\DataTables\Facades\DataTables;

// this is not real controller, just a template for dynamic CRUD operations
class TokoController extends Controller
{
    protected Toko $model;


    public function __construct()
    {
        $this->model = new Toko();
    }


    public function index()
    {
        $structure = (new Toko())->structure();
        $structure['tablename'] = 'bla';
        // dd($structure);
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $tokos = Toko::query();
            
            return DataTables::of($tokos)
                ->addIndexColumn()
                
                ->addColumn('actions', function ($toko) {
                    $actions = '';
                    
                    if (auth()->user()->hasPermission('show-tokos')) {
                        $actions .= '<a href="' . route('tokos.show', $toko) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }
                    
                    if (auth()->user()->hasPermission('edit-tokos')) {
                        $actions .= '<a href="' . route('tokos.edit', $toko) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }
                    
                    if (auth()->user()->hasPermission('delete-tokos')) {
                        $actions .= '<form action="' . route('tokos.destroy', $toko) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }
                    
                    return $actions;
                })
                
                ->rawColumns(['actions'])
                ->make(true);
        }
    }

    public function show(Toko $toko)
    {
        $structure = $toko->structure();
        $data = $toko;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new Toko())->structure();
        // dd($structure);
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $toko = Toko::create($validatedData);

        return redirect()->route('tokos.index')->with('success', 'Toko created successfully.');
    }

    public function edit(Toko $toko)
    {
        $structure = $toko->structure();
        $data = $toko;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, Toko $toko)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $toko->update($validatedData);

        return redirect()->route('tokos.index')->with('success', 'Toko updated successfully.');
    }

    public function destroy(Toko $toko)
    {
        $toko->delete();
        return redirect()->route('tokos.index')->with('success', 'Toko deleted successfully.');
    }
}
