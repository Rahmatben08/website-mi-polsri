<?php
// admin/prestasi.php — Kelola Prestasi Mahasiswa
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db     = getDB();
$errors = [];
$mode   = $_GET['mode'] ?? 'list';
$editId = (int)($_GET['id'] ?? 0);

// DELETE
if (isset($_GET['delete']) && validateCsrfToken($_GET['token'] ?? '')) {
    $delId = (int)$_GET['delete'];
    $sName = $db->prepare("SELECT nama, prestasi FROM mahasiswa_prestasi WHERE id=?");
    $sName->execute([$delId]);
    $row = $sName->fetch();
    $db->prepare("DELETE FROM mahasiswa_prestasi WHERE id=?")->execute([$delId]);
    if ($row) {
        logAktivitas('HAPUS', "Menghapus prestasi mahasiswa: " . $row['nama'] . " (" . $row['prestasi'] . ")");
    }
    setFlash('success', 'Prestasi mahasiswa berhasil dihapus.');
    redirect(APP_URL.'/admin/prestasi.php');
}

// TAMBAH
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'tambah') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token tidak valid.';
    } else {
        $nama     = trim($_POST['nama'] ?? '');
        $nim      = trim($_POST['nim'] ?? '');
        $prestasi = trim($_POST['prestasi'] ?? '');
        $tahun    = (int)($_POST['tahun'] ?? date('Y'));
        $foto     = uploadImage($_FILES['foto_file'] ?? [], $errors, 'default-mahasiswa.jpg', 'mhs');

        if (empty($nama))     $errors[] = 'Nama mahasiswa wajib diisi.';
        if (empty($nim))      $errors[] = 'NIM wajib diisi.';
        if (empty($prestasi)) $errors[] = 'Deskripsi prestasi wajib diisi.';
        if ($tahun < 2000 || $tahun > 2100) $errors[] = 'Tahun tidak valid.';

        if (empty($errors)) {
            $db->prepare("INSERT INTO mahasiswa_prestasi (nama, nim, prestasi, tahun, foto) VALUES (?, ?, ?, ?, ?)")
               ->execute([$nama, $nim, $prestasi, $tahun, $foto]);
            logAktivitas('TAMBAH', "Menambahkan prestasi mahasiswa: " . $nama . " - " . $prestasi);
            setFlash('success', 'Prestasi mahasiswa berhasil ditambahkan.');
            redirect(APP_URL.'/admin/prestasi.php');
        } else {
            $mode = 'tambah';
        }
    }
}

// EDIT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'edit') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token tidak valid.';
    } else {
        $id       = (int)$_POST['id'];
        
        $mhsQuery = $db->prepare("SELECT foto FROM mahasiswa_prestasi WHERE id=?");
        $mhsQuery->execute([$id]);
        $oldFoto  = $mhsQuery->fetchColumn() ?: 'default-mahasiswa.jpg';
        
        $nama     = trim($_POST['nama'] ?? '');
        $nim      = trim($_POST['nim'] ?? '');
        $prestasi = trim($_POST['prestasi'] ?? '');
        $tahun    = (int)($_POST['tahun'] ?? date('Y'));
        $foto     = uploadImage($_FILES['foto_file'] ?? [], $errors, $oldFoto, 'mhs');

        if (empty($nama))     $errors[] = 'Nama mahasiswa wajib diisi.';
        if (empty($nim))      $errors[] = 'NIM wajib diisi.';
        if (empty($prestasi)) $errors[] = 'Deskripsi prestasi wajib diisi.';
        if ($tahun < 2000 || $tahun > 2100) $errors[] = 'Tahun tidak valid.';

        if (empty($errors)) {
            $db->prepare("UPDATE mahasiswa_prestasi SET nama=?, nim=?, prestasi=?, tahun=?, foto=? WHERE id=?")
               ->execute([$nama, $nim, $prestasi, $tahun, $foto, $id]);
            logAktivitas('EDIT', "Memperbarui prestasi mahasiswa: " . $nama . " - " . $prestasi);
            setFlash('success', 'Prestasi mahasiswa berhasil diperbarui.');
            redirect(APP_URL.'/admin/prestasi.php');
        } else {
            $mode   = 'edit';
            $editId = $id;
        }
    }
}

$prestasiList = $db->query("SELECT * FROM mahasiswa_prestasi ORDER BY tahun DESC, id DESC")->fetchAll();
$editData     = null;
if ($editId) {
    $s = $db->prepare("SELECT * FROM mahasiswa_prestasi WHERE id=?");
    $s->execute([$editId]);
    $editData = $s->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Kelola Prestasi | Admin MI</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
</head>
<body style="background:#F8FAFC;">

<?php include __DIR__.'/_sidebar.php'; ?>

<main class="admin-content">
    <div class="admin-topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button id="sidebarToggle" class="sidebar-toggle-btn">
                <i class="fas fa-bars"></i>
            </button>
            <h5 style="margin:0;font-size:1rem;font-weight:700;">Kelola Prestasi Mahasiswa</h5>
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
            <a href="?mode=tambah" class="btn-admin-primary"><i class="fas fa-plus"></i> Tambah Prestasi</a>
        </div>
    </div>

    <div class="admin-main">
        <?php $flash = getFlash(); if ($flash): ?>
        <div class="alert alert-<?= $flash['type']==='success'?'success':'danger' ?> mb-4"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-4">
            <ul style="margin:0;padding-left:16px;">
                <?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <?php if ($mode==='tambah' || $mode==='edit'): ?>
            <div class="col-lg-4">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h5><?= $mode==='edit'?'Edit Prestasi':'Tambah Prestasi Baru' ?></h5>
                    </div>
                    <div class="admin-card-body" style="padding:24px;">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                            <input type="hidden" name="action" value="<?= $mode ?>">
                            <?php if ($mode==='edit'): ?>
                            <input type="hidden" name="id" value="<?= $editId ?>">
                            <?php endif; ?>

                            <div style="margin-bottom:14px;">
                                <label class="form-label-custom">Nama Mahasiswa *</label>
                                <input type="text" name="nama" class="form-control-custom" value="<?= $editData?e($editData['nama']):'' ?>" required placeholder="Nama lengkap mahasiswa...">
                            </div>

                            <div style="margin-bottom:14px;">
                                <label class="form-label-custom">NIM *</label>
                                <input type="text" name="nim" class="form-control-custom" value="<?= $editData?e($editData['nim']):'' ?>" required placeholder="Nomor Induk Mahasiswa...">
                            </div>

                            <div style="margin-bottom:14px;">
                                <label class="form-label-custom">Prestasi *</label>
                                <input type="text" name="prestasi" class="form-control-custom" value="<?= $editData?e($editData['prestasi']):'' ?>" required placeholder="Contoh: Juara 1 Hackathon Nasional 2024">
                            </div>

                            <div style="margin-bottom:14px;">
                                <label class="form-label-custom">Tahun *</label>
                                <input type="number" name="tahun" class="form-control-custom" value="<?= $editData?e($editData['tahun']):date('Y') ?>" required min="2000" max="2100">
                            </div>

                            <div style="margin-bottom:20px;">
                                <label class="form-label-custom">Unggah Foto Mahasiswa</label>
                                <input type="file" name="foto_file" class="form-control-custom" accept="image/*">
                                <div style="font-size:0.73rem;color:#94A3B8;margin-top:4px;margin-bottom:8px;">Format: JPG, JPEG, PNG, WEBP, GIF. Maksimal 2MB.</div>
                                <?php if ($editData && $editData['foto'] !== 'default-mahasiswa.jpg'): ?>
                                <div style="font-size:0.75rem;color:#64748B;">Foto saat ini: <code><?= e($editData['foto']) ?></code></div>
                                <?php endif; ?>
                            </div>

                            <div style="display:flex;gap:8px;">
                                <button type="submit" class="btn-admin-primary" style="flex:1;justify-content:center;"><i class="fas fa-save"></i> Simpan</button>
                                <a href="prestasi.php" style="padding:10px 14px;background:#F1F5F9;color:#64748B;border-radius:var(--radius-md);font-size:0.875rem;font-weight:600;display:flex;align-items:center;text-decoration:none;">Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
            <?php else: ?>
            <div class="col-12">
            <?php endif; ?>

                <div class="admin-card">
                    <div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                        <h5 style="margin:0;">Daftar Prestasi Mahasiswa <span style="background:#E2E8F0;color:#64748B;font-size:0.75rem;padding:3px 10px;border-radius:50px;margin-left:8px;"><?= count($prestasiList) ?></span></h5>
                        <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap; width:100%; max-width:400px; justify-content:flex-end;">
                            <div style="position:relative; width:100%; max-width:200px; margin:0;">
                                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94A3B8; font-size:0.8rem;"></i>
                                <input type="text" id="liveSearchInput" placeholder="Cari prestasi..." class="form-control-custom" style="padding-left:32px; font-size:0.8rem; height:34px; border-radius:6px; margin:0; width:100%;">
                            </div>
                            <button onclick="exportTableToCSV('prestasiTable', 'prestasi-mahasiswa.csv')" class="btn-admin-primary" style="font-size:0.78rem;padding:6px 14px;background:#10B981;border-color:#10B981; display:flex; align-items:center; gap:6px; margin:0;">
                                <i class="fas fa-file-csv"></i> Ekspor CSV
                            </button>
                        </div>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="table-admin" id="prestasiTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th>Nama &amp; NIM</th>
                                    <th>Prestasi</th>
                                    <th>Tahun</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($prestasiList)): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;padding:40px;color:#94A3B8;">
                                        Belum ada prestasi mahasiswa. <a href="?mode=tambah">Tambah sekarang</a>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($prestasiList as $i=>$p): ?>
                                <tr>
                                    <td style="color:#94A3B8;font-size:0.8rem;"><?= $i+1 ?></td>
                                    <td>
                                        <div style="width:48px;height:48px;border-radius:50%;overflow:hidden;background:#F1F5F9;border:1px solid #E2E8F0;display:flex;align-items:center;justify-content:center;">
                                            <?php if (!empty($p['foto']) && $p['foto'] !== 'default-mahasiswa.jpg'): ?>
                                            <img src="<?= APP_URL ?>/assets/images/<?= e($p['foto']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" onerror="this.outerHTML='<i class=\'fas fa-user-graduate\' style=\'color:#64748B;\'></i>'">
                                            <?php else: ?>
                                            <i class="fas fa-user-graduate" style="color:#64748B;"></i>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight:600;font-size:0.875rem;"><?= e($p['nama']) ?></div>
                                        <div style="font-size:0.75rem;color:#64748B;">NIM: <?= e($p['nim']) ?></div>
                                    </td>
                                    <td style="font-size:0.85rem;color:#334155;max-width:300px;word-wrap:break-word;white-space:normal;"><?= e($p['prestasi']) ?></td>
                                    <td><span class="badge-modern badge-modern-info"><?= e($p['tahun']) ?></span></td>
                                    <td>
                                        <div style="display:flex;gap:6px;">
                                            <a href="?mode=edit&id=<?= $p['id'] ?>" style="background:#ECFDF5;color:#059669;padding:6px 10px;border-radius:6px;font-size:0.78rem;"><i class="fas fa-edit"></i></a>
                                            <a href="?delete=<?= $p['id'] ?>&token=<?= generateCsrfToken() ?>" onclick="return confirm('Hapus prestasi mahasiswa ini?')" style="background:#FEF2F2;color:#DC2626;padding:6px 10px;border-radius:6px;font-size:0.78rem;"><i class="fas fa-trash"></i></a>
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
</body>
</html>
