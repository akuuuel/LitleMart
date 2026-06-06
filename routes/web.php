<?php

/** @var \App\Core\Router $router */

$router->get('/', [App\Controllers\HomeController::class, 'index']);
$router->get('/login', [App\Controllers\AuthController::class, 'showLogin']);
$router->post('/login', [App\Controllers\AuthController::class, 'login']);
$router->get('/register', [App\Controllers\AuthController::class, 'showRegister']);
$router->get('/vendor/register', [App\Controllers\AuthController::class, 'showRegister']);
$router->post('/register', [App\Controllers\AuthController::class, 'register']);
$router->post('/logout', [App\Controllers\AuthController::class, 'logout']);

// Forgot Password
$router->get('/forgot-password', [App\Controllers\AuthController::class, 'showForgotPassword']);
$router->post('/forgot-password', [App\Controllers\AuthController::class, 'forgotPassword']);
$router->get('/reset-password/{token}', [App\Controllers\AuthController::class, 'showResetPassword']);
$router->post('/reset-password', [App\Controllers\AuthController::class, 'resetPassword']);

$router->get('/auth/google', [App\Controllers\GoogleAuthController::class, 'googleRedirect']);
$router->get('/auth/google/callback', [App\Controllers\GoogleAuthController::class, 'callback']);

// Categories
$router->get('/categories', [App\Controllers\CategoryController::class, 'index']);

// Static Pages
$router->get('/help', [App\Controllers\HelpController::class, 'index']);
$router->get('/about', [App\Controllers\AboutController::class, 'index']);
$router->get('/career', [App\Controllers\HelpController::class, 'career']);
$router->post('/career/send-cv', [App\Controllers\HelpController::class, 'sendCv']);
$router->post('/career/ask-culture', [App\Controllers\HelpController::class, 'askCulture']);
$router->get('/contact', [App\Controllers\HelpController::class, 'contact']);
$router->get('/terms', [App\Controllers\HelpController::class, 'terms']);
$router->get('/privacy', [App\Controllers\HelpController::class, 'privacy']);

// Products
$router->get('/products', [App\Controllers\ProductController::class, 'index']);
$router->get('/products/{id}', [App\Controllers\ProductController::class, 'show']);
$router->get('/store/{id}', [App\Controllers\VendorShopController::class, 'index']);
$router->get('/user/{id}', [App\Controllers\UserProfileController::class, 'show'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/settings', [App\Controllers\ProfileSettingsController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/settings', [App\Controllers\ProfileSettingsController::class, 'update'], [App\Middlewares\AuthMiddleware::class]);

// Cart
$router->get('/cart', [App\Controllers\CartController::class, 'index']);
$router->post('/cart/add', [App\Controllers\CartController::class, 'add']);
$router->post('/cart/update', [App\Controllers\CartController::class, 'update']);
$router->get('/cart/remove/{id}', [App\Controllers\CartController::class, 'remove']);

// Checkout
$router->get('/checkout', [App\Controllers\CheckoutController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/checkout/prepare', [App\Controllers\CheckoutController::class, 'prepare'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/checkout/buynow', [App\Controllers\CheckoutController::class, 'buyNow']);
$router->post('/checkout/process', [App\Controllers\CheckoutController::class, 'process'], [App\Middlewares\AuthMiddleware::class]);

// Orders
$router->get('/orders', [App\Controllers\OrderController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/orders/delete', [App\Controllers\OrderController::class, 'delete'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/orders/confirm-receipt', [App\Controllers\OrderController::class, 'confirmReceipt'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/orders/get-snap-token', [App\Controllers\OrderController::class, 'getSnapToken'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/orders/success', [App\Controllers\OrderController::class, 'success'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/orders/pending', [App\Controllers\OrderController::class, 'pending'], [App\Middlewares\AuthMiddleware::class]);

// Chat System
$router->get('/messages', [App\Controllers\ChatController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
// Notifications
$router->get('/notifications', [App\Controllers\NotificationController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/notifications/mark-read', [App\Controllers\NotificationController::class, 'markAsRead'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/notifications/mark-all-read', [App\Controllers\NotificationController::class, 'markAllAsRead'], [App\Middlewares\AuthMiddleware::class]);

// API Routes
$router->get('/api/shipping/provinces', [App\Controllers\Api\ShippingApiController::class, 'provinces']);
$router->get('/api/shipping/cities', [App\Controllers\Api\ShippingApiController::class, 'cities']);
$router->post('/api/shipping/calculate', [App\Controllers\Api\ShippingApiController::class, 'calculate']);
$router->get('/api/notifications/unread-count', [App\Controllers\NotificationController::class, 'unreadCount'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/api/products/latest', [App\Controllers\HomeController::class, 'apiFeatured']);
$router->get('/api/cart/count', [App\Controllers\CartController::class, 'countCart']);

// Admin Routes
$router->get('/admin/dashboard', [App\Controllers\Admin\DashboardController::class, 'index'], [App\Middlewares\AuthMiddleware::class, App\Middlewares\AdminMiddleware::class]);
$router->get('/admin/users', [App\Controllers\Admin\DashboardController::class, 'users'], [App\Middlewares\AuthMiddleware::class, App\Middlewares\AdminMiddleware::class]);
$router->post('/admin/users/deactivate', [App\Controllers\Admin\DashboardController::class, 'deactivateUser'], [App\Middlewares\AuthMiddleware::class, App\Middlewares\AdminMiddleware::class]);
$router->get('/admin/vendors', [App\Controllers\Admin\DashboardController::class, 'vendors'], [App\Middlewares\AuthMiddleware::class, App\Middlewares\AdminMiddleware::class]);
$router->post('/admin/vendors/verify', [App\Controllers\Admin\DashboardController::class, 'verifyVendor'], [App\Middlewares\AuthMiddleware::class, App\Middlewares\AdminMiddleware::class]);
$router->post('/admin/vendors/suspend', [App\Controllers\Admin\DashboardController::class, 'suspendVendor'], [App\Middlewares\AuthMiddleware::class, App\Middlewares\AdminMiddleware::class]);
$router->get('/admin/analytics', [App\Controllers\Admin\DashboardController::class, 'analytics'], [App\Middlewares\AuthMiddleware::class, App\Middlewares\AdminMiddleware::class]);
$router->get('/admin/orders', [App\Controllers\Admin\DashboardController::class, 'orders'], [App\Middlewares\AuthMiddleware::class, App\Middlewares\AdminMiddleware::class]);
$router->post('/admin/announcement', [App\Controllers\Admin\DashboardController::class, 'sendAnnouncement'], [App\Middlewares\AuthMiddleware::class, App\Middlewares\AdminMiddleware::class]);

// Vendor Routes
$router->get('/vendor/dashboard', [App\Controllers\Vendor\DashboardController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/products', [App\Controllers\Vendor\ProductController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/products/create', [App\Controllers\Vendor\ProductController::class, 'create'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/products/edit/{id}', [App\Controllers\Vendor\ProductController::class, 'edit'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/vendor/products', [App\Controllers\Vendor\ProductController::class, 'store'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/vendor/products/update/{id}', [App\Controllers\Vendor\ProductController::class, 'update'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/vendor/products/delete/{id}', [App\Controllers\Vendor\ProductController::class, 'delete'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/orders', [App\Controllers\Vendor\OrderController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/vendor/orders/status', [App\Controllers\Vendor\OrderController::class, 'updateStatus'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/vendor/orders/delete', [App\Controllers\Vendor\OrderController::class, 'delete'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/customers', [App\Controllers\Vendor\CustomerController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/analytics', [App\Controllers\Vendor\AnalyticsController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/search', [App\Controllers\Vendor\SearchController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/notifications', [App\Controllers\Vendor\NotificationController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/vendor/notifications/mark-read', [App\Controllers\Vendor\NotificationController::class, 'markAsRead'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/notifications/mark-all-read', [App\Controllers\Vendor\NotificationController::class, 'markAllAsRead'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/help', [App\Controllers\HelpController::class, 'vendorHelp'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/profile', [App\Controllers\Vendor\ProfileController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/vendor/profile', [App\Controllers\Vendor\ProfileController::class, 'update'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/settings', [App\Controllers\Vendor\SettingsController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/vendor/settings', [App\Controllers\Vendor\SettingsController::class, 'update'], [App\Middlewares\AuthMiddleware::class]);
$router->get('/vendor/onboarding', [App\Controllers\Vendor\OnboardingController::class, 'index'], [App\Middlewares\AuthMiddleware::class]);
$router->post('/vendor/onboarding', [App\Controllers\Vendor\OnboardingController::class, 'store'], [App\Middlewares\AuthMiddleware::class]);
