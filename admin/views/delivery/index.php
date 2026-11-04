<?php
/**
 * JayArt Admin Delivery Tracking View
 */
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-heading font-bold text-xl text-slate-800">Delivery &amp; Transit Milestone Tracker</h1>
        <p class="text-xs text-slate-400">Update courier partnerships, tracking numbers, and delivery milestones</p>
    </div>
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Patron Name</th>
                    <th>Courier Partner</th>
                    <th>Tracking ID</th>
                    <th>Current Status</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($deliveries)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-8 text-xs text-slate-400">
                            No shipments currently recorded.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($deliveries as $d): ?>
                        <tr>
                            <td>
                                <a href="<?= ADMIN_URL ?>/orders/view/<?= (int)$d['order_id'] ?>" class="font-mono font-bold text-[#0F172A] hover:underline">
                                    #<?= htmlspecialchars($d['order_number']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="font-medium text-slate-800 text-xs"><?= htmlspecialchars($d['customer_name']) ?></span>
                            </td>
                            <td>
                                <span class="text-xs text-slate-600 font-semibold"><?= htmlspecialchars($d['courier_name']) ?></span>
                            </td>
                            <td>
                                <code class="font-mono text-xs font-bold text-slate-900 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">
                                    <?= htmlspecialchars($d['tracking_number']) ?>
                                </code>
                            </td>
                            <td>
                                <span class="status-pill status-<?= strtolower(str_replace(' ', '_', $d['current_status'])) ?>">
                                    <?= htmlspecialchars($d['current_status']) ?>
                                </span>
                            </td>
                            <td style="text-align: right;" class="whitespace-nowrap">
                                <button type="button" onclick="openMilestoneModal(<?= (int)$d['id'] ?>, '<?= htmlspecialchars($d['order_number']) ?>')" class="px-2.5 py-1 bg-[#0F172A] text-white rounded text-xs font-bold hover:bg-[#020617] transition-colors">
                                    + Add Milestone
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Milestone Modal -->
<div id="milestoneModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h3 class="font-heading font-bold text-base text-slate-800">Add Milestone for <span id="modalOrderNum" class="font-mono text-[#0F172A]"></span></h3>
            <button type="button" onclick="closeMilestoneModal()" class="text-slate-400 hover:text-slate-700">✕</button>
        </div>

        <form action="<?= ADMIN_URL ?>/delivery/add-milestone" method="POST" class="space-y-4">
            <input type="hidden" name="delivery_id" id="modalDeliveryId" value="">

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Milestone Status Title *</label>
                <select name="status_title" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:bg-white">
                    <option value="Packaging in Armored Wooden Crate">Packaging in Armored Wooden Crate</option>
                    <option value="Dispatched from Gallery">Dispatched from Gallery</option>
                    <option value="Arrived at Hub Sorting Center">Arrived at Hub Sorting Center</option>
                    <option value="Out for Doorstep Delivery">Out for Doorstep Delivery</option>
                    <option value="Delivered & Verified by Patron">Delivered & Verified by Patron</option>
                </select>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Location (City / Hub)</label>
                <input type="text" name="location" placeholder="e.g. Mumbai Logistics Hub" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Milestone Description</label>
                <textarea name="description" rows="2" placeholder="e.g. Artwork cleared security inspection and loaded on express freight..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeMilestoneModal()" class="px-4 py-2 text-xs font-semibold text-slate-600">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617]">
                    Save Milestone
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openMilestoneModal(deliveryId, orderNum) {
    document.getElementById('modalDeliveryId').value = deliveryId;
    document.getElementById('modalOrderNum').textContent = '#' + orderNum;
    const modal = document.getElementById('milestoneModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeMilestoneModal() {
    const modal = document.getElementById('milestoneModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>

