<?php
/**
 * JayArt Admin Orders List View
 */

$statuses = ['', 'pending', 'confirmed', 'framing', 'shipped', 'delivered', 'cancelled'];
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-heading font-bold text-xl text-slate-800">Orders &amp; Acquisitions</h1>
        <p class="text-xs text-slate-400">Total <?= count($orders) ?> customer orders recorded</p>
    </div>
</div>

<!-- Status Filter Tabs -->
<div class="flex items-center gap-2 overflow-x-auto pb-4 mb-4">
    <?php foreach ($statuses as $st): 
        $label = empty($st) ? 'All Orders' : ucfirst(str_replace('_', ' ', $st));
        $isActive = ($statusFilter === $st);
    ?>
        <a href="<?= ADMIN_URL ?>/orders<?= !empty($st) ? '?status=' . $st : '' ?>" 
           class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-colors <?= $isActive ? 'bg-[#0F172A] text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
            <?= $label ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order Ref</th>
                    <th>Patron Name</th>
                    <th>Total</th>
                    <th>Payment Method</th>
                    <th>Payment Status</th>
                    <th>Order Status</th>
                    <th>Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-8 text-xs text-slate-400">
                            No orders found matching this status filter.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $ord): ?>
                        <tr>
                            <td>
                                <a href="<?= ADMIN_URL ?>/orders/view/<?= (int)$ord['id'] ?>" class="font-mono font-bold text-[#0F172A] hover:underline">
                                    #<?= htmlspecialchars($ord['order_number']) ?>
                                </a>
                            </td>
                            <td>
                                <div class="font-medium text-slate-800 text-xs"><?= htmlspecialchars($ord['customer_name']) ?></div>
                                <div class="text-[10px] text-slate-400"><?= htmlspecialchars($ord['customer_email']) ?></div>
                            </td>
                            <td>
                                <span class="font-sans font-bold text-slate-900 text-xs"><?= Validator::formatPrice((float)$ord['total_amount']) ?></span>
                            </td>
                            <td>
                                <span class="text-xs uppercase font-semibold text-slate-600"><?= htmlspecialchars($ord['payment_method']) ?></span>
                                <?php if (!empty($ord['payment_reference'])): ?>
                                    <div class="font-mono text-[10px] text-slate-400">Ref: <?= htmlspecialchars($ord['payment_reference']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-pill status-<?= $ord['payment_status'] ?>">
                                    <?= ucfirst($ord['payment_status']) ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-pill status-<?= $ord['order_status'] ?>">
                                    <?= str_replace('_', ' ', ucfirst($ord['order_status'])) ?>
                                </span>
                            </td>
                            <td class="text-xs text-slate-500 whitespace-nowrap">
                                <?= date('d M Y, h:i A', strtotime($ord['created_at'])) ?>
                            </td>
                            <td style="text-align: right;" class="whitespace-nowrap">
                                <a href="<?= ADMIN_URL ?>/orders/view/<?= (int)$ord['id'] ?>" class="px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xs font-semibold mr-1">
                                    Manage
                                </a>
                                <a href="<?= ADMIN_URL ?>/orders/invoice/<?= (int)$ord['id'] ?>" target="_blank" class="px-2.5 py-1 bg-[#0F172A]/10 hover:bg-[#0F172A]/20 text-[#0F172A] rounded text-xs font-semibold">
                                    Invoice
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

