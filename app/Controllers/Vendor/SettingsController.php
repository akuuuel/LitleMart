<?php

namespace App\Controllers\Vendor;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;
use Ramsey\Uuid\Uuid;

class SettingsController extends Controller {
    
    private function getVendorData($userId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT v.*, u.name, u.email, u.password FROM vendors v JOIN users u ON u.id = v.user_id WHERE v.user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    public function index() {
        $userId = Session::get('user_id');
        if (!$userId) return $this->redirect('/login');

        $vendor = $this->getVendorData($userId);
        if (!$vendor) return $this->redirect('/vendor/onboarding');

        $db = Database::getInstance()->getConnection();

        // Fetch Payment Methods
        $stmt = $db->prepare("SELECT * FROM vendor_payment_methods WHERE vendor_id = ? ORDER BY is_primary DESC");
        $stmt->execute([$vendor['id']]);
        $payments = $stmt->fetchAll();

        // Fetch Notification Settings
        $stmt = $db->prepare("SELECT * FROM vendor_notification_settings WHERE vendor_id = ?");
        $stmt->execute([$vendor['id']]);
        $notifSettings = $stmt->fetch();

        if (!$notifSettings) {
            $notifSettings = [
                'new_order_email' => 1,
                'new_order_push' => 1,
                'weekly_report' => 1,
                'security_alerts' => 1
            ];
        }

        $walletBalance = (float)$db->query("SELECT COALESCE(balance, 0) FROM wallets WHERE user_id = '$userId'")->fetchColumn() ?: 0;

        return $this->view('vendor.settings', [
            'vendor' => $vendor, 
            'payments' => $payments,
            'notifSettings' => $notifSettings,
            'walletBalance' => $walletBalance,
            'title' => 'Settings - LitleMart Vendor',
            'pageTitle' => 'Settings'
        ]);
    }

    public function update() {
        $userId = Session::get('user_id');
        if (!$userId) return $this->redirect('/login');

        $vendor = $this->getVendorData($userId);
        $db = Database::getInstance()->getConnection();
        $action = $_POST['action'] ?? 'general';

        try {
            if ($action === 'update_password') {
                $current = $_POST['current_password'] ?? '';
                $new = $_POST['new_password'] ?? '';
                $confirm = $_POST['confirm_password'] ?? '';

                $new = trim($new);
                $confirm = trim($confirm);

                if (empty($new) || empty($confirm)) {
                    throw new \Exception("New password and confirmation are required.");
                }

                if (strlen($new) < 6) {
                    throw new \Exception("New password must be at least 6 characters.");
                }

                if ($new !== $confirm) {
                    throw new \Exception("New passwords do not match.");
                }

                $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch();

                // If current password is set in DB, verify it
                if (!empty($user['password'])) {
                    if (empty($current)) {
                        throw new \Exception("Current password is required to verify your identity.");
                    }
                    if (!password_verify($current, $user['password'])) {
                        throw new \Exception("Current password is incorrect.");
                    }
                }

                $hashed = password_hash($new, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->execute([$hashed, $userId]);

                Session::setFlash('success', 'Password updated successfully!');
            } 
            elseif ($action === 'add_payment') {
                $bank = $_POST['bank_name'] ?? '';
                $number = $_POST['account_number'] ?? '';
                $holder = $_POST['account_holder'] ?? '';

                if (empty($bank) || empty($number) || empty($holder)) {
                    throw new \Exception("All payment fields are required.");
                }

                // Check if it's the first method
                $stmt = $db->prepare("SELECT COUNT(*) FROM vendor_payment_methods WHERE vendor_id = ?");
                $stmt->execute([$vendor['id']]);
                $count = (int)$stmt->fetchColumn();

                $stmt = $db->prepare("INSERT INTO vendor_payment_methods (id, vendor_id, bank_name, account_number, account_holder, is_primary) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    Uuid::uuid4()->toString(),
                    $vendor['id'],
                    $bank,
                    $number,
                    $holder,
                    ($count === 0 ? 1 : 0)
                ]);

                Session::setFlash('success', 'Payment method added successfully!');
            }
            elseif ($action === 'update_notifications') {
                $settings = [
                    'new_order_email' => isset($_POST['new_order_email']) ? 1 : 0,
                    'new_order_push' => isset($_POST['new_order_push']) ? 1 : 0,
                    'weekly_report' => isset($_POST['weekly_report']) ? 1 : 0,
                    'security_alerts' => isset($_POST['security_alerts']) ? 1 : 0
                ];

                $stmt = $db->prepare("INSERT INTO vendor_notification_settings (vendor_id, new_order_email, new_order_push, weekly_report, security_alerts) 
                    VALUES (?, ?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE 
                    new_order_email = VALUES(new_order_email),
                    new_order_push = VALUES(new_order_push),
                    weekly_report = VALUES(weekly_report),
                    security_alerts = VALUES(security_alerts)");
                
                $stmt->execute([
                    $vendor['id'],
                    $settings['new_order_email'],
                    $settings['new_order_push'],
                    $settings['weekly_report'],
                    $settings['security_alerts']
                ]);

                Session::setFlash('success', 'Notification preferences updated!');
            } elseif ($action === 'request_withdrawal') {
                $amount = (float)($_POST['amount'] ?? 0);
                $methodId = $_POST['payment_method_id'] ?? '';
                $password = $_POST['password'] ?? '';

                if ($amount < 10000) {
                    throw new \Exception("Minimal penarikan adalah Rp 10.000");
                }

                // Verify Password
                $stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
                $stmt->execute([$userId]);
                $userPass = $stmt->fetchColumn();

                if ($userPass && !password_verify($password, $userPass)) {
                    throw new \Exception("Kata sandi salah. Penarikan dibatalkan.");
                }

                // Check balance
                $balance = (float)$db->query("SELECT COALESCE(balance, 0) FROM wallets WHERE user_id = '$userId'")->fetchColumn();
                if ($amount > $balance) {
                    throw new \Exception("Saldo tidak mencukupi.");
                }

                // Get bank info
                $stmt = $db->prepare("SELECT * FROM vendor_payment_methods WHERE id = ? AND vendor_id = ?");
                $stmt->execute([$methodId, $vendor['id']]);
                $method = $stmt->fetch();

                if (!$method) {
                    throw new \Exception("Metode pembayaran tidak valid.");
                }

                // Start transaction
                $db->beginTransaction();
                try {
                    // Deduct balance
                    $db->prepare("UPDATE wallets SET balance = balance - ?, updated_at = NOW() WHERE user_id = ?")
                       ->execute([$amount, $userId]);

                    // Record request
                    $stmt = $db->prepare("INSERT INTO withdrawals (id, user_id, amount, bank_name, account_number, account_holder, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        Uuid::uuid4()->toString(),
                        $userId,
                        $amount,
                        $method['bank_name'],
                        $method['account_number'],
                        $method['account_holder'],
                        'pending'
                    ]);

                    $db->commit();
                    Session::setFlash('success', 'Permintaan penarikan saldo berhasil dikirim. Menunggu persetujuan admin.');
                } catch (\Exception $e) {
                    $db->rollBack();
                    throw $e;
                }
            } else {
                // General Settings
                $storeName = trim($_POST['store_name'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $contactEmail = trim($_POST['contact_email'] ?? '');
                $provinceId = $_POST['province_id'] ?? null;
                $cityId = $_POST['city_id'] ?? null;

                if (empty($storeName)) {
                    throw new \Exception("Store name cannot be empty.");
                }

                // Handle Logo Upload
                $logoPath = $_POST['current_logo'] ?? $vendor['store_logo'];
                if (isset($_FILES['store_logo']) && $_FILES['store_logo']['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES['store_logo']['name'], PATHINFO_EXTENSION);
                    $filename = 'logo_' . $userId . '_' . time() . '.' . $ext;
                    $target = 'uploads/vendors/' . $filename;
                    if (!is_dir('uploads/vendors')) mkdir('uploads/vendors', 0777, true);
                    if (move_uploaded_file($_FILES['store_logo']['tmp_name'], $target)) {
                        $logoPath = 'uploads/vendors/' . $filename;
                    }
                }

                // Handle Banner Upload
                $bannerPath = $_POST['current_banner'] ?? $vendor['store_banner'];
                if (isset($_FILES['store_banner']) && $_FILES['store_banner']['error'] === UPLOAD_ERR_OK) {
                    $ext = pathinfo($_FILES['store_banner']['name'], PATHINFO_EXTENSION);
                    $filename = 'v_banner_' . $userId . '_' . time() . '.' . $ext;
                    $target = 'uploads/banners/' . $filename;
                    if (!is_dir('uploads/banners')) mkdir('uploads/banners', 0777, true);
                    if (move_uploaded_file($_FILES['store_banner']['tmp_name'], $target)) {
                        $bannerPath = 'uploads/banners/' . $filename;
                    }
                }

                $stmt = $db->prepare("UPDATE vendors SET store_name = ?, store_description = ?, location = ?, province_id = ?, city_id = ?, store_logo = ?, store_banner = ? WHERE user_id = ?");
                $stmt->execute([$storeName, $description, $_POST['location'] ?? '', $provinceId, $cityId, $logoPath, $bannerPath, $userId]);

                if (!empty($contactEmail)) {
                    $stmt = $db->prepare("UPDATE users SET email = ? WHERE id = ?");
                    $stmt->execute([$contactEmail, $userId]);
                }

                Session::setFlash('success', 'General settings updated successfully!');
            }
        } catch (\Exception $e) {
            Session::setFlash('error', $e->getMessage());
        }

        // Map action to tab for redirect (Using Indonesian labels as required by the view)
        $tabs = [
            'update_password' => 'Keamanan',
            'add_payment' => 'Pembayaran',
            'update_notifications' => 'Notifikasi',
            'request_withdrawal' => 'Pembayaran',
            'general' => 'Umum'
        ];
        $activeTab = $tabs[$action] ?? 'Umum';

        if ($action === 'general') {
            return $this->redirect('/store/' . $vendor['id']);
        }
        return $this->redirect('/vendor/settings?tab=' . $activeTab);
    }
}
