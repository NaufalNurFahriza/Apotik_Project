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

// User/TTK Routes (ganti dari admin)
$routes->group('user', function ($routes) {
    $routes->get('', 'User::index');
    $routes->get('tambah', 'User::tambah');
    $routes->post('simpan', 'User::simpan');
    $routes->get('edit/(:num)', 'User::edit/$1');
    $routes->post('update/(:num)', 'User::update/$1');
    $routes->get('hapus/(:num)', 'User::hapus/$1');
});

// Untuk backward compatibility, redirect admin ke user
$routes->group('admin', function ($routes) {
    $routes->get('', 'User::index');
    $routes->get('tambah', 'User::tambah');
    $routes->post('simpan', 'User::simpan');
    $routes->get('edit/(:num)', 'User::edit/$1');
    $routes->post('update/(:num)', 'User::update/$1');
    $routes->get('hapus/(:num)', 'User::hapus/$1');
});

// Transaksi Penjualan Routes
$routes->group('transaksi-penjualan', function ($routes) {
    $routes->get('', 'TransaksiPenjualan::index');
    $routes->get('tambah', 'TransaksiPenjualan::tambah');
    $routes->post('getObatById', 'TransaksiPenjualan::getObatById');
    $routes->post('simpan', 'TransaksiPenjualan::simpan');
    $routes->get('detail/(:num)', 'TransaksiPenjualan::detail/$1');
    $routes->get('struk', 'TransaksiPenjualan::daftarStruk');
    $routes->get('struk/(:num)', 'TransaksiPenjualan::struk/$1');
    $routes->get('faktur/(:num)', 'TransaksiPenjualan::faktur/$1');
    $routes->get('hapus/(:num)', 'TransaksiPenjualan::hapus/$1');
    $routes->get('laporan', 'TransaksiPenjualan::laporan');
});

// Transaksi Pembelian Routes
$routes->group('transaksi-pembelian', function ($routes) {
    $routes->get('', 'TransaksiPembelian::index');
    $routes->get('tambah', 'TransaksiPembelian::tambah');
    $routes->post('getObatById', 'TransaksiPembelian::getObatById');
    $routes->post('getObatBySupplier', 'TransaksiPembelian::getObatBySupplier');
    $routes->post('simpan', 'TransaksiPembelian::simpan');
    $routes->get('detail/(:num)', 'TransaksiPembelian::detail/$1');
    $routes->get('faktur', 'TransaksiPembelian::daftarFaktur');
    $routes->get('faktur/(:num)', 'TransaksiPembelian::faktur/$1');
    $routes->get('struk/(:num)', 'TransaksiPembelian::struk/$1');
    $routes->get('hapus/(:num)', 'TransaksiPembelian::hapus/$1');
    $routes->get('laporan', 'TransaksiPembelian::laporan');
    $routes->get('beliDariSupplier', 'TransaksiPembelian::beliDariSupplier');
    $routes->post('simpanPembelian', 'TransaksiPembelian::simpanPembelian');
});

// Laporan Routes
$routes->group('laporan', function ($routes) {
    $routes->get('penjualan', 'Laporan::penjualan');
    $routes->get('pembelian', 'Laporan::pembelian');
    $routes->get('obat-terlaris', 'Laporan::obatTerlaris');
    $routes->get('member', 'Laporan::member');
    $routes->get('supplier', 'Laporan::supplier');
    $routes->get('keuangan', 'Laporan::keuangan');
    $routes->get('stok', 'Laporan::stok');
});

// Untuk backward compatibility, redirect transaksi ke transaksi-penjualan
$routes->group('transaksi', function ($routes) {
    $routes->get('', 'TransaksiPenjualan::index');
    $routes->get('tambah', 'TransaksiPenjualan::tambah');
    $routes->post('getObatById', 'TransaksiPenjualan::getObatById');
    $routes->post('simpan', 'TransaksiPenjualan::simpan');
    $routes->get('detail/(:num)', 'TransaksiPenjualan::detail/$1');
    $routes->get('struk/(:num)', 'TransaksiPenjualan::struk/$1');
    $routes->get('hapus/(:num)', 'TransaksiPenjualan::hapus/$1');
    $routes->get('beliDariSupplier', 'TransaksiPembelian::beliDariSupplier');
    $routes->post('simpanPembelian', 'TransaksiPembelian::simpanPembelian');
});
