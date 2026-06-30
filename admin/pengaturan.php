<?php
// admin/pengaturan.php — Pengaturan Website & Akun
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();
$db     = getDB();
$errors = [];

// Load data pengaturan
$sQuery = $db->query("SELECT kunci, nilai FROM pengaturan")->fetchAll(PDO::FETCH_KEY_PAIR);
$settings = $sQuery ?: [];

// Load data admin saat ini
$adminId = $_SESSION['admin_id'];
$adminQuery = $db->prepare("SELECT * FROM admin WHERE id=?");
$adminQuery->execute([$adminId]);
$adminUser = $adminQuery->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token tidak valid.';
    } else {
        $action = $_POST['action'] ?? '';
        
        if ($action === 'identitas') {
            $email     = trim($_POST['email'] ?? '');
            $telepon   = trim($_POST['telepon'] ?? '');
            $alamat    = trim($_POST['alamat'] ?? '');
            $facebook  = trim($_POST['facebook'] ?? '');
            $instagram = trim($_POST['instagram'] ?? '');
            $youtube   = trim($_POST['youtube'] ?? '');
            
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
            if (empty($telepon)) $errors[] = 'Nomor telepon wajib diisi.';
            if (empty($alamat)) $errors[] = 'Alamat wajib diisi.';
            
            if (empty($errors)) {
                $stmt = $db->prepare("INSERT INTO pengaturan (kunci, nilai) VALUES (?, ?) ON DUPLICATE KEY UPDATE nilai=?");
                $fields = [
                    'email' => $email,
                    'telepon' => $telepon,
                    'alamat' => $alamat,
                    'facebook' => $facebook,
                    'instagram' => $instagram,
                    'youtube' => $youtube
                ];
                foreach ($fields as $k => $v) {
                    $stmt->execute([$k, $v, $v]);
                }
                logAktivitas('EDIT', "Memperbarui identitas dan kontak website");
                setFlash('success', 'Pengaturan identitas website berhasil diperbarui.');
                redirect(APP_URL.'/admin/pengaturan.php');
            }
        }
        
        if ($action === 'keamanan') {
            $newUsername = trim($_POST['username'] ?? '');
            $oldPassword = $_POST['old_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (empty($newUsername)) $errors[] = 'Username tidak boleh kosong.';
            if (empty($oldPassword)) $errors[] = 'Password saat ini wajib diisi untuk verifikasi keamanan.';
            
            // Verifikasi password saat ini
            if (empty($errors) && !password_verify($oldPassword, $adminUser['password'])) {
                $errors[] = 'Password saat ini salah.';
            }
            
            // Jika ingin mengubah password
            if (empty($errors) && !empty($newPassword)) {
                if (strlen($newPassword) < 6) {
                    $errors[] = 'Password baru minimal 6 karakter.';
                }
                if ($newPassword !== $confirmPassword) {
                    $errors[] = 'Konfirmasi password baru tidak cocok.';
                }
            }
            
            if (empty($errors)) {
                if (!empty($newPassword)) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                    $stmt = $db->prepare("UPDATE admin SET username=?, password=? WHERE id=?");
                    $stmt->execute([$newUsername, $hashedPassword, $adminId]);
                    logAktivitas('EDIT', "Mengubah username dan password akun admin");
                } else {
                    $stmt = $db->prepare("UPDATE admin SET username=? WHERE id=?");
                    $stmt->execute([$newUsername, $adminId]);
                    logAktivitas('EDIT', "Mengubah username akun admin");
                }
                
                // Update session username jika berhasil diubah
                $_SESSION['admin_user'] = $newUsername;
                
                setFlash('success', 'Pengaturan akun keamanan berhasil diperbarui.');
                redirect(APP_URL.'/admin/pengaturan.php');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Pengaturan | Admin MI</title>
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
            <div>
                <h5 style="margin:0;font-size:1rem;font-weight:700;">Pengaturan Panel &amp; Website</h5>
                <p style="margin:0;font-size:0.75rem;color:#64748B;">Kelola profil keamanan dan informasi website</p>
            </div>
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
        </div>
    </div>

    <div class="admin-main">
        <?php $flash = getFlash(); if ($flash): ?>
        <div class="alert alert-<?= $flash['type']==='success'?'success':'danger' ?> mb-4" style="border-radius:var(--radius-md);"><?= e($flash['message']) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-4" style="border-radius:var(--radius-md);">
            <ul style="margin:0;padding-left:16px;">
                <?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <!-- Pengaturan Umum / Website Identitas -->
            <div class="col-lg-6">
                <div class="admin-card" style="height:100%;">
                    <div class="admin-card-header" style="border-bottom:1px solid #E2E8F0;padding:20px 24px;">
                        <h5 style="margin:0;font-size:1rem;font-weight:700;color:var(--clr-primary);"><i class="fas fa-globe" style="margin-right:8px;"></i>Identitas Website</h5>
                    </div>
                    <div class="admin-card-body" style="padding:24px;">
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                            <input type="hidden" name="action" value="identitas">

                            <div style="margin-bottom:16px;">
                                <label class="form-label-custom">Email Jurusan *</label>
                                <input type="email" name="email" class="form-control-custom" value="<?= e($settings['email'] ?? 'mi@polsri.ac.id') ?>" required placeholder="Masukkan email jurusan...">
                            </div>

                            <div style="margin-bottom:16px;">
                                <label class="form-label-custom">Nomor Telepon *</label>
                                <input type="text" name="telepon" class="form-control-custom" value="<?= e($settings['telepon'] ?? '0711 321234') ?>" required placeholder="Masukkan nomor telepon...">
                            </div>

                            <div style="margin-bottom:16px;">
                                <label class="form-label-custom">Alamat Fisik *</label>
                                <textarea name="alamat" class="form-control-custom" rows="3" required placeholder="Masukkan alamat lengkap jurusan..." style="resize:vertical;min-height:90px;"><?= e($settings['alamat'] ?? 'Jl. Sungai Sahang No.3654, Lorok Pakjo, Kec. Ilir Bar. I, Palembang 30151') ?></textarea>
                            </div>

                            <div style="border-top:1px dashed #E2E8F0;margin:24px 0 16px;"></div>
                            <h6 style="font-weight:700;font-size:0.85rem;color:#334155;margin-bottom:14px;"><i class="fas fa-share-nodes" style="margin-right:6px;"></i>Tautan Media Sosial (Opsional)</h6>

                            <div style="margin-bottom:14px;">
                                <label class="form-label-custom"><i class="fab fa-facebook" style="color:#1877F2;margin-right:6px;"></i>Facebook Link</label>
                                <input type="text" name="facebook" class="form-control-custom" value="<?= e($settings['facebook'] ?? '#') ?>" placeholder="https://facebook.com/username...">
                            </div>

                            <div style="margin-bottom:14px;">
                                <label class="form-label-custom"><i class="fab fa-instagram" style="color:#E1306C;margin-right:6px;"></i>Instagram Link</label>
                                <input type="text" name="instagram" class="form-control-custom" value="<?= e($settings['instagram'] ?? '#') ?>" placeholder="https://instagram.com/username...">
                            </div>

                            <div style="margin-bottom:24px;">
                                <label class="form-label-custom"><i class="fab fa-youtube" style="color:#FF0000;margin-right:6px;"></i>YouTube Link</label>
                                <input type="text" name="youtube" class="form-control-custom" value="<?= e($settings['youtube'] ?? '#') ?>" placeholder="https://youtube.com/channel...">
                            </div>

                            <button type="submit" class="btn-admin-primary" style="width:100%;justify-content:center;"><i class="fas fa-save" style="margin-right:6px;"></i>Simpan Identitas Website</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pengaturan Keamanan / Kredensial Admin -->
            <div class="col-lg-6">
                <div class="admin-card" style="height:100%;">
                    <div class="admin-card-header" style="border-bottom:1px solid #E2E8F0;padding:20px 24px;">
                        <h5 style="margin:0;font-size:1rem;font-weight:700;color:var(--clr-primary);"><i class="fas fa-lock" style="margin-right:8px;"></i>Keamanan Akun</h5>
                    </div>
                    <div class="admin-card-body" style="padding:24px;">
                        <form method="POST">
                            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                            <input type="hidden" name="action" value="keamanan">

                            <div style="margin-bottom:16px;">
                                <label class="form-label-custom">Username Admin</label>
                                <input type="text" name="username" class="form-control-custom" value="<?= e($adminUser['username']) ?>" required placeholder="Masukkan username baru...">
                                <div style="font-size:0.75rem;color:#94A3B8;margin-top:4px;">Username untuk masuk ke panel admin.</div>
                            </div>

                            <div style="border-top:1px dashed #E2E8F0;margin:24px 0 16px;"></div>
                            <h6 style="font-weight:700;font-size:0.85rem;color:#E11D48;margin-bottom:14px;"><i class="fas fa-key" style="margin-right:6px;"></i>Ubah Password Akun (Kosongkan jika tidak diubah)</h6>

                            <div style="margin-bottom:14px;">
                                <label class="form-label-custom">Password Baru</label>
                                <input type="password" name="new_password" class="form-control-custom" placeholder="Masukkan password baru (min. 6 karakter)...">
                            </div>

                            <div style="margin-bottom:16px;">
                                <label class="form-label-custom">Konfirmasi Password Baru</label>
                                <input type="password" name="confirm_password" class="form-control-custom" placeholder="Ulangi password baru...">
                            </div>

                            <div style="border-top:1px dashed #E2E8F0;margin:24px 0 16px;"></div>
                            <h6 style="font-weight:700;font-size:0.85rem;color:#334155;margin-bottom:14px;"><i class="fas fa-shield-halved" style="margin-right:6px;"></i>Verifikasi Password Saat Ini</h6>

                            <div style="margin-bottom:24px;">
                                <label class="form-label-custom" style="color:#E11D48;font-weight:600;">Password Saat Ini *</label>
                                <input type="password" name="old_password" class="form-control-custom" required placeholder="Masukkan password saat ini untuk menyimpan perubahan...">
                                <div style="font-size:0.75rem;color:#94A3B8;margin-top:4px;">Wajib diisi untuk memvalidasi identitas Anda sebelum melakukan perubahan.</div>
                            </div>

                            <button type="submit" class="btn-admin-primary" style="width:100%;justify-content:center;background:#E11D48;"><i class="fas fa-user-shield" style="margin-right:6px;"></i>Simpan Perubahan Akun</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
