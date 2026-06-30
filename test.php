<?php
// test.php — Uji koneksi dan tampilkan error
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h3>1. PHP Status</h3>";
echo "PHP is working! Version: " . phpversion() . "<br>";

echo "<h3>2. Menghubungkan ke Config</h3>";
if (file_exists('includes/config.php')) {
    echo "✓ File includes/config.php ditemukan.<br>";
    try {
        require_once 'includes/config.php';
        echo "✓ Berhasil me-require includes/config.php.<br>";
        
        echo "<h3>3. Menghubungkan ke Database</h3>";
        $db = getDB();
        if ($db) {
            echo "✓ Koneksi database berhasil!<br>";
            $stmt = $db->query("SELECT COUNT(*) FROM dosen");
            $count = $stmt->fetchColumn();
            echo "✓ Jumlah dosen di database: " . $count . "<br>";
            
            echo "<h3>4. Cek Tabel Admin</h3>";
            try {
                // Update password admin secara otomatis ke 'admin123'
                $newHash = '$2y$12$N7rWSMQWIj/NtQtu/2ZIWuaubTjdqTlhZU6sogbl81Cxvogs3QTtC';
                $db->prepare("UPDATE admin SET password = ? WHERE username = 'admin'")->execute([$newHash]);
                echo "✓ Berhasil memperbarui/mengoreksi password admin ke 'admin123' di database!<br><br>";

                // Perbaikan kolom is_read di tabel pesan_kontak jika belum ada
                try {
                    $db->query("SELECT is_read FROM pesan_kontak LIMIT 1");
                } catch (PDOException $eCol) {
                    $db->query("ALTER TABLE pesan_kontak ADD COLUMN is_read TINYINT(1) DEFAULT 0");
                    echo "✓ Berhasil menambahkan kolom 'is_read' ke tabel 'pesan_kontak'!<br><br>";
                }

                $stmtAdmin = $db->query("SELECT * FROM admin");
                $admins = $stmtAdmin->fetchAll();
                echo "✓ Jumlah user admin di database: " . count($admins) . "<br>";
                foreach ($admins as $a) {
                    echo "- Username: '" . htmlspecialchars($a['username']) . "'<br>";
                    echo "  Password Hash: " . htmlspecialchars($a['password']) . "<br>";
                    if (password_verify('admin123', $a['password'])) {
                        echo "  Verify 'admin123': <strong>SUCCESS</strong><br>";
                    } else {
                        echo "  Verify 'admin123': <strong>FAILED</strong><br>";
                    }
                }
            } catch (PDOException $eAdmin) {
                echo "✗ Gagal membaca tabel admin: " . $eAdmin->getMessage() . "<br>";
            }
        } else {
            echo "✗ Koneksi database mengembalikan nilai kosong.<br>";
        }
        
        echo "<h3>5. Diagnostik URL & Protokol HTTPS</h3>";
        echo "APP_URL: <strong>" . (defined('APP_URL') ? APP_URL : 'Belum didefinisikan') . "</strong><br>";
        echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'Tidak ada') . "<br>";
        echo "HTTPS Server Var: " . ($_SERVER['HTTPS'] ?? 'Tidak ada') . "<br>";
        echo "HTTP_X_FORWARDED_PROTO: " . ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? 'Tidak ada') . "<br>";
        echo "HTTP_X_FORWARDED_SSL: " . ($_SERVER['HTTP_X_FORWARDED_SSL'] ?? 'Tidak ada') . "<br>";
        echo "SERVER_PORT: " . ($_SERVER['SERVER_PORT'] ?? 'Tidak ada') . "<br>";
        echo "isLocal (Terdeteksi Lokal?): " . ($isLocal ? 'YA (Lokal)' : 'TIDAK (Live Hosting)') . "<br>";
        
    } catch (Throwable $e) {
        echo "✗ Terjadi error: " . $e->getMessage() . " di " . $e->getFile() . " baris " . $e->getLine() . "<br>";
    }
} else {
    echo "✗ File includes/config.php tidak ditemukan di direktori saat ini!<br>";
}
