<?php

namespace App\Controllers\Vendor;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class AnalyticsController extends Controller {
    public function index() {
        $session  = new Session();
        $userId   = $session->get('user_id');
        $db       = Database::getInstance()->getConnection();

        $vStmt = $db->prepare("SELECT id, store_name FROM vendors WHERE user_id = ?");
        $vStmt->execute([$userId]);
        $vendor = $vStmt->fetch();
        if (!$vendor) return $this->redirect('/vendor/onboarding');

        $vendorId = $vendor['id'];

        // Total Revenue (completed orders)
        $totalRevenue = (float)$db->query("
            SELECT COALESCE(SUM(oi.total), 0) FROM order_items oi
            JOIN orders o ON o.id = oi.order_id
            WHERE oi.vendor_id = '$vendorId' AND o.status IN ('delivered','completed')
        ")->fetchColumn();

        // Total Orders
        $totalOrders = (int)$db->query("
            SELECT COUNT(DISTINCT o.id) FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            WHERE oi.vendor_id = '$vendorId'
        ")->fetchColumn();

        // Total Products
        $totalProducts = (int)$db->query("SELECT COUNT(*) FROM products WHERE vendor_id = '$vendorId'")->fetchColumn();

        // Total Customers (distinct buyers)
        $totalCustomers = (int)$db->query("
            SELECT COUNT(DISTINCT o.user_id) FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            WHERE oi.vendor_id = '$vendorId'
        ")->fetchColumn();

        // Monthly chart (last 6 months)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $label = date('M Y', strtotime("-$i months"));
            $rev   = (float)$db->query("
                SELECT COALESCE(SUM(oi.total), 0) FROM order_items oi
                JOIN orders o ON o.id = oi.order_id
                WHERE oi.vendor_id = '$vendorId'
                  AND DATE_FORMAT(o.created_at, '%Y-%m') = '$month'
                  AND o.status IN ('delivered','completed')
            ")->fetchColumn();
            $orders = (int)$db->query("
                SELECT COUNT(DISTINCT o.id) FROM orders o
                JOIN order_items oi ON oi.order_id = o.id
                WHERE oi.vendor_id = '$vendorId'
                  AND DATE_FORMAT(o.created_at, '%Y-%m') = '$month'
            ")->fetchColumn();
            $chartData[] = ['label' => $label, 'revenue' => $rev, 'orders' => $orders];
        }

        // Top Products by revenue
        $topProductsStmt = $db->prepare("
            SELECT p.name, SUM(oi.quantity) AS total_sold, SUM(oi.total) AS total_revenue
            FROM order_items oi
            JOIN products p ON p.id = oi.product_id
            JOIN orders o ON o.id = oi.order_id
            WHERE oi.vendor_id = ?
            GROUP BY oi.product_id, p.name
            ORDER BY total_revenue DESC
            LIMIT 5
        ");
        $topProductsStmt->execute([$vendorId]);
        $topProducts = $topProductsStmt->fetchAll();

        return $this->view('vendor.analytics', [
            'title'          => 'Analytics — ' . $vendor['store_name'],
            'pageTitle'      => 'Store Analytics',
            'vendor'         => $vendor,
            'totalRevenue'   => $totalRevenue,
            'totalOrders'    => $totalOrders,
            'totalProducts'  => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'chartData'      => $chartData,
            'topProducts'    => $topProducts,
        ]);
    }
}
