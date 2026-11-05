<?php
/**
 * JayArt Administration Panel Front Controller
 * cPanel Compatible Dedicated Admin Router
 */

declare(strict_types=1);

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}
require_once dirname(__DIR__) . '/core/App.php';
App::bootstrap();

// Parse admin route segment
$url = $_GET['url'] ?? '';
$adminPath = '/' . trim($url, '/');

// Admin Authentication (Public within admin)
Router::any('/admin/login', [AdminAuthController::class, 'login']);
Router::get('/admin/logout', [AdminAuthController::class, 'logout']);

// Dashboard & Analytics
Router::get('/admin', [AdminDashboardController::class, 'index']);
Router::get('/admin/dashboard', [AdminDashboardController::class, 'index']);

// Products & Artwork Management (Includes Per-Product SEO)
Router::get('/admin/products', [AdminProductController::class, 'index']);
Router::get('/admin/products/create', [AdminProductController::class, 'create']);
Router::post('/admin/products/store', [AdminProductController::class, 'store']);
Router::get('/admin/products/edit/{id}', [AdminProductController::class, 'edit']);
Router::post('/admin/products/update/{id}', [AdminProductController::class, 'update']);
Router::post('/admin/products/delete/{id}', [AdminProductController::class, 'delete']);

// Categories (Art Mediums & Styles)
Router::get('/admin/categories', [AdminCategoryController::class, 'index']);
Router::post('/admin/categories/save', [AdminCategoryController::class, 'save']);
Router::post('/admin/categories/delete/{id}', [AdminCategoryController::class, 'delete']);

// Order & Invoice Management
Router::get('/admin/orders', [AdminOrderController::class, 'index']);
Router::get('/admin/orders/view/{id}', [AdminOrderController::class, 'view']);
Router::post('/admin/orders/status/{id}', [AdminOrderController::class, 'updateStatus']);
Router::get('/admin/orders/invoice/{id}', [AdminOrderController::class, 'invoice']);

// Delivery Tracking
Router::get('/admin/delivery', [AdminDeliveryController::class, 'index']);
Router::post('/admin/delivery/update/{order_id}', [AdminDeliveryController::class, 'update']);
Router::post('/admin/delivery/add-milestone', [AdminDeliveryController::class, 'addMilestone']);

// Inventory Management
Router::get('/admin/inventory', [AdminInventoryController::class, 'index']);
Router::post('/admin/inventory/adjust', [AdminInventoryController::class, 'adjustStock']);

// Blog & Stories (Includes Per-Blog SEO)
Router::get('/admin/blog', [AdminBlogController::class, 'index']);
Router::get('/admin/blog/create', [AdminBlogController::class, 'create']);
Router::post('/admin/blog/store', [AdminBlogController::class, 'store']);
Router::get('/admin/blog/edit/{id}', [AdminBlogController::class, 'edit']);
Router::post('/admin/blog/update/{id}', [AdminBlogController::class, 'update']);
Router::post('/admin/blog/delete/{id}', [AdminBlogController::class, 'delete']);

// SEO Panel (Global SEO + GA4, GSC, Meta Pixel, Bing Keys + Sitemap)
Router::get('/admin/seo', [AdminSEOController::class, 'index']);
Router::post('/admin/seo/update-global', [AdminSEOController::class, 'updateGlobal']);
Router::post('/admin/seo/update-analytics', [AdminSEOController::class, 'updateAnalytics']);
Router::post('/admin/seo/generate-sitemap', [AdminSEOController::class, 'generateSitemap']);

// Payment Gateway Settings (Razorpay, Direct UPI, COD, Bank Transfer)
Router::get('/admin/payments', [AdminPaymentController::class, 'index']);
Router::post('/admin/payments/update', [AdminPaymentController::class, 'update']);

// SMTP Mail Settings
Router::get('/admin/mail', [AdminMailController::class, 'index']);
Router::post('/admin/mail/update', [AdminMailController::class, 'update']);
Router::post('/admin/mail/test', [AdminMailController::class, 'sendTest']);

// Customer Support & Tickets
Router::get('/admin/tickets', [AdminTicketController::class, 'index']);
Router::get('/admin/tickets/view/{id}', [AdminTicketController::class, 'view']);
Router::post('/admin/tickets/reply/{id}', [AdminTicketController::class, 'reply']);
Router::post('/admin/tickets/status/{id}', [AdminTicketController::class, 'status']);

// Promotional Coupons & Offer Management
Router::get('/admin/coupons', [AdminCouponController::class, 'index']);
Router::post('/admin/coupons/store', [AdminCouponController::class, 'store']);
Router::post('/admin/coupons/update/{id}', [AdminCouponController::class, 'update']);
Router::post('/admin/coupons/toggle/{id}', [AdminCouponController::class, 'toggle']);
Router::post('/admin/coupons/delete/{id}', [AdminCouponController::class, 'delete']);

// Poster-Style Ad Management
Router::get('/admin/ads', [AdminAdController::class, 'index']);
Router::post('/admin/ads/store', [AdminAdController::class, 'store']);
Router::post('/admin/ads/update/{id}', [AdminAdController::class, 'update']);
Router::post('/admin/ads/delete/{id}', [AdminAdController::class, 'delete']);

// General Site Settings
Router::get('/admin/settings', [AdminSettingController::class, 'index']);
Router::post('/admin/settings/update', [AdminSettingController::class, 'update']);
Router::post('/admin/settings/clean-dummy-data', [AdminSettingController::class, 'cleanDummyData']);

// Multi-Currency & FX Exchange Rates
Router::get('/admin/currencies', [AdminCurrencyController::class, 'index']);
Router::post('/admin/currencies/store', [AdminCurrencyController::class, 'store']);
Router::post('/admin/currencies/update/{id}', [AdminCurrencyController::class, 'update']);
Router::post('/admin/currencies/toggle/{id}', [AdminCurrencyController::class, 'toggle']);
Router::post('/admin/currencies/set-base/{id}', [AdminCurrencyController::class, 'setBase']);
Router::post('/admin/currencies/delete/{id}', [AdminCurrencyController::class, 'delete']);

// Cache Engine Management (Redis-like Telemetry & Invalidation)
Router::get('/admin/cache', [AdminCacheController::class, 'index']);
Router::post('/admin/cache/clear-all', [AdminCacheController::class, 'clearAll']);
Router::post('/admin/cache/clear-products', [AdminCacheController::class, 'clearProducts']);
Router::post('/admin/cache/clear-categories', [AdminCacheController::class, 'clearCategories']);
Router::post('/admin/cache/clear-home', [AdminCacheController::class, 'clearHome']);
Router::post('/admin/cache/toggle', [AdminCacheController::class, 'toggle']);
Router::post('/admin/cache/update-ttls', [AdminCacheController::class, 'updateTtls']);

// Normalize admin dispatching path
if (!empty($_GET['url'])) {
    $sub = trim($_GET['url'], '/');
    $fullAdminUri = '/admin' . ($sub !== '' ? '/' . $sub : '');
} else {
    $rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '/admin', PHP_URL_PATH);
    $fullAdminUri = '/' . trim($rawUri, '/');
}
if ($fullAdminUri === '/' || $fullAdminUri === '/admin/') {
    $fullAdminUri = '/admin';
}

Router::dispatch($fullAdminUri);

