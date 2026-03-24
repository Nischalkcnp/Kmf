<?php
require_once __DIR__ . '/config/config.php';
$pdo = getDb();
$stmt = $pdo->query("SELECT slug, title FROM pages");
file_put_contents('slugs.json', json_encode($stmt->fetchAll(PDO::FETCH_ASSOC)));
?>
