<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partners extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'partners';
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
			'assets/js/partners4.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false, 'partners'),
            'can_add'=>role_permitted_html(false, 'partners','add_partner'),
            'can_edit'=>role_permitted_html(false, 'partners','update_partner'),
            'can_delete'=>role_permitted_html(false, 'partners','delete_partner'),
            'can_reset'=>role_permitted_html(false, 'partners','partner_reset_password'),
        );
		$this->load->model('User_Model','user');
        
    }
	public function index()
	{
		auth();
        role_permitted(false, 'partners');
		$this->data['title'] = 'Partners Management';
		$this->data['content'] = $this->load->view('partners/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function partners_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'partners');
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
		
		$result = $this->user->getAllUsers(2,'id,full_name,gender,email,mobile,company_name,business_name,business_type,(case when (status = 1) THEN "Active" ELSE "Inactive" END) as status,(case when (watermark = 1) THEN "Yes" ELSE "No" END) as watermark,(case when (appearance = 1) THEN "Yes" ELSE "No" END) as appearance',$search,$start,$limit,$orderby,$params['columns'],2);
		$resultCount = $this->user->getAllUsers(2);
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Partner" href="javascript:void(0);" class="edit-partner" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
				$links .= '<a title="Add Representative Email" href="javascript:void(0);" class="add_partner_email" data-id="' . $row->id . '"><i class="material-icons">mail</i></a>';
            }
            if($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete Partner" href="javascript:void(0);" class="delete-partner" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
            }
            if($this->data['assess']['can_reset']) {
                $links .= '| <a title="Reset Partner Password" href="javascript:void(0);" class="reset-password" data-id="' . $row->id . '"><i class="material-icons">lock_open</i></a>';
            }
            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete'] || $this->data['assess']['can_reset']) {
                $r[] = $links;
            }
            $r[] = $row->company_name;
            $r[] = $row->full_name;
            $r[] = $row->email;
            $r[] = $row->business_name;
            $r[] = $row->business_type;
		/*	$r[] = $row->gender;
			$r[] = $row->email;
			$r[] = $row->mobile;*/
			$r[] = $row->status;
			$r[] = $row->watermark;
			$r[] = $row->appearance;


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

	public function add_partner(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}

        $role_permitted_ajax = role_permitted_ajax(false, 'partners','add_partner');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'New Partner Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
        $this->validation->set_rules('company_name','Company Name','trim|required');
        $this->validation->set_rules('business_name','Business Model','trim|required');
		$this->validation->set_rules('full_name','Representive Name','trim|required');
		$this->validation->set_rules('email','Representive Email Address','trim|required|valid_email|callback_validate_email');
		$this->validation->set_rules('business_type','Business Type','trim|required');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_rules('watermark','Status','trim|required');
		$this->validation->set_rules('appearance','Status','trim|required');
		$this->validation->set_message('required','This field is required.');
		
		if($this->validation->run() === false){
			
			$fields = array('company_name','business_name','full_name','email','business_type','status','watermark','appearance');
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
			$dbData['role_id'] = 2;
			$dbData['appearance'];
			$this->load->helper('string');
			$password = random_string('alnum', 8);
//			$dbData['password'] = sha1($password);
            $dbData['password'] = md5($password);
			$message = 'Dear '.$dbData['full_name'].'<br>You have been register successfully as partner and Your account credential given below<br> <b>Email :</b> '.$dbData['email'].' <br> <b>Password : </b> '.$password.'';
			$this->email($dbData['email'],$dbData['full_name'],'norelpty@viralgreats.com','WooGlobe','Account Register successfully',$message);
			$this->db->insert('users',$dbData);
			$id = $this->db->insert_id();
			$random = random_string('alnum',8);
			$random = strtoupper($random);
			$random = $random.$id;
			$key = hash("sha1",$random,FALSE);
			$key = strtoupper($key);
			$data = array(
				'unique_key' => $random,
				'encrypted_unique_key' => $key
			);

			$this->db->where('id', $id);
			$this->db->update('users',$data);
		}
		
		echo json_encode($response);
		exit;

	}

	public function validate_email($email)
	{
		
		$email = $this->security->xss_clean($email);
		
		if(!empty($email)){
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$result = $this->user->getUserByEmail($email, 2);
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

	public function get_partner(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'partners');
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
		$result = $this->user->getUserById($id,'id,full_name,company_name,email,business_name,business_type,status,watermark,appearance');
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

	public function update_partner(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'partners','update_partner');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }

		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Partner Updated Successfully!';
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
        $this->validation->set_rules('company_name','Company Name','trim|required');
        $this->validation->set_rules('business_name','Business Model','trim|required');
        $this->validation->set_rules('full_name','Representive Name','trim|required');
        $this->validation->set_rules('email','Email Address','trim|required|valid_email|callback_validate_email_edit['.$id.']');
        $this->validation->set_rules('business_type','Business Type','trim|required');
        $this->validation->set_rules('status','Status','trim|required');
        $this->validation->set_rules('watermark','Status','trim|required');
        $this->validation->set_rules('appearance','Appearance','trim|required');
        $this->validation->set_message('required','This field is required.');
		
		if($this->validation->run() === false){
			
			$fields = array('company_name','business_name','full_name','email','business_type','status','watermark','appearance');
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
				$result = $this->user->getPartnerByEmail($email, 2,"0");
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

	public function delete_partner(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'partners','delete_partner');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Partner Deleted Successfully!';
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
        $mrss_feed_results = $this->db->query('SELECT id FROM mrss_feeds WHERE partner_id = "'.$id.'"')->result();

        if($mrss_feed_results[0]){
            $feed_id =$mrss_feed_results[0]->id;
            $this->db->where('feed_id',$feed_id);
            $this->db->delete('feed_video');
        }
		$this->db->where('id',$id);
		$this->db->update('users',$dbData);

        $this->db->where('partner_id',$id);
        $this->db->update('mrss_feeds',$dbData);



		echo json_encode($response);
		exit;

	}

    public function partner_reset_password(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'partners','partner_reset_password');
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

	public function get_partner_emails()
	{
		$response = array();
		
		$response['code'] = 200;
		$response['emails'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->user->getPartnerEmailsById($id);
		if ($result)
		{
			$emails = array();
			foreach($result->result() as $email)
			{
				$emails[] = $email->email;
			}
			$response['emails'] = $emails;
		}

		echo json_encode($response);
		exit;
	}

	public function add_partner_email()
	{
		$response = array();
		$response['code'] = 200;
		$response['message'] = 'Representive Email Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';

		$this->validation->set_rules('rep_email', 'rep_email', 'required|valid_email');
		$this->validation->set_message('required', 'This field is required');
		$this->validation->set_message('valid_email', 'Email should be valid');

		if($this->validation->run() === false) {
			$fields = array('rep_email');
			$errors = array();
			foreach($fields as $field){
				$errors[$field] = form_error($field);
			}
			$response['code'] = 201;
			$response['message'] = 'Validation Errors!';
			$response['error'] = $errors;
		}
        else {
			$params = $this->security->xss_clean($this->input->post());

            $dbData['partner_id'] = $params['partner_id'];
			$dbData['email'] = $params['rep_email'];
			$this->db->insert('partner_emails', $dbData);
		}
		
		echo json_encode($response);
		exit;
	}
	
}
