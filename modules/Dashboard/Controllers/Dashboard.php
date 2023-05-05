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

        $vacancyModel = new \App\Models\JobVacanciesModel();
        $applicantModel = new \App\Models\ApplicantsModel();
        $jobApplicationModel = new \App\Models\JobApplicationsModel();
        $categoryModel = new \App\Models\QuestionTypesModel();

        $this->data['total_lowongan'] = $vacancyModel->countAllResults();
        $this->data['total_pelamar'] = $applicantModel->countAllResults();
        $this->data['total_lamaran'] = $jobApplicationModel->countAllResults();
        $this->data['total_kategori'] = $categoryModel->countAllResults();

        $jobApplications = [
            'jan' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-01')->find()),
            'feb' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-02')->find()),
            'mar' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-03')->find()),
            'apr' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-04')->find()),
            'mei' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-05')->find()),
            'jun' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-06')->find()),
            'jul' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-07')->find()),
            'aug' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-08')->find()),
            'sep' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-09')->find()),
            'oct' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-10')->find()),
            'nov' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-11')->find()),
            'dec' => count($jobApplicationModel->where("DATE_FORMAT(created_at,'%Y-%m')", date('Y') . '-12')->find()),
        ];
        $this->data['jobApplications'] = $jobApplications;

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
