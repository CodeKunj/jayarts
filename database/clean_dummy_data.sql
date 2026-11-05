-- ====================================================================
-- JayArt Painting E-Commerce Database Dummy Data Cleanup Script
-- Safely truncates mock/sample products, images, blogs, and demo orders
-- Leaves admin users, categories, currencies, and settings 100% intact
-- ====================================================================

SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE `products`;
TRUNCATE TABLE `product_images`;
TRUNCATE TABLE `product_seo`;
TRUNCATE TABLE `blogs`;
TRUNCATE TABLE `blog_seo`;
TRUNCATE TABLE `orders`;
TRUNCATE TABLE `order_items`;
TRUNCATE TABLE `delivery_tracking`;
TRUNCATE TABLE `delivery_milestones`;
TRUNCATE TABLE `reviews`;
TRUNCATE TABLE `wishlists`;
TRUNCATE TABLE `inventory_logs`;
TRUNCATE TABLE `support_tickets`;
TRUNCATE TABLE `ticket_messages`;
TRUNCATE TABLE `addresses`;
DELETE FROM `users` WHERE `role` != 'admin';

SET FOREIGN_KEY_CHECKS = 1;
