<?php
require_once __DIR__.'/../includes/config.php';
$db = getDB();

try {
    // 1. Create table log_aktivitas
    $db->exec("CREATE TABLE IF NOT EXISTS log_aktivitas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        admin_user VARCHAR(100) NOT NULL,
        aksi VARCHAR(100) NOT NULL,
        rincian TEXT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Tabel 'log_aktivitas' berhasil diverifikasi/dibuat!\n";

    // 2. Create table memo_catatan
    $db->exec("CREATE TABLE IF NOT EXISTS memo_catatan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        isi TEXT NOT NULL,
        warna VARCHAR(20) DEFAULT 'yellow',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
    echo "Tabel 'memo_catatan' berhasil diverifikasi/dibuat!\n";
    
    // Check if there are default notes, if empty insert one
    $count = $db->query("SELECT COUNT(*) FROM memo_catatan")->fetchColumn();
    if ($count == 0) {
        $db->exec("INSERT INTO memo_catatan (isi, warna) VALUES 
            ('Selamat datang di Panel Admin! Tulis catatan/memo harian Anda di sini.', 'yellow'),
            ('Jangan lupa untuk memperbarui berita pengumuman jurusan setiap hari Senin.', 'blue')");
        echo "Memo bawaan berhasil dimasukkan!\n";
    }

} catch (PDOException $e) {
    die("Error migrasi: " . $e->getMessage() . "\n");
}
