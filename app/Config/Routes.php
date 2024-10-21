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
$routes->delete('auth/delete', 'AuthController::deleteOne');
$routes->post('auth/forgotPassword', 'AuthController::forgotPassword');
$routes->get('auth/generate/(:num)', 'AuthController::generatePassword/$1');


$routes->get('send-email', 'EmailController::send_email');
