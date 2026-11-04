<?php
/**
 * JayArt Modern Shop & Catalog View
 * Shopcart-Inspired High-Converting E-Commerce Design
 */

$products = is_array($products ?? null) ? $products : (is_array($paintings ?? null) ? $paintings : (is_array($items ?? null) ? $items : []));
$total = (int)($total ?? count($products));
$selectedCat = $filters['category'] ?? '';
$selectedMedium = $filters['medium'] ?? '';
$selectedSort = $filters['sort'] ?? '';
?>

<!-- Shop Header with Breadcrumb -->
<div class="bg-white border-b border-gray-100 py-5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex text-xs text-stone-400 mb-2 font-medium" aria-label="Breadcrumb">
            <a href="<?= SITE_URL ?>" class="hover:text-black">Home</a>
            <span class="mx-2">/</span>
            <span class="text-[#18181B] font-semibold">Original Paintings</span>
        </nav>
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-[#18181B] tracking-tight">Original Paintings</h1>
                <p class="text-xs text-stone-500 mt-1"><?= (int)$total ?> artworks found</p>
            </div>

            <!-- Sort & Mobile Filter Toggle -->
            <div class="flex items-center gap-3">
                <button type="button" id="openMobileFilters" class="lg:hidden flex items-center gap-2 px-4 py-2 bg-stone-100 rounded-full text-xs font-bold text-stone-800 hover:bg-stone-200 transition-colors">
                    <svg class="w-4 h-4 text-stone-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    <span>Filters</span>
                </button>

                <form id="sortForm" method="GET" class="flex items-center gap-2">
                    <?php if (!empty($selectedCat)): ?><input type="hidden" name="category" value="<?= htmlspecialchars($selectedCat) ?>"><?php endif; ?>
                    <?php if (!empty($selectedMedium)): ?><input type="hidden" name="medium" value="<?= htmlspecialchars($selectedMedium) ?>"><?php endif; ?>
                    <?php if (!empty($filters['search'])): ?><input type="hidden" name="q" value="<?= htmlspecialchars($filters['search']) ?>"><?php endif; ?>
                    
                    <select id="sortSelect" name="sort" onchange="this.form.submit()" class="px-4 py-2 bg-stone-100 rounded-full text-xs font-semibold text-stone-800 border-none focus:outline-none focus:ring-2 focus:ring-black/10 cursor-pointer">
                        <option value="" <?= ($selectedSort === '') ? 'selected' : '' ?>>Sort by: Popularity</option>
                        <option value="price_low" <?= ($selectedSort === 'price_low') ? 'selected' : '' ?>>Sort by: Price (Low to High)</option>
                        <option value="price_high" <?= ($selectedSort === 'price_high') ? 'selected' : '' ?>>Sort by: Price (High to Low)</option>
                        <option value="popular" <?= ($selectedSort === 'popular') ? 'selected' : '' ?>>Sort by: Most Viewed</option>
                        <option value="newest" <?= ($selectedSort === 'newest') ? 'selected' : '' ?>>Sort by: Newest Arrivals</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Catalog Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex gap-8">
        
        <!-- Desktop Sticky Left Sidebar Filters -->
        <aside class="hidden lg:block w-64 flex-shrink-0">
            <div class="sticky top-24 bg-white border border-stone-200/80 rounded-2xl p-5 shadow-xs space-y-6">
                <div class="flex items-center justify-between pb-3 border-b border-stone-100">
                    <h2 class="text-sm font-extrabold text-[#18181B]">Filters</h2>
                    <?php if (!empty($selectedCat) || !empty($selectedMedium) || !empty($selectedSort)): ?>
                        <a href="<?= SITE_URL ?>/shop" class="text-xs text-[#18181B] font-bold hover:underline">Reset All</a>
                    <?php endif; ?>
                </div>

                <!-- Categories -->
                <div>
                    <h3 class="text-xs font-bold text-[#18181B] uppercase tracking-wider mb-3">Categories</h3>
                    <div class="space-y-1.5 text-xs font-medium">
                        <a href="<?= SITE_URL ?>/shop" class="flex items-center justify-between py-1.5 px-2 rounded-lg <?= empty($selectedCat) ? 'bg-stone-100 text-[#18181B] font-bold' : 'text-stone-600 hover:bg-stone-50' ?>">
                            <span>All Categories</span>
                            <span class="text-stone-400"><?= (int)$total ?></span>
                        </a>
                        <?php foreach ($categories as $cat): 
                            $count = (int)($cat['product_count'] ?? 0);
                            if ($count <= 0 && $selectedCat !== $cat['slug']) {
                                continue; // Hide categories with 0 products
                            }
                        ?>
                            <a href="<?= SITE_URL ?>/shop?category=<?= htmlspecialchars($cat['slug']) ?><?= !empty($selectedMedium) ? '&medium=' . urlencode($selectedMedium) : '' ?>" class="flex items-center justify-between py-1.5 px-2 rounded-lg <?= ($selectedCat === $cat['slug']) ? 'bg-stone-100 text-[#18181B] font-bold' : 'text-stone-600 hover:bg-stone-50' ?>">
                                <span><?= htmlspecialchars($cat['name']) ?></span>
                                <span class="text-stone-400"><?= $count ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Medium Filter -->
                <div class="pt-4 border-t border-stone-100">
                    <h3 class="text-xs font-bold text-[#18181B] uppercase tracking-wider mb-3">Painting Medium</h3>
                    <div class="space-y-1.5 text-xs font-medium">
                        <?php
                        $mediums = ['Oil', 'Acrylic', 'Watercolor', 'Mixed Media', 'Charcoal & Ink'];
                        foreach ($mediums as $med):
                        ?>
                            <a href="<?= SITE_URL ?>/shop?medium=<?= urlencode($med) ?><?= !empty($selectedCat) ? '&category=' . urlencode($selectedCat) : '' ?>" class="flex items-center justify-between py-1.5 px-2 rounded-lg <?= ($selectedMedium === $med) ? 'bg-stone-100 text-[#18181B] font-bold' : 'text-stone-600 hover:bg-stone-50' ?>">
                                <span><?= $med ?></span>
                                <?php if ($selectedMedium === $med): ?>
                                    <span class="text-[#18181B] font-bold">&check;</span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Price Ranges -->
                <div class="pt-4 border-t border-stone-100">
                    <h3 class="text-xs font-bold text-[#18181B] uppercase tracking-wider mb-3">Price Range</h3>
                    <div class="space-y-1.5 text-xs font-medium text-stone-600">
                        <a href="<?= SITE_URL ?>/shop?min_price=0&max_price=10000" class="block py-1 hover:text-black">Under ₹10,000</a>
                        <a href="<?= SITE_URL ?>/shop?min_price=10000&max_price=25000" class="block py-1 hover:text-black">₹10,000 &ndash; ₹25,000</a>
                        <a href="<?= SITE_URL ?>/shop?min_price=25000&max_price=50000" class="block py-1 hover:text-black">₹25,000 &ndash; ₹50,000</a>
                        <a href="<?= SITE_URL ?>/shop?min_price=50000" class="block py-1 hover:text-black">₹50,000 & Above</a>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Product Grid Column -->
        <main class="flex-1">
            <?php if (empty($products)): ?>
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center my-6">
                    <div class="w-16 h-16 rounded-full bg-[#F5F6F6] flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-900">No paintings found</h3>
                    <p class="text-xs text-gray-400 mt-1">Try modifying your category or filter selections</p>
                    <a href="<?= SITE_URL ?>/shop" class="inline-block mt-4 px-6 py-2.5 bg-[#18181B] hover:bg-black text-white rounded-full text-xs font-bold shadow-sm transition-colors">View All Paintings</a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-4 sm:gap-6">
                    <?php foreach ($products as $index => $p): 
                        $cardImg = !empty($p['primary_image']) ? $p['primary_image'] : (!empty($p['image']) ? $p['image'] : (!empty($p['image_path']) ? $p['image_path'] : ASSETS_URL . '/images/banner.jpg'));
                    ?>
                        
                        <!-- Product Card -->
                        <div class="product-card group">
                            <!-- Image Box with Heart Button -->
                            <div class="product-img-wrap">
                                <button onclick="toggleWishlist(<?= $p['id'] ?>, this)" class="wishlist-heart-btn" title="Add to Wishlist">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </button>

                                <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($p['slug']) ?>" class="w-full h-full flex items-center justify-center">
                                    <img src="<?= htmlspecialchars($cardImg) ?>" alt="<?= htmlspecialchars($p['title']) ?>" class="max-h-full max-w-full object-contain transform group-hover:scale-105 transition-transform duration-300" loading="lazy">
                                </a>
                            </div>

                            <!-- Meta Details -->
                            <div class="pt-3 pb-1 flex-1 flex flex-col justify-between">
                                <div>
                                    <!-- Title & Price -->
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="font-bold text-sm text-[#18181B] group-hover:text-black transition-colors line-clamp-1 flex-1">
                                            <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($p['slug']) ?>">
                                                <?= htmlspecialchars($p['title']) ?>
                                            </a>
                                        </h3>
                                        <span class="font-extrabold text-sm sm:text-base text-[#18181B] whitespace-nowrap">
                                            <?= Validator::formatPrice((float)($p['discount_price'] ?? $p['price'])) ?>
                                        </span>
                                    </div>

                                    <!-- Subtitle -->
                                    <p class="text-xs text-stone-500 mt-1 line-clamp-1">
                                        <?= htmlspecialchars($p['medium']) ?> &bull; <?= htmlspecialchars($p['dimensions']) ?>
                                    </p>

                                    <!-- Star Rating -->
                                    <div class="mt-2 flex items-center gap-1.5">
                                        <div class="text-amber-400 text-xs">
                                            <span>★★★★★</span>
                                        </div>
                                        <span class="text-xs font-bold text-stone-600">(<?= 100 + ($p['id'] * 7) ?>)</span>
                                    </div>
                                </div>

                                <!-- Add to Cart & Buy Now Pills -->
                                <div class="mt-4 grid grid-cols-2 gap-2">
                                    <button type="button" onclick="quickAddToCart(<?= $p['id'] ?>)" class="w-full py-2 px-2 border border-stone-800 text-[#18181B] hover:bg-stone-100 rounded-full text-xs font-bold transition-all flex items-center justify-center text-center cursor-pointer">
                                        <span>Add to Cart</span>
                                    </button>
                                    <button type="button" onclick="quickBuyNow(<?= $p['id'] ?>)" class="w-full py-2 px-2 bg-[#18181B] hover:bg-black text-white rounded-full text-xs font-bold transition-all shadow-xs flex items-center justify-center text-center cursor-pointer">
                                        <span>Buy Now</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- In-Grid Poster Ad Slot (Slot 3) -->
                        <?php if ($index === 2): ?>
                            <div class="col-span-1">
                                <?= Ad::render('in_grid_poster') ?>
                            </div>
                        <?php endif; ?>

                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="mt-12 flex justify-center items-center gap-2">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="<?= SITE_URL ?>/shop?page=<?= $i ?><?= !empty($selectedCat) ? '&category=' . urlencode($selectedCat) : '' ?><?= !empty($selectedMedium) ? '&medium=' . urlencode($selectedMedium) : '' ?>" class="w-10 h-10 rounded-full flex items-center justify-center text-xs font-bold transition-all <?= ($currentPage === $i) ? 'bg-[#18181B] text-white shadow-sm' : 'bg-stone-100 text-stone-700 hover:bg-stone-200' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </main>
    </div>
</div>

<!-- Mobile Bottom-Sheet Filter Drawer -->
<div id="mobileFilterDrawer" class="fixed inset-0 z-50 pointer-events-none transition-opacity">
    <div id="mobileFilterOverlay" class="absolute inset-0 bg-black/40 opacity-0 transition-opacity"></div>
    <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl p-6 shadow-2xl max-h-[85vh] overflow-y-auto transform translate-y-full transition-transform duration-300">
        <div class="flex items-center justify-between pb-4 border-b border-stone-100">
            <h3 class="text-base font-extrabold text-[#18181B]">Filter Artworks</h3>
            <button id="closeMobileFilters" class="p-1 text-stone-400 hover:text-stone-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="py-4 space-y-6">
            <div>
                <p class="text-xs uppercase font-bold text-stone-400 tracking-wider mb-2">Categories</p>
                <div class="flex flex-wrap gap-2">
                    <a href="<?= SITE_URL ?>/shop" class="filter-pill <?= empty($selectedCat) ? 'bg-[#18181B] text-white border-[#18181B]' : '' ?>">All</a>
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= SITE_URL ?>/shop?category=<?= htmlspecialchars($cat['slug']) ?>" class="filter-pill <?= ($selectedCat === $cat['slug']) ? 'bg-[#18181B] text-white border-[#18181B]' : '' ?>">
                            <?= htmlspecialchars($cat['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div>
                <p class="text-xs uppercase font-bold text-stone-400 tracking-wider mb-2">Medium</p>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($mediums as $med): ?>
                        <a href="<?= SITE_URL ?>/shop?medium=<?= urlencode($med) ?>" class="filter-pill <?= ($selectedMedium === $med) ? 'bg-[#18181B] text-white border-[#18181B]' : '' ?>">
                            <?= $med ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-stone-100">
            <a href="<?= SITE_URL ?>/shop" class="block w-full py-3 text-center bg-[#18181B] text-white rounded-full font-bold text-xs shadow-sm">
                Apply Filters
            </a>
        </div>
    </div>
</div>

<script>
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
</script>
