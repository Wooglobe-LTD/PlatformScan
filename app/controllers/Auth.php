<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends APP_Controller {

    private $client;
    private $fb;

    public function __construct() {
        parent::__construct();
        //auth_verify();
        $this->load->model('Auth_Model','auth');
        define('SCOPES', 'https://www.googleapis.com/auth/userinfo.email');
        $this->client = new Google_Client();
        $this->client->setApplicationName(APPLICATION_NAME);
        $this->client->setScopes(SCOPES);
        $this->client->setAuthConfig(CLIENT_SECRET_PATH);
        $this->client->setAccessType('offline');
        $this->data['gurl'] = $this->client->createAuthUrl();
        $this->fb = new Facebook\Facebook(array(
            'app_id' => '1999523953669799', // Replace {app-id} with your app id
            'app_secret' => '54709aa880364b6246486fac92a6896c',
            'default_graph_version' => 'v3.0',
        ));
        $helper = $this->fb->getRedirectLoginHelper();

        $permissions = array('email'); // Optional permissions
        $loginUrl = $helper->getLoginUrl(base_url('fb_callback'), $permissions);
        $this->data['fburl'] = $loginUrl;

//        if (isset($_GET['act']) && $_GET['act'] == 'password_reset')
//        {
//            $this->sess->set_userdata('act', 'password_reset');
//            $this->data['open_password_reset'] = true;
//        }

        if (isset($_GET['msg']) && $_GET['msg'] == 'account_unblock')
        {
            $this->data['account_unblock_msg'] = 'Your account was successfully unblocked';
        }

    }
    public function index($param = '')
    {
        //redirect('/');
        $this->load->helper('cookie');
        $email = get_cookie('email');
        $password = get_cookie('password');
        if(!empty($email) && !empty($password)){
            $result = $this->auth->login($email,$password);
            if($result->num_rows() > 0){
                $user = $result->row();
                $sess = array(
                    'isClientLogin'=>TRUE,
                    'clientId'=>$user->id,
                    'clientName'=>$user->full_name,
                    'clientEmail'=>$user->email,
                    'clientMobile'=>$user->mobile,
                );
                if($param == 'feed'){
                    $sess['isPartnerLogin'] = TRUE;
                }
                $this->sess->set_userdata($sess);
                if(empty($param) or $param != 'feed'){
                    redirect('home');
                }else{
                    redirect($_SERVER['HTTP_REFERER']);
                }

            }
        }
        $fail_try = 0;
        if($this->sess->userdata('fail_try') != ''){
            $fail_try = $this->sess->userdata('fail_try');
        }
        $this->data['title'] = 'Log In';
        $this->data['js'][] = 'signin';
        $this->data['fail_try'] = $fail_try;
        $view_name = 'login';
        if($param == 'feed') {
            $view_name = 'partner_login';
        }
        $this->data['content'] = $this->load->view($view_name, $this->data, true);
        $this->load->view('common_files/template',$this->data);
    }

    public function partner($param = 'feed')
    {
        //redirect('/');
        $this->load->helper('cookie');
        $email = get_cookie('email');
        $password = get_cookie('password');
        if(!empty($email) && !empty($password)){
            $result = $this->auth->login_partner($email,$password);
            if($result->num_rows() > 0){
                $user = $result->row();
                $sess = array(
                    'isClientLogin'=>TRUE,
                    'clientId'=>$user->id,
                    'clientName'=>$user->full_name,
                    'clientEmail'=>$user->email,
                    'clientMobile'=>$user->mobile,
                );
                if($param == 'feed'){
                    $sess['isPartnerLogin'] = TRUE;
                }
                $this->sess->set_userdata($sess);
                if(empty($param) or $param != 'feed'){
                    redirect('home');
                }else{
                    redirect($_SERVER['HTTP_REFERER']);
                }

            }
        }
        $fail_try = 0;
        if($this->sess->userdata('fail_try') != ''){
            $fail_try = $this->sess->userdata('fail_try');
        }
        $this->data['title'] = 'Log In';
        $this->data['js'][] = 'partner_signin';
        $this->data['fail_try'] = $fail_try;
        $view_name = 'login';
        if($param == 'feed') {
            $view_name = 'partner_login';
        }

        $view_name = 'partner_login';


        $this->data['content'] = $this->load->view($view_name, $this->data, true);
        $this->load->view('common_files/template',$this->data);
    }

    public function signup()
    {
        $this->data['title'] = 'Sign Up';
        $this->data['js'][] = 'signup';
        $this->data['content'] = $this->load->view('signup',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function forgot_password()
    {
        $this->data['title'] = 'Forgot Password';
        $this->data['js'][] = 'forgot';
        $this->data['content'] = $this->load->view('forgot_password',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function login()
    {
        $response = array();
        $fail_try = 0;
        if($this->sess->userdata('fail_try') != ''){
            $fail_try = $this->sess->userdata('fail_try');
        }
        $response['code'] = 200;
        $response['message'] = 'Sign In Successfully!';
        $response['error'] = '';
        $response['url'] = base_url('/');
        $response['fail_try'] = $fail_try;
        /*echo json_encode($response);
        exit;*/
        $this->validation->set_rules('email','Email Address','trim|required|valid_email|callback_validate_user');
        $email = $this->input->post('email');
        if($fail_try > 2){
            $this->send_failed_login_attempts_emalis($email, $_SERVER['REMOTE_ADDR']);
            $this->validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha');
        }

        $this->validation->set_rules('password','Password','trim|required');

        // if failed attempts exceeds allowed limit and account is not already blocked
        if (($fail_try > $this->config->item('allow_failed_login_attempts')) && !$this->auth->checkTempAccountBlockStatus($email)){
            // reset failed attempts after block
            $this->sess->set_userdata('fail_try',0);
            $this->auth->blockAccountByEmail($email);
            $this->send_temp_account_blockage_email($email);
        }

        if($this->validation->run() === false){
            $fail_try = 0;
            if($this->sess->userdata('fail_try') != ''){
                $fail_try = $this->sess->userdata('fail_try');
            }
            $fields = array('email','password');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $errors['g-recaptcha-response-login'] = form_error('g-recaptcha-response');
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';
            $response['fail_try'] = $fail_try;

        }else {
            if ($this->auth->checkTempAccountBlockStatus($email)){
                $response['code'] = 403;
                $response['account_blocked'] = 1;
                $response['message'] = 'Your account is temporarily blocked due to unsuccessful login attempts. It will take some time to restore it.';
                $response['fail_try'] = $fail_try;
                $response['error']   = 'Temp account blocked';

                echo json_encode($response);
                exit;
            }

            $this->load->helper('cookie');
            $rememberme = $this->security->xss_clean($this->input->post('rememberme'));
            $password = md5($this->security->xss_clean($this->input->post('password')));
            $username = $this->security->xss_clean($this->input->post('email'));
            if ($rememberme != '') {
                set_cookie('email', $username, 260000);
                set_cookie('password', $password, 260000);
            }
            $this->sess->unset_userdata('fail_try');
            $result = $this->auth->login($username, $password);
            $user = $result->row();

            if (!empty($user)){
                $sess = array(
                    'isClientLogin' => TRUE,
                    'clientId' => $user->id,
                    'clientName' => $user->full_name,
                    'clientEmail' => $user->email,
                    'clientMobile' => $user->mobile,
                    'isPartnerLogin' => TRUE,
                );
                //$sess['isPartnerLogin'] = TRUE;
                $this->sess->set_userdata($sess);
            }

            if($user->is_first_time_login == 1){
                $id = $user->id;
                $response['url'] = 'password-new/'.$id.'/'.$user->role_id;
            }
            else {
                //$response['url'] = $this->data['url']; //Home URL
                $response['url'] = $this->data['url']."dashboard";
            }
        }
        echo json_encode($response);
        exit;
    }

    public function partner_login ()
    {
        /* echo "<pre>";
        print_r($this->input->post());
        echo "</pre>";
        exit; */

        $response = array();
        $fail_try = 0;
        if($this->sess->userdata('fail_try') != ''){
            $fail_try = $this->sess->userdata('fail_try');
        }
        $response['code'] = 200;
        $response['message'] = 'Sign In Successfully!';
        $response['error'] = '';
        $response['url'] = base_url('/');
        $response['fail_try'] = $fail_try;
        /*echo json_encode($response);
        exit;*/
        $this->validation->set_rules('email','Email Address','trim|required|valid_email|callback_validate_partner');
        $email = $this->input->post('email');
        if($fail_try > 2){
            $this->send_failed_login_attempts_emalis($email, $_SERVER['REMOTE_ADDR']);
            //$this->validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha');
        }

        $this->validation->set_rules('password','Password','trim|required');

        // if failed attempts exceeds allowed limit and account is not already blocked
        if (($fail_try > $this->config->item('allow_failed_login_attempts')) && !$this->auth->checkTempPartnerAccountBlockStatus($email)){
            // reset failed attempts after block
            $this->sess->set_userdata('fail_try',0);
            $this->auth->blockAccountByEmail($email);
            $this->send_temp_account_blockage_email($email);
        }

        if($this->validation->run() === false){
            $fail_try = 0;
            if($this->sess->userdata('fail_try') != ''){
                $fail_try = $this->sess->userdata('fail_try');
            }
            $fields = array('email','password');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            //$errors['g-recaptcha-response-login'] = form_error('g-recaptcha-response');
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';
            $response['fail_try'] = $fail_try;

        }else {
            if ($this->auth->checkTempPartnerAccountBlockStatus($email)){
                $response['code'] = 403;
                $response['account_blocked'] = 1;
                $response['message'] = 'Your account is temporarily blocked due to unsuccessful login attempts. It will take some time to restore it.';
                $response['fail_try'] = $fail_try;
                $response['error']   = 'Temp account blocked';

                echo json_encode($response);
                exit;
            }

            $this->load->helper('cookie');
            $rememberme = $this->security->xss_clean($this->input->post('rememberme'));
            //$password = sha1($this->security->xss_clean($this->input->post('password')));
            $password = md5($this->security->xss_clean($this->input->post('password')));
            $username = $this->security->xss_clean($this->input->post('email'));
            if ($rememberme != '') {
                set_cookie('email', $username, 260000);
                set_cookie('password', $password, 260000);
            }
            $this->sess->unset_userdata('fail_try');
            $result = $this->auth->login_partner($username, $password);

            $user = $result->row();

            if (!empty($user)){
                $sess = array(
                    'isClientLogin' => TRUE,
                    'clientId' => $user->id,
                    'clientName' => $user->full_name,
                    'clientEmail' => $user->email,
                    'clientMobile' => $user->mobile,
                    'isPartnerLogin' => TRUE,
                );
                //$sess['isPartnerLogin'] = TRUE;
                $this->sess->set_userdata($sess);
            }

            if($user->is_first_time_login == 1){
                $id = $user->id;
                $response['url'] = 'password-new/'.$id.'/'.$user->role_id;
            }
            else {
                $redirect_url = $this->data['url']."dashboard";
                if ($this->sess->has_userdata('redirect_after_login'))
                {
                    $redirect_url = $this->sess->userdata('redirect_after_login');
                }
                //$response['url'] = $this->data['url']; //Home URL
                $response['url'] = $redirect_url;

            }
        }
        echo json_encode($response);
        exit;
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
    public function password_new($id, $role_id = '1')
    {
        $this->data['id'] = $id;
        $this->data['role_id'] = $role_id;
        $this->data['title'] = 'Change Password';
        $this->data['js'][] = 'c_password';
        $this->data['content'] = $this->load->view('first_time_change_password',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function c_password(){

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Password Changed Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('password','Password','trim|required|regex_match[/(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/]');
        $this->validation->set_message('regex_match','Password must contain UpperCase, LowerCase, Number, SpecialChar and min 8 Chars.');

        if($this->validation->run() === false){

            $fields = array('password');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';
        }
        else {

            $post = $this->input->post();
            if (isset($post['role-id']) && $post['role-id'] == '2')
            {
                $result = $this->auth->update_partner_password($post);
            }
            else
            {
                $result = $this->auth->update_password($post);
            }
            $data = $result->row();
            $sess = array(
                'isClientLogin' => TRUE,
                'clientId' => $data->id,
                'clientName' => $data->full_name,
                'clientEmail' => $data->email,
            );
            $this->sess->set_userdata($sess);
            $redirect_url = base_url();
            if ($this->sess->has_userdata('redirect_after_login'))
            {
                $redirect_url = $this->sess->has_userdata('redirect_after_login');
            }
            $response['url'] = $redirect_url;
        }
        echo json_encode($response);
        exit;
    }

    public function validate_user($email)
    {
        $fail_try = 0;
        if($this->sess->userdata('fail_try') != ''){
            $fail_try = $this->sess->userdata('fail_try');
        }
        $password = md5($this->security->xss_clean($this->input->post('password')));
        $email = $this->security->xss_clean($email);

        if(!empty($email)){
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result = $this->auth->login($email, $password);
                if ($result->num_rows() > 0) {
                    return true;
                } else {
                    $this->validation->set_message('validate_user', 'Invalid Email Address or Password!');
                    $fail_try++;
                    $this->sess->set_userdata('fail_try',$fail_try);
                    return false;
                }
            }else{
                $this->validation->set_message('validate_user', 'Please enter the valid email address!');
                return false;
            }
        }else{
            $this->validation->set_message('validate_user','The Email Address field is required.');
            return false;
        }

    }

    public function validate_partner($email)
    {
        $fail_try = 0;
        if($this->sess->userdata('fail_try') != ''){
            $fail_try = $this->sess->userdata('fail_try');
        }
        //$password = sha1($this->security->xss_clean($this->input->post('password')));
        $password = md5($this->security->xss_clean($this->input->post('password')));
        $email = $this->security->xss_clean($email);

        if(!empty($email)){
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result = $this->auth->login_partner($email, $password);
                if ($result->num_rows() > 0) {
                    return true;
                } else {
                    $this->validation->set_message('validate_partner', 'Invalid Email Address or Password!');
                    $fail_try++;
                    $this->sess->set_userdata('fail_try',$fail_try);
                    return false;
                }
            }else{
                $this->validation->set_message('validate_partner', 'Please enter the valid email address!');
                return false;
            }
        }else{
            $this->validation->set_message('validate_partner','The Email Address field is required.');
            return false;
        }
    }

    public function validate_user_ajax()
    {

        $password = md5($this->security->xss_clean($this->input->post('password')));
        $email = $this->security->xss_clean($this->input->post('email'));
        $fail_try = 0;
        if($this->sess->userdata('fail_try') != ''){
            $fail_try = $this->sess->userdata('fail_try');
        }


        $result = $this->auth->login($email,$password);
        //echo $this->db->last_query();exit;
        if($result->num_rows() > 0){
            echo json_encode(1);
        }else{
            http_response_code(404);
            $fail_try++;
            $this->sess->set_userdata('fail_try',$fail_try);
            $response['try'] = $fail_try;
            echo json_encode($response);
        }
        exit;
    }

    public function register()
    {
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Sign Up Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('full_name','Full Name','trim|required');
        $this->validation->set_rules('email','Email Address','trim|required|valid_email|is_unique[users.email]');
        $this->validation->set_rules('password','Password','trim|required|regex_match[/(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/]');
        $this->validation->set_message('is_unique','This email address already exist in our system.');
        $this->validation->set_message('regex_match','Password must contain UpperCase, LowerCase, Number, SpecialChar and min 8 Chars.');

        if($this->validation->run() === false){

            $fields = array('full_name','password','email');
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
            $dbData = $this->security->xss_clean($this->input->post());
            $token = random_string('alnum', 10);
            unset($dbData['cpassword']);
            unset($dbData['rememberme']);
            unset($dbData['signup-form-submit']);
            $dbData['password'] = md5($dbData['password']);

            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['role_id'] = 1;
            $dbData['verify_token'] = $token;
            $url = $this->urlmaker->shorten(base_url().'verify_email/'.$token);
            $this->db->insert('users',$dbData);
            $message = 'Dear '.$dbData['full_name'].'<br> <a href="'.$url.'">Click Here</a>  to confirm your email address.';
            $this->email($dbData['email'],$dbData['full_name'],'norelpty@viralgreats.com','WooGlobe','Email Address Confirmation',$message);
            $response['url'] = $this->data['url'].'login';
            $this->sess->set_flashdata('msg','Please check your mail inbox to verify your mail address.');
        }

        echo json_encode($response);
        exit;
    }

    public function reset_password(){

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Password reset successfully! Please check your mail inbox.';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('email','Email Address','trim|callback_validate_email|valid_email|required');
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
            $token = random_string('alnum', 8);
            $code = random_string('numeric', 6);
            $time = date('Y-m-d H:i:s');
            $this->auth->updateById('users',array('verify_token'=>$token,'otp'=>$code,'token_expiry_time'=>$time),$user->id);
            $message = 'Dear '.$user->full_name.'<br> Your reset passowrd verification code is <b>'.$code.'</b> '.'This code will expire in 15 minutes';
            $this->email($user->email,$user->full_name,'norelpty@viralgreats.com','WooGlobe','Password Reset verification code',$message);
            $this->auth->unblockAccountByEmail($user->email);
            $response['url'] = base_url('verify_code/'.$token);
            $this->sess->set_flashdata('msg','Please check your mail inbox for OTP code.');
        }

        echo json_encode($response);
        exit;

    }


    public function regenerate_otp()
    {
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Code sent Successfully! Please check your mail inbox.';
        $response['error'] = '';
        $response['url'] = '';

        $get = $this->security->xss_clean($this->input->post('token'));
        $result = $this->auth->getUserByToken($get);
        $user = $result->row();
        $this->load->helper('string');
        $token = random_string('alnum', 8);
        $code = random_string('numeric', 6);
        $time = date('Y-m-d H:i:s');
        $this->auth->updateById('users',array('verify_token'=>$token,'otp'=>$code,'token_expiry_time'=>$time),$user->id);
        $message = 'Dear '.$user->full_name.'<br> Your reset passowrd verification code is <b>'.$code.'</b> '.'This code will expire in 15 minutes';
        $this->email($user->email,$user->full_name,'norelpty@viralgreats.com','WooGlobe','Password Reset verification code',$message);
        $response['url'] = base_url('verify_code/'.$token);
        $this->sess->set_flashdata('msg','Please check your mail inbox for OTP code.');

        echo json_encode($response);
        exit;
    }

    public function verify_code($token){

        //   $time = date('Y-m-d H:i:s');
        $result = $this->auth->getUserByVerifyToken($token);

        if($result->num_rows() == 0){

            $this->sess->set_flashdata('err','Link expired');
            redirect('login');

        }

        $this->data['title'] = 'Verify OTP';
        $this->data['js'][] = 'otp';
        $this->data['token'] = $token;
        $this->data['content'] = $this->load->view('otp',$this->data,true);
        $this->load->view('common_files/template',$this->data);

    }

    public function send_failed_login_attempts_emalis ($email, $ip_address)
    {
        $result = $this->auth->getUserByEmail($email);

        /*         if ($result->num_rows() > 0) {
                    $user = $result->row();
                    $message = 'Dear '.$user->full_name.'<br> We are notifying you that someone from IP address '.$ip_address.' has tried unsucessfully to access your account on '.date('Y-m-d H:i:s').'<br>';
                    //email($to,$to_name = '',$from,$from_name = '',$subject,$message
                    $this->email($user->email, $user->full_name, 'norelpty@viralgreats.com','WooGlobe','Failed Login Attempts', $message);
                } */

        $send_email = true;

        if ($result->num_rows() > 0) {
            $user = $result->row();

            $last_sent_time = $user->last_bruteforce_notification_time;
            if (!empty($last_sent_time)) {
                $current_time = new DateTime(date('Y-m-d H:i'));
                $last_sent_time = new DateTime(date($user->last_bruteforce_notification_time));
                $diff = $current_time->diff($last_sent_time);

                if ($diff->h < $this->config->item('hours_diff_bw_bruteforce_notification')) {
                    $send_email = false;
                }
            }

            if ($send_email) {
                $message = 'Dear ' . $user->full_name . '<br> We are notifying you that someone from IP address ' . $ip_address . ' has tried unsucessfully to access your account on ' . date('Y-m-d H:i:s') . '<br>';
                $this->email($user->email, $user->full_name, 'norelpty@viralgreats.com', 'WooGlobe', 'Failed Login Attempts', $message);
                $this->db->update('users', array('last_bruteforce_notification_time' => date('Y-m-d H:i')), array('email' => $email));
            }
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

    public function validate_email_ajax()
    {

        $email = $this->security->xss_clean($this->input->post('email'));

        if(!empty($email)){
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result = $this->auth->getUserByEmail($email);
                if($result->num_rows() > 0){
                    echo json_encode(1);
                }else{
                    http_response_code(404);
                    echo json_encode(1);
                }
            }else{
                http_response_code(404);
                echo json_encode(1);
            }
        }else{
            http_response_code(404);
            echo json_encode(1);
        }
        exit;

    }

    public function validate_otp_ajax()
    {

        $otp = $this->security->xss_clean($this->input->post('otp'));
        $token = $this->security->xss_clean($this->input->post('token'));
        $result = $this->auth->getUserByVerifyTokenAndOTP($token,$otp);

        if($result->num_rows() > 0){
            echo json_encode(1);
        }else{
            http_response_code(404);
            echo json_encode(1);
        }
        exit;


    }

    public function check_email(){

        $email = $this->security->xss_clean($this->input->post('email'));
        $result = $this->auth->getUserByEmailWithNoPassword($email);
        $result_block = $this->auth->getBlockUserByEmail($email);
        $error = 'success';
        http_response_code(200);
        if($result_block->num_rows() > 0){
            $error = 'error';
            http_response_code(200);
        } else if($result->num_rows() > 0){
            $error = 'exists';
            http_response_code(404);
        }
        echo json_encode($error);
        exit;
    }
    public function check_paypal_email(){

        $email = $this->security->xss_clean($this->input->post('email'));
        $result = $this->auth->getUserByPayPalEmail($email);
        $error = 'success';
        if($result->num_rows() > 0){
            $error = 'error';
            http_response_code(404);
        }
        echo json_encode($error);
        exit;
    }

    public function verify_email($token){

        $token = $this->security->xss_clean($token);

        $result = $this->auth->getUserByVerifyToken($token);
        if($result->num_rows() > 0){

            $result = $result->row();
            $dbData['status'] = 1;
            $dbData['verify_token'] = '';
            $dbData['role_id'] = 1;
            $this->db->where('id',$result->id);
            $this->db->update('users',$dbData);
            $this->sess->set_flashdata('msg','Your email address confirm successfully! Now you can Log In.');
            redirect('login');
        }else{
            $this->sess->set_flashdata('err','Link expired');
            redirect('login');
        }

    }

    public function verified_otp(){

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'OTP Verifed successfully.';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('otp','OTP','trim|required|integer|callback_validate_otp');

        if($this->validation->run() === false){

            $fields = array('otp');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{
            $otp = $this->security->xss_clean($this->input->post('otp'));
            $token = $this->security->xss_clean($this->input->post('token'));
            $response['url'] = base_url('new-password/'.$token.'/'.$otp);
        }

        echo json_encode($response);
        exit;

    }

    public function validate_otp()
    {

        $otp = $this->security->xss_clean($this->input->post('otp'));
        $token = $this->security->xss_clean($this->input->post('token'));

        $time = date('Y-m-d H:i:s');
        $result = $this->auth->getUserByVerifyTokenAndOTPAndTime($token,$otp,$time);

        if($result){
            return true;
        }else{
            $this->validation->set_message('validate_otp','This field is required.');
            return false;
        }


    }



    public function new_password($token,$otp){

        $token = $this->security->xss_clean($token);
        $otp = $this->security->xss_clean($otp);

        $time = date('Y-m-d H:i:s');

        $result = $this->auth->getUserByVerifyTokenAndOTPAndTime($token,$otp,$time);

        if(!$result){
            $this->sess->set_flashdata('err','Invalid Link/Token Expired');
            redirect('login');
        }

        $this->data['title'] = 'New Password';
        $this->data['js'][] = 'password';
        $this->data['token'] = $token;
        $this->data['otp'] = $otp;
        $this->data['content'] = $this->load->view('new_password',$this->data,true);
        $this->load->view('common_files/template',$this->data);

    }

    public function update_password()
    {
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Your Password set succssfully!';
        $response['error'] = '';
        $response['url'] = '';
        $otp = $this->security->xss_clean($this->input->post('otp'));
        $token = $this->security->xss_clean($this->input->post('token'));
        $result = $this->auth->getUserByVerifyTokenAndOTP($token,$otp);

        if($result->num_rows() == 0){
            $response['code'] = 201;
            $response['message'] = 'Invalid Link!';
            $response['error'] = 'Invalid Link!';
            $response['url'] = base_url('login');
            echo json_encode($response);
            exit;
        }

        $result = $result->row();
        $this->validation->set_rules('password','Password','trim|required|regex_match[/(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/]');
        $this->validation->set_message('regex_match','Password must contain UpperCase, LowerCase, Number, SpecialChar and min 8 Chars.');

        if($this->validation->run() === false){

            $fields = array('password');
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
            unset($dbData['cpassword']);
            unset($dbData['signup-form-submit']);
            $dbData['password'] = md5($dbData['password']);
            $dbData['token'] = '';
            $dbData['otp'] = 0;

            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id',$result->id);
            $this->db->update('users',$dbData);

            // after reseting password, get user email and unblock this
            $this->db->select('email');
            $this->db->from('users');
            $this->db->where('id', $result->id);
            $user_query = $this->db->get();
            $user_row = $user_query->row();
            if (!empty($user_row)) {
                $this->auth->unblockAccountByEmail($user_row->email);
            }
            // after reseting password, get user email and unblock this

            $url = $this->urlmaker->shorten(base_url().'login/');
            $url = base_url().'login/';
            $message = 'Dear '.$result->full_name.'<br> Your Password update successfully. Now you can <a href="'.$url.'">Log In</a>.';
            $this->email($result->email,$result->full_name,'norelpty@viralgreats.com','WooGlobe','Password Updated',$message);
            $response['url'] = $this->data['url'].'login';
            $this->sess->set_flashdata('msg','Your Password reset succssfully!.');
        }

        echo json_encode($response);
        exit;
    }

    public function gm_callback(){
        $authCode = trim($this->input->get('code'));
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
        $this->client->setAccessToken($accessToken);
        $service = new Google_Service_Oauth2($this->client);
        //$user = 'me';
        $results = $service->userinfo->get();

        $result = $this->auth->getUserByEmail($results->email);
        if($result->num_rows() > 0){
            $result = $result->row();
            $user_id = $result->id;
            $name = $result->full_name;
            $email = $result->email;
        }else{
            $user_id = $results->id;
            $name = $results->name;
            $email = $results->email;
            $dbData['full_name'] = $name;
            $dbData['email'] = $email;
            $dbData['picture'] =$results->picture;
            $dbData['status'] =1;
            $dbData['role_id'] =1;
            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $this->db->insert('users',$dbData);
            $user_id = $this->db->insert_id();
        }

        $sess = array(
            'isClientLogin'=>TRUE,
            'clientId'=>$user_id,
            'clientName'=>$name,
            'clientEmail'=>$email,
        );
        $this->sess->set_userdata($sess);
        redirect('home');

    }

    public function fb_callback(){
        $helper = $this->fb->getRedirectLoginHelper();
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        try {
            // Get the \Facebook\GraphNodes\GraphUser object for the current user.
            // If you provided a 'default_access_token', the '{access-token}' is optional.
            $response = $this->fb->get('/me?fields=id,name,email,picture', $accessToken);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $me = $response->getGraphUser();


        $result = $this->auth->getUserByEmail($me->getEmail());
        if($result->num_rows() > 0){
            $result = $result->row();
            $user_id = $result->id;
            $name = $result->full_name;
            $email = $result->email;
        }else{
            $user_id = $me->getId();
            $name = $me->getName();
            $email = $me->getEmail();
            $dbData['full_name'] = $name;
            $dbData['email'] = $email;
            $dbData['picture'] =$me->getPicture()->getUrl();
            $dbData['status'] =1;
            $dbData['role_id'] =1;
            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $this->db->insert('users',$dbData);
            $user_id = $this->db->insert_id();
        }

        $sess = array(
            'isClientLogin'=>TRUE,
            'clientId'=>$user_id,
            'clientName'=>$name,
            'clientEmail'=>$email,
        );
        $this->sess->set_userdata($sess);
        redirect('home');

    }

    public function logout(){
        $this->sess->sess_destroy();
        redirect($this->data['url']);
    }

    public function new_login($token)
    {
        $time = date('Y-m-d H:i:s');
        $result = $this->auth->getUserByVerifyToken($token);
        $res =  $result->row_array();
        /*echo '<pre>';
        print_r($res);
        exit;*/
        if(isset($res['token_expiry_time']) && !empty($res['token_expiry_time'])) {
            $someDate = new \DateTime($res['token_expiry_time']);
            $now = new \DateTime();

            if($someDate->diff($now)->days > 30)
            {
                $this->sess->set_flashdata('err','Link expired');
                redirect('login');
            }
        }
        if(!empty($res['password'])){

            redirect('dashboard');
        }
        $this->data['title'] = 'New Password';
        $this->data['js'][] = 'password';
        $this->data['token'] = $token;
        $this->data['email'] = $res['email'];
        $this->data['content'] = $this->load->view('new_login',$this->data,true);
        $this->load->view('common_files/template',$this->data);

    }
    public function new_update_password()
    {
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Your Password reset succssfully!';
        $response['error'] = '';
        $response['url'] = '';
        $token = $this->security->xss_clean($this->input->post('token'));
        $result = $this->auth->getUserByVerifyToken($token);

        if($result->num_rows() == 0){
            $response['code'] = 204;
            $response['message'] = 'Invalid Link!';
            $response['error'] = 'Invalid Link!';
            $response['url'] = base_url('home');
            echo json_encode($response);
            exit;
        }

        $result = $result->row();
        if(!empty($result->token_expiry_time)) {
            $someDate = new \DateTime($result->token_expiry_time);
            $now = new \DateTime();

            if($someDate->diff($now)->days > 30)
            {
                $response['code'] = 204;
                $response['message'] = 'Invalid Link!';
                $response['error'] = 'Invalid Link!';
                $response['url'] = base_url('home');
                echo json_encode($response);
                exit;
            }
        }
        $this->validation->set_rules('password','Password','trim|required|regex_match[/(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/]');
        $this->validation->set_message('regex_match','Password must contain UpperCase, LowerCase, Number, SpecialChar and min 8 Chars.');

        if($this->validation->run() === false){

            $fields = array('password');
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
            unset($dbData['cpassword']);
            unset($dbData['passwordset-form-submit']);
            $dbData['password'] = md5($dbData['password']);
            $dbData['token'] = '';
            $dbData['otp'] = 0;
            $dbData['is_first_time_login'] = 0;

            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $this->db->where('id',$result->id);
            $this->db->update('users',$dbData);
            $url = $this->urlmaker->shorten(base_url().'login/'.$token);
            $email_temp = $this->app->getTemplateByShortCode('password_set');
            action_add(0,0,$result->id,0 ,0,'Password set successfully');
            /*if($email_temp){

                $str = $email_temp->message;
                $subject = $email_temp->subject;
                $from = 'viral@wooglobe.com';

                $ids = array(
                    'users' => $result->id,
                );


                $message = dynStr($str, $ids);
                $message = str_replace('@LINK',$url,$message);
                $result1 = $this->email($result->email, $result->full_name, $from, 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

            }*/
            /*$message = 'Dear '.$result->full_name.'<br> Your Password has been set successfully. Now you can <a href="'.$url.'">Signin</a>.';
            $this->email($result->email,$result->full_name,'norelpty@viralgreats.com','WooGlobe','Password Set',$message);*/

            $sess = array(
                'isClientLogin'=>TRUE,
                'clientId'=>$result->id,
                'clientName'=>$result->full_name,
                'clientEmail'=>$result->email,
            );
            $this->sess->set_userdata($sess);

            $response['url'] = $this->data['url'].'dashboard';
            $this->sess->set_flashdata('msg','Your Password reset succssfully!.');
        }

        echo json_encode($response);
        exit;
    }

    public function send_temp_account_blockage_email ($email)
    {
        $result = $this->auth->getUserByEmail($email);

        if ($result->num_rows() > 0) {
            $user = $result->row();
            $message = 'Dear '.$user->full_name.'<br> We are notifying you that we have temporarily blocked after we detected a number of unsuccessful login attempts. <p>If you want to unblock it now, please <a href="'.base_url().'unblock-account/'.$user->temp_unlock_code.'">click here</a></p> <p>If you forgot your password you can reset it <a href="'.base_url().'forgot-password'.'">here</a> or if you want to strengthen your password, you can visit this link to do it <a href="'.base_url().'change-password'.'">after logging in the system </a></p>'. '<br>';
            //$this->email('nadirawan17@gmail.com', $user->name, 'norelpty@viralgreats.com','WooGlobe','Failed Login Attempts', $message);
            $this->email($user->email, $user->full_name, 'norelpty@viralgreats.com','WooGlobe','Failed Login Attempts', $message);
        }
    }

    public function unblock_account ($unblock_code = '', $role_id = '1')
    {
        if (!empty($unblock_code))
        {
            $this->db->select('*');
            $this->db->where('temp_unlock_code', $unblock_code);
            $this->db->where('role_id', $role_id);
            $this->db->from('users');
            $user_info = $this->db->get()->result();

            if (!empty($user_info))
            {
                $this->auth->unblockAccountByEmail($user_info[0]->email);
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

    public function error_page()
    {

        $this->data['title'] = '404 Error';
        $this->data['js'][] = '404 Error';
        $this->data['content'] = $this->load->view('404_page',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }


    public function ghost($id)
    {
        //log_message("error",$this->data['url']);
        $result = $this->auth->ghost($id);
        $user = $result->row();

        if (!empty($user)){
            $sess = array(
                'isClientLogin' => TRUE,
                'clientId' => $user->id,
                'clientName' => $user->full_name,
                'clientEmail' => $user->email,
                'clientMobile' => $user->mobile,
                'isPartnerLogin' => TRUE,
            );
            //$sess['isPartnerLogin'] = TRUE;
            $this->sess->set_userdata($sess);
        }

        log_message("error",$this->data['url']);
        //redirect("http://localhost/wgplatform/index.php/dashboard");
        redirect($this->data['url']."dashboard");


    }
}
