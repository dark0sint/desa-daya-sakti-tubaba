<?php
require_once __DIR__ . '/../config/config.php';

if (!empty($_SESSION['admin_id'])) {
    redirect(BASE_URL . '/admin/index.php');
}

$error = '';
$_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
$_SESSION['login_block_until'] = $_SESSION['login_block_until'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (time() < $_SESSION['login_block_until']) {
        $error = 'Terlalu banyak percobaan gagal. Coba lagi dalam beberapa menit.';
    } elseif (!csrf_verify()) {
        $error = 'Sesi kedaluwarsa, silakan coba lagi.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $stmt = db()->prepare('SELECT * FROM admin_users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id']    = $user['id'];
            $_SESSION['admin_nama']  = $user['nama_lengkap'];
            $_SESSION['admin_level'] = $user['level'];
            $_SESSION['login_attempts'] = 0;
            redirect(BASE_URL . '/admin/index.php');
        } else {
            $_SESSION['login_attempts']++;
            if ($_SESSION['login_attempts'] >= 5) {
                $_SESSION['login_block_until'] = time() + 300; // blokir 5 menit
                $error = 'Terlalu banyak percobaan gagal. Coba lagi dalam 5 menit.';
            } else {
                $error = 'Username atau password salah.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin — <?= e(NAMA_DESA) ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body class="auth-page">
<div class="auth-box">
  <h1>🏛️ Login Admin Desa</h1>
  <p class="muted">Sistem Informasi <?= e(NAMA_DESA) ?></p>
  <?php if ($error): ?><p class="alert alert-error"><?= e($error) ?></p><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <label>Username</label>
    <input type="text" name="username" required autofocus>
    <label>Password</label>
    <input type="password" name="password" required>
    <button type="submit" class="btn btn-primary btn-block mt-30">Masuk</button>
  </form>
  <p class="center mt-30"><a href="<?= BASE_URL ?>/index.php">&larr; Kembali ke Beranda</a></p>
</div>
</body>
</html>
