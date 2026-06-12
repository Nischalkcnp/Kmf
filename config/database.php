<?php
/**
 * KMF Website - Database connection (MySQL)
 * Loads local credentials if database.local.php exists,
 * otherwise falls back to production cPanel credentials.
 */

// --- Local override (git-ignored, never deployed) ---
$_localCfg = __DIR__ . '/database.local.php';
if (file_exists($_localCfg)) {
    require_once $_localCfg;
} else {
    // --- Production: cPanel Hosting ---
    define('DB_HOST',    'localhost');
    define('DB_NAME',    'kmtfound_kmf_website');
    define('DB_USER',    'kmtfound_kmf_user');
    define('DB_PASS',    'Kmft#2026!');
}

define('DB_CHARSET', 'utf8mb4');

function getDb(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $opts = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $opts);
    }
    return $pdo;
}
