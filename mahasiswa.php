<?php
// mahasiswa.php — Halaman Mahasiswa
require_once __DIR__.'/includes/config.php';
$pageTitle = 'Mahasiswa';
$db = getDB();

$prestasiList = $db->query(
    "SELECT * FROM mahasiswa_prestasi ORDER BY tahun DESC, id DESC"
)->fetchAll();

include __DIR__.'/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <span class="page-hero-badge">
            <i class="fas fa-user-graduate"></i> Kemahasiswaan
        </span>
        <h1>Mahasiswa &amp; Prestasi<br>
            <span style="color:var(--clr-primary);">Membanggakan</span>
        </h1>
        <p>Prestasi, kegiatan, dan organisasi kemahasiswaan Jurusan
           Manajemen Informatika Polsri yang terus berkembang.</p>
    </div>
</section>

<!-- ── PRESTASI MAHASISWA ── -->
<section class="section-py" style="background:var(--clr-bg);">
    <div class="container">
        <div class="section-header text-center fade-up">
            <span class="badge-label">
                <i class="fas fa-trophy me-1"></i> Achievement
            </span>
            <h2>Prestasi Mahasiswa</h2>
            <p>Berbagai pencapaian membanggakan yang diraih mahasiswa
               MI Polsri di kancah nasional dan internasional</p>
        </div>

        <?php if (empty($prestasiList)): ?>
        <div style="text-align:center;padding:60px;color:var(--clr-text-muted);">
            <i class="fas fa-trophy" style="font-size:3rem;opacity:0.2;margin-bottom:16px;display:block;"></i>
            <p>Belum ada data prestasi mahasiswa.</p>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($prestasiList as $p): ?>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="prestasi-card card-shine">
                    <div class="prestasi-tahun"><?= e($p['tahun']) ?></div>
                    <div class="prestasi-avatar">
                        <?php if (!empty($p['foto']) && $p['foto'] !== 'default-mahasiswa.jpg'): ?>
                        <img src="<?= APP_URL ?>/assets/images/<?= e($p['foto']) ?>"
                             alt="<?= e($p['nama']) ?>"
                             onerror="this.outerHTML='<i class=\'fas fa-user-graduate\'></i>'">
                        <?php else: ?>
                        <i class="fas fa-user-graduate"></i>
                        <?php endif; ?>
                    </div>
                    <div class="prestasi-name"><?= e($p['nama']) ?></div>
                    <div class="prestasi-nim">
                        <i class="fas fa-id-card me-1"></i><?= e($p['nim']) ?>
                    </div>
                    <div class="prestasi-badge">
                        <i class="fas fa-trophy"></i>
                        <?= e($p['prestasi']) ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ── KEGIATAN MAHASISWA ── -->
<section class="section-py" style="background:var(--clr-bg-soft);">
    <div class="container">
        <div class="section-header text-center fade-up">
            <span class="badge-label">Kegiatan</span>
            <h2>Kegiatan Mahasiswa</h2>
            <p>Beragam kegiatan akademik dan non-akademik untuk
               pengembangan diri mahasiswa</p>
        </div>

        <div class="row g-4">
            <?php
            $kegiatan = [
                ['fas fa-laptop-code', 'Hackathon & Kompetisi',
                 'Mahasiswa secara rutin mengikuti berbagai hackathon dan kompetisi pemrograman tingkat lokal, nasional, bahkan internasional.',
                 ['Pemrograman', 'Team Work', 'Problem Solving']],

                ['fas fa-flask', 'Penelitian Terapan',
                 'Program penelitian terapan yang memungkinkan mahasiswa berkolaborasi langsung dengan dosen dalam menghasilkan karya ilmiah berkualitas.',
                 ['Research', 'Analisis', 'Publikasi']],

                ['fas fa-users', 'Seminar & Workshop',
                 'Partisipasi aktif dalam seminar nasional dan workshop yang menghadirkan praktisi industri untuk berbagi pengalaman dan pengetahuan terkini.',
                 ['Networking', 'Knowledge', 'Industry']],

                ['fas fa-building', 'PKL Industri',
                 'Program Praktik Kerja Lapangan terstruktur di perusahaan mitra, memberikan pengalaman kerja nyata sebelum memasuki dunia profesional.',
                 ['Industri', 'Praktek', 'Profesional']],

                ['fas fa-globe', 'Kunjungan Industri',
                 'Program kunjungan ke perusahaan teknologi terkemuka untuk mengenal lingkungan kerja dan tren industri secara langsung.',
                 ['Wawasan', 'Motivasi', 'Karir']],

                ['fas fa-certificate', 'Sertifikasi Nasional',
                 'Program persiapan dan pendampingan bagi mahasiswa yang ingin mengambil sertifikasi profesional di bidang IT.',
                 ['Sertifikat', 'Kompetensi', 'Nilai Tambah']],
            ];
            foreach ($kegiatan as $k): ?>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="glass-card card-shine" style="padding:28px;height:100%;">
                    <div style="width:52px;height:52px;background:var(--clr-primary-xlight);
                         border-radius:14px;display:flex;align-items:center;
                         justify-content:center;color:var(--clr-primary);
                         font-size:1.3rem;margin-bottom:18px;">
                        <i class="<?= $k[0] ?>"></i>
                    </div>
                    <h5 style="font-size:1rem;margin-bottom:10px;"><?= $k[1] ?></h5>
                    <p style="font-size:0.85rem;color:var(--clr-text-muted);
                       margin-bottom:16px;line-height:1.7;">
                        <?= $k[2] ?>
                    </p>
                    <div style="display:flex;gap:6px;flex-wrap:wrap;">
                        <?php foreach ($k[3] as $tag): ?>
                        <span style="background:var(--clr-primary-xlight);
                              color:var(--clr-primary);font-size:0.72rem;
                              font-weight:600;padding:3px 10px;border-radius:50px;
                              border:1px solid var(--clr-primary-light);">
                            <?= $tag ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── ORGANISASI MAHASISWA ── -->
<section class="section-py" style="background:var(--clr-bg);">
    <div class="container">
        <div class="section-header text-center fade-up">
            <span class="badge-label">Organisasi</span>
            <h2>Organisasi Mahasiswa</h2>
            <p>Wadah pengembangan diri dan kepemimpinan mahasiswa
               di luar kegiatan akademik</p>
        </div>

        <div class="row g-4">
            <?php
            $organisasi = [
                ['fas fa-sitemap', 'HMJ Manajemen Informatika',
                 'Himpunan Mahasiswa Jurusan MI sebagai wadah kegiatan dan pengembangan mahasiswa di tingkat jurusan.',
                 'Aktif', 'Pembina: Sony Oktapriandi, S.Kom., M.Kom.'],

                ['fas fa-code-branch', 'UKM Programming',
                 'Unit Kegiatan Mahasiswa fokus pada pengembangan kemampuan pemrograman dan persiapan kompetisi coding.',
                 'Aktif', '120+ Anggota'],

                ['fas fa-camera', 'UKM Multimedia',
                 'Wadah bagi mahasiswa yang berminat di bidang desain grafis, fotografi, videografi, dan konten kreatif digital.',
                 'Aktif', '85+ Anggota'],

                ['fas fa-hands-helping', 'Volunteer IT',
                 'Kelompok mahasiswa yang bergerak dalam pengabdian masyarakat berbasis teknologi informasi untuk desa dan UMKM.',
                 'Aktif', '60+ Relawan'],
            ];
            foreach ($organisasi as $o): ?>
            <div class="col-lg-3 col-sm-6 fade-up">
                <div class="glass-card card-shine" style="padding:28px;text-align:center;height:100%;">
                    <div style="width:64px;height:64px;
                         background:linear-gradient(135deg,var(--clr-primary),var(--clr-primary-dark));
                         border-radius:50%;display:flex;align-items:center;
                         justify-content:center;color:white;font-size:1.5rem;
                         margin:0 auto 18px;">
                        <i class="<?= $o[0] ?>"></i>
                    </div>
                    <h5 style="font-size:0.95rem;margin-bottom:10px;"><?= $o[1] ?></h5>
                    <p style="font-size:0.82rem;color:var(--clr-text-muted);
                       margin-bottom:14px;line-height:1.6;">
                        <?= $o[2] ?>
                    </p>
                    <span style="background:#ECFDF5;color:#065F46;font-size:0.72rem;
                          font-weight:700;padding:4px 12px;border-radius:50px;
                          display:inline-block;margin-bottom:10px;">
                        <?= $o[3] ?>
                    </span>
                    <div style="font-size:0.78rem;color:var(--clr-text-light);">
                        <?= $o[4] ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Stats bar mahasiswa -->
        <div class="row g-4 mt-5">
            <?php
            $statsRows = [
                ['fas fa-users',      '800+',  'Mahasiswa Aktif',     'var(--clr-primary)', 'var(--clr-primary-xlight)'],
                ['fas fa-graduation-cap','4000+','Total Alumni',       'var(--clr-accent-dark)', '#FEF3C7'],
                ['fas fa-briefcase',  '>90%',  'Tingkat Serapan Kerja','#7C3AED', '#EDE9FE'],
                ['fas fa-medal',      '150+',  'Prestasi Nasional',   '#E11D48', '#FFF1F2'],
            ];
            foreach ($statsRows as $s): ?>
            <div class="col-md-3 col-6 fade-up">
                <div class="glass-card" style="padding:24px;text-align:center;">
                    <div style="width:52px;height:52px;background:<?= $s[4] ?>;
                         border-radius:14px;display:flex;align-items:center;
                         justify-content:center;color:<?= $s[3] ?>;
                         font-size:1.3rem;margin:0 auto 14px;">
                        <i class="<?= $s[0] ?>"></i>
                    </div>
                    <div style="font-family:var(--font-display);font-size:2rem;
                         font-weight:800;color:<?= $s[3] ?>;line-height:1;">
                        <?= $s[1] ?>
                    </div>
                    <div style="font-size:0.82rem;color:var(--clr-text-muted);
                          margin-top:6px;">
                        <?= $s[2] ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include __DIR__.'/includes/footer.php'; ?>
