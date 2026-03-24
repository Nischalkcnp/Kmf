<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Our Programs';
$metaDescription = 'Current and completed projects of Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$current = $pdo->query("SELECT * FROM programs WHERE is_active = 1 AND type = 'current' ORDER BY sort_order ASC")->fetchAll();
$completed = $pdo->query("SELECT * FROM programs WHERE is_active = 1 AND type = 'completed' ORDER BY sort_order ASC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-12 md:py-20 lg:py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">Our Programs</p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-kmf-blue mb-6 leading-tight">Programs & Projects</h1>
            <p class="text-lg md:text-xl text-gray-600 font-medium leading-relaxed">We design and implement community-led programs focusing on education infrastructure, sustainable livelihoods, and accessible healthcare.</p>
        </div>

        <div class="mb-20">
            <div class="flex items-center justify-between mb-10 border-b border-gray-100 pb-4">
                <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue">Current Projects</h2>
                <div class="hidden sm:block h-1 flex-1 bg-gray-50 mx-6 rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <?php foreach ($current as $p): ?>
                <article class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full">
                    <?php
                        $img = !empty($p['image_url']) ? $p['image_url'] : 'assets/images/program-placeholder.svg';
                    ?>
                    <div class="relative overflow-hidden h-56">
                        <img src="<?php echo BASE_URL . escape($img); ?>" alt="<?php echo escape($p['title']); ?>" class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute top-4 left-4">
                            <span class="inline-block py-1.5 px-4 bg-kmf-orange/90 backdrop-blur-sm text-white text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow-sm">Active</span>
                        </div>
                    </div>
                    <div class="p-6 lg:p-8 flex flex-col flex-1">
                        <h3 class="text-xl md:text-2xl font-extrabold text-kmf-blue mb-4 leading-tight group-hover:text-kmf-orange transition-colors"><?php echo escape($p['title']); ?></h3>
                        <p class="text-gray-600 text-sm font-medium leading-relaxed mb-6 flex-1"><?php echo escape($p['excerpt']); ?></p>
                        
                        <?php if (!empty($p['content'])): ?>
                            <div class="pt-6 border-t border-gray-50 mt-auto">
                                <div class="prose-custom text-sm text-gray-500 line-clamp-3"><?php echo $p['content']; ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php if (empty($current)): ?>
                <div class="bg-gray-50 rounded-3xl p-12 text-center border border-dashed border-gray-300">
                    <p class="text-gray-500 font-medium italic">No current projects are active at the moment.</p>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="flex items-center justify-between mb-10 border-b border-gray-100 pb-4">
                <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue">Completed Projects</h2>
                <div class="hidden sm:block h-1 flex-1 bg-gray-50 mx-6 rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($completed as $p): ?>
                <article class="group bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col h-full opacity-80 hover:opacity-100">
                    <?php
                        $img = !empty($p['image_url']) ? $p['image_url'] : 'assets/images/program-placeholder.svg';
                    ?>
                    <div class="relative overflow-hidden h-48 filter grayscale group-hover:grayscale-0 transition-all duration-500">
                        <img src="<?php echo BASE_URL . escape($img); ?>" alt="<?php echo escape($p['title']); ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 lg:p-8 flex flex-col flex-1">
                        <h3 class="text-lg md:text-xl font-extrabold text-kmf-blue mb-3 leading-tight"><?php echo escape($p['title']); ?></h3>
                        <p class="text-gray-500 text-sm font-medium leading-relaxed line-clamp-2"><?php echo escape($p['excerpt']); ?></p>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php if (empty($completed)): ?>
                <p class="text-gray-400 font-medium italic">Completed project history will be listed here.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
