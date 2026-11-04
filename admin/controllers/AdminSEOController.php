<?php
/**
 * JayArt Admin SEO & Webmaster Integrations Controller
 */

declare(strict_types=1);

class AdminSEOController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $db = Database::connect();
        
        $seoRow = $db ? $db->query("SELECT * FROM `seo_settings` WHERE `id` = 1 LIMIT 1")->fetch() : [];
        $analyticsRow = $db ? $db->query("SELECT * FROM `analytics_settings` WHERE `id` = 1 LIMIT 1")->fetch() : [];

        $this->renderAdmin('seo/index', [
            'seo'       => $seoRow ?: [],
            'analytics' => $analyticsRow ?: [],
            'title'     => 'SEO Panel & Webmaster Integrations — JayArt Admin'
        ]);
    }

    public function updateGlobal(): void {
        $siteTitle = trim((string)($_POST['site_title'] ?? ''));
        $metaDesc  = trim((string)($_POST['default_meta_desc'] ?? ''));
        $keywords  = trim((string)($_POST['default_keywords'] ?? ''));
        $robots    = trim((string)($_POST['robots_txt'] ?? "User-agent: *\nAllow: /"));

        $db = Database::connect();
        if ($db) {
            $stmt = $db->prepare("UPDATE `seo_settings` SET `site_title` = :t, `default_meta_desc` = :d, `default_keywords` = :k, `robots_txt` = :r WHERE `id` = 1");
            $stmt->execute(['t' => $siteTitle, 'd' => $metaDesc, 'k' => $keywords, 'r' => $robots]);
        }

        Auth::setFlash('success', 'Global SEO parameters and robots.txt saved.');
        header('Location: ' . ADMIN_URL . '/seo');
        exit;
    }

    public function updateAnalytics(): void {
        $gaId    = trim((string)($_POST['google_analytics_id'] ?? ''));
        $gscTag  = trim((string)($_POST['google_search_console_tag'] ?? ''));
        $pixelId = trim((string)($_POST['meta_pixel_id'] ?? ''));
        $bingTag = trim((string)($_POST['bing_webmaster_tag'] ?? ''));

        $db = Database::connect();
        if ($db) {
            $stmt = $db->prepare("UPDATE `analytics_settings` SET `google_analytics_id` = :ga, `google_search_console_tag` = :gsc, `meta_pixel_id` = :px, `bing_webmaster_tag` = :bing WHERE `id` = 1");
            $stmt->execute(['ga' => $gaId, 'gsc' => $gscTag, 'px' => $pixelId, 'bing' => $bingTag]);
        }

        Auth::setFlash('success', 'Webmaster integration keys (GA4, GSC, Meta Pixel, Bing) updated successfully.');
        header('Location: ' . ADMIN_URL . '/seo');
        exit;
    }

    public function generateSitemap(): void {
        try {
            $result = SitemapController::writeStaticFile();
            SitemapController::writeRobotsTxt();
            Auth::setFlash('success', "XML Sitemap generated successfully! {$result['url_count']} URLs written to physical /sitemap.xml file. Dynamic endpoint also live at " . SITE_URL . "/sitemap.xml");
        } catch (Throwable $e) {
            Auth::setFlash('error', "Could not generate physical sitemap file: " . $e->getMessage());
        }
        header('Location: ' . ADMIN_URL . '/seo');
        exit;
    }
}

