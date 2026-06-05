<?php

namespace App\Controllers\Vendor;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class ProfileController extends Controller {
    public function index() {
        $session = new Session();
        $userId  = $session->get('user_id');
        $db      = Database::getInstance()->getConnection();

        $stmt = $db->prepare("SELECT v.*, u.name, u.email FROM vendors v JOIN users u ON u.id = v.user_id WHERE v.user_id = ?");
        $stmt->execute([$userId]);
        $vendor = $stmt->fetch();

        if (!$vendor) return $this->redirect('/vendor/onboarding');

        $this->view('vendor.profile', ['vendor' => $vendor, 'title' => 'My Profile - LitleMart Vendor']);
    }

    public function update() {
        $session  = new Session();
        $userId   = $session->get('user_id');
        $db       = Database::getInstance()->getConnection();
        $phone    = $_POST['phone'] ?? '';
        $location = $_POST['location'] ?? '';

        $db->prepare("UPDATE vendors SET phone = ?, location = ? WHERE user_id = ?")->execute([$phone, $location, $userId]);
        $this->redirect('/vendor/profile');
    }
}
