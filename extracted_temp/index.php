<?php
// ============================================================
// index.php — Halaman Beranda
// FIX: Hero layout, counter animasi, CSS path, responsive
// ============================================================
require_once __DIR__.'/includes/config.php';
$pageTitle = 'Beranda';
$db = getDB();

// Ambil 3 berita terbaru
$beritaStmt = $db->query("SELECT id, judul, slug, gambar, kategori, created_at, konten
                          FROM berita WHERE status='publish'
                          ORDER BY created_at DESC LIMIT 3");
$beritaTerbaru = $beritaStmt->fetchAll();

// Ambil dosen (preview 4)
$dosenStmt = $db->query("SELECT * FROM dosen ORDER BY id ASC LIMIT 4");
$dosenList = $dosenStmt->fetchAll();

include __DIR__.'/includes/header.php';
?>

<!-- ============================================================
     HERO SECTION — Full Screen dengan background image
============================================================ -->
<section class="hero" id="hero">
    <div class="hero-bg-overlay"></div>
    <div class="hero-pattern"></div>
    <div class="container hero-container">
        <div class="hero-content">
            <!-- Badge -->
            <div class="hero-badge animate-fade-down">
                <span class="badge-dot"></span>
                Politeknik Negeri Sriwijaya — Palembang
            </div>

            <!-- Heading -->
            <h1 class="hero-heading animate-fade-up">
                Jurusan<br>
                <span class="hero-highlight">Manajemen</span><br>
                Informatika
            </h1>

            <!-- Desc -->
            <p class="hero-desc animate-fade-up delay-1">
                Pelajari seluk beluk teknologi informasi dan manajemen bisnis
                untuk menjadi profesional handal yang siap menghadapi
                tantangan industri 4.0.
            </p>

            <!-- CTA Buttons -->
            <div class="hero-cta animate-fade-up delay-2">
                <a href="<?= APP_URL ?>/about.php" class="btn-hero-primary">
                    <i class="fas fa-building-columns"></i>
                    Profil Jurusan
                </a>
                <a href="<?= APP_URL ?>/kontak.php" class="btn-hero-outline">
                    <i class="fas fa-envelope"></i>
                    Hubungi Kami
                </a>
            </div>

            <!-- Mini Stats Row -->
            <div class="hero-stats-row animate-fade-up delay-3">
                <div class="hero-stat">
                    <span class="hero-stat-num">412+</span>
                    <span class="hero-stat-lbl">Mahasiswa D3</span>
                </div>
                <span class="hero-stat-divider"></span>
                <div class="hero-stat">
                    <span class="hero-stat-num">390+</span>
                    <span class="hero-stat-lbl">Mahasiswa D4</span>
                </div>
                <span class="hero-stat-divider"></span>
                <div class="hero-stat">
                    <span class="hero-stat-num">84+</span>
                    <span class="hero-stat-lbl">Dosen Aktif</span>
                </div>
            </div>
        </div>

        <!-- Hero Visual Right Side -->
        <div class="hero-visual animate-fade-left delay-1">
            <!-- Floating Card Top -->
            <div class="float-card float-top">
                <div class="float-icon primary-icon animate-pulse"><i class="fas fa-trophy"></i></div>
                <div class="float-text">
                    <strong>Akreditasi B</strong>
                    <span>BAN-PT Terakreditasi</span>
                </div>
            </div>

            <!-- Main Visual Card -->
            <div class="hero-main-card">
                <div class="hero-card-icon-wrap">
                    <i class="fas fa-microchip"></i>
                </div>
                <h4>Program Unggulan</h4>
                <p>D3 & D4 Manajemen Informatika dengan kurikulum industri terkini</p>
                <div class="hero-tags">
                    <span class="tag tag-primary">Web Development</span>
                    <span class="tag tag-primary">Mobile App</span>
                    <span class="tag tag-accent">Data Science</span>
                    <span class="tag tag-primary">Networking</span>
                </div>
            </div>

            <!-- Floating Card Bottom -->
            <div class="float-card float-bottom">
                <div class="float-icon accent-icon"><i class="fas fa-star"></i></div>
                <div class="float-text">
                    <strong>Lulusan Siap Kerja</strong>
                    <span>Serapan industri >90%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <span>Scroll</span>
        <div class="scroll-line"></div>
    </div>
</section>

<!-- ============================================================
     STATS COUNTER SECTION
============================================================ -->
<section class="stats-band" id="statsSection">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon-wrap">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">
                        <span class="counter" data-target="850">0</span>
                        <span class="stat-plus">+</span>
                    </div>
                    <div class="stat-name">Jumlah Mahasiswa</div>
                </div>
            </div>

            <div class="stat-divider-v"></div>

            <div class="stat-item">
                <div class="stat-icon-wrap">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">
                        <span class="counter" data-target="45">0</span>
                        <span class="stat-plus">+</span>
                    </div>
                    <div class="stat-name">Dosen Aktif</div>
                </div>
            </div>

            <div class="stat-divider-v"></div>

            <div class="stat-item">
                <div class="stat-icon-wrap">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">
                        <span class="counter" data-target="8">0</span>
                        <span class="stat-plus">+</span>
                    </div>
                    <div class="stat-name">Laboratorium Komputer</div>
                </div>
            </div>

            <div class="stat-divider-v"></div>

            <div class="stat-item">
                <div class="stat-icon-wrap">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">
                        <span class="counter" data-target="1500">0</span>
                        <span class="stat-plus">+</span>
                    </div>
                    <div class="stat-name">Alumni Sukses</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     PROFIL SINGKAT SECTION (Mirip referensi polsri.ac.id)
============================================================ -->
<section class="profil-section section-py">
    <div class="container">
        <div class="profil-grid">
            <!-- Left: Info Card -->
            <div class="profil-card-wrap fade-up">
                <div class="profil-card-main">
                    <div class="profil-card-icon">
                        <i class="fas fa-building-columns"></i>
                    </div>
                    <h3>Tentang Jurusan MI</h3>
                    <p>
                        Berdiri sejak 1982, Jurusan Manajemen Informatika Polsri
                        telah melahirkan ribuan profesional IT yang berkarir
                        di perusahaan nasional dan internasional.
                    </p>
                    <div class="profil-card-stats">
                        <div>
                            <strong>40+</strong>
                            <span>Tahun Berpengalaman</span>
                        </div>
                        <div class="divider-v-white"></div>
                        <div>
                            <strong>2</strong>
                            <span>Program Studi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Text + Keunggulan -->
            <div class="fade-up">
                <div class="section-badge-wrap">
                    <span class="section-badge">
                        <i class="fas fa-info-circle"></i> Profil Jurusan
                    </span>
                </div>
                <h2 class="section-title">
                    Mencetak Profesional IT<br>
                    <span class="text-primary">Berkelas Dunia</span>
                </h2>
                <p class="section-desc">
                    Jurusan Manajemen Informatika Polsri menghadirkan pendidikan
                    vokasi berkualitas tinggi yang menggabungkan penguasaan
                    teknologi informasi dengan kemampuan manajerial bisnis.
                </p>

                <div class="keunggulan-grid">
                    <?php
                    $keunggulan = [
                        ['fas fa-laptop-code', 'Kurikulum Industri',  'Dirancang bersama mitra industri terkemuka'],
                        ['fas fa-flask',        'Lab Modern',          'Fasilitas laboratorium komputer terkini'],
                        ['fas fa-handshake',    'PKL Terstruktur',     'Praktik di perusahaan IT ternama'],
                        ['fas fa-certificate',  'Sertifikasi',         'Program sertifikasi nasional & internasional'],
                    ];
                    foreach ($keunggulan as $k): ?>
                    <div class="keunggulan-item">
                        <div class="keunggulan-icon">
                            <i class="<?= $k[0] ?>"></i>
                        </div>
                        <div>
                            <strong><?= $k[1] ?></strong>
                            <span><?= $k[2] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <a href="<?= APP_URL ?>/about.php" class="btn-primary-custom" style="margin-top:28px;">
                    Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     KETUA JURUSAN SECTION (Sesuai referensi asli)
============================================================ -->
<section class="ketua-section section-py-sm">
    <div class="container">
        <div class="ketua-grid">
            <!-- Foto/Avatar -->
            <div class="ketua-avatar-wrap fade-up">
                <div class="ketua-avatar" style="overflow:hidden;display:flex;align-items:center;justify-content:center;background:#fff;border:3px solid rgba(255,255,255,0.3);">
                    <?php if (file_exists(__DIR__ . '/assets/images/sony-oktapriandi.png')): ?>
                    <img src="<?= APP_URL ?>/assets/images/sony-oktapriandi.png" alt="Sony Oktapriandi" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    <?php else: ?>
                    <i class="fas fa-user-tie" style="color:var(--clr-primary);font-size:4rem;"></i>
                    <?php endif; ?>
                </div>
                <div class="ketua-badge-ring"></div>
            </div>
            <!-- Quote -->
            <div class="ketua-quote-wrap fade-up">
                <span class="section-badge" style="margin-bottom:20px;">
                    <i class="fas fa-quote-left"></i> Ketua Jurusan
                </span>
                <h2 class="section-title" style="font-size:1.6rem;">
                    Sambutan Ketua Program Studi<br>
                    <span class="text-primary">Manajemen Informatika</span>
                </h2>
                <blockquote class="ketua-blockquote">
                    "Kami percaya teknologi bukan sekadar alat, tapi jalan untuk
                    menciptakan perubahan yang berarti — dan itulah yang kami
                    tanamkan di Manajemen Informatika Polsri."
                </blockquote>
                <div class="ketua-name-card">
                    <div>
                        <strong>Sony Oktapriandi, S.Kom., M.Kom.</strong>
                        <span>Ketua Program Studi Manajemen Informatika</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     DOSEN PREVIEW SECTION
============================================================ -->
<section class="dosen-section section-py" style="background:var(--clr-bg-soft);">
    <div class="container">
        <div class="section-header text-center fade-up">
            <span class="section-badge">
                <i class="fas fa-chalkboard-teacher"></i> Tenaga Pengajar
            </span>
            <h2 class="section-title">Dosen Berpengalaman & Kompeten</h2>
            <p class="section-desc" style="margin:0 auto;">
                Didukung oleh para dosen dengan latar belakang akademik dan industri yang kuat
            </p>
        </div>

        <div class="dosen-grid">
            <?php foreach ($dosenList as $d): ?>
            <div class="dosen-card fade-up">
                <div class="dosen-img-wrap">
                    <?php if (!empty($d['foto']) && $d['foto'] !== 'default-dosen.jpg' && file_exists(__DIR__ . '/assets/images/' . $d['foto'])): ?>
                    <img src="<?= APP_URL ?>/assets/images/<?= e($d['foto']) ?>"
                         alt="Foto <?= e($d['nama']) ?>">
                    <?php else: ?>
                    <div class="dosen-placeholder">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <?php endif; ?>
                    <div class="dosen-overlay">
                        <a href="mailto:<?= e($d['email']) ?>">
                            <i class="fas fa-envelope me-1"></i>Email
                        </a>
                    </div>
                </div>
                <div class="dosen-body">
                    <span class="dosen-jabatan"><?= e($d['jabatan']) ?></span>
                    <div class="dosen-name"><?= e($d['nama']) ?></div>
                    <div class="dosen-keahlian">
                        <i class="fas fa-tag me-1 text-primary"></i><?= e($d['bidang_keahlian']) ?>
                    </div>
                    <div class="dosen-email">
                        <a href="mailto:<?= e($d['email']) ?>">
                            <i class="fas fa-envelope"></i><?= e($d['email']) ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="<?= APP_URL ?>/dosen.php" class="btn-outline-custom">
                Lihat Semua Dosen <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     BERITA & PENGUMUMAN (GRID SYSTEM & SIDEBAR)
============================================================ -->
<section class="berita-section section-py">
    <div class="container">
        <div class="row g-5">
            <!-- Left Side: Latest News -->
            <div class="col-lg-8">
                <div class="berita-header d-flex justify-content-between align-items-end mb-4 fade-up" style="margin-bottom: 24px !important;">
                    <div>
                        <span class="section-badge">
                            <i class="fas fa-newspaper"></i> Informasi Terkini
                        </span>
                        <h2 class="section-title" style="margin-bottom:0;">Berita & Artikel</h2>
                    </div>
                    <a href="<?= APP_URL ?>/berita.php" class="btn-outline-custom" style="padding: 8px 16px; font-size: 0.85rem; border-radius: var(--radius-sm);">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                <div class="row g-4 fade-up">
                    <?php foreach ($beritaTerbaru as $b): ?>
                    <div class="col-md-6">
                        <div class="berita-card">
                            <div class="berita-img">
                                <img
                                    src="<?= APP_URL ?>/assets/images/<?= e($b['gambar']) ?>"
                                    alt="<?= e($b['judul']) ?>"
                                    loading="lazy"
                                    onerror="this.src='https://placehold.co/600x400/DCEBFA/003366?text=MI+Polsri'">
                                <span class="berita-kategori"><?= ucfirst(e($b['kategori'])) ?></span>
                            </div>
                            <div class="berita-body">
                                <div class="berita-meta">
                                    <i class="fas fa-calendar-alt text-accent me-1"></i>
                                    <?= formatTanggal($b['created_at']) ?>
                                </div>
                                <h5 class="berita-title" style="font-weight:700; font-size:1.05rem; margin-bottom:10px; color:var(--clr-text);"><?= e($b['judul']) ?></h5>
                                <p class="berita-excerpt" style="font-size:0.85rem; color:var(--clr-text-muted); margin-bottom:16px;"><?= truncate($b['konten'], 95) ?></p>
                                <a href="<?= APP_URL ?>/berita_detail/<?= e($b['slug']) ?>.php" class="berita-link" style="font-size:0.85rem; font-weight:600;">
                                    Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right Side: Academic Announcements -->
            <div class="col-lg-4">
                <div class="announcement-card fade-up">
                    <div class="d-flex align-items-center gap-2 mb-4" style="margin-bottom: 20px !important;">
                        <div style="width:36px;height:36px;background:var(--clr-primary);color:var(--clr-accent);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h4 style="margin:0;font-size:1.15rem;font-weight:800;font-family:var(--font-display);">Pengumuman Akademik</h4>
                    </div>

                    <div class="announcement-list">
                        <div class="announcement-item">
                            <div class="announcement-date-badge">
                                <span class="day">25</span>
                                <span>Mei</span>
                            </div>
                            <div>
                                <h6 class="announcement-title">
                                    <a href="#" onclick="alert('Detail pendaftaran PKL dapat diakses melalui SIAKAD Polsri.')">Pendaftaran PKL Tahun Akademik 2026/2027</a>
                                </h6>
                                <div class="announcement-meta">
                                    <i class="fas fa-tag me-1"></i> Akademik
                                </div>
                            </div>
                        </div>

                        <div class="announcement-item">
                            <div class="announcement-date-badge">
                                <span class="day">10</span>
                                <span>Jun</span>
                            </div>
                            <div>
                                <h6 class="announcement-title">
                                    <a href="#" onclick="alert('Jadwal UAS lengkap dapat diunduh di papan pengumuman jurusan.')">Ujian Akhir Semester (UAS) Genap</a>
                                </h6>
                                <div class="announcement-meta">
                                    <i class="fas fa-tag me-1"></i> Ujian
                                </div>
                            </div>
                        </div>

                        <div class="announcement-item">
                            <div class="announcement-date-badge">
                                <span class="day">18</span>
                                <span>Jul</span>
                            </div>
                            <div>
                                <h6 class="announcement-title">
                                    <a href="#" onclick="alert('Seluruh calon wisudawan diharapkan menyelesaikan berkas yudisium.')">Pelaksanaan Yudisium Tahap I</a>
                                </h6>
                                <div class="announcement-meta">
                                    <i class="fas fa-tag me-1"></i> Kelulusan
                                </div>
                            </div>
                        </div>

                        <div class="announcement-item">
                            <div class="announcement-date-badge">
                                <span class="day">12</span>
                                <span>Agu</span>
                            </div>
                            <div>
                                <h6 class="announcement-title">
                                    <a href="#" onclick="alert('Pendaftaran Ujian Jalur Mandiri dapat dilakukan online.')">Penerimaan Mahasiswa Baru Mandiri</a>
                                </h6>
                                <div class="announcement-meta">
                                    <i class="fas fa-tag me-1"></i> PMB
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="#" onclick="alert('Semua pengumuman resmi dapat diakses secara berkala di portal utama Polsri.')" class="btn-outline-custom w-100 justify-content-center mt-4" style="padding:10px 16px;font-size:0.85rem;margin-top:20px !important;">
                        Lihat Semua Pengumuman
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     VISI MISI SECTION
============================================================ -->
<section class="vismis-section section-py-sm" style="background:var(--clr-bg-soft);">
    <div class="container">
        <div class="section-header text-center fade-up">
            <span class="section-badge">
                <i class="fas fa-bullseye"></i> Visi & Misi
            </span>
            <h2 class="section-title">Arah & Tujuan Kami</h2>
        </div>
        <div class="vismis-grid">
            <!-- Visi -->
            <div class="vismis-card vismis-visi fade-up">
                <div class="vismis-icon"><i class="fas fa-eye"></i></div>
                <h4>Visi</h4>
                <p>
                    Menjadi Jurusan Manajemen Informatika yang unggul, inovatif,
                    dan berdaya saing global dalam menghasilkan lulusan profesional
                    di bidang teknologi informasi pada tahun 2027.
                </p>
            </div>
            <!-- Misi -->
            <div class="vismis-card vismis-misi fade-up">
                <div class="vismis-icon misi-icon"><i class="fas fa-list-check"></i></div>
                <h4>Misi Utama</h4>
                <ul class="misi-list">
                    <?php
                    $misiSingkat = [
                        'Menyelenggarakan pendidikan vokasi berkualitas tinggi',
                        'Mengembangkan penelitian terapan relevan industri',
                        'Membangun kemitraan strategis nasional & internasional',
                        'Menghasilkan lulusan berkarakter dan berintegritas',
                    ];
                    foreach ($misiSingkat as $i => $m): ?>
                    <li>
                        <span class="misi-num"><?= $i + 1 ?></span>
                        <?= $m ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="text-center mt-4 fade-up">
            <a href="<?= APP_URL ?>/about.php" class="btn-outline-custom">
                Baca Visi Misi Lengkap <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</section>

<!-- ============================================================
     CTA SECTION (Newsletter/Subscribe style referensi)
============================================================ -->
<section class="cta-section">
    <div class="cta-bg-pattern"></div>
    <div class="container">
        <div class="cta-inner">
            <div class="cta-badge">
                <i class="fas fa-sparkles"></i> Bergabunglah Bersama Kami
            </div>
            <h2>Tetap Update dengan Berita MI Polsri!</h2>
            <p>
                Dapatkan informasi terbaru seputar kegiatan, prestasi, dan
                pengumuman penting dari Jurusan Manajemen Informatika.
            </p>
            <div class="cta-actions">
                <a href="<?= APP_URL ?>/kontak.php" class="btn-cta-white">
                    <i class="fas fa-envelope"></i> Hubungi Kami
                </a>
                <a href="<?= APP_URL ?>/berita.php" class="btn-cta-outline">
                    <i class="fas fa-newspaper"></i> Baca Berita
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================
     PAGE-SPECIFIC CSS — diletakkan sebelum footer
============================================================ -->
<style>
/* ── HERO ── */
.hero {
    min-height: 100vh;
    background: linear-gradient(135deg, #0a1628 0%, #0d2137 40%, #002244 100%);
    position: relative;
    display: flex;
    align-items: center;
    overflow: hidden;
    padding-top: var(--navbar-h);
}
.hero-bg-overlay {
    position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 80% 30%, rgba(0, 51, 102,0.25) 0%, transparent 60%),
        radial-gradient(ellipse 50% 50% at 10% 80%, rgba(255, 204, 0,0.08) 0%, transparent 50%);
}
.hero-pattern {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
    background-size: 60px 60px;
}
.hero-container {
    display: flex;
    align-items: center;
    gap: 60px;
    position: relative;
    z-index: 2;
    padding-top: 40px;
    padding-bottom: 60px;
}
.hero-content { flex: 1; min-width: 0; }
.hero-visual   { flex: 0 0 420px; position: relative; }

/* Badge */
.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(0, 51, 102,0.4);
    color: rgba(255,255,255,0.85);
    font-size: 0.78rem; font-weight: 500;
    padding: 6px 16px; border-radius: 50px;
    margin-bottom: 24px;
    backdrop-filter: blur(8px);
}
.badge-dot {
    width: 7px; height: 7px;
    background: var(--clr-primary); border-radius: 50%;
    animation: pulse 2s infinite;
    flex-shrink: 0;
}

/* Heading */
.hero-heading {
    font-family: var(--font-display);
    font-size: clamp(2.8rem, 5.5vw, 4.2rem);
    font-weight: 800; line-height: 1.08;
    color: white; margin-bottom: 20px;
}
.hero-highlight {
    background: linear-gradient(135deg, var(--clr-primary), var(--clr-accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-desc {
    color: rgba(255,255,255,0.7);
    font-size: 1.05rem; line-height: 1.7;
    max-width: 500px; margin-bottom: 36px;
}

/* CTA Buttons */
.hero-cta { display: flex; gap: 14px; flex-wrap: wrap; }
.btn-hero-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--clr-primary); color: white;
    padding: 14px 28px; border-radius: 12px;
    font-weight: 600; font-size: 0.95rem;
    transition: all 0.3s; text-decoration: none;
}
.btn-hero-primary:hover {
    background: var(--clr-primary-dark); color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 51, 102,0.4);
}
.btn-hero-outline {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.9);
    padding: 14px 28px; border-radius: 12px;
    font-weight: 600; font-size: 0.95rem;
    border: 1.5px solid rgba(255,255,255,0.25);
    transition: all 0.3s; text-decoration: none;
    backdrop-filter: blur(4px);
}
.btn-hero-outline:hover {
    background: rgba(255,255,255,0.15);
    color: white; border-color: rgba(255,255,255,0.5);
}

/* Hero Stats Row */
.hero-stats-row {
    display: flex; align-items: center; gap: 32px;
    flex-wrap: wrap; margin-top: 44px;
}
.hero-stat-divider {
    width: 1px; height: 36px;
    background: rgba(255,255,255,0.2);
}
.hero-stat { text-align: left; }
.hero-stat-num {
    display: block;
    font-family: var(--font-display);
    font-size: 1.8rem; font-weight: 800;
    color: var(--clr-primary); line-height: 1;
}
.hero-stat-lbl {
    display: block;
    font-size: 0.75rem; color: rgba(255,255,255,0.6);
    margin-top: 3px;
}

/* ── Hero Visual ── */
.hero-main-card {
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.12);
    border-radius: 24px;
    padding: 32px;
}
.hero-card-icon-wrap {
    width: 72px; height: 72px;
    background: linear-gradient(135deg, rgba(0, 51, 102,0.3), rgba(0, 51, 102,0.1));
    border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; color: var(--clr-primary);
    margin-bottom: 20px;
    border: 1px solid rgba(0, 51, 102,0.3);
}
.hero-main-card h4 { color: white; font-size: 1.1rem; margin-bottom: 6px; }
.hero-main-card p  { color: rgba(255,255,255,0.6); font-size: 0.85rem; margin-bottom: 18px; }
.hero-tags { display: flex; gap: 8px; flex-wrap: wrap; }
.tag {
    font-size: 0.73rem; font-weight: 600;
    padding: 5px 12px; border-radius: 50px;
}
.tag-primary {
    background: rgba(0, 51, 102,0.2);
    color: #FFD700;
    border: 1px solid rgba(0, 51, 102,0.3);
}
.tag-accent {
    background: rgba(255, 204, 0,0.15);
    color: #FCD34D;
    border: 1px solid rgba(255, 204, 0,0.3);
}

/* Float Cards */
.float-card {
    position: absolute;
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 16px;
    padding: 14px 18px;
    display: flex; align-items: center; gap: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}
.float-top    { top: -24px; left: -24px; animation: floatY 5s ease-in-out 0.5s infinite; }
.float-bottom { bottom: -24px; right: -16px; animation: floatY 5s ease-in-out 1.5s infinite; }
.float-icon {
    width: 38px; height: 38px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
.primary-icon animate-pulse  { background: rgba(0, 51, 102,0.25); color: #FFD700; }
.accent-icon { background: rgba(255, 204, 0,0.25); color: #FCD34D; }
.float-text strong { display: block; font-size: 0.82rem; font-weight: 700; color: white; }
.float-text span   { display: block; font-size: 0.73rem; color: rgba(255,255,255,0.6); }

/* Scroll Indicator */
.scroll-indicator {
    position: absolute; bottom: 30px; left: 50%;
    transform: translateX(-50%);
    display: flex; flex-direction: column; align-items: center;
    gap: 6px; color: rgba(255,255,255,0.4); font-size: 0.7rem;
    letter-spacing: 0.1em; text-transform: uppercase;
    animation: fadeInUp 1s 1.5s ease both;
}
.scroll-line {
    width: 1px; height: 40px;
    background: linear-gradient(to bottom, rgba(255,255,255,0.4), transparent);
    animation: scrollPulse 2s ease-in-out infinite;
}

/* ── STATS BAND ── */
.stats-band {
    background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-dark));
    padding: 50px 0;
    position: relative; overflow: hidden;
}
.stats-band::before {
    content: '';
    position: absolute; top: -60%; right: -5%;
    width: 400px; height: 400px; border-radius: 50%;
    background: rgba(255,255,255,0.04);
}
.stats-grid {
    display: flex; align-items: center;
    justify-content: center; flex-wrap: wrap; gap: 0;
}
.stat-item {
    display: flex; align-items: center; gap: 16px;
    padding: 20px 40px;
    flex: 1; min-width: 200px;
    justify-content: center;
}
.stat-icon-wrap {
    width: 52px; height: 52px;
    background: rgba(255,255,255,0.15);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: white;
    flex-shrink: 0;
}
.stat-value {
    display: flex; align-items: flex-end; gap: 2px;
    font-family: var(--font-display);
    font-size: 2.6rem; font-weight: 800;
    color: white; line-height: 1;
}
.stat-plus { font-size: 1.6rem; color: #FCD34D; margin-bottom: 2px; }
.stat-name { font-size: 0.82rem; color: rgba(255,255,255,0.8); margin-top: 4px; }
.stat-divider-v {
    width: 1px; height: 60px;
    background: rgba(255,255,255,0.2);
    flex-shrink: 0;
}

/* ── PROFIL SECTION ── */
.profil-section { background: var(--clr-bg); }
.profil-grid {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 60px;
    align-items: center;
}
.profil-card-main {
    background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-dark));
    border-radius: 28px; padding: 40px; color: white;
    position: relative; overflow: hidden;
}
.profil-card-main::before {
    content: '';
    position: absolute; top: -40px; right: -40px;
    width: 150px; height: 150px; border-radius: 50%;
    background: rgba(255,255,255,0.06);
}
.profil-card-icon {
    font-size: 2.8rem; color: rgba(255,255,255,0.85);
    margin-bottom: 20px;
}
.profil-card-main h3 { color: white; font-size: 1.5rem; margin-bottom: 12px; }
.profil-card-main p  { color: rgba(255,255,255,0.82); font-size: 0.9rem; line-height: 1.7; margin-bottom: 28px; }
.profil-card-stats { display: flex; gap: 28px; }
.profil-card-stats strong { display: block; font-size: 2rem; font-weight: 800; }
.profil-card-stats span   { display: block; font-size: 0.78rem; opacity: 0.8; margin-top: 2px; }
.divider-v-white { width: 1px; background: rgba(255,255,255,0.2); align-self: stretch; }

/* Keunggulan Grid */
.keunggulan-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 14px; margin-top: 28px;
}
.keunggulan-item {
    display: flex; gap: 12px; align-items: flex-start;
    padding: 16px; border-radius: 14px;
    border: 1px solid var(--clr-border);
    background: var(--clr-bg-soft);
    transition: all 0.3s;
}
.keunggulan-item:hover {
    border-color: var(--clr-primary);
    background: var(--clr-primary-xlight);
    transform: translateY(-2px);
}
.keunggulan-icon {
    width: 40px; height: 40px;
    background: var(--clr-primary-xlight);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: var(--clr-primary); font-size: 1rem; flex-shrink: 0;
}
.keunggulan-item strong { display: block; font-size: 0.875rem; font-weight: 700; margin-bottom: 2px; }
.keunggulan-item span   { display: block; font-size: 0.78rem; color: var(--clr-text-muted); }

/* ── KETUA SECTION ── */
.ketua-section { background: var(--clr-bg-soft); }
.ketua-grid {
    display: grid; grid-template-columns: 260px 1fr;
    gap: 60px; align-items: center;
    max-width: 900px; margin: 0 auto;
}
.ketua-avatar-wrap { position: relative; text-align: center; }
.ketua-avatar {
    width: 160px; height: 160px; border-radius: 50%;
    background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-dark));
    display: flex; align-items: center; justify-content: center;
    font-size: 4rem; color: white; margin: 0 auto;
    box-shadow: 0 16px 48px rgba(0, 51, 102,0.35);
}
.ketua-badge-ring {
    position: absolute; inset: -12px;
    border-radius: 50%;
    border: 2px dashed rgba(0, 51, 102,0.3);
    top: -12px; left: 50%; transform: translateX(-50%);
    width: 184px; height: 184px;
    animation: spin 20s linear infinite;
}
.ketua-blockquote {
    font-size: 1.05rem; line-height: 1.8;
    color: var(--clr-text-muted);
    border-left: 3px solid var(--clr-primary);
    padding-left: 20px; margin: 20px 0 24px;
    font-style: italic;
}
.ketua-name-card {
    display: flex; align-items: center; gap: 14px;
    padding: 16px 20px;
    background: var(--clr-bg-card);
    border: 1px solid var(--clr-border);
    border-radius: 14px;
}
.ketua-name-card strong { display: block; font-size: 0.9rem; font-weight: 700; }
.ketua-name-card span   { display: block; font-size: 0.78rem; color: var(--clr-text-muted); margin-top: 2px; }

/* ── BERITA ── */
.berita-section { background: var(--clr-bg); }
.berita-header {
    display: flex; justify-content: space-between;
    align-items: flex-start; gap: 20px;
    margin-bottom: 44px; flex-wrap: wrap;
}
.berita-grid {
    display: grid; grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

/* ── VISI MISI ── */
.vismis-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 24px; margin-bottom: 32px;
}
.vismis-card {
    border-radius: 24px; padding: 36px;
}
.vismis-visi {
    background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-dark));
    color: white;
}
.vismis-visi h4, .vismis-visi p { color: white !important; }
.vismis-misi {
    background: var(--clr-bg-card);
    border: 1px solid var(--clr-border);
}
.vismis-icon {
    width: 48px; height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem; color: white; margin-bottom: 16px;
}
.misi-icon {
    background: var(--clr-primary-xlight);
    color: var(--clr-primary);
}
.vismis-visi h4 { font-size: 1.2rem; margin-bottom: 14px; }
.vismis-misi h4 { font-size: 1.2rem; margin-bottom: 14px; }
.vismis-visi p  { font-size: 0.92rem; line-height: 1.7; opacity: 0.92; }
.misi-list { list-style: none; padding: 0; margin: 0; }
.misi-list li {
    display: flex; gap: 12px; align-items: flex-start;
    padding: 9px 0; border-bottom: 1px solid var(--clr-border);
    font-size: 0.875rem; color: var(--clr-text-muted);
}
.misi-list li:last-child { border-bottom: none; }
.misi-num {
    width: 24px; height: 24px; flex-shrink: 0;
    background: var(--clr-primary-xlight); color: var(--clr-primary);
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.72rem; font-weight: 700;
}

/* ── CTA SECTION ── */
.cta-section {
    background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-dark));
    padding: 80px 0; text-align: center;
    position: relative; overflow: hidden;
}
.cta-bg-pattern {
    position: absolute; inset: 0;
    background-image:
        linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
    background-size: 40px 40px;
}
.cta-inner { position: relative; z-index: 2; max-width: 600px; margin: 0 auto; }
.cta-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.15);
    color: white; font-size: 0.78rem; font-weight: 600;
    padding: 6px 16px; border-radius: 50px;
    margin-bottom: 20px; backdrop-filter: blur(4px);
}
.cta-section h2 { color: white; font-size: 2rem; margin-bottom: 14px; }
.cta-section p  { color: rgba(255,255,255,0.82); margin-bottom: 32px; font-size: 1rem; }
.cta-actions    { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
.btn-cta-white {
    display: inline-flex; align-items: center; gap: 8px;
    background: white; color: var(--clr-primary);
    padding: 14px 28px; border-radius: 12px;
    font-weight: 700; font-size: 0.95rem;
    text-decoration: none; transition: all 0.3s;
}
.btn-cta-white:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.2); }
.btn-cta-outline {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.12);
    color: white;
    padding: 14px 28px; border-radius: 12px;
    font-weight: 600; font-size: 0.95rem;
    border: 1.5px solid rgba(255,255,255,0.35);
    text-decoration: none; transition: all 0.3s;
    backdrop-filter: blur(4px);
}
.btn-cta-outline:hover {
    background: rgba(255,255,255,0.22); color: white;
}

/* ── SECTION UTILITIES ── */
.section-badge-wrap { margin-bottom: 14px; }
.section-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--clr-primary-xlight);
    color: var(--clr-primary);
    font-size: 0.75rem; font-weight: 600;
    letter-spacing: 0.06em; text-transform: uppercase;
    padding: 6px 16px; border-radius: 50px;
    border: 1px solid var(--clr-primary-light);
}
.section-title {
    font-size: clamp(1.8rem, 3.5vw, 2.4rem);
    font-weight: 800; line-height: 1.2;
    color: var(--clr-text); margin: 12px 0 12px;
}
.section-desc {
    color: var(--clr-text-muted); font-size: 1rem;
    line-height: 1.7; max-width: 560px;
}
.dosen-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px; margin-top: 40px;
}

/* ── ANIMATIONS ── */
@keyframes floatY {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-10px); }
}
@keyframes scrollPulse {
    0%, 100% { opacity: 0.4; transform: scaleY(1); }
    50%       { opacity: 1; transform: scaleY(0.7); }
}
@keyframes spin {
    from { transform: translateX(-50%) rotate(0deg); }
    to   { transform: translateX(-50%) rotate(360deg); }
}
.animate-fade-down { animation: fadeInDown 0.7s ease both; }
.animate-fade-up   { animation: fadeInUp 0.7s ease both; }
.animate-fade-left { animation: fadeInRight 0.8s ease both; }
.delay-1 { animation-delay: 0.2s; }
.delay-2 { animation-delay: 0.4s; }
.delay-3 { animation-delay: 0.6s; }
@keyframes fadeInDown  { from { opacity:0; transform:translateY(-20px); } to { opacity:1; transform:none; } }
@keyframes fadeInUp    { from { opacity:0; transform:translateY(25px); }  to { opacity:1; transform:none; } }
@keyframes fadeInRight { from { opacity:0; transform:translateX(40px); }  to { opacity:1; transform:none; } }

/* ── DARK MODE ADJUSTMENTS ── */
[data-theme="dark"] .hero-main-card {
    background: rgba(255,255,255,0.05);
    border-color: rgba(255,255,255,0.1);
}
[data-theme="dark"] .float-card {
    background: rgba(255,255,255,0.06);
    border-color: rgba(255,255,255,0.1);
}

/* ── RESPONSIVE ── */
@media (max-width: 1100px) {
    .profil-grid { grid-template-columns: 1fr; gap: 40px; }
    .profil-card-main { max-width: 100%; }
}
@media (max-width: 991px) {
    .hero-container { flex-direction: column; text-align: center; gap: 40px; }
    .hero-visual    { display: none; }
    .hero-desc      { max-width: 100%; }
    .hero-cta       { justify-content: center; }
    .hero-stats-row { justify-content: center; }
    .stats-grid     { gap: 0; }
    .stat-divider-v { display: none; }
    .stat-item      { border-bottom: 1px solid rgba(255,255,255,0.12); }
    .berita-grid    { grid-template-columns: 1fr 1fr; }
    .dosen-grid     { grid-template-columns: 1fr 1fr; }
    .vismis-grid    { grid-template-columns: 1fr; }
    .ketua-grid     { grid-template-columns: 1fr; text-align: center; gap: 30px; }
    .ketua-blockquote { text-align: left; }
    .keunggulan-grid  { grid-template-columns: 1fr; }
}
@media (max-width: 576px) {
    .hero-heading { font-size: 2.4rem; }
    .hero-stats-row { gap: 16px; }
    .berita-grid  { grid-template-columns: 1fr; }
    .dosen-grid   { grid-template-columns: 1fr 1fr; }
    .stat-item    { padding: 16px 20px; }
    .stat-value   { font-size: 2rem; }
    .section-title { font-size: 1.6rem; }
}
</style>

<?php include __DIR__.'/includes/footer.php'; ?>
