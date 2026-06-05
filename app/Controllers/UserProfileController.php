<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class UserProfileController extends Controller {
    public function show($request, $id) {
        $db = Database::getInstance()->getConnection();
        
        // 1. Get User Details
        $stmt = $db->prepare("SELECT id, name, email, phone, avatar, bio, banner, is_verified, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            return $this->redirect('/');
        }
        
        // 2. Statistics
        $stmtStats = $db->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = 'completed'");
        $stmtStats->execute([$id]);
        $completedOrders = $stmtStats->fetchColumn();

        $stmtSpending = $db->prepare("SELECT SUM(grand_total) FROM orders WHERE user_id = ? AND status = 'completed'");
        $stmtSpending->execute([$id]);
        $totalSpending = $stmtSpending->fetchColumn() ?? 0;

        // 3. Recent Activity (Latest 5 Orders)
        $stmtRecent = $db->prepare("
            SELECT o.*, 
                   (SELECT p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = o.id LIMIT 1) as sample_product
            FROM orders o 
            WHERE o.user_id = ? 
            ORDER BY o.created_at DESC 
            LIMIT 5
        ");
        $stmtRecent->execute([$id]);
        $recentOrders = $stmtRecent->fetchAll();

        // 4. Vendor Info (if applicable)
        $stmtVendor = $db->prepare("SELECT * FROM vendors WHERE user_id = ?");
        $stmtVendor->execute([$id]);
        $vendor = $stmtVendor->fetch();

        return $this->view('user.profile', [
            'user' => $user,
            'stats' => [
                'completed_orders' => $completedOrders,
                'total_spending' => $totalSpending
            ],
            'recentOrders' => $recentOrders,
            'vendor' => $vendor,
            'title' => $user['name'] . ' - Profil LitleMart'
        ]);
    }
}
