<?php
/**
 * JayArt Creative & Artistic Pinterest-Inspired Visual Discovery View
 * High-End Editorial Atelier Pinboard, Natural Aspect Ratios & Tactile Touch Feed
 */

$paintings = !empty($featuredPaintings) ? $featuredPaintings : (!empty($featuredProducts) ? $featuredProducts : []);
$categoryList = !empty($categories) ? $categories : [];
$blogs = !empty($latestBlogs) ? $latestBlogs : (!empty($recentBlogs) ? $recentBlogs : []);
?>

<!-- ====================================================================
     1. CREATIVE ATELIER HERO & INSPIRATION BANNER
     ==================================================================== -->
<section class="w-full pt-4 sm:pt-8 pb-6 sm:pb-10 bg-[#FAF6F0]/65 backdrop-blur-xs border-b border-[#B67A31]/15">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        
        <!-- Hero Editorial Text -->
        <div class="text-center max-w-3xl mx-auto mb-6 sm:mb-8 pt-2">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/90 border border-[#B67A31]/20 shadow-2xs mb-4">
                <span class="w-2 h-2 rounded-full bg-[#E60023] animate-pulse"></span>
                <span class="text-[10px] sm:text-xs font-mono font-bold uppercase tracking-wider text-stone-800">
                    Fine Art Atelier • 2026 Visual Salon
                </span>
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-black text-[#18181B] tracking-tight leading-[1.08] mb-3 sm:mb-4">
                Where Masterstrokes <span class="italic font-normal text-stone-500">Meet Soul</span>.
            </h1>

            <p class="text-xs sm:text-sm text-stone-600 max-w-xl mx-auto font-medium leading-relaxed">
                Discover authenticated original paintings hand-crafted on archival Belgian linen, Arches paper, and natural silk direct from master studios.
            </p>
        </div>

        <!-- Pinterest-Inspired Horizontal Topic Discovery Row (1-Thumb Navigation) -->
        <div class="relative">
            <div class="pinterest-scroll-row pb-2 -mx-3 px-3 sm:mx-0 sm:px-0">
                <a href="<?= SITE_URL ?>/shop" class="pinterest-topic-pill active flex items-center gap-1.5">
                    <span class="text-[#E60023] text-xs">✦</span>
                    <span>All Masterworks</span>
                </a>
                <a href="<?= SITE_URL ?>/shop?category=oil-paintings" class="pinterest-topic-pill">
                    Oil on Linen
                </a>
                <a href="<?= SITE_URL ?>/shop?category=acrylic-art" class="pinterest-topic-pill">
                    Heavy Acrylic Impasto
                </a>
                <a href="<?= SITE_URL ?>/shop?category=watercolor-paintings" class="pinterest-topic-pill">
                    Pure Watercolors
                </a>
                <a href="<?= SITE_URL ?>/shop?category=modern-abstract" class="pinterest-topic-pill">
                    Modern Abstract
                </a>
                <a href="<?= SITE_URL ?>/shop?category=traditional-heritage" class="pinterest-topic-pill">
                    Spiritual &amp; Banaras
                </a>
                <a href="<?= SITE_URL ?>/shop?filter=gold" class="pinterest-topic-pill flex items-center gap-1">
                    <span class="text-amber-500 font-bold">★</span>
                    <span>24K Gold Leaf</span>
                </a>
                <a href="<?= SITE_URL ?>/shop?filter=charcoal" class="pinterest-topic-pill">
                    Charcoal Monochrome
                </a>
                <a href="<?= SITE_URL ?>/offers" class="pinterest-topic-pill bg-amber-50 text-amber-900 border-amber-300 font-bold hover:bg-[#18181B] hover:text-white">
                    Salon Offers 30-50% Off
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ====================================================================
     2. PINTEREST VISUAL WALL & DISCOVERY CONTROLS
     ==================================================================== -->
<section class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-10">
    
    <?php if (empty($paintings)): ?>
        <!-- Clean Atelier Curating State When Catalog Has No Artworks Yet -->
        <div class="py-16 px-4 text-center max-w-xl mx-auto bg-white/80 backdrop-blur-xs rounded-3xl border border-[#B67A31]/20 shadow-xs my-6">
            <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-800 flex items-center justify-center mx-auto mb-4 border border-[#B67A31]/20">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
            <h3 class="font-serif font-black text-xl text-[#18181B] mb-2">Curating New Masterworks</h3>
            <p class="text-xs sm:text-sm text-stone-600 leading-relaxed mb-6 font-medium">
                Our atelier artists are currently preparing new original paintings in oil on linen, heavy acrylic, and pure watercolor. Check back shortly or commission a bespoke masterpiece.
            </p>
            <div class="flex items-center justify-center gap-3 flex-wrap">
                <a href="<?= SITE_URL ?>/contact" class="px-5 py-2.5 bg-[#18181B] hover:bg-black text-white rounded-full text-xs font-bold transition-all shadow-xs">
                    Commission Custom Painting
                </a>
                <a href="<?= SITE_URL ?>/shop" class="px-5 py-2.5 bg-white hover:bg-stone-100 text-stone-800 border border-stone-300 rounded-full text-xs font-bold transition-all">
                    Explore Gallery Archive
                </a>
            </div>
        </div>
    <?php else: ?>

        <!-- Feed Status Bar -->
        <div class="flex items-center justify-between pb-4 border-b border-black/[0.06] text-xs text-stone-500 mb-6 sm:mb-8">
            <div class="flex items-center gap-2">
                <span class="font-extrabold text-[#18181B] text-xs sm:text-sm"><?= count($paintings) ?> Original Paintings</span>
                <span class="text-stone-300">•</span>
                <span class="hidden sm:inline font-medium">Museum Provenance Included</span>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="<?= SITE_URL ?>/shop" class="text-xs font-bold text-[#18181B] hover:text-stone-600 flex items-center gap-1 transition-colors">
                    <span>View All In Catalog</span>
                    <span class="text-sm">&rarr;</span>
                </a>
            </div>
        </div>

        <!-- PINTEREST MULTI-COLUMN MASONRY WALL (2 Cols on Mobile, 3-5 on Larger Screens) -->
        <div class="pinterest-masonry-grid" id="homeMasonryWall">
            <?php 
            $wishlistIds = $_SESSION['wishlist'] ?? [];
            foreach ($paintings as $index => $p): 
                $rawImg = !empty($p['primary_image']) ? $p['primary_image'] : (!empty($p['image']) ? $p['image'] : (!empty($p['image_path']) ? $p['image_path'] : ''));
                $cardImg = Product::imageUrl($rawImg);
                $pid = (int)($p['id'] ?? ($index + 1));
                $isWish = in_array($pid, $wishlistIds, true);
                $price = (float)(!empty($p['discount_price']) ? $p['discount_price'] : ($p['price'] ?? 0));
                $origPrice = (float)($p['price'] ?? 0);
                $hasDiscount = !empty($p['discount_price']) && ($p['discount_price'] < $origPrice);
                $artistName = !empty($p['artist_name']) ? $p['artist_name'] : 'Atelier Master Artist';
                $dimensions = !empty($p['dimensions']) ? $p['dimensions'] : 'Original Size';
                $medium = !empty($p['medium']) ? $p['medium'] : 'Original Pigments on Linen';
                $slug = !empty($p['slug']) ? $p['slug'] : ('art-' . $pid);
                $desc = !empty($p['description']) ? strip_tags($p['description']) : 'Authentic handmade atelier masterpiece painted with lightfast archival pigments.';
            ?>

                <!-- Individual Pinterest Pin -->
                <div class="pinterest-pin group" data-pin-id="<?= $pid ?>">
                    
                    <!-- Media Box with Natural Aspect Ratio & Pinterest Floating Controls -->
                    <div class="pinterest-pin-media" style="aspect-ratio: <?= Product::getAspectRatio($dimensions, $index) ?>;" onclick='openArtInspectModal(<?= json_encode([
                        'id' => $pid,
                        'title' => $p['title'],
                        'slug' => $slug,
                        'image' => $cardImg,
                        'artist' => $artistName,
                        'medium' => $medium,
                        'dimensions' => $dimensions,
                        'price' => $price,
                        'formatted_price' => Validator::formatPrice($price),
                        'is_wish' => $isWish,
                        'description' => $desc
                    ]) ?>)'>
                        
                        <img src="<?= htmlspecialchars($cardImg) ?>" 
                             alt="<?= htmlspecialchars($p['title']) ?>" 
                             loading="<?= $index < 4 ? 'eager' : 'lazy' ?>"
                             onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=900&q=80';">

                        <!-- Floating Hover / Mobile Overlay Actions -->
                        <div class="pinterest-pin-overlay" onclick="event.stopPropagation();">
                            
                            <!-- Top Row: Medium Pill & Red Save Pin Button -->
                            <div class="flex items-center justify-between w-full">
                                <span class="pinterest-tag-badge hidden sm:inline-block truncate max-w-[140px]">
                                    <?= htmlspecialchars($medium) ?>
                                </span>

                                <button type="button" 
                                        onclick="togglePinSave(<?= $pid ?>, this)" 
                                        class="pinterest-save-btn <?= $isWish ? 'is-saved' : '' ?> ml-auto"
                                        title="<?= $isWish ? 'Saved to Wishlist' : 'Save to Wishlist' ?>">
                                    <svg class="w-3.5 h-3.5" fill="<?= $isWish ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    <span class="save-label hidden sm:inline"><?= $isWish ? 'Saved' : 'Save' ?></span>
                                </button>
                            </div>

                            <!-- Bottom Row: Quick Inspect Eye & Quick Bag Addition -->
                            <div class="flex items-center justify-between w-full gap-1.5 pt-4">
                                <button type="button" 
                                        onclick='this.closest(".pinterest-pin").querySelector(".pinterest-pin-media").click()'
                                        class="pinterest-inspect-btn"
                                        title="Quick Inspect Provenance & Framing">
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

                    <!-- Pin Caption Section Below Artwork -->
                    <div class="p-3 sm:p-4 flex flex-col justify-between flex-1">
                        <div>
                            <div class="flex items-center justify-between text-[11px] text-stone-500 mb-1">
                                <span class="truncate max-w-[130px] sm:max-w-[170px] font-semibold text-stone-700 flex items-center gap-1">
                                    <?= htmlspecialchars($artistName) ?>
                                    <svg class="w-3 h-3 text-amber-500 inline flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </span>
                                <span class="text-[10px] text-stone-400 font-mono"><?= htmlspecialchars($dimensions) ?></span>
                            </div>

                            <h3 class="font-bold text-xs sm:text-sm text-[#18181B] leading-snug line-clamp-1 group-hover:text-black transition-colors">
                                <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($slug) ?>">
                                    <?= htmlspecialchars($p['title']) ?>
                                </a>
                            </h3>
                        </div>

                        <div class="mt-2.5 flex items-center justify-between gap-1">
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
                                    class="px-3 py-1 bg-stone-100 hover:bg-[#18181B] hover:text-white rounded-full text-[10px] sm:text-xs font-bold text-stone-800 transition-colors cursor-pointer">
                                Acquire
                            </button>
                        </div>
                    </div>

                </div>

            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</section>

<?php if (!empty($paintings)): ?>
<!-- Curated Atelier Spotlight: Sacred Kashi & Ghats Anthology -->
<section class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 mb-4">
    <div class="p-6 sm:p-8 bg-gradient-to-r from-[#2D2A26] to-[#18181B] text-white rounded-3xl shadow-sm border border-stone-800 flex flex-col sm:flex-row items-center justify-between gap-6">
        <div class="max-w-2xl">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400">Curated Atelier Board</span>
                <span class="text-stone-500">•</span>
                <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded-full text-stone-300">1-of-1 Originals</span>
            </div>
            <h3 class="font-serif font-black text-xl sm:text-2xl leading-tight mb-2">
                Sacred Kashi &amp; Ghats Anthology
            </h3>
            <p class="text-xs sm:text-sm text-stone-300 leading-relaxed font-light">
                Dawn water reflections, ancient stone temples, and dusk prayer aartis captured in natural gouache and impasto oils by master artists.
            </p>
        </div>
        <div class="flex items-center gap-3 flex-shrink-0">
            <a href="<?= SITE_URL ?>/shop?category=traditional-heritage" class="px-5 py-2.5 bg-amber-400 hover:bg-amber-300 text-stone-900 rounded-full font-bold text-xs sm:text-sm transition-colors shadow-xs">
                Explore Collection &rarr;
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ====================================================================
     3. MUSEUM PROVENANCE & ARCHIVAL CRATING STRIP
     ==================================================================== -->
<section class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-8 mb-8">
    <div class="bg-white/95 backdrop-blur-md border border-[#B67A31]/20 rounded-3xl p-6 sm:p-10 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 divide-y sm:divide-y-0 sm:divide-x divide-stone-100">
            
            <div class="flex items-start gap-4 sm:pr-4 pt-3 sm:pt-0">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-[#18181B] mb-0.5">Physical Provenance Certificate</h4>
                    <p class="text-xs text-stone-500 leading-relaxed">Each painting arrives accompanied by a signed Certificate of Authenticity signed by the master artist.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 sm:px-6 pt-6 sm:pt-0">
                <div class="w-12 h-12 rounded-2xl bg-stone-100 text-[#18181B] flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-[#18181B] mb-0.5">Insured Museum Crating</h4>
                    <p class="text-xs text-stone-500 leading-relaxed">Artworks are secured in custom timber-lined crates with shockproof archival cushioning for door delivery.</p>
                </div>
            </div>

            <div class="flex items-start gap-4 sm:pl-6 pt-6 sm:pt-0">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0 shadow-2xs">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <div>
                    <h4 class="font-bold text-sm text-[#18181B] mb-0.5">14-Day Private Viewing Returns</h4>
                    <p class="text-xs text-stone-500 leading-relaxed">Experience the artwork in your sanctuary. If it does not resonate, our white-glove courier retrieves it seamlessly.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ====================================================================
     4. INTERACTIVE QUICK ART INSPECT LIGHTBOX MODAL
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

                    <h2 id="modalArtTitle" class="text-xl sm:text-2xl font-serif font-black text-[#18181B] leading-tight mb-2"></h2>
                    <p id="modalArtDesc" class="text-xs text-stone-500 leading-relaxed mb-3"></p>

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
    document.getElementById('modalArtDesc').textContent = artData.description || '';
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

// Trigger Pinterest Waterfall layout
if (typeof initPinterestWaterfall === 'function') {
    initPinterestWaterfall();
}
</script>
