<?php
/**
 * JayArt Collector Profile Dashboard
 */
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-linen mb-8">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-ink">Collector Account</h1>
            <p class="text-xs text-muted mt-1">Manage personal details, delivery addresses, and art acquisitions</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= SITE_URL ?>/profile/orders" class="btn-secondary py-2 px-4 text-xs font-semibold rounded-lg">My Orders</a>
            <a href="<?= SITE_URL ?>/profile/wishlist" class="btn-secondary py-2 px-4 text-xs font-semibold rounded-lg">Saved Art</a>
            <a href="<?= SITE_URL ?>/profile/tickets" class="btn-secondary py-2 px-4 text-xs font-semibold rounded-lg">Support Tickets</a>
            <a href="<?= SITE_URL ?>/logout" class="text-xs text-red-600 hover:text-red-700 font-semibold px-2">Sign Out</a>
        </div>
    </div>

    <?php if ($flashSuccess = Auth::getFlash('success')): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs mb-6">
            ✓ <?= htmlspecialchars($flashSuccess) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Profile Info Form (Cols 1-6) -->
        <div class="lg:col-span-6 bg-white border border-linen rounded-2xl p-6 shadow-sm">
            <h2 class="font-serif text-base font-bold text-ink pb-3 border-b border-linen mb-4">
                Personal Information
            </h2>
            <form action="<?= SITE_URL ?>/profile/update" method="POST" class="space-y-4">
                <?= Validator::csrfField() ?>

                <div>
                    <label class="text-xs font-semibold text-ink block mb-1">Full Legal Name</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($user['name']) ?>" class="form-input text-xs">
                </div>

                <div>
                    <label class="text-xs font-semibold text-ink block mb-1">Registered Email Address</label>
                    <input type="email" disabled value="<?= htmlspecialchars($user['email']) ?>" class="form-input text-xs bg-canvas text-muted cursor-not-allowed">
                    <span class="text-[10px] text-muted">Email cannot be changed directly for provenance security.</span>
                </div>

                <div>
                    <label class="text-xs font-semibold text-ink block mb-1">Contact Phone Number</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="+91 98765 43210" class="form-input text-xs">
                </div>

                <div class="pt-2 border-t border-linen">
                    <label class="text-xs font-semibold text-ink block mb-1">Change Password (Leave blank to keep current)</label>
                    <input type="password" name="new_password" placeholder="New secure password" class="form-input text-xs">
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary py-2.5 px-6 rounded-xl text-xs font-bold">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Saved Delivery Addresses (Cols 7-12) -->
        <div class="lg:col-span-6 bg-white border border-linen rounded-2xl p-6 shadow-sm">
            <h2 class="font-serif text-base font-bold text-ink pb-3 border-b border-linen mb-4">
                Saved Shipping Addresses
            </h2>

            <?php if (empty($addresses)): ?>
                <p class="text-xs text-muted mb-4">No shipping addresses saved yet. Addresses are saved automatically during your acquisitions.</p>
            <?php else: ?>
                <div class="space-y-3 mb-6">
                    <?php foreach ($addresses as $addr): ?>
                        <div class="p-3 bg-canvas border border-linen rounded-xl text-xs space-y-0.5">
                            <div class="flex items-center justify-between">
                                <strong class="text-ink"><?= htmlspecialchars($addr['recipient_name']) ?></strong>
                                <?php if ($addr['is_default']): ?>
                                    <span class="text-[9px] bg-forest text-white px-2 py-0.5 rounded font-bold uppercase">Default</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-muted">
                                <?= htmlspecialchars($addr['address_line1']) ?>, <?= htmlspecialchars($addr['city']) ?> - <?= htmlspecialchars($addr['postal_code']) ?>
                            </div>
                            <div class="text-muted font-mono text-[11px]"><?= htmlspecialchars($addr['phone']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="p-4 bg-canvas rounded-xl text-center">
                <span class="text-xs text-muted block mb-2">Need an invoice or certificate reprinted?</span>
                <a href="<?= SITE_URL ?>/profile/orders" class="text-xs font-bold text-forest hover:text-gold">
                    View Acquired Masterworks &rarr;
                </a>
            </div>
        </div>

    </div>

</div>

