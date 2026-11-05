-- ====================================================================
-- JayArt Painting E-Commerce Production Starter Seed Data
-- Clean configuration: Zero dummy products or fake blogs
-- ====================================================================

-- 1. Default Super Admin User (admin@jayart.com / admin123)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`) VALUES
(1, 'JayArt Administrator', 'admin@jayart.com', '$2y$12$c3detcn4SJ.fEE4SCMuBK.aod2n7T5oSalkCFw52KMgbnNHS.8Nfm', '+91 98765 43210', 'admin', 'active')
ON DUPLICATE KEY UPDATE `email` = `email`;

-- 2. Standard Art Medium & Style Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `sort_order`, `status`) VALUES
(1, 'Oil Paintings', 'oil-paintings', 'Timeless classical and contemporary original oil paintings on Belgian linen and primed canvas.', 1, 'active'),
(2, 'Acrylic on Canvas', 'acrylic-paintings', 'Vibrant, fast-drying textures and contemporary impasto masterpieces by leading artists.', 2, 'active'),
(3, 'Watercolors', 'watercolor-paintings', 'Delicate washes, luminous gradients on museum-grade French cold-pressed cotton paper.', 3, 'active'),
(4, 'Modern Abstract', 'modern-abstract', 'Bold geometric structures, emotional expressions, and minimalist modern art for stylish interiors.', 4, 'active'),
(5, 'Landscapes & Nature', 'landscapes-nature', 'Serene pastoral valleys, misty mountains, golden hour seascapes, and botanical studies.', 5, 'active'),
(6, 'Heritage & Spiritual', 'heritage-spiritual', 'Sacred Indian temple aesthetics, divine depictions, and cultural heritage art.', 6, 'active')
ON DUPLICATE KEY UPDATE `slug` = `slug`;

-- 3. Default Global SEO Settings
INSERT INTO `seo_settings` (`id`, `site_title`, `default_meta_desc`, `default_keywords`, `robots_txt`, `sitemap_enabled`) VALUES
(1, 'JayArt — Buy Original Paintings, Modern Art & Fine Art Prints Online', 'Explore curated original oil paintings, acrylics, modern abstract art, and watercolors from acclaimed and emerging artists. Worldwide insured delivery with Certificate of Authenticity.', 'buy paintings online, original art, art gallery, oil paintings, acrylic on canvas, indian art gallery, wall art', 'User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /api/\nSitemap: https://jayart.com/sitemap.xml', 1)
ON DUPLICATE KEY UPDATE `id` = 1;

-- 4. Analytics & Webmaster Settings Placeholders
INSERT INTO `analytics_settings` (`id`, `google_analytics_id`, `google_search_console_tag`, `meta_pixel_id`, `bing_webmaster_tag`) VALUES
(1, 'G-DEMO123456', '', '', '')
ON DUPLICATE KEY UPDATE `id` = 1;

-- 5. Payment Gateway Settings (Multi-Gateway Ready)
INSERT INTO `payment_settings` (`id`, `gateway_code`, `display_name`, `is_enabled`, `mode`, `key_id`, `key_secret`, `upi_vpa`, `upi_merchant_name`, `bank_details`, `instructions`, `sort_order`) VALUES
(1, 'razorpay', 'Razorpay (Cards, NetBanking, Wallets)', 1, 'test', 'rzp_test_YourKeyIDHere', 'YourKeySecretHere', NULL, NULL, NULL, 'Pay securely via Credit Card, Debit Card, NetBanking, or digital wallet.', 1),
(2, 'upi', 'Direct UPI (Google Pay, PhonePe, Paytm, BHIM)', 1, 'live', NULL, NULL, 'jayartgallery@upi', 'JayArt Gallery', NULL, 'Scan the dynamic QR code or tap to pay via your favorite UPI application. Submit your 12-digit UTR reference number upon completion.', 2),
(3, 'cod', 'Cash on Delivery (COD)', 1, 'live', NULL, NULL, NULL, NULL, NULL, 'Pay in cash or UPI QR upon receiving your securely packaged artwork. Available for select pincodes.', 3),
(4, 'bank', 'Direct Bank Transfer (NEFT / RTGS / IMPS)', 1, 'live', NULL, NULL, NULL, NULL, 'Bank Name: State Bank of India\nAccount Name: JayArt Private Limited\nA/C No: 398200192837\nIFSC: SBIN0001234\nBranch: Fine Arts Heritage Branch', 'Please transfer the total order amount directly to our bank account. Use your Order ID as the payment reference.', 4)
ON DUPLICATE KEY UPDATE `gateway_code` = `gateway_code`;

-- 6. Poster-Style Ad Slots Configuration
INSERT INTO `ad_slots` (`id`, `slot_key`, `slot_name`, `slot_location`, `ad_type`, `title`, `subtitle`, `cta_text`, `target_url`, `is_active`) VALUES
(1, 'header_billboard', 'Top Exhibition Announcement Strip', 'Header / Below Navigation', 'custom', 'Spring Art Salon 2026', 'Curated collection of 40 new original works now unveiled', 'Explore Salon', '/shop?category=oil-paintings', 1),
(2, 'in_grid_poster', 'Art Gallery Exhibition Poster (In-Grid)', 'Painting Grid (Slot 3 or 7)', 'custom', 'Featured Master Solo: The Banaras Series', 'Archival museum-quality watercolor prints on 300gsm cotton rag', 'View Exhibition', '/shop?category=watercolor-paintings', 1),
(3, 'in_article_blog', 'Art Insights Sponsor Banner', 'Inside Blog Post Story', 'custom', 'Artisanal Framing & Canvas Preservation', 'Custom Italian wood mouldings and museum-grade UV conservation glass', 'Book Custom Frame', '/contact', 1),
(4, 'footer_banner', 'Footer Fine Art Banner', 'Above Footer', 'custom', 'Own a Piece of Contemporary Heritage', 'Complimentary insured shipping on all original masterworks across India', 'Shop Collection', '/shop', 1)
ON DUPLICATE KEY UPDATE `slot_key` = `slot_key`;

-- 7. Default SMTP Settings Placeholder
INSERT INTO `smtp_settings` (`id`, `host`, `port`, `encryption`, `username`, `password`, `from_email`, `from_name`, `is_enabled`) VALUES
(1, 'mail.jayartgallery.com', 465, 'ssl', 'notifications@jayartgallery.com', '', 'notifications@jayartgallery.com', 'JayArt Gallery Orders', 0)
ON DUPLICATE KEY UPDATE `id` = 1;

-- 8. Multi-Currency Starter Seed (Base: Indian Rupee)
INSERT INTO `currencies` (`id`, `code`, `name`, `symbol`, `html_entity`, `exchange_rate`, `is_base`, `is_enabled`, `decimal_places`, `symbol_position`, `sort_order`) VALUES
(1, 'INR', 'Indian Rupee', '₹', '&#8377;', 1.000000, 1, 1, 2, 'before', 1),
(2, 'USD', 'US Dollar', '$', '&#36;', 0.012000, 0, 1, 2, 'before', 2),
(3, 'EUR', 'Euro', '€', '&euro;', 0.011000, 0, 1, 2, 'before', 3),
(4, 'GBP', 'British Pound', '£', '&pound;', 0.009400, 0, 1, 2, 'before', 4),
(5, 'AED', 'UAE Dirham', 'AED', 'AED', 0.044000, 0, 1, 2, 'before', 5),
(6, 'CAD', 'Canadian Dollar', 'CA$', 'CA&#36;', 0.016300, 0, 1, 2, 'before', 6),
(7, 'AUD', 'Australian Dollar', 'AU$', 'AU&#36;', 0.018200, 0, 1, 2, 'before', 7)
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
