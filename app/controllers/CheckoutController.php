<?php
/**
 * JayArt Checkout Controller
 * Multi-Payment Handling: Razorpay, Direct UPI Dynamic QR, COD, Bank Transfer
 */

declare(strict_types=1);

class CheckoutController extends Controller {
    public function index(): void {
        $items = Cart::getItems();
        if (empty($items)) {
            $this->redirect('/cart');
            return;
        }

        $paymentModel = new Payment();
        $gateways = $paymentModel->getEnabledGateways();

        $subtotal = Cart::getSubtotal();
        $discount = Cart::getDiscountAmount();
        $shipping = Cart::getShippingFee();
        $total = Cart::getTotal();

        // User & saved addresses if logged in
        $currentUser = Auth::user();
        $savedAddresses = [];
        if ($currentUser) {
            $userModel = new User();
            $savedAddresses = $userModel->getAddresses((int)$currentUser['id']);
        }

        // Razorpay Key if active
        $razorpay = $paymentModel->getGateway('razorpay');
        $upi = $paymentModel->getGateway('upi');
        $bank = $paymentModel->getGateway('bank');

        $this->render('checkout/index', [
            'items'          => $items,
            'cartItems'      => $items,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'discountAmount' => $discount,
            'shipping'       => $shipping,
            'shippingFee'    => $shipping,
            'taxAmount'      => 0.00,
            'total'          => $total,
            'appliedCoupon'  => $_SESSION['applied_coupon'] ?? null,
            'gateways'       => $gateways,
            'currentUser'    => $currentUser,
            'savedAddresses' => $savedAddresses,
            'razorpay'       => $razorpay,
            'upi'            => $upi,
            'bank'           => $bank,
            'seo'            => ['title' => 'Secure Artwork Acquisition Checkout | JayArt']
        ]);
    }

    public function process(): void {
        $this->validateCSRF();

        $items = Cart::getItems();
        if (empty($items)) {
            $this->redirect('/cart');
            return;
        }

        // Validate customer shipping details
        $customerName  = trim((string)($_POST['customer_name'] ?? ''));
        $customerEmail = trim((string)($_POST['customer_email'] ?? ''));
        $customerPhone = trim((string)($_POST['customer_phone'] ?? ''));
        $address1      = trim((string)($_POST['address_line1'] ?? $_POST['address'] ?? ''));
        $address2      = trim((string)($_POST['address_line2'] ?? ''));
        $city          = trim((string)($_POST['city'] ?? ''));
        $state         = trim((string)($_POST['state'] ?? ''));
        $postalCode    = trim((string)($_POST['postal_code'] ?? $_POST['pincode'] ?? ''));
        $country       = trim((string)($_POST['country'] ?? 'India'));
        $paymentMethod = trim((string)($_POST['payment_method'] ?? 'cod'));
        $orderNotes    = trim((string)($_POST['notes'] ?? ''));

        if (empty($customerName) || empty($customerEmail) || empty($customerPhone) || empty($address1) || empty($city) || empty($postalCode)) {
            $_SESSION['old_checkout'] = $_POST;
            $_SESSION['flash_error'] = 'Please complete all required shipping fields.';
            $this->redirect('/checkout');
            return;
        }

        $subtotal = Cart::getSubtotal();
        $discount = Cart::getDiscountAmount();
        $shipping = Cart::getShippingFee();
        $total    = Cart::getTotal();

        $shippingAddress = [
            'recipient_name' => $customerName,
            'phone'          => $customerPhone,
            'address_line1'  => $address1,
            'address_line2'  => $address2,
            'city'           => $city,
            'state'          => $state,
            'postal_code'    => $postalCode,
            'country'        => $country,
        ];

        // Handle Payment Receipt Screenshot upload if UPI/Bank Transfer
        $receiptPath = null;
        if (!empty($_FILES['payment_receipt']['name'])) {
            $ext = strtolower(pathinfo($_FILES['payment_receipt']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'pdf'], true)) {
                $newName = 'receipt_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $dest = UPLOADS_PATH . '/payments/' . $newName;
                if (move_uploaded_file($_FILES['payment_receipt']['tmp_name'], $dest)) {
                    $receiptPath = 'uploads/payments/' . $newName;
                }
            }
        }

        $paymentRef = trim((string)($_POST['payment_reference'] ?? $_POST['upi_utr_number'] ?? ''));
        $initialPaymentStatus = in_array($paymentMethod, ['upi', 'bank_transfer']) ? 'pending' : 'pending';

        $orderModel = new Order();
        $orderId = $orderModel->createOrder([
            'user_id'           => (Auth::id() > 0) ? (int)Auth::id() : null,
            'customer_name'     => $customerName,
            'customer_email'    => $customerEmail,
            'customer_phone'    => $customerPhone,
            'shipping_address'  => $shippingAddress,
            'subtotal'          => $subtotal,
            'tax_amount'        => 0.00,
            'shipping_fee'      => $shipping,
            'discount_amount'   => $discount,
            'coupon_code'       => $_SESSION['applied_coupon']['code'] ?? null,
            'total_amount'      => $total,
            'payment_method'    => $paymentMethod,
            'payment_status'    => $initialPaymentStatus,
            'payment_reference' => $paymentRef,
            'notes'             => $orderNotes,
        ], $items);

        if (!$orderId) {
            $_SESSION['flash_error'] = 'Could not generate artwork acquisition order. Please try again.';
            $this->redirect('/checkout');
            return;
        }

        // Attach uploaded receipt if present
        if ($receiptPath) {
            $orderModel->update($orderId, ['payment_receipt' => $receiptPath]);
        }

        $order = $orderModel->find($orderId);
        $orderNumber = $order['order_number'];

        // Send Order Confirmation Email via Mailer with itemized breakdown
        $shippingText = ((float)$shipping > 0) ? Validator::formatPrice((float)$shipping) : 'FREE (Complimentary)';
        $emailBody = "<h3>Dear {$customerName},</h3>
                      <p>Thank you for acquiring original art from JayArt. We have received your order <strong>#{$orderNumber}</strong>.</p>
                      <table style='width:100%; max-width:500px; border-collapse:collapse; margin:15px 0; font-size:13px;'>
                          <tr><td style='padding:6px 0; color:#555;'>Subtotal:</td><td style='text-align:right; font-weight:bold;'>" . Validator::formatPrice($subtotal) . "</td></tr>";
        if ($discount > 0) {
            $emailBody .= "<tr><td style='padding:6px 0; color:#2D6A4F;'>Discount Applied:</td><td style='text-align:right; color:#2D6A4F; font-weight:bold;'>- " . Validator::formatPrice($discount) . "</td></tr>";
        }
        $emailBody .= "   <tr><td style='padding:6px 0; color:#555;'>Delivery Charges:</td><td style='text-align:right; font-weight:bold;'>{$shippingText}</td></tr>
                          <tr style='border-top:1px solid #ddd; font-size:15px;'><td style='padding:8px 0; font-weight:bold;'>Total Amount:</td><td style='text-align:right; font-weight:bold;'>" . Validator::formatPrice($total) . "</td></tr>
                          <tr><td style='padding:6px 0; color:#555;'>Payment Method:</td><td style='text-align:right; font-weight:bold;'>" . strtoupper($paymentMethod) . "</td></tr>
                      </table>
                      <p>Your artworks are currently being prepared with archival museum-grade transit packaging and insurance.</p>
                      <p>
                          <a href='" . SITE_URL . "/track/{$orderNumber}' style='display:inline-block; padding:8px 16px; background:#18181B; color:#fff; text-decoration:none; border-radius:6px; font-size:12px; margin-right:8px;'>Track Delivery</a>
                          <a href='" . SITE_URL . "/profile/invoice/{$orderId}' style='display:inline-block; padding:8px 16px; background:#E2B868; color:#18181B; text-decoration:none; border-radius:6px; font-size:12px; font-weight:bold;'>View / Print Bill</a>
                      </p>";
        
        Mailer::send($customerEmail, "Your JayArt Acquisition Order #{$orderNumber}", $emailBody, $customerName);

        // Clear cart
        Cart::clear();

        // Redirect to Order Success Celebration Page
        $this->redirect('/checkout/success/' . $orderNumber);
    }

    public function success(string $orderNumber = '', ?string $order_number = null): void {
        $orderNumber = !empty($orderNumber) ? $orderNumber : ($order_number ?? '');
        $orderModel = new Order();
        $order = $orderModel->findByOrderNumber($orderNumber);

        if (!$order) {
            $this->redirect('/');
            return;
        }

        $this->render('checkout/success', [
            'order' => $order,
            'seo'   => ['title' => 'Artwork Acquisition Confirmed | JayArt']
        ]);
    }

    public function razorpayVerify(): void {
        $paymentId = trim((string)($_POST['razorpay_payment_id'] ?? ''));
        $orderId   = trim((string)($_POST['razorpay_order_id'] ?? ''));
        $signature = trim((string)($_POST['razorpay_signature'] ?? ''));
        $localOrderNum = trim((string)($_POST['local_order_number'] ?? ''));

        $paymentModel = new Payment();
        $rzp = $paymentModel->getGateway('razorpay');
        $secret = $rzp['key_secret'] ?? '';

        $valid = Payment::verifyRazorpaySignature($orderId, $paymentId, $signature, $secret);

        if ($valid) {
            $orderModel = new Order();
            $order = $orderModel->findByOrderNumber($localOrderNum);
            if ($order) {
                $orderModel->updatePaymentStatus((int)$order['id'], 'paid', $paymentId);
            }
            $this->json(['success' => true]);
        } else {
            $this->json(['success' => false, 'message' => 'Signature verification failed.']);
        }
    }
}

