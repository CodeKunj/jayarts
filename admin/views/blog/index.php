<?php
/**
 * JayArt Admin Blog Listing View
 */
?>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="font-heading font-bold text-xl text-slate-800">Art Stories &amp; Editorial Magazine</h1>
        <p class="text-xs text-slate-400">Total <?= count($blogs) ?> articles published with individual SEO tracking</p>
    </div>
    <a href="<?= ADMIN_URL ?>/blog/create" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0F172A] text-white rounded-xl text-xs font-bold hover:bg-[#020617] transition-colors shadow">
        <span>+ Write New Story</span>
    </a>
</div>

<div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Story Title</th>
                    <th>Author</th>
                    <th>Reads / Views</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($blogs)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-8 text-xs text-slate-400">
                            No art stories published yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($blogs as $b): 
                        $img = !empty($b['featured_image']) ? SITE_URL . '/' . $b['featured_image'] : ASSETS_URL . '/images/banner.jpg';
                    ?>
                        <tr>
                            <td>
                                <img src="<?= htmlspecialchars($img) ?>" alt="Cover" class="w-14 h-10 object-cover rounded border border-slate-200">
                            </td>
                            <td>
                                <a href="<?= ADMIN_URL ?>/blog/edit/<?= (int)$b['id'] ?>" class="font-heading font-bold text-slate-900 text-sm hover:text-[#0F172A]">
                                    <?= htmlspecialchars($b['title']) ?>
                                </a>
                                <div class="font-mono text-[10px] text-slate-400">/blog/<?= htmlspecialchars($b['slug']) ?></div>
                            </td>
                            <td>
                                <span class="text-xs text-slate-700 font-medium"><?= htmlspecialchars($b['author_name']) ?></span>
                            </td>
                            <td>
                                <span class="font-mono text-xs font-bold text-slate-900"><?= (int)$b['views_count'] ?></span>
                            </td>
                            <td>
                                <span class="status-pill status-<?= ($b['status'] === 'published') ? 'active' : 'pending' ?>">
                                    <?= ucfirst($b['status']) ?>
                                </span>
                            </td>
                            <td class="text-xs text-slate-500 whitespace-nowrap">
                                <?= date('d M Y', strtotime($b['created_at'])) ?>
                            </td>
                            <td style="text-align: right;" class="whitespace-nowrap">
                                <a href="<?= ADMIN_URL ?>/blog/edit/<?= (int)$b['id'] ?>" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded text-xs font-semibold mr-1">
                                    Edit
                                </a>
                                <form action="<?= ADMIN_URL ?>/blog/delete/<?= (int)$b['id'] ?>" method="POST" class="inline" data-confirm="Are you sure you want to delete the story '<?= htmlspecialchars($b['title'] ?? '', ENT_QUOTES) ?>'?" data-confirm-title="Delete Story" data-confirm-btn="Delete Story">
                                    <button type="submit" class="px-2.5 py-1 bg-red-50 hover:bg-red-100 text-red-600 rounded text-xs font-semibold">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

