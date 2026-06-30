<?php
require_once dirname(__DIR__) . '/includes/config.php';

try {
    $db = getDB();
    
    // 1. Table pengumuman
    $db->exec("CREATE TABLE IF NOT EXISTS pengumuman (
        id INT AUTO_INCREMENT PRIMARY KEY,
        judul VARCHAR(255) NOT NULL,
        kategori VARCHAR(100) DEFAULT 'Akademik',
        tanggal DATE NOT NULL,
        link_info VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Tabel 'pengumuman' berhasil dibuat/diverifikasi.\n";

    // 2. Table dokumen
    $db->exec("CREATE TABLE IF NOT EXISTS dokumen (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        deskripsi TEXT DEFAULT NULL,
        file_name VARCHAR(255) NOT NULL,
        kategori VARCHAR(100) DEFAULT 'Umum',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Tabel 'dokumen' berhasil dibuat/diverifikasi.\n";

    // 3. Table kunjungan
    $db->exec("CREATE TABLE IF NOT EXISTS kunjungan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip_address VARCHAR(45) NOT NULL,
        tanggal DATE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_visit (ip_address, tanggal)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Tabel 'kunjungan' berhasil dibuat/diverifikasi.\n";

    // Seed Pengumuman
    $checkP = $db->query("SELECT COUNT(*) FROM pengumuman")->fetchColumn();
    if ($checkP == 0) {
        $stmt = $db->prepare("INSERT INTO pengumuman (judul, kategori, tanggal, link_info) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Pendaftaran PKL Tahun Akademik 2026/2027', 'Akademik', '2026-05-25', '']);
        $stmt->execute(['Ujian Akhir Semester (UAS) Genap', 'Ujian', '2026-06-10', '']);
        $stmt->execute(['Pelaksanaan Yudisium Tahap I', 'Kelulusan', '2026-07-18', '']);
        $stmt->execute(['Penerimaan Mahasiswa Baru Mandiri', 'PMB', '2026-08-12', '']);
        echo "Seed data 'pengumuman' berhasil dimasukkan.\n";
    }

    // Seed Dokumen
    $checkD = $db->query("SELECT COUNT(*) FROM dokumen")->fetchColumn();
    if ($checkD == 0) {
        $stmt = $db->prepare("INSERT INTO dokumen (nama, deskripsi, file_name, kategori) VALUES (?, ?, ?, ?)");
        $stmt->execute(['Formulir Pengisian PKL (Format Word)', 'Template resmi formulir pendaftaran Praktik Kerja Lapangan mahasiswa MI Polsri.', 'form_pkl_template.docx', 'PKL']);
        $stmt->execute(['Buku Panduan Laporan Akhir (PDF)', 'Buku panduan lengkap penulisan Laporan Akhir D3 / Tugas Akhir D4 Manajemen Informatika.', 'panduan_la_2026.pdf', 'Laporan Akhir']);
        $stmt->execute(['Formulir Bebas Pustaka Jurusan', 'Surat keterangan bebas peminjaman perpustakaan tingkat jurusan sebagai syarat yudisium.', 'bebas_pustaka_mi.docx', 'Kelulusan']);
        echo "Seed data 'dokumen' berhasil dimasukkan.\n";
    }

    // Seed Kunjungan (7 days back)
    $checkK = $db->query("SELECT COUNT(*) FROM kunjungan")->fetchColumn();
    if ($checkK == 0) {
        $stmt = $db->prepare("INSERT IGNORE INTO kunjungan (ip_address, tanggal) VALUES (?, ?)");
        
        $today = new DateTime();
        for ($i = 0; $i < 15; $i++) {
            $dateStr = $today->format('Y-m-d');
            // Generate some random visitor count for this day
            $count = rand(15, 40);
            for ($c = 0; $c < $count; $c++) {
                $ip = "192.168.1." . rand(1, 254);
                $stmt->execute([$ip, $dateStr]);
            }
            $today->modify('-1 day');
        }
        echo "Seed data 'kunjungan' 15 hari terakhir berhasil dimasukkan.\n";
    }

    // Create assets/documents folder and dummy files
    $docDir = dirname(__DIR__) . '/assets/documents/';
    if (!is_dir($docDir)) {
        mkdir($docDir, 0755, true);
    }
    
    file_put_contents($docDir . 'form_pkl_template.docx', 'Dummy content for Form Pengisian PKL');
    file_put_contents($docDir . 'panduan_la_2026.pdf', 'Dummy content for Panduan Laporan Akhir PDF');
    file_put_contents($docDir . 'bebas_pustaka_mi.docx', 'Dummy content for Bebas Pustaka Jurusan');
    echo "Folder dan file dummy dokumen sukses dibuat.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
