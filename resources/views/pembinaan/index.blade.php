@extends('layouts.app')

@section('title','Sanksi & Pembinaan')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Sanksi & Pembinaan</h4>
    @if(auth()->user()->role === 'admin')
    <a href="{{ route('pembinaan.create') }}" class="btn btn-primary btn-sm">Tambah Pembinaan</a>
    @endif
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<table class="table table-sm">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Murid</th>
            <th>Judul</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pembinaans as $p)
        <tr>
            <td>{{ $p->created_at->format('Y-m-d') }}</td>
            <td>{{ $p->user->name }} ({{ $p->user->nis }})</td>
            <td>{{ $p->judul }}</td>
            <td>
                @if($p->status === 'terbuka')
                    <span class="badge bg-warning text-dark">Terbuka</span>
                @elseif($p->status === 'selesai')
                    <span class="badge bg-success">Selesai</span>
                @else
                    <span class="badge bg-secondary">{{ $p->status }}</span>
                @endif
            </td>
            <td>
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('pembinaan.edit', $p) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form method="POST" action="{{ route('pembinaan.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Hapus pembinaan?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $pembinaans->links() }}
@endsection
