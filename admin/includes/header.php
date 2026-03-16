<?php
$adminTitle = $adminTitle ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo escape($adminTitle); ?> | KMF Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { kmf: { blue: '#1e3a5f', orange: '#e85d04' } } } } }</script>
</head>
<body class="bg-gray-100 text-gray-800">
    <nav class="bg-kmf-blue text-white shadow">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-14">
                <a href="<?php echo BASE_URL; ?>admin/index.php" class="font-bold">KMF Admin</a>
                <div class="flex items-center gap-4">
                    <a href="<?php echo BASE_URL; ?>index.php" target="_blank" class="text-sm hover:underline">View Site</a>
                    <a href="<?php echo BASE_URL; ?>admin/logout.php" class="text-sm hover:underline">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col md:flex-row gap-6">
            <aside class="w-full md:w-48 flex-shrink-0 space-y-1">
                <a href="<?php echo BASE_URL; ?>admin/index.php" class="block px-4 py-2 rounded bg-gray-200 font-medium">Dashboard</a>
                <a href="<?php echo BASE_URL; ?>admin/settings.php" class="block px-4 py-2 rounded hover:bg-gray-200">Settings</a>
                <a href="<?php echo BASE_URL; ?>admin/pages.php" class="block px-4 py-2 rounded hover:bg-gray-200">Pages</a>
                <a href="<?php echo BASE_URL; ?>admin/areas.php" class="block px-4 py-2 rounded hover:bg-gray-200">Strategic Areas</a>
                <a href="<?php echo BASE_URL; ?>admin/programs.php" class="block px-4 py-2 rounded hover:bg-gray-200">Programs</a>
                <a href="<?php echo BASE_URL; ?>admin/publications.php" class="block px-4 py-2 rounded hover:bg-gray-200">Publications</a>
                <a href="<?php echo BASE_URL; ?>admin/news.php" class="block px-4 py-2 rounded hover:bg-gray-200">News</a>
                <a href="<?php echo BASE_URL; ?>admin/events.php" class="block px-4 py-2 rounded hover:bg-gray-200">Events</a>
                <a href="<?php echo BASE_URL; ?>admin/team.php" class="block px-4 py-2 rounded hover:bg-gray-200">Team & Partners</a>
            </aside>
            <main class="flex-1 min-w-0">
