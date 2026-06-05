<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Database;
use Midtrans\Notification;

class PaymentApiController extends Controller {
    public function handleNotification(Request $request) {
        $db = Database::getInstance()->getConnection();
        
        try {
            // Configure Midtrans (same as PaymentService)
            \Midtrans\Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'];
            \Midtrans\Config::$isProduction = ($_ENV['MIDTRANS_IS_PRODUCTION'] ?? 'false') === 'true';
            
            $notif = new Notification();
            
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

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

            $status = 'pending';

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $status = 'challenge';
                    } else {
                        $status = 'paid';
                    }
                }
            } else if ($transaction == 'settlement') {
                $status = 'paid';
            } else if ($transaction == 'pending') {
                $status = 'pending';
            } else if ($transaction == 'deny') {
                $status = 'denied';
            } else if ($transaction == 'expire') {
                $status = 'expired';
            } else if ($transaction == 'cancel') {
                $status = 'cancelled';
            }

            // Update order status
            $stmt = $db->prepare("UPDATE orders SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$status, $orderId]);

            // If paid, you might want to create a payment record too
            if ($status === 'paid') {
                $stmt = $db->prepare("INSERT INTO payments (id, order_id, payment_type, external_id, amount, status) 
                                     VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    \Ramsey\Uuid\Uuid::uuid4()->toString(),
                    $orderId,
                    $type,
                    $notif->transaction_id,
                    $notif->gross_amount,
                    'success'
                ]);
            }

            return $this->json(['message' => 'Notification processed']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
