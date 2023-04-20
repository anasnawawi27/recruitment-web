<?php

$routes->group('admin', ['namespace' => '\Modules\Admin\Controllers'], function ($routes) {
    $routes->group('job-vacancy', function ($routes) {
        $routes->add('/', 'JobVacancies::index', ['as' => 'job_vacancies']);
        $routes->add('get_list', 'JobVacancies::get_list', ['as' => 'job_vacancy_list']);
        $routes->add('form/(:any)', 'JobVacancies::form/$1', ['as' => 'job_vacancy_form']);
        $routes->add('save', 'JobVacancies::save', ['as' => 'job_vacancy_save']);
        $routes->add('delete/(:any)', 'JobVacancies::delete/$1', ['as' => 'job_vacancy_delete']);
        $routes->add('get_total_questions', 'JobVacancies::getTotalQuestions', ['as' => 'get_total_questions']);
    });
    $routes->group('question-type', function ($routes) {
        $routes->add('/', 'QuestionTypes::index', ['as' => 'question_types']);
        $routes->add('get_list', 'QuestionTypes::get_list', ['as' => 'question_type_list']);
        $routes->add('form/(:any)', 'QuestionTypes::form/$1', ['as' => 'question_type_form']);
        $routes->add('save', 'QuestionTypes::save', ['as' => 'question_type_save']);
        $routes->add('delete/(:any)', 'QuestionTypes::delete/$1', ['as' => 'question_type_delete']);
    });
    $routes->group('question', function ($routes) {
        $routes->add('/', 'Questions::index', ['as' => 'questions']);
        $routes->add('get_list', 'Questions::get_list', ['as' => 'question_list']);
        $routes->add('form/(:any)', 'Questions::form/$1', ['as' => 'question_form']);
        $routes->add('save', 'Questions::save', ['as' => 'question_save']);
        $routes->add('delete/(:any)', 'Questions::delete/$1', ['as' => 'question_delete']);
    });
});

