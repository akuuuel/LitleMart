<?php

namespace App\Controllers\Vendor;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use App\Models\Product;
use App\Models\Category;
use App\Core\Request;
use App\Helpers\ImageHelper;
use Ramsey\Uuid\Uuid;

class ProductController extends Controller {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel  = new Product();
        $this->categoryModel = new Category();
    }

    /**
     * Resolve vendor_id from session.
     * Tries vendor_id directly, falls back to querying from user_id.
     */
    private function getVendorId(): ?string {
        $vendorId = Session::get('vendor_id');
        if ($vendorId) return $vendorId;

        $userId = Session::get('user_id');
        if (!$userId) return null;

        $db   = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id FROM vendors WHERE user_id = ?");
        $stmt->execute([$userId]);
        $row  = $stmt->fetch();
        if ($row) {
            // Cache in session for next requests
            Session::set('vendor_id', $row['id']);
            return $row['id'];
        }
        return null;
    }

    public function index() {
        $vendorId = $this->getVendorId();
        if (!$vendorId) return $this->redirect('/vendor/onboarding');

        $products = $this->productModel->getByVendor($vendorId);
        return $this->view('vendor.products.index', [
            'title'     => 'My Products',
            'pageTitle' => 'Product Inventory',
            'products'  => $products,
        ]);
    }

    public function create() {
        $vendorId = $this->getVendorId();
        if (!$vendorId) return $this->redirect('/vendor/onboarding');

        $categories = $this->categoryModel->getAllActive();
        return $this->view('vendor.products.create', [
            'title'      => 'Add New Product',
            'pageTitle'  => 'Add New Product',
            'categories' => $categories,
        ]);
    }

    public function edit(Request $request, $id) {
        $vendorId = $this->getVendorId();
        if (!$vendorId) return $this->redirect('/vendor/onboarding');

        $product = $this->productModel->find($id);
        if (!$product || $product['vendor_id'] !== $vendorId) {
            Session::setFlash('error', 'Product not found.');
            return $this->redirect('/vendor/products');
        }

        $categories = $this->categoryModel->getAllActive();
        return $this->view('vendor.products.edit', [
            'title'      => 'Edit Product',
            'pageTitle'  => 'Edit Product',
            'product'    => $product,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id) {
        $vendorId = $this->getVendorId();
        if (!$vendorId) return $this->redirect('/vendor/onboarding');

        $product = $this->productModel->find($id);
        if (!$product || $product['vendor_id'] !== $vendorId) {
            return $this->redirect('/vendor/products');
        }

        $data = $request->getBody();
        $updateData = [
            'category_id' => $data['category_id'],
            'brand'       => $data['brand'] ?? '',
            'condition'   => $data['condition'] ?? 'new',
            'name'        => $data['name'],
            'description' => $data['description'],
            'price'       => $data['price'],
            'stock'       => $data['stock'],
            'weight'      => $data['weight'],
            'status'      => 'published'
        ];

        // Handle image uploads with compression
        $uploadDir = __DIR__ . '/../../../public/uploads/products/';
        $images = [];
        if (!empty($_FILES['images']['tmp_name'][0])) {
            $images = ImageHelper::processUploads($_FILES['images'], $uploadDir);
        }

        if ($this->productModel->updateWithImages($id, $updateData, $images)) {
            Session::setFlash('success', 'Product updated successfully!');
            return $this->redirect('/vendor/products');
        }

        Session::setFlash('error', 'Failed to update product.');
        return $this->redirect('/vendor/products/edit/' . $id);
    }

    public function delete(Request $request, $id) {
        $vendorId = $this->getVendorId();
        if (!$vendorId) return $this->redirect('/vendor/onboarding');

        $product = $this->productModel->find($id);
        if (!$product || $product['vendor_id'] !== $vendorId) {
            return $this->redirect('/vendor/products');
        }

        if ($this->productModel->delete($id)) {
            Session::setFlash('success', 'Product deleted successfully!');
        } else {
            Session::setFlash('error', 'Failed to delete product.');
        }

        return $this->redirect('/vendor/products');
    }

    public function store(Request $request) {
        $vendorId = $this->getVendorId();
        if (!$vendorId) return $this->redirect('/vendor/onboarding');

        $data = $request->getBody();

        // Guard: POST data may be empty if upload exceeded server limits
        if (empty($data) || empty($data['name']) || empty($data['category_id'])) {
            Session::setFlash('error', 'Upload failed. Please ensure total file size is under 200MB and all required fields are filled.');
            return $this->redirect('/vendor/products/create');
        }

        $productId = Uuid::uuid4()->toString();

        $productData = [
            'id'          => $productId,
            'vendor_id'   => $vendorId,
            'category_id' => $data['category_id'],
            'brand'       => $data['brand'] ?? '',
            'condition'   => $data['condition'] ?? 'new',
            'name'        => $data['name'],
            'slug'        => $this->slugify($data['name']) . '-' . substr($productId, 0, 8),
            'description' => $data['description'] ?? '',
            'price'       => $data['price'] ?? 0,
            'stock'       => $data['stock'] ?? 0,
            'weight'      => $data['weight'] ?? 0,
            'status'      => 'published',
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        // Handle image uploads with compression
        $uploadDir = __DIR__ . '/../../../public/uploads/products/';
        $images = [];
        if (!empty($_FILES['images']['tmp_name'][0])) {
            $images = ImageHelper::processUploads($_FILES['images'], $uploadDir);
        }

        if ($this->productModel->createWithImages($productData, $images)) {
            Session::setFlash('success', 'Product published successfully!');
            return $this->redirect('/vendor/products');
        }

        Session::setFlash('error', 'Failed to publish product. Please try again.');
        return $this->redirect('/vendor/products/create');
    }

    private function slugify(string $text): string {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        return strtolower($text) ?: 'product';
    }
}
