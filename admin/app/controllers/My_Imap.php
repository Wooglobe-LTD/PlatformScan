<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 6/7/2018
 * Time: 10:14 AM
 */



class My_Imap extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->config->load('imap');
        $this->load->library('imap',$this->config->config['imap']);

        $this->load->model('Video_Lead_Model','lead');
        $this->load->model('Email_Template_Model','template');

    }

    public function index (){


        $messages = $this->imap->getAllMessages();

        echo '<pre>';
        print_r($messages);
        exit;
        foreach($messages as $message){
            $megs = $this->imap->get_message($message->uid);
        }


    }
    public function forword($message_id){
        $query = 'SELECT * FROM `gmail_conversation` 
                  WHERE message_id="'.$message_id.'" 
                  ORDER BY `converted_date_time`  DESC
                 ';
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            $megs=$result->result();
        }
        $return = array();
        foreach ($megs as $data){
            $return['from']['email'] = $data->from_email;
            $return['from']['name'] = $data->from_name;
            $return['to_email'] = $data->to_email;
            $return['date'] = date('m/d/Y H:i A',strtotime($data->converted_date_time));
            $return['subject'] = $data->subject;
            $return['body']['html'] = $data->message;
            $return['reply_link'] = $data->uid;
            $return['message_id'] = $data->message_id;
        }
        if(!empty($megs)){
            $this->data['templates'] = $this->template->getAllEmailTemplates();
            $this->data['email'] =$return;
            $this->load->view('email/forword',$this->data);
        }

    }
    public function reply($message_id){
        $query = 'SELECT * FROM `gmail_conversation` 
                  WHERE message_id="'.$message_id.'" 
                  ORDER BY `converted_date_time`  DESC
                 ';
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            $megs=$result->result();
        }
        $return = array();
        foreach ($megs as $data){
            if(strpos('viral@wooglobe.com', strtolower($data->from_email)) === false){
                $return['from']['email'] = $data->to_email;
                $return['from']['name'] = $data->to_name;
                $return['to_email'] = $data->from_email;
            }else{
                $return['from']['email'] = $data->from_email;
                $return['from']['name'] = $data->from_name;
                $return['to_email'] = $data->to_email;
            }

            $return['date'] = date('m/d/Y H:i A',strtotime($data->converted_date_time));
            $return['subject'] = $data->subject;
            $return['body']['html'] = $data->message;
            $return['reply_link'] = $data->uid;
            $return['message_id'] = $data->message_id;
        }
        if(!empty($megs)){
            $this->data['templates'] = $this->template->getAllEmailTemplates();
            $this->data['email'] =$return;
            $this->load->view('email/reply',$this->data);
        }

 }

    public function get_template(){
        $response['code'] = 200;
        $response['message'] = 'Get Template Successfully!';
        $response['error'] = '';
        $data = $this->security->xss_clean($this->input->post());
        $template = $this->template->getEmailTemplateByCode($data['code']);
        $leadData = $this->lead->getLeadByUniqueKey($data['id'],'v.id as vId,vl.*');
        if($template && $leadData){
            if(empty($leadData->vId)){
                $leadData->vId = 0;
            }
            $ids = array(
                'video_leads' => $leadData->id,
                'users' => $leadData->client_id,
                'videos' => $leadData->vId,
            );
            $message = dynStr($template->message, $ids);

            $response['html'] = $message;

        }else{
            $response['code'] = 201;
            $response['message'] = 'No Template Found!';
            $response['error'] = 'No Template Found!';
        }
        echo json_encode($response);
        exit;
    }

    public function send_mail(){

        $response['code'] = 200;
        $response['message'] = 'Email send Successfully!';
        $response['error'] = '';

        $data = $this->security->xss_clean($this->input->post());

        $this->email($data['to'],'','sell@wooglobe.com','WooGlobe',$data['subject'],$data['message'],$data['cc'],$data['bcc']);

        echo json_encode($response);
        exit;

    }

    public function reply_mail(){



    }
}