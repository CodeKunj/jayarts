<?php
/**
 * JayArt Admin Painting Add/Edit Form
 * Includes Dedicated Individual Product SEO Engine & Google Snippet Preview
 */

$actionUrl = $isEdit ? ADMIN_URL . '/products/update/' . $product['id'] : ADMIN_URL . '/products/store';
?>

<div class="max-w-5xl mx-auto">
    
    <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-6">
        <div>
            <h1 class="font-heading font-bold text-xl text-slate-800"><?= $isEdit ? 'Edit Artwork' : 'Add New Painting' ?></h1>
            <p class="text-xs text-slate-400">Complete artwork credentials, framing options, and individual SEO</p>
        </div>
        <a href="<?= ADMIN_URL ?>/products" class="text-xs font-semibold text-slate-500 hover:text-slate-800">
            &larr; Back to Catalog
        </a>
    </div>

    <form action="<?= $actionUrl ?>" method="POST" enctype="multipart/form-data" class="admin-tab-container space-y-6">
        
        <!-- Tab Navigation Buttons -->
        <div class="flex border-b border-slate-200 gap-2">
            <button type="button" class="admin-tab-btn active" data-tab="tabDetails">1. Artwork Details</button>
            <button type="button" class="admin-tab-btn" data-tab="tabFraming">2. Pricing &amp; Frames</button>
            <button type="button" class="admin-tab-btn" data-tab="tabSEO">3. Product SEO Engine</button>
        </div>

        <!-- TAB 1: Artwork Details -->
        <div id="tabDetails" class="admin-tab-content space-y-5 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Painting Title *</label>
                    <input type="text" name="title" id="artTitleInput" required value="<?= htmlspecialchars($product['title'] ?? '') ?>" placeholder="e.g. Symphony in Ochre & Gold" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Art Category / Medium Group</label>
                    <select name="category_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= (int)$cat['id'] ?>" <?= (isset($product['category_id']) && (int)$product['category_id'] === (int)$cat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Artist Name *</label>
                    <input type="text" name="artist_name" required value="<?= htmlspecialchars($product['artist_name'] ?? 'Independent Master Artist') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Specific Medium Formulation *</label>
                    <input type="text" name="medium" required value="<?= htmlspecialchars($product['medium'] ?? 'Oil on Belgian Canvas') ?>" placeholder="e.g. Oil on Linen, Acrylic Impasto" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Canvas Dimensions *</label>
                    <input type="text" name="dimensions" required value="<?= htmlspecialchars($product['dimensions'] ?? '36 x 48 inches (91 x 122 cm)') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Year of Creation</label>
                    <input type="text" name="year_created" value="<?= htmlspecialchars($product['year_created'] ?? date('Y')) ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-mono">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Status</label>
                    <select name="status" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                        <option value="active" <?= (isset($product['status']) && $product['status'] === 'active') ? 'selected' : '' ?>>Active (In Gallery)</option>
                        <option value="draft" <?= (isset($product['status']) && $product['status'] === 'draft') ? 'selected' : '' ?>>Draft</option>
                        <option value="sold_out" <?= (isset($product['status']) && $product['status'] === 'sold_out') ? 'selected' : '' ?>>Sold Out (Private Collection)</option>
                    </select>
                </div>
                <div class="flex items-center gap-4 pt-6">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                        <input type="checkbox" name="is_original" value="1" <?= (!isset($product) || !empty($product['is_original'])) ? 'checked' : '' ?> class="rounded text-[#0F172A]">
                        <span>Original 1-of-1 Piece</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-semibold text-slate-700">
                        <input type="checkbox" name="is_featured" value="1" <?= (!empty($product['is_featured'])) ? 'checked' : '' ?> class="rounded text-[#0F172A]">
                        <span>Feature on Homepage</span>
                    </label>
                </div>
            </div>

            <!-- Artwork Imagery & Multi-Photo Gallery Section -->
            <div class="pt-4 border-t border-slate-100 space-y-5">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Artwork Imagery &amp; Multi-Photo Gallery</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Upload the main cover artwork and multiple gallery perspectives (texture close-ups, wall angles, framing).</p>
                    </div>
                    <?php if (!empty($images)): ?>
                        <span class="text-[11px] font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200">
                            <?= count($images) ?> <?= count($images) === 1 ? 'Photo' : 'Photos' ?> in Gallery
                        </span>
                    <?php endif; ?>
                </div>

                <!-- 1. Primary Cover Painting Image -->
                <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="text-xs font-bold text-slate-800 flex items-center gap-2">
                            <span>Primary Cover Image</span>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">Main Catalog &amp; Feed Thumbnail</span>
                        </label>
                        <span class="text-[10px] text-slate-400">JPG, PNG, WebP (Max 15MB)</span>
                    </div>

                    <div class="flex items-center gap-4">
                        <?php 
                        $primaryImgUrl = null;
                        if (!empty($images)) {
                            foreach ($images as $im) {
                                if (!empty($im['is_primary'])) {
                                    $primaryImgUrl = Product::imageUrl($im['image_path']);
                                    break;
                                }
                            }
                            if (!$primaryImgUrl && !empty($images[0])) {
                                $primaryImgUrl = Product::imageUrl($images[0]['image_path']);
                            }
                        } elseif (!empty($product['primary_image'])) {
                            $primaryImgUrl = Product::imageUrl($product['primary_image']);
                        }
                        ?>
                        <!-- Preview Thumbnail -->
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-white border border-slate-200 overflow-hidden flex-shrink-0 flex items-center justify-center p-1 shadow-2xs">
                            <img id="primaryImagePreview" src="<?= $primaryImgUrl ?: 'https://images.unsplash.com/photo-1579783900882-c0d3dad7b119?w=300&q=80' ?>" alt="Cover Preview" class="w-full h-full object-cover rounded-lg">
                        </div>

                        <div class="flex-1 min-w-0">
                            <input type="file" name="artwork_image" id="artworkImageInput" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#18181B] file:text-white hover:file:bg-black cursor-pointer">
                            <p class="text-[10px] text-slate-400 mt-1">
                                <?= $isEdit ? 'Upload a new photo to replace the current primary cover image.' : 'This will be the hero photo shown in search feeds and collection cards.' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- 2. Additional Gallery Photos Uploader (Multiple Files) -->
                <div class="p-4 bg-slate-50 border border-dashed border-slate-300 rounded-2xl space-y-3">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div>
                            <label class="text-xs font-bold text-slate-800 flex items-center gap-2">
                                <span>Add Gallery Photos (Multiple Selection)</span>
                                <span class="text-[10px] font-bold text-blue-700 bg-blue-50 border border-blue-200 px-2 py-0.5 rounded-full">Multi-Angle &amp; Close-ups</span>
                            </label>
                            <p class="text-[11px] text-slate-500 mt-0.5">Select multiple photos at once (hold Ctrl/Cmd or drag &amp; drop). They will appear as interactive thumbnails on the artwork page.</p>
                        </div>
                    </div>

                    <div>
                        <input type="file" name="gallery_images[]" id="galleryImagesInput" multiple accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border file:border-slate-300 file:text-xs file:font-bold file:bg-white file:text-slate-700 hover:file:bg-slate-100 cursor-pointer">
                    </div>

                    <!-- Client-side Live Preview for newly selected gallery photos -->
                    <div id="newGalleryPreviewsContainer" class="hidden pt-2 border-t border-slate-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[11px] font-bold text-slate-700" id="newGalleryCountText">0 new photos ready to upload</span>
                            <button type="button" onclick="clearSelectedGalleryFiles()" class="text-[10px] text-red-600 hover:underline font-semibold">Clear Selected</button>
                        </div>
                        <div id="newGalleryPreviews" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2.5"></div>
                    </div>
                </div>

                <!-- 3. Existing Gallery Photos Manager (When Editing) -->
                <?php if ($isEdit && !empty($images)): ?>
                    <div class="space-y-3 pt-2">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-slate-800">Existing Gallery Images (<?= count($images) ?>)</h4>
                            <span class="text-[10px] text-slate-400">Click &ldquo;Make Cover&rdquo; to change primary or trash icon to remove</span>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3.5" id="existingGalleryGrid">
                            <?php foreach ($images as $img): 
                                $imgId = (int)$img['id'];
                                $isPrim = !empty($img['is_primary']);
                                $imgPath = Product::imageUrl($img['image_path']);
                            ?>
                                <div class="relative bg-white border <?= $isPrim ? 'border-emerald-500 ring-2 ring-emerald-100' : 'border-slate-200' ?> rounded-2xl p-2.5 shadow-2xs group flex flex-col justify-between" id="galleryCard-<?= $imgId ?>">
                                    <!-- Thumbnail -->
                                    <div class="aspect-square rounded-xl bg-slate-100 overflow-hidden mb-2 relative flex items-center justify-center border border-slate-100">
                                        <img src="<?= htmlspecialchars($imgPath) ?>" alt="Gallery Image" class="w-full h-full object-cover">
                                        
                                        <!-- Primary Badge -->
                                        <div class="primary-badge-indicator <?= $isPrim ? '' : 'hidden' ?> absolute top-1.5 left-1.5 px-2 py-0.5 rounded-full bg-emerald-600 text-white text-[9px] font-extrabold shadow-sm flex items-center gap-1">
                                            <span>★</span> <span>Cover</span>
                                        </div>

                                        <!-- Direct AJAX Delete Button -->
                                        <button type="button" 
                                                onclick="deleteGalleryImage(<?= $imgId ?>, <?= (int)$product['id'] ?>)" 
                                                class="absolute top-1.5 right-1.5 w-6 h-6 rounded-full bg-red-600/90 hover:bg-red-700 text-white flex items-center justify-center shadow-sm opacity-90 hover:opacity-100 transition-opacity" 
                                                title="Delete this photo">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>

                                    <!-- Bottom Action Controls -->
                                    <div class="flex items-center justify-between pt-1 text-[10px]">
                                        <div class="cover-action-wrap">
                                            <?php if ($isPrim): ?>
                                                <span class="text-emerald-700 font-bold flex items-center gap-1 primary-label">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                    <span>Main Cover</span>
                                                </span>
                                            <?php else: ?>
                                                <button type="button" 
                                                        onclick="setAsPrimaryImage(<?= $imgId ?>, <?= (int)$product['id'] ?>)" 
                                                        class="text-blue-600 hover:text-blue-800 font-bold hover:underline make-cover-btn">
                                                    Make Cover
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Hidden fallback radio for standard non-AJAX form submit -->
                                        <label class="text-[9px] text-slate-400 cursor-pointer flex items-center gap-1" title="Select cover for form save">
                                            <input type="radio" name="primary_image_id" value="<?= $imgId ?>" <?= $isPrim ? 'checked' : '' ?> class="text-emerald-600 focus:ring-emerald-500 w-3 h-3">
                                            <span>Cover</span>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Artwork Story &amp; Provenance Description</label>
                <textarea name="description" id="artDescInput" rows="5" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white leading-relaxed" placeholder="Detailed story of the painting, artistic inspiration, and textural description..."><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
            </div>

        </div>

        <!-- TAB 2: Pricing, Stock & Framing -->
        <div id="tabFraming" class="admin-tab-content hidden space-y-5 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Base Price (<?= CURRENCY_SYMBOL ?>) *</label>
                    <input type="number" step="0.01" name="price" required value="<?= htmlspecialchars($product['price'] ?? '') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-sans font-bold">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Discounted Sale Price (Optional)</label>
                    <input type="number" step="0.01" name="discount_price" value="<?= htmlspecialchars($product['discount_price'] ?? '') ?>" placeholder="Leave blank if regular price" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-sans">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Available Quantity / Stock *</label>
                    <input type="number" name="stock_quantity" required value="<?= htmlspecialchars($product['stock_quantity'] ?? '1') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-mono">
                    <span class="text-[10px] text-slate-400">For 1-of-1 original paintings, set to 1.</span>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-slate-700 block mb-1">Available Framing Options (Comma Separated)</label>
                <input type="text" name="frame_options" value="<?= htmlspecialchars($product['frame_options'] ?? 'Rolled Canvas,Stretched Canvas,Floating Matte Black Frame,Floating Teak Wood Frame') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                <span class="text-[10px] text-slate-400">Customers can choose their frame on the product page. Floating frames automatically calculate surcharge.</span>
            </div>

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-slate-800">
                    <input type="checkbox" name="certificate_authenticity" value="1" <?= (!isset($product) || !empty($product['certificate_authenticity'])) ? 'checked' : '' ?> class="rounded text-[#0F172A]">
                    <span>Include Physical Certificate of Authenticity with Wax Provenance Seal</span>
                </label>
            </div>

        </div>

        <!-- TAB 3: Dedicated Per-Product SEO Panel -->
        <div id="tabSEO" class="admin-tab-content hidden space-y-6 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            
            <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl text-xs text-blue-900 leading-relaxed">
                <strong>Individual Product SEO Optimization:</strong> This artwork will automatically generate a Google-compliant <code>schema.org/Product</code> JSON-LD microdata tag with live price, availability, artist credentials, and thumbnail previews for Google Shopping and search snippets.
            </div>

            <!-- Live Google Mobile Snippet Preview -->
            <div>
                <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block mb-2">Live Google Search Preview</span>
                <div class="bg-white border border-slate-300 rounded-xl p-4 max-w-lg shadow-sm">
                    <div class="text-[11px] text-slate-600 truncate flex items-center gap-1.5 mb-1">
                        <span class="w-4 h-4 rounded-full bg-[#0F172A] text-white flex items-center justify-center text-[9px] font-bold">J</span>
                        <span><?= SITE_URL ?> &rsaquo; product &rsaquo; <span id="previewSlug"><?= htmlspecialchars($product['slug'] ?? 'painting-title') ?></span></span>
                    </div>
                    <div id="previewTitle" class="text-sm font-medium text-blue-800 hover:underline cursor-pointer line-clamp-1">
                        <?= htmlspecialchars($seo['meta_title'] ?? ($product['title'] ?? 'Painting Title') . ' — Original Artwork by Artist | JayArt') ?>
                    </div>
                    <div id="previewDesc" class="text-xs text-slate-600 line-clamp-2 mt-1 leading-normal">
                        <?= htmlspecialchars($seo['meta_description'] ?? 'Buy original landscape oil painting on Belgian canvas with signed certificate of authenticity and insured shipping across India.') ?>
                    </div>
                </div>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-200">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-700">Custom Meta Title</label>
                        <span id="metaTitleCount" class="text-[10px] text-slate-400">0 / 60 chars recommended</span>
                    </div>
                    <input type="text" name="meta_title" id="metaTitleInput" value="<?= htmlspecialchars($seo['meta_title'] ?? '') ?>" placeholder="e.g. Echoes of Twilight Valley — Original Oil Painting by Rajeshwar Sharma | JayArt" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-xs font-bold text-slate-700">Custom Meta Description</label>
                        <span id="metaDescCount" class="text-[10px] text-slate-400">0 / 160 chars recommended</span>
                    </div>
                    <textarea name="meta_description" id="metaDescInput" rows="3" placeholder="Compelling 150-160 character description for search engines and social shares..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white leading-relaxed"><?= htmlspecialchars($seo['meta_description'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Meta Keywords (Comma Separated)</label>
                    <input type="text" name="keywords" value="<?= htmlspecialchars($seo['keywords'] ?? '') ?>" placeholder="oil painting, landscape art, buy paintings online india, original art" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white">
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-700 block mb-1">Canonical URL (Leave blank to use default)</label>
                    <input type="text" name="canonical_url" value="<?= htmlspecialchars($seo['canonical_url'] ?? '') ?>" placeholder="<?= SITE_URL ?>/product/<?= htmlspecialchars($product['slug'] ?? 'artwork-slug') ?>" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white font-mono">
                </div>

                <!-- Custom OG Social Share Image -->
                <?php
                $initialProductOg = !empty($seo['og_image']) 
                    ? Product::imageUrl($seo['og_image']) 
                    : (!empty($product['primary_image']) ? Product::imageUrl($product['primary_image']) : ASSETS_URL . '/images/og-default.jpg');
                ?>
                <div class="pt-4 border-t border-slate-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-xs font-bold text-slate-800 block mb-0.5">
                                OpenGraph Social Share Image (WhatsApp / Facebook Preview)
                            </label>
                            <span class="text-[10px] text-slate-400 block">
                                Custom 1200 &times; 630 px promotional banner when this artwork is shared on social media. (Leave blank to use the painting's primary photo automatically).
                            </span>
                        </div>
                        <span class="text-[9px] font-mono px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold uppercase">
                            OG Ready
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-start bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <!-- Preview Thumbnail -->
                        <div class="sm:col-span-4 relative group overflow-hidden rounded-xl border border-slate-300 bg-slate-900 aspect-video shadow-2xs">
                            <img id="productOgPreviewImg" src="<?= htmlspecialchars($initialProductOg) ?>" alt="OG Preview" class="w-full h-full object-cover">
                            <div class="absolute bottom-1 left-1 bg-black/75 backdrop-blur-xs px-1.5 py-0.5 rounded text-[8px] font-mono font-bold text-white">
                                1200 &times; 630 OG
                            </div>
                        </div>

                        <!-- Controls -->
                        <div class="sm:col-span-8 space-y-2.5">
                            <div>
                                <label class="text-[11px] font-semibold text-slate-700 block mb-1">Upload Custom OG Image File</label>
                                <input type="file" name="product_og_image_file" id="productOgFileInput" accept="image/png,image/jpeg,image/webp,image/svg+xml" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-white file:border file:border-slate-300 file:text-slate-700 hover:file:bg-slate-100 cursor-pointer">
                            </div>

                            <div>
                                <label class="text-[11px] font-semibold text-slate-700 block mb-1">Or Enter Custom Image URL / Server Path</label>
                                <input type="text" name="og_image" id="productOgImageInput" value="<?= htmlspecialchars($seo['og_image'] ?? '') ?>" placeholder="e.g. uploads/products/og/custom.jpg or https://..." class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-mono focus:bg-white">
                            </div>

                            <?php if (!empty($seo['og_image'])): ?>
                                <div class="flex items-center gap-2 pt-1 text-[11px]">
                                    <span class="text-slate-400">Current file:</span>
                                    <a href="<?= htmlspecialchars(Product::imageUrl($seo['og_image'])) ?>" target="_blank" class="text-blue-600 hover:underline font-mono truncate max-w-xs font-medium">
                                        <?= htmlspecialchars($seo['og_image']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-200">
            <a href="<?= ADMIN_URL ?>/products" class="px-5 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-900">
                Cancel
            </a>
            <button type="submit" class="px-7 py-3 bg-[#0F172A] hover:bg-[#020617] text-white rounded-xl text-xs font-bold tracking-wider transition-colors shadow-md">
                <?= $isEdit ? 'Save Artwork & SEO Changes' : 'Publish Painting to Catalog' ?> &rarr;
            </button>
        </div>

    </form>
</div>

<!-- SEO Live Preview Synchronizer Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const titleInput = document.getElementById('metaTitleInput');
    const artTitle = document.getElementById('artTitleInput');
    const descInput = document.getElementById('metaDescInput');
    const artDesc = document.getElementById('artDescInput');

    const previewTitle = document.getElementById('previewTitle');
    const previewDesc = document.getElementById('previewDesc');
    const metaTitleCount = document.getElementById('metaTitleCount');
    const metaDescCount = document.getElementById('metaDescCount');

    function updatePreview() {
        const titleVal = titleInput.value.trim() || (artTitle.value.trim() ? artTitle.value.trim() + ' | JayArt Gallery' : 'Painting Title | JayArt');
        const descVal = descInput.value.trim() || (artDesc.value.trim() ? artDesc.value.trim().substring(0, 155) : 'Buy original certified artwork delivered safely to your doorstep.');

        previewTitle.textContent = titleVal;
        previewDesc.textContent = descVal;

        metaTitleCount.textContent = `${titleInput.value.length} / 60 chars`;
        metaDescCount.textContent = `${descInput.value.length} / 160 chars`;
    }

    [titleInput, artTitle, descInput, artDesc].forEach(el => {
        el?.addEventListener('input', updatePreview);
    });

    updatePreview();

    // OG Image Live Preview Sync
    const ogFileInput = document.getElementById('productOgFileInput');
    const ogImageInput = document.getElementById('productOgImageInput');
    const ogPreviewImg = document.getElementById('productOgPreviewImg');

    if (ogFileInput && ogPreviewImg) {
        ogFileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    ogPreviewImg.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    if (ogImageInput && ogPreviewImg) {
        ogImageInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                const val = this.value.trim();
                ogPreviewImg.src = (val.startsWith('http://') || val.startsWith('https://'))
                    ? val
                    : '<?= SITE_URL ?>/' + val.replace(/^\/+/, '');
            }
        });
    }

    // 1. Primary Cover Live Preview
    const artworkInput = document.getElementById('artworkImageInput');
    const primaryPreview = document.getElementById('primaryImagePreview');
    if (artworkInput && primaryPreview) {
        artworkInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    primaryPreview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // 2. Multi-File Gallery Photos Live Preview
    const galleryInput = document.getElementById('galleryImagesInput');
    const galleryPrevContainer = document.getElementById('newGalleryPreviewsContainer');
    const galleryPrevGrid = document.getElementById('newGalleryPreviews');
    const galleryCountText = document.getElementById('newGalleryCountText');

    if (galleryInput && galleryPrevContainer && galleryPrevGrid) {
        galleryInput.addEventListener('change', function() {
            galleryPrevGrid.innerHTML = '';
            const files = Array.from(this.files || []);
            if (files.length > 0) {
                galleryPrevContainer.classList.remove('hidden');
                if (galleryCountText) {
                    galleryCountText.textContent = `${files.length} new ${files.length === 1 ? 'photo' : 'photos'} ready to upload`;
                }

                files.forEach((file) => {
                    if (!file.type.startsWith('image/')) return;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const card = document.createElement('div');
                        card.className = 'relative bg-white border border-slate-200 rounded-xl p-1.5 shadow-2xs text-[9px] text-slate-600';
                        card.innerHTML = `
                            <div class="aspect-square rounded-lg bg-slate-100 overflow-hidden mb-1 flex items-center justify-center">
                                <img src="${e.target.result}" alt="Preview" class="w-full h-full object-cover">
                            </div>
                            <p class="truncate font-mono">${file.name}</p>
                            <span class="text-slate-400 font-mono">${(file.size / 1024).toFixed(0)} KB</span>
                        `;
                        galleryPrevGrid.appendChild(card);
                    };
                    reader.readAsDataURL(file);
                });
            } else {
                galleryPrevContainer.classList.add('hidden');
            }
        });
    }

    window.clearSelectedGalleryFiles = function() {
        if (galleryInput) galleryInput.value = '';
        if (galleryPrevGrid) galleryPrevGrid.innerHTML = '';
        if (galleryPrevContainer) galleryPrevContainer.classList.add('hidden');
    };

    // 3. AJAX Delete Existing Gallery Image
    window.deleteGalleryImage = function(imgId, productId) {
        if (!confirm('Are you sure you want to delete this gallery photo?')) return;
        
        const card = document.getElementById('galleryCard-' + imgId);
        if (card) card.style.opacity = '0.4';

        fetch('<?= ADMIN_URL ?>/products/delete-image/' + imgId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                if (card) {
                    card.style.transition = 'all 0.3s ease';
                    card.style.transform = 'scale(0.8)';
                    card.style.opacity = '0';
                    setTimeout(() => card.remove(), 300);
                }
            } else {
                alert(data.message || 'Could not delete image.');
                if (card) card.style.opacity = '1';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Failed to delete image.');
            if (card) card.style.opacity = '1';
        });
    };

    // 4. AJAX Set Existing Gallery Image As Primary Cover
    window.setAsPrimaryImage = function(imgId, productId) {
        const card = document.getElementById('galleryCard-' + imgId);
        if (card) card.style.opacity = '0.5';

        fetch('<?= ADMIN_URL ?>/products/set-primary-image/' + imgId, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert(data.message || 'Could not set cover image.');
                if (card) card.style.opacity = '1';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Failed to set cover image.');
            if (card) card.style.opacity = '1';
        });
    };
});
</script>

