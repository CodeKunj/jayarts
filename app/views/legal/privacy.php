<?php
/**
 * JayArt Digital Personal Data Protection Act (DPDP Act, 2023) Notice & Privacy Policy
 * In compliance with Act No. 22 of 2023, Ministry of Law and Justice, Government of India
 */
?>

<div class="bg-[#FAF6F0]/65 py-10 sm:py-16 border-b border-[#B67A31]/15">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Badge & Title -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white border border-[#B67A31]/20 shadow-2xs mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-[10px] sm:text-xs font-mono font-bold uppercase tracking-wider text-stone-800">
                    Statutory Compliance Notice • DPDPA 2023
                </span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-serif font-black text-[#18181B] tracking-tight mb-4">
                Digital Personal Data Protection Policy
            </h1>
            <p class="text-xs sm:text-sm text-stone-600 max-w-2xl mx-auto leading-relaxed">
                In strict compliance with the <strong>Digital Personal Data Protection Act, 2023 (Act No. 22 of 2023)</strong> enacted by the Parliament of India, this notice informs you of how JayArt (Data Fiduciary) processes, protects, and respects your digital personal data.
            </p>
            <div class="mt-4 flex flex-wrap items-center justify-center gap-4 text-[11px] font-semibold text-stone-500">
                <span>Effective Date: <strong>1st September 2023</strong></span>
                <span>•</span>
                <span>Last Updated: <strong>November 2026</strong></span>
                <span>•</span>
                <span>Jurisdiction: <strong>Republic of India</strong></span>
            </div>
        </div>

        <!-- Quick Action Pill for Logged-In Collectors -->
        <div class="bg-white border border-[#B67A31]/20 rounded-2xl p-4 sm:p-5 mb-8 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h3 class="text-xs sm:text-sm font-bold text-[#18181B]">Exercise Your Data Principal Rights Online</h3>
                    <p class="text-[11px] text-stone-500">Download your data, manage consent, nominate beneficiaries, or request erasure directly from your collector dashboard.</p>
                </div>
            </div>
            <a href="<?= SITE_URL ?>/profile/privacy" class="px-4 py-2 bg-[#18181B] hover:bg-black text-white text-xs font-bold rounded-full transition-colors flex-shrink-0 shadow-xs">
                Manage Privacy Rights &rarr;
            </a>
        </div>

        <!-- Document Content Body -->
        <div class="bg-white border border-stone-200/90 rounded-3xl p-6 sm:p-10 shadow-xs space-y-8 text-xs sm:text-sm text-stone-700 leading-relaxed font-sans">
            
            <!-- 1. Definitions & Data Fiduciary Identification -->
            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold font-serif text-[#18181B] pb-2 border-b border-stone-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-stone-100 text-stone-800 text-xs flex items-center justify-center font-mono font-bold">1</span>
                    Data Fiduciary &amp; Key Definitions
                </h2>
                <p>
                    Under the Digital Personal Data Protection Act, 2023:
                </p>
                <ul class="list-disc pl-5 space-y-1.5 text-xs text-stone-600">
                    <li><strong>"Data Fiduciary":</strong> <strong>JayArt Fine Art Gallery</strong> (operated by RiTiVerse Pvt. Ltd., Gujarat, India) determines the purpose and means of personal data processing.</li>
                    <li><strong>"Data Principal":</strong> You, the natural person (art collector, buyer, visitor, or artist) to whom the personal data relates.</li>
                    <li><strong>"Personal Data":</strong> Any data about an individual who is identifiable by or in relation to such data.</li>
                    <li><strong>"Processing":</strong> An automated or manual operation including collection, recording, storage, retrieval, use, sharing, or erasure of personal data.</li>
                </ul>
            </section>

            <!-- 2. Notice of Personal Data Collected (Section 5) -->
            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold font-serif text-[#18181B] pb-2 border-b border-stone-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-stone-100 text-stone-800 text-xs flex items-center justify-center font-mono font-bold">2</span>
                    Notice of Personal Data Collected (Section 5(1))
                </h2>
                <p>JayArt adheres strictly to the principle of <em>Data Minimization</em>. We only collect personal data that is strictly necessary to fulfill your art acquisitions, generate statutory tax invoices, and maintain museum-grade physical provenance certificates:</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200/80">
                        <h4 class="font-bold text-xs text-[#18181B] mb-1">A. Identity &amp; Contact Data</h4>
                        <p class="text-[11px] text-stone-600 leading-normal">Full name, email address, contact phone number, and optional account credentials (passwords stored strictly as cryptographic bcrypt hashes).</p>
                    </div>

                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200/80">
                        <h4 class="font-bold text-xs text-[#18181B] mb-1">B. Shipping &amp; Transit Logistics Data</h4>
                        <p class="text-[11px] text-stone-600 leading-normal">Delivery address, city, state, postal code (PIN), landmark, and alternate contact numbers provided strictly for fragile artwork dispatch.</p>
                    </div>

                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200/80">
                        <h4 class="font-bold text-xs text-[#18181B] mb-1">C. Financial &amp; GST Invoicing Data</h4>
                        <p class="text-[11px] text-stone-600 leading-normal">Transaction reference number, GSTIN (for B2B acquisitions), billing address. <em>JayArt never stores debit/credit card CVV or UPI PINs.</em></p>
                    </div>

                    <div class="p-3.5 bg-stone-50 rounded-2xl border border-stone-200/80">
                        <h4 class="font-bold text-xs text-[#18181B] mb-1">D. Technical &amp; Essential Session Data</h4>
                        <p class="text-[11px] text-stone-600 leading-normal">IP address, browser user-agent, essential authentication tokens, currency selection, and shopping bag session identifiers.</p>
                    </div>
                </div>
            </section>

            <!-- 3. Purpose of Processing (Section 5) -->
            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold font-serif text-[#18181B] pb-2 border-b border-stone-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-stone-100 text-stone-800 text-xs flex items-center justify-center font-mono font-bold">3</span>
                    Specified Purposes of Processing (Section 5 &amp; 7)
                </h2>
                <p>Your personal data is processed solely for lawful and specified purposes:</p>
                <ol class="list-decimal pl-5 space-y-1.5 text-xs text-stone-600">
                    <li><strong>Order Fulfillment &amp; Provenance:</strong> Crafting physical Certificates of Authenticity, framing customization, and transit delivery of authentic artworks.</li>
                    <li><strong>Legal &amp; Tax Compliance:</strong> Generating valid GST tax invoices, audit trails, and financial records as mandated by the Central Goods and Services Tax Act, 2017.</li>
                    <li><strong>Customer Care &amp; Support:</strong> Order tracking updates, delivery milestone SMS/WhatsApp notifications, and resolving inquiries via support tickets.</li>
                    <li><strong>Platform Security &amp; Fraud Prevention:</strong> Preventing fraudulent transactions, unauthorized account access, and securing payment verification.</li>
                </ol>
            </section>

            <!-- 4. Consent Architecture & Right to Withdraw (Section 6) -->
            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold font-serif text-[#18181B] pb-2 border-b border-stone-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-stone-100 text-stone-800 text-xs flex items-center justify-center font-mono font-bold">4</span>
                    Consent &amp; Right to Withdraw Consent (Section 6(4))
                </h2>
                <p>
                    Your consent is collected through an affirmative, itemised, and unambiguous action (such as placing an order, registering an account, or accepting the DPDP cookie notice).
                </p>
                <p class="bg-amber-50/70 border border-amber-200/80 p-3.5 rounded-2xl text-xs text-amber-900 leading-relaxed">
                    <strong>Right to Withdraw (Section 6(4)):</strong> You have the legal right to withdraw your consent at any time, with the same ease with which it was given. You can withdraw optional marketing consent or request account closure through your <a href="<?= SITE_URL ?>/profile/privacy" class="underline font-bold text-amber-950">Profile Privacy Dashboard</a> or by emailing our Grievance Officer. Withdrawal does not affect the legality of processing prior to such withdrawal, nor does it affect mandatory statutory tax records.
                </p>
            </section>

            <!-- 5. Rights of the Data Principal (Sections 11 to 14) -->
            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold font-serif text-[#18181B] pb-2 border-b border-stone-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-stone-100 text-stone-800 text-xs flex items-center justify-center font-mono font-bold">5</span>
                    Your Legal Rights as a Data Principal (Sections 11–14)
                </h2>
                <p>As a Data Principal under Indian law, you are entitled to the following fundamental statutory rights:</p>

                <div class="space-y-2.5 pt-1 text-xs">
                    <div class="p-3 bg-stone-50 rounded-xl border border-stone-200">
                        <strong class="text-[#18181B] block mb-0.5">1. Right to Access Information (Section 11):</strong>
                        <span>You can request a complete summary of your personal data processed by JayArt, and the identities of data processors (e.g. logistics carriers) with whom it has been shared.</span>
                    </div>

                    <div class="p-3 bg-stone-50 rounded-xl border border-stone-200">
                        <strong class="text-[#18181B] block mb-0.5">2. Right to Correction &amp; Erasure (Section 12):</strong>
                        <span>You can update misleading or incomplete data, or request permanent erasure of your personal data when it is no longer necessary for the purpose it was collected (subject to statutory financial retention laws).</span>
                    </div>

                    <div class="p-3 bg-stone-50 rounded-xl border border-stone-200">
                        <strong class="text-[#18181B] block mb-0.5">3. Right of Grievance Redressal (Section 13):</strong>
                        <span>You have the right to readily available grievance redressal through our designated Grievance Officer, with guaranteed acknowledgment within 48 hours and resolution within 30 days.</span>
                    </div>

                    <div class="p-3 bg-stone-50 rounded-xl border border-stone-200">
                        <strong class="text-[#18181B] block mb-0.5">4. Right to Nominate (Section 14):</strong>
                        <span>You have the legal right to nominate any individual who shall, in the event of your death or incapacity, exercise your Data Principal rights. You can set your nominee in your <a href="<?= SITE_URL ?>/profile/privacy" class="underline font-bold text-black">Profile Privacy Dashboard</a>.</span>
                    </div>
                </div>
            </section>

            <!-- 6. Protection of Children's Personal Data (Section 9) -->
            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold font-serif text-[#18181B] pb-2 border-b border-stone-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-stone-100 text-stone-800 text-xs flex items-center justify-center font-mono font-bold">6</span>
                    Protection of Children's Data (Section 9)
                </h2>
                <p>
                    JayArt is committed to the protection of minors. In adherence to Section 9 of the DPDP Act, 2023:
                </p>
                <ul class="list-disc pl-5 space-y-1 text-xs text-stone-600">
                    <li>We do not knowingly process personal data of children under 18 years without verifiable consent of a parent or lawful guardian.</li>
                    <li>We strictly prohibit any behavioral monitoring, tracking, or targeted advertising directed towards minors.</li>
                    <li>Art acquisitions for minors must be transacted by a parent or legal guardian.</li>
                </ul>
            </section>

            <!-- 7. Data Sharing with Verified Processors -->
            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold font-serif text-[#18181B] pb-2 border-b border-stone-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-stone-100 text-stone-800 text-xs flex items-center justify-center font-mono font-bold">7</span>
                    Data Sharing &amp; Third-Party Processors
                </h2>
                <p>JayArt <strong>NEVER</strong> sells, rents, monetizes, or trades your personal data. Personal data is only shared with trusted Data Processors bound by contractual data protection agreements:</p>
                <ul class="list-disc pl-5 space-y-1 text-xs text-stone-600">
                    <li><strong>Logistics Partners:</strong> Verified Indian courier services (e.g., Shiprocket, Delhivery, BlueDart, India Post) solely for dispatching physical artworks.</li>
                    <li><strong>Payment Aggregators:</strong> RBI-licensed payment gateways (Razorpay, NPCI Unified Payments Interface) operating under PCI-DSS Level 1 compliance.</li>
                    <li><strong>Statutory Tax Authorities:</strong> When mandatory under Indian taxation statutes (GST &amp; Income Tax Act).</li>
                </ul>
            </section>

            <!-- 8. Reasonable Security Safeguards (Section 8(5)) -->
            <section class="space-y-3">
                <h2 class="text-base sm:text-lg font-bold font-serif text-[#18181B] pb-2 border-b border-stone-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-stone-100 text-stone-800 text-xs flex items-center justify-center font-mono font-bold">8</span>
                    Security Safeguards &amp; Breach Protocol (Section 8(5) &amp; 8(6))
                </h2>
                <p>
                    JayArt implements reasonable technical and organizational security safeguards, including TLS 1.3 in-transit encryption, bcrypt salted password hashing, automated session invalidation, and strict access controls. In the unlikely event of any personal data breach, JayArt will immediately notify the <strong>Data Protection Board of India (DPBI)</strong> and affected Data Principals in accordance with Section 8(6).
                </p>
            </section>

            <!-- 9. Grievance Officer & Contact Details (Section 13) -->
            <section class="space-y-3 pt-2">
                <h2 class="text-base sm:text-lg font-bold font-serif text-[#18181B] pb-2 border-b border-stone-100 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-stone-100 text-stone-800 text-xs flex items-center justify-center font-mono font-bold">9</span>
                    Grievance Redressal Officer &amp; Statutory Contacts (Section 13)
                </h2>
                <p>
                    If you have any questions, wish to exercise any Data Principal rights, or have a privacy grievance, you can directly reach our designated Grievance Officer:
                </p>

                <div class="p-4 sm:p-5 bg-stone-50 rounded-2xl border border-stone-200/90 text-xs space-y-2">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <span class="text-stone-400 font-medium block text-[10px] uppercase tracking-wider">Designated Officer</span>
                            <strong class="text-[#18181B]">Data Protection &amp; Grievance Officer</strong>
                            <p class="text-stone-600">JayArt Fine Art Gallery (RiTiVerse)</p>
                        </div>
                        <div>
                            <span class="text-stone-400 font-medium block text-[10px] uppercase tracking-wider">Direct Grievance Email</span>
                            <a href="mailto:privacy@ritiverse.in" class="font-bold text-[#18181B] underline hover:text-emerald-700">privacy@ritiverse.in</a>
                            <p class="text-stone-500 text-[11px]">Copy to: support@jayarts.ritiverse.in</p>
                        </div>
                        <div>
                            <span class="text-stone-400 font-medium block text-[10px] uppercase tracking-wider">Physical Atelier Office</span>
                            <p class="text-stone-700">JayArt Atelier, RiTiVerse Creative Labs, Vadodara, Gujarat, India - 390001</p>
                        </div>
                        <div>
                            <span class="text-stone-400 font-medium block text-[10px] uppercase tracking-wider">Statutory Response SLA</span>
                            <p class="text-stone-700">Acknowledgment: <strong>Within 48 hours</strong><br>Resolution: <strong>Within 30 calendar days</strong></p>
                        </div>
                    </div>
                </div>

                <div class="p-3.5 bg-emerald-50/70 border border-emerald-200/80 rounded-2xl text-xs text-emerald-950">
                    <strong>Escalation to Data Protection Board of India (DPBI):</strong> If your grievance is not resolved to your satisfaction within 30 days, you have the statutory right under Section 13(4) of the DPDP Act, 2023 to file an official complaint with the <strong>Data Protection Board of India</strong>.
                </div>
            </section>

        </div>

        <!-- Back to Top / Home Navigation -->
        <div class="mt-8 text-center text-xs text-stone-500">
            <a href="<?= SITE_URL ?>" class="font-bold text-[#18181B] hover:underline">&larr; Back to JayArt Atelier Gallery</a>
            <span class="mx-3">•</span>
            <a href="<?= SITE_URL ?>/terms" class="font-bold text-[#18181B] hover:underline">Read Terms of Service &rarr;</a>
        </div>

    </div>
</div>

