<?php
$artworks = [
    'echoes-twilight.jpg' => ['title' => 'Echoes of Twilight Valley', 'artist' => 'Rajeshwar Sharma', 'bg1' => '#1b2a47', 'bg2' => '#3a506b', 'accent' => '#e0a96d'],
    'ochre-gold.jpg' => ['title' => 'Symphony in Ochre & Gold', 'artist' => 'Aarav Varma', 'bg1' => '#3d2612', 'bg2' => '#8f5d24', 'accent' => '#d4af37'],
    'banaras-ghats.jpg' => ['title' => 'Morning Ghats of Banaras', 'artist' => 'Sunita Deodhar', 'bg1' => '#5c3d2e', 'bg2' => '#b85d19', 'accent' => '#e9c46a'],
    'monsoon-reverie.jpg' => ['title' => 'Monsoon Reverie', 'artist' => 'Devendra Patel', 'bg1' => '#143642', 'bg2' => '#0f8b8d', 'accent' => '#a8dadc'],
    'vrindavan-flute.jpg' => ['title' => 'Eternal Flute of Vrindavan', 'artist' => 'Maheshwari Devi', 'bg1' => '#2b1e3a', 'bg2' => '#6b2d5c', 'accent' => '#f4a261'],
];

if (!is_dir('uploads/products')) {
    mkdir('uploads/products', 0777, true);
}

foreach ($artworks as $file => $art) {
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="800" height="1000" viewBox="0 0 800 1000">
    <defs>
        <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:' . $art['bg1'] . ';stop-opacity:1" />
            <stop offset="100%" style="stop-color:' . $art['bg2'] . ';stop-opacity:1" />
        </linearGradient>
        <radialGradient id="glow" cx="50%" cy="40%" r="50%">
            <stop offset="0%" style="stop-color:' . $art['accent'] . ';stop-opacity:0.35" />
            <stop offset="100%" style="stop-color:' . $art['bg1'] . ';stop-opacity:0" />
        </radialGradient>
    </defs>
    <rect width="800" height="1000" fill="url(#grad)" />
    <circle cx="400" cy="400" r="320" fill="url(#glow)" />
    <path d="M100 200 C300 150 500 280 700 220" stroke="' . $art['accent'] . '" stroke-width="3" fill="none" opacity="0.4" />
    <path d="M80 450 C280 380 480 500 720 420" stroke="' . $art['accent'] . '" stroke-width="4" fill="none" opacity="0.5" />
    <path d="M120 700 C350 620 520 760 680 690" stroke="' . $art['accent'] . '" stroke-width="2" fill="none" opacity="0.3" />
    <circle cx="400" cy="820" r="38" fill="none" stroke="' . $art['accent'] . '" stroke-width="2" opacity="0.7" />
    <text x="400" y="826" fill="' . $art['accent'] . '" font-family="Georgia,serif" font-size="14" text-anchor="middle" opacity="0.9" letter-spacing="2">JAYART</text>
    <text x="400" y="880" fill="#ffffff" font-family="Georgia,serif" font-size="24" text-anchor="middle" font-weight="600" opacity="0.95">' . htmlspecialchars($art['title']) . '</text>
    <text x="400" y="915" fill="' . $art['accent'] . '" font-family="sans-serif" font-size="14" text-anchor="middle" letter-spacing="3" opacity="0.8">' . strtoupper(htmlspecialchars($art['artist'])) . '</text>
    </svg>';
    
    file_put_contents('uploads/products/' . $file, $svg);
    file_put_contents('uploads/products/' . str_replace('.jpg', '.svg', $file), $svg);
}

// Also create default banner image
$bannerSvg = '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="400" viewBox="0 0 1200 400">
<rect width="1200" height="400" fill="#1b4332" />
<circle cx="1000" cy="200" r="250" fill="#c59a58" opacity="0.15" />
<text x="80" y="160" fill="#c59a58" font-family="sans-serif" font-size="16" letter-spacing="4">EXHIBITION SALON 2026</text>
<text x="80" y="230" fill="#ffffff" font-family="Georgia,serif" font-size="48" font-weight="bold">Masterworks in Oil &amp; Gold</text>
<text x="80" y="280" fill="#e8e4df" font-family="sans-serif" font-size="18">Original certified fine art pieces directly from master atelier artists</text>
</svg>';
file_put_contents('assets/images/banner.jpg', $bannerSvg);
file_put_contents('assets/images/banner.svg', $bannerSvg);

echo "Art assets generated successfully.\n";

