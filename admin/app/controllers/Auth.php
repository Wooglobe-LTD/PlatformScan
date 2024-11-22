<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends APP_Controller {

	public function __construct() {
        parent::__construct();

        if (isset($_GET['act']) && $_GET['act'] == 'password_reset')
        {
            $this->sess->set_userdata('act', 'password_reset');
            $this->data['open_password_reset'] = true;
        }

        if (isset($_GET['msg']) && $_GET['msg'] == 'account_unblock')
        {
            $this->data['account_unblock_msg'] = 'Your account was successfully unblocked';
        }

		auth_verify();
		$this->load->model('Auth_Model','auth');
    }
	public function index()
	{
		$this->data['title'] = 'Login';
        $fail_try = 0;
        if($this->sess->userdata('fail_try') != ''){
            $fail_try = $this->sess->userdata('fail_try');
        }
        $this->data['fail_try']                    = $fail_try;
        $this->data['activate_login_recaptcha']    = $this->config->item('activate_login_recaptcha');
        $this->data['allow_failed_login_attempts'] = $this->config->item('allow_failed_login_attempts');
        $this->load->view('login',$this->data);
	}
	
	public function login()
	{
		$response = array();

        $fail_try = 0;
        if($this->sess->userdata('fail_try') != ''){
            $fail_try = $this->sess->userdata('fail_try');
        }

		$response['code'] = 200;
		$response['message'] = 'Login Successfully!';
		$response['error'] = '';
		$response['url'] = '';

        $username = $this->input->post('username');

        $activate_recaptcha = false;
        if($fail_try > $this->config->item('activate_login_recaptcha') || isset($_POST['g-recaptcha-response'])){
            $this->send_failed_login_attempts_emalis($username, $_SERVER['REMOTE_ADDR']);
            $this->validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha');
            $activate_recaptcha = true;
        }

		$this->validation->set_rules('username','Username','trim|required|callback_validate_user');
		$this->validation->set_rules('password','Password','trim|required');
		$this->validation->set_message('required','This field is required.');

		// if failed attempts exceeds allowed limit and account is not already blocked
        if (($fail_try > $this->config->item('allow_failed_login_attempts')) && !$this->auth->checkTempAccountBlockStatus($username)){
            // reset failed attempts after block
            $this->sess->set_userdata('fail_try',0);
            $this->auth->blockAccountByUsername($username);
            $this->send_temp_account_blockage_email($username);
        }
		
		if($this->validation->run() === false){
			// add captcha field in case recaptcha is activated
			$fields = array('username','password');
			if ($activate_recaptcha) {
                $fields[] = 'g-recaptcha-response';
            }
			$errors = array();
			foreach($fields as $field){
				$errors[$field] = form_error($field);
			}

			if ($fail_try > $this->config->item('activate_login_recaptcha')) {
                $response['show_recaptcha'] = 1;
            }

			$response['code'] = 201;
			$response['message'] = 'Validation Errors!';
			$response['error'] = $errors;
			$response['fail_try'] = $fail_try;
			$response['url'] = '';
			
		}else{
            // check if the account is not blocked
            if ($this->auth->checkTempAccountBlockStatus($username)){
                $response['code'] = 403;
                $response['account_blocked'] = 1;
                $response['message'] = 'Your account is temporarily blocked due to unsuccessful login attempts. It will take some time to restore it.';
                $response['fail_try'] = $fail_try;
                $response['error']   = 'Temp account blocked';

                echo json_encode($response);
                exit;
            }

			$password = sha1($this->security->xss_clean($this->input->post('password')));
			$username = $this->security->xss_clean($this->input->post('username'));
            $result = $this->auth->login($username,$password);
			$user = $result->row();

            $sess = array(
				'isAdminLogin'=>TRUE,
				'adminId'=>$user->id,
				'adminName'=>$user->name,
				'adminUsername'=>$user->username,
				'adminEmail'=>$user->email,
				'adminRoleId'=>$user->admin_role_id,
				'adminRole'=>$user->admin_role,
				'adminTypeId'=>$user->role_type_id,
                'adminType'=>$user->admin_type,
			);
			$this->sess->set_userdata($sess);
			$response['url'] = $this->data['url'].'dashboard';
		}
		
		echo json_encode($response);
		exit;
	}
	
	public function validate_user($username)
	{
		
		$password = sha1($this->security->xss_clean($this->input->post('password')));
		$username = $this->security->xss_clean($username);

        $fail_try = 0;
        if($this->sess->userdata('fail_try') != ''){
            $fail_try = $this->sess->userdata('fail_try');
        }

		if(!empty($username)){
			$result = $this->auth->login($username,$password);
			if($result->num_rows() > 0){
				return true;
			}else{
                $fail_try++;
                $this->sess->set_userdata('fail_try',$fail_try);
				$this->validation->set_message('validate_user','Invalid Username or Password!');
				return false;
			}
		}else{
			$this->validation->set_message('validate_user','This field is required.');
			return false;
		}
		
	}
	
	public function reset_password(){
		
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Password reset successfully! Please check your mail inbox.';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('email','Email','trim|callback_validate_email|valid_email|required');
		$this->validation->set_message('required','This field is required.');
		$this->validation->set_message('valid_email','Please enter the valid email address.');
		
		if($this->validation->run() === false){
			
			$fields = array('email');
			$errors = array();
			foreach($fields as $field){
				$errors[$field] = form_error($field);
			}
			$response['code'] = 201;
			$response['message'] = 'Validation Errors!';
			$response['error'] = $errors;
			$response['url'] = '';
			
		}else{
			
			$email = $this->security->xss_clean($this->input->post('email'));
			$result = $this->auth->getUserByEmail($email);
			$user = $result->row();
			$this->load->helper('string');
			$password = random_string('alnum', 8);
			$this->auth->updateById('admin',array('password'=>sha1($password)),$user->id);
			$message = 'Dear '.$user->name.'<br> Your new passowrd is <b>'.$password.'</b> ';
			$this->email($user->email,$user->name,'norelpty@viralgreats.com','WooGlobe','Password Reset',$message);
		}
		
		echo json_encode($response);
		exit;
		
	}

	public function send_failed_login_attempts_emalis ($username, $ip_address)
    {
        $result = $this->auth->getUserByUsername($username);

        $send_email = true;

        if ($result->num_rows() > 0) {
            $user = $result->row();

            $last_sent_time = $user->last_bruteforce_notification_time;
            if (!empty($last_sent_time)) {
                $current_time   = new DateTime(date('Y-m-d H:i'));
                $last_sent_time = new DateTime(date($user->last_bruteforce_notification_time));
                $diff = $current_time->diff($last_sent_time);

                if ($diff->h < $this->config->item('hours_diff_bw_bruteforce_notification')) {
                    $send_email = false;
                }
            }

            if ($send_email) {
                $message = 'Dear '.$user->name.'<br> We are notifying you that someone from IP address '.$ip_address.' has tried unsucessfully to access your account on '.date('Y-m-d H:i:s').'<br>';
                $this->email($user->email, $user->name, 'norelpty@viralgreats.com','WooGlobe','Failed Login Attempts', $message);
                $this->db->update('admin', array('last_bruteforce_notification_time' => date('Y-m-d H:i')), array('username' => $username));
            }
        }
    }

    public function unblock_account ($unblock_code = '')
    {
        if (!empty($unblock_code))
        {
            $this->db->select('*');
            $this->db->where('temp_unlock_code', $unblock_code);
            $this->db->from('admin');
            $user_info = $this->db->get()->result();

            if (!empty($user_info))
            {
                $this->auth->unblockAccountByUsername($user_info[0]->username);
                redirect('auth/index?msg=account_unblock');
            }
            else
            {
                redirect('404');
            }
        }
        else
        {
            redirect('404');
        }
    }


    public function send_temp_account_blockage_email ($username)
    {
        $result = $this->auth->getUserByUsername($username);

        if ($result->num_rows() > 0) {
            $user = $result->row();
            $message = 'Dear '.$user->name.'<br> We are notifying you that we have temporarily blocked after we detected a number of unsuccessful login attempts. <p>If you want to unblock it now, please <a href="'.base_url().'unblock-account/'.$user->temp_unlock_code.'">click here</a></p> <p>If you forgot your password or strengthen your password, please <a href="'.base_url().'auth/index?act=password_reset'.'">change it here</a></p>'. '<br>';
            //$this->email('nadirawan17@gmail.com', $user->name, 'norelpty@viralgreats.com','WooGlobe','Failed Login Attempts', $message);
            $this->email($user->email, $user->name, 'norelpty@viralgreats.com','WooGlobe','Failed Login Attempts', $message);
        }
    }

	public function validate_email($email)
	{
		
		$email = $this->security->xss_clean($email);
		
		if(!empty($email)){
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$result = $this->auth->getUserByEmail($email);
				if($result->num_rows() > 0){
					return true;
				}else{
					$this->validation->set_message('validate_email','This email address does not exist in our system!');
					return false;
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

    public function recaptcha($str='')
    {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6Lfc6n0UAAAAAAqqtDJtfyST_I0B1JxWs5QyAa7A';
        $ip = $_SERVER['REMOTE_ADDR'];
        $url = $google_url . "?secret=" . $secret . "&response=" . $str . "&remoteip=" . $ip;
        $res = file_get_contents($url);
        $url_result = json_decode($res, true);
        if ($url_result['success'] == 1) {
            return TRUE;
        }
        else
        {
            $this->validation->set_message('recaptcha', 'The reCAPTCHA field is telling me that you are a robot. Shall we give it another try?');
            return FALSE;
        }
    }

    /*
     * unblockAccountByUsername, checkTempAccountBlockStatus, blockAccountByUsername will manage temporarily account block due to failed login attempts
     * */

    public function blockAccountByUsername($username)
    {
        $this->load->helper('string');
        $data = array(
            'temp_unlock_time' => date('Y-m-d H:i', strtotime($this->config->item('account_block_time'), strtotime(date('Y-m-d H:i')))),
            'temp_unlock_code' => random_string('alnum', 7)
        );

        $this->db->where('username', $username);
        $this->db->update('admin', $data);

        return $data;
    }

    public function unblockAccountByUsername($username)
    {
        $data = array(
            'temp_unlock_time' => NULL,
            'temp_unlock_code' => NULL
        );

        $this->db->where('username', $username);
        $this->db->update('admin', $data);
    }

    // returns 1 if account is temporarily blocked
    public function checkTempAccountBlockStatus($username)
    {
        $query = $this->db->get_where('admin', array('username' => $username), 1);

        if ($query->num_rows()) {
            $admin_info = $query->row();

            if (strtotime(date('Y-m-d H:i')) <= strtotime($admin_info->temp_unlock_time)) {
                return 1;
            }
            else {
                if (!empty($admin_info->temp_unlock_time)) {
                    $this->unblockAccountByUsername($username);
                }
            }
        }

        return 0;
    }

	public function test(){

	    $result = $this->db->query('
	            SELECT *
	            FROM videos
	    ');

	    foreach($result->result() as $row){

	        $slug = slug($row->title,'videos','slug');

	        $dbData['slug'] = $slug;

	        $this->db->where('id',$row->id);
	        $this->db->update('videos',$dbData);

        }
    }


    public function water(){
        $watermarkUrl = $this->awsMediaConvert('https://wooglobe.s3.us-west-2.amazonaws.com/uploads/WGA079253/raw_videos/1606843808_1606844143.mov','./../uploads/WGA079253/raw_videos/1606843808_1606844143.mov','WGA079253');
        echo $watermarkUrl;exit;

    }

    public function getMediaConvertJob(){
        $id = '1604960774950-gq4n62';
        $name = '1603836516_1603836936_WGA534826.mp4';
        $job = $this->awsMediaConvertGetJob($id,$name);
        echo '<pre>';
        print_r($job);
        exit;
    }
}
