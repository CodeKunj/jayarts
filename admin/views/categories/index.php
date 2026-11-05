<?php
/**
 * JayArt Admin Categories View
 */
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-heading font-bold text-xl text-slate-800">Art Mediums &amp; Categories</h1>
        <p class="text-xs text-slate-400">Classify paintings by medium (Oil, Acrylic, Watercolor, Abstract, Heritage)</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    
    <!-- Add Category Form (Cols 1-4) -->
    <div class="lg:col-span-4 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <h2 class="font-heading font-bold text-sm text-slate-800 pb-3 border-b border-slate-100 mb-4">
            Add New Medium
        </h2>
        <form action="<?= ADMIN_URL ?>/categories/save" method="POST" class="space-y-4">
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Medium / Category Name *</label>
                <input type="text" name="name" required placeholder="e.g. Charcoal Sketches" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
            </div>
            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Description</label>
                <textarea name="description" rows="3" placeholder="Brief notes on this medium..." class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white"></textarea>
            </div>
            <div>
                <button type="submit" class="w-full py-2.5 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] transition-colors">
                    Save Medium Category
                </button>
            </div>
        </form>
    </div>

    <!-- Category Table (Cols 5-12) -->
    <div class="lg:col-span-8 bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Medium Name</th>
                        <th>URL Slug</th>
                        <th>Paintings Listed</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td>
                                <strong class="font-heading text-slate-900 text-sm"><?= htmlspecialchars($cat['name']) ?></strong>
                                <?php if (!empty($cat['description'])): ?>
                                    <div class="text-[11px] text-slate-400 line-clamp-1"><?= htmlspecialchars($cat['description']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code class="text-xs text-slate-600 bg-slate-50 px-2 py-0.5 rounded border border-slate-200 font-mono"><?= htmlspecialchars($cat['slug']) ?></code>
                            </td>
                            <td>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-[#0F172A]/10 text-[#0F172A]">
                                    <?= (int)$cat['product_count'] ?> artworks
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <form action="<?= ADMIN_URL ?>/categories/delete/<?= (int)$cat['id'] ?>" method="POST" class="inline" data-confirm="Are you sure you want to delete category '<?= htmlspecialchars($cat['name'] ?? '', ENT_QUOTES) ?>'?" data-confirm-title="Delete Category" data-confirm-btn="Delete Category">
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-700 font-semibold px-2 py-1">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

