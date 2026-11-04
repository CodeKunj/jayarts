-- ====================================================================
-- JayArt Painting E-Commerce Database Schema
-- Compatible with MySQL 8.0+ and MariaDB 10.4+ (cPanel Standard)
-- Charset: utf8mb4, Collation: utf8mb4_unicode_ci
-- ====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(180) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(25) NULL,
  `role` ENUM('admin', 'manager', 'customer') DEFAULT 'customer',
  `avatar` VARCHAR(255) NULL,
  `status` ENUM('active', 'inactive', 'banned') DEFAULT 'active',
  `reset_token` VARCHAR(100) NULL,
  `reset_token_expires` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_users_role` (`role`),
  INDEX `idx_users_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Addresses Table (Multiple shipping addresses per user)
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `recipient_name` VARCHAR(120) NOT NULL,
  `phone` VARCHAR(25) NOT NULL,
  `address_line1` VARCHAR(255) NOT NULL,
  `address_line2` VARCHAR(255) NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `postal_code` VARCHAR(20) NOT NULL,
  `country` VARCHAR(80) DEFAULT 'India',
  `is_default` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Categories Table (Art Mediums & Styles)
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(120) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `image` VARCHAR(255) NULL,
  `parent_id` INT UNSIGNED NULL DEFAULT NULL,
  `sort_order` INT DEFAULT 0,
  `status` ENUM('active', 'hidden') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Products Table (Paintings & Artworks)
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED NULL,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(220) NOT NULL UNIQUE,
  `artist_name` VARCHAR(120) NOT NULL DEFAULT 'Independent Artist',
  `medium` VARCHAR(80) NOT NULL COMMENT 'Oil on Canvas, Acrylic, Watercolor, Charcoal, etc.',
  `dimensions` VARCHAR(80) NOT NULL COMMENT 'e.g. 24 x 36 inches / 60 x 90 cm',
  `year_created` VARCHAR(10) NULL,
  `frame_options` VARCHAR(255) DEFAULT 'Rolled Canvas,Stretched Canvas,Floating Black Frame,Floating Gold Frame',
  `price` DECIMAL(10,2) NOT NULL,
  `discount_price` DECIMAL(10,2) NULL DEFAULT NULL,
  `stock_quantity` INT NOT NULL DEFAULT 1,
  `is_original` TINYINT(1) DEFAULT 1 COMMENT '1 = Original 1-of-1 painting, 0 = Limited Edition Print',
  `is_featured` TINYINT(1) DEFAULT 0,
  `certificate_authenticity` TINYINT(1) DEFAULT 1,
  `description` LONGTEXT NOT NULL,
  `views_count` INT UNSIGNED DEFAULT 0,
  `status` ENUM('active', 'draft', 'sold_out', 'archived') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_products_category` (`category_id`),
  INDEX `idx_products_status` (`status`),
  INDEX `idx_products_price` (`price`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Product Images Table
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT UNSIGNED NOT NULL,
  `image_path` VARCHAR(255) NOT NULL,
  `is_primary` TINYINT(1) DEFAULT 0,
  `sort_order` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Product SEO Table
CREATE TABLE IF NOT EXISTS `product_seo` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT UNSIGNED NOT NULL UNIQUE,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` TEXT NULL,
  `keywords` VARCHAR(255) NULL,
  `canonical_url` VARCHAR(255) NULL,
  `og_image` VARCHAR(255) NULL,
  `schema_type` VARCHAR(50) DEFAULT 'Product',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Orders Table
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(32) NOT NULL UNIQUE,
  `user_id` INT UNSIGNED NULL,
  `customer_name` VARCHAR(120) NOT NULL,
  `customer_email` VARCHAR(180) NOT NULL,
  `customer_phone` VARCHAR(25) NOT NULL,
  `shipping_address` JSON NOT NULL,
  `billing_address` JSON NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `tax_amount` DECIMAL(10,2) DEFAULT 0.00,
  `shipping_fee` DECIMAL(10,2) DEFAULT 0.00,
  `discount_amount` DECIMAL(10,2) DEFAULT 0.00,
  `coupon_code` VARCHAR(50) NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL COMMENT 'razorpay, upi_qr, cod, bank_transfer, stripe',
  `payment_status` ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
  `payment_reference` VARCHAR(100) NULL COMMENT 'Razorpay payment ID or UPI UTR reference',
  `payment_receipt` VARCHAR(255) NULL COMMENT 'Uploaded screenshot for manual UPI/Bank transfer',
  `order_status` ENUM('pending', 'confirmed', 'framing', 'shipped', 'out_for_delivery', 'delivered', 'cancelled') DEFAULT 'pending',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `idx_orders_status` (`order_status`),
  INDEX `idx_orders_payment_status` (`payment_status`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Order Items Table
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED NULL,
  `product_title` VARCHAR(200) NOT NULL,
  `artist_name` VARCHAR(120) NULL,
  `dimensions` VARCHAR(80) NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `frame_chosen` VARCHAR(100) DEFAULT 'Rolled Canvas',
  `total_price` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Delivery Tracking Table
CREATE TABLE IF NOT EXISTS `delivery_tracking` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT UNSIGNED NOT NULL UNIQUE,
  `courier_name` VARCHAR(100) NOT NULL DEFAULT 'Fine Art Express Courier',
  `tracking_number` VARCHAR(100) NOT NULL,
  `tracking_url` VARCHAR(255) NULL,
  `current_status` VARCHAR(80) NOT NULL DEFAULT 'In Transit',
  `estimated_delivery` DATE NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Delivery Milestones Table
CREATE TABLE IF NOT EXISTS `delivery_milestones` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `delivery_id` INT UNSIGNED NOT NULL,
  `status_title` VARCHAR(120) NOT NULL,
  `description` VARCHAR(255) NULL,
  `location` VARCHAR(100) NULL,
  `milestone_time` DATETIME NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`delivery_id`) REFERENCES `delivery_tracking`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Inventory Logs Table (Audit trail of every stock change)
CREATE TABLE IF NOT EXISTS `inventory_logs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT UNSIGNED NOT NULL,
  `change_type` ENUM('sale', 'restock', 'manual_edit', 'return', 'damaged') NOT NULL,
  `quantity_change` INT NOT NULL,
  `previous_stock` INT NOT NULL,
  `new_stock` INT NOT NULL,
  `note` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Blogs Table (Art Stories & Artist Interviews)
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `excerpt` TEXT NULL,
  `content` LONGTEXT NOT NULL,
  `featured_image` VARCHAR(255) NULL,
  `author_name` VARCHAR(100) DEFAULT 'JayArt Curators',
  `views_count` INT UNSIGNED DEFAULT 0,
  `status` ENUM('published', 'draft') DEFAULT 'published',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Blog SEO Table
CREATE TABLE IF NOT EXISTS `blog_seo` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `blog_id` INT UNSIGNED NOT NULL UNIQUE,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` TEXT NULL,
  `keywords` VARCHAR(255) NULL,
  `canonical_url` VARCHAR(255) NULL,
  `og_image` VARCHAR(255) NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`blog_id`) REFERENCES `blogs`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Reviews Table
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NULL,
  `user_name` VARCHAR(100) NOT NULL,
  `user_email` VARCHAR(180) NOT NULL,
  `rating` TINYINT(1) NOT NULL DEFAULT 5,
  `comment` TEXT NOT NULL,
  `is_approved` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Wishlists Table
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `product_id` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_user_product_wishlist` (`user_id`, `product_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Support Tickets Table
CREATE TABLE IF NOT EXISTS `support_tickets` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ticket_number` VARCHAR(32) NOT NULL UNIQUE,
  `user_id` INT UNSIGNED NULL,
  `customer_name` VARCHAR(120) NOT NULL,
  `customer_email` VARCHAR(180) NOT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `category` ENUM('order_inquiry', 'custom_painting', 'delivery_delay', 'damaged_artwork', 'payment_issue', 'general') DEFAULT 'order_inquiry',
  `priority` ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
  `status` ENUM('open', 'in_progress', 'resolved', 'closed') DEFAULT 'open',
  `order_id` INT UNSIGNED NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Ticket Messages Table
CREATE TABLE IF NOT EXISTS `ticket_messages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ticket_id` INT UNSIGNED NOT NULL,
  `sender_type` ENUM('user', 'admin') NOT NULL,
  `sender_name` VARCHAR(120) NOT NULL,
  `message` TEXT NOT NULL,
  `attachment_path` VARCHAR(255) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Global SEO Settings
CREATE TABLE IF NOT EXISTS `seo_settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `site_title` VARCHAR(255) NOT NULL DEFAULT 'JayArt — Original Paintings & Fine Art Gallery',
  `default_meta_desc` TEXT NULL,
  `default_keywords` VARCHAR(255) NULL,
  `default_og_image` VARCHAR(255) NULL,
  `robots_txt` TEXT NULL,
  `sitemap_enabled` TINYINT(1) DEFAULT 1,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. Analytics & Webmaster Integrations
CREATE TABLE IF NOT EXISTS `analytics_settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `google_analytics_id` VARCHAR(50) NULL DEFAULT NULL COMMENT 'G-XXXXXXXXXX',
  `google_search_console_tag` VARCHAR(255) NULL DEFAULT NULL COMMENT 'HTML meta tag content',
  `meta_pixel_id` VARCHAR(50) NULL DEFAULT NULL COMMENT 'Facebook Pixel ID',
  `bing_webmaster_tag` VARCHAR(100) NULL DEFAULT NULL COMMENT 'Bing validation code',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. Payment Gateway Settings
CREATE TABLE IF NOT EXISTS `payment_settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `gateway_code` VARCHAR(50) NOT NULL UNIQUE COMMENT 'razorpay, upi, cod, stripe, bank',
  `display_name` VARCHAR(100) NOT NULL,
  `is_enabled` TINYINT(1) DEFAULT 1,
  `mode` ENUM('test', 'live') DEFAULT 'test',
  `key_id` VARCHAR(255) NULL,
  `key_secret` VARCHAR(255) NULL,
  `webhook_secret` VARCHAR(255) NULL,
  `upi_vpa` VARCHAR(100) NULL COMMENT 'UPI ID for dynamic QR, e.g. jayart@upi',
  `upi_merchant_name` VARCHAR(100) NULL DEFAULT 'JayArt Gallery',
  `bank_details` TEXT NULL COMMENT 'JSON/Formatted string of Bank Account details',
  `extra_fee` DECIMAL(10,2) DEFAULT 0.00,
  `instructions` TEXT NULL,
  `sort_order` INT DEFAULT 0,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. SMTP Mail Settings
CREATE TABLE IF NOT EXISTS `smtp_settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `host` VARCHAR(120) NOT NULL DEFAULT 'mail.yourdomain.com',
  `port` INT NOT NULL DEFAULT 465,
  `encryption` ENUM('ssl', 'tls', 'none') DEFAULT 'ssl',
  `username` VARCHAR(180) NOT NULL DEFAULT 'support@yourdomain.com',
  `password` VARCHAR(255) NOT NULL DEFAULT '',
  `from_email` VARCHAR(180) NOT NULL DEFAULT 'support@yourdomain.com',
  `from_name` VARCHAR(100) NOT NULL DEFAULT 'JayArt Gallery',
  `is_enabled` TINYINT(1) DEFAULT 1,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. Ad Slots Table (Poster-Style & AdSense)
CREATE TABLE IF NOT EXISTS `ad_slots` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `slot_key` VARCHAR(50) NOT NULL UNIQUE COMMENT 'header_billboard, in_grid_poster, in_article_blog, sidebar_banner, footer_banner',
  `slot_name` VARCHAR(100) NOT NULL,
  `slot_location` VARCHAR(100) NOT NULL,
  `ad_type` ENUM('custom', 'adsense') DEFAULT 'custom',
  `banner_desktop` VARCHAR(255) NULL,
  `banner_mobile` VARCHAR(255) NULL,
  `target_url` VARCHAR(255) NULL,
  `title` VARCHAR(150) NULL,
  `subtitle` VARCHAR(200) NULL,
  `cta_text` VARCHAR(50) DEFAULT 'Explore Now',
  `adsense_code` TEXT NULL,
  `impressions_count` INT UNSIGNED DEFAULT 0,
  `clicks_count` INT UNSIGNED DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 23. Coupons Table
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(50) NOT NULL UNIQUE,
  `discount_type` ENUM('percentage', 'fixed') DEFAULT 'percentage',
  `discount_value` DECIMAL(10,2) NOT NULL,
  `min_order_amount` DECIMAL(10,2) DEFAULT 0.00,
  `max_uses` INT DEFAULT 100,
  `uses_count` INT DEFAULT 0,
  `expires_at` DATE NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 24. General Store Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(80) NOT NULL UNIQUE,
  `setting_value` TEXT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

