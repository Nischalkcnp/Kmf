<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_careers');
$adminTitle = 'Careers';

$pdo = getDb();
$items = $pdo->query("SELECT * FROM careers ORDER BY deadline ASC, created_at DESC")->fetchAll();
$edit = null;

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    if ($id === 0) {
        $edit = [
            'id' => 0,
            'title' => '',
            'slug' => '',
            'excerpt' => '',
            'description' => '',
            'deadline' => date('Y-m-d', strtotime('+30 days')),
            'is_active' => 1
        ];
    } else {
        $stmt = $pdo->prepare("SELECT * FROM careers WHERE id = ?");
        $stmt->execute([$id]);
        $edit = $stmt->fetch();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '') ?: slugify($title);
    $excerpt = trim($_POST['excerpt'] ?? '');
    $description = $_POST['description'] ?? '';
    $deadline = trim($_POST['deadline'] ?? '') ?: null;
    $active = isset($_POST['is_active']) ? 1 : 0;
    
    if ($id) {
        $stmt = $pdo->prepare("UPDATE careers SET title=?, slug=?, excerpt=?, description=?, deadline=?, is_active=? WHERE id=?");
        $stmt->execute([$title, $slug, $excerpt, $description, $deadline, $active, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO careers (title, slug, excerpt, description, deadline, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $slug, $excerpt, $description, $deadline, $active]);
    }
    redirect(BASE_URL . 'admin/careers.php?updated=1');
}

if (isset($_GET['delete'])) {
    if (isset($_GET['csrf']) && hash_equals($_SESSION['csrf_token'] ?? '', $_GET['csrf'])) {
        $id = (int)$_GET['delete'];
        $stmt = $pdo->prepare("DELETE FROM careers WHERE id = ?");
        $stmt->execute([$id]);
        redirect(BASE_URL . 'admin/careers.php?deleted=1');
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight">Careers Manager</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Add, edit, or remove job opportunities & vacancies</p>
    </div>
    <?php if (!$edit): ?>
    <a href="?edit=0" class="inline-flex items-center gap-2 bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold px-6 py-3 rounded-2xl shadow-lg shadow-kmf-orange/20 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Job Vacancy
    </a>
    <?php endif; ?>
</div>

<?php if (isset($_GET['updated'])): ?>
<div class="mb-8 flex items-center gap-3 bg-green-50 px-6 py-4 rounded-2xl border border-green-100">
    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <p class="text-sm font-bold text-green-600">Vacancy saved successfully</p>
</div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
<div class="mb-8 flex items-center gap-3 bg-red-50 px-6 py-4 rounded-2xl border border-red-100">
    <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </div>
    <p class="text-sm font-bold text-red-600">Vacancy deleted successfully</p>
</div>
<?php endif; ?>

<?php if ($edit !== null): ?>
<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm p-8 mb-10">
    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-slate-50">
        <div class="w-10 h-10 rounded-xl bg-kmf-orange/10 flex items-center justify-center text-kmf-orange">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="text-xl font-bold text-kmf-blue"><?php echo $edit['id'] ? 'Edit Job Vacancy' : 'Create Job Vacancy'; ?></h3>
    </div>

    <form method="post" class="space-y-6">
        <?php echo csrfField(); ?>
        <input type="hidden" name="id" value="<?php echo (int)$edit['id']; ?>">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Job Title</label>
                <input type="text" name="title" value="<?php echo escape($edit['title']); ?>" required
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                    placeholder="e.g., Community Health Coordinator">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">URL Slug</label>
                <input type="text" name="slug" value="<?php echo escape($edit['slug']); ?>"
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                    placeholder="community-health-coordinator (auto-generates if blank)">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Application Deadline</label>
                <input type="date" name="deadline" value="<?php echo escape($edit['deadline']); ?>" required
                    class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2 flex items-center pt-6 pl-2">
                <label class="flex items-center gap-3 font-bold text-sm text-kmf-blue cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1" <?php echo ($edit['is_active']) ? 'checked' : ''; ?>
                        class="w-5 h-5 rounded text-kmf-orange focus:ring-kmf-orange border-slate-300">
                    Active & Visible on Website
                </label>
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Job Excerpt (Short Description)</label>
            <textarea name="excerpt" rows="2"
                class="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue"
                placeholder="A brief 1-2 sentence overview of the role..."><?php echo escape($edit['excerpt']); ?></textarea>
        </div>

        <div class="space-y-2">
            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Full Job Description (HTML allowed)</label>
            <textarea name="description" rows="12" 
                class="w-full px-6 py-4 bg-slate-800 border border-slate-900 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-mono text-sm text-green-400 leading-relaxed"><?php echo escape($edit['description']); ?></textarea>
            <p class="text-[10px] text-slate-400 font-medium px-1">Tip: You can use HTML tags like &lt;p&gt;, &lt;ul&gt;, &lt;li&gt;, &lt;strong&gt;, etc. for structured listing.</p>
        </div>

        <div class="pt-6 flex flex-col md:flex-row gap-4">
            <button type="submit" class="flex-1 bg-kmf-blue hover:bg-kmf-blue-dark text-white font-extrabold py-5 rounded-2xl shadow-xl shadow-kmf-blue/20 transition-all duration-300 transform hover:-translate-y-1">
                Save Vacancy
            </button>
            <a href="careers.php" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold py-5 rounded-2xl text-center transition-all">
                Cancel
            </a>
        </div>
    </form>
</div>
<?php else: ?>

<div class="overflow-x-auto">
    <table class="w-full border-separate border-spacing-y-3">
        <thead>
            <tr class="text-left text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4">Job Title</th>
                <th class="px-6 py-4">Deadline</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $s): 
                $isExpired = strtotime($s['deadline']) < time();
            ?>
            <tr class="group hover:bg-slate-50/50 transition-colors">
                <td class="px-6 py-5 bg-slate-50/30 first:rounded-l-[2rem] border-y border-l border-slate-50">
                    <?php if ($s['is_active'] && !$isExpired): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-800 uppercase tracking-wider">
                            Active
                        </span>
                    <?php elseif ($isExpired): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 uppercase tracking-wider">
                            Expired
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 uppercase tracking-wider">
                            Hidden
                        </span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-5 bg-slate-50/30 border-y border-slate-50">
                    <div>
                        <p class="text-sm font-bold text-kmf-blue leading-none"><?php echo escape($s['title']); ?></p>
                        <p class="text-[10px] font-medium text-slate-400 mt-1.5 flex items-center gap-1">
                            /career.php#<?php echo escape($s['slug']); ?>
                        </p>
                    </div>
                </td>
                <td class="px-6 py-5 bg-slate-50/30 border-y border-slate-50 font-bold text-sm text-slate-500">
                    <?php echo escape($s['deadline']); ?>
                </td>
                <td class="px-6 py-5 bg-slate-50/30 last:rounded-r-[2rem] border-y border-r border-slate-50 text-right">
                    <div class="flex items-center justify-end gap-3 font-semibold text-sm">
                        <a href="?edit=<?php echo $s['id']; ?>" class="text-kmf-orange hover:text-kmf-orange-light hover:underline transition-colors shrink-0">Edit</a>
                        <a href="?delete=<?php echo $s['id']; ?>&csrf=<?php echo $_SESSION['csrf_token'] ?? ''; ?>" 
                           onclick="return confirm('Are you sure you want to delete this vacancy?');" 
                           class="text-red-500 hover:text-red-600 hover:underline transition-colors shrink-0">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; 
            if (empty($items)): ?>
            <tr><td colspan="4" class="p-8 text-center text-slate-400 italic">No job vacancies listed yet. Click "Add Job Vacancy" to get started.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
