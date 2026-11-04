<?php
/**
 * JayArt Public Offers & Coupon Vouchers Controller
 */

declare(strict_types=1);

class OfferController extends Controller {
    /**
     * Display Active Offers, Coupons, and Eligible Paintings
     */
    public function index(): void {
        $activeOffers = Coupon::allActive();
        $productModel = new Product();
        $eligibleArt = $productModel->getFeatured(4);

        $this->render('offers/index', [
            'offers'      => $activeOffers,
            'eligibleArt' => $eligibleArt,
            'seo'         => [
                'title'       => 'Exclusive Art Offers & Coupon Codes — JayArt Gallery',
                'description' => 'Browse verified active discount coupons, vouchers, and festival offers for authentic original paintings and fine art acquisitions.',
                'keywords'    => 'art coupon codes, painting discount offers, fine art promo code, gallery vouchers',
            ]
        ]);
    }
}

