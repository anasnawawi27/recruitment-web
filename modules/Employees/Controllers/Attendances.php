<?php

namespace Modules\Employees\Controllers;

use \App\Controllers\BaseController;
use \App\Models\AttendancesModel;
use CodeIgniter\I18n\Time;

class Attendances extends BaseController
{
    protected $data;
    protected $model;
    private $perm;
    private $permView;
    private $permAdd;
    private $permDelete;

    public function __construct()
    {
        parent::__construct();
        $this->perm = 'attendance';
        $this->data['module'] = 'employee';
        $this->data['menu'] = 'attendance';
        $this->data['module_url'] = route_to('live_attendance');
        $this->model = new AttendancesModel();
        $this->permView = has_permission($this->perm);
        $this->permAdd = has_permission($this->perm . '/add');
        $this->permEdit = has_permission($this->perm . '/edit');
        $this->permDelete = has_permission($this->perm . '/delete');
    }

    public function index()
    {
        $this->permView or exit();

        $breadcrumb = $this->_setDefaultBreadcrumb();
        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['title'] = lang('Attendances.heading');
        $this->data['heading'] = lang('Attendances.heading');
        $this->data['pluginCSS'] = [
            'https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css',
            'https://unpkg.com/leaflet@1.8.0/dist/leaflet.css'
        ];
        $this->data['pluginJS'] = [
            // "http://maps.google.com/maps/api/js?sensor=false&libraries=geometry",
            "https://maps.googleapis.com/maps/api/js?key=AIzaSyAzdWYBLD_dcAifJb5fk4B_vz0Dtynr1ug&sensor=false&libraries=geometry",
            'https://unpkg.com/leaflet@1.8.0/dist/leaflet.js',
            'https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js'
        ];
        $this->data['customJS'] = [
            base_url('js/modules/live-attendance.js'),
        ];
        return view('\Modules\Employees\Views\live_attendance', $this->data);
    }

    public function save()
    {
        $this->request->isAJAX() or exit();

        $rules = [
            'latitude'      => [
                'label' => lang('Attendances.latitude'),
                'rules' => 'required'
            ],
            'longitude'     => [
                'label' => lang('Attendances.longitude'),
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

            $postData['date'] = date('Y-m-d');
            $postData['user_id'] = user_id();
            $type = $postData['type'];
            $postData['latitude_' . $type] = $postData['latitude'];
            $postData['longitude_' . $type] = $postData['longitude'];

            $validate = $this->model->where(['user_id' => user_id(), 'date' => $postData['date'], 'clock_' . $type . ' IS NOT NULL' => NULL])->find();
            if ($validate) {
                $return['message'] = 'You already Clock ' . ucwords($type) . ' Today';
                break;
            }

            $attendance = $this->model->where(['user_id' => user_id(), 'date' => $postData['date']])->find();
            $current = new Time('now', 'Asia/Jakarta', 'id_ID');

            if (!$attendance) {
                $postData['created_by'] = $postData['user_id'];
                $postData['clock_in'] = $current->format('H:i:s');;

                if ((int) substr($postData['clock_in'], 0, 2) > (int) '08') {
                    $postData['status'] = 'coming late';
                } else {
                    $postData['status'] = 'on time';
                }

                $this->model->insert($postData);
            } else {
                $postData['updated_by'] = $postData['user_id'];
                $postData['clock_out'] = $current->format('H:i:s');

                $this->model->where(['user_id' => $postData['user_id'], 'date' => $postData['date']])->set($postData)->update();
            }

            $return = [
                'message'  => sprintf(lang('Common.saved.success'), lang('Attendances.heading') . ' ' . $postData['date']),
                'status'   => 'success',
            ];
        } while (0);

        echo json_encode($return);
    }

    public function validateAttendance()
    {
        $this->request->isAJAX() or exit();

        $attendance = $this->model->where(['user_id' => user_id(), 'date' => date('Y-m-d')])->first();

        if ($attendance) {
            $return['status'] = 'success';
            $return['status_present'] = $attendance->status;
            $return['clock_in'] = false;
            $return['clock_out'] = false;
            $return['in_time'] = $attendance->clock_in;
            $return['out_time'] = $attendance->clock_out;

            if ($attendance->clock_in) {
                $return['clock_in'] = true;
            }

            if ($attendance->clock_out) {
                $return['clock_out'] = true;
            }
        } else {
            $return['status'] = 'error';
            $return['clock_in'] = false;
            $return['clock_out'] = false;
        }

        echo json_encode($return);
    }

    private function _setDefaultBreadcrumb()
    {
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Attendances.heading'), route_to('live_attendance'));

        return $breadcrumb;
    }
};
