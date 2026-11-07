-- ====================================================================
-- JayArt Painting E-Commerce Database Dummy Data Cleanup Script
-- Safely cleans mock/sample products, images, blogs, and demo orders
-- Leaves admin users, categories, currencies, and settings 100% intact
-- Compatible with all cPanel MySQL user privileges (DELETE + TRUNCATE)
-- ====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- Wipe Products & Media
DELETE FROM `product_images`;
DELETE FROM `product_seo`;
DELETE FROM `products`;
ALTER TABLE `products` AUTO_INCREMENT = 1;
ALTER TABLE `product_images` AUTO_INCREMENT = 1;
ALTER TABLE `product_seo` AUTO_INCREMENT = 1;

-- Wipe Blogs & SEO
DELETE FROM `blog_seo`;
DELETE FROM `blogs`;
ALTER TABLE `blogs` AUTO_INCREMENT = 1;
ALTER TABLE `blog_seo` AUTO_INCREMENT = 1;

-- Wipe Orders & Deliveries
DELETE FROM `delivery_milestones`;
DELETE FROM `delivery_tracking`;
DELETE FROM `order_items`;
DELETE FROM `orders`;
ALTER TABLE `orders` AUTO_INCREMENT = 1;
ALTER TABLE `order_items` AUTO_INCREMENT = 1;

-- Wipe Social & Support Data
DELETE FROM `reviews`;
DELETE FROM `wishlists`;
DELETE FROM `inventory_logs`;
DELETE FROM `ticket_messages`;
DELETE FROM `support_tickets`;
DELETE FROM `addresses`;

-- Clean non-admin users
DELETE FROM `users` WHERE `role` != 'admin';

SET FOREIGN_KEY_CHECKS = 1;
