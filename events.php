<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Events';
$metaDescription = 'Upcoming and past events of Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$upcoming = $pdo->query("SELECT * FROM events WHERE is_active = 1 AND type = 'upcoming' AND event_date >= CURDATE() ORDER BY event_date ASC")->fetchAll();
$past = $pdo->query("SELECT * FROM events WHERE is_active = 1 AND (type = 'past' OR event_date < CURDATE()) ORDER BY event_date DESC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-12 md:py-20 lg:py-24 bg-white font-medium">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">Community</p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-kmf-blue mb-6 leading-tight">Events & Activities</h1>
            <p class="text-lg md:text-xl text-gray-600 font-medium">Join us in our upcoming activities or explore the history of our past community engagements.</p>
        </div>

        <div class="mb-20">
            <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue">Upcoming Events</h2>
                <div class="hidden sm:block h-1 flex-1 bg-gray-50 mx-6 rounded-full"></div>
                <span class="bg-kmf-green/10 text-kmf-green px-4 py-1 rounded-full text-xs font-bold uppercase tracking-widest"><?php echo count($upcoming); ?> Open</span>
            </div>
            
            <div class="grid grid-cols-1 gap-8">
                <?php foreach ($upcoming as $e): ?>
                <article class="group bg-gray-50 rounded-3xl p-6 md:p-10 border border-gray-100 flex flex-col lg:flex-row gap-8 lg:gap-12 shadow-sm hover:shadow-xl transition-all duration-500">
                    <?php
                        $img = !empty($e['image_url']) ? $e['image_url'] : 'assets/images/event-placeholder.svg';
                    ?>
                    <div class="flex-shrink-0 w-full lg:w-72 h-56 lg:h-64 overflow-hidden rounded-2xl shadow-lg transform transition-transform duration-500 group-hover:scale-[1.02]">
                        <img src="<?php echo BASE_URL . escape($img); ?>" alt="<?php echo escape($e['title']); ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 py-2">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="bg-white p-2 rounded-xl shadow-sm text-kmf-orange">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="text-sm font-extrabold text-kmf-orange uppercase tracking-widest">
                                <?php echo formatDate($e['event_date']); ?>
                                <?php if (!empty($e['end_date']) && $e['end_date'] !== $e['event_date']): ?>
                                    – <?php echo formatDate($e['end_date']); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-extrabold text-kmf-blue mt-1 mb-4 leading-tight group-hover:text-kmf-orange transition-colors"><?php echo escape($e['title']); ?></h3>
                        
                        <?php if (!empty($e['venue'])): ?>
                            <div class="flex items-center gap-2 text-gray-500 mb-6 font-semibold">
                                <svg class="w-4 h-4 text-kmf-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span><?php echo escape($e['venue']); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($e['excerpt'])): ?>
                            <p class="text-gray-600 text-lg leading-relaxed mb-6 font-medium"><?php echo escape($e['excerpt']); ?></p>
                        <?php endif; ?>
                        
                        <?php if (!empty($e['content'])): ?>
                            <div class="prose-custom text-gray-500 mt-4 pt-6 border-t border-gray-200"><?php echo $e['content']; ?></div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php if (empty($upcoming)): ?>
                <div class="bg-gray-50 rounded-3xl p-12 text-center border border-dashed border-gray-300">
                    <p class="text-gray-500 font-medium italic">No upcoming events are scheduled at this moment. Check back soon!</p>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-4">
                <h2 class="text-2xl font-extrabold text-kmf-blue">Past Events</h2>
                <div class="hidden sm:block h-1 flex-1 bg-gray-50 mx-6 rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 font-medium">
                <?php foreach ($past as $e): ?>
                <article class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs font-bold text-gray-400"><?php echo formatDate($e['event_date']); ?></p>
                    </div>
                    <h3 class="text-lg font-extrabold text-kmf-blue mb-2"><?php echo escape($e['title']); ?></h3>
                    <?php if (!empty($e['excerpt'])): ?>
                        <p class="text-gray-500 text-sm leading-relaxed line-clamp-2"><?php echo escape($e['excerpt']); ?></p>
                    <?php endif; ?>
                </article>
                <?php endforeach; ?>
            </div>
            <?php if (empty($past)): ?>
                <p class="text-gray-400 italic">Our event history will appear here.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
