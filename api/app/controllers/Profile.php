<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Profile extends APP_Controller {



	public function __construct() {
        parent::__construct();


        $this->load->model('Profile_Model','profile');
        $this->load->model('Location_Model','location');
        $this->load->model('User_Model','user');
        $this->load->model('Auth_Model','auth');
        $this->data['active'] = 'profile';
        $this->data['nav_profile'] = 'pofile';
        //$this->data['profile_menu'] = array('profile'=>'Profile','change-password'=>'Change Password','my-videos'=>'My Videos','upload-video'=>'Upload Video');
        $this->data['profile_menu'] = array('profile'=>'Profile','change-password'=>'Change Password');

        $client_id = 0;

        if($this->sess->userdata('clientId') != '') {

            $client_id = $this->sess->userdata('clientId');
        }
        $this->data['userData'] = $this->user->getUserById($client_id);




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
        $this->validation->set_rules('dob','Date Of Birth','trim|required');
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


        $ad_revenue = $this->profile->getAdRevenue($id,$slug);
        $this->data['ad_revenue'] = $ad_revenue->result_array();


        $videos_title = $this->profile->getAdVideosTitle($id);
        $this->data['videos_title'] = $videos_title->result_array();

        $this->data['title'] = 'Account Summary';
        $this->data['profile_nav'] = 'Ad-Revenue';
        $this->data['profile_menu'] = array('Ad-Revenue'=>'ad-revenue','Licensing'=>'Licensing');
        $this->data['js'][] = 'earnings';
        $this->data['content'] = $this->load->view('account_summary',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}

    public function account_summary()
    {
        auth();
        $id = $this->sess->userdata('clientId');

        $slug = $this->input->get('video');

        $videos_title = $this->profile->getVideosTitle($id);
        $this->data['videos_title'] = $videos_title->result_array();

        $revenue = $this->profile->getRevenue($id,$slug);

        $nextPayment = $this->profile->getNextPayment($id);
        $next = $nextPayment->row();
        $this->data['next_payment'] = $next->next_payment;

        $paid = $this->profile->paid($id);
        $paid = $paid->row();
        $this->data['paid'] = $paid->paid;

        $this->data['js'][] = 'earnings';
        $this->data['total_revenue'] = $revenue->result_array();
        $this->data['title'] = 'Account Summary';
        $this->data['profile_nav'] = 'account-summary';
        $this->data['profile_menu'] = array('account-summary'=>'Account Summary');
        $this->data['content'] = $this->load->view('total_account_summary',$this->data,true);
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
        $this->data['content'] = $this->load->view('video_detail_view',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}
    public function submit_video()
    {
        auth();

        $slug = $this->uri->segment(2);
        if(empty($slug)){
            $client_id = $this->sess->userdata('clientId');
            $result = $this->profile->getLeadById($client_id);
            $requests = $result->row_array();
        }
        else{
            $result = $this->profile->getLeadBySlug($slug);
            $requests = $result->row_array();

        }
        $results = $this->profile->checkView($requests['client_id'], $requests['id']);
        $results = $results->row_array();

        $users = $this->profile->checkUsers($requests['client_id']);

        /*echo '<pre>';
        print_r($users);
        exit;*/

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
        $this->data['countries'] = $this->location->getCountries();

        $this->data['content'] = $this->load->view('submit_video1',$this->data,true);
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

            $data['slug'] = slug($leadData->video_title,'videos', 'slug');
            $id = $this->profile->insert_video($data);


            action_add($lead_id,$id,0 ,0,0,'Orginal video files uploaded');
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
}
