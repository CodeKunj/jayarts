<?php
/**
 * JayArt Payment Gateway Model
 * Razorpay, Direct Dynamic UPI QR, COD, Bank Transfer
 */

declare(strict_types=1);

class Payment extends Model {
    protected string $table = 'payment_settings';

    /**
     * Get All Enabled Gateways for Checkout
     */
    public function getEnabledGateways(): array {
        return $this->fetchAll(
            "SELECT * FROM `payment_settings` WHERE `is_enabled` = 1 ORDER BY `sort_order` ASC, `id` ASC"
        );
    }

    /**
     * Get Gateway by Code (razorpay, upi, cod, bank, stripe)
     */
    public function getGateway(string $code): ?array {
        return $this->fetch(
            "SELECT * FROM `payment_settings` WHERE `gateway_code` = :code LIMIT 1",
            ['code' => $code]
        );
    }

    /**
     * Verify Razorpay Signature
     */
    public static function verifyRazorpaySignature(string $orderId, string $paymentId, string $signature, string $secret): bool {
        $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $secret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Generate Dynamic UPI Deep Link & QR Code URL
     */
    public static function buildUPIData(string $vpa, string $merchantName, float $amount, string $orderNumber): array {
        $encodedName = rawurlencode($merchantName);
        $encodedNote = rawurlencode("JayArt Order " . $orderNumber);
        $formattedAmount = number_format($amount, 2, '.', '');

        // Standard NPCI UPI URI
        $upiUri = "upi://pay?pa={$vpa}&pn={$encodedName}&am={$formattedAmount}&cu=INR&tn={$encodedNote}";

        // Fast high-availability QR image URL
        $qrImageUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . rawurlencode($upiUri);

        return [
            'upi_uri'     => $upiUri,
            'qr_image'    => $qrImageUrl,
            'vpa'         => $vpa,
            'amount'      => $formattedAmount,
            'order_number'=> $orderNumber
        ];
    }
}

