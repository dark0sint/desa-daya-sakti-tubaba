<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Lapak Desa';

$data = db()->query('SELECT * FROM produk_umkm ORDER BY created_at DESC')->fetchAll();
require_once __DIR__ . '/includes/admin_header.php';
?>
<div class="toolbar">
  <h3 class="mb-0">Daftar Produk UMKM &amp; Wisata</h3>
  <a href="<?= BASE_URL ?>/admin/produk_form.php" class="btn btn-primary">+ Tambah Produk</a>
</div>

<div class="table-wrap">
  <table>
    <thead><tr><th>Foto</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Tampil</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php if (empty($data)): ?>
        <tr><td colspan="6" class="center muted">Belum ada produk.</td></tr>
      <?php endif; ?>
      <?php foreach ($data as $p): ?>
        <tr>
          <td><?php if ($p['foto']): ?><img src="<?= UPLOAD_URL_PRODUK . e($p['foto']) ?>" style="width:50px;height:50px;object-fit:cover;border-radius:6px;"><?php else: ?>—<?php endif; ?></td>
          <td><?= e($p['nama_item']) ?></td>
          <td><?= e($p['kategori']) ?></td>
          <td><?= $p['harga'] ? rupiah($p['harga']) : '-' ?></td>
          <td><?= $p['tampil'] ? '<span class="badge badge-success">Ya</span>' : '<span class="badge badge-danger">Tidak</span>' ?></td>
          <td>
            <a href="<?= BASE_URL ?>/admin/produk_form.php?id=<?= (int)$p['id'] ?>" class="btn btn-small btn-green">Edit</a>
            <form method="post" action="<?= BASE_URL ?>/admin/produk_delete.php" style="display:inline;" onsubmit="return confirm('Hapus produk ini?');">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
              <button type="submit" class="btn btn-small btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
