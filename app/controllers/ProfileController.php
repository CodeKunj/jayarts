<?php
/**
 * JayArt Customer Profile, Orders, Wishlist & Support Desk Controller
 */

declare(strict_types=1);

class ProfileController extends Controller {
    public function __construct() {
        if (!Auth::check()) {
            if ($this->isAjax()) {
                $this->json(['redirect' => SITE_URL . '/login']);
            }
            header('Location: ' . SITE_URL . '/login');
            exit;
        }
    }

    public function index(): void {
        $userModel = new User();
        $user = $userModel->find(Auth::id());
        $addresses = $userModel->getAddresses((int)Auth::id());

        $this->render('profile/index', [
            'user'      => $user,
            'addresses' => $addresses,
            'seo'       => ['title' => 'My Collector Account | JayArt']
        ]);
    }

    public function update(): void {
        $this->validateCSRF();

        $name = trim((string)($_POST['name'] ?? ''));
        $phone = trim((string)($_POST['phone'] ?? ''));
        $newPass = (string)($_POST['new_password'] ?? '');

        $data = ['name' => $name, 'phone' => $phone];
        if (!empty($newPass) && strlen($newPass) >= 6) {
            $data['password'] = Auth::hashPassword($newPass);
        }

        $userModel = new User();
        $userModel->update((int)Auth::id(), $data);
        
        Auth::setFlash('success', 'Profile details updated.');
        $this->redirect('/profile');
    }

    public function orders(): void {
        $orderModel = new Order();
        $orders = $orderModel->fetchAll(
            "SELECT * FROM `orders` WHERE `user_id` = :uid ORDER BY `id` DESC",
            ['uid' => Auth::id()]
        );

        $this->render('profile/orders', [
            'orders' => $orders,
            'seo'    => ['title' => 'My Artwork Orders | JayArt']
        ]);
    }

    public function orderDetail(int $id): void {
        $orderModel = new Order();
        $order = $orderModel->find($id);

        if (!$order || (int)$order['user_id'] !== (int)Auth::id()) {
            $this->redirect('/profile/orders');
            return;
        }

        $items = $orderModel->getOrderItems($id);
        $tracking = $orderModel->getDeliveryTracking($id);

        $this->render('profile/order_detail', [
            'order'    => $order,
            'items'    => $items,
            'tracking' => $tracking,
            'seo'      => ['title' => 'Order #' . $order['order_number'] . ' | JayArt']
        ]);
    }

    public function downloadInvoice(int $id): void {
        $orderModel = new Order();
        $order = $orderModel->find($id);

        if (!$order || (int)$order['user_id'] !== (int)Auth::id()) {
            $this->redirect('/profile/orders');
            return;
        }

        $items = $orderModel->getOrderItems($id);
        $shipping = json_decode((string)$order['shipping_address'], true) ?: [];

        // Render clean, printable museum acquisition tax invoice
        header('Content-Type: text/html; charset=UTF-8');
        require APP_PATH . '/views/profile/invoice.php';
        exit;
    }

    public function wishlist(): void {
        $wishlistModel = new Wishlist();
        $artworks = $wishlistModel->getUserWishlist((int)Auth::id());

        $this->render('profile/wishlist', [
            'artworks' => $artworks,
            'seo'      => ['title' => 'My Saved Paintings Wishlist | JayArt']
        ]);
    }

    public function toggleWishlist(): void {
        $productId = (int)($_POST['product_id'] ?? 0);
        $wishlistModel = new Wishlist();
        $res = $wishlistModel->toggle((int)Auth::id(), $productId);
        $this->json($res);
    }

    public function tickets(): void {
        $ticketModel = new Ticket();
        $tickets = $ticketModel->fetchAll(
            "SELECT * FROM `support_tickets` WHERE `user_id` = :uid ORDER BY `id` DESC",
            ['uid' => Auth::id()]
        );

        $this->render('profile/tickets', [
            'tickets' => $tickets,
            'seo'     => ['title' => 'My Support Tickets | JayArt']
        ]);
    }

    public function createTicket(): void {
        $this->validateCSRF();

        $subject  = trim((string)($_POST['subject'] ?? ''));
        $category = trim((string)($_POST['category'] ?? 'order_inquiry'));
        $priority = trim((string)($_POST['priority'] ?? 'medium'));
        $message  = trim((string)($_POST['message'] ?? ''));

        $user = Auth::user();
        $ticketModel = new Ticket();
        $ticketId = $ticketModel->createTicket([
            'user_id'        => $user['id'],
            'customer_name'  => $user['name'],
            'customer_email' => $user['email'],
            'subject'        => $subject,
            'category'       => $category,
            'priority'       => $priority,
            'message'        => $message
        ]);

        Auth::setFlash('success', 'Support ticket raised successfully. Our curator team will respond shortly.');
        $this->redirect('/profile/ticket/' . $ticketId);
    }

    public function viewTicket(int $id): void {
        $ticketModel = new Ticket();
        $ticket = $ticketModel->find($id);

        if (!$ticket || (int)$ticket['user_id'] !== (int)Auth::id()) {
            $this->redirect('/profile/tickets');
            return;
        }

        $messages = $ticketModel->getMessages($id);

        $this->render('profile/ticket_view', [
            'ticket'   => $ticket,
            'messages' => $messages,
            'seo'      => ['title' => "Ticket {$ticket['ticket_number']} | JayArt"]
        ]);
    }

    public function replyTicket(int $id): void {
        $this->validateCSRF();

        $ticketModel = new Ticket();
        $ticket = $ticketModel->find($id);

        if (!$ticket || (int)$ticket['user_id'] !== (int)Auth::id()) {
            $this->redirect('/profile/tickets');
            return;
        }

        $msg = trim((string)($_POST['message'] ?? ''));
        if (!empty($msg)) {
            $user = Auth::user();
            $ticketModel->addMessage($id, 'user', $user['name'], $msg);
            $ticketModel->update($id, ['status' => 'open']);
        }

        $this->redirect('/profile/ticket/' . $id);
    }
}

