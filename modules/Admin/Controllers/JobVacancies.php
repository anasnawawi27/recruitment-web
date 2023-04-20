<?php

namespace Modules\Admin\Controllers;
use \App\Controllers\BaseController;
use \App\Models\JobVacanciesModel;
use Cloudinary\Api\Upload\UploadApi;

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
        $table->setSelect("a.id, a.posisi, a.batas_tanggal, '".($this->permEdit ? route_to('job_vacancy_form', 'ID') : '')."' AS `edit`, '".($this->permDelete ? route_to('job_vacancy_delete', 'ID') : '')."' AS `delete`");
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
                    'waktu'         => str_replace('T', ' ', $postData['waktu_interview']) . ':00',
                    'pewawancara'   => $postData['pewawancara'],
                    'konten_email' => nl2br( $postData['konten_email'])
                ];
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