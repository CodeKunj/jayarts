<?php
/**
 * JayArt Product Reviews Model
 */

declare(strict_types=1);

class Review extends Model {
    protected string $table = 'reviews';

    public function getForProduct(int $productId): array {
        return $this->fetchAll(
            "SELECT * FROM `reviews` WHERE `product_id` = :id AND `is_approved` = 1 ORDER BY `id` DESC",
            ['id' => $productId]
        );
    }

    public function getAverageRating(int $productId): float {
        $row = $this->fetch(
            "SELECT AVG(rating) as avg_rating, COUNT(id) as count FROM `reviews` WHERE `product_id` = :id AND `is_approved` = 1",
            ['id' => $productId]
        );
        return $row ? round((float)$row['avg_rating'], 1) : 5.0;
    }
}

