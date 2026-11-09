-- ====================================================================
-- JayArt Database Update: Configurable Delivery Charges & Frozen Order Snapshots
-- MariaDB 10.6+ & MySQL 8.0+ Compatible
-- Safe to execute multiple times (idempotent)
-- ====================================================================

-- 1. Ensure `shipping_fee` column exists in `orders`
ALTER TABLE `orders` 
ADD COLUMN IF NOT EXISTS `shipping_fee` DECIMAL(10,2) DEFAULT 0.00 AFTER `tax_amount`;

-- 2. Ensure existing orders have a non-null shipping fee
UPDATE `orders` 
SET `shipping_fee` = 0.00 
WHERE `shipping_fee` IS NULL;

-- 3. Ensure settings table exists
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(80) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Initialize delivery charges settings (Default: 0 for Free Delivery)
INSERT INTO `settings` (`setting_key`, `setting_value`) 
VALUES 
  ('standard_shipping_fee', '0'),
  ('free_shipping_threshold', '0'),
  ('delivery_charge', '0')
ON DUPLICATE KEY UPDATE `setting_key` = `setting_key`;

