<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Product;

class VendorShopController extends Controller {
    public function index($request, $id) {
        $db = Database::getInstance()->getConnection();
        
        // Get Vendor Details
        $stmt = $db->prepare("SELECT v.*, u.name as owner_name FROM vendors v JOIN users u ON v.user_id = u.id WHERE v.id = ?");
        $stmt->execute([$id]);
        $vendor = $stmt->fetch();
        
        if (!$vendor) {
            return $this->redirect('/');
        }
        
        // Get Vendor Products
        $productModel = new Product();
        $products = $productModel->getByVendor($id);
        
        return $this->view('vendor.shop', [
            'vendor' => $vendor,
            'products' => $products,
            'title' => $vendor['store_name'] . ' - Official Store'
        ]);
    }
}
