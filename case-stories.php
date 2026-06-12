<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Stories';
$metaDescription = 'Explore the life-changing impact stories from our health sector initiatives.';

$pdo = getDb();
$stories = $pdo->query("SELECT * FROM case_stories WHERE is_active = 1 ORDER BY story_date DESC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-10 md:py-16 lg:py-20 bg-slate-50 relative overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 -mr-32 -mt-32 w-96 h-96 rounded-full bg-kmf-orange/5 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -ml-32 -mb-32 w-96 h-96 rounded-full bg-kmf-blue/5 blur-3xl"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16 md:mb-24">
            <span class="inline-block py-1 px-3 rounded-full bg-kmf-orange/10 text-kmf-orange font-bold text-xs uppercase tracking-widest mb-4">Our Impact</span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-kmf-blue mb-6 tracking-tight leading-tight">Stories</h1>
            <p class="text-lg md:text-xl text-slate-600 font-medium leading-relaxed">
                Behind every initiative, there is a human story. Explore how our interventions in the health sector have transformed lives and restored hope across communities in Nepal.
            </p>
        </div>

        <?php if (empty($stories)): ?>
            <div class="bg-white rounded-3xl p-12 text-center border border-dashed border-slate-300 max-w-3xl mx-auto shadow-sm">
                <p class="text-slate-500 font-medium text-lg italic mt-4">New stories are being documented. Check back soon!</p>
            </div>
        <?php else: ?>
            <div class="max-w-5xl mx-auto">
                <div class="space-y-16 md:space-y-24">
                    <?php 
                    $isEven = false;
                    foreach ($stories as $index => $story): 
                    ?>
                    <article class="relative flex flex-col md:flex-row items-center gap-8 md:gap-12 group">
                        
                        <!-- Connecting Line (visible on desktop) -->
                        <?php if ($index !== count($stories) - 1): ?>
                            <div class="hidden md:block absolute left-1/2 top-1/2 bottom-[-96px] w-0.5 bg-slate-200 -translate-x-1/2 -z-10 group-hover:bg-kmf-orange/30 transition-colors duration-500"></div>
                        <?php endif; ?>

                        <!-- Content wrapper -->
                        <div class="w-full md:w-1/2 flex flex-col <?php echo $isEven ? 'md:order-1 md:items-end md:text-right' : 'md:order-2 md:items-start md:text-left'; ?>">
                            <div class="bg-white p-8 md:p-10 rounded-3xl shadow-lg border border-slate-100 hover:shadow-2xl transition-all duration-500 transform group-hover:-translate-y-2 w-full">
                                <span class="inline-block py-1.5 px-4 rounded-full bg-slate-100 text-slate-500 font-bold text-sm mb-6 flex items-center gap-2 <?php echo $isEven ? 'md:ml-auto md:flex-row-reverse' : ''; ?> w-max">
                                    <svg class="w-4 h-4 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <?php echo formatDate($story['story_date'], 'F j, Y'); ?>
                                </span>
                                
                                <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue mb-4 leading-tight group-hover:text-kmf-orange transition-colors">
                                    <?php echo escape($story['title']); ?>
                                </h2>
                                
                                <?php if (!empty($story['excerpt'])): ?>
                                    <p class="text-lg text-slate-600 font-medium leading-relaxed mb-6">
                                        <?php echo escape($story['excerpt']); ?>
                                    </p>
                                <?php endif; ?>
                                
                                <?php if (!empty($story['content'])): ?>
                                    <div class="prose-custom text-slate-500 text-base leading-relaxed border-t border-slate-100 pt-6">
                                        <?php echo $story['content']; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($story['link_url'])): ?>
                                    <div class="mt-6 <?php echo $isEven ? 'flex md:justify-end' : 'flex md:justify-start'; ?>">
                                        <a href="<?php echo escape($story['link_url']); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-kmf-orange text-slate-700 hover:text-white font-bold text-sm rounded-xl transition-all duration-300 shadow-sm hover:shadow-md">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            <span><?php echo escape($story['link_text'] ?: 'Read More'); ?></span>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Timeline Dot -->
                        <div class="hidden md:flex absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 w-16 h-16 rounded-full bg-white border-4 border-slate-100 shadow-xl items-center justify-center z-10 group-hover:border-kmf-orange transition-colors duration-500">
                            <div class="w-4 h-4 bg-kmf-orange rounded-full group-hover:scale-150 transition-transform duration-500"></div>
                        </div>

                        <!-- Image wrapper -->
                        <div class="w-full md:w-1/2 <?php echo $isEven ? 'md:order-2' : 'md:order-1'; ?>">
                            <?php 
                                // Clean up paths
                                $before_path = ltrim($story['image_before_url'] ?? '', '/');
                                $after_path = ltrim($story['image_after_url'] ?? '', '/');
                                
                                // Check if files actually exist on disk, else use nice SVG placeholders
                                $before_exists = !empty($before_path) && file_exists(__DIR__ . '/' . $before_path);
                                $after_exists = !empty($after_path) && file_exists(__DIR__ . '/' . $after_path);
                                $has_after_text = !empty($after_path); // Did user intend to have an after photo?

                                $final_before = $before_exists ? $before_path : 'assets/images/program-placeholder.svg';
                                $final_after = $after_exists ? $after_path : 'assets/images/event-placeholder.svg';
                            ?>
                            
                            <?php if (!empty($before_path) && $has_after_text): ?>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="relative rounded-3xl overflow-hidden shadow-xl aspect-[4/5] group-hover:scale-[1.03] transition-transform duration-700">
                                        <div class="absolute top-4 left-4 bg-kmf-orange text-white text-xs font-bold px-3 py-1 rounded-full z-20 shadow">Before</div>
                                        <div class="absolute inset-0 bg-kmf-blue/10 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                                        <img src="<?php echo BASE_URL . escape($final_before); ?>" alt="Before: <?php echo escape($story['title']); ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div class="relative rounded-3xl overflow-hidden shadow-xl aspect-[4/5] group-hover:scale-[1.03] transition-transform duration-700">
                                        <div class="absolute top-4 left-4 bg-kmf-green-light text-kmf-blue text-xs font-bold px-3 py-1 rounded-full z-20 shadow">After</div>
                                        <div class="absolute inset-0 bg-kmf-blue/10 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                                        <img src="<?php echo BASE_URL . escape($final_after); ?>" alt="After: <?php echo escape($story['title']); ?>" class="w-full h-full object-cover">
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="relative rounded-3xl overflow-hidden shadow-2xl aspect-[4/3] group-hover:scale-[1.03] transition-transform duration-700">
                                    <div class="absolute inset-0 bg-kmf-blue/10 group-hover:bg-transparent transition-colors duration-500 z-10"></div>
                                    <img src="<?php echo BASE_URL . escape($final_before); ?>" alt="<?php echo escape($story['title']); ?>" class="w-full h-full object-cover">
                                </div>
                            <?php endif; ?>
                        </div>
                    </article>
                    <?php 
                        $isEven = !$isEven;
                    endforeach; 
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
