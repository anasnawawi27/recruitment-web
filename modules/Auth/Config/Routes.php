<?php

$routes->get('login', '\Modules\Auth\Controllers\Auth::login');
$routes->get('register', '\Modules\Auth\Controllers\Auth::register', ['as' => 'register']);
$routes->post('login', '\Modules\Auth\Controllers\Auth::login', ['as' => 'login']);
$routes->post('register', '\Modules\Auth\Controllers\Auth::register');