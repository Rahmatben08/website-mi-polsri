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

// Ambil 4 pengumuman terbaru
$pengStmt = $db->query("SELECT * FROM pengumuman ORDER BY tanggal DESC, id DESC LIMIT 4");
$pengumumanList = $pengStmt->fetchAll();

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
                    <img src="<?= APP_URL ?>/assets/images/mi.png" alt="Logo MI" style="width:38px;height:38px;object-fit:contain;" onerror="this.outerHTML='<i class=\'fas fa-graduation-cap\' style=\'color:var(--clr-accent);\'></i>'">
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
                        <?php if (empty($pengumumanList)): ?>
                        <p style="font-size:0.85rem;color:var(--clr-text-muted);text-align:center;padding:20px 0;">Belum ada pengumuman terbaru.</p>
                        <?php else: ?>
                        <?php foreach ($pengumumanList as $p): 
                            $tgl = strtotime($p['tanggal']);
                            $day = date('d', $tgl);
                            $monthNames = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                            $month = $monthNames[(int)date('n', $tgl)];
                        ?>
                        <div class="announcement-item">
                            <div class="announcement-date-badge">
                                <span class="day"><?= $day ?></span>
                                <span><?= $month ?></span>
                            </div>
                            <div>
                                <h6 class="announcement-title">
                                    <?php if (!empty($p['link_info'])): ?>
                                    <a href="<?= e($p['link_info']) ?>" target="_blank"><?= e($p['judul']) ?></a>
                                    <?php else: ?>
                                    <a href="#" onclick="alert('Detail pengumuman: <?= e($p['judul']) ?>')"><?= e($p['judul']) ?></a>
                                    <?php endif; ?>
                                </h6>
                                <div class="announcement-meta" style="font-size: 0.73rem; color: var(--clr-text-light); margin-top: 4px;">
                                    <i class="fas fa-tag me-1"></i> <?= e($p['kategori']) ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <a href="<?= APP_URL ?>/berita.php" class="btn-outline-custom w-100 justify-content-center mt-4" style="padding:10px 16px;font-size:0.85rem;margin-top:20px !important;">
                        Lihat Semua Informasi
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


<?php include __DIR__.'/includes/footer.php'; ?>
