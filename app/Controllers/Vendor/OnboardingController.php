<?php

namespace App\Controllers\Vendor;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class OnboardingController extends Controller {
    public function index() {
        $this->view('vendor.onboarding', ['title' => 'Set Up Your Store - LitleMart']);
    }

    public function store() {
        $session     = new Session();
        $userId      = $session->get('user_id');
        $db          = Database::getInstance()->getConnection();
        
        $storeName   = $_POST['store_name'] ?? '';
        $category    = $_POST['category'] ?? '';
        $description = $_POST['description'] ?? '';
        $address     = $_POST['address'] ?? '';
        $phone       = $_POST['phone'] ?? '';
        $provinceId  = $_POST['province_id'] ?? null;
        $cityId      = $_POST['city_id'] ?? null;
        
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $storeName)));

        // Create or update vendor
        $stmt = $db->prepare("SELECT id FROM vendors WHERE user_id = ?");
        $stmt->execute([$userId]);
        $vendor = $stmt->fetch();
        
        if ($vendor) {
            $db->prepare("UPDATE vendors SET store_name=?, store_slug=?, store_description=?, address=?, phone=?, province_id=?, city_id=? WHERE user_id=?")
               ->execute([$storeName, $slug, $description, $address, $phone, $provinceId, $cityId, $userId]);
            $vendorId = $vendor['id'];
        } else {
            $vendorId = \Ramsey\Uuid\Uuid::uuid4()->toString();
            $db->prepare("INSERT INTO vendors (id, user_id, store_name, store_slug, store_description, address, phone, province_id, city_id) VALUES (?,?,?,?,?,?,?,?,?)")
               ->execute([$vendorId, $userId, $storeName, $slug, $description, $address, $phone, $provinceId, $cityId]);
            // Active by default for immediate setup
            $db->prepare("UPDATE vendors SET is_active = 1 WHERE id = ?")->execute([$vendorId]);
        }

        // Add vendor role to user if not already present
        $rolesStmt = $db->prepare("SELECT role_id FROM user_roles WHERE user_id = ? AND role_id = (SELECT id FROM roles WHERE name = 'vendor')");
        $rolesStmt->execute([$userId]);
        if (!$rolesStmt->fetch()) {
            $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, (SELECT id FROM roles WHERE name = 'vendor'))")
               ->execute([$userId]);
        }

        // Update session
        $roles = Session::get('roles', []);
        if (!in_array('vendor', $roles)) {
            $roles[] = 'vendor';
            Session::set('roles', $roles);
        }
        Session::set('vendor_id', $vendorId);
        Session::setFlash('success', 'Welcome to LitleMart! Your vendor account is ready.');

        return $this->redirect('/vendor/dashboard');
    }
}
