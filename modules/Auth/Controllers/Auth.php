<?php

namespace Modules\Auth\Controllers;

use \App\Controllers\BaseController;
use App\Models\UsersModel;
use Cloudinary\Api\Upload\UploadApi;

class Auth extends BaseController
{
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new UsersModel();
    }

    public function login()
    {
        if ($this->request->isAJAX()) {
            $data = $this->request->getPost();
            $return = ['status' => 'error'];
            $rules = [
                'username' => 'required',
                'password' => 'required',
            ];

            do {
                if (!$this->validate($rules)) {
                    $return['message'] = $this->validator->listErrors('bootstrap_list');
                    break;
                }
                $this->config = config('Auth');
                $this->auth = service('authentication');
                $remember = isset($data['remember']) ? true : false;
                
                if (!$this->auth->attempt([
                    'username' => $data['username'],
                    'password' => $data['password']
                ], $remember)) {
                    $return['message'] = alert_message('danger', $this->auth->error());
                    break;
                }

                $redirect = session('redirect_url') ?? (in_groups('hrd') ? route_to('dashboard') : route_to('home'));
                unset($_SESSION['redirect_url']);

                $return['status'] = 'success';
                $return['redirect'] = $redirect;
            } while (0);
            echo json_encode($return);
        } else {
            $this->data['title'] = 'Login';
            return view('Modules\Auth\Views\login', $this->data);
        }
    }

    public function register()
    {
        if ($this->request->isAJAX()) {
            $postData = $this->request->getPost();
            $rules = [
                'nama_lengkap'             => [
                    'rules'  => 'required',
                    'label'  => 'Nama Lengkap',
                    'errors' => [
                        'required' => 'Nama lengkap tidak boleh kosong'
                    ]
                ],
                'username'         => [
                    'rules'  => 'required',
                    'label'  => 'Username',
                    'errors' => [
                        'required' => 'Username tidak boleh kosong'
                    ]
                ],
                'password'         => [
                    'rules'  => 'required',
                    'label'  => 'Kata Sandi',
                    'errors' => [
                        'required' => 'Kata sandi tidak boleh kosong'
                    ]
                ],
                'konfirmasi_password' => [
                    'rules'  => 'required|matches[password]',
                    'label'  => 'Konfirmasi Kata Sandi',
                    'errors' => [
                        'required' => 'Konfirmasi kata sandi tidak boleh kosong'
                    ]
                ],
            ];
            $return = ['status' => 'error'];

            do {
                if (!$this->validate($rules)) {
                    $return['message'] = $this->validator->listErrors('bootstrap_list');
                    break;
                }

                //check username
                $usernameCheck = $this->model->where('username', $postData['username'])->find();
                if ($usernameCheck) {
                    $return['message'] = alert_message('danger', 'Username sudah terdaftar, silahkan masuk');
                    break;
                }

                $cld = new UploadApi();
                $imageID = NULL;
                $avatarName = str_replace(' ', '+', $postData['nama_lengkap']);
                if ($image = file_get_contents('https://ui-avatars.com/api/?background=random&name=' . $avatarName . '&rounded=true&size=300')) {
                    $base64 = 'data:image/png;base64,' . base64_encode($image);
                    if ($upload = $cld->upload($base64, ['folder' => 'pt-tekpak-indonesia/users'])) {
                        $imageID = $upload['public_id'];
                    }
                }

                $userData = [
                    'nama_lengkap' => $postData['nama_lengkap'],
                    'image' => $imageID,
                    'username' => $postData['username'],
                    'password_hash' => $this->_set_password($postData['password']),
                    'active' => 1,
                ];

                $this->model->insert($userData);
                $userId = $this->model->getInsertID();

                $db = db_connect();
                $group = $db->table('auth_groups')->where('name', 'applicant')->get()->getFirstRow();
                $db->table('auth_groups_users')->insert(['group_id' => $group->id, 'user_id' => $userId]);

                $postData['id_user'] = $userId;
                $applicantsModel = new \App\Models\ApplicantsModel();
                $applicantsModel->insert($postData);

                $ipAddress = \Config\Services::request()->getIPAddress();
                $loginModel = new \Myth\Auth\Models\LoginModel();

                $loginModel->insert([
                    'ip_address' => $ipAddress,
                    'username'   => $postData['username'],
                    'user_id'    => $userId,
                    'date'       => date('Y-m-d H:i:s'),
                    'success'    => 1,
                ]);

                $this->session->set('logged_in', $userId);
                $return['status'] = 'success';

                $redirect = session('redirect_url') ?? route_to('home');
                unset($_SESSION['redirect_url']);

                $return['status'] = 'success';
                $return['redirect'] = $redirect;
            } while (0);
            echo json_encode($return);
        } else {
            $this->data['title'] = 'Registrasi';
            return view('Modules\Auth\Views\register', $this->data);
        }
    }

    private function _set_password(string $password)
    {
        $config = config('Auth');
        $hashOptions = [
            'cost' => $config->hashCost,
        ];
        $setPasswordUser = password_hash(base64_encode(hash('sha384', $password, true)), $config->hashAlgorithm, $hashOptions);
        return $setPasswordUser;
    }
}
