<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_Compilation extends APP_Controller
{
    const MAILBOX_INBOX = 'INBOX';
    const MAILBOX_ALL = '[Gmail]/All Mail';
    const MAILBOX_DRAFTS = '[Gmail]/Drafts';
    const MAILBOX_IMPORTANT = '[Gmail]/Important';
    const MAILBOX_SENT = '[Gmail]/Sent Mail';
    const MAILBOX_SPAM = '[Gmail]/Spam';
    const MAILBOX_STARRED = '[Gmail]/Starred';
    const MAILBOX_TRASH = '[Gmail]/Trash';

    public function __construct()
    {

        parent::__construct();
        $this->data['active'] = 'video_compilation';
        $css = array(
            'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css',
            'assets/js/vid_up/jquery.fileuploader.min.css',
            'assets/js/vid_up/jquery.fileuploader-theme-dragdrop.css',
            'assets/js/vid_up/font/font-fileuploader.css',
            'assets/js/vid_up/font/font-fileuploader.ttf',
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
            'bower_components/ion.rangeslider/js/ion.rangeSlider.min.js',
            'bower_components/jquery.inputmask/dist/jquery.inputmask.bundle.js',
            'assets/js/custom/datatables/datatables.uikit.min.js',
            'assets/js/custom/dropify/dist/js/dropify.min.js',
            'assets/js/pages/forms_file_input.min.js',
            'assets/js/pages/forms_advanced.js',
            'bower_components/dragula.js/dist/dragula.min.js',
            'assets/js/pages/page_scrum_board.js',
            'assets/js/jquery.charactercounter.min.js',
            'assets/js/vid_up/jquery.fileuploader.min.js',
            'assets/js/vcompilation10.js',
            //'assets/js/videos.js'getTemplateId,// exclusive partner
            'assets/js/vid_up/custom.js',
            'assets/js/vid_up/jquery.fileuploader.min.js',
            'bower_components/tinymce/tinymce.min.js',
            'assets/js/pages/forms_wysiwyg.js',
            //'assets/js/pages/page_mailbox.js',
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);

        $this->data['assess'] = array(
            'list' => role_permitted_html(false, 'video_compilation'),
            'signed' => role_permitted_html(false, 'video_compilation', 'contract_signed'),
            'rejected' => role_permitted_html(false, 'video_compilation', 'rejected'),
            'can_delete' => role_permitted_html(false, 'video_compilation', 'can_delete'),
            'can_edit' => role_permitted_html(false, 'video_compilation', 'can_edit'),
            'deals' => role_permitted_html(false, 'video_rights', 'deals'),


        );
        $this->load->model('Video_Compilation_Model', 'compilation');
        $this->load->model('Video_Deal_Model', 'd');
        $this->load->model('User_Model', 'user');
        $this->load->model('Communication_Model', 'email');
        $this->load->model('Video_Model', 'video');
        $this->load->model('Video_Lead_Model', 'lead');
        $this->load->library('youtube');
        $this->load->library('fb');
        $this->load->model('Categories_Model', 'mrss');
        $this->load->model('Earning_Type_Model', 'earning_type');
        $this->load->model('Social_Sources_Model', 'source');
        $this->load->model('Staff_Model', 'staff');


    }

    public function index()
    {
        if ($this->input->is_ajax_request()) {
            $auth_ajax = auth_ajax();
            if ($auth_ajax) {
                echo json_encode($auth_ajax);
                exit;
            }

            $params = $this->input->post();
            $limit = $params['limit'];
            $offset = $params['offset'];
            $additional_info = [];
            if (isset($params['order_by']) && isset($params['sort'])) {
                $order_by_column = $params['order_by'];
                $sort_order = $params['sort'];
            } else {
                $order_by_column = 'vl.created_at';
                $sort_order = 'DESC';
            }

            if (isset($params['filter_by_curr_stage']) && $params['filter_by_curr_stage'] == 'true') {
                $additional_info['join_stage_filter'] = true;
            }
            $view = '';
            $num_of_recs = 0;

            switch ($params['deal_stage']) {


                case 'scrum_column_compilation':
                    $view = 'compilation';
                    $this->data[$view] = $this->compilation->getCSompilationLeads(str_replace('vl', 'cl', $order_by_column), $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_assigned':
                    $view = 'assigned';
                    $this->data[$view] = $this->compilation->getAssignedLeads(str_replace('vl', 'cl', $order_by_column), $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_progressed':
                    $view = 'progressed';
                    $this->data[$view] = $this->compilation->getProgressedLeads(str_replace('vl', 'cl', $order_by_column), $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_completed':
                    $view = 'completed';
                    $this->data[$view] = $this->compilation->getCompletedLeads(str_replace('vl', 'cl', $order_by_column), $sort_order, $limit, $offset, $additional_info);
                    break;

                default:
                    $view = '';
                    $this->data[$view] = array();
                    $videos_list_view = '';
            }

            $result_rows = $this->data[$view]->num_rows();

            $videos_list_view = $this->load->view('compilations/deal_stage_views/' . $view, $this->data, true);

            echo json_encode(array('status' => 200, 'view' => $videos_list_view, 'result_rows' => $result_rows, 'max_offset_reached' => ($result_rows < $limit) ? (1) : (0)));
            exit;
        } else {
            auth();
            role_permitted(false, 'video_compilation');
            if (in_array($_SERVER['REMOTE_ADDR'], ['localhost', '127.0.0.1', '::1'])) {
                $this->data['download_url'] = $this->data['url'];
            } else {
                //$this->data['download_url'] = 'https://downloads.'.$_SERVER['HTTP_HOST'].'.com/';
                $this->data['download_url'] = 'https://downloads.wooglobe.com/admin/';
            }

            //echo $this->data['download_url'];exit;
            $this->data['title'] = 'Youtube Compilations';


            $view_functions = array(

                'compilation' => 'getCSompilationLeads',
                'assigned' => 'getAssignedLeads',
                'progressed' => 'getProgressedLeads',
                'completed' => 'getCompletedLeads',

            );

            foreach ($view_functions as $view => $func) {
                $this->data[$view] = $this->compilation->$func();
                $countFunc = $func . 'Count';
                $this->data['num_' . $view] = $this->compilation->$countFunc();
                $this->data[$view] = $this->load->view('compilations/deal_stage_views/' . $view, $this->data, true);
            }

            $this->data['content'] = $this->load->view('compilations/deals', $this->data, true);
            //$this->data['content'] = $this->load->view('video_deals/deals', $this->data, true);
            $this->load->view('common_files/template', $this->data);
        }
    }





    public function compilations_urls()
    {
        auth();
        role_permitted(false, 'video_compilation', 'compilations_urls');
        $this->validation->set_rules('urls', 'Video URLs', 'trim|required');
        if ($this->validation->run() == FALSE) {
            $this->data['title'] = 'Compilations URLs';
            $this->data['content'] = $this->load->view('video_rights/url', $this->data, true);
            $this->load->view('common_files/template', $this->data);
        } else {
            $urls = $this->input->post('urls');
            $urls = explode(PHP_EOL, $urls);
            $unique_group_key_list = array_column($this->db->distinct()->select('lead_group_id')->get('compilation_leads')->result_array(), 'lead_group_id');
            $this->load->helper('string');
            $rand = random_string('numeric', 8);
            while (in_array("YTG" . $rand, $unique_group_key_list)) {
                $rand = random_string('numeric', 8);
            }
            $unique_group_key = "YTG" . $rand;

            foreach ($urls as $url) {
                $q = $this->db->query("SELECT * FROM compilation_leads WHERE yt_url LIKE  '%$url%'");
                if($q->num_rows() == 0){
                    $unique_key_list = array_column($this->db->distinct()->select('wg_id')->get('compilation_leads')->result_array(), 'wg_id');
                    $this->load->helper('string');
                    $this->load->library('youtube');
                    $random = random_string('numeric', 6);
                    while (in_array("YTC" . $random, $unique_key_list)) {
                        $random = random_string('numeric', 6);
                    }
                    $unique_key = "YTC" . $random;
                    $dbData['lead_group_id'] = $unique_group_key;
                    $dbData['wg_id'] = $unique_key;
                    $dbData['yt_url'] = $url;
                    $video_id = extrect_video_id($url);
                    $dbData['yt_id'] = $video_id;
                    $views = 0;
                    $category = '';
                    $title = '';
                    $thumb = '';
                    if (!empty($video_id)) {
                        $api_handle = curl_init();

                        curl_setopt($api_handle, CURLOPT_URL, 'https://youtube.googleapis.com/youtube/v3/videos?id=' . trim($video_id) . '&key=' . 'AIzaSyByLeu5LWf8z7Mf9cNAek0j-AF9x3wppJM' . '&part=snippet,statistics,topicDetails');
                        curl_setopt($api_handle, CURLOPT_POST, FALSE);
                        curl_setopt($api_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
                        curl_setopt($api_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
                        curl_setopt($api_handle, CURLOPT_RETURNTRANSFER, TRUE);

                        $api_result = curl_exec($api_handle);
                        if ($api_result) {
                            $yt_video_info = json_decode($api_result);

                            if (isset($yt_video_info->items[0])) {
                                $views = $yt_video_info->items[0]->statistics->viewCount;
                                $title = $yt_video_info->items[0]->snippet->title;
                                $thumb = $yt_video_info->items[0]->snippet->thumbnails->default->url;

                                $api_handle2 = curl_init();

                                curl_setopt($api_handle2, CURLOPT_URL, 'https://youtube.googleapis.com/youtube/v3/videoCategories?part=snippet&id=' . $yt_video_info->items[0]->snippet->categoryId . '&key=AIzaSyByLeu5LWf8z7Mf9cNAek0j-AF9x3wppJM');
                                curl_setopt($api_handle2, CURLOPT_POST, FALSE);
                                curl_setopt($api_handle2, CURLOPT_SSL_VERIFYPEER, FALSE);
                                curl_setopt($api_handle2, CURLOPT_SSL_VERIFYHOST, FALSE);
                                curl_setopt($api_handle2, CURLOPT_RETURNTRANSFER, TRUE);

                                $api_result2 = curl_exec($api_handle2);

                                if ($api_result2) {
                                    $yt_video_info2 = json_decode($api_result2);
                                    if (isset($yt_video_info2->items[0])) {
                                        $category = $yt_video_info2->items[0]->snippet->title;
                                    }
                                }

                            }

                        }

                    }
                    $dbData['views'] = $views;
                    $dbData['category'] = $category;
                    $dbData['title'] = $title;
                    $dbData['thumb'] = $thumb;
                    $dbData['created_at'] = date('Y-m-d H:i:s');
                    $dbData['updated_at'] = date('Y-m-d H:i:s');
                    $dbData['created_by'] = $this->sess->userdata('adminId');
                    $dbData['updated_by'] = $this->sess->userdata('adminId');
                    $this->db->insert('compilation_leads', $dbData);
                }

            }
            redirect('compilations_urls_info/'.$unique_group_key);
        }
    }

    public function compilations_urls_info($group_id, $id = null)
    {
        auth();
        role_permitted(false, 'video_compilation', 'compilations_urls_info');

        $this->data['title'] = 'Compilations URLs Info';
        $this->data['leads'] = $this->compilation->getCompilationLeadsByGroupId($group_id, $id);
        $this->data['leads_infos'] = $this->compilation->getLeadInfo($group_id, $id);

        $this->data['staffs'] = $this->staff->getAllMembers('a.*', '', 0, 0, 'a.name ASC');
        $this->data['id'] = $id;

        $this->data['content'] = $this->load->view('compilations/url_info', $this->data, true);
        $this->load->view('common_files/template', $this->data);

    }

    public function compilations_urls_info_edit($group_id, $id = null)
    {
        auth();
        role_permitted(false, 'video_compilation', 'compilations_urls_info');

        $this->data['title'] = 'Compilations URLs Info';
        $this->data['leads'] = $this->compilation->getCompilationLeadsByGroupId($group_id, $id);
        $this->data['staffs'] = $this->staff->getAllMembers('a.*', '', 0, 0, 'a.name ASC');
        $this->data['id'] = $id;

        $this->data['content'] = $this->load->view('compilations/url_info_edit', $this->data, true);
        $this->load->view('common_files/template', $this->data);

    }

    public function compilations_urls_info_save()
    {
        auth();
        role_permitted(false, 'video_compilation', 'compilations_urls_info');
        $this->load->library('tiktok');
        $input = $this->input->post();
        $leads = $this->input->post('leads');
        $leads_info = array_values($this->input->post('leads_info'));
        if(isset($leads)){
            foreach ($leads as $lead) {
                if(!empty($lead['assign_to'])){
                    $lead['status'] = 'Assigned';
                }
                $lead['updated_at'] = date('Y-m-d H:i:s');
                $lead['updated_by'] = $this->sess->userdata('adminId');
                $this->db->where('lead_group_id', $lead['lead_group_id']);
                $this->db->where('id', $lead['id']);
                $this->db->update('compilation_leads', $lead);
                $this->db->where('lead_group_id', $lead['lead_group_id']);
                $this->db->where('lead_id', $lead['id']);
                $this->db->delete('compilation_leads_info');
            }
        }

        
        if(isset($leads_info) && is_array($leads_info)){
            $this->db->where('lead_group_id', $leads_info[0]['lead_group_id']);
            $this->db->where('lead_id', $leads_info[0]['lead_id']);
            $this->db->delete('compilation_leads_info');

            foreach ($leads_info as $lead_in) {
                $this->db->where('lead_group_id', $lead_in['lead_group_id']);
                $this->db->where('id', $lead_in['lead_id']);
                $this->db->update('compilation_leads', array('status'=>'In-Progress','updated_at'=>date('Y-m-d H:i:s'),'updated_by'=>$this->sess->userdata('adminId')));
                $lead_in['tt_id'] = extrect_video_id($lead_in['tt_url']);
                $lead_in['updated_at'] = date('Y-m-d H:i:s');
                $lead_in['created_at'] = date('Y-m-d H:i:s');
                $lead_in['created_by'] = $this->sess->userdata('adminId');
                $lead_in['updated_by'] = $this->sess->userdata('adminId');
                $this->db->insert('compilation_leads_info', $lead_in);
            }
        }

        $this->sess->set_flashdata('msg', 'Information Updated successfully!');
        if(isset($input['detail_page'])){
            $gid = $input['lead_group_id'];
            $wid = $input['lead_id'];
            redirect('video-compilation-detail/'.$wid);
        }else{
            redirect('youtube_compilations');
        }


    }

    public function compilations_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_compilation');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'ASC';
        $column = 'cl.created_at';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
        $this->data['compilation'] = $this->compilation->getCSompilationLeads();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->compilation->getCSompilationLeadsCount();
        $response['data'] = $this->load->view('compilations/compilation', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }
    public function assigned_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_compilation');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'ASC';
        $column = 'cl.created_at';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
        $this->data['assigned'] = $this->compilation->getAssignedLeads();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->compilation->getAssignedLeadsCount();
        $response['data'] = $this->load->view('compilations/assigned', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }
    public function progressed_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_compilation');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'ASC';
        $column = 'cl.created_at';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
        $this->data['assigned'] = $this->compilation->getAssignedLeads();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->compilation->getAssignedLeadsCount();
        $response['data'] = $this->load->view('compilations/assigned', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }
    public function completed_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_compilation');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'ASC';
        $column = 'cl.created_at';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
        $this->data['assigned'] = $this->compilation->getAssignedLeads();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->compilation->getAssignedLeadsCount();
        $response['data'] = $this->load->view('compilations/assigned', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function compilation_detail($id)
    {

        auth();
        role_permitted(false, 'video_compilation');

        $result = $this->compilation->getCompilationDealById($id, 'cl.*,ad.name');


        //echo 1;exit;
        if (!$result) {

            redirect($_SERVER['HTTP_REFERER']);

        } else {


            $this->data['dealData'] = $result;


        }
        $this->data['videos'] = $this->compilation->getLeadInfoByLeadId($id);
        $this->data['content'] = $this->load->view('compilations/compilation_detail', $this->data, true);
        $this->load->view('common_files/template', $this->data);

    }

    public function compilation_claimed()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_compilation', 'Claimed');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();
        //$this->load->library('sheet');
        $response['code'] = 200;
        $response['message'] = 'Lead Completed Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $dbData = $this->security->xss_clean($this->input->post());
        $id = $dbData['lead_id'];
        $lead = $this->compilation->getCompilationDealById($id);
        $url = $video->url;
        if ($dbData['claimed_source'] == 'Raw') {
            $url = '';
            $rawVideos = $this->d->getRawVideosByVideoId($videoid);
            foreach ($rawVideos->result() as $v) {
                $url .= $v->s3_url . ' , ';
            }
            $url = rtrim($url, ' , ');
        } else {

        }

        //$this->sheet->addRowToSpreadsheet(array(date('Y-m-d'),'','','https://www.youtube.com/watch?v='.$video->youtube_id,'',$url),241318973);
        //$this->sheet->addRowToSpreadsheet(array($lead->yt_id, 'https://www.youtube.com/watch?v=' . $lead->yt_id, 'Compilation Video', '', $lead->wg_id));
        unset($dbData['lead_id']);
        $dbData['status'] = 'Completed';
        $dbData = array('status'=>'Completed','updated_at'=>date('Y-m-d H:i:s'),'updated_by'=>$this->sess->userdata('adminId'));
        $this->db->where('id', $id);
        $this->db->update('compilation_leads', $dbData);
        //echo $this->db->last_query();exit;

        echo json_encode($response);
        exit;

    }

    private function getContent($url, $geturl = false)
    {
        $ch = curl_init();
        $options = array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Linux; Android 5.0; SM-G900P Build/LRX21T) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Mobile Safari/537.36',
            CURLOPT_ENCODING       => "utf-8",
            CURLOPT_AUTOREFERER    => false,
            CURLOPT_COOKIEJAR      => 'cookie.txt',
            CURLOPT_COOKIEFILE     => 'cookie.txt',
            CURLOPT_REFERER        => 'https://www.tiktok.com/',
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_MAXREDIRS      => 10,
        );
        curl_setopt_array( $ch, $options );
        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }
        $data = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($geturl === true)
        {
            return curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        }
        curl_close($ch);
        return strval($data);
    }

}
