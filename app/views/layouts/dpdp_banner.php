<?php
/**
 * JayArt DPDP Act, 2023 Statutory Consent & Cookie Notice Banner
 * Luxury floating capsule positioned at bottom-left (zero Tailwind dependency)
 */
?>
<div id="jayartDpdpBanner" role="region" aria-label="Privacy and Cookie Consent" style="position:fixed;bottom:74px;left:12px;z-index:99999;opacity:0;visibility:hidden;pointer-events:none;">
    <div class="dpdp-card">
        <!-- Header: Badge & Close Button -->
        <div class="dpdp-header-row">
            <div class="dpdp-badge-wrap">
                <div class="dpdp-badge-icon" aria-hidden="true">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div class="dpdp-title-text">
                    <strong>Digital Personal Data Protection (DPDP)</strong>
                    <span>Act No. 22 of 2023 • India</span>
                </div>
            </div>
            <button type="button" onclick="closeDpdpBanner('essential')" class="dpdp-close-btn" title="Close Notice" aria-label="Close Notice">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Body Copy -->
        <p class="dpdp-body-copy">
            JayArt processes minimal personal data (essential shopping bag tokens, transit delivery addresses, and statutory GST invoicing) strictly to curate and dispatch authentic original paintings. You can review your Data Principal rights anytime.
        </p>

        <!-- Actions Row -->
        <div class="dpdp-footer-row">
            <a href="<?= SITE_URL ?>/privacy" class="dpdp-notice-link">
                View DPDP Notice &rarr;
            </a>

            <div class="dpdp-actions-wrap">
                <button type="button" onclick="closeDpdpBanner('essential')" class="dpdp-btn-essential">
                    Essential Only
                </button>
                <button type="button" onclick="closeDpdpBanner('accepted')" class="dpdp-btn-accept">
                    Accept &amp; Continue
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function checkDpdpConsent() {
        try {
            var consent = localStorage.getItem('jayart_dpdp_consent');
            if (!consent) {
                var banner = document.getElementById('jayartDpdpBanner');
                if (banner) {
                    setTimeout(function() {
                        banner.classList.add('is-visible');
                    }, 600);
                }
            }
        } catch(e) {}
    }

    window.closeDpdpBanner = function(choice) {
        try {
            localStorage.setItem('jayart_dpdp_consent', choice || 'accepted');
            localStorage.setItem('jayart_dpdp_consent_time', new Date().toISOString());
        } catch(e) {}
        document.cookie = "dpdp_consent=" + (choice || 'accepted') + "; path=/; max-age=31536000; SameSite=Lax";
        var banner = document.getElementById('jayartDpdpBanner');
        if (banner) {
            banner.classList.remove('is-visible');
        }
        if (typeof showToast === 'function') {
            showToast(choice === 'essential' ? 'Privacy preferences saved: Essential only' : 'DPDP Consent acknowledged. Thank you!', 'info');
        }
    };

    window.openDPDPConsentBanner = function() {
        var banner = document.getElementById('jayartDpdpBanner');
        if (banner) {
            banner.classList.add('is-visible');
            banner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkDpdpConsent);
    } else {
        checkDpdpConsent();
    }
})();
</script>
