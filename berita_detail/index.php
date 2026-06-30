<?php
// berita_detail/index.php — Detail Berita
require_once __DIR__.'/../includes/config.php';
$db = getDB();

// Ambil slug dari URL
$slug = '';
$uri  = $_SERVER['REQUEST_URI'];
if (preg_match('/berita_detail\/([a-z0-9\-]+)(?:\.php)?(?:\?.*)?$/', $uri, $m)) {
    $slug = $m[1];
}
if (!$slug && isset($_GET['slug'])) {
    $slug = preg_replace('/[^a-z0-9\-]/', '', $_GET['slug']);
}
if (!$slug) redirect(APP_URL.'/berita.php');

// Fetch berita
$stmt = $db->prepare("SELECT * FROM berita WHERE slug=? AND status='publish' LIMIT 1");
$stmt->execute([$slug]);
$berita = $stmt->fetch();

if (!$berita) {
    http_response_code(404);
    $pageTitle = '404 — Tidak Ditemukan';
    include __DIR__.'/../includes/header.php';
    echo '<div class="container" style="padding:140px 20px;text-align:center;">
        <i class="fas fa-newspaper" style="font-size:4rem;color:var(--clr-primary-light);margin-bottom:20px;display:block;"></i>
        <h2>Berita Tidak Ditemukan</h2>
        <p style="color:var(--clr-text-muted);">Berita yang kamu cari tidak ada atau telah dihapus.</p>
        <a href="'.APP_URL.'/berita.php" class="btn-primary-custom" style="margin-top:24px;display:inline-flex;">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Berita
        </a></div>';
    include __DIR__.'/../includes/footer.php';
    exit;
}

// Berita terkait
$rel = $db->prepare("SELECT id,judul,slug,gambar,created_at FROM berita WHERE kategori=? AND id!=? AND status='publish' ORDER BY created_at DESC LIMIT 3");
$rel->execute([$berita['kategori'], $berita['id']]);
$related = $rel->fetchAll();

$pageTitle = $berita['judul'];
include __DIR__.'/../includes/header.php';
?>
<div style="height:var(--navbar-h);"></div>

<!-- Breadcrumb -->
<nav style="background:var(--clr-bg-soft);border-bottom:1px solid var(--clr-border);padding:12px 0;">
    <div class="container">
        <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;flex-wrap:wrap;">
            <a href="<?= APP_URL ?>/index.php" style="color:var(--clr-text-muted);">Beranda</a>
            <i class="fas fa-chevron-right" style="font-size:0.6rem;color:var(--clr-text-light);"></i>
            <a href="<?= APP_URL ?>/berita.php" style="color:var(--clr-text-muted);">Berita</a>
            <i class="fas fa-chevron-right" style="font-size:0.6rem;color:var(--clr-text-light);"></i>
            <span style="color:var(--clr-primary);font-weight:500;"><?= truncate($berita['judul'],50) ?></span>
        </div>
    </div>
</nav>

<section class="section-py">
    <div class="container">
        <div class="row g-5 justify-content-center">

            <!-- Artikel Utama -->
            <div class="col-lg-8">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
                    <span style="background:var(--clr-primary);color:white;font-size:0.72rem;font-weight:700;padding:5px 14px;border-radius:50px;text-transform:uppercase;">
                        <?= ucfirst(e($berita['kategori'])) ?>
                    </span>
                    <span style="font-size:0.82rem;color:var(--clr-text-light);">
                        <i class="fas fa-calendar-alt me-1"></i><?= formatTanggal($berita['created_at']) ?>
                    </span>
                </div>

                <h1 style="font-size:clamp(1.8rem,4vw,2.5rem);line-height:1.25;margin-bottom:24px;">
                    <?= e($berita['judul']) ?>
                </h1>

                <div style="border-radius:var(--radius-xl);overflow:hidden;margin-bottom:36px;background:var(--clr-primary-xlight);">
                    <img src="<?= APP_URL ?>/assets/images/<?= e($berita['gambar']) ?>"
                         alt="<?= e($berita['judul']) ?>"
                         style="width:100%;max-height:460px;object-fit:cover;display:block;"
                         onerror="this.src='https://placehold.co/1200x600/DCEBFA/003366?text=MI+Polsri'">
                </div>

                <div class="article-body"><?= $berita['konten'] ?></div>

                <hr style="border:none;border-top:1px solid var(--clr-border);margin:36px 0;">

                <!-- Share -->
                <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                    <span style="font-size:0.875rem;font-weight:600;color:var(--clr-text-muted);">Bagikan:</span>
                    <?php
                    $su = urlencode(APP_URL.'/berita_detail/'.$berita['slug'].'.php');
                    $st = urlencode($berita['judul']);
                    $shares=[
                        ['fab fa-whatsapp','#25D366',"https://wa.me/?text={$st}%20{$su}",'WhatsApp'],
                        ['fab fa-twitter','#1DA1F2',"https://twitter.com/intent/tweet?text={$st}&url={$su}",'Twitter'],
                        ['fab fa-facebook-f','#1877F2',"https://www.facebook.com/sharer/sharer.php?u={$su}",'Facebook'],
                    ];
                    foreach($shares as $s): ?>
                    <a href="<?= $s[2] ?>" target="_blank" rel="noopener"
                       style="display:inline-flex;align-items:center;gap:6px;background:<?= $s[1] ?>;color:white;padding:8px 16px;border-radius:50px;font-size:0.8rem;font-weight:600;text-decoration:none;">
                        <i class="<?= $s[0] ?>"></i><?= $s[3] ?>
                    </a>
                    <?php endforeach; ?>
                </div>

                <div style="margin-top:28px;">
                    <a href="<?= APP_URL ?>/berita.php" class="btn-outline-custom">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Berita
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <?php if (!empty($related)): ?>
                <div style="position:sticky;top:calc(var(--navbar-h)+20px);">
                    <h5 style="font-size:1rem;margin-bottom:20px;padding-bottom:10px;border-bottom:2px solid var(--clr-primary);">
                        <i class="fas fa-layer-group me-2 text-accent"></i>Berita Terkait
                    </h5>
                    <?php foreach($related as $r): ?>
                    <a href="<?= APP_URL ?>/berita_detail/<?= e($r['slug']) ?>.php"
                       style="display:flex;gap:14px;background:var(--clr-bg-soft);border:1px solid var(--clr-border);border-radius:var(--radius-md);padding:14px;margin-bottom:14px;transition:all 0.3s;text-decoration:none;"
                       onmouseover="this.style.borderColor='var(--clr-primary)'"
                       onmouseout="this.style.borderColor='var(--clr-border)'">
                        <div style="width:70px;height:70px;border-radius:8px;overflow:hidden;flex-shrink:0;background:var(--clr-primary-xlight);display:flex;align-items:center;justify-content:center;color:var(--clr-primary);font-size:1.5rem;">
                            <img src="<?= APP_URL ?>/assets/images/<?= e($r['gambar']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" onerror="this.style.display='none'">
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:0.72rem;color:var(--clr-text-light);margin-bottom:4px;">
                                <i class="fas fa-calendar-alt me-1"></i><?= formatTanggal($r['created_at']) ?>
                            </div>
                            <div style="font-size:0.875rem;font-weight:600;color:var(--clr-text);display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;line-height:1.4;">
                                <?= e($r['judul']) ?>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.article-body{font-size:1rem;line-height:1.9;color:var(--clr-text);}
.article-body p{margin-bottom:1.2rem;}
.article-body h2,.article-body h3,.article-body h4{margin:2rem 0 1rem;}
.article-body ul,.article-body ol{padding-left:1.5rem;margin-bottom:1.2rem;}
.article-body li{margin-bottom:.5rem;}
.article-body strong{color:var(--clr-primary-dark);font-weight:700;}
.article-body blockquote{border-left:4px solid var(--clr-primary);padding:16px 20px;background:var(--clr-primary-xlight);border-radius:0 var(--radius-md) var(--radius-md) 0;margin:1.5rem 0;font-style:italic;color:var(--clr-text-muted);}
</style>

<?php include __DIR__.'/../includes/footer.php'; ?>
