<?php
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db = getDB();

$errors = [];
$action = $_GET['action'] ?? '';

// Handle Actions (ADD / EDIT / DELETE)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($token)) {
        die('CSRF token tidak valid.');
    }

    // --- TAMBAH DOKUMEN ---
    if (isset($_POST['tambah_dokumen'])) {
        $nama = trim($_POST['nama'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $kategori = trim($_POST['kategori'] ?? 'Umum');

        if (empty($nama)) $errors[] = 'Nama dokumen wajib diisi.';
        
        // Handle file upload
        $fileName = '';
        if (isset($_FILES['dokumen_file'])) {
            $fileName = uploadDocument($_FILES['dokumen_file'], $errors, '', 'doc');
        } else {
            $errors[] = 'File dokumen wajib diunggah.';
        }

        if (empty($errors) && !empty($fileName)) {
            $stmt = $db->prepare("INSERT INTO dokumen (nama, deskripsi, file_name, kategori) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nama, $deskripsi, $fileName, $kategori]);
            
            logAktivitas("Mengunggah Dokumen", "Nama: " . $nama . " (Kategori: " . $kategori . ")");
            setFlash('success', 'Dokumen berhasil diunggah.');
            redirect(APP_URL . '/admin/dokumen.php');
        }
    }

    // --- EDIT DOKUMEN ---
    if (isset($_POST['edit_dokumen'])) {
        $id = (int)($_POST['id'] ?? 0);
        $nama = trim($_POST['nama'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $kategori = trim($_POST['kategori'] ?? 'Umum');

        if (empty($nama)) $errors[] = 'Nama dokumen wajib diisi.';

        // Ambil info file lama
        $stmt = $db->prepare("SELECT file_name FROM dokumen WHERE id = ?");
        $stmt->execute([$id]);
        $oldFile = $stmt->fetchColumn();

        $fileName = $oldFile;
        // Check if new file is uploaded
        if (isset($_FILES['dokumen_file']) && $_FILES['dokumen_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $newFile = uploadDocument($_FILES['dokumen_file'], $errors, '', 'doc');
            if (empty($errors) && !empty($newFile)) {
                $fileName = $newFile;
                // Delete old file
                if ($oldFile && file_exists(dirname(__DIR__) . '/assets/documents/' . $oldFile)) {
                    unlink(dirname(__DIR__) . '/assets/documents/' . $oldFile);
                }
            }
        }

        if (empty($errors)) {
            $stmt = $db->prepare("UPDATE dokumen SET nama = ?, deskripsi = ?, file_name = ?, kategori = ? WHERE id = ?");
            $stmt->execute([$nama, $deskripsi, $fileName, $kategori, $id]);
            
            logAktivitas("Memperbarui Dokumen", "ID: " . $id . " - Nama: " . $nama);
            setFlash('success', 'Dokumen berhasil diperbarui.');
            redirect(APP_URL . '/admin/dokumen.php');
        }
    }
}

// --- HAPUS DOKUMEN (GET request with CSRF token on URL) ---
if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    $token = $_GET['token'] ?? '';
    if (!validateCsrfToken($token)) {
        die('Token keamanan tidak valid.');
    }

    // Ambil info file & nama untuk dihapus
    $stmt = $db->prepare("SELECT nama, file_name FROM dokumen WHERE id = ?");
    $stmt->execute([$id]);
    $doc = $stmt->fetch();

    if ($doc) {
        $stmt = $db->prepare("DELETE FROM dokumen WHERE id = ?");
        $stmt->execute([$id]);
        
        // Hapus file fisik
        if ($doc['file_name'] && file_exists(dirname(__DIR__) . '/assets/documents/' . $doc['file_name'])) {
            unlink(dirname(__DIR__) . '/assets/documents/' . $doc['file_name']);
        }
        
        logAktivitas("Menghapus Dokumen", "Nama: " . $doc['nama']);
        setFlash('success', 'Dokumen berhasil dihapus.');
    }
    redirect(APP_URL . '/admin/dokumen.php');
}

// Fetch all documents
$dokumenList = $db->query("SELECT * FROM dokumen ORDER BY created_at DESC")->fetchAll();
$adminPage = 'dokumen';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Kelola Dokumen | Admin MI</title>
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
            <h5 style="margin:0;font-size:1rem;font-weight:700;">Kelola Pusat Unduhan Dokumen</h5>
        </div>
        <div style="display:flex;align-items:center;gap:14px;">
            <div class="admin-clock-widget">
                <i class="far fa-clock"></i>
                <span id="adminDigitalClock">00:00:00</span>
                <span class="divider">|</span>
                <i class="fas fa-calendar-alt"></i>
                <span id="adminCalendarDate">00-00-0000</span>
            </div>
            <button id="themeToggleBtn" class="theme-toggle-btn" title="Ganti Tema">
                <i class="fas fa-moon"></i>
            </button>
            <button class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="fas fa-upload"></i> Unggah Dokumen</button>
        </div>
    </div>

    <div class="admin-main">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php $flash = getFlash(); if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : 'danger' ?> mb-4"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <h5 style="margin:0;">Daftar File Unduhan <span style="background:#E2E8F0;color:#64748B;font-size:0.75rem;padding:3px 10px;border-radius:50px;margin-left:8px;"><?= count($dokumenList) ?></span></h5>
                <div style="position:relative; width:100%; max-width:240px;">
                    <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94A3B8; font-size:0.8rem;"></i>
                    <input type="text" id="liveSearchInput" placeholder="Cari dokumen..." class="form-control-custom" style="padding-left:32px; font-size:0.8rem; height:34px; border-radius:6px; margin:0; width:100%;">
                </div>
            </div>
            
            <div style="overflow-x:auto;">
                <table class="table-admin" id="tableDokumen">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Dokumen</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                            <th>File Name</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dokumenList)): ?>
                            <tr>
                                <td colspan="6" style="text-align:center;padding:40px;color:#94A3B8;">Belum ada file dokumen. <a href="#" data-bs-toggle="modal" data-bs-target="#modalTambah">Unggah sekarang</a></td>
                            </tr>
                        <?php else: foreach ($dokumenList as $idx => $d): ?>
                            <tr class="dokumen-row" data-name="<?= e($d['nama']) ?>" data-kategori="<?= e($d['kategori']) ?>">
                                <td style="color:#94A3B8;font-size:0.8rem;"><?= $idx + 1 ?></td>
                                <td style="font-weight:600;font-size:0.875rem;max-width:260px;"><?= e($d['nama']) ?></td>
                                <td><span class="badge-modern badge-modern-secondary"><?= e($d['kategori']) ?></span></td>
                                <td style="font-size:0.8rem;color:#64748B;max-width:250px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($d['deskripsi']) ?: '-' ?></td>
                                <td style="font-size:0.75rem;color:#94A3B8;"><?= e($d['file_name']) ?></td>
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <a href="<?= APP_URL ?>/assets/documents/<?= e($d['file_name']) ?>" target="_blank" class="btn btn-sm" style="background:#ECFDF5;color:#10B981;"><i class="fas fa-download"></i></a>
                                        <button class="btn btn-sm" style="background:#EFF6FF;color:#3B82F6;" onclick='openEditModal(<?= json_encode($d) ?>)'><i class="fas fa-edit"></i></button>
                                        <a href="<?= APP_URL ?>/admin/dokumen.php?action=delete&id=<?= $d['id'] ?>&token=<?= $csrfToken ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus file ini?')" class="btn btn-sm" style="background:#FEF2F2;color:#EF4444;"><i class="fas fa-trash"></i></a>
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

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content glass-panel" style="border-radius:16px;">
            <div class="modal-header" style="border-bottom:1px solid var(--clr-border);">
                <h5 class="modal-title" style="font-weight:700;">Unggah Dokumen Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <input type="hidden" name="tambah_dokumen" value="1">
                <div class="modal-body" style="display:flex; flex-direction:column; gap:16px; padding:20px;">
                    <div>
                        <label class="form-label-custom">Nama Dokumen</label>
                        <input type="text" name="nama" class="form-control-custom" required placeholder="Contoh: Formulir Pendaftaran PKL">
                    </div>
                    <div>
                        <label class="form-label-custom">Kategori Dokumen</label>
                        <select name="kategori" class="form-control-custom">
                            <option value="PKL">Praktik Kerja Lapangan (PKL)</option>
                            <option value="Laporan Akhir">Laporan Akhir / Tugas Akhir</option>
                            <option value="Kelulusan">Kelulusan &amp; Yudisium</option>
                            <option value="Beasiswa">Beasiswa &amp; Kemahasiswaan</option>
                            <option value="Umum">Dokumen Umum</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label-custom">Deskripsi Singkat (Opsional)</label>
                        <textarea name="deskripsi" class="form-control-custom" placeholder="Penjelasan singkat isi dokumen..."></textarea>
                    </div>
                    <div>
                        <label class="form-label-custom">File Dokumen (PDF, DOCX, XLS, ZIP, Max 10MB)</label>
                        <input type="file" name="dokumen_file" class="form-control-custom" required>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--clr-border);">
                    <button type="button" class="btn-outline-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom" style="padding:10px 20px;">Unggah File</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content glass-panel" style="border-radius:16px;">
            <div class="modal-header" style="border-bottom:1px solid var(--clr-border);">
                <h5 class="modal-title" style="font-weight:700;">Edit Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <input type="hidden" name="edit_dokumen" value="1">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body" style="display:flex; flex-direction:column; gap:16px; padding:20px;">
                    <div>
                        <label class="form-label-custom">Nama Dokumen</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control-custom" required>
                    </div>
                    <div>
                        <label class="form-label-custom">Kategori Dokumen</label>
                        <select name="kategori" id="edit_kategori" class="form-control-custom">
                            <option value="PKL">Praktik Kerja Lapangan (PKL)</option>
                            <option value="Laporan Akhir">Laporan Akhir / Tugas Akhir</option>
                            <option value="Kelulusan">Kelulusan &amp; Yudisium</option>
                            <option value="Beasiswa">Beasiswa &amp; Kemahasiswaan</option>
                            <option value="Umum">Dokumen Umum</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label-custom">Deskripsi Singkat (Opsional)</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control-custom"></textarea>
                    </div>
                    <div>
                        <label class="form-label-custom">Ganti File Dokumen (Pilih jika ingin mengganti file lama)</label>
                        <input type="file" name="dokumen_file" class="form-control-custom">
                        <small id="current_file_lbl" style="font-size:0.75rem;color:#94A3B8;margin-top:6px;display:block;"></small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--clr-border);">
                    <button type="button" class="btn-outline-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom" style="padding:10px 20px;">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openEditModal(d) {
    document.getElementById('edit_id').value = d.id;
    document.getElementById('edit_nama').value = d.nama;
    document.getElementById('edit_kategori').value = d.kategori;
    document.getElementById('edit_deskripsi').value = d.deskripsi || '';
    document.getElementById('current_file_lbl').textContent = 'File saat ini: ' + d.file_name;
    
    var modal = new bootstrap.Modal(document.getElementById('modalEdit'));
    modal.show();
}

// Live Search
document.getElementById('liveSearchInput').addEventListener('input', function(e) {
    var query = e.target.value.toLowerCase().trim();
    var rows = document.querySelectorAll('.dokumen-row');
    
    rows.forEach(function(row) {
        var name = row.getAttribute('data-name').toLowerCase();
        var cat = row.getAttribute('data-kategori').toLowerCase();
        if (name.includes(query) || cat.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
</body>
</html>
