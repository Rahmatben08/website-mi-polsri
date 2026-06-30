<?php
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db = getDB();
$beritaList = $db->query("SELECT * FROM berita ORDER BY created_at DESC")->fetchAll();
$adminPage  = 'berita';
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Kelola Berita | Admin MI</title>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
</head><body style="background:#F8FAFC;">
<?php include __DIR__.'/_sidebar.php'; ?>
<main class="admin-content">
    <div class="admin-topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button id="sidebarToggle" class="sidebar-toggle-btn">
                <i class="fas fa-bars"></i>
            </button>
            <h5 style="margin:0;font-size:1rem;font-weight:700;">Kelola Berita & Artikel</h5>
        </div>
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="admin-clock-widget">
                <i class="far fa-clock"></i>
                <span id="adminDigitalClock">00:00:00</span>
                <span class="divider">|</span>
                <i class="fas fa-calendar-alt"></i>
                <span id="adminCalendarDate">00-00-0000</span>
            </div>
            <!-- Theme Toggle Button -->
            <button id="themeToggleBtn" class="theme-toggle-btn" title="Ganti Tema">
                <i class="fas fa-moon"></i>
            </button>
            <a href="<?= APP_URL ?>/admin/berita_tambah.php" class="btn-admin-primary"><i class="fas fa-plus"></i> Tambah Berita</a>
        </div>
    </div>
    <div class="admin-main">
        <?php $flash=getFlash(); if($flash): ?>
        <div class="alert alert-<?= $flash['type']==='success'?'success':'danger' ?> mb-4"><?= e($flash['message']) ?></div>
        <?php endif; ?>
        <div class="admin-card">
            <div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <h5 style="margin:0;">Daftar Berita <span style="background:#E2E8F0;color:#64748B;font-size:0.75rem;padding:3px 10px;border-radius:50px;margin-left:8px;"><?= count($beritaList) ?></span></h5>
                <div style="position:relative; width:100%; max-width:240px;">
                    <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94A3B8; font-size:0.8rem;"></i>
                    <input type="text" id="liveSearchInput" placeholder="Cari berita..." class="form-control-custom" style="padding-left:32px; font-size:0.8rem; height:34px; border-radius:6px; margin:0; width:100%;">
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="table-admin">
                    <thead><tr><th>#</th><th>Judul</th><th>Kategori</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                    <tbody>
                    <?php if(empty($beritaList)): ?>
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:#94A3B8;">Belum ada berita. <a href="<?= APP_URL ?>/admin/berita_tambah.php">Tambah sekarang</a></td></tr>
                    <?php else: foreach($beritaList as $i=>$b): ?>
                    <tr>
                        <td style="color:#94A3B8;font-size:0.8rem;"><?= $i+1 ?></td>
                        <td style="max-width:280px;">
                            <div style="font-weight:600;font-size:0.875rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($b['judul']) ?></div>
                            <div style="font-size:0.75rem;color:#94A3B8;">/<?= e($b['slug']) ?></div>
                        </td>
                        <td><span class="badge-modern badge-modern-secondary"><?= ucfirst(e($b['kategori'])) ?></span></td>
                        <td><span class="badge-modern badge-modern-<?= $b['status'] === 'publish' ? 'success' : 'warning' ?>"><?= ucfirst($b['status']) ?></span></td>
                        <td style="font-size:0.78rem;color:#64748B;white-space:nowrap;"><?= formatTanggal($b['created_at']) ?></td>
                        <td>
                            <div style="display:flex;gap:6px;">
                                <a href="<?= APP_URL ?>/berita_detail/<?= e($b['slug']) ?>.php" target="_blank" style="background:#EFF6FF;color:#3B82F6;padding:6px 10px;border-radius:6px;font-size:0.78rem;"><i class="fas fa-eye"></i></a>
                                <a href="<?= APP_URL ?>/admin/berita_edit.php?id=<?= $b['id'] ?>" style="background:#ECFDF5;color:#059669;padding:6px 10px;border-radius:6px;font-size:0.78rem;"><i class="fas fa-edit"></i></a>
                                <a href="<?= APP_URL ?>/admin/berita_delete.php?id=<?= $b['id'] ?>&token=<?= generateCsrfToken() ?>" onclick="return confirm('Hapus berita ini?')" style="background:#FEF2F2;color:#DC2626;padding:6px 10px;border-radius:6px;font-size:0.78rem;"><i class="fas fa-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.table-admin tbody tr:not(.no-data-row)');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.style.transition = 'opacity 0.2s ease';
                        row.style.opacity = '1';
                    }, 10);
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            let noDataRow = document.getElementById('noDataRow');
            if (visibleCount === 0) {
                if (!noDataRow) {
                    const tbody = document.querySelector('.table-admin tbody');
                    const colCount = document.querySelectorAll('.table-admin th').length;
                    noDataRow = document.createElement('tr');
                    noDataRow.id = 'noDataRow';
                    noDataRow.className = 'no-data-row';
                    noDataRow.innerHTML = `<td colspan="${colCount}" style="text-align:center; padding:30px; color:#94A3B8;"><i class="fas fa-search mb-2" style="font-size:1.8rem; display:block;"></i>Tidak ada data yang cocok dengan pencarian Anda.</td>`;
                    tbody.appendChild(noDataRow);
                }
            } else {
                if (noDataRow) noDataRow.remove();
            }
        });
    }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
