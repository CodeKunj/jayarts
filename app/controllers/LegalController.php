<?php
/**
 * JayArt Legal & DPDP Act 2023 Compliance Controller
 */

declare(strict_types=1);

class LegalController extends Controller {

    /**
     * Display DPDP Act, 2023 Statutory Notice & Comprehensive Privacy Policy
     */
    public function privacy(): void {
        $seo = SEO::getGlobalSettings();

        $this->render('legal/privacy', [
            'seo' => [
                'title'       => 'Digital Personal Data Protection (DPDP) Privacy Policy | JayArt',
                'description' => 'Official privacy notice and data principal rights under India’s Digital Personal Data Protection (DPDP) Act, 2023 for JayArt Fine Art Gallery.',
                'keywords'    => 'DPDP Act 2023, Privacy Policy, Data Principal Rights, JayArt Data Protection, Grievance Officer, Indian Data Protection Law',
                'og_image'    => $seo['default_og_image'] ?? '',
                'og_type'     => 'website'
            ]
        ]);
    }

    /**
     * Display Collector Terms of Service & Provenance Conditions
     */
    public function terms(): void {
        $seo = SEO::getGlobalSettings();

        $this->render('legal/terms', [
            'seo' => [
                'title'       => 'Terms of Service & Atelier Acquisition Conditions | JayArt',
                'description' => 'Official collector terms of service, provenance certificate guarantees, framing policy, and acquisition terms for JayArt Fine Art Gallery.',
                'keywords'    => 'Terms of Service, Art Acquisition, Authenticity Guarantee, JayArt Policy, Return Policy',
                'og_image'    => $seo['default_og_image'] ?? '',
                'og_type'     => 'website'
            ]
        ]);
    }
}

