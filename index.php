<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Home';
$metaDescription = getSetting('site_tagline');

// Fetch strategic areas for "What We Do" preview
$pdo = getDb();
$areas = $pdo->query("SELECT id, title, slug, excerpt FROM strategic_areas WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 6")->fetchAll();
$programs = $pdo->query("SELECT id, title, slug, excerpt, image_url FROM programs WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 3")->fetchAll();
$publications = $pdo->query("SELECT id, title, slug, excerpt, image_url, published_at FROM publications WHERE is_active = 1 ORDER BY published_at DESC LIMIT 3")->fetchAll();
$latestNews = $pdo->query("SELECT id, title, slug, excerpt, published_at FROM news WHERE is_active = 1 ORDER BY published_at DESC LIMIT 3")->fetchAll();
$upcomingEvent = $pdo->query("SELECT id, title, slug, event_date, end_date, venue FROM events WHERE is_active = 1 AND type = 'upcoming' AND event_date >= CURDATE() ORDER BY event_date ASC LIMIT 1")->fetch();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero -->
<section class="bg-kmf-blue text-white py-12 md:py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div class="text-center md:text-left">
                <p class="text-kmf-orange-light font-medium uppercase tracking-wider text-sm mb-4">About Us</p>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6"><?php echo escape(getSetting('site_name')); ?></h1>
                <p class="text-xl md:text-2xl text-gray-200 max-w-3xl md:max-w-none mx-auto md:mx-0 mb-6"><?php echo escape(getSetting('site_tagline')); ?></p>
                <p class="text-gray-300 max-w-2xl md:max-w-none mx-auto md:mx-0 mb-8"><?php echo nl2br(escape(getSetting('mission'))); ?></p>
                <a href="<?php echo BASE_URL; ?>about.php" class="inline-block bg-kmf-orange hover:bg-kmf-orange-light text-white font-semibold px-8 py-3 rounded-lg transition">More About Us</a>
            </div>
            <div class="hidden md:block relative h-72 lg:h-96 w-full overflow-hidden rounded-2xl shadow-xl border-4 border-white/40 group">
                <div id="hero-slider" class="relative h-full w-full">
                    <img src="<?php echo BASE_URL; ?>assets/images/hero-theme.png" alt="Education, community, and health themes of KMF" class="hero-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-100">
                    <img src="<?php echo BASE_URL; ?>assets/images/hero-education.png" alt="Our impact in education" class="hero-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0">
                    <img src="<?php echo BASE_URL; ?>assets/images/hero-health.png" alt="Our medical initiatives" class="hero-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0">
                    <img src="<?php echo BASE_URL; ?>assets/images/hero-community.png" alt="Community development and unity" class="hero-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0">
                </div>
                <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-2 z-10">
                    <button class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white transition-colors" data-index="0"></button>
                    <button class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white transition-colors" data-index="1"></button>
                    <button class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white transition-colors" data-index="2"></button>
                    <button class="slider-dot w-2.5 h-2.5 rounded-full bg-white/40 hover:bg-white transition-colors" data-index="3"></button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');
    let currentSlide = 0;
    const slideCount = slides.length;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle('opacity-100', i === index);
            slide.classList.toggle('opacity-0', i !== index);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-white', i === index);
            dot.classList.toggle('bg-white/40', i !== index);
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slideCount;
        showSlide(currentSlide);
    }

    let slideInterval = setInterval(nextSlide, 5000);

    dots.forEach((dot, i) => {
        dot.addEventListener('click', () => {
            clearInterval(slideInterval);
            currentSlide = i;
            showSlide(currentSlide);
            slideInterval = setInterval(nextSlide, 5000);
        });
    });

    // Initialize dots
    showSlide(0);
});
</script>

<!-- Mission, Vision, Goal -->
<section class="py-10 md:py-16 bg-white relative overflow-hidden">
    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-kmf-blue/5 rounded-full blur-3xl"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 card-hover transition-all duration-300">
                <div class="w-14 h-14 bg-kmf-blue rounded-xl flex items-center justify-center text-white mb-6 shadow-lg shadow-kmf-blue/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="text-kmf-blue font-bold text-xl mb-4">Mission</h3>
                <p class="text-gray-600 leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('mission'))); ?></p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 card-hover transition-all duration-300">
                <div class="w-14 h-14 bg-kmf-orange rounded-xl flex items-center justify-center text-white mb-6 shadow-lg shadow-kmf-orange/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h3 class="text-kmf-blue font-bold text-xl mb-4">Vision</h3>
                <p class="text-gray-600 leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('vision'))); ?></p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100 card-hover transition-all duration-300">
                <div class="w-14 h-14 bg-kmf-green rounded-xl flex items-center justify-center text-white mb-6 shadow-lg shadow-kmf-green/20">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-kmf-blue font-bold text-xl mb-4">Goal</h3>
                <p class="text-gray-600 leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('goal'))); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- What We Do - Strategic Areas -->
<section class="py-10 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">What We Do</p>
        <h2 class="text-2xl md:text-3xl font-bold text-kmf-blue mb-10">Strategic Areas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($areas as $a): 
                $icon = strtolower($a['icon'] ?: $a['slug']);
                $colorClass = 'bg-kmf-blue';
                $accentClass = 'shadow-kmf-blue/20';
                $bgClass = 'bg-blue-50';
                
                if (str_contains($icon, 'education') || str_contains($icon, 'scholar')) {
                    $colorClass = 'bg-kmf-blue';
                    $accentClass = 'shadow-kmf-blue/20';
                    $bgClass = 'bg-blue-50';
                } elseif (str_contains($icon, 'people') || str_contains($icon, 'community') || str_contains($icon, 'women')) {
                    $colorClass = 'bg-kmf-orange';
                    $accentClass = 'shadow-kmf-orange/20';
                    $bgClass = 'bg-orange-50';
                } elseif (str_contains($icon, 'health') || str_contains($icon, 'medical')) {
                    $colorClass = 'bg-kmf-green';
                    $accentClass = 'shadow-kmf-green/20';
                    $bgClass = 'bg-green-50';
                }
            ?>
            <a href="<?php echo BASE_URL; ?>what-we-do.php#<?php echo escape($a['slug']); ?>" class="block bg-white rounded-2xl p-8 shadow-sm card-hover transition-all duration-300 border border-gray-100 group">
                <div class="w-16 h-16 rounded-xl <?php echo $bgClass; ?> flex items-center justify-center mb-6 group-hover:<?php echo $colorClass; ?> group-hover:text-white transition-all duration-300 shadow-inner group-hover:shadow-lg group-hover:<?php echo $accentClass; ?>">
                    <div class="text-kmf-blue group-hover:text-white transition-colors">
                        <?php if (str_contains($icon, 'education') || str_contains($icon, 'scholar')): ?>
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 10v6M2 10v6"/></svg>
                        <?php elseif (str_contains($icon, 'people') || str_contains($icon, 'community') || str_contains($icon, 'women')): ?>
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <?php elseif (str_contains($icon, 'health') || str_contains($icon, 'medical')): ?>
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10h6M12 7v6"/></svg>
                        <?php else: ?>
                            <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <?php endif; ?>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-kmf-blue mb-3 group-hover:text-kmf-blue-light transition-colors"><?php echo escape($a['title']); ?></h3>
                <p class="text-gray-600 leading-relaxed group-hover:text-gray-700 transition-colors"><?php echo escape($a['excerpt']); ?></p>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="mt-10 text-center">
            <a href="<?php echo BASE_URL; ?>what-we-do.php" class="text-kmf-orange font-semibold hover:underline">View all strategic areas &rarr;</a>
        </div>
    </div>
</section>

<!-- Publications -->
<section class="py-10 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">Resources</p>
        <h2 class="text-2xl md:text-3xl font-bold text-kmf-blue mb-10">Publications</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($publications as $pub): ?>
            <a href="<?php echo BASE_URL; ?>resources.php#pub-<?php echo (int)$pub['id']; ?>" class="block bg-gray-50 rounded-xl overflow-hidden border border-gray-100 card-hover transition">
                <?php if (!empty($pub['image_url'])): ?>
                    <img src="<?php echo BASE_URL . escape($pub['image_url']); ?>" alt="" class="w-full h-40 object-cover">
                <?php else: ?>
                    <div class="w-full h-40 bg-kmf-green-light flex items-center justify-center text-kmf-blue">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                <?php endif; ?>
                <div class="p-4">
                    <h3 class="font-semibold text-kmf-blue mb-1"><?php echo escape($pub['title']); ?></h3>
                    <?php if ($pub['published_at']): ?>
                        <p class="text-sm text-gray-500"><?php echo formatDate($pub['published_at']); ?></p>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="mt-10 text-center">
            <a href="<?php echo BASE_URL; ?>resources.php" class="text-kmf-orange font-semibold hover:underline">View all Publications &rarr;</a>
        </div>
    </div>
</section>

<!-- Events + News -->
<section class="py-10 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">Events</p>
                <h2 class="text-2xl md:text-3xl font-bold text-kmf-blue mb-6">Upcoming Event</h2>
                <?php if ($upcomingEvent): ?>
                    <a href="<?php echo BASE_URL; ?>events.php" class="block bg-white rounded-xl p-6 border border-gray-100 card-hover">
                        <p class="text-sm text-gray-500 mb-1"><?php echo formatDate($upcomingEvent['event_date']); ?><?php if (!empty($upcomingEvent['end_date'])) echo ' – ' . formatDate($upcomingEvent['end_date']); ?></p>
                        <h3 class="text-lg font-semibold text-kmf-blue mb-2"><?php echo escape($upcomingEvent['title']); ?></h3>
                        <?php if (!empty($upcomingEvent['venue'])): ?>
                            <p class="text-gray-600 text-sm"><?php echo escape($upcomingEvent['venue']); ?></p>
                        <?php endif; ?>
                    </a>
                <?php else: ?>
                    <p class="text-gray-600">No upcoming events at the moment.</p>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>events.php" class="inline-block mt-4 text-kmf-orange font-semibold hover:underline">View all events &rarr;</a>
            </div>
            <div>
                <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">News</p>
                <h2 class="text-2xl md:text-3xl font-bold text-kmf-blue mb-6">Latest News</h2>
                <ul class="space-y-4">
                    <?php foreach ($latestNews as $n): ?>
                    <li>
                        <a href="<?php echo BASE_URL; ?>news.php#news-<?php echo (int)$n['id']; ?>" class="block bg-white rounded-lg p-4 border border-gray-100 card-hover">
                            <h3 class="font-semibold text-kmf-blue mb-1"><?php echo escape($n['title']); ?></h3>
                            <p class="text-sm text-gray-500"><?php echo formatDate($n['published_at'], 'd M, Y'); ?></p>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php if (empty($latestNews)): ?>
                    <p class="text-gray-600">No news yet.</p>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>news.php" class="inline-block mt-4 text-kmf-orange font-semibold hover:underline">View all news &rarr;</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
