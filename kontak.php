<?php
// kontak.php — Halaman Kontak
require_once __DIR__.'/includes/config.php';
$pageTitle = 'Kontak';
$db = getDB();

$errors  = [];
$success = false;
// Pertahankan nilai input saat error
$nama = $email = $pesan = '';

// ── Proses Form POST ──
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Validasi CSRF
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Token keamanan tidak valid. Silakan refresh halaman dan coba lagi.';
    } else {
        // 2. Sanitasi input
        $nama  = trim($_POST['nama']  ?? '');
        $email = trim($_POST['email'] ?? '');
        $pesan = trim($_POST['pesan'] ?? '');

        // 3. Validasi
        if (empty($nama))
            $errors[] = 'Nama tidak boleh kosong.';
        elseif (strlen($nama) < 3)
            $errors[] = 'Nama minimal 3 karakter.';

        if (empty($email))
            $errors[] = 'Email tidak boleh kosong.';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = 'Format email tidak valid.';

        if (empty($pesan))
            $errors[] = 'Pesan tidak boleh kosong.';
        elseif (strlen($pesan) < 10)
            $errors[] = 'Pesan minimal 10 karakter.';

        // 4. Simpan ke DB (Prepared Statement — aman SQL Injection)
        if (empty($errors)) {
            $stmt = $db->prepare(
                "INSERT INTO pesan_kontak (nama, email, pesan) VALUES (?, ?, ?)"
            );
            if ($stmt->execute([$nama, $email, $pesan])) {
                // Regenerate CSRF setelah sukses
                unset($_SESSION['csrf_token']);
                setFlash('success', '✅ Pesan berhasil dikirim! Kami akan menghubungi kamu segera.');
                redirect(APP_URL . '/kontak.php');
            } else {
                $errors[] = 'Terjadi kesalahan saat menyimpan pesan. Silakan coba lagi.';
            }
        }
    }
}

include __DIR__.'/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <span class="page-hero-badge">
            <i class="fas fa-envelope"></i> Hubungi Kami
        </span>
        <h1>Ada Pertanyaan?<br>
            <span style="color:var(--clr-primary);">Kami Siap Membantu</span>
        </h1>
        <p>Kirimkan pesan, pertanyaan, atau saran kepada kami.
           Tim kami akan merespons dalam 1×24 jam kerja.</p>
    </div>
</section>

<section class="section-py contact-section">
    <div class="container">

        <!-- Error Display -->
        <?php if (!empty($errors)): ?>
        <div class="alert-msg alert-error fade-up" role="alert">
            <div style="display:flex;align-items:flex-start;gap:12px;">
                <i class="fas fa-exclamation-triangle" style="margin-top:2px;flex-shrink:0;"></i>
                <div>
                    <strong>Pesan gagal dikirim. Perbaiki kesalahan berikut:</strong>
                    <ul style="margin:8px 0 0 18px;padding:0;">
                        <?php foreach ($errors as $err): ?>
                        <li style="font-size:0.875rem;margin-bottom:3px;">
                            <?= e($err) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="row g-4">

            <!-- ── Info Kontak ── -->
            <div class="col-lg-4 fade-up">
                <div class="contact-info-card">
                    <h3>Informasi Kontak</h3>
                    <p>Jangan ragu untuk menghubungi kami melalui
                       berbagai saluran di bawah ini.</p>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-info-text">
                            <h6>Alamat</h6>
                            <p><?= APP_ADDRESS ?></p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-info-text">
                            <h6>Telepon</h6>
                            <p><a href="tel:<?= APP_PHONE ?>"
                                  style="color:rgba(255,255,255,0.85);">
                                <?= APP_PHONE ?>
                            </a></p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-info-text">
                            <h6>Email</h6>
                            <p><a href="mailto:<?= APP_EMAIL ?>"
                                  style="color:rgba(255,255,255,0.85);">
                                <?= APP_EMAIL ?>
                            </a></p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-info-text">
                            <h6>Jam Layanan</h6>
                            <p>Senin – Jumat: 08.00 – 16.00 WIB</p>
                        </div>
                    </div>

                    <!-- Sosmed -->
                    <div style="margin-top:28px;padding-top:24px;
                         border-top:1px solid rgba(255,255,255,0.2);">
                        <p style="font-size:0.82rem;opacity:0.8;margin-bottom:14px;">
                            Ikuti kami di media sosial:
                        </p>
                        <div style="display:flex;gap:10px;">
                            <?php
                            $socmed = [
                                ['fab fa-instagram', 'https://www.instagram.com/jurusan.mi.polsri/', 'Instagram'],
                                ['fab fa-youtube',   '#', 'YouTube'],
                                ['fab fa-linkedin-in','#', 'LinkedIn'],
                            ];
                            foreach ($socmed as $s): ?>
                            <a href="<?= $s[1] ?>"
                               target="_blank" rel="noopener noreferrer"
                               title="<?= $s[2] ?>"
                               class="socmed-btn-white">
                                <i class="<?= $s[0] ?>"></i>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Form Kontak ── -->
            <div class="col-lg-8 fade-up">
                <div class="contact-form-card">
                    <h4 style="margin-bottom:6px;">Kirim Pesan</h4>
                    <p style="color:var(--clr-text-muted);font-size:0.875rem;margin-bottom:28px;">
                        Isi formulir di bawah ini dan kami akan menghubungi kamu
                        secepatnya.
                    </p>

                    <form method="POST" action="kontak.php"
                          id="contactForm" novalidate>

                        <!-- CSRF Hidden Token -->
                        <input type="hidden" name="csrf_token"
                               value="<?= generateCsrfToken() ?>">

                        <div class="row g-4">
                            <!-- Nama -->
                            <div class="col-md-6">
                                <div class="field-wrap">
                                    <label class="form-label-custom" for="nama">
                                        Nama Lengkap
                                        <span style="color:#E11D48;">*</span>
                                    </label>
                                    <input type="text" id="nama" name="nama"
                                           class="form-control-custom"
                                           placeholder="Masukkan nama lengkap kamu"
                                           value="<?= e($nama) ?>"
                                           maxlength="100" required
                                           autocomplete="name">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="field-wrap">
                                    <label class="form-label-custom" for="email">
                                        Alamat Email
                                        <span style="color:#E11D48;">*</span>
                                    </label>
                                    <input type="email" id="email" name="email"
                                           class="form-control-custom"
                                           placeholder="nama@email.com"
                                           value="<?= e($email) ?>"
                                           maxlength="150" required
                                           autocomplete="email">
                                </div>
                            </div>

                            <!-- Pesan -->
                            <div class="col-12">
                                <div class="field-wrap">
                                    <label class="form-label-custom" for="pesan">
                                        Pesan
                                        <span style="color:#E11D48;">*</span>
                                    </label>
                                    <textarea id="pesan" name="pesan"
                                              class="form-control-custom"
                                              placeholder="Tuliskan pertanyaan, saran, atau pesan kamu di sini..."
                                              rows="6" maxlength="2000"
                                              required><?= e($pesan) ?></textarea>
                                    <div style="display:flex;justify-content:flex-end;
                                         font-size:0.75rem;color:var(--clr-text-light);
                                         margin-top:4px;" id="charCount">
                                        0 / 2000
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="col-12">
                                <button type="submit" class="btn-submit">
                                    <i class="fas fa-paper-plane"></i>
                                    Kirim Pesan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ── Google Maps Embed ── -->
        <div class="row mt-5">
            <div class="col-12 fade-up">
                <div style="border-radius:var(--radius-xl);overflow:hidden;
                     box-shadow:var(--clr-shadow-card);
                     border:1px solid var(--clr-border);">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.428!2d104.7457!3d-2.9736!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e3b75c5d8dbc6b7%3A0x6e1c3d6b5a4b9!2sPoliteknik%20Negeri%20Sriwijaya!5e0!3m2!1sid!2sid!4v1"
                        width="100%" height="380" style="border:0;display:block;"
                        allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Lokasi Politeknik Negeri Sriwijaya">
                    </iframe>
                </div>
                <p style="text-align:center;font-size:0.82rem;
                   color:var(--clr-text-muted);margin-top:12px;">
                    <i class="fas fa-map-marker-alt me-1 text-accent"></i>
                    <?= e(APP_ADDRESS) ?>
                </p>
            </div>
        </div>

    </div>
</section>



<?php include __DIR__.'/includes/footer.php'; ?>
