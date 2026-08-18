<?php
/**
 * KMF Website - Master Database Migration Runner
 * Safely and idempotently executes all required SQL and PHP database migrations.
 */
require_once dirname(__DIR__) . '/config/config.php';

// Force script to run in CLI or admin session only
if (php_sapi_name() !== 'cli' && (!isset($_SESSION['admin_id']))) {
    die("Unauthorized access. This script must be run via command line or logged-in admin user.\n");
}

try {
    $pdo = getDb();
    echo "==================================================\n";
    echo "Starting Master Database Migration Runner\n";
    echo "==================================================\n\n";

    // 1. Run migration_iam.sql (creates roles, permissions, role_permissions tables and seeds default records)
    echo "1. Checking IAM setup...\n";
    $rolesTableExists = false;
    try {
        $pdo->query("SELECT 1 FROM roles LIMIT 1");
        $rolesTableExists = true;
    } catch (PDOException $e) {
        $rolesTableExists = false;
    }

    if (!$rolesTableExists) {
        echo "   - Table 'roles' not found. Executing migration_iam.sql...\n";
        $iamSqlFile = __DIR__ . '/migration_iam.sql';
        if (file_exists($iamSqlFile)) {
            $sql = file_get_contents($iamSqlFile);
            // Remove 'USE kmf_website;' or other database selectors if present to use current PDO database context
            $sql = preg_replace('/^USE\s+\w+;/i', '', $sql);
            $pdo->exec($sql);
            echo "   [SUCCESS] Applied migration_iam.sql\n";
        } else {
            echo "   [ERROR] migration_iam.sql not found!\n";
        }
    } else {
        echo "   [INFO] Roles and permissions already set up.\n";
    }

    // 2. Safely add role_id and status columns to admin_users table
    echo "\n2. Checking admin_users columns...\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM admin_users LIKE 'role_id'");
    if (!$stmt->fetch()) {
        echo "   - Column 'role_id' missing in 'admin_users'. Altering table...\n";
        $pdo->exec("ALTER TABLE admin_users ADD COLUMN role_id INT UNSIGNED NULL AFTER email");
        $pdo->exec("ALTER TABLE admin_users ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active' AFTER role_id");
        $pdo->exec("ALTER TABLE admin_users ADD CONSTRAINT fk_admin_users_roles FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL");
        
        // Update default admin to be Super Admin (role_id = 1)
        $pdo->exec("UPDATE admin_users SET role_id = 1 WHERE username = 'admin'");
        echo "   [SUCCESS] Added 'role_id' and 'status' to 'admin_users' and assigned Super Admin to default admin.\n";
    } else {
        echo "   [INFO] 'role_id' already exists in 'admin_users'.\n";
    }

    // 3. Check and run impact.sql (creates impact_stats and seeds it)
    echo "\n3. Checking impact statistics...\n";
    $impactTableExists = false;
    try {
        $pdo->query("SELECT 1 FROM impact_stats LIMIT 1");
        $impactTableExists = true;
    } catch (PDOException $e) {
        $impactTableExists = false;
    }

    if (!$impactTableExists) {
        echo "   - Table 'impact_stats' not found. Executing impact.sql...\n";
        $impactSqlFile = __DIR__ . '/impact.sql';
        if (file_exists($impactSqlFile)) {
            $sql = file_get_contents($impactSqlFile);
            $sql = preg_replace('/^USE\s+\w+;/i', '', $sql);
            $pdo->exec($sql);
            echo "   [SUCCESS] Applied impact.sql\n";
        } else {
            echo "   [ERROR] impact.sql not found!\n";
        }
    } else {
        echo "   [INFO] 'impact_stats' already exists.\n";
    }

    // 4. Run other php migration scripts
    echo "\n4. Running project migration scripts...\n";
    
    $scripts = [
        'migrate_gallery_project.php' => 'Gallery/Programs columns migration',
        'migrate_about_us_gallery.php' => 'Gallery About Us column migration',
        'create_strategic_area_photos.php' => 'Strategic area photos table migration',
        'create_program_photos.php' => 'Program photos table and seeding migration',
        'seed_area_photos.php' => 'Strategic area photos seeding'
    ];

    foreach ($scripts as $file => $desc) {
        $path = __DIR__ . '/' . $file;
        if (file_exists($path)) {
            echo "   - Executing $desc ($file):\n";
            // Buffer output to indent it
            ob_start();
            include $path;
            $output = ob_get_clean();
            echo "     " . str_replace("\n", "\n     ", trim($output)) . "\n";
        } else {
            echo "   - [WARNING] Migration file $file not found!\n";
        }
    }

    echo "\n==================================================\n";
    echo "Master Migration completed successfully!\n";
    echo "==================================================\n";

} catch (Exception $e) {
    echo "\n[ERROR] Master Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
