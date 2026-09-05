<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Dashboard';

$menunggu = (int) db()->query("SELECT COUNT(*) c FROM surat_pengajuan WHERE status='Menunggu'")->fetch()['c'];
$diproses = (int) db()->query("SELECT COUNT(*) c FROM surat_pengajuan WHERE status='Diproses'")->fetch()['c'];
$selesai  = (int) db()->query("SELECT COUNT(*) c FROM surat_pengajuan WHERE status='Selesai'")->fetch()['c'];
$totalWarga = (int) db()->query("SELECT COUNT(*) c FROM warga")->fetch()['c'];
$totalProduk = (int) db()->query("SELECT COUNT(*) c FROM produk_umkm")->fetch()['c'];

$suratTerbaru = db()->query("SELECT * FROM surat_pengajuan ORDER BY tanggal_pengajuan DESC LIMIT 8")->fetchAll();

require_once __DIR__ . '/includes/admin_header.php';
?>

<div class="stat-cards">
  <div class="stat-card"><div class="num"><?= $menunggu ?></div><div class="label">Surat Menunggu</div></div>
  <div class="stat-card"><div class="num"><?= $diproses ?></div><div class="label">Sedang Diproses</div></div>
  <div class="stat-card"><div class="num"><?= $selesai ?></div><div class="label">Surat Selesai</div></div>
  <div class="stat-card"><div class="num"><?= $totalWarga ?></div><div class="label">Data Warga</div></div>
</div>

<div class="toolbar">
  <h3 class="mb-0">Pengajuan Surat Terbaru</h3>
  <a href="<?= BASE_URL ?>/admin/surat.php" class="btn btn-green btn-small">Lihat Semua</a>
</div>

<div class="table-wrap">
  <table>
    <thead><tr><th>No. Pengajuan</th><th>Pemohon</th><th>Jenis Surat</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php if (empty($suratTerbaru)): ?>
        <tr><td colspan="6" class="center muted">Belum ada pengajuan surat.</td></tr>
      <?php endif; ?>
      <?php foreach ($suratTerbaru as $s): ?>
        <tr>
          <td><?= e($s['nomor_pengajuan']) ?></td>
          <td><?= e($s['nama_pemohon']) ?></td>
          <td><?= e($s['jenis_surat']) ?></td>
          <td><?= tanggal_indo($s['tanggal_pengajuan']) ?></td>
          <td><?php
            $map = ['Menunggu'=>'badge-warning','Diproses'=>'badge-info','Selesai'=>'badge-success','Ditolak'=>'badge-danger'];
            echo '<span class="badge '.$map[$s['status']].'">'.e($s['status']).'</span>';
          ?></td>
          <td><a href="<?= BASE_URL ?>/admin/surat_proses.php?id=<?= (int)$s['id'] ?>" class="btn btn-small btn-green">Kelola</a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
