<?php

namespace App\Controllers\Vendor;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class OrderController extends Controller {
    public function index() {
        $session  = new Session();
        $userId   = $session->get('user_id');
        $db       = Database::getInstance()->getConnection();

        // 1. AUTO-COMPLETE LOGIC: Move 'shipped' or 'delivered' orders older than 3 days to 'completed'
        $stmtAuto = $db->prepare("SELECT id FROM orders WHERE status IN ('shipped', 'delivered') AND updated_at < DATE_SUB(NOW(), INTERVAL 3 DAY)");
        $stmtAuto->execute();
        $toComplete = $stmtAuto->fetchAll();
        foreach ($toComplete as $ord) {
            $db->prepare("UPDATE orders SET status = 'completed', updated_at = NOW() WHERE id = ?")->execute([$ord['id']]);
            \App\Services\WalletService::creditVendorWallets($ord['id']);
            \App\Services\NotificationService::notifyOrderStatusChange($ord['id'], 'completed', 'both');
        }

        // 2. AUTO-CANCEL LOGIC: Move 'pending' orders older than 24 hours to 'cancelled'
        $stmtCancel = $db->prepare("SELECT id FROM orders WHERE status = 'pending' AND created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)");
        $stmtCancel->execute();
        $toCancel = $stmtCancel->fetchAll();
        foreach ($toCancel as $ord) {
            $db->prepare("UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = ?")->execute([$ord['id']]);
            \App\Services\NotificationService::notifyOrderStatusChange($ord['id'], 'cancelled', 'both');
        }

        // Get vendor
        $vStmt = $db->prepare("SELECT id, store_name FROM vendors WHERE user_id = ?");
        $vStmt->execute([$userId]);
        $vendor = $vStmt->fetch();

        if (!$vendor) return $this->redirect('/vendor/onboarding');

        $vendorId = $vendor['id'];

        // Status filter
        $statusFilter = $_GET['status'] ?? '';
        $allowedStatuses = ['pending','paid','processing','shipped','delivered','completed','cancelled'];
        $whereStatus = (in_array($statusFilter, $allowedStatuses)) ? "AND o.status = '$statusFilter'" : '';

        $stmt = $db->prepare("
            SELECT o.id, o.user_id, o.status, o.created_at, o.shipping_address, o.grand_total,
                   u.name AS customer_name, u.email AS customer_email,
                   SUM(oi.total) AS vendor_subtotal, COUNT(oi.id) AS item_count
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            JOIN users u ON u.id = o.user_id
            WHERE oi.vendor_id = ? $whereStatus
            GROUP BY o.id, o.user_id, o.status, o.created_at, o.shipping_address, o.grand_total, u.name, u.email
            ORDER BY o.created_at DESC
            LIMIT 50
        ");
        $stmt->execute([$vendorId]);
        $orders = $stmt->fetchAll();

        // Fetch items and shipment for each order to support detail modal
        if (!empty($orders)) {
            $orderIds = array_column($orders, 'id');
            $placeholders = str_repeat('?,', count($orderIds) - 1) . '?';
            
            // Fetch items
            $iStmt = $db->prepare("
                SELECT oi.*, p.name AS product_name, 
                       (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) AS product_image
                FROM order_items oi
                JOIN products p ON p.id = oi.product_id
                WHERE oi.order_id IN ($placeholders) AND oi.vendor_id = ?
            ");
            $iStmt->execute(array_merge($orderIds, [$vendorId]));
            $allItems = $iStmt->fetchAll();

            // Fetch shipments
            $sStmt = $db->prepare("
                SELECT * FROM shipments WHERE order_id IN ($placeholders)
            ");
            $sStmt->execute($orderIds);
            $allShipments = $sStmt->fetchAll();

            // Associate items and shipment to orders
            foreach ($orders as &$order) {
                $order['items'] = array_values(array_filter($allItems, function($item) use ($order) {
                    return $item['order_id'] === $order['id'];
                }));
                
                $shipment = array_filter($allShipments, function($s) use ($order) {
                    return $s['order_id'] === $order['id'];
                });
                $order['shipment'] = !empty($shipment) ? array_values($shipment)[0] : null;
            }
        }

        // Counts per status
        $countsStmt = $db->prepare("
            SELECT o.status, COUNT(DISTINCT o.id) AS cnt
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            WHERE oi.vendor_id = ?
            GROUP BY o.status
        ");
        $countsStmt->execute([$vendorId]);
        $statusCounts = [];
        foreach ($countsStmt->fetchAll() as $row) {
            $statusCounts[$row['status']] = $row['cnt'];
        }

        return $this->view('vendor.orders', [
            'title'        => 'Pesanan — ' . $vendor['store_name'],
            'pageTitle'    => 'Manajemen Pesanan',
            'vendor'       => $vendor,
            'orders'       => $orders,
            'statusCounts' => $statusCounts,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function updateStatus() {
        $db       = Database::getInstance()->getConnection();
        $session  = new Session();
        $userId   = $session->get('user_id');
        $orderId  = $_POST['order_id'] ?? '';
        $newStatus = $_POST['status'] ?? '';
        $courier   = $_POST['courier'] ?? '';
        $tracking  = $_POST['tracking_number'] ?? '';
        $allowed   = ['processing', 'shipped', 'delivered', 'cancelled'];

        if (!in_array($newStatus, $allowed)) {
            Session::setFlash('error', 'Status tidak valid.');
            return $this->redirect('/vendor/orders');
        }

        // Verify this order belongs to this vendor
        $vStmt = $db->prepare("SELECT id FROM vendors WHERE user_id = ?");
        $vStmt->execute([$userId]);
        $vendor = $vStmt->fetch();

        $checkStmt = $db->prepare("SELECT COUNT(*) FROM order_items WHERE order_id = ? AND vendor_id = ?");
        $checkStmt->execute([$orderId, $vendor['id']]);
        if (!$checkStmt->fetchColumn()) {
            Session::setFlash('error', 'Akses ditolak.');
            return $this->redirect('/vendor/orders');
        }

        // Handle shipping info
        if ($newStatus === 'shipped') {
            if (empty($courier) || empty($tracking)) {
                Session::setFlash('error', 'Kurir dan Nomor Resi wajib diisi untuk pengiriman.');
                return $this->redirect('/vendor/orders?status=processing');
            }
            
            // Upsert shipment record
            $sStmt = $db->prepare("SELECT id FROM shipments WHERE order_id = ?");
            $sStmt->execute([$orderId]);
            if ($sStmt->fetch()) {
                $db->prepare("UPDATE shipments SET courier = ?, tracking_number = ?, status = 'shipped' WHERE order_id = ?")
                   ->execute([$courier, $tracking, $orderId]);
            } else {
                $db->prepare("INSERT INTO shipments (order_id, courier, tracking_number, status) VALUES (?, ?, ?, 'shipped')")
                   ->execute([$orderId, $courier, $tracking]);
            }
        }

        $db->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?")->execute([$newStatus, $orderId]);
        
        // Notify user about status change
        \App\Services\NotificationService::notifyOrderStatusChange($orderId, $newStatus, 'buyer');

        Session::setFlash('success', 'Status order berhasil diperbarui!');
        return $this->redirect('/vendor/orders?status=' . $newStatus);
    }

    public function delete() {
        $db       = Database::getInstance()->getConnection();
        $session  = new Session();
        $userId   = $session->get('user_id');
        $orderId  = $_POST['order_id'] ?? '';

        // Verify vendor
        $vStmt = $db->prepare("SELECT id FROM vendors WHERE user_id = ?");
        $vStmt->execute([$userId]);
        $vendor = $vStmt->fetch();

        if (!$vendor) {
            Session::setFlash('error', 'Vendor tidak ditemukan.');
            return $this->redirect('/vendor/orders');
        }

        // Check if order belongs to vendor AND is pending
        $checkStmt = $db->prepare("SELECT o.status FROM orders o JOIN order_items oi ON oi.order_id = o.id WHERE o.id = ? AND oi.vendor_id = ?");
        $checkStmt->execute([$orderId, $vendor['id']]);
        $order = $checkStmt->fetch();

        if (!$order || $order['status'] !== 'pending') {
            Session::setFlash('error', 'Hanya pesanan pending yang bisa dibatalkan.');
            return $this->redirect('/vendor/orders?status=pending');
        }

        // Update status to cancelled instead of deleting
        $db->prepare("UPDATE orders SET status = 'cancelled', updated_at = NOW() WHERE id = ?")->execute([$orderId]);

        // Notify user about cancellation
        \App\Services\NotificationService::notifyOrderStatusChange($orderId, 'cancelled', 'buyer');

        Session::setFlash('success', 'Pesanan berhasil dibatalkan.');
        return $this->redirect('/vendor/orders?status=cancelled');
    }
}
