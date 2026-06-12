</main>
    <footer class="bg-kmf-blue text-white mt-4 md:mt-6">
        <div class="container mx-auto px-4 lg:px-8 py-5 md:py-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-10">
                <div class="flex flex-col gap-2">
                    <a href="<?php echo BASE_URL; ?>index.php" class="flex items-center gap-3 group">
                        <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center p-1 shadow-lg transition-transform duration-300 group-hover:scale-110">
                            <img src="<?php echo BASE_URL; ?>assets/images/kmf-logo.png" alt="<?php echo escape(getSetting('site_name')); ?>" class="max-h-full max-w-full object-contain">
                        </div>
                        <div class="flex flex-col justify-center uppercase tracking-tight">
                            <span class="font-bold text-xs leading-none text-white mb-0.5">Kanchhi Maya</span>
                            <span class="font-extrabold text-base leading-none mb-0.5 text-white">Tamang</span>
                            <span class="font-bold text-xs leading-none text-white">Foundation</span>
                        </div>
                    </a>
                    <p class="text-xs text-gray-400 font-medium leading-relaxed italic border-l-2 border-kmf-orange pl-3"><?php echo escape(getSetting('site_tagline')); ?></p>
                </div>
                <div>
                    <h4 class="font-extrabold text-white mb-2 uppercase tracking-widest text-xs">Who We Are</h4>
                    <ul class="space-y-1.5 text-gray-300 font-semibold text-xs">
                        <li><a href="<?php echo getPageUrl('about'); ?>" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-kmf-orange flex-shrink-0"></span> About Us</a></li>
                        <li><a href="<?php echo getPageUrl('team'); ?>" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-kmf-orange flex-shrink-0"></span> Our Team</a></li>
                        <li><a href="<?php echo getPageUrl('partners'); ?>" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-kmf-orange flex-shrink-0"></span> Partners</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-extrabold text-white mb-2 uppercase tracking-widest text-xs">Our Impact</h4>
                    <ul class="space-y-1.5 text-gray-300 font-semibold text-xs">
                        <li><a href="<?php echo getPageUrl('areas'); ?>" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-kmf-orange flex-shrink-0"></span> Strategic Areas</a></li>
                        <li><a href="<?php echo getPageUrl('programs'); ?>" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-kmf-orange flex-shrink-0"></span> Programs</a></li>
                        <li><a href="<?php echo getPageUrl('resources'); ?>" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-kmf-orange flex-shrink-0"></span> Knowledge Hub</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-extrabold text-white mb-2 uppercase tracking-widest text-xs">Get in Touch</h4>
                    <div class="space-y-1.5 font-medium text-xs text-gray-300">
                        <?php if (getSetting('address')): ?>
                            <div class="flex gap-2 items-start">
                                <svg class="w-4 h-4 text-kmf-orange flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span><?php echo nl2br(escape(getSetting('address'))); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (getSetting('phone')): ?>
                            <div class="flex gap-2 items-center">
                                <svg class="w-4 h-4 text-kmf-orange flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span><?php echo escape(getSetting('phone')); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (getSetting('email')): ?>
                            <div class="flex gap-2 items-center">
                                <svg class="w-4 h-4 text-kmf-orange flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a href="mailto:<?php echo escape(getSetting('email')); ?>" class="hover:text-kmf-orange-light transition-all"><?php echo escape(getSetting('email')); ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if (getSetting('facebook')): ?>
                            <div class="flex gap-2 items-center">
                                <svg class="w-4 h-4 text-kmf-orange flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                                <a href="<?php echo escape(getSetting('facebook')); ?>" target="_blank" rel="noopener" class="hover:text-kmf-orange-light transition-all">Facebook</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="border-t border-white/5 mt-4 pt-3 flex flex-col md:flex-row justify-between items-center gap-2 text-xs font-bold text-gray-500 uppercase tracking-widest">
                <p>&copy; <?php echo date('Y'); ?> <?php echo escape(getSetting('site_name')); ?>. All Rights Reserved.</p>
                <div class="flex gap-6">
                    <?php if (getSetting('facebook')): ?><a href="<?php echo escape(getSetting('facebook')); ?>" target="_blank" rel="noopener" class="hover:text-white transition-colors">Facebook</a><?php endif; ?>
                    <?php if (getSetting('twitter')): ?><a href="<?php echo escape(getSetting('twitter')); ?>" target="_blank" rel="noopener" class="hover:text-white transition-colors">Twitter</a><?php endif; ?>
                    <?php if (getSetting('linkedin')): ?><a href="<?php echo escape(getSetting('linkedin')); ?>" target="_blank" rel="noopener" class="hover:text-white transition-colors">LinkedIn</a><?php endif; ?>
                </div>
            </div>
        </div>
    </footer>
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>
