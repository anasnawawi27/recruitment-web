<?php

namespace Modules\Users\Controllers;

use \App\Controllers\BaseController;
use \App\Models\UsersModel;
use \App\Models\GroupsModel;
use \App\Models\JobTitlesModel;
use \App\Models\TableModel;
use \App\Libraries\FormBuilder;
use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

class Users extends BaseController
{

    protected $model;
    private $perm;
    private $permView;
    private $permAdd;
    private $permDelete;

    public function __construct()
    {
        parent::__construct();
        $this->model = new UsersModel();
        $this->data['module'] = 'user';
        $this->data['menu'] = 'user_account';
        $this->data['module_url'] = route_to('user_accounts');
        $this->perm = 'user/account';
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
                    'title'  => lang('Users.employee_id'),
                    'field'     => 'employee_id',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'detailFormatterDefault',
                ],
                [
                    'title'  => lang('Users.fullname'),
                    'field'     => 'fullname',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                [
                    'title'  => lang('Common.job_title'),
                    'field'     => 'job_title',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                [
                    'title'  => lang('Users.workplace'),
                    'field'     => 'branch',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                [
                    'title'  => lang('Users.role'),
                    'field'     => 'role',
                    'sortable'  => 'true',
                    'class'     => 'text-uppercase'
                ],
                [
                    'title'  => lang('Users.status'),
                    'field'     => 'status',
                    'sortable'  => 'true',
                    'formatter' => 'statusFormatterDefault'
                ],
            ],
            'url'       => route_to('user_account_list'),
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
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('user_account_form', 0), lang('Users.add_heading'));
        }
        $this->data['toolbar'] = view('table-toolbar/default', $this->data);
        return view('list', $this->data);
    }

    public function get_list()
    {
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['a.fullname'] = $getData['search'];
        $filter['a.employee_id'] = $getData['search'];
        $filter['d.name'] = $getData['search'];
        $filter['e.name'] = $getData['search'];
        $filter['c.name'] = $getData['search'];

        $table = new TableModel('users');
        if (isset($getData['sort'])) {
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }
        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.id, a.employee_id, a.fullname, d.name job_title, e.name branch, (CASE WHEN (a.active = 1) THEN  ( 'active') ELSE( 'nonactive' ) END) as status, c.name role, '" . route_to('user_account_detail', 'ID') . "' AS detail, '" . ($this->permEdit ? route_to('user_account_form', 'ID') : '') . "' AS `edit`, '" . ($this->permDelete ? route_to('user_account_delete', 'ID') : '') . "' AS `delete`");
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
            [
                'table' => 'job_titles d',
                'on'    => 'a.job_title_id =  d.id',
                'type'  => 'left'
            ]
        ]);
        $output['rows'] = $table->getAll();
        $output['total'] = $table->countAll();
        $table->setFilter();
        $output['totalNotFiltered'] = $table->countAll();
        echo json_encode($output);
    }

    public function detail($id)
    {
        if ($id) {
            $this->model->find($id);
            $employeeModels = new UsersModel();
            $employeeModels->select('users.*, b.name job_title, c.name branch');
            $employeeModels->join('job_titles b', 'b.id = users.job_title_id', 'left');
            $employeeModels->join('branches c', 'c.id = users.branch_id', 'left');
            $data = $employeeModels->where('users.id', $id)->first();

            $groupsModel = new GroupsModel();
            $groupsModel->select('auth_groups.id, auth_groups.name as role');
            $groupsModel->join('auth_groups_users b', 'b.group_id = auth_groups.id', 'left');
            $group = $groupsModel->where('b.user_id', $id)->first();
            if (!$data) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }

            $breadcrumb = $this->_setDefaultBreadcrumb();
            $breadcrumb->add(lang('Common.detail'), current_url());

            $this->data['detail'] = $data;
            $this->data['group'] = $group;
            $this->data['title'] = lang('Users.detail');
            $this->data['heading'] = lang('Users.detail');
            $this->data['breadcrumb'] = $breadcrumb->render();
            return view('Modules\Users\Views\User\detail', $this->data);
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
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
            $groupsModel->select('auth_groups.id, auth_groups.name as role');
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

        $formTab1 = [
            [
                'id'    => 'id',
                'type'  => 'hidden',
                'value' => ($data) ? $data->id : '',
            ],
            [
                'id'                    => 'employee_id',
                'value'                 => ($data) ? $data->employee_id : '',
                'label'                 => lang('Users.employee_id'),
                'required'              => 'required',
                'form_control_class'    => 'col-md-5'
            ],
            [
                'id'       => 'id_number',
                'value'    => ($data) ? $data->id_number : '',
                'label'    => lang('Users.id_number'),
                'required' => 'required',
            ],
            [
                'id'       => 'fullname',
                'value'    => ($data) ? $data->fullname : '',
                'label'    => lang('Users.fullname'),
                'required' => 'required',
            ],
            [
                'id'       => 'gender',
                'value'    => ($data) ? $data->gender : '',
                'label'    => lang('Users.gender'),
                'type'     => 'radio',
                'options'            => [
                    [
                        'label' => 'Male',
                        'value' => 'male',
                        'checked' => 'checked'
                    ],
                    [
                        'label' => 'Female',
                        'value' => 'female',
                    ]
                ],
                'form_control_class'    => 'pl-0 d-flex align-items-center',
            ],
            [
                'id'       => 'place_of_birth',
                'value'    => ($data) ? $data->place_of_birth : '',
                'label'    => lang('Users.place_of_birth'),
            ],
            [
                'id'       => 'date_of_birth',
                'value'    => ($data) ? $data->date_of_birth : '',
                'label'    => lang('Users.date_of_birth'),
                'type'     => 'date'
            ],
            [
                'id'       => 'blood_group',
                'value'    => ($data) ? $data->blood_group : '',
                'label'    => lang('Users.blood_group'),
                'type'     => 'radio',
                'options'            => [
                    [
                        'label' => 'A',
                        'value' => 'A',
                        'checked' => 'checked'
                    ],
                    [
                        'label' => 'B',
                        'value' => 'B',
                    ],
                    [
                        'label' => 'AB',
                        'value' => 'AB',
                    ],
                    [
                        'label' => 'O',
                        'value' => 'O',
                    ]
                ],
                'form_control_class'    => 'pl-0 d-flex align-items-center',
            ],
            [
                'id'       => 'email',
                'value'    => ($data) ? $data->email : '',
                'label'    => lang('Users.email'),
                'type'     => 'email',
                'required' => 'required',
            ],
            [
                'id'       => 'phone',
                'value'    => ($data) ? $data->phone : '',
                'label'    => lang('Users.phone'),
                'type'     => 'number',
                'required' => 'required',
            ],
            [
                'id'       => 'address',
                'value'    => ($data) ? $data->address : '',
                'label'    => lang('Users.address'),
                'type'     => 'textarea',
                'cols'     => '5',
                'rows'     => '2',
                'required' => 'required',
            ],
            [
                'id'       => 'image',
                'value'    => ($data) ? $data->image : '',
                'label'    => lang('Users.image'),
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

        $formTab2 = [
            [
                'id'        => 'job_title_id',
                'value'     => ($data) ? $data->job_title_id : '',
                'label'     => lang('Common.job_title'),
                'type'      => 'dropdown',
                'options'   => $this->_dropdownJobTitles(),
                'class'     => 'select2',
                'required'  => 'required',
            ],
            [
                'id'        => 'branch_id',
                'value'     => ($data) ? $data->branch_id : '',
                'label'     => lang('Common.branch'),
                'type'      => 'dropdown',
                'options'   => $this->_dropdownBranches(),
                'class'     => 'select2',
                'required'  => 'required',
            ],
            [
                'id'        => 'join_date',
                'value'     => ($data) ? $data->join_date : '',
                'label'     => lang('Users.join_date'),
                'type'      => 'date',
                'required'  => 'required',
            ],
            [
                'id'        => 'driving_license_number',
                'value'     => ($data) ? $data->driving_license_number : '',
                'label'     => lang('Users.driving_license_num'),
                'type'      => 'number',
            ],
            [
                'id'        => 'driving_license_exp',
                'value'     => ($data) ? $data->driving_license_expired : '',
                'label'     => lang('Users.driving_license_exp'),
                'type'      => 'date',
            ],
            [
                'type'                  => 'submit',
                'label'                 => lang('Common.btn.save_w_icon'),
                'back_url'              => $this->data['module_url'],
                'back_label'            => lang('Common.cancel'),
                'input_container_class' => 'form-group row text-right'
            ],
        ];

        $formTab3 = [
            [
                'id'        => 'username',
                'value'     => ($data) ? $data->username : '',
                'label'     => lang('Users.username'),
            ],
            [
                'id'        => 'password',
                'value'     => '',
                'label'     => lang('Users.password'),
                'type'      => 'password'
            ],
            [
                'id'    => 'group_id',
                'value' => ($group) ? $group->id : '',
                'label' => lang('Users.role'),
                'type'  => 'dropdown',
                'class' => 'select2',
                'options' => $this->_dropdownGroups(),
                'required'  => 'required',
            ],
            [
                'id'        => 'active',
                'value'     => ($data) ? $data->active : '',
                'label'     => lang('Users.status'),
                'type'      => 'dropdown',
                'class'     => 'select2',
                'options'   => [
                    '1' => 'Active',
                    '0' => 'Not Active'
                ],
            ],
            [
                'type'                  => 'submit',
                'label'                 => lang('Common.btn.save_w_icon'),
                'back_url'              => $this->data['module_url'],
                'back_label'            => lang('Common.cancel'),
                'input_container_class' => 'form-group row text-right'
            ],
        ];

        $form_builder = new FormBuilder();
        $this->data['form'] = [
            'action'   => route_to('user_account_save'),
            'form_tab' => true,
            'build'    => [
                [
                    'title' => lang('Users.personal'),
                    'icon'  => '<i class="ft-user"></i>',
                    'build' => $form_builder->build_form_horizontal($formTab1)
                ],
                [
                    'title' => lang('Users.work'),
                    'icon'  => '<i class="ft-briefcase"></i>',
                    'build' => $form_builder->build_form_horizontal($formTab2)
                ],
                [
                    'title' => lang('Users.account'),
                    'icon'  => '<i class="ft-lock"></i>',
                    'build' => $form_builder->build_form_horizontal($formTab3)
                ]
            ]
        ];

        $this->data['pluginCSS'] = [
            base_url('vendors/css/forms/icheck/icheck.css'),
        ];
        $this->data['pluginJS'] = [
            base_url('vendors/js/forms/icheck/icheck.min.js'),
        ];
        $this->data['customCSS'] = [
            base_url('vendors/css/forms/icheck/custom.css'),
        ];
        $this->data['customJS'] = [
            base_url('js/scripts/forms/checkbox-radio.min.js'),
        ];

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;
        $this->data['title'] = $data ? lang('Users.edit_heading') : lang('Users.add_heading');
        $this->data['heading'] = $this->data['title'];
        return view('form', $this->data);
    }

    public function _dropdownJobTitles()
    {
        $jobTitlesModel = new JobTitlesModel();
        $jobTitles = $jobTitlesModel->findAll();
        $dropdownJobTitles = ['' => ''];

        foreach ($jobTitles as $jobTitle) {
            $dropdownJobTitles[$jobTitle->id] = $jobTitle->name;
        }

        return $dropdownJobTitles;
    }

    public function _dropdownBranches()
    {
        $branchesModel = new BranchesModel();
        $branches = $branchesModel->findAll();
        $dropdownBranches = ['' => ''];

        foreach ($branches as $branch) {
            $dropdownBranches[$branch->id] = $branch->name;
        }

        return $dropdownBranches;
    }

    public function save()
    {
        $this->request->isAJAX() or exit();

        $rules = [
            'fullname'      => [
                'label' => lang('Users.fullname'),
                'rules' => 'required'
            ],
            'email'     => [
                'label' => lang('Users.email'),
                'rules' => 'required|valid_email'
            ],
            'username' => [
                'label' => lang('Users.username'),
                'rules' => 'required',
            ],
            'job_title_id' => [
                'label' => lang('Users.job_title_id'),
                'rules' => 'required',
            ],
            'branch_id' => [
                'label' => lang('Users.branch_id'),
                'rules' => 'required',
            ],
            'group_id' => [
                'label' => lang('Users.role'),
                'rules' => 'required',
            ],
            'join_date' => [
                'label' => lang('Users.join_date'),
                'rules' => 'required',
            ]
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
                    $upload = $cld->upload($image->getRealPath(), ['folder' => 'profile']);
                    $postData['image'] = $upload['secure_url'];
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

                $avatarName = str_replace(' ', '+', $this->request->getPost('fullname'));
                $cld = $cld = new Cloudinary(env('CLOUDINARY_URL'));
                $imageAvatar = 'https://ui-avatars.com/api/?background=random&name=' . $avatarName . '&rounded=true&size=300';
                $upload = $cld->uploadApi()->upload($imageAvatar, ['folder' => 'profile']);

                $postData['image'] = $upload['secure_url'];
                $postData['created_by'] = user_id();
                $this->model->insert($postData);
                $postData['id'] = $this->model->getInsertID();
            } else {
                $postData['updated_by'] = user_id();
                $this->model->update($postData['id'], $postData);
                $groupModel->removeUserFromAllGroups($postData['id']);
            }
            $groupModel->addUserToGroup($postData['id'], $postData['group_id']);
            $return = [
                'message'  => sprintf(lang('Common.saved.success'), lang('Common.user') . ' ' . $postData['fullname']),
                'status'   => 'success',
                'redirect' => route_to('user_accounts')
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
            $return = [
                'message' => sprintf(lang('Common.deleted.success'), lang('Common.user') . ' ' . $data->fullname),
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
        $groups = $groups->findAll();
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
        $breadcrumb->add(lang('Users.heading'), route_to('user_accounts'));

        return $breadcrumb;
    }
}
