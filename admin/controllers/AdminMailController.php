<?php
/**
 * JayArt Admin SMTP Mail System Controller
 */

declare(strict_types=1);

class AdminMailController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $db = Database::connect();
        $smtp = $db ? $db->query("SELECT * FROM `smtp_settings` WHERE `id` = 1 LIMIT 1")->fetch() : [];

        $this->renderAdmin('mail/index', [
            'smtp'  => $smtp ?: [],
            'title' => 'SMTP Mail Configuration — JayArt Admin'
        ]);
    }

    public function update(): void {
        $host      = trim((string)($_POST['host'] ?? ''));
        $port      = (int)($_POST['port'] ?? 465);
        $enc       = $_POST['encryption'] ?? 'ssl';
        $user      = trim((string)($_POST['username'] ?? ''));
        $pass      = (string)($_POST['password'] ?? '');
        $fromEmail = trim((string)($_POST['from_email'] ?? ''));
        $fromName  = trim((string)($_POST['from_name'] ?? ''));
        $enabled   = !empty($_POST['is_enabled']) ? 1 : 0;

        $db = Database::connect();
        if ($db) {
            $sql = "UPDATE `smtp_settings` SET `host` = :h, `port` = :p, `encryption` = :e, `username` = :u, `from_email` = :fe, `from_name` = :fn, `is_enabled` = :en";
            $params = [
                'h'  => $host,
                'p'  => $port,
                'e'  => $enc,
                'u'  => $user,
                'fe' => $fromEmail,
                'fn' => $fromName,
                'en' => $enabled
            ];

            if (!empty($pass)) {
                $sql .= ", `password` = :pwd";
                $params['pwd'] = $pass;
            }

            $sql .= " WHERE `id` = 1";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
        }

        Auth::setFlash('success', 'SMTP settings saved successfully.');
        header('Location: ' . ADMIN_URL . '/mail');
        exit;
    }

    public function sendTest(): void {
        $testEmail = trim((string)($_POST['test_email'] ?? ''));
        if (empty($testEmail)) {
            Auth::setFlash('error', 'Please provide a test recipient email.');
            header('Location: ' . ADMIN_URL . '/mail');
            exit;
        }

        $sent = Mailer::send(
            $testEmail,
            "JayArt SMTP Test Connection",
            "<h3>SMTP Connection Verified!</h3><p>Your mail server is configured properly. Automated order confirmation receipts, shipment notifications, and support ticket replies will be delivered seamlessly.</p>",
            "Collector"
        );

        if ($sent) {
            Auth::setFlash('success', "Test email delivered successfully to {$testEmail}.");
        } else {
            Auth::setFlash('error', "Could not send test email. Please check your SMTP host, port, or mailbox password.");
        }

        header('Location: ' . ADMIN_URL . '/mail');
        exit;
    }
}

