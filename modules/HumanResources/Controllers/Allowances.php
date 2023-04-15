<?php

namespace Modules\HumanResources\Controllers;
use \App\Controllers\BaseController;
use \App\Models\AllowancesModel;
use \App\Models\PositionsModel;

class Allowances extends BaseController{

    protected $data;
    protected $model;
    private $perm;
    private $permView;
    private $permAdd;
    private $permDelete;

    public function __construct(){
        parent::__construct();
        $this->model = new AllowancesModel();
        $this->data['module'] = 'human_resource';
        $this->data['menu'] = 'allowance';
        $this->data['module_url'] = route_to('allowances');
        $this->perm = 'position';
        $this->permView = has_permission($this->perm);
        $this->permAdd = has_permission($this->perm. '/add');
        $this->permEdit = has_permission($this->perm. '/edit');
        $this->permDelete = has_permission($this->perm. '/delete');
    }

    public function index(){
        $this->permView or exit();
        $this->permView or exit();
        $this->data['table'] = [
            'columns' => [
                [
                    'title'  => lang('Allowances.position_id'),
                    'field'     => 'position',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                [
                    'title'  => lang('Allowances.meal_allowance'),
                    'field'     => 'meal_allowance',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
                [
                    'title'  => lang('Allowances.transport_allowance'),
                    'field'     => 'transport_allowance',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
                [
                    'title'  => lang('Allowances.position_allowance'),
                    'field'     => 'position_allowance',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
            ],
            'url'       => route_to('allowance_list'),
            'cookie_id' => 'table-allowance'
        ];
        if ($this->permEdit || $this->permDelete) {
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
        $this->data['title'] = lang('Allowances.heading');
        $this->data['heading'] = lang('Allowances.heading');
        if ($this->permAdd) {
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('allowance_form', 0), lang('Allowances.add_heading'));
        }
        $this->data['toolbar'] = view('table-toolbar/default', $this->data);
        return view('list', $this->data);
    }

    public function get_list()
    {
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['position_id'] = $getData['search'];
        $filter['meal_allowance'] = $getData['search'];
        $filter['transport_allowance'] = $getData['search'];
        $filter['position_allowance'] = $getData['search'];

        $table = new \App\Models\TableModel('allowances');
        if (isset($getData['sort'])) {
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }

        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.id, b.name position, a.meal_allowance, a.transport_allowance, a.position_allowance, '" . ($this->permEdit ? route_to('allowance_form', 'ID') : '') . "' AS `edit`, '" . ($this->permDelete ? route_to('allowance_delete', 'ID') : '') . "' AS `delete`");
        $table->setJoin([
            [
                'table' => 'positions b',
                'on'    => 'b.id = a.position_id',
                'type'  => 'left'
            ]
        ]);
        $output['rows'] = $table->getAll();
        $output['total'] = $table->countAll();
        $table->setFilter();
        $output['totalNotFiltered'] = $table->countAll();
        echo json_encode($output);
    }

    public function form($id)
    {
        $data = NULL;
        $breadcrumb = $this->_setDefaultBreadcrumb();
        if ($id) {
            $this->permEdit or exit();
            $data = $this->model->find($id);
            if (!$data) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $breadcrumb->add(lang('Common.edit'), current_url());
        } else {
            $this->permAdd or exit();
            $breadcrumb->add(lang('Common.add'), current_url());
        }

        $dropdownPosition = ['' => ''];
        $positionsModel = new PositionsModel();
        $positions = $positionsModel->findAll();

        if($positions){
            foreach($positions as $position){
                $dropdownPosition[$position->id] = $position->name;
            }
        }

        $form = [
            [
                'id'    => 'id',
                'type'  => 'hidden',
                'value' => ($data) ? $data->id : '',
            ],
            [
                'id'                 => 'position_id',
                'value'              => ($data) ? $data->position_id : '',
                'label'              => lang('Allowances.position_id'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'class'              => 'select2',
                'type'               => 'dropdown',
                'options'            => $dropdownPosition,
            ],
            [
                'id'                 => 'meal_allowance',
                'value'              => ($data) ? $data->meal_allowance : '',
                'label'              => lang('Allowances.meal_allowance'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'class'              => 'number',
                'input_addons'       => [
                    'pre' => 'Rp'
                ]
            ],
            [
                'id'                 => 'transport_allowance',
                'value'              => ($data) ? $data->transport_allowance : '',
                'label'              => lang('Allowances.transport_allowance'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'class'              => 'number',
                'input_addons'       => [
                    'pre' => 'Rp'
                ]
            ],
            [
                'id'                 => 'position_allowance',
                'value'              => ($data) ? $data->position_allowance : '',
                'label'              => lang('Allowances.position_allowance'),
                'form_control_class' => 'col-md-5',
                'class'              => 'number',
                'input_addons'       => [
                    'pre' => 'Rp'
                ]
            ],
            [
                'type'                  => 'submit',
                'label'                 => lang('Common.btn.save_w_icon'),
                'form_control_class'    => 'col-md-5',
                'back_url'              => $this->data['module_url'],
                'back_label'            => lang('Common.cancel'),
                'input_container_class' => 'form-group row text-right'
            ]
        ];

        $form_builder = new \App\Libraries\FormBuilder();
        $this->data['form'] = [
            'action' => route_to('allowance_save'),
            'build'  => $form_builder->build_form_horizontal($form),
        ];

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;
        $this->data['title'] = $data ? lang('Allowances.edit_heading') : lang('Allowances.add_heading');
        $this->data['heading'] = $this->data['title'];
        return view('form', $this->data);
    }


    public function save()
    {
        $this->request->isAJAX() or exit();
                
        $rules = [
            'position_id'      => [
                'label' => lang('Allowances.position_id'),
                'rules' => 'required'
            ],
        ];
        $return['status'] = 'error';

        do {
            
            $postData = $this->request->getPost();
            
            if (!$this->validate($rules)) {
                $return['message'] = $this->validator->listErrors('default');
                break;
            }

            if (!$postData['id']) {
                $postData['created_by'] = user_id();
                $this->model->insert($postData);
                $postData['id'] = $this->model->getInsertID();
            } else {
                $postData['updated_by'] = user_id();
                $this->model->update($postData['id'], $postData);
            }

            $positionsModel = new PositionsModel();
            $position = $positionsModel->find($postData['position_id']);

            $return = [
                'message'  => sprintf(lang('Common.saved.success'), lang('Common.allowance') . ' ' . $position->name),
                'status'   => 'success',
                'redirect' => route_to('allowances')
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

            $positionsModel = new PositionsModel();
            $position = $positionsModel->find($data->position_id);
            $this->model->delete($id);
            $return = [
                'message' => sprintf(lang('Common.deleted.success'), lang('Common.allowance') . ' ' . $position->name),
                'status'  => 'success'
            ];
        } else {
            $return = [
                'message' => lang('Common.not_found'),
                'status'  => 'error'
            ];
        }

        echo json_encode($return);
    }


    private function _setDefaultBreadcrumb()
    {
        $breadcrumb = new \App\Libraries\Breadcrumb();
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Allowances.heading'), route_to('allowances'));

        return $breadcrumb;
    }

}