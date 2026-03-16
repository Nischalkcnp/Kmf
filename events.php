<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Events';
$metaDescription = 'Upcoming and past events of Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$upcoming = $pdo->query("SELECT * FROM events WHERE is_active = 1 AND type = 'upcoming' AND event_date >= CURDATE() ORDER BY event_date ASC")->fetchAll();
$past = $pdo->query("SELECT * FROM events WHERE is_active = 1 AND (type = 'past' OR event_date < CURDATE()) ORDER BY event_date DESC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <p class="text-kmf-orange font-medium uppercase tracking-wider text-sm mb-2">Events</p>
        <h1 class="text-3xl md:text-4xl font-bold text-kmf-blue mb-6">Events</h1>
        <p class="text-gray-600 max-w-3xl mb-12">Join our upcoming events and explore past activities.</p>

        <h2 class="text-xl font-bold text-kmf-blue mb-6">Upcoming Events</h2>
        <div class="space-y-6 mb-16">
            <?php foreach ($upcoming as $e): ?>
            <article class="bg-gray-50 rounded-xl p-6 md:p-8 border border-gray-100 flex flex-col md:flex-row gap-6">
                <?php
                    $img = !empty($e['image_url']) ? $e['image_url'] : 'assets/images/event-placeholder.svg';
                ?>
                <div class="flex-shrink-0 w-full md:w-56">
                    <img src="<?php echo BASE_URL . escape($img); ?>" alt="<?php echo escape($e['title']); ?>" class="w-full h-40 object-cover rounded-lg">
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-kmf-orange">
                        <?php echo formatDate($e['event_date']); ?>
                        <?php if (!empty($e['end_date']) && $e['end_date'] !== $e['event_date']): ?>
                            – <?php echo formatDate($e['end_date']); ?>
                        <?php endif; ?>
                    </p>
                    <h3 class="text-xl font-bold text-kmf-blue mt-1 mb-2"><?php echo escape($e['title']); ?></h3>
                    <?php if (!empty($e['venue'])): ?>
                        <p class="text-gray-600 mb-2"><?php echo escape($e['venue']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($e['excerpt'])): ?>
                        <p class="text-gray-600"><?php echo escape($e['excerpt']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($e['content'])): ?>
                        <div class="prose-custom text-gray-600 mt-2"><?php echo $e['content']; ?></div>
                    <?php endif; ?>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php if (empty($upcoming)): ?>
            <p class="text-gray-600 mb-12">No upcoming events at the moment.</p>
        <?php endif; ?>

        <h2 class="text-xl font-bold text-kmf-blue mb-6">Past Events</h2>
        <div class="space-y-6">
            <?php foreach ($past as $e): ?>
            <article class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                <p class="text-sm text-gray-500"><?php echo formatDate($e['event_date']); ?></p>
                <h3 class="text-lg font-semibold text-kmf-blue mt-1 mb-2"><?php echo escape($e['title']); ?></h3>
                <?php if (!empty($e['excerpt'])): ?>
                    <p class="text-gray-600 text-sm"><?php echo escape($e['excerpt']); ?></p>
                <?php endif; ?>
            </article>
            <?php endforeach; ?>
        </div>
        <?php if (empty($past)): ?>
            <p class="text-gray-600">No past events listed.</p>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
