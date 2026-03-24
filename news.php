<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'News & Media';
$metaDescription = 'Latest news and updates from Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$news = $pdo->query("SELECT * FROM news WHERE is_active = 1 ORDER BY published_at DESC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-12 md:py-20 lg:py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">News & Media</p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-kmf-blue mb-6 leading-tight">Latest News</h1>
            <p class="text-lg md:text-xl text-gray-600 font-medium leading-relaxed">Stay informed about our latest activities, community milestones, and stories of change from across Nepal.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($news as $n): ?>
            <article id="news-<?php echo (int)$n['id']; ?>" class="group scroll-mt-24 bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full">
                <?php
                    $img = !empty($n['image_url']) ? $n['image_url'] : 'assets/images/news-placeholder.svg';
                ?>
                <div class="relative overflow-hidden h-56">
                    <img src="<?php echo BASE_URL . escape($img); ?>" alt="<?php echo escape($n['title']); ?>" class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
                    <div class="absolute top-4 left-4">
                        <span class="inline-block py-1.5 px-4 bg-white/90 backdrop-blur-sm text-kmf-blue text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow-sm">News</span>
                    </div>
                </div>
                <div class="p-6 lg:p-8 flex flex-col flex-1">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-sm font-bold text-gray-400"><?php echo formatDate($n['published_at'], 'd M, Y'); ?></p>
                    </div>
                    <h2 class="text-xl md:text-2xl font-extrabold text-kmf-blue mb-4 leading-tight group-hover:text-kmf-orange transition-colors"><?php echo escape($n['title']); ?></h2>
                    <p class="text-gray-600 text-sm font-medium leading-relaxed mb-6 flex-1"><?php echo escape($n['excerpt']); ?></p>
                    
                    <?php if (!empty($n['content'])): ?>
                        <div class="pt-6 border-t border-gray-50 mt-auto">
                            <button onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('span').textContent = this.nextElementSibling.classList.contains('hidden') ? 'Read More' : 'Read Less'" class="flex items-center gap-2 text-kmf-orange font-bold text-sm group/btn">
                                <span>Read More</span>
                                <svg class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </button>
                            <div class="prose-custom text-sm mt-6 text-gray-600 hidden transition-all duration-300"><?php echo $n['content']; ?></div>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($news)): ?>
            <div class="bg-gray-50 rounded-2xl p-12 text-center border border-dashed border-gray-300">
                <p class="text-gray-500 font-medium italic">We'll be sharing news and updates here soon.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
