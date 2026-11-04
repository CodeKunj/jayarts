<?php
/**
 * JayArt Admin - Ticket Conversation & Reply View
 */
?>

<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Back & Breadcrumb -->
    <div class="flex items-center justify-between">
        <a href="<?= ADMIN_URL ?>/tickets" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Back to All Tickets</span>
        </a>
        <div class="flex items-center gap-3">
            <form method="POST" action="<?= ADMIN_URL ?>/tickets/status/<?= $ticket['id'] ?>" class="flex items-center gap-2">
                <?= Validator::csrfField() ?>
                <label class="text-xs font-semibold text-slate-500">Status:</label>
                <select name="status" onchange="this.form.submit()" class="text-xs rounded-lg border border-stone-300 py-1.5 px-3 bg-white font-semibold">
                    <option value="open" <?= $ticket['status'] === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="resolved" <?= $ticket['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                    <option value="closed" <?= $ticket['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
            </form>
        </div>
    </div>

    <!-- Ticket Summary Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <h1 class="text-xl font-heading font-bold text-slate-900"><?= htmlspecialchars($ticket['subject']) ?></h1>
                    <span class="font-mono text-xs font-bold px-2 py-0.5 bg-slate-100 text-slate-700 rounded">
                        #<?= htmlspecialchars($ticket['ticket_number']) ?>
                    </span>
                </div>
                <p class="text-xs text-slate-400">Created on <?= date('d M Y, h:i A', strtotime($ticket['created_at'])) ?></p>
            </div>

            <div class="flex items-center gap-2">
                <?php
                $prioBadge = match($ticket['priority']) {
                    'urgent' => 'bg-red-100 text-red-800 border-red-200',
                    'high'   => 'bg-amber-100 text-amber-800 border-amber-200',
                    'medium' => 'bg-blue-100 text-blue-800 border-blue-200',
                    default  => 'bg-slate-100 text-slate-700 border-slate-200',
                };
                ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider border <?= $prioBadge ?>">
                    <?= $ticket['priority'] ?> priority
                </span>

                <?php
                $statusBadge = match($ticket['status']) {
                    'open'        => 'bg-amber-50 text-amber-700 border-amber-300',
                    'in_progress' => 'bg-blue-50 text-blue-700 border-blue-300',
                    'resolved'    => 'bg-emerald-50 text-emerald-700 border-emerald-300',
                    'closed'      => 'bg-slate-100 text-slate-600 border-stone-300',
                    default       => 'bg-slate-50 text-slate-700 border-slate-200',
                };
                ?>
                <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider border <?= $statusBadge ?>">
                    <?= str_replace('_', ' ', $ticket['status']) ?>
                </span>
            </div>
        </div>

        <!-- Meta Details Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 text-xs">
            <div>
                <p class="text-slate-400 uppercase tracking-wider text-[10px] font-bold">Patron Information</p>
                <p class="font-semibold text-slate-800 mt-1"><?= htmlspecialchars($ticket['customer_name']) ?></p>
                <a href="mailto:<?= htmlspecialchars($ticket['customer_email']) ?>" class="text-slate-500 hover:text-slate-900"><?= htmlspecialchars($ticket['customer_email']) ?></a>
            </div>
            <div>
                <p class="text-slate-400 uppercase tracking-wider text-[10px] font-bold">Inquiry Category</p>
                <p class="font-semibold text-slate-800 mt-1 capitalize"><?= str_replace('_', ' ', $ticket['category']) ?></p>
            </div>
            <div>
                <p class="text-slate-400 uppercase tracking-wider text-[10px] font-bold">Linked Order</p>
                <?php if (!empty($ticket['order_number'])): ?>
                    <a href="<?= ADMIN_URL ?>/orders/view/<?= $ticket['order_id'] ?>" class="inline-flex items-center gap-1 font-semibold text-slate-900 hover:underline mt-1">
                        <span>#<?= htmlspecialchars($ticket['order_number']) ?></span>
                        <span class="text-slate-400">(<?= htmlspecialchars($ticket['order_status']) ?>)</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    </a>
                <?php else: ?>
                    <p class="text-slate-400 mt-1">No linked order</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Conversation Thread -->
    <div class="space-y-4">
        <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Conversation Thread</h2>

        <div class="space-y-4">
            <?php foreach ($messages as $msg): ?>
                <?php $isAdmin = ($msg['sender_type'] === 'admin'); ?>
                <div class="flex <?= $isAdmin ? 'justify-end' : 'justify-start' ?>">
                    <div class="max-w-2xl w-full <?= $isAdmin ? 'bg-stone-900 text-stone-100 rounded-2xl rounded-tr-sm p-5 border border-stone-800 shadow' : 'bg-white text-slate-800 rounded-2xl rounded-tl-sm p-5 border border-slate-200 shadow-sm' ?>">
                        <div class="flex items-center justify-between mb-3 text-xs <?= $isAdmin ? 'text-amber-300' : 'text-slate-500' ?>">
                            <div class="flex items-center gap-2">
                                <span class="font-bold"><?= htmlspecialchars($msg['sender_name']) ?></span>
                                <span class="text-[10px] px-2 py-0.5 rounded <?= $isAdmin ? 'bg-amber-400/20 text-amber-200 border border-amber-400/30' : 'bg-slate-100 text-slate-600' ?>">
                                    <?= $isAdmin ? 'Curator Team' : 'Patron' ?>
                                </span>
                            </div>
                            <span class="text-[11px] <?= $isAdmin ? 'text-slate-400' : 'text-slate-400' ?>">
                                <?= date('d M Y, h:i A', strtotime($msg['created_at'])) ?>
                            </span>
                        </div>
                        <div class="text-xs leading-relaxed font-sans whitespace-pre-line">
                            <?= htmlspecialchars($msg['message']) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Admin Reply Composer -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-base font-heading font-bold text-slate-900 mb-1">Post Curator Response</h3>
        <p class="text-xs text-slate-500 mb-4">Your response will appear on the patron's account and will be dispatched to their email address.</p>

        <!-- Canned Quick Responses Dropdown -->
        <div class="mb-4">
            <label class="block text-xs font-semibold text-slate-600 mb-1">Insert Quick Canned Template</label>
            <select id="cannedSelect" onchange="insertCannedResponse(this.value)" class="text-xs rounded-lg border border-stone-300 px-3 py-2 bg-slate-50 w-full sm:w-auto">
                <option value="">-- Choose a standard response --</option>
                <option value="Thank you for reaching out to JayArt Gallery. Your custom painting request has been assigned to our master artists. We are preparing the sketch draft and will share it within 48 hours.">Custom Artwork Draft Notice</option>
                <option value="We are delighted to confirm that your painting has been carefully packed with moisture-resistant archival wrap and handed over to our fine-art courier. You can track live updates using your order tracking link.">Artwork Dispatched Notice</option>
                <option value="Your Certificate of Authenticity (COA), hand-signed by the artist with tamper-evident wax seal, is enclosed inside the custom museum crating.">Certificate of Authenticity Enclosed</option>
                <option value="We sincerely apologize for the transit delay. We have expedited an inquiry with our courier partner and will update you within 24 hours.">Delivery Delay Expedited</option>
                <option value="We are pleased to inform you that your inquiry has been resolved. Please let us know if you require any further assistance with your art collection.">Issue Resolved</option>
            </select>
        </div>

        <form method="POST" action="<?= ADMIN_URL ?>/tickets/reply/<?= $ticket['id'] ?>" class="space-y-4">
            <?= Validator::csrfField() ?>
            <div>
                <textarea id="replyTextarea" name="message" rows="5" required placeholder="Write a thoughtful, respectful response to the art patron..." class="w-full text-xs rounded-xl border border-stone-300 p-4 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 font-sans"></textarea>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-2">
                <div class="flex items-center gap-3">
                    <label class="text-xs font-semibold text-slate-700">Set Status On Reply:</label>
                    <select name="status" class="text-xs rounded-lg border border-stone-300 px-3 py-2 bg-white">
                        <option value="in_progress" <?= $ticket['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                        <option value="resolved" <?= $ticket['status'] === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                        <option value="open">Keep Open</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

                <button type="submit" class="px-6 py-2.5 bg-slate-900 hover:bg-black text-white text-xs font-semibold rounded-xl transition-colors shadow flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    <span>Send Reply to Patron</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function insertCannedResponse(val) {
    if (!val) return;
    const txt = document.getElementById('replyTextarea');
    if (txt) {
        if (txt.value.trim() !== '') {
            txt.value += '\n\n' + val;
        } else {
            txt.value = val;
        }
        txt.focus();
    }
}
</script>

