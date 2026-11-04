<?php
/**
 * JayArt Global All Filters Modal Drawer
 * Responsive modal for instant painting filtering across all devices
 * Nordic Atelier Executive Theme - Zero Emojis, Pure SVG, CLS = 0
 */
?>
<div id="allFiltersModal" 
     class="fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4 sm:p-6 transition-opacity duration-200" 
     onclick="if(event.target === this) closeAllFiltersModal()"
     role="dialog"
     aria-modal="true"
     aria-labelledby="allFiltersModalTitle">
    
    <div id="allFiltersModalCard" 
         class="bg-white rounded-3xl max-w-lg w-full shadow-2xl border border-stone-200/80 overflow-hidden flex flex-col max-h-[85vh] transform scale-95 opacity-0 transition-all duration-200 my-auto" 
         onclick="event.stopPropagation()">
        
        <!-- Fixed Modal Header -->
        <div class="p-5 sm:p-6 border-b border-stone-100 flex items-center justify-between bg-[#FBFBFA]/90 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-[#18181B] text-white flex items-center justify-center font-bold shadow-xs flex-shrink-0">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                </div>
                <div>
                    <h3 id="allFiltersModalTitle" class="font-heading font-bold text-base sm:text-lg text-[#18181B]">All Art Filters</h3>
                    <p class="text-xs text-stone-500 mt-0.5">Refine your fine art gallery selection</p>
                </div>
            </div>
            <button type="button" 
                    onclick="closeAllFiltersModal()" 
                    class="w-9 h-9 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-600 hover:text-black flex items-center justify-center transition-colors cursor-pointer" 
                    aria-label="Close Filter Modal">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Scrollable Modal Body -->
        <div class="p-5 sm:p-6 space-y-6 overflow-y-auto flex-1 overscroll-contain">
            
            <!-- 0. Sort Order -->
            <div>
                <h4 class="text-xs font-bold text-[#18181B] uppercase tracking-wider mb-2.5 flex items-center justify-between">
                    <span>Sort Artworks</span>
                    <span class="text-[10px] text-stone-400 font-normal">Order By</span>
                </h4>
                <div class="flex flex-wrap gap-2 text-xs">
                    <button type="button" onclick="handleModalFilter('sort', 'popular', 'Popular', '<?= SITE_URL ?>/shop')" class="px-3.5 py-2 rounded-full border border-stone-200 bg-stone-50 hover:bg-stone-100 hover:border-black text-stone-800 font-semibold transition-all cursor-pointer">Popular / Featured</button>
                    <button type="button" onclick="handleModalFilter('sort', 'price_low', 'Low to High', '<?= SITE_URL ?>/shop?sort=price_low')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">Price: Low to High</button>
                    <button type="button" onclick="handleModalFilter('sort', 'price_high', 'High to Low', '<?= SITE_URL ?>/shop?sort=price_high')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">Price: High to Low</button>
                    <button type="button" onclick="handleModalFilter('sort', 'rating', 'Top Rated', '<?= SITE_URL ?>/shop?sort=rating')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">Highest Rated</button>
                    <button type="button" onclick="handleModalFilter('sort', 'name', 'A to Z', '<?= SITE_URL ?>/shop?sort=name')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">Alphabetical (A&ndash;Z)</button>
                </div>
            </div>

            <!-- 1. Medium -->
            <div>
                <h4 class="text-xs font-bold text-[#18181B] uppercase tracking-wider mb-2.5 flex items-center justify-between">
                    <span>Painting Medium</span>
                    <span class="text-[10px] text-stone-400 font-normal">Original Media</span>
                </h4>
                <div class="flex flex-wrap gap-2 text-xs">
                    <button type="button" onclick="handleModalFilter('medium', 'all', 'Medium Type', '<?= SITE_URL ?>/shop')" class="px-3.5 py-2 rounded-full border border-stone-200 bg-stone-50 hover:bg-stone-100 hover:border-black text-stone-800 font-semibold transition-all cursor-pointer">All Mediums</button>
                    <button type="button" onclick="handleModalFilter('medium', 'oil', 'Oil on Linen', '<?= SITE_URL ?>/shop?category=oil-paintings')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">Oil on Belgian Linen</button>
                    <button type="button" onclick="handleModalFilter('medium', 'acrylic', 'Acrylic Impasto', '<?= SITE_URL ?>/shop?category=acrylic-art')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">Heavy Acrylic</button>
                    <button type="button" onclick="handleModalFilter('medium', 'watercolor', 'Watercolor', '<?= SITE_URL ?>/shop?category=watercolor-paintings')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">Watercolor on Arches</button>
                    <button type="button" onclick="handleModalFilter('medium', 'mixed', 'Modern Abstract', '<?= SITE_URL ?>/shop?category=modern-abstract')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">Modern Abstract &amp; 24K Gold</button>
                    <button type="button" onclick="handleModalFilter('medium', 'traditional', 'Traditional Heritage', '<?= SITE_URL ?>/shop?category=traditional-heritage')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">Traditional Mineral Heritage</button>
                </div>
            </div>

            <!-- 2. Price Range -->
            <div class="pt-5 border-t border-stone-100">
                <h4 class="text-xs font-bold text-[#18181B] uppercase tracking-wider mb-2.5 flex items-center justify-between">
                    <span>Price Range</span>
                    <span class="text-[10px] text-stone-400 font-normal">INR Currency</span>
                </h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                    <button type="button" onclick="handleModalFilter('price', 'under_20000', 'Under ₹20,000', '<?= SITE_URL ?>/shop?max_price=20000')" class="px-4 py-2.5 rounded-2xl border border-stone-200 hover:border-black text-stone-800 font-semibold text-left transition-all hover:bg-stone-50 cursor-pointer flex items-center justify-between">
                        <span>Under <?= CURRENCY_SYMBOL ?> 20,000</span>
                        <span class="text-stone-400 text-[10px]">&rarr;</span>
                    </button>
                    <button type="button" onclick="handleModalFilter('price', '20000_30000', '₹20k - ₹30k', '<?= SITE_URL ?>/shop?min_price=20000&max_price=30000')" class="px-4 py-2.5 rounded-2xl border border-stone-200 hover:border-black text-stone-800 font-semibold text-left transition-all hover:bg-stone-50 cursor-pointer flex items-center justify-between">
                        <span><?= CURRENCY_SYMBOL ?> 20,000 &ndash; 30,000</span>
                        <span class="text-stone-400 text-[10px]">&rarr;</span>
                    </button>
                    <button type="button" onclick="handleModalFilter('price', '30000_40000', '₹30k - ₹40k', '<?= SITE_URL ?>/shop?min_price=30000&max_price=40000')" class="px-4 py-2.5 rounded-2xl border border-stone-200 hover:border-black text-stone-800 font-semibold text-left transition-all hover:bg-stone-50 cursor-pointer flex items-center justify-between">
                        <span><?= CURRENCY_SYMBOL ?> 30,000 &ndash; 40,000</span>
                        <span class="text-stone-400 text-[10px]">&rarr;</span>
                    </button>
                    <button type="button" onclick="handleModalFilter('price', 'above_40000', 'Above ₹40,000', '<?= SITE_URL ?>/shop?min_price=40000')" class="px-4 py-2.5 rounded-2xl border border-stone-200 hover:border-black text-stone-800 font-semibold text-left transition-all hover:bg-stone-50 cursor-pointer flex items-center justify-between">
                        <span>Above <?= CURRENCY_SYMBOL ?> 40,000</span>
                        <span class="text-stone-400 text-[10px]">&rarr;</span>
                    </button>
                </div>
            </div>

            <!-- 3. Canvas & Material -->
            <div class="pt-5 border-t border-stone-100">
                <h4 class="text-xs font-bold text-[#18181B] uppercase tracking-wider mb-2.5 flex items-center justify-between">
                    <span>Canvas &amp; Substrate</span>
                    <span class="text-[10px] text-stone-400 font-normal">Archival Support</span>
                </h4>
                <div class="flex flex-wrap gap-2 text-xs">
                    <button type="button" onclick="handleModalFilter('material', 'all', 'Material', '<?= SITE_URL ?>/shop')" class="px-3.5 py-2 rounded-full border border-stone-200 bg-stone-50 hover:bg-stone-100 hover:border-black text-stone-800 font-semibold transition-all cursor-pointer">All Materials</button>
                    <button type="button" onclick="handleModalFilter('material', 'linen', 'Belgian Linen', '<?= SITE_URL ?>/shop?medium=Linen')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">Belgian Linen Canvas</button>
                    <button type="button" onclick="handleModalFilter('material', 'cotton', 'Cotton Canvas', '<?= SITE_URL ?>/shop?medium=Cotton')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">Cotton Duck Canvas</button>
                    <button type="button" onclick="handleModalFilter('material', 'paper', 'Arches Paper', '<?= SITE_URL ?>/shop?medium=Watercolor')" class="px-3.5 py-2 rounded-full border border-stone-200 hover:border-black text-stone-700 hover:text-black font-semibold transition-all cursor-pointer">French Arches Paper</button>
                </div>
            </div>

        </div>

        <!-- Fixed Modal Footer -->
        <div class="p-5 sm:p-6 border-t border-stone-100 bg-[#FBFBFA]/90 flex items-center justify-between flex-shrink-0">
            <button type="button" onclick="handleModalReset('<?= SITE_URL ?>/shop')" class="text-xs font-bold text-red-600 hover:text-red-700 hover:underline transition-colors cursor-pointer">
                Reset All Filters
            </button>
            <button type="button" onclick="closeAllFiltersModal()" class="px-7 py-3 bg-[#18181B] hover:bg-black text-white text-xs font-bold rounded-full shadow-sm transition-all active:scale-95 cursor-pointer">
                Show Artworks
            </button>
        </div>

    </div>
</div>
