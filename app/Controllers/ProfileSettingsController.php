<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class ProfileSettingsController extends Controller {
    public function index() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        return $this->view('user.settings', [
            'user' => $user,
            'title' => 'Pengaturan Profil'
        ]);
    }

    public function update($request) {
        $db = Database::getInstance()->getConnection();
        $userId = $_SESSION['user_id'];
        
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $bio = $_POST['bio'] ?? '';
        
        // Handle Avatar Upload
        $avatarPath = $_POST['current_avatar'] ?? '';
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
            $target = 'uploads/avatars/' . $filename;
            
            if (!is_dir('uploads/avatars')) {
                mkdir('uploads/avatars', 0777, true);
            }
            
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $target)) {
                $avatarPath = 'uploads/avatars/' . $filename;
            }
        }

        // Handle Banner Upload
        $bannerPath = $_POST['current_banner'] ?? '';
        if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['banner']['name'], PATHINFO_EXTENSION);
            $filename = 'banner_' . $userId . '_' . time() . '.' . $ext;
            $target = 'uploads/banners/' . $filename;
            
            if (!is_dir('uploads/banners')) {
                mkdir('uploads/banners', 0777, true);
            }
            
            if (move_uploaded_file($_FILES['banner']['tmp_name'], $target)) {
                $bannerPath = 'uploads/banners/' . $filename;
            }
        }

        $stmt = $db->prepare("UPDATE users SET name = ?, phone = ?, bio = ?, avatar = ?, banner = ? WHERE id = ?");
        $stmt->execute([$name, $phone, $bio, $avatarPath, $bannerPath, $userId]);
        
        // Update Session
        $_SESSION['user_name'] = $name;

        Session::setFlash('success', 'Profil berhasil diperbarui!');
        return $this->redirect('/user/' . $userId);
    }
}
