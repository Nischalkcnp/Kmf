<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Our Programs';
$metaDescription = 'Current and completed projects of Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$current = $pdo->query("SELECT * FROM programs WHERE is_active = 1 AND type = 'current' ORDER BY sort_order ASC")->fetchAll();
$completed = $pdo->query("SELECT * FROM programs WHERE is_active = 1 AND type = 'completed' ORDER BY sort_order ASC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">Our Programs</p>
        <h1 class="text-3xl md:text-4xl font-bold text-kmf-blue mb-6">Programs & Projects</h1>
        <p class="text-gray-600 max-w-3xl mb-12">Explore our current and completed initiatives in education, community, and health.</p>

        <h2 class="text-xl font-bold text-kmf-blue mb-6">Current Projects</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
            <?php foreach ($current as $p): ?>
            <article class="bg-gray-50 rounded-xl overflow-hidden border border-gray-100 card-hover">
                <?php
                    $img = !empty($p['image_url']) ? $p['image_url'] : 'assets/images/program-placeholder.svg';
                ?>
                <img src="<?php echo BASE_URL . escape($img); ?>" alt="<?php echo escape($p['title']); ?>" class="w-full h-48 object-cover">
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-kmf-blue mb-2"><?php echo escape($p['title']); ?></h3>
                    <p class="text-gray-600 text-sm"><?php echo escape($p['excerpt']); ?></p>
                    <?php if (!empty($p['content'])): ?>
                        <div class="prose-custom text-sm mt-3 text-gray-600"><?php echo $p['content']; ?></div>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php if (empty($current)): ?>
            <p class="text-gray-600 mb-12">No current projects listed.</p>
        <?php endif; ?>

        <h2 class="text-xl font-bold text-kmf-blue mb-6">Completed Projects</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($completed as $p): ?>
            <article class="bg-gray-50 rounded-xl overflow-hidden border border-gray-100 card-hover">
                <?php
                    $img = !empty($p['image_url']) ? $p['image_url'] : 'assets/images/program-placeholder.svg';
                ?>
                <img src="<?php echo BASE_URL . escape($img); ?>" alt="<?php echo escape($p['title']); ?>" class="w-full h-48 object-cover">
                <div class="p-5">
                    <h3 class="text-lg font-semibold text-kmf-blue mb-2"><?php echo escape($p['title']); ?></h3>
                    <p class="text-gray-600 text-sm"><?php echo escape($p['excerpt']); ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php if (empty($completed)): ?>
            <p class="text-gray-600">No completed projects listed.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
