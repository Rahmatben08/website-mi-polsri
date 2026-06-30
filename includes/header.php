<?php
$pageTitle = $pageTitle ?? 'MI Polsri';
$csrfToken = generateCsrfToken();
?>
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Website resmi Jurusan Manajemen Informatika Politeknik Negeri Sriwijaya">
    <title><?= e($pageTitle) ?> | <?= APP_FULL_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css?v=2.0.1">
    <meta name="csrf-token" content="<?= $csrfToken ?>">
</head>
<body>

<div class="topbar-custom d-none d-lg-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="topbar-info d-flex gap-4">
            <span><i class="fas fa-envelope text-accent me-2"></i><?= APP_EMAIL ?></span>
            <span><i class="fas fa-phone text-accent me-2"></i><?= APP_PHONE ?></span>
            <span><i class="fas fa-clock text-accent me-2"></i>Senin – Jumat: 08.00 – 16.00 WIB</span>
        </div>
        <div class="topbar-social d-flex gap-3">
            <a href="https://www.instagram.com/jurusan.mi.polsri/" target="_blank" rel="noopener" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="https://www.instagram.com/hmjmi_polsri/" target="_blank" rel="noopener" title="HMJ MI"><i class="fab fa-users"></i></a>
            <a href="https://manajemeninformatika.polsri.ac.id" target="_blank" rel="noopener" title="Website Polsri"><i class="fas fa-globe"></i></a>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-custom" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand" href="<?= APP_URL ?>/index.php">
            <div class="brand-logo">
                <div class="brand-icon">
                    <img src="<?= APP_URL ?>/assets/images/mi.png" alt="Logo MI" style="width:28px;height:28px;object-fit:contain;" onerror="this.outerHTML='<i class=\'fas fa-graduation-cap\' style=\'color:var(--clr-accent);font-size:1rem;\'></i>'">
                </div>
                <div class="brand-text">
                    <span class="brand-name">Manajemen Informatika</span>
                    <span class="brand-sub">Politeknik Negeri Sriwijaya</span>
                </div>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigasi">
            <span class="toggler-icon"><i class="fas fa-bars"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link <?= isActive('index.php') ?>" href="<?= APP_URL ?>/index.php"><i class="fas fa-home me-1"></i>Beranda</a>
                </li>
                
                <!-- Dropdown Profil -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= isActive('about.php') ?>" href="#" id="profilDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-building-columns me-1"></i>Profil
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="profilDropdown">
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/about.php#sejarah">Sejarah</a></li>
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/about.php#visi-misi">Visi &amp; Misi</a></li>
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/about.php#struktur-organisasi">Struktur Organisasi</a></li>
                    </ul>
                </li>

                <!-- Dropdown Akademik -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= (isActive('dosen.php') || isActive('mahasiswa.php') || isActive('download.php') || isActive('kurikulum.php')) ? 'active' : '' ?>" href="#" id="akademikDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-graduation-cap me-1"></i>Akademik
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="akademikDropdown">
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/about.php#akreditasi">Akreditasi &amp; Program Studi</a></li>
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/dosen.php">Daftar Dosen</a></li>
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/mahasiswa.php">Prestasi Mahasiswa</a></li>
                        <li><a class="dropdown-item" href="<?= APP_URL ?>/download.php"><i class="fas fa-file-download me-1" style="color:var(--clr-primary);"></i> Pusat Unduhan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item <?= isActive('kurikulum.php') ?>" href="<?= APP_URL ?>/kurikulum.php">Kurikulum</a></li>
                        <li><a class="dropdown-item" href="#" onclick="alert('Kalender Akademik Polsri terbaru dapat diunduh di portal utama.')">Kalender Akademik</a></li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="alert('Fasilitas laboratorium dan penunjang akademik dapat dilihat pada menu Galeri.')"><i class="fas fa-flask me-1"></i>Fasilitas</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= isActive('galeri.php') ?>" href="<?= APP_URL ?>/galeri.php"><i class="fas fa-images me-1"></i>Galeri</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link <?= isActive('berita.php') ?>" href="<?= APP_URL ?>/berita.php"><i class="fas fa-newspaper me-1"></i>Berita</a>
                </li>
                
                <li class="nav-item nav-cta-item ms-lg-2">
                    <a class="nav-link nav-cta <?= isActive('kontak.php') ?>" href="<?= APP_URL ?>/kontak.php"><i class="fas fa-envelope me-1"></i>Kontak</a>
                </li>
                
                <li class="nav-item ms-lg-2">
                    <button class="theme-toggle" id="themeToggle" title="Toggle Tema">
                        <i class="fas fa-moon" id="themeIcon"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<?php $flash = getFlash(); if ($flash): ?>
<div class="alert-flash alert-flash-<?= e($flash['type']) ?>" id="flashMsg" role="alert">
    <i class="fas fa-<?= $flash['type']==='success'?'check-circle':'exclamation-triangle' ?> me-2"></i>
    <?= e($flash['message']) ?>
    <button onclick="this.parentElement.remove()" class="flash-close" aria-label="Tutup"><i class="fas fa-times"></i></button>
</div>
<?php endif; ?>
