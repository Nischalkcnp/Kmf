<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('access_admin');

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

// Display and clear flash error/success if present
$flash_error = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_error']);
$flash_success = $_SESSION['flash_success'] ?? '';
unset($_SESSION['flash_success']);
?>
<?php if ($flash_error): ?>
<div class="mb-8 flex items-center gap-3 bg-red-50 px-6 py-4 rounded-2xl border border-red-100 animate-headShake">
    <div class="w-8 h-8 rounded-full bg-red-500 flex items-center justify-center text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
    </div>
    <p class="text-sm font-bold text-red-600"><?php echo escape($flash_error); ?></p>
</div>
<?php endif; ?>
<?php if ($flash_success): ?>
<div class="mb-8 flex items-center gap-3 bg-green-50 px-6 py-4 rounded-2xl border border-green-100">
    <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    </div>
    <p class="text-sm font-bold text-green-600"><?php echo escape($flash_success); ?></p>
</div>
<?php endif; ?>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight">System Overview</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Manage and monitor your website content</p>
    </div>
    <div class="flex items-center gap-3 bg-slate-50 p-2 rounded-2xl border border-slate-100">
        <div class="px-4 py-2 bg-white rounded-xl shadow-sm border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Status</p>
            <p class="text-xs font-bold text-green-500 mt-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                System Live
            </p>
        </div>
        <div class="px-4 py-2 bg-white rounded-xl shadow-sm border border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Last Update</p>
            <p class="text-xs font-bold text-kmf-blue mt-1"><?php echo date('d M, Y'); ?></p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
    <?php
    $statsCards = [
        ['label' => 'Total Pages', 'count' => $counts['pages'], 'url' => 'admin/pages.php', 'color' => 'blue', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
        ['label' => 'Strategic Areas', 'count' => $counts['areas'], 'url' => 'admin/areas.php', 'color' => 'orange', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ['label' => 'Active Programs', 'count' => $counts['programs'], 'url' => 'admin/programs.php', 'color' => 'green', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        ['label' => 'Latest Stories', 'count' => $counts['news'], 'url' => 'admin/news.php', 'color' => 'blue', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z'],
        ['label' => 'Contact Messages', 'count' => $counts['contacts'], 'url' => 'admin/messages.php', 'color' => 'orange', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
    ];

    foreach ($statsCards as $card):
    ?>
    <a href="<?php echo BASE_URL . $card['url']; ?>" class="group p-6 bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-2xl bg-kmf-<?php echo $card['color']; ?>/10 flex items-center justify-center text-kmf-<?php echo $card['color']; ?>">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo $card['icon']; ?>"></path></svg>
            </div>
            <span class="text-xs font-bold text-slate-300 group-hover:text-kmf-orange transition-colors">Manage &rarr;</span>
        </div>
        <p class="text-3xl font-black text-kmf-blue tabular-nums"><?php echo (int)$card['count']; ?></p>
        <p class="text-sm font-bold text-slate-400 mt-1"><?php echo $card['label']; ?></p>
    </a>
    <?php endforeach; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Quick Actions -->
    <div class="p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
        <h3 class="text-xl font-extrabold text-kmf-blue mb-6">Quick Actions</h3>
        <div class="grid grid-cols-2 gap-4">
            <a href="<?php echo BASE_URL; ?>admin/news.php?edit=0" class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl border border-slate-200 hover:border-kmf-orange hover:shadow-lg transition-all group">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 mb-3 group-hover:bg-kmf-orange group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-600">Post New Story</span>
            </a>
            <a href="<?php echo BASE_URL; ?>admin/publications.php?edit=0" class="flex flex-col items-center justify-center p-6 bg-white rounded-2xl border border-slate-200 hover:border-kmf-orange hover:shadow-lg transition-all group">
                <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 mb-3 group-hover:bg-kmf-orange group-hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-600">Upload Publication</span>
            </a>
        </div>
    </div>

    <!-- System Info -->
    <div class="p-8 bg-kmf-blue rounded-[2rem] shadow-xl shadow-kmf-blue/20 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 p-8 opacity-10">
            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
        </div>
        <h3 class="text-xl font-bold mb-6">Need Support?</h3>
        <p class="text-blue-100/70 text-sm leading-relaxed mb-8">If you experience any issues with the CMS or need help updating specific sections, please contact the development team.</p>
        <div class="space-y-4">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-blue-300 uppercase tracking-widest leading-none">Email Support</p>
                    <p class="text-sm font-bold mt-1">support@kmf.org.np</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
