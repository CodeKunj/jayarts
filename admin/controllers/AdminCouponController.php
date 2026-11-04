<?php
/**
 * JayArt Admin Coupon & Promotional Offer Controller
 * Manage discount vouchers, minimum orders, usage counters and validity
 */

declare(strict_types=1);

class AdminCouponController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Coupon List & Management Dashboard
     */
    public function index(): void {
        $coupons = Coupon::all();
        $stats = Coupon::getStats();

        $this->renderAdmin('coupons/index', [
            'coupons' => $coupons,
            'stats'   => $stats,
            'title'   => 'Promotional Coupons & Offers — JayArt Admin'
        ]);
    }

    /**
     * Store New Coupon
     */
    public function store(): void {
        $code = strtoupper(trim((string)($_POST['code'] ?? '')));
        $discountType = in_array($_POST['discount_type'] ?? '', ['percentage', 'fixed']) ? $_POST['discount_type'] : 'percentage';
        $discountValue = (float)($_POST['discount_value'] ?? 0);
        $minOrder = (float)($_POST['min_order_amount'] ?? 0);
        $maxUses = !empty($_POST['max_uses']) ? (int)$_POST['max_uses'] : 100;
        $expiresAt = !empty($_POST['expires_at']) ? trim((string)$_POST['expires_at']) : null;
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        if (empty($code)) {
            Auth::setFlash('error', 'Coupon code cannot be empty.');
            header('Location: ' . ADMIN_URL . '/coupons');
            exit;
        }

        if ($discountValue <= 0) {
            Auth::setFlash('error', 'Discount value must be greater than zero.');
            header('Location: ' . ADMIN_URL . '/coupons');
            exit;
        }

        if ($discountType === 'percentage' && $discountValue > 100) {
            Auth::setFlash('error', 'Percentage discount cannot exceed 100%.');
            header('Location: ' . ADMIN_URL . '/coupons');
            exit;
        }

        // Check if code already exists
        $existing = Coupon::findByCode($code);
        if ($existing) {
            Auth::setFlash('error', "Coupon code '{$code}' already exists. Please choose a different code.");
            header('Location: ' . ADMIN_URL . '/coupons');
            exit;
        }

        Coupon::create([
            'code'             => $code,
            'title'            => $title ?: ($discountType === 'percentage' ? "{$discountValue}% Off Promo" : "Flat " . CURRENCY_SYMBOL . " {$discountValue} Off"),
            'description'      => $description ?: 'Special collector promotional voucher.',
            'discount_type'    => $discountType,
            'discount_value'   => $discountValue,
            'min_order_amount' => $minOrder,
            'max_uses'         => $maxUses,
            'expires_at'       => $expiresAt,
            'is_active'        => $isActive,
        ]);

        // Invalidate homepage cached coupon offers
        Cache::deletePattern('home:*');

        Auth::setFlash('success', "Coupon '{$code}' created successfully!");
        header('Location: ' . ADMIN_URL . '/coupons');
        exit;
    }

    /**
     * Update Existing Coupon
     */
    public function update(int $id): void {
        $coupon = Coupon::findById($id);
        if (!$coupon) {
            Auth::setFlash('error', 'Coupon voucher not found.');
            header('Location: ' . ADMIN_URL . '/coupons');
            exit;
        }

        $code = strtoupper(trim((string)($_POST['code'] ?? '')));
        $discountType = in_array($_POST['discount_type'] ?? '', ['percentage', 'fixed']) ? $_POST['discount_type'] : 'percentage';
        $discountValue = (float)($_POST['discount_value'] ?? 0);
        $minOrder = (float)($_POST['min_order_amount'] ?? 0);
        $maxUses = !empty($_POST['max_uses']) ? (int)$_POST['max_uses'] : 100;
        $expiresAt = !empty($_POST['expires_at']) ? trim((string)$_POST['expires_at']) : null;
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));

        if (empty($code) || $discountValue <= 0) {
            Auth::setFlash('error', 'Please provide a valid code and discount value.');
            header('Location: ' . ADMIN_URL . '/coupons');
            exit;
        }

        Coupon::update($id, [
            'code'             => $code,
            'title'            => $title,
            'description'      => $description,
            'discount_type'    => $discountType,
            'discount_value'   => $discountValue,
            'min_order_amount' => $minOrder,
            'max_uses'         => $maxUses,
            'expires_at'       => $expiresAt,
            'is_active'        => $isActive,
        ]);

        Cache::deletePattern('home:*');
        Auth::setFlash('success', "Coupon '{$code}' updated successfully.");
        header('Location: ' . ADMIN_URL . '/coupons');
        exit;
    }

    /**
     * Toggle Active State
     */
    public function toggle(int $id): void {
        Coupon::toggle($id);
        Cache::deletePattern('home:*');
        Auth::setFlash('success', 'Coupon status updated.');
        header('Location: ' . ADMIN_URL . '/coupons');
        exit;
    }

    /**
     * Delete Coupon
     */
    public function delete(int $id): void {
        Coupon::delete($id);
        Cache::deletePattern('home:*');
        Auth::setFlash('success', 'Coupon voucher deleted permanently.');
        header('Location: ' . ADMIN_URL . '/coupons');
        exit;
    }
}

