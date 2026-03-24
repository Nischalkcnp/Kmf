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
$impactStats = $pdo->query("SELECT title, stat_value, icon FROM impact_stats WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();
$team = $pdo->query("SELECT * FROM team WHERE is_active = 1 ORDER BY type, sort_order")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="relative min-h-[95vh] flex items-center overflow-hidden">
    <!-- Background Slider -->
    <div id="full-hero-slider" class="absolute inset-0 z-0">
        <?php 
        $heroImages = [
            getSetting('hero_image_1') ?: 'assets/images/hero-theme.png',
            getSetting('hero_image_2') ?: 'assets/images/hero-health.png',
            getSetting('hero_image_3') ?: 'assets/images/hero-education.png',
            getSetting('hero_image_4') ?: 'assets/images/hero-community.png'
        ];
        foreach ($heroImages as $index => $img):
        ?>
        <div class="hero-bg-slide absolute inset-0 transition-opacity duration-1500 <?php echo $index === 0 ? 'opacity-100' : 'opacity-0'; ?> bg-cover bg-center" style="background-image: url('<?php echo BASE_URL . $img; ?>');"></div>
        <?php endforeach; ?>
    </div>
    
    <!-- Grain/Texture Overlay for Premium Feel -->
    <div class="absolute inset-0 z-2 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/asfalt-light.png')]"></div>
    <!-- Simple dark overlay to ensure text readability without blurring the image -->
    <div class="absolute inset-0 z-1 bg-black/30"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10 py-12 md:py-20">
        <div class="max-w-4xl">
            <div class="inline-flex items-center gap-3 px-6 py-2 bg-[#F5E6E0] rounded-full mb-8 transform hover:scale-105 transition-all cursor-default group">
                <span class="text-[10px] font-bold text-[#D97706] uppercase tracking-widest"><?php echo escape(getSetting('hero_badge') ?: 'Empowering Nepal since 2024'); ?></span>
            </div>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-10 leading-[0.9] tracking-tighter drop-shadow-xl">
                <?php echo getSetting('hero_title') ?: 'Every Life<br><span class="text-kmf-orange">Deserves</span> a<br>Future.'; ?>
            </h1>
            <p class="text-lg md:text-xl text-white mb-12 max-w-2xl font-medium leading-relaxed opacity-90 drop-shadow-md">
                <?php echo escape(getSetting('hero_subtitle') ?: 'Education, Community & Health. We are a community-driven foundation dedicated to health, education, and sustainable development.'); ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-6">
                <a href="<?php echo BASE_URL; ?>about.php" class="relative overflow-hidden group bg-kmf-blue text-white font-extrabold px-10 py-5 rounded-2xl shadow-xl shadow-kmf-blue/20 flex items-center justify-center">
                    <span class="relative z-10">Our Legacy</span>
                    <div class="absolute inset-0 bg-kmf-orange transform translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                </a>
                <a href="<?php echo BASE_URL; ?>donate.php" class="inline-flex items-center justify-center px-10 py-5 bg-white border-2 border-slate-100 text-kmf-blue font-extrabold rounded-2xl hover:border-kmf-orange hover:text-kmf-orange transition-all group">
                    Donate Now
                    <svg class="w-6 h-6 ml-3 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>

            <!-- Floating Impact Widgets -->
            <div class="mt-16 flex flex-wrap gap-6 items-center">
                <div class="group flex items-center gap-5 p-5 bg-white/40 backdrop-blur-xl rounded-[2rem] border border-white/50 w-fit hover:bg-white/60 transition-all duration-500 hover:-translate-y-2">
                    <div class="w-16 h-16 bg-kmf-orange rounded-2xl flex items-center justify-center text-white shadow-xl shadow-kmf-orange/20 group-hover:rotate-12 transition-transform">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-kmf-blue tracking-tighter">10,000+</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest pl-1">Lives Impacted</div>
                    </div>
                </div>

                <div class="group flex items-center gap-5 p-5 bg-white/40 backdrop-blur-xl rounded-[2rem] border border-white/50 w-fit hover:bg-white/60 transition-all duration-500 hover:-translate-y-2 delay-75">
                    <div class="w-16 h-16 bg-kmf-blue rounded-2xl flex items-center justify-center text-white shadow-xl shadow-kmf-blue/20 group-hover:-rotate-12 transition-transform">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg>
                    </div>
                    <div>
                        <div class="text-3xl font-black text-kmf-blue tracking-tighter">50+</div>
                        <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest pl-1">Schools Rooted</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Hero Background Slider
    document.addEventListener('DOMContentLoaded', function() {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-bg-slide');
        
        function nextSlide() {
            if (slides.length <= 1) return;
            slides[currentSlide].style.opacity = '0';
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].style.opacity = '1';
        }
        
        if (slides.length > 1) {
            setInterval(nextSlide, 4000);
        }
    });
</script>

<?php if (getSetting('president_enabled') == '1'): ?>
<!-- Message from our President -->
<section class="py-20 lg:py-32 bg-white relative overflow-hidden">
    <!-- Background Accents -->
    <div class="absolute top-0 right-0 w-1/3 h-full bg-kmf-blue/[0.03] rounded-l-[100px] pointer-events-none transform translate-x-10"></div>
    <div class="absolute bottom-10 left-10 w-32 h-32 bg-kmf-orange/[0.05] rounded-full blur-3xl pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20">
            <!-- Image Side -->
            <div class="w-full lg:w-5/12">
                <div class="relative max-w-md mx-auto lg:max-w-none">
                    <!-- Accent shapes behind image -->
                    <div class="absolute -top-6 -left-6 w-32 h-32 bg-kmf-orange/10 rounded-full blur-xl pointer-events-none"></div>
                    <div class="absolute -bottom-6 -right-6 w-40 h-40 bg-kmf-green-light/20 rounded-full blur-xl pointer-events-none"></div>
                    
                    <div class="relative bg-white p-4 rounded-[2.5rem] shadow-2xl border border-gray-100 transform -rotate-2 hover:rotate-0 transition-all duration-500">
                        <?php 
                        $presImg = getSetting('president_image_url') ?: 'assets/images/team-placeholder.jpg';
                        ?>
                        <div class="relative aspect-[4/5] rounded-[2rem] overflow-hidden">
                            <img src="<?php echo BASE_URL . escape($presImg); ?>" alt="<?php echo escape(getSetting('president_name')); ?>" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-kmf-blue/60 to-transparent"></div>
                            
                            <!-- Name Badge overlay -->
                            <div class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur-md p-4 rounded-2xl shadow-lg border border-white">
                                <h3 class="font-extrabold text-xl text-kmf-blue mb-1"><?php echo escape(getSetting('president_name') ?: 'Dr. Ram Bahadur Tamang'); ?></h3>
                                <p class="text-xs font-bold text-kmf-orange uppercase tracking-widest"><?php echo escape(getSetting('president_role') ?: 'Chairperson (President)'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Side -->
            <div class="w-full lg:w-7/12">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-3 px-6 py-2 bg-kmf-blue/5 rounded-full mb-8">
                        <span class="text-xs font-bold text-kmf-blue uppercase tracking-widest">Leadership Message</span>
                    </div>
                    
                    <h2 class="text-4xl lg:text-5xl font-extrabold text-kmf-blue font-montserrat tracking-tight leading-tight mb-8">
                        Message From Our <br><span class="text-kmf-orange">President</span>
                    </h2>

                    <!-- Quote Icon -->
                    <div class="text-kmf-green-light/20 mb-6">
                        <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    </div>

                    <div class="text-lg text-slate-600 leading-relaxed font-medium space-y-6">
                        <?php 
                        $presMessage = getSetting('president_message') ?: 'Welcome to the Kanchhi Maya Tamang Foundation. Our journey began with a simple yet profound vision: to empower communities through education and healthcare. Every initiative we undertake is driven by the belief that every life deserves a future. I invite you to explore our work and join hands with us to create a lasting impact across Nepal.';
                        echo nl2br(escape($presMessage)); 
                        ?>
                    </div>

                    <div class="mt-10 flex items-center gap-4">
                        <div class="w-16 h-1 bg-kmf-orange rounded-full"></div>
                        <p class="font-bold text-sm text-kmf-blue uppercase tracking-widest">Sincerely</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Impact Stats & Mission -->
<section id="impact" class="py-20 lg:py-32 bg-white relative overflow-hidden">
    <!-- Abstract Decorations -->
    <div class="absolute top-0 right-0 -tr-x-1/4 -tr-y-1/4 w-[600px] h-[600px] bg-kmf-blue/[0.02] rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 tr-x-1/4 tr-y-1/4 w-[600px] h-[600px] bg-kmf-orange/[0.02] rounded-full blur-[120px] pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <!-- Impact Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-10 mb-24 md:mb-32">
            <?php foreach ($impactStats as $stat): ?>
            <div class="bg-gray-50/50 backdrop-blur-sm p-8 rounded-3xl border border-gray-100 text-center hover:bg-white hover:shadow-2xl hover:shadow-kmf-blue/10 hover:-translate-y-2 transition-all duration-500 group">
                <div class="w-20 h-20 mx-auto bg-kmf-blue/5 rounded-2xl flex items-center justify-center text-kmf-blue mb-6 group-hover:bg-kmf-orange group-hover:text-white transition-all duration-500 shadow-inner">
                    <?php 
                        $iconClass = strtolower($stat['icon'] ?? '');
                    ?>
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <?php if (str_contains($iconClass, 'user-group')): ?>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 00-3-3.87m4-8.13a4 4 0 110 8 4 4 0 010-8zM9 21v-2a4 4 0 00-3-3.87m4-8.13a4 4 0 110 8 4 4 0 010-8zM12 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2m8-14a4 4 0 110-8 4 4 0 010 8z" />
                        <?php elseif (str_contains($iconClass, 'user') || str_contains($iconClass, 'beneficiary')): ?>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        <?php elseif (str_contains($iconClass, 'academic') || str_contains($iconClass, 'school')): ?>
                            <path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 10v6M2 10v6"/>
                        <?php elseif (str_contains($iconClass, 'heart') || str_contains($iconClass, 'health')): ?>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        <?php else: ?>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        <?php endif; ?>
                    </svg>
                </div>
                <h3 class="text-4xl font-extrabold text-kmf-blue mb-2 font-montserrat counter-stat" 
                    data-target="<?php echo (int)str_replace(',', '', $stat['stat_value']); ?>"
                    <?php 
                        $suffix = preg_replace('/[0-9,]/', '', $stat['stat_value']);
                        if ($suffix): 
                    ?>
                    data-suffix="<?php echo escape($suffix); ?>"
                    <?php endif; ?>>
                    0
                </h3>
                <p class="text-gray-500 font-bold uppercase tracking-widest text-[10px]"><?php echo escape($stat['title']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Script for Counter Animation -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const counters = document.querySelectorAll('.counter-stat');
                const speed = 2000; // Total duration in ms

                const animate = (counter) => {
                    const target = parseInt(counter.getAttribute('data-target'));
                    const suffix = counter.getAttribute('data-suffix') || '';
                    const startTime = performance.now();

                    const updateCount = (currentTime) => {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / speed, 1);
                        
                        // Easing function for smooth finish
                        const easeOutQuad = (t) => t * (2 - t);
                        const current = Math.floor(easeOutQuad(progress) * target);

                        counter.innerText = current.toLocaleString() + suffix;

                        if (progress < 1) {
                            requestAnimationFrame(updateCount);
                        } else {
                            counter.innerText = target.toLocaleString() + suffix;
                        }
                    };

                    requestAnimationFrame(updateCount);
                };

                const observerOptions = {
                    threshold: 0.5
                };

                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animate(entry.target);
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                counters.forEach(counter => observer.observe(counter));
            });
        </script>

        <!-- Mission/Vision/Goal Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            <!-- Mission -->
            <div class="relative group">
                <div class="absolute inset-0 bg-kmf-blue rounded-[2.5rem] rotate-3 scale-95 opacity-5 group-hover:rotate-6 group-hover:scale-100 transition-all duration-500"></div>
                <div class="relative bg-white p-10 lg:p-12 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 h-full">
                    <div class="w-16 h-16 bg-kmf-blue rounded-2xl flex items-center justify-center text-white mb-8 shadow-xl shadow-kmf-blue/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-kmf-blue mb-6 font-montserrat uppercase tracking-tight">Our Mission</h3>
                    <p class="text-gray-600 leading-relaxed text-lg font-medium opacity-90"><?php echo nl2br(escape(getSetting('mission'))); ?></p>
                </div>
            </div>

            <!-- Vision -->
            <div class="relative group">
                <div class="absolute inset-0 bg-kmf-orange rounded-[2.5rem] -rotate-3 scale-95 opacity-5 group-hover:-rotate-6 group-hover:scale-100 transition-all duration-500"></div>
                <div class="relative bg-white p-10 lg:p-12 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 h-full">
                    <div class="w-16 h-16 bg-kmf-orange rounded-2xl flex items-center justify-center text-white mb-8 shadow-xl shadow-kmf-orange/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-kmf-blue mb-6 font-montserrat uppercase tracking-tight">Our Vision</h3>
                    <p class="text-gray-600 leading-relaxed text-lg font-medium opacity-90"><?php echo nl2br(escape(getSetting('vision'))); ?></p>
                </div>
            </div>

            <!-- Goal -->
            <div class="relative group">
                <div class="absolute inset-0 bg-kmf-green rounded-[2.5rem] rotate-3 scale-95 opacity-5 group-hover:rotate-6 group-hover:scale-100 transition-all duration-500"></div>
                <div class="relative bg-white p-10 lg:p-12 rounded-[2.5rem] border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-500 h-full">
                    <div class="w-16 h-16 bg-kmf-green rounded-2xl flex items-center justify-center text-white mb-8 shadow-xl shadow-kmf-green/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-kmf-blue mb-6 font-montserrat uppercase tracking-tight">Our Goal</h3>
                    <p class="text-gray-600 leading-relaxed text-lg font-medium opacity-90"><?php echo nl2br(escape(getSetting('goal'))); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Meet Our Team -->
<section class="py-24 lg:py-32 bg-gray-50/50">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 lg:mb-20">
            <div class="max-w-2xl">
                <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-xs mb-4 block">Leadership</span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-kmf-blue font-montserrat tracking-tight leading-tight">Meet Our Team</h2>
            </div>
            <div class="mt-6 md:mt-0">
                <a href="<?php echo BASE_URL; ?>view.php?slug=team" class="inline-flex items-center text-kmf-blue hover:text-kmf-orange font-bold text-lg transition-colors group">
                    View All Members
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($team as $t): ?>
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group hover:-translate-y-2">
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
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Strategic Areas (What We Do) -->
<section class="py-24 lg:py-32 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 lg:mb-20">
            <div class="max-w-2xl">
                <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-xs mb-4 block">How we help</span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-kmf-blue font-montserrat tracking-tight leading-tight">Strategic Areas of Focus</h2>
            </div>
            <div class="mt-6 md:mt-0">
                <a href="<?php echo BASE_URL; ?>what-we-do.php" class="inline-flex items-center text-kmf-blue hover:text-kmf-orange font-bold text-lg transition-colors group">
                    Explore Our Impact
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            <?php foreach ($areas as $a): 
                $icon = strtolower($a['icon'] ?: $a['slug']);
                $themeColor = 'kmf-blue';
                if (str_contains($icon, 'education') || str_contains($icon, 'scholar')) $themeColor = 'kmf-blue';
                elseif (str_contains($icon, 'people') || str_contains($icon, 'community')) $themeColor = 'kmf-orange';
                elseif (str_contains($icon, 'health') || str_contains($icon, 'medical')) $themeColor = 'kmf-green';
            ?>
            <div class="group relative bg-white rounded-[2rem] p-10 shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 flex flex-col h-full">
                <!-- Hover Background Accent -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-<?php echo $themeColor; ?>/5 rounded-bl-full transform translate-x-8 -translate-y-8 group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-500 pointer-events-none"></div>
                
                <div class="w-20 h-20 rounded-3xl bg-gray-50 flex items-center justify-center text-<?php echo $themeColor; ?> mb-10 group-hover:scale-110 transition-transform duration-500 shadow-inner group-hover:shadow-[0_10px_25px_rgba(0,0,0,0.05)]">
                    <?php if (str_contains($icon, 'education')): ?>
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 10v6M2 10v6"/></svg>
                    <?php elseif (str_contains($icon, 'people') || str_contains($icon, 'community')): ?>
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <?php else: ?>
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <?php endif; ?>
                </div>
                
                <h3 class="text-2xl font-extrabold text-kmf-blue mb-5 tracking-tight"><?php echo escape($a['title']); ?></h3>
                <p class="text-gray-500 leading-relaxed font-medium mb-10 flex-grow">
                    <?php echo escape($a['excerpt']); ?>
                </p>
                
                <a href="<?php echo BASE_URL; ?>what-we-do.php#<?php echo escape($a['slug']); ?>" class="inline-flex items-center text-sm font-bold uppercase tracking-widest text-<?php echo $themeColor; ?> group-hover:text-kmf-orange transition-colors">
                    Learn More
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest Publications -->
<section class="py-24 lg:py-32 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 lg:mb-20">
            <div class="max-w-2xl text-center md:text-left">
                <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-xs mb-4 block">Knowledge Center</span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-kmf-blue font-montserrat tracking-tight leading-tight">Latest Publications</h2>
            </div>
            <div class="mt-6 md:mt-0 text-center md:text-left">
                <a href="<?php echo BASE_URL; ?>resources.php" class="inline-flex items-center text-kmf-blue hover:text-kmf-orange font-bold text-lg transition-colors group">
                    View All Resources
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            <?php foreach ($publications as $pub): ?>
            <a href="<?php echo BASE_URL; ?>resources.php#pub-<?php echo (int)$pub['id']; ?>" class="group flex flex-col bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500">
                <div class="relative aspect-[16/10] overflow-hidden">
                    <?php if (!empty($pub['image_url'])): ?>
                        <img src="<?php echo BASE_URL . escape($pub['image_url']); ?>" alt="<?php echo escape($pub['title']); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <?php else: ?>
                        <div class="w-full h-full bg-kmf-blue/5 flex items-center justify-center text-kmf-blue">
                            <svg class="w-16 h-16 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                    <?php endif; ?>
                    <div class="absolute inset-0 bg-gradient-to-t from-kmf-blue/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-6">
                        <span class="text-white font-bold text-sm uppercase tracking-widest">Read More &rarr;</span>
                    </div>
                </div>
                <div class="p-8 flex flex-col flex-grow">
                    <span class="text-xs font-bold text-kmf-orange uppercase tracking-widest mb-3"><?php echo escape($pub['type'] ?? 'Publication'); ?></span>
                    <h3 class="text-xl font-extrabold text-kmf-blue mb-4 leading-tight group-hover:text-kmf-orange transition-colors"><?php echo escape($pub['title']); ?></h3>
                    <p class="text-gray-500 text-sm font-medium mb-6 line-clamp-2">
                        <?php echo escape($pub['excerpt']); ?>
                    </p>
                    <div class="mt-auto pt-6 border-t border-gray-50 flex items-center justify-between text-gray-400">
                        <span class="text-xs font-bold"><?php echo $pub['published_at'] ? formatDate($pub['published_at']) : 'Recently Published'; ?></span>
                        <svg class="w-5 h-5 text-kmf-blue opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Events & News Section -->
<section class="py-24 lg:py-32 bg-white text-kmf-blue relative overflow-hidden border-t border-slate-50">
    <!-- Background Decor -->
    <div class="absolute top-0 left-0 w-full h-full opacity-30 pointer-events-none">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-kmf-blue/5 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-kmf-orange/5 rounded-full blur-[120px]"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24">
            <!-- Events -->
            <div class="lg:col-span-5">
                <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-xs mb-4 block">Be Part of It</span>
                <h2 class="text-3xl md:text-5xl font-extrabold mb-10 font-montserrat tracking-tight leading-tight text-kmf-blue">Upcoming Events</h2>
                
                <?php if ($upcomingEvent): ?>
                <div class="group relative">
                    <div class="absolute -inset-1 bg-gradient-to-r from-kmf-blue/10 to-kmf-orange/10 rounded-3xl blur opacity-20 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative bg-slate-50 border border-slate-100 p-8 md:p-10 rounded-3xl hover:bg-white hover:shadow-2xl hover:shadow-kmf-blue/5 transition-all duration-500">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="px-5 py-2 rounded-xl bg-kmf-orange text-white text-center shadow-lg shadow-kmf-orange/20">
                                <span class="block text-2xl font-black leading-none"><?php echo date('d', strtotime($upcomingEvent['event_date'])); ?></span>
                                <span class="text-[10px] font-bold uppercase tracking-widest"><?php echo date('M', strtotime($upcomingEvent['event_date'])); ?></span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-kmf-blue group-hover:text-kmf-orange transition-colors"><?php echo escape($upcomingEvent['title']); ?></h3>
                                <p class="text-slate-500 text-sm font-medium mt-1 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <?php echo escape($upcomingEvent['venue']); ?>
                                </p>
                            </div>
                        </div>
                        <p class="text-slate-600 leading-relaxed mb-8">
                            Join us in our mission to create lasting impact. Your participation makes a world of difference.
                        </p>
                        <a href="<?php echo BASE_URL; ?>events.php" class="inline-flex items-center text-kmf-orange font-bold tracking-widest text-xs uppercase group/btn">
                            Event Details 
                            <svg class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <div class="p-10 rounded-3xl border border-slate-100 bg-slate-50 text-slate-400 font-medium italic">
                    Stay tuned for our upcoming initiatives.
                </div>
                <?php endif; ?>
                
                <div class="mt-10">
                    <a href="<?php echo BASE_URL; ?>events.php" class="text-slate-400 hover:text-kmf-blue font-bold flex items-center gap-2 transition-colors">
                        View Past Events <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- News -->
            <div class="lg:col-span-7">
                <div class="flex items-end justify-between mb-10">
                    <div>
                        <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-xs mb-4 block">Stay Updated</span>
                        <h2 class="text-3xl md:text-5xl font-extrabold font-montserrat tracking-tight text-kmf-blue">Latest News</h2>
                    </div>
                    <a href="<?php echo BASE_URL; ?>news.php" class="hidden md:inline-flex items-center text-slate-400 hover:text-kmf-blue font-bold transition-colors">
                        All Stories <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>

                <div class="space-y-6">
                    <?php foreach ($latestNews as $n): ?>
                    <a href="<?php echo BASE_URL; ?>news.php#news-<?php echo (int)$n['id']; ?>" class="group flex items-center gap-6 p-6 md:p-8 rounded-3xl bg-slate-50 hover:bg-white hover:shadow-xl hover:shadow-kmf-blue/5 border border-slate-100 transition-all duration-300">
                        <div class="hidden sm:flex flex-shrink-0 w-20 h-20 rounded-2xl bg-kmf-blue/5 items-center justify-center text-kmf-blue group-hover:bg-kmf-blue group-hover:text-white transition-all duration-300">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 2v6h6m-3 5H7m10 3H7m10 3H7"/></svg>
                        </div>
                        <div class="flex-grow">
                            <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-kmf-orange"><?php echo formatDate($n['published_at'], 'd M, Y'); ?></span>
                            <h3 class="text-xl md:text-2xl font-bold mt-2 text-kmf-blue group-hover:text-kmf-orange transition-colors leading-tight"><?php echo escape($n['title']); ?></h3>
                            <p class="text-slate-500 text-sm mt-3 line-clamp-1"><?php echo escape($n['excerpt']); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center group-hover:bg-kmf-orange group-hover:border-kmf-orange group-hover:text-white transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>

                <a href="<?php echo BASE_URL; ?>news.php" class="md:hidden mt-8 inline-flex items-center text-kmf-orange font-bold transition-colors">
                    Read All Stories <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<?php 
$partners = $pdo->query("SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order")->fetchAll();
if (!empty($partners)): 
?>
<!-- Partners Marquee -->
<section class="py-16 md:py-24 bg-white border-t border-gray-50 overflow-hidden">
    <div class="container mx-auto px-4 mb-12 text-center">
        <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-[10px] mb-3 block">Collaborations</span>
        <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight">Trusted by Our Partners</h2>
    </div>
    <div class="marquee-container">
        <div class="marquee-content gap-16 md:gap-32 items-center py-4">
            <?php 
            // Repeat partners multiple times to ensure the marquee spans the width and loops smoothly
            $displayPartners = array_merge($partners, $partners, $partners, $partners);
            foreach ($displayPartners as $p): 
            ?>
            <div class="flex-shrink-0 grayscale hover:grayscale-0 transition-all duration-500 opacity-50 hover:opacity-100 flex items-center justify-center min-w-[150px]">
                <?php if (!empty($p['logo_url'])): ?>
                    <img src="<?php echo (str_starts_with($p['logo_url'], 'http') ? '' : BASE_URL) . escape($p['logo_url']); ?>" alt="<?php echo escape($p['name']); ?>" class="h-10 md:h-14 w-auto object-contain transform group-hover:scale-110 transition-transform">
                <?php else: ?>
                    <span class="text-slate-400 font-bold text-lg whitespace-nowrap"><?php echo escape($p['name']); ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
