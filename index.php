<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Home';
$metaDescription = getSetting('site_tagline');

// Fetch strategic areas for "What We Do" preview
$pdo = getDb();
$areas = $pdo->query("SELECT id, title, slug, excerpt FROM strategic_areas WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 6")->fetchAll();
$programs = $pdo->query("SELECT id, title, slug, excerpt, image_url FROM programs WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 3")->fetchAll();
$publications = $pdo->query("SELECT id, title, slug, excerpt, image_url, published_at FROM publications WHERE is_active = 1 ORDER BY published_at DESC LIMIT 3")->fetchAll();
$latestNews = $pdo->query("SELECT id, title, slug, excerpt, published_at FROM news WHERE is_active = 1 ORDER BY published_at DESC LIMIT 3")->fetchAll();
$upcomingEvent = $pdo->query("SELECT id, title, slug, event_date, end_date, venue FROM events WHERE is_active = 1 AND type = 'upcoming' AND event_date >= CURDATE() ORDER BY event_date ASC LIMIT 1")->fetch();
$impactStats = $pdo->query("SELECT title, stat_value, icon FROM impact_stats WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();
$team = $pdo->query("SELECT * FROM team WHERE is_active = 1 ORDER BY type, sort_order")->fetchAll();
$partners = $pdo->query("SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order")->fetchAll();

// Dynamic Section Rendering
require_once __DIR__ . '/includes/section-renderer.php';
$homepageSections = $pdo->query("SELECT * FROM site_sections WHERE is_active = 1 ORDER BY sort_order ASC, id ASC")->fetchAll();

require_once __DIR__ . '/includes/header.php';

$renderData = [
    'areas' => $areas,
    'programs' => $programs,
    'publications' => $publications,
    'latestNews' => $latestNews,
    'upcomingEvent' => $upcomingEvent,
    'impactStats' => $impactStats,
    'team' => $team,
    'partners' => $partners
];

foreach ($homepageSections as $section) {
    renderSection($section, $renderData);
}

require_once __DIR__ . '/includes/footer.php';
exit; // Exit to avoid executing the hardcoded code below
?>
