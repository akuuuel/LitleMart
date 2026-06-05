<?php

namespace App\Services;

use App\Core\Database;

class WalletService {
    /**
     * Credit vendors for a completed order.
     * Ensures each vendor gets their portion of the order total.
     */
    public static function creditVendorWallets($orderId) {
        $db = Database::getInstance()->getConnection();
        
        try {
            $db->beginTransaction();

            // Check if already credited to avoid double payment
            $checkStmt = $db->prepare("SELECT is_credited FROM orders WHERE id = ? FOR UPDATE");
            $checkStmt->execute([$orderId]);
            $isCredited = $checkStmt->fetchColumn();

            if ($isCredited) {
                $db->rollBack();
                return false;
            }

            // Get all items in this order grouped by vendor
            $stmt = $db->prepare("
                SELECT oi.vendor_id, SUM(oi.total) as vendor_total, v.user_id as vendor_user_id
                FROM order_items oi
                JOIN vendors v ON oi.vendor_id = v.id
                WHERE oi.order_id = ?
                GROUP BY oi.vendor_id, v.user_id
            ");
            $stmt->execute([$orderId]);
            $vendorEarnings = $stmt->fetchAll();

            foreach ($vendorEarnings as $earning) {
                $vendorUserId = $earning['vendor_user_id'];
                $amount = $earning['vendor_total'];

                // Ensure wallet exists
                $wStmt = $db->prepare("SELECT id FROM wallets WHERE user_id = ?");
                $wStmt->execute([$vendorUserId]);
                if (!$wStmt->fetch()) {
                    $db->prepare("INSERT INTO wallets (user_id, balance) VALUES (?, 0)")->execute([$vendorUserId]);
                }

                // Credit the balance
                $db->prepare("UPDATE wallets SET balance = balance + ?, updated_at = NOW() WHERE user_id = ?")
                   ->execute([$amount, $vendorUserId]);
            }

            // Mark order as credited
            $db->prepare("UPDATE orders SET is_credited = 1 WHERE id = ?")->execute([$orderId]);

            $db->commit();
            return true;
        } catch (\Exception $e) {
            $db->rollBack();
            error_log("Wallet credit failed for order $orderId: " . $e->getMessage());
            return false;
        }
    }
}
