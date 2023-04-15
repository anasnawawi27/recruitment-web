<?php

$routes->group('human-resource', ['namespace' => '\Modules\HumanResources\Controllers'], function ($routes) {
    $routes->group('job-title', function ($routes) {
        $routes->add('/', 'JobTitles::index', ['as' => 'job_titles']);
        $routes->add('get_list', 'JobTitles::get_list', ['as' => 'job_title_list']);
        $routes->add('form/(:any)', 'JobTitles::form/$1', ['as' => 'job_title_form']);
        $routes->add('save', 'JobTitles::save', ['as' => 'job_title_save']);
        $routes->add('delete/(:any)', 'JobTitles::delete/$1', ['as' => 'job_title_delete']);
    });
    $routes->group('overtime', function ($routes) {
        $routes->add('/', 'Overtimes::index', ['as' => 'overtimes']);
        $routes->add('get_list', 'Overtimes::get_list', ['as' => 'overtime_list']);
        $routes->add('form/(:any)', 'Overtimes::form/$1', ['as' => 'overtime_form']);
        $routes->add('save', 'Overtimes::save', ['as' => 'overtime_save']);
        $routes->add('delete/(:any)', 'Overtimes::delete/$1', ['as' => 'overtime_delete']);
    });
    $routes->group('allowances', function ($routes) {
        $routes->add('/', 'Allowances::index', ['as' => 'allowances']);
        $routes->add('get_list', 'Allowances::get_list', ['as' => 'allowance_list']);
        $routes->add('form/(:any)', 'Allowances::form/$1', ['as' => 'allowance_form']);
        $routes->add('save', 'Allowances::save', ['as' => 'allowance_save']);
        $routes->add('delete/(:any)', 'Allowances::delete/$1', ['as' => 'allowance_delete']);
    });
    $routes->group('approvals', function ($routes) {
        $routes->add('/', 'Approvals::index', ['as' => 'approvals']);
        $routes->add('get_list', 'Approvals::get_list', ['as' => 'approval_list']);
        $routes->add('update/(:any)', 'Approvals::update/$1', ['as' => 'approval_update']);
        $routes->add('detail/(:any)', 'Approvals::detail/$1', ['as' => 'approval_detail']);
    });
});
