-- =============================================
-- DATABASE: project_mi
-- Jurusan Manajemen Informatika — POLSRI
-- Data dosen ASLI dari manajemeninformatika.polsri.ac.id
-- =============================================

-- CREATE DATABASE IF NOT EXISTS project_mi
--     CHARACTER SET utf8mb4
--     COLLATE utf8mb4_unicode_ci;
-- USE project_mi;

-- ─────────────────────────────────────────────
-- TABEL: pesan_kontak (WAJIB sesuai ketentuan)
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS pesan_kontak (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    pesan      TEXT NOT NULL,
    is_read    TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- TABEL: dosen (+ kolom prodi & nip)
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS dosen (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nama            VARCHAR(150) NOT NULL,
    foto            VARCHAR(255) DEFAULT 'default-dosen.jpg',
    bidang_keahlian VARCHAR(200) NOT NULL,
    email           VARCHAR(150) NOT NULL,
    nidn            VARCHAR(20),
    nip             VARCHAR(30),
    jabatan         VARCHAR(100) DEFAULT 'Dosen',
    prodi           ENUM('d3','d4','keduanya') DEFAULT 'd3',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- TABEL: berita
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS berita (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    judul      VARCHAR(255) NOT NULL,
    slug       VARCHAR(255) NOT NULL UNIQUE,
    konten     LONGTEXT NOT NULL,
    gambar     VARCHAR(255) DEFAULT 'default-berita.jpg',
    kategori   ENUM('berita','artikel') DEFAULT 'berita',
    status     ENUM('publish','draft') DEFAULT 'publish',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- TABEL: galeri
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS galeri (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    judul      VARCHAR(255) NOT NULL,
    gambar     VARCHAR(255) NOT NULL,
    deskripsi  TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- TABEL: mahasiswa_prestasi
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS mahasiswa_prestasi (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    nama       VARCHAR(150) NOT NULL,
    nim        VARCHAR(20)  NOT NULL,
    prestasi   VARCHAR(255) NOT NULL,
    tahun      YEAR NOT NULL,
    foto       VARCHAR(255) DEFAULT 'default-mahasiswa.jpg',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─────────────────────────────────────────────
-- TABEL: admin
-- ─────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS admin (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- SEED: Admin default
-- Username: admin | Password: admin123
INSERT INTO admin (username, password) VALUES
('admin', '$2y$12$N7rWSMQWIj/NtQtu/2ZIWuaubTjdqTlhZU6sogbl81Cxvogs3QTtC');

-- =============================================
-- SEED: Dosen LENGKAP dari polsri.ac.id
-- =============================================

-- Ketua Jurusan
INSERT INTO dosen (nama,foto,bidang_keahlian,email,nidn,nip,jabatan,prodi) VALUES
('Ir. Sony Oktapriandi, S.Kom., M.Kom.','sony-oktapriandi.png',
 'Sistem Informasi & Manajemen TI','sony@polsri.ac.id',
 '0027107512','197510272008121000','Ketua Jurusan','keduanya');

-- Dosen D4
INSERT INTO dosen (nama,foto,bidang_keahlian,email,nidn,nip,jabatan,prodi) VALUES
('Leni Novianti, S.Kom, M.Kom','leni-novianti.png','Rekayasa Perangkat Lunak & Basis Data','leni@polsri.ac.id','0031107701','197710312002122003','Dosen Senior','d4'),
('Meivi Kusnandar, S.Kom., M.Kom','meivi-kusnandar.png','Pemrograman & Rekayasa Sistem','meivi@polsri.ac.id','0001077405','197407052002121020','Dosen Senior','d4'),
('Rika Sadariawati, S.E., M.Si','rika-sadariawati.png','Manajemen Bisnis & E-Commerce','rika@polsri.ac.id','0023027303','197302232002122020','Dosen','d4'),
('Desi Apriyanty, S.E., M.Si','desi-apriyanty.png','Ekonomi & Manajemen Informatika','desi@polsri.ac.id','0029047303','197304292005012000','Dosen','d4'),
('Dr. Hetty Meileni, S.Kom., M.T.','hetty-meileni.png','Sistem Informasi & Komputasi','hetty@polsri.ac.id','0014057906','197905142008122020','Dosen Senior','d4'),
('Dr. Indri Ariyanti, S.E., M.Si','indri-ariyanti.png','Manajemen Strategis & Kewirausahaan Digital','indri@polsri.ac.id','0003067305','197306032008012008','Dosen Senior','d4'),
('Nita Novita, S.E., M.M.','nita-novita.png','Manajemen & Bisnis Digital','nita@polsri.ac.id','0023117410','197411232008012000','Dosen','d4'),
('Muhammad Aris Ganiardi, S.Si., M.T.','aris-ganiardi.png','Matematika Komputasi & Statistika','aris@polsri.ac.id','0214018101','198101142012121000','Dosen','d4'),
('Andre Mariza Putra, S.Kom., M.Kom','andre-mariza.png','Pengembangan Aplikasi & Cloud Computing','andre@polsri.ac.id','1008038803','198803082019031000','Dosen','d4'),
('Ayu Octarina, S.Pd., M.Pd','ayu-octarina.png','Pendidikan Vokasi & Bahasa Inggris Profesional','ayu@polsri.ac.id','0009109005','199010092019092000','Dosen','d4'),
('Febie Elfaladonna, S.Kom., M.Kom','febie-elfaladonna.png','Sistem Informasi & Analisis Data','febie@polsri.ac.id','0422029401','199402222019032030','Dosen','d4');

-- Dosen D3
INSERT INTO dosen (nama,foto,bidang_keahlian,email,nidn,nip,jabatan,prodi) VALUES
('Ridwan Effendi, S.E., M.Si','ridwan-effendi.png','Akuntansi & Manajemen Keuangan','ridwan@polsri.ac.id','0011036007','196003111989031005','Dosen Senior','d3'),
('Ir. Zulkarnaini, M.T.','zulkarnaini.png','Teknik Informatika & Jaringan Komputer','zulkarnaini@polsri.ac.id','0018096206','196209181992031001','Dosen Senior','d3'),
('Indra Satriadi, S.T., M.Kom','indra-satriadi.png','Pemrograman Web & Mobile','indra@polsri.ac.id','0016117202','197211162000031000','Dosen Senior','d3'),
('Devi Sartika, S.Kom., M.AB','devi-sartika.png','Administrasi Bisnis & Sistem Informasi','devi@polsri.ac.id','0011107702','197710112001122002','Dosen','d3'),
('Dewi Irmawati Siregar, S.Kom., M.Kom','dewi-irmawati.png','Rekayasa Sistem & Basis Data','dewi@polsri.ac.id','0018097702','197709182001122001','Dosen','d3'),
('Deri Darfin, S.Sos., M.Si','deri-darfin.png','Sosiologi Digital & Komunikasi Organisasi','deri@polsri.ac.id','0020117402','197411202002121000','Dosen','d3'),
('Ienda Meiriska, S.Kom., M.Kom','ienda-meiriska.png','Pemrograman & Rekayasa Perangkat Lunak','ienda@polsri.ac.id','0017057907','197905172002122020','Dosen','d3'),
('Robinson, S.Kom., M.Kom','robinson.png','Keamanan Sistem & Jaringan Komputer','robinson@polsri.ac.id','0017037503','197503172002121000','Dosen','d3'),
('Henny Madora, S.Kom., M.M.','henny-madora.png','Manajemen Informasi & Sistem ERP','henny@polsri.ac.id','0027097701','197709272005012000','Dosen','d3'),
('Ida Wahyuningrum, S.E., M.Si','ida-wahyuningrum.png','Akuntansi Manajemen & Sistem Informasi Akuntansi','ida@polsri.ac.id','0011108003','198010112005012000','Dosen','d3'),
('Muhammad Noval, S.E., M.Si','muhammad-noval.png','Ekonomi Manajerial & Kewirausahaan','noval@polsri.ac.id','0008117502','197511082005011003','Dosen','d3'),
('Yusniarti, S.Kom., M.Kom','yusniarti.png','Sistem Informasi & Analisis Pemrograman','yusniarti@polsri.ac.id','0021097902','197909212005012000','Dosen','d3'),
('Herlinda Kusmiati, S.Kom., M.Kom.','herlinda-kusmiati.png','Sistem Informasi & Tata Kelola TI','herlinda@polsri.ac.id','0204098901','198909042022032000','Koordinator Program Studi D4','d4'),
('Ir. Sulistiyanto, S.Kom., M.T.I.','sulistiyanto.png','Sistem Informasi & Sistem Pakar','sulistiyanto@polsri.ac.id','0223029301','199302232022031000','Sekretaris Jurusan','d4');

-- =============================================
-- SEED: Berita & Artikel (4 data)
-- =============================================
INSERT INTO berita (judul,slug,konten,gambar,kategori,status) VALUES
('Mahasiswa MI Polsri Raih Juara 1 Hackathon Nasional 2025',
 'mahasiswa-mi-polsri-raih-juara-1-hackathon-nasional-2025',
 '<p>Mahasiswa Jurusan Manajemen Informatika Politeknik Negeri Sriwijaya berhasil menorehkan prestasi gemilang dengan meraih <strong>Juara 1 Hackathon Nasional 2025</strong> yang diselenggarakan oleh Kementerian Komunikasi dan Informatika Republik Indonesia.</p><p>Tim yang terdiri dari tiga mahasiswa semester 6 ini berhasil mengalahkan lebih dari 200 tim peserta dari seluruh Indonesia dengan menghadirkan solusi inovatif berbasis kecerdasan buatan untuk sistem monitoring kesehatan masyarakat.</p><p>Ketua Jurusan MI Polsri, Ir. Sony Oktapriandi, S.Kom., M.Kom., menyampaikan rasa bangga dan apresiasinya. Para pemenang mendapatkan hadiah Rp 50 juta dan kesempatan program inkubasi bisnis teknologi.</p>',
 'berita1.jpg','berita','publish'),
('Workshop Pengembangan Aplikasi Mobile Berbasis Flutter 2025',
 'workshop-pengembangan-aplikasi-mobile-berbasis-flutter-2025',
 '<p>Jurusan Manajemen Informatika Polsri sukses menyelenggarakan <strong>Workshop Pengembangan Aplikasi Mobile Berbasis Flutter</strong> yang berlangsung selama dua hari pada 15-16 Mei 2025.</p><p>Workshop ini diikuti oleh lebih dari 150 mahasiswa aktif dari berbagai semester dan dibimbing langsung oleh praktisi industri berpengalaman dari perusahaan teknologi ternama di Palembang.</p><p>Peserta mendapatkan materi komprehensif mulai dari dasar-dasar Flutter, pengelolaan state management, integrasi API, hingga deployment ke Google Play Store.</p>',
 'berita2.jpg','berita','publish'),
('Revolusi AI dalam Dunia Bisnis: Peluang dan Tantangan',
 'revolusi-ai-dalam-dunia-bisnis-peluang-dan-tantangan',
 '<p>Kecerdasan buatan (AI) telah mengubah lanskap bisnis secara fundamental. Teknologi ini tidak lagi sekadar alat bantu, melainkan telah menjadi inti strategi bisnis perusahaan terkemuka di seluruh dunia.</p><p><strong>Peluang yang Ditawarkan AI:</strong></p><ul><li>Automatisasi proses bisnis yang repetitif</li><li>Analisis data besar (Big Data) secara real-time</li><li>Personalisasi layanan pelanggan secara masif</li><li>Prediksi tren pasar dengan akurasi tinggi</li></ul><p>Namun tantangan terkait keamanan data, etika penggunaan AI, dan transformasi tenaga kerja tetap harus dihadapi secara bijak.</p>',
 'artikel1.jpg','artikel','publish'),
('Tren Teknologi Web 2025: Apa yang Harus Dipelajari Developer?',
 'tren-teknologi-web-2025-apa-yang-harus-dipelajari-developer',
 '<p>Dunia pengembangan web terus berevolusi. Memasuki 2025, beberapa teknologi baru mulai mendominasi industri.</p><p><strong>1. WebAssembly (WASM) Mainstream</strong> — Memungkinkan aplikasi web berjalan dengan performa mendekati native.</p><p><strong>2. Edge Computing & Serverless</strong> — Platform seperti Cloudflare Workers memungkinkan latensi jauh lebih rendah.</p><p><strong>3. AI-Augmented Development</strong> — Tools seperti GitHub Copilot mengubah cara developer bekerja. Kolaborasi efektif dengan AI menjadi skill yang wajib dikuasai setiap developer modern.</p>',
 'artikel2.jpg','artikel','publish'),
('Pengumuman Persiapan Ujian Akhir Semester (UAS) Semester Genap TA 2025/2026',
 '2026-06-04-pengumuman-persiapan-ujian-akhir-semester-uas-semester-genap-ta-20252026',
 '<p>Nomor: 1283/PL6.1.25/LL/2026<br>Perihal: Persiapan Ujian Akhir Semester (UAS) Semester Genap Tahun Akademik 2025/2026</p><p>&nbsp;</p><p>Dalam rangka pelaksanaan Ujian Akhir Semester (UAS) Semester Genap Tahun Akademik 2025/2026, disampaikan beberapa hal sebagai berikut:</p><ol><li><p>Mahasiswa wajib memastikan bahwa tidak ada tunggakan <strong>UKT dan UKT semester berjalan telah lunas</strong> sesuai ketentuan yang berlaku. Mahasiswa yang belum menyelesaikan kewajiban administrasi akademik agar segera melakukan pembayaran dan berkoordinasi dengan pihak terkait atau tidak bisa mengikuti UAS / Ujian Tugas Akhir/Laporan Akhir.</p></li><li><p>Mahasiswa diharapkan memastikan seluruh kewajiban akademik telah diselesaikan sebelum pelaksanaan UAS.</p></li><li><p>Jadwal UAS mengikuti jadwal KBM yang telah berjalan.</p></li><li><p>Mahasiswa diwajibkan membawa identitas diri (KTM/KTP) dan perlengkapan ujian yang diperlukan.</p></li><li><p>Mahasiswa yang sedang menempuh Tugas Akhir/Laporan Akhir maupun Kerja Praktik agar segera menyelesaikan proses bimbingan dan berkoordinasi dengan dosen pembimbing terkait penilaian bimbingan.</p></li><li><p>Mahasiswa diharapkan menjaga integritas akademik selama pelaksanaan UAS. Segala bentuk kecurangan, plagiarisme, maupun pelanggaran tata tertib ujian akan dikenakan sanksi sesuai peraturan akademik yang berlaku.</p></li><li><p>Pelaksanaan UAS tanggal 15 - 19 Juni 2026</p></li><li><p>Untuk mahasiswa Sarjana Terapan yang telah menyelesaikan Kerja Praktek (KP), untuk segera mengumpulkan nilai dari dosen pembimbing perusahaan.</p></li><li><p>Sehubungan dengan adanya Ujian Masuk Mandiri yang diadakah di Lab Komputer MI, maka untuk perkuliahan tanggal <strong>6 - 9 Juni 2026</strong> dilaksanakan secara <em>daring. </em>Untuk KBM hari <strong>Jumat, 5 Juni 2026</strong>, MK praktikum dilaksanakan secara <em>daring</em>, sedangkan teori <em>luring</em></p></li></ol><p>Informasi lebih lanjut terkait jadwal dan tata tertib pelaksanaan UAS akan disampaikan melalui Program Studi masing-masing.</p><p>&nbsp;</p><p>Palembang,     Juni 2026<br>Ketua Jurusan Manajemen Informatika </p><p> dto</p><p>Ir. Sony Oktapriandi, M.Kom<br>NIP 197510272008121001</p>',
 'berita_uas.jpg','berita','publish'),
('Pengumpulan Berkas Magang',
 '2026-05-23-pengumpulan-berkas-magang',
 '<p>Bagi Mahasiswa Diploma 3 angkatan 2024 yg sudah mendapat surat jawaban izin magang dari perusahaan tujuan, silahkan upload berkas di link GForm berikut:</p><p><code>https://s.id/BerkasMagangMI </code></p>',
 'berita_magang.jpg','berita','publish'),
('Ujian Program/Aplikasi Mahasiswa D3 & D4',
 '2026-05-13-pelaksanaan-ujian-programaplikasi-mahasiswa-d3--d4',
 '<p>Diberitahukan kepada seluruh mahasiswa tingkat akhir Jurusan Manajemen Informatika Politeknik Negeri Sriwijaya Semester Genap Tahun Akademik 2025/2026 semester 8 (Sarjana Terapan) & semester 6 (Diploma), bahwa akan dilaksanakan kegiatan <strong>Ujian Program/Aplikasi</strong> sebagai bagian dari rangkaian proses penyelesaian Laporan/Tugas Akhir.</p><p>Pelaksanaan ujian dilakukan secara <strong>terjadwal dan paralel</strong> dengan melibatkan tim penguji dari laboratorium dan dosen terkait. Sistem ini diharapkan dapat menjaga efektivitas pelaksanaan ujian, pemerataan peserta, serta kualitas proses penilaian.</p><p>Ujian Program/Aplikasi bertujuan untuk mengukur:</p><ul><li><p>Penguasaan sistem/aplikasi yang dikembangkan</p></li><li><p>Pemahaman logika dan alur sistem</p></li><li><p>Kemampuan implementasi dan modifikasi program</p></li><li><p>Kemampuan mahasiswa dalam menjelaskan aplikasi secara teknis</p></li></ul><p>Ketentuan Revisi Hasil Ujian Program / Aplikasi:</p><ul><li><p>Revisi Ringan : Perbaikan tampilan, validasi input, atau penyesuaian minor lainnya</p></li><li><p>Revisi Sedang : Perubahan alur proses, penambahan fitur terbatas, atau penyempurnaan logika sistem</p></li><li><p>Revisi Berat : Perbaikan mendasar yang mempengaruhi struktur sistem, fungsi utama aplikasi, atau ketidakmampuan mahasiswa dalam menjelaskan dan/atau memodifikasi sistem saat ujian</p></li></ul><p>Mekanisme Penyelesaian Revisi:</p><ul><li><p>Melakukan perbaikan sesuai catatan penguji</p></li><li><p>Menyerahkan hasil revisi kepada dosen pembimbing disertai bukti perubahan</p></li></ul><p>Validasi Revisi:</p><ul><li><p>Revisi ringan dan sedang : diverifikasi oleh dosen pembimbing</p></li><li><p>Revisi berat : wajib dilakukan validasi ulang oleh penguji melalui ujian ulang terbatas (mini test)</p></li></ul><p>Hasil revisi dinyatakan sah apabila:</p><ul><li><p>Telah diverifikasi dan disetujui oleh dosen pembimbing</p></li><li><p>Telah lulus validasi ulang oleh penguji</p></li><li><p>Tanpa adanya validasi tersebut, mahasiswa dianggap belum menyelesaikan kewajiban ujian program/aplikasi</p></li></ul><p>Ketentuan Ujian Ulang</p><ul><li><p>Ujian ulang hanya diberlakukan untuk mahasiswa dengan: (1) Revisi kategori berat, atau (2) yang dinilai belum menguasai sistem saat ujian</p></li></ul><p>Batas Waktu Revisi</p><ul><li><p>Mahasiswa wajib menyelesaikan revisi dalam waktu maksimal <strong>(3–5) hari kerja</strong> sejak tanggal pelaksanaan ujian</p></li><li><p>Mahasiswa yang tidak menyelesaikan revisi dalam batas waktu yang ditentukan: (1) Dianggap <strong>belum memenuhi syarat kelulusan ujian program/aplikasi, </strong>(2) Diwajibkan mengikuti ujian ulang pada periode berikutnya</p></li></ul><p>Form Pendaftaran:</p><p><code>https://forms.gle/KRa4TwWXqD6FkefN8 </code></p><p>Pelaksanaan:</p><ul><li><p>10 - 13 Juni 2026</p></li><li><p>Ruangan Aula</p></li></ul><p>&nbsp;</p>',
 'berita_ujian.jpg','berita','publish'),
('Uji Kompetensi Mahasiswa Sarjana Terapan',
 '2026-05-12-uji-kompetensi-mahasiswa-sarjana-terapan',
 '<p>Kepada mahasiswa Program Studi <strong>Sarjana Terapan Manajemen Informatika</strong> bahwa akan dilaksanakan kegiatan <strong>Uji Kompetensi (Ujikom)</strong> sebagai bagian dari penguatan capaian kompetensi mahasiswa dan kesiapan lulusan menghadapi dunia kerja serta industri.</p><p>Perlu disampaikan bahwa biaya pelaksanaan Ujikom telah termasuk dalam UKT mahasiswa, sehingga mahasiswa tidak dikenakan biaya tambahan untuk mengikuti kegiatan ini. Kesempatan ini sangat penting karena apabila mengikuti sertifikasi/uji kompetensi secara mandiri di luar kampus, peserta umumnya dikenakan biaya tersendiri.</p><p>Melalui pelaksanaan Ujikom ini, mahasiswa yang dinyatakan kompeten nantinya akan memperoleh <strong>Sertifikat Kompetensi BNSP (Badan Nasional Sertifikasi Profesi)</strong> sesuai skema yang diikuti. Sertifikat BNSP tersebut dapat menjadi nilai tambah dalam proses rekrutmen kerja, penguatan portofolio profesional, serta pengakuan kompetensi sesuai standar nasional.</p><p>Seluruh mahasiswa Sarjana Terapan Manajemen Informatika dipersiapkan untuk mengikuti pelaksanaan Ujikom sesuai dengan jadwal, sesi, dan ketentuan yang ditetapkan oleh program studi. Mahasiswa diminta untuk melakukan pendaftaran melalui tautan di bawah ini.</p><p><strong>Tahap Pendaftaran & Pelaksanaan</strong></p><p>Peserta melakukan pengisian data diri melalui portal resmi LSP Polsri pada laman berikut: <u><a href="#" target="_blank" rel="noopener">https://lsp.polsri.ac.id/pendaftaran</a></u>. Batas akhir pendaftaran online: <strong>17 Mei 2026</strong>.</p><p>Untuk jenjang D4 Manajemen Informatika, pilihan skema yang dibuka yaitu: <a href="#" target="_blank" rel="noopener">Skema Junior Web Programmer</a> & <a href="#" target="_blank" rel="noopener">Skema System Analyst.</a></p><p><strong>Tahap Penyerahan Berkas Fisik (Hardcopy)</strong></p><p>Peserta menyerahkan berkas fisik berupa:</p><ol><li><p>Formulir APL-01 yang telah diisi lengkap. Form dapat diunduh pada link dibawah ini<br><code>https://drive.google.com/drive/folders/1pvggz6DFS3WHzWQQUh1nufuysPkUOEsO?usp=sharing</code></p><p>(<em>diisi menggunakan huruf kapital, tinta hitam, tanpa coretan, dan ditandatangani</em>)</p></li><li><p>Pas foto terbaru background merah ukuran 3x4 sebanyak 2 lembar</p></li><li><p>Fotokopi KTP</p></li><li><p>Fotokopi KTM</p></li><li><p>KHS Semester 1–7 (lengkap)</p></li><li><p>Sertifikat Magang atau dokumen pendukung lainnya</p></li></ol><p>Seluruh berkas diserahkan kepada LSP melalui Admin TUK (<strong>Ibu Riska</strong>) dalam kondisi lengkap. Batas akhir berkas diterima: <strong>20 Mei 2026 pukul 14.00 WIB.</strong></p><p>Pengiriman berkas ke LSP dilakukan secara bertahap, sehingga mahasiswa sangat disarankan untuk mengumpulkan berkas lebih awal agar proses verifikasi dapat segera dilakukan. Jangan menunggu batas akhir pengumpulan.</p><p><strong>Petunjuk Pendaftaran</strong></p><p>Tata cara pengisian APL-01 terdapat pada tautan berikut:<br><code>https://drive.google.com/file/d/1p_XbG0g5bQgN7ho0WKSZrbiIYGv_Ucka/view?usp=sharing </code></p><p><strong>Informasi Pelaksanaan Ujikom</strong></p><p>Hari/Tanggal Pelaksanaan : <strong>10 – 12 Juni 2026</strong><br>Waktu                             : 08.00 s.d selesai<br>Tempat                           : Laboratorium Komputer Manajemen Informatika</p><p>Informasi pembagian sesi, teknis pelaksanaan, serta ketentuan lainnya akan diumumkan lebih lanjut melalui <em>WAG</em> & Website Jurusan.</p><p>Mahasiswa yang tidak mengikuti tahapan pendaftaran dan pelaksanaan Ujikom berarti tidak memanfaatkan fasilitas kompetensi yang telah disediakan melalui pembiayaan UKT serta kehilangan kesempatan memperoleh sertifikasi kompetensi tanpa biaya tambahan dari kampus.</p><p>Demikian pengumuman ini disampaikan untuk menjadi perhatian seluruh mahasiswa.</p>',
 'berita_ujikom.jpg','berita','publish'),
('Dosen Manajemen Informatika Ikuti Pelatihan Asesor Kompetensi BNSP guna Tingkatkan Kualitas Pendidikan Vokasi',
 'dosen-manajemen-informatika-ikuti-pelatihan-asesor-kompetensi-bnsp-guna-tingkatkan-kualitas-pendidikan-vokasi',
 '<p><strong>PALEMBANG</strong> – Sejumlah dosen dari Program Studi Manajemen Informatika (MI) mengikuti Pelatihan Calon Asesor Kompetensi yang diselenggarakan oleh Lembaga Sertifikasi Profesi (LSP) Politeknik Negeri Sriwijaya (Polsri). Pelatihan intensif selama lima hari ini dilaksanakan di Hotel Batiqa, Palembang.</p><p>Pelatihan ini bertujuan untuk mencetak asesor-asesor baru yang tersertifikasi secara nasional oleh Badan Nasional Sertifikasi Profesi (BNSP). Keikutsertaan dosen MI dalam kegiatan ini merupakan langkah strategis untuk memperkuat ekosistem pendidikan vokasi, memastikan lulusan memiliki kompetensi yang selaras dengan kebutuhan industri.</p><p>Selama pelatihan, peserta dibekali dengan materi regulasi sistem sertifikasi nasional, pengembangan perangkat asesmen, dan metode uji kompetensi. Setelah lulus asesmen, para dosen ini akan memiliki wewenang untuk melakukan uji kompetensi bagi mahasiswa, khususnya di bawah naungan LSP Polsri, guna meningkatkan mutu dan kredibilitas lulusan.</p>',
 'berita_asesor.jpg','berita','publish');

-- =============================================
-- SEED: Galeri (8 foto)
-- =============================================
INSERT INTO galeri (judul,gambar,deskripsi) VALUES
('Wisuda Mahasiswa MI 2024','galeri1.jpg','Momen bahagia wisuda mahasiswa Jurusan MI angkatan 2021'),
('Seminar Nasional Teknologi Informasi','galeri2.jpg','Seminar nasional dengan pembicara dari industri teknologi terkemuka'),
('Workshop Flutter Development','galeri3.jpg','Workshop intensif pengembangan aplikasi mobile menggunakan Flutter'),
('PKL Mahasiswa di Perusahaan IT','galeri4.jpg','Mahasiswa MI melaksanakan PKL di berbagai perusahaan teknologi'),
('Kompetisi Coding Antar Mahasiswa','galeri5.jpg','Ajang kompetisi coding untuk mengasah kemampuan pemrograman mahasiswa'),
('Kegiatan HMJ Manajemen Informatika','galeri6.jpg','Berbagai kegiatan kemahasiswaan yang diorganisir oleh HMJ MI Polsri'),
('Laboratorium Komputer MI','galeri7.jpg','Fasilitas laboratorium komputer modern untuk menunjang pembelajaran'),
('Penerimaan Mahasiswa Baru 2025','galeri8.jpg','Proses orientasi dan penerimaan mahasiswa baru jurusan MI tahun 2025');

-- =============================================
-- SEED: Prestasi Mahasiswa (5 data)
-- =============================================
INSERT INTO mahasiswa_prestasi (nama,nim,prestasi,tahun,foto) VALUES
('Ahmad Fauzi Rahman','062230700001','Juara 1 Hackathon Nasional Kemkominfo 2025',2025,'mhs1.jpg'),
('Siti Nurhaliza','062230700015','Best Paper Award — Seminar Nasional APTISI 2024',2024,'mhs2.jpg'),
('Budi Santoso','062230700023','Juara 2 Lomba Web Design Tingkat Sumatera 2024',2024,'mhs3.jpg'),
('Dewi Anggraini','062220700008','Penerima Beasiswa Unggulan Kemendikbud 2024',2024,'mhs4.jpg'),
('Rizky Pratama','062220700019','Juara 1 Business Plan Competition Regional Sumatera 2025',2025,'mhs5.jpg');
