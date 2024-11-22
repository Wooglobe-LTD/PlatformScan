<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bulk extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'bulk';
        $css = array(
            'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css'
        );
        $js = array(

            'assets/js/pages/forms_file_upload.js',
            'assets/js/custom/dropify/dist/js/dropify.min.js',
            'assets/js/pages/forms_file_input.min.js',
            'assets/js/bulk.js'
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);
        $this->data['assess'] = array(
            'can_upload' => role_permitted_html(false, 'bulk'),

        );
        $this->load->library('csvimport');


    }

    public function index()
    {
        auth();
        role_permitted(false, 'bulk');
        $this->data['content'] = $this->load->view('bulk/upload_edited_video', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }
    public function raw_videos()
    {
        $file_data = $this->csvimport->get_array("/var/www/html/admin/app/controllers/raw.csv", ["unique_key", "url"]);


        foreach ($file_data as $data) {



            $this->db->select(' videos.id, videos.lead_id')
                ->from('videos')
                ->join('video_leads', 'video_leads.id = videos.lead_id');
            $this->db->where('video_leads.unique_key', $data["unique_key"]);
            $query = $this->db->get();
            $query = $query->result();

            $raw_data['video_id'] = $query[0]->id;
            $raw_data['lead_id'] = $query[0]->lead_id;
            $raw_data['url'] = $data["url"];
            $raw_data['s3_url'] = "https://wooglobe.s3-us-west-2.amazonaws.com/".$data["url"];
            $this->db->insert('raw_video', $raw_data);


        }

    }
    public function upload_bulk()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'bulk');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video Uploaded Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $this->load->helper('string');
        $config['upload_path'] = './bulk_files/';
        $config['allowed_types'] = 'EXCEL|xlsx';
        $config['encrypt_name'] = true;
        $config['remove_spaces'] = true;
        $config['file_ext_tolower'] = true;
        $this->load->library('upload', $config);
        require_once(APPPATH.'libraries/SimpleXLSX.php');
        if (!$this->upload->do_upload('csv')) {

            $response['code'] = 201;
            $response['message'] = $this->upload->display_errors();
            $response['error'] = array('dropify_my' => $this->upload->display_errors());

        } else {
            $data1 = $this->upload->data();
            $response['csvfile'] = $data1;
            $this->load->helper('file');
            $file = $data1['full_path'];
            $this->db->query('TRUNCATE bulk_upload');

            if ( $xlsx = SimpleXLSX::parse($file) ) {
                //print_r( $xlsx->rows() );
            } else {
                echo SimpleXLSX::parseError();
            }
            //$fd = fopen($file, "r");
            foreach($xlsx->rows() as $line) //($line = fgetcsv($fd))
            {//print_r($line);
                //print "<br>";print_r($line);echo"<br>";
                $unique_key_list = array_column($this->db->distinct()->select('unique_key')->get('video_leads')->result_array(), 'unique_key');

                do {
                    $random = random_string('numeric',6);
                }
                while (in_array("WGA".$random, $unique_key_list));
                //$random = strtoupper($random);
                //$random = $random.$id;

                $key = hash("sha1",$random,FALSE);
                //$key = strtoupper($key);

                // Randomly generate unique key for leads.

                $unique = "WGA".$random;
                if( !empty($line[0])  and !empty($line[2]) ){
                    $data['client_first_name'] = ($line[0]);
                    $data['client_last_name'] = ($line[1]);
                    $data['client_email'] = ($line[2]);
                    $data['lead_video_title'] = ($line[3]);
                    $data['lead_video_url'] = ($line[4]);
                    $data['lead_message'] = ($line[5]);
                    $data['rating_points'] = ($line[6]);
                    $data['rating_comments'] = ($line[7]);
                    $data['deal_closing_date'] = ($line[8]);
                    $data['revenue_share'] = ($line[9]);
                    $data['youtube_video_id'] = ($line[10]);
                    $data['real_description_updated'] = ($line[11]);
                    $data['have_the_original_video'] = ($line[12]);
                    $data['where_taken'] = ($line[13]);
                    $data['context'] = ($line[14]);
                    $data['when_taken'] = ($line[15]);
                    $data['other_information'] = ($line[16]);
                    $data['tags'] = ($line[17]);
                    $data['category'] = ($line[18]);
                    $data['publish_video_title'] = ($line[19]);
                    $data['youtube_status'] = ($line[20]);
                    $data['status'] = ($line[21]);
                    $data['video_elephant'] = ($line[22]);
                    $data['raw_video'] = ($line[23]);
                    $data['description_updated'] = ($line[24]);
                    $data['exclusive_status'] = ($line[25]);
                    $data['facebook'] = ($line[26]);
                    $data['instagram'] = ($line[27]);
                    $data['mrss_categories'] = ($line[29]);
                    $data['unique_key'] = ($unique);
                    $data['confidence_level'] = ($line[30]);
                    $this->db->insert('bulk_upload', $data);
                }
                /*    echo $sql =  $this->db->query("INSERT INTO bulk_upload (client_first_name, client_last_name, client_email, lead_video_title, lead_video_url,
     lead_message, rating_points, rating_comments, deal_closing_date, revenue_share, youtube_video_id, real_description_updated ,have_the_original_video,
     where_taken, context, when_taken, other_information, tags, category, publish_video_title, youtube_status, status, video_elephant, raw_video,
     description_updated, exclusive_status, facebook,instagram,mrss_categories,unique_key)
     VALUES (addslashes($line[0]), 'addslashes($line[1])', 'addslashes($line[2])', 'addslashes($line[3])', 'addslashes($line[4])', 'addslashes($line[5])', 'addslashes($line[6])', 'addslashes($line[7])', 'addslashes($line[8])', 'addslashes($line[9])', 'addslashes($line[10])', 'addslashes($line[11])', 'addslashes($line[12])',
     'addslashes($line[13])', 'addslashes($line[14])', 'addslashes($line[15])', 'addslashes($line[16])', 'addslashes($line[17])', 'addslashes($line[18])', 'addslashes($line[19])', 'addslashes($line[20])', 'addslashes($line[21])', 'addslashes($line[22])', 'addslashes($line[23])', 'addslashes($line[24])', 'addslashes($line[25])',
     'addslashes($line[26])', 'addslashes($line[27])', 'addslashes($line[28])', 'addslashes($line[29])')");*/
                // $res = mysql_query($sql);
            }

            /* $csvFile = file($file);
             $data = [];
             foreach ($csvFile as $line) {
                 $data[] = str_getcsv($line);
             }
             echo "<pre>";print_r($data);echo "</pre>";*/
            /* echo  "LOAD DATA LOCAL INFILE '" . $file . "'
                 INTO TABLE bulk_upload
                 COLUMNS TERMINATED BY ','
                 OPTIONALLY ENCLOSED BY '\"'
                 ESCAPED BY '\"'
                 LINES TERMINATED BY '\r\n'
                 IGNORE 1 LINES
             (client_first_name, client_last_name, client_email, lead_video_title, lead_video_url, lead_message, rating_points, rating_comments, deal_closing_date, revenue_share, youtube_video_id, real_description_updated ,have_the_original_video, where_taken, context, when_taken, other_information,
                 tags, category, publish_video_title, youtube_status, status, video_elephant, raw_video, description_updated, exclusive_status, facebook,instagram,mrss_categories,unique_key)";
             exit;$query = $this->db->query("
                 LOAD DATA LOCAL INFILE '" . $file . "'
                 INTO TABLE bulk_upload
                 COLUMNS TERMINATED BY ','
                 OPTIONALLY ENCLOSED BY '\"'
                 ESCAPED BY '\"'
                 LINES TERMINATED BY '\n'
                 IGNORE 1 LINES
                 (client_first_name, client_last_name, client_email, lead_video_title, lead_video_url, lead_message, rating_points, rating_comments, deal_closing_date, revenue_share, youtube_video_id, real_description_updated ,have_the_original_video, where_taken, context, when_taken, other_information,
                 tags, category, publish_video_title, youtube_status, status, video_elephant, raw_video, description_updated, exclusive_status, facebook,instagram,mrss_categories,unique_key)
         ");*/
            //(video_title,category,description, tags,thumb,channel,video_id,status,url,first_name,last_name,email,message,rating_point,rating_comment,closeing_date,revenue_share);
            unlink($file);
            //exit;
            $videos = $this->db->query('
	            SELECT *
	            FROM bulk_upload
	         ');
            $ci = &get_instance();

            $sess = $ci->sess->userdata('isAdminLogin');
            $row_num = 1;
            $error_text = '';
            /*print_r($videos->result());
            exit();*/
            foreach ($videos->result() as $video) {
                // Prepare error text for the row, if any required value is missing. This is ssent in email after all data imported
                $error_text .= $this->csv_issues($video, $row_num);
                $currnt_date = date('Y-m-d H:i:s');
                if (!empty($video->client_email) && !empty($video->client_first_name) ) {

                    $client_id = 0;
                    $client_query = $this->db->query('
	                SELECT *
	                FROM users 
	                WHERE email = "' . $video->client_email . '"
	                AND role_id = 1
	            ');
                    if ($client_query->num_rows() > 0) {
                        $client_id = $client_query->row()->id;
                    } else {

                        $client_db_data['full_name'] = $video->client_first_name . ' ' . $video->client_last_name;
                        $client_db_data['email'] = $video->client_email;
                        $client_db_data['created_at'] = $currnt_date;
                        $client_db_data['deleted_at'] = $currnt_date;
                        $client_db_data['created_by'] = 1;
                        $client_db_data['updated_by'] = 1;
                        $client_db_data['role_id'] = 1;
                        $client_db_data['status'] = 1;
                        $client_db_data['bulk_user'] = 1;
                        $this->db->insert('users', $client_db_data);
                        $client_id = $this->db->insert_id();
                    }
                    /**
                     * Insert  video lead data
                     */

                    $lead_data = array();
                    $lead_data['created_at'] = $currnt_date;
                    $lead_data['deleted_at'] = '';//$currnt_date;
                    $lead_data['created_by'] = 1;
                    $lead_data['updated_by'] = 1;
                    $lead_data['status'] = $video->status;
                    $lead_data['client_id'] = $client_id;
                    $lead_data['first_name'] = $video->client_first_name;
                    $lead_data['last_name'] = $video->client_last_name;
                    $lead_data['email'] = $video->client_email;
                    $lead_data['video_title'] = $video->lead_video_title;
                    $lead_data['video_url'] = $video->lead_video_url;
                    $lead_data['message'] = '';
                    $lead_data['published_yt'] = 1;
                    $lead_data['published_fb'] = 1;
                    $lead_data['published_portal'] = 1;
                    $lead_data['uploaded_edited_videos'] = 1;
                    $lead_data['terms'] = 1;

                    $lead_data['rating_point'] = ($video->rating_points ? $video->rating_points : 7);
                    $lead_data['rating_comments'] = ($video->rating_comments ? $video->rating_comments : '');
                    $lead_data['closing_date'] =  date('Y-m-d');
                    $lead_data['revenue_share'] = ($video->revenue_share ? $video->revenue_share : 50);
                    $lead_data['slug'] = slug($lead_data['video_title'], 'video_leads', 'slug');
                    $lead_data['load_view'] = 4;
                    $lead_data['shotVideo'] = 1;
                    $lead_data['haveOrignalVideo'] = 1;
                    $lead_data['information_pending'] = 1;
                    $lead_data['video_elephant'] = $video->video_elephant;
                    $lead_data['raw_video'] = $video->raw_video;
                    $lead_data['description_updated'] = $video->description_updated;
                    $lead_data['exclusive_status'] = $video->exclusive_status;
                    $lead_data['facebook'] = $video->facebook;
                    $lead_data['instagram'] = $video->instagram;
                    $lead_data['confidence_level'] = $video->confidence_level;
                    $this->db->insert('video_leads', $lead_data);
                    $lead_id = $this->db->insert_id();

                    $key = hash("sha1", $video->unique_key, FALSE);
                    $key = strtoupper($key);
                    $data = array(
                        'unique_key' => $video->unique_key,
                        'encrypted_unique_key' => $key
                    );

                    $this->db->where('id', $lead_id);
                    $this->db->update('video_leads', $data);

                    /* if (!is_numeric($video->category)) {
                         $cQuery = $this->db->query('
                              SELECT *
                              FROM categories
                              WHERE id = "'.$video->category.'"
                          ');
                          if($cQuery->num_rows() == 0){
                              $category['title'] = $video->category;
                              $category['parent_id'] = 0;
                              //$category['image'] ='';
                              $category['status'] = 1;
                              $category['created_at'] = date('Y-m-d H:i:s');
                              $category['updated_at'] = date('Y-m-d H:i:s');
                              $category['created_by'] = 1;
                              $category['updated_by'] = 1;
                              $category['slug'] = slug($video->category,'categories','slug');
                              $this->db->insert('categories',$category);
                              $cat_id = $this->db->insert_id('categories');
                              $video->category = $cat_id;
                          }


                     }*/
                    /**
                     * Insert video data
                     */

                    if ($video->video_elephant === "Non-Exclusive" || $video->video_elephant === "Exclusive"){
                        $mrss=1;
                    }
                    else{
                        $mrss=0;
                    }
                    $video_data = array();
                    $video_data['created_at'] = $currnt_date;
                    $video_data['deleted_at'] = '';//$currnt_date;
                    $video_data['created_by'] = 1;
                    $video_data['updated_by'] = 1;
                    $video_data['status'] = 1;
                    $video_data['embed'] = 0;
                    $video_data['is_wooglobe_video'] = 1;
                    $video_data['real_deciption_updated'] = 1;
                    $video_data['is_category_verified'] = 1;
                    $video_data['is_tags_verified'] = 1;
                    $video_data['is_title_verified'] = 1;
                    $video_data['is_description_verified'] = 1;
                    $video_data['is_orignal_video_verified'] = 1;
                    $video_data['video_verified'] = 1;
                    $video_data['is_high_quality'] = 1;
                    $video_data['question_video_taken'] = $video->where_taken;
                    $video_data['question_video_context'] = $video->context;
                    $video_data['question_when_video_taken'] = $video->when_taken;//date('Y-m-d H:i:s');
                    $video_data['question_video_information'] = $video->other_information;
                    $video_data['title'] = $video->publish_video_title;
                    $video_data['description'] = $video->real_description_updated;
                    $video_data['tags'] = $video->tags;
                    $video_data['youtube_id'] = $video->youtube_video_id;
                    $video_data['url'] = $video->lead_video_url;
                    //$video_data['thumbnail'] = $video->	portal_thumbnail_directory;
                    $video_data['user_id'] = $client_id;
                    $video_data['lead_id'] = $lead_id;
                    $video_data['category_id'] = $video->category;
                    $video_data['mrss'] = $mrss;
                    $video_data['mrss_categories'] = $video->mrss_categories;
                    $video_data['video_type_id'] = 1;

                    $video_data['bulk'] = 1;
                    $video_data['slug'] = slug($lead_data['video_title'], 'videos', 'slug');
                    $this->db->insert('videos', $video_data);
                    $video_id = $this->db->insert_id();

                    /**
                     *  Add into video_categories table
                     */
                    $cat_data['video_id'] = $video_id;
                    $cat_data['category_id'] = $video->category;
                    $this->db->insert('video_categories', $cat_data);

                    /**
                     *  Insert  lead_action_dates table
                     */



                    $lead_action_dates_data = array();
                    $lead_action_dates_data['lead_id'] = $lead_id;
                    $lead_action_dates_data['lead_rated_date'] = $currnt_date;
                    $lead_action_dates_data['contract_sent_date'] = $currnt_date;
                    $this->db->insert('lead_action_dates', $lead_action_dates_data);
                    action_add($lead_id, $video_id, 0, $this->sess->userdata('adminId'), 1, 'Lead converted to deal');

                    /**
                     *  Insert raw video path/url in raw_video table
                     * Table: raw_video
                     * fields are video_id, lead_id, url
                     */
                    $raw_video_data = array();
                    // $file = fopen("C:/Users/muhammad.waqas/Desktop/new-raw.csv", "r"); /*change the path according to csv*/
                   /* $file_data = $this->csvimport->get_array("/var/www/html/wooglobe/admin/app/controllers/raw.csv",["unique_key","url"]);


                    foreach ($file_data as $data) {

                        //$csv_raw_data = fgetcsv($file);

                        $this->db->select('videos.id, videos.lead_id')
                            ->from('videos')
                            ->join('video_leads', 'video_leads.id = videos.lead_id');
                        $this->db->where('video_leads.unique_key',$data["unique_key"]);
                        $query = $this->db->get();



                        if ($query->num_rows() > 0) {
                            $video_id_lead_id = $query->result_array();
                            foreach ($video_id_lead_id as $video_id_lead_id) {
                                $raw_data['video_id'] = $video_id_lead_id['id'];
                                $raw_data['lead_id'] = $video_id_lead_id['lead_id'];
                                $raw_data['url'] = $data["url"];
                                $this->db->insert('raw_video', $raw_data);



                            }


                        }



                    }*/




                    /*$raw_video_data['video_id'] = $video_id;
                    $raw_video_data['lead_id'] = $lead_id;
                    $raw_video_data['url'] = $video->raw_file_directory;
                    $this->db->insert('raw_video', $raw_video_data);*/

                    /**
                     * Insert data for edit_videos
                     * Table: edit_video
                     * Fields: video_id, fb_url, yt_url, portal_url, fb_thumb, yt_thumb, portal_thumb
                     */
                    /*	$edited_video_data = array();
                        $edited_video_data['video_id'] = $video_id;
                        $edited_video_data['fb_url'] = $video->facebook_directory;
                        $edited_video_data['yt_url'] = $video->youtube_directory;
                        $edited_video_data['portal_url'] = $video->portal_directory;
                        $edited_video_data['fb_thumb'] = $video->facebook_thumbnail_directory;
                        $edited_video_data['yt_thumb'] = $video->youtube_thumbnail_directory;
                        $edited_video_data['portal_thumb'] = $video->portal_thumbnail_directory;
                        $this->db->insert('edited_video', $edited_video_data);*/

                    /* $file_directory = strtolower(trim($video->first_name).'_'.trim($video->last_name));
                     $rawfile_path = './wooglobe/raw_files/'.$file_directory.'/';
                     $upload_path =  './../uploads/videos/';
                     $all_raw_files = get_filenames($rawfile_path);
                     $edited_videos = array();
                     foreach($all_raw_files as $raw_file){
                         $file_info = pathinfo($rawfile_path.$raw_file);
                         $currentFilePath = $rawfile_path.$raw_file;
                         $new_file_name = md5($file_info['filename']).'.'.$file_info['extension'];
                         $newFilePath = $upload_path.'raw_videos/'.$new_file_name;
                         $fileMoved = rename($currentFilePath, $newFilePath);
                         $raw_video_dbdata['video_id'] = $video_id;
                         $raw_video_dbdata['lead_id'] = $lead_id;
                         $raw_video_dbdata['url'] = 'uploads/videos/'.$new_file_name;
                         $this->db->insert('raw_video',$raw_video_dbdata);
                     }
                     $portalfile_path = './wooglobe/portal/'.$file_directory.'/';
                     $portalthumb_path = './wooglobe/portal/thumbnail/'.$file_directory.'/';
                     $all_portal_files = get_filenames($portalfile_path);
                     $all_portal_thumb = get_filenames($portalthumb_path);
                     foreach($all_portal_files as $portal_file){
                         $file_info = pathinfo($portalfile_path.$portal_file);
                         $currentFilePath = $portalfile_path.$portal_file;
                         $new_file_name = md5($file_info['filename']).'.'.$file_info['extension'];
                         $newFilePath = $upload_path.'edited/'.$new_file_name;
                         $fileMoved = rename($currentFilePath, $newFilePath);
                         $edited_videos['portal_url'] = 'uploads/videos/edited/'.$new_file_name;

                     }
                     foreach($all_portal_thumb as $portal_thumb){
                         $file_info = pathinfo($portalthumb_path.$portal_thumb);
                         $currentFilePath = $portalthumb_path.$portal_thumb;
                         $new_file_name = md5($file_info['filename']).'.'.$file_info['extension'];
                         $newFilePath = $upload_path.'edited/thumbnail/'.$new_file_name;
                         $fileMoved = rename($currentFilePath, $newFilePath);
                         $edited_videos['portal_thumb'] = 'uploads/videos/edited/thumbnail/'.$new_file_name;

                     }
                     $fbfile_path = './wooglobe/facebook/'.$file_directory.'/';
                     $fbthumb_path = './wooglobe/facebook/thumbnail/'.$file_directory.'/';
                     $all_fb_files = get_filenames($fbfile_path);
                     $all_fb_thumb = get_filenames($fbthumb_path);
                     foreach($all_fb_files as $fb_file){
                         $file_info = pathinfo($fbfile_path.$fb_file);
                         $currentFilePath = $fbfile_path.$fb_file;
                         $new_file_name = md5($file_info['filename']).'.'.$file_info['extension'];
                         $newFilePath = $upload_path.'edited/'.$new_file_name;
                         $fileMoved = rename($currentFilePath, $newFilePath);
                         $edited_videos['fb_url'] = 'uploads/videos/edited/'.$new_file_name;

                     }
                     foreach($all_fb_thumb as $fb_thumb){
                         $file_info = pathinfo($fbthumb_path.$fb_thumb);
                         $currentFilePath = $fbthumb_path.$fb_thumb;
                         $new_file_name = md5($file_info['filename']).'.'.$file_info['extension'];
                         $newFilePath = $upload_path.'edited/thumbnail/'.$new_file_name;
                         $fileMoved = rename($currentFilePath, $newFilePath);
                         $edited_videos['fb_thumb'] = 'uploads/videos/edited/thumbnail/'.$new_file_name;

                     }
                     $ytfile_path = './wooglobe/youtube/'.$file_directory.'/';
                     $ytthumb_path = './wooglobe/youtube/thumbnail/'.$file_directory.'/';
                     $all_yt_files = get_filenames($ytfile_path);
                     $all_yt_thumb = get_filenames($ytthumb_path);
                     foreach($all_yt_files as $yt_file){
                         $file_info = pathinfo($ytfile_path.$yt_file);
                         $currentFilePath = $ytfile_path.$yt_file;
                         $new_file_name = md5($file_info['filename']).'.'.$file_info['extension'];
                         $newFilePath = $upload_path.'edited/'.$new_file_name;
                         $fileMoved = rename($currentFilePath, $newFilePath);
                         $edited_videos['yt_url'] = 'uploads/videos/edited/'.$new_file_name;

                     }
                     foreach($all_yt_thumb as $yt_thumb){
                         $file_info = pathinfo($ytthumb_path.$yt_thumb);
                         $currentFilePath = $ytthumb_path.$yt_thumb;
                         $new_file_name = md5($file_info['filename']).'.'.$file_info['extension'];
                         $newFilePath = $upload_path.'edited/thumbnail/'.$new_file_name;
                         $fileMoved = rename($currentFilePath, $newFilePath);
                         $edited_videos['yt_thumb'] = 'uploads/videos/edited/thumbnail/'.$new_file_name;

                     }
                     $edited_videos['video_id'] = $video_id;
                     $this->db->insert('edited_video',$edited_videos);*/
                }

                $row_num++;
            }
            $response['problems'] = $error_text;
        }
        echo json_encode($response);
        exit;
    }

    public function csv_issues($video, $row)
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'bulk');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }

        $error_text = '';
        if (empty($video->lead_video_title)) {
            $error_text .= "<li>Title is empty</li>";
        }
        if (empty($video->client_first_name)) {
            $error_text .= "<li>First name is empty</li>";
        }
        if (empty($video->client_first_name)) {
            $error_text .= "<li>Last name is empty</li>";
        }
        if (empty($video->client_email)) {
            $error_text .= "<li>Email is empty</li>";
        }
        if (empty($video->category)) {
            $error_text .= "<li>Category is empty</li>";
        }
        if (empty($video->youtube_video_id)) {
            $error_text .= "<li>Video ID is empty</li>";
        }

        if ($error_text != "") {
            $error_text = '<br><br><b>Row # ' . $row . '</b><ul>' . $error_text . '</ul>';
        }

        return $error_text;
    }

}
