<?php
/**
 * JayArt Admin Coupons & Offer Management View
 * Clean, high-performance, Shopcart/Emerald styled administration
 */

$stats = $stats ?? [
    'total_coupons'        => count($coupons),
    'active_coupons'       => 0,
    'total_redemptions'    => 0,
    'est_customer_savings' => 0.00
];
?>

<!-- 1. Header with Title and Create Action -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-heading font-bold text-2xl text-slate-800">Coupons &amp; Offer Management</h1>
        <p class="text-xs text-slate-500 mt-1">Create promotional discount codes, track customer redemptions, and set spending thresholds</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= SITE_URL ?>/offers" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-colors">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            <span>View Public Offers Page</span>
        </a>
        <button type="button" onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0F172A] hover:bg-[#020617] text-white text-xs font-bold rounded-xl shadow-xs transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>Create New Coupon</span>
        </button>
    </div>
</div>

<!-- 2. Performance & Analytics Summary Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Coupons</span>
            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-2xl font-bold text-slate-800"><?= $stats['active_coupons'] ?></span>
            <span class="text-xs text-slate-400">of <?= $stats['total_coupons'] ?> total</span>
        </div>
    </div>

    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Redemptions</span>
            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-2xl font-bold text-slate-800"><?= number_format($stats['total_redemptions']) ?></span>
            <span class="text-xs text-emerald-600 font-medium">orders saved</span>
        </div>
    </div>

    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Est. Collector Savings</span>
            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-xs">
                ₹
            </div>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-2xl font-bold text-slate-800"><?= CURRENCY_SYMBOL ?> <?= number_format($stats['est_customer_savings']) ?></span>
            <span class="text-xs text-slate-400">incentives</span>
        </div>
    </div>

    <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Campaign Status</span>
            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>
        <div class="mt-3 flex items-baseline gap-2">
            <span class="text-sm font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">Spring Festival Live</span>
        </div>
    </div>
</div>

<!-- 3. Coupons Management Table Card -->
<div class="bg-white border border-slate-200/80 rounded-2xl shadow-xs overflow-hidden">
    <!-- Toolbar -->
    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
        <div class="relative flex-1 max-w-sm">
            <input type="text" id="couponSearchInput" placeholder="Filter coupons by code or name..." onkeyup="filterCoupons()" class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#0F172A] text-slate-800">
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
        <div class="text-xs text-slate-500 font-medium">
            Showing <span class="font-bold text-slate-800"><?= count($coupons) ?></span> total vouchers
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse" id="couponsTable">
            <thead>
                <tr class="border-b border-slate-200/80 text-[11px] font-bold text-slate-500 uppercase tracking-wider bg-slate-50/80">
                    <th class="py-3 px-5">Coupon Code</th>
                    <th class="py-3 px-5">Offer / Title</th>
                    <th class="py-3 px-5">Discount</th>
                    <th class="py-3 px-5">Min. Spend</th>
                    <th class="py-3 px-5">Usage Progress</th>
                    <th class="py-3 px-5">Validity / Expiry</th>
                    <th class="py-3 px-5">Status</th>
                    <th class="py-3 px-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs">
                <?php if (empty($coupons)): ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400 font-medium">
                            No coupons created yet. Click "Create New Coupon" to set up your first promo offer!
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($coupons as $c): 
                        $isExpired = !empty($c['expires_at']) && strtotime($c['expires_at']) < strtotime('today');
                        $maxUses = (int)($c['max_uses'] ?? 100);
                        $usesCount = (int)($c['uses_count'] ?? 0);
                        $usagePercent = $maxUses > 0 ? min(100, round(($usesCount / $maxUses) * 100)) : 0;
                    ?>
                        <tr class="hover:bg-slate-50/70 transition-colors coupon-row" data-code="<?= strtolower($c['code']) ?>" data-title="<?= strtolower($c['title'] ?? '') ?>">
                            <!-- Code -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-[#0F172A] bg-emerald-50 px-2.5 py-1 rounded-md border border-dashed border-emerald-300 text-xs tracking-wider">
                                        <?= htmlspecialchars($c['code']) ?>
                                    </span>
                                    <button type="button" onclick="navigator.clipboard.writeText('<?= htmlspecialchars($c['code']) ?>'); alert('Code <?= htmlspecialchars($c['code']) ?> copied!');" title="Copy code" class="text-slate-400 hover:text-slate-700 p-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    </button>
                                </div>
                            </td>

                            <!-- Title & Description -->
                            <td class="py-4 px-5 max-w-xs">
                                <div class="font-bold text-slate-800 truncate"><?= htmlspecialchars($c['title'] ?? $c['code']) ?></div>
                                <div class="text-[11px] text-slate-500 truncate mt-0.5"><?= htmlspecialchars($c['description'] ?? 'Fine art promotional voucher') ?></div>
                            </td>

                            <!-- Discount -->
                            <td class="py-4 px-5">
                                <?php if ($c['discount_type'] === 'percentage'): ?>
                                    <span class="inline-flex items-center gap-1 font-bold text-emerald-700 bg-emerald-100/70 px-2.5 py-0.5 rounded-full text-xs">
                                        <?= (float)$c['discount_value'] ?>% OFF
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 font-bold text-blue-700 bg-blue-100/70 px-2.5 py-0.5 rounded-full text-xs">
                                        Flat <?= CURRENCY_SYMBOL ?> <?= number_format((float)$c['discount_value']) ?> OFF
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Min Spend -->
                            <td class="py-4 px-5 font-medium text-slate-700">
                                <?php if ((float)$c['min_order_amount'] > 0): ?>
                                    <?= CURRENCY_SYMBOL ?> <?= number_format((float)$c['min_order_amount']) ?>
                                <?php else: ?>
                                    <span class="text-slate-400 italic">No Minimum</span>
                                <?php endif; ?>
                            </td>

                            <!-- Usage Progress -->
                            <td class="py-4 px-5 min-w-[140px]">
                                <div class="flex items-center justify-between text-[11px] mb-1 font-semibold text-slate-600">
                                    <span><?= $usesCount ?> / <?= $maxUses ?></span>
                                    <span><?= $usagePercent ?>%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="h-1.5 rounded-full <?= $usagePercent >= 100 ? 'bg-red-500' : 'bg-[#0F172A]' ?>" style="width: <?= $usagePercent ?>%"></div>
                                </div>
                            </td>

                            <!-- Expiry -->
                            <td class="py-4 px-5">
                                <?php if ($isExpired): ?>
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-md">
                                        Expired (<?= date('d M Y', strtotime($c['expires_at'])) ?>)
                                    </span>
                                <?php elseif (!empty($c['expires_at'])): ?>
                                    <span class="text-[11px] text-slate-600 font-medium">
                                        Till <?= date('d M Y', strtotime($c['expires_at'])) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-[11px] text-slate-400 italic">Never Expires</span>
                                <?php endif; ?>
                            </td>

                            <!-- Status Toggle -->
                            <td class="py-4 px-5">
                                <form action="<?= ADMIN_URL ?>/coupons/toggle/<?= $c['id'] ?>" method="POST" class="inline">
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold transition-colors <?= !empty($c['is_active']) ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-200 text-slate-600 hover:bg-slate-300' ?>" title="Click to toggle status">
                                        <span class="w-1.5 h-1.5 rounded-full <?= !empty($c['is_active']) ? 'bg-emerald-600' : 'bg-slate-400' ?>"></span>
                                        <span><?= !empty($c['is_active']) ? 'Active' : 'Inactive' ?></span>
                                    </button>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="py-4 px-5 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Edit Trigger Button -->
                                    <button type="button" 
                                        onclick="openEditModal(<?= htmlspecialchars(json_encode($c), ENT_QUOTES, 'UTF-8') ?>)"
                                        class="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors" title="Edit Coupon">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <form action="<?= ADMIN_URL ?>/coupons/delete/<?= $c['id'] ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete coupon <?= htmlspecialchars($c['code']) ?>?');" class="inline">
                                        <button type="submit" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Delete Coupon">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================== -->
<!-- 4. Create New Coupon Modal Dialog          -->
<!-- ========================================== -->
<div id="createCouponModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-[#0F172A] flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-lg text-slate-800">Create New Coupon Voucher</h3>
                    <p class="text-xs text-slate-500">Configure promotional discount parameters</p>
                </div>
            </div>
            <button type="button" onclick="closeCreateModal()" class="text-slate-400 hover:text-slate-700 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form action="<?= ADMIN_URL ?>/coupons/store" method="POST" class="mt-6 space-y-4">
            <!-- Code & Discount Type -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Coupon Code *</label>
                    <input type="text" name="code" required placeholder="e.g. SUMMER50" style="text-transform:uppercase" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold focus:bg-white focus:outline-none focus:border-[#0F172A]">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Discount Type *</label>
                    <select name="discount_type" id="createDiscountType" onchange="updateDiscountLabel('create')" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-[#0F172A]">
                        <option value="percentage">Percentage Discount (%)</option>
                        <option value="fixed">Fixed Amount Discount (<?= CURRENCY_SYMBOL ?>)</option>
                    </select>
                </div>
            </div>

            <!-- Title -->
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Offer Title</label>
                <input type="text" name="title" placeholder="e.g. Summer Canvas Exclusive" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-[#0F172A]">
            </div>

            <!-- Discount Value & Min Order -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1" id="createDiscountValueLabel">Discount Value (%) *</label>
                    <input type="number" step="0.01" min="0.01" name="discount_value" required placeholder="e.g. 20" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:bg-white focus:outline-none focus:border-[#0F172A]">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Minimum Order (<?= CURRENCY_SYMBOL ?>)</label>
                    <input type="number" step="1" min="0" name="min_order_amount" value="0" placeholder="0 = No minimum" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-[#0F172A]">
                </div>
            </div>

            <!-- Max Uses & Expiry Date -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Max Redemptions Limit</label>
                    <input type="number" min="1" name="max_uses" value="100" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-[#0F172A]">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Expiry Date</label>
                    <input type="date" name="expires_at" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-[#0F172A]">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Offer Terms / Description</label>
                <textarea name="description" rows="2" placeholder="e.g. Valid on all original oil paintings and custom framing." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-[#0F172A]"></textarea>
            </div>

            <!-- Active Checkbox -->
            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded text-[#0F172A] w-4 h-4">
                    <span>Activate coupon immediately for collectors</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-[#0F172A] hover:bg-[#020617] text-white rounded-xl shadow-xs transition-colors">
                    Save &amp; Publish Coupon
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================== -->
<!-- 5. Edit Coupon Modal Dialog                -->
<!-- ========================================== -->
<div id="editCouponModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h3 class="font-heading font-bold text-lg text-slate-800">Edit Coupon Voucher</h3>
                    <p class="text-xs text-slate-500">Update promotional limits and terms</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-700 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="editCouponForm" action="" method="POST" class="mt-6 space-y-4">
            <!-- Code & Discount Type -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Coupon Code *</label>
                    <input type="text" id="editCode" name="code" required style="text-transform:uppercase" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold focus:bg-white focus:outline-none focus:border-[#0F172A]">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Discount Type *</label>
                    <select name="discount_type" id="editDiscountType" onchange="updateDiscountLabel('edit')" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white focus:outline-none focus:border-[#0F172A]">
                        <option value="percentage">Percentage Discount (%)</option>
                        <option value="fixed">Fixed Amount Discount (<?= CURRENCY_SYMBOL ?>)</option>
                    </select>
                </div>
            </div>

            <!-- Title -->
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Offer Title</label>
                <input type="text" id="editTitle" name="title" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-[#0F172A]">
            </div>

            <!-- Discount Value & Min Order -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1" id="editDiscountValueLabel">Discount Value *</label>
                    <input type="number" step="0.01" min="0.01" id="editDiscountValue" name="discount_value" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:bg-white focus:outline-none focus:border-[#0F172A]">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Minimum Order (<?= CURRENCY_SYMBOL ?>)</label>
                    <input type="number" step="1" min="0" id="editMinOrder" name="min_order_amount" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-[#0F172A]">
                </div>
            </div>

            <!-- Max Uses & Expiry Date -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Max Redemptions Limit</label>
                    <input type="number" min="1" id="editMaxUses" name="max_uses" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-[#0F172A]">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Expiry Date</label>
                    <input type="date" id="editExpiresAt" name="expires_at" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-[#0F172A]">
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Offer Terms / Description</label>
                <textarea id="editDescription" name="description" rows="2" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:outline-none focus:border-[#0F172A]"></textarea>
            </div>

            <!-- Active Checkbox -->
            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                    <input type="checkbox" id="editIsActive" name="is_active" value="1" class="rounded text-[#0F172A] w-4 h-4">
                    <span>Active voucher</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 text-xs font-bold bg-[#0F172A] hover:bg-[#020617] text-white rounded-xl shadow-xs transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openCreateModal() {
    document.getElementById('createCouponModal').classList.remove('hidden');
}

function closeCreateModal() {
    document.getElementById('createCouponModal').classList.add('hidden');
}

function openEditModal(coupon) {
    document.getElementById('editCouponForm').action = '<?= ADMIN_URL ?>/coupons/update/' + coupon.id;
    document.getElementById('editCode').value = coupon.code || '';
    document.getElementById('editTitle').value = coupon.title || '';
    document.getElementById('editDescription').value = coupon.description || '';
    document.getElementById('editDiscountType').value = coupon.discount_type || 'percentage';
    document.getElementById('editDiscountValue').value = coupon.discount_value || 0;
    document.getElementById('editMinOrder').value = coupon.min_order_amount || 0;
    document.getElementById('editMaxUses').value = coupon.max_uses || 100;
    document.getElementById('editExpiresAt').value = coupon.expires_at || '';
    document.getElementById('editIsActive').checked = parseInt(coupon.is_active) === 1;

    updateDiscountLabel('edit');
    document.getElementById('editCouponModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editCouponModal').classList.add('hidden');
}

function updateDiscountLabel(prefix) {
    const typeSelect = document.getElementById(prefix + 'DiscountType');
    const label = document.getElementById(prefix + 'DiscountValueLabel');
    if (typeSelect.value === 'percentage') {
        label.textContent = 'Discount Value (%) *';
    } else {
        label.textContent = 'Discount Value (<?= CURRENCY_SYMBOL ?>) *';
    }
}

function filterCoupons() {
    const q = document.getElementById('couponSearchInput').value.toLowerCase().trim();
    const rows = document.querySelectorAll('.coupon-row');
    rows.forEach(r => {
        const code = r.getAttribute('data-code') || '';
        const title = r.getAttribute('data-title') || '';
        if (code.includes(q) || title.includes(q)) {
            r.style.display = '';
        } else {
            r.style.display = 'none';
        }
    });
}
</script>

