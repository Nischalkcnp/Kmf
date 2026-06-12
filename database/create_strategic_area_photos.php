<?php
/**
 * KMF Website - Strategic Areas Photo Table Migration and Cleanup
 */
require_once dirname(__DIR__) . '/config/config.php';

// Force script to run in CLI or admin session only
if (php_sapi_name() !== 'cli' && (!isset($_SESSION['admin_id']))) {
    die("Unauthorized access. This script must be run via command line or logged-in admin user.");
}

try {
    $pdo = getDb();
    echo "Starting strategic areas migration and cleanup...\n";

    // 1. Delete duplicate strategic area records
    $duplicateIds = [1, 2, 3, 4, 5, 6, 11, 13];
    $placeholders = implode(',', array_fill(0, count($duplicateIds), '?'));
    
    // Check if they exist before deleting to be informative
    $stmt = $pdo->prepare("SELECT id, title FROM strategic_areas WHERE id IN ($placeholders)");
    $stmt->execute($duplicateIds);
    $found = $stmt->fetchAll();
    
    if (!empty($found)) {
        $deleteStmt = $pdo->prepare("DELETE FROM strategic_areas WHERE id IN ($placeholders)");
        $deleteStmt->execute($duplicateIds);
        echo "[SUCCESS] Removed " . count($found) . " duplicate strategic area records.\n";
    } else {
        echo "[INFO] No duplicate strategic area records to remove.\n";
    }

    // 2. Add unique constraint on slug if it doesn't exist
    // In MySQL, we can check if the index exists by querying statistics
    $stmtIndex = $pdo->query("SHOW INDEX FROM strategic_areas WHERE Key_name = 'uq_strategic_areas_slug'");
    if (!$stmtIndex->fetch()) {
        try {
            $pdo->exec("ALTER TABLE strategic_areas ADD UNIQUE INDEX uq_strategic_areas_slug (slug)");
            echo "[SUCCESS] Added unique constraint on 'slug' column in 'strategic_areas' table.\n";
        } catch (Exception $e) {
            echo "[WARNING] Could not create unique index on slug. If there are still duplicate slugs, clean them manually. Error: " . $e->getMessage() . "\n";
        }
    } else {
        echo "[INFO] Unique index 'uq_strategic_areas_slug' already exists.\n";
    }

    // 3. Create strategic_area_photos table
    $createTableSql = "
        CREATE TABLE IF NOT EXISTS strategic_area_photos (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            area_id INT UNSIGNED NOT NULL,
            image_url VARCHAR(255) NOT NULL,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_area_photos FOREIGN KEY (area_id) REFERENCES strategic_areas(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($createTableSql);
    echo "[SUCCESS] Table 'strategic_area_photos' created or already exists.\n";

    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "[ERROR] Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
