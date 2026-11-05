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

        Auth::setFlash('success', 'Store settings updated successfully.');
        header('Location: ' . ADMIN_URL . '/settings');
        exit;
    }
}

