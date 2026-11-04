<?php
/**
 * JayArt Modern Cart View
 * Full-Screen Architectural Nordic Atelier E-Commerce Design
 */

$subtotal = (float)($subtotal ?? 0.0);
$discountAmount = (float)($discount ?? $discountAmount ?? 0.0);
$shippingFee = (float)($shipping ?? $shippingFee ?? 0.0);
$taxAmount = (float)($taxAmount ?? 0.0);
$total = (float)($total ?? max(0.0, $subtotal - $discountAmount + $shippingFee + $taxAmount));
$items = is_array($items ?? null) ? $items : [];
?>

<div class="w-full space-y-6">
    <div class="flex items-center justify-between pb-5 border-b border-stone-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#18181B] tracking-tight">Shopping Cart</h1>
            <p class="text-xs text-stone-500 mt-1">Review your curated original paintings and fine art acquisitions</p>
        </div>
        <a href="<?= SITE_URL ?>/shop" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#18181B] hover:text-stone-600 transition-colors">
            &larr; <span>Continue Exploring</span>
        </a>
    </div>

    <?php if (empty($items)): ?>
        <div class="bg-white border border-stone-200/80 rounded-3xl p-12 text-center my-8 max-w-md mx-auto shadow-xs">
            <div class="w-16 h-16 rounded-full bg-stone-100 text-stone-400 flex items-center justify-center mx-auto mb-4" style="width: 64px; height: 64px;">
                <svg width="28" height="28" style="width: 28px; height: 28px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <h2 class="text-lg font-bold text-[#18181B] mb-1">Your cart is empty</h2>
            <p class="text-xs text-stone-500 mb-6">Discover master artworks from certified artist studios across India.</p>
            <a href="<?= SITE_URL ?>/shop" class="inline-block py-3 px-8 bg-[#18181B] text-white rounded-full text-xs font-bold shadow-xs hover:bg-black transition-colors">
                Explore Artworks
            </a>
        </div>
    <?php else: ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
            
            <!-- Items List (Cols 1-8) -->
            <div class="lg:col-span-8 space-y-4">
                <div class="bg-white border border-stone-200/80 rounded-3xl p-5 sm:p-6 shadow-xs divide-y divide-stone-100">
                    <?php foreach ($items as $key => $item): 
                        $itemKey = (string)($item['item_key'] ?? $key);
                        $itemImg = !empty($item['image']) ? $item['image'] : ASSETS_URL . '/images/banner.jpg';
                        $unitPrice = (float)($item['price'] ?? $item['final_unit_price'] ?? 0.0);
                        $qty = (int)($item['quantity'] ?? 1);
                        $lineTotal = $unitPrice * $qty;
                    ?>
                        <div class="py-5 first:pt-0 last:pb-0 flex items-center gap-4 sm:gap-6">
                            <!-- Thumbnail in stone box -->
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-stone-100 rounded-2xl p-2 flex items-center justify-center flex-shrink-0" style="width: 80px; height: 80px; min-width: 80px;">
                                <img src="<?= htmlspecialchars($itemImg) ?>" alt="<?= htmlspecialchars($item['title'] ?? 'Artwork') ?>" class="max-h-full max-w-full object-contain">
                            </div>

                            <!-- Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <h3 class="text-sm sm:text-base font-bold text-[#18181B] truncate">
                                            <a href="<?= SITE_URL ?>/product/<?= htmlspecialchars($item['slug'] ?? '') ?>" class="hover:text-stone-600 transition-colors">
                                                <?= htmlspecialchars($item['title'] ?? 'Artwork') ?>
                                            </a>
                                        </h3>
                                        <p class="text-xs text-stone-400 mt-0.5">Frame: <span class="font-semibold text-stone-700"><?= htmlspecialchars($item['frame_chosen'] ?? 'Rolled Canvas') ?></span></p>
                                    </div>
                                    <span class="text-sm sm:text-base font-extrabold text-[#18181B] whitespace-nowrap">
                                        <?= Validator::formatPrice($lineTotal) ?>
                                    </span>
                                </div>

                                <!-- Actions Row: Quantity Pill + Remove -->
                                <div class="flex items-center justify-between mt-3">
                                    <div class="inline-flex items-center bg-stone-100 rounded-full px-3 py-1 border border-stone-200">
                                        <button type="button" onclick="updateCartQty('<?= htmlspecialchars($itemKey, ENT_QUOTES) ?>', <?= max(1, $qty - 1) ?>)" class="text-stone-600 hover:text-black font-extrabold text-sm px-1.5 cursor-pointer">-</button>
                                        <span class="w-6 text-center text-xs font-bold text-[#18181B]"><?= $qty ?></span>
                                        <button type="button" onclick="updateCartQty('<?= htmlspecialchars($itemKey, ENT_QUOTES) ?>', <?= min(10, $qty + 1) ?>)" class="text-stone-600 hover:text-black font-extrabold text-sm px-1.5 cursor-pointer">+</button>
                                    </div>

                                    <button type="button" onclick="removeCartItem('<?= htmlspecialchars($itemKey, ENT_QUOTES) ?>')" class="text-xs text-stone-400 hover:text-red-600 font-semibold flex items-center gap-1 transition-colors cursor-pointer">
                                        <svg width="14" height="14" style="width: 14px; height: 14px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span>Remove</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Order Summary Card (Cols 9-12) -->
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white border border-stone-200/80 rounded-3xl p-6 sm:p-7 shadow-xs space-y-4">
                    <h2 class="text-base font-extrabold text-[#18181B]">Order Summary</h2>

                    <!-- Coupon Code Input -->
                    <div>
                        <div class="flex items-center gap-2">
                            <input type="text" id="cartCouponInput" placeholder="Enter Coupon Code" class="form-input text-xs uppercase font-mono tracking-wider flex-1 rounded-full px-4 py-2.5 border border-stone-300">
                            <button type="button" onclick="applyCartCoupon()" class="px-5 py-2.5 bg-[#18181B] hover:bg-black text-white text-xs font-bold rounded-full transition-colors whitespace-nowrap shadow-xs cursor-pointer">
                                Apply
                            </button>
                        </div>
                        <?php 
                        $activeCoupons = Coupon::allActive(); 
                        if (!empty($activeCoupons)): 
                        ?>
                            <div class="mt-2.5">
                                <span class="text-[11px] text-stone-500 font-semibold block mb-1.5">Available Offers (Click to apply):</span>
                                <div class="flex flex-wrap gap-1.5">
                                    <?php foreach (array_slice($activeCoupons, 0, 4) as $ac): ?>
                                        <button type="button" onclick="selectCouponCode('<?= htmlspecialchars($ac['code']) ?>')" class="px-2.5 py-1 bg-stone-100 hover:bg-stone-200 text-[#18181B] border border-dashed border-stone-300 rounded-lg text-[10px] font-mono font-bold transition-colors cursor-pointer" title="<?= htmlspecialchars($ac['title'] ?? '') ?>">
                                            <?= htmlspecialchars($ac['code']) ?> (<?= $ac['discount_type'] === 'percentage' ? ((float)$ac['discount_value'] . '%') : (CURRENCY_SYMBOL . ' ' . number_format((float)$ac['discount_value'])) ?>)
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                                <a href="<?= SITE_URL ?>/offers" target="_blank" class="text-[10px] text-[#18181B] hover:underline block mt-1.5 font-bold">
                                    View All Offers &amp; Terms &rarr;
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr class="border-stone-100">

                    <div class="space-y-2.5 text-xs">
                        <div class="flex justify-between text-stone-600">
                            <span>Subtotal</span>
                            <span class="font-bold text-[#18181B]"><?= Validator::formatPrice($subtotal) ?></span>
                        </div>
                        <?php if ($discountAmount > 0): ?>
                            <div class="flex justify-between text-amber-600 font-bold">
                                <span>Discount</span>
                                <span>-<?= Validator::formatPrice($discountAmount) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex justify-between text-stone-600">
                            <span>Shipping</span>
                            <span><?= ($shippingFee > 0) ? Validator::formatPrice($shippingFee) : '<strong class="text-amber-600">FREE</strong>' ?></span>
                        </div>
                        <div class="flex justify-between text-stone-600">
                            <span>Estimated Tax</span>
                            <span class="font-bold text-[#18181B]"><?= Validator::formatPrice($taxAmount) ?></span>
                        </div>
                        <hr class="border-stone-100 my-2">
                        <div class="flex justify-between items-baseline pt-1">
                            <span class="text-sm font-extrabold text-[#18181B]">Total</span>
                            <span class="text-2xl font-extrabold text-[#18181B]"><?= Validator::formatPrice($total) ?></span>
                        </div>
                    </div>

                    <a href="<?= SITE_URL ?>/checkout" class="block w-full py-3.5 px-6 bg-[#18181B] hover:bg-black text-white rounded-full font-bold text-xs uppercase tracking-wider text-center transition-all shadow-xs">
                        Proceed to Checkout &rarr;
                    </a>
                </div>
            </div>

        </div>

    <?php endif; ?>
</div>

<script>
function updateCartQty(itemKey, newQty) {
    fetch('<?= SITE_URL ?>/cart/update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'item_key=' + encodeURIComponent(itemKey) + '&quantity=' + encodeURIComponent(newQty)
    })
    .then(r => r.json())
    .then(data => {
        if (data && data.success) {
            window.location.reload();
        }
    })
    .catch(() => window.location.reload());
}

function removeCartItem(itemKey) {
    fetch('<?= SITE_URL ?>/cart/remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'item_key=' + encodeURIComponent(itemKey)
    })
    .then(r => r.json())
    .then(data => {
        if (data && data.success) {
            window.location.reload();
        }
    })
    .catch(() => window.location.reload());
}

function selectCouponCode(code) {
    const input = document.getElementById('cartCouponInput');
    if (input) {
        input.value = code;
        applyCartCoupon();
    }
}

function applyCartCoupon() {
    const input = document.getElementById('cartCouponInput');
    const code = input ? input.value.trim() : '';
    if (!code) return;

    fetch('<?= SITE_URL ?>/cart/apply-coupon', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'coupon_code=' + encodeURIComponent(code) + '&code=' + encodeURIComponent(code)
    })
    .then(r => r.json())
    .then(data => {
        if (data && data.success) {
            if (typeof window.openCouponCelebrationModal === 'function') {
                window.openCouponCelebrationModal(data);
            } else {
                window.location.reload();
            }
        } else {
            const err = (data && data.message) ? data.message : 'Coupon code could not be verified.';
            if (typeof window.openCouponErrorModal === 'function') {
                window.openCouponErrorModal(err);
            } else if (typeof showToast === 'function') {
                showToast(err, 'error');
            }
        }
    })
    .catch(() => {
        if (typeof window.openCouponErrorModal === 'function') {
            window.openCouponErrorModal('Error communicating with gallery server. Please try again.');
        } else if (typeof showToast === 'function') {
            showToast('Could not apply coupon. Please try again.', 'error');
        }
    });
}
</script>
