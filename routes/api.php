<?php

/** @var \App\Core\Router $router */

$router->post('/api/payments/webhook', [App\Controllers\Api\PaymentController::class, 'webhook']);
$router->post('/api/payments/finish', [App\Controllers\Api\PaymentController::class, 'finish']);
