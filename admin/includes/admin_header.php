<?php
// Diasumsikan admin/includes/auth.php sudah di-include sebelum file ini.
$profil = get_profil_desa();
$currentPage = basename($_SERVER['SCRIPT_NAME']);
$menu = [
    'index.php'   => ['🏠', 'Dashboard'],
    'surat.php'   => ['📨', 'E-Surat'],
    'warga.php'   => ['🗂️', 'Data Warga'],
    'apbdes.php'  => ['📊', 'APBDes'],
    'produk.php'  => ['🛍️', 'Lapak Desa'],
    'profil.php'  => ['⚙️', 'Profil Desa'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?>Admin <?= e($profil['nama_desa']) ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<div class="admin-wrap">
  <aside class="admin-sidebar">
    <div class="brand">🏛️ <span>Admin Desa</span></div>
    <nav>
      <?php foreach ($menu as $file => [$icon, $label]): ?>
        <a href="<?= BASE_URL ?>/admin/<?= $file ?>" class="<?= $currentPage === $file ? 'active' : '' ?>"><?= $icon ?> <?= e($label) ?></a>
      <?php endforeach; ?>
      <a href="<?= BASE_URL ?>/admin/logout.php">🚪 Keluar</a>
    </nav>
  </aside>
  <div class="admin-main">
    <div class="admin-topbar">
      <div style="display:flex;align-items:center;gap:12px;">
        <button class="admin-toggle">☰</button>
        <strong><?= isset($pageTitle) ? e($pageTitle) : 'Dashboard' ?></strong>
      </div>
      <div>Halo, <strong><?= e(ADMIN_NAMA) ?></strong></div>
    </div>
    <div class="admin-content">
      <?php if ($msg = flash_get('success')): ?><p class="alert alert-success" data-autohide><?= e($msg) ?></p><?php endif; ?>
      <?php if ($msg = flash_get('error')): ?><p class="alert alert-error"><?= e($msg) ?></p><?php endif; ?>
