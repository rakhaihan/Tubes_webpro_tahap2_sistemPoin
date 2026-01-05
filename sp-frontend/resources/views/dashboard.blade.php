<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard - Sistem Poin</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <div id="appContainer" class="app">
    <aside class="sidebar">
      <div class="brand-compact">
        <div class="logo">SP</div>
        <h2>SMK Merdeka</h2>
      </div>

      <nav class="menu" id="mainMenu">
        <a href="/dashboard" class="active">Dashboard</a>
        <a href="/data-murid">Data Murid</a>
        <a href="/catatan-pelanggaran">Catatan & Laporan</a>
        <a href="/sanksi-pembinaan">Sanksi & Pembinaan</a>
      </nav>

      <div class="sidebar-foot">
        <button class="btn" data-logout>Logout</button>
      </div>
    </aside>

    <main class="main">
      <header class="header">
        <h1>Dashboard</h1>
        <p class="muted">Ringkasan singkat pelanggaran dan status pembinaan</p>
      </header>

      <section class="grid grid-3">
        <div class="card stat">
          <h3>Total Pelanggaran</h3>
          <p id="statTotal" class="big">0</p>
        </div>

        <div class="card stat">
          <h3>Siswa Dengan Poin ≥ Batas</h3>
          <p id="statAlert" class="big">0</p>
        </div>

        <div class="card stat">
          <h3>Siswa Dalam Pembinaan</h3>
          <p id="statPembinaan" class="big">0</p>
        </div>
      </section>

      <section class="card">
        <h3>Riwayat Pelanggaran</h3>
        <canvas id="chartCanvas" width="800" height="240"></canvas>
      </section>

      <section class="card">
        <h3>Terakhir Ditambahkan</h3>
        <table id="recentTable">
          <thead>
            <tr><th>Nama</th><th>Jenis</th><th>Tanggal</th></tr>
          </thead>
          <tbody></tbody>
        </table>
      </section>
    </main>
  </div>

  <script src="{{ asset('/js/core.js') }}"></script>
  <script src="{{ asset('https://cdn.jsdelivr.net/npm/chart.js') }}"></script>
  <script src="{{ asset('/js/dashboard.js') }}"></script>
</body>
</html>
