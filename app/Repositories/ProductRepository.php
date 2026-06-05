<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository {
    protected $model;

    public function __construct() {
        $this->model = new Product();
    }

    public function findById($id) {
        return $this->model->getWithDetails($id);
    }

    public function getFeatured() {
        return $this->model->getLatest(8);
    }

    public function getByCategory($categoryId) {
        // Implementation
    }
}
