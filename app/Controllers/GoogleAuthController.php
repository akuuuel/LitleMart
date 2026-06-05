<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Core\Session;

class GoogleAuthController extends Controller {

    private function getClientId(): string {
        return $_ENV['GOOGLE_CLIENT_ID'] ?? '';
    }

    private function getClientSecret(): string {
        return $_ENV['GOOGLE_CLIENT_SECRET'] ?? '';
    }

    private function getRedirectUri(): string {
        // Detect protocol with support for ngrok/proxies
        $protocol = 'http://';
        if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || 
            (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            $protocol = 'https://';
        }

        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $basePath = ($scriptName === '/' ? '' : $scriptName);
        
        return $protocol . $host . $basePath . '/auth/google/callback';
    }

    /**
     * Redirect user to Google OAuth consent screen.
     */
    public function googleRedirect() {
        $state = bin2hex(random_bytes(16));
        Session::set('oauth_state', $state);

        $params = http_build_query([
            'client_id'     => $this->getClientId(),
            'redirect_uri'  => $this->getRedirectUri(),
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $state,
            'access_type'   => 'online',
        ]);

        header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . $params);
        exit;
    }

    /**
     * Handle Google callback and log the user in.
     */
    public function callback($request) {
        // Verify state to prevent CSRF
        $state = $request->input('state');
        if (!$state || $state !== Session::get('oauth_state')) {
            Session::setFlash('error', 'Invalid OAuth state. Please try again.');
            return $this->redirect_to('/login');
        }
        Session::remove('oauth_state');

        $code = $request->input('code');
        if (!$code) {
            Session::setFlash('error', 'Google login was cancelled or failed.');
            return $this->redirect_to('/login');
        }

        // Exchange code for access token
        $tokenResponse = $this->post('https://oauth2.googleapis.com/token', [
            'code'          => $code,
            'client_id'     => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'redirect_uri'  => $this->getRedirectUri(),
            'grant_type'    => 'authorization_code',
        ]);

        if (!isset($tokenResponse['access_token'])) {
            Session::setFlash('error', 'Failed to authenticate with Google. Please try again.');
            return $this->redirect_to('/login');
        }

        // Get user info from Google
        $userInfo = $this->get(
            'https://www.googleapis.com/oauth2/v3/userinfo',
            $tokenResponse['access_token']
        );

        if (!isset($userInfo['email'])) {
            Session::setFlash('error', 'Could not retrieve your Google account information.');
            return $this->redirect_to('/login');
        }

        // Find or create user in database
        $db   = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$userInfo['email']]);
        $user = $stmt->fetch();

        if (!$user) {
            // Create new user from Google data
            $userId = \Ramsey\Uuid\Uuid::uuid4()->toString();
            $stmt   = $db->prepare("
                INSERT INTO users (id, name, email, password, is_verified, avatar, created_at)
                VALUES (?, ?, ?, '', 1, ?, NOW())
            ");
            $stmt->execute([
                $userId,
                $userInfo['name']    ?? $userInfo['email'],
                $userInfo['email'],
                $userInfo['picture'] ?? null,
            ]);

            // Assign default 'customer' role
            $roleStmt = $db->prepare("SELECT id FROM roles WHERE name = 'customer'");
            $roleStmt->execute();
            $roleId = $roleStmt->fetchColumn();
            if ($roleId) {
                $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)")
                   ->execute([$userId, $roleId]);
            }

            $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
        }

        // Load roles
        $rolesStmt = $db->prepare("SELECT r.name FROM roles r JOIN user_roles ur ON r.id = ur.role_id WHERE ur.user_id = ?");
        $rolesStmt->execute([$user['id']]);
        $roles = $rolesStmt->fetchAll(\PDO::FETCH_COLUMN);

        // Create session
        Session::set('user_id',   $user['id']);
        Session::set('user_name', $user['name']);
        Session::set('user_email',$user['email']);
        Session::set('roles',     $roles);

        // Redirect based on role
        if (in_array('admin', $roles)) {
            return $this->redirect_to('/admin/dashboard');
        }
        if (in_array('vendor', $roles)) {
            return $this->redirect_to('/vendor/dashboard');
        }
        return $this->redirect_to('/');
    }

    // --- HTTP helpers ---

    private function post(string $url, array $data): array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true) ?? [];
    }

    private function get(string $url, string $accessToken): array {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => ["Authorization: Bearer $accessToken"],
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result, true) ?? [];
    }

    private function redirect_to(string $path) {
        header('Location: ' . url($path));
        exit;
    }
}
