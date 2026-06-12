<?php
/**
 * KMF Website - Programs Database Migration and Photos Seeding
 */
require_once dirname(__DIR__) . '/config/config.php';

// Force script to run in CLI or admin session only
if (php_sapi_name() !== 'cli' && (!isset($_SESSION['admin_id']))) {
    die("Unauthorized access. This script must be run via command line or logged-in admin user.");
}

try {
    $pdo = getDb();
    echo "Starting programs migration and seeding...\n";

    // 1. Add unique constraint on slug if it doesn't exist
    $stmtIndex = $pdo->query("SHOW INDEX FROM programs WHERE Key_name = 'uq_programs_slug'");
    if (!$stmtIndex->fetch()) {
        try {
            $pdo->exec("ALTER TABLE programs ADD UNIQUE INDEX uq_programs_slug (slug)");
            echo "[SUCCESS] Added unique constraint on 'slug' column in 'programs' table.\n";
        } catch (Exception $e) {
            echo "[WARNING] Could not create unique index on slug: " . $e->getMessage() . "\n";
        }
    } else {
        echo "[INFO] Unique index 'uq_programs_slug' already exists.\n";
    }

    // 2. Create program_photos table
    $createTableSql = "
        CREATE TABLE IF NOT EXISTS program_photos (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            program_id INT UNSIGNED NOT NULL,
            image_url VARCHAR(255) NOT NULL,
            sort_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_program_photos FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";
    $pdo->exec($createTableSql);
    echo "[SUCCESS] Table 'program_photos' created or already exists.\n";

    // 3. Clear and seed program photos
    $pdo->exec("DELETE FROM program_photos");
    echo "Cleared old program photos.\n";

    $photosSeed = [
        // Healthcare & Rural Logistics (ID 1)
        1 => [
            'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1516574187841-cb9cc2ca948b?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1581578731548-c64695cc6952?auto=format&fit=crop&w=800&q=80'
        ],
        // Education & Awareness (ID 2)
        2 => [
            'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=800&q=80'
        ],
        // Sewa Ghar (ID 3)
        3 => [
            'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1518391846015-55a9cc003b25?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1482862549707-f63cb32c5fd9?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1469571486040-af250c558d53?auto=format&fit=crop&w=800&q=80'
        ],
        // Environmental Protection (ID 4)
        4 => [
            'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1473448912268-2022ce9509d8?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=800&q=80'
        ],
        // Clean Drinking Water Initiative (ID 5)
        5 => [
            'https://images.unsplash.com/photo-1541888946425-d81bb19240f5?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1527018601619-a508a2be00cd?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1581244277943-fe4a9c777189?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1538300342682-be57f6d37061?auto=format&fit=crop&w=800&q=80'
        ],
        // Youth Vocational Skill Training (ID 6)
        6 => [
            'https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1515187029135-18ee286d815b?auto=format&fit=crop&w=800&q=80'
        ],
        // Himalayan Afforestation & Eco-Forestry (ID 7)
        7 => [
            'https://images.unsplash.com/photo-1448375240586-882707db888b?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1500485035595-cbe6f645feb1?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1473448912268-2022ce9509d8?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1511497584788-876760111969?auto=format&fit=crop&w=800&q=80'
        ]
    ];

    $insertStmt = $pdo->prepare("INSERT INTO program_photos (program_id, image_url, sort_order) VALUES (?, ?, ?)");

    foreach ($photosSeed as $programId => $images) {
        $stmt = $pdo->prepare("SELECT id FROM programs WHERE id = ?");
        $stmt->execute([$programId]);
        if ($stmt->fetch()) {
            foreach ($images as $index => $imageUrl) {
                $insertStmt->execute([$programId, $imageUrl, $index + 1]);
            }
            echo "Successfully seeded " . count($images) . " photos for Program ID $programId.\n";
        } else {
            echo "Skipped seeding for Program ID $programId: Program does not exist.\n";
        }
    }

    echo "Migration completed successfully!\n";
} catch (Exception $e) {
    echo "[ERROR] Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
