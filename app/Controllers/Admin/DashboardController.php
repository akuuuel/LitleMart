<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class DashboardController extends Controller {

    public function index() {
        $db = Database::getInstance()->getConnection();

        // Single query for all stats (5 queries → 1)
        $statsRow = $db->query("
            SELECT
                (SELECT COUNT(*) FROM users) as total_users,
                (SELECT COUNT(*) FROM vendors) as total_vendors,
                (SELECT COUNT(*) FROM products) as total_products,
                (SELECT COUNT(*) FROM orders) as total_orders,
                (SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status='completed') as total_revenue
        ")->fetch();
        $stats = $statsRow;

        $revenueNow  = $db->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status='completed' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
        $revenuePast = $db->query("SELECT COALESCE(SUM(total_amount),0) FROM orders WHERE status='completed' AND created_at BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();
        $growth = ($revenuePast > 0) ? round((($revenueNow - $revenuePast) / $revenuePast) * 100, 1) : 0;

        // Daily revenue last 7 days
        $rows = $db->query("
            SELECT DATE(created_at) as d, COALESCE(SUM(total_amount),0) as total
            FROM orders WHERE status='completed' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(created_at)
        ")->fetchAll(\PDO::FETCH_KEY_PAIR);

        $chartLabels = [];
        $chartData   = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chartLabels[] = date('D', strtotime($date));
            $chartData[]   = (float)($rows[$date] ?? 0);
        }

        $recentOrders = $db->query("
            SELECT o.*, u.name as customer_name
            FROM orders o JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC LIMIT 10
        ")->fetchAll();

        ob_start();
        $this->view('admin.dashboard', [
            'stats'         => $stats,
            'growth'        => $growth,
            'chart_labels'  => $chartLabels,
            'chart_data'    => $chartData,
            'recent_orders' => $recentOrders,
        ]);
        $content = ob_get_clean();
        return $this->view('layouts.app', ['content' => $content, 'title' => 'Market Intelligence']);
    }

    public function users() {
        $db = Database::getInstance()->getConnection();
        $users = $db->query("
            SELECT u.*, GROUP_CONCAT(r.name SEPARATOR ', ') as roles
            FROM users u
            LEFT JOIN user_roles ur ON u.id = ur.user_id
            LEFT JOIN roles r ON ur.role_id = r.id
            GROUP BY u.id ORDER BY u.created_at DESC
        ")->fetchAll();

        ob_start();
        $this->view('admin.users', ['users' => $users]);
        $content = ob_get_clean();
        return $this->view('layouts.app', ['content' => $content, 'title' => 'User Management']);
    }

    public function deactivateUser($request) {
        $db = Database::getInstance()->getConnection();
        $userId = $request->input('user_id');
        if ($userId) {
            // Remove all roles from user (effectively deactivating)
            $stmt = $db->prepare("DELETE FROM user_roles WHERE user_id = ?");
            $stmt->execute([$userId]);
            Session::setFlash('success', 'User has been deactivated successfully.');
        }
        return $this->redirect('/admin/users');
    }

    public function vendors() {
        $db = Database::getInstance()->getConnection();
        $vendors = $db->query("
            SELECT v.*, u.email as user_email, u.name as owner_name
            FROM vendors v JOIN users u ON v.user_id = u.id
            ORDER BY v.created_at DESC
        ")->fetchAll();

        ob_start();
        $this->view('admin.vendors', ['vendors' => $vendors]);
        $content = ob_get_clean();
        return $this->view('layouts.app', ['content' => $content, 'title' => 'Vendor Management']);
    }

    public function verifyVendor($request) {
        $db = Database::getInstance()->getConnection();
        $vendorId = $request->input('vendor_id');
        if ($vendorId) {
            $stmt = $db->prepare("UPDATE vendors SET is_active = 1 WHERE id = ?");
            $stmt->execute([$vendorId]);
            Session::setFlash('success', 'Vendor has been verified and activated.');
        }
        return $this->redirect('/admin/vendors');
    }

    public function suspendVendor($request) {
        $db = Database::getInstance()->getConnection();
        $vendorId = $request->input('vendor_id');
        if ($vendorId) {
            $stmt = $db->prepare("UPDATE vendors SET is_active = 0 WHERE id = ?");
            $stmt->execute([$vendorId]);
            Session::setFlash('success', 'Vendor has been suspended.');
        }
        return $this->redirect('/admin/vendors');
    }

    public function orders() {
        $db = Database::getInstance()->getConnection();
        $orders = $db->query("
            SELECT o.*, u.name as customer_name, u.email as customer_email
            FROM orders o JOIN users u ON o.user_id = u.id
            ORDER BY o.created_at DESC
            LIMIT 200
        ")->fetchAll();

        ob_start();
        $this->view('admin.orders', ['orders' => $orders]);
        $content = ob_get_clean();
        return $this->view('layouts.app', ['content' => $content, 'title' => 'Order Management']);
    }

    public function analytics() {
        $db = Database::getInstance()->getConnection();

        // Monthly revenue last 12 months
        $monthly = $db->query("
            SELECT DATE_FORMAT(created_at, '%b %Y') as month,
                   COALESCE(SUM(total_amount),0) as total,
                   COUNT(*) as count
            FROM orders WHERE status='completed'
            AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
            GROUP BY YEAR(created_at), MONTH(created_at)
            ORDER BY created_at ASC
        ")->fetchAll();

        // Top products
        $topProducts = $db->query("
            SELECT p.name, SUM(oi.quantity) as total_sold, SUM(oi.price * oi.quantity) as revenue
            FROM order_items oi JOIN products p ON oi.product_id = p.id
            GROUP BY oi.product_id ORDER BY total_sold DESC LIMIT 10
        ")->fetchAll();

        // New users per month
        $userGrowth = $db->query("
            SELECT DATE_FORMAT(created_at,'%b %Y') as month, COUNT(*) as count
            FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY YEAR(created_at), MONTH(created_at)
            ORDER BY created_at ASC
        ")->fetchAll();

        ob_start();
        $this->view('admin.analytics', [
            'monthly'     => $monthly,
            'topProducts' => $topProducts,
            'userGrowth'  => $userGrowth,
        ]);
        $content = ob_get_clean();
        return $this->view('layouts.app', ['content' => $content, 'title' => 'Analytics Hub']);
    }

    public function sendAnnouncement($request) {
        $db = Database::getInstance()->getConnection();
        $title   = trim($request->input('title', ''));
        $message = trim($request->input('message', ''));
        $target  = $request->input('target', 'all'); // all, vendors, customers

        if ($title && $message) {
            // Get target user IDs
            if ($target === 'vendors') {
                $stmt = $db->query("SELECT DISTINCT user_id FROM vendors");
            } elseif ($target === 'customers') {
                $stmt = $db->query("
                    SELECT u.id as user_id FROM users u
                    LEFT JOIN vendors v ON u.id = v.user_id
                    WHERE v.user_id IS NULL
                ");
            } else {
                $stmt = $db->query("SELECT id as user_id FROM users");
            }
            $targetUsers = $stmt->fetchAll(\PDO::FETCH_COLUMN);

            // Insert notifications for each user
            $insertStmt = $db->prepare("
                INSERT INTO notifications (id, user_id, type, title, message, is_read, created_at)
                VALUES (UUID(), ?, 'announcement', ?, ?, 0, NOW())
            ");
            foreach ($targetUsers as $userId) {
                $insertStmt->execute([$userId, $title, $message]);
            }
            Session::setFlash('success', 'Announcement sent to ' . count($targetUsers) . ' user(s) successfully.');
        } else {
            Session::setFlash('error', 'Title and message are required.');
        }

        return $this->redirect('/admin/dashboard');
    }
}
