<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', ['as' => 'main_page', 'uses' => 'DomainsController@showMainPage']);

$router->post('/domains', ['as' => 'add_domain', 'uses' => 'DomainsController@addDomain']);

$router->get('/domains', ['as' => 'domains', 'uses' => 'DomainsController@getDomains']);

$router->get('/domains/{id}', ['as' => 'domain', 'uses' => 'DomainsController@getDomain']);
