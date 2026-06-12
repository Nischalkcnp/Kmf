<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_pages');
$adminTitle = 'Pages';

$pdo = getDb();
$pages = $pdo->query("SELECT * FROM pages ORDER BY parent_id ASC, sort_order ASC, title ASC")->fetchAll();

$edit = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if ($id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM pages WHERE id = ?");
        $stmt->execute([$id]);
        $edit = $stmt->fetch();
    } else {
        $edit = ['id'=>0,'slug'=>'','title'=>'','content'=>'','meta_description'=>'','image_url'=>'','parent_id'=>null,'sort_order'=>0];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $id = (int)($_POST['id'] ?? 0);
    $slug = trim($_POST['slug'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $content = $_POST['content'] ?? '';
    $meta = trim($_POST['meta_description'] ?? '');
    $parent_id = !empty($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
    $sort_order = (int)($_POST['sort_order'] ?? 0);
    $image_url = handleImageUpload('image_file', 'pages', $_POST['current_image_url'] ?? '');

    if ($slug && $title) {
        if ($id) {
            $stmt = $pdo->prepare("UPDATE pages SET slug=?, title=?, content=?, meta_description=?, image_url=?, parent_id=?, sort_order=? WHERE id=?");
            $stmt->execute([$slug, $title, $content, $meta, $image_url, $parent_id, $sort_order, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO pages (slug, title, content, meta_description, image_url, parent_id, sort_order) VALUES (?,?,?,?,?,?,?)");
            $stmt->execute([$slug, $title, $content, $meta, $image_url, $parent_id, $sort_order]);
        }
        redirect(BASE_URL . 'admin/pages.php?updated=1');
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight">Page Manager</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Create and manage static website pages</p>
    </div>
    <?php if (!$edit): ?>
    <a href="?edit=0" class="inline-flex items-center gap-2 bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold px-6 py-3 rounded-2xl shadow-lg shadow-kmf-orange/20 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Create New Page
    </a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['updated'])): ?>
<div class="mb-8 flex items-center gap-3 bg-green-50 px-6 py-4 rounded-2xl border border-green-100">
    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <p class="text-sm font-bold text-green-600">Page updated successfully</p>
</div>
<?php endif; ?>

<?php if ($edit): ?>
<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8 mb-10">
    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-slate-50">
        <div class="w-10 h-10 rounded-xl bg-kmf-orange/10 flex items-center justify-center text-kmf-orange">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-kmf-blue"><?php echo $edit['id'] ? 'Edit Page' : 'Create New Page'; ?></h3>
    </div>

    <form method="post" enctype="multipart/form-data" class="space-y-6">
        <?php echo csrfField(); ?>
        <input type="hidden" name="id" value="<?php echo (int)($edit['id'] ?? 0); ?>">
        <input type="hidden" name="current_image_url" value="<?php echo escape($edit['image_url'] ?? ''); ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Page Title</label>
                <input type="text" name="title" value="<?php echo escape($edit['title']); ?>" required
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                    placeholder="e.g., About Us">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">URL Slug</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-xs font-medium">/</span>
                    <input type="text" name="slug" value="<?php echo escape($edit['slug']); ?>" required
                        class="w-full pl-8 pr-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                        placeholder="about-us">
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Parent Page</label>
                <select name="parent_id" class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
                    <option value="">None (Top Level)</option>
                    <?php 
                    foreach ($pages as $p): 
                        if ($p['id'] == $edit['id']) continue;
                        if (!$p['parent_id']): 
                    ?>
                    <option value="<?php echo $p['id']; ?>" <?php echo ($edit['parent_id'] == $p['id']) ? 'selected' : ''; ?>>
                        <?php echo escape($p['title']); ?>
                    </option>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </select>
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Sort Order</label>
                <input type="number" name="sort_order" value="<?php echo (int)$edit['sort_order']; ?>"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Meta Description (SEO)</label>
            <input type="text" name="meta_description" value="<?php echo escape($edit['meta_description']); ?>"
                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                placeholder="Brief summary for search results...">
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Featured / Header Image</label>
            <?php if (!empty($edit['image_url'])): ?>
                <div class="mb-3 relative group w-48 rounded-2xl overflow-hidden border border-slate-100 shadow-sm">
                    <img src="<?php echo BASE_URL . escape($edit['image_url']); ?>" alt="Page Image" class="w-full h-32 object-cover">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <span class="text-white text-[10px] font-bold uppercase tracking-wider">Current Image</span>
                    </div>
                </div>
            <?php endif; ?>
            <div class="relative flex items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl p-6 hover:border-kmf-orange transition-colors bg-slate-50/50">
                <input type="file" name="image_file" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                <div class="text-center pointer-events-none">
                    <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-xs font-bold text-slate-400 block">Drag & drop or Click to Upload</span>
                    <span class="text-[10px] text-slate-300 font-medium mt-1 block">Supports JPG, PNG, WEBP (Max 2MB)</span>
                </div>
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Page Content (HTML)</label>
            <textarea name="content" rows="15" 
                class="w-full px-6 py-4 bg-slate-800 border border-slate-900 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-mono text-sm text-green-400 leading-relaxed"><?php echo escape($edit['content']); ?></textarea>
            <p class="text-[10px] text-slate-400 font-medium px-1">You can use standard HTML tags for formatting.</p>
        </div>

        <div class="pt-6 flex flex-col md:flex-row gap-4">
            <button type="submit" class="flex-1 bg-kmf-blue hover:bg-kmf-blue-dark text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-kmf-blue/20 transition-all duration-300 transform hover:-translate-y-1">
                Save Changes
            </button>
            <a href="pages.php" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold py-5 rounded-2xl text-center transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>
<?php endif; ?>

<div class="overflow-x-auto">
    <table class="w-full border-separate border-spacing-y-3">
        <thead>
            <tr class="text-left text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Title & Slug</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pages as $p): ?>
            <tr class="group hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-5 bg-slate-50/30 first:rounded-l-[2rem] border-y border-l border-slate-50">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 uppercase tracking-wider">
                        Published
                    </span>
                </td>
                <td class="px-6 py-5 bg-slate-50/30 border-y border-slate-50">
                    <div class="flex items-center gap-2">
                        <?php if ($p['parent_id']): ?>
                            <span class="text-slate-300">↳</span>
                        <?php endif; ?>
                        <div>
                            <p class="text-sm font-bold text-kmf-blue leading-none"><?php echo escape($p['title']); ?></p>
                            <p class="text-[10px] font-medium text-slate-400 mt-1.5 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.103 1.103"/></svg>
                                /<?php echo escape($p['slug']); ?>
                            </p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5 bg-slate-50/30 last:rounded-r-[2rem] border-y border-r border-slate-50 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <?php 
                        $viewUrl = getPageUrl($p['slug']);
                        ?>
                        <a href="<?php echo $viewUrl; ?>" target="_blank" class="p-2.5 rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-kmf-blue hover:shadow-sm transition-all" title="View Page">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="?edit=<?php echo $p['id']; ?>" class="p-2.5 rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-kmf-orange hover:shadow-sm transition-all" title="Edit Content">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
