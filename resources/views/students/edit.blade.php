@extends('layouts.app')

@section('title','Edit Murid')

@section('content')
<div class="card">
    <div class="card-body">
        <h5>Edit Murid</h5>
        <form method="POST" action="{{ route('students.update', $student) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input name="name" value="{{ $student->name }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">NIS</label>
                <input name="nis" value="{{ $student->nis }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <input name="kelas" value="{{ $student->kelas }}" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input name="username" value="{{ $student->username }}" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="aktif" {{ $student->status=='aktif' ? 'selected':'' }}>Aktif</option>
                    <option value="pembinaan" {{ $student->status=='pembinaan' ? 'selected':'' }}>Pembinaan</option>
                </select>
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
