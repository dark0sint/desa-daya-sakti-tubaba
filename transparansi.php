<?php
$pageTitle = 'Transparansi APBDes';
require_once __DIR__ . '/includes/header.php';

$tahunList = db()->query('SELECT DISTINCT tahun_anggaran FROM apbdes ORDER BY tahun_anggaran DESC')->fetchAll(PDO::FETCH_COLUMN);
$tahunDipilih = isset($_GET['tahun']) && in_array($_GET['tahun'], $tahunList) ? (int)$_GET['tahun'] : ($tahunList[0] ?? date('Y'));

$stmt = db()->prepare('SELECT * FROM apbdes WHERE tahun_anggaran = ? ORDER BY jenis, kategori');
$stmt->execute([$tahunDipilih]);
$rows = $stmt->fetchAll();

$totalPendapatan = 0; $totalBelanja = 0;
$kategoriPendapatan = []; $kategoriBelanja = [];
foreach ($rows as $r) {
    if ($r['jenis'] === 'Pendapatan') {
        $totalPendapatan += (float)$r['realisasi'];
        $kategoriPendapatan[$r['kategori']] = ($kategoriPendapatan[$r['kategori']] ?? 0) + (float)$r['realisasi'];
    } elseif ($r['jenis'] === 'Belanja') {
        $totalBelanja += (float)$r['realisasi'];
        $kategoriBelanja[$r['kategori']] = ($kategoriBelanja[$r['kategori']] ?? 0) + (float)$r['realisasi'];
    }
}
$sisa = $totalPendapatan - $totalBelanja;
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Transparansi Anggaran</span>
      <h2>Dashboard APBDes <?= e((string)$tahunDipilih) ?></h2>
      <p class="muted">Data ini disajikan agar warga dapat melihat langsung realisasi kerja pemerintah desa.</p>
    </div>

    <?php if (empty($tahunList)): ?>
      <p class="alert alert-info center">Data APBDes belum tersedia. Admin desa dapat menambahkannya melalui panel admin.</p>
    <?php else: ?>
      <form method="get" class="center mb-0" style="margin-bottom:26px;">
        <label style="display:inline;">Tahun Anggaran:</label>
        <select name="tahun" onchange="this.form.submit()" style="width:auto;display:inline-block;">
          <?php foreach ($tahunList as $t): ?>
            <option value="<?= e((string)$t) ?>" <?= $t == $tahunDipilih ? 'selected' : '' ?>><?= e((string)$t) ?></option>
          <?php endforeach; ?>
        </select>
      </form>

      <div class="stat-cards">
        <div class="stat-card"><div class="num"><?= rupiah($totalPendapatan) ?></div><div class="label">Total Pendapatan (Realisasi)</div></div>
        <div class="stat-card"><div class="num"><?= rupiah($totalBelanja) ?></div><div class="label">Total Belanja (Realisasi)</div></div>
        <div class="stat-card"><div class="num" style="color:<?= $sisa >= 0 ? 'var(--hijau)' : 'var(--merah)' ?>"><?= rupiah($sisa) ?></div><div class="label">Selisih (Silpa)</div></div>
        <div class="stat-card"><div class="num"><?= count($rows) ?></div><div class="label">Total Pos Anggaran</div></div>
      </div>

      <div class="grid grid-2">
        <div class="card">
          <h3>Pendapatan per Kategori</h3>
          <canvas id="chartPendapatan" height="220"></canvas>
        </div>
        <div class="card">
          <h3>Belanja per Kategori</h3>
          <canvas id="chartBelanja" height="220"></canvas>
        </div>
      </div>

      <div class="card mt-30">
        <h3>Perbandingan Anggaran vs Realisasi</h3>
        <canvas id="chartAnggaran" height="120"></canvas>
      </div>

      <div class="table-wrap mt-30">
        <table>
          <thead><tr><th>Jenis</th><th>Kategori</th><th>Uraian</th><th>Anggaran</th><th>Realisasi</th><th>%</th></tr></thead>
          <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= e($r['jenis']) ?></td>
              <td><?= e($r['kategori']) ?></td>
              <td><?= e($r['uraian'] ?: '-') ?></td>
              <td><?= rupiah($r['anggaran']) ?></td>
              <td><?= rupiah($r['realisasi']) ?></td>
              <td><?= $r['anggaran'] > 0 ? round(($r['realisasi'] / $r['anggaran']) * 100) . '%' : '-' ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
      <script>
        const dataPendapatan = <?= json_encode($kategoriPendapatan, JSON_UNESCAPED_UNICODE) ?>;
        const dataBelanja = <?= json_encode($kategoriBelanja, JSON_UNESCAPED_UNICODE) ?>;
        const rows = <?= json_encode($rows, JSON_UNESCAPED_UNICODE) ?>;

        new Chart(document.getElementById('chartPendapatan'), {
          type: 'doughnut',
          data: {
            labels: Object.keys(dataPendapatan),
            datasets: [{ data: Object.values(dataPendapatan), backgroundColor: ['#178a56','#0d5c3f','#d9a441','#5aa88a','#8fc9ac','#c9932f'] }]
          }
        });
        new Chart(document.getElementById('chartBelanja'), {
          type: 'doughnut',
          data: {
            labels: Object.keys(dataBelanja),
            datasets: [{ data: Object.values(dataBelanja), backgroundColor: ['#c0392b','#d9a441','#0d5c3f','#e07856','#f0a868','#a85a3d'] }]
          }
        });
        new Chart(document.getElementById('chartAnggaran'), {
          type: 'bar',
          data: {
            labels: rows.map(r => r.kategori),
            datasets: [
              { label: 'Anggaran', data: rows.map(r => parseFloat(r.anggaran)), backgroundColor: '#a9d3c1' },
              { label: 'Realisasi', data: rows.map(r => parseFloat(r.realisasi)), backgroundColor: '#178a56' }
            ]
          },
          options: { responsive: true, scales: { x: { ticks: { autoSkip: false, maxRotation: 60, minRotation: 30 } } } }
        });
      </script>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
