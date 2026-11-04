<?php
/**
 * JayArt Admin Payment Gateways & UPI Management View
 */

$rzp = $gateways['razorpay'] ?? [];
$upi = $gateways['upi'] ?? [];
$cod = $gateways['cod'] ?? [];
$bank = $gateways['bank'] ?? [];
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-heading font-bold text-xl text-slate-800">Payment Gateways &amp; UPI Configuration</h1>
        <p class="text-xs text-slate-400">Configure online cards, real-time dynamic UPI QR code generator, and manual transfer methods</p>
    </div>
</div>

<form action="<?= ADMIN_URL ?>/payments/update" method="POST" class="space-y-8 max-w-5xl">
    
    <!-- 1. Razorpay Integration Panel -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                    RZP
                </div>
                <div>
                    <h2 class="font-heading font-bold text-base text-slate-800">Razorpay Payment Gateway</h2>
                    <p class="text-xs text-slate-400">Credit Cards, Debit Cards, NetBanking, International Cards</p>
                </div>
            </div>
            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                <input type="checkbox" name="razorpay_enabled" value="1" <?= !empty($rzp['is_enabled']) ? 'checked' : '' ?> class="rounded text-[#0F172A] w-4 h-4">
                <span>Enabled</span>
            </label>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Environment Mode</label>
                <select name="razorpay_mode" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-medium">
                    <option value="test" <?= (($rzp['mode'] ?? 'test') === 'test') ? 'selected' : '' ?>>Test / Sandbox Mode</option>
                    <option value="live" <?= (($rzp['mode'] ?? '') === 'live') ? 'selected' : '' ?>>Live Production</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Razorpay Key ID *</label>
                <input type="text" name="razorpay_key_id" value="<?= htmlspecialchars($rzp['key_id'] ?? '') ?>" placeholder="rzp_test_XXXXXXXXXX" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white">
            </div>
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Razorpay Key Secret *</label>
                <input type="password" name="razorpay_key_secret" value="<?= htmlspecialchars($rzp['key_secret'] ?? '') ?>" placeholder="••••••••••••••••" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white">
            </div>
        </div>
    </div>

    <!-- 2. Direct Dynamic UPI QR Code Panel -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    UPI
                </div>
                <div>
                    <h2 class="font-heading font-bold text-base text-slate-800">Direct Dynamic UPI QR Code Engine</h2>
                    <p class="text-xs text-slate-400">Zero gateway fee • Generates real-time QR code with exact order amount on checkout</p>
                </div>
            </div>
            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                <input type="checkbox" name="upi_enabled" value="1" <?= !empty($upi['is_enabled']) ? 'checked' : '' ?> class="rounded text-[#0F172A] w-4 h-4">
                <span>Enabled</span>
            </label>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">UPI VPA / ID *</label>
                <input type="text" name="upi_vpa" value="<?= htmlspecialchars($upi['upi_vpa'] ?? 'jayartgallery@upi') ?>" placeholder="e.g. yourname@oksbi or paytm-9876543210@paytm" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-[#0F172A] focus:bg-white">
                <span class="text-[10px] text-slate-400">Funds go directly to this UPI bank account with 0% commission.</span>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Payee Business / Merchant Name *</label>
                <input type="text" name="upi_merchant_name" value="<?= htmlspecialchars($upi['upi_merchant_name'] ?? 'JayArt Gallery') ?>" placeholder="e.g. JayArt Gallery" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-medium">
            </div>
        </div>

        <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">Patron Instructions for UPI</label>
            <textarea name="upi_instructions" rows="2" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white"><?= htmlspecialchars($upi['instructions'] ?? 'Scan the dynamic QR code or tap to open Google Pay/PhonePe. Enter your 12-digit UTR reference number upon payment.') ?></textarea>
        </div>
    </div>

    <!-- 3. Cash on Delivery (COD) Panel -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    COD
                </div>
                <div>
                    <h2 class="font-heading font-bold text-base text-slate-800">Cash on Delivery (COD)</h2>
                    <p class="text-xs text-slate-400">Doorstep cash or mobile QR payment on delivery</p>
                </div>
            </div>
            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                <input type="checkbox" name="cod_enabled" value="1" <?= !empty($cod['is_enabled']) ? 'checked' : '' ?> class="rounded text-[#0F172A] w-4 h-4">
                <span>Enabled</span>
            </label>
        </div>

        <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">Extra Handling Charge for COD (Optional, <?= CURRENCY_SYMBOL ?>)</label>
            <input type="number" step="0.01" name="cod_extra_fee" value="<?= htmlspecialchars($cod['extra_fee'] ?? '0.00') ?>" class="w-48 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
        </div>
    </div>

    <!-- 4. Direct Bank Transfer (NEFT/RTGS) Panel -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-5">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                    NEFT
                </div>
                <div>
                    <h2 class="font-heading font-bold text-base text-slate-800">Direct Bank Transfer (NEFT / RTGS / IMPS)</h2>
                    <p class="text-xs text-slate-400">Display official gallery bank accounts for high-value artwork purchases</p>
                </div>
            </div>
            <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-700">
                <input type="checkbox" name="bank_enabled" value="1" <?= !empty($bank['is_enabled']) ? 'checked' : '' ?> class="rounded text-[#0F172A] w-4 h-4">
                <span>Enabled</span>
            </label>
        </div>

        <div>
            <label class="text-xs font-bold text-slate-700 block mb-1">Bank Account Coordinates</label>
            <textarea name="bank_details" rows="4" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white"><?= htmlspecialchars($bank['bank_details'] ?? "Bank Name: State Bank of India\nAccount Name: JayArt Private Limited\nA/C No: 398200192837\nIFSC: SBIN0001234\nBranch: Heritage Arts Branch, Mumbai") ?></textarea>
        </div>
    </div>

    <div>
        <button type="submit" class="px-8 py-3 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] transition-colors shadow-md">
            Save Payment Gateway Settings &rarr;
        </button>
    </div>

</form>

