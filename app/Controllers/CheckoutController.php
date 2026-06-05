<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Core\Request;
use App\Core\Session;
use App\Services\PaymentService;
use App\Services\ShippingService;

class CheckoutController extends Controller {
    private $paymentService;
    private $shippingService;
    private $productModel;

    public function __construct() {
        $this->paymentService = new PaymentService();
        $this->shippingService = new ShippingService();
        $this->productModel = new Product();
    }

    public function index() {
        $userId = Session::get('user_id');
        if (!$userId) return $this->redirect('/login');

        // Check if this is a Buy Now flow
        $buyNow = Session::get('buy_now_product');
        $selectedIds = Session::get('selected_checkout_items');
        
        if ($buyNow) {
            $items = [];
            $p = $this->productModel->getWithDetails($buyNow['product_id']);
            if ($p) {
                $p['quantity'] = $buyNow['quantity'];
                $p['subtotal'] = $p['price'] * $buyNow['quantity'];
                $items[] = $p;
                $total = $p['subtotal'];
            } else {
                Session::remove('buy_now_product');
                return $this->redirect('/products');
            }
        } else {
            $cart = Session::get('cart', []);
            if (empty($cart)) return $this->redirect('/cart');

            $total = 0;
            $items = [];
            foreach ($cart as $id => $qty) {
                // If specific items were selected, skip others
                if ($selectedIds !== null && !in_array($id, $selectedIds)) continue;

                $p = $this->productModel->getWithDetails($id);
                if ($p) {
                    $p['quantity'] = $qty;
                    $p['subtotal'] = $p['price'] * $qty;
                    $items[] = $p;
                    $total += $p['subtotal'];
                }
            }

            if (empty($items)) return $this->redirect('/cart');
        }

        // Get provinces for RajaOngkir
        $provinces = $this->shippingService->getProvinces();

        return $this->view('checkout.index', [
            'items' => $items,
            'total' => $total,
            'provinces' => $provinces,
            'title' => 'Checkout',
            'isBuyNow' => (bool)$buyNow,
            'userName' => Session::get('user_name'),
            'midtransClientKey' => $_ENV['MIDTRANS_CLIENT_KEY'],
            'midtransIsProduction' => ($_ENV['MIDTRANS_IS_PRODUCTION'] ?? 'false') === 'true'
        ]);
    }

    public function prepare(Request $request) {
        $data = $request->getBody();
        $selectedItems = $data['selected_items'] ?? null;
        
        if ($selectedItems) {
            Session::set('selected_checkout_items', $selectedItems);
        } else {
            Session::remove('selected_checkout_items');
        }

        return $this->redirect('/checkout');
    }

    public function buyNow(Request $request) {
        $data = $request->getBody();
        if (empty($data['product_id'])) {
            return $this->redirect('/products');
        }

        Session::remove('selected_checkout_items');
        Session::set('buy_now_product', [
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'] ?? 1
        ]);

        return $this->redirect('/checkout');
    }

    public function process(Request $request) {
        error_log("Checkout hit: " . date('Y-m-d H:i:s'));
        $userId = Session::get('user_id');
        error_log("User ID: " . ($userId ?? 'None'));
        if (!$userId) return $this->json(['error' => 'Unauthorized'], 401);

        $data = $request->getBody();
        error_log("Payload: " . json_encode($data));
        $isBuyNow = ($data['is_buy_now'] === true || $data['is_buy_now'] === 'true');
        
        // 1. Re-calculate items from session (Security: don't trust frontend price)
        $items = [];
        $totalAmount = 0;

        if ($isBuyNow) {
            $buyNow = Session::get('buy_now_product');
            if (!$buyNow) return $this->json(['error' => 'Buy now session expired'], 400);
            $p = $this->productModel->getWithDetails($buyNow['product_id']);
            if ($p) {
                $p['quantity'] = $buyNow['quantity'];
                $p['total'] = $p['price'] * $buyNow['quantity'];
                $items[] = $p;
                $totalAmount = $p['total'];
            }
        } else {
            $cart = Session::get('cart', []);
            $selectedIds = Session::get('selected_checkout_items');
            foreach ($cart as $id => $qty) {
                if ($selectedIds !== null && !in_array($id, $selectedIds)) continue;
                $p = $this->productModel->getWithDetails($id);
                if ($p) {
                    $p['quantity'] = $qty;
                    $p['total'] = $p['price'] * $qty;
                    $items[] = $p;
                    $totalAmount += $p['total'];
                }
            }
        }

        if (empty($items)) return $this->json(['error' => 'No items found'], 400);

        // 1.5 Validate Stock
        foreach ($items as $item) {
            if ($item['stock'] < $item['quantity']) {
                return $this->json([
                    'error' => "Stok tidak mencukupi untuk {$item['name']}. Sisa stok: {$item['stock']}.",
                    'insufficient_stock' => true
                ], 400);
            }
        }

        // 2. Validate Shipping
        $shippingCost = (float)($data['shipping_cost'] ?? 0);
        $grandTotal = $totalAmount + $shippingCost;
        $shippingAddress = ($data['address'] ?? '') . ', ' . ($data['city_name'] ?? '') . ', ' . ($data['province_name'] ?? '') . ' ' . ($data['postal_code'] ?? '');

        // 3. Create Order in DB
        $orderId = 'ORD-' . strtoupper(substr(uniqid(), -8)) . '-' . rand(100, 999);
        
        $db = \App\Core\Database::getInstance();
        $db->beginTransaction();

        try {
            \App\Models\Order::create([
                'id' => $orderId,
                'user_id' => $userId,
                'total_amount' => $totalAmount,
                'shipping_cost' => $shippingCost,
                'grand_total' => $grandTotal,
                'status' => 'pending',
                'shipping_address' => $shippingAddress
            ]);

            foreach ($items as $item) {
                \App\Models\Order::addItem([
                    'order_id' => $orderId,
                    'product_id' => $item['id'],
                    'vendor_id' => $item['vendor_id'],
                    'variant_id' => null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['total']
                ]);

                // Reduce Stock - Throw exception if it fails (e.g. stock became insufficient)
                if (!$this->productModel->reduceStock($item['id'], $item['quantity'])) {
                    throw new \Exception("Stok tidak mencukupi untuk '{$item['name']}'. Silakan periksa keranjang Anda.");
                }
            }

            // Trigger initial "Order Placed" notification for vendors
            \App\Services\NotificationService::notifyOrderStatusChange($orderId, 'pending', 'vendor');

            // 4. Create Midtrans Transaction
            $snapToken = $this->paymentService->createTransaction($orderId, $grandTotal, [
                'first_name' => Session::get('user_name') ?? 'Customer',
                'email' => Session::get('user_email') ?? 'customer@example.com',
                'phone' => $data['phone'] ?? ''
            ]);

            if (!$snapToken) throw new \Exception("Gagal membuat token pembayaran.");
            
            $db->commit();
            error_log("Order created and stock reduced: $orderId");

            // 5. Cleanup
            if ($isBuyNow) {
                Session::remove('buy_now_product');
            } else {
                $cart = Session::get('cart', []);
                $selectedIds = Session::get('selected_checkout_items');
                if ($selectedIds) {
                    foreach ($selectedIds as $id) unset($cart[$id]);
                    Session::set('cart', $cart);
                    Session::remove('selected_checkout_items');
                } else {
                    Session::remove('cart');
                }
            }

            return $this->json([
                'snap_token' => $snapToken,
                'order_id' => $orderId
            ]);

        } catch (\Exception $e) {
            $db->rollBack();
            error_log("Checkout Failed: " . $e->getMessage());
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
