<?php

namespace Modules\Admin\Controllers;

use \App\Controllers\BaseController;
use \App\Models\ApplicantsModel;
use \App\Models\UsersModel;
use \App\Models\TableModel;
use \App\Libraries\FormBuilder;
use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;
use Myth\Auth\Models\UserModel;
use Irsyadulibad\NIKValidator\Validator;

class Applicants extends BaseController
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
        $this->model = new ApplicantsModel();
        $this->data['module'] = 'administration';
        $this->data['menu'] = 'applicant';
        $this->data['module_url'] = route_to('applicants');
        $this->perm = 'applicant';
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
                    'title'  => lang('Applicants.fullname'),
                    'field'     => 'nama_lengkap',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'detailFormatterDefault',
                ],
                // [
                //     'title'  => lang('Applicants.gender'),
                //     'field'     => 'jenis_kelamin',
                //     'sortable'  => 'true',
                //     'switchable' => 'false'
                // ],
                [
                    'title'  => lang('Applicants.birthdate'),
                    'field'     => 'tanggal_lahir',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter'     => 'longDateFormatterDefault'
                ],
                [
                    'title'  => lang('Applicants.birthplace'),
                    'field'     => 'tempat_lahir',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                [
                    'title'  => lang('Users.nik'),
                    'field'     => 'nik',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                [
                    'title'  => lang('Users.email'),
                    'field'     => 'email',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                // [
                //     'title'  => lang('Applicants.phone_1'),
                //     'field'     => 'no_handphone_1',
                //     'sortable'  => 'true',
                //     'switchable' => 'false'
                // ],
                // [
                //     'title'  => lang('Applicants.phone_2'),
                //     'field'     => 'no_handphone_2',
                //     'sortable'  => 'true',
                //     'switchable' => 'false'
                // ],
            ],
            'url'       => route_to('applicant_list'),
            'cookie_id' => 'table-applicant'
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
        $this->data['title'] = lang('Applicants.heading');
        $this->data['heading'] = lang('Applicants.heading');
        if ($this->permAdd) {
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('applicant_form', 0), lang('Applicants.add_heading'));
        }
        $this->data['toolbar'] = view('table-toolbar/default', $this->data);
        return view('list', $this->data);
    }

    public function get_list()
    {
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['a.nama_lengkap'] = $getData['search'];
        $filter['a.jenis_kelamin'] = $getData['search'];
        $filter['a.tanggal_lahir'] = $getData['search'];
        $filter['a.tempat_lahir'] = $getData['search'];

        $table = new TableModel('pelamar');
        if (isset($getData['sort'])) {
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }
        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.id, a.nama_lengkap, a.jenis_kelamin, a.tanggal_lahir, a.tempat_lahir, a.no_handphone_1, a.no_handphone_2, a.email, a.nik, '" . route_to('applicant_detail', 'ID') . "' AS detail, '" . ($this->permEdit ? route_to('applicant_form', 'ID') : '') . "' AS `edit`, '" . ($this->permDelete ? route_to('applicant_delete', 'ID') : '') . "' AS `delete`");
        $output['rows'] = $table->getAll();
        $output['total'] = $table->countAll();
        $table->setFilter();
        $output['totalNotFiltered'] = $table->countAll();
        echo json_encode($output);
    }

    public function detail($id)
    {
        if ($id) {

            $data = $this->model->find($id);;

            if (!$data) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $usersModel = new UsersModel();
            $usersModel->select('users.*, c.description role');
            $usersModel->join('auth_groups_users b', 'b.user_id = users.id', 'left');
            $usersModel->join('auth_groups c', 'c.id = b.group_id', 'left');

            $user = $usersModel->where('users.id', $data->id_user)->first();

            $breadcrumb = $this->_setDefaultBreadcrumb();
            $breadcrumb->add(lang('Common.detail'), current_url());

            $this->data['detail'] = $data;
            $this->data['account'] = $user;
            $this->data['title'] = lang('Applicants.detail_heading');
            $this->data['heading'] = lang('Applicants.detail_heading');
            $this->data['breadcrumb'] = $breadcrumb->render();
            return view('Modules\Admin\Views\Applicants\detail', $this->data);
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
                'id'                    => 'nama_lengkap',
                'value'                 => ($data) ? $data->nama_lengkap : '',
                'label'                 => lang('Applicants.fullname'),
                'required'              => 'required',
                'form_control_class'    => 'col-md-5 col-sm-8'
            ],
            [
                'id'       => 'jenis_kelamin',
                'value'    => ($data) ? $data->jenis_kelamin : '',
                'label'    => lang('Applicants.gender'),
                'type'     => 'radio',
                'options'            => [
                    [
                        'label' => 'Laki-Laki',
                        'value' => 'laki-laki',
                        'checked' => 'checked'
                    ],
                    [
                        'label' => 'Perempuan',
                        'value' => 'perempuan',
                    ]
                ],
                'form_control_class'    => 'col-md-5 col-sm-8',
            ],
            [
                'id'       => 'tempat_lahir',
                'value'    => ($data) ? $data->tempat_lahir : '',
                'label'    => lang('Applicants.birthplace'),
            ],
            [
                'id'       => 'tanggal_lahir',
                'value'    => ($data) ? $data->tanggal_lahir : '',
                'label'    => lang('Applicants.birthdate'),
                'type'     => 'date'
            ],
            [
                'id'       => 'nik',
                'value'    => ($data) ? $data->nik : '',
                'label'    => lang('Users.nik'),
                'type'     => 'number',
                'required' => 'required',
            ],
            [
                'id'       => 'email',
                'value'    => ($data) ? $data->email : '',
                'label'    => lang('Applicants.email'),
                'type'     => 'email',
                'required' => 'required',
            ],
            [
                'id'       => 'no_handphone_1',
                'value'    => ($data) ? $data->no_handphone_1 : '',
                'label'    => lang('Applicants.phone_1'),
                'type'     => 'number',
                'required' => 'required',
            ],
            [
                'id'       => 'no_handphone_2',
                'value'    => ($data) ? $data->no_handphone_2 : '',
                'label'    => lang('Applicants.phone_2'),
                'type'     => 'number',
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
                'id'       => 'ktp',
                'value'    => ($data) ? $data->ktp : '',
                'label'    => lang('Applicants.ktp'),
                'type'     => 'custom_image',
            ],
            [
                'id'       => 'file_vaksin_1',
                'value'    => ($data) ? $data->file_vaksin_1 : '',
                'label'    => lang('Applicants.vaksin_1'),
                'type'     => 'files',
            ],
            [
                'id'        => 'tanggal_vaksin_1',
                'value'     => ($data) ? $data->tanggal_vaksin_1 : '',
                'label'     => lang('Applicants.vaksin_date_1'),
                'type'      => 'date',
            ],
            [
                'id'       => 'file_vaksin_2',
                'value'    => ($data) ? $data->file_vaksin_2 : '',
                'label'    => lang('Applicants.vaksin_2'),
                'type'     => 'files',
            ],
            [
                'id'        => 'tanggal_vaksin_2',
                'value'     => ($data) ? $data->tanggal_vaksin_2 : '',
                'label'     => lang('Applicants.vaksin_date_2'),
                'type'      => 'date',
            ],
            [
                'id'       => 'file_vaksin_3',
                'value'    => ($data) ? $data->file_vaksin_3 : '',
                'label'    => lang('Applicants.vaksin_3'),
                'type'     => 'files',
            ],
            [
                'id'        => 'tanggal_vaksin_3',
                'value'     => ($data) ? $data->tanggal_vaksin_3 : '',
                'label'     => lang('Applicants.vaksin_date_3'),
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

        $userModel = new UsersModel();
        $userData = ($data) ? $userModel->find($data->id_user) : NULL;
        
        $formTab3 = [
            [
                'id'        => 'id_user',
                'value'     => ($data) ? $data->id_user : '',
                'type'      => 'hidden',
            ],
            [
                'id'        => 'username',
                'value'     => ($userData) ? $userData->username : '',
                'label'     => lang('Users.username'),
            ],
            [
                'id'        => 'password',
                'value'     => '',
                'label'     => lang('Users.password'),
                'type'      => 'password'
            ],
            [
                'id'        => 'active',
                'value'     => ($userData) ?$userData->active : '',
                'label'     => lang('Users.status'),
                'type'      => 'dropdown',
                'class'     => 'select2',
                'options'   => [
                    '1' => 'Active',
                    '0' => 'Not Active'
                ],
            ],
            [
                'id'       => 'image',
                'value'    => ($userData) ? $userData->image : '',
                'label'    => lang('Users.image'),
                'type'     => 'custom_image',
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
            'action'   => route_to('applicant_save'),
            'form_tab' => true,
            'build'    => [
                [
                    'title' => lang('Applicants.personal'),
                    'icon'  => '<i class="ft-user"></i>',
                    'build' => $form_builder->build_form_horizontal($formTab1)
                ],
                [
                    'title' => lang('Applicants.document'),
                    'icon'  => '<i class="ft-file-text"></i>',
                    'build' => $form_builder->build_form_horizontal($formTab2)
                ],
                [
                    'title' => lang('Applicants.account'),
                    'icon'  => '<i class="ft-lock"></i>',
                    'build' => $form_builder->build_form_horizontal($formTab3)
                ]
            ]
        ];

        // $this->data['pluginCSS'] = [
        //     base_url('vendors/css/forms/icheck/icheck.css'),
        // ];
        // $this->data['pluginJS'] = [
        //     base_url('vendors/js/forms/icheck/icheck.min.js'),
        // ];
        // $this->data['customCSS'] = [
        //     base_url('vendors/css/forms/icheck/custom.css'),
        // ];
        // $this->data['customJS'] = [
        //     base_url('js/scripts/forms/checkbox-radio.min.js'),
        // ];

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;
        $this->data['title'] = $data ? lang('Applicants.edit_heading') : lang('Applicants.add_heading');
        $this->data['heading'] = $this->data['title'];
        return view('form', $this->data);
    }

    public function save()
    {
        $this->request->isAJAX() or exit();

        $rules = [
            'nama_lengkap'      => [
                'label' => lang('Users.fullname'),
                'rules' => 'required'
            ],
        ];
        $return['status'] = 'error';

        do {
            $userModel = new UsersModel();
            $groupModel = new \Myth\Auth\Authorization\GroupModel();

            $postData = $this->request->getPost();

            $parsed = Validator::set($postData['nik'])->parse();
            if(!$parsed->valid) {
                $return['message'] = 'NIK Tidak Valid';
                break;
            }

            if (!$postData['id']) {
                $rules['password'] = [
                    'label' => lang('Users.password'),
                    'rules' => 'required|strong_password|min_length[8]'
                ];
                $sameData = $userModel->where('username', $postData['username'])->find();
                if ($sameData) {
                    $rules['username']['label'] = lang('Users.username');
                    $rules['username']['rules'] = 'is_unique[users.username]';
                }
            } else {
                $data = $userModel->find($postData['id_user']);
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

            $userData['nama_lengkap'] = $postData['nama_lengkap'];
            $userData['username'] = $postData['username'];
            $userData['email'] = $postData['email'];
            $userData['active'] = $postData['active']; 

            if ($postData['password']) {
                $userData['password_hash'] = $this->_set_password($postData['password']);
            }

            $keys = ['ktp', 'file_vaksin_1', 'file_vaksin_2', 'file_vaksin_3', 'image'];
            foreach($keys as $key){
                $cld = new UploadApi();
                $file = $this->request->getFile($key);
                if ($file) {
                    if ($file->isValid()) {
                        if($key == 'image'){
                            $upload = $cld->upload($file->getRealPath(), ['folder' => 'pt-tekpak-indonesia/users/' . $key]);
                            $userData[$key] = $upload['public_id'];
                        } else {
                            $upload = $cld->upload($file->getRealPath(), ['folder' => 'pt-tekpak-indonesia/' . $key]);
                            $postData[$key] = $upload['public_id'];
                        }
                    }
                } else {
                    if($key == 'image'){
                        if (isset($postData['id'])) {
                            if ($data = $userModel->find($postData['id_user'])) {
                                $userData['image'] = $data->image;
                                if ($data->image) {
                                    if (isset($postData['delete_image_image']) && $postData['delete_image_image'] === '1') {
                                        $cld->destroy($data->image);
                                        $userData['image'] = NULL;
                                    }
                                }
                            }
                        }
                    } else {
                        if (isset($postData['id'])) {
                            if ($data = (array) $this->model->find($postData['id'])) {
                                $postData[$key] = $data[$key];
                                if ($data[$key]) {
                                    if (isset($postData['delete_image_' .  $key]) && $postData['delete_image_' . $key] === '1') {
                                        $cld->destroy($data[$key]);
                                        $postData[$key] = NULL;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $image = $this->request->getFile('image');

            if (!$postData['id']) {
                if(!$image){
                    $avatarName = str_replace(' ', '+',  $this->request->getPost('nama_lengkap'));
                    $cld = $cld = new Cloudinary(env('CLOUDINARY_URL'));
                    $imageAvatar = 'https://ui-avatars.com/api/?background=random&name=' . $avatarName . '&rounded=true&size=300';
                    $upload = $cld->uploadApi()->upload($imageAvatar, ['folder' => 'pt-tekpak-indonesia/users']);
    
                    $userData['image'] = $upload['public_id'];
                }

                $userModel->insert($userData);
                $postData['id_user'] = $userModel->getInsertID();

                $this->model->insert($postData);
                $postData['id'] = $this->model->getInsertID();
            } else {
                
                $userModel->update($postData['id_user'], $userData);
                $this->model->update($postData['id'], $postData);

                $groupModel->removeUserFromAllGroups($postData['id_user']);
            }
            $db = db_connect();
            $builder = $db->table('auth_groups');
            $builder->select('*');
            $builder->where('name', 'applicant');
            $group =  $builder->get()->getFirstRow();

            $groupModel->addUserToGroup($postData['id_user'], $group->id);
            $return = [
                'message'  => sprintf(lang('Common.saved.success'), lang('Common.applicant') . ' ' . $postData['nama_lengkap']),
                'status'   => 'success',
                'redirect' => route_to('applicants')
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
            $cld = new UploadApi();
            $userModel = new UserModel();
            $user = $userModel->find($data->id_user);

            if($user){
                if($user->image){
                    $cld->destroy($user->image);
                }
                $userModel->delete($data->id_user);
            }
            
            if($data->ktp){
                $cld->destroy($data->ktp);
            }
            if($data->file_vaksin_1){
                $cld->destroy($data->file_vaksin_1);
            }
            if($data->file_vaksin_2){
                $cld->destroy($data->file_vaksin_2);
            }
            if($data->file_vaksin_3){
                $cld->destroy($data->file_vaksin_2);
            }

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
        $breadcrumb->add(lang('Applicants.heading'), route_to('applicants'));

        return $breadcrumb;
    }
}
