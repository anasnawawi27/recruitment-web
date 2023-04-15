<?php

namespace Modules\Psikotest\Controllers;
use \App\Controllers\BaseController;
use \App\Models\QuestionTypesModel;
use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

class Questions extends BaseController
{
    protected $model;
    private $perm;
    private $permView;
    private $permAdd;
    private $permEdit;
    private $permDelete;

    public function __construct(){
        parent::__construct();
        $this->perm = 'question';
        $this->data['menu'] = 'question';
        $this->data['module'] = 'psikotest';
        $this->data['module_url'] = route_to('question_types');
        $this->data['filter_name'] = 'table_filter_question_type';
        $this->model = new QuestionTypesModel();
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
                    'title'         => lang('QuestionTypes.category'),
                    'field'         => 'kategori',
                    'sortable'      => 'true',
                    'switchable'    => 'true',
                ],
            ],
            'url'       => route_to('question_list'),
            'cookie_id' => 'table-question'  
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
        $this->data['title'] = lang('QuestionTypes.heading');
        $this->data['heading'] = lang('QuestionTypes.heading');
        if($this->permAdd){
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('question_form',0), lang('QuestionTypes.add_heading'));
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
                'id'        => 'kategori',
                'value'     => ($data) ? $data->kategori : '',
                'label'     => lang('QuestionTypes.category'),
                'required'  => 'required',
                'form_control_class' => 'col-md-5'
            ],
            [
                'type'                  => 'submit',
                'label'                 => lang('Common.btn.save_w_icon'),
                'back_url'              => $this->data['module_url'],
                'back_label'            => lang('Common.cancel'),
                'input_container_class' => 'form-group row text-right'
            ],
        ];

        $form_builder = new \App\Libraries\FormBuilder();
        $this->data['form'] = [
            'action'    => route_to('question_type_save'),
            'build'     => $form_builder->build_form_horizontal($form),
        ];
        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;

        $this->data['title'] = ($data ? lang('Common.edit') : lang('Common.add')) . ' ' . lang('Questions.heading');
        $this->data['heading'] = $this->data['title'];

        $questionTypeModel = new \App\Models\QuestionTypesModel();
        $this->data['types'] = $questionTypeModel->findAll();
        return view('form_question', $this->data);
    }

    public function get_list(){
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['kategori'] = $getData['search'];
        $table = new \App\Models\TableModel('kategori_soal', false);
        if(isset($getData['sort'])){
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }
        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        // $table->withUser();
        $table->setSelect("a.id, a.kategori, '".($this->permEdit ? route_to('question_form', 'ID') : '')."' AS `edit`, '".($this->permDelete ? route_to('question_delete', 'ID') : '')."' AS `delete`");
        $output['rows'] = $table->getAll();
        $output['total'] = $table->countAll();
        $table->setFilter();
        $output['totalNotFiltered'] = $table->countAll();
        echo json_encode($output);
    }

    public function save(){
        $this->request->isAJAX() or exit();
        $return['status'] = 'error';

        do {
            $postData = $this->request->getPost();
            $image_questions = $this->request->getFile('image_questions');
            $questions = $postData['questions'];

            $options = [];

            foreach($questions as $key => $question){

            }
            $cld = new UploadApi();
            foreach($image_questions  as $key => $image_question){
                $cld = new UploadApi();
                if ($image_question) {
                    if ($image_question->isValid()) {
                        $upload = $cld->upload($file->getRealPath(), ['folder' => 'pt-tekpak-indonesia/' . $key]);
                        if($key == 'ktp' || $key == 'file_vaksin_1' | $key == 'file_vaksin_2' | $key == 'file_vaksin_3'){
                            $documents[$key] = $upload['secure_url'];
                        } else {
                            $postData[$key] = $upload['secure_url'];
                        }
                    }
                }
            }

            $payload = [
                'id_kategori' => $postData['id_kategori'],
                ''
            ];

            if (!$postData['id']) {
                $postData['created_by'] = user_id();
                $this->model->insert($postData);
            } else {
                $postData['updated_by'] = user_id();
                $this->model->update($postData['id'], $postData);
            }

            $return = [
                'message'  =>sprintf(lang('Common.saved.success'), lang('Questions.heading') . ' ' . $postData['kategori']),
                'status'   => 'success',
                'redirect' => route_to('questions')
            ];
        } while (0);
        if (isset($return['redirect'])) {
            $this->session->setFlashdata('form_response_status', $return['status']);
            $this->session->setFlashdata('form_response_message', $return['message']);
        }
        echo json_encode($output);
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

    private function _setDefaultBreadcrumb(){
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Questions.heading'), route_to('questions'));

        return $breadcrumb;
    }

}