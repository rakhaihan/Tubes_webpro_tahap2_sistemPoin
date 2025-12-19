@extends('layouts.app')

@section('title','Tambah Murid')

@section('content')
<div class="card">
    <div class="card-body">
        <h5>Tambah Murid</h5>
        <form method="POST" action="{{ route('students.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">NIS</label>
                <input name="nis" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <input name="kelas" class="form-control">
            </div>
            <div class="alert alert-info">Username akan di-generate otomatis dari NIS dan password default adalah <strong>siswa123</strong>.</div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="aktif">Aktif</option>
                    <option value="pembinaan">Pembinaan</option>
                </select>
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
