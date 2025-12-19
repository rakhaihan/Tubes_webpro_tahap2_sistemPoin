@extends('layouts.app')

@section('title','Tambah Pembinaan')

@section('content')
<div class="card">
    <div class="card-body">
        <h5>Tambah Pembinaan</h5>
        <form method="POST" action="{{ route('pembinaan.store') }}">
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
                <label class="form-label">Hukuman</label>
                <input name="judul" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Mulai</label>
                <input name="tanggal_mulai" type="date" class="form-control">
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
