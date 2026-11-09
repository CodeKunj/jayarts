<?php
/**
 * JayArt Payment Gateway Model
 * Razorpay, Direct Dynamic UPI QR, COD, Bank Transfer
 */

declare(strict_types=1);

class Payment extends Model {
    protected string $table = 'payment_settings';

    /**
     * Default Gateway Fallbacks (Guarantees Checkout Always Renders Even If DB Unseeded)
     */
    public static function defaultGateways(): array {
        return [
            'razorpay' => [
                'gateway_code'  => 'razorpay',
                'gateway_name'  => 'Credit / Debit Card / Netbanking (Razorpay)',
                'is_enabled'    => 1,
                'is_test_mode'  => 1,
                'key_id'        => '',
                'key_secret'    => '',
                'sort_order'    => 1
            ],
            'upi' => [
                'gateway_code'  => 'upi',
                'gateway_name'  => 'Direct Dynamic UPI QR (GPay, PhonePe, Paytm)',
                'is_enabled'    => 1,
                'upi_vpa'       => 'jayart@icici',
                'merchant_name' => 'JayArt Fine Atelier',
                'sort_order'    => 2
            ],
            'cod' => [
                'gateway_code'  => 'cod',
                'gateway_name'  => 'Cash on Delivery (COD)',
                'is_enabled'    => 1,
                'sort_order'    => 3
            ],
            'bank' => [
                'gateway_code'  => 'bank',
                'gateway_name'  => 'Direct Bank Wire (NEFT / RTGS / IMPS)',
                'is_enabled'    => 1,
                'account_name'  => 'JayArt Fine Art Atelier LLP',
                'bank_name'     => 'HDFC Bank Ltd',
                'account_no'    => '50200084920194',
                'ifsc_code'     => 'HDFC0000240',
                'branch'        => 'Heritage Arts Branch',
                'sort_order'    => 4
            ]
        ];
    }

    /**
     * Get All Enabled Gateways for Checkout (Keyed Associatively by gateway_code)
     */
    public function getEnabledGateways(): array {
        $keyed = [];
        if ($this->db !== null) {
            try {
                $rows = $this->fetchAll(
                    "SELECT * FROM `payment_settings` WHERE `is_enabled` = 1 ORDER BY `sort_order` ASC, `id` ASC"
                );
                if (!empty($rows)) {
                    foreach ($rows as $r) {
                        if (!empty($r['gateway_code'])) {
                            $keyed[$r['gateway_code']] = $r;
                        }
                    }
                    return $keyed;
                }
            } catch (\Throwable $e) {
                error_log("Payment getEnabledGateways notice: " . $e->getMessage());
            }
        }
        return self::defaultGateways();
    }

    /**
     * Get Gateway by Code (razorpay, upi, cod, bank, stripe)
     */
    public function getGateway(string $code): ?array {
        if ($this->db !== null) {
            try {
                $row = $this->fetch(
                    "SELECT * FROM `payment_settings` WHERE `gateway_code` = :code LIMIT 1",
                    ['code' => $code]
                );
                if ($row) return $row;
            } catch (\Throwable $e) {
                error_log("Payment getGateway notice: " . $e->getMessage());
            }
        }
        $defaults = self::defaultGateways();
        return $defaults[$code] ?? null;
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

