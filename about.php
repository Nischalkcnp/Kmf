<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('about');
$pageTitle = $page ? $page['title'] : 'Who We Are';
$metaDescription = $page ? $page['meta_description'] : 'Learn about Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$team = $pdo->query("SELECT * FROM team WHERE is_active = 1 ORDER BY type, sort_order")->fetchAll();
$partners = $pdo->query("SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-10 md:py-12 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
            <div>
                <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">About Us</p>
                <h1 class="text-3xl md:text-4xl font-bold text-kmf-blue mb-8"><?php echo escape($pageTitle); ?></h1>
                <div class="prose-custom max-w-4xl text-gray-600">
                    <?php echo $page ? $page['content'] : '<p>Kanchhi Maya Tamang Foundation (KMF) is dedicated to advancing education, community welfare, and health in Nepal.</p>'; ?>
                </div>
            </div>
            <div class="hidden md:block">
                <img src="<?php echo BASE_URL; ?>assets/images/about-women-community.jpg" alt="Women and community members participating in KMF activities" class="w-full h-72 lg:h-80 object-cover rounded-2xl shadow-md border-4 border-white/50">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <h3 class="text-kmf-blue font-bold text-lg mb-3">Mission</h3>
                <p class="text-gray-600 text-sm"><?php echo nl2br(escape(getSetting('mission'))); ?></p>
            </div>
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <h3 class="text-kmf-blue font-bold text-lg mb-3">Vision</h3>
                <p class="text-gray-600 text-sm"><?php echo nl2br(escape(getSetting('vision'))); ?></p>
            </div>
            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <h3 class="text-kmf-blue font-bold text-lg mb-3">Goal</h3>
                <p class="text-gray-600 text-sm"><?php echo nl2br(escape(getSetting('goal'))); ?></p>
            </div>
        </div>
    </div>
</section>

<section id="team" class="py-10 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-kmf-blue mb-8">Our Team</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($team as $t): ?>
            <div class="bg-white rounded-xl p-6 border border-gray-100 card-hover">
                <?php if (!empty($t['image_url'])): ?>
                    <img src="<?php echo BASE_URL . escape($t['image_url']); ?>" alt="<?php echo escape($t['name']); ?>" class="w-24 h-24 rounded-full object-cover mx-auto mb-4">
                <?php else: ?>
                    <div class="w-24 h-24 rounded-full bg-kmf-green-light flex items-center justify-center text-kmf-blue font-bold text-2xl mx-auto mb-4"><?php echo strtoupper(substr($t['name'], 0, 1)); ?></div>
                <?php endif; ?>
                <h3 class="font-semibold text-kmf-blue text-center"><?php echo escape($t['name']); ?></h3>
                <?php if (!empty($t['role'])): ?>
                    <p class="text-sm text-gray-500 text-center mb-2"><?php echo escape($t['role']); ?></p>
                <?php endif; ?>
                <?php if (!empty($t['bio'])): ?>
                    <p class="text-sm text-gray-600"><?php echo nl2br(escape($t['bio'])); ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if (empty($team)): ?>
            <p class="text-gray-600">Team information will be added soon.</p>
        <?php endif; ?>
    </div>
</section>

<section id="partners" class="py-10 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-bold text-kmf-blue mb-8">Partners</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 items-center">
            <?php foreach ($partners as $p): ?>
            <div class="flex justify-center">
                <?php if (!empty($p['link_url'])): ?>
                    <a href="<?php echo escape($p['link_url']); ?>" target="_blank" rel="noopener" class="block">
                <?php endif; ?>
                <?php if (!empty($p['logo_url'])): ?>
                    <img src="<?php echo BASE_URL . escape($p['logo_url']); ?>" alt="<?php echo escape($p['name']); ?>" class="max-h-16 w-auto object-contain">
                <?php else: ?>
                    <span class="text-gray-600 font-medium"><?php echo escape($p['name']); ?></span>
                <?php endif; ?>
                <?php if (!empty($p['link_url'])): ?>
                    </a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if (empty($partners)): ?>
            <p class="text-gray-600">Partners will be listed here.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
