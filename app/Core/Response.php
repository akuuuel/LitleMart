<?php

namespace App\Core;

class Response {
    public function setStatusCode(int $code) {
        http_response_code($code);
    }

    public function json($data, int $code = 200) {
        header('Content-Type: application/json');
        $this->setStatusCode($code);
        echo json_encode($data);
        exit;
    }

    public function redirect(string $url) {
        $finalUrl = (strpos($url, '/') === 0) ? url($url) : $url;
        header("Location: $finalUrl");
        exit;
    }

    public function view(string $view, array $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../../resources/views/' . str_replace('.', '/', $view) . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View $view not found at $viewPath");
        }
    }
}
