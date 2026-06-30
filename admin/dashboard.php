<?php
// admin/dashboard.php
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db = getDB();

// Handler untuk Memo Catatan (AJAX POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['memo_action'])) {
    header('Content-Type: application/json');
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid. Silakan reload halaman.']);
        exit;
    }
    $memoAction = $_POST['memo_action'];
    if ($memoAction === 'add') {
        $isi = trim($_POST['isi'] ?? '');
        $warna = trim($_POST['warna'] ?? 'yellow');
        if (!empty($isi)) {
            $stmt = $db->prepare("INSERT INTO memo_catatan (isi, warna) VALUES (?, ?)");
            $stmt->execute([$isi, $warna]);
            $newId = $db->lastInsertId();
            echo json_encode(['success' => true, 'id' => $newId, 'isi' => htmlspecialchars($isi), 'warna' => $warna]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Isi catatan tidak boleh kosong.']);
        }
        exit;
    } elseif ($memoAction === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $stmt = $db->prepare("DELETE FROM memo_catatan WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['success' => true]);
        exit;
    }
}

// ── Statistik ──
$stats = [
    'berita'  => $db->query("SELECT COUNT(*) FROM berita")->fetchColumn(),
    'dosen'   => $db->query("SELECT COUNT(*) FROM dosen")->fetchColumn(),
    'galeri'  => $db->query("SELECT COUNT(*) FROM galeri")->fetchColumn(),
    'pesan'   => $db->query("SELECT COUNT(*) FROM pesan_kontak")->fetchColumn(),
    'pesan_baru' => $db->query(
        "SELECT COUNT(*) FROM pesan_kontak WHERE is_read=0"
    )->fetchColumn(),
    'mahasiswa' => $db->query("SELECT COUNT(*) FROM mahasiswa_prestasi")->fetchColumn(),
];

// ── Data terbaru ──
$pesanTerbaru  = $db->query(
    "SELECT * FROM pesan_kontak ORDER BY created_at DESC LIMIT 5"
)->fetchAll();
$beritaTerbaru = $db->query(
    "SELECT * FROM berita ORDER BY created_at DESC LIMIT 5"
)->fetchAll();

// ── Log Aktivitas Admin ──
$logAktivitas = $db->query(
    "SELECT * FROM log_aktivitas ORDER BY created_at DESC LIMIT 5"
)->fetchAll();

// ── Memo Catatan Kilat ──
$memoCatatan = $db->query(
    "SELECT * FROM memo_catatan ORDER BY created_at DESC"
)->fetchAll();

// Statistik Kunjungan 7 Hari Terakhir
$kunjunganStats = $db->query("
    SELECT tanggal, COUNT(DISTINCT ip_address) as jumlah 
    FROM kunjungan 
    WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY tanggal
    ORDER BY tanggal ASC
")->fetchAll(PDO::FETCH_KEY_PAIR);

$kunjunganChartData = [];
for ($i = 6; $i >= 0; $i--) {
    $tgl = date('Y-m-d', strtotime("-$i days"));
    $lbl = date('d M', strtotime($tgl));
    $kunjunganChartData[$lbl] = $kunjunganStats[$tgl] ?? 0;
}

// Penentuan sapaan dinamis berdasarkan jam
$hour = (int)date('H');
if ($hour >= 5 && $hour < 11) {
    $greeting = "Selamat Pagi";
} elseif ($hour >= 11 && $hour < 15) {
    $greeting = "Selamat Siang";
} elseif ($hour >= 15 && $hour < 18) {
    $greeting = "Selamat Sore";
} else {
    $greeting = "Selamat Malam";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Dashboard | Admin MI Polsri</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
</head>
<body style="background:#F1F5F9;">

<!-- ── Sidebar ── -->
<?php include __DIR__.'/_sidebar.php'; ?>

<!-- ── Main Content ── -->
<main class="admin-content">
    <!-- Topbar -->
    <div class="admin-topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button id="sidebarToggle" class="sidebar-toggle-btn">
                <i class="fas fa-bars"></i>
            </button>
            <h5 style="margin:0;font-size:1rem;font-weight:700;">Dashboard</h5>
        </div>
        <div style="display:flex;align-items:center;gap:14px;">
            <!-- Clock Widget -->
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
            <span style="font-size:0.85rem;color:#64748B;">
                👋 Halo, <strong><?= e($_SESSION['admin_user']) ?></strong>
            </span>
            <a href="logout.php"
               style="color:#E11D48;font-size:0.82rem;font-weight:600;
                      display:flex;align-items:center;gap:5px;text-decoration:none;">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

    <div class="admin-main">
        <!-- Flash -->
        <?php $flash = getFlash(); if ($flash): ?>
        <div class="alert alert-<?= $flash['type']==='success'?'success':'danger' ?> mb-4"
             style="border-radius:12px;">
            <?= e($flash['message']) ?>
        </div>
        <?php endif; ?>

        <!-- Welcome Banner Hero Card -->
        <div class="welcome-banner" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; background:linear-gradient(135deg, var(--clr-primary), var(--clr-primary-dark)); padding:28px; border-radius:16px; margin-bottom:32px;">
            <div class="welcome-text" style="flex:1; min-width:280px; margin-bottom:0;">
                <h4 style="color:white; margin-bottom:8px; font-weight:700;"><?= $greeting ?>, <?= e($_SESSION['admin_user']) ?>! 👋</h4>
                <p style="color:rgba(255,255,255,0.85); margin:0; font-size:0.875rem; line-height:1.5;">Selamat datang kembali di panel administrasi Program Studi Manajemen Informatika. Berikut adalah ringkasan performa dan statistik konten website Anda hari ini.</p>
            </div>
            <div style="flex-shrink:0;">
                <a href="backup.php?token=<?= generateCsrfToken() ?>" class="btn-primary-custom" style="background:rgba(255,255,255,0.15); color:white; border:1px solid rgba(255,255,255,0.25); padding:12px 20px; font-weight:600; font-size:0.85rem; display:inline-flex; align-items:center; gap:8px; border-radius:10px; transition:all var(--transition);" onmouseover="this.style.background='rgba(255,255,255,0.25)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(0)'">
                    <i class="fas fa-database"></i> Cadangan Database (.SQL)
                </a>
            </div>
        </div>

        <!-- ── Stat Widgets ── -->
        <div class="row g-4 mb-5">
            <?php
            $widgets = [
                ['Berita & Artikel', $stats['berita'],  'fas fa-newspaper',         'teal',  'berita.php'],
                ['Total Dosen',      $stats['dosen'],   'fas fa-chalkboard-teacher','blue',  'dosen.php'],
                ['Foto Galeri',      $stats['galeri'],  'fas fa-images',            'amber', 'galeri.php'],
                ['Pesan Masuk',      $stats['pesan'],   'fas fa-envelope',          'rose',  'pesan.php'],
                ['Prestasi Mhs',     $stats['mahasiswa'],'fas fa-trophy',           'indigo',  'prestasi.php'],
            ];
            foreach ($widgets as $w): ?>
            <div class="col-xl col-md-4 col-sm-6">
                <a href="<?= $w[4] ?>" class="h-100 d-block" style="text-decoration:none;">
                    <div class="stat-widget h-100" style="position:relative; display:flex; align-items:center; gap:16px;">
                        <div class="stat-widget-icon <?= $w[3] ?>">
                            <i class="<?= $w[2] ?>"></i>
                        </div>
                        <div style="flex:1;">
                            <div class="stat-widget-num" data-target="<?= $w[1] ?>">0</div>
                            <div class="stat-widget-label" style="margin:0;"><?= $w[0] ?></div>
                        </div>
                        <?php if ($w[0]==='Pesan Masuk' && $stats['pesan_baru']>0): ?>
                        <span style="position:absolute; top:12px; right:12px; background:#E11D48; color:white; font-size:0.68rem; padding:2px 8px; border-radius:50px; font-weight:700; box-shadow: 0 2px 8px rgba(225, 29, 72, 0.3);">
                            <?= $stats['pesan_baru'] ?> baru
                        </span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Row Grafik Statistik -->
        <div class="row g-4 mb-5">
            <!-- Grafik Distribusi Konten -->
            <div class="col-lg-6">
                <div class="admin-card" style="height:100%;">
                    <div class="admin-card-header" style="border-bottom:1px solid var(--clr-border);padding:20px 24px;">
                        <h5 style="margin:0;font-weight:700;font-size:0.95rem;color:var(--clr-primary);"><i class="fas fa-chart-pie" style="margin-right:8px;"></i>Proporsi Data Konten Website</h5>
                    </div>
                    <div class="admin-card-body" style="padding:24px;">
                        <div class="chart-container-custom">
                            <canvas id="proporsiKontenChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Statistik Pengunjung -->
            <div class="col-lg-6">
                <div class="admin-card" style="height:100%;">
                    <div class="admin-card-header" style="border-bottom:1px solid var(--clr-border);padding:20px 24px;">
                        <h5 style="margin:0;font-weight:700;font-size:0.95rem;color:var(--clr-primary);"><i class="fas fa-users" style="margin-right:8px;"></i>Statistik Pengunjung (7 Hari Terakhir)</h5>
                    </div>
                    <div class="admin-card-body" style="padding:24px;">
                        <div class="chart-container-custom">
                            <canvas id="pengunjungChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Pesan Terbaru -->
            <div class="col-lg-6">
                <div class="admin-card" style="height:100%;">
                    <div class="admin-card-header">
                        <h5>
                            <i class="fas fa-envelope me-2" style="color:var(--clr-primary);"></i>
                            Pesan Terbaru
                        </h5>
                        <a href="pesan.php" class="btn-admin-primary"
                           style="font-size:0.78rem;padding:6px 14px;">
                            Lihat Semua
                        </a>
                    </div>
                    <div style="max-height:360px; overflow-y:auto;">
                        <?php if (empty($pesanTerbaru)): ?>
                        <div style="text-align:center;padding:40px;color:#94A3B8;">
                            <i class="fas fa-inbox" style="font-size:2.5rem;margin-bottom:8px;display:block;"></i>
                            Belum ada pesan masuk
                        </div>
                        <?php else: ?>
                        <?php foreach ($pesanTerbaru as $p): ?>
                        <div style="padding:14px 24px;border-bottom:1px solid var(--clr-border);
                             display:flex;gap:12px;align-items:flex-start;">
                            <div style="width:38px;height:38px;background:var(--clr-primary-light);
                                 border-radius:10px;display:flex;align-items:center;
                                 justify-content:center;color:var(--clr-primary);font-weight:700;
                                 font-size:0.95rem;flex-shrink:0;">
                                <?= strtoupper(mb_substr($p['nama'], 0, 1)) ?>
                            </div>
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:600;font-size:0.875rem;margin-bottom:2px;">
                                    <?= e($p['nama']) ?>
                                </div>
                                <div style="font-size:0.78rem;color:#64748B;margin-bottom:4px;">
                                    <?= e($p['email']) ?>
                                </div>
                                <div style="font-size:0.82rem;color:var(--clr-text-light);
                                     white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    <?= e(truncate($p['pesan'], 55)) ?>
                                </div>
                            </div>
                            <div style="font-size:0.72rem;color:#94A3B8;white-space:nowrap;">
                                <?= formatTanggal($p['created_at']) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Berita Terbaru -->
            <div class="col-lg-6">
                <div class="admin-card" style="height:100%;">
                    <div class="admin-card-header">
                        <h5>
                            <i class="fas fa-newspaper me-2" style="color:var(--clr-primary);"></i>
                            Berita Terbaru
                        </h5>
                        <a href="berita_tambah.php" class="btn-admin-primary"
                           style="font-size:0.78rem;padding:6px 14px;">
                            <i class="fas fa-plus me-1"></i>Tambah
                        </a>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="table-admin">
                            <thead>
                                <tr>
                                    <th>Judul</th>
                                    <th>Kat.</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($beritaTerbaru)): ?>
                                <tr>
                                    <td colspan="5" style="text-align:center;
                                        color:#94A3B8;padding:30px;">
                                        Belum ada berita
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($beritaTerbaru as $b): ?>
                                <tr>
                                    <td style="max-width:160px;white-space:nowrap;
                                        overflow:hidden;text-overflow:ellipsis;
                                        font-weight:600;font-size:0.875rem;">
                                        <?= e($b['judul']) ?>
                                    </td>
                                    <td>
                                        <span class="badge-modern badge-modern-secondary">
                                            <?= ucfirst(e($b['kategori'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge-modern badge-modern-<?= $b['status'] === 'publish' ? 'success' : 'warning' ?>">
                                            <?= ucfirst($b['status']) ?>
                                        </span>
                                    </td>
                                    <td style="font-size:0.78rem;color:#64748B;">
                                        <?= formatTanggal($b['created_at']) ?>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:5px;">
                                            <a href="berita_edit.php?id=<?= $b['id'] ?>"
                                               style="background:#ECFDF5;color:#059669;
                                                      padding:5px 9px;border-radius:6px;
                                                      font-size:0.78rem;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="berita_delete.php?id=<?= $b['id'] ?>&token=<?= generateCsrfToken() ?>"
                                               onclick="return confirm('Yakin hapus?')"
                                               style="background:#FEF2F2;color:#DC2626;
                                                      padding:5px 9px;border-radius:6px;
                                                      font-size:0.78rem;">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row Log Aktivitas & Catatan Kilat -->
        <div class="row g-4 mt-2">
            <!-- Log Aktivitas Admin -->
            <div class="col-lg-6">
                <div class="admin-card" style="height:100%;">
                    <div class="admin-card-header d-flex justify-content-between align-items-center">
                        <h5 class="m-0 d-flex align-items-center">
                            <i class="fas fa-history me-2" style="color:var(--clr-primary);"></i>
                            Log Aktivitas Admin
                            <span class="live-pulse-dot ms-2" title="Live Monitoring"></span>
                        </h5>
                    </div>
                    <div style="padding:24px; max-height:360px; overflow-y:auto;">
                        <?php if (empty($logAktivitas)): ?>
                        <div style="text-align:center;color:#94A3B8;padding:30px;">
                            Belum ada aktivitas tercatat
                        </div>
                        <?php else: ?>
                        <div class="activity-timeline">
                            <?php foreach ($logAktivitas as $log): 
                                $badgeClass = 'badge-activity-info';
                                $iconClass = 'fa-info-circle';
                                if ($log['aksi'] === 'TAMBAH') {
                                    $badgeClass = 'badge-activity-success';
                                    $iconClass = 'fa-plus-circle';
                                } elseif ($log['aksi'] === 'EDIT') {
                                    $badgeClass = 'badge-activity-warning';
                                    $iconClass = 'fa-pen';
                                } elseif ($log['aksi'] === 'HAPUS') {
                                    $badgeClass = 'badge-activity-danger';
                                    $iconClass = 'fa-trash-alt';
                                }
                            ?>
                            <div class="timeline-item">
                                <div class="timeline-icon <?= $badgeClass ?>">
                                    <i class="fas <?= $iconClass ?>"></i>
                                </div>
                                <div class="timeline-content">
                                    <div style="font-weight:600;font-size:0.875rem;margin-bottom:4px;">
                                        <?= e($log['rincian']) ?>
                                    </div>
                                    <div style="font-size:0.75rem;color:#94A3B8;display:flex;justify-content:space-between;align-items:center;">
                                        <span>Oleh: <strong style="color:var(--clr-text-light);"><?= e($log['admin_user']) ?></strong></span>
                                        <span><?= date('H:i - d M Y', strtotime($log['created_at'])) ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Catatan Kilat Admin -->
            <div class="col-lg-6">
                <div class="admin-card" style="height:100%;">
                    <div class="admin-card-header">
                        <h5>
                            <i class="fas fa-sticky-note me-2" style="color:#EAB308;"></i>
                            Catatan Kilat Admin
                        </h5>
                    </div>
                    <div style="padding:24px; display:flex; flex-direction:column; justify-content:space-between; height:calc(100% - 62px);">
                        <!-- Sticky Notes Container -->
                        <div id="memoContainer" style="display:grid; grid-template-columns:1fr 1fr; gap:12px; max-height:260px; overflow-y:auto; margin-bottom:16px; align-content:start;">
                            <?php if (empty($memoCatatan)): ?>
                            <div id="noMemoMsg" style="grid-column: span 2; text-align:center; color:#94A3B8; padding:30px;">
                                Belum ada catatan. Tambahkan di bawah!
                            </div>
                            <?php else: ?>
                            <?php foreach ($memoCatatan as $memo): 
                                $memoBg = '#FEF08A'; // yellow default
                                $memoText = '#713F12';
                                $memoBorder = '#FDE047';
                                if ($memo['warna'] === 'blue') {
                                    $memoBg = '#DBEAFE'; $memoText = '#1E3A8A'; $memoBorder = '#BFDBFE';
                                } elseif ($memo['warna'] === 'green') {
                                    $memoBg = '#D1FAE5'; $memoText = '#065F46'; $memoBorder = '#A7F3D0';
                                } elseif ($memo['warna'] === 'pink') {
                                    $memoBg = '#FCE7F3'; $memoText = '#9D174D'; $memoBorder = '#FBCFE8';
                                }
                            ?>
                            <div class="sticky-note" data-id="<?= $memo['id'] ?>" style="background:<?= $memoBg ?>; color:<?= $memoText ?>; border:1px solid <?= $memoBorder ?>; border-radius:10px; padding:12px 14px; position:relative; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); min-height:85px; display:flex; flex-direction:column; justify-content:space-between;">
                                <button class="delete-memo-btn" onclick="deleteMemo(<?= $memo['id'] ?>)" style="position:absolute; top:6px; right:8px; background:none; border:none; color:inherit; opacity:0.5; font-size:0.75rem; cursor:pointer;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.5">
                                    <i class="fas fa-times"></i>
                                </button>
                                <div style="font-size:0.82rem; font-weight:500; line-height:1.4; word-break:break-word; margin-right:10px; flex:1;">
                                    <?= nl2br(e($memo['isi'])) ?>
                                </div>
                                <div style="font-size:0.65rem; opacity:0.6; text-align:right; margin-top:6px;">
                                    <?= date('H:i, j M', strtotime($memo['created_at'])) ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Add Sticky Note Form -->
                        <form id="addMemoForm" onsubmit="addMemo(event)">
                            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                            <div style="display:flex; gap:8px; align-items:center;">
                                <input type="text" id="memoInput" placeholder="Tulis pengingat baru..." required class="form-control-custom" style="font-size:0.85rem; padding:8px 12px; border-radius:8px; flex:1; height:38px;">
                                <select id="memoWarna" class="form-admin-select" style="width:85px; height:38px; padding:0 8px; border-radius:8px; font-size:0.8rem; margin:0; flex-shrink:0;">
                                    <option value="yellow">Kuning</option>
                                    <option value="blue">Biru</option>
                                    <option value="green">Hijau</option>
                                    <option value="pink">Pink</option>
                                </select>
                                <button type="submit" class="btn-admin-primary" style="height:38px; width:38px; padding:0; display:flex; align-items:center; justify-content:center; border-radius:8px; flex-shrink:0;">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-4">
            <div style="background:linear-gradient(135deg,var(--clr-primary),var(--clr-primary-dark));
                 border-radius:var(--radius-lg);padding:24px 28px;
                 display:flex;align-items:center;justify-content:space-between;
                 flex-wrap:wrap;gap:16px;">
                <div style="color:white;">
                    <h5 style="color:white;margin-bottom:3px;">Aksi Cepat</h5>
                    <p style="color:rgba(255,255,255,.75);margin:0;font-size:0.85rem;">
                        Kelola konten website langsung dari sini
                    </p>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap;">
                    <a href="berita_tambah.php"
                       style="background:rgba(255,255,255,.15);color:white;
                              padding:10px 18px;border-radius:var(--radius-md);
                              font-size:0.85rem;font-weight:600;
                              border:1px solid rgba(255,255,255,.3);
                              display:inline-flex;align-items:center;gap:6px;
                              text-decoration:none;">
                        <i class="fas fa-plus"></i> Tambah Berita
                    </a>
                    <a href="dosen.php?mode=tambah"
                       style="background:rgba(255,255,255,.15);color:white;
                              padding:10px 18px;border-radius:var(--radius-md);
                              font-size:0.85rem;font-weight:600;
                              border:1px solid rgba(255,255,255,.3);
                              display:inline-flex;align-items:center;gap:6px;
                              text-decoration:none;">
                        <i class="fas fa-user-plus"></i> Tambah Dosen
                    </a>
                    <a href="galeri.php?mode=tambah"
                       style="background:rgba(255,255,255,.15);color:white;
                              padding:10px 18px;border-radius:var(--radius-md);
                              font-size:0.85rem;font-weight:600;
                              border:1px solid rgba(255,255,255,.3);
                              display:inline-flex;align-items:center;gap:6px;
                              text-decoration:none;">
                        <i class="fas fa-image"></i> Tambah Galeri
                    </a>
                    <a href="prestasi.php?mode=tambah"
                       style="background:rgba(255,255,255,.15);color:white;
                              padding:10px 18px;border-radius:var(--radius-md);
                              font-size:0.85rem;font-weight:600;
                              border:1px solid rgba(255,255,255,.3);
                              display:inline-flex;align-items:center;gap:6px;
                              text-decoration:none;">
                        <i class="fas fa-trophy"></i> Tambah Prestasi
                    </a>
                    <a href="<?= APP_URL ?>/index.php" target="_blank"
                       style="background:rgba(255,255,255,.15);color:white;
                              padding:10px 18px;border-radius:var(--radius-md);
                              font-size:0.85rem;font-weight:600;
                              border:1px solid rgba(255,255,255,.3);
                              display:inline-flex;align-items:center;gap:6px;
                              text-decoration:none;">
                        <i class="fas fa-external-link-alt"></i> Lihat Website
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Register custom plugin to draw total count in the center of the doughnut chart
    const centerTextPlugin = {
        id: 'centerText',
        beforeDraw: function(chart) {
            if (chart.config.type !== 'doughnut') return;
            const { ctx, chartArea } = chart;
            if (!chartArea) return;
            ctx.save();
            
            const dataset = chart.data.datasets[0];
            const total = dataset.data.reduce((sum, val) => sum + val, 0);
            
            const centerX = (chartArea.left + chartArea.right) / 2;
            const centerY = (chartArea.top + chartArea.bottom) / 2;
            
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            
            // Draw "TOTAL DATA" label
            ctx.font = "700 0.72rem 'Plus Jakarta Sans', sans-serif";
            ctx.fillStyle = '#94A3B8';
            ctx.fillText('TOTAL DATA', centerX, centerY - 10);
            
            // Draw the actual number sum
            ctx.font = "800 1.85rem 'Plus Jakarta Sans', sans-serif";
            ctx.fillStyle = '#0F172A';
            ctx.fillText(total, centerX, centerY + 12);
            ctx.restore();
        }
    };
    Chart.register(centerTextPlugin);

    // 1. Chart Proporsi Konten Website (Doughnut Chart)
    const proporsiCtx = document.getElementById('proporsiKontenChart');
    if (proporsiCtx) {
        new Chart(proporsiCtx, {
            type: 'doughnut',
            data: {
                labels: ['Berita', 'Dosen', 'Galeri', 'Pesan', 'Prestasi'],
                datasets: [{
                    data: [
                        <?= (int)$stats['berita'] ?>,
                        <?= (int)$stats['dosen'] ?>,
                        <?= (int)$stats['galeri'] ?>,
                        <?= (int)$stats['pesan'] ?>,
                        <?= (int)$stats['mahasiswa'] ?>
                    ],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.85)',   // Blue
                        'rgba(16, 185, 129, 0.85)',   // Emerald
                        'rgba(245, 158, 11, 0.85)',   // Amber
                        'rgba(244, 63, 94, 0.85)',    // Rose
                        'rgba(99, 102, 241, 0.85)'    // Indigo
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 3,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11, weight: '600' },
                            color: '#475569',
                            usePointStyle: true,
                            padding: 18
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { family: "'Plus Jakarta Sans', sans-serif", weight: 'bold', size: 12 },
                        bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
                        padding: 12,
                        cornerRadius: 10,
                        boxPadding: 6,
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1
                    }
                },
                cutout: '76%'
            }
        });
    }

    // 2. Chart Statistik Pengunjung (Gradient Area Line Chart)
    const pengunjungCtx = document.getElementById('pengunjungChart');
    if (pengunjungCtx) {
        const ctx = pengunjungCtx.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.00)');

        const labels = <?= json_encode(array_keys($kunjunganChartData)) ?>;
        const counts = <?= json_encode(array_values($kunjunganChartData)) ?>;
        
        new Chart(pengunjungCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pengunjung Unik',
                    data: counts,
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#10B981',
                    borderWidth: 3,
                    tension: 0.38,
                    pointBackgroundColor: '#10B981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0F172A',
                        titleFont: { family: "'Plus Jakarta Sans', sans-serif", weight: 'bold', size: 12 },
                        bodyFont: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
                        padding: 12,
                        cornerRadius: 10,
                        boxPadding: 6,
                        borderColor: 'rgba(255,255,255,0.1)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(226, 232, 240, 0.5)', drawBorder: false },
                        ticks: {
                            stepSize: 1,
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                            color: '#64748b'
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: "'Plus Jakarta Sans', sans-serif", size: 11 },
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    }
});
</script>

<script>
function addMemo(e) {
    e.preventDefault();
    const input = document.getElementById('memoInput');
    const warna = document.getElementById('memoWarna');
    const content = input.value.trim();
    if (!content) return;
    
    const formData = new FormData();
    formData.append('memo_action', 'add');
    formData.append('isi', content);
    formData.append('warna', warna.value);
    formData.append('csrf_token', '<?= generateCsrfToken() ?>');
    
    fetch('dashboard.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            input.value = '';
            
            const noMemo = document.getElementById('noMemoMsg');
            if (noMemo) noMemo.remove();
            
            let memoBg = '#FEF08A';
            let memoText = '#713F12';
            let memoBorder = '#FDE047';
            if (data.warna === 'blue') {
                memoBg = '#DBEAFE'; memoText = '#1E3A8A'; memoBorder = '#BFDBFE';
            } else if (data.warna === 'green') {
                memoBg = '#D1FAE5'; memoText = '#065F46'; memoBorder = '#A7F3D0';
            } else if (data.warna === 'pink') {
                memoBg = '#FCE7F3'; memoText = '#9D174D'; memoBorder = '#FBCFE8';
            }
            
            const memoContainer = document.getElementById('memoContainer');
            const newNote = document.createElement('div');
            newNote.className = 'sticky-note';
            newNote.dataset.id = data.id;
            newNote.style = `background:${memoBg}; color:${memoText}; border:1px solid ${memoBorder}; border-radius:10px; padding:12px 14px; position:relative; box-shadow:0 4px 6px -1px rgba(0,0,0,0.05); min-height:85px; display:flex; flex-direction:column; justify-content:space-between;`;
            
            newNote.innerHTML = `
                <button class="delete-memo-btn" onclick="deleteMemo(${data.id})" style="position:absolute; top:6px; right:8px; background:none; border:none; color:inherit; opacity:0.5; font-size:0.75rem; cursor:pointer;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.5">
                    <i class="fas fa-times"></i>
                </button>
                <div style="font-size:0.82rem; font-weight:500; line-height:1.4; word-break:break-word; margin-right:10px; flex:1;">
                    ${data.isi.replace(/\n/g, '<br>')}
                </div>
                <div style="font-size:0.65rem; opacity:0.6; text-align:right; margin-top:6px;">
                    Baru saja
                </div>
            `;
            
            memoContainer.insertBefore(newNote, memoContainer.firstChild);
            if (typeof showToast === 'function') {
                showToast('Catatan berhasil ditambahkan.', 'success');
            }
        } else {
            if (typeof showToast === 'function') showToast(data.message || 'Gagal menambahkan catatan.', 'error');
        }
    })
    .catch(err => {
        if (typeof showToast === 'function') showToast('Koneksi bermasalah.', 'error');
    });
}

function deleteMemo(id) {
    if (!confirm('Hapus catatan ini?')) return;
    
    const formData = new FormData();
    formData.append('memo_action', 'delete');
    formData.append('id', id);
    formData.append('csrf_token', '<?= generateCsrfToken() ?>');
    
    fetch('dashboard.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const note = document.querySelector(`.sticky-note[data-id="${id}"]`);
            if (note) {
                note.remove();
                
                const memoContainer = document.getElementById('memoContainer');
                if (memoContainer.querySelectorAll('.sticky-note').length === 0) {
                    memoContainer.innerHTML = `
                        <div id="noMemoMsg" style="grid-column: span 2; text-align:center; color:#94A3B8; padding:30px;">
                            Belum ada catatan. Tambahkan di bawah!
                        </div>
                    `;
                }
            }
            if (typeof showToast === 'function') showToast('Catatan berhasil dihapus.', 'success');
        } else {
            if (typeof showToast === 'function') showToast('Gagal menghapus catatan.', 'error');
        }
    })
    .catch(err => {
        if (typeof showToast === 'function') showToast('Koneksi bermasalah.', 'error');
    });
}

// Dynamic count-up effect for dashboard stat widgets
document.addEventListener('DOMContentLoaded', () => {
    const widgets = document.querySelectorAll('.stat-widget-num');
    
    widgets.forEach(w => {
        const target = parseInt(w.dataset.target || '0', 10);
        if (target === 0) {
            w.textContent = '0';
            return;
        }
        
        const duration = 1200; // 1.2 seconds for snappy feel
        const startTime = performance.now();
        
        function easeOutQuad(t) {
            return t * (2 - t);
        }
        
        function updateCount(currentTime) {
            const elapsedTime = currentTime - startTime;
            const progress = Math.min(elapsedTime / duration, 1);
            const currentVal = Math.floor(easeOutQuad(progress) * target);
            
            w.textContent = currentVal.toLocaleString('id-ID');
            
            if (progress < 1) {
                requestAnimationFrame(updateCount);
            } else {
                w.textContent = target.toLocaleString('id-ID');
            }
        }
        
        requestAnimationFrame(updateCount);
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
