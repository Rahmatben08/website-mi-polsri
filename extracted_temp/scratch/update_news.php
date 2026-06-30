<?php
// =============================================
// scratch/update_news.php — MI Polsri v2.0
// Script otomatis untuk memperbarui berita di database lokal & hosting
// =============================================
require_once __DIR__ . '/../includes/config.php';
$db = getDB();

$new_news = [
    [
        'judul'      => 'Pengumuman Persiapan Ujian Akhir Semester (UAS) Semester Genap TA 2025/2026',
        'slug'       => '2026-06-04-pengumuman-persiapan-ujian-akhir-semester-uas-semester-genap-ta-20252026',
        'konten'     => '<p>Nomor: 1283/PL6.1.25/LL/2026<br>Perihal: Persiapan Ujian Akhir Semester (UAS) Semester Genap Tahun Akademik 2025/2026</p><p>&nbsp;</p><p>Dalam rangka pelaksanaan Ujian Akhir Semester (UAS) Semester Genap Tahun Akademik 2025/2026, disampaikan beberapa hal sebagai berikut:</p><ol><li><p>Mahasiswa wajib memastikan bahwa tidak ada tunggakan <strong>UKT dan UKT semester berjalan telah lunas</strong> sesuai ketentuan yang berlaku. Mahasiswa yang belum menyelesaikan kewajiban administrasi akademik agar segera melakukan pembayaran dan berkoordinasi dengan pihak terkait atau tidak bisa mengikuti UAS / Ujian Tugas Akhir/Laporan Akhir.</p></li><li><p>Mahasiswa diharapkan memastikan seluruh kewajiban akademik telah diselesaikan sebelum pelaksanaan UAS.</p></li><li><p>Jadwal UAS mengikuti jadwal KBM yang telah berjalan.</p></li><li><p>Mahasiswa diwajibkan membawa identitas diri (KTM/KTP) dan perlengkapan ujian yang diperlukan.</p></li><li><p>Mahasiswa yang sedang menempuh Tugas Akhir/Laporan Akhir maupun Kerja Praktik agar segera menyelesaikan proses bimbingan dan berkoordinasi dengan dosen pembimbing terkait penilaian bimbingan.</p></li><li><p>Mahasiswa diharapkan menjaga integritas akademik selama pelaksanaan UAS. Segala bentuk kecurangan, plagiarisme, maupun pelanggaran tata tertib ujian akan dikenakan sanksi sesuai peraturan akademik yang berlaku.</p></li><li><p>Pelaksanaan UAS tanggal 15 - 19 Juni 2026</p></li><li><p>Untuk mahasiswa Sarjana Terapan yang telah menyelesaikan Kerja Praktek (KP), untuk segera mengumpulkan nilai dari dosen pembimbing perusahaan.</p></li><li><p>Sehubungan dengan adanya Ujian Masuk Mandiri yang diadakah di Lab Komputer MI, maka untuk perkuliahan tanggal <strong>6 - 9 Juni 2026</strong> dilaksanakan secara <em>daring. </em>Untuk KBM hari <strong>Jumat, 5 Juni 2026</strong>, MK praktikum dilaksanakan secara <em>daring</em>, sedangkan teori <em>luring</em></p></li></ol><p>Informasi lebih lanjut terkait jadwal dan tata tertib pelaksanaan UAS akan disampaikan melalui Program Studi masing-masing.</p><p>&nbsp;</p><p>Palembang,     Juni 2026<br>Ketua Jurusan Manajemen Informatika </p><p> dto</p><p>Ir. Sony Oktapriandi, M.Kom<br>NIP 197510272008121001</p>',
        'gambar'     => 'berita_uas.jpg',
        'kategori'   => 'berita',
        'status'     => 'publish',
        'created_at' => '2026-06-04 11:56:08'
    ],
    [
        'judul'      => 'Pengumpulan Berkas Magang',
        'slug'       => '2026-05-23-pengumpulan-berkas-magang',
        'konten'     => '<p>Bagi Mahasiswa Diploma 3 angkatan 2024 yg sudah mendapat surat jawaban izin magang dari perusahaan tujuan, silahkan upload berkas di link GForm berikut:</p><p><code>https://s.id/BerkasMagangMI </code></p>',
        'gambar'     => 'berita_magang.jpg',
        'kategori'   => 'berita',
        'status'     => 'publish',
        'created_at' => '2026-05-23 04:16:30'
    ],
    [
        'judul'      => 'Ujian Program/Aplikasi Mahasiswa D3 & D4',
        'slug'       => '2026-05-13-pelaksanaan-ujian-programaplikasi-mahasiswa-d3--d4',
        'konten'     => '<p>Diberitahukan kepada seluruh mahasiswa tingkat akhir Jurusan Manajemen Informatika Politeknik Negeri Sriwijaya Semester Genap Tahun Akademik 2025/2026 semester 8 (Sarjana Terapan) & semester 6 (Diploma), bahwa akan dilaksanakan kegiatan <strong>Ujian Program/Aplikasi</strong> sebagai bagian dari rangkaian proses penyelesaian Laporan/Tugas Akhir.</p><p>Pelaksanaan ujian dilakukan secara <strong>terjadwal dan paralel</strong> dengan melibatkan tim penguji dari laboratorium dan dosen terkait. Sistem ini diharapkan dapat menjaga efektivitas pelaksanaan ujian, pemerataan peserta, serta kualitas proses penilaian.</p><p>Ujian Program/Aplikasi bertujuan untuk mengukur:</p><ul><li><p>Penguasaan sistem/aplikasi yang dikembangkan</p></li><li><p>Pemahaman logika dan alur sistem</p></li><li><p>Kemampuan implementasi dan modifikasi program</p></li><li><p>Kemampuan mahasiswa dalam menjelaskan aplikasi secara teknis</p></li></ul><p>Ketentuan Revisi Hasil Ujian Program / Aplikasi:</p><ul><li><p>Revisi Ringan : Perbaikan tampilan, validasi input, atau penyesuaian minor lainnya</p></li><li><p>Revisi Sedang : Perubahan alur proses, penambahan fitur terbatas, atau penyempurnaan logika sistem</p></li><li><p>Revisi Berat : Perbaikan mendasar yang mempengaruhi struktur sistem, fungsi utama aplikasi, atau ketidakmampuan mahasiswa dalam menjelaskan dan/atau memodifikasi sistem saat ujian</p></li></ul><p>Mekanisme Penyelesaian Revisi:</p><ul><li><p>Melakukan perbaikan sesuai catatan penguji</p></li><li><p>Menyerahkan hasil revisi kepada dosen pembimbing disertai bukti perubahan</p></li></ul><p>Validasi Revisi:</p><ul><li><p>Revisi ringan dan sedang : diverifikasi oleh dosen pembimbing</p></li><li><p>Revisi berat : wajib dilakukan validasi ulang oleh penguji melalui ujian ulang terbatas (mini test)</p></li></ul><p>Hasil revisi dinyatakan sah apabila:</p><ul><li><p>Telah diverifikasi dan disetujui oleh dosen pembimbing</p></li><li><p>Telah lulus validasi ulang oleh penguji</p></li><li><p>Tanpa adanya validasi tersebut, mahasiswa dianggap belum menyelesaikan kewajiban ujian program/aplikasi</p></li></ul><p>Ketentuan Ujian Ulang</p><ul><li><p>Ujian ulang hanya diberlakukan untuk mahasiswa dengan: (1) Revisi kategori berat, atau (2) yang dinilai belum menguasai sistem saat ujian</p></li></ul><p>Batas Waktu Revisi</p><ul><li><p>Mahasiswa wajib menyelesaikan revisi dalam waktu maksimal <strong>(3–5) hari kerja</strong> sejak tanggal pelaksanaan ujian</p></li><li><p>Mahasiswa yang tidak menyelesaikan revisi dalam batas waktu yang ditentukan: (1) Dianggap <strong>belum memenuhi syarat kelulusan ujian program/aplikasi, </strong>(2) Diwajibkan mengikuti ujian ulang pada periode berikutnya</p></li></ul><p>Form Pendaftaran:</p><p><code>https://forms.gle/KRa4TwWXqD6FkefN8 </code></p><p>Pelaksanaan:</p><ul><li><p>10 - 13 Juni 2026</p></li><li><p>Ruangan Aula</p></li></ul><p>&nbsp;</p>',
        'gambar'     => 'berita_ujian.jpg',
        'kategori'   => 'berita',
        'status'     => 'publish',
        'created_at' => '2026-05-13 08:54:19'
    ],
    [
        'judul'      => 'Uji Kompetensi Mahasiswa Sarjana Terapan',
        'slug'       => '2026-05-12-uji-kompetensi-mahasiswa-sarjana-terapan',
        'konten'     => '<p>Kepada mahasiswa Program Studi <strong>Sarjana Terapan Manajemen Informatika</strong> bahwa akan dilaksanakan kegiatan <strong>Uji Kompetensi (Ujikom)</strong> sebagai bagian dari penguatan capaian kompetensi mahasiswa dan kesiapan lulusan menghadapi dunia kerja serta industri.</p><p>Perlu disampaikan bahwa biaya pelaksanaan Ujikom telah termasuk dalam UKT mahasiswa, sehingga mahasiswa tidak dikenakan biaya tambahan untuk mengikuti kegiatan ini. Kesempatan ini sangat penting karena apabila mengikuti sertifikasi/uji kompetensi secara mandiri di luar kampus, peserta umumnya dikenakan biaya tersendiri.</p><p>Melalui pelaksanaan Ujikom ini, mahasiswa yang dinyatakan kompeten nantinya akan memperoleh <strong>Sertifikat Kompetensi BNSP (Badan Nasional Sertifikasi Profesi)</strong> sesuai skema yang diikuti. Sertifikat BNSP tersebut dapat menjadi nilai tambah dalam proses rekrutmen kerja, penguatan portofolio profesional, serta pengakuan kompetensi sesuai standar nasional.</p><p>Seluruh mahasiswa Sarjana Terapan Manajemen Informatika dipersiapkan untuk mengikuti pelaksanaan Ujikom sesuai dengan jadwal, sesi, dan ketentuan yang ditetapkan oleh program studi. Mahasiswa diminta untuk melakukan pendaftaran melalui tautan di bawah ini.</p><p><strong>Tahap Pendaftaran & Pelaksanaan</strong></p><p>Peserta melakukan pengisian data diri melalui portal resmi LSP Polsri pada laman berikut: <u><a href="#" target="_blank" rel="noopener">https://lsp.polsri.ac.id/pendaftaran</a></u>. Batas akhir pendaftaran online: <strong>17 Mei 2026</strong>.</p><p>Untuk jenjang D4 Manajemen Informatika, pilihan skema yang dibuka yaitu: <a href="#" target="_blank" rel="noopener">Skema Junior Web Programmer</a> & <a href="#" target="_blank" rel="noopener">Skema System Analyst.</a></p><p><strong>Tahap Penyerahan Berkas Fisik (Hardcopy)</strong></p><p>Peserta menyerahkan berkas fisik berupa:</p><ol><li><p>Formulir APL-01 yang telah diisi lengkap. Form dapat diunduh pada link dibawah ini<br><code>https://drive.google.com/drive/folders/1pvggz6DFS3WHzWQQUh1nufuysPkUOEsO?usp=sharing</code></p><p>(<em>diisi menggunakan huruf kapital, tinta hitam, tanpa coretan, dan ditandatangani</em>)</p></li><li><p>Pas foto terbaru background merah ukuran 3x4 sebanyak 2 lembar</p></li><li><p>Fotokopi KTP</p></li><li><p>Fotokopi KTM</p></li><li><p>KHS Semester 1–7 (lengkap)</p></li><li><p>Sertifikat Magang atau dokumen pendukung lainnya</p></li></ol><p>Seluruh berkas diserahkan kepada LSP melalui Admin TUK (<strong>Ibu Riska</strong>) dalam kondisi lengkap. Batas akhir berkas diterima: <strong>20 Mei 2026 pukul 14.00 WIB.</strong></p><p>Pengiriman berkas ke LSP dilakukan secara bertahap, sehingga mahasiswa sangat disarankan untuk mengumpulkan berkas lebih awal agar proses verifikasi dapat segera dilakukan. Jangan menunggu batas akhir pengumpulan.</p><p><strong>Petunjuk Pendaftaran</strong></p><p>Tata cara pengisian APL-01 terdapat pada tautan berikut:<br><code>https://drive.google.com/file/d/1p_XbG0g5bQgN7ho0WKSZrbiIYGv_Ucka/view?usp=sharing </code></p><p><strong>Informasi Pelaksanaan Ujikom</strong></p><p>Hari/Tanggal Pelaksanaan : <strong>10 – 12 Juni 2026</strong><br>Waktu                             : 08.00 s.d selesai<br>Tempat                           : Laboratorium Komputer Manajemen Informatika</p><p>Informasi pembagian sesi, teknis pelaksanaan, serta ketentuan lainnya akan diumumkan lebih lanjut melalui <em>WAG</em> & Website Jurusan.</p><p>Mahasiswa yang tidak mengikuti tahapan pendaftaran dan pelaksanaan Ujikom berarti tidak memanfaatkan fasilitas kompetensi yang telah disediakan melalui pembiayaan UKT serta kehilangan kesempatan memperoleh sertifikasi kompetensi tanpa biaya tambahan dari kampus.</p><p>Demikian pengumuman ini disampaikan untuk menjadi perhatian seluruh mahasiswa.</p>',
        'gambar'     => 'berita_ujikom.jpg',
        'kategori'   => 'berita',
        'status'     => 'publish',
        'created_at' => '2026-05-12 06:59:28'
    ],
    [
        'judul'      => 'Dosen Manajemen Informatika Ikuti Pelatihan Asesor Kompetensi BNSP guna Tingkatkan Kualitas Pendidikan Vokasi',
        'slug'       => 'dosen-manajemen-informatika-ikuti-pelatihan-asesor-kompetensi-bnsp-guna-tingkatkan-kualitas-pendidikan-vokasi',
        'konten'     => '<p><strong>PALEMBANG</strong> – Sejumlah dosen dari Program Studi Manajemen Informatika (MI) mengikuti Pelatihan Calon Asesor Kompetensi yang diselenggarakan oleh Lembaga Sertifikasi Profesi (LSP) Politeknik Negeri Sriwijaya (Polsri). Pelatihan intensif selama lima hari ini dilaksanakan di Hotel Batiqa, Palembang.</p><p>Pelatihan ini bertujuan untuk mencetak asesor-asesor baru yang tersertifikasi secara nasional oleh Badan Nasional Sertifikasi Profesi (BNSP). Keikutsertaan dosen MI dalam kegiatan ini merupakan langkah strategis untuk memperkuat ekosistem pendidikan vokasi, memastikan lulusan memiliki kompetensi yang selaras dengan kebutuhan industri.</p><p>Selama pelatihan, peserta dibekali dengan materi regulasi sistem sertifikasi nasional, pengembangan perangkat asesmen, dan metode uji kompetensi. Setelah lulus asesmen, para dosen ini akan memiliki wewenang untuk melakukan uji kompetensi bagi mahasiswa, khususnya di bawah naungan LSP Polsri, guna meningkatkan mutu dan kredibilitas lulusan.</p>',
        'gambar'     => 'berita_asesor.jpg',
        'kategori'   => 'berita',
        'status'     => 'publish',
        'created_at' => '2026-04-28 08:37:21'
    ],
];

echo "<h3>Memulai Proses Pembaruan Berita...</h3>";
$inserted = 0;
$updated = 0;

foreach ($new_news as $n) {
    try {
        // Cek apakah berita dengan slug tersebut sudah ada
        $check = $db->prepare("SELECT id FROM berita WHERE slug = :slug");
        $check->execute(['slug' => $n['slug']]);
        $existing = $check->fetch();
        
        if ($existing) {
            // Update berita yang sudah ada
            $stmt = $db->prepare("UPDATE berita SET 
                judul = :judul, 
                konten = :konten, 
                gambar = :gambar, 
                kategori = :kategori, 
                status = :status,
                created_at = :created_at
                WHERE id = :id");
            $stmt->execute([
                'judul'      => $n['judul'],
                'konten'     => $n['konten'],
                'gambar'     => $n['gambar'],
                'kategori'   => $n['kategori'],
                'status'     => $n['status'],
                'created_at' => $n['created_at'],
                'id'         => $existing['id']
            ]);
            $updated++;
            echo "✓ Berita diperbarui: " . htmlspecialchars($n['judul']) . "<br>";
        } else {
            // Tambahkan berita baru
            $stmt = $db->prepare("INSERT INTO berita (judul, slug, konten, gambar, kategori, status, created_at) 
                VALUES (:judul, :slug, :konten, :gambar, :kategori, :status, :created_at)");
            $stmt->execute([
                'judul'      => $n['judul'],
                'slug'       => $n['slug'],
                'konten'     => $n['konten'],
                'gambar'     => $n['gambar'],
                'kategori'   => $n['kategori'],
                'status'     => $n['status'],
                'created_at' => $n['created_at']
            ]);
            $inserted++;
            echo "✓ Berita baru ditambahkan: " . htmlspecialchars($n['judul']) . "<br>";
        }
    } catch (Exception $e) {
        echo "❌ Gagal memproses: " . htmlspecialchars($n['judul']) . " - Error: " . htmlspecialchars($e->getMessage()) . "<br>";
    }
}

echo "<br><b>Pembaruan selesai!</b> Berita baru ditambahkan: $inserted, Berita diperbarui: $updated.<br>";
