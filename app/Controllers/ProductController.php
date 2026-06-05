<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Core\Request;

class ProductController extends Controller {
    public function index(Request $request) {
        $productModel = new Product();
        $categoryModel = new \App\Models\Category();
        
        $filters = [
            'search' => $request->input('search'),
            'category' => $request->input('category'),
            'min_price' => $request->input('min_price'),
            'max_price' => $request->input('max_price'),
            'rating' => $request->input('rating')
        ];
        
        $products = $productModel->search($filters, 24);
        $categories = $categoryModel->getAllActive();
        
        return $this->view('products.index', [
            'products' => $products,
            'categories' => $categories,
            'title' => 'Search Products',
            'filters' => $filters
        ]);
    }

    public function show(Request $request, $id) {
        $productModel = new Product();
        $product = $productModel->getWithDetails($id);
        
        if (!$product) {
            return $this->redirect('/products');
        }

        $images = $productModel->getImages($id);
        $relatedProducts = $productModel->search(['category' => $product['category_id']], 4);
        // Remove current product from related
        $relatedProducts = array_filter($relatedProducts, fn($p) => $p['id'] !== $id);
        
        return $this->view('products.show', [
            'product' => $product,
            'images' => $images,
            'relatedProducts' => $relatedProducts,
            'title' => $product['name']
        ]);
    }
}
