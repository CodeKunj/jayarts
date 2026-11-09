-- ====================================================================
-- JayArt Fine Art Atelier: Safe Dummy Products Removal & Featured Curation
-- Execute in phpMyAdmin (Database: ritivers_jayarts)
-- ====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Remove SEO metadata of dummy/demo paintings
DELETE FROM `product_seo` 
WHERE `product_id` IN (
    SELECT `id` FROM `products` 
    WHERE `slug` IN ('vrindavan-flute', 'monsoon-reverie', 'morning-ghats', 'echoes-twilight', 'ochre-gold', 'banaras-ghats')
       OR `title` LIKE '%Eternal Flute%' 
       OR `title` LIKE '%Monsoon Reverie%' 
       OR `title` LIKE '%Morning Ghats%'
       OR `title` LIKE '%Echoes of Twilight%'
       OR `title` LIKE '%Ochre%'
);

-- 2. Remove product images associated with dummy paintings or SVG mockups
DELETE FROM `product_images` 
WHERE `image_path` LIKE '%.svg%'
   OR `product_id` IN (
    SELECT `id` FROM `products` 
    WHERE `slug` IN ('vrindavan-flute', 'monsoon-reverie', 'morning-ghats', 'echoes-twilight', 'ochre-gold', 'banaras-ghats')
       OR `title` LIKE '%Eternal Flute%' 
       OR `title` LIKE '%Monsoon Reverie%' 
       OR `title` LIKE '%Morning Ghats%'
       OR `title` LIKE '%Echoes of Twilight%'
       OR `title` LIKE '%Ochre%'
);

-- 3. Remove dummy/demo paintings from products table
DELETE FROM `products` 
WHERE `slug` IN ('vrindavan-flute', 'monsoon-reverie', 'morning-ghats', 'echoes-twilight', 'ochre-gold', 'banaras-ghats')
   OR `title` LIKE '%Eternal Flute%' 
   OR `title` LIKE '%Monsoon Reverie%' 
   OR `title` LIKE '%Morning Ghats%'
   OR `title` LIKE '%Echoes of Twilight%'
   OR `title` LIKE '%Ochre%';

-- 4. Reset featured status on all remaining paintings (so home screen only displays what you pick)
-- (By default sets is_featured = 0 so home screen only shows the 4-6 you choose)
UPDATE `products` SET `is_featured` = 0;

-- 5. Set the newest 4 active paintings as featured on homepage (optional, edit IDs as needed):
UPDATE `products` SET `is_featured` = 1 ORDER BY `id` DESC LIMIT 4;

SET FOREIGN_KEY_CHECKS = 1;

