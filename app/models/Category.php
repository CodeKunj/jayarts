<?php
/**
 * JayArt Category Model (Art Mediums & Styles)
 */

declare(strict_types=1);

class Category extends Model {
    protected string $table = 'categories';

    public static function getDummyCategories(): array {
        return [
            ['id' => 1, 'name' => 'Oil Paintings', 'slug' => 'oil-paintings', 'product_count' => 48, 'image_url' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=160&q=80'],
            ['id' => 2, 'name' => 'Acrylic Art', 'slug' => 'acrylic-art', 'product_count' => 36, 'image_url' => 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=160&q=80'],
            ['id' => 3, 'name' => 'Watercolor Art', 'slug' => 'watercolor-paintings', 'product_count' => 29, 'image_url' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=160&q=80'],
            ['id' => 4, 'name' => 'Modern Abstract', 'slug' => 'modern-abstract', 'product_count' => 42, 'image_url' => 'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?w=160&q=80'],
            ['id' => 5, 'name' => 'Traditional Heritage', 'slug' => 'traditional-heritage', 'product_count' => 21, 'image_url' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?w=160&q=80'],
            ['id' => 6, 'name' => 'Charcoal & Ink', 'slug' => 'charcoal-and-ink', 'product_count' => 15, 'image_url' => 'https://images.unsplash.com/photo-1544717305-2782549b5136?w=160&q=80'],
        ];
    }

    /**
     * Get all active categories with product counts (Cached)
     * Automatically filters out empty categories with 0 products so storefront catalog stays in sync
     */
    public function getActiveWithCounts(bool $onlyWithProducts = true): array {
        $ttl = (int)Cache::getConfig()['category_ttl'];
        $cacheKey = 'categories:active_with_counts:' . ($onlyWithProducts ? 'active_only' : 'all');

        return Cache::remember($cacheKey, $ttl, function() use ($onlyWithProducts) {
            if ($this->db !== null) {
                $havingClause = $onlyWithProducts ? "HAVING product_count > 0" : "";
                $sql = "SELECT c.*, COUNT(p.id) as product_count
                        FROM `categories` c
                        LEFT JOIN `products` p ON c.id = p.category_id AND p.status = 'active'
                        WHERE c.status = 'active'
                        GROUP BY c.id
                        {$havingClause}
                        ORDER BY c.sort_order ASC, c.name ASC";
                $res = $this->fetchAll($sql);
                if (!empty($res)) {
                    if ($onlyWithProducts) {
                        $res = array_filter($res, fn($c) => (int)($c['product_count'] ?? 0) > 0);
                    }
                    return array_values($res);
                }
            }

            $dummy = self::getDummyCategories();
            if ($onlyWithProducts) {
                $dummy = array_filter($dummy, fn($c) => (int)($c['product_count'] ?? 0) > 0);
            }
            return array_values($dummy);
        });
    }

    public function findBySlug(string $slug): ?array {
        $ttl = (int)Cache::getConfig()['category_ttl'];
        return Cache::remember('categories:slug:' . $slug, $ttl, function() use ($slug) {
            if ($this->db !== null) {
                $res = $this->findBy('slug', $slug);
                if ($res) return $res;
            }
            foreach (self::getDummyCategories() as $c) {
                if ($c['slug'] === $slug) return $c;
            }
            return self::getDummyCategories()[0] ?? null;
        });
    }
}
