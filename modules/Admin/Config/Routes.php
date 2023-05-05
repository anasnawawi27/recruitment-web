<?php

$routes->group('admin', ['namespace' => '\Modules\Admin\Controllers'], function ($routes) {
    $routes->group('job-vacancy', function ($routes) {
        $routes->add('/', 'JobVacancies::index', ['as' => 'job_vacancies']);
        $routes->add('get_list', 'JobVacancies::get_list', ['as' => 'job_vacancy_list']);
        $routes->add('form/(:any)', 'JobVacancies::form/$1', ['as' => 'job_vacancy_form']);
        $routes->add('save', 'JobVacancies::save', ['as' => 'job_vacancy_save']);
        $routes->add('delete/(:any)', 'JobVacancies::delete/$1', ['as' => 'job_vacancy_delete']);
        $routes->add('detail/(:any)', 'JobVacancies::detail/$1', ['as' => 'job_vacancy_detail']);
        $routes->add('save_interview', 'JobVacancies::save_interview', ['as' => 'save_interview']);
        $routes->add('save_schedule', 'JobVacancies::save_schedule', ['as' => 'save_schedule']);
        $routes->add('save_and_send', 'JobVacancies::save_and_send', ['as' => 'save_and_send']);
        $routes->add('get_total_questions', 'JobVacancies::getTotalQuestions', ['as' => 'get_total_questions']);
        $routes->add('set_accepted', 'JobVacancies::set_accepted', ['as' => 'set_accepted']);
        $routes->add('modal_detail_psikotest/(:any)', 'JobVacancies::modal_detail_psikotest/$1', ['as' => 'modal_detail_psikotest']);
    });
    $routes->group('applicant', function ($routes) {
        $routes->add('/', 'Applicants::index', ['as' => 'applicants']);
        $routes->add('get_list', 'Applicants::get_list', ['as' => 'applicant_list']);
        $routes->add('form/(:any)', 'Applicants::form/$1', ['as' => 'applicant_form']);
        $routes->add('save', 'Applicants::save', ['as' => 'applicant_save']);
        $routes->add('delete/(:any)', 'Applicants::delete/$1', ['as' => 'applicant_delete']);
        $routes->add('detail/(:any)', 'Applicants::detail/$1', ['as' => 'applicant_detail']);
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
        $routes->add('detail/(:any)', 'Questions::detail/$1', ['as' => 'question_detail']);
        $routes->add('save', 'Questions::save', ['as' => 'question_save']);
        $routes->add('delete/(:any)', 'Questions::delete/$1', ['as' => 'question_delete']);
    });

    $routes->group('users', function ($routes) {
        $routes->add('/', 'Users::index', ['as' => 'users']);
        $routes->add('get_list', 'Users::get_list', ['as' => 'user_list']);
        $routes->add('form/(:any)', 'Users::form/$1', ['as' => 'user_form']);
        $routes->add('save', 'Users::save', ['as' => 'user_save']);
        $routes->add('delete/(:any)', 'Users::delete/$1', ['as' => 'user_delete']);
        $routes->add('profile_form/', 'Users::profile_form', ['as' => 'user_profile_form']);
        $routes->add('profile_save/', 'Users::profile_save', ['as' => 'user_profile_save']);
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

