<?php

$routes->group('vacancy', ['namespace' => '\Modules\Frontend\Controllers'], function ($routes) {
    $routes->add('/', 'Vacancies::index', ['as' => 'vacancies']);
    $routes->add('detail/(:any)', 'Vacancies::detail/$1', ['as' => 'vacancy_detail']);
    $routes->add('apply/(:any)', 'Vacancies::apply/$1', ['as' => 'vacancy_apply']);
    $routes->add('complete-data/(:any)', 'Vacancies::complete_data/$1', ['as' => 'complete_data']);
    $routes->add('not-qualified', 'Vacancies::not_qualified', ['as' => 'not_qualified']);
});

$routes->group('job-application', ['namespace' => '\Modules\Frontend\Controllers'], function ($routes) {
    $routes->add('/', 'JobApplications::index', ['as' => 'job_applications']);
    $routes->add('detail/(:any)', 'JobApplications::detail/$1', ['as' => 'job_application_detail']);
    $routes->add('psikotest/(:any)', 'JobApplications::psikotest/$1', ['as' => 'psikotest_session']);

});
