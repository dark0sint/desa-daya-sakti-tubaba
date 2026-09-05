<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'APBDes';

$tahunList = db()->query('SELECT DISTINCT tahun_anggaran FROM apbdes ORDER BY tahun_anggaran DESC')->fetchAll(PDO::FETCH_COLUMN);
$tahunDipilih = isset($_GET['tahun']) && in_array($_GET['tahun'], $tahunList) ? (int)$_GET['tahun'] : ($tahunList[0] ?? date('Y'));

$stmt = db()->prepare('SELECT * FROM apbdes WHERE tahun_anggaran = ? ORDER BY jenis, kategori');
$stmt->execute([$tahunDipilih]);
$data = $stmt->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="toolbar">
  <form method="get" class="search-box">
    <label style="margin:0;display:flex;align-items:center;">Tahun:</label>
    <select name="tahun" onchange="this.form.submit()">
      <?php if (empty($tahunList)): ?><option value="<?= date('Y') ?>"><?= date('Y') ?></option><?php endif; ?>
      <?php foreach ($tahunList as $t): ?>
        <option value="<?= $t ?>" <?= $t == $tahunDipilih ? 'selected' : '' ?>><?= $t ?></option>
      <?php endforeach; ?>
    </select>
  </form>
  <a href="<?= BASE_URL ?>/admin/apbdes_form.php?tahun=<?= $tahunDipilih ?>" class="btn btn-primary">+ Tambah Pos Anggaran</a>
</div>

<div class="table-wrap">
  <table>
    <thead><tr><th>Jenis</th><th>Kategori</th><th>Uraian</th><th>Anggaran</th><th>Realisasi</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php if (empty($data)): ?>
        <tr><td colspan="6" class="center muted">Belum ada data untuk tahun ini.</td></tr>
      <?php endif; ?>
      <?php foreach ($data as $a): ?>
        <tr>
          <td><?= e($a['jenis']) ?></td>
          <td><?= e($a['kategori']) ?></td>
          <td><?= e($a['uraian'] ?: '-') ?></td>
          <td><?= rupiah($a['anggaran']) ?></td>
          <td><?= rupiah($a['realisasi']) ?></td>
          <td>
            <a href="<?= BASE_URL ?>/admin/apbdes_form.php?id=<?= (int)$a['id'] ?>" class="btn btn-small btn-green">Edit</a>
            <form method="post" action="<?= BASE_URL ?>/admin/apbdes_delete.php" style="display:inline;" onsubmit="return confirm('Hapus pos anggaran ini?');">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
              <button type="submit" class="btn btn-small btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<p class="mt-30"><a href="<?= BASE_URL ?>/transparansi.php" target="_blank">🔗 Lihat tampilan publik Dashboard Transparansi</a></p>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
