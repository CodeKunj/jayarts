<?php
/**
 * JayArt Blog Listing View
 */
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    <div class="text-center max-w-xl mx-auto mb-12">
        <span class="text-xs uppercase font-bold text-gold tracking-widest block mb-1">Art Journal &amp; Salon</span>
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-ink mb-2">Curator Stories</h1>
        <p class="text-xs sm:text-sm text-muted">Technique breakdowns, artist profiles, and insights for fine art collectors</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php foreach ($posts as $post): 
            $img = !empty($post['featured_image']) ? SITE_URL . '/' . $post['featured_image'] : ASSETS_URL . '/images/banner.jpg';
        ?>
            <article class="bg-white border border-linen rounded-2xl overflow-hidden hover:shadow-md transition-all flex flex-col justify-between group">
                <div class="aspect-[16/9] bg-canvas overflow-hidden">
                    <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-[11px] text-muted mb-2 font-mono">
                            <?= date('d M Y', strtotime($post['created_at'])) ?> • By <?= htmlspecialchars($post['author_name']) ?>
                        </div>
                        <h2 class="font-serif text-lg font-bold text-ink mb-3 group-hover:text-forest transition-colors">
                            <a href="<?= SITE_URL ?>/blog/<?= htmlspecialchars($post['slug']) ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h2>
                        <p class="text-xs text-muted leading-relaxed line-clamp-3 mb-4">
                            <?= htmlspecialchars(strip_tags((string)($post['excerpt'] ?: $post['content']))) ?>
                        </p>
                    </div>

                    <a href="<?= SITE_URL ?>/blog/<?= htmlspecialchars($post['slug']) ?>" class="text-xs font-semibold text-forest hover:text-gold transition-colors inline-flex items-center gap-1">
                        <span>Read Full Story</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>

