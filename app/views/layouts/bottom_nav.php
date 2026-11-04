<?php
/**
 * JayArt Sticky Mobile Bottom Navigation Bar
 * 1-Thumb Touch Accessibility (Auto-hidden on Desktop/Laptop)
 */

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$cartCount = Cart::getCount();
$isLoggedIn = Auth::check();
?>
<nav class="mobile-bottom-nav" aria-label="Mobile Bottom Navigation">
    
    <!-- 1. Home -->
    <a href="<?= SITE_URL ?>" class="bottom-nav-item <?= ($currentPath === '/' || $currentPath === '') ? 'active' : '' ?>">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        <span>Home</span>
    </a>

    <!-- 2. Shop / Gallery -->
    <a href="<?= SITE_URL ?>/shop" class="bottom-nav-item <?= str_contains($currentPath, '/shop') || str_contains($currentPath, '/product') ? 'active' : '' ?>">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        <span>Paintings</span>
    </a>

    <!-- 3. Cart with Badge -->
    <a href="<?= SITE_URL ?>/cart" class="bottom-nav-item <?= str_contains($currentPath, '/cart') ? 'active' : '' ?>">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        <span id="bottomNavCartBadge" class="bottom-nav-badge <?= ($cartCount > 0) ? '' : 'hidden' ?>">
            <?= $cartCount ?>
        </span>
        <span>Cart</span>
    </a>

    <!-- 4. Saved / Wishlist -->
    <a href="<?= $isLoggedIn ? SITE_URL . '/profile/wishlist' : SITE_URL . '/login' ?>" class="bottom-nav-item <?= str_contains($currentPath, '/wishlist') ? 'active' : '' ?>">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
        <span>Wishlist</span>
    </a>

    <!-- 5. User Account -->
    <a href="<?= $isLoggedIn ? SITE_URL . '/profile' : SITE_URL . '/login' ?>" class="bottom-nav-item <?= str_contains($currentPath, '/profile') || str_contains($currentPath, '/login') ? 'active' : '' ?>">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        <span>Account</span>
    </a>

</nav>

