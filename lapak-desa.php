<?php
$pageTitle = 'Lapak Desa';
require_once __DIR__ . '/includes/header.php';

$kategoriFilter = $_GET['kategori'] ?? '';
$sql = 'SELECT * FROM produk_umkm WHERE tampil = 1';
$params = [];
if ($kategoriFilter !== '' && in_array($kategoriFilter, ['Produk UMKM', 'Wisata Desa', 'Jasa'], true)) {
    $sql .= ' AND kategori = ?';
    $params[] = $kategoriFilter;
}
$sql .= ' ORDER BY created_at DESC';
$stmt = db()->prepare($sql);
$stmt->execute($params);
$produk = $stmt->fetchAll();
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Lapak Desa</span>
      <h2>Produk UMKM &amp; Wisata Desa Daya Sakti</h2>
      <p class="muted">Dukung ekonomi warga dengan membeli produk lokal atau berkunjung ke destinasi wisata desa.</p>
    </div>

    <div class="center" style="margin-bottom:30px;">
      <a href="?kategori=" class="btn btn-small <?= $kategoriFilter === '' ? 'btn-green' : 'btn-outline' ?>" style="<?= $kategoriFilter === '' ? '' : 'border-color:var(--hijau);color:var(--hijau);' ?>">Semua</a>
      <a href="?kategori=Produk+UMKM" class="btn btn-small <?= $kategoriFilter === 'Produk UMKM' ? 'btn-green' : 'btn-outline' ?>" style="<?= $kategoriFilter === 'Produk UMKM' ? '' : 'border-color:var(--hijau);color:var(--hijau);' ?>">Produk UMKM</a>
      <a href="?kategori=Wisata+Desa" class="btn btn-small <?= $kategoriFilter === 'Wisata Desa' ? 'btn-green' : 'btn-outline' ?>" style="<?= $kategoriFilter === 'Wisata Desa' ? '' : 'border-color:var(--hijau);color:var(--hijau);' ?>">Wisata Desa</a>
      <a href="?kategori=Jasa" class="btn btn-small <?= $kategoriFilter === 'Jasa' ? 'btn-green' : 'btn-outline' ?>" style="<?= $kategoriFilter === 'Jasa' ? '' : 'border-color:var(--hijau);color:var(--hijau);' ?>">Jasa</a>
    </div>

    <?php if (empty($produk)): ?>
      <p class="alert alert-info center">Belum ada produk yang ditampilkan pada kategori ini.</p>
    <?php else: ?>
      <div class="grid grid-3">
        <?php foreach ($produk as $p): ?>
          <div class="card">
            <?php if (!empty($p['foto'])): ?>
              <img src="<?= UPLOAD_URL_PRODUK . e($p['foto']) ?>" alt="<?= e($p['nama_item']) ?>" style="border-radius:8px;margin-bottom:14px;height:170px;object-fit:cover;width:100%;">
            <?php else: ?>
              <div class="icon" style="width:100%;height:170px;font-size:2.4rem;">🛍️</div>
            <?php endif; ?>
            <span class="badge badge-info"><?= e($p['kategori']) ?></span>
            <h3 style="margin-top:10px;"><?= e($p['nama_item']) ?></h3>
            <p><?= nl2br(e($p['deskripsi'])) ?></p>
            <?php if (!empty($p['harga'])): ?><p><strong><?= rupiah($p['harga']) ?></strong></p><?php endif; ?>
            <?php if (!empty($p['nama_pemilik'])): ?><p class="muted">Oleh: <?= e($p['nama_pemilik']) ?></p><?php endif; ?>
            <?php if (!empty($p['no_wa'])): ?>
              <a href="https://wa.me/<?= e(preg_replace('/^0/', '62', only_digits($p['no_wa']))) ?>" target="_blank" rel="noopener" class="btn btn-green btn-small">Hubungi via WhatsApp</a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
