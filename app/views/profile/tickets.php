<?php
/**
 * JayArt Support Tickets Center
 */
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-linen mb-8">
        <div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-ink">Collector Support Desk</h1>
            <p class="text-xs text-muted mt-1">Raise inquiries regarding artwork delivery, framing, or custom commissions</p>
        </div>
        <a href="<?= SITE_URL ?>/profile" class="text-xs font-semibold text-forest hover:text-gold transition-colors">
            &larr; Back to Account
        </a>
    </div>

    <?php if ($flashSuccess = Auth::getFlash('success')): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs mb-6">
            ✓ <?= htmlspecialchars($flashSuccess) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Raise New Ticket Form (Cols 1-5) -->
        <div class="lg:col-span-5 bg-white border border-linen rounded-2xl p-6 shadow-sm">
            <h2 class="font-serif text-base font-bold text-ink pb-3 border-b border-linen mb-4">
                Raise Support Ticket
            </h2>
            <form action="<?= SITE_URL ?>/profile/tickets/create" method="POST" class="space-y-4">
                <?= Validator::csrfField() ?>

                <div>
                    <label class="text-xs font-semibold text-ink block mb-1">Subject *</label>
                    <input type="text" name="subject" required placeholder="e.g. Inquire about custom framing" class="form-input text-xs">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-ink block mb-1">Category</label>
                        <select name="category" class="form-input text-xs">
                            <option value="order_inquiry">Order Inquiry</option>
                            <option value="custom_painting">Custom Commission</option>
                            <option value="delivery_delay">Delivery Status</option>
                            <option value="payment_issue">Payment / Billing</option>
                            <option value="general">General Inquiry</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-ink block mb-1">Priority</label>
                        <select name="priority" class="form-input text-xs">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-ink block mb-1">Message Description *</label>
                    <textarea name="message" required rows="4" placeholder="Provide full details of your request..." class="form-input text-xs"></textarea>
                </div>

                <div>
                    <button type="submit" class="btn-primary w-full py-2.5 rounded-xl text-xs font-bold tracking-wider">
                        Submit Ticket to Curators &rarr;
                    </button>
                </div>
            </form>
        </div>

        <!-- Ticket History (Cols 6-12) -->
        <div class="lg:col-span-7 space-y-4">
            <h2 class="font-serif text-base font-bold text-ink pb-3 border-b border-linen">
                Your Support Tickets
            </h2>

            <?php if (empty($tickets)): ?>
                <div class="bg-white border border-linen rounded-2xl p-8 text-center text-xs text-muted">
                    No support tickets raised yet. If you have any inquiries, use the form on the left!
                </div>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($tickets as $tck): 
                        $badgeClass = match($tck['status']) {
                            'open'        => 'bg-blue-50 text-blue-700 border-blue-200',
                            'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'resolved'    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            default       => 'bg-stone-50 text-stone-700 border-stone-200',
                        };
                    ?>
                        <div class="bg-white border border-linen rounded-xl p-4 shadow-sm flex items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-mono text-xs font-bold text-forest"><?= htmlspecialchars($tck['ticket_number']) ?></span>
                                    <span class="text-[9px] uppercase font-bold px-2 py-0.5 rounded border <?= $badgeClass ?>">
                                        <?= str_replace('_', ' ', $tck['status']) ?>
                                    </span>
                                </div>
                                <h3 class="font-serif text-sm font-bold text-ink">
                                    <a href="<?= SITE_URL ?>/profile/ticket/<?= (int)$tck['id'] ?>" class="hover:text-forest">
                                        <?= htmlspecialchars($tck['subject']) ?>
                                    </a>
                                </h3>
                                <div class="text-[11px] text-muted">
                                    Updated: <?= date('d M Y, h:i A', strtotime($tck['updated_at'] ?? $tck['created_at'])) ?>
                                </div>
                            </div>

                            <a href="<?= SITE_URL ?>/profile/ticket/<?= (int)$tck['id'] ?>" class="btn-secondary py-1.5 px-3 rounded-lg text-xs font-semibold whitespace-nowrap">
                                View Thread &rarr;
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>

