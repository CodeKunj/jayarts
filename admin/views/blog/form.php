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
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-200">
            <a href="<?= ADMIN_URL ?>/blog" class="px-5 py-2 text-xs font-semibold text-slate-600">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] shadow">
                Save &amp; Publish Story &rarr;
            </button>
        </div>

    </form>
</div>

