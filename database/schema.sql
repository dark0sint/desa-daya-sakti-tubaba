-- =========================================================
-- Database: db_desa_dayasakti
-- Sistem Informasi Desa Daya Sakti, Kec. Tumijajar, Kab. Tulang Bawang Barat
-- =========================================================

CREATE DATABASE IF NOT EXISTS db_desa_dayasakti CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_desa_dayasakti;

-- Admin / operator desa
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(150) NOT NULL,
    level ENUM('super_admin','admin') NOT NULL DEFAULT 'admin',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Profil desa (dipakai di kop surat & landing page)
CREATE TABLE desa_profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_desa VARCHAR(150) NOT NULL DEFAULT 'Desa Daya Sakti',
    kecamatan VARCHAR(100) NOT NULL DEFAULT 'Tumijajar',
    kabupaten VARCHAR(100) NOT NULL DEFAULT 'Tulang Bawang Barat',
    provinsi VARCHAR(100) NOT NULL DEFAULT 'Lampung',
    alamat_kantor VARCHAR(255) DEFAULT NULL,
    no_telp VARCHAR(30) DEFAULT NULL,
    email VARCHAR(100) DEFAULT NULL,
    kepala_desa VARCHAR(150) DEFAULT NULL,
    sekretaris_desa VARCHAR(150) DEFAULT NULL,
    logo VARCHAR(255) DEFAULT NULL,
    deskripsi TEXT
) ENGINE=InnoDB;

-- Data kependudukan warga
CREATE TABLE warga (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nik VARCHAR(20) NOT NULL UNIQUE,
    no_kk VARCHAR(20) NOT NULL,
    nama_lengkap VARCHAR(150) NOT NULL,
    jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
    tempat_lahir VARCHAR(100) DEFAULT NULL,
    tanggal_lahir DATE DEFAULT NULL,
    alamat VARCHAR(255) DEFAULT NULL,
    rt VARCHAR(5) DEFAULT NULL,
    rw VARCHAR(5) DEFAULT NULL,
    dusun VARCHAR(100) DEFAULT NULL,
    agama VARCHAR(30) DEFAULT NULL,
    pekerjaan VARCHAR(100) DEFAULT NULL,
    status_perkawinan ENUM('Belum Kawin','Kawin','Cerai Hidup','Cerai Mati') DEFAULT 'Belum Kawin',
    status_hidup ENUM('Hidup','Meninggal','Pindah') NOT NULL DEFAULT 'Hidup',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nik (nik),
    INDEX idx_kk (no_kk),
    INDEX idx_nama (nama_lengkap)
) ENGINE=InnoDB;

-- Pengajuan surat (E-Surat Kilat)
CREATE TABLE surat_pengajuan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_pengajuan VARCHAR(30) NOT NULL UNIQUE,
    nik VARCHAR(20) NOT NULL,
    nama_pemohon VARCHAR(150) NOT NULL,
    no_hp VARCHAR(20) DEFAULT NULL,
    jenis_surat VARCHAR(100) NOT NULL,
    keperluan TEXT NOT NULL,
    file_pendukung VARCHAR(255) DEFAULT NULL,
    status ENUM('Menunggu','Diproses','Selesai','Ditolak') NOT NULL DEFAULT 'Menunggu',
    catatan_admin VARCHAR(255) DEFAULT NULL,
    diproses_oleh INT DEFAULT NULL,
    tanggal_pengajuan DATETIME DEFAULT CURRENT_TIMESTAMP,
    tanggal_selesai DATETIME DEFAULT NULL,
    INDEX idx_nomor (nomor_pengajuan),
    INDEX idx_nik_pengajuan (nik),
    INDEX idx_status (status),
    FOREIGN KEY (diproses_oleh) REFERENCES admin_users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- APBDes (untuk Dashboard Transparansi)
CREATE TABLE apbdes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tahun_anggaran YEAR NOT NULL,
    jenis ENUM('Pendapatan','Belanja','Pembiayaan') NOT NULL,
    kategori VARCHAR(150) NOT NULL,
    uraian VARCHAR(255) DEFAULT NULL,
    anggaran DECIMAL(15,2) NOT NULL DEFAULT 0,
    realisasi DECIMAL(15,2) NOT NULL DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tahun (tahun_anggaran)
) ENGINE=InnoDB;

-- Lapak Desa: UMKM & Wisata
CREATE TABLE produk_umkm (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori ENUM('Produk UMKM','Wisata Desa','Jasa') NOT NULL DEFAULT 'Produk UMKM',
    nama_item VARCHAR(150) NOT NULL,
    nama_pemilik VARCHAR(150) DEFAULT NULL,
    deskripsi TEXT,
    harga DECIMAL(12,2) DEFAULT NULL,
    no_wa VARCHAR(20) DEFAULT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    tampil TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Catatan: akun admin PERTAMA dibuat lewat halaman setup.php (bukan lewat SQL),
-- agar password langsung di-hash dengan aman sesuai server PHP Anda.
-- Jangan hapus baris ini, cukup jalankan setup.php sekali setelah import database.

INSERT INTO desa_profile (nama_desa, kecamatan, kabupaten, provinsi, alamat_kantor, no_telp, email, kepala_desa)
VALUES ('Desa Daya Sakti', 'Tumijajar', 'Tulang Bawang Barat', 'Lampung', 'Jl. Poros Desa Daya Sakti, Kec. Tumijajar', '0800-0000-0000', 'admin@dayasakti.desa.id', 'Nama Kepala Desa');
