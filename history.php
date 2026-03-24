<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('history');
$pageTitle = $page ? $page['title'] : 'Our History';
$metaDescription = $page ? $page['meta_description'] : 'The journey of Kanchhi Maya Tamang Foundation';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section with Background Image -->
<section class="relative h-[60vh] md:h-[70vh] flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?php echo BASE_URL; ?>assets/images/history-hero.png" alt="KMF History" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-kmf-blue/60 backdrop-blur-[2px]"></div>
    </div>
    
    <div class="container mx-auto px-4 lg:px-8 relative z-10 text-center">
        <p class="text-kmf-orange font-bold uppercase tracking-[0.3em] text-sm md:text-base mb-4 animate-fade-in">Our Journey</p>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-tight tracking-tighter animate-slide-up font-montserrat">
            A Legacy of <span class="text-kmf-green-light italic">Impact</span>
        </h1>
        <div class="mt-8 w-24 h-1.5 bg-kmf-orange mx-auto rounded-full animate-pulse-slow"></div>
    </div>
</section>

<!-- Timeline Section -->
<section class="py-16 md:py-24 lg:py-32 bg-white relative overflow-hidden">
    <!-- Decorative curve background -->
    <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-gray-50 to-white -translate-y-full"></div>

    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mx-auto mb-20">
            <h2 class="text-3xl md:text-4xl font-extrabold text-kmf-blue mb-6">Our Milestones</h2>
            <p class="text-lg text-gray-600 leading-relaxed">Since our inception, every year has been a step towards a more inclusive and empowered society. Here's a look back at our key moments.</p>
        </div>

        <div class="relative">
            <!-- Central Line -->
            <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-kmf-orange via-kmf-blue to-kmf-green-light rounded-full transform md:-translate-x-1/2"></div>

            <!-- Milestones -->
            <div class="space-y-12 md:space-y-24">
                
                <!-- 2015: Founding -->
                <div class="relative flex flex-col md:flex-row items-center group">
                    <div class="flex-1 md:text-right md:pr-12 md:order-1 mb-8 md:mb-0">
                        <div class="inline-block px-4 py-1 rounded-full bg-kmf-orange/10 text-kmf-orange font-bold text-sm mb-4">2015</div>
                        <h3 class="text-2xl md:text-3xl font-bold text-kmf-blue mb-4">The Beginning</h3>
                        <p class="text-gray-600 leading-relaxed">Kanchhi Maya Tamang Foundation was officially established with a mission to improve rural education and community health in memory of our inspirer.</p>
                    </div>
                    
                    <div class="absolute left-4 md:left-1/2 w-8 h-8 bg-white border-4 border-kmf-orange rounded-full transform -translate-x-1/2 z-10 transition-transform duration-500 group-hover:scale-125 group-hover:bg-kmf-orange shadow-lg"></div>
                    
                    <div class="flex-1 md:pl-12 md:order-2 w-full">
                        <div class="aspect-video rounded-3xl overflow-hidden shadow-xl border border-gray-100 group-hover:shadow-2xl transition-all duration-500">
                             <img src="<?php echo BASE_URL; ?>assets/images/about-women-community.jpg" alt="Founding" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        </div>
                    </div>
                </div>

                <!-- 2018: First Major Program -->
                <div class="relative flex flex-col md:flex-row items-center group">
                    <div class="flex-1 md:pl-12 md:order-2 mb-8 md:mb-0">
                        <div class="inline-block px-4 py-1 rounded-full bg-kmf-blue/10 text-kmf-blue font-bold text-sm mb-4">2018</div>
                        <h3 class="text-2xl md:text-3xl font-bold text-kmf-blue mb-4">Expanding Reach</h3>
                        <p class="text-gray-600 leading-relaxed">Launched our first comprehensive district-wide health camp initiative, reaching over 5,000 community members in remote villages.</p>
                    </div>
                    
                    <div class="absolute left-4 md:left-1/2 w-8 h-8 bg-white border-4 border-kmf-blue rounded-full transform -translate-x-1/2 z-10 transition-transform duration-500 group-hover:scale-125 group-hover:bg-kmf-blue shadow-lg"></div>
                    
                    <div class="flex-1 md:pr-12 md:order-1 w-full">
                        <div class="aspect-video rounded-3xl overflow-hidden shadow-xl border border-gray-100 group-hover:shadow-2xl transition-all duration-500">
                             <img src="<?php echo BASE_URL; ?>assets/uploads/strategic-areas/education.png" alt="Expansion" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        </div>
                    </div>
                </div>

                <!-- 2022: Educational Excellence -->
                <div class="relative flex flex-col md:flex-row items-center group">
                    <div class="flex-1 md:text-right md:pr-12 md:order-1 mb-8 md:mb-0">
                        <div class="inline-block px-4 py-1 rounded-full bg-kmf-green/10 text-kmf-green font-bold text-sm mb-4">2022</div>
                        <h3 class="text-2xl md:text-3xl font-bold text-kmf-blue mb-4">Digital Literacy</h3>
                        <p class="text-gray-600 leading-relaxed">Inaugurated the first community-led digital learning center, providing children with access to computers and modern educational resources.</p>
                    </div>
                    
                    <div class="absolute left-4 md:left-1/2 w-8 h-8 bg-white border-4 border-kmf-green rounded-full transform -translate-x-1/2 z-10 transition-transform duration-500 group-hover:scale-125 group-hover:bg-kmf-green shadow-lg"></div>
                    
                    <div class="flex-1 md:pl-12 md:order-2 w-full">
                        <div class="aspect-video rounded-3xl overflow-hidden shadow-xl border border-gray-100 group-hover:shadow-2xl transition-all duration-500">
                            <img src="<?php echo BASE_URL; ?>assets/uploads/strategic-areas/community.png" alt="Educational Excellence" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        </div>
                    </div>
                </div>

                <!-- 2026: The Vision Ahead -->
                <div class="relative flex flex-col md:flex-row items-center group">
                    <div class="flex-1 md:pl-12 md:order-2">
                        <div class="inline-block px-4 py-1 rounded-full bg-kmf-orange/10 text-kmf-orange font-bold text-sm mb-4">2026</div>
                        <h3 class="text-2xl md:text-3xl font-bold text-kmf-blue mb-4">Sustainable Future</h3>
                        <p class="text-gray-600 leading-relaxed">Today, we are pioneering sustainable community models that integrate ecological farming with local entrepreneurial programs.</p>
                    </div>
                    
                    <div class="absolute left-4 md:left-1/2 w-8 h-8 bg-white border-4 border-kmf-orange rounded-full transform -translate-x-1/2 z-10 transition-transform duration-500 group-hover:scale-125 group-hover:bg-kmf-orange shadow-lg"></div>
                    
                    <div class="flex-1 md:pr-12 md:order-1 w-full">
                        <div class="aspect-video rounded-3xl overflow-hidden shadow-xl border border-gray-100 group-hover:shadow-2xl transition-all duration-500">
                            <img src="<?php echo BASE_URL; ?>assets/uploads/strategic-areas/health.png" alt="Vision Ahead" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 bg-kmf-blue relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-20">
         <img src="<?php echo BASE_URL; ?>assets/images/history-hero.png" alt="History Bg" class="w-full h-full object-cover">
    </div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h2 class="text-3xl md:text-4xl font-black text-white mb-8">Be Part of Our Next Chapter</h2>
        <div class="flex flex-wrap justify-center gap-6">
            <a href="<?php echo BASE_URL; ?>donate.php" class="bg-kmf-orange hover:bg-kmf-orange-light text-white font-bold px-10 py-4 rounded-2xl shadow-xl transition-all transform hover:-translate-y-1">Donate Now</a>
            <a href="<?php echo BASE_URL; ?>contact.php" class="bg-white hover:bg-gray-100 text-kmf-blue font-bold px-10 py-4 rounded-2xl shadow-xl transition-all transform hover:-translate-y-1">Get Involved</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
