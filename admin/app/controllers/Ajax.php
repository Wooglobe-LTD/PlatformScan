<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends APP_Controller {

	public function __construct() {
        parent::__construct();
		
		$this->load->model('Auth_Model','auth');
		$this->load->model('User_Model','user');
    }
	
	
	public function change_password()
	{
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Password Chnaged Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('old_password','Old Password','trim|required|callback_validate_old_password');
		$this->validation->set_rules('new_password','New Password','trim|required|regex_match[/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/]');
		$this->validation->set_rules('confirm_password','Confirm Password','trim|required|matches[new_password]');
		$this->validation->set_message('required','This field is required.');
		$this->validation->set_message('regex_match','Your password must contain at least (1) lowercase, (1) uppercase, , (1) number, (1) special character and minmum 8 letter.');
		$this->validation->set_message('matches',"Confirm password doesn't match with new password.");
		
		if($this->validation->run() === false){
			
			$fields = array('old_password','new_password','confirm_password');
			$errors = array();
			foreach($fields as $field){
				$errors[$field] = form_error($field);
			}
			$response['code'] = 201;
			$response['message'] = 'Validation Errors!';
			$response['error'] = $errors;
			$response['url'] = '';
			
		}else{
			
			$password = sha1($this->security->xss_clean($this->input->post('new_password')));
			$dbData['password'] = $password;
			$this->db->where('id',$this->sess->userdata('adminId'));
			$this->db->update('admin',$dbData);
		}
		
		echo json_encode($response);
		exit;
	}
	
	public function validate_old_password($password){
		
		$password = sha1($this->security->xss_clean($password));
		
		if(!empty($password)){
			$result = $this->auth->getUserByPassword($password);
			if($result->num_rows()>0){
				
				return true;
				
			}else{
				
				$this->validation->set_message('validate_old_password','Invalid old password.');
				return false;
				
			}
		}else{
			
			$this->validation->set_message('validate_old_password','This field is required.');
			return false;
			
		}
		
	}
	
	public function settings(){
		
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        /*include('./vendor/signrequest/src/AtaneNL/SignRequest/Client.php');
        include('./vendor/signrequest/src/AtaneNL/SignRequest/CreateDocumentResponse.php');
        $client = new \AtaneNL\SignRequest\Client($this->config->config['sr_token']);
		$templates = $client->getTemplates();*/

		$response['data'] = settings();
        $array = array('');
        $null = array('value'=>'','text'=>'Default SignRequest Template*');
        $array[] = $null;
        /*foreach($templates->results as $row){

            $arr = array('value'=>$row->uuid,'text'=>$row->name);
            $array[] = $arr;

        }*/
		$response['templates'] = $array;
		echo json_encode($response);
		exit;
	}
	
	public function settings_save()
	{
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Site Settings Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('site_title','Site Title','trim|required');
		$this->validation->set_rules('site_email','Site Email','trim|required|valid_email');
		$this->validation->set_rules('site_mobile','Site Mobile','trim|required');
		$this->validation->set_rules('site_mobile2','Site Mobile 2','trim|required');
		$this->validation->set_rules('site_address','Site Address','trim|required');
		$this->validation->set_rules('description_footer','Video Description Footer','trim|required');
		$this->validation->set_rules('expense','Expense','trim|required');
		$this->validation->set_message('required','This field is required.');
		$this->validation->set_message('valid_email','Please enter the valid email address.');
		
		if($this->validation->run() === false){
			
			$fields = array('site_title','site_email','site_mobile','site_mobile2','site_address','description_footer');
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
			$this->db->where('id',1);
			$this->db->update('settings',$dbData);
		}
		
		echo json_encode($response);
		exit;
	}

	
	
	
}
