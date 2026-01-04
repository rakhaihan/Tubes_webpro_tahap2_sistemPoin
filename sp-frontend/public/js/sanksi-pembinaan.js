// sanksi-pembinaan.js
document.addEventListener('DOMContentLoaded', () => {
  app.auth.requireAuth();
  app.setupLogout();
  app.setupSidebarToggle();
  app.setupRoleUI();

  const modal = document.getElementById('sanctionModal');
  const openBtn = document.getElementById('addSanctionBtn');
  const closeBtn = document.getElementById('closeModal');
  const form = document.getElementById('sanctionForm');
  const table = document.querySelector('#sanctionsTable tbody');
  const selectStudent = document.getElementById('sanctionStudent');
  const sanctionType = document.getElementById('sanctionType');
  const sanctionDate = document.getElementById('sanctionDate');

  async function fillStudentDropdown(){
    const students = await app.data.getAllStudents();
    selectStudent.innerHTML = students.length ? students.map(s=>`<option value="${s.id}">${s.name}${s.class? ' — ' + s.class : ''}</option>`).join('') : '<option disabled>Belum ada murid</option>';
  }

  function totalPoints(studentId){
    const sid = String(studentId);
    return app.data
      .getAllViolations()
      .filter(v => String(v.studentId) === sid)
      .reduce((a, b) => a + Number(b.points || 0), 0);
  }

  function decideSanction(poin){
    if (poin <= 0) return 'Tidak Ada Sanksi';
    if (poin >= 1 && poin <= 10) return 'Surat Peringatan';
    if (poin >= 11 && poin <= 20) return 'Kerja Bakti';
    if (poin >= 21 && poin <= 40) return 'Pemanggilan Orang Tua';
    if (poin >= 41 && poin <= 60) return 'Skorsing';
    if (poin >= 61) return 'DO';
    return 'Tidak Ada Sanksi';
  }

  async function renderTable(){
    const sanctions = app.data.getAllSanctions(); 
    const students = await app.data.getAllStudents();
    if(!sanctions.length){ table.innerHTML = `<tr><td colspan="6" style="text-align:center">Belum ada data</td></tr>`; return; }

    const findById = (id) => students.find(x => String(x.id) === String(id));

    table.innerHTML = sanctions.map((rec, i) => {
      const s = findById(rec.studentId);
      const name = s ? s.name : (rec.name || '—');
      const points = rec.points != null ? rec.points : (rec.studentId ? totalPoints(rec.studentId) : '—');
      const badgeClass = rec.status === 'aktif' ? 'green' : (rec.status === 'pembinaan' ? 'orange' : 'blue');
      return `<tr>
        <td>${name}</td>
        <td>${points}</td>
        <td>${rec.type}</td>
        <td>${app.util.formatDate(rec.date)}</td>
        <td><span class="badge ${badgeClass}">${rec.status}</span></td>
        <td>
          ${canAdd() ? `
            <button class="btn small ghost" onclick="startEditSanctionStatus(${i})">Edit</button>
          ` : ''}
          ${canDelete() ? `<button class="btn small danger" onclick="deleteSanction(${i})">Hapus</button>` : ''}
        </td>
      </tr>`;
    }).join('');
  }

  function canAdd(){ return app.auth.getRole() === 'admin'; }
  function canDelete(){ return app.auth.getRole() === 'admin'; }

  openBtn && openBtn.addEventListener('click', async ()=>{
    if(!canAdd()) return alert('Hanya admin yang dapat menambah sanksi');
    await fillStudentDropdown();
    // reset pilihan ke murid pertama (jika ada)
    if (selectStudent.options.length > 0) {
      selectStudent.selectedIndex = 0;
      const id = selectStudent.value;
      const pts = totalPoints(id);
      sanctionType.value = decideSanction(pts);
    } else {
      sanctionType.value = 'Tidak Ada Sanksi';
    }
    sanctionDate.value = app.util.nowDate();
    document.getElementById('sanctionStatus').value = 'aktif';
    modal.classList.add('show');
  });

  closeBtn && closeBtn.addEventListener('click', ()=> modal.classList.remove('show'));
  window.addEventListener('click', e => { if(e.target===modal) modal.classList.remove('show'); });

  // jika murid diganti, hitung ulang poin & sanksi otomatis
  selectStudent && selectStudent.addEventListener('change', ()=>{
    const id = selectStudent.value;
    const pts = totalPoints(id);
    sanctionType.value = decideSanction(pts);
  });

  form && form.addEventListener('submit', async e => {
    e.preventDefault();
    if(!canAdd()) return alert('Hanya admin');
    const sid = selectStudent.value;
    if(!sid) return alert('Pilih murid');
     const s = {
      studentId: sid,
      name: await app.data.findStudentName(sid),
      type: sanctionType.value || 'Tidak Ada Sanksi',
      date: sanctionDate.value || app.util.nowDate(),
      status: document.getElementById('sanctionStatus').value,
      points: totalPoints(sid)
    };
     const arr = app.data.getAllSanctions();
    arr.push(s); localStorage.setItem('sp_sanctions_v1', JSON.stringify(arr)); // use app.data.addSanction not to duplicate, but keep storage consistent
    // re-render
    await renderTable();
    modal.classList.remove('show');
    app.showPopup('✅ Data sanksi berhasil ditambahkan!');
  });

   window.deleteSanction = (index) => {
    if(!canDelete()) return alert('Tidak punya akses hapus');
    const arr = app.data.getAllSanctions();
    if(!arr[index]) return;
    if(confirm('Hapus data sanksi ini?')){
      arr.splice(index,1);
      localStorage.setItem('sp_sanctions_v1', JSON.stringify(arr));
      renderTable();
      app.showPopup('🗑️ Data sanksi dihapus');
    }
  };

  // klik tombol Edit -> tampilkan dropdown status di baris tersebut
  window.startEditSanctionStatus = (index) => {
    if (!canAdd()) return alert('Tidak punya akses edit');

    const tbody = document.querySelector('#sanctionsTable tbody');
    const row = tbody && tbody.rows[index];
    if (!row) return;

    // kolom Status = index 4, kolom Aksi = index 5
    const statusCell = row.cells[4];
    const actionCell = row.cells[5];
    const currentStatus = statusCell.textContent.trim();

    // ganti konten status jadi <select>
    statusCell.innerHTML = `
      <select class="input small" id="statusEditor-${index}">
        <option value="aktif" ${currentStatus === 'aktif' ? 'selected' : ''}>Aktif</option>
        <option value="pembinaan" ${currentStatus === 'pembinaan' ? 'selected' : ''}>Pembinaan</option>
        <option value="selesai" ${currentStatus === 'selesai' ? 'selected' : ''}>Selesai</option>
      </select>
    `;

    const select = document.getElementById(`statusEditor-${index}`);
    if (!select) return;

    select.focus();
    select.addEventListener('change', () => {
      changeSanctionStatus(index, select.value);
    });

    // opsional: saat blur, kalau tidak berubah tetap kembalikan tampilan normal
    select.addEventListener('blur', () => {
      // render ulang baris via renderTable agar badge kembali normal
      renderTable();
    }, { once: true });
  };

  // ubah status sanksi dan simpan
  function changeSanctionStatus(index, newStatus) {
    const arr = app.data.getAllSanctions();
    const rec = arr[index];
    if (!rec) return;
    rec.status = newStatus;
    localStorage.setItem('sp_sanctions_v1', JSON.stringify(arr));
    renderTable();
    app.showPopup('✅ Status diperbarui');
  }

  // init
  (async () => {
    await fillStudentDropdown();
    await renderTable();
  })();
});