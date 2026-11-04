<?php
/**
 * JayArt Offers & Coupon Vouchers View
 * High-converting, Shopcart-inspired clean design
 */

$cartCount = Cart::getCount();
$cartSubtotal = Cart::getSubtotal();
?>

<div class="bg-[#F8FAFC] py-8 sm:py-12 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-xs text-stone-500 mb-4">
            <a href="<?= SITE_URL ?>" class="hover:text-black transition-colors">Home</a>
            <span>/</span>
            <span class="text-stone-900 font-bold">Offers &amp; Coupons</span>
        </nav>

        <!-- Header Hero Strip -->
        <div class="bg-[#18181B] rounded-[28px] sm:rounded-[34px] p-6 sm:p-10 text-white shadow-lg relative overflow-hidden border border-white/10">
            <!-- Subtle background accent rings -->
            <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-white/5 pointer-events-none"></div>
            <div class="absolute right-32 -top-16 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-xs rounded-full text-stone-300 text-xs font-bold mb-3 border border-white/10">
                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>Verified Active Vouchers (<?= count($offers) ?> Available)</span>
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold font-heading tracking-tight text-white mb-3">
                    Exclusive Art Collector Offers &amp; Vouchers
                </h1>
                <p class="text-stone-300 text-xs sm:text-sm leading-relaxed mb-6 font-normal">
                    Acquire original museum-grade oil paintings, acrylics, and framed masterpieces with authentic gallery discounts. Click any code below to copy and apply directly at checkout.
                </p>

                <!-- Quick info badges -->
                <div class="flex flex-wrap items-center gap-3 text-xs text-white/90">
                    <div class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-xl border border-white/10">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>100% Authentic Originals</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-xl border border-white/10">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <span>Free Insured Art Packaging</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-xl border border-white/10">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>Limited Time Curation</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Offers Content Area -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 sm:py-14">

    <!-- Active Offers Vouchers Grid -->
    <div class="mb-14">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-8">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold font-heading text-gray-900">Active Coupon Vouchers</h2>
                <p class="text-xs text-gray-500 mt-1">Copy code and apply on cart or checkout to redeem your savings</p>
            </div>
            
            <?php if ($cartCount > 0): ?>
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-stone-100 border border-stone-200 text-[#18181B] rounded-full text-xs font-bold">
                    <span>You have <?= $cartCount ?> painting(s) in your cart (<?= CURRENCY_SYMBOL ?> <?= number_format($cartSubtotal, 2) ?>)</span>
                    <a href="<?= SITE_URL ?>/cart" class="underline hover:text-stone-600 ml-1">View Cart &rarr;</a>
                </div>
            <?php endif; ?>
        </div>

        <?php if (empty($offers)): ?>
            <div class="bg-white border border-gray-200 rounded-3xl p-12 text-center max-w-lg mx-auto">
                <div class="w-12 h-12 rounded-2xl bg-gray-50 text-gray-400 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
                <h3 class="font-bold text-gray-800 text-base mb-1">No Active Offers Right Now</h3>
                <p class="text-xs text-gray-500 mb-5">New seasonal promotional vouchers are being curated. Please check back shortly.</p>
                <a href="<?= SITE_URL ?>/shop" class="inline-block px-5 py-2.5 bg-[#18181B] text-white text-xs font-bold rounded-xl hover:bg-black transition-colors">
                    Explore Gallery Paintings
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($offers as $offer): 
                    $isPercentage = ($offer['discount_type'] === 'percentage');
                    $discountLabel = $isPercentage ? ((float)$offer['discount_value'] . '% OFF') : ('FLAT ' . CURRENCY_SYMBOL . ' ' . number_format((float)$offer['discount_value']) . ' OFF');
                    $minSpend = (float)($offer['min_order_amount'] ?? 0);
                    $expiresAt = !empty($offer['expires_at']) ? date('d M Y', strtotime($offer['expires_at'])) : null;
                ?>
                    <!-- Single Voucher Card with Ticket Notch Effect -->
                    <div class="bg-white border border-gray-200 hover:border-stone-400 rounded-3xl p-6 sm:p-7 shadow-xs hover:shadow-md transition-all flex flex-col justify-between relative group">
                        
                        <!-- Top Row: Badge & Type -->
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <span class="inline-block px-3 py-1 bg-amber-50 border border-amber-200 text-amber-900 font-heading font-extrabold text-sm sm:text-base rounded-xl">
                                    <?= $discountLabel ?>
                                </span>
                                <h3 class="font-heading font-bold text-base sm:text-lg text-gray-900 mt-2.5">
                                    <?= htmlspecialchars($offer['title'] ?? $offer['code']) ?>
                                </h3>
                            </div>
                            <div class="w-10 h-10 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center text-gray-500 group-hover:bg-[#18181B] group-hover:text-white transition-colors flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                            </div>
                        </div>

                        <!-- Description & Constraints -->
                        <p class="text-xs text-gray-600 leading-relaxed mb-5">
                            <?= htmlspecialchars($offer['description'] ?? 'Valid on original fine art collection paintings.') ?>
                        </p>

                        <!-- Constraint Badges -->
                        <div class="flex flex-wrap items-center gap-2 mb-6 text-[11px] font-semibold text-gray-500">
                            <span class="inline-flex items-center gap-1 bg-gray-100 px-2.5 py-1 rounded-lg">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                <span><?= $minSpend > 0 ? ('Min. order ' . CURRENCY_SYMBOL . ' ' . number_format($minSpend)) : 'No minimum order' ?></span>
                            </span>
                            <?php if ($expiresAt): ?>
                                <span class="inline-flex items-center gap-1 bg-gray-100 px-2.5 py-1 rounded-lg">
                                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>Valid till <?= $expiresAt ?></span>
                                </span>
                            <?php endif; ?>
                            <span class="inline-flex items-center gap-1 bg-stone-100 text-stone-700 px-2.5 py-1 rounded-lg">
                                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Verified Active</span>
                            </span>
                        </div>

                        <!-- Dashed Divider -->
                        <div class="border-t border-dashed border-gray-200 pt-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <!-- Code Display Pill with 1-Click Copy -->
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-2xl p-1.5 pl-3.5 justify-between sm:justify-start gap-3">
                                <div class="font-mono font-extrabold text-[#18181B] text-sm tracking-wider">
                                    <?= htmlspecialchars($offer['code']) ?>
                                </div>
                                <button type="button" 
                                    onclick="copyVoucherCode('<?= htmlspecialchars($offer['code']) ?>', this)"
                                    class="px-3.5 py-1.5 bg-white hover:bg-stone-100 border border-gray-200 text-gray-800 hover:text-black text-xs font-bold rounded-xl shadow-2xs transition-all flex items-center gap-1.5 cursor-pointer">
                                    <svg class="w-3.5 h-3.5 copy-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    <span class="btn-text">Copy Code</span>
                                </button>
                            </div>

                            <!-- Shop Now Action -->
                            <a href="<?= SITE_URL ?>/shop" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 bg-[#18181B] hover:bg-black text-white text-xs font-bold rounded-2xl shadow-xs transition-colors">
                                <span>Shop Artworks</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- How to Redeem Steps -->
    <div class="bg-white border border-stone-200/80 rounded-3xl p-8 sm:p-10 shadow-xs mb-14">
        <h2 class="text-xl font-bold font-heading text-[#18181B] mb-1 text-center">How to Redeem Your Art Vouchers</h2>
        <p class="text-xs text-stone-500 text-center mb-8">Follow these simple steps to claim your collector savings in seconds</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-stone-100 text-[#18181B] flex items-center justify-center font-bold text-sm flex-shrink-0">
                    1
                </div>
                <div>
                    <h3 class="font-bold text-sm text-[#18181B] mb-1">Select an Original Artwork</h3>
                    <p class="text-xs text-stone-600 leading-relaxed">Browse our gallery of oil paintings, acrylics, and water colours. Choose custom artisan framing and add to your cart.</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-stone-100 text-[#18181B] flex items-center justify-center font-bold text-sm flex-shrink-0">
                    2
                </div>
                <div>
                    <h3 class="font-bold text-sm text-[#18181B] mb-1">Copy Your Voucher Code</h3>
                    <p class="text-xs text-stone-600 leading-relaxed">Click "Copy Code" on any eligible voucher card above. The promotional code will be copied to your clipboard instantly.</p>
                </div>
            </div>

            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-2xl bg-stone-100 text-[#18181B] flex items-center justify-center font-bold text-sm flex-shrink-0">
                    3
                </div>
                <div>
                    <h3 class="font-bold text-sm text-[#18181B] mb-1">Apply &amp; Enjoy Savings</h3>
                    <p class="text-xs text-stone-600 leading-relaxed">Paste the code into the promo field on the Cart or Checkout page. Your order total will update immediately with the discount.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Eligible Artworks Showcase -->
    <?php if (!empty($eligibleArt)): ?>
        <div class="mb-14">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold font-heading text-[#18181B]">Featured Art Eligible for Offers</h2>
                    <p class="text-xs text-stone-500 mt-0.5">Original canvases ready for instant acquisition and discount application</p>
                </div>
                <a href="<?= SITE_URL ?>/shop" class="text-xs font-bold text-[#18181B] hover:underline flex items-center gap-1">
                    <span>View All Collection</span>
                    <span>&rarr;</span>
                </a>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                <?php foreach ($eligibleArt as $art): ?>
                    <div class="bg-white rounded-2xl border border-stone-200/80 overflow-hidden shadow-2xs hover:shadow-md transition-all group flex flex-col justify-between">
                        <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($art['slug']) ?>" class="block relative aspect-square overflow-hidden bg-stone-100">
                            <img src="<?= htmlspecialchars($art['primary_image'] ?? $art['image'] ?? '') ?>" alt="<?= htmlspecialchars($art['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                            <div class="absolute top-2.5 left-2.5">
                                <span class="bg-black/70 backdrop-blur-xs text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    <?= htmlspecialchars($art['medium'] ?? 'Original Oil') ?>
                                </span>
                            </div>
                        </a>
                        <div class="p-3.5 flex flex-col flex-1 justify-between">
                            <div>
                                <h3 class="font-bold text-xs sm:text-sm text-[#18181B] truncate">
                                    <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($art['slug']) ?>" class="hover:text-stone-600 transition-colors">
                                        <?= htmlspecialchars($art['title']) ?>
                                    </a>
                                </h3>
                                <p class="text-[11px] text-stone-500 truncate mt-0.5"><?= htmlspecialchars($art['artist_name'] ?? 'JayArt Master Artist') ?></p>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-stone-100 flex items-center justify-between">
                                <span class="font-extrabold text-xs sm:text-sm text-[#18181B]">
                                    <?= CURRENCY_SYMBOL ?> <?= number_format((float)$art['price']) ?>
                                </span>
                                <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($art['slug']) ?>" class="px-2.5 py-1 bg-stone-100 hover:bg-[#18181B] text-[#18181B] hover:text-white rounded-lg text-[10px] font-bold transition-colors">
                                    Apply Offer
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Collector Assurance Bar -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-stone-100 border border-stone-200/80 rounded-3xl p-6 sm:p-8">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-white text-[#18181B] flex items-center justify-center font-bold shadow-2xs flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-xs text-[#18181B]">Certificate Included</h4>
                <p class="text-[11px] text-stone-500">Signed provenance with every piece</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-white text-[#18181B] flex items-center justify-center font-bold shadow-2xs flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-xs text-[#18181B]">Insured Shipping</h4>
                <p class="text-[11px] text-stone-500">Custom crated fine art delivery</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-white text-[#18181B] flex items-center justify-center font-bold shadow-2xs flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-xs text-[#18181B]">14-Day Returns</h4>
                <p class="text-[11px] text-stone-500">Hassle-free collection guarantee</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-white text-[#18181B] flex items-center justify-center font-bold shadow-2xs flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-xs text-[#18181B]">Secure Payments</h4>
                <p class="text-[11px] text-stone-500">Razorpay, UPI QR, NetBanking</p>
            </div>
        </div>
    </div>
</div>

<!-- Copy Code Toast & Script -->
<div id="copyToast" class="fixed bottom-6 right-6 z-50 bg-gray-900 text-white text-xs px-4 py-2.5 rounded-2xl shadow-xl flex items-center gap-2 transform translate-y-16 opacity-0 transition-all duration-300 pointer-events-none">
    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    <span id="copyToastText">Coupon code copied to clipboard!</span>
</div>

<script>
function copyVoucherCode(code, btn) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(code).then(() => {
            showCopyFeedback(code, btn);
        }).catch(() => {
            fallbackCopy(code, btn);
        });
    } else {
        fallbackCopy(code, btn);
    }
}

function fallbackCopy(code, btn) {
    const tempInput = document.createElement('input');
    tempInput.value = code;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    showCopyFeedback(code, btn);
}

function showCopyFeedback(code, btn) {
    const originalText = btn.querySelector('.btn-text').textContent;
    btn.querySelector('.btn-text').textContent = 'Copied!';
    btn.classList.add('bg-emerald-500', 'text-white', 'border-emerald-600');

    // Toast
    const toast = document.getElementById('copyToast');
    const toastText = document.getElementById('copyToastText');
    toastText.textContent = 'Code "' + code + '" copied! Paste it in your cart.';
    toast.classList.remove('translate-y-16', 'opacity-0');
    toast.classList.add('translate-y-0', 'opacity-100');

    setTimeout(() => {
        btn.querySelector('.btn-text').textContent = originalText;
        btn.classList.remove('bg-emerald-500', 'text-white', 'border-emerald-600');
        toast.classList.add('translate-y-16', 'opacity-0');
        toast.classList.remove('translate-y-0', 'opacity-100');
    }, 2500);
}
</script>

