<?php
require_once dirname(__DIR__) . '/config/config.php';
requirePermission('manage_settings');
$adminTitle = 'Settings';

$pdo = getDb();
$keys = [
    'site_name','site_tagline','logo_url','email','phone','address',
    'mission','vision','goal','facebook','twitter','linkedin','youtube', 'instagram',
    'hero_image_1', 'hero_image_2', 'hero_image_3', 'hero_image_4',
    'hero_badge', 'hero_title', 'hero_subtitle',
    'popup_enabled', 'popup_title', 'popup_content', 'popup_image_url', 'popup_cta_text', 'popup_cta_link', 'popup_frequency',
    'president_enabled', 'president_name', 'president_role', 'president_image_url', 'president_message'
];
$settings = [];
foreach ($keys as $k) {
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->execute([$k]);
    $settings[$k] = $stmt->fetchColumn() ?: '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && validateCsrf()) {
    $image_keys = ['logo_url', 'hero_image_1', 'hero_image_2', 'hero_image_3', 'hero_image_4', 'popup_image_url', 'president_image_url'];
    
    foreach ($keys as $k) {
        if (in_array($k, $image_keys)) {
            // Handle file upload
            $file_key = $k . '_file';
            $v = handleImageUpload($file_key, 'settings', $_POST[$k] ?? '');
        } else {
            $v = trim($_POST[$k] ?? '');
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO settings (setting_key, setting_value) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = NOW()
        ");
        $stmt->execute([$k, $v, $v]);
    }
    redirect(BASE_URL . 'admin/settings.php?updated=1');
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h2 class="text-3xl font-extrabold text-kmf-blue font-montserrat tracking-tight">System Settings</h2>
        <p class="text-slate-400 text-sm font-medium mt-1">Configure global website details and social links</p>
    </div>
    <?php if (isset($_GET['updated'])): ?>
    <div class="flex items-center gap-3 bg-green-50 px-6 py-3 rounded-2xl border border-green-100 animate-bounce">
        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <p class="text-sm font-bold text-green-600">Settings saved successfully</p>
    </div>
    <?php endif; ?>
</div>

<form method="post" enctype="multipart/form-data" class="space-y-12">
    <?php echo csrfField(); ?>
    
    <!-- Identity Section -->
    <section>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-kmf-blue/10 flex items-center justify-center text-kmf-blue">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-kmf-blue uppercase tracking-wider text-sm">Site Identity</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Website Name</label>
                <input type="text" name="site_name" value="<?php echo escape($settings['site_name']); ?>" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Tagline / Motto</label>
                <input type="text" name="site_tagline" value="<?php echo escape($settings['site_tagline']); ?>" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2 md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Website Logo</label>
                <?php if (!empty($settings['logo_url'])): ?>
                    <img src="<?php echo BASE_URL . escape($settings['logo_url']); ?>" class="h-16 w-auto mb-2 rounded border">
                <?php endif; ?>
                <input type="hidden" name="logo_url" value="<?php echo escape($settings['logo_url']); ?>">
                <input type="file" name="logo_url_file" class="w-full text-sm">
            </div>
        </div>
    </section>

    <!-- Communication Section -->
    <section>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-kmf-orange/10 flex items-center justify-center text-kmf-orange">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0 L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-kmf-blue uppercase tracking-wider text-sm">Communication & Address</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Contact Email</label>
                <input type="email" name="email" value="<?php echo escape($settings['email']); ?>" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Phone Number</label>
                <input type="text" name="phone" value="<?php echo escape($settings['phone']); ?>" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2 md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Physical Address</label>
                <textarea name="address" rows="3" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue resize-none"><?php echo escape($settings['address']); ?></textarea>
            </div>
        </div>
    </section>

    <!-- Content Pillars Section -->
    <section>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
            <h3 class="text-xl font-bold text-kmf-blue uppercase tracking-wider text-sm">Strategic Foundations</h3>
        </div>
        
        <div class="grid grid-cols-1 gap-6 p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Our Mission</label>
                <textarea name="mission" rows="2" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue resize-none"><?php echo escape($settings['mission']); ?></textarea>
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Our Vision</label>
                <textarea name="vision" rows="2" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue resize-none"><?php echo escape($settings['vision']); ?></textarea>
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Our Goal</label>
                <textarea name="goal" rows="2" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue resize-none"><?php echo escape($settings['goal']); ?></textarea>
            </div>
        </div>
    </section>
    
    <!-- Popup Notice Section -->
    <section>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-kmf-orange/10 flex items-center justify-center text-kmf-orange">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <h3 class="text-xl font-bold text-kmf-blue uppercase tracking-wider text-sm">Popup Notification</h3>
        </div>
        
        <div class="grid grid-cols-1 gap-6 p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
            <div class="flex items-center justify-between p-6 bg-white rounded-2xl border border-slate-100">
                <div>
                    <h4 class="text-sm font-bold text-kmf-blue">Enable Popup Notice</h4>
                    <p class="text-[10px] text-slate-400 font-medium">Toggle the visibility of the homepage announcement popup</p>
                </div>
                <select name="popup_enabled" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-kmf-orange outline-none font-bold text-sm text-kmf-blue">
                    <option value="1" <?php echo $settings['popup_enabled'] == '1' ? 'selected' : ''; ?>>Enabled</option>
                    <option value="0" <?php echo $settings['popup_enabled'] == '0' ? 'selected' : ''; ?>>Disabled</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Popup Title</label>
                    <input type="text" name="popup_title" value="<?php echo escape($settings['popup_title']); ?>" 
                        class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Display Frequency</label>
                    <select name="popup_frequency" class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
                        <option value="always" <?php echo $settings['popup_frequency'] == 'always' ? 'selected' : ''; ?>>Always Show</option>
                        <option value="once_per_session" <?php echo $settings['popup_frequency'] == 'once_per_session' ? 'selected' : ''; ?>>Once Per Session</option>
                    </select>
                </div>
                <div class="space-y-2 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Notice Content</label>
                    <textarea name="popup_content" rows="3" 
                        class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue resize-none"><?php echo escape($settings['popup_content']); ?></textarea>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Popup Image (Optional)</label>
                    <?php if (!empty($settings['popup_image_url'])): ?>
                        <img src="<?php echo BASE_URL . escape($settings['popup_image_url']); ?>" class="h-16 w-auto mb-2 rounded border">
                    <?php endif; ?>
                    <input type="hidden" name="popup_image_url" value="<?php echo escape($settings['popup_image_url']); ?>">
                    <input type="file" name="popup_image_url_file" class="w-full text-sm">
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">CTA Button Text</label>
                    <input type="text" name="popup_cta_text" value="<?php echo escape($settings['popup_cta_text']); ?>" placeholder="Learn More"
                        class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
                </div>
                <div class="space-y-2 md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">CTA Link Target</label>
                    <input type="text" name="popup_cta_link" value="<?php echo escape($settings['popup_cta_link']); ?>" placeholder="/about.php"
                        class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
                </div>
            </div>
        </div>
    </section>

    <!-- Hero Content Section -->
    <section>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-kmf-blue uppercase tracking-wider text-sm">Hero Section Content</h3>
        </div>
        
        <div class="grid grid-cols-1 gap-6 p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Hero Badge Text</label>
                <input type="text" name="hero_badge" value="<?php echo escape($settings['hero_badge']); ?>" placeholder="Empowering Nepal since 2024"
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Hero Main Title (HTML allowed for line breaks/colors)</label>
                <textarea name="hero_title" rows="3" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue resize-none"><?php echo escape($settings['hero_title']); ?></textarea>
                <p class="text-[10px] text-slate-400 mt-1 pl-1">Suggestion: Use &lt;br&gt; for breaks and &lt;span class="text-kmf-orange"&gt;...&lt;/span&gt; for accents.</p>
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Hero Subtitle</label>
                <textarea name="hero_subtitle" rows="3" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue resize-none"><?php echo escape($settings['hero_subtitle']); ?></textarea>
            </div>
        </div>
    </section>

    <!-- Hero Background Photos Section -->
    <section>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-xl font-bold text-kmf-blue uppercase tracking-wider text-sm">Hero Background Photos</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Hero Image 1</label>
                <?php if (!empty($settings['hero_image_1'])): ?>
                    <img src="<?php echo BASE_URL . escape($settings['hero_image_1']); ?>" class="h-16 w-auto mb-2 rounded border">
                <?php endif; ?>
                <input type="hidden" name="hero_image_1" value="<?php echo escape($settings['hero_image_1']); ?>">
                <input type="file" name="hero_image_1_file" class="w-full text-sm">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Hero Image 2</label>
                <?php if (!empty($settings['hero_image_2'])): ?>
                    <img src="<?php echo BASE_URL . escape($settings['hero_image_2']); ?>" class="h-16 w-auto mb-2 rounded border">
                <?php endif; ?>
                <input type="hidden" name="hero_image_2" value="<?php echo escape($settings['hero_image_2']); ?>">
                <input type="file" name="hero_image_2_file" class="w-full text-sm">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Hero Image 3</label>
                <?php if (!empty($settings['hero_image_3'])): ?>
                    <img src="<?php echo BASE_URL . escape($settings['hero_image_3']); ?>" class="h-16 w-auto mb-2 rounded border">
                <?php endif; ?>
                <input type="hidden" name="hero_image_3" value="<?php echo escape($settings['hero_image_3']); ?>">
                <input type="file" name="hero_image_3_file" class="w-full text-sm">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Hero Image 4</label>
                <?php if (!empty($settings['hero_image_4'])): ?>
                    <img src="<?php echo BASE_URL . escape($settings['hero_image_4']); ?>" class="h-16 w-auto mb-2 rounded border">
                <?php endif; ?>
                <input type="hidden" name="hero_image_4" value="<?php echo escape($settings['hero_image_4']); ?>">
                <input type="file" name="hero_image_4_file" class="w-full text-sm">
            </div>
        </div>
    </section>

    <!-- President Message Section -->
    <section>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-teal-100 flex items-center justify-center text-teal-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </div>
            <h3 class="text-xl font-bold text-kmf-blue uppercase tracking-wider text-sm">Message From President</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
            <div class="flex items-center justify-between p-6 bg-white rounded-2xl border border-slate-100 md:col-span-2">
                <div>
                    <h4 class="text-sm font-bold text-kmf-blue">Enable President Message Section</h4>
                    <p class="text-[10px] text-slate-400 font-medium">Toggle visibility of the "Message from our President" on the homepage</p>
                </div>
                <select name="president_enabled" class="px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-kmf-orange outline-none font-bold text-sm text-kmf-blue">
                    <option value="1" <?php echo ($settings['president_enabled'] ?? '1') == '1' ? 'selected' : ''; ?>>Enabled</option>
                    <option value="0" <?php echo ($settings['president_enabled'] ?? '1') == '0' ? 'selected' : ''; ?>>Disabled</option>
                </select>
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">President's Name</label>
                <input type="text" name="president_name" value="<?php echo escape($settings['president_name'] ?? ''); ?>" placeholder="Dr. Ram Bahadur Tamang"
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Role/Title</label>
                <input type="text" name="president_role" value="<?php echo escape($settings['president_role'] ?? ''); ?>" placeholder="Chairperson (President)"
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2 md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">President's Photo</label>
                <?php if (!empty($settings['president_image_url'])): ?>
                    <img src="<?php echo BASE_URL . escape($settings['president_image_url']); ?>" class="h-20 w-auto mb-2 rounded border">
                <?php endif; ?>
                <input type="hidden" name="president_image_url" value="<?php echo escape($settings['president_image_url'] ?? ''); ?>">
                <input type="file" name="president_image_url_file" class="w-full text-sm">
            </div>
            <div class="space-y-2 md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Message Content (HTML allowed)</label>
                <textarea name="president_message" rows="5" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue resize-none"><?php echo escape($settings['president_message'] ?? ''); ?></textarea>
            </div>
        </div>
    </section>

    <!-- Social Connect Section -->
    <section>
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.103 1.103"/></svg>
            </div>
            <h3 class="text-xl font-bold text-kmf-blue uppercase tracking-wider text-sm">Social Media Links</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8 bg-slate-50 rounded-[2rem] border border-slate-100">
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Facebook URL</label>
                <input type="url" name="facebook" value="<?php echo escape($settings['facebook']); ?>" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Twitter URL</label>
                <input type="url" name="twitter" value="<?php echo escape($settings['twitter']); ?>" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">LinkedIn URL</label>
                <input type="url" name="linkedin" value="<?php echo escape($settings['linkedin']); ?>" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">YouTube URL</label>
                <input type="url" name="youtube" value="<?php echo escape($settings['youtube']); ?>" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest ml-1">Instagram URL</label>
                <input type="url" name="instagram" value="<?php echo escape($settings['instagram']); ?>" 
                    class="w-full px-6 py-4 bg-white border border-slate-200 rounded-2xl focus:ring-4 focus:ring-kmf-orange/10 focus:border-kmf-orange outline-none transition-all font-medium text-kmf-blue">
            </div>
        </div>
    </section>

    <div class="pt-6 border-t border-slate-100 flex justify-end">
        <button type="submit" class="w-full md:w-auto bg-kmf-orange hover:bg-kmf-orange-light text-white font-extrabold px-12 py-5 rounded-2xl shadow-xl shadow-kmf-orange/20 transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98]">
            Save All Settings
        </button>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
