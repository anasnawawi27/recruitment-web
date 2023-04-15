<?php

$routes->group('employees', ['namespace' => '\Modules\Employees\Controllers'], function ($routes) {
    $routes->group('live-attendance', function ($routes) {
        $routes->add('/', 'Attendances::index', ['as' => 'live_attendance']);
        $routes->add('save', 'Attendances::save', ['as' => 'live_attendance_save']);
        $routes->add('validate', 'Attendances::validateAttendance', ['as' => 'live_attendance_validate']);
    });
    $routes->group('attendance-record', function ($routes) {
        $routes->add('/', 'AttendanceRecords::index', ['as' => 'attendance_record']);
    });
    $routes->group('request-leave', function ($routes) {
        $routes->add('/', 'RequestLeaves::index', ['as' => 'request_leaves']);
        $routes->add('get_list', 'RequestLeaves::get_list', ['as' => 'request_leave_list']);
        $routes->add('form/(:any)', 'RequestLeaves::form/$1', ['as' => 'request_leave_form']);
        $routes->add('save', 'RequestLeaves::save', ['as' => 'request_leave_save']);
        $routes->add('delete/(:any)', 'RequestLeaves::delete/$1', ['as' => 'request_leave_delete']);
    });
});
