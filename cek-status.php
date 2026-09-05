<?php
$pageTitle = 'Cek Status Surat';
require_once __DIR__ . '/includes/header.php';

$nomor = trim($_GET['nomor'] ?? ($_POST['nomor'] ?? ''));
$hasil = null;
$dicari = false;

if ($nomor !== '') {
    $dicari = true;
    $stmt = db()->prepare('SELECT * FROM surat_pengajuan WHERE nomor_pengajuan = ?');
    $stmt->execute([$nomor]);
    $hasil = $stmt->fetch();
}

function badge_status(string $status): string
{
    $map = [
        'Menunggu' => 'badge-warning',
        'Diproses' => 'badge-info',
        'Selesai'  => 'badge-success',
        'Ditolak'  => 'badge-danger',
    ];
    $class = $map[$status] ?? 'badge-info';
    return '<span class="badge ' . $class . '">' . e($status) . '</span>';
}
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Lacak Pengajuan</span>
      <h2>Cek Status Surat Anda</h2>
      <p class="muted">Masukkan nomor pengajuan yang Anda terima setelah mengirim formulir E-Surat.</p>
    </div>
    <div class="form-card">
      <form method="get">
        <label>Nomor Pengajuan</label>
        <input type="text" name="nomor" value="<?= e($nomor) ?>" placeholder="Contoh: 20260905-AB12C" required>
        <button type="submit" class="btn btn-primary btn-block mt-30">Cek Status</button>
      </form>

      <?php if ($dicari): ?>
        <?php if ($hasil): ?>
          <hr style="margin:26px 0;border:none;border-top:1px solid var(--border);">
          <p><strong>Nama Pemohon:</strong> <?= e($hasil['nama_pemohon']) ?></p>
          <p><strong>Jenis Surat:</strong> <?= e($hasil['jenis_surat']) ?></p>
          <p><strong>Tanggal Pengajuan:</strong> <?= tanggal_indo($hasil['tanggal_pengajuan'], true) ?></p>
          <p><strong>Status:</strong> <?= badge_status($hasil['status']) ?></p>
          <?php if (!empty($hasil['catatan_admin'])): ?>
            <p><strong>Catatan Admin:</strong> <?= e($hasil['catatan_admin']) ?></p>
          <?php endif; ?>
          <?php if ($hasil['status'] === 'Selesai'): ?>
            <p class="alert alert-success">Surat Anda sudah selesai diproses. Silakan datang ke Kantor Desa untuk pengambilan.</p>
          <?php endif; ?>
        <?php else: ?>
          <p class="alert alert-error">Nomor pengajuan tidak ditemukan. Pastikan penulisan sudah benar.</p>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
