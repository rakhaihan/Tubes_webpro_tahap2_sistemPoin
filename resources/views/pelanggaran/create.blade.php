@extends('layouts.app')

@section('title','Catat Pelanggaran')

@section('content')
<div class="card">
    <div class="card-body">
        <h5>Catat Pelanggaran</h5>
        <form method="POST" action="{{ route('pelanggaran.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Murid</label>
                <select name="user_id" class="form-select" required>
                    <option value="">Pilih murid</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} - {{ $s->nis }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="ringan">Ringan</option>
                    <option value="sedang">Sedang</option>
                    <option value="berat">Berat</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Jenis</label>
                <input name="jenis" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control"></textarea>
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
