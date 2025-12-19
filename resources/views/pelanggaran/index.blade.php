@extends('layouts.app')

@section('title','Riwayat Pelanggaran')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <h4>Riwayat Pelanggaran</h4>
    </div>
    <div class="col-md-4 text-end">
        @if(in_array(auth()->user()->role, ['admin', 'guru']))
        <a href="{{ route('pelanggaran.create') }}" class="btn btn-primary btn-sm">Tambah</a>
        @endif
        <a href="{{ route('pelanggaran.print') }}" target="_blank" class="btn btn-outline-secondary btn-sm">Print</a>
    </div>
</div>

    <div>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Murid</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Points</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pelanggarans as $p)
                <tr>
                    <td>{{ $p->created_at->format('Y-m-d') }}</td>
                    <td>{{ $p->user->name }} ({{ $p->user->nis }})</td>
                    <td>{{ $p->kategori }}</td>
                    <td>{{ $p->jenis }}</td>
                    <td>{{ $p->points }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $pelanggarans->links() }}

        <div class="card mb-3">
            <div class="card-body">
                <h5>Grafik Pelanggaran</h5>
                <canvas id="chartPelanggaranFull"></canvas>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5>Siswa Terakhir Ditambahkan</h5>
                <ul class="list-group">
                    @foreach($lastStudents as $s)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>{{ $s->name }}</strong><br>
                                    {{ $s->nis }} - {{ $s->kelas }}
                                </div>
                                <div class="text-muted">{{ $s->created_at->format('Y-m-d') }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx2 = document.getElementById('chartPelanggaranFull');
        if(ctx2){
            const data2 = {
                labels: ['Ringan','Sedang','Berat'],
                datasets: [{
                    label: 'Jumlah',
                    data: [{{ $riwayatKategori['ringan'] ?? 0 }}, {{ $riwayatKategori['sedang'] ?? 0 }}, {{ $riwayatKategori['berat'] ?? 0 }}],
                    backgroundColor: ['#6c757d','#ffc107','#dc3545']
                }]
            };
            new Chart(ctx2, { type: 'bar', data: data2 });
        }
    </script>
@endsection
