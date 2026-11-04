<?php
/**
 * JayArt Admin Inventory & Audit Log View
 */
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-heading font-bold text-xl text-slate-800">Inventory Management &amp; Stock Audit Trail</h1>
        <p class="text-xs text-slate-400">Monitor stock levels, set reorder alerts, and audit stock history</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Stock Levels Table (Cols 1-7) -->
    <div class="lg:col-span-7 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-heading font-bold text-sm text-slate-800">Catalog Stock Status</h2>
            <span class="text-xs text-slate-400">Total <?= count($paintings) ?> items</span>
        </div>
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Artwork</th>
                        <th>Medium</th>
                        <th>Current Stock</th>
                        <th style="text-align: right;">Adjust</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paintings as $p): ?>
                        <tr>
                            <td>
                                <strong class="font-heading text-slate-900 text-xs block"><?= htmlspecialchars($p['title']) ?></strong>
                                <span class="text-[10px] text-slate-400"><?= htmlspecialchars($p['artist_name']) ?></span>
                            </td>
                            <td>
                                <span class="text-xs text-slate-500"><?= htmlspecialchars($p['medium']) ?></span>
                            </td>
                            <td>
                                <span class="font-bold text-xs <?= ((int)$p['stock_quantity'] <= 1) ? 'text-amber-600 bg-amber-50 px-2 py-0.5 rounded border border-amber-200' : 'text-emerald-700' ?>">
                                    <?= (int)$p['stock_quantity'] ?> units
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <form action="<?= ADMIN_URL ?>/inventory/adjust" method="POST" class="inline-flex items-center gap-1">
                                    <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
                                    <input type="number" name="new_stock" value="<?= (int)$p['stock_quantity'] ?>" class="w-16 px-2 py-1 text-xs border border-slate-200 rounded text-center">
                                    <button type="submit" class="px-2.5 py-1 bg-[#0F172A] text-white rounded text-xs font-bold hover:bg-[#020617]">
                                        Save
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Inventory Audit Trail (Cols 8-12) -->
    <div class="lg:col-span-5 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-heading font-bold text-sm text-slate-800">Recent Inventory Logs</h2>
            <span class="text-xs text-slate-400">Audit Trail</span>
        </div>
        <div class="p-4 space-y-3 max-h-[600px] overflow-y-auto">
            <?php if (empty($logs)): ?>
                <div class="text-xs text-slate-400 text-center py-6">No inventory movements recorded yet.</div>
            <?php else: ?>
                <?php foreach ($logs as $log): 
                    $isDeduction = ((int)$log['quantity_change'] < 0);
                ?>
                    <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs space-y-1">
                        <div class="flex items-center justify-between">
                            <strong class="font-heading text-slate-900"><?= htmlspecialchars($log['product_title']) ?></strong>
                            <span class="font-mono font-bold <?= $isDeduction ? 'text-red-600' : 'text-emerald-600' ?>">
                                <?= $log['quantity_change'] > 0 ? '+' : '' ?><?= (int)$log['quantity_change'] ?>
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-500 flex items-center justify-between">
                            <span><?= htmlspecialchars($log['note'] ?? 'Adjustment') ?></span>
                            <span>Stock: <?= (int)$log['previous_stock'] ?> &rarr; <?= (int)$log['new_stock'] ?></span>
                        </div>
                        <div class="text-[10px] text-slate-400 font-mono">
                            <?= date('d M Y, h:i A', strtotime($log['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

