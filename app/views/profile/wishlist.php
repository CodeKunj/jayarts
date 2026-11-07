<?php
/**
 * JayArt Collector Saved Artworks Wishlist View
 * Pinterest-Inspired Visual Pinboard of Saved Pieces
 */
$artworks = is_array($artworks ?? null) ? $artworks : [];
?>

<div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-10">
    
    <!-- Top Header & Discovery Backlink -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-5 border-b border-stone-200/80 mb-6 sm:mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2.5 h-2.5 rounded-full bg-[#E60023]"></span>
                <span class="text-[11px] font-bold uppercase tracking-wider text-stone-500">Curated Personal Board</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-heading font-extrabold text-[#18181B] tracking-tight">
                My Saved Artworks
            </h1>
            <p class="text-xs text-stone-500 mt-0.5">
                Paintings you've earmarked for your private art collection (<?= count($artworks) ?> saved)
            </p>
        </div>
        <a href="<?= SITE_URL ?>/shop" class="inline-flex items-center gap-2 px-4 py-2 bg-stone-100 hover:bg-stone-200 text-[#18181B] rounded-full text-xs font-bold transition-colors self-start sm:self-auto cursor-pointer">
            &larr; <span>Explore More Art</span>
        </a>
    </div>

    <?php if (empty($artworks)): ?>
        <!-- Clean Empty State -->
        <div class="bg-white/95 backdrop-blur-md border border-[#B67A31]/20 rounded-3xl p-10 sm:p-16 text-center my-8 max-w-md mx-auto shadow-xs">
            <div class="w-16 h-16 rounded-full bg-red-50 text-[#E60023] flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </div>
            <h3 class="text-lg font-heading font-extrabold text-[#18181B] mb-1">Your Board is Empty</h3>
            <p class="text-xs text-stone-500 mb-6 leading-relaxed">
                Save original paintings by tapping the red Save button on any artwork while exploring our visual gallery.
            </p>
            <a href="<?= SITE_URL ?>/shop" class="inline-flex items-center justify-center px-6 py-3 bg-[#18181B] hover:bg-black text-white rounded-full text-xs font-bold shadow-xs transition-colors">
                Explore Original Artworks
            </a>
        </div>
    <?php else: ?>
        <!-- Pinterest-Inspired Masonry Grid for Saved Pins -->
        <div class="pinterest-masonry-grid" id="wishlist-grid">
            <?php foreach ($artworks as $paint): 
                $pid = (int)($paint['id'] ?? 0);
                $img = Product::imageUrl($paint['primary_image'] ?? $paint['image'] ?? null);
                $price = (float)(!empty($paint['discount_price']) ? $paint['discount_price'] : $paint['price']);
                $origPrice = (float)($paint['price'] ?? 0);
                $hasDiscount = !empty($paint['discount_price']) && ($paint['discount_price'] < $origPrice);
                $artistName = !empty($paint['artist_name']) ? $paint['artist_name'] : 'Atelier Master';
                $slug = !empty($paint['slug']) ? $paint['slug'] : ('art-' . $pid);
            ?>
                <div class="pinterest-pin group transition-all duration-300" id="wishlist-item-<?= $pid ?>">
                    
                    <!-- Media Box with Natural Aspect Ratio & Floating Actions -->
                    <div class="pinterest-pin-media">
                        <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($slug) ?>" class="block w-full">
                            <img src="<?= htmlspecialchars($img) ?>" 
                                 alt="<?= htmlspecialchars($paint['title']) ?>" 
                                 loading="lazy" 
                                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=900&q=80';">
                        </a>

                        <!-- Floating Overlay with Red Saved Button & Quick Add -->
                        <div class="pinterest-pin-overlay">
                            <div class="flex items-center justify-between w-full">
                                <span class="pinterest-tag-badge">Saved Pin</span>
                                <!-- Click to remove from saved board -->
                                <button type="button" 
                                        onclick="removeFromWishlist(<?= $pid ?>, this)" 
                                        class="pinterest-save-btn is-saved" 
                                        title="Remove from Saved Artworks">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    <span>Saved</span>
                                </button>
                            </div>

                            <div class="flex items-center justify-end w-full pt-4">
                                <button type="button" 
                                        onclick="quickAddToCart(<?= $pid ?>)" 
                                        class="pinterest-quick-buy">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    <span>+ Bag</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Meta details -->
                    <div class="p-3 sm:p-3.5 flex flex-col justify-between flex-1">
                        <div>
                            <div class="text-[11px] font-semibold text-stone-500 mb-0.5 truncate">
                                <?= htmlspecialchars($artistName) ?>
                            </div>
                            <h4 class="font-bold text-xs sm:text-sm text-[#18181B] truncate mb-1.5">
                                <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($slug) ?>" class="hover:text-black">
                                    <?= htmlspecialchars($paint['title']) ?>
                                </a>
                            </h4>
                        </div>
                        
                        <div>
                            <div class="flex items-center justify-between gap-1 mb-2.5">
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
                            </div>

                            <div class="grid grid-cols-2 gap-1.5">
                                <button type="button" 
                                        onclick="quickAddToCart(<?= $pid ?>)" 
                                        class="w-full py-2 bg-stone-100 hover:bg-stone-200 text-[#18181B] rounded-full text-[10px] sm:text-xs font-bold transition-colors">
                                    Add to Bag
                                </button>
                                <button type="button" 
                                        onclick="quickBuyNow(<?= $pid ?>)" 
                                        class="w-full py-2 bg-[#18181B] hover:bg-black text-white rounded-full text-[10px] sm:text-xs font-bold transition-colors shadow-2xs">
                                    Buy Now
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<script>
if (typeof initPinterestWaterfall === 'function') {
    initPinterestWaterfall();
}
</script>
