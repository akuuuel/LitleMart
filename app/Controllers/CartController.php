<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Core\Request;
use App\Core\Session;

class CartController extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = new Product();
    }

    public function index() {
        // Clear any Buy Now intent when users view their cart
        Session::remove('buy_now_product');
        
        $items = Session::get('cart', []);
        $cartItems  = [];
        $staleItems = []; // track product IDs that no longer exist
        $total = 0;

        foreach ($items as $productId => $quantity) {
            $product = $this->productModel->getWithDetails($productId);
            if ($product) {
                $images = $this->productModel->getImages($productId);
                $product['main_image'] = $images[0]['image_path'] ?? null;
                $product['quantity']   = $quantity;
                $product['subtotal']   = $product['price'] * $quantity;
                $cartItems[] = $product;
                $total += $product['subtotal'];
            } else {
                // Product no longer exists — mark for removal
                $staleItems[] = $productId;
            }
        }

        // Clean stale entries from the session so the badge stays accurate
        if (!empty($staleItems)) {
            $cleanCart = Session::get('cart', []);
            foreach ($staleItems as $staleId) {
                unset($cleanCart[$staleId]);
            }
            Session::set('cart', $cleanCart);
        }

        return $this->view('cart.index', [
            'items' => $cartItems,
            'total' => $total,
            'title' => 'Your Shopping Cart'
        ]);
    }

    public function add(Request $request) {
        $productId = $request->input('product_id');
        $quantity = (int)$request->input('quantity', 1);
        $isAjax = $request->isAjax() || $request->input('ajax') == 'true' || isset($_SERVER['HTTP_X_REQUESTED_WITH']);

        $product = $this->productModel->getWithDetails($productId);
        if (!$product) {
            if ($isAjax) return $this->json(['status' => 'error', 'message' => 'Product not found'], 404);
            return $this->redirect('/products');
        }

        $cart = Session::get('cart', []);
        $currentQty = isset($cart[$productId]) ? $cart[$productId] : 0;
        $newQty = $currentQty + $quantity;

        if ($product['stock'] < $newQty) {
            $msg = "Maaf, stok tidak mencukupi. Stok tersisa: {$product['stock']}";
            if ($isAjax) return $this->json(['status' => 'error', 'message' => $msg], 400);
            Session::setFlash('error', $msg);
            return $this->redirect('/products/' . $productId);
        }

        $cart[$productId] = $newQty;
        Session::set('cart', $cart);
        
        $count = 0;
        foreach($cart as $qty) $count += $qty;

        if ($isAjax) {
            return $this->json([
                'status' => 'success', 
                'message' => 'Product added to cart!', 
                'cart_count' => $count
            ]);
        }

        // Session::setFlash('success', 'Product added to cart!');
        return $this->redirect('/cart');
    }

    public function update(Request $request) {
        $productId = $request->input('product_id');
        $quantity = (int)$request->input('quantity');

        $product = $this->productModel->getWithDetails($productId);
        if (!$product) return $this->json(['success' => false, 'message' => 'Product not found'], 404);

        if ($quantity > $product['stock']) {
            return $this->json([
                'success' => false, 
                'message' => "Maaf, stok tidak mencukupi. Sisa stok: {$product['stock']}."
            ], 400);
        }

        $cart = Session::get('cart', []);
        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        Session::set('cart', $cart);
        return $this->json(['success' => true]);
    }


    public function remove(Request $request, $id) {
        $cart = Session::get('cart', []);
        unset($cart[$id]);
        Session::set('cart', $cart);
        return $this->redirect('/cart');
    }

    /**
     * API: Returns the real cart count from session.
     * Simple session read — no DB queries needed for badge count.
     */
    public function countCart() {
        $items = Session::get('cart', []);
        $count = 0;
        foreach ($items as $qty) {
            $count += (int)$qty;
        }

        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }
}
