<?php
/**
 * JayArt Admin SEO Panel & Webmaster Integrations View
 */

$currentOg = !empty($seo['default_og_image']) ? $seo['default_og_image'] : ASSETS_URL . '/images/og-default.jpg';
$currentOgUrl = (str_starts_with($currentOg, 'http://') || str_starts_with($currentOg, 'https://'))
    ? $currentOg 
    : SITE_URL . '/' . ltrim($currentOg, '/');
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-heading font-bold text-xl text-slate-800">SEO Panel &amp; Webmaster Integrations</h1>
        <p class="text-xs text-slate-400">Manage global meta tags, OpenGraph social previews, search engine crawlers, and analytics measurement keys</p>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= SITE_URL ?>/favicon.ico" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-50 transition-colors shadow-2xs">
            <img src="<?= ASSETS_URL ?>/images/favicon.svg" alt="Favicon" class="w-4 h-4">
            <span>Favicon</span>
        </a>
        <a href="<?= SITE_URL ?>/sitemap.xml" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] transition-colors shadow">
            <span>View XML Sitemap</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Left: Global SEO, OG Image & Robots.txt (Cols 1-7) -->
    <div class="lg:col-span-7 space-y-6">
        
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h2 class="font-heading font-bold text-base text-slate-800 pb-3 border-b border-slate-100 flex items-center justify-between">
                <span>1. Global Search Engine Optimization (Default)</span>
                <span class="text-[10px] font-mono text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full font-bold">OG Active</span>
            </h2>
            <form action="<?= ADMIN_URL ?>/seo/update-global" method="POST" enctype="multipart/form-data" class="space-y-4">
                <?= Validator::csrfInput() ?>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Global Site Meta Title *</label>
                    <input type="text" name="site_title" id="ogTitleField" required value="<?= htmlspecialchars($seo['site_title'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-medium">
                    <span class="text-[10px] text-slate-400">Used as default title when no individual product or blog title is set.</span>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Default Meta Description</label>
                    <textarea name="default_meta_desc" id="ogDescField" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white leading-relaxed"><?= htmlspecialchars($seo['default_meta_desc'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Default Meta Keywords</label>
                    <input type="text" name="default_keywords" value="<?= htmlspecialchars($seo['default_keywords'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>

                <!-- OpenGraph Social Share Image (1200x630) -->
                <div class="pt-3 border-t border-slate-100 space-y-3">
                    <div>
                        <label class="text-xs font-bold text-slate-800 block mb-0.5">
                            OpenGraph Social Share Image (OG Image)
                        </label>
                        <span class="text-[10px] text-slate-400 block">
                            Displayed when links to your store are shared on WhatsApp, Facebook, iMessage, LinkedIn, or Twitter/X (Recommended size: 1200 &times; 630 px).
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-start">
                        <!-- Preview Thumbnail -->
                        <div class="sm:col-span-5 relative group overflow-hidden rounded-xl border border-slate-200 bg-slate-900 aspect-video shadow-xs">
                            <img id="ogPreviewImg" src="<?= htmlspecialchars($currentOgUrl) ?>" alt="OG Preview" class="w-full h-full object-cover">
                            <div class="absolute bottom-1.5 left-1.5 bg-black/75 backdrop-blur-xs px-2 py-0.5 rounded text-[9px] font-mono font-bold text-white">
                                1200 &times; 630
                            </div>
                        </div>

                        <!-- Upload & URL Controls -->
                        <div class="sm:col-span-7 space-y-2.5 text-xs">
                            <div>
                                <label class="text-[11px] font-semibold text-slate-700 block mb-1">Upload New OG Image File</label>
                                <input type="file" name="og_image_file" id="ogFileInput" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="w-full text-xs text-slate-500 file:mr-2.5 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                            </div>
                            
                            <div>
                                <label class="text-[11px] font-semibold text-slate-700 block mb-1">Or Enter Custom Image URL / Path</label>
                                <input type="text" name="default_og_image" id="ogUrlInput" value="<?= htmlspecialchars($seo['default_og_image'] ?? '') ?>" placeholder="e.g. assets/images/og-default.jpg" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono focus:bg-white">
                            </div>

                            <button type="button" onclick="document.getElementById('ogUrlInput').value='assets/images/og-default.jpg'; document.getElementById('ogPreviewImg').src='<?= ASSETS_URL ?>/images/og-default.jpg'; document.getElementById('ogSimImg').src='<?= ASSETS_URL ?>/images/og-default.jpg';" class="text-[10px] text-amber-800 hover:text-amber-950 font-bold inline-flex items-center gap-1 cursor-pointer">
                                <span>↺ Reset to Default Atelier Card</span>
                            </button>
                        </div>
                    </div>

                    <!-- Live WhatsApp / Facebook Preview Simulation Card -->
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Live WhatsApp / Social Link Preview</span>
                        <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-2xs max-w-sm">
                            <div class="aspect-video bg-slate-900 overflow-hidden">
                                <img id="ogSimImg" src="<?= htmlspecialchars($currentOgUrl) ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="p-3">
                                <span class="text-[9px] font-mono text-slate-400 uppercase tracking-wider"><?= parse_url(SITE_URL, PHP_URL_HOST) ?: 'jayarts.ritiverse.in' ?></span>
                                <div id="ogSimTitle" class="font-bold text-xs text-slate-800 truncate mt-0.5"><?= htmlspecialchars($seo['site_title'] ?? APP_NAME) ?></div>
                                <div id="ogSimDesc" class="text-[10px] text-slate-500 line-clamp-2 mt-1 leading-normal"><?= htmlspecialchars($seo['default_meta_desc'] ?? '') ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Robots.txt Crawl Directives</label>
                    <textarea name="robots_txt" rows="4" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-mono leading-relaxed"><?= htmlspecialchars($seo['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /admin/\nSitemap: " . SITE_URL . "/sitemap.xml") ?></textarea>
                    <span class="text-[10px] text-slate-400">Served live dynamically at <code><?= SITE_URL ?>/robots.txt</code></span>
                </div>

                <div class="pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] transition-colors shadow">
                        Save Global SEO &amp; OG Settings
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

<!-- Interactive Social Preview Real-Time Sync Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleField = document.getElementById('ogTitleField');
    const descField = document.getElementById('ogDescField');
    const urlField = document.getElementById('ogUrlInput');
    const fileField = document.getElementById('ogFileInput');
    
    const previewImg = document.getElementById('ogPreviewImg');
    const simImg = document.getElementById('ogSimImg');
    const simTitle = document.getElementById('ogSimTitle');
    const simDesc = document.getElementById('ogSimDesc');

    if (titleField && simTitle) {
        titleField.addEventListener('input', function() {
            simTitle.textContent = this.value || 'JayArt — Original Paintings & Fine Art Gallery';
        });
    }

    if (descField && simDesc) {
        descField.addEventListener('input', function() {
            simDesc.textContent = this.value || 'Explore original fine art oil paintings, acrylics, and watercolors.';
        });
    }

    if (urlField) {
        urlField.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                const src = this.value.startsWith('http') ? this.value : '<?= SITE_URL ?>/' + this.value.replace(/^\/+/, '');
                if (previewImg) previewImg.src = src;
                if (simImg) simImg.src = src;
            }
        });
    }

    if (fileField) {
        fileField.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImg) previewImg.src = e.target.result;
                    if (simImg) simImg.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
});
</script>

