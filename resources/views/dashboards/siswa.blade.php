@extends('layouts.app')

@section('title','Siswa Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5>Total Pelanggaran</h5>
                    <h3>{{ $totalPelanggaran ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">
                    <h5>Siswa Melebihi Batas</h5>
                    <h3>{{ $exceeding ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5>Siswa dalam Pembinaan</h5>
                    <h3>{{ $inPembinaan ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5>Terbaru</h5>
                    <h3>{{ $lastStudents->count() ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Grafik Pelanggaran</h5>
                    <canvas id="chartPelanggaran"></canvas>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartPelanggaran');
        if(ctx){
            const data = {
                labels: ['Ringan','Sedang','Berat'],
                datasets: [{
                    label: 'Jumlah',
                    data: [{{ $riwayatKategori['ringan'] ?? 0 }}, {{ $riwayatKategori['sedang'] ?? 0 }}, {{ $riwayatKategori['berat'] ?? 0 }}],
                    backgroundColor: ['#6c757d','#ffc107','#dc3545']
                }]
            };
            new Chart(ctx, { type: 'bar', data });
        }
    </script>
@endsection
