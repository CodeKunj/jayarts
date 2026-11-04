<?php
/**
 * JayArt Order Model
 */

declare(strict_types=1);

class Order extends Model {
    protected string $table = 'orders';

    /**
     * Generate Unique Order Number
     */
    public static function generateOrderNumber(): string {
        return 'JA-' . date('Ymd') . '-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 5));
    }

    /**
     * Create Order from Checkout
     */
    public function createOrder(array $orderData, array $cartItems): ?int {
        if ($this->db === null) {
            return null;
        }

        try {
            $this->db->beginTransaction();

            $orderNumber = self::generateOrderNumber();

            $sql = "INSERT INTO `orders` (
                `order_number`, `user_id`, `customer_name`, `customer_email`, `customer_phone`,
                `shipping_address`, `billing_address`, `subtotal`, `tax_amount`, `shipping_fee`,
                `discount_amount`, `coupon_code`, `total_amount`, `payment_method`, `payment_status`,
                `payment_reference`, `order_status`, `notes`
            ) VALUES (
                :order_number, :user_id, :customer_name, :customer_email, :customer_phone,
                :shipping_address, :billing_address, :subtotal, :tax_amount, :shipping_fee,
                :discount_amount, :coupon_code, :total_amount, :payment_method, :payment_status,
                :payment_reference, :order_status, :notes
            )";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'order_number'      => $orderNumber,
                'user_id'           => $orderData['user_id'] ?? null,
                'customer_name'     => $orderData['customer_name'],
                'customer_email'    => $orderData['customer_email'],
                'customer_phone'    => $orderData['customer_phone'],
                'shipping_address'  => json_encode($orderData['shipping_address']),
                'billing_address'   => json_encode($orderData['billing_address'] ?? $orderData['shipping_address']),
                'subtotal'          => $orderData['subtotal'],
                'tax_amount'        => $orderData['tax_amount'] ?? 0.00,
                'shipping_fee'      => $orderData['shipping_fee'] ?? 0.00,
                'discount_amount'   => $orderData['discount_amount'] ?? 0.00,
                'coupon_code'       => $orderData['coupon_code'] ?? null,
                'total_amount'      => $orderData['total_amount'],
                'payment_method'    => $orderData['payment_method'],
                'payment_status'    => $orderData['payment_status'] ?? 'pending',
                'payment_reference' => $orderData['payment_reference'] ?? null,
                'order_status'      => 'pending',
                'notes'             => $orderData['notes'] ?? null,
            ]);

            $orderId = (int)$this->db->lastInsertId();

            // Insert Order Items & decrement product stock
            $itemStmt = $this->db->prepare("INSERT INTO `order_items` (
                `order_id`, `product_id`, `product_title`, `artist_name`, `dimensions`, `price`, `quantity`, `frame_chosen`, `total_price`
            ) VALUES (
                :order_id, :product_id, :product_title, :artist_name, :dimensions, :price, :quantity, :frame_chosen, :total_price
            )");

            $stockStmt = $this->db->prepare("UPDATE `products` SET `stock_quantity` = GREATEST(0, `stock_quantity` - :qty) WHERE `id` = :id");
            $logStmt = $this->db->prepare("INSERT INTO `inventory_logs` (`product_id`, `change_type`, `quantity_change`, `previous_stock`, `new_stock`, `note`) VALUES (:pid, 'sale', :qty_change, :prev, :curr, :note)");

            foreach ($cartItems as $item) {
                $itemTotal = (float)$item['price'] * (int)$item['quantity'];
                $itemStmt->execute([
                    'order_id'      => $orderId,
                    'product_id'    => $item['product_id'],
                    'product_title' => $item['title'],
                    'artist_name'   => $item['artist_name'] ?? null,
                    'dimensions'    => $item['dimensions'] ?? null,
                    'price'         => $item['price'],
                    'quantity'      => $item['quantity'],
                    'frame_chosen'  => $item['frame_chosen'] ?? 'Rolled Canvas',
                    'total_price'   => $itemTotal,
                ]);

                // Reduce inventory & log
                $pid = (int)$item['product_id'];
                $qty = (int)$item['quantity'];
                
                // Get current stock
                $pRow = $this->fetch("SELECT stock_quantity FROM `products` WHERE `id` = :id", ['id' => $pid]);
                $prevStock = (int)($pRow['stock_quantity'] ?? 1);
                $newStock = max(0, $prevStock - $qty);

                $stockStmt->execute(['qty' => $qty, 'id' => $pid]);
                $logStmt->execute([
                    'pid'        => $pid,
                    'qty_change' => -$qty,
                    'prev'       => $prevStock,
                    'curr'       => $newStock,
                    'note'       => "Order #{$orderNumber} sale",
                ]);
            }

            // Create initial Delivery Tracking placeholder
            $trackStmt = $this->db->prepare("INSERT INTO `delivery_tracking` (`order_id`, `courier_name`, `tracking_number`, `current_status`) VALUES (:oid, 'Fine Art Logistics', :tnum, 'Order Received')");
            $trackStmt->execute([
                'oid'  => $orderId,
                'tnum' => 'TRK-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8))
            ]);

            $this->db->commit();

            // Invalidate product & catalog caches so stock decrements reflect immediately
            Cache::deletePattern('products:*');
            Cache::deletePattern('home:*');

            return $orderId;
        } catch (Throwable $e) {
            if ($this->db && $this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('Order Creation Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Find Order by Order Number
     */
    public function findByOrderNumber(string $orderNumber): ?array {
        $order = $this->findBy('order_number', $orderNumber);
        if ($order) {
            $order['items'] = $this->getOrderItems((int)$order['id']);
            $order['tracking'] = $this->getDeliveryTracking((int)$order['id']);
            $order['shipping_address'] = json_decode((string)$order['shipping_address'], true) ?: [];
            $order['billing_address'] = json_decode((string)$order['billing_address'], true) ?: [];
        }
        return $order;
    }

    /**
     * Get Order Items
     */
    public function getOrderItems(int $orderId): array {
        return $this->fetchAll("SELECT * FROM `order_items` WHERE `order_id` = :id", ['id' => $orderId]);
    }

    /**
     * Get Delivery Tracking
     */
    public function getDeliveryTracking(int $orderId): ?array {
        $tracking = $this->fetch("SELECT * FROM `delivery_tracking` WHERE `order_id` = :id LIMIT 1", ['id' => $orderId]);
        if ($tracking) {
            $tracking['milestones'] = $this->fetchAll(
                "SELECT * FROM `delivery_milestones` WHERE `delivery_id` = :did ORDER BY `milestone_time` ASC",
                ['did' => $tracking['id']]
            );
        }
        return $tracking;
    }

    /**
     * Update Order Payment Status
     */
    public function updatePaymentStatus(int $orderId, string $status, ?string $ref = null, ?string $receipt = null): bool {
        $data = ['payment_status' => $status];
        if ($ref !== null) {
            $data['payment_reference'] = $ref;
        }
        if ($receipt !== null) {
            $data['payment_receipt'] = $receipt;
        }
        return $this->update($orderId, $data);
    }
}

