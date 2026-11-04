<?php
/**
 * JayArt Coupon & Offer Model
 * Manages promotional discount vouchers, validity rules, and admin CRUD
 */

declare(strict_types=1);

class Coupon {
    /**
     * Default curated dummy coupons for offline/demo/preview mode
     */
    private static array $dummyCoupons = [
        [
            'id'               => 1,
            'code'             => 'SPRING50',
            'title'            => 'Spring Fine Art Festival',
            'description'      => 'Enjoy a special 50% curation discount on selected original oil & acrylic paintings.',
            'discount_type'    => 'percentage',
            'discount_value'   => 50.00,
            'min_order_amount' => 0.00,
            'max_uses'         => 500,
            'uses_count'       => 142,
            'expires_at'       => '2026-12-31',
            'is_active'        => 1,
            'created_at'       => '2026-01-15 10:00:00',
        ],
        [
            'id'               => 2,
            'code'             => 'WELCOME10',
            'title'            => 'New Collector Welcome Gift',
            'description'      => 'Take 10% off your first gallery acquisition. Includes free insured shipping.',
            'discount_type'    => 'percentage',
            'discount_value'   => 10.00,
            'min_order_amount' => 2500.00,
            'max_uses'         => 1000,
            'uses_count'       => 387,
            'expires_at'       => '2026-12-31',
            'is_active'        => 1,
            'created_at'       => '2026-01-01 08:30:00',
        ],
        [
            'id'               => 3,
            'code'             => 'FLAT2000',
            'title'            => 'Masterpiece Grand Discount',
            'description'      => 'Instant flat ₹2,000 off on large-format canvases and bespoke framed artworks.',
            'discount_type'    => 'fixed',
            'discount_value'   => 2000.00,
            'min_order_amount' => 15000.00,
            'max_uses'         => 200,
            'uses_count'       => 68,
            'expires_at'       => '2026-11-30',
            'is_active'        => 1,
            'created_at'       => '2026-02-01 12:00:00',
        ],
        [
            'id'               => 4,
            'code'             => 'ARTLOVER',
            'title'            => 'Artisan Framing Privilege',
            'description'      => 'Special 15% discount on all museum-framed canvases and heritage artworks.',
            'discount_type'    => 'percentage',
            'discount_value'   => 15.00,
            'min_order_amount' => 5000.00,
            'max_uses'         => 350,
            'uses_count'       => 94,
            'expires_at'       => '2026-12-15',
            'is_active'        => 1,
            'created_at'       => '2026-02-10 14:15:00',
        ],
        [
            'id'               => 5,
            'code'             => 'FESTIVE25',
            'title'            => 'Seasonal Heritage Offer',
            'description'      => 'Exclusive 25% off on divine portraits, spiritual art, and limited edition original pieces.',
            'discount_type'    => 'percentage',
            'discount_value'   => 25.00,
            'min_order_amount' => 8000.00,
            'max_uses'         => 150,
            'uses_count'       => 150,
            'expires_at'       => '2026-04-30',
            'is_active'        => 0, // Inactive example to test admin toggles
            'created_at'       => '2026-01-20 09:00:00',
        ],
    ];

    /**
     * Get All Coupons
     */
    public static function all(): array {
        $db = Database::connect();
        if ($db) {
            try {
                $stmt = $db->query("SELECT * FROM `coupons` ORDER BY `id` DESC");
                $results = $stmt->fetchAll();
                if (!empty($results)) {
                    return $results;
                }
            } catch (\Throwable $e) {
                // fallback to dummy on DB error
            }
        }
        return self::getSessionCoupons();
    }

    /**
     * Get All Active & Non-Expired Coupons (For Public Offers Page & Home Showcase)
     */
    public static function allActive(): array {
        $all = self::all();
        $active = [];
        $today = date('Y-m-d');

        foreach ($all as $c) {
            $isActive = !empty($c['is_active']);
            $isNotExpired = empty($c['expires_at']) || ($c['expires_at'] >= $today);
            $hasUsesLeft = empty($c['max_uses']) || ((int)$c['uses_count'] < (int)$c['max_uses']);

            if ($isActive && $isNotExpired && $hasUsesLeft) {
                $active[] = $c;
            }
        }
        return $active;
    }

    /**
     * Find Coupon by Code
     */
    public static function findByCode(string $code): ?array {
        $code = strtoupper(trim($code));
        if ($code === '') {
            return null;
        }

        $db = Database::connect();
        if ($db) {
            try {
                $stmt = $db->prepare("SELECT * FROM `coupons` WHERE UPPER(`code`) = :code LIMIT 1");
                $stmt->execute(['code' => $code]);
                $coupon = $stmt->fetch();
                if ($coupon) {
                    return $coupon;
                }
            } catch (\Throwable $e) {
                // fallback
            }
        }

        $all = self::getSessionCoupons();
        foreach ($all as $c) {
            if (strtoupper($c['code']) === $code) {
                return $c;
            }
        }
        return null;
    }

    /**
     * Find Coupon by ID
     */
    public static function findById(int $id): ?array {
        $db = Database::connect();
        if ($db) {
            try {
                $stmt = $db->prepare("SELECT * FROM `coupons` WHERE `id` = :id LIMIT 1");
                $stmt->execute(['id' => $id]);
                $coupon = $stmt->fetch();
                if ($coupon) {
                    return $coupon;
                }
            } catch (\Throwable $e) {
                // fallback
            }
        }

        $all = self::getSessionCoupons();
        foreach ($all as $c) {
            if ((int)$c['id'] === $id) {
                return $c;
            }
        }
        return null;
    }

    /**
     * Validate Coupon for a given Cart Subtotal
     */
    public static function validate(string $code, float $subtotal): array {
        $coupon = self::findByCode($code);
        if (!$coupon) {
            return [
                'valid'   => false,
                'message' => 'Invalid coupon code. Please check and try again.',
                'coupon'  => null,
                'discount'=> 0.00
            ];
        }

        if (empty($coupon['is_active'])) {
            return [
                'valid'   => false,
                'message' => 'This coupon voucher is currently inactive or disabled.',
                'coupon'  => null,
                'discount'=> 0.00
            ];
        }

        if (!empty($coupon['expires_at']) && strtotime($coupon['expires_at']) < strtotime('today')) {
            return [
                'valid'   => false,
                'message' => 'This coupon has expired on ' . date('d M Y', strtotime($coupon['expires_at'])) . '.',
                'coupon'  => null,
                'discount'=> 0.00
            ];
        }

        if (!empty($coupon['max_uses']) && (int)$coupon['uses_count'] >= (int)$coupon['max_uses']) {
            return [
                'valid'   => false,
                'message' => 'This coupon voucher has reached its maximum redemption limit.',
                'coupon'  => null,
                'discount'=> 0.00
            ];
        }

        $minOrder = (float)($coupon['min_order_amount'] ?? 0.00);
        if ($subtotal < $minOrder) {
            return [
                'valid'   => false,
                'message' => 'Minimum order value of ' . CURRENCY_SYMBOL . ' ' . number_format($minOrder) . ' is required for coupon ' . $coupon['code'] . '.',
                'coupon'  => null,
                'discount'=> 0.00
            ];
        }

        // Calculate discount
        $discount = 0.00;
        $discountValue = (float)$coupon['discount_value'];
        if ($coupon['discount_type'] === 'percentage') {
            $discount = round(($subtotal * $discountValue) / 100, 2);
        } else {
            $discount = min($subtotal, $discountValue);
        }

        return [
            'valid'    => true,
            'message'  => 'Coupon applied! You saved ' . CURRENCY_SYMBOL . ' ' . number_format($discount, 2) . '.',
            'coupon'   => $coupon,
            'discount' => $discount
        ];
    }

    /**
     * Increment Usage Count
     */
    public static function incrementUsage(string $code): void {
        $db = Database::connect();
        if ($db) {
            try {
                $stmt = $db->prepare("UPDATE `coupons` SET `uses_count` = `uses_count` + 1 WHERE UPPER(`code`) = :code");
                $stmt->execute(['code' => strtoupper($code)]);
                return;
            } catch (\Throwable $e) {
                // ignore
            }
        }

        // Update in session
        $coupons = self::getSessionCoupons();
        foreach ($coupons as &$c) {
            if (strtoupper($c['code']) === strtoupper($code)) {
                $c['uses_count'] = ((int)$c['uses_count']) + 1;
                break;
            }
        }
        $_SESSION['admin_coupons_cache'] = $coupons;
    }

    /**
     * Create New Coupon
     */
    public static function create(array $data): int {
        $code = strtoupper(trim((string)($data['code'] ?? '')));
        $discountType = in_array($data['discount_type'] ?? '', ['percentage', 'fixed']) ? $data['discount_type'] : 'percentage';
        $discountValue = (float)($data['discount_value'] ?? 0);
        $minOrder = (float)($data['min_order_amount'] ?? 0);
        $maxUses = !empty($data['max_uses']) ? (int)$data['max_uses'] : 100;
        $expiresAt = !empty($data['expires_at']) ? $data['expires_at'] : null;
        $isActive = isset($data['is_active']) ? (int)$data['is_active'] : 1;

        $db = Database::connect();
        if ($db) {
            try {
                $stmt = $db->prepare("
                    INSERT INTO `coupons` 
                    (`code`, `discount_type`, `discount_value`, `min_order_amount`, `max_uses`, `uses_count`, `expires_at`, `is_active`)
                    VALUES (:code, :discount_type, :discount_value, :min_order, :max_uses, 0, :expires_at, :is_active)
                ");
                $stmt->execute([
                    'code'           => $code,
                    'discount_type'  => $discountType,
                    'discount_value' => $discountValue,
                    'min_order'      => $minOrder,
                    'max_uses'       => $maxUses,
                    'expires_at'     => $expiresAt,
                    'is_active'      => $isActive,
                ]);
                return (int)$db->lastInsertId();
            } catch (\Throwable $e) {
                // fall back to session
            }
        }

        $coupons = self::getSessionCoupons();
        $newId = count($coupons) > 0 ? (max(array_column($coupons, 'id')) + 1) : 1;
        $newCoupon = [
            'id'               => $newId,
            'code'             => $code,
            'title'            => $data['title'] ?? ($discountType === 'percentage' ? "{$discountValue}% Off Promo" : "Flat " . CURRENCY_SYMBOL . " {$discountValue} Off"),
            'description'      => $data['description'] ?? 'Exclusive gallery acquisition discount voucher.',
            'discount_type'    => $discountType,
            'discount_value'   => $discountValue,
            'min_order_amount' => $minOrder,
            'max_uses'         => $maxUses,
            'uses_count'       => 0,
            'expires_at'       => $expiresAt,
            'is_active'        => $isActive,
            'created_at'       => date('Y-m-d H:i:s'),
        ];
        array_unshift($coupons, $newCoupon);
        $_SESSION['admin_coupons_cache'] = $coupons;
        return $newId;
    }

    /**
     * Update Coupon
     */
    public static function update(int $id, array $data): bool {
        $db = Database::connect();
        if ($db) {
            try {
                $stmt = $db->prepare("
                    UPDATE `coupons` SET
                        `code` = :code,
                        `discount_type` = :discount_type,
                        `discount_value` = :discount_value,
                        `min_order_amount` = :min_order,
                        `max_uses` = :max_uses,
                        `expires_at` = :expires_at,
                        `is_active` = :is_active
                    WHERE `id` = :id
                ");
                return $stmt->execute([
                    'id'             => $id,
                    'code'           => strtoupper(trim((string)$data['code'])),
                    'discount_type'  => $data['discount_type'],
                    'discount_value' => (float)$data['discount_value'],
                    'min_order'      => (float)($data['min_order_amount'] ?? 0),
                    'max_uses'       => (int)($data['max_uses'] ?? 100),
                    'expires_at'     => !empty($data['expires_at']) ? $data['expires_at'] : null,
                    'is_active'      => (int)($data['is_active'] ?? 1),
                ]);
            } catch (\Throwable $e) {
                // fall back to session
            }
        }

        $coupons = self::getSessionCoupons();
        foreach ($coupons as &$c) {
            if ((int)$c['id'] === $id) {
                $c['code'] = strtoupper(trim((string)$data['code']));
                $c['discount_type'] = $data['discount_type'];
                $c['discount_value'] = (float)$data['discount_value'];
                $c['min_order_amount'] = (float)($data['min_order_amount'] ?? 0);
                $c['max_uses'] = (int)($data['max_uses'] ?? 100);
                $c['expires_at'] = !empty($data['expires_at']) ? $data['expires_at'] : null;
                $c['is_active'] = (int)($data['is_active'] ?? 1);
                if (isset($data['title'])) $c['title'] = $data['title'];
                if (isset($data['description'])) $c['description'] = $data['description'];
                break;
            }
        }
        $_SESSION['admin_coupons_cache'] = $coupons;
        return true;
    }

    /**
     * Toggle Active State
     */
    public static function toggle(int $id): bool {
        $db = Database::connect();
        if ($db) {
            try {
                $stmt = $db->prepare("UPDATE `coupons` SET `is_active` = IF(`is_active` = 1, 0, 1) WHERE `id` = :id");
                return $stmt->execute(['id' => $id]);
            } catch (\Throwable $e) {
                // fall back
            }
        }

        $coupons = self::getSessionCoupons();
        foreach ($coupons as &$c) {
            if ((int)$c['id'] === $id) {
                $c['is_active'] = $c['is_active'] ? 0 : 1;
                break;
            }
        }
        $_SESSION['admin_coupons_cache'] = $coupons;
        return true;
    }

    /**
     * Delete Coupon
     */
    public static function delete(int $id): bool {
        $db = Database::connect();
        if ($db) {
            try {
                $stmt = $db->prepare("DELETE FROM `coupons` WHERE `id` = :id");
                return $stmt->execute(['id' => $id]);
            } catch (\Throwable $e) {
                // fall back
            }
        }

        $coupons = self::getSessionCoupons();
        $coupons = array_filter($coupons, fn($c) => (int)$c['id'] !== $id);
        $_SESSION['admin_coupons_cache'] = array_values($coupons);
        return true;
    }

    /**
     * Calculate Summary Statistics for Admin Panel
     */
    public static function getStats(): array {
        $all = self::all();
        $activeCount = 0;
        $totalUses = 0;
        $totalEstimatedSavings = 0.00;

        foreach ($all as $c) {
            if (!empty($c['is_active'])) {
                $activeCount++;
            }
            $uses = (int)($c['uses_count'] ?? 0);
            $totalUses += $uses;
            if ($c['discount_type'] === 'fixed') {
                $totalEstimatedSavings += $uses * (float)$c['discount_value'];
            } else {
                // estimate based on avg order of ₹12,000
                $totalEstimatedSavings += $uses * (12000 * ((float)$c['discount_value'] / 100));
            }
        }

        return [
            'total_coupons'          => count($all),
            'active_coupons'         => $activeCount,
            'total_redemptions'      => $totalUses,
            'est_customer_savings'   => $totalEstimatedSavings,
        ];
    }

    /**
     * Session-based storage helper for demo/preview without MySQL
     */
    private static function getSessionCoupons(): array {
        if (!isset($_SESSION['admin_coupons_cache'])) {
            $_SESSION['admin_coupons_cache'] = self::$dummyCoupons;
        }
        return $_SESSION['admin_coupons_cache'];
    }
}

