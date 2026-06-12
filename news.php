<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'News & Media';
$metaDescription = 'Latest news, updates, photos, and videos from Kanchhi Maya Tamang Foundation';

$pdo = getDb();

// Active tab selection
$activeTab = in_array($_GET['tab'] ?? '', ['news', 'gallery']) ? $_GET['tab'] : 'gallery';

// Helper to extract YouTube ID
function getYouTubeId($url) {
    $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
    if (preg_match($pattern, $url, $match)) {
        return $match[1];
    }
    return null;
}

// Helper to get YouTube Embed URL
function getYouTubeEmbedUrl($url) {
    $id = getYouTubeId($url);
    return $id ? "https://www.youtube.com/embed/{$id}?autoplay=1" : '';
}

if ($activeTab === 'news') {
    $news = $pdo->query("SELECT * FROM news WHERE is_active = 1 ORDER BY published_at DESC")->fetchAll();
    $popupEnabled = getSetting('popup_enabled', '0');
    $popupTitle = getSetting('popup_title');
    $popupContent = getSetting('popup_content');
    $popupImageUrl = getSetting('popup_image_url');
} else {
    // Fetch projects that have active media items
    $galleryProjects = $pdo->query("
        SELECT DISTINCT p.* 
        FROM programs p 
        INNER JOIN gallery g ON p.id = g.program_id 
        WHERE p.is_active = 1 AND g.is_active = 1 
        ORDER BY p.type ASC, p.sort_order ASC, p.id DESC
    ")->fetchAll();

    // Fetch all active media items
    $galleryItems = $pdo->query("
        SELECT * FROM gallery 
        WHERE is_active = 1 
        ORDER BY sort_order ASC, id DESC
    ")->fetchAll();

    // Group media items by project ID
    $projectMedia = [];
    foreach ($galleryItems as $item) {
        $pId = $item['program_id'];
        if ($pId !== null) {
            $projectMedia[$pId][] = $item;
        }
    }
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-8 md:py-12 lg:py-16 bg-white">
    <div class="container mx-auto px-4 lg:px-8">
        <!-- Header Section -->
        <div class="max-w-4xl mb-10 lg:mb-12">
            <p class="text-kmf-orange font-bold uppercase tracking-widest text-xs md:text-sm mb-2">News & Media</p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-kmf-blue mb-6 leading-tight">Latest from the Foundation</h1>
            <p class="text-lg md:text-xl text-gray-600 font-medium leading-relaxed">Stay updated on our local activities, stories of change, and see our work in action through our project media gallery.</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-gray-200 mb-12 overflow-x-auto whitespace-nowrap scrollbar-hide">
            <a href="?tab=news" class="py-4 px-6 md:px-8 font-bold text-sm md:text-base border-b-2 transition-all flex items-center gap-2 <?php echo $activeTab === 'news' ? 'border-kmf-orange text-kmf-orange font-extrabold' : 'border-transparent text-gray-500 hover:text-kmf-blue hover:border-gray-300'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z M14 2v6h6 m-3 5H7m10 3H7m10 3H7"/></svg>
                News & Updates
            </a>
            <a href="?tab=gallery" class="py-4 px-6 md:px-8 font-bold text-sm md:text-base border-b-2 transition-all flex items-center gap-2 <?php echo $activeTab === 'gallery' ? 'border-kmf-orange text-kmf-orange font-extrabold' : 'border-transparent text-gray-500 hover:text-kmf-blue hover:border-gray-300'; ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Project Media Gallery
            </a>
        </div>

        <?php if ($activeTab === 'news'): ?>
            <!-- News & Updates Tab -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 animate-fade-in">
                <?php if ($popupEnabled === '1' && !empty($popupTitle)): ?>
                <article class="group bg-slate-50 rounded-3xl overflow-hidden border border-kmf-orange/20 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full relative ring-2 ring-kmf-orange/10">
                    <?php
                        $popupImg = !empty($popupImageUrl) ? $popupImageUrl : 'assets/images/news-placeholder.svg';
                    ?>
                    <div class="relative overflow-hidden h-56 <?php echo !empty($popupImageUrl) ? 'cursor-zoom-in' : ''; ?>" <?php echo !empty($popupImageUrl) ? 'onclick="openLightbox(\'' . BASE_URL . escape($popupImg) . '\')"' : ''; ?>>
                        <img src="<?php echo BASE_URL . escape($popupImg); ?>" alt="<?php echo escape($popupTitle); ?>" class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute top-4 left-4">
                            <span class="inline-block py-1.5 px-4 bg-kmf-orange text-white text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow-sm">Announcement</span>
                        </div>
                        <?php if (!empty($popupImageUrl)): ?>
                        <!-- Overlay indicator on hover for images -->
                        <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <span class="bg-black/60 text-white px-4 py-2 rounded-full text-xs font-semibold tracking-wider flex items-center gap-2 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m4-3H6"/></svg>
                                View Full Size
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-6 lg:p-8 flex flex-col flex-1">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2.5 h-2.5 rounded-full bg-kmf-orange animate-pulse"></span>
                            <p class="text-xs font-black uppercase tracking-wider text-kmf-orange">Active Notice</p>
                        </div>
                        <h2 class="text-xl md:text-2xl font-extrabold text-kmf-blue mb-4 leading-tight group-hover:text-kmf-orange transition-colors"><?php echo escape($popupTitle); ?></h2>
                        <p class="text-gray-600 text-sm font-medium leading-relaxed mb-6 flex-1"><?php echo nl2br(escape($popupContent)); ?></p>
                    </div>
                </article>
                <?php endif; ?>

                <?php foreach ($news as $n): ?>
                <article id="news-<?php echo (int)$n['id']; ?>" class="group scroll-mt-24 bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full">
                    <?php
                        $img = !empty($n['image_url']) ? $n['image_url'] : 'assets/images/news-placeholder.svg';
                    ?>
                    <div class="relative overflow-hidden h-56">
                        <img src="<?php echo BASE_URL . escape($img); ?>" alt="<?php echo escape($n['title']); ?>" class="w-full h-full object-cover transform transition-transform duration-500 group-hover:scale-110">
                        <div class="absolute top-4 left-4">
                            <span class="inline-block py-1.5 px-4 bg-white/90 backdrop-blur-sm text-kmf-blue text-[10px] font-extrabold uppercase tracking-widest rounded-full shadow-sm">News</span>
                        </div>
                    </div>
                    <div class="p-6 lg:p-8 flex flex-col flex-1">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-4 h-4 text-kmf-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-sm font-bold text-gray-400"><?php echo formatDate($n['published_at'], 'd M, Y'); ?></p>
                        </div>
                        <h2 class="text-xl md:text-2xl font-extrabold text-kmf-blue mb-4 leading-tight group-hover:text-kmf-orange transition-colors"><?php echo escape($n['title']); ?></h2>
                        <p class="text-gray-600 text-sm font-medium leading-relaxed mb-6 flex-1"><?php echo escape($n['excerpt']); ?></p>
                        
                        <?php if (!empty($n['content']) || !empty($n['link_url'])): ?>
                            <div class="pt-6 border-t border-gray-50 mt-auto flex flex-wrap items-center justify-between gap-4">
                                <?php if (!empty($n['content'])): ?>
                                    <button onclick="const contentDiv = this.closest('.mt-auto').querySelector('.prose-custom'); contentDiv.classList.toggle('hidden'); this.querySelector('span').textContent = contentDiv.classList.contains('hidden') ? 'Read More' : 'Read Less'" class="flex items-center gap-2 text-kmf-orange font-bold text-sm group/btn">
                                        <span>Read More</span>
                                        <svg class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                    </button>
                                <?php endif; ?>

                                <?php if (!empty($n['link_url'])): ?>
                                    <a href="<?php echo escape($n['link_url']); ?>" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1.5 text-slate-500 hover:text-kmf-orange font-semibold text-sm transition-colors group/link">
                                        <svg class="w-4 h-4 text-gray-400 group-hover/link:text-kmf-orange transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span><?php echo escape($n['link_text'] ?: 'Related Link'); ?></span>
                                    </a>
                                <?php endif; ?>

                                <?php if (!empty($n['content'])): ?>
                                    <div class="prose-custom text-sm mt-4 text-gray-600 hidden transition-all duration-300 w-full"><?php echo $n['content']; ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

            <?php if (empty($news) && ($popupEnabled !== '1' || empty($popupTitle))): ?>
                <div class="bg-gray-50 rounded-2xl p-12 text-center border border-dashed border-gray-300">
                    <p class="text-gray-500 font-medium italic">We'll be sharing news and updates here soon.</p>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <!-- Project Media Gallery Tab -->
            <div class="space-y-16 md:space-y-24 animate-fade-in">
                <?php if (empty($galleryProjects)): ?>
                    <div class="bg-gray-50 rounded-3xl p-16 text-center border border-dashed border-gray-300 max-w-xl mx-auto">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-gray-500 font-bold text-lg mb-1">No Project Media Available</p>
                        <p class="text-gray-400 text-sm">Photos and videos of our field projects will be published here shortly.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($galleryProjects as $project): 
                        $pId = $project['id'];
                        $mediaItems = $projectMedia[$pId] ?? [];
                        if (empty($mediaItems)) continue;
                    ?>
                    <div class="scroll-mt-28" id="project-gallery-<?php echo $pId; ?>">
                        <!-- Project Header Banner -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between border-b border-gray-100 pb-4 mb-8">
                            <div class="space-y-1">
                                <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue tracking-tight font-montserrat"><?php echo escape($project['title']); ?></h2>
                                <p class="text-gray-500 text-sm font-medium leading-relaxed max-w-2xl"><?php echo escape($project['excerpt']); ?></p>
                            </div>
                            
                            <div class="mt-4 md:mt-0 flex-shrink-0">
                                <?php if ($project['type'] === 'completed'): ?>
                                    <span class="inline-flex items-center gap-1.5 py-2 px-5 bg-gray-100 text-gray-700 text-xs font-black uppercase tracking-wider rounded-2xl shadow-sm border border-gray-200">
                                        <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                                        Concluded <?php echo !empty($project['conclude_date']) ? ': ' . formatDate($project['conclude_date'], 'M Y') : ''; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 py-2 px-5 bg-green-50 text-green-700 text-xs font-black uppercase tracking-wider rounded-2xl shadow-sm border border-green-100">
                                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                        Ongoing Project
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Media Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            <?php foreach ($mediaItems as $item): 
                                $isVid = $item['category'] === 'video';
                                $srcUrl = $isVid ? getYouTubeEmbedUrl($item['video_url']) : BASE_URL . $item['image_url'];
                                $thumb = $item['image_url'];
                            ?>
                            <div class="group relative aspect-[4/3] rounded-3xl overflow-hidden bg-slate-900 shadow-sm hover:shadow-xl transition-all duration-500 cursor-pointer" 
                                 data-lightbox-trigger 
                                 data-type="<?php echo $item['category']; ?>" 
                                 data-src="<?php echo escape($srcUrl); ?>" 
                                 data-title="<?php echo escape($item['title'] ?: $project['title']); ?>" 
                                 data-project="<?php echo escape($project['title']); ?>">
                                
                                <!-- Thumbnail Image -->
                                <img src="<?php echo (strpos($thumb, 'http') === 0) ? $thumb : BASE_URL . $thumb; ?>" 
                                     alt="<?php echo escape($item['title'] ?: $project['title']); ?>" 
                                     class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110 opacity-95 group-hover:opacity-100">
                                
                                <!-- Play icon for videos -->
                                <?php if ($isVid): ?>
                                    <div class="absolute inset-0 flex items-center justify-center z-10">
                                        <div class="w-14 h-14 rounded-full bg-kmf-orange/95 flex items-center justify-center text-white shadow-xl transform transition-all duration-500 group-hover:scale-110 group-hover:bg-kmf-orange-light">
                                            <svg class="w-6 h-6 fill-current ml-0.5" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <!-- Category Badge -->
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="inline-flex py-1 px-3.5 bg-black/60 backdrop-blur-sm text-white text-[9px] font-black uppercase tracking-widest rounded-full border border-white/10 shadow-sm">
                                        <?php echo escape($item['category']); ?>
                                    </span>
                                </div>

                                <!-- Hover Info Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                                    <h4 class="text-white font-extrabold text-base leading-tight font-montserrat truncate mb-1"><?php echo escape($item['title'] ?: 'View Media'); ?></h4>
                                    <p class="text-[10px] text-slate-300 font-extrabold uppercase tracking-widest flex items-center gap-1.5">
                                        <span>Click to open</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

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
                    </div>
                    <!-- Caption / Text -->
                    <div class="text-center mt-6 px-4">
                        <h3 id="lightbox-caption" class="text-white font-extrabold text-lg md:text-2xl font-montserrat tracking-tight leading-snug"></h3>
                        <p id="lightbox-project" class="text-kmf-orange font-bold text-xs uppercase tracking-[0.2em] mt-2"></p>
                    </div>
                </div>
            </div>

            <!-- Lightbox Script -->
            <script>
            document.addEventListener('DOMContentLoaded', () => {
                let activeIndex = -1;
                const triggers = Array.from(document.querySelectorAll('[data-lightbox-trigger]'));
                
                const modal = document.getElementById('lightbox-modal');
                const img = document.getElementById('lightbox-image');
                const videoContainer = document.getElementById('lightbox-video-container');
                const iframe = document.getElementById('lightbox-iframe');
                const caption = document.getElementById('lightbox-caption');
                const projectText = document.getElementById('lightbox-project');
                
                if (triggers.length === 0) return;

                function openLightbox(index) {
                    if (index < 0 || index >= triggers.length) return;
                    activeIndex = index;
                    const trigger = triggers[index];
                    const type = trigger.getAttribute('data-type');
                    const src = trigger.getAttribute('data-src');
                    const title = trigger.getAttribute('data-title');
                    const project = trigger.getAttribute('data-project');
                    
                    // Hide elements initially
                    img.classList.add('hidden');
                    videoContainer.classList.add('hidden');
                    iframe.setAttribute('src', '');
                    
                    if (type === 'photo') {
                        img.setAttribute('src', src);
                        img.setAttribute('alt', title);
                        img.classList.remove('hidden');
                    } else if (type === 'video') {
                        iframe.setAttribute('src', src);
                        videoContainer.classList.remove('hidden');
                    }
                    
                    caption.textContent = title;
                    projectText.textContent = project;
                    
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // block parent scroll
                }
                
                function closeLightbox() {
                    modal.classList.add('hidden');
                    iframe.setAttribute('src', '');
                    document.body.style.overflow = '';
                }
                
                triggers.forEach((trigger, idx) => {
                    trigger.addEventListener('click', () => openLightbox(idx));
                });
                
                document.getElementById('lightbox-close').addEventListener('click', closeLightbox);
                
                document.getElementById('lightbox-prev').addEventListener('click', (e) => {
                    e.stopPropagation();
                    let prevIndex = activeIndex - 1;
                    if (prevIndex < 0) prevIndex = triggers.length - 1;
                    openLightbox(prevIndex);
                });
                
                document.getElementById('lightbox-next').addEventListener('click', (e) => {
                    e.stopPropagation();
                    let nextIndex = activeIndex + 1;
                    if (nextIndex >= triggers.length) nextIndex = 0;
                    openLightbox(nextIndex);
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
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
