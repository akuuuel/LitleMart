<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Core\Database;

class OrderController extends Controller {
    public function success() {
        $orderId = $_GET['id'] ?? null;
        return $this->view('orders.status', [
            'title' => 'Payment Successful',
            'status' => 'success',
            'orderId' => $orderId,
            'message' => 'Thank you for your purchase! Your order is being processed.'
        ]);
    }

    public function pending() {
        $orderId = $_GET['id'] ?? null;
        return $this->view('orders.status', [
            'title' => 'Payment Pending',
            'status' => 'pending',
            'orderId' => $orderId,
            'message' => 'Your payment is pending. Please complete it as soon as possible.'
        ]);
    }

    public function index() {
        $userId = Session::get('user_id');
        if (!$userId) return $this->redirect('/login');

        $db = Database::getInstance()->getConnection();

        // 1. AUTO-COMPLETE: Only run if there are candidates (avoid scan if none)
        $stmtAuto = $db->prepare("SELECT id FROM orders WHERE status IN ('shipped', 'delivered') AND updated_at < DATE_SUB(NOW(), INTERVAL 3 DAY) LIMIT 20");
        $stmtAuto->execute();
        $toComplete = $stmtAuto->fetchAll();
        if (!empty($toComplete)) {
            foreach ($toComplete as $ord) {
                $db->prepare("UPDATE orders SET status = 'completed', updated_at = NOW() WHERE id = ?")->execute([$ord['id']]);
                \App\Services\WalletService::creditVendorWallets($ord['id']);
                \App\Services\NotificationService::notifyOrderStatusChange($ord['id'], 'completed', 'both');
            }
        }

        // 2. AUTO-CANCEL: Only run if there are candidates
        $stmtCancel = $db->prepare("SELECT id FROM orders WHERE status = 'pending' AND created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR) AND user_id = ? LIMIT 20");
        $stmtCancel->execute([$userId]);
        $toCancel = $stmtCancel->fetchAll();
        if (!empty($toCancel)) {
            foreach ($toCancel as $ord) {
                $db->prepare("UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = ?")->execute([$ord['id']]);
                \App\Services\NotificationService::notifyOrderStatusChange($ord['id'], 'cancelled', 'both');
            }
        }

        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
        $stmt->execute([$userId]);
        $orders = $stmt->fetchAll();

        if (empty($orders)) {
            return $this->view('orders.index', ['title' => 'My Orders', 'orders' => []]);
        }

        // Bulk fetch items, payments, shipments via IN() — eliminates N+1 queries
        $orderIds = array_column($orders, 'id');
        $placeholders = implode(',', array_fill(0, count($orderIds), '?'));

        $stmtItems = $db->prepare("
            SELECT oi.*, p.name as product_name, pi.image_path as product_image, v.store_name, v.id as vendor_id
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            JOIN vendors v ON oi.vendor_id = v.id
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
            WHERE oi.order_id IN ($placeholders)
        ");
        $stmtItems->execute($orderIds);
        $allItems = $stmtItems->fetchAll();

        $stmtPayments = $db->prepare("SELECT * FROM payments WHERE order_id IN ($placeholders)");
        $stmtPayments->execute($orderIds);
        $allPayments = $stmtPayments->fetchAll();

        $stmtShipments = $db->prepare("SELECT * FROM shipments WHERE order_id IN ($placeholders)");
        $stmtShipments->execute($orderIds);
        $allShipments = $stmtShipments->fetchAll();

        // Index by order_id for O(1) lookup
        $itemsByOrder    = [];
        $paymentByOrder  = [];
        $shipmentByOrder = [];
        foreach ($allItems    as $row) { $itemsByOrder[$row['order_id']][]  = $row; }
        foreach ($allPayments as $row) { $paymentByOrder[$row['order_id']]  = $row; }
        foreach ($allShipments as $row) { $shipmentByOrder[$row['order_id']] = $row; }

        foreach ($orders as &$order) {
            $order['items']    = $itemsByOrder[$order['id']]    ?? [];
            $order['payment']  = $paymentByOrder[$order['id']]  ?? null;
            $order['shipment'] = $shipmentByOrder[$order['id']] ?? null;
        }

        return $this->view('orders.index', [
            'title' => 'My Orders',
            'orders' => $orders
        ]);
    }

    public function confirmReceipt() {
        $orderId = $_POST['order_id'] ?? null;
        $userId = Session::get('user_id');

        if (!$orderId || !$userId) {
            Session::setFlash('error', 'Gagal memproses konfirmasi.');
            return $this->redirect('/orders');
        }

        $db = Database::getInstance()->getConnection();
        
        // Verify order belongs to user and is delivered
        $stmt = $db->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch();

        if (!$order || $order['status'] !== 'delivered') {
            Session::setFlash('error', 'Hanya pesanan yang sudah sampai (delivered) yang bisa dikonfirmasi.');
            return $this->redirect('/orders');
        }

        $db->prepare("UPDATE orders SET status = 'completed', updated_at = NOW() WHERE id = ?")->execute([$orderId]);
        
        // Credit the vendor(s) wallet
        \App\Services\WalletService::creditVendorWallets($orderId);
        
        // Notify both parties
        \App\Services\NotificationService::notifyOrderStatusChange($orderId, 'completed', 'vendor');

        Session::setFlash('success', 'Terima kasih! Pesanan Anda telah selesai.');
        return $this->redirect('/orders');
    }

    public function delete() {
        $orderId = $_POST['order_id'] ?? null;
        $userId = Session::get('user_id');

        if (!$orderId || !$userId) {
            Session::setFlash('error', 'Gagal memproses permintaan.');
            return $this->redirect('/orders');
        }

        $db = Database::getInstance()->getConnection();
        
        // Fetch order to check status
        $stmt = $db->prepare("SELECT status FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch();

        if (!$order) {
            Session::setFlash('error', 'Pesanan tidak ditemukan.');
            return $this->redirect('/orders');
        }

        if ($order['status'] === 'pending') {
            // Cancel the order instead of deleting it
            $stmt = $db->prepare("UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = ? AND user_id = ?");
            $stmt->execute([$orderId, $userId]);
            
            // Notify vendor about cancellation
            \App\Services\NotificationService::notifyOrderStatusChange($orderId, 'cancelled', 'vendor');
            
            Session::setFlash('success', 'Pesanan berhasil dibatalkan.');
        } else if ($order['status'] === 'completed' || $order['status'] === 'cancelled') {
            // Safe to delete from history
            $stmt = $db->prepare("DELETE FROM orders WHERE id = ? AND user_id = ?");
            $stmt->execute([$orderId, $userId]);
            Session::setFlash('success', 'Riwayat pesanan berhasil dihapus.');
        } else {
            Session::setFlash('error', 'Pesanan yang sedang diproses tidak dapat dibatalkan.');
        }

        return $this->redirect('/orders');
    }

    public function getSnapToken() {
        $orderId = $_GET['id'] ?? null;
        $userId = Session::get('user_id');

        if (!$orderId || !$userId) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$orderId, $userId]);
        $order = $stmt->fetch();

        if (!$order) {
            return $this->json(['error' => 'Order not found'], 404);
        }

        if ($order['status'] !== 'pending') {
            return $this->json(['error' => 'Order is already paid or cancelled'], 400);
        }

        // Regenerate Snap Token
        try {
            $paymentService = new \App\Services\PaymentService();
            // Append suffix to avoid 'duplicate order_id' error in Midtrans
            $midtransOrderId = $order['id'] . '-' . rand(100, 999);
            
            $snapToken = $paymentService->createTransaction($midtransOrderId, $order['grand_total'], [
                'first_name' => Session::get('user_name') ?? 'Customer',
                'email' => Session::get('user_email') ?? 'customer@example.com',
                'phone' => '' 
            ]);

            return $this->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
