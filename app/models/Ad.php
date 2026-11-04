<?php
/**
 * JayArt Poster-Style & AdSense Advertisement Model
 */

declare(strict_types=1);

class Ad extends Model {
    protected string $table = 'ad_slots';

    /**
     * Default curated ad slots for instant demonstration & zero-config fallbacks
     */
    public static function getDefaultSlots(): array {
        $baseUrl = defined('SITE_URL') ? SITE_URL : '';
        return [
            [
                'id' => 1,
                'slot_key' => 'in_grid_poster',
                'slot_name' => 'Art Gallery Exhibition Poster (In-Grid)',
                'slot_location' => 'Homepage & Shop Artwork Grid (Slot #5)',
                'ad_type' => 'custom',
                'title' => 'Featured Master Solo: The Banaras Series',
                'subtitle' => 'Archival museum-quality watercolor prints on 300gsm French Arches cotton rag with signed provenance certificate.',
                'cta_text' => 'View Exhibition',
                'target_url' => $baseUrl . '/shop?category=watercolor-paintings',
                'banner_desktop' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=600&q=80',
                'banner_mobile' => 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=400&q=80',
                'adsense_code' => '',
                'impressions_count' => 14250,
                'clicks_count' => 840,
                'is_active' => 1
            ],
            [
                'id' => 2,
                'slot_key' => 'footer_banner',
                'slot_name' => 'Footer Fine Art Banner',
                'slot_location' => 'Bottom Above Footer (Global)',
                'ad_type' => 'custom',
                'title' => 'Own a Certified Masterwork & Preserve Heritage',
                'subtitle' => 'Complimentary temperature-controlled crated delivery with physical tamper-evident seal across India.',
                'cta_text' => 'Explore Collection',
                'target_url' => $baseUrl . '/shop',
                'banner_desktop' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=1200&q=80',
                'banner_mobile' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=600&q=80',
                'adsense_code' => '',
                'impressions_count' => 9840,
                'clicks_count' => 520,
                'is_active' => 1
            ],
            [
                'id' => 3,
                'slot_key' => 'header_billboard',
                'slot_name' => 'Top Exhibition Announcement Strip',
                'slot_location' => 'Homepage Hero & Header',
                'ad_type' => 'custom',
                'title' => 'Spring Art Salon 2026 Live Now',
                'subtitle' => 'Curated collection of 40 new original works from emerging masters now available for collector acquisition.',
                'cta_text' => 'Explore Salon',
                'target_url' => $baseUrl . '/shop?filter=deals',
                'banner_desktop' => 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=1200&q=80',
                'banner_mobile' => 'https://images.unsplash.com/photo-1541701494587-cb58502866ab?w=600&q=80',
                'adsense_code' => '',
                'impressions_count' => 18910,
                'clicks_count' => 1120,
                'is_active' => 1
            ],
            [
                'id' => 4,
                'slot_key' => 'in_article_blog',
                'slot_name' => 'Art Insights Sponsor Banner',
                'slot_location' => 'Inside Blog Post Story',
                'ad_type' => 'custom',
                'title' => 'Artisanal Framing & Canvas Preservation',
                'subtitle' => 'Custom Italian wood mouldings and museum-grade UV conservation glass for fine art collectors.',
                'cta_text' => 'Book Custom Frame',
                'target_url' => $baseUrl . '/contact',
                'banner_desktop' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=1200&q=80',
                'banner_mobile' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?w=600&q=80',
                'adsense_code' => '',
                'impressions_count' => 5430,
                'clicks_count' => 310,
                'is_active' => 1
            ]
        ];
    }

    /**
     * Get all ad slots for Admin Management
     */
    public static function getAllSlots(): array {
        // 1. First check session modifications
        if (!empty($_SESSION['custom_ad_slots']) && is_array($_SESSION['custom_ad_slots'])) {
            return $_SESSION['custom_ad_slots'];
        }

        // 2. Try fetching from database
        $adModel = new self();
        if ($adModel->db !== null) {
            try {
                $slots = $adModel->fetchAll("SELECT * FROM `ad_slots` ORDER BY `id` ASC");
                if (!empty($slots)) {
                    return $slots;
                }
            } catch (Throwable $e) {
                // Database offline or table missing, fallback
            }
        }

        // 3. Fallback to curated default slots
        $defaults = self::getDefaultSlots();
        $_SESSION['custom_ad_slots'] = $defaults;
        return $defaults;
    }

    /**
     * Update ad slot data (database + session sync)
     */
    public static function updateSlotData(int $id, array $data): bool {
        $slots = self::getAllSlots();
        $found = false;

        foreach ($slots as $idx => $slot) {
            if ((int)$slot['id'] === $id) {
                $slots[$idx] = array_merge($slot, $data);
                $found = true;
                break;
            }
        }

        if ($found) {
            $_SESSION['custom_ad_slots'] = $slots;
        }

        // Also attempt database update if connected
        $adModel = new self();
        if ($adModel->db !== null) {
            try {
                $adModel->update($id, $data);
            } catch (Throwable $e) {
                // DB error ignored in offline demo mode
            }
        }

        return $found;
    }

    /**
     * Create a new Ad Slot (DB + Session Sync)
     */
    public static function createSlot(array $data): int {
        $adModel = new self();
        $newId = 0;

        if ($adModel->db !== null) {
            try {
                $newId = $adModel->insert($data);
            } catch (Throwable $e) {
                // Fallback if DB insert fails
            }
        }

        $slots = self::getAllSlots();
        if ($newId <= 0) {
            $ids = !empty($slots) ? array_column($slots, 'id') : [0];
            $newId = max($ids) + 1;
        }

        $data['id'] = $newId;
        $data['impressions_count'] = (int)($data['impressions_count'] ?? 0);
        $data['clicks_count'] = (int)($data['clicks_count'] ?? 0);
        $data['is_active'] = !empty($data['is_active']) ? 1 : 0;

        $slots[] = $data;
        $_SESSION['custom_ad_slots'] = $slots;

        return $newId;
    }

    /**
     * Delete an Ad Slot by ID (DB + Session Sync)
     */
    public static function deleteSlot(int $id): bool {
        $adModel = new self();
        if ($adModel->db !== null) {
            try {
                $adModel->delete($id);
            } catch (Throwable $e) {
                // DB error ignored
            }
        }

        $slots = self::getAllSlots();
        $filtered = array_values(array_filter($slots, fn($s) => (int)$s['id'] !== $id));
        $_SESSION['custom_ad_slots'] = $filtered;

        return true;
    }

    /**
     * Get Active Ad Slot by Key (Static & Instance Compatible)
     */
    public static function getSlot(string $slotKey): ?array {
        $slots = self::getAllSlots();
        foreach ($slots as $slot) {
            if ($slot['slot_key'] === $slotKey && !empty($slot['is_active'])) {
                return $slot;
            }
        }
        return null;
    }

    /**
     * Render Responsive Poster Ad HTML
     */
    public static function render(string $slotKey): string {
        $adModel = new self();
        $slot = $adModel->getSlot($slotKey);

        if (!$slot || empty($slot['is_active'])) {
            return '';
        }

        // If slot is AdSense
        if (($slot['ad_type'] ?? '') === 'adsense' && !empty($slot['adsense_code'])) {
            return '<div class="w-full my-4 flex justify-center overflow-hidden rounded-2xl">' . $slot['adsense_code'] . '</div>';
        }

        // Custom Curated Poster Ad
        $targetUrl = !empty($slot['target_url']) ? htmlspecialchars($slot['target_url'], ENT_QUOTES, 'UTF-8') : '#';
        $title = !empty($slot['title']) ? htmlspecialchars($slot['title'], ENT_QUOTES, 'UTF-8') : 'Featured Art Exhibition';
        $subtitle = !empty($slot['subtitle']) ? htmlspecialchars($slot['subtitle'], ENT_QUOTES, 'UTF-8') : 'Archival museum prints & master original acquisitions.';
        $cta = !empty($slot['cta_text']) ? htmlspecialchars($slot['cta_text'], ENT_QUOTES, 'UTF-8') : 'Explore Now';
        $imgSrc = !empty($slot['banner_desktop']) ? htmlspecialchars($slot['banner_desktop'], ENT_QUOTES, 'UTF-8') : 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=500&q=80';

        // 1. Render in-grid poster matching Shopcart card proportions
        if ($slotKey === 'in_grid_poster') {
            return <<<HTML
<div class="product-card bg-white border border-stone-200/70 rounded-3xl p-3 sm:p-4 flex flex-col justify-between h-full group shadow-xs">
    <div class="product-img-wrap bg-stone-100 rounded-2xl relative overflow-hidden flex items-center justify-center p-3 mb-3 aspect-square">
        <span class="absolute top-3 left-3 text-[9px] uppercase tracking-wider font-extrabold px-2.5 py-1 bg-[#18181B] text-white rounded-full z-10 shadow-xs">
            Exhibition Partner
        </span>
        <img src="{$imgSrc}" alt="Exhibition Poster" class="max-h-full max-w-full object-contain rounded-xl transform group-hover:scale-105 transition-transform duration-300">
    </div>
    <div class="flex-1 flex flex-col justify-between pt-1">
        <div>
            <h4 class="font-bold text-sm text-[#18181B] leading-snug line-clamp-2">{$title}</h4>
            <p class="text-xs text-stone-500 mt-1 line-clamp-2 leading-relaxed">{$subtitle}</p>
        </div>
        <div class="mt-4">
            <a href="{$targetUrl}" class="w-full py-2.5 px-4 bg-[#18181B] hover:bg-black text-white text-xs font-bold rounded-full transition-all shadow-xs text-center block">
                {$cta} &rarr;
            </a>
        </div>
    </div>
</div>
HTML;
        }

        // 2. Footer or Header Billboard Poster Strip
        $stripImg = !empty($slot['banner_desktop']) ? htmlspecialchars($slot['banner_desktop'], ENT_QUOTES, 'UTF-8') : 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?w=200&q=80';
        return <<<HTML
<div class="w-full my-6 rounded-3xl overflow-hidden bg-[#F7F7F5] border border-stone-200/80 p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xs">
    <div class="flex items-center gap-4">
        <div class="w-16 h-16 rounded-2xl bg-white p-1 shadow-xs overflow-hidden flex-shrink-0 hidden sm:block border border-stone-200/50">
            <img src="{$stripImg}" class="w-full h-full object-cover rounded-xl" alt="Partner">
        </div>
        <div>
            <span class="text-[10px] uppercase tracking-wider font-extrabold px-2.5 py-0.5 bg-[#18181B] text-white rounded-full">Official Partner</span>
            <h3 class="text-base sm:text-lg font-extrabold text-[#18181B] mt-1">{$title}</h3>
            <p class="text-xs text-stone-600 mt-0.5">{$subtitle}</p>
        </div>
    </div>
    <a href="{$targetUrl}" class="px-7 py-3 bg-[#18181B] hover:bg-black text-white text-xs font-bold rounded-full transition-all whitespace-nowrap shadow-sm">
        {$cta} &rarr;
    </a>
</div>
HTML;
    }
}
