<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\HargaEmas\HargaEmas;
use App\Models\Pembelian\Pembelian;
use App\Models\Transaksi;
use App\Models\TransaksiPembelian;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PembelianController extends Controller
{
    protected Pembelian $model;


    public function __construct()
    {
        $this->model = new Pembelian();
    }


    public function index()
    {
        $structure = (new Pembelian())->structure();
        return view('dynamiccrud.index', $structure);
    }

    public function indexTable(Request $request)
    {
        if (request()->ajax()) {

            $pembelians = Pembelian::query();

            return DataTables::of($pembelians)
                ->addIndexColumn()
                ->editColumn('tanggal', function ($pembelian) {
                    return $pembelian->tanggal ? $pembelian->tanggal->format('Y-m-d') : '';
                })
                ->addColumn('actions', function ($pembelian) {
                    $actions = '';

                    if (auth()->user()->hasPermission('show-pembelians')) {
                        $actions .= '<a href="' . route('pembelians.show', $pembelian) . '" class="text-green-600 dark:text-green-400 hover:underline mr-3">View</a>';
                    }

                    if (auth()->user()->hasPermission('edit-pembelians')) {
                        $actions .= '<a href="' . route('pembelians.edit', $pembelian) . '" class="text-blue-600 dark:text-blue-400 hover:underline mr-3">Edit</a>';
                    }

                    if (auth()->user()->hasPermission('delete-pembelians')) {
                        $actions .= '<form action="' . route('pembelians.destroy', $pembelian) . '" method="POST" class="inline" onsubmit="return confirm(\'Are you sure?\')">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Delete</button>
                        </form>';
                    }

                    return $actions;
                })
                ->editColumn('created_at', function ($pembelian) {
                    return $pembelian->created_at ? $pembelian->created_at->format('Y-m-d H:i:s') : '';
                })
                ->rawColumns(['actions',])
                ->make(true);
        }
    }

    public function show(Pembelian $pembelian)
    {
        $structure = $pembelian->structure();
        $data = $pembelian;
        $transaksi = $pembelian->transaksiOne();
        // dd(array_merge($structure, ['data' => $data, 'pembelian' => $pembelian, 'transaksi' => $transaksi]));
        return view('pembelians.show', array_merge($structure, ['data' => $data, 'pembelian' => $pembelian, 'transaksi' => $transaksi]));
    }

    public function create()
    {
        $structure = (new Pembelian())->structure();
        $hargaEmass = HargaEmas::get();
        $asal_emas_options = [];
        foreach ($hargaEmass as $hargaEmas) {
            $asal_emas_options[$hargaEmas->id] = $hargaEmas->emas_asal . ' - ' . $hargaEmas->kadar . 'K';
        }
        $asal_emas_data = [];

        foreach ($hargaEmass as $hargaEmas) {
            $asal_emas_data[$hargaEmas->id] = [
                'emas_asal' => $hargaEmas->emas_asal,
                'kadar' => $hargaEmas->kadar,
                'satuan' => $hargaEmas->satuan,
                'jumlah' => $hargaEmas->jumlah,
                'harga_per_satuan' => $hargaEmas->harga_beli,
            ];
        }


        return view('pembelians.create', array_merge($structure, ['asal_emas_options' => $asal_emas_options, 'asal_emas_data' => $asal_emas_data]));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules(),

            'details' => ['required', 'array', 'min:1'],
            'details.*.emas_asal' => ['required', 'string'],
            'details.*.kadar' => ['required'],
            'details.*.satuan' => ['required', 'string'],
            'details.*.jumlah' => ['required', 'numeric'],
            'details.*.harga_per_satuan' => ['required', 'numeric'],
            'details.*.total_harga' => ['required', 'numeric'],
        ]);

        DB::transaction(function () use ($validatedData) {

            // Simpan data pembelian
            $pembelian = Pembelian::create(
                collect($validatedData)->except('details')->toArray()
            );

            // Simpan semua detail pembelian
            $pembelian->details()->createMany(
                $validatedData['details']
            );

            //jika status pembelian adalah 'completed', buat transaksi
            if (isset($validatedData['status']) && $validatedData['status'] === 'completed') {
                // Buat data transaksi terkait pembelian
                $transaksi = Transaksi::create([
                    'type' => 'OUT',
                    'transaksi_kategori_id' => 4, // Ganti dengan ID kategori yang sesuai
                    'tanggal' => now(),
                    'toko_id' => 1, // TODO:Ganti dengan ID toko yang sesuai
                    'nominal' => $pembelian->totalHarga(),
                    'keterangan' => 'Pembelian #' . $pembelian->no_invoice,
                ]);

                $pembelian->transaksi()->attach($transaksi->id);
            }
        });

        return redirect()->route('pembelians.index')->with('success', 'Pembelian created successfully.');
    }

    public function edit(Pembelian $pembelian)
    {
        $structure = $pembelian->structure();
        $data = $pembelian->load('details');
        $hargaEmass = HargaEmas::get();
        $asal_emas_options = [];
        foreach ($hargaEmass as $hargaEmas) {
            $asal_emas_options[$hargaEmas->id] = $hargaEmas->emas_asal . ' - ' . $hargaEmas->kadar . 'K';
        }
        $asal_emas_data = [];

        foreach ($hargaEmass as $hargaEmas) {
            $asal_emas_data[$hargaEmas->id] = [
                'emas_asal' => $hargaEmas->emas_asal,
                'kadar' => $hargaEmas->kadar,
                'satuan' => $hargaEmas->satuan,
                'jumlah' => $hargaEmas->jumlah,
                'harga_per_satuan' => $hargaEmas->harga_beli,
            ];
        }

        return view('pembelians.edit', array_merge($structure, ['data' => $data, 'asal_emas_options' => $asal_emas_options, 'asal_emas_data' => $asal_emas_data]));
    }

    public function update(Request $request, Pembelian $pembelian)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules(),

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
        

        DB::transaction(function () use ($validatedData, $pembelian, $perlu_buat_transaksi) {

            

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
                    'toko_id' => 1, // TODO:Ganti dengan ID toko yang sesuai
                    'nominal' => $pembelian->totalHarga(),
                    'keterangan' => 'Pembelian #' . $pembelian->no_invoice,
                ]);
            }
        });

        return redirect()->route('pembelians.index')->with('success', 'Pembelian updated successfully.');
    }

    public function destroy(Pembelian $pembelian)
    {
        $pembelian->delete();
        return redirect()->route('pembelians.index')->with('success', 'Pembelian deleted successfully.');
    }
}
