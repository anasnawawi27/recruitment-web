<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $helpers = [];
    protected $session;

    public $data;

    public function __construct()
    {
        $this->helpers = array_merge($this->helpers, ['common']);
        $this->session = service('session');
        helper('auth');

        date_default_timezone_set('Asia/Jakarta');
    }

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();

        if (logged_in()) {
            $userModel = new \App\Models\UsersModel();
            if(in_groups('recruiter')){
                $user = $userModel->select('a.nama_lengkap, a.image, a.username, b.*')->from('users a')->join('pelamar b', 'a.id = b.id_user', 'left')->find(user_id());
                
                $this->data['user'] = $user;
                $this->session->set('id_pelamar', $user->id);
            } else {
                $this->data['user'] = $userModel->select('*')->find(user_id());
            }
        }
    }
}
