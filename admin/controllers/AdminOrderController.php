<?php
/**
 * JayArt Admin Order & Invoice Management Controller
 */

declare(strict_types=1);

class AdminOrderController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $orderModel = new Order();
        $statusFilter = $_GET['status'] ?? '';

        $sql = "SELECT * FROM `orders`";
        $params = [];
        if (!empty($statusFilter)) {
            $sql .= " WHERE `order_status` = :st";
            $params['st'] = $statusFilter;
        }
        $sql .= " ORDER BY `id` DESC";

        $orders = $orderModel->fetchAll($sql, $params);

        $this->renderAdmin('orders/index', [
            'orders'       => $orders,
            'statusFilter' => $statusFilter,
            'title'        => 'Order Management — JayArt Admin'
        ]);
    }

    public function view(int $id): void {
        $orderModel = new Order();
        $order = $orderModel->find($id);

        if (!$order) {
            header('Location: ' . ADMIN_URL . '/orders');
            exit;
        }

        $items = $orderModel->getOrderItems($id);
        $tracking = $orderModel->getDeliveryTracking($id);
        $shipping = json_decode((string)$order['shipping_address'], true) ?: [];

        $this->renderAdmin('orders/view', [
            'order'    => $order,
            'items'    => $items,
            'tracking' => $tracking,
            'shipping' => $shipping,
            'title'    => 'Order #' . $order['order_number'] . ' — JayArt Admin'
        ]);
    }

    public function updateStatus(int $id): void {
        $orderModel = new Order();
        $order = $orderModel->find($id);

        if (!$order) {
            header('Location: ' . ADMIN_URL . '/orders');
            exit;
        }

        $orderStatus   = trim((string)($_POST['order_status'] ?? $order['order_status']));
        $paymentStatus = trim((string)($_POST['payment_status'] ?? $order['payment_status']));

        $orderModel->update($id, [
            'order_status'   => $orderStatus,
            'payment_status' => $paymentStatus
        ]);

        // Send Email Notification to Customer on Status Change
        $subject = "Update regarding your JayArt order #{$order['order_number']}";
        $body = "<h3>Order Status Update</h3>
                 <p>Dear {$order['customer_name']},</p>
                 <p>The status of your artwork order has been updated to: <strong>" . strtoupper(str_replace('_', ' ', $orderStatus)) . "</strong></p>
                 <p>Payment Status: <strong>" . strtoupper($paymentStatus) . "</strong></p>
                 <p><a href='" . SITE_URL . "/track/{$order['order_number']}' class='btn'>View Live Delivery Tracking</a></p>";
        
        Mailer::send($order['customer_email'], $subject, $body, $order['customer_name']);

        Auth::setFlash('success', 'Order status updated and customer notification email dispatched.');
        header('Location: ' . ADMIN_URL . '/orders/view/' . $id);
        exit;
    }

    public function invoice(int $id): void {
        $orderModel = new Order();
        $order = $orderModel->find($id);

        if (!$order) {
            header('Location: ' . ADMIN_URL . '/orders');
            exit;
        }

        $items = $orderModel->getOrderItems($id);
        $shipping = json_decode((string)$order['shipping_address'], true) ?: [];

        header('Content-Type: text/html; charset=UTF-8');
        require APP_PATH . '/views/profile/invoice.php';
        exit;
    }
}

