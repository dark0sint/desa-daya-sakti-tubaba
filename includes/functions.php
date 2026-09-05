<?php
/** Escape output aman dari XSS */
function e($str): string
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

/** Redirect helper */
function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

/** Flash message sederhana via session */
function flash_set(string $key, string $msg): void
{
    $_SESSION['flash'][$key] = $msg;
}
function flash_get(string $key): ?string
{
    if (!empty($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

/** CSRF token */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}
function csrf_verify(): bool
{
    return isset($_POST['csrf_token']) && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

/** Format Rupiah */
function rupiah($angka): string
{
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

/** Format tanggal Indonesia */
function tanggal_indo($tanggal, bool $withTime = false): string
{
    if (empty($tanggal)) return '-';
    $bulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $ts = is_numeric($tanggal) ? $tanggal : strtotime($tanggal);
    $hasil = date('d', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    if ($withTime) $hasil .= ', ' . date('H:i', $ts) . ' WIB';
    return $hasil;
}

/** Generate nomor pengajuan unik, mis: 20260905-XXXX */
function generate_nomor_pengajuan(): string
{
    return date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
}

/** Validasi & simpan file upload. Return nama file baru atau null. */
function handle_upload(string $inputName, string $targetDir, array $allowedExt = ['jpg', 'jpeg', 'png', 'pdf']): ?string
{
    if (empty($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    $file = $_FILES[$inputName];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Terjadi kesalahan saat mengunggah file.');
    }
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        throw new RuntimeException('Ukuran file maksimal 2MB.');
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExt, true)) {
        throw new RuntimeException('Format file tidak didukung. Gunakan: ' . implode(', ', $allowedExt));
    }
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    $newName = uniqid('f_', true) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $targetDir . $newName)) {
        throw new RuntimeException('Gagal menyimpan file.');
    }
    return $newName;
}

/** Sanitasi NIK / angka saja */
function only_digits(string $str): string
{
    return preg_replace('/\D/', '', $str);
}

/** Ambil profil desa (cached per-request) */
function get_profil_desa(): array
{
    static $profil = null;
    if ($profil === null) {
        $stmt = db()->query('SELECT * FROM desa_profile LIMIT 1');
        $profil = $stmt->fetch() ?: [
            'nama_desa' => NAMA_DESA,
            'kecamatan' => NAMA_KECAMATAN,
            'kabupaten' => NAMA_KABUPATEN,
            'alamat_kantor' => '-',
            'no_telp' => '-',
            'email' => '-',
            'kepala_desa' => '-',
            'logo' => null,
        ];
    }
    return $profil;
}

/** Daftar jenis surat yang bisa diajukan warga */
function daftar_jenis_surat(): array
{
    return [
        'Surat Pengantar KTP',
        'Surat Pengantar Kartu Keluarga (KK)',
        'Surat Keterangan Domisili',
        'Surat Keterangan Tidak Mampu (SKTM)',
        'Surat Keterangan Usaha (SKU)',
        'Surat Keterangan Kelahiran',
        'Surat Keterangan Kematian',
        'Surat Pengantar Nikah',
        'Surat Keterangan Belum Menikah',
        'Surat Keterangan Pindah',
        'Lainnya',
    ];
}
