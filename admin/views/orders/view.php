<?php
/**
 * JayArt Admin Order Detail View
 */
?>

<div class="max-w-5xl mx-auto space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="font-mono text-2xl font-bold text-slate-900">Order #<?= htmlspecialchars($order['order_number']) ?></h1>
                <span class="status-pill status-<?= $order['order_status'] ?>">
                    <?= str_replace('_', ' ', ucfirst($order['order_status'])) ?>
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1">Placed on <?= date('d M Y, h:i A', strtotime($order['created_at'])) ?></p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= ADMIN_URL ?>/orders/invoice/<?= (int)$order['id'] ?>" target="_blank" class="px-4 py-2 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] transition-colors shadow">
                View / Print Tax Invoice
            </a>
            <a href="<?= ADMIN_URL ?>/orders" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
                &larr; Back to Orders
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Left: Items & Delivery Details (Cols 1-8) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Items Table -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <h2 class="font-heading font-bold text-sm text-slate-800 pb-3 border-b border-slate-100 mb-4">
                    Artworks in this Acquisition
                </h2>
                <div class="space-y-4">
                    <?php foreach ($items as $item): ?>
                        <div class="flex items-center justify-between text-xs py-3 border-b border-slate-100 last:border-none">
                            <div>
                                <h3 class="font-heading font-bold text-slate-900 text-sm"><?= htmlspecialchars($item['product_title']) ?></h3>
                                <div class="text-[11px] text-slate-500">
                                    Artist: <?= htmlspecialchars($item['artist_name'] ?? 'Artist') ?> • Framing: <strong class="text-slate-800"><?= htmlspecialchars($item['frame_chosen']) ?></strong>
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono"><?= htmlspecialchars($item['dimensions'] ?? '') ?></div>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-slate-900 block"><?= Validator::formatPrice((float)$item['total_price']) ?></span>
                                <span class="text-[11px] text-slate-400"><?= (int)$item['quantity'] ?> × <?= Validator::formatPrice((float)$item['price']) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="border-t border-slate-200 pt-4 space-y-1.5 text-xs text-right">
                    <div class="text-slate-500">Subtotal: <strong class="text-slate-800"><?= Validator::formatPrice((float)$order['subtotal']) ?></strong></div>
                    <?php if ((float)$order['discount_amount'] > 0): ?>
                        <div class="text-emerald-600">Discount (<?= htmlspecialchars($order['coupon_code'] ?? '') ?>): <strong>- <?= Validator::formatPrice((float)$order['discount_amount']) ?></strong></div>
                    <?php endif; ?>
                    <?php if ((float)($order['tax_amount'] ?? 0.0) > 0.0): ?>
                        <div class="text-slate-500">GST / Tax: <strong><?= Validator::formatPrice((float)$order['tax_amount']) ?></strong></div>
                    <?php endif; ?>
                    <div class="text-slate-500">Delivery Charges: <strong class="text-slate-800"><?= ((float)($order['shipping_fee'] ?? 0.0) <= 0.0) ? '<span class="text-emerald-700 font-bold">FREE</span>' : Validator::formatPrice((float)$order['shipping_fee']) ?></strong></div>
                    <div class="text-base font-bold text-[#0F172A] pt-2 border-t border-slate-100">
                        Total Amount: <?= Validator::formatPrice((float)$order['total_amount']) ?>
                    </div>
                </div>
            </div>

            <!-- Customer Shipping Address -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-3">
                <h2 class="font-heading font-bold text-sm text-slate-800 pb-3 border-b border-slate-100">
                    Destination Shipping Address
                </h2>
                <div class="text-xs text-slate-600 leading-relaxed">
                    <strong class="text-slate-900 font-medium"><?= htmlspecialchars($order['customer_name']) ?></strong> (<?= htmlspecialchars($order['customer_phone']) ?>)<br>
                    <?= htmlspecialchars($shipping['address_line1'] ?? '') ?>, <?= htmlspecialchars($shipping['address_line2'] ?? '') ?><br>
                    <?= htmlspecialchars($shipping['city'] ?? '') ?>, <?= htmlspecialchars($shipping['state'] ?? '') ?> - <?= htmlspecialchars($shipping['postal_code'] ?? '') ?>, <?= htmlspecialchars($shipping['country'] ?? 'India') ?><br>
                    Email: <a href="mailto:<?= htmlspecialchars($order['customer_email']) ?>" class="text-[#0F172A] hover:underline"><?= htmlspecialchars($order['customer_email']) ?></a>
                </div>
                <?php if (!empty($order['notes'])): ?>
                    <div class="p-3 bg-amber-50 rounded-xl text-xs text-amber-900 mt-2">
                        <strong>Patron Note:</strong> <?= htmlspecialchars($order['notes']) ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Right: Status Updater & Payment Verification (Cols 9-12) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Update Order & Payment Status Form -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
                <h2 class="font-heading font-bold text-sm text-slate-800 pb-3 border-b border-slate-100">
                    Status Management
                </h2>
                <form action="<?= ADMIN_URL ?>/orders/status/<?= (int)$order['id'] ?>" method="POST" class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Order Status</label>
                        <select name="order_status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-medium">
                            <option value="pending" <?= ($order['order_status'] === 'pending') ? 'selected' : '' ?>>Pending Review</option>
                            <option value="confirmed" <?= ($order['order_status'] === 'confirmed') ? 'selected' : '' ?>>Confirmed</option>
                            <option value="framing" <?= ($order['order_status'] === 'framing') ? 'selected' : '' ?>>Archival Framing</option>
                            <option value="shipped" <?= ($order['order_status'] === 'shipped') ? 'selected' : '' ?>>Dispatched in Transit</option>
                            <option value="out_for_delivery" <?= ($order['order_status'] === 'out_for_delivery') ? 'selected' : '' ?>>Out for Delivery</option>
                            <option value="delivered" <?= ($order['order_status'] === 'delivered') ? 'selected' : '' ?>>Delivered to Collector</option>
                            <option value="cancelled" <?= ($order['order_status'] === 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-slate-700 block mb-1">Payment Status</label>
                        <select name="payment_status" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-medium">
                            <option value="pending" <?= ($order['payment_status'] === 'pending') ? 'selected' : '' ?>>Pending Verification</option>
                            <option value="paid" <?= ($order['payment_status'] === 'paid') ? 'selected' : '' ?>>Paid (Confirmed)</option>
                            <option value="failed" <?= ($order['payment_status'] === 'failed') ? 'selected' : '' ?>>Payment Failed</option>
                            <option value="refunded" <?= ($order['payment_status'] === 'refunded') ? 'selected' : '' ?>>Refunded</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] transition-colors shadow">
                        Update Status &amp; Notify Patron
                    </button>
                </form>
            </div>

            <!-- Payment Details & Receipt Verification Card -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-3">
                <h2 class="font-heading font-bold text-sm text-slate-800 pb-3 border-b border-slate-100">
                    Payment Verification
                </h2>
                <div class="text-xs space-y-2">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase">Method</span>
                        <strong class="text-slate-900 uppercase"><?= htmlspecialchars($order['payment_method']) ?></strong>
                    </div>

                    <?php if (!empty($order['payment_reference'])): ?>
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase">UPI UTR / Reference ID</span>
                            <code class="font-mono text-xs font-bold text-[#0F172A] bg-slate-50 px-2 py-1 rounded border border-slate-200 block truncate">
                                <?= htmlspecialchars($order['payment_reference']) ?>
                            </code>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($order['payment_receipt'])): ?>
                        <div class="pt-2">
                            <span class="text-slate-400 block text-[10px] uppercase mb-1">Uploaded Payment Screenshot</span>
                            <a href="<?= SITE_URL ?>/<?= htmlspecialchars($order['payment_receipt']) ?>" target="_blank" class="block border border-slate-200 rounded-lg overflow-hidden group">
                                <img src="<?= SITE_URL ?>/<?= htmlspecialchars($order['payment_receipt']) ?>" alt="Receipt" class="w-full h-36 object-cover group-hover:scale-105 transition-transform">
                                <span class="block text-center text-[10px] py-1 bg-slate-50 text-slate-600 font-semibold">Click to View Full Receipt &rarr;</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Delivery Tracking Quick Links -->
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-3">
                <h2 class="font-heading font-bold text-sm text-slate-800 pb-2 border-b border-slate-100">
                    Delivery Tracking
                </h2>
                <div class="text-xs text-slate-600 space-y-1">
                    <div>Courier: <strong class="text-slate-900"><?= htmlspecialchars($tracking['courier_name'] ?? 'Not Assigned') ?></strong></div>
                    <div>Tracking ID: <code class="font-mono text-[11px] text-slate-900 font-bold"><?= htmlspecialchars($tracking['tracking_number'] ?? 'Pending') ?></code></div>
                </div>
                <a href="<?= ADMIN_URL ?>/delivery" class="text-xs font-bold text-[#0F172A] hover:text-[#10B981] block pt-2">
                    Manage Courier Milestones &rarr;
                </a>
            </div>

        </div>

    </div>

</div>

