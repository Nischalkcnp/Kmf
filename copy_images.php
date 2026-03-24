<?php
$files = [
    'C:\Users\A S U S\.gemini\antigravity\brain\c378b4ba-f888-454a-badd-5c1b829a644d\strategic_education_1773840848304.png' => 'd:\kmf\assets\uploads\strategic-areas\education.png',
    'C:\Users\A S U S\.gemini\antigravity\brain\c378b4ba-f888-454a-badd-5c1b829a644d\strategic_community_1773840913345.png' => 'd:\kmf\assets\uploads\strategic-areas\community.png',
    'C:\Users\A S U S\.gemini\antigravity\brain\c378b4ba-f888-454a-badd-5c1b829a644d\strategic_health_1773840970483.png' => 'd:\kmf\assets\uploads\strategic-areas\health.png'
];

foreach ($files as $src => $dst) {
    if (file_exists($src)) {
        if (copy($src, $dst)) {
            echo "Copied: $src -> $dst\n";
        } else {
            echo "Failed to copy: $src -> $dst\n";
        }
    } else {
        echo "Source not found: $src\n";
    }
}
?>
