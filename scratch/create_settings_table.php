<?php
require_once __DIR__.'/../includes/config.php';
$db = getDB();

try {
    // 1. Buat tabel pengaturan
    $db->exec("CREATE TABLE IF NOT EXISTS pengaturan (
        id INT AUTO_INCREMENT PRIMARY KEY,
        kunci VARCHAR(100) UNIQUE NOT NULL,
        nilai TEXT
    )");
    echo "Tabel 'pengaturan' berhasil dibuat atau sudah ada.\n";

    // 2. Isi data default jika belum ada
    $defaultSettings = [
        'email' => 'mi@polsri.ac.id',
        'telepon' => '0711 321234',
        'alamat' => 'Jl. Sungai Sahang No.3654, Lorok Pakjo, Kec. Ilir Bar. I, Palembang 30151',
        'facebook' => '#',
        'instagram' => '#',
        'youtube' => '#'
    ];

    $stmt = $db->prepare("INSERT IGNORE INTO pengaturan (kunci, nilai) VALUES (?, ?)");
    foreach ($defaultSettings as $kunci => $nilai) {
        $stmt->execute([$kunci, $nilai]);
    }
    echo "Data pengaturan bawaan berhasil ditambahkan.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
