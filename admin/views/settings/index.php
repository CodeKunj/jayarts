<?php
/**
 * JayArt Admin - Store Settings View
 * Executive Obsidian Slate & Emerald Theme
 */
?>

<div class="space-y-6 max-w-5xl">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-slate-900 tracking-tight">Store &amp; Gallery Settings</h1>
            <p class="text-xs text-slate-500 mt-1">Configure gallery identity, customer care channels, currency, taxes, and shipping rates.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Instant System Sync Active
            </span>
        </div>
    </div>

    <form method="POST" action="<?= ADMIN_URL ?>/settings/update" class="space-y-6">
        <?= Validator::csrfField() ?>

        <!-- 1. Gallery & Brand Identity -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-5">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center flex-shrink-0" style="width: 36px; height: 36px; min-width: 36px;">
                        <svg width="18" height="18" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-heading font-bold text-slate-900">Gallery &amp; Brand Identity</h2>
                        <p class="text-xs text-slate-400">Core brand representation displayed in header, invoices, and certificates</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Gallery / Storefront Name</label>
                    <input type="text" name="store_name" value="<?= htmlspecialchars($settings['store_name'] ?? 'JayArt Gallery') ?>" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Brand Tagline / Atelier Descriptor</label>
                    <input type="text" name="store_tagline" value="<?= htmlspecialchars($settings['store_tagline'] ?? 'Curated Original Paintings & Fine Art Masterworks') ?>" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Physical Gallery / Studio Address</label>
                <textarea name="store_address" rows="2" class="w-full text-xs rounded-xl border border-slate-200 p-3 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white font-sans"><?= htmlspecialchars($settings['store_address'] ?? 'Heritage Arts Boulevard, Fort, Mumbai 400001, Maharashtra, India') ?></textarea>
            </div>
        </div>

        <!-- 2. Customer Care & Direct Lines -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-5">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0" style="width: 36px; height: 36px; min-width: 36px;">
                        <svg width="18" height="18" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-heading font-bold text-slate-900">Customer Support &amp; Curatorial Channels</h2>
                        <p class="text-xs text-slate-400">Direct contact channels rendered in live chat widget and collector support tickets</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Support Phone</label>
                    <input type="text" name="store_phone" value="<?= htmlspecialchars($settings['store_phone'] ?? '+91 98765 43210') ?>" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Support Email</label>
                    <input type="email" name="store_email" value="<?= htmlspecialchars($settings['store_email'] ?? 'support@jayart.com') ?>" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">WhatsApp Curatorial Line</label>
                    <input type="text" name="whatsapp_number" value="<?= htmlspecialchars($settings['whatsapp_number'] ?? '919876543210') ?>" placeholder="e.g. 919876543210" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white font-mono">
                    <p class="text-[10px] text-slate-400 mt-1">Powers the 1-click floating WhatsApp live chat</p>
                </div>
            </div>
        </div>

        <!-- 3. Financial, Taxes & Shipping Rules -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-5">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0" style="width: 36px; height: 36px; min-width: 36px;">
                        <svg width="18" height="18" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-heading font-bold text-slate-900">Delivery Charges &amp; Financial Rules</h2>
                        <p class="text-xs text-slate-400">Configure customer delivery charges, complimentary shipping limits, and tax rates</p>
                    </div>
                </div>
            </div>

            <!-- Delivery Permanent Snapshot Reassurance Note -->
            <div class="p-3.5 bg-blue-50/70 border border-blue-200/80 rounded-xl text-xs text-blue-900 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="leading-relaxed">
                    <strong>Permanent Order Snapshot:</strong> The delivery charge configured here is permanently frozen into each customer's order when placed. If you change delivery charges in the future, <u>previous orders and their bills will never change</u>.
                </div>
            </div>

            <!-- Dedicated Delivery Charge & Free Delivery Threshold Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50/80 rounded-xl border border-slate-200 space-y-1">
                    <label class="block text-xs font-bold text-slate-800">
                        Standard Delivery Charge (<?= htmlspecialchars(Currency::cleanSymbol($settings['currency_code'] ?? 'INR', $settings['currency_symbol'] ?? '₹')) ?>)
                    </label>
                    <input type="number" step="0.01" min="0" name="standard_shipping_fee" value="<?= htmlspecialchars($settings['standard_shipping_fee'] ?? '0') ?>" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                    <p class="text-[10px] text-slate-500">
                        Standard delivery fee charged to the customer at checkout. Set to <strong>0</strong> for 100% Free Delivery.
                    </p>
                </div>
                <div class="p-4 bg-slate-50/80 rounded-xl border border-slate-200 space-y-1">
                    <label class="block text-xs font-bold text-slate-800">
                        Free Delivery Minimum Order Amount (<?= htmlspecialchars(Currency::cleanSymbol($settings['currency_code'] ?? 'INR', $settings['currency_symbol'] ?? '₹')) ?>)
                    </label>
                    <input type="number" step="0.01" min="0" name="free_shipping_threshold" value="<?= htmlspecialchars($settings['free_shipping_threshold'] ?? '0') ?>" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                    <p class="text-[10px] text-slate-500">
                        Orders with subtotal at or above this amount get Free Delivery automatically. Set to <strong>0</strong> to disable free threshold.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Currency Code</label>
                    <input type="text" name="currency_code" value="<?= htmlspecialchars($settings['currency_code'] ?? 'INR') ?>" autocomplete="off" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Currency Symbol</label>
                    <input type="text" name="currency_symbol" value="<?= htmlspecialchars(Currency::cleanSymbol($settings['currency_code'] ?? 'INR', $settings['currency_symbol'] ?? '₹')) ?>" autocomplete="off" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 font-mono font-bold focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tax / GST Rate (%)</label>
                    <input type="number" step="0.01" name="tax_percentage" value="<?= htmlspecialchars($settings['tax_percentage'] ?? '0') ?>" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Authenticity &amp; Return Window (Days)</label>
                    <input type="number" name="return_policy_days" value="<?= htmlspecialchars($settings['return_policy_days'] ?? '14') ?>" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 font-mono focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
            </div>
            <div class="pt-1">
                <a href="<?= ADMIN_URL ?>/currencies" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-900 hover:text-emerald-700 hover:underline">
                    <span>Manage All Currencies &amp; Real-Time FX Conversion Rates</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>

        <!-- 4. Social Media & Fine Art Channels -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-5">
            <div class="border-b border-slate-100 pb-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center flex-shrink-0" style="width: 36px; height: 36px; min-width: 36px;">
                        <svg width="18" height="18" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-heading font-bold text-slate-900">Social Media &amp; Curatorial Profiles</h2>
                        <p class="text-xs text-slate-400">Direct profile URLs placed in the storefront footer and artwork share sheets</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Instagram URL</label>
                    <input type="url" name="social_instagram" value="<?= htmlspecialchars($settings['social_instagram'] ?? 'https://instagram.com/jayart') ?>" placeholder="https://instagram.com/yourgallery" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Facebook URL</label>
                    <input type="url" name="social_facebook" value="<?= htmlspecialchars($settings['social_facebook'] ?? 'https://facebook.com/jayart') ?>" placeholder="https://facebook.com/yourgallery" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pinterest Portfolio URL</label>
                    <input type="url" name="social_pinterest" value="<?= htmlspecialchars($settings['social_pinterest'] ?? 'https://pinterest.com/jayart') ?>" placeholder="https://pinterest.com/yourgallery" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">YouTube Art Studio URL</label>
                    <input type="url" name="social_youtube" value="<?= htmlspecialchars($settings['social_youtube'] ?? 'https://youtube.com/@jayart') ?>" placeholder="https://youtube.com/@yourgallery" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
                </div>
            </div>
        </div>

        <!-- 5. Legal Notice & Copyright Note -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h2 class="text-sm font-heading font-bold text-slate-900">Footer Legal Disclaimer &amp; Copyright</h2>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Storefront Copyright Line</label>
                <input type="text" name="custom_footer_text" value="<?= htmlspecialchars($settings['custom_footer_text'] ?? '© 2026 JayArt Gallery. All Rights Reserved. Curated Original Fine Artworks.') ?>" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A] bg-white">
            </div>
        </div>

        <!-- 6. SEO & OpenGraph Social Sharing Direct Access -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center flex-shrink-0" style="width: 36px; height: 36px; min-width: 36px;">
                        <svg width="18" height="18" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-sm font-heading font-bold text-slate-900">SEO &amp; OpenGraph (OG) Social Previews</h2>
                        <p class="text-xs text-slate-400">Manage global meta tags, upload 1200 &times; 630 px WhatsApp / Facebook OG images, and webmaster tags</p>
                    </div>
                </div>
                <a href="<?= ADMIN_URL ?>/seo" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0F172A] hover:bg-black text-white text-xs font-bold rounded-xl transition-all shadow-xs flex-shrink-0">
                    <span>Manage OG Image &amp; SEO</span> &rarr;
                </a>
            </div>
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span>Configure custom social share banners, preview live WhatsApp share cards, and update Google Analytics / Search Console.</span>
                <span class="text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full font-bold text-[10px] uppercase font-mono">OG Active</span>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pt-2">
            <button type="submit" class="px-6 py-3 bg-[#0F172A] hover:bg-[#020617] text-white text-xs font-bold rounded-xl transition-all shadow-xs flex items-center gap-2">
                <svg width="15" height="15" style="width: 15px; height: 15px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Save All Store Settings
            </button>
        </div>
    </form>

    <!-- 6. Database Maintenance & Clean Dummy Data (Danger Zone) -->
    <div class="bg-red-50/60 border border-red-200/80 rounded-2xl p-6 space-y-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-red-600 bg-red-100/80 px-2.5 py-0.5 rounded-full inline-block mb-1.5">Danger Zone</span>
                <h3 class="text-sm font-heading font-bold text-slate-900">Purge Sample &amp; Dummy Data</h3>
                <p class="text-xs text-slate-600 mt-1 max-w-2xl leading-relaxed">
                    Permanently truncate all sample paintings, product images, demo blog articles, mock customer reviews, demo orders, and dummy wishlists from your database.
                    <br><strong class="text-slate-800">Preserved safely:</strong> Your Administrator login, art medium categories, system currencies, and payment settings remain 100% untouched.
                </p>
            </div>
            <form method="POST" action="<?= ADMIN_URL ?>/settings/clean-dummy-data" onsubmit="return confirm('CRITICAL WARNING: This will permanently delete all paintings, blog articles, reviews, and test orders from the database. Are you absolutely sure you want to clean all dummy data?');">
                <?= Validator::csrfField() ?>
                <button type="submit" class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all shadow-xs flex items-center gap-2 whitespace-nowrap cursor-pointer">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    Clean All Dummy Data
                </button>
            </form>
        </div>
    </div>
</div>
