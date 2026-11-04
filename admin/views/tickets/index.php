<?php
/**
 * JayArt Admin - Customer Support Tickets List View
 */
?>

<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-slate-900">Customer Support & Tickets</h1>
            <p class="text-xs text-slate-500 mt-1">Manage inquiries, bespoke painting requests, and order assistance</p>
        </div>
    </div>

    <!-- Quick Stat Pills -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <a href="<?= ADMIN_URL ?>/tickets?status=open" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-amber-400 transition-colors flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Open</p>
                <p class="text-2xl font-bold text-amber-600 mt-1"><?= $counts['open'] ?></p>
            </div>
            <div class="w-10 h-10 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </a>

        <a href="<?= ADMIN_URL ?>/tickets?status=in_progress" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-blue-400 transition-colors flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">In Progress</p>
                <p class="text-2xl font-bold text-blue-600 mt-1"><?= $counts['in_progress'] ?></p>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
            </div>
        </a>

        <a href="<?= ADMIN_URL ?>/tickets?status=resolved" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-emerald-400 transition-colors flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Resolved</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1"><?= $counts['resolved'] ?></p>
            </div>
            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </a>

        <a href="<?= ADMIN_URL ?>/tickets" class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm hover:border-stone-400 transition-colors flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">All Tickets</p>
                <p class="text-2xl font-bold text-slate-800 mt-1"><?= $counts['total'] ?></p>
            </div>
            <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="<?= ADMIN_URL ?>/tickets" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div class="sm:col-span-2">
                <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Search ticket #, name, email or topic..." class="w-full text-xs rounded-lg border border-stone-300 px-3 py-2.5 focus:border-slate-800 focus:ring-1 focus:ring-slate-800">
            </div>
            <div>
                <select name="status" class="w-full text-xs rounded-lg border border-stone-300 px-3 py-2.5 bg-white focus:border-slate-800">
                    <option value="">All Statuses</option>
                    <option value="open" <?= $statusFilter === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="in_progress" <?= $statusFilter === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="resolved" <?= $statusFilter === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                    <option value="closed" <?= $statusFilter === 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
            </div>
            <div class="flex gap-2">
                <select name="category" class="w-full text-xs rounded-lg border border-stone-300 px-3 py-2.5 bg-white focus:border-slate-800">
                    <option value="">All Categories</option>
                    <option value="order_inquiry" <?= $categoryFilter === 'order_inquiry' ? 'selected' : '' ?>>Order Inquiry</option>
                    <option value="custom_painting" <?= $categoryFilter === 'custom_painting' ? 'selected' : '' ?>>Custom Painting</option>
                    <option value="delivery_delay" <?= $categoryFilter === 'delivery_delay' ? 'selected' : '' ?>>Delivery Delay</option>
                    <option value="damaged_artwork" <?= $categoryFilter === 'damaged_artwork' ? 'selected' : '' ?>>Damaged Artwork</option>
                    <option value="payment_issue" <?= $categoryFilter === 'payment_issue' ? 'selected' : '' ?>>Payment Issue</option>
                    <option value="general" <?= $categoryFilter === 'general' ? 'selected' : '' ?>>General</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-xs font-semibold rounded-lg hover:bg-black transition-colors whitespace-nowrap cursor-pointer">Filter</button>
            </div>
        </form>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-6 py-3.5">Ticket #</th>
                        <th class="px-6 py-3.5">Patron</th>
                        <th class="px-6 py-3.5">Subject & Category</th>
                        <th class="px-6 py-3.5">Priority</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5 text-center">Msgs</th>
                        <th class="px-6 py-3.5">Updated</th>
                        <th class="px-6 py-3.5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-stone-100 font-sans">
                    <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-stone-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm font-medium text-slate-600">No support tickets found</p>
                                <p class="text-xs text-slate-400 mt-1">All patron inquiries have been answered or no matching records found.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tickets as $t): ?>
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-slate-900">
                                    <a href="<?= ADMIN_URL ?>/tickets/view/<?= $t['id'] ?>" class="text-slate-900 font-bold hover:underline">
                                        #<?= htmlspecialchars($t['ticket_number']) ?>
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900"><?= htmlspecialchars($t['customer_name']) ?></div>
                                    <div class="text-[11px] text-slate-400"><?= htmlspecialchars($t['customer_email']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800 line-clamp-1"><?= htmlspecialchars($t['subject']) ?></div>
                                    <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mt-0.5">
                                        <?= str_replace('_', ' ', $t['category']) ?>
                                        <?php if (!empty($t['order_number'])): ?>
                                            &bull; Order #<?= htmlspecialchars($t['order_number']) ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $prioBadge = match($t['priority']) {
                                        'urgent' => 'bg-red-100 text-red-800 border-red-200',
                                        'high'   => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'medium' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        default  => 'bg-slate-100 text-slate-700 border-slate-200',
                                    };
                                    ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border <?= $prioBadge ?>">
                                        <?= $t['priority'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $statusBadge = match($t['status']) {
                                        'open'        => 'bg-amber-50 text-amber-700 border-amber-300',
                                        'in_progress' => 'bg-blue-50 text-blue-700 border-blue-300',
                                        'resolved'    => 'bg-emerald-50 text-emerald-700 border-emerald-300',
                                        'closed'      => 'bg-slate-100 text-slate-600 border-stone-300',
                                        default       => 'bg-slate-50 text-slate-700 border-slate-200',
                                    };
                                    ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border <?= $statusBadge ?>">
                                        <?= str_replace('_', ' ', $t['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">
                                        <?= $t['msg_count'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-400 text-[11px] whitespace-nowrap">
                                    <?= date('d M Y, h:i A', strtotime($t['updated_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <a href="<?= ADMIN_URL ?>/tickets/view/<?= $t['id'] ?>" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-900 text-white hover:bg-black rounded-md text-xs font-medium transition-colors shadow-sm cursor-pointer">
                                        <span>Respond</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

