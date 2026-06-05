<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;

class CategoryController extends Controller {
    public function index() {
        $categoryModel = new Category();
        $categories = $categoryModel->getAllActive();
        
        return $this->view('categories.index', [
            'categories' => $categories,
            'title' => 'Categories'
        ]);
    }
}
