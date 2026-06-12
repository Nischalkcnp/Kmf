<?php
require_once __DIR__ . '/config/config.php';

$slug = $_GET['slug'] ?? '';
if (!$slug) {
    redirect(BASE_URL);
}

$page = getPageBySlug($slug);
if (!$page) {
    // Try without .php if it was appended
    $cleanSlug = str_replace('.php', '', $slug);
    $page = getPageBySlug($cleanSlug);
}

if (!$page) {
    // 404 - For now just redirect to home or show simple message
    http_response_code(404);
    $pageTitle = 'Page Not Found';
    require_once __DIR__ . '/includes/header.php';
    echo '<section class="py-24 text-center"><h1 class="text-4xl font-bold">404 - Page Not Found</h1><p class="mt-4">The page you are looking for does not exist.</p></section>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$pageTitle = $page['title'];
$metaDescription = $page['meta_description'];

// If this is the team page, fetch team members
$team = [];
$partners = [];
if ($slug === 'team') {
    $pdo = getDb();
    $team = $pdo->query("SELECT * FROM team WHERE is_active = 1 ORDER BY type, sort_order")->fetchAll();
} elseif ($slug === 'partners' || $slug === 'our-partners') {
    $pdo = getDb();
    $partners = $pdo->query("SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order")->fetchAll();
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-6 lg:py-10 bg-white min-h-[60vh]">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <?php
            // Check if this is an about subpage (slug is team or partners)
            $isAboutSubpage = in_array($slug, ['team', 'partners']);
            if ($isAboutSubpage):
            ?>
            <!-- Quick Subpage Navigation -->
            <div class="flex flex-wrap items-center gap-2 mb-8 pb-6 border-b border-gray-100">
                <a href="<?php echo BASE_URL; ?>about.php" class="px-5 py-2.5 bg-white text-gray-600 hover:text-kmf-blue hover:bg-gray-50 border border-gray-100 rounded-xl text-sm font-semibold transition-all">Who We Are</a>
                <a href="<?php echo BASE_URL; ?>history.php" class="px-5 py-2.5 bg-white text-gray-600 hover:text-kmf-blue hover:bg-gray-50 border border-gray-100 rounded-xl text-sm font-semibold transition-all">Our History</a>
                <a href="<?php echo getPageUrl('team'); ?>" class="px-5 py-2.5 <?php echo $slug === 'team' ? 'bg-kmf-blue text-white shadow-md shadow-kmf-blue/15' : 'bg-white text-gray-600 hover:text-kmf-blue hover:bg-gray-50 border border-gray-100'; ?> rounded-xl text-sm font-semibold transition-all">Meet Our Team</a>
                <a href="<?php echo getPageUrl('partners'); ?>" class="px-5 py-2.5 <?php echo $slug === 'partners' ? 'bg-kmf-blue text-white shadow-md shadow-kmf-blue/15' : 'bg-white text-gray-600 hover:text-kmf-blue hover:bg-gray-50 border border-gray-100'; ?> rounded-xl text-sm font-semibold transition-all">Our Partners</a>
            </div>
            <?php endif; ?>

            <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2"><?php echo $page['parent_id'] ? 'Sub Page' : 'Information'; ?></p>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-kmf-blue mb-6 leading-tight tracking-tighter"><?php echo escape($pageTitle); ?></h1>
            
            <div class="prose-custom max-w-none text-gray-600 text-lg leading-relaxed">
                <?php echo $page['content']; ?>
            </div>
            
            <?php if ($slug === 'team' && !empty($team)): ?>
            <div class="mt-10">
                <?php 
                $boardMembers = array_filter($team, function($t) { return $t['type'] === 'board'; });
                $staffMembers = array_filter($team, function($t) { return $t['type'] === 'staff'; });
                ?>
                
                <?php if (!empty($boardMembers)): ?>
                <div class="mb-10 animate-fade-in">
                    <h3 class="text-xl font-bold text-kmf-blue mb-6 border-b border-gray-100 pb-2">Board of Directors</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($boardMembers as $t): ?>
                        <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group hover:-translate-y-1.5">
                            <div class="relative w-28 h-28 mx-auto mb-4 transition-transform duration-300 group-hover:scale-105">
                                <?php if (!empty($t['image_url'])): ?>
                                    <img src="<?php echo BASE_URL . escape($t['image_url']); ?>" alt="<?php echo escape($t['name']); ?>" class="relative w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                                <?php else: ?>
                                    <div class="relative w-full h-full rounded-full bg-kmf-green-light flex items-center justify-center text-kmf-blue font-bold text-2xl border-4 border-white shadow-md"><?php echo strtoupper(substr($t['name'], 0, 1)); ?></div>
                                <?php endif; ?>
                            </div>
                            <h4 class="font-bold text-lg text-kmf-blue text-center mb-1"><?php echo escape($t['name']); ?></h4>
                            <?php if (!empty($t['role'])): ?>
                                <p class="text-kmf-orange font-semibold text-center mb-3 text-xs uppercase tracking-widest"><?php echo escape($t['role']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($t['bio'])): ?>
                                <p class="text-gray-600 text-center text-sm leading-relaxed"><?php echo nl2br(escape($t['bio'])); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($staffMembers)): ?>
                <div class="animate-fade-in">
                    <h3 class="text-xl font-bold text-kmf-blue mb-6 border-b border-gray-100 pb-2">Our Staff</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($staffMembers as $t): ?>
                        <div class="bg-gray-50 rounded-3xl p-6 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group hover:-translate-y-1.5">
                            <div class="relative w-28 h-28 mx-auto mb-4 transition-transform duration-300 group-hover:scale-105">
                                <?php if (!empty($t['image_url'])): ?>
                                    <img src="<?php echo BASE_URL . escape($t['image_url']); ?>" alt="<?php echo escape($t['name']); ?>" class="relative w-full h-full rounded-full object-cover border-4 border-white shadow-md">
                                <?php else: ?>
                                    <div class="relative w-full h-full rounded-full bg-kmf-green-light flex items-center justify-center text-kmf-blue font-bold text-2xl border-4 border-white shadow-md"><?php echo strtoupper(substr($t['name'], 0, 1)); ?></div>
                                <?php endif; ?>
                            </div>
                            <h4 class="font-bold text-lg text-kmf-blue text-center mb-1"><?php echo escape($t['name']); ?></h4>
                            <?php if (!empty($t['role'])): ?>
                                <p class="text-kmf-orange font-semibold text-center mb-3 text-xs uppercase tracking-widest"><?php echo escape($t['role']); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($t['bio'])): ?>
                                <p class="text-gray-600 text-center text-sm leading-relaxed"><?php echo nl2br(escape($t['bio'])); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (($slug === 'partners' || $slug === 'our-partners') && !empty($partners)): ?>
            <div class="mt-10 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 items-center">
                <?php foreach ($partners as $p): ?>
                <div class="group flex justify-center p-6 bg-gray-50 rounded-2xl border border-transparent hover:border-kmf-orange/20 hover:bg-white hover:shadow-xl transition-all duration-500">
                    <?php if (!empty($p['link_url'])): ?>
                        <a href="<?php echo escape($p['link_url']); ?>" target="_blank" rel="noopener" class="block transform group-hover:scale-110 transition-transform">
                    <?php endif; ?>
                    <?php if (!empty($p['logo_url'])): ?>
                        <img src="<?php echo (str_starts_with($p['logo_url'], 'http') ? '' : BASE_URL) . escape($p['logo_url']); ?>" alt="<?php echo escape($p['name']); ?>" class="max-h-16 w-auto object-contain grayscale group-hover:grayscale-0 transition-all duration-500">
                    <?php else: ?>
                        <span class="text-gray-400 font-bold text-lg group-hover:text-kmf-blue transition-colors"><?php echo escape($p['name']); ?></span>
                    <?php endif; ?>
                    <?php if (!empty($p['link_url'])): ?>
                        </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
