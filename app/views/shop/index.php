<?php
/**
 * JayArt Pinterest-Inspired Shop & Fine Art Gallery Catalog
 * Fluid Multi-Column Masonry Art Feed, Instant Topic Discovery & Quick Inspect Lightbox
 */

$products = is_array($products ?? null) ? $products : (is_array($paintings ?? null) ? $paintings : (is_array($items ?? null) ? $items : []));
$total = (int)($total ?? count($products));
$selectedCat = $filters['category'] ?? '';
$selectedMedium = $filters['medium'] ?? '';
$selectedSort = $filters['sort'] ?? '';
$selectedMinPrice = $filters['min_price'] ?? '';
$selectedMaxPrice = $filters['max_price'] ?? '';
$searchQuery = $filters['search'] ?? '';

$hasActiveFilters = !empty($selectedCat) || !empty($selectedMedium) || !empty($selectedSort) || $selectedMinPrice !== '' || !empty($searchQuery);
?>

<!-- ====================================================================
     1. PINTEREST CATALOG HEADER & TOPIC DISCOVERY BAR
     ==================================================================== -->
<div class="bg-white border-b border-stone-200/60 sticky top-0 sm:top-0 z-30 backdrop-blur-md bg-white/95">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-3 sm:py-4">
        
        <!-- Breadcrumbs & Quick Stats -->
        <div class="flex items-center justify-between text-xs text-stone-400 mb-2">
            <nav class="flex items-center gap-1.5 font-medium" aria-label="Breadcrumb">
                <a href="<?= SITE_URL ?>" class="hover:text-black transition-colors">Home</a>
                <span>/</span>
                <span class="text-[#18181B] font-bold">Original Art Catalog</span>
            </nav>
            <span class="text-[11px] font-bold text-stone-500 bg-stone-100 px-2.5 py-0.5 rounded-full">
                <?= (int)$total ?> Original Works
            </span>
        </div>

        <!-- Title Row + Filter & Sort Controls -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-heading font-extrabold text-[#18181B] tracking-tight">
                    <?= !empty($searchQuery) ? 'Results for "' . htmlspecialchars($searchQuery) . '"' : (!empty($selectedCat) ? htmlspecialchars(ucwords(str_replace('-', ' ', $selectedCat))) : 'Visual Art Gallery') ?>
                </h1>
                <p class="text-xs text-stone-500 mt-0.5 hidden sm:block">
                    Discover original handmade art with certified museum provenance and natural aspect framing.
                </p>
            </div>

            <div class="flex items-center gap-2 sm:gap-3 flex-wrap">
                <!-- Mobile Filter Drawer Trigger -->
                <button type="button" id="openMobileFilters" class="lg:hidden inline-flex items-center gap-1.5 px-3.5 py-2 bg-stone-100 hover:bg-stone-200 rounded-full text-xs font-bold text-stone-800 transition-colors cursor-pointer">
                    <svg class="w-4 h-4 text-stone-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    <span>Filters <?= $hasActiveFilters ? '• Active' : '' ?></span>
                </button>

                <!-- Sort Dropdown Form -->
                <form id="sortForm" method="GET" class="flex items-center gap-2">
                    <?php if (!empty($selectedCat)): ?><input type="hidden" name="category" value="<?= htmlspecialchars($selectedCat) ?>"><?php endif; ?>
                    <?php if (!empty($selectedMedium)): ?><input type="hidden" name="medium" value="<?= htmlspecialchars($selectedMedium) ?>"><?php endif; ?>
                    <?php if ($selectedMinPrice !== ''): ?><input type="hidden" name="min_price" value="<?= htmlspecialchars((string)$selectedMinPrice) ?>"><?php endif; ?>
                    <?php if (!empty($selectedMaxPrice)): ?><input type="hidden" name="max_price" value="<?= htmlspecialchars((string)$selectedMaxPrice) ?>"><?php endif; ?>
                    <?php if (!empty($searchQuery)): ?><input type="hidden" name="search" value="<?= htmlspecialchars($searchQuery) ?>"><?php endif; ?>
                    
                    <select id="sortSelect" name="sort" onchange="this.form.submit()" class="px-3 sm:px-4 py-2 bg-stone-100 rounded-full text-xs font-bold text-stone-800 border-none focus:outline-none focus:ring-2 focus:ring-black/10 cursor-pointer shadow-xs">
                        <option value="" <?= ($selectedSort === '') ? 'selected' : '' ?>>Sort: Curated Feed</option>
                        <option value="newest" <?= ($selectedSort === 'newest') ? 'selected' : '' ?>>Sort: Latest Additions</option>
                        <option value="popular" <?= ($selectedSort === 'popular') ? 'selected' : '' ?>>Sort: Most Loved</option>
                        <option value="price_low" <?= ($selectedSort === 'price_low') ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_high" <?= ($selectedSort === 'price_high') ? 'selected' : '' ?>>Price: High to Low</option>
                    </select>
                </form>

                <?php if ($hasActiveFilters): ?>
                    <a href="<?= SITE_URL ?>/shop" class="text-xs font-bold text-red-600 hover:text-red-700 bg-red-50 px-3 py-1.5 rounded-full transition-colors">
                        Reset All ✕
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Horizontal Scrollable Pinterest Topic Pills (Tactile 1-Thumb Filter on Mobile) -->
        <div class="mt-3 pt-2.5 border-t border-stone-100">
            <div class="pinterest-scroll-row">
                <a href="<?= SITE_URL ?>/shop" class="pinterest-topic-pill <?= empty($selectedCat) && empty($selectedMedium) && $selectedMinPrice === '' ? 'active' : '' ?>">
                    All Inspirations
                </a>
                
                <!-- Category Pills -->
                <?php foreach ($categories as $cat): 
                    $count = (int)($cat['product_count'] ?? 0);
                    if ($count <= 0 && $selectedCat !== $cat['slug']) continue;
                ?>
                    <a href="<?= SITE_URL ?>/shop?category=<?= htmlspecialchars($cat['slug']) ?><?= !empty($selectedMedium) ? '&medium=' . urlencode($selectedMedium) : '' ?>" class="pinterest-topic-pill <?= ($selectedCat === $cat['slug']) ? 'active' : '' ?>">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>

                <!-- Medium Pills -->
                <?php 
                $quickMediums = ['Oil', 'Acrylic', 'Watercolor', 'Mixed Media', 'Charcoal & Ink'];
                foreach ($quickMediums as $qm):
                ?>
                    <a href="<?= SITE_URL ?>/shop?medium=<?= urlencode($qm) ?><?= !empty($selectedCat) ? '&category=' . urlencode($selectedCat) : '' ?>" class="pinterest-topic-pill <?= ($selectedMedium === $qm) ? 'active' : '' ?>">
                        #<?= htmlspecialchars($qm) ?>
                    </a>
                <?php endforeach; ?>

                <!-- Quick Price Filter Pills -->
                <a href="<?= SITE_URL ?>/shop?min_price=0&max_price=10000" class="pinterest-topic-pill <?= ($selectedMinPrice === '0' && $selectedMaxPrice === '10000') ? 'active' : '' ?>">
                    Under ₹10k
                </a>
                <a href="<?= SITE_URL ?>/shop?min_price=10000&max_price=25000" class="pinterest-topic-pill <?= ($selectedMinPrice === '10000' && $selectedMaxPrice === '25000') ? 'active' : '' ?>">
                    ₹10k &ndash; ₹25k
                </a>
                <a href="<?= SITE_URL ?>/shop?min_price=25000" class="pinterest-topic-pill <?= ($selectedMinPrice === '25000' && empty($selectedMaxPrice)) ? 'active' : '' ?>">
                    ₹25k+ Masterworks
                </a>
            </div>
        </div>

    </div>
</div>

<!-- ====================================================================
     2. MAIN MASONRY FEED & SIDEBAR CONTAINER
     ==================================================================== -->
<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-8">
    <div class="flex items-start gap-8">
        
        <!-- Desktop Sticky Left Filter Sidebar -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="sticky top-28 bg-white border border-stone-200/80 rounded-3xl p-5 shadow-xs space-y-6">
                
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <h2 class="text-sm font-extrabold text-[#18181B] tracking-tight">Curate Feed</h2>
                    <?php if ($hasActiveFilters): ?>
                        <a href="<?= SITE_URL ?>/shop" class="text-xs text-red-600 font-bold hover:underline">Reset</a>
                    <?php endif; ?>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-2.5">Category</h3>
                    <div class="space-y-1 text-xs font-medium">
                        <a href="<?= SITE_URL ?>/shop" class="flex items-center justify-between py-1.5 px-3 rounded-xl transition-colors <?= empty($selectedCat) ? 'bg-[#18181B] text-white font-bold' : 'text-stone-600 hover:bg-stone-50' ?>">
                            <span>All Collections</span>
                            <span class="<?= empty($selectedCat) ? 'text-stone-300' : 'text-stone-400' ?>"><?= (int)$total ?></span>
                        </a>
                        <?php foreach ($categories as $cat): 
                            $count = (int)($cat['product_count'] ?? 0);
                            if ($count <= 0 && $selectedCat !== $cat['slug']) continue;
                            $isCatActive = ($selectedCat === $cat['slug']);
                        ?>
                            <a href="<?= SITE_URL ?>/shop?category=<?= htmlspecialchars($cat['slug']) ?><?= !empty($selectedMedium) ? '&medium=' . urlencode($selectedMedium) : '' ?>" class="flex items-center justify-between py-1.5 px-3 rounded-xl transition-colors <?= $isCatActive ? 'bg-[#18181B] text-white font-bold' : 'text-stone-600 hover:bg-stone-50' ?>">
                                <span class="truncate"><?= htmlspecialchars($cat['name']) ?></span>
                                <span class="<?= $isCatActive ? 'text-stone-300' : 'text-stone-400' ?>"><?= $count ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Painting Medium -->
                <div class="pt-4 border-t border-stone-100">
                    <h3 class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-2.5">Medium</h3>
                    <div class="space-y-1 text-xs font-medium">
                        <?php
                        $mediumList = ['Oil', 'Acrylic', 'Watercolor', 'Mixed Media', 'Charcoal & Ink'];
                        foreach ($mediumList as $med):
                            $isMedActive = ($selectedMedium === $med);
                        ?>
                            <a href="<?= SITE_URL ?>/shop?medium=<?= urlencode($med) ?><?= !empty($selectedCat) ? '&category=' . urlencode($selectedCat) : '' ?>" class="flex items-center justify-between py-1.5 px-3 rounded-xl transition-colors <?= $isMedActive ? 'bg-[#18181B] text-white font-bold' : 'text-stone-600 hover:bg-stone-50' ?>">
                                <span><?= $med ?></span>
                                <?php if ($isMedActive): ?>
                                    <span class="text-xs font-bold">&check;</span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Price Range -->
                <div class="pt-4 border-t border-stone-100">
                    <h3 class="text-xs font-bold text-stone-400 uppercase tracking-wider mb-2.5">Price Range</h3>
                    <div class="space-y-1 text-xs font-medium text-stone-600">
                        <a href="<?= SITE_URL ?>/shop?min_price=0&max_price=10000<?= !empty($selectedCat) ? '&category=' . urlencode($selectedCat) : '' ?>" class="block py-1.5 px-3 rounded-xl hover:bg-stone-50 <?= ($selectedMinPrice === '0' && $selectedMaxPrice === '10000') ? 'bg-stone-100 text-[#18181B] font-bold' : '' ?>">Under ₹10,000</a>
                        <a href="<?= SITE_URL ?>/shop?min_price=10000&max_price=25000<?= !empty($selectedCat) ? '&category=' . urlencode($selectedCat) : '' ?>" class="block py-1.5 px-3 rounded-xl hover:bg-stone-50 <?= ($selectedMinPrice === '10000' && $selectedMaxPrice === '25000') ? 'bg-stone-100 text-[#18181B] font-bold' : '' ?>">₹10,000 &ndash; ₹25,000</a>
                        <a href="<?= SITE_URL ?>/shop?min_price=25000&max_price=50000<?= !empty($selectedCat) ? '&category=' . urlencode($selectedCat) : '' ?>" class="block py-1.5 px-3 rounded-xl hover:bg-stone-50 <?= ($selectedMinPrice === '25000' && $selectedMaxPrice === '50000') ? 'bg-stone-100 text-[#18181B] font-bold' : '' ?>">₹25,000 &ndash; ₹50,000</a>
                        <a href="<?= SITE_URL ?>/shop?min_price=50000<?= !empty($selectedCat) ? '&category=' . urlencode($selectedCat) : '' ?>" class="block py-1.5 px-3 rounded-xl hover:bg-stone-50 <?= ($selectedMinPrice === '50000') ? 'bg-stone-100 text-[#18181B] font-bold' : '' ?>">₹50,000 & Above</a>
                    </div>
                </div>

                <!-- Provenance & Guarantee Callout -->
                <div class="pt-4 border-t border-stone-100">
                    <div class="bg-stone-50 rounded-2xl p-3.5 border border-stone-200/60 text-center">
                        <div class="w-8 h-8 rounded-full bg-white shadow-2xs flex items-center justify-center mx-auto mb-2 text-stone-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <p class="text-[11px] font-extrabold text-[#18181B]">Museum Authenticity</p>
                        <p class="text-[10px] text-stone-500 mt-1 leading-normal">Every painting ships with signed atelier certificate &amp; insured crating.</p>
                    </div>
                </div>

            </div>
        </aside>

        <!-- Main Fluid Masonry Column -->
        <main class="flex-1 min-w-0">
            
            <?php if (empty($products)): ?>
                <!-- Empty State -->
                <div class="bg-white rounded-3xl border border-stone-200/80 p-12 text-center my-6 shadow-xs max-w-lg mx-auto">
                    <div class="w-16 h-16 rounded-full bg-stone-100 flex items-center justify-center mx-auto mb-4 text-stone-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-stone-900">No artworks match this curation</h3>
                    <p class="text-xs text-stone-500 mt-1">Try resetting your filters or exploring another medium.</p>
                    <a href="<?= SITE_URL ?>/shop" class="inline-block mt-4 px-6 py-2.5 bg-[#18181B] hover:bg-black text-white rounded-full text-xs font-bold shadow-xs transition-colors">
                        View All Original Works
                    </a>
                </div>
            <?php else: ?>

                <!-- PINTEREST MULTI-COLUMN MASONRY GRID (2 cols on mobile, 3 on tablet/desktop) -->
                <div class="pinterest-masonry-grid">
                    <?php 
                    $wishlistIds = $_SESSION['wishlist'] ?? [];
                    foreach ($products as $index => $p): 
                        $rawImg = !empty($p['primary_image']) ? $p['primary_image'] : (!empty($p['image']) ? $p['image'] : (!empty($p['image_path']) ? $p['image_path'] : ''));
                        $cardImg = Product::imageUrl($rawImg);
                        $pid = (int)($p['id'] ?? 0);
                        $isWish = in_array($pid, $wishlistIds, true);
                        $price = (float)($p['discount_price'] ?? $p['price'] ?? 0);
                        $origPrice = (float)($p['price'] ?? 0);
                        $hasDiscount = !empty($p['discount_price']) && ($p['discount_price'] < $origPrice);
                        $artistName = !empty($p['artist_name']) ? $p['artist_name'] : 'Master Atelier Artist';
                        $dimensions = !empty($p['dimensions']) ? $p['dimensions'] : 'Original Format';
                        $medium = !empty($p['medium']) ? $p['medium'] : 'Original Medium';
                        $slug = !empty($p['slug']) ? $p['slug'] : ('art-' . $pid);
                    ?>

                        <!-- Pinterest Pin Card -->
                        <div class="pinterest-pin group" data-pin-id="<?= $pid ?>">
                            
                            <!-- Media Box with Natural Aspect Ratio & Hover Controls -->
                            <div class="pinterest-pin-media">
                                <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($slug) ?>" class="block w-full">
                                    <img src="<?= htmlspecialchars($cardImg) ?>" 
                                         alt="<?= htmlspecialchars($p['title']) ?>" 
                                         loading="lazy" 
                                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=900&q=80';">
                                </a>

                                <!-- Pinterest Hover / Tap Overlay -->
                                <div class="pinterest-pin-overlay">
                                    
                                    <!-- Top Row: Medium Tag + Red Save Pin Button -->
                                    <div class="flex items-center justify-between w-full">
                                        <span class="pinterest-tag-badge">
                                            <?= htmlspecialchars($medium) ?>
                                        </span>
                                        <button type="button" 
                                                onclick="togglePinSave(<?= $pid ?>, this)" 
                                                class="pinterest-save-btn <?= $isWish ? 'is-saved' : '' ?>"
                                                title="<?= $isWish ? 'Saved to Wishlist' : 'Save to Wishlist' ?>">
                                            <svg class="w-3.5 h-3.5" fill="<?= $isWish ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            <span class="save-label"><?= $isWish ? 'Saved' : 'Save' ?></span>
                                        </button>
                                    </div>

                                    <!-- Bottom Row: Quick Inspect Lightbox & Add to Bag -->
                                    <div class="flex items-center justify-between w-full gap-1.5 pt-4">
                                        <button type="button" 
                                                onclick='openArtInspectModal(<?= json_encode([
                                                    'id' => $pid,
                                                    'title' => $p['title'],
                                                    'slug' => $slug,
                                                    'image' => $cardImg,
                                                    'artist' => $artistName,
                                                    'medium' => $medium,
                                                    'dimensions' => $dimensions,
                                                    'price' => $price,
                                                    'formatted_price' => Validator::formatPrice($price),
                                                    'is_wish' => $isWish
                                                ]) ?>)' 
                                                class="pinterest-inspect-btn"
                                                title="Quick Inspect Artwork">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </button>

                                        <button type="button" 
                                                onclick="quickAddToCart(<?= $pid ?>)" 
                                                class="pinterest-quick-buy">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                            <span>+ Bag</span>
                                        </button>
                                    </div>

                                </div>
                            </div>

                            <!-- Pin Metadata Information Under Image -->
                            <div class="p-3 sm:p-3.5">
                                <div class="flex items-center justify-between text-[11px] text-stone-500 mb-1">
                                    <span class="truncate max-w-[130px] font-semibold text-stone-600 flex items-center gap-1">
                                        <?= htmlspecialchars($artistName) ?>
                                        <svg class="w-3 h-3 text-emerald-600 inline flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    </span>
                                    <span class="text-[10px] text-stone-400 font-mono"><?= htmlspecialchars($dimensions) ?></span>
                                </div>

                                <h3 class="font-bold text-xs sm:text-sm text-[#18181B] leading-snug line-clamp-1 group-hover:text-black transition-colors">
                                    <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($slug) ?>">
                                        <?= htmlspecialchars($p['title']) ?>
                                    </a>
                                </h3>

                                <div class="mt-2 flex items-center justify-between gap-1">
                                    <div>
                                        <span class="font-extrabold text-xs sm:text-sm text-[#18181B]">
                                            <?= Validator::formatPrice($price) ?>
                                        </span>
                                        <?php if ($hasDiscount): ?>
                                            <span class="text-[10px] text-stone-400 line-through ml-1">
                                                <?= Validator::formatPrice($origPrice) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <button type="button" 
                                            onclick="quickBuyNow(<?= $pid ?>)" 
                                            class="px-2.5 py-1 bg-stone-100 hover:bg-[#18181B] hover:text-white rounded-full text-[10px] font-bold text-stone-800 transition-colors">
                                        Buy Now
                                    </button>
                                </div>
                            </div>

                        </div>

                        <!-- Interleaved Atelier Inspiration Quote Pin (Slot 5) -->
                        <?php if ($index === 4): ?>
                            <div class="pinterest-pin p-5 sm:p-6 bg-[#18181B] text-white flex flex-col justify-between rounded-3xl shadow-sm">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400 block mb-2">Curator Note</span>
                                    <p class="font-serif italic text-sm sm:text-base leading-relaxed text-stone-200">
                                        &ldquo;Art enables us to find ourselves and lose ourselves at the same time.&rdquo;
                                    </p>
                                </div>
                                <div class="mt-6 pt-4 border-t border-stone-800 flex items-center justify-between">
                                    <span class="text-[11px] text-stone-400 font-medium">Thomas Merton</span>
                                    <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded-full text-stone-300">JayArt Collection</span>
                                </div>
                            </div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>

                <!-- Pagination (Clean Pill Buttons) -->
                <?php if ($totalPages > 1): ?>
                    <div class="mt-12 mb-6 flex justify-center items-center gap-2">
                        <?php 
                        $urlParams = [];
                        if (!empty($selectedCat)) $urlParams['category'] = $selectedCat;
                        if (!empty($selectedMedium)) $urlParams['medium'] = $selectedMedium;
                        if ($selectedMinPrice !== '') $urlParams['min_price'] = $selectedMinPrice;
                        if (!empty($selectedMaxPrice)) $urlParams['max_price'] = $selectedMaxPrice;
                        if (!empty($selectedSort)) $urlParams['sort'] = $selectedSort;
                        if (!empty($searchQuery)) $urlParams['search'] = $searchQuery;

                        for ($i = 1; $i <= $totalPages; $i++): 
                            $urlParams['page'] = $i;
                            $pageUrl = SITE_URL . '/shop?' . http_build_query($urlParams);
                        ?>
                            <a href="<?= $pageUrl ?>" class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold transition-all <?= ($currentPage === $i) ? 'bg-[#18181B] text-white shadow-xs' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>

        </main>
    </div>
</div>

<!-- ====================================================================
     3. MOBILE FILTER BOTTOM-SHEET DRAWER
     ==================================================================== -->
<div id="mobileFilterDrawer" class="fixed inset-0 z-50 pointer-events-none transition-opacity duration-300">
    <div id="mobileFilterOverlay" class="absolute inset-0 bg-black/50 opacity-0 transition-opacity duration-300"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-[32px] p-6 shadow-2xl max-h-[85vh] overflow-y-auto transform translate-y-full transition-transform duration-300">
        
        <div class="flex items-center justify-between pb-4 border-b border-stone-100">
            <div>
                <h3 class="text-base font-extrabold text-[#18181B]">Filter Artworks</h3>
                <p class="text-xs text-stone-500">Fine-tune your personal visual feed</p>
            </div>
            <button id="closeMobileFilters" class="w-8 h-8 rounded-full bg-stone-100 flex items-center justify-center text-stone-500 hover:text-stone-900 cursor-pointer">
                ✕
            </button>
        </div>

        <div class="py-5 space-y-6">
            <!-- Categories -->
            <div>
                <p class="text-xs uppercase font-extrabold text-stone-400 tracking-wider mb-2.5">Category</p>
                <div class="flex flex-wrap gap-2">
                    <a href="<?= SITE_URL ?>/shop" class="filter-pill <?= empty($selectedCat) ? 'bg-[#18181B] text-white border-[#18181B]' : 'bg-stone-50' ?>">
                        All Categories
                    </a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= SITE_URL ?>/shop?category=<?= htmlspecialchars($cat['slug']) ?><?= !empty($selectedMedium) ? '&medium=' . urlencode($selectedMedium) : '' ?>" class="filter-pill <?= ($selectedCat === $cat['slug']) ? 'bg-[#18181B] text-white border-[#18181B]' : 'bg-stone-50' ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Medium -->
            <div>
                <p class="text-xs uppercase font-extrabold text-stone-400 tracking-wider mb-2.5">Painting Medium</p>
                <div class="flex flex-wrap gap-2">
                    <?php 
                    $drawerMediums = ['Oil', 'Acrylic', 'Watercolor', 'Mixed Media', 'Charcoal & Ink'];
                    foreach ($drawerMediums as $med): 
                    ?>
                        <a href="<?= SITE_URL ?>/shop?medium=<?= urlencode($med) ?><?= !empty($selectedCat) ? '&category=' . urlencode($selectedCat) : '' ?>" class="filter-pill <?= ($selectedMedium === $med) ? 'bg-[#18181B] text-white border-[#18181B]' : 'bg-stone-50' ?>">
                            <?= $med ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Price -->
            <div>
                <p class="text-xs uppercase font-extrabold text-stone-400 tracking-wider mb-2.5">Price Range</p>
                <div class="grid grid-cols-2 gap-2">
                    <a href="<?= SITE_URL ?>/shop?min_price=0&max_price=10000" class="filter-pill text-center <?= ($selectedMinPrice === '0' && $selectedMaxPrice === '10000') ? 'bg-[#18181B] text-white' : 'bg-stone-50' ?>">Under ₹10,000</a>
                    <a href="<?= SITE_URL ?>/shop?min_price=10000&max_price=25000" class="filter-pill text-center <?= ($selectedMinPrice === '10000' && $selectedMaxPrice === '25000') ? 'bg-[#18181B] text-white' : 'bg-stone-50' ?>">₹10k &ndash; ₹25k</a>
                    <a href="<?= SITE_URL ?>/shop?min_price=25000&max_price=50000" class="filter-pill text-center <?= ($selectedMinPrice === '25000' && $selectedMaxPrice === '50000') ? 'bg-[#18181B] text-white' : 'bg-stone-50' ?>">₹25k &ndash; ₹50k</a>
                    <a href="<?= SITE_URL ?>/shop?min_price=50000" class="filter-pill text-center <?= ($selectedMinPrice === '50000') ? 'bg-[#18181B] text-white' : 'bg-stone-50' ?>">₹50,000+</a>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-stone-100 flex gap-2">
            <a href="<?= SITE_URL ?>/shop" class="flex-1 py-3 text-center bg-stone-100 hover:bg-stone-200 text-[#18181B] rounded-full font-bold text-xs transition-colors">
                Reset All
            </a>
            <button type="button" onclick="document.getElementById('closeMobileFilters').click()" class="flex-1 py-3 text-center bg-[#18181B] text-white rounded-full font-bold text-xs shadow-xs transition-colors">
                Apply Filters
            </button>
        </div>

    </div>
</div>

<!-- ====================================================================
     4. QUICK ART INSPECT LIGHTBOX MODAL
     ==================================================================== -->
<div id="artInspectModal">
    <div class="art-modal-container">
        
        <div class="p-4 sm:p-6 border-b border-stone-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Master Provenance Inspection</span>
            </div>
            <button type="button" onclick="closeArtInspectModal()" class="w-8 h-8 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-700 flex items-center justify-center font-bold text-sm transition-colors cursor-pointer">
                ✕
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 p-4 sm:p-6 lg:p-8">
            
            <!-- Left: Artwork Zoom Showcase with Frame Visualizer -->
            <div class="md:col-span-7 flex flex-col items-center justify-center bg-stone-50 rounded-2xl p-4 sm:p-6 border border-stone-100">
                <div id="modalArtFrameWrapper" class="relative max-h-[460px] overflow-hidden rounded-xl shadow-lg transition-all duration-300" style="padding: 0; background: transparent;">
                    <img id="modalArtImg" src="" alt="Inspect Art" class="max-h-[420px] max-w-full object-contain mx-auto block rounded-sm">
                </div>

                <div class="mt-4 flex items-center gap-2 text-[11px] text-stone-400 font-mono">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <span>High-Resolution Atelier Canvas View</span>
                </div>
            </div>

            <!-- Right: Details, Framing Options & Instant Bag Addition -->
            <div class="md:col-span-5 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between text-xs text-stone-500 mb-1">
                        <span id="modalArtistName" class="font-bold text-stone-700"></span>
                        <span id="modalArtMedium" class="text-[11px] bg-stone-100 px-2 py-0.5 rounded-full font-semibold"></span>
                    </div>

                    <h2 id="modalArtTitle" class="text-xl sm:text-2xl font-heading font-extrabold text-[#18181B] leading-tight mb-2"></h2>

                    <div class="flex items-baseline gap-2 mb-4">
                        <span id="modalArtPrice" class="text-2xl font-extrabold text-[#18181B]"></span>
                        <span class="text-xs text-stone-400">All Taxes &amp; Crating Included</span>
                    </div>

                    <!-- Interactive Framing Selector -->
                    <div class="space-y-3 pt-3 border-t border-stone-100">
                        <label class="text-xs font-bold text-stone-700 block">Choose Archival Framing</label>
                        <div class="grid grid-cols-1 gap-2" id="modalFrameOptions">
                            <label class="flex items-center justify-between p-2.5 rounded-xl border border-stone-900 bg-stone-50 cursor-pointer text-xs font-medium">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="modal_frame" value="Rolled Canvas" checked onchange="updateModalFrameStyle('rolled', 0)" class="text-stone-900 focus:ring-stone-900">
                                    <span>Rolled Canvas in Tube</span>
                                </div>
                                <span class="text-stone-500 font-bold">Included</span>
                            </label>
                            <label class="flex items-center justify-between p-2.5 rounded-xl border border-stone-200 hover:border-stone-400 cursor-pointer text-xs font-medium">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="modal_frame" value="Stretched Gallery Wrap" onchange="updateModalFrameStyle('stretched', 1800)" class="text-stone-900 focus:ring-stone-900">
                                    <span>Stretched Wood Bar</span>
                                </div>
                                <span class="text-stone-700 font-bold">+₹1,800</span>
                            </label>
                            <label class="flex items-center justify-between p-2.5 rounded-xl border border-stone-200 hover:border-stone-400 cursor-pointer text-xs font-medium">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="modal_frame" value="Teak Floating Frame" onchange="updateModalFrameStyle('teak', 3600)" class="text-stone-900 focus:ring-stone-900">
                                    <span>Teak Floating Frame</span>
                                </div>
                                <span class="text-stone-700 font-bold">+₹3,600</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-t border-stone-100 space-y-1.5 text-xs text-stone-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>Physical Authenticity Certificate Included</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>7-Day Return &amp; Authenticity Guarantee</span>
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-4 border-t border-stone-100 flex flex-col gap-2">
                    <button type="button" id="modalAddBagBtn" class="w-full py-3.5 bg-[#18181B] hover:bg-black text-white rounded-full font-bold text-xs transition-colors shadow-sm cursor-pointer">
                        Add to Acquisition Bag
                    </button>
                    <a id="modalViewFullBtn" href="#" class="w-full py-3 bg-stone-100 hover:bg-stone-200 text-[#18181B] rounded-full font-bold text-xs text-center transition-colors">
                        View Full Art Page &amp; Provenance &rarr;
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- ====================================================================
     5. PINTEREST DISCOVERY & LIGHTBOX JAVASCRIPT
     ==================================================================== -->
<script>
// Filter Drawer Logic
const filterBtn = document.getElementById('openMobileFilters');
const filterDrawer = document.getElementById('mobileFilterDrawer');
const filterOverlay = document.getElementById('mobileFilterOverlay');
const closeFilterBtn = document.getElementById('closeMobileFilters');

if (filterBtn && filterDrawer) {
    filterBtn.addEventListener('click', () => {
        filterDrawer.classList.remove('pointer-events-none');
        filterOverlay.classList.remove('opacity-0');
        filterDrawer.querySelector('.absolute.bottom-0').classList.remove('translate-y-full');
    });

    const closeDrawer = () => {
        filterDrawer.classList.add('pointer-events-none');
        filterOverlay.classList.add('opacity-0');
        filterDrawer.querySelector('.absolute.bottom-0').classList.add('translate-y-full');
    };

    if (closeFilterBtn) closeFilterBtn.addEventListener('click', closeDrawer);
    if (filterOverlay) filterOverlay.addEventListener('click', closeDrawer);
}

// Pinterest Pin Save Button (Wishlist)
function togglePinSave(productId, btnElement) {
    if (typeof toggleWishlist === 'function') {
        toggleWishlist(productId, btnElement);
    }
    
    // Optimistic UI toggle
    const isSaved = btnElement.classList.contains('is-saved');
    const label = btnElement.querySelector('.save-label');
    const icon = btnElement.querySelector('svg');

    if (isSaved) {
        btnElement.classList.remove('is-saved');
        if (label) label.textContent = 'Save';
        if (icon) icon.setAttribute('fill', 'none');
    } else {
        btnElement.classList.add('is-saved');
        if (label) label.textContent = 'Saved';
        if (icon) icon.setAttribute('fill', 'currentColor');
    }
}

// Quick Art Inspect Modal Controller
let currentModalArt = null;
let currentFrameSurcharge = 0;

function openArtInspectModal(artData) {
    currentModalArt = artData;
    currentFrameSurcharge = 0;

    document.getElementById('modalArtImg').src = artData.image;
    document.getElementById('modalArtistName').textContent = artData.artist;
    document.getElementById('modalArtMedium').textContent = artData.medium;
    document.getElementById('modalArtTitle').textContent = artData.title;
    document.getElementById('modalArtPrice').textContent = artData.formatted_price;
    document.getElementById('modalViewFullBtn').href = '<?= SITE_URL ?>/product/' + encodeURIComponent(artData.slug);

    // Reset frame radio
    const firstRadio = document.querySelector('input[name="modal_frame"][value="Rolled Canvas"]');
    if (firstRadio) firstRadio.checked = true;
    updateModalFrameStyle('rolled', 0);

    const addBtn = document.getElementById('modalAddBagBtn');
    addBtn.onclick = function() {
        const selectedFrame = document.querySelector('input[name="modal_frame"]:checked')?.value || 'Rolled Canvas';
        quickAddToCart(artData.id, 1, selectedFrame);
        closeArtInspectModal();
    };

    const modal = document.getElementById('artInspectModal');
    modal.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
}

function closeArtInspectModal() {
    const modal = document.getElementById('artInspectModal');
    modal.classList.remove('modal-open');
    document.body.style.overflow = '';
}

function updateModalFrameStyle(frameType, surcharge) {
    currentFrameSurcharge = surcharge;
    const wrapper = document.getElementById('modalArtFrameWrapper');
    if (!wrapper) return;

    if (frameType === 'teak') {
        wrapper.style.padding = '14px';
        wrapper.style.backgroundColor = '#5c3a21';
        wrapper.style.boxShadow = '0 16px 36px rgba(45, 25, 12, 0.45)';
    } else if (frameType === 'stretched') {
        wrapper.style.padding = '6px';
        wrapper.style.backgroundColor = '#18181B';
        wrapper.style.boxShadow = '0 12px 28px rgba(0, 0, 0, 0.3)';
    } else {
        wrapper.style.padding = '0';
        wrapper.style.backgroundColor = 'transparent';
        wrapper.style.boxShadow = '0 8px 24px rgba(0, 0, 0, 0.12)';
    }

    if (currentModalArt) {
        const basePrice = parseFloat(currentModalArt.price) || 0;
        const totalNow = basePrice + surcharge;
        const formatted = '₹ ' + totalNow.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('modalArtPrice').textContent = formatted;
    }
}

// Close lightbox on Escape key or outside click
window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeArtInspectModal();
});

const inspectModal = document.getElementById('artInspectModal');
if (inspectModal) {
    inspectModal.addEventListener('click', function(e) {
        if (e.target === this) closeArtInspectModal();
    });
}
</script>
