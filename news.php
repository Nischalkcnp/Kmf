<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'News & Media';
$metaDescription = 'Latest news and updates from Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$news = $pdo->query("SELECT * FROM news WHERE is_active = 1 ORDER BY published_at DESC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">News & Media</p>
        <h1 class="text-3xl md:text-4xl font-bold text-kmf-blue mb-6">Latest News</h1>
        <p class="text-gray-600 max-w-3xl mb-12">Stay updated with our latest news and stories.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($news as $n): ?>
            <article id="news-<?php echo (int)$n['id']; ?>" class="scroll-mt-24 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 card-hover">
                <?php
                    $img = !empty($n['image_url']) ? $n['image_url'] : 'assets/images/news-placeholder.svg';
                ?>
                <img src="<?php echo BASE_URL . escape($img); ?>" alt="<?php echo escape($n['title']); ?>" class="w-full h-48 object-cover">
                <div class="p-5">
                    <p class="text-sm text-gray-500 mb-2"><?php echo formatDate($n['published_at'], 'd M, Y'); ?></p>
                    <h2 class="text-lg font-semibold text-kmf-blue mb-2"><?php echo escape($n['title']); ?></h2>
                    <p class="text-gray-600 text-sm"><?php echo escape($n['excerpt']); ?></p>
                    <?php if (!empty($n['content'])): ?>
                        <div class="prose-custom text-sm mt-3 text-gray-600"><?php echo $n['content']; ?></div>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($news)): ?>
            <p class="text-gray-600">No news items yet.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
