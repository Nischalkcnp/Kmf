<?php
require_once dirname(__DIR__) . '/config/config.php';
requireLogin();
$adminTitle = 'Settings';

$pdo = getDb();
$keys = ['site_name','site_tagline','logo_url','email','phone','address','mission','vision','goal','facebook','twitter','linkedin','youtube'];
$settings = [];
foreach ($keys as $k) {
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$k]);
    $settings[$k] = $stmt->fetchColumn() ?: '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    foreach ($keys as $k) {
        $v = trim($_POST[$k] ?? '');
        $stmt = $pdo->prepare("UPDATE settings SET setting_value = ?, updated_at = NOW() WHERE setting_key = ?");
        $stmt->execute([$v, $k]);
    }
    redirect(BASE_URL . 'admin/settings.php?updated=1');
}

require_once __DIR__ . '/includes/header.php';
?>
<h1 class="text-2xl font-bold text-kmf-blue mb-6">Settings</h1>
<?php if (isset($_GET['updated'])): ?>
    <p class="text-green-600 mb-4">Settings saved.</p>
<?php endif; ?>
<form method="post" class="space-y-6 max-w-2xl">
    <?php echo csrfField(); ?>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
        <input type="text" name="site_name" value="<?php echo escape($settings['site_name']); ?>" class="w-full px-4 py-2 border rounded-lg">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
        <input type="text" name="site_tagline" value="<?php echo escape($settings['site_tagline']); ?>" class="w-full px-4 py-2 border rounded-lg">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Logo URL (path from root)</label>
        <input type="text" name="logo_url" value="<?php echo escape($settings['logo_url']); ?>" placeholder="assets/images/logo.jpg" class="w-full px-4 py-2 border rounded-lg">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email" value="<?php echo escape($settings['email']); ?>" class="w-full px-4 py-2 border rounded-lg">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
        <input type="text" name="phone" value="<?php echo escape($settings['phone']); ?>" class="w-full px-4 py-2 border rounded-lg">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
        <textarea name="address" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo escape($settings['address']); ?></textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Mission</label>
        <textarea name="mission" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo escape($settings['mission']); ?></textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Vision</label>
        <textarea name="vision" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo escape($settings['vision']); ?></textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Goal</label>
        <textarea name="goal" rows="3" class="w-full px-4 py-2 border rounded-lg"><?php echo escape($settings['goal']); ?></textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label><input type="url" name="facebook" value="<?php echo escape($settings['facebook']); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">Twitter URL</label><input type="url" name="twitter" value="<?php echo escape($settings['twitter']); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label><input type="url" name="linkedin" value="<?php echo escape($settings['linkedin']); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium text-gray-700 mb-1">YouTube URL</label><input type="url" name="youtube" value="<?php echo escape($settings['youtube']); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
    </div>
    <button type="submit" class="bg-kmf-orange text-white font-semibold px-6 py-2 rounded-lg hover:bg-orange-600">Save Settings</button>
</form>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
