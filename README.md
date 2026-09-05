# Sistem Informasi Desa Daya Sakti

Website & aplikasi desa berbasis **PHP Native (PHP + MySQL, tanpa framework)** untuk
Desa Daya Sakti, Kecamatan Tumijajar, Kabupaten Tulang Bawang Barat.

Fitur utama:
- **E-Surat Kilat** — warga ajukan surat dari HP, admin tinggal proses & cetak.
- **Dashboard Transparansi APBDes** — grafik pendapatan/belanja desa (Chart.js).
- **Database Warga** — pencarian cepat berdasarkan NIK/KK.
- **Lapak Desa** — katalog produk UMKM & wisata desa, terhubung langsung ke WhatsApp.
- **Panel Admin** — login aman, kelola semua data di atas dari satu tempat.

Kenapa PHP Native (bukan Laravel)? Supaya sistem tetap **ringan, cepat diakses
walau sinyal desa lemah, dan mudah di-hosting di paket shared hosting murah**
tanpa perlu Composer/dependency berat. Tidak ada komponen Python — seluruh
aplikasi (frontend + backend) berjalan dalam satu bahasa (PHP) agar mudah
dirawat oleh operator desa/hosting lokal.

---

## 1. Kebutuhan Server

- PHP **7.4 atau lebih baru** (disarankan PHP 8.1+)
- Ekstensi PHP: `pdo_mysql`, `mbstring`, `fileinfo`, `gd` (opsional untuk gambar)
- MySQL / MariaDB 5.7+
- Apache dengan `mod_rewrite` & `mod_headers` (untuk `.htaccess`) — atau Nginx (lihat catatan di bawah)

## 2. Instalasi

1. **Upload semua file** ke folder hosting Anda (misal `public_html/` atau subdomain `.desa.id`).
2. **Buat database** baru di cPanel/phpMyAdmin, misal `db_desa_dayasakti`.
3. **Import** file `database/schema.sql` ke database tersebut.
4. Edit `config/database.php`, sesuaikan:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'db_desa_dayasakti');
   define('DB_USER', 'user_database_anda');
   define('DB_PASS', 'password_database_anda');
   ```
5. Pastikan folder berikut **bisa ditulis (writable, chmod 755/775)** oleh web server:
   - `uploads/produk/`
   - `uploads/surat/`
6. Buka `https://domainanda.desa.id/setup.php` di browser untuk **membuat akun admin pertama**.
   Setelah akun berhasil dibuat, **hapus atau ganti nama file `setup.php`** dari server (penting untuk keamanan).
7. Login admin di `https://domainanda.desa.id/admin/login.php`.
8. Buka menu **Profil Desa** untuk melengkapi nama kepala desa, alamat kantor, logo, dll — data ini otomatis dipakai di kop surat cetak dan halaman publik.
9. Tambahkan data **APBDes** (menu APBDes) agar grafik transparansi tampil di `transparansi.php`.

## 3. Struktur Folder

```
├── index.php                # Landing page publik
├── ajukan-surat.php         # Form pengajuan surat (publik)
├── cek-status.php           # Cek status pengajuan (publik)
├── transparansi.php         # Dashboard APBDes (publik)
├── lapak-desa.php           # Katalog UMKM & wisata (publik)
├── setup.php                # Setup akun admin pertama (hapus setelah dipakai)
├── admin/                   # Seluruh panel admin (butuh login)
│   ├── login.php / logout.php
│   ├── index.php            # Dashboard admin
│   ├── surat.php / surat_proses.php / cetak_surat.php
│   ├── warga.php / warga_form.php / warga_delete.php
│   ├── apbdes.php / apbdes_form.php / apbdes_delete.php
│   ├── produk.php / produk_form.php / produk_delete.php
│   └── profil.php
├── config/                  # Koneksi DB & konfigurasi (dilindungi .htaccess)
├── includes/                # Header/footer publik + fungsi helper
├── database/schema.sql      # Struktur database
├── uploads/                 # File upload (dilindungi dari eksekusi PHP)
└── assets/                  # CSS & JS (murni, tanpa framework berat)
```

## 4. Catatan Keamanan yang Sudah Diterapkan

- Password admin di-hash dengan `password_hash()` (bcrypt), **tidak pernah** disimpan plain text.
- Semua query database memakai **PDO prepared statements** (aman dari SQL Injection).
- Proteksi **CSRF token** pada semua form yang mengubah data (tambah/edit/hapus/login).
- Percobaan login dibatasi (maks. 5x gagal → diblokir sementara 5 menit).
- Semua output ditampilkan lewat `htmlspecialchars()` (aman dari XSS).
- Folder `uploads/` diblokir dari eksekusi PHP lewat `.htaccess`, mencegah upload file berbahaya dieksekusi sebagai skrip.
- Folder `config/` diblokir total dari akses langsung browser.
- Validasi tipe & ukuran file upload (maks 2MB, ekstensi terbatas).

**Wajib dilakukan setelah instalasi:**
- Hapus/rename `setup.php`.
- Ganti password default admin secara berkala lewat menu Profil Desa.
- Aktifkan HTTPS (SSL) di domain `.desa.id` Anda, lalu aktifkan baris redirect HTTPS di `.htaccess`.

## 5. Untuk Server Nginx

File `.htaccess` hanya berlaku di Apache. Jika hosting Anda memakai Nginx, tambahkan konfigurasi setara di block server, misalnya:
```nginx
location ~* ^/uploads/.*\.(php|phtml)$ { deny all; }
location ~* ^/config/ { deny all; }
```

## 6. Kustomisasi Jenis Surat

Daftar jenis surat yang bisa diajukan warga diatur di fungsi `daftar_jenis_surat()`
pada file `includes/functions.php`. Tambahkan/ubah sesuai kebutuhan desa.

## 7. Lisensi & Dukungan

Sistem ini dibangun khusus untuk Desa Daya Sakti. Untuk pendampingan lanjutan
(instalasi domain resmi `.desa.id`, pelatihan perangkat desa, atau penambahan
fitur khusus), silakan hubungi tim pengembang Anda.
