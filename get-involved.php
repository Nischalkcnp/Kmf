<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('get-involved');
$pageTitle = $page ? $page['title'] : 'Get Involved';
$metaDescription = $page ? $page['meta_description'] : 'Opportunities to get involved with Kanchhi Maya Tamang Foundation';

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-20 items-center mb-20">
            <div class="order-2 md:order-1">
                <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">Join Our Mission</p>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-kmf-blue mb-8 leading-tight"><?php echo escape($pageTitle); ?></h1>
                <div class="prose-custom max-w-lg text-gray-600 font-medium leading-relaxed">
                    <?php echo $page ? $page['content'] : '<p class="text-lg">Join us through volunteering, partnerships, or donations. We welcome your support in creating lasting impact in rural communities of Nepal.</p>'; ?>
                </div>
            </div>
            <div class="order-1 md:order-2 group">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl transition-transform duration-500 group-hover:scale-[1.02]">
                    <img src="<?php echo BASE_URL; ?>assets/images/get-involved-volunteers.svg" alt="Volunteers working with KMF in the community" class="w-full h-64 md:h-80 lg:h-96 object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-kmf-blue/30 to-transparent"></div>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 lg:gap-8">
            <a href="<?php echo BASE_URL; ?>contact.php" class="group block bg-kmf-blue text-white rounded-3xl p-8 md:p-10 transition-all duration-300 hover:shadow-2xl hover:shadow-kmf-blue/20 hover:-translate-y-1">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mb-6 transition-transform duration-500 group-hover:scale-110">
                    <svg class="w-8 h-8 text-kmf-orange-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="font-extrabold text-2xl mb-4">Partner with Us</h3>
                <p class="text-blue-100 font-medium leading-relaxed mb-6">Explore institutional partnerships and collaborative opportunities.</p>
                <div class="flex items-center gap-2 text-kmf-orange-light font-bold text-sm">
                    <span>Contact Us</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </a>
            
            <a href="<?php echo BASE_URL; ?>contact.php" class="group block bg-gray-50 rounded-3xl p-8 md:p-10 border border-gray-100 transition-all duration-300 hover:shadow-2xl hover:shadow-gray-200 hover:-translate-y-1">
                <div class="w-16 h-16 bg-kmf-blue/10 rounded-2xl flex items-center justify-center mb-6 transition-transform duration-500 group-hover:scale-110">
                    <svg class="w-8 h-8 text-kmf-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="font-extrabold text-2xl text-kmf-blue mb-4">Job Vacancies</h3>
                <p class="text-gray-600 font-medium leading-relaxed mb-6">Join our dedicated team and contribute to professional community work.</p>
                <div class="flex items-center gap-2 text-kmf-orange font-bold text-sm">
                    <span>View Openings</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </a>
            
            <a href="<?php echo BASE_URL; ?>contact.php" class="group block bg-gray-50 rounded-3xl p-8 md:p-10 border border-gray-100 transition-all duration-300 hover:shadow-2xl hover:shadow-gray-200 hover:-translate-y-1">
                <div class="w-16 h-16 bg-kmf-green/10 rounded-2xl flex items-center justify-center mb-6 transition-transform duration-500 group-hover:scale-110">
                    <svg class="w-8 h-8 text-kmf-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="font-extrabold text-2xl text-kmf-blue mb-4">Interships & EOI</h3>
                <p class="text-gray-600 font-medium leading-relaxed mb-6">Gain field experience or express your interest for volunteering.</p>
                <div class="flex items-center gap-2 text-kmf-orange font-bold text-sm">
                    <span>Express Interest</span>
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </div>
            </a>
        </div>

        <!-- Membership Eligibility & Fee Section -->
        <div class="mt-12 md:mt-20 p-6 sm:p-8 md:p-12 bg-slate-50 border border-slate-100 rounded-3xl md:rounded-[2.5rem] shadow-sm flex flex-col lg:flex-row items-center gap-6 lg:gap-10">
            <div class="w-full lg:w-7/12">
                <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-xs mb-3 block">Membership Program</span>
                <h2 class="text-3xl font-extrabold text-kmf-blue font-montserrat mb-6 tracking-tight">Become an Active Member</h2>
                <p class="text-slate-600 font-medium leading-relaxed mb-6">Join KMTF as a registered member to help drive sustainable development, healthcare access, and quality education in marginalized communities. In accordance with our official Memorandum of Association, we invite dedicated citizens to join us.</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm">
                    <div class="flex gap-3 items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-kmf-blue/10 rounded-full flex items-center justify-center text-kmf-blue">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-kmf-blue mb-1">Clause 9: Nationality</h4>
                            <p class="text-slate-500 font-medium">Must be a Nepalese citizen who is not disqualified under prevailing laws.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-kmf-blue/10 rounded-full flex items-center justify-center text-kmf-blue">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-kmf-blue mb-1">Clause 9: Age Limit</h4>
                            <p class="text-slate-500 font-medium">Must have completed 18 years of age.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-kmf-blue/10 rounded-full flex items-center justify-center text-kmf-blue">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-kmf-blue mb-1">Clause 7: Entry Fee</h4>
                            <p class="text-slate-500 font-medium">New members shall deposit a minimum membership fee of NRs. 5,000/- (Rupees Five Thousand Only).</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-start">
                        <div class="flex-shrink-0 w-6 h-6 bg-kmf-blue/10 rounded-full flex items-center justify-center text-kmf-blue">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-kmf-blue mb-1">Non-Transferability</h4>
                            <p class="text-slate-500 font-medium">Membership is strictly non-transferable and subject to Board approval.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="w-full lg:w-5/12 bg-white rounded-3xl p-8 border border-slate-100 shadow-sm flex flex-col justify-center text-center">
                <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Clause 7 (2) Entry Fee Structure</div>
                <div class="text-4xl font-black text-kmf-blue mb-1">NRs. 5,000/-</div>
                <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-6">One-Time Minimum Deposit</div>
                <a href="<?php echo BASE_URL; ?>contact.php?subject=Membership%20Application" class="w-full bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold py-4 rounded-2xl shadow-xl shadow-kmf-orange/20 transition-all text-center">Apply for Membership &rarr;</a>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
