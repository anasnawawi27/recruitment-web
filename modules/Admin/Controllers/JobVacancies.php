<?php

namespace Modules\Admin\Controllers;
use \App\Controllers\BaseController;
use \App\Models\JobVacanciesModel;
use Cloudinary\Api\Upload\UploadApi;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client;

class JobVacancies extends BaseController
{
    protected $model;
    private $perm;
    private $permView;
    private $permAdd;
    private $permEdit;
    private $permDelete;

    public function __construct(){
        parent::__construct();
        $this->perm = 'job_vacancy';
        $this->data['menu'] = 'job_vacancy';
        $this->data['module'] = 'administration';
        $this->data['module_url'] = route_to('job_vacancies');
        $this->data['filter_name'] = 'table_filter_job_vacancy';
        $this->model = new JobVacanciesModel();
        $this->permView = has_permission($this->perm);
        $this->permAdd = has_permission($this->perm. '/add');
        $this->permEdit = has_permission($this->perm. '/edit');
        $this->permDelete = has_permission($this->perm. '/delete');
    }

    public function index(){
        $this->permView or exit();
        $this->data['table'] = [
            'columns' => [
                [
                    'title'         => lang('JobVacancies.position'),
                    'field'         => 'posisi',
                    'sortable'      => 'true',
                    'switchable'    => 'true',
                    'formatter' => 'detailFormatterDefault',
                ],
                [
                    'title'         => lang('JobVacancies.required_date'),
                    'field'         => 'batas_tanggal',
                    'sortable'      => 'true',
                    'switchable'    => 'true',
                    'formatter'     => 'longDateFormatterDefault'
                ],
            ],
            'url'       => route_to('job_vacancy_list'),
            'cookie_id' => 'table-job-vacancy'  
        ];

        if($this->permEdit || $this->permDelete){
            $this->data['table']['columns'][] = [
                'field'      => 'action',
                'class'      => 'w-100px nowrap',
                'align'      => 'center',
                'switchable' => 'false',
                'formatter'  => 'actionFormatterDefault',
                'events'     => 'actionEventDefault'
            ];
        }

        $breadcrumb = $this->_setDefaultBreadcrumb();
        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['title'] = lang('JobVacancies.heading');
        $this->data['heading'] = lang('JobVacancies.heading');
        if($this->permAdd){
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('job_vacancy_form',0), lang('JobVacancies.add_heading'));
        }
        $this->data['toolbar'] = view('table-toolbar/default', $this->data);
        return view('list', $this->data);
    }

    public function form($id){
        $data = NULL;
        $psikotest = NULL;
        $interview = NULL;
        $breadcrumb = $this->_setDefaultBreadcrumb();
        if($id){
            $this->permEdit or exit();
            $data = $this->model->find($id);
            if(!$data){
                throw \Codeigniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $breadcrumb->add(lang('Common.edit'), current_url());

            $psikotestModel = new \App\Models\PsikotestModel();
            $interviewModel = new \App\Models\InterviewModel();

            $psikotest = $psikotestModel->where(['id_lowongan' => $data->id])->first();
            if($data->id_interview){
                $interview = $interviewModel->find($data->id_interview);
            }
        } else {
            $this->permAdd or exit();
            $breadcrumb->add(lang('Common.add'), current_url());
        }

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;
        $this->data['psikotest'] = $psikotest;
        $this->data['interview'] = $interview;

        if($psikotest){
            $db = db_connect();
            $builder = $db->table('soal');
            $builder->select('COUNT(id) as jumlah_soal');
            $builder->whereIn('id_kategori', json_decode($psikotest->kategori_soal_ids, true));
            $count = $builder->get()->getFirstRow();

            $this->data['jumlah_soal'] = $count->jumlah_soal;
            $this->data['total_nilai'] = $count->jumlah_soal * $psikotest->point_persoal;
        }

        $this->data['title'] = ($data ? lang('Common.edit') : lang('Common.add')) . ' ' . lang('JobVacancies.heading');
        $this->data['heading'] = $this->data['title'];

        $db = db_connect();
        $builder = $db->table('soal a');
        $builder->select('a.id_kategori, b.kategori');
        $builder->join('kategori_soal b', 'a.id_kategori = b.id', 'left');
        $builder->groupBy('a.id_kategori');
        $this->data['kategori_soal'] = $builder->get()->getResult();

        return view('form_vacancy', $this->data);
    }

    public function get_list(){
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['posisi'] = $getData['search'];
        $table = new \App\Models\TableModel('lowongan');
        if(isset($getData['sort'])){
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }
        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.id, a.posisi, a.batas_tanggal, '" . route_to('job_vacancy_detail', 'ID') . "' AS detail, '".($this->permEdit ? route_to('job_vacancy_form', 'ID') : '')."' AS `edit`, '".($this->permDelete ? route_to('job_vacancy_delete', 'ID') : '')."' AS `delete`");
        $output['rows'] = $table->getAll();
        $output['total'] = $table->countAll();
        $table->setFilter();
        $output['totalNotFiltered'] = $table->countAll();
        echo json_encode($output);
    }

    public function save(){
        $this->request->isAJAX() or exit();

        $rules = [
            'posisi'         => [
                'label' => lang('JobVacancies.position'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Posisi wajib diisi'
                ]
            ],
            'deskripsi' => [
                'label' => lang('JobVacancies.description'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Deskripsi wajib diisi'
                ]
            ],
            'batas_tanggal' => [
                'label' => lang('JobVacancies.date_required'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Batas Tanggal wajib diisi'
                ]
            ]
        ];

        $return['status'] = 'error';
        
        do {
            if (!$this->validate($rules)) {
                $return['message'] = $this->validator->listErrors('bootstrap_list');
                break;
            }
            $postData = $this->request->getPost();

            $lowongan = [
                'posisi'        => $postData['posisi'],
                'deskripsi'     => nl2br($postData['deskripsi']),
                'batas_tanggal' => $postData['batas_tanggal'],
            ];
            
            $image = $this->request->getFile('upload_image');
            $cld = new UploadApi();
            if ($image) {
                if ($image->isValid()) {
                    $upload = $cld->upload($image->getRealPath(), ['folder' => 'pt-tekpak-indonesia/vacancy']);
                    $lowongan['gambar'] = $upload['public_id'];
                }
            } else {
                if (isset($postData['id'])) {
                    if ($image = $this->model->find($postData['id'])) {
                        if ($image->gambar) {
                            $lowongan['gambar'] = $image->gambar;
                            if (isset($postData['delete_image']) && $postData['delete_image'] === '1') {
                                $cld->destroy($image->gambar);
                                $lowongan['gambar'] = NULL;
                            }
                        }
                    }
                }
            }

            if(isset($postData['set_interview']) && $postData['set_interview'] == 1){
                $interview = [
                    'agenda' => $postData['agenda'],
                    'tanggal' => $postData['tanggal'],
                    'waktu'   => $postData['waktu'],
                    'pewawancara' => $postData['pewawancara']
                 ];
    
                if(isset($postData['is_online'])){
                    $interview['via'] = 'online';
                    $interview['link'] = $postData['link'];
                    $interview['tempat'] = NULL;
                } else {
                    $interview['via'] = 'offline';
                    $interview['tempat'] = $postData['tempat'];
                    $interview['link'] = NULL;
                }
            }

            $psikotest = [
                'kategori_soal_ids' => json_encode($postData['kategori_soal']),
                'waktu_pengerjaan'  =>  $postData['waktu_pengerjaan'],
                'point_persoal'     => $postData['nilai_persoal'],
                'nilai_minimum'     => $postData['nilai_minimum']
            ];

            $interviewModel = new \App\Models\InterviewModel();
            $psikotestModel = new \App\Models\PsikotestModel();

            $qualifikasi = [];

            if(isset($postData['syarat_umur'])){
                $qualifikasi['syarat_umur'] = $postData['age'];
            }

            if(isset($postData['syarat_gender'])){
                $qualifikasi['syarat_gender'] = $postData['gender'];
            }

            if($postData['last_education']){
                $qualifikasi['last_education'] = $postData['last_education'];
            }

            if($postData['syarat_jurusan'] == 'jurusan_spesifik'){
                $jurusan = explode(',', $postData['jurusan']);
                $qualifikasi['syarat_jurusan'] = json_encode($jurusan);
            }

            if($postData['minimum_nilai'] == 'ya'){
                $qualifikasi['minimum_nilai'] = $postData['syarat_nilai'];
            }

            if($postData['kriteria'] == 'Berpengalaman'){
                $qualifikasi['berpengalaman'] = true;
                $qualifikasi['minimum_pengalaman'] = $postData['minimum_pengalaman'];
            }

            $lowongan['qualifikasi'] = json_encode($qualifikasi);


            if (!$postData['id']) {
                if(isset($postData['set_interview'])){
                    $interviewModel->insert($interview);
                    $lowongan['id_interview'] =  $interviewModel->getInsertID();
                } else {
                    $lowongan['id_interview'] = NULL;
                }

                $lowongan['created_by'] = user_id();
                $this->model->insert($lowongan);
                $id_lowongan =  $this->model->getInsertID();

                $psikotest['id_lowongan'] = $id_lowongan;
                $psikotestModel->insert($psikotest);

            } else {
                
                if(isset($postData['set_interview'])){
                    if($postData['id_interview']){
                        $interviewModel->update($postData['id_interview'], $interview);
                        $lowongan['id_interview'] = $postData['id_interview'];
                    } else {
                        $interviewModel->insert($interview);
                        $lowongan['id_interview'] =  $interviewModel->getInsertID();
                    }
                } else {
                    if($postData['id_interview']){
                        $interviewModel->delete($postData['id_interview']);
                    }
                    $lowongan['id_interview'] = NULL;
                }

                $lowongan['updated_by'] = user_id();
                $this->model->update($postData['id'], $lowongan);
                $psikotestModel->update($postData['id_psikotest'], $psikotest);
            }

            $return = [
                'message'  =>sprintf(lang('Common.saved.success'), lang('JobVacancies.heading') . ' ' . $postData['posisi']),
                'status'   => 'success',
                'redirect' => route_to('job_vacancies')
            ];
        } while (0);
        if (isset($return['redirect'])) {
            $this->session->setFlashdata('form_response_status', $return['status']);
            $this->session->setFlashdata('form_response_message', $return['message']);
        }
        echo json_encode($return);
    }

    public function detail($id){
        if ($id) {
            $data = $this->model->find($id);

            if (!$data) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }

            $breadcrumb = $this->_setDefaultBreadcrumb();
            $breadcrumb->add(lang('Common.detail'), current_url());

            $applicationsModel = new \App\Models\JobApplicationsModel();

            $this->data['accepted'] = $applicationsModel->select('lamaran.*, b.nama_lengkap, b.jenis_kelamin')->join('pelamar b', 'b.id = lamaran.id_pelamar', 'left')->where(['lamaran.id_lowongan' => $id, 'lamaran.nilai_interview !=' => NULL, 'lamaran.nilai_psikotest !=' => NULL,])->whereIn('lamaran.status', ['accepted'])->findAll();
            $this->data['ranks'] = $applicationsModel->select('lamaran.*, b.nama_lengkap, b.jenis_kelamin')->join('pelamar b', 'b.id = lamaran.id_pelamar', 'left')->where(['lamaran.id_lowongan' => $id, 'lamaran.nilai_interview !=' => NULL, 'lamaran.nilai_psikotest !=' => NULL,])->whereIn('lamaran.status', ['interview', 'failed'])->findAll();
            $this->data['ranks_ids'] = $applicationsModel->select('lamaran.id')->join('pelamar b', 'b.id = lamaran.id_pelamar', 'left')->where(['lamaran.id_lowongan' => $id, 'lamaran.nilai_interview !=' => NULL, 'lamaran.nilai_psikotest !=' => NULL,])->whereIn('lamaran.status', ['interview', 'failed'])->findAll();
            $this->data['interviews'] = $applicationsModel->select('lamaran.*, b.nama_lengkap, b.jenis_kelamin')->join('pelamar b', 'b.id = lamaran.id_pelamar', 'left')->where(['lamaran.id_lowongan' => $id])->whereIn('lamaran.status', ['interview', 'accepted'])->findAll();
            $this->data['applicants'] = $applicationsModel->select('lamaran.*, b.nama_lengkap, b.jenis_kelamin')->join('pelamar b', 'b.id = lamaran.id_pelamar', 'left')->where(['lamaran.id_lowongan' => $id])->findAll();

            $interviewModel = new \App\Models\InterviewModel();
            $interview = NULL;

            if($data->id_interview){
                $interview = $interviewModel->find($data->id_interview);
            }

            $this->data['detail'] = $data;
            $this->data['interview_data'] = $interview;
            $this->data['title'] = lang('JobVacancies.detail_heading');
            $this->data['heading'] = lang('JobVacancies.detail_heading');
            $this->data['breadcrumb'] = $breadcrumb->render();
            return view('Modules\Admin\Views\Job_Vacancies\detail', $this->data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function set_accepted(){
        $this->request->isAJAX() or exit();
        
        $return = [
            'message'  => 'Oops! Something Went Wrong!',
            'status'   => 'error',
        ];

        $id_vacancy = $this->request->getPost('id');
        $ids = $this->request->getPost('accepted');
        $ids_failed = $this->request->getPost('failed');
        if($ids){
            $jobApplicationsModel = new \App\Models\JobApplicationsModel();
            
            foreach($ids as $id){
                $jobApplicationsModel->update($id, ['status' => 'accepted']);
            }

            if($ids_failed){
                foreach($ids_failed as $failed_id){
                    $jobApplicationsModel->update($failed_id, ['status' => 'failed']);
                }  
            }
            $return = [
                'message'  => 'Set Accepted Berhasil',
                'status'   => 'success',
                'redirect' => route_to('job_vacancy_detail', $id_vacancy)
            ];
            $this->session->setFlashdata('open_accepted_tab', TRUE);
        }
        if (isset($return['redirect'])) {
            $this->session->setFlashdata('form_response_status', $return['status']);
            $this->session->setFlashdata('form_response_message', $return['message']);
        }

        echo json_encode($return);

    }

    public function modal_detail_psikotest($id)
    {
        $this->request->isAJAX() or exit;

        $applicationsModel = new \App\Models\JobApplicationsModel();
        $questionsModel = new \App\Models\QuestionsModel();
        
        $data = $applicationsModel->select('lamaran.*, b.nama_lengkap, b.jenis_kelamin, c.posisi, d.kategori_soal_ids')->join('pelamar b', 'b.id = lamaran.id_pelamar', 'left')->join('lowongan c', 'c.id = lamaran.id_lowongan', 'left')->join('psikotest d', 'd.id_lowongan = c.id', 'left')->where('lamaran.id', $id)->first();
        $ids = json_decode($data->kategori_soal_ids);

        $this->data['data'] = $data;

        $categories = [];
        foreach($ids as $id_kategori){
            $categoryModel = new \App\Models\QuestionTypesModel();
            $category = $categoryModel->find($id_kategori);
            $categories[$category->kategori] =  $questionsModel->where('id_kategori', $id_kategori)->findAll();
        }
        $this->data['categories'] = $categories;
        return view('Modules\Admin\Views\Job_Vacancies\modal_detail_psikotest', $this->data);
    }

    public function save_schedule(){
        $this->request->isAJAX() or exit();

        $rules = [
            'agenda'         => [
                'label' => lang('Common.agenda'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Agenda wajib diisi'
                ]
            ],
            'tanggal' => [
                'label' => lang('Common.date'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal wajib diisi'
                ]
            ],
            'waktu' => [
                'label' => lang('Common.time'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Waktu wajib diisi'
                ]
            ],
            'pewawancara' => [
                'label' => lang('Common.interviewer'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pewawancara wajib diisi'
                ]
            ]
        ];

        $return['status'] = 'error';

        do {
            $postData = $this->request->getPost();

            if (isset($postData['is_online'])) {
                $rules['link'] = [
                    'label' => lang('Common.link'),
                    'rules' => 'required',
                    'errors' => [
                        'Link Wajib Diisi'
                    ]
                ];
            } else {
                $rules['tempat'] = [
                    'label' => lang('Common.place'),
                    'rules' => 'required',
                    'errors' => [
                        'Tempat Wajib Diisi'
                    ]
                ];
            }

            if (!$this->validate($rules)) {
                $return['message'] = $this->validator->listErrors('default');
                break;
            }
             $payload = [
                'agenda' => $postData['agenda'],
                'tanggal' => $postData['tanggal'],
                'waktu'   => $postData['waktu'],
                'pewawancara' => $postData['pewawancara']
             ];

            if(isset($postData['is_online'])){
                $payload['via'] = 'online';
                $payload['link'] = $postData['link'];
                $payload['tempat'] = NULL;
            } else {
                $payload['via'] = 'offline';
                $payload['tempat'] = $postData['tempat'];
                $payload['link'] = NULL;
            }

            $interviewModel = new \App\Models\InterviewModel();

            if (!$postData['id_interview']) {
                $interviewModel->insert($payload);
            } else {
                $interviewModel->update($postData['id_interview'], $payload);
            }

            $return = [
                'message'  =>sprintf(lang('Common.saved.success'), lang('Common.schedule')),
                'status'   => 'success',
                'redirect' => route_to('job_vacancy_detail', $postData['id_lowongan'])
            ];
            $this->session->setFlashdata('open_interview_tab', TRUE);
        } while (0);
        if (isset($return['redirect'])) {
            $this->session->setFlashdata('form_response_status', $return['status']);
            $this->session->setFlashdata('form_response_message', $return['message']);
        }
        echo json_encode($return);
    }

    public function save_and_send(){
        $this->request->isAJAX() or exit();

        $rules = [
            'agenda'         => [
                'label' => lang('Common.agenda'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Agenda wajib diisi'
                ]
            ],
            'tanggal' => [
                'label' => lang('Common.date'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal wajib diisi'
                ]
            ],
            'waktu' => [
                'label' => lang('Common.time'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Waktu wajib diisi'
                ]
            ],
            'pewawancara' => [
                'label' => lang('Common.interviewer'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pewawancara wajib diisi'
                ]
            ]
        ];

        $return['status'] = 'error';

        do {
            $postData = $this->request->getPost();

            if (isset($postData['is_online'])) {
                $rules['link'] = [
                    'label' => lang('Common.link'),
                    'rules' => 'required',
                    'errors' => [
                        'Link Wajib Diisi'
                    ]
                ];
            } else {
                $rules['tempat'] = [
                    'label' => lang('Common.place'),
                    'rules' => 'required',
                    'errors' => [
                        'Tempat Wajib Diisi'
                    ]
                ];
            }

            if (!$this->validate($rules)) {
                $return['message'] = $this->validator->listErrors('default');
                break;
            }
             $payload = [
                'agenda' => $postData['agenda'],
                'tanggal' => $postData['tanggal'],
                'waktu'   => $postData['waktu'],
                'pewawancara' => $postData['pewawancara']
             ];

            if(isset($postData['is_online'])){
                $payload['via'] = 'online';
                $payload['link'] = $postData['link'];
                $payload['tempat'] = NULL;
            } else {
                $payload['via'] = 'offline';
                $payload['tempat'] = $postData['tempat'];
                $payload['link'] = NULL;
            }

            $interviewModel = new \App\Models\InterviewModel();

            if (!$postData['id_interview']) {
                $interviewModel->insert($payload);
            } else {
                $interviewModel->update($postData['id_interview'], $payload);
            }
            $jobApplicationsModel = new \App\Models\JobApplicationsModel();
            $applicants = $jobApplicationsModel->select('b.posisi, c.*')->join('lowongan b', 'b.id = lamaran.id_lowongan', 'left')->join('pelamar c', 'c.id = lamaran.id_pelamar', 'left')->where(['lamaran.id_lowongan' => $postData['id_lowongan'], 'lamaran.status' => 'interview'])->findAll();
            
            if($applicants){
                foreach($applicants as $applicant){
                    
                    $param['applicant'] = $applicant;
                    $param['interview'] = (object) $payload;
    
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

            $return = [
                'message'  => 'Interview Schedule Sended!',
                'status'   => 'success',
                'redirect' => route_to('job_vacancy_detail', $postData['id_lowongan'])
            ];
            $this->session->setFlashdata('open_interview_tab', TRUE);
        } while (0);
        if (isset($return['redirect'])) {
            $this->session->setFlashdata('form_response_status', $return['status']);
            $this->session->setFlashdata('form_response_message', $return['message']);
        }
        echo json_encode($return);
    }

    public function save_interview(){
        $this->request->isAJAX() or exit();
        $postData = $this->request->getPost();
        $return = [
            'message'  => 'Oops! Something Went Wrong!',
            'status'   => 'error',
        ];
        $data = $this->model->find($postData['id_lowongan']);
        if($data){
            $jobApplicationsModel = new \App\Models\JobApplicationsModel();
            $values = $postData['nilai_interview'];
            foreach($values as $key => $value){
                $jobApplicationsModel->where(['id_lowongan' => $postData['id_lowongan'], 'id_pelamar' => $key])->set(['nilai_interview' => $value])->update();
            }
            $return = [
                'message'  => 'Input Nilai Interview Berhasil',
                'status'   => 'success',
                'redirect' => route_to('job_vacancy_detail', $postData['id_lowongan'])
            ];
            $this->session->setFlashdata('open_interview_tab', TRUE);
        }
        if (isset($return['redirect'])) {
            $this->session->setFlashdata('form_response_status', $return['status']);
            $this->session->setFlashdata('form_response_message', $return['message']);
        }

        echo json_encode($return);
    }

    public function delete($id)
    {
        $this->request->isAJAX() or exit();
        $data = $this->model->find($id);
        if ($data) {
            $interviewModel = new \App\Models\InterviewModel();
            $psikotestModel = new \App\Models\PsikotestModel();

            if($data->gambar){
                $cld = new UploadApi();
                $cld->destroy($data->gambar);
            }

            $psikotestModel->where(['id_lowongan' => $data->id])->delete();
            if($data->id_interview){
                $interviewModel->delete($data->id_interview);
            }
            
            $this->model->update($id, ['deleted_by' => user_id()]);
            $this->model->delete($id);


            $return = ['message' => sprintf(lang('Common.deleted.success'), lang('JobVacancies.heading') . ' ' . $data->posisi), 'status' => 'success'];
        } else {
            $return = ['message' => lang('Common.not_found'), 'status' => 'error'];
        }
        echo json_encode($return);
    }

    public function getTotalQuestions(){
        $this->request->isAJAX() or exit();
        $ids_category = $this->request->getPost('ids_category');

        $db = db_connect();
        $builder = $db->table('soal');
        $builder->select('COUNT(id) as jumlah_soal');
        $builder->whereIn('id_kategori', $ids_category);
        $count = $builder->get()->getFirstRow();

        echo json_encode($count);
    }

    private function _setDefaultBreadcrumb(){
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('JobVacancies.heading'), route_to('permissions'));

        return $breadcrumb;
    }

}