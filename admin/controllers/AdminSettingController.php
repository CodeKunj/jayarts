<?php
/**
 * JayArt Admin General Store Settings Controller
 */

declare(strict_types=1);

class AdminSettingController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * Display the General Settings form
     */
    public function index(): void {
        $settings = Setting::loadAll();

        $this->renderAdmin('settings/index', [
            'settings' => $settings,
            'title'    => 'Store & Gallery Settings — JayArt Admin'
        ]);
    }

    /**
     * Save updated settings
     */
    public function update(): void {
        Validator::validateCsrf();

        $allowedKeys = [
            'store_name',
            'store_tagline',
            'store_phone',
            'store_email',
            'store_address',
            'whatsapp_number',
            'currency_code',
            'currency_symbol',
            'tax_percentage',
            'free_shipping_threshold',
            'standard_shipping_fee',
            'delivery_charge',
            'social_instagram',
            'social_facebook',
            'social_pinterest',
            'social_youtube',
            'return_policy_days',
            'custom_footer_text',
        ];

        foreach ($allowedKeys as $key) {
            if (isset($_POST[$key])) {
                $val = trim((string)$_POST[$key]);
                if ($key === 'currency_symbol') {
                    $val = Currency::cleanSymbol((string)($_POST['currency_code'] ?? 'INR'), $val);
                }
                Setting::set($key, $val);
            }
        }

        // Keep standard_shipping_fee and delivery_charge synced
        if (isset($_POST['standard_shipping_fee'])) {
            Setting::set('delivery_charge', trim((string)$_POST['standard_shipping_fee']));
        } elseif (isset($_POST['delivery_charge'])) {
            Setting::set('standard_shipping_fee', trim((string)$_POST['delivery_charge']));
        }

        Cache::clear();

        Auth::setFlash('success', 'Store settings and delivery charges updated successfully.');
        header('Location: ' . ADMIN_URL . '/settings');
        exit;
    }

    /**
     * Purge all dummy catalog, reviews, orders, and demo content
     */
    public function cleanDummyData(): void {
        Validator::validateCsrf();

        try {
            $db = Database::connect();
            if ($db !== null) {
                $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
                $tables = [
                    'products',
                    'product_images',
                    'product_seo',
                    'blogs',
                    'blog_seo',
                    'orders',
                    'order_items',
                    'delivery_tracking',
                    'delivery_milestones',
                    'reviews',
                    'wishlists',
                    'inventory_logs',
                    'support_tickets',
                    'ticket_messages',
                    'addresses',
                ];

                foreach ($tables as $t) {
                    try {
                        $db->exec("TRUNCATE TABLE `{$t}`;");
                    } catch (Throwable $e) {
                        // ignore if table doesn't exist yet
                    }
                }

                // Remove demo non-admin users
                try {
                    $db->exec("DELETE FROM `users` WHERE `role` != 'admin';");
                } catch (Throwable $e) {}

                $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
            }

            // Flush all file/Redis caches
            Cache::clear();

            Auth::setFlash('success', 'Database cleaned successfully! All dummy products, reviews, orders, and blogs have been removed.');
        } catch (Throwable $e) {
            Auth::setFlash('error', 'Database cleanup error: ' . $e->getMessage());
        }

        header('Location: ' . ADMIN_URL . '/settings');
        exit;
    }
}

