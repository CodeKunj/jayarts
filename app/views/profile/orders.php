<?php
/**
 * JayArt Order History View
 */
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <div class="flex items-center justify-between pb-6 border-b border-linen mb-8">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-ink">My Artwork Acquisitions</h1>
            <p class="text-xs text-muted mt-1">Order tracking, delivery milestones, and tax invoices</p>
        </div>
        <a href="<?= SITE_URL ?>/profile" class="text-xs font-semibold text-forest hover:text-gold transition-colors">
            &larr; Back to Account
        </a>
    </div>

    <?php if (empty($orders)): ?>
        <div class="bg-white border border-linen rounded-2xl p-12 text-center my-8 max-w-md mx-auto shadow-sm">
            <h3 class="font-serif text-lg font-bold text-ink mb-1">No Orders Yet</h3>
            <p class="text-xs text-muted mb-6">You haven't acquired any paintings yet.</p>
            <a href="<?= SITE_URL ?>/shop" class="btn-primary py-2.5 px-6 rounded-full text-xs">Explore Gallery</a>
        </div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($orders as $ord): ?>
                <div class="bg-white border border-linen rounded-2xl p-5 sm:p-6 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="font-mono text-base font-bold text-forest">#<?= htmlspecialchars($ord['order_number']) ?></span>
                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded bg-canvas border border-linen text-muted">
                                <?= htmlspecialchars($ord['order_status']) ?>
                            </span>
                        </div>
                        <div class="text-xs text-muted">
                            Placed on <?= date('d M Y, h:i A', strtotime($ord['created_at'])) ?> • 
                            Payment: <strong class="text-ink"><?= strtoupper($ord['payment_method']) ?> (<?= ucfirst($ord['payment_status']) ?>)</strong>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-4">
                        <div class="sm:text-right">
                            <span class="text-[10px] text-muted block uppercase">Amount</span>
                            <span class="font-sans font-bold text-base text-forest"><?= Validator::formatPrice((float)$ord['total_amount']) ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="<?= SITE_URL ?>/track/<?= htmlspecialchars($ord['order_number']) ?>" class="btn-secondary py-2 px-3 text-xs rounded-lg font-semibold">
                                Track
                            </a>
                            <a href="<?= SITE_URL ?>/profile/invoice/<?= (int)$ord['id'] ?>" target="_blank" class="btn-primary py-2 px-3 text-xs rounded-lg font-semibold">
                                Invoice
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

