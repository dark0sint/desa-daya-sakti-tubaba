<?php $profil = get_profil_desa(); ?>
<footer class="site-footer">
  <div class="container footer-grid">
    <div>
      <h4><?= e($profil['nama_desa']) ?></h4>
      <p><?= e($profil['alamat_kantor']) ?></p>
      <p>Kec. <?= e($profil['kecamatan']) ?>, Kab. <?= e($profil['kabupaten']) ?>, <?= e($profil['provinsi'] ?? 'Lampung') ?></p>
    </div>
    <div>
      <h4>Layanan</h4>
      <p><a href="<?= BASE_URL ?>/ajukan-surat.php">Ajukan Surat</a></p>
      <p><a href="<?= BASE_URL ?>/transparansi.php">Transparansi APBDes</a></p>
      <p><a href="<?= BASE_URL ?>/lapak-desa.php">Lapak Desa</a></p>
    </div>
    <div>
      <h4>Kontak</h4>
      <p>📞 <?= e($profil['no_telp']) ?></p>
      <p>✉️ <?= e($profil['email']) ?></p>
    </div>
  </div>
  <div class="footer-bottom">
    &copy; <?= date('Y') ?> Pemerintah <?= e($profil['nama_desa']) ?>. Seluruh hak cipta dilindungi.
  </div>
</footer>
<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
</body>
</html>
