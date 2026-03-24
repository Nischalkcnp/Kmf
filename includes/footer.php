    </main>
    <footer class="bg-kmf-blue text-white mt-20 md:mt-32">
        <div class="container mx-auto px-4 lg:px-8 py-16 md:py-20">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-16">
                <div class="flex flex-col gap-6">
                    <a href="<?php echo BASE_URL; ?>index.php" class="flex items-center gap-4 group">
                        <div class="h-16 w-16 bg-white rounded-2xl flex items-center justify-center p-1.5 shadow-xl transition-transform duration-300 group-hover:scale-110">
                            <img src="<?php echo BASE_URL; ?>assets/images/kmf-logo.png" alt="<?php echo escape(getSetting('site_name')); ?>" class="max-h-full max-w-full object-contain">
                        </div>
                        <div class="flex flex-col justify-center uppercase tracking-tight">
                            <span class="font-bold text-xs leading-none text-kmf-orange-light mb-1">Kanchhi Maya</span>
                            <span class="font-extrabold text-xl leading-none mb-1 text-white">Tamang</span>
                            <span class="font-bold text-xs leading-none text-gray-400">Foundation</span>
                        </div>
                    </a>
                    <p class="text-sm md:text-base text-gray-300 font-medium leading-relaxed italic border-l-2 border-kmf-orange pl-4"><?php echo escape(getSetting('site_tagline')); ?></p>
                </div>
                <div>
                    <h4 class="text-lg font-extrabold text-white mb-6 uppercase tracking-widest text-xs">Who We Are</h4>
                    <ul class="space-y-4 text-gray-300 font-semibold text-sm">
                        <li><a href="<?php echo BASE_URL; ?>about.php" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-kmf-orange"></span> About Us</a></li>
                        <li><a href="<?php echo BASE_URL; ?>view.php?slug=team" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-kmf-orange"></span> Our Team</a></li>
                        <li><a href="<?php echo BASE_URL; ?>view.php?slug=partners" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-kmf-orange"></span> Partners</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-extrabold text-white mb-6 uppercase tracking-widest text-xs">Our Impact</h4>
                    <ul class="space-y-4 text-gray-300 font-semibold text-sm">
                        <li><a href="<?php echo BASE_URL; ?>what-we-do.php" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-kmf-orange"></span> Strategic Areas</a></li>
                        <li><a href="<?php echo BASE_URL; ?>programs.php" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-kmf-orange"></span> Programs</a></li>
                        <li><a href="<?php echo BASE_URL; ?>resources.php" class="hover:text-kmf-orange-light transition-colors flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-kmf-orange"></span> Knowledge Hub</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-extrabold text-white mb-6 uppercase tracking-widest text-xs">Get in Touch</h4>
                    <div class="space-y-4 font-semibold text-sm text-gray-300">
                        <?php if (getSetting('address')): ?>
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-kmf-orange flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span><?php echo nl2br(escape(getSetting('address'))); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (getSetting('phone')): ?>
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-kmf-orange flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span><?php echo escape(getSetting('phone')); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (getSetting('email')): ?>
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-kmf-orange flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <a href="mailto:<?php echo escape(getSetting('email')); ?>" class="hover:text-kmf-orange-light transition-all"><?php echo escape(getSetting('email')); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-white/5 mt-16 pt-10 flex flex-col md:flex-row justify-between items-center gap-8 text-sm font-bold text-gray-500 uppercase tracking-widest">
                <p>&copy; <?php echo date('Y'); ?> <?php echo escape(getSetting('site_name')); ?>. All Rights Reserved.</p>
                <div class="flex gap-8">
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
