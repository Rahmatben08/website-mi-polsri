<?php
require_once __DIR__.'/../includes/config.php';
$db = getDB();
$q = $db->query("SHOW TABLES");
$tables = $q->fetchAll(PDO::FETCH_COLUMN);
echo "Tables: " . implode(", ", $tables) . "\n";

foreach ($tables as $t) {
    echo "\nTable: $t\n";
    $cols = $db->query("DESCRIBE `$t`")->fetchAll();
    foreach ($cols as $c) {
        echo " - {$c['Field']} ({$c['Type']})\n";
    }
}
