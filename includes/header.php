<?php
if (!isset($pageTitle)) $pageTitle = getSetting('site_name');
$siteName = getSetting('site_name');
// Force KMF logo from assets; DB setting is ignored to avoid path issues
$logoUrl = 'assets/images/kmf-logo.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape($pageTitle); ?> | <?php echo escape($siteName); ?></title>
    <meta name="description" content="<?php echo isset($metaDescription) ? escape($metaDescription) : escape(getSetting('site_tagline')); ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kmf: {
                            blue: '#1e3a5f',
                            'blue-light': '#2d5a87',
                            orange: '#e85d04',
                            'orange-light': '#f48c06',
                            green: '#52b788',
                            'green-light': '#95d5b2',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/custom.css">
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <header class="bg-kmf-blue text-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 md:px-8">
            <div class="flex items-center justify-between h-24 md:h-32">
                <a href="<?php echo BASE_URL; ?>index.php" class="flex items-center gap-4 group">
                    <div class="h-20 w-20 md:h-24 md:w-24 bg-white rounded-xl flex items-center justify-center p-1 shadow-inner border shadow-sm group-hover:shadow-md transition-shadow">
                        <img src="<?php echo BASE_URL . $logoUrl; ?>" alt="<?php echo escape($siteName); ?>" class="max-h-full max-w-full object-contain">
                    </div>
                    <div class="flex flex-col justify-center uppercase tracking-tight">
                        <span class="font-bold text-sm md:text-base leading-none text-kmf-orange-light mb-0.5">Kanchhi Maya</span>
                        <span class="font-extrabold text-xl md:text-2xl leading-none mb-0.5 text-white">Tamang</span>
                        <span class="font-bold text-sm md:text-base leading-none text-gray-300">Foundation</span>
                    </div>
                </a>
                <button type="button" id="nav-toggle" class="md:hidden p-2 rounded hover:bg-kmf-blue-light" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <nav id="main-nav" class="hidden md:flex items-center gap-2">
                    <a href="<?php echo BASE_URL; ?>index.php" class="nav-link px-3 py-2 rounded-lg hover:bg-kmf-blue-light transition-all duration-300 hover:scale-105 text-sm lg:text-base relative group">
                        Home
                        <span class="absolute bottom-1 left-3 right-3 h-0.5 bg-kmf-orange transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>about.php" class="nav-link px-3 py-2 rounded-lg hover:bg-kmf-blue-light transition-all duration-300 hover:scale-105 text-sm lg:text-base relative group">
                        Who We Are
                        <span class="absolute bottom-1 left-3 right-3 h-0.5 bg-kmf-orange transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>what-we-do.php" class="nav-link px-3 py-2 rounded-lg hover:bg-kmf-blue-light transition-all duration-300 hover:scale-105 text-sm lg:text-base relative group">
                        What We Do
                        <span class="absolute bottom-1 left-3 right-3 h-0.5 bg-kmf-orange transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>programs.php" class="nav-link px-3 py-2 rounded-lg hover:bg-kmf-blue-light transition-all duration-300 hover:scale-105 text-sm lg:text-base relative group">
                        Our Programs
                        <span class="absolute bottom-1 left-3 right-3 h-0.5 bg-kmf-orange transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>resources.php" class="nav-link px-3 py-2 rounded-lg hover:bg-kmf-blue-light transition-all duration-300 hover:scale-105 text-sm lg:text-base relative group">
                        Resources
                        <span class="absolute bottom-1 left-3 right-3 h-0.5 bg-kmf-orange transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>news.php" class="nav-link px-3 py-2 rounded-lg hover:bg-kmf-blue-light transition-all duration-300 hover:scale-105 text-sm lg:text-base relative group">
                        News & Media
                        <span class="absolute bottom-1 left-3 right-3 h-0.5 bg-kmf-orange transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>events.php" class="nav-link px-3 py-2 rounded-lg hover:bg-kmf-blue-light transition-all duration-300 hover:scale-105 text-sm lg:text-base relative group">
                        Events
                        <span class="absolute bottom-1 left-3 right-3 h-0.5 bg-kmf-orange transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left"></span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>contact.php" class="ml-2 px-5 py-2 rounded-lg bg-kmf-orange hover:bg-kmf-orange-light transition-all duration-300 hover:scale-105 hover:shadow-lg font-bold text-sm lg:text-base shadow-sm ring-2 ring-white/20">Contact</a>
                </nav>
            </div>
            <div id="mobile-nav" class="hidden md:hidden pb-4">
                <a href="<?php echo BASE_URL; ?>index.php" class="block py-2 hover:bg-kmf-blue-light px-2 rounded">Home</a>
                <a href="<?php echo BASE_URL; ?>about.php" class="block py-2 hover:bg-kmf-blue-light px-2 rounded">Who We Are</a>
                <a href="<?php echo BASE_URL; ?>what-we-do.php" class="block py-2 hover:bg-kmf-blue-light px-2 rounded">What We Do</a>
                <a href="<?php echo BASE_URL; ?>programs.php" class="block py-2 hover:bg-kmf-blue-light px-2 rounded">Our Programs</a>
                <a href="<?php echo BASE_URL; ?>resources.php" class="block py-2 hover:bg-kmf-blue-light px-2 rounded">Resources</a>
                <a href="<?php echo BASE_URL; ?>news.php" class="block py-2 hover:bg-kmf-blue-light px-2 rounded">News & Media</a>
                <a href="<?php echo BASE_URL; ?>events.php" class="block py-2 hover:bg-kmf-blue-light px-2 rounded">Events</a>
                <a href="<?php echo BASE_URL; ?>contact.php" class="block py-2 hover:bg-kmf-blue-light px-2 rounded font-medium">Contact</a>
            </div>
        </div>
    </header>
    <main class="min-h-screen">
