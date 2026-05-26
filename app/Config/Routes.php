<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'ArtistController::index');
$routes->get('artist',               'ArtistController::index');
$routes->get('artist/create',            'ArtistController::create');
$routes->post('artist/store',            'ArtistController::store');
$routes->get('artist/(:num)/edit',       'ArtistController::edit/$1');
$routes->post('artist/(:num)/update',    'ArtistController::update/$1');
$routes->post('artist/(:num)/delete',    'ArtistController::delete/$1');