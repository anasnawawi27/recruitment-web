<?php

$routes->get('login', '\Modules\Auth\Controllers\Auth::login');
$routes->get('register', '\Modules\Auth\Controllers\Auth::register', ['as' => 'register']);
$routes->post('login', '\Modules\Auth\Controllers\Auth::login', ['as' => 'login']);
$routes->post('register', '\Modules\Auth\Controllers\Auth::register');
$routes->get('confirmation/(:any)', '\Modules\Auth\Controllers\Auth::confirmation/$1', ['as' => 'confirmation']);
$routes->get('confirmation_email/(:any)', '\Modules\Auth\Controllers\Auth::confirmation_email/$1', ['as' => 'confirmation_email']);
$routes->get('resend-activate-account', '\Modules\Auth\Controllers\Auth::resendActivation', ['as' => 'resend-activate-account']);