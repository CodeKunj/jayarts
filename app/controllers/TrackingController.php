<?php
/**
 * JayArt Public Order Tracking Controller
 */

declare(strict_types=1);

class TrackingController extends Controller {
    public function index(): void {
        $orderNumber = trim((string)($_GET['order_number'] ?? ''));

        if (!empty($orderNumber)) {
            $this->search($orderNumber);
            return;
        }

        $this->render('tracking/index', [
            'order' => null,
            'seo'   => ['title' => 'Live Artwork Delivery Tracking | JayArt Gallery']
        ]);
    }

    public function search(string $orderNumber = '', ?string $order_number = null): void {
        $orderNumber = !empty($orderNumber) ? $orderNumber : ($order_number ?? '');
        $orderModel = new Order();
        $order = $orderModel->findByOrderNumber($orderNumber);

        $this->render('tracking/index', [
            'order'       => $order,
            'searchQuery' => $orderNumber,
            'seo'         => ['title' => "Tracking Order #{$orderNumber} | JayArt"]
        ]);
    }
}

