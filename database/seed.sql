-- ====================================================================
-- JayArt Painting E-Commerce Starter Seed Data
-- ====================================================================

-- 1. Default Super Admin User (admin@jayart.com / admin123)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`) VALUES
(1, 'JayArt Administrator', 'admin@jayart.com', '$2y$12$c3detcn4SJ.fEE4SCMuBK.aod2n7T5oSalkCFw52KMgbnNHS.8Nfm', '+91 98765 43210', 'admin', 'active')
ON DUPLICATE KEY UPDATE `email` = `email`;

-- 2. Art Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `sort_order`, `status`) VALUES
(1, 'Oil Paintings', 'oil-paintings', 'Timeless classical and contemporary original oil paintings on Belgian linen and primed canvas.', 1, 'active'),
(2, 'Acrylic on Canvas', 'acrylic-paintings', 'Vibrant, fast-drying textures and contemporary impasto masterpieces by leading artists.', 2, 'active'),
(3, 'Watercolors', 'watercolor-paintings', 'Delicate washes, luminous gradients on museum-grade French cold-pressed cotton paper.', 3, 'active'),
(4, 'Modern Abstract', 'modern-abstract', 'Bold geometric structures, emotional expressions, and minimalist modern art for stylish interiors.', 4, 'active'),
(5, 'Landscapes & Nature', 'landscapes-nature', 'Serene pastoral valleys, misty mountains, golden hour seascapes, and botanical studies.', 5, 'active'),
(6, 'Heritage & Spiritual', 'heritage-spiritual', 'Sacred Indian temple aesthetics, divine depictions, and cultural heritage art.', 6, 'active')
ON DUPLICATE KEY UPDATE `slug` = `slug`;

-- 3. Sample Artworks (Paintings)
INSERT INTO `products` (`id`, `category_id`, `title`, `slug`, `artist_name`, `medium`, `dimensions`, `year_created`, `frame_options`, `price`, `discount_price`, `stock_quantity`, `is_original`, `is_featured`, `certificate_authenticity`, `description`, `status`) VALUES
(1, 1, 'Echoes of Twilight Valley', 'echoes-of-twilight-valley', 'Rajeshwar Sharma', 'Oil on Belgian Canvas', '36 x 48 inches (91 x 122 cm)', '2025', 'Rolled Canvas,Stretched Canvas,Floating Teak Wood Frame,Floating Matte Black Frame', 34500.00, 29900.00, 1, 1, 1, 1, 'A museum-quality landscape depicting the quiet descent of twilight across misty pine valleys. Painted with premium Old Holland oil pigments with generous texture applied via palette knife. Comes with signed physical Certificate of Authenticity and wooden provenance stamp on the reverse.', 'active'),
(2, 4, 'Symphony in Ochre & Gold', 'symphony-in-ochre-and-gold', 'Aarav Varma', 'Mixed Media & 24K Gold Leaf on Canvas', '30 x 30 inches (76 x 76 cm)', '2026', 'Stretched Canvas,Floating Raw Brass Frame,Floating Charcoal Oak Frame', 22000.00, NULL, 1, 1, 1, 1, 'An energetic abstract work exploring harmony between mineral ochre earths and textured 24K genuine gold leaf. Dynamic layering creates reflective illumination that changes with natural sunlight throughout the day.', 'active'),
(3, 3, 'Morning Ghats of Banaras', 'morning-ghats-of-banaras', 'Sunita Deodhar', 'Artist Watercolor on 300gsm Arches Paper', '22 x 30 inches (56 x 76 cm)', '2025', 'Matted with Italian Glass Frame,Matted Unframed (Archival Sleeve)', 14500.00, 12800.00, 2, 0, 1, 1, 'Luminous atmospheric depiction of dawn over the ancient stone ghats of Varanasi. The golden river mist and traditional wooden boats are rendered in transparent pigments that preserve the pristine brilliance of the archival cotton paper.', 'active'),
(4, 2, 'Monsoon Reverie', 'monsoon-reverie', 'Devendra Patel', 'Heavy Body Acrylic on Canvas', '40 x 50 inches (101 x 127 cm)', '2026', 'Rolled Canvas,Stretched Canvas,Floating Dark Walnut Frame', 42000.00, 38500.00, 1, 1, 1, 1, 'An immersive depiction of the arrival of the Indian monsoon over verdant hillsides. Deep peacock blues, emerald greens, and raw umber tones capture the electric tension of rainclouds breaking over thirsty soil.', 'active'),
(5, 6, 'Eternal Flute of Vrindavan', 'eternal-flute-of-vrindavan', 'Maheshwari Devi', 'Tempera & Natural Pigments on Linen', '28 x 38 inches (71 x 96 cm)', '2025', 'Stretched Canvas,Carved Antique Gold Frame', 28000.00, NULL, 1, 1, 1, 1, 'A spiritual tribute to divine melody, incorporating traditional pigment formulations with modern devotional minimalism. Intricate floral borders painted with single-hair squirrel brushes.', 'active')
ON DUPLICATE KEY UPDATE `slug` = `slug`;

-- Product Images Linking
INSERT INTO `product_images` (`id`, `product_id`, `image_path`, `is_primary`, `sort_order`) VALUES
(1, 1, 'uploads/products/echoes-twilight.jpg', 1, 0),
(2, 2, 'uploads/products/ochre-gold.jpg', 1, 0),
(3, 3, 'uploads/products/banaras-ghats.jpg', 1, 0),
(4, 4, 'uploads/products/monsoon-reverie.jpg', 1, 0),
(5, 5, 'uploads/products/vrindavan-flute.jpg', 1, 0)
ON DUPLICATE KEY UPDATE `product_id` = `product_id`;

-- 4. Product SEO Records
INSERT INTO `product_seo` (`id`, `product_id`, `meta_title`, `meta_description`, `keywords`, `canonical_url`) VALUES
(1, 1, 'Echoes of Twilight Valley — Original Oil Painting by Rajeshwar Sharma | JayArt', 'Buy original landscape oil painting Echoes of Twilight Valley (36x48 inches). Authentic fine art on Belgian canvas with certificate of authenticity. Safe insured delivery.', 'oil painting, landscape painting, original art, rajeshwar sharma, art gallery india', 'https://jayart.com/product/echoes-of-twilight-valley'),
(2, 2, 'Symphony in Ochre & Gold — Modern Abstract Painting | JayArt Gallery', 'Discover Symphony in Ochre & Gold, a luxury mixed media & 24K gold leaf artwork on canvas. Perfect statement centerpiece for modern living spaces.', 'abstract painting, gold leaf art, modern wall art, luxury painting india', 'https://jayart.com/product/symphony-in-ochre-and-gold'),
(3, 3, 'Morning Ghats of Banaras — Fine Watercolor on Arches Paper | JayArt', 'Original fine watercolor painting of Varanasi morning ghats by master artist Sunita Deodhar. Luminous art on 300gsm French Arches cotton paper.', 'watercolor painting, banaras painting, varanasi art, arches paper painting', 'https://jayart.com/product/morning-ghats-of-banaras'),
(4, 4, 'Monsoon Reverie — 40x50 Large Acrylic Painting | JayArt', 'Large scale contemporary acrylic painting Monsoon Reverie. Original Indian monsoon landscape art with heavy body texture and framed options.', 'large painting, acrylic on canvas, contemporary landscape, monsoon art', 'https://jayart.com/product/monsoon-reverie'),
(5, 5, 'Eternal Flute of Vrindavan — Spiritual Heritage Artwork | JayArt', 'Authentic traditional and spiritual painting on linen with hand-ground natural pigments. Certified original artwork delivered safely to your home.', 'spiritual painting, krishna art, traditional indian art, heritage painting', 'https://jayart.com/product/eternal-flute-of-vrindavan')
ON DUPLICATE KEY UPDATE `product_id` = `product_id`;

-- 5. Default Global SEO Settings
INSERT INTO `seo_settings` (`id`, `site_title`, `default_meta_desc`, `default_keywords`, `robots_txt`, `sitemap_enabled`) VALUES
(1, 'JayArt — Buy Original Paintings, Modern Art & Fine Art Prints Online', 'Explore curated original oil paintings, acrylics, modern abstract art, and watercolors from acclaimed and emerging artists. Worldwide insured delivery with Certificate of Authenticity.', 'buy paintings online, original art, art gallery, oil paintings, acrylic on canvas, indian art gallery, wall art', 'User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /api/\nSitemap: https://jayart.com/sitemap.xml', 1)
ON DUPLICATE KEY UPDATE `id` = 1;

-- 6. Analytics & Webmaster Settings Placeholders
INSERT INTO `analytics_settings` (`id`, `google_analytics_id`, `google_search_console_tag`, `meta_pixel_id`, `bing_webmaster_tag`) VALUES
(1, 'G-DEMO123456', '', '', '')
ON DUPLICATE KEY UPDATE `id` = 1;


-- 7. Payment Gateway Settings (Multi-Gateway Ready)
INSERT INTO `payment_settings` (`id`, `gateway_code`, `display_name`, `is_enabled`, `mode`, `key_id`, `key_secret`, `upi_vpa`, `upi_merchant_name`, `bank_details`, `instructions`, `sort_order`) VALUES
(1, 'razorpay', 'Razorpay (Cards, NetBanking, Wallets)', 1, 'test', 'rzp_test_YourKeyIDHere', 'YourKeySecretHere', NULL, NULL, NULL, 'Pay securely via Credit Card, Debit Card, NetBanking, or digital wallet.', 1),
(2, 'upi', 'Direct UPI (Google Pay, PhonePe, Paytm, BHIM)', 1, 'live', NULL, NULL, 'jayartgallery@upi', 'JayArt Gallery', NULL, 'Scan the dynamic QR code or tap to pay via your favorite UPI application. Submit your 12-digit UTR reference number upon completion.', 2),
(3, 'cod', 'Cash on Delivery (COD)', 1, 'live', NULL, NULL, NULL, NULL, NULL, 'Pay in cash or UPI QR upon receiving your securely packaged artwork. Available for select pincodes.', 3),
(4, 'bank', 'Direct Bank Transfer (NEFT / RTGS / IMPS)', 1, 'live', NULL, NULL, NULL, NULL, 'Bank Name: State Bank of India\nAccount Name: JayArt Private Limited\nA/C No: 398200192837\nIFSC: SBIN0001234\nBranch: Fine Arts Heritage Branch', 'Please transfer the total order amount directly to our bank account. Use your Order ID as the payment reference.', 4)
ON DUPLICATE KEY UPDATE `gateway_code` = `gateway_code`;

-- 8. Poster-Style Ad Slots Configuration
INSERT INTO `ad_slots` (`id`, `slot_key`, `slot_name`, `slot_location`, `ad_type`, `title`, `subtitle`, `cta_text`, `target_url`, `is_active`) VALUES
(1, 'header_billboard', 'Top Exhibition Announcement Strip', 'Header / Below Navigation', 'custom', 'Spring Art Salon 2026', 'Curated collection of 40 new original works now unveiled', 'Explore Salon', '/shop?category=oil-paintings', 1),
(2, 'in_grid_poster', 'Art Gallery Exhibition Poster (In-Grid)', 'Painting Grid (Slot 3 or 7)', 'custom', 'Featured Master Solo: The Banaras Series', 'Archival museum-quality watercolor prints on 300gsm cotton rag', 'View Exhibition', '/shop?category=watercolor-paintings', 1),
(3, 'in_article_blog', 'Art Insights Sponsor Banner', 'Inside Blog Post Story', 'custom', 'Artisanal Framing & Canvas Preservation', 'Custom Italian wood mouldings and museum-grade UV conservation glass', 'Book Custom Frame', '/contact', 1),
(4, 'footer_banner', 'Footer Fine Art Banner', 'Above Footer', 'custom', 'Own a Piece of Contemporary Heritage', 'Complimentary insured shipping on all original masterworks across India', 'Shop Collection', '/shop', 1)
ON DUPLICATE KEY UPDATE `slot_key` = `slot_key`;

-- 9. Sample Blog Post
INSERT INTO `blogs` (`id`, `title`, `slug`, `excerpt`, `content`, `author_name`, `status`) VALUES
(1, 'The Lure of the Palette Knife: Impasto Oil Techniques in Modern Indian Art', 'lure-of-palette-knife-impasto-oil', 'Discover how master painters use heavy impasto textures and steel palette knives to give sculptural depth and vitality to contemporary landscapes.', '<p>There is an elemental visceral thrill in experiencing an oil painting executed with a palette knife. Unlike the delicate, feathered gradations of soft sable hair brushes, the steel blade leaves decisive, sculptural ridges of pure pigment on the canvas.</p><h2>The Tactile Landscape</h2><p>In works like <em>Echoes of Twilight Valley</em>, the artist does not merely render the mist—they sculpt the geological weight of the hills. Under warm gallery lighting, each ridge catches ambient light, casting microscopic shadows that shift as you move across the room.</p><p>When purchasing an original artwork, understanding the medium allows collectors to appreciate the physical labor and archival integrity of the piece.</p>', 'Dr. Ananya Mukherjee', 'published')
ON DUPLICATE KEY UPDATE `slug` = `slug`;

INSERT INTO `blog_seo` (`id`, `blog_id`, `meta_title`, `meta_description`, `keywords`) VALUES
(1, 1, 'The Lure of the Palette Knife: Impasto Oil Techniques | JayArt Magazine', 'Learn about the impasto palette knife painting technique used in contemporary landscape art and how it brings dimensional light into art collections.', 'palette knife painting, impasto oil technique, art collecting tips, contemporary art india')
ON DUPLICATE KEY UPDATE `blog_id` = `blog_id`;

-- 10. Default SMTP Settings Placeholder
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

