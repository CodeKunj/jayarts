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
        return [
            [
                'id' => 1,
                'title' => 'Eternal Flute of Vrindavan',
                'slug' => 'eternal-flute-of-vrindavan',
                'artist_name' => 'Maheshwari Devi',
                'category_name' => 'Heritage & Spiritual',
                'category_slug' => 'traditional-heritage',
                'medium' => 'Tempera & 24K Gold Leaf on Linen',
                'dimensions' => '36 × 48 in',
                'price' => 35000.00,
                'discount_price' => 28000.00,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=900&q=80',
                'description' => 'A transcendent devotional portrayal rendered in natural stone pigments and pure 24K gold foil accents on archival primed Belgian linen.'
            ],
            [
                'id' => 2,
                'title' => 'Morning Ghats of Banaras',
                'slug' => 'morning-ghats-of-banaras',
                'artist_name' => 'Sunita Deodhar',
                'category_name' => 'Watercolors',
                'category_slug' => 'watercolor-paintings',
                'medium' => 'Artist Watercolor on 300gsm Arches',
                'dimensions' => '24 × 36 in',
                'price' => 12800.00,
                'discount_price' => null,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=900&q=80',
                'description' => 'A misty sunrise washing over the historical stone steps of Dashashwamedh Ghat, captured with fluid wet-on-wet pigments.'
            ],
            [
                'id' => 3,
                'title' => 'Kashi Astral Odyssey',
                'slug' => 'kashi-astral-odyssey',
                'artist_name' => 'Prof. Jayant Varma',
                'category_name' => 'Oil on Linen',
                'category_slug' => 'oil-paintings',
                'medium' => 'Heavy Impasto Palette Knife Oil',
                'dimensions' => '40 × 52 in',
                'price' => 52000.00,
                'discount_price' => 45000.00,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1578926375605-eaf7559b1458?w=900&q=80',
                'description' => 'Rich textural impasto knife strokes evoke celestial constellations dancing over the timeless temple towers of Varanasi.'
            ],
            [
                'id' => 4,
                'title' => 'Gilded Tantric Geometry',
                'slug' => 'gilded-tantric-geometry',
                'artist_name' => 'Ananya Sen',
                'category_name' => 'Modern Abstract',
                'category_slug' => 'modern-abstract',
                'medium' => '24K Gold Leaf & Mineral Pigments',
                'dimensions' => '30 × 30 in',
                'price' => 42000.00,
                'discount_price' => 38500.00,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=900&q=80',
                'description' => 'Concentric mandalas in genuine beaten gold leaf, exploring the equilibrium of cosmic sound and silent meditation.'
            ],
            [
                'id' => 5,
                'title' => 'Serenade of the Ancient Banyan',
                'slug' => 'serenade-of-the-ancient-banyan',
                'artist_name' => 'Rameshwar Broota',
                'category_name' => 'Charcoal & Ink',
                'category_slug' => 'charcoal-mono',
                'medium' => 'Willow Charcoal & Ink Wash on Silk',
                'dimensions' => '28 × 42 in',
                'price' => 18000.00,
                'discount_price' => null,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1577083552431-6e5fd01aa342?w=900&q=80',
                'description' => 'Dramatic light and shade highlighting the aerial roots of an ancestral banyan tree, rendered with tonal charcoal gradation.'
            ],
            [
                'id' => 6,
                'title' => 'Rhapsody in Indigo',
                'slug' => 'rhapsody-in-indigo',
                'artist_name' => 'Kabir Sharma',
                'category_name' => 'Modern Abstract',
                'category_slug' => 'modern-abstract',
                'medium' => 'Contemporary Acrylic & Resin on Canvas',
                'dimensions' => '48 × 60 in',
                'price' => 60000.00,
                'discount_price' => 54000.00,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1549887534-1541e9326642?w=900&q=80',
                'description' => 'Expansive fields of deep Prussian blue and natural Indian indigo colliding with raw sienna brush energy.'
            ],
            [
                'id' => 7,
                'title' => 'Monsoon Melody at Assi Ghat',
                'slug' => 'monsoon-melody-at-assi-ghat',
                'artist_name' => 'Deepika Rao',
                'category_name' => 'Oil on Linen',
                'category_slug' => 'oil-paintings',
                'medium' => 'Plein-Air Oil on Italian Linen',
                'dimensions' => '22 × 30 in',
                'price' => 19500.00,
                'discount_price' => 16500.00,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?w=900&q=80',
                'description' => 'Painted outdoors as the first rain clouds broke over the riverbanks, infusing reflections with energetic emerald tones.'
            ],
            [
                'id' => 8,
                'title' => 'Cosmic Tandava in Crimson',
                'slug' => 'cosmic-tandava-in-crimson',
                'artist_name' => 'Suresh Kumar',
                'category_name' => 'Heritage & Spiritual',
                'category_slug' => 'traditional-heritage',
                'medium' => 'Natural Ochre & Lac on Canvas',
                'dimensions' => '36 × 48 in',
                'price' => 38000.00,
                'discount_price' => 32000.00,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1579783928621-7a13d66a62d1?w=900&q=80',
                'description' => 'Dynamic rhythmic choreography captured in fierce cinnabar and soot black lines of ancient Nataraja iconology.'
            ],
            [
                'id' => 9,
                'title' => 'Lotus Bloom of Sarnath',
                'slug' => 'lotus-bloom-of-sarnath',
                'artist_name' => 'Vandana Mehta',
                'category_name' => 'Watercolors',
                'category_slug' => 'watercolor-paintings',
                'medium' => 'Gouache & Gold Powder on Wasli Paper',
                'dimensions' => '20 × 26 in',
                'price' => 14200.00,
                'discount_price' => null,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1582561424760-0321d75e81fa?w=900&q=80',
                'description' => 'Delicate miniature technique depicting serene white water lilies awakening in sacred meditation ponds.'
            ],
            [
                'id' => 10,
                'title' => 'Whispers of the Thar',
                'slug' => 'whispers-of-the-thar',
                'artist_name' => 'Vikramaditya Rathore',
                'category_name' => 'Modern Abstract',
                'category_slug' => 'modern-abstract',
                'medium' => 'Sandstone Powder & Acrylic Medium',
                'dimensions' => '32 × 44 in',
                'price' => 24000.00,
                'discount_price' => null,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1576769267415-964200045b30?w=900&q=80',
                'description' => 'Real desert sand blended into amber pigments producing tactile dune ripples on reinforced canvas.'
            ],
            [
                'id' => 11,
                'title' => 'Nocturne over Mandovi Waters',
                'slug' => 'nocturne-over-mandovi-waters',
                'artist_name' => 'Clara D\'Souza',
                'category_name' => 'Oil on Linen',
                'category_slug' => 'oil-paintings',
                'medium' => 'Heavy Palette Knife Oil on Linen',
                'dimensions' => '30 × 40 in',
                'price' => 29500.00,
                'discount_price' => null,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1579783901586-d88db74b4fe4?w=900&q=80',
                'description' => 'Deep cobalt night reflections shimmering under coastal starlight, laid down with vigorous steel knife strokes.'
            ],
            [
                'id' => 12,
                'title' => 'Shiva\'s Silence in Kailash',
                'slug' => 'shivas-silence-in-kailash',
                'artist_name' => 'Harishankar Joshi',
                'category_name' => 'Heritage & Spiritual',
                'category_slug' => 'traditional-heritage',
                'medium' => 'Pure Mineral Pigments on Raw Khadi',
                'dimensions' => '42 × 56 in',
                'price' => 70000.00,
                'discount_price' => 62000.00,
                'stock_quantity' => 1,
                'is_featured' => 1,
                'primary_image' => 'https://images.unsplash.com/photo-1580136579312-94651dfd596d?w=900&q=80',
                'description' => 'A monumental meditative contemplation of Mount Kailash rendered in crushed lapis lazuli and Himalayan white clay.'
            ]
        ];
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

            // Fallback to Dummy Catalog
            foreach (self::getDummyProducts() as $item) {
                if ($item['slug'] === $slug) {
                    $item['images'] = [];
                    return $item;
                }
            }

            return self::getDummyProducts()[0] ?? null;
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
     */
    public function getFeatured(int $limit = 24): array {
        $ttl = (int)Cache::getConfig()['homepage_ttl'];
        $cacheKey = 'products:featured:' . $limit;

        return Cache::remember($cacheKey, $ttl, function() use ($limit) {
            if ($this->db !== null) {
                try {
                    // 1. Try fetching explicit featured active paintings
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

                    // 2. Fallback to any active paintings
                    $sqlActive = "SELECT p.*, c.name as category_name,
                                         (SELECT image_path FROM `product_images` WHERE `product_id` = p.id AND `is_primary` = 1 LIMIT 1) as primary_image
                                  FROM `products` p
                                  LEFT JOIN `categories` c ON p.category_id = c.id
                                  WHERE p.status = 'active'
                                  ORDER BY p.id DESC LIMIT {$limit}";
                    $resActive = $this->fetchAll($sqlActive);
                    if (!empty($resActive)) {
                        return array_map([self::class, 'formatProduct'], $resActive);
                    }

                    // 3. Fallback to any paintings in catalog
                    $sqlAll = "SELECT p.*, c.name as category_name,
                                      (SELECT image_path FROM `product_images` WHERE `product_id` = p.id AND `is_primary` = 1 LIMIT 1) as primary_image
                               FROM `products` p
                               LEFT JOIN `categories` c ON p.category_id = c.id
                               ORDER BY p.id DESC LIMIT {$limit}";
                    $resAll = $this->fetchAll($sqlAll);
                    if (!empty($resAll)) {
                        return array_map([self::class, 'formatProduct'], $resAll);
                    }
                } catch (Throwable $e) {
                    error_log('getFeatured DB notice: ' . $e->getMessage());
                }
            }

            // Return curated dummy list
            return array_slice(self::getDummyProducts(), 0, $limit);
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
            }

            // Return Filtered Dummy Products List (Dev Fallback Mode)
            $dummy = self::getDummyProducts();
            $filtered = array_filter($dummy, function($item) use ($filters) {
                if (!empty($filters['category'])) {
                    $cat = $filters['category'];
                    if ($cat === 'oil-paintings' && !str_contains(strtolower($item['medium']), 'oil')) return false;
                    if ($cat === 'acrylic-art' && !str_contains(strtolower($item['medium']), 'acrylic')) return false;
                    if ($cat === 'watercolor-paintings' && !str_contains(strtolower($item['medium']), 'watercolor')) return false;
                    if ($cat === 'modern-abstract' && $item['category_slug'] !== 'modern-abstract') return false;
                    if ($cat === 'traditional-heritage' && $item['category_slug'] !== 'traditional-heritage') return false;
                }
                if (!empty($filters['medium'])) {
                    if (!str_contains(strtolower($item['medium']), strtolower($filters['medium']))) return false;
                }
                if (!empty($filters['filter'])) {
                    if ($filters['filter'] === 'gold' && !str_contains(strtolower($item['title'] . ' ' . $item['medium']), 'gold')) return false;
                    if ($filters['filter'] === 'charcoal' && !str_contains(strtolower($item['title'] . ' ' . $item['medium']), 'charcoal')) return false;
                    if ($filters['filter'] === 'deals' && empty($item['discount_price'])) return false;
                }
                if (!empty($filters['search'])) {
                    $q = strtolower($filters['search']);
                    $searchable = strtolower($item['title'] . ' ' . $item['artist_name'] . ' ' . $item['medium'] . ' ' . $item['description']);
                    if (!str_contains($searchable, $q)) return false;
                }
                return true;
            });

            $filtered = array_values($filtered);
            return [
                'items'        => array_map([self::class, 'formatProduct'], $filtered),
                'total'        => count($filtered),
                'current_page' => 1,
                'per_page'     => $perPage,
                'total_pages'  => 1,
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

            $dummy = self::getDummyProducts();
            $related = [];
            foreach ($dummy as $p) {
                if ($p['id'] !== $productId) {
                    $related[] = $p;
                }
                if (count($related) >= $limit) break;
            }
            return $related;
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
        // Dev fallback
        foreach (self::getDummyProducts() as $p) {
            if ($p['id'] === $productId) {
                return (int)$p['stock_quantity'];
            }
        }
        return 0;
    }

    public function incrementViews(int $productId): void {
        if ($this->db !== null) {
            $this->query("UPDATE `products` SET `views_count` = `views_count` + 1 WHERE `id` = :id", ['id' => $productId]);
        }
    }
}
