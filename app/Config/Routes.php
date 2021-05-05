<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Login::index');

$routes->get('Admin/Dashboard', 'Admin\Dashboard::index', ['filter' => 'ceklogin']);
// $routes->get('/admin/dashboard', 'Admin/Dashboard::index', ['filter' => 'ceklogin']);
$routes->add('notification', 'Guru\Notifikasi::notifikasi', ['filter' => 'ceklogin']);
$routes->add('countNotification', 'Guru\Notifikasi::countNotification', ['filter' => 'ceklogin']);
$routes->get('profil', 'Admin\Profil::index', ['filter' => 'ceklogin']);
$routes->add('updateprofil', 'Admin\Profil::updateProfil', ['filter' => 'ceklogin']);
$routes->add('changepassword', 'Admin\Profil::changePassword', ['filter' => 'ceklogin']);
$routes->group('admin', ['filter' => 'ceklogin'], function ($routes) {
	$routes->get('/', 'Admin\Dashboard::index');
	$routes->get('suratmasuk', 'Admin\SuratMasuk::index');
	$routes->get('suratkeluar', 'Admin\SuratKeluar::index');
	$routes->get('laporansuratmasuk', 'Admin\LaporanSuratMasuk::index');
	$routes->get('laporansuratkeluar', 'Admin\LaporanSuratKeluar::index');
	$routes->get('users', 'Admin\Users::index');
});
$routes->group('guru', ['filter' => 'ceklogin'], function ($routes) {
	$routes->get('/', 'Guru\Home::index');
	$routes->add('listData', 'Guru\Home::ajax_list');
});
// $routes->group('suratmasuk', ['filter' => 'ceklogin'], function ($routes) {
// 	$routes->get('/', 'Admin/SuratMasuk::index');
// 	$routes->get('ajax_list', 'Admin/SuratMasuk::ajax_list');
// });
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
