<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StaffController extends APP_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['active'] = 'staff';
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
        if(role_permitted_html(false)){
            $js[] = 'assets/js/staff.js';
        }
        $this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->load->model('MobileAppStaff','staff');
        $this->load->model('Role_Model','role');

    }
    public function index()
    {
        auth();
        role_permitted();
        $this->data['title'] = 'Staff Member Management';
        $this->data['roles'] = $this->role->getActiveRoles('ar.id,ar.title');
        $this->data['content'] = $this->load->view('staff/listing',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }


    public function members_listing(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
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

        $result = $this->staff->getAllMembers('ar.title,a.id,a.username,a.name,a.email,case when (a.status = 1) THEN "Active" ELSE "Inactive" END as status',$search,$start,$limit,$orderby,$params['columns']);
        $resultCount = $this->staff->getAllMembers();
        $response = array();
        $data = array();

        foreach($result->result() as $row){
            $r = array();
            if(role_permitted_html(false)) {
                $links = '<a title="Edit Member" href="javascript:void(0);" class="edit-member" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> | <a title="Delete Member" href="javascript:void(0);" class="delete-member" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>| <a title="Password Change" href="javascript:void(0);" class="reset-password" data-id="' . $row->id . '"><i class="material-icons">lock_open</i></a>';
                $r[] = $links;
            }
            $r[] = $row->title;
            $r[] = $row->name;
            $r[] = $row->username;
            $r[] = $row->email;
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

    public function add_member(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Member Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('admin_role_id','Designation','trim|required');
        $this->validation->set_rules('name','Member Name','trim|required|alpha_numeric_spaces');
        $this->validation->set_rules('username','Designation','trim|required|callback_validate_username');
        $this->validation->set_rules('email','Controller URI','trim|required|callback_validate_email');
        $this->validation->set_rules('status','Status','trim|required');
        $this->validation->set_message('required','This field is required.');
        $this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');

        if($this->validation->run() === false){

            $fields = array('admin_role_id','name','username','email','status');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{
            $this->load->helper('string');
            $password = random_string('alnum', 10);
            $dbData = $this->security->xss_clean($this->input->post());
            $dbData['password']   = sha1($password);
            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['created_by'] = $this->sess->userdata('adminId');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->insert('admin',$dbData);
            $message = 'Dear '.$dbData['name'].'<br> You are register as WooGlobe Staff member successfully. Now You can <a href="'.$this->urlmaker->shorten(base_url()).'">login</a> using this credentials.<br><b>Username : </b>'.$dbData['username'].'<br><b>Password : </b>'.$password;
            $this->email($dbData['email'],$dbData['name'],'noreply@wooglobe.com','WooGlobe','WooGlobe Member',$message);
        }

        echo json_encode($response);
        exit;

    }




    public function validate_username($username)
    {

        $username = $this->security->xss_clean($username);


        if(!empty($username)){
            if (preg_match('/^[a-zA-Z0-9.]+$/', $username)) {
                $result = $this->staff->getMemberByUsername($username);
                if($result->num_rows() > 0){
                    $this->validation->set_message('validate_username','This username already in use.');
                    return false;
                }else{
                    return true;
                }
            }else{
                $this->validation->set_message('validate_username','Only alphabets,numbers and dot are allowed.');
                return false;
            }
        }else{
            $this->validation->set_message('validate_username','This field is required.');
            return false;
        }

    }

    public function validate_email($email)
    {

        $email = $this->security->xss_clean($email);


        if(!empty($email)){
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result = $this->staff->getMemberByEmail($email);
                if($result->num_rows() > 0){
                    $this->validation->set_message('validate_email','This email already in use.');
                    return false;
                }else{
                    return true;
                }
            }else{
                $this->validation->set_message('validate_email','Please enter the valid email.');
                return false;
            }
        }else{
            $this->validation->set_message('validate_email','This field is required.');
            return false;
        }

    }

    public function get_member(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Record found!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->staff->getMemberById($id,'a.id,a.admin_role_id,a.status,a.name,a.username,a.email');
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No member found!';
            $response['error'] = 'No member found!';
            $response['url'] = '';

        }else{

            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }

    public function update_member(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Member Updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->staff->getMemberById($id);
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No member found!';
            $response['error'] = 'No member found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }


        $this->validation->set_rules('admin_role_id','Designation','trim|required');
        $this->validation->set_rules('name','Member Name','trim|required|alpha_numeric_spaces');
        $this->validation->set_rules('username','Designation','trim|required|callback_validate_username_edit['.$id.']');
        $this->validation->set_rules('email','Controller URI','trim|required|callback_validate_email_edit['.$id.']');
        $this->validation->set_rules('status','Status','trim|required');
        $this->validation->set_message('required','This field is required.');
        $this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');

        if($this->validation->run() === false){

            $fields = array('admin_role_id','name','username','email','status');



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
            unset($dbData['id']);
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->where('id',$id);
            $this->db->where('id >',1);
            $this->db->update('admin',$dbData);
        }

        echo json_encode($response);
        exit;

    }

    public function validate_username_edit($username,$id)
    {

        $username = $this->security->xss_clean($username);


        if(!empty($username)){
            if (preg_match('/^[a-zA-Z0-9.]+$/', $username)) {
                $result = $this->staff->getMemberByUsername($username);
                if($result->num_rows() > 0){
                    $result = $result->row();
                    if($result->id == $id){

                        return true;

                    }else{

                        $this->validation->set_message('validate_username_edit','This username already in use.');
                        return false;

                    }

                }else{
                    return true;
                }
            }else{
                $this->validation->set_message('validate_username_edit','Only alphabets,numbers and dot are allowed.');
                return false;
            }
        }else{
            $this->validation->set_message('validate_username_edit','This field is required.');
            return false;
        }

    }


    public function validate_email_edit($email,$id)
    {

        $email = $this->security->xss_clean($email);


        if(!empty($email)){
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result = $this->staff->getMemberByEmail($email);
                if($result->num_rows() > 0){
                    $result = $result->row();
                    if($result->id == $id){

                        return true;

                    }else{

                        $this->validation->set_message('validate_email_edit','This email already in use.');
                        return false;

                    }

                }else{
                    return true;
                }
            }else{
                $this->validation->set_message('validate_email_edit','Please enter the valid email.');
                return false;
            }
        }else{
            $this->validation->set_message('validate_email_edit','This field is required.');
            return false;
        }

    }

    public function delete_member(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Member Deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->staff->getMemberById($id);

        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No member found!';
            $response['error'] = 'No member found!';
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
        $this->db->where('id >',1);
        $this->db->update('admin',$dbData);

        echo json_encode($response);
        exit;

    }


    public function member_reset_password(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Password Changed Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->staff->getMemberById($id,'a.id,a.name,a.email');
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No member found!';
            $response['error'] = 'No member found!';
            $response['url'] = '';

        }else{

            $this->load->helper('string');
            $password = random_string('alnum', 10);
            $dbData['password']   = sha1($password);
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->where('id',$id);
            $this->db->where('id >',1);
            $this->db->update('admin',$dbData);
            $message = 'Dear '.$result->name.'<br> You are password change successfully.<br> <b>New Password : </b>'.$password;
            $this->email($result->email,$result->name,'noreply@wooglobe.com','WooGlobe','Paasword Reset',$message);


            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }

}
