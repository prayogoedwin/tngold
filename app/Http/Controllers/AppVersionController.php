<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Yajra\DataTables\Facades\DataTables;

class AppVersionController extends Controller
{
    protected AppVersion $model;


    public function __construct()
    {
        $this->model = new AppVersion();
    }


    public function index()
    {
        $structure = (new AppVersion())->structure();

        //jika sudah login maka beda view
        if (auth()->check()) {
            //jika role super-admin atau owner
            if (auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('owner')) {
                return view('appversions.index', $structure);
            }
        }

        return view('appversions.public-index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $objects = AppVersion::query();

            return DataTables::of($objects)
                ->addIndexColumn()
                ->addColumn('foreign_id', function ($row) {
                    return $row->foreign ? $row->foreign->name : '';
                })
                ->addColumn('actions', function ($row) {
                    $actions = '';

                    if (auth()->user()->hasPermission('show-template-studs')) {
                        $actions .= '<a href="' . route('template-studs.show', $row) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }

                    if (auth()->user()->hasPermission('edit-template-studs')) {
                        $actions .= '<a href="' . route('template-studs.edit', $row) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }

                    if (auth()->user()->hasPermission('delete-template-studs')) {
                        $actions .= '<form action="' . route('template-studs.destroy', $row) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }

                    return $actions;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function show(AppVersion $object)
    {
        $structure = $object->structure();
        $data = $object;
        return view('dynamiccrud.show', array_merge($structure, ['data' => $data]));
    }

    public function create()
    {
        $structure = (new AppVersion())->structure();
        // dd($structure);
        return view('dynamiccrud.create', $structure);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $object = AppVersion::create($validatedData);

        return redirect()->route('template-studs.index')->with('success', 'Template Stud created successfully.');
    }

    public function edit(AppVersion $object)
    {
        $structure = $object->structure();
        $data = $object;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, AppVersion $object)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        $object->update($validatedData);

        return redirect()->route('template-studs.index')->with('success', 'Template Stud updated successfully.');
    }

    public function destroy(AppVersion $object)
    {
        $object->delete();
        return redirect()->route('template-studs.index')->with('success', 'Template Stud deleted successfully.');
    }
}
