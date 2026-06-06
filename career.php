<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Careers';
$metaDescription = 'Join Kanchhi Maya Tamang Foundation and help make a difference in health, education, and community infrastructure in Nepal.';

$pdo = getDb();
// Fetch active vacancies whose deadlines are today or in the future
$stmt = $pdo->prepare("SELECT * FROM careers WHERE is_active = 1 AND deadline >= :today ORDER BY deadline ASC");
$stmt->execute(['today' => date('Y-m-d')]);
$jobs = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-10 md:py-16 lg:py-20 bg-slate-50 relative overflow-hidden">
    <!-- Decorative background blobs -->
    <div class="absolute top-0 right-0 -mr-32 -mt-32 w-96 h-96 rounded-full bg-kmf-orange/5 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -ml-32 -mb-32 w-96 h-96 rounded-full bg-kmf-blue/5 blur-3xl"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-16 md:mb-24">
            <span class="inline-block py-1 px-3 rounded-full bg-kmf-orange/10 text-kmf-orange font-bold text-xs uppercase tracking-widest mb-4">Work With Us</span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-kmf-blue mb-6 tracking-tight leading-tight">Careers & Opportunities</h1>
            <p class="text-lg md:text-xl text-slate-600 font-medium leading-relaxed">
                Join Kanchhi Maya Tamang Foundation in empowering communities, improving rural health systems, and creating sustainable educational opportunities in Nepal.
            </p>
        </div>

        <?php if (empty($jobs)): ?>
            <div class="bg-white rounded-[2rem] p-12 md:p-16 text-center border border-slate-100 shadow-xl max-w-3xl mx-auto">
                <div class="w-20 h-20 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-kmf-blue mb-4">No Open Positions Right Now</h3>
                <p class="text-slate-500 font-medium text-lg mb-8 leading-relaxed">
                    While we don't have any active vacancies at the moment, we are always on the lookout for passionate individuals. Send us your CV, and we will get in touch when an opportunity matches your profile.
                </p>
                <a href="<?php echo BASE_URL; ?>contact.php" class="inline-flex nav-link-animate px-8 py-4 rounded-2xl text-sm font-bold tracking-wider uppercase text-white bg-kmf-orange hover:bg-kmf-orange-light shadow-[0_10px_20px_rgba(232,93,4,0.15)] transition-all duration-300 transform hover:-translate-y-0.5">
                    Submit Your Resume
                </a>
            </div>
        <?php else: ?>
            <div class="max-w-4xl mx-auto space-y-8">
                <?php foreach ($jobs as $job): ?>
                <div id="<?php echo escape($job['slug']); ?>" class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-100 pb-6 mb-6">
                        <div>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-green-50 text-green-700 mb-3 border border-green-100">
                                Open Position
                            </span>
                            <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue leading-snug">
                                <?php echo escape($job['title']); ?>
                            </h2>
                        </div>
                        <div class="flex items-center gap-2 text-slate-500 font-medium text-sm md:text-right shrink-0 bg-slate-50 px-4 py-2.5 rounded-2xl border border-slate-100 w-max md:w-auto">
                            <svg class="w-5 h-5 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Deadline: <?php echo formatDate($job['deadline'], 'F j, Y'); ?></span>
                        </div>
                    </div>

                    <p class="text-slate-600 text-base md:text-lg leading-relaxed mb-6 font-medium">
                        <?php echo escape($job['excerpt']); ?>
                    </p>

                    <!-- Toggle Button -->
                    <button onclick="toggleJobDetails(this)" class="inline-flex items-center gap-2 text-kmf-orange hover:text-kmf-orange-light font-extrabold text-sm transition-all focus:outline-none">
                        <span>View Details & Requirements</span>
                        <svg class="w-4 h-4 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Expanded Content -->
                    <div class="hidden overflow-hidden mt-6 pt-6 border-t border-slate-100 transition-all duration-500">
                        <div class="prose-custom text-slate-500 text-sm md:text-base leading-relaxed mb-8">
                            <?php echo $job['description']; ?>
                        </div>
                        
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                            <div>
                                <h4 class="font-extrabold text-kmf-blue text-base">Interested in this role?</h4>
                                <p class="text-xs text-slate-500 mt-1 font-medium">Please send your updated CV and cover letter to <span class="font-bold text-kmf-orange">info@kmf.org.np</span> before the deadline.</p>
                            </div>
                            <a href="<?php echo BASE_URL; ?>contact.php?subject=Application for <?php echo urlencode($job['title']); ?>" class="w-full sm:w-auto bg-kmf-blue hover:bg-kmf-blue-dark text-white text-center font-bold px-6 py-3 rounded-xl transition-all shadow-md">
                                Apply Now
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
function toggleJobDetails(button) {
    const detailsDiv = button.nextElementSibling;
    const arrowSvg = button.querySelector('svg');
    const labelSpan = button.querySelector('span');

    if (detailsDiv.classList.contains('hidden')) {
        detailsDiv.classList.remove('hidden');
        arrowSvg.classList.add('rotate-180');
        labelSpan.textContent = 'Hide Details & Requirements';
    } else {
        detailsDiv.classList.add('hidden');
        arrowSvg.classList.remove('rotate-180');
        labelSpan.textContent = 'View Details & Requirements';
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
