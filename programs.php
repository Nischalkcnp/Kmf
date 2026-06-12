<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Our Programs';
$metaDescription = 'Current and completed projects of Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$current = $pdo->query("SELECT * FROM programs WHERE is_active = 1 AND type = 'current' ORDER BY sort_order ASC")->fetchAll();
$completed = $pdo->query("SELECT * FROM programs WHERE is_active = 1 AND type = 'completed' ORDER BY sort_order ASC")->fetchAll();

// Fetch photos for all active programs
$allActivePrograms = array_merge($current, $completed);
$programIds = array_column($allActivePrograms, 'id');
$programPhotos = [];
if (!empty($programIds)) {
    $placeholders = implode(',', array_fill(0, count($programIds), '?'));
    $stmt = $pdo->prepare("SELECT * FROM program_photos WHERE program_id IN ($placeholders) ORDER BY sort_order, id");
    $stmt->execute($programIds);
    $photos = $stmt->fetchAll();
    foreach ($photos as $photo) {
        $programPhotos[$photo['program_id']][] = $photo;
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-4xl mb-12 lg:mb-16">
            <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">Our Programs</p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-kmf-blue mb-6 leading-tight">Programs & Projects</h1>
            <p class="text-lg md:text-xl text-gray-600 font-medium leading-relaxed">We design and implement community-led programs focusing on education infrastructure, sustainable livelihoods, and accessible healthcare.</p>
        </div>

        <div class="mb-20">
            <div class="flex items-center justify-between mb-10 border-b border-gray-100 pb-4">
                <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue">Current Projects</h2>
                <div class="hidden sm:block h-1 flex-1 bg-gray-50 mx-6 rounded-full"></div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                <?php foreach ($current as $p): ?>
                <article class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full text-left">
                    <?php
                        $img = !empty($p['image_url']) ? $p['image_url'] : 'assets/images/program-placeholder.svg';
                    ?>
                    <div class="relative overflow-hidden h-56">
                        <img src="<?php echo (strpos($img, 'http') === 0) ? $img : BASE_URL . escape($img); ?>" alt="<?php echo escape($p['title']); ?>" class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute top-4 left-4">
                            <span class="inline-block py-1.5 px-4 bg-kmf-orange/90 backdrop-blur-sm text-white text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow-sm">Active</span>
                        </div>
                    </div>
                    <div class="p-6 lg:p-8 flex flex-col flex-1">
                        <h3 class="text-xl md:text-2xl font-extrabold text-kmf-blue mb-4 leading-tight group-hover:text-kmf-orange transition-colors"><?php echo escape($p['title']); ?></h3>
                        <p class="text-gray-600 text-sm font-medium leading-relaxed mb-6 flex-1"><?php echo escape($p['excerpt']); ?></p>
                        
                        <?php if (!empty($p['content'])): ?>
                            <div class="pt-6 border-t border-gray-50 mb-4">
                                <div class="prose-custom text-sm text-gray-500 line-clamp-3"><?php echo $p['content']; ?></div>
                            </div>
                        <?php endif; ?>

                        <!-- Gallery Section -->
                        <?php 
                        $gallery = $programPhotos[$p['id']] ?? []; 
                        if (!empty($gallery)): 
                        ?>
                            <div class="mt-4 border-t border-gray-100 pt-4">
                                <h4 class="text-[10px] font-extrabold uppercase tracking-widest text-kmf-blue mb-2.5 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Project Gallery
                                </h4>
                                <div class="grid grid-cols-4 gap-2">
                                    <?php 
                                    $displayCount = min(4, count($gallery));
                                    for ($photoIndex = 0; $photoIndex < $displayCount; $photoIndex++): 
                                        $photo = $gallery[$photoIndex];
                                        $isLast = ($photoIndex === 3 && count($gallery) > 4);
                                        $imgSrc = (strpos($photo['image_url'], 'http') === 0) ? $photo['image_url'] : BASE_URL . $photo['image_url'];
                                    ?>
                                        <div class="relative group/photo overflow-hidden rounded-xl border border-gray-200 aspect-square cursor-pointer hover:shadow-md transition-all duration-300" 
                                             onclick="openProgramLightbox(<?php echo $p['id']; ?>, <?php echo $photoIndex; ?>)">
                                            <img src="<?php echo escape($imgSrc); ?>" alt="<?php echo escape($p['title']); ?> gallery" class="w-full h-full object-cover transform group-hover/photo:scale-110 transition-transform duration-500">
                                            
                                            <?php if ($isLast): ?>
                                                <div class="absolute inset-0 bg-kmf-blue/80 flex flex-col items-center justify-center text-white p-1">
                                                    <span class="text-sm font-extrabold">+<?php echo count($gallery) - 3; ?></span>
                                                    <span class="text-[8px] font-bold uppercase tracking-wider">Photos</span>
                                                </div>
                                            <?php else: ?>
                                                <div class="absolute inset-0 bg-kmf-blue/20 opacity-0 group-hover/photo:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white transform scale-75 group-hover/photo:scale-100 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php if (empty($current)): ?>
                <div class="bg-gray-50 rounded-3xl p-12 text-center border border-dashed border-gray-300">
                    <p class="text-gray-500 font-medium italic">No current projects are active at the moment.</p>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <div class="flex items-center justify-between mb-10 border-b border-gray-100 pb-4">
                <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue">Completed Projects</h2>
                <div class="hidden sm:block h-1 flex-1 bg-gray-50 mx-6 rounded-full"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($completed as $p): ?>
                <article class="group bg-gray-50 rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col h-full opacity-80 hover:opacity-100 text-left">
                    <?php
                        $img = !empty($p['image_url']) ? $p['image_url'] : 'assets/images/program-placeholder.svg';
                    ?>
                    <div class="relative overflow-hidden h-48 filter grayscale group-hover:grayscale-0 transition-all duration-500">
                        <img src="<?php echo (strpos($img, 'http') === 0) ? $img : BASE_URL . escape($img); ?>" alt="<?php echo escape($p['title']); ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6 lg:p-8 flex flex-col flex-1">
                        <h3 class="text-lg md:text-xl font-extrabold text-kmf-blue mb-3 leading-tight"><?php echo escape($p['title']); ?></h3>
                        <p class="text-gray-500 text-sm font-medium leading-relaxed mb-4"><?php echo escape($p['excerpt']); ?></p>

                        <!-- Gallery Section -->
                        <?php 
                        $gallery = $programPhotos[$p['id']] ?? []; 
                        if (!empty($gallery)): 
                        ?>
                            <div class="mt-auto border-t border-gray-200 pt-4">
                                <h4 class="text-[10px] font-extrabold uppercase tracking-widest text-kmf-blue mb-2.5 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Project Gallery
                                </h4>
                                <div class="grid grid-cols-4 gap-2">
                                    <?php 
                                    $displayCount = min(4, count($gallery));
                                    for ($photoIndex = 0; $photoIndex < $displayCount; $photoIndex++): 
                                        $photo = $gallery[$photoIndex];
                                        $isLast = ($photoIndex === 3 && count($gallery) > 4);
                                        $imgSrc = (strpos($photo['image_url'], 'http') === 0) ? $photo['image_url'] : BASE_URL . $photo['image_url'];
                                    ?>
                                        <div class="relative group/photo overflow-hidden rounded-xl border border-gray-200 aspect-square cursor-pointer hover:shadow-md transition-all duration-300" 
                                             onclick="openProgramLightbox(<?php echo $p['id']; ?>, <?php echo $photoIndex; ?>)">
                                            <img src="<?php echo escape($imgSrc); ?>" alt="<?php echo escape($p['title']); ?> gallery" class="w-full h-full object-cover transform group-hover/photo:scale-110 transition-transform duration-500">
                                            
                                            <?php if ($isLast): ?>
                                                <div class="absolute inset-0 bg-kmf-blue/80 flex flex-col items-center justify-center text-white p-1">
                                                    <span class="text-sm font-extrabold">+<?php echo count($gallery) - 3; ?></span>
                                                    <span class="text-[8px] font-bold uppercase tracking-wider">Photos</span>
                                                </div>
                                            <?php else: ?>
                                                <div class="absolute inset-0 bg-kmf-blue/20 opacity-0 group-hover/photo:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white transform scale-75 group-hover/photo:scale-100 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php if (empty($completed)): ?>
                <p class="text-gray-400 font-medium italic">Completed project history will be listed here.</p>
            <?php endif; ?>
        </div>
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
            <h4 id="lightbox-title" class="text-white font-extrabold text-lg tracking-tight font-montserrat">Program Gallery</h4>
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
    const programGalleries = <?php echo json_encode($programPhotos); ?>;
    const programTitles = <?php 
        $titles = [];
        foreach ($allActivePrograms as $p) {
            $titles[$p['id']] = $p['title'];
        }
        echo json_encode($titles); 
    ?>;

    let currentProgramId = null;
    let currentPhotoIndex = 0;

    function openProgramLightbox(programId, photoIndex) {
        currentProgramId = programId;
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
        
        const gallery = programGalleries[currentProgramId] || [];
        if (gallery.length === 0) return;
        
        // Wrap-around boundaries
        if (currentPhotoIndex < 0) currentPhotoIndex = gallery.length - 1;
        if (currentPhotoIndex >= gallery.length) currentPhotoIndex = 0;
        
        const photo = gallery[currentPhotoIndex];
        const path = photo.image_url;
        const finalSrc = (path.indexOf('http') === 0) ? path : '<?php echo BASE_URL; ?>' + path;
        
        // Animate transition
        img.classList.remove('scale-100', 'opacity-100');
        img.classList.add('scale-95', 'opacity-0');
        
        // Wait for animation out, then change src and animate in
        setTimeout(() => {
            img.src = finalSrc;
            titleEl.textContent = programTitles[currentProgramId] + ' Gallery';
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
