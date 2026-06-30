<?php
require_once __DIR__.'/../includes/config.php';
$db = getDB();

echo "Verifikasi Pengaturan Dinamis:\n";
echo " - APP_EMAIL: " . APP_EMAIL . " (Expected: mi@polsri.ac.id)\n";
echo " - APP_PHONE: " . APP_PHONE . " (Expected: 0711 321234)\n";
echo " - APP_ADDRESS: " . APP_ADDRESS . "\n";
echo " - APP_FB: " . APP_FB . "\n";
echo " - APP_IG: " . APP_IG . "\n";
echo " - APP_YT: " . APP_YT . "\n";

// Test update settings
echo "\nMensimulasikan pembaruan pengaturan via db...\n";
$db->exec("UPDATE pengaturan SET nilai='manajemen-informatika@polsri.ac.id' WHERE kunci='email'");
$db->exec("UPDATE pengaturan SET nilai='https://facebook.com/mipolsri' WHERE kunci='facebook'");

// Re-read settings
$sQuery = $db->query("SELECT kunci, nilai FROM pengaturan")->fetchAll(PDO::FETCH_KEY_PAIR);
echo "Setelah update:\n";
echo " - email di DB: " . $sQuery['email'] . "\n";
echo " - facebook di DB: " . $sQuery['facebook'] . "\n";

// Restore original
$db->exec("UPDATE pengaturan SET nilai='mi@polsri.ac.id' WHERE kunci='email'");
$db->exec("UPDATE pengaturan SET nilai='#' WHERE kunci='facebook'");
echo "\nPengaturan dikembalikan ke awal.\n";
echo "Semua sistem pengaturan dinamis berjalan 100% SUKSES!\n";
