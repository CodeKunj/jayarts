<?php
/**
 * JayArt Admin - Poster Ads & Monetization Studio
 * Professional Executive Obsidian Slate & Emerald Theme
 * High UX clarity with Interactive Storefront Previews & Placement Maps
 */

$totalSlots = count($slots);
$activeSlots = count(array_filter($slots, fn($s) => !empty($s['is_active'])));
$totalImpressions = array_sum(array_column($slots, 'impressions_count'));
$totalClicks = array_sum(array_column($slots, 'clicks_count'));
$avgCtr = $totalImpressions > 0 ? round(($totalClicks / $totalImpressions) * 100, 2) : 0;

$selectedSlotId = isset($_GET['slot']) ? (int)$_GET['slot'] : (!empty($slots[0]['id']) ? (int)$slots[0]['id'] : 1);

// Friendly metadata for each ad placement slot
$slotMetadata = [
    'in_grid_poster' => [
        'friendly_name' => 'Shop Artwork Grid Card',
        'badge' => 'Catalog Grid',
        'badge_color' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'location_title' => 'Shop Catalog & Homepage Grid',
        'location_detail' => 'Appears as Card #4 directly among paintings in the artwork grid.',
        'target_pages' => '/shop & Homepage',
        'recommended_size' => 'Square (1:1 Ratio, e.g. 800 x 800 px)',
        'type_hint' => 'Blends seamlessly into the art grid with high engagement.',
        'wireframe' => 'grid',
        'preview_link' => SITE_URL . '/shop',
    ],
    'header_billboard' => [
        'friendly_name' => 'Homepage Hero Partner Box',
        'badge' => 'Hero Bento',
        'badge_color' => 'bg-blue-50 text-blue-700 border-blue-200',
        'location_title' => 'Top Hero Bento Grid (Widget #3)',
        'location_detail' => 'Positioned in the prime top section of the homepage beside featured paintings.',
        'target_pages' => 'Homepage (/) Hero Section',
        'recommended_size' => 'Landscape (1200 x 600 px or 600 x 400 px)',
        'type_hint' => 'Prime high-conversion spot seen by all homepage visitors.',
        'wireframe' => 'hero',
        'preview_link' => SITE_URL . '/',
    ],
    'footer_banner' => [
        'friendly_name' => 'Storefront Footer Billboard',
        'badge' => 'Global Footer',
        'badge_color' => 'bg-purple-50 text-purple-700 border-purple-200',
        'location_title' => 'Full-Width Strip above Footer',
        'location_detail' => 'Spans across the bottom of all storefront pages just before the dark footer.',
        'target_pages' => 'All Storefront Pages (Global)',
        'recommended_size' => 'Wide Billboard (1200 x 250 px)',
        'type_hint' => 'Great for brand partnerships or special museum announcements.',
        'wireframe' => 'footer',
        'preview_link' => SITE_URL . '/',
    ],
    'in_article_blog' => [
        'friendly_name' => 'Art Stories & Magazine Banner',
        'badge' => 'Curator Journal',
        'badge_color' => 'bg-amber-50 text-amber-700 border-amber-200',
        'location_title' => 'Inside Curated Blog Articles',
        'location_detail' => 'Embedded between article paragraphs in the Art Insights journal.',
        'target_pages' => 'Curator Stories (/blog/{slug})',
        'recommended_size' => 'Editorial Banner (900 x 300 px)',
        'type_hint' => 'Targeted to serious collectors reading educational content.',
        'wireframe' => 'article',
        'preview_link' => SITE_URL . '/blog/lure-of-palette-knife-impasto-oil',
    ]
];
?>

<div class="space-y-6">
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-heading font-bold text-slate-900 tracking-tight">Poster Ads &amp; Exhibition Banners</h1>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    CLS = 0 Guaranteed
                </span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Easily control poster ads, featured exhibition banners, and Google AdSense units across storefront slots.</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" 
                    onclick="openCreateAdModal()" 
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-[#0F172A] hover:bg-[#020617] text-white shadow-xs transition-all cursor-pointer">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>Add Other Ad</span>
            </button>
            <a href="<?= SITE_URL ?>/shop" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 shadow-xs transition-all">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                <span>View Live Shop</span>
            </a>
        </div>
    </div>

    <!-- Monetization Analytics KPIs -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Configured Slots</span>
                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600" style="width: 32px; height: 32px; min-width: 32px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-slate-900 mt-2"><?= $totalSlots ?></p>
            <p class="text-[11px] text-slate-400 mt-1"><?= $activeSlots ?> currently live on website</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Total Views</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600" style="width: 32px; height: 32px; min-width: 32px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-slate-900 mt-2"><?= number_format($totalImpressions) ?></p>
            <p class="text-[11px] text-emerald-600 mt-1 font-medium">100% Zero layout shift</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Visitor Clicks</span>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600" style="width: 32px; height: 32px; min-width: 32px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-slate-900 mt-2"><?= number_format($totalClicks) ?></p>
            <p class="text-[11px] text-blue-600 mt-1 font-medium">Verified interactions</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Average CTR</span>
                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600" style="width: 32px; height: 32px; min-width: 32px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
            </div>
            <p class="text-2xl font-bold font-mono text-slate-900 mt-2"><?= $avgCtr ?>%</p>
            <p class="text-[11px] text-purple-600 mt-1 font-medium">Collector engagement rate</p>
        </div>
    </div>

    <!-- Quick How-To Instructions (Zero Technical Jargon) -->
    <div class="bg-gradient-to-r from-slate-900 to-[#0F172A] text-white p-5 rounded-2xl border border-slate-800 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="flex-1">
                <h3 class="font-heading text-sm font-bold text-white">How Ad Slots Work in JayArt</h3>
                <p class="text-xs text-slate-300 mt-0.5 leading-relaxed">
                    Select a slot placement below to configure what visitors see. You can publish your own <strong>Custom Art Exhibition Poster</strong> (with image, headline, and link) or paste <strong>Google AdSense</strong> HTML code. Turn the slot ON or OFF anytime with one click.
                </p>
            </div>
        </div>
    </div>

    <!-- Slot Selection Tabs (Primary Navigation) -->
    <div>
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500">1. Select Website Placement Slot:</h2>
            <span class="text-[11px] text-slate-400">Click a slot to configure &amp; preview</span>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <?php foreach ($slots as $idx => $slot): ?>
                <?php
                $meta = $slotMetadata[$slot['slot_key']] ?? [
                    'friendly_name' => $slot['slot_name'],
                    'badge' => 'Slot #' . $slot['id'],
                    'badge_color' => 'bg-slate-100 text-slate-700 border-slate-200',
                    'location_title' => $slot['slot_location'],
                    'location_detail' => 'Storefront advertisement placement.',
                    'target_pages' => 'Storefront',
                    'recommended_size' => 'Standard Banner',
                    'wireframe' => 'generic',
                    'preview_link' => SITE_URL . '/',
                ];
                $isActiveSlot = ((int)$slot['id'] === $selectedSlotId);
                ?>
                <button type="button" 
                        onclick="switchSlotTab(<?= (int)$slot['id'] ?>)" 
                        id="slot-tab-btn-<?= (int)$slot['id'] ?>"
                        class="slot-tab-btn p-4 rounded-2xl border text-left transition-all duration-200 cursor-pointer flex flex-col justify-between <?= $isActiveSlot ? 'bg-[#0F172A] text-white shadow-md border-transparent' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300 hover:shadow-xs' ?>">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider <?= $isActiveSlot ? 'bg-white/10 text-white border border-white/20' : $meta['badge_color'] ?>">
                                <?= htmlspecialchars($meta['badge']) ?>
                            </span>
                            <?php if (!empty($slot['is_active'])): ?>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold <?= $isActiveSlot ? 'text-emerald-400' : 'text-emerald-700' ?>">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Live
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold <?= $isActiveSlot ? 'text-slate-400' : 'text-slate-400' ?>">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Off
                                </span>
                            <?php endif; ?>
                        </div>
                        <h3 class="font-heading font-bold text-xs leading-snug line-clamp-1"><?= htmlspecialchars($meta['friendly_name']) ?></h3>
                        <p class="text-[11px] <?= $isActiveSlot ? 'text-slate-300' : 'text-slate-500' ?> mt-1 line-clamp-2 leading-relaxed">
                            <?= htmlspecialchars($meta['location_detail']) ?>
                        </p>
                    </div>
                    <div class="pt-3 mt-3 border-t <?= $isActiveSlot ? 'border-white/10' : 'border-slate-100' ?> flex items-center justify-between text-[10px]">
                        <span class="<?= $isActiveSlot ? 'text-slate-400' : 'text-slate-400' ?>">Key: <code class="font-mono"><?= htmlspecialchars($slot['slot_key']) ?></code></span>
                        <span class="font-mono font-semibold <?= $isActiveSlot ? 'text-emerald-300' : 'text-slate-700' ?>"><?= number_format($slot['impressions_count'] ?? 0) ?> views</span>
                    </div>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Active Slot Studio (Forms + Live Preview + Placement Maps) -->
    <?php foreach ($slots as $slot): ?>
        <?php
        $slotId = (int)$slot['id'];
        $isActiveSlot = ($slotId === $selectedSlotId);
        $meta = $slotMetadata[$slot['slot_key']] ?? [
            'friendly_name' => $slot['slot_name'],
            'badge' => 'Slot #' . $slotId,
            'badge_color' => 'bg-slate-100 text-slate-700 border-slate-200',
            'location_title' => $slot['slot_location'],
            'location_detail' => 'Storefront advertisement placement.',
            'target_pages' => 'Storefront',
            'recommended_size' => 'Standard Banner',
            'wireframe' => 'generic',
            'preview_link' => SITE_URL . '/',
        ];
        $isSlotLive = !empty($slot['is_active']);
        $adType = ($slot['ad_type'] ?? '') === 'adsense' ? 'adsense' : 'custom';
        $fallbackImg = 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=600&q=80';
        $currentImg = !empty($slot['banner_desktop']) ? $slot['banner_desktop'] : $fallbackImg;
        ?>

        <div id="slot-pane-<?= $slotId ?>" class="slot-pane <?= $isActiveSlot ? '' : 'hidden' ?> space-y-6">
            
            <!-- Slot Header & Live Status Banner -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-sm" style="width: 40px; height: 40px; min-width: 40px;">
                        #<?= $slotId ?>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 class="text-base font-heading font-bold text-slate-900"><?= htmlspecialchars($meta['friendly_name']) ?></h2>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider <?= $meta['badge_color'] ?>">
                                <?= htmlspecialchars($meta['badge']) ?>
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Target Placement: <strong class="text-slate-700"><?= htmlspecialchars($meta['location_title']) ?></strong> &bull; Size: <span class="text-slate-600"><?= htmlspecialchars($meta['recommended_size']) ?></span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <a href="<?= htmlspecialchars($meta['preview_link']) ?>" target="_blank" class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors flex items-center gap-1.5">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        <span>Open Target Page</span>
                    </a>
                </div>
            </div>

            <!-- 2-Column Studio: Left = Edit Form, Right = Live Preview & Location Map -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                <!-- LEFT COLUMN: Simplified Configuration Form (7 Cols) -->
                <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    
                    <div class="p-5 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                        <div>
                            <h3 class="font-heading font-bold text-sm text-slate-900">2. Configure Slot Content</h3>
                            <p class="text-[11px] text-slate-500 mt-0.5">Customize your poster image, messaging, or AdSense code.</p>
                        </div>
                        <span class="text-[10px] font-mono text-slate-400">ID: <?= $slotId ?></span>
                    </div>

                    <form method="POST" action="<?= ADMIN_URL ?>/ads/update/<?= $slotId ?>" enctype="multipart/form-data" class="p-6 space-y-6">
                        <?= Validator::csrfField() ?>

                        <!-- STEP 1: Live Status Toggle Switch -->
                        <div class="p-4 rounded-xl border <?= $isSlotLive ? 'border-emerald-200 bg-emerald-50/40' : 'border-slate-200 bg-slate-50' ?> flex items-center justify-between gap-4 transition-colors">
                            <div>
                                <label for="toggle-active-<?= $slotId ?>" class="text-xs font-bold text-slate-900 block cursor-pointer">
                                    Display Live on Storefront
                                </label>
                                <p class="text-[11px] text-slate-500 mt-0.5">
                                    <?= $isSlotLive ? 'Active: Visitors can currently see this banner on the website.' : 'Disabled: Hidden from visitors. Website layout smoothly collapses with zero shift.' ?>
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="checkbox" 
                                       id="toggle-active-<?= $slotId ?>" 
                                       name="is_active" 
                                       value="1" 
                                       <?= $isSlotLive ? 'checked' : '' ?> 
                                       onchange="toggleLiveStatusBadge(<?= $slotId ?>, this.checked)"
                                       class="sr-only peer">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        <!-- STEP 2: Ad Type Selector (Visual Radio Cards) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-2">Select Banner Format:</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <!-- Card 1: Custom Poster -->
                                <div id="card-type-custom-<?= $slotId ?>" 
                                     onclick="selectAdType(<?= $slotId ?>, 'custom')"
                                     class="p-4 rounded-xl border-2 cursor-pointer transition-all flex flex-col justify-between <?= $adType === 'custom' ? 'border-emerald-500 bg-emerald-50/30 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300' ?>">
                                    <div class="flex items-start justify-between">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center mb-2" style="width: 32px; height: 32px; min-width: 32px;">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <input type="radio" name="ad_type" id="radio-custom-<?= $slotId ?>" value="custom" <?= $adType === 'custom' ? 'checked' : '' ?> class="text-emerald-600 focus:ring-emerald-500 mt-1">
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xs text-slate-900">Custom Art Poster</h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Upload image, set headline, subtitle, and link to collection.</p>
                                    </div>
                                </div>

                                <!-- Card 2: Google AdSense -->
                                <div id="card-type-adsense-<?= $slotId ?>" 
                                     onclick="selectAdType(<?= $slotId ?>, 'adsense')"
                                     class="p-4 rounded-xl border-2 cursor-pointer transition-all flex flex-col justify-between <?= $adType === 'adsense' ? 'border-emerald-500 bg-emerald-50/30 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300' ?>">
                                    <div class="flex items-start justify-between">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center mb-2" style="width: 32px; height: 32px; min-width: 32px;">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                        </div>
                                        <input type="radio" name="ad_type" id="radio-adsense-<?= $slotId ?>" value="adsense" <?= $adType === 'adsense' ? 'checked' : '' ?> class="text-blue-600 focus:ring-blue-500 mt-1">
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xs text-slate-900">Google AdSense</h4>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Paste AdSense banner script or external HTML embed code.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION: Custom Poster Form Fields -->
                        <div id="custom-fields-<?= $slotId ?>" class="space-y-4 <?= $adType === 'adsense' ? 'hidden' : '' ?>">
                            
                            <!-- Artwork Image Upload & URL -->
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                                <label class="block text-xs font-bold text-slate-800">Poster / Artwork Image</label>
                                
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl bg-white border border-slate-200 overflow-hidden flex-shrink-0 shadow-xs flex items-center justify-center" style="width: 64px; height: 64px; min-width: 64px;">
                                        <img id="current-thumb-<?= $slotId ?>" src="<?= htmlspecialchars($currentImg) ?>" alt="Current Poster" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <div class="text-[11px] text-slate-600 font-medium">Upload File From Device:</div>
                                        <input type="file" 
                                               name="banner_desktop" 
                                               accept="image/*" 
                                               onchange="handleFilePreview(<?= $slotId ?>, this)"
                                               class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#0F172A] file:text-white hover:file:bg-[#020617] cursor-pointer">
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-slate-200/80">
                                    <label class="block text-[11px] font-semibold text-slate-600 mb-1">Or Paste Image URL (e.g. Unsplash or Cloud link):</label>
                                    <input type="text" 
                                           id="input-img-url-<?= $slotId ?>"
                                           name="banner_desktop_url" 
                                           value="<?= htmlspecialchars($slot['banner_desktop'] ?? '') ?>" 
                                           placeholder="https://images.unsplash.com/..." 
                                           oninput="updatePreview(<?= $slotId ?>)"
                                           class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0F172A]">
                                </div>
                            </div>

                            <!-- Headline / Title -->
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1">Main Headline / Exhibition Title</label>
                                <input type="text" 
                                       id="input-title-<?= $slotId ?>"
                                       name="title" 
                                       value="<?= htmlspecialchars($slot['title'] ?? '') ?>" 
                                       placeholder="e.g. Featured Master Solo: The Banaras Series" 
                                       oninput="updatePreview(<?= $slotId ?>)"
                                       class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0F172A] font-medium">
                                <p class="text-[11px] text-slate-400 mt-1">The primary bold headline visitors will see on this banner.</p>
                            </div>

                            <!-- Subtitle / Curator Note -->
                            <div>
                                <label class="block text-xs font-bold text-slate-800 mb-1">Curator Summary / Subtitle</label>
                                <textarea id="input-subtitle-<?= $slotId ?>"
                                          name="subtitle" 
                                          rows="2" 
                                          placeholder="e.g. Archival museum-quality watercolor prints on 300gsm French Arches cotton rag with signed provenance certificate." 
                                          oninput="updatePreview(<?= $slotId ?>)"
                                          class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0F172A]"><?= htmlspecialchars($slot['subtitle'] ?? '') ?></textarea>
                                <p class="text-[11px] text-slate-400 mt-1">1-2 sentences highlighting the exhibition provenance or special offer.</p>
                            </div>

                            <!-- CTA Button & Target URL -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 mb-1">Button Label (CTA)</label>
                                    <input type="text" 
                                           id="input-cta-<?= $slotId ?>"
                                           name="cta_text" 
                                           value="<?= htmlspecialchars($slot['cta_text'] ?? 'Explore Now') ?>" 
                                           placeholder="e.g. View Exhibition" 
                                           oninput="updatePreview(<?= $slotId ?>)"
                                           class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0F172A]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 mb-1">Click Destination URL</label>
                                    <input type="text" 
                                           id="input-url-<?= $slotId ?>"
                                           name="target_url" 
                                           value="<?= htmlspecialchars($slot['target_url'] ?? SITE_URL . '/shop') ?>" 
                                           placeholder="/shop?category=watercolor-paintings" 
                                           class="w-full text-xs rounded-xl border border-slate-200 px-3 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0F172A]">
                                </div>
                            </div>

                            <!-- Quick URL Helper Presets -->
                            <div>
                                <span class="text-[11px] text-slate-500 font-medium">Quick link presets:</span>
                                <div class="flex flex-wrap gap-1.5 mt-1">
                                    <button type="button" onclick="applyUrlPreset(<?= $slotId ?>, '<?= SITE_URL ?>/shop')" class="px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                        All Paintings (/shop)
                                    </button>
                                    <button type="button" onclick="applyUrlPreset(<?= $slotId ?>, '<?= SITE_URL ?>/shop?category=watercolor-paintings')" class="px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                        Watercolors
                                    </button>
                                    <button type="button" onclick="applyUrlPreset(<?= $slotId ?>, '<?= SITE_URL ?>/shop?category=oil-paintings')" class="px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                        Oil Paintings
                                    </button>
                                    <button type="button" onclick="applyUrlPreset(<?= $slotId ?>, '<?= SITE_URL ?>/shop?filter=deals')" class="px-2.5 py-1 rounded-lg text-[10px] font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition-colors">
                                        Offers &amp; Deals
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION: Google AdSense Code (shown when AdSense selected) -->
                        <div id="adsense-fields-<?= $slotId ?>" class="space-y-3 <?= $adType !== 'adsense' ? 'hidden' : '' ?>">
                            <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-200 space-y-2">
                                <label class="block text-xs font-bold text-slate-900">Google AdSense / HTML Script Snippet</label>
                                <textarea name="adsense_code" 
                                          rows="5" 
                                          placeholder="<script async src='https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-...'></script>&#10;<ins class='adsbygoogle' style='display:block' data-ad-client='ca-pub-...' data-ad-slot='...' data-ad-format='auto'></ins>&#10;<script>(adsbygoogle = window.adsbygoogle || []).push({});</script>" 
                                          class="w-full font-mono text-xs rounded-xl border border-slate-200 p-3 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($slot['adsense_code'] ?? '') ?></textarea>
                                <p class="text-[11px] text-slate-500 leading-relaxed">
                                    Paste your responsive Google AdSense code or third-party banner tag. JayArt automatically secures it within a layout-shift-free museum container.
                                </p>
                            </div>
                        </div>

                        <!-- Template Code Snippet for Developers / Storefront Integration -->
                        <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Storefront Template Snippet</span>
                                <p class="text-[11px] text-slate-600 mt-0.5">Paste this PHP code in any view to render this ad banner:</p>
                                <code class="font-mono text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded text-[11px] font-bold mt-1 inline-block select-all">&lt;?= Ad::render('<?= htmlspecialchars($slot['slot_key']) ?>') ?&gt;</code>
                            </div>
                            <button type="button" 
                                    onclick="copyCodeSnippet('<?= htmlspecialchars($slot['slot_key']) ?>', this)" 
                                    class="px-3 py-1.5 text-[11px] font-bold rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-xs flex-shrink-0 flex items-center gap-1">
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                                <span>Copy Code</span>
                            </button>
                        </div>

                        <!-- Action Bar: Save & Delete -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                            <div>
                                <?php if ($slotId > 3): ?>
                                    <button type="button" 
                                            onclick="confirmDeleteSlot(<?= $slotId ?>, '<?= htmlspecialchars(addslashes($slot['slot_name'])) ?>')" 
                                            class="text-xs text-red-600 hover:text-red-700 font-bold flex items-center gap-1.5 px-3 py-2 rounded-xl hover:bg-red-50 transition-colors cursor-pointer">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        <span>Delete Slot</span>
                                    </button>
                                <?php else: ?>
                                    <span class="text-[11px] text-slate-400">Core Storefront Slot</span>
                                <?php endif; ?>
                            </div>
                            <button type="submit" class="px-6 py-2.5 bg-[#0F172A] hover:bg-[#020617] text-white text-xs font-bold rounded-xl transition-all shadow-sm flex items-center gap-2 cursor-pointer">
                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Save Slot Configuration</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- RIGHT COLUMN: Live Storefront Preview & Visual Location Map (5 Cols) -->
                <div class="lg:col-span-5 space-y-6">
                    
                    <!-- 1. Real-Time Storefront Preview -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                        <div class="p-4 border-b border-slate-100 bg-slate-50/70 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Real-Time WYSIWYG</span>
                                <h3 class="font-heading font-bold text-xs text-slate-900">Live Storefront Preview</h3>
                            </div>
                            <!-- Desktop / Mobile View Switcher -->
                            <div class="flex items-center gap-1 bg-slate-200/70 p-1 rounded-xl">
                                <button type="button" 
                                        id="btn-device-desktop-<?= $slotId ?>"
                                        onclick="setPreviewDevice(<?= $slotId ?>, 'desktop')" 
                                        class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-slate-900 text-white shadow-xs transition-all">
                                    Desktop
                                </button>
                                <button type="button" 
                                        id="btn-device-mobile-<?= $slotId ?>"
                                        onclick="setPreviewDevice(<?= $slotId ?>, 'mobile')" 
                                        class="px-2.5 py-1 text-[10px] font-bold rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                                    Mobile
                                </button>
                            </div>
                        </div>

                        <div class="p-6 bg-slate-100/60 min-h-[360px] flex items-center justify-center">
                            <div id="preview-device-wrap-<?= $slotId ?>" class="w-full transition-all duration-300">
                                
                                <!-- CUSTOM POSTER PREVIEWS -->
                                <div id="preview-custom-<?= $slotId ?>" class="<?= $adType === 'adsense' ? 'hidden' : '' ?>">
                                    <?php if ($slot['slot_key'] === 'in_grid_poster'): ?>
                                        <!-- In-Grid Card Preview (Matches /shop Card) -->
                                        <div class="max-w-[280px] mx-auto bg-white border border-stone-200 rounded-3xl p-3 sm:p-4 shadow-sm flex flex-col justify-between">
                                            <div class="bg-stone-100 rounded-2xl relative overflow-hidden flex items-center justify-center p-3 mb-3 aspect-square">
                                                <span class="absolute top-3 left-3 text-[9px] uppercase tracking-wider font-extrabold px-2.5 py-1 bg-[#18181B] text-white rounded-full z-10 shadow-xs">
                                                    Exhibition Partner
                                                </span>
                                                <img data-preview-img="<?= $slotId ?>" 
                                                     src="<?= htmlspecialchars($currentImg) ?>" 
                                                     alt="Poster Preview" 
                                                     class="max-h-full max-w-full object-contain rounded-xl">
                                            </div>
                                            <div class="pt-1">
                                                <h4 data-preview-title="<?= $slotId ?>" class="font-bold text-sm text-[#18181B] leading-snug line-clamp-2">
                                                    <?= htmlspecialchars($slot['title'] ?? 'Featured Master Solo: The Banaras Series') ?>
                                                </h4>
                                                <p data-preview-subtitle="<?= $slotId ?>" class="text-xs text-stone-500 mt-1 line-clamp-2 leading-relaxed">
                                                    <?= htmlspecialchars($slot['subtitle'] ?? 'Archival museum-quality watercolor prints on 300gsm cotton rag with signed provenance certificate.') ?>
                                                </p>
                                                <div class="mt-4">
                                                    <span data-preview-cta="<?= $slotId ?>" class="w-full py-2.5 px-4 bg-[#18181B] text-white text-xs font-bold rounded-full text-center block pointer-events-none shadow-xs">
                                                        <?= htmlspecialchars($slot['cta_text'] ?? 'View Exhibition') ?> &rarr;
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    <?php elseif ($slot['slot_key'] === 'header_billboard'): ?>
                                        <!-- Homepage Bento Widget Preview -->
                                        <div class="max-w-[320px] mx-auto bg-white rounded-[24px] p-5 border border-black/[0.06] shadow-sm flex flex-col justify-between h-[180px]">
                                            <div class="flex items-center justify-between">
                                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-stone-700 flex items-center gap-1.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-[#18181B]"></span>
                                                    OFFICIAL PARTNER
                                                </span>
                                                <span class="w-5 h-5 rounded-full bg-stone-100 flex items-center justify-center text-stone-600 text-xs font-bold">↗</span>
                                            </div>
                                            <div>
                                                <h4 data-preview-title="<?= $slotId ?>" class="text-xs font-bold text-[#18181B] leading-snug line-clamp-1">
                                                    <?= htmlspecialchars($slot['title'] ?? 'Spring Art Salon 2026') ?>
                                                </h4>
                                                <p data-preview-subtitle="<?= $slotId ?>" class="text-[11px] text-stone-500 mt-0.5 line-clamp-2">
                                                    <?= htmlspecialchars($slot['subtitle'] ?? 'Curated collection of 40 new original works from emerging masters.') ?>
                                                </p>
                                            </div>
                                            <div class="flex items-center justify-between pt-1">
                                                <img data-preview-img="<?= $slotId ?>" src="<?= htmlspecialchars($currentImg) ?>" class="w-8 h-8 rounded-lg object-cover border border-black/5" alt="Partner">
                                                <span data-preview-cta="<?= $slotId ?>" class="bg-[#18181B] text-white text-[10px] font-bold px-3 py-1.5 rounded-full flex items-center gap-1">
                                                    <span><?= htmlspecialchars($slot['cta_text'] ?? 'Explore Salon') ?></span>
                                                    <span>↗</span>
                                                </span>
                                            </div>
                                        </div>

                                    <?php else: ?>
                                        <!-- Full-Width Footer Strip Preview -->
                                        <div class="w-full rounded-2xl overflow-hidden bg-[#F7F7F5] border border-stone-200 p-4 md:p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xs">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-xl bg-white p-1 shadow-xs overflow-hidden flex-shrink-0 border border-stone-200/50">
                                                    <img data-preview-img="<?= $slotId ?>" src="<?= htmlspecialchars($currentImg) ?>" class="w-full h-full object-cover rounded-lg" alt="Partner">
                                                </div>
                                                <div>
                                                    <span class="text-[9px] uppercase tracking-wider font-extrabold px-2 py-0.5 bg-[#18181B] text-white rounded-full">Official Partner</span>
                                                    <h3 data-preview-title="<?= $slotId ?>" class="text-xs sm:text-sm font-extrabold text-[#18181B] mt-1 line-clamp-1">
                                                        <?= htmlspecialchars($slot['title'] ?? 'Fine Art Exhibition Banner') ?>
                                                    </h3>
                                                    <p data-preview-subtitle="<?= $slotId ?>" class="text-[11px] text-stone-600 line-clamp-1">
                                                        <?= htmlspecialchars($slot['subtitle'] ?? 'Curated museum prints and masterwork collections.') ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <span data-preview-cta="<?= $slotId ?>" class="px-4 py-2 bg-[#18181B] text-white text-[11px] font-bold rounded-full whitespace-nowrap shadow-xs">
                                                <?= htmlspecialchars($slot['cta_text'] ?? 'Explore Collection') ?> &rarr;
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- ADSENSE PREVIEW -->
                                <div id="preview-adsense-<?= $slotId ?>" class="<?= $adType !== 'adsense' ? 'hidden' : '' ?> text-center p-6 bg-white border border-dashed border-blue-300 rounded-2xl">
                                    <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-2">
                                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                    </div>
                                    <h4 class="font-bold text-xs text-slate-800">Google AdSense Container</h4>
                                    <p class="text-[11px] text-slate-500 mt-1 max-w-[240px] mx-auto">
                                        Responsive ad unit delivered by Google. Fixed container prevents layout shift.
                                    </p>
                                </div>

                            </div>
                        </div>

                        <div class="p-3 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                            <span class="flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full <?= $isSlotLive ? 'bg-emerald-500' : 'bg-slate-400' ?>"></span>
                                Status: <strong class="<?= $isSlotLive ? 'text-emerald-700' : 'text-slate-600' ?>"><?= $isSlotLive ? 'Live on Storefront' : 'Disabled (Hidden)' ?></strong>
                            </span>
                            <span class="text-slate-400">Updates live as you type</span>
                        </div>
                    </div>

                    <!-- 2. Visual "Where It Appears" Location Map -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Where Does This Appear?</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700 font-mono"><?= htmlspecialchars($meta['target_pages']) ?></span>
                        </div>
                        <h4 class="font-heading font-bold text-sm text-slate-900"><?= htmlspecialchars($meta['location_title']) ?></h4>

                        <!-- Visual Layout Diagram -->
                        <?php if ($meta['wireframe'] === 'grid'): ?>
                            <!-- Grid Wireframe Diagram -->
                            <div class="bg-slate-950 text-white rounded-xl p-4 border border-slate-800 font-mono text-[11px]">
                                <div class="flex items-center justify-between pb-2 mb-3 border-b border-slate-800 text-slate-400 text-[10px]">
                                    <span>STOREFRONT: /shop (Product Catalog)</span>
                                    <span class="text-emerald-400 font-bold">Position: In-Grid Card #4</span>
                                </div>
                                <div class="grid grid-cols-4 gap-2 text-center text-[10px]">
                                    <div class="p-2 rounded-lg bg-slate-900 border border-slate-800 text-slate-400">
                                        <div class="h-6 bg-slate-800 rounded mb-1 flex items-center justify-center text-[8px]">Painting</div>
                                        #1
                                    </div>
                                    <div class="p-2 rounded-lg bg-slate-900 border border-slate-800 text-slate-400">
                                        <div class="h-6 bg-slate-800 rounded mb-1 flex items-center justify-center text-[8px]">Painting</div>
                                        #2
                                    </div>
                                    <div class="p-2 rounded-lg bg-slate-900 border border-slate-800 text-slate-400">
                                        <div class="h-6 bg-slate-800 rounded mb-1 flex items-center justify-center text-[8px]">Painting</div>
                                        #3
                                    </div>
                                    <div class="p-2 rounded-lg bg-emerald-950/90 border-2 border-emerald-500 text-emerald-300 font-bold shadow-lg shadow-emerald-950/50 relative">
                                        <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-emerald-500 text-slate-950 px-1 py-0.2 rounded text-[7px] font-extrabold uppercase">THIS AD</span>
                                        <div class="h-6 bg-emerald-900/60 rounded mb-1 flex items-center justify-center text-[8px] text-emerald-300">Poster</div>
                                        Card #4
                                    </div>
                                    <div class="p-2 rounded-lg bg-slate-900 border border-slate-800 text-slate-400">
                                        <div class="h-6 bg-slate-800 rounded mb-1 flex items-center justify-center text-[8px]">Painting</div>
                                        #5
                                    </div>
                                    <div class="p-2 rounded-lg bg-slate-900 border border-slate-800 text-slate-400">
                                        <div class="h-6 bg-slate-800 rounded mb-1 flex items-center justify-center text-[8px]">Painting</div>
                                        #6
                                    </div>
                                    <div class="p-2 rounded-lg bg-slate-900 border border-slate-800 text-slate-400">
                                        <div class="h-6 bg-slate-800 rounded mb-1 flex items-center justify-center text-[8px]">Painting</div>
                                        #7
                                    </div>
                                    <div class="p-2 rounded-lg bg-slate-900 border border-slate-800 text-slate-400">
                                        <div class="h-6 bg-slate-800 rounded mb-1 flex items-center justify-center text-[8px]">Painting</div>
                                        #8
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($meta['wireframe'] === 'hero'): ?>
                            <!-- Hero Wireframe Diagram -->
                            <div class="bg-slate-950 text-white rounded-xl p-4 border border-slate-800 font-mono text-[11px]">
                                <div class="flex items-center justify-between pb-2 mb-3 border-b border-slate-800 text-slate-400 text-[10px]">
                                    <span>STOREFRONT: / (Homepage Hero)</span>
                                    <span class="text-blue-400 font-bold">Position: Widget #3</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2 text-center text-[10px]">
                                    <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 col-span-1">
                                        <div class="h-8 bg-slate-800 rounded mb-1 flex items-center justify-center text-[8px]">Masterwork</div>
                                        Hero Art
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 col-span-1">
                                        <div class="h-8 bg-slate-800 rounded mb-1 flex items-center justify-center text-[8px]">Curator</div>
                                        Insights
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-blue-950/90 border-2 border-blue-500 text-blue-300 font-bold shadow-lg shadow-blue-950/50 relative col-span-1">
                                        <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-blue-500 text-white px-1 py-0.2 rounded text-[7px] font-extrabold uppercase">THIS AD</span>
                                        <div class="h-8 bg-blue-900/60 rounded mb-1 flex items-center justify-center text-[8px] text-blue-300">Partner</div>
                                        Partner Box
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Footer Wireframe Diagram -->
                            <div class="bg-slate-950 text-white rounded-xl p-4 border border-slate-800 font-mono text-[11px]">
                                <div class="flex items-center justify-between pb-2 mb-3 border-b border-slate-800 text-slate-400 text-[10px]">
                                    <span>STOREFRONT: Global (All Pages)</span>
                                    <span class="text-purple-400 font-bold">Position: Above Footer Strip</span>
                                </div>
                                <div class="space-y-2 text-center text-[10px]">
                                    <div class="p-2 rounded-lg bg-slate-900/60 border border-slate-800/60 text-slate-500 text-[9px]">
                                        Page Content / Art Catalog Section
                                    </div>
                                    <div class="p-2.5 rounded-lg bg-purple-950/90 border-2 border-purple-500 text-purple-300 font-bold shadow-lg shadow-purple-950/50 relative">
                                        <span class="absolute -top-2 left-1/2 -translate-x-1/2 bg-purple-500 text-white px-1 py-0.2 rounded text-[7px] font-extrabold uppercase">THIS AD</span>
                                        Full-Width Partner Billboard Strip (1200 x 250)
                                    </div>
                                    <div class="p-1.5 rounded-lg bg-slate-900 border border-slate-800 text-slate-400 text-[9px]">
                                        Dark Footer Navigation &amp; Copyright
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <p class="text-xs text-slate-500 leading-relaxed">
                            <?= htmlspecialchars($meta['type_hint']) ?>
                        </p>
                    </div>

                </div>

            </div>

        </div>
    <?php endforeach; ?>

    <!-- Modal: Add New Ad Slot / Banner -->
    <div id="create-ad-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
        <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-xl overflow-hidden max-h-[92vh] flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <div>
                    <h3 class="font-heading font-bold text-base text-slate-900">Add New Ad Slot / Banner</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Create a new exhibition poster or Google AdSense unit.</p>
                </div>
                <button type="button" onclick="closeCreateAdModal()" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-slate-300 flex items-center justify-center text-slate-600 transition-colors cursor-pointer">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <form method="POST" action="<?= ADMIN_URL ?>/ads/store" enctype="multipart/form-data" class="p-6 space-y-4 overflow-y-auto flex-1">
                <?= Validator::csrfField() ?>

                <!-- Placement Presets -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1">Target Placement / Location Preset</label>
                    <select id="modal-placement-preset" name="slot_location" onchange="autoFillSlotPreset(this.value)" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2.5 bg-white text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A]">
                        <option value="Shopping Cart Page (Above Summary)">Shopping Cart Page (Above Order Summary)</option>
                        <option value="Checkout Page (Privilege Strip)">Checkout Page (Collector Privilege Strip)</option>
                        <option value="Product Details Page (Under Artist Bio)">Product Details Page (Under Artist Bio)</option>
                        <option value="Homepage Artwork Grid">Homepage Artwork Grid</option>
                        <option value="Custom Storefront Placement">Custom Storefront Placement</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Slot Name</label>
                        <input type="text" name="slot_name" id="new-slot-name" required value="Cart Collector Promo" oninput="autoGenerateSlotKey(this.value)" placeholder="e.g. Cart Collector Promo" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0F172A]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Unique Slot Key</label>
                        <input type="text" name="slot_key" id="new-slot-key" required value="cart_collector_promo" placeholder="e.g. cart_collector_promo" class="w-full font-mono text-xs rounded-xl border border-slate-200 px-3.5 py-2 text-slate-800 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#0F172A]">
                    </div>
                </div>

                <!-- Ad Format -->
                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1">Ad Format</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="p-3 rounded-xl border border-slate-200 flex items-center gap-2 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="ad_type" value="custom" checked onchange="toggleModalType('custom')" class="text-[#0F172A] focus:ring-[#0F172A]">
                            <span class="text-xs font-semibold text-slate-800">Custom Art Poster</span>
                        </label>
                        <label class="p-3 rounded-xl border border-slate-200 flex items-center gap-2 cursor-pointer hover:bg-slate-50">
                            <input type="radio" name="ad_type" value="adsense" onchange="toggleModalType('adsense')" class="text-[#0F172A] focus:ring-[#0F172A]">
                            <span class="text-xs font-semibold text-slate-800">Google AdSense</span>
                        </label>
                    </div>
                </div>

                <div id="modal-custom-fields" class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Headline Text</label>
                        <input type="text" name="title" id="modal-title" value="Collector Exclusive: Free Insured Art Delivery" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0F172A]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Subtitle / Curator Summary</label>
                        <textarea name="subtitle" id="modal-subtitle" rows="2" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0F172A]">Temperature-controlled wooden crated packaging and authenticity certificate included on every original masterpiece.</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Button Text</label>
                            <input type="text" name="cta_text" id="modal-cta" value="Explore Guarantee" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0F172A]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Target Link URL</label>
                            <input type="text" name="target_url" id="modal-url" value="<?= SITE_URL ?>/shop" class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0F172A]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Poster Artwork Image</label>
                        <input type="text" name="banner_desktop_url" value="https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=800&q=80" placeholder="Paste image URL..." class="w-full text-xs rounded-xl border border-slate-200 px-3.5 py-2 text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-[#0F172A] mb-2">
                        <div class="text-[11px] text-slate-500 mb-1 font-medium">Or Upload File:</div>
                        <input type="file" name="banner_desktop" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                    </div>
                </div>

                <div id="modal-adsense-fields" class="space-y-2 hidden">
                    <label class="block text-xs font-bold text-slate-800 mb-1">Google AdSense HTML Code</label>
                    <textarea name="adsense_code" rows="4" placeholder="<script async src='https://pagead2.googlesyndication.com/...'></script>&#10;<ins class='adsbygoogle' ...></ins>" class="w-full font-mono text-xs rounded-xl border border-slate-200 p-3 bg-slate-50 text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#0F172A]"></textarea>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-[#0F172A] rounded border-slate-300 focus:ring-[#0F172A]">
                        <span class="text-xs font-semibold text-slate-800">Publish live immediately</span>
                    </label>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button type="button" onclick="closeCreateAdModal()" class="px-4 py-2 text-xs font-semibold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 text-xs font-bold rounded-xl bg-[#0F172A] hover:bg-[#020617] text-white shadow-xs transition-all cursor-pointer">
                        Create Ad Slot
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Form for Delete Action -->
    <form id="delete-slot-form" method="POST" action="" class="hidden">
        <?= Validator::csrfField() ?>
    </form>

</div>

<!-- Client-side Interactive Studio Script -->
<script>
// Modal Controls
function openCreateAdModal() {
    const modal = document.getElementById('create-ad-modal');
    if (modal) modal.classList.remove('hidden');
}

function closeCreateAdModal() {
    const modal = document.getElementById('create-ad-modal');
    if (modal) modal.classList.add('hidden');
}

// Auto-generate slot key slug from slot name
function autoGenerateSlotKey(val) {
    const keyInput = document.getElementById('new-slot-key');
    if (keyInput) {
        keyInput.value = val.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
    }
}

// Auto-fill preset details in modal
function autoFillSlotPreset(preset) {
    const nameInput = document.getElementById('new-slot-name');
    const keyInput = document.getElementById('new-slot-key');
    const titleInput = document.getElementById('modal-title');
    const subInput = document.getElementById('modal-subtitle');
    const ctaInput = document.getElementById('modal-cta');

    if (preset.includes('Cart')) {
        if (nameInput) nameInput.value = 'Cart Collector Promo';
        if (keyInput) keyInput.value = 'cart_collector_promo';
        if (titleInput) titleInput.value = 'Collector Privilege: Complimentary Insurance';
        if (subInput) subInput.value = 'Every original painting includes full transit coverage & sealed crating.';
        if (ctaInput) ctaInput.value = 'Learn More';
    } else if (preset.includes('Checkout')) {
        if (nameInput) nameInput.value = 'Checkout Trust Strip';
        if (keyInput) keyInput.value = 'checkout_trust_strip';
        if (titleInput) titleInput.value = 'Guaranteed Archival Provenance';
        if (subInput) subInput.value = 'Direct authentication certificate signed by the master artist.';
        if (ctaInput) ctaInput.value = 'View Certificate';
    } else if (preset.includes('Product Details')) {
        if (nameInput) nameInput.value = 'Bespoke Italian Framing';
        if (keyInput) keyInput.value = 'bespoke_framing_banner';
        if (titleInput) titleInput.value = 'Custom Museum Glass & Italian Wood Frames';
        if (subInput) subInput.value = 'Elevate your collector artwork with 99% UV-protective museum glazing.';
        if (ctaInput) ctaInput.value = 'Book Framing';
    }
}

// Toggle Modal Ad Type
function toggleModalType(type) {
    const customDiv = document.getElementById('modal-custom-fields');
    const adsenseDiv = document.getElementById('modal-adsense-fields');
    if (type === 'custom') {
        customDiv?.classList.remove('hidden');
        adsenseDiv?.classList.add('hidden');
    } else {
        customDiv?.classList.add('hidden');
        adsenseDiv?.classList.remove('hidden');
    }
}

// Copy PHP Integration Code
function copyCodeSnippet(key, btn) {
    const code = "<?= Ad::render('" + key + "') ?>";
    navigator.clipboard.writeText(code).then(() => {
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="text-emerald-700 font-bold">Copied!</span>';
        setTimeout(() => {
            btn.innerHTML = originalText;
        }, 2000);
    });
}

// Confirm & Submit Delete Slot
function confirmDeleteSlot(slotId, slotName) {
    if (confirm('Are you sure you want to delete the ad slot "' + slotName + '"?')) {
        const form = document.getElementById('delete-slot-form');
        if (form) {
            form.action = '<?= ADMIN_URL ?>/ads/delete/' + slotId;
            form.submit();
        }
    }
}
// Switch Active Slot Tab
function switchSlotTab(slotId) {
    document.querySelectorAll('.slot-pane').forEach(el => el.classList.add('hidden'));
    const targetPane = document.getElementById('slot-pane-' + slotId);
    if (targetPane) targetPane.classList.remove('hidden');

    document.querySelectorAll('.slot-tab-btn').forEach(btn => {
        btn.classList.remove('bg-[#0F172A]', 'text-white', 'shadow-md', 'border-transparent');
        btn.classList.add('bg-white', 'text-slate-700', 'border-slate-200', 'hover:border-slate-300');
        // also revert text inside button
        const desc = btn.querySelector('p');
        if (desc) {
            desc.classList.remove('text-slate-300');
            desc.classList.add('text-slate-500');
        }
    });

    const activeBtn = document.getElementById('slot-tab-btn-' + slotId);
    if (activeBtn) {
        activeBtn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200', 'hover:border-slate-300');
        activeBtn.classList.add('bg-[#0F172A]', 'text-white', 'shadow-md', 'border-transparent');
        const desc = activeBtn.querySelector('p');
        if (desc) {
            desc.classList.remove('text-slate-500');
            desc.classList.add('text-slate-300');
        }
    }

    if (history.pushState) {
        const newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?slot=' + slotId;
        window.history.pushState({path:newurl}, '', newurl);
    }
}

// Real-Time WYSIWYG Preview Update
function updatePreview(slotId) {
    const titleVal = document.getElementById('input-title-' + slotId)?.value || 'Featured Master Solo';
    const subtitleVal = document.getElementById('input-subtitle-' + slotId)?.value || 'Archival museum-quality watercolor prints on 300gsm cotton rag';
    const ctaVal = document.getElementById('input-cta-' + slotId)?.value || 'Explore Now';
    const imgUrlVal = document.getElementById('input-img-url-' + slotId)?.value;

    const titleEl = document.querySelector('[data-preview-title="' + slotId + '"]');
    if (titleEl) titleEl.textContent = titleVal;

    const subEl = document.querySelector('[data-preview-subtitle="' + slotId + '"]');
    if (subEl) subEl.textContent = subtitleVal;

    const ctaEl = document.querySelector('[data-preview-cta="' + slotId + '"]');
    if (ctaEl) {
        if (ctaEl.tagName === 'SPAN' && ctaEl.querySelector('span')) {
            ctaEl.querySelector('span').textContent = ctaVal;
        } else {
            ctaEl.innerHTML = ctaVal + ' &rarr;';
        }
    }

    if (imgUrlVal && imgUrlVal.trim() !== '') {
        const imgEl = document.querySelector('[data-preview-img="' + slotId + '"]');
        if (imgEl) imgEl.src = imgUrlVal;
        const thumbEl = document.getElementById('current-thumb-' + slotId);
        if (thumbEl) thumbEl.src = imgUrlVal;
    }
}

// Handle Local File Upload Preview
function handleFilePreview(slotId, input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgEl = document.querySelector('[data-preview-img="' + slotId + '"]');
            if (imgEl) imgEl.src = e.target.result;
            const thumbEl = document.getElementById('current-thumb-' + slotId);
            if (thumbEl) thumbEl.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Select Ad Type (Custom Poster vs Google AdSense)
function selectAdType(slotId, type) {
    const radioCustom = document.getElementById('radio-custom-' + slotId);
    const radioAdsense = document.getElementById('radio-adsense-' + slotId);
    const cardCustom = document.getElementById('card-type-custom-' + slotId);
    const cardAdsense = document.getElementById('card-type-adsense-' + slotId);
    const fieldsCustom = document.getElementById('custom-fields-' + slotId);
    const fieldsAdsense = document.getElementById('adsense-fields-' + slotId);
    const previewCustom = document.getElementById('preview-custom-' + slotId);
    const previewAdsense = document.getElementById('preview-adsense-' + slotId);

    if (type === 'custom') {
        if (radioCustom) radioCustom.checked = true;
        cardCustom?.classList.add('border-emerald-500', 'bg-emerald-50/30', 'shadow-xs');
        cardCustom?.classList.remove('border-slate-200', 'bg-white');
        cardAdsense?.classList.remove('border-emerald-500', 'bg-emerald-50/30', 'shadow-xs');
        cardAdsense?.classList.add('border-slate-200', 'bg-white');

        fieldsCustom?.classList.remove('hidden');
        fieldsAdsense?.classList.add('hidden');
        previewCustom?.classList.remove('hidden');
        previewAdsense?.classList.add('hidden');
    } else {
        if (radioAdsense) radioAdsense.checked = true;
        cardAdsense?.classList.add('border-emerald-500', 'bg-emerald-50/30', 'shadow-xs');
        cardAdsense?.classList.remove('border-slate-200', 'bg-white');
        cardCustom?.classList.remove('border-emerald-500', 'bg-emerald-50/30', 'shadow-xs');
        cardCustom?.classList.add('border-slate-200', 'bg-white');

        fieldsCustom?.classList.add('hidden');
        fieldsAdsense?.classList.remove('hidden');
        previewCustom?.classList.add('hidden');
        previewAdsense?.classList.remove('hidden');
    }
}

// Toggle Device Mode in Preview
function setPreviewDevice(slotId, mode) {
    const wrap = document.getElementById('preview-device-wrap-' + slotId);
    const btnDesktop = document.getElementById('btn-device-desktop-' + slotId);
    const btnMobile = document.getElementById('btn-device-mobile-' + slotId);

    if (mode === 'mobile') {
        wrap?.classList.add('max-w-[340px]', 'border-4', 'border-slate-800', 'rounded-[32px]', 'p-3', 'bg-slate-50', 'shadow-lg');
        btnMobile?.classList.add('bg-slate-900', 'text-white', 'shadow-xs');
        btnMobile?.classList.remove('text-slate-600');
        btnDesktop?.classList.remove('bg-slate-900', 'text-white', 'shadow-xs');
        btnDesktop?.classList.add('text-slate-600');
    } else {
        wrap?.classList.remove('max-w-[340px]', 'border-4', 'border-slate-800', 'rounded-[32px]', 'p-3', 'bg-slate-50', 'shadow-lg');
        btnDesktop?.classList.add('bg-slate-900', 'text-white', 'shadow-xs');
        btnDesktop?.classList.remove('text-slate-600');
        btnMobile?.classList.remove('bg-slate-900', 'text-white', 'shadow-xs');
        btnMobile?.classList.add('text-slate-600');
    }
}

// Preset URL Helper
function applyUrlPreset(slotId, url) {
    const input = document.getElementById('input-url-' + slotId);
    if (input) {
        input.value = url;
        input.focus();
    }
}

// Visual status feedback for toggle switch
function toggleLiveStatusBadge(slotId, isChecked) {
    const pane = document.getElementById('slot-pane-' + slotId);
    if (!pane) return;
    const statusText = pane.querySelector('.text-emerald-700, .text-slate-600');
    if (statusText) {
        if (isChecked) {
            statusText.textContent = 'Live on Storefront';
            statusText.className = 'text-emerald-700 font-bold';
        } else {
            statusText.textContent = 'Disabled (Hidden)';
            statusText.className = 'text-slate-600 font-bold';
        }
    }
}
</script>
