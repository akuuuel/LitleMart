<?php

namespace App\Controllers\Vendor;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Request;
use App\Core\Session;

class SearchController extends Controller {
    public function index(Request $request) {
        $userId = Session::get('user_id');
        if (!$userId) return $this->redirect('/login');

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id FROM vendors WHERE user_id = ?");
        $stmt->execute([$userId]);
        $vendor = $stmt->fetch();
        if (!$vendor) return $this->redirect('/vendor/onboarding');

        $query = $request->input('q', '');
        $results = [];

        if (!empty($query)) {
            $searchTerm = "%$query%";
            
            // Search Products
            $stmt = $db->prepare("SELECT 'product' as type, name as title, id, created_at FROM products WHERE vendor_id = ? AND (name LIKE ? OR sku LIKE ?)");
            $stmt->execute([$vendor['id'], $searchTerm, $searchTerm]);
            $products = $stmt->fetchAll();
            
            // Search Orders
            $stmt = $db->prepare("SELECT DISTINCT 'order' as type, o.id as title, o.id, o.created_at 
                                FROM orders o 
                                JOIN order_items oi ON o.id = oi.order_id 
                                WHERE oi.vendor_id = ? AND (o.id LIKE ?)");
            $stmt->execute([$vendor['id'], $searchTerm]);
            $orders = $stmt->fetchAll();

            // Search Customers
            $stmt = $db->prepare("SELECT DISTINCT 'customer' as type, u.name as title, u.id, u.created_at 
                                FROM users u 
                                JOIN orders o ON u.id = o.user_id 
                                JOIN order_items oi ON o.id = oi.order_id 
                                WHERE oi.vendor_id = ? AND (u.name LIKE ? OR u.email LIKE ?)");
            $stmt->execute([$vendor['id'], $searchTerm, $searchTerm]);
            $customers = $stmt->fetchAll();

            $results = array_merge($products, $orders, $customers);
        }

        return $this->view('vendor.search', [
            'title' => 'Search Results - Vendor Dashboard',
            'query' => $query,
            'results' => $results
        ]);
    }
}
