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

    public function apply($id){

        if($this->request->isAJAX()){
            $postData = $this->request->getPost();
            $documents = [];
            $files = $this->request->getFiles('files');

            $return['status'] = 'error';

            do {
                foreach($files as $key => $file){
                    $cld = new UploadApi();
                    if ($file) {
                        if ($file->isValid()) {
                            $upload = $cld->upload($file->getRealPath(), ['folder' => 'pt-tekpak-indonesia/' . $key]);
                            if($key == 'ktp' || $key == 'file_vaksin_1' | $key == 'file_vaksin_2' | $key == 'file_vaksin_3'){
                                $documents[$key] = $upload['secure_url'];
                            } else {
                                $postData[$key] = $upload['secure_url'];
                            }
                        }
                    }
                }
    
                if($postData['berpengalaman'] == 'no'){
                    $postData['berpengalaman'] = 0;
                    $postData['lama_pengalaman'] = NULL;
                } else {
                    $postData['berpengalaman'] = 1;
                }
                $postData['status'] = 'applied';
                $postData['id_pelamar'] = $this->session->get('id_pelamar');
                $postData['id_lowongan'] = $id;
                
                $jobApplicationModel = new \App\Models\JobApplicationsModel();
                $jobApplicationModel->insert($postData);

                if(count($documents) > 0){
                    $documents['tanggal_vaksin_1'] = $postData['tanggal_vaksin_1'];
                    $documents['tanggal_vaksin_2'] = $postData['tanggal_vaksin_2'];
                    $documents['tanggal_vaksin_3'] = $postData['tanggal_vaksin_1'];
                    
                    $userModel = new \App\Models\UsersModel();
                    $recruiter = $userModel->select('b.id pelamar_id')->from('users a')->join('pelamar b', 'a.id = b.id_user', 'left')->find(user_id());
                    
                    $recruiterModel = new \App\Models\RecruitersModel();
                    $recruiterModel->update($recruiter->pelamar_id, $documents);
                }

                $return = [
                    'message'  =>sprintf('Lamaran kerja berhasil disubmit!'),
                    'status'   => 'success',
                    'redirect' => route_to('vacancies')
                ];
            } while (0);
            if (isset($return['redirect'])) {
                $this->session->setFlashdata('form_response_status', $return['status']);
                $this->session->setFlashdata('form_response_message', $return['message']);
            }
            echo json_encode($return);

        } else {

            $this->data['title'] = lang('JobVacancies.apply');
            $this->data['heading'] = lang('JobVacancies.personal_data');;
            $data = $this->model->find($id);
            if(!$data){
                throw \Codeigniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $breadcrumb = $this->_setDefaultBreadcrumb();
            $breadcrumb->add($data->posisi, base_url('vacancy/detail/' . $data->id));
            $breadcrumb->add('Apply', current_url());
            $this->data['breadcrumb'] = $breadcrumb->render();
    
            return view('Modules\Frontend\Views\Vacancies\apply', $this->data);
        }
    }

    public function delete($id)
    {
        $this->request->isAJAX() or exit();
        $data = $this->model->find($id);
        if ($data) {
            $this->model->delete($id);
            $return = ['message' => sprintf(lang('Common.delete.success'), lang('QuestionTypes.heading') . ' ' . $data->name), 'status' => 'success'];
        } else {
            $return = ['message' => lang('Common.not_found'), 'status' => 'error'];
        }
        echo json_encode($return);
    }

    public function job_applications(){
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('JobVacancies.job_applications'), site_url());

        $this->data['title'] = lang('JobVacancies.job_applications');
        $this->data['heading'] = lang('JobVacancies.job_applications');

        $jobApplicationModel = new \App\Models\JobApplicationsModel();
        $this->data['job_applications'] =  $jobApplicationModel->where('id_pelamar', $this->session->get('id_pelamar'))->findAll();

        $this->data['breadcrumb'] = $breadcrumb->render();
        return view('Modules\Frontend\Views\Vacancies\applications', $this->data);
    }

    private function _setDefaultBreadcrumb(){
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('JobVacancies.heading'), route_to('vacancies'));

        return $breadcrumb;
    }

}