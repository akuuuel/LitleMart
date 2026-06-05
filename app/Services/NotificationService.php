<?php

namespace App\Services;

use App\Core\Database;

class NotificationService {
    /**
     * Create a notification for a specific user.
     * 
     * @param string $userId The user who will receive the notification
     * @param string $title Title of the notification
     * @param string $message Detailed message
     * @param string $type info, success, warning, error
     */
    public static function send($userId, $title, $message, $type = 'info', $targetUrl = null) {
        $db = Database::getInstance()->getConnection();
        $id = bin2hex(random_bytes(16)); // Simple UUID simulation
        
        $stmt = $db->prepare("INSERT INTO notifications (id, user_id, title, message, type, target_url, is_read, created_at) VALUES (?, ?, ?, ?, ?, ?, 0, NOW())");
        $stmt->execute([$id, $userId, $title, $message, $type, $targetUrl]);

        // Push to Firebase for Real-time Alert
        self::pushToFirebase($userId, [
            'id' => $id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'targetUrl' => $targetUrl,
            'isRead' => false,
            'timestamp' => time() * 1000,
            'senderId' => 'system'
        ]);
    }

    private static function pushToFirebase($userId, $data) {
        $url = "https://litlemart-f4c8f-default-rtdb.asia-southeast1.firebasedatabase.app/notifications/$userId.json";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Fire-and-forget: timeout=1ms so PHP doesn't block waiting for Firebase response
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1500);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 1000);
        @curl_exec($ch);
        curl_close($ch);
    }

    /**
     * Notify buyer and/or vendor about an order status change.
     * 
     * @param string $orderId The order ID
     * @param string $newStatus The new status
     * @param string $targetSide 'buyer', 'vendor', or 'both'
     */
    public static function notifyOrderStatusChange($orderId, $newStatus, $targetSide = 'both') {
        $db = Database::getInstance()->getConnection();
        
        // 1. Get buyer info and product sample
        $stmt = $db->prepare("
            SELECT o.user_id as buyer_id, p.name as product_name
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            WHERE o.id = ?
            LIMIT 1
        ");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch();
        if (!$order) return;

        $shortId = substr($orderId, 0, 8);
        $buyerId = $order['buyer_id'];
        $productName = $order['product_name'];

        $statusLabels = [
            'pending'    => 'Menunggu Pembayaran',
            'paid'       => 'Pembayaran Diterima',
            'processing' => 'Sedang Diproses',
            'shipped'    => 'Dikirim',
            'delivered'  => 'Sampai di Tujuan',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan'
        ];

        $label = $statusLabels[$newStatus] ?? $newStatus;

        // 2. Notify Buyer
        if ($targetSide === 'both' || $targetSide === 'buyer') {
            $buyerTitle = "Status Pesanan #$shortId: $label";
            $buyerMsg = "Pesanan Anda ($productName dkk) kini berstatus: $label.";
            
            if ($newStatus === 'shipped') {
                $buyerMsg = "Pesanan Anda sedang dikirim oleh kurir. Silakan pantau status pengiriman.";
            } elseif ($newStatus === 'delivered') {
                $buyerMsg = "Hore! Pesanan Anda telah sampai di lokasi. Silakan konfirmasi penerimaan barang.";
            } elseif ($newStatus === 'cancelled') {
                $buyerMsg = "Maaf, pesanan Anda telah dibatalkan.";
            }

            $targetUrl = url('/orders'); // Default to orders list
            if ($newStatus === 'shipped' || $newStatus === 'delivered' || $newStatus === 'completed') {
                $targetUrl = url('/orders'); // Can be refined to specific order detail if route exists
            }

            self::send($buyerId, $buyerTitle, $buyerMsg, self::getTypeFromStatus($newStatus), $targetUrl);
        }

        // 3. Notify All Unique Vendors in this order
        if ($targetSide === 'both' || $targetSide === 'vendor') {
            $stmtVendors = $db->prepare("
                SELECT DISTINCT v.user_id 
                FROM order_items oi 
                JOIN vendors v ON oi.vendor_id = v.id 
                WHERE oi.order_id = ?
            ");
            $stmtVendors->execute([$orderId]);
            $vendors = $stmtVendors->fetchAll();

            foreach ($vendors as $v) {
                $vendorTitle = "Update Pesanan #$shortId: $label";
                $vendorMsg = "Status pesanan dari pembeli telah berubah menjadi: $label.";

                if ($newStatus === 'paid' || $newStatus === 'processing') {
                    $vendorMsg = "Pembayaran untuk pesanan #$shortId telah diterima! Silakan segera proses pesanan.";
                } elseif ($newStatus === 'completed') {
                    $vendorMsg = "Dana dari pesanan #$shortId telah diteruskan ke saldo Anda karena pesanan telah selesai.";
                }

                $targetUrl = url('/vendor/orders');
                self::send($v['user_id'], $vendorTitle, $vendorMsg, self::getTypeFromStatus($newStatus), $targetUrl);
            }
        }
    }

    private static function getTypeFromStatus($status) {
        switch ($status) {
            case 'paid':
            case 'delivered':
            case 'completed':
                return 'success';
            case 'cancelled':
                return 'error';
            case 'processing':
            case 'shipped':
            case 'pending':
                return 'info';
            default:
                return 'info';
        }
    }
}
