<?php
require_once __DIR__ . '/config/config.php';
$pdo = getDb();
$stmt = $pdo->query("SELECT slug, title, image_url FROM strategic_areas");
while ($row = $stmt->fetch()) {
    echo "{$row['slug']} ({$row['title']}): {$row['image_url']}\n";
}
