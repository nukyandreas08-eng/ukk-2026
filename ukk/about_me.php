<?php
require_once __DIR__ . '/includes/cek_session.php';
$nama = 'Nucky Andreas Febrian';
$kelas = 'XII RPL 1';
$embed = (($_GET['embed'] ?? '') === '1');
?>
<?php if (!$embed): ?><!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>About Me</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet"></head><body><?php endif; ?>
<style>
.about-page{max-width:1200px;margin:0 auto;padding:clamp(1rem,3vw,2.5rem)}
.about-hero{background:linear-gradient(135deg,#0d6efd,#5138b8);color:#fff;border-radius:24px;padding:clamp(2rem,5vw,4rem);box-shadow:0 12px 32px rgba(36,70,160,.18)}
.about-hero h2{font-size:clamp(2rem,4vw,3rem);line-height:1.15}
.about-hero p{font-size:clamp(1rem,1.5vw,1.2rem);line-height:1.85;max-width:1000px}
.about-card{height:100%;border:0;border-radius:20px;box-shadow:0 5px 22px rgba(20,35,70,.08)}
.about-card .card-body{padding:clamp(1.25rem,3vw,2rem)}
.about-card p,.about-card li{font-size:1.05rem;line-height:1.85}
.about-label{font-size:.8rem;text-transform:uppercase;letter-spacing:.09em;color:#6c757d;font-weight:700}
.about-profile-icon{width:120px;height:120px;flex:0 0 120px;object-fit:cover;border:4px solid rgba(255,255,255,.85);box-shadow:0 8px 24px rgba(0,0,0,.2)}
@media(max-width:575px){.about-profile-icon{width:88px;height:88px;flex-basis:88px}}
</style>
<main class="about-page">
  <header class="about-hero mb-4 mb-lg-5">
    <div class="d-flex align-items-center gap-3 gap-md-4 mb-4">
      <img class="about-profile-icon rounded-circle" src="assets/about-me-photo.jpg" alt="Foto profil" loading="lazy">
      <div><div class="text-white-50 fw-semibold mb-2">ABOUT THE DEVELOPER</div><h2 class="fw-bold mb-2">Nucky Andreas Febrian</h2><div class="fs-5">Kelas <?= htmlspecialchars($kelas, ENT_QUOTES, 'UTF-8') ?> <span class="mx-2">•</span> Web Developer</div></div>
    </div>
    <h3 class="h4 fw-bold mb-3">Membangun Ekosistem Sekolah yang Lebih Tertib dan Transparan.</h3>
    <p class="mb-0">Halo! Saya adalah seorang <strong>Web Developer</strong> yang berfokus pada penciptaan solusi digital untuk efisiensi dunia pendidikan. Website Sistem Pelanggaran Siswa ini dirancang untuk menggantikan pencatatan manual yang rentan hilang atau tidak akurat. Melalui platform ini, saya ingin membantu sekolah memantau kedisiplinan siswa secara <em>real-time</em>, mempermudah tugas guru bimbingan konseling (BK), serta menjembatani komunikasi yang transparan antara pihak sekolah dan orang tua murid. Saya percaya bahwa kedisiplinan yang terdata dengan baik adalah awal dari pembentukan karakter generasi masa depan.</p>
  </header>

  <div class="row g-4 mb-4">
    <div class="col-12 col-lg-7">
      <section class="card about-card"><div class="card-body">
        <div class="about-label mb-2">Tentang Projek</div><h3 class="h4 fw-bold mb-3">Mengubah Data Menjadi Solusi Kedisiplinan.</h3>
        <p class="mb-0">Saya mengembangkan <strong>Sistem Informasi Pelanggaran Siswa</strong> ini untuk menghadirkan kepraktisan dalam manajemen sekolah. Dengan sistem berbasis poin dan rekam jejak digital, platform ini tidak hanya mencatat kesalahan, tetapi juga membantu sekolah dalam mengambil tindakan preventif dan pembinaan yang lebih adil bagi setiap siswa.</p>
      </div></section>
    </div>
    <div class="col-12 col-lg-5">
      <section class="card about-card"><div class="card-body">
        <div class="about-label mb-2">Identitas Pengembang</div><h3 class="h4 fw-bold mb-3">Profil Saya</h3>
        <div class="table-responsive"><table class="table table-borderless align-middle mb-0"><tbody>
          <tr><th class="ps-0" style="width:115px">Nama</th><td><?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8') ?></td></tr>
          <tr><th class="ps-0">Kelas</th><td><?= htmlspecialchars($kelas, ENT_QUOTES, 'UTF-8') ?></td></tr>
          <tr><th class="ps-0">Bidang</th><td>Web Development</td></tr>
          <tr><th class="ps-0">Projek</th><td>Sistem Informasi Pelanggaran Siswa</td></tr>
        </tbody></table></div>
      </div></section>
    </div>
  </div>

  <section class="card about-card mb-4"><div class="card-body">
    <div class="about-label mb-2">Komitmen Kami</div><h3 class="h4 fw-bold mb-3">Bahu-membahu Menjaga Nilai dan Integritas Sekolah.</h3>
    <p class="mb-0">Website ini hadir sebagai alat bantu modern untuk mempermudah pemantauan perilaku siswa secara objektif. Dengan sistem yang terintegrasi, setiap tindakan kedisiplinan diharapkan dapat tercatat dengan akurat demi mendukung proses evaluasi dan bimbingan karakter siswa secara berkelanjutan. Semoga aplikasi ini dapat membantu menciptakan lingkungan belajar yang aman, kondusif, dan berintegritas.</p>
  </div></section>

  <footer class="text-center rounded-4 p-4 p-md-5 bg-body-tertiary">
    <i class="bi bi-mortarboard-fill text-primary fs-1"></i><h3 class="h4 fw-bold mt-3">Teknologi untuk Pendidikan yang Lebih Baik</h3>
    <p class="text-secondary mb-0 mx-auto" style="max-width:760px;font-size:1.05rem;line-height:1.8">Terima kasih telah mengunjungi halaman About Me. Semoga Sistem Informasi Pelanggaran Siswa ini dapat memberikan manfaat dan mendukung pembinaan kedisiplinan siswa secara lebih tertib, transparan, dan berkelanjutan.</p>
  </footer>
</main>
<?php if (!$embed): ?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></body></html><?php endif; ?>
