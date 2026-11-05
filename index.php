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

// Start output buffering early to prevent premature header output
if (ob_get_level() === 0) {
    ob_start();
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

// Multi-Currency Switcher Routes
Router::get('/currency/switch/{code}', [CurrencyController::class, 'switchGet']);
Router::post('/currency/switch', [CurrencyController::class, 'switch']);

// Cart Routes (AJAX and Standard)
Router::get('/cart', [CartController::class, 'index']);
Router::post('/cart/add', [CartController::class, 'add']);
Router::post('/cart/update', [CartController::class, 'update']);
Router::post('/cart/remove', [CartController::class, 'remove']);
Router::post('/cart/apply-coupon', [CartController::class, 'applyCoupon']);
Router::post('/cart/remove-coupon', [CartController::class, 'removeCoupon']);

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

// Safe Database Migration & Self-Healing Verification Endpoint
Router::get('/db-setup', function() {
    $db = Database::connect();
    if (!$db) {
        die('<div style="font-family:sans-serif;max-width:600px;margin:50px auto;padding:24px;border:1px solid #f87171;border-radius:12px;background:#fef2f2;color:#991b1b;">
            <h2 style="margin-top:0;">Database Connection Error</h2>
            <p>Could not connect to MySQL server using credentials from <code>.env</code>.</p>
            <p>Please check <code>DB_HOST</code>, <code>DB_NAME</code>, <code>DB_USER</code>, and <code>DB_PASS</code> in your <code>.env</code> file.</p>
        </div>');
    }

    Database::autoMigrateIfEmpty($db, true);

    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $tableCount = count($tables);

    echo '<div style="font-family:sans-serif;max-width:650px;margin:50px auto;padding:30px;border:1px solid #e2e8f0;border-radius:16px;background:#ffffff;box-shadow:0 10px 25px -5px rgba(0,0,0,0.05);color:#0f172a;">
        <h2 style="margin-top:0;color:#0f172a;">JayArt Database Initialized</h2>
        <p style="color:#475569;font-size:14px;">The database schema and essential seeds are verified and up to date.</p>
        <div style="background:#f8fafc;padding:16px;border-radius:12px;border:1px solid #e2e8f0;margin:20px 0;font-size:13px;">
            <strong>Database Status:</strong> Connected to MySQL<br>
            <strong>Active Tables:</strong> ' . $tableCount . ' tables created<br>
            <strong>Default Super Admin:</strong> admin@jayart.com (admin123)<br>
            <strong>Base Currency:</strong> Indian Rupee (INR)
        </div>
        <a href="' . SITE_URL . '" style="display:inline-block;background:#0f172a;color:#ffffff;text-decoration:none;padding:12px 24px;border-radius:50px;font-weight:600;font-size:13px;">Go to Storefront &rarr;</a>
        <a href="' . SITE_URL . '/admin" style="display:inline-block;background:#f1f5f9;color:#0f172a;text-decoration:none;padding:12px 24px;border-radius:50px;font-weight:600;font-size:13px;margin-left:8px;">Admin Panel &rarr;</a>
    </div>';
    exit;
});

// Dispatch the Request
Router::dispatch();

