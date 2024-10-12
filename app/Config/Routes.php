<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('auth/register', 'AuthController::register');
$routes->put('auth/update/(:num)', 'AuthController::updateUser/$1');
$routes->get('auth/find/(:num)', 'AuthController::getUser/$1');
$routes->post('auth/login', 'AuthController::login');
$routes->get('auth/users', 'AuthController::users');
$routes->delete('auth/users', 'AuthController::users');



$routes->post('children/register', 'ChildrenController::register');
$routes->put('children/update/(:num)', 'ChildrenController::upgrade/$1');
$routes->get('children/find/(:num)', 'ChildrenController::find/$1');
$routes->get('children/display/', 'ChildrenController::display');
$routes->get('children/delete/(:num)', 'ChildrenController::remove/$1');



$routes->post('receta/register', 'RecetaController::register');
$routes->post('receta/update/(:num)', 'RecetaController::upgrade/$1');
$routes->get('receta/find/(:num)', 'RecetaController::find/$1');
$routes->get('receta/display/', 'RecetaController::display');
$routes->get('receta/delete/(:num)', 'RecetaController::remove/$1');
$routes->get('receta/dia/', 'RecetaController::dia');


$routes->get('send-email', 'EmailController::send_email');
