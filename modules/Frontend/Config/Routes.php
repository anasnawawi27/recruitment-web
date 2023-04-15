<?php

$routes->group('vacancy', ['namespace' => '\Modules\Frontend\Controllers'], function ($routes) {
    $routes->add('/', 'Vacancies::index', ['as' => 'vacancies']);
    $routes->add('detail/(:any)', 'Vacancies::detail/$1', ['as' => 'vacancy_detail']);
    $routes->add('apply/(:any)', 'Vacancies::apply/$1', ['as' => 'vacancy_apply']);
});

$routes->group('job-application', ['namespace' => '\Modules\Frontend\Controllers'], function ($routes) {
    $routes->add('/', 'Vacancies::job_applications', ['as' => 'job_applications']);
});
