<?php
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = db()->prepare('SELECT * FROM surat_pengajuan WHERE id = ?');
$stmt->execute([$id]);
$surat = $stmt->fetch();
if (!$surat) { die('Data tidak ditemukan.'); }

// ambil data warga (jika NIK terdaftar) agar surat lebih lengkap
$stmtW = db()->prepare('SELECT * FROM warga WHERE nik = ? LIMIT 1');
$stmtW->execute([$surat['nik']]);
$warga = $stmtW->fetch();

$profil = get_profil_desa();
$nomorSurat = date('d/', strtotime($surat['tanggal_pengajuan'])) . e($surat['id']) . '/' . NAMA_DESA . '/' . date('Y', strtotime($surat['tanggal_pengajuan']));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Cetak Surat — <?= e($surat['nomor_pengajuan']) ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<style>
  body{background:#fff;max-width:800px;margin:30px auto;padding:0 20px;font-family:"Times New Roman",serif;}
  .kop-surat h2{font-size:1.3rem;}
</style>
</head>
<body>
  <div class="no-print center" style="margin-bottom:20px;">
    <button onclick="window.print()" class="btn btn-primary">🖨️ Cetak / Simpan sebagai PDF</button>
    <a href="<?= BASE_URL ?>/admin/surat_proses.php?id=<?= $id ?>" class="btn btn-outline" style="border-color:var(--hijau);color:var(--hijau);">&larr; Kembali</a>
  </div>

  <div class="kop-surat">
    <h2>PEMERINTAH KABUPATEN <?= strtoupper(e($profil['kabupaten'])) ?></h2>
    <h3>KECAMATAN <?= strtoupper(e($profil['kecamatan'])) ?></h3>
    <h2>PEMERINTAH <?= strtoupper(e($profil['nama_desa'])) ?></h2>
    <p><?= e($profil['alamat_kantor']) ?> — Telp/WA: <?= e($profil['no_telp']) ?> — Email: <?= e($profil['email']) ?></p>
  </div>

  <p class="center"><u><strong>SURAT KETERANGAN</strong></u><br>Nomor: <?= $nomorSurat ?></p>

  <div class="surat-body">
    <p>Yang bertanda tangan di bawah ini, Kepala <?= e($profil['nama_desa']) ?>, Kecamatan <?= e($profil['kecamatan']) ?>, Kabupaten <?= e($profil['kabupaten']) ?>, dengan ini menerangkan bahwa:</p>
    <table style="width:100%;margin:14px 0;">
      <tr><td style="width:200px;padding:3px 0;">Nama</td><td>: <?= e($surat['nama_pemohon']) ?></td></tr>
      <tr><td style="padding:3px 0;">NIK</td><td>: <?= e($surat['nik']) ?></td></tr>
      <?php if ($warga): ?>
      <tr><td style="padding:3px 0;">Tempat/Tgl Lahir</td><td>: <?= e($warga['tempat_lahir'] ?: '-') ?>, <?= tanggal_indo($warga['tanggal_lahir']) ?></td></tr>
      <tr><td style="padding:3px 0;">Jenis Kelamin</td><td>: <?= e($warga['jenis_kelamin'] ?: '-') ?></td></tr>
      <tr><td style="padding:3px 0;">Pekerjaan</td><td>: <?= e($warga['pekerjaan'] ?: '-') ?></td></tr>
      <tr><td style="padding:3px 0;">Alamat</td><td>: <?= e($warga['alamat'] ?: '-') ?>, RT <?= e($warga['rt'] ?: '-') ?>/RW <?= e($warga['rw'] ?: '-') ?></td></tr>
      <?php endif; ?>
    </table>
    <p>Nama tersebut di atas benar merupakan warga <?= e($profil['nama_desa']) ?> dan yang bersangkutan mengajukan permohonan <strong><?= e($surat['jenis_surat']) ?></strong> dengan keperluan sebagai berikut:</p>
    <p style="padding:10px 16px;background:#f7f7f7;border-left:3px solid #333;"><?= nl2br(e($surat['keperluan'])) ?></p>
    <p>Demikian surat keterangan ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
  </div>

  <div class="ttd-block">
    <p><?= e($profil['kecamatan']) ?>, <?= tanggal_indo(date('Y-m-d')) ?></p>
    <p>Kepala <?= e($profil['nama_desa']) ?>,</p>
    <div style="height:70px;"></div>
    <p><strong><u><?= e($profil['kepala_desa']) ?></u></strong></p>
  </div>
</body>
</html>
