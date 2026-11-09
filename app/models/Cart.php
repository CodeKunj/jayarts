<?php
/**
 * JayArt Cart Model
 * Session-based cart with frame options and coupon support
 */

declare(strict_types=1);

class Cart {
    /**
     * Get All Items in Cart
     */
    public static function getItems(): array {
        return $_SESSION['cart'] ?? [];
    }

    /**
     * Get Total Number of Items in Cart (for mobile & desktop badges)
     */
    public static function getCount(): int {
        $count = 0;
        foreach (self::getItems() as $item) {
            $count += (int)($item['quantity'] ?? 1);
        }
        return $count;
    }

    /**
     * Add Item to Cart
     */
    public static function add(int $productId, int $quantity = 1, string $frameChosen = 'Rolled Canvas'): array {
        $productModel = new Product();
        $product = $productModel->find($productId);

        if (!$product || $product['status'] !== 'active') {
            return ['success' => false, 'message' => 'Painting is currently not available.'];
        }

        // Frame surcharge calculation
        $frameExtra = 0.00;
        if (str_contains(strtolower($frameChosen), 'teak') || str_contains(strtolower($frameChosen), 'brass') || str_contains(strtolower($frameChosen), 'frame')) {
            $frameExtra = 2500.00; // Premium floating frame
        } elseif (str_contains(strtolower($frameChosen), 'stretched')) {
            $frameExtra = 1200.00; // Stretched canvas
        }

        $basePrice = (float)(!empty($product['discount_price']) ? $product['discount_price'] : $product['price']);
        $unitPrice = $basePrice + $frameExtra;

        // Primary Image
        $images = $productModel->getImages($productId);
        $primaryImage = !empty($images[0]['image_path']) ? $images[0]['image_path'] : null;

        // Cart item unique key (product_id + frame)
        $itemKey = $productId . '_' . md5($frameChosen);

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$itemKey])) {
            $_SESSION['cart'][$itemKey]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$itemKey] = [
                'item_key'     => $itemKey,
                'product_id'   => (int)$product['id'],
                'title'        => $product['title'],
                'slug'         => $product['slug'],
                'artist_name'  => $product['artist_name'],
                'medium'       => $product['medium'],
                'dimensions'   => $product['dimensions'],
                'price'        => $unitPrice,
                'base_price'   => $basePrice,
                'frame_extra'  => $frameExtra,
                'frame_chosen' => $frameChosen,
                'quantity'     => $quantity,
                'image'        => $primaryImage,
            ];
        }

        return [
            'success'   => true,
            'message'   => 'Painting added to your collection cart.',
            'cart_count'=> self::getCount(),
            'cart_total'=> self::getSubtotal(),
        ];
    }

    /**
     * Update Item Quantity
     */
    public static function update(string $itemKey, int $quantity): bool {
        if ($quantity <= 0) {
            return self::remove($itemKey);
        }

        if (isset($_SESSION['cart'][$itemKey])) {
            $_SESSION['cart'][$itemKey]['quantity'] = $quantity;
            return true;
        }
        return false;
    }

    /**
     * Remove Item from Cart
     */
    public static function remove(string $itemKey): bool {
        if (isset($_SESSION['cart'][$itemKey])) {
            unset($_SESSION['cart'][$itemKey]);
            return true;
        }
        return false;
    }

    /**
     * Clear Entire Cart
     */
    public static function clear(): void {
        unset($_SESSION['cart']);
        unset($_SESSION['applied_coupon']);
    }

    /**
     * Calculate Subtotal
     */
    public static function getSubtotal(): float {
        $subtotal = 0.00;
        foreach (self::getItems() as $item) {
            $subtotal += ((float)$item['price'] * (int)$item['quantity']);
        }
        return $subtotal;
    }

    /**
     * Apply Coupon Code
     */
    public static function applyCoupon(string $code): array {
        $subtotal = self::getSubtotal();
        if ($subtotal <= 0.00) {
            return ['success' => false, 'message' => 'Your cart is empty. Add a painting before applying a promo code.'];
        }

        $result = Coupon::validate($code, $subtotal);
        if (!$result['valid']) {
            return [
                'success' => false,
                'message' => $result['message'],
            ];
        }

        $coupon = $result['coupon'];
        $_SESSION['applied_coupon'] = [
            'code'           => $coupon['code'],
            'title'          => $coupon['title'] ?? $coupon['code'],
            'discount_type'  => $coupon['discount_type'],
            'discount_value' => (float)$coupon['discount_value'],
        ];

        $subtotal = self::getSubtotal();
        $discountAmount = self::getDiscountAmount();
        $shipping = self::getShippingFee();
        $total = self::getTotal();

        return [
            'success'            => true,
            'message'            => $result['message'],
            'code'               => $coupon['code'],
            'title'              => $coupon['title'] ?? ($coupon['code'] . ' Discount'),
            'discount_type'      => $coupon['discount_type'],
            'discount_value'     => (float)$coupon['discount_value'],
            'discount'           => $discountAmount,
            'discount_formatted' => Validator::formatPrice($discountAmount),
            'subtotal'           => $subtotal,
            'subtotal_formatted' => Validator::formatPrice($subtotal),
            'shipping'           => $shipping,
            'shipping_formatted' => ($shipping > 0) ? Validator::formatPrice($shipping) : 'FREE',
            'total'              => $total,
            'total_formatted'    => Validator::formatPrice($total),
        ];
    }

    /**
     * Remove Applied Coupon Code
     */
    public static function removeCoupon(): array {
        unset($_SESSION['applied_coupon']);

        $subtotal = self::getSubtotal();
        $discountAmount = self::getDiscountAmount();
        $shipping = self::getShippingFee();
        $total = self::getTotal();

        return [
            'success'            => true,
            'message'            => 'Coupon removed successfully.',
            'discount'           => $discountAmount,
            'discount_formatted' => Validator::formatPrice($discountAmount),
            'subtotal'           => $subtotal,
            'subtotal_formatted' => Validator::formatPrice($subtotal),
            'shipping'           => $shipping,
            'shipping_formatted' => ($shipping > 0) ? Validator::formatPrice($shipping) : 'FREE',
            'total'              => $total,
            'total_formatted'    => Validator::formatPrice($total),
        ];
    }

    /**
     * Calculate Discount Amount from Applied Coupon
     */
    public static function getDiscountAmount(): float {
        if (empty($_SESSION['applied_coupon'])) {
            return 0.00;
        }

        $coupon = $_SESSION['applied_coupon'];
        $subtotal = self::getSubtotal();

        if ($coupon['discount_type'] === 'percentage') {
            return round(($subtotal * $coupon['discount_value']) / 100, 2);
        } else {
            return min($subtotal, (float)$coupon['discount_value']);
        }
    }

    /**
     * Calculate Delivery / Shipping Fee (Dynamic based on Admin Settings)
     */
    public static function getShippingFee(): float {
        $subtotal = self::getSubtotal();
        if ($subtotal <= 0.00) {
            return 0.00;
        }

        $standardFee = (float)(Setting::get('standard_shipping_fee') ?? Setting::get('delivery_charge') ?? '0');
        $freeThreshold = (float)(Setting::get('free_shipping_threshold') ?? '0');

        // If free delivery threshold is configured (> 0) and subtotal meets or exceeds threshold
        if ($freeThreshold > 0.00 && $subtotal >= $freeThreshold) {
            return 0.00;
        }

        return max(0.00, $standardFee);
    }

    /**
     * Calculate Final Total
     */
    public static function getTotal(): float {
        $subtotal = self::getSubtotal();
        if ($subtotal === 0.00) {
            return 0.00;
        }
        $discount = self::getDiscountAmount();
        $shipping = self::getShippingFee();
        return max(0.00, $subtotal - $discount + $shipping);
    }
}
