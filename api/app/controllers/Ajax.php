<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends APP_Controller {

	public function __construct() {
        parent::__construct();

        $this->load->model('Auth_Model','auth');
        $this->load->model('User_Model','user');
        $this->load->model('Location_Model','location');
    }
	
	





	public function get_states(){

        $country_id = $this->security->xss_clean($this->input->post('country_id'));
        $states = $this->location->getStatesByCountryId($country_id);
        $country = $this->location->getCountryById($country_id);
        $response['code'] = 200;
        $response['message'] = 'Get States Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $response['states'] = $states->result();
        $response['country'] = $country;
        echo json_encode($response);
        exit;

    }

    public function get_cities(){

        $state_id = $this->security->xss_clean($this->input->post('state_id'));
        $cities = $this->location->getCitiesByStateId($state_id);
        $state = $this->location->getStateById($state_id);
        $response['code'] = 200;
        $response['message'] = 'Get Cities Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $response['cities'] = $cities->result();
        $response['state'] = $state;
        echo json_encode($response);
        exit;

    }


    public function get_child_categories(){
        $category_id = $this->security->xss_clean($this->input->post('category_id'));
        $result = child_categories($category_id);
        $response['code'] = 200;
        $response['message'] = 'Get Categoris Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $response['categories'] = $result->result();
        echo json_encode($response);
        exit;
    }

    public function mailchimp(){
        $this->load->library('MailChimp');
        $list_id = '6685154911';
        $email = $this->security->xss_clean($this->input->post('email'));
        $result = $this->mailchimp->post("lists/$list_id/members", [
            'email_address' => $email,
            'status'        => 'subscribed',
        ]);
        //$result = $this->mailchimp->get('lists');
        $response['code'] = 200;
        $response['message'] = 'Subcribed Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $response['result'] = $result;
        echo json_encode($response);
        exit;
    }

    public function get_list_mailchimp(){
        $this->load->library('MailChimp');
        //$list_id = '7348afd807';
        //$email = $this->security->xss_clean($this->input->post('email'));
        $result = $this->mailchimp->get("lists");
        //$result = $this->mailchimp->get('lists');
        echo '<pre>';
        print_r($result);
        exit;
        $response['code'] = 200;
        $response['message'] = 'Subcribed Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $response['result'] = $result;
        echo json_encode($response);
        exit;
    }

    public function contact_lead()
    {

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Thanks for contacting us and our representative will be contact you ASAP!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('name','Full Name','trim|required');
        $this->validation->set_rules('email','Email','trim|required|valid_email');
        $this->validation->set_rules('subject','Subject','trim|required');
        $this->validation->set_rules('','Message','trim|required');
        $this->validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha');
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('name','email','subject','message','g-recaptcha-response');
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
            unset($dbData['g-recaptcha-response']);
            //$dbData['created_by'] = date('Y-m-d H:i:s');
            //$dbData['updated_at'] = date('Y-m-d H:i:s');
            $this->db->insert('contact_lead',$dbData);
            $id = $this->db->insert_id();
            $email_temp = $this->app->getTemplateByShortCode('contact_us_thanks');
            if($email_temp){

                $str = $email_temp->message;
                $subject = $email_temp->subject;
                $from = 'no-reply@wooglobe.com';

                $ids = array(
                    'contact_lead' => $id
                );


                $message = dynStr($str, $ids);
                $result = $this->email($dbData['email'], $dbData['name'], $from, $from_name = '', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                $staffSubject = 'New Contact Lead';
                $to = $this->data['setting']->site_email;
                $toName = 'Admin';
                $message = 'Hi Admin,<br> <strong>Name : </strong>'.$dbData['name'].'<br> <strong>Email : </strong>'.$dbData['email'].' <br> <strong>Subject : </strong> '.$dbData['subject'].' <br> <strong>Message : </strong>'.$dbData['message'];
                $result = $this->email($to, $toName, $from, $from_name = '', $staffSubject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

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
	
	
	
}
