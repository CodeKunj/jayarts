<?php
/**
 * JayArt Advanced SEO & Webmaster Integrations Engine
 * Handles Meta Tags, OpenGraph, JSON-LD Schemas, GA4, GSC, Meta Pixel, Bing, and Sitemap
 */

declare(strict_types=1);

class SEO {
    private static ?array $cachedGlobal = null;
    private static ?array $cachedAnalytics = null;

    /**
     * Get Global SEO Settings from Database
     */
    public static function getGlobalSettings(): array {
        if (self::$cachedGlobal === null) {
            $db = Database::connect();
            if ($db !== null) {
                try {
                    $stmt = $db->query("SELECT * FROM `seo_settings` WHERE `id` = 1 LIMIT 1");
                    self::$cachedGlobal = $stmt ? ($stmt->fetch() ?: []) : [];
                } catch (Throwable $e) {
                    self::$cachedGlobal = [];
                }
            } else {
                self::$cachedGlobal = [];
            }
        }

        return [
            'site_title'        => self::$cachedGlobal['site_title'] ?? APP_NAME . ' — ' . APP_TAGLINE,
            'default_meta_desc' => self::$cachedGlobal['default_meta_desc'] ?? 'Explore original fine art oil paintings, acrylics, and watercolors.',
            'default_keywords'  => self::$cachedGlobal['default_keywords'] ?? 'original paintings, buy art online, oil painting, acrylic on canvas',
            'default_og_image'  => self::$cachedGlobal['default_og_image'] ?? ASSETS_URL . '/images/og-default.jpg',
            'robots_txt'        => self::$cachedGlobal['robots_txt'] ?? "User-agent: *\nAllow: /",
        ];
    }

    /**
     * Get Webmaster & Analytics Integration Keys from Database
     */
    public static function getAnalyticsSettings(): array {
        if (self::$cachedAnalytics === null) {
            $db = Database::connect();
            if ($db !== null) {
                try {
                    $stmt = $db->query("SELECT * FROM `analytics_settings` WHERE `id` = 1 LIMIT 1");
                    self::$cachedAnalytics = $stmt ? ($stmt->fetch() ?: []) : [];
                } catch (Throwable $e) {
                    self::$cachedAnalytics = [];
                }
            } else {
                self::$cachedAnalytics = [];
            }
        }

        return [
            'ga_id'   => self::$cachedAnalytics['google_analytics_id'] ?? '',
            'gsc_tag' => self::$cachedAnalytics['google_search_console_tag'] ?? '',
            'pixel_id'=> self::$cachedAnalytics['meta_pixel_id'] ?? '',
            'bing_tag'=> self::$cachedAnalytics['bing_webmaster_tag'] ?? '',
        ];
    }

    /**
     * Render Complete HTML Head SEO Meta Tags & Scripts
     */
    public static function renderHead(array $customSeo = []): string {
        $global = self::getGlobalSettings();
        $analytics = self::getAnalyticsSettings();

        $title = !empty($customSeo['title']) ? $customSeo['title'] : $global['site_title'];
        $desc = !empty($customSeo['description']) ? $customSeo['description'] : $global['default_meta_desc'];
        $keywords = !empty($customSeo['keywords']) ? $customSeo['keywords'] : $global['default_keywords'];
        $ogImage = !empty($customSeo['og_image']) ? $customSeo['og_image'] : $global['default_og_image'];
        $canonical = !empty($customSeo['canonical']) ? $customSeo['canonical'] : (SITE_URL . ($_SERVER['REQUEST_URI'] ?? ''));
        $ogType = $customSeo['og_type'] ?? 'website';

        $html = "<!-- Primary SEO Tags -->\n";
        $html .= "<title>" . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "</title>\n";
        $html .= '<meta name="description" content="' . htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') . "\">\n";
        if (!empty($keywords)) {
            $html .= '<meta name="keywords" content="' . htmlspecialchars($keywords, ENT_QUOTES, 'UTF-8') . "\">\n";
        }
        $html .= '<link rel="canonical" href="' . htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') . "\">\n";

        // OpenGraph / Facebook
        $html .= "\n<!-- OpenGraph / Facebook -->\n";
        $html .= '<meta property="og:type" content="' . htmlspecialchars($ogType, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '<meta property="og:title" content="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '<meta property="og:description" content="' . htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '<meta property="og:image" content="' . htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '<meta property="og:url" content="' . htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '<meta property="og:site_name" content="' . htmlspecialchars(APP_NAME, ENT_QUOTES, 'UTF-8') . "\">\n";

        // Twitter Card
        $html .= "\n<!-- Twitter Cards -->\n";
        $html .= "<meta name=\"twitter:card\" content=\"summary_large_image\">\n";
        $html .= '<meta name="twitter:title" content="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '<meta name="twitter:description" content="' . htmlspecialchars($desc, ENT_QUOTES, 'UTF-8') . "\">\n";
        $html .= '<meta name="twitter:image" content="' . htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') . "\">\n";

        // Google Search Console Verification Meta Tag
        if (!empty($analytics['gsc_tag'])) {
            $html .= "\n<!-- Google Search Console -->\n";
            $html .= '<meta name="google-site-verification" content="' . htmlspecialchars($analytics['gsc_tag'], ENT_QUOTES, 'UTF-8') . "\">\n";
        }

        // Bing Webmaster Verification Meta Tag
        if (!empty($analytics['bing_tag'])) {
            $html .= "\n<!-- Bing Webmaster Tools -->\n";
            $html .= '<meta name="msvalidate.01" content="' . htmlspecialchars($analytics['bing_tag'], ENT_QUOTES, 'UTF-8') . "\">\n";
        }

        // Google Analytics 4 (GA4) Tag
        if (!empty($analytics['ga_id'])) {
            $gaId = htmlspecialchars($analytics['ga_id'], ENT_QUOTES, 'UTF-8');
            $html .= "\n<!-- Google Analytics (GA4) -->\n";
            $html .= "<script async src=\"https://www.googletagmanager.com/gtag/js?id={$gaId}\"></script>\n";
            $html .= "<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{$gaId}');
</script>\n";
        }

        // Meta Pixel (Facebook Pixel)
        if (!empty($analytics['pixel_id'])) {
            $pixelId = htmlspecialchars($analytics['pixel_id'], ENT_QUOTES, 'UTF-8');
            $html .= "\n<!-- Meta Pixel Code -->\n";
            $html .= "<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{$pixelId}');
fbq('track', 'PageView');
</script>\n";
        }

        // Render JSON-LD Structured Data Schema
        if (!empty($customSeo['schema'])) {
            $html .= "\n<!-- JSON-LD Structured Data Schema -->\n";
            $html .= '<script type="application/ld+json">' . "\n";
            $html .= json_encode($customSeo['schema'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n";
            $html .= "</script>\n";
        }

        return $html;
    }

    /**
     * Generate Product Schema JSON-LD (Rich Google Search Results)
     */
    public static function buildProductSchema(array $product, ?string $imageUrl = null): array {
        $price = !empty($product['discount_price']) ? $product['discount_price'] : $product['price'];

        return [
            '@context'    => 'https://schema.org/',
            '@type'       => 'Product',
            'name'        => $product['title'],
            'image'       => $imageUrl ? [$imageUrl] : [],
            'description' => strip_tags((string)$product['description']),
            'sku'         => 'ART-' . $product['id'],
            'brand'       => [
                '@type' => 'Brand',
                'name'  => APP_NAME,
            ],
            'creator'     => [
                '@type' => 'Person',
                'name'  => $product['artist_name'] ?? 'Artist',
            ],
            'offers'      => [
                '@type'         => 'Offer',
                'url'           => SITE_URL . '/product/' . $product['slug'],
                'priceCurrency' => CURRENCY_CODE,
                'price'         => (float)$price,
                'availability'  => ((int)$product['stock_quantity'] > 0) ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
            ]
        ];
    }

    /**
     * Generate Blog Article Schema JSON-LD
     */
    public static function buildArticleSchema(array $blog, ?string $imageUrl = null): array {
        return [
            '@context'         => 'https://schema.org',
            '@type'            => 'Article',
            'headline'         => $blog['title'],
            'image'            => $imageUrl ? [$imageUrl] : [],
            'datePublished'    => date('c', strtotime($blog['created_at'] ?? 'now')),
            'dateModified'     => date('c', strtotime($blog['updated_at'] ?? 'now')),
            'author'           => [
                '@type' => 'Person',
                'name'  => $blog['author_name'] ?? 'JayArt Curators',
            ],
            'publisher'        => [
                '@type' => 'Organization',
                'name'  => APP_NAME,
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => ASSETS_URL . '/images/logo.png',
                ]
            ],
            'description'      => $blog['excerpt'] ?? '',
        ];
    }
}

