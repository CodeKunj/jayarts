<?php
/**
 * JayArt Admin Delivery Tracking Controller
 */

declare(strict_types=1);

class AdminDeliveryController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $deliveryModel = new Delivery();
        $deliveries = $deliveryModel->fetchAll(
            "SELECT d.*, o.order_number, o.customer_name, o.customer_email, o.customer_phone, o.order_status
             FROM `delivery_tracking` d
             JOIN `orders` o ON d.order_id = o.id
             ORDER BY d.id DESC"
        );

        $this->renderAdmin('delivery/index', [
            'deliveries' => $deliveries,
            'title'      => 'Delivery Tracking Milestones — JayArt Admin'
        ]);
    }

    public function update(int $orderId = 0, ?int $order_id = null): void {
        $orderId = $orderId > 0 ? $orderId : (int)($order_id ?? 0);
        $courierName    = trim((string)($_POST['courier_name'] ?? 'Fine Art Express'));
        $trackingNumber = trim((string)($_POST['tracking_number'] ?? ''));
        $currentStatus  = trim((string)($_POST['current_status'] ?? 'In Transit'));

        $db = Database::connect();
        $stmt = $db->prepare("UPDATE `delivery_tracking` SET `courier_name` = :c, `tracking_number` = :t, `current_status` = :st WHERE `order_id` = :oid");
        $stmt->execute(['c' => $courierName, 't' => $trackingNumber, 'st' => $currentStatus, 'oid' => $orderId]);

        Auth::setFlash('success', 'Courier tracking information updated.');
        header('Location: ' . ADMIN_URL . '/delivery');
        exit;
    }

    public function addMilestone(): void {
        $deliveryId  = (int)($_POST['delivery_id'] ?? 0);
        $statusTitle = trim((string)($_POST['status_title'] ?? ''));
        $desc        = trim((string)($_POST['description'] ?? ''));
        $loc         = trim((string)($_POST['location'] ?? ''));

        if ($deliveryId && !empty($statusTitle)) {
            $deliveryModel = new Delivery();
            $deliveryModel->addMilestone($deliveryId, $statusTitle, $desc, $loc);
            Auth::setFlash('success', 'Milestone recorded. Live patron tracking updated.');
        }

        header('Location: ' . ADMIN_URL . '/delivery');
        exit;
    }
}

