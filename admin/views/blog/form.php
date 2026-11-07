<?php
/**
 * JayArt Admin Blog Story Add/Edit Form
 * Tabbed Interface with Dedicated Individual Article SEO Engine
 */

$actionUrl = $isEdit ? ADMIN_URL . '/blog/update/' . $blog['id'] : ADMIN_URL . '/blog/store';
?>

<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-6">
        <div>
            <h1 class="font-heading font-bold text-xl text-slate-800"><?= $isEdit ? 'Edit Art Story' : 'Write New Art Story' ?></h1>
            <p class="text-xs text-slate-400">Compose rich editorial articles with individual article SEO tags</p>
        </div>
        <a href="<?= ADMIN_URL ?>/blog" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Back to Stories
        </a>
    </div>

    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data" class="admin-tab-container space-y-6">
        
        <!-- Tab Navigation Buttons -->
        <div class="flex border-b border-slate-200 gap-2">
            <button type="button" class="admin-tab-btn active" data-tab="tabContent">1. Article Content</button>
            <button type="button" class="admin-tab-btn" data-tab="tabBlogSEO">2. Article SEO Engine</button>
        </div>

        <!-- TAB 1: Content -->
        <div id="tabContent" class="admin-tab-content space-y-5 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Story Headline Title *</label>
                <input type="text" name="title" required value="<?= htmlspecialchars($blog['title'] ?? '') ?>" placeholder="e.g. The Lure of the Palette Knife in Modern Landscapes" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-heading font-bold">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Author / Curator Name</label>
                    <input type="text" name="author_name" value="<?= htmlspecialchars($blog['author_name'] ?? 'JayArt Curators') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Publish Status</label>
                    <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-medium">
                        <option value="published" <?= (isset($blog['status']) && $blog['status'] === 'published') ? 'selected' : '' ?>>Published (Live)</option>
                        <option value="draft" <?= (isset($blog['status']) && $blog['status'] === 'draft') ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Featured Cover Image</label>
                <input type="file" name="featured_image" accept="image/*" class="w-full text-xs text-slate-500">
                <span class="text-[10px] text-slate-400">Landscape orientation (1200x630px) recommended for best Google &amp; Social sharing</span>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Brief Excerpt</label>
                <textarea name="excerpt" rows="2" placeholder="Short introductory hook for the article..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white"><?= htmlspecialchars($blog['excerpt'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Full Article HTML Content *</label>
                <textarea name="content" required rows="10" placeholder="Write full article body (Supports standard HTML tags: <p>, <h2>, <em>, <strong>, <blockquote>)..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-mono leading-relaxed"><?= htmlspecialchars($blog['content'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- TAB 2: Individual Blog SEO -->
        <div id="tabBlogSEO" class="admin-tab-content hidden space-y-5 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl text-xs text-blue-900 leading-relaxed">
                <strong>Individual Article SEO Optimization:</strong> When published, this article will inject JSON-LD <code>schema.org/Article</code> structured data for Google Discover and Google News eligibility, alongside OpenGraph social sharing tags.
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Custom Meta Title</label>
                <input type="text" name="meta_title" value="<?= htmlspecialchars($seo['meta_title'] ?? '') ?>" placeholder="Headline | JayArt Magazine" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Custom Meta Description</label>
                <textarea name="meta_description" rows="3" placeholder="150-160 characters summary for Google search result snippet..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white"><?= htmlspecialchars($seo['meta_description'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Meta Keywords</label>
                <input type="text" name="keywords" value="<?= htmlspecialchars($seo['keywords'] ?? '') ?>" placeholder="art collecting, oil painting techniques, indian contemporary art" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
            </div>

            <!-- Custom Article OG Image -->
            <?php
            $initialBlogOg = !empty($seo['og_image']) 
                ? (str_starts_with($seo['og_image'], 'http') ? $seo['og_image'] : SITE_URL . '/' . ltrim($seo['og_image'], '/'))
                : (!empty($blog['featured_image']) ? (str_starts_with($blog['featured_image'], 'http') ? $blog['featured_image'] : SITE_URL . '/' . ltrim($blog['featured_image'], '/')) : ASSETS_URL . '/images/og-default.jpg');
            ?>
            <div class="pt-4 border-t border-slate-100 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="text-xs font-bold text-slate-800 block mb-0.5">Custom Article OpenGraph Social Share Image</label>
                        <span class="text-[10px] text-slate-400 block">Leave blank to automatically use the story's featured cover image, or upload a custom 1200 &times; 630 banner for WhatsApp / Facebook.</span>
                    </div>
                    <span class="text-[9px] font-mono px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold uppercase">
                        Article OG
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-start bg-slate-50 p-4 rounded-xl border border-slate-200">
                    <div class="sm:col-span-4 relative group overflow-hidden rounded-xl border border-slate-300 bg-slate-900 aspect-video shadow-2xs">
                        <img id="blogOgPreviewImg" src="<?= htmlspecialchars($initialBlogOg) ?>" alt="Blog OG Preview" class="w-full h-full object-cover">
                        <div class="absolute bottom-1 left-1 bg-black/75 backdrop-blur-xs px-1.5 py-0.5 rounded text-[8px] font-mono font-bold text-white">
                            1200 &times; 630 OG
                        </div>
                    </div>

                    <div class="sm:col-span-8 space-y-2.5">
                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">Upload Custom OG Image File</label>
                            <input type="file" name="blog_og_image_file" id="blogOgFileInput" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-white file:border file:border-slate-300 file:text-slate-700 hover:file:bg-slate-100 cursor-pointer">
                        </div>

                        <div>
                            <label class="text-[11px] font-semibold text-slate-700 block mb-1">Or Enter Custom Image URL / Server Path</label>
                            <input type="text" name="og_image" id="blogOgImageInput" value="<?= htmlspecialchars($seo['og_image'] ?? '') ?>" placeholder="e.g. uploads/blogs/og/custom.jpg or https://..." class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono focus:bg-white">
                        </div>

                        <?php if (!empty($seo['og_image'])): ?>
                            <div class="flex items-center gap-2 pt-1 text-[11px]">
                                <span class="text-slate-400">Current file:</span>
                                <span class="font-mono text-blue-600 truncate max-w-xs font-medium"><?= htmlspecialchars($seo['og_image']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-200">
            <a href="<?= ADMIN_URL ?>/blog" class="px-5 py-2 text-xs font-semibold text-slate-600">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] shadow">
                Save &amp; Publish Story &rarr;
            </button>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const ogFileInput = document.getElementById('blogOgFileInput');
    const ogImageInput = document.getElementById('blogOgImageInput');
    const ogPreviewImg = document.getElementById('blogOgPreviewImg');

    if (ogFileInput && ogPreviewImg) {
        ogFileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    ogPreviewImg.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    if (ogImageInput && ogPreviewImg) {
        ogImageInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                const val = this.value.trim();
                ogPreviewImg.src = (val.startsWith('http://') || val.startsWith('https://'))
                    ? val
                    : '<?= SITE_URL ?>/' + val.replace(/^\/+/, '');
            }
        });
    }
});
</script>

