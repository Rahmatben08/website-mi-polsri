<?php
// dosen.php — Halaman Dosen
require_once __DIR__.'/includes/config.php';
$pageTitle = 'Dosen';
$db = getDB();

// Search filter
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($search !== '') {
    $stmt = $db->prepare(
        "SELECT * FROM dosen
         WHERE nama LIKE ? OR bidang_keahlian LIKE ?
         ORDER BY jabatan DESC, nama ASC"
    );
    $term = "%$search%";
    $stmt->execute([$term, $term]);
} else {
    $stmt = $db->query(
        "SELECT * FROM dosen ORDER BY jabatan DESC, nama ASC"
    );
}
$dosenList = $stmt->fetchAll();

// Pisahkan ketua jurusan & dosen lain
$ketua  = null;
$others = [];
foreach ($dosenList as $d) {
    if (stripos($d['jabatan'], 'Ketua') !== false && !$search) {
        $ketua = $d;
    } else {
        $others[] = $d;
    }
}

include __DIR__.'/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <span class="page-hero-badge">
            <i class="fas fa-chalkboard-teacher"></i> Tenaga Pengajar
        </span>
        <h1>Dosen Jurusan<br>
            <span style="color:var(--clr-primary);">Manajemen Informatika</span>
        </h1>
        <p>Tim pengajar profesional dengan pengalaman akademik dan industri
           yang luar biasa untuk membimbing mahasiswa menuju karir terbaik.</p>
    </div>
</section>

<section class="section-py">
    <div class="container">

        <!-- Search Bar -->
        <div class="row justify-content-center mb-5 fade-up">
            <div class="col-md-6">
                <form method="GET" action="">
                    <div style="display:flex;gap:10px;">
                        <input type="text" name="q"
                               value="<?= e($search) ?>"
                               placeholder="Cari nama atau bidang keahlian..."
                               class="form-control-custom" style="flex:1;">
                        <button type="submit" class="btn-primary-custom"
                                style="padding:12px 20px;min-width:unset;">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php if ($search): ?>
                        <a href="dosen.php" class="btn-outline-custom"
                           style="padding:12px 16px;">
                            <i class="fas fa-times"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </form>

                <?php if ($search): ?>
                <p style="margin-top:10px;font-size:0.85rem;color:var(--clr-text-muted);">
                    Hasil pencarian: <strong>"<?= e($search) ?>"</strong>
                    — <?= count($dosenList) ?> dosen ditemukan
                </p>
                <?php endif; ?>
            </div>
        </div>

        <?php if (empty($dosenList)): ?>
        <!-- Empty State -->
        <div style="text-align:center;padding:80px 20px;color:var(--clr-text-muted);">
            <i class="fas fa-search" style="font-size:3.5rem;opacity:0.25;margin-bottom:20px;display:block;"></i>
            <h4>Tidak ada dosen ditemukan</h4>
            <p>Coba gunakan kata kunci yang berbeda</p>
            <a href="dosen.php" class="btn-outline-custom" style="margin-top:16px;">
                <i class="fas fa-arrow-left me-1"></i> Lihat Semua Dosen
            </a>
        </div>

        <?php else: ?>

        <!-- ── Ketua Jurusan Featured Card ── -->
        <?php if ($ketua): ?>
        <div class="fade-up mb-5">
            <div class="ketua-featured-card">
                <div class="ketua-featured-avatar">
                    <?php if (!empty($ketua['foto']) && $ketua['foto'] !== 'default-dosen.jpg' && file_exists(__DIR__ . '/assets/images/' . $ketua['foto'])): ?>
                    <img src="<?= APP_URL ?>/assets/images/<?= e($ketua['foto']) ?>"
                          alt="Foto <?= e($ketua['nama']) ?>"
                          style="width:100%;height:100%;object-fit:cover;">
                    <?php else: ?>
                    <i class="fas fa-user-tie" style="color:var(--clr-accent);font-size:2.4rem;"></i>
                    <?php endif; ?>
                </div>
                <div class="ketua-featured-info">
                    <div class="ketua-label">Ketua Jurusan</div>
                    <h3><?= e($ketua['nama']) ?></h3>
                    <p><?= e($ketua['bidang_keahlian']) ?></p>
                    <div style="display:flex;gap:14px;flex-wrap:wrap;margin-top:14px;">
                        <a href="mailto:<?= e($ketua['email']) ?>"
                           class="ketua-contact-btn">
                            <i class="fas fa-envelope"></i> <?= e($ketua['email']) ?>
                        </a>
                        <?php if (!empty($ketua['nidn'])): ?>
                        <span class="ketua-contact-btn" style="cursor:default;">
                            <i class="fas fa-id-badge"></i> NIDN: <?= e($ketua['nidn']) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Decorative -->
                <div class="ketua-deco"><i class="fas fa-award"></i></div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Grid Dosen ── -->
        <?php $displayList = $search ? $dosenList : $others; ?>
        <?php if (!empty($displayList)): ?>
        <div class="row g-4">
            <?php foreach ($displayList as $d): ?>
            <div class="col-xl-3 col-lg-4 col-sm-6 fade-up">
                <div class="dosen-card card-shine">
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
                            <i class="fas fa-tag me-1 text-accent"></i>
                            <?= e($d['bidang_keahlian']) ?>
                        </div>
                        <?php if (!empty($d['nidn'])): ?>
                        <div style="font-size:0.75rem;color:var(--clr-text-light);margin-bottom:8px;">
                            <i class="fas fa-id-badge me-1"></i>NIDN: <?= e($d['nidn']) ?>
                        </div>
                        <?php endif; ?>
                        <div class="dosen-email">
                            <a href="mailto:<?= e($d['email']) ?>">
                                <i class="fas fa-envelope"></i><?= e($d['email']) ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Info total -->
        <div class="text-center mt-5 fade-up">
            <p style="font-size:0.875rem;color:var(--clr-text-muted);">
                <i class="fas fa-info-circle me-1"></i>
                Menampilkan <strong><?= count($dosenList) ?></strong> dari total
                <strong><?= $db->query("SELECT COUNT(*) FROM dosen")->fetchColumn() ?></strong>
                dosen aktif
            </p>
        </div>

        <?php endif; ?>
    </div>
</section>

<?php include __DIR__.'/includes/footer.php'; ?>
