<?php

namespace Modules\Frontend\Controllers;
use \App\Controllers\BaseController;
use \App\Models\JobApplicationsModel;

class JobApplications extends BaseController
{
    protected $model;
    public $id_pelamar;

    public function __construct(){
        parent::__construct();
        $this->data['menu'] = 'applications';
        $this->data['module_url'] = route_to('job_applications');
        $this->model = new JobApplicationsModel();
        $this->id_pelamar = $this->session->get('id_pelamar');
    }

    public function index(){
        $breadcrumb = $this->_setDefaultBreadcrumb();

        $this->data['title'] = lang('JobApplications.heading');
        $this->data['heading'] = lang('JobApplications.heading');
        $this->data['applications'] = $this->model->select('a.*, b.posisi')->join('lowongan b', 'a.id_lowongan = b.id', 'left')->from('lamaran a')->where(['a.id_pelamar' => $this->id_pelamar])->find();
        $this->data['breadcrumb'] = $breadcrumb->render();
        return view('Modules\Frontend\Views\Job_applications\list', $this->data);
    }

    public function detail($id){
        $data = NULL;
        if($id){
            $data = $this->model->select('a.*, b.posisi, c.kategori_soal_ids, c.waktu_pengerjaan, c.point_persoal')->join('lowongan b', 'a.id_lowongan = b.id', 'left')->join('psikotest c', 'b.id = c.id_lowongan', 'left')->from('lamaran a')->where(['a.id_pelamar' => $this->id_pelamar])->first();
            if(!$data){
                throw \Codeigniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }
        $this->data['data'] = $data;
        $this->data['title'] = lang('JobApplications.detail_heading');
        $this->data['heading'] = $this->data['title'];
        return view('Modules\Frontend\Views\Job_applications\detail', $this->data);
    }

    public function psikotest($id){
        $data = NULL;
        if($id){
            $data = $this->model->select('a.*, b.posisi, c.kategori_soal_ids, c.waktu_pengerjaan, c.point_persoal')->join('lowongan b', 'a.id_lowongan = b.id', 'left')->join('psikotest c', 'b.id = c.id_lowongan', 'left')->from('lamaran a')->where(['a.id_pelamar' => $this->id_pelamar])->first();
            if(!$data){
                throw \Codeigniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }
    }

    private function _setDefaultBreadcrumb(){
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('JobApplications.heading'), route_to('job_applications'));

        return $breadcrumb;
    }
}