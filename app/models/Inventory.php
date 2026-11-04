<?php
/**
 * JayArt Inventory Model & Audit Trail
 */

declare(strict_types=1);

class Inventory extends Model {
    protected string $table = 'inventory_logs';

    /**
     * Get Recent Stock Logs with Product Details
     */
    public function getRecentLogs(int $limit = 50): array {
        $sql = "SELECT log.*, p.title as product_title, p.artist_name, p.medium
                FROM `inventory_logs` log
                JOIN `products` p ON log.product_id = p.id
                ORDER BY log.id DESC LIMIT {$limit}";
        return $this->fetchAll($sql);
    }

    /**
     * Get Low Stock Paintings (stock <= threshold)
     */
    public function getLowStock(int $threshold = 2): array {
        $sql = "SELECT p.*, c.name as category_name
                FROM `products` p
                LEFT JOIN `categories` c ON p.category_id = c.id
                WHERE p.status = 'active' AND p.stock_quantity <= :threshold
                ORDER BY p.stock_quantity ASC";
        return $this->fetchAll($sql, ['threshold' => $threshold]);
    }

    /**
     * Manually Adjust Stock Quantity
     */
    public function adjustStock(int $productId, int $newStock, string $reason = 'Manual adjustment'): bool {
        $pRow = $this->fetch("SELECT stock_quantity FROM `products` WHERE `id` = :id", ['id' => $productId]);
        if (!$pRow) return false;

        $previous = (int)$pRow['stock_quantity'];
        $diff = $newStock - $previous;

        $this->query("UPDATE `products` SET `stock_quantity` = :stock WHERE `id` = :id", [
            'stock' => max(0, $newStock),
            'id'    => $productId
        ]);

        $this->insert([
            'product_id'      => $productId,
            'change_type'     => 'manual_edit',
            'quantity_change' => $diff,
            'previous_stock'  => $previous,
            'new_stock'       => max(0, $newStock),
            'note'            => $reason,
        ]);

        return true;
    }
}

