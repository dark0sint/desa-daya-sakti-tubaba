<?php
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$data = [
    'tahun_anggaran' => $_GET['tahun'] ?? date('Y'),
    'jenis' => 'Pendapatan', 'kategori' => '', 'uraian' => '', 'anggaran' => '', 'realisasi' => '',
];
if ($id) {
    $stmt = db()->prepare('SELECT * FROM apbdes WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) { flash_set('error', 'Data tidak ditemukan.'); redirect(BASE_URL . '/admin/apbdes.php'); }
    $data = $row;
}
$pageTitle = $id ? 'Edit Pos Anggaran' : 'Tambah Pos Anggaran';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $tahun = (int)$_POST['tahun_anggaran'];
    $kategori = trim($_POST['kategori'] ?? '');
    $anggaran = (float)str_replace(['.', ','], ['', '.'], $_POST['anggaran'] ?? '0');
    $realisasi = (float)str_replace(['.', ','], ['', '.'], $_POST['realisasi'] ?? '0');

    if ($kategori === '' || $tahun < 2000) {
        $error = 'Kategori dan tahun anggaran wajib diisi dengan benar.';
    } else {
        if ($id) {
            $stmt = db()->prepare('UPDATE apbdes SET tahun_anggaran=?, jenis=?, kategori=?, uraian=?, anggaran=?, realisasi=? WHERE id=?');
            $stmt->execute([$tahun, $_POST['jenis'], $kategori, $_POST['uraian'] ?: null, $anggaran, $realisasi, $id]);
        } else {
            $stmt = db()->prepare('INSERT INTO apbdes (tahun_anggaran, jenis, kategori, uraian, anggaran, realisasi) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$tahun, $_POST['jenis'], $kategori, $_POST['uraian'] ?: null, $anggaran, $realisasi]);
        }
        flash_set('success', 'Data APBDes berhasil disimpan.');
        redirect(BASE_URL . '/admin/apbdes.php?tahun=' . $tahun);
    }
    $data = array_merge($data, $_POST);
}

require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="card" style="max-width:640px;">
  <?php if ($error): ?><p class="alert alert-error"><?= e($error) ?></p><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <div class="row">
      <div><label>Tahun Anggaran *</label><input type="number" name="tahun_anggaran" value="<?= e($data['tahun_anggaran']) ?>" required></div>
      <div><label>Jenis *</label>
        <select name="jenis">
          <?php foreach (['Pendapatan','Belanja','Pembiayaan'] as $j): ?>
            <option <?= $data['jenis']===$j?'selected':'' ?>><?= $j ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <label>Kategori * (contoh: Dana Desa, Bidang Pembangunan, dsb)</label>
    <input type="text" name="kategori" value="<?= e($data['kategori']) ?>" required>
    <label>Uraian (opsional)</label>
    <input type="text" name="uraian" value="<?= e($data['uraian']) ?>">
    <div class="row">
      <div><label>Anggaran (Rp) *</label><input type="text" name="anggaran" value="<?= e($data['anggaran']) ?>" required></div>
      <div><label>Realisasi (Rp) *</label><input type="text" name="realisasi" value="<?= e($data['realisasi']) ?>" required></div>
    </div>
    <button type="submit" class="btn btn-primary btn-block mt-30">Simpan</button>
  </form>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
