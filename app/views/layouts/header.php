<?php
/**
 * JayArt Master Header Layout
 * Full Screen Edge-to-Edge Architectural Atelier Design
 * Seamless Floating Modular Capsule Navigation
 */

$cartCount = Cart::getCount();
$currentUser = Auth::user();
$categoryModel = new Category();
$navCategories = $categoryModel->getActiveWithCounts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    
    <!-- Dynamic SEO & Webmaster Integrations (GA4, GSC, Meta Pixel, Bing) -->
    <?= SEO::renderHead($seo ?? []) ?>

    <!-- Google Web Fonts (Outfit Display + Plus Jakarta Sans Body + JetBrains Mono) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Local Production Compiled Tailwind CSS (Fast, 100% Offline, cPanel Ready, Zero-CDN Dependency) -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/tailwind.min.css?v=<?= APP_VERSION ?>">

    <!-- Master Stylesheet (With automatic filemtime cache-busting) -->
    <?php 
    $styleVer = defined('APP_VERSION') ? APP_VERSION : '1.0.2';
    $styleFile = ROOT_PATH . '/assets/css/style.css';
    if (file_exists($styleFile)) {
        $styleVer .= '.' . filemtime($styleFile);
    }
    ?>
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css?v=<?= $styleVer ?>">

    <!-- Dynamic Artistic Wallpaper Variable (BG.jpeg & canvas-weave.svg with filemtime cache-busting) -->
    <?php 
    $bgVer = '1';
    $bgPath = ROOT_PATH . '/assets/images/BG.jpeg';
    if (file_exists($bgPath)) {
        $bgVer = filemtime($bgPath);
    }
    $canvasVer = '1';
    $canvasPath = ROOT_PATH . '/assets/images/canvas-weave.svg';
    if (file_exists($canvasPath)) {
        $canvasVer = filemtime($canvasPath);
    }
    ?>
    <style>
        :root {
            --site-bg-image: url('<?= ASSETS_URL ?>/images/BG.jpeg?v=<?= $bgVer ?>');
            --site-canvas-weave: url('<?= ASSETS_URL ?>/images/canvas-weave.svg?v=<?= $canvasVer ?>');
        }
    </style>

    <!-- Dynamic Site Global Configuration -->
    <script>
        window.SITE_URL = '<?= SITE_URL ?>';
        if (window.location.protocol === 'https:' && window.SITE_URL.startsWith('http:')) {
            window.SITE_URL = window.SITE_URL.replace(/^http:/, 'https:');
        }
    </script>

    <!-- Critical Inlined Preloader CSS (Zero FOUC, Instant Full-Screen Render) -->
    <style id="jayartPreloaderCritical">
        #jayartPreloader {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 99999999 !important;
            background: #FAF6F0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 !important;
            padding: 0 !important;
            box-sizing: border-box !important;
            transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.4s cubic-bezier(0.16, 1, 0.3, 1) !important;
            pointer-events: all !important;
            user-select: none !important;
        }
        #jayartPreloader.preloader-hidden {
            opacity: 0 !important;
            visibility: hidden !important;
            pointer-events: none !important;
        }
        .sculpture-loader-content {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            padding: 24px !important;
            box-sizing: border-box !important;
            animation: sculptureFadeUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
        }
        .sculpture-wrapper {
            position: relative !important;
            width: 100px !important;
            height: 120px !important;
            margin: 0 auto 16px auto !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        .sculpture-ambient-glow {
            position: absolute !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            width: 140px !important;
            height: 140px !important;
            background: radial-gradient(circle, rgba(197, 154, 88, 0.25) 0%, rgba(197, 154, 88, 0.05) 50%, transparent 70%) !important;
            border-radius: 9999px !important;
            filter: blur(8px) !important;
            animation: sculpturePulse 2.4s ease-in-out infinite !important;
            pointer-events: none !important;
        }
        .sculpture-svg {
            width: 100px !important;
            height: 120px !important;
            display: block !important;
            filter: drop-shadow(0 6px 14px rgba(24, 24, 27, 0.08)) !important;
            animation: sculptureFloat 3s ease-in-out infinite !important;
        }
        .sculpture-halo {
            animation: sculptureSpin 16s linear infinite !important;
            transform-origin: 100px 105px !important;
        }
        .sculpture-chisel-line {
            stroke-dasharray: 120 !important;
            stroke-dashoffset: 120 !important;
            animation: sculptureDraw 2.4s ease-in-out infinite alternate !important;
        }
        .sculpture-base-line {
            width: 50px !important;
            height: 2px !important;
            background: linear-gradient(90deg, transparent, #C59A58, transparent) !important;
            margin: 6px auto 0 auto !important;
        }
        .sculpture-brand {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            gap: 2px !important;
            margin: 0 auto 14px auto !important;
        }
        .sculpture-brand-title {
            font-family: 'Outfit', -apple-system, sans-serif !important;
            font-weight: 800 !important;
            font-size: 1.15rem !important;
            letter-spacing: 0.1em !important;
            text-transform: uppercase !important;
            color: #18181B !important;
            line-height: 1.2 !important;
        }
        .sculpture-brand-sub {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 0.65rem !important;
            letter-spacing: 0.16em !important;
            text-transform: uppercase !important;
            color: #71717A !important;
            font-weight: 600 !important;
        }
        .sculpture-progress-track {
            width: 130px !important;
            height: 2px !important;
            background: rgba(24, 24, 27, 0.08) !important;
            border-radius: 9999px !important;
            overflow: hidden !important;
            position: relative !important;
            margin: 0 auto 10px auto !important;
        }
        .sculpture-progress-bar {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            height: 100% !important;
            width: 40% !important;
            background: linear-gradient(90deg, #C59A58, #E5C388, #C59A58) !important;
            border-radius: 9999px !important;
            animation: sculptureTrack 1.5s ease-in-out infinite !important;
        }
        .sculpture-status-text {
            font-size: 0.65rem !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            color: #A1A1AA !important;
            letter-spacing: 0.06em !important;
            text-transform: uppercase !important;
            font-weight: 500 !important;
        }
        @keyframes sculptureFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        @keyframes sculpturePulse {
            0%, 100% { transform: translate(-50%, -50%) scale(0.92); opacity: 0.6; }
            50% { transform: translate(-50%, -50%) scale(1.15); opacity: 1; }
        }
        @keyframes sculptureDraw {
            0% { stroke-dashoffset: 120; opacity: 0.3; }
            50% { stroke-dashoffset: 0; opacity: 1; }
            100% { stroke-dashoffset: -120; opacity: 0.3; }
        }
        @keyframes sculptureSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes sculptureTrack {
            0% { left: -40%; width: 30%; }
            50% { left: 35%; width: 50%; }
            100% { left: 100%; width: 30%; }
        }
        @keyframes sculptureFadeUp {
            0% { opacity: 0; transform: translateY(8px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="site-body-bg text-[#18181B] min-h-screen flex flex-col font-sans antialiased overflow-x-clip selection:bg-[#18181B] selection:text-white">

    <!-- Classical Sculpture Atelier Preloader -->
    <div id="jayartPreloader" class="jayart-preloader" aria-hidden="true" style="position:fixed;top:0;left:0;right:0;bottom:0;width:100vw;height:100vh;background:#FAF6F0;z-index:99999999;display:flex;align-items:center;justify-content:center;margin:0;padding:0;">
        <div class="sculpture-loader-content" style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;">
            <div class="sculpture-wrapper" style="position:relative;width:100px;height:120px;margin:0 auto 16px auto;">
                <div class="sculpture-ambient-glow"></div>
                <!-- Inline Classical Sculpture SVG -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 240" fill="none" class="sculpture-svg" width="100" height="120" style="width:100px;height:120px;display:block;">
                    <defs>
                        <linearGradient id="sculptureGoldGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="#C59A58" />
                            <stop offset="50%" stop-color="#E5C388" />
                            <stop offset="100%" stop-color="#9A7432" />
                        </linearGradient>
                    </defs>

                    <!-- Ambient Golden Aura Ring -->
                    <circle cx="100" cy="105" r="70" stroke="url(#sculptureGoldGrad)" stroke-width="1" stroke-dasharray="4 6" opacity="0.4" class="sculpture-halo" />

                    <!-- Pedestal / Socle Base -->
                    <path d="M65 224 H135 V228 C135 230 133 232 131 232 H69 C67 232 65 230 65 228 V224 Z" fill="#18181B" />
                    <path d="M72 216 H128 L131 224 H69 L72 216 Z" fill="#27272A" stroke="#C59A58" stroke-width="1" />
                    <path d="M80 200 H120 V216 H80 V200 Z" fill="#18181B" />
                    <path d="M76 196 H124 L122 200 H78 L76 196 Z" fill="#C59A58" />

                    <!-- Draped Classical Torso & Chest -->
                    <path d="M42 188 C50 170 70 162 86 160 C90 174 110 174 114 160 C130 162 150 170 158 188 C144 198 126 202 100 202 C74 202 56 198 42 188 Z" fill="#27272A" stroke="#18181B" stroke-width="1.5" />
                    
                    <!-- Toga Folds / Chiseled Drapery Lines -->
                    <path d="M54 184 C68 174 84 172 100 174" stroke="#C59A58" stroke-width="1.5" stroke-linecap="round" fill="none" opacity="0.8" />
                    <path d="M62 192 C78 182 96 182 110 186" stroke="#3F3F46" stroke-width="1.2" stroke-linecap="round" fill="none" />
                    <path d="M146 184 C132 174 116 172 100 174" stroke="#C59A58" stroke-width="1.2" stroke-linecap="round" fill="none" opacity="0.6" />
                    <path d="M100 174 V198" stroke="#3F3F46" stroke-width="1" stroke-linecap="round" />

                    <!-- Neck & Musculature -->
                    <path d="M86 134 C86 148 84 160 84 162 C94 166 106 166 116 162 C116 160 114 148 114 134 Z" fill="#3F3F46" stroke="#18181B" stroke-width="1" />
                    <path d="M96 140 C95 148 94 156 90 162" stroke="#52525B" stroke-width="1.2" stroke-linecap="round" fill="none" />
                    <path d="M104 140 C105 148 106 156 110 162" stroke="#52525B" stroke-width="1.2" stroke-linecap="round" fill="none" />

                    <!-- Head Contour & Jawline (Classical Greco-Roman Portrait) -->
                    <path d="M72 90 C70 108 72 122 82 134 C90 144 100 146 104 146 C112 144 122 136 128 124 C132 112 132 96 130 90 Z" fill="#27272A" stroke="#18181B" stroke-width="1.5" />

                    <!-- Facial Planes & Features -->
                    <path d="M75 96 L86 112 L84 130 L78 124 Z" fill="#18181B" opacity="0.4" />
                    <path d="M98 76 L96 110 L102 112 L103 108 L100 76 Z" fill="#D4D4D8" />
                    <path d="M96 110 L92 112 L96 114 L102 112 Z" fill="#A1A1AA" />

                    <!-- Brows & Eyes -->
                    <path d="M82 82 C86 80 92 80 96 82" stroke="#18181B" stroke-width="2" stroke-linecap="round" fill="none" />
                    <path d="M104 82 C108 80 114 80 118 82" stroke="#18181B" stroke-width="2" stroke-linecap="round" fill="none" />
                    <path d="M84 87 C87 85 91 85 94 88 C91 90 87 90 84 87 Z" fill="#18181B" />
                    <path d="M106 88 C109 85 113 85 116 87 C113 90 109 90 106 88 Z" fill="#18181B" />

                    <!-- Sculpted Lips & Chin -->
                    <path d="M93 124 C97 122 103 122 107 124 C103 127 97 127 93 124 Z" fill="#18181B" />
                    <path d="M95 128 C98 130 102 130 105 128" stroke="#18181B" stroke-width="1.2" stroke-linecap="round" fill="none" />
                    <path d="M98 138 C99 140 101 140 102 138" stroke="#52525B" stroke-width="1.5" stroke-linecap="round" fill="none" />

                    <!-- Classical Renaissance Sculpted Hair Locks -->
                    <path d="M70 82 C66 74 68 64 74 58 C80 52 86 54 88 50 C92 44 100 42 106 46 C112 42 122 44 126 52 C132 54 136 62 134 70 C138 76 136 86 130 92 C126 84 124 76 118 74 C112 72 108 76 102 72 C96 68 90 70 86 76 C82 74 76 76 72 82 Z" fill="#18181B" stroke="#C59A58" stroke-width="1.2" />
                    <path d="M76 60 C82 64 80 72 74 74" stroke="#C59A58" stroke-width="1.5" stroke-linecap="round" fill="none" />
                    <path d="M88 54 C94 58 92 68 86 70" stroke="#C59A58" stroke-width="1.5" stroke-linecap="round" fill="none" />
                    <path d="M104 48 C110 52 108 62 102 64" stroke="#C59A58" stroke-width="1.5" stroke-linecap="round" fill="none" />
                    <path d="M118 54 C124 58 122 68 116 70" stroke="#C59A58" stroke-width="1.5" stroke-linecap="round" fill="none" />
                    <path d="M128 66 C134 72 130 80 124 82" stroke="#C59A58" stroke-width="1.5" stroke-linecap="round" fill="none" />
                    <path d="M68 76 C64 82 66 90 70 94 C68 98 70 104 74 106" stroke="#C59A58" stroke-width="1.5" stroke-linecap="round" fill="none" />
                    <path d="M132 78 C136 84 134 92 130 96 C132 100 130 106 126 108" stroke="#C59A58" stroke-width="1.5" stroke-linecap="round" fill="none" />

                    <!-- Gold Atelier Chisel Contour Line -->
                    <path d="M42 188 C50 170 70 162 86 160 C90 174 110 174 114 160 C130 162 150 170 158 188" stroke="url(#sculptureGoldGrad)" stroke-width="2" stroke-linecap="round" fill="none" class="sculpture-chisel-line" />
                </svg>
                <div class="sculpture-base-line"></div>
            </div>

            <div class="sculpture-brand">
                <img src="<?= ASSETS_URL ?>/images/logo.svg?v=<?= APP_VERSION ?>" alt="JayArt" class="h-8 w-auto object-contain mx-auto mb-1">
                <span class="sculpture-brand-sub">Fine Art Atelier • 2026</span>
            </div>

            <div class="sculpture-progress-track">
                <div class="sculpture-progress-bar"></div>
            </div>
            <span class="sculpture-status-text">Curating Gallery Masterworks...</span>
        </div>
    </div>
    <noscript>
        <style>#jayartPreloader { display: none !important; }</style>
    </noscript>
    <script>
        // Inline early dismissal fail-safe
        (function() {
            var startTime = Date.now();
            function closeEarly() {
                var p = document.getElementById('jayartPreloader');
                if (!p) return;
                var diff = Date.now() - startTime;
                var delay = Math.max(0, 320 - diff);
                setTimeout(function() {
                    p.classList.add('preloader-hidden');
                    p.style.opacity = '0';
                    p.style.pointerEvents = 'none';
                    setTimeout(function() { if (p && p.parentNode) p.parentNode.removeChild(p); }, 450);
                }, delay);
            }
            if (document.readyState === 'complete') {
                closeEarly();
            } else {
                window.addEventListener('load', closeEarly);
                setTimeout(closeEarly, 1600);
            }
        })();
    </script>

    <!-- Sticky Full-Width Header -->
    <header id="siteMasterHeader" class="site-sticky-header w-full bg-[#FAF6F0]/90 backdrop-blur-md sticky top-0 z-50 border-b border-[#B67A31]/15 shadow-xs transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 sm:py-4">
            
            <!-- 1. Top Modular Capsule Navigation Bar -->
            <div class="flex items-center justify-between gap-2.5 sm:gap-4 flex-wrap lg:flex-nowrap">
                
                <!-- Left: Brand Logo + Atelier Salon Tag -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="<?= SITE_URL ?>" class="inline-flex items-center focus:outline-none transition-transform hover:opacity-90 active:scale-95 py-0.5" title="JayArt Fine Art Atelier">
                        <img src="<?= ASSETS_URL ?>/images/logo.svg?v=<?= APP_VERSION ?>" alt="JayArt" class="h-9 sm:h-11 w-auto object-contain">
                    </a>

                    <!-- Collection / Breadcrumb Capsule -->
                    <div class="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-black/[0.06] rounded-full shadow-2xs text-[11px] font-semibold text-stone-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>Salon 2026</span>
                        <span class="text-stone-300">/</span>
                        <a href="<?= SITE_URL ?>/shop" class="text-stone-500 hover:text-black">Paintings</a>
                    </div>
                </div>

                <!-- Center: Search Capsule Bar with Black Circular Icon -->
                <div class="flex-1 max-w-lg order-last lg:order-none w-full lg:w-auto mt-2 lg:mt-0">
                    <form method="GET" action="<?= SITE_URL ?>/shop" class="relative flex items-center">
                        <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Search original paintings, mediums, artists..." class="w-full bg-white text-xs sm:text-sm text-[#18181B] rounded-full pl-5 pr-12 py-2 sm:py-2.5 border border-black/[0.06] focus:outline-none focus:ring-2 focus:ring-black/10 shadow-xs placeholder-stone-400">
                        <button type="submit" aria-label="Search" class="absolute right-1.5 w-7 sm:w-8 h-7 sm:h-8 rounded-full bg-[#18181B] hover:bg-black text-white flex items-center justify-center shadow-xs transition-transform active:scale-90">
                            <svg width="13" height="13" style="width: 13px; height: 13px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </form>
                </div>

                <!-- Right Action Capsules -->
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <!-- Currency Selector Capsule Dropdown -->
                    <?php 
                    $activeCurr = Currency::getActive();
                    $enabledCurrs = Currency::getEnabled();
                    ?>
                    <div class="relative" id="siteCurrencyDropdown">
                        <button type="button" onclick="toggleCurrencyDropdown(event)" class="inline-flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-3 py-1.5 sm:py-2 bg-white hover:bg-stone-50 border border-black/[0.06] rounded-full shadow-xs text-xs font-bold text-stone-800 transition-colors" title="Change Currency">
                            <span class="font-mono text-stone-500 text-[11px]"><?= htmlspecialchars($activeCurr['symbol']) ?></span>
                            <span class="text-[11px] sm:text-xs"><?= htmlspecialchars($activeCurr['code']) ?></span>
                            <svg class="w-3 h-3 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="siteCurrencyMenu" class="hidden absolute right-0 mt-2 w-48 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-black/[0.06] py-1.5 z-50 overflow-hidden text-left">
                            <div class="px-3.5 py-1 text-[10px] font-bold text-stone-400 uppercase tracking-wider">Select Currency</div>
                            <?php foreach ($enabledCurrs as $c): ?>
                                <a href="<?= SITE_URL ?>/currency/switch/<?= $c['code'] ?>" class="flex items-center justify-between px-3.5 py-2 text-xs hover:bg-stone-100 transition-colors <?= $c['code'] === $activeCurr['code'] ? 'font-bold text-black bg-stone-50' : 'text-stone-700' ?>">
                                    <span class="flex items-center gap-2">
                                        <span class="w-5 text-center font-mono text-stone-500"><?= htmlspecialchars($c['symbol']) ?></span>
                                        <span><?= htmlspecialchars($c['name']) ?></span>
                                    </span>
                                    <span class="text-[10px] font-mono text-stone-400"><?= htmlspecialchars($c['code']) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <a href="<?= SITE_URL ?>/offers" class="hidden md:inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-stone-50 border border-black/[0.06] rounded-full shadow-xs text-xs font-semibold text-stone-700 transition-colors">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span>Offers</span>
                    </a>

                    <!-- Wishlist Pill -->
                    <?php 
                    $sessionWishlist = $_SESSION['wishlist'] ?? [];
                    $headerWishCount = is_array($sessionWishlist) ? count($sessionWishlist) : 0;
                    ?>
                    <a href="<?= SITE_URL ?>/profile/wishlist" class="relative w-9 sm:w-10 h-9 sm:h-10 rounded-full bg-white hover:bg-stone-50 border border-black/[0.06] flex items-center justify-center shadow-xs text-red-500 hover:text-red-600 transition-colors" title="Wishlist">
                        <svg width="16" height="16" style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        <span id="wishlistCountBadge" class="<?= $headerWishCount > 0 ? '' : 'hidden' ?> absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center font-mono">
                            <?= $headerWishCount ?>
                        </span>
                    </a>

                    <!-- Shopping Bag Pill -->
                    <a href="<?= SITE_URL ?>/cart" class="inline-flex items-center gap-1.5 px-3.5 py-2 sm:py-2.5 bg-white hover:bg-stone-50 border border-black/[0.06] rounded-full shadow-xs text-[#18181B] transition-colors" title="Cart Bag">
                        <svg width="16" height="16" style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <span id="cartCountBadge" class="bg-[#18181B] text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center font-mono">
                            <?= $cartCount ?>
                        </span>
                    </a>

                    <!-- Profile Capsule -->
                    <?php if ($currentUser): ?>
                        <a href="<?= SITE_URL ?>/profile" class="inline-flex items-center gap-2 pl-1 pr-3 py-1 bg-white hover:bg-stone-50 border border-black/[0.06] rounded-full shadow-xs transition-colors">
                            <div class="w-7 h-7 rounded-full bg-stone-200 text-stone-700 font-bold text-xs flex items-center justify-center overflow-hidden">
                                <?= strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <span class="hidden sm:inline text-xs font-semibold text-[#18181B]"><?= htmlspecialchars(explode(' ', $currentUser['name'] ?? 'Collector')[0]) ?></span>
                        </a>
                    <?php else: ?>
                        <a href="<?= SITE_URL ?>/login" class="inline-flex items-center gap-2 pl-1.5 pr-3.5 py-1.5 bg-white hover:bg-stone-50 border border-black/[0.06] rounded-full shadow-xs transition-colors">
                            <div class="w-6 h-6 rounded-full bg-stone-100 text-stone-500 flex items-center justify-center text-[10px]">
                                <svg width="12" height="12" style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <span class="text-xs font-semibold text-[#18181B]">Sign In</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php
            $reqUri = $_SERVER['REQUEST_URI'] ?? '';
            $reqCategory = $_GET['category'] ?? '';
            $reqFilter = $_GET['filter'] ?? '';
            $reqMedium = $_GET['medium'] ?? '';

            $activeKey = 'all';
            if (str_contains($reqUri, '/offers')) {
                $activeKey = 'offers';
            } elseif (!empty($reqCategory)) {
                if ($reqCategory === 'oil-paintings') $activeKey = 'oil';
                elseif ($reqCategory === 'acrylic-art') $activeKey = 'acrylic';
                elseif ($reqCategory === 'watercolor-paintings') $activeKey = 'watercolor';
                elseif ($reqCategory === 'modern-abstract') $activeKey = 'abstract';
                elseif ($reqCategory === 'traditional-heritage') $activeKey = 'traditional';
            } elseif (!empty($reqFilter)) {
                if ($reqFilter === 'gold') $activeKey = 'gold';
                elseif ($reqFilter === 'charcoal') $activeKey = 'charcoal';
            } elseif (!empty($reqMedium)) {
                $mLow = strtolower($reqMedium);
                if (str_contains($mLow, 'oil')) $activeKey = 'oil';
                elseif (str_contains($mLow, 'acrylic')) $activeKey = 'acrylic';
                elseif (str_contains($mLow, 'watercolor')) $activeKey = 'watercolor';
                elseif (str_contains($mLow, 'mixed')) $activeKey = 'abstract';
            }
            ?>

            <?php 
            $requestPath = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
            $isHome = ($requestPath === '' || $requestPath === 'index.php');
            ?>
            <?php if (!$isHome): ?>
            <!-- 2. Sub-Nav Category Capsule Bar (Scrollbar-Free, Native Swipe & Desktop Drag) -->
            <div id="categoryCapsuleBar" class="mt-3 sm:mt-3.5 flex items-center gap-2 overflow-x-auto scrollbar-none pb-1.5 -mx-1 px-1 select-none cursor-grab active:cursor-grabbing" style="scrollbar-width: none; -ms-overflow-style: none;">
                <!-- Filter Toggle Pill Button -->
                <button type="button" onclick="openAllFiltersModal()" class="w-9 h-9 rounded-full bg-white hover:bg-stone-100 border border-black/[0.08] flex items-center justify-center text-stone-800 shadow-xs flex-shrink-0 transition-transform active:scale-95" title="All Art Filters">
                    <svg width="15" height="15" style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                </button>

                <!-- Category Capsule Links / Buttons -->
                <a href="<?= SITE_URL ?>/shop" onclick="return handleCategoryPillClick(event, this, 'all', '<?= SITE_URL ?>/shop')" class="category-capsule-pill <?= ($activeKey === 'all') ? 'active' : '' ?>" data-cat="all">All Paintings</a>
                <a href="<?= SITE_URL ?>/shop?category=oil-paintings" onclick="return handleCategoryPillClick(event, this, 'oil', '<?= SITE_URL ?>/shop?category=oil-paintings')" class="category-capsule-pill <?= ($activeKey === 'oil') ? 'active' : '' ?>" data-cat="oil">Oil on Linen</a>
                <a href="<?= SITE_URL ?>/shop?category=acrylic-art" onclick="return handleCategoryPillClick(event, this, 'acrylic', '<?= SITE_URL ?>/shop?category=acrylic-art')" class="category-capsule-pill <?= ($activeKey === 'acrylic') ? 'active' : '' ?>" data-cat="acrylic">Heavy Acrylic</a>
                <a href="<?= SITE_URL ?>/shop?category=watercolor-paintings" onclick="return handleCategoryPillClick(event, this, 'watercolor', '<?= SITE_URL ?>/shop?category=watercolor-paintings')" class="category-capsule-pill <?= ($activeKey === 'watercolor') ? 'active' : '' ?>" data-cat="watercolor">Watercolor</a>
                <a href="<?= SITE_URL ?>/shop?category=modern-abstract" onclick="return handleCategoryPillClick(event, this, 'abstract', '<?= SITE_URL ?>/shop?category=modern-abstract')" class="category-capsule-pill <?= ($activeKey === 'abstract') ? 'active' : '' ?>" data-cat="abstract">Modern Abstract</a>
                <a href="<?= SITE_URL ?>/shop?category=traditional-heritage" onclick="return handleCategoryPillClick(event, this, 'traditional', '<?= SITE_URL ?>/shop?category=traditional-heritage')" class="category-capsule-pill <?= ($activeKey === 'traditional') ? 'active' : '' ?>" data-cat="traditional">Traditional Heritage</a>
                <a href="<?= SITE_URL ?>/shop?filter=gold" onclick="return handleCategoryPillClick(event, this, 'gold', '<?= SITE_URL ?>/shop?filter=gold')" class="category-capsule-pill <?= ($activeKey === 'gold') ? 'active' : '' ?>" data-cat="gold">24K Gold Leaf</a>
                <a href="<?= SITE_URL ?>/shop?filter=charcoal" onclick="return handleCategoryPillClick(event, this, 'charcoal', '<?= SITE_URL ?>/shop?filter=charcoal')" class="category-capsule-pill <?= ($activeKey === 'charcoal') ? 'active' : '' ?>" data-cat="charcoal">Charcoal Mono</a>
                <a href="<?= SITE_URL ?>/offers" onclick="return handleCategoryPillClick(event, this, 'offers', '<?= SITE_URL ?>/offers')" class="category-capsule-pill bg-stone-100 hover:bg-stone-200 text-[#18181B] font-bold border-black/[0.08] <?= ($activeKey === 'offers') ? 'active' : '' ?>" data-cat="offers">Salon Offers</a>
            </div>
            <?php endif; ?>
        </div>
    </header>

    <script>
    function openAllFiltersModal() {
        const modal = document.getElementById('allFiltersModal');
        const card = document.getElementById('allFiltersModalCard');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            if (card) {
                setTimeout(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        }
    }

    function closeAllFiltersModal() {
        const modal = document.getElementById('allFiltersModal');
        const card = document.getElementById('allFiltersModalCard');
        if (card) {
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
        }
        setTimeout(() => {
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
            document.body.style.overflow = '';
        }, 150);
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('allFiltersModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeAllFiltersModal();
            }
        }
    });

    function handleModalFilter(type, val, label, targetUrl) {
        closeAllFiltersModal();
        if (document.getElementById('paintingsGrid')) {
            if (type === 'sort' && typeof selectSort === 'function') {
                selectSort(val, label);
            } else if (typeof selectFilter === 'function') {
                selectFilter(type, val, label);
            }
            const target = document.getElementById('catalogHeading') || document.getElementById('paintingsGrid');
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            window.location.href = targetUrl;
        }
    }

    function handleModalReset(targetUrl) {
        closeAllFiltersModal();
        if (document.getElementById('paintingsGrid') && typeof resetAllFilters === 'function') {
            resetAllFilters();
            const target = document.getElementById('catalogHeading') || document.getElementById('paintingsGrid');
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            window.location.href = targetUrl;
        }
    }

    function handleCategoryPillClick(event, el, key, targetUrl) {
        const grid = document.getElementById('paintingsGrid');
        
        // IF ON HOME PAGE (interactive in-place grid present) -> Filter in-place without page redirection!
        if (grid && typeof selectFilter === 'function') {
            event.preventDefault();

            // 1. Update visual active state across all category capsule pills
            document.querySelectorAll('#categoryCapsuleBar .category-capsule-pill').forEach(pill => pill.classList.remove('active'));
            el.classList.add('active');

            // 2. Apply Instant Filter
            if (key === 'all') {
                if (typeof resetAllFilters === 'function') resetAllFilters();
            } else if (key === 'oil') {
                selectFilter('medium', 'oil', 'Oil on Linen');
            } else if (key === 'acrylic') {
                selectFilter('medium', 'acrylic', 'Heavy Acrylic');
            } else if (key === 'watercolor') {
                selectFilter('medium', 'watercolor', 'Watercolor');
            } else if (key === 'abstract') {
                selectFilter('medium', 'mixed', 'Modern Abstract');
            } else if (key === 'traditional') {
                selectFilter('medium', 'traditional', 'Traditional Heritage');
            } else if (key === 'gold') {
                selectFilter('color', 'gold', '24K Gold Leaf');
            } else if (key === 'charcoal') {
                selectFilter('color', 'monochrome', 'Charcoal Mono');
            } else if (key === 'offers') {
                const offersSection = document.getElementById('collectorOffersSection');
                if (offersSection) {
                    offersSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                } else {
                    window.location.href = targetUrl;
                    return false;
                }
            }

            // 3. Smooth scroll down to paintings catalog grid
            const catalogHeading = document.getElementById('catalogHeading') || grid;
            if (catalogHeading) {
                catalogHeading.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            return false;
        }

        // On other pages (or /shop), allow natural navigation to targetUrl
        return true;
    }

    // Drag-to-Scroll support for category capsule bar on desktop/laptop
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('categoryCapsuleBar');
        if (!slider) return;
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('cursor-grabbing');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener('mouseleave', () => { isDown = false; slider.classList.remove('cursor-grabbing'); });
        slider.addEventListener('mouseup', () => { isDown = false; slider.classList.remove('cursor-grabbing'); });
        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 1.5;
            slider.scrollLeft = scrollLeft - walk;
        });
    });
    </script>

    <!-- Main Content Full-Screen Container Begins -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-7 flex-1 w-full">
