<?php
/**
 * JayArt Customer Registration View
 */
?>

<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white border border-linen rounded-2xl p-8 shadow-sm">
        
        <div class="text-center mb-6">
            <a href="<?= SITE_URL ?>" class="inline-block mb-3" title="Back to JayArt Gallery">
                <img src="<?= ASSETS_URL ?>/images/logo.svg?v=<?= APP_VERSION ?>" alt="JayArt" class="h-9 w-auto mx-auto object-contain">
            </a>
            <h1 class="font-serif text-2xl font-bold text-ink">Join Collector Salon</h1>
            <p class="text-xs text-muted mt-1">Acquire original certified artworks directly from artists</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs mb-4 space-y-1">
                <?php foreach ($errors as $err): ?>
                    <div>• <?= htmlspecialchars($err) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= SITE_URL ?>/register" method="POST" class="space-y-4">
            <?= Validator::csrfField() ?>

            <div>
                <label class="text-xs font-semibold text-ink block mb-1">Full Legal Name *</label>
                <input type="text" name="name" required value="<?= htmlspecialchars($old['name'] ?? '') ?>" placeholder="Your Name" class="form-input text-xs">
            </div>

            <div>
                <label class="text-xs font-semibold text-ink block mb-1">Email Address *</label>
                <input type="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="collector@domain.com" class="form-input text-xs">
            </div>

            <div>
                <label class="text-xs font-semibold text-ink block mb-1">Phone Number (For Delivery Logistics)</label>
                <input type="tel" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>" placeholder="+91 98765 43210" class="form-input text-xs">
            </div>

            <div>
                <label class="text-xs font-semibold text-ink block mb-1">Account Password (Min 6 characters) *</label>
                <input type="password" name="password" required placeholder="••••••••" class="form-input text-xs">
            </div>

            <div class="pt-1">
                <label class="flex items-start gap-2 text-[11px] text-stone-600 cursor-pointer select-none">
                    <input type="checkbox" name="dpdp_consent" value="1" required checked class="mt-0.5 rounded border-stone-300 text-ink focus:ring-ink">
                    <span>
                        I consent to JayArt processing my personal data in accordance with the <strong>Digital Personal Data Protection (DPDP) Act, 2023</strong> and agree to the <a href="<?= SITE_URL ?>/privacy" target="_blank" class="font-bold underline text-ink">Privacy Policy</a> &amp; <a href="<?= SITE_URL ?>/terms" target="_blank" class="font-bold underline text-ink">Terms</a>.
                    </span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-gold w-full py-3 rounded-xl text-xs font-bold tracking-wider">
                    Create Collector Account
                </button>
            </div>
        </form>

        <div class="text-center text-xs text-muted mt-6 pt-6 border-t border-linen">
            Already have an account? 
            <a href="<?= SITE_URL ?>/login" class="text-forest font-bold hover:text-gold ml-1">Sign In &rarr;</a>
        </div>

    </div>
</div>

