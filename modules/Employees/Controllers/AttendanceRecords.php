<?php

namespace Modules\Employees\Controllers;

use \App\Controllers\BaseController;
use \App\Models\AttendancesModel;
use CodeIgniter\I18n\Time;

class AttendanceRecords extends BaseController
{
    protected $data;
    protected $model;
    private $perm;
    private $permView;
    private $permAdd;
    private $permDelete;

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'attendance_record';
        $this->data['module'] = 'employee';
        $this->data['menu'] = 'attendance_record';
        $this->data['module_url'] = route_to('attendance_record');
        $this->model = new AttendancesModel();
        $this->permView = has_permission($this->perm);
    }

    public function index()
    {
        $this->permView or exit();

        $breadcrumb = $this->_setDefaultBreadcrumb();
        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['title'] = lang('Common.attendance_record');
        $this->data['heading'] = lang('Common.attendance_record');
        $this->data['pluginCSS'] = [
            base_url('vendors/evo-calendar/css/evo-calendar.min.css'),
            base_url('vendors/evo-calendar/css/evo-calendar.royal-navy.min.css'),
            "https://fonts.googleapis.com/css?family=Source+Sans+Pro&display=swap",
            "https://fonts.googleapis.com/css2?family=Pacifico&display=swap",
            "https://fonts.googleapis.com/css2?family=Fira+Mono&display=swap",
        ];
        $this->data['pluginJS'] = [
            base_url('vendors/evo-calendar/js/evo-calendar.min.js'),
        ];
        $this->data['customJS'] = [
            base_url('js/modules/attendance-record.js'),
        ];
        $records = $this->model->where('user_id', user_id())->find();
        $this->data['records'] = json_encode($records);
        return view('\Modules\Employees\Views\attendance_record', $this->data);
    }

    private function _setDefaultBreadcrumb()
    {
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Common.attendance_record'), route_to('attendance_record'));

        return $breadcrumb;
    }
};
