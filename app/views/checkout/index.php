<?php
/**
 * JayArt Modern Checkout View
 * Architectural Nordic Atelier Design
 */

$cartItems = is_array($cartItems ?? null) ? $cartItems : (is_array($items ?? null) ? $items : []);
$subtotal = (float)($subtotal ?? 0.0);
$discountAmount = (float)($discount ?? $discountAmount ?? 0.0);
$shippingFee = (float)($shipping ?? $shippingFee ?? 0.0);
$taxAmount = (float)($taxAmount ?? 0.0);
$total = (float)($total ?? max(0.0, $subtotal - $discountAmount + $shippingFee + $taxAmount));

$upiData = null;
if (!empty($upi['upi_vpa'])) {
    $tempOrderNumber = 'JA-' . date('ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
    $upiData = Payment::buildUPIData($upi['upi_vpa'], $upi['upi_merchant_name'] ?? 'JayArt Gallery', $total, $tempOrderNumber);
}
?>

<div class="w-full space-y-6 pb-12 sm:pb-8">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-5 border-b border-stone-200">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#18181B] tracking-tight">Checkout</h1>
            <p class="text-xs text-stone-500 mt-1">Review your fine art acquisition and insured delivery details</p>
        </div>
        <a href="<?= SITE_URL ?>/cart" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#18181B] hover:text-stone-600 transition-colors self-start sm:self-auto py-1">
            &larr; <span>Back to Cart</span>
        </a>
    </div>

    <?php if ($flashErr = Auth::getFlash('error')): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl text-xs mb-6">
            <?= htmlspecialchars($flashErr) ?>
        </div>
    <?php endif; ?>

    <form id="checkoutForm" action="<?= SITE_URL ?>/checkout/process" method="POST" enctype="multipart/form-data">
        <?= Validator::csrfField() ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10">
            
            <!-- Left Column: Review Item And Shipping & Delivery Information -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- 1. Review Item And Shipping Card -->
                <div class="bg-white border border-stone-200/80 rounded-3xl p-5 sm:p-7 shadow-xs">
                    <h2 class="text-base font-extrabold text-[#18181B] mb-4">Review Items &amp; Packaging</h2>
                    
                    <div class="divide-y divide-stone-100">
                        <?php foreach ($cartItems as $item): 
                            $itemImg = !empty($item['image']) ? $item['image'] : (!empty($item['primary_image']) ? $item['primary_image'] : ASSETS_URL . '/images/banner.jpg');
                            $frameName = $item['frame_chosen'] ?? $item['frame'] ?? 'Rolled Canvas';
                            $unitPrice = (float)($item['price'] ?? $item['final_unit_price'] ?? 0.0);
                            $qty = (int)($item['quantity'] ?? 1);
                        ?>
                            <div class="py-4 first:pt-0 last:pb-0 flex items-start sm:items-center gap-3.5 sm:gap-4">
                                <!-- Thumbnail in stone box -->
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-stone-100 p-2 flex items-center justify-center flex-shrink-0 border border-stone-200/50" style="width: 64px; height: 64px; min-width: 64px;">
                                    <img src="<?= htmlspecialchars($itemImg) ?>" alt="<?= htmlspecialchars($item['title'] ?? 'Artwork') ?>" class="max-h-full max-w-full object-contain">
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-1 sm:gap-2">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="text-sm font-bold text-[#18181B] leading-snug line-clamp-2"><?= htmlspecialchars($item['title'] ?? 'Original Painting') ?></h3>
                                            <p class="text-[11px] sm:text-xs text-stone-500 mt-0.5">Frame: <span class="font-semibold text-stone-700"><?= htmlspecialchars($frameName) ?></span></p>
                                        </div>
                                        <div class="sm:text-right mt-1 sm:mt-0 flex-shrink-0">
                                            <span class="text-sm font-extrabold text-[#18181B] block">
                                                <?= Validator::formatPrice($unitPrice * $qty) ?>
                                            </span>
                                            <p class="text-[10px] sm:text-xs text-stone-400 mt-0.5">Quantity: <?= sprintf('%02d', $qty) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 2. Delivery Information Card -->
                <div class="bg-white border border-stone-200/80 rounded-3xl p-5 sm:p-7 shadow-xs space-y-4">
                    <div class="flex items-center justify-between pb-2">
                        <h2 class="text-base font-extrabold text-[#18181B]">Delivery Information</h2>
                        <span class="text-xs text-[#18181B] font-bold">Secure Insured Crating</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-bold text-stone-700 block mb-1">Full Legal Name *</label>
                            <input type="text" name="customer_name" required value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>" class="form-input text-xs" placeholder="e.g. Wade Warren">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-stone-700 block mb-1">Mobile Phone *</label>
                            <input type="tel" name="customer_phone" required value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>" class="form-input text-xs" placeholder="e.g. +91 98765 43210">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-stone-700 block mb-1">Email Address (For Invoice & Updates) *</label>
                        <input type="email" name="customer_email" required value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" class="form-input text-xs" placeholder="e.g. collector@domain.com">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-stone-700 block mb-1">Street Address *</label>
                        <input type="text" name="address_line1" required class="form-input text-xs" placeholder="e.g. 4140 Heritage Boulevard, Arts Enclave">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-bold text-stone-700 block mb-1">City *</label>
                            <input type="text" name="city" required class="form-input text-xs" placeholder="e.g. Mumbai">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-stone-700 block mb-1">State *</label>
                            <input type="text" name="state" required class="form-input text-xs" placeholder="e.g. Maharashtra">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-stone-700 block mb-1">Pincode / Postal Code *</label>
                            <input type="text" name="pincode" required class="form-input text-xs" placeholder="e.g. 400001">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Order Summary & Payment Details (Shopcart style) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Order Summary Card with Coupon Input -->
                <div class="bg-white border border-stone-200/80 rounded-3xl p-5 sm:p-7 shadow-xs space-y-4">
                    <h2 class="text-base font-extrabold text-[#18181B]">Order Summary</h2>

                    <!-- Coupon Code Input with Obsidian Pill Button -->
                    <div>
                        <div class="flex items-center gap-2">
                            <input type="text" id="couponCodeInput" placeholder="ENTER COUPON CODE" class="form-input text-xs uppercase font-mono tracking-wider flex-1 min-w-0 rounded-full px-4 py-2.5 border border-stone-300 bg-white">
                            <button type="button" onclick="applyCouponCode()" class="px-4 sm:px-5 py-2.5 bg-[#18181B] hover:bg-black text-white text-xs font-bold rounded-full transition-colors whitespace-nowrap shadow-xs cursor-pointer flex-shrink-0">
                                Apply
                            </button>
                        </div>
                        <div id="couponMessage" class="text-xs font-semibold hidden mt-1.5"></div>

                        <?php 
                        $checkoutCoupons = Coupon::allActive(); 
                        if (!empty($checkoutCoupons)): 
                        ?>
                            <div class="mt-2.5">
                                <span class="text-[11px] text-stone-500 font-semibold block mb-1">Available Offers (Click to apply):</span>
                                <div class="flex flex-wrap gap-1.5">
                                    <?php foreach (array_slice($checkoutCoupons, 0, 4) as $ac): ?>
                                        <button type="button" onclick="selectCheckoutCoupon('<?= htmlspecialchars($ac['code']) ?>')" class="px-2.5 py-1 bg-stone-100 hover:bg-stone-200 text-[#18181B] border border-dashed border-stone-300 rounded-lg text-[10px] font-mono font-bold transition-colors cursor-pointer" title="<?= htmlspecialchars($ac['title'] ?? '') ?>">
                                            <?= htmlspecialchars($ac['code']) ?> (<?= $ac['discount_type'] === 'percentage' ? ((float)$ac['discount_value'] . '%') : (CURRENCY_SYMBOL . ' ' . number_format((float)$ac['discount_value'])) ?>)
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr class="border-stone-100">

                    <!-- Breakdown Rows -->
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
                            <span>Museum Crating & Shipping</span>
                            <span><?= ($shippingFee > 0) ? Validator::formatPrice($shippingFee) : '<strong class="text-amber-600">FREE</strong>' ?></span>
                        </div>
                        <div class="flex justify-between text-stone-600">
                            <span>GST / Tax</span>
                            <span class="font-bold text-[#18181B]"><?= Validator::formatPrice($taxAmount) ?></span>
                        </div>
                        <hr class="border-stone-100 my-2">
                        <div class="flex justify-between items-baseline pt-1">
                            <span class="text-sm font-extrabold text-[#18181B]">Total</span>
                            <span class="text-2xl font-extrabold text-[#18181B]"><?= Validator::formatPrice($total) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Details Card (Shopcart style with Radio buttons) -->
                <div class="bg-white border border-stone-200/80 rounded-3xl p-5 sm:p-7 shadow-xs space-y-4">
                    <h2 class="text-base font-extrabold text-[#18181B]">Payment Details</h2>

                    <div class="space-y-3">
                        <!-- 1. Razorpay Option -->
                        <?php if (!empty($gateways['razorpay']['is_enabled'])): ?>
                            <label class="flex items-center justify-between p-3.5 border border-stone-200 rounded-2xl cursor-pointer hover:border-stone-900 transition-colors gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_method" value="razorpay" checked onchange="togglePaymentBox('razorpay')" class="text-[#18181B] focus:ring-[#18181B] flex-shrink-0">
                                    <span class="text-xs font-bold text-[#18181B] truncate">Credit / Debit Card (Razorpay)</span>
                                </div>
                                <div class="flex items-center gap-1.5 opacity-80 flex-shrink-0">
                                    <span class="text-[10px] font-bold text-stone-400">VISA / MC</span>
                                </div>
                            </label>
                        <?php endif; ?>

                        <!-- 2. Direct Dynamic UPI QR Option -->
                        <?php if (!empty($gateways['upi']['is_enabled'])): ?>
                            <label class="flex items-center justify-between p-3.5 border border-stone-200 rounded-2xl cursor-pointer hover:border-stone-900 transition-colors gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_method" value="upi" onchange="togglePaymentBox('upi')" class="text-[#18181B] focus:ring-[#18181B] flex-shrink-0">
                                    <span class="text-xs font-bold text-[#18181B] truncate">Direct UPI QR (GPay, PhonePe)</span>
                                </div>
                                <span class="text-[10px] bg-stone-100 text-[#18181B] font-bold px-2 py-0.5 rounded-full border border-stone-200 flex-shrink-0">Instant</span>
                            </label>

                            <!-- Dynamic UPI Expandable Box -->
                            <div id="upiBox" class="hidden p-4 bg-stone-50 rounded-2xl space-y-3 text-xs border border-stone-200">
                                <?php if ($upiData): ?>
                                    <div class="text-center">
                                        <p class="font-bold text-[#18181B] mb-2">Scan & Pay <?= Validator::formatPrice($total) ?></p>
                                        <img src="<?= htmlspecialchars($upiData['qr_image']) ?>" alt="UPI QR" class="w-40 h-40 mx-auto rounded-xl border border-stone-200 shadow-sm">
                                        <p class="text-[11px] text-stone-500 mt-2 font-mono">UPI ID: <strong><?= htmlspecialchars($upiData['vpa']) ?></strong></p>
                                    </div>
                                    <div>
                                        <label class="font-bold text-[#18181B] block mb-1">Enter 12-digit UPI UTR / Ref No. *</label>
                                        <input type="text" name="upi_utr_number" placeholder="e.g. 408291028371" class="form-input text-xs font-mono">
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- 3. Cash on Delivery (COD) -->
                        <?php if (!empty($gateways['cod']['is_enabled'])): ?>
                            <label class="flex items-center justify-between p-3.5 border border-stone-200 rounded-2xl cursor-pointer hover:border-stone-900 transition-colors gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_method" value="cod" onchange="togglePaymentBox('cod')" class="text-[#18181B] focus:ring-[#18181B] flex-shrink-0">
                                    <span class="text-xs font-bold text-[#18181B] truncate">Cash on Delivery (COD)</span>
                                </div>
                            </label>
                        <?php endif; ?>

                        <!-- 4. Direct Bank Transfer -->
                        <?php if (!empty($gateways['bank']['is_enabled'])): ?>
                            <label class="flex items-center justify-between p-3.5 border border-stone-200 rounded-2xl cursor-pointer hover:border-stone-900 transition-colors gap-2">
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_method" value="bank" onchange="togglePaymentBox('bank')" class="text-[#18181B] focus:ring-[#18181B] flex-shrink-0">
                                    <span class="text-xs font-bold text-[#18181B] truncate">Direct Bank Wire (NEFT / RTGS)</span>
                                </div>
                            </label>
                        <?php endif; ?>
                    </div>

                    <!-- Final Submission Button -->
                    <div class="pt-4">
                        <button type="submit" class="w-full py-3.5 sm:py-4 px-4 sm:px-8 bg-[#18181B] hover:bg-black text-white rounded-full font-extrabold text-xs sm:text-sm uppercase tracking-wider transition-all shadow-md flex items-center justify-center gap-2">
                            <span>Confirm and Pay <?= Validator::formatPrice($total) ?></span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>

<script>
function selectCheckoutCoupon(code) {
    const input = document.getElementById('couponCodeInput');
    if (input) {
        input.value = code;
        applyCouponCode();
    }
}

function togglePaymentBox(method) {
    const upiBox = document.getElementById('upiBox');
    if (upiBox) {
        if (method === 'upi') {
            upiBox.classList.remove('hidden');
        } else {
            upiBox.classList.add('hidden');
        }
    }
}

function applyCouponCode() {
    const input = document.getElementById('couponCodeInput');
    const code = input ? input.value.trim() : '';
    const msg = document.getElementById('couponMessage');
    if (!code) return;

    fetch('<?= SITE_URL ?>/cart/apply-coupon', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'coupon_code=' + encodeURIComponent(code) + '&code=' + encodeURIComponent(code)
    })
    .then(r => r.json())
    .then(data => {
        if (data && data.success) {
            if (msg) {
                msg.classList.remove('hidden');
                msg.className = 'text-xs font-bold text-emerald-600 mt-1';
                msg.innerText = data.message;
            }
            if (typeof window.openCouponCelebrationModal === 'function') {
                window.openCouponCelebrationModal(data);
            } else {
                setTimeout(() => window.location.reload(), 600);
            }
        } else {
            const err = (data && data.message) ? data.message : 'Coupon code could not be applied.';
            if (msg) {
                msg.classList.remove('hidden');
                msg.className = 'text-xs font-bold text-red-600 mt-1';
                msg.innerText = err;
            }
            if (typeof window.openCouponErrorModal === 'function') {
                window.openCouponErrorModal(err);
            }
        }
    })
    .catch(() => {
        if (typeof window.openCouponErrorModal === 'function') {
            window.openCouponErrorModal('Error communicating with gallery server. Please try again.');
        }
    });
}
</script>
