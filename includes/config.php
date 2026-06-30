<?php
// =============================================
// includes/config.php — MI Polsri v2.0
// =============================================
require_once dirname(__DIR__) . '/config/database.php';

define('APP_NAME',       'MI Polsri');
define('APP_FULL_NAME',  'Jurusan Manajemen Informatika');
define('APP_UNIVERSITY', 'Politeknik Negeri Sriwijaya');
// Deteksi host secara dinamis agar port apa pun yang digunakan di browser (8080, 88, dll) otomatis terdeteksi
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isLocal = (strpos($host, 'localhost') !== false || $host === '127.0.0.1' || strpos($host, '.test') !== false);

if (!$isLocal) {
    $protocol = "https://";
} else {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' 
                 || ($_SERVER['SERVER_PORT'] ?? 80) == 443 
                 || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
                 || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on')
                ) ? "https://" : "http://";
}

$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$subfolder = (strpos($scriptName, '/project-mi/') !== false) ? '/project-mi' : '';
define('APP_URL',        $protocol . $host . $subfolder);
define('APP_VERSION',    '2.0.0');

$settings = [];
try {
    $dbInstance = getDB();
    $sQuery = $dbInstance->query("SELECT kunci, nilai FROM pengaturan")->fetchAll(PDO::FETCH_KEY_PAIR);
    if ($sQuery) {
        $settings = $sQuery;
    }
} catch (Exception $e) {
    // Database or table might not exist yet during initial setup
}

define('APP_EMAIL',   $settings['email'] ?? 'mi@polsri.ac.id');
define('APP_PHONE',   $settings['telepon'] ?? '0711 321234');
define('APP_ADDRESS', $settings['alamat'] ?? 'Jl. Sungai Sahang No.3654, Lorok Pakjo, Kec. Ilir Bar. I, Palembang 30151');
define('APP_FB',      $settings['facebook'] ?? '#');
define('APP_IG',      $settings['instagram'] ?? '#');
define('APP_YT',      $settings['youtube'] ?? '#');

if (session_status() === PHP_SESSION_NONE) session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Auto-track visit on public page loads
if (strpos($_SERVER['SCRIPT_NAME'] ?? '', '/admin/') === false) {
    trackVisit();
}


/* ── Helper Functions ── */
function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token']))
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function validateCsrfToken(string $t): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $t);
}
function redirect(string $url): void { header("Location: $url"); exit(); }
function setFlash(string $type, string $msg): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $msg];
}
function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash']; unset($_SESSION['flash']); return $f;
    }
    return null;
}
function formatTanggal(string $date): string {
    $b = ['','Januari','Februari','Maret','April','Mei','Juni',
          'Juli','Agustus','September','Oktober','November','Desember'];
    return date('j', strtotime($date)).' '
          .$b[(int)date('n', strtotime($date))].' '
          .date('Y', strtotime($date));
}
function makeSlug(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}
function truncate(string $text, int $len = 150): string {
    $text = strip_tags($text);
    return mb_strlen($text) > $len ? mb_substr($text, 0, $len).'...' : $text;
}
function isAdminLoggedIn(): bool { return !empty($_SESSION['admin_id']); }
function requireAdminAuth(): void {
    if (!isAdminLoggedIn()) redirect(APP_URL.'/admin/login.php');
}
function isActive(string $page): string {
    $current = basename($_SERVER['PHP_SELF']);
    if ($page === 'berita.php' && in_array($current, ['berita.php', 'berita_tambah.php', 'berita_edit.php'])) return 'active';
    if ($page === 'dosen.php' && in_array($current, ['dosen.php'])) return 'active';
    if ($page === 'galeri.php' && in_array($current, ['galeri.php'])) return 'active';
    if ($page === 'prestasi.php' && in_array($current, ['prestasi.php'])) return 'active';
    if ($page === 'pesan.php' && in_array($current, ['pesan.php'])) return 'active';
    if ($page === 'pengaturan.php' && in_array($current, ['pengaturan.php'])) return 'active';
    return $current === $page ? 'active' : '';
}

/**
 * Mengunggah file gambar ke folder /assets/images/
 * 
 * @param array $fileField Array $_FILES['input_name']
 * @param array &$errors Array referensi untuk menampung pesan error
 * @param string $defaultName Nama file lama/default jika tidak ada file yang diunggah
 * @param string $prefix Prefix nama file hasil unggah (opsional)
 * @return string Nama file gambar yang disimpan
 */
function uploadImage(array $fileField, array &$errors, string $defaultName = '', string $prefix = 'img'): string {
    if (!isset($fileField['error']) || $fileField['error'] === UPLOAD_ERR_NO_FILE) {
        return $defaultName;
    }

    if ($fileField['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Gagal mengunggah file gambar (Error: ' . $fileField['error'] . ').';
        return $defaultName;
    }

    // Maksimal 2MB
    $maxSize = 2 * 1024 * 1024;
    if ($fileField['size'] > $maxSize) {
        $errors[] = 'Ukuran file gambar terlalu besar. Maksimal 2MB.';
        return $defaultName;
    }

    // Validasi tipe file
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
    $mimeType = '';
    if (function_exists('finfo_open')) {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($fileInfo, $fileField['tmp_name']);
        finfo_close($fileInfo);
    } else {
        $mimeType = $fileField['type'];
    }

    if (!in_array($mimeType, $allowedTypes)) {
        $errors[] = 'Format file tidak valid. Hanya diperbolehkan JPG, JPEG, PNG, WEBP, atau GIF.';
        return $defaultName;
    }

    $ext = pathinfo($fileField['name'], PATHINFO_EXTENSION);
    if (empty($ext)) {
        $ext = ($mimeType === 'image/png') ? 'png' : (($mimeType === 'image/webp') ? 'webp' : (($mimeType === 'image/gif') ? 'gif' : 'jpg'));
    }
    
    $cleanPrefix = preg_replace('/[^a-zA-Z0-9_-]/', '', $prefix);
    $newFileName = $cleanPrefix . '_' . uniqid() . '.' . strtolower($ext);
    
    $targetDir = dirname(__DIR__) . '/assets/images/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    $targetPath = $targetDir . $newFileName;
    
    if (move_uploaded_file($fileField['tmp_name'], $targetPath)) {
        return $newFileName;
    } else {
        $errors[] = 'Gagal menyimpan file gambar ke server.';
        return $defaultName;
    }
}

// Log aktivitas admin ke database
function logAktivitas(string $aksi, string $rincian): void {
    try {
        $db = getDB();
        $adminUser = $_SESSION['admin_user'] ?? 'System';
        $stmt = $db->prepare("INSERT INTO log_aktivitas (admin_user, aksi, rincian) VALUES (?, ?, ?)");
        $stmt->execute([$adminUser, $aksi, $rincian]);
    } catch (Exception $e) {
        // Silently fail to avoid blocking administrative operations
    }
}

// Track unique IP visits daily
function trackVisit(): void {
    try {
        $db = getDB();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $tanggal = date('Y-m-d');
        
        $stmt = $db->prepare("INSERT IGNORE INTO kunjungan (ip_address, tanggal) VALUES (?, ?)");
        $stmt->execute([$ip, $tanggal]);
    } catch (Exception $e) {
        // Silently fail
    }
}

/**
 * Mengunggah file dokumen ke folder /assets/documents/
 */
function uploadDocument(array $fileField, array &$errors, string $defaultName = '', string $prefix = 'doc'): string {
    if (!isset($fileField['error']) || $fileField['error'] === UPLOAD_ERR_NO_FILE) {
        return $defaultName;
    }

    if ($fileField['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Gagal mengunggah file dokumen (Error: ' . $fileField['error'] . ').';
        return $defaultName;
    }

    // Maksimal 10MB
    $maxSize = 10 * 1024 * 1024;
    if ($fileField['size'] > $maxSize) {
        $errors[] = 'Ukuran file dokumen terlalu besar. Maksimal 10MB.';
        return $defaultName;
    }

    // Validasi ekstensi
    $ext = pathinfo($fileField['name'], PATHINFO_EXTENSION);
    $allowedExts = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', 'txt'];
    if (!in_array(strtolower($ext), $allowedExts)) {
        $errors[] = 'Format file tidak valid. Hanya diperbolehkan PDF, DOC, DOCX, XLS, XLSX, ZIP, atau RAR.';
        return $defaultName;
    }

    $cleanPrefix = preg_replace('/[^a-zA-Z0-9_-]/', '', $prefix);
    $newFileName = $cleanPrefix . '_' . uniqid() . '.' . strtolower($ext);
    
    $targetDir = dirname(__DIR__) . '/assets/documents/';
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    $targetPath = $targetDir . $newFileName;
    
    if (move_uploaded_file($fileField['tmp_name'], $targetPath)) {
        return $newFileName;
    } else {
        $errors[] = 'Gagal menyimpan file dokumen ke server.';
        return $defaultName;
    }
}


