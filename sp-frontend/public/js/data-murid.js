// data-murid.js
document.addEventListener('DOMContentLoaded', () => {
  app.auth.requireAuth();
  app.setupLogout();
  app.setupSidebarToggle();
  app.setupRoleUI();

  const tableBody = document.querySelector('#studentsTable tbody');
  const modal = document.getElementById('modal');
  const form = document.getElementById('studentForm');
  const cancelBtn = document.getElementById('cancelModal');
  const addBtn = document.getElementById('addStudentBtn');
  const search = document.getElementById('searchStudent');
  const exportBtn = document.getElementById('exportStudents');
  const classDropdown = document.getElementById('filterClass');

   let students = [];

  async function reloadStudents() {
    students = await app.data.getAllStudents();
  }

  async function renderTable(list){
    if (!list) {
      await reloadStudents();
      list = students;
    }
    tableBody.innerHTML = list.map((s, i) => `
      <tr>
        <td>${s.name}</td>
        <td>${s.class || '-'}</td>
        <td>${s.nis || '-'}</td>
        <td><span class="badge ${s.status === 'pembinaan' ? 'orange' : 'green'}">${s.status}</span></td>
         <td>
          ${canEdit() ? `<button class="btn small" onclick="editStudent('${s.id}')">Edit</button>` : ''}
          ${canDelete() ? `<button class="btn small danger" onclick="deleteStudent('${s.id}')">Hapus</button>` : ''}
        </td>
      </tr>
    `).join('');
  }

  function canEdit(){ const r = app.auth.getRole(); return r === 'admin' || r === 'guru'; }
  function canDelete(){ const r = app.auth.getRole(); return r === 'admin'; }

  // fill class dropdown
  // fill class dropdown
  async function fillClassDropdown(){
    await reloadStudents();
    const kelas = [...new Set(students.map(s => s.class || '').filter(x=>x))];
    classDropdown.innerHTML = `<option value="">Semua Kelas</option>` + kelas.map(k => `<option value="${k}">${k}</option>`).join('');
  }

  // initial
  (async () => {
    await fillClassDropdown();
    await renderTable();
  })();

 window.editStudent = async (id) => {
    const all = await app.data.getAllStudents();
    const s = all.find(x => String(x.id) === String(id));
    if(!s) return;
    if(!canEdit()) return alert('Tidak punya akses edit');
    document.getElementById('modalTitle').textContent = 'Edit Murid';
    form.studentId.value = s.id;
    form.studentName.value = s.name;
    form.studentClass.value = s.class;
    form.studentNIS.value = s.nis;
    form.studentStatus.value = s.status;
    modal.classList.remove('hidden');
  };

   window.deleteStudent = async (id) => {
    if(!canDelete()) return alert('Tidak punya akses menghapus');
    if(confirm('Hapus murid ini?')){
      await app.data.deleteStudent(id);
      await fillClassDropdown();
      await renderTable();
    }
  };

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const s = {
      id: form.studentId.value || null,
      name: form.studentName.value.trim(),
      class: form.studentClass.value.trim(),
      nis: form.studentNIS.value.trim(),
      status: form.studentStatus.value
    };
    if(!s.name || !s.class) return alert('Nama dan kelas wajib diisi');
     if(s.id){
      await app.data.updateStudent(s);
    } else {
      await app.data.addStudent(s);
    }
    form.reset();
    modal.classList.add('hidden');
    await fillClassDropdown();
    await renderTable();
  });

  addBtn.addEventListener('click', () => {
    if(!canEdit()) return alert('Tidak punya akses menambah murid');
    document.getElementById('modalTitle').textContent = 'Tambah Murid';
    form.reset(); form.studentId.value = '';
    modal.classList.remove('hidden');
  });

  cancelBtn.addEventListener('click', ()=> modal.classList.add('hidden'));

   async function doFilter(){
    const cls = classDropdown.value;
    const q = search.value.toLowerCase();
    await reloadStudents();
    const filtered = students.filter(s => (cls === '' || (s.class||'') === cls) && (s.name.toLowerCase().includes(q) || (s.nis||'').includes(q)));
    renderTable(filtered);
  }
  search.addEventListener('input', doFilter);
  classDropdown.addEventListener('change', doFilter);

  // export
 exportBtn.addEventListener('click', async () => {
    const arr = await app.data.getAllStudents();
    if(!arr.length) return alert('Tidak ada data murid');
    const csv = app.util.exportCSV(arr, ['name','class','nis','status']);
    app.util.downloadFile(csv, 'data_murid.csv', 'text/csv');
  });

});
