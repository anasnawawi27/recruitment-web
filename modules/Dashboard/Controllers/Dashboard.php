<?php

namespace Modules\Dashboard\Controllers;

use \App\Controllers\BaseController;

class Dashboard extends BaseController
{
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->data['title'] = 'Dashboard';
        $this->data['module'] = 'dashboard';
        $this->data['menu'] = 'dashboard';
    }

    public function index()
    {
        $breadcrumb = $this->_setDefaultBreadcrumb();
        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['title'] = lang('Common.dashboard');
        $this->data['heading'] = lang('Common.dashboard');

        $this->data['pluginJS'] = [
            base_url('vendors/js/charts/chart.min.js'),
        ];

        $this->data['customJS'] = [
            base_url('js/modules/dashboard.js')
        ];
        return view('Modules\Dashboard\Views\dashboard', $this->data);
    }

    private function _setDefaultBreadcrumb()
    {
        $breadcrumb = new \App\Libraries\Breadcrumb();
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Common.dashboard'), route_to('dashboard'));

        return $breadcrumb;
    }
}
