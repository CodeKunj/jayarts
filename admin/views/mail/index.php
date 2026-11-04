<?php
/**
 * JayArt Admin SMTP Mail Settings View
 */
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-heading font-bold text-xl text-slate-800">Advanced SMTP Mail Configuration</h1>
        <p class="text-xs text-slate-400">Configure transactional emails (Order Confirmed, Shipped, Delivered, Support Ticket replies)</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 max-w-5xl">
    
    <!-- SMTP Settings Form (Cols 1-7) -->
    <div class="lg:col-span-7 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
        <h2 class="font-heading font-bold text-base text-slate-800 pb-3 border-b border-slate-100">
            Mail Server Connection
        </h2>

        <form action="<?= ADMIN_URL ?>/mail/update" method="POST" class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-slate-50 border border-slate-200 rounded-xl">
                <div>
                    <strong class="text-xs text-slate-800 block">Enable Custom SMTP</strong>
                    <span class="text-[10px] text-slate-400">When disabled, falls back to native PHP mail()</span>
                </div>
                <label class="cursor-pointer">
                    <input type="checkbox" name="is_enabled" value="1" <?= !empty($smtp['is_enabled']) ? 'checked' : '' ?> class="rounded text-[#0F172A] w-4 h-4">
                </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                    <label class="text-xs font-bold text-slate-700 block mb-1">SMTP Host *</label>
                    <input type="text" name="host" required value="<?= htmlspecialchars($smtp['host'] ?? 'mail.yourdomain.com') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Port</label>
                    <input type="number" name="port" required value="<?= htmlspecialchars($smtp['port'] ?? '465') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white">
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Encryption Protocol</label>
                <select name="encryption" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-medium">
                    <option value="ssl" <?= (($smtp['encryption'] ?? '') === 'ssl') ? 'selected' : '' ?>>SSL (Recommended for Port 465)</option>
                    <option value="tls" <?= (($smtp['encryption'] ?? '') === 'tls') ? 'selected' : '' ?>>TLS (Port 587)</option>
                    <option value="none" <?= (($smtp['encryption'] ?? '') === 'none') ? 'selected' : '' ?>>None (Port 25)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Mailbox Username / Email *</label>
                    <input type="text" name="username" required value="<?= htmlspecialchars($smtp['username'] ?? '') ?>" placeholder="notifications@domain.com" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Mailbox Password *</label>
                    <input type="password" name="password" placeholder="••••••••••••" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                    <span class="text-[10px] text-slate-400">Leave blank to keep existing password</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">From Sender Email</label>
                    <input type="email" name="from_email" required value="<?= htmlspecialchars($smtp['from_email'] ?? 'support@jayart.com') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">From Sender Name</label>
                    <input type="text" name="from_name" required value="<?= htmlspecialchars($smtp['from_name'] ?? 'JayArt Gallery Orders') ?>" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-6 py-2.5 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] transition-colors shadow">
                    Save SMTP Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Send Test Email Tool (Cols 8-12) -->
    <div class="lg:col-span-5 space-y-6">
        
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="font-heading font-bold text-base text-slate-800 pb-3 border-b border-slate-100">
                Live Test Email Utility
            </h2>
            <p class="text-xs text-slate-500 leading-relaxed">
                Send an immediate test message to verify socket handshake, authentication, and inbox delivery.
            </p>

            <form action="<?= ADMIN_URL ?>/mail/test" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Recipient Test Email Address *</label>
                    <input type="email" name="test_email" required placeholder="your.personal@email.com" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>

                <button type="submit" class="w-full py-2.5 bg-[#10B981] text-white rounded-xl text-xs font-bold hover:bg-[#B08546] transition-colors shadow">
                    Dispatch Live Test Email
                </button>
            </form>
        </div>

        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-xs text-slate-600 space-y-2">
            <strong class="font-bold text-slate-800 block">Automated Transactional Emails Active:</strong>
            <ul class="space-y-1 text-[11px] list-disc pl-4 text-slate-500">
                <li>New Customer Salon Welcome</li>
                <li>Order Acquisition Confirmation + Receipt</li>
                <li>Artwork Dispatched &amp; Courier Tracking Link</li>
                <li>Doorstep Delivery Confirmation</li>
                <li>Support Desk Reply Notification</li>
            </ul>
        </div>

    </div>

</div>

