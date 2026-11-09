<?php
/**
 * JayArt DPDP Act, 2023 Statutory Consent & Cookie Notice Banner
 * Seamless floating capsule with right to withdraw anytime
 */
?>
<div id="jayartDpdpBanner" class="hidden fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 sm:max-w-lg z-50 transition-all duration-300" role="region" aria-label="Privacy and Cookie Consent">
    <div class="bg-white/98 backdrop-blur-md border border-[#B67A31]/25 rounded-3xl p-4 sm:p-5 shadow-2xl space-y-3.5">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0 text-xs font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-[#18181B] leading-tight">Digital Personal Data Protection (DPDP)</h3>
                    <span class="text-[10px] text-stone-400 font-mono">Act No. 22 of 2023 • India</span>
                </div>
            </div>
            <button type="button" onclick="closeDpdpBanner('essential')" class="text-stone-400 hover:text-stone-700 p-1" title="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <p class="text-[11px] text-stone-600 leading-relaxed">
            JayArt processes minimal personal data (essential shopping bag tokens, transit delivery addresses, and statutory GST invoicing) strictly to curate and dispatch authentic original paintings. You can review your Data Principal rights anytime.
        </p>

        <div class="flex flex-wrap items-center justify-between gap-2 pt-1 border-t border-stone-100">
            <a href="<?= SITE_URL ?>/privacy" class="text-[11px] font-bold text-[#18181B] underline hover:text-emerald-700">
                View DPDP Notice &rarr;
            </a>

            <div class="flex items-center gap-2">
                <button type="button" onclick="closeDpdpBanner('essential')" class="px-3 py-1.5 rounded-full text-[11px] font-semibold text-stone-600 hover:text-black bg-stone-100 hover:bg-stone-200 transition-colors">
                    Essential Only
                </button>
                <button type="button" onclick="closeDpdpBanner('accepted')" class="px-4 py-1.5 rounded-full text-[11px] font-bold text-white bg-[#18181B] hover:bg-black transition-colors shadow-xs">
                    Accept &amp; Continue
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function checkDpdpConsent() {
        var consent = localStorage.getItem('jayart_dpdp_consent');
        if (!consent) {
            var banner = document.getElementById('jayartDpdpBanner');
            if (banner) {
                setTimeout(function() {
                    banner.classList.remove('hidden');
                }, 800);
            }
        }
    }

    window.closeDpdpBanner = function(choice) {
        localStorage.setItem('jayart_dpdp_consent', choice || 'accepted');
        localStorage.setItem('jayart_dpdp_consent_time', new Date().toISOString());
        document.cookie = "dpdp_consent=" + (choice || 'accepted') + "; path=/; max-age=31536000; SameSite=Lax";
        var banner = document.getElementById('jayartDpdpBanner');
        if (banner) {
            banner.style.opacity = '0';
            banner.style.transform = 'translateY(16px)';
            banner.style.transition = 'all 0.25s ease';
            setTimeout(function() { banner.classList.add('hidden'); }, 260);
        }
        if (typeof showToast === 'function') {
            showToast(choice === 'essential' ? 'Privacy preferences saved: Essential only' : 'DPDP Consent acknowledged. Thank you!', 'info');
        }
    };

    window.openDPDPConsentBanner = function() {
        var banner = document.getElementById('jayartDpdpBanner');
        if (banner) {
            banner.classList.remove('hidden');
            banner.style.opacity = '1';
            banner.style.transform = 'translateY(0)';
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkDpdpConsent);
    } else {
        checkDpdpConsent();
    }
})();
</script>

