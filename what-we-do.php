<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('what-we-do');
$pageTitle = $page ? $page['title'] : 'What We Do';
$metaDescription = $page ? $page['meta_description'] : 'Strategic areas: Education, Community, and Health';
$pageIntro = ($page && !empty(trim($page['content']))) ? $page['content'] : '<p>Our work is organized around education, community welfare, and health—carefully aligned with our foundation\'s core mission to empower Nepal\'s local communities.</p>';

$pdo = getDb();
$areas = $pdo->query("SELECT * FROM strategic_areas WHERE is_active = 1 ORDER BY sort_order ASC")->fetchAll();

// Fetch photos for all active areas
$areaIds = array_column($areas, 'id');
$areaPhotos = [];
if (!empty($areaIds)) {
    $placeholders = implode(',', array_fill(0, count($areaIds), '?'));
    $stmt = $pdo->prepare("SELECT * FROM strategic_area_photos WHERE area_id IN ($placeholders) ORDER BY sort_order, id");
    $stmt->execute($areaIds);
    $photos = $stmt->fetchAll();
    foreach ($photos as $photo) {
        $areaPhotos[$photo['area_id']][] = $photo;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl">
            <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">Our Impact</p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-kmf-blue mb-6 leading-tight">Strategic Areas</h1>
            <p class="text-lg md:text-xl text-gray-600 mb-12 font-medium leading-relaxed"><?php echo $pageIntro; ?></p>
        </div>

        <div class="grid grid-cols-1 gap-12 md:gap-20">
            <?php foreach ($areas as $index => $a): 
                $isEven = $index % 2 === 0;
            ?>
            <article id="<?php echo escape($a['slug']); ?>" class="group scroll-mt-24">
                <div class="flex flex-col <?php echo $isEven ? 'md:flex-row' : 'md:flex-row-reverse'; ?> items-center gap-8 lg:gap-16">
                    <!-- Image Card -->
                    <div class="w-full md:w-1/2 overflow-hidden rounded-3xl shadow-lg border border-gray-100 group-hover:shadow-2xl transition-all duration-700">
                        <div class="relative aspect-[4/3] md:aspect-[16/10] overflow-hidden">
                            <?php if (!empty($a['image_url'])): ?>
                                <img src="<?php echo BASE_URL . escape($a['image_url']); ?>" alt="<?php echo escape($a['title']); ?>" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-1000">
                            <?php else: ?>
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                     <div class="w-24 h-24 rounded-2xl bg-kmf-green-light flex items-center justify-center text-kmf-blue shadow-inner group-hover:bg-kmf-orange group-hover:text-white transition-all duration-300">
                                        <?php if ($a['icon'] === 'education'): ?>
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                        <?php elseif ($a['icon'] === 'people'): ?>
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <?php else: ?>
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <!-- Overlay detail for touch of luxury -->
                            <div class="absolute inset-0 bg-gradient-to-t from-kmf-blue/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        </div>
                    </div>

                    <!-- Content Card -->
                    <div class="w-full md:w-1/2">
                        <div class="flex items-center gap-4 mb-4">
                             <div class="w-10 h-10 rounded-xl bg-kmf-green-light flex items-center justify-center text-kmf-blue group-hover:bg-kmf-orange group-hover:text-white transition-all duration-300">
                                <?php if ($a['icon'] === 'education'): ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                <?php elseif ($a['icon'] === 'people'): ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <?php else: ?>
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <?php endif; ?>
                            </div>
                            <span class="text-sm font-bold uppercase tracking-wider text-kmf-orange"><?php echo escape(str_replace('-', ' ', $a['slug'])); ?></span>
                        </div>
                        <h2 class="text-3xl lg:text-4xl font-extrabold text-kmf-blue mb-6 group-hover:text-kmf-orange transition-colors"><?php echo escape($a['title']); ?></h2>
                        <?php if (!empty($a['excerpt'])): ?>
                            <p class="text-xl text-gray-700 mb-6 font-medium leading-relaxed italic border-l-4 border-kmf-green pl-4"><?php echo escape($a['excerpt']); ?></p>
                        <?php endif; ?>
                        <div class="prose-custom text-gray-600 max-w-none text-lg leading-loose">
                            <?php echo $a['content'] ?: ''; ?>
                        </div>

                        <!-- Gallery Section -->
                        <?php 
                        $gallery = $areaPhotos[$a['id']] ?? []; 
                        if (!empty($gallery)): 
                        ?>
                            <div class="mt-8 border-t border-gray-100 pt-6">
                                <h4 class="text-xs font-extrabold uppercase tracking-widest text-kmf-blue mb-4 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Project Gallery
                                </h4>
                                <div class="grid grid-cols-4 gap-3">
                                    <?php 
                                    $displayCount = min(4, count($gallery));
                                    for ($photoIndex = 0; $photoIndex < $displayCount; $photoIndex++): 
                                        $photo = $gallery[$photoIndex];
                                        $isLast = ($photoIndex === 3 && count($gallery) > 4);
                                    ?>
                                        <div class="relative group/photo overflow-hidden rounded-2xl shadow-sm border border-gray-200 aspect-square cursor-pointer hover:shadow-lg transition-all duration-300" 
                                             onclick="openAreaLightbox(<?php echo $a['id']; ?>, <?php echo $photoIndex; ?>)">
                                            <img src="<?php echo BASE_URL . escape($photo['image_url']); ?>" alt="<?php echo escape($a['title']); ?> gallery" class="w-full h-full object-cover transform group-hover/photo:scale-110 transition-transform duration-500">
                                            
                                            <?php if ($isLast): ?>
                                                <div class="absolute inset-0 bg-kmf-blue/80 flex flex-col items-center justify-center text-white p-2">
                                                    <span class="text-lg font-extrabold">+<?php echo count($gallery) - 3; ?></span>
                                                    <span class="text-[10px] font-bold uppercase tracking-wider">Photos</span>
                                                </div>
                                            <?php else: ?>
                                                <div class="absolute inset-0 bg-kmf-blue/30 opacity-0 group-hover/photo:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-white transform scale-75 group-hover/photo:scale-100 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php if (empty($areas)): ?>
            <div class="bg-gray-50 rounded-2xl p-12 text-center border border-dashed border-gray-300">
                <p class="text-gray-500 font-medium italic">Strategic areas will be detailed here soon.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="kmf-lightbox" class="fixed inset-0 z-[9998] bg-black/95 backdrop-blur-md opacity-0 pointer-events-none transition-opacity duration-300 flex items-center justify-center">
    <!-- Close Button -->
    <button type="button" onclick="closeLightbox()" class="absolute top-6 right-6 p-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white transition-colors z-[9999]" aria-label="Close Gallery">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    <!-- Navigation Prev -->
    <button type="button" onclick="navigateLightbox(-1)" class="absolute left-6 top-1/2 -translate-y-1/2 p-4 rounded-2xl bg-white/10 hover:bg-white/20 text-white transition-colors z-[9999] focus:outline-none" aria-label="Previous Photo">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>

    <!-- Main Media Container -->
    <div class="max-w-5xl max-h-[85vh] w-full px-4 flex flex-col items-center justify-center select-none">
        <img id="lightbox-image" src="" alt="Gallery Image" class="max-h-[75vh] w-auto object-contain rounded-2xl shadow-2xl transition-all duration-300 transform scale-95 opacity-0">
        <!-- Caption / Photo Index -->
        <div class="mt-4 text-center">
            <h4 id="lightbox-title" class="text-white font-extrabold text-lg tracking-tight font-montserrat">Education Gallery</h4>
            <p id="lightbox-counter" class="text-gray-400 text-xs mt-1 font-semibold tracking-wider uppercase">Photo 1 of 5</p>
        </div>
    </div>

    <!-- Navigation Next -->
    <button type="button" onclick="navigateLightbox(1)" class="absolute right-6 top-1/2 -translate-y-1/2 p-4 rounded-2xl bg-white/10 hover:bg-white/20 text-white transition-colors z-[9999] focus:outline-none" aria-label="Next Photo">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
</div>

<script>
    // Gallery data map
    const areaGalleries = <?php echo json_encode($areaPhotos); ?>;
    const areaTitles = <?php 
        $titles = [];
        foreach ($areas as $a) {
            $titles[$a['id']] = $a['title'];
        }
        echo json_encode($titles); 
    ?>;

    let currentAreaId = null;
    let currentPhotoIndex = 0;

    function openAreaLightbox(areaId, photoIndex) {
        currentAreaId = areaId;
        currentPhotoIndex = photoIndex;
        
        const lightbox = document.getElementById('kmf-lightbox');
        const img = document.getElementById('lightbox-image');
        
        // Show Lightbox Backdrop
        lightbox.classList.remove('opacity-0', 'pointer-events-none');
        
        updateLightboxContent();
        
        // Lock scroll
        document.body.style.overflow = 'hidden';
    }

    function updateLightboxContent() {
        const img = document.getElementById('lightbox-image');
        const titleEl = document.getElementById('lightbox-title');
        const counterEl = document.getElementById('lightbox-counter');
        
        const gallery = areaGalleries[currentAreaId] || [];
        if (gallery.length === 0) return;
        
        // Wrap-around boundaries
        if (currentPhotoIndex < 0) currentPhotoIndex = gallery.length - 1;
        if (currentPhotoIndex >= gallery.length) currentPhotoIndex = 0;
        
        const photo = gallery[currentPhotoIndex];
        
        // Animate transition
        img.classList.remove('scale-100', 'opacity-100');
        img.classList.add('scale-95', 'opacity-0');
        
        // Wait for animation out, then change src and animate in
        setTimeout(() => {
            img.src = '<?php echo BASE_URL; ?>' + photo.image_url;
            titleEl.textContent = areaTitles[currentAreaId] + ' Gallery';
            counterEl.textContent = `Photo ${currentPhotoIndex + 1} of ${gallery.length}`;
            
            img.onload = () => {
                img.classList.remove('scale-95', 'opacity-0');
                img.classList.add('scale-100', 'opacity-100');
            };
        }, 150);
    }

    function navigateLightbox(direction) {
        currentPhotoIndex += direction;
        updateLightboxContent();
    }

    function closeLightbox() {
        const lightbox = document.getElementById('kmf-lightbox');
        const img = document.getElementById('lightbox-image');
        
        lightbox.classList.add('opacity-0', 'pointer-events-none');
        img.classList.remove('scale-100', 'opacity-100');
        img.classList.add('scale-95', 'opacity-0');
        
        // Restore scroll
        document.body.style.overflow = '';
    }

    // Keyboard Navigation
    document.addEventListener('keydown', function(event) {
        const lightbox = document.getElementById('kmf-lightbox');
        if (lightbox.classList.contains('opacity-0')) return;
        
        if (event.key === 'Escape') {
            closeLightbox();
        } else if (event.key === 'ArrowRight') {
            navigateLightbox(1);
        } else if (event.key === 'ArrowLeft') {
            navigateLightbox(-1);
        }
    });
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
