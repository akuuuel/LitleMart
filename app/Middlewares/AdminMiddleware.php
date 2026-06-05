<?php

namespace App\Middlewares;

use App\Core\Request;
use App\Core\Session;
use App\Core\Response;

class AdminMiddleware {
    public function handle(Request $request) {
        $session = new Session();
        $roles = $session->get('roles', []);
        
        if (!in_array('admin', $roles)) {
            $response = new Response();
            $response->redirect('/');
        }
    }
}
