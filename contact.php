<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Contact Us';
$metaDescription = 'Contact Kanchhi Maya Tamang Foundation';

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

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">Contact</p>
        <h1 class="text-3xl md:text-4xl font-bold text-kmf-blue mb-8">Contact Us</h1>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h2 class="text-xl font-semibold text-kmf-blue mb-4">Get in Touch</h2>
                <?php if (getSetting('address')): ?>
                    <p class="text-gray-600 mb-4"><?php echo nl2br(escape(getSetting('address'))); ?></p>
                <?php endif; ?>
                <?php if (getSetting('phone')): ?>
                    <p class="text-gray-600 mb-4"><?php echo escape(getSetting('phone')); ?></p>
                <?php endif; ?>
                <?php if (getSetting('email')): ?>
                    <p class="mb-4">
                        <a href="mailto:<?php echo escape(getSetting('email')); ?>" class="text-kmf-orange font-semibold hover:underline"><?php echo escape(getSetting('email')); ?></a>
                    </p>
                <?php endif; ?>
            </div>
            <div class="bg-gray-50 rounded-xl p-6 md:p-8 border border-gray-100">
                <?php if ($sent): ?>
                    <p class="text-green-600 font-medium">Thank you. Your message has been sent.</p>
                <?php else: ?>
                    <?php if ($error): ?>
                        <p class="text-red-600 mb-4"><?php echo escape($error); ?></p>
                    <?php endif; ?>
                    <form method="post" action="" class="space-y-4">
                        <?php echo csrfField(); ?>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                            <input type="text" id="name" name="name" required value="<?php echo escape($_POST['name'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kmf-blue">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" id="email" name="email" required value="<?php echo escape($_POST['email'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kmf-blue">
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <input type="text" id="subject" name="subject" value="<?php echo escape($_POST['subject'] ?? ''); ?>" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kmf-blue">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                            <textarea id="message" name="message" required rows="5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-kmf-blue"><?php echo escape($_POST['message'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="w-full md:w-auto bg-kmf-orange hover:bg-kmf-orange-light text-white font-semibold px-8 py-3 rounded-lg transition">Send Message</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
