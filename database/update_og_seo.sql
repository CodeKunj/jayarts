-- ====================================================================
-- JayArt Database Update: OpenGraph (OG) Image & Favicon SEO Support
-- MariaDB 10.6+ & MySQL 8.0+ Compatible
-- Safe to execute multiple times (idempotent)
-- ====================================================================

-- 1. Ensure `seo_settings` table exists
CREATE TABLE IF NOT EXISTS `seo_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_title` varchar(255) NOT NULL DEFAULT 'JayArt — Buy Original Paintings, Modern Art & Fine Art Prints Online',
  `default_meta_desc` text DEFAULT NULL,
  `default_keywords` varchar(255) DEFAULT NULL,
  `default_og_image` varchar(255) DEFAULT 'assets/images/og-default.jpg',
  `robots_txt` text DEFAULT NULL,
  `sitemap_enabled` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Add `default_og_image` to `seo_settings` if missing
ALTER TABLE `seo_settings` 
ADD COLUMN IF NOT EXISTS `default_og_image` VARCHAR(255) NULL AFTER `default_keywords`;

-- 3. Add `og_image` to `product_seo` for per-artwork social share banners
ALTER TABLE `product_seo` 
ADD COLUMN IF NOT EXISTS `og_image` VARCHAR(255) NULL AFTER `canonical_url`;

-- 4. Add `og_image` to `blog_seo` for per-article social share banners
ALTER TABLE `blog_seo` 
ADD COLUMN IF NOT EXISTS `og_image` VARCHAR(255) NULL AFTER `canonical_url`;

-- 5. Insert or update default Global OG Image in `seo_settings`
INSERT INTO `seo_settings` (`id`, `site_title`, `default_meta_desc`, `default_keywords`, `default_og_image`, `robots_txt`, `sitemap_enabled`) 
VALUES (
  1, 
  'JayArt — Buy Original Paintings, Modern Art & Fine Art Prints Online', 
  'Explore curated original oil paintings, acrylics, modern abstract art, and watercolors from acclaimed and emerging artists. Worldwide insured delivery with Certificate of Authenticity.', 
  'buy paintings online, original art, art gallery, oil paintings, acrylic on canvas, indian art gallery, wall art', 
  'assets/images/og-default.jpg', 
  CONCAT('User-agent: *\nAllow: /\nDisallow: /admin/\nSitemap: https://jayarts.ritiverse.in/sitemap.xml'), 
  1
)
ON DUPLICATE KEY UPDATE 
  `default_og_image` = COALESCE(NULLIF(`default_og_image`, ''), 'assets/images/og-default.jpg');

-- 6. Ensure default_og_image is not blank
UPDATE `seo_settings` 
SET `default_og_image` = 'assets/images/og-default.jpg' 
WHERE `id` = 1 AND (`default_og_image` IS NULL OR `default_og_image` = '');

