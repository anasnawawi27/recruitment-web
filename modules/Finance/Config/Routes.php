<?php

$routes->group('finance', ['namespace' => '\Modules\Finance\Controllers'], function ($routes) {
    $routes->group('payroll-component', function ($routes) {
        $routes->add('/', 'PayrollComponents::index', ['as' => 'payroll_components']);
        $routes->add('get_list', 'PayrollComponents::get_list', ['as' => 'payroll_component_list']);
        $routes->add('form/(:any)', 'PayrollComponents::form/$1', ['as' => 'payroll_component_form']);
        $routes->add('save', 'PayrollComponents::save', ['as' => 'payroll_component_save']);
        $routes->add('delete/(:any)', 'PayrollComponents::delete/$1', ['as' => 'payroll_component_delete']);
    });
    $routes->group('payroll-data', function ($routes) {
        $routes->add('/', 'PayrollData::index', ['as' => 'payroll_data']);
        $routes->add('get_list', 'PayrollData::get_list', ['as' => 'payroll_data_list']);
        $routes->add('get-draft', 'PayrollData::get_draft', ['as' => 'payroll_draft']);
        $routes->add('send/(:any)', 'PayrollData::send/$1', ['as' => 'payroll_data_send']);
        $routes->add('pdf/(:any)', 'PayrollData::pdf/$1', ['as' => 'payroll_data_pdf']);
        $routes->add('generate', 'PayrollData::generate', ['as' => 'generate_payroll_data']);
        $routes->add('pdf-report', 'PayrollData::pdf_report', ['as' => 'pdf_report_payroll_data']);
        $routes->add('send-all', 'PayrollData::send_all', ['as' => 'send_all_payroll_data']);
    });
});
