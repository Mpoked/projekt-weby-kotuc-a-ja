<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Úvodní stránka → seznam alb
$routes->get('/', 'AlbumController::index');

// Auth
$routes->get('login',    'AuthController::login');
$routes->post('login',   'AuthController::loginPost');
$routes->get('register', 'AuthController::register');
$routes->post('register','AuthController::registerPost');
$routes->post('logout',  'AuthController::logout');

// Genre (veřejné)
$routes->get('genre',          'GenreController::index');
$routes->get('genre/(:num)',   'GenreController::show/$1');

// Artist
$routes->get('artist',                     'ArtistController::index');
$routes->get('artist/(:num)',              'ArtistController::show/$1');
$routes->get('artist/create',              'ArtistController::create',        ['filter' => 'auth:admin']);
$routes->post('artist/store',              'ArtistController::store',         ['filter' => 'auth:admin']);
$routes->get('artist/(:num)/edit',         'ArtistController::edit/$1',       ['filter' => 'auth:admin']);
$routes->post('artist/(:num)/update',      'ArtistController::update/$1',     ['filter' => 'auth:admin']);
$routes->post('artist/(:num)/delete',      'ArtistController::delete/$1',     ['filter' => 'auth:admin']);

// Album
$routes->get('album',                      'AlbumController::index');
$routes->get('album/create',               'AlbumController::create',         ['filter' => 'auth:admin']);
$routes->post('album/store',               'AlbumController::store',          ['filter' => 'auth:admin']);
$routes->get('album/(:num)',               'AlbumController::show/$1');
$routes->get('album/(:num)/pdf',           'AlbumController::pdf/$1');
$routes->get('album/(:num)/edit',          'AlbumController::edit/$1',        ['filter' => 'auth:admin']);
$routes->post('album/(:num)/update',       'AlbumController::update/$1',      ['filter' => 'auth:admin']);
$routes->post('album/(:num)/delete',       'AlbumController::delete/$1',      ['filter' => 'auth:admin']);

// Track (nested pod album, 2 parametry = splní požadavek na routu se 2+ parametry)
$routes->get('album/(:num)/track/create',              'TrackController::create/$1',       ['filter' => 'auth:admin']);
$routes->post('album/(:num)/track/store',              'TrackController::store/$1',        ['filter' => 'auth:admin']);
$routes->get('album/(:num)/track/(:num)/edit',         'TrackController::edit/$1/$2',      ['filter' => 'auth:admin']);
$routes->post('album/(:num)/track/(:num)/update',      'TrackController::update/$1/$2',    ['filter' => 'auth:admin']);
$routes->post('album/(:num)/track/(:num)/delete',      'TrackController::delete/$1/$2',    ['filter' => 'auth:admin']);

// Review
$routes->post('album/(:num)/review/store',             'ReviewController::store/$1',       ['filter' => 'auth']);
$routes->post('album/(:num)/review/(:num)/delete',     'ReviewController::delete/$1/$2',   ['filter' => 'auth:admin']);