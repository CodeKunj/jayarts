<?php
/**
 * JayArt High-Performance XML Sitemap & SEO Engine for Google & Bing
 * Supports Standard Sitemap 0.9 + Google Image Sitemap 1.1 Specifications
 */

declare(strict_types=1);

class SitemapController extends Controller {

    /**
     * Dynamic HTTP delivery of XML Sitemap
     */
    public function index(): void {
        header('Content-Type: application/xml; charset=utf-8');
        header('X-Robots-Tag: noindex'); // Do not index the sitemap xml itself as a webpage
        echo self::buildXml();
        exit;
    }

    /**
     * Dynamic HTTP delivery of robots.txt
     */
    public function robots(): void {
        header('Content-Type: text/plain; charset=utf-8');
        echo self::buildRobotsTxt();
        exit;
    }

    /**
     * Build standard robots.txt content with Sitemap reference
     */
    public static function buildRobotsTxt(): string {
        $baseUrl = rtrim(SITE_URL, '/');
        return "User-agent: *\n" .
               "Allow: /\n" .
               "Disallow: /admin/\n" .
               "Disallow: /cart\n" .
               "Disallow: /checkout\n" .
               "Disallow: /profile\n" .
               "\n" .
               "Sitemap: {$baseUrl}/sitemap.xml\n";
    }

    /**
     * Build complete XML Sitemap string with Google Image extensions
     */
    public static function buildXml(): string {
        $baseUrl = rtrim(SITE_URL, '/');
        $today = date('Y-m-d');

        // Fetch paintings
        $productModel = new Product();
        $products = $productModel->all("id DESC");
        if (empty($products)) {
            $products = Product::getDummyProducts();
        }

        // Fetch categories
        $categoryModel = new Category();
        $categories = $categoryModel->all("id ASC");
        if (empty($categories)) {
            $categories = Category::getDummyCategories();
        }

        // Fetch blog stories
        $blogModel = new Blog();
        $blogs = $blogModel->getPublished(100);

        $xml = [];
        $xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
        $xml[] = '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        // 1. Core Primary Storefront Pages
        $staticRoutes = [
            ['loc' => $baseUrl . '/',        'priority' => '1.0', 'freq' => 'daily',   'lastmod' => $today],
            ['loc' => $baseUrl . '/shop',    'priority' => '0.9', 'freq' => 'daily',   'lastmod' => $today],
            ['loc' => $baseUrl . '/offers',  'priority' => '0.8', 'freq' => 'daily',   'lastmod' => $today],
            ['loc' => $baseUrl . '/blog',    'priority' => '0.7', 'freq' => 'weekly',  'lastmod' => $today],
            ['loc' => $baseUrl . '/track',   'priority' => '0.6', 'freq' => 'monthly', 'lastmod' => $today],
            ['loc' => $baseUrl . '/contact', 'priority' => '0.5', 'freq' => 'monthly', 'lastmod' => $today],
        ];

        foreach ($staticRoutes as $route) {
            $xml[] = '  <url>';
            $xml[] = '    <loc>' . htmlspecialchars($route['loc'], ENT_XML1, 'UTF-8') . '</loc>';
            $xml[] = '    <lastmod>' . $route['lastmod'] . '</lastmod>';
            $xml[] = '    <changefreq>' . $route['freq'] . '</changefreq>';
            $xml[] = '    <priority>' . $route['priority'] . '</priority>';
            $xml[] = '  </url>';
        }

        // 2. Category Pages
        foreach ($categories as $cat) {
            $slug = $cat['slug'] ?? '';
            if (empty($slug)) continue;
            $catUrl = $baseUrl . '/shop?category=' . urlencode($slug);

            $xml[] = '  <url>';
            $xml[] = '    <loc>' . htmlspecialchars($catUrl, ENT_XML1, 'UTF-8') . '</loc>';
            $xml[] = '    <lastmod>' . $today . '</lastmod>';
            $xml[] = '    <changefreq>weekly</changefreq>';
            $xml[] = '    <priority>0.8</priority>';
            $xml[] = '  </url>';
        }

        // 3. Product Detail Pages (Original Artworks with Image Sitemap)
        foreach ($products as $p) {
            $status = $p['status'] ?? 'active';
            if ($status !== 'active') continue;

            $slug = $p['slug'] ?? '';
            if (empty($slug)) continue;

            $prodUrl = $baseUrl . '/product/' . urlencode($slug);
            $lastmod = !empty($p['updated_at']) ? date('Y-m-d', strtotime($p['updated_at'])) : $today;
            $imgUrl = !empty($p['primary_image']) ? $p['primary_image'] : '';

            $xml[] = '  <url>';
            $xml[] = '    <loc>' . htmlspecialchars($prodUrl, ENT_XML1, 'UTF-8') . '</loc>';
            $xml[] = '    <lastmod>' . $lastmod . '</lastmod>';
            $xml[] = '    <changefreq>weekly</changefreq>';
            $xml[] = '    <priority>0.9</priority>';

            if (!empty($imgUrl)) {
                $title = htmlspecialchars($p['title'] ?? 'Original Painting', ENT_XML1, 'UTF-8');
                $caption = htmlspecialchars(($p['medium'] ?? 'Original Fine Art') . ' by ' . ($p['artist_name'] ?? 'Master Artist'), ENT_XML1, 'UTF-8');
                $xml[] = '    <image:image>';
                $xml[] = '      <image:loc>' . htmlspecialchars($imgUrl, ENT_XML1, 'UTF-8') . '</image:loc>';
                $xml[] = '      <image:title>' . $title . '</image:title>';
                $xml[] = '      <image:caption>' . $caption . '</image:caption>';
                $xml[] = '    </image:image>';
            }

            $xml[] = '  </url>';
        }

        // 4. Curated Art Stories & Journal Pages
        foreach ($blogs as $b) {
            $status = $b['status'] ?? 'published';
            if ($status !== 'published') continue;

            $slug = $b['slug'] ?? '';
            if (empty($slug)) continue;

            $blogUrl = $baseUrl . '/blog/' . urlencode($slug);
            $lastmod = !empty($b['updated_at']) ? date('Y-m-d', strtotime($b['updated_at'])) : $today;
            $imgUrl = !empty($b['featured_image']) ? $b['featured_image'] : '';

            $xml[] = '  <url>';
            $xml[] = '    <loc>' . htmlspecialchars($blogUrl, ENT_XML1, 'UTF-8') . '</loc>';
            $xml[] = '    <lastmod>' . $lastmod . '</lastmod>';
            $xml[] = '    <changefreq>monthly</changefreq>';
            $xml[] = '    <priority>0.7</priority>';

            if (!empty($imgUrl)) {
                $title = htmlspecialchars($b['title'] ?? 'Art Journal', ENT_XML1, 'UTF-8');
                $caption = htmlspecialchars($b['excerpt'] ?? '', ENT_XML1, 'UTF-8');
                $xml[] = '    <image:image>';
                $xml[] = '      <image:loc>' . htmlspecialchars($imgUrl, ENT_XML1, 'UTF-8') . '</image:loc>';
                $xml[] = '      <image:title>' . $title . '</image:title>';
                if (!empty($caption)) {
                    $xml[] = '      <image:caption>' . $caption . '</image:caption>';
                }
                $xml[] = '    </image:image>';
            }

            $xml[] = '  </url>';
        }

        $xml[] = '</urlset>';
        return implode("\n", $xml);
    }

    /**
     * Write physical sitemap.xml file directly to root directory
     */
    public static function writeStaticFile(?string $targetPath = null): array {
        $path = $targetPath ?? (defined('ROOT_PATH') ? ROOT_PATH . '/sitemap.xml' : dirname(__DIR__, 2) . '/sitemap.xml');
        $content = self::buildXml();
        
        $bytes = @file_put_contents($path, $content);
        if ($bytes === false) {
            throw new RuntimeException("Failed to write physical sitemap file at {$path}. Check filesystem permissions.");
        }

        // Count URLs in generated sitemap
        $urlCount = substr_count($content, '<url>');

        return [
            'success'   => true,
            'path'      => $path,
            'bytes'     => $bytes,
            'url_count' => $urlCount
        ];
    }

    /**
     * Write physical robots.txt file directly to root directory
     */
    public static function writeRobotsTxt(?string $targetPath = null): bool {
        $path = $targetPath ?? (defined('ROOT_PATH') ? ROOT_PATH . '/robots.txt' : dirname(__DIR__, 2) . '/robots.txt');
        $content = self::buildRobotsTxt();
        $bytes = @file_put_contents($path, $content);
        return $bytes !== false;
    }
}
