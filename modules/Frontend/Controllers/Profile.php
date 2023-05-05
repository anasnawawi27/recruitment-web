<?php

namespace Modules\Frontend\Controllers;
use \App\Controllers\BaseController;
use App\Models\ApplicantsModel;
use CodeIgniter\I18n\Time;
use Cloudinary\Api\Upload\UploadApi;

class Profile extends BaseController
{
    protected $model;

    public function __construct(){
        parent::__construct();
        $this->data['menu'] = 'profile';
        $this->data['module_url'] = route_to('profile');
        $this->model = new ApplicantsModel();
    }

    public function index(){
        $data = NULL;
        $breadcrumb = $this->_setDefaultBreadcrumb();
        $data = $this->model->find($this->session->get('id_pelamar'));
        if (!$data) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $userModel = new \App\Models\UsersModel();
        $userData = ($data) ? $userModel->find($data->id_user) : NULL;

        $profiles = [
            [
                'section' => 'Personal Info',
                'icon'    => 'ft-user',
                'rows'    => [
                    [
                        'label' => 'Nama Lengkap',
                        'data'  => $data->nama_lengkap
                    ],
                    [
                        'label' => 'Jenis Kelamin',
                        'data'  => ucwords($data->jenis_kelamin)
                    ],
                    [
                        'label' => 'Tanggal Lahir',
                        'data'  => $data->tanggal_lahir ? Time::parse($data->tanggal_lahir, 'Asia/Jakarta')->toLocalizedString('d MMMM, yyyy') : '-'
                    ],
                    [
                        'label' => 'Tempat Lahir',
                        'data'  => $data->tempat_lahir
                    ],
                    [
                        'label' => 'Email',
                        'data'  => $data->email
                    ],
                    [
                        'label' => 'No Handphone 1',
                        'data'  => $data->no_handphone_1 ?? '-'
                    ],
                    [
                        'label' => 'No Handphone 2',
                        'data'  => $data->no_handphone_2 ?? '-'
                    ],
                ],
            ],
            [
                'section' => 'Document',
                'icon'    => 'ft-file-text',
                'rows'    => [
                    [
                        'label' => 'ktp',
                        'data'  => $data->ktp
                    ],
                    [
                        'label' => 'Tanggal Vaksin 1',
                        'data'  => $data->tanggal_vaksin_1 ? Time::parse($data->tanggal_vaksin_1, 'Asia/Jakarta')->toLocalizedString('d MMMM, yyyy') : '-'
                    ],
                    [
                        'label' => 'File Vaksin 1',
                        'data'  => $data->file_vaksin_1
                    ],
                    [
                        'label' => 'Tanggal Vaksin 2',
                        'data'  => $data->tanggal_vaksin_2 ? Time::parse($data->tanggal_vaksin_2, 'Asia/Jakarta')->toLocalizedString('d MMMM, yyyy') : '-'
                    ],
                    [
                        'label' => 'File Vaksin 2',
                        'data'  => $data->file_vaksin_2
                    ],
                    [
                        'label' => 'Tanggal Vaksin 3',
                        'data'  => $data->tanggal_vaksin_3 ? Time::parse($data->tanggal_vaksin_3, 'Asia/Jakarta')->toLocalizedString('d MMMM, yyyy') : '-'
                    ],
                    [
                        'label' => 'File Vaksin 3',
                        'data'  => $data->file_vaksin_3
                    ],
                ],
            ],
            [
                'section' => 'Account',
                'icon'    => 'ft-user-check',
                'rows'    => [
                    [
                        'label' => 'Username',
                        'data'  => $userData ? $userData->username : ''
                    ],
                    [
                        'label' => 'Profile Picture',
                        'data'  =>$userData ? $userData->image : ''
                    ],
                ],
            ],
        ];

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['profiles'] = $profiles;
        $this->data['data'] = $data;
        $this->data['title'] = lang('Common.profile');
        $this->data['heading'] = $this->data['title'];
        return view('Modules\Frontend\Views\Profile\index', $this->data);
    }

    public function form($id){
        $data = NULL;
        $breadcrumb = $this->_setDefaultBreadcrumb();
        if ($id) {
            $data = $this->model->find($id);
            if (!$data) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $breadcrumb->add(lang('Common.edit'), current_url());
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
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

        $userModel = new \App\Models\UsersModel();
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

        $form_builder = new \App\Libraries\FormBuilder;
        $this->data['form'] = [
            'action'   => route_to('profile_save'),
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

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;
        $this->data['title'] = $data ? lang('Applicants.edit_heading') : lang('Applicants.add_heading');
        $this->data['heading'] = $this->data['title'];
        return view('Modules\Frontend\Views\Profile\form', $this->data);
    }

    public function save(){
        $this->request->isAJAX() or exit();

        $rules = [
            'nama_lengkap'      => [
                'label' => lang('Users.fullname'),
                'rules' => 'required'
            ],
        ];
        $return['status'] = 'error';

        do {
            $userModel = new \App\Models\UsersModel();
            $groupModel = new \Myth\Auth\Authorization\GroupModel();

            $postData = $this->request->getPost();

            $data = $userModel->find($postData['id_user']);
            $rules['username']['rules'] = 'is_unique[users.username,username,' . $data->username . ']';

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

            $userModel->update($postData['id_user'], $userData);
            $this->model->update($postData['id'], $postData);

            $groupModel->removeUserFromAllGroups($postData['id_user']);

            $db = db_connect();
            $builder = $db->table('auth_groups');
            $builder->select('*');
            $builder->where('name', 'applicant');
            $group =  $builder->get()->getFirstRow();

            $groupModel->addUserToGroup($postData['id_user'], $group->id);
            $return = [
                'message'  => sprintf(lang('Common.saved.success'), lang('Common.applicant') . ' ' . $postData['nama_lengkap']),
                'status'   => 'success',
                'redirect' => route_to('profile')
            ];
        } while (0);
        if (isset($return['redirect'])) {
            $this->session->setFlashdata('form_response_status', $return['status']);
            $this->session->setFlashdata('form_response_message', $return['message']);
        }
        echo json_encode($return);
    }

    private function _setDefaultBreadcrumb(){
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Common.profile'), route_to('profile'));

        return $breadcrumb;
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

}