<?php
/**
 * JayArt Admin Responsive Sidebar Navigation
 * Executive Tech Dark Theme (#0B0F19 Slate with Mint & Emerald Accents)
 */
$adminUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
?>
<aside id="adminSidebar" class="admin-sidebar">
    <!-- Brand / Header -->
    <div class="h-16 flex items-center justify-between px-6 border-b border-[#1E293B]">
        <a href="<?= ADMIN_URL ?>" class="flex items-center gap-2.5" title="JayArt Administration Console">
            <img src="<?= ASSETS_URL ?>/images/logo.svg?v=<?= APP_VERSION ?>" alt="JayArt" class="h-7 w-auto brightness-0 invert">
            <span class="text-[9px] bg-slate-800/80 text-slate-300 border border-slate-700/80 px-2 py-0.5 rounded-full font-mono font-bold uppercase tracking-wider ml-1">Admin</span>
        </a>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 py-4 space-y-0.5">
        
        <a href="<?= ADMIN_URL ?>" class="admin-nav-item <?= ($adminUri === '/admin' || $adminUri === '/admin/') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span>Dashboard</span>
        </a>

        <div class="admin-nav-heading">Catalog &amp; Artworks</div>

        <a href="<?= ADMIN_URL ?>/products" class="admin-nav-item <?= str_contains($adminUri, '/products') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span>Paintings (Products)</span>
        </a>

        <a href="<?= ADMIN_URL ?>/categories" class="admin-nav-item <?= str_contains($adminUri, '/categories') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
            <span>Art Mediums &amp; Categories</span>
        </a>

        <a href="<?= ADMIN_URL ?>/inventory" class="admin-nav-item <?= str_contains($adminUri, '/inventory') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            <span>Inventory &amp; Stock Log</span>
        </a>

        <div class="admin-nav-heading">Operations &amp; Sales</div>

        <a href="<?= ADMIN_URL ?>/orders" class="admin-nav-item <?= str_contains($adminUri, '/orders') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <span>Orders &amp; Invoices</span>
        </a>

        <a href="<?= ADMIN_URL ?>/delivery" class="admin-nav-item <?= str_contains($adminUri, '/delivery') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
            <span>Delivery Tracking</span>
        </a>

        <a href="<?= ADMIN_URL ?>/tickets" class="admin-nav-item <?= str_contains($adminUri, '/tickets') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <span>Support Tickets &amp; Concierge</span>
        </a>

        <div class="admin-nav-heading">Marketing &amp; Growth</div>

        <a href="<?= ADMIN_URL ?>/blog" class="admin-nav-item <?= str_contains($adminUri, '/blog') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            <span>Art Stories (Blog)</span>
        </a>

        <a href="<?= ADMIN_URL ?>/seo" class="admin-nav-item <?= str_contains($adminUri, '/seo') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
            <span>SEO &amp; Webmasters</span>
        </a>

        <a href="<?= ADMIN_URL ?>/coupons" class="admin-nav-item <?= str_contains($adminUri, '/coupons') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            <span>Coupons &amp; Offers</span>
        </a>

        <a href="<?= ADMIN_URL ?>/ads" class="admin-nav-item <?= str_contains($adminUri, '/ads') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
            <span>Poster Ads &amp; Monetization</span>
        </a>

        <div class="admin-nav-heading">Settings &amp; Infrastructure</div>

        <a href="<?= ADMIN_URL ?>/payments" class="admin-nav-item <?= str_contains($adminUri, '/payments') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            <span>Payment Gateways &amp; UPI</span>
        </a>

        <a href="<?= ADMIN_URL ?>/currencies" class="admin-nav-item <?= str_contains($adminUri, '/currencies') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span>Currencies &amp; FX Rates</span>
        </a>

        <a href="<?= ADMIN_URL ?>/mail" class="admin-nav-item <?= str_contains($adminUri, '/mail') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            <span>SMTP Mail Delivery</span>
        </a>

        <a href="<?= ADMIN_URL ?>/settings" class="admin-nav-item <?= str_contains($adminUri, '/settings') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span>Store Configuration</span>
        </a>

        <a href="<?= ADMIN_URL ?>/cache" class="admin-nav-item <?= str_contains($adminUri, '/cache') ? 'active' : '' ?>">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            <span class="flex items-center justify-between flex-1">
                <span>Cache Engine</span>
                <span class="text-[9px] px-1.5 py-0.5 rounded font-mono font-bold <?= Cache::isEnabled() ? 'bg-emerald-500/20 text-emerald-400' : 'bg-slate-700 text-slate-400' ?>">
                    <?= Cache::isEnabled() ? 'FAST' : 'OFF' ?>
                </span>
            </span>
        </a>

    </nav>

    <!-- Logout / Return to Site -->
    <div class="p-3 border-t border-[#1E293B] space-y-1">
        <a href="<?= SITE_URL ?>" target="_blank" class="admin-nav-item m-0">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            <span>View Public Store</span>
        </a>
        <a href="<?= ADMIN_URL ?>/logout" class="admin-nav-item m-0 text-rose-400 hover:text-white hover:bg-rose-950/30">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18" style="width: 18px; height: 18px; min-width: 18px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
            <span>Sign Out</span>
        </a>
    </div>
</aside>
<div id="adminSidebarOverlay" class="fixed inset-0 bg-black/60 backdrop-blur-xs z-950 hidden lg:hidden"></div>
