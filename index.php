<?php
$pageTitle = 'Beranda';
require_once __DIR__ . '/includes/header.php';

$totalSurat = (int) db()->query("SELECT COUNT(*) c FROM surat_pengajuan WHERE status='Selesai'")->fetch()['c'];
$totalWarga = (int) db()->query("SELECT COUNT(*) c FROM warga")->fetch()['c'];
$totalUmkm  = (int) db()->query("SELECT COUNT(*) c FROM produk_umkm WHERE tampil=1")->fetch()['c'];
?>

<section class="hero">
  <div class="container">
    <div>
      <span class="hero-badge">Sistem Informasi Desa Digital</span>
      <h1>Website & Aplikasi Desa Daya Sakti — Alat Kerja Nyata, Bukan Sekadar Pajangan.</h1>
      <p>Layanan surat online tanpa antre, transparansi APBDes yang bisa dilihat semua warga, database kependudukan yang tercari dalam detik, dan lapak digital untuk UMKM &amp; wisata desa — semuanya dalam satu sistem yang ringan dan mudah dipakai.</p>
      <div class="hero-actions">
        <a href="<?= BASE_URL ?>/ajukan-surat.php" class="btn btn-primary">Ajukan Surat Sekarang</a>
        <a href="<?= BASE_URL ?>/transparansi.php" class="btn btn-outline">Lihat Transparansi APBDes</a>
      </div>
    </div>
    <div class="hero-art">
      <ul>
        <li>✅ <div><strong><?= $totalSurat ?>+</strong> surat telah diselesaikan tanpa antre</div></li>
        <li>👥 <div><strong><?= $totalWarga ?></strong> data warga tercatat rapi &amp; aman</div></li>
        <li>🛍️ <div><strong><?= $totalUmkm ?></strong> produk UMKM &amp; wisata dipromosikan online</div></li>
      </ul>
    </div>
  </div>
</section>

<section class="section" id="fitur">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Fitur Utama</span>
      <h2>Empat Alat Kerja Nyata untuk Desa</h2>
      <p class="muted">Dirancang bukan untuk pajangan, tapi untuk menyelesaikan pekerjaan harian pemerintah desa.</p>
    </div>
    <div class="grid grid-4">
      <div class="card">
        <div class="icon">📨</div>
        <h3>E-Surat Kilat</h3>
        <p>Warga ajukan dari HP, admin tinggal cetak. Tanpa antrean di kantor desa.</p>
      </div>
      <div class="card">
        <div class="icon">📊</div>
        <h3>Dashboard Transparansi</h3>
        <p>Grafik APBDes agar warga tahu kerja nyata pemerintah desa, secara terbuka.</p>
      </div>
      <div class="card">
        <div class="icon">🗂️</div>
        <h3>Database Warga</h3>
        <p>Cari data NIK atau KK hanya dalam hitungan detik, tanpa bongkar arsip fisik.</p>
      </div>
      <div class="card">
        <div class="icon">🛍️</div>
        <h3>Lapak Desa</h3>
        <p>Promosikan produk UMKM dan wisata desa ke dunia luar secara online.</p>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt" id="kenapa">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Kenapa Sistem Ini</span>
      <h2>Dibangun untuk Kondisi Desa yang Sesungguhnya</h2>
    </div>
    <div class="grid grid-3">
      <div class="card">
        <div class="icon">🙂</div>
        <h3>Mudah Dioperasikan</h3>
        <p>Tampilan sederhana dan intuitif. Perangkat desa tidak perlu jago komputer untuk bisa mengoperasikan sistem ini.</p>
      </div>
      <div class="card">
        <div class="icon">⚡</div>
        <h3>Ringan &amp; Cepat</h3>
        <p>Dibangun dengan PHP Native tanpa beban framework berat — aksesnya tetap ngebut meski sinyal desa sedang tidak stabil.</p>
      </div>
      <div class="card">
        <div class="icon">🌐</div>
        <h3>Domain Resmi .desa.id</h3>
        <p>Kami bantu urus legalitas domain resmi pemerintah agar desa terlihat profesional, sah, dan terpercaya.</p>
      </div>
    </div>
  </div>
</section>

<section class="section" id="pendampingan">
  <div class="container">
    <div class="section-head">
      <span class="eyebrow">Pendampingan Total</span>
      <h2>Kami Tidak Lepas Tangan Setelah Instalasi</h2>
      <p class="muted">Perangkat desa dilatih secara intensif sampai benar-benar mahir mengoperasikan sistem.</p>
    </div>
    <div class="steps" style="max-width:640px;margin:0 auto;">
      <div class="step">
        <h4>1. Konsultasi</h4>
        <p>Diskusi kebutuhan fitur spesifik desa Anda agar sistem yang dibangun tepat sasaran.</p>
      </div>
      <div class="step">
        <h4>2. Instalasi &amp; Setup</h4>
        <p>Kami pasangkan sistem di domain resmi desa Anda (.desa.id) dengan aman dan siap pakai.</p>
      </div>
      <div class="step">
        <h4>3. Pelatihan</h4>
        <p>Perangkat desa kami bimbing langsung cara mengoperasikannya sampai benar-benar mahir.</p>
      </div>
    </div>
  </div>
</section>

<section class="section section-alt">
  <div class="container">
    <div class="cta-banner">
      <h2>Siap Membuat Layanan Desa Lebih Cepat &amp; Transparan?</h2>
      <p>Coba langsung fitur E-Surat Kilat, atau lihat bagaimana APBDes desa ini dikelola secara terbuka.</p>
      <div class="hero-actions" style="justify-content:center;">
        <a href="<?= BASE_URL ?>/ajukan-surat.php" class="btn btn-primary">Ajukan Surat</a>
        <a href="<?= BASE_URL ?>/lapak-desa.php" class="btn btn-outline" style="border-color:#fff;color:#fff;">Kunjungi Lapak Desa</a>
      </div>
    </div>
  </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
