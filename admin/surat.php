<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'E-Surat';

$status = $_GET['status'] ?? '';
$keyword = trim($_GET['q'] ?? '');

$sql = 'SELECT * FROM surat_pengajuan WHERE 1=1';
$params = [];
if ($status !== '' && in_array($status, ['Menunggu','Diproses','Selesai','Ditolak'], true)) {
    $sql .= ' AND status = ?';
    $params[] = $status;
}
if ($keyword !== '') {
    $sql .= ' AND (nama_pemohon LIKE ? OR nik LIKE ? OR nomor_pengajuan LIKE ?)';
    $like = "%$keyword%";
    array_push($params, $like, $like, $like);
}
$sql .= ' ORDER BY tanggal_pengajuan DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="toolbar">
  <form method="get" class="search-box">
    <input type="text" name="q" value="<?= e($keyword) ?>" placeholder="Cari nama / NIK / no. pengajuan...">
    <select name="status" onchange="this.form.submit()">
      <option value="">Semua Status</option>
      <?php foreach (['Menunggu','Diproses','Selesai','Ditolak'] as $st): ?>
        <option value="<?= $st ?>" <?= $status === $st ? 'selected' : '' ?>><?= $st ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-green btn-small">Cari</button>
  </form>
</div>

<div class="table-wrap">
  <table>
    <thead><tr><th>No. Pengajuan</th><th>Pemohon</th><th>NIK</th><th>Jenis Surat</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php if (empty($data)): ?>
        <tr><td colspan="7" class="center muted">Tidak ada data.</td></tr>
      <?php endif; ?>
      <?php foreach ($data as $s): ?>
        <?php $map = ['Menunggu'=>'badge-warning','Diproses'=>'badge-info','Selesai'=>'badge-success','Ditolak'=>'badge-danger']; ?>
        <tr>
          <td><?= e($s['nomor_pengajuan']) ?></td>
          <td><?= e($s['nama_pemohon']) ?></td>
          <td><?= e($s['nik']) ?></td>
          <td><?= e($s['jenis_surat']) ?></td>
          <td><?= tanggal_indo($s['tanggal_pengajuan']) ?></td>
          <td><span class="badge <?= $map[$s['status']] ?>"><?= e($s['status']) ?></span></td>
          <td><a href="<?= BASE_URL ?>/admin/surat_proses.php?id=<?= (int)$s['id'] ?>" class="btn btn-small btn-green">Kelola</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
