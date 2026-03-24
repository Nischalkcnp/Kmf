<?php
require_once __DIR__ . '/config/config.php';
$pdo = getDb();

function exportTable($pdo, $table) {
    $stmt = $pdo->query("SELECT * FROM $table");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $sql = "-- Data for $table\n";
    foreach ($rows as $row) {
        $cols = array_keys($row);
        $vals = array_values($row);
        $vals = array_map(function($v) use ($pdo) {
            return $v === null ? 'NULL' : $pdo->quote($v);
        }, $vals);
        $sql .= "INSERT INTO $table (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ");\n";
    }
    return $sql . "\n";
}

$tables = ['admin_users', 'settings', 'pages', 'strategic_areas', 'programs', 'publications', 'news', 'events', 'team', 'partners', 'gallery', 'impact_stats'];
$fullSql = "SET FOREIGN_KEY_CHECKS = 0;\n";
foreach ($tables as $table) {
    try {
        $fullSql .= exportTable($pdo, $table);
    } catch (Exception $e) {
        echo "Error exporting $table: " . $e->getMessage() . "\n";
    }
}
$fullSql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

file_put_contents(__DIR__ . '/database/full_dump.sql', $fullSql);
echo "Database exported to database/full_dump.sql\n";
