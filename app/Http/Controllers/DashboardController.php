<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggaran = Pelanggaran::count();

        // compute points per user
        $pointsPerUser = Pelanggaran::select('user_id', DB::raw('SUM(points) as total'))
            ->groupBy('user_id');

        $threshold = 10;
        $exceeding = DB::table(DB::raw("({$pointsPerUser->toSql()}) as t"))
            ->mergeBindings($pointsPerUser->getQuery())
            ->where('total', '>', $threshold)
            ->count();

        $inPembinaan = User::where('role','siswa')->where('status','pembinaan')->count();

        $riwayatKategori = Pelanggaran::select('kategori', DB::raw('count(*) as cnt'))
            ->groupBy('kategori')
            ->pluck('cnt','kategori')->toArray();

        $lastStudents = User::where('role','siswa')->orderBy('created_at','desc')->limit(5)->get();

        $data = compact('totalPelanggaran','exceeding','inPembinaan','riwayatKategori','lastStudents');

        // Return the same dashboard data view for admin, guru, and siswa
        $role = auth()->user()->role ?? 'admin';
        if ($role === 'guru') {
            return view('dashboards.guru', $data);
        }
        if ($role === 'siswa') {
            return view('dashboards.siswa', $data);
        }

        return view('dashboards.admin', $data);
    }
}
