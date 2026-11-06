<?php
/**
 * JayArt Pinterest-Inspired Visual Discovery Home View
 * Fluid Masonry Art Pinboard, Tactile Touch Discovery & Curated Collections
 */

$paintings = !empty($featuredPaintings) ? $featuredPaintings : (!empty($featuredProducts) ? $featuredProducts : []);
$categoryList = !empty($categories) ? $categories : [];
$blogs = !empty($latestBlogs) ? $latestBlogs : (!empty($recentBlogs) ? $recentBlogs : []);
?>

<!-- ====================================================================
     1. PINTEREST INSPIRATION HERO & DISCOVERY BAR
     ==================================================================== -->
<section class="w-full pt-2 sm:pt-4 pb-6 sm:pb-8">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        
        <!-- Editorial Header Pill & Title -->
        <div class="text-center max-w-3xl mx-auto mb-6 sm:mb-8 pt-2">
            <div class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 rounded-full bg-white border border-black/[0.06] shadow-xs mb-3.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-wider text-stone-700">Curated Master Atelier • 2026 Collection</span>
            </div>
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-heading font-extrabold text-[#18181B] tracking-tight leading-tight">
                Visual Inspiration &amp; Original Fine Art
            </h1>
            <p class="text-xs sm:text-sm text-stone-500 mt-2 max-w-xl mx-auto font-medium leading-relaxed">
                Explore hand-painted masterworks directly from artist studios. Every original arrives with an archival provenance certificate and museum crating.
            </p>
        </div>

        <!-- Pinterest-Style Horizontal Explore Topic Pills Bar -->
        <div class="relative mb-6 sm:mb-8">
            <div class="pinterest-scroll-row pb-2 -mx-3 px-3 sm:mx-0 sm:px-0">
                <a href="<?= SITE_URL ?>/shop" class="pinterest-topic-pill active flex items-center gap-1.5">
                    <span class="text-red-500 text-xs">✦</span>
                    <span>All Masterworks</span>
                </a>
                <a href="<?= SITE_URL ?>/shop?category=oil-paintings" class="pinterest-topic-pill">Oil on Linen</a>
                <a href="<?= SITE_URL ?>/shop?category=acrylic-art" class="pinterest-topic-pill">Heavy Acrylic</a>
                <a href="<?= SITE_URL ?>/shop?category=watercolor-paintings" class="pinterest-topic-pill">Watercolors</a>
                <a href="<?= SITE_URL ?>/shop?category=modern-abstract" class="pinterest-topic-pill">Modern Abstract</a>
                <a href="<?= SITE_URL ?>/shop?category=traditional-heritage" class="pinterest-topic-pill">Heritage &amp; Spiritual</a>
                <a href="<?= SITE_URL ?>/shop?filter=gold" class="pinterest-topic-pill flex items-center gap-1">
                    <span class="text-amber-500 font-bold">★</span>
                    <span>24K Gold Leaf</span>
                </a>
                <a href="<?= SITE_URL ?>/shop?filter=charcoal" class="pinterest-topic-pill">Charcoal Mono</a>
                <a href="<?= SITE_URL ?>/offers" class="pinterest-topic-pill bg-amber-50 text-amber-900 border-amber-200 hover:bg-[#18181B] hover:text-white font-bold">
                    Salon Offers 30-50% Off
                </a>
            </div>
        </div>

        <!-- Discovery Filter Summary Row -->
        <div class="flex items-center justify-between pb-4 border-b border-black/[0.05] text-xs text-stone-500 mb-6 sm:mb-8">
            <div class="flex items-center gap-2">
                <span class="font-bold text-[#18181B]"><?= count($paintings) ?> Original Works</span>
                <span class="text-stone-300">•</span>
                <span class="hidden sm:inline">Authenticated Provenance</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?= SITE_URL ?>/shop" class="font-bold text-[#18181B] hover:underline flex items-center gap-1">
                    <span>Explore Full Catalog</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- 2. PINTEREST FLUID MASONRY PINBOARD                            -->
        <!--    Mobile: 2 Tight Columns | Tablet: 3 | Desktop: 4 | XL: 5    -->
        <!-- ============================================================== -->
        <?php if (!empty($paintings)): ?>
        <div class="pinterest-masonry-grid">
            <?php foreach ($paintings as $idx => $p): 
                $rawImg = !empty($p['primary_image']) ? $p['primary_image'] : (!empty($p['image']) ? $p['image'] : (!empty($p['image_path']) ? $p['image_path'] : ''));
                $imgUrl = Product::imageUrl($rawImg);
                $isSaved = in_array((int)$p['id'], $_SESSION['wishlist'] ?? [], true);
                $displayPrice = !empty($p['discount_price']) ? (float)$p['discount_price'] : (float)$p['price'];
                $hasDiscount = !empty($p['discount_price']) && (float)$p['discount_price'] < (float)$p['price'];
                $medium = !empty($p['medium']) ? $p['medium'] : (!empty($p['category_name']) ? $p['category_name'] : 'Original Painting');
                $dimensions = !empty($p['dimensions']) ? $p['dimensions'] : '30 × 40 in';
                $artistName = !empty($p['artist_name']) ? $p['artist_name'] : 'JayArt Master';
            ?>
            <div class="pinterest-pin group" data-pin-id="<?= (int)$p['id'] ?>">
                
                <!-- Media Box -->
                <div class="pinterest-pin-media" onclick="openArtModal(<?= htmlspecialchars(json_encode([
                    'id'            => (int)$p['id'],
                    'title'         => $p['title'],
                    'slug'          => $p['slug'],
                    'artist'        => $artistName,
                    'medium'        => $medium,
                    'dimensions'    => $dimensions,
                    'price'         => $displayPrice,
                    'priceFormatted'=> Validator::formatPrice($displayPrice),
                    'originalPrice' => $hasDiscount ? Validator::formatPrice((float)$p['price']) : null,
                    'image'         => $imgUrl,
                    'isSaved'       => $isSaved,
                    'description'   => !empty($p['description']) ? strip_tags($p['description']) : 'Exquisite authentic atelier original painted with archival museum-grade pigments.'
                ])) ?>)">
                    
                    <img src="<?= htmlspecialchars($imgUrl) ?>" 
                         alt="<?= htmlspecialchars($p['title']) ?>" 
                         onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=900&q=80';" 
                         loading="<?= $idx < 6 ? 'eager' : 'lazy' ?>">

                    <!-- Pinterest Signature Floating Action Overlay -->
                    <div class="pinterest-pin-overlay" onclick="event.stopPropagation();">
                        
                        <!-- Top Row: Medium Tag + Red Save Button -->
                        <div class="flex items-center justify-between w-full">
                            <span class="pinterest-tag-badge line-clamp-1 max-w-[120px] sm:max-w-[150px]">
                                <?= htmlspecialchars($medium) ?>
                            </span>

                            <button type="button" 
                                    onclick="toggleWishlist(<?= (int)$p['id'] ?>, this)" 
                                    class="pinterest-save-btn <?= $isSaved ? 'is-saved' : '' ?>"
                                    title="Save to My Collection">
                                <svg width="12" height="12" fill="<?= $isSaved ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                <span><?= $isSaved ? 'Saved' : 'Save' ?></span>
                            </button>
                        </div>

                        <!-- Bottom Row: Quick Inspect Eye + Quick Buy Button -->
                        <div class="flex items-center justify-between w-full pt-4">
                            <button type="button" 
                                    class="pinterest-inspect-btn" 
                                    title="Quick Inspect Provenance & Details"
                                    onclick="this.closest('.pinterest-pin').querySelector('.pinterest-pin-media').click()">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>

                            <button type="button" 
                                    onclick="quickAddToCart(<?= (int)$p['id'] ?>)" 
                                    class="pinterest-quick-buy" 
                                    title="Add to Collection Bag">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>Add to Bag</span>
                            </button>
                        </div>

                    </div>
                </div>

                <!-- Pin Card Meta Footer -->
                <div class="p-3 sm:p-4">
                    <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($p['slug']) ?>" class="block group-hover:text-stone-600 transition-colors">
                        <h3 class="font-heading font-bold text-xs sm:text-sm text-[#18181B] line-clamp-1 leading-snug">
                            <?= htmlspecialchars($p['title']) ?>
                        </h3>
                    </a>

                    <!-- Artist & Provenance Attribution -->
                    <div class="flex items-center justify-between text-[11px] text-stone-500 mt-1">
                        <span class="truncate max-w-[70%] font-medium"><?= htmlspecialchars($artistName) ?></span>
                        <span class="flex items-center gap-0.5 text-amber-600 font-bold flex-shrink-0">
                            <svg width="11" height="11" class="fill-current text-amber-500" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span>4.9</span>
                        </span>
                    </div>

                    <!-- Price & Mobile Quick Action -->
                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-black/[0.04]">
                        <div class="flex items-baseline gap-1.5">
                            <span class="font-mono font-bold text-xs sm:text-sm text-[#18181B]">
                                <?= Validator::formatPrice($displayPrice) ?>
                            </span>
                            <?php if ($hasDiscount): ?>
                                <span class="font-mono text-[10px] text-stone-400 line-through">
                                    <?= Validator::formatPrice((float)$p['price']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Mobile direct bag tap (Visible on mobile/tablet) -->
                        <button type="button" 
                                onclick="quickBuyNow(<?= (int)$p['id'] ?>)" 
                                class="lg:hidden text-[11px] font-bold text-stone-800 hover:text-black bg-stone-100 px-2.5 py-1 rounded-full transition-colors" 
                                title="Instant Acquisition">
                            Buy Now
                        </button>
                    </div>

                </div>

            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="bg-white rounded-3xl border border-stone-200/80 p-12 text-center my-6 shadow-xs max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-full bg-stone-100 flex items-center justify-center mx-auto mb-4 text-stone-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-base font-bold text-stone-900">Curating New Masterpieces</h3>
            <p class="text-xs text-stone-500 mt-1">Our curatorial atelier is cataloging new acquisitions.</p>
            <a href="<?= SITE_URL ?>/shop" class="inline-block mt-4 px-6 py-2.5 bg-[#18181B] hover:bg-black text-white rounded-full text-xs font-bold shadow-sm transition-colors">
                Browse Full Catalog
            </a>
        </div>
        <?php endif; ?>

        <!-- Pinterest-Inspired Infinite "Load More In Gallery" Anchor -->
        <div class="text-center mt-10 sm:mt-14 mb-12 sm:mb-16">
            <a href="<?= SITE_URL ?>/shop" class="inline-flex items-center gap-2 px-8 py-3.5 bg-[#18181B] hover:bg-black text-white text-xs sm:text-sm font-bold uppercase tracking-wider rounded-full shadow-md hover:shadow-lg transition-all active:scale-95">
                <span>Explore All <?= count($paintings) ?>+ Artworks in Salon Catalog</span>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

    </div>
</section>

<!-- ====================================================================
     3. PINTEREST-STYLE CURATED BOARDS / THEMATIC SPACES
     ==================================================================== -->
<section class="w-full py-12 bg-stone-100/60 border-t border-b border-black/[0.04]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500 block mb-1">Inspirational Art Spaces</span>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-[#18181B] tracking-tight">
                    Curated Atelier Boards
                </h2>
            </div>
            <a href="<?= SITE_URL ?>/shop" class="text-xs font-bold text-stone-700 hover:text-black hover:underline flex items-center gap-1">
                <span>View all aesthetic boards</span>
                <span>&rarr;</span>
            </a>
        </div>

        <!-- 3 Pinterest Board Cards (Collage-Style Previews) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Board 1: Heritage & Sacred Waters -->
            <a href="<?= SITE_URL ?>/shop?category=traditional-heritage" class="bg-white rounded-3xl p-4 sm:p-5 border border-black/[0.05] shadow-xs hover:shadow-md transition-all group block">
                <div class="grid grid-cols-3 gap-2 h-44 sm:h-52 rounded-2xl overflow-hidden mb-4 bg-stone-100">
                    <div class="col-span-2 h-full">
                        <img src="https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=600&q=80" alt="Sacred Waters" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="grid grid-rows-2 gap-2 h-full">
                        <img src="https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=300&q=80" alt="Banaras Ghats" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=300&q=80" alt="Heritage Flute" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
                <h3 class="font-heading font-bold text-base text-[#18181B] group-hover:text-stone-600 transition-colors">
                    Sacred Banaras &amp; Spiritual Heritage
                </h3>
                <p class="text-xs text-stone-500 mt-1">Oils and temperas celebrating the Ghats and divine mythology</p>
                <div class="flex items-center justify-between text-xs font-bold text-stone-700 mt-3 pt-3 border-t border-black/[0.04]">
                    <span>12 Original Works</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Board 2: Modern Impasto & Bold Geometry -->
            <a href="<?= SITE_URL ?>/shop?category=modern-abstract" class="bg-white rounded-3xl p-4 sm:p-5 border border-black/[0.05] shadow-xs hover:shadow-md transition-all group block">
                <div class="grid grid-cols-3 gap-2 h-44 sm:h-52 rounded-2xl overflow-hidden mb-4 bg-stone-100">
                    <div class="col-span-2 h-full">
                        <img src="https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=600&q=80" alt="Abstract Impasto" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="grid grid-rows-2 gap-2 h-full">
                        <img src="https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=300&q=80" alt="Modern Brush" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1549887534-1541e9326642?w=300&q=80" alt="Vibrant Palette" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
                <h3 class="font-heading font-bold text-base text-[#18181B] group-hover:text-stone-600 transition-colors">
                    Textured Impasto &amp; Abstract Vibrations
                </h3>
                <p class="text-xs text-stone-500 mt-1">Palette-knife heavy acrylics tailored for contemporary architecture</p>
                <div class="flex items-center justify-between text-xs font-bold text-stone-700 mt-3 pt-3 border-t border-black/[0.04]">
                    <span>16 Original Works</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

            <!-- Board 3: Belgian Linen Master Oils -->
            <a href="<?= SITE_URL ?>/shop?category=oil-paintings" class="bg-white rounded-3xl p-4 sm:p-5 border border-black/[0.05] shadow-xs hover:shadow-md transition-all group block">
                <div class="grid grid-cols-3 gap-2 h-44 sm:h-52 rounded-2xl overflow-hidden mb-4 bg-stone-100">
                    <div class="col-span-2 h-full">
                        <img src="https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=600&q=80" alt="Linen Oil" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="grid grid-rows-2 gap-2 h-full">
                        <img src="https://images.unsplash.com/photo-1549887534-1541e9326642?w=300&q=80" alt="Atelier Master" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <img src="https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=300&q=80" alt="Oil Portrait" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                </div>
                <h3 class="font-heading font-bold text-base text-[#18181B] group-hover:text-stone-600 transition-colors">
                    Belgian Linen &amp; Classical Realism
                </h3>
                <p class="text-xs text-stone-500 mt-1">Layered glazing techniques inspired by Dutch and Renaissance masters</p>
                <div class="flex items-center justify-between text-xs font-bold text-stone-700 mt-3 pt-3 border-t border-black/[0.04]">
                    <span>14 Original Works</span>
                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                </div>
            </a>

        </div>

    </div>
</section>

<!-- ====================================================================
     4. MUSEUM PROVENANCE GUARANTEE STRIP
     ==================================================================== -->
<section class="w-full py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 text-center">
            
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-[#18181B] mb-3 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h4 class="font-bold text-xs sm:text-sm text-[#18181B]">Physical Provenance</h4>
                <p class="text-[11px] text-stone-500 mt-1">Stamped atelier certificate signed by the artist</p>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-[#18181B] mb-3 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <h4 class="font-bold text-xs sm:text-sm text-[#18181B]">Insured Art Crating</h4>
                <p class="text-[11px] text-stone-500 mt-1">Multi-layered moisture-proof archival wooden crates</p>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-[#18181B] mb-3 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                </div>
                <h4 class="font-bold text-xs sm:text-sm text-[#18181B]">14-Day In-Home Trial</h4>
                <p class="text-[11px] text-stone-500 mt-1">Full inspection in your living space with guaranteed returns</p>
            </div>

            <div class="flex flex-col items-center">
                <div class="w-12 h-12 rounded-full bg-stone-100 flex items-center justify-center text-[#18181B] mb-3 shadow-xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h4 class="font-bold text-xs sm:text-sm text-[#18181B]">Multi-Currency &amp; EMI</h4>
                <p class="text-[11px] text-stone-500 mt-1">Seamless global card, UPI, bank wire &amp; zero-interest splits</p>
            </div>

        </div>
    </div>
</section>

<!-- ====================================================================
     5. ARTIST STORIES & JOURNAL (PINTEREST EDITORIAL JOURNAL)
     ==================================================================== -->
<?php if (!empty($blogs)): ?>
<section class="w-full py-12 sm:py-16 bg-[#FBFBFA]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex items-center justify-between mb-8">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500 block mb-1">Atelier Chronicles</span>
                <h2 class="text-2xl sm:text-3xl font-heading font-extrabold text-[#18181B] tracking-tight">
                    Artist Profiles &amp; Studio Stories
                </h2>
            </div>
            <a href="<?= SITE_URL ?>/blog" class="text-xs font-bold text-stone-700 hover:text-black hover:underline flex items-center gap-1">
                <span>View all stories</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($blogs as $post): 
                $blogImg = !empty($post['featured_image']) ? $post['featured_image'] : 'https://images.unsplash.com/photo-1549887534-1541e9326642?w=600&q=80';
            ?>
            <a href="<?= SITE_URL ?>/blog/<?= htmlspecialchars($post['slug']) ?>" class="group block bg-white rounded-3xl overflow-hidden border border-black/[0.05] shadow-xs hover:shadow-md transition-all">
                <div class="aspect-[16/10] overflow-hidden bg-stone-100">
                    <img src="<?= htmlspecialchars($blogImg) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-5">
                    <span class="text-[10px] font-bold text-stone-400 uppercase tracking-wider block mb-1">
                        <?= date('M d, Y', strtotime($post['created_at'] ?? 'now')) ?>
                    </span>
                    <h3 class="font-heading font-bold text-sm sm:text-base text-[#18181B] group-hover:text-stone-600 transition-colors leading-snug">
                        <?= htmlspecialchars($post['title']) ?>
                    </h3>
                    <p class="text-xs text-stone-500 mt-2 line-clamp-2 leading-relaxed">
                        <?= htmlspecialchars(strip_tags($post['excerpt'] ?? ($post['content'] ?? ''))) ?>
                    </p>
                    <span class="text-xs font-bold text-stone-800 mt-3 inline-flex items-center gap-1">
                        Read Story &rarr;
                    </span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
<?php endif; ?>

<!-- ====================================================================
     6. PINTEREST QUICK INSPECT LIGHTBOX MODAL
     ==================================================================== -->
<div id="artInspectModal" onclick="closeArtModal(event)">
    <div class="art-modal-container" onclick="event.stopPropagation();">
        <div class="relative p-5 sm:p-8">
            
            <!-- Close Button -->
            <button type="button" 
                    onclick="closeArtModal()" 
                    class="absolute top-4 right-4 w-9 h-9 rounded-full bg-stone-100 hover:bg-stone-200 text-stone-700 flex items-center justify-center transition-colors z-20"
                    aria-label="Close Preview">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-center">
                
                <!-- Left: High-Res Painting Preview -->
                <div class="lg:col-span-7 flex items-center justify-center bg-stone-50 rounded-2xl p-4 sm:p-6 border border-black/[0.04]">
                    <div class="relative max-h-[60vh] flex items-center justify-center">
                        <img id="inspectModalImg" src="" alt="Painting" class="max-h-[55vh] w-auto object-contain rounded-xl shadow-lg">
                    </div>
                </div>

                <!-- Right: Artwork Information & Framing Controls -->
                <div class="lg:col-span-5 flex flex-col justify-between space-y-4">
                    
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span id="inspectModalMedium" class="text-[11px] font-bold text-stone-700 bg-stone-100 px-3 py-1 rounded-full"></span>
                            <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                <span>Certified Original</span>
                            </span>
                        </div>

                        <h2 id="inspectModalTitle" class="text-xl sm:text-2xl font-heading font-extrabold text-[#18181B] leading-snug"></h2>
                        <p id="inspectModalArtist" class="text-xs font-medium text-stone-500 mt-1"></p>
                        
                        <p id="inspectModalDesc" class="text-xs text-stone-600 mt-3 leading-relaxed line-clamp-3"></p>
                    </div>

                    <!-- Framing Selector -->
                    <div class="pt-2 border-t border-black/[0.06]">
                        <label class="text-xs font-bold text-[#18181B] block mb-2">Museum Framing Options:</label>
                        <div class="grid grid-cols-3 gap-2 text-center text-xs">
                            <button type="button" onclick="selectModalFrame('Rolled Canvas', 0, this)" class="modal-frame-btn active p-2.5 rounded-xl border border-[#18181B] bg-stone-900 text-white font-bold transition-all">
                                <span class="block font-bold">Rolled</span>
                                <span class="text-[10px] text-stone-300">Included</span>
                            </button>
                            <button type="button" onclick="selectModalFrame('Stretched Canvas', 1200, this)" class="modal-frame-btn p-2.5 rounded-xl border border-stone-200 bg-white hover:border-black text-stone-800 font-medium transition-all">
                                <span class="block font-bold">Stretched</span>
                                <span class="text-[10px] text-stone-500">+₹1,200</span>
                            </button>
                            <button type="button" onclick="selectModalFrame('Teak Floating Frame', 2500, this)" class="modal-frame-btn p-2.5 rounded-xl border border-stone-200 bg-white hover:border-black text-stone-800 font-medium transition-all">
                                <span class="block font-bold">Teak Float</span>
                                <span class="text-[10px] text-stone-500">+₹2,500</span>
                            </button>
                        </div>
                    </div>

                    <!-- Price & Actions -->
                    <div class="pt-3 border-t border-black/[0.06]">
                        <div class="flex items-baseline justify-between mb-3">
                            <span class="text-xs text-stone-500 font-semibold">Acquisition Price:</span>
                            <div class="text-right">
                                <span id="inspectModalPrice" class="font-mono text-xl sm:text-2xl font-bold text-[#18181B]"></span>
                                <span id="inspectModalOriginalPrice" class="font-mono text-xs text-stone-400 line-through ml-2 hidden"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" 
                                    id="modalAddBagBtn"
                                    onclick="executeModalAddToCart()" 
                                    class="w-full py-3 px-4 border border-[#18181B] text-[#18181B] hover:bg-stone-100 rounded-full text-xs font-bold transition-colors">
                                Add to Bag
                            </button>
                            <button type="button" 
                                    id="modalBuyNowBtn"
                                    onclick="executeModalBuyNow()" 
                                    class="w-full py-3 px-4 bg-[#18181B] hover:bg-black text-white rounded-full text-xs font-bold shadow-md transition-all">
                                Instant Acquire
                            </button>
                        </div>

                        <div class="text-center pt-2">
                            <a id="inspectModalFullLink" href="#" class="text-xs text-stone-500 hover:text-black underline font-semibold">
                                View Full Artwork Details Page &rarr;
                            </a>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<!-- ====================================================================
     7. JAVASCRIPT FOR PINTEREST QUICK VIEW MODAL & INTERACTIONS
     ==================================================================== -->
<script>
let currentModalArt = null;
let currentModalFrame = 'Rolled Canvas';
let currentModalFrameSurcharge = 0;

function openArtModal(artData) {
    currentModalArt = artData;
    currentModalFrame = 'Rolled Canvas';
    currentModalFrameSurcharge = 0;

    document.getElementById('inspectModalImg').src = artData.image;
    document.getElementById('inspectModalTitle').textContent = artData.title;
    document.getElementById('inspectModalArtist').textContent = artData.artist + ' • ' + artData.dimensions;
    document.getElementById('inspectModalMedium').textContent = artData.medium;
    document.getElementById('inspectModalDesc').textContent = artData.description;
    document.getElementById('inspectModalFullLink').href = (window.SITE_URL || '') + '/product/' + encodeURIComponent(artData.slug);
    
    updateModalPriceDisplay();

    // Reset frame buttons
    document.querySelectorAll('.modal-frame-btn').forEach((btn, idx) => {
        if (idx === 0) {
            btn.className = 'modal-frame-btn active p-2.5 rounded-xl border border-[#18181B] bg-stone-900 text-white font-bold transition-all';
        } else {
            btn.className = 'modal-frame-btn p-2.5 rounded-xl border border-stone-200 bg-white hover:border-black text-stone-800 font-medium transition-all';
        }
    });

    const modal = document.getElementById('artInspectModal');
    modal.classList.add('modal-open');
    document.body.style.overflow = 'hidden';
}

function closeArtModal(e) {
    if (e && e.target !== e.currentTarget && !e.target.closest('button[aria-label="Close Preview"]')) {
        return;
    }
    const modal = document.getElementById('artInspectModal');
    modal.classList.remove('modal-open');
    document.body.style.overflow = '';
}

function selectModalFrame(frameName, surcharge, btn) {
    currentModalFrame = frameName;
    currentModalFrameSurcharge = surcharge;

    document.querySelectorAll('.modal-frame-btn').forEach(b => {
        b.className = 'modal-frame-btn p-2.5 rounded-xl border border-stone-200 bg-white hover:border-black text-stone-800 font-medium transition-all';
    });
    btn.className = 'modal-frame-btn active p-2.5 rounded-xl border border-[#18181B] bg-stone-900 text-white font-bold transition-all';

    updateModalPriceDisplay();
}

function updateModalPriceDisplay() {
    if (!currentModalArt) return;
    const base = parseFloat(currentModalArt.price) || 0;
    const total = base + currentModalFrameSurcharge;
    
    // Approximate currency formatting for modal display
    const priceEl = document.getElementById('inspectModalPrice');
    if (typeof window.CURRENCY_SYMBOL !== 'undefined') {
        priceEl.textContent = window.CURRENCY_SYMBOL + ' ' + total.toLocaleString('en-IN', { minimumFractionDigits: 2 });
    } else {
        priceEl.textContent = '₹ ' + total.toLocaleString('en-IN', { minimumFractionDigits: 2 });
    }
}

async function executeModalAddToCart() {
    if (!currentModalArt) return;
    const btn = document.getElementById('modalAddBagBtn');
    if (btn) btn.disabled = true;
    try {
        await quickAddToCart(currentModalArt.id, 1, currentModalFrame);
        closeArtModal();
    } finally {
        if (btn) btn.disabled = false;
    }
}

async function executeModalBuyNow() {
    if (!currentModalArt) return;
    const btn = document.getElementById('modalBuyNowBtn');
    if (btn) btn.disabled = true;
    try {
        await quickBuyNow(currentModalArt.id, 1, currentModalFrame);
    } finally {
        if (btn) btn.disabled = false;
    }
}

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeArtModal();
});
</script>
