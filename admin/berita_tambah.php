<?php
// admin/berita_tambah.php — Tambah Berita Baru
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db     = getDB();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token tidak valid.';
    } else {
        $judul    = trim($_POST['judul']    ?? '');
        $konten   = $_POST['konten']        ?? '';
        $kategori = $_POST['kategori']      ?? 'berita';
        $status   = $_POST['status']        ?? 'draft';
        $gambar   = uploadImage($_FILES['gambar_file'] ?? [], $errors, 'default-berita.jpg', 'berita');
        $slug     = makeSlug($judul);

        if (empty($judul))  $errors[] = 'Judul wajib diisi.';
        if (empty($konten)) $errors[] = 'Konten wajib diisi.';
        if (!in_array($kategori, ['berita','artikel'])) $errors[] = 'Kategori tidak valid.';
        if (!in_array($status,   ['publish','draft']))  $errors[] = 'Status tidak valid.';

        // Cek slug unik
        if (empty($errors)) {
            $checkSlug = $db->prepare("SELECT id FROM berita WHERE slug = ?");
            $checkSlug->execute([$slug]);
            if ($checkSlug->fetchColumn()) {
                $slug = $slug . '-' . time(); // Tambah timestamp jika slug duplikat
            }

            $stmt = $db->prepare(
                "INSERT INTO berita (judul, slug, konten, gambar, kategori, status) VALUES (?, ?, ?, ?, ?, ?)"
            );
            if ($stmt->execute([$judul, $slug, $konten, $gambar, $kategori, $status])) {
                logAktivitas('TAMBAH', "Menambahkan berita baru: " . $judul);
                setFlash('success', 'Berita "' . $judul . '" berhasil ditambahkan.');
                redirect(APP_URL . '/admin/berita.php');
            } else {
                $errors[] = 'Gagal menyimpan berita.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Tambah Berita | Admin MI</title>
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
            <a href="berita.php" style="color:#64748B;"><i class="fas fa-arrow-left"></i></a>
            <h5 style="margin:0;font-size:1rem;font-weight:700;">Tambah Berita / Artikel</h5>
        </div>
    </div>
    <div class="admin-main">
        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-4">
            <ul style="margin:0;padding-left:16px;">
                <?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div class="row g-4">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="admin-card mb-4">
                        <div class="admin-card-header"><h5>Konten Berita</h5></div>
                        <div class="admin-card-body">
                            <div style="margin-bottom:20px;">
                                <label class="form-label-custom">Judul <span style="color:#E11D48;">*</span></label>
                                <input type="text" name="judul" class="form-control-custom"
                                       placeholder="Masukkan judul berita yang menarik..."
                                       value="<?= isset($judul) ? e($judul) : '' ?>"
                                       id="judulInput" required>
                                <div style="font-size:0.78rem;color:#64748B;margin-top:6px;">
                                    Slug: <span id="slugPreview" style="color:var(--clr-primary);"></span>
                                </div>
                            </div>
                            <div>
                                <label class="form-label-custom">Konten <span style="color:#E11D48;">*</span></label>
                                <textarea name="konten" id="kontenEditor"
                                          class="form-control-custom"
                                          rows="16"
                                          placeholder="Tulis konten berita di sini... Kamu bisa menggunakan HTML seperti <p>, <strong>, <ul>, <h3>, dll."
                                          required><?= isset($konten) ? e($konten) : '' ?></textarea>
                                <div style="font-size:0.75rem;color:#94A3B8;margin-top:4px;">
                                    Mendukung HTML: &lt;p&gt; &lt;strong&gt; &lt;em&gt; &lt;ul&gt; &lt;ol&gt; &lt;li&gt; &lt;h3&gt; &lt;h4&gt; &lt;blockquote&gt;
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Settings -->
                <div class="col-lg-4">
                    <div class="admin-card mb-4">
                        <div class="admin-card-header"><h5>Pengaturan</h5></div>
                        <div class="admin-card-body">
                            <div style="margin-bottom:16px;">
                                <label class="form-label-custom">Kategori</label>
                                <select name="kategori" class="form-control-custom">
                                    <option value="berita"   <?= (isset($kategori) && $kategori==='berita')   ? 'selected' : '' ?>>📰 Berita</option>
                                    <option value="artikel"  <?= (isset($kategori) && $kategori==='artikel')  ? 'selected' : '' ?>>✍️ Artikel</option>
                                </select>
                            </div>
                            <div style="margin-bottom:16px;">
                                <label class="form-label-custom">Status Publikasi</label>
                                <select name="status" class="form-control-custom">
                                    <option value="publish" <?= (isset($status) && $status==='publish') ? 'selected' : '' ?>>✅ Publish</option>
                                    <option value="draft"   <?= (isset($status) && $status==='draft')   ? 'selected' : '' ?>>📝 Draft</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label-custom">Unggah Gambar Berita</label>
                                <input type="file" name="gambar_file" class="form-control-custom" accept="image/*">
                                <div style="font-size:0.75rem;color:#94A3B8;margin-top:4px;">
                                    Maksimal 2MB. Format: JPG, JPEG, PNG, WEBP, GIF.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;">
                        <button type="submit" class="btn-admin-primary" style="flex:1;justify-content:center;">
                            <i class="fas fa-save"></i> Simpan Berita
                        </button>
                        <a href="berita.php" style="padding:10px 16px;background:#F1F5F9;color:#64748B;border-radius:var(--radius-md);font-size:0.875rem;font-weight:600;display:flex;align-items:center;">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
// Auto-generate slug preview dari judul
const judulInput  = document.getElementById('judulInput');
const slugPreview = document.getElementById('slugPreview');
if (judulInput && slugPreview) {
    judulInput.addEventListener('input', () => {
        const slug = judulInput.value
            .toLowerCase().trim()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/[\s-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        slugPreview.textContent = slug || '(akan dibuat otomatis)';
    });
}
</script>
</body>
</html>
