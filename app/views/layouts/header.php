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

    <!-- Master Stylesheet -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/style.css?v=<?= APP_VERSION ?>">

    <!-- Dynamic Site Global Configuration -->
    <script>
        window.SITE_URL = '<?= SITE_URL ?>';
        if (window.location.protocol === 'https:' && window.SITE_URL.startsWith('http:')) {
            window.SITE_URL = window.SITE_URL.replace(/^http:/, 'https:');
        }
    </script>
</head>
<body class="bg-[#FBFBFA] text-[#18181B] min-h-screen flex flex-col font-sans antialiased overflow-x-clip selection:bg-[#18181B] selection:text-white">

    <!-- Sticky Full-Width Header -->
    <header id="siteMasterHeader" class="site-sticky-header w-full bg-[#FBFBFA]/95 backdrop-blur-md sticky top-0 z-50 border-b border-black/[0.06] shadow-xs transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 sm:py-4">
            
            <!-- 1. Top Modular Capsule Navigation Bar -->
            <div class="flex items-center justify-between gap-2.5 sm:gap-4 flex-wrap lg:flex-nowrap">
                
                <!-- Left: Brand Capsule + Collection Capsule -->
                <div class="flex items-center gap-2 sm:gap-2.5">
                    <!-- Brand Capsule -->
                    <a href="<?= SITE_URL ?>" class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 bg-white hover:bg-stone-50 border border-black/[0.06] rounded-full shadow-xs transition-transform active:scale-95 group">
                        <div class="w-5 h-5 rounded-full bg-[#18181B] text-white flex items-center justify-center font-bold text-[10px] shadow-xs">
                            <span>✦</span>
                        </div>
                        <span class="font-heading font-extrabold text-sm sm:text-base tracking-tight text-[#18181B]">JayArt</span>
                    </a>

                    <!-- Collection / Breadcrumb Capsule -->
                    <div class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 bg-white border border-black/[0.06] rounded-full shadow-xs text-xs font-semibold text-stone-700">
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
