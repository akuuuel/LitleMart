<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Session;
use App\Core\Response;

class AuthMiddleware {
    public function handle(Request $request) {
        // Prevent browser caching for authenticated routes
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

        $session = new Session();
        if (!$session->has('user_id')) {
            $response = new Response();
            $response->redirect('/login');
        }
    }
}
