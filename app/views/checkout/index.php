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

$oldCheckout = $_SESSION['old_checkout'] ?? [];
unset($_SESSION['old_checkout']);

$defaultAddress = null;
if (!empty($savedAddresses) && is_array($savedAddresses)) {
    foreach ($savedAddresses as $sa) {
        if (!empty($sa['is_default'])) {
            $defaultAddress = $sa;
            break;
        }
    }
    if (!$defaultAddress) {
        $defaultAddress = $savedAddresses[0] ?? null;
    }
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
                            <label class="text-xs font-bold text-stone-700 block mb-1">Full Legal Name <span class="text-red-500">*</span></label>
                            <input type="text" id="checkout_name" name="customer_name" required value="<?= htmlspecialchars(!empty($oldCheckout['customer_name']) ? $oldCheckout['customer_name'] : ($defaultAddress['recipient_name'] ?? $currentUser['name'] ?? '')) ?>" class="form-input text-xs" placeholder="Your Full Name">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-stone-700 block mb-1">Mobile Phone <span class="text-red-500">*</span></label>
                            <input type="tel" id="checkout_phone" name="customer_phone" required value="<?= htmlspecialchars(!empty($oldCheckout['customer_phone']) ? $oldCheckout['customer_phone'] : ($defaultAddress['phone'] ?? $currentUser['phone'] ?? '')) ?>" class="form-input text-xs" placeholder="10-digit mobile number">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-stone-700 block mb-1">Email Address (For Invoice &amp; Updates) <span class="text-red-500">*</span></label>
                        <input type="email" id="checkout_email" name="customer_email" required value="<?= htmlspecialchars(!empty($oldCheckout['customer_email']) ? $oldCheckout['customer_email'] : ($currentUser['email'] ?? '')) ?>" class="form-input text-xs" placeholder="name@example.com">
                    </div>

                    <div>
                        <label class="text-xs font-bold text-stone-700 block mb-1">Street Address <span class="text-red-500">*</span></label>
                        <input type="text" id="checkout_address" name="address_line1" required value="<?= htmlspecialchars(!empty($oldCheckout['address_line1']) ? $oldCheckout['address_line1'] : ($defaultAddress['address_line1'] ?? '')) ?>" class="form-input text-xs" placeholder="House/Flat No., Building, Street, Area">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="text-xs font-bold text-stone-700 block mb-1">City <span class="text-red-500">*</span></label>
                            <input type="text" id="checkout_city" name="city" required value="<?= htmlspecialchars(!empty($oldCheckout['city']) ? $oldCheckout['city'] : ($defaultAddress['city'] ?? '')) ?>" class="form-input text-xs" placeholder="City">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-stone-700 block mb-1">State <span class="text-red-500">*</span></label>
                            <input type="text" id="checkout_state" name="state" required value="<?= htmlspecialchars(!empty($oldCheckout['state']) ? $oldCheckout['state'] : ($defaultAddress['state'] ?? '')) ?>" class="form-input text-xs" placeholder="State">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-stone-700 block mb-1">Pincode / Postal Code <span class="text-red-500">*</span></label>
                            <input type="text" id="checkout_pincode" name="postal_code" required value="<?= htmlspecialchars(!empty($oldCheckout['postal_code']) ? $oldCheckout['postal_code'] : (!empty($oldCheckout['pincode']) ? $oldCheckout['pincode'] : ($defaultAddress['postal_code'] ?? ''))) ?>" class="form-input text-xs" placeholder="6-digit PIN code">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Order Summary & Payment Details (Shopcart style) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Order Summary Card with Coupon Input -->
                <div class="bg-white border border-stone-200/80 rounded-3xl p-5 sm:p-7 shadow-xs space-y-4">
                    <h2 class="text-base font-extrabold text-[#18181B]">Order Summary</h2>

                    <!-- Coupon Code Input / Applied Badge -->
                    <div>
                        <?php if (!empty($appliedCoupon)): ?>
                            <!-- Active Applied Coupon Box with Remove Option -->
                            <div class="bg-emerald-50/80 border border-emerald-200/90 rounded-2xl p-3 sm:p-3.5 flex items-center justify-between gap-3 shadow-2xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 shadow-xs">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="font-mono font-extrabold text-xs text-emerald-950 tracking-wider">
                                                <?= htmlspecialchars($appliedCoupon['code']) ?>
                                            </span>
                                            <span class="text-[9px] font-bold uppercase tracking-wider text-emerald-800 bg-emerald-100 px-2 py-0.5 rounded-full border border-emerald-200">
                                                Applied
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-emerald-700 font-medium truncate mt-0.5">
                                            Saving <?= Validator::formatPrice($discountAmount) ?> on your acquisition
                                        </p>
                                    </div>
                                </div>
                                <button type="button" 
                                        onclick="removeCheckoutCoupon()" 
                                        class="px-3 py-1.5 rounded-full text-xs font-bold text-red-600 hover:text-red-700 bg-white hover:bg-red-50 border border-red-200 hover:border-red-300 transition-all cursor-pointer flex-shrink-0 shadow-2xs">
                                    Remove
                                </button>
                            </div>
                        <?php else: ?>
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
                            <div class="flex justify-between text-emerald-700 font-bold bg-emerald-50/60 px-2.5 py-1.5 rounded-xl border border-emerald-100">
                                <span class="flex items-center gap-1.5">
                                    <span>Discount</span>
                                    <?php if (!empty($appliedCoupon['code'])): ?>
                                        <span class="font-mono text-[10px] bg-emerald-100 px-1.5 py-0.5 rounded text-emerald-800"><?= htmlspecialchars($appliedCoupon['code']) ?></span>
                                    <?php endif; ?>
                                </span>
                                <span>-<?= Validator::formatPrice($discountAmount) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="flex justify-between text-stone-600">
                            <span>Delivery Charges</span>
                            <span><?= ($shippingFee > 0) ? Validator::formatPrice($shippingFee) : '<strong class="text-emerald-700">FREE</strong>' ?></span>
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
                <?php
                $gateways = (!empty($gateways) && is_array($gateways)) ? $gateways : Payment::defaultGateways();
                
                // Determine sensible default payment method
                $selectedMethod = $_POST['payment_method'] ?? '';
                if (empty($selectedMethod)) {
                    if (!empty($gateways['upi']['is_enabled'])) {
                        $selectedMethod = 'upi';
                    } elseif (!empty($gateways['cod']['is_enabled'])) {
                        $selectedMethod = 'cod';
                    } elseif (!empty($gateways['razorpay']['is_enabled'])) {
                        $selectedMethod = 'razorpay';
                    } elseif (!empty($gateways['bank']['is_enabled'])) {
                        $selectedMethod = 'bank';
                    } else {
                        $selectedMethod = 'cod';
                    }
                }
                ?>
                <div class="bg-white border border-stone-200/80 rounded-3xl p-5 sm:p-7 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-base font-extrabold text-[#18181B]">Select Payment Method</h2>
                        <span class="text-[11px] text-stone-500 font-medium">100% Secure &amp; Encrypted</span>
                    </div>

                    <div class="space-y-3">
                        <!-- 1. Direct Dynamic UPI QR Option -->
                        <?php if (!empty($gateways['upi']['is_enabled'])): ?>
                            <label class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer transition-all gap-2 <?= ($selectedMethod === 'upi') ? 'border-stone-900 bg-stone-50/60 shadow-xs' : 'border-stone-200 hover:border-stone-400' ?>">
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_method" value="upi" <?= ($selectedMethod === 'upi') ? 'checked' : '' ?> onchange="togglePaymentBox('upi')" class="text-[#18181B] focus:ring-[#18181B] flex-shrink-0">
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold text-[#18181B] block">Direct UPI QR (GPay, PhonePe, Paytm, BHIM)</span>
                                        <span class="text-[10px] text-stone-500">Scan QR with any UPI app &amp; zero surcharge</span>
                                    </div>
                                </div>
                                <span class="text-[10px] bg-emerald-50 text-emerald-700 font-bold px-2 py-0.5 rounded-full border border-emerald-200 flex-shrink-0">Instant &amp; Fast</span>
                            </label>

                            <!-- Dynamic UPI Expandable Box -->
                            <div id="upiBox" class="<?= ($selectedMethod === 'upi') ? '' : 'hidden' ?> p-4 bg-stone-50 rounded-2xl space-y-3 text-xs border border-stone-200">
                                <?php if ($upiData): ?>
                                    <div class="text-center">
                                        <p class="font-bold text-[#18181B] mb-2">Scan &amp; Pay <?= Validator::formatPrice($total) ?></p>
                                        <img src="<?= htmlspecialchars($upiData['qr_image']) ?>" alt="UPI QR" class="w-40 h-40 mx-auto rounded-xl border border-stone-200 shadow-sm bg-white p-1">
                                        <p class="text-[11px] text-stone-500 mt-2 font-mono">UPI VPA: <strong><?= htmlspecialchars($upiData['vpa']) ?></strong></p>
                                    </div>
                                    <div>
                                        <label class="font-bold text-[#18181B] block mb-1">Enter 12-digit UPI UTR / Reference No. *</label>
                                        <input type="text" name="upi_utr_number" placeholder="e.g. 408291028371" class="form-input text-xs font-mono bg-white">
                                        <p class="text-[10px] text-stone-400 mt-1">Available in your GPay / PhonePe payment receipt after transfer</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- 2. Cash on Delivery (COD) -->
                        <?php if (!empty($gateways['cod']['is_enabled'])): ?>
                            <label class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer transition-all gap-2 <?= ($selectedMethod === 'cod') ? 'border-stone-900 bg-stone-50/60 shadow-xs' : 'border-stone-200 hover:border-stone-400' ?>">
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_method" value="cod" <?= ($selectedMethod === 'cod') ? 'checked' : '' ?> onchange="togglePaymentBox('cod')" class="text-[#18181B] focus:ring-[#18181B] flex-shrink-0">
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold text-[#18181B] block">Cash on Delivery (Pay on Delivery)</span>
                                        <span class="text-[10px] text-stone-500">Pay cash or UPI upon delivery of your artwork</span>
                                    </div>
                                </div>
                                <span class="text-[10px] bg-stone-100 text-[#18181B] font-bold px-2 py-0.5 rounded-full border border-stone-200 flex-shrink-0">COD</span>
                            </label>

                            <!-- COD Explanatory Box -->
                            <div id="codBox" class="<?= ($selectedMethod === 'cod') ? '' : 'hidden' ?> p-3.5 bg-stone-50 rounded-2xl text-xs border border-stone-200 text-stone-600">
                                <p class="flex items-center gap-2 font-semibold text-[#18181B] mb-1">
                                    <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span>Museum-Grade Insured Delivery</span>
                                </p>
                                <p class="text-[11px] leading-relaxed">Our courier will hand over your insured art consignment and collect payment via Cash or UPI at your doorstep.</p>
                            </div>
                        <?php endif; ?>

                        <!-- 3. Razorpay Option -->
                        <?php if (!empty($gateways['razorpay']['is_enabled'])): ?>
                            <label class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer transition-all gap-2 <?= ($selectedMethod === 'razorpay') ? 'border-stone-900 bg-stone-50/60 shadow-xs' : 'border-stone-200 hover:border-stone-400' ?>">
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_method" value="razorpay" <?= ($selectedMethod === 'razorpay') ? 'checked' : '' ?> onchange="togglePaymentBox('razorpay')" class="text-[#18181B] focus:ring-[#18181B] flex-shrink-0">
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold text-[#18181B] block">Credit / Debit Card / Netbanking (Razorpay)</span>
                                        <span class="text-[10px] text-stone-500">Visa, Mastercard, RuPay, Amex, Netbanking</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 opacity-80 flex-shrink-0">
                                    <span class="text-[10px] font-bold text-stone-400">VISA / MC</span>
                                </div>
                            </label>

                            <div id="razorpayBox" class="<?= ($selectedMethod === 'razorpay') ? '' : 'hidden' ?> p-3.5 bg-stone-50 rounded-2xl text-xs border border-stone-200 text-stone-600">
                                <p class="text-[11px] leading-relaxed">You will be securely redirected to the Razorpay checkout portal to complete payment via Card or Netbanking.</p>
                            </div>
                        <?php endif; ?>

                        <!-- 4. Direct Bank Transfer -->
                        <?php if (!empty($gateways['bank']['is_enabled'])): ?>
                            <?php $bankInfo = $gateways['bank']; ?>
                            <label class="flex items-center justify-between p-3.5 border rounded-2xl cursor-pointer transition-all gap-2 <?= ($selectedMethod === 'bank') ? 'border-stone-900 bg-stone-50/60 shadow-xs' : 'border-stone-200 hover:border-stone-400' ?>">
                                <div class="flex items-center gap-3 min-w-0">
                                    <input type="radio" name="payment_method" value="bank" <?= ($selectedMethod === 'bank') ? 'checked' : '' ?> onchange="togglePaymentBox('bank')" class="text-[#18181B] focus:ring-[#18181B] flex-shrink-0">
                                    <div class="min-w-0">
                                        <span class="text-xs font-bold text-[#18181B] block">Direct Bank Wire (NEFT / RTGS / IMPS)</span>
                                        <span class="text-[10px] text-stone-500">Transfer directly into JayArt Atelier bank account</span>
                                    </div>
                                </div>
                                <span class="text-[10px] bg-stone-100 text-[#18181B] font-bold px-2 py-0.5 rounded-full border border-stone-200 flex-shrink-0">Bank Wire</span>
                            </label>

                            <!-- Bank Details Expandable Box -->
                            <div id="bankBox" class="<?= ($selectedMethod === 'bank') ? '' : 'hidden' ?> p-4 bg-stone-50 rounded-2xl space-y-2 text-xs border border-stone-200">
                                <p class="font-bold text-[#18181B]">Beneficiary Bank Account Details:</p>
                                <div class="bg-white p-3 rounded-xl border border-stone-200/80 font-mono text-[11px] space-y-1">
                                    <div><span class="text-stone-400">Bank:</span> <strong><?= htmlspecialchars($bankInfo['bank_name'] ?? 'HDFC Bank Ltd') ?></strong></div>
                                    <div><span class="text-stone-400">A/C Name:</span> <strong><?= htmlspecialchars($bankInfo['account_name'] ?? 'JayArt Fine Art Atelier LLP') ?></strong></div>
                                    <div><span class="text-stone-400">A/C No:</span> <strong><?= htmlspecialchars($bankInfo['account_no'] ?? '50200084920194') ?></strong></div>
                                    <div><span class="text-stone-400">IFSC:</span> <strong><?= htmlspecialchars($bankInfo['ifsc_code'] ?? 'HDFC0000240') ?></strong></div>
                                    <?php if (!empty($bankInfo['branch'])): ?>
                                        <div><span class="text-stone-400">Branch:</span> <strong><?= htmlspecialchars($bankInfo['branch']) ?></strong></div>
                                    <?php endif; ?>
                                </div>
                                <p class="text-[10px] text-stone-500">Please mention your order reference upon transferring. We will verify and process dispatch promptly.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- DPDP Act, 2023 Statutory Consent & Terms Notice -->
                    <div class="pt-2">
                        <label class="flex items-start gap-2.5 p-3 rounded-2xl bg-stone-50 border border-stone-200 text-xs text-stone-700 cursor-pointer select-none">
                            <input type="checkbox" name="dpdp_consent" value="1" required checked class="mt-0.5 rounded border-stone-300 text-[#18181B] focus:ring-[#18181B]">
                            <span class="leading-relaxed text-[11px] text-stone-600">
                                I consent to JayArt collecting and processing my digital personal data (name, contact, and shipping address) strictly for order fulfillment, delivery transit via logistics partners, and GST tax invoicing under the <strong>Digital Personal Data Protection (DPDP) Act, 2023</strong>. Read our <a href="<?= SITE_URL ?>/privacy" target="_blank" class="font-bold text-[#18181B] underline hover:text-black">DPDP Privacy Policy</a> and <a href="<?= SITE_URL ?>/terms" target="_blank" class="font-bold text-[#18181B] underline hover:text-black">Terms of Service</a>.
                            </span>
                        </label>
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
    const boxes = {
        'upi': document.getElementById('upiBox'),
        'cod': document.getElementById('codBox'),
        'razorpay': document.getElementById('razorpayBox'),
        'bank': document.getElementById('bankBox')
    };
    for (const key in boxes) {
        if (boxes[key]) {
            if (key === method) {
                boxes[key].classList.remove('hidden');
            } else {
                boxes[key].classList.add('hidden');
            }
        }
    }
}

function getCheckoutActionUrl(path) {
    const clean = path.replace(/^\//, '');
    let base = (typeof window.SITE_URL !== 'undefined' && window.SITE_URL) ? window.SITE_URL.replace(/\/$/, '') : '';
    if (window.location.protocol === 'https:' && base.startsWith('http:')) {
        base = base.replace(/^http:/, 'https:');
    }
    if (base && (base.includes(window.location.host) || base.startsWith('/'))) {
        try {
            const parsed = new URL(base, window.location.origin);
            const prefix = parsed.pathname.replace(/\/$/, '');
            return prefix ? `${prefix}/${clean}` : `/${clean}`;
        } catch (e) {}
    }
    return base ? `${base}/${clean}` : `/${clean}`;
}

function applyCouponCode() {
    const input = document.getElementById('couponCodeInput');
    const code = input ? input.value.trim() : '';
    const msg = document.getElementById('couponMessage');
    if (!code) return;

    const url = getCheckoutActionUrl('cart/apply-coupon');
    fetch(url, {
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

async function removeCheckoutCoupon() {
    let confirmed = true;
    if (typeof showConfirmModal === 'function') {
        confirmed = await showConfirmModal({
            title: 'Remove Coupon',
            message: 'Are you sure you want to remove the applied discount coupon from your order?',
            confirmText: 'Remove Coupon',
            cancelText: 'Keep Coupon',
            type: 'warning'
        });
    }

    if (!confirmed) return;

    const url = getCheckoutActionUrl('cart/remove-coupon');
    fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
    })
    .then(r => r.json())
    .then(data => {
        if (data && data.success) {
            window.location.reload();
        }
    })
    .catch(() => window.location.reload());
}

// Client-side Checkout Form Validation with smooth scroll to empty fields
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        const requiredIds = [
            { id: 'checkout_name', name: 'Full Legal Name' },
            { id: 'checkout_phone', name: 'Mobile Phone' },
            { id: 'checkout_email', name: 'Email Address' },
            { id: 'checkout_address', name: 'Street Address' },
            { id: 'checkout_city', name: 'City' },
            { id: 'checkout_pincode', name: 'Pincode / Postal Code' }
        ];

        let firstMissing = null;

        requiredIds.forEach(item => {
            const input = document.getElementById(item.id);
            if (input) {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500', 'bg-red-50/50');
                    if (!firstMissing) firstMissing = input;
                } else {
                    input.classList.remove('border-red-500', 'bg-red-50/50');
                }
                
                input.addEventListener('input', function() {
                    if (this.value.trim()) {
                        this.classList.remove('border-red-500', 'bg-red-50/50');
                    }
                }, { once: true });
            }
        });

        if (firstMissing) {
            e.preventDefault();
            firstMissing.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstMissing.focus();
        }
    });
});
</script>
