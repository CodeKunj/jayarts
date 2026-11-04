<?php
/**
 * JayArt Visual Delivery Tracking View
 * Stepped Milestone Progress Bar & Courier Tracker
 */

$statuses = [
    'pending'          => ['label' => 'Order Received', 'step' => 1],
    'confirmed'        => ['label' => 'Authentication & Framing', 'step' => 2],
    'shipped'          => ['label' => 'Dispatched in Transit', 'step' => 3],
    'out_for_delivery' => ['label' => 'Out for Delivery', 'step' => 4],
    'delivered'        => ['label' => 'Delivered to Collector', 'step' => 5],
];

$currentStatus = $order['order_status'] ?? 'pending';
$currentStep = $statuses[$currentStatus]['step'] ?? 1;
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    
    <div class="text-center max-w-xl mx-auto mb-10">
        <span class="text-xs uppercase font-bold text-gold tracking-widest block mb-1">Live Transit System</span>
        <h1 class="font-display text-2xl sm:text-3xl font-bold text-ink mb-2">Track Artwork Delivery</h1>
        <p class="text-xs text-muted leading-relaxed">
            Monitor the insured transit of your original paintings and handcrafted frames in real-time.
        </p>

        <!-- Search Form -->
        <form action="<?= SITE_URL ?>/track" method="GET" class="mt-6 flex gap-2 max-w-md mx-auto">
            <input type="text" name="order_number" required placeholder="Enter Order Number (e.g. JA-202611-XXXX)" value="<?= htmlspecialchars($searchQuery ?? '') ?>" class="form-input text-xs font-mono uppercase rounded-xl pl-4">
            <button type="submit" class="btn-primary py-2.5 px-6 rounded-xl text-xs font-bold whitespace-nowrap">
                Track
            </button>
        </form>
    </div>

    <?php if (isset($searchQuery) && !$order): ?>
        <div class="bg-white border border-linen rounded-2xl p-8 text-center shadow-sm">
            <svg class="w-12 h-12 text-muted mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="font-serif text-base font-bold text-ink mb-1">Order Reference Not Found</h3>
            <p class="text-xs text-muted">Please double check the reference code sent to your order confirmation email.</p>
        </div>
    <?php elseif ($order): 
        $tracking = $order['tracking'] ?? null;
    ?>
        
        <!-- Live Tracking Status Card -->
        <div class="bg-white border border-linen rounded-2xl p-6 sm:p-8 shadow-sm space-y-8">
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-linen">
                <div>
                    <span class="text-[10px] text-muted uppercase tracking-wider block">Acquisition Reference</span>
                    <h2 class="font-mono text-xl font-bold text-forest"><?= htmlspecialchars($order['order_number']) ?></h2>
                    <span class="text-xs text-muted">Placed on <?= date('d M Y', strtotime($order['created_at'])) ?></span>
                </div>
                <div class="text-right sm:text-right">
                    <span class="text-[10px] text-muted uppercase tracking-wider block">Courier Partner</span>
                    <strong class="font-medium text-xs text-ink"><?= htmlspecialchars($tracking['courier_name'] ?? 'Fine Art Logistics') ?></strong>
                    <div class="font-mono text-xs text-gold"><?= htmlspecialchars($tracking['tracking_number'] ?? 'Assigned on Dispatch') ?></div>
                </div>
            </div>

            <!-- Stepped Visual Progress Bar -->
            <div class="relative py-4">
                <div class="overflow-hidden h-2 mb-8 text-xs flex rounded-full bg-linen">
                    <div style="width: <?= ($currentStep / 5) * 100 ?>%" class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-forest transition-all duration-700"></div>
                </div>

                <!-- 5 Steps Indicators -->
                <div class="grid grid-cols-5 text-center text-[10px] sm:text-xs">
                    <div class="<?= ($currentStep >= 1) ? 'text-forest font-bold' : 'text-muted' ?>">
                        <div class="w-6 h-6 mx-auto rounded-full mb-1 flex items-center justify-center font-bold text-[10px] <?= ($currentStep >= 1) ? 'bg-forest text-white' : 'bg-linen text-muted' ?>">1</div>
                        <span>Placed</span>
                    </div>
                    <div class="<?= ($currentStep >= 2) ? 'text-forest font-bold' : 'text-muted' ?>">
                        <div class="w-6 h-6 mx-auto rounded-full mb-1 flex items-center justify-center font-bold text-[10px] <?= ($currentStep >= 2) ? 'bg-forest text-white' : 'bg-linen text-muted' ?>">2</div>
                        <span>Framing</span>
                    </div>
                    <div class="<?= ($currentStep >= 3) ? 'text-forest font-bold' : 'text-muted' ?>">
                        <div class="w-6 h-6 mx-auto rounded-full mb-1 flex items-center justify-center font-bold text-[10px] <?= ($currentStep >= 3) ? 'bg-forest text-white' : 'bg-linen text-muted' ?>">3</div>
                        <span>Dispatched</span>
                    </div>
                    <div class="<?= ($currentStep >= 4) ? 'text-forest font-bold' : 'text-muted' ?>">
                        <div class="w-6 h-6 mx-auto rounded-full mb-1 flex items-center justify-center font-bold text-[10px] <?= ($currentStep >= 4) ? 'bg-forest text-white' : 'bg-linen text-muted' ?>">4</div>
                        <span>Out for Delivery</span>
                    </div>
                    <div class="<?= ($currentStep >= 5) ? 'text-forest font-bold' : 'text-muted' ?>">
                        <div class="w-6 h-6 mx-auto rounded-full mb-1 flex items-center justify-center font-bold text-[10px] <?= ($currentStep >= 5) ? 'bg-forest text-white' : 'bg-linen text-muted' ?>">5</div>
                        <span>Delivered</span>
                    </div>
                </div>
            </div>

            <!-- Milestone History -->
            <?php if (!empty($tracking['milestones'])): ?>
                <div class="pt-6 border-t border-linen">
                    <h3 class="font-serif text-sm font-bold text-ink uppercase tracking-wider mb-4">Transit Milestones</h3>
                    <div class="space-y-4">
                        <?php foreach ($tracking['milestones'] as $m): ?>
                            <div class="flex items-start gap-4 text-xs">
                                <div class="w-2.5 h-2.5 rounded-full bg-forest mt-1 flex-shrink-0"></div>
                                <div class="flex-1">
                                    <span class="font-semibold text-ink block"><?= htmlspecialchars($m['status_title']) ?></span>
                                    <span class="text-muted"><?= htmlspecialchars($m['description'] ?? '') ?> <?= !empty($m['location']) ? '• ' . htmlspecialchars($m['location']) : '' ?></span>
                                </div>
                                <span class="font-mono text-[10px] text-muted whitespace-nowrap">
                                    <?= date('d M, h:i A', strtotime($m['milestone_time'])) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    <?php endif; ?>

</div>

