<?php
/**
 * JayArt Admin - Cache Management & Performance Dashboard
 * Executive Obsidian Slate & Emerald Theme
 */

$enabled = !empty($stats['enabled']);
$hitRatio = (float)($stats['hit_ratio'] ?? 0.0);
$totalFiles = (int)($stats['total_files'] ?? 0);
$totalSize = $stats['total_size_formatted'] ?? '0 B';
$hits = (int)($stats['hits'] ?? 0);
$misses = (int)($stats['misses'] ?? 0);
$writes = (int)($stats['writes'] ?? 0);
$deletes = (int)($stats['deletes'] ?? 0);
$modules = $stats['modules_breakdown'] ?? [];
$ttls = $stats['ttls'] ?? [];
$recentKeys = $stats['recent_keys'] ?? [];
?>

<div class="space-y-6 max-w-7xl">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-heading font-bold text-slate-900 tracking-tight">Cache Engine &amp; Performance Telemetry</h1>
            <p class="text-xs text-slate-500 mt-1">High-performance Redis-inspired file caching with atomic file locking, pattern invalidation, and zero server bloat.</p>
        </div>
        <div class="flex items-center gap-3">
            <?php if ($enabled): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Engine Active (Fast Path)
                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    Bypassed (Direct MySQL)
                </span>
            <?php endif; ?>

            <form method="POST" action="<?= ADMIN_URL ?>/cache/toggle" class="m-0">
                <?= Validator::csrfField() ?>
                <button type="submit" class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shadow-xs <?= $enabled ? 'bg-slate-200 text-slate-800 hover:bg-slate-300' : 'bg-emerald-600 text-white hover:bg-emerald-700' ?>">
                    <?= $enabled ? 'Disable Cache' : 'Activate Cache' ?>
                </button>
            </form>
        </div>
    </div>

    <!-- Telemetry KPIs -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Hit Ratio -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Cache Hit Ratio</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600" style="width: 32px; height: 32px; min-width: 32px;">
                    <svg width="18" height="18" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-slate-900 mt-2"><?= $hitRatio ?>%</p>
            <p class="text-[11px] text-slate-400 mt-1"><?= number_format($hits) ?> hits / <?= number_format($hits + $misses) ?> requests</p>
        </div>

        <!-- Total Files -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Active Cache Files</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600" style="width: 32px; height: 32px; min-width: 32px;">
                    <svg width="18" height="18" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7M4 7c0-2 1.5-3 3.5-3h9c2 0 3.5 1 3.5 3M4 7h16"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-slate-900 mt-2"><?= number_format($totalFiles) ?></p>
            <p class="text-[11px] text-blue-600 mt-1 font-medium">Auto-expiring atomic blobs</p>
        </div>

        <!-- Storage Used -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Disk Consumption</span>
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600" style="width: 32px; height: 32px; min-width: 32px;">
                    <svg width="18" height="18" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-slate-900 mt-2"><?= $totalSize ?></p>
            <p class="text-[11px] text-slate-400 mt-1">Quota limit: 150 MB</p>
        </div>

        <!-- Invalidation Operations -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Invalidations &amp; Purges</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600" style="width: 32px; height: 32px; min-width: 32px;">
                    <svg width="18" height="18" style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-slate-900 mt-2"><?= number_format($deletes) ?></p>
            <p class="text-[11px] text-slate-400 mt-1">Writes tracked: <?= number_format($writes) ?></p>
        </div>
    </div>

    <!-- Targeted Purge & Invalidation Center -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-100 gap-2">
            <div>
                <h2 class="text-base font-heading font-bold text-slate-900">Cache Invalidation Actions</h2>
                <p class="text-xs text-slate-500 mt-0.5">Purge individual functional modules or flush all persistent cache storage instantly.</p>
            </div>
            <div class="text-xs text-slate-400 font-mono">
                Storage: <span class="text-slate-700 font-medium"><?= htmlspecialchars($stats['storage_path']) ?></span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
            <!-- Clear Products -->
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/70 flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-800">Products &amp; Catalog</h3>
                    <p class="text-[11px] text-slate-500 mt-1">Invalidates all artwork listings, category filters, search results, and artwork details.</p>
                    <span class="inline-block mt-2 text-[10px] font-mono text-slate-400">Pattern: products:*</span>
                </div>
                <form method="POST" action="<?= ADMIN_URL ?>/cache/clear-products" class="mt-4 mb-0">
                    <?= Validator::csrfField() ?>
                    <button type="submit" class="w-full py-2 px-3 bg-white border border-slate-300 hover:border-slate-400 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-all">
                        Purge Product Cache
                    </button>
                </form>
            </div>

            <!-- Clear Categories -->
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/70 flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-800">Art Mediums &amp; Categories</h3>
                    <p class="text-[11px] text-slate-500 mt-1">Invalidates category counts, mediums list, and category slug mapping.</p>
                    <span class="inline-block mt-2 text-[10px] font-mono text-slate-400">Pattern: categories:*</span>
                </div>
                <form method="POST" action="<?= ADMIN_URL ?>/cache/clear-categories" class="mt-4 mb-0">
                    <?= Validator::csrfField() ?>
                    <button type="submit" class="w-full py-2 px-3 bg-white border border-slate-300 hover:border-slate-400 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-all">
                        Purge Category Cache
                    </button>
                </form>
            </div>

            <!-- Clear Homepage -->
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/70 flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-800">Homepage Bento &amp; Deals</h3>
                    <p class="text-[11px] text-slate-500 mt-1">Invalidates featured masterworks, published art stories preview, and coupon vouchers banner.</p>
                    <span class="inline-block mt-2 text-[10px] font-mono text-slate-400">Pattern: home:*</span>
                </div>
                <form method="POST" action="<?= ADMIN_URL ?>/cache/clear-home" class="mt-4 mb-0">
                    <?= Validator::csrfField() ?>
                    <button type="submit" class="w-full py-2 px-3 bg-white border border-slate-300 hover:border-slate-400 hover:bg-slate-50 text-slate-700 rounded-lg text-xs font-semibold transition-all">
                        Purge Homepage Cache
                    </button>
                </form>
            </div>

            <!-- Clear All -->
            <div class="p-4 rounded-xl border border-rose-100 bg-rose-50/50 flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-bold text-rose-900">Flush All Cache Storage</h3>
                    <p class="text-[11px] text-rose-700/80 mt-1">Deletes all cached files and resets runtime metrics. Clean slate reset.</p>
                    <span class="inline-block mt-2 text-[10px] font-mono text-rose-500">Pattern: * (Complete Flush)</span>
                </div>
                <form method="POST" action="<?= ADMIN_URL ?>/cache/clear-all" class="mt-4 mb-0" data-confirm="Are you sure you want to flush all cache files from disk? Cache will regenerate automatically as users visit pages." data-confirm-title="Flush Cache" data-confirm-btn="Flush Entire Cache">
                    <?= Validator::csrfField() ?>
                    <button type="submit" class="w-full py-2 px-3 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold transition-all shadow-xs">
                        Flush Entire Cache
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- TTL Configuration & Security Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- TTL Form -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-xs p-6">
            <h2 class="text-base font-heading font-bold text-slate-900 mb-1">Time-To-Live (TTL) Configurations</h2>
            <p class="text-xs text-slate-500 mb-5">Configure cache duration per functional module. Changes take effect on next cache generation.</p>

            <form method="POST" action="<?= ADMIN_URL ?>/cache/update-ttls" class="space-y-4">
                <?= Validator::csrfField() ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Product Details &amp; Catalog TTL (seconds)</label>
                        <input type="number" name="product_ttl" value="<?= (int)($ttls['product'] ?? 1800) ?>" min="60" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:border-slate-800">
                        <span class="text-[10px] text-slate-400 mt-1 block">Default: 1800 (30 minutes)</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Categories &amp; Mediums TTL (seconds)</label>
                        <input type="number" name="category_ttl" value="<?= (int)($ttls['category'] ?? 86400) ?>" min="60" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:border-slate-800">
                        <span class="text-[10px] text-slate-400 mt-1 block">Default: 86400 (24 hours)</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Homepage Bento Grid TTL (seconds)</label>
                        <input type="number" name="homepage_ttl" value="<?= (int)($ttls['homepage'] ?? 900) ?>" min="60" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:border-slate-800">
                        <span class="text-[10px] text-slate-400 mt-1 block">Default: 900 (15 minutes)</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Search Results TTL (seconds)</label>
                        <input type="number" name="search_ttl" value="<?= (int)($ttls['search'] ?? 600) ?>" min="60" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:outline-none focus:border-slate-800">
                        <span class="text-[10px] text-slate-400 mt-1 block">Default: 600 (10 minutes)</span>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-5 py-2.5 bg-[#0F172A] hover:bg-[#1E293B] text-white rounded-xl text-xs font-bold transition-all shadow-xs">
                        Save TTL Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- Security & Authoritative Check Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <h2 class="text-base font-heading font-bold text-slate-900">Security &amp; Integrity</h2>

            <div class="space-y-3">
                <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">✓</div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-800">Filesystem Access Guard</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Storage directory is protected with .htaccess <code>Deny from all</code> and index.php HTTP 403 response.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">✓</div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-800">Authoritative MySQL Stock</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Checkout, cart, orders, and live inventory operations bypass cache and read directly from MySQL via PDO.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">✓</div>
                    <div>
                        <h3 class="text-xs font-bold text-slate-800">Concurrency &amp; Locking</h3>
                        <p class="text-[11px] text-slate-500 mt-0.5">Shared read locks (LOCK_SH) and atomic tempfile writes with exclusive locks (LOCK_EX) prevent race conditions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Keys Preview Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-heading font-bold text-slate-900">Active Cached Keys (Sample)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Real-time inspection of active cache entries stored in the filesystem.</p>
            </div>
            <span class="text-xs font-mono font-semibold text-slate-500"><?= count($recentKeys) ?> keys shown</span>
        </div>

        <?php if (!empty($recentKeys)): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-[11px] uppercase tracking-wider font-semibold text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3">Module</th>
                            <th class="px-6 py-3">Internal Cache Key</th>
                            <th class="px-6 py-3">TTL Remaining</th>
                            <th class="px-6 py-3">Blob Size</th>
                            <th class="px-6 py-3">Created</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($recentKeys as $rk): ?>
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-3 font-semibold text-slate-900 capitalize"><?= htmlspecialchars($rk['module']) ?></td>
                                <td class="px-6 py-3 font-mono text-[11px] text-slate-700 max-w-xs truncate" title="<?= htmlspecialchars($rk['key']) ?>"><?= htmlspecialchars($rk['key']) ?></td>
                                <td class="px-6 py-3 font-mono text-slate-600"><?= htmlspecialchars((string)$rk['ttl_left']) ?></td>
                                <td class="px-6 py-3 font-mono text-slate-600"><?= htmlspecialchars($rk['size']) ?></td>
                                <td class="px-6 py-3 text-slate-500"><?= htmlspecialchars($rk['created_at']) ?></td>
                                <td class="px-6 py-3">
                                    <?php if ($rk['is_expired']): ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-800">Expired</span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">Fresh</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="p-8 text-center text-slate-400 text-xs">
                No active cache files currently in storage. Cache entries will generate on demand as pages are requested.
            </div>
        <?php endif; ?>
    </div>
</div>

