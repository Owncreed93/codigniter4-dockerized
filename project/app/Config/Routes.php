<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Products Routes
$routes->get('/products', 'Product::index');
$routes->post('/products/create', 'Product::create');
$routes->get('/products/edit/(:num)', 'Product::edit/$1');
$routes->post('/products/update/(:num)', 'Product::update/$1');
$routes->get('/products/delete/(:num)', 'Product::delete/$1');
$routes->get('/products/search', 'Product::search');

?>