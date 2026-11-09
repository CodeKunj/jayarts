<?php
/**
 * JayArt Product (Painting) Model
 */

declare(strict_types=1);

class Product extends Model {
    protected string $table = 'products';

    /**
     * Curated Fine Art Catalog Fallback (Used when DB is empty or during offline dev)
     */
    public static function getDummyProducts(): array {
        return [];
    }

    public function find(int|string $id): ?array {
        if ($this->db !== null) {
            try {
                $item = parent::find($id);
                if ($item) return $item;
            } catch (\Throwable $e) {}
        }
        foreach (self::getDummyProducts() as $dummy) {
            if ((int)($dummy['id'] ?? 0) === (int)$id) {
                return $dummy;
            }
        }
        return null;
    }

    public function findBySlug(string $slug): ?array {
        $ttl = (int)Cache::getConfig()['product_ttl'];
        $cacheKey = 'products:detail:slug:' . $slug;

        return Cache::remember($cacheKey, $ttl, function() use ($slug) {
            if ($this->db !== null) {
                $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                               seo.meta_title, seo.meta_description, seo.keywords, seo.canonical_url, seo.og_image
                        FROM `products` p
                        LEFT JOIN `categories` c ON p.category_id = c.id
                        LEFT JOIN `product_seo` seo ON p.id = seo.product_id
                        WHERE p.slug = :slug LIMIT 1";
                
                $product = $this->fetch($sql, ['slug' => $slug]);
                if ($product) {
                    $product['images'] = $this->getImages((int)$product['id']);
                    return $product;
                }
            }

            // Product not found
            return null;
        });
    }

    /**
     * Resolve image URL: converts localhost links to current SITE_URL or fallback
     */
    public static function imageUrl(?string $url): string {
        if (empty($url)) {
            return 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=900&q=80';
        }
        $url = trim($url);
        if (str_contains($url, 'localhost') || str_contains($url, '127.0.0.1')) {
            $path = preg_replace('#^https?://[^/]+#', '', $url);
            return (defined('SITE_URL') ? SITE_URL : '') . '/' . ltrim($path, '/');
        }
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            return (defined('SITE_URL') ? SITE_URL : '') . '/' . ltrim($url, '/');
        }
        return $url;
    }

    /**
     * Format product data (ensures valid absolute image URLs & fallbacks)
     */
    public static function formatProduct(array $p): array {
        $p['primary_image'] = self::imageUrl($p['primary_image'] ?? null);
        return $p;
    }

    /**
     * Get Featured Paintings for Home Page (Cached)
     * Strictly honors is_featured = 1 so non-featured products stay in the catalog
     */
    public function getFeatured(int $limit = 8): array {
        $ttl = (int)Cache::getConfig()['homepage_ttl'];
        $cacheKey = 'products:featured:' . $limit;

        return Cache::remember($cacheKey, $ttl, function() use ($limit) {
            if ($this->db !== null) {
                try {
                    // 1. Strictly fetch active paintings explicitly marked as featured by admin
                    $sql = "SELECT p.*, c.name as category_name,
                                   (SELECT image_path FROM `product_images` WHERE `product_id` = p.id AND `is_primary` = 1 LIMIT 1) as primary_image
                            FROM `products` p
                            LEFT JOIN `categories` c ON p.category_id = c.id
                            WHERE p.status = 'active' AND p.is_featured = 1
                            ORDER BY p.id DESC LIMIT {$limit}";
                    $res = $this->fetchAll($sql);
                    if (!empty($res)) {
                        return array_map([self::class, 'formatProduct'], $res);
                    }

                    // 2. Safe fallback ONLY if admin has not yet marked any painting as featured:
                    // Return a small teaser of up to 4 recent active paintings (never dump the whole catalog!)
                    $sqlRecent = "SELECT p.*, c.name as category_name,
                                         (SELECT image_path FROM `product_images` WHERE `product_id` = p.id AND `is_primary` = 1 LIMIT 1) as primary_image
                                  FROM `products` p
                                  LEFT JOIN `categories` c ON p.category_id = c.id
                                  WHERE p.status = 'active'
                                  ORDER BY p.id DESC LIMIT 4";
                    $resRecent = $this->fetchAll($sqlRecent);
                    if (!empty($resRecent)) {
                        return array_map([self::class, 'formatProduct'], $resRecent);
                    }

                    return [];
                } catch (\Throwable $e) {
                    error_log('getFeatured DB notice: ' . $e->getMessage());
                }
            }

            return [];
        });
    }

    /**
     * Filter Paintings (Supports Mobile Bottom-Sheet & Desktop Sidebar Filters, Cached)
     */
    public function filter(array $filters = [], int $page = 1, int $perPage = 12): array {
        // Build deterministic cache key with sorted non-empty filters
        $cleanFilters = array_filter($filters, fn($val) => $val !== null && $val !== '');
        ksort($cleanFilters);
        $filterHash = md5(http_build_query($cleanFilters) . '&p=' . $page . '&l=' . $perPage);
        $cacheKey = 'products:list:' . $filterHash;

        $ttl = !empty($filters['search']) 
            ? (int)Cache::getConfig()['search_ttl'] 
            : (int)Cache::getConfig()['product_ttl'];

        return Cache::remember($cacheKey, $ttl, function() use ($filters, $page, $perPage) {
            if ($this->db !== null) {
                $conditions = ["p.status = 'active'"];
                $params = [];

                if (!empty($filters['category'])) {
                    $conditions[] = "(c.slug = :category OR c.id = :cat_id)";
                    $params['category'] = $filters['category'];
                    $params['cat_id'] = is_numeric($filters['category']) ? (int)$filters['category'] : 0;
                }

                if (!empty($filters['medium'])) {
                    $conditions[] = "p.medium LIKE :medium";
                    $params['medium'] = '%' . $filters['medium'] . '%';
                }

                if (!empty($filters['search'])) {
                    $conditions[] = "(p.title LIKE :search OR p.artist_name LIKE :search OR p.medium LIKE :search OR p.description LIKE :search)";
                    $params['search'] = '%' . $filters['search'] . '%';
                }

                if (!empty($filters['filter'])) {
                    if ($filters['filter'] === 'gold') {
                        $conditions[] = "(p.title LIKE '%gold%' OR p.medium LIKE '%gold%')";
                    } elseif ($filters['filter'] === 'charcoal') {
                        $conditions[] = "(p.title LIKE '%charcoal%' OR p.medium LIKE '%charcoal%')";
                    } elseif ($filters['filter'] === 'deals') {
                        $conditions[] = "p.discount_price IS NOT NULL AND p.discount_price < p.price";
                    }
                }

                $whereClause = implode(' AND ', $conditions);

                $countSql = "SELECT COUNT(*) as total FROM `products` p LEFT JOIN `categories` c ON p.category_id = c.id WHERE {$whereClause}";
                $totalRow = $this->fetch($countSql, $params);
                $totalItems = (int)($totalRow['total'] ?? 0);

                if ($totalItems > 0) {
                    $offset = max(0, ($page - 1) * $perPage);
                    $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug,
                                   (SELECT image_path FROM `product_images` WHERE `product_id` = p.id ORDER BY `is_primary` DESC, `sort_order` ASC LIMIT 1) as primary_image
                            FROM `products` p
                            LEFT JOIN `categories` c ON p.category_id = c.id
                            WHERE {$whereClause}
                            ORDER BY p.id DESC
                            LIMIT {$perPage} OFFSET {$offset}";
                    $items = $this->fetchAll($sql, $params);

                    return [
                        'items'        => $items,
                        'total'        => $totalItems,
                        'current_page' => $page,
                        'per_page'     => $perPage,
                        'total_pages'  => (int)ceil($totalItems / $perPage),
                    ];
                }

                return [
                    'items'        => [],
                    'total'        => 0,
                    'current_page' => $page,
                    'per_page'     => $perPage,
                    'total_pages'  => 0,
                ];
            }

            return [
                'items'        => [],
                'total'        => 0,
                'current_page' => 1,
                'per_page'     => $perPage,
                'total_pages'  => 0,
            ];
        });
    }

    /**
     * Get Painting Images
     */
    public function getImages(int $productId): array {
        if ($this->db !== null) {
            return $this->fetchAll(
                "SELECT * FROM `product_images` WHERE `product_id` = :id ORDER BY `is_primary` DESC, `sort_order` ASC",
                ['id' => $productId]
            );
        }
        return [];
    }

    /**
     * Get Related Paintings (Cached)
     */
    public function getRelated(int $productId, ?int $categoryId, int $limit = 4): array {
        $ttl = (int)Cache::getConfig()['product_ttl'];
        $cacheKey = 'products:related:' . $productId . ':' . (int)$categoryId . ':' . $limit;

        return Cache::remember($cacheKey, $ttl, function() use ($productId, $categoryId, $limit) {
            if ($this->db !== null && $categoryId !== null) {
                $sql = "SELECT p.*, c.name as category_name,
                               (SELECT image_path FROM `product_images` WHERE `product_id` = p.id ORDER BY `is_primary` DESC LIMIT 1) as primary_image
                        FROM `products` p
                        LEFT JOIN `categories` c ON p.category_id = c.id
                        WHERE p.id != :id AND p.category_id = :cat_id AND p.status = 'active'
                        ORDER BY p.id DESC LIMIT {$limit}";
                $res = $this->fetchAll($sql, ['id' => $productId, 'cat_id' => $categoryId]);
                if (!empty($res)) {
                    return array_map([self::class, 'formatProduct'], $res);
                }
            }

            return [];
        });
    }

    /**
     * Authoritative Real-Time Stock Query directly from MySQL (Never Cached)
     * Used exclusively for checkout, payment verification, and order processing.
     */
    public function getAuthoritativeStock(int $productId): int {
        if ($this->db !== null) {
            $row = $this->fetch("SELECT `stock_quantity` FROM `products` WHERE `id` = :id", ['id' => $productId]);
            return (int)($row['stock_quantity'] ?? 0);
        }
        return 0;
    }

    public function incrementViews(int $productId): void {
        if ($this->db !== null) {
            $this->query("UPDATE `products` SET `views_count` = `views_count` + 1 WHERE `id` = :id", ['id' => $productId]);
        }
    }
}
