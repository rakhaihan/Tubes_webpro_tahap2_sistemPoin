@extends('layouts.app')

@section('title','Edit Pembinaan')

@section('content')
<div class="card">
    <div class="card-body">
        <h5>Edit Pembinaan</h5>
        <form method="POST" action="{{ route('pembinaan.update', $pembinaan) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Murid</label>
                <select name="user_id" class="form-select" required>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ $pembinaan->user_id == $s->id ? 'selected' : '' }}>{{ $s->name }} - {{ $s->nis }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Hukuman</label>
                <input name="judul" class="form-control" value="{{ $pembinaan->judul }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Mulai</label>
                <input name="tanggal_mulai" type="date" class="form-control" value="{{ $pembinaan->tanggal_mulai ? $pembinaan->tanggal_mulai->format('Y-m-d') : '' }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="terbuka" {{ $pembinaan->status=='terbuka' ? 'selected':'' }}>Terbuka</option>
                    <option value="selesai" {{ $pembinaan->status=='selesai' ? 'selected':'' }}>Selesai</option>
                </select>
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
