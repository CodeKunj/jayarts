<?php
/**
 * JayArt Admin SEO Panel & Webmaster Integrations View
 */
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-heading font-bold text-xl text-slate-800">SEO Panel &amp; Webmaster Integrations</h1>
        <p class="text-xs text-slate-400">Manage global meta tags, search engine crawlers, and analytics measurement keys</p>
    </div>
    <a href="<?= SITE_URL ?>/sitemap.xml" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] transition-colors shadow">
        <span>View XML Sitemap</span>
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Left: Global SEO & Robots.txt (Cols 1-7) -->
    <div class="lg:col-span-7 space-y-6">
        
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="font-heading font-bold text-base text-slate-800 pb-3 border-b border-slate-100">
                1. Global Search Engine Optimization (Default)
            </h2>
            <form action="<?= ADMIN_URL ?>/seo/update-global" method="POST" class="space-y-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Global Site Meta Title *</label>
                    <input type="text" name="site_title" required value="<?= htmlspecialchars($seo['site_title'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-medium">
                    <span class="text-[10px] text-slate-400">Used as default title when no individual product or blog title is set.</span>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Default Meta Description</label>
                    <textarea name="default_meta_desc" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white leading-relaxed"><?= htmlspecialchars($seo['default_meta_desc'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Default Meta Keywords</label>
                    <input type="text" name="default_keywords" value="<?= htmlspecialchars($seo['default_keywords'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Robots.txt Crawl Directives</label>
                    <textarea name="robots_txt" rows="4" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-mono leading-relaxed"><?= htmlspecialchars($seo['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /admin/\nSitemap: " . SITE_URL . "/sitemap.xml") ?></textarea>
                    <span class="text-[10px] text-slate-400">Served live dynamically at <code><?= SITE_URL ?>/robots.txt</code></span>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] transition-colors shadow">
                        Save Global SEO Settings
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Right: Third-Party Analytics & Webmaster Tracking Keys (Cols 8-12) -->
    <div class="lg:col-span-5 space-y-6">
        
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="font-heading font-bold text-base text-slate-800 pb-3 border-b border-slate-100">
                2. Webmaster &amp; Analytics Integrations
            </h2>
            <p class="text-xs text-slate-500 leading-relaxed">
                Paste your unique verification and tracking keys. The system auto-injects required tracking tags across all pages with zero code modifications.
            </p>

            <form action="<?= ADMIN_URL ?>/seo/update-analytics" method="POST" class="space-y-4">
                
                <!-- Google Analytics 4 -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-700">Google Analytics (GA4)</label>
                        <span class="text-[10px] text-slate-400 font-mono">Measurement ID</span>
                    </div>
                    <input type="text" name="google_analytics_id" value="<?= htmlspecialchars($analytics['google_analytics_id'] ?? '') ?>" placeholder="e.g. G-ABC123XYZ0" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-mono">
                </div>

                <!-- Google Search Console -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-700">Google Search Console</label>
                        <span class="text-[10px] text-slate-400 font-mono">HTML Tag Code</span>
                    </div>
                    <input type="text" name="google_search_console_tag" value="<?= htmlspecialchars($analytics['google_search_console_tag'] ?? '') ?>" placeholder="e.g. 7qX_AbCdEfGhIjKlMnOpQrStUvWxYz" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-mono">
                </div>

                <!-- Meta Pixel -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-700">Meta Pixel (Facebook)</label>
                        <span class="text-[10px] text-slate-400 font-mono">Pixel ID</span>
                    </div>
                    <input type="text" name="meta_pixel_id" value="<?= htmlspecialchars($analytics['meta_pixel_id'] ?? '') ?>" placeholder="e.g. 123456789012345" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-mono">
                </div>

                <!-- Bing Webmaster Tools -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-700">Bing Webmaster Tools</label>
                        <span class="text-[10px] text-slate-400 font-mono">msvalidate.01</span>
                    </div>
                    <input type="text" name="bing_webmaster_tag" value="<?= htmlspecialchars($analytics['bing_webmaster_tag'] ?? '') ?>" placeholder="e.g. A1B2C3D4E5F6G7H8I9J0K1L2M3N4O5P6" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-mono">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-[#10B981] text-white rounded-xl text-xs font-bold hover:bg-[#B08546] transition-colors shadow">
                        Save Analytics &amp; Webmaster Keys
                    </button>
                </div>
            </form>
        </div>

        <!-- Dynamic Sitemap Helper Card -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-xs space-y-3">
            <div>
                <span class="font-bold text-slate-800 block text-sm">XML Sitemap &amp; Search Engine Indexing</span>
                <p class="text-slate-500 leading-relaxed mt-1">
                    Your sitemap updates in real-time as you add new paintings, mediums, or blog posts. It includes Google Image Sitemap extensions (<code class="text-[10px] bg-slate-200/60 px-1 py-0.5 rounded">&lt;image:image&gt;</code>) for every artwork.
                </p>
            </div>
            
            <div class="space-y-1.5">
                <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Live Sitemap Endpoint</span>
                <code class="font-mono text-[11px] text-[#0F172A] bg-white px-3 py-1.5 rounded-lg border border-slate-200 block truncate">
                    <?= SITE_URL ?>/sitemap.xml
                </code>
            </div>

            <form action="<?= ADMIN_URL ?>/seo/generate-sitemap" method="POST" class="pt-1">
                <button type="submit" class="w-full py-2.5 bg-[#0F172A] hover:bg-black text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Regenerate Physical sitemap.xml</span>
                </button>
            </form>
        </div>

    </div>

</div>

