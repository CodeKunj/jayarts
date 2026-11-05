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
        $address1      = trim((string)($_POST['address_line1'] ?? ''));
        $address2      = trim((string)($_POST['address_line2'] ?? ''));
        $city          = trim((string)($_POST['city'] ?? ''));
        $state         = trim((string)($_POST['state'] ?? ''));
        $postalCode    = trim((string)($_POST['postal_code'] ?? ''));
        $country       = trim((string)($_POST['country'] ?? 'India'));
        $paymentMethod = trim((string)($_POST['payment_method'] ?? 'cod'));
        $orderNotes    = trim((string)($_POST['notes'] ?? ''));

        if (empty($customerName) || empty($customerEmail) || empty($customerPhone) || empty($address1) || empty($city) || empty($postalCode)) {
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

        $paymentRef = trim((string)($_POST['payment_reference'] ?? ''));
        $initialPaymentStatus = in_array($paymentMethod, ['upi', 'bank_transfer']) ? 'pending' : 'pending';

        $orderModel = new Order();
        $orderId = $orderModel->createOrder([
            'user_id'           => Auth::id(),
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

        // Send Order Confirmation Email via Mailer
        $emailBody = "<h3>Dear {$customerName},</h3>
                      <p>Thank you for acquiring original art from JayArt. We have received your order <strong>#{$orderNumber}</strong>.</p>
                      <p><strong>Total Amount:</strong> " . Validator::formatPrice($total) . "</p>
                      <p><strong>Payment Method:</strong> " . strtoupper($paymentMethod) . "</p>
                      <p>Your artworks are currently being prepared with archival museum-grade packaging and insurance.</p>
                      <p><a href='" . SITE_URL . "/track/{$orderNumber}' class='btn'>Track Artwork Delivery</a></p>";
        
        Mailer::send($customerEmail, "Your JayArt Acquisition Order #{$orderNumber}", $emailBody, $customerName);

        // Clear cart
        Cart::clear();

        // Redirect to Order Success Celebration Page
        $this->redirect('/checkout/success/' . $orderNumber);
    }

    public function success(string $orderNumber): void {
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

