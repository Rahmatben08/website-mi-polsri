<?php
// admin/backup.php — Cadangan Database Sekali Klik
require_once __DIR__.'/../includes/config.php';
requireAdminAuth();

try {
    $db = getDB();
    
    // Proteksi Keamanan CSRF
    $token = $_GET['token'] ?? '';
    if (!validateCsrfToken($token)) {
        die('Token keamanan tidak valid. Silakan akses melalui dashboard.');
    }
    
    // Ambil daftar tabel
    $tables = [];
    $result = $db->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
    
    $sqlScript = "-- ====================================================\n";
    $sqlScript .= "-- Database Backup: Manajemen Informatika Polsri\n";
    $sqlScript .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n";
    $sqlScript .= "-- ====================================================\n\n";
    $sqlScript .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
    
    foreach ($tables as $table) {
        $sqlScript .= "-- ----------------------------------------------------\n";
        $sqlScript .= "-- Table structure for table `$table`\n";
        $sqlScript .= "-- ----------------------------------------------------\n";
        $sqlScript .= "DROP TABLE IF EXISTS `$table`;\n";
        
        $query = $db->query("SHOW CREATE TABLE `$table`");
        $row = $query->fetch(PDO::FETCH_NUM);
        $sqlScript .= $row[1] . ";\n\n";
        
        $sqlScript .= "-- ----------------------------------------------------\n";
        $sqlScript .= "-- Dumping data for table `$table`\n";
        $sqlScript .= "-- ----------------------------------------------------\n";
        
        $query = $db->query("SELECT * FROM `$table`");
        $hasRows = false;
        
        while ($rowData = $query->fetch(PDO::FETCH_ASSOC)) {
            $hasRows = true;
            $keys = array_keys($rowData);
            $values = array_values($rowData);
            
            $fields = implode("`, `", $keys);
            $escapedValues = array_map(function($val) use ($db) {
                if ($val === null) {
                    return 'NULL';
                }
                return $db->quote($val);
            }, $values);
            
            $vals = implode(", ", $escapedValues);
            $sqlScript .= "INSERT INTO `$table` (`$fields`) VALUES ($vals);\n";
        }
        
        if (!$hasRows) {
            $sqlScript .= "-- (No data in this table)\n";
        }
        $sqlScript .= "\n\n";
    }
    
    $sqlScript .= "SET FOREIGN_KEY_CHECKS=1;\n";
    
    // Log the backup activity
    logAktivitas("Cadangan Database", "Melakukan unduh cadangan database SQL.");
    
    // Bersihkan buffer output sebelum mengirim file untuk mencegah file kosong / korup
    if (ob_get_level()) {
        ob_end_clean();
    }
    
    // Set headers to force download
    $fileName = 'backup_mi_' . date('Y-m-d_H-i-s') . '.sql';
    header('Content-Type: application/sql');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Length: ' . strlen($sqlScript));
    
    echo $sqlScript;
    exit;
    
} catch (Exception $e) {
    if (ob_get_level()) {
        ob_end_clean();
    }
    setFlash('danger', 'Gagal mencadangkan database: ' . $e->getMessage());
    redirect(APP_URL . '/admin/dashboard.php');
}
