<?php

$routes->group('administration', ['namespace' => '\Modules\Administrations\Controllers'], function ($routes) {
    $routes->group('job-vacancy', function ($routes) {
        $routes->add('/', 'JobVacancies::index', ['as' => 'job_vacancies']);
        $routes->add('get_list', 'JobVacancies::get_list', ['as' => 'job_vacancy_list']);
        $routes->add('form/(:any)', 'JobVacancies::form/$1', ['as' => 'job_vacancy_form']);
        $routes->add('save', 'JobVacancies::save', ['as' => 'job_vacancy_save']);
        $routes->add('delete/(:any)', 'JobVacancies::delete/$1', ['as' => 'job_vacancy_delete']);
    });
});
