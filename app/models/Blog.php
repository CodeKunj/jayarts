<?php
/**
 * JayArt Blog & Stories Model
 */

declare(strict_types=1);

class Blog extends Model {
    protected string $table = 'blogs';

    /**
     * Clean Production: Zero dummy blogs
     */
    public static function getDummyBlogs(): array {
        return [];
    }

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

