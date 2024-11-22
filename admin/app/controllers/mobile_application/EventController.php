<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EventController extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'Mobile App Events';
        $css = array(
            'bower_components/uikit/css/uikit.almost-flat.min.css',
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
            'assets/js/ma_events.js'
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);
        $this->data['assess'] = array(
            'list' => role_permitted_html(false, 'event'),
            'can_add' => role_permitted_html(false, 'users', 'add_event'),
            'can_edit' => role_permitted_html(false, 'users', 'update_event'),
            'can_delete' => role_permitted_html(false, 'users', 'delete_event'),
        );
        $this->load->model('MobileAPPEvent', 'event');

    }

    public function index()
    {
        auth();
        role_permitted(false, 'events');
        $this->data['title'] = 'APP Events Management';
        $categories = $this->event->getAllCategories();
        $this->data['categories']=$categories->result();
        $this->data['content'] = $this->load->view('mobile_app/events/listing', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }


    public function events_listing()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'events');
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
        $categories = $this->event->getAllCategories();


        $result = $this->event->getAllEvents(3, 'id,name,description,starting_date,ending_date,country_of_event,category_id,(case when (status = 1) THEN "Active" ELSE "Inactive" END) as status', $search, $start, $limit, $orderby, $params['columns'], 3);
        $resultCount = $this->event->getAllEvents(3);
        $response = array();
        $data = array();

        foreach ($result->result() as $row) {
            $r = array();
            $links = '';
            if ($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Event" href="javascript:void(0);" class="edit-event" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
            }
            if ($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete Event" href="javascript:void(0);" class="delete-event" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
            }

            if ($this->data['assess']['can_edit'] || $this->data['assess']['can_delete'] || $this->data['assess']['can_reset']) {
                $r[] = $links;
            }
            $r[] = $row->name;
            $r[] = $row->description;
            $r[] = $row->starting_date;
            $r[] = $row->ending_date;
            $r[] = $row->country_of_event;
            $r[] = $row->title;
            $r[] = $row->status;


            $data[] = $r;
        }
        //$data['categories']=$categories->result();
        $response['code'] = 200;
        $response['message'] = 'Listing';
        $response['error'] = '';
        $response['data'] = $data;
        $response['recordsTotal'] = $resultCount->num_rows();
        $response['recordsFiltered'] = $resultCount->num_rows();
        echo json_encode($response);
        exit;
    }

    public function add_event()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'events', 'add_event');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Event Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('name', 'Event Name', 'trim|required');
        $this->validation->set_rules('description', 'Desciption', 'trim|required');
        $this->validation->set_rules('starting_date', 'Event Starting Date', 'required');
        $this->validation->set_rules('ending_date', 'Event Ending Date', 'required|callback_check_date');

        $this->validation->set_rules('country_of_event', 'Country of Event', 'trim|required');
        $this->validation->set_rules('category_id', 'Category', 'trim|required');
        $this->validation->set_rules('status', 'Status', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('name', 'description', 'starting_date', 'ending_date', 'country_of_event', 'category','status');
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
            $dbData['created_by'] = $this->sess->userdata('adminId');

            $this->db->insert('ma_events', $dbData);
        }

        echo json_encode($response);
        exit;

    }
    function check_date($str){



        if ($_POST['ending_date']){



            if (strtotime($_POST['ending_date']) > strtotime($_POST['starting_date'])){

                return TRUE;
            }
            else{

                return FALSE;
            }
        }
        else{

            return FALSE;
        }

    }


    public function get_event()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'events');
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
        $result = $this->event->getEventById($id);
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Event found!';
            $response['error'] = 'No Event found!';
            $response['url'] = '';

        } else {

            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }

    public function update_event()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'events', 'update_event');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Event Updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->event->getEventById($id);
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No event found!';
            $response['error'] = 'No event found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $this->validation->set_rules('name', 'name', 'required');
        $this->validation->set_rules('name', 'Event Name', 'trim|required');
        $this->validation->set_rules('description', 'Desciption', 'trim|required');
        $this->validation->set_rules('starting_date', 'Event Starting Date', 'required');
        $this->validation->set_rules('ending_date', 'Event Ending Date', 'required|callback_check_date');

        $this->validation->set_rules('country_of_event', 'Country of Event', 'trim|required');
        $this->validation->set_rules('status', 'Status', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('name', 'description', 'starting_date', 'ending_date', 'country_of_event', 'status');
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
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->where('id', $id);
            $this->db->update('ma_events', $dbData);
        }

        echo json_encode($response);
        exit;

    }


    public function delete_event()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'events', 'delete_event');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Event Deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->event->getEventById($id);

        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Event found!';
            $response['error'] = 'No Event found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->sess->userdata('adminId');
        $dbData['deleted_at'] = date('Y-m-d H:i:s');
        $dbData['deleted_by'] = $this->sess->userdata('adminId');
        $dbData['deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update('ma_events', $dbData);

        echo json_encode($response);
        exit;

    }


}
