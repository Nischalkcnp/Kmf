<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_settings');
$adminTitle = 'Homepage Sections';

$pdo = getDb();

// Handle Post Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'save_order') {
        foreach ($_POST['order'] as $id => $order) {
            $stmt = $pdo->prepare("UPDATE site_sections SET sort_order = ? WHERE id = ?");
            $stmt->execute([(int)$order, (int)$id]);
        }
        redirect(BASE_URL . 'admin/sections.php?updated=1');
    }
    
    if ($action === 'toggle_active') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("UPDATE site_sections SET is_active = NOT is_active WHERE id = ?");
        $stmt->execute([$id]);
        redirect(BASE_URL . 'admin/sections.php?updated=1');
    }

    if ($action === 'save_custom') {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $content = $_POST['content'] ?? '';
        $section_key = $id ? $_POST['section_key'] : 'custom_' . time();
        $image_url = handleImageUpload('section_image', 'sections', $_POST['current_image_url'] ?? '');
        $active = isset($_POST['is_active']) ? 1 : 0;
        
        if ($id) {
            $stmt = $pdo->prepare("UPDATE site_sections SET title=?, subtitle=?, content=?, image_url=?, is_active=? WHERE id=?");
            $stmt->execute([$title, $subtitle, $content, $image_url, $active, $id]);
        } else {
            $lastOrder = $pdo->query("SELECT MAX(sort_order) FROM site_sections")->fetchColumn() ?: 0;
            $stmt = $pdo->prepare("INSERT INTO site_sections (section_key, display_name, section_type, title, subtitle, content, image_url, sort_order, is_active) VALUES (?, ?, 'custom', ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$section_key, $title, $title, $subtitle, $content, $image_url, $lastOrder + 10, $active]);
        }
        redirect(BASE_URL . 'admin/sections.php?updated=1');
    }

    if ($action === 'delete_custom') {
        $id = (int)$_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM site_sections WHERE id = ? AND section_type = 'custom'");
        $stmt->execute([$id]);
        redirect(BASE_URL . 'admin/sections.php?deleted=1');
    }
}

$sections = $pdo->query("SELECT * FROM site_sections ORDER BY sort_order ASC, id ASC")->fetchAll();
$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM site_sections WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight">Homepage Layout</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Reorder, toggle visibility, and add custom segments</p>
    </div>
    <div class="flex gap-3">
        <a href="?edit=0" class="bg-kmf-orange hover:bg-kmf-orange-light text-white font-bold px-6 py-3 rounded-2xl shadow-lg transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Custom Section
        </a>
    </div>
</div>

<?php if (isset($_GET['updated'])): ?><p class="text-green-600 mb-6 font-bold shadow-sm inline-block px-4 py-2 bg-green-50 rounded-lg">Layout updated successfully.</p><?php endif; ?>
<?php if (isset($_GET['deleted'])): ?><p class="text-red-600 mb-6 font-bold shadow-sm inline-block px-4 py-2 bg-red-50 rounded-lg">Section removed.</p><?php endif; ?>

<?php if ($edit !== null): ?>
    <!-- Edit Form for Custom Section -->
    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm p-8 mb-10">
        <h3 class="text-xl font-bold text-kmf-blue mb-6"><?php echo $edit['id'] ? 'Edit Section' : 'Create Custom Section'; ?></h3>
        <form method="post" enctype="multipart/form-data" class="space-y-6 max-w-3xl">
            <?php echo csrfField(); ?>
            <input type="hidden" name="action" value="save_custom">
            <input type="hidden" name="id" value="<?php echo (int)$edit['id']; ?>">
            <input type="hidden" name="section_key" value="<?php echo escape($edit['section_key'] ?? ''); ?>">
            <input type="hidden" name="current_image_url" value="<?php echo escape($edit['image_url'] ?? ''); ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Section Title</label>
                    <input type="text" name="title" value="<?php echo escape($edit['title'] ?? ''); ?>" required
                        class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Subsection / Tagline</label>
                    <input type="text" name="subtitle" value="<?php echo escape($edit['subtitle'] ?? ''); ?>"
                        class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
                </div>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Content (HTML allowed)</label>
                <textarea name="content" rows="6"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue resize-none"><?php echo escape($edit['content'] ?? ''); ?></textarea>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Feature Image (Optional)</label>
                <?php if (!empty($edit['image_url'])): ?>
                    <img src="<?php echo BASE_URL . escape($edit['image_url']); ?>" class="h-24 w-auto mb-3 rounded-xl border">
                <?php endif; ?>
                <input type="file" name="section_image" class="w-full text-sm">
            </div>

            <div class="flex items-center gap-2 mt-4">
                <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo ($edit['is_active'] ?? 1) ? 'checked' : ''; ?> class="w-5 h-5 rounded text-kmf-orange focus:ring-kmf-orange">
                <label for="is_active" class="text-sm font-bold text-slate-600">Active (Visible on site)</label>
            </div>

            <div class="pt-6 flex gap-4">
                <button type="submit" class="bg-kmf-blue text-white px-8 py-3 rounded-xl font-bold hover:bg-kmf-blue-dark transition-all">Save Section</button>
                <a href="sections.php" class="bg-slate-100 text-slate-600 px-8 py-3 rounded-xl font-bold hover:bg-slate-200 transition-all">Cancel</a>
            </div>
        </form>
    </div>
<?php endif; ?>

<!-- Section List -->
<div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
    <form method="post">
        <?php echo csrfField(); ?>
        <input type="hidden" name="action" value="save_order">
        <table class="w-full border-collapse">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="p-6 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Order</th>
                    <th class="p-6 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Section Type</th>
                    <th class="p-6 text-left text-xs font-black text-slate-400 uppercase tracking-widest">Internal Name</th>
                    <th class="p-6 text-center text-xs font-black text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="p-6 text-right text-xs font-black text-slate-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($sections as $s): ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="p-6 w-24">
                        <input type="number" name="order[<?php echo $s['id']; ?>]" value="<?php echo (int)$s['sort_order']; ?>" 
                            class="w-16 px-3 py-2 bg-white border border-slate-200 rounded-lg text-center font-bold text-kmf-blue focus:border-kmf-orange focus:ring-0">
                    </td>
                    <td class="p-6">
                        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?php echo $s['section_type'] === 'system' ? 'bg-blue-100 text-blue-600' : 'bg-kmf-orange/10 text-kmf-orange'; ?>">
                            <?php echo $s['section_type']; ?>
                        </span>
                    </td>
                    <td class="p-6">
                        <div class="font-bold text-kmf-blue"><?php echo escape($s['display_name']); ?></div>
                        <div class="text-[10px] text-slate-400 font-medium">ID: <?php echo escape($s['section_key']); ?></div>
                    </td>
                    <td class="p-6 text-center">
                        <form method="post" class="inline">
                            <?php echo csrfField(); ?>
                            <input type="hidden" name="action" value="toggle_active">
                            <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                            <button type="submit" class="px-4 py-1.5 rounded-full text-xs font-bold transition-all <?php echo $s['is_active'] ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'; ?>">
                                <?php echo $s['is_active'] ? 'Active' : 'Hidden'; ?>
                            </button>
                        </form>
                    </td>
                    <td class="p-6 text-right space-x-2">
                        <?php if ($s['section_type'] === 'custom'): ?>
                            <a href="?edit=<?php echo $s['id']; ?>" class="text-sm font-bold text-kmf-blue hover:text-kmf-orange transition-colors">Edit</a>
                            <form method="post" class="inline" onsubmit="return confirm('Delete this custom section?');">
                                <?php echo csrfField(); ?>
                                <input type="hidden" name="action" value="delete_custom">
                                <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                <button type="submit" class="text-sm font-bold text-red-400 hover:text-red-600 transition-colors">Delete</button>
                            </form>
                        <?php else: ?>
                            <span class="text-xs font-medium text-slate-300 italic">Static System Logic</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
            <p class="text-sm text-slate-400 font-medium italic">Tip: Lower numbers appear first on the page.</p>
            <button type="submit" class="bg-kmf-blue text-white px-10 py-4 rounded-2xl font-black shadow-xl shadow-kmf-blue/20 hover:scale-[1.02] active:scale-95 transition-all">Update Page Order</button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
