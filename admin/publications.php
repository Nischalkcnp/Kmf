<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_publications');
$adminTitle = 'Publications';

$pdo = getDb();
$items = $pdo->query("SELECT * FROM publications ORDER BY published_at DESC, sort_order")->fetchAll();
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if ($id === 0) $edit = ['id'=>0,'title'=>'','slug'=>'','excerpt'=>'','type'=>'publication','file_url'=>'','image_url'=>'','published_at'=>'','sort_order'=>0,'is_active'=>1];
    else { $stmt = $pdo->prepare("SELECT * FROM publications WHERE id = ?"); $stmt->execute([$id]); $edit = $stmt->fetch(); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
    $excerpt = trim($_POST['excerpt'] ?? '');
    $type = in_array($_POST['type'] ?? '', ['publication','report','article']) ? $_POST['type'] : 'publication';
    $file_url = trim($_POST['file_url'] ?? '');
    $image_url = handleImageUpload('image_file', 'publications', $_POST['current_image_url'] ?? '');
    $published_at = trim($_POST['published_at'] ?? '') ?: null;
    $sort = (int)($_POST['sort_order'] ?? 0);
    $active = isset($_POST['is_active']) ? 1 : 0;
    if ($id) {
        $stmt = $pdo->prepare("UPDATE publications SET title=?, slug=?, excerpt=?, type=?, file_url=?, image_url=?, published_at=?, sort_order=?, is_active=? WHERE id=?");
        $stmt->execute([$title, $slug, $excerpt, $type, $file_url, $image_url, $published_at, $sort, $active, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO publications (title, slug, excerpt, type, file_url, image_url, published_at, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$title, $slug, $excerpt, $type, $file_url, $image_url, $published_at, $sort, $active]);
    }
    redirect(BASE_URL . 'admin/publications.php?updated=1');
}

require_once __DIR__ . '/includes/header.php';
?>
<h1 class="text-2xl font-bold text-kmf-blue mb-6">Publications</h1>
<?php if (isset($_GET['updated'])): ?><p class="text-green-600 mb-4">Saved.</p><?php endif; ?>

<?php if ($edit !== null): ?>
<form method="post" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 mb-6 max-w-3xl">
    <?php echo csrfField(); ?>
    <input type="hidden" name="id" value="<?php echo (int)$edit['id']; ?>">
    <input type="hidden" name="current_image_url" value="<?php echo escape($edit['image_url'] ?? ''); ?>">
    <div class="space-y-4">
        <div><label class="block text-sm font-medium mb-1">Title</label><input type="text" name="title" value="<?php echo escape($edit['title']); ?>" class="w-full px-4 py-2 border rounded-lg" required></div>
        <div><label class="block text-sm font-medium mb-1">Slug</label><input type="text" name="slug" value="<?php echo escape($edit['slug']); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium mb-1">Excerpt</label><textarea name="excerpt" rows="2" class="w-full px-4 py-2 border rounded-lg"><?php echo escape($edit['excerpt']); ?></textarea></div>
        <div><label class="block text-sm font-medium mb-1">Type</label><select name="type" class="w-full px-4 py-2 border rounded-lg"><option value="publication" <?php echo ($edit['type']??'')==='publication'?'selected':''; ?>>Publication</option><option value="report" <?php echo ($edit['type']??'')==='report'?'selected':''; ?>>Report</option><option value="article" <?php echo ($edit['type']??'')==='article'?'selected':''; ?>>Article</option></select></div>
        <div><label class="block text-sm font-medium mb-1">File URL</label><input type="text" name="file_url" value="<?php echo escape($edit['file_url'] ?? ''); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium mb-1">Thumbnail Image (Upload file)</label>
        <?php if (!empty($edit['image_url'])): ?>
            <img src="<?php echo BASE_URL . escape($edit['image_url']); ?>" class="h-20 w-auto mb-2 rounded border">
        <?php endif; ?>
        <input type="file" name="image_file" class="w-full text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Published Date</label><input type="date" name="published_at" value="<?php echo escape($edit['published_at'] ?? ''); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium mb-1">Sort</label><input type="number" name="sort_order" value="<?php echo (int)($edit['sort_order'] ?? 0); ?>" class="w-24 px-4 py-2 border rounded-lg"></div>
        <div><label><input type="checkbox" name="is_active" value="1" <?php echo ($edit['is_active'] ?? 1) ? 'checked' : ''; ?>> Active</label></div>
    </div>
    <div class="mt-4"><button type="submit" class="bg-kmf-orange text-white px-4 py-2 rounded-lg">Save</button> <a href="<?php echo BASE_URL; ?>admin/publications.php" class="text-gray-600 ml-2">Cancel</a></div>
</form>
<?php else: ?>
<p class="mb-4"><a href="?edit=0" class="bg-kmf-orange text-white px-4 py-2 rounded-lg inline-block">Add Publication</a></p>
<?php endif; ?>

<table class="bg-white rounded-lg shadow overflow-hidden w-full max-w-3xl">
    <thead class="bg-gray-100"><tr><th class="text-left p-3">Title</th><th class="text-left p-3">Type</th><th class="p-3">Date</th><th class="p-3">Actions</th></tr></thead>
    <tbody>
        <?php foreach ($items as $p): ?>
        <tr class="border-t"><td class="p-3"><?php echo escape($p['title']); ?></td><td class="p-3"><?php echo escape($p['type']); ?></td><td class="p-3"><?php echo escape($p['published_at'] ?? '-'); ?></td><td class="p-3"><a href="?edit=<?php echo $p['id']; ?>" class="text-kmf-orange hover:underline">Edit</a></td></tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
