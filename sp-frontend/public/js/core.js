// core.js
(function () {

  const KEY = {
    AUTH: 'sp_auth',
    STUDENTS: 'sp_students_v1',
    VIOLATIONS: 'sp_violations_v1',
    SANCTIONS: 'sp_sanctions_v1'
  };

  // ==========================
  // STORAGE UTILS
  // ==========================
  function uuid() { return 'id-' + Math.random().toString(36).slice(2, 9); }
  function nowDate() { const d = new Date(); return d.toISOString().slice(0, 10); }

  function save(key, val) { localStorage.setItem(key, JSON.stringify(val)); }
  function load(key) { try { return JSON.parse(localStorage.getItem(key) || 'null'); } catch { return null; } }


  // ==========================
  // DATA API
  // ==========================
 const data = {

    // ==========================
    // STUDENTS via API NODE.JS
    // ==========================
    async getAllStudents() {
      const res = await fetch('http://localhost:3000/api/students');
      if (!res.ok) throw new Error('Gagal mengambil data murid');
      return await res.json();
    },

    async addStudent(s) {
      const res = await fetch('http://localhost:3000/api/students', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: s.name,
          class_name: s.class, // backend pakai field class_name di body
          nis: s.nis,
          status: s.status || 'aktif'
        })
      });
      if (!res.ok) throw new Error('Gagal menambah murid');
      return await res.json(); // { message, id }
    },

    async updateStudent(newS) {
      const res = await fetch(`http://localhost:3000/api/students/${newS.id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: newS.name,
          class_name: newS.class,
          nis: newS.nis,
          status: newS.status
        })
      });
      if (!res.ok) throw new Error('Gagal mengubah murid');
      return await res.json();
    },

    async deleteStudent(id) {
      const res = await fetch(`http://localhost:3000/api/students/${id}`, {
        method: 'DELETE'
      });
      if (!res.ok) throw new Error('Gagal menghapus murid');
      return await res.json();
    },

    getAllViolations() { return load(KEY.VIOLATIONS) || []; },
    addViolation(v) {
      const arr = this.getAllViolations();
      arr.push(v);
      save(KEY.VIOLATIONS, arr);
    },
    getViolation(id){ return this.getAllViolations().find(x=> x.id === id); },
    clearViolations(){ save(KEY.VIOLATIONS, []); },
    deleteViolation(id) {
      const arr = this.getAllViolations().filter(v => v.id !== id);
      save(KEY.VIOLATIONS, arr);
    },

    getAllSanctions() { return load(KEY.SANCTIONS) || []; },
    addSanction(s) {
      const arr = this.getAllSanctions();
      arr.push(s);
      save(KEY.SANCTIONS, arr);
    },
    deleteSanction(index) {
      const arr = this.getAllSanctions();
      arr.splice(index, 1);
      save(KEY.SANCTIONS, arr);
    },

    getSanction(id){ return this.getAllSanctions().find(x=> x.id === id); },

    // helper student harus async, karena getAllStudents() async
    async findStudentName(id) {
      const students = await this.getAllStudents();
      const s = students.find(x => String(x.id) === String(id));
      return s ? s.name : '(–)';
    },
    async findStudentById(id){
      const students = await this.getAllStudents();
      return students.find(x => String(x.id) === String(id));
    },

    ensureInitial() {
      if (localStorage.getItem(KEY.STUDENTS) === null) save(KEY.STUDENTS, []);
      if (localStorage.getItem(KEY.VIOLATIONS) === null) save(KEY.VIOLATIONS, []);
      if (localStorage.getItem(KEY.SANCTIONS) === null) save(KEY.SANCTIONS, []);
    }
  };


  // ==========================
  // AUTH HANDLER
  // ==========================
const auth = {

    // login ke backend, simpan token + role
    async login(username, password) {
      try {
        const res = await fetch('http://localhost:3000/api/auth/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ username, password })
        });

        if (!res.ok) return false;

        const data = await res.json();
        localStorage.setItem('token', data.token);
        localStorage.setItem('role', data.role);
        return true;
      } catch (e) {
        console.error('Login error', e);
        return false;
      }
    },

    logout() {
      localStorage.removeItem('token');
      localStorage.removeItem('role');
    },

    getUser() {
      const token = localStorage.getItem('token');
      const role = localStorage.getItem('role');
      if (!token || !role) return null;
      return { token, role };
    },

    getRole() {
      const u = this.getUser();
      return u ? u.role : null;
    },

    requireAuth() {
      if (!this.getUser()) {
        location.href = '/';
      }
    },

    // role protector
    requireRole(requiredRole) {
      const u = this.getUser();
      if (!u) return location.href = '/';
      if (u.role !== requiredRole) return location.href = '/dashboard';
    }
  };

  // ==========================
  // UI HELPERS
  // ==========================
  function setupLogout() {
    const buttons = document.querySelectorAll("[data-logout]");
    buttons.forEach(btn => {
      btn.addEventListener("click", () => {
        localStorage.removeItem("token");
        localStorage.removeItem("role");
        window.location.href = "/";
      });
    });
  }



  function setupSidebarToggle() {
    const sidebar = document.querySelector('.sidebar');
    const toggle = document.getElementById('sidebarToggle');
    if (toggle && sidebar) {
      toggle.addEventListener('click', () => sidebar.classList.toggle('show'));
    }
  }

  function setupRoleUI(){
    const role = auth.getRole();
    // murid: view-only, can still print reports
    if(role === 'murid'){
      // For murid, we don't remove menu or page elements — actions are already
      // protected by per-page checks (canEdit/canAdd). Keep pages visible; only
      // optionally mark inputs disabled inside sanction form so they cannot
      // submit from modal directly if the modal opens unexpectedly.
      const sanctionForm = document.getElementById('sanctionForm');
      if(sanctionForm) Array.from(sanctionForm.querySelectorAll('input,select,button,textarea')).forEach(i=>{ if(i.type!=='button') i.disabled = true; });
    }

    // guru: cannot add sanctions -> hide add button
    if(role === 'guru'){
      // Do not remove the navigation or buttons — the page remains visible but
      // action handlers already check `canAdd()` and will alert if not allowed.
    }
  }

  function showPopup(msg) {
    const p = document.createElement('div');
    p.className = 'popup-msg';
    p.textContent = msg;
    document.body.appendChild(p);
    setTimeout(() => p.classList.add('show'), 10);
    setTimeout(() => p.classList.remove('show'), 2000);
    setTimeout(() => p.remove(), 2500);
  }


  // ==========================
  // EXPOSE APP
  // ==========================
  window.app = {
    data,
    auth,
    util: {
      uuid,
      nowDate,
      formatDate: function(d){ if(!d) return ''; try{ const dt = new Date(d); return dt.toISOString().slice(0,10); } catch { return d; } },
      exportCSV: function(arr, keys){ if(!arr || !arr.length) return ''; const k = keys || Object.keys(arr[0]); const rows = [k.join(',')].concat(arr.map(obj=> k.map(h=> `"${String(obj[h]===undefined||obj[h]===null?'':String(obj[h])).replace(/"/g,'""')}"`).join(','))); return rows.join('\n'); },
      downloadFile: function(content, filename='export.txt', mime='text/plain'){ const blob = new Blob([content], { type: mime }); const url = URL.createObjectURL(blob); const a = document.createElement('a'); a.href = url; a.download = filename; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url); }
    },
    requireLogin: auth.requireAuth.bind(auth),
    setupLogout,
    setupSidebarToggle,
    setupRoleUI,
    showPopup
  };

  // Inisialisasi storage dasar
  app.data.ensureInitial();
})();
