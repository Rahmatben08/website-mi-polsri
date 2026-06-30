<?php
// =============================================
// config/database.php — MI Polsri v2.0
// Konfigurasi Database Utama menggunakan PDO
// =============================================

$isLocal = false;
if (isset($_SERVER['HTTP_HOST'])) {
    $hostName = $_SERVER['HTTP_HOST'];
    if (strpos($hostName, 'localhost') !== false || $hostName === '127.0.0.1' || strpos($hostName, '.test') !== false) {
        $isLocal = true;
    }
} else {
    $isLocal = true;
}

if ($isLocal) {
    define('DB_HOST',    'localhost');
    define('DB_USER',    'root');
    define('DB_PASS',    '');
    define('DB_NAME',    'project_mi');
} else {
    define('DB_HOST',    'sql101.infinityfree.com');
    define('DB_USER',    'if0_41981917');
    define('DB_PASS',    'Beben0807');
    define('DB_NAME',    'if0_41981917_db_mi');
}
define('DB_CHARSET', 'utf8mb4');

/**
 * Mengambil instans koneksi database PDO secara global.
 * Menggunakan design pattern Singleton sederhana via static variable.
 * 
 * @return PDO
 */
function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            // Inisialisasi koneksi PDO dengan parameter DSN dan opsi optimasi
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Menampilkan error sebagai exception
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Hasil fetch berupa array asosiatif
                    PDO::ATTR_EMULATE_PREPARES   => false                  // Menggunakan prepared statement asli MySQL
                ]
            );
        } catch (PDOException $e) {
            // Tampilan error yang user-friendly jika koneksi gagal
            die('<div style="font-family:sans-serif;padding:2rem;background:#FEF2F2;
                 color:#991B1B;border-radius:8px;margin:20px;border:1px solid #FECACA;">
                 <h2>&#10060; Koneksi Database Gagal</h2>
                 <p>Pastikan MySQL (XAMPP/Laragon) berjalan dan database <strong>project_mi</strong> 
                 sudah diimport dari berkas <code>database.sql</code>.</p>
                 <small>' . htmlspecialchars($e->getMessage()) . '</small></div>');
        }
    }
    return $pdo;
}
