<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends APP_Controller {

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
        if(role_permitted_html(false)){
            $js[] = 'assets/js/groups.js';
        }
        $this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->load->model('Group_Model','group');
        $this->load->model('Role_Model','role');
        $this->load->model('Menu_Model','menu');
        $this->load->model('Staff_Model','staff');

    }
    public function index()
    {
        auth();
        role_permitted();
        $this->data['title'] = 'Staff Members Groups Management';
        $this->data['staffs'] =  $this->staff->getAllMembers('a.*','',0,0,'a.name ASC');
        $this->data['roles'] =  $this->role->getAllRoles('ar.*','',0,0,'ar.title ASC');
        $this->data['content'] = $this->load->view('groups/listing',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }


    public function groups_listing(){
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

        $result = $this->group->getAllGroups('ar.id,ar.title,case when (ar.status = 1) THEN "Active" ELSE "Inactive" END as status',$search,$start,$limit,$orderby,$params['columns']);
        $resultCount = $this->group->getAllGroups();
        $response = array();
        $data = array();

        foreach($result->result() as $row){
            $r = array();
            if(role_permitted_html(false)) {
                $links = '<a title="Edit Group" href="javascript:void(0);" class="edit-group" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> | <a title="Delete Group" href="javascript:void(0);" class="delete-group" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a> ';
                $r[] = $links;
            }
            $r[] = $row->title;

            $roles = getRolesByGroupId($row->id);
            $role = '';
            foreach ($roles->result() as $ro){
                $role .= $ro->title.'</br>';
            }
            $r[] = $role;
            $staffs = getStaffByGroupId($row->id);
            $staff = '';
            foreach ($staffs->result() as $st){
                $staff .= $st->name.'</br>';
            }
            $r[] = $staff;
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

    public function add_group(){
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
        $response['message'] = 'New Groups Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('title','Group Title','trim|required|callback_validate_title');
        $this->validation->set_rules('status','Status','trim|required');
        $this->validation->set_rules('roles[]','Roles','trim|required');
        $this->validation->set_rules('staffs[]','Staff Members','trim|required');
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('title','status','roles','staffs');
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
            $roles = $dbData['roles'];
            $staffs = $dbData['staffs'];
            unset($dbData['roles']);
            unset($dbData['staffs']);
           // $dbData['role_type_id'] = 2;
            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['created_by'] = $this->sess->userdata('adminId');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->insert('admin_groups',$dbData);
            $group_id = $this->db->insert_id();
            foreach ($roles as $role_id){
                $roleData['role_id'] = $role_id;
                $roleData['group_id'] = $group_id;
                $this->db->insert('role_groups',$roleData);
            }
            foreach ($staffs as $staff_id){
                $gData['admin_id'] = $staff_id;
                $gData['group_id'] = $group_id;
                $this->db->insert('staff_groups',$gData);
                $this->db->where('id',$staff_id);
                $this->db->update('admin',['admin_role_id'=>implode(',',$roles)]);
            }
        }

        echo json_encode($response);
        exit;

    }

    public function validate_title($title)
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

    }

    public function get_group(){
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
        $result = $this->group->getGroupById($id,'ar.id,ar.title,ar.status');
        $roles = getRolesByGroupId($id);
        $role = array();
        foreach ($roles->result() as $ro){
            $role[] = $ro->id;
        }
        $result->roles = $role;
        $staffs = getStaffByGroupId($id);
        $staff = array();
        foreach ($staffs->result() as $st){
            $staff[] = $st->id;
        }
        $result->staffs = $staff;
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

    public function update_group(){
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
        $response['message'] = 'Group Updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->group->getGroupById($id);
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No Group found!';
            $response['error'] = 'No Group found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $this->validation->set_rules('title','Group Title','trim|required|callback_validate_title_edit['.$id.']');
        $this->validation->set_rules('status','Status','trim|required');
        $this->validation->set_rules('roles[]','Roles','trim|required');
        $this->validation->set_rules('staffs[]','Staff Members','trim|required');
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('title','status','roles','staffs');
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
            $roles = $dbData['roles'];
            $staffs = $dbData['staffs'];
            unset($dbData['roles']);
            unset($dbData['staffs']);
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->where('id',$id);
            $this->db->update('admin_groups',$dbData);
            $this->db->where('group_id',$id);
            $this->db->delete('role_groups');
            $this->db->where('group_id',$id);
            $this->db->delete('staff_groups');
            foreach ($roles as $role_id){
                $roleData['role_id'] = $role_id;
                $roleData['group_id'] = $id;
                $this->db->insert('role_groups',$roleData);
            }
            foreach ($staffs as $staff_id){
                $gData['admin_id'] = $staff_id;
                $gData['group_id'] = $id;
                $this->db->insert('staff_groups',$gData);
                $this->db->where('id',$staff_id);
                $this->db->update('admin',['admin_role_id'=>implode(',',$roles)]);
            }
        }

        echo json_encode($response);
        exit;

    }

    public function validate_title_edit($title,$id)
    {

        $title = $this->security->xss_clean($title);


        if(!empty($title)){
            if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
                $result = $this->group->getGroupByTitle($title);
                if($result->num_rows() > 0){
                    $result = $result->row();
                    if($result->id == $id){

                        return true;

                    }else{

                        $this->validation->set_message('validate_title_edit','This Group already exist!');
                        return false;

                    }

                }else{
                    return true;
                }
            }else{
                $this->validation->set_message('validate_title_edit','Only alphabets and namubers are allowed.');
                return false;
            }
        }else{
            $this->validation->set_message('validate_title_edit','This field is required.');
            return false;
        }

    }

    public function delete_group(){
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
        $response['message'] = 'Group Deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->group->getGroupById($id);

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
        $this->db->update('admin_groups',$dbData);
        /*$this->db->where('group_id',$id);
        $this->db->delete('role_groups');
        $this->db->where('group_id',$id);
        $this->db->delete('staff_groups');*/

        echo json_encode($response);
        exit;

    }



}
