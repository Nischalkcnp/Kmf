<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape($adminTitle); ?> | KMF Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        kmf: {
                            blue: '#1e3a5f',
                            'blue-dark': '#132841',
                            orange: '#e85d04',
                            'orange-light': '#ff8c00',
                            'green-light': '#f0fdf4',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        montserrat: ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar-link.active {
            background-color: #1e3a5f;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(30, 58, 95, 0.2);
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-700">
    <!-- Top Header -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="mx-auto px-4 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-kmf-blue rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-kmf-blue/20">K</div>
                    <div>
                        <h1 class="text-lg font-extrabold text-kmf-blue font-montserrat tracking-tight leading-none">KMF <span class="text-kmf-orange">Admin</span></h1>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Content Management System</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-6">
                    <a href="<?php echo BASE_URL; ?>index.php" target="_blank" class="hidden md:flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-kmf-orange transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        View Website
                    </a>
                    <div class="h-6 w-px bg-slate-200"></div>
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold text-kmf-blue"><?php echo escape($_SESSION['admin_username'] ?? 'Administrator'); ?></p>
                            <a href="<?php echo BASE_URL; ?>admin/logout.php" class="text-[10px] font-bold text-red-400 hover:text-red-500 transition-colors uppercase tracking-wider">Logout</a>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="mx-auto px-4 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <aside class="w-full lg:w-72 flex-shrink-0">
                <nav class="space-y-1.5 sticky top-28">
                    <?php
                    $navItems = [
                        ['url' => 'admin/index.php', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['url' => 'admin/settings.php', 'label' => 'General Settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                        ['url' => 'admin/pages.php', 'label' => 'Page Manager', 'icon' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z'],
                        ['url' => 'admin/areas.php', 'label' => 'Strategic Areas', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                        ['url' => 'admin/programs.php', 'label' => 'Our Programs', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                        ['url' => 'admin/publications.php', 'label' => 'Publications', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['url' => 'admin/news.php', 'label' => 'News/Stories', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z M14 2v6h6 m-3 5H7m10 3H7m10 3H7'],
                        ['url' => 'admin/events.php', 'label' => 'Events Manager', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['url' => 'admin/team.php', 'label' => 'Team & Partners', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                    ];

                    $currentPage = basename($_SERVER['PHP_SELF']);
                    foreach ($navItems as $item):
                        $isActive = (basename($item['url']) === $currentPage);
                    ?>
                        <a href="<?php echo BASE_URL . $item['url']; ?>" 
                           class="sidebar-link group flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition-all duration-200 <?php echo $isActive ? 'active' : 'text-slate-500 hover:text-kmf-blue hover:bg-white hover:shadow-sm'; ?>">
                            <svg class="w-5 h-5 <?php echo $isActive ? 'text-white' : 'text-slate-400 group-hover:text-kmf-orange'; ?> transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo $item['icon']; ?>"></path>
                            </svg>
                            <?php echo $item['label']; ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 min-w-0 bg-white rounded-[2rem] shadow-sm border border-slate-200/60 p-6 md:p-10 mb-10">
