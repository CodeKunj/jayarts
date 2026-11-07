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
        $ogImage   = trim((string)($_POST['default_og_image'] ?? ''));
        $robots    = trim((string)($_POST['robots_txt'] ?? "User-agent: *\nAllow: /"));

        // Handle OG Image File Upload
        if (!empty($_FILES['og_image_file']['tmp_name']) && is_uploaded_file($_FILES['og_image_file']['tmp_name'])) {
            $file = $_FILES['og_image_file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
            if (in_array($ext, $allowedExts, true)) {
                $targetDir = ROOT_PATH . '/uploads/seo';
                if (!is_dir($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }
                $filename = 'og-share-' . time() . '.' . $ext;
                $targetPath = $targetDir . '/' . $filename;
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $ogImage = 'uploads/seo/' . $filename;
                }
            } else {
                Auth::setFlash('error', 'Invalid image format. Allowed formats: JPG, PNG, WEBP, SVG.');
            }
        }

        $db = Database::connect();
        if ($db) {
            $stmt = $db->prepare("UPDATE `seo_settings` SET `site_title` = :t, `default_meta_desc` = :d, `default_keywords` = :k, `default_og_image` = :og, `robots_txt` = :r WHERE `id` = 1");
            $stmt->execute(['t' => $siteTitle, 'd' => $metaDesc, 'k' => $keywords, 'og' => ($ogImage !== '' ? $ogImage : null), 'r' => $robots]);
        }

        Cache::deletePattern('home:*');
        Cache::clear();

        Auth::setFlash('success', 'Global SEO parameters and OpenGraph social share image saved.');
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

