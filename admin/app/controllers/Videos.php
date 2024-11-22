<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use Aws\S3\MultipartUploader;


class Videos extends APP_Controller
{
    private $fb;
    private $token;


    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'videos';
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
            //'bower_components/tinymce/tinymce.min.js',
            //'assets/js/pages/forms_wysiwyg.js',
            'assets/js/pages/forms_file_upload.js',
            'assets/js/custom/dropify/dist/js/dropify.min.js',
            'assets/js/pages/forms_file_input.min.js',
            'assets/js/typehead.js',
            'assets/js/videos6.js',
            'assets/js/upload_edited_video.js',
            'assets/js/vid_up/video.js',
            'assets/js/vid_up/jquery.fileuploader.min.js'
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);
        $this->data['assess'] = array(
            'list' => role_permitted_html(false, 'videos'),
            'can_add' => role_permitted_html(false, 'videos', 'add_video'),
            'can_edit' => role_permitted_html(false, 'videos', 'update_video'),
            'can_delete' => role_permitted_html(false, 'videos', 'delete_video'),
            'can_view_files' => role_permitted_html(false, 'videos', 'original_files'),
            'can_add_earning' => role_permitted_html(false, 'earnings', 'add_earning'),
            'can_verify' => role_permitted_html(false, 'video_deals', 'can_verify'),
            'can_list_earning' => role_permitted_html(false, 'earnings'),
            'can_edit_earning' => role_permitted_html(false, 'earnings', 'update_earning'),
            'can_delete_earning' => role_permitted_html(false, 'earnings', 'delete_eaning'),
            'can_add_expense' => role_permitted_html(false, 'video_expenses', 'add_video_expense'),
            'can_list_expense' => role_permitted_html(false, 'video_expenses'),
            'can_edit_expense' => role_permitted_html(false, 'video_expenses', 'update_video_expense'),
            'can_delete_expense' => role_permitted_html(false, 'video_expenses', 'delete_video_expense'),
        );
        $this->ci =& get_instance();
        $this->ci->load->database();
        $this->fb = new Facebook\Facebook([
            'app_id' => $this->ci->config->config['fb_id'], // Replace {app-id} with your app id
            'app_secret' => $this->ci->config->config['fb_secret'],
            'default_graph_version' => 'v3.1',
        ]);
        $this->token = $this->ci->db->query('SELECT token FROM  fb_token WHERE id = 1')->row()->token;
        $this->load->model('Video_Model', 'video');
        $this->load->model('Video_Lead_Model', 'lead');
        $this->load->model('Video_Deal_Model', 'deal');
        $this->load->model('Earning_Type_Model', 'earning_type');
        $this->load->model('Social_Sources_Model', 'source');
        $this->load->model('User_Model', 'user');
        $this->load->model('Categories_Model', 'mrss');
        $this->load->model('Category_Model', 'category');
        $this->load->library('form_validation');
		
    }

    public function index()
    {
        auth();
        role_permitted(false, 'videos');
        $this->data['title'] = 'Videos Management';
        $this->data['earning_type'] = $this->earning_type->getAllEarningTypesActive('et.id,et.earning_type', '', 0, 0, 'et.earning_type ASC');
        $this->data['sources'] = $this->source->getAllSourcesActive('ss.id,ss.sources', '', 0, 0, 'ss.sources ASC');
        $this->data['partners'] = $this->user->getAllUsersActive(2, 'u.id,u.full_name,u.email', '', 0, 0, 'u.full_name ASC');
        $this->data['mrss_categories'] = $this->mrss->getMrss();
        $result = $this->video->getAllVideos(2, 'v.id,v.title,case when (v.status = 1) THEN "Active" ELSE "Inactive" END as status,u.email as email,c.title as ctitle,t.title as ttitle');
        $autoComplete = array();
        foreach ($result->result() as $auto) {
            if (!in_array($auto->title, $autoComplete)) {
                $autoComplete[] = str_replace("'", '', $auto->title);
            }
            if (!in_array($auto->email, $autoComplete)) {
                $autoComplete[] = $auto->email;
            }
            if (!in_array($auto->ctitle, $autoComplete)) {
                $autoComplete[] = $auto->ctitle;
            }
            if (!in_array($auto->ttitle, $autoComplete)) {
                $autoComplete[] = str_replace("'", '', $auto->title);;
            }

        }
        $this->data['autoComplete'] = $autoComplete;

        $this->data['content'] = $this->load->view('videos/listing', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }

    public function upload_edited_video($id)
    {
        $this->data['video_id'] = $id;

        $dealData = $this->video->getLeadDetailByVideoId($id);
        $finalUrl = '';
        if (strpos($dealData->video_url, 'facebook.com/') !== false) {
            //it is FB video
            $finalUrl = 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($dealData->video_url) . '&show_text=1&width=200';
        } else if (strpos($dealData->video_url, 'vimeo.com/') !== false) {
            //it is Vimeo video
            $videoId = explode("vimeo.com/", $dealData->video_url);
            $videoId = $videoId[1];
            if (strpos($videoId, '&') !== false) {
                $videoId = explode("&", $videoId);
                $videoId = $videoId[0];
            }
            $finalUrl = 'https://player.vimeo.com/video/' . $videoId;
        } else if (strpos($dealData->video_url, 'youtube.com/') !== false) {
            //it is Youtube video
            $videoId = explode("v=", $dealData->video_url);
            $videoId = $videoId[1];
            if (strpos($videoId, '&') !== false) {
                $videoId = explode("&", $videoId);
                $videoId = $videoId[0];
            }
            $finalUrl = 'https://www.youtube.com/embed/' . $videoId;


        } else if (strpos($dealData->video_url, 'youtu.be/') !== false) {
            //it is Youtube video
            $videoId = explode("youtu.be/", $dealData->video_url);
            $videoId = $videoId[1];
            if (strpos($videoId, '&') !== false) {
                $videoId = explode("&", $videoId);
                $videoId = $videoId[0];
            }
            $finalUrl = 'https://www.youtube.com/embed/' . $videoId;


        } else {
            $finalUrl = $dealData->video_url;
        }
        $raw_video = $this->video->getRawVideoById($id);
        include_once('./app/third_party/getid3/getid3/getid3.php');
        $getID3 = new getID3;
        //$file = $getID3->analyze($file);
        $edited_video_details = $this->video->getEditedvideoByVideoId($id);
        $category_id = $this->video->getVideoDetailById($id, 'category_id');
        $category = $this->category->getCategoryById($category_id->category_id, 'title');
        //$feed_video_data = $this->mrss->getFeedDataByVideoId($id);

        $this->data['getid3'] = $getID3;
        $this->data['raw_video'] = $raw_video;
        $this->data['video_category'] = $category;
        $this->data['mrss_categories'] = $this->mrss->getGeneralCategories();
        $this->data['selected_mrss_categories'] = $this->mrss->getVideoSelectedCategoriesByVideoId($id);
        $this->data['mrss_partners'] = $this->mrss->getMrssPartners();
        $this->data['mrss_feed_data'] = $this->mrss->getFeedDataByVideoId($id);
        $this->data['non_exclusive_partner_data']=$this->mrss->nonExclusivePartnerdataByVideoId($id);
        $this->data['edited_video_details'] = $edited_video_details;
        $this->data['video'] = $finalUrl;
        $this->data['dealData'] = $dealData;
        $this->data['content'] = $this->load->view('videos/upload_edited_video', $this->data, true);
        $this->load->view('common_files/template', $this->data);

    }

    public function video_listing()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'videos');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->get());
        $search = '';
        $orderby = '';
        $type = 1;
        $start = 0;
        $limit = 0;
        if (isset($params['search'])) {
            $search = $params['search']['value'];
        }
        if (isset($params['start'])) {
            $start = $params['start'];
        }
        if (isset($params['length'])) {
            $limit = $params['length'];
        }
        if (isset($params['video_type'])) {
            $type = $params['video_type'];
        }
        if (isset($params['order'])) {
            $orderby = $params['columns'][$params['order'][0]['column']]['name'] . ' ' . $params['order'][0]['dir'];
        }

        $result = $this->video->getAllVideos($type, 'v.id,v.title,v.mrss,v.mrss_categories,case when (v.status = 1) THEN "Active" ELSE "Inactive" END as status,u.email as email,c.title as ctitle,t.title as ttitle', $search, $start, $limit, $orderby, $params['columns']);
        $resultCount = $this->video->getAllVideos($type);
        $response = array();
        $data = array();

        foreach ($result->result() as $row) {
            $r = array();
            $links = '<a title="Play Video" href="javascript:void(0);" class="play-video" data-id="' . $row->id . '"><i class="material-icons">&#xE04A;</i></a> ';
            if ($this->data['assess']['can_edit']) {

                $links .= ' | <a title="Edit Video" href="' . base_url('edit_video/' . $row->id) . '" class="edit-video" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
            }
            if ($this->data['assess']['can_delete']) {

                $links .= '| <a title="Delete Video" href="javascript:void(0);" class="delete-video" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
            }
            if ($this->data['assess']['can_list_earning']) {

                $links .= '| <a title="Video Earnings" href="' . $this->data['url'] . 'earnings?video_id=' . $row->id . '"><i class="material-icons">&#xE84F;</i></a>';
            }

            if ($this->data['assess']['can_add_earning']) {

                $links .= '| <a title="Add Earning" href="javascript:void(0);" class="add-earning" data-id="' . $row->id . '" data-title="' . $row->title . '"><i class="material-icons">account_balance_wallet</i></a>';
            }

            if ($this->data['assess']['can_list_expense']) {

                $links .= '| <a title="Video Expense" href="' . $this->data['url'] . 'video_expenses?video_id=' . $row->id . '"><i class="material-icons">&#xE25C;</i></a>';
            }

            if ($this->data['assess']['can_add_expense']) {

                $links .= '| <a title="Add Video Expense" href="javascript:void(0);" class="add-expense" data-id="' . $row->id . '" data-title="' . $row->title . '"><i class="material-icons">&#xE227;</i></a>';
            }
            if ($this->data['assess']['can_edit']) {

                $links .= '| <a title="MRSS feed" href="javascript:void(0);" class="mrss-feed" data-id="' . $row->id . '" data-title="' . $row->title . '" data-mrss="' . $row->mrss . '" data-mrss-c="' . $row->mrss_categories . '"><i class="material-icons">rss_feed</i></a>';
            }


            $r[] = $links;
            $r[] = '<a title="Play Video" href="javascript:void(0);" class="play-video" data-id="' . $row->id . '">' . $row->title . '</a>';
            $files = '';
            if ($this->data['assess']['can_view_files']) {

                $files .= '<a title="Original Files" href="' . base_url('original_files/' . $row->id) . '" class="original-files" data-id="' . $row->id . '">Original Files</a> ';
            }
            $r[] = $files;
            $r[] = $row->ctitle;
            $r[] = $row->ttitle;
            $r[] = $row->email;
            $r[] = $row->status;


            $data[] = $r;
        }
        $response['code'] = 200;
        $response['message'] = 'Listing';
        $response['error'] = '';
        $response['data'] = $data;
        $response['recordsTotal'] = $resultCount->num_rows();
        $response['recordsFiltered'] = $resultCount->num_rows();
        echo json_encode($response);
        exit;
    }

    public function video_add()
    {
        auth();
        role_permitted(false, 'videos', 'add_video');
        $this->data['title'] = 'Add New Video';
        $this->data['categories'] = $this->video->getParentCategories('id,title');
        $this->data['users'] = $this->video->getAllUsers('id,full_name,email');
        $this->data['videoTypes'] = $this->video->getAllVideoTypes('id,title');
        $this->data['content'] = $this->load->view('videos/add', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }

    public function add_video()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'videos', 'add_video');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Video Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('parent', 'Parent Category', 'trim|required');
        $this->validation->set_rules('category_id', 'Sub Category', 'trim|required');
        $this->validation->set_rules('title', 'Video Title', 'trim|required');
        $this->validation->set_rules('user_id', 'User', 'trim|required');
        $this->validation->set_rules('video_type_id', 'Video Type', 'trim|required');
        $this->validation->set_rules('status_u', 'Status', 'trim|required');
        $this->validation->set_rules('url', 'Embed Code/URL', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');
        $this->validation->set_message('alpha_numeric_spaces', 'Only alphabet and number are allowed.');
        if ($this->validation->run() === false) {

            $fields = array('title', 'status_u', 'parent', 'category_id', 'user_id', 'video_type_id', 'url');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {
            $response['url'] = $this->data['url'] . 'videos';
            $dbData = $this->security->xss_clean($this->input->post());
            if (!isset($dbData['real_deciption_updated'])) {
                $dbData['real_deciption_updated'] = 0;
            }
            $dbData['url'] = $this->input->post('url');
            $status = $dbData['status_u'];
            $dbData['status'] = $status;
            if (isset($dbData['is_wooglobe_video'])) {
                $dbData['is_wooglobe_video'] = 1;
            } else {
                $dbData['is_wooglobe_video'] = 0;
            }
            unset($dbData['status_u']);
            unset($dbData['parent']);

            if (!empty($_FILES['thumb']['name'])) {

                $data = $this->upload('thumb');

                if ($data['code'] == 200) {

                    $dbData['thumbnail'] = $data['url'];

                }

            }
            if ($dbData['video_type_id'] != 1) {
                $dbData['embed'] = 1;
            }
            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['created_by'] = $this->sess->userdata('adminId');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->insert('videos', $dbData);
        }

        echo json_encode($response);
        exit;

    }

    function getProtectedValue($obj, $name)
    {
        $array = (array)$obj;
        $prefix = chr(0) . '*' . chr(0);
        return $array[$prefix . $name];
    }

    public function instagram_url()
    {
        header("Content-type: application/json");
        $url = $this->security->xss_clean($this->input->post('id'));
        try {
            $response_accounts = $this->fb->get('/me/accounts', $this->token);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error account: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $response_accounts = $response_accounts->getGraphEdge()->asArray();

        $data['data'] = '?scrape=true&id=';
        $data['id'] = $url;
        $insta_response = $this->fb->post('/', $data, $response_accounts[0]['access_token']);

        $insta_jason = $this->getProtectedValue($insta_response, 'body');

        $insta_arrs = json_decode($insta_jason, true);
        /*       print "<pre>";
               print_r( $insta_arrs);
               print "</pre>";
               exit();*/
        /* print"<pre>";
         print_r($insta_arrs);*/
        $response = array();
        $response['code'] = 200;
        $response['data'] = $insta_arrs;
        echo json_encode($response);
        exit;
        //return json_encode($response);
    }


    public function get_video()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'videos');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video found!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->video->getVideoById($id, 'url,embed,title,youtube_id');

        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';

        } else {

            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }

    public function edit_video($id)
    {
//        $this->upload_file_dropbox_new("", "");
        auth();
        role_permitted(false, 'videos', 'update_video');
        $role_permitted_ajax = role_permitted_ajax(false, 'clients', 'can_verify');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $result = $this->video->getVideoDetailById($id, 'v.*,c.parent_id');
        if (!$result) {

            redirect('videos');

        }


        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->sess->set_userdata('refferer', $_SERVER['HTTP_REFERER']);
        }


        $dataArry = array();
        foreach ($result as $i => $v) {
            if ($i == 'category_id' || $i == 'video_type_id' || $i == 'user_id' || $i == 'status' || $i == 'parent_id') {
                $dataArry[$i] = $v;
            }

        }


        $raw_video = $this->video->getRawVideoById($id);

        $this->data['title'] = 'Edit Video';
        $this->data['id'] = $id;
        $this->data['data'] = $result;
        $this->data['edit_data'] = json_encode($dataArry, true);

        $this->data['raw_video'] = $raw_video;


        //$file  = './uploads/WGA457362/raw_videos/wga457362_1600072485.mp4';
        include_once('./app/third_party/getid3/getid3/getid3.php');
        $getID3 = new getID3;
        //$file = $getID3->analyze($file);
        /*echo '<pre>';
        print_r($file);
        echo '</pre>';
        exit;*/
        $this->data['getid3'] = $getID3;
        $this->data['categories'] = $this->video->getParentCategories('id,title');
        $this->data['reuters_categories'] = $this->video->getReutersCategories('id,title');
        $this->data['users'] = $this->video->getAllUsers('id,full_name,email');
        $this->data['lead'] = $this->lead->getLeadByIdAllStatus($result->lead_id);

        $this->data['videoTypes'] = $this->video->getAllVideoTypes('id,title');
        $this->data['content'] = $this->load->view('videos/edit', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }

    public function portraitToLandscape($id)
    {
        $videos = $this->video->getVideoForDataForLandscapeConversion($id);
        include_once('./app/third_party/getid3/getid3/getid3.php');
        $getid3 = new getID3;

        foreach ($videos->result() as $video){
            $video_url = '';
            $is_local = false;
            if (strlen($video->portal_url) > 0) {
                $video_url = $video->portal_url;
            } else if (strlen($video->watermark_url) > 0){
                $video_url = $video->watermark_url;
            } else if (strlen($video->s3_url) > 0){
                $video_url = $video->s3_url;
            } else {
                $video_url = '../'.$video->url;
                $is_local = true;
            }

            $wgid = $video->unique_key;
            if(!is_dir("../uploads/".$wgid."/landscape_converted"))
            {
                mkdir("../uploads/".$wgid."/landscape_converted", 0777, true);
            }
            
            if (strlen($video_url) > 0) {
                $filename = basename($video_url);
                $output_file = "uploads/".$wgid."/landscape_converted/".$filename;
                $file_extension = explode('.', $filename);
                $file_extension = $file_extension[1];
                
                if(!$is_local) 
                {
                    $video_data = exec("ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of csv=p=0 $video_url");
                    $video_data = explode(',', $video_data);
                    $video_dimensions = array(
                        'width' => (int) $video_data[0],
                        'height' => (int) $video_data[1]
                    );
                    if ($video_dimensions['width'] / $video_dimensions['height'] != 16/9) {
                        exec("ffmpeg -i $video_url -vf \"scale=-1:1080,pad=1920:1080:(ow-iw)/2:(oh-ih)/2\" ../$output_file");
                    }
                } 
                else 
                {
                    if(file_exists($video_url)){
                        $file_data = $getid3->analyze($video_url);
                    } else {
                        $file_data = "";
                    }
                    if(is_array($file_data) && (!isset($file_data['error']))){
                        if($file_data['video']['resolution_x'] / $file_data['video']['resolution_y'] != 1.78) {
                            exec("ffmpeg -i $video_url -vf \"scale=-1:1080,pad=1920:1080:(ow-iw)/2:(oh-ih)/2\" ../$output_file");
                        }
                    } else {
                        $response['code'] = 201;
                        $response['message'] = 'No video found!';
                        $response['error'] = $file_data['error'];
                        $response['url'] = '';
                        echo json_encode($response);
                        exit;
                    }
                }
                $s3_url = $this->upload_file_s3_new('public-read','../'.$output_file, $output_file, $file_extension, FALSE);
                if (strlen($s3_url) > 0) {
                    $this->db->where('video_id', $video->video_id);
                    $this->db->update('raw_video', array('landscape_converted_url' => $s3_url));
                }
                else {
                    $response['code'] = 201;
                    $response['message'] = 'Landscape Converted S3 Upload Failed!';
                    $response['error'] = 'Landscape Converted S3 Upload Failed!';
                    $response['url'] = '';
                    echo json_encode($response);
                    exit;
                }
            }
        }
    }

    public function update_video()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'videos', 'update_video');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video Updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';


        /*echo '<pre>';
        print_r($this->input->post());
        exit;*/

        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->video->getVideoById($id);
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        //$this->validation->set_rules('parent','Parent Category','trim|required');
        $this->validation->set_rules('category_id[]', 'Category', 'trim|required');
        $this->validation->set_rules('reuters_category_id[]', 'Category', 'trim|required');
        $this->validation->set_rules('title', 'Video Title', 'trim|required');
        //$this->validation->set_rules('user_id','User','trim|required');
        //$this->validation->set_rules('video_type_id','Video Type','trim|required');
        //$this->validation->set_rules('status_u','Status','trim|required');
        //$this->validation->set_rules('url','Embed Code/URL','trim|required');
        $this->validation->set_message('required', 'This field is required.');$this->validation->set_message('alpha_numeric_spaces', 'Only alphabet and number are allowed.');
        if ($this->validation->run() === false) {

            $fields = array('title', 'status_u', 'category_id', 'reuters_category_id');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {
            $response['url'] = $this->data['url'] . 'videos';
            $dbData = $this->security->xss_clean($this->input->post());
            $verify = false;
            if (!isset($dbData['real_deciption_updated'])) {
                $dbData['real_deciption_updated'] = 0;
            } else {
                $dbData['real_deciption_updated'] = 1;
            }
            if (!isset($dbData['sign_appreance'])) {
                $dbData['sign_appreance'] = 0;
            } else {
                $dbData['sign_appreance'] = 1;
            }
            // $dbData['url'] = $this->input->post('url');
            //$status = $dbData['status_u'];
            $dbData['status'] = 1;

            $dbData['is_wooglobe_video'] = 1;

            if (!empty($dbData['category_id'])) {

                $mrss_info = array();
                $this->mrss->clearGeneralFeedsByVideoId($id);
                foreach ($dbData['category_id'] as $key => $category_id) {
                    $mrss_video_data = array('feed_id' => $category_id, 'video_id' => $id);
                    $mrss_info [] = $mrss_video_data;
                }

                if (!empty($mrss_info)) {
                    $this->db->insert_batch('feed_video', $mrss_info);
                }

                $dbData['category_id'] = implode(',', $dbData['category_id']);

            }
            if (!empty($dbData['reuters_category_id'])) {
                $dbData['reuters_category_id'] = implode(',', $dbData['reuters_category_id']);

            }
            if (isset($dbData['is_high_quality'])) {
                $dbData['is_high_quality'] = 1;
            } else {
                $dbData['is_high_quality'] = 0;
            }

            if (isset($dbData['is_real_file'])) {
                $dbData['is_real_file'] = 1;
            } else {
                $dbData['is_real_file'] = 0;
            }

            if (isset($dbData['is_complete_file'])) {
                $dbData['is_complete_file'] = 1;
            } else {
                $dbData['is_complete_file'] = 0;
            }
            if (isset($dbData['second_signer'])) {
                $dbData['second_signer'] = 1;
            } else {
                $dbData['second_signer'] = 0;
            }
          /*  if (isset($dbData['sign_appreance'])) {
                $dbData['sign_appreance'] = 1;
            } else {
                $dbData['sign_appreance'] = 0;
            }*/
            //start video encoding section
            
            if (isset($dbData['outro_video'])) {
                $dbData['outro_video'] = 1;
            } else {
                $dbData['outro_video'] = 0;
            }
            if (isset($dbData['video_credits'])) {
                $dbData['video_credits'] = 1;
            } else {
                $dbData['video_credits'] = 0;
            }
            if (isset($dbData['social_icon'])) {
                $dbData['social_icon'] = 1;
            } else {
                $dbData['social_icon'] = 0;
            }
            if (isset($dbData['sound_mute'])) {
                $dbData['sound_mute'] = 1;
            } else {
                $dbData['sound_mute'] = 0;
            }
            $dbData['credit_user_name'];
            $dbData['video_credits_value '];
            $dbData['outro_video_value'];
            // end video encoding

            if (isset($dbData['watermark'])) {
                $dbData['watermark'] = 1;
            } else {
                $dbData['watermark'] = 0;
            }


            $dbData['mrss'] = 1;

            if (!isset($dbData['is_featured'])) {
                $dbData['is_featured'] = 0;
            } else {
                $dbData['is_featured'] = 1;
            }
            unset($dbData['status_u']);
            unset($dbData['parent']);
            $lead_id = $result->lead_id;
            $leadquery = $this->db->query('SELECT *
		    FROM video_leads vl
		    WHERE vl.id = '.$lead_id);
            $leadData = $leadquery->row();
           /* print_r($leadData->unique_key);
            exit();*/
            $emailData = $this->lead->getLeadMailStatusByLeadId($lead_id);
            $data_log_query=$this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="'.$lead_id.'"');
            $logs_query=$data_log_query->result();
            $sent_log_query=$this->db->query('SELECT lead_rated_date  FROM `lead_action_dates` WHERE `lead_id` = "'.$lead_id.'"');
            $sent_log_result=$sent_log_query->result();
            $user_query=$this->db->query('SELECT *  FROM `users` WHERE `id` = "'.$leadData->client_id.'"');
            $user_result=$user_query->result();
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
            if(isset($user_result[0])){
                $user['full_name']=$user_result[0]->full_name;
                $user['email']=$user_result[0]->email;
                $user['paypal_email']=$user_result[0]->paypal_email;
                $country_code = explode('-',$user_result[0]->country_code);
                $user['phone']=$country_code[0].$user_result[0]->mobile;
                $user['country']=$user_result[0]->country_code;
                $user['state']=$user_result[0]->state_id;
                $user['city']=$user_result[0]->city_id;
                $user['address']=$user_result[0]->address;
                $user['zip']=$user_result[0]->zip_code;
            }
            $data['video_title']=$leadData->video_title;
            $data['video_url']=$leadData->video_url;
            if(isset($video_result[0])){
                $data['question1']=$video_result[0]->question_video_taken;
                $data['question3']=$video_result[0]->question_when_video_taken;
            }
            // set IP address and API access key

            $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';

// Initialize CURL:
            $ch = curl_init('http://api.ipstack.com/'.$data_log['user_ip_address'].'?access_key='.$access_key.'');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
            $json = curl_exec($ch);
            curl_close($ch);

// Decode JSON response:
            $api_result = json_decode($json, true);
            //Generate Pdf
            $country_name_va='';
            if($user['country']){
                $country_name=explode("-",$user['country']);
                if(isset($country_name[1])){
                    $country_name_va=$country_name[1];
                }
            }

            //if($dbData['is_high_quality'] == 1 && $dbData['is_complete_file'] == 1 && $dbData['is_real_file'] == 1
            // && $dbData['real_deciption_updated'] == 1){

            if($dbData['sign_appreance'] == 1)
            {
                $time = time();
                $uid =$leadData->unique_key ;
                if($leadData->pdf_appreance_signed==0)
                {
                $help_us =$dbData['release_description'];
                $pdf_link = '/uploads/'.$uid.'/owner_appearance/'.$uid.'_signed_'.$time.'.pdf';
                $date = date("Y-m-d H:i:s");
                $insert_appreance_lead = array(
                    'uid' => $uid,
                    'first_name' => $leadData->first_name,
                    'last_name' =>  $leadData->last_name,
                    'email' => $user['email'],
                    'countary_code' => $user_result[0]->country_code,
                    'phone' => $user['phone'],
                    'country_id'=>$user['country'],
                    'state_id'=>$user['state'],
                    'city_id'=>$user['city'],
                    'address' => $user['address'],
                    'zip_code' => $user['zip'],
                    'help_us'  => $help_us,
                    'date_added' =>  date("Y-m-d H:i:s"),
                    'pdf_link'=>$pdf_link,
                    'created_at' => date("Y-m-d H:i:s"),

                );

                $this->db->insert('appreance_release',$insert_appreance_lead);

                // Apperence release PDF document
                 $appreance_pdf= $this->appreance_signed_pdf($data['video_url'],$leadData->first_name,$leadData->last_name,$user['email'],$user['phone'],$country_name_va,$user['state'],$user['city'],$user['address'],$user['zip'],$help_us,$date,$leadData->unique_key,$data_log,$api_result,$time);
                    $source_file = rtrim($_SERVER['DOCUMENT_ROOT'],'/') . $pdf_link;
                    $file_extension = explode('.', $source_file);
                    $file_extension = $file_extension[1];
                    $target_file_key = $pdf_link;
                    $url = $this->upload_file_s3_new('private',$source_file, $target_file_key, $file_extension, FALSE);
                  $appreance['pdf_appreance_signed'] = 1;
                  $this->db->where('id', $result->lead_id);
                  $this->db->update('video_leads', $appreance);
                 }

            }
            if ($dbData['is_complete_file'] == 1 && $dbData['is_real_file'] == 1 && $dbData['real_deciption_updated'] == 1)
            {

                if($dbData['second_signer'] == 1)
                {
                    if($dbData['second_signer_required'] == 'Yes')
                    {
                      $query = "Select * From second_signer Where uid = '$uid'";

                      $result_signer = $this->db->query($query);
                      if($result_signer->num_rows()>0)
                      {
                          $verify = true;
                      }
                    }
                    else
                    {
                        $verify = true;
                    }
                }
                else{
                    $verify = true;
                }

                if($verify === true)
                {
                    $dbData['video_verified'] = 1;
                    // $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
                    if($data_log == NULL || !isset($data_log['contract_signed_datetime'])){
                        $cont_sig_date = date('Y-m-d');
                    }else{
                        $cont_sig_date = date('Y-m-d',strtotime($data_log['contract_signed_datetime']));
                    }
                    
                    if($leadData->contract_signed==0)
                    {
                        $result_pdf=$this->video_signed_pdf($user['full_name'],$user['email'],$user['phone'],$country_name_va,$user['state'],$user['city'],$user['address'],$user['zip'],$cont_sig_date,$leadData->revenue_share,$leadData->unique_key,$data,$data_log,$api_result);
                    }
                    if($leadData->contract_signed==0 && $leadData->simple_video==1)
                    {
                        $result_pdf=$this->simple_video_signed_pdf($user['full_name'],$user['email'],$user['phone'],$country_name_va,$user['state'],$user['city'],$user['address'],$user['zip'], $cont_sig_date,$leadData->revenue_share,$leadData->unique_key,$data,$data_log,$api_result);
                    }

                    $data = array(
                        'status' => 6,
                        'video_title' => $dbData['title'],
                    );

                    $this->db->where('id', $result->lead_id);
                    $this->db->update('video_leads', $data);
                    action_add($result->lead_id, $result->id, 0, $this->sess->userdata('adminId'), 1, 'Video verified');
                    $rawVideos = $this->video->getRawVideoById1($id);
                    $assignFiles = array();
                    $uid = '';

                    if(!empty($leadData->social_video_id)){
                        $infoQuery = $this->db->query('
                                SELECT *
                                FROM compilation_leads_info
                                WHERE tt_id = "'. $leadData->social_video_id .'"
                            ');
                        if($infoQuery->num_rows() > 0){

                            $info = $infoQuery->row();
                            $this->db->where('id',$leadData->id);
                            $this->db->update('video_leads',array('compilation'=>1,'ytc_id'=>$info->lead_id,'ytc_group_id'=>$info->lead_group_id,'cli_id'=>$info->id));
                            $this->db->where('id',$info->id);
                            $this->db->update('compilation_leads_info',array('status'=>'Acquired'));
                        }
                    }

                    // check each file size and record its source & target
                    $source_key_files = array();
                    $s3_ajax_upload = false;
                    foreach ($rawVideos as $row) {
                        $uid = explode('/', $row->url);

                        $assignFiles[] = $uid[count($uid) - 1];
                        $uid = $uid[1];
                        if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] !=='wooglobe.com') {
                            $source_file = $_SERVER['DOCUMENT_ROOT'] . $this->config->item('local_dir').'/' . $row->url;

                        }else{
                            $source_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $row->url;

                        }

                        $size = filesize($source_file);

                        if (($size/(1024*1024*1024)) >= 1) { // if file is greater than 1 GB; set $s3_ajax_upload = true
                            $s3_ajax_upload = true;
                        }

                        $file_extension = explode('.', $source_file);
                        $file_extension = $file_extension[1];
                        $target_file_key = $row->url;
                        array_push($source_key_files, array('file_source' => $source_file, 'file_ext' => $file_extension, 'file_key' => $target_file_key, 'rid' => $row->id));
                    }

                    if ($s3_ajax_upload) {
                        $response['s3_ajax_files'] = $source_key_files;


                        foreach ($source_key_files as $key => $flist) {
                            $s3_url = 'https://wooglobe.s3-us-west-2.amazonaws.com/'.$flist['file_key'];
                            $this->db->where('id', $flist['rid']);
                            $this->db->update('raw_video', array('s3_url' => $s3_url));
                            if (isset($dbData['watermark']) && $dbData['watermark'] == 1) {
                                $jobId = $this->awsMediaConvert('s3://wooglobe/'.$flist['file_key'],'./../'.$flist['file_key'],$uid);
                                $jobData['video_id'] = $id;
                                $jobData['aws_job_id'] = $jobId;
                                $jobData['local_url'] = $flist['file_key'];
                                $jobData['unique_key'] = $uid;
                                $this->db->insert('watermark_videos', $jobData);
                            }

                            if ((isset($dbData['outro_video']) && $dbData['outro_video'] == 1) || (isset($dbData['video_credits']) && $dbData['video_credits'] == 1) || (isset($dbData['social_icon']) && $dbData['social_icon'] == 1) || (isset($dbData['sound_mute']) && $dbData['sound_mute'] == 1))   {

                                $jobId = $this->awsMediaConvertSocial('s3://wooglobe/'.$flist['file_key'],'./../'.$flist['file_key'],$uid,$dbData);

                                $jobData['video_id'] = $id;
                                $jobData['aws_job_id'] = $jobId;
                                $jobData['local_url'] = $flist['file_key'];
                                $jobData['unique_key'] = $uid;
                                $this->db->insert('social_videos', $jobData);
                            }
                        }

                    }
                    else {
                        foreach ($rawVideos as $row) {
                            $uid = explode('/', $row->url);

                            $assignFiles[] = $uid[count($uid) - 1];
                            $uid = $uid[1];
                            if($_SERVER['HTTP_HOST'] != 'wooglobe.com') {

                                $source_file = $_SERVER['DOCUMENT_ROOT'] . $this->config->item('local_dir').'/' . $row->url;
                            }else{
                                $source_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $row->url;

                            }
                            $file_extension = explode('.', $source_file);
                            $file_extension = $file_extension[1];
                            $target_file_key = $row->url;

                            // Upload Video file to S3 if less than 1GB

                            $url = $this->upload_file_s3_new('public-read',$source_file, $target_file_key, $file_extension, FALSE);
                            $this->db->where('id', $row->id);
                            $this->db->update('raw_video', array('s3_url' => $url));
                            if (isset($dbData['watermark']) && $dbData['watermark'] == 1) {
                                $jobId = $this->awsMediaConvert($url,'./../'.$row->url,$uid);
                                $jobData['video_id'] = $id;
                                $jobData['aws_job_id'] = $jobId;
                                $jobData['local_url'] = $row->url;
                                $jobData['unique_key'] = $uid;
                                $this->db->insert('watermark_videos', $jobData);
                            }

                            if ((isset($dbData['outro_video']) && $dbData['outro_video'] == 1) || (isset($dbData['video_credits']) && $dbData['video_credits'] == 1) || (isset($dbData['social_icon']) && $dbData['social_icon'] == 1) || (isset($dbData['sound_mute']) && $dbData['sound_mute'] == 1))   {

                                $jobId = $this->awsMediaConvertSocial($url,'./../'.$row->url,$uid,$dbData);

                                $jobData['video_id'] = $id;
                                $jobData['aws_job_id'] = $jobId;
                                $jobData['local_url'] = $row->url;
                                $jobData['unique_key'] = $uid;
                                $this->db->insert('social_videos', $jobData);
                            }
                        }
                        $allFiles = scandir("./../uploads/$uid/raw_videos");
                        if(is_array($allFiles)){
                            foreach ($allFiles as $file) {
                                if (!in_array($file, $assignFiles)) {
                                    @unlink("./../uploads/$uid/raw_videos/" . $file);
                                }
                            }
                        }



                    }
                } else {
                    $dbData['video_verified'] = 0;
                    $response['code'] = 205;
                    $response['message'] = 'The video has not been verified yet and second signer contract is required.';
                    $response['error'] = 'The video has not been verified yet and second signer contract is required.';
                    $response['url'] = '';
                    $this->sess->set_flashdata('err', 'The video has not been verified yet and second signer contract is required.');
                }


                //

            } else {
                $dbData['video_verified'] = 0;
                $response['code'] = 205;
                $response['message'] = 'The video has not been verified yet.';
                $response['error'] = 'The video has not been verified yet.';
                $response['url'] = '';
                $this->sess->set_flashdata('err', 'The video has not been verified yet.');
            }

            /*if(!empty($_FILES['thumb']['name'])){

                $data = $this->upload('thumb');

                if($data['code'] == 200){

                    $dbData['thumbnail'] = $data['url'];

                }

            }*/


            unset($dbData['id']);
            /*	if($dbData['video_type_id'] != 1){
                    $dbData['embed'] = 1;
                }*/
            //$dbData['video_verified'] = 1;

            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');

            $this->db->where('id', $id);
            $this->db->update('videos', $dbData);

            //client email after video verification about account created and pdf send
            $leadData = $this->video->getLeadDetailByVideoId($id);
            $this->db->where('unique_key', $leadData->unique_key);
            $this->db->update('video_leads', array("verification_date" => date('Y-m-d H:i:s')));
            $emailData = getEmailTemplateByCode('welcome_email');
            $already_accountemailData = getEmailTemplateByCode('deal_information_received');
            $token_results = $this->db->query('SELECT verify_token,password FROM `users`WHERE `id`= "' . $leadData->client_id . '"')->result();
            $token = $token_results[0]->verify_token;
            if(empty($token)){
                $this->load->helper('string');
                $token = random_string('alnum', 20);
                $dbDatatoken['verify_token'] = $token;
                $dbDatatoken['token_expiry_time'] = date("Y-m-d H:i:s",strtotime("+20 days",strtotime(date("Y-m-d H:i:s"))));
                
                $dbDatatoken['is_active'] = 1;
                $this->db->where('id', $leadData->client_id);
                $this->db->update('users', $dbDatatoken);
                
                $token_results = $this->db->query('SELECT verify_token,password FROM `users`WHERE `id`= "' . $leadData->client_id . '"')->result();
                $token = $token_results[0]->verify_token;
                action_add($result->lead_id, $result->id, 0, $leadData->client_id, 1, 'Token Added');
            }
            
            $password = $token_results[0]->password;
            $unique_key = $leadData->unique_key;
            if($password == null){
                   if($emailData){
                    $str = $emailData->message;
                    if($unique_key){
                        $subject = $emailData->subject.'(Important)-'.$unique_key;
                    }
                    else{
                        $subject = $emailData->subject;
                    }
                    $ids = array(
                        'users' => $leadData->client_id
                    );

                    $message = dynStr($str,$ids);
                    // $url = $this->data['root'].'new-login/'.$token -> waqas;
                    $url = "www.wooglobe.com" . '/new-login/' . $token;
                    $message = str_replace('@LINK',$url,$message);
                       if($_SERVER['HTTP_HOST'] != 'wooglobe.com') {
                           $file_to_attach = $_SERVER['DOCUMENT_ROOT'].$this->config->item('local_dir').'/uploads/'.$unique_key.'/documents/'.$unique_key. '_signed.pdf';

                       }else{
                           $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$unique_key.'/documents/'.$unique_key. '_signed.pdf';

                       }

                  if($leadData->contract_signed==0 && $dbData['video_verified'] ==1) {
                      //$result = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '', $file_to_attach, $unique_key);
                    if($leadData->simple_video == 0) {
                        $result = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '', null, $unique_key);
                    }else{
                        //$result = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '', $file_to_attach, $unique_key);
                    }
                      $datacontract['contract_signed'] = 1;
                      $this->db->where('unique_key', $leadData->unique_key);
                      $this->db->update('video_leads', $datacontract);
                  }

                    $notification = array();

                    $notification['send_datime'] = date('Y-m-d H:i:s');
                    $notification['lead_id'] = $leadData->id;
                    $notification['email_template_id'] = $emailData->id;
                    $notification['email_title'] = $emailData->title;
                    $notification['ids'] = json_encode($ids);

                    $this->db->insert('email_notification_history',$notification);

                    //$insert = $this->user->email_notification($notification);
                }
            }elseif ($already_accountemailData) {
                # code...
                $str = $already_accountemailData->message;
                    if($unique_key){
                        $subject = $already_accountemailData->subject.'(Important)-'.$unique_key;
                    }
                    else{
                        $subject = $already_accountemailData->subject;
                    }
                    $ids = array(
                        'users' => $leadData->client_id
                    );
                    
                    $message = dynStr($str,$ids);
                    $url = $this->data['root'].'login';
                    $message = str_replace('@LINK',$url,$message);
                if($_SERVER['HTTP_HOST'] != 'wooglobe.com') {
                    $file_to_attach = $_SERVER['DOCUMENT_ROOT'].$this->config->item('local_dir').'/uploads/'.$unique_key.'/documents/'.$unique_key.'_signed.pdf';
                }else{
                    $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$unique_key.'/documents/'.$unique_key.'_signed.pdf';

                }

                    //$result=$this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '',$file_to_attach,$unique_key);
                if($leadData->simple_video == 0) {
                    $result=$this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '',null,$unique_key);
                }else{
                    //$result=$this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '',$file_to_attach,$unique_key);
                }
                    $notification = array();

                    $notification['send_datime'] = date('Y-m-d H:i:s');
                    $notification['lead_id'] = $leadData->id;
                    $notification['email_template_id'] = $emailData->id;
                    $notification['email_title'] = $emailData->title;
                    $notification['ids'] = json_encode($ids);

                    $this->db->insert('email_notification_history',$notification);

            }
             /////////////ADDING SIGNED DOCUMENT TO S3 //////////////////////////////////////////
            if($_SERVER['HTTP_HOST'] != 'wooglobe.com') {
                $source_file_document = $_SERVER['DOCUMENT_ROOT'].$this->config->item('local_dir').'/uploads/'.$unique_key.'/documents/'.$unique_key.'_signed.pdf';
                $file_extension_document = 'pdf';
                $target_file_key_doc = $this->config->item('local_dir').'/uploads/'.$unique_key.'/documents/'.$unique_key.'_signed.pdf';
            }else{
                $source_file_document = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$unique_key.'/documents/'.$unique_key.'_signed.pdf';
                $file_extension_document = 'pdf';
                $target_file_key_doc = 'uploads/'.$unique_key.'/documents/'.$unique_key.'_signed.pdf';
            }

                    $url_document = $this->upload_file_s3_new('private',$source_file_document, $target_file_key_doc, $file_extension_document, FALSE);
                    $this->db->where('video_id', $id);
                    $this->db->update('raw_video', array('s3_document_url' => $url_document));
        }


        $flash = $this->sess->userdata('refferer');

        if (!empty($flash)) {
            $response['url'] = $flash;
        }
        echo json_encode($response);
        exit;

    }

    public function put_oject_s3_cmd ()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        if ($this->input->post())
        {
            $file_key    = $this->input->post('file-key');
            //$file_key    = 'uploads/WGA879541/raw_videos/tf31.mp4';
            //$file_key    = 'testupload/t31.mp4';
            $file_source = $this->input->post('file-source');
            $file_ext    = $this->input->post('file-ext');

            $post_params = http_build_query(['filename' => $file_source, 'keyname' => $file_key, 'ext' => $file_ext]);

            //$curl_cmd = 'curl -d "'.$post_params.'" -X POST '.base_url().'TestController/test_upload_multipart --insecure';
            $curl_cmd = 'curl -d "'.$post_params.'" -X POST '.base_url().'Videos/upload_multipart_video --insecure';

            //var_dump(shell_exec($curl_cmd));
            shell_exec($curl_cmd.' 2>&1;');
        }
    }

    public function upload_multipart_video ()
    {
        ini_set("memory_limit", "-1"); // for infinite memory limit
        set_time_limit(0);                     // for infinite time of execution

        if ($this->input->post())
        {
            $s3 = $this->get_s3_client();

            $filename = $this->input->post('filename');
            //$source_file    = 'D:\\testdir\\wga197205_1577960559.mp4';
            $keyname = $this->input->post('keyname');

            $bucket = S3_BUCKET;

            // Prepare the upload parameters.
            $uploader = new MultipartUploader($s3, $filename, [
                'bucket' => $bucket,
                'key'    => $keyname,
                'ACL' => 'public-read',
            ]);

            // Perform the upload.
            try {
                $result = $uploader->upload();
                echo "Upload complete: {$result['ObjectURL']}" . PHP_EOL;
            }
            catch (MultipartUploadException $e) {
                echo $e->getMessage() . PHP_EOL;
            }
        }
    }

    public function delete_video()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'videos', 'delete_video');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->video->getVideoById($id);

        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->sess->userdata('adminId');
        $dbData['deleted_at'] = date('Y-m-d H:i:s');
        $dbData['deleted_by'] = $this->sess->userdata('adminId');
        $dbData['deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update('videos', $dbData);

        echo json_encode($response);
        exit;

    }

    public function delete_videolead()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'videos', 'delete_lead');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video lead deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $dbData = $this->security->xss_clean($this->input->post());
        $leadData = $this->email->getLeadById($dbData['lead_id'], 'vl.*');
        $templateId = $this->deal->getTemplateId($dbData['lead_id']);
        $template = $this->app->getEmailTemplateByCode('contract_cancel');
        $ids = json_decode($templateId->ids, true);
        if ($leadData) {
            if (!empty($leadData->sr_uuid)) {
                include('./vendor/signrequest/src/AtaneNL/SignRequest/Client.php');
                include('./vendor/signrequest/src/AtaneNL/SignRequest/CreateDocumentResponse.php');
                $client = new \AtaneNL\SignRequest\Client($this->config->config['sr_token']);
                if (isset($leadData->docoment_uuid)) {
                    $cdr = $client->deleteSignRequestDocument($leadData->docoment_uuid);
                    action_add($leadData->id, 0, 0, $this->sess->userdata('adminId'), 1, 'Contract Cancel Email');
                    $subject = "WooGlobe Contract Cancel";
                    $cancel_message = $dbData['cancel_comments'];
                    $name = $leadData->first_name;
                    $message = dynStr($template->message, $ids);
                    $message = str_replace('@NAME', $name, $message);
                    $message = str_replace('@CANCELMESSAGE', $cancel_message, $message);
                    $sent = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
                }

            }
        }
        action_add($dbData['lead_id'], 0, 0, $this->sess->userdata('adminId'), 1, 'Deal Deleted');

        $result = $this->video->getVideoByLeadId($dbData['lead_id'], 'videos');

        if (!$result) {

            $response['code'] = 200;
            $response['message'] = '';
            $response['error'] = '';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $lead_id = $dbData['lead_id'];

        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->sess->userdata('adminId');
        $dbData['deleted_at'] = date('Y-m-d H:i:s');
        $dbData['deleted_by'] = $this->sess->userdata('adminId');
        $dbData['deleted'] = 1;
        $this->db->where('lead_id', $dbData['lead_id']);
        unset($dbData['lead_id']);
        $this->db->update('videos', $dbData);


        $result_lead = $this->lead->getLeadByIdAllStatus($lead_id);
        if (!$result_lead) {

            $response['code'] = 200;
            $response['message'] = '';
            $response['error'] = '';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $dbData1['updated_at'] = date('Y-m-d H:i:s');
        $dbData1['updated_by'] = $this->sess->userdata('adminId');
        $dbData1['deleted_at'] = date('Y-m-d H:i:s');
        $dbData1['deleted_by'] = $this->sess->userdata('adminId');
        $dbData1['deleted'] = 1;
        $this->db->where('id', $lead_id);
        $this->db->update('video_leads', $dbData1);
        $result_raw = $this->video->getVideoByLeadId($lead_id, 'raw_video');
        if (!$result_raw) {

            $response['code'] = 200;
            $response['message'] = '';
            $response['error'] = '';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $this->db->where('lead_id', $lead_id);
        $this->db->delete('raw_video');
        $videoid = $this->security->xss_clean($this->input->post('videoid'));

        if ($videoid) {
            $result_edited = $this->video->getEditedvideoByVideoId($videoid, 'edited_video');
            if ($result_edited) {
                $target_file_key = substr($result_edited->portal_url, 44);
                $deletes3file = $this->delete_file($target_file_key);
            }
            $this->db->where('video_id', $videoid);
            $this->db->delete('edited_video');
        }
        echo json_encode($response);
        exit;
    }

    public function update_mrss()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'videos', 'update_video');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('video_id'));
        $input = $this->input->post();
        $result = $this->video->getVideoById($id);

        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->sess->userdata('adminId');
        //$dbData['deleted_at'] = date('Y-m-d H:i:s');
        //$dbData['deleted_by'] = $this->sess->userdata('adminId');
        if (isset($input['is_mrss'])) {
            $dbData['mrss'] = 1;
            $dbData['mrss_categories'] = implode(',', $input['mrss_categories']);
        } else {
            $dbData['mrss'] = 0;
            $dbData['mrss_categories'] = 0;
        }
        if (isset($input['is_mrss_partner'])) {
            $data1['exclusive_to_partner'] = $input['mrss_partner'];

        } else {
            $data1['exclusive_to_partner'] = 0;
        }
        //$dbData['deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update('videos', $dbData);

        echo json_encode($response);
        exit;

    }

    public function get_video_sub_category()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Record found!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->video->getSubCategories('id,title', $id);
        $array = array('');
        $null = array('value' => '', 'text' => 'Choose...');
        $array[] = $null;
        foreach ($result->result() as $row) {

            $arr = array('value' => $row->id, 'text' => $row->title);
            $array[] = $arr;

        }
        $response['data'] = $array;


        echo json_encode($response);
        exit;

    }

    public function edited_video_upload()
    {
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Video Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $input = $this->input->post();
        $input_file = $_FILES['portal_thumb']['name'];

        $this->validation->set_rules('yt_video', 'Youtube Video', 'trim');
        $this->validation->set_rules('fb_video', 'Facebook Video', 'trim');
        if(isset($input['portal_video_check'])|| isset($input['chkbox-upload-single-video'])) {
            $this->validation->set_rules('portal_video', 'Portal Video', 'trim|required');
            $this->validation->set_message('required','This Field is required');
        }
        $this->validation->set_rules('yt_thumbnail_provided', 'Youtube Thumbnail', 'callback_yt_thumbnails');
        $this->validation->set_rules('fb_thumbnail_provided', 'Facebook Thumbnail', 'callback_fb_thumbnails');
        $this->validation->set_rules('portal_thumb', 'Portal Thumb', 'callback_portal_thumb');

        $this->validation->set_message('portal_thumb','Portal Thumbnail Field is required');


        if ($this->validation->run() == FALSE) {

            $fields = array('yt_video', 'fb_video','portal_thumb','portal_video');
            $errors = array();

            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }

            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

            echo json_encode($response);
            exit;
        }


        $dbData = array();
        if(isset($input['yt_video'])) {
            if ($input['yt_video']) {
                $dbData['yt_url'] = $input['yt_video'];
            }
        }
        if(isset($input['fb_video'])) {
            if ($input['fb_video']) {
                $dbData['fb_url'] = $input['fb_video'];
            }
        }
        //$dbData['portal_url'] = $input['portal_video'];
        $vid_edited = $input['video_id'];
        $lead_id = $this->video->getLeadByVideoId($vid_edited);
        $leadData = $this->deal->getDealById($lead_id->lead_id);
        // $this->portraitToLandscape($vid_edited);
        $portal_video_target_key = '';
        if(isset($input['portal_video'])){
            if($input['portal_video']) {
                $source_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $input['portal_video'];
                $file_extension = explode('.', $source_file);


                $file_extension = $file_extension[1];
                $target_file_key = S3_BASE_FOLDER . "/" . $input['portal_video'];
                $target_file_key = explode('/', $target_file_key);
                $edit_uniquekey = $leadData->unique_key;
                $portal_video_results = $this->db->query('SELECT portal_url FROM edited_video')->result();

                $portal_url = '';

                $result = '';
                foreach ($portal_video_results as $portal_video_result) {
                    $portal_url = $portal_video_result->portal_url;
                    $portal_arr = explode('/', $portal_url);
                    $portal_arr_keyvalue = '';
                    if (isset($portal_arr[5])) {
                        $portal_arr_keyvalue = $portal_arr[5];
                    }
                    if ($edit_uniquekey == $portal_arr_keyvalue) {
                        $portal_video_id_results = $this->db->query('SELECT video_id FROM `edited_video`WHERE `portal_url`= "' . $portal_url . '"')->result();
                        $portal_video_id = $portal_video_id_results[0]->video_id;
                        $portal_video_target_key = end($portal_arr);
                    }
                }
            }
        }
				
        if ($portal_video_target_key) {
            $target_file_key = 'uploads/' . $leadData->unique_key . '/edited_videos/' . $portal_video_target_key;
						
            $urlp = $this->upload_file_s3_new('public-read',$source_file, $target_file_key, $file_extension, false);
							
            if (!empty($_FILES['portal_thumb']['name'])) {

                $data = $this->upload_edited_thumbnail('portal_thumb', $leadData->unique_key, 'mrss');
                $source_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $data['url'];
                $file_extension = explode('.', $source_file);
                $file_extension = $file_extension[1];
                $target_file_key = S3_BASE_FOLDER . "/" . $data['url'];
                $target_file_key = explode('/', $target_file_key);
                $target_file_key = 'uploads/' . $leadData->unique_key . '/edited_videos/thumbnail/' . $target_file_key[count($target_file_key) - 1];
                $urlfb = $this->upload_file_s3_new('public-read',$source_file, $target_file_key, $file_extension, false);
                $dbData['portal_thumb'] = $urlfb;

            }
            if (!empty($_FILES['yt_thumb']['name'])) {

                $data = $this->upload_edited_thumbnail('yt_thumb', $leadData->unique_key, 'youtube');
                $dbData['yt_thumb'] = $data['url'];
            }

            if (!empty($_FILES['fb_thumb']['name'])) {

                $data = $this->upload_edited_thumbnail('fb_thumb', $leadData->unique_key, 'facebook');
                $dbData['fb_thumb'] = $data['url'];
            }
            $dbData['portal_url'] = $urlp;
            //$dbData['video_id'] = $portal_video_id;
            $this->db->where('video_id', $portal_video_id);
            $result = $this->db->update('edited_video', $dbData);
            $facebook_video_id_results = $this->db->query('SELECT facebook_id FROM `videos` WHERE `id`= "' . $vid_edited . '"')->result();
            if ($facebook_video_id_results) {
                $facebook_repub_status = 1;
                $update_facebook_repub = $this->db->query('UPDATE videos SET `facebook_repub`="' . $facebook_repub_status . '" WHERE `id`= "' . $vid_edited . '"');
            }
            $youtube_video_id_results = $this->db->query('SELECT youtube_id FROM `videos` WHERE `id`= "' . $vid_edited . '"')->result();
            if ($youtube_video_id_results) {
                $youtube_repub_status = 1;
                $update_facebook_repub = $this->db->query('UPDATE videos SET `youtube_repub`="' . $youtube_repub_status . '" WHERE `id`= "' . $vid_edited . '"');
            }
            $this->db->where('video_id',$input['video_id']);
            $this->db->delete('feed_video');
            if (isset($input['is_mrss']) && !empty($input['mrss_categories'])) {
                $data1['mrss'] = 1;
                $datamrss = array();
                foreach ($input['mrss_categories'] as $feed){
                    $datamrss[] =  array('feed_id'=>$feed, 'video_id'=>$input['video_id'], 'exclusive_to_partner'=>'0');
                }
                $this->db->insert_batch('feed_video', $datamrss);
            }
            else {
                $data1['mrss'] = 0;
                $data1['mrss_categories'] = 0;
            }
            if(isset($input['is_mrss_partner'])){
                if($input['is_mrss_partner'] == 1){
                    $ex_mrss_partner_id = $input['mrss_partner'];
                    $data = array();
                    foreach ($input['mrss_partner_cat_opt'] as $feed){
                        $data[] =  array('feed_id'=>$feed, 'video_id'=>$input['video_id'], 'exclusive_to_partner'=>$input['mrss_partner']);
                    }
                    $result = $this->db->insert_batch('feed_video', $data,'video_id');

                }
            }
            if(isset($input['not_mrss_partner'])){
                if($input['not_mrss_partner'] == 1){
                    $datant = array();
                    foreach ($input['all_mrss_cat'] as $feed){
                        $datant[] =  array('feed_id'=>$feed, 'video_id'=>$input['video_id'], 'exclusive_to_partner'=>'0');
                    }
                    $result = $this->db->insert_batch('feed_video', $datant,'video_id');

                }
            }
            $this->db->where('id', $vid_edited);
            $this->db->update('videos', $data1);
        } else {
            if (isset($_POST['portal_video'])) {
                if($_POST['portal_video']){
                    $target_file_key = 'uploads/' . $leadData->unique_key . '/edited_videos/' . $target_file_key[count($target_file_key) - 1];
                    $urlp = $this->upload_file_s3_new('public-read',$source_file, $target_file_key, $file_extension, false);
                    $dbData['portal_url'] = $urlp;						
                }
            }
            if (!empty($_FILES['portal_thumb']['name'])) {

                $data = $this->upload_edited_thumbnail('portal_thumb', $leadData->unique_key, 'mrss');
                $source_file = root_path($this->config->item('local_dir')) . '/' . $data['url'];
                //echo $source_file;
                $file_extension = explode('.', $source_file);
                $file_extension = $file_extension[1];
                $target_file_key = S3_BASE_FOLDER . "/" . $data['url'];
                $target_file_key = explode('/', $target_file_key);
                $target_file_key = 'uploads/' . $leadData->unique_key . '/edited_videos/thumbnail/' . $target_file_key[count($target_file_key) - 1];
                $urlfb = $this->upload_file_s3_new('public-read',$source_file, $target_file_key, $file_extension, false);
                $dbData['portal_thumb'] = $urlfb;

            }
            if (!empty($_FILES['yt_thumb']['name'])) {

                $data = $this->upload_edited_thumbnail('yt_thumb', $leadData->unique_key, 'youtube');
                $dbData['yt_thumb'] = $data['url'];
            }

            if (!empty($_FILES['fb_thumb']['name'])) {

                $data = $this->upload_edited_thumbnail('fb_thumb', $leadData->unique_key, 'facebook');
                $dbData['fb_thumb'] = $data['url'];
            }
            $dbDatavid = $input['video_id'];
            $this->db->where('video_id', $input['video_id']);
            $query = $this->db->get('edited_video');
            $this->db->query('DELETE FROM feed_video WHERE video_id = "'.$input['video_id'].'"');
            $data1 = array();
            if (isset($input['is_mrss']) && !empty($input['mrss_categories'])) {
                $data1['mrss'] = 1;
                $datamrss = array();
                foreach ($input['mrss_categories'] as $feed){
                    $datamrss[] =  array('feed_id'=>$feed, 'video_id'=>$input['video_id'], 'exclusive_to_partner'=>'0');
                }
                $result = $this->db->insert_batch('feed_video', $datamrss);
            }
            else {
                $data1['mrss'] = 0;
                $data1['mrss_categories'] = 0;
            }
            if(isset($input['is_mrss_partner'])){
                if($input['is_mrss_partner'] == 1){
                    $ex_mrss_partner_id = $input['mrss_partner'];
                    $data = array();
                    foreach ($input['mrss_partner_cat_opt'] as $feed){
                        $data[] =  array('feed_id'=>$feed, 'video_id'=>$input['video_id'], 'exclusive_to_partner'=>$input['mrss_partner']);
                    }
                    $result = $this->db->insert_batch('feed_video', $data);

                }
            }
            if(isset($input['not_mrss_partner'])){
                    $datant = array();
                    //foreach ($input['all_mrss_cat'] as $feed){
                    foreach ($input['all_mrss_partners'] as $feed){
                        $datant[] =  array('feed_id'=>$feed, 'video_id'=>$input['video_id'], 'exclusive_to_partner'=>'0');
                    }
                    $result = $this->db->insert_batch('feed_video', $datant);

            }
            $edvid=$dbDatavid;
            $this->db->where('id', $edvid);
            $this->db->update('videos', $data1);
            if ($query->num_rows() > 0) {
                $this->db->where('video_id', $input['video_id']);
                $result = $this->db->update('edited_video', $dbData);

            } else {
                $dbData['video_id'] = $input['video_id'];
                $result = $this->db->insert('edited_video', $dbData);
            }

        }

        if (!empty($_FILES['portal_thumb']['name'])) {
			
			
			$wga_dir = root_path() .'/uploads/'.$leadData->unique_key;
			//echo $wga_dir;
            $dirs_to_be_cleared_paths = array(
											$wga_dir . '/raw_videos/',
											$wga_dir . '/edited_videos/mrss/',
											$wga_dir . '/edited_videos/mrss/thumbnail/'										
									    );

			foreach ($dirs_to_be_cleared_paths as $dir_path) {
                //echo $dir_path;

				$dir_files_list = scandir(realpath($dir_path));

				foreach ($dir_files_list as $file) {
					@unlink($dir_path.$file);		
				}
			}								
        }
		
        $result = $this->video->getVideoById($vid_edited);
        action_add($result->lead_id, $result->id, 0, $this->sess->userdata('adminId'), 1, 'Edited files uploaded');
        if ($result) {

            $lead_id = $this->video->getLeadByVideoId($vid_edited);

            $lead_data = array(
                'uploaded_edited_videos' => 1,
                'edited_datetime' => date('Y-m-d H:i:s')
            );


            $this->db->where('id', $lead_id->lead_id);
            $this->db->update('video_leads', $lead_data);


            //$response['code'] = 201;
            $response['url'] = base_url().'deal-detail/'.$result->lead_id;
           /* echo '<pre>';
            print_r($response);
            exit;*/
            echo json_encode($response);
            exit;
        } else {
            $response['code'] = 201;
            $response['message'] = 'Something is going wrong!';

            echo json_encode($response);
            exit;
        }


    }
	
    function is_required_portal_video()
    {
      /*  $dbData['video_id'] = $_POST['video_id'];
        $lead_id = $this->video->getLeadByVideoId($dbData['video_id']);
        $leadData = $this->deal->getDealById($lead_id->lead_id);
        $path = $_SERVER['DOCUMENT_ROOT'] . "/uploads/$leadData->unique_key/edited_videos/mrss/";

        $files = glob($path . "*.{avi,mp4,wmv,flv,mkv,AVI,MP4,FLV,MKV}", GLOB_BRACE); // get all file names

        if (!empty($files)) {

            foreach ($files as $file) { // iterate files
                if (is_file($file)) {
                    return false;
                }

            }
        } else {

            $this->form_validation->set_message('Please Provide MRSS Thumbnail and Video');
            return true;
        }*/
        $this->db->where('portal_url',Null,FALSE);
        $query = $this->db->get('edited_video');

    }

    public function yt_thumbnails()
    {

        if (isset($_POST['yt_thumbnail_provided'])) {

            if (!empty($_FILES['yt_thumb']['name']) && !empty($_POST['yt_video'])) {

                return true;
            } else {

                $this->form_validation->set_message('Please Provide Youtube Thumbnail and Video');

                return false;
            }

        } else {

            return true;
        }


    }

    public function fb_thumbnails()
    {

        if (isset($_POST['fb_thumbnail_provided'])) {
            if (!empty($_FILES['fb_thumb']['name']) && $_POST['fb_video']) {
                return true;
            } else {

                $this->form_validation->set_message('Please Provide Facebook Thumbnail and Video');

                return false;
            }

        } else {
            return true;
        }


    }

    public function portal_thumb()
    {
        if (!empty($_FILES['portal_thumb']['name'])) {
            return true;
        } else {

            $this->form_validation->set_message('portal_thumb','Please Provide Portal Thumbnail');

            return false;
        }




    }

    public function upload_video()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $ukey = $this->input->get('key');
        $directory = $this->input->get('type');
        include('./app/third_party/class.fileuploader.php');
        if($directory == 'facebook'){
            $FileUploader = new FileUploader('file-fb', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/edited_videos/'.$directory.'/',
            ));
        }
        else if($directory == 'youtube'){
            $FileUploader = new FileUploader('file-yt', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/edited_videos/'.$directory.'/',
            ));
        }
        else if($directory == 'mrss'){
            $FileUploader = new FileUploader('file-mrss', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/edited_videos/'.$directory.'/',
            ));
        }
        // call to upload the files
        $upload = $FileUploader->upload();

        if ($upload['isSuccess']) {
            // get the uploaded files
            $files = $upload['files'];
            foreach ($upload['files'] as $i => $file) {
                $upload['files'][$i]['video'] = 'uploads/' . $ukey . '/edited_videos/'.$directory.'/'. $file['name'];
                $upload['files'][$i]['title_new'] = explode('.', $file['name'])[0];
            }
            $upload['code'] = 200;
            $upload['url'] = "uploads/$ukey/edited_videos/$directory/" . $file['name'];
            $upload['message'] = "Video Upload Sucessfully";
        }
        else{
            $upload['code'] = 201;
            $upload['url'] = "";
            $upload['message'] = "Something Went Wrong";
        }



        echo json_encode($upload);
        exit;

    }
    public function watermark_upload_video()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $ukey = $this->input->get('key');
        $directory = $this->input->get('type');
        include('./app/third_party/class.fileuploader.php');
        if($directory == 'facebook'){
            $FileUploader = new FileUploader('file-fb', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/edited_videos/'.$directory.'/',
            ));
        }
        else if($directory == 'youtube'){
            $FileUploader = new FileUploader('file-yt', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/edited_videos/'.$directory.'/',
            ));
        }
        else if($directory == 'mrss'){
            $FileUploader = new FileUploader('file-mrss', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/edited_videos/'.$directory.'/',
            ));
        }else if($directory == 'watermark'){
            if(!file_exists('./../uploads/' . $ukey . '/watermark_video')){
                mkdir('./../uploads/' . $ukey . '/watermark_video',0777);
            }
            $FileUploader = new FileUploader('file-mrss', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/watermark_video/',
            ));
        }
        // call to upload the files
        $upload = $FileUploader->upload();

        if ($upload['isSuccess']) {
            // get the uploaded files
            if($directory == 'watermark'){
                $files = $upload['files'];
                foreach ($upload['files'] as $i => $file) {
                    $upload['files'][$i]['video'] = 'uploads/' . $ukey . '/watermark_video/'. $file['name'];
                    $upload['files'][$i]['title_new'] = explode('.', $file['name'])[0];
                }
                $upload['code'] = 200;
                $upload['url'] = "uploads/$ukey/watermark_video/" . $file['name'];
                $s3_url = $this->upload_file_s3_new('public-read',"./../uploads/$ukey/watermark_video/" . $file['name'],"uploads/$ukey/watermark_video/" . $file['name'],explode('.', $file['name'])[1],true);
                $upload['s3_url'] = $s3_url;
                $this->db->where('unique_key',$ukey);
                $this->db->update('watermark_videos',array('s3_url'=>$s3_url));
            }else{
                $files = $upload['files'];
                foreach ($upload['files'] as $i => $file) {
                    $upload['files'][$i]['video'] = 'uploads/' . $ukey . '/edited_videos/'.$directory.'/'. $file['name'];
                    $upload['files'][$i]['title_new'] = explode('.', $file['name'])[0];
                }
                $upload['code'] = 200;
                $upload['url'] = "uploads/$ukey/edited_videos/$directory/" . $file['name'];
            }

            $upload['message'] = "Video Upload Sucessfully";
        }
        else{
            $upload['code'] = 201;
            $upload['url'] = "";
            $upload['message'] = "Something Went Wrong";
        }



        echo json_encode($upload);
        exit;

    }

    public function story_upload_video()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $ukey = $this->input->get('key');
        $directory = 'story_video';
        include('./app/third_party/class.fileuploader.php');
        if($directory == 'facebook'){
            $FileUploader = new FileUploader('file-fb', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/edited_videos/'.$directory.'/',
            ));
        }
        else if($directory == 'youtube'){
            $FileUploader = new FileUploader('file-yt', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/edited_videos/'.$directory.'/',
            ));
        }
        else if($directory == 'mrss'){
            $FileUploader = new FileUploader('file-mrss', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/edited_videos/'.$directory.'/',
            ));
        }else if($directory == 'watermark'){
            if(!file_exists('./../uploads/' . $ukey . '/watermark_video')){
                mkdir('./../uploads/' . $ukey . '/watermark_video',0777);
            }
            $FileUploader = new FileUploader('file-mrss', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/watermark_video/',
            ));
        }else if($directory == 'story_video'){
            if(!file_exists('./../uploads/' . $ukey )){
                mkdir('./../uploads/' . $ukey ,0777);
            }
            if(!file_exists('./../uploads/' . $ukey . '/story_based')){
                mkdir('./../uploads/' . $ukey . '/story_based',0777);
            }
            $FileUploader = new FileUploader('story_content', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/story_based/',
            ));
        }
        // call to upload the files
        $upload = $FileUploader->upload();


        if ($upload['isSuccess']) {
            // get the uploaded files
            if($directory == 'watermark'){
                $files = $upload['files'];
                foreach ($upload['files'] as $i => $file) {
                    $upload['files'][$i]['video'] = 'uploads/' . $ukey . '/watermark_video/'. $file['name'];
                    $upload['files'][$i]['title_new'] = explode('.', $file['name'])[0];
                }
                $upload['code'] = 200;
                $upload['url'] = "uploads/$ukey/watermark_video/" . $file['name'];
                $s3_url = $this->upload_file_s3_new('public-read',"./../uploads/$ukey/watermark_video/" . $file['name'],"uploads/$ukey/watermark_video/" . $file['name'],explode('.', $file['name'])[1],true);
                $upload['s3_url'] = $s3_url;
                $this->db->where('unique_key',$ukey);
                $this->db->update('watermark_videos',array('s3_url'=>$s3_url));
            }else if($directory == 'story_video'){
                $leadData = $this->db->query('SELECT * FROM video_leads WHERE unique_key = "'.$ukey.'"')->row();
                if(!empty($leadData->stroy_s3_url)){
                    $url = end(explode('.com/',$leadData->stroy_s3_url));
                    $this->delete_file($url);

                }
                $files = $upload['files'];
                foreach ($upload['files'] as $i => $file) {
                    $upload['files'][$i]['video'] = 'uploads/' . $ukey . '/story_based/'. $file['name'];
                    $upload['files'][$i]['title_new'] = explode('.', $file['name'])[0];
                }
                $upload['code'] = 200;
                $upload['url'] = "uploads/$ukey/story_based/" . $file['name'];
                $s3_url = $this->upload_file_s3_new('public-read',"./../uploads/$ukey/story_based/" . $file['name'],"uploads/$ukey/story_based/" . $file['name'],explode('.', $file['name'])[1],true);
                $upload['s3_url'] = $s3_url;
                $this->db->where('unique_key',$ukey);
                $this->db->update('video_leads',array('stroy_s3_url'=>$s3_url,'is_story_content'=>1));
            }else{
                $files = $upload['files'];
                foreach ($upload['files'] as $i => $file) {
                    $upload['files'][$i]['video'] = 'uploads/' . $ukey . '/edited_videos/'.$directory.'/'. $file['name'];
                    $upload['files'][$i]['title_new'] = explode('.', $file['name'])[0];
                }
                $upload['code'] = 200;
                $upload['url'] = "uploads/$ukey/edited_videos/$directory/" . $file['name'];
            }

            $upload['message'] = "Video Upload Sucessfully";
        }
        else{
            $upload['code'] = 201;
            $upload['url'] = "";
            $upload['message'] = "Something Went Wrong";
        }



        echo json_encode($upload);
        exit;

    }
    public function story_upload_thumb()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $ukey = $this->input->get('key');
        $directory = 'story_video';
        include('./app/third_party/class.fileuploader.php');
        if($directory == 'story_video'){
            if(!file_exists('./../uploads/' . $ukey )){
                mkdir('./../uploads/' . $ukey ,0777);
            }
            if(!file_exists('./../uploads/' . $ukey . '/story_based')){
                mkdir('./../uploads/' . $ukey . '/story_based',0777);
            }
            $FileUploader = new FileUploader('story_content_thumb', array(
                'title' => strtolower($ukey) . '_' . time(),
                'uploadDir' => './../uploads/' . $ukey . '/story_based/',
                'extensions' => array('jpeg','jpg','png'),
            ));
        }
        // call to upload the files
        $upload = $FileUploader->upload();


        if ($upload['isSuccess']) {
            // get the uploaded files
            if($directory == 'story_video'){
                $leadData = $this->db->query('SELECT * FROM video_leads WHERE unique_key = "'.$ukey.'"')->row();
                if(!empty($leadData->s3_url_story_thumb)){
                    $url = end(explode('.com/',$leadData->stroy_s3_url));
                    $this->delete_file($url);

                }
                $files = $upload['files'];
                foreach ($upload['files'] as $i => $file) {
                    $upload['files'][$i]['video'] = 'uploads/' . $ukey . '/story_based/'. $file['name'];
                    $upload['files'][$i]['title_new'] = explode('.', $file['name'])[0];
                }
                $upload['code'] = 200;
                $upload['url'] = "uploads/$ukey/story_based/" . $file['name'];
                $s3_url = $this->upload_file_s3_new('public-read',"./../uploads/$ukey/story_based/" . $file['name'],"uploads/$ukey/story_based/" . $file['name'],explode('.', $file['name'])[1],true);
                $upload['s3_url'] = $s3_url;
                $this->db->where('unique_key',$ukey);
                $this->db->update('video_leads',array('s3_url_story_thumb'=>$s3_url));
            }else{
                $files = $upload['files'];
                foreach ($upload['files'] as $i => $file) {
                    $upload['files'][$i]['video'] = 'uploads/' . $ukey . '/edited_videos/'.$directory.'/'. $file['name'];
                    $upload['files'][$i]['title_new'] = explode('.', $file['name'])[0];
                }
                $upload['code'] = 200;
                $upload['url'] = "uploads/$ukey/edited_videos/$directory/" . $file['name'];
            }

            $upload['message'] = "Thumbnail Upload Sucessfully";
        }
        else{
            $upload['code'] = 201;
            $upload['url'] = "";
            $upload['message'] = "Something Went Wrong";
        }



        echo json_encode($upload);
        exit;

    }
    public function new_raw_upload_video()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $ukey = $this->input->get('key');
        $directory = 'raw_videos';
        include('./app/third_party/class.fileuploader.php');
        $FileUploader = new FileUploader('raw_new', array(
            'title' => strtolower($ukey) . '_' . time(),
            'uploadDir' => './../uploads/' . $ukey . '/raw_videos/',
        ));

        // call to upload the files
        $upload = $FileUploader->upload();


        if ($upload['isSuccess']) {
            // get the uploaded files

                $files = $upload['files'];
                foreach ($upload['files'] as $i => $file) {
                    $upload['files'][$i]['video'] = 'uploads/' . $ukey . '/raw_videos/'. $file['name'];
                    $upload['files'][$i]['title_new'] = explode('.', $file['name'])[0];
                }
                $upload['code'] = 200;
                $upload['url'] = "uploads/$ukey/raw_videos/" . $file['name'];
                $query = "SELECT vl.id,v.id vid,vl.status FROM `video_leads` vl LEFT JOIN videos v ON v.lead_id = vl.id  WHERE vl.unique_key = '".$ukey."'";
                $result = $this->db->query($query);
                $lead = $result->row();
                $s3_url = null;
                if($lead->status == 6 || $lead->status == 7 || $lead->status == 8){
                    $s3_url = $this->upload_file_s3_new('public-read',"./../uploads/$ukey/raw_videos/" . $file['name'],"uploads/$ukey/raw_videos/" . $file['name'],explode('.', $file['name'])[1],true);
                }
                $upload['s3_url'] = $s3_url;

                $dbData['lead_id'] = $lead->id;
                $dbData['video_id'] = $lead->vid;
                $dbData['url'] = $upload['url'];
                $dbData['s3_url'] = $upload['s3_url'];
                $this->db->insert('raw_video',$dbData);
                /*$this->db->where('unique_key',$ukey);
                $this->db->update('watermark_videos',array('s3_url'=>$s3_url));*/


            $upload['message'] = "Video Upload Sucessfully";
        }
        else{
            $upload['code'] = 201;
            $upload['url'] = "";
            $upload['message'] = "Something Went Wrong";
        }



        echo json_encode($upload);
        exit;

    }
    public function original_files($id)
    {
        auth();
        role_permitted(false, 'videos', 'original_files');
        $this->data['title'] = 'Videos Original Files';
        $this->data['id'] = $id;
        $this->data['content'] = $this->load->view('videos/files', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }

    public function get_original_files($id)
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'videos', 'original_files');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->get());
        $search = '';
        $orderby = '';
        $start = 0;
        $limit = 0;
        if (isset($params['search'])) {
            $search = $params['search']['value'];
        }
        if (isset($params['start'])) {
            $start = $params['start'];
        }
        if (isset($params['length'])) {
            $limit = $params['length'];
        }

        if (isset($params['order'])) {
            $orderby = $params['columns'][$params['order'][0]['column']]['name'] . ' ' . $params['order'][0]['dir'];
        }

        $result = $this->video->getAllVideosOriginalFiles($id, 'rv.id,rv.video_id,rv.url,v.title', $search, $start, $limit, $orderby, $params['columns']);
        $resultCount = $this->video->getAllVideosOriginalFiles($id);
        $response = array();
        $data = array();
        $i = 1;
        foreach ($result->result() as $row) {
            $r = array();
            $r[] = $i;
            $r[] = '<a title="Play Video" href="javascript:void(0);" class="play-files" data-id="' . $row->id . '">' . $row->title . '</a>';


            $links = '<a title="Play Video" href="javascript:void(0);" class="play-files" data-id="' . $row->id . '"><i class="material-icons">&#xE04A;</i></a> ';


            $r[] = $links;

            $data[] = $r;
            $i++;
        }
        $response['code'] = 200;
        $response['message'] = 'Listing';
        $response['error'] = '';
        $response['data'] = $data;
        $response['recordsTotal'] = $resultCount->num_rows();
        $response['recordsFiltered'] = $resultCount->num_rows();
        echo json_encode($response);
        exit;
    }

    public function get_file()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'videos', 'original_files');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video found!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->video->getFileById($id, 'rv.url,v.title');
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';

        } else {

            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }

    public function delete_raw_file()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'videos', 'can_delete');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video File Deleted successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $url = $this->security->xss_clean($this->input->post('url'));

        $this->db->where('id', $id);
        $this->db->delete('raw_video');
        @unlink('./../' . $url);
        echo json_encode($response);
        exit;

    }

    /**
     * Reject Video and delete all raw files and send email to client and move deal to information pending
     */
    public function reject_videos()
    {
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video File Deleted successfully!';
        $dbData = $this->security->xss_clean($this->input->post());
        $id = $dbData['lead_id'];
        $videoid = $dbData['video_id'];
        $rejct_comments = $dbData['reject_comments'];
        $email_send = $dbData['email_send'];
        action_add($id, $videoid, 0, $this->sess->userdata('adminId'), 1, 'Video Rejected');
        $rawVideos = $this->video->getRawVideoById1($videoid);
        $assignFiles = array();
        $uid = '';
        foreach ($rawVideos as $row) {
            $uid = explode('/', $row->url);
            if(isset($uid[1])){
                $uid = $uid[1];
            }else{
                $uid ='';
            }


        }
        if(!empty($uid)){

            $allFiles = scandir($_SERVER['DOCUMENT_ROOT'] . '/' . "uploads/$uid/raw_videos/");

            $allFiles = array_slice($allFiles, 2);
            foreach ($allFiles as $file) {

                unlink("../uploads/$uid/raw_videos/" . $file);
            }
        }
        $lead_data = $this->lead->getLeadByIdAllStatus($id);
        $templateId = $this->deal->getTemplateId($id);
        $template = $this->app->getEmailTemplateByCode('video_reject');
        $ids = json_decode($templateId->ids, true);
        action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Video Reject Email');
        $subject = $template->subject;
        $message = dynStr($template->message, $ids);
        $name = $lead_data->first_name;
        $slug = $lead_data->slug;
        $url = $this->data['root'].'video_reject_upload/'.$slug;
        $url = preg_replace('/\s/', '', $url);
        $message = str_replace('@NAME', $name, $message);
        $message = str_replace('@REJECTMESSAGE', $rejct_comments, $message);
        $message = str_replace('@LINK', $url, $message);
        $email =$lead_data->email;
        if($email_send == 'not send'){
            $email = 'viral@wooglobe.com';
            //$email = 'maliks_usman786@yahoo.com';
        }
        $sent = $this->email($email, $lead_data->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

        $data = array(
            'load_view' => 5,
            'reject_comments' => $rejct_comments,
        );
        $this->db->where('id', $id);
        unset($dbData['lead_id']);
        unset($dbData['video_id']);
        unset($dbData['reject_comments']);
        $this->db->update('video_leads', $data);

        $this->db->where('lead_id', $id);
        $this->db->delete('raw_video');

        echo json_encode($response);
        exit;
    }
    public function reject_videos2()
    {

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video File Deleted successfully!';
        $dbData = $this->security->xss_clean($this->input->post());
        $id = $dbData['lead_id'];
        $videoid = $dbData['video_id'];

        $rawVideos = $this->video->getRawVideoById1($videoid);
        $assignFiles = array();
        $uid = '';
        /*foreach ($rawVideos as $row) {
            $uid = explode('/', $row->url);
            if(isset($uid[1])){
                $uid = $uid[1];
            }else{
                $uid ='';
            }


        }*/

        $lead_data = $this->lead->getLeadByIdAllStatus($id);

        $data = array(
            'load_view' => 5,
        );
        $this->db->where('id', $id);
        unset($dbData['lead_id']);
        unset($dbData['video_id']);
        $this->db->update('video_leads', $data);

        //$this->db->where('lead_id', $id);
        //$this->db->delete('raw_video');

        echo json_encode($response);
        exit;
    }
    public function mrss_partner(){
        $response = array();
        $mrss_partner_id = $this->security->xss_clean($this->input->post('id'));

        if (gettype($mrss_partner_id) == 'array') {
            $result = $this->mrss->getMrssPartners2(implode(',',$mrss_partner_id))->result_array();
        }
        else {
            $result = $this->mrss->getPartnerData($mrss_partner_id);
        }

        if($result){
            $response['code'] = 200;
            $response['data'] = $result;
        }else{
            $response['code'] = 201;
            $response['message'] = 'No partner found!';
        }
        echo json_encode($response);
        exit;
    }
    public function mrss_partner2(){
        $response = array();
        $mrss_partner_id = $this->security->xss_clean($this->input->post('id'));

        if (gettype($mrss_partner_id) == 'array') {
            $result = $this->mrss->getMrssPartners2(implode(',',$mrss_partner_id),2)->result_array();
        }
        else {
            $result = $this->mrss->getPartnerData($mrss_partner_id);
        }

        if($result){
            $response['code'] = 200;
            $response['data'] = $result;
        }else{
            $response['code'] = 201;
            $response['message'] = 'No partner found!';
        }
        echo json_encode($response);
        exit;
    }
    public function moveFiles()
    {
        $query = 'SELECT * FROM callback';
        $results = $this->db->query($query)->result_array();


        foreach ($results as $result) {

            $data = json_decode($result['data']);
            //$lead_id = $data->document->uuid;
            $id = $result['id'];
            $timestamp = $data->timestamp;
            print "<br>";
            $datatime=date("Y-m-d", strtotime($timestamp));
            if ($datatime > '2019-10-22') {
                echo 'greater than';
            }else{
                print_r($id);
                print "<br>";
                print_r($datatime);
                echo 'Less than';
                $result=$this->db->query('DELETE FROM callback WHERE id = '.$id.';');
                print_r($result);
            }
          //  print_r($timestamp);
          //  print_r();
            //print_r( strtotime($timestamp));
        }
    }
    public function edited_backup(){
        $client = $this->get_s3_client();
   /* $this->db->query("SET SESSION group_concat_max_len = 1000000");
        $edited_vid_id_query =$this->db->query("SELECT GROUP_CONCAT(DISTINCT video_id ORDER BY video_id ASC SEPARATOR ',') AS vid FROM edited_video")->result();
        $ed_id= $edited_vid_id_query[0]->vid;*/

        //$lead_id_query =$this->db->query("SELECT GROUP_CONCAT(DISTINCT lead_id ORDER BY lead_id WHERE id = ".$ed_id." ASC SEPARATOR ',') AS vid FROM videos")->result();*/
       // $vid_ids =$this->db->query('SELECT video_leads.id as lead_id,video_leads.unique_key as unique_key,videos.id as vid,edited_video.video_id as eid FROM video_leads INNER JOIN edited_videos ON video_leads.id = videos.lead_id INNER JOIN videos ON videos.id = edited_video.video_id')->result();
      /*  print "<pre>";
        print_r($lead_id_query);*/






    /*print "<pre>";
    print_r('SELECT video_leads.id as lead_id,video_leads.unique_key as unique_key,videos.id as vid FROM video_leads INNER JOIN videos ON video_leads.id = videos.lead_id  WHERE video_leads.status =6 AND video_leads.uploaded_edited_videos = 1 AND video_leads.deleted = 0 AND NOT IN ('.$ed_id.')');
        print_r( $edited_vid_id_query[0]->vid);
       $vid_ids =$this->db->query('SELECT video_leads.id as lead_id,video_leads.unique_key as unique_key,videos.id as vid FROM video_leads INNER JOIN videos ON video_leads.id = videos.lead_id  WHERE video_leads.status =6 AND video_leads.uploaded_edited_videos = 1 AND video_leads.deleted = 0 AND videos.id NOT IN ('.$ed_id.')')->result();*/

        $vid_ids =$this->db->query('SELECT video_leads.id as lead_id,video_leads.unique_key as unique_key,videos.id as vid FROM video_leads INNER JOIN videos ON video_leads.id = videos.lead_id  WHERE video_leads.status =6 AND video_leads.uploaded_edited_videos = 1 AND video_leads.deleted = 0')->result();

       foreach ($vid_ids as $vid_id){
            $editedunique=$vid_id->unique_key;
            $edit_vid_id=$vid_id->vid;
           $youtube_video = '';
           $facebook_video='';
            print "<pre>";
            print_r($edit_vid_id);

            $dir = "/var/www/html/uploads/".$editedunique."/edited_videos/facebook";

// Open a directory, and read its contents
            if (is_dir($dir)){
                if ($dh = opendir($dir)){
                    while (($file = readdir($dh)) !== false){
                        if( strpos($file, '.mp4') !== false || strpos($file, '.mov') !== false ){
                            $facebook_video="uploads/".$editedunique."/edited_videos/facebook/".$file;
                            print "<pre>";
                            print_r($facebook_video);
                        }
                    }
                    closedir($dh);
                }
            }
            $diryt = "/var/www/html/uploads/".$editedunique."/edited_videos/youtube";

// Open a directory, and read its contents
           if (is_dir($diryt)){
                if ($dh = opendir($diryt)){
                    while (($file = readdir($dh)) !== false){
                        if( strpos($file, '.mp4') !== false || strpos($file, '.mov') !== false ){
                            $youtube_video="uploads/".$editedunique."/edited_videos/youtube/".$file;
                            print "<pre>";
                            print_r($youtube_video);
                        }
                    }
                    closedir($dh);
                }
            }

           $portal_update =$this->db->query('UPDATE edited_video
			SET fb_url="'.$facebook_video.'",yt_url="'.$youtube_video.'"
			WHERE video_id = "'.$edit_vid_id.'"');
            print_r($portal_update);
        }
     /* foreach ($vid_ids as $vid_id){
           $objects = $client->ListObjects(array('Bucket'=>S3_BUCKET, 'Prefix'=>'uploads/'.$vid_id->unique_key));
           $objectarray=$objects->toArray();
           if(isset($objectarray['Contents'])){
               $data = $objectarray['Contents'];
               foreach ($data as $obj){
                   $objsplit = preg_split("#/#", $obj['Key']);
                   print"<pre>";
                   print_r($objsplit);
                   if(isset($objsplit[3])){
                       if($objsplit[3] == 'thumbnail'){
                           $portal_thumb = $obj['Key'];
                           print_r($portal_thumb);
                       }else if($objsplit[3] == 'edited_videos' || $objsplit[2] == 'edited_videos'){
                           $portal_video = $obj['Key'];
                           print_r($portal_video);
                           print"</pre>";
                       }
                   }
               }
           }
           $portal_update =$this->db->query('INSERT INTO `edited_video` (`video_id`,`portal_url`,`portal_thumb`)
			VALUES ("'.$vid_id->vid.'","'.$portal_video.'","'.$portal_thumb.'");');
           print_r($portal_update);
        }*/
       // $portal_fb_thumbs = $this->db->query('SELECT fb_thumb FROM edited_video')->result();
       /* $portal_yt_thumbs = $this->db->query('SELECT yt_thumb FROM edited_video')->result();



            foreach ($portal_yt_thumbs as $portal_yt_thumb){
                $unique = '';
                $led_id = '';
                $vid_id = '';
               $yt=$portal_yt_thumb->yt_thumb;
                $ytsplit = preg_split("#/#", $yt);
                print_r("<pre>");
                if(isset($ytsplit[1])){
                    $unique = $ytsplit[1];
                }echo "<br>unique:".$unique.'<br>';
                echo 'SELECT id FROM video_leads WHERE unique_key ="'.$unique.'"';
                $lead_id =$this->db->query('SELECT id FROM video_leads WHERE unique_key ="'.$unique.'"')->result();
                if(isset($lead_id[0])){
                    $led_id= $lead_id[0]->id;
                }echo "<br>lead id:".$led_id.'<br>';
                echo 'SELECT id FROM videos WHERE lead_id ="'.$led_id.'"';
                if($led_id==""){
                    $portal_update =$this->db->query('UPDATE edited_video
			SET video_id=""
			WHERE yt_thumb = "'.$yt.'"');
                    continue;}
                $vid_id_query =$this->db->query('SELECT id FROM videos WHERE lead_id ="'.$led_id.'"')->result();
                if(isset($vid_id_query[0])){
                    $vid_id= $vid_id_query[0]->id;
                }
                echo "<br>video id:";print_r($vid_id);
              $objects = $client->ListObjects(array('Bucket'=>S3_BUCKET, 'Prefix'=>'uploads/'.$unique));
                $objectarray=$objects->toArray();
                if(isset($objectarray['Contents'])){
                $data = $objectarray['Contents'];
                    foreach ($data as $obj){
                        $objsplit = preg_split("#/#", $obj['Key']);
                        print"<pre>";
                        print_r($objsplit);
                        if(isset($objsplit[3])){
                            if($objsplit[3] == 'thumbnail'){
                                print_r($obj['Key']);
                                print"</pre>";
                                $portal_thumb = $obj['Key'];
                            }else{
                                $portal_video = $obj['Key'];
                            }
                        }
                    }
                }
                $portal_update =$this->db->query('UPDATE edited_video
			SET video_id="'.$vid_id.'",portal_url="'.$portal_video.'",portal_thumb="'.$portal_thumb.'"
			WHERE yt_thumb = "'.$yt.'"');
                print_r($portal_update);
        }*/

    }

    public function simple_video_signed_pdf($full_name,$email,$phone,$country,$state,$city,$address,$zip = "54000",$date = null,$revenue_share = "50",$unique_key = "WGA375614",$data = null,$data_log = null,$api_result = null){

        $signimgpath= root_path().'/uploads/'.$unique_key.'/documents/'.$unique_key.'.png';

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
        $pdf->SetMargins(15,15,15,true);
        //$font_size = $pdf->pixelsToUnits('20');
// add a page
       $pdf->AddPage('P','','A4');

        //$pdf->writeHTML($html);

        /*$fontname = TCPDF_FONTS::addTTFfont('calibri.ttf', 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 12);*/
// add a page
      // $data = [];

       //$html = $this->load->view('pdf/simple_video_aggrement',$data, true);
        //$html = '<p>This is an A4 document.</p>';
        //$pdf->SetFont ('Helvetica', '', $font_size , '', 'default', true );
        $html = '
<style>
        h1.heading {
            text-align: center;
        } 

    </style>
    <BR><BR><BR><BR>
        <h1 class="heading"><FONT FACE="Helvetica Neue, serif" SIZE="28">WooGlobe Content Agreement</FONT></h1>

<p STYLE="margin-bottom: 0in;text-align: justify;">
    
    <FONT COLOR="#4473c4" SIZE="11"><BR>TERMS (“MAIN TERMS”)</FONT>
    <FONT COLOR="#000000" SIZE="10"><BR><SPAN STYLE="background: #ffffff">This is an agreement made between WooGlobe Ltd.  of 16 Weir Road, Bexley, Kent, DA5 1BJ, UK and the “Assignor” (Named in your Submission Form), that states:</SPAN></FONT>  
    <FONT COLOR="#000000" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">AGENCY: WooGlobe Ltd. of 16 Weir Road, Bexley, Kent, DA5 1BJ, UK (“the Agency” “us” “our” or “we” which expression shall be deemed to include the Agency’s successors in title, licensees and assigns)</SPAN></FONT>
    <FONT COLOR="#000000" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">LICENSOR: (“the Assignor”, “Assignor”, “Licensor”, “the Licensor”, “Submitter”, “you” or “your”)</SPAN></FONT>
    <FONT COLOR="#000000" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">TERRITORY: The universe (“the Territory”)</SPAN></FONT>
    <FONT COLOR="#000000" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">MEDIA: The Assignor exclusively appoints the Agency to market, syndicate, sell and monetise the Content for distribution to all media now known or hereafter invented. If other media outlets make contact with the Contributor regarding the Material, the Contributor agrees to put them in touch with the Agency so that it can negotiate further sales.</SPAN></FONT>
    <FONT COLOR="#000000" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">DURATION: This agreement becomes valid and effective from the date of signing in writing or using an electronic signature or sending digital confirmation of agreement. The exclusive rights contained herein are valid for the full period of copyright and all renewals, reversions, revivals and extensions and thereafter in perpetuity to the extent permitted by law (unless terminated in accordance with 3.1-3.2 of General Terms).</SPAN></FONT>
    <FONT COLOR="#000000" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">AGREEMENT: This Agreement consists of these Main Terms and the attached General Terms of Agreement. These Main Terms along with the General Terms of Agreement below set out the full terms and conditions of this exclusive management agreement (“Agreement”) and forms a binding contract between the parties. By signing or sending digital confirmation to this Agreement, you agree to be bound by the terms of this Agreement.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="16"><BR><BR><BR>General Terms of Agreement</FONT>
    <FONT COLOR="#000000" SIZE="10"><BR><SPAN STYLE="background: #ffffff">This Agreement shall comprise of the Main Terms and these General Terms of Agreement.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="13"><BR><BR><BR>1. CONTENT</FONT>
    <FONT COLOR="#000000" SIZE="10"><BR><SPAN STYLE="background: #ffffff">The content shall consist of all the video specified in the Main Terms. It includes (but is not limited to) any identifiable individuals, locations, sounds, trademarks and logos and all other rights depicted or contained in the Content (the “Content”). You agree to supply the Content in such formats and by such means as we may require.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="13"><BR><BR><BR>2. GRANT OF LICENCE</FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><SPAN STYLE="background: #ffffff">2.1 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">You appoint the Agency to exclusively manage the Content throughout the Territory with respect to all activities and opportunities relating to the Content, including but not limited to: licensing, distribution, pursuing and receiving costs and damages for past and future infringements, brand integration, endorsements, product placements, sponsorships, merchandising, advertising sales and any other form of usage relating to the Content whatsoever (“Services”) and you permit us to collect revenue with respect to the foregoing.</SPAN></FONT>
</p>';
// output the HTML content
        $pdf->writeHTML($html);
        $pdf->AddPage('P','','A4');
        $html = '
<style>
        h1.heading {
            text-align: center;
        }

    </style>

<p STYLE="margin-bottom: 0in;text-align: justify;">
    <FONT COLOR="#263d63" SIZE="10"><BR><SPAN STYLE="background: #ffffff">2.2 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">The agency is granted a worldwide licence to use the Content, in whole or part, on the terms set out in this Agreement. We may grant third parties the right to use the Content for any purpose and in any manner, including but not limited to exhibition, broadcast, distribution, advertising or promotion on any media now known or hereafter invented, worldwide and in perpetuity. We may change, alter, edit, modify, rearrange and reproduce the Content and authorise other parties to do the same. The Agency is permitted to pursue and conclude any form of opportunity on your behalf.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">2.3 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">The Agency may provide the Content to third parties to assist the business and for the purpose of marketing or promoting the Content. This shall not be construed as being the solicitation of, obtaining employment for, or pursuing work on the Assignor’s behalf and the Agency shall not act as the Assignor’s talent or employment agent or otherwise with respect to this Agreement, nor shall this Agreement be deemed to establish any partnership or joint venture between the Assignor and the Agency.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">2.4 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">During the Term of this Agreement, the Assignor agrees not to engage any other person or party as your representative in relation to the Content nor to provide the Content to any other person or party without the Agency’s prior written permission. The Assignor will refer all messages, enquiries or interest relating to the Content directly to the Agency and the Assignor further agrees not to negotiate or enter into any form of agreement with any other person, entity or party without the Agency’s prior written consent.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">2.5 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">The Agency shall be entitled to set the price for the Content in relation to the Services.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="13"><BR><BR><BR>3. TERM</FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><SPAN STYLE="background: #ffffff">3.1 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">You may seek to terminate this Agreement with at least thirty (30) days’ written notice(the “Termination Date”), after two years of signing this agreement; however, this Agreement shall only be terminable upon the mutual agreement of the parties, the consent of which may be granted or denied in WooGlobe’s sole discretion. No termination shall impact any prior license of the Images by WooGlobe prior to termination, which shall continue in full effect under the terms of this Agreement.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">3.2 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">In the event that the parties mutually agree in writing to terminate this Agreement, we agree to cease any further sales of the Content thirty (30) days following such mutual agreement being confirmed; notwithstanding the foregoing, the Content may continue to remain on our Pages, which may be monetised.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">3.3 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">Any agreements or conversations relating to the Content that began prior to the Termination Date shall persist beyond the Termination Date and we shall be entitled to conclude such conversations and/or agreements and collect any revenues relating to those conversations and/or agreements. This shall include any long-term licensing of Content, work booked prior to the Termination Date and any renewals arising from conversations or agreements entered into or agreed during the Term.  Should you breach any of the terms of this Agreement, we may terminate this Agreement and withhold any payments in relation to the Content.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">3.4 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">The Licensor understands that the video may be featured on any WooGlobe Ltd. channels, WooGlobe affiliate channels and/or websites currently in existence and/or invented in the future.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">3.5 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">The Licensor agrees that the payment will be only made to the Licensor if the video submitted gets used as an individual stand-alone video as a native upload and remains on https://www.tiktok.com/@wooglobe for over 24 hours.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="13"><BR><BR><BR>4. CREDIT</FONT>
    <FONT COLOR="#000000" SIZE="10"><BR><SPAN STYLE="background: #ffffff">The Agency will include a credit to the Assignor’ with the Content on any usage on our pages wherever possible. Also where possible, we try to include the credit line on the Content in the captions and meta data when it is being sent out to third parties. The Agency strives to ensure the credits are included by third parties but cannot guarantee that each individual publication follows our credit instructions.</SPAN></FONT>
</p>';
// output the HTML content
        $pdf->writeHTML($html);
        $pdf->AddPage('P','','A4');
        $html = '
<style>
        h1.heading {
            text-align: center;
        }

    </style>

<p STYLE="margin-bottom: 0in;text-align: justify;">
    <FONT COLOR="#4473c4" SIZE="13"><BR>5. WARRANTIES</FONT> 
    <FONT COLOR="#263d63" SIZE="10"><BR><SPAN STYLE="background: #ffffff">5.1 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">You warrant and represent that you are the sole absolute unencumbered legal and beneficial owner or controller of all rights in and to the Content and have the right and power to enter into this Agreement, to perform all of your obligations under this Agreement and to grant those rights and licences set out in this Agreement and have not assigned or sub-licenced the Content or the rights being granted.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">5.2 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">You warrant and represent that all individuals featured in the Content have provided full consent to their inclusion in the Content and you have obtained all required permissions and releases from individuals, parties or locations, including the express written consent of any identifiable minor’s parent or legal guardian, to enable you to grant us the rights granted herein.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">5.3 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">You warrant and represent that the Content shall contain nothing that is, or that when used by the Agency shall be in breach of any Intellectual Property Rights or infringe the moral rights of any person or infringe any obligation of any nature owed to any third party.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">5.4 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">You warrant and represent that the Agency will not be required to obtain any other rights or licence or make any payments to any parties in order to exercise the rights provided by you herein and the payment of all residuals or other sums that may be payable to any and all third parties on account of any exercise of our rights hereunder (including without limitation any sums payable by way of equitable remuneration from the exercise of so-called rental and lending rights) or for any other reason whatsoever, shall have been paid or will be paid by you and that the Agency is not and will not be liable for any such payments.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">5.5 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">You warrant and represent that nothing in the Content, nor any usage of the Content will infringe or violate the rights or interests of any third party, including intellectual property rights, proprietary rights or rights of publicity or privacy, or bring us into disrepute.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">5.6 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">You warrant and represent that the Content does not contain any viruses or other computer programming routines that are intended to damage, detrimentally interfere with, surreptitiously intercept or expropriate any system, data or personal information.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">5.7 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">The Agency acknowledge that the Assignor’ does not own or control the rights to any third party music featured in the Content.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="13"><BR><BR><BR>6. INDEMNITIES</FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><SPAN STYLE="background: #ffffff">6.1 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">You shall remain the copyright holder for the content and we take no responsibility with respect to the production or copyright involved in the content. The Agency will not be held liable for any costs, expenses, damages, liabilities, claims, fees and any other costs or expenses in relation to any claims or potential claims, which may be brought against us as a result of the production of the Content and any exploitation of the Content as contemplated in this Agreement or otherwise or as a result of your breach of any warranties contained in this Agreement.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">6.2 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">The Assignor shall indemnify the Agency, its respective officers, employees, successors, licensees and permitted assigns (and third parties authorised to use the Content) harmless from and against any costs, claim, demand, action, damages, loss and/or expense (including but not limited to any direct, indirect or consequential losses, loss of profit, loss of reputation and all interest penalties, legal costs and any other reasonable costs and expenses suffered or incurred) arising from actions brought by any third parties resulting from any breach of any of the warranties made by the Assignor; any claims respecting slander, libel, defamation, copyright or trademark infringement, invasion of privacy, or violations of any other rights arising out of or relating to any use of the Content authorised by the Agreement and this indemnity shall survive the termination of this agreement.</SPAN></FONT>
</p>';
        $pdf->writeHTML($html);
        $pdf->AddPage('P','','A4');
        $html = '
<style>
        h1.heading {
            text-align: center;
        }

    </style>

<p STYLE="margin-bottom: 0in;text-align: justify;">
    <FONT COLOR="#4473c4" SIZE="13"><BR>7. PERMITTED USE ON WEBSITE/SOCIAL MEDIA</FONT> 
    <FONT COLOR="#000000" SIZE="10"><BR><SPAN STYLE="background: #ffffff">The Agency shall be entitled to edit, upload and monetise the Content onto any of social media platforms, pages and websites currently in existence and/or invented in the future (“Pages”); the Content may remain on the platforms in perpetuity regardless of whether this Agreement is renewed and any revenue generated from the use of the Content on the Pages shall not be subject to any Revenue Share.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="13"><BR><BR><BR>8. PAYMENT</FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><SPAN STYLE="background: #ffffff">8.1 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">Agency agrees that if the video submitted gets used on the TikTok  page “WooGlobe” as a stand-alone native upload in accordance with clause 3.5, the submitter will receive a one-off $250 (Two Hundred and Fifty United States Dollars) payment. The amount will be paid to the Licensor of the winning submission within eight (8) weeks of when the video gets used on the WooGlobe TikTok ( https://www.tiktok.com/@wooglobe)</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">8.2 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">Licensor will be notified by email or phone or using the other contact details provided by the submitter. All reasonable endeavours will be made to contact the winners during the specified time. If the licensor cannot be contacted or is not available, the Agency reserves the right to forfeit the payment.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">8.3 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">The payment is non-transferable and there are no alternatives to the payment in whole or in part.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">8.4 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">In the event this contract becomes void after the money has been paid out, the Licensor agrees to pay back the money to the Agency.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">8.5 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">The licensor agrees that the payments will be made exclusively via Paypal. If the licensor does not have paypal, making the payment via alternate means will be completely at the discretion of WooGlobe Ltd.</SPAN></FONT>
    <FONT COLOR="#263d63" SIZE="10"><BR><BR><SPAN STYLE="background: #ffffff">8.6 </SPAN></FONT><FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff">Licensor understands and agrees that there will be no other payments made to the submitter in relation to the content.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="13"><BR><BR><BR>9. ENTIRE AGREEMENT</FONT> 
    <FONT COLOR="#000000" SIZE="10"><BR><SPAN STYLE="background: #ffffff">This Agreement may not be modified or altered except in writing by both parties. The invalidity or unenforceability of any provisions of this Agreement shall not affect the validity or enforceability of any other provision of this Agreement, which shall remain in full force and effect.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="13"><BR><BR><BR>10. CONFIDENTIALITY</FONT> 
    <FONT COLOR="#000000" SIZE="10"><BR><SPAN STYLE="background: #ffffff">This Agreement is confidential and, during the subsistence of this Agreement or at any time thereafter, the Assignor agrees not to disclose to any third party the terms of this Agreement or any other information disclosed to you by us to any other person or entity without our express written consent, unless required by law.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="13"><BR><BR><BR>11. ASSIGNMENT</FONT> 
    <FONT COLOR="#000000" SIZE="10"><BR><SPAN STYLE="background: #ffffff">You shall remain the copyright holder for the Content. You may not assign your rights under this Agreement without our prior written consent.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="13"><BR><BR><BR>12. COUNTERPARTS & CONFIRMATION</FONT> 
    <FONT COLOR="#000000" SIZE="10"><BR><SPAN STYLE="background: #ffffff">This agreement may be executed in any number of counterparts which together shall constitute one agreement. This agreement becomes valid and effective from the date of signing in writing or using an electronic signature or sending digital confirmation of agreement in the form of an email response confirming acceptance. You agree that electronic signature, clicking the buttons Accept and/or Submit in the form or sending digital confirmation is the legal equivalent of your manual signature in validating this Agreement.</SPAN></FONT>
    <FONT COLOR="#4473c4" SIZE="13"><BR><BR><BR>13. GOVERNING LAW</FONT> 
    <FONT COLOR="#000000" SIZE="10"><BR><SPAN STYLE="background: #ffffff">This agreement and any dispute or claim arising out of or in connection with it or its subject matter or formation (including noncontractual disputes or claims) shall be governed by the laws of England & Wales and the parties irrevocably agree that the courts of England and Wales shall have exclusive jurisdiction to settle any dispute or claim that arises out of or in connection with this Agreement.</SPAN></FONT>
</p>';
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
       
                <div class="top-form"  style="border: 3px solid #30538f;border-radius: 10px;padding: 5px;" >
                    <h3>&nbsp;Video Owner / Licensor</h3>
                        <table cellpadding="5" style="line-height: 25px">
                        <tr>
                        <td align="right" style="width: 20%;">Full Name<font color="red">*</font> : </td>
                        <td colspan="3" align="left" style="border-bottom: 1px solid #000000;width: 50%;">'.$full_name.'</td>
                        </tr>
                        <tr>
                        <td align="right" style="width: 20%;">Email<font color="red">*</font> : </td>
                        <td colspan="3" align="left" style="border-bottom: 1px solid #000000;width: 50%;">'.$email.'</td>
                        </tr>
                        <tr>
                        <td align="right" style="width: 20%;">Phone : </td>
                        <td colspan="3" align="left" style="border-bottom: 1px solid #000000;width: 50%;">'.$phone.'</td>
                        </tr>
                        
                        <tr>
                        <td align="right" style="width: 20%;">Address : </td>
                        <td colspan="3" align="left" style="border-bottom: 1px solid #000000;width: 78%;"></td>
                        </tr>
                        <tr>
                        <td align="right" style="width: 20%;">City : </td>
                        <td align="left" style="border-bottom: 1px solid #000000;width: 30%;"></td>
                        <td align="right" style="width: 20%;">State : </td>
                        <td align="left" style="border-bottom: 1px solid #000000; width: 28%;"></td>
                        </tr>
                        <tr>
                        <td align="right" style="width: 20%;">Zip Code : </td>
                        <td align="left" style="border-bottom: 1px solid #000000;width: 30%;"></td>
                        <td align="right" style="width: 20%;">Country : </td>
                        <td align="left" style="border-bottom: 1px solid #000000; width: 28%;"></td>
                        </tr>
                        </table>
                                            
                </div>
                <h3>&nbsp;Image(s): <FONT COLOR="#000000" SIZE="10"><SPAN STYLE="background: #ffffff; font-weight: normal;">(i.e. your video(s))</SPAN></FONT></h3>
                
                <div class="bottom-form" style="border: 3px solid #30538f; padding: 5px;">
                    <table cellpadding="5" style="line-height: 25px">
                        <tr>
                        <td align="right" style="width: 10%;">Title : </td>
                        <td align="left" style="border-bottom: 1px solid #000000;width: 88%;">'.$data["video_title"].'</td>
                        </tr>
                        <tr>
                        <td align="right" style="width: 10%;">URL : </td>
                        <td align="left" style="border-bottom: 1px solid #000000;width: 88%;">'.$data["video_url"].'</td>
                        </tr>
                        
                        </table>
                        <table cellpadding="5" style="line-height: 25px; margin-top: 25px;">
                        <tr>
                        <td align="center" style="width: 98%;" colspan="2"><small><BR>Includes all additional footage (e.g. "B-roll", raw footage, etc) submitted by Licensor
                            to WooGlobe in<BR>connection with the Images. Does not include Licensor\'s channel or other
                            works unless expressly stated.</small></td>
                   
                        </tr>
                        <tr>
                        <td align="right" style="width: 40%;">Where was this video filmed? : </td>
                        <td align="left" style="border-bottom: 1px solid #000000;width: 58%;">'.$data["question1"].'</td>
                        </tr>
                        <tr>
                        <td align="right" style="width: 40%;">When was this video filmed? : </td>
                        <td align="left" style="border-bottom: 1px solid #000000;width: 58%;">'.$data["question3"].'</td>
                        </tr>
                        
                        </table>
                       
                </div>
                    <h3><FONT SIZE="11">Declaration</FONT></h3>
                    <ul>
                        <li><FONT SIZE="10">I am 18 years of age or older and I either shot this video all by myself or own full
                            rights to the video.</FONT></li>
                        <li><FONT SIZE="10">By signing the agreement, I acknowledge that I have read and understood the detailed
                            WooGlobe Ltd. content agreement below, and that I accept and agree to adhere to all of
                            its terms, which includes the exclusive grant of rights of video to WooGlobe Ltd.</FONT></li>
                    </ul>
                    <div>
                    
                    <table>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        
                        
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

        $pdf->Image($signimgpath, 100, 245, 18, 14, 'PNG');
        $output_file=root_path(). '/uploads/'.$unique_key.'/documents/'.$unique_key.'_signed.pdf';
        if (! file_exists ( root_path(). '/uploads/'.$unique_key.'/documents/') ) {
            mkdir(root_path(). '/uploads/'.$unique_key.'/documents/',0777,true);
        }
        $pdf->Output($output_file, 'F');

    }

    public function video_signed_pdf($full_name,$email,$phone,$country,$state,$city,$address,$zip,$date,$revenue_share,$unique_key,$data,$data_log,$api_result){

        $signimgpath= root_path().'/uploads/'.$unique_key.'/documents/'.$unique_key.'.png';

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

        $pdf->Image($signimgpath, 100, 240, 18, 14, 'PNG');

// define active area for signature appearance
        $pdf->setSignatureAppearance(100, 240, 18, 14);

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
        <p>Document ID : '.$unique_key.'</p>
        <p>----------------------------------------------------------------------------------------------------------------------------</p>
        <p>WooGlobe</p>
        <p>Document Name:   '.$unique_key.'_signed.pdf</p>
        <p>Sent On:  '.$data_log["lead_rated_date"].' GMT</p>
        <p>IP Address: '.$data_log["user_ip_address"].'</p>
        <p>User Agent: '.$data_log["user_browser"].'</p>
        <p>Contract Signed Date And Time:  '.$data_log["contract_signed_datetime"].' GMT</p>
        <p>Contract Export Date And Time:  '.date('Y-m-d H:i:s').' GMT</p>
        '.$addres_html.'<br>
        ';

        $pdf->writeHTML($html);
// reset pointer to the last page
        $pdf->lastPage();
//Close and output PDF document
        $output_file=root_path(). '/uploads/'.$unique_key.'/documents/'.$unique_key.'_signed.pdf';
        if (! file_exists ( root_path(). '/uploads/'.$unique_key.'/documents/') ) {
            mkdir(root_path(). '/uploads/'.$unique_key.'/documents/',0777,true);
        }
        $pdf->Output($output_file, 'F');

    }

    public function appreance_signed_pdf($url_name,$first_name,$last_name,$email,$phone,$country,$state,$city,$address,$zip,$help_us,$date,$unique_key,$data_log,$api_result,$time){
        /*echo'<pre>';
        print_r($data1);
        exit();*/
        $signimgpath= root_path().'/uploads/'.$unique_key.'/documents/'.$unique_key.'.png';

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
        $output_file=root_path(). '/uploads/'.$unique_key.'/owner_appearance/'.$unique_key.'_signed_'.$time.'.pdf';
        if (! file_exists ( root_path(). '/uploads/'.$unique_key.'/owner_appearance' )) {
            mkdir(root_path(). '/uploads/'.$unique_key.'/owner_appearance',0777,true);
            $output_file=root_path(). '/uploads/'.$unique_key.'/owner_appearance/'.$unique_key.'_signed_'.$time.'.pdf';
        }

        $pdf->Output($output_file, 'F');

    }

}
