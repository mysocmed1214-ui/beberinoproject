<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// === PUBLIC SHOP ROUTES ===
$routes->get('/', 'Shop::index');
$routes->get('product/(:num)', 'Shop::detail/$1');
$routes->get('category/(:any)', 'Shop::category/$1');
$routes->get('category/(:any)', 'Shop::category/$1');
$routes->post('shop/review/(:num)', 'Shop::review/$1');
$routes->post('shop/review/(:num)', 'Shop::review/$1');

// POST route for AJAX buy
$routes->post('shop/buy/(:num)', 'Shop::buy/$1');

$routes->post('shop/buy', 'Shop::buy');





// === CART & CHECKOUT ===
$routes->get('cart', 'Cart::index');
$routes->post('cart/add', 'Cart::add');
$routes->post('cart/update', 'Cart::update');
$routes->get('cart/remove/(:num)', 'Cart::remove/$1');
$routes->post('cart/placeOrder', 'Cart::placeOrder');


// === USER AUTH ===
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/login', 'Auth::loginPost');
$routes->get('auth/register', 'Auth::register');
$routes->post('auth/register', 'Auth::registerPost');
$routes->get('auth/logout', 'Auth::logout');

// === ADMIN AUTH ===
$routes->get('auth/admin_login', 'Auth::admin_login');
$routes->post('auth/adminLoginPost', 'Auth::adminLoginPost');
$routes->get('auth/admin_logout', 'Auth::admin_logout');





// ✅ Admin Register Routes
$routes->get('auth/admin_register', 'Auth::admin_register');
$routes->post('auth/adminRegisterPost', 'Auth::adminRegisterPost');
$routes->get('maintenance', 'Maintenance::index');

// === ADMIN DASHBOARD ===
// NOTE: Only protect real admin pages, NOT login/register
$routes->group('admin', ['filter' => 'authAdmin'], static function ($routes) {
    $routes->get('/', 'Admin::dashboard');
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('products', 'Admin\Products::index');
$routes->get('products/create', 'Admin\Products::create');
$routes->post('products/store', 'Admin\Products::store');
$routes->get('products/edit/(:num)', 'Admin\Products::edit/$1');
$routes->post('products/update/(:num)', 'Admin\Products::update/$1');
$routes->get('products/delete/(:num)', 'Admin\Products::delete/$1');
$routes->get('types', 'Admin\Types::index');
$routes->post('types/store', 'Admin\Types::store');
$routes->get('types/delete/(:num)', 'Admin\Types::delete/$1');





    $routes->resource('users', ['controller' => 'Admin\Users']);
     // ✅ Fix here: Use Admin::orders method
    $routes->get('orders', 'Admin::orders');
    $routes->get('customers', 'Admin\Customers::index');
    $routes->post('customers/update/(:num)', 'Admin\Customers::update/$1'); // ✅ Add this
    $routes->get('customers/delete/(:num)', 'Admin\Customers::delete/$1');
    $routes->get('system', 'Admin\System::index');
$routes->post('system/toggle', 'Admin\System::toggleMode');

// ✅ Activity Logs route
    $routes->get('activity-logs', 'Admin\ActivityLogs::index');
    $routes->post('activity-logs/clear', 'Admin\ActivityLogs::clear');

});


