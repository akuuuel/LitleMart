<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class ChatController extends Controller {
    public function index(\App\Core\Request $request) {
        $userId = Session::get('user_id');
        if (!$userId) {
            return $this->redirect('/login');
        }

        $db = Database::getInstance()->getConnection();
        
        // Get all conversations for the user
        // This is a mockup of how you'd fetch conversation partners
        $stmt = $db->prepare("
            SELECT DISTINCT u.id, u.name, u.avatar, v.store_name
            FROM users u
            LEFT JOIN vendors v ON v.user_id = u.id
            WHERE u.id IN (
                -- In a real app, you'd join with a messages table
                -- For now, we show the vendor if vendor_id is passed in URL
                ? 
            )
        ");
        
        $vendorId = $_GET['vendor_id'] ?? null;
        $partnerUserId = $_GET['u'] ?? null;
        $activeVendor = null;
        
        if ($vendorId) {
            $vStmt = $db->prepare("SELECT v.*, u.name as owner_name, u.avatar, u.id as user_id FROM vendors v JOIN users u ON v.user_id = u.id WHERE v.id = ?");
            $vStmt->execute([$vendorId]);
            $activeVendor = $vStmt->fetch();
        } elseif ($partnerUserId) {
            // Check if partner is a vendor first to get store details
            $vStmt = $db->prepare("SELECT v.*, u.name as owner_name, u.avatar, u.id as user_id FROM users u LEFT JOIN vendors v ON v.user_id = u.id WHERE u.id = ?");
            $vStmt->execute([$partnerUserId]);
            $activeVendor = $vStmt->fetch();
            
            // If they are not a vendor, fill basic info
            if ($activeVendor && !$activeVendor['store_name']) {
                $activeVendor['store_name'] = $activeVendor['owner_name'];
            }
        }

        return $this->view('chat.index', [
            'title' => 'Pesan - LitleMart',
            'activeVendor' => $activeVendor,
            'conversations' => []
        ]);
    }
}
