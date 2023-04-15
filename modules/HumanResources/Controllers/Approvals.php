<?php

namespace Modules\HumanResources\Controllers;

use \App\Controllers\BaseController;
use \App\Models\LeavesModel;
use Exception;

class Approvals extends BaseController
{

    protected $data;
    protected $model;
    private $perm;
    private $permView;
    private $permApprove;
    private $permCancel;
    private $permDetail;

    public function __construct()
    {
        parent::__construct();
        $this->model = new LeavesModel();
        $this->data['module'] = 'human_resource';
        $this->data['menu'] = 'approval';
        $this->data['module_url'] = route_to('approvals');
        $this->perm = 'approvals';
        $this->permView = has_permission($this->perm);
        $this->permApprove = has_permission($this->perm . '/approve');
        $this->permCancel = has_permission($this->perm . '/cancel');
        $this->permDetail = has_permission($this->perm, '/detail');
    }

    public function index()
    {
        $this->permView or exit();
        $this->data['table'] = [
            'columns' => [
                [
                    'title'  => lang('Approvals.employee'),
                    'field'     => 'fullname',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'detailFormatterDefault'
                ],
                [
                    'title'  => lang('RequestLeaves.start_date'),
                    'field'     => 'start_date',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter'  => 'longDateFormatterDefault'
                ],
                [
                    'title'  => lang('RequestLeaves.end_date'),
                    'field'     => 'regional_wage',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter'  => 'longDateFormatterDefault'
                ],
                [
                    'title'  => lang('RequestLeaves.type'),
                    'field'     => 'type',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'class'  => 'text-uppercase font-weight-bold'
                ],
                [
                    'title'  => lang('RequestLeaves.status'),
                    'field'     => 'status',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter'  => 'statusFormatterDefault'
                ],
                [
                    'title'  => lang('RequestLeaves.document'),
                    'field'     => 'file',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter'  => 'documentFormatterDefault'
                ],
            ],
            'url'       => route_to('approval_list'),
            'cookie_id' => 'table-approval'
        ];
        if ($this->permApprove || $this->permCancel) {
            $this->data['table']['columns'][] = [
                'field'      => 'action',
                'class'      => 'w-100px nowrap',
                'align'      => 'center',
                'switchable' => 'false',
                'formatter'  => 'actionApprovalFormatterDefault',
                'events'     => 'actionApprovalEventDefault'
            ];
        }

        $breadcrumb = $this->_setDefaultBreadcrumb();
        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['title'] = lang('Approvals.heading');
        $this->data['heading'] = lang('Approvals.heading');
        if ($this->permView) {
            $this->data['left_toolbar'] = '<div class="d-flex"><input type="text" name="month" class="table-filter form-control mr-1" style="width: 200px;" placeholder="Select Month" autocomplete="off">';
            $this->data['left_toolbar'] .= '<div class="mr-1"><select name="user_id" class="table-filter select-employee select2 mr-1" style="width:200px;">' . $this->_dropdownEmployees() . '</select></div>';
            $this->data['left_toolbar'] .= '<select name="type" class="table-filter select-type mx-1 select2 ml-1" style="width:200px">' . $this->_dropdownTypes() . '</select></div>';
        }

        $this->data['pluginCSS'] = [
            base_url('vendors/js/bootstrap-datepicker/bootstrap-datepicker.min.css'),
        ];
        $this->data['pluginJS'] = [
            base_url('vendors/js/bootstrap-datepicker/bootstrap-datepicker.min.js'),
        ];

        $this->data['customJS'] = [
            base_url('js/modules/approvals.js'),
        ];

        $this->data['toolbar'] = view('table-toolbar/default', $this->data);
        return view('list', $this->data);
    }

    public function get_list()
    {
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['b.fullname'] = $getData['search'];
        $filter['a.start_date'] = $getData['search'];
        $filter['a.end_date'] = $getData['search'];
        $filter['a.type'] = $getData['search'];
        $filterAdditional = [];

        $table = new \App\Models\TableModel('leaves');
        if (isset($getData['sort'])) {
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }

        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.id, b.fullname, a.start_date, a.end_date, a.type, a.status, a.file, '" . route_to('approval_detail', 'ID') . "' AS detail, IF(a.status = 'draft', '" . ($this->permCancel ? route_to('approval_update', 'ID') : '') . "', '') AS `cancel`, IF(a.status = 'draft', '" . ($this->permApprove ? route_to('approval_update', 'ID') : '') . "', '') AS `approve`");
        $table->setJoin([
            [
                'table' => 'users b',
                'on'    => 'b.id = a.user_id',
                'type'  => 'left'
            ]
        ]);

        if ($getData['filter']['user_id']) {
            if ($getData['filter']['user_id'] !== 'all') {
                $filterAdditional[] = [
                    'function' => 'where',
                    'column'   => 'a.user_id',
                    'value'    => $getData['filter']['user_id']
                ];
            }
        }

        if ($getData['filter']['type']) {
            if ($getData['filter']['type'] !== 'all') {
                $filterAdditional[] = [
                    'function' => 'where',
                    'column'   => 'a.type',
                    'value'    => $getData['filter']['type']
                ];
            }
        }

        if ($getData['filter']['month']) {
            $month = explode('-', $getData['filter']['month']);
            $filterAdditional[] = [
                'function' => 'where',
                'column'   => 'MONTH(a.start_date)',
                'value'    => $month[1],
            ];
            $filterAdditional[] = [
                'function' => 'where',
                'column'   => 'YEAR(a.start_date)',
                'value'    => $month[0],
            ];
        }

        $table->orderBy('a.id', 'desc');
        $table->setFilter($filter);
        $table->setFilterAdditional($filterAdditional);
        $output['rows'] = $table->getAll();
        $output['total'] = $table->countAll();
        $output['totalNotFiltered'] = $table->countAll();
        echo json_encode($output);
    }

    public function update($id)
    {
        $this->request->isAJAX() or exit();
        $this->permApprove or exit();
        $this->permCancel or exit();

        $type = $this->request->getPost('type');
        $where = $type == 'cancel' ? 'cancelled' : 'approved';
        $message = $type == 'cancel' ? lang('Common.approve.cancel') : lang('Common.approve.success');

        $data = $this->model->find($id);
        if ($data) {
            $this->model->update($id, ['status' => $where]);
            $return = [
                'message' => sprintf($message, lang('Common.leave')),
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

    public function detail($id)
    {
        $this->permDetail or exit();

        $this->model->select('leaves.*, a.employee_id, a.fullname');
        $this->model->join('users a', 'a.id = leaves.user_id', 'left');
        $data = $this->model->where(['leaves.id' => $id])->first();

        if ($data) {
            $this->data['heading'] = lang('Approvals.detail_heading');
            $this->data['title'] = lang('Approvals.detail_heading');
            $this->data['data'] = $data;

            $this->data['customJS'] = [
                base_url('js/modules/approvals-detail.js'),
            ];
            return view('Modules\HumanResources\Views\approval_detail', $this->data);
        } else {
            throw \Codeigniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    private function _dropdownEmployees()
    {
        $model = new \App\Models\UsersModel();
        $options = '<option value=""></option><option value="all">View All</option>';
        foreach ($model->findAll() as $data) {
            $options .= '<option value="' . $data->id . '">' . $data->fullname . '</option>';
        }
        return $options;
    }

    private function _dropdownTypes()
    {
        $option = '<option value=""></option>
                <option value="all">View All</option>                                
                <option value="cuti">Cuti</option>                                
                <option value="sakit">Sakit</option>                                
                <option value="izin">Izin</option>';
        return $option;
    }

    private function _setDefaultBreadcrumb()
    {
        $breadcrumb = new \App\Libraries\Breadcrumb();
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Approvals.heading'), route_to('approvals'));

        return $breadcrumb;
    }
}
