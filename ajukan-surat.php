<?php
$pageTitle = 'Ajukan Surat';
require_once __DIR__ . '/includes/header.php';

$error = '';
$nomorBerhasil = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Sesi kedaluwarsa, silakan kirim ulang formulir.';
    } else {
        $nik        = only_digits($_POST['nik'] ?? '');
        $nama       = trim($_POST['nama_pemohon'] ?? '');
        $hp         = trim($_POST['no_hp'] ?? '');
        $jenisSurat = trim($_POST['jenis_surat'] ?? '');
        $keperluan  = trim($_POST['keperluan'] ?? '');

        if (strlen($nik) !== 16) {
            $error = 'NIK harus terdiri dari 16 digit angka.';
        } elseif ($nama === '' || $jenisSurat === '' || $keperluan === '') {
            $error = 'Semua kolom bertanda * wajib diisi.';
        } else {
            try {
                $fileName = handle_upload('file_pendukung', UPLOAD_DIR_SURAT, ['jpg', 'jpeg', 'png', 'pdf']);
                $nomor = generate_nomor_pengajuan();
                $stmt = db()->prepare(
                    'INSERT INTO surat_pengajuan (nomor_pengajuan, nik, nama_pemohon, no_hp, jenis_surat, keperluan, file_pendukung)
                     VALUES (?, ?, ?, ?, ?, ?, ?)'
                );
                $stmt->execute([$nomor, $nik, $nama, $hp, $jenisSurat, $keperluan, $fileName]);
                $nomorBerhasil = $nomor;
            } catch (RuntimeException $ex) {
                $error = $ex->getMessage();
            } catch (PDOException $ex) {
                $error = 'Gagal menyimpan pengajuan. Silakan coba lagi.';
            }
        }
    }
}
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">E-Surat Kilat</span>
      <h2>Ajukan Surat Tanpa Perlu Antre</h2>
      <p class="muted">Isi formulir di bawah dari HP Anda. Admin desa akan memproses dan Anda tinggal datang untuk mengambil/tanda tangan surat.</p>
    </div>

    <?php if ($nomorBerhasil): ?>
      <div class="form-card">
        <p class="alert alert-success">
          Pengajuan berhasil dikirim! Simpan nomor pengajuan Anda untuk melacak status:
        </p>
        <h2 class="center" style="letter-spacing:2px;color:var(--hijau-tua);"><?= e($nomorBerhasil) ?></h2>
        <p class="center"><a class="btn btn-green" href="<?= BASE_URL ?>/cek-status.php?nomor=<?= urlencode($nomorBerhasil) ?>">Cek Status Pengajuan</a></p>
      </div>
    <?php else: ?>
      <div class="form-card">
        <?php if ($error): ?><p class="alert alert-error"><?= e($error) ?></p><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="row">
            <div>
              <label>NIK (16 digit) *</label>
              <input type="text" name="nik" maxlength="16" pattern="\d{16}" required placeholder="3201xxxxxxxxxxxx">
            </div>
            <div>
              <label>Nomor HP / WhatsApp</label>
              <input type="tel" name="no_hp" placeholder="08xxxxxxxxxx">
            </div>
          </div>
          <label>Nama Lengkap Pemohon *</label>
          <input type="text" name="nama_pemohon" required>

          <label>Jenis Surat *</label>
          <select name="jenis_surat" required>
            <option value="">-- Pilih Jenis Surat --</option>
            <?php foreach (daftar_jenis_surat() as $js): ?>
              <option value="<?= e($js) ?>"><?= e($js) ?></option>
            <?php endforeach; ?>
          </select>

          <label>Keperluan *</label>
          <textarea name="keperluan" required placeholder="Jelaskan singkat untuk keperluan apa surat ini dibutuhkan"></textarea>

          <label>Lampiran Pendukung (opsional, KTP/KK, JPG/PNG/PDF maks 2MB)</label>
          <input type="file" name="file_pendukung" accept=".jpg,.jpeg,.png,.pdf">

          <button type="submit" class="btn btn-primary btn-block mt-30">Kirim Pengajuan</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
