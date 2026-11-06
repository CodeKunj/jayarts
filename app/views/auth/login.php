<?php
/**
 * JayArt Customer Login View
 */
?>

<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white border border-linen rounded-2xl p-8 shadow-sm">
        
        <div class="text-center mb-6">
            <a href="<?= SITE_URL ?>" class="inline-block mb-3" title="Back to JayArt Gallery">
                <img src="<?= ASSETS_URL ?>/images/logo.svg?v=<?= APP_VERSION ?>" alt="JayArt" class="h-9 w-auto mx-auto object-contain">
            </a>
            <h1 class="font-serif text-2xl font-bold text-ink">Collector Sign In</h1>
            <p class="text-xs text-muted mt-1">Access your saved artworks, order history, and invoices</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl text-xs mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="<?= SITE_URL ?>/login" method="POST" class="space-y-4">
            <?= Validator::csrfField() ?>

            <div>
                <label class="text-xs font-semibold text-ink block mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="collector@domain.com" class="form-input text-xs">
            </div>

            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="text-xs font-semibold text-ink">Password</label>
                    <a href="<?= SITE_URL ?>/contact" class="text-[11px] text-forest hover:text-gold">Forgot password?</a>
                </div>
                <input type="password" name="password" required placeholder="••••••••" class="form-input text-xs">
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-primary w-full py-3 rounded-xl text-xs font-bold tracking-wider">
                    Sign In to Collector Account
                </button>
            </div>
        </form>

        <div class="text-center text-xs text-muted mt-6 pt-6 border-t border-linen">
            Don't have a collector account yet? 
            <a href="<?= SITE_URL ?>/register" class="text-forest font-bold hover:text-gold ml-1">Create Account &rarr;</a>
        </div>

    </div>
</div>

