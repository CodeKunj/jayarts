<?php
/**
 * JayArt Admin Inventory & Audit Trail Controller
 */

declare(strict_types=1);

class AdminInventoryController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $productModel = new Product();
        $inventoryModel = new Inventory();

        $paintings = $productModel->fetchAll(
            "SELECT p.*, c.name as category_name FROM `products` p LEFT JOIN `categories` c ON p.category_id = c.id ORDER BY p.stock_quantity ASC, p.id DESC"
        );

        $logs = $inventoryModel->getRecentLogs(30);

        $this->renderAdmin('inventory/index', [
            'paintings' => $paintings,
            'logs'      => $logs,
            'title'     => 'Inventory Management & Audit Log — JayArt Admin'
        ]);
    }

    public function adjustStock(): void {
        $productId = (int)($_POST['product_id'] ?? 0);
        $newStock  = (int)($_POST['new_stock'] ?? 0);
        $reason    = trim((string)($_POST['reason'] ?? 'Manual stock adjustment by admin'));

        $inventoryModel = new Inventory();
        $inventoryModel->adjustStock($productId, $newStock, $reason);

        // Automatic Redis-like Cache Invalidation on inventory adjustment
        Cache::deletePattern('products:*');
        Cache::deletePattern('categories:*');
        Cache::deletePattern('home:*');

        Auth::setFlash('success', 'Artwork stock adjusted and recorded in inventory audit log.');
        header('Location: ' . ADMIN_URL . '/inventory');
        exit;
    }
}

