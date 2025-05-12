<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Auth::index');

// Auth Routes
$routes->group('auth', function ($routes) {
    $routes->get('', 'Auth::index');
    $routes->post('login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
    $routes->get('register', 'Auth::register');
    $routes->post('doRegister', 'Auth::doRegister');
});

// Dashboard Routes
$routes->get('dashboard', 'Dashboard::index');

// Obat Routes
$routes->group('obat', function ($routes) {
    $routes->get('', 'Obat::index');
    $routes->get('tambah', 'Obat::tambah');
    $routes->post('simpan', 'Obat::simpan');
    $routes->get('edit/(:num)', 'Obat::edit/$1');
    $routes->post('update/(:num)', 'Obat::update/$1');
    $routes->get('hapus/(:num)', 'Obat::hapus/$1');
});

// Supplier Routes
$routes->group('supplier', function ($routes) {
    $routes->get('', 'Supplier::index');
    $routes->get('tambah', 'Supplier::tambah');
    $routes->post('simpan', 'Supplier::simpan');
    $routes->get('edit/(:num)', 'Supplier::edit/$1');
    $routes->post('update/(:num)', 'Supplier::update/$1');
    $routes->get('hapus/(:num)', 'Supplier::hapus/$1');
});

// Member Routes
$routes->group('member', function ($routes) {
    $routes->get('', 'Member::index');
    $routes->get('tambah', 'Member::tambah');
    $routes->post('simpan', 'Member::simpan');
    $routes->post('simpanAjax', 'Member::simpanAjax');
    $routes->get('edit/(:num)', 'Member::edit/$1');
    $routes->post('update/(:num)', 'Member::update/$1');
    $routes->get('hapus/(:num)', 'Member::hapus/$1');
    $routes->get('riwayat/(:num)', 'Member::riwayat/$1');
});

// Admin Routes
$routes->group('admin', function ($routes) {
    $routes->get('', 'Admin::index');
    $routes->get('tambah', 'Admin::tambah');
    $routes->post('simpan', 'Admin::simpan');
    $routes->get('edit/(:num)', 'Admin::edit/$1');
    $routes->post('update/(:num)', 'Admin::update/$1');
    $routes->get('hapus/(:num)', 'Admin::hapus/$1');
});

// Transaksi Routes
$routes->group('transaksi', function ($routes) {
    $routes->get('', 'Transaksi::index');
    $routes->get('tambah', 'Transaksi::tambah');
    $routes->post('getObatById', 'Transaksi::getObatById');
    $routes->post('simpan', 'Transaksi::simpan');
    $routes->get('detail/(:num)', 'Transaksi::detail/$1');
    $routes->get('struk/(:num)', 'Transaksi::struk/$1');
    $routes->get('hapus/(:num)', 'Transaksi::hapus/$1');
    $routes->get('beliDariSupplier', 'Transaksi::beliDariSupplier');
    $routes->post('simpanPembelian', 'Transaksi::simpanPembelian');
});
