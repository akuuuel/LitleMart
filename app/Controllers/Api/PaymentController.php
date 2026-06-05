<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Database;

class PaymentController extends Controller {

    /**
     * Handle payment gateway webhook callbacks (e.g. Midtrans)
     */
    public function finish() {
        // Try to get from JSON first
        $rawBody = file_get_contents('php://input');
        $payload = json_decode($rawBody, true);
        
        // Fallback to GET parameters (standard Midtrans redirect)
        $orderId = $payload['order_id'] ?? $_GET['order_id'] ?? null;
        $status = $payload['status'] ?? $_GET['transaction_status'] ?? 'pending';

        if ($orderId) {
            $db = Database::getInstance()->getConnection();
            
            // Handle retry suffix (e.g., ORD-ABC-123-456 -> ORD-ABC-123)
            $stmtOrder = $db->prepare("SELECT id, status FROM orders WHERE id = ?");
            $stmtOrder->execute([$orderId]);
            $order = $stmtOrder->fetch();
            
            if (!$order) {
                $parts = explode('-', $orderId);
                if (count($parts) > 3) {
                    array_pop($parts);
                    $orderId = implode('-', $parts);
                    
                    $stmtOrder = $db->prepare("SELECT id, status FROM orders WHERE id = ?");
                    $stmtOrder->execute([$orderId]);
                    $order = $stmtOrder->fetch();
                }
            }

            // Only update if not already paid/processing (to avoid overwriting more advanced statuses)
            if ($order && in_array($order['status'], ['pending', 'unpaid'])) {
                $targetStatus = 'paid'; // On finish, we assume it's paid or being processed
                
                $stmt = $db->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?");
                $stmt->execute([$targetStatus, $orderId]);
                
                // Trigger notification for immediate feedback
                \App\Services\NotificationService::notifyOrderStatusChange($orderId, $targetStatus, 'both');
            }
        }

        // If it was a redirect, redirect to orders page. If it was an API call, return JSON.
        if (!empty($_GET)) {
            header('Location: ' . url('/orders?payment=success'));
            exit;
        }

        echo json_encode(['status' => 'ok']);
    }

    public function webhook() {
        // Read raw JSON payload
        $rawBody  = file_get_contents('php://input');
        $payload  = json_decode($rawBody, true);

        if (!$payload) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid payload']);
            return;
        }

        $orderId       = $payload['order_id']       ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus   = $payload['fraud_status']   ?? null;

        if (!$orderId || !$transactionStatus) {
            http_response_code(422);
            echo json_encode(['status' => 'error', 'message' => 'Missing fields']);
            return;
        }

        $db = Database::getInstance()->getConnection();

        // Handle retry suffix (e.g., ORD-ABC-123-456 -> ORD-ABC-123)
        $stmtOrder = $db->prepare("SELECT id FROM orders WHERE id = ?");
        $stmtOrder->execute([$orderId]);
        if (!$stmtOrder->fetch()) {
            $parts = explode('-', $orderId);
            if (count($parts) > 3) {
                array_pop($parts);
                $orderId = implode('-', $parts);
            }
        }

        // Map Midtrans statuses to internal payment statuses
        $paymentStatus = 'pending';
        $orderStatus   = 'pending';

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'accept' || $fraudStatus === null) {
                $paymentStatus = 'paid';
                $orderStatus   = 'processing';
            } elseif ($fraudStatus === 'challenge') {
                $paymentStatus = 'challenged';
            }
        } elseif ($transactionStatus === 'settlement') {
            $paymentStatus = 'paid';
            $orderStatus   = 'processing';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $paymentStatus = 'failed';
            $orderStatus   = 'cancelled';
        } elseif ($transactionStatus === 'refund') {
            $paymentStatus = 'refunded';
            $orderStatus   = 'refunded';
        }

        // Update payment record
        $db->prepare("UPDATE payments SET status = ?, updated_at = NOW() WHERE order_id = ?")
           ->execute([$paymentStatus, $orderId]);

        // Update order status
        $db->prepare("UPDATE orders SET status = ?, updated_at = NOW() WHERE id = ?")
           ->execute([$orderStatus, $orderId]);
           
        // Notify both parties about payment result
        \App\Services\NotificationService::notifyOrderStatusChange($orderId, $orderStatus, 'both');

        http_response_code(200);
        echo json_encode(['status' => 'ok']);
    }
}
