<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Login - Sistem Poin Pelanggaran</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-page">
  <main class="card login-card">
    <div class="brand">
      <div class="logo">SP</div>
      <h1>Sistem Poin Pelanggaran</h1>
      <p class="muted">Login</p>
    </div>

    <form id="loginForm" class="form">
      <label class="label">Username</label>
      <input id="username" class="input" placeholder="Masukkan Username" required />

      <label class="label">Password</label>
      <input id="password" type="password" class="input" placeholder="Masukkan Password" required />

      <!-- Role dihapus -->
      <!-- <label class="label">Role</label> -->

      <div class="row space-between" style="margin-top:12px">
        <button type="submit" class="btn primary">Login</button>
        <button type="reset" class="btn ghost">Reset</button>
      </div>
      <p id="loginError" class="error" aria-live="polite"></p>
    </form>

    <p class="foot muted">Catatan Pelanggaran SMK Merdeka.</p>
  </main>

 <script src="{{ asset('js/core.js') }}"></script>
  <script>
  const form = document.getElementById('loginForm');
  const errorMsg = document.getElementById('loginError');

  form.addEventListener('submit', async e => {
    e.preventDefault();

    const u = document.getElementById('username').value.trim();
    const p = document.getElementById('password').value.trim();

    errorMsg.textContent = '';

    if (!window.app || !app.auth) {
      errorMsg.textContent = 'Aplikasi belum siap.';
      return;
    }

    const ok = await app.auth.login(u, p);
    if (ok) {
      window.location.href = '/dashboard';
    } else {
      errorMsg.textContent = 'Username atau password salah!';
    }
  });
</script>

</body>
</html>
