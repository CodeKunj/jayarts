<?php
/**
 * JayArt 404 Artwork Not Found View
 */
?>

<div class="max-w-xl mx-auto px-4 py-24 text-center">
    <div class="w-20 h-20 rounded-full bg-forest/10 text-forest flex items-center justify-center mx-auto mb-6">
        <span class="font-display text-2xl font-bold">404</span>
    </div>
    <h1 class="font-serif text-3xl font-bold text-ink mb-3">Artwork Not Found</h1>
    <p class="text-xs sm:text-sm text-muted leading-relaxed mb-8 max-w-sm mx-auto">
        The painting or gallery page you are seeking may have moved to a private collection or is no longer listed.
    </p>
    <div class="flex items-center justify-center gap-4">
        <a href="<?= SITE_URL ?>" class="btn-primary py-3 px-6 rounded-full text-xs font-bold uppercase tracking-wider">
            Return Home
        </a>
        <a href="<?= SITE_URL ?>/shop" class="btn-secondary py-3 px-6 rounded-full text-xs font-semibold">
            Explore Gallery
        </a>
    </div>
</div>

