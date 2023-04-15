<?php

namespace Modules\Finance\Controllers;

use CodeIgniter\I18n\Time;
use \App\Controllers\BaseController;
use \App\Models\PayrollDataModel;
use \App\Models\PayrollDetailModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use GuzzleHttp\Client;

class PayrollData extends BaseController
{

    protected $data;
    private $model;
    private $modelDetail;
    private $permView;

    public function __construct()
    {
        parent::__construct();
        $this->model = new PayrollDataModel();
        $this->modelDetail = new PayrollDetailModel();
        $this->data['module'] = 'finance';
        $this->data['menu'] = 'payroll_data';
        $this->data['module_url'] = route_to('payroll_data');
        $this->permView = has_permission('payroll_data');
    }

    public function index()
    {
        $this->permView or exit();
        $this->data['table'] = [
            'columns' => [
                [
                    'title'  => lang('PayrollData.date'),
                    'field'     => 'date',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'longDateFormatterDefault'
                ],
                [
                    'title'  => lang('PayrollData.employee'),
                    'field'     => 'employee',
                    'sortable'  => 'true',
                    'switchable' => 'false'
                ],
                [
                    'title'  => lang('PayrollData.salary'),
                    'field'     => 'total_salary',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
                [
                    'title'  => lang('PayrollData.overtime'),
                    'field'     => 'total_overtime',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
                [
                    'title'  => lang('PayrollData.total_allowance'),
                    'field'     => 'total_allowance',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
                [
                    'title'  => lang('PayrollData.total_deduction'),
                    'field'     => 'total_deduction',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
                [
                    'title'  => lang('PayrollData.take_home_pay'),
                    'field'     => 'take_home_pay',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter' => 'currencyFormatterDefault'
                ],
            ],
            'url'       => route_to('payroll_data_list'),
            'cookie_id' => 'table-payroll-data'
        ];
        $this->data['table']['columns'][] = [
            'field'      => 'action',
            'class'      => 'w-100px nowrap',
            'align'      => 'center',
            'switchable' => 'false',
            'formatter'  => 'actionFormatterPayroll',
            'events'     => 'actionEventPayroll'
        ];

        $this->data['pluginCSS'] = [
            base_url('vendors/js/bootstrap-datepicker/bootstrap-datepicker.min.css'),
        ];

        $this->data['customJS'] = [
            base_url('js/modules/payroll-data.js')
        ];

        $this->data['pluginJS'] = [
            base_url('vendors/js/bootstrap-datepicker/bootstrap-datepicker.min.js'),
        ];

        $payroll = $this->model->where('month(date)', date('m'))->findAll();
        $this->data['hasProceed'] = $payroll ? true : false;

        $breadcrumb = $this->_setDefaultBreadcrumb();
        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['title'] = lang('PayrollData.heading');
        $this->data['heading'] = lang('PayrollData.heading');

        $this->data['left_toolbar'] = '<div class="d-flex"><input type="text" name="month" class="table-filter form-control mr-1" style="width: 200px;" placeholder="Select Month" autocomplete="off">';
        $this->data['left_toolbar'] .= '<div class="mr-1"><select name="user_id" class="table-filter select-employee select2 mr-1" style="width:200px;">' . $this->_dropdownEmployees() . '</select></div>';
        $this->data['left_toolbar'] .= '<a class="btn btn-danger text-white pdf-report" target="_blank" style="margin-right:8px">PDF</a>';
        $this->data['left_toolbar'] .= '<a href="javascript:void(0)" class="btn btn-success text-white send-all">Send All</a></div>';

        $this->data['toolbar'] = view('table-toolbar/default', $this->data);

        return view('list2', $this->data);
    }

    public function pdf_report()
    {
        $monthYear = $this->request->getGet('month_year');
        $userId = $this->request->getGet('user_id');

        $payrollDataModel = new \App\Models\PayrollDataModel();
        $payrollDataModel->select('payrolls.*, a.fullname employee_name');
        $payrollDataModel->join('users a', 'a.id = payrolls.user_id', 'left');

        if ($monthYear && $userId) {
            $payrollDataModel->where(['payrolls.user_id' => $userId, "DATE_FORMAT(payrolls.date,'%Y-%m')" => $monthYear]);
        }
        if ($monthYear) {
            $payrollDataModel->where("DATE_FORMAT(payrolls.date,'%Y-%m')", $monthYear);
        }
        if ($userId) {
            $payrollDataModel->where("payrolls.user_id", $userId);
        }
        $payroll = $payrollDataModel->findAll();

        $path = ROOTPATH . '/public/images/logo/logo.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $dataImage = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($dataImage);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isRemoteEnabled', true);
        $pdf = new Dompdf($options);

        $this->data['logo'] = $logo;
        $this->data['data'] = $payroll;
        $this->data['employee'] = $userId && $payroll ? $payroll[0]->employee_name : '';
        $this->data['monthYear'] = $monthYear && $payroll ? $monthYear : '';

        $html = view('Modules\Finance\Views\report_payroll', $this->data);
        $pdf->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        $pdf->stream('Payroll-report.pdf', array("Attachment" => false));
    }

    public function pdf($id)
    {
        $path = ROOTPATH . '/public/images/logo/logo.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $dataImage = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($dataImage);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isRemoteEnabled', true);
        $pdf = new Dompdf($options);

        $this->data['logo'] = $logo;

        $this->model->select('payrolls.*, a.fullname, a.employee_id, b.name job_title, c.name workplace');
        $this->model->join('users a', 'a.id = payrolls.user_id', 'left');
        $this->model->join('job_titles b', 'b.id = a.job_title_id', 'left');
        $this->model->join('branches c', 'c.id = a.branch_id', 'left');
        $data = $this->model->find($id);

        $this->data['data'] = $data;
        $this->data['detail'] = $this->modelDetail->where('payroll_id', $id)->first();

        $html = view('Modules\Finance\Views\payroll_slip', $this->data);
        $pdf->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();
        $pdf->stream('Payslip-' . $data->fullname . '-' . $data->date . '.pdf', array("Attachment" => false));
    }

    public function send($id)
    {
        // generate pdf slip
        $this->request->isAJAX();

        $path = ROOTPATH . '/public/images/logo/logo.png';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $dataImage = file_get_contents($path);
        $logo = 'data:image/' . $type . ';base64,' . base64_encode($dataImage);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');
        $options->set('isRemoteEnabled', true);
        $pdf = new Dompdf($options);

        $this->data['logo'] = $logo;

        $this->model->select('payrolls.*, a.fullname, a.employee_id, a.email, b.name job_title, c.name workplace');
        $this->model->join('users a', 'a.id = payrolls.user_id', 'left');
        $this->model->join('job_titles b', 'b.id = a.job_title_id', 'left');
        $this->model->join('branches c', 'c.id = a.branch_id', 'left');

        $data = $this->model->find($id);
        $detail = $this->modelDetail->where('payroll_id', $id)->first();

        $this->data['data'] = $data;
        $this->data['detail'] = $detail;

        $html = view('Modules\Finance\Views\payroll_slip', $this->data);
        $pdf->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();
        $payslip = $pdf->output();

        // send email
        $config = new Configuration();
        $config->setApiKey('api-key', $_ENV['SENDINBLUE_KEY']);
        $apiInstance = new TransactionalEmailsApi(new Client(), $config);
        $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();

        $content = chunk_split(base64_encode($payslip));
        $attachment_item = array(
            'name' => 'Slip Gaji-' . $data->fullname . '-' . Time::parse($data->date)->toLocalizedString('MMMM, yyyy') . '.pdf',
            'content' => $content
        );
        $attachment_list = array($attachment_item);
        $sendSmtpEmail['attachment']    = $attachment_list;

        $sendSmtpEmail['subject'] = 'Slip Penggajian Bulan';
        $sendSmtpEmail['htmlContent'] = view('\Modules\Finance\Views\body_email', ['name' => $data->fullname, 'date' => $data->date]);
        $sendSmtpEmail['sender'] = array('name' => 'Mulia Bintang Kejora', 'email' => 'muliabintangkejora.noreply@gmail.com');
        $sendSmtpEmail['to'] = array(
            array('email' => $data->email, 'name' => $data->fullname)
        );

        try {
            $apiInstance->sendTransacEmail($sendSmtpEmail);
            $return['status'] = 'success';
            $return['message'] = 'Success Send PaySlip to ' . $data->fullname;
        } catch (Exception $e) {
            $return['status'] = 'error';
            $return['message'] = 'Oops.. something went wrong!';
        }

        echo json_encode($return);
    }

    public function send_all()
    {
        $this->request->isAJAX() or exit();

        $monthYear = $this->request->getPost('month_year');
        $userId = $this->request->getPost('user_id');

        $payrollDataModel = new \App\Models\PayrollDataModel();
        $payrollDataModel->select('payrolls.*, a.fullname employee_name');
        $payrollDataModel->join('users a', 'a.id = payrolls.user_id', 'left');

        if ($monthYear && $userId) {
            $payrollDataModel->where(['payrolls.user_id' => $userId, "DATE_FORMAT(payrolls.date,'%Y-%m')" => $monthYear]);
        }
        if ($monthYear) {
            $payrollDataModel->where("DATE_FORMAT(payrolls.date,'%Y-%m')", $monthYear);
        }
        if ($userId) {
            $payrollDataModel->where("payrolls.user_id", $userId);
        }
        $payroll = $payrollDataModel->findAll();


        if ($payroll) {
            foreach ($payroll as $row) {
                $path = ROOTPATH . '/public/images/logo/logo.png';
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $dataImage = file_get_contents($path);
                $logo = 'data:image/' . $type . ';base64,' . base64_encode($dataImage);

                $options = new Options();
                $options->set('defaultFont', 'Helvetica');
                $options->set('isRemoteEnabled', true);
                $pdf = new Dompdf($options);

                $this->data['logo'] = $logo;

                $this->model->select('payrolls.*, a.fullname, a.employee_id, a.email, b.name job_title, c.name workplace');
                $this->model->join('users a', 'a.id = payrolls.user_id', 'left');
                $this->model->join('job_titles b', 'b.id = a.job_title_id', 'left');
                $this->model->join('branches c', 'c.id = a.branch_id', 'left');

                $data = $this->model->find($row->id);
                $detail = $this->modelDetail->where('payroll_id', $row->id)->first();

                $this->data['data'] = $data;
                $this->data['detail'] = $detail;

                $html = view('Modules\Finance\Views\payroll_slip', $this->data);
                $pdf->loadHtml(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
                $pdf->setPaper('A4', 'landscape');
                $pdf->render();
                $payslip = $pdf->output();

                // send email
                $config = new Configuration();
                $config->setApiKey('api-key', $_ENV['SENDINBLUE_KEY']);
                $apiInstance = new TransactionalEmailsApi(new Client(), $config);
                $sendSmtpEmail = new \SendinBlue\Client\Model\SendSmtpEmail();

                $content = chunk_split(base64_encode($payslip));
                $attachment_item = array(
                    'name' => 'Slip Gaji-' . $data->fullname . '-' . Time::parse($data->date)->toLocalizedString('MMMM, yyyy') . '.pdf',
                    'content' => $content
                );
                $attachment_list = array($attachment_item);
                $sendSmtpEmail['attachment']    = $attachment_list;

                $sendSmtpEmail['subject'] = 'Slip Penggajian Bulan';
                $sendSmtpEmail['htmlContent'] = view('\Modules\Finance\Views\body_email', ['name' => $data->fullname, 'date' => $data->date]);
                $sendSmtpEmail['sender'] = array('name' => 'Mulia Bintang Kejora', 'email' => 'muliabintangkejora.noreply@gmail.com');
                $sendSmtpEmail['to'] = array(
                    array('email' => $data->email, 'name' => $data->fullname)
                );

                $apiInstance->sendTransacEmail($sendSmtpEmail);
            }

            $return = ['status' => 'success', 'message' => 'All Payslip sended'];
        } else {
            $return = ['status' => 'error', 'message' => 'There is no data send !'];
        }

        echo json_encode($return);
    }

    public function get_draft()
    {
        $this->request->isAJAX() or exit();

        $db = db_connect();
        $builder = $db->table('users a');
        $builder->select('a.fullname, a.id user_id, (SELECT COUNT(id) FROM attendances WHERE attendances.user_id = a.id AND MONTH(attendances.date) = ' . date('m') . ') as work_days, (SELECT SUM(overtime.total_amount) FROM overtime WHERE overtime.user_id = a.id AND MONTH(overtime.date) = ' . date('m') . ') as overtime, b.regional_wage, c.*, d.*');
        $builder->join('branches b', 'a.branch_id = b.id', 'inner');
        $builder->join('allowances c', 'a.job_title_id = c.job_title_id', 'inner');
        $builder->join('deductions d', 'a.job_title_id = d.job_title_id', 'inner');
        $data = $builder->get()->getResultArray();

        echo json_encode($data);
    }

    public function get_list()
    {
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['employee'] = $getData['search'];
        $filterAdditional = [];
        $table = new \App\Models\TableModel('payrolls', false);
        if (isset($getData['sort'])) {
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }
        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.*, b.fullname as employee, '" . route_to('payroll_data_send', 'ID') . "' AS `send`, '" . route_to('payroll_data_pdf', 'ID') . "' AS `pdf`");
        $table->setJoin([
            [
                'table' => 'users b',
                'on'    => 'b.id = a.user_id',
                'type'  => 'left',
            ],
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

        if ($getData['filter']['month']) {
            $month = explode('-', $getData['filter']['month']);
            $filterAdditional[] = [
                'function' => 'where',
                'column'   => 'MONTH(a.date)',
                'value'    => $month[1],
            ];
            $filterAdditional[] = [
                'function' => 'where',
                'column'   => 'YEAR(a.date)',
                'value'    => $month[0],
            ];
        }

        $table->setFilter();
        $table->setFilterAdditional($filterAdditional);
        $output['rows'] = $table->getAll();
        $output['total'] = $table->countAll();
        $output['totalNotFiltered'] = $table->countAll();
        echo json_encode($output);
    }

    public function generate()
    {
        $this->request->isAJAX() or exit();
        $data = json_decode($this->request->getPost('data'));
        $return['status'] = 'error';
        $return['message'] = 'Something Error';
        foreach ($data as $row) {
            $totalAllowance = $row->meal_allowance + $row->transport_allowance + $row->job_title_allowance + $row->credit_allowance;
            $totalDeduction = $row->jht_num + $row->bpjs_ks_num + $row->retire_insurance_num + $row->other_deduction;
            $payroll = [
                'date' => date('Y-m-d'),
                'user_id' => $row->user_id,
                'work_days' => $row->work_days,
                'total_salary' => $row->total_salary,
                'total_overtime' => $row->total_overtime,
                'total_allowance' => $totalAllowance,
                'total_deduction' => $totalDeduction,
                'take_home_pay' => ($row->total_salary + $row->total_overtime + $totalAllowance) -  $totalDeduction,
                'status' => 1,
                'created_by' => user_id(),
            ];

            $this->model->insert($payroll);
            $insertId = $this->model->getInsertID();

            $payrollDetail = [
                'payroll_id' => $insertId,
                'meal_allowance' => $row->meal_allowance,
                'transport_allowance' => $row->transport_allowance,
                'job_title_allowance' => $row->job_title_allowance,
                'credit_allowance'  => $row->credit_allowance,
                'jht' => $row->jht_num,
                'bpjs_ks' => $row->bpjs_ks_num,
                'retire_insurance' => $row->retire_insurance_num,
                'other_deduction' => $row->other_deduction,
            ];

            $this->modelDetail->insert($payrollDetail);
            $return['status'] = 'success';
            $return['message'] = 'Payroll generate successfully';
        }

        echo json_encode($return);
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
                'jht' => $postData['jht_num'],
                'bpjs_ks' => $postData['bpjs_ks_num'],
                'retire_insurance' => $postData['retire_insurance_num'],
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

    private function _setDefaultBreadcrumb()
    {
        $breadcrumb = new \App\Libraries\Breadcrumb();
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('PayrollData.heading'), route_to('payroll_data'));

        return $breadcrumb;
    }
}
