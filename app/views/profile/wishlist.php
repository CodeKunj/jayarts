<?php
/**
 * JayArt Collector Saved Artworks Wishlist View
 */
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <div class="flex items-center justify-between pb-6 border-b border-linen mb-8">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-ink">My Saved Artworks</h1>
            <p class="text-xs text-muted mt-1">Paintings you've earmarked for your personal collection</p>
        </div>
        <a href="<?= SITE_URL ?>/shop" class="text-xs font-semibold text-forest hover:text-gold transition-colors">
            &larr; Discover More Paintings
        </a>
    </div>

    <?php if (empty($artworks)): ?>
        <div class="bg-white border border-linen rounded-2xl p-12 text-center my-8 max-w-md mx-auto shadow-sm">
            <svg class="w-12 h-12 text-muted mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            <h3 class="font-serif text-lg font-bold text-ink mb-1">Your Wishlist is Empty</h3>
            <p class="text-xs text-muted mb-6">Tap the heart icon on any painting in our gallery to bookmark it here.</p>
            <a href="<?= SITE_URL ?>/shop" class="btn-primary py-2.5 px-6 rounded-full text-xs">Explore Artworks</a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            <?php foreach ($artworks as $paint): 
                $img = !empty($paint['primary_image']) ? SITE_URL . '/' . $paint['primary_image'] : SITE_URL . '/uploads/products/echoes-twilight.jpg';
                $price = !empty($paint['discount_price']) ? $paint['discount_price'] : $paint['price'];
            ?>
                <div class="painting-card group">
                    <div class="painting-image-box">
                        <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($paint['slug']) ?>">
                            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($paint['title']) ?>">
                        </a>
                        <!-- Remove button -->
                        <button type="button" class="btn-wishlist active" data-product-id="<?= (int)$paint['id'] ?>" aria-label="Remove from wishlist">
                            <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </button>
                    </div>
                    <div class="p-3">
                        <div class="text-[10px] text-muted truncate"><?= htmlspecialchars($paint['artist_name']) ?></div>
                        <h4 class="font-serif font-bold text-xs sm:text-sm text-ink truncate mb-1">
                            <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($paint['slug']) ?>" class="hover:text-forest">
                                <?= htmlspecialchars($paint['title']) ?>
                            </a>
                        </h4>
                        <div class="font-sans font-bold text-xs text-forest mb-2">
                            <?= Validator::formatPrice((float)$price) ?>
                        </div>
                        <button type="button" class="btn-add-to-cart w-full py-1.5 bg-canvas hover:bg-forest hover:text-white border border-linen rounded-lg text-xs font-semibold transition-colors" data-product-id="<?= (int)$paint['id'] ?>">
                            Add to Cart
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

