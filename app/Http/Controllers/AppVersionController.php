<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Support\Facades\Storage;
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
                ->editColumn('url_apk_file', function ($row) {
                    // buka modal untuk form untuk mengisi kode_akses / password, nanti routing ke download
                    return '<button class="text-blue-600 dark:text-blue-400 hover:underline" onclick="openDownloadModal(' . $row->id . ')">Download</button>';
                })
                ->addColumn('actions', function ($row) {
                    $actions = '';
                    if(!auth()->check()){
                        return '';
                    }

                    if (auth()->user()->hasPermission('show-app-versions')) {
                        $actions .= '<a href="' . route('app-versions.show', $row) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }

                    if (auth()->user()->hasPermission('edit-app-versions')) {
                        $actions .= '<a href="' . route('app-versions.edit', $row) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }

                    if (auth()->user()->hasPermission('delete-app-versions')) {
                        $actions .= '<form action="' . route('app-versions.destroy', $row) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }

                    return $actions;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions', 'url_apk_file'])
                ->make(true);
        }
    }

    public function download(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:app_versions,id',
            'kode_akses' => 'nullable|string|max:255',
        ]);

        $object = AppVersion::findOrFail($request->id);

        if ($object->kode_akses && $object->kode_akses !== $request->kode_akses) {
            return redirect()->back()->with('error', 'Invalid access code.');
        }


        if ($object->url_apk_file && Storage::disk('public')->exists('apk_files/' . $object->url_apk_file)) {
            return Storage::disk('public')->download('apk_files/' . $object->url_apk_file);
        }

        return redirect()->back()->with('error', 'File not found.');
    }

    public function show(AppVersion $appVersion)
    {
        $structure = $appVersion->structure();
        $data = $appVersion;
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

        //file handling
        if ($request->has('url_apk_file')) {
            // dd('masuk sini');

            //format file = tngold_{versi}.apk

            $file = $request->file('url_apk_file');
            $filename = 'tngold_' . $validatedData['version'] . '.' . $file->getClientOriginalExtension();
            $file->storeAs('apk_files', $filename, 'public');
            $validatedData['url_apk_file'] = $filename;
        }
        else {
            //jika tida ada maka tetap generate
            $validatedData['url_apk_file'] = 'tngold_' . $validatedData['version'] . '.apk';
        }

        $object = AppVersion::create($validatedData);

        return redirect()->route('app-versions.index')->with('success', 'App Version created successfully.');
    }

    public function edit(AppVersion $appVersion)
    {
        $structure = $appVersion->structure();
        $data = $appVersion;
        return view('dynamiccrud.edit', array_merge($structure, ['data' => $data]));
    }

    public function update(Request $request, AppVersion $appVersion)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        //file handling
        if ($request->has('url_apk_file')) {
            // dd('masuk sini');

            //format file = tngold_{versi}.apk

            //delete old file if exists
            if ($appVersion->url_apk_file) {
                Storage::disk('public')->delete('apk_files/' . $appVersion->url_apk_file);
            }

            $file = $request->file('url_apk_file');
            $filename = 'tngold_' . $validatedData['version'] . '.' . $file->getClientOriginalExtension();
            $file->storeAs('apk_files', $filename, 'public');
            $validatedData['url_apk_file'] = $filename;
        }

        $appVersion->update($validatedData);

        return redirect()->route('app-versions.index')->with('success', 'App Version updated successfully.');
    }

    public function destroy(AppVersion $appVersion)
    {
        $appVersion->delete();
        return redirect()->route('app-versions.index')->with('success', 'App Version deleted successfully.');
    }
}
