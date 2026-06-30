<?php
// download.php — Pusat Unduhan Dokumen
require_once __DIR__.'/includes/config.php';
$pageTitle = 'Pusat Unduhan';
$db = getDB();

// Fetch all documents
$dokumenList = $db->query("SELECT * FROM dokumen ORDER BY created_at DESC")->fetchAll();

include __DIR__.'/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <span class="page-hero-badge">
            <i class="fas fa-file-download"></i> Dokumen
        </span>
        <h1>Pusat Unduhan<br>
            <span style="color:var(--clr-primary);">Akademik</span>
        </h1>
        <p>Akses cepat ke berbagai berkas, formulir, panduan PKL, format Laporan Akhir, dan informasi kelulusan untuk mahasiswa MI Polsri.</p>
    </div>
</section>

<section class="section-py">
    <div class="container">
        
        <!-- Live Search & Info -->
        <div class="row align-items-center g-3 mb-5 fade-up">
            <div class="col-md-6">
                <div style="position:relative;">
                    <i class="fas fa-search" style="position:absolute; left:16px; top:50%; transform:translateY(-50%); color:var(--clr-text-light); font-size:0.95rem;"></i>
                    <input type="text" id="docSearchInput" placeholder="Cari nama dokumen atau kategori..." class="form-control-custom" style="padding-left:44px; margin-bottom:0;">
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <span id="docCountText" style="font-size:0.875rem; color:var(--clr-text-muted); font-weight:600;">
                    <?= count($dokumenList) ?> Dokumen Tersedia
                </span>
            </div>
        </div>

        <!-- Empty State -->
        <div id="docEmptyState" style="display:none; text-align:center; padding:80px 20px; color:var(--clr-text-muted);" class="fade-up">
            <i class="fas fa-file-excel" style="font-size:3rem; opacity:0.25; margin-bottom:20px; display:block;"></i>
            <h4>Dokumen tidak ditemukan</h4>
            <p>Coba kata kunci lain atau silakan hubungi admin jurusan.</p>
        </div>

        <!-- Grid Dokumen -->
        <div class="row g-4" id="docGridContainer">
            <?php if (empty($dokumenList)): ?>
                <div style="text-align:center; padding:80px 20px; color:var(--clr-text-muted);" class="w-100">
                    <i class="fas fa-folder-open" style="font-size:3rem; opacity:0.25; margin-bottom:20px; display:block;"></i>
                    <h4>Belum ada dokumen yang diunggah</h4>
                    <p>Silakan kembali di lain waktu.</p>
                </div>
            <?php else: foreach ($dokumenList as $doc): 
                // Determine icon based on category or name
                $icon = 'fa-file-alt';
                $cat = strtolower($doc['kategori']);
                if (strpos($cat, 'pkl') !== false) $icon = 'fa-user-tie';
                else if (strpos($cat, 'laporan') !== false || strpos($cat, 'akhir') !== false) $icon = 'fa-book';
                else if (strpos($cat, 'yudisium') !== false || strpos($cat, 'lulus') !== false) $icon = 'fa-graduation-cap';
                else if (strpos($cat, 'beasiswa') !== false) $icon = 'fa-award';
                
                // File extension info
                $ext = pathinfo($doc['file_name'], PATHINFO_EXTENSION);
                $extLabel = strtoupper($ext ?: 'FILE');
            ?>
                <div class="col-lg-4 col-md-6 fade-up doc-card-col" data-name="<?= e($doc['nama']) ?>" data-kategori="<?= e($doc['kategori']) ?>" data-description="<?= e($doc['deskripsi']) ?>">
                    <div class="berita-card card-shine" style="height: 100%; display: flex; flex-direction: column;">
                        <div style="padding: 24px; flex: 1; display: flex; flex-direction: column; gap: 14px;">
                            <!-- Category & Extension -->
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span class="badge-modern badge-modern-secondary" style="font-size:0.7rem; text-transform:uppercase;">
                                    <?= e($doc['kategori']) ?>
                                </span>
                                <span style="font-size:0.7rem; font-weight:700; color:var(--clr-primary); background:var(--clr-primary-xlight); padding:2px 8px; border-radius:4px;">
                                    <?= $extLabel ?>
                                </span>
                            </div>
                            
                            <!-- Header Icon and Name -->
                            <div style="display:flex; gap:14px; align-items:flex-start;">
                                <div style="width:42px; height:42px; border-radius:10px; background:var(--clr-primary-xlight); color:var(--clr-primary); display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0;">
                                    <i class="fas <?= $icon ?>"></i>
                                </div>
                                <h5 style="margin:0; font-size:0.95rem; font-weight:700; line-height:1.4; color:var(--clr-text); display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical; overflow:hidden;">
                                    <?= e($doc['nama']) ?>
                                </h5>
                            </div>
                            
                            <!-- Description -->
                            <p style="font-size:0.8rem; color:var(--clr-text-light); line-height:1.5; margin:0; flex:1; display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical; overflow:hidden;">
                                <?= e($doc['deskripsi']) ?: 'Tidak ada deskripsi tambahan.' ?>
                            </p>
                            
                            <!-- Action Footer -->
                            <div style="border-top:1px solid var(--clr-border); padding-top:14px; margin-top:10px; display:flex; align-items:center; justify-content:space-between;">
                                <span style="font-size:0.7rem; color:var(--clr-text-muted);">
                                    <i class="far fa-calendar-alt me-1"></i> <?= date('d/m/Y', strtotime($doc['created_at'])) ?>
                                </span>
                                <a href="<?= APP_URL ?>/assets/documents/<?= e($doc['file_name']) ?>" download class="btn-primary-custom" style="padding:6px 14px; font-size:0.78rem; border-radius:8px; min-width:unset;">
                                    <i class="fas fa-download me-1"></i> Unduh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>

    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('docSearchInput');
    const docCards = document.querySelectorAll('.doc-card-col');
    const emptyState = document.getElementById('docEmptyState');
    const countText = document.getElementById('docCountText');
    
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            let visibleCount = 0;
            
            docCards.forEach(card => {
                const name = card.getAttribute('data-name').toLowerCase();
                const cat = card.getAttribute('data-kategori').toLowerCase();
                const desc = card.getAttribute('data-description').toLowerCase();
                
                if (name.includes(query) || cat.includes(query) || desc.includes(query)) {
                    card.style.display = '';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Empty State
            if (visibleCount === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
            
            // Count text
            countText.textContent = `${visibleCount} Dokumen Ditemukan`;
        });
    }
});
</script>

<?php include __DIR__.'/includes/footer.php'; ?>
