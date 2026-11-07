-- ====================================================================
-- JayArt Fine Art Atelier Database Schema & Production Clean Dump
-- Database: `ritivers_jayarts`
-- Compatible with MySQL 8.0+ / MariaDB 10.4+ (phpMyAdmin Ready)
-- Zero Dummy Data (Production Clean State)
-- ====================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- 1. Users Table
-- --------------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `email` varchar(180) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `role` enum('admin','manager','customer') DEFAULT 'customer',
  `avatar` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Clean Admin Seed (Password: admin123)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `avatar`, `status`, `reset_token`, `reset_token_expires`, `created_at`, `updated_at`) VALUES
(1, 'JayArt Administrator', 'admin@jayart.com', '$2y$12$c3detcn4SJ.fEE4SCMuBK.aod2n7T5oSalkCFw52KMgbnNHS.8Nfm', '+91 98765 43210', 'admin', NULL, 'active', NULL, NULL, NOW(), NOW());

-- --------------------------------------------------------
-- 2. Addresses Table
-- --------------------------------------------------------
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `recipient_name` varchar(120) NOT NULL,
  `phone` varchar(25) NOT NULL,
  `address_line1` varchar(255) NOT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(80) DEFAULT 'India',
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 3. Categories Table
-- --------------------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `status` enum('active','hidden') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Clean Core Categories Seed
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `parent_id`, `sort_order`, `status`, `created_at`) VALUES
(1, 'Oil Paintings', 'oil-paintings', 'Timeless classical and contemporary original oil paintings on Belgian linen and primed canvas.', NULL, NULL, 1, 'active', NOW()),
(2, 'Acrylic on Canvas', 'acrylic-paintings', 'Vibrant, fast-drying textures and contemporary impasto masterpieces by leading artists.', NULL, NULL, 2, 'active', NOW()),
(3, 'Watercolors', 'watercolor-paintings', 'Delicate washes, luminous gradients on museum-grade French cold-pressed cotton paper.', NULL, NULL, 3, 'active', NOW()),
(4, 'Modern Abstract', 'modern-abstract', 'Bold geometric structures, emotional expressions, and minimalist modern art for stylish interiors.', NULL, NULL, 4, 'active', NOW()),
(5, 'Landscapes & Nature', 'landscapes-nature', 'Serene pastoral valleys, misty mountains, golden hour seascapes, and botanical studies.', NULL, NULL, 5, 'active', NOW()),
(6, 'Heritage & Spiritual', 'heritage-spiritual', 'Sacred Indian temple aesthetics, divine depictions, and cultural heritage art.', NULL, NULL, 6, 'active', NOW());

-- --------------------------------------------------------
-- 4. Products Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `artist_name` varchar(120) NOT NULL DEFAULT 'Independent Artist',
  `medium` varchar(80) NOT NULL COMMENT 'Oil on Canvas, Acrylic, Watercolor, Charcoal, etc.',
  `dimensions` varchar(80) NOT NULL COMMENT 'e.g. 24 x 36 inches / 60 x 90 cm',
  `year_created` varchar(10) DEFAULT NULL,
  `frame_options` varchar(255) DEFAULT 'Rolled Canvas,Stretched Canvas,Floating Black Frame,Floating Gold Frame',
  `price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 1,
  `is_original` tinyint(1) DEFAULT 1 COMMENT '1 = Original 1-of-1 painting, 0 = Limited Edition Print',
  `is_featured` tinyint(1) DEFAULT 0,
  `certificate_authenticity` tinyint(1) DEFAULT 1,
  `description` longtext NOT NULL,
  `views_count` int(10) UNSIGNED DEFAULT 0,
  `status` enum('active','draft','sold_out','archived') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `idx_products_category` (`category_id`),
  KEY `idx_products_status` (`status`),
  KEY `idx_products_price` (`price`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 5. Product Images Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 6. Product SEO Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `product_seo`;
CREATE TABLE `product_seo` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `canonical_url` varchar(255) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `schema_type` varchar(50) DEFAULT 'Product',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `product_id` (`product_id`),
  CONSTRAINT `product_seo_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 7. Orders Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_number` varchar(32) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(120) NOT NULL,
  `customer_email` varchar(180) NOT NULL,
  `customer_phone` varchar(25) NOT NULL,
  `shipping_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`shipping_address`)),
  `billing_address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`billing_address`)),
  `subtotal` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) DEFAULT 0.00,
  `shipping_fee` decimal(10,2) DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `coupon_code` varchar(50) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) NOT NULL COMMENT 'razorpay, upi, cod, bank, stripe',
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `payment_reference` varchar(100) DEFAULT NULL COMMENT 'Razorpay payment ID or UPI UTR reference',
  `payment_receipt` varchar(255) DEFAULT NULL COMMENT 'Uploaded screenshot for manual UPI/Bank transfer',
  `order_status` enum('pending','confirmed','framing','shipped','out_for_delivery','delivered','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_orders_status` (`order_status`),
  KEY `idx_orders_payment_status` (`payment_status`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 8. Order Items Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED DEFAULT NULL,
  `product_title` varchar(200) NOT NULL,
  `artist_name` varchar(120) DEFAULT NULL,
  `dimensions` varchar(80) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `frame_chosen` varchar(100) DEFAULT 'Rolled Canvas',
  `total_price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 9. Delivery Tracking Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `delivery_tracking`;
CREATE TABLE `delivery_tracking` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int(10) UNSIGNED NOT NULL,
  `courier_name` varchar(100) NOT NULL DEFAULT 'Fine Art Express Courier',
  `tracking_number` varchar(100) NOT NULL,
  `tracking_url` varchar(255) DEFAULT NULL,
  `current_status` varchar(80) NOT NULL DEFAULT 'In Transit',
  `estimated_delivery` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`),
  CONSTRAINT `delivery_tracking_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 10. Delivery Milestones Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `delivery_milestones`;
CREATE TABLE `delivery_milestones` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `delivery_id` int(10) UNSIGNED NOT NULL,
  `status_title` varchar(120) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `milestone_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `delivery_id` (`delivery_id`),
  CONSTRAINT `delivery_milestones_ibfk_1` FOREIGN KEY (`delivery_id`) REFERENCES `delivery_tracking` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 11. Inventory Logs Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `inventory_logs`;
CREATE TABLE `inventory_logs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `change_type` enum('sale','restock','manual_edit','return','damaged') NOT NULL,
  `quantity_change` int(11) NOT NULL,
  `previous_stock` int(11) NOT NULL,
  `new_stock` int(11) NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `inventory_logs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 12. Blogs Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `blogs`;
CREATE TABLE `blogs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `author_name` varchar(100) DEFAULT 'JayArt Curators',
  `views_count` int(10) UNSIGNED DEFAULT 0,
  `status` enum('published','draft') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 13. Blog SEO Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `blog_seo`;
CREATE TABLE `blog_seo` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `blog_id` int(10) UNSIGNED NOT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `canonical_url` varchar(255) DEFAULT NULL,
  `og_image` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_id` (`blog_id`),
  CONSTRAINT `blog_seo_ibfk_1` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 14. Reviews Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(180) NOT NULL,
  `rating` tinyint(1) NOT NULL DEFAULT 5,
  `comment` text NOT NULL,
  `is_approved` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 15. Wishlists Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE `wishlists` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_product_wishlist` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 16. Coupons Table (Completely Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percentage','fixed') DEFAULT 'percentage',
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT 0.00,
  `max_uses` int(11) DEFAULT 100,
  `uses_count` int(11) DEFAULT 0,
  `expires_at` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 17. Currencies Table (Clean Core Standard)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `currencies`;
CREATE TABLE `currencies` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `name` varchar(60) NOT NULL,
  `symbol` varchar(20) NOT NULL,
  `html_entity` varchar(30) DEFAULT NULL,
  `exchange_rate` decimal(12,6) NOT NULL DEFAULT 1.000000,
  `is_base` tinyint(1) NOT NULL DEFAULT 0,
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `decimal_places` tinyint(4) NOT NULL DEFAULT 2,
  `symbol_position` enum('before','after') NOT NULL DEFAULT 'before',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Standard Multi-Currency Seed
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `html_entity`, `exchange_rate`, `is_base`, `is_enabled`, `decimal_places`, `symbol_position`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'INR', 'Indian Rupee', '₹', '&#8377;', 1.000000, 1, 1, 2, 'before', 1, NOW(), NOW()),
(2, 'USD', 'US Dollar', '$', '&#36;', 0.012000, 0, 1, 2, 'before', 2, NOW(), NOW()),
(3, 'EUR', 'Euro', '€', '&euro;', 0.011000, 0, 1, 2, 'before', 3, NOW(), NOW()),
(4, 'GBP', 'British Pound', '£', '&pound;', 0.009400, 0, 1, 2, 'before', 4, NOW(), NOW()),
(5, 'AED', 'UAE Dirham', 'AED', 'AED', 0.044000, 0, 1, 2, 'before', 5, NOW(), NOW()),
(6, 'CAD', 'Canadian Dollar', 'CA$', 'CA&#36;', 0.016300, 0, 1, 2, 'before', 6, NOW(), NOW()),
(7, 'AUD', 'Australian Dollar', 'AU$', 'AU&#36;', 0.018200, 0, 1, 2, 'before', 7, NOW(), NOW());

-- --------------------------------------------------------
-- 18. Payment Settings Table (Clean Gateway Configuration)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `payment_settings`;
CREATE TABLE `payment_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gateway_code` varchar(50) NOT NULL COMMENT 'razorpay, upi, cod, bank, stripe',
  `display_name` varchar(100) NOT NULL,
  `is_enabled` tinyint(1) DEFAULT 1,
  `mode` enum('test','live') DEFAULT 'test',
  `key_id` varchar(255) DEFAULT NULL,
  `key_secret` varchar(255) DEFAULT NULL,
  `webhook_secret` varchar(255) DEFAULT NULL,
  `upi_vpa` varchar(100) DEFAULT NULL COMMENT 'UPI ID for dynamic QR, e.g. jayart@upi',
  `upi_merchant_name` varchar(100) DEFAULT 'JayArt Gallery',
  `bank_details` text DEFAULT NULL COMMENT 'JSON/Formatted string of Bank Account details',
  `extra_fee` decimal(10,2) DEFAULT 0.00,
  `instructions` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `gateway_code` (`gateway_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Clean Gateway Seeds
INSERT INTO `payment_settings` (`id`, `gateway_code`, `display_name`, `is_enabled`, `mode`, `key_id`, `key_secret`, `webhook_secret`, `upi_vpa`, `upi_merchant_name`, `bank_details`, `extra_fee`, `instructions`, `sort_order`, `updated_at`) VALUES
(1, 'razorpay', 'Razorpay (Cards, NetBanking, UPI, Wallets)', 1, 'test', '', '', NULL, NULL, NULL, NULL, 0.00, 'Pay securely via Credit Card, Debit Card, NetBanking, or digital wallet.', 1, NOW()),
(2, 'upi', 'Direct UPI (Google Pay, PhonePe, Paytm, BHIM)', 1, 'live', NULL, NULL, NULL, 'jayartgallery@upi', 'JayArt Gallery', NULL, 0.00, 'Scan dynamic QR code or tap to pay via UPI application. Submit your 12-digit UTR reference number upon completion.', 2, NOW()),
(3, 'cod', 'Cash on Delivery (COD)', 1, 'live', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 'Pay in cash or UPI QR upon receiving your securely crated artwork.', 3, NOW()),
(4, 'bank', 'Direct Bank Transfer (NEFT / RTGS / IMPS)', 1, 'live', NULL, NULL, NULL, NULL, NULL, 'Bank Name: State Bank of India\nAccount Name: JayArt Private Limited\nA/C No: 398200192837\nIFSC: SBIN0001234\nBranch: Fine Arts Heritage Branch', 0.00, 'Please transfer the total order amount directly to our bank account. Use your Order ID as payment reference.', 4, NOW());

-- --------------------------------------------------------
-- 19. SEO Settings Table
-- --------------------------------------------------------
DROP TABLE IF EXISTS `seo_settings`;
CREATE TABLE `seo_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_title` varchar(255) NOT NULL DEFAULT 'JayArt — Original Paintings & Fine Art Gallery',
  `default_meta_desc` text DEFAULT NULL,
  `default_keywords` varchar(255) DEFAULT NULL,
  `default_og_image` varchar(255) DEFAULT NULL,
  `robots_txt` text DEFAULT NULL,
  `sitemap_enabled` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `seo_settings` (`id`, `site_title`, `default_meta_desc`, `default_keywords`, `default_og_image`, `robots_txt`, `sitemap_enabled`, `updated_at`) VALUES
(1, 'JayArt — Buy Original Paintings, Modern Art & Fine Art Prints Online', 'Explore curated original oil paintings, acrylics, modern abstract art, and watercolors from acclaimed and emerging artists. Worldwide insured delivery with Certificate of Authenticity.', 'buy paintings online, original art, art gallery, oil paintings, acrylic on canvas, indian art gallery, wall art', NULL, 'User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /api/\nSitemap: https://jayarts.ritiverse.in/sitemap.xml', 1, NOW());

-- --------------------------------------------------------
-- 20. Analytics Settings Table (Clean, No Demo IDs)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `analytics_settings`;
CREATE TABLE `analytics_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `google_analytics_id` varchar(50) DEFAULT NULL COMMENT 'G-XXXXXXXXXX',
  `google_search_console_tag` varchar(255) DEFAULT NULL COMMENT 'HTML meta tag content',
  `meta_pixel_id` varchar(50) DEFAULT NULL COMMENT 'Facebook Pixel ID',
  `bing_webmaster_tag` varchar(100) DEFAULT NULL COMMENT 'Bing validation code',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `analytics_settings` (`id`, `google_analytics_id`, `google_search_console_tag`, `meta_pixel_id`, `bing_webmaster_tag`, `updated_at`) VALUES
(1, NULL, NULL, NULL, NULL, NOW());

-- --------------------------------------------------------
-- 21. SMTP Settings Table
-- --------------------------------------------------------
DROP TABLE IF EXISTS `smtp_settings`;
CREATE TABLE `smtp_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `host` varchar(120) NOT NULL DEFAULT 'mail.yourdomain.com',
  `port` int(11) NOT NULL DEFAULT 465,
  `encryption` enum('ssl','tls','none') DEFAULT 'ssl',
  `username` varchar(180) NOT NULL DEFAULT 'support@yourdomain.com',
  `password` varchar(255) NOT NULL DEFAULT '',
  `from_email` varchar(180) NOT NULL DEFAULT 'support@yourdomain.com',
  `from_name` varchar(100) NOT NULL DEFAULT 'JayArt Gallery',
  `is_enabled` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `smtp_settings` (`id`, `host`, `port`, `encryption`, `username`, `password`, `from_email`, `from_name`, `is_enabled`, `updated_at`) VALUES
(1, 'mail.jayarts.ritiverse.in', 465, 'ssl', 'notifications@jayarts.ritiverse.in', '', 'notifications@jayarts.ritiverse.in', 'JayArt Atelier Orders', 0, NOW());

-- --------------------------------------------------------
-- 22. Ad Slots Table (Clean Slots, No Fake Campaign Text)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `ad_slots`;
CREATE TABLE `ad_slots` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slot_key` varchar(50) NOT NULL COMMENT 'header_billboard, in_grid_poster, in_article_blog, sidebar_banner, footer_banner',
  `slot_name` varchar(100) NOT NULL,
  `slot_location` varchar(100) NOT NULL,
  `ad_type` enum('custom','adsense') DEFAULT 'custom',
  `banner_desktop` varchar(255) DEFAULT NULL,
  `banner_mobile` varchar(255) DEFAULT NULL,
  `target_url` varchar(255) DEFAULT NULL,
  `title` varchar(150) DEFAULT NULL,
  `subtitle` varchar(200) DEFAULT NULL,
  `cta_text` varchar(50) DEFAULT 'Explore Now',
  `adsense_code` text DEFAULT NULL,
  `impressions_count` int(10) UNSIGNED DEFAULT 0,
  `clicks_count` int(10) UNSIGNED DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 0,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slot_key` (`slot_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `ad_slots` (`id`, `slot_key`, `slot_name`, `slot_location`, `ad_type`, `banner_desktop`, `banner_mobile`, `target_url`, `title`, `subtitle`, `cta_text`, `adsense_code`, `impressions_count`, `clicks_count`, `is_active`, `updated_at`) VALUES
(1, 'header_billboard', 'Top Exhibition Announcement Strip', 'Header / Below Navigation', 'custom', NULL, NULL, NULL, NULL, NULL, 'Explore Salon', NULL, 0, 0, 0, NOW()),
(2, 'in_grid_poster', 'Art Gallery Exhibition Poster (In-Grid)', 'Painting Grid (Slot 3 or 7)', 'custom', NULL, NULL, NULL, NULL, NULL, 'View Exhibition', NULL, 0, 0, 0, NOW()),
(3, 'in_article_blog', 'Art Insights Sponsor Banner', 'Inside Blog Post Story', 'custom', NULL, NULL, NULL, NULL, NULL, 'Learn More', NULL, 0, 0, 0, NOW()),
(4, 'footer_banner', 'Footer Fine Art Banner', 'Above Footer', 'custom', NULL, NULL, NULL, NULL, NULL, 'Shop Collection', NULL, 0, 0, 0, NOW());

-- --------------------------------------------------------
-- 23. General Store Settings Table (Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(80) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 24. Support Tickets Table (Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `support_tickets`;
CREATE TABLE `support_tickets` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_number` varchar(32) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(120) NOT NULL,
  `customer_email` varchar(180) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `category` enum('order_inquiry','custom_painting','delivery_delay','damaged_artwork','payment_issue','general') DEFAULT 'order_inquiry',
  `priority` enum('low','medium','high','urgent') DEFAULT 'medium',
  `status` enum('open','in_progress','resolved','closed') DEFAULT 'open',
  `order_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `ticket_number` (`ticket_number`),
  KEY `user_id` (`user_id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `support_tickets_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- 25. Ticket Messages Table (Clean)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `ticket_messages`;
CREATE TABLE `ticket_messages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` int(10) UNSIGNED NOT NULL,
  `sender_type` enum('user','admin') NOT NULL,
  `sender_name` varchar(120) NOT NULL,
  `message` text NOT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  CONSTRAINT `ticket_messages_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
