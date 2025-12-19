<?php

namespace App\Http\Controllers;

use App\Models\Pelanggaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PelanggaranController extends Controller
{
    public function create()
    {
        // Admin & Guru only
        if (!in_array(auth()->user()->role, ['admin', 'guru'])) abort(403);
        $students = User::where('role','siswa')->orderBy('name')->get();
        return view('pelanggaran.create', compact('students'));
    }

    public function store(Request $request)
    {
        // Admin & Guru only
        if (!in_array(auth()->user()->role, ['admin', 'guru'])) abort(403);
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'kategori' => 'required|in:ringan,sedang,berat',
            'jenis' => 'required|string',
            'keterangan' => 'nullable|string',
        ]);

        $points = match($data['kategori']) {
            'ringan' => 1,
            'sedang' => 3,
            'berat' => 5,
        };

        Pelanggaran::create([
            'user_id' => $data['user_id'],
            'kategori' => $data['kategori'],
            'jenis' => $data['jenis'],
            'keterangan' => $data['keterangan'] ?? null,
            'points' => $points,
        ]);

        return redirect()->route('pelanggaran.index')->with('success','Pelanggaran dicatat');
    }

    public function index(Request $request)
    {
        $q = $request->get('q');
        $pelanggarans = Pelanggaran::with('user')
            ->when($q, fn($query) => $query->whereHas('user', fn($uq)=>$uq->where('name','like','%'.$q.'%')))
            ->orderBy('created_at','desc')
            ->paginate(20);

        // statistik kategori untuk grafik
        $riwayatKategori = Pelanggaran::select('kategori', DB::raw('count(*) as cnt'))
            ->groupBy('kategori')
            ->pluck('cnt','kategori')->toArray();

        // siswa terakhir ditambahkan
        $lastStudents = \App\Models\User::where('role','siswa')->orderBy('created_at','desc')->limit(5)->get();

        return view('pelanggaran.index', compact('pelanggarans','q','riwayatKategori','lastStudents'));
    }

    public function print()
    {
        $pelanggarans = Pelanggaran::with('user')->orderBy('created_at','desc')->get();
        return view('pelanggaran.print', compact('pelanggarans'));
    }
}
