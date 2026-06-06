<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_areas');
$adminTitle = 'Strategic Areas';

$pdo = getDb();
$areas = $pdo->query("SELECT * FROM strategic_areas ORDER BY sort_order, id")->fetchAll();
$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM strategic_areas WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? '';
    $icon = trim($_POST['icon'] ?? '');
    $image_url = handleImageUpload('image_file', 'areas', $_POST['current_image_url'] ?? '');
    $sort = (int)($_POST['sort_order'] ?? 0);
    $active = isset($_POST['is_active']) ? 1 : 0;
    if ($id) {
        $stmt = $pdo->prepare("UPDATE strategic_areas SET title=?, slug=?, excerpt=?, content=?, icon=?, image_url=?, sort_order=?, is_active=? WHERE id=?");
        $stmt->execute([$title, $slug, $excerpt, $content, $icon, $image_url, $sort, $active, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO strategic_areas (title, slug, excerpt, content, icon, image_url, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->execute([$title, $slug, $excerpt, $content, $icon, $image_url, $sort, $active]);
    }
    redirect(BASE_URL . 'admin/areas.php?updated=1');
}

require_once __DIR__ . '/includes/header.php';
?>
<h1 class="text-2xl font-bold text-kmf-blue mb-6">Strategic Areas</h1>
<?php if (isset($_GET['updated'])): ?><p class="text-green-600 mb-4">Saved.</p><?php endif; ?>

<?php if ($edit): ?>
<form method="post" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 mb-6 max-w-3xl">
    <?php echo csrfField(); ?>
    <input type="hidden" name="id" value="<?php echo $edit['id']; ?>">
    <input type="hidden" name="current_image_url" value="<?php echo escape($edit['image_url'] ?? ''); ?>">
    <div class="space-y-4">
        <div><label class="block text-sm font-medium mb-1">Title</label><input type="text" name="title" value="<?php echo escape($edit['title']); ?>" class="w-full px-4 py-2 border rounded-lg" required></div>
        <div><label class="block text-sm font-medium mb-1">Slug</label><input type="text" name="slug" value="<?php echo escape($edit['slug']); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium mb-1">Excerpt</label><textarea name="excerpt" rows="2" class="w-full px-4 py-2 border rounded-lg"><?php echo escape($edit['excerpt']); ?></textarea></div>
        <div><label class="block text-sm font-medium mb-1">Content (HTML)</label><textarea name="content" rows="6" class="w-full px-4 py-2 border rounded-lg"><?php echo escape($edit['content']); ?></textarea></div>
        <div class="flex gap-4">
            <div><label class="block text-sm font-medium mb-1">Icon (education|people|health)</label><input type="text" name="icon" value="<?php echo escape($edit['icon']); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
            <div><label class="block text-sm font-medium mb-1">Sort</label><input type="number" name="sort_order" value="<?php echo (int)$edit['sort_order']; ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        </div>
        <div>
            <label class="block text-sm font-medium mb-1">Image (Upload file)</label>
            <?php if (!empty($edit['image_url'])): ?>
                <div class="mt-2 text-sm text-gray-500">Current Preview:</div>
                <img src="<?php echo BASE_URL . $edit['image_url']; ?>" class="mt-1 h-32 w-auto rounded border" alt="Preview">
            <?php endif; ?>
            <input type="file" name="image_file" class="w-full text-sm mt-2">
        </div>
        <div><label><input type="checkbox" name="is_active" value="1" <?php echo $edit['is_active'] ? 'checked' : ''; ?>> Active</label></div>
    </div>
    <div class="mt-4"><button type="submit" class="bg-kmf-orange text-white px-4 py-2 rounded-lg">Save</button> <a href="<?php echo BASE_URL; ?>admin/areas.php" class="text-gray-600 ml-2">Cancel</a></div>
</form>
<?php endif; ?>

<table class="bg-white rounded-lg shadow overflow-hidden w-full max-w-3xl">
    <thead class="bg-gray-100"><tr><th class="text-left p-3">Title</th><th class="text-left p-3">Slug</th><th class="p-3">Sort</th><th class="p-3">Actions</th></tr></thead>
    <tbody>
        <?php foreach ($areas as $a): ?>
        <tr class="border-t">
            <td class="p-3">
                <div class="flex items-center gap-3">
                    <?php if ($a['image_url']): ?>
                        <img src="<?php echo BASE_URL . $a['image_url']; ?>" class="w-10 h-10 object-cover rounded shadow-sm" alt="">
                    <?php endif; ?>
                    <?php echo escape($a['title']); ?>
                </div>
            </td>
            <td class="p-3"><?php echo escape($a['slug']); ?></td>
            <td class="p-3 text-center"><?php echo (int)$a['sort_order']; ?></td>
            <td class="p-3 text-center"><a href="?edit=<?php echo $a['id']; ?>" class="text-kmf-orange hover:underline font-medium">Edit</a></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
