<?php

namespace App\Controllers\Vendor;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class DashboardController extends Controller {
    public function index() {
        $session  = new Session();
        $userId   = $session->get('user_id');
        $db       = Database::getInstance()->getConnection();

        // Get vendor
        $stmt = $db->prepare("SELECT v.*, u.name AS user_name, u.email FROM vendors v JOIN users u ON u.id = v.user_id WHERE v.user_id = ?");
        $stmt->execute([$userId]);
        $vendor = $stmt->fetch();

        if (!$vendor) {
            return $this->redirect('/vendor/onboarding');
        }

        $vendorId = $vendor['id'];

        // Real stats — using prepared statements (fixes SQL injection)
        $stmtProd = $db->prepare("SELECT COUNT(*) FROM products WHERE vendor_id = ? AND status != 'archived'");
        $stmtProd->execute([$vendorId]);
        $totalProducts = (int)$stmtProd->fetchColumn();

        $stmtOrd = $db->prepare("SELECT COUNT(DISTINCT o.id) FROM orders o JOIN order_items oi ON oi.order_id = o.id WHERE oi.vendor_id = ?");
        $stmtOrd->execute([$vendorId]);
        $totalOrders = (int)$stmtOrd->fetchColumn();

        $stmtRev = $db->prepare("SELECT COALESCE(SUM(oi.total), 0) FROM order_items oi JOIN orders o ON o.id = oi.order_id WHERE oi.vendor_id = ? AND o.status IN ('delivered','completed')");
        $stmtRev->execute([$vendorId]);
        $totalRevenue = (float)$stmtRev->fetchColumn();

        $stmtPend = $db->prepare("SELECT COUNT(DISTINCT o.id) FROM orders o JOIN order_items oi ON oi.order_id = o.id WHERE oi.vendor_id = ? AND o.status = 'pending'");
        $stmtPend->execute([$vendorId]);
        $pendingOrders = (int)$stmtPend->fetchColumn();

        $stmtWallet = $db->prepare("SELECT COALESCE(balance, 0) FROM wallets WHERE user_id = ?");
        $stmtWallet->execute([$userId]);
        $walletBalance = (float)($stmtWallet->fetchColumn() ?: 0);

        // Monthly revenue chart (last 6 months) — 1 query instead of 6
        $chartStmt = $db->prepare("
            SELECT DATE_FORMAT(o.created_at, '%Y-%m') AS month_key,
                   DATE_FORMAT(o.created_at, '%b')    AS label,
                   COALESCE(SUM(oi.total), 0)         AS value
            FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            WHERE oi.vendor_id = ?
              AND o.status IN ('delivered','completed')
              AND o.created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(o.created_at, '%Y-%m'), DATE_FORMAT(o.created_at, '%b')
            ORDER BY month_key ASC
        ");
        $chartStmt->execute([$vendorId]);
        $rawChart = $chartStmt->fetchAll();

        // Fill in missing months with 0
        $chartMap = [];
        foreach ($rawChart as $row) { $chartMap[$row['month_key']] = $row; }
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $mk = date('Y-m', strtotime("-$i months"));
            $lb = date('M',   strtotime("-$i months"));
            $chartData[] = ['label' => $lb, 'value' => (float)($chartMap[$mk]['value'] ?? 0)];
        }

        // Recent orders for dashboard
        $recentOrdersStmt = $db->prepare("
            SELECT o.id, o.status, o.created_at, u.name AS customer_name,
                   SUM(oi.total) AS subtotal, COUNT(oi.id) AS item_count
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            JOIN users u ON u.id = o.user_id
            WHERE oi.vendor_id = ?
            GROUP BY o.id, o.status, o.created_at, u.name
            ORDER BY o.created_at DESC
            LIMIT 5
        ");
        $recentOrdersStmt->execute([$vendorId]);
        $recentOrders = $recentOrdersStmt->fetchAll();

        // Recent notifications
        $notifsStmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
        $notifsStmt->execute([$userId]);
        $notifications = $notifsStmt->fetchAll();

        $data = [
            'title'         => 'Dashboard — ' . $vendor['store_name'],
            'pageTitle'     => 'Store Dashboard',
            'vendor'        => $vendor,
            'totalProducts' => $totalProducts,
            'totalOrders'   => $totalOrders,
            'totalRevenue'  => $totalRevenue,
            'pendingOrders' => $pendingOrders,
            'walletBalance' => $walletBalance,
            'recentOrders'  => $recentOrders,
            'notifications' => $notifications,
            'chartData'     => $chartData,
        ];

        return $this->view('vendor.dashboard', $data);
    }
}
