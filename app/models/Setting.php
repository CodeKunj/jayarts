<?php
/**
 * JayArt Global Store Settings Model
 */

declare(strict_types=1);

class Setting extends Model {
    protected string $table = 'settings';

    private static ?array $cache = null;

    /**
     * Default Fallback Settings
     */
    public static function defaults(): array {
        return [
            'store_name' => 'JayArt Gallery',
            'store_tagline' => 'Curated Original Paintings & Fine Art Masterworks',
            'store_phone' => '+91 98765 43210',
            'store_email' => 'support@jayart.com',
            'store_address' => 'Heritage Arts Boulevard, Fort, Mumbai 400001, Maharashtra, India',
            'whatsapp_number' => '919876543210',
            'currency_code' => 'INR',
            'currency_symbol' => '₹',
            'tax_percentage' => '0',
            'free_shipping_threshold' => '0',
            'standard_shipping_fee' => '0',
            'delivery_charge' => '0',
            'return_policy_days' => '14',
            'social_instagram' => 'https://instagram.com/jayart',
            'social_facebook' => 'https://facebook.com/jayart',
            'social_pinterest' => 'https://pinterest.com/jayart',
            'social_youtube' => 'https://youtube.com/@jayart',
            'custom_footer_text' => '© 2026 JayArt Gallery. All Rights Reserved. Curated Original Fine Artworks.',
        ];
    }

    /**
     * Get single setting value by key
     */
    public static function get(string $key, ?string $default = null): ?string {
        // 1. Check session modifications
        if (isset($_SESSION['custom_settings'][$key])) {
            return (string)$_SESSION['custom_settings'][$key];
        }

        // 2. Load from DB or defaults if cache empty
        if (self::$cache === null) {
            self::loadAll();
        }

        return self::$cache[$key] ?? $default ?? (self::defaults()[$key] ?? null);
    }

    /**
     * Load all settings into cache
     */
    public static function loadAll(): array {
        $settings = self::defaults();

        // Overlay DB rows if available
        try {
            $db = Database::connect();
            if ($db) {
                $stmt = $db->query("SELECT `setting_key`, `setting_value` FROM `settings`");
                if ($stmt) {
                    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($rows as $row) {
                        $settings[$row['setting_key']] = $row['setting_value'];
                    }
                    if (isset($settings['currency_symbol'])) {
                        $settings['currency_symbol'] = Currency::cleanSymbol($settings['currency_code'] ?? 'INR', $settings['currency_symbol']);
                    }
                }
            }
        } catch (Throwable $e) {
            // DB error ignored in demo mode
        }

        // Overlay session modifications
        if (!empty($_SESSION['custom_settings']) && is_array($_SESSION['custom_settings'])) {
            foreach ($_SESSION['custom_settings'] as $k => $v) {
                $settings[$k] = (string)$v;
            }
        }

        self::$cache = $settings;
        return $settings;
    }

    /**
     * Set setting value
     */
    public static function set(string $key, ?string $value): void {
        if (!isset($_SESSION['custom_settings'])) {
            $_SESSION['custom_settings'] = [];
        }
        $_SESSION['custom_settings'][$key] = $value;

        if (self::$cache !== null) {
            self::$cache[$key] = $value;
        }

        try {
            $db = Database::connect();
            if ($db) {
                $stmt = $db->prepare("INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES (:k, :v) ON DUPLICATE KEY UPDATE `setting_value` = :v2");
                $stmt->execute(['k' => $key, 'v' => $value, 'v2' => $value]);
            }
        } catch (Throwable $e) {
            // DB error ignored
        }
    }
}
