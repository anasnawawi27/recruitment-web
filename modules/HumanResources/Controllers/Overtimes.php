<?php

namespace Modules\HumanResources\Controllers;

use \App\Controllers\BaseController;
use \App\Models\OvertimeModel;

class Overtimes extends BaseController
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
        $this->model = new OvertimeModel();
        $this->data['module'] = 'human_resource';
        $this->data['menu'] = 'overtime';
        $this->data['module_url'] = route_to('overtimes');
        $this->perm = 'overtime';
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
                    'title'  => lang('Overtimes.employee'),
                    'field'     => 'fullname',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                [
                    'title' => lang('Overtimes.date'),
                    'field' => 'date',
                    'sortable'  => 'true',
                    'switchable'   => 'false',
                    'formatter' => 'longDateFormatterDefault'
                ],
                [
                    'title' => lang('Overtimes.hours'),
                    'field' => 'hours',
                    'sortable'  => 'true',
                    'switchable'   => 'false',
                    'formatter' => 'hourFormatterDefault',
                ],
                [
                    'title' => lang('Overtimes.amount'),
                    'field' => 'amount',
                    'sortable'  => 'true',
                    'switchable'   => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
                [
                    'title' => lang('Overtimes.total_amount'),
                    'field' => 'total_amount',
                    'sortable'  => 'true',
                    'switchable'   => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
            ],
            'url'       => route_to('overtime_list'),
            'cookie_id' => 'table-overtime'
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
        $this->data['title'] = lang('Overtimes.heading');
        $this->data['heading'] = lang('Overtimes.heading');
        if ($this->permAdd) {
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('overtime_form', 0), lang('Overtimes.add_heading'));
        }
        $this->data['toolbar'] = view('table-toolbar/default', $this->data);
        return view('list', $this->data);
    }

    public function get_list()
    {
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['fullname'] = $getData['search'];
        $filter['date'] = $getData['search'];
        $table = new \App\Models\TableModel('overtime');
        if (isset($getData['sort'])) {
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }
        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.id, b.fullname, a.date, a.hours, a.amount, a.total_amount, '" . ($this->permEdit ? route_to('overtime_form', 'ID') : '') . "' AS `edit`, '" . ($this->permDelete ? route_to('overtime_delete', 'ID') : '') . "' AS `delete`");
        $table->setJoin([
            [
                'table' => 'users b',
                'on'    => 'b.id = a.user_id',
                'type'  => 'left'
            ],
        ]);
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
                'id'                 => 'user_id',
                'value'              => ($data) ? $data->user_id : '',
                'label'              => lang('Overtimes.employee'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'type'               => 'dropdown',
                'class'              => 'select2',
                'options'            => $this->_dropdownEmployees()
            ],
            [
                'id'                 => 'date',
                'value'              => ($data) ? $data->date : '',
                'label'              => lang('Overtimes.date'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'type'               => 'date',
            ],
            [
                'id'                 => 'start_time',
                'value'              => ($data) ? $data->start_time : '',
                'label'              => lang('Overtimes.start_time'),
                'form_control_class' => 'col-md-5',
                'type'               => 'time',
            ],
            [
                'id'                 => 'end_time',
                'value'              => ($data) ? $data->end_time : '',
                'label'              => lang('Overtimes.end_time'),
                'form_control_class' => 'col-md-5',
                'type'               => 'time',
            ],
            [
                'id'                 => 'amount',
                'value'              => ($data) ? $data->amount : '',
                'label'              => lang('Overtimes.amount'),
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
            'action' => route_to('overtime_save'),
            'build'  => $form_builder->build_form_horizontal($form),
        ];

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;
        $this->data['title'] = $data ? lang('Overtimes.edit_heading') : lang('Overtimes.add_heading');
        $this->data['heading'] = $this->data['title'];
        return view('form', $this->data);
    }


    public function save()
    {
        $this->request->isAJAX() or exit();

        $rules = [
            'user_id'      => [
                'label' => lang('Overtimes.employee'),
                'rules' => 'required'
            ],
            'date'      => [
                'label' => lang('Overtimes.date'),
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

            $startTime = strtotime($postData['start_time']);
            $endTime = strtotime($postData['end_time']);
            $diff = ($endTime - $startTime) / 3600;
            $postData['hours'] = $diff;
            $postData['total_amount'] = $diff * $postData['amount'];

            if (!$postData['id']) {
                $postData['created_by'] = user_id();
                $this->model->insert($postData);
            } else {
                $postData['updated_by'] = user_id();
                $this->model->update($postData['id'], $postData);
            }

            $return = [
                'message'  => sprintf(lang('Common.saved.success'), lang('Common.overtime') . ' ' . $postData['date']),
                'status'   => 'success',
                'redirect' => route_to('overtimes')
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
                'message' => sprintf(lang('Common.deleted.success'), lang('Common.overtime') . ' ' . $data->date),
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

    private function _dropdownEmployees()
    {
        $model = new \App\Models\UsersModel();
        $options =  ['' => ''];
        foreach ($model->findAll() as $data) {
            $options[$data->id] = $data->fullname;
        }
        return $options;
    }


    private function _setDefaultBreadcrumb()
    {
        $breadcrumb = new \App\Libraries\Breadcrumb();
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('Overtimes.heading'), route_to('overtimes'));

        return $breadcrumb;
    }
}
