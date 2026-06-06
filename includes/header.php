<?php
if (!isset($pageTitle))
    $pageTitle = getSetting('site_name');
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
    <!-- Google Fonts: Inter & Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        montserrat: ['Montserrat', 'sans-serif'],
                    },
                    colors: {
                        kmf: {
                            blue: '#1e3a5f',
                            'blue-light': '#2d5a87',
                            'blue-dark': '#132640',
                            orange: '#e85d04',
                            'orange-light': '#f48c06',
                            green: '#52b788',
                            'green-light': '#95d5b2',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out',
                        'slide-up': 'slideUp 0.6s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/custom.css">
</head>
<body class="bg-gray-50 text-gray-800 antialiased">
    <header class="bg-kmf-blue/95 backdrop-blur-md text-white shadow-xl sticky top-0 z-50 border-b border-white/5">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between h-20 md:h-24 transition-all duration-300">
                <a href="<?php echo BASE_URL; ?>index.php" class="flex items-center gap-3 md:gap-4 group">
                    <div class="h-14 w-14 md:h-16 md:w-16 bg-white rounded-2xl flex items-center justify-center p-1.5 shadow-2xl group-hover:scale-105 transition-transform duration-500">
                        <img src="<?php echo BASE_URL . $logoUrl; ?>" alt="<?php echo escape($siteName); ?>" class="max-h-full max-w-full object-contain">
                    </div>
                    <div class="flex flex-col justify-center">
                        <span class="font-extrabold text-lg md:text-xl lg:text-2xl leading-none tracking-tight font-montserrat text-white group-hover:text-kmf-green-light transition-colors">KMTF</span>
                        <span class="text-xs md:text-sm font-bold text-white mt-1 group-hover:text-kmf-orange-light transition-colors">कान्छी माया तामाङ फाउण्डेशन</span>

                    </div>
                </a>

                <!-- Navigation -->
                <div class="hidden lg:flex items-center lg:gap-3 xl:gap-6">
                    <nav id="main-nav" class="flex items-center gap-1">
                        <?php
$currentFile = basename($_SERVER['PHP_SELF']);
$currentSlug = str_replace('.php', '', $currentFile);
if (empty($currentSlug) || $currentFile === 'index.php') {
    $currentSlug = 'index';
}

// Fetch all active top-level pages from database
$pdo = getDb();
$stmt = $pdo->query("SELECT * FROM pages WHERE parent_id IS NULL ORDER BY sort_order ASC, title ASC");
$topPages = $stmt->fetchAll();

$donatePage = null;
foreach ($topPages as $page):
    if ($page['slug'] === 'donate' || $page['slug'] === 'donate.php') {
        $donatePage = $page;
        continue;
    }

    // Fetch subpages
    $stmtSub = $pdo->prepare("SELECT * FROM pages WHERE parent_id = ? ORDER BY sort_order ASC, title ASC");
    $stmtSub->execute([$page['id']]);
    $subPages = $stmtSub->fetchAll();

    $isActive = (isset($_GET['slug']) && $_GET['slug'] == $page['slug']) || $currentSlug == $page['slug'];
    $hasSubmenu = !empty($subPages);
    $isDonate = ($page['slug'] == 'donate.php' || $page['slug'] == 'donate');

    // Link logic: prefer existing .php file if it matches the slug
    $linkUrl = BASE_URL . 'view.php?slug=' . urlencode($page['slug']);
    if (file_exists(ROOT_PATH . $page['slug'] . '.php')) {
        $linkUrl = BASE_URL . $page['slug'] . '.php';
    }
    elseif (file_exists(ROOT_PATH . $page['slug'])) {
        $linkUrl = BASE_URL . $page['slug'];
    }
?>
                        <div class="relative group/menu">
                            <a href="<?php echo $linkUrl; ?>" class="nav-link-animate lg:px-2 xl:px-4 py-2 rounded-xl text-sm transition-all duration-300 <?php echo $isActive ? 'bg-white/10 text-kmf-green-light' : ($isDonate ? 'text-kmf-green-light hover:text-kmf-green-light hover:bg-white/5' : 'hover:bg-white/5 text-gray-200 hover:text-kmf-green-light'); ?> <?php echo $isDonate ? 'font-black tracking-widest uppercase' : 'font-semibold tracking-wide'; ?> flex items-center gap-1.5 whitespace-nowrap">
                                <?php echo escape($page['title']); ?>
                                <?php if ($hasSubmenu): ?>
                                    <svg class="w-4 h-4 opacity-50 group-hover/menu:rotate-180 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                <?php
    endif; ?>
                            </a>
                            
                            <?php if ($hasSubmenu): ?>
                            <div class="absolute top-full left-0 w-56 pt-4 opacity-0 invisible group-hover/menu:opacity-100 group-hover/menu:visible transition-all duration-300 translate-y-2 group-hover/menu:translate-y-0 z-50">
                                <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-2 overflow-hidden transform-gpu">
                                    <?php foreach ($subPages as $sub):
            $subUrl = BASE_URL . 'view.php?slug=' . urlencode($sub['slug']);
            if (file_exists(ROOT_PATH . $sub['slug'] . '.php')) {
                $subUrl = BASE_URL . $sub['slug'] . '.php';
            }
            elseif (file_exists(ROOT_PATH . $sub['slug'])) {
                $subUrl = BASE_URL . $sub['slug'];
            }
?>
                                    <a href="<?php echo $subUrl; ?>" class="block px-4 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 hover:text-kmf-green-light rounded-xl transition-all">
                                        <?php echo escape($sub['title']); ?>
                                    </a>
                                    <?php
        endforeach; ?>
                                </div>
                            </div>
                            <?php
    endif; ?>
                        </div>
                        <?php
endforeach; ?>
                        
                    </nav>

                    <!-- Donate Button & Social Icons -->
                    <div class="flex items-center lg:gap-2 xl:gap-4 lg:pl-3 xl:pl-6 border-l border-white/10">
                        <?php if (isset($donatePage) && $donatePage):
    $donateUrl = BASE_URL . 'view.php?slug=' . urlencode($donatePage['slug']);
    if (file_exists(ROOT_PATH . $donatePage['slug'] . '.php')) {
        $donateUrl = BASE_URL . $donatePage['slug'] . '.php';
    }
    elseif (file_exists(ROOT_PATH . $donatePage['slug'])) {
        $donateUrl = BASE_URL . $donatePage['slug'];
    }
?>
                            <a href="<?php echo $donateUrl; ?>" class="hidden lg:inline-flex nav-link-animate px-6 py-2.5 rounded-xl text-sm font-bold tracking-widest uppercase text-white bg-kmf-green hover:bg-kmf-green-light shadow-[0_0_15px_rgba(46,196,182,0.3)] transition-all duration-300 transform hover:-translate-y-0.5 whitespace-nowrap">
                                <?php echo escape($donatePage['title']); ?>
                            </a>
                        <?php
endif; ?>

                        <?php
$socials = [
    ['facebook', '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>'],
    ['youtube', '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>'],
    ['instagram', '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>']
];
foreach ($socials as $soc):
    $link = getSetting($soc[0]);
    if ($link):
?>
                        <a href="<?php echo escape($link); ?>" target="_blank" class="nav-link-animate text-gray-400 hover:text-white transition-colors p-2 hover:bg-white/5 rounded-lg">
                            <?php echo $soc[1]; ?>
                        </a>
                        <?php
    endif;
endforeach;
?>
                    </div>
                </div>

                <!-- Mobile Nav Toggle -->
                <button type="button" id="nav-toggle" class="lg:hidden p-2 rounded-xl bg-white/5 text-white focus:outline-none transition-all" aria-label="Toggle Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="fixed inset-0 z-[60] bg-kmf-blue-dark text-white lg:hidden transition-transform duration-500 translate-x-full overflow-y-auto" style="background-color: #132640;">
            <button type="button" id="nav-close" class="absolute top-6 right-6 p-2 rounded-xl bg-white/5 text-white hover:bg-white/10 transition-colors" aria-label="Close Menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <div class="flex flex-col h-full p-8 pt-24 min-h-0">
                <nav class="flex-1 flex flex-col gap-4 overflow-y-auto pb-12 min-h-0">
                <?php foreach ($topPages as $page):
    if ($page['slug'] === 'donate' || $page['slug'] === 'donate.php')
        continue;

    $stmtSub = $pdo->prepare("SELECT * FROM pages WHERE parent_id = ? ORDER BY sort_order ASC, title ASC");
    $stmtSub->execute([$page['id']]);
    $subPages = $stmtSub->fetchAll();
    $hasSubmenu = !empty($subPages);
    $linkUrl = BASE_URL . 'view.php?slug=' . urlencode($page['slug']);
    if (file_exists(ROOT_PATH . $page['slug'] . '.php')) {
        $linkUrl = BASE_URL . $page['slug'] . '.php';
    }
    elseif (file_exists(ROOT_PATH . $page['slug'])) {
        $linkUrl = BASE_URL . $page['slug'];
    }
?>
                <?php
    $isActive = (isset($_GET['slug']) && $_GET['slug'] == $page['slug']) || $currentSlug == $page['slug'];
?>
                <div class="flex flex-col">
                    <a href="<?php echo $linkUrl; ?>" class="text-2xl font-black flex items-center justify-between <?php echo $isActive ? 'text-kmf-green-light' : 'text-white'; ?>">
                        <?php echo escape($page['title']); ?>
                    </a>
                    <?php if ($hasSubmenu): ?>
                    <div class="mt-4 ml-4 flex flex-col gap-3 border-l-2 border-white/10 pl-6 pb-2">
                        <?php foreach ($subPages as $sub):
            $subUrl = BASE_URL . 'view.php?slug=' . urlencode($sub['slug']);
            if (file_exists(ROOT_PATH . $sub['slug'] . '.php')) {
                $subUrl = BASE_URL . $sub['slug'] . '.php';
            }
            elseif (file_exists(ROOT_PATH . $sub['slug'])) {
                $subUrl = BASE_URL . $sub['slug'];
            }
?>
                        <a href="<?php echo $subUrl; ?>" class="text-lg font-medium text-gray-400 hover:text-kmf-green-light transition-colors">
                            <?php echo escape($sub['title']); ?>
                        </a>
                        <?php
        endforeach; ?>
                    </div>
                    <?php
    endif; ?>
                </div>
                <?php
endforeach; ?>
                
                <?php if (isset($donatePage) && $donatePage):
    $donateUrl = BASE_URL . 'view.php?slug=' . urlencode($donatePage['slug']);
    if (file_exists(ROOT_PATH . $donatePage['slug'] . '.php')) {
        $donateUrl = BASE_URL . $donatePage['slug'] . '.php';
    }
    elseif (file_exists(ROOT_PATH . $donatePage['slug'])) {
        $donateUrl = BASE_URL . $donatePage['slug'];
    }
?>
                    <a href="<?php echo $donateUrl; ?>" class="text-2xl font-black tracking-widest uppercase text-kmf-green-light mt-4 pt-4 border-t border-white/10">
                        <?php echo escape($donatePage['title']); ?>
                    </a>
                <?php
endif; ?>
            </nav>
            <div class="mt-8 pt-8 border-t border-white/10 flex gap-6">
                <!-- Mobile Socials -->
                <?php
foreach ($socials as $soc):
    $link = getSetting($soc[0]);
    if ($link):
?>
                <a href="<?php echo escape($link); ?>" target="_blank" class="text-gray-400">
                    <?php echo $soc[1]; ?>
                </a>
                <?php
    endif;
endforeach;
?>
            </div>
            <div class="mt-auto">
                <a href="<?php echo BASE_URL; ?>donate.php" class="block w-full bg-kmf-orange text-white text-center font-extrabold py-5 rounded-3xl shadow-xl shadow-kmf-orange/20">DONATE NOW</a>
            </div>
        </div>
    </div>
</header>

    <!-- Popup Notice -->
    <?php include_once ROOT_PATH . 'includes/popup.php'; ?>
    <main class="min-h-screen">
