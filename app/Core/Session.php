<?php

namespace App\Core;

class Session {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        self::init();
        $_SESSION[$key] = $value;
    }

    public static function get($key, $default = null) {
        self::init();
        return $_SESSION[$key] ?? $default;
    }

    public static function remove($key) {
        self::init();
        unset($_SESSION[$key]);
    }

    public static function has($key) {
        self::init();
        return isset($_SESSION[$key]);
    }

    public static function destroy() {
        self::init();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }

    public static function setFlash($key, $message) {
        self::set('flash_' . $key, $message);
    }

    public static function getFlash($key) {
        $message = self::get('flash_' . $key);
        self::remove('flash_' . $key);
        return $message;
    }

    public static function hasFlash($key) {
        return self::has('flash_' . $key);
    }
}
