<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('what-we-do');
$pageTitle = $page ? $page['title'] : 'What We Do';
$metaDescription = $page ? $page['meta_description'] : 'Strategic areas: Education, Community, and Health';
$pageIntro = ($page && !empty(trim($page['content']))) ? $page['content'] : '<p>Our work is organized around education, community welfare, and health—carefully aligned with our foundation\'s core mission to empower Nepal\'s local communities.</p>';

$pdo = getDb();
$areas = $pdo->query("SELECT * FROM strategic_areas WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl">
            <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">Our Impact</p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-kmf-blue mb-6 leading-tight">Strategic Areas</h1>
            <p class="text-lg md:text-xl text-gray-600 mb-12 font-medium leading-relaxed"><?php echo $pageIntro; ?></p>
        </div>

        <div class="grid grid-cols-1 gap-12 md:gap-20">
            <?php foreach ($areas as $index => $a): 
                $isEven = $index % 2 === 0;
            ?>
            <article id="<?php echo escape($a['slug']); ?>" class="group scroll-mt-24">
                <div class="flex flex-col <?php echo $isEven ? 'md:flex-row' : 'md:flex-row-reverse'; ?> items-center gap-8 lg:gap-16">
                    <!-- Image Card -->
                    <div class="w-full md:w-1/2 overflow-hidden rounded-3xl shadow-lg border border-gray-100 group-hover:shadow-2xl transition-all duration-700">
                        <div class="relative aspect-[4/3] md:aspect-[16/10] overflow-hidden">
                            <?php if (!empty($a['image_url'])): ?>
                                <img src="<?php echo BASE_URL . escape($a['image_url']); ?>" alt="<?php echo escape($a['title']); ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-1000">
                            <?php else: ?>
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                     <div class="w-24 h-24 rounded-2xl bg-kmf-green-light flex items-center justify-center text-kmf-blue shadow-inner group-hover:bg-kmf-orange group-hover:text-white transition-all duration-300">
                                        <?php if ($a['icon'] === 'education'): ?>
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                        <?php elseif ($a['icon'] === 'people'): ?>
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <?php else: ?>
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <!-- Overlay detail for touch of luxury -->
                            <div class="absolute inset-0 bg-gradient-to-t from-kmf-blue/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                    </div>

                    <!-- Content Card -->
                    <div class="w-full md:w-1/2">
                        <div class="flex items-center gap-4 mb-4">
                             <div class="w-10 h-10 rounded-xl bg-kmf-green-light flex items-center justify-center text-kmf-blue group-hover:bg-kmf-orange group-hover:text-white transition-all duration-300">
                                <?php if ($a['icon'] === 'education'): ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                <?php elseif ($a['icon'] === 'people'): ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <?php else: ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <?php endif; ?>
                            </div>
                            <span class="text-sm font-bold uppercase tracking-wider text-kmf-orange"><?php echo escape(str_replace('-', ' ', $a['slug'])); ?></span>
                        </div>
                        <h2 class="text-3xl lg:text-4xl font-extrabold text-kmf-blue mb-6 group-hover:text-kmf-orange transition-colors"><?php echo escape($a['title']); ?></h2>
                        <?php if (!empty($a['excerpt'])): ?>
                            <p class="text-xl text-gray-700 mb-6 font-medium leading-relaxed italic border-l-4 border-kmf-green pl-4"><?php echo escape($a['excerpt']); ?></p>
                        <?php endif; ?>
                        <div class="prose-custom text-gray-600 max-w-none text-lg leading-loose">
                            <?php echo $a['content'] ?: ''; ?>
                        </div>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($areas)): ?>
            <div class="bg-gray-50 rounded-2xl p-12 text-center border border-dashed border-gray-300">
                <p class="text-gray-500 font-medium italic">Strategic areas will be detailed here soon.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
