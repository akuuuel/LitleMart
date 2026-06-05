<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class AboutController extends Controller {
    public function index() {
        $db = Database::getInstance()->getConnection();

        // Fetch live stats
        $userCount = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $vendorCount = $db->query("SELECT COUNT(*) FROM vendors")->fetchColumn();
        $productCount = $db->query("SELECT COUNT(*) FROM products WHERE status = 'active'")->fetchColumn();
        $orderCount = $db->query("SELECT COUNT(*) FROM orders WHERE status = 'completed'")->fetchColumn();

        return $this->view('about', [
            'title' => 'How It Works - LitleMart',
            'stats' => [
                'users'    => $userCount,
                'vendors'  => $vendorCount,
                'products' => $productCount,
                'orders'   => $orderCount,
            ]
        ]);
    }
}
