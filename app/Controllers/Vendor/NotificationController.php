<?php

namespace App\Controllers\Vendor;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class NotificationController extends Controller {
    public function index() {
        $userId = Session::get('user_id');
        if (!$userId) return $this->redirect('/login');

        $db = Database::getInstance()->getConnection();
        
        // Fetch notifications for the vendor
        $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
        $stmt->execute([$userId]);
        $notifications = $stmt->fetchAll();

        return $this->view('vendor.notifications', [
            'notifications' => $notifications,
            'title' => 'Notifications - LitleMart Vendor',
            'pageTitle' => 'Notifications'
        ]);
    }

    public function markAsRead() {
        $id = $_POST['id'] ?? null;
        $userId = Session::get('user_id');
        if ($id && $userId) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $userId]);
        }
        return $this->redirect('/vendor/notifications');
    }

    public function markAllAsRead() {
        $userId = Session::get('user_id');
        if ($userId) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
            $stmt->execute([$userId]);
        }
        return $this->redirect('/vendor/notifications');
    }
}
