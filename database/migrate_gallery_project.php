<?php
/**
 * KMF Website - Safe Idempotent Database Migration
 * Adds conclude_date to programs and program_id to gallery.
 */
require_once dirname(__DIR__) . '/config/config.php';

// Force script to run in CLI or admin session only
if (php_sapi_name() !== 'cli' && (!isset($_SESSION['admin_id']))) {
    die("Unauthorized access. This script must be run via command line or logged-in admin user.");
}

try {
    $pdo = getDb();
    echo "Starting database migration...\n";

    // 1. Add conclude_date to programs table if it doesn't exist
    $stmt = $pdo->query("SHOW COLUMNS FROM programs LIKE 'conclude_date'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE programs ADD COLUMN conclude_date DATE NULL AFTER type");
        echo "[SUCCESS] Added 'conclude_date' column to 'programs' table.\n";
    } else {
        echo "[INFO] 'conclude_date' column already exists in 'programs' table.\n";
    }

    // 2. Add program_id to gallery table if it doesn't exist
    $stmt = $pdo->query("SHOW COLUMNS FROM gallery LIKE 'program_id'");
    if (!$stmt->fetch()) {
        $pdo->exec("ALTER TABLE gallery ADD COLUMN program_id INT UNSIGNED NULL AFTER title");
        echo "[SUCCESS] Added 'program_id' column to 'gallery' table.\n";

        // Add foreign key constraint
        $pdo->exec("ALTER TABLE gallery ADD CONSTRAINT fk_gallery_programs FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL");
        echo "[SUCCESS] Added foreign key constraint 'fk_gallery_programs' to 'gallery' table.\n";
    } else {
        echo "[INFO] 'program_id' column already exists in 'gallery' table.\n";
    }

    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "[ERROR] Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
