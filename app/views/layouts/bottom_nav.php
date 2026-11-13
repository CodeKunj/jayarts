<?php
/**
 * JayArt Sticky Mobile Bottom Navigation Bar
 * 1-Thumb Touch Accessibility (Auto-hidden on Desktop/Laptop)
 */

// Comprehensive and robust active route resolution
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$parsedPath = parse_url($requestUri, PHP_URL_PATH) ?? '/';

// Strip subfolder if running in subdirectory
$scriptDir = dirname($_SERVER['SCRIPT_NAME'] ?? '');
$subfolder = rtrim(str_replace(['/admin', '\\admin'], '', $scriptDir), '/\\');
if (!empty($subfolder) && $subfolder !== '/' && str_starts_with($parsedPath, $subfolder)) {
    $parsedPath = substr($parsedPath, strlen($subfolder));
}

$cleanPath = '/' . trim($parsedPath, '/');
if ($cleanPath === '/index.php' || $cleanPath === '//' || $cleanPath === '') {
    $cleanPath = '/';
}

$activeTab = 'home';
if ($cleanPath === '/') {
    $activeTab = 'home';
} elseif (
    str_starts_with($cleanPath, '/wishlist') ||
    str_contains($cleanPath, '/wishlist')
) {
    $activeTab = 'wishlist';
} elseif (
    str_starts_with($cleanPath, '/cart') ||
    str_starts_with($cleanPath, '/checkout') ||
    str_starts_with($cleanPath, '/order-success') ||
    str_starts_with($cleanPath, '/order/success')
) {
    $activeTab = 'cart';
} elseif (
    str_starts_with($cleanPath, '/shop') ||
    str_starts_with($cleanPath, '/product') ||
    str_starts_with($cleanPath, '/category') ||
    str_starts_with($cleanPath, '/offers') ||
    str_starts_with($cleanPath, '/curator') ||
    str_starts_with($cleanPath, '/art')
) {
    $activeTab = 'paintings';
} elseif (
    str_starts_with($cleanPath, '/profile') ||
    str_starts_with($cleanPath, '/login') ||
    str_starts_with($cleanPath, '/register') ||
    str_starts_with($cleanPath, '/account') ||
    str_starts_with($cleanPath, '/orders') ||
    str_starts_with($cleanPath, '/track') ||
    str_starts_with($cleanPath, '/forgot-password') ||
    str_starts_with($cleanPath, '/reset-password')
) {
    $activeTab = 'account';
}

$cartCount = Cart::getCount();
$isLoggedIn = Auth::check();
$sessionWishlist = $_SESSION['wishlist'] ?? [];
$wishlistCount = is_array($sessionWishlist) ? count($sessionWishlist) : 0;
?>
<nav class="mobile-bottom-nav" aria-label="Mobile Bottom Navigation">
    
    <!-- 1. Home -->
    <a href="<?= SITE_URL ?>" class="bottom-nav-item <?= ($activeTab === 'home') ? 'active' : '' ?>" data-tab="home" title="JayArt Home">
        <div class="bottom-nav-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        </div>
        <span class="bottom-nav-label">Home</span>
    </a>

    <!-- 2. Shop / Gallery -->
    <a href="<?= SITE_URL ?>/shop" class="bottom-nav-item <?= ($activeTab === 'paintings') ? 'active' : '' ?>" data-tab="paintings" title="Original Paintings">
        <div class="bottom-nav-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <span class="bottom-nav-label">Paintings</span>
    </a>

    <!-- 3. Cart with Badge -->
    <a href="<?= SITE_URL ?>/cart" class="bottom-nav-item <?= ($activeTab === 'cart') ? 'active' : '' ?>" data-tab="cart" title="Shopping Bag">
        <div class="bottom-nav-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            <span id="bottomNavCartBadge" class="bottom-nav-badge <?= ($cartCount > 0) ? '' : 'hidden' ?>">
                <?= $cartCount ?>
            </span>
        </div>
        <span class="bottom-nav-label">Cart</span>
    </a>

    <!-- 4. Saved / Wishlist with Badge -->
    <a href="<?= SITE_URL ?>/profile/wishlist" class="bottom-nav-item <?= ($activeTab === 'wishlist') ? 'active' : '' ?>" data-tab="wishlist" title="Wishlist">
        <div class="bottom-nav-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            <span id="bottomNavWishlistBadge" class="bottom-nav-badge <?= ($wishlistCount > 0) ? '' : 'hidden' ?>">
                <?= $wishlistCount ?>
            </span>
        </div>
        <span class="bottom-nav-label">Wishlist</span>
    </a>

    <!-- 5. User Account -->
    <a href="<?= $isLoggedIn ? SITE_URL . '/profile' : SITE_URL . '/login' ?>" class="bottom-nav-item <?= ($activeTab === 'account') ? 'active' : '' ?>" data-tab="account" title="Account">
        <div class="bottom-nav-icon-wrap">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <span class="bottom-nav-label">Account</span>
    </a>

</nav>

<script>
(function() {
    function syncBottomNavActive() {
        try {
            var rawPath = (window.location.pathname || '/').toLowerCase();
            var tab = 'home';
            if (rawPath === '/' || rawPath.endsWith('/index.php') || rawPath === '') {
                tab = 'home';
            } else if (rawPath.indexOf('/wishlist') !== -1) {
                tab = 'wishlist';
            } else if (rawPath.indexOf('/cart') !== -1 || rawPath.indexOf('/checkout') !== -1) {
                tab = 'cart';
            } else if (rawPath.indexOf('/shop') !== -1 || rawPath.indexOf('/product') !== -1 || rawPath.indexOf('/offers') !== -1 || rawPath.indexOf('/category') !== -1) {
                tab = 'paintings';
            } else if (rawPath.indexOf('/profile') !== -1 || rawPath.indexOf('/login') !== -1 || rawPath.indexOf('/register') !== -1 || rawPath.indexOf('/track') !== -1 || rawPath.indexOf('/account') !== -1 || rawPath.indexOf('/orders') !== -1) {
                tab = 'account';
            }

            var items = document.querySelectorAll('.mobile-bottom-nav .bottom-nav-item');
            if (!items.length) return;

            items.forEach(function(item) {
                if (item.getAttribute('data-tab') === tab) {
                    item.classList.add('active');
                } else {
                    item.classList.remove('active');
                }
            });
        } catch (e) {}
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', syncBottomNavActive);
    } else {
        syncBottomNavActive();
    }
})();
</script>
