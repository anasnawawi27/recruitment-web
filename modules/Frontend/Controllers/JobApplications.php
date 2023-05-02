<?php

namespace Modules\Frontend\Controllers;
use \App\Controllers\BaseController;
use \App\Models\JobApplicationsModel;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client;

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
        $this->data['applications'] = $this->model->select('lamaran.*, b.posisi, b.gambar')->join('lowongan b', 'b.id = lamaran.id_lowongan', 'left')->where(['lamaran.id_pelamar' => $this->id_pelamar])->find();
        $this->data['breadcrumb'] = $breadcrumb->render();
        return view('Modules\Frontend\Views\Job_applications\list', $this->data);
    }

    public function detail($id){
        $data = NULL;
        if($id){
            $data = $this->model->select('a.*, b.posisi, b.gambar, c.kategori_soal_ids, c.waktu_pengerjaan, c.point_persoal')->join('lowongan b', 'a.id_lowongan = b.id', 'left')->join('psikotest c', 'b.id = c.id_lowongan', 'left')->from('lamaran a')->where(['a.id' => $id])->first();
            if(!$data){
                throw \Codeigniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }
        $categoryIds = json_decode($data->kategori_soal_ids, true);
        $categoryModel = new \App\Models\QuestionTypesModel();
        $questionModel = new \App\Models\QuestionsModel();
        $categories = $categoryModel->whereIn('id', $categoryIds)->findAll();
        $questions = $questionModel->whereIn('id_kategori', $categoryIds)->findAll();

        $interview = NULL;
        if($data->id_interview){
            $interviewModel = new \App\Models\InterviewModel();
            $interview = $interviewModel->find($data->id_interview);
        }

        $this->data['data'] = $data;
        $this->data['jumlah_soal'] = count($questions);
        $this->data['kategori_soal'] = implode(', ', array_map(function($d){
            return $d->kategori;
        }, $categories));
        $this->data['interview'] = $interview;
        $this->data['title'] = lang('JobApplications.detail_heading');
        $this->data['heading'] = $this->data['title'];
        return view('Modules\Frontend\Views\Job_applications\detail', $this->data);
    }

    public function psikotest_session($id){
        $data = NULL;
        if($id){
            $data = $this->model->select('a.*, b.posisi, c.kategori_soal_ids, c.waktu_pengerjaan, c.point_persoal')->join('lowongan b', 'a.id_lowongan = b.id', 'left')->join('psikotest c', 'b.id = c.id_lowongan', 'left')->from('lamaran a')->where(['a.id' => $id])->first();
            if(!$data){
                throw \Codeigniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

        $categoryIds = json_decode($data->kategori_soal_ids, true);
        $categoryModel = new \App\Models\QuestionTypesModel();
        $questionModel = new \App\Models\QuestionsModel();

        $categories = $categoryModel->whereIn('id', $categoryIds)->findAll();

        $questions = [];
        foreach($categories as $category){
            $questions[$category->kategori] = $questionModel->select('id, id_kategori, gambar, pertanyaan, options')->where('id_kategori', $category->id)->findAll();
        }

        $this->data['title'] = lang('Common.psikotest');
        $this->data['heading'] = $this->data['title'];
        $this->data['data'] = $data;
        $this->data['questions'] = $questions;
        return view('Modules\Frontend\Views\Job_applications\psikotest', $this->data);
    }

    public function set_status_failed($id){
        $this->request->isAjax() or exit();
        $data = $this->model->find($id);
        if($data){
            $this->model->update($id, ['status' => 'failed_psikotest']);
            $return = [
                'status' => 'success',
                'message' => 'Set Status Success'
            ];
        } else {
            $return = [
                'status' => 'error',
                'message' => 'Oops! Something went wrong..'
            ];
        }
        
        echo json_encode($return);
    }

    public function submit_psikotest(){
        $this->request->isAjax() or exit();

        $id = $this->request->getPost('id');
        $postData = $this->request->getPost('data');
        $data = $this->model->select('a.*, b.posisi, c.kategori_soal_ids, c.waktu_pengerjaan, c.point_persoal, c.nilai_minimum')->join('lowongan b', 'a.id_lowongan = b.id', 'left')->join('psikotest c', 'b.id = c.id_lowongan', 'left')->from('lamaran a')->where(['a.id' => $id])->first();

        $return = [
            'status'    => 'error',
            'message'   => 'Oops! Something went wrong..',
            'redirect'  => route_to('job_application_detail', $id)
        ];        

        do{
            if(!$postData){
                $this->model->update($id, ['status' => 'failed_psikotest', 'waktu_psikotest' => date('Y-m-d h:i:s')]);
                $return = [
                    'status'    => 'success',
                    'message'   => 'Submit Berhasil',
                    'redirect'  => route_to('job_application_detail', $id)
                ];         
                
                break;
            }

            $categoryIds = json_decode($data->kategori_soal_ids, true);
            $questionModel = new \App\Models\QuestionsModel();
            $questions = $questionModel->select('id, id_kategori, gambar, pertanyaan, jawaban, options')->whereIn('id_kategori', $categoryIds)->findAll();

            $correct = [];
            foreach($postData as $categoryKey => $value){
                foreach($value as $questionKey => $val){
                    $categoryId = str_replace('category-', '', $categoryKey);
                    $questionId = str_replace('question-', '', $questionKey);

                    $selected = array_filter($questions, function ($d) use ($categoryId, $questionId) {
                        return $d->id_kategori == $categoryId && $d->id == $questionId;
                    });
                    $key = key($selected);
                    $explode = explode('-index-', $val);

                    if($selected[$key]->jawaban == $explode[0]){
                        $correct[] = true;
                    }
                }
            }

            $correct_answer = count($correct);
            if($correct_answer > 0){
                $total_score = $correct_answer *  $data->point_persoal;
                $payload = [
                    'jumlah_soal_benar' => $correct_answer,
                    'nilai_psikotest' => $total_score,
                    'status' => ($total_score >= $data->nilai_minimum ? 'interview' : 'failed_psikotest'),
                    'waktu_psikotest' => date('Y-m-d h:i:s'),
                    'respond_psikotest' => json_encode($postData)
                ];
                $this->model->update($id, $payload);

                if($data->id_interview){
                    $interviewModel = new \App\Models\InterviewModel();

                    $applicants = $this->model->select('b.posisi, c.*')->join('lowongan b', 'b.id = lamaran.id_lowongan', 'left')->join('pelamar c', 'c.id = lamaran.id_pelamar', 'left')->where(['lamaran.id' => $id, 'lamaran.status' => 'interview'])->findAll();
                    $interview = $interviewModel->find($data->id_interview);

                    if($applicants){
                        foreach($applicants as $applicant){
                            
                            $param['applicant'] = $applicant;
                            $param['interview'] = $interview;
            
                            // send email
                            $config = new Configuration();
                            $config->setApiKey('api-key', $_ENV['SENDINBLUE_KEY']);
                            $apiInstance = new TransactionalEmailsApi(new Client(), $config);
                            $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();
            
                            $sendSmtpEmail['subject'] = 'Informasi Interview - ' . $applicant->posisi;
                            $sendSmtpEmail['htmlContent'] = view('body_email', $param);
                            $sendSmtpEmail['sender'] = ['name' => 'PT Tekpak Indonesia', 'email' => 'tekpak.indonesia.test@gmail.com'];
                            $sendSmtpEmail['to'] = [
                                ['email' => $applicant->email, 'name' => $applicant->nama_lengkap]
                            ];
            
                            try {
                                $apiInstance->sendTransacEmail($sendSmtpEmail);
                            } catch (\Exception $e) {
                                $return = [
                                    'message'  => $e->getMessage(),
                                    'status'   => 'error',
                                ];
                                break;
                            }
                        }
                    } else {
                        $return = [
                            'message'  => 'Data Applicant Tidak Ditemukan!',
                            'status'   => 'error',
                        ];
                    }
                }

                $return = [
                    'status'    => 'success',
                    'message'   => 'Submit Berhasil',
                    'redirect'  => route_to('job_application_detail', $id)
                ];
            } else {
                $this->model->update($id, ['status' => 'failed_psikotest', 'waktu_psikotest' => date('Y-m-d h:i:s'), 'respond_psikotest' => json_encode($postData)]);
                $return = [
                    'status'    => 'success',
                    'message'   => 'Submit Berhasil',
                    'redirect'  => route_to('job_application_detail', $id)
                ];   
                break;
            }            
            
        } while(0);
        
        echo json_encode($return);
    }

    private function _setDefaultBreadcrumb(){
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('JobApplications.heading'), route_to('job_applications'));

        return $breadcrumb;
    }
}