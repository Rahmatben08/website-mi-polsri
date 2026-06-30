<?php
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db = getDB();

// Handle delete
if (isset($_GET['delete']) && isset($_GET['token'])) {
    $delId = (int)$_GET['delete'];
    if ($delId && validateCsrfToken($_GET['token'])) {
        $db->prepare("DELETE FROM pesan_kontak WHERE id = ?")->execute([$delId]);
        setFlash('success', 'Pesan berhasil dihapus.');
    }
    redirect(APP_URL.'/admin/pesan.php');
}

$pesanList = $db->query("SELECT * FROM pesan_kontak ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Pesan Kontak | Admin MI</title>
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
            <h5 style="margin:0;font-size:1rem;font-weight:700;">Pesan Kontak <span style="background:#E2E8F0;color:#64748B;font-size:0.75rem;padding:3px 10px;border-radius:50px;margin-left:8px;"><?= count($pesanList) ?></span></h5>
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
            <?php if (!empty($pesanList)): ?>
            <button onclick="exportMessagesToCSV()" class="btn-admin-primary" style="font-size:0.78rem;padding:6px 14px;background:#10B981;border-color:#10B981; display:flex; align-items:center; gap:6px;">
                <i class="fas fa-file-csv"></i> Ekspor CSV
            </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="admin-main">
        <?php $flash = getFlash(); if ($flash): ?>
        <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> mb-4"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <?php if (empty($pesanList)): ?>
        <div class="admin-card" style="text-align:center;padding:60px;color:#94A3B8;">
            <i class="fas fa-inbox" style="font-size:3rem;margin-bottom:16px;display:block;"></i>
            <p>Belum ada pesan masuk</p>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($pesanList as $p): ?>
            <div class="col-md-6">
                <div class="admin-card">
                    <div style="padding:20px 24px;border-bottom:1px solid #E2E8F0;display:flex;align-items:center;justify-content:space-between;gap:12px;">
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="width:40px;height:40px;background:var(--clr-primary-light);border-radius:10px;display:flex;align-items:center;justify-content:center;color:var(--clr-primary);font-weight:700;font-size:1rem;">
                                <?= strtoupper(substr($p['nama'], 0, 1)) ?>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:0.9rem;"><?= e($p['nama']) ?></div>
                                <a href="mailto:<?= e($p['email']) ?>" style="font-size:0.78rem;color:var(--clr-primary);"><?= e($p['email']) ?></a>
                            </div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:0.72rem;color:#94A3B8;"><?= formatTanggal($p['created_at']) ?></div>
                            <div style="font-size:0.7rem;color:#CBD5E1;">#<?= $p['id'] ?></div>
                        </div>
                    </div>
                    <div style="padding:20px 24px;">
                        <p style="font-size:0.875rem;color:#475569;line-height:1.7;margin-bottom:16px;"><?= nl2br(e($p['pesan'])) ?></p>
                        <div style="display:flex;gap:8px;">
                            <a href="mailto:<?= e($p['email']) ?>"
                               style="background:#EFF6FF;color:#3B82F6;padding:8px 14px;border-radius:8px;font-size:0.8rem;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                                <i class="fas fa-reply"></i> Balas
                            </a>
                            <a href="pesan.php?delete=<?= $p['id'] ?>&token=<?= generateCsrfToken() ?>"
                               onclick="return confirm('Yakin ingin menghapus pesan ini?')"
                               style="background:#FEF2F2;color:#DC2626;padding:8px 14px;border-radius:8px;font-size:0.8rem;font-weight:600;display:inline-flex;align-items:center;gap:6px;">
                                <i class="fas fa-trash"></i> Hapus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</main>
</body></html>
