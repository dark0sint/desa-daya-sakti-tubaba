<?php
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$data = [
    'nik' => '', 'no_kk' => '', 'nama_lengkap' => '', 'jenis_kelamin' => 'Laki-laki',
    'tempat_lahir' => '', 'tanggal_lahir' => '', 'alamat' => '', 'rt' => '', 'rw' => '',
    'dusun' => '', 'agama' => '', 'pekerjaan' => '', 'status_perkawinan' => 'Belum Kawin', 'status_hidup' => 'Hidup',
];
if ($id) {
    $stmt = db()->prepare('SELECT * FROM warga WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) { flash_set('error', 'Data tidak ditemukan.'); redirect(BASE_URL . '/admin/warga.php'); }
    $data = $row;
}
$pageTitle = $id ? 'Edit Warga' : 'Tambah Warga';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $nik = only_digits($_POST['nik'] ?? '');
    $noKk = only_digits($_POST['no_kk'] ?? '');
    $nama = trim($_POST['nama_lengkap'] ?? '');

    if (strlen($nik) !== 16) {
        $error = 'NIK harus 16 digit.';
    } elseif ($nama === '') {
        $error = 'Nama lengkap wajib diisi.';
    } else {
        try {
            if ($id) {
                $stmt = db()->prepare(
                    'UPDATE warga SET nik=?, no_kk=?, nama_lengkap=?, jenis_kelamin=?, tempat_lahir=?, tanggal_lahir=?,
                     alamat=?, rt=?, rw=?, dusun=?, agama=?, pekerjaan=?, status_perkawinan=?, status_hidup=? WHERE id=?'
                );
                $stmt->execute([
                    $nik, $noKk, $nama, $_POST['jenis_kelamin'], $_POST['tempat_lahir'] ?: null, $_POST['tanggal_lahir'] ?: null,
                    $_POST['alamat'] ?: null, $_POST['rt'] ?: null, $_POST['rw'] ?: null, $_POST['dusun'] ?: null,
                    $_POST['agama'] ?: null, $_POST['pekerjaan'] ?: null, $_POST['status_perkawinan'], $_POST['status_hidup'], $id
                ]);
            } else {
                $stmt = db()->prepare(
                    'INSERT INTO warga (nik, no_kk, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir,
                     alamat, rt, rw, dusun, agama, pekerjaan, status_perkawinan, status_hidup)
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
                );
                $stmt->execute([
                    $nik, $noKk, $nama, $_POST['jenis_kelamin'], $_POST['tempat_lahir'] ?: null, $_POST['tanggal_lahir'] ?: null,
                    $_POST['alamat'] ?: null, $_POST['rt'] ?: null, $_POST['rw'] ?: null, $_POST['dusun'] ?: null,
                    $_POST['agama'] ?: null, $_POST['pekerjaan'] ?: null, $_POST['status_perkawinan'], $_POST['status_hidup']
                ]);
            }
            flash_set('success', 'Data warga berhasil disimpan.');
            redirect(BASE_URL . '/admin/warga.php');
        } catch (PDOException $ex) {
            $error = str_contains($ex->getMessage(), 'Duplicate') ? 'NIK sudah terdaftar di sistem.' : 'Gagal menyimpan data.';
        }
    }
    $data = array_merge($data, $_POST);
}

require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="card" style="max-width:760px;">
  <?php if ($error): ?><p class="alert alert-error"><?= e($error) ?></p><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <div class="row">
      <div><label>NIK *</label><input type="text" name="nik" maxlength="16" value="<?= e($data['nik']) ?>" required></div>
      <div><label>No. KK *</label><input type="text" name="no_kk" maxlength="16" value="<?= e($data['no_kk']) ?>" required></div>
    </div>
    <label>Nama Lengkap *</label>
    <input type="text" name="nama_lengkap" value="<?= e($data['nama_lengkap']) ?>" required>
    <div class="row">
      <div><label>Jenis Kelamin</label>
        <select name="jenis_kelamin">
          <option <?= $data['jenis_kelamin']==='Laki-laki'?'selected':'' ?>>Laki-laki</option>
          <option <?= $data['jenis_kelamin']==='Perempuan'?'selected':'' ?>>Perempuan</option>
        </select>
      </div>
      <div><label>Status</label>
        <select name="status_hidup">
          <?php foreach (['Hidup','Meninggal','Pindah'] as $sh): ?>
            <option <?= $data['status_hidup']===$sh?'selected':'' ?>><?= $sh ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="row">
      <div><label>Tempat Lahir</label><input type="text" name="tempat_lahir" value="<?= e($data['tempat_lahir']) ?>"></div>
      <div><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" value="<?= e($data['tanggal_lahir']) ?>"></div>
    </div>
    <label>Alamat</label>
    <input type="text" name="alamat" value="<?= e($data['alamat']) ?>">
    <div class="row">
      <div><label>RT</label><input type="text" name="rt" value="<?= e($data['rt']) ?>"></div>
      <div><label>RW</label><input type="text" name="rw" value="<?= e($data['rw']) ?>"></div>
    </div>
    <div class="row">
      <div><label>Dusun</label><input type="text" name="dusun" value="<?= e($data['dusun']) ?>"></div>
      <div><label>Agama</label><input type="text" name="agama" value="<?= e($data['agama']) ?>"></div>
    </div>
    <div class="row">
      <div><label>Pekerjaan</label><input type="text" name="pekerjaan" value="<?= e($data['pekerjaan']) ?>"></div>
      <div><label>Status Perkawinan</label>
        <select name="status_perkawinan">
          <?php foreach (['Belum Kawin','Kawin','Cerai Hidup','Cerai Mati'] as $sp): ?>
            <option <?= $data['status_perkawinan']===$sp?'selected':'' ?>><?= $sp ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block mt-30">Simpan Data</button>
  </form>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
