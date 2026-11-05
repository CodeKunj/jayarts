<?php
/**
 * JayArt Category Model (Art Mediums & Styles)
 */

declare(strict_types=1);

class Category extends Model {
    protected string $table = 'categories';

    public static function getDummyCategories(): array {
        return [];
    }

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
