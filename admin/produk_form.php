<?php
require_once __DIR__ . '/includes/auth.php';

$id = (int)($_GET['id'] ?? 0);
$data = ['kategori' => 'Produk UMKM', 'nama_item' => '', 'nama_pemilik' => '', 'deskripsi' => '', 'harga' => '', 'no_wa' => '', 'foto' => null, 'tampil' => 1];
if ($id) {
    $stmt = db()->prepare('SELECT * FROM produk_umkm WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    if (!$row) { flash_set('error', 'Data tidak ditemukan.'); redirect(BASE_URL . '/admin/produk.php'); }
    $data = $row;
}
$pageTitle = $id ? 'Edit Produk' : 'Tambah Produk';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $nama = trim($_POST['nama_item'] ?? '');
    if ($nama === '') {
        $error = 'Nama produk/item wajib diisi.';
    } else {
        try {
            $foto = $data['foto'] ?? null;
            $newFoto = handle_upload('foto', UPLOAD_DIR_PRODUK, ['jpg', 'jpeg', 'png']);
            if ($newFoto) {
                if ($foto && file_exists(UPLOAD_DIR_PRODUK . $foto)) unlink(UPLOAD_DIR_PRODUK . $foto);
                $foto = $newFoto;
            }
            $harga = $_POST['harga'] !== '' ? (float)str_replace(['.', ','], ['', '.'], $_POST['harga']) : null;
            $tampil = isset($_POST['tampil']) ? 1 : 0;

            if ($id) {
                $stmt = db()->prepare('UPDATE produk_umkm SET kategori=?, nama_item=?, nama_pemilik=?, deskripsi=?, harga=?, no_wa=?, foto=?, tampil=? WHERE id=?');
                $stmt->execute([$_POST['kategori'], $nama, $_POST['nama_pemilik'] ?: null, $_POST['deskripsi'] ?: null, $harga, $_POST['no_wa'] ?: null, $foto, $tampil, $id]);
            } else {
                $stmt = db()->prepare('INSERT INTO produk_umkm (kategori, nama_item, nama_pemilik, deskripsi, harga, no_wa, foto, tampil) VALUES (?,?,?,?,?,?,?,?)');
                $stmt->execute([$_POST['kategori'], $nama, $_POST['nama_pemilik'] ?: null, $_POST['deskripsi'] ?: null, $harga, $_POST['no_wa'] ?: null, $foto, $tampil]);
            }
            flash_set('success', 'Produk berhasil disimpan.');
            redirect(BASE_URL . '/admin/produk.php');
        } catch (RuntimeException $ex) {
            $error = $ex->getMessage();
        }
    }
    $data = array_merge($data, $_POST);
}

require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="card" style="max-width:640px;">
  <?php if ($error): ?><p class="alert alert-error"><?= e($error) ?></p><?php endif; ?>
  <form method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <label>Kategori</label>
    <select name="kategori">
      <?php foreach (['Produk UMKM','Wisata Desa','Jasa'] as $k): ?>
        <option <?= $data['kategori']===$k?'selected':'' ?>><?= $k ?></option>
      <?php endforeach; ?>
    </select>
    <label>Nama Produk/Item *</label>
    <input type="text" name="nama_item" value="<?= e($data['nama_item']) ?>" required>
    <label>Nama Pemilik/Pengelola</label>
    <input type="text" name="nama_pemilik" value="<?= e($data['nama_pemilik']) ?>">
    <label>Deskripsi</label>
    <textarea name="deskripsi"><?= e($data['deskripsi']) ?></textarea>
    <div class="row">
      <div><label>Harga (Rp, opsional)</label><input type="text" name="harga" value="<?= e($data['harga']) ?>"></div>
      <div><label>No. WhatsApp</label><input type="text" name="no_wa" value="<?= e($data['no_wa']) ?>" placeholder="08xxxxxxxxxx"></div>
    </div>
    <label>Foto (JPG/PNG, maks 2MB)</label>
    <input type="file" name="foto" accept=".jpg,.jpeg,.png">
    <?php if (!empty($data['foto'])): ?><img src="<?= UPLOAD_URL_PRODUK . e($data['foto']) ?>" style="width:90px;margin-top:10px;border-radius:8px;"><?php endif; ?>
    <label style="display:flex;align-items:center;gap:8px;margin-top:16px;">
      <input type="checkbox" name="tampil" style="width:auto;" <?= $data['tampil'] ? 'checked' : '' ?>> Tampilkan di Lapak Desa (publik)
    </label>
    <button type="submit" class="btn btn-primary btn-block mt-30">Simpan Produk</button>
  </form>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
