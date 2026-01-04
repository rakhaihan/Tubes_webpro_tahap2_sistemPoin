<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Catatan & Laporan - Sistem Poin</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <div class="app">
    <aside class="sidebar">
      <div class="brand-compact">
        <div class="logo">SP</div>
        <h2>SMK Merdeka</h2>
      </div>
      <nav class="menu">
        <a href="/dashboard">Dashboard</a>
        <a href="/data-murid">Data Murid</a>
        <a href="/catatan-pelanggaran" class="active">Catatan & Laporan</a>
        <a href="/sanksi-pembinaan">Sanksi & Pembinaan</a>
      </nav>
      <div class="sidebar-foot">
        <button class="btn" data-logout>Logout</button>
      </div>
    </aside>

    <main class="main">
  <header class="header">
    <h1>Catatan Pelanggaran</h1>
    <p class="muted">Tambah catatan, lihat riwayat, dan cetak laporan</p>
  </header>

  <section class="card form-section">
    <h3>Tambah Catatan Pelanggaran</h3>
    <form id="violationForm">
      <label class="label">Pilih Murid</label>
      <select id="violationStudent" class="input" required></select>

      <label class="label">Jenis Pelanggaran</label>
      <select id="violationType" class="input" required>
        <option value="">-- Pilih Pelanggaran --</option>
        <option value="2">Membuang Sampah Sembarangan</option>
        <option value="5">Tidak Membawa Buku</option>
        <option value="5">Tidak Memakai Seragam Lengkap</option>
        <option value="10">Tidak Ikut Upacara</option>
        <option value="10">Terlambat Masuk</option>
        <option value="10">Keluar Lingkungan Sekolah</option>
        <option value="15">Membolos Tanpa Alasan</option>
        <option value="20">Merokok</option>
        <option value="20">Membawa Senjata Tajam</option>
        <option value="25">Berkelahi Dengan Siswa Lain</option>
        <option value="25">Merusak Fasilitas Sekolah</option>
        <option value="30">Mengonsumsi Narkotika</option>
        <option value="30">Melakukan Tindakan Asusila</option>
      </select>

      <label class="label">Tanggal</label>
      <input id="violationDate" type="date" class="input" required />

      <label class="label">Keterangan</label>
      <textarea id="violationNote" class="input" rows="2"></textarea>

      <label class="label">Poin (angka)</label>
      <input id="violationPoints" type="text" class="input" readonly />

      <div class="row" style="margin-top:12px;">
        <button class="btn primary" type="submit">Simpan</button>
        <button type="button" id="clearViolations" class="btn ghost">Bersihkan Semua</button>
      </div>
    </form>
  </section>

  <section class="card">
    <h3>Riwayat Pelanggaran</h3>

    <div class="row space-between">
      <input id="searchViolation" class="input small" placeholder="Cari nama/jenis..." />
      <button id="printReport" type="button" class="btn">Cetak Riwayat</button>
    </div>

    <table id="violationsTable">
      <thead>
        <tr>
          <th>Nama</th>
          <th>Jenis</th>
          <th>Tanggal</th>
          <th>Poin</th>
          <th>Keterangan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </section>
  <section class="card">
    <h3>Statistik Pelanggaran</h3>
    <canvas id="chartCanvas2" width="800" height="240"></canvas>
  </section>
</main>

  </div>

  <script src="{{ asset('/js/core.js') }}"></script>
  <script src="{{ asset('/js/catatan-pelanggaran.js') }}"></script>
  <script src="{{ asset('https://cdn.jsdelivr.net/npm/chart.js') }}"></script>
</body>
</html>
