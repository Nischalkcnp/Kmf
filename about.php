<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('about');
$pageTitle = $page ? $page['title'] : 'Who We Are';
$metaDescription = $page ? $page['meta_description'] : 'Learn about Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$team     = $pdo->query("SELECT * FROM team WHERE is_active = 1 ORDER BY type, sort_order")->fetchAll();
$partners = $pdo->query("SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order")->fetchAll();

$boardMembers = array_filter($team, fn($t) => $t['type'] === 'board');
$staffMembers = array_filter($team, fn($t) => $t['type'] === 'staff');

require_once __DIR__ . '/includes/header.php';
?>

<!-- ═══════════════════════════════════════════════
     HERO BANNER — compact gradient strip
════════════════════════════════════════════════ -->
<section class="relative bg-kmf-blue overflow-hidden py-5 md:py-7">
    <!-- Decorative blobs -->
    <div class="absolute -top-20 -right-20 w-72 h-72 bg-kmf-orange/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <!-- Sub-nav -->
        <div class="flex flex-wrap items-center gap-2 mb-5">
            <a href="<?php echo BASE_URL; ?>about.php"    class="px-4 py-1.5 bg-white text-kmf-blue rounded-xl text-xs font-bold shadow transition-all">Who We Are</a>
            <a href="<?php echo BASE_URL; ?>history.php"  class="px-4 py-1.5 bg-white/10 text-white/80 hover:bg-white/20 rounded-xl text-xs font-semibold transition-all">Our History</a>
            <a href="<?php echo getPageUrl('team'); ?>"     class="px-4 py-1.5 bg-white/10 text-white/80 hover:bg-white/20 rounded-xl text-xs font-semibold transition-all">Meet Our Team</a>
            <a href="<?php echo getPageUrl('partners'); ?>" class="px-4 py-1.5 bg-white/10 text-white/80 hover:bg-white/20 rounded-xl text-xs font-semibold transition-all">Our Partners</a>
        </div>

        <!-- Title + intro -->
        <div class="max-w-3xl">
            <span class="inline-block text-kmf-orange font-bold uppercase tracking-[0.25em] text-xs mb-1.5">Our Story</span>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight tracking-tight mb-2 font-montserrat">
                <?php echo escape($pageTitle); ?>
            </h1>
            <div class="text-white/70 text-sm leading-relaxed font-medium max-w-2xl">
                <?php
                if ($page && !empty(trim($page['content']))) {
                    $plain = strip_tags($page['content']);
                    echo '<p>' . escape(mb_substr($plain, 0, 180)) . (mb_strlen($plain) > 180 ? '…' : '') . '</p>';
                } else {
                    echo '<p>Kanchhi Maya Tamang Foundation (KMF) is dedicated to advancing education, community welfare, and health in Nepal.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     FULL CONTENT BLOCK
════════════════════════════════════════════════ -->
<section class="py-8 lg:py-12 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl mx-auto prose-custom text-gray-600 text-base leading-relaxed">
            <?php
            if ($page && !empty(trim($page['content']))) {
                echo $page['content'];
            } else {
                echo '<p>Kanchhi Maya Tamang Foundation (KMF) is dedicated to advancing education, community welfare, and health in Nepal. Our foundation was born from a desire to create lasting change in the lives of the marginalized and underserved populations of the Himalayan region.</p>
                <p class="mt-4">We believe that every child deserves a quality education, every mother deserves safe healthcare, and every community deserves the opportunity to thrive. Our programs are designed and implemented in close collaboration with local leaders and community members to ensure sustainability and cultural relevance.</p>';
            }
            ?>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     MISSION · VISION · GOAL   (icon cards)
════════════════════════════════════════════════ -->
<section class="py-8 lg:py-10 bg-gray-50/70">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <!-- Mission -->
            <div class="group relative bg-white rounded-[1.75rem] p-7 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-400 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-kmf-blue rounded-l-[1.75rem]"></div>
                <div class="w-11 h-11 rounded-xl bg-kmf-blue/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-kmf-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold text-kmf-blue uppercase tracking-widest mb-2">Our Mission</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('mission'))); ?></p>
            </div>

            <!-- Vision -->
            <div class="group relative bg-white rounded-[1.75rem] p-7 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-400 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-kmf-orange rounded-l-[1.75rem]"></div>
                <div class="w-11 h-11 rounded-xl bg-kmf-orange/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold text-kmf-blue uppercase tracking-widest mb-2">Our Vision</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('vision'))); ?></p>
            </div>

            <!-- Goal -->
            <div class="group relative bg-white rounded-[1.75rem] p-7 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-400 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-green-500 rounded-l-[1.75rem]"></div>
                <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold text-kmf-blue uppercase tracking-widest mb-2">Our Goal</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('goal'))); ?></p>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     TEAM
════════════════════════════════════════════════ -->
<?php if (!empty($team)): ?>
<section id="team" class="py-8 lg:py-12 bg-white">
    <div class="container mx-auto px-4 lg:px-8">

        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-kmf-orange font-bold uppercase tracking-widest text-xs block mb-1">Leadership</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue font-montserrat">Meet Our Team</h2>
            </div>
        </div>

        <?php if (!empty($boardMembers)): ?>
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-5">
                <div class="h-px flex-1 bg-gray-100"></div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2">Board of Directors</span>
                <div class="h-px flex-1 bg-gray-100"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <?php foreach ($boardMembers as $t): ?>
                <div class="group flex items-center gap-4 bg-gray-50/60 hover:bg-white border border-transparent hover:border-gray-100 hover:shadow-lg rounded-2xl p-4 transition-all duration-300">
                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl overflow-hidden shadow-sm border-2 border-white group-hover:scale-105 transition-transform duration-300">
                        <?php if (!empty($t['image_url'])): ?>
                            <img src="<?php echo BASE_URL . escape($t['image_url']); ?>" alt="<?php echo escape($t['name']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-kmf-blue/10 flex items-center justify-center text-kmf-blue font-black text-xl"><?php echo strtoupper(substr($t['name'], 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-kmf-blue text-sm leading-tight truncate"><?php echo escape($t['name']); ?></h4>
                        <?php if (!empty($t['role'])): ?>
                            <p class="text-kmf-orange text-[10px] font-bold uppercase tracking-widest mt-0.5"><?php echo escape($t['role']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($t['bio'])): ?>
                            <p class="text-gray-500 text-xs mt-1 line-clamp-2 leading-relaxed"><?php echo escape($t['bio']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($staffMembers)): ?>
        <div>
            <div class="flex items-center gap-3 mb-5">
                <div class="h-px flex-1 bg-gray-100"></div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2">Our Staff</span>
                <div class="h-px flex-1 bg-gray-100"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <?php foreach ($staffMembers as $t): ?>
                <div class="group flex items-center gap-4 bg-gray-50/60 hover:bg-white border border-transparent hover:border-gray-100 hover:shadow-lg rounded-2xl p-4 transition-all duration-300">
                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl overflow-hidden shadow-sm border-2 border-white group-hover:scale-105 transition-transform duration-300">
                        <?php if (!empty($t['image_url'])): ?>
                            <img src="<?php echo BASE_URL . escape($t['image_url']); ?>" alt="<?php echo escape($t['name']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-kmf-orange/10 flex items-center justify-center text-kmf-orange font-black text-xl"><?php echo strtoupper(substr($t['name'], 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-kmf-blue text-sm leading-tight truncate"><?php echo escape($t['name']); ?></h4>
                        <?php if (!empty($t['role'])): ?>
                            <p class="text-kmf-orange text-[10px] font-bold uppercase tracking-widest mt-0.5"><?php echo escape($t['role']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($t['bio'])): ?>
                            <p class="text-gray-500 text-xs mt-1 line-clamp-2 leading-relaxed"><?php echo escape($t['bio']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════
     PARTNERS
════════════════════════════════════════════════ -->
<?php if (!empty($partners)): ?>
<section id="partners" class="py-8 lg:py-10 bg-gray-50/60 border-t border-gray-100">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            <div class="h-px flex-1 bg-gray-200"></div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-3">Our Partners</span>
            <div class="h-px flex-1 bg-gray-200"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 items-center">
            <?php foreach ($partners as $p): ?>
            <div class="group flex justify-center p-4 grayscale hover:grayscale-0 opacity-60 hover:opacity-100 transition-all duration-400">
                <?php if (!empty($p['link_url'])): ?>
                    <a href="<?php echo escape($p['link_url']); ?>" target="_blank" rel="noopener" class="block transform group-hover:scale-105 transition-transform">
                <?php endif; ?>
                <?php if (!empty($p['logo_url'])): ?>
                    <img src="<?php echo (str_starts_with($p['logo_url'], 'http') ? '' : BASE_URL) . escape($p['logo_url']); ?>" alt="<?php echo escape($p['name']); ?>" class="max-h-12 w-auto object-contain">
                <?php else: ?>
                    <span class="text-gray-400 font-bold text-base group-hover:text-kmf-blue transition-colors"><?php echo escape($p['name']); ?></span>
                <?php endif; ?>
                <?php if (!empty($p['link_url'])): ?></a><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
