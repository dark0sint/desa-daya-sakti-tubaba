<?php
/**
 * Konfigurasi koneksi database.
 * Ubah nilai di bawah sesuai kredensial hosting/server Anda.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'db_desa_dayasakti');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            http_response_code(500);
            die('Koneksi database gagal. Hubungi administrator sistem. (' . htmlspecialchars($e->getMessage()) . ')');
        }
    }
    return $pdo;
}
