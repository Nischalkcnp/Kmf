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
    <div class="relative w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row border border-white/20 transform transition-transform duration-500">
        
        <!-- Image Section (Optional) -->
        <?php if ($imageUrl): ?>
        <div class="md:w-5/12 relative min-h-[300px] md:min-h-full">
            <img src="<?php echo BASE_URL . escape($imageUrl); ?>" alt="Notice" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-kmf-blue-dark/60 to-transparent md:hidden"></div>
        </div>
        <?php endif; ?>
        
        <!-- Content Section -->
        <div class="flex-1 p-8 md:p-12 lg:p-16 flex flex-col justify-center">
            <button onclick="closePopup()" class="absolute top-6 right-6 p-2 rounded-full bg-slate-100 text-slate-400 hover:text-kmf-orange hover:bg-slate-200 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            
            <span class="text-kmf-orange font-bold uppercase tracking-[0.2em] text-xs mb-4 block">Important Notice</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-kmf-blue font-montserrat tracking-tight leading-tight mb-6">
                <?php echo escape($title); ?>
            </h2>
            <div class="prose prose-slate max-w-none text-slate-500 leading-relaxed mb-10">
                <?php echo nl2br(escape($content)); ?>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?php echo escape($ctaLink); ?>" class="inline-flex items-center justify-center bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold px-8 py-4 rounded-2xl shadow-xl shadow-kmf-orange/20 transition-all duration-300 transform hover:-translate-y-1">
                    <?php echo escape($ctaText ?: 'Learn More'); ?>
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <button onclick="closePopup()" class="inline-flex items-center justify-center bg-slate-100 hover:bg-slate-200 text-slate-500 font-bold px-8 py-4 rounded-2xl transition-all">
                    Dismiss
                </button>
            </div>
        </div>
    </div>
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

    // Auto-init
    document.addEventListener('DOMContentLoaded', showPopup);
</script>
