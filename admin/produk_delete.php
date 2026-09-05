<?php
require_once __DIR__ . '/includes/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        $stmt = db()->prepare('SELECT foto FROM produk_umkm WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row && $row['foto'] && file_exists(UPLOAD_DIR_PRODUK . $row['foto'])) {
            unlink(UPLOAD_DIR_PRODUK . $row['foto']);
        }
        $del = db()->prepare('DELETE FROM produk_umkm WHERE id = ?');
        $del->execute([$id]);
        flash_set('success', 'Produk berhasil dihapus.');
    }
}
redirect(BASE_URL . '/admin/produk.php');
