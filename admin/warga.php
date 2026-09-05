<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Data Warga';

$keyword = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$sql = 'SELECT * FROM warga WHERE 1=1';
$countSql = 'SELECT COUNT(*) c FROM warga WHERE 1=1';
$params = [];
if ($keyword !== '') {
    $cond = ' AND (nik LIKE ? OR no_kk LIKE ? OR nama_lengkap LIKE ?)';
    $sql .= $cond; $countSql .= $cond;
    $like = "%$keyword%";
    array_push($params, $like, $like, $like);
}
$stmtCount = db()->prepare($countSql);
$stmtCount->execute($params);
$totalRows = (int)$stmtCount->fetch()['c'];
$totalPages = max(1, (int)ceil($totalRows / $perPage));

$sql .= ' ORDER BY nama_lengkap ASC LIMIT ' . $perPage . ' OFFSET ' . $offset;
$stmt = db()->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="toolbar">
  <form method="get" class="search-box">
    <input type="text" name="q" value="<?= e($keyword) ?>" placeholder="Cari NIK / No. KK / Nama...">
    <button type="submit" class="btn btn-green btn-small">Cari</button>
  </form>
  <a href="<?= BASE_URL ?>/admin/warga_form.php" class="btn btn-primary">+ Tambah Warga</a>
</div>

<div class="table-wrap">
  <table>
    <thead><tr><th>NIK</th><th>No. KK</th><th>Nama</th><th>L/P</th><th>Alamat</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php if (empty($data)): ?>
        <tr><td colspan="7" class="center muted">Tidak ada data ditemukan.</td></tr>
      <?php endif; ?>
      <?php foreach ($data as $w): ?>
        <tr>
          <td><?= e($w['nik']) ?></td>
          <td><?= e($w['no_kk']) ?></td>
          <td><?= e($w['nama_lengkap']) ?></td>
          <td><?= $w['jenis_kelamin'] === 'Laki-laki' ? 'L' : 'P' ?></td>
          <td><?= e($w['alamat']) ?> RT<?= e($w['rt']) ?>/RW<?= e($w['rw']) ?></td>
          <td><?= e($w['status_hidup']) ?></td>
          <td>
            <a href="<?= BASE_URL ?>/admin/warga_form.php?id=<?= (int)$w['id'] ?>" class="btn btn-small btn-green">Edit</a>
            <form method="post" action="<?= BASE_URL ?>/admin/warga_delete.php" style="display:inline;" onsubmit="return confirm('Hapus data ini?');">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$w['id'] ?>">
              <button type="submit" class="btn btn-small btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php if ($totalPages > 1): ?>
  <div class="center mt-30">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <a href="?q=<?= urlencode($keyword) ?>&page=<?= $i ?>" class="btn btn-small <?= $i === $page ? 'btn-green' : 'btn-outline' ?>" style="<?= $i === $page ? '' : 'border-color:var(--hijau);color:var(--hijau);' ?>"><?= $i ?></a>
    <?php endfor; ?>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
