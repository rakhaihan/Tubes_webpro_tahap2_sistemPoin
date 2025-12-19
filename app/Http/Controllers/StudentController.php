<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        // Admin & Guru only
        if (!in_array(auth()->user()->role, ['admin', 'guru'])) abort(403);
        $q = $request->get('q');
        $students = User::where('role','siswa')
            ->when($q, fn($query) => $query->where(function($qq) use ($q){
                $qq->where('name','like','%'.$q.'%')
                   ->orWhere('nis','like','%'.$q.'%')
                   ->orWhere('kelas','like','%'.$q.'%');
            }))
            ->orderBy('created_at','desc')
            ->paginate(15);

        return view('students.index', compact('students','q'));
    }

    public function create()
    {
        // Admin & Guru only
        if (!in_array(auth()->user()->role, ['admin', 'guru'])) abort(403);
        return view('students.create');
    }

    public function store(Request $request)
    {
        // Admin & Guru only
        if (!in_array(auth()->user()->role, ['admin', 'guru'])) abort(403);
        $data = $request->validate([
            'name' => 'required|string',
            'nis' => 'required|string|unique:users,nis',
            'kelas' => 'nullable|string',
            'status' => 'required|in:aktif,pembinaan',
        ]);

        // generate username from NIS and default password
        $username = $data['nis'];
        $passwordPlain = 'siswa123';

        User::create([
            'name' => $data['name'],
            'username' => $username,
            'nis' => $data['nis'],
            'kelas' => $data['kelas'] ?? null,
            'role' => 'siswa',
            'status' => $data['status'],
            'password' => bcrypt($passwordPlain),
            'email' => $username.'@example.local',
        ]);

        return redirect()->route('students.index')->with('success',"Murid ditambahkan. Username: {$username}, Password: {$passwordPlain}");
    }

    public function edit(User $student)
    {
        // Admin & Guru only
        if (!in_array(auth()->user()->role, ['admin', 'guru'])) abort(403);
        return view('students.edit', compact('student'));
    }

    public function update(Request $request, User $student)
    {
        // Admin & Guru only
        if (!in_array(auth()->user()->role, ['admin', 'guru'])) abort(403);
        $data = $request->validate([
            'name' => 'required|string',
            'nis' => 'required|string|unique:users,nis,'.$student->id,
            'kelas' => 'nullable|string',
            'username' => 'required|string|unique:users,username,'.$student->id,
            'status' => 'required|in:aktif,pembinaan',
        ]);

        $student->update($data);
        return redirect()->route('students.index')->with('success','Murid diperbarui');
    }

    public function destroy(User $student)
    {
        // Admin & Guru only
        if (!in_array(auth()->user()->role, ['admin', 'guru'])) abort(403);
        $student->delete();
        return redirect()->route('students.index')->with('success','Murid dihapus');
    }
}
