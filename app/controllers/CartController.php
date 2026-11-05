<?php
/**
 * JayArt Cart Controller
 */

declare(strict_types=1);

class CartController extends Controller {
    public function index(): void {
        $items = Cart::getItems();
        $subtotal = Cart::getSubtotal();
        $discount = Cart::getDiscountAmount();
        $shipping = Cart::getShippingFee();
        $total = Cart::getTotal();
        $appliedCoupon = $_SESSION['applied_coupon'] ?? null;

        $this->render('cart/index', [
            'items'          => $items,
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'discountAmount' => $discount,
            'shipping'       => $shipping,
            'shippingFee'    => $shipping,
            'taxAmount'      => 0.00,
            'total'          => $total,
            'appliedCoupon'  => $appliedCoupon,
            'seo'            => ['title' => 'Your Collection Cart | JayArt Gallery']
        ]);
    }

    public function add(): void {
        $productId = (int)($_POST['product_id'] ?? 0);
        $qty = max(1, (int)($_POST['quantity'] ?? 1));
        $frame = trim((string)($_POST['frame_chosen'] ?? 'Rolled Canvas'));

        $res = Cart::add($productId, $qty, $frame);
        $this->json($res);
    }

    public function update(): void {
        $itemKey = trim((string)($_POST['item_key'] ?? $_POST['cart_id'] ?? ''));
        $qty = (int)($_POST['quantity'] ?? 1);

        $success = Cart::update($itemKey, $qty);
        $this->json([
            'success'   => $success,
            'cart_count'=> Cart::getCount(),
            'subtotal'  => Cart::getSubtotal(),
            'total'     => Cart::getTotal(),
        ]);
    }

    public function remove(): void {
        $itemKey = trim((string)($_POST['item_key'] ?? $_POST['cart_id'] ?? ''));
        $success = Cart::remove($itemKey);
        $this->json([
            'success'   => $success,
            'cart_count'=> Cart::getCount(),
        ]);
    }

    public function applyCoupon(): void {
        $code = trim((string)($_POST['coupon_code'] ?? $_POST['code'] ?? ''));
        if (empty($code)) {
            $this->json(['success' => false, 'message' => 'Please enter a coupon code.']);
            return;
        }

        $res = Cart::applyCoupon($code);
        $this->json($res);
    }

    public function removeCoupon(): void {
        $res = Cart::removeCoupon();
        $this->json($res);
    }
}

