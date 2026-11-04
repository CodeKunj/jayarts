<?php
/**
 * JayArt Wishlist Model
 */

declare(strict_types=1);

class Wishlist extends Model {
    protected string $table = 'wishlists';

    public function getUserWishlist(int $userId): array {
        $sql = "SELECT w.id as wishlist_id, p.*, c.name as category_name,
                       (SELECT image_path FROM `product_images` WHERE `product_id` = p.id ORDER BY `is_primary` DESC LIMIT 1) as primary_image
                FROM `wishlists` w
                JOIN `products` p ON w.product_id = p.id
                LEFT JOIN `categories` c ON p.category_id = c.id
                WHERE w.user_id = :uid
                ORDER BY w.id DESC";
        return $this->fetchAll($sql, ['uid' => $userId]);
    }

    public function toggle(int $userId, int $productId): array {
        $exists = $this->fetch(
            "SELECT id FROM `wishlists` WHERE `user_id` = :uid AND `product_id` = :pid LIMIT 1",
            ['uid' => $userId, 'pid' => $productId]
        );

        if ($exists) {
            $this->query("DELETE FROM `wishlists` WHERE `id` = :id", ['id' => $exists['id']]);
            return ['saved' => false, 'message' => 'Artwork removed from your wishlist.'];
        } else {
            $this->insert([
                'user_id'    => $userId,
                'product_id' => $productId
            ]);
            return ['saved' => true, 'message' => 'Artwork saved to your wishlist!'];
        }
    }

    public function isWishlisted(int $userId, int $productId): bool {
        $row = $this->fetch(
            "SELECT id FROM `wishlists` WHERE `user_id` = :uid AND `product_id` = :pid LIMIT 1",
            ['uid' => $userId, 'pid' => $productId]
        );
        return $row !== null;
    }
}

