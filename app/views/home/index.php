<?php
/**
 * JayArt Modern Home Page View
 * Exact Clean UI Matching Reference (Shopcart E-Commerce Design)
 */
?>

<!-- 1. Signature Architectural Bento Hero Grid (Matching Reference Image 2 UI) -->
<section class="w-full pt-1 pb-4 sm:pb-8">
    <!-- Horizontal Snap Slider on Mobile (< lg), Full 12-Col Bento Grid on Desktop (lg+) -->
    <div id="mobileBentoSlider" class="flex lg:grid lg:grid-cols-12 overflow-x-auto lg:overflow-visible snap-x snap-mandatory scrollbar-none gap-3.5 sm:gap-5 -mx-4 px-4 sm:-mx-6 sm:px-6 lg:mx-0 lg:px-0 pb-2">
        
        <!-- ============================================================== -->
        <!-- 1. COLUMN 1 / SLIDE 1: Tall Card ("New Deals" / "Master Solo") -->
        <!-- ============================================================== -->
        <div class="w-[88vw] max-w-[360px] sm:w-[420px] sm:max-w-none flex-shrink-0 snap-center lg:w-auto lg:flex-shrink lg:col-span-4 bento-stone-1 rounded-[28px] sm:rounded-[34px] p-5 sm:p-7 flex flex-col justify-between relative overflow-hidden min-h-[460px] sm:min-h-[560px] shadow-xs group">
            
            <!-- Card Header -->
            <div class="relative z-10">
                <span class="text-[10px] font-bold uppercase tracking-wider text-stone-500 block mb-1">Curated Masterworks</span>
                <h2 class="text-3xl sm:text-4xl font-heading font-semibold text-[#18181B] tracking-tight leading-tight">
                    New Deals
                </h2>
                <p class="text-xs text-stone-600 mt-1">Direct acquisitions from master atelier studios</p>
            </div>

            <!-- Center: Floating Glassmorphic Artwork Card (Matching Reference Card) -->
            <div id="bentoHeroCarousel" class="relative z-10 w-full max-w-[280px] sm:max-w-[290px] mx-auto my-3 sm:my-4">
                <?php 
                $carouselArt = array_slice($featuredPaintings, 0, 4);
                foreach ($carouselArt as $idx => $art): 
                ?>
                <div class="bento-slide <?= $idx === 0 ? '' : 'hidden' ?> bg-white/95 backdrop-blur-md rounded-2xl p-4 sm:p-4.5 shadow-xl border border-white/80 transition-all duration-300" data-slide-index="<?= $idx ?>">
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-xl font-bold font-mono text-[#18181B]">
                            <?= !empty($art['discount_price']) ? Validator::formatPrice($art['discount_price']) : Validator::formatPrice($art['price']) ?>
                        </span>
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-full border border-amber-200/50">
                            <svg class="w-3 h-3 text-amber-500 fill-current inline" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            4.9
                        </span>
                    </div>
                    <p class="text-[11px] text-stone-500 font-medium mb-3 truncate"><?= htmlspecialchars($art['medium']) ?></p>
                    
                    <!-- Artwork Image -->
                    <div class="w-full aspect-[4/3] rounded-xl overflow-hidden bg-stone-100 mb-3 relative flex items-center justify-center p-2">
                        <img src="<?= htmlspecialchars($art['primary_image']) ?>" alt="<?= htmlspecialchars($art['title']) ?>" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=900&q=80';" class="max-h-full max-w-full object-contain drop-shadow-md rounded-lg">
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <div class="max-w-[65%]">
                            <h4 class="font-bold text-xs text-[#18181B] line-clamp-1"><?= htmlspecialchars($art['title']) ?></h4>
                            <p class="text-[10px] text-stone-400 truncate"><?= htmlspecialchars($art['artist_name']) ?></p>
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <button type="button" onclick="toggleWishlist(<?= $art['id'] ?>, this)" class="w-8 h-8 rounded-full bg-stone-100 hover:bg-red-50 text-stone-600 hover:text-red-500 flex items-center justify-center transition-colors shadow-xs" title="Save to Wishlist">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            </button>
                            <button type="button" onclick="quickAddToCart(<?= $art['id'] ?>)" class="w-8 h-8 rounded-full bg-[#18181B] hover:bg-black text-white flex items-center justify-center transition-transform active:scale-90 shadow-xs" title="Add to Bag">
                                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Bottom Navigation Bar (Matching Reference Image 2: "← Slide left and right →") -->
            <div class="relative z-10 flex items-center justify-between pt-3 sm:pt-4 border-t border-black/[0.06] text-xs font-medium text-stone-600">
                <button type="button" onclick="changeBentoSlide(-1)" class="w-8 sm:w-9 h-8 sm:h-9 rounded-full bg-white hover:bg-[#18181B] text-stone-700 hover:text-white flex items-center justify-center shadow-xs transition-colors" aria-label="Previous Slide">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <span class="text-xs text-stone-600 font-medium">Slide left and right</span>
                <button type="button" onclick="changeBentoSlide(1)" class="w-8 sm:w-9 h-8 sm:h-9 rounded-full bg-[#18181B] hover:bg-black text-white flex items-center justify-center shadow-xs transition-colors" aria-label="Next Slide">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- 2. COLUMN 2 / SLIDE 2: Stacked Bento Cards                      -->
        <!--    Top: "Great Value Deals" | Bottom: "EXCLUSIVE PureSpace Duo" -->
        <!-- ============================================================== -->
        <div class="w-[88vw] max-w-[360px] sm:w-[420px] sm:max-w-none flex-shrink-0 snap-center lg:w-auto lg:flex-shrink lg:col-span-5 flex flex-col justify-between gap-3 sm:gap-5">
            
            <!-- Top Card: Great Value Deals (Matching Big Center Card in Reference) -->
            <div class="bento-stone-2 rounded-[28px] sm:rounded-[34px] p-5 sm:p-7 flex flex-col justify-between relative overflow-hidden min-h-[270px] sm:min-h-[340px] shadow-xs group flex-1">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-heading font-semibold text-[#18181B] tracking-tight leading-tight">
                        Great Value Deals
                    </h2>
                    <p class="text-xs text-stone-600 mt-1">Acquire curated originals on sale with 30 &ndash; 50% discount</p>
                </div>

                <!-- Center Large Featured Artwork with Soft Drop Shadow -->
                <div class="my-2 sm:my-3 flex items-center justify-center relative">
                    <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($featuredPaintings[1]['slug'] ?? 'symphony-in-ochre-and-gold') ?>" class="block transform group-hover:scale-105 transition-transform duration-300">
                        <img src="<?= htmlspecialchars($featuredPaintings[1]['primary_image'] ?? 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=600') ?>" alt="Featured Art Deal" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=600';" class="max-h-44 sm:max-h-52 w-auto object-contain rounded-2xl drop-shadow-2xl">
                    </a>
                </div>

                <!-- Bottom Tag Pill & Action -->
                <div class="flex items-center justify-between pt-1">
                    <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-stone-800 bg-white/90 backdrop-blur-xs px-3 sm:px-3.5 py-1.5 rounded-full shadow-xs">
                        <svg class="w-3.5 h-3.5 text-amber-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>4.9 Authenticated Provenance</span>
                    </span>
                    <a href="<?= SITE_URL ?>/shop?filter=deals" class="text-xs font-bold text-[#18181B] hover:underline flex items-center gap-1">
                        Explore Deals &rarr;
                    </a>
                </div>
            </div>

            <!-- Bottom Split Card: "EXCLUSIVE PureSpace Focus Duo" (Matching Split in Reference) -->
            <div class="bento-stone-3 rounded-[28px] sm:rounded-[34px] p-4 sm:p-6 flex items-center justify-between gap-3 sm:gap-4 shadow-xs">
                <div class="max-w-[58%]">
                    <span class="text-[9px] font-extrabold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-white text-[#18181B] shadow-xs inline-block mb-1.5">
                        EXCLUSIVE
                    </span>
                    <h3 class="font-heading font-bold text-sm sm:text-lg text-[#18181B] leading-snug">
                        <?= htmlspecialchars($featuredPaintings[2]['title'] ?? 'Morning Ghats of Banaras') ?>
                    </h3>
                    <p class="text-[11px] text-stone-500 mt-0.5 sm:mt-1 line-clamp-2">
                        Archival museum watercolor on French Arches cotton rag.
                    </p>
                </div>

                <!-- Right Artwork Thumbnail with Heart & "Open ↗" pill -->
                <div class="relative flex-shrink-0">
                    <div class="w-20 h-20 sm:w-28 sm:h-28 rounded-2xl overflow-hidden bg-white shadow-md relative group">
                        <img src="<?= htmlspecialchars($featuredPaintings[2]['primary_image'] ?? 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=300') ?>" alt="Exclusive" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=300';" class="w-full h-full object-cover">
                        <button type="button" onclick="toggleWishlist(<?= $featuredPaintings[2]['id'] ?? 3 ?>, this)" class="absolute top-1.5 right-1.5 sm:top-2 sm:right-2 w-6 h-6 rounded-full bg-white/90 text-red-500 flex items-center justify-center text-xs shadow-xs" title="Wishlist">
                            <svg class="w-3.5 h-3.5 text-red-500 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
                        </button>
                    </div>
                    <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($featuredPaintings[2]['slug'] ?? 'morning-ghats-of-banaras') ?>" class="absolute -bottom-2 -left-2 bg-[#18181B] hover:bg-black text-white text-[10px] font-bold pl-2.5 sm:pl-3 pr-2 py-0.5 sm:py-1 rounded-full flex items-center gap-1 shadow-md transition-colors">
                        <span>Open</span>
                        <span class="w-3.5 h-3.5 sm:w-4 sm:h-4 rounded-full bg-white/20 flex items-center justify-center text-[9px] sm:text-[10px]">↗</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- ============================================================== -->
        <!-- 3. COLUMN 3 / SLIDE 3: Modular 3-Widget Stack & Revenue Engine -->
        <!-- ============================================================== -->
        <div class="w-[88vw] max-w-[360px] sm:w-[420px] sm:max-w-none flex-shrink-0 snap-center lg:w-auto lg:flex-shrink lg:col-span-3 flex flex-col justify-between gap-2.5 sm:gap-4">
            
            <!-- Widget 1: OUR TEAM / CURATORS (Matching Reference Widget 1) -->
            <div class="bg-white rounded-[24px] p-4 sm:p-5 border border-black/[0.05] shadow-xs flex flex-col justify-between h-[135px] sm:h-[165px] hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-stone-400">OUR CURATORS</span>
                    <a href="<?= SITE_URL ?>/about" class="w-5 h-5 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-black transition-colors" title="Learn more">↗</a>
                </div>
                <p class="text-xs text-stone-700 font-medium leading-relaxed">
                    Our panel of master art historians certifies the provenance of every acquisition.
                </p>
                <div class="flex items-center justify-between pt-1">
                    <!-- Curators Avatar Stack -->
                    <div class="flex -space-x-2">
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=60&h=60&fit=crop" class="w-6 h-6 rounded-full border-2 border-white object-cover" alt="Curator">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=60&h=60&fit=crop" class="w-6 h-6 rounded-full border-2 border-white object-cover" alt="Curator">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=60&h=60&fit=crop" class="w-6 h-6 rounded-full border-2 border-white object-cover" alt="Curator">
                        <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=60&h=60&fit=crop" class="w-6 h-6 rounded-full border-2 border-white object-cover" alt="Curator">
                    </div>
                    <!-- Pagination Indicator Dots from Reference Image 2 -->
                    <div class="flex items-center gap-1">
                        <span class="w-3.5 h-1 rounded-full bg-[#18181B]"></span>
                        <span class="w-1 h-1 rounded-full bg-stone-300"></span>
                        <span class="w-1 h-1 rounded-full bg-stone-300"></span>
                    </div>
                </div>
            </div>

            <!-- Widget 2: GET A BONUS (VIP Voucher & Coupon Integration) -->
            <div class="bg-white rounded-[24px] p-4 sm:p-5 border border-black/[0.05] shadow-xs flex flex-col justify-between h-[145px] sm:h-[175px] hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-stone-400">GET A BONUS</span>
                    <a href="<?= SITE_URL ?>/offers" class="w-5 h-5 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-black transition-colors" title="View all offers">↗</a>
                </div>
                <p class="text-xs text-stone-700 font-medium">
                    Discover our salon preview &amp; claim an instant collector acquisition grant.
                </p>
                <div class="flex items-center gap-2 pt-1">
                    <button type="button" onclick="navigator.clipboard.writeText('SPRING50'); showToast('Coupon SPRING50 copied to clipboard!', 'success')" class="flex-1 bg-stone-50 hover:bg-stone-100 border border-stone-200 rounded-full px-3 py-1.5 text-xs font-mono font-bold text-[#18181B] text-center transition-colors flex items-center justify-center gap-1.5" title="Click to Copy">
                        <span>SPRING50</span>
                        <svg class="w-3.5 h-3.5 text-stone-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                    <a href="<?= SITE_URL ?>/offers" class="bg-[#18181B] hover:bg-black text-white text-[11px] font-bold px-3.5 py-1.5 rounded-full transition-colors whitespace-nowrap">
                        Claim
                    </a>
                </div>
            </div>

            <!-- Widget 3: SPONSORED EXHIBITION / AD MONETIZATION ENGINE (Matches Reference Widget 3) -->
            <?php 
            $headerAd = Ad::getSlot('header_billboard');
            $adTitle = !empty($headerAd['title']) ? htmlspecialchars($headerAd['title']) : 'Spring Art Salon 2026';
            $adSubtitle = !empty($headerAd['subtitle']) ? htmlspecialchars($headerAd['subtitle']) : 'National Modern Art Exhibition. VIP vernissage now open.';
            $adUrl = !empty($headerAd['target_url']) ? htmlspecialchars($headerAd['target_url']) : SITE_URL . '/shop?filter=deals';
            $adThumb = !empty($headerAd['banner_desktop']) ? htmlspecialchars($headerAd['banner_desktop']) : 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=120';
            ?>
            <div class="bg-white rounded-[24px] p-4 sm:p-5 border border-black/[0.06] shadow-xs flex flex-col justify-between h-[145px] sm:h-[175px] hover:shadow-md transition-shadow relative overflow-hidden">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-stone-700 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#18181B]"></span>
                        OFFICIAL PARTNER
                    </span>
                    <a href="<?= $adUrl ?>" class="w-5 h-5 rounded-full hover:bg-stone-100 flex items-center justify-center text-stone-400 hover:text-black transition-colors">↗</a>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-[#18181B] leading-snug line-clamp-1"><?= $adTitle ?></h4>
                    <p class="text-[11px] text-stone-500 mt-0.5 line-clamp-2"><?= $adSubtitle ?></p>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <img src="<?= $adThumb ?>" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=120';" class="w-8 h-8 rounded-lg object-cover border border-black/5" alt="Partner">
                    <a href="<?= $adUrl ?>" class="bg-[#18181B] hover:bg-black text-white text-[10px] font-bold px-3 py-1.5 rounded-full transition-colors flex items-center gap-1">
                        <span>Explore Expo</span>
                        <span>↗</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Mobile Slider Pagination Indicator Dots (Visible ONLY on mobile/tablet < lg) -->
    <div class="flex lg:hidden items-center justify-center gap-1.5 pt-3 pb-1" id="bentoSliderDots">
        <button type="button" onclick="scrollBentoToSlide(0)" class="bento-dot w-6 h-1.5 rounded-full bg-[#18181B] transition-all cursor-pointer" aria-label="Go to slide 1"></button>
        <button type="button" onclick="scrollBentoToSlide(1)" class="bento-dot w-2 h-1.5 rounded-full bg-stone-300 transition-all cursor-pointer" aria-label="Go to slide 2"></button>
        <button type="button" onclick="scrollBentoToSlide(2)" class="bento-dot w-2 h-1.5 rounded-full bg-stone-300 transition-all cursor-pointer" aria-label="Go to slide 3"></button>
    </div>
</section>

<script>
let currentBentoSlide = 0;
function changeBentoSlide(dir) {
    const slides = document.querySelectorAll('.bento-slide');
    if (!slides || slides.length === 0) return;
    slides[currentBentoSlide].classList.add('hidden');
    currentBentoSlide = (currentBentoSlide + dir + slides.length) % slides.length;
    slides[currentBentoSlide].classList.remove('hidden');
}

function scrollBentoToSlide(index) {
    const slider = document.getElementById('mobileBentoSlider');
    if (!slider) return;
    const slides = slider.children;
    if (slides[index]) {
        slides[index].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const bentoSlider = document.getElementById('mobileBentoSlider');
    if (bentoSlider) {
        bentoSlider.addEventListener('scroll', function() {
            const dots = document.querySelectorAll('#bentoSliderDots .bento-dot');
            if (!dots.length) return;
            const scrollLeft = bentoSlider.scrollLeft;
            const cardWidth = bentoSlider.children[0] ? bentoSlider.children[0].offsetWidth : bentoSlider.offsetWidth * 0.85;
            const activeIdx = Math.min(dots.length - 1, Math.max(0, Math.round(scrollLeft / cardWidth)));
            dots.forEach((dot, idx) => {
                if (idx === activeIdx) {
                    dot.classList.remove('w-2', 'bg-stone-300');
                    dot.classList.add('w-6', 'bg-[#18181B]');
                } else {
                    dot.classList.remove('w-6', 'bg-[#18181B]');
                    dot.classList.add('w-2', 'bg-stone-300');
                }
            });
        }, { passive: true });
    }
});
</script>

<!-- 2. Horizontal Filter Pills Bar with In-Place Interactive Dropdowns (Shopcart Style) -->
<section class="w-full py-2.5 sm:py-3 relative z-30">
    <!-- Backdrop for mobile popovers -->
    <div id="filterBackdrop" onclick="closeAllFilterMenus()" class="fixed inset-0 bg-black/40 backdrop-blur-xs z-40 hidden sm:hidden transition-opacity"></div>

    <div class="flex items-center gap-2 overflow-x-auto scrollbar-none pb-2 -mx-4 px-4 sm:mx-0 sm:px-0 sm:justify-between sm:overflow-visible">
        
        <!-- Filter Pills Scroll Row (Single Horizontal Line on Mobile) -->
        <div class="flex items-center gap-2 flex-nowrap flex-shrink-0 sm:flex-shrink">
            
            <!-- 0. All Filters Modal Trigger (Prominent on Mobile & Desktop) -->
            <button type="button" onclick="openAllFiltersModal()" class="filter-pill font-bold bg-[#18181B] text-white hover:bg-black border-transparent shadow-xs flex-shrink-0" id="btnAllFilters">
                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                <span>All Filters</span>
            </button>

            <!-- 1. Medium Type Dropdown -->
            <div class="relative filter-dropdown-wrap flex-shrink-0">
                <button type="button" onclick="toggleFilterMenu('mediumDropdown', this)" class="filter-pill" id="btnFilterMedium">
                    <span class="filter-label">Medium Type</span>
                    <svg class="w-3.5 h-3.5 text-gray-400 filter-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="mediumDropdown" class="filter-popover hidden fixed sm:absolute inset-x-4 sm:inset-x-auto bottom-20 sm:bottom-auto left-auto sm:left-0 sm:mt-2 z-50 bg-white rounded-3xl sm:rounded-2xl shadow-2xl sm:shadow-xl border border-stone-200 sm:border-gray-100 p-2 sm:py-2 min-w-[220px] max-w-sm sm:max-w-none mx-auto sm:mx-0 max-h-[65vh] overflow-y-auto">
                    <div class="px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">Art Medium</div>
                    <button type="button" onclick="selectFilter('medium', 'all', 'Medium Type')" class="filter-opt-btn active" data-type="medium" data-val="all">
                        <span>All Mediums</span>
                        <span class="text-[10px] text-gray-400">8</span>
                    </button>
                    <button type="button" onclick="selectFilter('medium', 'oil', 'Oil on Linen')" class="filter-opt-btn" data-type="medium" data-val="oil">
                        <span>Oil on Belgian Linen</span>
                        <span class="text-[10px] text-gray-400">3</span>
                    </button>
                    <button type="button" onclick="selectFilter('medium', 'acrylic', 'Acrylic Impasto')" class="filter-opt-btn" data-type="medium" data-val="acrylic">
                        <span>Heavy Acrylic Impasto</span>
                        <span class="text-[10px] text-gray-400">2</span>
                    </button>
                    <button type="button" onclick="selectFilter('medium', 'watercolor', 'Watercolor')" class="filter-opt-btn" data-type="medium" data-val="watercolor">
                        <span>Fine Watercolor</span>
                        <span class="text-[10px] text-gray-400">1</span>
                    </button>
                    <button type="button" onclick="selectFilter('medium', 'mixed', 'Mixed Media & Gold')" class="filter-opt-btn" data-type="medium" data-val="mixed">
                        <span>Mixed Media &amp; 24K Gold</span>
                        <span class="text-[10px] text-gray-400">1</span>
                    </button>
                    <button type="button" onclick="selectFilter('medium', 'traditional', 'Traditional Heritage')" class="filter-opt-btn" data-type="medium" data-val="traditional">
                        <span>Traditional Mineral Heritage</span>
                        <span class="text-[10px] text-gray-400">1</span>
                    </button>
                </div>
            </div>

            <!-- 2. Price Range Dropdown -->
            <div class="relative filter-dropdown-wrap flex-shrink-0">
                <button type="button" onclick="toggleFilterMenu('priceDropdown', this)" class="filter-pill" id="btnFilterPrice">
                    <span class="filter-label">Price</span>
                    <svg class="w-3.5 h-3.5 text-gray-400 filter-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="priceDropdown" class="filter-popover hidden fixed sm:absolute inset-x-4 sm:inset-x-auto bottom-20 sm:bottom-auto left-auto sm:left-0 sm:mt-2 z-50 bg-white rounded-3xl sm:rounded-2xl shadow-2xl sm:shadow-xl border border-stone-200 sm:border-gray-100 p-2 sm:py-2 min-w-[210px] max-w-sm sm:max-w-none mx-auto sm:mx-0 max-h-[65vh] overflow-y-auto">
                    <div class="px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">Acquisition Price</div>
                    <button type="button" onclick="selectFilter('price', 'all', 'Price')" class="filter-opt-btn active" data-type="price" data-val="all">
                        <span>All Prices</span>
                    </button>
                    <button type="button" onclick="selectFilter('price', 'under_20000', 'Under ₹20,000')" class="filter-opt-btn" data-type="price" data-val="under_20000">
                        <span>Under <?= CURRENCY_SYMBOL ?> 20,000</span>
                        <span class="text-[10px] text-gray-400">2</span>
                    </button>
                    <button type="button" onclick="selectFilter('price', '20000_30000', '₹20k - ₹30k')" class="filter-opt-btn" data-type="price" data-val="20000_30000">
                        <span><?= CURRENCY_SYMBOL ?> 20,000 &ndash; <?= CURRENCY_SYMBOL ?> 30,000</span>
                        <span class="text-[10px] text-gray-400">3</span>
                    </button>
                    <button type="button" onclick="selectFilter('price', '30000_40000', '₹30k - ₹40k')" class="filter-opt-btn" data-type="price" data-val="30000_40000">
                        <span><?= CURRENCY_SYMBOL ?> 30,000 &ndash; <?= CURRENCY_SYMBOL ?> 40,000</span>
                        <span class="text-[10px] text-gray-400">2</span>
                    </button>
                    <button type="button" onclick="selectFilter('price', 'above_40000', 'Above ₹40,000')" class="filter-opt-btn" data-type="price" data-val="above_40000">
                        <span>Above <?= CURRENCY_SYMBOL ?> 40,000</span>
                        <span class="text-[10px] text-gray-400">1</span>
                    </button>
                </div>
            </div>

            <!-- 3. Review / Rating Dropdown -->
            <div class="relative filter-dropdown-wrap flex-shrink-0">
                <button type="button" onclick="toggleFilterMenu('reviewDropdown', this)" class="filter-pill" id="btnFilterReview">
                    <span class="filter-label">Review</span>
                    <svg class="w-3.5 h-3.5 text-gray-400 filter-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="reviewDropdown" class="filter-popover hidden fixed sm:absolute inset-x-4 sm:inset-x-auto bottom-20 sm:bottom-auto left-auto sm:left-0 sm:mt-2 z-50 bg-white rounded-3xl sm:rounded-2xl shadow-2xl sm:shadow-xl border border-stone-200 sm:border-gray-100 p-2 sm:py-2 min-w-[200px] max-w-sm sm:max-w-none mx-auto sm:mx-0 max-h-[65vh] overflow-y-auto">
                    <div class="px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">Collector Rating</div>
                    <button type="button" onclick="selectFilter('review', 'all', 'Review')" class="filter-opt-btn active" data-type="review" data-val="all">
                        <span>All Ratings</span>
                    </button>
                    <button type="button" onclick="selectFilter('review', '5', '5.0 Stars')" class="filter-opt-btn" data-type="review" data-val="5">
                        <span class="flex items-center gap-1.5"><span class="text-emerald-700 font-bold flex items-center gap-0.5"><svg class="w-3 h-3 text-amber-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> 5.0</span> Top Rated Art</span>
                    </button>
                    <button type="button" onclick="selectFilter('review', 'top', 'Top Curated')" class="filter-opt-btn" data-type="review" data-val="top">
                        <span>Most Reviewed Works</span>
                    </button>
                </div>
            </div>

            <!-- 4. Color Palette Dropdown -->
            <div class="relative filter-dropdown-wrap flex-shrink-0">
                <button type="button" onclick="toggleFilterMenu('colorDropdown', this)" class="filter-pill" id="btnFilterColor">
                    <span class="filter-label">Color</span>
                    <svg class="w-3.5 h-3.5 text-gray-400 filter-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="colorDropdown" class="filter-popover hidden fixed sm:absolute inset-x-4 sm:inset-x-auto bottom-20 sm:bottom-auto left-auto sm:left-0 sm:mt-2 z-50 bg-white rounded-3xl sm:rounded-2xl shadow-2xl sm:shadow-xl border border-stone-200 sm:border-gray-100 p-2 sm:py-2 min-w-[220px] max-w-sm sm:max-w-none mx-auto sm:mx-0 max-h-[65vh] overflow-y-auto">
                    <div class="px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">Dominant Palette</div>
                    <button type="button" onclick="selectFilter('color', 'all', 'Color')" class="filter-opt-btn active" data-type="color" data-val="all">
                        <span>All Palettes</span>
                    </button>
                    <button type="button" onclick="selectFilter('color', 'green', 'Emerald & Forest')" class="filter-opt-btn" data-type="color" data-val="green">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-800 border border-gray-200"></span> Emerald &amp; Twilight</span>
                    </button>
                    <button type="button" onclick="selectFilter('color', 'gold', 'Ochre & Gold Leaf')" class="filter-opt-btn" data-type="color" data-val="gold">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-500 border border-gray-200"></span> Ochre &amp; Gold Leaf</span>
                    </button>
                    <button type="button" onclick="selectFilter('color', 'blue', 'Cobalt & Ocean')" class="filter-opt-btn" data-type="color" data-val="blue">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-blue-700 border border-gray-200"></span> Cobalt &amp; Monsoon Blue</span>
                    </button>
                    <button type="button" onclick="selectFilter('color', 'earth', 'Desert & Earth')" class="filter-opt-btn" data-type="color" data-val="earth">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-amber-700 border border-gray-200"></span> Desert Earth &amp; Sienna</span>
                    </button>
                    <button type="button" onclick="selectFilter('color', 'monochrome', 'Monochrome')" class="filter-opt-btn" data-type="color" data-val="monochrome">
                        <span class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-gray-900 border border-gray-200"></span> Charcoal Monochrome</span>
                    </button>
                </div>
            </div>

            <!-- 5. Material / Canvas Dropdown -->
            <div class="relative filter-dropdown-wrap flex-shrink-0">
                <button type="button" onclick="toggleFilterMenu('materialDropdown', this)" class="filter-pill" id="btnFilterMaterial">
                    <span class="filter-label">Material</span>
                    <svg class="w-3.5 h-3.5 text-gray-400 filter-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="materialDropdown" class="filter-popover hidden fixed sm:absolute inset-x-4 sm:inset-x-auto bottom-20 sm:bottom-auto left-auto sm:left-0 sm:mt-2 z-50 bg-white rounded-3xl sm:rounded-2xl shadow-2xl sm:shadow-xl border border-stone-200 sm:border-gray-100 p-2 sm:py-2 min-w-[230px] max-w-sm sm:max-w-none mx-auto sm:mx-0 max-h-[65vh] overflow-y-auto">
                    <div class="px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">Canvas &amp; Substrate</div>
                    <button type="button" onclick="selectFilter('material', 'all', 'Material')" class="filter-opt-btn active" data-type="material" data-val="all">
                        <span>All Materials</span>
                    </button>
                    <button type="button" onclick="selectFilter('material', 'linen', 'Belgian Linen')" class="filter-opt-btn" data-type="material" data-val="linen">
                        <span>Belgian Linen Canvas</span>
                        <span class="text-[10px] text-gray-400">3</span>
                    </button>
                    <button type="button" onclick="selectFilter('material', 'cotton', 'Cotton Canvas')" class="filter-opt-btn" data-type="material" data-val="cotton">
                        <span>Cotton Duck Canvas</span>
                        <span class="text-[10px] text-gray-400">2</span>
                    </button>
                    <button type="button" onclick="selectFilter('material', 'paper', 'Arches Paper')" class="filter-opt-btn" data-type="material" data-val="paper">
                        <span>French Arches 300gsm Paper</span>
                        <span class="text-[10px] text-gray-400">1</span>
                    </button>
                    <button type="button" onclick="selectFilter('material', 'gold_leaf', '24K Gold Board')" class="filter-opt-btn" data-type="material" data-val="gold_leaf">
                        <span>24K Genuine Gold Leaf Board</span>
                        <span class="text-[10px] text-gray-400">1</span>
                    </button>
                </div>
            </div>

            <!-- 6. Offer Dropdown -->
            <div class="relative filter-dropdown-wrap flex-shrink-0">
                <button type="button" onclick="toggleFilterMenu('offerDropdown', this)" class="filter-pill" id="btnFilterOffer">
                    <span class="filter-label">Offer</span>
                    <svg class="w-3.5 h-3.5 text-gray-400 filter-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div id="offerDropdown" class="filter-popover hidden fixed sm:absolute inset-x-4 sm:inset-x-auto bottom-20 sm:bottom-auto left-auto sm:left-0 sm:mt-2 z-50 bg-white rounded-3xl sm:rounded-2xl shadow-2xl sm:shadow-xl border border-stone-200 sm:border-gray-100 p-2 sm:py-2 min-w-[210px] max-w-sm sm:max-w-none mx-auto sm:mx-0 max-h-[65vh] overflow-y-auto">
                    <div class="px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">Promotions &amp; Deals</div>
                    <button type="button" onclick="selectFilter('offer', 'all', 'Offer')" class="filter-opt-btn active" data-type="offer" data-val="all">
                        <span>All Paintings</span>
                    </button>
                    <button type="button" onclick="selectFilter('offer', 'special_price', 'Special Price')" class="filter-opt-btn" data-type="offer" data-val="special_price">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Discounted Gallery Deals</span>
                        <span class="text-[10px] text-gray-400">4</span>
                    </button>
                    <button type="button" onclick="selectFilter('offer', 'has_coupon', 'Coupon Eligible')" class="filter-opt-btn" data-type="offer" data-val="has_coupon">
                        <span>Coupon Voucher Eligible</span>
                        <span class="text-[10px] text-gray-400">All</span>
                    </button>
                </div>
            </div>

            <!-- Reset Active Filters Pill (Revealed when any filter is active) -->
            <button type="button" onclick="resetAllFilters()" id="clearAllFiltersBtn" class="hidden filter-pill border-red-200 text-red-600 bg-red-50 hover:bg-red-100 hover:border-red-300 font-bold transition-colors flex-shrink-0">
                <span>Reset</span>
                <span class="text-xs">&times;</span>
            </button>
        </div>

        <!-- Right: Sort by Dropdown -->
        <div class="relative filter-dropdown-wrap flex-shrink-0">
            <button type="button" onclick="toggleFilterMenu('sortDropdown', this)" class="filter-pill bg-gray-50 border-gray-200" id="btnFilterSort">
                <span class="text-gray-500 font-normal">Sort:</span>
                <span class="font-bold sort-label">Popular</span>
                <svg class="w-3.5 h-3.5 text-gray-400 filter-chevron" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>
            <div id="sortDropdown" class="filter-popover hidden fixed sm:absolute inset-x-4 sm:inset-x-auto bottom-20 sm:bottom-auto right-auto sm:right-0 sm:mt-2 z-50 bg-white rounded-3xl sm:rounded-2xl shadow-2xl sm:shadow-xl border border-stone-200 sm:border-gray-100 p-2 sm:py-2 min-w-[210px] max-w-sm sm:max-w-none mx-auto sm:mx-0 max-h-[60vh] overflow-y-auto">
                <div class="px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">Sort Options</div>
                <button type="button" onclick="selectSort('popular', 'Popular')" class="filter-opt-btn active" data-type="sort" data-val="popular">
                    <span>Popular / Best Sellers</span>
                </button>
                <button type="button" onclick="selectSort('price_low', 'Price: Low to High')" class="filter-opt-btn" data-type="sort" data-val="price_low">
                    <span>Price: Low to High</span>
                </button>
                <button type="button" onclick="selectSort('price_high', 'Price: High to Low')" class="filter-opt-btn" data-type="sort" data-val="price_high">
                    <span>Price: High to Low</span>
                </button>
                <button type="button" onclick="selectSort('rating', 'Highest Rated')" class="filter-opt-btn" data-type="sort" data-val="rating">
                    <span>Highest Rated</span>
                </button>
                <button type="button" onclick="selectSort('name', 'Name: A to Z')" class="filter-opt-btn" data-type="sort" data-val="name">
                    <span>Alphabetical (A&ndash;Z)</span>
                </button>
            </div>
        </div>
    </div>
</section>
                <div class="px-3.5 py-1.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">Sort Options</div>
                <button type="button" onclick="selectSort('popular', 'Popular')" class="filter-opt-btn active" data-type="sort" data-val="popular">
                    <span>Popular / Best Sellers</span>
                </button>
                <button type="button" onclick="selectSort('price_low', 'Price: Low to High')" class="filter-opt-btn" data-type="sort" data-val="price_low">
                    <span>Price: Low to High</span>
                </button>
                <button type="button" onclick="selectSort('price_high', 'Price: High to Low')" class="filter-opt-btn" data-type="sort" data-val="price_high">
                    <span>Price: High to Low</span>
                </button>
                <button type="button" onclick="selectSort('rating', 'Highest Rated')" class="filter-opt-btn" data-type="sort" data-val="rating">
                    <span>Highest Rated</span>
                </button>
                <button type="button" onclick="selectSort('name', 'Name: A to Z')" class="filter-opt-btn" data-type="sort" data-val="name">
                    <span>Alphabetical (A&ndash;Z)</span>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- 3. Section Title with Live Count Feedback -->
<section class="w-full pt-4 pb-2" id="catalogSection">
    <div class="flex items-center justify-between">
        <h2 id="catalogHeading" class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">Paintings For You!</h2>
        <span id="filteredCountBadge" class="text-xs font-semibold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Showing 8 artworks</span>
    </div>
</section>

<!-- 4. Clean Product Grid (4 Columns Desktop, 2 Mobile - Shopcart Style) -->
<section class="w-full py-4">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6" id="paintingsGrid">
        <?php if (!empty($featuredProducts)): ?>
            <?php foreach ($featuredProducts as $index => $p): 
                $medLower = strtolower($p['medium'] ?? '');
                $titleLower = strtolower($p['title'] ?? '');

                $materialType = 'canvas';
                if (str_contains($medLower, 'linen')) $materialType = 'linen';
                elseif (str_contains($medLower, 'paper') || str_contains($medLower, 'arches')) $materialType = 'paper';
                elseif (str_contains($medLower, 'cotton')) $materialType = 'cotton';
                elseif (str_contains($medLower, 'gold leaf') || str_contains($titleLower, 'gold')) $materialType = 'gold_leaf';

                $colorPalette = 'earth';
                if (str_contains($titleLower, 'gold') || str_contains($titleLower, 'ochre')) $colorPalette = 'gold';
                elseif (str_contains($titleLower, 'cobalt') || str_contains($titleLower, 'blue') || str_contains($titleLower, 'monsoon') || str_contains($titleLower, 'vrindavan')) $colorPalette = 'blue';
                elseif (str_contains($titleLower, 'twilight') || str_contains($titleLower, 'valley') || str_contains($titleLower, 'lotus')) $colorPalette = 'green';
                elseif (str_contains($titleLower, 'charcoal') || str_contains($medLower, 'charcoal')) $colorPalette = 'monochrome';
                elseif (str_contains($titleLower, 'thar') || str_contains($titleLower, 'desert') || str_contains($titleLower, 'banaras')) $colorPalette = 'earth';

                $mediumCategory = 'oil';
                if (str_contains($medLower, 'acrylic')) $mediumCategory = 'acrylic';
                elseif (str_contains($medLower, 'watercolor')) $mediumCategory = 'watercolor';
                elseif (str_contains($medLower, 'mixed media')) $mediumCategory = 'mixed';
                elseif (str_contains($medLower, 'heritage') || str_contains($medLower, 'mineral')) $mediumCategory = 'traditional';
                elseif (str_contains($medLower, 'oil')) $mediumCategory = 'oil';

                $effectivePrice = (float)($p['discount_price'] ?? $p['price']);
                $hasOffer = !empty($p['discount_price']) ? '1' : '0';
                $reviewsCount = 100 + ($p['id'] * 7);
            ?>
                
                <!-- Product Card Grid Item with Rich Filter Data Attributes -->
                <div class="product-grid-item col-span-1"
                    data-id="<?= $p['id'] ?>"
                    data-title="<?= htmlspecialchars($titleLower) ?>"
                    data-medium="<?= htmlspecialchars($medLower) ?>"
                    data-medium-cat="<?= $mediumCategory ?>"
                    data-material="<?= $materialType ?>"
                    data-color="<?= $colorPalette ?>"
                    data-price="<?= $effectivePrice ?>"
                    data-raw-price="<?= (float)$p['price'] ?>"
                    data-rating="5"
                    data-reviews="<?= $reviewsCount ?>"
                    data-has-offer="<?= $hasOffer ?>"
                    data-original-order="<?= $index ?>">

                    <div class="product-card group h-full">
                        <!-- Image Container with Heart Button -->
                        <div class="product-img-wrap">
                            <!-- Wishlist Heart Button -->
                            <button onclick="toggleWishlist(<?= $p['id'] ?>, this)" class="wishlist-heart-btn" title="Add to Wishlist">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>

                            <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($p['slug']) ?>" class="w-full h-full flex items-center justify-center">
                                <img src="<?= htmlspecialchars($p['primary_image']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=900&q=80';" class="max-h-full max-w-full object-contain transform group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            </a>
                        </div>

                        <!-- Meta Details -->
                        <div class="pt-3 pb-1 flex-1 flex flex-col justify-between">
                            <div>
                                <!-- Row 1: Title (left) & Price (right) -->
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-1">
                                    <h3 class="font-bold text-xs sm:text-sm text-[#111827] group-hover:text-black transition-colors line-clamp-1 min-w-0 flex-1">
                                        <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($p['slug']) ?>">
                                            <?= htmlspecialchars($p['title']) ?>
                                        </a>
                                    </h3>
                                    <div class="sm:text-right flex-shrink-0">
                                        <span class="font-extrabold text-xs sm:text-sm md:text-base text-[#111827] whitespace-nowrap block">
                                            <?= Validator::formatPrice($effectivePrice) ?>
                                        </span>
                                        <?php if (!empty($p['discount_price'])): ?>
                                            <span class="text-[10px] text-gray-400 line-through block">
                                                <?= Validator::formatPrice((float)$p['price']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Row 2: Subtitle Description -->
                                <p class="text-[11px] sm:text-xs text-gray-500 mt-1 line-clamp-1">
                                    <?= htmlspecialchars($p['medium']) ?> &bull; <?= htmlspecialchars($p['dimensions']) ?>
                                </p>

                                <!-- Row 3: Star Rating -->
                                <div class="mt-2 flex items-center gap-1.5">
                                    <div class="flex items-center text-amber-500 gap-0.5">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    </div>
                                    <span class="text-[11px] sm:text-xs font-bold text-stone-600">(<?= $reviewsCount ?>)</span>
                                </div>
                            </div>

                            <!-- Row 4: Clean Pill Buttons (Add to Cart & Buy Now) -->
                            <div class="mt-3 sm:mt-4 grid grid-cols-2 gap-1.5 sm:gap-2">
                                <button type="button" onclick="quickAddToCart(<?= $p['id'] ?>)" class="w-full py-1.5 sm:py-2 px-1 sm:px-2 border border-[#18181B] text-[#18181B] hover:bg-stone-100 rounded-full text-[10px] sm:text-xs font-bold transition-all flex items-center justify-center text-center cursor-pointer whitespace-nowrap leading-tight">
                                    <span class="sm:hidden">+ Cart</span>
                                    <span class="hidden sm:inline">Add to Cart</span>
                                </button>
                                <button type="button" onclick="quickBuyNow(<?= $p['id'] ?>)" class="w-full py-1.5 sm:py-2 px-1 sm:px-2 bg-[#18181B] hover:bg-black text-white rounded-full text-[10px] sm:text-xs font-bold transition-all shadow-xs flex items-center justify-center text-center cursor-pointer whitespace-nowrap leading-tight">
                                    <span>Buy Now</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- In-Grid Poster Ad Slot (Inserted seamlessly at index 2) -->
                <?php if ($index === 2): ?>
                    <div class="col-span-1 in-grid-ad-container" id="inGridAdPoster">
                        <?= Ad::render('in_grid_poster') ?>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Zero Results Empty State -->
        <div id="noPaintingsFound" class="hidden col-span-2 md:col-span-3 lg:col-span-4 bg-gray-50 border border-gray-200 rounded-3xl p-10 text-center my-4">
            <div class="w-12 h-12 rounded-2xl bg-white border border-gray-200 text-gray-400 mx-auto flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </div>
            <h3 class="font-bold text-gray-800 text-base mb-1">No Paintings Match Your Filter</h3>
            <p class="text-xs text-gray-500 mb-5 max-w-sm mx-auto">Try clearing one or more filters to view our full collection of original canvases.</p>
            <button type="button" onclick="resetAllFilters()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#18181B] hover:bg-black text-white text-xs font-bold rounded-full transition-colors cursor-pointer shadow-xs">
                <span>Reset All Filters</span>
            </button>
        </div>
    </div>
</section>

<!-- Filters modal is loaded globally via footer.php -->

<!-- ======================================================== -->
<!-- Instant Client-Side Filter Engine Script                 -->
<!-- ======================================================== -->
<script>
const activeFilters = {
    medium: 'all',
    price: 'all',
    review: 'all',
    color: 'all',
    material: 'all',
    offer: 'all',
    sort: 'popular'
};

const defaultLabels = {
    medium: 'Medium Type',
    price: 'Price',
    review: 'Review',
    color: 'Color',
    material: 'Material',
    offer: 'Offer',
    sort: 'Popular'
};

function toggleFilterMenu(dropdownId, buttonEl) {
    const targetMenu = document.getElementById(dropdownId);
    if (!targetMenu) return;
    const isCurrentlyOpen = !targetMenu.classList.contains('hidden');
    const backdrop = document.getElementById('filterBackdrop');

    // Close all open popovers first
    closeAllFilterMenus();

    if (!isCurrentlyOpen) {
        targetMenu.classList.remove('hidden');
        buttonEl.classList.add('open');
        if (backdrop && window.innerWidth < 640) {
            backdrop.classList.remove('hidden');
        }
    }
}

function closeAllFilterMenus() {
    document.querySelectorAll('.filter-popover').forEach(menu => {
        menu.classList.add('hidden');
    });
    document.querySelectorAll('.filter-pill').forEach(btn => {
        btn.classList.remove('open');
    });
    const backdrop = document.getElementById('filterBackdrop');
    if (backdrop) {
        backdrop.classList.add('hidden');
    }
}

// Global click outside to dismiss menus
document.addEventListener('click', function(e) {
    if (!e.target.closest('.filter-dropdown-wrap') && !e.target.closest('#filterBackdrop')) {
        closeAllFilterMenus();
    }
});

function selectFilter(filterType, filterVal, displayLabel) {
    activeFilters[filterType] = filterVal;

    // Update active state in that dropdown
    const dropdown = document.getElementById(filterType + 'Dropdown');
    if (dropdown) {
        dropdown.querySelectorAll('.filter-opt-btn').forEach(btn => {
            if (btn.getAttribute('data-val') === filterVal) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    // Update button text & active border
    const pillBtn = document.getElementById('btnFilter' + capitalizeFirst(filterType));
    if (pillBtn) {
        const labelSpan = pillBtn.querySelector('.filter-label');
        if (filterVal === 'all') {
            labelSpan.textContent = defaultLabels[filterType];
            pillBtn.classList.remove('active');
        } else {
            labelSpan.textContent = displayLabel;
            pillBtn.classList.add('active');
        }
    }

    closeAllFilterMenus();
    applyAllFilters();
}

function selectSort(sortVal, displayLabel) {
    activeFilters.sort = sortVal;

    const dropdown = document.getElementById('sortDropdown');
    if (dropdown) {
        dropdown.querySelectorAll('.filter-opt-btn').forEach(btn => {
            if (btn.getAttribute('data-val') === sortVal) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    const sortBtn = document.getElementById('btnFilterSort');
    if (sortBtn) {
        const sortLabel = sortBtn.querySelector('.sort-label');
        if (sortLabel) sortLabel.textContent = displayLabel;
    }

    closeAllFilterMenus();
    applyAllFilters();
}

function applyAllFilters() {
    const grid = document.getElementById('paintingsGrid');
    if (!grid) return;

    const items = Array.from(grid.querySelectorAll('.product-grid-item'));
    let matchCount = 0;

    items.forEach(item => {
        const medCat = item.getAttribute('data-medium-cat') || '';
        const price = parseFloat(item.getAttribute('data-price') || '0');
        const color = item.getAttribute('data-color') || '';
        const material = item.getAttribute('data-material') || '';
        const hasOffer = item.getAttribute('data-has-offer') || '0';
        const reviews = parseInt(item.getAttribute('data-reviews') || '0');

        let matches = true;

        // 1. Medium Match
        if (activeFilters.medium !== 'all' && medCat !== activeFilters.medium) {
            matches = false;
        }

        // 2. Price Match
        if (matches && activeFilters.price !== 'all') {
            if (activeFilters.price === 'under_20000' && price >= 20000) matches = false;
            else if (activeFilters.price === '20000_30000' && (price < 20000 || price > 30000)) matches = false;
            else if (activeFilters.price === '30000_40000' && (price < 30000 || price > 40000)) matches = false;
            else if (activeFilters.price === 'above_40000' && price <= 40000) matches = false;
        }

        // 3. Review Match
        if (matches && activeFilters.review !== 'all') {
            if (activeFilters.review === '5' && reviews < 100) matches = false;
            else if (activeFilters.review === 'top' && reviews < 130) matches = false;
        }

        // 4. Color Match
        if (matches && activeFilters.color !== 'all' && color !== activeFilters.color) {
            matches = false;
        }

        // 5. Material Match
        if (matches && activeFilters.material !== 'all' && material !== activeFilters.material) {
            matches = false;
        }

        // 6. Offer Match
        if (matches && activeFilters.offer !== 'all') {
            if (activeFilters.offer === 'special_price' && hasOffer !== '1') matches = false;
        }

        if (matches) {
            item.style.display = '';
            matchCount++;
        } else {
            item.style.display = 'none';
        }
    });

    // Handle Sort Order on visible items
    if (activeFilters.sort !== 'popular') {
        items.sort((a, b) => {
            if (activeFilters.sort === 'price_low') {
                return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
            } else if (activeFilters.sort === 'price_high') {
                return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
            } else if (activeFilters.sort === 'rating') {
                return parseInt(b.getAttribute('data-reviews')) - parseInt(a.getAttribute('data-reviews'));
            } else if (activeFilters.sort === 'name') {
                return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'));
            }
            return 0;
        });

        // Re-append in sorted order (before the empty card)
        const emptyCard = document.getElementById('noPaintingsFound');
        items.forEach(el => grid.insertBefore(el, emptyCard));
    } else {
        // Return to natural order
        items.sort((a, b) => {
            return parseInt(a.getAttribute('data-original-order')) - parseInt(b.getAttribute('data-original-order'));
        });
        const emptyCard = document.getElementById('noPaintingsFound');
        items.forEach(el => grid.insertBefore(el, emptyCard));
    }

    // Toggle In-Grid Ad visibility if few results
    const inGridAd = document.getElementById('inGridAdPoster');
    if (inGridAd) {
        inGridAd.style.display = (matchCount >= 3) ? '' : 'none';
    }

    // Update Counter Badge
    const counterBadge = document.getElementById('filteredCountBadge');
    if (counterBadge) {
        counterBadge.textContent = matchCount === items.length 
            ? 'Showing ' + matchCount + ' artworks' 
            : 'Showing ' + matchCount + ' of ' + items.length + ' matching';
    }

    // Toggle Empty State Card
    const emptyState = document.getElementById('noPaintingsFound');
    if (emptyState) {
        if (matchCount === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }

    // Toggle Reset Filters button
    const clearBtn = document.getElementById('clearAllFiltersBtn');
    const isAnyActive = Object.keys(activeFilters).some(k => k !== 'sort' && activeFilters[k] !== 'all');
    if (clearBtn) {
        if (isAnyActive) {
            clearBtn.classList.remove('hidden');
        } else {
            clearBtn.classList.add('hidden');
        }
    }
}

function resetAllFilters() {
    Object.keys(activeFilters).forEach(k => {
        if (k !== 'sort') {
            activeFilters[k] = 'all';
            const pillBtn = document.getElementById('btnFilter' + capitalizeFirst(k));
            if (pillBtn) {
                const labelSpan = pillBtn.querySelector('.filter-label');
                if (labelSpan) labelSpan.textContent = defaultLabels[k];
                pillBtn.classList.remove('active');
            }
            const dropdown = document.getElementById(k + 'Dropdown');
            if (dropdown) {
                dropdown.querySelectorAll('.filter-opt-btn').forEach(btn => {
                    if (btn.getAttribute('data-val') === 'all') btn.classList.add('active');
                    else btn.classList.remove('active');
                });
            }
        }
    });

    closeAllFilterMenus();
    applyAllFilters();
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}
</script>

<!-- 4b. Active Offers & Collector Vouchers Section -->
<?php if (!empty($activeCoupons)): ?>
<section class="w-full py-6" id="collectorOffersSection">
    <div class="bg-[#18181B] rounded-[28px] sm:rounded-[34px] p-6 sm:p-8 text-white relative overflow-hidden shadow-lg border border-white/10">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/10 rounded-full text-stone-200 text-xs font-bold mb-2 border border-white/15">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                    <span>Collector Privilege Offers</span>
                </div>
                <h2 class="text-xl sm:text-2xl font-bold font-heading text-white">Save Up to 50% With Verified Coupon Codes</h2>
                <p class="text-xs text-stone-300 mt-1 max-w-xl">Apply promotional vouchers during checkout for instant price reductions on original fine art.</p>
            </div>
            <a href="<?= SITE_URL ?>/offers" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-[#18181B] hover:bg-stone-100 rounded-full text-xs font-bold transition-all shadow-xs flex-shrink-0 self-start md:self-auto">
                <span>View All <?= count($activeCoupons) ?> Offers</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <!-- Horizontal Voucher Cards Preview -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6 relative z-10">
            <?php foreach (array_slice($activeCoupons, 0, 3) as $c): 
                $discLabel = ($c['discount_type'] === 'percentage') ? ((float)$c['discount_value'] . '% OFF') : ('Flat ' . CURRENCY_SYMBOL . ' ' . number_format((float)$c['discount_value']) . ' OFF');
            ?>
                <div class="bg-white/10 backdrop-blur-xs border border-white/10 rounded-2xl p-4 flex items-center justify-between gap-3 hover:bg-white/15 transition-all">
                    <div class="overflow-hidden">
                        <span class="text-[11px] font-extrabold text-amber-400 uppercase tracking-wider block"><?= $discLabel ?></span>
                        <h4 class="text-xs font-bold text-white mt-0.5 truncate"><?= htmlspecialchars($c['title'] ?? $c['code']) ?></h4>
                        <span class="text-[10px] text-stone-400 block mt-0.5">
                            <?= ((float)$c['min_order_amount'] > 0) ? ('Min. spend ' . CURRENCY_SYMBOL . ' ' . number_format((float)$c['min_order_amount'])) : 'No minimum spend' ?>
                        </span>
                    </div>
                    <button type="button" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($c['code']) ?>'); this.innerText = 'Copied!'; setTimeout(() => this.innerText = '<?= htmlspecialchars($c['code']) ?>', 2000);" title="Click to copy code" class="px-3 py-1.5 bg-white text-[#18181B] hover:bg-stone-100 rounded-xl text-xs font-mono font-bold shadow-xs transition-colors flex items-center gap-1 flex-shrink-0 cursor-pointer">
                        <span><?= htmlspecialchars($c['code']) ?></span>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 5. Popular Categories Section -->
<section class="w-full py-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl sm:text-3xl font-extrabold text-[#111827] tracking-tight">Popular Categories</h2>
        <a href="<?= SITE_URL ?>/shop" class="text-xs sm:text-sm font-bold text-[#18181B] hover:text-stone-600 hover:underline">See All Categories &rarr;</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php
        $categoryThumbnails = [
            'oil-paintings' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=200&q=80',
            'acrylic-paintings' => 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=200&q=80',
            'acrylic-art' => 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=200&q=80',
            'watercolor-paintings' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=200&q=80',
            'modern-abstract' => 'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?w=200&q=80',
            'landscapes-nature' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=200&q=80',
            'heritage-spiritual' => 'https://images.unsplash.com/photo-1578925518470-4def7a0f08bb?w=200&q=80',
            'traditional-heritage' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?w=200&q=80',
            'charcoal-and-ink' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=200&q=80',
        ];
        ?>
        <?php foreach ($categories as $cat): 
            $pCount = (int)($cat['product_count'] ?? 0);
            if ($pCount <= 0) continue;
        ?>
            <?php 
                $img = !empty($cat['image_url']) && str_starts_with($cat['image_url'], 'http') 
                    ? $cat['image_url'] 
                    : ($categoryThumbnails[$cat['slug']] ?? 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=200&q=80');
            ?>
            <a href="<?= SITE_URL ?>/shop?category=<?= htmlspecialchars($cat['slug']) ?>" class="category-box-item group">
                <div class="w-14 h-14 rounded-xl bg-stone-100 p-1 overflow-hidden shadow-xs flex-shrink-0 flex items-center justify-center">
                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($cat['name']) ?>" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=200&q=80';" class="w-full h-full object-cover rounded-lg group-hover:scale-110 transition-transform duration-300">
                </div>
                <div>
                    <h3 class="font-bold text-sm text-[#111827] group-hover:text-black transition-colors">
                        <?= htmlspecialchars($cat['name']) ?>
                    </h3>
                    <p class="text-xs text-stone-500 font-medium mt-0.5">
                        <?= $pCount ?> Items Available
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<!-- 6. Weekly Curated Art Stories -->
<?php if (!empty($latestBlogs)): ?>
<section class="w-full py-8 border-t border-black/[0.06]">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-extrabold text-[#111827]">Art Stories &amp; Curator Insights</h2>
            <p class="text-xs text-stone-500 mt-1">Collecting tips, master artist techniques, and salon exhibition reviews</p>
        </div>
        <a href="<?= SITE_URL ?>/blog" class="text-xs font-bold text-[#18181B] hover:text-stone-600 hover:underline">Read All &rarr;</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php foreach ($latestBlogs as $blog): ?>
            <a href="<?= SITE_URL ?>/blog/<?= htmlspecialchars($blog['slug']) ?>" class="group bg-white rounded-2xl overflow-hidden border border-black/[0.06] hover:shadow-md transition-all">
                <div class="aspect-[16/10] bg-[#F5F6F6] overflow-hidden">
                    <img src="<?= htmlspecialchars($blog['featured_image'] ?? ASSETS_URL . '/images/banner.jpg') ?>" alt="<?= htmlspecialchars($blog['title']) ?>" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=600&q=80';" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                <div class="p-5">
                    <span class="text-[10px] uppercase font-bold text-stone-500 tracking-wider">Fine Art Journal</span>
                    <h3 class="font-bold text-base text-[#111827] group-hover:text-black transition-colors mt-1 line-clamp-2">
                        <?= htmlspecialchars($blog['title']) ?>
                    </h3>
                    <p class="text-xs text-stone-500 mt-2 line-clamp-2">
                        <?= htmlspecialchars($blog['excerpt'] ?? '') ?>
                    </p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- 7. Footer Billboard Banner Ad Slot -->
<div class="w-full py-4">
    <?= Ad::render('footer_banner') ?>
</div>
