<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_news');
$adminTitle = 'Stories';

$pdo = getDb();
$items = $pdo->query("SELECT * FROM case_stories ORDER BY story_date DESC")->fetchAll();
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if ($id === 0) $edit = ['id'=>0,'title'=>'','slug'=>'','excerpt'=>'','content'=>'','image_before_url'=>'','image_after_url'=>'','link_url'=>'','link_text'=>'','story_date'=>date('Y-m-d'),'is_active'=>1];
    else { $stmt = $pdo->prepare("SELECT * FROM case_stories WHERE id = ?"); $stmt->execute([$id]); $edit = $stmt->fetch(); }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? '';
    
    // Handle Uploads using shared function
    $image_before_url = handleImageUpload('image_before_file', 'case_stories', $_POST['existing_image_before_url'] ?? '');
    $image_after_url = handleImageUpload('image_after_file', 'case_stories', $_POST['existing_image_after_url'] ?? '');
    $link_url = trim($_POST['link_url'] ?? '');
    $link_text = trim($_POST['link_text'] ?? '');

    $story_date = trim($_POST['story_date'] ?? '') ?: null;
    $active = isset($_POST['is_active']) ? 1 : 0;
    
    if ($id) {
        $stmt = $pdo->prepare("UPDATE case_stories SET title=?, slug=?, excerpt=?, content=?, image_before_url=?, image_after_url=?, link_url=?, link_text=?, story_date=?, is_active=? WHERE id=?");
        $stmt->execute([$title, $slug, $excerpt, $content, $image_before_url, $image_after_url, $link_url, $link_text, $story_date, $active, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO case_stories (title, slug, excerpt, content, image_before_url, image_after_url, link_url, link_text, story_date, is_active) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$title, $slug, $excerpt, $content, $image_before_url, $image_after_url, $link_url, $link_text, $story_date, $active]);
    }
    redirect(BASE_URL . 'admin/case-stories.php?updated=1');
}

if (isset($_GET['delete'])) {
    if (isset($_GET['csrf']) && hash_equals($_SESSION['csrf_token'] ?? '', $_GET['csrf'])) {
        $id = (int)$_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM case_stories WHERE id = ?");
        $stmt->execute([$id]);
        redirect(BASE_URL . 'admin/case-stories.php?deleted=1');
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<h1 class="text-2xl font-bold text-kmf-blue mb-6">Stories</h1>
<?php if (isset($_GET['updated'])): ?><p class="text-green-600 mb-4 font-bold">Story saved successfully.</p><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><p class="text-red-600 mb-4 font-bold">Story deleted.</p><?php endif; ?>

<?php if ($edit !== null): ?>
<form method="post" enctype="multipart/form-data" class="bg-white rounded-lg shadow p-6 mb-6 max-w-3xl">
    <?php echo csrfField(); ?>
    <input type="hidden" name="id" value="<?php echo (int)$edit['id']; ?>">
    <input type="hidden" name="existing_image_before_url" value="<?php echo escape($edit['image_before_url']); ?>">
    <input type="hidden" name="existing_image_after_url" value="<?php echo escape($edit['image_after_url']); ?>">
    <div class="space-y-4">
        <div><label class="block text-sm font-medium mb-1 text-gray-700">Patient/Story Name</label>
        <input type="text" name="title" value="<?php echo escape($edit['title']); ?>" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-kmf-blue" required></div>
        
        <div><label class="block text-sm font-medium mb-1 text-gray-700">Slug</label>
        <input type="text" name="slug" value="<?php echo escape($edit['slug']); ?>" placeholder="Leave blank to auto-generate" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg"></div>
        
        <div><label class="block text-sm font-medium mb-1 text-gray-700">Health Impact Excerpt</label>
        <textarea name="excerpt" rows="2" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg"><?php echo escape($edit['excerpt']); ?></textarea></div>
        
        <div><label class="block text-sm font-medium mb-1 text-gray-700">Full Story Content (HTML allowed)</label>
        <textarea name="content" rows="8" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg"><?php echo escape($edit['content']); ?></textarea></div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <label class="block text-sm font-bold mb-2 text-kmf-orange">Upload Before Photo</label>
                <?php if (!empty($edit['image_before_url'])): ?>
                    <p class="text-xs text-gray-500 mb-2 font-medium">Image has been uploaded. Upload a new one to replace it.</p>
                <?php endif; ?>
                <input type="file" name="image_before_file" accept=".jpg,.jpeg,.png,.webp,.gif" class="w-full text-sm">
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <label class="block text-sm font-bold mb-2 text-kmf-green">Upload After Photo</label>
                <?php if (!empty($edit['image_after_url'])): ?>
                    <p class="text-xs text-gray-500 mb-2 font-medium">Image has been uploaded. Upload a new one to replace it.</p>
                <?php endif; ?>
                <input type="file" name="image_after_file" accept=".jpg,.jpeg,.png,.webp,.gif" class="w-full text-sm">
            </div>
        </div>
        
        <div><label class="block text-sm font-medium mb-1 text-gray-700">Link URL</label>
        <input type="url" name="link_url" value="<?php echo escape($edit['link_url'] ?? ''); ?>" placeholder="https://example.com/more-info" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-kmf-blue"></div>
        
        <div><label class="block text-sm font-medium mb-1 text-gray-700">Link Text</label>
        <input type="text" name="link_text" value="<?php echo escape($edit['link_text'] ?? ''); ?>" placeholder="Read More / View Case Document" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-kmf-blue"></div>

        <div><label class="block text-sm font-medium mb-1 text-gray-700">Timeline Date (YYYY-MM-DD)</label>
        <input type="date" name="story_date" value="<?php echo escape($edit['story_date']); ?>" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg"></div>
        
        <div><label class="flex items-center gap-2 font-medium text-gray-700"><input type="checkbox" name="is_active" value="1" <?php echo ($edit['is_active']) ? 'checked' : ''; ?> class="rounded text-kmf-blue focus:ring-kmf-blue"> Active (Visible on site)</label></div>
    </div>
    <div class="mt-6 flex gap-4">
        <button type="submit" class="bg-kmf-orange hover:bg-kmf-orange-light text-white font-bold px-6 py-2 rounded-lg transition-colors shadow-lg">Save Story</button> 
        <a href="<?php echo BASE_URL; ?>admin/case-stories.php" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition-colors">Cancel</a>
    </div>
</form>
<?php else: ?>
<div class="mb-6 flex justify-between items-center max-w-5xl">
    <p class="text-gray-600">Manage impact stories from our health sector.</p>
    <a href="?edit=0" class="bg-kmf-green hover:bg-kmf-green-light text-white font-bold px-4 py-2 rounded-xl transition-colors shadow-md flex items-center gap-2">
        <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
        Add New Story
    </a>
</div>

<div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden w-full max-w-5xl">
    <table class="w-full text-left">
        <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-bold tracking-wider">
            <tr>
                <th class="p-4 md:p-6 text-sm">Story Name</th>
                <th class="p-4 md:p-6 text-sm">Date</th>
                <th class="p-4 md:p-6 text-sm">Status</th>
                <th class="p-4 md:p-6 text-right text-sm">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            <?php foreach ($items as $s): ?>
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="p-4 md:p-6 font-semibold text-kmf-blue text-sm md:text-base w-1/2">
                    <?php echo escape($s['title']); ?>
                </td>
                <td class="p-4 md:p-6 font-medium text-slate-500 text-sm whitespace-nowrap">
                    <?php echo escape($s['story_date'] ?? '-'); ?>
                </td>
                <td class="p-4 md:p-6">
                    <?php if ($s['is_active']): ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Active</span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">Hidden</span>
                    <?php endif; ?>
                </td>
                <td class="p-4 md:p-6 text-right font-medium text-sm whitespace-nowrap space-x-3">
                    <a href="?edit=<?php echo $s['id']; ?>" class="text-kmf-orange hover:text-kmf-orange-light hover:underline transition-colors shrink-0">Edit</a>
                    <a href="?delete=<?php echo $s['id']; ?>&csrf=<?php echo $_SESSION['csrf_token'] ?? ''; ?>" onclick="return confirm('Are you sure you want to delete this story?');" class="text-red-500 hover:text-red-600 hover:underline transition-colors shrink-0">Delete</a>
                </td>
            </tr>
            <?php endforeach; 
            if (empty($items)): ?>
            <tr><td colspan="4" class="p-6 text-center text-slate-500 italic">No stories found.</td></tr>
            <?php endif;?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
