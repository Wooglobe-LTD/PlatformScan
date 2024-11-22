<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends APP_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('Home_Model','home');
    }

	public function index($categorySlug = 'home')
	{
        $video = $this->input->get('video');
        if(!empty($video)){
            $this->details($video);
        }
        else {
            // $categorySlug = $this->security->xss_clean($categorySlug);
            // $sort = 'DESC';
            // $by = 'v.id';
            // $start = 0;
            // $limit = 0;
            // $search = '';
            // $this->data['banner'] = true;
            // $this->data['active'] = 'home';
            // $this->data['title'] = 'Home';
            // $this->data['body_class'] = 'body-home';
			// $metaTags = getPageById(1);
			// $this->data['share_url'] = current_url();
			// if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
			// 	$this->data['title'] = $metaTags->title;
			// 	$this->data['keywords'] = $metaTags->meta_keywords;
			// 	$this->data['description'] = $metaTags->meta_description;
			// 	$this->data['share_title'] = $metaTags->title;
			// 	$this->data['share_description'] = $metaTags->meta_description;
			// }
            // $this->data['videos'] = $this->home->getVideosByCategorySlug($categorySlug, $search, $start, $limit, $by, $sort);
            // $this->data['content'] = $this->load->view('home', $this->data, true);
            // $this->load->view('common_files/template', $this->data);
            redirect('404_override');
        }
	}

	public function categories() {

        $slug = $this->uri->segment(2);
        $this->data['active'] = 'cate';
        $this->data['cate_url'] = '';
        $this->data['slug'] = $slug;
        $this->data['categories'] = $this->home->getVideosData($slug);

        $this->data['latest'] = $this->home->getLatestVideo();
        $this->data['trending'] = $this->home->getTrendingVideo();
		$metaTags = getPageById(7);
		$this->data['share_url'] = current_url();
		if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
			$this->data['title'] = $metaTags->title;
			$this->data['keywords'] = $metaTags->meta_keywords;
			$this->data['description'] = $metaTags->meta_description;
			$this->data['share_title'] = $metaTags->title;
			$this->data['share_description'] = $metaTags->meta_description;
		}
        $this->data['content'] = $this->load->view('categories', $this->data, true);
        $this->load->view('common_files/template', $this->data);

    }
    public function categories_partner() {

        $slug = $this->uri->segment(3);
        $this->data['active'] = 'cate_buy';
        $this->data['cate_url'] = 'partner/';
        $this->data['slug'] = $slug;
        if($slug =='trending'){
            $this->data['categories'] = $this->home->getTrendingVideo(0,9);
        }elseif($slug =='latest'){
            $this->data['categories'] = $this->home->getLatestVideo(0,9);
        }else{
            $this->data['categories'] = $this->home->getVideosDataPagination($slug,0);
        }

        $this->data['latest'] = $this->home->getLatestVideo(0,3);
        $this->data['trending'] = $this->home->getTrendingVideo(0,3);
		$metaTags = getPageById(7);
		$this->data['share_url'] = current_url();
        if($slug =='trending'){
            $this->data['total_videos'] = $this->home->getTrendingVideo(0,0)->num_rows();
        }elseif($slug =='latest'){
            $this->data['total_videos'] = $this->home->getLatestVideo(0,0)->num_rows();
        }

        else{
            $this->data['total_videos'] = $this->home->getVideosData($slug);
            if(isset($this->data['total_videos'][0])){
                $this->data['total_videos'] = count($this->data['total_videos'][0]->videos);
            }else{
                $this->data['total_videos'] = 0;
            }
        }


		if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
			$this->data['title'] = $metaTags->title;
			$this->data['keywords'] = $metaTags->meta_keywords;
			$this->data['description'] = $metaTags->meta_description;
			$this->data['share_title'] = $metaTags->title;
			$this->data['share_description'] = $metaTags->meta_description;
		}

        $this->data['content'] = $this->load->view('categories_partner', $this->data, true);
        $this->load->view('common_files/template', $this->data);

    }
    public function categories_partner_load() {

        $slug = $this->uri->segment(2);
        $this->data['slug'] = $slug;
        $start = $this->input->post('start');

        if($slug =='trending'){
            $this->data['categories'] = $this->home->getTrendingVideo($start,9);
        }elseif($slug =='latest'){
            $this->data['categories'] = $this->home->getLatestVideo($start,9);
        }else{
            $this->data['categories'] = $this->home->getVideosDataPagination($slug,$start);
        }


        $html = $this->load->view('categories_partner_load', $this->data, true);
        echo $html;

    }

	public function about_us() {
        $this->data['title'] = 'About Us';
        $this->data['active'] = 'about-us';
        $this->data['page'] = $this->home->getPageContentById(2);
		$metaTags = getPageById(2);
		$this->data['share_url'] = current_url();
		if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
			$this->data['title'] = $metaTags->title;
			$this->data['keywords'] = $metaTags->meta_keywords;
			$this->data['description'] = $metaTags->meta_description;
			$this->data['share_title'] = $metaTags->title;
			$this->data['share_description'] = $metaTags->meta_description;
		}
        $this->data['content'] = $this->load->view('about_us',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }
    public function contact_us() {
        $this->data['title'] = 'Contact Us';
        $this->data['active'] = 'contact-us';
        $this->data['js'][] = 'gmap3.min';
        $this->data['js'][] = 'contact';
		$metaTags = getPageById(3);
		$this->data['share_url'] = current_url();
		if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
			$this->data['title'] = $metaTags->title;
			$this->data['keywords'] = $metaTags->meta_keywords;
			$this->data['description'] = $metaTags->meta_description;
			$this->data['share_title'] = $metaTags->title;
			$this->data['share_description'] = $metaTags->meta_description;
		}
        $this->data['content'] = $this->load->view('contact_us',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

	public function privacy() {
        $this->data['title'] = 'Privacy Policy';
        $this->data['active'] = 'privacy';
		$metaTags = getPageById(11);
		$this->data['share_url'] = current_url();
		if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
			$this->data['title'] = $metaTags->title;
			$this->data['keywords'] = $metaTags->meta_keywords;
			$this->data['description'] = $metaTags->meta_description;
			$this->data['share_title'] = $metaTags->title;
			$this->data['share_description'] = $metaTags->meta_description;
		}
        $this->data['content'] = $this->load->view('privacy',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }
    public function appearance() {
        $this->data['title'] = 'Appearance Release';
        $this->data['active'] = 'appearance';
        $metaTags = getPageById(11);
        $this->data['share_url'] = current_url();
        if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
            $this->data['title'] = $metaTags->title;
            $this->data['keywords'] = $metaTags->meta_keywords;
            $this->data['description'] = $metaTags->meta_description;
            $this->data['share_title'] = $metaTags->title;
            $this->data['share_description'] = $metaTags->meta_description;
        }
        $this->data['content'] = $this->load->view('appearance',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }
    public function faq() {
        $this->data['title'] = 'faq';
        $this->data['active'] = 'faq';
        //$this->data['js'][] = 'faq';
		$metaTags = getPageById(5);
		$this->data['share_url'] = current_url();
		if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
			$this->data['title'] = $metaTags->title;
			$this->data['keywords'] = $metaTags->meta_keywords;
			$this->data['description'] = $metaTags->meta_description;
			$this->data['share_title'] = $metaTags->title;
			$this->data['share_description'] = $metaTags->meta_description;
		}
        $this->data['content'] = $this->load->view('faq',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function terms_of_use() {
        $this->data['title'] = 'Terms of Use';
        $this->data['active'] = 'terms_of_use';
        $this->data['js'][] = 'terms_of_use';
		$metaTags = getPageById(9);
		$this->data['share_url'] = current_url();
		if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
			$this->data['title'] = $metaTags->title;
			$this->data['keywords'] = $metaTags->meta_keywords;
			$this->data['description'] = $metaTags->meta_description;
			$this->data['share_title'] = $metaTags->title;
			$this->data['share_description'] = $metaTags->meta_description;
		}
        $this->data['content'] = $this->load->view('terms_of_use',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }
    public function terms_of_submission() {
        $this->data['title'] = 'Terms of Submission';
        $this->data['active'] = 'terms_of_submission';
        $this->data['js'][] = 'terms_of_submission';
		$metaTags = getPageById(10);
		$this->data['share_url'] = current_url();
		if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
			$this->data['title'] = $metaTags->title;
			$this->data['keywords'] = $metaTags->meta_keywords;
			$this->data['description'] = $metaTags->meta_description;
			$this->data['share_title'] = $metaTags->title;
			$this->data['share_description'] = $metaTags->meta_description;
		}
        $this->data['content'] = $this->load->view('terms_of_submission',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }
    public function terms_of_submission_video_sharing() {
        $this->data['title'] = 'Terms of Submission';
        $this->data['active'] = 'terms_of_submission';
        $this->data['js'][] = 'terms_of_submission';
        $metaTags = getPageById(11);
        $this->data['share_url'] = current_url();
        if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
            $this->data['title'] = $metaTags->title;
            $this->data['keywords'] = $metaTags->meta_keywords;
            $this->data['description'] = $metaTags->meta_description;
            $this->data['share_title'] = $metaTags->title;
            $this->data['share_description'] = $metaTags->meta_description;
        }
        $this->data['content'] = $this->load->view('terms_of_submission_sharing',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function details($slug) {

        $page = $this->uri->segment(1);
        $button = false;
        if($page == 'partner'){
            $this->data['active'] = 'cate_buy';
            $this->data['cate_url'] = 'partner/';
            $button = true;
        }else{
            $this->data['active'] = 'cate';
            $this->data['cate_url'] = '';
        }
        $this->data['title'] = 'Video Details';
        $result = $this->home->video_details($slug);
       /* print_r($result);
        exit;*/
        /*     echo $this->db->last_query();
             print "cont  ....";
             print_r(count($result));
         exit;
         /*  	if(count($result) == 0){
                 redirect($this->data['url']);
             }*/
        if(count($result) == 0){
            redirect($this->data['url']);
        }
        //echo $this->db->last_query();exit;
        $this->load->model('Location_Model','location');
        $list = $this->home->list_videos($result['user_id']);
        $this->data['categories_videos'] = $this->home->getVideoCategories($result['category_id']);
        //echo $this->db->last_query();exit;
        $this->data['result'] = $result;
        $this->data['count_video'] = $this->home->view_count_video($result['id']);
        $this->data['countries'] = $this->location->getCountries();
        //$this->data['suggested'] = $this->home->getSuggestedVideos($result['category_id'],trim($result['vtitle']),$result['id']);
        //$this->data['related'] = $this->home->getRelatedVideos(trim($result['vtitle']),$result['id']);
        $this->data['list'] = $list;
        $this->data['body_class'] = '';
        $this->data['buy_button'] = $button;


		$this->data['share_url'] = current_url().'?video='.$slug;
		$this->data['share_image'] = $this->data['url'].$result['thumbnail'];
        if(!empty($result['vtitle']) || !empty($result['description']) || !empty($result['tags'])){
        	if(!empty($result['vtitle'])){
				$this->data['title'] = $result['vtitle'];
				$this->data['share_title'] = $result['vtitle'];
			}
			if(!empty($result['description'])){
				$this->data['description'] = $result['description'];
				$this->data['share_description'] = $result['description'];
			}
			if(!empty($result['tags'])){
				$this->data['keywords'] = $result['tags'];
			}


		}else{
			$metaTags = getPageById(8);
			if(!empty($metaTags->meta_keywords) && !empty($metaTags->meta_description) && !empty($metaTags->title)){
				$this->data['title'] = $metaTags->title;
				$this->data['keywords'] = $metaTags->meta_keywords;
				$this->data['description'] = $metaTags->meta_description;
			}
		}

        $this->data['content'] = $this->load->view('details',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }
    public function details_load_later() {
        $slug= $_POST['id'] ;


        $result = $this->home->video_details($slug);
        $this->data['related'] = $this->home->getRelatedVideos(trim($result['vtitle']),$result['id']);
        $response =$this->data['related']->result();
        echo json_encode($response);
        exit;
    }

    public function search() {
        $uri = $this->uri->segment(1);
        if($uri == 'partner'){
            $this->data['partner'] = true;
        }
        $this->data['cate_url'] = 'partner/';
        $video = $this->input->get('video');
        if(!empty($video)){
            $this->details($video);
        }

        else{
            $sort = 'DESC';
            $by = 'v.id';
            $start = 0;
            $limit = 0;
            $search = '';
            if(!empty($_GET['search'])){
                $search = trim($_GET['search']);
            }
            $this->data['banner'] = true;
            $this->data['search'] = $search;
            $this->data['videos_total'] = $this->home->videosSearch($search,$start,$limit,$by,$sort);
            $this->data['videos'] = $this->home->videosSearch($search,$start,15,$by,$sort);
			$this->data['latest'] = $this->home->getLatestVideo();
        	$this->data['trending'] = $this->home->getTrendingVideo();
            $this->data['content'] = $this->load->view('search',$this->data,true);
            $this->load->view('common_files/template',$this->data);
        }
    }
    public function search_load() {

        $sort = 'DESC';
        $by = 'v.id';
        $start = $_GET['start'];
        $limit = 15;
        $search = '';
        if(!empty($_GET['search'])){
            $search = trim($_GET['search']);
        }
        $this->data['search'] = $search;
        $this->data['videos'] = $this->home->videosSearch($search,$start,$limit,$by,$sort);

        $html = $this->load->view('search_load', $this->data, true);
        echo $html;

    }

    public function licensing_model() {

        $video =$this->input->get('video');
        $videoId = $this->home->getVideoId($video);
        $this->data['video_id'] = $videoId;
        $this->data['title'] = 'Buy Video';
        $this->data['js'][] = 'license';
        $result = $this->home->getCountries();
        $result1 =$this->home->getLicenseType();
        $this->data['countries'] = $result;
        $this->data['license_type'] = $result1;
        $this->data['content'] = $this->load->view('licensing_model',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }
    public function license_video() {

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Inquiry Placed Successfully. We Will contact you shortly!';
        $response['error'] = '';
        $response['url'] = '';
		if(!isset($_POST['check-other']) && !isset($_POST['chec'])){
			//$this->validation->set_rules('check[]','Media Type','required');
		}

        $this->validation->set_rules('territory','Territory','required');
        if(!empty($this->input->post('territory')) && $this->input->post('territory') == 'National') {
            $this->validation->set_rules('buy_country','Country','required');
        }
		if(isset($_POST['check-other'])){
			$this->validation->set_rules('other','Other','required');
		}
        $this->validation->set_rules('name', 'Name', 'required');
        $this->validation->set_rules('time','Duration','required');
        $this->validation->set_rules('programme','Programme or Publication','required');
        $this->validation->set_rules('country_code','Country Code','required');
        $this->validation->set_rules('contact_mobile','Mobile Number','required|max_length[10]');
        $this->validation->set_rules('contact_email','Email','trim|required|valid_email');



        if($this->validation->run() == false){
            $fields = array('territory','buy_country','other','name','country_code','contact_mobile','contact_email','programme','time');
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
        else {
			$dbData = $this->security->xss_clean($this->input->post());
			if(isset($_POST['check-other'])){
				$dbData['check'][] = $_POST['check-other'];
			}
			if(isset($_POST['chec'])){
				$dbData['check'][] = $_POST['chec'];
			}
			if(isset($_POST['selectall1'])){
				$dbData['check'][] = $_POST['selectall1'];
			}
            $data['video_id'] = $dbData['video_id'];
            $data['country_id'] = $dbData['buy_country'];
            $data['territory'] = $dbData['territory'];
            $data['created_at'] = date('Y-m-d H:i:s');

            $data['duration'] = $dbData['time'];
            $data['name'] = $dbData['name'];
            $data['license_type_id'] = 0;
            $data['media_types'] = implode(',',$dbData['check']);
            $data['programme_or_publication'] = $dbData['programme'];
            $data['country_code'] = $dbData['country_code'];
            $data['mobile'] = $dbData['contact_mobile'];
            $data['email'] = $dbData['contact_email'];


            $id =  $this->home->license_video($data);
            if(isset($id)){

                $info = $this->home->get_ids($id);
                $short_code = 'buy_partner_video_request';
                $result = $this->app->getTemplateByShortCode($short_code);
                $str = $result->message;
                $subject = $result->subject;
                $from = 'viral@wooglobe.com';
                $ids =array(
                    'license_type' => $info['license_type_id'],
                    'video_license' => $info['id'],
                    'users' => 0,
                    'videos' => $info['video_id'],
                    'countries' => $info['country_id']
                );

                $message = dynStr($str,$ids);

                $to = $dbData['contact_email'];
                $settings = settings();
                //$to1 = $settings->site_email;
                //$to1 = 'maliks_usman786@yahoo.com';
				$to1 = 'licensing@WooGlobe.com';

                $short_code1 = 'buy_wooglobe_video_request';

                $result1 =  $this->app->getTemplateByShortCode($short_code1);
                $str1 = $result1->message;
                $subject1 = $result1->subject;


                $message1 = dynStr($str1,$ids);


                $result =  $this->email($to,$to_name = '',$from,$from_name = '',$subject,$message,$cc = '',$bcc = '',$replyto = '',$replyto_name = '');
                $result1 =  $this->email($to1,$to_name = '',$from,$from_name = '',$subject1,$message1,$cc = '',$bcc = '',$replyto = '',$replyto_name = '');

                if($result && $result1){
                    $response['url'] = base_url();
                    $response['code'] = 200;
                    $response['message'] = 'We received your video request and Sent You an Email !';
                    echo json_encode($response);
                    exit;
                }
                else{
                    $response['url'] = base_url();
                    $response['code'] = 205;
                    $response['message'] = 'We received your video request and having problem while sending you Email !';
                    echo json_encode($response);
                    exit;
                }
            }
            else if(empty($id)){
                $response['code'] = 201;
                $response['message'] = 'Error While Placing Inquiry!';
                $response['error'] = '';
                $response['url'] = '';
                echo json_encode($response);
                exit;
            }

        }
    }
    public function contract_signed($slug) {

        $this->load->model('Upload','upload_video');
        $result = $this->upload_video->getDetailsBySlug($slug);

        $email = $result['email'];
        $name = $result['first_name'].' '.$result['last_name'];

        $this->data['lead'] = $result;

        $this->data['title'] = 'Contract Signed';
        $this->data['content'] = $this->load->view('contract_signed', $this->data, true);
        $this->load->view('common_files/template', $this->data);

    }

    public function mrss($url) {
        $query_category = "SELECT cm.*
                FROM mrss_feeds cm
                WHERE cm.status = 1
                AND cm.deleted = 0
                AND cm.url = '$url'
        ";
        $category = $this->db->query($query_category);

        if($category->num_rows() > 0) {
            $category = $category->row();
            $query = 'SELECT v.*,vl.unique_key,vl.created_at,fd.feed_id,wv.s3_url w_url
                FROM videos v
                INNER JOIN video_leads vl
                ON v.lead_id = vl.id
                INNER JOIN feed_video fd ON v.id = fd.video_id
                LEFT JOIN watermark_videos wv ON v.id = wv.video_id
                WHERE v.status = 1
                AND v.deleted = 0
                AND v.is_wooglobe_video = 1
                AND v.mrss = 1
                AND fd.feed_id = '.$category->id.'
                group by v.id
                ORDER BY v.created_at DESC
            ';

            $videos = $this->db->query($query);
            $title = "WooGlobe Video Feed";
            $link = $this->data['url'];
            $description = "Description of the WooGlobe Video Feed";
            // This is the language you display for this podcast.
            $lang = "en-us";
            // This is the copyright information.
            $copyright = "Copyright 2019 WooGlobe";
            $builddate = date(DATE_RFC2822);

            $html = '<!DOCTYPE html>
                    <html>
                    <head>
                        <title>' . $title . '</title>
                    </head>
                    <body>
                    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
                    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
                    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
                    <style>
                    .mrss-area {
                        padding-bottom: 30px;
                    }
                        .mrss-area h1 {
                            margin-bottom: 20px;
                        }
                        .video-feed-card {
                            border: 1px solid #e9e9e9;
                            border-radius: 4px;
                            margin-bottom: 20px;
                            -webkit-transition: all 0.3s;
                            -ms-transition: all 0.3s;
                            -o-transition: all 0.3s;
                            transition: all 0.3s;
                        }
                        .video-feed-card:hover {
                            -webkit-box-shadow: 0 0 12px rgba(0,0,0,0.15);
                            -moz-box-shadow: 0 0 12px rgba(0,0,0,0.15);
                            box-shadow: 0 0 12px rgba(0,0,0,0.15);
                        }
                        .video-feed-card > a {
                            display: -ms-flexbox;
                            display: flex;
                        }
                        .video-feed-card > a:hover,
                         .video-feed-card > a:focus{
                            text-decoration: none;
                            outline: 0;
                        }
                        .video-feed-card-thumb {
                            width: 300px;
                        }
                        .video-feed-card-thumb img {
                            border-radius: 4px 0 0 4px;
                        }
                        .video-feed-card-body {
                            -ms-flex: 1;
                            flex: 1;
                        }
                        .video-feed-content {
                            padding: 15px 20px;
                        }
                        .video-feed-title {
                            color: #f5544d;
                            font-weight: 700;
                        }
                        .video-feed_time {
                            color: #999;
                        }
                        .video-feed-desc {
                            color: #4d4d4d;
                            margin-top: 15px;
                            font-size: 16px;
                        }
                        .video-feed-tags {
                            margin-top: 8px;
                            color:#999999;
                        }
                        .video-feed-download {
                            display: inline-block;
                            background: #f5544d;
                            color: #fff;
                            line-height: 41px;
                            font-weight: 600;
                            padding: 0 30px;
                            font-size: 16px;
                        }
                        .video-feed-download:hover {
                            text-decoration: none;
                            color: #ffffff;
                        }
                    
                    </style>
                    <div class="container">
                    <div class="mrss-area">
                    <h1>' . $title . '</h1>
                    ';

            if ($videos->num_rows() > 0) {
                foreach ($videos->result() as $video) {
                    $query_raw = 'SELECT rv.s3_url
                            FROM videos v
                            INNER JOIN raw_video rv
                            ON rv.video_id = v.id
                            AND rv.video_id = '.$video->id;
                    $videos_raw = $this->db->query($query_raw);
                    $raw_vid=$videos_raw->result();
                    $raw_total=count($raw_vid);

                    $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                            FROM videos v
                            INNER JOIN edited_video ev
                            ON ev.video_id = v.id
                            AND ev.video_id = '.$video->id;
                    $videos_edited = $this->db->query($query_edited);
                    $vid=$videos_edited->result();

                    $videothumb = trim($video->youtube_id);
                    $thumb='';
                    $videourl='';
                    if(isset($vid[0])){
                        $thumb=$vid[0]->portal_thumb;
                    }
                    if($thumb){
                        $thumb=$thumb;
                    }else{
                        $thumb='https://img.youtube.com/vi/'.$videothumb.'/hqdefault.jpg';
                    }
                    if ($raw_total > 1) {
                        if(isset($vid[0])) {
                            $videourl = $vid[0]->portal_url;
                            if ($videourl == 'Manual Upload' || $videourl == 'http://manualupload.com') {
                                $videourl = 'yes';
                                if (isset($raw_vid[0])) {
                                    $videourl = $raw_vid[0]->s3_url;
                                    if (empty($videourl)) {
                                        $videourl = 'http://wooglobe.com/';
                                    }
                                    $ext = explode('.', $raw_vid[0]->s3_url);
                                    $ext = $ext[count($ext) - 1];
                                    if (empty($ext)) {
                                        $ext = 'mp4';
                                    }
                                }
                            } else {
                                $videourl = 'no';
                                $videourl = $vid[0]->portal_url;
                                if (empty($videourl)) {
                                    $videourl = 'http://wooglobe.com/';
                                }
                                $ext = explode('.', $vid[0]->portal_url);
                                $ext = $ext[count($ext) - 1];
                                if (empty($ext)) {
                                    $ext = 'mp4';
                                }
                            }
                        }else{
                            if (isset($raw_vid[0])) {
                                $videourl = $raw_vid[0]->s3_url;
                                if (empty($videourl)) {
                                    $videourl = 'http://wooglobe.com/';
                                }
                                $ext = explode('.', $raw_vid[0]->s3_url);
                                $ext = $ext[count($ext) - 1];
                                if (empty($ext)) {
                                    $ext = 'mp4';
                                }
                            }
                        }
                    } else {
                        if (isset($vid[0])) {
                            $videourl = $vid[0]->portal_url;
                            if ($videourl == 'Manual Upload' || $videourl == 'http://manualupload.com') {
                                if(isset($raw_vid[0])){
                                    $videourl = $raw_vid[0]->s3_url;
                                }
                            }elseif(empty($videourl)){
                                $videourl = 'https://WooGlobe.com/';
                            }
                            $ext = explode('.', $vid[0]->portal_url);
                        }elseif (isset($raw_vid[0])) {
                            $videourl = $raw_vid[0]->s3_url;
                            if(empty($videourl)){
                                $videourl = 'https://WooGlobe.com/';
                            }
                            $ext = explode('.', $raw_vid[0]->s3_url);
                        }
                    }

                    $html .= '<div class="video-feed-card"> 
                                       <a href="'. $videourl .'" target="_blank">
                                           <div class="video-feed-card-thumb">
                                                <div class="video-thumb"><img src="' . $thumb . '" class="img-responsive"></div>
                                           </div>
                                           <div class="video-feed-card-body">
                                                <div class="video-feed-content">
                                                    <h4 class="video-feed-title">' . $video->title . '</h4>
                                                    <div class="video-feed_time">' . date('d M, Y', strtotime($video->created_at)) . '</div>
                                                    <div class="video-feed-desc">' . $video->description . '</div>
                                                    <div class="video-feed-tags">' . $video->tags . '</div>
                                                </div>
                                           </div>
                                       </a>
                              </div> ';
                }
            }
            $html .= '<a class="video-feed-download" href="'.base_url().'/mrss/download/'.$url.'">Download Xml File</a></div></div>';
            $xml_file = '';
            echo $html.$xml_file;

        }
        else {
            echo 'Invalid URL';
            exit;
        }

    }
    public function mrss_story($p,$url) {
        $query_category = "SELECT cm.*
                FROM mrss_feeds cm
                WHERE cm.status = 1
                AND cm.deleted = 0
                AND cm.url = '".$p.'/'."$url'
        ";
        $category = $this->db->query($query_category);

        if($category->num_rows() > 0) {
            $category = $category->row();
            $query = 'SELECT v.*,vl.unique_key,vl.created_at,fd.feed_id,vl.is_story_content,vl.stroy_s3_url,fd.categories,fd.publish_story_content,fd.publish_story_title
                FROM video_leads vl 
                INNER JOIN videos v
                ON v.lead_id = vl.id
                INNER JOIN feed_video_story fd ON vl.id = fd.lead_id
                WHERE v.status = 1
                AND v.deleted = 0
                AND v.is_wooglobe_video = 1
                AND v.mrss = 1
                AND fd.feed_id = '.$category->id.'
                group by v.id
                ORDER BY v.created_at DESC
            ';
            $videos = $this->db->query($query);

            $title = "WooGlobe Video Feed";
            $link = $this->data['url'];
            $description = "Description of the WooGlobe Video Feed";
            // This is the language you display for this podcast.
            $lang = "en-us";
            // This is the copyright information.
            $copyright = "Copyright 2019 WooGlobe";
            $builddate = date(DATE_RFC2822);

            $html = '<!DOCTYPE html>
                    <html>
                    <head>
                        <title>' . $title . '</title>
                    </head>
                    <body>
                    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
                    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
                    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
                    <style>
                    .mrss-area {
                        padding-bottom: 30px;
                    }
                        .mrss-area h1 {
                            margin-bottom: 20px;
                        }
                        .video-feed-card {
                            border: 1px solid #e9e9e9;
                            border-radius: 4px;
                            margin-bottom: 20px;
                            -webkit-transition: all 0.3s;
                            -ms-transition: all 0.3s;
                            -o-transition: all 0.3s;
                            transition: all 0.3s;
                        }
                        .video-feed-card:hover {
                            -webkit-box-shadow: 0 0 12px rgba(0,0,0,0.15);
                            -moz-box-shadow: 0 0 12px rgba(0,0,0,0.15);
                            box-shadow: 0 0 12px rgba(0,0,0,0.15);
                        }
                        .video-feed-card > a {
                            display: -ms-flexbox;
                            display: flex;
                        }
                        .video-feed-card > a:hover,
                         .video-feed-card > a:focus{
                            text-decoration: none;
                            outline: 0;
                        }
                        .video-feed-card-thumb {
                            width: 300px;
                        }
                        .video-feed-card-thumb img {
                            border-radius: 4px 0 0 4px;
                        }
                        .video-feed-card-body {
                            -ms-flex: 1;
                            flex: 1;
                        }
                        .video-feed-content {
                            padding: 15px 20px;
                        }
                        .video-feed-title {
                            color: #f5544d;
                            font-weight: 700;
                        }
                        .video-feed_time {
                            color: #999;
                        }
                        .video-feed-desc {
                            color: #4d4d4d;
                            margin-top: 15px;
                            font-size: 16px;
                        }
                        .video-feed-tags {
                            margin-top: 8px;
                            color:#999999;
                        }
                        .video-feed-download {
                            display: inline-block;
                            background: #f5544d;
                            color: #fff;
                            line-height: 41px;
                            font-weight: 600;
                            padding: 0 30px;
                            font-size: 16px;
                        }
                        .video-feed-download:hover {
                            text-decoration: none;
                            color: #ffffff;
                        }
                    
                    </style>
                    <div class="container">
                    <div class="mrss-area">
                    <h1>' . $title . '</h1>
                    ';

            if ($videos->num_rows() > 0) {
                foreach ($videos->result() as $video) {
                    $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                        FROM videos v
                        INNER JOIN edited_video ev
                        ON ev.video_id = v.id
                        AND ev.video_id = '.$video->id;
                    $videos_edited = $this->db->query($query_edited);
                    $vid=$videos_edited->result();

                    $videothumb = trim($video->youtube_id);
                    $thumb='';
                    $videourl='';
                    if(!empty($video->s3_url_story_thumb)){
                        $thumb = $video->s3_url_story_thumb;
                    }else if(isset($vid[0])){
                        $thumb=$vid[0]->portal_thumb;
                    }
                    if($thumb){
                        $thumb=$thumb;
                    }else{
                        $thumb='https://img.youtube.com/vi/'.$videothumb.'/hqdefault.jpg';
                    }

                    if ($video->is_story_content == 1) {
                        $videourl = $video->stroy_s3_url;
                        if ($videourl == 'Manual Upload' || $videourl == 'http://manualupload.com') {
                            if(isset($raw_vid[0])){
                                $videourl = $raw_vid[0]->s3_url;
                            }
                        }elseif(empty($videourl)){
                            $videourl = 'https://WooGlobe.com/';
                        }
                        $ext = explode('.', $video->stroy_s3_url);
                    }elseif (isset($raw_vid[0])) {
                        $videourl = $vid[0]->portal_url;
                        if ($videourl == 'Manual Upload' || $videourl == 'http://manualupload.com') {
                            if(isset($raw_vid[0])){
                                $videourl = $raw_vid[0]->s3_url;
                            }
                        }elseif(empty($videourl)){
                            $videourl = 'https://WooGlobe.com/';
                        }
                        $ext = explode('.', $vid[0]->portal_url);
                    }
                    $description = $video->description;
                    if(!empty($video->publish_story_content)){
                        $description = $video->publish_story_content;
                    }
                    $videoTittl = $video->title;
                    if(!empty($video->publish_story_title)){
                        $videoTitle = $video->publish_story_title;
                    }

                    $html .= '<div class="video-feed-card"> 
                                       <a href="'. $videourl .'" target="_blank">
                                           <div class="video-feed-card-thumb">
                                                <div class="video-thumb"><img src="' . $thumb . '" class="img-responsive"></div>
                                           </div>
                                           <div class="video-feed-card-body">
                                                <div class="video-feed-content">
                                                    <h4 class="video-feed-title">' . $videoTitle . '</h4>
                                                    <div class="video-feed_time">' . date('d M, Y', strtotime($video->created_at)) . '</div>
                                                    <div class="video-feed-desc">' . $description . '</div>
                                                    <div class="video-feed-tags">' . $video->tags . '</div>
                                                </div>
                                           </div>
                                       </a>
                              </div> ';
                }
            }
            $html .= '<a class="video-feed-download" href="'.base_url().'mrss_story/download/'.$p.'/'.$url.'">Download Xml File</a></div></div>';
            $xml_file = '';
            echo $html.$xml_file;

        }
        else {
            echo 'Invalid URL';
            exit;
        }

    }
    public function mrss_partner($partner,$url) {
        $query_category = "SELECT cm.id, partner_id
            FROM mrss_feeds cm
            WHERE cm.status = 1
            AND cm.deleted = 0
            AND cm.url = '".$partner."/".$url."'";
        $category = $this->db->query($query_category);
        
        if($category->num_rows() > 0) {
            $category = $category->row();
            $partner_id = $category->partner_id;
            $query = 'SELECT v.*,vl.unique_key,ev.portal_url,ev.portal_thumb,vl.created_at,fd.feed_id,wv.s3_url w_url
                FROM videos v
                INNER JOIN video_leads vl
                ON v.lead_id = vl.id
                INNER JOIN edited_video ev
                ON ev.video_id = v.id
                INNER JOIN feed_video fd ON v.id = fd.video_id
                LEFT JOIN watermark_videos wv ON v.id = wv.video_id
                WHERE v.status = 1
                AND v.deleted = 0
                AND v.is_wooglobe_video = 1
                AND v.mrss = 1
                and fd.feed_id='.$category->id.'
                ORDER BY v.created_at DESC
            ';
            $videos = $this->db->query($query);

            $title = "WooGlobe Video Feed";
            $link = $this->data['url'];
            $description = "Description of the WooGlobe Video Feed";
            // This is the language you display for this podcast.
            $lang = "en-us";
            // This is the copyright information.
            $copyright = "Copyright 2019 WooGlobe";
            $builddate = date(DATE_RFC2822);

            $html = '<!DOCTYPE html>
                    <html>
                    <head>
                        <title>' . $title . '</title>
                    </head>
                    <body>
                    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
                    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
                    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
                    <style>
                    .mrss-area {
                        padding-bottom: 30px;
                    }
                        .mrss-area h1 {
                            margin-bottom: 20px;
                        }
                        .video-feed-card {
                            border: 1px solid #e9e9e9;
                            border-radius: 4px;
                            margin-bottom: 20px;
                            -webkit-transition: all 0.3s;
                            -ms-transition: all 0.3s;
                            -o-transition: all 0.3s;
                            transition: all 0.3s;
                        }
                        .video-feed-card:hover {
                            -webkit-box-shadow: 0 0 12px rgba(0,0,0,0.15);
                            -moz-box-shadow: 0 0 12px rgba(0,0,0,0.15);
                            box-shadow: 0 0 12px rgba(0,0,0,0.15);
                        }
                        .video-feed-card > a {
                            display: -ms-flexbox;
                            display: flex;
                        }
                        .video-feed-card > a:hover,
                        .video-feed-card > a:focus{
                            text-decoration: none;
                            outline: 0;
                        }
                        .video-feed-card-thumb {
                            width: 300px;
                        }
                        .video-feed-card-thumb img {
                            border-radius: 4px 0 0 4px;
                        }
                        .video-feed-card-body {
                            -ms-flex: 1;
                            flex: 1;
                        }
                        .video-feed-content {
                            padding: 15px 20px;
                        }
                        .video-feed-title {
                            color: #f5544d;
                            font-weight: 700;
                        }
                        .video-feed_time {
                            color: #999;
                        }
                        .video-feed-desc {
                            color: #4d4d4d;
                            margin-top: 15px;
                            font-size: 16px;
                        }
                        .video-feed-tags {
                            margin-top: 8px;
                            color:#999999;
                        }
                        .video-feed-download {
                            display: inline-block;
                            background: #f5544d;
                            color: #fff;
                            line-height: 41px;
                            font-weight: 600;
                            padding: 0 30px;
                            font-size: 16px;
                        }
                        .video-feed-download:hover {
                            text-decoration: none;
                            color: #ffffff;
                        }
                    
                    </style>
                    <div class="container">
                    <div class="mrss-area">
                    <h1>' . $title . '</h1>
                    ';

            if ($videos->num_rows() > 0) {
                foreach ($videos->result() as $video) {
                    $query_action = 'SELECT ac.action , ac.created_at
                            FROM action_taken ac
                            WHERE ac.lead_id = '.$video->lead_id;
                    $videos_action = $this->db->query($query_action);
                    $vid_actions=$videos_action->result();
                    $numItems = count($vid_actions);
                    $i = 0;
                    foreach ($vid_actions as $vid_ac) {
                        if($video->question_when_video_taken != '0000-00-00' ){
                            $pub_date = date(DATE_RFC2822, strtotime($video->question_when_video_taken));
                        }elseif($vid_ac->action == "Edited files uploaded"){
                            $pub_date = date(DATE_RFC2822, strtotime($vid_ac->created_at));
                        }else{
                            $pub_date = date(DATE_RFC2822, strtotime($video->created_at));
                        }
                        if(++$i === $numItems) {
                            $updated= date(DATE_RFC2822, strtotime($vid_ac->created_at));
                        }
                    }
                    $query_raw = 'SELECT rv.s3_url
                        FROM videos v
                        INNER JOIN raw_video rv
                        ON rv.video_id = v.id
                        AND rv.video_id = '.$video->id;
                    $videos_raw = $this->db->query($query_raw);
                    $raw_vid=$videos_raw->result();
                    $raw_total=count($raw_vid);

                    $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                        FROM videos v
                        INNER JOIN edited_video ev
                        ON ev.video_id = v.id
                        AND ev.video_id = '.$video->id;
                    $videos_edited = $this->db->query($query_edited);
                    $vid=$videos_edited->result();

                    $videothumb = trim($video->youtube_id);
                    $thumb='';
                    $videourl='';
                    if(isset($vid[0])){
                        $thumb=$vid[0]->portal_thumb;
                    }
                    if($thumb){
                        $thumb=$thumb;
                    }else{
                        $thumb='https://img.youtube.com/vi/'.$videothumb.'/hqdefault.jpg';
                    }
                    if ($raw_total > 1) {
                        if (isset($vid[0])) {
                            $videourl = $vid[0]->portal_url;

                            if($videourl == 'Manual Upload' || $videourl =='http://manualupload.com'){
                                if (isset($raw_vid[0])) {
                                    $videourl = $raw_vid[0]->s3_url;
                                    if(empty($videourl)){
                                        $videourl = 'http://wooglobe.com/';
                                    }
                                    $ext = explode('.', $raw_vid[0]->s3_url);
                                    $ext =$ext[count($ext) - 1];
                                    if(empty($ext)){
                                        $ext = 'mp4';
                                    }
                                }
                            }elseif(empty($videourl)){
                                $videourl = $raw_vid[0]->s3_url;
                            }
                            $ext = explode('.', $vid[0]->portal_url);
                            $ext =$ext[count($ext) - 1];
                            if(empty($ext)){
                                $ext = 'mp4';
                            }
                        }
                    } else {
                        if (isset($vid[0])) {
                            $videourl = $vid[0]->portal_url;
                            if($videourl == 'Manual Upload' || $videourl =='http://manualupload.com'){
                                if (isset($raw_vid[0])) {
                                    $videourl = $raw_vid[0]->s3_url;
                                    if(empty($videourl)){
                                        $videourl = 'http://wooglobe.com/';
                                    }
                                    $ext = explode('.', $raw_vid[0]->s3_url);
                                    $ext =$ext[count($ext) - 1];
                                    if(empty($ext)){
                                        $ext = 'mp4';
                                    }
                                }
                            }else{
                                $videourl = $vid[0]->portal_url;
                                if(empty($videourl)){
                                    $videourl = $raw_vid[0]->s3_url;
                                }
                                $ext = explode('.', $vid[0]->portal_url);
                                $ext =$ext[count($ext) - 1];
                                if(empty($ext)){
                                    $ext = 'mp4';
                                }
                            }
                        }elseif (isset($raw_vid[0])) {
                            $videourl = $raw_vid[0]->s3_url;
                            if(empty($videourl)){
                                $videourl = 'http://wooglobe.com/';
                            }
                            $ext = explode('.', $raw_vid[0]->s3_url);
                            $ext =$ext[count($ext) - 1];
                            if(empty($ext)){
                                $ext = 'mp4';
                            }
                        }
                    }
                    $slug  = $video->slug;
                    $slug =rawurlencode($slug);
                    $dataurl=$this->data['url'];
                    $dataurl =str_replace("https", "http", $dataurl);

                    $html .= '<div class="video-feed-card"> 
                                    <a href="' . $dataurl . '?video=' . $slug . '" target="_blank">
                                        <div class="video-feed-card-thumb">
                                                <div class="video-thumb"><img src="' . $thumb . '" class="img-responsive"></div>
                                        </div>
                                        <div class="video-feed-card-body">
                                                <div class="video-feed-content">
                                                    <h4 class="video-feed-title">' . $video->title . '</h4>
                                                    <div class="video-feed_time">' . date('d M, Y', strtotime($video->created_at)) . '</div>
                                                    <div class="video-feed-desc">' . $video->description . '</div>
                                                    <div class="video-feed-tags">' . $video->tags . '</div>
                                                </div>
                                        </div>
                                    </a>
                            </div> ';
                }
            }
            $html .= '<a class="video-feed-download" href="'.base_url().'mrss/mrss_partner_download/'.$partner.'/'.$url.'">Download Xml File</a></div></div>';
            $xml_file = '';
            echo $html.$xml_file;

        }
        else {
            echo 'Invalid URL';
            exit;
        }
    }
    public function mrss_partner_secure($partner,$url,$password) {
        $url=$url.'/'.$password;
        $query_category = "SELECT cm.id, partner_id
            FROM mrss_feeds cm
            WHERE cm.status = 1
            AND cm.deleted = 0
            AND cm.url = '".$partner."/".$url."'";
        $category = $this->db->query($query_category);

        if($category->num_rows() > 0) {
            $category = $category->row();
            $partner_id = $category->partner_id;
            $query = 'SELECT v.*,vl.unique_key,ev.portal_url,ev.portal_thumb,vl.created_at,fd.feed_id,wv.s3_url w_url
                FROM videos v
                INNER JOIN video_leads vl
                ON v.lead_id = vl.id
                INNER JOIN edited_video ev
                ON ev.video_id = v.id
                INNER JOIN feed_video fd ON v.id = fd.video_id
                LEFT JOIN watermark_videos wv ON v.id = wv.video_id
                WHERE v.status = 1
                AND v.deleted = 0
                AND v.is_wooglobe_video = 1
                AND v.mrss = 1
                and fd.feed_id='.$category->id.'
                ORDER BY v.created_at DESC
            ';
            $videos = $this->db->query($query);

            $title = "WooGlobe Video Feed";
            $link = $this->data['url'];
            $description = "Description of the WooGlobe Video Feed";
            // This is the language you display for this podcast.
            $lang = "en-us";
            // This is the copyright information.
            $copyright = "Copyright 2019 WooGlobe";
            $builddate = date(DATE_RFC2822);

            $html = '<!DOCTYPE html>
                    <html>
                    <head>
                        <title>' . $title . '</title>
                    </head>
                    <body>
                    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
                    <link href="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.css" rel="stylesheet">
                    <script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.js"></script>
                    <style>
                    .mrss-area {
                        padding-bottom: 30px;
                    }
                        .mrss-area h1 {
                            margin-bottom: 20px;
                        }
                        .video-feed-card {
                            border: 1px solid #e9e9e9;
                            border-radius: 4px;
                            margin-bottom: 20px;
                            -webkit-transition: all 0.3s;
                            -ms-transition: all 0.3s;
                            -o-transition: all 0.3s;
                            transition: all 0.3s;
                        }
                        .video-feed-card:hover {
                            -webkit-box-shadow: 0 0 12px rgba(0,0,0,0.15);
                            -moz-box-shadow: 0 0 12px rgba(0,0,0,0.15);
                            box-shadow: 0 0 12px rgba(0,0,0,0.15);
                        }
                        .video-feed-card > a {
                            display: -ms-flexbox;
                            display: flex;
                        }
                        .video-feed-card > a:hover,
                        .video-feed-card > a:focus{
                            text-decoration: none;
                            outline: 0;
                        }
                        .video-feed-card-thumb {
                            width: 300px;
                        }
                        .video-feed-card-thumb img {
                            border-radius: 4px 0 0 4px;
                        }
                        .video-feed-card-body {
                            -ms-flex: 1;
                            flex: 1;
                        }
                        .video-feed-content {
                            padding: 15px 20px;
                        }
                        .video-feed-title {
                            color: #f5544d;
                            font-weight: 700;
                        }
                        .video-feed_time {
                            color: #999;
                        }
                        .video-feed-desc {
                            color: #4d4d4d;
                            margin-top: 15px;
                            font-size: 16px;
                        }
                        .video-feed-tags {
                            margin-top: 8px;
                            color:#999999;
                        }
                        .video-feed-download {
                            display: inline-block;
                            background: #f5544d;
                            color: #fff;
                            line-height: 41px;
                            font-weight: 600;
                            padding: 0 30px;
                            font-size: 16px;
                        }
                        .video-feed-download:hover {
                            text-decoration: none;
                            color: #ffffff;
                        }
                    
                    </style>
                    <div class="container">
                    <div class="mrss-area">
                    <h1>' . $title . '</h1>
                    ';
            if ($videos->num_rows() > 0) {
                foreach ($videos->result() as $video) {
                    $query_raw = 'SELECT rv.s3_url
                            FROM videos v
                            INNER JOIN raw_video rv
                            ON rv.video_id = v.id
                            AND rv.video_id = '.$video->id;
                    $videos_raw = $this->db->query($query_raw);
                    $raw_vid=$videos_raw->result();
                    $raw_total=count($raw_vid);

                    $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                            FROM videos v
                            INNER JOIN edited_video ev
                            ON ev.video_id = v.id
                            AND ev.video_id = '.$video->id;
                    $videos_edited = $this->db->query($query_edited);
                    $vid=$videos_edited->result();

                    $videothumb = trim($video->youtube_id);
                    $thumb='';
                    $videourl='';
                    if(isset($vid[0])){
                        $thumb=$vid[0]->portal_thumb;
                    }
                    if($thumb){
                        $thumb=$thumb;
                    }else{
                        $thumb='https://img.youtube.com/vi/'.$videothumb.'/hqdefault.jpg';
                    }
                    if($raw_total > 1){
                        if(isset($vid[0])) {
                            $videourl = $vid[0]->portal_url;
                        }
                    }else{
                        if(isset($vid[0]) && isset($vid[0]->portal_url) ) {
                            $videourl = $vid[0]->portal_url;
                        }elseif(isset($raw_vid[0])) {
                            $videourl = $raw_vid[0]->s3_url;
                        }
                    }
                    $html .= '<div class="video-feed-card"> 
                                    <a href="' . $videourl . '" target="_blank">
                                        <div class="video-feed-card-thumb">
                                                <div class="video-thumb"><img src="' . $thumb . '" class="img-responsive"></div>
                                        </div>
                                        <div class="video-feed-card-body">
                                                <div class="video-feed-content">
                                                    <h4 class="video-feed-title">' . $video->title . '</h4>
                                                    <div class="video-feed_time">' . date('d M, Y', strtotime($video->created_at)) . '</div>
                                                    <div class="video-feed-desc">' . $video->description . '</div>
                                                    <div class="video-feed-tags">' . $video->tags . '</div>
                                                </div>
                                        </div>
                                    </a>
                            </div> ';
                }
            }
            $html .= '<a class="video-feed-download" href="'.base_url().'/mrss/mrss_partner_download/'.$partner.'/'.$url.'">Download Xml File</a></div></div>';
            $xml_file = '';
            echo $html.$xml_file;

        }
        else {
            echo 'Invalid URL';
            exit;
        }
    }
    public function mrss_download($url) {
        $limit='';
        $offset='';
        if(isset($_REQUEST['limit'])){
            $limit = $_REQUEST['limit'];
        }
        if(isset($_REQUEST['offset'])){
            $offset = $_REQUEST['offset'];
        }
        if(isset($_REQUEST['sort'])){
            $sort = $_REQUEST['sort'];
        }
        if(!empty($limit)){
            $limit_sec = "limit $offset,$limit";
        }else{
            $limit_sec ='';
        }
        if(!empty($sort)){
            $sort_sec = $sort;
        }else{
            $sort_sec ='DESC';
        }

        $query_category = "SELECT cm.*
            FROM mrss_feeds cm
            WHERE cm.status = 1
            AND cm.deleted = 0
            AND cm.url = '$url'
        ";
        $category = $this->db->query($query_category);

        if ($category->num_rows() > 0) {
            $category = $category->row();
            $query = 'SELECT v.*,vl.slug as lead_slug,vl.unique_key,vl.created_at,fd.feed_id,ac.action,ac.created_at,wv.s3_url w_url
                FROM videos v
                INNER JOIN video_leads vl
                ON v.lead_id = vl.id
                INNER JOIN feed_video fd ON v.id = fd.video_id
                LEFT JOIN action_taken ac ON v.lead_id = ac.lead_id
                LEFT JOIN watermark_videos wv ON v.id = wv.video_id 
                WHERE v.status = 1
                AND v.deleted = 0
                AND v.is_wooglobe_video = 1
                AND v.mrss = 1
                AND fd.feed_id = ' . $category->id . '
                and ac.action = "Edited files uploaded"
                group by v.id
                ORDER BY ac.created_at '.$sort_sec.' '.$limit_sec.'
            ';
            $videos = $this->db->query($query);

            $title = "WooGlobe Video Feed";
            $link = $this->data['url'];
            $description = "Description of the WooGlobe Video Feed";
            // This is the language you display for this podcast.
            $lang = "en-us";
            // This is the copyright information.
            $copyright = "Copyright 2019 WooGlobe";
            $builddate = date(DATE_RFC2822);
            header("Content-type: text/xml");
            $ref_url= 'https://wgv.wooglobe.com/mrss/download/'.$url;

            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                <rss xmlns:media="http://search.yahoo.com/mrss/" version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:georss="http://www.georss.org/georss"
                xmlns:gml="http://www.opengis.net/gml" >
                <channel>
                <atom:link href="'.htmlspecialchars($ref_url).'" rel="self" type="application/rss+xml" />
                    <title><![CDATA[' . htmlspecialchars($title) . ']]></title>
                    <link>' . htmlspecialchars($link) . '</link>
                    <description><![CDATA[' . htmlspecialchars($description) . ']]></description>
                    <language>' . htmlspecialchars($lang) . '</language>
                    <copyright>' . htmlspecialchars($copyright) . '</copyright>
                    <lastBuildDate>' . htmlspecialchars($builddate) . '</lastBuildDate>';

            if ($videos->num_rows() > 0) {
                foreach ($videos->result() as $video) {
                    $query_action = 'SELECT ac.action , ac.created_at
                            FROM action_taken ac
                            WHERE ac.lead_id = '.$video->lead_id;
                    $videos_action = $this->db->query($query_action);
                    $vid_actions=$videos_action->result();
                    $numItems = count($vid_actions);
                    $i = 0;
                    foreach ($vid_actions as $vid_ac){
                        if($video->question_when_video_taken != '0000-00-00' ){
                            $pub_date = date(DATE_RFC2822, strtotime($video->question_when_video_taken));
                        }elseif($vid_ac->action == "Edited files uploaded"){
                            $pub_date = date(DATE_RFC2822, strtotime($vid_ac->created_at));
                        }else{
                            $pub_date = date(DATE_RFC2822, strtotime($video->created_at));
                        }
                        if(++$i === $numItems) {
                            $updated= date(DATE_RFC2822, strtotime($vid_ac->created_at));
                        }
                    }
                    $cat= $video->category_id;

                    $feed_cat_title='';
                        if( strpos($cat, ',') !== false ) {
                            $farr=explode(",",$cat);
                            foreach ($farr as $far){
                                $verify_gerenal_id = $this->home->getGeneralMRSSCatNameby($far);
                                $verify_gerenal_res =$verify_gerenal_id->result();
                                if(isset($verify_gerenal_res[0])){
                                    $feed_cat_title .=$verify_gerenal_res[0]->title.', ';
                                }
                            }
                            $feed_cat_title =substr(trim($feed_cat_title), 0, -1);
                        }else{
                            $verify_gerenal_id = $this->home->getGeneralMRSSCatNameby($cat);
                            $verify_gerenal_res =$verify_gerenal_id->result();
                            if(isset($verify_gerenal_res[0])){
                                $feed_cat_title .=$verify_gerenal_res[0]->title;
                            }
                        }
                    if(empty($feed_cat_title)){
                        $feed_cat_title='wooglobe';
                    }
                    $ext='';
                    $query_raw = 'SELECT rv.s3_url
                            FROM videos v
                            INNER JOIN raw_video rv
                            ON rv.video_id = v.id
                            AND rv.video_id = ' . $video->id;
                    $videos_raw = $this->db->query($query_raw);
                    $raw_vid = $videos_raw->result();
                    $raw_total = count($raw_vid);

                    $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                            FROM videos v
                            INNER JOIN edited_video ev
                            ON ev.video_id = v.id
                            AND ev.video_id = ' . $video->id;
                    $videos_edited = $this->db->query($query_edited);
                    $vid = $videos_edited->result();

                    $videothumb = trim($video->youtube_id);
                    $thumb = '';
                    $videourl = 'https://WooGlobe.com/';
                    if (isset($vid[0])) {
                        $thumb = $vid[0]->portal_thumb;
                    }
                    if ($thumb) {
                        $thumb = $thumb;
                    } else {
                        $thumb = 'https://img.youtube.com/vi/' . $videothumb . '/hqdefault.jpg';
                    }
                    if ($raw_total > 1) {
                        if(isset($vid[0])) {
                            $videourl = $vid[0]->portal_url;
                            if ($videourl == 'Manual Upload' || $videourl == 'http://manualupload.com') {
                                $videourl = 'yes';
                                if (isset($raw_vid[0])) {
                                    $videourl = $raw_vid[0]->s3_url;
                                    if (empty($videourl)) {
                                        $videourl = 'http://wooglobe.com/';
                                    }
                                    $ext = explode('.', $raw_vid[0]->s3_url);
                                    $ext = $ext[count($ext) - 1];
                                    if (empty($ext)) {
                                        $ext = 'mp4';
                                    }
                                }
                            } else {
                                $videourl = 'no';
                            $videourl = $vid[0]->portal_url;
                                if (empty($videourl)) {
                                    $videourl = 'http://wooglobe.com/';
                                }
                                $ext = explode('.', $vid[0]->portal_url);
                                $ext = $ext[count($ext) - 1];
                                if (empty($ext)) {
                                    $ext = 'mp4';
                                }
                            }
                        }else{
                            if (isset($raw_vid[0])) {
                                $videourl = $raw_vid[0]->s3_url;
                                if (empty($videourl)) {
                                    $videourl = 'http://wooglobe.com/';
                                }
                                $ext = explode('.', $raw_vid[0]->s3_url);
                                $ext = $ext[count($ext) - 1];
                                if (empty($ext)) {
                                    $ext = 'mp4';
                                }
                            }
                        }
                    } else {
                        if (isset($vid[0])) {
                            $videourl = $vid[0]->portal_url;
                            if ($videourl == 'Manual Upload' || $videourl == 'http://manualupload.com') {
                                if(isset($raw_vid[0])){
                                    $videourl = $raw_vid[0]->s3_url;
                                }
                            }elseif(empty($videourl)){
                                $videourl = 'https://WooGlobe.com/';
                            }
                            $ext = explode('.', $vid[0]->portal_url);
                        }elseif (isset($raw_vid[0])) {
                            $videourl = $raw_vid[0]->s3_url;
                            if(empty($videourl)){
                                $videourl = 'https://WooGlobe.com/';
                            }
                            $ext = explode('.', $raw_vid[0]->s3_url);
                        }
                    }

                    $slug  = $video->lead_slug;
                    $slug =rawurlencode($slug);
                    $dataurl=$this->data['url'];
                    $dataurl =str_replace("https", "http", $dataurl);
                    $unique_key=$video->unique_key;
                    $videourl  = str_replace(" ", "%20", $videourl);
                    $videourl =str_replace("https", "http", $videourl);
                    if(strpos($videourl, "http") === false){
                        $videourl='http://'.$videourl;
                    }
                    $dataurl =str_replace("https", "http", $dataurl);
                    $videourl = preg_replace('/\s+/', ' ',$videourl);
                    $videotitle =str_replace('&','',$video->title);
                    $videotitle =str_replace('<','',$videotitle);
                    $videotitle = preg_replace('/[^A-Za-z0-9\,\ ]/', '', $videotitle);
                    $videotags =str_replace('&','',$video->tags);
                    $videotags =str_replace('<','',$videotags);
                    $videotags = preg_replace('/[^A-Za-z0-9\,\ ]/', '', $videotags);
                    $videodescription =str_replace('&','',$video->description);
                    $videodescription =str_replace('<','',$videodescription);
                    $videodescription = preg_replace('/[^A-Za-z0-9\-\ ]/', '', $videodescription);
                    $location = $video->question_video_taken;

                    $xml .= '<item>
                            <title><![CDATA[' . htmlspecialchars($videotitle) . ']]></title>
                            <link>' . htmlspecialchars($dataurl) . '?video=' . htmlspecialchars($slug) . '</link>
                            <guid isPermaLink="false">' . htmlspecialchars($unique_key) . '</guid>
                            <description><![CDATA[' . htmlspecialchars($videodescription)  . ']]></description>
                            <category><![CDATA[' . htmlspecialchars($feed_cat_title) . ']]></category>
                            <pubDate>' . htmlspecialchars($pub_date) . '</pubDate>
                            <enclosure url="' . htmlspecialchars($videourl) . '" length="' . strlen($videourl) . '" type="video/mp4"></enclosure>
                            <media:content type="video/mp4" url="' . htmlspecialchars($videourl) . '">
                                <media:keywords><![CDATA[' . htmlspecialchars($videotags) . ']]></media:keywords>
                                <media:thumbnail url="' . htmlspecialchars($thumb) . '" />
                                <media:credit role="producer" scheme="urn:ebu"><![CDATA[ WooGlobe ]]></media:credit>
                            </media:content>
                        <media:location description="'.htmlspecialchars($location).'" start="00:01" end="01:00">
                        </media:location>
                            </item>';
                }
                $xml .= '</channel></rss>';
                $name = 'mrss.xml';
                echo $xml;
                exit;
            }
            else {
                echo 'Invalid URL';
                exit;
            }
        }
    }
    public function mrss_download_story($p,$url) {
        $limit='';
        $offset='';
        if(isset($_REQUEST['limit'])){
            $limit = $_REQUEST['limit'];
        }
        if(isset($_REQUEST['offset'])){
            $offset = $_REQUEST['offset'];
        }
        if(isset($_REQUEST['sort'])){
            $sort = $_REQUEST['sort'];
        }
        if(!empty($limit)){
            $limit_sec = "limit $offset,$limit";
        }else{
            $limit_sec ='';
        }
        if(!empty($sort)){
            $sort_sec = $sort;
        }else{
            $sort_sec ='DESC';
        }

        $query_category = "SELECT cm.*
            FROM mrss_feeds cm
            WHERE cm.status = 1
            AND cm.deleted = 0
            AND cm.url = '$p"."/"."$url'
        ";
        $category = $this->db->query($query_category);

        if ($category->num_rows() > 0) {
            $category = $category->row();
            $query = 'SELECT v.*,vl.slug as lead_slug,vl.unique_key,vl.created_at,fd.feed_id,ac.action,ac.created_at,vl.is_story_content,vl.stroy_s3_url,fd.categories,fd.publish_story_content,us.full_name, ss.first_name AS filmed_by_first, ss.last_name AS filmed_by_last
                FROM video_leads vl
                INNER JOIN videos v
                ON v.lead_id = vl.id
                INNER JOIN feed_video_story fd ON vl.id = fd.lead_id
                LEFT JOIN action_taken ac ON v.lead_id = ac.lead_id
                LEFT JOIN users us ON us.id = v.user_id
                LEFT JOIN second_signer ss ON vl.unique_key = ss.uid
                WHERE vl.is_story_content = 1
                AND v.status = 1
                AND v.deleted = 0
                AND v.is_wooglobe_video = 1
                AND fd.feed_id = ' . $category->id . '
                group by v.id
                ORDER BY ac.created_at '.$sort_sec.' '.$limit_sec.'
            ';
            $videos = $this->db->query($query);

            $query = 
            "SELECT feed_id, name, location, filmed_on, wgid, license_signature, wooglobe_signature
            FROM mrss_info";
            $res = $this->db->query($query);
            $feed_decription_info = db_result_to_array_map($res->result_array());

            $title = "WooGlobe Video Feed";
            $link = $this->data['url'];
            $description = "Description of the WooGlobe Video Feed";
            // This is the language you display for this podcast.
            $lang = "en-us";
            // This is the copyright information.
            $copyright = "Copyright 2019 WooGlobe";
            $builddate = date(DATE_RFC2822);
            header("Content-type: text/xml");
            $ref_url= 'https://wgv.wooglobe.com/mrss/download/'.$p.'/'.$url;

            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                <rss xmlns:media="http://search.yahoo.com/mrss/" version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:georss="http://www.georss.org/georss"
                xmlns:gml="http://www.opengis.net/gml" >
                <channel>
                <atom:link href="'.htmlspecialchars($ref_url).'" rel="self" type="application/rss+xml" />
                    <title><![CDATA[' . htmlspecialchars($title) . ']]></title>
                    <link>' . htmlspecialchars($link) . '</link>
                    <description><![CDATA[' . htmlspecialchars($description) . ']]></description>
                    <language>' . htmlspecialchars($lang) . '</language>
                    <copyright>' . htmlspecialchars($copyright) . '</copyright>
                    <lastBuildDate>' . htmlspecialchars($builddate) . '</lastBuildDate>';

            if ($videos->num_rows() > 0) {
                foreach ($videos->result() as $video) {
                    $query_action = 'SELECT ac.action , ac.created_at
                            FROM action_taken ac
                            WHERE ac.lead_id = '.$video->lead_id;
                    $videos_action = $this->db->query($query_action);
                    $vid_actions=$videos_action->result();
                    $numItems = count($vid_actions);
                    $i = 0;
                    foreach ($vid_actions as $vid_ac){
                        if($video->question_when_video_taken != '0000-00-00' ){
                            $pub_date = date(DATE_RFC2822, strtotime($video->question_when_video_taken));
                        }elseif($vid_ac->action == "Edited files uploaded"){
                            $pub_date = date(DATE_RFC2822, strtotime($vid_ac->created_at));
                        }else{
                            $pub_date = date(DATE_RFC2822, strtotime($video->created_at));
                        }
                        if(++$i === $numItems) {
                            $updated= date(DATE_RFC2822, strtotime($vid_ac->created_at));
                        }
                    }
                    $cat= $video->categories;

                    $feed_cat_title='';
                    if( strpos($cat, ',') !== false ) {
                        $farr=explode(",",$cat);
                        foreach ($farr as $far){
                            $verify_gerenal_id = $this->home->getGeneralMRSSCatNameby($far);
                            $verify_gerenal_res =$verify_gerenal_id->result();
                            if(isset($verify_gerenal_res[0])){
                                $feed_cat_title .=$verify_gerenal_res[0]->title.', ';
                            }
                        }
                        $feed_cat_title =substr(trim($feed_cat_title), 0, -1);
                    }else{
                        $verify_gerenal_id = $this->home->getGeneralMRSSCatNameby($cat);
                        if($verify_gerenal_id){
                            $verify_gerenal_res =$verify_gerenal_id->result();
                            if(isset($verify_gerenal_res[0])){
                                $feed_cat_title .=$verify_gerenal_res[0]->title;
                            }
                        }
                    }
                    if(empty($feed_cat_title)){
                        $feed_cat_title='wooglobe';
                    }
                    $ext='';

                    $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                            FROM videos v
                            INNER JOIN edited_video ev
                            ON ev.video_id = v.id
                            AND ev.video_id = ' . $video->id;
                    $videos_edited = $this->db->query($query_edited);
                    $vid = $videos_edited->result();
                    $videothumb = trim($video->youtube_id);
                    $thumb = '';
                    $videourl = 'https://WooGlobe.com/';
                    if(!empty($video->s3_url_story_thumb)){
                        $thumb = $video->s3_url_story_thumb;
                    }else if(isset($vid[0])){
                        $thumb=$vid[0]->portal_thumb;
                    }
                    if ($thumb) {
                        $thumb = $thumb;
                    } else {
                        $thumb = 'https://img.youtube.com/vi/' . $videothumb . '/hqdefault.jpg';
                    }
                    if ($video->is_story_content == 1) {
                        $videourl = $video->stroy_s3_url;
                        if ($videourl == 'Manual Upload' || $videourl == 'http://manualupload.com') {
                            if(isset($raw_vid[0])){
                                $videourl = $raw_vid[0]->s3_url;
                            }
                        }elseif(empty($videourl)){
                            $videourl = 'https://WooGlobe.com/';
                        }
                        $ext = explode('.', $video->stroy_s3_url);
                    }elseif (isset($vid[0])) {
                        $videourl = $vid[0]->portal_url;
                        if ($videourl == 'Manual Upload' || $videourl == 'http://manualupload.com') {
                            if(isset($vid[0])){
                                $videourl = $vid[0]->s3_url;
                            }
                        }elseif(empty($videourl)){
                            $videourl = 'https://WooGlobe.com/';
                        }
                        $ext = explode('.', $vid[0]->portal_url);
                    }

                    $slug  = $video->lead_slug;
                    $slug =rawurlencode($slug);
                    $dataurl=$this->data['url'];
                    $dataurl =str_replace("https", "http", $dataurl);
                    $unique_key=$video->unique_key;
                    $videourl  = str_replace(" ", "%20", $videourl);
                    $videourl =str_replace("https", "http", $videourl);
                    if(strpos($videourl, "http") === false){
                        $videourl='http://'.$videourl;
                    }
                    $dataurl =str_replace("https", "http", $dataurl);
                    $videourl = preg_replace('/\s+/', ' ',$videourl);
                    $videotitle =str_replace('&','',$video->title);
                    $videotitle =str_replace('<','',$videotitle);
                    $videotitle = preg_replace('/[^A-Za-z0-9\,\ ]/', '', $videotitle);
                    if(!empty($video->publish_story_title)){
                        $videotitle =str_replace('&','',$video->publish_story_title);
                        $videotitle =str_replace('<','',$videotitle);
                        $videotitle = preg_replace('/[^A-Za-z0-9\,\ ]/', '', $videotitle);
                    }
                    $videotags =str_replace('&','',$video->tags);
                    $videotags =str_replace('<','',$videotags);
                    $videotags = preg_replace('/[^A-Za-z0-9\,\ ]/', '', $videotags);
                    $videodescription =str_replace('&','',$video->description);
                    if(!empty($video->publish_story_content)){
                        $videodescription =str_replace('&','',$video->description);
                    }
                    $videodescription =str_replace('<','',$videodescription);
                    $videodescription = preg_replace('/[^A-Za-z0-9\-\ \:\n]/', '', $videodescription);
                    $location = $video->question_video_taken;
                    $filmed_on = $video->question_when_video_taken;
                    $filmed_by = $video->full_name;
                    if ($video->filmed_by_first){
                        $filmed_by = $video->filmed_by_first." ".$video->filmed_by_last;
                    }

                    if(array_key_exists($video->feed_id, $feed_decription_info)){
                        if($feed_decription_info[$video->feed_id]["name"]){
                            if(strpos($videodescription, "Name: ") === false)
                                $videodescription .= " Name: ".$filmed_by;
                        }
                        if($feed_decription_info[$video->feed_id]["location"]){
                            if(strpos($videodescription, "Location: ") === false)
                                $videodescription .= " Location: ".$location;
                        }
                        if($feed_decription_info[$video->feed_id]["filmed_on"]){
                            $videodescription .= " Filmed on: ".$filmed_on;
                        }
                        if($feed_decription_info[$video->feed_id]["wgid"]){
                            $videodescription .= " WGID: ".$unique_key;
                        }
                        if($feed_decription_info[$video->feed_id]["license_signature"]){
                            $videodescription .= " Licensing Signature: "."For licensing and to use this video, please email licensing@wooglobe.com";
                        }
                        if($feed_decription_info[$video->feed_id]["wooglobe_signature"]){
                            $videodescription .= "
                            Twitter : https://twitter.com/WooGlobe 
                            Facebook : https://fb.com/Wooglobe 
                            Instagram : https://www.instagram.com/wooglobe";
                        }
                    }

                    $xml .= '<item>
                            <title><![CDATA[' . htmlspecialchars($videotitle) . ']]></title>
                            <link>' . htmlspecialchars($dataurl) . '?video=' . htmlspecialchars($slug) . '</link>
                            <guid isPermaLink="false">' . htmlspecialchars($unique_key) . '</guid>
                            <description><![CDATA[' . htmlspecialchars($videodescription)  . ']]></description>
                            <category><![CDATA[' . htmlspecialchars($feed_cat_title) . ']]></category>
                            <pubDate>' . htmlspecialchars($pub_date) . '</pubDate>
                            <enclosure url="' . htmlspecialchars($videourl) . '" length="' . strlen($videourl) . '" type="video/mp4"></enclosure>
                            <media:content type="video/mp4" url="' . htmlspecialchars($videourl) . '">
                                <media:keywords><![CDATA[' . htmlspecialchars($videotags) . ']]></media:keywords>
                                <media:thumbnail url="' . htmlspecialchars($thumb) . '" />
                                <media:credit role="producer" scheme="urn:ebu"><![CDATA[ WooGlobe ]]></media:credit>
                            </media:content>
                        <media:location description="'.htmlspecialchars($location).'" start="00:01" end="01:00">
                        </media:location>
                            </item>';
                }


                $xml .= '</channel></rss>';
                $name = 'mrss.xml';
                /*  $this->load->helper('download');
                force_download($name, $xml);*/
                /*<bc:videoid>'.$video->unique_key.'</bc:videoid>
                <bc:duration>'.@$file_data['playtime_string'].'</bc:duration>*/
                echo $xml;
                exit;
            } else {
                echo 'Invalid URL';
                exit;
            }

        }
    }
    public function brands_mrss_download($partner, $brand) {
        $query = '
            SELECT * 
            FROM brands_mrss_feeds bmf
            WHERE bmf.url = "'.$partner . "/" . $brand.'"
            LIMIT 1
        ';
        $result = $this->db->query($query);

        if($result->num_rows() > 0){
            $result = $result->row();
            $partner_id = $result->partner_id;
            $brand_id = $result->brand_id;
            $brand_name = $this->home->getBrandName($brand_id);
            $title = "WooGlobe Video Feed";
            $link = "https://www.wooglobe.com";
            $description = "Description of the WooGlobe Video Feed";
            $lang = "en-us";
            $copyright = "Copyright 2023 WooGlobe";
            $builddate = date(DATE_RFC2822);
            header("Content-type: text/xml");
            $ref_url= 'https://wgv.wooglobe.com/brands_mrss/'.$partner . "/" . $brand;

            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                <rss xmlns:media="http://search.yahoo.com/mrss/" version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:georss="http://www.georss.org/georss"
                xmlns:gml="http://www.opengis.net/gml">
                <channel>
                <atom:link href="'.htmlspecialchars($ref_url).'" rel="self" type="application/rss+xml"  />
                    <title><![CDATA[' . htmlspecialchars($title) . ']]></title>
                    <link>' . htmlspecialchars($link) . '</link>
                    <description><![CDATA[' . htmlspecialchars($description) . ']]></description>
                    <language>' . htmlspecialchars($lang) . '</language>
                    <copyright>' . htmlspecialchars($copyright) . '</copyright>
                    <lastBuildDate>' . htmlspecialchars($builddate) . '</lastBuildDate>';

            $query = '
                SELECT * 
                FROM mrss_brand_feeds mbf
                WHERE mbf.partner_id = "'.$partner_id.'"
                AND mbf.brand_id = "'.$brand_id.'"
                AND mbf.published = 1
                ORDER BY publication_date DESC
            ';
        
            $result = $this->db->query($query)->result();
            
            foreach($result as $row){
                $lead_id = $row->lead_id;
                $lead = $this->home->getLeadByLeadId($lead_id);
                $videotitle = $row->story_title;
                $dataurl = "";
                $unique_key = $lead->unique_key;
                $videodescription = $row->story_description;
                $feed_cat_title = $brand_name; // brand ?
                $pub_date = $row->publication_date;
                $videourl = $row->story_video_s3_url;
                $ext = "";
                $videotags = $row->story_tags;
                $thumb = $row->story_thumb_s3_url;
                $location = $lead->question_video_taken; // from lead

                $xml .= '<item>
                        <title><![CDATA[' . htmlspecialchars($videotitle) . ']]></title>
                        <link>' . htmlspecialchars($dataurl) . '?video=' . htmlspecialchars($slug) . '</link>
                        <guid  isPermaLink="false">' . htmlspecialchars($unique_key) . '</guid>
                        <description><![CDATA[' . htmlspecialchars($videodescription) . ']]></description>
                        <category><![CDATA[' . htmlspecialchars($feed_cat_title) . ']]></category>
                        <pubDate>' . htmlspecialchars($pub_date) . '</pubDate>
                        <enclosure url="' . htmlspecialchars($videourl) . '" length="'.strlen($videourl).'" type="video/mp4"></enclosure>
                        <media:content type="video/' . htmlspecialchars($ext) . '" url="' . htmlspecialchars($videourl) . '">
                            <media:keywords><![CDATA[' . htmlspecialchars($videotags) . ']]></media:keywords>
                            <media:thumbnail url="' . htmlspecialchars($thumb) . '" />';
                
                $xml .='<media:credit role="producer" scheme="urn:ebu"><![CDATA[ WooGlobe ]]></media:credit>
                </media:content>
                <media:location description="'.htmlspecialchars($location).'" start="00:01" end="01:00">
                </media:location>
                </item>';
            }

            $xml .= '</channel></rss>';
            $name = 'mrss.xml';
            echo $xml;
            exit;
        }
        else {
            echo "Invalid URL read"; exit;
        }

    }
    public function mrss_partner_download($url,$partner) {
        $limit='';
        $offset='';
        if(isset($_REQUEST['limit'])) {
            $limit = $_REQUEST['limit'];
        }
        if(isset($_REQUEST['offset'])) {
            $offset = $_REQUEST['offset'];
        }
        if(isset($_REQUEST['sort'])) {
            $sort = $_REQUEST['sort'];
        }
        if(!empty($limit)) {
            $limit_sec = "limit $offset,$limit";
        }
        else {
            $limit_sec ='';
        }
        if(!empty($sort)) {
            $sort_sec = $sort;
        }
        else {
            $sort_sec ='DESC';
        }

        $query_category = "SELECT cm.id,u.watermark,u.appearance,cm.partner_id,cm.pub_date
                FROM mrss_feeds cm
                LEFT JOIN users u ON u.id = cm.partner_id
                WHERE cm.status = 1
                AND cm.deleted = 0
                AND cm.url = '".$url."/".$partner."'";
        $category = $this->db->query($query_category);

        if($category->num_rows() > 0) {
            $category = $category->row();
            $query = 'SELECT v.*,vl.video_title_2,vl.unique_key,ev.portal_url,ev.portal_thumb,vl.created_at,fd.feed_id,ac.action,ac.created_at,wv.s3_url w_url,mp.publication_date,v.watermark,us.full_name, ss.first_name AS filmed_by_first, ss.last_name AS filmed_by_last
                FROM videos v
                INNER JOIN video_leads vl
                ON v.lead_id = vl.id
                INNER JOIN edited_video ev
                ON ev.video_id = v.id
                INNER JOIN feed_video fd ON v.id = fd.video_id
                LEFT JOIN users us ON us.id = v.user_id
                LEFT JOIN second_signer ss ON vl.unique_key = ss.uid
                LEFT JOIN action_taken ac ON v.lead_id = ac.lead_id
                LEFT JOIN watermark_videos wv ON v.id = wv.video_id AND v.watermark=1
                INNER JOIN mrss_publication mp ON v.id = mp.video_id AND mp.feed_id = '.$category->id.'
                WHERE v.status = 1
                AND v.deleted = 0
                AND v.is_wooglobe_video = 1
                AND v.mrss = 1
                AND fd.feed_id='.$category->id.'
                AND ac.action = "Edited files uploaded"
                group by v.id
                ORDER BY mp.publication_date '.$sort_sec.' '.$limit_sec.'
            ';
            $videos = $this->db->query($query);

            $query = 
                "SELECT feed_id, name, location, filmed_on, wgid, license_signature, wooglobe_signature, is_title_2
                FROM mrss_info";
            $res = $this->db->query($query);

            $feed_decription_info = db_result_to_array_map($res->result_array());
            $title = "WooGlobe Video Feed";
            $link = $this->data['url'];
            $description = "Description of the WooGlobe Video Feed";
            // This is the language you display for this podcast.
            $lang = "en-us";
            // This is the copyright information.
            $copyright = "Copyright 2019 WooGlobe";
            $builddate = date(DATE_RFC2822);
            header("Content-type: text/xml");
            $ref_url= 'https://wgv.wooglobe.com/mrss/mrss_partner_download/'.$url.'/'.$partner;

            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                <rss xmlns:media="http://search.yahoo.com/mrss/" version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:georss="http://www.georss.org/georss"
                xmlns:gml="http://www.opengis.net/gml">
                <channel>
                <atom:link href="'.htmlspecialchars($ref_url).'" rel="self" type="application/rss+xml"  />
                    <title><![CDATA[' . htmlspecialchars($title) . ']]></title>
                    <link>' . htmlspecialchars($link) . '</link>
                    <description><![CDATA[' . htmlspecialchars($description) . ']]></description>
                    <language>' . htmlspecialchars($lang) . '</language>
                    <copyright>' . htmlspecialchars($copyright) . '</copyright>
                    <lastBuildDate>' . htmlspecialchars($builddate) . '</lastBuildDate>';

            $videos_array = array();

            if ($videos->num_rows() > 0) {
                foreach ($videos->result() as $i => $video) {
                    $query_action = 'SELECT ac.action , ac.created_at
                            FROM action_taken ac
                            WHERE ac.lead_id = '.$video->lead_id;
                    $videos_action = $this->db->query($query_action);
                    $vid_actions=$videos_action->result();
                    $numItems = count($vid_actions);
                    $pub_date = "";
                    if(!empty($category->pub_date)) {
                        if($category->pub_date == "publish_date" || $category->pub_date == "queue_to_mrss") {
                            if(!empty($video->publication_date)){
                                $pub_date = date(DATE_RFC2822, strtotime($video->publication_date));
                            }
                            else {
                                foreach ($vid_actions as $vid_ac) {
                                    if($video->question_when_video_taken != '0000-00-00' ) {
                                        $pub_date = date(DATE_RFC2822, strtotime($video->question_when_video_taken));
                                    }
                                    else if($vid_ac->action == "Edited files uploaded") {
                                        $pub_date = date(DATE_RFC2822, strtotime($vid_ac->created_at));
                                    }
                                    else {
                                        $pub_date = date(DATE_RFC2822, strtotime($video->created_at));
                                    }
                                    if(++$i === $numItems) {
                                        $updated= date(DATE_RFC2822, strtotime($vid_ac->created_at));
                                    }
                                }
                            }
                        }
                        else {
                            foreach ($vid_actions as $vid_ac) {
                                if($category->pub_date == "question_when_video_taken" && $video->question_when_video_taken != '0000-00-00' ) {
                                    $pub_date = date(DATE_RFC2822, strtotime($video->question_when_video_taken));
                                }
                                else if($vid_ac->action == "Edited files uploaded") {
                                    $pub_date = date(DATE_RFC2822, strtotime($vid_ac->created_at));
                                }
                                else {
                                    $pub_date = date(DATE_RFC2822, strtotime($video->created_at));
                                }
                                if(++$i === $numItems) {
                                    $updated= date(DATE_RFC2822, strtotime($vid_ac->created_at));
                                }
                            }
                        }
                    }
                    else {
                        foreach ($vid_actions as $vid_ac) {
                            if($video->question_when_video_taken != '0000-00-00' ) {
                                $pub_date = date(DATE_RFC2822, strtotime($video->question_when_video_taken));
                            }
                            else if($vid_ac->action == "Edited files uploaded") {
                                $pub_date = date(DATE_RFC2822, strtotime($vid_ac->created_at));
                            }
                            else {
                                $pub_date = date(DATE_RFC2822, strtotime($video->created_at));
                            }
                            if(++$i === $numItems) {
                                $updated= date(DATE_RFC2822, strtotime($vid_ac->created_at));
                            }
                        }
                    }

                    $cat= $video->category_id;
                    $feed_cat_title='';

                    if($url == "reuters") {
                        $r_cat= $video->reuters_category_id;
                        if( strpos($r_cat, ',') !== false ) {
                            $farr=explode(",",$r_cat);
                            foreach ($farr as $far) {
                                $verify_gerenal_id = $this->home->getReutersCatNameby($far);
                                $verify_gerenal_res =$verify_gerenal_id->result();
                                if(isset($verify_gerenal_res[0])) {
                                    $feed_cat_title .=$verify_gerenal_res[0]->title.', ';
                                }
                            }
                            $feed_cat_title =substr(trim($feed_cat_title), 0, -1);
                        }
                        else {
                            $verify_gerenal_id = $this->home->getReutersCatNameby($r_cat);
                            $verify_gerenal_res =$verify_gerenal_id->result();
                            if(isset($verify_gerenal_res[0])){
                                $feed_cat_title .=$verify_gerenal_res[0]->title;
                            }
                        }
                        if(empty($feed_cat_title)) {
                            $feed_cat_title='ENT';
                        }
                        $pub_date = date(DATE_RFC2822, strtotime($video->publication_date));
                    }
                    else if( strpos($cat, ',') !== false ) {
                        $farr=explode(",",$cat);
                        foreach ($farr as $far){
                            $verify_gerenal_id = $this->home->getGeneralMRSSCatNameby($far);
                            $verify_gerenal_res =$verify_gerenal_id->result();
                            if(isset($verify_gerenal_res[0])) {
                                $feed_cat_title .=$verify_gerenal_res[0]->title.', ';
                            }
                        }
                        $feed_cat_title =substr(trim($feed_cat_title), 0, -1);
                    }
                    else {
                        $verify_gerenal_id = $this->home->getGeneralMRSSCatNameby($cat);
                        $verify_gerenal_res =$verify_gerenal_id->result();
                        if(isset($verify_gerenal_res[0])) {
                            $feed_cat_title .=$verify_gerenal_res[0]->title;
                        }
                    }
                    if(empty($feed_cat_title)) {
                        $feed_cat_title='wooglobe';
                    }
                    $ext='';
                    $query_raw = 'SELECT rv.s3_url
                            FROM videos v
                            INNER JOIN raw_video rv
                            ON rv.video_id = v.id
                            AND rv.video_id = ' . $video->id;
                    $videos_raw = $this->db->query($query_raw);
                    $raw_vid = $videos_raw->result();
                    $raw_total = count($raw_vid);

                    $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                            FROM videos v
                            INNER JOIN edited_video ev
                            ON ev.video_id = v.id
                            AND ev.video_id = ' . $video->id;
                    $videos_edited = $this->db->query($query_edited);
                    $vid = $videos_edited->result();

                    $videothumb = trim($video->youtube_id);
                    $thumb = '';
                    $videourl = 'http://wooglobe.com';
                    if (isset($vid[0])) {
                        $thumb = $vid[0]->portal_thumb;
                    }
                    if ($thumb) {
                        $thumb = $thumb;
                    } else {
                        $thumb = 'https://img.youtube.com/vi/' . $videothumb . '/hqdefault.jpg';
                    }
                    if($category->watermark == 2) {
                        $videourl = array();
                        if (isset($raw_vid[0])) {
                            foreach($raw_vid as $raw) {
                                $raw_data = array();
                                $ext = explode('.', $raw->s3_url);
                                $ext =$ext[count($ext) - 1];
                                if(empty($ext)) {
                                    $ext = 'mp4';
                                }
                                $raw_data['url'] = $raw->s3_url;
                                $raw_data['ext'] = $raw->s3_url;
                                $videourl[] = $raw_data;
                            }
                        }
                    }
                    else if ($raw_total > 1) {
                        if (isset($vid[0])) {
                            $videourl = $vid[0]->portal_url;
                            if($videourl == 'Manual Upload' || $videourl =='http://manualupload.com') {
                                if (isset($raw_vid[0])) {
                                    if($category->watermark == 0) {
                                        $videourl = $raw_vid[0]->s3_url;
                                    }
                                    else {
                                        if(!empty($video->w_url)) {
                                            $videourl = $video->w_url;
                                        }
                                        else {
                                            $videourl = $raw_vid[0]->s3_url;
                                        }
                                    }
                                    if(empty($videourl)) {
                                        $videourl = 'http://wooglobe.com/';
                                    }
                                    $ext = explode('.', $raw_vid[0]->s3_url);
                                    $ext =$ext[count($ext) - 1];
                                    if(empty($ext)) {
                                        $ext = 'mp4';
                                    }
                                }
                            }
                            else if(empty($videourl)) {
                                if($category->watermark == 0) {
                                    $videourl = $raw_vid[0]->s3_url;
                                }
                                else {
                                    if(!empty($video->w_url)) {
                                        $videourl = $video->w_url;
                                    }
                                    else {
                                        $videourl = $raw_vid[0]->s3_url;
                                    }
                                }
                            }
                            $ext = explode('.', $vid[0]->portal_url);
                            $ext =$ext[count($ext) - 1];
                            if(empty($ext)) {
                                $ext = 'mp4';
                            }
                        }
                    }
                    else {
                        if (isset($vid[0])) {
                            $videourl = $vid[0]->portal_url;
                            if($videourl == 'Manual Upload' || $videourl =='http://manualupload.com') {
                            if(isset($raw_vid[0])) {
                                    if($category->watermark == 0) {
                                        $videourl = $raw_vid[0]->s3_url;
                                    }
                                    else {
                                        if(!empty($video->w_url)) {
                                            $videourl = $video->w_url;
                                        }
                                        else {
                                            $videourl = $raw_vid[0]->s3_url;
                                        }
                                    }
                                    if(empty($videourl)) {
                                        $videourl = 'http://wooglobe.com/';
                                    }
                                    $ext = explode('.', $raw_vid[0]->s3_url);
                                    $ext =$ext[count($ext) - 1];
                                    if(empty($ext)) {
                                        $ext = 'mp4';
                                    }
                                }
                            }
                            else {
                                $videourl = $vid[0]->portal_url;
                                if(!empty($videourl)) {
                                    if($category->watermark == 0) {
                                        $videourl = $vid[0]->portal_url;
                                    }
                                    else {
                                        if(!empty($video->w_url)) {
                                            $videourl = $video->w_url;
                                        }
                                        else {
                                            $videourl = $vid[0]->portal_url;
                                        }
                                    }
                                }
                                if(empty($videourl)) {
                                    if($category->watermark == 0) {
                                        $videourl = $raw_vid[0]->s3_url;
                                    }
                                    else {
                                        if(!empty($video->w_url)) {
                                            $videourl = $video->w_url;
                                        }
                                        else {
                                            $videourl = $raw_vid[0]->s3_url;
                                        }
                                    }
                                }

                                $ext = explode('.', $vid[0]->portal_url);
                                $ext =$ext[count($ext) - 1];
                                if(empty($ext)) {
                                    $ext = 'mp4';
                                }
                            }
                        }
                        else if(isset($raw_vid[0])) {
                            if($category->watermark == 0) {
                                $videourl = $raw_vid[0]->s3_url;
                            }
                            else {
                                if(!empty($video->w_url)) {
                                    $videourl = $video->w_url;
                                }
                                else {
                                    $videourl = $vid[0]->s3_url;
                                }
                            }
                            if(empty($videourl)) {
                                $videourl = 'http://wooglobe.com/';
                            }
                            $ext = explode('.', $raw_vid[0]->s3_url);
                            $ext =$ext[count($ext) - 1];
                            if(empty($ext)) {
                                $ext = 'mp4';
                            }
                        }
                    }

                    $slug  = $video->slug;
                    $slug =rawurlencode($slug);
                    $dataurl=$this->data['url'];
                    $dataurl =str_replace("https", "http", $dataurl);
                    $videourl =str_replace("https", "http", $videourl);
                    if(strpos($videourl, "http") === false){
                        $videourl='http://'.$videourl;
                    }
                    if ($url == "reuters") {
                        $landscape_url = $this->home->getLandscapeConvertedUrl($video->lead_id);
                        if (isset($landscape_url) && $landscape_url != null) {
                            $videourl = $landscape_url;
                        }
                    }
                    $unique_key=$video->unique_key;
                    $videotitle = $video->title;
                    if ($feed_decription_info[$video->feed_id]["is_title_2"]) {
                        if ($video->video_title_2 != NULL && strlen($video->video_title_2) > 0) {
                            $videotitle = $video->video_title_2;
                        }
                    }
                    $videotags = $video->tags;
                    $videodescription = $video->description;
                    $location = $video->question_video_taken;
                    $filmed_on = $video->question_when_video_taken;
                    $filmed_by = $video->full_name;
                    if ($video->filmed_by_first){
                        $filmed_by = $video->filmed_by_first." ".$video->filmed_by_last;
                    }

                    if(array_key_exists($video->feed_id, $feed_decription_info)) {
                        if($feed_decription_info[$video->feed_id]["name"]) {
                            if(strpos($videodescription, "Name: ") === false)
                                $videodescription .= "\nName: ".$filmed_by;
                        }
                        if($feed_decription_info[$video->feed_id]["location"]) {
                            if(strpos($videodescription, "Location: ") === false)
                                $videodescription .= "\nLocation: ".$location;
                        }
                        if($feed_decription_info[$video->feed_id]["filmed_on"]) {
                            $videodescription .= "\n\nFilmed on: ".$filmed_on;
                        }
                        if($feed_decription_info[$video->feed_id]["wgid"]) {
                            $videodescription .= "\nWooGlobe Ref : ".$unique_key;
                        }
                        if($feed_decription_info[$video->feed_id]["license_signature"]) {
                            $videodescription .= "\nFor licensing and to use this video, please email licensing@wooglobe.com";
                        }
                        if($feed_decription_info[$video->feed_id]["wooglobe_signature"]) {
                            $videodescription .= "\nTwitter : https://twitter.com/WooGlobe\nFacebook : https://fb.com/Wooglobe\nInstagram : https://www.instagram.com/wooglobe";
                        }
                    }

                    // if ($category->watermark == 2) {
                    //     for($i = 0; $i < count($videourl); $i++) {
                    //         $xml .= '<item>';
                    //         if(count($videourl) > 1) {
                    //             $xml .= '<title><![CDATA[' . trim(htmlspecialchars($videotitle)).' - Part '.($i+1) . ']]></title>
                    //                     <link>' . htmlspecialchars($dataurl) . '?video=' . htmlspecialchars($slug) . '</link>
                    //                     <guid  isPermaLink="false">' . htmlspecialchars($unique_key).'-'.($i+1) . '</guid>';
                    //         }
                    //         else {
                    //             $xml .= '<title><![CDATA[' . htmlspecialchars($videotitle) . ']]></title>
                    //                     <link>' . htmlspecialchars($dataurl) . '?video=' . htmlspecialchars($slug) . '</link>
                    //                     <guid  isPermaLink="false">' . htmlspecialchars($unique_key) . '</guid>';
                    //         }
                    //         $xml .= '<description><![CDATA[' . htmlspecialchars($videodescription) . ']]></description>
                    //                 <category><![CDATA[' . htmlspecialchars($feed_cat_title) . ']]></category>
                    //                 <pubDate>' . htmlspecialchars($pub_date) . '</pubDate>
                    //                 <enclosure url="' . htmlspecialchars($videourl[$i][url]) . '" length="'.strlen($videourl[$i]['url']).'" type="video/mp4"></enclosure>
                    //                 <media:content type="video/' . htmlspecialchars($videourl[$i]['ext']) . '" url="' . htmlspecialchars($videourl[$i]['url']) . '">
                    //                     <media:keywords><![CDATA[' . htmlspecialchars($videotags) . ']]></media:keywords>
                    //                     <media:thumbnail url="' . htmlspecialchars($thumb) . '" />';
                            
                    //         if($category->appearance == 1) {
                    //             $appearance_query = "SELECT * FROM appreance_release WHERE uid = '$unique_key'";
                    //             $appearance_result = $this->db->query($appearance_query);
                    //             if($appearance_result->num_rows() > 0) {
                    //                 foreach ($appearance_result->result() as $i=>$app) {
                    //                     $ur = str_replace('"','',$app->help_us);
                    //                     $ur = str_replace("'",'',$ur);
                    //                     $xml .='<media:thumbnail url="DOCUMENTS_URL_' .htmlspecialchars($i). '?'.str_replace('&', '&amp;',htmlspecialchars($ur)).'" />';
                    //                 }
                    //             }
                    //         }
                            
                    //         $xml .='<media:credit role="producer" scheme="urn:ebu"><![CDATA[ WooGlobe ]]></media:credit>
                    //         </media:content>
                    //         <media:location description="'.htmlspecialchars($location).'" start="00:01" end="01:00">
                    //         </media:location>
                    //         </item>';
                    //     }
                    // }
                    if ($category->watermark == 2) {
                        $xml .= '<item>
                                    <title><![CDATA[' . htmlspecialchars($videotitle) . ']]></title>
                                    <link>' . htmlspecialchars($dataurl) . '?video=' . htmlspecialchars($slug) . '</link>
                                    <guid  isPermaLink="false">' . htmlspecialchars($unique_key) . '</guid>
                                    <description><![CDATA[' . htmlspecialchars($videodescription) . ']]></description>
                                    <category><![CDATA[' . htmlspecialchars($feed_cat_title) . ']]></category>
                                    <pubDate>' . htmlspecialchars($pub_date) . '</pubDate>';
                        for($i = 0; $i < count($videourl); $i++) {
                            $xml .= '<enclosure url="' . htmlspecialchars($videourl[$i][url]) . '" length="'.strlen($videourl[$i]['url']).'" type="video/mp4"></enclosure>
                                    <media:content type="video/' . htmlspecialchars($videourl[$i]['ext']) . '" url="' . htmlspecialchars($videourl[$i]['url']) . '">
                                        <media:keywords><![CDATA[' . htmlspecialchars($videotags) . ']]></media:keywords>
                                        <media:thumbnail url="' . htmlspecialchars($thumb) . '" />';
                            if($category->appearance == 1) {
                                $appearance_query = "SELECT * FROM appreance_release WHERE uid = '$unique_key'";
                                $appearance_result = $this->db->query($appearance_query);
                                if($appearance_result->num_rows() > 0) {
                                    foreach ($appearance_result->result() as $i=>$app) {
                                        $ur = str_replace('"','',$app->help_us);
                                        $ur = str_replace("'",'',$ur);
                                        $xml .='<media:thumbnail url="DOCUMENTS_URL_' .htmlspecialchars($i). '?'.str_replace('&', '&amp;',htmlspecialchars($ur)).'" />';
                                    }
                                }
                            }
                        
                            $xml .='<media:credit role="producer" scheme="urn:ebu"><![CDATA[ WooGlobe ]]></media:credit>
                                    </media:content>';
                        }
                        $xml .= '<media:location description="'.htmlspecialchars($location).'" start="00:01" end="01:00">
                                </media:location>
                            </item>';
                    }
                    else {
                        $xml .= '<item>
                                    <title><![CDATA[' . htmlspecialchars($videotitle) . ']]></title>
                                    <link>' . htmlspecialchars($dataurl) . '?video=' . htmlspecialchars($slug) . '</link>
                                    <guid  isPermaLink="false">' . htmlspecialchars($unique_key) . '</guid>
                                    <description><![CDATA[' . htmlspecialchars($videodescription) . ']]></description>
                                    <category><![CDATA[' . htmlspecialchars($feed_cat_title) . ']]></category>
                                    <pubDate>' . htmlspecialchars($pub_date) . '</pubDate>
                                    <enclosure url="' . htmlspecialchars($videourl) . '" length="'.strlen($videourl).'" type="video/mp4"></enclosure>
                                    <media:content type="video/' . htmlspecialchars($ext) . '" url="' . htmlspecialchars($videourl) . '">
                                        <media:keywords><![CDATA[' . htmlspecialchars($videotags) . ']]></media:keywords>
                                        <media:thumbnail url="' . htmlspecialchars($thumb) . '" />';
                                
                        if($category->appearance == 1) {
                            $appearance_query = "SELECT * FROM appreance_release WHERE uid = '$unique_key'";
                            $appearance_result = $this->db->query($appearance_query);
                            if($appearance_result->num_rows() > 0) {
                                foreach ($appearance_result->result() as $i=>$app) {
                                    $ur = str_replace('"','',$app->help_us);
                                    $ur = str_replace("'",'',$ur);
                                    $xml .='<media:thumbnail url="DOCUMENTS_URL_' .htmlspecialchars($i). '?'.str_replace('&', '&amp;',htmlspecialchars($ur)).'" />';
                                }
                            }
                        }

                        $xml .='<media:credit role="producer" scheme="urn:ebu"><![CDATA[ WooGlobe ]]></media:credit>
                        </media:content>
                        <media:location description="'.htmlspecialchars($location).'" start="00:01" end="01:00">
                        </media:location>
                        </item>';
                    }
                }
            }
            $xml .= '</channel></rss>';
            $name = 'mrss.xml';
            echo $xml;
            exit;
        }
        else {
            echo 'Invalid URL read';
            exit;
        }

    }
    public function mrss_partner_secure_download($url,$partner,$password) {
        $limit='';
        $offset='';
        if(isset($_REQUEST['limit'])){
            $limit = $_REQUEST['limit'];
        }
        if(isset($_REQUEST['offset'])){
            $offset = $_REQUEST['offset'];
        }
        if(!empty($limit)){
            $limit_sec = "limit $offset,$limit";
        }else{
            $limit_sec ='';
        }

        $query_category = "SELECT cm.id,u.watermark
                FROM mrss_feeds cm
                LEFT JOIN users u ON u.id = cm.partner_id
                WHERE cm.status = 1
                AND cm.deleted = 0
                AND cm.url = '".$url."/".$partner."/".$password."'";
        $category = $this->db->query($query_category);

        if($category->num_rows() > 0) {
            $category = $category->row();
            $query = 'SELECT v.*,vl.slug as lead_slug,vl.unique_key,ev.portal_url,ev.portal_thumb,vl.created_at,fd.feed_id,wv.s3_url w_url
                FROM videos v
                INNER JOIN video_leads vl
                ON v.lead_id = vl.id
                INNER JOIN edited_video ev
                ON ev.video_id = v.id
                INNER JOIN feed_video fd ON v.id = fd.video_id
                LEFT JOIN watermark_videos wv ON v.id = wv.video_id
                WHERE v.status = 1
                AND v.deleted = 0
                AND v.is_wooglobe_video = 1
                AND v.mrss = 1
                and fd.feed_id='.$category->id.'
                ORDER BY v.created_at DESC '.$limit_sec.'
            ';
            $videos = $this->db->query($query);

            $title = "WooGlobe Video Feed";
            $link = $this->data['url'];
            $description = "Description of the WooGlobe Video Feed";
            // This is the language you display for this podcast.
            $lang = "en-us";
            // This is the copyright information.
            $copyright = "Copyright 2019 WooGlobe";
            $builddate = date(DATE_RFC2822);
            header("Content-type: text/xml");

            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                <rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:atom="http://www.w3.org/2005/Atom">
                <channel>
                <atom:link href="https://wooglobe.com/rss.xml" rel="self" type="text/xml" />
                    <title><![CDATA[' . htmlspecialchars($title) . ']]></title>
                    <link>' .htmlspecialchars($link) . '</link>
                    <description><![CDATA[' . htmlspecialchars($description) . ']]></description>
                    <language>' . htmlspecialchars($lang) . '</language>
                    <copyright>' . htmlspecialchars($copyright) . '</copyright>
                    <lastBuildDate>' . htmlspecialchars($builddate) . '</lastBuildDate>';

            if ($videos->num_rows() > 0) {
                foreach ($videos->result() as $video) {
                    $ext='';
                    $query_raw = 'SELECT rv.s3_url
                            FROM videos v
                            INNER JOIN raw_video rv
                            ON rv.video_id = v.id
                            AND rv.video_id = ' . $video->id;
                    $videos_raw = $this->db->query($query_raw);
                    $raw_vid = $videos_raw->result();
                    $raw_total = count($raw_vid);

                    $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                            FROM videos v
                            INNER JOIN edited_video ev
                            ON ev.video_id = v.id
                            AND ev.video_id = ' . $video->id;
                    $videos_edited = $this->db->query($query_edited);
                    $vid = $videos_edited->result();

                    $videothumb = trim($video->youtube_id);
                    $thumb = '';
                    $videourl = 'http://wooglobe.com';

                    if (isset($vid[0])) {
                        $thumb = $vid[0]->portal_thumb;
                    }
                    if ($thumb) {
                        $thumb = $thumb;
                    } else {
                        $thumb = 'https://img.youtube.com/vi/' . $videothumb . '/hqdefault.jpg';
                    }
                    if ($raw_total > 1) {
                        if (isset($vid[0])) {
                            if($category->watermark == 0){
                                $videourl = $vid[0]->portal_url;
                            }else{
                                $videourl = $video->w_url;
                            }
                            if(empty($videourl)){
                                $videourl = 'http://wooglobe.com/';
                            }
                            $ext = explode('.', $vid[0]->portal_url);
                            $ext =$ext[count($ext) - 1];
                            if(empty($ext)){
                                $ext = 'mp4';
                            }
                        }
                    } else {
                        if (isset($raw_vid[0])) {
                            if($category->watermark == 0){
                                $videourl = $raw_vid[0]->s3_url;
                            }else{
                                $videourl = $video->w_url;
                            }
                            if(empty($videourl)){
                                $videourl = 'http://wooglobe.com/';
                            }
                            $ext = explode('.', $raw_vid[0]->s3_url);
                            $ext =$ext[count($ext) - 1];
                            if(empty($ext)){
                                $ext = 'mp4';
                            }
                        }
                    }
                    $slug  = $video->slug;
                    $slug =rawurlencode($slug);
                    $dataurl=$this->data['url'];
                    $dataurl =str_replace("https", "http", $dataurl);
                    $videourl =str_replace("https", "http", $videourl);

                    if(strpos($videourl, "http") === false){
                        $videourl='http://'.$videourl;
                    }

                    $unique_key=$video->unique_key;
                    $videotitle =str_replace('&','',$video->title);
                    $videotitle =str_replace('<','',$videotitle);
                    $videotitle = preg_replace('/[^A-Za-z0-9\,\ ]/', '', $videotitle);

                    $videotags =str_replace('&','',$video->tags);
                    $videotags =str_replace('<','',$videotags);
                    $videotags = preg_replace('/[^A-Za-z0-9\,\ ]/', '', $videotags);

                    $videodescription =str_replace('&','',$video->description);
                    $videodescription =str_replace('<','',$videodescription);
                    $videodescription = preg_replace('/[^A-Za-z0-9\-\ ]/', '', $videodescription);

                    $xml .= '<item>
                            <title><![CDATA[' . htmlspecialchars($videotitle) . ']]></title>
                            <link>' . htmlspecialchars($dataurl) . '?video=' . htmlspecialchars($slug) . '</link>
                            <guid  isPermaLink="false">' . htmlspecialchars($unique_key) . '</guid>
                            <description><![CDATA[' . htmlspecialchars($videodescription) . ']]></description>
                            <pubDate>' . date(DATE_RFC2822, strtotime($video->created_at)) . '</pubDate>
                            <enclosure url="' . htmlspecialchars($videourl) . '" length="'.strlen($videourl).'" type="video/mp4"></enclosure>
                            <media:content type="video/' . htmlspecialchars($ext) . '" url="' . htmlspecialchars($videourl) . '">
                                <media:keywords><![CDATA[' . htmlspecialchars($videotags) . ']]></media:keywords>
                                <media:thumbnail url="' . htmlspecialchars($thumb) . '" />
                                <media:credit role="producer" scheme="urn:ebu"><![CDATA[ WooGlobe ]]></media:credit>
                            </media:content>
                            </item>';
                }
            }
            $xml .= '</channel></rss>';
            $name = 'mrss.xml';
            echo $xml;
            exit;
        }
        else {
            echo 'Invalid URL';
            exit;
        }

    }

    public function rss_article($p_name, $url) {
        $feed = $this->home->getArticleFeed($p_name, $url);
        $feed = $feed->result();
        $article_data = $this->home->getArticlesByFeedId($feed[0]->id);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <rss
                xmlns:atom="http://www.w3.org/2005/Atom"
                xmlns:media="http://search.yahoo.com/mrss/"
                xmlns:mi="http://schemas.ingestion.microsoft.com/common/"
                xmlns:dc="http://purl.org/dc/elements/1.1/"
                xmlns:dcterms="http://purl.org/dc/terms/"
                version="2.0">
                    <channel>
                        <title>' . htmlspecialchars($feed[0]->feed_title) . '</title>
                        <link></link>
                        <description></description>
                        <lastBuildDate></lastBuildDate>';

        foreach ($article_data->result() as $article) {
            $xml .= '
            <item>
                <guid isPermaLink="false">' . $article->id. '</guid>
                <title>' . $article->title . '</title>
                <pubDate>' . $article->created_at . '</pubDate>
                <dcterms:modified>' . $article->updated_at . '</dcterms:modified>
                <dc:creator>' . $article->credit . '</dc:creator>
                <dcterms:alternative></dcterms:alternative>
                <dcterms:valid></dcterms:valid>
                <mi:expirationDate></mi:expirationDate>
                <mi:shortTitle></mi:shortTitle>
                <media:keywords>' . $article->keywords . '</media:keywords>
                <category>' . $article->category . '</category>
                <description><![CDATA[ ' . $article->description . ' ]]></description>
            ';

            $slides = $this->home->getSlidesByArticleId($article->id);

            foreach ($slides->result() as $slide) {
                $xml .= '
                <media:content url="' . $slide->image_s3_url . '" type="image/*" medium="image">
                    <media:credit>' . $slide->credit . '</media:credit>
                    <media:title>' . $slide->file_title . '</media:title>
                    <media:text></media:text>
                    <media:description><![CDATA[<p>' . $slide->description . '</p>]]></media:description>
                    <mi:focalRegion>
                        <mi:x1></mi:x1>
                        <mi:y1></mi:y1>
                        <mi:x2></mi:x2>
                        <mi:y2></mi:y2>
                    </mi:focalRegion>
                    <mi:hasSyndicationRights></mi:hasSyndicationRights>
                    <mi:licenseId></mi:licenseId>
                    <mi:licensorName></mi:licensorName>
                </media:content>';
            }
            $xml .= '</item>';
        }

        $xml .= '</channel>
                </rss>';

        header('Content-Type: application/xml; charset=UTF-8');
        echo $xml;
        exit();
    }

    public function publication_date_video($url,$partner) {
        $limit='';
        $offset='';
        if(isset($_REQUEST['limit'])){
            $limit = $_REQUEST['limit'];
        }
        if(isset($_REQUEST['offset'])){
            $offset = $_REQUEST['offset'];
        }
        if(isset($_REQUEST['sort'])){
            $sort = $_REQUEST['sort'];
        }
        if(!empty($limit)){
            $limit_sec = "limit $offset,$limit";
        }else{
            $limit_sec ='';
        }
        if(!empty($sort)){
            $sort_sec = $sort;
        }else{
            $sort_sec ='DESC';
        }

        $query_category = "SELECT cm.id,u.watermark,cm.partner_id,cm.pub_date
                FROM mrss_feeds cm
                LEFT JOIN users u ON u.id = cm.partner_id
                WHERE cm.status = 1
                AND cm.deleted = 0
                AND cm.url = '".$url."/".$partner."'";
        $category = $this->db->query($query_category);

        if($category->num_rows() > 0) {
            $category = $category->row();
            $query = 'SELECT v.*,vl.unique_key,ev.portal_url,ev.portal_thumb,vl.created_at,fd.feed_id,ac.action,ac.created_at,wv.s3_url w_url
                FROM videos v
                INNER JOIN video_leads vl
                ON v.lead_id = vl.id
                INNER JOIN edited_video ev
                ON ev.video_id = v.id
                INNER JOIN feed_video fd ON v.id = fd.video_id
                LEFT JOIN action_taken ac ON v.lead_id = ac.lead_id
                LEFT JOIN watermark_videos wv ON v.id = wv.video_id 
                WHERE v.status = 1
                AND v.deleted = 0
                AND v.is_wooglobe_video = 1
                AND v.mrss = 1
                and fd.feed_id='.$category->id.'
                and ac.action = "Edited files uploaded"
                group by v.id
                ORDER BY ac.created_at '.$sort_sec.' '.$limit_sec.'
            ';
            $videos = $this->db->query($query);

            $title = "WooGlobe Video Feed";
            $link = $this->data['url'];
            $description = "Description of the WooGlobe Video Feed";
            // This is the language you display for this podcast.
            $lang = "en-us";
            // This is the copyright information.
            $copyright = "Copyright 2019 WooGlobe";
            $builddate = date(DATE_RFC2822);
            header("Content-type: text/xml");
            $ref_url= 'https://wgv.wooglobe.com/mrss/mrss_partner_download/'.$url.'/'.$partner;

            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
                <rss xmlns:media="http://search.yahoo.com/mrss/" version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:georss="http://www.georss.org/georss"
                xmlns:gml="http://www.opengis.net/gml">
                <channel>
                <atom:link href="'.htmlspecialchars($ref_url).'" rel="self" type="application/rss+xml"  />
                    <title><![CDATA[' . htmlspecialchars($title) . ']]></title>
                    <link>' . htmlspecialchars($link) . '</link>
                    <description><![CDATA[' . htmlspecialchars($description) . ']]></description>
                    <language>' . htmlspecialchars($lang) . '</language>
                    <copyright>' . htmlspecialchars($copyright) . '</copyright>
                    <lastBuildDate>' . htmlspecialchars($builddate) . '</lastBuildDate>';
            $videos_array = array();

            if ($videos->num_rows() > 0) {
                foreach ($videos->result() as $video) {
                    $query_action = 'SELECT ac.action , ac.created_at
                            FROM action_taken ac
                            WHERE ac.lead_id = '.$video->lead_id;
                    $videos_action = $this->db->query($query_action);
                    $vid_actions=$videos_action->result();
                    $numItems = count($vid_actions);
                    $i = 0;
                    $pub_date = "";
                    if(!empty($category->pub_date)){
                        if($category->pub_date == "publish_date"){
                            $pubQuery = $this->db->query("
                            SELECT *
                            FROM mrss_publication
                            WHERE feed_id = $category->id
                            AND video_id = $video->id
                        ");
                            if($pubQuery->num_rows() > 0){
                                $pubRow = $pubQuery->row();
                                $pub_date = date(DATE_RFC2822, strtotime($pubRow->publication_date));
                            }else{
                                foreach ($vid_actions as $vid_ac){
                                    if($video->question_when_video_taken != '0000-00-00' ){
                                        $pub_date = date(DATE_RFC2822, strtotime($video->question_when_video_taken));
                                    }elseif($vid_ac->action == "Edited files uploaded"){
                                        $pub_date = date(DATE_RFC2822, strtotime($vid_ac->created_at));
                                    }else{
                                        $pub_date = date(DATE_RFC2822, strtotime($video->created_at));
                                    }
                                    if(++$i === $numItems) {
                                        $updated= date(DATE_RFC2822, strtotime($vid_ac->created_at));
                                    }
                                }
                            }
                        }else{
                            foreach ($vid_actions as $vid_ac){
                                if($category->pub_date == "question_when_video_taken" && $video->question_when_video_taken != '0000-00-00' ){
                                    $pub_date = date(DATE_RFC2822, strtotime($video->question_when_video_taken));
                                }elseif($vid_ac->action == "Edited files uploaded"){
                                    $pub_date = date(DATE_RFC2822, strtotime($vid_ac->created_at));
                                }else{
                                    $pub_date = date(DATE_RFC2822, strtotime($video->created_at));
                                }
                                if(++$i === $numItems) {
                                    $updated= date(DATE_RFC2822, strtotime($vid_ac->created_at));
                                }
                            }
                        }

                    }else{
                        foreach ($vid_actions as $vid_ac){
                            if($video->question_when_video_taken != '0000-00-00' ){
                                $pub_date = date(DATE_RFC2822, strtotime($video->question_when_video_taken));
                            }elseif($vid_ac->action == "Edited files uploaded"){
                                $pub_date = date(DATE_RFC2822, strtotime($vid_ac->created_at));
                            }else{
                                $pub_date = date(DATE_RFC2822, strtotime($video->created_at));
                            }
                            if(++$i === $numItems) {
                                $updated= date(DATE_RFC2822, strtotime($vid_ac->created_at));
                            }
                        }
                    }

                    $dbdata['feed_id'] = $category->id;
                    $dbdata['video_id'] = $video->id;
                    $dbdata['publication_date'] = date('Y-m-d', strtotime($pub_date));
                    $this->db->insert('mrss_publication',$dbdata);
                }
            }
        }
        else {
            echo 'Invalid URL read';
            exit;
        }

    }
    public function copy_feeds($url,$partner) {
        $limit='';
        $offset='';
        if(isset($_REQUEST['limit'])){
            $limit = $_REQUEST['limit'];
        }
        if(isset($_REQUEST['offset'])){
            $offset = $_REQUEST['offset'];
        }
        if(isset($_REQUEST['sort'])){
            $sort = $_REQUEST['sort'];
        }
        if(!empty($limit)){
            $limit_sec = "limit $offset,$limit";
        }else{
            $limit_sec ='';
        }
        if(!empty($sort)){
            $sort_sec = $sort;
        }else{
            $sort_sec ='DESC';
        }

        $query_category = "SELECT cm.id,u.watermark,cm.partner_id,cm.pub_date
                FROM mrss_feeds cm
                LEFT JOIN users u ON u.id = cm.partner_id
                WHERE cm.status = 1
                AND cm.deleted = 0
                AND cm.url = '".$url."/".$partner."'";
        $category = $this->db->query($query_category);

        if($category->num_rows() > 0) {
            $category = $category->row();
            $query = 'SELECT v.*,vl.unique_key,ev.portal_url,ev.portal_thumb,vl.created_at,fd.feed_id,ac.action,ac.created_at,wv.s3_url w_url,fd.video_id fvid,fd.exclusive_to_partner
                FROM videos v
                INNER JOIN video_leads vl
                ON v.lead_id = vl.id
                INNER JOIN edited_video ev
                ON ev.video_id = v.id
                INNER JOIN feed_video fd ON v.id = fd.video_id
                LEFT JOIN action_taken ac ON v.lead_id = ac.lead_id
                LEFT JOIN watermark_videos wv ON v.id = wv.video_id
                LEFT JOIN mrss_publication mp ON v.id = mp.video_id 
                WHERE v.status = 1
                AND v.deleted = 0
                AND v.is_wooglobe_video = 1
                AND v.mrss = 1
                and fd.feed_id='.$category->id.'
                and ac.action = "Edited files uploaded"
                GROUP By v.id
                ORDER BY mp.publication_date  '.$sort_sec.' '.$limit_sec.'
            ';
            $videos = $this->db->query($query);

            if ($videos->num_rows() > 0) {
                $day =0;
                foreach ($videos->result() as $i=>$video) {
                    $dbdata['feed_id'] = 48;
                    $dbdata['video_id'] = $video->fvid;
                    $dbdata['exclusive_to_partner'] = $video->exclusive_to_partner;  
                    $this->db->insert('feed_video',$dbdata); 
                    $db_data['feed_id'] = 48;
                    $db_data['video_id'] = $video->fvid;
                    if($i < 10){
                        $db_data['publication_date'] = date('Y-m-d');
                    }else{
                        if(($i%10) == 0){
                            $day++;
                        }
                        $db_data['publication_date'] = date('Y-m-d',strtotime("-$day days"));
                    }
                    $this->db->insert('mrss_publication',$db_data);
                }

            }
        }
        else {
            echo 'Invalid URL read';
            exit;
        }

    }
    public function update_video_leads() {
        $query = 'SELECT videos.id,videos.slug,video_leads.id FROM `video_leads` INNER JOIN videos ON video_leads.id=videos.lead_id WHERE video_leads.`deleted` = 0 AND video_leads.`slug`=0';
        $videos = $this->db->query($query);
        foreach ($videos->result() as $video) {
            $lquery = 'UPDATE video_leads SET slug ="'.$video->slug.'" WHERE id='.$video->id;
            $this->db->query($lquery);
        }
    }
    public function delete_files() {
     $query = 'SELECT unique_key FROM `video_leads` WHERE DATE(created_at) BETWEEN  "2020-01-01 00:00:00" AND "2020-03-01 00:00:00"';
        $videos = $this->db->query($query);
        foreach ($videos->result() as $video) {
            if(!empty($video->unique_key)) {
                $dir= '/var/www/html/uploads/';
                $Pscan = scandir($dir);
                print "<pre>";
                print_r($Pscan);
                print "</pre>";
            }
       }
    }
    public function createDirectories() {
        $query = $this->db->query('
            SELECT * FROM video_leads
        ');
        $i = 0;
        foreach ($query->result() as $row){
            if(!file_exists("./uploads/$row->unique_key")){
                mkdir("./uploads/$row->unique_key");
                if(!file_exists("./uploads/$row->unique_key/documents")){
                    mkdir("./uploads/$row->unique_key/documents");
                }
                if(!file_exists("./uploads/$row->unique_key/edited_videos")){
                    mkdir("./uploads/$row->unique_key/edited_videos");
                    if(!file_exists("./uploads/$row->unique_key/edited_videos/facebook")){
                        mkdir("./uploads/$row->unique_key/edited_videos/facebook");
                        if(!file_exists("./uploads/$row->unique_key/edited_videos/facebook/thumbnail")){
                            mkdir("./uploads/$row->unique_key/edited_videos/facebook/thumbnail");
                        }
                    }
                    if(!file_exists("./uploads/$row->unique_key/edited_videos/mrss")){
                        mkdir("./uploads/$row->unique_key/edited_videos/mrss");
                        if(!file_exists("./uploads/$row->unique_key/edited_videos/mrss/thumbnail")){
                            mkdir("./uploads/$row->unique_key/edited_videos/mrss/thumbnail");
                        }
                    }
                    if(!file_exists("./uploads/$row->unique_key/edited_videos/youtube")){
                        mkdir("./uploads/$row->unique_key/edited_videos/youtube");
                        if(!file_exists("./uploads/$row->unique_key/edited_videos/youtube/thumbnail")){
                            mkdir("./uploads/$row->unique_key/edited_videos/youtube/thumbnail");
                        }
                    }
                }
                if(!file_exists("./uploads/$row->unique_key/raw_videos")){
                    mkdir("./uploads/$row->unique_key/raw_videos");
                }
                $i++;
            }
        }
        echo $i ." Directories created.";
        exit;
    }
}
