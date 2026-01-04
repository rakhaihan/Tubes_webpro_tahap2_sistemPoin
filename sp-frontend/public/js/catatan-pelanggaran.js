// catatan-pelanggaran.js
document.addEventListener('DOMContentLoaded', () => {
  app.auth.requireAuth();
  app.setupLogout();
  app.setupSidebarToggle();
  app.setupRoleUI();

  const selStudent = document.getElementById('violationStudent');
  const violationType = document.getElementById('violationType');
  const violationPoints = document.getElementById('violationPoints');
  const violationForm = document.getElementById('violationForm');
  const searchInput = document.getElementById('searchViolation');
  const clearBtn = document.getElementById('clearViolations');
  const printBtn = document.getElementById('printReport');
  const tableBody = document.querySelector('#violationsTable tbody');

   async function fillStudentOptions(){
    const students = await app.data.getAllStudents();
    selStudent.innerHTML = '<option value="">-- Pilih Murid --</option>' + students.map(s => `<option value="${s.id}">${s.name} — ${s.class||'-'}</option>`).join('');
  }

   async function renderTable(filter = ''){
    const q = filter.toLowerCase();
    const violations = app.data.getAllViolations();
    // ambil sekali biar tidak getAllStudents berkali-kali
    const students = await app.data.getAllStudents();
    const getName = (id) => {
      const s = students.find(x => String(x.id) === String(id));
      return s ? s.name : '(–)';
    };
    const filtered = violations.filter(v => {
      const name = getName(v.studentId).toLowerCase();
      return name.includes(q) || v.type.toLowerCase().includes(q) || (v.note || '').toLowerCase().includes(q);
    });

    tableBody.innerHTML = '';
    if(filtered.length === 0){
      tableBody.innerHTML = `<tr><td colspan="6" class="muted center">Belum ada catatan pelanggaran</td></tr>`;
      drawBarChart('chartCanvas2', [], []);
      return;
    }

    filtered.slice().reverse().forEach(v => {
      const name = getName(v.studentId);
      const tr = document.createElement('tr');
      tr.innerHTML = `<td>${name}</td>
        <td>${v.type}</td>
        <td>${app.util.formatDate(v.date)}</td>
        <td>${v.points}</td>
        <td>${v.note || ''}</td>
        <td>
          ${canEdit() ? `<button class="btn small" onclick="editViolation('${v.id}')">Edit</button>` : ''}
          ${canDelete() ? `<button class="btn small danger" onclick="deleteViolation('${v.id}')">Hapus</button>` : ''}
        </td>`;
      tableBody.appendChild(tr);
    });

    // chart: counts per severity category (Ringan / Sedang / Berat)
    const severityCounts = { Ringan:0, Sedang:0, Berat:0 };
    app.data.getAllViolations().forEach(x => {
      const pts = Number(x.points || 0);
      if(pts >= 20) severityCounts.Berat++;
      else if(pts >= 10) severityCounts.Sedang++;
      else severityCounts.Ringan++;
    });
    const labels = ['Ringan','Sedang','Berat'];
    const values = [severityCounts.Ringan, severityCounts.Sedang, severityCounts.Berat];
    const colors = ['rgba(255,205,86,0.9)','rgba(255,159,64,0.9)','rgba(255,99,132,0.9)'];
    drawBarChart('chartCanvas2', labels, values, colors);
  }

  function canEdit(){ const r = app.auth.getRole(); return r === 'admin' || r === 'guru'; }
  function canDelete(){ const r = app.auth.getRole(); return r === 'admin'; }

  violationType.addEventListener('change', function(){ violationPoints.value = this.value; });

  violationForm.addEventListener('submit', async e => {
    e.preventDefault();
    if(!canEdit()) return alert('Tidak punya akses menambah catatan');
    const studentId = selStudent.value;
    if(!studentId) return alert('Pilih murid terlebih dahulu');
    const newData = {
      id: app.util.uuid(),
      studentId,
      type: violationType.options[violationType.selectedIndex].text,
      date: document.getElementById('violationDate').value || app.util.nowDate(),
      note: document.getElementById('violationNote').value.trim(),
      points: Number(violationPoints.value) || 0
    };
   app.data.addViolation(newData);
    await fillStudentOptions();
    await renderTable();
    violationForm.reset();
    violationPoints.value = '';
  });

  window.deleteViolation = async (id) => {
    if(!canDelete()) return alert('Tidak punya akses hapus');
    if(confirm('Hapus catatan ini?')){
      app.data.deleteViolation(id);
      await renderTable();
    }
  };

  window.editViolation = async (id) => {
    if(!canEdit()) return alert('Tidak punya akses edit');
    const v = app.data.getViolation(id);
    if(!v) return alert('Data tidak ditemukan');
    if(!confirm('Muat data ke form untuk diedit? (menyimpan akan membuat entri baru)')) return;
    document.getElementById('violationStudent').value = v.studentId;
    document.getElementById('violationType').value = v.points;
    document.getElementById('violationDate').value = v.date;
    document.getElementById('violationNote').value = v.note;
    document.getElementById('violationPoints').value = v.points;
    app.data.deleteViolation(id);
    await renderTable();
  };

  clearBtn.addEventListener('click', async () => {
    if(!canDelete()) return alert('Tidak punya akses bersihkan');
    if(confirm('Hapus semua catatan pelanggaran?')){
      app.data.clearViolations();
      await renderTable();
    }
  });

  // print: open a printable window with the violations table (more reliable than CSS-only print)
  printBtn.addEventListener('click', () => {
    const table = document.getElementById('violationsTable');
    if(!table) return alert('Tidak ada tabel untuk dicetak');
    const style = `
      <style>
        body{font-family:Inter,Arial,Helvetica,sans-serif;padding:20px}
        table{width:100%;border-collapse:collapse}
        th,td{padding:8px;border:1px solid #ddd;text-align:left}
        th{background:#f4f6fb}
      </style>`;
    const win = window.open('', '_blank');
    win.document.write('<html><head><title>Riwayat Pelanggaran</title>'+style+'</head><body>');
    win.document.write('<h2>Riwayat Pelanggaran</h2>');
    win.document.write(table.outerHTML);
    win.document.write('</body></html>');
    win.document.close();
    win.focus();
    setTimeout(()=>{ win.print(); win.close(); }, 300);
  });

  searchInput.addEventListener('input', e => renderTable(e.target.value));

  function drawBarChart(canvasId, labels, values, colors){
    const c = document.getElementById(canvasId);
    if(!c) return;
    const ctx = c.getContext('2d');
    ctx.clearRect(0,0,c.width,c.height);
    if(!labels.length){ ctx.fillStyle='#94a3b8'; ctx.font='16px Inter'; ctx.fillText('Tidak ada data',20,40); return; }
    // use Chart.js if you want; here simple bars
    // we'll draw with Chart.js for better visuals:
    if(window._chart2) window._chart2.destroy();
    window._chart2 = new Chart(ctx, { type:'bar', data:{ labels, datasets:[{ label: 'Data Pelanggaran', data:values, backgroundColor: colors || (values.map(v=> v<10 ? 'rgba(255,205,86,0.9)' : (v<20 ? 'rgba(255,159,64,0.9)' : 'rgba(255,99,132,0.9)'))) }]}, options:{ plugins:{ legend:{ display:false }, title:{ display:true, text:'Data Pelanggaran', padding:{ bottom:10 } } }, scales:{ y:{ beginAtZero:true, ticks:{precision:0, stepSize:1}}}}});
  }

  // init
  (async () => {
    await fillStudentOptions();
    await renderTable();
  })();
});
