<?php
/**
 * JayArt Customer Profile, Orders, Wishlist & Support Desk Controller
 */

declare(strict_types=1);

class ProfileController extends Controller {
    public function __construct() {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
        // Allow wishlist viewing and toggling for guests without forced login
        if ($uri === '/profile/wishlist/toggle' || $uri === '/profile/wishlist' || str_contains((string)$uri, '/wishlist')) {
            return;
        }

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

    public function orderDetail(int|string $id): void {
        $id = (int)$id;
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

    public function downloadInvoice(int|string $id): void {
        $id = (int)$id;
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
        $userId = (int)Auth::id();

        if ($userId > 0) {
            $artworks = $wishlistModel->getUserWishlist($userId);
            $_SESSION['wishlist'] = array_map('intval', array_column($artworks, 'id'));
        } else {
            // Guest session wishlist
            $wishlistIds = $_SESSION['wishlist'] ?? [];
            $artworks = [];
            if (!empty($wishlistIds)) {
                $productModel = new Product();
                foreach ($wishlistIds as $pid) {
                    $item = $productModel->find((int)$pid);
                    if ($item && ($item['status'] ?? 'active') === 'active') {
                        $images = $productModel->getImages((int)$pid);
                        $item['primary_image'] = !empty($images[0]['image_path']) ? $images[0]['image_path'] : ($item['primary_image'] ?? null);
                        $artworks[] = $item;
                    }
                }
            }
        }

        $this->render('profile/wishlist', [
            'artworks' => $artworks,
            'seo'      => ['title' => 'My Saved Paintings Wishlist | JayArt']
        ]);
    }

    public function toggleWishlist(): void {
        $productId = (int)($_POST['product_id'] ?? 0);
        $action = trim((string)($_POST['action'] ?? 'toggle')); // 'toggle', 'remove', 'add'
        if ($productId <= 0) {
            $this->json(['success' => false, 'message' => 'Invalid artwork.'], 400);
            return;
        }

        $userId = (int)Auth::id();
        if (!isset($_SESSION['wishlist']) || !is_array($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }

        // Idempotent Explicit Remove
        if ($action === 'remove') {
            if ($userId > 0) {
                $wishlistModel = new Wishlist();
                $wishlistModel->remove($userId, $productId);
            }
            $_SESSION['wishlist'] = array_values(array_diff($_SESSION['wishlist'], [$productId]));
            $this->json([
                'success'        => true,
                'saved'          => false,
                'wishlist_count' => count($_SESSION['wishlist']),
                'message'        => 'Artwork removed from your wishlist.'
            ]);
            return;
        }

        // Idempotent Explicit Add
        if ($action === 'add') {
            if ($userId > 0) {
                $wishlistModel = new Wishlist();
                if (!$wishlistModel->isWishlisted($userId, $productId)) {
                    $wishlistModel->toggle($userId, $productId);
                }
            }
            if (!in_array($productId, $_SESSION['wishlist'], true)) {
                $_SESSION['wishlist'][] = $productId;
            }
            $this->json([
                'success'        => true,
                'saved'          => true,
                'wishlist_count' => count($_SESSION['wishlist']),
                'message'        => 'Artwork saved to your wishlist!'
            ]);
            return;
        }

        // Action === 'toggle'
        if ($userId > 0) {
            $wishlistModel = new Wishlist();
            $res = $wishlistModel->toggle($userId, $productId);
            if (!empty($res['saved'])) {
                if (!in_array($productId, $_SESSION['wishlist'], true)) {
                    $_SESSION['wishlist'][] = $productId;
                }
            } else {
                $_SESSION['wishlist'] = array_values(array_diff($_SESSION['wishlist'], [$productId]));
            }
            $res['wishlist_count'] = count($_SESSION['wishlist']);
            $res['success'] = true;
            $this->json($res);
            return;
        }

        // Guest Session Wishlist Toggle
        if (in_array($productId, $_SESSION['wishlist'], true)) {
            $_SESSION['wishlist'] = array_values(array_diff($_SESSION['wishlist'], [$productId]));
            $this->json([
                'success'        => true,
                'saved'          => false,
                'wishlist_count' => count($_SESSION['wishlist']),
                'message'        => 'Artwork removed from your wishlist.'
            ]);
        } else {
            $_SESSION['wishlist'][] = $productId;
            $this->json([
                'success'        => true,
                'saved'          => true,
                'wishlist_count' => count($_SESSION['wishlist']),
                'message'        => 'Artwork saved to your wishlist!'
            ]);
        }
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

    public function viewTicket(int|string $id): void {
        $id = (int)$id;
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

    public function replyTicket(int|string $id): void {
        $id = (int)$id;
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

    /**
     * DPDP Act 2023: Data Principal Privacy & Rights Portal
     */
    public function privacy(): void {
        self::ensurePrivacyColumns();
        $userModel = new User();
        $user = $userModel->find(Auth::id());

        $this->render('profile/privacy', [
            'user' => $user,
            'seo'  => ['title' => 'DPDP Privacy & Rights Portal | JayArt']
        ]);
    }

    /**
     * DPDP Section 11: Right to Access Information (Export My Personal Data as JSON)
     */
    public function exportData(): void {
        $userId = (int)Auth::id();
        $userModel = new User();
        $user = $userModel->find($userId);
        if (!$user) {
            $this->redirect('/profile');
            return;
        }

        $addresses = $userModel->getAddresses($userId);
        
        $orderModel = new Order();
        $orders = $orderModel->fetchAll("SELECT * FROM `orders` WHERE `user_id` = :uid", ['uid' => $userId]);
        foreach ($orders as &$ord) {
            $ord['items'] = $orderModel->getOrderItems((int)$ord['id']);
        }
        unset($ord);

        $wishlistIds = $_SESSION['wishlist'] ?? [];

        $export = [
            'statutory_notice' => 'Personal Data Export generated under Section 11 of the Digital Personal Data Protection Act, 2023 (DPDPA 2023), Government of India.',
            'generated_at'     => date('Y-m-d H:i:s T'),
            'data_fiduciary'   => 'JayArt Fine Art Gallery (RiTiVerse)',
            'data_principal'   => [
                'id'              => $user['id'],
                'name'            => $user['name'],
                'email'           => $user['email'],
                'phone'           => $user['phone'] ?? null,
                'nominee_name'    => $user['nominee_name'] ?? null,
                'nominee_contact' => $user['nominee_contact'] ?? null,
                'registered_at'   => $user['created_at'] ?? null,
            ],
            'saved_shipping_addresses' => $addresses,
            'artwork_acquisition_orders' => $orders,
            'saved_wishlist_product_ids' => $wishlistIds
        ];

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="jayart-personal-data-' . date('Ymd-His') . '.json"');
        echo json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * DPDP Section 14: Right to Nominate a Beneficiary
     */
    public function updateNominee(): void {
        $this->validateCSRF();
        self::ensurePrivacyColumns();

        $name = trim((string)($_POST['nominee_name'] ?? ''));
        $contact = trim((string)($_POST['nominee_contact'] ?? ''));

        $userModel = new User();
        $userModel->update((int)Auth::id(), [
            'nominee_name'    => $name,
            'nominee_contact' => $contact
        ]);

        Auth::setFlash('success', 'DPDP Nominee details updated successfully under Section 14 of DPDP Act.');
        $this->redirect('/profile/privacy');
    }

    /**
     * DPDP Section 12: Right to Erasure / Request Account Deletion
     */
    public function requestErasure(): void {
        $this->validateCSRF();
        $reason = trim((string)($_POST['erasure_reason'] ?? 'Requested account deletion under DPDP Act 2023'));

        $user = Auth::user();
        $ticketModel = new Ticket();
        $ticketId = $ticketModel->createTicket([
            'user_id'        => $user['id'],
            'customer_name'  => $user['name'],
            'customer_email' => $user['email'],
            'subject'        => 'DPDP Act Statutory Data Erasure Request (Section 12)',
            'category'       => 'privacy_erasure',
            'priority'       => 'high',
            'message'        => "Data Principal Erasure Request:\n\nUser ID: {$user['id']}\nName: {$user['name']}\nEmail: {$user['email']}\n\nReason/Note: {$reason}\n\nNotice: Please verify pending orders before purging account records, while retaining statutory GST invoices for 6 years as mandated by the CGST Act, 2017."
        ]);

        Auth::setFlash('success', 'Statutory data erasure request submitted to Grievance Officer. Ticket #' . $ticketId . ' generated.');
        $this->redirect('/profile/tickets');
    }

    /**
     * Self-healing DB check for DPDP columns
     */
    private static function ensurePrivacyColumns(): void {
        try {
            $db = Database::connect();
            if ($db) {
                $cols = $db->query("SHOW COLUMNS FROM `users` LIKE 'nominee_name'")->fetchAll();
                if (empty($cols)) {
                    $db->exec("ALTER TABLE `users` ADD COLUMN `nominee_name` VARCHAR(120) NULL, ADD COLUMN `nominee_contact` VARCHAR(180) NULL, ADD COLUMN `dpdp_consent_at` DATETIME NULL");
                }
            }
        } catch (\Throwable $e) {}
    }
}

