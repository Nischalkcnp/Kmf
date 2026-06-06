<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('resources');
$pageTitle = $page ? $page['title'] : 'Resources';
$metaDescription = $page ? $page['meta_description'] : 'Publications, reports, and articles from Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$publications = $pdo->query("SELECT * FROM publications WHERE is_active = 1 ORDER BY published_at DESC, sort_order ASC")->fetchAll();
$articles = array_filter($publications, fn($p) => $p['type'] === 'article');
$reports = array_filter($publications, fn($p) => $p['type'] === 'report');
$pubs = array_filter($publications, fn($p) => $p['type'] === 'publication');

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">Knowledge Hub</p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-kmf-blue mb-6 leading-tight">Publications & Reports</h1>
            <p class="text-lg md:text-xl text-gray-600 font-medium">Browse our collection of publications, annual reports, and community impact articles.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10">
            <?php foreach ($publications as $pub): ?>
            <article id="pub-<?php echo (int)$pub['id']; ?>" class="group scroll-mt-24 bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 flex flex-col md:flex-row shadow-sm hover:shadow-xl transition-all duration-300">
                <?php if (!empty($pub['image_url'])): ?>
                    <div class="flex-shrink-0 w-full md:w-48 lg:w-56 overflow-hidden">
                        <img src="<?php echo BASE_URL . escape($pub['image_url']); ?>" alt="" class="w-full h-48 md:h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
                    </div>
                <?php else: ?>
                    <div class="flex-shrink-0 w-full md:w-48 lg:w-56 bg-kmf-green-light flex items-center justify-center text-kmf-blue p-8">
                        <svg class="w-16 h-16 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                <?php endif; ?>
                <div class="p-6 md:p-8 flex flex-col justify-between flex-1">
                    <div>
                        <span class="inline-block py-1 px-3 bg-kmf-orange/10 text-kmf-orange text-[10px] font-bold uppercase tracking-widest rounded-full mb-3"><?php echo escape($pub['type']); ?></span>
                        <h2 class="text-xl md:text-2xl font-bold text-kmf-blue mb-2 group-hover:text-kmf-orange transition-colors"><?php echo escape($pub['title']); ?></h2>
                        <?php if ($pub['published_at']): ?>
                            <p class="text-sm font-semibold text-gray-400 mb-4"><?php echo formatDate($pub['published_at']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($pub['excerpt'])): ?>
                            <p class="text-gray-600 text-sm leading-relaxed mb-6"><?php echo escape($pub['excerpt']); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($pub['file_url'])): ?>
                        <a href="<?php echo BASE_URL . escape($pub['file_url']); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-kmf-orange font-bold hover:underline transition-all">
                            <span class="w-8 h-8 rounded-full bg-kmf-orange text-white flex items-center justify-center group-hover:scale-110 transition-transform">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                            Download Resource
                        </a>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($publications)): ?>
            <div class="bg-gray-50 rounded-2xl p-12 text-center border border-dashed border-gray-300">
                <p class="text-gray-500 font-medium italic">Resources and documents will be uploaded shortly.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
