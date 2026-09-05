<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Kelola Pengajuan Surat';

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM surat_pengajuan WHERE id = ?');
$stmt->execute([$id]);
$surat = $stmt->fetch();
if (!$surat) {
    flash_set('error', 'Data pengajuan tidak ditemukan.');
    redirect(BASE_URL . '/admin/surat.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $statusBaru = $_POST['status'] ?? $surat['status'];
    $catatan = trim($_POST['catatan_admin'] ?? '');
    $tglSelesai = $statusBaru === 'Selesai' ? date('Y-m-d H:i:s') : null;

    $upd = db()->prepare('UPDATE surat_pengajuan SET status=?, catatan_admin=?, diproses_oleh=?, tanggal_selesai=? WHERE id=?');
    $upd->execute([$statusBaru, $catatan ?: null, ADMIN_ID, $tglSelesai, $id]);

    flash_set('success', 'Status pengajuan berhasil diperbarui.');
    redirect(BASE_URL . '/admin/surat_proses.php?id=' . $id);
}

require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="grid grid-2">
  <div class="card">
    <h3>Detail Pengajuan</h3>
    <p><strong>No. Pengajuan:</strong> <?= e($surat['nomor_pengajuan']) ?></p>
    <p><strong>Nama Pemohon:</strong> <?= e($surat['nama_pemohon']) ?></p>
    <p><strong>NIK:</strong> <?= e($surat['nik']) ?></p>
    <p><strong>No. HP:</strong> <?= e($surat['no_hp'] ?: '-') ?></p>
    <p><strong>Jenis Surat:</strong> <?= e($surat['jenis_surat']) ?></p>
    <p><strong>Keperluan:</strong><br><?= nl2br(e($surat['keperluan'])) ?></p>
    <p><strong>Tanggal Pengajuan:</strong> <?= tanggal_indo($surat['tanggal_pengajuan'], true) ?></p>
    <?php if (!empty($surat['file_pendukung'])): ?>
      <p><strong>Lampiran:</strong> <a href="<?= BASE_URL ?>/uploads/surat/<?= e($surat['file_pendukung']) ?>" target="_blank">Lihat File</a></p>
    <?php endif; ?>
    <?php if ($surat['status'] !== 'Menunggu'): ?>
      <a href="<?= BASE_URL ?>/admin/cetak_surat.php?id=<?= $id ?>" target="_blank" class="btn btn-green btn-small">🖨️ Cetak Surat</a>
    <?php endif; ?>
  </div>

  <div class="card">
    <h3>Update Status</h3>
    <form method="post">
      <?= csrf_field() ?>
      <label>Status</label>
      <select name="status">
        <?php foreach (['Menunggu','Diproses','Selesai','Ditolak'] as $st): ?>
          <option value="<?= $st ?>" <?= $surat['status'] === $st ? 'selected' : '' ?>><?= $st ?></option>
        <?php endforeach; ?>
      </select>
      <label>Catatan Admin (opsional, terlihat oleh warga saat cek status)</label>
      <textarea name="catatan_admin"><?= e($surat['catatan_admin'] ?? '') ?></textarea>
      <button type="submit" class="btn btn-primary btn-block mt-30">Simpan Perubahan</button>
    </form>
  </div>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
