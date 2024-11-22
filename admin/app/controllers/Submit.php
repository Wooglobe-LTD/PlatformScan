<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Submit extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'submit';
        $css = array(
            'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css'
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
            'assets/js/custom/dropify/dist/js/dropify.min.js',
            'assets/js/pages/forms_file_input.min.js',
            'assets/js/submit.js'
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);
        $this->data['assess'] = array(
            'submit' => role_permitted_html(false, 'submit'),
        );
        $this->load->model('Submit_Model', 'submit');
        $this->load->model('Video_Lead_Model', 'lead');
        $this->load->helper('string');
        $this->load->model('Upload','upload_video');

    }

    public function index()
    {
        auth();
        role_permitted(false, 'submit');
        $this->data['title'] = 'Video Leads Submission';
        $this->data['content'] = $this->load->view('submit/index', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }


    public function submit_lead(){

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video Uploaded Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        if($this->sess->userdata('isClientLogin') == '') {
            $this->validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->validation->set_rules('last_name', 'Last Name', 'trim|required');
            $this->validation->set_rules('phone', 'Last Name', 'trim|required');
            $this->validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
        }
        $this->validation->set_rules('video_title','Video Title','trim|required');
        $this->validation->set_rules('video_url','Video URL','required|is_unique[video_leads.video_url]');
        //$this->validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha');


        if($this->validation->run() == FALSE){

            $fields = array('first_name','last_name','email','video_title','video_url','g-recaptcha-response');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            //header("Content-type: application/json");
            $response['url'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            $insert = $this->security->xss_clean($this->input->post());
            $response['post'] = $insert;
            if($this->sess->userdata('isClientLogin') != '') {
                $name = $this->sess->userdata('clientName');
                $split_name = explode(' ' ,$name);
                $first_name = $split_name[0];
                $last_name  = end($split_name);
                $insert['first_name'] = $first_name;
                $insert['last_name'] = $last_name;
                $insert['email'] = $this->sess->userdata('clientEmail');
                $insert['client_id'] = $this->sess->userdata('clientId');
                $insert['phone'] = $this->sess->userdata('clientMobile');
            }
            /*echo '<pre>';
            print_r($insert);
            exit;*/


            unset($insert['video-upload-form-submit']);
            unset($insert['g-recaptcha-response']);
            $slug = slug($this->input->post('video_title'), 'video_leads', 'slug');

            $insert['created_at'] = date("Y-m-d H:i:s");
            $insert['updated_at'] = date("Y-m-d H:i:s");
            $insert['slug'] = $slug;

            if(!isset($insert['shotVideo']) && empty($insert['shotVideo'])){
                $insert['shotVideo'] = 'No';
            }
            if(!isset($insert['haveOrignalVideo']) && empty($insert['haveOrignalVideo'])){
                $insert['haveOrignalVideo'] = 'No';
            }
            if(isset($insert['newsletter']) && empty($insert['newsletter'])){
                $this->load->library('MailChimp');
                $list_id = '6685154911';

                $result = $this->mailchimp->post("lists/$list_id/members", [
                    'email_address' => $insert['email'],
                    'status'        => 'subscribed',
                ]);
                unset($insert['newsletter']);
            }
            $id = $this->upload_video->upload_video($insert);

            if (isset($id)) {

                if ($this->sess->userdata('isClientLogin')) {
                    $to = $this->sess->userdata('clientEmail');
                } else {
                    $to = $this->input->post('email');
                }
                $unique_key_list = array_column($this->db->distinct()->select('unique_key')->get('video_leads')->result_array(), 'unique_key');
                $random = random_string('numeric',6);
                while (in_array("WGA".$random, $unique_key_list))
                {
                    $random = random_string('numeric',6);   
                }
                //$random = strtoupper($random);
                //$random = $random.$id;

                $key = hash("sha1",$random,FALSE);
                //$key = strtoupper($key);

                // Randomly generate unique key for leads.
                $random = "WGA".$random;
                $data = array(
                    'unique_key' => $random,
                    'encrypted_unique_key' => $key
                );

                $this->db->where('id', $id);
                $this->db->update('video_leads',$data);

                $result = $this->upload_video->getTemplate();

                $email_template_id = $result['id'];
                $title = $result['title'];

                $str = $result['message'];
                $subject = "WooGlobe Video Opportunity - ".$random;
                $from = 'no-reply@wooglobe.com';

                $ids = array(
                    'video_leads' => $id
                );


                $message = dynStr($str, $ids);

                //$result = $this->email($to, $to_name = '', $from, $from_name = '', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
                if ($result) {

                    $db_data = array();

                    $db_data['send_datime'] = date('Y-m-d H:i:s');

                    $db_data['lead_id'] = $id;
                    $db_data['email_template_id'] = $email_template_id;
                    $db_data['email_title'] = $title;
                    $db_data['ids'] = json_encode($ids);

                    $insert = $this->upload_video->email_notification($db_data);

                    $date = array();
                    $date['lead_id'] = $id;
                    $action_date = $this->db->insert('lead_action_dates',$date);
                    action_add($id,0,0,0,0,'Lead Submitted');

                    $response['code'] = 200;
                    $response['message'] = 'We received your video request and Sent You an Email !';
                    $email_temp = $this->app->getTemplateByShortCode('new_staff_lead_submission');
                    if($email_temp){

                        $str = $email_temp->message;
                        $subject = $email_temp->subject;
                        $from = 'no-reply@wooglobe.com';

                        $ids = array(
                            'video_leads' => $id
                        );


                        $message = dynStr($str, $ids);
                        $admin = $this->app->getSuperAdmin();
                       // $result = $this->email($admin->email, $admin->name, $from, $from_name = '', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                    }
                    $root_path = './../uploads/'.$random;
                    $raw_videos = './../uploads/'.$random.'/raw_videos';
                    $edited_videos = './../uploads/'.$random.'/edited_videos';
                    $edited_yt = './../uploads/'.$random.'/edited_videos/youtube';
                    $edited_yt_thumb = './../uploads/'.$random.'/edited_videos/youtube/thumbnail';
                    $edited_fb = './../uploads/'.$random.'/edited_videos/facebook';
                    $edited_fb_thumb = './../uploads/'.$random.'/edited_videos/facebook/thumbnail';
                    $edited_mrsss = './../uploads/'.$random.'/edited_videos/mrss';
                    $edited_mrss_thumb = './../uploads/'.$random.'/edited_videos/mrss/thumbnail';
                    $documents = './../uploads/'.$random.'/documents';
                    mkdir($root_path, 0777,true);
                    mkdir($raw_videos, 0777,true);
                    mkdir($edited_videos, 0777,true);
                    mkdir($edited_yt, 0777,true);
                    mkdir($edited_yt_thumb, 0777,true);
                    mkdir($edited_fb, 0777,true);
                    mkdir($edited_fb_thumb, 0777,true);
                    mkdir($edited_mrsss, 0777,true);
                    mkdir($edited_mrss_thumb, 0777,true);
                    mkdir($documents, 0777,true);
                    echo json_encode($response);
                    exit;
                } else {
                    $response['code'] = 205;
                    $response['message'] = 'We received your video request and having problem while sending you Email !';
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response['code'] = 204;
                $response['message'] = 'Video cannot be uploaded !';
                $response['error'] = '';
                $response['url'] = '';
                echo json_encode($response);
                exit;

            }

        }

    }

    public function check_email()
    {

        $email = $this->security->xss_clean($this->input->post('email'));
        $result = $this->submit->getUserByEmail($email);
        $error = 'success';
        if ($result->num_rows() > 0) {
            $error = 'error';
            http_response_code(404);
        }
        echo json_encode($error);
        exit;
    }

    public function check_url($url)
    {
        $url = $this->input->post('video_url');
        if (empty($url)) {
            $this->validation->set_message('check_url', 'URL is required!');
            return false;
        }
        $urlValue = explode(".", $url);

        if ($urlValue[1] == "youtube" || $urlValue[0] == "youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "youtu" || $urlValue[0] == "https://youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "https://youtu" || $urlValue[0] == "http://youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "http://youtu") {

            $youtube_regexp = "/^(?:http|https):\/\/|(?:www\.)?(?:youtube.com|youtu.be)\/(?:watch\?(?=.*v=([\w\-]+))(?:\S+)?|([\w\-]+))$/";

            if (!preg_match($youtube_regexp, $url, $matches)) {
                $this->validation->set_message('check_url', 'Invalid Youtube video Url!');
                return false;
            }

        } else if ($urlValue[1] == "facebook" || $urlValue[0] == "facebook" || $urlValue[0] == "https://facebook" || $urlValue[0] == "http://facebook") {
            $facebook_regexp = "/^(?:(?:https?:)?\/\/)?(?:www\.)?facebook\.com\/[a-z\.]+\/videos\/(?:[a-zA-Z0-9\.]+\/)?([0-9]+)\/?(?:\?.*)?$/";


            if (!preg_match($facebook_regexp, $url, $matches)) {
                $this->validation->set_message('check_url', 'Invalid Facebook video Url!');
                return false;
            }
        } else if ($urlValue[1] == "instagram" || $urlValue[0] == "instagram" || $urlValue[0] == "https://instagram" || $urlValue[0] == "http://instagrm" || $urlValue[1] == "instagr" || $urlValue[0] == "instagr") {
            $instagram_regexp = "/^(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am)\/([A-Za-z0-9-_]+)/";

            if (!preg_match($instagram_regexp, $url, $matches)) {
                $this->validation->set_message('check_url', 'Invalid Instagram video Url!');
                return false;
            }
        } else if ($urlValue[1] == "viemo" || $urlValue[0] == "viemo" || $urlValue[0] == "https://viemo" || $urlValue[0] == "http://viemo") {

            $viemo_regexp = "/^(http|https)?:\/\/(www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|)(\d+)(?:|\/\?)/";

            if (!preg_match($viemo_regexp, $url, $matches)) {
                $this->validation->set_message('check_url', 'Invalid Viemo video Url!');
                return false;
            }
        } else {

            if (preg_match('/^(?:([^:]*)\:)?\/\/(.+)$/', $url, $matches)) {
                if (empty($matches[2])) {
                    return FALSE;
                } elseif (!in_array(strtolower($matches[1]), array('http', 'https'), TRUE)) {
                    return FALSE;
                }

                $url = $matches[2];
            }

        }

    }

}
