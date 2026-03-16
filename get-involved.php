<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('get-involved');
$pageTitle = $page ? $page['title'] : 'Get Involved';
$metaDescription = $page ? $page['meta_description'] : 'Opportunities to get involved with Kanchhi Maya Tamang Foundation';

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start mb-10">
            <div>
                <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">Get Involved</p>
                <h1 class="text-3xl md:text-4xl font-bold text-kmf-blue mb-8"><?php echo escape($pageTitle); ?></h1>
                <div class="prose-custom max-w-4xl text-gray-600">
                    <?php echo $page ? $page['content'] : '<p>Join us through volunteering, partnerships, or donations. We welcome your support.</p>'; ?>
                </div>
            </div>
            <div class="hidden md:block">
                <img src="<?php echo BASE_URL; ?>assets/images/get-involved-volunteers.svg" alt="Volunteers working with KMF in the community" class="w-full h-72 lg:h-80 object-cover rounded-2xl shadow-md">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="<?php echo BASE_URL; ?>contact.php" class="block bg-kmf-blue text-white rounded-xl p-6 text-center card-hover">
                <svg class="w-12 h-12 mx-auto mb-3 text-kmf-orange-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <h3 class="font-semibold text-lg mb-2">Contact Us</h3>
                <p class="text-gray-300 text-sm">Get in touch for partnerships or general inquiries.</p>
            </a>
            <a href="<?php echo BASE_URL; ?>contact.php" class="block bg-gray-50 rounded-xl p-6 border border-gray-100 text-center card-hover">
                <svg class="w-12 h-12 mx-auto mb-3 text-kmf-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <h3 class="font-semibold text-lg text-kmf-blue mb-2">Vacancy</h3>
                <p class="text-gray-600 text-sm">Check for job opportunities.</p>
            </a>
            <a href="<?php echo BASE_URL; ?>contact.php" class="block bg-gray-50 rounded-xl p-6 border border-gray-100 text-center card-hover">
                <svg class="w-12 h-12 mx-auto mb-3 text-kmf-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <h3 class="font-semibold text-lg text-kmf-blue mb-2">Internship / EOI</h3>
                <p class="text-gray-600 text-sm">Express your interest for internships.</p>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
