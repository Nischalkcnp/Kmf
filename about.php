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

<section class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20 items-center">
            <div class="order-2 lg:order-1">
                <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">Our Story</p>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-kmf-blue mb-8 leading-tight"><?php echo escape($pageTitle); ?></h1>
                <div class="prose-custom max-w-none text-gray-600 text-lg leading-relaxed">
                    <?php 
                    if ($page && !empty(trim($page['content']))) {
                        echo $page['content'];
                    } else {
                        echo '<p>Kanchhi Maya Tamang Foundation (KMF) is dedicated to advancing education, community welfare, and health in Nepal. Our foundation was born from a desire to create lasting change in the lives of the marginalized and underserved populations of the Himalayan region.</p>';
                    }
                    ?>
                </div>
            </div>
            <div class="order-1 lg:order-2">
                <div class="relative group">
                    <img src="<?php echo BASE_URL; ?>assets/images/about-women-community.jpg" alt="Women and community members participating in KMF activities" class="w-full h-72 sm:h-80 md:h-[400px] lg:h-[450px] object-cover rounded-3xl shadow-2xl border-4 border-white transform transition-transform duration-500 group-hover:scale-[1.02]">
                    <div class="absolute -bottom-6 -left-6 bg-kmf-orange text-white p-6 rounded-2xl shadow-xl hidden lg:block">
                        <p class="text-2xl font-bold">100%</p>
                        <p class="text-xs uppercase tracking-wider font-semibold">Local Impact</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 mt-16 md:mt-24">
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 shadow-sm">
                <h3 class="text-kmf-blue font-bold text-xl mb-4">Our Mission</h3>
                <p class="text-gray-600 leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('mission'))); ?></p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 shadow-sm">
                <h3 class="text-kmf-blue font-bold text-xl mb-4">Our Vision</h3>
                <p class="text-gray-600 leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('vision'))); ?></p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 shadow-sm sm:col-span-2 lg:col-span-1">
                <h3 class="text-kmf-blue font-bold text-xl mb-4">Our Goal</h3>
                <p class="text-gray-600 leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('goal'))); ?></p>
            </div>
        </div>
    </div>
</section>

<section id="team" class="py-8 md:py-12 lg:py-16 bg-gray-50">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="mb-12 lg:mb-16">
            <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">Leadership</p>
            <h2 class="text-3xl md:text-4xl font-extrabold text-kmf-blue">Meet Our Team</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 lg:gap-8">
            <?php foreach ($team as $t): ?>
            <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group hover:-translate-y-2">
                <div class="relative w-32 h-32 mx-auto mb-6 transition-transform duration-300 group-hover:scale-110">
                    <?php if (!empty($t['image_url'])): ?>
                        <img src="<?php echo BASE_URL . escape($t['image_url']); ?>" alt="<?php echo escape($t['name']); ?>" class="relative w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                    <?php else: ?>
                        <div class="relative w-full h-full rounded-full bg-kmf-green-light flex items-center justify-center text-kmf-blue font-bold text-3xl border-4 border-white shadow-md"><?php echo strtoupper(substr($t['name'], 0, 1)); ?></div>
                    <?php endif; ?>
                </div>
                <h3 class="font-bold text-xl text-kmf-blue text-center mb-1"><?php echo escape($t['name']); ?></h3>
                <?php if (!empty($t['role'])): ?>
                    <p class="text-kmf-orange font-semibold text-center mb-4 text-xs uppercase tracking-widest"><?php echo escape($t['role']); ?></p>
                <?php endif; ?>
                <?php if (!empty($t['bio'])): ?>
                    <p class="text-gray-600 text-center text-sm leading-relaxed"><?php echo nl2br(escape($t['bio'])); ?></p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="partners" class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="text-center mb-12 lg:mb-16">
            <h2 class="text-3xl md:text-4xl font-extrabold text-kmf-blue inline-block relative">
                Our Partners
                <span class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 w-16 h-1 bg-kmf-orange rounded-full"></span>
            </h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 items-center">
            <?php foreach ($partners as $p): ?>
            <div class="group flex justify-center p-4 grayscale hover:grayscale-0 transition-all duration-500">
                <?php if (!empty($p['link_url'])): ?>
                    <a href="<?php echo escape($p['link_url']); ?>" target="_blank" rel="noopener" class="block transform group-hover:scale-110 transition-transform">
                <?php endif; ?>
                <?php if (!empty($p['logo_url'])): ?>
                    <img src="<?php echo BASE_URL . escape($p['logo_url']); ?>" alt="<?php echo escape($p['name']); ?>" class="max-h-16 w-auto object-contain">
                <?php else: ?>
                    <span class="text-gray-400 font-bold text-lg group-hover:text-kmf-blue transition-colors"><?php echo escape($p['name']); ?></span>
                <?php endif; ?>
                <?php if (!empty($p['link_url'])): ?>
                    </a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
