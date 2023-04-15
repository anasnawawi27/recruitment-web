<?php

namespace Modules\Employees\Controllers;

use \App\Controllers\BaseController;
use \App\Models\LeavesModel;
use Cloudinary\Cloudinary;
use Cloudinary\Api\Upload\UploadApi;

class RequestLeaves extends BaseController
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
        $this->model = new LeavesModel();
        $this->data['module'] = 'employee';
        $this->data['menu'] = 'request_leaves';
        $this->data['module_url'] = route_to('request_leaves');
        $this->perm = 'request_leave';
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
                    'title'  => lang('RequestLeaves.start_date'),
                    'field'     => 'start_date',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter'  => 'longDateFormatterDefault'
                ],
                [
                    'title'  => lang('RequestLeaves.end_date'),
                    'field'     => 'end_date',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'formatter'  => 'longDateFormatterDefault'
                ],
                [
                    'title'  => lang('RequestLeaves.type'),
                    'field'     => 'type',
                    'sortable'  => 'true',
                    'switchable' => 'false',
                    'class'     => 'text-uppercase font-weight-bold'
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
            'url'       => route_to('request_leave_list'),
            'cookie_id' => 'table-request-leave'
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
        $this->data['title'] = lang('RequestLeaves.heading');
        $this->data['heading'] = lang('RequestLeaves.heading');
        if ($this->permAdd) {
            $this->data['left_toolbar'] = sprintf(lang('Common.btn.add'), route_to('request_leave_form', 0), lang('RequestLeaves.add_heading'));
        }

        $this->data['toolbar'] = view('table-toolbar/default', $this->data);
        return view('list', $this->data);
    }

    public function get_list()
    {
        $this->request->isAJAX() or exit();
        $getData = $this->request->getGet();

        $filter['a.start_date'] = $getData['search'];
        $filter['a.end_date'] = $getData['search'];
        $filter['a.type'] = $getData['search'];

        $table = new \App\Models\TableModel('leaves');
        if (isset($getData['sort'])) {
            $table->setOrder([
                'sort'  => $getData['sort'],
                'order' => $getData['order']
            ]);
        }

        $table->setLimit($getData['offset'], $getData['limit']);
        $table->setFilter($filter);
        $table->setSelect("a.id, a.start_date, a.end_date, a.type, a.status, a.file, IF(a.status = 'draft', '" . ($this->permEdit ? route_to('request_leave_form', 'ID') : '') . "', '') AS `edit`,IF(a.status = 'draft', '" . ($this->permDelete ? route_to('request_leave_delete', 'ID') : '') . "', '') AS `delete`");
        $table->setWhere(['a.user_id' => user_id()]);
        $table->orderBy('a.id', 'desc');
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
                'id'                 => 'start_date',
                'value'              => ($data) ? $data->start_date : '',
                'label'              => lang('RequestLeaves.start_date'),
                'type'               => 'date',
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
            ],
            [
                'id'                 => 'end_date',
                'value'              => ($data) ? $data->end_date : '',
                'label'              => lang('RequestLeaves.end_date'),
                'type'               => 'date',
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
            ],
            [
                'id'                 => 'type',
                'value'              => ($data) ? $data->type : '',
                'label'              => lang('RequestLeaves.type'),
                'type'               => 'dropdown',
                'class'              => 'select2',
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'options'            => [
                    'cuti'  => 'Cuti',
                    'sakit' => 'Sakit',
                    'izin'  => 'Izin Khusus'
                ]
            ],
            [
                'id'                 => 'note',
                'value'              => ($data) ? $data->note : '',
                'label'              => lang('RequestLeaves.note'),
                'type'               => 'textarea',
                'required'           => 'required',
                'form_control_class' => 'col-md-5',
                'cols'               => 10,
                'rows'               => 3,
            ],
            [
                'id'                 => 'file',
                'value'              => ($data) ? $data->file : '',
                'label'              => lang('RequestLeaves.document'),
                'type'               => 'image',
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
            'action' => route_to('request_leave_save'),
            'build'  => $form_builder->build_form_horizontal($form),
        ];

        $this->data['breadcrumb'] = $breadcrumb->render();
        $this->data['data'] = $data;
        $this->data['title'] = $data ? lang('RequestLeaves.edit_heading') : lang('RequestLeaves.add_heading');
        $this->data['heading'] = $this->data['title'];
        return view('form', $this->data);
    }

    public function save()
    {
        $this->request->isAJAX() or exit();

        $rules = [
            'start_date'      => [
                'label' => lang('RequestLeaves.start_date'),
                'rules' => 'required'
            ],
            'end_date'      => [
                'label' => lang('RequestLeaves.end_date'),
                'rules' => 'required'
            ],
            'type'      => [
                'label' => lang('RequestLeaves.type'),
                'rules' => 'required'
            ],
        ];
        $return['status'] = 'error';

        do {

            $postData = $this->request->getPost();
            $image = $this->request->getFile('upload_image');

            if (!$image) {
                if ($postData['type'] == 'sakit' && !$image) {
                    $rules['file'] = [
                        'label' => lang('RequestLeaves.document'),
                        'rules' => 'required'
                    ];
                }
            }

            if ($postData['type'] == 'sakit' && isset($postData['delete_image']) && $postData['delete_image'] === '1') {
                $rules['file'] = [
                    'label' => lang('RequestLeaves.document'),
                    'rules' => 'required'
                ];
            }


            if (!$this->validate($rules)) {
                $return['message'] = $this->validator->listErrors('default');
                break;
            }

            // image upload
            $cld = new UploadApi();
            if ($image) {
                if ($image->isValid()) {
                    $upload = $cld->upload($image->getRealPath(), ['folder' => 'mulia-bintang-kejora/documents']);
                    $postData['file'] = $upload['secure_url'];
                }
            } else {
                if (isset($postData['id'])) {
                    if ($image = $this->model->find($postData['id'])) {
                        $postData['file'] = $image->file;
                        if ($image->file) {
                            if (isset($postData['delete_image']) && $postData['delete_image'] === '1') {
                                $postData['file'] = NULL;
                            }
                        }
                    }
                }
            }

            $postData['user_id'] = user_id();
            $postData['status'] = 'draft';

            if (!$postData['id']) {
                $postData['created_by'] = user_id();
                $this->model->insert($postData);
            } else {
                $postData['updated_by'] = user_id();
                $this->model->update($postData['id'], $postData);
            }
            $return = [
                'message'  => sprintf(lang('Common.saved.success'), lang('Common.leave')),
                'status'   => 'success',
                'redirect' => route_to('request_leaves')
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
                'message' => sprintf(lang('Common.deleted.success'), lang('Common.leave')),
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
        $breadcrumb = new \App\Libraries\Breadcrumb;
        $breadcrumb->add(lang('Common.home'), site_url());
        $breadcrumb->add(lang('RequestLeaves.heading'), route_to('request_leaves'));

        return $breadcrumb;
    }
};
