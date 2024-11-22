<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addstaffmember extends APP_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['active'] = 'groups';
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
        );
        /*if(group_permitted_html(false)){*/
            $js[] = 'assets/js/add_staff_member.js';
        /*}*/
        $this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->load->model('Addstaffmember_Model','add_staff_group');
        $this->load->model('Menu_Model','menu');
        $this->load->model('Staff_Model','staff');
    }
    public function index()
    {
        auth();
       // group_permitted(false);
        $this->data['title'] = 'Staff Members Groups Management';
        $query = $this->db->query('SELECT *  FROM `admin_groups` WHERE  deleted = 0');
        //$result = $query->row();
        $this->data['groups'] = $query ;
        $this->data['staffs'] =  $this->staff->getAllMembers('a.*','',0,0,'a.name ASC');
        $this->data['content'] = $this->load->view('Addstaffmember/listing',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }


    public function add_staff_groups_listing(){
        auth();
       /* $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $group_permitted_ajax = group_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($group_permitted_ajax);
            exit;
        }*/
        $params = $this->security->xss_clean($this->input->get());
        $search = '';
        $orderby = '';
        $start = 0;
        $limit = 0;
        if(isset($params['search'])){
            $search = $params['search']['value'];
        }
        if(isset($params['start'])){
            $start = $params['start'];
        }
        if(isset($params['length'])){
            $limit = $params['length'];
        }
        if(isset($params['order'])){
            $orderby = $params['columns'][$params['order'][0]['column']]['name'].' '.$params['order'][0]['dir'];
        }

        $result = $this->add_staff_group->getAllAddstaffmembers('ar.id,ar.title,ar.staff_member,case when (ar.status = 1) THEN "Active" ELSE "Inactive" END as status',$search,$start,$limit,$orderby,$params['columns']);
        $resultCount = $this->add_staff_group->getAllAddstaffmembers();
        $response = array();
        $data = array();

        foreach($result->result() as $row){
            $r = array();
           /* if(group_permitted_html(false)) {*/
                $links = '<a title="Delete Group" href="javascript:void(0);" class="delete-group" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a> ';
                $r[] = $links;
           /* }*/
            $r[] = $row->title;
            $r[] = $row->staff_member;
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

    public function add_staff_group(){
        auth();
       /* $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $group_permitted_ajax = group_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($group_permitted_ajax);
            exit;
        }*/
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Groups Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('title','Group Title','trim|required');
        $this->validation->set_rules('staff_member[]','Staff Member','trim|required');
        $this->validation->set_rules('status','Status','trim|required');
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('title','status','staff_member[]');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{

            $dbData = $this->security->xss_clean($this->input->post());
            for ($i = 0; $i < count($dbData['staff_member']); $i++) {
                $staff_member = $_POST['staff_member'][$i];


           // $dbData['role_type_id'] = 2;
                $dbData['staff_member'] = $staff_member;
            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['created_by'] = $this->sess->userdata('adminId');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->insert('add_staff_group',$dbData);
            }
        }

        echo json_encode($response);
        exit;

    }

   /* public function validate_title($title)
    {

        $title = $this->security->xss_clean($title);


        if(!empty($title)){
            if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
                $result = $this->group->getGroupByTitle($title);
                if($result->num_rows() > 0){
                    $this->validation->set_message('validate_title','This Groups already exist!');
                    return false;
                }else{
                    return true;
                }
            }else{
                $this->validation->set_message('validate_title','Only alphabet and numbers are allowed.');
                return false;
            }
        }else{
            $this->validation->set_message('validate_title','This field is required.');
            return false;
        }

    }*/

    public function get_add_staff_group(){
        auth();
       /* $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $group_permitted_ajax = group_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($group_permitted_ajax);
            exit;
        }*/
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Record found!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->add_staff_group->getAddstaffmembeById($id,'ar.id,ar.title,ar.status');
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No Group found!';
            $response['error'] = 'No Group found!';
            $response['url'] = '';

        }else{

            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }


    public function delete_add_staff_group(){
        auth();
       /* $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }

        $group_permitted_ajax = group_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($group_permitted_ajax);
            exit;
        }*/
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Group Deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->add_staff_group->getAddstaffmemberById($id);

        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No group found!';
            $response['error'] = 'No group found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->sess->userdata('adminId');
        $dbData['deleted_at'] = date('Y-m-d H:i:s');
        $dbData['deleted_by'] = $this->sess->userdata('adminId');
        $dbData['deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('add_staff_group',$dbData);

        echo json_encode($response);
        exit;

    }



}
