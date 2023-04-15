<?php

namespace Modules\Administrations\Controllers;
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
        $breadcrumb = $this->_setDefaultBreadcrumb();
        if($id){
            $this->permEdit or exit();
            $data = $this->model->find($id);
            if(!$data){
                throw \Codeigniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $breadcrumb->add(lang('Common.edit'), current_url());
        } else {
            $this->permAdd or exit();
            $breadcrumb->add(lang('Common.add'), current_url());
        }

        $form = [
            [
                'id'        => 'id',
                'type'      => 'hidden',
                'value'     => ($data) ? $data->id : '',
            ],
            [
                'id'        => 'posisi',
                'value'     => ($data) ? $data->posisi : '',
                'label'     => lang('JobVacancies.position'),
                'required'  => 'required',
                'form_control_class' => 'col-md-5'
            ],
            [
                'id'    => 'qualifikasi',
                'value' => ($data) ? $data->qualifikasi : '',
                'label' => lang('JobVacancies.qualification'),
                'type'  => 'textarea',
                'class' => 'editor',
                'form_control_class' => 'col-md-10 col-sm-9' 
            ],
            [
                'id'    => 'tanggal_expired',
                'value' => ($data) ? $data->tanggal_expired : '',
                'label' => lang('JobVacancies.expired'),
                'type'  => 'date',
                'form_control_class' => 'col-md-5' 
            ],
            [
                'id'    => 'tampil',
                'value' => ($data) ? $data->tampil : '',
                'type'  => 'checkbox',
                'options' => [
                    [
                        'label' => 'Tampil',
                        'value' => '1',
                    ]
                ],
                'label' => lang('JobVacancies.visible'),
                'form_control_class' => 'col-md-5' 
            ],
            [
                'id'       => 'gambar',
                'value'    => ($data) ? $data->gambar : '',
                'label'    => lang('JobVacancies.image'),
                'type'     => 'image',
            ],
            [
                'type'                  => 'submit',
                'label'                 => lang('Common.btn.save_w_icon'),
                'back_url'              => $this->data['module_url'],
                'back_label'            => lang('Common.cancel'),
                'input_container_class' => 'form-group row text-right'
            ],
        ];

        if($data && $data->tampil == 1){
            $form[4]['options'][0]['checked'] = 'checked';
        }

        $form_builder = new \App\Libraries\FormBuilder();
        $this->data['form'] = [
            'action'    => route_to('job_vacancy_save'),
            'build'     => $form_builder->build_form_horizontal($form),
        ];
        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;

        $this->data['title'] = ($data ? lang('Common.edit') : lang('Common.add')) . ' ' . lang('JobVacancies.heading');
        $this->data['heading'] = $this->data['title'];
        return view('form', $this->data);
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
        // $table->withUser();
        $table->setSelect("a.id, a.posisi, '".($this->permEdit ? route_to('job_vacancy_form', 'ID') : '')."' AS `edit`, '".($this->permDelete ? route_to('job_vacancy_delete', 'ID') : '')."' AS `delete`");
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
            'qualifikasi' => [
                'label' => lang('JobVacancies.qualification'),
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kualifikasi wajib diisi'
                ]
            ]
        ];

        $return['status'] = 'error';

        do {
            if (!$this->validate($rules)) {
                $return['message'] = $this->validator->listErrors('bootstrap_list');
                break;
            }
            $postData = input_filter($this->request->getPost());
            $postData['qualifikasi'] = nl2br($postData['qualifikasi']);
            
            $image = $this->request->getFile('upload_image');
            $cld = new UploadApi();
            if ($image) {
                if ($image->isValid()) {
                    $upload = $cld->upload($image->getRealPath(), ['folder' => 'pt-tekpak-indonesia/vacancy']);
                    $postData['gambar'] = $upload['public_id'];
                }
            } else {
                if (isset($postData['id'])) {
                    if ($image = $this->model->find($postData['id'])) {
                        if ($image->gambar) {
                            $postData['gambar'] = $image->gambar;
                            if (isset($postData['delete_image']) && $postData['delete_image'] === '1') {
                                $cld->destroy($image->gambar);
                                $postData['gambar'] = NULL;
                            }
                        }
                    }
                }
            }

            if(!isset($postData['tampil'])){
                $postData['tampil'] = 0;
            }

            if (!$postData['id']) {
                $postData['created_by'] = user_id();
                $this->model->insert($postData);
            } else {
                $postData['updated_by'] = user_id();
                $this->model->update($postData['id'], $postData);
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
            $this->model->update($id, ['deleted_by' => user_id()]);
            $this->model->delete($id);
            $return = ['message' => sprintf(lang('Common.delete.success'), lang('JobVacancies.heading') . ' ' . $data->name), 'status' => 'success'];
        } else {
            $return = ['message' => lang('Common.not_found'), 'status' => 'error'];
        }
        echo json_encode($return);
    }

    private function _setDefaultBreadcrumb(){
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('JobVacancies.heading'), route_to('permissions'));

        return $breadcrumb;
    }

}