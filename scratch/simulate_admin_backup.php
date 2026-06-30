<?php
// scratch/simulate_admin_backup.php
// Set up admin session simulation
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION['admin_id'] = 1;
$_SESSION['admin_user'] = 'admin';
$_SESSION['csrf_token'] = 'mock_token_123';

// Set up GET token to match
$_GET['token'] = 'mock_token_123';

// Buffer the output so we can capture it
ob_start();
include __DIR__ . '/../admin/backup.php';
$output = ob_get_clean();

// Get the sent headers
$headers = headers_list();

echo "--- Sent Headers ---\n";
foreach ($headers as $h) {
    echo $h . "\n";
}

echo "\n--- Output Content (First 500 chars) ---\n";
echo substr($output, 0, 500) . "\n";
echo "Total Length: " . strlen($output) . " bytes\n";
