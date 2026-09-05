<?php
/**
 * Konfigurasi umum aplikasi.
 */
error_reporting(E_ALL);
ini_set('display_errors', '0'); // matikan di produksi, aktifkan (1) saat debug lokal

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
    ]);
}

// Deteksi otomatis base URL (aman untuk subfolder/domain apapun)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$scriptDir = rtrim(preg_replace('#/(admin)$#', '', $scriptDir), '/');
define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . $scriptDir);

define('NAMA_DESA', 'Desa Daya Sakti');
define('NAMA_KECAMATAN', 'Tumijajar');
define('NAMA_KABUPATEN', 'Tulang Bawang Barat');
define('NAMA_PROVINSI', 'Lampung');

define('UPLOAD_DIR_PRODUK', __DIR__ . '/../uploads/produk/');
define('UPLOAD_URL_PRODUK', BASE_URL . '/uploads/produk/');
define('UPLOAD_DIR_SURAT', __DIR__ . '/../uploads/surat/');
define('MAX_UPLOAD_SIZE', 2 * 1024 * 1024); // 2MB

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/functions.php';
