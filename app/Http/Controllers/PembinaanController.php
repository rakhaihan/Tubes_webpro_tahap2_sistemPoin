<?php

namespace App\Http\Controllers;

use App\Models\Pembinaan;
use App\Models\User;
use Illuminate\Http\Request;

class PembinaanController extends Controller
{
    public function index()
    {
        // Admin only
        if (auth()->user()->role !== 'admin') abort(403);
        $pembinaans = Pembinaan::with('user')->orderBy('created_at','desc')->paginate(20);
        return view('pembinaan.index', compact('pembinaans'));
    }

    public function create()
    {
        // Admin only
        if (auth()->user()->role !== 'admin') abort(403);
        $students = User::where('role','siswa')->orderBy('name')->get();
        return view('pembinaan.create', compact('students'));
    }

    public function store(Request $request)
    {
        // Admin only
        if (auth()->user()->role !== 'admin') abort(403);
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'judul' => 'required|string',
            'tanggal_mulai' => 'nullable|date',
        ]);

        Pembinaan::create($data);
        // optionally set user status to pembinaan
        $user = User::find($data['user_id']);
        if($user) $user->update(['status'=>'pembinaan']);

        return redirect()->route('pembinaan.index')->with('success','Pembinaan ditambahkan');
    }

    public function edit(Pembinaan $pembinaan)
    {
        // Admin only
        if (auth()->user()->role !== 'admin') abort(403);
        $students = User::where('role','siswa')->orderBy('name')->get();
        return view('pembinaan.edit', compact('pembinaan','students'));
    }

    public function update(Request $request, Pembinaan $pembinaan)
    {
        // Admin only
        if (auth()->user()->role !== 'admin') abort(403);
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'judul' => 'required|string',
            'tanggal_mulai' => 'nullable|date',
            'status' => 'required|in:terbuka,selesai',
        ]);

        $pembinaan->update($data);

        // update user status accordingly
        $user = User::find($data['user_id']);
        if($user) $user->update(['status' => $data['status'] === 'terbuka' ? 'pembinaan' : 'aktif']);

        return redirect()->route('pembinaan.index')->with('success','Pembinaan diperbarui');
    }

    public function destroy(Pembinaan $pembinaan)
    {
        // Admin only
        if (auth()->user()->role !== 'admin') abort(403);
        $pembinaan->delete();
        return redirect()->route('pembinaan.index')->with('success','Pembinaan dihapus');
    }
}
