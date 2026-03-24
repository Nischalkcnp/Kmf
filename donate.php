<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('donate');
$pageTitle = $page ? $page['title'] : 'Donate - Support Our Mission';
$metaDescription = $page ? $page['meta_description'] : 'Support Kanchhi Maya Tamang Foundation. Your donations help us empower rural communities through education, health, and sustainable development.';

require_once __DIR__ . '/includes/header.php';
?>

<section class="relative py-20 lg:py-32 overflow-hidden bg-white">
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-40">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-kmf-blue/5 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-kmf-orange/5 rounded-full blur-[100px]"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="max-w-4xl mx-auto text-center mb-16 lg:mb-24">
            <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-xs mb-4 block">Make a Difference</span>
            <h1 class="text-4xl md:text-6xl font-black text-kmf-blue mb-8 tracking-tighter leading-tight">Your Support <span class="text-kmf-orange">Empowers</span> Rural Communities</h1>
            <p class="text-xl text-slate-600 font-medium leading-relaxed max-w-2xl mx-auto">
                Every contribution, no matter the size, goes directly towards our mission of improving education, health, and community welfare in Nepal.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-start">
            <!-- Donation Info -->
            <div class="space-y-10">
                <div class="bg-gray-50/50 rounded-[2.5rem] p-10 border border-gray-100">
                    <h2 class="text-2xl font-extrabold text-kmf-blue mb-6">Direct Bank Transfer</h2>
                    <p class="text-slate-500 mb-8 font-medium">You can donate directly to our official bank account. Please share the confirmation receipt with us at <a href="mailto:info@kmf.org.np" class="text-kmf-blue font-bold hover:text-kmf-orange transition-colors">info@kmf.org.np</a>.</p>
                    
                    <div class="space-y-6">
                        <div class="flex flex-col p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Account Name</span>
                            <span class="text-lg font-black text-kmf-blue">KANCHHI MAYA TAMANG FOUNDATION</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Bank Name</span>
                                <span class="text-lg font-black text-kmf-blue">Global IME Bank</span>
                            </div>
                            <div class="flex flex-col p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Branch</span>
                                <span class="text-lg font-black text-kmf-blue">Kathmandu, Nepal</span>
                            </div>
                        </div>
                        <div class="flex flex-col p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Account Number</span>
                            <span class="text-xl font-black text-kmf-orange tracking-wider">001002003004005</span>
                        </div>
                        <div class="flex flex-col p-6 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">SWIFT Code</span>
                            <span class="text-lg font-black text-kmf-blue uppercase">GIME NP KA</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-6 p-8 bg-kmf-blue rounded-[2rem] text-white">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center flex-shrink-0 text-kmf-orange-light">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-xl mb-1">Secure & Transparent</h3>
                        <p class="text-blue-100 text-sm font-medium opacity-80">We ensure that 100% of your donation is utilized for project-related expenses.</p>
                    </div>
                </div>
            </div>

            <!-- QR Code / Mobile Pay -->
            <div class="lg:sticky lg:top-36 space-y-8">
                <div class="bg-white rounded-[2.5rem] p-10 shadow-2xl shadow-kmf-blue/10 border border-gray-50 flex flex-col items-center text-center">
                    <h2 class="text-2xl font-extrabold text-kmf-blue mb-4">Scan to Donate</h2>
                    <p class="text-slate-500 mb-10 font-medium">Use any Nepalese Digital Wallet (eSewa, Khalti, or FonePay) to scan the QR code below.</p>
                    
                    <div class="w-64 h-64 bg-gray-50 rounded-3xl border-4 border-dashed border-gray-200 flex items-center justify-center mb-10 p-4">
                        <!-- Placeholder for QR Code -->
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">QR Code Placeholder</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 w-full">
                        <div class="p-4 bg-gray-50 rounded-2xl flex items-center justify-center grayscale hover:grayscale-0 transition-all opacity-60 hover:opacity-100">
                            <span class="font-black text-gray-400">eSewa</span>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl flex items-center justify-center grayscale hover:grayscale-0 transition-all opacity-60 hover:opacity-100">
                            <span class="font-black text-gray-400">FonePay</span>
                        </div>
                    </div>
                </div>

                <div class="bg-kmf-orange/5 rounded-[2rem] p-8 border border-kmf-orange/10">
                    <h3 class="font-extrabold text-kmf-blue mb-4">Other Ways to Help?</h3>
                    <p class="text-slate-600 font-medium text-sm mb-6">If you wish to donate materials, sponsor a child's education, or volunteer your skills, please get in touch with our team.</p>
                    <a href="<?php echo BASE_URL; ?>contact.php" class="inline-flex items-center gap-2 text-kmf-orange font-bold text-sm hover:underline">
                        Explore Opportunities
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
