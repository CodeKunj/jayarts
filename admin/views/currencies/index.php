<?php
/**
 * JayArt Administration - Multi-Currency & FX Rates Management
 * Professional Executive SaaS Interface
 */
?>

<div class="space-y-6">
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-heading font-extrabold text-slate-900 tracking-tight">Currencies &amp; FX Exchange Rates</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Configure global collector currencies, exchange conversion rates, and live storefront display.</p>
        </div>
        <button type="button" onclick="openAddCurrencyModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0F172A] hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-xs transition-colors self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>Add New Currency</span>
        </button>
    </div>

    <!-- Stat Overview Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-xs">
            <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider">Base Currency</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-xl sm:text-2xl font-heading font-black text-slate-900"><?= htmlspecialchars($baseCurrency['name'] ?? 'Indian Rupee') ?></span>
                <span class="text-xs font-mono font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">(<?= htmlspecialchars($baseCurrency['code'] ?? 'INR') ?> - <?= htmlspecialchars($baseCurrency['symbol'] ?? '₹') ?>)</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">All catalog prices in database are pegged to this</p>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-xs">
            <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider">Enabled Currencies</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-xl sm:text-2xl font-heading font-black text-slate-900"><?= count(array_filter($currencies, fn($c) => !empty($c['is_enabled']))) ?></span>
                <span class="text-xs text-slate-400">of <?= count($currencies) ?> active</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Available in header currency selector</p>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-xs">
            <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider">Live Conversion Engine</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-xl sm:text-2xl font-heading font-black text-emerald-600">Active</span>
                <span class="text-xs text-slate-400 font-mono">1 INR Base</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Auto-converts catalog prices on visitor switch</p>
        </div>

        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-100 shadow-xs">
            <span class="text-slate-400 text-xs font-semibold block uppercase tracking-wider">Symbol Integrity</span>
            <div class="flex items-baseline gap-2 mt-1">
                <span class="text-xl sm:text-2xl font-heading font-black text-slate-900">100% UTF-8</span>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Protected against numeric corruption &amp; autofill</p>
        </div>
    </div>

    <!-- Currencies Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="font-heading font-bold text-sm sm:text-base text-slate-900">Exchange Rates &amp; Formatting Configuration</h2>
                <p class="text-xs text-slate-400 mt-0.5">Rates represent how much 1 <?= htmlspecialchars($baseCurrency['code'] ?? 'INR') ?> equals in that currency.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Code / Flag</th>
                        <th class="py-3 px-4">Currency Name</th>
                        <th class="py-3 px-4">Symbol</th>
                        <th class="py-3 px-4">Exchange Rate (1 <?= htmlspecialchars($baseCurrency['code'] ?? 'INR') ?> =)</th>
                        <th class="py-3 px-4">Live Preview (₹ 25,000)</th>
                        <th class="py-3 px-4">Position &amp; Decimals</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($currencies as $curr): ?>
                        <tr class="hover:bg-slate-50/60 transition-colors <?= !empty($curr['is_base']) ? 'bg-amber-50/20' : '' ?>">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                <div class="flex items-center gap-2">
                                    <span class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-800 border border-slate-200/60">
                                        <?= htmlspecialchars($curr['code']) ?>
                                    </span>
                                    <?php if (!empty($curr['is_base'])): ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 border border-amber-300/60">
                                            BASE
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                <?= htmlspecialchars($curr['name']) ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 bg-slate-100 rounded-md font-mono font-bold text-slate-900 border border-slate-200/50">
                                    <?= htmlspecialchars($curr['symbol']) ?>
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-900">
                                <?= number_format((float)$curr['exchange_rate'], 6) ?>
                            </td>
                            <td class="py-3.5 px-4 font-mono font-extrabold text-slate-800">
                                <?= Currency::format(25000, $curr['code']) ?>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                <span class="capitalize"><?= htmlspecialchars($curr['symbol_position']) ?></span> (<?= (int)$curr['decimal_places'] ?> dec)
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <?php if (!empty($curr['is_base'])): ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Always Active
                                    </span>
                                <?php else: ?>
                                    <form method="POST" action="<?= ADMIN_URL ?>/currencies/toggle/<?= $curr['id'] ?>" class="inline-block">
                                        <?= Validator::csrfField() ?>
                                        <button type="submit" class="px-2.5 py-1 rounded-full text-[10px] font-bold transition-colors <?= !empty($curr['is_enabled']) ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200 border border-slate-200' ?>">
                                            <?= !empty($curr['is_enabled']) ? 'Enabled' : 'Disabled' ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <button type="button" onclick='openEditCurrencyModal(<?= json_encode($curr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="px-2.5 py-1 text-slate-600 hover:text-black hover:bg-slate-100 rounded-lg font-medium transition-colors">
                                        Edit
                                    </button>
                                    <?php if (empty($curr['is_base'])): ?>
                                        <form method="POST" action="<?= ADMIN_URL ?>/currencies/set-base/<?= $curr['id'] ?>" class="inline-block" data-confirm="Set <?= htmlspecialchars($curr['name']) ?> (<?= htmlspecialchars($curr['code']) ?>) as the store base currency?">
                                            <?= Validator::csrfField() ?>
                                            <button type="submit" class="px-2.5 py-1 text-amber-700 hover:bg-amber-50 rounded-lg font-medium transition-colors" title="Set as Base Currency">
                                                Make Base
                                            </button>
                                        </form>
                                        <form method="POST" action="<?= ADMIN_URL ?>/currencies/delete/<?= $curr['id'] ?>" class="inline-block" data-confirm="Are you sure you want to delete currency <?= htmlspecialchars($curr['code']) ?>?">
                                            <?= Validator::csrfField() ?>
                                            <button type="submit" class="px-2 py-1 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete Currency">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Add Currency -->
<div id="addCurrencyModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="font-heading font-bold text-base text-slate-900">Add New Currency</h3>
            <button type="button" onclick="closeAddCurrencyModal()" class="w-8 h-8 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-black">&times;</button>
        </div>
        <form method="POST" action="<?= ADMIN_URL ?>/currencies/store" class="mt-4 space-y-4">
            <?= Validator::csrfField() ?>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Currency Code (ISO 4217) *</label>
                    <input type="text" name="code" placeholder="e.g. USD, EUR, SGD" maxlength="10" required class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono font-bold uppercase focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Currency Name *</label>
                    <input type="text" name="name" placeholder="e.g. US Dollar" required class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Symbol *</label>
                    <input type="text" name="symbol" placeholder="e.g. $, €, £, ₹" required class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">HTML Entity (Optional)</label>
                    <input type="text" name="html_entity" placeholder="e.g. &#36;, &euro;" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Exchange Rate (1 <?= htmlspecialchars($baseCurrency['code'] ?? 'INR') ?> =) *</label>
                    <input type="number" step="0.000001" name="exchange_rate" value="1.000000" required class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono font-bold focus:ring-2 focus:ring-[#0F172A] bg-white">
                    <p class="text-[10px] text-slate-400 mt-1">e.g. 0.012 for USD if 1 INR = 0.012 USD</p>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Decimal Places</label>
                    <select name="decimal_places" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-[#0F172A] bg-white">
                        <option value="2" selected>2 Decimals (e.g. 19.99)</option>
                        <option value="0">0 Decimals (e.g. 20)</option>
                        <option value="3">3 Decimals (e.g. 19.995)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Symbol Position</label>
                    <select name="symbol_position" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-[#0F172A] bg-white">
                        <option value="before" selected>Before ($ 100)</option>
                        <option value="after">After (100 AED)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Display Sort Order</label>
                    <input type="number" name="sort_order" value="10" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_enabled" value="1" checked class="rounded border-slate-300 text-[#0F172A] focus:ring-[#0F172A]">
                    <span class="text-xs font-medium text-slate-700">Enable immediately in storefront currency selector</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeAddCurrencyModal()" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-[#0F172A] hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-colors">Save Currency</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Edit Currency -->
<div id="editCurrencyModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="font-heading font-bold text-base text-slate-900">Edit Currency &amp; Exchange Rate</h3>
            <button type="button" onclick="closeEditCurrencyModal()" class="w-8 h-8 rounded-full hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-black">&times;</button>
        </div>
        <form id="editCurrencyForm" method="POST" action="" class="mt-4 space-y-4">
            <?= Validator::csrfField() ?>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Currency Code (ISO 4217) *</label>
                    <input type="text" id="edit_code" name="code" required class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono font-bold uppercase focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Currency Name *</label>
                    <input type="text" id="edit_name" name="name" required class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Symbol *</label>
                    <input type="text" id="edit_symbol" name="symbol" required class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">HTML Entity</label>
                    <input type="text" id="edit_html_entity" name="html_entity" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Exchange Rate (1 <?= htmlspecialchars($baseCurrency['code'] ?? 'INR') ?> =) *</label>
                    <input type="number" step="0.000001" id="edit_exchange_rate" name="exchange_rate" required class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono font-bold focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Decimal Places</label>
                    <select id="edit_decimal_places" name="decimal_places" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-[#0F172A] bg-white">
                        <option value="2">2 Decimals</option>
                        <option value="0">0 Decimals</option>
                        <option value="3">3 Decimals</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Symbol Position</label>
                    <select id="edit_symbol_position" name="symbol_position" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 focus:ring-2 focus:ring-[#0F172A] bg-white">
                        <option value="before">Before ($ 100)</option>
                        <option value="after">After (100 AED)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Sort Order</label>
                    <input type="number" id="edit_sort_order" name="sort_order" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 font-mono focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="edit_is_enabled" name="is_enabled" value="1" class="rounded border-slate-300 text-[#0F172A] focus:ring-[#0F172A]">
                    <span class="text-xs font-medium text-slate-700">Enabled on Storefront</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeEditCurrencyModal()" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition-colors">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-[#0F172A] hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-colors">Update Currency</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddCurrencyModal() {
    const m = document.getElementById('addCurrencyModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}

function closeAddCurrencyModal() {
    const m = document.getElementById('addCurrencyModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
}

function openEditCurrencyModal(curr) {
    document.getElementById('editCurrencyForm').action = '<?= ADMIN_URL ?>/currencies/update/' + curr.id;
    document.getElementById('edit_code').value = curr.code || '';
    document.getElementById('edit_name').value = curr.name || '';
    document.getElementById('edit_symbol').value = curr.symbol || '';
    document.getElementById('edit_html_entity').value = curr.html_entity || '';
    document.getElementById('edit_exchange_rate').value = curr.exchange_rate || 1.0;
    document.getElementById('edit_decimal_places').value = curr.decimal_places !== undefined ? curr.decimal_places : 2;
    document.getElementById('edit_symbol_position').value = curr.symbol_position || 'before';
    document.getElementById('edit_sort_order').value = curr.sort_order || 0;
    document.getElementById('edit_is_enabled').checked = !!curr.is_enabled;

    const m = document.getElementById('editCurrencyModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}

function closeEditCurrencyModal() {
    const m = document.getElementById('editCurrencyModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
}
</script>
