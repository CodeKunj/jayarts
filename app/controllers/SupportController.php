<?php
/**
 * JayArt Support, Contact & Chatbot Controller
 */

declare(strict_types=1);

class SupportController extends Controller {
    public function contact(): void {
        $this->render('contact/index', [
            'seo' => [
                'title'       => 'Contact Curators & Art Advisory Desk | JayArt',
                'description' => 'Connect with JayArt gallery curators for artwork inquiries, custom commissions, or delivery support.',
            ]
        ]);
    }

    public function submitContact(): void {
        $this->validateCSRF();

        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $subject = trim((string)($_POST['subject'] ?? 'General Art Inquiry'));
        $message = trim((string)($_POST['message'] ?? ''));

        if (!empty($name) && !empty($email) && !empty($message)) {
            $ticketModel = new Ticket();
            $ticketModel->createTicket([
                'user_id'        => Auth::id(),
                'customer_name'  => $name,
                'customer_email' => $email,
                'subject'        => $subject,
                'category'       => 'general',
                'priority'       => 'medium',
                'message'        => $message
            ]);

            Auth::setFlash('success', 'Your message has been delivered to our curator desk. We will respond via email within 24 hours.');
        }

        $this->redirect('/contact');
    }

    public function chatMessage(): void {
        $msg = trim((string)($_POST['message'] ?? ''));
        if (empty($msg)) {
            $this->json(['success' => false]);
            return;
        }

        // Auto log inquiry
        $db = Database::connect();
        if ($db) {
            $stmt = $db->prepare("INSERT INTO `ticket_messages` (`ticket_id`, `sender_type`, `sender_name`, `message`) VALUES (1, 'user', :name, :msg)");
            $stmt->execute([
                'name' => Auth::check() ? Auth::user()['name'] : 'Guest Visitor',
                'msg'  => $msg
            ]);
        }

        $this->json(['success' => true]);
    }
}

