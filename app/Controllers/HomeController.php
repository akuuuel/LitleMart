<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        $productModel = new \App\Models\Product();
        $categoryModel = new \App\Models\Category();

        $featured_products = $productModel->getLatest(24);
        $categories = $categoryModel->getAllActive();

        return $this->view('home.index', [
            'title' => 'Home',
            'featured_products' => $featured_products,
            'categories' => $categories
        ]);
    }
    public function apiFeatured() {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        
        $productModel = new \App\Models\Product();
        $products = $productModel->getLatest($limit, $offset);
        
        // Enhance data for JSON
        foreach($products as &$p) {
            $p['detail_url'] = url('/products/' . $p['id']);
            $p['formatted_price'] = 'Rp ' . number_format($p['price'], 0, ',', '.');
            $p['image_url'] = $p['primary_image'] ? url($p['primary_image']) : "https://picsum.photos/seed/prod-{$p['id']}/400/400";
        }
        
        header('Content-Type: application/json');
        echo json_encode(array_values($products), JSON_HEX_APOS | JSON_HEX_QUOT);
        exit;
    }
}
