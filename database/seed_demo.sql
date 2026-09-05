-- =========================================================
-- Data contoh (OPSIONAL) — jalankan setelah schema.sql
-- Berguna untuk melihat tampilan dashboard & lapak desa terisi
-- sebelum admin memasukkan data asli. Hapus data ini kapan saja
-- lewat panel admin jika tidak diperlukan.
-- =========================================================
USE db_desa_dayasakti;

INSERT INTO apbdes (tahun_anggaran, jenis, kategori, uraian, anggaran, realisasi) VALUES
(2026, 'Pendapatan', 'Dana Desa', 'Dana Desa dari APBN', 850000000, 850000000),
(2026, 'Pendapatan', 'Alokasi Dana Desa', 'ADD dari Kabupaten', 320000000, 300000000),
(2026, 'Pendapatan', 'Pendapatan Asli Desa', 'Hasil usaha & aset desa', 45000000, 38000000),
(2026, 'Belanja', 'Bidang Pembangunan', 'Perbaikan jalan & drainase desa', 520000000, 410000000),
(2026, 'Belanja', 'Bidang Pemerintahan', 'Operasional kantor & penghasilan perangkat', 260000000, 260000000),
(2026, 'Belanja', 'Bidang Pemberdayaan', 'Pelatihan UMKM & kelompok tani', 90000000, 62000000),
(2026, 'Belanja', 'Bidang Kemasyarakatan', 'Bantuan sosial & kesehatan warga', 130000000, 95000000);

INSERT INTO produk_umkm (kategori, nama_item, nama_pemilik, deskripsi, harga, no_wa, tampil) VALUES
('Produk UMKM', 'Keripik Pisang Daya Sakti', 'Bu Aminah', 'Keripik pisang renyah aneka rasa, diproduksi langsung oleh UMKM desa.', 15000, '081234567890', 1),
('Produk UMKM', 'Kopi Robusta Tumijajar', 'Pak Slamet', 'Kopi bubuk asli hasil kebun warga desa, disangrai tradisional.', 35000, '081234567891', 1),
('Wisata Desa', 'Kolam Pemancingan Desa Daya Sakti', 'Pokdarwis Desa', 'Spot pemancingan dan wisata keluarga dengan suasana asri pedesaan.', NULL, '081234567892', 1);
