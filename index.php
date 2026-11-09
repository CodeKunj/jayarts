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

// Instant Cache Flush Query Trigger (Supports ?flush=1 anywhere from browser)
if (isset($_GET['flush']) && $_GET['flush'] === '1') {
    if (class_exists('Cache')) {
        Cache::clear();
    }
    $cleanUri = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
    header('Location: ' . ($cleanUri ?: '/'));
    exit;
}

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

// DPDP Act, 2023 Statutory Privacy Notice & Terms of Service
Router::get('/privacy', [LegalController::class, 'privacy']);
Router::get('/dpdp', [LegalController::class, 'privacy']);
Router::get('/terms', [LegalController::class, 'terms']);

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

// Profile DPDP Act 2023 Data Principal Rights Portal
Router::get('/profile/privacy', [ProfileController::class, 'privacy']);
Router::get('/profile/privacy/export', [ProfileController::class, 'exportData']);
Router::post('/profile/privacy/nominee', [ProfileController::class, 'updateNominee']);
Router::post('/profile/privacy/erasure-request', [ProfileController::class, 'requestErasure']);

// Dynamic XML Sitemap for Google & Bing
Router::get('/sitemap.xml', [SitemapController::class, 'index']);

// Direct Favicon Endpoint (Ensures /favicon.ico works across Apache, Nginx, and PHP built-in server)
Router::get('/favicon.ico', function() {
    $ico = ROOT_PATH . '/favicon.ico';
    if (file_exists($ico)) {
        header('Content-Type: image/x-icon');
        header('Cache-Control: public, max-age=604800');
        readfile($ico);
        exit;
    }
    http_response_code(404);
    exit;
});

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

    if (isset($_GET['clean']) && $_GET['clean'] === '1') {
        $cleanFile = ROOT_PATH . '/database/clean_dummy_data.sql';
        if (file_exists($cleanFile)) {
            Database::executeSqlFile($db, $cleanFile);
        }

        // Direct table wipe fallback ensuring 100% cleanup across any cPanel database privilege level
        $tablesToClean = [
            'product_images', 'product_seo', 'products',
            'blog_seo', 'blogs', 'order_items', 'orders',
            'delivery_milestones', 'delivery_tracking', 'reviews',
            'wishlists', 'inventory_logs', 'ticket_messages', 'support_tickets', 'addresses'
        ];
        $db->exec("SET FOREIGN_KEY_CHECKS = 0;");
        foreach ($tablesToClean as $tbl) {
            try {
                $db->exec("DELETE FROM `{$tbl}`");
                $db->exec("ALTER TABLE `{$tbl}` AUTO_INCREMENT = 1");
            } catch (Throwable $e) {}
        }
        try {
            $db->exec("DELETE FROM `users` WHERE `role` != 'admin'");
        } catch (Throwable $e) {}
        $db->exec("SET FOREIGN_KEY_CHECKS = 1;");
    }

    Database::autoMigrateIfEmpty($db, false);
    Cache::clear();

    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $tableCount = count($tables);

    $productCount = 0;
    try {
        $productCount = (int)$db->query("SELECT COUNT(*) FROM `products`")->fetchColumn();
    } catch (Throwable $e) {}

    echo '<div style="font-family:sans-serif;max-width:650px;margin:50px auto;padding:30px;border:1px solid #e2e8f0;border-radius:16px;background:#ffffff;box-shadow:0 10px 25px -5px rgba(0,0,0,0.05);color:#0f172a;">
        <h2 style="margin-top:0;color:#0f172a;">JayArt Database Initialized</h2>
        <p style="color:#475569;font-size:14px;">The database schema and caches have been flushed and verified.</p>
        ' . (isset($_GET['clean']) ? '<div style="background:#ecfdf5;color:#065f46;padding:12px;border-radius:8px;margin-bottom:16px;font-size:13px;font-weight:600;">All sample paintings, dummy reviews, and demo orders have been purged!</div>' : '') . '
        <div style="background:#f8fafc;padding:16px;border-radius:12px;border:1px solid #e2e8f0;margin:20px 0;font-size:13px;line-height:1.7;">
            <strong>Database Status:</strong> Connected to MySQL<br>
            <strong>Active Tables:</strong> ' . $tableCount . ' tables created<br>
            <strong>Total Paintings in Catalog:</strong> ' . $productCount . '<br>
            <strong>System Cache:</strong> Cleared and Fresh<br>
            <strong>Default Super Admin:</strong> admin@jayart.com (admin123)<br>
            <strong>Base Currency:</strong> Indian Rupee (INR)
        </div>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:20px;">
            <a href="' . SITE_URL . '" style="background:#0f172a;color:#ffffff;text-decoration:none;padding:12px 24px;border-radius:50px;font-weight:600;font-size:13px;">Go to Storefront &rarr;</a>
            <a href="' . SITE_URL . '/admin" style="background:#f1f5f9;color:#0f172a;text-decoration:none;padding:12px 24px;border-radius:50px;font-weight:600;font-size:13px;">Admin Panel &rarr;</a>
            <a href="' . SITE_URL . '/db-setup?clean=1" onclick="return confirm(\'Are you sure you want to clean all dummy data from the database?\');" style="background:#fef2f2;color:#dc2626;text-decoration:none;padding:12px 20px;border-radius:50px;font-weight:600;font-size:13px;border:1px solid #fecaca;">Purge Dummy Data Now</a>
        </div>
    </div>';
    exit;
});

// Dispatch the Request
Router::dispatch();

