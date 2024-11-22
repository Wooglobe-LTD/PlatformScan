<?php
use Kunnu\Dropbox\DropboxApp;
use Kunnu\Dropbox\DropboxFile;
use Kunnu\Dropbox\Dropbox;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

defined('BASEPATH') OR exit('No direct script access allowed');
class Video_Deals extends APP_Controller
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
        $this->data['active'] = 'video_deals';
        $css = array(
            'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css',
            'assets/js/vid_up/jquery.fileuploader.min.css',
            'assets/js/vid_up/jquery.fileuploader-theme-dragdrop.css',
            'assets/js/vid_up/font/font-fileuploader.css',
            'assets/js/vid_up/font/font-fileuploader.ttf',
            'assets/css/status_indicator.css'
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
            'bower_components/dragula.js/dist/dragula.min.js',
            'assets/js/pages/page_scrum_board.js',
            'assets/js/jquery.charactercounter.min.js',
            'assets/js/vid_up/jquery.fileuploader.min.js',
            'assets/js/popper.min.js',
            //'assets/js/vdeals13.js',

            'assets/js/vdeals32.js',
            'assets/js/manual_deal4.js',
            'assets/js/youtube_publish.js',

            //'assets/js/videos.js'getTemplateId,// exclusive partner
            'assets/js/vid_up/custom1.js',
            'assets/js/vid_up/jquery.fileuploader.min.js',
            'bower_components/tinymce/tinymce.min.js',
            'assets/js/pages/forms_wysiwyg.js',
            //'assets/js/pages/page_mailbox.js',
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);

        $this->data['assess'] = array(
            'list' => role_permitted_html(false, 'video_deals'),
            'signed' => role_permitted_html(false, 'video_deals', 'contract_signed'),
            'can_verify' => role_permitted_html(false, 'video_deals', 'can_verify'),
            'can_info' => role_permitted_html(false, 'video_deals', 'can_info'),
            'can_deal_information' => role_permitted_html(false, 'video_deals', 'can_deal_information'),
            'can_deal_corresponding_email' => role_permitted_html(false, 'video_deals', 'can_deal_corresponding_email'),
            'can_overall_email' => role_permitted_html(false, 'video_deals', 'can_overall_email'),
            'can_video_insights' => role_permitted_html(false, 'video_deals', 'can_video_insights'),
            'can_deal_payment' => role_permitted_html(false, 'video_deals', 'can_deal_payment'),
            'add_new_earning' => role_permitted_html(false, 'video_deals', 'add_new_earning'),
            'earning_edit' => role_permitted_html(false, 'video_deals', 'earning_edit'),
            'earning_delete' => role_permitted_html(false, 'video_deals', 'earning_delete'),
            'can_edit_confidence_level' => role_permitted_html(false, 'video_deals', 'can_edit_confidence_level'),
            'can_edit_internal_notes' => role_permitted_html(false, 'video_deals', 'can_edit_internal_notes'),
            'can_update_exclusive_status' => role_permitted_html(false, 'video_deals', 'can_update_exclusive_status'),
            'can_edit_title' => role_permitted_html(false, 'video_deals', 'can_edit_title'),
            'can_edit_description' => role_permitted_html(false, 'video_deals', 'can_edit_description'),
            'can_edit_tags' => role_permitted_html(false, 'video_deals', 'can_edit_tags'),
            'can_edit_message' => role_permitted_html(false, 'video_deals', 'can_edit_message'),
            'can_edit_rating_comment' => role_permitted_html(false, 'video_deals', 'can_edit_rating_comment'),
            'create_signer_link' => role_permitted_html(false, 'video_deals', 'create_signer_link'),
            'create_appearance_link' => role_permitted_html(false, 'video_deals', 'create_appearance_link'),
            'rejected' => role_permitted_html(false, 'video_deals', 'rejected'),
            'can_delete' => role_permitted_html(false, 'video_deals', 'can_delete'),
            'can_edit' => role_permitted_html(false, 'video_deals', 'can_edit'),
            'can_click_email' => role_permitted_html(false, 'video_deals', 'can_click_email'),
            'deals' => role_permitted_html(false, 'video_deals', 'deals'),
            'sent' => role_permitted_html(false, 'video_deals', 'sent'),
            'created' => role_permitted_html(false, 'video_deals', 'account_created'),
            'information' => role_permitted_html(false, 'video_deals', 'deal_information_pending'),
            'information_received' => role_permitted_html(false, 'video_deals', 'deal_information_received'),
            'upload_video' => role_permitted_html(false, 'video_deals', 'upload_video'),
            'won' => role_permitted_html(false, 'video_deals', 'closed_won'),
            'lost' => role_permitted_html(false, 'video_deals', 'closed_lost'),
            'reminder' => role_permitted_html(false, 'video_deals', 'welcome_mail_reminder'),
            'information_reminder' => role_permitted_html(false, 'video_deals', 'information_mail_reminder'),
            'verify' => role_permitted_html(false, 'video_deals', 'verify'),
            'can_upload_edited_videos' => role_permitted_html(false, 'video_deals', 'upload_edited_video'),
            'can_client_add' => role_permitted_html(false, 'clients', 'add_client'),
            'can_send_email' => role_permitted_html(false, 'video_deals', 'can_send_email'),
            'can_distribute' => role_permitted_html(false, 'video_deals', 'can_distribute'),
            'can_revenue_update' => role_permitted_html(false, 'video_deals', 'can_revenue_update'),
            'can_contract_cancel' => role_permitted_html(false, 'video_deals', 'can_contract_cancel'),
            'not_interested' => role_permitted_html(false, 'video_deals', 'not_interested'),
            'can_view_contract' => role_permitted_html(false, 'video_deals', 'can_view_contract'),
            'can_delete_lead' => role_permitted_html(false, 'video_deals', 'delete_lead'),
            'earnings_list'=>role_permitted_html(false,'earnings'),
            'earnings_can_add'=>role_permitted_html(false,'earnings','add_earning'),
            'earnings_can_edit'=>role_permitted_html(false,'earnings','update_earning'),
            'earnings_can_delete'=>role_permitted_html(false,'earnings','delete_earning'),
            'expense_list'=>role_permitted_html(false,'video_expenses'),
            'expense_can_add'=>role_permitted_html(false,'video_expenses','add_video_expense'),
            'expense_can_edit'=>role_permitted_html(false,'video_expenses','update_video_expense'),
            'expense_can_delete'=>role_permitted_html(false,'video_expenses','delete_video_expense'),
            'can_delete_second_signer'=>role_permitted_html(false,'video_deals','can_delete_second_signer'),
            'can_delete_appearance_release'=>role_permitted_html(false,'video_deals','can_delete_appearance_release'),
            'can_view_second_signer'=>role_permitted_html(false,'video_deals','can_view_second_signer'),
            'can_view_appearance_release'=>role_permitted_html(false,'video_deals','can_view_appearance_release'),
            'can_download_raw_files'=>role_permitted_html(false,'video_deals','can_download_raw_files'),
            'can_edit_facebook_publish_url'=>role_permitted_html(false,'video_deals','can_edit_facebook_publish_url'),
            'can_edit_youtube_publish_url'=>role_permitted_html(false,'video_deals','can_edit_youtube_publish_url'),
            'can_edit_edited_video_url'=>role_permitted_html(false,'video_deals','can_edit_edited_video_url'),
            'can_edit_edited_video_thumbnail'=>role_permitted_html(false,'video_deals','can_edit_edited_video_thumbnail'),
            'can_video_story'=>role_permitted_html(false,'video_deals','can_video_story'),
            'can_story_publish'=>role_permitted_html(false,'video_deals','can_story_publish'),
            'can_deal_report'=>role_permitted_html(false,'video_deals','can_deal_report'),
            //'can_view_contract'=>role_permitted_html(false,'video_deals','can_view_contract'),
            'can_upload_raw_videos'=>role_permitted_html(false,'video_deals','can_upload_raw_videos'),
            'can_delete_raw_videos'=>role_permitted_html(false,'video_deals','can_delete_raw_videos'),
            'can_edit_raw_videos'=>role_permitted_html(false,'video_deals','can_edit_raw_videos'),
            'can_edit_aws_document_url'=>role_permitted_html(false,'video_deals','can_edit_s3_document_url'),
            'can_edit_when_video_taken'=>role_permitted_html(false,'video_deals','can_edit_when_video_taken'),
            'can_edit_where_video_taken'=>role_permitted_html(false,'video_deals','can_edit_where_video_taken'),
            'can_edit_video_context'=>role_permitted_html(false,'video_deals','can_edit_video_context'),
            'can_edit_video_url'=>role_permitted_html(false,'video_deals','can_edit_video_url'),
            'can_add_to_white_list'=>role_permitted_html(false,'video_deals','can_add_to_white_list'),
            'can_edit_staff'=>role_permitted_html(false,'video_deals','can_edit_staff'),
            'can_edit_video_type'=>role_permitted_html(false,'video_deals','can_edit_video_types'),
            'can_edit_rating_point'=>role_permitted_html(false,'video_deals','can_edit_rating_point'),
            'can_edit_video_email'=>role_permitted_html(false,'video_deals','can_edit_video_email'),
            'can_sign_release'=>role_permitted_html(false,'video_deals','can_sign_release'),
            'can_edit_target_words'=>role_permitted_html(false,'video_deals','can_edit_target_words'),
        );

        $this->load->model('Video_Deal_Model', 'deal');
        $this->load->model('User_Model', 'user');
        $this->load->model('Communication_Model', 'email');
        $this->load->model('Video_Model', 'video');
        $this->load->model('Video_Lead_Model', 'lead');
        $this->load->library('youtube');
        $this->load->library('fb');
        $this->load->model('Categories_Model', 'mrss');
        $this->load->model('MRSS_Queue_Model', 'mrss_queue');
        $this->load->model('Earning_Type_Model','earning_type');
        $this->load->model('Social_Sources_Model','source');
        $this->load->model('Staff_Model','staff');
        $this->load->model('Dropbox_Report_Model','dropbox_model');
        $this->load->model('MrssBrands_Model','brand');
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
            $limit  = $params['limit'];
            $offset = $params['offset'];
            $additional_info = [];
            if (isset($params['order_by']) && isset($params['sort'])) {
                $order_by_column = $params['order_by'];
                $sort_order = $params['sort'];
            }
            else {
                $order_by_column = 'vl.closing_date';
                $sort_order = 'DESC';
            }

            if (isset($params['filter_by_curr_stage']) && $params['filter_by_curr_stage'] == 'true') {
                $additional_info['join_stage_filter'] = true;
            }
            $view = '';
            $num_of_recs = 0;

            $this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
            $this->data['editedVideos'] = $this->deal->getEditedVideos();
            $this->data['rawVideos'] = $this->deal->getRawVideos();
            switch ($params['deal_stage'])
            {
                /*
                case 'scrum_column_deats_rated':
                    $view = 'deals_rated';
                    $this->data[$view] = $this->deal->getRatedDeals($order_by_column, $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_contract_sent':
                    $view = 'contract_sent';
                    $this->data[$view] = $this->deal->getSentContracts($order_by_column, $order, $limit, $offset);
                    break;
                case 'scrum_column_contract_signed':
                    $view = 'contract_signed';
                    $this->data[$view] = $this->deal->getSignedDeals($order_by_column, $order, $limit, $offset);
                    break;
                case 'scrum_column_account_created':
                    $view = 'account_created';
                    $this->data[$view] = $this->deal->getAccountCreatedDeals($order_by_column, $order, $limit, $offset);
                    break;
                case 'scrum_column_account_deal_information':
                    $view = 'deal_information';
                    $this->data[$view] = $this->deal->getDealInformation($order_by_column, $order, $limit, $offset);
                    break;
                case 'scrum_column_account_upload':
                    $view = 'upload_videos';
                    $this->data[$view] = $this->deal->getDealUploadVideos($order_by_column, $sort_order, $limit, $offset, $additional_info);
                    break;
                */
                case 'scrum_column_account_deal_information_received':
                    $view = 'deal_received';
                    $this->data[$view] = $this->deal->getDealInformationReceived($order_by_column, $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_edited_upload':
                    $view = 'edited_videos';
                    $this->data[$view] = $this->deal->getDealUploadEditedVideos($order_by_column, $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_distribute':
                    $view = 'distribute';
                    $this->data[$view] = $this->deal->getDealDistributeVideos($order_by_column, $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_distribute_yt':
                    $view = 'distribute_yt';
                    $this->data[$view] = $this->deal->getDealYTDistributeVideos($order_by_column, $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_distribute_mrss':
                    $view = 'distribute_mrss';
                    $this->data[$view] = $this->deal->getDealMRSSDistributeVideos($order_by_column, $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_distribute_dropbox':
                    $view = 'distribute_dropbox';
                    $this->data[$view] = $this->deal->getDealDropboxDistributeVideos($order_by_column, $sort_order, $limit, $offset, $additional_info);
                    break;
//            case 'editedVideos':
//                $data = $this->deal->getEditedVideos($limit, $offset);
//                break;
                default:
                    $view = '';
                    $this->data[$view] = array();
                    $videos_list_view = '';
            }
            // $result_rows = $this->data[$view]->num_rows();
            $videos_list_view = $this->load->view('video_deals/deal_stage_views/'.$view, $this->data, true);

            echo json_encode(array('status' => 200, 'view' => $videos_list_view, 'result_rows' => $result_rows, 'max_offset_reached' => ($result_rows < $limit)?(0):(1)));
            exit;
        }
        else {
            auth();
            role_permitted(false, 'video_deals');
            if (in_array($_SERVER['REMOTE_ADDR'], ['localhost','127.0.0.1', '::1'])) {
                $this->data['download_url'] = $this->data['url'];
            }
            else {
                //$this->data['download_url'] = 'https://downloads.'.$_SERVER['HTTP_HOST'].'.com/';
                $this->data['download_url'] = 'https://downloads.wooglobe.com/admin/';
            }

            //echo $this->data['download_url'];exit;
            $this->data['title'] = 'Video Deals Management';
            $this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
            /*
            $this->data['dealsRated'] = $this->deal->getRatedDeals();
            $this->data['contractSent'] = $this->deal->getSentContracts();
            $this->data['signed'] = $this->deal->getSignedDeals();
            //$this->data['dealsRejected'] = $this->deal->getRejectedDeals();
            $this->data['accountCreated'] = $this->deal->getAccountCreatedDeals();
            $this->data['dealInformation'] = $this->deal->getDealInformation();
            $this->data['dealReceived'] = $this->deal->getDealInformationReceived();
            $this->data['uploadVideo'] = $this->deal->getDealUploadVideos();
            $this->data['distribute'] = $this->deal->getDealDistributeVideos();*/
            $this->data['editedVideos'] = $this->deal->getEditedVideos();

            $this->data['rawVideos'] = $this->deal->getRawVideos();
            //$this->data['dealWon'] = $this->deal->getDealWon();
            //$this->data['dealLost'] = $this->deal->getDealLost();
            //$this->data['notInterested'] = $this->deal->getNotInterested();
            $this->data['channels'] = $this->youtube->getChannels();
            //$this->data['categories'] = $this->youtube->getCategories();


           $view_functions = array(
                // 'deals_rated'      => 'getRatedDeals',
//                'contract_sent'    => 'getSentContracts',
//                'contract_signed'  => 'getSignedDeals',
//                'account_created'  => 'getAccountCreatedDeals',
//                'deal_information' => 'getDealInformation',
                'deal_received'    => 'getDealInformationReceived',

                'edited_videos'    => 'getDealUploadEditedVideos',
                
                'upload_videos'    => 'getDealUploadVideos',
                'distribute'       => 'getDealDistributeVideos',
               'distribute_yt'    => 'getDealYTDistributeVideos',
               'distribute_mrss'    => 'getDealMRSSDistributeVideos',
               'distribute_dropbox'    => 'getDealDropboxDistributeVideos'
            );

            foreach ($view_functions as $view => $func) {
                $this->data[$view] = $this->deal->$func();
                $countFunc = $func.'Count';
                $this->data['num_'.$view] = $this->deal->$countFunc();

                // if($view == 'distribute_yt')
                {
                    // echo $this->data['num_'.$view] . "\n";
                    // echo count($this->data[$view]->result()) . "</br>";
                    // var_dump($this->data);
                    // exit();
                }
                // $this->data['num_'.$view] = count($this->data);

                $this->data[$view] = $this->load->view('video_deals/deal_stage_views/'.$view, $this->data, true);
            }
            // exit;
            $this->data['content'] = $this->load->view('video_deals/deals', $this->data, true);
            //$this->data['content'] = $this->load->view('video_deals/deals', $this->data, true);
            $this->load->view('common_files/template', $this->data);
        }
    }

    public function assign_deal_client()
    {


        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'clients', 'add_client');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }

        $lead_id = $this->security->xss_clean($this->input->post('lead_id'));
        $client_id = $this->security->xss_clean($this->input->post('client_id'));

        $this->db->where('id', $lead_id);
        $this->db->update('video_leads', array('client_id' => $client_id));
		$this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['signed'] = $this->deal->getSignedDeals();
        $this->data['accountCreated'] = $this->deal->getAccountCreatedDeals();

        $response['code'] = 200;
        $response['message'] = 'Account Created successfully';
        $response['error'] = '';

        $response['signed'] = $this->load->view('video_deals/signed', $this->data, true);
        $response['created'] = $this->load->view('video_deals/created', $this->data, true);
        echo json_encode($response);
        exit;

    }

    public function deal_rated_refresh()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
		$this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['dealsRated'] = $this->deal->getRatedDeals($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['dealsRated']->num_rows();
        $response['data'] = $this->load->view('video_deals/rated', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function edited_refresh()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
		$this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['edited_videos'] = $this->deal->getDealUploadEditedVideos($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['edited_videos']->num_rows();
        $response['data'] = $this->load->view('video_deals/deal_stage_views/edited_videos', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function send_reminder_email()
    {
        /*ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);*/
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_send_email');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Remiander Email Sent Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $data = $this->input->post();

        $leadData = $this->email->getLeadById($data['id'], 'vl.*');
        $short_code = array(10 => "reminder_welcome_intro_email", 2 => "reminder_sending_contract_email", 3 => "reminder_lead_information_missing");

        $templateId = $this->deal->getTemplateId($data['id']);
        $template = $this->app->getEmailTemplateByCode($short_code[$leadData->status]);
        $action = 'Final ' . str_replace('_', ' ', $short_code[$leadData->status]);
        /*echo '<pre>';
        print_r($templateId);
        exit;*/
        if(!empty($templateId)){
            $ids = json_decode($templateId->ids, true);
            if ($templateId->email_template_id == 2) {
                $this->email->update_status($leadData->id, $leadData->status);
                $unique_key = $this->lead->getUniqueKey($leadData->id);

                if ($unique_key) {
                    $subject = $template->subject . '-' . $unique_key->unique_key;
                } else {
                    $subject = $template->subject;
                }
                /*echo $this->data['root'] . 'send_contract/' . $leadData->slug;
                exit;*/
                action_add($leadData->id, 0, 0, $this->sess->userdata('adminId'), 1, $action);
                if ($leadData->status==3){
                    // $url=root_url('login/');
                    $url = $this->data['root'] . 'login';

                }
                else{
                    $url = $this->data['root'] . 'video-contract/'.$leadData->slug;

                    //$url = root_url('send_contract/' . $leadData->slug);//$this->data['root'] . 'send_contract/' . $leadData->slug;
                }
                $message = dynStr($template->message, $ids);
                $message = str_replace('@LINK', $url, $message);
                $sent = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
                if ($sent) {
                    $vleads['reminder_sent'] = 2;
                    $this->db->where('id', $leadData->id);
                    $this->db->update('video_leads', $vleads);
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
        echo json_encode($response);
        exit;

    }
    public function description_send_reminder_email(){
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_send_email');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Remiander Email Sent Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $data = $this->input->post();

        $leadData = $this->email->getLeadById($data['id'], 'vl.*');
        $emailData = getEmailTemplateByCode('description_reminder');
        if ($emailData) {
            $str = $emailData->message;
            $subject = $emailData->subject;
            $ids = array(
                'users' => $leadData->client_id
            );
            $message = dynStr($str, $ids);
            $url = $leadData->video_url;
            $message = str_replace('@LINK', $url, $message);
            $sent= $this->email($leadData->email, $leadData->first_name, 'norelpy@wooglobe.com', 'WooGlobe', $subject, $message);


        }
        if($sent){
            action_add($leadData->id, 0, 0, 0, 1, 'Description Reminder Sent');
            echo json_encode($response);
            exit;
        }else{
            $response['code'] = 201;
            $response['message'] = 'Email not sent!';
            echo json_encode($response);
            exit;
        }

    }
    public function move_closewon()
    {
        $lead = $this->security->xss_clean($this->input->post());
        $lead_id=$lead['id'];
        $lead_closewon=$this->deal->move_closewon($lead_id);
        if($lead_closewon){
            $response['code'] = 200;
            $response['message'] = 'Move to Close Won successfully!';
            $response['error'] = '';
        }else{
            $response['code'] = 201;
            $response['message'] = 'Not Move to Close Won!';
            $response['error'] = '';
        }
        echo json_encode($response);
        exit;

    }
    public function pending_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
		$this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['dealInformation'] = $this->deal->getDealInformation($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['dealInformation']->num_rows();
        $response['data'] = $this->load->view('video_deals/pending', $this->data, true);
        $response['pending'] = '';
        echo json_encode($response);
        exit;
    }

    public function signed_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
		$this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['signed'] = $this->deal->getSignedDeals($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['signed']->num_rows();
        $response['data'] = $this->load->view('video_deals/signed', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function contract_sent_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
		$this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['contractSent'] = $this->deal->getSentContracts($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['contractSent']->num_rows();
        $response['data'] = $this->load->view('video_deals/sent', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function created_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
		$this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['accountCreated'] = $this->deal->getAccountCreatedDeals($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['accountCreated']->num_rows();
        $response['data'] = $this->load->view('video_deals/created', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function information_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
		$this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['dealInformation'] = $this->deal->getDealInformation($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['dealInformation']->num_rows();
        $response['data'] = $this->load->view('video_deals/information', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function information_received_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
		$this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['dealReceived'] = $this->deal->getDealInformationReceived($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['dealReceived']->num_rows();
        $response['data'] = $this->load->view('video_deals/information_received', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function rejected_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
        $this->data['dealsRejected'] = $this->deal->getRejectedDeals($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['dealsRejected']->num_rows();
        $response['data'] = $this->load->view('video_deals/rejected', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function won_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
        $this->data['dealWon'] = $this->deal->getDealWon($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['dealWon']->num_rows();
        $response['data'] = $this->load->view('video_deals/won', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function lost_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
        $this->data['dealLost'] = $this->deal->getDealLost($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['dealLost']->num_rows();
        $response['data'] = $this->load->view('video_deals/lost', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function distribute_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                if($params['column'] == 'rv.dropbox_status')
                {
                    $column = 'CASE';
                    $sort = "WHEN rv.dropbox_status = 'success' THEN 1";
                }
                else
                {
                    $column = $parms['column'];
                }
            }
        }
        $this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['distribute'] = $this->deal->getDealDistributeVideos($column, $sort);
        $this->data['editedVideos'] = $this->deal->getEditedVideos();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['distribute']->num_rows();
        $response['data'] = $this->load->view('video_deals/deal_stage_views/distribute', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function distribute_yt_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
        $this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['distribute_yt'] = $this->deal->getDealDistributeVideos($column, $sort);
        $this->data['editedVideos'] = $this->deal->getEditedVideos();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['distribute']->num_rows();
        $response['data'] = $this->load->view('video_deals/deal_stage_views/distribute_yt', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function distribute_mrss_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
        $this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['distribute_mrss'] = $this->deal->getDealDistributeVideos($column, $sort);
        $this->data['editedVideos'] = $this->deal->getEditedVideos();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['distribute']->num_rows();
        $response['data'] = $this->load->view('video_deals/deal_stage_views/distribute_mrss', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function distribute_dpbx_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
        $this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['distribute_dropbox'] = $this->deal->getDealDistributeVideos($column, $sort);
        $this->data['editedVideos'] = $this->deal->getEditedVideos();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['distribute']->num_rows();
        $response['data'] = $this->load->view('video_deals/deal_stage_views/distribute_dropbox', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function interested_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $sort = 'DESC';
        $column = 'vl.closing_date';
        $parms = $this->security->xss_clean($this->input->post());
        if (count($parms) > 0) {
            if (!empty($parms['sort'])) {
                $sort = $parms['sort'];
            }
            if (!empty($parms['column'])) {
                $column = $parms['column'];
            }
        }
        $this->data['notInterested'] = $this->deal->getNotInterested($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['notInterested']->num_rows();
        $response['data'] = $this->load->view('video_deals/interested', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }


    public function video_deals_listing()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
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

        $result = $this->deal->getAllDeals('CONCAT(vl.first_name," ",vl.last_name) as name,vl.id,vl.first_name,vl.last_name,vl.email,vl.video_title,vl.video_url, case when (vl.status = 1) THEN "Active" ELSE "Inactive" END as status', $search, $start, $limit, $orderby, $params['columns']);
        $resultCount = $this->deal->getAllDeals();
        $response = array();
        $data = array();

        foreach ($result->result() as $row) {

            $finalUrl = '';
            if (strpos($row->video_url, 'facebook.com/') !== false) {
                //it is FB video
                $finalUrl = 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($row->video_url) . '&show_text=1&width=200';
            } else if (strpos($row->video_url, 'vimeo.com/') !== false) {
                //it is Vimeo video
                $videoId = explode("vimeo.com/", $row->video_url);
                $videoId = $videoId[1];
                if (strpos($videoId, '&') !== false) {
                    $videoId = explode("&", $videoId);
                    $videoId = $videoId[0];
                }
                $finalUrl = 'https://player.vimeo.com/video/' . $videoId;
            } else if (strpos($row->video_url, 'youtube.com/') !== false) {
                //it is Youtube video
                $videoId = explode("v=", $row->video_url);
                $videoId = $videoId[1];
                if (strpos($videoId, '&') !== false) {
                    $videoId = explode("&", $videoId);
                    $videoId = $videoId[0];
                }
                $finalUrl = 'https://www.youtube.com/embed/' . $videoId;


            } else if (strpos($row->video_url, 'youtu.be/') !== false) {
                //it is Youtube video
                $videoId = explode("youtu.be/", $row->video_url);
                $videoId = $videoId[1];
                if (strpos($videoId, '&') !== false) {
                    $videoId = explode("&", $videoId);
                    $videoId = $videoId[0];
                }
                $finalUrl = 'https://www.youtube.com/embed/' . $videoId;


            } else {
                $finalUrl = $row->video_url;
            }
            $r = array();
            $r[] = $row->name;
            $r[] = $row->email;
            $r[] = $row->video_title;
            //$r[] = $row->video_url;
            //$r[] = $row->status;
            $links = '<a href="javascript:void(0);" class="play-video" data-id="' . $row->id . '" data-url="' . $finalUrl . '" data-title="' . $row->video_title . '"><i class="material-icons">&#xE04A;</i></a> ';
            $links .= '| <a href="javascript:void(0);" class="lead_detail" data-id="' . $row->id . '"><i class="material-icons">&#xE873;</i></a> ';
            /*if($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Categeory" href="javascript:void(0);" class="edit-category" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';

            }*/

            $r[] = $links;
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


    public function get_deal()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Record found!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->deal->getDealById($id, 'vl.id,vl.revenue_share,DATE_FORMAT(closing_date,"%a %m/%d/%Y") as closing_date,vl.first_name,vl.last_name,vl.email,vl.video_title,vl.video_url,vl.message,vl.rating_point,vl.rating_comments');
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Deal found!';
            $response['error'] = 'No Deal found!';
            $response['url'] = '';

        } else {

            $url = $result->video_url;

            $finalUrl = '';
            if (strpos($url, 'facebook.com/') !== false) {
                //it is FB video
                $finalUrl = 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($url) . '&show_text=1&width=200';
            } else if (strpos($url, 'vimeo.com/') !== false) {
                //it is Vimeo video
                $videoId = explode("vimeo.com/", $url);
                $videoId = $videoId[1];
                if (strpos($videoId, '&') !== false) {
                    $videoId = explode("&", $videoId);
                    $videoId = $videoId[0];
                }
                $finalUrl = 'https://player.vimeo.com/video/' . $videoId;
            } else if (strpos($url, 'youtube.com/') !== false) {
                //it is Youtube video
                $videoId = explode("v=", $url);
                $videoId = $videoId[1];
                if (strpos($videoId, '&') !== false) {
                    $videoId = explode("&", $videoId);
                    $videoId = $videoId[0];
                }
                $finalUrl = 'https://www.youtube.com/embed/' . $videoId;


            } else if (strpos($url, 'youtu.be/') !== false) {
                //it is Youtube video
                $videoId = explode("youtu.be/", $url);
                $videoId = $videoId[1];
                if (strpos($videoId, '&') !== false) {
                    $videoId = explode("&", $videoId);
                    $videoId = $videoId[0];
                }
                $finalUrl = 'https://www.youtube.com/embed/' . $videoId;


            } else {
                $finalUrl = $url;
            }
            $result->url = $finalUrl;
            $result->closing_date = date('M d, Y', strtotime($result->closing_date));
            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }


    public function import_deals()
    {
        auth();
        role_permitted(false, 'video_dealss');

        $url = 'getRecords';
        $param = 'selectColumns=Leads(First Name,Last Name,Email,Video URL,Video Title,Description,Created Time)';
        $zoho = zoho($url, $param);
        header("Content-type: application/json");
        $xml = simplexml_load_string($zoho, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        $rows = array();
        $count = 0;
        if (isset($array['result'])) {
            if (isset($array['result']['Leads'])) {
                if (isset($array['result']['Leads']['row'])) {
                    $rows = $array['result']['Leads']['row'];
                    foreach ($rows as $row) {
                        if (isset($row['FL']) && count($row['FL']) > 0) {
                            $result = $this->db->query('
                                SELECT id 
                                FROM video_leads
                                WHERE zoho_lead_id = ' . $row['FL'][0] . '
                            ');
                            if ($result->num_rows() == 0) {
                                $dbData['zoho_lead_id'] = $row['FL'][0];
                                $dbData['first_name'] = $row['FL'][1];
                                $dbData['last_name'] = $row['FL'][2];
                                $dbData['email'] = $row['FL'][3];
                                $dbData['created_at'] = $row['FL'][4];
                                $dbData['message'] = $row['FL'][5];
                                $dbData['video_url'] = $row['FL'][6];
                                $dbData['video_title'] = $row['FL'][7];
                                $dbData['status'] = 1;
                                $dbData['updated_at'] = date('Y-m-d H:i:s');
                                $this->db->insert('video_leads', $dbData);
                                $count++;
                            }

                        }
                    }

                }
            }
        }
        $this->sess->flashdata('msg', $count . ' Leads Imports Successfully!');
        redirect('video_leads');


    }

    public function welcome_reminder()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'welcome_mail_reminder');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Reminder email send successfully to the selected deals.';
        $response['error'] = '';
        $response['url'] = '';
        $request = $this->security->xss_clean($this->input->post());

        foreach ($request['created_reminder'] as $client_id) {
            $response['id'] = $client_id;
            $user = $this->user->getUserById($client_id);

            $this->db->where('id',$request['created_reminder_lead_id']);
            $query=$this->db->get('video_leads');
            $leadData=$query->row();


            $this->load->helper('string');
            $token = $user->verify_token;
            $emailData = getEmailTemplateByCode('welcome_email');
            if ($emailData) {
                $str = $emailData->message;
                $subject = $emailData->subject;
                $ids = array(
                    'users' => $client_id
                );
                $message = dynStr($str, $ids);
                // $url = $this->data['root'] . 'new-login/' . $token; -> waqas
                $url = "www.wooglobe.com" . '/new-login/' . $token;

                //$url = $this->urlmaker->shorten($url);
                //echo $url;exit;
                $message = str_replace('@LINK', $url, $message);
                //$message = 'Dear '.$dbData['full_name'].'<br>You have been register successfully as client and Your account credential given below<br> <b>Email :</b> '.$dbData['email'].' <br> <b>Password : </b> '.$password.'';
                $this->email($user->email, $user->full_name, 'norelpy@wooglobe.com', 'WooGlobe', 'Reminder : ' . $subject, $message);
                $this->db->set('reminder_sent', "2");
                $this->db->where('id',$request['created_reminder_lead_id']);
                $this->db->update('video_leads');
                action_add($leadData->id, 0, 0, 0, 1, 'Final Reminder Account Creation');

            }
        }
        echo json_encode($response);
        exit;
    }

    public function pending_information_reminder()
    {


        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'information_mail_reminder');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Notification email send successfully to the selected deals.';
        $response['error'] = '';
        $response['url'] = '';
        $request = $this->security->xss_clean($this->input->post());
        foreach ($request['information_reminder'] as $deal_id) {
            $response['id'] = $deal_id;
            $dealData = $this->deal->getDealDataById($deal_id);

            //echo $this->db->last_query();exit;
            $user = $this->user->getUserById($dealData->client_id);

            $emailData = getEmailTemplateByCode('reminder_lead_information_missing');

            if ($emailData) {
                $str = $emailData->message;
                $subject = $emailData->subject;
                $ids = array(
                    'users' => $dealData->client_id,
                );
                if ($dealData->unique_key) {
                    $subject = $emailData->subject . '-' . $dealData->unique_key;
                } else {
                    $subject = $emailData->subject;
                }
                $message = dynStr($str, $ids);

                /*$missing = '';
                $video = $this->deal->getVideoByLeadId($deal_id);
                $file = $this->deal->getRawVideoByLeadId($deal_id);

                if($file->num_rows() == 0){
                    $missing .= '<p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;">&nbsp;</p>
                                  <p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;"><strong>Unedited video file missing.</strong></p>
                                 ';
                }
                if($video){
                    if(empty($video->question_video_taken)){
                        $missing .= '<p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;">&nbsp;</p>
                                  <p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;"><strong>Video taken location missing.</strong></p>
                                 ';
                    }
                    if(empty($video->question_video_context)){
                        $missing .= '<p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;">&nbsp;</p>
                                  <p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;"><strong>Video context missing.</strong></p>
                                 ';
                    }
                    if(empty($video->question_when_video_taken)){
                        $missing .= '<p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;">&nbsp;</p>
                                  <p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;"><strong>Video taken date missing.</strong></p>
                                 ';
                    }
                    if(empty($video->question_video_information)){
                        $missing .= '<p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;">&nbsp;</p>
                                  <p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;"><strong>Video other important information missing.</strong></p>
                                 ';
                    }
                }
                if(empty($user->mobile)){
                    $missing .= '<p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;">&nbsp;</p>
                                  <p style="letter-spacing: normal; color: #000000; padding: 0px; margin: 0px;"><strong>Payment Info missing.</strong></p>
                                 ';
                }*/

                action_add($dealData->id, 0, 0, $this->sess->userdata('adminId'), 1, $subject);
                // $message = str_replace('@MISSING', $missing, $message);
               $url = $this->data['root'] . 'login';
               // $url=root_url('login/');

               // $url = $this->urlmaker->shorten($url);
                $message = str_replace('@LINK', $url, $message);
                $sent = $this->email($dealData->email, $dealData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
                $vleads['reminder_sent'] = 2;
                $this->db->where('id', $dealData->id);
                $this->db->update('video_leads', $vleads);
                /*$url = $this->data['root'].'new-login/'.$token;

                $url = $this->urlmaker->shorten($url);

                $message = str_replace('@LINK',$url,$message);
                $message = 'Dear '.$dbData['full_name'].'<br>You have been register successfully as client and Your account credential given below<br> <b>Email :</b> '.$dbData['email'].' <br> <b>Password : </b> '.$password.'';
                $this->email($user->email,$user->full_name,'norelpy@wooglobe.com','WooGlobe',$subject,$message);*/

            }
        }
        echo json_encode($response);
        exit;


    }
    
    public function get_conversion_rate(){
        // auth();
        // print_r("after auth");
        $response['code'] = 404;
        $response['message'] = '';
        $response['error'] = 'Conversion rate not found!';
        $response['url'] = '';

        $params = $this->security->xss_clean($this->input->post());

        $from = $params["from"];
        $to = $params["to"];

        if(!isset($from) || !isset($to)){
            echo json_encode($response);
            exit();
        }
        $response['code'] = 200;
        $response['error'] = '';
        $response['message'] = getConversionRate($from, $to);

        echo json_encode($response);
    }

    public function temp_bulk_update_payments(){
        $default_currency =  getCurrencyByCode('USD');;
        
        $sum = 0;
        $query = 'SELECT e.id, e.currency_id, e.partner_currency, e.client_net_earning, e.conversion_rate,e.paid_amount
                    FROM earnings e
                    WHERE e.paid = 1
                    AND e.status = 1
                    AND e.deleted = 0';

        $result = $this->db->query($query)->result_array();
        $dbData = [];
        foreach($result as $row){
            $to = $row["currency_id"];
            $from = $row["partner_currency"];
            $amount = $row["client_net_earning"];
            $rate = $row["conversion_rate"];

            list($conved_payment, $conved_rate) = convertPaymentCurrency($from, $to, $amount, $rate, $default_currency["id"]);
            if($conved_payment == 0){
                echo $to." ".$from." ".$amount." ".$rate."<br>";
            }
            $dbData["paid_amount"] = $conved_payment;
            $dbData["paid_conversion_rate"] = $conved_rate;

            $this->db->where('id',$row["id"]);
            $this->db->update('earnings',$dbData);
        }
        echo "Done";
    }

    public function landscapeConversion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            // Retrieve POST data
            $url = isset($_POST['url']) ? $_POST['url'] : '';
            $unique_key = isset($_POST['unique_key']) ? $_POST['unique_key'] : '';
            $lead_id = isset($_POST['lead_id']) ? $_POST['lead_id'] : '';
            $unique_key = trim($unique_key, '"');
            $lead_id = trim($lead_id, '"');
    
            // Validate the URL
            if (empty($url)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'URL is required']);
                return;
            }
            if (empty($unique_key)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'unique_key is required']);
                return;
            }
            if (empty($lead_id)) {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'lead_id is required']);
                return;
            }

            $s3_path = "uploads/" . $unique_key . "/landscape_converted/" . $unique_key."-landscape";

            $uploaded = LandscapeConversion_AWS($url,$s3_path);
            if($uploaded){
                $this->db->where("lead_id", $lead_id);
                $this->db->update("raw_video",array("landscape_converted_url"=>$uploaded));
                http_response_code(200);
                echo json_encode(['status' => 'success', 'message' => 'Uploaded successfuly.','data'=> $uploaded]);
                return;
            }
        } else {
            // If accessed directly or via non-AJAX request, send a 400 Bad Request
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        }
    }
    
    public function reupload_landscapeVideo() {
        // Get Data
        $unique_key = trim($_POST["unique_key"], '"');
        $lead_id = trim($_POST["lead_id"], '"');
        $local_temp_url = root_path() . 'uploads/videos/';
    
        // Check if the directory exists
        if (!is_dir($local_temp_url)) {
            // Directory does not exist, so create it
            mkdir($local_temp_url, 0777, true);
        } else {
            // Directory exists, ensure it has the correct permissions
            chmod($local_temp_url, 0777);
        }
    
        // Load the upload library
        $this->load->library('upload');
    
        // Define upload path and configuration
        $config['upload_path'] = $local_temp_url;
        $config['allowed_types'] = 'mp4|avi|mov'; 
        $config['max_size'] = 102400 * 10; // 100MB * 10 => 1 GB
    
        $this->upload->initialize($config);
    
        if (!$this->upload->do_upload('file_url')) {
            // Upload failed
            http_response_code(400); 
            $error = array('error' => $this->upload->display_errors());
            echo json_encode($error);
        } else {
            $data = $this->upload->data();
    
            $file_extension = pathinfo($data['file_name'], PATHINFO_EXTENSION);
    
            $file_name = $unique_key . '.' . $file_extension;
            $file_path = $local_temp_url . $file_name;
    
            // Rename the uploaded file
            rename($data['full_path'], $file_path);
    
            // Initialize S3 Client
            $s3Client = new S3Client([
                'version' => S3_VERSION,
                'region' => S3_REGION,
                'credentials' => [
                    'key' => S3_KEY,
                    'secret' => S3_SECRET,
                ],
            ]);
    
            try {
                // Upload the file to S3
                $result = $s3Client->putObject([
                    'Bucket' => S3_BUCKET,
                    'Key' => "uploads/" . $unique_key . "/landscape_converted/" . $file_name,
                    'SourceFile' => $file_path,
                    'ACL' => 'public-read',
                ]);
    
                if ($result['@metadata']['statusCode'] == 200) {
                    // Successfully uploaded to S3, now delete the local file
                    unlink($file_path);
    
                    echo json_encode(array('file_name' => $file_name, 's3_url' => $result['ObjectURL']));
                } else {
                    // Failed to upload to S3
                    http_response_code(500); // Send 500 Internal Server Error status
                    echo json_encode(array('error' => 'Failed to upload to S3'));
                }
            } catch (AwsException $e) {
                // Output AWS error message
                http_response_code(500); // Send 500 Internal Server Error status
                echo json_encode(array('error' => $e->getMessage()));
            }
        }
    }
     
    
    public function deal_detail($id)
    {

        auth();
        role_permitted(false, 'video_deals');
        $channelName = '';
        $channelLink = '';
        $manualRelease = true;
        $result = $this->deal->getDealById($id, '
            vl.id,
            vl.revenue_share,
            vl.unique_key,
            DATE_FORMAT(vl.closing_date,"%a %m/%d/%Y") as closing_date,
            DATE_FORMAT(vl.created_at,"%a %m/%d/%Y") as created_at,
            DATE_FORMAT(lad.lead_rated_date,"%a %m/%d/%Y") as deal_date,
            DATE_FORMAT(lad.contract_sent_date,"%a %m/%d/%Y") as sent_date,
            vl.updated_at,
            vl.client_id,
            vl.first_name,
            vl.last_name,
            vl.email,
            vl.video_title,
            vl.video_title_2,
            vl.video_url,
            vl.message,
            vl.rating_point,
            vl.rating_comments,
            dc.researcher_comment,
            dc.manager_comment,
            dc.local_url,
            vl.uploaded_edited_videos,
            vl.is_cn_updated,
            vl.trending,
            vl.is_ai_based,
            vl.status,
            vl.information_pending,
            vl.load_view,
            vl.published_portal,
            vl.published_yt,
            vl.published_fb,
            vl.reminder_sent,
            vl.video_elephant,
            vl.raw_video,
            vl.description_updated,
            vl.exclusive_status,
            vl.facebook,
            vl.instagram,
            vl.confidence_level,
            vl.video_comment,
            vl.staff_id,vl.third_party_staff_id,
            vl.is_story_content,vl.stroy_s3_url,
            vl.s3_url_story_thumb,
            ad.name staff_name,
            vl.report_issue_type,
            vl.report_issue_desc,
            vl.priority,
            vl.deleted
        ');

        $user = $this->deal->getUserByLeadId($id);
        if(empty($user->full_name) || empty($user->email) || empty($user->country_id)|| empty($user->state_id)|| empty($user->address)|| empty($user->zip_code)){
            $manualRelease = false;
        }

        $video = $this->deal->getVideosByLeadId($id);

        $rawVideos = $this->deal->getRawVideoByLeadId($id);

        $email = $this->deal->getEmailByLeadId($id);
        $activity = $this->deal->getLastActivityByLeadId($id);
        if (!empty($video->facebook_id)) {
            $result = $this->deal->getDealById($id, '
                vl.id,
                vl.revenue_share,
                vl.unique_key,
                DATE_FORMAT(vl.closing_date,"%a %m/%d/%Y") as closing_date,
                DATE_FORMAT(vl.created_at,"%a %m/%d/%Y") as created_at,
                DATE_FORMAT(lad.lead_rated_date,"%a %m/%d/%Y") as deal_date,
                DATE_FORMAT(lad.contract_sent_date,"%a %m/%d/%Y") as sent_date,
                vl.updated_at,
                vl.client_id,
                vl.first_name,
                vl.last_name,
                vl.email,
                vl.video_title,
                vl.video_title_2,
                vl.video_url,
                vl.message,
                vl.rating_point,
                vl.rating_comments,
                vl.status,
                dc.researcher_comment,
                dc.manager_comment,
                dc.local_url,
                vl.uploaded_edited_videos,
                vl.is_cn_updated,
                vl.trending,
                vl.is_ai_based,
                vl.information_pending,
                vl.published_portal,
                vl.published_yt,
                vl.published_fb,
                vl.reminder_sent,
                vl.confidence_level,
                vl.video_comment,
                vl.staff_id,vl.third_party_staff_id,
                vl.is_story_content,vl.stroy_s3_url,
                vl.s3_url_story_thumb,
                ad.name staff_name,
                vl.report_issue_type,
                vl.report_issue_desc,
                vl.priority,
                vl.deleted
            ');
        }

        if (!empty($video->youtube_id)) {
            $result = $this->deal->getDealById($id, '
                vl.id,
                vl.revenue_share,
                vl.unique_key,
                DATE_FORMAT(vl.closing_date,"%a %m/%d/%Y") as closing_date,
                DATE_FORMAT(vl.created_at,"%a %m/%d/%Y") as created_at,
                DATE_FORMAT(lad.lead_rated_date,"%a %m/%d/%Y") as deal_date,
                DATE_FORMAT(lad.contract_sent_date,"%a %m/%d/%Y") as sent_date,
                vl.updated_at,
                vl.client_id,
                vl.first_name,
                vl.last_name,
                vl.email,
                vl.video_title,
                vl.video_title_2,
                vl.video_url,
                vl.message,
                vl.rating_point,
                vl.rating_comments,
                dc.researcher_comment,
                dc.manager_comment,
                dc.local_url,
                vl.uploaded_edited_videos,
                vl.is_cn_updated,
                vl.trending,
                vl.is_ai_based,
                vl.status,
                vl.information_pending,
                vl.published_portal,
                vl.published_yt,
                vl.published_fb,
                vl.reminder_sent,
                vl.video_elephant,
                vl.raw_video,
                vl.description_updated,
                vl.exclusive_status,
                vl.facebook,
                vl.instagram,
                vl.confidence_level,
                vl.video_comment,
                    vl.staff_id,vl.third_party_staff_id,
                    vl.is_story_content,vl.stroy_s3_url,
                    vl.s3_url_story_thumb,
                    ad.name staff_name,
                vl.report_issue_type,
                vl.report_issue_desc,
                vl.priority,
                vl.deleted
            ');
        }

        /*$this->load->library('imap',$this->config->config['imap']);
        $f = $this->imap->get_folders();


        //$this->imap->select_folder('INBOX');
        $this->imap->search_subject($result->unique_key);
        $uids = $this->imap->search();
        $messages = $this->imap->get_messages($uids);

        $uidsAll = $this->imap->search('FROM "'.$email->email.'"');

        $messagesAllDetial = $this->imap->get_messages($uidsAll);

        $this->imap->select_folder('[Gmail]/Sent Mail');

        $uidsAllTo = $this->imap->search('TO "'.$email->email.'"');

        echo '<pre>';
        print_r($uidsAllTo);
        exit;

        if(is_array($messagesAllDetial)){
            $messagesAll = array_merge($messagesAllDetial,$this->imap->get_messages($uidsAllTo));
        }else{
            $messagesAll = $this->imap->get_messages($uidsAllTo);
        }

        //$messagesAll = $this->imap->get_messages($uidsAll);
        //$this->imap->select_folder('INBOX');*/

        //echo "checking isset ";
        //var_dump(isset($result->facebook));
        //exit;

        if (empty($result->facebook))
        {
            if (empty($result->facebook))
            {
                if (empty($video->facebook_id))
                {
                    $result->facebook = 'No';
                }
                else
                {
                    $result->facebook = 'Yes';
                }
            }
        }

        if (empty($result->youtube))
        {
            if (empty($result->youtube))
            {
                if (empty($video->youtube_id))
                {
                    $result->youtube = 'No';
                }
                else
                {
                    $result->youtube = 'Yes';
                }
            }
        }
        else
        {
            $result->youtube = 'No';
        }

        if (property_exists($result, 'instagram') && empty($result->instagram))
        {
            $result->instagram = 'No';
        }

        if (empty($result->exclusive_status))
        {
            $result->exclusive_status = 'No';
        }

        if (empty($result->raw_video))
        {
            $this->db->select('*');
            $this->db->from('raw_video');
            $this->db->where('lead_id', $result->id);
            $rv_query = $this->db->get();

            /* 			echo "<pre>";
                        print_r($result->num_rows());
                        echo "</pre>";
                        exit; */

            if ($rv_query->num_rows() > 0)
            {
                $result->raw_video = 'Yes';
            }
            else
            {
                $result->raw_video = 'No';
            }

        }

        if ((strpos($result->video_url, 'https//www.youtu') !== false) || (strpos($result->video_url, 'https://www.youtu') !== false) || (strpos($result->video_url, 'http://youtu') !== false) || (strpos($result->video_url, 'https://youtu') !== false))
        {
            $video_id = '';

            if (strpos($result->video_url, 'watch'))
            {
                //$yt_url_parts = explode('=', $result->video_url);
                parse_str( parse_url( $result->video_url, PHP_URL_QUERY ), $my_array_of_vars );
                $video_id = $my_array_of_vars['v'];
            }
            else if (strpos($result->video_url, '.be'))
            {
                $yt_url_parts = explode('/', $result->video_url);
                $video_id = $yt_url_parts[count($yt_url_parts)-1];
            }

            /*if (isset($yt_url_parts) && !empty($yt_url_parts)) {
                $video_id = $yt_url_parts[count($yt_url_parts)-1];
            }*/


            if (!empty($video_id)) {
                $api_handle = curl_init();

                curl_setopt($api_handle, CURLOPT_URL, 'https://youtube.googleapis.com/youtube/v3/videos?id=' . $video_id . '&key=' . 'AIzaSyByLeu5LWf8z7Mf9cNAek0j-AF9x3wppJM' . '&part=snippet');
                curl_setopt($api_handle, CURLOPT_POST, FALSE);
                curl_setopt($api_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($api_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($api_handle, CURLOPT_RETURNTRANSFER, TRUE);

                $api_result = curl_exec($api_handle);
                if($api_result){
                    $yt_video_info = json_decode($api_result);
                    if(isset($yt_video_info->items[0])){
                        $channelName = $yt_video_info->items[0]->snippet->channelTitle;
                        $channelLink = 'https://www.youtube.com/channel/'.$yt_video_info->items[0]->snippet->channelId;
                    }

                }
                if (!empty($result->description_updated)){
                    if ($api_result) {
                        $yt_video_info = json_decode($api_result);

                        if (isset($yt_video_info->items[0]) && !empty($yt_video_info->items[0])) {
                            $result->description_updated = $yt_video_info->items[0]->snippet->description;

                            if (stripos($result->description_updated, 'subscribe') !== false) {
                                $result->description_updated = 'Yes';
                            } else {
                                $result->description_updated = 'No';
                            }
                            $this->lead->update_description_updated_status($id, $result->description_updated);
                        } else {
                            $result->description_updated = 'No';
                        }
                    } else {
                        $result->description_updated = 'No';
                    }
                }
            }
        }
        if (ENVIROMENT != "development"){
            $correspondingEmails = $this->deal->getCorrespondingEmails($email->email, $result->unique_key);
            $allEmails = $this->deal->getAllEmails($email->email);
        }
        //echo $this->db->last_query();exit;
        $LeadRelaseLink = $this->deal->getLeadRelaseLink($result->unique_key);
        $secondsigner = $this->deal->getsecondsigner($result->unique_key);
        $appreancerelease = $this->deal->getappreancerelease($result->unique_key);
        $this->data['correspondingEmails'] = $correspondingEmails;
        $this->data['allEmails'] = $allEmails;
        $releaselinks = array();
        foreach ($LeadRelaseLink as $link){
            $releaselinks[$link->link_type] = $link;
        }

        $this->data['LeadRelaseLink'] = $releaselinks;
        $this->data['second_signer'] = $secondsigner;
        $this->data['appreance_release'] = $appreancerelease;
        $this->data['staffs'] =  $this->staff->getAllMembers('a.*','',0,0,'a.name ASC');


        //echo 1;exit;
        if (!$result) {

            redirect($_SERVER['HTTP_REFERER']);

        } else {

            $staff_id=$result->staff_id;
            $third_party_staff_id = $result->third_party_staff_id;
            if(!empty($staff_id)){
                $party_query = $this->lead->getPartyById('admin',$staff_id);
                $party_result =$party_query->result();
                if(count($party_result)){
                    $party_name = $party_result[0]->name;
                }else{
                    $party_name = "WooGlobe";
                }

            } elseif(!empty($third_party_staff_id)){
                $party_query = $this->lead->getPartyById('third_party_staff',$third_party_staff_id);
                $party_result =$party_query->result();
                if(count($party_result)){
                    $party_name = $party_result[0]->name;
                }else{
                    $party_name = "WooGlobe";
                }
            }else{

                $party_name = 'WooGlobe';
            }

            $query = $this->db->get('countries');
            $countries = $query->result();
            $video_query=$this->db->query('SELECT *  FROM `videos` WHERE `lead_id` = "'.$id.'"');
            $video_result=$video_query->result();
            if(isset($video_result[0])){
                $this->data['question_video_taken']=$video_result[0]->question_video_taken;
                $this->data['question_when_video_taken']=$video_result[0]->question_when_video_taken;
            }

            $unique_key = $this->lead->getUniqueKey($id);
            $unique_key = $unique_key->unique_key;
            $signimgpath = root_url().'uploads/'.$unique_key.'/documents/'.$unique_key.'.png';
            if(!file_exists('./../uploads/'.$unique_key.'/documents/'.$unique_key.'.png')){
                $manualRelease = false;
            }
            $slug = strtolower($result->video_title);
            $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
            $slug = trim($slug, '-');
            $this->data['slug'] = $slug;
            $this->data['signature'] = $signimgpath;
            $this->data['lead_id'] = $id;
            $this->data['researcher_comment'] = $result->researcher_comment;
            $this->data['manager_comment'] = $result->manager_comment;
            $this->data['priority'] = $result->priority;
            $this->data['rawVideos'] = $rawVideos;
            $this->data['dealData'] = $result;
            $this->data['userData'] = $user;
            $this->data['countriesData'] = $countries;
            $this->data['party_name'] = $party_name;
            $this->data['mrss_brand_feeds'] = $this->brand->getMrssBrandFeedsByLeadId($id)->result();

            /* MRSS categories : S */
            
            $m_queue = $this->mrss_queue->getQueueByLeadId($id);
            $this->data["mrss_queue"] = $m_queue;
            // print_r($m_queue->result_array());
            // exit();
            $this->data['mrss_default_values'] = [];
            $this->data['allow_mrss'] = 'no';
            $this->data['mrss_partners'] = $this->brand->getMrssPartners();
            $this->data['mrss_brands'] = $this->brand->getMrssBrands(true);
            // Loop
            $mrss_brands = $this->data['mrss_brands'];
            $brand_users = [];

            foreach ($mrss_brands->result() as $brand) {
                $brand_id = $brand->id;
               
                $brand_users[$brand_id] = $this->user->getMrssBrandsUsersFromBrands($brand_id)->result();
            }
          
            $this->data['mrss_brands_partners'] = $brand_users;
            $this->data['general_categories'] = $this->mrss->getGeneralCategories('id, title');
            if (isset($video->id)) {
                $this->data['mrss_feed_data'] = $this->mrss->getFeedDataByVideoId($video->id);
                $this->data['non_exclusive_partner_data']=$this->mrss->nonExclusivePartnerdataByVideoId($video->id);

                $partnership_type = '';
                $partners_info = $this->mrss->getExclusivePartnerByVideoId($video->id); // get single partners for video

                if (!empty($partners_info)) { // if there is single partner then type = single partnership (1) for default selection in view
                    $partnership_type = '1';
                }
                else {
                    // if no single partnership exists in above case then check for multiple partnership
                    $partners_info = $this->mrss->getNonExclusivePartnersByVideoId($video->id);
                    if (!empty($partners_info)) {
                        $partnership_type = '2'; // multiple partnership
                    }
                }

                $this->data['video_selected_categories'] = $this->mrss->getVideoSelectedCategoriesByVideoId($video->id, 'mrss_feeds.id, title');
                /*echo '<pre>';
                print_r($this->data['video_selected_categories']);
                exit;*/
                $this->data['partners_info'] = $partners_info;
            }


            if (!empty($partners_info) || !empty($this->data['video_selected_categories'])) {
                $this->data['allow_mrss'] = 'yes';
            }
            else {
                $partnership_type = '';
            }

            $this->data['partnership_type'] = $partnership_type; // multiple ;

            if ($partnership_type == 1 || $partnership_type == 2) {
                $this->data['dealData']->exclusive_status = 'Yes';
            }
            else {
                $this->data['dealData']->exclusive_status = 'No';
            }
            // echo "DEAL DETAIL 5 =========================="; die();

            /* MRSS categories : E */
            $currencies  = $this->lead->getCurencies();
            $this->data['videoData'] = $video;
            $this->data['activity'] = $activity;
            $this->data['currencies'] = $currencies;
            if (ENVIROMENT != "development") {
                $this->data['emailHistory'] = $this->deal->getDealEmailNotifictionHIstory($id);
            }
            if(isset($video->id)){
                $this->data['editedVideo'] = $this->deal->getEditedVideoById($video->id);
            }

            $userData = $this->deal->getUserByLeadId($id);
            $currency_id = 0;

            // Code to restrict currency to user's first earning's currrency

            // if(!empty($userData) && isset($userData->currency_id) && !empty($userData->currency_id)){
            //     $currency_id = $userData->currency_id;
            // }
            
            $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';
            $country_name = "";

            $data_log_query=$this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="'.$id.'"');
            if($data_log_query->num_rows() > 0){
                $logs_query=$data_log_query->row();



                // Initialize CURL:
                $ch = curl_init('http://api.ipstack.com/'.$logs_query->user_ip_address.'?access_key='.$access_key.'');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Store the data:
                $json = curl_exec($ch);
                curl_close($ch);

                // Decode JSON response:
                $api_result = json_decode($json, true);

                //Generate Pdf
                $country_name_va='';
                if(!empty($api_result) && isset($api_result['country_name'])){
                    $country_name = $api_result['country_name'];
                }
            }

            $nextPayment = $this->deal->getNextPayment($result->client_id);
            // $next = $nextPayment->row();
            // $this->data['next_payment'] = $next->next_payment;
            $this->data['next_payment'] = $nextPayment;

            $paid = $this->deal->paid($result->client_id);
            // $paid = $paid->row();
            // $this->data['paid'] = $paid->paid;
            $this->data['paid'] = $paid;

            // $this->data['currency'] = getUserCurrencySymbolById($result->client_id);
            $this->data['currency'] = getDefaultCurrencySymbol();
            $this->data['lifetime_paid_currency'] = getCurrencyByCode('USD')['symbol'];


            $this->data['earning_type'] = $this->earning_type->getAllEarningTypesActive('et.id,et.earning_type','',0,0,'et.earning_type ASC');
            $this->data['country_name'] = $country_name;
            $this->data['currency_id'] = $currency_id;
            $this->data['channelName'] = $channelName;
            $this->data['channelLink'] = $channelLink;
            $this->data['manualRelease'] = $manualRelease;
            $this->data['sources'] = $this->source->getAllSourcesActive('ss.id,ss.sources','',0,0,'ss.sources ASC');
            $this->data['partners'] = $this->user->getAllUsersActive(2,'u.id,u.full_name,u.email','',0,0,'u.full_name ASC');
            $this->data['commonJs'] = array_merge($this->data['commonJs'], array('assets/js/vid_earnings17.js'));
            $story_categories = $this->db->query(' SELECT * FROM mrss_feeds WHERE type = 2 AND status = 1 AND deleted = 0');
            $this->data['storyCategories'] = $story_categories;

            // TODO: create a function and call everywhere duration being calculated

            $sum_duration = 0;
            if($rawVideos != NULL){
                $res = $this->dropbox_model->update_durations($rawVideos->result_array()[0]["lead_id"]);
                $res = json_decode($res);
                $sum_duration = $res->duration;
            }
            // foreach($rawVideos->result_array() as $row){
            //     if($row['video_duration'] == 0){
            //         $res = $this->dropbox_model->update_durations($row['video_id']);
            //         $res = json_decode($res);
            //         $sum_duration += $res->duration;  
            //     }
            //     else{
            //         $sum_duration += $row['video_duration'];
            //     }
    
            // }
            $this->data['sum_duration'] = $sum_duration;
            $this->data['content'] = $this->load->view('video_deals/deal_detail', $this->data, true);
            $this->load->view('common_files/template', $this->data);

        }


    }


    public function email_detail($id)
    {
        $result = $this->deal->getEmailById($id);
        $this->data['email'] = $result;
        $this->data['content'] = $this->load->view('email_detail', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }

    public function publish_portal($id)
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_distribute');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response['code'] = 200;
        $response['message'] = 'Video Published on Portal Successfully!';
        $response['error'] = '';

        $video = $this->deal->getPortalVideo($id);

        if ($video) {
            $dbData['url'] = $video->portal_url;
            $dbData['thumbnail'] = $video->portal_thumb;

            $update = $this->deal->updatePortalUrl($dbData, $id);
            $this->deal->dealStatusChangeFromDistributeToWon($id);
            if ($update) {

                $get = $this->video->getVideoById($id, 'lead_id');
                if ($get) {
                    $status = $this->deal->updateStatus($get->lead_id);
                }
                echo json_encode($response);
                exit;
            }
        } else {
            $response['code'] = 201;
            $response['message'] = 'Video not published!';
            echo json_encode($response);
            exit;

        }

    }

    public function delete_youtube_video(){
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_distribute');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }

        $response['code'] = 200;
        $response['message'] = 'Video Deleted from Youtube Successfully!';
        $response['error'] = '';
        $request = $this->security->xss_clean($this->input->post());
        
        $this->db->where('video_id', $request["video_id"]);
        // $this->db->where('publish_type', 'YouTube');
        $res = $this->db->get("video_publishing_scheduling")->result();
        $channel_id = "";
        if(count($res) > 0){
            $channel_id = $res[0]->youtube_channel;
        }

        $this->db->where('id', $request["video_id"]);
        $res = $this->db->get("videos")->result();
        $youtube_id = "";
        if(count($res) > 0){
            $youtube_id = $res[0]->youtube_id;
        }
        // if($youtube_id == ""){
        //     $response['code'] = 204;
        //     $response['message'] = 'Video not found on Youtube';
        //     $response['error'] = '';
        //     echo json_encode($response);
        //     exit;
        // }

        $result = $this->youtube->deleteVideo($request["video_id"], $youtube_id, $channel_id);
        // if ($result['error'] == false)
        {
            $this->db->where('id', $request["video_id"]);
            $dbData['youtube_id'] = '';
            $this->db->update('videos', $dbData);

            $this->db->where('video_id', $request["video_id"]);
            $this->db->where('publish_type', 'YouTube');
            $this->db->delete('video_publishing_scheduling');

        } 
        // else{
        //     $response['code'] = 206;
        //     $response['message'] = $result;
        //     echo json_encode($response);
        //     exit;
        // }
        echo json_encode($response);

    }

    public function publish_facebook()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_distribute');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response['code'] = 200;
        $response['message'] = 'Video Published on Facebook Successfully!';
        $response['error'] = '';
        $request = $this->security->xss_clean($this->input->post());
        $video_id = $request['video_id'];
        $dbData['publish_type'] = 'FaceBook';
        if (isset($request['publish_now_facebook']) and $request['publish_now_facebook'] == '1') {
            $dbData['publish_now'] = 1;
            $dbData['published'] = 1;

        } else {
            $dbData['publish_now'] = 0;
            $dbData['publish_datetime'] = $request['facebook_publish_date'] . ' ' . $request['facebook_publish_time'] . ':00';
        }
        //$page_id = $request['page_id'];
        //$publish_time = strtotime($request['facebook_publish_time']);
        $dbData['video_title'] = $request['facebook_publish_title'];
        $dbData['video_description'] = $request['facebook_publish_description'];
        $dbData['video_tags'] = $request['facebook_publish_tags'];
        $publishData = $this->deal->getPublishData($request['video_id'], 'FaceBook');
        $video = $this->video->getVideoById($request['video_id']);
        if ($publishData) {
            $this->db->where('video_id', $request['video_id']);
            $this->db->where('publish_type', 'FaceBook');
            $this->db->update('video_publishing_scheduling', $dbData);
        } else {
            $dbData['video_id'] = $request['video_id'];
            $this->db->insert('video_publishing_scheduling', $dbData);
        }

        if ($request['publish_now_facebook'] == 0) {
            $publishData = $this->deal->getPublishData($request['video_id'], 'FaceBook');
            if ($publishData) {
                $video_data = $this->deal->getPortalVideo($request['video_id']);
                $tim_tags = trim($publishData->video_tags);
                $WGA = $this->db->select('unique_key')->from('video_leads')->join('videos', 'videos.lead_id = video_leads.id')->where('videos.id', $request['video_id'])->get()->row();
                $tags = str_replace(" ", ",", $tim_tags);
                $unique_key_array='';
                if (isset($WGA->unique_key) && !empty($WGA->unique_key)) {
                    $unique_key_array = array($WGA->unique_key);
                }
                $privacy = array(
                    'value' => 'EVERYONE' //private
                );
                $data = array(
                    'title' => $publishData->video_title,
                    'name' => $publishData->video_title,
                    'message' => $publishData->video_description,
                    'description' => $publishData->video_description,
                    'custom_labels' => $unique_key_array,
                    'content_tags' => explode(',', $tags),
                    'privacy'=> $privacy,
                    //'custom_labels'=>explode(',',$publishData->video_tags),
                    //'tags'=>$publishData->video_tags,
                    'is_published' => true,
                    /*'scheduled_publish_time'=>strtotime($publishData->publish_datetime),*/
                );
                $base_path = '/var/www/html/';
                $videoPath = $base_path . $video_data->fb_url;
                $thumbPath = $base_path . $video_data->fb_thumb;

                //$videoPath = $video_data->fb_url;
                $result = $this->fb->uplaodVideo($data, $videoPath, $thumbPath);
                if ($result) {

                    $dbData1['facebook_id'] = $result['id'];
                    $this->db->where('id', $request['video_id']);
                    $this->db->update('videos', $dbData1);
                    $dbData2['published'] = 1;
                    $this->db->where('video_id', $request['video_id']);
                    $this->db->where('publish_type', 'FaceBook');
                    $this->db->update('video_publishing_scheduling', $dbData2);
                    $dbData3['facebook_repub'] = 0;
                    $this->db->where('id', $request['video_id']);
                    $this->db->update('videos', $dbData3);
                    if ($video_data) {
                        $status = $this->deal->updateFacebookStatus($video->lead_id);
                        $this->deal->dealStatusChangeFromDistributeToWon($video->lead_id);
                        @unlink($videoPath); // delete youtube video from server after upload
                        if (isset($WGA->unique_key) && !empty($WGA->unique_key)) {
                            $WGA_dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$WGA->unique_key;

                            $yt_thumbnails_path = $WGA_dir.'/edited_videos/facebook/thumbnail';

                            if (is_dir($yt_thumbnails_path) ) {
                                $thumbnails_list = scandir($yt_thumbnails_path);

                                foreach ($thumbnails_list as $thumbnail) {
                                    @unlink($yt_thumbnails_path.'/'.$thumbnail); // delete related thumbnails from server after upload
                                }
                            }
                        }

                    }

                }
            } else {
                $response['code'] = 201;
                $response['message'] = 'Video not published!';
                echo json_encode($response);
                exit;

            }


        }
        echo json_encode($response);
        exit;
    }

     public function revenue_update()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_revenue_update');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Revenue Share upated Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('revenue_share', 'Revenue Shasre', 'trim|required');
        $this->validation->set_rules('lead_id', 'Lead Id', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('revenue_share', 'lead_id');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {

            $dbData = $this->security->xss_clean($this->input->post());

            if (isset($dbData['lead_id']) && !empty($dbData['lead_id'])) {
                $lead_id = $dbData['lead_id'];
            }
            $revenue = $dbData['revenue_share'];
            $sent = $dbData['sent'];
            unset($dbData['lead_id']);
            unset($dbData['sent']);


            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');

            $this->db->where('id', $lead_id);
            $this->db->update('video_leads', $dbData);

            $leadData = $this->email->getLeadById($lead_id, 'vl.*');
            action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Revenue updated');
            $leadVideoUrl=$leadData->video_url;
            $emailData = $this->lead->getLeadMailStatusByLeadId($lead_id);

            if ($sent == 1 && $leadData->rating_point >= 5) {
               /* $template = $this->app->getEmailTemplateByCode('revenue_update_email');

                    if ($template) {
                         $subject = $template->subject.'-'.$leadData->unique_key;
                         $emailDbData['video_lead_id'] = $lead_id;
                        $emailDbData['email_sent'] = 1;
                        $emailDbData['mail_sent_time'] = date('Y-m-d H:i:s');
                        $this->db->insert('intro_email', $emailDbData);
                        $leadVideoUrl=$leadData->video_url;
                        $ids = array(
                            'video_leads' => $lead_id
                        );

                        $url = $this->data['root'].'video-contract/'.$leadData->slug;
                        //$url = '<p>If you are interested to make a contract with us then <a href="'.$url.'">click here</a></p>';
                        $message = dynStr($template->message, $ids);
                        $message = str_replace('@VIDEO_LEADS.URL',"<a href='".$leadVideoUrl."'>$leadVideoUrl</a>",$message);

                        $message = str_replace('@LINK',$url,$message);
                        $message = str_replace('@REVENUE',$dbData['revenue_share'],$message);
                        $leftover = 100 - $dbData['revenue_share'];
                        $message = str_replace('@LEFT',$leftover,$message);
                        $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
                        $notification['send_datime'] = date('Y-m-d H:i:s');
                        $notification['lead_id'] = $lead_id;
                        $notification['email_template_id'] = $template->id;
                        $notification['email_title'] = $template->title;
                        $notification['ids'] = json_encode($ids);
                        //$this->db->insert('email_notification_history',$notification);
                    }
                action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'New contract sent');*/


                    $dbData['revenue_share'] = $revenue;
                    if(!empty($leadData->staff_id) && isset($leadData->staff_id)|| !empty($leadData->third_party_staff_id) && isset($leadData->third_party_staff_id)){
                        $dbData['status'] = 3;
                        if(!empty($leadData->third_party_staff_id) && isset($leadData->third_party_staff_id)){
                            $thirdpartytitle=$this->lead->getThirdpartyById($leadData->third_party_staff_id);
                            $template = $this->app->getEmailTemplateByCode('welcome_third_party_intro_email');

                            if ($template) {

                                $subject = $thirdpartytitle->name;

                                $emailDbData['video_lead_id'] = $lead_id;
                                $emailDbData['email_sent'] = 1;
                                $emailDbData['mail_sent_time'] = date('Y-m-d H:i:s');
                                $this->db->insert('intro_email', $emailDbData);
                                $ids = array(
                                    'video_leads' => $lead_id
                                );

                                $url = $this->data['root'].'video-contract/'.$leadData->slug;
                                //$url = '<p>If you are interested to make a contract with us then <a href="'.$url.'">click here</a></p>';
                                $message = dynStr($template->message, $ids);
                                $message = str_replace('@VIDEO_LEADS.URL',"<a href='".$leadVideoUrl."'>$leadVideoUrl</a>",$message);

                                $message = str_replace('@LINK',$url,$message);
                                $message = str_replace('@REVENUE',$dbData['revenue_share'],$message);
                                $leftover = 100 - $dbData['revenue_share'];
                                $message = str_replace('@LEFT',$leftover,$message);

                                //$this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                                $notification = array();

                                $notification['send_datime'] = date('Y-m-d H:i:s');
                                $notification['lead_id'] = $lead_id;
                                $notification['email_template_id'] = $template->id;
                                $notification['email_title'] = $template->title;
                                $notification['ids'] = json_encode($ids);
                                //$this->db->insert('email_notification_history',$notification);
                                action_add($lead_id,0,0,$this->sess->userdata('adminId'),1,'Lead converted to deal');
                                $cur_time = date('Y-m-d H:i:s');
                                $date = array();
                                $date['lead_id'] = $lead_id;
                                $action_date = $this->db->insert('lead_action_dates',$date);
                                $insert = $this->lead->action_dates($cur_time,$lead_id);
                                //Extract log data
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
                                    $user['phone']=$user_result[0]->mobile;
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
                                //$this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                                //$result_pdf=$this->video_signed_pdf($user['full_name'],$user['email'],$user['phone'],$country_name_va,$user['state'],$user['city'],$user['address'],$user['address'],date('Y-m-d',strtotime($data_log['contract_signed_datetime'])),$date,$dbData['revenue_share'],$leadData->unique_key,$data,$data_log,$api_result);

                            }
                        }else{
                            /*  $template = $this->app->getEmailTemplateByCode('welcome_intro_email');

                              if ($template) {

                                  $unique_key = $this->lead->getUniqueKey($lead_id);

                                  if($unique_key){
                                      $subject = $template->subject.'-'.$unique_key->unique_key;
                                  }
                                  else{
                                      $subject = $template->subject;
                                  }
                                  $emailDbData['video_lead_id'] = $lead_id;
                                  $emailDbData['email_sent'] = 1;
                                  $emailDbData['mail_sent_time'] = date('Y-m-d H:i:s');
                                  $this->db->insert('intro_email', $emailDbData);
                                  $ids = array(
                                      'video_leads' => $lead_id
                                  );

                                  $url = $this->data['root'].'video-contract/'.$leadData->slug;
                                  //$url = '<p>If you are interested to make a contract with us then <a href="'.$url.'">click here</a></p>';
                                  $message = dynStr($template->message, $ids);
                                  $message = str_replace('@VIDEO_LEADS.URL',"<a href='".$leadVideoUrl."'>$leadVideoUrl</a>",$message);

                                  $message = str_replace('@LINK',$url,$message);
                                  $message = str_replace('@REVENUE',$dbData['revenue_share'],$message);
                                  $leftover = 100 - $dbData['revenue_share'];
                                  $message = str_replace('@LEFT',$leftover,$message);

                                  //$this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                                  $notification = array();

                                  $notification['send_datime'] = date('Y-m-d H:i:s');
                                  $notification['lead_id'] = $lead_id;
                                  $notification['email_template_id'] = $template->id;
                                  $notification['email_title'] = $template->title;
                                  $notification['ids'] = json_encode($ids);
                                  $this->db->insert('email_notification_history',$notification);
                                  action_add($lead_id,0,0,$this->sess->urdata('adminId'),1,'Lead converted to deal');*/
                            $cur_time = date('Y-m-d H:i:s');
                            $date = array();
                            $date['lead_id'] = $lead_id;
                            $action_date = $this->db->insert('lead_action_dates',$date);
                            $insert = $this->lead->action_dates($cur_time,$lead_id);
                            //Extract log data
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
                                $user['phone']=$user_result[0]->mobile;
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
                            //$this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
                            //$result_pdf=$this->video_signed_pdf($user['full_name'],$user['email'],$user['phone'],$country_name_va,$user['state'],$user['city'],$user['address'],$user['address'],date('Y-m-d',strtotime($data_log['contract_signed_datetime'])),$dbData['revenue_share'],$leadData->unique_key,$data,$data_log,$api_result);

                        }
                    }else{

                        $dbData['status'] = 10;
                        /*if ($emailData->num_rows() == 0) {
                            print_r(3);
                            exit;*/

                            $template = $this->app->getEmailTemplateByCode('welcome_intro_email');

                            if ($template) {

                                $unique_key = $this->lead->getUniqueKey($lead_id);

                                if($unique_key){
                                    $subject = $template->subject.'-'.$unique_key->unique_key;
                                }
                                else{
                                    $subject = $template->subject;
                                }
                                $emailDbData['video_lead_id'] = $lead_id;
                                $emailDbData['email_sent'] = 1;
                                $emailDbData['mail_sent_time'] = date('Y-m-d H:i:s');
                                $this->db->insert('intro_email', $emailDbData);
                                $ids = array(
                                    'video_leads' => $lead_id
                                );

                                $url = $this->data['root'].'video-contract/'.$leadData->slug;
                                //$url = '<p>If you are interested to make a contract with us then <a href="'.$url.'">click here</a></p>';
                                $message = dynStr($template->message, $ids);
                                $message = str_replace('@VIDEO_LEADS.URL',"<a href='".$leadVideoUrl."'>$leadVideoUrl</a>",$message);

                                $message = str_replace('@LINK',$url,$message);
                                $message = str_replace('@REVENUE',$dbData['revenue_share'],$message);
                                $leftover = 100 - $dbData['revenue_share'];
                                $message = str_replace('@LEFT',$leftover,$message);

                                $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                                $notification = array();

                                $notification['send_datime'] = date('Y-m-d H:i:s');
                                $notification['lead_id'] = $lead_id;
                                $notification['email_template_id'] = $template->id;
                                $notification['email_title'] = $template->title;
                                $notification['ids'] = json_encode($ids);
                                $this->db->insert('email_notification_history',$notification);
                                action_add($lead_id,0,0,$this->sess->userdata('adminId'),1,'Lead converted to deal');
                                $cur_time = date('Y-m-d H:i:s');
                                $insert = $this->lead->action_dates($cur_time,$lead_id);;

                            }else{
                                action_add($lead_id,0,0,$this->sess->userdata('adminId'),1,'Lead not converted to deal');
                            }
                       /* }else{
                            action_add($lead_id,0,0,$this->sess->userdata('adminId'),1,'Email Data not found');
                        }*/
                    }
                }


            $this->db->where('id', $lead_id);
            $this->db->update('video_leads', $dbData);

        }
        echo json_encode($response);
        exit;
    }

    public function revenue_update2()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_revenue_update');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Revenue Share upated Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('revenue_share', 'Revenue Shasre', 'trim|required');
        $this->validation->set_rules('lead_id', 'Lead Id', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('revenue_share', 'lead_id');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {

            $dbData = $this->security->xss_clean($this->input->post());

            if (isset($dbData['lead_id']) && !empty($dbData['lead_id'])) {
                $lead_id = $dbData['lead_id'];
            }
            $revenue = $dbData['revenue_share'];
            $sent = $dbData['sent'];
            unset($dbData['lead_id']);
            unset($dbData['sent']);


            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');

            $this->db->where('id', $lead_id);
            $this->db->update('video_leads', $dbData);



        }
        echo json_encode($response);
        exit;
    }

    public function get_video_publlish_data()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_distribute');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Record found Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $request = $this->security->xss_clean($this->input->post());

        $publishData = $this->deal->getPublishData($request['video_id'], $request['type']);
        $video_key = $this->deal->getUniquekeyByVideoId($request['video_id']);
        $data = array();
        $data['categories'] = $this->youtube->getCategories();
        $data['channels'] =  $this->youtube->getChannels();
        try{
            $this->youtube->getCategories();
        }catch(Exception $ex){
            var_dump($ex);
        }
        if ($publishData) {
            $data['publish_title'] = $publishData->video_title;
            $data['publish_description'] = $publishData->video_description;
            $data['publish_now'] = $publishData->publish_now;
            $data['yt_channel'] = $publishData->youtube_channel;
            $data['publish_tags'] = $publishData->video_tags;
            $data['channel'] = $publishData->youtube_channel;
            $data['category'] = $publishData->youtube_category;
            $data['page_id'] = $publishData->facebook_page_id;
            $datetime = explode(' ', $publishData->publish_datetime);
            $data['publish_date'] = $datetime[0];
            $data['publish_time'] = substr($datetime[1], 0, 5);
            $data['youtube_publish_status'] = $publishData->youtube_publish_status;
            $data['unique_key'] = $video_key->unique_key;

        } else {

            $video = $this->video->getVideoById($request['video_id']);
            if ($video) {
                $data['publish_title'] = $video->title;
                $data['publish_description'] = $video->description;
                $data['publish_tags'] = $video->tags;
                $data['unique_key'] = $video_key->unique_key;

            }

        }

        $response['data'] = $data;
        echo json_encode($response);
        exit;
    }


    public function not_interested()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'not_interested');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Deal moved to not interested Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('title', 'Ttitle', 'trim|required');
        $this->validation->set_rules('id', 'Lead Id', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('id', 'title');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {

            $dbData = $this->security->xss_clean($this->input->post());

            if (isset($dbData['id']) && !empty($dbData['id'])) {
                $lead_id = $dbData['id'];
            }
            $title = $dbData['title'];
            $restore = '';
            if (isset($dbData['restore']) && !empty($dbData['restore'])) {
                $restore = $dbData['restore'];
                unset($dbData['restore']);
            }
            unset($dbData['lead_id']);
            unset($dbData['title']);

            $leadData = $this->email->getLeadById($dbData['id'], 'vl.*');
            $leadVideoUrl = $leadData->video_url;
          /*  if ($leadData) {
                if (!empty($leadData->sr_uuid)) {
                    include('./vendor/signrequest/src/AtaneNL/SignRequest/Client.php');
                    include('./vendor/signrequest/src/AtaneNL/SignRequest/CreateDocumentResponse.php');
                    $client = new \AtaneNL\SignRequest\Client($this->config->config['sr_token']);
                    $cdr = $client->cancelSignRequest($leadData->sr_uuid);
                }
            }*/
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $dbData['status'] = 11;
            if ($restore) {
                $dbData['status'] = 1;
                $rating_point = $leadData->rating_point;
                if ($rating_point > 0) {
                    $dbData['status'] = 10;
                }
                $template = $this->app->getEmailTemplateByCode('welcome_intro_email');

                if ($template) {

                    $unique_key = $this->lead->getUniqueKey($lead_id);

                    if ($unique_key) {
                        $subject = $template->subject . '-' . $unique_key->unique_key;
                    } else {
                        $subject = $template->subject;
                    }
                    $emailDbData['video_lead_id'] = $lead_id;
                    $emailDbData['email_sent'] = 1;
                    $emailDbData['mail_sent_time'] = date('Y-m-d H:i:s');
                    $this->db->insert('intro_email', $emailDbData);
                    $ids = array(
                        'video_leads' => $lead_id
                    );

                    $url = $this->data['root'] . 'video-contract/' . $leadData->slug;
                    //$url = '<p>If you are interested to make a contract with us then <a href="'.$url.'">click here</a></p>';
                    $message = dynStr($template->message, $ids);
                    $message = str_replace('@VIDEO_LEADS.URL', "<a href='" . $leadVideoUrl . "'>$leadVideoUrl</a>", $message);

                    $message = str_replace('@LINK', $url, $message);


                    $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

                    $notification = array();

                    $notification['send_datime'] = date('Y-m-d H:i:s');
                    $notification['lead_id'] = $lead_id;
                    $notification['email_template_id'] = $template->id;
                    $notification['email_title'] = $template->title;
                    $notification['ids'] = json_encode($ids);
                   // $this->db->insert('email_notification_history', $notification);
                    action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Not Interested to deal');
                    $cur_time = date('Y-m-d H:i:s');
                    $insert = $this->lead->action_dates($cur_time, $lead_id);;
                    action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Removed from Not Interested');
                    $response['message'] = 'Deal removed from not interested successfully!';
                } else {
                    action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Move to Not Interested');
                }
            }else{
                action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Move to Not Interested');
            }
            $dbData['not_interested'] = $title;

            $this->db->where('id', $lead_id);
            $this->db->update('video_leads', $dbData);

        }

        echo json_encode($response);
        exit;

    }

    public function getEmailInformation()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Email Record Found Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('id', 'Email Id', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('id');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {

            $dbData = $this->security->xss_clean($this->input->post());

            if (isset($dbData['id']) && !empty($dbData['id'])) {
                $email_id = $dbData['id'];
            }
            unset($dbData['id']);

            $item = $this->deal->getEmailData($email_id);


            if ($item) {
                /* print "<pre>";
                 print_r($item);
                 print "</pre>";*/
                $return = array();
                //print_r($return);
                foreach ($item as $data) {
                    $return['from_email'] = $data->from_email;
                    $return['to_email'] = $data->to_email;
                    $return['date_email'] = date('m/d/Y h:i A', strtotime($data->converted_date_time));
                    $return['subject_email'] = $data->subject;
                    $return['content_email'] = $data->message;
                    $return['reply_link'] = $data->uid;
                    $return['message_id'] = $data->message_id;
                    $response['data'][] = array(
                        'from_email' => $return['from_email'],
                        'to_email' => $return['to_email'],
                        'date_email' => $return['date_email'],
                        'subject_email' => $return['subject_email'],
                        'content_email' => $return['content_email'],
                        'reply_link' => $return['reply_link'],
                        'message_id' => $return['message_id'],
                    );
                }
            } else {
                $response['data'] = array();
            }

            //print_r($response['data']);
        }
        echo json_encode($response);
        exit;
    }

    public function download_files($video_id)
    {
        //$video_id = 20;
        auth();
        role_permitted(false, 'video_deals', 'upload_edited_videos');
        $result = $this->video->getVideoById($video_id);
        $videos = $this->deal->getRawVideosByVideoId($video_id);
//        echo "<pre>";
//        print_r($videos->result_array());
//        exit;
        action_add($result->lead_id, $result->id, 0, $this->sess->userdata('adminId'), 1, 'Raw files downloaded');
        if ($videos->num_rows() > 0) {
            $this->load->library('zip');
            foreach ($videos->result() as $video) {
                $path = './../' . $video->url;
                //echo 'Path : '.$path."<br>";exit;
                $this->zip->read_file($path);
            }
            //echo "read file : ".$path."<br>";
            //exit;
            $this->zip->download($video_id . '.zip');


        } else {
            $this->sess->set_flashdata('err', 'Invalid link');
            redirect('dashboard');
        }
    }

    public function decide_download_category ()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $video_id = $this->input->post('vid');
        //ini_set("memory_limit", "-1");
        //set_time_limit(0);

        $email_download = false;

        role_permitted(false, 'video_deals', 'upload_edited_videos');

        $result = $this->video->getVideoById($video_id);
        $videos = $this->deal->getRawVideosByVideoId($video_id);

        action_add($result->lead_id, $result->id, 0, $this->sess->userdata('adminId'), 1, 'Raw files downloaded');

//        echo "<pre>";
//        var_dump($videos->num_rows());
//        echo "</pre>";
//        exit;

        if ($videos->num_rows() > 0) {

            $file_list = [];

            $email_download = false;

            foreach ($videos->result() as $video) {
                $path = './../' . $video->url;
                $path_segments = explode('/', $video->url);

                $curr_file_size = filesize($path);

                if ($curr_file_size > 1048576 * 512) {
                    $email_download = true;
                    break;
                }
            }


            if ($email_download) {
                foreach ($videos->result() as $video) {
                    $path = './../' . $video->url;
                    $path_segments = explode('/', $video->url);

                    $dir_path = implode('/', array_slice($path_segments, 0, 2));

                    $file_list[] = $path;

                    $cmd = 'zip '.$dir_path.'/'.$video_id.'.zip '.implode(' ', $file_list).' && php '.base_url().'Pending_downloads/';

                    //echo $cmd = 'zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/testfile.zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/wga197205_1577960559.mp4';
                    //$cmd = 'zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/testfile2.zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/wga197205_1577960559.mp4';

                    $admin_id = $this->sess->userdata('adminId');

                    //$cmd = 'zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/testfile2.zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/wga325108_1574083937.mp4 && curl --url '.base_url().'Pending_downloads/send_pending_download_links/'.$video_id.'/'.$admin_id.' --insecure';
                    //$cmd = 'zip /var/www/html/wooglobe/'.$dir_path.'/raw_videos/'.$video_id.'.zip '.implode(' ', $file_list).'&& curl --url '.base_url().'Pending_downloads/send_pending_download_links/'.$video_id.'/'.$admin_id.' --insecure >/dev/null 2> /dev/null &';

                }

                if (!empty($file_list)) {
                    //$cmd = 'zip /var/www/html/wooglobe/'.$dir_path.'/raw_videos/'.$video_id.'.zip '.implode(' ', $file_list).'&& curl --url '.base_url().'Pending_downloads/send_pending_download_links/'.$video_id.'/'.$admin_id.' --insecure';
                    $cmd = 'zip /var/www/html/'.$dir_path.'/raw_videos/'.$video_id.'.zip '.implode(' ', $file_list).'&& curl --url '.base_url().'send-zip-email/'.$video_id.'/'.$admin_id.' --insecure';


                    echo json_encode(['type' => 'email', 'cmd' => $cmd]);
                    exit;
                }
            }
            else {
                echo json_encode(['type' => 'direct']);
                exit;
            }
            //$this->zip->download($video_id . '.zip');

        } else {
            echo json_encode(['type' => 'none']);
            exit;
        }

    }

    public function ajax_create_zip_request ()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        if ($this->input->post('cmd')) {

            session_write_close();
            $cmd = $this->input->post('cmd');

            /* 			$cmd = $this->input->post('cmd');
                        exec($cmd.' > /dev/null 2> /dev/null &');
                        json_encode(['status' => 'success']);
                        exit;	 */



            $gen_file = curl_init();


            curl_setopt($gen_file, CURLOPT_URL,base_url().'Pending_downloads/run_genfile_cmd');
            curl_setopt($gen_file, CURLOPT_POST, 1);
            //curl_setopt($gen_file, CURLOPT_POSTFIELDS, "cmd=".$cmd);
            curl_setopt($gen_file, CURLOPT_POSTFIELDS, http_build_query(array('cmd' => $cmd)));
            curl_setopt($gen_file, CURLOPT_VERBOSE, 0);
            curl_setopt($gen_file, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($gen_file, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($gen_file, CURLOPT_SSL_VERIFYHOST, false);

            curl_setopt($gen_file, CURLOPT_FAILONERROR, true); // Required for HTTP error codes to be reported via our call to curl_error($ch)


            $exec_out = curl_exec($gen_file);

            echo "<pre>";
            var_dump($exec_out);
            echo "</pre>";
            exit;

            if (curl_errno($gen_file)) {
                $error_msg = curl_error($gen_file);

                echo "<pre>";
                print_r($error_msg);
                echo "</pre>";
                exit;
            }
        }
    }

    public function delete_deal()
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
        $response['message'] = 'Video lead deleted successfully!';
        $response['error'] = '';
        $response['emails'] = '';
        $dbData = $this->security->xss_clean($this->input->post());
        $leadData = $this->email->getLeadDataById($dbData['lead_id'], 'vl.*, v.id as video_id');
        if ($leadData) {
            $lead_id = $dbData['lead_id'];
            $sent = false;
            $all_emails = array();
            if (isset($dbData['mail_owner']) && !empty($dbData['mail_owner']) && $dbData['mail_owner'] == 1) {
                $template = $this->app->getEmailTemplateByCode('wooglobe_video_cancellation');
                if ($template) {
                    $cancel_message = $dbData['cancel_comments'];
                    $name = $leadData->first_name;
                    $subject = $template->subject;
                    $message = $template->message;
                    $message = str_replace('@NAME', $name, $message);
                    $message = str_replace('@CANCEL_MESSAGE', $cancel_message, $message);
                    $sent = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = 'rightsmanagement@wooglobe.com', $replyto_name = 'WooGlobe');
                }
            }
            if (isset($dbData['mail_partner']) && !empty($dbData['mail_partner']) && $dbData['mail_partner'] == 1) {
                $template = $this->app->getEmailTemplateByCode('partner_video_cancellation');
                if ($template) {
                    $subject = $template->subject;
                    $subject = str_replace('@WGID', $leadData->unique_key, $subject);
                    $message = $template->message;
                    $wgid = $leadData->unique_key;
                    $title = $leadData->video_title;

                    $partners = $this->email->getPartnersByLeadId($lead_id);
                    foreach ($partners->result() as $partner) {
                        $ext='';
                        $query_raw = 'SELECT rv.s3_url
                        FROM videos v
                        INNER JOIN raw_video rv
                        ON rv.video_id = v.id
                        AND rv.video_id = ' . $leadData->video_id;

                        $videos_raw = $this->db->query($query_raw);
                        $raw_vid = $videos_raw->result();
                        $raw_total = count($raw_vid);

                        $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                        FROM videos v
                        INNER JOIN edited_video ev
                        ON ev.video_id = v.id
                        AND ev.video_id = ' . $leadData->video_id;

                        $videos_edited = $this->db->query($query_edited);
                        $vid = $videos_edited->result();
                        
                        $videourl = 'http://wooglobe.com';

                        if ($raw_total > 1) {
                            if (isset($vid[0])) {
                                $videourl = $vid[0]->portal_url;

                                if($videourl == 'Manual Upload' || $videourl =='http://manualupload.com'){
                                    if (isset($raw_vid[0])) {
                                        if($partner->watermark == 0){
                                            $videourl = $raw_vid[0]->s3_url;
                                        }else{
                                            if(!empty($video->w_url)){
                                                $videourl = $video->w_url;
                                            }else{
                                                $videourl = $raw_vid[0]->s3_url;
                                            }
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
                                }elseif(empty($videourl)){
                                    if($partner->watermark == 0){
                                        $videourl = $raw_vid[0]->s3_url;
                                    }else{
                                        if(!empty($video->w_url)){
                                            $videourl = $video->w_url;
                                        }else{
                                            $videourl = $raw_vid[0]->s3_url;
                                        }
                                    }
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
                                        if($partner->watermark == 0){
                                            $videourl = $raw_vid[0]->s3_url;
                                        }else{
                                            if(!empty($video->w_url)){
                                                $videourl = $video->w_url;
                                            }else{
                                                $videourl = $raw_vid[0]->s3_url;
                                            }
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
                                }else{

                                    $videourl = $vid[0]->portal_url;
                                    if(!empty($videourl)){
                                        if($partner->watermark == 0){
                                            $videourl = $vid[0]->portal_url;
                                        }else{
                                            if(!empty($video->w_url)){
                                                $videourl = $video->w_url;
                                            }else{
                                                $videourl = $vid[0]->portal_url;
                                            }

                                        }
                                    }


                                if(empty($videourl)){
                                    if($partner->watermark == 0){
                                        $videourl = $raw_vid[0]->s3_url;
                                    }else{
                                        if(!empty($video->w_url)){
                                            $videourl = $video->w_url;
                                        }else{
                                            $videourl = $raw_vid[0]->s3_url;
                                        }
                                    }
                                    }

                                    $ext = explode('.', $vid[0]->portal_url);
                                    $ext =$ext[count($ext) - 1];
                                    if(empty($ext)){
                                        $ext = 'mp4';
                                    }
                                }
                            }
                            elseif (isset($raw_vid[0])) {
                                if($partner->watermark == 0){
                                    $videourl = $raw_vid[0]->s3_url;
                                }else{
                                    if(!empty($video->w_url)){
                                        $videourl = $video->w_url;
                                    }else{
                                        $videourl = $vid[0]->s3_url;
                                    }
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
                        $videourl =str_replace("https", "http", $videourl);

                        if(strpos($videourl, "http") === false){
                            $videourl='http://'.$videourl;
                        }

                        $partner_emails = $this->user->getPartnerEmailsById($partner->id)->result();
                        if(isset($partner->email) && !empty($partner->email)) {
                            $partner_emails[] = $partner;
                        }
                        $partner_name = $partner->full_name;
                        $email_message = $message;
                        $email_message = str_replace('@PARTNER_NAME', $partner_name, $email_message);
                        $email_message = str_replace('@VIDEO_WGID', $wgid, $email_message);
                        $email_message = str_replace('@VIDEO_TITLE', $title, $email_message);
                        $email_message = str_replace('@VIDEO_URL', $videourl, $email_message);
                        foreach ($partner_emails as $email) {
                            $all_emails[] = $email->email;
                            $sent = $this->email($email->email, $partner_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $email_message, $cc = '', $bcc = '', $replyto = 'rightsmanagement@wooglobe.com', $replyto_name = 'WooGlobe');
                        }
                    }
                }
            }
            if ($sent) {
                $response['emails'] = $all_emails;
            } else {
                $response['message'] = 'Email not sent!';
            }
            action_add($dbData['lead_id'], 0, 0, $this->sess->userdata('adminId'), 1, 'Deal Deleted');
        }

        $result_lead = $this->lead->getLeadByIdAllStatus($dbData['lead_id']);
        if (!$result_lead) {

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $dbData1['updated_at'] = date('Y-m-d H:i:s');
        $dbData1['updated_by'] = $this->sess->userdata('adminId');
        $dbData1['deleted_at'] = date('Y-m-d H:i:s');
        $dbData1['deleted_by'] = $this->sess->userdata('adminId');
        $dbData1['deleted'] = 1;
        $this->db->where('id', $dbData['lead_id']);
        $this->db->update('video_leads', $dbData1);

        $this->db->where('lead_id', $dbData['lead_id']);
        $this->db->update('videos', $dbData1);
        
        echo json_encode($response);
        exit;

    }

    public function delete_deal_email_preview()
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
        $response['message'] = 'Video lead deleted successfully!';
        $response['error'] = '';
        $response['emails'] = array();
        $dbData = $this->security->xss_clean($this->input->post());
        $leadData = $this->email->getLeadDataById($dbData['lead_id'], 'vl.*, v.id as video_id');
        if ($leadData) {
            $lead_id = $dbData['lead_id'];
            $sent = false;
            $all_emails = array();
            if (isset($dbData['mail_owner']) && !empty($dbData['mail_owner']) && $dbData['mail_owner'] == 1) {
                $template = $this->app->getEmailTemplateByCode('wooglobe_video_cancellation');
                if ($template) {
                    $cancel_message = $dbData['cancel_comments'];
                    $name = $leadData->first_name;
                    $subject = $template->subject;
                    $message = $template->message;
                    $message = str_replace('@NAME', $name, $message);
                    $message = str_replace('@CANCEL_MESSAGE', $cancel_message, $message);
                    
                    $response['emails']['owner']['from'] = "WooGlobe &lt;noreply@wooglobe.com&gt;";
                    $response['emails']['owner']['to'] = $name . " &lt;" . $leadData->email . "&gt;";
                    $response['emails']['owner']['subject'] = $subject;
                    $response['emails']['owner']['message'] = $message;
                    $response['emails']['owner']['name'] = $leadData->first_name . " " . $leadData->last_name;
                }
                else {
                    $response['code'] = 201;
                    $response['message'] = 'Owner email template not found!';
                    $response['error'] = 'Template not found!';
                }
            }
            if (isset($dbData['mail_partner']) && !empty($dbData['mail_partner']) && $dbData['mail_partner'] == 1) {
                $template = $this->app->getEmailTemplateByCode('partner_video_cancellation');
                if ($template) {
                    $subject = $template->subject;
                    $subject = str_replace('@WGID', $leadData->unique_key, $subject);
                    $message = $template->message;
                    $wgid = $leadData->unique_key;
                    $title = $leadData->video_title;

                    $partners = $this->email->getPartnersByLeadId($lead_id);
                    foreach ($partners->result() as $partner) {
                        $ext='';
                        $query_raw = 'SELECT rv.s3_url
                        FROM videos v
                        INNER JOIN raw_video rv
                        ON rv.video_id = v.id
                        AND rv.video_id = ' . $leadData->video_id;

                        $videos_raw = $this->db->query($query_raw);
                        $raw_vid = $videos_raw->result();
                        $raw_total = count($raw_vid);

                        $query_edited = 'SELECT ev.portal_url,ev.portal_thumb
                        FROM videos v
                        INNER JOIN edited_video ev
                        ON ev.video_id = v.id
                        AND ev.video_id = ' . $leadData->video_id;

                        $videos_edited = $this->db->query($query_edited);
                        $vid = $videos_edited->result();
                        
                        $videourl = 'http://wooglobe.com';

                        if ($raw_total > 1) {
                            if (isset($vid[0])) {
                                $videourl = $vid[0]->portal_url;

                                if($videourl == 'Manual Upload' || $videourl =='http://manualupload.com'){
                                    if (isset($raw_vid[0])) {
                                        if($partner->watermark == 0){
                                            $videourl = $raw_vid[0]->s3_url;
                                        }else{
                                            if(!empty($video->w_url)){
                                                $videourl = $video->w_url;
                                            }else{
                                                $videourl = $raw_vid[0]->s3_url;
                                            }
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
                                }elseif(empty($videourl)){
                                    if($partner->watermark == 0){
                                        $videourl = $raw_vid[0]->s3_url;
                                    }else{
                                        if(!empty($video->w_url)){
                                            $videourl = $video->w_url;
                                        }else{
                                            $videourl = $raw_vid[0]->s3_url;
                                        }
                                    }
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
                                        if($partner->watermark == 0){
                                            $videourl = $raw_vid[0]->s3_url;
                                        }else{
                                            if(!empty($video->w_url)){
                                                $videourl = $video->w_url;
                                            }else{
                                                $videourl = $raw_vid[0]->s3_url;
                                            }
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
                                }else{

                                    $videourl = $vid[0]->portal_url;
                                    if(!empty($videourl)){
                                        if($partner->watermark == 0){
                                            $videourl = $vid[0]->portal_url;
                                        }else{
                                            if(!empty($video->w_url)){
                                                $videourl = $video->w_url;
                                            }else{
                                                $videourl = $vid[0]->portal_url;
                                            }

                                        }
                                    }


                                if(empty($videourl)){
                                    if($partner->watermark == 0){
                                        $videourl = $raw_vid[0]->s3_url;
                                    }else{
                                        if(!empty($video->w_url)){
                                            $videourl = $video->w_url;
                                        }else{
                                            $videourl = $raw_vid[0]->s3_url;
                                        }
                                    }
                                    }

                                    $ext = explode('.', $vid[0]->portal_url);
                                    $ext =$ext[count($ext) - 1];
                                    if(empty($ext)){
                                        $ext = 'mp4';
                                    }
                                }
                            }
                            elseif (isset($raw_vid[0])) {
                                if($partner->watermark == 0){
                                    $videourl = $raw_vid[0]->s3_url;
                                }else{
                                    if(!empty($video->w_url)){
                                        $videourl = $video->w_url;
                                    }else{
                                        $videourl = $vid[0]->s3_url;
                                    }
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
                        $videourl =str_replace("https", "http", $videourl);

                        if(strpos($videourl, "http") === false){
                            $videourl='http://'.$videourl;
                        }

                        $partner_name = $partner->full_name;
                        $url_div = '<a class="email_preview_video_url" href="'.$videourl.'">'.$videourl.'</a>';
                        $email_message = $message;
                        $email_message = str_replace('@PARTNER_NAME', $partner_name, $email_message);
                        $email_message = str_replace('@VIDEO_WGID', $wgid, $email_message);
                        $email_message = str_replace('@VIDEO_TITLE', $title, $email_message);
                        $email_message = str_replace('@VIDEO_URL', $videourl, $email_message);

                        $response['emails']['p_'.$partner->id]['from'] = "Wooglobe &lt;noreply@wooglobe.com&gt;";
                        $response['emails']['p_'.$partner->id]['to'] = $partner_name . " &lt;" . $partner->email . "&gt;";
                        $response['emails']['p_'.$partner->id]['subject'] = $subject;
                        $response['emails']['p_'.$partner->id]['message'] = $email_message;
                        $response['emails']['p_'.$partner->id]['name'] = $partner_name;
                    }
                }
                else {
                    $response['code'] = 201;
                    $response['message'] = 'Partner email template not found!';
                    $response['error'] = 'Template not found!';
                }
            }
        }
        else {
            $response['code'] = 201;
            $response['message'] = 'Video lead not found!';
            $response['error'] = 'Lead Data not found!';
        }
        
        echo json_encode($response);
        exit;

    }

    function deleteDirectory($dir) {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }

        }

        return rmdir($dir);
    }
    public function delete_per()
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
        $lead_id = $dbData['lead_id'];
        $uid=$leadData->unique_key;
        $deletes3file = $this->delete_file("uploads/$uid");
        action_add($dbData['lead_id'], 0, 0, $this->sess->userdata('adminId'), 1, 'Deal Deleted Permantly');
        $result_lead = $this->lead->getLeadByIdAllStatus($dbData['lead_id']);
        if (!$result_lead) {

            $response['code'] = 201;
            $response['message'] = 'No video found!';
            $response['error'] = 'No video found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $this->db->where('id', $dbData['lead_id']);
        $this->db->delete('video_leads');


        $result = $this->video->getVideoByLeadId($lead_id, 'videos');
        if ($result) {
            $this->db->where('lead_id', $lead_id);
            $this->db->delete('videos');
        }
        $result_raw = $this->video->getVideoByLeadId($lead_id, 'raw_video');
        if ($result_raw) {
            $this->db->where('lead_id', $lead_id);
            $this->db->delete('raw_video');
        }
        $videodata = $this->video->getVideoByLeadId($lead_id, 'videos');
        if ($videodata) {
            $videoid = $videodata->id;

            if ($videoid) {
                $this->db->where('video_id', $videoid);
                $this->db->delete('edited_video');
            }
        }
        $this->deleteDirectory("/var/www/html/uploads/$uid");
        echo json_encode($response);
        exit;

    }
    public function appearance_delete()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
       /* $role_permitted_ajax = role_permitted_ajax(false, 'videos', 'delete_lead');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }*/
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Appearance Release deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $dbData = $this->security->xss_clean($this->input->post());
        $leadData = $this->email->getLeadById($dbData['lead_id'], 'vl.*');
        $lead_id = $dbData['lead_id'];
        $this->db->where('id', $lead_id);
        $this->db->delete('appreance_release');

        //$this->deleteDirectory("/var/www/html/uploads/$uid");
        echo json_encode($response);
        exit;

    }
    public function second_signer_delete()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
       /* $role_permitted_ajax = role_permitted_ajax(false, 'videos', 'delete_lead');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }*/
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Second Signer deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $dbData = $this->security->xss_clean($this->input->post());
        $leadData = $this->email->getLeadById($dbData['lead_id'], 'vl.*');
        $lead_id = $dbData['lead_id'];
        $this->db->where('id', $lead_id);
        $this->db->delete('second_signer');

        //$this->deleteDirectory("/var/www/html/uploads/$uid");
        echo json_encode($response);
        exit;

    }

    public function update_closing()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatedate = $this->security->xss_clean($this->input->post('content'));
        $updatedate = date('Y-m-d', strtotime($updatedate));
        $result = $this->deal->updateclosingdate($id, $updatedate);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Closing date updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }

    }

    public function update_revenue()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        $updaterevenue = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updaterevenue($id, $updaterevenue);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Revenue share updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Revenue share not updated');
        }


    }

    public function update_title()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit_title');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatetitle = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updatetitle($id, $updatetitle);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead title updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';

            $this->db->where('lead_id', $id);
            $this->db->update('videos', array('title_updated' => 1));
            if ($this->deal->getCNchecks($id)) {
                $this->db->where('id', $id);
                $this->db->update('video_leads', array('is_cn_updated' => 1, 'cn_datetime' => date('Y-m-d H:i:s')));
            }

            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead title not updated');
        }
    }
    public function update_title_2()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit_title');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatetitle = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updatetitle2($id, $updatetitle);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead title 2 updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead title 2 not updated');
        }
    }
    public function update_seo_targets()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit_target_words');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $update_targets = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updateTargets($id, $update_targets);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead target words updated');
            $response = [
                'code' => 200,
                'message' => "Record Updated",
                'error' => ""
            ];
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead des not updated');
        }
    }
    public function update_video_url()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit_title');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));

        $updatetitle = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updateurl($id, $updatetitle);
        //echo $this->db->last_query();exit;
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead URl updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead URL not updated');
        }
    }
    public function update_video_email()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit_video_email');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatedes =  $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updateLeadEmail($id, $updatedes);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead Email updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead email not updated');
        }
    }
    public function update_des()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit_description');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatedes =  $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updatedes($id, $updatedes);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead des updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            
            $this->db->where('lead_id', $id);
            $this->db->update('videos', array('description_updated' => 1));
            if ($this->deal->getCNchecks($id)) {
                $this->db->where('id', $id);
                $this->db->update('video_leads', array('is_cn_updated' => 1, 'cn_datetime' => date('Y-m-d H:i:s')));
            }

            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead des not updated');
        }
    }
    public function update_tags()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit_tags');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatetags = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updatetags($id, $updatetags);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead tags updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            
            $this->db->where('lead_id', $id);
            $this->db->update('videos', array('tags_updated' => 1));
            if ($this->deal->getCNchecks($id)) {
                $this->db->where('id', $id);
                $this->db->update('video_leads', array('is_cn_updated' => 1, 'cn_datetime' => date('Y-m-d H:i:s')));
            }
            
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead des not updated');
        }
    }

    public function update_message()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit_message');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatemessage = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updatemessage($id, $updatemessage);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead message updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead message not updated');
        }
    }

    public function update_ratings()
    {

        $id = $this->security->xss_clean($this->input->post('id'));
        $updateratings = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updateratings($id, $updateratings);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead rating updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead rating not updated');
        }
    }

    public function update_ratings_comment()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit_rating_comment');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updateratingcomment = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updateratingcomment($id, $updateratingcomment);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead rating comment updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead rating comment not updated');
        }
    }
    public function update_facebook()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatefacebook = $this->security->xss_clean($this->input->post('content'));
        $updatefacebook = preg_replace('/\s+/', '', $updatefacebook);
        $result = $this->deal->updatefacebook($id, $updatefacebook);

         if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Facebook Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Facebook Data not updated');
        }


    }
    public function update_youtube()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updateyoutube = $this->security->xss_clean($this->input->post('content'));
        $updateyoutube = preg_replace('/\s+/', '', $updateyoutube);
        $result = $this->deal->updateyoutube($id, $updateyoutube);

        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Youtube Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Youtube Data not updated');
        }


    }
    public function update_confidence()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit_confidence_level');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updateconfidence = $this->security->xss_clean($this->input->post('content'));
        $updateconfidence = preg_replace('/\s+/', '', $updateconfidence);
        $result = $this->deal->updateconfidence($id, $updateconfidence);

        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Confidence Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Youtube Data not updated');
        }
    }
    public function update_video_comment()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit_internal_notes');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatevideocomment = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updatevideocomment($id, $updatevideocomment);

        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Confidence Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Youtube Data not updated');
        }
    }
    public function update_raws3()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updateraw = $this->security->xss_clean($this->input->post('content'));
        $updateraw = preg_replace('/\s+/', '', $updateraw);
        $result = $this->deal->updateraws3($id, $updateraw);

        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['video_id'] = $result;
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data not updated');
        }


    }
    public function update_docs3()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updates3doc = $this->security->xss_clean($this->input->post('content'));
        $updates3doc = preg_replace('/\s+/', '', $updates3doc);
        $result = $this->deal->updatedocs3($id, $updates3doc);

        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['video_id'] = $result;
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data not updated');
        }


    }
    public function update_editeds3()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updateediteds3 = $this->security->xss_clean($this->input->post('content'));
        $updateediteds3 = preg_replace('/\s+/', '', $updateediteds3);
        $result = $this->deal->updateediteds3($id, $updateediteds3);

        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Edited Video s3 Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['result'] = $result;
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Edited Video s3 Data not updated');
        }


    }
    public function update_thumbs3()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatethumbs3 = $this->security->xss_clean($this->input->post('content'));
        $updatethumbs3 = preg_replace('/\s+/', '', $updatethumbs3);
        $result = $this->deal->updatethumbs3($id, $updatethumbs3);

        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Edited Thumb s3 Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['result'] = $result;
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Edited Thumb s3 Data not updated');
        }


    }
    public function update_deal_mrss()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_update_exclusive_status');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatemrss = $this->security->xss_clean($this->input->post('content'));
        //$content_mrss_categories=implode(" ",$updatemrss);
        $result = $this->deal->updatedealmrss($id, $updatemrss);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'MRSS categories updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
    }

    public function update_q0()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updateq2 = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updateq0($id, $updateq2);
        if ($result > 0) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['video_id'] = $result;
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{

            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data not updated');
            $response['code'] = 404;
            $response['message'] = 'Record Not Updated';
            $response['video_id'] = $result;
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }

    }

    public function update_q1()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatedate = $this->security->xss_clean($this->input->post('content'));
        $updatedate = date('Y-m-d', strtotime($updatedate));
        $result = $this->deal->updateq1($id, $updatedate);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Closing date updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }

    }

    public function update_q2()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updateq2 = $this->security->xss_clean($this->input->post('content'));
        // $updateq2 = preg_replace('/\s+/', '', $updateq2);
        $result = $this->deal->updateq2($id, $updateq2);

        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['video_id'] = $result;
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data not updated');
        }


    }

    public function update_q3()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $id = $this->security->xss_clean($this->input->post('id'));
        $updateq3 = $this->security->xss_clean($this->input->post('content'));
        // $updateq3 = preg_replace('/\s+/', '', $updateq3);
        $result = $this->deal->updateq3($id, $updateq3);

        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['video_id'] = $result;
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data not updated');
        }


    }
    public function contract_cancel()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_contract_cancel');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Contract Cancel upated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('lead_id', 'Lead Id', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('revenue_share', 'lead_id');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {
            $dbData = $this->security->xss_clean($this->input->post());
            if (isset($dbData['lead_id']) && !empty($dbData['lead_id'])) {
                $lead_id = $dbData['lead_id'];
            }
            unset($dbData['lead_id']);
            unset($dbData['contract_cancel_details']);
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');

            $this->db->where('id', $lead_id);
            $this->db->update('video_leads', $dbData);

            $leadData = $this->email->getLeadById($lead_id, 'vl.*');
            action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Contract Cancel');
            include('./vendor/signrequest/src/AtaneNL/SignRequest/Client.php');
            include('./vendor/signrequest/src/AtaneNL/SignRequest/CreateDocumentResponse.php');
            $client = new \AtaneNL\SignRequest\Client($this->config->config['sr_token']);
           /* if (!empty($leadData->sr_uuid)) {
                $cdr = $client->cancelSignRequest($leadData->sr_uuid);
            }*/
            $contractName = $leadData->first_name . '_' . $leadData->last_name . '_' . $leadData->unique_key . '.pdf';
            $this->data['lead_data'] = $leadData;
            $contract_message = "Hi " . $leadData->first_name . "\n,
                                    Your Contract has been cancel";
            $sent = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', 'Contract Cancel', $contract_message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
        }
        echo json_encode($response);
        exit;

    }

    public function dropbox_access_token(){
        if (isset($_GET['code']) && isset($_GET['state'])) {    
            //Bad practice! No input sanitization!
            $code = $_GET['code'];
            $state = $_GET['state'];
            
            // $app = new DropboxApp($GLOBALS["DROPBOX_KEY"], $GLOBALS["DROPBOX_SECRET"]);
            $app = new DropboxApp("acuytfy91crp1xb", "9wmi20ycocatamp");
            
            $dropbox = new Dropbox($app);
            
            $redirect_uri = base_url()."dropbox_access_token";
            
            $dropbox->getAuthHelper()->getPersistentDataStore()->set('state', filter_var($_GET['state'], FILTER_SANITIZE_STRING));
            $authHelper = $dropbox->getAuthHelper();
            
            //Fetch the AccessToken
            $accessToken = $authHelper->getAccessToken($code, $state, $redirect_uri);
            
            
            global $DROPBOX_ACCESS_TOKEN;
            $DROPBOX_ACCESS_TOKEN = $accessToken->getToken();
            
            $this->sess->set_userdata('DROPBOX_ACCESS_TOKEN', $accessToken->getToken());


            echo "Access code updated. Kindly try re-uploading video. <br><br> Closing window in 5 seconds.";
            echo "<script type='text/javascript'>";
                echo "setTimeout(function(){ window.close(); }, 5)";
            echo "</script>";
            
        }
    }


    

    public function upload_dropbox($id = NULL, $title= NULL, $selected_option = NULL){
        $params = $this->input->post();
        if($id == NULL || $title == NULL  || $selected_option == NULL){
            $id = $params['id'];
            $title = $params['title'];
        }

        if($selected_option == NULL){
            $selected_option = $params['choice'];
        }

        if($id == NULL || $title == NULL || $selected_option == NULL){
            exit();
        }

        if($selected_option == "upload_only_sheet"){
            $rows = $params["rows"];
            foreach($rows as $row){
                $this->add_entry_to_sheet($row["Youtube-URL"], $row["From"], $row["To"], $row["WGID"], $row["Predefined-Text"], $row["Dropbox-URL"]);
            }


            $response['code'] = 202;
            $response['message'] = "Records Successfully Updated";
            $response['error'] = "";
            $response['url'] = "";
            echo json_encode($response);
            exit(); 
        }

        $title = trim($title);
        
        // $target_folder = '/Temp/WooGlobe-'.$id.'-'.$title.'/';

        $lead = $this->lead->getLeadByUniqueKey($id);
        $rawVideos = $this->lead->getRawVideosByLeadId($lead->lead_id);
        if($rawVideos == NULL){
            $response['code'] = 404;
            $response['message'] = "";
            $response['error'] = "Something went wrong!!";
            $response['url'] = '';
            echo json_encode($response);
        
            exit();
        }
        
        

        $edited_video = $this->deal->getEditedVideoById($rawVideos->result()[0]->video_id);
        

        $client = $this->get_dropbox_client();

        if(is_string($client)){
            $response['code'] = 301;
            $response['message'] = "";
            $response['error'] = "";
            $response['url'] = $client;
            echo json_encode($response);
            exit(); 
        }


        $response = array();

        $queue_files = array();
        $error_files = array();

        $video_url = "";

        $is_edited = FALSE;

        $edited_result = $edited_video->result();
        if(isset($edited_result[0])){
            if(!empty($edited_result[0]->portal_url)){
                $video_url = $edited_result[0]->portal_url;
                $is_edited = TRUE;
            } 
        }
        // TODO: use 
        $sum_duration = 0;
        if($rawVideos != NULL){
            $res = $this->dropbox_model->update_durations($rawVideos->result_array()[0]["lead_id"]);
            $res = json_decode($res);
            $sum_duration = $res->duration;
        }
        // $sum_duration = 0;
        // foreach ($rawVideos->result_array() as $row){
        //     if($row['video_duration'] == 0){
        //         $res = $this->dropbox_model->update_durations($row['video_id']);
        //         $res = json_decode($res);
        //         $sum_duration += $res->duration;  
        //     }
        //     else{
        //         $sum_duration += $row['video_duration'];
        //     }

        // }

        $rawVideos = $rawVideos->result();
        if($sum_duration < 20){

            $video_id = $rawVideos[0]->video_id;
            $result = $this->db->where("id =", $video_id)->get("videos");
            $youtube_id = $result->result_array()[0]["youtube_id"];
            if($youtube_id == NULL || strlen($youtube_id) == 0){
                $response['code'] = 201;
                $response['message'] = "";
                $response['error'] = "Video not published to Youtube!";
                $response['url'] = "";
                echo json_encode($response);
                exit(); 
            }
            if($selected_option == "upload_only_dropbox"){
                $response['code'] = 201;
                $response['message'] = "";
                $response['error'] = "Please select second option for videos less than 20 seconds!";
                $response['url'] = "";
                echo json_encode($response);
                exit(); 
            }


            $target_folder = '/Weglobe/Less Than 20 (Manual Claims)/WooGlobe-'.$id.'-'.$title.'/';
        }
        else{
            $target_folder = '/Weglobe/Processing/WooGlobe-'.$id.'-'.$title.'/';
        }

        
        $is_enter_sheet = 0;
        if($selected_option == "upload_dropbox_sheet"){
            $is_enter_sheet = 1;
        }


        // Final Uploading
        foreach ($rawVideos as $row){
            $base_file_name = basename($row->url);
            
            if($row->s3_url != NULL){
                if(!$is_edited){
                    $video_url = $row->s3_url;
                }

                $file_ext = pathinfo($row->s3_url, PATHINFO_EXTENSION);
                $target_file = $target_folder.'WooGlobe-'.$id.'-'.$title.'.'.$file_ext;
                $queue_files[$base_file_name] = $this->upload_file_dropbox_new($video_url, $target_file, $lead->lead_id, $is_edited, $is_enter_sheet);
                if($is_edited){
                    break;
                }
            }
            else{
                $error_files[$base_file_name] = "Video not uploaded to S3 yet!";
            }

        }
        $response['code'] = 200;
        $response['message'] = $queue_files;
        $response['error'] = $error_files;
        $response['url'] = '';
        if($this->input->post() != NULL){
            echo json_encode($response);
            exit();
        }

        return json_encode($response);
        // exit();
    }

    public function bulk_upload_dropbox(){
        $params = $this->input->post();
        $data = $params['dropbox_rows'];
        $overwrite_already_uploaded = $params['overwrite'];

        if($data){
            $res = $this->deal->getDealsByUniqueKeys($data, "vl.unique_key, vl.video_title, rv.dropbox_status")->result_array();
            $queued = 0;

            foreach($res as $row){
                if($row["dropbox_status"] == "success" && $overwrite_already_uploaded == FALSE)
                    continue;

                $r = $this->upload_dropbox($row["unique_key"], $row["video_title"], "upload_dropbox_sheet");
                $out = json_decode($r);
                if($out->code == 200){
                    $queued += 1;
                }
            }
            $response['code'] = 200;
            $response['message'] = $queued." files are being processed";
            $response['error'] = "";
            $response['url'] = '';
            
            echo json_encode($response);
        }
    }

    public function upload_file($uid)
    {

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
        $file_pointer = './../uploads/'.$uid.'/raw_videos/';

        if (file_exists($file_pointer))
        {
            include('./app/third_party/class.fileuploader.php');
            $FileUploader = new FileUploader('file', array(
                'title'=> strtolower($uid).'_'.time(),
                'uploadDir'=> './../uploads/'.$uid.'/raw_videos/'
            ));
        }
        else
        {
            $root_path = './../uploads/'.$uid;
            $raw_videos = './../uploads/'.$uid.'/raw_videos';
            $edited_videos = './../uploads/'.$uid.'/edited_videos';
            $edited_yt = './../uploads/'.$uid.'/edited_videos/youtube';
            $edited_yt_thumb = './uploads/'.$uid.'/edited_videos/youtube/thumbnail';
            $edited_fb = './../uploads/'.$uid.'/edited_videos/facebook';
            $edited_fb_thumb = './../uploads/'.$uid.'/edited_videos/facebook/thumbnail';
            $edited_mrsss = './../uploads/'.$uid.'/edited_videos/mrss';
            $edited_mrss_thumb = './../uploads/'.$uid.'/edited_videos/mrss/thumbnail';
            $documents = './../uploads/'.$uid.'/documents';
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
            include('./app/third_party/class.fileuploader.php');
            $FileUploader = new FileUploader('file', array(
                'title'=> strtolower($uid).'_'.time(),
                'uploadDir'=> './uploads/'.$uid.'/raw_videos/'
            ));
        }


        // call to upload the files
        $upload = $FileUploader->upload();

        if ($upload['isSuccess']) {
            // get the uploaded files
            $files = $upload['files'];
            foreach ($upload['files'] as $i => $file) {
                $upload['files'][$i]['video'] = 'uploads/' . $uid . '/raw_videos/' . $file['name'];
                $upload['files'][$i]['title_new'] = explode('.', $file['name'])[0];
            }
        }


        echo json_encode($upload);
        exit;

    }

    /*for submit information behalf on user*/
    /*    public function upload_user_submit(){
            $response = array();
            $response['code'] = 200;
            $response['message'] = 'Video Uploaded Successfully!';
            $response['error'] = '';
            $response['url'] = '';
            $db_data = $this->security->xss_clean($this->input->post());
            $id=$db_data['lead_id'];
            $lead_data=$this->lead->getLeadByIdAllStatus($id);
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Admin Upload Raw File');
            $data = array(
                'load_view' => 2,
            );
            $this->db->where('id', $id);
            $this->db->update('video_leads',$data);
            echo json_encode($response);
            exit;
        }*/
    public function upload_user_submit()
    {
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Video Uploaded Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $db_data = $this->security->xss_clean($this->input->post());
        $id = $db_data['lead_id'];
        $lead_data = $this->lead->getLeadByIdAllStatus($id);
      /*  action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Admin Upload Raw File');
        $video_data = $this->deal->getVideosByLeadId($id);
        if ($video_data) {
            $vid = $video_data->id;
            $ldata = array(
                'title' => $lead_data->video_title,
                'slug' => $lead_data->slug,
                'lead_id' => $lead_data->id,
            );
            $this->db->where('lead_id', $id);
            $this->db->update('videos', $ldata);
        } else {
            $ldata = array(
                'title' => $lead_data->video_title,
                'slug' => $lead_data->slug,
                'lead_id' => $lead_data->id,
            );
            $vid = $this->lead->insert_video($ldata);
        }

        $data = array(
            'load_view' => 2,
        );
        $this->db->where('id', $id);
        $this->db->update('video_leads', $data);*/
        foreach ($db_data['url'] as $url) {
            $raw_data['url'] = $url;
            /*$raw_data['video_id'] = $vid;*/
            $raw_data['lead_id'] = $lead_data->id;
            $result = $this->lead->update_raw_video($raw_data);
        }
       echo json_encode($response);
        exit;
    }

    public function story_information_submission()
    {
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Story Information Submitted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('question_video_taken', 'Where was this taken(Country/City)?*', 'trim|required');
        $this->validation->set_rules('question_when_video_taken', 'When was this video Taken?*', 'trim|required');
        $this->validation->set_rules('question_video_context', 'What was the context?*', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('question_video_taken', 'question_when_video_taken','question_when_video_taken');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }
        else{
            $db_data = $this->security->xss_clean($this->input->post());

            $lead_id = $db_data['lead_id'];

            action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Admin Upload Story Information Submission');
            $data = array(
                'load_view' => 3,
            );
            $this->db->where('lead_id', $lead_id);
            $this->db->update('videos', $db_data);
            $this->db->where('id', $lead_id);
            $this->db->update('video_leads', $data);

        }
        echo json_encode($response);
        exit;

    }

    public function personal_information_submission()
    {
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Personal Information Submitted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('email', 'Email', 'trim|required');
        $this->validation->set_rules('address', 'Mobile Number', 'trim|required');

        $this->validation->set_rules('country_id', 'Country', 'trim|required');
        $this->validation->set_rules('state_id', 'Country', 'trim|required');
        $this->validation->set_rules('city_id', 'Country', 'trim|required');
        $this->validation->set_rules('zip_code', 'Country', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('email', 'address','country_id','state_id','city_id','zip_code');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }
        else{
            $db_data = $this->security->xss_clean($this->input->post());
            $client_id = $db_data['id'];
            action_add($client_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Admin Upload Story Information Submission');
            $data = array(
                'load_view' => 4,
                'information_pending'=>1
            );
            $lead_id=$db_data['lead_id'];
            unset($db_data['lead_id']);
            $this->db->where('id', $client_id);
            $this->db->update('users', $db_data);
            $this->db->where('id', $lead_id);
            $this->db->update('video_leads', $data);
        }

        echo json_encode($response);
        exit;
    }


    public function staff_update()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Staff Assigned Successfully';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('staff_id', 'Staff Member', 'trim|required');
        $this->validation->set_rules('uid', 'UID', 'trim|required');


        if ($this->validation->run() === false) {

            $fields = array('staff_id', 'uid');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }
        else{
            $db_data = $this->security->xss_clean($this->input->post());
            $uid = $db_data['uid'];
            $data = array(
                'staff_id' => $db_data['staff_id']
            );

            $this->db->where('unique_key', $uid);
            $this->db->update('video_leads', $data);
        }

        echo json_encode($response);
        exit;
    }
    public function video_type()
    {
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_edit');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Video Type Assigned Successfully';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('video_type', 'Video Type', 'trim|required');
        $this->validation->set_rules('uid', 'UID', 'trim|required');


        if ($this->validation->run() === false) {

            $fields = array('video_type', 'uid');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }
        else{
            $db_data = $this->security->xss_clean($this->input->post());
            $uid = $db_data['uid'];
            $simple_video = 0;
            if($db_data['video_type']== 'simple')
            {
                $simple_video = 1;
            }



            $data = array(
                'lead_type' => $db_data['video_type'],
                'simple_video' => $simple_video

            );

            $this->db->where('unique_key', $uid);
            $this->db->update('video_leads', $data);
        }

        echo json_encode($response);
        exit;
    }
    public function signer_link()
    {
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Signer Link Successfully';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('days_expire', 'DAY EXPIRE', 'trim|required');
        $this->validation->set_rules('uid', 'UID', 'trim|required');
        if ($this->validation->run() === false) {

            $fields = array('days_expire','uid');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }
        else{
            $db_data = $this->security->xss_clean($this->input->post());
            $data_expire = array(
                'days_interval	' => $db_data['days_expire'],
                'unique_key'=> $db_data['uid'],
                'link_type'=> $db_data['type'],
            );
            $data_expire['created_at'] = date('Y-m-d H:i:s');
            $data_expire['created_by'] = $this->sess->userdata('adminId');
            $this->db->insert('release_link', $data_expire);
        }

        echo json_encode($response);
        exit;
    }
    public function renew_date_signerlink()
    {
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Update Link Successfully';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('id', 'UID', 'trim|required');
        if ($this->validation->run() === false) {

            $fields = array('id');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }
        else{

            $db_data = $this->security->xss_clean($this->input->post());
            $data_id =  $db_data['id'];
            $update_date['created_at'] = date('Y-m-d H:i:s');
            $this->db->where('id',$data_id);
            $this->db->update('release_link', $update_date);
            //echo $this->db->last_query();exit;
        }

        echo json_encode($response);
        exit;

    }
    public function second_signer_appearance_release(){

     $response = array();

     $response['code'] = 200;
     $response['message'] = 'Appearance Release Created Successfully';
     $response['error'] = '';
     $data = $this->security->xss_clean($this->input->post());
     $this->validation->set_rules('appearance_detail', 'Appearance detail', 'trim|required');
     if ($this->validation->run() === false) {

         $fields = array('appearance_detail');
         $errors = array();
         foreach ($fields as $field) {
             $errors[$field] = form_error($field);
         }
         $response['code'] = 201;
         $response['message'] = 'Your has been not submitted yet, Please recheck your field carefully.';
         $response['error'] = $errors;
         header("Content-type: application/json");
         $response['url'] = '';
         echo json_encode($response);
         exit;
     } else {
         $uid = $data['uid'];
         $id = $data['Second_Signer_Id'];
         $leadid = $this->db->query('SELECT * FROM `second_signer` WHERE `id` ="'.$id.'"');
         $second_signer = $leadid->row();
         $first_name = $second_signer->first_name;
         $last_name = $second_signer->last_name;
         $phone = $second_signer->phone;
         $country_code = $second_signer->countary_code;
         $country = $second_signer->country_id;
         $state = $second_signer->state_id;
         $city = $second_signer->city_id;
         $address = $second_signer->address;
         $address2 = $second_signer->address2;
         $zip = $second_signer->zip_code;
         $help_us = $data['appearance_detail'];
         $dateadded = $second_signer->created_at;
         $signature = $second_signer->img;
         $email = $second_signer->email;
         $time = time();
         $pdf_link = '/uploads/'.$uid.'/appreance/'.$uid.'_signed_'.$time.'.pdf';

         //Insert  data
         $insert_appreance_lead = array(
             'uid' => $uid,
             'first_name' => $first_name,
             'last_name' =>  $last_name,
             'email' => $email,
             'countary_code' => $country,
             'phone' => $phone,
             'country_id'=>$country,
             'state_id'=>$state,
             'city_id'=>$city,
             'address' => $address,
             'address2' => $address2,
             'zip_code' => $zip,
             'help_us'  => $help_us,
             'pdf_link'=>$pdf_link,
             'created_at' => $dateadded,
             'img'=> $signature
         );
        $this->db->insert('appreance_release',$insert_appreance_lead);

     }
     $first_name = $first_name ;
     $last_name = $last_name;
     $full_adress = $address .' '.$address2;
     $country_name_va = $country;
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

     $appreance_pdf= $this->appreance_signed_pdf($url_name,$first_name,$last_name,$email,$phone,$country,$state,$city,$full_adress,$zip,$signature,$help_us,$date,$unique_key,$data_log,$api_result,$time);
     $source_file = rtrim($_SERVER['DOCUMENT_ROOT'],'/') . $pdf_link;
     $file_extension = explode('.', $source_file);
     $file_extension = $file_extension[1];
     $target_file_key = $pdf_link;
     $url = $this->upload_file_s3_new('private',$source_file, $target_file_key, $file_extension, FALSE);
     header("Content-type: application/json");
     echo json_encode($response);
     exit;



 }
    public function appreance_signed_pdf($url_name,$first_name,$last_name,$email,$phone,$country,$state,$city,$address,$zip,$signature,$help_us,$date,$unique_key,$data_log,$api_result,$time,$sig = null){
        if(empty($sig)){
            $this->base30_to_jpeg ( $signature, root_path(). '/uploads/'.$unique_key.'/appreance/'.$unique_key.'_'.$time.'.png' );
            $signimgpath= root_path().'/uploads/'.$unique_key.'/appreance/'.$unique_key.'_'.$time.'.png';
        }else{
            $signimgpath = $signature;
        }



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
    public function owner_appreance_signed_pdf($url_name,$first_name,$last_name,$email,$phone,$country,$state,$city,$address,$zip,$help_us,$date,$unique_key,$data_log,$api_result,$time){
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
    public function base30_to_jpeg($base30_string, $output_file) {
        require_once (root_path().'admin/app/libraries/jSignature_Tools_Base30.php');
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

    public function soical_video_delete(){
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Social Video Deleted';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->input->post('video_id');
        $query = "SELECT * FROM `social_videos` WHERE video_id = ".$id;
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            $row = $result->row();
            $this->delete_file(str_replace('https://wooglobe.s3-us-west-2.amazonaws.com/','',$row->s3_url));
            $this->db->where('video_id',$id);
            $this->db->delete('social_videos');

        }
        echo json_encode($response);
        exit;
    }
    public function raw_video_delete(){

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Raw Video Deleted';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->input->post('id');
        $query = "SELECT * FROM `raw_video` WHERE id = ".$id;
        $result = $this->db->query($query);
        if($result->num_rows() > 0){
            $row = $result->row();

            if(!empty($row->s3_url)){

                $this->delete_file(str_replace('https://wooglobe.s3.us-west-2.amazonaws.com/','',$row->s3_url));
            }

            unlink($_SERVER['DOCUMENT_ROOT'].'/'.$row->url);
            $this->db->where('id',$id);
            $this->db->delete('raw_video');

        }
        echo json_encode($response);
        exit;
    }


    public function manual_ar()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Appearance Release signed Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('appearance_detail', 'Identify The Person', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('id');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {

            $dbData = $this->security->xss_clean($this->input->post());
            $uid = $dbData['uid'];
            $person = $dbData['appearance_detail'];
            $leadQuery = "
                    SELECT  vl.*,u.full_name,
                           u.mobile,
                           u.country_code,
                           u.country_id,
                           u.state_id,
                           u.city_id,
                           u.address,
                           u.address2,
                           u.zip_code,
                           u.email
                    FROM video_leads vl
                    INNER JOIN users u
                    ON u.id = vl.client_id
                    WHERE vl.unique_key = '$uid'
                    AND u.mobile NOT NULL 
                    AND u.country_code NOT NULL 
                    AND u.country_id NOT NULL 
                    AND u.state_id NOT NULL 
                    AND u.address NOT NULL 
                    AND u.address2 NOT NULL 
                    AND u.zip_code NOT NULL 
                    AND u.email NOT NULL 
            ";
            $lead = $this->db->query($leadQuery)->row();

            if($lead){
                $full_name = explode(' ',$lead->full_name);
                $first_name = $full_name[0];
                $last_name =  $full_name[1];
                $query = "SELECT * FROM `appreance_release` WHERE uid = '$uid' AND first_name = '$first_name' AND last_name = '$last_name'";
                $result = $this->db->query($query);
                if ($result->num_rows() > 0)
                {
                    $errors = array();
                    $response['code'] = 201;
                    $response['message'] = 'You have already submit the second signer form.';
                    $response['error'] = $errors;
                    header("Content-type: application/json");
                    $response['url'] = '';
                    echo json_encode($response);
                    exit;
                }else{
                    $country_code = explode('-',$lead->country_code);
                    $phone = $lead->mobile;
                    $country_code = $country_code[0];
                    $country = $lead->country_code;
                    $state = $lead->state_id;
                    $city = $lead->city_id;
                    $address = $lead->address;
                    $address2 = $lead->address2;
                    $zip = $lead->address2;
                    $help_us = $person;
                    $dateadded = date("Y-m-d H:i:s");

                    $sigimag = './../uploads/'.$uid.'/documents/'.$uid.'.png';
                    $signature = $sigimag;
                    $email = $lead->email;
                    $time = time();
                    $pdf_link = 'uploads/'.$uid.'/appreance/'.$uid.'_signed_'.$time.'.pdf';
                    $insert_appreance_lead = array(
                        'uid' => $uid,
                        'first_name' => $first_name,
                        'last_name' =>  $last_name,
                        'email' => $email,
                        'countary_code' => $country,
                        'phone' => $phone,
                        'country_id'=>$country,
                        'state_id'=>$state,
                        'city_id'=>$city,
                        'address' => $address,
                        'address2' => $address2,
                        'zip_code' => $zip,
                        'terms_check'=>1,
                        'help_us'  => $person,
                        'pdf_link'=>$pdf_link,
                        'created_at' => $dateadded,
                        'img'=> $signature,
                        'manual'=> 1
                    );
                    $this->db->insert('appreance_release',$insert_appreance_lead);
                    $first_name = $first_name ;
                    $last_name = $last_name;
                    $full_adress = $address.' '.$address2;
                    $country_name_va = $country;
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
                    $appreance_pdf= $this->appreance_signed_pdf($url_name,$first_name,$last_name,$email,$phone,$country,$state,$city,$full_adress,$zip,$signature,$help_us,$date,$unique_key,$data_log,$api_result,$time,true);
                    if($_SERVER['HTTP_HOST'] == 'localhost') {
                        $source_file = $_SERVER['DOCUMENT_ROOT'].'/uat/'. '/' . $pdf_link;
                    }else {
                        $source_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $pdf_link;
                    }
                    $file_extension = explode('.', $source_file);
                    $file_extension = $file_extension[1];
                    $target_file_key = $pdf_link;
                    $url = $this->upload_file_s3_new('private',$source_file, $target_file_key, $file_extension, FALSE);

                    echo json_encode($response);
                    exit;
                }
            }else{
                $errors = array();
                $response['code'] = 201;
                $response['message'] = 'Invalid lead Id';
                $response['error'] = $errors;
                header("Content-type: application/json");
                $response['url'] = '';
                echo json_encode($response);
                exit;
            }




        }
        echo json_encode($response);
        exit;
    }

    public function email_contract_send(){
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_click_email');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Email contract send';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('lead_id'));
        $query = "SELECT * FROM video_leads WHERE Id =$id";
        $lead_query = $this->db->query($query);
        $result = $lead_query->row();
        $user_id = $result->client_id;
        $unique_key = $result->unique_key;
        $query = "SELECT * FROM users WHERE Id = $user_id";
        $user_query = $this->db->query($query);
        $result2 = $user_query->row();

        $file_to_attach = '';
        if($_SERVER['HTTP_HOST'] == 'localhost') {

            $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/uat/uploads/'.$unique_key.'/documents/'.$unique_key. '_signed.pdf';

        }else{
            $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$unique_key.'/documents/'.$unique_key. '_signed.pdf';

        }
        if(!empty($file_to_attach) && !file_exists($file_to_attach)){
            $query = "SELECT * FROM raw_video WHERE lead_id = $id AND s3_document_url IS NOT NULL";
            $raw_query = $this->db->query($query);
            if($raw_query->num_rows() > 0){
                $result2 = $raw_query->row();
                $file_path = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$unique_key.'/documents/'.$unique_key. '_signed.pdf';
                $file = $this->get_file_s3_pri($file_path);
                $file_download = file_get_contents($file,$file_path);
                //$file_to_attach = $file_download;
                if($_SERVER['HTTP_HOST'] == 'localhost') {

                    $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/uat/uploads/'.$unique_key.'/documents/'.$unique_key. '_signed.pdf';

                }else{
                    $file_to_attach = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$unique_key.'/documents/'.$unique_key. '_signed.pdf';

                }
            }
            else{
                $response['code'] = 201;
                $response['message'] = 'Not Contract Signed Yet';
                $response['error'] = '';
                $response['url'] = '';
                echo json_encode($response);
                exit;
            }
        }
        $subject = "Video Contract";
        $message = "Attact video Contract";
        $result = $this->email($result2->email, $result2->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '', $file_to_attach, $unique_key);
        echo json_encode($response);
        exit;
    }

    public function view_contract($lead_id){
        $rawVideos = $this->deal->getRawVideoByLeadId($lead_id);
        $url = base_url('dashboard');

        foreach ($rawVideos->result() as $row){

           // 'https://wooglobe.s3.us-west-2.amazonaws.com/'
            if(!empty($row->s3_document_url)){
                $key = str_replace('https://wooglobe.s3-us-west-2.amazonaws.com/','',$row->s3_document_url);
                $key = str_replace('https://wooglobe.s3.us-west-2.amazonaws.com/','',$key);
                $url = $this->get_file_s3($key);
                /*if(!file_exists($_SERVER['DOCUMENT_ROOT'].$key)){
                    $url = $this->get_file_s3($key);
                }else{
                    $url = root_url($key);
                }*/

            }
        }
        redirect($url);
    }


    public function add_to_white(){
        $data = $this->input->post();
        $this->load->library('sheet');
        $this->sheet->addRowToSpreadsheet(array($data['name'],$data['link'],'',''),'123746797');
        $response['code'] = 200;
        $response['message'] = 'Social Video Deleted';
        $response['error'] = '';
        $response['url'] = '';
        echo json_encode($response);
        exit;
    }

    public function report_bug()
    {
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Report a bug of deal successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('lead_id', 'Lead Id', 'trim|required');
        $this->validation->set_rules('report_issue_type[]', 'Report Type', 'trim|required');
        $this->validation->set_rules('report_issue_desc', 'Detail', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('lead_id', 'report_issue_type','report_issue_desc');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }
        else{
            $db_data = $this->security->xss_clean($this->input->post());
            $type = $this->security->xss_clean($this->input->post('report_issue_type[]'));
            $lead_id = $db_data['lead_id'];

            action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Report Deal Issue');
            unset($db_data['lead_id']);
            $db_data['report_issue_type'] = implode(',',$type);
            $db_data['scout_resolved'] = 0;

            $this->db->where('id', $lead_id);
            $this->db->update('video_leads', $db_data);

        }
        echo json_encode($response);
        exit;

    }
    public function report_bug_resolved()
    {
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Issue Solved successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('id', 'Lead Id', 'trim|required');

        if ($this->validation->run() === false) {

            $fields = array('id');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }
        else{
            $db_data = $this->security->xss_clean($this->input->post());
            $lead_id = $db_data['id'];

            action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Deal Issue Resolved');
            unset($db_data['id']);
            $db_data['report_issue_type'] = null;
            $db_data['report_issue_desc'] = null;
            $db_data['scout_resolved'] = 1;

            $this->db->where('id', $lead_id);
            $this->db->update('video_leads', $db_data);

        }
        echo json_encode($response);
        exit;

    }
    
    public function scout_report_bug_resolved()
    {
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Issue Solved successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('id', 'Lead Id', 'trim|required');

        if ($this->validation->run() === false) {

            $fields = array('id');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }
        else{
            $db_data = $this->security->xss_clean($this->input->post());
            $lead_id = $db_data['id'];

            action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Deal Issue Resolved');
            unset($db_data['id']);
            $db_data['scout_resolved'] = 1;

            $this->db->where('id', $lead_id);
            $this->db->update('video_leads', $db_data);

        }
        echo json_encode($response);
        exit;

    }

    public function cn_update()
    {
        $request = $this->security->xss_clean($this->input->post());
        if ($request['checked'] == 1) {
            $db_data['is_cn_updated'] = 1;
            $db_data['cn_datetime'] = date('Y-m-d H:i:s');
        }
        else {
            $db_data['is_cn_updated'] = 0;
            $db_data['cn_datetime'] = NULL;
        }
        $this->db->where('id', $request['lead_id']);
        $this->db->update('video_leads', $db_data);
    }
    public function ve_update()
    {
        $request = $this->security->xss_clean($this->input->post());
        if ($request['checked'] == 1) {
            $db_data['uploaded_edited_videos'] = 1;
            $db_data['edited_datetime'] = date('Y-m-d H:i:s');
        }
        else {
            $db_data['uploaded_edited_videos'] = 0;
            $db_data['edited_datetime'] = NULL;
        }
        $this->db->where('id', $request['lead_id']);
        $this->db->update('video_leads', $db_data);
    }
    public function trending_update()
    {
        $request = $this->security->xss_clean($this->input->post());
        if ($request['checked'] == 1) {
            $db_data['trending'] = 1;
            $db_data['trending_datetime'] = date("Y-m-d H:i:s");
        }
        else {
            $db_data['trending'] = 0;
            $db_data['trending_datetime'] = NULL;
        }
        
        $this->db->where('id', $request['lead_id']);
        $this->db->update('video_leads', $db_data);
    }
    public function ai_based_update()
    {
        $request = $this->security->xss_clean($this->input->post());
        if ($request['checked'] == 1) {
            $db_data['is_ai_based'] = 1;
        }
        else {
            $db_data['is_ai_based'] = 0;
        }
        
        $this->db->where('id', $request['lead_id']);
        $this->db->update('video_leads', $db_data);
    }

    public function save_res_comm()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        $comment =  $this->security->xss_clean($this->input->post('comment'));
        $result = $this->deal->updateResComm($id, $comment);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Researcher comment updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Researcher comment not updated');
        }
    }

    public function save_mgr_comm()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        $comment =  $this->security->xss_clean($this->input->post('comment'));
        $result = $this->deal->updateMgrComm($id, $comment);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Manager comment updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
        else{
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Manager comment not updated');
        }
    }

    public function res_att_upload($lead_id)
    {
        $id = $this->lead->getUniquekey($lead_id)->unique_key;
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt', 'txt');
        $res_directory = "../uploads/".$id."/attachments/researcher";
        if(!is_dir("../uploads/".$id."/attachments/researcher"))
        {
            mkdir("../uploads/".$id."/attachments/researcher", 0777, true);
        }
        if ($handle = opendir($res_directory))
        {
            while(($file = readdir($handle)) !== FALSE)
            {
                $res_att[] = basename($file);
            }
            closedir($handle);
            $res_att = array_diff($res_att, array('.', '..'));
        }
        if($_FILES['res-file'])
        {
            for($i = 0; $i < count($_FILES['res-file']['name']); $i++){
                $duplicate_no = 1;
                $rep = "";
                $path = "../uploads/".$id."/attachments/researcher/";
                $img = $_FILES['res-file']['name'][$i];
                $tmp = $_FILES['res-file']['tmp_name'][$i];
                $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                $oldname = str_replace(".$ext", "", strtolower($img));
                while (in_array(strtolower($img), $res_att)) {
                    $rep = "-$duplicate_no.";
                    $img = $oldname.$rep.$ext;
                    echo $img;
                    $duplicate_no++;
                }
                $path = $path.strtolower($img);
                if(in_array($ext, $valid_extensions))
                {
                    $path = $path.strtolower($final_image);
                    if(move_uploaded_file($tmp,$path))
                    {
                        $this->deal->addCommentAttachment($lead_id, $id);
                        $response['code'] = 200;
                        $response['message'] = 'Record Updated';
                        $response['error'] = '';
                    }
                    else
                    {
                        $response['code'] = 409;
                        $response['message'] = 'Something Went Wrong!';
                        $response['error'] = '';
                    }
                }
                else
                {
                    $response['code'] = 422;
                    $response['message'] = 'Invalid Extension';
                    $response['error'] = '';
                }
            }
        }
    }

    public function mgr_att_upload($lead_id)
    {
        $id = $this->lead->getUniquekey($lead_id)->unique_key;
        $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'bmp' , 'pdf' , 'doc' , 'ppt', 'txt');
        $mgr_directory = "../uploads/".$id."/attachments/manager";
        if(!is_dir("../uploads/".$id."/attachments/manager"))
        {
            mkdir("../uploads/".$id."/attachments/manager", 0777, true);
        }
        if ($handle = opendir($mgr_directory))
        {
            while(($file = readdir($handle)) !== FALSE)
            {
                $mgr_att[] = basename($file);
            }
            closedir($handle);
            $mgr_att = array_diff($mgr_att, array('.', '..'));
        }
        if($_FILES['mgr-file'])
        {
            for($i = 0; $i < count($_FILES['mgr-file']['name']); $i++){
                $duplicate_no = 1;
                $rep = "";
                $path = "../uploads/".$id."/attachments/manager/";
                $img = $_FILES['mgr-file']['name'][$i];
                $tmp = $_FILES['mgr-file']['tmp_name'][$i];
                $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                $path = $path.strtolower($img);
                $oldname = str_replace(".$ext", "", strtolower($img));
                while (in_array(strtolower($img), $mgr_att)) {
                    $rep = "-$duplicate_no.";
                    $img = $oldname.$rep.$ext;
                    $duplicate_no++;
                }
                if(in_array($ext, $valid_extensions))
                {
                    $path = $path.strtolower($final_image);
                    if(move_uploaded_file($tmp,$path))
                    {
                        $this->deal->addCommentAttachment($lead_id, $id);
                        $response['code'] = 200;
                        $response['message'] = 'Record Updated';
                        $response['error'] = '';
                    }
                    else
                    {
                        $response['code'] = 409;
                        $response['message'] = 'Something Went Wrong!';
                        $response['error'] = '';
                    }
                }
                else
                {
                    $response['code'] = 422;
                    $response['message'] = 'Invalid Extension';
                    $response['error'] = '';
                }
            }
        }
    }

    public function remove_att()
    {
        $file =  $this->security->xss_clean($this->input->post('file'));
        unlink($file);
    }

    public function set_priority()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        $value =  $this->security->xss_clean($this->input->post('selected'));
        if ($value == 1) {
            $db_data['priority'] = "High";
        }
        else if ($value == 2) {
            $db_data['priority'] = "Medium";
        }
        else if ($value == 3) {
            $db_data['priority'] = "Low";
        }
        $this->db->where('id', $id);
        $this->db->update('video_leads', $db_data);

        $response['code'] = 200;
        $response['message'] = 'Record Updated';
        $response['error'] = '';
        echo json_encode($response);
    }

    public function ChatGPTTextPaint()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data =  $this->input->post()["data"];
            $data = json_decode($data);
            $ch = curl_init();
            // Caling Chat GPT
            curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            // Genrateing Config
            $data = array(
                'model' => 'gpt-3.5-turbo',
                'messages' => array(
                    array(
                        'role' => 'user',
                        'content' => $data,
                    ),
                ),
                'temperature' => 0.7,
            );
            // Preparing data
            $jsonData = json_encode($data);
            // Contructer Object 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . CHAT_GPT_KEY;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                $response = [
                    'status' => 400,
                    'message' => 'Error:' . curl_error($ch)
                ];
                echo json_encode($response);
                exit;
            } else {

                curl_close($ch);

                $response_data = json_decode($result);

                $messages = $response_data->choices[0]->message->content;
                $response = [
                    'status' => 200,
                    'message' =>  $messages,
                ];
            }
            echo json_encode($response);
            exit;
        }
    }

    public function generate_appearance_release()
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
        $response['message'] = 'Appearance Release Generated Successfully!';
        $response['error'] = '';
        
        $result = $this->security->xss_clean($this->input->post());
        $lead_id = $result['lead_id'];
        $leadquery = $this->db->query('SELECT *
        FROM video_leads vl
        WHERE vl.id = '.$lead_id);
        $leadData = $leadquery->row();

        $data_log_query=$this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="'.$lead_id.'"');
        $logs_query=$data_log_query->result();
        $sent_log_query=$this->db->query('SELECT lead_rated_date  FROM `lead_action_dates` WHERE `lead_id` = "'.$lead_id.'"');
        $sent_log_result=$sent_log_query->result();
        $user_query=$this->db->query('SELECT *  FROM `users` WHERE `id` = "'.$leadData->client_id.'"');
        $user_result=$user_query->result();
        
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
        
        $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';
        $ch = curl_init('http://api.ipstack.com/'.$data_log['user_ip_address'].'?access_key='.$access_key.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        curl_close($ch);
        $api_result = json_decode($json, true);

        $country_name_va='';
        if($user['country']){
            $country_name=explode("-",$user['country']);
            if(isset($country_name[1])){
                $country_name_va=$country_name[1];
            }
        }

        $time = time();
        $uid =$leadData->unique_key ;

        $help_us =$result['release_description'];
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

        $appreance_pdf= $this->owner_appreance_signed_pdf($data['video_url'],$leadData->first_name,$leadData->last_name,$user['email'],$user['phone'],$country_name_va,$user['state'],$user['city'],$user['address'],$user['zip'],$help_us,$date,$leadData->unique_key,$data_log,$api_result,$time);
        $source_file = rtrim($_SERVER['DOCUMENT_ROOT'],'/') . $pdf_link;
        $file_extension = explode('.', $source_file);
        $file_extension = $file_extension[1];
        $target_file_key = $pdf_link;
        $url = $this->upload_file_s3_new('private',$source_file, $target_file_key, $file_extension, FALSE);
        $appreance['pdf_appreance_signed'] = 1;
        $this->db->where('id', $result->lead_id);
        $this->db->update('video_leads', $appreance);

        echo json_encode($response);
        exit();
    }

    public function dropbox_search_file() {
        $unique_key = $this->input->post('unique_key');
        $files = [];

        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Video Found In Dropbox!';
        $response['error'] = '';
        $response['data'] = array();

        $client = $this->get_dropbox_client();
        if(is_string($client)){
            $response['code'] = 301;
            $response['message'] = "";
            $response['error'] = "";
            $response['url'] = $client;
            echo json_encode($response);
            exit(); 
        }

        $files = $this->search_dropbox_file($unique_key);

        if (empty($files)) {
            $response['code'] = 404;
            $response['message'] = 'Video Not Found In Dropbox!';
            $response['error'] = '';
        } else {
            $response['data'] = $files;
        }

        echo json_encode($response);
        exit();
    }

    public function get_dropbox_job_status() {
        $status = $this->input->post('status');
        $lead_id = $this->input->post('lead_id');
        
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Dropbox Job Status Fetched!';
        $response['error'] = '';
        $response['data'] = '';

        $client = $this->get_dropbox_client();
        if(is_string($client)){
            $response['code'] = 301;
            $response['message'] = "";
            $response['error'] = "";
            $response['url'] = $client;
            echo json_encode($response);
            exit(); 
        }
        
        $_status = $this->get_job_status($status);

        if (json_encode($_status) == '{}') {
            $_status = $status;
        }

        $this->db
            ->where('lead_id', $lead_id)
            ->update('raw_video', ['dropbox_status' => $_status]);

        $response['data'] = $_status;

        echo json_encode($response);
        exit();
    }

    
    public function publish_lead_to_all_feeds() {
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'categories_mrss');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Video published to MRSS feeds successfully!';
        $response['error'] = '';
        
        $param = $this->security->xss_clean($this->input->post());
        $video_id = $param['video_id'];

        $mrss_queue = $this->db->query('SELECT * FROM mrss_queue WHERE video_id = '.$video_id)->result_array();
        foreach ($mrss_queue as $row) {
            $res = $this->db->query('SELECT pub_date FROM mrss_feeds')->result_array();
            $feed_delays = db_result_to_array_map($res);

            // 2 feed_video
            $this->db->insert('feed_video', ['video_id' => $row['video_id'], 'feed_id' => $row['feed_id'], 'exclusive_to_partner' => $row['exclusive_to_partner']]);

            if ($feed_delays[$feed_id]["pub_date"] == "queue_to_mrss") {
                $db_data['publication_date'] = date('Y-m-d H:i:s');
            } else {
                $db_data['publication_date'] = $row['publication_date'];
            }

            // 3 mrss_publications
            $dbQuery = $this->db->query('
                SELECT * FROM mrss_publication
                WHERE feed_id = '.$row['feed_id'].'
                AND video_id = '.$row['video_id']
            );
            if ($dbQuery->num_rows() > 0) {
                $this->db->where('feed_id', $row['feed_id']);
                $this->db->where('video_id', $row['video_id']);
                $this->db->update('mrss_publication', $db_data);
            } else {
                $db_data['feed_id'] = $row['feed_id'];
                $db_data['video_id'] = $row['video_id'];
                $this->db->insert('mrss_publication', $db_data);
            }

            $this->db->query('DELETE FROM mrss_queue where video_id = ' . $row['video_id'] . ' AND feed_id = ' . $row['feed_id']);
        }

        // 1 videos
        $this->db->where('id', $video_id);
        $this->db->update('videos', array('mrss' => 1));

        echo json_encode($response);
        exit;
    }

}
