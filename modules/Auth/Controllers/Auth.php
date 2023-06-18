<?php

namespace Modules\Auth\Controllers;

use \App\Controllers\BaseController;
use App\Models\UsersModel;
use Cloudinary\Api\Upload\UploadApi;
use Irsyadulibad\NIKValidator\Validator;

use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client;
use Myth\Auth\Password;

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

                $parsed = Validator::set($postData['nik'])->parse();
                if(!$parsed->valid) {
                    $return['message'] = alert_message('danger', 'NIK Tidak Valid');
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
                    'email' => $postData['email'],
                    'password_hash' => $this->_set_password($postData['password']),
                    'active' => 0,
                ];

                $this->model->insert($userData);
                $userId = $this->model->getInsertID();

                $db = db_connect();
                $group = $db->table('auth_groups')->where('name', 'applicant')->get()->getFirstRow();
                $db->table('auth_groups_users')->insert(['group_id' => $group->id, 'user_id' => $userId]);

                $postData['id_user'] = $userId;
                $applicantsModel = new \App\Models\ApplicantsModel();
                $applicantsModel->insert($postData);

                // send email
                $param = [
                    'nama_lengkap' => $postData['nama_lengkap'],
                    'username' => $postData['username'],
                    'password' => $userData['password_hash'],
                ];

                $config = new Configuration();
                $config->setApiKey('api-key', $_ENV['SENDINBLUE_KEY']);
                $apiInstance = new TransactionalEmailsApi(new Client(), $config);
                $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();

                $sendSmtpEmail['subject'] = 'Konfirmasi Email';
                $sendSmtpEmail['htmlContent'] = view('body_email_activation', $param);
                $sendSmtpEmail['sender'] = ['name' => 'PT Tekpak Indonesia', 'email' => 'tekpak.indonesia.test@gmail.com'];
                $sendSmtpEmail['to'] = [
                    ['email' => $postData['email'], 'name' => $postData['nama_lengkap']]
                ];

                try {
                    $apiInstance->sendTransacEmail($sendSmtpEmail);
                } catch (\Exception $e) {
                    $return = [
                        'message'  => $e->getMessage(),
                        'status'   => 'error',
                    ];
                    break;
                }

                $redirect = session('redirect_url') ?? route_to('confirmation_email', encode($userId));
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

    public function confirmation($encode){
        $encode or exit();

        $param = explode('tkpk', $encode);
        if(!$param) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $username = decode($param[0]);
        $password = decode($param[1]);

        $user = $this->model->where(['username' => $username, 'password_hash' => $password])->first();

        if(!$user) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        
        $this->model->update($user->id, ['active' => 1]);
        $ipAddress = \Config\Services::request()->getIPAddress();
        $loginModel = new \Myth\Auth\Models\LoginModel();

        $loginModel->insert([
            'ip_address' => $ipAddress,
            'username'   => $username,
            'user_id'    => $user->id,
            'date'       => date('Y-m-d H:i:s'),
            'success'    => 1,
        ]);

        $this->session->set('logged_in', $user->id);

        return view('activation_success');
    }

    public function confirmation_email($id){
        if(!$id) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $user = $this->model->find(decode($id));
        if(!$user) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        return view('confirmation_email');
    }

    public function resendActivation(){

        if ($this->request->getGet('login')) {
            $username = $this->request->getGet('login');
            $return = ['status' => 'error'];

            do {
                $user = $this->model->where('username', $username)->first();
                $param = [
                    'nama_lengkap' => $user->nama_lengkap,
                    'username' => $user->username,
                    'password' => $user->password_hash,
                ];

                $config = new Configuration();
                $config->setApiKey('api-key', $_ENV['SENDINBLUE_KEY']);
                $apiInstance = new TransactionalEmailsApi(new Client(), $config);
                $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();

                $sendSmtpEmail['subject'] = 'Konfirmasi Email';
                $sendSmtpEmail['htmlContent'] = view('body_email_activation', $param);
                $sendSmtpEmail['sender'] = ['name' => 'PT Tekpak Indonesia', 'email' => 'tekpak.indonesia.test@gmail.com'];
                $sendSmtpEmail['to'] = [
                    ['email' =>$user->email, 'name' => $user->nama_lengkap]
                ];

                try {
                    $apiInstance->sendTransacEmail($sendSmtpEmail);
                } catch (\Exception $e) {
                    $return = [
                        'message'  => $e->getMessage(),
                        'status'   => 'error',
                    ];
                    break;
                }

                $redirect = route_to('confirmation_email', encode($user->id));
                unset($_SESSION['redirect_url']);

                $return['status'] = 'success';
                $return['redirect'] = $redirect;
            } while (0);
            
            if($return['status'] == 'success'){
                return redirect()->to($redirect);
            }
        } else {
            $this->data['title'] = 'Login';
            return view('Modules\Auth\Views\login', $this->data);
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
