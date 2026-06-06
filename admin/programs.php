<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_programs');
$adminTitle = 'Programs';

$pdo = getDb();
$programs = $pdo->query("SELECT * FROM programs ORDER BY type, sort_order, id")->fetchAll();
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if ($id === 0) {
        $edit = ['id' => 0, 'title' => '', 'slug' => '', 'excerpt' => '', 'content' => '', 'type' => 'current', 'conclude_date' => null, 'image_url' => '', 'sort_order' => 0, 'is_active' => 1];
    } else {
        $stmt = $pdo->prepare("SELECT * FROM programs WHERE id = ?");
        $stmt->execute([$id]);
        $edit = $stmt->fetch();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? '';
    $type = in_array($_POST['type'] ?? '', ['current','completed']) ? $_POST['type'] : 'current';
    $conclude_date = !empty($_POST['conclude_date']) ? $_POST['conclude_date'] : null;
    $image_url = handleImageUpload('image_file', 'programs', $_POST['current_image_url'] ?? '');
    $sort = (int)($_POST['sort_order'] ?? 0);
    $active = isset($_POST['is_active']) ? 1 : 0;
    if ($id) {
        $stmt = $pdo->prepare("UPDATE programs SET title=?, slug=?, excerpt=?, content=?, type=?, conclude_date=?, image_url=?, sort_order=?, is_active=? WHERE id=?");
        $stmt->execute([$title, $slug, $excerpt, $content, $type, $conclude_date, $image_url, $sort, $active, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO programs (title, slug, excerpt, content, type, conclude_date, image_url, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$title, $slug, $excerpt, $content, $type, $conclude_date, $image_url, $sort, $active]);
    }
    redirect(BASE_URL . 'admin/programs.php?updated=1');
}

require_once __DIR__ . '/includes/header.php';
?>
<h1 class="text-2xl font-bold text-kmf-blue mb-6">Programs</h1>
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
        <div><label class="block text-sm font-medium mb-1">Type</label><select name="type" onchange="toggleConcludeDate(this.value)" class="w-full px-4 py-2 border rounded-lg"><option value="current" <?php echo $edit['type']==='current'?'selected':''; ?>>Current</option><option value="completed" <?php echo $edit['type']==='completed'?'selected':''; ?>>Completed</option></select></div>
        <div id="conclude_date_container" class="<?php echo $edit['type']==='completed'?'':'hidden'; ?>"><label class="block text-sm font-medium mb-1">Conclusion Date</label><input type="date" name="conclude_date" value="<?php echo escape($edit['conclude_date'] ?? ''); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium mb-1">Image (Upload file)</label>
        <?php if (!empty($edit['image_url'])): ?>
            <img src="<?php echo BASE_URL . escape($edit['image_url']); ?>" class="h-20 w-auto mb-2 rounded border">
        <?php endif; ?>
        <input type="file" name="image_file" class="w-full text-sm"></div>
        <div><label class="block text-sm font-medium mb-1">Sort</label><input type="number" name="sort_order" value="<?php echo (int)$edit['sort_order']; ?>" class="w-24 px-4 py-2 border rounded-lg"></div>
        <script>
        function toggleConcludeDate(val) {
            const container = document.getElementById('conclude_date_container');
            if (val === 'completed') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
                container.querySelector('input').value = '';
            }
        }
        </script>
        <div><label><input type="checkbox" name="is_active" value="1" <?php echo $edit['is_active'] ? 'checked' : ''; ?>> Active</label></div>
    </div>
    <div class="mt-4"><button type="submit" class="bg-kmf-orange text-white px-4 py-2 rounded-lg">Save</button> <a href="<?php echo BASE_URL; ?>admin/programs.php" class="text-gray-600 ml-2">Cancel</a></div>
</form>
<?php else: ?>
<p class="mb-4"><a href="?edit=0" class="bg-kmf-orange text-white px-4 py-2 rounded-lg inline-block">Add Program</a></p>
<?php endif; ?>

<table class="bg-white rounded-lg shadow overflow-hidden w-full max-w-3xl">
    <thead class="bg-gray-100"><tr><th class="text-left p-3">Title</th><th class="text-left p-3">Type</th><th class="p-3">Actions</th></tr></thead>
    <tbody>
        <?php foreach ($programs as $p): ?>
        <tr class="border-t"><td class="p-3"><?php echo escape($p['title']); ?></td><td class="p-3"><?php echo escape($p['type']); ?></td><td class="p-3"><a href="?edit=<?php echo $p['id']; ?>" class="text-kmf-orange hover:underline">Edit</a></td></tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
