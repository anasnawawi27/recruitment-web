<?php

namespace Modules\Users\Controllers;
use \App\Controllers\BaseController;
use \App\Models\GroupsModel;
use \App\Models\PermissionsModel;

class Roles extends BaseController
{
    protected $model;
    private $perm;
    private $permView;
    private $permAdd;
    private $permDelete;

    public function __construct(){
        parent::__construct();
        $this->perm = 'user/role';
        $this->data['menu'] = 'user_role';
        $this->data['module'] = 'user';
        $this->data['module_url'] = route_to('user_roles');
        $this->data['filter_name'] = 'table_filter_user_role';
        $this->model = new GroupsModel();
        $this->permView = has_permission($this->perm);
        $this->permAdd = has_permission($this->perm. '/add');
        $this->permEdit = has_permission($this->perm. '/edit');
        $this->permDelete = has_permission($this->perm. '/delete');
    }

    public function index(){
        $this->permView or exit();
        $this->data['table'] = [
            'columns' => [
                '0' => [
                    'title'         => lang('Roles.name'),
                    'field'         => 'name',
                    'sortable'      => 'true',
                    'switchable'    => 'true',
                ],
                '1' => [
                    'title'         => lang('Roles.description'),
                    'field'         => 'description',
                    'switchable'    => 'true'
                ]
            ],
            'url'       => route_to('user_role_list'),
            'cookie_id' => 'table-user-role'  
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
        $this->data['title'] = lang('Roles.heading');
        $this->data['heading'] = lang('Roles.heading');
        if($this->permAdd){
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('user_role_form',0), lang('Roles.add_heading'));
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
                'id'        => 'name',
                'value'     => ($data) ? $data->name : '',
                'label'     => lang('Roles.name'),
                'required'  => 'required',
                'form_control_class' => 'col-md-5'
            ],
            [
                'id'    => 'description',
                'value' => ($data) ? $data->description : '',
                'label' => lang('Roles.description'),
                'form_control_class' => 'col-md-5' 
            ],
            [
                'id' => 'permissions[]',
                'type'  => 'html',
                'label' => lang('Roles.permissions'),
                'html'  => $this->_html_permission($data)
            ],
            [
                'type'  => 'submit',
                'label' => lang('Common.btn.save_w_icon'),
                'form_control_class' => 'col-md-5',
                'back_url'  => $this->data['module_url'],
                'back_label' => lang('Common.cancel'),
                'input_container_class' => 'form-role row text-right'
            ],
        ];

        $form_builder = new \App\Libraries\FormBuilder();
        $this->data['form'] = [
            'action'    => route_to('user_role_save'),
            'build'     => $form_builder->build_form_horizontal($form),
        ];
        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;
        $this->data['btn_option'] = sprintf(lang('Common.btn.back_w_icon'), route_to('user_roles'));

        $this->data['title'] = ($data ? lang('Common.edit') : lang('Common.add')) . ' ' . lang('Roles.heading');
        $this->data['heading'] = $this->data['title'];
        return view('form', $this->data);
    }

    public function get_list(){
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['name'] = $getData['search'];
        $table = new \App\Models\TableModel('auth_groups');
        if(isset($getData['sort'])){
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }
        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        // $table->withUser();
        $table->setSelect("a.id, a.name, a.description, '".($this->permEdit ? route_to('user_role_form', 'ID') : '')."' AS `edit`, '".($this->permDelete ? route_to('user_role_delete', 'ID') : '')."' AS `delete`");
        $output['rows'] = $table->getAll();
        $output['total'] = $table->countAll();
        $table->setFilter();
        $output['totalNotFiltered'] = $table->countAll();
        echo json_encode($output);
    }

    public function save(){
        $this->request->isAJAX() or exit();
        $rules = [
            'name'  => [
                'label' => lang('Roles.name'),
                'rules' => 'required'
            ]
            ];
            $return['status'] = 'error';
            do{
                if(!$this->validate($rules)){
                    $return['message'] = $this->validator->listErrors('default');
                    break;
                }
                $postData = $this->request->getPost();
                $permissions = isset($postData['permissions']) ? array_filter($postData['permissions']) : '';

                if(!$postData['id']){
                    $postData['created_by'] = user_id();
                    $this->model->insert($postData);
                    $postData['id'] = $this->model->getInsertID();
                } else {
                    $postData['updated_by'] = user_id();
                    $this->model->update($postData['id'],$postData);
                    $this->model->deletePermissions($postData['id']);
                }

                if($permissions){
                    $dataPermissions = [];
                    foreach($permissions as $permissionId){
                        array_push($dataPermissions, ['group_id' => $postData['id'], 'permission_id' => $permissionId]);
                    }
                    $this->model->insertPermissions($dataPermissions);
                }

                $return = [
                    'message' => sprintf(lang('Common.saved.success'), lang('Roles.heading') . ' ' . $postData['name']),
                    'status' => 'success',
                    'redirect' => route_to('user_roles')
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
            $return = ['message' => sprintf(lang('Common.delete.success'), lang('Roles.heading') . ' ' . $data->name), 'status' => 'success'];
        } else {
            $return = ['message' => lang('Common.not_found'), 'status' => 'error'];
        }
        echo json_encode($return);
    }

    private function _html_permission($data = NULL){
        $permissionsModel = new PermissionsModel();
        $permissions = $permissionsModel->orderBy('name')->findAll();
        $perm_exists = [];
        if($data){
            $perm_exist = $permissionsModel->onGroups($data->id);
            if($perm_exist){
                foreach($perm_exist as $perm){
                    array_push($perm_exists, $perm->permission_id);
                }
            }
        }
        $html = '<div style="height: 216px; overflow: auto; padding: .4375rem; border: 1px solid #ddd;">';
        foreach ($permissions as $permission) {
            $html .= '<div class="checkbox"><label><input type="checkbox" name="permissions[]" value="' . $permission->id . '" ' . ($data ? (in_array($permission->id, $perm_exists) ? 'checked' : '') : '') . '> ' . ($permission->description ?? $permission->name) . '</label></div>';
        }
        $html .= '</div>';
        return $html;

    }

    private function _setDefaultBreadcrumb(){
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Roles.heading'), route_to('permissions'));

        return $breadcrumb;
    }

}