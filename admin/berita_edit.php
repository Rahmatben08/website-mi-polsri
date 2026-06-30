<?php
// admin/berita_edit.php
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db = getDB();

$id = (int)($_GET['id'] ?? 0);
if (!$id) redirect(APP_URL . '/admin/berita.php');

$berita = $db->prepare("SELECT * FROM berita WHERE id = ?");
$berita->execute([$id]);
$b = $berita->fetch();
if (!$b) redirect(APP_URL . '/admin/berita.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token tidak valid.';
    } else {
        $judul    = trim($_POST['judul']    ?? '');
        $konten   = $_POST['konten']        ?? '';
        $kategori = $_POST['kategori']      ?? 'berita';
        $status   = $_POST['status']        ?? 'draft';
        $gambar   = uploadImage($_FILES['gambar_file'] ?? [], $errors, $b['gambar'], 'berita');

        if (empty($judul))  $errors[] = 'Judul wajib diisi.';
        if (empty($konten)) $errors[] = 'Konten wajib diisi.';

        if (empty($errors)) {
            $slug = makeSlug($judul);
            // Cek slug unik (kecuali milik sendiri)
            $checkSlug = $db->prepare("SELECT id FROM berita WHERE slug = ? AND id != ?");
            $checkSlug->execute([$slug, $id]);
            if ($checkSlug->fetchColumn()) $slug = $slug . '-' . $id;

            $stmt = $db->prepare(
                "UPDATE berita SET judul=?, slug=?, konten=?, gambar=?, kategori=?, status=? WHERE id=?"
            );
            if ($stmt->execute([$judul, $slug, $konten, $gambar, $kategori, $status, $id])) {
                logAktivitas('EDIT', "Memperbarui berita: " . $judul);
                setFlash('success', 'Berita berhasil diperbarui.');
                redirect(APP_URL . '/admin/berita.php');
            } else {
                $errors[] = 'Gagal memperbarui berita.';
            }
        }
    }
}
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Edit Berita | Admin MI</title>
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
            <a href="berita.php" style="color:#64748B;"><i class="fas fa-arrow-left"></i></a>
            <h5 style="margin:0;font-size:1rem;font-weight:700;">Edit Berita</h5>
        </div>
    </div>
    <div class="admin-main">
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-4"><ul style="margin:0;padding-left:16px;"><?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="admin-card mb-4">
                        <div class="admin-card-header"><h5>Konten Berita</h5></div>
                        <div class="admin-card-body">
                            <div style="margin-bottom:20px;">
                                <label class="form-label-custom">Judul *</label>
                                <input type="text" name="judul" class="form-control-custom" value="<?= e(isset($judul) ? $judul : $b['judul']) ?>" required>
                            </div>
                            <div>
                                <label class="form-label-custom">Konten *</label>
                                <textarea name="konten" class="form-control-custom" rows="16" required><?= e(isset($konten) ? $konten : $b['konten']) ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="admin-card mb-4">
                        <div class="admin-card-header"><h5>Pengaturan</h5></div>
                        <div class="admin-card-body">
                            <div style="margin-bottom:16px;">
                                <label class="form-label-custom">Kategori</label>
                                <select name="kategori" class="form-control-custom">
                                    <option value="berita"  <?= ($b['kategori']==='berita')  ? 'selected' : '' ?>>📰 Berita</option>
                                    <option value="artikel" <?= ($b['kategori']==='artikel') ? 'selected' : '' ?>>✍️ Artikel</option>
                                </select>
                            </div>
                            <div style="margin-bottom:16px;">
                                <label class="form-label-custom">Status</label>
                                <select name="status" class="form-control-custom">
                                    <option value="publish" <?= ($b['status']==='publish') ? 'selected' : '' ?>>✅ Publish</option>
                                    <option value="draft"   <?= ($b['status']==='draft')   ? 'selected' : '' ?>>📝 Draft</option>
                                </select>
                            </div>
                             <div>
                                 <label class="form-label-custom">Unggah Gambar Baru (Opsional)</label>
                                 <input type="file" name="gambar_file" class="form-control-custom" accept="image/*">
                                 <div style="font-size:0.75rem;color:#94A3B8;margin-top:4px;margin-bottom:8px;">
                                     Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.
                                 </div>
                                 <div style="font-size:0.75rem;color:#64748B;">
                                     Gambar saat ini: <code><?= e($b['gambar']) ?></code>
                                 </div>
                             </div>
                        </div>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <button type="submit" class="btn-admin-primary" style="flex:1;justify-content:center;"><i class="fas fa-save"></i> Simpan</button>
                        <a href="berita.php" style="padding:10px 16px;background:#F1F5F9;color:#64748B;border-radius:var(--radius-md);font-size:0.875rem;font-weight:600;display:flex;align-items:center;">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
</body></html>
