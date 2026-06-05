<?php

namespace App\Core;

class Controller {
    protected function view($view, $data = []) {
        $response = new Response();
        return $response->view($view, $data);
    }

    protected function json($data, $code = 200) {
        $response = new Response();
        return $response->json($data, $code);
    }

    protected function redirect($url) {
        $response = new Response();
        return $response->redirect($url);
    }

    protected function back() {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? url('/')));
        exit;
    }
}
