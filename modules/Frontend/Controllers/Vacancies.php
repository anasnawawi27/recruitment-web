<?php

namespace Modules\Frontend\Controllers;
use \App\Controllers\BaseController;
use \App\Models\JobVacanciesModel;
use Cloudinary\Api\Upload\UploadApi;

class Vacancies extends BaseController
{
    protected $model;

    public function __construct(){
        parent::__construct();
        $this->data['menu'] = 'vacancies';
        $this->data['module_url'] = route_to('vacancies');
        $this->model = new JobVacanciesModel();
    }

    public function index(){
        $breadcrumb = $this->_setDefaultBreadcrumb();

        $this->data['title'] = lang('JobVacancies.heading');
        $this->data['heading'] = lang('JobVacancies.heading');
        $this->data['vacancies'] = $this->model->findAll();
        $this->data['breadcrumb'] = $breadcrumb->render();
        return view('Modules\Frontend\Views\Vacancies\list', $this->data);
    }

    public function detail($id){
    
        $data = NULL;
        $breadcrumb = $this->_setDefaultBreadcrumb();
        if($id){
            $data = $this->model->find($id);
            if(!$data){
                throw \Codeigniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $breadcrumb->add($data->posisi, current_url());
        }

        $this->data['data'] = $data;

        $this->data['title'] = lang('JobVacancies.detail_heading');
        $this->data['heading'] = $this->data['title'];
        $this->data['breadcrumb'] = $breadcrumb->render();
        return view('Modules\Frontend\Views\Vacancies\detail', $this->data);
    }

    public function not_qualified(){
        $this->data['title'] = 'Not Qualified';
        return view('Modules\Frontend\Views\Vacancies\not_qualified', $this->data);
    }

    public function apply($id){

        if($this->request->isAJAX()){
            $postData = $this->request->getPost();
            $return['status'] = 'error';

            do {

                $data = $this->model->find($id);
                $qualifikasi = json_decode($data->qualifikasi, true);

                $respond = [
                    'last_education' => $postData['last_education'],
                    'jurusan'        => $postData['jurusan'],
                    'nilai_terakhir' => $postData['nilai_terakhir'],
                    'berpengalaman'  => ($postData['berpengalaman'] == 'yes' ? true : false)
                ];

                if($postData['jurusan'] == 'other'){
                    $respond['jurusan'] = $postData['jurusan_lain'];
                }

                if(isset($postData['lama_pengalaman'])){
                    $respond['lama_pengalaman'] = $postData['lama_pengalaman'];
                }

                $not_passed = [];

                if (array_key_exists('syarat_gender', $qualifikasi)) {
                    $user = $this->data['user'];
                    if($user->jenis_kelamin !== $qualifikasi['syarat_gender']){
                        $not_passed[] = true;
                    }
                }

                if($postData['last_education'] < $qualifikasi['last_education']){
                    $not_passed[] = true;
                }

                if (array_key_exists('syarat_jurusan', $qualifikasi)) {
                    if($postData['jurusan'] == 'other'){
                        $not_passed[] = true;
                    }
                }

                if (array_key_exists('nilai_minimum', $qualifikasi)) {
                    if($postData['nilai_terakhir'] < $qualifikasi['nilai_minimum']){
                        $not_passed[] = true;
                    }
                }

                if (array_key_exists('berpengalaman', $qualifikasi)) {
                    
                    if($postData['berpengalaman'] !== 'yes'){
                        $not_passed[] = true;
                    }

                    if($postData['lama_pengalaman'] < $qualifikasi['minimum_pengalaman']){
                        $not_passed[] = true;
                    }
                }

                $passed = count($not_passed) > 0 ? false : true;
                if(!$passed){
                    $return = [
                        'status'   => 'not_passed',
                        'redirect' => route_to('not_qualified')
                    ];

                    break;
                }

                $payload = [
                    'id_pelamar'         => $this->session->get('id_pelamar'),
                    'id_lowongan'        => $id,
                    'respond_input'      => json_encode($respond),
                    'lolos_administrasi' => 1,
                    'id_interview'       => $data->id_interview,
                    'berpengalaman'      => ($postData['berpengalaman'] == 'yes' ? 1 : 0),
                    'lama_pengalaman'    => isset($postData['lama_pengalaman']) ? $postData['lama_pengalaman'] : NULL,
                    'status'             => 'passed',
                ];
                
                $jobApplicationModel = new \App\Models\JobApplicationsModel();
                $jobApplicationModel->insert($payload);

                $return = [
                    'status'   => 'success',
                    'redirect' => base_url('vacancy/complete-data/' . $id)
                ];
            } while (0);
            echo json_encode($return);

        } else {

            $this->data['title'] = lang('JobVacancies.apply');
            $data = $this->model->find($id);
            if(!$data){
                throw \Codeigniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $this->data['data'] = $data;

            $jobApplicationModel = new \App\Models\JobApplicationsModel();
            $applied = $jobApplicationModel->where(['id_lowongan' => $id, 'id_pelamar' => $this->session->get('id_pelamar')])->first();

            if($applied){
                return redirect()->to('job-application/detail/' . $applied->id);
            } else {
                return view('Modules\Frontend\Views\Vacancies\apply', $this->data);
            }

        }
    }

    public function complete_data($id){

        if($this->request->isAJAX()){
            $postData = $this->request->getPost();
            $documents = [];
            $files = $this->request->getFiles('files');

            $return['status'] = 'error';

            do {

                $payload = [];
                foreach($files as $key => $file){
                    $cld = new UploadApi();
                    if ($file) {
                        if ($file->isValid()) {
                            $upload = $cld->upload($file->getRealPath(), ['folder' => 'pt-tekpak-indonesia/' . $key]);
                            if($key == 'ktp' || $key == 'file_vaksin_1' | $key == 'file_vaksin_2' | $key == 'file_vaksin_3'){
                                $documents[$key] = $upload['public_id'];
                            } else {
                                $payload[$key] = $upload['public_id'];
                            }
                        }
                    }
                }
    
                $id_pelamar = $this->session->get('id_pelamar');
                $payload['status'] = 'applied';
                
                $jobApplicationModel = new \App\Models\JobApplicationsModel();
                $jobApplicationModel->where(['id_lowongan' => $id, 'id_pelamar' => $id_pelamar])->set($payload)->update();

                if(count($documents) > 0){
                    $documents['tanggal_vaksin_1'] = $postData['tanggal_vaksin_1'];
                    $documents['tanggal_vaksin_2'] = $postData['tanggal_vaksin_2'];
                    $documents['tanggal_vaksin_3'] = $postData['tanggal_vaksin_1'];
                    
                    $applicantModel = new \App\Models\ApplicantsModel();
                    $applicantModel->update($id_pelamar, $documents);
                }

                $jobApplicationModel = new \App\Models\JobApplicationsModel();
                $application =  $jobApplicationModel->where(['id_lowongan' => $id, 'id_pelamar' => $this->session->get('id_pelamar')])->first();

                $return = [
                    'message'  => sprintf('Lamaran kerja berhasil disubmit!'),
                    'status'   => 'success',
                    'redirect' => route_to('job_application_detail', $application->id)
                ];
            } while (0);
            if (isset($return['redirect'])) {
                $this->session->setFlashdata('form_response_status', $return['status']);
                $this->session->setFlashdata('form_response_message', $return['message']);
            }
            echo json_encode($return);

        } else {

            $this->data['title'] = lang('JobVacancies.apply');

            $jobApplicationModel = new \App\Models\JobApplicationsModel();

            $data =  $jobApplicationModel->where(['id_lowongan' => $id, 'id_pelamar' => $this->session->get('id_pelamar'), 'status' => 'passed'])->find();
            if(!$data){
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $this->data['data'] = $data;
            return view('Modules\Frontend\Views\Vacancies\complete_data', $this->data);
        }
    }

    private function _setDefaultBreadcrumb(){
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('JobVacancies.heading'), route_to('vacancies'));

        return $breadcrumb;
    }

}