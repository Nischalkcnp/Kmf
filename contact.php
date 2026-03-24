<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('contact');
$pageTitle = $page ? $page['title'] : 'Contact Us';
$metaDescription = $page ? $page['meta_description'] : 'Contact Kanchhi Maya Tamang Foundation';

$sent = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name && $email && $message && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pdo = getDb();
        $stmt = $pdo->prepare("INSERT INTO contact_submissions (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);
        $sent = true;
    } else {
        $error = 'Please fill in all required fields with a valid email.';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-12 md:py-20 lg:py-24 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">Contact</p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-kmf-blue mb-6 leading-tight">Get in Touch</h1>
            <p class="text-lg md:text-xl text-gray-600 font-medium">Have questions or want to support our mission? We'd love to hear from you. Reach out through the form or our contact details below.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
            <div class="space-y-8">
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 shadow-sm">
                    <h2 class="text-2xl font-extrabold text-kmf-blue mb-6">Contact Information</h2>
                    <div class="space-y-6">
                        <?php if (getSetting('address')): ?>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-kmf-orange/10 text-kmf-orange rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Our Location</p>
                                <p class="text-gray-700 font-medium leading-relaxed"><?php echo nl2br(escape(getSetting('address'))); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (getSetting('phone')): ?>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-kmf-blue/10 text-kmf-blue rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Call Us</p>
                                <p class="text-gray-700 font-bold"><?php echo escape(getSetting('phone')); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (getSetting('email')): ?>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-12 h-12 bg-kmf-green/10 text-kmf-green rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-1">Email Us</p>
                                <a href="mailto:<?php echo escape(getSetting('email')); ?>" class="text-kmf-orange font-bold hover:underline"><?php echo escape(getSetting('email')); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-8 lg:p-10 border border-gray-100 shadow-xl shadow-kmf-blue/5">
                <?php if ($sent): ?>
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-kmf-green/20 text-kmf-green rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="text-2xl font-extrabold text-kmf-blue mb-2">Message Sent!</h3>
                        <p class="text-gray-600 font-medium">Thank you for reaching out. We'll get back to you shortly.</p>
                        <a href="contact.php" class="inline-block mt-8 text-kmf-orange font-bold hover:underline">Send another message</a>
                    </div>
                <?php else: ?>
                    <h2 class="text-2xl font-extrabold text-kmf-blue mb-8">Send a Message</h2>
                    <?php if ($error): ?>
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 font-medium border border-red-100"><?php echo escape($error); ?></div>
                    <?php endif; ?>
                    <form method="post" action="" class="space-y-6">
                        <?php echo csrfField(); ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name *</label>
                                <input type="text" id="name" name="name" required placeholder="Your name" value="<?php echo escape($_POST['name'] ?? ''); ?>" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-kmf-blue focus:bg-white transition-all outline-none">
                            </div>
                            <div>
                                <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" required placeholder="your.email@example.com" value="<?php echo escape($_POST['email'] ?? ''); ?>" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-kmf-blue focus:bg-white transition-all outline-none">
                            </div>
                        </div>
                        <div>
                            <label for="subject" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Subject</label>
                            <input type="text" id="subject" name="subject" placeholder="How can we help?" value="<?php echo escape($_POST['subject'] ?? ''); ?>" class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-kmf-blue focus:bg-white transition-all outline-none">
                        </div>
                        <div>
                            <label for="message" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Message *</label>
                            <textarea id="message" name="message" required rows="5" placeholder="Share your thoughts with us..." class="w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-kmf-blue focus:bg-white transition-all outline-none resize-none"><?php echo escape($_POST['message'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="w-full bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold px-10 py-5 rounded-2xl transition-all shadow-lg shadow-kmf-orange/20 hover:scale-[1.02] active:scale-95">Send Your Message</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
