<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class VideosManagementController extends APP_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['active'] = 'Mobile App Videos';
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
            'assets/js/ma_videos.js',
            'assets/js/upload_edited_video.js'
        );
        $this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'videos'),
            'can_add'=>role_permitted_html(false,'videos','add_video'),
            'can_edit'=>role_permitted_html(false,'videos','update_video'),
            'can_delete'=>role_permitted_html(false,'videos','delete_video'),
            'can_view_files'=>role_permitted_html(false,'videos','original_files'),
            'can_add_earning'=>role_permitted_html(false,'earnings','add_earning'),

            'can_list_earning'=>role_permitted_html(false,'earnings'),
            'can_edit_earning'=>role_permitted_html(false,'earnings','update_earning'),
            'can_delete_earning'=>role_permitted_html(false,'earnings','delete_eaning'),
            'can_add_expense'=>role_permitted_html(false,'video_expenses','add_video_expense'),
            'can_list_expense'=>role_permitted_html(false,'video_expenses'),
            'can_edit_expense'=>role_permitted_html(false,'video_expenses','update_video_expense'),
            'can_delete_expense'=>role_permitted_html(false,'video_expenses','delete_video_expense'),
        );
        $this->load->model('MobileAppVideo','video');
        $this->load->model('Video_Lead_Model','lead');
        $this->load->model('Video_Deal_Model','deal');
        $this->load->model('Earning_Type_Model','earning_type');
        $this->load->model('Social_Sources_Model','source');
        $this->load->model('User_Model','user');
        $this->load->model('Categories_Model','mrss');

    }
    function createSlug($str, $delimiter = '-')
    {

        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;

    }
    public function index()
    {
        auth();
        role_permitted(false,'videos');
        $this->data['title'] = 'Videos Management';
        $this->data['earning_type'] = $this->earning_type->getAllEarningTypesActive('et.id,et.earning_type','',0,0,'et.earning_type ASC');
        $this->data['sources'] = $this->source->getAllSourcesActive('ss.id,ss.sources','',0,0,'ss.sources ASC');
        $this->data['partners'] = $this->user->getAllUsersActive(2,'u.id,u.full_name,u.email','',0,0,'u.full_name ASC');
        $this->data['mrss_categories'] = $this->mrss->getMrss();
        $result = $this->video->getAllVideos(2,'v.id,v.title,case when (v.status = 1) THEN "Active" ELSE "Inactive" END as status,a.email as email,c.title as ctitle,t.title as ttitle');
        $autoComplete = array();
        foreach($result->result() as $auto){
            if(!in_array($auto->title, $autoComplete)){
                $autoComplete[] =str_replace("'",'',$auto->title);
            }
            if(!in_array($auto->email, $autoComplete)){
                $autoComplete[] =$auto->email;
            }
            if(!in_array($auto->ctitle, $autoComplete)){
                $autoComplete[] =$auto->ctitle;
            }
            if(!in_array($auto->ttitle, $autoComplete)){
                $autoComplete[] =str_replace("'",'',$auto->title);;
            }

        }
        $this->data['autoComplete'] = $autoComplete;

        $this->data['content'] = $this->load->view('mobile_app/videos/listing',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function upload_edited_video($id)
    {
        $this->data['video_id'] = $id;

        $dealData = $this->video->getLeadDetailByVideoId($id);
        $finalUrl = '';
        if(strpos($dealData->video_url, 'facebook.com/') !== false) {
            //it is FB video
            $finalUrl = 'https://www.facebook.com/plugins/video.php?href='.rawurlencode($dealData->video_url).'&show_text=1&width=200';
        }else if(strpos($dealData->video_url, 'vimeo.com/') !== false) {
            //it is Vimeo video
            $videoId = explode("vimeo.com/", $dealData->video_url);
            $videoId = $videoId[1];
            if (strpos($videoId, '&') !== false) {
                $videoId = explode("&", $videoId);
                $videoId = $videoId[0];
            }
            $finalUrl = 'https://player.vimeo.com/video/'.$videoId;
        }else if(strpos($dealData->video_url, 'youtube.com/') !== false) {
            //it is Youtube video
            $videoId = explode("v=",$dealData->video_url);
            $videoId = $videoId[1];
            if(strpos($videoId, '&') !== false){
                $videoId = explode("&",$videoId);
                $videoId = $videoId[0];
            }
            $finalUrl = 'https://www.youtube.com/embed/'.$videoId;



        }else if(strpos($dealData->video_url, 'youtu.be/') !== false){
            //it is Youtube video
            $videoId = explode("youtu.be/",$dealData->video_url);
            $videoId = $videoId[1];
            if(strpos($videoId, '&') !== false){
                $videoId = explode("&",$videoId);
                $videoId = $videoId[0];
            }
            $finalUrl ='https://www.youtube.com/embed/'.$videoId;



        }else{
            $finalUrl = $dealData->video_url;
        }
        $raw_video = $this->video->getRawVideoById($id);
        include_once('./app/third_party/getid3/getid3/getid3.php');
        $getID3 = new getID3;
        //$file = $getID3->analyze($file);

        $this->data['getid3'] = $getID3;
        $this->data['raw_video'] = $raw_video;
        $this->data['mrss_categories'] = $this->mrss->getMrss();
        $this->data['video'] = $finalUrl;
        $this->data['dealData'] = $dealData;
        $this->data['content'] = $this->load->view('videos/upload_edited_video',$this->data,true);
        $this->load->view('common_files/template',$this->data);

    }

    public function video_listing(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'videos');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->get());
        $search = '';
        $orderby = '';
        $type = 1;
        $start = 0;
        $limit = 0;
        if(isset($params['search'])){
            $search = $params['search']['value'];
        }
        if(isset($params['start'])){
            $start = $params['start'];
        }
        if(isset($params['length'])){
            $limit = $params['length'];
        }
        if(isset($params['video_type'])){
            $type = $params['video_type'];
        }
        if(isset($params['order'])){
            $orderby = $params['columns'][$params['order'][0]['column']]['name'].' '.$params['order'][0]['dir'];
        }

        $result = $this->video->getAllVideos($type,'v.id,v.title,v.mrss,v.mrss_categories,case when (v.status = 1) THEN "Active" ELSE "Inactive" END as status,a.email as email,c.title as ctitle,t.title as ttitle',$search,$start,$limit,$orderby,$params['columns']);

        $resultCount = $this->video->getAllVideos($type);
        $response = array();
        $data = array();

        foreach($result->result() as $row){
            $r = array();
            $links = '<a title="Play Video" href="javascript:void(0);" class="play-video" data-id="'.$row->id.'"><i class="material-icons">&#xE04A;</i></a> ';
            if($this->data['assess']['can_edit']) {

                $links .= ' | <a title="Edit Video" href="'.base_url('mobile-app-edit_video/'.$row->id).'" class="edit-video" data-id="'.$row->id.'"><i class="material-icons">&#xE254;</i></a> ';
            }
            if($this->data['assess']['can_delete']) {

                $links .= '| <a title="Delete Video" href="javascript:void(0);" class="delete-video" data-id="'.$row->id.'"><i class="material-icons">&#xE92B;</i></a>';
            }
           /* if($this->data['assess']['can_list_earning']) {

                $links .= '| <a title="Video Earnings" href="'.$this->data['url'].'earnings?video_id='.$row->id.'"><i class="material-icons">&#xE84F;</i></a>';
            }

            if($this->data['assess']['can_add_earning']) {

                $links .= '| <a title="Add Earning" href="javascript:void(0);" class="add-earning" data-id="'.$row->id.'" data-title="'.$row->title.'"><i class="material-icons">account_balance_wallet</i></a>';
            }

            if($this->data['assess']['can_list_expense']) {

                $links .= '| <a title="Video Expense" href="'.$this->data['url'].'video_expenses?video_id='.$row->id.'"><i class="material-icons">&#xE25C;</i></a>';
            }

            if($this->data['assess']['can_add_expense']) {

                $links .= '| <a title="Add Video Expense" href="javascript:void(0);" class="add-expense" data-id="'.$row->id.'" data-title="'.$row->title.'"><i class="material-icons">&#xE227;</i></a>';
            }
            if($this->data['assess']['can_edit']) {

                $links .= '| <a title="MRSS feed" href="javascript:void(0);" class="mrss-feed" data-id="'.$row->id.'" data-title="'.$row->title.'" data-mrss="'.$row->mrss.'" data-mrss-c="'.$row->mrss_categories.'"><i class="material-icons">rss_feed</i></a>';
            }*/



            $r[] = $links;
            $r[] = '<a title="Play Video" href="javascript:void(0);" class="play-video" data-id="'.$row->id.'">'.$row->title.'</a>';
          /*  $files = '';*/
            /*if($this->data['assess']['can_view_files']) {

                $files .= '<a title="Original Files" href="'.base_url('original_files/'.$row->id).'" class="original-files" data-id="'.$row->id.'">Original Files</a> ';
            }*/
         /*   $r[] = $files;*/
            $r[] = $row->ctitle;
           /* $r[] = $row->ttitle;*/
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
        role_permitted(false,'videos','add_video');
        $this->data['title'] = 'Add New Video';
        $this->data['categories'] = $this->video->getParentCategories('id,title');
        $this->data['users'] = $this->video->getAllUsers('id,full_name,email');
        $this->data['videoTypes'] = $this->video->getAllVideoTypes('id,title');
        $this->data['content'] = $this->load->view('videos/add',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function add_video(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'videos','add_video');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Video Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('parent','Parent Category','trim|required');
        $this->validation->set_rules('category_id','Sub Category','trim|required');
        $this->validation->set_rules('title','Video Title','trim|required');
        $this->validation->set_rules('user_id','User','trim|required');
        $this->validation->set_rules('video_type_id','Video Type','trim|required');
        $this->validation->set_rules('status_u','Status','trim|required');
        $this->validation->set_rules('url','Embed Code/URL','trim|required');
        $this->validation->set_message('required','This field is required.');
        $this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
        if($this->validation->run() === false){

            $fields = array('title','status_u','parent','category_id','user_id','video_type_id','url');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{
            $response['url'] = $this->data['url'].'videos';
            $dbData = $this->security->xss_clean($this->input->post());
            if(!isset($dbData['real_deciption_updated'])){
                $dbData['real_deciption_updated'] = 0;
            }
            $dbData['url'] = $this->input->post('url');
            $status = $dbData['status_u'];
            $dbData['status'] = $status;
            if(isset($dbData['is_wooglobe_video'])){
                $dbData['is_wooglobe_video'] = 1;
            }else{
                $dbData['is_wooglobe_video'] = 0;
            }
            unset($dbData['status_u']);
            unset($dbData['parent']);

            if(!empty($_FILES['thumb']['name'])){

                $data = $this->upload('thumb');

                if($data['code'] == 200){

                    $dbData['thumbnail'] = $data['url'];

                }

            }
            if($dbData['video_type_id'] != 1){
                $dbData['embed'] = 1;
            }
            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['created_by'] = $this->sess->userdata('adminId');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->insert('videos',$dbData);
        }

        echo json_encode($response);
        exit;

    }



    public function get_video(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'videos');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video found!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->video->getVideoById($id,'url,embed,title,youtube_id');

        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';

        }else{

            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }

    public function edit_video($id)
    {
        auth();
        role_permitted(false,'videos','update_video');
        $result = $this->video->getVideoDetailById($id,'v.*,c.parent_id');
        if(!$result){

            redirect('videos');

        }


        if(isset($_SERVER['HTTP_REFERER'])) {
            $this->sess->set_userdata('refferer', $_SERVER['HTTP_REFERER']);
        }


        $dataArry = array();
        foreach($result as $i=>$v){
            if($i == 'category_id' || $i == 'video_type_id' || $i == 'user_id' || $i == 'status' || $i == 'parent_id'){
                $dataArry[$i]=$v;
            }

        }



        $raw_video = $this->video->getRawVideoById($id);

        $this->data['title'] = 'Edit Video';
        $this->data['id'] = $id;
        $this->data['data'] = $result;
        $this->data['edit_data'] = json_encode($dataArry,true);

        $this->data['raw_video'] = $raw_video;

        //$file  = './../uploads/videos/7672aa2e763a5c79f09e9970edbb547a.mp4';
        include_once('./app/third_party/getid3/getid3/getid3.php');
        $getID3 = new getID3;
        //$file = $getID3->analyze($file);

        $this->data['getid3'] = $getID3;
        $this->data['categories'] = $this->video->getParentCategories('id,title');
        $this->data['users'] = $this->video->getAllUsers('id,full_name,email');
        $this->data['lead'] = $this->lead->getLeadByIdAllStatus($result->lead_id);

        $this->data['videoTypes'] = $this->video->getAllVideoTypes('id,title');
        $this->data['content'] = $this->load->view('mobile_app/videos/edit',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function update_video(){

        $auth_ajax = auth_ajax();

        if($auth_ajax){
            echo json_encode($auth_ajax);

            exit;
        }


        $role_permitted_ajax = role_permitted_ajax(false,'videos','update_video');
        if($role_permitted_ajax){

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

        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        //$this->validation->set_rules('parent','Parent Category','trim|required');
        $this->validation->set_rules('category_id[]','Category','trim|required');
        $this->validation->set_rules('title','Video Title','trim|required');
        //$this->validation->set_rules('user_id','User','trim|required');
        //$this->validation->set_rules('video_type_id','Video Type','trim|required');
        //$this->validation->set_rules('status_u','Status','trim|required');
        //$this->validation->set_rules('url','Embed Code/URL','trim|required');
        $this->validation->set_message('required','This field is required.');
        $this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
        if($this->validation->run() === false){

            $fields = array('title','status_u','category_id');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{
            $response['url'] = $this->data['url'].'videos';
            $dbData = $this->security->xss_clean($this->input->post());
           /* if(!isset($dbData['real_deciption_updated'])){
                $dbData['real_deciption_updated'] = 0;
            }else{
                $dbData['real_deciption_updated'] = 1;
            }*/
            // $dbData['url'] = $this->input->post('url');
            //$status = $dbData['status_u'];
            /*$dbData['status'] = 1;*/

            $dbData['is_wooglobe_video'] = 0;

            if(!empty($dbData['category_id'])){
                $dbData['category_id'] = implode(',',$dbData['category_id']);
            }
            $dbData['slug'] = $this->createSlug($dbData['title']);


           /* if(isset($dbData['is_high_quality'])){
                $dbData['is_high_quality'] = 1;
            }else{
                $dbData['is_high_quality'] = 0;
            }

            if(isset($dbData['is_real_file'])){
                $dbData['is_real_file'] = 1;
            }else{
                $dbData['is_real_file'] = 0;
            }

            if(isset($dbData['is_complete_file'])){
                $dbData['is_complete_file'] = 1;
            }else{
                $dbData['is_complete_file'] = 0;
            }

            if(!isset($dbData['mrss'])){
                $dbData['mrss'] = 0;
            }else{
                $dbData['mrss'] = 1;
            }

            if(!isset($dbData['is_featured'])){
                $dbData['is_featured'] = 0;
            }else{
                $dbData['is_featured'] = 1;
            }*/
            unset($dbData['status_u']);
            unset($dbData['parent']);

            //if($dbData['is_high_quality'] == 1 && $dbData['is_complete_file'] == 1 && $dbData['is_real_file'] == 1
            // && $dbData['real_deciption_updated'] == 1){
           /* if(true){
                $dbData['video_verified'] = 1;

                $data = array(
                    'status' => 6
                );

                $this->db->where('id', $result->lead_id);
                $this->db->update('video_leads',$data);
                action_add($result->lead_id,$result->id,0,$this->sess->userdata('adminId'),1,'Video verified');
                $rawVideos = $this->video->getRawVideoById1($id);
                $assignFiles = array();
                $uid = '';
                foreach($rawVideos as $row){
                    $uid = explode('/',$row->url);

                    $assignFiles[] = $uid[count($uid)-1];
                    $uid = $uid[1];
                    $source_file = $_SERVER['DOCUMENT_ROOT'].'/'.$row->url;
                    $file_extension = explode('.',$source_file);
                    $file_extension = $file_extension[1];
                    $target_file_key = $row->url;
                    $url = $this->upload_file_s3($source_file, $target_file_key, $file_extension, FALSE);
                    $this->db->where('id',$row->id);
                    $this->db->update('raw_video',array('s3_url'=>$url));
                }
                $allFiles = scandir("./../uploads/$uid/raw_videos");

                foreach($allFiles as $file){
                    if(!in_array($file,$assignFiles)){
                        @unlink("./../uploads/$uid/raw_videos/".$file);
                    }
                }

            }else{
                $dbData['video_verified'] = 0;
                $response['code'] = 205;
                $response['message'] = 'The video has not been verified yet.';
                $response['error'] = 'The video has not been verified yet.';
                $response['url'] = '';
                $this->sess->set_flashdata('err','The video has not been verified yet.');
            }*/

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
            $this->db->where('id',$id);

            $this->db->update('videos',$dbData);

        }


        $flash = $this->sess->userdata('refferer');

        if(!empty($flash)){
            $response['url'] = $flash;
        }
        echo json_encode($response);
        exit;

    }



    public function delete_video(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'videos','delete_video');
        if($role_permitted_ajax){
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

        if(!$result){

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
        $this->db->where('id',$id);
        $this->db->update('videos',$dbData);

        echo json_encode($response);
        exit;

    }
    public function update_mrss(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'videos','update_video');
        if($role_permitted_ajax){
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

        if(!$result){

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
        if(isset($input['is_mrss'])){
            $dbData['mrss'] = 1;
            $dbData['mrss_categories'] = implode(',',$input['mrss_categories']);
        }else{
            $dbData['mrss'] = 0;
            $dbData['mrss_categories'] = 0;
        }
        //$dbData['deleted'] = 1;
        $this->db->where('id',$id);
        $this->db->update('videos',$dbData);

        echo json_encode($response);
        exit;

    }

    public function get_video_sub_category(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Record found!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->video->getSubCategories('id,title',$id);
        $array = array('');
        $null = array('value'=>'','text'=>'Choose...');
        $array[] = $null;
        foreach($result->result() as $row){

            $arr = array('value'=>$row->id,'text'=>$row->title);
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

        $this->validation->set_rules('yt_video','Youtube Video','trim');
        $this->validation->set_rules('fb_video','Facebook Video','trim');
        $this->validation->set_rules('portal_video','Portal Video','trim|required');
        $this->validation->set_message('required','This Field is required');

        if($this->validation->run() == FALSE){
            $fields = array('yt_video','fb_video','portal_video');
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
        $dbData = array();
        $dbData['yt_url'] = $input['yt_video'];
        $dbData['fb_url'] = $input['fb_video'];
        //$dbData['portal_url'] = $input['portal_video'];
        $dbData['video_id'] = $input['video_id'];
        $lead_id = $this->video->getLeadByVideoId($dbData['video_id']);
        $leadData = $this->deal->getDealById($lead_id->lead_id);


        $source_file = $_SERVER['DOCUMENT_ROOT'].'/'.$input['portal_video'];
        $file_extension = explode('.',$source_file);
        $file_extension = $file_extension[1];
        $target_file_key = S3_BASE_FOLDER . "/" . $input['portal_video'];
        $target_file_key = explode('/',$target_file_key);
        $edit_uniquekey=$leadData->unique_key;
        $portal_video_results = $this->db->query('SELECT portal_url FROM edited_video')->result();
        $portal_url='';
        $portal_video_target_key='';
        $result='';
        foreach ($portal_video_results as $portal_video_result){
            $portal_url = $portal_video_result->portal_url;
            $portal_arr= explode('/',$portal_url);
            $portal_arr_keyvalue='';
            if(isset($portal_arr[5])){
                $portal_arr_keyvalue=$portal_arr[5];
            }
            if($edit_uniquekey ==  $portal_arr_keyvalue){
                $portal_video_id_results = $this->db->query('SELECT video_id FROM `edited_video`WHERE `portal_url`= "'.$portal_url.'"')->result();
                $portal_video_id=$portal_video_id_results[0]->video_id;
                $portal_video_target_key=end($portal_arr);
            }
        }

        if($portal_video_target_key){
            $target_file_key = 'videos/edited_videos/'.$leadData->unique_key.'/portal_video/'.$portal_video_target_key;
            $urlp = $this->upload_file_s3($source_file, $target_file_key, $file_extension, false);

            if(!empty($_FILES['portal_thumb']['name'])){

                $data = $this->upload_edited_thumbnail('portal_thumb',$leadData->unique_key,'mrss');
                $source_file = $_SERVER['DOCUMENT_ROOT'].'/'.$data['url'];
                $file_extension = explode('.',$source_file);
                $file_extension = $file_extension[1];
                $target_file_key = S3_BASE_FOLDER . "/" . $data['url'];
                $target_file_key = explode('/',$target_file_key);
                $target_file_key = 'videos/edited_videos/'.$leadData->unique_key.'/portal_video/'.$target_file_key[count($target_file_key)-1];
                $urlfb = $this->upload_file_s3($source_file, $target_file_key, $file_extension, false);
                $dbData['portal_thumb'] = $urlfb;

            }
            if(!empty($_FILES['yt_thumb']['name'])){

                $data = $this->upload_edited_thumbnail('yt_thumb',$leadData->unique_key,'youtube');
                $dbData['yt_thumb'] = $data['url'];
            }

            if(!empty($_FILES['fb_thumb']['name'])){

                $data = $this->upload_edited_thumbnail('fb_thumb',$leadData->unique_key,'facebook');
                $dbData['fb_thumb'] = $data['url'];
            }
            $dbData['portal_url'] = $urlp;
            $dbData['video_id'] = $portal_video_id;
            $this->db->where('video_id',$dbData['video_id']);
            $result = $this->db->update('edited_video',$dbData);
            $facebook_video_id_results = $this->db->query('SELECT facebook_id FROM `videos` WHERE `id`= "'.$dbData['video_id'].'"')->result();
            if($facebook_video_id_results){
                $facebook_repub_status= 1;
                $update_facebook_repub = $this->db->query('UPDATE videos SET `facebook_repub`="'.$facebook_repub_status.'" WHERE `id`= "'.$dbData['video_id'].'"');
            }
            $youtube_video_id_results = $this->db->query('SELECT youtube_id FROM `videos` WHERE `id`= "'.$dbData['video_id'].'"')->result();
            if($youtube_video_id_results){
                $youtube_repub_status= 1;
                $update_facebook_repub = $this->db->query('UPDATE videos SET `youtube_repub`="'.$youtube_repub_status.'" WHERE `id`= "'.$dbData['video_id'].'"');
            }
        }
        else{
            $target_file_key = 'videos/edited_videos/'.$leadData->unique_key.'/portal_video/'.$target_file_key[count($target_file_key)-1];
            $urlp = $this->upload_file_s3($source_file, $target_file_key, $file_extension, false);
            $dbData['portal_url'] = $urlp;

            if(!empty($_FILES['portal_thumb']['name'])){

                $data = $this->upload_edited_thumbnail('portal_thumb',$leadData->unique_key,'mrss');
                $source_file = $_SERVER['DOCUMENT_ROOT'].'/'.$data['url'];
                $file_extension = explode('.',$source_file);
                $file_extension = $file_extension[1];
                $target_file_key = S3_BASE_FOLDER . "/" . $data['url'];
                $target_file_key = explode('/',$target_file_key);
                $target_file_key = 'videos/edited_videos/'.$leadData->unique_key.'/portal_video/'.$target_file_key[count($target_file_key)-1];
                $urlfb = $this->upload_file_s3($source_file, $target_file_key, $file_extension, false);
                $dbData['portal_thumb'] = $urlfb;

            }
            if(!empty($_FILES['yt_thumb']['name'])){

                $data = $this->upload_edited_thumbnail('yt_thumb',$leadData->unique_key,'youtube');
                $dbData['yt_thumb'] = $data['url'];
            }

            if(!empty($_FILES['fb_thumb']['name'])){

                $data = $this->upload_edited_thumbnail('fb_thumb',$leadData->unique_key,'facebook');
                $dbData['fb_thumb'] = $data['url'];
            }
            $dbData['video_id'] = $input['video_id'];

            $result = $this->db->insert('edited_video',$dbData);
        }
        $result = $this->video->getVideoById($dbData['video_id']);
        action_add($result->lead_id,$result->id,0,$this->sess->userdata('adminId'),1,'Edited files uploaded');
        if($result){



            $lead_id = $this->video->getLeadByVideoId($dbData['video_id']);

            $data = array(
                'uploaded_edited_videos' => 1
            );
            $data1 = array();
            if(isset($input['is_mrss'])){
                $data1['mrss'] = 1;

                $data1['mrss_categories'] = implode(',',$input['mrss_categories']);
            }else{
                $data1['mrss'] = 0;
                $data1['mrss_categories'] = 0;
            }

            $this->db->where('id', $lead_id->lead_id);
            $this->db->update('video_leads',$data);
            $this->db->where('id', $dbData['video_id']);
            $this->db->update('videos',$data1);


            $response['url'] = base_url().'video_deals';
            echo json_encode($response);
            exit;
        }
        else{
            $response['code'] = 201;
            $response['message'] = 'Something is going wrong!';

            echo json_encode($response);
            exit;
        }




    }

    public function upload_video(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video uploaded successfully!';
        $response['error'] = '';
        $response['url'] = '';

        /*echo '<pre>';
        print_r($_FILES);
        exit;*/
        $directory = $this->input->get('type');
        $key = $this->input->get('key');
        $config['upload_path']          = "./../uploads/$key/edited_videos/$directory/";
        $config['allowed_types']        = 'avi|mp4|wmv|flv|mkv|AVI|MP4|FLV|MKV';
        $config['encrypt_name']        	= true;
        $config['remove_spaces']        = true;
        $config['file_ext_tolower']     = true;
        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('files'))
        {
            $error = array('error' => $this->upload->display_errors());

            $response['code'] = 200;
            $response['message'] = 'Video not uploaded successfully!';

            $response['error'] = $error;
        }
        else
        {
            $data = $this->upload->data();

            $response['url'] = "uploads/$key/edited_videos/$directory/".$data['file_name'];
        }


        echo json_encode($response);
        exit;

    }

    public function original_files($id){
        auth();
        role_permitted(false,'videos','original_files');
        $this->data['title'] = 'Videos Original Files';
        $this->data['id'] = $id;
        $this->data['content'] = $this->load->view('videos/files',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function get_original_files($id){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'videos','original_files');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->get());
        $search = '';
        $orderby = '';
        $start = 0;
        $limit = 0;
        if(isset($params['search'])){
            $search = $params['search']['value'];
        }
        if(isset($params['start'])){
            $start = $params['start'];
        }
        if(isset($params['length'])){
            $limit = $params['length'];
        }

        if(isset($params['order'])){
            $orderby = $params['columns'][$params['order'][0]['column']]['name'].' '.$params['order'][0]['dir'];
        }

        $result = $this->video->getAllVideosOriginalFiles($id,'rv.id,rv.video_id,rv.url,v.title',$search,$start,$limit,$orderby,$params['columns']);
        $resultCount = $this->video->getAllVideosOriginalFiles($id);
        $response = array();
        $data = array();
        $i = 1;
        foreach($result->result() as $row){
            $r = array();
            $r[] = $i;
            $r[] = '<a title="Play Video" href="javascript:void(0);" class="play-files" data-id="'.$row->id.'">'.$row->title.'</a>';


            $links = '<a title="Play Video" href="javascript:void(0);" class="play-files" data-id="'.$row->id.'"><i class="material-icons">&#xE04A;</i></a> ';


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

    public function get_file(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'videos','original_files');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video found!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->video->getFileById($id,'rv.url,v.title');
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';

        }else{

            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }

    public function delete_raw_file(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false,'videos','can_delete');
        if($role_permitted_ajax){
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

        $this->db->where('id',$id);
        $this->db->delete('raw_video');
        @unlink('./../'.$url);
        echo json_encode($response);
        exit;

    }

}
