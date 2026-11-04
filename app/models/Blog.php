<?php
/**
 * JayArt Blog & Stories Model
 */

declare(strict_types=1);

class Blog extends Model {
    protected string $table = 'blogs';

    /**
     * Fallback Curated Articles (Used if database is offline or empty)
     */
    public static function getDummyBlogs(): array {
        return [
            [
                'id' => 1,
                'title' => 'The Lure of the Palette Knife: Impasto Oil Techniques in Modern Indian Art',
                'slug' => 'lure-of-palette-knife-impasto-oil',
                'excerpt' => 'Discover how master painters use heavy impasto textures and steel palette knives to give sculptural depth and vitality to contemporary landscapes.',
                'featured_image' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=900&q=80',
                'author_name' => 'Dr. Ananya Mukherjee',
                'status' => 'published',
                'created_at' => '2026-03-15 10:00:00',
                'updated_at' => '2026-03-20 14:30:00'
            ],
            [
                'id' => 2,
                'title' => 'Caring for Original Canvas Paintings in Tropical Climates',
                'slug' => 'caring-for-canvas-paintings-tropical-climates',
                'excerpt' => 'Archival conservation guidance: humidity management, UV-protective museum glass, and framing techniques for lasting provenance.',
                'featured_image' => 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=900&q=80',
                'author_name' => 'JayArt Atelier Conservators',
                'status' => 'published',
                'created_at' => '2026-03-10 11:00:00',
                'updated_at' => '2026-03-18 16:00:00'
            ],
            [
                'id' => 3,
                'title' => 'The Spiritual Architecture of Banaras Ghats in Watercolors',
                'slug' => 'spiritual-architecture-banaras-ghats-watercolors',
                'excerpt' => 'Analyzing how light, river reflections, and ancient stone steps are captured through transparent French watercolor washes.',
                'featured_image' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=900&q=80',
                'author_name' => 'Prof. Vikramaditya Sen',
                'status' => 'published',
                'created_at' => '2026-03-05 09:30:00',
                'updated_at' => '2026-03-12 12:00:00'
            ]
        ];
    }

    /**
     * Get Published Blogs with SEO
     */
    public function getPublished(int $limit = 10, int $offset = 0): array {
        $sql = "SELECT b.*, seo.meta_title, seo.meta_description, seo.keywords, seo.og_image
                FROM `blogs` b
                LEFT JOIN `blog_seo` seo ON b.id = seo.blog_id
                WHERE b.status = 'published'
                ORDER BY b.id DESC LIMIT {$limit} OFFSET {$offset}";
        $rows = $this->fetchAll($sql);
        if (empty($rows)) {
            return array_slice(self::getDummyBlogs(), $offset, $limit);
        }
        return $rows;
    }

    /**
     * Find Blog by Slug
     */
    public function findBySlug(string $slug): ?array {
        $sql = "SELECT b.*, seo.meta_title, seo.meta_description, seo.keywords, seo.og_image
                FROM `blogs` b
                LEFT JOIN `blog_seo` seo ON b.id = seo.blog_id
                WHERE b.slug = :slug LIMIT 1";
        $row = $this->fetch($sql, ['slug' => $slug]);
        if (!$row) {
            foreach (self::getDummyBlogs() as $dummy) {
                if ($dummy['slug'] === $slug) {
                    return $dummy;
                }
            }
        }
        return $row;
    }

    /**
     * Increment Blog View Count
     */
    public function incrementViews(int $blogId): void {
        $this->query("UPDATE `blogs` SET `views_count` = `views_count` + 1 WHERE `id` = :id", ['id' => $blogId]);
    }
}

