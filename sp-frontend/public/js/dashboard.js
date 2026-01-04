// dashboard.js
document.addEventListener('DOMContentLoaded', async () => {

  app.auth.requireAuth();
  app.setupLogout();
  app.setupSidebarToggle();
  app.setupRoleUI();

  const violations = app.data.getAllViolations();
  const students = await app.data.getAllStudents();

  // helper nama
  const getName = (id) => {
    const s = students.find(x => String(x.id) === String(id));
    return s ? s.name : '(–)';
  };

  // totals
  document.getElementById('statTotal').textContent = violations.length;
  document.getElementById('statPembinaan').textContent = students.filter(s => s.status === 'pembinaan').length;

  const pointsByStudent = {};
  violations.forEach(v => {
    pointsByStudent[v.studentId] = (pointsByStudent[v.studentId] || 0) + Number(v.points || 0);
  });
  const batas = 30;
  const alertCount = Object.values(pointsByStudent).filter(p => p >= batas).length;
  document.getElementById('statAlert').textContent = alertCount;

  const tbody = document.querySelector('#recentTable tbody');
  tbody.innerHTML = violations.slice(-5).reverse().map(v => {
    const name = getName(v.studentId);
    return `<tr>
      <td>${name}</td>
      <td>${v.type}</td>
      <td>${app.util.formatDate(v.date)}</td>
    </tr>`;
  }).join('');

  const severityCounts = { Ringan: 0, Sedang: 0, Berat: 0 };
  violations.forEach(v => {
    const pts = Number(v.points || 0);
    if (pts >= 20) severityCounts.Berat++;
    else if (pts >= 10) severityCounts.Sedang++;
    else severityCounts.Ringan++;
  });

  const labels = ['Ringan','Sedang','Berat'];
  const counts = [severityCounts.Ringan, severityCounts.Sedang, severityCounts.Berat];
  const colors = ['rgba(255,205,86,0.9)','rgba(255,159,64,0.9)','rgba(255,99,132,0.9)'];

  const canvas = document.getElementById('chartCanvas');
  if(canvas){
    const ctx = canvas.getContext('2d');
    if(window._dashboardChart) window._dashboardChart.destroy();
    window._dashboardChart = new Chart(ctx, {
      type:'bar',
      data:{ labels, datasets:[{ label:'Data Pelanggaran', data:counts, backgroundColor: colors }]},
      options:{
        plugins: {
          legend: { display: false },
          title: { display: true, text: 'Data Pelanggaran', padding: { bottom: 10 } }
        },
        scales:{ y:{ beginAtZero:true, ticks:{ precision:0, stepSize:1 }}}
      }
    });
  }
});
