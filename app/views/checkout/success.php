<?php
/**
 * JayArt Order Acquisition Celebration & Confirmation
 */

$shipping = $order['shipping_address'] ?? [];
?>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16 text-center">
    
    <!-- Success Celebration Icon -->
    <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto mb-6 shadow-sm animate-bounce">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    </div>

    <span class="text-xs uppercase font-bold text-gold tracking-widest block mb-2">Acquisition Confirmed</span>
    <h1 class="font-display text-3xl sm:text-4xl font-bold text-ink mb-3">
        Thank You for Your Patronage!
    </h1>
    <p class="text-xs sm:text-sm text-muted max-w-md mx-auto leading-relaxed mb-6 font-sans">
        Your order has been recorded. Our master curators are now preparing your artwork with archival museum-grade packaging and authentic documentation.
    </p>

    <!-- Order Number Badge Card -->
    <div class="bg-white border border-linen rounded-2xl p-6 shadow-sm mb-8 text-left">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-linen">
            <div>
                <span class="text-[10px] text-muted uppercase tracking-wider block">Order Reference Number</span>
                <span class="font-mono text-xl font-bold text-forest"><?= htmlspecialchars($order['order_number']) ?></span>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?= SITE_URL ?>/track/<?= htmlspecialchars($order['order_number']) ?>" class="btn-primary py-2 px-4 rounded-lg text-xs font-semibold">
                    Track Artwork Delivery &rarr;
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-xs">
            <div>
                <span class="text-muted block text-[10px] uppercase">Acquisition Date</span>
                <span class="font-medium text-ink"><?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></span>
            </div>
            <div>
                <span class="text-muted block text-[10px] uppercase">Payment Method</span>
                <span class="font-medium text-ink uppercase"><?= htmlspecialchars($order['payment_method']) ?></span>
                <span class="text-[10px] text-emerald-600 font-bold block"><?= ucfirst($order['payment_status']) ?></span>
            </div>
            <div>
                <span class="text-muted block text-[10px] uppercase">Total Amount</span>
                <span class="font-sans font-bold text-base text-forest"><?= Validator::formatPrice((float)$order['total_amount']) ?></span>
            </div>
        </div>
    </div>

    <!-- Acquired Artwork Items -->
    <?php if (!empty($order['items'])): ?>
        <div class="bg-white border border-linen rounded-2xl p-6 shadow-sm mb-8 text-left">
            <h3 class="font-serif text-sm font-bold text-ink uppercase tracking-wider mb-4 pb-2 border-b border-linen">
                Acquired Masterworks
            </h3>
            <div class="space-y-3">
                <?php foreach ($order['items'] as $item): ?>
                    <div class="flex items-center justify-between text-xs py-2 border-b border-linen/50 last:border-none">
                        <div>
                            <span class="font-serif font-bold text-ink block"><?= htmlspecialchars($item['product_title']) ?></span>
                            <span class="text-[11px] text-muted">Framing: <?= htmlspecialchars($item['frame_chosen']) ?> • Qty: <?= (int)$item['quantity'] ?></span>
                        </div>
                        <span class="font-sans font-semibold text-ink">
                            <?= Validator::formatPrice((float)$item['total_price']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Price Breakdown Summary -->
            <div class="border-t border-linen pt-4 mt-3 space-y-1.5 text-xs text-right">
                <div class="text-muted">Subtotal: <strong class="text-ink"><?= Validator::formatPrice((float)$order['subtotal']) ?></strong></div>
                <?php if ((float)$order['discount_amount'] > 0): ?>
                    <div class="text-emerald-700">Discount Applied: <strong>- <?= Validator::formatPrice((float)$order['discount_amount']) ?></strong></div>
                <?php endif; ?>
                <div class="text-muted">Delivery Charges: <strong class="text-ink"><?= ((float)($order['shipping_fee'] ?? 0.0) <= 0.0) ? '<span class="text-emerald-700 font-bold">FREE</span>' : Validator::formatPrice((float)$order['shipping_fee']) ?></strong></div>
                <div class="text-sm font-bold text-forest pt-1 border-t border-linen">
                    Total Paid: <?= Validator::formatPrice((float)$order['total_amount']) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Recipient Shipping Address Preview -->
    <div class="bg-canvas border border-linen rounded-xl p-5 text-left text-xs mb-8">
        <span class="font-serif font-bold text-ink block mb-1">Destination Address:</span>
        <div class="text-muted leading-relaxed">
            <strong><?= htmlspecialchars($order['customer_name']) ?></strong> (<?= htmlspecialchars($order['customer_phone']) ?>)<br>
            <?= htmlspecialchars($shipping['address_line1'] ?? '') ?>, <?= htmlspecialchars($shipping['address_line2'] ?? '') ?><br>
            <?= htmlspecialchars($shipping['city'] ?? '') ?>, <?= htmlspecialchars($shipping['state'] ?? '') ?> - <?= htmlspecialchars($shipping['postal_code'] ?? '') ?>, <?= htmlspecialchars($shipping['country'] ?? 'India') ?>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-center gap-4">
        <a href="<?= SITE_URL ?>" class="btn-primary py-3 px-8 rounded-full text-xs font-bold uppercase tracking-wider">
            Return to Gallery Home
        </a>
        <a href="<?= SITE_URL ?>/profile/invoice/<?= (int)$order['id'] ?>" target="_blank" class="btn-secondary py-3 px-6 rounded-full text-xs font-semibold">
            View / Download Bill
        </a>
        <button type="button" onclick="window.print()" class="btn-secondary py-3 px-6 rounded-full text-xs font-semibold">
            Print Order Receipt
        </button>
    </div>

</div>

