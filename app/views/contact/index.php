<?php
/**
 * JayArt Contact & Curator Desk View
 */
?>

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    <div class="text-center max-w-xl mx-auto mb-12">
        <span class="text-xs uppercase font-bold text-gold tracking-widest block mb-1">Curator Advisory</span>
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-ink mb-2">Connect with Our Gallery</h1>
        <p class="text-xs sm:text-sm text-muted">Inquire about artwork provenance, custom artist commissions, or delivery care</p>
    </div>

    <?php if ($flashSuccess = Auth::getFlash('success')): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs mb-8 max-w-2xl mx-auto">
            ✓ <?= htmlspecialchars($flashSuccess) ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        
        <!-- Contact Form (Cols 1-7) -->
        <div class="lg:col-span-7 bg-white border border-linen rounded-2xl p-6 sm:p-8 shadow-sm">
            <h2 class="font-serif text-lg font-bold text-ink pb-3 border-b border-linen mb-6">Send an Inquiry</h2>
            
            <form action="<?= SITE_URL ?>/contact/submit" method="POST" class="space-y-4">
                <?= Validator::csrfField() ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-ink block mb-1">Your Name *</label>
                        <input type="text" name="name" required class="form-input text-xs" placeholder="Full name">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-ink block mb-1">Email Address *</label>
                        <input type="email" name="email" required class="form-input text-xs" placeholder="collector@domain.com">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-ink block mb-1">Inquiry Subject</label>
                    <input type="text" name="subject" class="form-input text-xs" placeholder="e.g. Inquiring about Echoes of Twilight Valley">
                </div>

                <div>
                    <label class="text-xs font-semibold text-ink block mb-1">Message *</label>
                    <textarea name="message" required rows="5" placeholder="How can our curatorial advisory assist you today?" class="form-input text-xs"></textarea>
                </div>

                <div>
                    <button type="submit" class="btn-primary w-full py-3 rounded-xl text-xs font-bold tracking-wider">
                        Deliver Message to Curators &rarr;
                    </button>
                </div>
            </form>
        </div>

        <!-- Gallery Details (Cols 8-12) -->
        <div class="lg:col-span-5 space-y-6">
            
            <div class="bg-white border border-linen rounded-2xl p-6 shadow-sm space-y-4">
                <h3 class="font-serif text-base font-bold text-ink pb-2 border-b border-linen">Atelier Location</h3>
                <div class="text-xs text-muted leading-relaxed space-y-1">
                    <strong class="text-ink font-serif text-sm">JayArt Private Gallery</strong><br>
                    Heritage Fine Arts Boulevard, Kala Ghoda<br>
                    Mumbai, Maharashtra 400001, India
                </div>
                <div class="text-xs text-muted pt-2 border-t border-linen">
                    Phone: <strong class="text-ink">+91 98765 43210</strong><br>
                    Curator Email: <strong class="text-ink">curators@jayart.com</strong>
                </div>
            </div>

            <div class="bg-forest text-white rounded-2xl p-6 shadow-sm space-y-3">
                <span class="text-[10px] text-gold uppercase tracking-widest font-bold block">Instant Assistance</span>
                <h4 class="font-serif text-lg font-bold">Chat Live with Our Curators</h4>
                <p class="text-xs text-stone-300 leading-relaxed">
                    Have questions about framing options or want to see high-res brushwork detail photos? Message us directly on WhatsApp.
                </p>
                <a href="https://wa.me/919876543210?text=Hello%20JayArt%20Curators" target="_blank" class="btn-gold py-2.5 px-5 rounded-lg text-xs font-bold inline-flex items-center gap-2">
                    Open WhatsApp Chat &rarr;
                </a>
            </div>

        </div>

    </div>
</div>

