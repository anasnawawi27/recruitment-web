<?php

namespace Modules\Users\Controllers;

use \App\Controllers\BaseController;
use \App\Models\PermissionsModel;
use \App\Models\TableModel;

class Permissions extends BaseController
{
    protected $model;
    private $perm;
    private $permView;
    private $permAdd;
    private $permDelete;

    public function __construct()
    {
        parent::__construct();
        $this->model = new PermissionsModel();
        $this->data['module'] = 'user';
        $this->data['menu'] = 'permission';
        $this->data['module_url'] = route_to('permissions');
        $this->perm = 'permission';
        $this->permView = has_permission($this->perm);
        $this->permAdd = has_permission($this->perm . '/add');
        $this->permEdit = has_permission($this->perm . '/edit');
        $this->permDelete = has_permission($this->perm . '/delete');
    }

    public function index()
    {
        $this->permView or exit();
        $this->data['heading'] = 'User Permissions';
        $this->data['table'] = [
            'columns'   => [
                [
                    'title'      => lang('Permissions.name'),
                    'field'      => 'name',
                    'sortable'   => 'true',
                    'switchable' => 'true'
                ],
                [
                    'title'    => lang('Permissions.description'),
                    'field'    => 'description',
                    'sortable'   => 'true',
                    'switchable' => 'true'
                ]
            ],
            'url'       => route_to('permission_list'),
            'cookie_id' => 'table-permission'
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

        if ($this->permAdd) {
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('permission_form', 0), lang('Permissions.add_heading'));
        }

        $breadcrumb = $this->_setDefaultBreadcrumb();
        $this->data['title'] = lang('Permissions.heading');
        $this->data['heading'] = lang('Permissions.heading');
        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['toolbar'] = view('table-toolbar/default', $this->data);
        return view('list', $this->data);
    }

    public function get_list()
    {
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['name'] = $getData['search'];
        $filter['description'] = $getData['search'];
        $table = new TableModel('auth_permissions', false);
        if (isset($getData['sort'])) {
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }
        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.id, a.name, a.description, '" . ($this->permEdit ? route_to('permission_form', 'ID') : '') . "' AS `edit`, '" . ($this->permDelete ? route_to('permission_delete', 'ID') : '') . "' AS `delete`");
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
                throw \Codeigniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $breadcrumb->add(lang('Common.edit'), current_url());
        } else {
            $this->permAdd or exit();
            $breadcrumb->add(lang('Common.add'), current_url());
        }

        $form = [
            [
                'id'                    => 'id',
                'type'                  => 'hidden',
                'value'                 => ($data) ? $data->id : '',
            ],
            [
                'id'                    => 'name',
                'value'                 => ($data) ? $data->name : '',
                'label'                 => lang('Permissions.name'),
                'required'              => 'required',
                'form_control_class'    => 'col-md-5'
            ],
            [
                'id'                    => 'description',
                'value'                 => ($data) ? $data->description : '',
                'label'                 => lang('Permissions.description'),
                'form_control_class'    => 'col-md-5'
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
            'action' => route_to('permission_save'),
            'build'  => $form_builder->build_form_horizontal($form),
        ];

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;
        $this->data['title'] = $data ? lang('Permissions.edit_heading') : lang('Permissions.add_heading');
        $this->data['heading']  = $this->data['title'];
        return view('form', $this->data);
    }

    public function save()
    {
        $this->request->isAJAX() or exit();
        $rules = [
            'name'  => [
                'label' => lang('Permissions.name'),
                'rules' => 'required|is_unique[auth_permissions.name,id,{id}]',
            ]
        ];
        $return['status'] = 'error';

        do {
            if (!$this->validate($rules)) {
                $return['message'] = $this->validator->listErrors('default');
                break;
            }
            $postData = $this->request->getPost();
            $this->model->save($postData);
            $return = [
                'message'   => sprintf(lang('Common.saved.success'), lang('Common.permissions') . ' ' . $postData['name']),
                'status'    => 'success',
                'redirect'  => route_to('permissions')
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
        $this->request->isAJAX() or exit;
        $this->permDelete or exit;
        $data = $this->model->find($id);
        $return['status'] = 'error';
        do {
            if (!$data) {
                $return['message'] = sprintf(lang('Common.deleted.failed'), lang('Permissions.heading')) . ' ' . lang('Common.not_found');
                break;
            }
            $this->model->delete($data->id);
            $return = [
                'status'  => 'success',
                'message' => sprintf(lang('Common.deleted.success'), lang('Permissions.heading') . ' ' . $data->name)
            ];
        } while (0);
        return json_encode($return);
    }

    private function _setDefaultBreadcrumb()
    {
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Permissions.heading'), route_to('permissions'));

        return $breadcrumb;
    }
}
