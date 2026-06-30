<?php
// berita.php — Halaman Berita & Artikel
require_once __DIR__.'/includes/config.php';
$pageTitle = 'Berita & Artikel';
$db = getDB();

// ── Filter & Search ──
$kategori = isset($_GET['kat']) && in_array($_GET['kat'], ['berita','artikel'])
            ? $_GET['kat'] : '';
$search   = isset($_GET['q']) ? trim($_GET['q']) : '';

// ── Build Query ──
$sql    = "SELECT * FROM berita WHERE status='publish'";
$params = [];

if ($kategori) {
    $sql    .= " AND kategori = ?";
    $params[] = $kategori;
}
if ($search) {
    $sql    .= " AND (judul LIKE ? OR konten LIKE ?)";
    $term    = "%$search%";
    $params[] = $term;
    $params[] = $term;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$beritaList = $stmt->fetchAll();

// Berita unggulan = yang pertama (hanya jika tidak ada filter/search)
$featured = (!empty($beritaList) && !$search && !$kategori)
            ? array_shift($beritaList) : null;

include __DIR__.'/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <span class="page-hero-badge">
            <i class="fas fa-newspaper"></i> Informasi
        </span>
        <h1>Berita &amp; Artikel<br>
            <span style="color:var(--clr-primary);">Terkini</span>
        </h1>
        <p>Informasi terbaru seputar kegiatan, prestasi, dan perkembangan
           teknologi dari MI Polsri.</p>
    </div>
</section>

<section class="section-py">
    <div class="container">

        <!-- ── Filter & Search Bar ── -->
        <div class="row align-items-center g-3 mb-5 fade-up">
            <div class="col-md-5">
                <form method="GET" action="" onsubmit="event.preventDefault();">
                    <div style="display:flex;gap:8px;">
                        <input type="text" id="newsSearchInput" name="q"
                               value="<?= e($search) ?>"
                               placeholder="Cari berita atau artikel..."
                               class="form-control-custom" style="flex:1;">
                        <button type="button" class="btn-primary-custom"
                                style="padding:12px 18px;min-width:unset;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-7">
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <a href="berita.php"
                       class="filter-btn <?= (!$kategori && !$search) ? 'active' : '' ?>">
                        <i class="fas fa-th-large me-1"></i>Semua
                    </a>
                    <a href="berita.php?kat=berita<?= $search ? '&q='.urlencode($search) : '' ?>"
                       class="filter-btn <?= $kategori === 'berita' ? 'active' : '' ?>">
                        <i class="fas fa-bullhorn me-1"></i>Berita
                    </a>
                    <a href="berita.php?kat=artikel<?= $search ? '&q='.urlencode($search) : '' ?>"
                       class="filter-btn <?= $kategori === 'artikel' ? 'active' : '' ?>">
                        <i class="fas fa-pen-nib me-1"></i>Artikel
                    </a>
                    <a href="berita.php" class="filter-btn" id="resetFiltersBtn" style="display: <?= ($search || $kategori) ? 'inline-flex' : 'none' ?>;">
                        <i class="fas fa-times me-1"></i>Reset
                    </a>
                </div>
                <p id="resultsCountText" style="margin-top:10px;font-size:0.82rem;color:var(--clr-text-muted);">
                    <?= count($beritaList) + ($featured ? 1 : 0) ?> hasil ditemukan
                </p>
            </div>
        </div>

        <!-- Empty State (Hidden by default) -->
        <div id="newsEmptyState" style="display:none;text-align:center;padding:80px 20px;color:var(--clr-text-muted);">
            <i class="fas fa-search" style="font-size:3rem;opacity:0.25;margin-bottom:20px;display:block;"></i>
            <h4>Tidak ada hasil ditemukan</h4>
            <p>Coba kata kunci lain atau hapus filter kategori</p>
        </div>

        <!-- ── Featured Article ── -->
        <?php if ($featured): ?>
        <div class="fade-up mb-5 news-item-col" id="featuredNewsSection" data-title="<?= e($featured['judul']) ?>" data-content="<?= e(strip_tags($featured['konten'])) ?>" data-kategori="<?= e($featured['kategori']) ?>" data-featured="1">
            <div class="featured-card card-shine">
                <!-- Image side -->
                <div class="featured-img">
                    <img src="<?= APP_URL ?>/assets/images/<?= e($featured['gambar']) ?>"
                         alt="<?= e($featured['judul']) ?>"
                         onerror="this.src='https://placehold.co/800x500/DCEBFA/003366?text=MI+Polsri'">
                    <div class="featured-img-overlay"></div>
                </div>
                <!-- Text side -->
                <div class="featured-body">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
                        <span style="background:var(--clr-primary);color:white;
                              font-size:0.72rem;font-weight:700;
                              padding:4px 14px;border-radius:50px;
                              text-transform:uppercase;">
                            <?= ucfirst(e($featured['kategori'])) ?>
                        </span>
                        <span style="font-size:0.78rem;color:var(--clr-text-light);">
                            <i class="fas fa-star me-1" style="color:var(--clr-accent);"></i>
                            Artikel Unggulan
                        </span>
                    </div>
                    <h2 class="featured-title"><?= e($featured['judul']) ?></h2>
                    <p class="featured-date">
                        <i class="fas fa-calendar-alt me-1"></i>
                        <?= formatTanggal($featured['created_at']) ?>
                    </p>
                    <p class="featured-excerpt">
                        <?= truncate($featured['konten'], 200) ?>
                    </p>
                    <a href="<?= APP_URL ?>/berita_detail/<?= e($featured['slug']) ?>.php"
                       class="btn-primary-custom">
                        Baca Selengkapnya <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ── Berita Grid ── -->
        <div class="row g-4" id="newsGridContainer">
            <?php if (!empty($beritaList)): ?>
            <?php foreach ($beritaList as $b): ?>
            <div class="col-lg-4 col-md-6 fade-up news-item-col" data-title="<?= e($b['judul']) ?>" data-content="<?= e(strip_tags($b['konten'])) ?>" data-kategori="<?= e($b['kategori']) ?>" data-featured="0">
                <div class="berita-card card-shine">
                    <div class="berita-img">
                        <img src="<?= APP_URL ?>/assets/images/<?= e($b['gambar']) ?>"
                             alt="<?= e($b['judul']) ?>"
                             loading="lazy"
                             onerror="this.src='https://placehold.co/600x400/DCEBFA/003366?text=MI+Polsri'">
                        <span class="berita-kategori">
                            <?= ucfirst(e($b['kategori'])) ?>
                        </span>
                    </div>
                    <div class="berita-body">
                        <div class="berita-meta">
                            <i class="fas fa-calendar-alt"></i>
                            <?= formatTanggal($b['created_at']) ?>
                        </div>
                        <h5 class="berita-title"><?= e($b['judul']) ?></h5>
                        <p class="berita-excerpt"><?= truncate($b['konten'], 110) ?></p>
                        <a href="<?= APP_URL ?>/berita_detail/<?= e($b['slug']) ?>.php"
                           class="berita-link">
                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('newsSearchInput');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const newsCards = document.querySelectorAll('.news-item-col');
    const emptyState = document.getElementById('newsEmptyState');
    const resultsCount = document.getElementById('resultsCountText');
    const resetBtn = document.getElementById('resetFiltersBtn');
    
    let activeCategory = '<?= e($kategori) ?>';
    
    function filterNews() {
        const query = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        newsCards.forEach(card => {
            const title = card.getAttribute('data-title').toLowerCase();
            const content = card.getAttribute('data-content').toLowerCase();
            const cat = card.getAttribute('data-kategori').toLowerCase();
            
            const matchesQuery = title.includes(query) || content.includes(query);
            const matchesCategory = activeCategory === '' || cat === activeCategory;
            
            if (matchesQuery && matchesCategory) {
                card.style.display = '';
                // Trigger animation reset
                card.style.opacity = '1';
                card.style.transform = 'translateY(0) scale(1)';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Show/hide empty state
        if (visibleCount === 0) {
            emptyState.style.display = 'block';
            resultsCount.textContent = '0 hasil ditemukan';
        } else {
            emptyState.style.display = 'none';
            resultsCount.textContent = `${visibleCount} hasil ditemukan`;
        }
        
        // Show/hide reset button
        if (query !== '' || activeCategory !== '') {
            resetBtn.style.display = 'inline-flex';
        } else {
            resetBtn.style.display = 'none';
        }
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterNews);
    }
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const href = btn.getAttribute('href');
            if (href === 'berita.php') {
                e.preventDefault();
                activeCategory = '';
                searchInput.value = '';
            } else {
                const parts = href.split('?');
                if (parts.length > 1) {
                    const urlParams = new URLSearchParams(parts[1]);
                    if (urlParams.has('kat')) {
                        e.preventDefault();
                        activeCategory = urlParams.get('kat');
                    }
                }
            }
            
            // Update active status
            filterBtns.forEach(b => {
                if (b !== resetBtn) b.classList.remove('active');
            });
            if (btn !== resetBtn) {
                btn.classList.add('active');
            } else {
                // If it's the reset button, make 'Semua' active
                filterBtns[0].classList.add('active');
            }
            
            filterNews();
        });
    });
    
    // Initial run in case URL params exist
    if (searchInput.value !== '' || activeCategory !== '') {
        filterNews();
    }
});
</script>

<?php include __DIR__.'/includes/footer.php'; ?>
