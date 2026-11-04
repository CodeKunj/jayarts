<?php
/**
 * JayArt Painting E-Commerce Platform
 * Root Front Controller (cPanel Compatible)
 */

declare(strict_types=1);

// Support for PHP Built-in Server (php -S localhost:8000 index.php)
if (php_sapi_name() === 'cli-server') {
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $filePath = __DIR__ . $uriPath;
    if ($uriPath !== '/' && file_exists($filePath) && !is_dir($filePath)) {
        return false; // Let PHP built-in server stream the static file directly
    }
}

// Bootstrap Core Application
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__);
}
require_once __DIR__ . '/core/App.php';
App::bootstrap();

// Forward /admin routes
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
if ($requestPath === '/admin' || str_starts_with((string)$requestPath, '/admin/')) {
    require_once __DIR__ . '/admin/index.php';
    exit;
}

// Register Public Routes
Router::get('/', [HomeController::class, 'index']);
Router::get('/shop', [ShopController::class, 'index']);
Router::get('/product/{slug}', [ProductController::class, 'view']);
Router::get('/offers', [OfferController::class, 'index']);

// Cart Routes (AJAX and Standard)
Router::get('/cart', [CartController::class, 'index']);
Router::post('/cart/add', [CartController::class, 'add']);
Router::post('/cart/update', [CartController::class, 'update']);
Router::post('/cart/remove', [CartController::class, 'remove']);
Router::post('/cart/apply-coupon', [CartController::class, 'applyCoupon']);

// Checkout & Multi-Payment Routes
Router::get('/checkout', [CheckoutController::class, 'index']);
Router::post('/checkout/process', [CheckoutController::class, 'process']);
Router::get('/checkout/success/{order_number}', [CheckoutController::class, 'success']);
Router::post('/checkout/razorpay-verify', [CheckoutController::class, 'razorpayVerify']);

// Order Tracking
Router::get('/track', [TrackingController::class, 'index']);
Router::any('/track/{order_number}', [TrackingController::class, 'search']);

// Art Magazine / Blog
Router::get('/blog', [BlogController::class, 'index']);
Router::get('/blog/{slug}', [BlogController::class, 'post']);

// Customer Care, Contact & Instant Chatbox
Router::get('/contact', [SupportController::class, 'contact']);
Router::post('/contact/submit', [SupportController::class, 'submitContact']);
Router::post('/chat/send', [SupportController::class, 'chatMessage']);

// Customer Authentication
Router::any('/login', [AuthController::class, 'login']);
Router::any('/register', [AuthController::class, 'register']);
Router::get('/logout', [AuthController::class, 'logout']);

// Customer Account & Profile
Router::get('/profile', [ProfileController::class, 'index']);
Router::post('/profile/update', [ProfileController::class, 'update']);
Router::get('/profile/orders', [ProfileController::class, 'orders']);
Router::get('/profile/order/{id}', [ProfileController::class, 'orderDetail']);
Router::get('/profile/invoice/{id}', [ProfileController::class, 'downloadInvoice']);
Router::get('/profile/wishlist', [ProfileController::class, 'wishlist']);
Router::post('/profile/wishlist/toggle', [ProfileController::class, 'toggleWishlist']);
Router::get('/profile/tickets', [ProfileController::class, 'tickets']);
Router::post('/profile/tickets/create', [ProfileController::class, 'createTicket']);
Router::get('/profile/ticket/{id}', [ProfileController::class, 'viewTicket']);
Router::post('/profile/ticket/{id}/reply', [ProfileController::class, 'replyTicket']);

// Dynamic XML Sitemap for Google & Bing
Router::get('/sitemap.xml', [SitemapController::class, 'index']);

// Dynamic Robots.txt with Sitemap link
Router::get('/robots.txt', [SitemapController::class, 'robots']);

// Dispatch the Request
Router::dispatch();

