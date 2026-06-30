<?php
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db     = getDB();
$errors = [];
$mode   = $_GET['mode'] ?? 'list';
$editId = (int)($_GET['id'] ?? 0);

// DELETE
if (isset($_GET['delete']) && validateCsrfToken($_GET['token'] ?? '')) {
    $delId = (int)$_GET['delete'];
    $sName = $db->prepare("SELECT nama FROM dosen WHERE id=?");
    $sName->execute([$delId]);
    $name = $sName->fetchColumn();
    $db->prepare("DELETE FROM dosen WHERE id=?")->execute([$delId]);
    if ($name) {
        logAktivitas('HAPUS', "Menghapus dosen: " . $name);
    }
    setFlash('success','Dosen berhasil dihapus.');
    redirect(APP_URL.'/admin/dosen.php');
}
// TAMBAH
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='tambah') {
    if (!validateCsrfToken($_POST['csrf_token']??'')) { $errors[]='Token tidak valid.'; }
    else {
        $nama    = trim($_POST['nama']??'');
        $keahlian= trim($_POST['bidang_keahlian']??'');
        $email   = trim($_POST['email']??'');
        $nidn    = trim($_POST['nidn']??'');
        $nip     = trim($_POST['nip']??'');
        $jabatan = trim($_POST['jabatan']??'Dosen');
        $prodi   = in_array($_POST['prodi']??'',['d3','d4','keduanya'])?$_POST['prodi']:'d3';
        $foto    = uploadImage($_FILES['foto_file'] ?? [], $errors, 'default-dosen.jpg', 'dosen');
        if(empty($nama)) $errors[]='Nama wajib diisi.';
        if(empty($keahlian)) $errors[]='Bidang keahlian wajib diisi.';
        if(empty($email)||!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[]='Email tidak valid.';
        if(empty($errors)){
            $db->prepare("INSERT INTO dosen (nama,foto,bidang_keahlian,email,nidn,nip,jabatan,prodi) VALUES(?,?,?,?,?,?,?,?)")
               ->execute([$nama,$foto,$keahlian,$email,$nidn,$nip,$jabatan,$prodi]);
            logAktivitas('TAMBAH', "Menambahkan dosen baru: " . $nama);
            setFlash('success','Dosen berhasil ditambahkan.');
            redirect(APP_URL.'/admin/dosen.php');
        } else { $mode='tambah'; }
    }
}
// EDIT
if ($_SERVER['REQUEST_METHOD']==='POST' && ($_POST['action']??'')==='edit') {
    if (!validateCsrfToken($_POST['csrf_token']??'')) { $errors[]='Token tidak valid.'; }
    else {
        $id      = (int)$_POST['id'];
        
        $dosenQuery = $db->prepare("SELECT foto FROM dosen WHERE id=?");
        $dosenQuery->execute([$id]);
        $oldFoto = $dosenQuery->fetchColumn() ?: 'default-dosen.jpg';
        
        $nama    = trim($_POST['nama']??'');
        $keahlian= trim($_POST['bidang_keahlian']??'');
        $email   = trim($_POST['email']??'');
        $nidn    = trim($_POST['nidn']??'');
        $nip     = trim($_POST['nip']??'');
        $jabatan = trim($_POST['jabatan']??'Dosen');
        $prodi   = in_array($_POST['prodi']??'',['d3','d4','keduanya'])?$_POST['prodi']:'d3';
        $foto    = uploadImage($_FILES['foto_file'] ?? [], $errors, $oldFoto, 'dosen');
        if(empty($nama)) $errors[]='Nama wajib diisi.';
        if(empty($errors)){
            $db->prepare("UPDATE dosen SET nama=?,foto=?,bidang_keahlian=?,email=?,nidn=?,nip=?,jabatan=?,prodi=? WHERE id=?")
               ->execute([$nama,$foto,$keahlian,$email,$nidn,$nip,$jabatan,$prodi,$id]);
            logAktivitas('EDIT', "Memperbarui data dosen: " . $nama);
            setFlash('success','Data dosen diperbarui.');
            redirect(APP_URL.'/admin/dosen.php');
        } else { $mode='edit'; $editId=(int)$_POST['id']; }
    }
}

$dosenList = $db->query("SELECT * FROM dosen ORDER BY jabatan DESC, nama ASC")->fetchAll();
$editData  = null;
if ($editId) {
    $s = $db->prepare("SELECT * FROM dosen WHERE id=?");
    $s->execute([$editId]); $editData=$s->fetch();
}
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Kelola Dosen | Admin MI</title>
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
            <h5 style="margin:0;font-size:1rem;font-weight:700;">Kelola Dosen</h5>
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
            <a href="?mode=tambah" class="btn-admin-primary"><i class="fas fa-plus"></i> Tambah Dosen</a>
        </div>
    </div>
    <div class="admin-main">
        <?php $flash=getFlash(); if($flash): ?>
        <div class="alert alert-<?= $flash['type']==='success'?'success':'danger' ?> mb-4"><?= e($flash['message']) ?></div>
        <?php endif; ?>
        <?php if(!empty($errors)): ?>
        <div class="alert alert-danger mb-4"><ul style="margin:0;padding-left:16px;"><?php foreach($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <div class="row g-4">
            <?php if($mode==='tambah'||$mode==='edit'): ?>
            <div class="col-lg-4">
                <div class="admin-card">
                    <div class="admin-card-header"><h5><?= $mode==='edit'?'Edit Dosen':'Tambah Dosen Baru' ?></h5></div>
                    <div class="admin-card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                            <input type="hidden" name="action" value="<?= $mode ?>">
                            <?php if($mode==='edit'): ?><input type="hidden" name="id" value="<?= $editId ?>"><?php endif; ?>
                            <div style="margin-bottom:12px;">
                                <label class="form-label-custom">Nama Lengkap *</label>
                                <input type="text" name="nama" class="form-control-custom" value="<?= $editData?e($editData['nama']):'' ?>" required>
                            </div>
                            <div style="margin-bottom:12px;">
                                <label class="form-label-custom">NIDN</label>
                                <input type="text" name="nidn" class="form-control-custom" value="<?= $editData?e($editData['nidn']??''):'' ?>">
                            </div>
                            <div style="margin-bottom:12px;">
                                <label class="form-label-custom">NIP</label>
                                <input type="text" name="nip" class="form-control-custom" value="<?= $editData?e($editData['nip']??''):'' ?>">
                            </div>
                            <div style="margin-bottom:12px;">
                                <label class="form-label-custom">Jabatan</label>
                                <select name="jabatan" class="form-control-custom">
                                    <?php foreach(['Ketua Jurusan','Dosen Senior','Dosen'] as $j): ?>
                                    <option value="<?= $j ?>" <?= ($editData&&$editData['jabatan']===$j)?'selected':'' ?>><?= $j ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div style="margin-bottom:12px;">
                                <label class="form-label-custom">Program Studi</label>
                                <select name="prodi" class="form-control-custom">
                                    <option value="d3" <?= ($editData&&($editData['prodi']??'')==='d3')?'selected':'' ?>>D3 Diploma</option>
                                    <option value="d4" <?= ($editData&&($editData['prodi']??'')==='d4')?'selected':'' ?>>D4 Sarjana Terapan</option>
                                    <option value="keduanya" <?= ($editData&&($editData['prodi']??'')==='keduanya')?'selected':'' ?>>Keduanya</option>
                                </select>
                            </div>
                            <div style="margin-bottom:12px;">
                                <label class="form-label-custom">Bidang Keahlian *</label>
                                <input type="text" name="bidang_keahlian" class="form-control-custom" value="<?= $editData?e($editData['bidang_keahlian']):'' ?>" required>
                            </div>
                            <div style="margin-bottom:12px;">
                                <label class="form-label-custom">Email *</label>
                                <input type="email" name="email" class="form-control-custom" value="<?= $editData?e($editData['email']):'' ?>" required>
                            </div>
                            <div style="margin-bottom:20px;">
                                <label class="form-label-custom">Unggah Foto Dosen</label>
                                <input type="file" name="foto_file" class="form-control-custom" accept="image/*">
                                <div style="font-size:0.73rem;color:#94A3B8;margin-top:4px;margin-bottom:8px;">Format: JPG, JPEG, PNG, WEBP, GIF. Maksimal 2MB.</div>
                                <?php if ($editData && $editData['foto'] !== 'default-dosen.jpg'): ?>
                                <div style="font-size:0.75rem;color:#64748B;">Foto saat ini: <code><?= e($editData['foto']) ?></code></div>
                                <?php endif; ?>
                            </div>
                            <div style="display:flex;gap:8px;">
                                <button type="submit" class="btn-admin-primary" style="flex:1;justify-content:center;"><i class="fas fa-save"></i> Simpan</button>
                                <a href="dosen.php" style="padding:10px 14px;background:#F1F5F9;color:#64748B;border-radius:var(--radius-md);font-size:0.875rem;font-weight:600;display:flex;align-items:center;">Batal</a>
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
                        <h5 style="margin:0;">Daftar Dosen <span style="background:#E2E8F0;color:#64748B;font-size:0.75rem;padding:3px 10px;border-radius:50px;margin-left:8px;"><?= count($dosenList) ?></span></h5>
                        <div style="position:relative; width:100%; max-width:240px;">
                            <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94A3B8; font-size:0.8rem;"></i>
                            <input type="text" id="liveSearchInput" placeholder="Cari dosen..." class="form-control-custom" style="padding-left:32px; font-size:0.8rem; height:34px; border-radius:6px; margin:0; width:100%;">
                        </div>
                    </div>
                    <div style="overflow-x:auto;">
                        <table class="table-admin">
                            <thead><tr><th>#</th><th>Nama</th><th>Jabatan</th><th>Prodi</th><th>Keahlian</th><th>NIDN</th><th>Aksi</th></tr></thead>
                            <tbody>
                            <?php foreach($dosenList as $i=>$d): ?>
                            <tr>
                                <td style="color:#94A3B8;font-size:0.8rem;"><?= $i+1 ?></td>
                                <td style="font-weight:600;font-size:0.875rem;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($d['nama']) ?></td>
                                <td><span class="badge-modern badge-modern-success"><?= e($d['jabatan']) ?></span></td>
                                <td><span class="badge-modern badge-modern-info" style="text-transform:uppercase;"><?= e($d['prodi']??'d3') ?></span></td>
                                <td style="font-size:0.82rem;color:#64748B;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= e($d['bidang_keahlian']) ?></td>
                                <td style="font-size:0.78rem;color:#64748B;"><?= e($d['nidn']??'-') ?></td>
                                <td>
                                    <div style="display:flex;gap:6px;">
                                        <a href="?mode=edit&id=<?= $d['id'] ?>" style="background:#ECFDF5;color:#059669;padding:6px 10px;border-radius:6px;font-size:0.78rem;"><i class="fas fa-edit"></i></a>
                                        <a href="?delete=<?= $d['id'] ?>&token=<?= generateCsrfToken() ?>" onclick="return confirm('Hapus dosen ini?')" style="background:#FEF2F2;color:#DC2626;padding:6px 10px;border-radius:6px;font-size:0.78rem;"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
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
</body></html>
