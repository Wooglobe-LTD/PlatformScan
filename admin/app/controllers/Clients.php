<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'clients';
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
			'assets/js/clients.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false, 'clients'),
            'can_add'=>role_permitted_html(false, 'clients','add_client'),
            'can_edit'=>role_permitted_html(false, 'clients','update_client'),
            'can_delete'=>role_permitted_html(false, 'clients','delete_client'),
            'can_reset'=>role_permitted_html(false, 'clients','client_reset_password'),
        );
		$this->load->model('User_Model','user');
		$this->load->model('Video_Lead_Model','lead');

    }
	public function index()
	{
		auth();
        role_permitted(false, 'clients');
		$this->data['title'] = 'Clients Management';
		$this->data['content'] = $this->load->view('clients/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function clients_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'clients');
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
		
		$result = $this->user->getAllUsers(1,'id,full_name,gender,email,mobile,(case when (status = 1) THEN "Active" ELSE "Inactive" END) as status',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->user->getAllUsers(1);
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '<a title="Ghost Login" href="'.root_url().'ghost/' . $row->id . '" target="_blank" class="" data-id="' . $row->id . '"><i class="material-icons">face</i></a> ';
			if($row->status == "Active") {
            	$links .= '| <a title="Wooglobe Ghost Login" href="https://www.wooglobe.com/?login=master&username='. $row->email .'&password='. MASTERPASS .'" target="_blank" class="" data-id="' . $row->id . '"><i class="material-icons">account_box</i></a> ';
			}
            if($this->data['assess']['can_edit']) {
                $links .= '| <a title="Edit Client" href="javascript:void(0);" class="edit-client" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
            }
            if($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete Client" href="javascript:void(0);" class="delete-client" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
            }
            if($this->data['assess']['can_reset']) {
                $links .= '| <a title="Reset Client Password" href="javascript:void(0);" class="reset-password" data-id="' . $row->id . '"><i class="material-icons">lock_open</i></a>';
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

	public function add_client(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}

        $role_permitted_ajax = role_permitted_ajax(false, 'clients','add_client');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'New Client Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('full_name','Full Name','trim|required');
		//$this->validation->set_rules('gender','Gender','trim|required');
		$this->validation->set_rules('email','Email Address','trim|required|valid_email|callback_validate_email');
		//$this->validation->set_rules('mobile','Mobile','trim|required');
		//$this->validation->set_rules('address','Address','trim|required');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');
		
		if($this->validation->run() === false){
			
			//$fields = array('full_name','gender','email','mobile','address','status');
			$fields = array('full_name','email','status');
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
			$dbData['role_id'] = 1;
			$this->load->helper('string');
			$token = random_string('alnum', 20);
			$dbData['verify_token'] = $token;
			$dbData['token_expiry_time'] = date('Y-m-d H:i:s',strtotime('+20 days',strtotime(date('Y-m-d H:i:s'))));
			$this->db->insert('users',$dbData);
			$id = $this->db->insert_id();
			$response['id'] = $id;
           /* $emailData = getEmailTemplateByCode('welcome_email');
            if($emailData){
                $str = $emailData->message;
                $subject = $emailData->subject;
                $ids = array(
                    'users' => $id
                );
                $message = dynStr($str,$ids);
                $url = $this->data['root'].'new-login/'.$token;

                //$url = $this->urlmaker->shorten($url);

                $message = str_replace('@LINK',$url,$message);
                //$message = 'Dear '.$dbData['full_name'].'<br>You have been register successfully as client and Your account credential given below<br> <b>Email :</b> '.$dbData['email'].' <br> <b>Password : </b> '.$password.'';
                $this->email($dbData['email'],$dbData['full_name'],'norelpy@wooglobe.com','WooGlobe',$subject,$message);
            }*/

		}
		
		echo json_encode($response);
		exit;

	}

    public function add_client_deal(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'clients','add_client');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Client Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('full_name','Full Name','trim|required');
        $this->validation->set_rules('email','Email Address','trim|required');
        $this->validation->set_rules('status','Status','trim|required');
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('full_name','email','status');
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

            if(isset($dbData['client_id']) and $dbData['client_id'] == 0 ){
                unset($dbData['client_id']);
            }

            if(isset($dbData['lead_id']) && !empty($dbData['lead_id'])){
                $lead_id = $dbData['lead_id'];
            }
            if(isset($dbData['email']) && !empty($dbData['email'])){
                $lead_email = $dbData['email'];
            }
            unset($dbData['lead_id']);
            //Get Client Id by email
            $result = $this->user->getUserByEmail($lead_email,'1');
            if($result->num_rows() > 0){
                $email_result=$result->result();
                $client_id=$email_result[0]->id;
                //Update client id data with verify token
                $dbData['created_at'] = date('Y-m-d H:i:s');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['created_by'] = $this->sess->userdata('adminId');
                $dbData['updated_by'] = $this->sess->userdata('adminId');
                $dbData['role_id'] = 1;
                $this->load->helper('string');
                $token = random_string('alnum', 20);
                $dbData['verify_token'] = $token;
                $dbData['token_expiry_time'] = date('Y-m-d H:i:s',strtotime('+20 days',strtotime(date('Y-m-d H:i:s'))));
                $this->db->where('id',$client_id);
                $this->db->update('users',$dbData);
            }else{
                $dbData['created_at'] = date('Y-m-d H:i:s');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['created_by'] = $this->sess->userdata('adminId');
                $dbData['updated_by'] = $this->sess->userdata('adminId');
                $dbData['role_id'] = 1;
                $this->load->helper('string');
                $token = random_string('alnum', 20);
                $dbData['verify_token'] = $token;
                $dbData['token_expiry_time'] = date('Y-m-d H:i:s',strtotime('+20 days',strtotime(date('Y-m-d H:i:s'))));
                $this->db->insert('users',$dbData);
                $client_id = $this->db->insert_id();
            }



            //Add client id in video lead
            $this->db->set('client_id',$client_id);
            $this->db->where('id',$lead_id);
            $this->db->update('video_leads');


            $this->db->set('client_id',$client_id);
            $this->db->where('email',$lead_email);
            $this->db->update('video_leads');

            action_add($lead_id,0,0,$this->sess->userdata('adminId'),1,'Account created');
            $response['id'] = $client_id;
           /* $emailData = getEmailTemplateByCode('welcome_email');
            if($emailData){
                $str = $emailData->message;

                $unique_key = $this->lead->getUniqueKey($lead_id);

                if($unique_key){
                    $subject = $emailData->subject.'-'.$unique_key->unique_key;
                }
                else{
                    $subject = $emailData->subject;
                }
                $ids = array(
                    'users' => $client_id
                );
                $message = dynStr($str,$ids);
                $url = $this->data['root'].'new-login/'.$token;

                //$url = $this->urlmaker->shorten($url);

                $message = str_replace('@LINK',$url,$message);
                //$message = 'Dear '.$dbData['full_name'].'<br>You have been register successfully as client and Your account credential given below<br> <b>Email :</b> '.$dbData['email'].' <br> <b>Password : </b> '.$password.'';
                $result=$this->email($lead_email,$dbData['full_name'],'norelpy@wooglobe.com','WooGlobe',$subject,$message);

                $notification = array();

                $notification['send_datime'] = date('Y-m-d H:i:s');
                $notification['lead_id'] = $lead_id;
                $notification['email_template_id'] = $emailData->id;
                $notification['email_title'] = $emailData->title;
                $notification['ids'] = json_encode($ids);

                $this->db->insert('email_notification_history',$notification);

               // $insert = $this->user->email_notification($notification);
            }*/

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

	public function get_client(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'clients');
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

	public function update_client(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'clients','update_client');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }

		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Client Updated Successfully!';
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
		//$this->validation->set_rules('gender','Gender','trim|required');
		$this->validation->set_rules('email','Email Address','trim|required|valid_email|callback_validate_email_edit['.$id.']');
		//$this->validation->set_rules('mobile','Mobile','trim|required');
		//$this->validation->set_rules('address','Address','trim|required');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');
		
		if($this->validation->run() === false){
			
			//$fields = array('full_name','gender','email','mobile','address','status');
			$fields = array('full_name','email','status');
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

	public function delete_client(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'clients','delete_client');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Client Deleted Successfully!';
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

    public function client_reset_password(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'clients','client_reset_password');
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
            $dbData['password']   = md5($password);
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
