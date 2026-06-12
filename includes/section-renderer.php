<?php
/**
 * Render a specific homepage section based on its key
 */
function renderSection(array $section, array $data = []) {
    if (!$section['is_active']) return;

    $key = $section['section_key'];
    $type = $section['section_type'];

    if ($type === 'custom') {
        renderCustomSection($section);
        return;
    }

    // Map system keys to their respective render functions
    switch ($key) {
        case 'hero_slider':
            renderHeroSlider();
            break;
        case 'president_message':
            renderPresidentMessage();
            break;
        case 'impact_mission':
            renderImpactMission($data['impactStats'] ?? []);
            break;
        case 'team_grid':
            renderTeamGrid($data['team'] ?? []);
            break;
        case 'strategic_areas':
            renderStrategicAreas($data['areas'] ?? []);
            break;
        case 'publications_list':
            renderPublicationsList($data['publications'] ?? []);
            break;
        case 'news_events':
            renderNewsEvents($data['latestNews'] ?? [], $data['upcomingEvent'] ?? null);
            break;
        case 'partners_marquee':
            renderPartnersMarquee($data['partners'] ?? []);
            break;
    }
}

function renderCustomSection($section) {
    ?>
    <section class="py-8 lg:py-12 bg-white relative overflow-hidden border-t border-slate-50">
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-14 <?php echo $section['id'] % 2 === 0 ? 'lg:flex-row-reverse' : ''; ?>">
                <?php if (!empty($section['image_url'])): ?>
                    <div class="w-full lg:w-1/2">
                        <div class="relative rounded-[2rem] overflow-hidden shadow-2xl transition-transform hover:scale-[1.02] duration-500">
                            <img src="<?php echo BASE_URL . escape($section['image_url']); ?>" alt="<?php echo escape($section['title']); ?>" class="w-full h-full object-cover">
                        </div>
                    </div>
                <?php endif; ?>
                <div class="w-full <?php echo !empty($section['image_url']) ? 'lg:w-1/2' : 'max-w-4xl mx-auto text-center'; ?>">
                    <?php if (!empty($section['subtitle'])): ?>
                        <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-xs mb-3 block"><?php echo escape($section['subtitle']); ?></span>
                    <?php endif; ?>
                    <h2 class="text-2xl md:text-4xl font-extrabold text-kmf-blue font-montserrat tracking-tight mb-4"><?php echo escape($section['title']); ?></h2>
                    <div class="text-base text-slate-600 leading-relaxed font-medium">
                        <?php echo nl2br($section['content']); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
}

function renderHeroSlider() {
    ?>
    <!-- Hero Section -->
    <section class="relative min-h-[80vh] flex items-center overflow-hidden">
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
            <div class="hero-bg-slide absolute inset-0 bg-cover bg-center <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo BASE_URL . $img; ?>');"></div>
            <?php endforeach; ?>
        </div>
        
        <!-- Grain/Texture Overlay -->
        <div class="absolute inset-0 z-2 opacity-[0.03] pointer-events-none bg-[url('https://www.transparenttextures.com/patterns/asfalt-light.png')]"></div>
        <div class="absolute inset-0 z-1 bg-black/30"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10 py-10 md:py-14">
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-3 px-5 py-1.5 bg-[#F5E6E0] rounded-full mb-5 transform hover:scale-105 transition-all cursor-default group">
                    <span class="text-[10px] font-bold text-[#D97706] uppercase tracking-widest"><?php echo escape(getSetting('hero_badge') ?: 'Empowering Nepal since 2024'); ?></span>
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 md:mb-6 leading-[1.1] tracking-tight drop-shadow-xl">
                    <?php echo getSetting('hero_title') ?: 'Every Life<br><span class="text-kmf-orange">Deserves</span> a<br>Future.'; ?>
                </h1>
                <p class="text-base md:text-lg text-white mb-8 max-w-2xl font-medium leading-relaxed opacity-90 drop-shadow-md">
                    <?php echo escape(getSetting('hero_subtitle') ?: 'Education, Community & Health. We are a community-driven foundation dedicated to health, education, and sustainable development.'); ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?php echo BASE_URL; ?>about.php" class="relative overflow-hidden group bg-kmf-blue text-white font-extrabold px-8 py-4 rounded-2xl shadow-xl shadow-kmf-blue/20 flex items-center justify-center">
                        <span class="relative z-10">Our Legacy</span>
                        <div class="absolute inset-0 bg-kmf-orange transform translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                    </a>
                    <a href="<?php echo BASE_URL; ?>donate.php" class="inline-flex items-center justify-center px-8 py-4 bg-white border-2 border-slate-100 text-kmf-blue font-extrabold rounded-2xl hover:border-kmf-orange hover:text-kmf-orange transition-all group">
                        Donate Now
                        <svg class="w-5 h-5 ml-3 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <!-- Floating Impact Widgets -->
                <div class="mt-10 flex flex-wrap gap-4 items-center">
                    <div class="group flex items-center gap-4 p-4 bg-white/40 backdrop-blur-xl rounded-[2rem] border border-white/50 w-fit hover:bg-white/60 transition-all duration-500 hover:-translate-y-2">
                        <div class="w-12 h-12 bg-kmf-orange rounded-xl flex items-center justify-center text-white shadow-xl shadow-kmf-orange/20 group-hover:rotate-12 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-kmf-blue tracking-tighter">10,000+</div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest pl-1">Lives Impacted</div>
                        </div>
                    </div>

                    <div class="group flex items-center gap-4 p-4 bg-white/40 backdrop-blur-xl rounded-[2rem] border border-white/50 w-fit hover:bg-white/60 transition-all duration-500 hover:-translate-y-2 delay-75">
                        <div class="w-12 h-12 bg-kmf-blue rounded-xl flex items-center justify-center text-white shadow-xl shadow-kmf-blue/20 group-hover:-rotate-12 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/></svg>
                        </div>
                        <div>
                            <div class="text-2xl font-black text-kmf-blue tracking-tighter">50+</div>
                            <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest pl-1">Schools Rooted</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentSlide = 0;
            const slides = document.querySelectorAll('.hero-bg-slide');
            if (slides.length > 1) {
                setInterval(() => {
                    slides[currentSlide].classList.remove('active');
                    currentSlide = (currentSlide + 1) % slides.length;
                    slides[currentSlide].classList.add('active');
                }, 5000);
            }
        });
    </script>
    <?php
}

function renderPresidentMessage() {
    if (getSetting('president_enabled') != '1') return;
    ?>
    <!-- Message from our President -->
    <section class="py-8 lg:py-12 bg-white relative overflow-hidden">
        <!-- Background Accents -->
        <div class="absolute top-0 right-0 w-1/3 h-full bg-kmf-blue/[0.03] rounded-l-[100px] pointer-events-none transform translate-x-10"></div>
        <div class="absolute bottom-10 left-10 w-32 h-32 bg-kmf-orange/[0.05] rounded-full blur-3xl pointer-events-none"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-14">
                <div class="w-full lg:w-5/12">
                    <div class="relative max-w-md mx-auto lg:max-w-none">
                        <!-- Accent shapes -->
                        <div class="absolute -top-6 -left-6 w-32 h-32 bg-kmf-orange/10 rounded-full blur-xl pointer-events-none"></div>
                        
                        <div class="relative bg-white p-3 rounded-[2rem] shadow-2xl border border-gray-100 transform -rotate-2 hover:rotate-0 transition-all duration-500">
                            <?php $presImg = getSetting('president_image_url') ?: 'assets/images/team-placeholder.jpg'; ?>
                            <div class="relative aspect-[4/5] rounded-[1.5rem] overflow-hidden">
                                <img src="<?php echo BASE_URL . escape($presImg); ?>" alt="<?php echo escape(getSetting('president_name')); ?>" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-kmf-blue/60 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4 bg-white/95 backdrop-blur-md p-3 rounded-xl shadow-lg border border-white">
                                    <h3 class="font-extrabold text-lg text-kmf-blue mb-0.5"><?php echo escape(getSetting('president_name') ?: 'Dr. Ram Bahadur Tamang'); ?></h3>
                                    <p class="text-xs font-bold text-kmf-orange uppercase tracking-widest"><?php echo escape(getSetting('president_role') ?: 'Chairperson (President)'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-7/12">
                    <div class="max-w-2xl text-base text-slate-600 leading-relaxed font-medium space-y-4">
                        <div class="inline-flex items-center gap-3 px-5 py-1.5 bg-kmf-blue/5 rounded-full mb-4">
                            <span class="text-xs font-bold text-kmf-blue uppercase tracking-widest">Leadership Message</span>
                        </div>
                        <h2 class="text-3xl lg:text-4xl font-extrabold text-kmf-blue mb-4 font-montserrat tracking-tight leading-tight">Message From Our <br><span class="text-kmf-orange">Chairman</span></h2>
                        <!-- Quote Icon -->
                        <div class="text-kmf-green-light/20 mb-3">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                        </div>
                        <div><?php echo nl2br(escape(getSetting('president_message'))); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
}

function renderImpactMission($impactStats) {
    if (empty($impactStats)) return;
    ?>
    <section class="py-8 lg:py-12 bg-white relative overflow-hidden">
        <!-- Decorations -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-kmf-blue/[0.02] rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-kmf-orange/[0.02] rounded-full blur-[120px] pointer-events-none"></div>

        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-10 md:mb-14">
                <?php foreach ($impactStats as $stat): ?>
                <div class="bg-gray-50/50 p-5 rounded-2xl border border-gray-100 text-center hover:bg-white hover:shadow-xl transition-all duration-500 group">
                    <h3 class="text-3xl font-extrabold text-kmf-blue mb-1 font-montserrat tracking-tighter counter-stat" 
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

            <!-- Counter Animation Script -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const counters = document.querySelectorAll('.counter-stat');
                    const animate = (counter) => {
                        const target = parseInt(counter.getAttribute('data-target'));
                        const suffix = counter.getAttribute('data-suffix') || '';
                        let current = 0;
                        const increment = target / 100;
                        const timer = setInterval(() => {
                            current += increment;
                            if (current >= target) {
                                counter.innerText = target.toLocaleString() + suffix;
                                clearInterval(timer);
                            } else {
                                counter.innerText = Math.floor(current).toLocaleString() + suffix;
                            }
                        }, 20);
                    };
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                animate(entry.target);
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.5 });
                    counters.forEach(c => observer.observe(c));
                });
            </script>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 lg:gap-8">
                <div class="p-6 lg:p-8 rounded-[2rem] border border-gray-100 shadow-sm bg-white h-full border-t-4 border-t-kmf-blue hover:shadow-xl transition-all duration-500">
                    <h3 class="text-xl font-extrabold text-kmf-blue mb-3 font-montserrat uppercase tracking-tight">Our Mission</h3>
                    <p class="text-gray-600 leading-relaxed text-base font-medium opacity-90"><?php echo nl2br(escape(getSetting('mission'))); ?></p>
                </div>
                <div class="p-6 lg:p-8 rounded-[2rem] border border-gray-100 shadow-sm bg-white h-full border-t-4 border-t-kmf-orange hover:shadow-xl transition-all duration-500">
                    <h3 class="text-xl font-extrabold text-kmf-blue mb-3 font-montserrat uppercase tracking-tight">Our Vision</h3>
                    <p class="text-gray-600 leading-relaxed text-base font-medium opacity-90"><?php echo nl2br(escape(getSetting('vision'))); ?></p>
                </div>
                <div class="p-6 lg:p-8 rounded-[2rem] border border-gray-100 shadow-sm bg-white h-full border-t-4 border-t-green-500 hover:shadow-xl transition-all duration-500">
                    <h3 class="text-xl font-extrabold text-kmf-blue mb-3 font-montserrat uppercase tracking-tight">Our Goal</h3>
                    <p class="text-gray-600 leading-relaxed text-base font-medium opacity-90"><?php echo nl2br(escape(getSetting('goal'))); ?></p>
                </div>
            </div>
        </div>
    </section>
    <?php
}

function renderTeamGrid($team) {
    if (empty($team)) return;
    ?>
    <section class="py-8 lg:py-12 bg-gray-50/50">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <h2 class="text-2xl md:text-4xl font-extrabold text-kmf-blue font-montserrat tracking-tight leading-tight">Meet Our Team</h2>
                <a href="<?php echo BASE_URL; ?>about.php" class="font-bold text-kmf-blue hover:text-kmf-orange transition-colors text-sm">View All &rarr;</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 lg:gap-6">
                <?php foreach ($team as $t): ?>
                <div class="bg-white rounded-[1.5rem] p-5 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 group hover:-translate-y-1 text-center">
                    <div class="relative w-20 h-20 mx-auto mb-3 transition-transform duration-300 group-hover:scale-110">
                        <?php if (!empty($t['image_url'])): ?>
                            <img src="<?php echo BASE_URL . escape($t['image_url']); ?>" class="w-full h-full rounded-full object-cover border-2 border-white shadow-md">
                        <?php else: ?>
                            <div class="w-full h-full rounded-full bg-kmf-orange/10 flex items-center justify-center text-kmf-blue font-bold text-2xl border-2 border-white shadow-md"><?php echo strtoupper(substr($t['name'], 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>
                    <h3 class="font-bold text-sm text-kmf-blue mb-0.5 leading-tight"><?php echo escape($t['name']); ?></h3>
                    <p class="text-kmf-orange font-semibold text-[10px] uppercase tracking-widest"><?php echo escape($t['role'] ?? ''); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

function renderStrategicAreas($areas) {
    if (empty($areas)) return;
    ?>
    <section class="py-8 lg:py-12 bg-white">
        <div class="container mx-auto px-4 lg:px-8">
            <h2 class="text-2xl md:text-4xl font-extrabold text-kmf-blue font-montserrat mb-8 tracking-tight leading-tight">Strategic Areas of Focus</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($areas as $a): 
                    $icon = strtolower($a['title']);
                ?>
                <div class="group bg-white rounded-[2rem] p-7 shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 relative overflow-hidden">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-500">
                        <?php if (str_contains($icon, 'education')): ?>
                            <svg class="w-8 h-8 text-kmf-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 10v6M2 10v6"/></svg>
                        <?php elseif (str_contains($icon, 'health')): ?>
                            <svg class="w-8 h-8 text-kmf-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <?php else: ?>
                            <svg class="w-8 h-8 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-xl font-extrabold text-kmf-blue mb-3 tracking-tight"><?php echo escape($a['title']); ?></h3>
                    <p class="text-gray-500 leading-relaxed font-medium mb-5 text-sm"><?php echo escape($a['excerpt']); ?></p>
                    <a href="<?php echo BASE_URL; ?>what-we-do.php#<?php echo escape($a['slug']); ?>" class="font-bold text-kmf-orange hover:text-kmf-blue transition-colors uppercase text-xs tracking-widest inline-flex items-center gap-2">Learn More <span>&rarr;</span></a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

function renderPublicationsList($publications) {
    if (empty($publications)) return;
    ?>
    <section class="py-8 lg:py-12 bg-white border-t border-slate-50">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <h2 class="text-2xl md:text-4xl font-extrabold text-kmf-blue font-montserrat tracking-tight leading-tight">Latest Publications</h2>
                <a href="<?php echo BASE_URL; ?>resources.php" class="font-bold text-kmf-blue hover:text-kmf-orange transition-colors text-sm">View All &rarr;</a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <?php foreach ($publications as $pub): ?>
                <div class="group flex flex-col bg-white rounded-[2rem] overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500">
                    <div class="relative aspect-[16/9] overflow-hidden">
                        <?php if (!empty($pub['image_url'])): ?>
                            <img src="<?php echo BASE_URL . escape($pub['image_url']); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <?php else: ?>
                            <div class="w-full h-full bg-kmf-blue/5 flex items-center justify-center text-kmf-blue opacity-30">No Image</div>
                        <?php endif; ?>
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <span class="text-xs font-bold text-kmf-orange uppercase mb-2 block tracking-widest"><?php echo escape($pub['type'] ?? 'Publication'); ?></span>
                        <h3 class="text-base font-extrabold text-kmf-blue mb-2 leading-tight group-hover:text-kmf-orange transition-colors"><?php echo escape($pub['title']); ?></h3>
                        <p class="text-gray-500 text-xs font-medium mb-4 line-clamp-2"><?php echo escape($pub['excerpt']); ?></p>
                        <a href="<?php echo BASE_URL; ?>resources.php" class="text-kmf-blue font-bold text-xs tracking-widest uppercase mt-auto">Download &rarr;</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}

function renderNewsEvents($latestNews, $upcomingEvent) {
    ?>
    <section class="py-8 lg:py-12 bg-white relative overflow-hidden border-t border-slate-50">
        <div class="container mx-auto px-4 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14">
                <div class="lg:col-span-5">
                    <h2 class="text-2xl md:text-4xl font-extrabold text-kmf-blue font-montserrat mb-6 tracking-tight leading-tight">Upcoming Events</h2>
                    <?php if ($upcomingEvent): ?>
                        <div class="bg-slate-50 border border-slate-100 p-6 rounded-[2rem] hover:bg-white hover:shadow-2xl transition-all duration-500">
                            <div class="flex items-center gap-4 mb-5">
                                <div class="px-4 py-2 rounded-xl bg-kmf-orange text-white text-center shadow-lg shadow-kmf-orange/20 flex-shrink-0">
                                    <span class="block text-xl font-black"><?php echo date('d', strtotime($upcomingEvent['event_date'])); ?></span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest"><?php echo date('M', strtotime($upcomingEvent['event_date'])); ?></span>
                                </div>
                                <h3 class="text-lg font-bold text-kmf-blue"><?php echo escape($upcomingEvent['title']); ?></h3>
                            </div>
                            <p class="text-slate-500 text-xs font-medium mb-5 inline-flex items-center gap-2">
                                <svg class="w-4 h-4 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <?php echo escape($upcomingEvent['venue']); ?>
                            </p>
                            <a href="<?php echo BASE_URL; ?>events.php" class="block font-bold text-kmf-orange uppercase text-xs tracking-widest">Event Details &rarr;</a>
                        </div>
                    <?php else: ?>
                        <p class="text-slate-400 italic p-8 border border-dashed rounded-[2rem] text-sm">Stay tuned for upcoming events.</p>
                    <?php endif; ?>
                </div>
                <div class="lg:col-span-7">
                    <div class="flex items-end justify-between mb-6">
                        <h2 class="text-2xl md:text-4xl font-extrabold text-kmf-blue font-montserrat tracking-tight leading-tight">Latest News</h2>
                        <a href="<?php echo BASE_URL; ?>news.php" class="text-slate-400 font-bold hover:text-kmf-blue transition-colors text-sm">All Stories &rarr;</a>
                    </div>
                    <div class="space-y-3">
                        <?php foreach ($latestNews as $n): ?>
                        <a href="<?php echo BASE_URL; ?>news.php#news-<?php echo (int)$n['id']; ?>" class="group flex items-center gap-5 p-5 rounded-[1.5rem] bg-slate-50 hover:bg-white hover:shadow-xl border border-slate-100 transition-all duration-300">
                            <div class="flex-grow">
                                <span class="text-[10px] font-bold text-kmf-orange uppercase tracking-[0.2em]"><?php echo date('M d, Y', strtotime($n['published_at'] ?? 'now')); ?></span>
                                <h3 class="text-base md:text-lg font-bold mt-1 text-kmf-blue group-hover:text-kmf-orange transition-colors leading-tight"><?php echo escape($n['title']); ?></h3>
                            </div>
                            <div class="w-10 h-10 rounded-full border border-slate-200 flex items-center justify-center flex-shrink-0 group-hover:bg-kmf-orange group-hover:border-kmf-orange group-hover:text-white transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
}

function renderPartnersMarquee($partners) {
    if (empty($partners)) return;
    ?>
    <section class="py-6 md:py-10 bg-white border-t border-gray-50 overflow-hidden text-center">
        <div class="container mx-auto px-4 mb-6">
            <h2 class="text-xl md:text-2xl font-extrabold text-kmf-blue font-montserrat tracking-tight">Trusted Partners</h2>
        </div>
        
        <style>
            .marquee-container {
                overflow: hidden;
                width: 100%;
                display: flex;
            }
            .marquee-content {
                display: flex;
                white-space: nowrap;
                animation: marquee 30s linear infinite;
            }
            @keyframes marquee {
                from { transform: translateX(0); }
                to { transform: translateX(-50%); }
            }
            .marquee-content:hover {
                animation-play-state: paused;
            }
        </style>

        <div class="marquee-container">
            <div class="marquee-content flex items-center gap-12 md:gap-24 py-3">
                <?php 
                // Double the partners for seamless looping
                $displayPartners = array_merge($partners, $partners);
                foreach ($displayPartners as $p): 
                ?>
                    <div class="flex-shrink-0 grayscale hover:grayscale-0 opacity-50 hover:opacity-100 transition-all duration-500">
                        <?php if ($p['logo_url']): ?>
                            <img src="<?php echo BASE_URL . escape($p['logo_url']); ?>" alt="<?php echo escape($p['name']); ?>" class="h-8 md:h-10 w-auto object-contain">
                        <?php else: ?>
                            <span class="font-bold text-slate-400 text-base"><?php echo escape($p['name']); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}
