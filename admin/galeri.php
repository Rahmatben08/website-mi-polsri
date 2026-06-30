<?php
// admin/galeri.php — Kelola Galeri Foto
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db     = getDB();
$errors = [];
$mode   = $_GET['mode'] ?? 'list';
$editId = (int)($_GET['id'] ?? 0);

// DELETE
if (isset($_GET['delete']) && validateCsrfToken($_GET['token'] ?? '')) {
    $delId = (int)$_GET['delete'];
    $sName = $db->prepare("SELECT judul FROM galeri WHERE id=?");
    $sName->execute([$delId]);
    $title = $sName->fetchColumn();
    $db->prepare("DELETE FROM galeri WHERE id=?")->execute([$delId]);
    if ($title) {
        logAktivitas('HAPUS', "Menghapus foto galeri: " . $title);
    }
    setFlash('success', 'Foto galeri berhasil dihapus.');
    redirect(APP_URL.'/admin/galeri.php');
}

// TAMBAH
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'tambah') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token tidak valid.';
    } else {
        $judul     = trim($_POST['judul'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $gambar    = uploadImage($_FILES['gambar_file'] ?? [], $errors, '', 'galeri');

        if (empty($judul))  $errors[] = 'Judul wajib diisi.';
        if (empty($gambar)) $errors[] = 'Gambar wajib diunggah.';

        if (empty($errors)) {
            $db->prepare("INSERT INTO galeri (judul, deskripsi, gambar) VALUES (?, ?, ?)")
               ->execute([$judul, $deskripsi, $gambar]);
            logAktivitas('TAMBAH', "Menambahkan foto galeri: " . $judul);
            setFlash('success', 'Foto galeri berhasil ditambahkan.');
            redirect(APP_URL.'/admin/galeri.php');
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
        $id        = (int)$_POST['id'];
        
        $galeriQuery = $db->prepare("SELECT gambar FROM galeri WHERE id=?");
        $galeriQuery->execute([$id]);
        $oldGambar = $galeriQuery->fetchColumn() ?: '';
        
        $judul     = trim($_POST['judul'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $gambar    = uploadImage($_FILES['gambar_file'] ?? [], $errors, $oldGambar, 'galeri');

        if (empty($judul))  $errors[] = 'Judul wajib diisi.';
        if (empty($gambar)) $errors[] = 'Gambar tidak boleh kosong.';

        if (empty($errors)) {
            $db->prepare("UPDATE galeri SET judul=?, deskripsi=?, gambar=? WHERE id=?")
               ->execute([$judul, $deskripsi, $gambar, $id]);
            logAktivitas('EDIT', "Memperbarui foto galeri: " . $judul);
            setFlash('success', 'Foto galeri berhasil diperbarui.');
            redirect(APP_URL.'/admin/galeri.php');
        } else {
            $mode   = 'edit';
            $editId = $id;
        }
    }
}

$galeriList = $db->query("SELECT * FROM galeri ORDER BY created_at DESC")->fetchAll();
$editData   = null;
if ($editId) {
    $s = $db->prepare("SELECT * FROM galeri WHERE id=?");
    $s->execute([$editId]);
    $editData = $s->fetch();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Kelola Galeri | Admin MI</title>
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
            <h5 style="margin:0;font-size:1rem;font-weight:700;">Kelola Galeri Foto</h5>
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
            <a href="?mode=tambah" class="btn-admin-primary"><i class="fas fa-plus"></i> Tambah Foto</a>
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
                        <h5><?= $mode==='edit'?'Edit Foto Galeri':'Tambah Foto Baru' ?></h5>
                    </div>
                    <div class="admin-card-body" style="padding:24px;">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                            <input type="hidden" name="action" value="<?= $mode ?>">
                            <?php if ($mode==='edit'): ?>
                            <input type="hidden" name="id" value="<?= $editId ?>">
                            <?php endif; ?>

                            <div style="margin-bottom:14px;">
                                <label class="form-label-custom">Judul Foto *</label>
                                <input type="text" name="judul" class="form-control-custom" value="<?= $editData?e($editData['judul']):'' ?>" required placeholder="Masukkan judul foto...">
                            </div>

                            <div style="margin-bottom:14px;">
                                <label class="form-label-custom">Deskripsi Singkat</label>
                                <textarea name="deskripsi" class="form-control-custom" rows="4" placeholder="Masukkan deskripsi singkat foto (opsional)..."><?= $editData?e($editData['deskripsi']??''):'' ?></textarea>
                            </div>

                             <div style="margin-bottom:20px;">
                                 <label class="form-label-custom">Unggah File Gambar *</label>
                                 <input type="file" name="gambar_file" class="form-control-custom" accept="image/*" <?= $mode === 'tambah' ? 'required' : '' ?>>
                                 <div style="font-size:0.73rem;color:#94A3B8;margin-top:4px;margin-bottom:8px;">Format: JPG, JPEG, PNG, WEBP, GIF. Maksimal 2MB.</div>
                                 <?php if ($editData): ?>
                                 <div style="font-size:0.75rem;color:#64748B;">Gambar saat ini: <code><?= e($editData['gambar']) ?></code></div>
                                 <?php endif; ?>
                             </div>

                            <div style="display:flex;gap:8px;">
                                <button type="submit" class="btn-admin-primary" style="flex:1;justify-content:center;"><i class="fas fa-save"></i> Simpan</button>
                                <a href="galeri.php" style="padding:10px 14px;background:#F1F5F9;color:#64748B;border-radius:var(--radius-md);font-size:0.875rem;font-weight:600;display:flex;align-items:center;text-decoration:none;">Batal</a>
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
                    <div class="admin-card-header">
                        <h5>Daftar Foto Galeri <span style="background:#E2E8F0;color:#64748B;font-size:0.75rem;padding:3px 10px;border-radius:50px;margin-left:8px;"><?= count($galeriList) ?></span></h5>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="table-admin">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gambar</th>
                                    <th>Judul &amp; Deskripsi</th>
                                    <th>File</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($galeriList)): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center;padding:40px;color:#94A3B8;">
                                        Belum ada foto galeri. <a href="?mode=tambah">Tambah sekarang</a>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($galeriList as $i=>$g): ?>
                                <tr>
                                    <td style="color:#94A3B8;font-size:0.8rem;"><?= $i+1 ?></td>
                                    <td>
                                        <div style="width:64px;height:48px;border-radius:6px;overflow:hidden;background:#F1F5F9;border:1px solid #E2E8F0;">
                                            <img src="<?= APP_URL ?>/assets/images/<?= e($g['gambar']) ?>" alt="" style="width:100%;height:100%;object-fit:cover;" onerror="this.src='https://placehold.co/120x90/DCEBFA/003366?text=Error'">
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-weight:600;font-size:0.875rem;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($g['judul']) ?></div>
                                        <div style="font-size:0.75rem;color:#64748B;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($g['deskripsi'] ?? '-') ?></div>
                                    </td>
                                    <td style="font-size:0.78rem;color:#64748B;"><code><?= e($g['gambar']) ?></code></td>
                                    <td style="font-size:0.78rem;color:#64748B;white-space:nowrap;"><?= formatTanggal($g['created_at']) ?></td>
                                    <td>
                                        <div style="display:flex;gap:6px;">
                                            <a href="?mode=edit&id=<?= $g['id'] ?>" style="background:#ECFDF5;color:#059669;padding:6px 10px;border-radius:6px;font-size:0.78rem;"><i class="fas fa-edit"></i></a>
                                            <a href="?delete=<?= $g['id'] ?>&token=<?= generateCsrfToken() ?>" onclick="return confirm('Hapus foto ini dari galeri?')" style="background:#FEF2F2;color:#DC2626;padding:6px 10px;border-radius:6px;font-size:0.78rem;"><i class="fas fa-trash"></i></a>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
