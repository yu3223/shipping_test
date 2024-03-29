<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
//$routes->get('/', 'Home::index');

$routes->options('/(:any)', 'Home::options', ['filter' => 'ApiAccessFilter']);

$routes->get('/', 'MembersController::index');
$routes->get('/register', 'MembersController::renderRegisterPage');
$routes->post('/register', 'MembersController::register');
$routes->get('/login', 'MembersController::renderLoginPage');
$routes->post('/login', 'MembersController::login');


$routes->group('/', ['filter' => 'JwtAuth','ApiAccessFilter'], function($routes)
{
    $routes->get('/home', 'MemberManage::index');
    $routes->get('/logout', 'MembersController::logout');

    $routes->get('/editMemberData', 'MemberManage::renderEditMemberDataPage');
    $routes->post('/editMemberData', 'MemberManage::update');
    $routes->delete('/delete', 'MemberManage::delete');

    $routes->get('/product', 'ProductsController::renderProductPage');
    $routes->post('/product', 'ProductsController::AddProduct');
});


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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
