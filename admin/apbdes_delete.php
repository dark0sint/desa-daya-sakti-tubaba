<?php
require_once __DIR__ . '/includes/auth.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        $stmt = db()->prepare('DELETE FROM apbdes WHERE id = ?');
        $stmt->execute([$id]);
        flash_set('success', 'Pos anggaran berhasil dihapus.');
    }
}
redirect(BASE_URL . '/admin/apbdes.php');
