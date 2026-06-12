<?php
/**
 * Dynamic Popup Notice Component
 */
$popupEnabled = getSetting('popup_enabled', '0');
if ($popupEnabled !== '1') return;

$title = getSetting('popup_title');
$content = getSetting('popup_content');
$imageUrl = getSetting('popup_image_url');
$ctaText = getSetting('popup_cta_text');
$ctaLink = getSetting('popup_cta_link', '#');
$frequency = getSetting('popup_frequency', 'always');
?>

<div id="site-popup" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 md:p-6 opacity-0 pointer-events-none transition-all duration-500 scale-95">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-kmf-blue-dark/80 backdrop-blur-sm" onclick="closePopup()"></div>
    
    <!-- Modal -->
    <div class="relative w-full max-w-3xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-white/20 transform transition-transform duration-500">
        
        <!-- Image Section (Optional) -->
        <?php if ($imageUrl): ?>
        <div class="md:w-7/12 relative min-h-[300px] md:min-h-full cursor-zoom-in group overflow-hidden" onclick="openLightbox('<?php echo BASE_URL . escape($imageUrl); ?>')">
            <img src="<?php echo BASE_URL . escape($imageUrl); ?>" alt="Notice" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-t from-kmf-blue-dark/60 to-transparent md:hidden"></div>
            <!-- Overlay indicator on hover -->
            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                <span class="bg-black/60 text-white px-4 py-2 rounded-full text-xs font-semibold tracking-wider flex items-center gap-2 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m4-3H6"/></svg>
                    View Full Size
                </span>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Content Section -->
        <div class="flex-1 p-6 md:p-8 lg:p-10 flex flex-col justify-center">
            <button onclick="closePopup()" class="absolute top-6 right-6 p-2 rounded-full bg-slate-100 text-slate-400 hover:text-kmf-orange hover:bg-slate-200 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-xs mb-3 block">Important Notice</span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight leading-tight mb-4">
                <?php echo escape($title); ?>
            </h2>
            <div class="prose prose-slate max-w-none text-slate-500 leading-relaxed text-sm mb-6">
                <?php echo nl2br(escape($content)); ?>
            </div>
            
            <div class="flex justify-start">
                <button onclick="closePopup()" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold px-6 py-3 rounded-xl transition-all text-sm">
                    Dismiss
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Lightbox / Full Size Image Overlay -->
<div id="image-lightbox" class="fixed inset-0 z-[10000] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/95 backdrop-blur-sm cursor-zoom-out" onclick="closeLightbox()"></div>
    
    <!-- Close Button -->
    <button type="button" onclick="closeLightbox()" class="absolute top-6 right-6 p-3 rounded-full bg-white/10 text-white/70 hover:text-white hover:bg-white/20 transition-all z-[10002] cursor-pointer">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    
    <!-- Image -->
    <img id="lightbox-img" src="" alt="Full size notice" class="relative max-w-[90vw] max-h-[90vh] md:max-w-[85vw] md:max-h-[85vh] object-contain rounded-xl shadow-2xl transition-transform duration-300 scale-95 z-[10001]">
</div>

<script>
    const POPUP_STORAGE_KEY = 'kmf_popup_dismissed_v1';
    const FREQUENCY = '<?php echo $frequency; ?>';

    function showPopup() {
        const popup = document.getElementById('site-popup');
        if (!popup) return;

        // Check frequency
        if (FREQUENCY === 'once_per_session') {
            if (sessionStorage.getItem(POPUP_STORAGE_KEY)) return;
        }

        setTimeout(() => {
            popup.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
            popup.classList.add('opacity-100', 'scale-100');
        }, 800); 
    }

    function closePopup() {
        const popup = document.getElementById('site-popup');
        if (!popup) return;

        popup.classList.remove('opacity-100', 'scale-100');
        popup.classList.add('opacity-0', 'pointer-events-none', 'scale-95');

        if (FREQUENCY === 'once_per_session') {
            sessionStorage.setItem(POPUP_STORAGE_KEY, 'true');
        }
    }

    function openLightbox(src) {
        const lightbox = document.getElementById('image-lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        if (!lightbox || !lightboxImg) return;

        lightboxImg.src = src;
        lightbox.classList.remove('opacity-0', 'pointer-events-none');
        lightbox.classList.add('opacity-100');
        setTimeout(() => {
            lightboxImg.classList.remove('scale-95');
            lightboxImg.classList.add('scale-100');
        }, 50);
    }

    function closeLightbox() {
        const lightbox = document.getElementById('image-lightbox');
        const lightboxImg = document.getElementById('lightbox-img');
        if (!lightbox || !lightboxImg) return;

        lightboxImg.classList.remove('scale-100');
        lightboxImg.classList.add('scale-95');
        lightbox.classList.remove('opacity-100');
        lightbox.classList.add('opacity-0', 'pointer-events-none');
    }

    // Close lightbox on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox();
        }
    });

    // Auto-init
    document.addEventListener('DOMContentLoaded', showPopup);
</script>

