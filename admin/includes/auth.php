<?php
require_once __DIR__ . '/../../config/config.php';

if (empty($_SESSION['admin_id'])) {
    redirect(BASE_URL . '/admin/login.php');
}

// Regenerate session id occasionally to reduce fixation risk
if (empty($_SESSION['last_regen']) || (time() - $_SESSION['last_regen']) > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regen'] = time();
}

define('ADMIN_ID', $_SESSION['admin_id']);
define('ADMIN_NAMA', $_SESSION['admin_nama'] ?? 'Admin');
define('ADMIN_LEVEL', $_SESSION['admin_level'] ?? 'admin');
