<?php

/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 2/13/2018
 * Time: 2:13 PM
 */
class Video_upload extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_Model','user');
        $this->load->model('Auth_Model','auth');
        $this->load->model('Upload','upload_video');
        $this->load->helper('string');
    }

    public function index()
    {
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
        $this->validation->set_message('is_unique','This video link was previously submitted, please contact customer services.');

        //$this->validation->set_rules('g-recaptcha-response','Captcha','callback_recaptcha');


        if($this->validation->run() == FALSE){

            $fields = array('first_name','last_name','email','video_title','video_url','g-recaptcha-response');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Your lead has been not submitted yet, Please recheck your form carefully.';
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
           

            header("Content-type: application/json");
            unset($insert['video-upload-form-submit']);
            unset($insert['g-recaptcha-response']);
            $slug = slug($this->input->post('video_title'), 'video_leads', 'slug');

            $insert['created_at'] = date("Y-m-d H:i:s");
            $insert['updated_at'] = date("Y-m-d H:i:s");
            $insert['slug'] = $slug;
            $insert['lead_type'] = 'website';
            $insert['social_video_id'] = extrect_video_id($insert['video_url']);


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

                /*do {*/
                $random = random_string('numeric',6);
                while (in_array("WGA".$random, $unique_key_list))
                {
                    $random = random_string('numeric',6);   
                }
                /*}
                while (in_array("WGA".$random, $unique_key_list));*/
                //$random = strtoupper($random);
                //$random = $random.$id;
            if(!empty($random)){
                $key = hash("sha1",$random,FALSE);
                //$key = strtoupper($key);

                // Randomly generate unique key for leads.
                $random = "WGA".$random;
                $kslug=$slug.'-'.$random;
                $data = array(
                    'unique_key' => $random,
                    'encrypted_unique_key' => $key,
                    'slug' => $kslug,
                );
                action_add($id,0,0,0,0,'Lead Data Added '.$random.' ra');
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
                $result=1;
                /*$result = $this->email($to, $to_name = '', $from, $from_name = 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');*/
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
                        $response['message'] = 'We have received your request. We will contact you soon if your video is selected';
                        $email_temp = $this->app->getTemplateByShortCode('new_lead_submission');
                        if($email_temp){

                            $str = $email_temp->message;
                            $subject = $email_temp->subject;
                            $from = 'no-reply@wooglobe.com';

                            $ids = array(
                                'video_leads' => $id
                            );


                            $message = dynStr($str, $ids);
                            $admin = $this->app->getSuperAdmin();
                            $result = $this->email($admin->email, $admin->name, $from, $from_name = '', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                        }
                    $root_path = './uploads/'.$random;
                    $raw_videos = './uploads/'.$random.'/raw_videos';
                    $edited_videos = './uploads/'.$random.'/edited_videos';
                    $edited_yt = './uploads/'.$random.'/edited_videos/youtube';
                    $edited_yt_thumb = './uploads/'.$random.'/edited_videos/youtube/thumbnail';
                    $edited_fb = './uploads/'.$random.'/edited_videos/facebook';
                    $edited_fb_thumb = './uploads/'.$random.'/edited_videos/facebook/thumbnail';
                    $edited_mrsss = './uploads/'.$random.'/edited_videos/mrss';
                    $edited_mrss_thumb = './uploads/'.$random.'/edited_videos/mrss/thumbnail';
                    $documents = './uploads/'.$random.'/documents';
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
                    } 
                    else {
                        $response['code'] = 205;
                        $response['message'] = 'We received your video request and having problem while sending you Email !';
                        echo json_encode($response);
                        exit;
                    }
            } 
            else {
                $response['code'] = 204;
                $response['message'] = 'Video cannot be uploaded ! Please refresh page and try again';
                $response['error'] = '';
                $response['url'] = '';
                echo json_encode($response);
                exit;
            }  
        } 
        else {
            $response['code'] = 204;
            $response['message'] = 'Video cannot be uploaded !';
            $response['error'] = '';
            $response['url'] = '';
            echo json_encode($response);
            exit;
        }
            
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

    public function check_url($url)
    {
            $url = $this->input->post('video_url');
            if(empty($url)){
                $this->validation->set_message('check_url','URL is required!');
                return false;
            }
            $urlValue = explode(".",$url);

            if($urlValue[1] == "youtube" || $urlValue[0] == "youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "youtu" || $urlValue[0] == "https://youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "https://youtu"  || $urlValue[0] == "http://youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "http://youtu"){

                $youtube_regexp = "/^(?:http|https):\/\/|(?:www\.)?(?:youtube.com|youtu.be)\/(?:watch\?(?=.*v=([\w\-]+))(?:\S+)?|([\w\-]+))$/";

                if (!preg_match($youtube_regexp, $url, $matches)){
                    $this->validation->set_message('check_url','Invalid Youtube video Url!');
                    return false;
                }

            }
            else if($urlValue[1] == "facebook" || $urlValue[0] == "facebook" || $urlValue[0] == "https://facebook" || $urlValue[0] == "http://facebook" )
            {
                //$facebook_regexp = "/^(?:(?:https?:)?\/\/)?(?:www\.)?facebook\.com\/[a-z\.]+\/videos\/(?:[a-zA-Z0-9\.]+\/)?([0-9]+)\/?(?:\?.*)?$/";
                $fbUrlCheck = '/^(https?:\/\/)?(www\.)?facebook.com\/[a-zA-Z0-9(\.\?)?]/';
                $secondCheck = '/home((\/)?\.[a-zA-Z0-9])?/';

                /*if (!preg_match($facebook_regexp, $url, $matches)){
                    $this->validation->set_message('check_url','Invalid Facebook video Url!');
                    return false;
                }*/
                if(!preg_match($fbUrlCheck, $url) == 1) {
                    $this->validation->set_message('check_url','Invalid Facebook video Url!');
                    return false;
                }
            }
            else if($urlValue[1] == "instagram" || $urlValue[0] == "instagram" || $urlValue[0] == "https://instagram" ||  $urlValue[0] == "http://instagrm" || $urlValue[1] == "instagr" || $urlValue[0] == "instagr"){
                $instagram_regexp = "/^(?:(?:http|https):\/\/)?(?:www.)?(?:instagram.com|instagr.am)\/([A-Za-z0-9-_]+)/";

                if (!preg_match($instagram_regexp, $url, $matches)){
                    $this->validation->set_message('check_url','Invalid Instagram video Url!');
                    return false;
                }
            }
            else if($urlValue[1] == "viemo" || $urlValue[0] == "viemo" || $urlValue[0] == "https://viemo" ||  $urlValue[0] == "http://viemo" ){

                $viemo_regexp = "/^(http|https)?:\/\/(www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|)(\d+)(?:|\/\?)/";

                if (!preg_match($viemo_regexp, $url, $matches)){
                    $this->validation->set_message('check_url','Invalid Viemo video Url!');
                    return false;
                }
            }
            else{

                if (preg_match('/^(?:([^:]*)\:)?\/\/(.+)$/', $url, $matches))
                {
                    if (empty($matches[2]))
                    {
                        return FALSE;
                    }
                    elseif ( ! in_array(strtolower($matches[1]), array('http', 'https'), TRUE))
                    {
                        return FALSE;
                    }

                    $url = $matches[2];
                }

            }

    }

    public function send_contract()
    {
        include('./vendor/signrequest/src/AtaneNL/SignRequest/Client.php');
        include('./vendor/signrequest/src/AtaneNL/SignRequest/CreateDocumentResponse.php');

        $slug =  $this->uri->segment(2);

        $result = $this->upload_video->getDetailsBySlug($slug);

        $email = $result['email'];
        $name = $result['first_name'].' '.$result['last_name'];

        $this->data['lead'] = $result;

        $this->data['title'] = 'Send Contract';

        if($result['status'] == 10){
            $lead_id = $result['id'];
            $contractName = $result['first_name'].'_'.$result['last_name'] . '_' .$result['unique_key'] . '.pdf';
            $this->data['lead_data'] = $result;
            //$html = $this->load->view('pdf', $this->data, true);



            //$mpdf = new \Mpdf\Mpdf();
            //$mpdf->WriteHTML($html);
            //$mpdf->Output('./contract/' . $contractName);
            $prifills =array();
            $title = array("external_id"=>"contract_video_title", "text"=>$result['video_title']);
            $url = array("external_id"=>"contract_video_url", "text"=>$result['video_url']);
            $share = array("external_id"=>"contract_revenue_share", "text"=>$result['revenue_share']);
            $prifills[] = $title;
            $prifills[] = $url;
            $prifills[] = $share;
            $contract_message = "Hi ".$result['first_name']."\n,
                                    Sophia Grace has sent you the attached document to review and sign online.\n  
                                    Please review and sign this agreement allowing WooGlobe Ltd to represent your video.  If you have any questions about it, please email us at Viral@Wooglobe.com or give us a call at +44 74 5656 1051. 
                                    \n
                                    Thank you so much!\n
                                     Kind regards\n
                                     -- \n
                                    Sophia Grace / Content Acquisition\n
                                    + 44 (0) A 74 5656 1051 / WooGlobe, Inc\n
                                    Losngeles, CA / New York, NY / London, UK\n
                                    Viral@WooGlobe.com
                                    \n
                                    Check out our best videos of the month : https://youtu.be/PqWTAqG_mvw";
            $additinaolParams = array('from_email_name' => 'WooGlobe', 'redirect_url' => base_url('contract_signed/'
                .$slug),'subject'=>'Wooglobe Contract - '.$result['unique_key'].' - '.$result['email'],'from_email'=>'no-reply@wooglobe
                .com','prefill_tags'=>$prifills,'external_id'=>$lead_id,'message'=>$contract_message);

            $client = new \AtaneNL\SignRequest\Client($this->config->config['sr_token']);
            //$cdr = $client->createDocument(getcwd() . '/contract/' . $contractName, $lead_id);

            //$result = $client->sendSignRequest($cdr->uuid, 'sell@wooglobe.com', array(array('email' => $result['email']),array('email' => 'sell@wooglobe.com')), "WooGlobe Content Agreement", true, $additinaolParams);
            $result = $client->sendSignRequestFromTemplate($this->data['setting']->default_sr_template, 'no-reply@wooglobe.com',array(array('email' => $result['email']),array('email' => 'viral@wooglobe.com', 'needs_to_sign' => false, 'notify_only'=> true)), $contract_message, true,$additinaolParams);
            $uuid = $result->uuid;
            $date_time = date('Y-m-d H:i:s');

            $update_status  = $this->upload_video->update_status($lead_id,$uuid);

            $update_contract_time = $this->upload_video->update_contract_time($date_time,$lead_id);
            $email_temp = $this->app->getTemplateByShortCode('sending_contract_email');
            if($email_temp){
                $random = random_string('alnum',5);
                $random = strtoupper($random);

                $key = hash("sha1",$random,FALSE);
                $key = strtoupper($key);

                // Randomly generate unique key for leads.
                $random = "WG".$random;

                $str = $email_temp->message;
                $subject = $email_temp->subject;
                $subject = $subject.$random;
                $from = 'no-reply@wooglobe.com';
                action_add($lead_id,0,0,0,0,'Contract send');
                $ids = array(
                    'video_leads' => $lead_id
                );


                $message = dynStr($str, $ids);
                // $result = $this->email($email, $name, $from, $from_name = '', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

            }
            else{
                action_add($lead_id,0,0,0,0,'Contract not send');
                action_add($lead_id,0,0,0,0,$email_temp);
            }
            $this->data['profile_nav'] = 'Contract Sent';
            $this->data['page_content'] = getContentById(1);

            $this->data['content'] = $this->load->view('contract_send',$this->data,true);
            $this->load->view('common_files/template',$this->data);

        }
        else{
            $this->data['profile_nav'] = 'Link Expired';
            $this->data['page_content'] = getContentById(2);
            $this->data['content'] = $this->load->view('contract_link_expired',$this->data,true);
            $this->load->view('common_files/template',$this->data);
        }

    }

    public function upload_file($uid){

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video Uploaded Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        /*$result = $this->upload_local_video('file',$this->input->post('uid'));

        if(!isset($result['url']) && empty($result['url'])){
            $response['code'] = 201;
            $response['message'] = 'Video not Uploaded Successfully!';

            echo json_encode($response);
            exit;
        }

        $response['video'] = $result;*/
        $file_pointer = './uploads/'.$uid.'/raw_videos/';

        if (file_exists($file_pointer))
        {
            include('./app/third_party/class.fileuploader.php');
            $FileUploader = new FileUploader('file', array(
                'title'=> ($uid).'_'.time(),
                'uploadDir'=> './uploads/'.$uid.'/raw_videos/'
            ));
        }
        else
        {
            $root_path = './uploads/'.$uid;
            $raw_videos = './uploads/'.$uid.'/raw_videos';
            $edited_videos = './uploads/'.$uid.'/edited_videos';
            $edited_yt = './uploads/'.$uid.'/edited_videos/youtube';
            $edited_yt_thumb = './uploads/'.$uid.'/edited_videos/youtube/thumbnail';
            $edited_fb = './uploads/'.$uid.'/edited_videos/facebook';
            $edited_fb_thumb = './uploads/'.$uid.'/edited_videos/facebook/thumbnail';
            $edited_mrsss = './uploads/'.$uid.'/edited_videos/mrss';
            $edited_mrss_thumb = './uploads/'.$uid.'/edited_videos/mrss/thumbnail';
            $documents = './uploads/'.$uid.'/documents';
            mkdir($root_path, 0755,true);
            mkdir($raw_videos, 0755,true);
            mkdir($edited_videos, 0755,true);
            mkdir($edited_yt, 0755,true);
            mkdir($edited_yt_thumb, 0755,true);
            mkdir($edited_fb, 0755,true);
            mkdir($edited_fb_thumb, 0755,true);
            mkdir($edited_mrsss, 0755,true);
            mkdir($edited_mrss_thumb, 0755,true);
            mkdir($documents, 0755,true);
            include('./app/third_party/class.fileuploader.php');
            $FileUploader = new FileUploader('file', array(
                'title'=> ($uid).'_'.time(),
                'uploadDir'=> './uploads/'.$uid.'/raw_videos/'
            ));
        }
        // call to upload the files
        $upload = $FileUploader->upload();

        if($upload['isSuccess']) {
            // get the uploaded files
            $files = $upload['files'];
            foreach ($upload['files'] as $i=>$file){
                $upload['files'][$i]['video'] = 'uploads/'.$uid.'/raw_videos/'.$file['name'];
                $upload['files'][$i]['title_new'] = explode('.',$file['name'])[0];
            }
        }


        echo json_encode($upload);
        exit;

    }
    public function upload_files($uid){

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video Uploaded Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        /*$result = $this->upload_local_video('file',$this->input->post('uid'));

        if(!isset($result['url']) && empty($result['url'])){
            $response['code'] = 201;
            $response['message'] = 'Video not Uploaded Successfully!';

            echo json_encode($response);
            exit;
        }

        $response['video'] = $result;*/
        $file_pointer = './uploads/'.$uid.'/raw_videos/';

        if (file_exists($file_pointer))
        {
            include('./app/third_party/class.fileuploader.php');
            $FileUploader = new FileUploader('files', array(
                'title'=> ($uid).'_'.time(),
                'uploadDir'=> './uploads/'.$uid.'/raw_videos/'
            ));
        }
        else
        {
            $root_path = './uploads/'.$uid;
            $raw_videos = './uploads/'.$uid.'/raw_videos';
            $edited_videos = './uploads/'.$uid.'/edited_videos';
            $edited_yt = './uploads/'.$uid.'/edited_videos/youtube';
            $edited_yt_thumb = './uploads/'.$uid.'/edited_videos/youtube/thumbnail';
            $edited_fb = './uploads/'.$uid.'/edited_videos/facebook';
            $edited_fb_thumb = './uploads/'.$uid.'/edited_videos/facebook/thumbnail';
            $edited_mrsss = './uploads/'.$uid.'/edited_videos/mrss';
            $edited_mrss_thumb = './uploads/'.$uid.'/edited_videos/mrss/thumbnail';
            $documents = './uploads/'.$uid.'/documents';
            mkdir($root_path, 0755,true);
            mkdir($raw_videos, 0755,true);
            mkdir($edited_videos, 0755,true);
            mkdir($edited_yt, 0755,true);
            mkdir($edited_yt_thumb, 0755,true);
            mkdir($edited_fb, 0755,true);
            mkdir($edited_fb_thumb, 0755,true);
            mkdir($edited_mrsss, 0755,true);
            mkdir($edited_mrss_thumb, 0755,true);
            mkdir($documents, 0755,true);
            include('./app/third_party/class.fileuploader.php');
            $FileUploader = new FileUploader('files', array(
                'title'=> ($uid).'_'.time(),
                'uploadDir'=> './uploads/'.$uid.'/raw_videos/'
            ));
        }
        // call to upload the files
        $upload = $FileUploader->upload();

        if($upload['isSuccess']) {
            // get the uploaded files
            $files = $upload['files'];
            foreach ($upload['files'] as $i=>$file){
                $upload['files'][$i]['video'] = 'uploads/'.$uid.'/raw_videos/'.$file['name'];
                $upload['files'][$i]['title_new'] = explode('.',$file['name'])[0];
            }
        }


        echo json_encode($upload);
        exit;

    }

    public function upload_videos()
    {
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video Uploaded Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('title','Title','required');
        $this->validation->set_rules('category_id','Category','required');
        $this->validation->set_rules('video_type_id','Video Type','required');
        $this->validation->set_rules('url','Video URL','required');


        if($this->validation->run() == false){
            $fields = array('title','category_id','video_type_id','url');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            $result = $this->input->post();

            $id = $this->upload_video->upload_videos($result);
            if(isset($id)){
                $response['code'] = 200;
                $response['message'] = 'Video Uploaded Successfully!';
                $response['error'] = '';
                $response['url'] = base_url();

                echo json_encode($response);
                exit;
            }
            else{
                $response['code'] = 204;
                $response['message'] = 'Problem While Uploading Video!';
                $response['error'] = '';
                $response['url'] = '';

                echo json_encode($response);
                exit;
            }
        }

    }
    public function remove_file(){

        $file = $this->input->post('file');
        @unlink('./uploads/videos/raw_videos/'.$file);
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'File removed Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        echo json_encode($response);
        exit;

    }
    public function  create_folder(){
        print "start";
        $query = 'SELECT DISTINCT unique_key FROM video_leads LIMIT 0,1054';
        $result = $this->db->query($query);
        $unique_key_list=$result->result_array();
        foreach ($unique_key_list as $randoms) {
            $random=$randoms['unique_key'];
            print_r($random);
            $root_path = './uploads/' . $random;
            $raw_videos = './uploads/' . $random . '/raw_videos';
            $edited_videos = './uploads/' . $random . '/edited_videos';
            $edited_yt = './uploads/' . $random . '/edited_videos/youtube';
            $edited_yt_thumb = './uploads/' . $random . '/edited_videos/youtube/thumbnail';
            $edited_fb = './uploads/' . $random . '/edited_videos/facebook';
            $edited_fb_thumb = './uploads/' . $random . '/edited_videos/facebook/thumbnail';
            $edited_mrsss = './uploads/' . $random . '/edited_videos/mrss';
            $edited_mrss_thumb = './uploads/' . $random . '/edited_videos/mrss/thumbnail';
            $documents = './uploads/' . $random . '/documents';
            mkdir($root_path, 0777, true);
            mkdir($raw_videos, 0777, true);
            mkdir($edited_videos, 0777, true);
            mkdir($edited_yt, 0777, true);
            mkdir($edited_yt_thumb, 0777, true);
            mkdir($edited_fb, 0777, true);
            mkdir($edited_fb_thumb, 0777, true);
            mkdir($edited_mrsss, 0777, true);
            mkdir($edited_mrss_thumb, 0777, true);
            mkdir($documents, 0777, true);

        }
        print "stop";
        exit();
    }
}
