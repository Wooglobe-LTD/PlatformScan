<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'users';
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
			'assets/js/users.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false, 'users'),
            'can_add'=>role_permitted_html(false, 'users','add_user'),
            'can_edit'=>role_permitted_html(false, 'users','update_user'),
            'can_delete'=>role_permitted_html(false, 'users','delete_user'),
            'can_reset'=>role_permitted_html(false, 'users','user_reset_password'),
        );
		$this->load->model('User_Model','user');
        
    }
	public function index()
	{
		auth();
        role_permitted(false, 'users');
		$this->data['title'] = 'APP Users Management';
		$this->data['content'] = $this->load->view('users/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function users_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'users');
        if($role_permitted_ajax){
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
		
		$result = $this->user->getAllUsers(3,'id,full_name,gender,email,mobile,(case when (status = 1) THEN "Active" ELSE "Inactive" END) as status',$search,$start,$limit,$orderby,$params['columns'],3);
		$resultCount = $this->user->getAllUsers(3);
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit User" href="javascript:void(0);" class="edit-user" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
            }
            if($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete User" href="javascript:void(0);" class="delete-user" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
            }
            if($this->data['assess']['can_reset']) {
                $links .= '| <a title="Reset User Password" href="javascript:void(0);" class="reset-password" data-id="' . $row->id . '"><i class="material-icons">lock_open</i></a>';
            }
            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete'] || $this->data['assess']['can_reset']) {
                $r[] = $links;
            }
			$r[] = $row->full_name;
			$r[] = $row->gender;
			$r[] = $row->email;
			$r[] = $row->mobile;
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

	public function add_user(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}

        $role_permitted_ajax = role_permitted_ajax(false, 'users','add_user');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'New User Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('full_name','Full Name','trim|required');
		$this->validation->set_rules('gender','Gender','trim|required');
		$this->validation->set_rules('email','Email Address','trim|required|valid_email|callback_validate_email');
		$this->validation->set_rules('mobile','Mobile','trim|required');
		$this->validation->set_rules('address','Address','trim|required');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');
		
		if($this->validation->run() === false){
			
			$fields = array('full_name','gender','email','mobile','address','status');
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
			$dbData['created_at'] = date('Y-m-d H:i:s');
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['created_by'] = $this->sess->userdata('adminId');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$dbData['role_id'] = 3;
			$this->load->helper('string');
			$password = random_string('alnum', 8);
			$dbData['password'] = sha1($password);
			$message = 'Dear '.$dbData['full_name'].'<br>You have been register successfully and Your account credential given below<br> <b>Email :</b> '.$dbData['email'].' <br> <b>Password : </b> '.$password.'';
			$this->email($dbData['email'],$dbData['full_name'],'norelpty@viralgreats.com','WooGlobe','Account Register successfully',$message);
			$this->db->insert('users',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	public function validate_email($email)
	{
		
		$email = $this->security->xss_clean($email);
		
		if(!empty($email)){
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$result = $this->user->getUserByEmail($email);
				if($result->num_rows() > 0){
					$this->validation->set_message('validate_email','This email address already in use!');
					return false;
				}else{
					return true;
				}
			}else{
				$this->validation->set_message('validate_email','Please enter the valid email address.');
				return false;
			}
		}else{
			$this->validation->set_message('validate_email','This field is required.');
			return false;
		}
		
	}

	public function get_user(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'users');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Record found!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->user->getUserById($id,'id,full_name,gender,email,mobile,address,status');
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No user found!';
			$response['error'] = 'No user found!';
			$response['url'] = '';

		}else{

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}

	public function update_user(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'users','update_user');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }

		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'User Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->user->getUserById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No user found!';
			$response['error'] = 'No user found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
		$this->validation->set_rules('full_name','Full Name','trim|required');
		$this->validation->set_rules('gender','Gender','trim|required');
		$this->validation->set_rules('email','Email Address','trim|required|valid_email|callback_validate_email_edit['.$id.']');
		$this->validation->set_rules('mobile','Mobile','trim|required');
		$this->validation->set_rules('address','Address','trim|required');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');
		
		if($this->validation->run() === false){
			
			$fields = array('full_name','gender','email','mobile','address','status');
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
			$this->db->update('users',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	public function validate_email_edit($email,$id)
	{
		
		$email = $this->security->xss_clean($email);
		
		if(!empty($email)){
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$result = $this->user->getUserByEmail($email);
				if($result->num_rows() > 0){
					$result = $result->row();
					if($result->id == $id){

						return true;

					}else{

						$this->validation->set_message('validate_email_edit','This email address already in use!');
						return false;

					}
					
				}else{
					return true;
				}
			}else{
				$this->validation->set_message('validate_email_edit','Please enter the valid email address.');
				return false;
			}
		}else{
			$this->validation->set_message('validate_email_edit','This field is required.');
			return false;
		}
		
	}

	public function delete_user(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'users','delete_user');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'User Deleted Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->user->getUserById($id);

		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No user found!';
			$response['error'] ='No user found!';
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
		$this->db->update('users',$dbData);
		
		echo json_encode($response);
		exit;

	}

    public function user_reset_password(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'users','user_reset_password');
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Password Reset Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->user->getUserById($id,'u.id,u.full_name,u.email');
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No user found!';
            $response['error'] = 'No user found!';
            $response['url'] = '';

        }else{

            $this->load->helper('string');
            $password = random_string('alnum', 10);
            $dbData['password']   = sha1($password);
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->where('id',$id);
            $this->db->update('users',$dbData);
            $message = 'Dear '.$result->full_name.'<br> You are password change successfully.<br> <b>New Password : </b>'.$password;
            $this->email($result->email,$result->full_name,'noreply@wooglobe.com','WooGlobe','Paasword Reset',$message);


            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }
	
}
