<?php

namespace Modules\HumanResources\Controllers;

use \App\Controllers\BaseController;
use \App\Models\BranchesModel;

class Branches extends BaseController
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
        $this->model = new BranchesModel();
        $this->data['module'] = 'human_resource';
        $this->data['menu'] = 'branch';
        $this->data['module_url'] = route_to('branches');
        $this->perm = 'branch';
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
                    'title'  => lang('Branches.name'),
                    'field'     => 'name',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                ],
                [
                    'title'  => lang('Branches.address'),
                    'field'     => 'address',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'width'      => '550px'
                ],
                [
                    'title'  => lang('Branches.wage'),
                    'field'     => 'regional_wage',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter'  => 'currencyFormatterDefault'
                ],
            ],
            'url'       => route_to('branch_list'),
            'cookie_id' => 'table-branch'
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
        $this->data['title'] = lang('Branches.heading');
        $this->data['heading'] = lang('Branches.heading');
        if ($this->permAdd) {
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('branch_form', 0), lang('Branches.add_heading'));
        }

        $this->data['toolbar'] = view('table-toolbar/default', $this->data);
        return view('list', $this->data);
    }

    public function get_list()
    {
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['a.name'] = $getData['search'];
        $filter['a.address'] = $getData['search'];
        $filter['a.regional_wage'] = $getData['search'];

        $table = new \App\Models\TableModel('branches');
        if (isset($getData['sort'])) {
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }

        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.id, a.name, a.address, a.regional_wage, '" . ($this->permEdit ? route_to('branch_form', 'ID') : '') . "' AS `edit`, '" . ($this->permDelete ? route_to('branch_delete', 'ID') : '') . "' AS `delete`");
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
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $breadcrumb->add(lang('Common.edit'), current_url());
        } else {
            $this->permAdd or exit();
            $breadcrumb->add(lang('Common.add'), current_url());
        }

        $form = [
            [
                'id'    => 'id',
                'type'  => 'hidden',
                'value' => ($data) ? $data->id : '',
            ],
            [
                'id'                 => 'name',
                'value'              => ($data) ? $data->name : '',
                'label'              => lang('Branches.name'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
            ],
            [
                'id' => '',
                'type'  => 'html',
                'label' => lang('Branches.location'),
                'html'  => $this->_html_location($data)
            ],
            [
                'id'                 => 'address',
                'value'              => ($data) ? $data->address : '',
                'label'              => lang('Branches.address'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'type'               => 'textarea',
                'cols'               => 3,
                'rows'               => 3
            ],
            [
                'id'                 => 'regional_wage',
                'value'              => ($data) ? $data->regional_wage : '',
                'label'              => lang('Branches.wage'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'class'              => 'number',
                'input_addons'       => [
                    'pre' => 'Rp'
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

        $form_builder = new \App\Libraries\FormBuilder();
        $this->data['form'] = [
            'action' => route_to('branch_save'),
            'build'  => $form_builder->build_form_horizontal($form),
        ];

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;
        $this->data['title'] = $data ? lang('Branches.edit_heading') : lang('Branches.add_heading');
        $this->data['heading'] = $this->data['title'];
        $this->data['pluginCSS'] = [
            'https://unpkg.com/leaflet@1.8.0/dist/leaflet.css'
        ];
        $this->data['pluginJS'] = [
            // 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCDeFffmCAPmTP0iDDv6cYd1Vc41v24cOg&libraries=places,geometry',
            // 'https://maps.google.com/maps/api/js?sensor=false&libraries=geometry',
            "https://maps.googleapis.com/maps/api/js?key=AIzaSyAzdWYBLD_dcAifJb5fk4B_vz0Dtynr1ug&libraries=places,geometry",
            'https://unpkg.com/leaflet@1.8.0/dist/leaflet.js'
        ];
        $this->data['customJS'] = [
            base_url('js/modules/branches.js')
        ];
        return view('form', $this->data);
    }


    public function save()
    {
        $this->request->isAJAX() or exit();

        $rules = [
            'name'      => [
                'label' => lang('Branches.name'),
                'rules' => 'required'
            ],
            'address'      => [
                'label' => lang('Branches.address'),
                'rules' => 'required'
            ],
            'latitude'      => [
                'label' => lang('Branches.location'),
                'rules' => 'required'
            ],
            'longitude'      => [
                'label' => lang('Branches.location'),
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

            if (!$postData['id']) {
                $postData['created_by'] = user_id();
                $this->model->insert($postData);
                $postData['id'] = $this->model->getInsertID();
            } else {
                $postData['updated_by'] = user_id();
                $this->model->update($postData['id'], $postData);
            }

            $return = [
                'message'  => sprintf(lang('Common.saved.success'), lang('Common.branch') . ' ' . $postData['name']),
                'status'   => 'success',
                'redirect' => route_to('branches')
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
                'message' => sprintf(lang('Common.deleted.success'), lang('Common.branch') . ' ' . $data->name),
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

    private function _html_location($data = NULL)
    {

        $inputLocation = '<div class="set-map-location d-flex justify-content-between align-items-center"><button type="button" class="btn btn-sm btn-outline btn-info choose-location">
                            <i class="la la-map-o"></i> Set Location
                        </button>
                        <input type="hidden" id="latitude" class="form-control" name="latitude" value="' . ($data && $data->latitude ? $data->latitude : '') . '">
                        <input type="hidden" id="longitude" class="form-control" name="longitude" value="' . ($data && $data->longitude ? $data->longitude : '') . '">' .
            ($data && $data->latitude && $data->longitude ? '<div><small class="text-muted">Lat : <span id="lat-display">' . $data->latitude . '</span></small> &bull; <small class="text-muted">Long : <span id="lng-display">' . $data->longitude . '</span></small></div>' : '')
            . '</div>';

        if ($data && $data->latitude && $data->longitude) {
            $html = '<div id="display-map" style="height: 216px; padding: .4375rem; border: 1px solid #ddd; margin-bottom:10px; border-radius:3px" data-lat="' . $data->latitude . '" data-long="' . $data->longitude . '"></div>';
            $html .= $inputLocation;
        } else {
            $html = $inputLocation;
        }

        return $html;
    }

    public function pointForm()
    {
        $this->request->isAJAX() or exit;
        return view('Modules\HumanResources\Views\point_form', $this->data);
    }


    private function _setDefaultBreadcrumb()
    {
        $breadcrumb = new \App\Libraries\Breadcrumb();
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Branches.heading'), route_to('branches'));

        return $breadcrumb;
    }
}
