<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('log_aktivitas')
            ->join('users', 'log_aktivitas.id_users', '=', 'users.id_users')
            ->join('cabang', 'log_aktivitas.id_cabang', '=', 'cabang.id_cabang')
            ->select('log_aktivitas.*', 'users.nama', 'cabang.nama_cabang')
            ->orderBy('log_aktivitas.waktu_aktivitas', 'desc');

        // If not superadmin, filter by user's cabang
        if (auth()->user()->role !== 'Superadmin') {
            $query->where('log_aktivitas.id_cabang', auth()->user()->id_cabang);
        }

        // Filter berdasarkan waktu
        if ($request->has('filter')) {
            $today = Carbon::today();

            switch ($request->filter) {
                case 'today':
                    $query->whereDate('log_aktivitas.waktu_aktivitas', $today);
                    break;
                case 'this_month':
                    $query->whereMonth('log_aktivitas.waktu_aktivitas', $today->month)
                          ->whereYear('log_aktivitas.waktu_aktivitas', $today->year);
                    break;
                case 'last_month':
                    $lastMonth = $today->copy()->subMonth();
                    $query->whereMonth('log_aktivitas.waktu_aktivitas', $lastMonth->month)
                          ->whereYear('log_aktivitas.waktu_aktivitas', $lastMonth->year);
                    break;
            }
        }

        $activities = $query->paginate(10)->withQueryString();

        return view('activities.index', compact('activities'));
    }
}
