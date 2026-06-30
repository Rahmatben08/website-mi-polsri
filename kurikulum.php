<?php
// kurikulum.php — Halaman Kurikulum Interaktif
require_once __DIR__.'/includes/config.php';
$pageTitle = 'Kurikulum Interaktif';

$curriculumData = [
    'd3' => [
        1 => [
            ['code' => 'MI-101', 'name' => 'Pemrograman Dasar', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Tidak Ada', 'desc' => 'Mata kuliah ini membahas dasar-dasar logika pemrograman menggunakan bahasa C/C++.', 'comp' => 'Menguasai konsep dasar algoritma, percabangan, perulangan, dan array.'],
            ['code' => 'MI-102', 'name' => 'Pengantar Teknologi Informasi', 'sks' => 2, 'type' => 'teori', 'prereq' => 'Tidak Ada', 'desc' => 'Pengenalan konsep dasar sistem komputer, hardware, software, dan jaringan.', 'comp' => 'Memahami arsitektur komputer dan sejarah perkembangan teknologi informasi.'],
            ['code' => 'MI-103', 'name' => 'Sistem Operasi', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Tidak Ada', 'desc' => 'Mempelajari administrasi Linux, shell scripting, manajemen proses dan memori.', 'comp' => 'Mampu mengoperasikan Linux CLI, administrasi server dasar, dan penulisan skrip bash.'],
            ['code' => 'MI-104', 'name' => 'Matematika Diskrit', 'sks' => 2, 'type' => 'teori', 'prereq' => 'Tidak Ada', 'desc' => 'Membahas logika matematika, teori himpunan, relasi, fungsi, dan graf.', 'comp' => 'Memiliki fondasi berpikir logis untuk penyelesaian masalah pemrograman tingkat lanjut.'],
        ],
        2 => [
            ['code' => 'MI-201', 'name' => 'Struktur Data & Algoritma', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Pemrograman Dasar', 'desc' => 'Membahas implementasi stack, queue, linked list, tree, searching, dan sorting.', 'comp' => 'Mampu mengimplementasikan struktur data kompleks untuk efisiensi performa program.'],
            ['code' => 'MI-202', 'name' => 'Basis Data Dasar', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Tidak Ada', 'desc' => 'Mempelajari konsep Entity Relationship Diagram (ERD) dan sintaks SQL (DDL & DML).', 'comp' => 'Mampu mendesain skema database relasional dan mengoptimalkan query dasar.'],
            ['code' => 'MI-203', 'name' => 'Pemrograman Web Client-Side', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Pemrograman Dasar', 'desc' => 'Mempelajari HTML5, CSS3, JavaScript modern, dan integrasi Bootstrap.', 'comp' => 'Mampu mendesain interface web responsif yang modern dan interaktif.'],
        ],
        3 => [
            ['code' => 'MI-301', 'name' => 'Pemrograman Berorientasi Objek', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Struktur Data & Algoritma', 'desc' => 'Mengajarkan paradigma OOP (Inheritance, Polymorphism, Encapsulation) dengan Java.', 'comp' => 'Mampu menulis kode berstandar industri dengan pola desain berorientasi objek.'],
            ['code' => 'MI-302', 'name' => 'Sistem Informasi Manajemen', 'sks' => 2, 'type' => 'teori', 'prereq' => 'Tidak Ada', 'desc' => 'Menganalisis peran teknologi informasi dalam manajemen keputusan organisasi.', 'comp' => 'Memahami tata kelola sistem informasi, ERP, dan integrasi alur kerja digital.'],
            ['code' => 'MI-303', 'name' => 'Jaringan Komputer', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Tidak Ada', 'desc' => 'Mempelajari model OSI, TCP/IP, IP addressing, subnetting, dan konfigurasi switch/router.', 'comp' => 'Mampu membangun topologi jaringan skala lokal (LAN) serta troubleshooting konektivitas.'],
        ],
        4 => [
            ['code' => 'MI-401', 'name' => 'Rekayasa Perangkat Lunak', 'sks' => 3, 'type' => 'teori', 'prereq' => 'Basis Data Dasar', 'desc' => 'Membahas daur hidup pengembangan perangkat lunak (SDLC), Agile, Scrum, dan UML.', 'comp' => 'Mampu menganalisis kebutuhan sistem dan memodelkan dokumen rancangan perangkat lunak.'],
            ['code' => 'MI-402', 'name' => 'Pemrograman Web Server-Side', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Basis Data Dasar & Pemrograman Web Client', 'desc' => 'Membangun aplikasi web dinamis dengan PHP (Laravel) dan integrasi database.', 'comp' => 'Mampu membuat RESTful API, otentikasi aman, dan manajemen state server-side.'],
            ['code' => 'MI-403', 'name' => 'Sistem Keamanan Jaringan', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Jaringan Komputer', 'desc' => 'Keamanan infrastruktur, konfigurasi firewall, enkripsi data, dan deteksi intrusi.', 'comp' => 'Mampu mengamankan jaringan server dan menganalisis celah keamanan sistem.'],
        ],
        5 => [
            ['code' => 'MI-501', 'name' => 'Metodologi Penelitian & Proyek Akhir', 'sks' => 2, 'type' => 'teori', 'prereq' => 'Rekayasa Perangkat Lunak', 'desc' => 'Persiapan penulisan proposal Laporan Akhir, tinjauan pustaka, dan teknik analisis.', 'comp' => 'Mampu menyusun proposal penelitian ilmiah secara sistematis dan terstruktur.'],
            ['code' => 'MI-502', 'name' => 'Kewirausahaan IT (Technopreneurship)', 'sks' => 2, 'type' => 'teori', 'prereq' => 'Tidak Ada', 'desc' => 'Mempelajari cara membangun startup digital, business canvas model, dan pitch deck.', 'comp' => 'Mampu merancang model bisnis IT yang layak dan mempresentasikan produk digital.'],
            ['code' => 'MI-503', 'name' => 'Kerja Praktek (PKL)', 'sks' => 4, 'type' => 'praktikum', 'prereq' => 'Telah menempuh 80 SKS', 'desc' => 'Praktik kerja langsung di industri mitra selama minimal 3 bulan.', 'comp' => 'Mendapatkan pengalaman kerja nyata dan melatih etika profesionalitas di industri.'],
        ],
        6 => [
            ['code' => 'MI-601', 'name' => 'Laporan Akhir (Tugas Akhir)', 'sks' => 6, 'type' => 'praktikum', 'prereq' => 'Lulus PKL & Proposal', 'desc' => 'Penyusunan proyek aplikasi final dan dokumentasi karya ilmiah.', 'comp' => 'Mampu merancang solusi teknologi komprehensif dan mempertahankannya di sidang akhir.'],
        ]
    ],
    'd4' => [
        1 => [
            ['code' => 'M4-101', 'name' => 'Algoritma & Pemrograman Lanjut', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Tidak Ada', 'desc' => 'Mempelajari pemrograman lanjut, rekursi, pointer, alokasi memori dinamis, dan file I/O.', 'comp' => 'Menguasai teknik pemrograman tingkat menengah ke atas menggunakan bahasa C/C++.'],
            ['code' => 'M4-102', 'name' => 'Sistem Fisik Siber & IoT', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Tidak Ada', 'desc' => 'Integrasi hardware mikrokontroler (Arduino/ESP32), sensor, dan pemrosesan data.', 'comp' => 'Mampu merakit sirkuit IoT dasar dan mengirimkan data sensor ke cloud platform.'],
        ],
        2 => [
            ['code' => 'M4-201', 'name' => 'Struktur Data Terapan', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Algoritma & Pemrograman Lanjut', 'desc' => 'Menggunakan struktur data canggih (BST, Red-Black Trees, Graphs) untuk optimasi memori.', 'comp' => 'Mampu memecahkan masalah komputasional kompleks menggunakan struktur data yang optimal.'],
            ['code' => 'M4-202', 'name' => 'Basis Data Lanjut', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Tidak Ada', 'desc' => 'Membahas normalisasi data tingkat tinggi, indexing, trigger, stored procedure, dan NoSQL.', 'comp' => 'Mampu merancang arsitektur database berskala enterprise dan mengoptimalkan query kompleks.'],
        ],
        3 => [
            ['code' => 'M4-301', 'name' => 'Pemrograman Berorientasi Objek Lanjut', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Struktur Data Terapan', 'desc' => 'Penerapan Design Patterns, multithreading, concurrency, dan GUI menggunakan Java/C#.', 'comp' => 'Mampu mengembangkan aplikasi desktop skala besar menggunakan arsitektur OOP modern.'],
            ['code' => 'M4-302', 'name' => 'Analisis & Desain Berorientasi Objek', 'sks' => 2, 'type' => 'teori', 'prereq' => 'Tidak Ada', 'desc' => 'Menggunakan UML untuk memodelkan proses bisnis dan arsitektur perangkat lunak.', 'comp' => 'Mampu merancang diagram arsitektur perangkat lunak dengan pendekatan OOP.'],
        ],
        4 => [
            ['code' => 'M4-401', 'name' => 'Pemrograman Framework Web (Enterprise)', 'sks' => 4, 'type' => 'praktikum', 'prereq' => 'Basis Data Lanjut', 'desc' => 'Menggunakan framework modern seperti Laravel/Next.js/Spring Boot untuk sistem berskala besar.', 'comp' => 'Mampu mendesain dan mengimplementasikan aplikasi web arsitektur MVC atau Serverless.'],
            ['code' => 'M4-402', 'name' => 'Statistika Industri & Big Data', 'sks' => 3, 'type' => 'teori', 'prereq' => 'Tidak Ada', 'desc' => 'Membahas pengolahan data statistik, probabilitas, dan konsep Big Data Hadoop/Spark.', 'comp' => 'Mampu melakukan analisis prediktif dan memproses dataset besar untuk business intelligence.'],
        ],
        5 => [
            ['code' => 'M4-501', 'name' => 'Pemrograman Mobile Terdistribusi', 'sks' => 4, 'type' => 'praktikum', 'prereq' => 'PBO Lanjut', 'desc' => 'Pengembangan aplikasi mobile lintas platform (Flutter/React Native) terhubung ke API.', 'comp' => 'Mampu membuat aplikasi Android/iOS dengan backend terintegrasi dan sinkronisasi data offline.'],
            ['code' => 'M4-502', 'name' => 'Kecerdasan Buatan & Machine Learning', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Statistika Industri', 'desc' => 'Dasar-dasar regresi, klasifikasi, clustering, neural networks menggunakan Python (Scikit-Learn).', 'comp' => 'Mampu membangun model kecerdasan buatan untuk pengenalan pola dan klasifikasi data.'],
        ],
        6 => [
            ['code' => 'M4-601', 'name' => 'Arsitektur Cloud & DevOps', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Tidak Ada', 'desc' => 'Docker containerization, CI/CD pipelines, dan deployment aplikasi di AWS/Google Cloud.', 'comp' => 'Mampu melakukan otomatisasi deploy aplikasi dan mengelola infrastruktur cloud modern.'],
            ['code' => 'M4-602', 'name' => 'Keamanan Siber & Kriptografi', 'sks' => 3, 'type' => 'praktikum', 'prereq' => 'Tidak Ada', 'desc' => 'Prinsip kriptografi simetris/asimetris, penetration testing, pertahanan jaringan.', 'comp' => 'Mampu melakukan audit keamanan perangkat lunak dan mengamankan transmisi data.'],
        ],
        7 => [
            ['code' => 'M4-701', 'name' => 'Magang Industri (Full Semester)', 'sks' => 8, 'type' => 'praktikum', 'prereq' => 'Telah menempuh 110 SKS', 'desc' => 'Magang kerja profesional penuh waktu di perusahaan teknologi nasional/internasional.', 'comp' => 'Mengasah keterampilan teknis langsung pada proyek riil industri dan soft skills kerja tim.'],
        ],
        8 => [
            ['code' => 'M4-801', 'name' => 'Skripsi & Proyek Desain Akhir', 'sks' => 6, 'type' => 'praktikum', 'prereq' => 'Lulus Magang', 'desc' => 'Implementasi skripsi hasil penelitian terapan orisinal di bidang manajemen informatika.', 'comp' => 'Mampu mempublikasikan karya ilmiah terapan dan mempertahankannya di hadapan penguji.'],
        ]
    ]
];

include __DIR__.'/includes/header.php';
?>

<!-- PAGE HERO -->
<section class="page-hero">
    <div class="container">
        <span class="page-hero-badge">
            <i class="fas fa-graduation-cap"></i> Kurikulum Akademik
        </span>
        <h1>Papan Kurikulum<br>
            <span style="color:var(--clr-primary);">Interaktif &amp; OBE</span>
        </h1>
        <p>Jelajahi mata kuliah, jumlah SKS, prasyarat, dan kompetensi lulusan di setiap semester untuk Program Studi D3 dan D4 Manajemen Informatika.</p>
    </div>
</section>

<!-- CURRICULUM BOARD SECTION -->
<section class="section-py" style="background:var(--clr-bg);">
    <div class="container">
        
        <!-- PRODI SELECTOR TABS -->
        <div class="curr-tabs fade-up">
            <button class="curr-tab-btn active" data-prodi="d3">
                <i class="fas fa-certificate me-1"></i> Diploma III (D3)
            </button>
            <button class="curr-tab-btn" data-prodi="d4">
                <i class="fas fa-award me-1"></i> Sarjana Terapan (D4)
            </button>
        </div>

        <!-- FILTER BAR -->
        <div class="curr-filters fade-up">
            <div style="flex: 1; min-width: 250px;">
                <div style="font-size:0.75rem; font-weight:700; color:var(--clr-text-light); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Pilih Semester</div>
                <div class="semester-pill-container" id="semesterPillContainer">
                    <!-- Dynamic semesters based on active prodi -->
                </div>
            </div>
            
            <div style="min-width: 200px;">
                <div style="font-size:0.75rem; font-weight:700; color:var(--clr-text-light); text-transform:uppercase; letter-spacing:0.05em; margin-bottom:8px;">Kategori Kuliah</div>
                <div class="d-flex gap-2">
                    <button class="sem-pill active" data-filter="all">Semua</button>
                    <button class="sem-pill" data-filter="teori">Teori</button>
                    <button class="sem-pill" data-filter="praktikum">Praktikum</button>
                </div>
            </div>
        </div>

        <!-- BOARD GRID -->
        <div class="curr-grid fade-up" id="curriculumGrid">
            <!-- Dynamic course cards injected here via JS -->
        </div>

    </div>
</section>

<!-- COURSE DETAILS DRAWER / MODAL -->
<div class="drawer-overlay" id="courseDrawer">
    <div class="drawer-panel">
        <button class="drawer-close" id="courseDrawerClose" aria-label="Tutup">
            <i class="fas fa-times"></i>
        </button>
        
        <div style="display:flex; flex-direction:column; gap:24px; height:100%; justify-content:space-between; margin-top:20px;">
            <div>
                <span id="drawerCourseCode" class="course-code" style="font-size:0.85rem; letter-spacing:0.1em; display:inline-block; margin-bottom:8px;">MI-101</span>
                <h3 id="drawerCourseName" style="font-size:1.4rem; font-weight:800; color:var(--clr-text); line-height:1.3; margin-bottom:12px;">Pemrograman Dasar</h3>
                
                <div style="display:flex; gap:10px; margin-bottom:28px;">
                    <span id="drawerCourseSks" style="background:var(--clr-primary-xlight); color:var(--clr-primary); font-size:0.8rem; font-weight:700; padding:4px 14px; border-radius:50px; border:1px solid var(--clr-primary-light);">3 SKS</span>
                    <span id="drawerCourseType" class="course-type teori">Teori</span>
                </div>
                
                <div style="margin-bottom:24px;">
                    <div style="font-size:0.72rem; font-weight:700; color:var(--clr-text-light); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Prasyarat</div>
                    <p id="drawerCoursePrereq" style="font-size:0.9rem; color:var(--clr-text); font-weight:600; margin:0;">Tidak Ada</p>
                </div>
                
                <div style="margin-bottom:24px;">
                    <div style="font-size:0.72rem; font-weight:700; color:var(--clr-text-light); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Deskripsi Mata Kuliah</div>
                    <p id="drawerCourseDesc" style="font-size:0.88rem; color:var(--clr-text-muted); line-height:1.6; margin:0;">Mata kuliah ini membahas dasar-dasar logika pemrograman menggunakan bahasa C/C++.</p>
                </div>
                
                <div>
                    <div style="font-size:0.72rem; font-weight:700; color:var(--clr-text-light); text-transform:uppercase; letter-spacing:0.08em; margin-bottom:6px;">Kompetensi Utama</div>
                    <p id="drawerCourseComp" style="font-size:0.88rem; color:var(--clr-text-muted); line-height:1.6; margin:0;">Menguasai konsep dasar algoritma, percabangan, perulangan, dan array.</p>
                </div>
            </div>
            
            <div style="border-top:1px solid var(--clr-border); padding-top:20px;">
                <button class="btn-primary-custom w-100" id="courseDrawerCloseBtn" style="justify-content:center;">
                    Tutup Detail
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Expose PHP curriculum data to JS
const curriculumData = <?= json_encode($curriculumData) ?>;

document.addEventListener('DOMContentLoaded', () => {
    let activeProdi = 'd3';
    let activeSemester = 1;
    let activeFilter = 'all';

    const prodiBtns = document.querySelectorAll('[data-prodi]');
    const semContainer = document.getElementById('semesterPillContainer');
    const filterBtns = document.querySelectorAll('[data-filter]');
    const grid = document.getElementById('curriculumGrid');

    // Drawer elements
    const drawer = document.getElementById('courseDrawer');
    const dCode = document.getElementById('drawerCourseCode');
    const dName = document.getElementById('drawerCourseName');
    const dSks = document.getElementById('drawerCourseSks');
    const dType = document.getElementById('drawerCourseType');
    const dPrereq = document.getElementById('drawerCoursePrereq');
    const dDesc = document.getElementById('drawerCourseDesc');
    const dComp = document.getElementById('drawerCourseComp');
    const dClose = document.getElementById('courseDrawerClose');
    const dCloseBtn = document.getElementById('courseDrawerCloseBtn');

    // Setup prodi click listeners
    prodiBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            prodiBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeProdi = btn.dataset.prodi;
            activeSemester = 1; // Reset to sem 1 on prodi change
            renderSemesterPills();
            renderCourses();
        });
    });

    // Setup category filter click listeners
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeFilter = btn.dataset.filter;
            renderCourses();
        });
    });

    // Render semester pills based on active prodi
    function renderSemesterPills() {
        semContainer.innerHTML = '';
        const totalSemesters = activeProdi === 'd3' ? 6 : 8;
        
        for (let s = 1; s <= totalSemesters; s++) {
            const pill = document.createElement('button');
            pill.className = `sem-pill ${s === activeSemester ? 'active' : ''}`;
            pill.textContent = `Semester ${s}`;
            pill.addEventListener('click', () => {
                const pills = semContainer.querySelectorAll('.sem-pill');
                pills.forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                activeSemester = s;
                renderCourses();
            });
            semContainer.appendChild(pill);
        }
    }

    // Render course cards
    function renderCourses() {
        grid.innerHTML = '';
        const courses = curriculumData[activeProdi][activeSemester] || [];
        
        // Filter courses by category
        const filtered = courses.filter(c => {
            if (activeFilter === 'all') return true;
            return c.type === activeFilter;
        });

        if (filtered.length === 0) {
            grid.innerHTML = `
                <div style="grid-column: 1/-1; text-align:center; padding:40px; color:var(--clr-text-muted);">
                    <i class="fas fa-book-open" style="font-size:2rem; opacity:0.3; margin-bottom:12px;"></i>
                    <p>Tidak ada mata kuliah kategori "${activeFilter}" di semester ini.</p>
                </div>`;
            return;
        }

        filtered.forEach(c => {
            const card = document.createElement('div');
            card.className = 'course-card card-shine';
            card.innerHTML = `
                <div>
                    <div class="course-code">${c.code}</div>
                    <div class="course-name">${c.name}</div>
                </div>
                <div class="course-meta">
                    <span class="course-sks">${c.sks} SKS</span>
                    <span class="course-type ${c.type}">${c.type === 'teori' ? 'Teori' : 'Praktikum'}</span>
                </div>
            `;
            
            card.addEventListener('click', () => openCourseDrawer(c));
            grid.appendChild(card);
        });
    }

    // Drawer management
    function openCourseDrawer(c) {
        dCode.textContent = c.code;
        dName.textContent = c.name;
        dSks.textContent = `${c.sks} SKS`;
        
        // Update type badge styling
        dType.textContent = c.type === 'teori' ? 'Teori' : 'Praktikum';
        dType.className = `course-type ${c.type}`;
        
        dPrereq.textContent = c.prereq;
        dDesc.textContent = c.desc;
        dComp.textContent = c.comp;
        
        drawer.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeCourseDrawer() {
        drawer.classList.remove('active');
        document.body.style.overflow = '';
    }

    dClose.addEventListener('click', closeCourseDrawer);
    dCloseBtn.addEventListener('click', closeCourseDrawer);
    drawer.addEventListener('click', e => { if (e.target === drawer) closeCourseDrawer(); });

    // Initial render
    renderSemesterPills();
    renderCourses();
});
</script>

<?php include __DIR__.'/includes/footer.php'; ?>
