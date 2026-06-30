<?php
// about.php — Halaman Profil Jurusan
require_once __DIR__.'/includes/config.php';
$pageTitle = 'Profil Jurusan';

$db = getDB();
$dosenList = $db->query("SELECT * FROM dosen")->fetchAll();

// Helper to find dosen details by name substring
function findDosen($sub, $list) {
    foreach ($list as $d) {
        if (stripos($d['nama'], $sub) !== false) {
            return $d;
        }
    }
    return null;
}

// Find key leaders
$ketua = findDosen('Sony Oktapriandi', $dosenList);
$sekretaris = findDosen('Sulistiyanto', $dosenList);
$koorD4 = findDosen('Herlinda Kusmiati', $dosenList);

// Fallbacks if not found
$orgData = [
    'ketua' => [
        'role' => 'Ketua Jurusan',
        'nama' => $ketua ? $ketua['nama'] : 'Sony Oktapriandi, S.Kom., M.Kom.',
        'foto' => ($ketua && !empty($ketua['foto'])) ? $ketua['foto'] : 'Sony.jpg',
        'nip' => $ketua ? $ketua['nip'] : '197510272008121000',
        'nidn' => $ketua ? $ketua['nidn'] : '0027107512',
        'email' => $ketua ? $ketua['email'] : 'sony_o@polsri.ac.id',
        'keahlian' => $ketua ? $ketua['bidang_keahlian'] : 'Sistem Informasi, Software Engineering, IT Governance'
    ],
    'sekretaris' => [
        'role' => 'Sekretaris Jurusan',
        'nama' => $sekretaris ? $sekretaris['nama'] : 'Sulistiyanto, S.Kom., M.T.I.',
        'foto' => ($sekretaris && !empty($sekretaris['foto'])) ? $sekretaris['foto'] : 'Sulistiyanto.jpg',
        'nip' => $sekretaris ? $sekretaris['nip'] : '199302232022031000',
        'nidn' => $sekretaris ? $sekretaris['nidn'] : '0223029301',
        'email' => $sekretaris ? $sekretaris['email'] : 'sulistiyanto@polsri.ac.id',
        'keahlian' => $sekretaris ? $sekretaris['bidang_keahlian'] : 'Mobile Development, Cloud Computing, Distributed Systems'
    ],
    'koor_d4' => [
        'role' => 'Koordinator D4 MI',
        'nama' => $koorD4 ? $koorD4['nama'] : 'Herlinda Kusmiati, S.Kom., M.Kom.',
        'foto' => ($koorD4 && !empty($koorD4['foto'])) ? $koorD4['foto'] : 'Herlinda.jpg',
        'nip' => $koorD4 ? $koorD4['nip'] : '198909042022032000',
        'nidn' => $koorD4 ? $koorD4['nidn'] : '0204098901',
        'email' => $koorD4 ? $koorD4['email'] : 'herlinda_k@polsri.ac.id',
        'keahlian' => $koorD4 ? $koorD4['bidang_keahlian'] : 'Data Mining, Business Intelligence, Database Systems'
    ],
    'koor_d3' => [
        'role' => 'Koordinator D3 MI',
        'nama' => 'Indah Purnamasari, S.Kom., M.Cs.',
        'foto' => 'default-dosen.jpg',
        'nip' => '198808122020122000',
        'nidn' => '0212088801',
        'email' => 'indah_p@polsri.ac.id',
        'keahlian' => 'Human-Computer Interaction, Web Development, UI/UX Design'
    ],
    'ka_lab' => [
        'role' => 'Ka. Lab. Komputer',
        'nama' => 'Oka Sudarsana, S.Kom., M.T.',
        'foto' => 'default-dosen.jpg',
        'nip' => '198504152018031000',
        'nidn' => '0215048502',
        'email' => 'oka_sudarsana@polsri.ac.id',
        'keahlian' => 'Computer Networks, Cybersecurity, System Administration'
    ],
    'ka_perpus' => [
        'role' => 'Ka. Perpustakaan Jurusan',
        'nama' => 'Rahmawati, S.Sos., M.A.',
        'foto' => 'default-dosen.jpg',
        'nip' => '198305202015042000',
        'nidn' => '0220058301',
        'email' => 'rahmawati@polsri.ac.id',
        'keahlian' => 'Library Science, Information Management, Digital Archiving'
    ],
    'ka_tu' => [
        'role' => 'Ka. Tata Usaha',
        'nama' => 'Ahmad Faisal, S.E.',
        'foto' => 'default-dosen.jpg',
        'nip' => '197911052009041000',
        'nidn' => 'N/A',
        'email' => 'faisal_ahmad@polsri.ac.id',
        'keahlian' => 'Office Administration, Academic Services, Finance'
    ]
];

// Dynamically override remaining from DB if matching Dosen exists
foreach ($orgData as $key => $default) {
    if (in_array($key, ['ketua', 'sekretaris', 'koor_d4'])) continue; // Already handled
    foreach ($dosenList as $d) {
        $shortName = explode(',', $default['nama'])[0];
        $shortName = trim(str_replace(['Ir.', 'Dr.', 'Drs.', 'S.Kom.', 'M.Kom.', 'M.T.I.', 'M.T.', 'S.Sos.', 'M.A.', 'S.E.', 'M.Cs.'], '', $shortName));
        if (stripos($d['nama'], $shortName) !== false) {
            $orgData[$key]['nama'] = $d['nama'];
            if (!empty($d['foto'])) $orgData[$key]['foto'] = $d['foto'];
            if (!empty($d['nip'])) $orgData[$key]['nip'] = $d['nip'];
            if (!empty($d['nidn'])) $orgData[$key]['nidn'] = $d['nidn'];
            if (!empty($d['email'])) $orgData[$key]['email'] = $d['email'];
            if (!empty($d['bidang_keahlian'])) $orgData[$key]['keahlian'] = $d['bidang_keahlian'];
        }
    }
}

include __DIR__.'/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <span class="page-hero-badge">
            <i class="fas fa-building-columns"></i> Profil Jurusan
        </span>
        <h1>Manajemen Informatika<br>
            <span style="color:var(--clr-primary);">Politeknik Negeri Sriwijaya</span>
        </h1>
        <p>Mengenal lebih dalam sejarah, visi misi, dan kompetensi unggulan
           Jurusan Manajemen Informatika POLSRI sejak 1982.</p>
    </div>
</section>

<!-- ── SEJARAH & VISI MISI ── -->
<section class="section-py" id="sejarah" style="background:var(--clr-bg);">
    <div class="container">
        <div class="row g-5 align-items-start">

            <!-- Kiri: Timeline Sejarah -->
            <div class="col-lg-5 fade-up">
                <div class="section-header">
                    <span class="badge-label">Sejarah Jurusan</span>
                    <h2>Perjalanan Panjang<br>Menuju Keunggulan</h2>
                    <p>Lebih dari empat dekade membangun generasi
                       profesional teknologi informasi Indonesia.</p>
                </div>

                <div class="timeline">
                    <?php
                    $sejarah = [
                        ['1982','Pendirian Jurusan',
                         'Jurusan Manajemen Informatika berdiri sebagai salah satu jurusan pertama di Politeknik Negeri Sriwijaya (saat itu Politeknik Universitas Sriwijaya).'],
                        ['1997','Program D3 Resmi',
                         'Program Diploma III Manajemen Informatika mendapat akreditasi resmi dari Direktorat Jenderal Pendidikan Tinggi.'],
                        ['2010','Akreditasi B',
                         'Meraih akreditasi B dari BAN-PT untuk program D3 Manajemen Informatika, menegaskan kualitas pendidikan.'],
                        ['2018','Program D4 Dibuka',
                         'Pembukaan Program Sarjana Terapan (D4) Manajemen Informatika untuk memenuhi kebutuhan industri akan lulusan bergelar sarjana.'],
                        ['2024','Era Digital Baru',
                         'Pembaruan kurikulum besar-besaran yang mengintegrasikan AI, Cloud Computing, dan Cybersecurity sesuai kebutuhan industri 4.0.'],
                    ];
                    foreach ($sejarah as $s): ?>
                    <div class="timeline-item">
                        <div class="timeline-dot"></div>
                        <span class="timeline-year"><?= $s[0] ?></span>
                        <div class="timeline-content">
                            <h5><?= $s[1] ?></h5>
                            <p><?= $s[2] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Kanan: Visi Misi -->
            <div class="col-lg-7 fade-up" id="visi-misi">
                <div class="row g-4">
                    <!-- Visi -->
                    <div class="col-12">
                        <div class="visi-misi-card visi-card glass-card card-shine">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                                <div style="width:44px;height:44px;background:rgba(255,255,255,0.2);
                                     border-radius:12px;display:flex;align-items:center;
                                     justify-content:center;font-size:1.2rem;">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <h3 style="margin:0;font-size:1.3rem;">Visi</h3>
                            </div>
                            <p style="font-size:0.95rem;line-height:1.75;opacity:0.95;margin:0;">
                                "Menjadi Jurusan Manajemen Informatika yang unggul, inovatif,
                                dan berdaya saing global dalam menghasilkan lulusan yang
                                profesional di bidang teknologi informasi dan manajemen
                                bisnis pada tahun 2027."
                            </p>
                        </div>
                    </div>
                    <!-- Misi -->
                    <div class="col-12">
                        <div class="visi-misi-card misi-card glass-card card-shine">
                            <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
                                <div style="width:44px;height:44px;background:var(--clr-primary-xlight);
                                     border-radius:12px;display:flex;align-items:center;
                                     justify-content:center;color:var(--clr-primary);font-size:1.2rem;">
                                    <i class="fas fa-bullseye"></i>
                                </div>
                                <h3 style="margin:0;font-size:1.3rem;">Misi</h3>
                            </div>
                            <ul>
                                <?php
                                $misi = [
                                    'Menyelenggarakan pendidikan vokasi berkualitas tinggi di bidang manajemen informatika',
                                    'Mengembangkan penelitian terapan yang relevan dengan kebutuhan industri dan masyarakat',
                                    'Melaksanakan pengabdian kepada masyarakat berbasis teknologi informasi',
                                    'Membangun kemitraan strategis dengan industri nasional dan internasional',
                                    'Menghasilkan lulusan berkarakter, kompeten, dan berintegritas tinggi',
                                ];
                                foreach ($misi as $i => $m): ?>
                                <li>
                                    <span class="misi-num"><?= $i + 1 ?></span>
                                    <span><?= $m ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ── AKREDITASI ── -->
<section class="section-py-sm" id="akreditasi" style="background:var(--clr-bg-soft);">
    <div class="container">
        <div class="section-header text-center fade-up">
            <span class="badge-label">Pengakuan Mutu</span>
            <h2>Akreditasi &amp; Sertifikasi</h2>
            <p>Pengakuan kualitas pendidikan dari lembaga resmi nasional</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php
            $akreditasi = [
                ['fas fa-award',      'B',    'Akreditasi BAN-PT',   'D3 Manajemen Informatika', 'blue'],
                ['fas fa-star',       'B',    'Akreditasi BAN-PT',   'D4 Manajemen Informatika', 'gold'],
                ['fas fa-shield-alt', 'ISO',  'ISO 9001:2015',       'Sistem Manajemen Mutu',    'purple'],
            ];
            foreach ($akreditasi as $a): ?>
            <div class="col-md-4 fade-up">
                <div class="glass-card card-shine" style="padding:36px;text-align:center;">
                    <div class="akred-icon-wrap akred-icon-<?= $a[4] ?>">
                        <i class="<?= $a[0] ?>"></i>
                    </div>
                    <div class="akred-rating akred-rating-<?= $a[4] ?>"><?= $a[1] ?></div>
                    <h5 style="margin:14px 0 6px;font-size:1rem;"><?= $a[2] ?></h5>
                    <p style="color:var(--clr-text-muted);font-size:0.85rem;margin:0;"><?= $a[3] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── STRUKTUR ORGANISASI ── -->
<section class="section-py" id="struktur-organisasi" style="background:var(--clr-bg);">
    <div class="container">
        <div class="section-header text-center fade-up">
            <span class="badge-label">Organisasi</span>
            <h2>Struktur Organisasi Jurusan</h2>
            <p>Klik pada setiap kartu jabatan untuk melihat profil dan detail kontak pengelola Jurusan Manajemen Informatika.</p>
        </div>

        <div class="org-tree fade-up">
            <!-- Level 0: Ketua Jurusan -->
            <div class="org-level">
                <div class="org-card" data-org="ketua">
                    <div class="org-card-avatar">
                        <img src="<?= APP_URL ?>/assets/images/<?= e($orgData['ketua']['foto']) ?>" onerror="this.outerHTML='<i class=\'fas fa-user-tie\'></i>'">
                    </div>
                    <div class="org-card-role"><?= e($orgData['ketua']['role']) ?></div>
                    <div class="org-card-name"><?= e($orgData['ketua']['nama']) ?></div>
                </div>
            </div>

            <!-- Level 1: Sekretaris Jurusan -->
            <div class="org-level">
                <div class="org-card" data-org="sekretaris">
                    <div class="org-card-avatar">
                        <img src="<?= APP_URL ?>/assets/images/<?= e($orgData['sekretaris']['foto']) ?>" onerror="this.outerHTML='<i class=\'fas fa-user-tie\'></i>'">
                    </div>
                    <div class="org-card-role"><?= e($orgData['sekretaris']['role']) ?></div>
                    <div class="org-card-name"><?= e($orgData['sekretaris']['nama']) ?></div>
                </div>
            </div>

            <!-- Level 2: Bidang / Seksi Koor -->
            <div class="org-level" style="flex-wrap: wrap; justify-content: center; gap: 20px;">
                <?php 
                $subNodes = ['koor_d4', 'koor_d3', 'ka_lab', 'ka_perpus', 'ka_tu'];
                foreach ($subNodes as $nodeKey): 
                    $node = $orgData[$nodeKey];
                ?>
                <div class="org-card" data-org="<?= $nodeKey ?>" style="width: 210px;">
                    <div class="org-card-avatar" style="width: 50px; height: 50px;">
                        <img src="<?= APP_URL ?>/assets/images/<?= e($node['foto']) ?>" onerror="this.outerHTML='<i class=\'fas fa-user-tie\'></i>'">
                    </div>
                    <div class="org-card-role" style="font-size:0.65rem;"><?= e($node['role']) ?></div>
                    <div class="org-card-name" style="font-size:0.8rem;"><?= e($node['nama']) ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- ORG DETAIL DRAWER -->
<div class="drawer-overlay" id="orgDrawer">
    <div class="drawer-panel">
        <button class="drawer-close" id="orgDrawerClose" aria-label="Tutup">
            <i class="fas fa-times"></i>
        </button>
        
        <div style="display:flex; flex-direction:column; gap:28px; height:100%; justify-content:space-between; margin-top:20px; text-align:center;">
            <div>
                <div id="drawerOrgAvatar" style="width:120px; height:120px; border-radius:50%; border:3px solid var(--clr-primary); margin:0 auto 16px; overflow:hidden; background:var(--clr-primary-xlight); display:flex; align-items:center; justify-content:center; font-size:3rem; color:var(--clr-primary); box-shadow:0 8px 24px var(--clr-shadow);">
                    <i class="fas fa-user-tie"></i>
                </div>
                
                <span id="drawerOrgRole" class="org-card-role" style="font-size:0.8rem; display:inline-block; margin-bottom:8px;">Ketua Jurusan</span>
                <h4 id="drawerOrgName" style="font-size:1.2rem; font-weight:800; color:var(--clr-text); line-height:1.4; margin-bottom:24px;">Sony Oktapriandi, S.Kom., M.Kom.</h4>
                
                <div style="text-align:left; display:flex; flex-direction:column; gap:16px; background:var(--clr-bg-soft); padding:20px; border-radius:12px; border:1px solid var(--clr-border);">
                    <div>
                        <div style="font-size:0.7rem; font-weight:700; color:var(--clr-text-light); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">NIP</div>
                        <p id="drawerOrgNip" style="font-size:0.85rem; color:var(--clr-text); font-weight:600; margin:0;">197510272008121000</p>
                    </div>
                    <div>
                        <div style="font-size:0.7rem; font-weight:700; color:var(--clr-text-light); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">NIDN</div>
                        <p id="drawerOrgNidn" style="font-size:0.85rem; color:var(--clr-text); font-weight:600; margin:0;">0027107512</p>
                    </div>
                    <div>
                        <div style="font-size:0.7rem; font-weight:700; color:var(--clr-text-light); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:4px;">Bidang Keahlian</div>
                        <p id="drawerOrgKeahlian" style="font-size:0.85rem; color:var(--clr-text-muted); line-height:1.5; margin:0;">Sistem Informasi, Software Engineering, IT Governance</p>
                    </div>
                </div>
            </div>
            
            <div style="border-top:1px solid var(--clr-border); padding-top:20px; display:flex; flex-direction:column; gap:12px;">
                <a id="drawerOrgEmailBtn" href="mailto:sony_o@polsri.ac.id" class="btn-primary-custom w-100" style="justify-content:center;">
                    <i class="fas fa-envelope"></i> Kirim Email
                </a>
                <button class="btn-outline-custom w-100" id="orgDrawerCloseBtn" style="justify-content:center; padding:10px 20px;">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ── KOMPETENSI LULUSAN ── -->
<section class="section-py" style="background:var(--clr-bg-soft);">
    <div class="container">
        <div class="section-header text-center fade-up">
            <span class="badge-label">Capaian Pembelajaran</span>
            <h2>Kompetensi Lulusan</h2>
            <p>Yang akan kamu kuasai setelah lulus dari Jurusan Manajemen Informatika</p>
        </div>

        <div class="row g-4">
            <?php
            $kompetensi = [
                ['fas fa-code',           'Web & Mobile Development',
                 'Mampu merancang dan membangun aplikasi web dan mobile yang modern, skalabel, dan user-friendly.'],
                ['fas fa-database',       'Manajemen Basis Data',
                 'Menguasai perancangan, implementasi, dan optimasi database relasional dan non-relasional.'],
                ['fas fa-network-wired',  'Jaringan Komputer',
                 'Memahami arsitektur jaringan, keamanan sistem, dan administrasi server.'],
                ['fas fa-chart-bar',      'Analisis Data',
                 'Mampu mengolah dan menganalisis data untuk pengambilan keputusan bisnis berbasis data.'],
                ['fas fa-project-diagram','Manajemen Proyek IT',
                 'Menguasai metodologi manajemen proyek teknologi informasi (Scrum, Agile, PMBOK).'],
                ['fas fa-shield-alt',     'Keamanan Informasi',
                 'Memahami prinsip keamanan informasi dan praktik terbaik dalam melindungi aset digital.'],
            ];
            foreach ($kompetensi as $k): ?>
            <div class="col-lg-4 col-md-6 fade-up">
                <div class="glass-card card-shine" style="padding:28px; height: 100%;">
                    <div class="komp-icon-wrap">
                        <i class="<?= $k[0] ?>"></i>
                    </div>
                    <h5 style="font-size:1.05rem;margin-bottom:10px;"><?= $k[1] ?></h5>
                    <p style="font-size:0.85rem;color:var(--clr-text-muted);margin:0;line-height:1.75;">
                        <?= $k[2] ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ── CTA ── -->
<section class="section-py-sm cta-section">
    <div class="container" style="position:relative; z-index:2;">
        <h2 style="color:white;margin-bottom:12px;">Tertarik Bergabung?</h2>
        <p style="color:rgba(255,255,255,0.85);margin-bottom:28px;font-size:1rem;">
            Hubungi kami untuk informasi lebih lanjut tentang program studi.
        </p>
        <a href="<?= APP_URL ?>/kontak.php" class="cta-btn">
            <i class="fas fa-envelope"></i> Hubungi Kami
        </a>
    </div>
</section>
<script>
const orgData = <?= json_encode($orgData) ?>;

document.addEventListener('DOMContentLoaded', () => {
    const orgCards = document.querySelectorAll('.org-card');
    const drawer = document.getElementById('orgDrawer');
    const dAvatar = document.getElementById('drawerOrgAvatar');
    const dRole = document.getElementById('drawerOrgRole');
    const dName = document.getElementById('drawerOrgName');
    const dNip = document.getElementById('drawerOrgNip');
    const dNidn = document.getElementById('drawerOrgNidn');
    const dKeahlian = document.getElementById('drawerOrgKeahlian');
    const dEmailBtn = document.getElementById('drawerOrgEmailBtn');
    const dClose = document.getElementById('orgDrawerClose');
    const dCloseBtn = document.getElementById('orgDrawerCloseBtn');

    orgCards.forEach(card => {
        card.addEventListener('click', () => {
            const orgKey = card.dataset.org;
            const data = orgData[orgKey];
            if (!data) return;

            dRole.textContent = data.role;
            dName.textContent = data.nama;
            dNip.textContent = data.nip || 'N/A';
            dNidn.textContent = data.nidn || 'N/A';
            dKeahlian.textContent = data.keahlian || 'N/A';
            dEmailBtn.href = `mailto:${data.email}`;

            if (data.foto && data.foto !== 'default-dosen.jpg') {
                dAvatar.innerHTML = `<img src="<?= APP_URL ?>/assets/images/${data.foto}" style="width:100%; height:100%; object-fit:cover;" onerror="this.outerHTML='<i class=\\'fas fa-user-tie\\'></i>'">`;
            } else {
                dAvatar.innerHTML = `<i class="fas fa-user-tie"></i>`;
            }

            drawer.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    });

    function closeOrgDrawer() {
        drawer.classList.remove('active');
        document.body.style.overflow = '';
    }

    dClose.addEventListener('click', closeOrgDrawer);
    dCloseBtn.addEventListener('click', closeOrgDrawer);
    drawer.addEventListener('click', e => { if (e.target === drawer) closeOrgDrawer(); });
});
</script>

<?php include __DIR__.'/includes/footer.php'; ?>
