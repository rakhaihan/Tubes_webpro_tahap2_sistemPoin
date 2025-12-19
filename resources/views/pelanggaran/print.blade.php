<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print Riwayat Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h4>Riwayat Pelanggaran</h4>
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
</div>
<script>
    window.onload = function(){ window.print(); }
</script>
</body>
</html>
