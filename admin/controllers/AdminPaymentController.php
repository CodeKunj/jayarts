<?php
/**
 * JayArt Admin Payment Gateway Settings Controller
 * Razorpay, Direct Dynamic UPI QR, COD, Bank Transfer
 */

declare(strict_types=1);

class AdminPaymentController extends Controller {
    public function __construct() {
        Auth::requireAdmin();
    }

    public function index(): void {
        $paymentModel = new Payment();
        $gateways = $paymentModel->all("sort_order ASC, id ASC");

        $gatewaysByCode = [];
        foreach ($gateways as $g) {
            $gatewaysByCode[$g['gateway_code']] = $g;
        }

        $this->renderAdmin('payments/index', [
            'gateways' => $gatewaysByCode,
            'title'    => 'Payment Gateway Settings — JayArt Admin'
        ]);
    }

    public function update(): void {
        $db = Database::connect();
        if (!$db) {
            header('Location: ' . ADMIN_URL . '/payments');
            exit;
        }

        // 1. Update Razorpay
        $rzpEnabled = !empty($_POST['razorpay_enabled']) ? 1 : 0;
        $rzpMode    = $_POST['razorpay_mode'] ?? 'test';
        $rzpKeyId   = trim((string)($_POST['razorpay_key_id'] ?? ''));
        $rzpSecret  = trim((string)($_POST['razorpay_key_secret'] ?? ''));

        $stmt = $db->prepare("UPDATE `payment_settings` SET `is_enabled` = :en, `mode` = :m, `key_id` = :k, `key_secret` = :s WHERE `gateway_code` = 'razorpay'");
        $stmt->execute(['en' => $rzpEnabled, 'm' => $rzpMode, 'k' => $rzpKeyId, 's' => $rzpSecret]);

        // 2. Update Direct Dynamic UPI
        $upiEnabled = !empty($_POST['upi_enabled']) ? 1 : 0;
        $upiVpa     = trim((string)($_POST['upi_vpa'] ?? ''));
        $upiName    = trim((string)($_POST['upi_merchant_name'] ?? ''));
        $upiInst    = trim((string)($_POST['upi_instructions'] ?? ''));

        $stmt = $db->prepare("UPDATE `payment_settings` SET `is_enabled` = :en, `upi_vpa` = :vpa, `upi_merchant_name` = :n, `instructions` = :inst WHERE `gateway_code` = 'upi'");
        $stmt->execute(['en' => $upiEnabled, 'vpa' => $upiVpa, 'n' => $upiName, 'inst' => $upiInst]);

        // 3. Update COD
        $codEnabled = !empty($_POST['cod_enabled']) ? 1 : 0;
        $codFee     = (float)($_POST['cod_extra_fee'] ?? 0.0);

        $stmt = $db->prepare("UPDATE `payment_settings` SET `is_enabled` = :en, `extra_fee` = :fee WHERE `gateway_code` = 'cod'");
        $stmt->execute(['en' => $codEnabled, 'fee' => $codFee]);

        // 4. Update Bank Transfer
        $bankEnabled = !empty($_POST['bank_enabled']) ? 1 : 0;
        $bankDetails = trim((string)($_POST['bank_details'] ?? ''));
        $bankInst    = trim((string)($_POST['bank_instructions'] ?? ''));

        $stmt = $db->prepare("UPDATE `payment_settings` SET `is_enabled` = :en, `bank_details` = :det, `instructions` = :inst WHERE `gateway_code` = 'bank'");
        $stmt->execute(['en' => $bankEnabled, 'det' => $bankDetails, 'inst' => $bankInst]);

        Auth::setFlash('success', 'Payment gateway configurations saved successfully.');
        header('Location: ' . ADMIN_URL . '/payments');
        exit;
    }
}

