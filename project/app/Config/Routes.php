<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->group('api', function($routes){
    // Products Routes
    $routes->group('products', function ($routes){
        $routes->get('/', 'Product::index');
        $routes->post('create', 'Product::create');
        $routes->get('edit/(:num)', 'Product::edit/$1');
        $routes->post('update/(:num)', 'Product::update/$1');
        $routes->post('sdelete/(:num)', 'Product::softDelete/$1');
        $routes->delete('delete/(:num)', 'Product::delete/$1');
        $routes->get('search', 'Product::search');
        $routes->get('list', 'Product::list');
        $routes->get('get/(:num)', 'Product::get/$1');
    });
});


?>