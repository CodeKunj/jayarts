<?php
/**
 * JayArt Single Blog Article View
 * Rich Typography & In-Article Poster Ad
 */
?>

<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    
    <!-- Breadcrumb -->
    <nav class="flex text-xs text-muted mb-4 font-mono" aria-label="Breadcrumb">
        <a href="<?= SITE_URL ?>" class="hover:text-forest">Home</a>
        <span class="mx-2">/</span>
        <a href="<?= SITE_URL ?>/blog" class="hover:text-forest">Art Stories</a>
        <span class="mx-2">/</span>
        <span class="text-ink font-semibold truncate max-w-xs"><?= htmlspecialchars($post['title']) ?></span>
    </nav>

    <!-- Header -->
    <div class="mb-8">
        <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl font-bold text-ink leading-tight mb-4">
            <?= htmlspecialchars($post['title']) ?>
        </h1>
        <div class="flex items-center gap-3 text-xs text-muted font-mono pb-6 border-b border-linen">
            <span>By <strong><?= htmlspecialchars($post['author_name']) ?></strong></span>
            <span>•</span>
            <span>Published <?= date('d M Y', strtotime($post['created_at'])) ?></span>
            <span>•</span>
            <span><?= (int)$post['views_count'] ?> Reads</span>
        </div>
    </div>

    <!-- Featured Artwork Banner -->
    <div class="rounded-2xl overflow-hidden mb-10 shadow-sm border border-linen">
        <img src="<?= htmlspecialchars($featuredImg) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-auto max-h-[460px] object-cover">
    </div>

    <!-- Article Content -->
    <div class="prose max-w-none text-ink-light text-sm sm:text-base leading-relaxed space-y-6">
        <?= $post['content'] ?>
    </div>

    <!-- In-Article Art Poster Ad Slot -->
    <div class="my-10">
        <?= Ad::render('in_article_blog') ?>
    </div>

    <!-- Back to Gallery CTA -->
    <div class="mt-12 p-8 bg-white border border-linen rounded-2xl flex flex-col sm:flex-row items-center justify-between gap-6 shadow-sm">
        <div>
            <h3 class="font-serif text-lg font-bold text-ink mb-1">Inspired by this Story?</h3>
            <p class="text-xs text-muted">Explore original paintings crafted using these historic and contemporary techniques.</p>
        </div>
        <a href="<?= SITE_URL ?>/shop" class="btn-primary py-3 px-6 rounded-xl text-xs font-bold whitespace-nowrap">
            Explore Original Paintings &rarr;
        </a>
    </div>

</article>

