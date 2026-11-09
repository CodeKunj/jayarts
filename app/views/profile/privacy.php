<?php
/**
 * JayArt Collector DPDP Act, 2023 Rights Management Dashboard
 */
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    
    <!-- Top Bar with Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-linen mb-8">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] font-bold uppercase tracking-wider mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                <span>DPDPA 2023 Data Principal Portal</span>
            </div>
            <h1 class="font-display text-2xl sm:text-3xl font-bold text-ink">Privacy &amp; Data Protection Rights</h1>
            <p class="text-xs text-muted mt-0.5">Exercise your statutory rights under the Digital Personal Data Protection Act, 2023</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= SITE_URL ?>/profile" class="btn-secondary py-2 px-4 text-xs font-semibold rounded-lg">&larr; Back to Profile</a>
            <a href="<?= SITE_URL ?>/privacy" target="_blank" class="btn-secondary py-2 px-4 text-xs font-semibold rounded-lg">Read Full DPDP Notice</a>
        </div>
    </div>

    <?php if ($flashSuccess = Auth::getFlash('success')): ?>
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-xs mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span><?= htmlspecialchars($flashSuccess) ?></span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column: Rights Management Cards (Cols 1-7) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- 1. Right to Access & Download Personal Data (Section 11) -->
            <div class="bg-white border border-linen rounded-2xl p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4 mb-3 pb-3 border-b border-linen">
                    <div>
                        <span class="text-[10px] font-bold font-mono text-emerald-700 uppercase tracking-wider">Section 11 • DPDPA 2023</span>
                        <h2 class="font-serif text-base font-bold text-ink">Right to Access Information &amp; Data Export</h2>
                    </div>
                    <span class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0 text-xs font-bold font-mono">11</span>
                </div>
                <p class="text-xs text-muted mb-4 leading-relaxed">
                    You have the right to obtain a machine-readable summary of all personal data processed by JayArt, including your collector credentials, delivery addresses, order history, and wishlist tokens.
                </p>
                <a href="<?= SITE_URL ?>/profile/privacy/export" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#18181B] hover:bg-black text-white text-xs font-bold rounded-xl transition-colors shadow-2xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <span>Download Personal Data (JSON)</span>
                </a>
            </div>

            <!-- 2. Right to Nominate a Beneficiary (Section 14) -->
            <div class="bg-white border border-linen rounded-2xl p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4 mb-3 pb-3 border-b border-linen">
                    <div>
                        <span class="text-[10px] font-bold font-mono text-emerald-700 uppercase tracking-wider">Section 14 • DPDPA 2023</span>
                        <h2 class="font-serif text-base font-bold text-ink">Right to Nominate a Beneficiary</h2>
                    </div>
                    <span class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center flex-shrink-0 text-xs font-bold font-mono">14</span>
                </div>
                <p class="text-xs text-muted mb-4 leading-relaxed">
                    Under Indian data protection law, you have the right to nominate an individual who will be authorized to exercise your Data Principal rights in the event of your death or incapacity.
                </p>
                
                <form action="<?= SITE_URL ?>/profile/privacy/nominee" method="POST" class="space-y-3.5">
                    <?= Validator::csrfField() ?>
                    <div>
                        <label class="text-xs font-semibold text-ink block mb-1">Nominee Full Legal Name</label>
                        <input type="text" name="nominee_name" value="<?= htmlspecialchars($user['nominee_name'] ?? '') ?>" placeholder="e.g. Spouse, Child, or Legal Guardian" class="form-input text-xs" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-ink block mb-1">Nominee Email Address / Phone Number</label>
                        <input type="text" name="nominee_contact" value="<?= htmlspecialchars($user['nominee_contact'] ?? '') ?>" placeholder="e.g. nominee@email.com or +91 9876543210" class="form-input text-xs" required>
                    </div>
                    <div>
                        <button type="submit" class="btn-primary py-2.5 px-6 rounded-xl text-xs font-bold">
                            Save Nominee Details
                        </button>
                    </div>
                </form>
            </div>

            <!-- 3. Right to Erasure / Account Closure (Section 12) -->
            <div class="bg-white border border-red-100 rounded-2xl p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4 mb-3 pb-3 border-b border-red-100">
                    <div>
                        <span class="text-[10px] font-bold font-mono text-red-600 uppercase tracking-wider">Section 12 • DPDPA 2023</span>
                        <h2 class="font-serif text-base font-bold text-red-900">Right to Erasure &amp; Account Deletion</h2>
                    </div>
                    <span class="w-8 h-8 rounded-full bg-red-50 text-red-700 flex items-center justify-center flex-shrink-0 text-xs font-bold font-mono">12</span>
                </div>
                <p class="text-xs text-stone-600 mb-3 leading-relaxed">
                    You may submit an official request to erase your personal data and delete your collector account. Note that statutory tax invoices and audit trails must be retained for 6 years under the Central Goods and Services Tax (CGST) Act, 2017.
                </p>
                <form action="<?= SITE_URL ?>/profile/privacy/erasure-request" method="POST" onsubmit="return confirm('Are you sure you wish to submit an official data erasure and account deletion request under Section 12 of DPDP Act, 2023?');" class="space-y-3">
                    <?= Validator::csrfField() ?>
                    <div>
                        <label class="text-[11px] font-semibold text-stone-700 block mb-1">Reason for Data Erasure (Optional)</label>
                        <textarea name="erasure_reason" rows="2" placeholder="Briefly state your reason for closure..." class="form-input text-xs"></textarea>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-colors shadow-2xs">
                        Submit Statutory Erasure Request
                    </button>
                </form>
            </div>

        </div>

        <!-- Right Column: Statutory Summary & Grievance Contact (Cols 8-12) -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Grievance Redressal Officer Card -->
            <div class="bg-white border border-[#B67A31]/20 rounded-2xl p-6 shadow-sm">
                <div class="flex items-center gap-2 mb-3 pb-2 border-b border-stone-100">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    <h3 class="font-serif text-base font-bold text-ink">Designated Grievance Officer</h3>
                </div>
                <p class="text-xs text-muted mb-4 leading-relaxed">
                    In compliance with Section 13 of DPDP Act 2023, you can directly reach out to our Data Protection &amp; Grievance Redressal Officer for any privacy concerns:
                </p>
                <div class="bg-stone-50 rounded-xl p-3.5 border border-stone-200/80 text-xs space-y-2">
                    <div>
                        <span class="text-[10px] text-stone-400 font-medium uppercase block">Officer in Charge</span>
                        <strong class="text-[#18181B]">Data Protection &amp; Grievance Officer</strong>
                    </div>
                    <div>
                        <span class="text-[10px] text-stone-400 font-medium uppercase block">Direct Privacy Email</span>
                        <a href="mailto:privacy@ritiverse.in" class="font-bold text-[#18181B] underline hover:text-emerald-700">privacy@ritiverse.in</a>
                    </div>
                    <div>
                        <span class="text-[10px] text-stone-400 font-medium uppercase block">Statutory SLA</span>
                        <span class="text-stone-700">Acknowledgment: <strong>48 hours</strong><br>Resolution: <strong>Within 30 days</strong></span>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-linen">
                    <a href="<?= SITE_URL ?>/profile/tickets" class="btn-secondary w-full py-2.5 text-center text-xs font-bold rounded-xl block">
                        Open a Privacy Support Ticket
                    </a>
                </div>
            </div>

            <!-- DPBI Escalation Information -->
            <div class="bg-emerald-50/70 border border-emerald-200/90 rounded-2xl p-5 text-xs text-emerald-950 space-y-2">
                <div class="flex items-center gap-2 font-bold text-emerald-900">
                    <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Data Protection Board of India (DPBI)</span>
                </div>
                <p class="leading-relaxed text-[11px] text-emerald-800">
                    If our Grievance Officer does not resolve your query within 30 days, or if you are not satisfied with the resolution, you hold the statutory right under Section 13(4) of the DPDP Act, 2023 to file an appeal before the <strong>Data Protection Board of India</strong>.
                </p>
            </div>

            <!-- Provenance & Authenticity Security Note -->
            <div class="bg-white border border-linen rounded-2xl p-5 text-xs text-muted space-y-2">
                <h4 class="font-bold text-ink">Provenance Security Notice</h4>
                <p class="text-[11px] leading-relaxed">
                    To preserve the secondary market value and authenticity of your acquired original paintings, physical Certificates of Authenticity bear unique provenance IDs linked to the physical artwork itself.
                </p>
            </div>

        </div>

    </div>

</div>

