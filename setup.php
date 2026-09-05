<?php
/**
 * SETUP AWAL — jalankan sekali saja setelah import database.php,
 * lalu HAPUS atau ganti nama file ini demi keamanan.
 */
require_once __DIR__ . '/config/config.php';

$sudahAdaAdmin = (int)db()->query('SELECT COUNT(*) c FROM admin_users')->fetch()['c'] > 0;
$error = '';
$sukses = false;

if ($sudahAdaAdmin) {
    // Sudah pernah setup — jangan izinkan buat akun lagi dari sini.
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $nama     = trim($_POST['nama_lengkap'] ?? '');

    if (strlen($username) < 4) {
        $error = 'Username minimal 4 karakter.';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter.';
    } elseif ($nama === '') {
        $error = 'Nama lengkap wajib diisi.';
    } else {
        $stmt = db()->prepare('INSERT INTO admin_users (username, password, nama_lengkap, level) VALUES (?, ?, ?, "super_admin")');
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $nama]);
        $sukses = true;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Setup Awal — Sistem Desa Daya Sakti</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-page">
<div class="auth-box">
    <h1>Setup Akun Admin</h1>
    <?php if ($sudahAdaAdmin): ?>
        <p class="alert alert-info">Akun admin sudah pernah dibuat. Untuk keamanan, file <code>setup.php</code> ini sebaiknya <strong>dihapus dari server</strong>.</p>
        <a class="btn btn-primary" href="admin/login.php">Ke Halaman Login Admin</a>
    <?php elseif ($sukses): ?>
        <p class="alert alert-success">Akun admin berhasil dibuat! Silakan login, lalu segera hapus file <code>setup.php</code> dari server.</p>
        <a class="btn btn-primary" href="admin/login.php">Login Sekarang</a>
    <?php else: ?>
        <p class="muted">Buat akun administrator pertama untuk mengelola sistem desa.</p>
        <?php if ($error): ?><p class="alert alert-error"><?= e($error) ?></p><?php endif; ?>
        <form method="post">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" required>
            <label>Username</label>
            <input type="text" name="username" required minlength="4">
            <label>Password</label>
            <input type="password" name="password" required minlength="8">
            <button type="submit" class="btn btn-primary">Buat Akun Admin</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
