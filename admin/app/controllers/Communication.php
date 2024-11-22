<?php

/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 4/25/2018
 * Time: 4:13 PM
 */
class Communication extends APP_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'video_deals';
        $css = array(
            'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css'
        );
        $js = array(
            //'bower_components/datatables/media/js/jquery.dataTables.min.js',
            //'bower_components/datatables-buttons/js/dataTables.buttons.js',
            //'assets/js/custom/datatables/buttons.uikit.js',
            //'bower_components/jszip/dist/jszip.min.js',
            //'bower_components/pdfmake/build/pdfmake.min.js',
            //'bower_components/pdfmake/build/vfs_fonts.js',
            //'bower_components/datatables-buttons/js/buttons.colVis.js',
            //'bower_components/datatables-buttons/js/buttons.html5.js',
            //'bower_components/datatables-buttons/js/buttons.print.js',
            //'assets/js/custom/datatables/datatables.uikit.min.js',
            'bower_components/tinymce/tinymce.min.js',
            'assets/js/pages/forms_wysiwyg.js',
            'assets/js/custom/dropify/dist/js/dropify.min.js',
            'assets/js/pages/forms_file_input.min.js',
            'bower_components/dragula.js/dist/dragula.min.js',
            'assets/js/pages/page_scrum_board.js',
            'assets/js/communication.js'
        );
        $this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'],$js);

        $this->data['assess'] =  array(

        );
        $this->load->model('Communication_Model','email');
        $this->load->model('Video_Lead_Model','lead');
    }

    public function index($id)
    {
        auth();
        $this->data['templates'] = $this->email->getTemplates($id);
        $this->data['content'] = $this->load->view('communiction',$this->data,true);
        $this->load->view('common_files/template',$this->data);

    }

    public function send_email()
    {
        auth();
        $data = $this->input->post();


        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Email Sent Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $lead_id = $data['lead_id'];

        $leadData = $this->email->getLeadById($lead_id, 'vl.*');

        $templateId = $this->email->getTemplateId($data['email_template'],'enh.*');
        $template = $this->email->getTemplate($templateId->email_template_id,'et.*');


        $ids = json_decode($templateId->ids,true);

        if($templateId->email_template_id == 2){

            if(isset($data['is_url'])){
                $this->email->update_status($lead_id);
                //$url1 = $this->urlmaker->shorten($this->data['root'] . 'send_contract/' . $leadData->slug);
                $url1 = $this->data['root'] . 'send_contract/' . $leadData->slug;
                $url = '<p>If you are interested to make a contract with us then <a href="'.$url1.'">click here</a></p>';
            }
            else{
                $url = '';
            }
            $message = dynStr($template->message, $ids);
            $message = str_replace('@LINK', $url, $message);
        }
        else{
            $message = dynStr($template->message, $ids);
        }
        $sent = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $template->subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');


        if ($sent) {
            echo json_encode($response);
            exit;
        } else {
            $response['code'] = 201;
            $response['message'] = 'Email not sent!';
            echo json_encode($response);
            exit;
        }


    }

    public function send_custom_email()
    {
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Email Sent Successfully';
        $response['error'] = '';
        $response['url'] = '';

        $data = $this->input->post();

        $this->validation->set_rules('subject','Subject','required');
        $this->validation->set_rules('message','Message','required');
        $this->validation->set_message('required','This field is required');

        if($this->validation->run() == FALSE){
            $fields = array('subject','message');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';
        }
        else{
            $lead_id = $data['leadId'];
            $leadData = $this->email->getLeadById($lead_id, 'vl.*');
            $subject = $data['subject'];
            $message = $data['message'];

            $sent = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

            if ($sent) {

                $ids = array(
                    'video_leads' => $lead_id
                );

                $notification = array();

                $notification['send_datime'] = date('Y-m-d H:i:s');
                $notification['lead_id'] = $lead_id;
                $notification['email_template_id'] = 0;
                $notification['email_title'] = $subject;
                $notification['ids'] = json_encode($ids);
               $this->db->insert('email_notification_history',$notification);

                echo json_encode($response);
                exit;
            } else {
                $response['code'] = 201;
                $response['message'] = 'Email not sent!';
                echo json_encode($response);
                exit;
            }
        }

    }
}