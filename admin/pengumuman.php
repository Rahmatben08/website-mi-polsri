<?php
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db = getDB();

// Handle Actions (ADD / EDIT / DELETE)
$errors = [];
$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!validateCsrfToken($token)) {
        die('CSRF token tidak valid.');
    }

    // --- TAMBAH PENGUMUMAN ---
    if (isset($_POST['tambah_pengumuman'])) {
        $judul = trim($_POST['judul'] ?? '');
        $kategori = trim($_POST['kategori'] ?? 'Akademik');
        $tanggal = trim($_POST['tanggal'] ?? '');
        $link_info = trim($_POST['link_info'] ?? '');

        if (empty($judul)) $errors[] = 'Judul pengumuman wajib diisi.';
        if (empty($tanggal)) $errors[] = 'Tanggal pengumuman wajib diisi.';

        if (empty($errors)) {
            $stmt = $db->prepare("INSERT INTO pengumuman (judul, kategori, tanggal, link_info) VALUES (?, ?, ?, ?)");
            $stmt->execute([$judul, $kategori, $tanggal, $link_info ?: null]);
            
            logAktivitas("Menambah Pengumuman", "Judul: " . $judul . " (" . $kategori . ")");
            setFlash('success', 'Pengumuman berhasil ditambahkan.');
            redirect(APP_URL . '/admin/pengumuman.php');
        }
    }

    // --- EDIT PENGUMUMAN ---
    if (isset($_POST['edit_pengumuman'])) {
        $id = (int)($_POST['id'] ?? 0);
        $judul = trim($_POST['judul'] ?? '');
        $kategori = trim($_POST['kategori'] ?? 'Akademik');
        $tanggal = trim($_POST['tanggal'] ?? '');
        $link_info = trim($_POST['link_info'] ?? '');

        if (empty($judul)) $errors[] = 'Judul pengumuman wajib diisi.';
        if (empty($tanggal)) $errors[] = 'Tanggal pengumuman wajib diisi.';

        if (empty($errors)) {
            $stmt = $db->prepare("UPDATE pengumuman SET judul = ?, kategori = ?, tanggal = ?, link_info = ? WHERE id = ?");
            $stmt->execute([$judul, $kategori, $tanggal, $link_info ?: null, $id]);
            
            logAktivitas("Memperbarui Pengumuman", "ID: " . $id . " - Judul: " . $judul);
            setFlash('success', 'Pengumuman berhasil diperbarui.');
            redirect(APP_URL . '/admin/pengumuman.php');
        }
    }
}

// --- HAPUS PENGUMUMAN (GET request with CSRF token on URL) ---
if ($action === 'delete') {
    $id = (int)($_GET['id'] ?? 0);
    $token = $_GET['token'] ?? '';
    if (!validateCsrfToken($token)) {
        die('Token keamanan tidak valid.');
    }

    // Ambil info judul untuk logging
    $stmt = $db->prepare("SELECT judul FROM pengumuman WHERE id = ?");
    $stmt->execute([$id]);
    $judul = $stmt->fetchColumn();

    if ($judul) {
        $stmt = $db->prepare("DELETE FROM pengumuman WHERE id = ?");
        $stmt->execute([$id]);
        
        logAktivitas("Menghapus Pengumuman", "Judul: " . $judul);
        setFlash('success', 'Pengumuman berhasil dihapus.');
    }
    redirect(APP_URL . '/admin/pengumuman.php');
}

// Fetch all announcements
$pengumumanList = $db->query("SELECT * FROM pengumuman ORDER BY tanggal DESC, id DESC")->fetchAll();
$adminPage = 'pengumuman';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Kelola Pengumuman | Admin MI</title>
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
            <h5 style="margin:0;font-size:1rem;font-weight:700;">Kelola Pengumuman Akademik</h5>
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
            <button class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#modalTambah"><i class="fas fa-plus"></i> Tambah Pengumuman</button>
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
                <h5 style="margin:0;">Daftar Pengumuman <span style="background:#E2E8F0;color:#64748B;font-size:0.75rem;padding:3px 10px;border-radius:50px;margin-left:8px;"><?= count($pengumumanList) ?></span></h5>
                <div style="position:relative; width:100%; max-width:240px;">
                    <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94A3B8; font-size:0.8rem;"></i>
                    <input type="text" id="liveSearchInput" placeholder="Cari pengumuman..." class="form-control-custom" style="padding-left:32px; font-size:0.8rem; height:34px; border-radius:6px; margin:0; width:100%;">
                </div>
            </div>
            
            <div style="overflow-x:auto;">
                <table class="table-admin" id="tablePengumuman">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Pengumuman</th>
                            <th>Kategori</th>
                            <th>Tanggal Pelaksanaan</th>
                            <th>Link Info</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pengumumanList)): ?>
                            <tr>
                                <td colspan="6" style="text-align:center;padding:40px;color:#94A3B8;">Belum ada pengumuman. <a href="#" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah baru</a></td>
                            </tr>
                        <?php else: foreach ($pengumumanList as $idx => $p): ?>
                            <tr class="pengumuman-row" data-title="<?= e($p['judul']) ?>" data-kategori="<?= e($p['kategori']) ?>">
                                <td style="color:#94A3B8;font-size:0.8rem;"><?= $idx + 1 ?></td>
                                <td style="font-weight:600;font-size:0.875rem;max-width:280px;"><?= e($p['judul']) ?></td>
                                <td><span class="badge-modern badge-modern-secondary"><?= e($p['kategori']) ?></span></td>
                                <td style="font-size:0.78rem;color:#64748B;white-space:nowrap;"><?= formatTanggal($p['tanggal']) ?></td>
                                <td>
                                    <?php if ($p['link_info']): ?>
                                        <a href="<?= e($p['link_info']) ?>" target="_blank" class="text-truncate d-inline-block" style="max-width:150px; font-size:0.8rem; color:var(--clr-primary);"><i class="fas fa-external-link-alt"></i> Link</a>
                                    <?php else: ?>
                                        <span style="color:#94A3B8; font-size:0.8rem;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <button class="btn btn-sm" style="background:#EFF6FF;color:#3B82F6;" onclick='openEditModal(<?= json_encode($p) ?>)'><i class="fas fa-edit"></i></button>
                                        <a href="<?= APP_URL ?>/admin/pengumuman.php?action=delete&id=<?= $p['id'] ?>&token=<?= $csrfToken ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')" class="btn btn-sm" style="background:#FEF2F2;color:#EF4444;"><i class="fas fa-trash"></i></a>
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
                <h5 class="modal-title" style="font-weight:700;">Tambah Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <input type="hidden" name="tambah_pengumuman" value="1">
                <div class="modal-body" style="display:flex; flex-direction:column; gap:16px; padding:20px;">
                    <div>
                        <label class="form-label-custom">Judul Pengumuman</label>
                        <input type="text" name="judul" class="form-control-custom" required placeholder="Contoh: Pendaftaran PKL Semester Ganjil">
                    </div>
                    <div>
                        <label class="form-label-custom">Kategori</label>
                        <select name="kategori" class="form-control-custom">
                            <option value="Akademik">Akademik</option>
                            <option value="Ujian">Ujian</option>
                            <option value="Kelulusan">Kelulusan</option>
                            <option value="PMB">PMB</option>
                            <option value="Beasiswa">Beasiswa</option>
                            <option value="Umum">Umum</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label-custom">Tanggal Pelaksanaan / Batas Akhir</label>
                        <input type="date" name="tanggal" class="form-control-custom" required value="<?= date('Y-m-d') ?>">
                    </div>
                    <div>
                        <label class="form-label-custom">Link Info Eksternal (Opsional)</label>
                        <input type="url" name="link_info" class="form-control-custom" placeholder="https://spmb.polsri.ac.id">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--clr-border);">
                    <button type="button" class="btn-outline-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-primary-custom" style="padding:10px 20px;">Simpan</button>
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
                <h5 class="modal-title" style="font-weight:700;">Edit Pengumuman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                <input type="hidden" name="edit_pengumuman" value="1">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body" style="display:flex; flex-direction:column; gap:16px; padding:20px;">
                    <div>
                        <label class="form-label-custom">Judul Pengumuman</label>
                        <input type="text" name="judul" id="edit_judul" class="form-control-custom" required>
                    </div>
                    <div>
                        <label class="form-label-custom">Kategori</label>
                        <select name="kategori" id="edit_kategori" class="form-control-custom">
                            <option value="Akademik">Akademik</option>
                            <option value="Ujian">Ujian</option>
                            <option value="Kelulusan">Kelulusan</option>
                            <option value="PMB">PMB</option>
                            <option value="Beasiswa">Beasiswa</option>
                            <option value="Umum">Umum</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label-custom">Tanggal Pelaksanaan / Batas Akhir</label>
                        <input type="date" name="tanggal" id="edit_tanggal" class="form-control-custom" required>
                    </div>
                    <div>
                        <label class="form-label-custom">Link Info Eksternal (Opsional)</label>
                        <input type="url" name="link_info" id="edit_link_info" class="form-control-custom">
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
function openEditModal(p) {
    document.getElementById('edit_id').value = p.id;
    document.getElementById('edit_judul').value = p.judul;
    document.getElementById('edit_kategori').value = p.kategori;
    document.getElementById('edit_tanggal').value = p.tanggal;
    document.getElementById('edit_link_info').value = p.link_info || '';
    
    var modal = new bootstrap.Modal(document.getElementById('modalEdit'));
    modal.show();
}

// Live Search
document.getElementById('liveSearchInput').addEventListener('input', function(e) {
    var query = e.target.value.toLowerCase().trim();
    var rows = document.querySelectorAll('.pengumuman-row');
    
    rows.forEach(function(row) {
        var title = row.getAttribute('data-title').toLowerCase();
        var cat = row.getAttribute('data-kategori').toLowerCase();
        if (title.includes(query) || cat.includes(query)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
</body>
</html>
