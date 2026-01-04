<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Sanksi & Pembinaan - Sistem Poin</title>
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
        <a href="/catatan-pelanggaran">Catatan & Laporan</a>
        <a href="/sanksi-pembinaan" class="active">Sanksi & Pembinaan</a>
      </nav>
      <div class="sidebar-foot">
        <button class="btn" data-logout>Logout</button>
      </div>
    </aside>

    <main class="main">
      <header class="header">
        <h1>Sanksi & Pembinaan</h1>
      </header>

      <section class="card">
        <div class="row space-between">
          <h3>Daftar Pembinaan</h3>
          <button id="addSanctionBtn" class="btn primary">+ Tambah Sanksi</button>
        </div>
        <table id="sanctionsTable">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Poin</th>
              <th>Sanksi</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </section>
    </main>
  </div>

  <!-- Modal -->
  <div class="modalSP" id="sanctionModal">
    <div class="modalSP-content">
      <div class="modalSP-header">
        <h3>Tambah Sanksi</h3>
        <button class="close-btn" id="closeModal">&times;</button>
      </div>

      <form id="sanctionForm">
        <label class="label">Pilih Murid</label>
        <select id="sanctionStudent" class="input"></select>

        <label class="label">Jenis Sanksi (otomatis)</label>
        <input id="sanctionType" class="input" readonly />

        <label class="label">Tanggal</label>
        <input id="sanctionDate" type="date" class="input" />

        <label class="label">Status Pembinaan</label>
        <select id="sanctionStatus" class="input">
          <option value="aktif">Aktif</option>
          <option value="pembinaan">Pembinaan</option>
          <option value="selesai">Selesai</option>
        </select>

        <div class="row" style="margin-top: 15px;">
          <button class="btn primary" type="submit">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <script src="{{ asset('/js/core.js') }}"></script>
  <script src="{{ asset('/js/sanksi-pembinaan.js') }}"></script>
</body>
</html>
