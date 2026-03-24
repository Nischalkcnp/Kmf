<?php
require_once __DIR__ . '/config/config.php';
$pdo = getDb();
$stmt = $pdo->prepare("SELECT id FROM pages WHERE slug = ?");
$stmt->execute(['about']);
$aboutRow = $stmt->fetch();
$aboutId = $aboutRow ? $aboutRow['id'] : null;

if ($aboutId) {
    echo "About ID: $aboutId\n";
    // Check if history page already exists
    $stmt = $pdo->prepare("SELECT id FROM pages WHERE slug = ?");
    $stmt->execute(['history']);
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO pages (slug, title, content, meta_description, sort_order, parent_id) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute(['history', 'Our History', '<p>Our journey since 2015.</p>', 'Learn about the history and milestones of Kanchhi Maya Tamang Foundation.', 1, $aboutId]);
        echo "History page inserted.\n";
    } else {
        echo "History page already exists.\n";
    }
} else {
    echo "About page not found.\n";
}
