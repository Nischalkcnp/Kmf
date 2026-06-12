<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('donate');
$pageTitle       = $page['title']            ?? 'Donate - Support Our Mission';
$metaDescription = $page['meta_description'] ?? 'Support Kanchhi Maya Tamang Foundation through your donations.';

// Donate page settings
$heading      = getSetting('donate_heading',      'Your Support Empowers Rural Communities');
$subheading   = getSetting('donate_subheading',   'Every contribution, no matter the size, goes directly towards our mission of improving education, health, and community welfare in Nepal.');
$accountName  = getSetting('donate_account_name', 'KANCHHI MAYA TAMANG FOUNDATION');
$bankName     = getSetting('donate_bank_name',    'Global IME Bank');
$bankBranch   = getSetting('donate_bank_branch',  'Kathmandu, Nepal');
$accountNo    = getSetting('donate_account_no',   '');
$swift        = getSetting('donate_swift',        '');
$qrImage      = getSetting('donate_qr_image',     '');
$otherHelp    = getSetting('donate_other_help',   'If you wish to donate materials, sponsor a child\'s education, or volunteer your skills, please get in touch with our team.');
$email        = getSetting('email',               'info@kmf.org.np');

require_once __DIR__ . '/includes/header.php';
?>

<section class="relative py-10 lg:py-16 overflow-hidden bg-white">
    <!-- Background Accents -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-40">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-kmf-blue/5 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-kmf-orange/5 rounded-full blur-[100px]"></div>
    </div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">

        <!-- Page Header -->
        <div class="max-w-4xl mx-auto text-center mb-10 lg:mb-12">
            <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-xs mb-2 block">Make a Difference</span>
            <h1 class="text-3xl md:text-5xl font-black text-kmf-blue mb-4 tracking-tighter leading-tight">
                <?php echo escape($heading); ?>
            </h1>
            <p class="text-lg text-slate-600 font-medium leading-relaxed max-w-2xl mx-auto">
                <?php echo escape($subheading); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-start">

            <!-- Left: Bank Transfer -->
            <div class="space-y-6">
                <div class="bg-gray-50/50 rounded-3xl p-6 lg:p-8 border border-gray-100">
                    <h2 class="text-xl font-extrabold text-kmf-blue mb-4">Direct Bank Transfer</h2>
                    <p class="text-slate-500 mb-6 font-medium text-sm">
                        You can donate directly to our official bank account. Please share the confirmation receipt with us at
                        <a href="mailto:<?php echo escape($email); ?>" class="text-kmf-blue font-bold hover:text-kmf-orange transition-colors"><?php echo escape($email); ?></a>.
                    </p>

                    <div class="space-y-4">
                        <!-- Account Name -->
                        <div class="flex flex-col p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Account Name</span>
                            <span class="text-base font-black text-kmf-blue"><?php echo escape($accountName); ?></span>
                        </div>

                        <!-- Bank + Branch -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Bank Name</span>
                                <span class="text-base font-black text-kmf-blue"><?php echo escape($bankName); ?></span>
                            </div>
                            <div class="flex flex-col p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Branch</span>
                                <span class="text-base font-black text-kmf-blue"><?php echo escape($bankBranch); ?></span>
                            </div>
                        </div>

                        <!-- Account Number -->
                        <?php if ($accountNo): ?>
                        <div class="flex flex-col p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Account Number</span>
                            <span class="text-lg font-black text-kmf-orange tracking-wider"><?php echo escape($accountNo); ?></span>
                        </div>
                        <?php endif; ?>

                        <!-- SWIFT -->
                        <?php if ($swift): ?>
                        <div class="flex flex-col p-4 bg-white rounded-2xl border border-gray-100 shadow-sm">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">SWIFT Code</span>
                            <span class="text-base font-black text-kmf-blue uppercase"><?php echo escape($swift); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Trust badge -->
                <div class="flex items-center gap-4 p-6 bg-kmf-blue rounded-3xl text-white">
                    <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center flex-shrink-0 text-kmf-orange-light">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-lg mb-1">Secure &amp; Transparent</h3>
                        <p class="text-blue-100 text-xs font-medium opacity-80">We ensure that 100% of your donation is utilized for project-related expenses.</p>
                    </div>
                </div>
            </div>

            <!-- Right: QR + Other ways -->
            <div class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white rounded-3xl p-6 lg:p-8 shadow-xl shadow-kmf-blue/5 border border-gray-50 flex flex-col items-center text-center">
                    <h2 class="text-xl font-extrabold text-kmf-blue mb-4">Scan to Donate</h2>
                    <p class="text-slate-500 mb-6 font-medium text-sm">Use any Nepalese digital wallet (eSewa, Khalti, or FonePay) to scan the QR code below.</p>

                    <div class="w-48 h-48 bg-gray-50 rounded-3xl border-4 border-dashed border-gray-200 flex items-center justify-center mb-6 p-4 overflow-hidden">
                        <?php if (!empty($qrImage)): ?>
                            <img src="<?php echo BASE_URL . escape($qrImage); ?>" alt="Donation QR Code" class="w-full h-full object-contain">
                        <?php else: ?>
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">QR Code</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="grid grid-cols-2 gap-4 w-full">
                        <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-center opacity-60 hover:opacity-100 transition-all">
                            <span class="font-black text-gray-400 text-sm">eSewa</span>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-xl flex items-center justify-center opacity-60 hover:opacity-100 transition-all">
                            <span class="font-black text-gray-400 text-sm">FonePay</span>
                        </div>
                    </div>
                </div>

                <!-- Other ways -->
                <div class="bg-kmf-orange/5 rounded-3xl p-6 lg:p-8 border border-kmf-orange/10">
                    <h3 class="font-extrabold text-kmf-blue mb-2">Other Ways to Help?</h3>
                    <p class="text-slate-600 font-medium text-xs mb-4"><?php echo escape($otherHelp); ?></p>
                    <a href="<?php echo BASE_URL; ?>contact.php" class="inline-flex items-center gap-2 text-kmf-orange font-bold text-[13px] hover:underline">
                        Explore Opportunities
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
