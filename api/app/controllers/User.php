<?php

/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 2/13/2018
 * Time: 2:13 PM
 */
class User extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_Model', 'user');
        $this->load->model('Auth_Model', 'auth');
        $this->load->model('Upload', 'upload_video');
        $this->load->helper('string');
    }

    public function index()
    {
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'User Unblock Request Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        if($this->sess->userdata('isClientLogin') == '') {
            $this->validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
        }
        if($this->validation->run() == FALSE){

            $fields = array('email');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            header("Content-type: application/json");
            $response['url'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            $insert = $this->security->xss_clean($this->input->post());
            $response['post'] = $insert;
            if($this->sess->userdata('isClientLogin') != '') {
                $insert['email'] = $this->sess->userdata('clientEmail');
            }
            header("Content-type: application/json");
            if ($this->sess->userdata('isClientLogin')) {
                $to = $this->sess->userdata('clientEmail');
            } else {
                $to = $this->input->post('email');
            }

                $subject = 'Request for Unblocking User';
                $from = 'no-reply@wooglobe.com';
                $message = 'You request for unblock has been submitted successfully.<br> <b>We will get back to you shortly </b>';
                $result = $this->email($to, $to_name = '', $from, $from_name = '', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
                if($result){
                    $response['code'] = 200;
                    $response['message'] = 'We received your unblock request. We will get get back to you shortly !';
                    echo json_encode($response);
                    exit;
                }

        }


    }

}
