<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Profil Desa';

$profil = db()->query('SELECT * FROM desa_profile LIMIT 1')->fetch();
$errorProfil = ''; $errorPass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    if (isset($_POST['simpan_profil'])) {
        $foto = handle_upload('logo', UPLOAD_DIR_PRODUK, ['jpg','jpeg','png']);
        $logo = $foto ?: ($profil['logo'] ?? null);
        if ($profil) {
            $stmt = db()->prepare('UPDATE desa_profile SET nama_desa=?, kecamatan=?, kabupaten=?, provinsi=?, alamat_kantor=?, no_telp=?, email=?, kepala_desa=?, sekretaris_desa=?, logo=?, deskripsi=? WHERE id=?');
            $stmt->execute([$_POST['nama_desa'], $_POST['kecamatan'], $_POST['kabupaten'], $_POST['provinsi'], $_POST['alamat_kantor'], $_POST['no_telp'], $_POST['email'], $_POST['kepala_desa'], $_POST['sekretaris_desa'], $logo, $_POST['deskripsi'], $profil['id']]);
        } else {
            $stmt = db()->prepare('INSERT INTO desa_profile (nama_desa, kecamatan, kabupaten, provinsi, alamat_kantor, no_telp, email, kepala_desa, sekretaris_desa, logo, deskripsi) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute([$_POST['nama_desa'], $_POST['kecamatan'], $_POST['kabupaten'], $_POST['provinsi'], $_POST['alamat_kantor'], $_POST['no_telp'], $_POST['email'], $_POST['kepala_desa'], $_POST['sekretaris_desa'], $logo, $_POST['deskripsi']]);
        }
        flash_set('success', 'Profil desa berhasil diperbarui.');
        redirect(BASE_URL . '/admin/profil.php');
    }

    if (isset($_POST['ganti_password'])) {
        $lama = $_POST['password_lama'] ?? '';
        $baru = $_POST['password_baru'] ?? '';
        $stmt = db()->prepare('SELECT * FROM admin_users WHERE id = ?');
        $stmt->execute([ADMIN_ID]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($lama, $user['password'])) {
            $errorPass = 'Password lama salah.';
        } elseif (strlen($baru) < 8) {
            $errorPass = 'Password baru minimal 8 karakter.';
        } else {
            $upd = db()->prepare('UPDATE admin_users SET password = ? WHERE id = ?');
            $upd->execute([password_hash($baru, PASSWORD_DEFAULT), ADMIN_ID]);
            flash_set('success', 'Password berhasil diubah.');
            redirect(BASE_URL . '/admin/profil.php');
        }
    }
    $profil = array_merge($profil ?: [], $_POST);
}

require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="grid grid-2">
  <div class="card">
    <h3>Profil Desa</h3>
    <form method="post" enctype="multipart/form-data">
      <?= csrf_field() ?>
      <label>Nama Desa</label><input type="text" name="nama_desa" value="<?= e($profil['nama_desa'] ?? '') ?>" required>
      <div class="row">
        <div><label>Kecamatan</label><input type="text" name="kecamatan" value="<?= e($profil['kecamatan'] ?? '') ?>"></div>
        <div><label>Kabupaten</label><input type="text" name="kabupaten" value="<?= e($profil['kabupaten'] ?? '') ?>"></div>
      </div>
      <label>Provinsi</label><input type="text" name="provinsi" value="<?= e($profil['provinsi'] ?? 'Lampung') ?>">
      <label>Alamat Kantor</label><input type="text" name="alamat_kantor" value="<?= e($profil['alamat_kantor'] ?? '') ?>">
      <div class="row">
        <div><label>No. Telp/WA</label><input type="text" name="no_telp" value="<?= e($profil['no_telp'] ?? '') ?>"></div>
        <div><label>Email</label><input type="email" name="email" value="<?= e($profil['email'] ?? '') ?>"></div>
      </div>
      <div class="row">
        <div><label>Kepala Desa</label><input type="text" name="kepala_desa" value="<?= e($profil['kepala_desa'] ?? '') ?>"></div>
        <div><label>Sekretaris Desa</label><input type="text" name="sekretaris_desa" value="<?= e($profil['sekretaris_desa'] ?? '') ?>"></div>
      </div>
      <label>Deskripsi Singkat Desa</label>
      <textarea name="deskripsi"><?= e($profil['deskripsi'] ?? '') ?></textarea>
      <label>Logo Desa</label>
      <input type="file" name="logo" accept=".jpg,.jpeg,.png">
      <?php if (!empty($profil['logo'])): ?><img src="<?= UPLOAD_URL_PRODUK . e($profil['logo']) ?>" style="width:70px;margin-top:10px;border-radius:50%;"><?php endif; ?>
      <button type="submit" name="simpan_profil" class="btn btn-primary btn-block mt-30">Simpan Profil</button>
    </form>
  </div>

  <div class="card">
    <h3>Ubah Password</h3>
    <?php if ($errorPass): ?><p class="alert alert-error"><?= e($errorPass) ?></p><?php endif; ?>
    <form method="post">
      <?= csrf_field() ?>
      <label>Password Lama</label><input type="password" name="password_lama" required>
      <label>Password Baru (min. 8 karakter)</label><input type="password" name="password_baru" required minlength="8">
      <button type="submit" name="ganti_password" class="btn btn-green btn-block mt-30">Ubah Password</button>
    </form>

    <?php if (ADMIN_LEVEL === 'super_admin'): ?>
      <hr style="margin:26px 0;border:none;border-top:1px solid var(--border);">
      <h3>Tambah Admin Baru</h3>
      <p class="muted">Gunakan halaman <code>setup.php</code> untuk membuat akun admin tambahan, atau minta developer menambahkan fitur manajemen pengguna sesuai kebutuhan Anda.</p>
    <?php endif; ?>
  </div>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
