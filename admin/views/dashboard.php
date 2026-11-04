<?php
/**
 * JayArt Admin Dashboard View
 * Modern Executive Tech Theme
 */
?>

<!-- KPI Stat Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    
    <div class="stat-card">
        <div>
            <span class="text-xs text-slate-500 uppercase tracking-wider font-bold block mb-1">Total Revenue</span>
            <div class="text-2xl font-bold font-heading text-emerald-600">
                <?= Validator::formatPrice($totalRevenue) ?>
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">Paid &amp; verified orders</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-xs">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <span class="text-xs text-slate-500 uppercase tracking-wider font-bold block mb-1">Total Orders</span>
            <div class="text-2xl font-bold font-heading text-slate-900">
                <?= $totalOrders ?>
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">Customer acquisitions</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-xs">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <span class="text-xs text-slate-500 uppercase tracking-wider font-bold block mb-1">Active Paintings</span>
            <div class="text-2xl font-bold font-heading text-slate-900">
                <?= $activePaintings ?>
            </div>
            <span class="text-[11px] text-slate-400 mt-1 block">In gallery catalog</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-xs">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <span class="text-xs text-slate-500 uppercase tracking-wider font-bold block mb-1">Stock &amp; Support</span>
            <div class="text-2xl font-bold font-heading text-amber-600">
                <?= $lowStockCount ?> <span class="text-xs font-normal text-slate-400">Low / <?= $openTicketsCount ?> Tickets</span>
            </div>
            <span class="text-[11px] text-amber-600 font-semibold mt-1 block">Requires attention</span>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shadow-xs">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
    </div>

</div>

<!-- Analytics Sales Chart -->
<div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-xs mb-8">
    <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
        <div>
            <h2 class="font-heading font-extrabold text-base text-slate-900">Acquisition Sales Performance (Past 7 Days)</h2>
            <p class="text-xs text-slate-400 mt-0.5">Daily verified revenue generated across all payment channels</p>
        </div>
        <div class="flex items-center gap-2 text-xs">
            <span class="w-2.5 h-2.5 rounded-full bg-slate-900"></span>
            <span class="font-bold text-slate-700">Revenue (<?= CURRENCY_SYMBOL ?>)</span>
        </div>
    </div>
    <div class="h-64 sm:h-72">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="bg-white border border-slate-200 rounded-2xl shadow-xs overflow-hidden mb-8">
    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="font-heading font-extrabold text-base text-slate-900">Recent Customer Orders</h2>
            <p class="text-xs text-slate-400 mt-0.5">Manage customer orders, track dispatch progress, and view invoices</p>
        </div>
        <a href="<?= ADMIN_URL ?>/orders" class="text-xs font-bold text-slate-900 hover:underline flex items-center gap-1">
            <span>View All Orders</span>
            <span>&rarr;</span>
        </a>
    </div>

    <?php if (empty($recentOrders)): ?>
        <div class="p-8 text-center text-xs text-slate-400 font-medium">
            No orders placed yet. Orders will appear here as soon as patrons check out.
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order Ref</th>
                        <th>Patron</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Date</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $ord): ?>
                        <tr>
                            <td>
                                <a href="<?= ADMIN_URL ?>/orders/view/<?= (int)$ord['id'] ?>" class="font-mono font-bold text-slate-900 hover:underline">
                                    #<?= htmlspecialchars($ord['order_number']) ?>
                                </a>
                            </td>
                            <td>
                                <div class="font-bold text-slate-900"><?= htmlspecialchars($ord['customer_name']) ?></div>
                                <div class="text-[11px] text-slate-400"><?= htmlspecialchars($ord['customer_phone']) ?></div>
                            </td>
                            <td>
                                <span class="font-bold text-slate-900"><?= Validator::formatPrice((float)$ord['total_amount']) ?></span>
                            </td>
                            <td>
                                <span class="text-xs font-bold uppercase text-slate-600"><?= htmlspecialchars($ord['payment_method']) ?></span>
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
                                <?= date('d M, h:i A', strtotime($ord['created_at'])) ?>
                            </td>
                            <td style="text-align: right;">
                                <a href="<?= ADMIN_URL ?>/orders/view/<?= (int)$ord['id'] ?>" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-lg text-xs font-bold transition-colors">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Chart.js Initialization -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('salesChart');
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $chartLabels ?>,
            datasets: [{
                label: 'Daily Revenue',
                data: <?= $chartData ?>,
                borderColor: '#0F172A',
                backgroundColor: 'rgba(15, 23, 42, 0.05)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#2563EB',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 2,
                pointRadius: 4.5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#F1F5F9'
                    },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        callback: function(val) { return '₹ ' + val.toLocaleString(); }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                }
            }
        }
    });
});
</script>
