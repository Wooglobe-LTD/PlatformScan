<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TasksController extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'Mobile Tasks Management';
        $css = array(
            'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css'
        );
        $js = array(
            'bower_components/datatables/media/js/jquery.dataTables.min.js',
            'bower_components/datatables-buttons/js/dataTables.buttons.js',
            'assets/js/custom/datatables/buttons.uikit.js',
            'bower_components/jszip/dist/jszip.min.js',
            'bower_components/pdfmake/build/pdfmake.min.js',
            'bower_components/pdfmake/build/vfs_fonts.js',
            'bower_components/datatables-buttons/js/buttons.colVis.js',
            'bower_components/datatables-buttons/js/buttons.html5.js',
            'bower_components/datatables-buttons/js/buttons.print.js',
            'assets/js/custom/datatables/datatables.uikit.min.js',
            'assets/js/custom/dropify/dist/js/dropify.min.js',
            'assets/js/pages/forms_file_input.min.js',
            'assets/js/ma_tasks.js'
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);
        $this->data['assess'] = array(
            'list' => role_permitted_html(false, 'Tasks'),
            'can_add' => role_permitted_html(false, 'Tasks', 'add_Task'),
            'can_edit' => role_permitted_html(false, 'Tasks', 'update_Task'),
            'can_delete' => role_permitted_html(false, 'Tasks', 'delete_Task')
        );
        $this->load->model('MobileAPPTask', 'ma_task');

    }

    public function index()
    {

        auth();
        role_permitted(false, 'tasks');
        $this->data['title'] = 'Mobile Tasks Management';


        $this->data['content'] = $this->load->view('mobile_app/tasks/listing', $this->data, true);


        $this->load->view('common_files/template', $this->data);
    }


    public function tasks_listing()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'tasks');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->get());
        $search = '';
        $orderby = '';
        $start = 0;
        $limit = 0;
        if (isset($params['search'])) {
            $search = $params['search']['value'];
        }
        if (isset($params['start'])) {
            $start = $params['start'];
        }
        if (isset($params['length'])) {
            $limit = $params['length'];
        }
        if (isset($params['order'])) {
            $orderby = $params['columns'][$params['order'][0]['column']]['name'] . ' ' . $params['order'][0]['dir'];
        }

        $result = $this->ma_task->getAllTasks('t.id,t.task,t.points,t.created_at,case when (t.status = 1) THEN "Active" ELSE "Inactive" END as status', $search, $start, $limit, $orderby, $params['columns']);
        $resultCount = $this->ma_task->getAllTasks();
        $response = array();
        $data = array();

        foreach ($result->result() as $row) {
            $r = array();
            $links = '';
            if ($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Task" href="javascript:void(0);" class="ma-edit-task" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';

            }
            if ($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete Task" href="javascript:void(0);" class="ma-delete-task" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';

            }
            if ($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }

            $r[] = $row->task;
            $r[] = $row->points;
            $r[] = $row->created_at;

            $r[] = $row->status;

            $data[] = $r;
        }
        $response['code'] = 200;
        $response['message'] = 'Listing';

        $response['error'] = '';
        $response['data'] = $data;
        $response['recordsTotal'] = $resultCount->num_rows();
        $response['recordsFiltered'] = $resultCount->num_rows();

        echo json_encode($response);
        exit;
    }

    public function add_task()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'Tasks', 'add_Task');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Task Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('task', 'Task', 'required');
        $this->validation->set_rules('points', 'Points', 'required');
        $this->validation->set_rules('status', 'Status', 'required');

        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('Task','points', 'status');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {

            $dbData = $this->security->xss_clean($this->input->post());

            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['created_by'] = $this->sess->userdata('adminId');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->insert('ma_tasks', $dbData);
        }

        echo json_encode($response);
        exit;

    }


    public function get_task()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'Tasks');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Record found!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->ma_task->getTaskById($id, 'id,task,points,status');
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Task found!';
            $response['error'] = 'No Task found!';
            $response['url'] = '';

        } else {

            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }

    public function update_task()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'Tasks', 'update_Task');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Task Updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->ma_task->getTaskById($id);
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Task found!';
            $response['error'] = 'No Task found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $this->validation->set_rules('task', 'Task', 'required');
        $this->validation->set_rules('points', 'Points', 'required');
        $this->validation->set_rules('status', 'Status', 'required');

        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('Task', 'points','status');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {


            $dbData = $this->security->xss_clean($this->input->post());
            unset($dbData['id']);
            //$dbData['slug'] = slug($dbData['title'],'Tasks','slug');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->where('id', $id);
            $this->db->update('ma_tasks', $dbData);
        }

        echo json_encode($response);
        exit;

    }


    public function delete_Task()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'Tasks', 'delete_Task');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Task Deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->ma_task->getTaskById($id);

        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Task found!';
            $response['error'] = 'No Task found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }

        $this->db->where('id', $id);
        $this->db->delete('ma_tasks');

        echo json_encode($response);
        exit;

    }

}
