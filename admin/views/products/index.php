<?php
/**
 * JayArt Admin Products List View
 */
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-heading font-bold text-xl text-slate-800">Paintings &amp; Catalog Management</h1>
        <p class="text-xs text-slate-400">Total <?= count($paintings) ?> paintings currently listed</p>
    </div>
    <a href="<?= ADMIN_URL ?>/products/create" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0F172A] hover:bg-[#020617] text-white rounded-xl text-xs font-bold transition-colors shadow">
        <span>+ Add New Painting</span>
    </a>
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th style="width: 70px;">Artwork</th>
                    <th>Title &amp; Dimensions</th>
                    <th>Artist</th>
                    <th>Medium</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paintings as $p): 
                    $img = !empty($p['primary_image']) ? SITE_URL . '/' . $p['primary_image'] : SITE_URL . '/uploads/products/echoes-twilight.jpg';
                ?>
                    <tr>
                        <td>
                            <img src="<?= htmlspecialchars($img) ?>" alt="Artwork" class="w-12 h-14 object-cover rounded border border-slate-200">
                        </td>
                        <td>
                            <a href="<?= ADMIN_URL ?>/products/edit/<?= (int)$p['id'] ?>" class="font-heading font-bold text-sm text-slate-900 hover:text-[#0F172A]">
                                <?= htmlspecialchars($p['title']) ?>
                            </a>
                            <div class="font-mono text-[10px] text-slate-400"><?= htmlspecialchars($p['dimensions']) ?></div>
                        </td>
                        <td>
                            <span class="font-medium text-xs text-slate-700"><?= htmlspecialchars($p['artist_name']) ?></span>
                        </td>
                        <td>
                            <span class="text-xs text-slate-500"><?= htmlspecialchars($p['medium']) ?></span>
                        </td>
                        <td>
                            <span class="font-sans font-bold text-slate-900"><?= Validator::formatPrice((float)$p['price']) ?></span>
                            <?php if (!empty($p['discount_price'])): ?>
                                <span class="text-[10px] text-emerald-600 font-bold block">Disc: <?= Validator::formatPrice((float)$p['discount_price']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="font-bold text-xs <?= ((int)$p['stock_quantity'] <= 1) ? 'text-amber-600' : 'text-slate-800' ?>">
                                <?= (int)$p['stock_quantity'] ?> in stock
                            </span>
                        </td>
                        <td>
                            <span class="status-pill status-<?= $p['status'] ?>">
                                <?= ucfirst($p['status']) ?>
                            </span>
                        </td>
                        <td style="text-align: right;" class="whitespace-nowrap">
                            <a href="<?= ADMIN_URL ?>/products/edit/<?= (int)$p['id'] ?>" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xs font-semibold mr-1">
                                Edit
                            </a>
                            <form action="<?= ADMIN_URL ?>/products/delete/<?= (int)$p['id'] ?>" method="POST" class="inline" data-confirm="Are you sure you want to archive or delete '<?= htmlspecialchars($p['title'] ?? '', ENT_QUOTES) ?>'?" data-confirm-title="Delete Artwork" data-confirm-btn="Delete Artwork">
                                <button type="submit" class="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-600 rounded text-xs font-semibold">
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

