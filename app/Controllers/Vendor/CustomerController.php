<?php

namespace App\Controllers\Vendor;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class CustomerController extends Controller {
    public function index() {
        $session  = new Session();
        $userId   = $session->get('user_id');
        $db       = Database::getInstance()->getConnection();

        $vStmt = $db->prepare("SELECT id, store_name FROM vendors WHERE user_id = ?");
        $vStmt->execute([$userId]);
        $vendor = $vStmt->fetch();
        if (!$vendor) return $this->redirect('/vendor/onboarding');

        $vendorId = $vendor['id'];

        // Customers who have ordered from this vendor
        $stmt = $db->prepare("
            SELECT u.id, u.name, u.email, u.created_at AS member_since,
                   COUNT(DISTINCT o.id) AS order_count,
                   SUM(oi.total) AS total_spent,
                   MAX(o.created_at) AS last_order
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            JOIN users u ON u.id = o.user_id
            WHERE oi.vendor_id = ?
            GROUP BY u.id, u.name, u.email, u.created_at
            ORDER BY total_spent DESC
            LIMIT 50
        ");
        $stmt->execute([$vendorId]);
        $customers = $stmt->fetchAll();

        return $this->view('vendor.customers', [
            'title'     => 'Customers — ' . $vendor['store_name'],
            'pageTitle' => 'Customer List',
            'vendor'    => $vendor,
            'customers' => $customers,
        ]);
    }
}
