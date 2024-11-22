<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_Rights extends APP_Controller
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
        $this->data['active'] = 'video_rights';
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
            'assets/js/custom/datatables/datatables.uikit.min.js',
            'assets/js/custom/dropify/dist/js/dropify.min.js',
            'assets/js/pages/forms_file_input.min.js',
            'bower_components/dragula.js/dist/dragula.min.js',
            'assets/js/pages/page_scrum_board.js',
            'assets/js/jquery.charactercounter.min.js',
            'assets/js/vid_up/jquery.fileuploader.min.js',
            'assets/js/vrights.js',
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
            'list' => role_permitted_html(false, 'video_rights'),
            'signed' => role_permitted_html(false, 'video_rights', 'contract_signed'),
            'rejected' => role_permitted_html(false, 'video_rights', 'rejected'),
            'can_delete' => role_permitted_html(false, 'video_rights', 'can_delete'),
            'can_edit' => role_permitted_html(false, 'video_rights', 'can_edit'),
            'deals' => role_permitted_html(false, 'video_rights', 'deals'),


        );
        $this->load->model('Video_Right_Model', 'deal');
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

            $this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
            $this->data['editedVideos'] = $this->deal->getEditedVideos();
            $this->data['rawVideos'] = $this->deal->getRawVideos();
            switch ($params['deal_stage']) {


                case 'scrum_column_pending':
                    $view = 'pending';
                    $this->data[$view] = $this->deal->getPendingClaimedVideos($order_by_column, $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_unclaimed':
                    $view = 'unclaimed';
                    $this->data[$view] = $this->deal->getDealunclaimedVideos($order_by_column, $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_claimed':
                    $view = 'claimed';
                    $this->data[$view] = $this->deal->getDealclaimedVideos($order_by_column, $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_compilation':
                    $view = 'compilation';
                    $this->data[$view] = $this->deal->getCSompilationLeads(str_replace('vl', 'cl', $order_by_column), $sort_order, $limit, $offset, $additional_info);
                    break;
                case 'scrum_column_compilation_claimed':
                    $view = 'compilation_claimed';
                    $this->data[$view] = $this->deal->getCSompilationClaimedLeads(str_replace('vl', 'cl', $order_by_column), $sort_order, $limit, $offset, $additional_info);

                    break;
//            case 'editedVideos':
//                $data = $this->deal->getEditedVideos($limit, $offset);
//                break;
                default:
                    $view = '';
                    $this->data[$view] = array();
                    $videos_list_view = '';
            }

            $result_rows = $this->data[$view]->num_rows();

            $videos_list_view = $this->load->view('video_rights/deal_stage_views/' . $view, $this->data, true);

            echo json_encode(array('status' => 200, 'view' => $videos_list_view, 'result_rows' => $result_rows, 'max_offset_reached' => ($result_rows < $limit) ? (1) : (0)));
            exit;
        } else {
            auth();
            role_permitted(false, 'video_rights');
            if (in_array($_SERVER['REMOTE_ADDR'], ['localhost', '127.0.0.1', '::1'])) {
                $this->data['download_url'] = $this->data['url'];
            } else {
                //$this->data['download_url'] = 'https://downloads.'.$_SERVER['HTTP_HOST'].'.com/';
                $this->data['download_url'] = 'https://downloads.wooglobe.com/admin/';
            }

            //echo $this->data['download_url'];exit;
            $this->data['title'] = 'Video Rights Management';
            $this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();

            $this->data['editedVideos'] = $this->deal->getEditedVideos();

            $this->data['rawVideos'] = $this->deal->getRawVideos();


            $view_functions = array(


                'pending' => 'getPendingClaimedVideos',
                'unclaimed' => 'getDealunclaimedVideos',
                'claimed' => 'getDealclaimedVideos',
                'compilation' => 'getCSompilationLeads',
                'compilation_claimed' => 'getCSompilationClaimedLeads',

            );

            foreach ($view_functions as $view => $func) {
                $this->data[$view] = $this->deal->$func();
                $countFunc = $func . 'Count';
                $this->data['num_' . $view] = $this->deal->$countFunc();
                $this->data[$view] = $this->load->view('video_rights/deal_stage_views/' . $view, $this->data, true);


            }

            $this->data['content'] = $this->load->view('video_rights/deals', $this->data, true);
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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

    public function upload_refresh()
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $this->data['uploadVideo'] = $this->deal->getDealUploadVideos($column, $sort);
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['uploadVideo']->num_rows();
        $response['data'] = $this->load->view('video_deals/uploaded_video', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function send_reminder_email()
    {
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
            if ($leadData->status == 3) {
                // $url=root_url('login/');
                $url = $this->data['root'] . 'login';

            } else {
                $url = $this->data['root'] . 'video-contract/' . $leadData->slug;

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

    public function description_send_reminder_email()
    {
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
            $sent = $this->email($leadData->email, $leadData->first_name, 'norelpy@wooglobe.com', 'WooGlobe', $subject, $message);


        }
        if ($sent) {
            action_add($leadData->id, 0, 0, 0, 1, 'Description Reminder Sent');
            echo json_encode($response);
            exit;
        } else {
            $response['code'] = 201;
            $response['message'] = 'Email not sent!';
            echo json_encode($response);
            exit;
        }

    }

    public function move_closewon()
    {
        $lead = $this->security->xss_clean($this->input->post());
        $lead_id = $lead['id'];
        $lead_closewon = $this->deal->move_closewon($lead_id);
        if ($lead_closewon) {
            $response['code'] = 200;
            $response['message'] = 'Move to Close Won successfully!';
            $response['error'] = '';
        } else {
            $response['code'] = 201;
            $response['message'] = 'Not Move to Close Won!';
            $response['error'] = '';
        }
        echo json_encode($response);
        exit;

    }

    /*public function pending_refresh()
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
    }*/

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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $this->data['pending'] = $this->deal->getPendingClaimedVideos($column, $sort);
        $this->data['editedVideos'] = $this->deal->getEditedVideos();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['pending']->num_rows();
        $response['total'] = $this->deal->getPendingClaimedVideosCount();
        $response['data'] = $this->load->view('video_rights/pending', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function unclaimed_refresh()
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $this->data['unclaimed'] = $this->deal->getDealunclaimedVideos($column, $sort);
        $this->data['editedVideos'] = $this->deal->getEditedVideos();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->deal->getDealunclaimedVideosCount();;
        $response['data'] = $this->load->view('video_rights/unclaimed', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function claimed_refresh()
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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
        $this->data['claimed'] = $this->deal->getDealclaimedVideos($column, $sort);
        $this->data['editedVideos'] = $this->deal->getEditedVideos();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->deal->getDealclaimedVideosCount();
        $response['data'] = $this->load->view('video_rights/claimed', $this->data, true);
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
        $sort = 'ASC';
        $column = 'vl.created_at';
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

            $this->db->where('id', $request['created_reminder_lead_id']);
            $query = $this->db->get('video_leads');
            $leadData = $query->row();


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
                // $url = $this->data['root'] . 'new-login/' . $token -> waqas;
                $url = "www.wooglobe.com" . '/new-login/' . $token;

                //$url = $this->urlmaker->shorten($url);
                //echo $url;exit;
                $message = str_replace('@LINK', $url, $message);
                //$message = 'Dear '.$dbData['full_name'].'<br>You have been register successfully as client and Your account credential given below<br> <b>Email :</b> '.$dbData['email'].' <br> <b>Password : </b> '.$password.'';
                $this->email($user->email, $user->full_name, 'norelpy@wooglobe.com', 'WooGlobe', 'Reminder : ' . $subject, $message);
                $this->db->set('reminder_sent', "2");
                $this->db->where('id', $request['created_reminder_lead_id']);
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


    public function deal_detail($id)
    {

        auth();
        role_permitted(false, 'video_deals');

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
        vl.video_url,
        vl.message,
        vl.rating_point,
        vl.rating_comments,
        vl.status,
        vl.information_pending,
        vl.load_view,
        vl.uploaded_edited_videos,
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
        ad.name staff_name
        ');

        $user = $this->deal->getUserByLeadId($id);
        $video = $this->deal->getVideosByLeadId($id);

        $rawVideos = $this->deal->getRawVideoByLeadId($id);

        $email = $this->deal->getEmailByLeadId($id);
        $activity = $this->deal->getLastActivityByLeadId($id);
        if (!empty($video->facebook_id)) {
            /* $fb_video = $this->fb->getVideoById($video->facebook_id);
             if ($fb_video) {
                 $this->db->where('id', $id);
                 $this->db->update('video_leads', array('published_fb' => 0));*/
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
                                vl.video_url,
                                vl.message,
                                vl.rating_point,
                                vl.rating_comments,
                                vl.status,
                                vl.information_pending,
                                vl.uploaded_edited_videos,
                                vl.published_portal,
                                vl.published_yt,
                                vl.published_fb,
                                vl.reminder_sent,
                                vl.confidence_level,
                                vl.video_comment,
                                 vl.staff_id,vl.third_party_staff_id,
                                 ad.name staff_name
                                ');
            /* }*/
        }

        if (!empty($video->youtube_id)) {
            /* $yt_video = $this->youtube->getVideos(array($video->youtube_id));

             if (!$yt_video) {*/
            /* $this->db->where('id', $id);
             $this->db->update('video_leads', array('published_yt' => 0));*/
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
                                vl.video_url,
                                vl.message,
                                vl.rating_point,
                                vl.rating_comments,
                                vl.status,
                                vl.information_pending,
                                vl.uploaded_edited_videos,
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
                                 ad.name staff_name
                                ');
            /*  }*/
        }
        /*print_r($result);
                exit();*/

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

        if (empty($result->facebook)) {
            if (empty($result->facebook)) {
                if (empty($video->facebook_id)) {
                    $result->facebook = 'No';
                } else {
                    $result->facebook = 'Yes';
                }
            }
        }

        if (empty($result->youtube)) {
            if (empty($result->youtube)) {
                if (empty($video->youtube_id)) {
                    $result->youtube = 'No';
                } else {
                    $result->youtube = 'Yes';
                }
            }
        } else {
            $result->youtube = 'No';
        }

        if (property_exists($result, 'instagram') && empty($result->instagram)) {
            $result->instagram = 'No';
        }

        if (empty($result->exclusive_status)) {
            $result->exclusive_status = 'No';
        }

        if (empty($result->raw_video)) {
            $this->db->select('*');
            $this->db->from('raw_video');
            $this->db->where('lead_id', $result->id);
            $rv_query = $this->db->get();

            /* 			echo "<pre>";
                        print_r($result->num_rows());
                        echo "</pre>";
                        exit; */

            if ($rv_query->num_rows() > 0) {
                $result->raw_video = 'Yes';
            } else {
                $result->raw_video = 'No';
            }

        }

        if ((strpos($result->video_url, 'https//www.youtu') !== false) || (strpos($result->video_url, 'https://www.youtu') !== false) || (strpos($result->video_url, 'http://youtu') !== false) || (strpos($result->video_url, 'https://youtu') !== false)) {
            $video_id = '';

            if (strpos($result->video_url, 'watch')) {
                $yt_url_parts = explode('=', $result->video_url);
            } else if (strpos($result->video_url, '.be')) {
                $yt_url_parts = explode('/', $result->video_url);
            }

            if (isset($yt_url_parts) && !empty($yt_url_parts)) {
                $video_id = $yt_url_parts[count($yt_url_parts) - 1];
            }

            if (!empty($video_id) && !empty($result->description_updated)) {
                $api_handle = curl_init();

                curl_setopt($api_handle, CURLOPT_URL, 'https://www.googleapis.com/youtube/v3/videos?id=' . $video_id . '&key=' . $this->config->item('youtube_api_token') . '&part=snippet');
                curl_setopt($api_handle, CURLOPT_POST, FALSE);
                curl_setopt($api_handle, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($api_handle, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($api_handle, CURLOPT_RETURNTRANSFER, TRUE);

                $api_result = curl_exec($api_handle);

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

        $correspondingEmails = $this->deal->getCorrespondingEmails($email->email, $result->unique_key);
        //echo $this->db->last_query();exit;
        $allEmails = $this->deal->getAllEmails($email->email);
        $LeadRelaseLink = $this->deal->getLeadRelaseLink($result->unique_key);
        $secondsigner = $this->deal->getsecondsigner($result->unique_key);
        $appreancerelease = $this->deal->getappreancerelease($result->unique_key);
        $this->data['correspondingEmails'] = $correspondingEmails;
        $this->data['allEmails'] = $allEmails;
        $releaselinks = array();
        foreach ($LeadRelaseLink as $link) {
            $releaselinks[$link->link_type] = $link;
        }

        $this->data['LeadRelaseLink'] = $releaselinks;
        $this->data['second_signer'] = $secondsigner;
        $this->data['appreance_release'] = $appreancerelease;
        $this->data['staffs'] = $this->staff->getAllMembers('a.*', '', 0, 0, 'a.name ASC');

        //echo 1;exit;
        if (!$result) {

            redirect($_SERVER['HTTP_REFERER']);

        } else {

            $staff_id = $result->staff_id;
            $third_party_staff_id = $result->third_party_staff_id;
            if (!empty($staff_id)) {
                $party_query = $this->lead->getPartyById('admin', $staff_id);
                $party_result = $party_query->result();
                if (count($party_result)) {
                    $party_name = $party_result[0]->name;
                } else {
                    $party_name = "WooGlobe";
                }

            } elseif (!empty($third_party_staff_id)) {
                $party_query = $this->lead->getPartyById('third_party_staff', $third_party_staff_id);
                $party_result = $party_query->result();
                if (count($party_result)) {
                    $party_name = $party_result[0]->name;
                } else {
                    $party_name = "WooGlobe";
                }
            } else {

                $party_name = 'WooGlobe';
            }

            $query = $this->db->get('countries');
            $countries = $query->result();
            $video_query = $this->db->query('SELECT *  FROM `videos` WHERE `lead_id` = "' . $id . '"');
            $video_result = $video_query->result();
            if (isset($video_result[0])) {
                $this->data['question_video_taken'] = $video_result[0]->question_video_taken;
                $this->data['question_when_video_taken'] = $video_result[0]->question_when_video_taken;
            }
            $unique_key = $result->unique_key;
            $signimgpath = root_url() . 'uploads/' . $unique_key . '/documents/' . $unique_key . '.png';
            $this->data['signature'] = $signimgpath;
            $this->data['lead_id'] = $id;
            $this->data['rawVideos'] = $rawVideos;
            $this->data['dealData'] = $result;
            $this->data['userData'] = $user;
            $this->data['countriesData'] = $countries;
            $this->data['party_name'] = $party_name;

            /* MRSS categories : S */
            $this->data['mrss_default_values'] = [];
            $this->data['allow_mrss'] = 'no';
            $this->data['mrss_partners'] = $this->mrss->getMrssPartners();

            $this->data['general_categories'] = $this->mrss->getGeneralCategories('id, title');
            if (isset($video->id)) {
                $this->data['mrss_feed_data'] = $this->mrss->getFeedDataByVideoId($video->id);
                $this->data['non_exclusive_partner_data'] = $this->mrss->nonExclusivePartnerdataByVideoId($video->id);

                $partnership_type = '';
                $partners_info = $this->mrss->getExclusivePartnerByVideoId($video->id); // get single partners for video

                if (!empty($partners_info)) { // if there is single partner then type = single partnership (1) for default selection in view
                    $partnership_type = '1';
                } else {
                    // if no single partnership exists in above case then check for multiple partnership
                    $partners_info = $this->mrss->getNonExclusivePartnersByVideoId($video->id);
                    if (!empty($partners_info)) {
                        $partnership_type = '2'; // multiple partnership
                    }
                }

                $this->data['video_selected_categories'] = $this->mrss->getVideoSelectedCategoriesByVideoId($video->id, 'mrss_feeds.id, title');
                $this->data['partners_info'] = $partners_info;
            }


            if (!empty($partners_info) || !empty($this->data['video_selected_categories'])) {
                $this->data['allow_mrss'] = 'yes';
            } else {
                $partnership_type = '';
            }

            $this->data['partnership_type'] = $partnership_type; // multiple ;

            if ($partnership_type == 1 || $partnership_type == 2) {
                $this->data['dealData']->exclusive_status = 'Yes';
            } else {
                $this->data['dealData']->exclusive_status = 'No';
            }

            /* MRSS categories : E */
            $currencies = $this->lead->getCurencies();
            $this->data['videoData'] = $video;
            $this->data['activity'] = $activity;
            $this->data['currencies'] = $currencies;
            $this->data['emailHistory'] = $this->deal->getDealEmailNotifictionHIstory($id);
            if (isset($video->id)) {
                $this->data['editedVideo'] = $this->deal->getEditedVideoById($video->id);
            }

            $userData = $this->deal->getUserByLeadId($id);
            $currency_id = 0;
            if (!empty($userData) && isset($userData->currency_id) && !empty($userData->currency_id)) {
                $currency_id = $userData->currency_id;
            }
            $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';
            $country_name = "";

            $data_log_query = $this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="' . $id . '"');
            if ($data_log_query->num_rows() > 0) {
                $logs_query = $data_log_query->row();


// Initialize CURL:
                $ch = curl_init('http://api.ipstack.com/' . $logs_query->user_ip_address . '?access_key=' . $access_key . '');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
                $json = curl_exec($ch);
                curl_close($ch);

// Decode JSON response:
                $api_result = json_decode($json, true);

                //Generate Pdf
                $country_name_va = '';
                if (!empty($api_result) && isset($api_result['country_name'])) {
                    $country_name = $api_result['country_name'];
                }
            }
            $nextPayment = $this->deal->getNextPayment($result->client_id);
            $next = $nextPayment->row();
            $this->data['next_payment'] = $next->next_payment;
            $paid = $this->deal->paid($result->client_id);
            $paid = $paid->row();
            $this->data['paid'] = $paid->paid;
            $this->data['currency'] = getUserCurrencySymbolById($result->client_id);

            $this->data['earning_type'] = $this->earning_type->getAllEarningTypesActive('et.id,et.earning_type', '', 0, 0, 'et.earning_type ASC');
            $this->data['country_name'] = $country_name;
            $this->data['currency_id'] = $currency_id;
            $this->data['sources'] = $this->source->getAllSourcesActive('ss.id,ss.sources', '', 0, 0, 'ss.sources ASC');
            $this->data['partners'] = $this->user->getAllUsersActive(2, 'u.id,u.full_name,u.email', '', 0, 0, 'u.full_name ASC');
            $this->data['commonJs'] = array_merge($this->data['commonJs'], array('assets/js/vid_earnings17.js'));

            $this->data['content'] = $this->load->view('video_rights/deal_detail', $this->data, true);
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


    public function publish_youtube()
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
        $response['message'] = 'Video Published on Youtube Successfully!';
        $response['error'] = '';
        $request = $this->security->xss_clean($this->input->post());

        $dbData['publish_type'] = 'YouTube';
        if (isset($request['publish_now_youtube']) and $request['publish_now_youtube'] == '1') {
            $dbData['publish_now'] = 1;
            $dbData['published'] = 1;

        } else {
            $dbData['publish_now'] = 0;
            $dbData['publish_datetime'] = $request['youtube_publish_date'] . ' ' . $request['youtube_publish_time'] . ':00';
        }
        $dbData['video_title'] = $request['youtube_publish_title'];
        $dbData['video_description'] = $request['youtube_publish_description'] . '

►SUBSCRIBE for more Awesome Videos: https://goo.gl/uDVc4n

-----------------------
Copyright - #WooGlobe.

We bring you the most trending internet videos!

For licensing and to use this video, please email licensing(at)Wooglobe(dot)com.

Video ID: ' . $request['wgid'] . '

Twitter : https://twitter.com/WooGlobe
Facebook : https://fb.com/Wooglobe
Instagram : https://www.instagram.com/WooGlobe/';
        $dbData['video_tags'] = $request['youtube_publish_tags'];
        $dbData['youtube_channel'] = $request['youtube_channel'];
        $dbData['youtube_category'] = $request['youtube_category'];
        if (isset($request['youtube_publish_now'])) {
            $dbData['publish_now'] = $request['youtube_publish_time'];
        }
        $dbData['youtube_publish_status'] = $request['youtube_publish_status'];
        $publishData = $this->deal->getPublishData($request['video_id'], 'YouTube');
        $video = $this->video->getVideoById($request['video_id']);


        if ($publishData) {
            $this->db->where('video_id', $request['video_id']);
            $this->db->where('publish_type', 'YouTube');
            $this->db->update('video_publishing_scheduling', $dbData);
        } else {
            $dbData['video_id'] = $request['video_id'];
            $this->db->insert('video_publishing_scheduling', $dbData);

        }

        if ($request['publish_now_youtube'] == 0) {

            $publishData = $this->deal->getPublishData($request['video_id'], 'YouTube');

            if ($publishData) {
                $video_data = $this->deal->getPortalVideo($request['video_id']);

                $result = $this->youtube->publishVideo($video_data, $publishData);

                if ($result['error'] == false) {

                    $dbData1['youtube_id'] = $result['id'];
                    $this->db->where('id', $request['video_id']);
                    $this->db->update('videos', $dbData1);
                    $dbData2['published'] = 1;
                    $this->db->where('video_id', $request['video_id']);
                    $this->db->where('publish_type', 'YouTube');
                    $this->db->update('video_publishing_scheduling', $dbData2);
                    $dbData3['youtube_repub'] = 0;
                    $this->db->where('id', $request['video_id']);
                    $this->db->update('videos', $dbData3);
                    if ($video_data) {
                        $status = $this->deal->updateYoutubeStatus($video->lead_id);
                        $this->deal->dealStatusChangeFromDistributeToWon($video->lead_id);
                        @unlink($video_data->yt_url); // delete youtube video from server after upload

                        $WGA = $this->db->select('unique_key')->from('video_leads')->join('videos', 'videos.lead_id = video_leads.id')->where('videos.id', $request['video_id'])->get()->row()->unique_key;

                        if (isset($WGA->unique_key) && !empty($WGA->unique_key)) {
                            $WGA_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $WGA->unique_key;

                            $yt_thumbnails_path = $WGA_dir . '/edited_videos/youtube/thumbnail';

                            if (is_dir($yt_thumbnails_path)) {
                                $thumbnails_list = scandir($yt_thumbnails_path);

                                foreach ($thumbnails_list as $thumbnail) {
                                    @unlink($yt_thumbnails_path . '/' . $thumbnail); // delete related thumbnails from server after upload
                                }
                            }
                        }

                    }
                    $response['data'] = $result;

                } else {
                    $response['code'] = 206;
                    $response['message'] = $result['msg'];
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
                $unique_key_array = '';
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
                    'privacy' => $privacy,
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
                            $WGA_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $WGA->unique_key;

                            $yt_thumbnails_path = $WGA_dir . '/edited_videos/facebook/thumbnail';

                            if (is_dir($yt_thumbnails_path)) {
                                $thumbnails_list = scandir($yt_thumbnails_path);

                                foreach ($thumbnails_list as $thumbnail) {
                                    @unlink($yt_thumbnails_path . '/' . $thumbnail); // delete related thumbnails from server after upload
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

            $sent = $dbData['sent'];
            unset($dbData['lead_id']);
            unset($dbData['sent']);


            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');

            $this->db->where('id', $lead_id);
            $this->db->update('video_leads', $dbData);

            $leadData = $this->email->getLeadById($lead_id, 'vl.*');
            action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Revenue updated');

            if ($sent == 1) {
                $template = $this->app->getEmailTemplateByCode('revenue_update_email');

                if ($template) {
                    $subject = $template->subject . '-' . $leadData->unique_key;
                    $emailDbData['video_lead_id'] = $lead_id;
                    $emailDbData['email_sent'] = 1;
                    $emailDbData['mail_sent_time'] = date('Y-m-d H:i:s');
                    $this->db->insert('intro_email', $emailDbData);
                    $leadVideoUrl = $leadData->video_url;
                    $ids = array(
                        'video_leads' => $lead_id
                    );

                    $url = $this->data['root'] . 'video-contract/' . $leadData->slug;
                    //$url = '<p>If you are interested to make a contract with us then <a href="'.$url.'">click here</a></p>';
                    $message = dynStr($template->message, $ids);
                    $message = str_replace('@VIDEO_LEADS.URL', "<a href='" . $leadVideoUrl . "'>$leadVideoUrl</a>", $message);

                    $message = str_replace('@LINK', $url, $message);
                    $message = str_replace('@REVENUE', $dbData['revenue_share'], $message);
                    $leftover = 100 - $dbData['revenue_share'];
                    $message = str_replace('@LEFT', $leftover, $message);
                    $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
                    $notification['send_datime'] = date('Y-m-d H:i:s');
                    $notification['lead_id'] = $lead_id;
                    $notification['email_template_id'] = $template->id;
                    $notification['email_title'] = $template->title;
                    $notification['ids'] = json_encode($ids);
                    //$this->db->insert('email_notification_history',$notification);
                }
                action_add($lead_id, 0, 0, $this->sess->userdata('adminId'), 1, 'New contract sent');
            }

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
        if ($publishData) {
            $data['publish_title'] = $publishData->video_title;
            $data['publish_description'] = $publishData->video_title;
            $data['publish_now'] = $publishData->publish_now;
            $data['yt_channel'] = $publishData->youtube_channel;
            $data['publish_tags'] = $publishData->video_tags;
            $data['channel'] = $publishData->video_tags;
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
            } else {
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

    public function decide_download_category()
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

                    $cmd = 'zip ' . $dir_path . '/' . $video_id . '.zip ' . implode(' ', $file_list) . ' && php ' . base_url() . 'Pending_downloads/';

                    //echo $cmd = 'zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/testfile.zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/wga197205_1577960559.mp4';
                    //$cmd = 'zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/testfile2.zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/wga197205_1577960559.mp4';

                    $admin_id = $this->sess->userdata('adminId');

                    //$cmd = 'zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/testfile2.zip /var/www/html/wooglobe/uploads/WGA197205/raw_videos/wga325108_1574083937.mp4 && curl --url '.base_url().'Pending_downloads/send_pending_download_links/'.$video_id.'/'.$admin_id.' --insecure';
                    //$cmd = 'zip /var/www/html/wooglobe/'.$dir_path.'/raw_videos/'.$video_id.'.zip '.implode(' ', $file_list).'&& curl --url '.base_url().'Pending_downloads/send_pending_download_links/'.$video_id.'/'.$admin_id.' --insecure >/dev/null 2> /dev/null &';

                }

                if (!empty($file_list)) {
                    //$cmd = 'zip /var/www/html/wooglobe/'.$dir_path.'/raw_videos/'.$video_id.'.zip '.implode(' ', $file_list).'&& curl --url '.base_url().'Pending_downloads/send_pending_download_links/'.$video_id.'/'.$admin_id.' --insecure';
                    $cmd = 'zip /var/www/html/' . $dir_path . '/raw_videos/' . $video_id . '.zip ' . implode(' ', $file_list) . '&& curl --url ' . base_url() . 'send-zip-email/' . $video_id . '/' . $admin_id . ' --insecure';


                    echo json_encode(['type' => 'email', 'cmd' => $cmd]);
                    exit;
                }
            } else {
                echo json_encode(['type' => 'direct']);
                exit;
            }
            //$this->zip->download($video_id . '.zip');

        } else {
            echo json_encode(['type' => 'none']);
            exit;
        }

    }

    public function ajax_create_zip_request()
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


            curl_setopt($gen_file, CURLOPT_URL, base_url() . 'Pending_downloads/run_genfile_cmd');
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
        $response['message'] = 'Video lead deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $dbData = $this->security->xss_clean($this->input->post());
        $leadData = $this->email->getLeadById($dbData['lead_id'], 'vl.*');
        if ($leadData) {
            $templateId = $this->deal->getTemplateId($dbData['lead_id']);
            $lead_id = $dbData['lead_id'];
            $template = $this->app->getEmailTemplateByCode('contract_cancel');
            if (isset($templateId->ids)) {
                $ids = json_decode($templateId->ids, true);
            }
            $subject = "WooGlobe Contract Cancel";
            $cancel_message = $dbData['cancel_comments'];
            $name = $leadData->first_name;
            $message = "Hi @NAME

 

Good day to you.  

 

Kindly note that your agreement with WooGlobe has to be canceled beacuse @CANCELMESSAGE

 

Thanks for your understanding.

 

Best regards,

The WooGlobe Team";
            $message = str_replace('@NAME', $name, $message);
            $message = str_replace('@CANCELMESSAGE', $cancel_message, $message);
            $sent = $this->email($leadData->email, $leadData->first_name, 'noreply@wooglobe.com', 'WooGlobe', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
            action_add($dbData['lead_id'], 0, 0, $this->sess->userdata('adminId'), 1, 'Deal Deleted');

        }
        action_add($dbData['lead_id'], 0, 0, $this->sess->userdata('adminId'), 1, 'Deal Deleted');

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
        unset($dbData['lead_id']);
        unset($dbData['cancel_comments']);
        $this->db->update('video_leads', $dbData1);


        $result = $this->video->getVideoByLeadId($lead_id, 'videos');

        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->sess->userdata('adminId');
        $dbData['deleted_at'] = date('Y-m-d H:i:s');
        $dbData['deleted_by'] = $this->sess->userdata('adminId');
        $dbData['deleted'] = 1;
        $dbData['delete_contract'];
        $this->db->where('lead_id', $lead_id);
        $this->db->update('videos', $dbData);


        $result_raw = $this->video->getVideoByLeadId($lead_id, 'raw_video');

        if ($result_raw) {
            if (isset($dbData['delete_contract'])) {
                $target_file_key = substr($result_raw->s3_url, 44);
                $uid = $leadData->unique_key;
                $allrawFiles = scandir("./../uploads/$uid/raw_videos");
                $alldocumentFiles = scandir("./../uploads/$uid/documents");

                foreach ($allrawFiles as $file) {
                    @unlink("./../uploads/$uid/raw_videos/" . $file);
                }
                foreach ($alldocumentFiles as $document) {
                    @unlink("./../uploads/$uid/documents/" . $document);
                }
                $deletes3file = $this->delete_file($target_file_key);
            }
        }
        $this->db->where('lead_id', $lead_id);
        $this->db->delete('raw_video');
        $videodata = $this->video->getVideoByLeadId($lead_id, 'videos');
        if ($videodata) {
            $videoid = $videodata->id;

            if ($videoid) {
                $this->db->where('video_id', $videoid);
                $this->db->delete('edited_video');
            }
        }
        echo json_encode($response);
        exit;

    }

    function deleteDirectory($dir)
    {
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
        $uid = $leadData->unique_key;
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
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Revenue share not updated');
        }


    }

    public function update_title()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatetitle = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updatetitle($id, $updatetitle);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead title updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead title not updated');
        }
    }

    public function update_des()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatedes = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updatedes($id, $updatedes);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead des updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead des not updated');
        }
    }

    public function update_tags()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        $updatetags = $this->security->xss_clean($this->input->post('content'));
        $result = $this->deal->updatetags($id, $updatetags);
        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead tags updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead des not updated');
        }
    }

    public function update_message()
    {
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
        } else {
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
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead rating not updated');
        }
    }

    public function update_ratings_comment()
    {
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
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Lead rating comment not updated');
        }
    }

    public function update_facebook()
    {
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
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Facebook Data not updated');
        }


    }

    public function update_youtube()
    {
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
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Youtube Data not updated');
        }


    }

    public function update_confidence()
    {
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
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Youtube Data not updated');
        }
    }

    public function update_video_comment()
    {
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
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Youtube Data not updated');
        }
    }

    public function update_raws3()
    {
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
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data not updated');
        }


    }

    public function update_docs3()
    {
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
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data not updated');
        }


    }

    public function update_editeds3()
    {
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
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Edited Video s3 Data not updated');
        }


    }

    public function update_thumbs3()
    {
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
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Edited Thumb s3 Data not updated');
        }


    }

    public function update_deal_mrss()
    {
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

    public function update_q1()
    {
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
        $id = $this->security->xss_clean($this->input->post('id'));
        $updateq2 = $this->security->xss_clean($this->input->post('content'));
        $updateq2 = preg_replace('/\s+/', '', $updateq2);
        $result = $this->deal->updateq2($id, $updateq2);

        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['video_id'] = $result;
            $response['error'] = '';
            echo json_encode($response);
            exit;
        } else {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data not updated');
        }


    }

    public function update_q3()
    {
        $id = $this->security->xss_clean($this->input->post('id'));
        $updateq3 = $this->security->xss_clean($this->input->post('content'));
        $updateq3 = preg_replace('/\s+/', '', $updateq3);
        $result = $this->deal->updateq3($id, $updateq3);

        if ($result) {
            action_add($id, 0, 0, $this->sess->userdata('adminId'), 1, 'Raw s3 Data updated');
            $response['code'] = 200;
            $response['message'] = 'Record Updated';
            $response['video_id'] = $result;
            $response['error'] = '';
            echo json_encode($response);
            exit;
        } else {
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
        $file_pointer = './../uploads/' . $uid . '/raw_videos/';

        if (file_exists($file_pointer)) {
            include('./app/third_party/class.fileuploader.php');
            $FileUploader = new FileUploader('file', array(
                'title' => strtolower($uid) . '_' . time(),
                'uploadDir' => './../uploads/' . $uid . '/raw_videos/'
            ));
        } else {
            $root_path = './../uploads/' . $uid;
            $raw_videos = './../uploads/' . $uid . '/raw_videos';
            $edited_videos = './../uploads/' . $uid . '/edited_videos';
            $edited_yt = './../uploads/' . $uid . '/edited_videos/youtube';
            $edited_yt_thumb = './uploads/' . $uid . '/edited_videos/youtube/thumbnail';
            $edited_fb = './../uploads/' . $uid . '/edited_videos/facebook';
            $edited_fb_thumb = './../uploads/' . $uid . '/edited_videos/facebook/thumbnail';
            $edited_mrsss = './../uploads/' . $uid . '/edited_videos/mrss';
            $edited_mrss_thumb = './../uploads/' . $uid . '/edited_videos/mrss/thumbnail';
            $documents = './../uploads/' . $uid . '/documents';
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
            include('./app/third_party/class.fileuploader.php');
            $FileUploader = new FileUploader('file', array(
                'title' => strtolower($uid) . '_' . time(),
                'uploadDir' => './uploads/' . $uid . '/raw_videos/'
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

            $fields = array('question_video_taken', 'question_when_video_taken', 'question_when_video_taken');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {
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

            $fields = array('email', 'address', 'country_id', 'state_id', 'city_id', 'zip_code');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {
            $db_data = $this->security->xss_clean($this->input->post());
            $client_id = $db_data['id'];
            action_add($client_id, 0, 0, $this->sess->userdata('adminId'), 1, 'Admin Upload Story Information Submission');
            $data = array(
                'load_view' => 4,
                'information_pending' => 1
            );
            $lead_id = $db_data['lead_id'];
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

        } else {
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

        } else {
            $db_data = $this->security->xss_clean($this->input->post());
            $uid = $db_data['uid'];
            $simple_video = 0;
            if ($db_data['video_type'] == 'simple') {
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

            $fields = array('days_expire', 'uid');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {
            $db_data = $this->security->xss_clean($this->input->post());
            $data_expire = array(
                'days_interval	' => $db_data['days_expire'],
                'unique_key' => $db_data['uid'],
                'link_type' => $db_data['type'],
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

        } else {
            $db_data = $this->security->xss_clean($this->input->post());
            $data_id = $db_data['id'];
            $update_date['created_at'] = date('Y-m-d H:i:s');
            $this->db->where('id', $data_id);
            $this->db->update('release_link', $update_date);
        }

        echo json_encode($response);
        exit;

    }

    public function second_signer_appearance_release()
    {

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
            $leadid = $this->db->query('SELECT * FROM `second_signer` WHERE `id` ="' . $id . '"');
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
            $pdf_link = '/uploads/' . $uid . '/appreance/' . $uid . '_signed_' . $time . '.pdf';

            //Insert  data
            $insert_appreance_lead = array(
                'uid' => $uid,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'countary_code' => $country,
                'phone' => $phone,
                'country_id' => $country,
                'state_id' => $state,
                'city_id' => $city,
                'address' => $address,
                'address2' => $address2,
                'zip_code' => $zip,
                'help_us' => $help_us,
                'pdf_link' => $pdf_link,
                'created_at' => $dateadded,
                'img' => $signature
            );
            $this->db->insert('appreance_release', $insert_appreance_lead);

        }
        $first_name = $first_name;
        $last_name = $last_name;
        $full_adress = $address . ' ' . $address2;
        $country_name_va = $country;
        $unique_key = $uid;
        $date = $dateadded;


        $leadid = $this->db->query('SELECT id FROM `video_leads` WHERE `unique_key` ="' . $uid . '"');
        $leadid = $leadid->row();
        $lead_id = $leadid->id;
        $leadquery = $this->db->query('SELECT *
		    FROM video_leads vl
		    WHERE vl.id ="' . $lead_id . '"');
        $leadData = $leadquery->row();
        $data_log_query = $this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="' . $lead_id . '"');
        $logs_query = $data_log_query->result();
        $sent_log_query = $this->db->query('SELECT lead_rated_date  FROM `lead_action_dates` WHERE `lead_id` = "' . $lead_id . '"');
        $sent_log_result = $sent_log_query->result();
        $video_query = $this->db->query('SELECT *  FROM `videos` WHERE `lead_id` = "' . $lead_id . '"');
        $video_result = $video_query->result();
        $data_log['user_browser'] = '';
        $data_log['user_ip_address'] = '';
        $data_log['contract_signed_datetime'] = '';
        $data_log['contract_view_datetime'] = '';
        if (isset($logs_query[0])) {
            $data_log['user_browser'] = $logs_query[0]->user_browser;
            $data_log['user_ip_address'] = $logs_query[0]->user_ip_address;
            $data_log['contract_signed_datetime'] = $logs_query[0]->contract_signed_datetime;
            $data_log['contract_view_datetime'] = $logs_query[0]->contract_view_datetime;
        }
        if (isset($sent_log_result[0])) {
            $data_log['lead_rated_date'] = $sent_log_result[0]->lead_rated_date;
        }

        $data1['video_url'] = $leadData->video_url;
        $url_name = $data1['video_url'];

        $access_key = '1d092358b81f0bfd0755bb3b19ac3bbf';


// Initialize CURL:
        $ch = curl_init('http://api.ipstack.com/' . $data_log['user_ip_address'] . '?access_key=' . $access_key . '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Store the data:
        $json = curl_exec($ch);
        curl_close($ch);
        $api_result = json_decode($json, true);

        $appreance_pdf = $this->appreance_signed_pdf($url_name, $first_name, $last_name, $email, $phone, $country, $state, $city, $full_adress, $zip, $signature, $help_us, $date, $unique_key, $data_log, $api_result, $time);
        $source_file = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . $pdf_link;
        $file_extension = explode('.', $source_file);
        $file_extension = $file_extension[1];
        $target_file_key = $pdf_link;
        $url = $this->upload_file_s3_new('private', $source_file, $target_file_key, $file_extension, FALSE);
        header("Content-type: application/json");
        echo json_encode($response);
        exit;


    }

    public function appreance_signed_pdf($url_name, $first_name, $last_name, $email, $phone, $country, $state, $city, $address, $zip, $signature, $help_us, $date, $unique_key, $data_log, $api_result, $time)
    {

        $this->base30_to_jpeg($signature, root_path() . '/uploads/' . $unique_key . '/appreance/' . $unique_key . '_' . $time . '.png');
        $signimgpath = root_path() . '/uploads/' . $unique_key . '/appreance/' . $unique_key . '_' . $time . '.png';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //$certificate = 'file://'.root_path().'/admin/app/libraries/TCPDF-master/examples/data/cert/tcpdf.crt';
        $certificate = 'file://' . root_path() . '/admin/app/libraries/TCPDF-master/examples/data/cert/signature.crt';

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
        $pdf->AddPage('L', '', 'A4');

        $html = '<img src="' . root_path() . '/assets/img/wooglobe-con-1.png" width="1000px" height="600px">';
        $pdf->writeHTML($html);
        /*$fontname = TCPDF_FONTS::addTTFfont('calibri.ttf', 'TrueTypeUnicode', '', 96);
        $pdf->SetFont($fontname, '', 12);*/
// add a page
        $pdf->AddPage('P', '', 'A4');
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

        $pdf->AddPage('P', '', 'A4');

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
                   
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Video URL <font color="red">*</font> :' . $url_name . '</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;First Name  <font color="red">*</font> :' . $first_name . '</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Last Name <font color="red">*</font> :' . $last_name . '</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Email <font color="red">*</font> :' . $email . '</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Phone <font color="red">*</font> :' . $phone . '</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Address <font color="red">*</font> :' . $address . '</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;City <font color="red">*</font> :' . $city . '</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;State <font color="red">*</font> :' . $state . '</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Postal Code <font color="red">*</font> :' . $zip . '</p>
                        <p>&nbsp;&nbsp;&nbsp;&nbsp;Country <font color="red">*</font> :' . $country . '</p>
                        <p >&nbsp;&nbsp;&nbsp;&nbsp;Help us find you in the video <font color="red">*</font> : ' . $help_us . '</p>

                </div>
               
                    <p style="height: 40px;"> &nbsp;</p>
                    <div>
                    <table>
                        <tr>
                            <td>' . $first_name . ' ' . $last_name . '</td>
                            <td></td>
                            <td>' . $date . '</td>
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
        $pdf->AddPage('P', '', 'A4');
        $addres_html = '';
        $location_country = '';
        $location_region_code = '';
        $location_city = '';
        $location_continent_name = '';
        $location_zip = '';
        $location_latitude = '';
        $location_longitude = '';
        if (isset($api_result['country_name'])) {
            $location_country = $api_result['country_name'];
        }
        if (isset($api_result['region_code'])) {
            $location_region_code = $api_result['region_code'];
        }
        if (isset($api_result['city'])) {
            $location_city = $api_result['city'];
        }
        if (isset($api_result['continent_name'])) {
            $location_continent_name = $api_result['continent_name'];
        }
        if (isset($api_result['zip'])) {
            $location_zip = $api_result['zip'];
        }
        if (isset($api_result['latitude'])) {
            $location_latitude = $api_result['latitude'];
        }
        if (isset($api_result['longitude'])) {
            $location_longitude = $api_result['longitude'];
            $addres_html = ' <p>Continent : ' . $location_continent_name . '</p>
        <p>Country : ' . $location_country . '</p>';
        }

        $html = '<p style="text-align: left"><img src="' . root_path() . '/admin/assets/assets/img/logo.png" width="80px" height="80px"></p>
       <span style="font-size: 30px">Signing Log</span>
        <p>Document ID : ' . $unique_key . '</p>
        <p>----------------------------------------------------------------------------------------------------------------------------</p>
        <p>WooGlobe</p>
        <p>Document Name:   ' . $unique_key . '_signed.pdf</p>
        <p>Sent On:  ' . $data_log["lead_rated_date"] . ' GMT</p>
        <p>IP Address: ' . $data_log["user_ip_address"] . '</p>
        <p>User Agent: ' . $data_log["user_browser"] . '</p>
        <p>Contract Signed Date And Time:  ' . $data_log["contract_signed_datetime"] . ' GMT</p>
        <p>Contract Created Date And Time:  ' . date('Y-m-d H:i:s') . 'GMT</p>
        ' . $addres_html . '<br>
        ';
        $pdf->writeHTML($html);
// reset pointer to the last page
        $pdf->lastPage();
//Close and output PDF document
        $output_file = root_path() . '/uploads/' . $unique_key . '/appreance/' . $unique_key . '_signed_' . $time . '.pdf';
        if (!file_exists(root_path() . '/uploads/' . $unique_key . '/appreance')) {
            mkdir(root_path() . '/uploads/' . $unique_key . '/appreance', 0777, true);
            $output_file = root_path() . '/uploads/' . $unique_key . '/appreance/' . $unique_key . '_signed_' . $time . '.pdf';
        }

        $pdf->Output($output_file, 'F');

    }

    public function base30_to_jpeg($base30_string, $output_file)
    {
        require_once(root_path() . 'admin/app/libraries/jSignature_Tools_Base30.php');
        $data = str_replace('image/jsignature;base30,', '', $base30_string);
        $converter = new jSignature_Tools_Base30 ();
        $raw = $converter->Base64ToNative($data);
// Calculate dimensions
        $width = 0;
        $height = 0;
        foreach ($raw as $line) {
            if (max($line ['x']) > $width)
                $width = max($line ['x']);
            if (max($line ['y']) > $height)
                $height = max($line ['y']);
        }

// Create an image
        $im = imagecreatetruecolor($width + 20, $height + 20);

// Save transparency for PNG
        imagesavealpha($im, true);
// Fill background with transparency
        $trans_colour = imagecolorallocatealpha($im, 255, 255, 255, 127);
        imagefill($im, 0, 0, $trans_colour);
// Set pen thickness
        imagesetthickness($im, 2);
// Set pen color to black
        $black = imagecolorallocate($im, 0, 0, 0);
// Loop through array pairs from each signature word
        for ($i = 0; $i < count($raw); $i++) {
            // Loop through each pair in a word
            for ($j = 0; $j < count($raw [$i] ['x']); $j++) {
                // Make sure we are not on the last coordinate in the array
                if (!isset ($raw [$i] ['x'] [$j]))
                    break;
                if (!isset ($raw [$i] ['x'] [$j + 1]))
                    // Draw the dot for the coordinate
                    imagesetpixel($im, $raw [$i] ['x'] [$j], $raw [$i] ['y'] [$j], $black);
                else
                    // Draw the line for the coordinate pair
                    imageline($im, $raw [$i] ['x'] [$j], $raw [$i] ['y'] [$j], $raw [$i] ['x'] [$j + 1], $raw [$i] ['y'] [$j + 1], $black);
            }
        }

// Check if the image exists
        if (!file_exists(dirname($output_file))) {
            mkdir(dirname($output_file));
        }

// Create Image
        $ifp = fopen($output_file, "wb");
        imagepng($im, $output_file);
        fclose($ifp);
        imagedestroy($im);

        return $output_file;
    }

    public function soical_video_delete()
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
        $response['message'] = 'Social Video Deleted';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->input->post('video_id');
        $query = "SELECT * FROM `social_videos` WHERE video_id = " . $id;
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            $row = $result->row();
            $this->delete_file(str_replace('https://wooglobe.s3-us-west-2.amazonaws.com/', '', $row->s3_url));
            $this->db->where('video_id', $id);
            $this->db->delete('social_videos');

        }
        echo json_encode($response);
        exit;
    }

    public function rights_claimed()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_rights', 'Claimed');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();
        $this->load->library('sheet');
        $response['code'] = 200;
        $response['message'] = 'Video Claimed Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $dbData = $this->security->xss_clean($this->input->post());
        $videoid = $dbData['video_id'];
        $video = $this->video->getVideoById($videoid);
        $dealData = $this->video->getLeadDetailByVideoId($videoid);
        $url = $video->url;
        if ($dbData['claimed_source'] == 'Raw') {
            $url = '';
            $rawVideos = $this->d->getRawVideosByVideoId($videoid);
            foreach ($rawVideos->result() as $v) {
                $url .= $v->s3_url . ' , ';
            }
            $url = rtrim($url, ' , ');
        } else {
            $this->sheet->addRowToSpreadsheet(array($video->youtube_id, 'https://www.youtube.com/watch?v=' . $video->youtube_id, $video->video_title, '', $dealData->unique_key));
        }

        //$this->sheet->addRowToSpreadsheet(array(date('Y-m-d'),'','','https://www.youtube.com/watch?v='.$video->youtube_id,'',$url),241318973);
        unset($dbData['video_id']);
        $dbData['claimed'] = 1;
        $this->db->where('id', $videoid);
        $this->db->update('videos', $dbData);

        echo json_encode($response);
        exit;

    }

    public function compilations_urls()
    {
        auth();
        role_permitted(false, 'video_rights', 'compilations_urls');
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
            while (in_array("WGA" . $rand, $unique_group_key_list)) {
                $rand = random_string('numeric', 8);
            }
            $unique_group_key = "WGA" . $rand;

            foreach ($urls as $url) {
                $unique_key_list = array_column($this->db->distinct()->select('wg_id')->get('compilation_leads')->result_array(), 'wg_id');
                $this->load->helper('string');
                $this->load->library('youtube');
                $random = random_string('numeric', 6);
                while (in_array("WGC" . $random, $unique_key_list)) {
                    $random = random_string('numeric', 6);
                }
                $unique_key = "WGC" . $random;
                $dbData['lead_group_id'] = $unique_group_key;
                $dbData['wg_id'] = $unique_key;
                $dbData['yt_url'] = $url;
                $video_id = extrect_video_id($url);
                $dbData['yt_id'] = $video_id;
                $views = 0;
                $category = '';
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
                $dbData['created_at'] = date('Y-m-d H:i:s');
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['created_by'] = $this->sess->userdata('adminId');
                $dbData['updated_by'] = $this->sess->userdata('adminId');
                $this->db->insert('compilation_leads', $dbData);
            }
            redirect('compilations_urls_info/'.$unique_group_key);
        }
    }

    public function compilations_urls_info($group_id, $id = null)
    {
        auth();
        role_permitted(false, 'video_rights', 'compilations_urls_info');

        $this->data['title'] = 'Compilations URLs Info';
        $this->data['leads'] = $this->deal->getCompilationLeadsByGroupId($group_id, $id);
        $this->data['staffs'] = $this->staff->getAllMembers('a.*', '', 0, 0, 'a.name ASC');

        $this->data['content'] = $this->load->view('video_rights/url_info', $this->data, true);
        $this->load->view('common_files/template', $this->data);

    }

    public function compilations_urls_info_save()
    {
        auth();
        role_permitted(false, 'video_rights', 'compilations_urls_info');

        $leads = $this->input->post('leads');
        foreach ($leads as $lead) {
            $lead['updated_at'] = date('Y-m-d H:i:s');
            $lead['updated_by'] = $this->sess->userdata('adminId');
            $this->db->where('lead_group_id', $lead['lead_group_id']);
            $this->db->where('id', $lead['id']);
            $this->db->update('compilation_leads', $lead);
        }
        $this->sess->set_flashdata('msg', 'Information Updated successfully!');
        redirect('video_rights');

    }

    public function compilations_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_rights');
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
        $this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['compilation'] = $this->deal->getCSompilationLeads($column, $sort);
        $this->data['editedVideos'] = $this->deal->getEditedVideos();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['compilation']->num_rows();
        $response['total'] = $this->deal->getCSompilationLeadsCount();
        $response['data'] = $this->load->view('video_rights/compilation', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }

    public function compilations_claimed_refresh()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_rights');
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
        $this->data['activityTime'] = $this->deal->getMaxActivityTimeForLead();
        $this->data['compilation_claimed'] = $this->deal->getCSompilationClaimedLeads($column, $sort);
        $this->data['editedVideos'] = $this->deal->getEditedVideos();
        $response['code'] = 200;
        $response['message'] = 'Data sync successfully!';
        $response['error'] = '';
        $response['total'] = $this->data['compilation_claimed']->num_rows();
        $response['total'] = $this->deal->getCSompilationClaimedLeadsCount();
        $response['data'] = $this->load->view('video_rights/compilation_claimed', $this->data, true);
        $response['created'] = '';
        echo json_encode($response);
        exit;
    }
    public function compilation_detail($id,$infoId)
    {

        auth();
        role_permitted(false, 'video_rights');

        $result = $this->deal->getCompilationDealDetail($id,$infoId, 'cl.*,ad.name,cli.tt_id,cli.tt_url,cli.tt_handle');


        //echo 1;exit;
        if (!$result) {

            redirect($_SERVER['HTTP_REFERER']);

        } else {


            $this->data['dealData'] = $result;


        }

        $this->data['content'] = $this->load->view('video_rights/compilation_detail', $this->data, true);
        $this->load->view('common_files/template', $this->data);

    }

    public function compilation_claimed()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_rights', 'Claimed');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();
        $this->load->library('sheet');
        $response['code'] = 200;
        $response['message'] = 'Video Claimed Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $dbData = $this->security->xss_clean($this->input->post());
        $id = $dbData['lead_id'];
        $lead = $this->deal->getCompilationDealById($id);
        /*$url = $video->url;
        if ($dbData['claimed_source'] == 'Raw') {
            $url = '';
            $rawVideos = $this->d->getRawVideosByVideoId($videoid);
            foreach ($rawVideos->result() as $v) {
                $url .= $v->s3_url . ' , ';
            }
            $url = rtrim($url, ' , ');
        } else {

        }*/

        //$this->sheet->addRowToSpreadsheet(array(date('Y-m-d'),'','','https://www.youtube.com/watch?v='.$video->youtube_id,'',$url),241318973);
        $this->sheet->addRowToSpreadsheet(array($lead->yt_id, 'https://www.youtube.com/watch?v=' . $lead->yt_id, $lead->title, '', $lead->wg_id));
        unset($dbData['lead_id']);
        $dbData['claim_date'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update('compilation_leads', $dbData);
        //echo $this->db->last_query();exit;
        echo json_encode($response);
        exit;

    }

}
