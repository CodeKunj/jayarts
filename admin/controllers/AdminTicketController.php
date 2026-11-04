<?php
/**
 * JayArt Admin Customer Support & Ticket Controller
 */

declare(strict_types=1);

class AdminTicketController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    /**
     * List all support tickets with filtering
     */
    public function index(): void {
        $statusFilter = trim((string)($_GET['status'] ?? ''));
        $categoryFilter = trim((string)($_GET['category'] ?? ''));
        $search = trim((string)($_GET['q'] ?? ''));

        $params = [];
        $whereClauses = [];

        if ($statusFilter !== '') {
            $whereClauses[] = "st.status = :status";
            $params['status'] = $statusFilter;
        }

        if ($categoryFilter !== '') {
            $whereClauses[] = "st.category = :category";
            $params['category'] = $categoryFilter;
        }

        if ($search !== '') {
            $whereClauses[] = "(st.ticket_number LIKE :q OR st.customer_name LIKE :q OR st.customer_email LIKE :q OR st.subject LIKE :q)";
            $params['q'] = "%{$search}%";
        }

        $whereSql = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';

        $ticketModel = new Ticket();
        $tickets = $ticketModel->fetchAll(
            "SELECT st.*, o.order_number, 
                    (SELECT COUNT(*) FROM `ticket_messages` tm WHERE tm.ticket_id = st.id) AS msg_count
             FROM `support_tickets` st
             LEFT JOIN `orders` o ON st.order_id = o.id
             {$whereSql}
             ORDER BY 
                CASE st.status
                    WHEN 'open' THEN 1
                    WHEN 'in_progress' THEN 2
                    WHEN 'resolved' THEN 3
                    WHEN 'closed' THEN 4
                    ELSE 5
                END,
                st.updated_at DESC",
            $params
        );

        // Fetch counts for dashboard badges
        $counts = [
            'open'        => (int)($ticketModel->fetch("SELECT COUNT(*) AS c FROM `support_tickets` WHERE `status` = 'open'")['c'] ?? 0),
            'in_progress' => (int)($ticketModel->fetch("SELECT COUNT(*) AS c FROM `support_tickets` WHERE `status` = 'in_progress'")['c'] ?? 0),
            'resolved'    => (int)($ticketModel->fetch("SELECT COUNT(*) AS c FROM `support_tickets` WHERE `status` = 'resolved'")['c'] ?? 0),
            'closed'      => (int)($ticketModel->fetch("SELECT COUNT(*) AS c FROM `support_tickets` WHERE `status` = 'closed'")['c'] ?? 0),
            'total'       => (int)($ticketModel->fetch("SELECT COUNT(*) AS c FROM `support_tickets`")['c'] ?? 0),
        ];

        $this->renderAdmin('tickets/index', [
            'tickets'        => $tickets,
            'counts'         => $counts,
            'statusFilter'   => $statusFilter,
            'categoryFilter' => $categoryFilter,
            'search'         => $search,
            'title'          => 'Customer Care & Support Tickets — JayArt Admin'
        ]);
    }

    /**
     * View a single ticket conversation
     */
    public function view(int $id): void {
        $ticketModel = new Ticket();
        $ticket = $ticketModel->fetch(
            "SELECT st.*, o.order_number, o.total_amount, o.order_status, o.created_at AS order_created_at
             FROM `support_tickets` st
             LEFT JOIN `orders` o ON st.order_id = o.id
             WHERE st.id = :id
             LIMIT 1",
            ['id' => $id]
        );

        if (!$ticket) {
            Auth::setFlash('error', 'Ticket not found.');
            header('Location: ' . ADMIN_URL . '/tickets');
            exit;
        }

        $messages = $ticketModel->getMessages($id);

        $this->renderAdmin('tickets/view', [
            'ticket'   => $ticket,
            'messages' => $messages,
            'title'    => 'Ticket #' . $ticket['ticket_number'] . ' — JayArt Admin'
        ]);
    }

    /**
     * Post an Admin reply to the ticket
     */
    public function reply(int $id): void {
        Validator::validateCsrf();

        $message = trim((string)($_POST['message'] ?? ''));
        $newStatus = trim((string)($_POST['status'] ?? 'in_progress'));

        if (empty($message)) {
            Auth::setFlash('error', 'Reply message cannot be empty.');
            header('Location: ' . ADMIN_URL . '/tickets/view/' . $id);
            exit;
        }

        $ticketModel = new Ticket();
        $ticket = $ticketModel->find($id);

        if (!$ticket) {
            Auth::setFlash('error', 'Ticket not found.');
            header('Location: ' . ADMIN_URL . '/tickets');
            exit;
        }

        $admin = Auth::admin();
        $adminName = $admin['name'] ?? 'JayArt Curator Support';

        // Add admin message
        $ticketModel->addMessage($id, 'admin', $adminName, $message);

        // Update status if selected
        if (!empty($newStatus) && in_array($newStatus, ['open', 'in_progress', 'resolved', 'closed'], true)) {
            $ticketModel->update($id, ['status' => $newStatus]);
        }

        // Notify user via Email (if configured)
        try {
            $emailBody = "
                <p>Hello <strong>" . htmlspecialchars($ticket['customer_name']) . "</strong>,</p>
                <p>A curator from JayArt Gallery has responded to your inquiry (Ticket <strong>#" . htmlspecialchars($ticket['ticket_number']) . "</strong>):</p>
                <div style='background: #f8fafc; border-left: 4px solid #18181B; padding: 15px; margin: 15px 0; font-style: italic; color: #1e293b;'>
                    " . nl2br(htmlspecialchars($message)) . "
                </div>
                <p>Current ticket status: <strong style='text-transform: capitalize;'>" . htmlspecialchars($newStatus) . "</strong></p>
                <p>You can view your full inquiry history by logging into your account at <a href='" . SITE_URL . "/profile/tickets'>" . SITE_URL . "/profile/tickets</a>.</p>
            ";
            Mailer::send($ticket['customer_email'], "Update on Ticket #" . $ticket['ticket_number'] . " — JayArt Gallery", $emailBody);
        } catch (Throwable $e) {
            // Silently log or continue if mail server is offline in local dev
        }

        Auth::setFlash('success', 'Response recorded and patron notified.');
        header('Location: ' . ADMIN_URL . '/tickets/view/' . $id);
        exit;
    }

    /**
     * Update ticket status directly
     */
    public function status(int $id): void {
        Validator::validateCsrf();

        $status = trim((string)($_POST['status'] ?? ''));

        if (in_array($status, ['open', 'in_progress', 'resolved', 'closed'], true)) {
            $ticketModel = new Ticket();
            $ticketModel->update($id, ['status' => $status]);
            Auth::setFlash('success', "Ticket status changed to {$status}.");
        }

        header('Location: ' . ADMIN_URL . '/tickets/view/' . $id);
        exit;
    }
}

