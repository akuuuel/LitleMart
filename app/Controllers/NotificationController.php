<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class NotificationController extends Controller {
    public function index() {
        $userId = Session::get('user_id');
        if (!$userId) return $this->redirect('/login');

        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
        $stmt->execute([$userId]);
        $notifications = $stmt->fetchAll();

        return $this->view('notifications.index', [
            'notifications' => $notifications,
            'title' => 'My Notifications'
        ]);
    }

    public function markAsRead() {
        $id = $_POST['id'] ?? null;
        $targetUrl = $_POST['target_url'] ?? null;
        $userId = Session::get('user_id');
        
        if ($id && $userId) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $userId]);
        }
        
        if ($targetUrl) return $this->redirect($targetUrl);
        return $this->redirect('/notifications');
    }

    public function markAllAsRead() {
        $userId = Session::get('user_id');
        if ($userId) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
            $stmt->execute([$userId]);
        }
        return $this->redirect('/notifications');
    }

    public function unreadCount() {
        $userId = Session::get('user_id');
        $count = 0;
        if ($userId) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
            $stmt->execute([$userId]);
            $count = $stmt->fetchColumn();
        }
        header('Content-Type: application/json');
        echo json_encode(['count' => (int)$count]);
    }
}
