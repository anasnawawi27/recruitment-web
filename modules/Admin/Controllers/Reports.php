<?php

namespace Modules\Admin\Controllers;
use \App\Controllers\BaseController;

use Dompdf\Dompdf;
use Dompdf\Options;

class Reports extends BaseController
{
    protected $model;
    private   $permView;

    public function __construct() {
        parent::__construct();
        $this->data['menu'] = 'report_list';
        $this->data['module'] = 'report';
        $this->data['module_url'] = route_to('reports');
        $this->permView = has_permission('report');
    }

    public function index() {
        $this->permView or exit();
        $postData = $this->request->getPost();
        if (isset($postData['date'])) {
            var_dump($postData); die;
            $this->data['date'] = $postData['date'];
        }

        $vacancyModel = new \App\Models\JobVacanciesModel();
        $this->data['vacancies'] = $vacancyModel->findAll();
        $this->data['title'] = lang('Common.report');
        $this->data['heading'] = lang('Common.report');
        return view('\Modules\Admin\Views\report\index', $this->data);
    }

    public function get_report_data(){
        $this->request->isAJAX() or exit();
        $postData = $this->request->getPost();
        $data = NULL;
        $jobApplicationModel = new \App\Models\JobApplicationsModel();
        $builder = $jobApplicationModel->select('lamaran.created_at as tanggal_apply, b.posisi, lamaran.respond_input, c.nama_lengkap, c.nik, lamaran.cv, lamaran.pas_photo, lamaran.nilai_psikotest, lamaran.nilai_interview, d.nilai_minimum as nilai_minimum_psikotest')->join('lowongan b', 'b.id = lamaran.id_lowongan', 'left')->join('pelamar c', 'c.id = lamaran.id_pelamar', 'left')->join('psikotest d', 'd.id_lowongan = b.id', 'left');
        if($postData['date'] && $postData['lowongan_id']){
            $date = explode(' - ', $postData['date']);
            $date1 = str_replace('/', '-', $date[0]);
            $date2 = str_replace('/', '-', $date[1]);
            $builder->where('lamaran.status','accepted')->where('lamaran.created_at BETWEEN "'. date('Y-m-d', strtotime($date1)). '" and "'. date('Y-m-d', strtotime($date2)).'"')->where('lamaran.id_lowongan', $postData['lowongan_id']);
        } else if($postData['date']) {
            $date = explode(' - ', $postData['date']);
            $date1 = str_replace('/', '-', $date[0]);
            $date2 = str_replace('/', '-', $date[1]);
            $builder->where('lamaran.status','accepted')->where('lamaran.created_at BETWEEN "'. date('Y-m-d', strtotime($date1)). '" and "'. date('Y-m-d', strtotime($date2)).'"');
        } else if($postData['lowongan_id']){
            $builder->where(['lamaran.status' => 'accepted', 'lamaran.id_lowongan' => $postData['lowongan_id']]);
        } else {
            $builder->where('lamaran.status', 'accepted');
        }

        $data = $builder->findAll();

        echo json_encode(['data' => $data]);
    }

    public function pdf(){
        $this->request->getPost() or exit();
        $postData = $this->request->getPost();
        $data = NULL;
        $jobApplicationModel = new \App\Models\JobApplicationsModel();
        $builder = $jobApplicationModel->select('c.jenis_kelamin, c.tempat_lahir, c.tanggal_lahir, c.email, c.no_handphone_1, c.no_handphone_2, b.posisi, lamaran.respond_input, c.nama_lengkap, c.nik, lamaran.cv, lamaran.pas_photo, lamaran.nilai_psikotest, lamaran.nilai_interview, d.nilai_minimum as nilai_minimum_psikotest')->join('lowongan b', 'b.id = lamaran.id_lowongan', 'left')->join('pelamar c', 'c.id = lamaran.id_pelamar', 'left')->join('psikotest d', 'd.id_lowongan = b.id', 'left');
        if($postData['date'] && $postData['lowongan_id']){
            $date = explode(' - ', $postData['date']);
            $date1 = str_replace('/', '-', $date[0]);
            $date2 = str_replace('/', '-', $date[1]);
            $builder->where('lamaran.status','accepted')->where('lamaran.created_at BETWEEN "'. date('Y-m-d', strtotime($date1)). '" and "'. date('Y-m-d', strtotime($date2)).'"')->where('lamaran.id_lowongan', $postData['lowongan_id']);
        } else if($postData['date']) {
            $date = explode(' - ', $postData['date']);
            $date1 = str_replace('/', '-', $date[0]);
            $date2 = str_replace('/', '-', $date[1]);
            $builder->where('lamaran.status','accepted')->where('lamaran.created_at BETWEEN "'. date('Y-m-d', strtotime($date1)). '" and "'. date('Y-m-d', strtotime($date2)).'"');
        } else if($postData['lowongan_id']){
            $builder->where(['lamaran.status' => 'accepted', 'lamaran.id_lowongan' => $postData['lowongan_id']]);
        } else {
            $builder->where('lamaran.status', 'accepted');
        }

        $data['rows'] = $builder->findAll();
        
        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $pdf = new Dompdf($options);
        $html = view('\Modules\Admin\Views\report\pdf', $data);
        $pdf->loadHtml($html);
        $pdf->setPaper('A4');
        $pdf->render();
        $pdf->stream('Report-Accepted-Applicants.pdf');
    }



 }