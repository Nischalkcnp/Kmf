<?php
/**
 * KMF Website - Helper functions
 */

function getSetting(string $key, string $default = ''): string {
    static $settings = null;
    if ($settings === null) {
        $pdo = getDb();
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM settings");
        $settings = [];
        while ($row = $stmt->fetch()) {
            $settings[$row['setting_key']] = $row['setting_value'] ?? '';
        }
    }
    return $settings[$key] ?? $default;
}

function getPageBySlug(string $slug): ?array {
    $pdo = getDb();
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ? LIMIT 1");
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function getPageUrl(string $slug): string {
    // Custom mappings for special slugs to their respective static php files
    $customMappings = [
        'areas' => 'what-we-do.php',
        'publications' => 'resources.php',
        'news' => 'news.php?tab=gallery',
        'news-and-media' => 'news.php?tab=gallery',
    ];

    if (isset($customMappings[$slug])) {
        return BASE_URL . $customMappings[$slug];
    }

    if (file_exists(ROOT_PATH . $slug . '.php')) {
        return BASE_URL . $slug . '.php';
    }

    if (file_exists(ROOT_PATH . $slug)) {
        return BASE_URL . $slug;
    }

    return BASE_URL . 'view.php?slug=' . urlencode($slug);
}


function slugify(string $text): string {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    return strtolower($text);
}

function escape(?string $s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

function formatDate(?string $date, string $format = 'd M, Y'): string {
    if (!$date) return '';
    $t = strtotime($date);
    return $t ? date($format, $t) : '';
}

function csrfField(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="csrf_token" value="' . escape($_SESSION['csrf_token']) . '">';
}

function validateCsrf(): bool {
    return isset($_POST['csrf_token'], $_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

function isLoggedIn(): bool {
    return !empty($_SESSION['admin_id']);
}

function hasPermission(string $permission): bool {
    if (!isLoggedIn()) return false;
    
    static $userPermissions = null;
    if ($userPermissions === null) {
        $pdo = getDb();
        try {
            $stmt = $pdo->prepare("
                SELECT u.status, r.name as role_name, p.code_name 
                FROM admin_users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                LEFT JOIN role_permissions rp ON r.id = rp.role_id 
                LEFT JOIN permissions p ON rp.permission_id = p.id 
                WHERE u.id = ?
            ");
            $stmt->execute([$_SESSION['admin_id']]);
            $rows = $stmt->fetchAll();
            
            if (empty($rows) || $rows[0]['status'] !== 'active') {
                $userPermissions = [
                    'role' => '',
                    'perms' => []
                ];
            } else {
                $perms = [];
                foreach ($rows as $row) {
                    if ($row['code_name']) {
                        $perms[] = $row['code_name'];
                    }
                }
                $userPermissions = [
                    'role' => $rows[0]['role_name'] ?? '',
                    'perms' => $perms
                ];
            }
        } catch (Exception $e) {
            // Fallback in case migration hasn't run yet so admin user can still access migrate script
            $userPermissions = [
                'role' => 'Super Admin',
                'perms' => []
            ];
        }
    }
    
    if ($userPermissions['role'] === 'Super Admin') {
        return true;
    }
    
    return in_array($permission, $userPermissions['perms']);
}

function requirePermission(string $permission): void {
    requireLogin();
    if (!hasPermission($permission)) {
        $_SESSION['flash_error'] = "You do not have permission to access that section.";
        redirect(BASE_URL . 'admin/index.php');
    }
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . 'admin/login.php');
        exit;
    }
}

function redirect(string $url, int $code = 302): void {
    header('Location: ' . $url, true, $code);
    exit;
}

/**
 * Handle Single Image Upload
 * @param string $key Name attribute of the file input
 * @param string $subfolder Subfolder inside assets/images/
 * @param string $current_url Current image URL to return if no new file uploaded
 * @return string The path to the uploaded image or the current image URL
 */
function handleImageUpload(string $key, string $subfolder = 'uploads', string $current_url = ''): string {
    if (!isset($_FILES[$key]) || $_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
        return $current_url;
    }

    $uploadDir = dirname(__DIR__) . '/assets/images/' . trim($subfolder, '/') . '/';
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }

    $file = $_FILES[$key];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    if (!in_array($ext, $allowed)) {
        return $current_url;
    }

    $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return 'assets/images/' . trim($subfolder, '/') . '/' . $filename;
    }

    return $current_url;
}
