<?php
/**
 * JayArt Admin Cache Management Controller
 * High-performance Redis-like file caching dashboard and management
 */

declare(strict_types=1);

class AdminCacheController extends Controller {

    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Cache Management Dashboard
     */
    public function index(): void {
        $stats = Cache::stats();

        $this->renderAdmin('cache/index', [
            'stats' => $stats,
            'title' => 'Cache Engine & Telemetry — JayArt Admin'
        ]);
    }

    /**
     * Flush All Cache Entries
     */
    public function clearAll(): void {
        Validator::validateCsrf();

        Cache::clear();
        Auth::setFlash('success', 'Entire cache storage flushed successfully. All modules will re-warm on demand.');
        header('Location: ' . ADMIN_URL . '/cache');
        exit;
    }

    /**
     * Invalidate Product & Catalog Cache
     */
    public function clearProducts(): void {
        Validator::validateCsrf();

        $purged = Cache::deletePattern('products:*');
        Auth::setFlash('success', "Purged {$purged} product catalog and detail cache entries.");
        header('Location: ' . ADMIN_URL . '/cache');
        exit;
    }

    /**
     * Invalidate Art Category & Mediums Cache
     */
    public function clearCategories(): void {
        Validator::validateCsrf();

        $purged = Cache::deletePattern('categories:*');
        Auth::setFlash('success', "Purged {$purged} category and art medium cache entries.");
        header('Location: ' . ADMIN_URL . '/cache');
        exit;
    }

    /**
     * Invalidate Homepage Bento & Featured Cache
     */
    public function clearHome(): void {
        Validator::validateCsrf();

        $purged = Cache::deletePattern('home:*');
        Auth::setFlash('success', "Purged {$purged} homepage showcase and coupon cache entries.");
        header('Location: ' . ADMIN_URL . '/cache');
        exit;
    }

    /**
     * Toggle Cache Engine State (Enabled / Disabled)
     */
    public function toggle(): void {
        Validator::validateCsrf();

        $currentState = Cache::isEnabled();
        $newState = !$currentState;

        Setting::set('cache_enabled', $newState ? '1' : '0');
        Cache::setEnabled($newState);

        $msg = $newState 
            ? 'High-performance Redis-like caching engine activated.' 
            : 'Caching engine disabled. All requests are currently routed directly to MySQL.';
        Auth::setFlash('success', $msg);
        header('Location: ' . ADMIN_URL . '/cache');
        exit;
    }

    /**
     * Update TTL Configurations
     */
    public function updateTtls(): void {
        Validator::validateCsrf();

        $prodTtl = max(60, (int)($_POST['product_ttl'] ?? 1800));
        $catTtl  = max(60, (int)($_POST['category_ttl'] ?? 86400));
        $homeTtl = max(60, (int)($_POST['homepage_ttl'] ?? 900));
        $srchTtl = max(60, (int)($_POST['search_ttl'] ?? 600));

        Setting::set('cache_product_ttl', (string)$prodTtl);
        Setting::set('cache_category_ttl', (string)$catTtl);
        Setting::set('cache_homepage_ttl', (string)$homeTtl);
        Setting::set('cache_search_ttl', (string)$srchTtl);

        Auth::setFlash('success', 'Cache Time-To-Live (TTL) parameters updated successfully.');
        header('Location: ' . ADMIN_URL . '/cache');
        exit;
    }
}

