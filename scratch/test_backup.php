<?php
require_once __DIR__.'/../includes/config.php';
$db = getDB();

try {
    $tables = [];
    $result = $db->query("SHOW TABLES");
    while ($row = $result->fetch(PDO::FETCH_NUM)) {
        $tables[] = $row[0];
    }
    
    echo "Tables found: " . implode(', ', $tables) . "\n\n";
    
    foreach ($tables as $table) {
        echo "Processing table: $table\n";
        $query = $db->query("SHOW CREATE TABLE `$table`");
        $row = $query->fetch(PDO::FETCH_NUM);
        echo "Create table statement length: " . strlen($row[1]) . "\n";
        
        $query = $db->query("SELECT * FROM `$table`");
        $count = 0;
        while ($rowData = $query->fetch(PDO::FETCH_ASSOC)) {
            $count++;
        }
        echo "Rows count: $count\n\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
