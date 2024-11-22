<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends APP_Controller {



	public function __construct() {
        parent::__construct();


        $this->load->model('Profile_Model','profile');
        $this->load->model('Location_Model','location');
        $this->load->model('User_Model','user');
        $this->load->model('Auth_Model','auth');
        $this->load->model('Upload','upload_video');
        $this->data['active'] = 'profile';
        $this->data['nav_profile'] = 'pofile';
        //$this->data['profile_menu'] = array('profile'=>'Profile','change-password'=>'Change Password','my-videos'=>'My Videos','upload-video'=>'Upload Video');
        $this->data['profile_menu'] = array('profile'=>'Profile','change-password'=>'Change Password','dashboard'=>'Dashboard');

        $client_id = 0;

        if($this->sess->userdata('clientId') != '') {

            $client_id = $this->sess->userdata('clientId');
        }
        $this->data['userData'] = $this->user->getUserById($client_id);
        // $this->data['currency'] = getUserCurrencySymbolById($client_id);
        $this->data['currency'] = getDefaultCurrency()["symbol"];
        


    }
	public function index()
	{
        auth();

		$this->data['title'] = 'Pofile';
        $this->data['profile_nav'] = 'profile';
        $this->data['countries'] = $this->location->getCountries();
        $this->data['data'] = $this->user->getUserById($this->sess->userdata('clientId'));
        $this->data['js'][] = 'profile';

        $this->data['content'] = $this->load->view('profile',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}

    public function update_profile()
    {
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Profile Updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('full_name','Full Name','trim|required');
        $this->validation->set_rules('full_name','Gender','trim|required');
        $this->validation->set_rules('paypal_email','PayPal Email','trim|required');
        //$this->validation->set_rules('dob','Date Of Birth','trim|required');
        $this->validation->set_rules('country_id','Country','trim|required');
        $this->validation->set_rules('state_id','State','trim|required');
        $this->validation->set_rules('city_id','Çity','trim|required');
        $this->validation->set_rules('address','Address','trim|required');
        $this->validation->set_rules('country_code','Country Code','trim|required');
        $this->validation->set_rules('mobile','Mobile Number','trim|required');
        $this->validation->set_rules('zip_code','Zipcode','trim|required');
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('full_name','full_name','paypal_email','dob','country_id','state_id','city_id','address','country_code','mobile','zip_code');
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

            if(!empty($_FILES['picture']['name'])){

                $picture = $this->profile_upload('picture');

                if($picture['code'] == 200){
                    $dbData['picture'] = $picture['url'];
                }
            }

            $this->db->where('id',$this->sess->userdata('clientId'));
            $this->db->update('users',$dbData);
        }

        echo json_encode($response);
        exit;
    }

    public function change_password()
    {
        auth();

        $this->data['title'] = 'Change Password';
        $this->data['profile_nav'] = 'change-password';
        $this->data['js'][] = 'change_password';
        $this->data['content'] = $this->load->view('change_password',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    // 3rd party functions
    public function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public function base30_to_jpeg($base30_string, $output_file) {
        if($_SERVER['HTTP_HOST'] !='wooglobe.com')
            require_once (root_path(). $this->config->item('local_dir') .'/app/libraries/jSignature_Tools_Base30.php');
        else
            require_once (root_path().'/app/libraries/jSignature_Tools_Base30.php');
        
        $data = str_replace ( 'image/jsignature;base30,', '', $base30_string );
        $converter = new jSignature_Tools_Base30 ();
        $raw = $converter->Base64ToNative ( $data );
// Calculate dimensions
        $width = 0;
        $height = 0;
        foreach ( $raw as $line ) {
            if (max ( $line ['x'] ) > $width)
                $width = max ( $line ['x'] );
            if (max ( $line ['y'] ) > $height)
                $height = max ( $line ['y'] );
        }

// Create an image
        $im = imagecreatetruecolor ( $width + 20, $height + 20 );

// Save transparency for PNG
        imagesavealpha ( $im, true );
// Fill background with transparency
        $trans_colour = imagecolorallocatealpha ( $im, 255, 255, 255, 127 );
        imagefill ( $im, 0, 0, $trans_colour );
// Set pen thickness
        imagesetthickness ( $im, 2 );
// Set pen color to black
        $black = imagecolorallocate ( $im, 0, 0, 0 );
// Loop through array pairs from each signature word
        for($i = 0; $i < count ( $raw ); $i ++) {
            // Loop through each pair in a word
            for($j = 0; $j < count ( $raw [$i] ['x'] ); $j ++) {
                // Make sure we are not on the last coordinate in the array
                if (! isset ( $raw [$i] ['x'] [$j] ))
                    break;
                if (! isset ( $raw [$i] ['x'] [$j + 1] ))
                    // Draw the dot for the coordinate
                    imagesetpixel ( $im, $raw [$i] ['x'] [$j], $raw [$i] ['y'] [$j], $black );
                else
                    // Draw the line for the coordinate pair
                    imageline ( $im, $raw [$i] ['x'] [$j], $raw [$i] ['y'] [$j], $raw [$i] ['x'] [$j + 1], $raw [$i] ['y'] [$j + 1], $black );
            }
        }

// Check if the image exists
        if (! file_exists ( dirname ( $output_file ) )) {
            mkdir(dirname($output_file));
        }

// Create Image
        $ifp = fopen ( $output_file, "wb" );
        imagepng ( $im, $output_file );
        fclose ( $ifp );
        imagedestroy ( $im );

        return $output_file;
    }

    // New form functions
    public function submit_viral_video(){

        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        $inputData = $this->security->xss_clean($this->input->post());
        /*echo '<pre>';
        print_r($inputData);
        exit;*/
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Video Submitted Successfully';
        $response['error'] = '';

        $this->validation->set_rules('first_name','First Name','trim|required');
        $this->validation->set_rules('last_name','Last Name','trim|required');
        $this->validation->set_rules('email','Email','trim|required|valid_email');
        $this->validation->set_rules('phone','Phone','trim|required');
        $this->validation->set_rules('city','City','trim|required');
        $this->validation->set_rules('state','State','trim|required');
        $this->validation->set_rules('country_code','Country_code','trim|required');
        $this->validation->set_rules('country','Country','trim|required');
        $this->validation->set_rules('address','Address','trim|required');

       /* $this->validation->set_rules('question1','question1','trim|required');
        $this->validation->set_rules('question3','question3','trim|required');
        $this->validation->set_rules('question4','question4','trim|required');
        $this->validation->set_rules('video_single_url','Video URL','required|is_unique[video_leads.video_url]|callback_valid_url');
        $this->validation->set_rules('video_title','Video Title','trim|required');*/

        $this->validation->set_rules('shotVideo','Video Title','trim|required');
        $this->validation->set_rules('ageVideo','Video Title','trim|required');
        $this->validation->set_rules('zip','Zip Code','trim|required');
       /* $this->validation->set_rules('termsShared','Video Title','trim|required');
        if(isset($inputData['yeslink'])) {
            $this->validation->set_rules('link_name', 'File', 'trim|required|callback_validate_url');
        }else{
            $this->validation->set_rules('fileuploader-list-file', 'File', 'trim|required|callback_validate_video');
        }*/
        if(isset($inputData['yespaypal'])) {
            $this->validation->set_rules('paypal','Paypal','trim|required');
        }
        $this->validation->set_rules('img','signature','trim|required');
        if(isset($inputData['file_link'])) {
            $this->validation->set_rules('terms_check', 'terms_check', 'trim');
        }else{
            $this->validation->set_rules('terms_check', 'terms_check', 'trim|required');
        }
        $this->validation->set_message('required','This field is required.');
        $this->validation->set_message('is_unique','This video link was previously submitted, please contact customer services.');
        if($this->validation->run() === false){

            $fields = array('first_name','last_name','email','phone','question1','zip','question3','question4','age','city','state','country','country_code','address','paypal','fileuploader-list-file','img','video_single_url','video_title','link_name','terms_check');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }

            foreach ($inputData['video_title']['videos'] as $i=>$video_title){
                if(empty($inputData['question1']['videos'][$i])){
                    $errors[($i+1).'_question1'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['question3']['videos'][$i])){
                    $errors[($i+1).'_question3'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['question4']['videos'][$i])){
                    $errors[($i+1).'_question4'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['video_single_url']['videos'][$i])){
                    $errors[($i+1).'_video_single_url'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['video_title']['videos'][$i])){
                    $errors[($i+1).'_video_title'] = "This field is required.";
                    $validation = true;
                }
                if(!empty($inputData['yeslink']['videos'][$i]) && $inputData['yeslink']['videos'][$i] == 1){
                    if(empty($inputData['link_name']['videos'][$i])){
                        $errors[($i+1).'_link_name'] = "This field is required.";
                        $validation = true;
                    }else{
                        $link = $inputData['link_name']['videos'][$i];
                        $linkarr=explode("/",$link);
                        if(isset($linkarr[2])){
                            if($linkarr[2] == 'www.dropbox.com' || $linkarr[2] == 'drive.google.com' || $linkarr[2] == '1drv.ms'|| $linkarr[2] == 'www.icloud.com' || $linkarr[2] == 'wetransfer.com'){
                                //return true;
                                $validation = false;
                            }else{
                                $errors[($i+1).'_link_name'] = "Enter valid url";
                                $validation = true;
                            }
                        }else{
                            $errors[($i+1).'_link_name'] = "Enter valid url";
                            $validation = true;
                        }
                    }

                }else{
                    if(!isset($inputData['url_multi']['videos'][$inputData['uid_mul']['videos'][$i]])){
                        $errors[($i+1).'_file'] = "This field is required.";
                        $validation = true;
                    }
                }


            }
            $response['code'] = 201;
            $response['message'] = 'Your lead has been not submitted yet, Please recheck your form carefully.';
            $response['error'] = $errors;
            //header("Content-type: application/json");
            $response['url'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            $validation = false;
            $errors = array();

            foreach ($inputData['video_title']['videos'] as $i=>$video_title){
               if(empty($inputData['question1']['videos'][$i])){
                   $errors[($i+1).'_question1'] = "This field is required.";
                   $validation = true;
               }
                if(empty($inputData['question3']['videos'][$i])){
                    $errors[($i+1).'_question3'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['question4']['videos'][$i])){
                    $errors[($i+1).'_question4'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['video_single_url']['videos'][$i])){
                    $errors[($i+1).'_video_single_url'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['video_title']['videos'][$i])){
                    $errors[($i+1).'_video_title'] = "This field is required.";
                    $validation = true;
                }



                if(!empty($inputData['yeslink']['videos'][$i]) && $inputData['yeslink']['videos'][$i] == 1){
                    if(empty($inputData['link_name']['videos'][$i])){
                        $errors[($i+1).'_link_name'] = "This field is required.";
                        $validation = true;
                    }else{
                        $link = $inputData['link_name']['videos'][$i];
                        $linkarr=explode("/",$link);
                        if(isset($linkarr[2])){
                            if($linkarr[2] == 'www.dropbox.com' || $linkarr[2] == 'drive.google.com' || $linkarr[2] == '1drv.ms'|| $linkarr[2] == 'www.icloud.com' || $linkarr[2] == 'wetransfer.com'){
                                //return true;
                                $validation = false;
                            }else{
                                $errors[($i+1).'_link_name'] = "Enter valid url";
                                $validation = true;
                            }
                        }else{
                            $errors[($i+1).'_link_name'] = "Enter valid url";
                            $validation = true;
                        }
                    }

                }else{
                    if(!isset($inputData['url_multi']['videos'][$inputData['uid_mul']['videos'][$i]])){
                        $errors[($i+1).'_file'] = "This field is required.";
                        $validation = true;
                        $response['message'] = 'Raw video is required to proceed.';
                    }
                }


            }

            if($validation){
                $response['message'] = 'Your lead has been not submitted yet, Please recheck your form carefully.';
                $response['code'] = 201;

                $response['error'] = $errors;
                header("Content-type: application/json");
                $response['url'] = '';
                echo json_encode($response);
                exit;
            }
            $full_name=$inputData['first_name'].' '.$inputData['last_name'];
            $paypal_email='';
            $email_check='false';
            if(isset($inputData['paypal'])){
                $paypal_email=$inputData['paypal'];
            }
            $phone=$inputData['phone'];
            $country_code=$inputData['country_code'];
            $country=$country_code.'-'.$inputData['country'];
            $state=$inputData['state'];
            $city=$inputData['city'];
            $address=$inputData['address'];
            $haveOrignalVideo = 'No';
            $shotVideo = 'No';
            if(isset($inputData['address2'])){
                $address2=$inputData['address2'];
            }
            $zip=$inputData['zip'];
            //$raw_file=$inputData['fileuploader-list-file'];
            if(isset($inputData['ageVideo'])){
                $age=1;
            }
           /* if(isset($inputData['shotVideo'])){
                $shotVideo=1;
            }
            if(isset($inputData['termsShared'])){
                $haveOrignalVideo=1;
            }*/

            if(isset($inputData['shotVideo'])){
                $shotVideo = 'Yes';
            }
            if(isset($inputData['termsShared']) ){
                $haveOrignalVideo = 'Yes';
            }
            $haveOrignalVideo = 'Yes';
            if(isset($inputData['newsletter'])){
                $this->load->library('MailChimp');
                $list_id = '6685154911';

                $result = $this->mailchimp->post("lists/$list_id/members", [
                    'email_address' => $inputData['email'],
                    'status'        => 'subscribed',
                ]);
                $newsletter=1;
            }else{
                $newsletter=0;
            }
            if(isset($inputData['eu'])){
                $eu=1;
            }else{
                $eu=0;
            }


            $sigimag=substr($inputData['img'],5);
            $signature = $sigimag;
            $date =date("Y/m/d");
            //$data['video_title']=$inputData['video_title'];
            //$slug = slug($inputData['video_title'], 'video_leads', 'slug');
            //$data['video_url']=$inputData['video_single_url'];
            if(isset($inputData['img_url'])){
                $data['thumbnail']=$inputData['img_url'];
            }else{
                $data['thumbnail']= "";
            }

            //$data['question1']=$inputData['question1'];
            //$data['question3']=$inputData['question3'];
            //$data['question4']=$inputData['question4'];
            $email= $inputData['email'];
            $lead_id='';
            $unique_key='';
            $leadIds = array();

            //Insert Lead data
            // Randomly generate unique key for leads.
            foreach ($inputData['video_title']['videos'] as $i=>$video_title){
                $unique_key_list = array_column($this->db->distinct()->select('unique_key')->get('video_leads')->result_array(), 'unique_key');
                $this->load->helper('string');
                $random = random_string('numeric',6);
                while (in_array("WGA".$random, $unique_key_list))
                {
                    $random = random_string('numeric',6);
                }
                if(!empty($random)) {
                    $slug = slug($video_title, 'video_leads', 'slug');
                    $unique_key = "WGA".$random;
                    $kslug=$slug.'-'.$unique_key;
                    $insert_lead = array(
                        'slug' => $kslug,
                        'unique_key' =>$unique_key,
                        'information_pending' => 1,
                        'shotVideo' => $shotVideo,
                        'haveOrignalVideo' => $haveOrignalVideo,
                        'status' => 1,
                        'first_name' => $inputData['first_name'],
                        'last_name' => $inputData['last_name'],
                        'email' => $email,
                        'video_title' => $video_title,
                        'video_url' => $inputData['video_single_url']['videos'][$i],
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                        'lead_type' => 'scout',
                        'social_video_id' => extrect_video_id($inputData['video_single_url']['videos'][$i]),

                    );
                    $lead_id = $this->upload_video->upload_video($insert_lead);
                    $leadIds[$lead_id] = array();
                    if($inputData['staff_party'] == 2){
                        $insert_staff = array(
                            $staff_id= 'staff_id' => $inputData['staff_id'],
                        );
                        $this->db->where('id', $lead_id);
                        $this->db->update('video_leads', $insert_staff);

                    }else if($inputData['staff_party'] == 3){
                        $insert_staff = array(
                            $staff_id= 'third_party_staff_id' => $inputData['staff_id'],

                        );
                        $this->db->where('id', $lead_id);
                        $this->db->update('video_leads', $insert_staff);
                    }

                    $data['slug']=$kslug;
                    $data['lead_id']=$lead_id;
                    $leadIds[$lead_id]['slug'] = $kslug;
                    $leadIds[$lead_id]['lead_id'] = $lead_id;
                    $leadIds[$lead_id]['video_title'] = $video_title;
                    $leadIds[$lead_id]['video_url'] = $inputData['video_single_url']['videos'][$i];
                    $leadIds[$lead_id]['yeslink'] = $inputData['yeslink']['videos'][$i];
                    $leadIds[$lead_id]['link_name'] = $inputData['link_name']['videos'][$i];
                    $leadIds[$lead_id]['uid'] = $inputData['uid_mul']['videos'][$i];
                    $leadIds[$lead_id]['thumbnail'] = '';
                    $leadIds[$lead_id]['unique_key'] = $unique_key;

                }
            }

                //Extract users data from email
            $email_check_query=$this->db->query('SELECT * FROM `users` WHERE role_id =1  and email = "'.$email.'"');
            /*$result_emails =$email_check_query->result();
            foreach ($result_emails as $result_email){
                //Check if user already exit
                if($result_email->email == $email ){
                    $email_check='true';
                    continue;
                }
            }*/

            if($email_check_query->num_rows() == 0){
                //Add new user in table with given details in contract
                $user_insert= $this->user->insertUser($full_name,$email,$age,$paypal_email,$phone,$country,$state,$city,$address,$address2,$zip,$newsletter,$eu);
                $data['user_id']=$user_insert;
                $string_client_id =array(
                    'client_id' =>  $data['user_id']
                );
                $this->db->where('id', $lead_id);
                $this->db->update('video_leads', $string_client_id);
                action_add($lead_id,0,0,0,0,'Already have a client');
            }else{
                //$email_check_query=$this->db->query('SELECT id FROM `users` WHERE email="'.$email.'" AND role_id =1');
                $user_query=$email_check_query->row();
                
                $data['user_id']=$user_query->id;
                $string = array(
                    'full_name' => $full_name,
                    'paypal_email' => $paypal_email,
                    'country_code' => $country,
                    'mobile' => $phone,
                    'age'=>$age,
                    'address' => $address,
                    'address2' => $address2,
                    'city_id' => $city,
                    'state_id' => $state,
                    'zip_code' => $zip,
                    'newsletter' => $newsletter,
                    'eu'=> $eu
                );
                $this->db->where('id', $data['user_id']);
                $this->db->update('users', $string);
                //Adding client id tu lead data in client already created
                $string_client_id =array(
                    'client_id' =>  $data['user_id']
                );
                $this->db->where('id', $lead_id);
                $this->db->update('video_leads', $string_client_id);
                action_add($lead_id,0,0,0,0,'Already have a client');
            }


            $video_check_query=$this->db->query('SELECT id FROM `videos` WHERE lead_id="'.$lead_id.'"');
            $video_query=$video_check_query->result();
            if(isset($video_query[0])){
                $video_id_exit=$video_query[0]->id;
            }else{
                $video_id_exit='';
            }
            if($video_id_exit){
                $response['code'] = 200;
                $response['message'] = 'Data already exits';
                $response['error'] = '';
                $response['url'] = '';
                header("Content-type: application/json");
                echo json_encode($response);
                exit;
            }
            else{
                $j = 0;
                foreach ($leadIds as $lead_id => $lead_data){
                    $this->db->where('id', $lead_id);
                    $this->db->update('video_leads', array('client_id' =>  $data['user_id']));
                    $data['slug'] = $lead_data['slug'];
                    $data['lead_id'] = $lead_data['lead_id'];
                    $data['video_title'] = $lead_data['video_title'];
                    $data['video_url'] = $lead_data['video_url'];
                    $data['thumbnail'] = $lead_data['thumbnail'];
                    $data['question1'] = $inputData['question1']['videos'][$j];
                    $data['question3'] = $inputData['question3']['videos'][$j];
                    $data['question4'] = $inputData['question4']['videos'][$j];

                    $video_insert= $this->profile->insert_video_data($data);
                   /* echo $this->db->last_query();
                    exit();*/
                    //Update lead data load view 4
                    // $update = $this->profile->update_pending($lead_id);
                    $view_update = $this->profile->update_view('4',$lead_id);
                    //Add raw video data
                    $raw_data = array();
                    if(isset($lead_data['yeslink']) && $lead_data['yeslink'] == 1) {
                        if(!empty($lead_data['link_name'])){
                            $file_data['client_link'] = $lead_data['link_name'];
                            $file_data['video_id'] = $video_insert;
                            $file_data['lead_id'] = $lead_id;
                            $this->profile->insert_client_link($file_data);
                        }
                    }else {
                        foreach ($inputData['url_multi']['videos'][$lead_data['uid']] as $url) {
                            $unique_key = $lead_data['unique_key'];
                            $old_url=$url;
                            $urls_old= explode('/',$old_url);
                            $urls= explode('/',$url);
                            $urls[1]= $unique_key;
                            $urls[3]= $unique_key."_".$urls[3];
                            $url=implode('/',$urls);
                            $urls_old[3]= $unique_key."_".$urls_old[3];
                            $url_new=implode('/',$urls);
                            $url_new_old=implode('/',$urls_old);
                            if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] !='wooglobe.com') {
                                @rename($_SERVER["DOCUMENT_ROOT"] .$this->config->item('local_dir') . '/' . $old_url, $_SERVER["DOCUMENT_ROOT"] .$this->config->item('local_dir') . '/' . $url_new_old);
                            }else {
                                @rename($_SERVER["DOCUMENT_ROOT"] . $old_url, $_SERVER["DOCUMENT_ROOT"] . $url_new_old);
                            }
                            $raw_data['url'] = $url_new;
                            $raw_data['video_id'] = $video_insert;
                            $raw_data['lead_id'] = $lead_id;
                            $result = $this->profile->insert_raw_video($raw_data);
                        }
                    }
                    action_add($lead_id,0,0,0,0,'Info updated successfully');
                    $urlValue = explode(".",$data['video_url']);
                    /*if($urlValue[1] == "youtube" || $urlValue[0] == "youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "youtu" || $urlValue[0] == "https://youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "https://youtu"  || $urlValue[0] == "http://youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "http://youtu" || $urlValue[1] == "facebook" || $urlValue[0] == "facebook" || $urlValue[0] == "https://facebook" || $urlValue[0] == "http://facebook" || $urlValue[1] == "instagram" || $urlValue[0] == "instagram" || $urlValue[0] == "https://instagram" ||  $urlValue[0] == "http://instagrm" || $urlValue[1] == "instagr" || $urlValue[0] == "instagr"){
                        $response['url'] = $this->data['url'].'submit-video-description/'.$data['slug'];
                    } else{*/
                    $response['url'] = $this->data['url'].'submit-video';
                    //}
                    $contract_log['contract_view_datetime'] = date('Y-m-d H:i:s', time());
                    $contract_log['lead_id'] = $lead_id;
                    $result = $this->profile->insert_contract_logs($contract_log);
                    $update_contract_log['user_browser'] = $_SERVER['HTTP_USER_AGENT'];
                    $update_contract_log['user_ip_address'] = $this->getUserIpAddr();
                    $update_contract_log['contract_signed_datetime'] = date('Y-m-d H:i:s', time());
                    $update_contract_log['lead_id'] = $lead_id;
                    $result = $this->profile->update_contract_logs($update_contract_log);
                    if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] != 'wooglobe.com') {
                        @rename(root_path().$this->config->item('local_dir') . '/uploads/'.$lead_data['uid'],root_path().$this->config->item('local_dir') . '/uploads/'.$lead_data['unique_key']);
                        @mkdir(root_path(). $this->config->item('local_dir') . '/uploads/'.$lead_data['unique_key'],0777);
                        $this->base30_to_jpeg ( $signature, root_path(). $this->config->item('local_dir'). '/uploads/'.$lead_data['unique_key'].'/documents/'.$lead_data['unique_key'].'.png' );
                    }else{
                        @rename(root_path(). 'uploads/'.$lead_data['uid'],root_path(). '/uploads/'.$lead_data['unique_key']);
                        @mkdir(root_path(). '/uploads/'.$lead_data['unique_key'],0777);
                        $this->base30_to_jpeg ( $signature, root_path(). '/uploads/'.$lead_data['unique_key'].'/documents/'.$lead_data['unique_key'].'.png' );
                    }
                    $j++;
                }
                //Add Video data


                //Add action




            }
        }
        if(!empty($response['error']['img'])){
            $response['error']['img'] = strip_tags($response['error']['img']);
        }
        //exit;
        header("Content-type: application/json");
        echo json_encode($response);
        exit;
    }
    public function submit_viral_video_form()
    {
        $link = $this->uri->segment(2);
        $result = $this->profile->getAdminByLink($link);
        $admin_res=$result->result();
        $staff_party='';
        if(!empty($admin_res)){
            if(isset($admin_res[0])){
                $admin_status =$admin_res[0]->status;
            }
            $staff_party = 2;
        }else{
            $result = $this->profile->getThirdPartyByLink($link);
            $admin_res=$result->result();
            if(isset($admin_res[0])){
                $admin_status =$admin_res[0]->status;
            }
            $staff_party = 3;
        }

        if($admin_status == 0){
            $this->data['profile_nav'] = 'Link Expired';
            $this->data['page_content'] = getContentById(2);
            $this->data['content'] = $this->load->view('contract_link_expired',$this->data,true);
            $this->load->view('common_files/template',$this->data);
        }else{
            $this->data['title'] = 'Submit Video';
            $this->data['staff_party'] = $staff_party;
            $this->data['tid'] = time();
            $this->data['staff'] =$admin_res;
            $this->data['upload_js'][] = 'jquery.fileuploader.min';
            $this->data['upload_js'][] = 'custom1';
            $this->data['countries'] = $this->location->getCountries();
            $this->data['content'] = $this->load->view('submit_viral_video', $this->data, true);
            $this->load->view('common_files/template', $this->data);
        }
    }
    public function simple_viral_video(){
        $inputData = $this->security->xss_clean($this->input->post());
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Contract Signed and Video Added Successfully!';
        $response['error'] = '';

        $this->validation->set_rules('first_name','First Name','trim|required');
        $this->validation->set_rules('last_name','Last Name','trim|required');
        $this->validation->set_rules('email','Email','trim|required|valid_email');
        //$this->validation->set_rules('phone','Phone','trim|required');
        //$this->validation->set_rules('city','City','trim|required');
        //$this->validation->set_rules('state','State','trim|required');
        //$this->validation->set_rules('country_code','Country_code','trim|required');
        //$this->validation->set_rules('country','Country','trim|required');
        //$this->validation->set_rules('address','Address','trim|required');

        /* $this->validation->set_rules('question1','question1','trim|required');
         $this->validation->set_rules('question3','question3','trim|required');
         $this->validation->set_rules('question4','question4','trim|required');
         $this->validation->set_rules('video_single_url','Video URL','required|is_unique[video_leads.video_url]|callback_valid_url');
         $this->validation->set_rules('video_title','Video Title','trim|required');*/

        $this->validation->set_rules('shotVideo','Video Title','trim|required');
        $this->validation->set_rules('ageVideo','Video Title','trim|required');
        //$this->validation->set_rules('zip','Zip Code','trim|required');
        /* $this->validation->set_rules('termsShared','Video Title','trim|required');
         if(isset($inputData['yeslink'])) {
             $this->validation->set_rules('link_name', 'File', 'trim|required|callback_validate_url');
         }else{
             $this->validation->set_rules('fileuploader-list-file', 'File', 'trim|required|callback_validate_video');
         }*/
        /*if(isset($inputData['yespaypal'])) {
            $this->validation->set_rules('paypal','Paypal','trim|required');
        }*/
        $this->validation->set_rules('img','signature','trim|required');
        if(isset($inputData['file_link'])) {
            $this->validation->set_rules('terms_check', 'terms_check', 'trim');
        }else{
            $this->validation->set_rules('terms_check', 'terms_check', 'trim|required');
        }
        $this->validation->set_message('required','This field is required.');
        $this->validation->set_message('is_unique','This video link was previously submitted, please contact customer services.');
        if($this->validation->run() === false){

            $fields = array('first_name','last_name','email','phone','question1','zip','question3','question4','age','city','state','country','country_code','address','fileuploader-list-file','img','video_single_url','video_title','link_name','terms_check');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            foreach ($inputData['video_title']['videos'] as $i=>$video_title){
                if(empty($inputData['question1']['videos'][$i])){
                    $errors[($i+1).'_question1'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['question3']['videos'][$i])){
                    $errors[($i+1).'_question3'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['question4']['videos'][$i])){
                    $errors[($i+1).'_question4'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['video_single_url']['videos'][$i])){
                    $errors[($i+1).'_video_single_url'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['video_title']['videos'][$i])){
                    $errors[($i+1).'_video_title'] = "This field is required.";
                    $validation = true;
                }

                if(!empty($inputData['yeslink']['videos'][$i]) && $inputData['yeslink']['videos'][$i] == 1){
                    if(empty($inputData['link_name']['videos'][$i])){
                        $errors[($i+1).'_link_name'] = "This field is required.";
                        $validation = true;
                    }else{
                        $link = $inputData['link_name']['videos'][$i];
                        $linkarr=explode("/",$link);
                        if(isset($linkarr[2])){
                            if($linkarr[2] == 'www.dropbox.com' || $linkarr[2] == 'drive.google.com' || $linkarr[2] == '1drv.ms'|| $linkarr[2] == 'www.icloud.com' || $linkarr[2] == 'wetransfer.com'){
                                //return true;
                                $validation = false;
                            }else{
                                $errors[($i+1).'_link_name'] = "Enter valid url";
                                $validation = true;
                            }
                        }else{
                            $errors[($i+1).'_link_name'] = "Enter valid url";
                            $validation = true;
                        }
                    }

                }else{
                    if(!isset($inputData['url_multi']['videos'][$inputData['uid_mul']['videos'][$i]])){
                        $errors[($i+1).'_file'] = "This field is required.";
                        $validation = true;
                    }
                }


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
            $validation = false;
            $errors = array();
            foreach ($inputData['video_title']['videos'] as $i=>$video_title){
                if(empty($inputData['question1']['videos'][$i])){
                    $errors[($i+1).'_question1'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['question3']['videos'][$i])){
                    $errors[($i+1).'_question3'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['question4']['videos'][$i])){
                    $errors[($i+1).'_question4'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['video_single_url']['videos'][$i])){
                    $errors[($i+1).'_video_single_url'] = "This field is required.";
                    $validation = true;
                }
                if(empty($inputData['video_title']['videos'][$i])){
                    $errors[($i+1).'_video_title'] = "This field is required.";
                    $validation = true;
                }



                if(!empty($inputData['yeslink']['videos'][$i]) && $inputData['yeslink']['videos'][$i] == 1){
                    if(empty($inputData['link_name']['videos'][$i])){
                        $errors[($i+1).'_link_name'] = "This field is required.";
                        $validation = true;
                    }else{
                        $link = $inputData['link_name']['videos'][$i];
                        $linkarr=explode("/",$link);
                        if(isset($linkarr[2])){
                            if($linkarr[2] == 'www.dropbox.com' || $linkarr[2] == 'drive.google.com' || $linkarr[2] == '1drv.ms'|| $linkarr[2] == 'www.icloud.com' || $linkarr[2] == 'wetransfer.com'){
                                //return true;
                                $validation = false;
                            }else{
                                $errors[($i+1).'_link_name'] = "Enter valid url";
                                $validation = true;
                            }
                        }else{
                            $errors[($i+1).'_link_name'] = "Enter valid url";
                            $validation = true;
                        }
                    }

                }else{
                    if(!isset($inputData['url_multi']['videos'][$inputData['uid_mul']['videos'][$i]])){
                        $errors[($i+1).'_file'] = "This field is required.";
                        $validation = true;
                    }
                }


            }

            if($validation){

                $response['code'] = 201;
                $response['message'] = 'Your lead has been not submitted yet, Please recheck your form carefully.';
                $response['error'] = $errors;
                header("Content-type: application/json");
                $response['url'] = '';
                echo json_encode($response);
                exit;
            }
            $full_name=$inputData['first_name'].' '.$inputData['last_name'];
            $paypal_email='';
            $email_check='false';
            if(isset($inputData['paypal'])){
                $paypal_email=$inputData['paypal'];
            }
            $phone=$inputData['phone'];
            $country_code=$inputData['country_code'];
            $country=$country_code.'-'.$inputData['country'];
            $state=$inputData['state'];
            $city=$inputData['city'];
            $address=$inputData['address'];
            $haveOrignalVideo = 'No';
            $shotVideo = 'No';
            $zip=$inputData['zip'];
            //$raw_file=$inputData['fileuploader-list-file'];
            if(isset($inputData['ageVideo'])){
                $age=1;
            }
            /* if(isset($inputData['shotVideo'])){
                 $shotVideo=1;
             }
             if(isset($inputData['termsShared'])){
                 $haveOrignalVideo=1;
             }*/

            if(isset($inputData['shotVideo'])){
                $shotVideo = 'Yes';
            }
            if(isset($inputData['termsShared']) ){
                $haveOrignalVideo = 'Yes';
            }
            $haveOrignalVideo = 'Yes';
          /*  if(isset($inputData['newsletter'])){
                $this->load->library('MailChimp');
                $list_id = '6685154911';

                $result = $this->mailchimp->post("lists/$list_id/members", [
                    'email_address' => $inputData['email'],
                    'status'        => 'subscribed',
                ]);
                $newsletter=1;
            }else{
                $newsletter=0;
            }*/



            $sigimag=substr($inputData['img'],5);
            $signature = $sigimag;
            $date =date("Y/m/d");
            //$data['video_title']=$inputData['video_title'];
            //$slug = slug($inputData['video_title'], 'video_leads', 'slug');
            //$data['video_url']=$inputData['video_single_url'];
            if(isset($inputData['img_url'])){
                $data['thumbnail']=$inputData['img_url'];
            }else{
                $data['thumbnail']= "";
            }

            //$data['question1']=$inputData['question1'];
            //$data['question3']=$inputData['question3'];
            //$data['question4']=$inputData['question4'];
            $email= $inputData['email'];
            $lead_id='';
            $unique_key='';
            $leadIds = array();
            //Insert Lead data
            // Randomly generate unique key for leads.
            foreach ($inputData['video_title']['videos'] as $i=>$video_title){
                $unique_key_list = array_column($this->db->distinct()->select('unique_key')->get('video_leads')->result_array(), 'unique_key');
                $this->load->helper('string');
                $random = random_string('numeric',6);
                while (in_array("WGA".$random, $unique_key_list))
                {
                    $random = random_string('numeric',6);
                }
                if(!empty($random)) {
                    $slug = slug($video_title, 'video_leads', 'slug');
                    $unique_key = "WGA".$random;
                    $kslug=$slug.'-'.$unique_key;
                    $insert_lead = array(
                        'slug' => $kslug,
                        'unique_key' =>$unique_key,
                        'information_pending' => 1,
                        'shotVideo' => $shotVideo,
                        'haveOrignalVideo' => $haveOrignalVideo,
                        'status' => 1,
                        'first_name' => $inputData['first_name'],
                        'last_name' => $inputData['last_name'],
                        'email' => $email,
                        'video_title' => $video_title,
                        'video_url' => $inputData['video_single_url']['videos'][$i],
                        'created_at' => date("Y-m-d H:i:s"),
                        'updated_at' => date("Y-m-d H:i:s"),
                        'simple_video' => 1,
                        'lead_type' => 'simple',
                        'social_video_id' => extrect_video_id($inputData['video_single_url']['videos'][$i]),


                    );
                    $lead_id = $this->upload_video->upload_video($insert_lead);
                    $leadIds[$lead_id] = array();
                    if($inputData['staff_party'] == 2){
                        $insert_staff = array(
                            $staff_id= 'staff_id' => $inputData['staff_id'],
                        );
                        $this->db->where('id', $lead_id);
                        $this->db->update('video_leads', $insert_staff);

                    }else if($inputData['staff_party'] == 3){
                        $insert_staff = array(
                            $staff_id= 'third_party_staff_id' => $inputData['staff_id'],

                        );
                        $this->db->where('id', $lead_id);
                        $this->db->update('video_leads', $insert_staff);
                    }

                    $data['slug']=$kslug;
                    $data['lead_id']=$lead_id;
                    $leadIds[$lead_id]['slug'] = $kslug;
                    $leadIds[$lead_id]['lead_id'] = $lead_id;
                    $leadIds[$lead_id]['video_title'] = $video_title;
                    $leadIds[$lead_id]['video_url'] = $inputData['video_single_url']['videos'][$i];
                    $leadIds[$lead_id]['yeslink'] = $inputData['yeslink']['videos'][$i];
                    $leadIds[$lead_id]['link_name'] = $inputData['link_name']['videos'][$i];
                    $leadIds[$lead_id]['uid'] = $inputData['uid_mul']['videos'][$i];
                    $leadIds[$lead_id]['thumbnail'] = $inputData['img_url']['videos'][$i];
                    $leadIds[$lead_id]['unique_key'] = $unique_key;

                }
            }

            //Extract users data from email
            $email_check_query=$this->db->query('SELECT * FROM `users` WHERE role_id =1  and email = "'.$email.'"');
            /*$result_emails =$email_check_query->result();
            foreach ($result_emails as $result_email){
                //Check if user already exit
                if($result_email->email == $email ){
                    $email_check='true';
                    continue;
                }
            }*/

            if($email_check_query->num_rows() == 0){
                //Add new user in table with given details in contract
                $user_insert= $this->user->insertUser($full_name,$email,$age,$paypal_email,$phone,$country,$state,$city,$address,null,$zip,null,null);
                $data['user_id']=$user_insert;
                $string_client_id =array(
                    'client_id' =>  $data['user_id']
                );
                $this->db->where('id', $lead_id);
                $this->db->update('video_leads', $string_client_id);
                action_add($lead_id,0,0,0,0,'Already have a client');
            }else{
                //$email_check_query=$this->db->query('SELECT id FROM `users` WHERE email="'.$email.'" AND role_id =1');
                $user_query=$email_check_query->row();

                $data['user_id']=$user_query->id;
                $string = array(
                    'full_name' => $full_name,
                    'paypal_email' => $paypal_email,
                    'country_code' => $country,
                    'mobile' => $phone,
                    'age'=>$age,
                    'address' => $address,
                    'city_id' => $city,
                    'state_id' => $state,
                    'zip_code' => $zip,
                );
                $this->db->where('id', $data['user_id']);
                $this->db->update('users', $string);
                //Adding client id tu lead data in client already created
                $string_client_id =array(
                    'client_id' =>  $data['user_id']
                );
                $this->db->where('id', $lead_id);
                $this->db->update('video_leads', $string_client_id);
                action_add($lead_id,0,0,0,0,'Already have a client');
            }


            $video_check_query=$this->db->query('SELECT id FROM `videos` WHERE lead_id="'.$lead_id.'"');
            $video_query=$video_check_query->result();
            if(isset($video_query[0])){
                $video_id_exit=$video_query[0]->id;
            }else{
                $video_id_exit='';
            }
            if($video_id_exit){
                $response['code'] = 200;
                $response['message'] = 'Data already exits';
                $response['error'] = '';
                $response['url'] = '';
                header("Content-type: application/json");
                echo json_encode($response);
                exit;
            }
            else{
                $j = 0;
                foreach ($leadIds as $lead_id => $lead_data){
                    $this->db->where('id', $lead_id);
                    $this->db->update('video_leads', array('client_id' =>  $data['user_id']));
                    $data['slug'] = $lead_data['slug'];
                    $data['lead_id'] = $lead_data['lead_id'];
                    $data['video_title'] = $lead_data['video_title'];
                    $data['video_url'] = $lead_data['video_url'];
                    $data['thumbnail'] = $lead_data['thumbnail'];
                    $data['question1'] = $inputData['question1']['videos'][$j];
                    $data['question3'] = $inputData['question3']['videos'][$j];
                    $data['question4'] = $inputData['question4']['videos'][$j];

                    $video_insert= $this->profile->insert_video_data($data);
                    /* echo $this->db->last_query();
                     exit();*/
                    //Update lead data load view 4
                    // $update = $this->profile->update_pending($lead_id);
                    $view_update = $this->profile->update_view('4',$lead_id);
                    //Add raw video data 
                    $raw_data = array();
                    if(isset($inputData[$lead_id]['yeslink']) && $inputData[$lead_id]['yeslink'] == 1) {
                        if(!empty($inputData[$lead_id]['link_name'])){
                            $file_data['client_link'] = $inputData[$lead_id]['link_name'];
                            $file_data['video_id'] = $video_insert;
                            $file_data['lead_id'] = $lead_id;
                            $this->profile->insert_client_link($file_data);
                        }
                    }else {
                        foreach ($inputData['url_multi']['videos'][$lead_data['uid']] as $url) {
                            $unique_key = $lead_data['unique_key'];
                            $old_url=$url;
                            $urls_old= explode('/',$old_url);
                            $urls= explode('/',$url);
                            $urls[1]= $unique_key;
                            $urls[3]= $unique_key."_".$urls[3];
                            $url=implode('/',$urls);
                            $urls_old[3]= $unique_key."_".$urls_old[3];
                            $url_new=implode('/',$urls);
                            $url_new_old=implode('/',$urls_old);
                            if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] !='wooglobe.com') {
                                rename($_SERVER["DOCUMENT_ROOT"] . $this->config->item('local_dir',true) . '/' . $old_url, $_SERVER["DOCUMENT_ROOT"] . $this->config->item('local_dir',true) . '/' . $url_new_old);

                            }else {
                                rename($_SERVER["DOCUMENT_ROOT"] . $old_url, $_SERVER["DOCUMENT_ROOT"] . $url_new_old);
                            }
                            $raw_data['url'] = $url_new;
                            $raw_data['video_id'] = $video_insert;
                            $raw_data['lead_id'] = $lead_id;
                            $result = $this->profile->insert_raw_video($raw_data);
                        }
                    }
                    action_add($lead_id,0,0,0,0,'Info updated successfully');
                    $urlValue = explode(".",$data['video_url']);
                    /*if($urlValue[1] == "youtube" || $urlValue[0] == "youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "youtu" || $urlValue[0] == "https://youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "https://youtu"  || $urlValue[0] == "http://youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "http://youtu" || $urlValue[1] == "facebook" || $urlValue[0] == "facebook" || $urlValue[0] == "https://facebook" || $urlValue[0] == "http://facebook" || $urlValue[1] == "instagram" || $urlValue[0] == "instagram" || $urlValue[0] == "https://instagram" ||  $urlValue[0] == "http://instagrm" || $urlValue[1] == "instagr" || $urlValue[0] == "instagr"){
                        $response['url'] = $this->data['url'].'submit-video-description/'.$data['slug'];
                    } else{*/
                    $response['url'] = $this->data['url'].'submit-success';
                    //}
                    $contract_log['contract_view_datetime'] = date('Y-m-d H:i:s', time());
                    $contract_log['lead_id'] = $lead_id;
                    $result = $this->profile->insert_contract_logs($contract_log);
                    $update_contract_log['user_browser'] = $_SERVER['HTTP_USER_AGENT'];
                    $update_contract_log['user_ip_address'] = $this->getUserIpAddr();
                    $update_contract_log['contract_signed_datetime'] = date('Y-m-d H:i:s', time());
                    $update_contract_log['lead_id'] = $lead_id;
                    $result = $this->profile->update_contract_logs($update_contract_log);
                    if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] !='wooglobe.com') {
                        @rename(root_path(). $this->config->item('local_dir',true).'/uploads/'.$lead_data['uid'],root_path(). '/uploads/'.$lead_data['unique_key']);
                        @mkdir(root_path().$this->config->item('local_dir',true) . '/uploads/'.$lead_data['unique_key'],0777);
                        $this->base30_to_jpeg ( $signature, root_path(). '/uploads/'.$lead_data['unique_key'].'/documents/'.$lead_data['unique_key'].'.png' );
                    }else{
                        @rename(root_path(). 'uploads/'.$lead_data['uid'],root_path(). '/uploads/'.$lead_data['unique_key']);
                        @mkdir(root_path(). '/uploads/'.$lead_data['unique_key'],0777);
                        $this->base30_to_jpeg ( $signature, root_path(). '/uploads/'.$lead_data['unique_key'].'/documents/'.$lead_data['unique_key'].'.png' );
                    }
                    $j++;
                }
                //Add Video data


                //Add action




            }
        }
        //exit;
        header("Content-type: application/json");
        echo json_encode($response);
        exit;
    }
    public function simple_viral_video_form()
    {

        $link = $this->uri->segment(2);
        $result = $this->profile->getAdminBysimpleLink($link);
        $admin_res=$result->result();
     /*   print_r($admin_res);
        exit();*/
        $staff_party='';
        if(!empty($admin_res)){

            if(isset($admin_res[0])){
                $admin_status =$admin_res[0]->status;
            }
            $staff_party = 2;
        }else{
            $result = $this->profile->getThirdPartyByLink($link);
            $admin_res=$result->result();
            if(isset($admin_res[0])){
                $admin_status =$admin_res[0]->status;
            }
            $staff_party = 3;
        }

        if($admin_status == 0){
            $this->data['profile_nav'] = 'Link Expired';
            $this->data['page_content'] = getContentById(2);
            $this->data['content'] = $this->load->view('contract_link_expired',$this->data,true);
            $this->load->view('common_files/template',$this->data);
        }else{
            $this->data['title'] = 'Submit Video';
            $this->data['staff_party'] = $staff_party;
            $this->data['tid'] = time();
            $this->data['staff'] =$admin_res;
            $this->data['upload_js'][] = 'jquery.fileuploader.min';
            $this->data['upload_js'][] = 'custom1';
            $this->data['countries'] = $this->location->getCountries();
            $this->data['content'] = $this->load->view('simple_viral_video', $this->data, true);
            $this->load->view('common_files/template', $this->data);
        }
    }
    // Old form functions
    public function video_signed_pdf($full_name,$email,$paypal_email,$phone,$country,$state,$city,$address,$zip,$date,$signature,$revenue_share,$unique_key,$data,$data_log,$api_result){
        // signature convertion from base 30 to  jpg
        $this->base30_to_jpeg ( $signature, root_path(). '/uploads/'.$unique_key.'/documents/'.$unique_key.'.png' );
        $signimgpath= root_path().'/uploads/'.$unique_key.'/documents/'.$unique_key.'.png';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $certificate = 'file://'.root_path().'/app/libraries/TCPDF-master/examples/data/cert/tcpdf.crt';
        $certificate = 'file://'.root_path().'/app/libraries/TCPDF-master/examples/data/cert/signature.crt';

// set additional information
        $info = array(
            'Name' => 'WooGlobe',
            'Location' => 'viral@wooglobe.com',
            'Reason' => 'WooGlobe Contract Signing',
            'ContactInfo' => 'https://wooglobe.com/',
        );

// set document signature
        $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('WooGlobe');
        $pdf->SetTitle('WooGlobe');
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
// add a page
        $pdf->AddPage('L','','A4');

        $html = '<img src="'.root_path().'/assets/img/wooglobe-con-1.png" width="1000px" height="600px">';
        $pdf->writeHTML($html);
        /*$fontname = TCPDF_FONTS::addTTFfont('calibri.ttf', 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 12);*/
// add a page
        $pdf->AddPage('P','','A4');
        $html = '
<style>
        h1.heading {
            text-align: center;
        }

    </style>
        <h1 class="heading"><FONT FACE="Helvetica Neue, serif">WooGlobe Content Agreement</FONT></h1>
        <h1 class="heading"><FONT FACE="Helvetica Neue, serif">Summary</FONT></h1>

<p STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">Thank
you for agreeing to use WooGlobe to distribute your video worldwide
across the web, TV and other platforms. Your agreement with WooGlobe
explains what permissions you give to us, what our role is and how we
work to earn you money. The full terms are set out in the WooGlobe
Content Agreement, which you should read carefully. A summary of the
key points in the Content Agreement is:</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Uses:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">This
agreement gives WooGlobe and its partners the rights they need to
distribute and use your video(s) worldwide. Your video(s) may appear
on websites, on TV shows and in films, in advertising, in public
places and in any other type of media. We will exclusively manage
your video(s) on YouTube and similar platforms (no
restrictions).</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Exclusivity:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">The
rights you grant to us are worldwide and exclusive.&nbsp;</SPAN></FONT></p>
<p STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><U><B>Term:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">The
term of this agreement is perpetual. We need this in order to best
protect and monetise your video(s). </SPAN></FONT><FONT COLOR="#000000">You
may seek to terminate this Agreement under the conditions as set
forth in Section 4 of the agreement. <BR><BR></FONT><FONT COLOR="#000000"><U><B>Earnings:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">We
will pay you agreed percentage, as set forth in agreement, of any
money earned from your video.</SPAN></FONT><FONT COLOR="#000000"> </FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">We
will do our best to earn you as much revenue as possible but we
cannot make any promises as to how much will be
earned.</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Ownership:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">You
must have taken any videos you submit to us. You will retain your
copyright ownership of them. </SPAN></FONT>
</p>
<p STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><U><B>Privacy:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">If
your videos feature any identifiable people, you must make sure you
have their permission to submit your videos to us and for us to use
them. The information you provide to us may be shared with our
clients and we, or our clients may contact you to further verify this
information.</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Your
obligations:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">You
must ensure that your videos are lawful, that you own them and that
you are entitled to allow us to distribute them. Please make sure
that you comply with the WooGlobe Content Agreement at all times.&nbsp;</SPAN></FONT></p>';
// output the HTML content
        $pdf->writeHTML($html);

        $pdf->AddPage('P','','A4');

        $html = '

<style>
       h1.heading {
            text-align: center;
        }

        .top-form,
        .bottom-form {
            background-color: #ececec;
           
            
        }
        
        td {
            text-align: center;
        }

    </style>
        <h1 class="heading">VIDEO OWNER AGREEMENT</h1>
       
                <div class="top-form"  style="border: 3px solid #30538f;border-radius: 10px" >
                    <h3>&nbsp;Video Owner / Licensor</h3>
                        
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Full Name <font color="red">*</font> :'.$full_name.'</p>
                        <p >&nbsp;&nbsp;&nbsp;&nbsp;Email <font color="red">*</font> : '.$email.' &nbsp;&nbsp; Phone <font color="red">*</font> : '.$phone.'</p>
                        <p >&nbsp;&nbsp;&nbsp;&nbsp;Address <font color="red">*</font> : '.$address.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;City <font color="red">*</font> :'.$city.'&nbsp;&nbsp;  State <font color="red">*</font> : '.$state.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Zip Code <font color="red">*</font> : '.$zip.'  &nbsp;&nbsp;Country <font color="red">*</font> : '.$country.'</p>
                        
                </div>
                <div class="bottom-form" style="border: 3px solid #30538f">
                    <h3>&nbsp;Image(s): (i.e. your video(s))</h3>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;Title: '.$data["video_title"].'</p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;URL: '.$data["video_url"].'</p>
                        <small>&nbsp;&nbsp;&nbsp;&nbsp;Includes all additional footage (e.g. "B-roll", raw footage, etc) submitted by Licensor
                            to WooGlobe in connection with the Images. Does not include Licensor\'s channel or other
                            works unless expressly stated.</small>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;Share: '.$revenue_share.'%</p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;Where was this video filmed?<font color="red">*</font>: '.$data["question1"].'</p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;When was this video filmed?<font color="red">*</font>: '.$data["question3"].'</p>
                </div>
                    <h3>Declaration</h3>
                    <ul>
                        <li>I am 18 years of age or older and I either shot this video all by myself or own full
                            rights to the video.</li>
                        <li>By signing the agreement, I acknowledge that I have read and understood the detailed
                            WooGlobe Ltd. content agreement below, and that I accept and agree to adhere to all of
                            its terms, which includes the exclusive grant of rights of video to WooGlobe Ltd.</li>
                    </ul>
                    <div>
                    <table>
                        <tr>
                            <td>'.$full_name.'</td>
                            <td></td>
                            <td>'.$date.'</td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #7f7f7f">Name</td>
                            <td style="border-top: 1px solid #7f7f7f">Signature</td>
                            <td style="border-top: 1px solid #7f7f7f">Date</td>
                        </tr>
                    </table>  
                </div>


';

        $pdf->writeHTML($html);

        $pdf->Image($signimgpath, 100, 244, 18, 14, 'PNG');

// define active area for signature appearance
        $pdf->setSignatureAppearance(100, 244, 18, 14);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// *** set an empty signature appearance ***
        $pdf->addEmptySignatureAppearance(100, 255, 18, 14);



        $pdf->AddPage('P','','A4');
        $html = '

<style>
        h1.heading {
            text-align: center;
        }
 td {
            text-align: center;
        }
    </style>
      
<p style="text-align: right;"><FONT COLOR="#7f7f7f"><FONT FACE="Helvetica Neue, serif"><FONT SIZE=2 STYLE="font-size: 10pt">WooGlobe Ltd.<br />
16 Weir Road, <br />
London, DA51BJ<br />
UK</FONT></FONT></FONT></p>

<h1 class="heading"><FONT FACE="Helvetica Neue, serif">Agreement Terms and Conditions</FONT></h1>

<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>1.
Licensed Rights</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">.
Licensor grants WooGlobe the exclusive, unlimited right to use,
refrain from using, change, alter, edit, modify, add to, subtract
from and rearrange the Images and to exhibit, distribute, broadcast,
reproduce, license others to reproduce and distribute, advertise,
promote, publish and otherwise exploit the Images by any and all
methods or means, whether now known or hereafter devised, in any
manner and in any and all media throughout the world, in perpetuity,
for any purpose whatsoever as WooGlobe in its sole discretion may
determine (the &quot;Licensed Rights&quot;), including for the
purpose of marketing, advertising, and promotion. Licensor
furthermore does hereby irrevocably appoint WooGlobe as its
attorney-in-fact to take any such action as may from time to time be
necessary to effect, transfer, or assign the rights granted to
WooGlobe herein, including without limitation copyright-related
actions, and assigns to WooGlobe the right to prosecute any and all
claims from the past, present, and future use of the Images by
unauthorized third parties. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>2.
Payments to Licensor.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
In full consideration of all of the Licensed Rights granted
hereunder, WooGlobe will pay Licensor the Share of the net revenue
earned and received by WooGlobe from the exhibition, distribution,
broadcast, licensing and other exploitation of the Licensed Rights,
less proceeds received from uses intended to generate marketable
interest in the Images. Licensor shall be responsible for any taxes
relating to payments it receives to the appropriate tax authority and
governmental entities. Licensor must deliver to WooGlobe agreement to
these terms, any additional information requested by WooGlobe
relating to the Images, and the above-described images in a format
acceptable to WooGlobe in order to receive payment. Licensor
must provide the best quality video file available to WooGlobe and
add a line to any site where Licensor has previously posted the work,
stating: For licensing or usage, contact:licensing@wooglobe.com.</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">WooGlobe
shall process the payment to Licensor within fifteen (15) days after
the end of every quarter (i.e 15th April, 15th July, 15th Oct, 15th
Jan); however, if the amount owed to Licensor is less than </FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">seventy
five</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
US dollars ($75 USD), WooGlobe reserves the right to carry the
royalty over for payment to Licensor until the amount exceeds </FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">seventy
five</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
US dollars ($75 USD).</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
If the amount never exceeds seventy-five US dollars ($75 USD) or if
WooGlobe ceases license acquisition operations, then no Payment will
come due. </FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">WooGlobe
shall not be responsible for any Payments to Licensor for revenue
earned in connection with the images but not received by WooGlobe for
any reason (for example, due to non-payment, or where WooGlobe does
not receive adequate reporting so as to enable WooGlobe to assign
revenue). Licensor agrees that if the outstanding Payment does not
exceed seventy-five US Dollars ($75 USD) for a period of twenty-four
(24) months, account maintenance costs will exceed expected future
revenue. In this event, any outstanding Payment will be charged as a
maintenance fee, and no future Payments are due. Licensor may choose
to be paid via PayPal, or electronic bank transfer (the “Payment
Method”). Any electronic bank transfer fees will be deducted from
the Licensor’s Payment prior to sending. Licensor agrees to provide
WooGlobe all the necessary and accurate information required to
process the Payment (the “Payment Details’) via their preferred
Payment Method. If Licensor fails to provide Payment Details to
WooGlobe within sixty (60) days of the execution of this Agreement or
the expiration of provided Payment Details, Licensor will forfeit the
outstanding Payment balance to WooGlobe. If after sixty (60) days
Licensor updates Payment Details, WooGlobe will make Payments to the
Licensor in accordance with the above terms for Net Revenue earned
for the period after Payment Details are updated. Licensor further
understands that Payments may be subject to withholding tax which
will be paid on behalf of Licensor to the appropriate tax authority.
</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Licensor
agrees that WooGlobe is entitled to deduct a reasonable sum from any
revenue to cover the costs incurred to generate interest in the
images. For the avoidance of doubt, any such deductions shall be made
prior to our calculation of the revenue share.</FONT></FONT></p>';
        $pdf->writeHTML($html);
        $pdf->AddPage('P','','A4');
        $html = '
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><u><b>3.
Licensor Representations and Warranties. </b></u></FONT></FONT><br />
<FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">(a)
Owner of Rights: Licensor has the sole, exclusive and unencumbered
ownership of all rights of every kind and character throughout the
universe in and to the Licensed Rights and has clear title to the
material upon which the Images are based. Licensor has the absolute
right to grant to WooGlobe, all rights, licenses and privileges
granted to or vested in WooGlobe under this Agreement. Licensor has
not authorized and will not authorize any other party to exercise any
right or take any action that impairs the rights herein granted to
WooGlobe. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">(b)
Rights Are Clear: Licensor has obtained all clearances and paid all
monies necessary for WooGlobe to exercise its exclusive rights
hereunder and there will not be any other rights to be cleared or any
payments required to be made by WooGlobe as a result of any use of
the Images pursuant to the rights and licenses herein granted
(including without limitation, payments in connection with contingent
participations, residuals, clearance rights, moral rights, union
fees, and music rights). Licensor has not previously entered into any
other agreement in connection with the Images. All of the individuals
and entities connected with the production of the Images, and all of
the individuals and entities whose names, voices, photographs,
likenesses, appearance, works, services and other materials appear or
have been used in the Images, have authorized and approved Licensor’s
use thereof, and WooGlobe shall have the right to use all names,
voices, photographs, likenesses, appearance and performances
contained in the Images in connection with the exploitation,
promotion, and use of the Licensed Rights. It is expressly understood
that WooGlobe has not assumed any obligations under any contracts
entered into by Licensor. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">(c)
No Infringement: No part of the Images, any materials contained
therein, or the exercise by WooGlobe of the Licensed Rights violates
or will violate, or infringes or will infringe, any trademark, trade
name, contract, agreement, copyright (whether common law or
statutory), patent, literary, artistic, music, dramatic, personal,
private, civil, property, privacy or publicity right or &quot;moral
rights of authors&quot; or any other right of any person or entity,
and shall not give rise to a claim of slander or libel. There are no
existing, anticipated, or threatened claims or litigation that would
adversely affect or impair any of the Licensed Rights. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>4.
Termination.</B></U>
Licensor may seek to terminate this Agreement after one year of
signing the agreement; however, this Agreement shall only be
terminable upon the mutual agreement of the parties, the consent of
which may be granted or denied in WooGlobe’s sole discretion. No
termination shall impact any prior license of the Images by WooGlobe
prior to termination, which shall continue in full effect under the
terms of this Agreement. Any
use of the images in promotions or compilations created by WooGlobe
or its affiliates, prior to the termination of this agreement, shall
survive termination and that such use shall not be a breach of any of
Licensor’s rights. WooGlobe may terminate this agreement
immediately with no obligation to the Licensor if Licensor is in
breach of any term of the contract. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>5.
Release and Indemnity.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor hereby agrees to indemnify, release and hold harmless
WooGlobe, its successors, licensees, sub-distributors and assigns,
and the directors, officers, employees, representatives and agents of
each of the foregoing, from any and all claims, demands, causes of
action, damages, judgments, liabilities, losses, costs, expenses, and
attorney’s fees arising out of or resulting from (i) any breach by
Licensor of any warranty, representation or any other provision of
this Agreement, and/or (ii) any claims of or respecting slander,
libel, defamation, invasion of privacy or right of publicity, false
light, infringement of copyright or trademark, or violations of any
other rights arising out of or relating to any use by WooGlobe of the
rights granted under this Agreement. Licensor acknowledges that
WooGlobe is relying on the representations contained in this
Agreement and a breach by Licensor would cause WooGlobe irrevocable
injury and damage that cannot be adequately compensated by damages in
an action at law and Licensor therefore expressly agrees that,
without limiting WooGlobe’s remedies, WooGlobe shall be entitled to
injunctive and other equitable relief. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>6.
No Guarantee Regarding Revenue.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor acknowledges and agrees that WooGlobe is not making any
representation, guarantee or agreement regarding the total amount of
revenue, if any, which will be generated by the Licensed Rights.
Licensor agrees that the judgment of WooGlobe regarding the
exploitation of the Licensed Rights shall be binding and conclusive
upon Licensor and agrees not to make any claim or action that
WooGlobe has not properly exploited the Licensed Rights, that more
revenue could have been earned than was actually earned by the
exploitation of the Licensed Rights, or that any buyout or one-time
payment to Licensor is insufficient in comparison to the revenue
earned by the exploitation of the Licensed Rights. Nothing in this
Agreement shall obligate WooGlobe to actually use or to exploit the
Licensed Rights. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>7.
Publicity/Confidentiality.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor shall not release, disseminate, issue, authorize or cause
the release, dissemination or issuance of any publicity or
information concerning the Licensed Rights, WooGlobe, or the terms of
this Agreement without WooGlobe’s prior specific written consent
(including, without limitation, posting, participating or engaging in
social media discussions, news stories, blogs, reports or responses
thereto), and Licensor shall direct all licensing or other inquiries
relating to the Images solely to WooGlobe. The parties acknowledge
that the terms and provisions of this Agreement are confidential in
nature and agree not to disclose the content or substance thereof to
any third parties other than: (i) the parties’ respective attorneys
and accountants, (ii) as may be necessary to defend Licensor’s
and/or WooGlobe’s rights, and/or (iii) as may be reasonably
required in order to comply with any obligations imposed by the
Agreement, or any statute, ordinance, rule, regulation, other law, or
court order. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>8.
Confidentiality.</B></U>
Licensor acknowledges that the terms and provisions of this Agreement
are confidential in nature and agrees not to disclose the content or
substance thereof to any third parties, other than Licensor\'s
respective attorneys and accountants, or as may be reasonably
required in order to comply with any obligations imposed by this
Agreement. Licensor acknowledges that any unauthorized disclosure,
statement, or publicity may subject WooGlobe to substantial damages,
the exact amount of which are extremely difficult and impractical to
determine, and such unauthorized disclosure shall subject Licensor to
legal liability (including an injunction to prevent further
disclosure). </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>9.
Electronic Signature Agreement.</B></U>
The Licensor agrees that by entering their name into the space
designated above or through the use of any electronic signature
software/service or by any other means, Licensor is agreeing to the
terms of this agreement electronically. The Licensor agrees that the
electronic signature is the legal equivalent of manual signature on
this Agreement and that no certification authority or other third
party verification is necessary to validate Licensor’s e-signature.
The lack of such certification or third party verification will not
in any way affect the enforceability of Licensor’s e-signature or
any resulting contract between Licensor and WooGlobe. </FONT></FONT>
</p>
<p><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>10.
Severability.</B></U></FONT><FONT SIZE=2 STYLE="font-size: 11pt"> If any
provision of this Agreement is illegal and unenforceable in whole or
in part, the remainder of this Agreement shall remain enforceable to
the extent permitted by law. </FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>11.
Miscellaneous.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor acknowledges and warrants that this Agreement has not been
induced by any representation or assurance not contained herein. This
Agreement supersedes and replaces all prior agreements, negotiations
or understandings in connection with the Licensed Rights, including
without limitation any simplified explanation of the terms herein,
and in the event there are any inconsistencies between this
English-language contract and any translations of terms and
conditions, the English-language version shall prevail. This
Agreement contains the entire understanding of the parties and shall
not be modified or amended except by a written document executed by
both parties. If any provision of this Agreement is found to be
unlawful or unenforceable, such provision shall be limited only to
the extent necessary, with all other provisions of the Agreement
remaining in effect. The waiver by either party or consent to a
breach of any provision of this Agreement by the other party shall
not operate or be construed as a waiver of, consent to, or excuse of
any other or subsequent breach by the other party. WooGlobe shall
have the right to assign freely this Agreement, the Licensed Rights
and/or any of WooGlobe’s other rights hereunder to any person or
entity (by operation of law or otherwise). Licensor may not assign
this Agreement or the Licensed Rights. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>12.
Choice of Law/Dispute Resolution.</B></U>
This Agreement shall be deemed to have been executed and delivered
within England, UK, and the rights and obligations of the parties
hereunder shall be construed and enforced in accordance with
English law, without regard to the conflicts of law principles
thereof. Any disputes relating to these terms and conditions shall be
subject to the non-exclusive jurisdiction of the courts of England.
The parties agree to the personal jurisdiction by and venue in
England, and waive any objection to such jurisdiction or venue
irrespective of the fact that a party may not be a resident of
England. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Except
for WooGlobe’s equitable rights as set forth in this Agreement, the parties hereby
agree to submit any disputes or controversies arising from, relating
to or in connection with this Agreement or the parties’ respective
obligations in connection therewith to binding arbitration in England
in accordance with the English law and only for actual monetary
damages, if any. In
the event of any dispute, Licensor shall not be entitled to, and does
hereby waive all right to, any equitable relief whatsoever, including
the right to rescind its agreement to these Terms, to rescind any
rights granted hereunder, or to enjoin, restrain or interfere in any
manner with the marketing, advertisement, distribution or
exploitation of the Licensed Rights. All rights to recover
consequential, incidental and/or punitive damages are waived by
Licensor.</FONT></FONT></p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>13.
Terms &amp; Conditions</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><B>.</B></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor may be required to agree to additional terms and conditions
displayed on the WooGlobe website at www.WooGlobe.com and its
partners in connection with the management of this Agreement and the
payments related thereto, which will be incorporated herein by
reference and subject to change. </FONT></FONT>
</p>
';
        $pdf->writeHTML($html);

        $pdf->AddPage('P','','A4');
        $addres_html='';
        $location_country='';
        $location_region_code='';
        $location_city='';
        $location_continent_name='';
        $location_zip='';
        $location_latitude='';
        $location_longitude='';
        if(isset($api_result['country_name'])){
            $location_country =$api_result['country_name'];
        }
        if(isset($api_result['region_code'])){
            $location_region_code =$api_result['region_code'];
        }
        if(isset($api_result['city'])){
            $location_city =$api_result['city'];
        }
        if(isset($api_result['continent_name'])){
            $location_continent_name =$api_result['continent_name'];
        }
        if(isset($api_result['zip'])){
            $location_zip =$api_result['zip'];
        }
        if(isset($api_result['latitude'])){
            $location_latitude =$api_result['latitude'];
        }
        if(isset($api_result['longitude'])){
            $location_longitude =$api_result['longitude'];
            $addres_html =' <p>Continent : '.$location_continent_name.'</p>
        <p>Country : '.$location_country.'</p>';
        }

        $html = '<p style="text-align: left"><img src="'.root_path().'/admin/assets/assets/img/logo.png" width="80px" height="80px"></p>
        <span style="font-size: 30px">Signing Log</span>
        <p>Document id : '.$unique_key.'</p>
        <p>----------------------------------------------------------------------------------------------------------------------------</p>
        <p>WooGlobe</p>
        <p>Document Name:   '.$unique_key.'_signed.pdf</p>
        <p>Sent on:  '.$data_log["lead_rated_date"].' GMT</p>
        <p>Ip address: '.$data_log["user_ip_address"].'</p>
        <p>User Agent: '.$data_log["user_browser"].'</p>
        <p>Contrat Signed date time:  '.$data_log["contract_signed_datetime"].' GMT</p>'.$addres_html.'<br>
        ';

        $pdf->writeHTML($html);
// reset pointer to the last page
        $pdf->lastPage();
//Close and output PDF document
        $output_file=root_path(). '/uploads/'.$unique_key.'/documents/'.$unique_key.'_signed.pdf';
        if (! file_exists ( dirname ( $output_file ) )) {
            mkdir(dirname($output_file),0777,true);
        }
        $pdf->Output($output_file, 'F');

    }
    public function video_contract($slug)
    {

        $slug = $this->uri->segment(2);
        $result = $this->profile->getLeadBySlug($slug);

        $requests = $result->row_array();
       /* echo '<pre>';
        print_r($requests);
        exit;*/

        $results = $this->profile->checkView($requests['client_id'], $requests['id']);
        $results = $results->row_array();

        $users = $this->profile->checkUsers($requests['client_id']);
        $lead_res=$result->result();
        if(isset($lead_res[0])){
            $lead_status =$lead_res[0]->status;
        }
        if($lead_status == 3 || $lead_status == 6 || $lead_status == 8 || $lead_status == 11){
            $this->data['profile_nav'] = 'Link Expired';
            $this->data['page_content'] = getContentById(2);
            $this->data['content'] = $this->load->view('contract_link_expired',$this->data,true);
            $this->load->view('common_files/template',$this->data);
            action_add($requests['id'],0,0,0,0,'link expired');

        }
        else {
            action_add($requests['id'],0,0,0,0,'link Viewd');
            $logs_check_query=$this->db->query('SELECT id FROM `contract_logs` WHERE lead_id="'.$requests['id'].'"');
            $logs_query=$logs_check_query->result();
            $log_id='';
            if(isset($logs_query[0])){
                $log_id=$logs_query[0]->id;
            }
            if(!empty($log_id)){
                $contract_log['contract_view_datetime'] = date('Y-m-d H:i:s', time());
                $contract_log['lead_id'] = $requests['id'];
                $result = $this->profile->update_contract_logs($contract_log);
            }else{
                $contract_log['contract_view_datetime'] = date('Y-m-d H:i:s', time());
                $contract_log['lead_id'] = $requests['id'];
                $result = $this->profile->insert_contract_logs($contract_log);
            }
            $this->data['slug'] = $slug;
            $this->data['title'] = 'Submit Video';
            $this->data['videos'] = $requests;
            $this->data['video'] = $results;
            $this->data['load_view'] = $requests['load_view'];
            $this->data['users'] = $users;
            $this->data['upload_js'][] = 'jquery.fileuploader.min';
            $this->data['upload_js'][] = 'custom1';
            $this->data['countries'] = $this->location->getCountries();
            $this->data['content'] = $this->load->view('video_contract', $this->data, true);
            $this->load->view('common_files/template', $this->data);
        }
    }
    public function video_signed_contract(){

        $inputData = $this->security->xss_clean($this->input->post());
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Contract Signed and Video Added Successfully!';
        $response['error'] = '';

        $this->validation->set_rules('first_name','First Name','trim|required');
        $this->validation->set_rules('last_name','Last Name','trim|required');
        $this->validation->set_rules('email','Email','trim|required');
        $this->validation->set_rules('phone','Phone','trim|required');
        $this->validation->set_rules('city','City','trim|required');
        $this->validation->set_rules('state','State','trim|required');
        $this->validation->set_rules('country_code','Country_code','trim|required');
        $this->validation->set_rules('country','Country','trim|required');
        $this->validation->set_rules('address','Address','trim|required');
        $this->validation->set_rules('question1','question1','trim|required');
        $this->validation->set_rules('question3','question3','trim|required');
        $this->validation->set_rules('question4','question4','trim|required');
        if(isset($inputData['yeslink'])) {
            $this->validation->set_rules('link_name', 'File', 'trim|required|callback_validate_url');
        }else{
            $this->validation->set_rules('fileuploader-list-file', 'File', 'trim|required|callback_validate_video');
        }
        if(isset($inputData['yespaypal'])) {
            $this->validation->set_rules('paypal','Paypal','trim|required');
        }
        $this->validation->set_rules('img','signature','trim|required');
        if(isset($inputData['file_link'])) {
            $this->validation->set_rules('terms_check', 'terms_check', 'trim');
        }else{
            $this->validation->set_rules('terms_check', 'terms_check', 'trim|required');
        }
        $this->validation->set_message('required','This field is required.');
        if($this->validation->run() === false){

            $fields = array('first_name','last_name','email','phone','question1','question3','question4','age','city','state','country','country_code','address','paypal','fileuploader-list-file','img','link_name','terms_check');
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
            $full_name=$inputData['first_name'].' '.$inputData['last_name'];
            $paypal_email='';
            $email_check='false';
            if(isset($inputData['paypal'])){
                $paypal_email=$inputData['paypal'];
            }
            $phone=$inputData['phone'];
            $country_code=$inputData['country_code'];
            $country=$country_code.'-'.$inputData['country'];
            $state=$inputData['state'];
            $city=$inputData['city'];
            $address=$inputData['address'];
            if(isset($inputData['address2'])){
                $address2=$inputData['address2'];
            }
            $zip=$inputData['zip'];
            $unique_key=$inputData['unique_key'];
            $revenue_share=$inputData['revenue_share'];
            $raw_file=$inputData['fileuploader-list-file'];
            if(isset($inputData['ageVideo'])){
                $age=1;
            }
            if(isset($inputData['shotVideo'])){
                $shotVideo=1;
            }
            if(isset($inputData['termsShared'])){
                $haveOrignalVideo=1;
            }
            if(isset($inputData['newsletter'])){
                $this->load->library('MailChimp');
                $list_id = '6685154911';

                $result = $this->mailchimp->post("lists/$list_id/members", [
                    'email_address' => $inputData['email'],
                    'status'        => 'subscribed',
                ]);
                $newsletter=1;
            }else{
                $newsletter=0;
            }
            if(isset($inputData['eu'])){
                $eu=1;
            }else{
                $eu=0;
            }
            $sigimag=substr($inputData['img'],5);
            $signature = $sigimag;
            $date =date("Y/m/d");
            $lead_id=$inputData['lead_id'];
            $data['video_title']=$inputData['video_title'];
            $data['slug']=$inputData['slug'];
            $data['lead_id']=$inputData['lead_id'];
            $data['video_url']=$inputData['video_single_url'];
            $data['thumbnail']=$inputData['img_url'];
            $data['question1']=$inputData['question1'];
            $data['question3']=$inputData['question3'];
            $data['question4']=$inputData['question4'];
            $email= $inputData['email'];
            //Extract users data from email
            $email_check_query=$this->db->query('SELECT email FROM `users`');
            $result_emails =$email_check_query->result();
            foreach ($result_emails as $result_email){
                //Check if user already exit
                if($result_email->email == $email ){
                    $email_check='true';
                    continue;
                }
            }
            if($email_check == 'false'){
                //Add new user in table with given details in contract
                $user_insert= $this->user->insertUser($full_name,$email,$age,$paypal_email,$phone,$country,$state,$city,$address,$address2,$zip,$newsletter,$eu);
                $data['user_id']=$user_insert;
            }else{
                $email_check_query=$this->db->query('SELECT id FROM `users` WHERE email="'.$email.'"');
                $user_query=$email_check_query->result();
                $data['user_id']=$user_query[0]->id;
                $string = array(
                    'full_name' => $full_name,
                    'paypal_email' => $paypal_email,
                    'country_code' => $country,
                    'mobile' => $phone,
                    'age'=>$age,
                    'address' => $address,
                    'address2' => $address2,
                    'city_id' => $city,
                    'state_id' => $state,
                    'zip_code' => $zip,
                    'newsletter' => $newsletter,
                    'eu'=> $eu
                );
                $this->db->where('id', $data['user_id']);
                $this->db->update('users', $string);
                //Adding client id tu lead data in client already created
                $string_client_id =array(
                    'client_id' =>  $data['user_id']
                );
                $this->db->where('id', $lead_id);
                $this->db->update('video_leads', $string_client_id);
                action_add($lead_id,0,0,0,0,'Already have a client');
            }
            $video_check_query=$this->db->query('SELECT id FROM `videos` WHERE lead_id="'.$lead_id.'"');
            $video_query=$video_check_query->result();
            if(isset($video_query[0])){
                $video_id_exit=$video_query[0]->id;
            }else{
                $video_id_exit='';
            }
            if($video_id_exit){
                $response['code'] = 200;
                $response['message'] = 'Data already exits';
                $response['error'] = '';
                $response['url'] = '';
                echo json_encode($response);
                exit;
            }
            else{
                //Add Video data
                $video_insert= $this->profile->insert_video_data($data);
                //Update lead data with status 3 and information pending 1 and load view 4 and shotVideo 1 and have orignal video 1
                $update = $this->profile->update_pending($lead_id);
                $view_update = $this->profile->update_view('4',$lead_id);
                //Add raw video data
                $raw_data = array();
                if(isset($inputData['yeslink'])) {
                    if(!empty($inputData['link_name'])){
                        $file_data['client_link'] = $inputData['link_name'];
                        $file_data['video_id'] = $video_insert;
                        $file_data['lead_id'] = $lead_id;
                        $this->profile->insert_client_link($file_data);
                    }
                }else {
                    foreach ($inputData['url'] as $url) {
                        $raw_data['url'] = $url;
                        $raw_data['video_id'] = $video_insert;
                        $raw_data['lead_id'] = $lead_id;


                        $result = $this->profile->insert_raw_video($raw_data);
                    }
                }
                //Add action
                action_add($lead_id,0,0,0,0,'Info updated successfully');
                $urlValue = explode(".",$data['video_url']);
                if($urlValue[1] == "youtube" || $urlValue[0] == "youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "youtu" || $urlValue[0] == "https://youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "https://youtu"  || $urlValue[0] == "http://youtube" || $urlValue[1] == "youtu" || $urlValue[0] == "http://youtu" || $urlValue[1] == "facebook" || $urlValue[0] == "facebook" || $urlValue[0] == "https://facebook" || $urlValue[0] == "http://facebook" || $urlValue[1] == "instagram" || $urlValue[0] == "instagram" || $urlValue[0] == "https://instagram" ||  $urlValue[0] == "http://instagrm" || $urlValue[1] == "instagr" || $urlValue[0] == "instagr"){
                    $response['url'] = $this->data['url'].'submit-video-description/'.$data['slug'];
                } else{
                    $response['url'] = $this->data['url'].'submit-video';
                }
                $contract_log['user_browser'] = $_SERVER['HTTP_USER_AGENT'];
                $contract_log['user_ip_address'] = $this->getUserIpAddr();
                $contract_log['contract_signed_datetime'] = date('Y-m-d H:i:s', time());
                $contract_log['lead_id'] = $lead_id;
                $result = $this->profile->update_contract_logs($contract_log);
                // set IP address and API access key
                $ip = $contract_log['user_ip_address'];
                $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';

// Initialize CURL:
                $ch = curl_init('http://api.ipstack.com/'.$ip.'?access_key='.$access_key.'');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
                $json = curl_exec($ch);
                curl_close($ch);

// Decode JSON response:
                $api_result = json_decode($json, true);

                $data_log_query=$this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="'.$lead_id.'"');
                $logs_query=$data_log_query->result();
                $sent_log_query=$this->db->query('SELECT lead_rated_date  FROM `lead_action_dates` WHERE `lead_id` = "'.$lead_id.'"');
                $sent_log_result=$sent_log_query->result();
                if(isset($logs_query[0])){
                    $data_log['user_browser']=$logs_query[0]->user_browser;
                    $data_log['user_ip_address']=$logs_query[0]->user_ip_address;
                    $data_log['contract_signed_datetime']=$logs_query[0]->contract_signed_datetime;
                    $data_log['contract_view_datetime']=$logs_query[0]->contract_view_datetime;
                }
                if(isset($sent_log_result[0])){
                    $data_log['lead_rated_date']=$sent_log_result[0]->lead_rated_date;
                }
                //Generate Pdf
                if($country){
                    $country_name=explode("-",$country);
                    if(isset($country_name[1])){
                        $country_name_va=$country_name[1];
                    }
                }
                $result_pdf=$this->video_signed_pdf($full_name,$email,$paypal_email,$phone,$country_name_va,$state,$city,$address,$zip,$date,$signature,$revenue_share,$unique_key,$data,$data_log,$api_result);
                //Send Email
                /*$email_temp = $this->app->getTemplateByShortCode('deal_information_received');

                if($email_temp){

                    $str = $email_temp->message;
                    $subject = $email_temp->subject;
                    $from = 'no-reply@wooglobe.com';

                    $ids =  $ids = array(
                        'video_leads' => $lead_id
                    );
                    $message = dynStr($str, $ids);
                    $file_to_attach = root_path().'/uploads/'.$unique_key.'/documents/'.$unique_key.'_signed.pdf';
                    $result = $this->email($email, $full_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '',$file_to_attach,$unique_key);
                }else{
                    action_add($lead_id,0,0,0,0,'No email Template');
                }*/
            }
        }
        echo json_encode($response);
        exit;
    }

    // Validation functions
    public function validate_url($link_name){
        $link = $this->security->xss_clean($link_name);
        $linkarr=explode("/",$link);
        if(isset($linkarr[2])){
            if($linkarr[2] == 'www.dropbox.com' || $linkarr[2] == 'drive.google.com' || $linkarr[2] == '1drv.ms'|| $linkarr[2] == 'www.icloud.com' || $linkarr[2] == 'wetransfer.com'){
                return true;
            }else{
                $this->validation->set_message('validate_url', 'Enter valid url');
                return false;
            }
        }else{
            $this->validation->set_message('validate_url', 'Enter valid url');

            return false;
        }
    }
    public function validate_video($video){
        $video_data = $this->security->xss_clean($video);
        if(isset($video_data)){
            if($video_data == '[]'){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    // Acquire video after rejection functions
    public function acquired_client_videos($slug)
    {

        $slug = $this->uri->segment(2);
        $result = $this->profile->getLeadBySlug($slug);

        $requests = $result->row_array();


        $results = $this->profile->checkView($requests['client_id'], $requests['id']);
        $results = $results->row_array();

        $users = $this->profile->checkUsers($requests['client_id']);
        $lead_res=$result->result();
        if(isset($lead_res[0])){
            $load_view =$lead_res[0]->load_view;
        }
        if($load_view == 4){
            $this->data['profile_nav'] = 'Link Expired';
            $this->data['page_content'] = getContentById(2);
            $this->data['content'] = $this->load->view('contract_link_expired',$this->data,true);
            $this->load->view('common_files/template',$this->data);

        }
        else {
            action_add($requests['id'],0,0,0,0,'Video Aquired Contract Viewed');
            $this->data['slug'] = $slug;
            $this->data['title'] = 'Submit Video';
            $this->data['videos'] = $requests;
            $this->data['video'] = $results;
            $this->data['load_view'] = $requests['load_view'];
            $this->data['users'] = $users;
            $this->data['upload_js'][] = 'jquery.fileuploader.min';
            $this->data['upload_js'][] = 'custom1';
            $this->data['countries'] = $this->location->getCountries();
            $this->data['content'] = $this->load->view('acquired_client_videos', $this->data, true);
            $this->load->view('common_files/template', $this->data);
        }
    }
    public function acquired_client_video_submit(){
        $inputData = $this->security->xss_clean($this->input->post());
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Video Added Successfully!';
        $response['error'] = '';

        if(isset($inputData['yeslink'])) {
            $this->validation->set_rules('link_name', 'File', 'trim|required|callback_validate_url');
        }else{
            $this->validation->set_rules('fileuploader-list-file', 'File', 'trim|required|callback_validate_video');
        }
        $this->validation->set_message('required','This field is required.');
        if($this->validation->run() === false){

            $fields = array('fileuploader-list-file','link_name');
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

            $lead_id=$inputData['lead_id'];
            $video_id='';
            $video_check_query=$this->db->query('SELECT id FROM `videos` WHERE lead_id="'.$lead_id.'"');
            $video_query=$video_check_query->result();
            if(isset($video_query[0])){
                $video_id=$video_query[0]->id;
            }
            $view_update = $this->profile->update_view('4',$lead_id);
            //Add raw video data
            $raw_data = array();
            if(isset($inputData['yeslink'])) {
                $file_data['client_link'] = $inputData['link_name'];
                $file_data['video_id'] = $video_id;
                $file_data['lead_id'] = $lead_id;
                $this->profile->insert_client_link($file_data);
            }else {
                foreach ($inputData['url'] as $url) {
                    $raw_data['url'] = $url;
                    $raw_data['video_id'] = $video_id;
                    $raw_data['lead_id'] = $lead_id;


                    $result = $this->profile->insert_raw_video($raw_data);
                }
            }
            //Add action
            action_add($lead_id,0,0,0,0,'client added video successfully');

            $response['url'] = $this->data['url'];
        }
        echo json_encode($response);
        exit;
    }

    public function passwod_update()
    {
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Password Changed Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('old','Old Password','trim|required|callback_validate_old_password');
        $this->validation->set_rules('new','New Password','trim|required|regex_match[/(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/]');
        $this->validation->set_rules('confirm','Confirm New Password','trim|required|matches[new]');
        $this->validation->set_message('required','This field is required.');
        $this->validation->set_message('regex_match','Password must contain UpperCase, LowerCase, Number, SpecialChar and min 8 Chars.');
        $this->validation->set_message('matches','Confirm new password does not match with new password.');

        if($this->validation->run() === false){

            $fields = array('old','new','confirm');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{

            $password = $this->security->xss_clean($this->input->post('new'));
            $dbData['password'] = md5($password);
            $this->db->where('id',$this->sess->userdata('clientId'));
            $this->db->update('users',$dbData);
            $this->load->model('Auth_Model', 'auth');
            $this->auth->unblockAccountByEmail($this->sess->userdata('clientEmail'));
        }

        echo json_encode($response);
        exit;
    }

    public function validate_old_password_ajax()
    {
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $password = $this->security->xss_clean($this->input->post('password'));
        $userPassword =  $this->data['userData']->password;
        if(md5($password) == $userPassword){
            echo json_encode(1);
        }else{
            http_response_code(404);
            echo json_encode(1);
        }
        exit;


    }

    public function validate_old_password($password){
        $password = $this->security->xss_clean($password);
        $userPassword =  $this->data['userData']->password;
        if(!empty($password)) {
            if (md5($password) == $userPassword) {
                return true;
            } else {
                $this->validation->set_message('validate_old_password', 'Invalid old password.');
                return false;
            }
        }else{
            $this->validation->set_message('validate_old_password', 'This field is required.');
            return false;
        }
    }


	public function upload_video()
	{
        auth();

		$this->data['title'] = 'Upload Video';
		$this->data['profile_nav'] = 'upload-video';
		$this->data['videosTypes'] = $this->profile->getVideoTypes();
        $this->data['js'][] = 'video_upload';
        $this->data['content'] = $this->load->view('profile_upload',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}

    public function dashboard()
    {
        auth();
        $email = $this->sess->userdata('clientEmail');
        $id = $this->sess->userdata('clientId');
        $videos = $this->profile->getAllDealsByEmail($email);
        $earningsVideos = $this->profile->getUserEarnings($id,null,'v.title ASC','v.id');
        $page = $this->input->get('page');
        $slug = $this->input->get('video');
        if(empty($page)){
            $page = 'dashboard';
        }
        $earnings = $this->profile->getUserEarnings($id,$slug);
        /*echo '<pre>';
        print_r($videos);
        exit;*/

        /*$requests = $this->profile->getReuestsByEmail($email);
        $request = $this->profile->getDealsByEmail($id);

        $rejected_videos = $this->profile->getRejectedByEmail($email);
        $all_videos = $this->profile->getAllByEmail($email);

        $result = $this->profile->videosLicense($id);

        $nextPayment = $this->profile->getNextPayment($id);
        $next = $nextPayment->row();
        $this->data['next_payment'] = $next->next_payment;

        $paid = $this->profile->paid($id);
        $paid = $paid->row();
        $this->data['paid'] = $paid->paid;

        $nextPaymentMonthly = $this->profile->getNextPaymentMonthly($id);
        $next = $nextPaymentMonthly->row();
        $this->data['next_payment_monthly'] = $next->next_payment;

        $paidMonthly = $this->profile->paidMonthly($id);
        $paid = $paidMonthly->row();
        $this->data['paidMonthly'] = $paid->paid;

        $this->data['requests'] = $requests->num_rows();

        $this->data['videos_sold'] = $result->sold_videos;
        $this->data['deals'] = $request->num_rows();

        $this->data['rejected'] = $rejected_videos->num_rows();
        $this->data['all'] = $all_videos->num_rows();
        $this->data['all_videos_count'] = $this->profile->getRevenue($id)->num_rows();


        $this->data['uploaded_videos'] = $result->uploaded_videos;
       // $this->data['social_earning'] = $result->social_earning;
       // $this->data['buying_earning'] = $result->buying_earning;
		$ad_revenue = $this->profile->getAdRevenue($id);
		$license_revenue = $this->profile->getLicenseRevenue($id);
		if($ad_revenue->num_rows() > 0){
			$ad_revenue = $ad_revenue->row();
			$ad_revenue = $ad_revenue->earn;
			if(empty($ad_revenue)){
				$ad_revenue = 0;
			}
		}else{
			$ad_revenue = 0;
		}

		if($license_revenue->num_rows() > 0){
			$license_revenue = $license_revenue->row();
			$license_revenue = $license_revenue->earn;
			if(empty($license_revenue)){
				$license_revenue = 0;
			}
		}else{
			$license_revenue = 0;
		}

        $videos_title = $this->profile->getVideosTitle($id);

        $this->data['videos_title'] = $videos_title->result_array();
        $this->data['ad_revenue'] = $ad_revenue;
        $this->data['license_revenue'] = $license_revenue;*/
		/*echo '<pre>';
		print_r($this->data);
		exit;*/

        $this->data['js'][] = 'amcharts';
        $this->data['js'][] = 'pie';
        $this->data['js'][] = 'export.min';
        $this->data['js'][] = 'light';
        $this->data['js'][] = 'earnings';
        $this->data['title'] = 'Dashboard';
        $this->data['videos'] = $videos;
        $this->data['earnings'] = $earnings;
        $this->data['earningsVideos'] = $earningsVideos;
        $this->data['page'] = $page;
        $this->data['slug'] = $slug;
        /*echo '<pre>';
        print_r($videos->result());
        exit;*/
        $this->data['content'] = $this->load->view('Dashboard',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}

    public function earnings_breakdown()
    {
        auth();
        $id = $this->sess->userdata('clientId');

        $slug = $this->input->get('video');


        $ad_revenue = $this->profile->getUserEarnings($id,$slug);
        //$ad_revenue = $this->profile->getAdRevenue($id,$slug);
        //echo $this->db->last_query();exit;
        $this->data['ad_revenue'] = $ad_revenue->result_array();

        /*echo '<pre>';
        print_r($this->data['ad_revenue']);
        exit;*/
        $videos_title = $this->profile->getAdVideosTitle($id);
        $this->data['videos_title'] = $videos_title->result_array();

        $this->data['title'] = 'Account Summary';
        $this->data['profile_nav'] = 'earnings-breakdown';
        $this->data['profile_menu'] = array('earnings-breakdown'=>'Earning Breakdown');
        $this->data['js'][] = 'breakdown';
        $this->data['page'] = 'earnings-breakdown';
        $this->data['content'] = $this->load->view('account_summary',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}

    /*public function earnings_breakdown()
    {
        auth();
        $id = $this->sess->userdata('clientId');

        $slug = $this->input->get('video');


        $ad_revenue = $this->profile->getAdRevenue($id,$slug);
        //echo $this->db->last_query();exit;
        $this->data['ad_revenue'] = $ad_revenue->result_array();

        echo '<pre>';
        print_r($this->data['ad_revenue']);
        exit;
        $videos_title = $this->profile->getAdVideosTitle($id);
        $this->data['videos_title'] = $videos_title->result_array();

        $this->data['title'] = 'Account Summary';
        $this->data['profile_nav'] = 'Ad-Revenue';
        $this->data['profile_menu'] = array('Ad-Revenue'=>'ad-revenue','Licensing'=>'Licensing');
        $this->data['js'][] = 'earnings';
        $this->data['page'] = 'earnings-breakdown';
        $this->data['content'] = $this->load->view('account_summary',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }*/
      public function valid_url($url)
    {


// Validate url
          $validation = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
        if((bool)preg_match($validation, $url) === true) {
            return true;
        }
        else
            {
                $this->validation->set_message('valid_url','Please enter the valid URL' );
                return false;
            }
    }


    public function account_summary()
    {
        auth();
        $id = $this->sess->userdata('clientId');

        $slug = $this->input->get('video');

        $videos_title = $this->profile->getVideosTitle($id);
        //echo $this->db->last_query();exit;
        $this->data['videos_title'] = $videos_title->result_array();

        $revenue = $this->profile->getRevenueGroupNew($id,$slug);

        $nextPayment = $this->profile->getNextPayment($id);
        $this->data['next_payment'] = $nextPayment;

        // $next = $nextPayment->row();
        // $this->data['next_payment'] = $next->next_payment;

        $paid = $this->profile->paid($id);
        $this->data['paid'] = $paid;

        // $paid = $paid->row();
        // $this->data['paid'] = $paid->paid;
        $this->db->last_query();
        $this->data['js'][] = 'earnings';
        $this->data['total_revenue'] = $revenue->result_array();

        $this->data['title'] = 'Account Summary';
        $this->data['profile_nav'] = 'account-summary';
        $this->data['profile_menu'] = array('dashboard'=>'Dashboard');
        $this->data['page'] = 'dashboard';
        $this->data['content'] = $this->load->view('total_account_summary',$this->data,true);


        $this->load->view('common_files/template',$this->data);
    }

    public function payment_history()
    {
        auth();
        $id = $this->sess->userdata('clientId');

        $slug = $this->input->get('video');


        $ad_revenue = $this->profile->getUserPayments($id,$slug);
        //$ad_revenue = $this->profile->getAdRevenue($id,$slug);
        //echo $this->db->last_query();exit;
        $this->data['ad_revenue'] = $ad_revenue->result_array();

        /*echo '<pre>';
        print_r($this->data['ad_revenue']);
        exit;*/
        $videos_title = $this->profile->getAdVideosTitle($id);
        $this->data['videos_title'] = $videos_title->result_array();

        $nextPayment = $this->profile->getNextPayment($id);
        $this->data['next_payment'] = $nextPayment;

        
        $paid = $this->profile->paid($id);
        $this->data['paid'] = $paid;

        $this->data['title'] = 'Payment History';
        $this->data['profile_nav'] = 'payment-history';
        $this->data['profile_menu'] = array('payment-history'=>'Payment History');
        $this->data['js'][] = 'breakdown';
        $this->data['page'] = 'payment-history';
        $this->data['content'] = $this->load->view('payment_history',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }
    public function my_videos()
    {
        auth();
        $id = $this->sess->userdata('clientId');
        $myvideo = $this->profile->myvideo($id);
        $this->data['my_video'] = $myvideo->result_array();
        $this->data['title'] = 'My Videos';
        $this->data['profile_nav'] = 'my-videos';
        $this->data['profile_menu'] = array('my-videos'=>'My Videos');
        $this->data['js'][] = 'breakdown';
        $this->data['page'] = 'my-videos';
        $this->data['content'] = $this->load->view('my_videos',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function Licensing()
    {
        auth();
        $id = $this->sess->userdata('clientId');

        $slug = $this->input->get('video');

        $license_revenue = $this->profile->getLicenseRevenue($id,$slug);
        $this->data['license_revenue'] = $license_revenue->result_array();


        $videos_title = $this->profile->getLicenseVideoTitle($id);
        $this->data['videos_title'] = $videos_title->result_array();

        $this->data['title'] = 'Account Summary';
        $this->data['profile_nav'] = 'Licensing';
        $this->data['profile_menu'] = array('Ad-Revenue'=>'ad-revenue','Licensing'=>'Licensing');
        $this->data['js'][] = 'earnings';
        $this->data['page'] = 'earnings-breakdown';
        $this->data['content'] = $this->load->view('licensing_summary',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}

    public function monthly_account_summary()
    {
        auth();
        $id = $this->sess->userdata('clientId');
        $this->data['title'] = 'Monthly Account Summary';
        $this->data['client_id'] = $id;
        $this->data['profile_nav'] = 'Monthly Account Summary';
        $this->data['page'] = 'dashboard';
        $this->data['content'] = $this->load->view('monthly_account_summary',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}
    public function detailVideoRequests()
    {
        auth();
        $email = $this->sess->userdata('clientEmail');
        $result = $this->profile->getReuestsByEmail($email);
        $requests = $result->result_array();
        $this->data['title'] = 'Detail Video Requests';
        $this->data['videos'] = $requests;
        $this->data['profile_nav'] = 'Pending Approval Videos';
        $this->data['page'] = 'video-requests';
        $this->data['content'] = $this->load->view('video_detail_view',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}

    public function approved_videos()
    {
        auth();
        $email = $this->sess->userdata('clientId');
        $result = $this->profile->getDealsByEmail($email);
        $requests = $result->result_array();
        $this->data['title'] = 'Approved Videos';
        $this->data['videos'] = $requests;
        $this->data['profile_nav'] = 'Approved Videos';
        $this->data['page'] = 'approved-videos';
        $this->data['content'] = $this->load->view('approved_videos',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}

    public function acquired_videos()
    {
        auth();
        $id = $this->sess->userdata('clientId');
        $result = $this->profile->getAcquiredVideos($id);

        $result = $result->result_array();
        $this->data['title'] = 'Acquired Videos';
        $this->data['videos'] = $result;
        $this->data['profile_nav'] = 'Acquired Videos';
        $this->data['page'] = 'acquired-videos';
        $this->data['content'] = $this->load->view('acquired_videos',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}

    public function rejected_videos()
    {
        auth();
        $email = $this->sess->userdata('clientEmail');
        $result = $this->profile->getRejectedByEmail($email);
        $requests = $result->result_array();
        $this->data['title'] = 'Rejected Videos';
        $this->data['videos'] = $requests;
        $this->data['profile_nav'] = 'Rejected Videos';
        $this->data['page'] = 'dashboard';
        $this->data['content'] = $this->load->view('video_detail_view',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}

    public function all_videos()
    {
        auth();
        $email = $this->sess->userdata('clientEmail');
        $result = $this->profile->getAllByEmail($email);
        $requests = $result->result_array();
        $this->data['title'] = 'All Videos';
        $this->data['videos'] = $requests;
        $this->data['profile_nav'] = 'All Videos';
        $this->data['page'] = 'account-summary';
        $this->data['content'] = $this->load->view('video_detail_view',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}
    public function submit_video()
    {
        /*auth();

        $slug = $this->uri->segment(2);*/
        /*if(empty($slug)){
            $client_id = $this->sess->userdata('clientId');
            $result = $this->profile->getLeadById($client_id);
            $requests = $result->row_array();
        }
        else{*/
           /* $result = $this->profile->getLeadBySlug($slug);
            $requests = $result->row_array();*/

       /* }*/
        /*$results = $this->profile->checkView($requests['client_id'], $requests['id']);
        $results = $results->row_array();

        $users = $this->profile->checkUsers($requests['client_id']);
        action_add($requests['lead_id'],0,0,0,0,'user submitted video');

        $this->data['slug'] = $slug;
        $this->data['title'] = 'Submit Video';
        $this->data['videos'] = $requests;
        $this->data['video'] = $results;
        $this->data['load_view'] = $requests['load_view'];
        $this->data['js'][] = 'multiform';
        $this->data['users'] = $users;
        $this->data['js'][] = 'submitvideo';
        $this->data['upload_js'][] = 'jquery.fileuploader.min';
        $this->data['upload_js'][] = 'custom';
        $this->data['countries'] = $this->location->getCountries();*/

        $this->data['content'] = $this->load->view('submit_video1',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }
    public function submit_video_success()
    {
        /*auth();

        $slug = $this->uri->segment(2);*/
        /*if(empty($slug)){
            $client_id = $this->sess->userdata('clientId');
            $result = $this->profile->getLeadById($client_id);
            $requests = $result->row_array();
        }
        else{*/
           /* $result = $this->profile->getLeadBySlug($slug);
            $requests = $result->row_array();*/

       /* }*/
        /*$results = $this->profile->checkView($requests['client_id'], $requests['id']);
        $results = $results->row_array();

        $users = $this->profile->checkUsers($requests['client_id']);
        action_add($requests['lead_id'],0,0,0,0,'user submitted video');

        $this->data['slug'] = $slug;
        $this->data['title'] = 'Submit Video';
        $this->data['videos'] = $requests;
        $this->data['video'] = $results;
        $this->data['load_view'] = $requests['load_view'];
        $this->data['js'][] = 'multiform';
        $this->data['users'] = $users;
        $this->data['js'][] = 'submitvideo';
        $this->data['upload_js'][] = 'jquery.fileuploader.min';
        $this->data['upload_js'][] = 'custom';
        $this->data['countries'] = $this->location->getCountries();*/

        $this->data['content'] = $this->load->view('submit_video_success',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }
    public function submit_video_description()
    {
        $slug = $this->uri->segment(2);
        $result = $this->profile->getLeadBySlug($slug);
        $requests = $result->row_array();
        $this->data['video_url'] = $requests['video_url'];
        $this->data['content'] = $this->load->view('submit_video2',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function video_submission()
    {
        auth();
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Data Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';


       // $id = $this->sess->userdata('clientId');

        $data = $this->input->post();


        /*echo '<pre>';
        print_r($data);
        exit;*/



        $view = $data['view'];
        $lead_id = $data['lead_id'];
        $leadData = $this->profile->getLeadById1($lead_id);

        if($view == 'submit_video3'){
            $this->validation->set_rules('question1','This Field','required');
            //$this->validation->set_rules('question2','This Field','required');
            $this->validation->set_rules('question3','This Field','required');
            $this->validation->set_rules('question4','This Field','required');
            $this->validation->set_message('required','This field is required.');
            if($this->validation->run() == false){
                $fields = array('question1','question2','question3','question4');
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
            $view_no = 3;
            $lead = $this->profile->getVideoByLeadId($data['lead_id']);
            $data['title'] = '';
            if($lead){
                $data['id'] = $lead->id;
            }
            if($leadData){
                $data['title'] = $leadData->video_title;
            }

            /*echo '<pre>';
            print_r($lead);
            exit;*/
            $result = $this->profile->insert_video1($data);
            action_add($data['lead_id'],$data['id'],0,0,0,'Lead answer submitted');
            $view_update = $this->profile->update_view($view_no,$lead_id);
        }
        else if($view == 'submit_video4'){
            $this->validation->set_rules('country_code','Country Code','required');
            $this->validation->set_rules('mobile','Mobile Number','required');
            if(!isset($data['same']) && empty($data['same'])) {
                $this->validation->set_rules('email', 'This Field', 'required');
            }
            //$this->validation->set_rules('gender','Gender','required');
            $this->validation->set_rules('address','Address','required');
            $this->validation->set_rules('city_id','City','required');
            $this->validation->set_rules('state_id','State','required');
            $this->validation->set_rules('zip_code','Postal Code','required');
            $this->validation->set_rules('country_id','Country','required');
            $this->validation->set_message('required','This field is required.');
            if($this->validation->run() == false){
                $fields = array('country_code','mobile','email','address','city_id','state_id','zip_code','country_id');
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
            if(!isset($data['email']) && empty($data['email'])){
                $email = $this->sess->userdata('clientEmail');
                $id = $this->sess->userdata('clientId');
                $data['email'] = $email;
                $data['id'] = $id;
            }
            else{
                $id =  $this->sess->userdata('clientId');
                $data['id'] = $id;
            }

            $view_no = 4;
            $result = $this->profile->insert_info($data);
            $update = $this->profile->update_pending($lead_id);
            $view_update = $this->profile->update_view($view_no,$lead_id);
            action_add($lead_id,0,$id,0,0,'Payment info updated successfully');
            $response['url'] = $this->data['url'] . 'submit-video';

        }
        else{

            $view_no = 2;

            $this->validation->set_rules('url[]','Upload Video','required');
            $this->validation->set_message('required','Minimum one video file is requred.');

            if($this->validation->run() == FALSE){
                $fields = array('url[]');
                $errors = array();
                foreach($fields as $field){
                    $errors['url'] = form_error($field);
                }
                $response['code'] = 201;
                $response['message'] = 'Validation Errors!';
                $response['error'] = $errors;
                $response['url'] = '';
                echo json_encode($response);
                exit;

            }
            $video_data=$this->profile->getVideoByLeadId($lead_id);
            if($video_data){
                $data['slug'] = slug($leadData->video_title,'videos', 'slug');
                $id=$video_data->id;
                $ldata = array(
                    'title' => $leadData->video_title,
                    'slug' => $data['slug'],
                    'lead_id' => $lead_id,
                );
                $this->db->where('id', $lead_id);
                $this->db->update('videos',$ldata);
                action_add($lead_id,$id,0 ,0,0,'Orginal video files uploaded and Update in Video Table');
            }else{
                $data['slug'] = slug($leadData->video_title,'videos', 'slug');
                $id = $this->profile->insert_video($data);
                action_add($lead_id,$id,0 ,0,0,'Orginal video files uploaded and New Entry in Video Table');
            }


            $raw_data = array();
            foreach($data['url'] as $url){

                /*$source_file = $_SERVER['DOCUMENT_ROOT'].'/'.$url;
                $file_extension = explode('.',$source_file);
                $file_extension = $file_extension[1];

                $target_file_key = S3_BASE_FOLDER . "/" . $url;
                $target_file_key = explode('/',$target_file_key);
                $target_file_key = 'videos/raw_videos/'.$leadData->unique_key.'/'.$target_file_key[count($target_file_key)-1];
                $url = $this->upload_file_s3($source_file, $target_file_key, $file_extension, TRUE);*/

                $raw_data['url'] = $url;
                $raw_data['video_id'] = $id;
                $raw_data['lead_id'] = $data['lead_id'];


                $result = $this->profile->insert_raw_video($raw_data);
            }


            $view_update = $this->profile->update_view($view_no,$lead_id);
        }

        $response['url'] = $this->data['url'] . 'submit-video';
        echo json_encode($response);
        exit;
    }
    public function  appearance_release($uid)
    {

        $this->data['title'] = 'Submit Video';
        $this->data['tid'] = time();
        $this->data['upload_js'][] = 'jquery.fileuploader.min';
        //$this->data['upload_js'][] = 'customs9';
        $this->data['uid'] = $uid;
        $LeadRelaseLink = $this->profile->getLeadRelaseLink($uid);
        $releaselinks = array();
        foreach ($LeadRelaseLink as $link){
            $releaselinks[$link->link_type] = $link;
        }
        $leadid = $this->db->query('SELECT video_url FROM `video_leads` WHERE `unique_key` ="'.$uid.'"');
        $leadid = $leadid->row();
        $video_url = $leadid->video_url;
        $this->data['video_url'] = $video_url;
        $expire_date = date("Y-m-d", strtotime($link->created_at." +$link->days_interval days "));
        $current_date = date("Y-m-d");
        //echo "$expire_date>>>$current_date";exit;
      if($expire_date < $current_date)
        {
            $this->sess->set_flashdata('err','Linked expired!');
            redirect('home');
        }
        $this->data['countries'] = $this->location->getCountries();
        $this->data['content'] = $this->load->view('appearance_release', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }

    public function appearance_release_ajax(){
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Data Added Successfully!';
        $response['error'] = '';
        $response['url'] = $this->data['url'].'submit-video';
        $data = $this->security->xss_clean($this->input->post());
        $this->validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->validation->set_rules('phone', 'Phone', 'trim|required');
        $this->validation->set_rules('city', 'City', 'trim|required');
        $this->validation->set_rules('state', 'State', 'trim|required');
        $this->validation->set_rules('country_code', 'Country code', 'trim|required');
        $this->validation->set_rules('country', 'Country', 'trim|required');
        $this->validation->set_rules('address', 'Address', 'trim|required');
        $this->validation->set_rules('zip', 'Zip Code', 'trim|required');
        $this->validation->set_rules('Help','Help','trim|required');
        $this->validation->set_rules('terms_check', 'terms check', 'trim|required');
        $this->validation->set_rules('img','signature','trim|required');

        if ($this->validation->run() == false) {

            $fields = array('first_name','last_name','Help', 'img','email', 'phone','zip', 'city', 'state', 'country', 'country_code', 'address','terms_check');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Your has been not submitted yet, Please recheck your form carefully.';
            $response['error'] = $errors;
            header("Content-type: application/json");
            $response['url'] = '';
            echo json_encode($response);
            exit;
        } else {
            $uid = $data['uid'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $query = "SELECT * FROM `appreance_release` WHERE uid = '$uid' AND first_name = '$first_name' AND last_name = '$last_name'";
            $result = $this->db->query($query);
            if ($result->num_rows() > 0)
            {
                $errors = array();
                $errors['duplicate_name'] = 'You have already submit the second signer form.';
                $response['code'] = 201;
                $response['message'] = 'Your has been not submitted yet, Please recheck your form carefully.';
                $response['error'] = $errors;
                header("Content-type: application/json");
                $response['url'] = '';
                echo json_encode($response);
                exit;
            }
            $phone = $data['phone'];
            $country_code = $data['country_code'];
            $country = $country_code . '-' . $data['country'];
            $state = $data['state'];
            $city = $data['city'];
            $address = $data['address'];
            $address2 = $data['address2'];
            $zip = $data['zip'];
            $help_us = $data['Help'];
            $dateadded = date("Y-m-d H:i:s");
            $sigimag = substr($data['img'], 5);
            $signature = $sigimag;
            $email = $data['email'];
            $time = time();
            $pdf_link = '/uploads/'.$uid.'/appreance/'.$uid.'_signed_'.$time.'.pdf';
            $terms_check = 0;
            if(isset($data['terms_check'])){
                $terms_check = 1;
            }
            //Insert  data
            $insert_appreance_lead = array(
                'uid' => $uid,
                'first_name' => $data['first_name'],
                'last_name' =>  $data['last_name'],
                'email' => $email,
                'countary_code' => $country,
                'phone' => $phone,
                'country_id'=>$data['country'],
                'state_id'=>$state,
                'city_id'=>$city,
                'address' => $address,
                'address2' => $address2,
                'zip_code' => $zip,
                'terms_check'=>$terms_check,
                'help_us'  => $help_us,
                'pdf_link'=>$pdf_link,
                'created_at' => $dateadded,
                'img'=> $signature
            );
            $this->db->insert('appreance_release',$insert_appreance_lead);

        }
        $first_name = $first_name ;
        $last_name = $last_name;
        $full_adress = $data['address'].' '.$data['address2'];
        $country_name_va = $data['country'];
        $unique_key =$uid;
        $date=$dateadded;


        $leadid = $this->db->query('SELECT id FROM `video_leads` WHERE `unique_key` ="'.$uid.'"');
        $leadid = $leadid->row();
        $lead_id = $leadid->id;
        $leadquery = $this->db->query('SELECT *
		    FROM video_leads vl
		    WHERE vl.id ="'.$lead_id.'"');
        $leadData = $leadquery->row();
        $data_log_query=$this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="'.$lead_id.'"');
        $logs_query=$data_log_query->result();
        $sent_log_query=$this->db->query('SELECT lead_rated_date  FROM `lead_action_dates` WHERE `lead_id` = "'.$lead_id.'"');
        $sent_log_result=$sent_log_query->result();
        $video_query=$this->db->query('SELECT *  FROM `videos` WHERE `lead_id` = "'.$lead_id.'"');
        $video_result=$video_query->result();
        $data_log['user_browser']='';
        $data_log['user_ip_address']='';
        $data_log['contract_signed_datetime']='';
        $data_log['contract_view_datetime']='';
        if(isset($logs_query[0])){
            $data_log['user_browser']=$logs_query[0]->user_browser;
            $data_log['user_ip_address']=$logs_query[0]->user_ip_address;
            $data_log['contract_signed_datetime']=$logs_query[0]->contract_signed_datetime;
            $data_log['contract_view_datetime']=$logs_query[0]->contract_view_datetime;
        }
        if(isset($sent_log_result[0])){
            $data_log['lead_rated_date']=$sent_log_result[0]->lead_rated_date;
        }

        $data1['video_url']=$leadData->video_url;
        $url_name = $data1['video_url'];

        $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';


// Initialize CURL:
        $ch = curl_init('http://api.ipstack.com/'.$data_log['user_ip_address'].'?access_key='.$access_key.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
        $json = curl_exec($ch);
        curl_close($ch);
        $api_result = json_decode($json, true);

        $appreance_pdf= $this->appreance_signed_pdf($url_name,$first_name,$last_name,$email,$phone,$data['country'],$state,$city,$full_adress,$zip,$signature,$help_us,$date,$unique_key,$data_log,$api_result,$time);
        if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] !='wooglobe.com') {
            $source_file = $_SERVER['DOCUMENT_ROOT']. $this->config->item('local_dir',true) . '/' . $pdf_link;
        }else {
            $source_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $pdf_link;
        }
        $file_extension = explode('.', $source_file);
        $file_extension = $file_extension[1];
        $target_file_key = $pdf_link;
        $url = $this->upload_file_s3_new('private',$source_file, $target_file_key, $file_extension, FALSE);
        $subject = "New Appearance Release Form $uid Submitted";
        $message = "Dear, New appearance release form submitted please check the details of deal $uid.";
        $result = $this->email(' viral@wooglobe.com', 'Admin', 'noreply@wooglobe.com', 'WooGlobe', $subject, $message);
        header("Content-type: application/json");
            echo json_encode($response);
            exit;

    }
    public function check_name_appearance(){
        $data = $this->security->xss_clean($this->input->post());
        $uid = $data['uid'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $query = "SELECT * FROM `appreance_release` WHERE uid = '$uid' AND first_name = '$first_name' AND last_name = '$last_name'";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0)
        {
            $this->form_validation->set_message('check_name_appearance', 'You have already submit the appearance release form.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    public function  second_signer($uid){

        $this->data['title'] = 'Submit Video';
        $this->data['tid'] = time();
        $this->data['upload_js'][] = 'jquery.fileuploader.min';
       // $this->data['upload_js'][] = 'customs9';
        $this->data['uid'] = $uid;
        $LeadRelaseLink = $this->profile->getLeadRelaseLink($uid);
        $releaselinks = array();
        foreach ($LeadRelaseLink as $link){
            $releaselinks[$link->link_type] = $link;
        }
        $link = $releaselinks[$link->link_type];

        $leadid = $this->db->query('SELECT video_url FROM `video_leads` WHERE `unique_key` ="'.$uid.'"');
        $leadid = $leadid->row();
        $video_url = $leadid->video_url;
        $this->data['video_url'] = $video_url;
          $expire_date = date("Y.m.d", strtotime($link->created_at." +$link->days_interval days "));
          $current_date = date("Y.m.d ");
          //echo "$expire_date >>>> $current_date";exit;
          if($expire_date<$current_date)
            {

                $this->sess->set_flashdata('err','Linked expired!');
                 redirect('home');
            }
        $this->data['countries'] = $this->location->getCountries();
        $this->data['content'] = $this->load->view('second_signer', $this->data, true);
        $this->load->view('common_files/template', $this->data);


    }

    public function second_signer_ajax(){
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Data Added Successfully!';
        $response['error'] = '';
        $response['url'] = $this->data['url'].'submit-video';
        $data = $this->security->xss_clean($this->input->post());

        $this->validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->validation->set_rules('phone', 'Phone', 'trim|required');
        $this->validation->set_rules('city', 'City', 'trim|required');
        $this->validation->set_rules('state', 'State', 'trim|required');
        $this->validation->set_rules('country_code', 'Country code', 'trim|required');
        $this->validation->set_rules('country', 'Country', 'trim|required');
        $this->validation->set_rules('address', 'Address', 'trim|required');
        $this->validation->set_rules('zip', 'Zip Code', 'trim|required');
        $this->validation->set_rules('img','signature','trim|required');
        $this->validation->set_rules('shotVideo','Shot Video','trim|required');
        $this->validation->set_rules('ageVideo','Age Video','trim|required');
        $this->validation->set_rules('termsShared','Terms Shared','trim|required');
        $this->validation->set_rules('terms_check', 'terms check', 'trim|required');
        if ($this->validation->run() === false) {

            $fields = array('first_name','last_name','img','email', 'phone','zip', 'city', 'state', 'country', 'country_code', 'address','terms_check', 'termsShared', 'ageVideo','shotVideo'  );
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Your has been not submitted yet, Please recheck your form carefully.';
            $response['error'] = $errors;
            header("Content-type: application/json");
            $response['url'] = '';
            echo json_encode($response);
            exit;
        } else {
            $uid = $data['uid'];
            $first_name = $data['first_name'];
            $last_name = $data['last_name'];
            $query = "SELECT * FROM `appreance_release` WHERE uid = '$uid' AND first_name = '$first_name' AND last_name = '$last_name'";
            $result = $this->db->query($query);
            if ($result->num_rows() > 0)
            {
                $errors = array();
                $errors['duplicate_name'] = 'You have already submit the second signer form.';
                $response['code'] = 201;
                $response['message'] = 'Your has been not submitted yet, Please recheck your form carefully.';
                $response['error'] = $errors;
                header("Content-type: application/json");
                $response['url'] = '';
                echo json_encode($response);
                exit;
            }
            $phone = $data['phone'];
            $country_code = $data['country_code'];
            $country = $country_code . '-' . $data['country'];
            $state = $data['state'];
            $city = $data['city'];
            $address = $data['address'];
            $address2 = $data['address2'];
            $zip = $data['zip'];
            $dateadded = date("Y-m-d H:i:s");
            $sigimag = substr($data['img'], 5);
            $signature = $sigimag;
            $email = $data['email'];
            $time = time();
            $pdf_link = '/uploads/'.$uid.'/second_signer/'.$uid.'_signed_'.$time.'.pdf';
            $pdf_link_appreance = '/uploads/'.$uid.'/appreance/'.$uid.'_signed_'.$time.'.pdf';
            $terms_check = 0;
            if(isset($data['terms_check'])){
                $terms_check = 1;
            }
            $shotVideo = 'No';
            if(isset($data['shotVideo'])){
                $shotVideo = 'Yes';
            }
            $ageVideo = 'No';
            if(isset($data['ageVideo'])){
                $ageVideo = 'Yes';
            }
            $termsShared = 0;
            if(isset($data['termsShared'])){
                $termsShared = 1;
            }
            $newsletter = 0;
            if(isset($data['newsletter'])){
                $newsletter = 1;
            }
            $eu = 0;
            if(isset($data['eu'])){
                $eu = 1;
            }

            //Insert  data
            $insert_appreance_lead = array(
                'uid' => $uid,
                'first_name' => $data['first_name'],
                'last_name' =>  $data['last_name'],
                'email' => $email,
                'countary_code' => $country,
                'phone' => $phone,
                'country_id'=>$data['country'],
                'state_id'=>$state,
                'city_id'=>$city,
                'address' => $address,
                'address2' => $address2,
                'zip_code' => $zip,
                'img'=> $signature,
                'pdf_link'=>$pdf_link,
                'terms_check'=>$terms_check,
                'shotVideo'=>$shotVideo,
                'ageVideo'=>$ageVideo,
                'termsShared'=>$termsShared,
                'created_at' => $dateadded

            );
            $this->db->insert('second_signer',$insert_appreance_lead);
          /*  $insert_second_signer = array(
                'uid' => $uid,
                'first_name' => $data['first_name'],
                'last_name' =>  $data['last_name'],
                'email' => $email,
                'countary_code' => $country,
                'phone' => $phone,
                'country_id'=>$data['country'],
                'state_id'=>$state,
                'city_id'=>$city,
                'address' => $address,
                'address2' => $address2,
                'zip_code' => $zip,
                'help_us'  => $help_us,
                'date_added' =>  $dateadded,
                'pdf_link'=>$pdf_link_appreance,
                'created_at' => date("Y-m-d H:i:s"),
                'img'=> $signature
            );
            $this->db->insert('appreance_release',$insert_second_signer);*/
        }

        $full_name = $data['first_name'].' '.$data['last_name'];

        $full_adress = $data['address'].' '.$data['address2'];
        $country_name_va = $data['country'];
        $unique_key =$uid;
        $date=$dateadded;


        $leadid = $this->db->query('SELECT id FROM `video_leads` WHERE `unique_key` ="'.$uid.'"');
        $leadid = $leadid->row();
        $lead_id = $leadid->id;
        $leadquery = $this->db->query('SELECT *
		    FROM video_leads vl
		    WHERE vl.id ="'.$lead_id.'"');
        $leadData = $leadquery->row();
        $data_log_query=$this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="'.$lead_id.'"');
        $logs_query=$data_log_query->result();
        $sent_log_query=$this->db->query('SELECT lead_rated_date  FROM `lead_action_dates` WHERE `lead_id` = "'.$lead_id.'"');
        $sent_log_result=$sent_log_query->result();
        $video_query=$this->db->query('SELECT *  FROM `videos` WHERE `lead_id` = "'.$lead_id.'"');
        $video_result=$video_query->result();
        $data_log['user_browser']='';
        $data_log['user_ip_address']='';
        $data_log['contract_signed_datetime']='';
        $data_log['contract_view_datetime']='';
        if(isset($logs_query[0])){
            $data_log['user_browser']=$logs_query[0]->user_browser;
            $data_log['user_ip_address']=$logs_query[0]->user_ip_address;
            $data_log['contract_signed_datetime']=$logs_query[0]->contract_signed_datetime;
            $data_log['contract_view_datetime']=$logs_query[0]->contract_view_datetime;
        }
        if(isset($sent_log_result[0])){
            $data_log['lead_rated_date']=$sent_log_result[0]->lead_rated_date;
        }
        $data1['video_title']=$leadData->video_title;
        $data1['video_url']=$leadData->video_url;
        if(isset($video_result[0])){
            $data1['question1']=$video_result[0]->question_video_taken;
            $data1['question3']=$video_result[0]->question_when_video_taken;
        }

        $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';


// Initialize CURL:
        $ch = curl_init('http://api.ipstack.com/'.$data_log['user_ip_address'].'?access_key='.$access_key.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
        $json = curl_exec($ch);
        curl_close($ch);
        $api_result = json_decode($json, true);

        $result_pdf=$this->second_signer_signed_pdf($full_name,$email,$phone,$signature,$country_name_va,$state,$city,$full_adress,$zip,$date,$leadData->revenue_share,$unique_key,$data1,$data_log,$api_result,$time);
        if($_SERVER['HTTP_HOST'] == 'localhost') {
            $source_file = rtrim($_SERVER['DOCUMENT_ROOT'], '/').'uat/' . $pdf_link;

        }else {
            $source_file = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $pdf_link;
        }
        $file_extension = explode('.', $source_file);
        $file_extension = $file_extension[1];
        $target_file_key = $pdf_link;
        $url = $this->upload_file_s3_new('private',$source_file, $target_file_key, $file_extension, FALSE);
       // $appreance_pdf= $this->appreance_signed_pdf($url_name,$first_name,$last_name,$email,$phone,$data['country'],$state,$city,$full_adress,$zip,$signature,$help_us,$date,$unique_key,$data_log,$api_result,$time);
        $subject = "New Second Signer Form $uid Submitted";
        $message = "Dear, New second signer form submitted please check the details of deal $uid.";
        $result = $this->email(' viral@wooglobe.com', 'Admin', 'noreply@wooglobe.com', 'WooGlobe', $subject, $message);
        header("Content-type: application/json");
        echo json_encode($response);
        exit;

    }
    public function check_name(){
        $data = $this->security->xss_clean($this->input->post());

        $uid = $data['uid'];
        $first_name = $data['first_name'];
        $last_name = $data['last_name'];
        $query = "SELECT * FROM `second_signer` WHERE uid = '$uid' AND first_name = '$first_name' AND last_name = '$last_name'";
        //echo $query;exit;
        $result = $this->db->query($query);

        if ($result->num_rows() > 0)
        {
            $this->form_validation->set_message('check_name', 'You have already submit the second signer form.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    public function second_signer_signed_pdf($full_name,$email,$phone,$signature,$country,$state,$city,$address,$zip,$date,$revenue_share,$unique_key,$data1,$data_log,$api_result,$time){
         /*echo'<pre>';
         print_r($data1);
         exit();*/
        $this->base30_to_jpeg ( $signature, root_path(). '/uploads/'.$unique_key.'/second_signer/'.$unique_key.'_'.$time.'.png' );

        $signimgpath= root_path().'/uploads/'.$unique_key.'/second_signer/'.$unique_key.'_'.$time.'.png';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //$certificate = 'file://'.root_path().'/admin/app/libraries/TCPDF-master/examples/data/cert/tcpdf.crt';
        $certificate = 'file://'.root_path().'/admin/app/libraries/TCPDF-master/examples/data/cert/signature.crt';

// set additional information
        $info = array(
            'Name' => 'WooGlobe',
            'Location' => 'viral@wooglobe.com',
            'Reason' => 'WooGlobe Contract Signing',
            'ContactInfo' => 'https://wooglobe.com/',
        );

// set document signature
        $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('WooGlobe');
        $pdf->SetTitle('WooGlobe');
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
// add a page
        $pdf->AddPage('L','','A4');

        $html = '<img src="'.root_path().'/assets/img/wooglobe-con-1.png" width="1000px" height="600px">';
        $pdf->writeHTML($html);
        /*$fontname = TCPDF_FONTS::addTTFfont('calibri.ttf', 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 12);*/
// add a page
        $pdf->AddPage('P','','A4');
        $html = '
<style>
        h1.heading {
            text-align: center;
        }

    </style>
        <h1 class="heading"><FONT FACE="Helvetica Neue, serif">WooGlobe Content Agreement</FONT></h1>
        <h1 class="heading"><FONT FACE="Helvetica Neue, serif">Summary</FONT></h1>

<p STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">Thank
you for agreeing to use WooGlobe to distribute your video worldwide
across the web, TV and other platforms. Your agreement with WooGlobe
explains what permissions you give to us, what our role is and how we
work to earn you money. The full terms are set out in the WooGlobe
Content Agreement, which you should read carefully. A summary of the
key points in the Content Agreement is:</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Uses:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">This
agreement gives WooGlobe and its partners the rights they need to
distribute and use your video(s) worldwide. Your video(s) may appear
on websites, on TV shows and in films, in advertising, in public
places and in any other type of media. We will exclusively manage
your video(s) on YouTube and similar platforms (no
restrictions).</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Exclusivity:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">The
rights you grant to us are worldwide and exclusive.&nbsp;</SPAN></FONT></p>
<p STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><U><B>Term:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">The
term of this agreement is perpetual. We need this in order to best
protect and monetise your video(s). </SPAN></FONT><FONT COLOR="#000000">You
may seek to terminate this Agreement under the conditions as set
forth in Section 4 of the agreement. <BR><BR></FONT><FONT COLOR="#000000"><U><B>Earnings:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">We
will pay you agreed percentage, as set forth in agreement, of any
money earned from your video.</SPAN></FONT><FONT COLOR="#000000"> </FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">We
will do our best to earn you as much revenue as possible but we
cannot make any promises as to how much will be
earned.</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Ownership:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">You
must have taken any videos you submit to us. You will retain your
copyright ownership of them. </SPAN></FONT>
</p>
<p STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><U><B>Privacy:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">If
your videos feature any identifiable people, you must make sure you
have their permission to submit your videos to us and for us to use
them. The information you provide to us may be shared with our
clients and we, or our clients may contact you to further verify this
information.</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT><FONT COLOR="#000000"><U><B>Your
obligations:</B></U></FONT><FONT COLOR="#000000"><BR></FONT><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">You
must ensure that your videos are lawful, that you own them and that
you are entitled to allow us to distribute them. Please make sure
that you comply with the WooGlobe Content Agreement at all times.&nbsp;</SPAN></FONT></p>';
// output the HTML content
        $pdf->writeHTML($html);

        $pdf->AddPage('P','','A4');

        $html = '

<style>
       h1.heading {
            text-align: center;
        }

        .top-form,
        .bottom-form {
            background-color: #ececec;
           
            
        }
        
        td {
            text-align: center;
        }

    </style>
        <h1 class="heading">VIDEO OWNER AGREEMENT</h1>
       
                <div class="top-form"  style="border: 3px solid #30538f;border-radius: 10px" >
                    <h3>&nbsp;Video Owner / Licensor</h3>
                        
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Full Name <font color="red">*</font> :'.$full_name.'</p>
                        <p >&nbsp;&nbsp;&nbsp;&nbsp;Email <font color="red">*</font> : '.$email.' &nbsp;&nbsp; Phone <font color="red">*</font> : '.$phone.'</p>
                        <p >&nbsp;&nbsp;&nbsp;&nbsp;Address <font color="red">*</font> : '.$address.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;City <font color="red">*</font> :'.$city.'&nbsp;&nbsp;  State <font color="red">*</font> : '.$state.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Zip Code <font color="red">*</font> : '.$zip.'  &nbsp;&nbsp;Country <font color="red">*</font> : '.$country.'</p>
                        
                </div>
                <div class="bottom-form" style="border: 3px solid #30538f">
                    <h3>&nbsp;Image(s): (i.e. your video(s))</h3>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;Title: '. $data1["video_title"].'</p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;URL: '.$data1["video_url"].'</p>
                        <small>&nbsp;&nbsp;&nbsp;&nbsp;Includes all additional footage (e.g. "B-roll", raw footage, etc) submitted by Licensor
                            to WooGlobe in connection with the Images. Does not include Licensor\'s channel or other
                            works unless expressly stated.</small>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;Share: '.$revenue_share.'%</p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;Where was this video filmed?<font color="red">*</font>: '.$data1["question1"].'</p>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;When was this video filmed?<font color="red">*</font>: '.$data1["question3"].'</p>
                </div>
                    <h3>Declaration</h3>
                    <ul>
                        <li>I am 18 years of age or older and I either shot this video all by myself or own full
                            rights to the video.</li>
                        <li>By signing the agreement, I acknowledge that I have read and understood the detailed
                            WooGlobe Ltd. content agreement below, and that I accept and agree to adhere to all of
                            its terms, which includes the exclusive grant of rights of video to WooGlobe Ltd.</li>
                    </ul>
                   
                    <div >
                    <table>
                        <tr>
                            <td>'.$full_name.'</td>
                            <td></td>
                            <td>'.$date.'</td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #7f7f7f">Name</td>
                            <td style="border-top: 1px solid #7f7f7f">Signature</td>
                            <td style="border-top: 1px solid #7f7f7f">Date</td>
                        </tr>
                    </table>  
                </div>


';

        $pdf->writeHTML($html);

        $pdf->Image($signimgpath, 100, 249, 18, 14, 'PNG');

// define active area for signature appearance
        $pdf->setSignatureAppearance(100, 249, 18, 14);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// *** set an empty signature appearance ***
        $pdf->addEmptySignatureAppearance(100, 253, 18, 14);



        $pdf->AddPage('P','','A4');
        $html = '

<style>
        h1.heading {
            text-align: center;
        }
 td {
            text-align: center;
        }
    </style>
      
<p style="text-align: right;"><FONT COLOR="#7f7f7f"><FONT FACE="Helvetica Neue, serif"><FONT SIZE=2 STYLE="font-size: 10pt">WooGlobe Ltd.<br />
16 Weir Road, <br />
London, DA51BJ<br />
UK</FONT></FONT></FONT></p>

<h1 class="heading"><FONT FACE="Helvetica Neue, serif">Agreement Terms and Conditions</FONT></h1>

<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>1.
Licensed Rights</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">.
Licensor grants WooGlobe the exclusive, unlimited right to use,
refrain from using, change, alter, edit, modify, add to, subtract
from and rearrange the Images and to exhibit, distribute, broadcast,
reproduce, license others to reproduce and distribute, advertise,
promote, publish and otherwise exploit the Images by any and all
methods or means, whether now known or hereafter devised, in any
manner and in any and all media throughout the world, in perpetuity,
for any purpose whatsoever as WooGlobe in its sole discretion may
determine (the &quot;Licensed Rights&quot;), including for the
purpose of marketing, advertising, and promotion. Licensor
furthermore does hereby irrevocably appoint WooGlobe as its
attorney-in-fact to take any such action as may from time to time be
necessary to effect, transfer, or assign the rights granted to
WooGlobe herein, including without limitation copyright-related
actions, and assigns to WooGlobe the right to prosecute any and all
claims from the past, present, and future use of the Images by
unauthorized third parties. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>2.
Payments to Licensor.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
In full consideration of all of the Licensed Rights granted
hereunder, WooGlobe will pay Licensor the Share of the net revenue
earned and received by WooGlobe from the exhibition, distribution,
broadcast, licensing and other exploitation of the Licensed Rights,
less proceeds received from uses intended to generate marketable
interest in the Images. Licensor shall be responsible for any taxes
relating to payments it receives to the appropriate tax authority and
governmental entities. Licensor must deliver to WooGlobe agreement to
these terms, any additional information requested by WooGlobe
relating to the Images, and the above-described images in a format
acceptable to WooGlobe in order to receive payment. Licensor
must provide the best quality video file available to WooGlobe and
add a line to any site where Licensor has previously posted the work,
stating: For licensing or usage, contact:licensing@wooglobe.com.</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">WooGlobe
shall process the payment to Licensor within fifteen (15) days after
the end of every quarter (i.e 15th April, 15th July, 15th Oct, 15th
Jan); however, if the amount owed to Licensor is less than </FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">seventy
five</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
US dollars ($75 USD), WooGlobe reserves the right to carry the
royalty over for payment to Licensor until the amount exceeds </FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">seventy
five</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
US dollars ($75 USD).</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
If the amount never exceeds seventy-five US dollars ($75 USD) or if
WooGlobe ceases license acquisition operations, then no Payment will
come due. </FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">WooGlobe
shall not be responsible for any Payments to Licensor for revenue
earned in connection with the images but not received by WooGlobe for
any reason (for example, due to non-payment, or where WooGlobe does
not receive adequate reporting so as to enable WooGlobe to assign
revenue). Licensor agrees that if the outstanding Payment does not
exceed seventy-five US Dollars ($75 USD) for a period of twenty-four
(24) months, account maintenance costs will exceed expected future
revenue. In this event, any outstanding Payment will be charged as a
maintenance fee, and no future Payments are due. Licensor may choose
to be paid via PayPal, or electronic bank transfer (the “Payment
Method”). Any electronic bank transfer fees will be deducted from
the Licensor’s Payment prior to sending. Licensor agrees to provide
WooGlobe all the necessary and accurate information required to
process the Payment (the “Payment Details’) via their preferred
Payment Method. If Licensor fails to provide Payment Details to
WooGlobe within sixty (60) days of the execution of this Agreement or
the expiration of provided Payment Details, Licensor will forfeit the
outstanding Payment balance to WooGlobe. If after sixty (60) days
Licensor updates Payment Details, WooGlobe will make Payments to the
Licensor in accordance with the above terms for Net Revenue earned
for the period after Payment Details are updated. Licensor further
understands that Payments may be subject to withholding tax which
will be paid on behalf of Licensor to the appropriate tax authority.
</FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Licensor
agrees that WooGlobe is entitled to deduct a reasonable sum from any
revenue to cover the costs incurred to generate interest in the
images. For the avoidance of doubt, any such deductions shall be made
prior to our calculation of the revenue share.</FONT></FONT></p>';
        $pdf->writeHTML($html);
        $pdf->AddPage('P','','A4');
        $html = '
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><u><b>3.
Licensor Representations and Warranties. </b></u></FONT></FONT><br />
<FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">(a)
Owner of Rights: Licensor has the sole, exclusive and unencumbered
ownership of all rights of every kind and character throughout the
universe in and to the Licensed Rights and has clear title to the
material upon which the Images are based. Licensor has the absolute
right to grant to WooGlobe, all rights, licenses and privileges
granted to or vested in WooGlobe under this Agreement. Licensor has
not authorized and will not authorize any other party to exercise any
right or take any action that impairs the rights herein granted to 
WooGlobe. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">(b)
Rights Are Clear: Licensor has obtained all clearances and paid all
monies necessary for WooGlobe to exercise its exclusive rights
hereunder and there will not be any other rights to be cleared or any
payments required to be made by WooGlobe as a result of any use of
the Images pursuant to the rights and licenses herein granted
(including without limitation, payments in connection with contingent
participations, residuals, clearance rights, moral rights, union
fees, and music rights). Licensor has not previously entered into any
other agreement in connection with the Images. All of the individuals
and entities connected with the production of the Images, and all of
the individuals and entities whose names, voices, photographs,
likenesses, appearance, works, services and other materials appear or
have been used in the Images, have authorized and approved Licensor’s
use thereof, and WooGlobe shall have the right to use all names,
voices, photographs, likenesses, appearance and performances
contained in the Images in connection with the exploitation,
promotion, and use of the Licensed Rights. It is expressly understood
that WooGlobe has not assumed any obligations under any contracts
entered into by Licensor. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">(c)
No Infringement: No part of the Images, any materials contained
therein, or the exercise by WooGlobe of the Licensed Rights violates
or will violate, or infringes or will infringe, any trademark, trade
name, contract, agreement, copyright (whether common law or
statutory), patent, literary, artistic, music, dramatic, personal,
private, civil, property, privacy or publicity right or &quot;moral
rights of authors&quot; or any other right of any person or entity,
and shall not give rise to a claim of slander or libel. There are no
existing, anticipated, or threatened claims or litigation that would
adversely affect or impair any of the Licensed Rights. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>4.
Termination.</B></U>
Licensor may seek to terminate this Agreement after one year of
signing the agreement; however, this Agreement shall only be
terminable upon the mutual agreement of the parties, the consent of
which may be granted or denied in WooGlobe’s sole discretion. No
termination shall impact any prior license of the Images by WooGlobe
prior to termination, which shall continue in full effect under the
terms of this Agreement. Any
use of the images in promotions or compilations created by WooGlobe
or its affiliates, prior to the termination of this agreement, shall
survive termination and that such use shall not be a breach of any of
Licensor’s rights. WooGlobe may terminate this agreement
immediately with no obligation to the Licensor if Licensor is in
breach of any term of the contract. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>5.
Release and Indemnity.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor hereby agrees to indemnify, release and hold harmless
WooGlobe, its successors, licensees, sub-distributors and assigns,
and the directors, officers, employees, representatives and agents of
each of the foregoing, from any and all claims, demands, causes of
action, damages, judgments, liabilities, losses, costs, expenses, and
attorney’s fees arising out of or resulting from (i) any breach by
Licensor of any warranty, representation or any other provision of
this Agreement, and/or (ii) any claims of or respecting slander,
libel, defamation, invasion of privacy or right of publicity, false
light, infringement of copyright or trademark, or violations of any
other rights arising out of or relating to any use by WooGlobe of the
rights granted under this Agreement. Licensor acknowledges that
WooGlobe is relying on the representations contained in this
Agreement and a breach by Licensor would cause WooGlobe irrevocable
injury and damage that cannot be adequately compensated by damages in
an action at law and Licensor therefore expressly agrees that,
without limiting WooGlobe’s remedies, WooGlobe shall be entitled to
injunctive and other equitable relief. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>6.
No Guarantee Regarding Revenue.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor acknowledges and agrees that WooGlobe is not making any
representation, guarantee or agreement regarding the total amount of
revenue, if any, which will be generated by the Licensed Rights.
Licensor agrees that the judgment of WooGlobe regarding the
exploitation of the Licensed Rights shall be binding and conclusive
upon Licensor and agrees not to make any claim or action that
WooGlobe has not properly exploited the Licensed Rights, that more
revenue could have been earned than was actually earned by the
exploitation of the Licensed Rights, or that any buyout or one-time
payment to Licensor is insufficient in comparison to the revenue
earned by the exploitation of the Licensed Rights. Nothing in this
Agreement shall obligate WooGlobe to actually use or to exploit the
Licensed Rights. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>7.
Publicity/Confidentiality.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor shall not release, disseminate, issue, authorize or cause
the release, dissemination or issuance of any publicity or
information concerning the Licensed Rights, WooGlobe, or the terms of
this Agreement without WooGlobe’s prior specific written consent
(including, without limitation, posting, participating or engaging in
social media discussions, news stories, blogs, reports or responses
thereto), and Licensor shall direct all licensing or other inquiries
relating to the Images solely to WooGlobe. The parties acknowledge
that the terms and provisions of this Agreement are confidential in
nature and agree not to disclose the content or substance thereof to
any third parties other than: (i) the parties’ respective attorneys
and accountants, (ii) as may be necessary to defend Licensor’s
and/or WooGlobe’s rights, and/or (iii) as may be reasonably
required in order to comply with any obligations imposed by the
Agreement, or any statute, ordinance, rule, regulation, other law, or
court order. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>8.
Confidentiality.</B></U>
Licensor acknowledges that the terms and provisions of this Agreement
are confidential in nature and agrees not to disclose the content or
substance thereof to any third parties, other than Licensor\'s
respective attorneys and accountants, or as may be reasonably
required in order to comply with any obligations imposed by this
Agreement. Licensor acknowledges that any unauthorized disclosure,
statement, or publicity may subject WooGlobe to substantial damages,
the exact amount of which are extremely difficult and impractical to
determine, and such unauthorized disclosure shall subject Licensor to
legal liability (including an injunction to prevent further
disclosure). </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>9.
Electronic Signature Agreement.</B></U>
The Licensor agrees that by entering their name into the space
designated above or through the use of any electronic signature
software/service or by any other means, Licensor is agreeing to the
terms of this agreement electronically. The Licensor agrees that the
electronic signature is the legal equivalent of manual signature on
this Agreement and that no certification authority or other third
party verification is necessary to validate Licensor’s e-signature.
The lack of such certification or third party verification will not
in any way affect the enforceability of Licensor’s e-signature or
any resulting contract between Licensor and WooGlobe. </FONT></FONT>
</p>
<p><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>10.
Severability.</B></U></FONT><FONT SIZE=2 STYLE="font-size: 11pt"> If any
provision of this Agreement is illegal and unenforceable in whole or
in part, the remainder of this Agreement shall remain enforceable to
the extent permitted by law. </FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>11.
Miscellaneous.</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor acknowledges and warrants that this Agreement has not been
induced by any representation or assurance not contained herein. This
Agreement supersedes and replaces all prior agreements, negotiations
or understandings in connection with the Licensed Rights, including
without limitation any simplified explanation of the terms herein,
and in the event there are any inconsistencies between this
English-language contract and any translations of terms and
conditions, the English-language version shall prevail. This
Agreement contains the entire understanding of the parties and shall
not be modified or amended except by a written document executed by
both parties. If any provision of this Agreement is found to be
unlawful or unenforceable, such provision shall be limited only to
the extent necessary, with all other provisions of the Agreement
remaining in effect. The waiver by either party or consent to a
breach of any provision of this Agreement by the other party shall
not operate or be construed as a waiver of, consent to, or excuse of
any other or subsequent breach by the other party. WooGlobe shall
have the right to assign freely this Agreement, the Licensed Rights
and/or any of WooGlobe’s other rights hereunder to any person or
entity (by operation of law or otherwise). Licensor may not assign
this Agreement or the Licensed Rights. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>12.
Choice of Law/Dispute Resolution.</B></U>
This Agreement shall be deemed to have been executed and delivered
within England, UK, and the rights and obligations of the parties
hereunder shall be construed and enforced in accordance with
English law, without regard to the conflicts of law principles
thereof. Any disputes relating to these terms and conditions shall be
subject to the non-exclusive jurisdiction of the courts of England.
The parties agree to the personal jurisdiction by and venue in
England, and waive any objection to such jurisdiction or venue
irrespective of the fact that a party may not be a resident of
England. </FONT></FONT>
</p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">Except
for WooGlobe’s equitable rights as set forth in this Agreement, the parties hereby
agree to submit any disputes or controversies arising from, relating
to or in connection with this Agreement or the parties’ respective
obligations in connection therewith to binding arbitration in England
in accordance with the English law and only for actual monetary
damages, if any. In
the event of any dispute, Licensor shall not be entitled to, and does
hereby waive all right to, any equitable relief whatsoever, including
the right to rescind its agreement to these Terms, to rescind any
rights granted hereunder, or to enjoin, restrain or interfere in any
manner with the marketing, advertisement, distribution or
exploitation of the Licensed Rights. All rights to recover
consequential, incidental and/or punitive damages are waived by
Licensor.</FONT></FONT></p>
<p><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><U><B>13.
Terms &amp; Conditions</B></U></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt"><B>.</B></FONT></FONT><FONT COLOR="#000000"><FONT SIZE=2 STYLE="font-size: 11pt">
Licensor may be required to agree to additional terms and conditions
displayed on the WooGlobe website at www.WooGlobe.com and its
partners in connection with the management of this Agreement and the
payments related thereto, which will be incorporated herein by
reference and subject to change. </FONT></FONT>
</p>
';
        $pdf->writeHTML($html);

        $pdf->AddPage('P','','A4');
        $addres_html='';
        $location_country='';
        $location_region_code='';
        $location_city='';
        $location_continent_name='';
        $location_zip='';
        $location_latitude='';
        $location_longitude='';
        if(isset($api_result['country_name'])){
            $location_country =$api_result['country_name'];
        }
        if(isset($api_result['region_code'])){
            $location_region_code =$api_result['region_code'];
        }
        if(isset($api_result['city'])){
            $location_city =$api_result['city'];
        }
        if(isset($api_result['continent_name'])){
            $location_continent_name =$api_result['continent_name'];
        }
        if(isset($api_result['zip'])){
            $location_zip =$api_result['zip'];
        }
        if(isset($api_result['latitude'])){
            $location_latitude =$api_result['latitude'];
        }
        if(isset($api_result['longitude'])){
            $location_longitude =$api_result['longitude'];
            $addres_html =' <p>Continent : '.$location_continent_name.'</p>
        <p>Country : '.$location_country.'</p>';
        }

        $html = '<p style="text-align: left"><img src="'.root_path().'/admin/assets/assets/img/logo.png" width="80px" height="80px"></p>
      <span style="font-size: 30px">Signing Log</span>
        <p>Document ID : '.$unique_key.'</p>
        <p>----------------------------------------------------------------------------------------------------------------------------</p>
        <p>WooGlobe</p>
        <p>Document Name:   '.$unique_key.'_signed.pdf</p>
        <p>Sent On:  '.$data_log["lead_rated_date"].' GMT</p>
        <p>IP Address: '.$data_log["user_ip_address"].'</p>
        <p>User Agent: '.$data_log["user_browser"].'</p>
        <p>Contract Signed Date And Time:  '.$data_log["contract_signed_datetime"].' GMT</p>
        <p>Contract Created Date And Time:  '.date('Y-m-d H:i:s').'GMT</p>
        '.$addres_html.'<br>
        ';
        $pdf->writeHTML($html);
// reset pointer to the last page
        $pdf->lastPage();
//Close and output PDF document
        $output_file=root_path(). '/uploads/'.$unique_key.'/second_signer/'.$unique_key.'_signed_'.$time.'.pdf';
        if (! file_exists ( root_path(). '/uploads/'.$unique_key.'/second_signer' )) {
            mkdir(root_path(). '/uploads/'.$unique_key.'/second_signer',0777,true);
            $output_file=root_path(). '/uploads/'.$unique_key.'/second_signer/'.$unique_key.'_signed_'.$time.'.pdf';
        }
        $pdf->Output($output_file, 'F');

    }
    public function appreance_signed_pdf($url_name,$first_name,$last_name,$email,$phone,$country,$state,$city,$address,$zip,$signature,$help_us,$date,$unique_key,$data_log,$api_result,$time){
        /*echo'<pre>';
        print_r($data1);
        exit();*/

        $this->base30_to_jpeg ( $signature, root_path(). '/uploads/'.$unique_key.'/appreance/'.$unique_key.'_'.$time.'.png' );
        $signimgpath= root_path().'/uploads/'.$unique_key.'/appreance/'.$unique_key.'_'.$time.'.png';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //$certificate = 'file://'.root_path().'/admin/app/libraries/TCPDF-master/examples/data/cert/tcpdf.crt';
        $certificate = 'file://'.root_path().'/admin/app/libraries/TCPDF-master/examples/data/cert/signature.crt';

// set additional information
        $info = array(
            'Name' => 'WooGlobe',
            'Location' => 'viral@wooglobe.com',
            'Reason' => 'WooGlobe Contract Signing',
            'ContactInfo' => 'https://wooglobe.com/',
        );

// set document signature
        $pdf->setSignature($certificate, $certificate, 'tcpdfdemo', '', 2, $info);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('WooGlobe');
        $pdf->SetTitle('WooGlobe');
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
// add a page
        $pdf->AddPage('L','','A4');

        $html = '<img src="'.root_path().'/assets/img/wooglobe-con-1.png" width="1000px" height="600px">';
        $pdf->writeHTML($html);
        /*$fontname = TCPDF_FONTS::addTTFfont('calibri.ttf', 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 12);*/
// add a page
        $pdf->AddPage('P','','A4');
        $html = '
<style>
        h1.heading {
            text-align: center;
        }

    </style>
        <h1 class="heading"><FONT FACE="Helvetica Neue, serif"><u>WooGlobe Appearance Release</u></FONT></h1>
       

<p STYLE="margin-bottom: 0in"><FONT COLOR="#000000"><SPAN STYLE="background: #ffffff">1. For good and valuable consideration, the receipt of which is hereby acknowledged, I hereby irrevocably grant to WooGlobe Ltd and its subsidiaries, parents, designees, licensees, successors and assigns (collectively, the “Company”), the absolute and unrestricted right and permission to record, copy, reproduce, adapt, modify, summarize, copyright, photograph, film, license, vend, rent, distribute, televise, publish, exhibit, disseminate, display, perform and otherwise exploit in any and all markets and media (collectively “use”) my appearance, name, likeness, voice, documents, biographical data, and other media artifacts provided to the Company by me or concerning me (collectively the “Materials”). This grant of rights is made without limitation upon time, circumstances, location, market, or medium of use, and includes without limitation all uses of the Materials in all types of content.
<BR><BR>
2. I understand that all approvals or uses to which any of the Materials may be put will be determined by the producer working with the content/Materials, without limitation to any program, product, or service, and the related advertising and promotion thereof.  
<BR><BR>
3. Recognizing the Company’s reliance upon this Appearance Release, I hereby irrevocably release, discharge, and agree to indemnify and hold harmless the Company from and against all actions, damages, costs, liabilities, claims, losses, and expenses of every type and description, including without limitation any claim for violation, infringement, or invasion of any copyright, trademark right, privacy or publicity right, defamation, or any other right whatsoever that I now have or may ever have resulting from or relating to any such use of the Materials.
<BR><BR>
4. I agree that the Company may copyright all audio and/or video recordings of the Materials, and that the Company and/or its licensees may copyright in its name and for its sole benefit any such audio or video recording containing the Materials.
<BR><BR>
5. Nothing herein will constitute any obligation on the part of the Company to make any use of the rights or the Materials set forth above.
<BR><BR>
6. I acknowledge that the terms and provisions of this Appearance Release are confidential in nature, and therefore agree that neither I nor my representatives will disclose the content or substance thereof to any third parties.  Neither I nor my representatives shall issue any press releases or public statements about this Appearance Release or Company without Company’s prior written permission.
<BR><BR>
7. This Appearance Release shall be governed by, and construed in accordance with, the laws of England & Wales and the parties submit to the exclusive jurisdiction of the courts of England & Wales, United Kingdom. I waive the right to revoke this Appearance Release, as well as any other right to injunctive or other equitable relief in connection with this Appearance Release.
</SPAN></FONT><FONT COLOR="#000000"><BR><BR></FONT>';
// output the HTML content
        $pdf->writeHTML($html);

        $pdf->AddPage('P','','A4');

        $html = '

<style>
       h1.heading {
            text-align: center;
        }

        .top-form,
        .bottom-form {
            background-color: #ececec;
           
            
        }
        
        td {
            text-align: center;
        }

    </style>
        <h1 class="heading">Appearance Release Agreement</h1>
       
                <div class="top-form"  style="border: 3px solid #30538f;border-radius: 10px" >
                   
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Video URL <font color="red">*</font> :'.$url_name.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;First Name  <font color="red">*</font> :'.$first_name.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Last Name <font color="red">*</font> :'.$last_name.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Email <font color="red">*</font> :'.$email.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Phone <font color="red">*</font> :'.$phone.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Address <font color="red">*</font> :'.$address.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;City <font color="red">*</font> :'.$city.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;State <font color="red">*</font> :'.$state.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Postal Code <font color="red">*</font> :'.$zip.'</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Country <font color="red">*</font> :'.$country.'</p>
                        <p >&nbsp;&nbsp;&nbsp;&nbsp;Help us find you in the video <font color="red">*</font> : '.$help_us.'</p>

                </div>
               
                    <p style="height: 40px;"> &nbsp;</p>
                    <div>
                    <table>
                        <tr>
                            <td>'.$first_name.' '.$last_name.'</td>
                            <td></td>
                            <td>'.$date.'</td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #7f7f7f">Name</td>
                            <td style="border-top: 1px solid #7f7f7f">Signature</td>
                            <td style="border-top: 1px solid #7f7f7f">Date</td>
                      </tr>
                    </table>  
                </div>


';

        $pdf->writeHTML($html);

        $pdf->Image($signimgpath, 100, 168, 18, 14, 'PNG');

// define active area for signature appearance
        $pdf->setSignatureAppearance(100, 168, 18, 14);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// *** set an empty signature appearance ***
        $pdf->addEmptySignatureAppearance(100, 173, 18, 14);
        $pdf->AddPage('P','','A4');
        $addres_html='';
        $location_country='';
        $location_region_code='';
        $location_city='';
        $location_continent_name='';
        $location_zip='';
        $location_latitude='';
        $location_longitude='';
        if(isset($api_result['country_name'])){
            $location_country =$api_result['country_name'];
        }
        if(isset($api_result['region_code'])){
            $location_region_code =$api_result['region_code'];
        }
        if(isset($api_result['city'])){
            $location_city =$api_result['city'];
        }
        if(isset($api_result['continent_name'])){
            $location_continent_name =$api_result['continent_name'];
        }
        if(isset($api_result['zip'])){
            $location_zip =$api_result['zip'];
        }
        if(isset($api_result['latitude'])){
            $location_latitude =$api_result['latitude'];
        }
        if(isset($api_result['longitude'])){
            $location_longitude =$api_result['longitude'];
            $addres_html =' <p>Continent : '.$location_continent_name.'</p>
        <p>Country : '.$location_country.'</p>';
        }

        $html = '<p style="text-align: left"><img src="'.root_path().'/admin/assets/assets/img/logo.png" width="80px" height="80px"></p>
       <span style="font-size: 30px">Signing Log</span>
        <p>Document ID : '.$unique_key.'</p>
        <p>----------------------------------------------------------------------------------------------------------------------------</p>
        <p>WooGlobe</p>
        <p>Document Name:   '.$unique_key.'_signed.pdf</p>
        <p>Sent On:  '.$data_log["lead_rated_date"].' GMT</p>
        <p>IP Address: '.$data_log["user_ip_address"].'</p>
        <p>User Agent: '.$data_log["user_browser"].'</p>
        <p>Contract Signed Date And Time:  '.$data_log["contract_signed_datetime"].' GMT</p>
        <p>Contract Created Date And Time:  '.date('Y-m-d H:i:s').'GMT</p>
        '.$addres_html.'<br>
        ';
        $pdf->writeHTML($html);
// reset pointer to the last page
        $pdf->lastPage();
//Close and output PDF document
        $output_file=root_path(). '/uploads/'.$unique_key.'/appreance/'.$unique_key.'_signed_'.$time.'.pdf';
        if (! file_exists ( root_path(). '/uploads/'.$unique_key.'/appreance' )) {
            mkdir(root_path(). '/uploads/'.$unique_key.'/appreance',0777,true);
            $output_file=root_path(). '/uploads/'.$unique_key.'/appreance/'.$unique_key.'_signed_'.$time.'.pdf';
        }

        $pdf->Output($output_file, 'F');

    }

}
