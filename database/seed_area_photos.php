<?php
/**
 * KMF Website - Seed Area Photos
 */
require_once dirname(__DIR__) . '/config/config.php';

// Force script to run in CLI or admin session only
if (php_sapi_name() !== 'cli' && (!isset($_SESSION['admin_id']))) {
    die("Unauthorized access. This script must be run via command line or logged-in admin user.");
}

try {
    $pdo = getDb();
    echo "Seeding strategic area photos...\n";

    // Clear existing photos first to make it clean/idempotent
    $pdo->exec("DELETE FROM strategic_area_photos");
    echo "Cleared old photos.\n";

    $photosSeed = [
        // Education (ID 7)
        7 => [
            'assets/images/hero-education.png',
            'assets/images/areas/1780729738_796fa3f0.jpg',
            'assets/images/get-involved-volunteers.jpg',
            'assets/images/about-women-community.jpg'
        ],
        // Healthcare (ID 8)
        8 => [
            'assets/images/hero-health.png',
            'assets/images/areas/1780729752_629fe14b.jpg',
            'assets/images/dummy_before.png',
            'assets/images/dummy_after.png'
        ],
        // Sewa Ghar (ID 9)
        9 => [
            'assets/images/areas/1780729767_cd4cc88b.jpg',
            'assets/images/hero-community.png',
            'assets/images/about-women-community.jpg',
            'assets/images/get-involved-volunteers.jpg'
        ],
        // Community (ID 12)
        12 => [
            'assets/images/hero-community.png',
            'assets/images/about-women-community.jpg',
            'assets/images/get-involved-volunteers.jpg',
            'assets/images/hero-education.png'
        ]
    ];

    $insertStmt = $pdo->prepare("INSERT INTO strategic_area_photos (area_id, image_url, sort_order) VALUES (?, ?, ?)");

    foreach ($photosSeed as $areaId => $images) {
        // Double-check if the strategic area exists in the database
        $stmt = $pdo->prepare("SELECT id FROM strategic_areas WHERE id = ?");
        $stmt->execute([$areaId]);
        if ($stmt->fetch()) {
            foreach ($images as $index => $imageUrl) {
                $insertStmt->execute([$areaId, $imageUrl, $index + 1]);
            }
            echo "Successfully seeded " . count($images) . " photos for Area ID $areaId.\n";
        } else {
            echo "Skipped seeding for Area ID $areaId: Area does not exist in DB.\n";
        }
    }

    echo "Photos seeding completed successfully!\n";
} catch (Exception $e) {
    echo "[ERROR] Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}
