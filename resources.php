<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Resources';
$metaDescription = 'Publications, reports, and articles from Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$publications = $pdo->query("SELECT * FROM publications WHERE is_active = 1 ORDER BY published_at DESC, sort_order ASC")->fetchAll();
$articles = array_filter($publications, fn($p) => $p['type'] === 'article');
$reports = array_filter($publications, fn($p) => $p['type'] === 'report');
$pubs = array_filter($publications, fn($p) => $p['type'] === 'publication');

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">Resources</p>
        <h1 class="text-3xl md:text-4xl font-bold text-kmf-blue mb-6">Publications & Reports</h1>
        <p class="text-gray-600 max-w-3xl mb-12">Browse our publications, reports, and articles.</p>

        <div class="space-y-8">
            <?php foreach ($publications as $pub): ?>
            <article id="pub-<?php echo (int)$pub['id']; ?>" class="scroll-mt-24 bg-gray-50 rounded-xl p-6 md:p-8 border border-gray-100 flex flex-col md:flex-row gap-6">
                <?php if (!empty($pub['image_url'])): ?>
                    <div class="flex-shrink-0 w-full md:w-48">
                        <img src="<?php echo BASE_URL . escape($pub['image_url']); ?>" alt="" class="w-full h-40 md:h-36 object-cover rounded-lg">
                    </div>
                <?php endif; ?>
                <div class="flex-1">
                    <span class="text-xs font-medium text-kmf-orange uppercase"><?php echo escape($pub['type']); ?></span>
                    <h2 class="text-xl font-bold text-kmf-blue mt-1 mb-2"><?php echo escape($pub['title']); ?></h2>
                    <?php if ($pub['published_at']): ?>
                        <p class="text-sm text-gray-500 mb-2"><?php echo formatDate($pub['published_at']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($pub['excerpt'])): ?>
                        <p class="text-gray-600 mb-3"><?php echo escape($pub['excerpt']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($pub['file_url'])): ?>
                        <a href="<?php echo BASE_URL . escape($pub['file_url']); ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-kmf-orange font-semibold hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download
                        </a>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($publications)): ?>
            <p class="text-gray-600">Publications will be added here.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
