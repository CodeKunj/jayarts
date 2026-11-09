<?php
/**
 * JayArt Modern Product Detail View
 * Shopcart-Inspired High-Converting E-Commerce Detail Design
 */

$isDiscounted = !empty($product['discount_price']) && (float)$product['discount_price'] < (float)$product['price'];
$basePrice = $isDiscounted ? (float)$product['discount_price'] : (float)$product['price'];
$frames = array_map('trim', explode(',', $product['frame_options'] ?: 'Rolled Canvas,Stretched Canvas,Floating Matte Black Frame,Antique Gold Museum Frame'));
$primaryImg = Product::imageUrl($product['primary_image'] ?? null);
?>

<!-- Breadcrumbs (Exact Shopcart style) -->
<div class="bg-white border-b border-stone-200/80 py-3.5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex text-xs text-stone-400 font-medium" aria-label="Breadcrumb">
            <a href="<?= SITE_URL ?>" class="hover:text-black">Home</a>
            <span class="mx-2 text-stone-300">/</span>
            <a href="<?= SITE_URL ?>/shop" class="hover:text-black">Paintings</a>
            <?php if (!empty($product['category_name'])): ?>
                <span class="mx-2 text-stone-300">/</span>
                <a href="<?= SITE_URL ?>/shop?category=<?= htmlspecialchars($product['category_slug'] ?? '') ?>" class="hover:text-black"><?= htmlspecialchars($product['category_name']) ?></a>
            <?php endif; ?>
            <span class="mx-2 text-stone-300">/</span>
            <span class="text-[#18181B] font-semibold truncate"><?= htmlspecialchars($product['title']) ?></span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14">
        
        <!-- Left Column: Big Product Image & Thumbnails (Shopcart Style) -->
        <div class="lg:col-span-6 space-y-4">
            <!-- Main Crisp Image Container -->
            <div class="bg-stone-100 rounded-3xl p-6 sm:p-10 aspect-square flex items-center justify-center relative overflow-hidden border border-stone-200/80">
                <?php $isWish = in_array((int)$product['id'], $_SESSION['wishlist'] ?? [], true); ?>
                <button onclick="toggleWishlist(<?= $product['id'] ?>, this)" class="wishlist-heart-btn top-4 right-4 <?= $isWish ? 'active text-red-500' : '' ?>" title="Add to Wishlist">
                    <svg class="w-5 h-5 <?= $isWish ? 'text-red-500' : '' ?>" fill="<?= $isWish ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                </button>

                <img id="mainProductImage" src="<?= htmlspecialchars($primaryImg) ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="max-h-full max-w-full object-contain transition-transform duration-300 hover:scale-105">
            </div>

            <!-- Thumbnail Carousel (Shown when artwork has multiple gallery photos) -->
            <?php 
            $allImages = !empty($product['images']) ? $product['images'] : [];
            if (empty($allImages) && !empty($primaryImg)) {
                $allImages = [['image_path' => $primaryImg, 'is_primary' => 1]];
            }
            ?>
            <?php if (count($allImages) > 1): ?>
                <div class="flex items-center gap-2.5 overflow-x-auto pb-2 scrollbar-none" id="productThumbnailStrip">
                    <?php foreach ($allImages as $idx => $img): 
                        $imgPath = Product::imageUrl($img['image_path'] ?? null);
                        $isFirst = ($idx === 0);
                    ?>
                        <button type="button" 
                                onclick="switchProductMainImage('<?= htmlspecialchars($imgPath) ?>', this)" 
                                class="thumb-btn w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white p-1.5 border-2 <?= $isFirst ? 'border-[#18181B] ring-2 ring-black/10' : 'border-stone-200 hover:border-stone-400' ?> flex items-center justify-center flex-shrink-0 transition-all cursor-pointer shadow-2xs">
                            <img src="<?= htmlspecialchars($imgPath) ?>" class="max-h-full max-w-full object-contain rounded-xl" alt="View <?= $idx + 1 ?>">
                        </button>
                    <?php endforeach; ?>
                </div>
                <script>
                function switchProductMainImage(src, btn) {
                    const mainImg = document.getElementById('mainProductImage');
                    if (mainImg) mainImg.src = src;
                    document.querySelectorAll('#productThumbnailStrip .thumb-btn').forEach(b => {
                        b.classList.remove('border-[#18181B]', 'ring-2', 'ring-black/10');
                        b.classList.add('border-stone-200');
                    });
                    if (btn) {
                        btn.classList.add('border-[#18181B]', 'ring-2', 'ring-black/10');
                        btn.classList.remove('border-stone-200');
                    }
                }
                </script>
            <?php endif; ?>
        </div>

        <!-- Right Column: Product Info & Actions (Shopcart Style) -->
        <div class="lg:col-span-6 flex flex-col justify-between">
            <div class="space-y-4">
                
                <!-- Product Title -->
                <div>
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#18181B] tracking-tight">
                        <?= htmlspecialchars($product['title']) ?>
                    </h1>
                    <p class="text-xs sm:text-sm text-stone-500 mt-1">
                        By <?= htmlspecialchars($product['artist_name'] ?? 'Celebrated Master Artist') ?> &bull; <?= htmlspecialchars($product['medium']) ?> on Canvas
                    </p>
                </div>

                <!-- Star Rating -->
                <div class="flex items-center gap-2">
                    <div class="text-amber-400 text-sm">
                        <span>★★★★★</span>
                    </div>
                    <span class="text-xs font-bold text-stone-600">(<?= 121 + $product['id'] ?> Reviews)</span>
                </div>

                <hr class="border-stone-100">

                <!-- Price & Installment Note -->
                <div>
                    <div class="flex items-baseline gap-3">
                        <span id="displayPrice" class="text-2xl sm:text-3xl font-extrabold text-[#18181B]">
                            <?= Validator::formatPrice($basePrice) ?>
                        </span>
                        <?php if ($isDiscounted): ?>
                            <span class="text-base text-stone-400 line-through">
                                <?= Validator::formatPrice((float)$product['price']) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-stone-500 mt-1 font-medium">
                        Suggested payments with 0% interest monthly financing options available at checkout
                    </p>
                </div>

                <hr class="border-stone-100">

                <!-- Choose a Color / Framing Swatches -->
                <div>
                    <label class="block text-xs font-bold text-[#18181B] uppercase tracking-wider mb-2">
                        Choose a Frame / Finish
                    </label>
                    <div class="flex items-center gap-3">
                        <!-- Swatch 1: Matte Black -->
                        <label class="cursor-pointer group flex flex-col items-center gap-1">
                            <input type="radio" name="frame_choice" value="Floating Matte Black" checked onchange="updateFrame(this.value, 2500)" class="sr-only peer">
                            <span class="w-8 h-8 rounded-full bg-[#18181B] border-2 border-white ring-2 ring-transparent peer-checked:ring-[#18181B] flex items-center justify-center transition-all"></span>
                            <span class="text-[11px] text-stone-600 peer-checked:font-bold peer-checked:text-[#18181B]">Black</span>
                        </label>
                        <!-- Swatch 2: Antique Gold -->
                        <label class="cursor-pointer group flex flex-col items-center gap-1">
                            <input type="radio" name="frame_choice" value="Antique Gold Museum Frame" onchange="updateFrame(this.value, 4500)" class="sr-only peer">
                            <span class="w-8 h-8 rounded-full bg-[#C59A58] border-2 border-white ring-2 ring-transparent peer-checked:ring-[#18181B] flex items-center justify-center transition-all"></span>
                            <span class="text-[11px] text-stone-600 peer-checked:font-bold peer-checked:text-[#18181B]">Gold</span>
                        </label>
                        <!-- Swatch 3: Natural Oak Wood -->
                        <label class="cursor-pointer group flex flex-col items-center gap-1">
                            <input type="radio" name="frame_choice" value="Natural Oak Wood" onchange="updateFrame(this.value, 3000)" class="sr-only peer">
                            <span class="w-8 h-8 rounded-full bg-[#A07855] border-2 border-white ring-2 ring-transparent peer-checked:ring-[#18181B] flex items-center justify-center transition-all"></span>
                            <span class="text-[11px] text-stone-600 peer-checked:font-bold peer-checked:text-[#18181B]">Oak</span>
                        </label>
                        <!-- Swatch 4: Gallery White -->
                        <label class="cursor-pointer group flex flex-col items-center gap-1">
                            <input type="radio" name="frame_choice" value="Gallery White Frame" onchange="updateFrame(this.value, 2500)" class="sr-only peer">
                            <span class="w-8 h-8 rounded-full bg-[#F3F4F6] border-2 border-stone-300 ring-2 ring-transparent peer-checked:ring-[#18181B] flex items-center justify-center transition-all"></span>
                            <span class="text-[11px] text-stone-600 peer-checked:font-bold peer-checked:text-[#18181B]">White</span>
                        </label>
                        <!-- Swatch 5: Rolled Canvas (No Frame) -->
                        <label class="cursor-pointer group flex flex-col items-center gap-1">
                            <input type="radio" name="frame_choice" value="Rolled Canvas (Unframed)" onchange="updateFrame(this.value, 0)" class="sr-only peer">
                            <span class="w-8 h-8 rounded-full bg-[#E5E7EB] border-2 border-white ring-2 ring-transparent peer-checked:ring-[#18181B] flex items-center justify-center transition-all text-xs font-bold text-stone-500">None</span>
                            <span class="text-[11px] text-stone-600 peer-checked:font-bold peer-checked:text-[#18181B]">Rolled</span>
                        </label>
                    </div>
                </div>

                <!-- Quantity Pill & Scarcity Note (Exact Shopcart design) -->
                <div class="flex flex-wrap items-center gap-3 sm:gap-6 pt-2">
                    <!-- Pill Quantity Stepper -->
                    <div class="inline-flex items-center bg-stone-100 rounded-full px-4 py-2 border border-stone-200">
                        <button type="button" onclick="decrementQty()" class="text-stone-600 hover:text-black font-extrabold text-base px-2 cursor-pointer">-</button>
                        <input id="productQty" type="number" value="1" min="1" max="10" readonly class="w-8 text-center bg-transparent border-none text-xs font-extrabold text-[#18181B] focus:outline-none">
                        <button type="button" onclick="incrementQty()" class="text-stone-600 hover:text-black font-extrabold text-base px-2 cursor-pointer">+</button>
                    </div>

                    <!-- Scarcity Badge -->
                    <p class="text-xs text-amber-700 font-semibold flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                        <span>Only <?= max(1, (int)$product['stock_quantity']) ?> Item Left! Don't miss it</span>
                    </p>
                </div>

                <!-- Action Buttons: Buy Now & Add to Cart (Exact Shopcart style) -->
                <div class="flex flex-col sm:flex-row items-center gap-3 pt-4">
                    <button id="buyNowBtn" onclick="submitBuyNow(<?= $product['id'] ?>)" class="w-full sm:w-1/2 py-3.5 px-8 bg-[#18181B] hover:bg-black text-white rounded-full font-bold text-xs uppercase tracking-wider transition-all shadow-sm text-center cursor-pointer">
                        Buy Now
                    </button>
                    <button id="addToCartBtn" onclick="submitAddToCart(<?= $product['id'] ?>)" class="w-full sm:w-1/2 py-3 px-8 border-2 border-[#18181B] hover:bg-[#18181B] hover:text-white text-[#18181B] rounded-full font-bold text-xs uppercase tracking-wider transition-all text-center cursor-pointer">
                        Add to Cart
                    </button>
                </div>

                <!-- Delivery & Return Policy Box (Exact Shopcart design) -->
                <div class="border border-stone-200 rounded-2xl p-4 space-y-3.5 mt-6 bg-white">
                    <div class="flex items-start gap-3">
                        <div class="text-[#18181B] mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#18181B]">Free Insured Delivery</p>
                            <p class="text-[11px] text-stone-500 mt-0.5">Enter your postal code for delivery date and availability</p>
                        </div>
                    </div>

                    <hr class="border-stone-100">

                    <div class="flex items-start gap-3">
                        <div class="text-[#18181B] mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#18181B]">Return Guarantee</p>
                            <p class="text-[11px] text-stone-500 mt-0.5">Free 14 Days Authenticity & Inspection Returns. <a href="<?= SITE_URL ?>/contact" class="underline font-bold text-[#18181B]">Details</a></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Description & Provenance Details -->
    <div class="mt-16 pt-8 border-t border-gray-100">
        <div class="max-w-3xl">
            <h2 class="text-xl font-bold text-[#111827] mb-3">About This Artwork</h2>
            <div class="text-xs sm:text-sm text-gray-600 leading-relaxed font-sans space-y-3">
                <?= nl2br(htmlspecialchars($product['description'])) ?>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-gray-100 text-xs">
                <div>
                    <span class="text-gray-400 font-bold uppercase text-[10px]">Medium</span>
                    <p class="font-bold text-gray-900 mt-0.5"><?= htmlspecialchars($product['medium']) ?></p>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase text-[10px]">Dimensions</span>
                    <p class="font-bold text-gray-900 mt-0.5"><?= htmlspecialchars($product['dimensions']) ?></p>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase text-[10px]">Authenticity</span>
                    <p class="font-bold text-gray-900 mt-0.5">Signed Certificate Included</p>
                </div>
                <div>
                    <span class="text-gray-400 font-bold uppercase text-[10px]">Crating</span>
                    <p class="font-bold text-gray-900 mt-0.5">Custom Archival Box</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentBasePrice = <?= (float)$basePrice ?>;
let currentFramePrice = 2500;
let selectedFrameName = 'Floating Matte Black';

function updateFrame(name, extraPrice) {
    selectedFrameName = name;
    currentFramePrice = extraPrice;
    const total = currentBasePrice + currentFramePrice;
    document.getElementById('displayPrice').innerText = '₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2});
}

function incrementQty() {
    const input = document.getElementById('productQty');
    let val = parseInt(input.value) || 1;
    if (val < 10) input.value = val + 1;
}

function decrementQty() {
    const input = document.getElementById('productQty');
    let val = parseInt(input.value) || 1;
    if (val > 1) input.value = val - 1;
}

function submitAddToCart(productId) {
    const qty = parseInt(document.getElementById('productQty').value) || 1;
    quickAddToCart(productId, qty, selectedFrameName);
}

async function submitBuyNow(productId) {
    const qty = parseInt(document.getElementById('productQty').value) || 1;
    await quickBuyNow(productId, qty, selectedFrameName);
}
</script>
