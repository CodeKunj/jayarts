<?php
/**
 * JayArt Admin Dashboard & Analytics Controller
 */

declare(strict_types=1);

class AdminDashboardController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $db = Database::connect();

        // 1. KPI Statistics
        $totalRevenue = 0.00;
        $totalOrders = 0;
        $activePaintings = 0;
        $lowStockCount = 0;
        $openTicketsCount = 0;

        if ($db) {
            $revRow = $db->query("SELECT SUM(total_amount) as rev FROM `orders` WHERE `payment_status` = 'paid' OR `order_status` IN ('confirmed', 'shipped', 'delivered')")->fetch();
            $totalRevenue = (float)($revRow['rev'] ?? 0);

            $ordRow = $db->query("SELECT COUNT(id) as cnt FROM `orders`")->fetch();
            $totalOrders = (int)($ordRow['cnt'] ?? 0);

            $prodRow = $db->query("SELECT COUNT(id) as cnt FROM `products` WHERE `status` = 'active'")->fetch();
            $activePaintings = (int)($prodRow['cnt'] ?? 0);

            $stockRow = $db->query("SELECT COUNT(id) as cnt FROM `products` WHERE `stock_quantity` <= 1 AND `status` = 'active'")->fetch();
            $lowStockCount = (int)($stockRow['cnt'] ?? 0);

            $tckRow = $db->query("SELECT COUNT(id) as cnt FROM `support_tickets` WHERE `status` = 'open'")->fetch();
            $openTicketsCount = (int)($tckRow['cnt'] ?? 0);
        }

        // 2. Recent Orders
        $orderModel = new Order();
        $recentOrders = $orderModel->fetchAll("SELECT * FROM `orders` ORDER BY `id` DESC LIMIT 8");

        // 3. Analytics Chart Data (Past 7 Days Sales)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = date('Y-m-d', strtotime("-{$i} days"));
            $chartLabels[] = date('D, d M', strtotime($day));

            $dayRev = 0.00;
            if ($db) {
                $stmt = $db->prepare("SELECT SUM(total_amount) as rev FROM `orders` WHERE DATE(created_at) = :day");
                $stmt->execute(['day' => $day]);
                $r = $stmt->fetch();
                $dayRev = (float)($r['rev'] ?? 0);
            }
            $chartData[] = $dayRev;
        }

        $this->renderAdmin('dashboard', [
            'totalRevenue'      => $totalRevenue,
            'totalOrders'       => $totalOrders,
            'activePaintings'   => $activePaintings,
            'lowStockCount'     => $lowStockCount,
            'openTicketsCount'  => $openTicketsCount,
            'recentOrders'      => $recentOrders,
            'chartLabels'       => json_encode($chartLabels),
            'chartData'         => json_encode($chartData),
            'title'             => 'Admin Dashboard — JayArt'
        ]);
    }
}

