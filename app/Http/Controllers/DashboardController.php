<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembelian\Pembelian;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUser = \App\Models\User::count() - 1;



        $totalRevenue = new Transaksi()->currentSaldoAll();
        $totalOrders = Pembelian::where('status', 'completed')->count();

        //ambil dari table session
        $totalVisitors = DB::table('sessions')->count();

        return view('dashboard', compact('totalUser', 'totalRevenue', 'totalOrders', 'totalVisitors'));
    }
}
