<?php

namespace Modules\Psikotest\Controllers;
use \App\Controllers\BaseController;
use \App\Models\QuestionsModel;
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
        $this->data['module_url'] = route_to('questions');
        $this->data['filter_name'] = 'table_filter_question';
        $this->model = new QuestionsModel();
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
                [
                    'title'         => lang('Questions.number_question'),
                    'field'         => 'jumlah_soal',
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
        $this->data['title'] = lang('Questions.heading');
        $this->data['heading'] = lang('Questions.heading');
        if($this->permAdd){
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('question_form',0), lang('Questions.add_heading'));
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
        $table->setSelect("a.id, a.kategori, (SELECT COUNT(id) FROM soal WHERE id_kategori = a.id ) as jumlah_soal, '".($this->permEdit ? route_to('question_form', 'ID') : '')."' AS `edit`, '".($this->permDelete ? route_to('question_delete', 'ID') : '')."' AS `delete`");
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
            $files = $this->request->getFiles();

            $options = $postData['options'];
            $questions = $postData['questions'];
            
            foreach($questions as $key => $question){
                $payload = [
                    'id_kategori' => $postData['id_kategori'],
                    'pertanyaan'  => $question,
                    'jawaban'     => $postData['answers'][$key]
                ];

                $cld = new UploadApi();
                if (isset($files['image_questions'][$key])) {
                    $image_question = $files['image_questions'][$key];
                    if ($image_question->isValid()) {
                        $upload = $cld->upload($image_question->getRealPath(), ['folder' => 'pt-tekpak-indonesia/soal/gambar_soal']);
                        $payload['gambar'] = $upload['public_id'];
                    }
                }

                $opsi = [];
                $data = [];
                foreach($options[$key] as $index => $option){
                    $data['opsi'] = $option;

                    if (isset($files['image_options'][$key][$index])) {
                        $image_option = $files['image_options'][$key][$index];
                        if ($image_option->isValid()) {
                            $upload = $cld->upload($image_option->getRealPath(), ['folder' => 'pt-tekpak-indonesia/soal/gambar_pilihan']);
                            $data['gambar_id'] = $upload['public_id'];
                        }
                    }
                    $opsi[] = $data;
                }
                $payload['options'] = json_encode($opsi);

                if (!$postData['id']) {
                    $this->model->insert($payload);
                } else {
                    $this->model->update($postData['id'], $payload);
                }
            }

            $return = [
                'message'  =>sprintf(lang('Common.saved.success'), lang('Questions.heading')),
                'status'   => 'success',
                'redirect' => route_to('questions')
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