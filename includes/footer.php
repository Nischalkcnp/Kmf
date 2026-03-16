    </main>
    <footer class="bg-kmf-blue text-white mt-16">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="flex flex-col gap-4">
                    <a href="<?php echo BASE_URL; ?>index.php" class="flex items-center gap-4">
                        <div class="h-16 w-16 bg-white rounded-lg flex items-center justify-center p-1 shadow-sm overflow-hidden">
                            <img src="<?php echo BASE_URL; ?>assets/images/kmf-logo.png" alt="<?php echo escape(getSetting('site_name')); ?>" class="max-h-full max-w-full object-contain">
                        </div>
                        <div class="flex flex-col justify-center uppercase tracking-tight">
                            <span class="font-bold text-xs leading-none text-kmf-orange-light mb-0.5">Kanchhi Maya</span>
                            <span class="font-extrabold text-lg leading-none mb-0.5 text-white">Tamang</span>
                            <span class="font-bold text-xs leading-none text-gray-300">Foundation</span>
                        </div>
                    </a>
                    <p class="text-sm text-gray-200 mt-2"><?php echo escape(getSetting('site_tagline')); ?></p>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Who We Are</h4>
                    <ul class="space-y-2 text-sm text-gray-200">
                        <li><a href="<?php echo BASE_URL; ?>about.php" class="hover:text-white">About Us</a></li>
                        <li><a href="<?php echo BASE_URL; ?>about.php#team" class="hover:text-white">Our Team</a></li>
                        <li><a href="<?php echo BASE_URL; ?>about.php#partners" class="hover:text-white">Partners</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">What We Do</h4>
                    <ul class="space-y-2 text-sm text-gray-200">
                        <li><a href="<?php echo BASE_URL; ?>what-we-do.php" class="hover:text-white">Strategic Areas</a></li>
                        <li><a href="<?php echo BASE_URL; ?>programs.php" class="hover:text-white">Programs</a></li>
                        <li><a href="<?php echo BASE_URL; ?>resources.php" class="hover:text-white">Resources</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-3">Contact</h4>
                    <?php if (getSetting('address')): ?>
                        <p class="text-sm text-gray-200 mb-2"><?php echo nl2br(escape(getSetting('address'))); ?></p>
                    <?php endif; ?>
                    <?php if (getSetting('phone')): ?>
                        <p class="text-sm text-gray-200 mb-2"><?php echo escape(getSetting('phone')); ?></p>
                    <?php endif; ?>
                    <?php if (getSetting('email')): ?>
                        <p class="text-sm"><a href="mailto:<?php echo escape(getSetting('email')); ?>" class="text-kmf-orange-light hover:underline"><?php echo escape(getSetting('email')); ?></a></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="border-t border-gray-600 mt-8 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-gray-300">
                <p>&copy; <?php echo date('Y'); ?> <?php echo escape(getSetting('site_name')); ?>. All rights reserved.</p>
                <div class="flex gap-4">
                    <?php if (getSetting('facebook')): ?><a href="<?php echo escape(getSetting('facebook')); ?>" target="_blank" rel="noopener" class="hover:text-white">Facebook</a><?php endif; ?>
                    <?php if (getSetting('twitter')): ?><a href="<?php echo escape(getSetting('twitter')); ?>" target="_blank" rel="noopener" class="hover:text-white">Twitter</a><?php endif; ?>
                    <?php if (getSetting('linkedin')): ?><a href="<?php echo escape(getSetting('linkedin')); ?>" target="_blank" rel="noopener" class="hover:text-white">LinkedIn</a><?php endif; ?>
                </div>
            </div>
        </div>
    </footer>
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
</body>
</html>
