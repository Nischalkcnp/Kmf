<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'What We Do';
$metaDescription = 'Strategic areas: Education, Community, and Health';

$pdo = getDb();
$areas = $pdo->query("SELECT * FROM strategic_areas WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">What We Do</p>
        <h1 class="text-3xl md:text-4xl font-bold text-kmf-blue mb-6">Strategic Areas</h1>
        <p class="text-gray-600 max-w-3xl mb-12">Our work is organized around education, community welfare, and health—aligned with our foundation's mission.</p>

        <div class="space-y-12">
            <?php foreach ($areas as $a): ?>
            <article id="<?php echo escape($a['slug']); ?>" class="scroll-mt-24 bg-gray-50 rounded-2xl p-6 md:p-10 border border-gray-100">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-shrink-0">
                        <div class="w-16 h-16 rounded-xl bg-kmf-green-light flex items-center justify-center text-kmf-blue">
                            <?php if ($a['icon'] === 'education'): ?>
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            <?php elseif ($a['icon'] === 'people'): ?>
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <?php else: ?>
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-kmf-blue mb-3"><?php echo escape($a['title']); ?></h2>
                        <?php if (!empty($a['excerpt'])): ?>
                            <p class="text-gray-600 mb-4"><?php echo escape($a['excerpt']); ?></p>
                        <?php endif; ?>
                        <div class="prose-custom text-gray-600">
                            <?php echo $a['content'] ?: ''; ?>
                        </div>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($areas)): ?>
            <p class="text-gray-600">Strategic areas will be added here.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
