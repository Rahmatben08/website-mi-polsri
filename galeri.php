<?php
// galeri.php — Halaman Galeri
require_once __DIR__.'/includes/config.php';
$pageTitle = 'Galeri';
$db = getDB();

$galeriList = $db->query(
    "SELECT * FROM galeri ORDER BY created_at DESC"
)->fetchAll();

include __DIR__.'/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <span class="page-hero-badge">
            <i class="fas fa-images"></i> Dokumentasi
        </span>
        <h1>Galeri Kegiatan<br>
            <span style="color:var(--clr-primary);">MI Polsri</span>
        </h1>
        <p>Kumpulan dokumentasi foto berbagai kegiatan akademik dan
           non-akademik jurusan yang penuh kenangan berharga.</p>
    </div>
</section>

<section class="section-py">
    <div class="container">

        <!-- Header info -->
        <div class="section-header text-center fade-up">
            <span class="badge-label">
                <i class="fas fa-camera me-1"></i>
                <?= count($galeriList) ?> Foto Dokumentasi
            </span>
            <h2>Momen Berharga Kami</h2>
            <p>Klik foto untuk melihat tampilan penuh.
               Gunakan tombol panah atau keyboard untuk navigasi.</p>
        </div>

        <?php if (empty($galeriList)): ?>
        <div style="text-align:center;padding:80px;color:var(--clr-text-muted);">
            <i class="fas fa-image" style="font-size:3.5rem;opacity:0.2;margin-bottom:20px;display:block;"></i>
            <h4>Belum ada foto galeri</h4>
            <p>Foto akan ditampilkan di sini setelah ditambahkan oleh admin.</p>
        </div>
        <?php else: ?>

        <!-- ── Masonry-style Grid ── -->
        <div class="row g-3">
            <?php foreach ($galeriList as $i => $g):
                // Pola: item ke-0 dan ke-3 di setiap grup 5 = lebar (col-8)
                $mod = $i % 5;
                $col = ($mod === 0 || $mod === 3)
                       ? 'col-lg-8 col-md-8'
                       : 'col-lg-4 col-md-4 col-sm-6';
                $ratio = ($mod === 0 || $mod === 3) ? '16/9' : '4/3';
            ?>
            <div class="<?= $col ?> fade-up">
                <div class="galeri-item"
                     data-lightbox="<?= APP_URL ?>/assets/images/<?= e($g['gambar']) ?>"
                     data-caption="<?= e($g['judul']) ?>"
                     style="aspect-ratio:<?= $ratio ?>;"
                     role="button"
                     tabindex="0"
                     aria-label="Lihat foto: <?= e($g['judul']) ?>">

                    <img src="<?= APP_URL ?>/assets/images/<?= e($g['gambar']) ?>"
                         alt="<?= e($g['judul']) ?>"
                         loading="lazy"
                         onerror="this.src='https://placehold.co/800x600/DCEBFA/003366?text=<?= urlencode($g['judul']) ?>'">

                    <div class="galeri-overlay">
                        <div class="icon" aria-hidden="true">
                            <i class="fas fa-expand"></i>
                        </div>
                        <p><?= e($g['judul']) ?></p>
                    </div>
                </div>

                <?php if (!empty($g['deskripsi'])): ?>
                <div style="padding:8px 4px;font-size:0.78rem;color:var(--clr-text-muted);">
                    <i class="fas fa-info-circle me-1 text-accent"></i>
                    <?= e($g['deskripsi']) ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Keyboard tips -->
        <div class="text-center mt-5 fade-up">
            <p style="color:var(--clr-text-muted);font-size:0.875rem;">
                <i class="fas fa-keyboard me-1"></i>
                Tips: Tekan
                <kbd style="background:var(--clr-bg-soft);border:1px solid var(--clr-border);
                     padding:2px 7px;border-radius:4px;font-size:0.8rem;">←</kbd>
                <kbd style="background:var(--clr-bg-soft);border:1px solid var(--clr-border);
                     padding:2px 7px;border-radius:4px;font-size:0.8rem;">→</kbd>
                untuk navigasi,
                <kbd style="background:var(--clr-bg-soft);border:1px solid var(--clr-border);
                     padding:2px 7px;border-radius:4px;font-size:0.8rem;">Esc</kbd>
                untuk menutup lightbox
            </p>
        </div>

        <?php endif; ?>
    </div>
</section>

<script>
// Keyboard support untuk galeri item (aksesibilitas)
document.querySelectorAll('.galeri-item[role="button"]').forEach(item => {
    item.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            item.click();
        }
    });
});
</script>

<?php include __DIR__.'/includes/footer.php'; ?>
