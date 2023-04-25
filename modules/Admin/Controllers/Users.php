<?php

namespace Modules\Admin\Controllers;

use \App\Controllers\BaseController;
use \App\Models\UsersModel;
use \App\Models\GroupsModel;
use \App\Models\TableModel;
use \App\Libraries\FormBuilder;
use Cloudinary\Api\Upload\UploadApi;

class Users extends BaseController
{

    protected $model;
    private $perm;
    private $permView;
    private $permAdd;
    private $permEdit;
    private $permDelete;

    public function __construct()
    {
        parent::__construct();
        $this->model = new UsersModel();
        $this->data['module'] = 'user';
        $this->data['menu'] = 'users';
        $this->data['module_url'] = route_to('users');
        $this->perm = 'user';
        $this->permView = has_permission($this->perm);
        $this->permAdd = has_permission($this->perm . '/add');
        $this->permEdit = has_permission($this->perm . '/edit');
        $this->permDelete = has_permission($this->perm . '/delete');
    }

    public function index()
    {
        $this->permView or exit();
        $this->data['table'] = [
            'columns' => [
                [
                    'title'  => lang('Users.fullname'),
                    'field'     => 'nama_lengkap',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                [
                    'title'  => lang('Users.username'),
                    'field'     => 'username',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                [
                    'title'  => lang('Users.role'),
                    'field'     => 'role',
                    'sortable'  => 'true',
                ],
                [
                    'title'  => lang('Users.status'),
                    'field'     => 'status',
                    'sortable'  => 'true',
                    'formatter' => 'statusFormatterDefault'
                ],
            ],
            'url'       => route_to('user_list'),
            'cookie_id' => 'table-user'
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
        $this->data['title'] = lang('Users.heading');
        $this->data['heading'] = lang('Users.heading');
        if ($this->permAdd) {
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('user_form', 0), lang('Users.add_heading'));
        }
        $this->data['toolbar'] = view('table-toolbar/default', $this->data);
        return view('list', $this->data);
    }

    public function get_list()
    {
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['a.nama_lengkap'] = $getData['search'];
        $filter['a.username'] = $getData['search'];
        $filter['a.active'] = $getData['search'];
        $filter['c.description'] = $getData['search'];

        $table = new TableModel('users');
        if (isset($getData['sort'])) {
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }
        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.id, a.nama_lengkap, a.username, c.description as role, (CASE WHEN (a.active = 1) THEN  ( 'active') ELSE( 'nonactive' ) END) as status, '" . ($this->permEdit ? route_to('user_form', 'ID') : '') . "' AS `edit`, '" . ($this->permDelete ? route_to('user_delete', 'ID') : '') . "' AS `delete`");
        $table->setJoin([
            [
                'table' => 'auth_groups_users b',
                'on'    => 'a.id =  b.user_id',
                'type'  => 'left'
            ],
            [
                'table' => 'auth_groups c',
                'on'    => 'b.group_id =  c.id',
                'type'  => 'left'
            ],
        ]);
        $table->setWhere(['c.name !=' => 'applicant']);
        $output['rows'] = $table->getAll();
        $output['total'] = $table->countAll();
        $table->setFilter();
        $output['totalNotFiltered'] = $table->countAll();
        echo json_encode($output);
    }

    public function form($id)
    {
        $data = NULL;
        $group = NULL;
        $breadcrumb = $this->_setDefaultBreadcrumb();
        if ($id) {
            $this->permEdit or exit();
            $data = $this->model->find($id);
            $groupsModel = new GroupsModel();
            $groupsModel->select('auth_groups.id, auth_groups.description as role');
            $groupsModel->join('auth_groups_users b', 'b.group_id = auth_groups.id', 'left');
            $group = $groupsModel->where('b.user_id', $data->id)->first();
            if (!$data) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
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
                'id'                    => 'nama_lengkap',
                'value'                 => ($data) ? $data->nama_lengkap : '',
                'label'                 => lang('Users.fullname'),
                'required'              => 'required',
            ],
            [
                'id'                    => 'email',
                'value'                 => ($data) ? $data->email : '',
                'label'                 => lang('Users.email'),
                'required'              => 'required',
            ],
            [
                'id'                    => 'image',
                'value'                 => ($data) ? $data->image : '',
                'label'                 => lang('Users.image'),
                'type'                  => 'image',
            ],
            [
                'id'                    => 'username',
                'value'                 => ($data) ? $data->username : '',
                'label'                 => lang('Users.username'),
                'required'              => 'required',
            ],
            
            [
                'id'                    => 'password',
                'value'                 => '',
                'label'                 => lang('Users.password'),
                'type'                  => 'password'
            ],
            [
                'id'                    => 'group_id',
                'value'                 => ($group) ? $group->id : '',
                'label'                 => lang('Users.role'),
                'type'                  => 'dropdown',
                'class'                 => 'select2',
                'options'               => $this->_dropdownGroups(),
                'required'              => 'required',
            ],
            [
                'id'                    => 'active',
                'value'                 => ($data) ? $data->active : '',
                'label'                 => lang('Users.status'),
                'type'                  => 'dropdown',
                'class'                 => 'select2',
                'options'               => [
                    '1' => 'Active',
                    '0' => 'Not Active'
                ],
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

        $form_builder = new FormBuilder();
        $this->data['form'] = [
            'action' => route_to('user_save'),
            'build'  => $form_builder->build_form_horizontal($form),
        ];

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;
        $this->data['title'] = $data ? lang('Users.edit_heading') : lang('Users.add_heading');
        $this->data['heading'] = $this->data['title'];
        return view('form', $this->data);
    }

    public function save()
    {
        $this->request->isAJAX() or exit();

        $rules = [
            'nama_lengkap'      => [
                'label' => lang('Users.nama_lengkap'),
                'rules' => 'required'
            ],
            'email'      => [
                'label' => lang('Users.email'),
                'rules' => 'required'
            ],
            'username' => [
                'label' => lang('Users.username'),
                'rules' => 'required',
            ],
            'group_id' => [
                'label' => lang('Users.role'),
                'rules' => 'required',
            ],
        ];
        $return['status'] = 'error';

        do {

            $postData = $this->request->getPost();

            if (!$postData['id']) {
                $rules['password'] = [
                    'label' => lang('Users.password'),
                    'rules' => 'required|strong_password|min_length[8]'
                ];
                $sameData = $this->model->where('username', $postData['username'])->find();
                if ($sameData) {
                    $rules['username']['label'] = lang('Users.username');
                    $rules['username']['rules'] = 'is_unique[users.username]';
                }
            } else {
                $data = $this->model->find($postData['id']);
                $rules['username']['rules'] = 'is_unique[users.username,username,' . $data->username . ']';
            }

            if (!$this->validate($rules)) {
                $return['message'] = $this->validator->listErrors('default');
                break;
            }

            $this->model->where('email', $postData['email']);
            if ($postData['id']) {
                $this->model->where('id !=', $postData['id']);
            }

            $emailCheck = $this->model->first();
            if ($emailCheck) {
                $return['message'] = lang('Users.email_existing');
                break;
            }

            if ($postData['password']) {
                $postData['password_hash'] = $this->_set_password($postData['password']);
            }

            // image upload
            $image = $this->request->getFile('upload_image');
            $cld = new UploadApi();
            if ($image) {
                if ($image->isValid()) {
                    $upload = $cld->upload($image->getRealPath(), ['folder' => 'pt-tekpak-indonesia/users']);
                    $postData['image'] = $upload['public_id'];
                }
            } else {
                if (isset($postData['id'])) {
                    if ($image = $this->model->find($postData['id'])) {
                        $postData['image'] = $image->image;
                        if ($image->image) {
                            if (isset($postData['delete_image']) && $postData['delete_image'] === '1') {
                                $postData['image'] = NULL;
                            }
                        }
                    }
                }
            }

            $groupModel = new \Myth\Auth\Authorization\GroupModel();
            if (!$postData['id']) {

                $this->model->insert($postData);
                $postData['id'] = $this->model->getInsertID();
            } else {
                $this->model->update($postData['id'], $postData);
                $groupModel->removeUserFromAllGroups($postData['id']);
            }
            $groupModel->addUserToGroup($postData['id'], $postData['group_id']);
            $return = [
                'message'  => sprintf(lang('Common.saved.success'), lang('Common.user') . ' ' . $postData['nama_lengkap']),
                'status'   => 'success',
                'redirect' => route_to('users')
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
            $return = [
                'message' => sprintf(lang('Common.deleted.success'), lang('Common.user') . ' ' . $data->nama_lengkap),
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

    private function _dropdownGroups()
    {
        $groups = new GroupsModel();
        $groups = $groups->where('name !=', 'applicant')->findAll();
        $options = ['' => ''];
        if ($groups) {
            foreach ($groups as $group) {
                $options[$group->id] = ucwords($group->name);
            }
        }
        return $options;
    }

    private function _set_password(string $password)
    {
        $config = config('Auth');
        $hashOptions = [
            'cost' => $config->hashCost,
        ];
        $setPasswordUser = password_hash(
            base64_encode(
                hash('sha384', $password, true)
            ),
            $config->hashAlgorithm,
            $hashOptions
        );
        return $setPasswordUser;
    }

    private function _setDefaultBreadcrumb()
    {
        $breadcrumb = new \App\Libraries\Breadcrumb();
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Users.heading'), route_to('users'));

        return $breadcrumb;
    }
}
