<?php

$routes->group('users', ['namespace' => '\Modules\Users\Controllers'], function ($routes) {
    $routes->group('accounts', function ($routes) {
        $routes->add('/', 'Users::index', ['as' => 'user_accounts']);
        $routes->add('get_list', 'Users::get_list', ['as' => 'user_account_list']);
        $routes->add('form/(:any)', 'Users::form/$1', ['as' => 'user_account_form']);
        $routes->add('save', 'Users::save', ['as' => 'user_account_save']);
        $routes->add('delete/(:any)', 'Users::delete/$1', ['as' => 'user_account_delete']);
        $routes->add('detail/(:any)', 'Users::detail/$1', ['as' => 'user_account_detail']);
    });
    $routes->group('roles', function ($routes) {
        $routes->add('/', 'Roles::index', ['as' => 'user_roles']);
        $routes->add('get_list', 'Roles::get_list', ['as' => 'user_role_list']);
        $routes->add('form/(:any)', 'Roles::form/$1', ['as' => 'user_role_form']);
        $routes->add('save', 'Roles::save', ['as' => 'user_role_save']);
        $routes->add('delete/(:any)', 'Roles::delete/$1', ['as' => 'user_role_delete']);
    });
    $routes->group('permissions', function ($routes) {
        $routes->add('/', 'Permissions::index', ['as' => 'permissions']);
        $routes->add('get_list', 'Permissions::get_list', ['as' => 'permission_list']);
        $routes->add('form/(:any)', 'Permissions::form/$1', ['as' => 'permission_form']);
        $routes->add('save', 'Permissions::save', ['as' => 'permission_save']);
        $routes->add('delete/(:any)', 'Permissions::delete/$1', ['as' => 'permission_delete']);
    });
});
