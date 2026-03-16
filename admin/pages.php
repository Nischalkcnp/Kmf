<?php
require_once dirname(__DIR__) . '/config/config.php';
requireLogin();
$adminTitle = 'Pages';

$pdo = getDb();
$pages = $pdo->query("SELECT * FROM pages ORDER BY slug")->fetchAll();

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $edit = $stmt->fetch();
    } else {
        $edit = ['id'=>0,'slug'=>'','title'=>'','content'=>'','meta_description'=>''];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $id = (int)($_POST['id'] ?? 0);
    $slug = trim($_POST['slug'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $meta = trim($_POST['meta_description'] ?? '');
    if ($slug && $title) {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE pages SET slug=?, title=?, content=?, meta_description=? WHERE id=?");
            $stmt->execute([$slug, $title, $content, $meta, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO pages (slug, title, content, meta_description) VALUES (?,?,?,?)");
            $stmt->execute([$slug, $title, $content, $meta]);
        }
        redirect(BASE_URL . 'admin/pages.php?updated=1');
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<h1 class="text-2xl font-bold text-kmf-blue mb-6">Pages</h1>
<?php if (isset($_GET['updated'])): ?><p class="text-green-600 mb-4">Saved.</p><?php endif; ?>

<?php if ($edit): ?>
<form method="post" class="bg-white rounded-lg shadow p-6 mb-6 max-w-3xl">
    <?php echo csrfField(); ?>
    <input type="hidden" name="id" value="<?php echo (int)($edit['id'] ?? 0); ?>">
    <div class="space-y-4">
        <div><label class="block text-sm font-medium mb-1">Slug</label><input type="text" name="slug" value="<?php echo escape($edit['slug']); ?>" class="w-full px-4 py-2 border rounded-lg" required></div>
        <div><label class="block text-sm font-medium mb-1">Title</label><input type="text" name="title" value="<?php echo escape($edit['title']); ?>" class="w-full px-4 py-2 border rounded-lg" required></div>
        <div><label class="block text-sm font-medium mb-1">Meta Description</label><input type="text" name="meta_description" value="<?php echo escape($edit['meta_description']); ?>" class="w-full px-4 py-2 border rounded-lg"></div>
        <div><label class="block text-sm font-medium mb-1">Content (HTML allowed)</label><textarea name="content" rows="12" class="w-full px-4 py-2 border rounded-lg font-mono text-sm"><?php echo escape($edit['content']); ?></textarea></div>
    </div>
    <div class="mt-4 flex gap-2">
        <button type="submit" class="bg-kmf-orange text-white px-4 py-2 rounded-lg">Save</button>
        <a href="<?php echo BASE_URL; ?>admin/pages.php" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg">Cancel</a>
    </div>
</form>
<?php endif; ?>

<p class="mb-4"><a href="?edit=0" class="bg-kmf-orange text-white px-4 py-2 rounded-lg inline-block">Add Page</a></p>
<table class="bg-white rounded-lg shadow overflow-hidden w-full max-w-3xl">
    <thead class="bg-gray-100"><tr><th class="text-left p-3">Slug</th><th class="text-left p-3">Title</th><th class="p-3">Actions</th></tr></thead>
    <tbody>
        <?php foreach ($pages as $p): ?>
        <tr class="border-t"><td class="p-3"><?php echo escape($p['slug']); ?></td><td class="p-3"><?php echo escape($p['title']); ?></td><td class="p-3"><a href="?edit=<?php echo $p['id']; ?>" class="text-kmf-orange hover:underline">Edit</a></td></tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
