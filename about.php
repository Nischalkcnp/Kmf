<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('about');
$pageTitle = $page ? $page['title'] : 'Who We Are';
$metaDescription = $page ? $page['meta_description'] : 'Learn about Kanchhi Maya Tamang Foundation';

$pdo = getDb();
$team     = $pdo->query("SELECT * FROM team WHERE is_active = 1 ORDER BY type, sort_order")->fetchAll();
$partners = $pdo->query("SELECT * FROM partners WHERE is_active = 1 ORDER BY sort_order")->fetchAll();

$boardMembers = array_filter($team, fn($t) => $t['type'] === 'board');
$staffMembers = array_filter($team, fn($t) => $t['type'] === 'staff');

$aboutMedia = $pdo->query("SELECT * FROM gallery WHERE is_active = 1 AND is_about_us = 1 AND category = 'video' ORDER BY sort_order ASC, id DESC")->fetchAll();

// YouTube URL Helpers
if (!function_exists('getYouTubeId')) {
    function getYouTubeId($url) {
        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
        if (preg_match($pattern, $url, $match)) {
            return $match[1];
        }
        return null;
    }
}

if (!function_exists('getYouTubeEmbedUrl')) {
    function getYouTubeEmbedUrl($url) {
        $id = getYouTubeId($url);
        return $id ? "https://www.youtube.com/embed/{$id}?autoplay=1" : '';
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<!-- ═══════════════════════════════════════════════
     HERO BANNER — compact gradient strip
════════════════════════════════════════════════ -->
<section class="relative bg-kmf-blue overflow-hidden py-5 md:py-7">
    <!-- Decorative blobs -->
    <div class="absolute -top-20 -right-20 w-72 h-72 bg-kmf-orange/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <!-- Sub-nav -->
        <div class="flex flex-wrap items-center gap-2 mb-5">
            <a href="<?php echo BASE_URL; ?>about.php"    class="px-4 py-1.5 bg-white text-kmf-blue rounded-xl text-xs font-bold shadow transition-all">Who We Are</a>
            <a href="<?php echo BASE_URL; ?>history.php"  class="px-4 py-1.5 bg-white/10 text-white/80 hover:bg-white/20 rounded-xl text-xs font-semibold transition-all">Our History</a>
            <a href="<?php echo getPageUrl('team'); ?>"     class="px-4 py-1.5 bg-white/10 text-white/80 hover:bg-white/20 rounded-xl text-xs font-semibold transition-all">Meet Our Team</a>
            <a href="<?php echo getPageUrl('partners'); ?>" class="px-4 py-1.5 bg-white/10 text-white/80 hover:bg-white/20 rounded-xl text-xs font-semibold transition-all">Our Partners</a>
        </div>

        <!-- Title + intro -->
        <div class="max-w-3xl">
            <span class="inline-block text-kmf-orange font-bold uppercase tracking-[0.25em] text-xs mb-1.5">Our Story</span>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight tracking-tight mb-2 font-montserrat">
                <?php echo escape($pageTitle); ?>
            </h1>
            <div class="text-white/70 text-sm leading-relaxed font-medium max-w-2xl">
                <?php
                if ($page && !empty(trim($page['content']))) {
                    $plain = strip_tags($page['content']);
                    echo '<p>' . escape(mb_substr($plain, 0, 180)) . (mb_strlen($plain) > 180 ? '…' : '') . '</p>';
                } else {
                    echo '<p>Kanchhi Maya Tamang Foundation (KMF) is dedicated to advancing education, community welfare, and health in Nepal.</p>';
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     VIDEOS GALLERY SECTION
════════════════════════════════════════════════ -->
<?php if (!empty($aboutMedia)): ?>
<section class="py-6 bg-gray-50/50 border-b border-gray-100 overflow-hidden animate-fade-in">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Section Header -->
        <div class="text-center max-w-2xl mx-auto mb-6">
            <span class="inline-block text-kmf-orange font-bold uppercase tracking-[0.25em] text-xs mb-1">Watch in Action</span>
            <h2 class="text-2xl md:text-3xl font-black text-kmf-blue font-montserrat tracking-tight">Our Videos</h2>
            <div class="h-1 w-12 bg-kmf-orange mx-auto mt-2.5 rounded-full"></div>
        </div>

        <!-- Centered Media Cards Container with Larger Thumbnails -->
        <div class="flex flex-wrap justify-center gap-8 max-w-6xl mx-auto">
            <?php foreach ($aboutMedia as $item): 
                $isLocal = strpos($item['video_url'], 'assets/videos/') === 0;
                $srcUrl = $isLocal ? BASE_URL . $item['video_url'] : getYouTubeEmbedUrl($item['video_url']);
                $thumb = $item['image_url'];
            ?>
            <div class="group relative w-full sm:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.5rem)] max-w-md aspect-[16/9] rounded-3xl overflow-hidden bg-slate-900 shadow-md hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 cursor-pointer"
                 data-gallery-item>
                
                <!-- Trigger area for Lightbox -->
                <div class="w-full h-full"
                     data-lightbox-trigger 
                     data-type="video" 
                     data-src="<?php echo escape($srcUrl); ?>" 
                     data-title="<?php echo escape($item['title'] ?: 'Foundation Video'); ?>" 
                     data-project="About Us">
                    
                    <!-- Thumbnail Image -->
                    <img src="<?php echo (strpos($thumb, 'http') === 0) ? $thumb : BASE_URL . $thumb; ?>" 
                         alt="<?php echo escape($item['title']); ?>" 
                         class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110 opacity-95 group-hover:opacity-100">
                    
                    <!-- Play icon overlay for videos -->
                    <div class="absolute inset-0 flex items-center justify-center z-10">
                        <div class="w-14 h-14 rounded-full bg-kmf-orange/95 flex items-center justify-center text-white shadow-xl transform transition-all duration-500 group-hover:scale-110 group-hover:bg-kmf-orange-light">
                            <svg class="w-6 h-6 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>

                    <!-- Category Badge -->
                    <div class="absolute top-4 left-4 z-10">
                        <span class="inline-flex py-1 px-3.5 bg-black/60 backdrop-blur-sm text-white text-[9px] font-black uppercase tracking-widest rounded-full border border-white/10 shadow-sm">
                            <?php echo $isLocal ? 'Local Video' : 'YouTube'; ?>
                        </span>
                    </div>

                    <!-- Info Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                        <h4 class="text-white font-extrabold text-base leading-tight font-montserrat truncate mb-1"><?php echo escape($item['title'] ?: 'Watch Video'); ?></h4>
                        <p class="text-[10px] text-slate-300 font-extrabold uppercase tracking-widest flex items-center gap-1.5">
                            <span>Click to play</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox-modal" class="fixed inset-0 z-[100] hidden bg-black/95 backdrop-blur-md flex items-center justify-center p-4 transition-all duration-300">
    <!-- Close Button -->
    <button id="lightbox-close" class="absolute top-6 right-6 text-white hover:text-kmf-orange transition-colors p-3.5 rounded-full bg-white/5 hover:bg-white/10 border border-white/10 shadow-lg" aria-label="Close Lightbox">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    
    <!-- Prev Button -->
    <button id="lightbox-prev" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 text-white hover:text-kmf-orange transition-all p-3.5 rounded-full bg-white/5 hover:bg-white/10 border border-white/10 shadow-lg hidden md:block" aria-label="Previous">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
    </button>
    
    <!-- Next Button -->
    <button id="lightbox-next" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 text-white hover:text-kmf-orange transition-all p-3.5 rounded-full bg-white/5 hover:bg-white/10 border border-white/10 shadow-lg hidden md:block" aria-label="Next">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
    </button>
    
    <!-- Content Container -->
    <div class="max-w-4xl w-full flex flex-col items-center">
        <!-- Media Target -->
        <div id="lightbox-media-container" class="w-full flex justify-center items-center relative min-h-[300px]">
            <img id="lightbox-image" src="" alt="" class="max-h-[75vh] max-w-full object-contain rounded-3xl shadow-2xl transition-all duration-300 hidden">
            <div id="lightbox-video-container" class="w-full aspect-video hidden max-w-3xl rounded-3xl overflow-hidden shadow-2xl border border-white/10">
                <iframe id="lightbox-iframe" class="w-full h-full border-0" src="" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            </div>
            <video id="lightbox-local-video" class="max-h-[75vh] w-full max-w-3xl rounded-3xl overflow-hidden shadow-2xl border border-white/10 hidden" controls autoplay></video>
        </div>
        <!-- Caption / Text -->
        <div class="text-center mt-6 px-4">
            <h3 id="lightbox-caption" class="text-white font-extrabold text-lg md:text-2xl font-montserrat tracking-tight leading-snug"></h3>
            <p id="lightbox-project" class="text-kmf-orange font-bold text-xs uppercase tracking-[0.2em] mt-2"></p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let activeIndex = -1;
    
    function getVisibleTriggers() {
        return Array.from(document.querySelectorAll('[data-lightbox-trigger]'));
    }
    
    const modal = document.getElementById('lightbox-modal');
    const img = document.getElementById('lightbox-image');
    const videoContainer = document.getElementById('lightbox-video-container');
    const iframe = document.getElementById('lightbox-iframe');
    const localVideo = document.getElementById('lightbox-local-video');
    const caption = document.getElementById('lightbox-caption');
    const projectText = document.getElementById('lightbox-project');

    function openLightbox(index, visibleTriggers) {
        if (index < 0 || index >= visibleTriggers.length) return;
        activeIndex = index;
        const trigger = visibleTriggers[index];
        const type = trigger.getAttribute('data-type');
        const src = trigger.getAttribute('data-src');
        const title = trigger.getAttribute('data-title');
        const project = trigger.getAttribute('data-project');
        
        // Hide elements initially and pause local video stream
        img.classList.add('hidden');
        videoContainer.classList.add('hidden');
        localVideo.classList.add('hidden');
        localVideo.removeAttribute('src');
        localVideo.load();
        iframe.setAttribute('src', '');
        
        if (type === 'photo') {
            img.setAttribute('src', src);
            img.setAttribute('alt', title);
            img.classList.remove('hidden');
        } else if (type === 'video') {
            if (src.includes('assets/videos/')) {
                localVideo.setAttribute('src', src);
                localVideo.classList.remove('hidden');
                localVideo.load();
                localVideo.play().catch(e => console.log("Autoplay blocked:", e));
            } else {
                iframe.setAttribute('src', src);
                videoContainer.classList.remove('hidden');
            }
        }
        
        caption.textContent = title;
        projectText.textContent = project;
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // block parent scroll
    }
    
    function closeLightbox() {
        modal.classList.add('hidden');
        iframe.setAttribute('src', '');
        localVideo.removeAttribute('src');
        localVideo.load();
        document.body.style.overflow = '';
    }
    
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-lightbox-trigger]');
        if (trigger) {
            const visibleTriggers = getVisibleTriggers();
            const idx = visibleTriggers.indexOf(trigger);
            if (idx !== -1) {
                openLightbox(idx, visibleTriggers);
            }
        }
    });
    
    document.getElementById('lightbox-close').addEventListener('click', closeLightbox);
    
    document.getElementById('lightbox-prev').addEventListener('click', (e) => {
        e.stopPropagation();
        const visibleTriggers = getVisibleTriggers();
        if (visibleTriggers.length === 0) return;
        let prevIndex = activeIndex - 1;
        if (prevIndex < 0) prevIndex = visibleTriggers.length - 1;
        openLightbox(prevIndex, visibleTriggers);
    });
    
    document.getElementById('lightbox-next').addEventListener('click', (e) => {
        e.stopPropagation();
        const visibleTriggers = getVisibleTriggers();
        if (visibleTriggers.length === 0) return;
        let nextIndex = activeIndex + 1;
        if (nextIndex >= visibleTriggers.length) nextIndex = 0;
        openLightbox(nextIndex, visibleTriggers);
    });
    
    // Keyboard controls
    document.addEventListener('keydown', (e) => {
        if (modal.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') document.getElementById('lightbox-prev').click();
        if (e.key === 'ArrowRight') document.getElementById('lightbox-next').click();
    });
    
    // Click backdrop to close
    modal.addEventListener('click', (e) => {
        if (e.target === modal || e.target === document.getElementById('lightbox-media-container')) {
            closeLightbox();
        }
    });
});
</script>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════
     FULL CONTENT BLOCK
════════════════════════════════════════════════ -->
<section class="py-8 lg:py-12 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="max-w-3xl mx-auto prose-custom text-gray-600 text-base leading-relaxed">
            <?php
            if ($page && !empty(trim($page['content']))) {
                echo $page['content'];
            } else {
                echo '<p>Kanchhi Maya Tamang Foundation (KMF) is dedicated to advancing education, community welfare, and health in Nepal. Our foundation was born from a desire to create lasting change in the lives of the marginalized and underserved populations of the Himalayan region.</p>
                <p class="mt-4">We believe that every child deserves a quality education, every mother deserves safe healthcare, and every community deserves the opportunity to thrive. Our programs are designed and implemented in close collaboration with local leaders and community members to ensure sustainability and cultural relevance.</p>';
            }
            ?>
        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     MISSION · VISION · GOAL   (icon cards)
════════════════════════════════════════════════ -->
<section class="py-8 lg:py-10 bg-gray-50/70">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <!-- Mission -->
            <div class="group relative bg-white rounded-[1.75rem] p-7 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-400 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-kmf-blue rounded-l-[1.75rem]"></div>
                <div class="w-11 h-11 rounded-xl bg-kmf-blue/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-kmf-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold text-kmf-blue uppercase tracking-widest mb-2">Our Mission</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('mission'))); ?></p>
            </div>

            <!-- Vision -->
            <div class="group relative bg-white rounded-[1.75rem] p-7 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-400 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-kmf-orange rounded-l-[1.75rem]"></div>
                <div class="w-11 h-11 rounded-xl bg-kmf-orange/10 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold text-kmf-blue uppercase tracking-widest mb-2">Our Vision</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('vision'))); ?></p>
            </div>

            <!-- Goal -->
            <div class="group relative bg-white rounded-[1.75rem] p-7 border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-400 overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-green-500 rounded-l-[1.75rem]"></div>
                <div class="w-11 h-11 rounded-xl bg-green-50 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h3 class="text-sm font-extrabold text-kmf-blue uppercase tracking-widest mb-2">Our Goal</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-medium"><?php echo nl2br(escape(getSetting('goal'))); ?></p>
            </div>

        </div>
    </div>
</section>

<!-- ═══════════════════════════════════════════════
     TEAM
════════════════════════════════════════════════ -->
<?php if (!empty($team)): ?>
<section id="team" class="py-8 lg:py-12 bg-white">
    <div class="container mx-auto px-4 lg:px-8">

        <div class="flex items-end justify-between mb-8">
            <div>
                <span class="text-kmf-orange font-bold uppercase tracking-widest text-xs block mb-1">Leadership</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue font-montserrat">Meet Our Team</h2>
            </div>
        </div>

        <?php if (!empty($boardMembers)): ?>
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-5">
                <div class="h-px flex-1 bg-gray-100"></div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2">Board of Directors</span>
                <div class="h-px flex-1 bg-gray-100"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <?php foreach ($boardMembers as $t): ?>
                <div class="group flex items-center gap-4 bg-gray-50/60 hover:bg-white border border-transparent hover:border-gray-100 hover:shadow-lg rounded-2xl p-4 transition-all duration-300">
                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl overflow-hidden shadow-sm border-2 border-white group-hover:scale-105 transition-transform duration-300">
                        <?php if (!empty($t['image_url'])): ?>
                            <img src="<?php echo BASE_URL . escape($t['image_url']); ?>" alt="<?php echo escape($t['name']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-kmf-blue/10 flex items-center justify-center text-kmf-blue font-black text-xl"><?php echo strtoupper(substr($t['name'], 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-kmf-blue text-sm leading-tight truncate"><?php echo escape($t['name']); ?></h4>
                        <?php if (!empty($t['role'])): ?>
                            <p class="text-kmf-orange text-[10px] font-bold uppercase tracking-widest mt-0.5"><?php echo escape($t['role']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($t['bio'])): ?>
                            <p class="text-gray-500 text-xs mt-1 line-clamp-2 leading-relaxed"><?php echo escape($t['bio']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($staffMembers)): ?>
        <div>
            <div class="flex items-center gap-3 mb-5">
                <div class="h-px flex-1 bg-gray-100"></div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2">Our Staff</span>
                <div class="h-px flex-1 bg-gray-100"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                <?php foreach ($staffMembers as $t): ?>
                <div class="group flex items-center gap-4 bg-gray-50/60 hover:bg-white border border-transparent hover:border-gray-100 hover:shadow-lg rounded-2xl p-4 transition-all duration-300">
                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl overflow-hidden shadow-sm border-2 border-white group-hover:scale-105 transition-transform duration-300">
                        <?php if (!empty($t['image_url'])): ?>
                            <img src="<?php echo BASE_URL . escape($t['image_url']); ?>" alt="<?php echo escape($t['name']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full bg-kmf-orange/10 flex items-center justify-center text-kmf-orange font-black text-xl"><?php echo strtoupper(substr($t['name'], 0, 1)); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-kmf-blue text-sm leading-tight truncate"><?php echo escape($t['name']); ?></h4>
                        <?php if (!empty($t['role'])): ?>
                            <p class="text-kmf-orange text-[10px] font-bold uppercase tracking-widest mt-0.5"><?php echo escape($t['role']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($t['bio'])): ?>
                            <p class="text-gray-500 text-xs mt-1 line-clamp-2 leading-relaxed"><?php echo escape($t['bio']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════════════
     PARTNERS
════════════════════════════════════════════════ -->
<?php if (!empty($partners)): ?>
<section id="partners" class="py-8 lg:py-10 bg-gray-50/60 border-t border-gray-100">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="flex items-center gap-3 mb-8">
            <div class="h-px flex-1 bg-gray-200"></div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest px-3">Our Partners</span>
            <div class="h-px flex-1 bg-gray-200"></div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 items-center">
            <?php foreach ($partners as $p): ?>
            <div class="group flex justify-center p-4 grayscale hover:grayscale-0 opacity-60 hover:opacity-100 transition-all duration-400">
                <?php if (!empty($p['link_url'])): ?>
                    <a href="<?php echo escape($p['link_url']); ?>" target="_blank" rel="noopener" class="block transform group-hover:scale-105 transition-transform">
                <?php endif; ?>
                <?php if (!empty($p['logo_url'])): ?>
                    <img src="<?php echo (str_starts_with($p['logo_url'], 'http') ? '' : BASE_URL) . escape($p['logo_url']); ?>" alt="<?php echo escape($p['name']); ?>" class="max-h-12 w-auto object-contain">
                <?php else: ?>
                    <span class="text-gray-400 font-bold text-base group-hover:text-kmf-blue transition-colors"><?php echo escape($p['name']); ?></span>
                <?php endif; ?>
                <?php if (!empty($p['link_url'])): ?></a><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
