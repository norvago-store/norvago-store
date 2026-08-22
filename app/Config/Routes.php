<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Public / Frontend Routes
$routes->get('/', 'Home::index');
$routes->get('/order/(:segment)', 'Order::detail/$1');
$routes->post('/order/check-id', 'Order::checkId');
$routes->post('/order/apply-voucher', 'Order::applyVoucher');
$routes->post('/order/checkout', 'Order::checkout');

// Invoice & Order Tracking
$routes->get('/invoice/(:segment)', 'Invoice::detail/$1');
$routes->get('/invoice/check-status/(:segment)', 'Invoice::checkStatus/$1');
$routes->get('/invoice/print/(:segment)', 'Invoice::printReceipt/$1');
$routes->get('/tracking', 'Home::tracking');

// Calculator Gaming Tools
$routes->get('/calculator/winrate', 'Home::calculatorWinrate');
$routes->get('/calculator/magic-wheel', 'Home::calculatorMagicWheel');

// Member Authentication & Panel
$routes->get('/login', 'Member\Auth::login');
$routes->post('/login-process', 'Member\Auth::loginProcess');
$routes->get('/register', 'Member\Auth::register');
$routes->post('/register-process', 'Member\Auth::registerProcess');
$routes->get('/logout', 'Member\Auth::logout');
$routes->get('/member/dashboard', 'Member\Dashboard::index');

// Self-Hosted QRIS / Bank Mutation Webhook Automation
$routes->post('/api/webhook/qris-mutation', 'Api\Webhook::qrisMutation');
$routes->get('/api/webhook/qris-mutation', 'Api\Webhook::qrisMutation');

// Admin Auth
$routes->get('/admin/login', 'Admin\Auth::login');
$routes->post('/admin/login-process', 'Admin\Auth::loginProcess');
$routes->get('/admin/logout', 'Admin\Auth::logout');

// Admin Panel (Protected by adminAuth filter)
$routes->group('admin', ['filter' => 'adminAuth'], static function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Games Management
    $routes->get('games', 'Admin\Games::index');
    $routes->get('games/create', 'Admin\Games::create');
    $routes->get('games/edit/(:num)', 'Admin\Games::edit/$1');
    $routes->post('games/save', 'Admin\Games::save');
    $routes->get('games/delete/(:num)', 'Admin\Games::delete/$1');

    // Products Management
    $routes->get('products', 'Admin\Products::index');
    $routes->get('products/create', 'Admin\Products::create');
    $routes->get('products/edit/(:num)', 'Admin\Products::edit/$1');
    $routes->post('products/save', 'Admin\Products::save');
    $routes->get('products/delete/(:num)', 'Admin\Products::delete/$1');

    // Orders & Transactions Management
    $routes->get('orders', 'Admin\Orders::index');
    $routes->get('orders/export-pdf', 'Admin\Orders::exportPdf');
    $routes->get('orders/detail/(:num)', 'Admin\Orders::detail/$1');
    $routes->post('orders/mark-paid/(:num)', 'Admin\Orders::markPaid/$1');
    $routes->post('orders/retry-provider/(:num)', 'Admin\Orders::retryProvider/$1');
    $routes->post('orders/update-status/(:num)', 'Admin\Orders::updateStatus/$1');
    $routes->get('orders/delete/(:num)', 'Admin\Orders::delete/$1');
    $routes->post('orders/delete/(:num)', 'Admin\Orders::delete/$1');

    // QRIS Mutations & Logs
    $routes->get('mutations', 'Admin\Mutations::index');
    $routes->post('mutations/match-manual', 'Admin\Mutations::matchManual');

    // Payment Methods & Admin Fee
    $routes->get('payments', 'Admin\Payments::index');
    $routes->get('payments/edit/(:num)', 'Admin\Payments::edit/$1');
    $routes->post('payments/save', 'Admin\Payments::save');

    // Banners Management
    $routes->get('banners', 'Admin\Banners::index');
    $routes->post('banners/save', 'Admin\Banners::save');
    $routes->get('banners/delete/(:num)', 'Admin\Banners::delete/$1');

    // Promo & Vouchers
    $routes->get('vouchers', 'Admin\Vouchers::index');
    $routes->post('vouchers/save', 'Admin\Vouchers::save');
    $routes->get('vouchers/delete/(:num)', 'Admin\Vouchers::delete/$1');

    // Settings & QRIS Setup
    $routes->get('settings', 'Admin\Settings::index');
    $routes->post('settings/save', 'Admin\Settings::save');
    $routes->post('settings/generate-secret', 'Admin\Settings::generateSecret');
});
