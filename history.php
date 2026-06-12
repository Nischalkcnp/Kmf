<?php
require_once __DIR__ . '/config/config.php';

$page = getPageBySlug('history');
$pageTitle = $page ? $page['title'] : 'Our History';
$metaDescription = $page ? $page['meta_description'] : 'The journey of Kanchhi Maya Tamang Foundation';

require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="relative bg-kmf-blue overflow-hidden py-5 md:py-7">
    <div class="absolute -top-20 -right-20 w-72 h-72 bg-kmf-orange/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

    <div class="container mx-auto px-4 lg:px-8 relative z-10">
        <!-- Sub-nav -->
        <div class="flex flex-wrap items-center gap-2 mb-5">
            <a href="<?php echo BASE_URL; ?>about.php"    class="px-4 py-1.5 bg-white/10 text-white/80 hover:bg-white/20 rounded-xl text-xs font-semibold transition-all">Who We Are</a>
            <a href="<?php echo BASE_URL; ?>history.php"  class="px-4 py-1.5 bg-white text-kmf-blue rounded-xl text-xs font-bold shadow transition-all">Our History</a>
            <a href="<?php echo getPageUrl('team'); ?>"     class="px-4 py-1.5 bg-white/10 text-white/80 hover:bg-white/20 rounded-xl text-xs font-semibold transition-all">Meet Our Team</a>
            <a href="<?php echo getPageUrl('partners'); ?>" class="px-4 py-1.5 bg-white/10 text-white/80 hover:bg-white/20 rounded-xl text-xs font-semibold transition-all">Our Partners</a>
        </div>

        <!-- Title -->
        <div class="max-w-3xl">
            <span class="inline-block text-kmf-orange font-bold uppercase tracking-[0.25em] text-xs mb-1.5">Our Journey</span>
            <h1 class="text-2xl md:text-3xl font-black text-white leading-tight tracking-tight font-montserrat">
                <?php echo escape($pageTitle); ?>
            </h1>
        </div>
    </div>
</section>

<!-- Main Content Section (fully dynamic from CMS) -->
<section class="py-8 lg:py-12 bg-white relative overflow-hidden">

    <div class="container mx-auto px-4 lg:px-8">

        <!-- Quick Subpage Navigation removed — now in hero -->

        <div class="max-w-4xl mx-auto">
            <?php if ($page && !empty($page['image_url'])): ?>
                <div class="mb-10 rounded-[2rem] overflow-hidden shadow-lg border border-slate-100 max-h-[480px]">
                    <img src="<?php echo BASE_URL . escape($page['image_url']); ?>" alt="<?php echo escape($pageTitle); ?>" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>

            <?php if ($page && !empty(trim($page['content']))): ?>
                <div class="prose-custom max-w-none text-gray-600 leading-relaxed text-lg">
                    <?php echo $page['content']; ?>
                </div>
            <?php else: ?>
                <!-- Fallback shown when no content has been set in CMS yet -->
                <div class="text-gray-600 leading-relaxed text-lg space-y-6">
                    <p>Deep within the Himalayas, rugged mountain ranges form a natural barrier. During the monsoon season, heavy rains frequently trigger landslides, making transportation and road access extremely difficult. In this remote, mist-covered region, access to healthcare and essential medicines remains severely limited. For patients requiring emergency treatment in the capital, poor transportation infrastructure and treacherous mountain roads often mean enduring days of painful travel, sometimes with life-threatening consequences.</p>

                    <p>Determined to change this reality, a physician made the courageous decision to dedicate his life to serving these remote communities. That physician is Dr. Dil Bahadur Tamang, founder of Kanchhi Maya Tamang Memorial Community Hospital, a warm and welcoming healthcare center nestled among the majestic Himalayan mountains.</p>

                    <p>The hospital was named in honor of his late mother, Kanchhi Maya Tamang. More than a tribute, the name represents a heartfelt promise from a healer—to protect every precious life with the same compassion, strength, and resilience embodied by a mother’s love.</p>

                    <p>Kanchhi Maya Tamang was an ordinary woman with an extraordinary heart. Throughout her life, she devoted herself to public service and charitable work, always extending kindness and support to those in need. She organized and led local women in agricultural activities to help improve their economic conditions and strengthen community self-reliance.</p>

                    <p>From an early age, she inspired and encouraged her son to become a doctor who would serve society and bring hope to others. Today, Dr. Dil continues to honor her vision through his dedication to humanitarian service. He has also provided educational support to several medical students, hoping they will one day become healthcare professionals who serve their communities and assist those most in need.</p>

                    <p>Inspired by this spirit of compassion and service, healthcare workers and volunteers have received generous support from individuals and organizations around the world. Numerous national and international charitable organizations have joined hands to overcome the challenges of Nepal’s rugged terrain, delivering advanced medical equipment and essential medicines to remote mountain communities.</p>

                    <p>Together, they have traveled throughout Nepal, providing free healthcare services and medical outreach programs to underserved populations. Their efforts have brought vital medical care, hope, and dignity to countless individuals living in isolated regions.</p>

                    <p>Saving lives remains their unwavering mission, while promoting health education continues to be their enduring commitment. Dr. Dil also leads volunteers to schools and communities, where they educate people about the nutritional value and practical benefits of the remarkable Moringa tree. By teaching communities how to cultivate and utilize this natural resource to improve nutrition and help prevent disease, they are fostering greater health awareness and self-sufficiency among local populations.</p>

                    <p>Speaking about his vision for the future, Dr. Dil shared:</p>

                    <blockquote class="border-l-4 border-kmf-blue pl-4 my-6 italic text-gray-700 font-medium bg-slate-50 py-3 pr-2 rounded-r-xl">
                        “It is my hope that, in the years ahead, ten community hospitals will be established across the mountainous regions of Nepal to address the shortage of quality healthcare services in remote areas. We also aim to continue supporting students from disadvantaged families, helping to nurture future medical professionals who will serve society with compassion and dedication.”
                    </blockquote>

                    <p>Kanchhi Maya Tamang often said during her lifetime, “A life dedicated to helping others is the most valuable life of all.”</p>

                    <p>Today, her spirit of selfless service lives on through the work of Dr. Dil and his colleagues. It continues to inspire compassionate people around the world to extend kindness, hope, and healing throughout the Himalayas.</p>

                    <div class="text-slate-400 italic text-sm border border-dashed border-slate-200 rounded-2xl p-6 mt-8">
                        📝 <strong>Admin tip:</strong> Edit this page's content from
                        <a href="<?php echo BASE_URL; ?>admin/pages.php" class="text-kmf-orange underline">Admin → Pages → Our History</a>.
                        You can paste HTML including timelines, images, and more.
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<!-- Call to Action -->
<section class="py-8 bg-kmf-blue relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-20">
        <img src="<?php echo BASE_URL; ?>assets/images/history-hero.png" alt="History Bg" class="w-full h-full object-cover">
    </div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h2 class="text-2xl md:text-3xl font-black text-white mb-6">Be Part of Our Next Chapter</h2>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="<?php echo BASE_URL; ?>donate.php"  class="bg-kmf-orange hover:bg-kmf-orange-light text-white font-bold px-8 py-3.5 rounded-2xl shadow-xl transition-all transform hover:-translate-y-1">Donate Now</a>
            <a href="<?php echo BASE_URL; ?>contact.php" class="bg-white hover:bg-gray-100 text-kmf-blue font-bold px-8 py-3.5 rounded-2xl shadow-xl transition-all transform hover:-translate-y-1">Get Involved</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
