<?php

namespace App\Core;

class Request {
    public function getPath() {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }
        
        // Remove base path and index.php
        $scriptName = $_SERVER['SCRIPT_NAME']; // /LitleMart/public/index.php
        $basePath = str_replace('\\', '/', dirname($scriptName)); // /LitleMart/public
        
        if ($basePath !== '/' && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }
        
        // Force remove index.php if it's still there
        if (strpos($path, '/index.php') === 0) {
            $path = substr($path, 10);
        }
        
        $path = '/' . ltrim($path, '/');
        return $path;
    }

    public function getMethod() {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function input($key = null, $default = null) {
        $data = array_merge($_GET, $_POST);
        
        // Handle JSON input
        $json = json_decode(file_get_contents('php://input'), true);
        if (is_array($json)) {
            $data = array_merge($data, $json);
        }

        if ($key === null) return $data;
        return $data[$key] ?? $default;
    }

    public function all() {
        return $this->input();
    }

    public function getBody() {
        return $this->all();
    }

    public function isAjax() {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') || 
               (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
    }
}
