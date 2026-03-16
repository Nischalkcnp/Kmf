<?php
require_once dirname(__DIR__) . '/config/config.php';
requireLogin();

$adminTitle = 'Dashboard';
$pdo = getDb();
$counts = [
    'pages' => $pdo->query("SELECT COUNT(*) FROM pages")->fetchColumn(),
    'areas' => $pdo->query("SELECT COUNT(*) FROM strategic_areas WHERE is_active = 1")->fetchColumn(),
    'programs' => $pdo->query("SELECT COUNT(*) FROM programs WHERE is_active = 1")->fetchColumn(),
    'publications' => $pdo->query("SELECT COUNT(*) FROM publications WHERE is_active = 1")->fetchColumn(),
    'news' => $pdo->query("SELECT COUNT(*) FROM news WHERE is_active = 1")->fetchColumn(),
    'events' => $pdo->query("SELECT COUNT(*) FROM events WHERE is_active = 1")->fetchColumn(),
    'contacts' => $pdo->query("SELECT COUNT(*) FROM contact_submissions")->fetchColumn(),
];

require_once __DIR__ . '/includes/header.php';
?>
<h1 class="text-2xl font-bold text-kmf-blue mb-6">Dashboard</h1>
<p class="text-gray-600 mb-8">Welcome, <?php echo escape($_SESSION['admin_username'] ?? 'Admin'); ?>.</p>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <a href="<?php echo BASE_URL; ?>admin/pages.php" class="bg-white rounded-lg shadow p-4 border border-gray-100 hover:border-kmf-blue">
        <p class="text-3xl font-bold text-kmf-blue"><?php echo (int)$counts['pages']; ?></p>
        <p class="text-gray-600 text-sm">Pages</p>
    </a>
    <a href="<?php echo BASE_URL; ?>admin/areas.php" class="bg-white rounded-lg shadow p-4 border border-gray-100 hover:border-kmf-blue">
        <p class="text-3xl font-bold text-kmf-blue"><?php echo (int)$counts['areas']; ?></p>
        <p class="text-gray-600 text-sm">Strategic Areas</p>
    </a>
    <a href="<?php echo BASE_URL; ?>admin/programs.php" class="bg-white rounded-lg shadow p-4 border border-gray-100 hover:border-kmf-blue">
        <p class="text-3xl font-bold text-kmf-blue"><?php echo (int)$counts['programs']; ?></p>
        <p class="text-gray-600 text-sm">Programs</p>
    </a>
    <a href="<?php echo BASE_URL; ?>admin/publications.php" class="bg-white rounded-lg shadow p-4 border border-gray-100 hover:border-kmf-blue">
        <p class="text-3xl font-bold text-kmf-blue"><?php echo (int)$counts['publications']; ?></p>
        <p class="text-gray-600 text-sm">Publications</p>
    </a>
    <a href="<?php echo BASE_URL; ?>admin/news.php" class="bg-white rounded-lg shadow p-4 border border-gray-100 hover:border-kmf-blue">
        <p class="text-3xl font-bold text-kmf-blue"><?php echo (int)$counts['news']; ?></p>
        <p class="text-gray-600 text-sm">News</p>
    </a>
    <a href="<?php echo BASE_URL; ?>admin/events.php" class="bg-white rounded-lg shadow p-4 border border-gray-100 hover:border-kmf-blue">
        <p class="text-3xl font-bold text-kmf-blue"><?php echo (int)$counts['events']; ?></p>
        <p class="text-gray-600 text-sm">Events</p>
    </a>
    <div class="bg-white rounded-lg shadow p-4 border border-gray-100">
        <p class="text-3xl font-bold text-kmf-blue"><?php echo (int)$counts['contacts']; ?></p>
        <p class="text-gray-600 text-sm">Contact submissions</p>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
