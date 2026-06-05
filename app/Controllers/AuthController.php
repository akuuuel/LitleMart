<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Session;
use App\Models\User;
use App\Core\Database;
use Ramsey\Uuid\Uuid;

class AuthController extends Controller {
    public function showLogin() {
        return $this->view('auth.login');
    }

    public function login(Request $request) {
        $email = $request->input('email');
        $password = $request->input('password');

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_email', $user['email']);
            // Clear guest cart so badge starts clean for this user
            Session::remove('cart');
            
            $roles = $userModel->getRoles($user['id']);
            Session::set('roles', $roles);

            // If vendor, store vendor_id in session
            if (in_array('vendor', $roles)) {
                $db = Database::getInstance()->getConnection();
                $stmt = $db->prepare("SELECT id FROM vendors WHERE user_id = ?");
                $stmt->execute([$user['id']]);
                $vendor = $stmt->fetch();
                if ($vendor) {
                    Session::set('vendor_id', $vendor['id']);
                }
            }
            
            return $this->redirect('/');
        }

        Session::setFlash('error', 'Invalid email or password');
        return $this->redirect('/login');
    }

    public function showRegister() {
        return $this->view('auth.register');
    }

    public function register(Request $request) {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = password_hash($request->input('password'), PASSWORD_BCRYPT);
        
        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            Session::setFlash('error', 'Email already exists');
            return $this->redirect('/register');
        }

        $userId = Uuid::uuid4()->toString();
        $userModel->create([
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Default role: customer
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, (SELECT id FROM roles WHERE name = 'customer'))");
        $stmt->execute([$userId]);

        Session::setFlash('success', 'Registration successful. Please login.');
        return $this->redirect('/login');
    }

    public function logout() {
        Session::destroy();
        return $this->redirect('/login');
    }
}
