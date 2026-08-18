<?php
/**
 * KMF Website - Safe Idempotent Database Migration
 * Adds is_about_us column to gallery table.
 */
require_once dirname(__DIR__) . '/config/config.php';

// Force script to run in CLI or admin session only
if (php_sapi_name() !== 'cli' && (!isset($_SESSION['admin_id']))) {
    die("Unauthorized access. This script must be run via command line or logged-in admin user.");
}

try {
    $pdo = getDb();
    echo "Starting About Us gallery database migration...\n";

    // Add is_about_us to gallery table if it doesn't exist
    $stmt = $pdo->query("SHOW COLUMNS FROM gallery LIKE 'is_about_us'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE gallery ADD COLUMN is_about_us TINYINT(1) DEFAULT 0 AFTER is_active");
        echo "[SUCCESS] Added 'is_about_us' column to 'gallery' table.\n";
    } else {
        echo "[INFO] 'is_about_us' column already exists in 'gallery' table.\n";
    }

    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "[ERROR] Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
