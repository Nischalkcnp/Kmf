<?php
require_once __DIR__ . '/config/config.php';
$pdo = getDb();
$stmt = $pdo->query("DESCRIBE pages");
while ($row = $stmt->fetch()) {
    echo "{$row['Field']}: {$row['Type']}\n";
}
