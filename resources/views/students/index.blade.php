@extends('layouts.app')

@section('title','Data Murid')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4>Data Murid</h4>
    <div class="d-flex">
        <form class="me-2" method="GET" action="{{ route('students.index') }}">
            <div class="input-group">
                <input name="q" value="{{ $q }}" class="form-control form-control-sm" placeholder="Cari murid...">
                <button class="btn btn-sm btn-outline-secondary">Cari</button>
            </div>
        </form>
        @if(in_array(auth()->user()->role, ['admin', 'guru']))
        <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">Tambah Murid</a>
        @endif
    </div>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif

<table class="table table-sm">
    <thead>
        <tr>
            <th>Nama</th>
            <th>NIS</th>
            <th>Kelas</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($students as $s)
        <tr>
            <td>{{ $s->name }}</td>
            <td>{{ $s->nis }}</td>
            <td>{{ $s->kelas }}</td>
            <td>
                @if($s->status === 'aktif')
                    <span class="badge bg-success">Aktif</span>
                @elseif($s->status === 'pembinaan')
                    <span class="badge bg-warning text-dark">Pembinaan</span>
                @else
                    <span class="badge bg-secondary">{{ $s->status }}</span>
                @endif
            </td>
            <td>
                @if(in_array(auth()->user()->role, ['admin', 'guru']))
                <a href="{{ route('students.edit', $s) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                <form method="POST" action="{{ route('students.destroy', $s) }}" class="d-inline" onsubmit="return confirm('Hapus murid?')">
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

{{ $students->withQueryString()->links() }}
@endsection
