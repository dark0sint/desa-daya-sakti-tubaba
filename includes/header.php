<?php
require_once __DIR__ . '/../config/config.php';
$profil = get_profil_desa();
$current = basename($_SERVER['SCRIPT_NAME']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($pageTitle) ? e($pageTitle) . ' — ' : '' ?><?= e($profil['nama_desa']) ?></title>
<meta name="description" content="Website resmi <?= e($profil['nama_desa']) ?>, Kecamatan <?= e($profil['kecamatan']) ?>, Kabupaten <?= e($profil['kabupaten']) ?>. Layanan surat online, transparansi APBDes, data warga, dan lapak UMKM desa.">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="navbar">
  <div class="nav-inner">
    <a href="<?= BASE_URL ?>/index.php" class="brand">
      <?php if (!empty($profil['logo'])): ?>
        <img src="<?= BASE_URL . '/uploads/produk/' . e($profil['logo']) ?>" alt="Logo">
      <?php else: ?>🏛️<?php endif; ?>
      <span><?= e($profil['nama_desa']) ?></span>
    </a>
    <button class="nav-toggle" aria-label="Menu">☰</button>
    <nav class="nav-links">
      <a href="<?= BASE_URL ?>/index.php" class="<?= $current === 'index.php' ? 'active' : '' ?>">Beranda</a>
      <a href="<?= BASE_URL ?>/ajukan-surat.php">E-Surat</a>
      <a href="<?= BASE_URL ?>/cek-status.php">Cek Status</a>
      <a href="<?= BASE_URL ?>/transparansi.php">Transparansi APBDes</a>
      <a href="<?= BASE_URL ?>/lapak-desa.php">Lapak Desa</a>
      <a href="<?= BASE_URL ?>/admin/login.php" class="nav-cta">Login Admin</a>
    </nav>
  </div>
</header>
