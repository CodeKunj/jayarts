<?php
/**
 * JayArt Admin Poster-Style & AdSense Advertisement Controller
 */

declare(strict_types=1);

class AdminAdController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * List all ad slots with their configurations and analytics
     */
    public function index(): void {
        $slots = Ad::getAllSlots();

        $this->renderAdmin('ads/index', [
            'slots' => $slots,
            'title' => 'Poster Ads & Monetization — JayArt Admin'
        ]);
    }

    /**
     * Update an ad slot configuration
     */
    public function update(int $id): void {
        Validator::validateCsrf();

        $slots = Ad::getAllSlots();
        $slot = null;
        foreach ($slots as $s) {
            if ((int)$s['id'] === $id) {
                $slot = $s;
                break;
            }
        }

        if (!$slot) {
            Auth::setFlash('error', 'Ad slot not found.');
            header('Location: ' . ADMIN_URL . '/ads');
            exit;
        }

        $adType      = in_array($_POST['ad_type'] ?? '', ['custom', 'adsense'], true) ? $_POST['ad_type'] : 'custom';
        $title       = trim((string)($_POST['title'] ?? ''));
        $subtitle    = trim((string)($_POST['subtitle'] ?? ''));
        $ctaText     = trim((string)($_POST['cta_text'] ?? 'Explore Now'));
        $targetUrl   = trim((string)($_POST['target_url'] ?? ''));
        $adsenseCode = trim((string)($_POST['adsense_code'] ?? ''));
        $isActive    = isset($_POST['is_active']) ? 1 : 0;

        $updateData = [
            'ad_type'      => $adType,
            'title'        => $title,
            'subtitle'     => $subtitle,
            'cta_text'     => $ctaText,
            'target_url'   => $targetUrl,
            'adsense_code' => $adsenseCode,
            'is_active'    => $isActive,
        ];

        // Handle Image URL input if provided
        if (!empty($_POST['banner_desktop_url'])) {
            $updateData['banner_desktop'] = trim((string)$_POST['banner_desktop_url']);
        }
        if (!empty($_POST['banner_mobile_url'])) {
            $updateData['banner_mobile'] = trim((string)$_POST['banner_mobile_url']);
        }

        // Handle Desktop Banner Upload (overrides URL if file uploaded)
        if (!empty($_FILES['banner_desktop']['name']) && $_FILES['banner_desktop']['error'] === UPLOAD_ERR_OK) {
            $uploadedPath = $this->handleUpload('banner_desktop');
            if ($uploadedPath) {
                $updateData['banner_desktop'] = $uploadedPath;
            }
        }

        // Handle Mobile Banner Upload
        if (!empty($_FILES['banner_mobile']['name']) && $_FILES['banner_mobile']['error'] === UPLOAD_ERR_OK) {
            $uploadedPath = $this->handleUpload('banner_mobile');
            if ($uploadedPath) {
                $updateData['banner_mobile'] = $uploadedPath;
            }
        }

        Ad::updateSlotData($id, $updateData);

        // Invalidate frontend cache keys so updates reflect instantly
        if (class_exists('Cache')) {
            Cache::deletePattern('home:*');
            Cache::deletePattern('products:*');
            Cache::deletePattern('ads:*');
        }

        Auth::setFlash('success', 'Ad slot "' . htmlspecialchars($slot['slot_name']) . '" updated successfully.');
        header('Location: ' . ADMIN_URL . '/ads?slot=' . $id);
        exit;
    }

    /**
     * Create a new custom ad slot
     */
    public function store(): void {
        Validator::validateCsrf();

        $slotName = trim((string)($_POST['slot_name'] ?? 'New Art Exhibition Banner'));
        $rawKey = trim((string)($_POST['slot_key'] ?? ''));
        if (empty($rawKey)) {
            $slotKey = 'ad_' . strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $slotName));
        } else {
            $slotKey = strtolower(preg_replace('/[^a-zA-Z0-9_]+/', '_', $rawKey));
        }

        $slotLocation = trim((string)($_POST['slot_location'] ?? 'Custom Storefront Slot'));
        $adType = in_array($_POST['ad_type'] ?? '', ['custom', 'adsense'], true) ? $_POST['ad_type'] : 'custom';
        $title = trim((string)($_POST['title'] ?? $slotName));
        $subtitle = trim((string)($_POST['subtitle'] ?? ''));
        $ctaText = trim((string)($_POST['cta_text'] ?? 'Explore Now'));
        $targetUrl = trim((string)($_POST['target_url'] ?? SITE_URL . '/shop'));
        $adsenseCode = trim((string)($_POST['adsense_code'] ?? ''));
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $bannerDesktop = trim((string)($_POST['banner_desktop_url'] ?? 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=800&q=80'));
        if (!empty($_FILES['banner_desktop']['name']) && $_FILES['banner_desktop']['error'] === UPLOAD_ERR_OK) {
            $uploaded = $this->handleUpload('banner_desktop');
            if ($uploaded) {
                $bannerDesktop = $uploaded;
            }
        }

        $newId = Ad::createSlot([
            'slot_key' => $slotKey,
            'slot_name' => $slotName,
            'slot_location' => $slotLocation,
            'ad_type' => $adType,
            'title' => $title,
            'subtitle' => $subtitle,
            'cta_text' => $ctaText,
            'target_url' => $targetUrl,
            'banner_desktop' => $bannerDesktop,
            'banner_mobile' => $bannerDesktop,
            'adsense_code' => $adsenseCode,
            'is_active' => $isActive,
            'impressions_count' => 0,
            'clicks_count' => 0,
        ]);

        if (class_exists('Cache')) {
            Cache::deletePattern('home:*');
            Cache::deletePattern('products:*');
            Cache::deletePattern('ads:*');
        }

        Auth::setFlash('success', 'New ad banner slot "' . htmlspecialchars($slotName) . '" created successfully.');
        header('Location: ' . ADMIN_URL . '/ads?slot=' . $newId);
        exit;
    }

    /**
     * Delete an ad slot
     */
    public function delete(int $id): void {
        Validator::validateCsrf();

        Ad::deleteSlot($id);

        if (class_exists('Cache')) {
            Cache::deletePattern('home:*');
            Cache::deletePattern('products:*');
            Cache::deletePattern('ads:*');
        }

        Auth::setFlash('success', 'Ad slot deleted successfully.');
        header('Location: ' . ADMIN_URL . '/ads');
        exit;
    }

    /**
     * Helper to process image uploads securely
     */
    private function handleUpload(string $fieldKey): ?string {
        $file = $_FILES[$fieldKey] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExts, true)) {
            return null;
        }

        $uploadDir = UPLOADS_PATH . '/ads';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = 'ad_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $targetFile = $uploadDir . '/' . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return '/uploads/ads/' . $fileName;
        }

        return null;
    }
}
