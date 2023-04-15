<?php

namespace Modules\Finance\Controllers;

use \App\Controllers\BaseController;
use \App\Models\JobTitlesModel;
use \App\Models\AllowancesModel;
use \App\Models\DeductionsModel;

class PayrollComponents extends BaseController
{

    protected $data;
    private $jobTitlesModel;
    private $allowancesModel;
    private $deductionsModel;
    private $perm;
    private $permView;
    private $permAdd;
    private $permDelete;

    public function __construct()
    {
        parent::__construct();
        $this->jobTitlesModel = new JobTitlesModel();
        $this->allowancesModel = new AllowancesModel();
        $this->deductionsModel = new DeductionsModel();
        $this->data['module'] = 'finance';
        $this->data['menu'] = 'payroll_component';
        $this->data['module_url'] = route_to('payroll_components');
        $this->perm = 'payroll_component';
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
                    'title'  => lang('PayrollComponents.job_title'),
                    'field'     => 'job_title',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                [
                    'title'  => lang('PayrollComponents.meal'),
                    'field'     => 'meal_allowance',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
                [
                    'title'  => lang('PayrollComponents.transport'),
                    'field'     => 'transport_allowance',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
                [
                    'title'  => lang('PayrollComponents.job_allowance'),
                    'field'     => 'job_title_allowance',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
                [
                    'title'  => lang('PayrollComponents.credit_allowance'),
                    'field'     => 'credit_allowance',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
                [
                    'title'  => lang('PayrollComponents.jht'),
                    'field'     => 'jht',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'percentFormatterDefault'
                ],
                [
                    'title'  => lang('PayrollComponents.bpjs_ks'),
                    'field'     => 'bpjs_ks',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'percentFormatterDefault'
                ],
                [
                    'title'  => lang('PayrollComponents.retire'),
                    'field'     => 'retire_insurance',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'percentFormatterDefault'
                ],
            ],
            'url'       => route_to('payroll_component_list'),
            'cookie_id' => 'table-payroll-component'
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
        $this->data['title'] = lang('PayrollComponents.heading');
        $this->data['heading'] = lang('PayrollComponents.heading');
        if ($this->permAdd) {
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('payroll_component_form', 0), lang('PayrollComponents.add_heading'));
        }
        $this->data['toolbar'] = view('table-toolbar/default', $this->data);
        return view('list', $this->data);
    }

    public function get_list()
    {
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['job_title'] = $getData['search'];
        $table = new \App\Models\TableModel('allowances', false);
        if (isset($getData['sort'])) {
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }
        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.*, b.name as job_title, c.*, '" . ($this->permEdit ? route_to('payroll_component_form', 'ID') : '') . "' AS `edit`, '" . ($this->permDelete ? route_to('payroll_component_delete', 'ID') : '') . "' AS `delete`");
        $table->setJoin([
            [
                'table' => 'job_titles b',
                'on'    => 'b.id = a.job_title_id',
                'type'  => 'left',
            ],
            [
                'table' => 'deductions c',
                'on'    => 'c.job_title_id = b.id',
                'type'  => 'left',
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
        $allowance = NULL;
        $deduction = NULL;
        $breadcrumb = $this->_setDefaultBreadcrumb();
        if ($id) {
            $this->permEdit or exit();
            $allowance = $this->allowancesModel->where(['job_title_id' => $id])->first();
            $deduction = $this->deductionsModel->where(['job_title_id' => $id])->first();
            if (!$allowance || !$deduction) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
            $breadcrumb->add(lang('Common.edit'), current_url());
        } else {
            $this->permAdd or exit();
            $breadcrumb->add(lang('Common.add'), current_url());
        }

        $form = [
            [
                'id'                 => 'allowance_id',
                'value'              => ($allowance && $deduction) ? $allowance->id : '',
                'type'               => 'hidden'
            ],
            [
                'id'                 => 'deduction_id',
                'value'              => ($allowance && $deduction) ? $deduction->id : '',
                'type'               => 'hidden'
            ],
            [
                'id'                 => 'job_title_id',
                'value'              => ($allowance && $deduction) ? $allowance->job_title_id : '',
                'label'              => lang('PayrollComponents.job_title'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'type'               => 'dropdown',
                'class'              => 'select2',
                'options'            => $this->_dropdownJobTitles()
            ],
            [
                'id' => '',
                'type'  => 'html',
                'html'  => $this->_section('Allowance')
            ],
            [
                'id'                 => 'meal_allowance',
                'value'              => ($allowance && $deduction) ? $allowance->meal_allowance : '',
                'label'              => lang('PayrollComponents.meal'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'class'              => 'number',
                'input_addons'       => [
                    'pre' => 'Rp'
                ],
            ],
            [
                'id'                 => 'transport_allowance',
                'value'              => ($allowance && $deduction) ? $allowance->transport_allowance : '',
                'label'              => lang('PayrollComponents.transport'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'class'              => 'number',
                'input_addons'       => [
                    'pre' => 'Rp'
                ],
            ],
            [
                'id'                 => 'job_title_allowance',
                'value'              => ($allowance && $deduction) ? $allowance->job_title_allowance : '',
                'label'              => lang('PayrollComponents.job_allowance'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'class'              => 'number',
                'input_addons'       => [
                    'pre' => 'Rp'
                ],
            ],
            [
                'id'                 => 'credit_allowance',
                'value'              => ($allowance && $deduction) ? $allowance->credit_allowance : '',
                'label'              => lang('PayrollComponents.credit_allowance'),
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'class'              => 'number',
                'input_addons'       => [
                    'pre' => 'Rp'
                ],
            ],
            [
                'id' => '',
                'type'  => 'html',
                'html'  => $this->_section('Deductions')
            ],
            [
                'id'                 => 'jht',
                'value'              => ($allowance && $deduction) ? $deduction->jht : '',
                'label'              => lang('PayrollComponents.jht'),
                'required'           => 'required',
                'form_control_class' => 'col-md-3',
                'class'              => 'number',
                'input_addons'       => [
                    'post' => '%'
                ],
            ],
            [
                'id'                 => 'bpjs_ks',
                'value'              => ($allowance && $deduction) ? $deduction->bpjs_ks : '',
                'label'              => lang('PayrollComponents.bpjs_ks'),
                'required'           => 'required',
                'form_control_class' => 'col-md-3',
                'class'              => 'number',
                'input_addons'       => [
                    'post' => '%'
                ],
            ],
            [
                'id'                 => 'retire_insurance',
                'value'              => ($allowance && $deduction) ? $deduction->retire_insurance : '',
                'label'              => lang('PayrollComponents.retire'),
                'required'           => 'required',
                'form_control_class' => 'col-md-3',
                'class'              => 'number',
                'input_addons'       => [
                    'post' => '%'
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
            'action' => route_to('payroll_component_save'),
            'build'  => $form_builder->build_form_horizontal($form),
        ];

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['title'] = $allowance && $deduction ? lang('PayrollComponents.edit_heading') : lang('PayrollComponents.add_heading');
        $this->data['heading'] = $this->data['title'];
        return view('form', $this->data);
    }

    private function _section($type)
    {

        $icon = $type == 'Allowance' ? '<i class="la la-plus-circle"></i>' : '<i class="la la-minus-circle"></i>';
        $html = '<hr>
                <div class="d-flex font-weight-bolder">
                    ' . $icon . '
                    <h5 class="font-weight-bolder mb-0" style="margin-left:5px">' . $type . '</h5>
                </div>';
        return $html;
    }

    public function _dropdownJobTitles()
    {
        $jobTitles = $this->jobTitlesModel->findAll();
        $dropdownJobTitles = ['' => ''];

        foreach ($jobTitles as $jobTitle) {
            $dropdownJobTitles[$jobTitle->id] = $jobTitle->name;
        }

        return $dropdownJobTitles;
    }


    public function save()
    {
        $this->request->isAJAX() or exit();

        $rules = [
            'job_title_id'      => [
                'label' => lang('PayrollComponents.job_title'),
                'rules' => 'required'
            ],
        ];
        $return['status'] = 'error';

        do {

            $postData = $this->request->getPost();

            if (!$postData['allowance_id'] || !$postData['deduction_id']) {
                $rules['job_title_id'] = [
                    'label' => lang('PayrollComponents.job_title'),
                    'rules' => 'is_unique[allowances.job_title_id]',
                ];
            } else {
                $rules['username']['rules'] = 'is_unique[allowances.job_title_id,job_title_id,' . $postData['job_title_id'] . ']';
            }

            if (!$this->validate($rules)) {
                $return['message'] = $this->validator->listErrors('default');
                break;
            }

            $allowanceData = [
                'job_title_id' => $postData['job_title_id'],
                'meal_allowance' => $postData['meal_allowance'],
                'transport_allowance' => $postData['transport_allowance'],
                'job_title_allowance' => $postData['job_title_allowance'],
                'credit_allowance' => $postData['credit_allowance'],
            ];
            $deductionData = [
                'job_title_id' => $postData['job_title_id'],
                'jht' => $postData['jht'],
                'bpjs_ks' => $postData['bpjs_ks'],
                'retire_insurance' => $postData['retire_insurance'],
            ];

            if (!$postData['allowance_id'] || !$postData['deduction_id']) {
                $this->allowancesModel->insert($allowanceData);
                $this->deductionsModel->insert($deductionData);
            } else {

                $this->allowancesModel->update($postData['allowance_id'], $allowanceData);
                $this->deductionsModel->update($postData['deduction_id'], $deductionData);
            }

            $return = [
                'message'  => sprintf(lang('Common.saved.success'), lang('Common.payroll_component')),
                'status'   => 'success',
                'redirect' => route_to('payroll_components')
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
        $this->permDelete or exit();

        $allowance = $this->allowancesModel->where(['job_title_id' => $id])->first();
        $deduction = $this->deductionsModel->where(['job_title_id' => $id])->first();

        if ($allowance && $deduction) {
            $this->allowancesModel->delete($allowance->id);
            $this->deductionsModel->delete($deduction->id);
            $return = [
                'message' => sprintf(lang('Common.deleted.success'), lang('Common.payroll_component')),
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


    private function _setDefaultBreadcrumb()
    {
        $breadcrumb = new \App\Libraries\Breadcrumb();
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('PayrollComponents.heading'), route_to('payroll_components'));

        return $breadcrumb;
    }
}
