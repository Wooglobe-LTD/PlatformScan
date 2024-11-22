<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dropbox_Report extends APP_Controller {

	public function __construct() {
        parent::__construct();
        
		$this->data['active'] = 'dropbox_report';
		$css = array(
			'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css',
            'assets/css/dataTables.checkboxes.css'
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
			'assets/js/dropbox_reports.js',
            'assets/js/canvasjs.min.js',
            'assets/js/dataTables.checkboxes.min.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'reports'),
        );
		$this->load->model('Dropbox_Report_Model','report');
		$this->load->model('Video_Deal_Model','deal');

    }

    public function update_dropbox_status(){
        return $this->update_dropbox_upload_status();
    }
    public function update_all_durations(){
        return $this->report->update_all_durations();
    }
    public function update_durations(){
        return $this->report->update_durations(NULL, TRUE);
    }
	public function index()
	{
		auth();
        role_permitted(false,'reports');



        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $signed_data = array();
        $reject_data = array();
        $cancel_data = array();
       /* if($role_id == 11) {*/
            $now = new DateTime("7 days ago", new DateTimeZone('America/New_York'));
            $interval = new DateInterval('P1D'); // 1 Day interval
            $period = new DatePeriod($now, $interval, 7); // 7 Days
            $i = 0;
            foreach ($period as $day) {
                $key = $day->format('Y-m-d');
                $where_date = " AND DATE(vl.created_at) = '$key'";
                $where_update_date = " AND DATE(vl.updated_at) = '$key'";
                $where_lead = " AND vl.status >= 1 AND DATE(vl.created_at) = '$key'";
                $day_key = date('l', strtotime($key));
                $day_date = date('Y-m-d', strtotime($key));
                $dealCount = $this->report->getleadReports('*', '', 0, 0, 'vl.id DESC', '', $where_lead);
                // echo $this->db->last_query();echo "<br>";
                $signedCount = $this->report->getsignedReports('*', '', 0, 0, 'vl.id DESC', '', $where_date);
                $rejectCount = $this->report->getrejectedReports('*', '', 0, 0, 'vl.id DESC', '', $where_update_date);
                $cancelCount = $this->report->getCancelReports('*', '', 0, 0, 'vl.id DESC', '', $where_update_date);
                $deal_data[$i]['y'] = $dealCount->num_rows();
                $deal_data[$i]['label'] = $day_key . '-' . $day_date;
                $reject_data[$i]['y'] = $rejectCount->num_rows();
                $reject_data[$i]['label'] = $day_key . '-' . $day_date;
                $signed_data[$i]['y'] = $signedCount->num_rows();
                $signed_data[$i]['label'] = $day_key . '-' . $day_date;
                $cancel_data[$i]['y'] = $cancelCount->num_rows();
                $cancel_data[$i]['label'] = $day_key . '-' . $day_date;

                $i++;

            }
            /*   print_r($deal_data);
               exit();*/
            $month_now = new DateTime("30 days ago", new DateTimeZone('America/New_York'));
            $month_interval = new DateInterval('P1D'); // 1 Day interval
            $month_period = new DatePeriod($month_now, $month_interval, 30); // 7 Days
            $j = 0;
            foreach ($month_period as $month_day) {
                $month_key = $month_day->format('Y-m-d');
                $month_where_date = " AND DATE(vl.created_at) = '$month_key'";
                $month_where_update_date = " AND DATE(vl.updated_at) = '$month_key'";
                $month_where_lead = " AND vl.status >= 1 AND DATE(vl.created_at) = '$month_key'";
                $month_day_key = date('Y-m-d', strtotime($month_key));
                $month_dealCount = $this->report->getleadReports('*', '', 0, 0, 'vl.id DESC', '', $month_where_lead);
                $month_signedCount = $this->report->getsignedReports('*', '', 0, 0, 'vl.id DESC', '', $month_where_date);
                $month_rejectCount = $this->report->getrejectedReports('*', '', 0, 0, 'vl.id DESC', '', $month_where_update_date);
                $month_cancelCount = $this->report->getCancelReports('*', '', 0, 0, 'vl.id DESC', '', $month_where_update_date);
                $month_deal_data[$j]['y'] = $month_dealCount->num_rows();
                $month_deal_data[$j]['label'] = $month_day_key;
                $month_reject_data[$j]['y'] = $month_rejectCount->num_rows();
                $month_reject_data[$j]['label'] = $month_day_key;
                $month_signed_data[$j]['y'] = $month_signedCount->num_rows();
                $month_signed_data[$j]['label'] = $month_day_key;
                $month_cancel_data[$j]['y'] = $month_cancelCount->num_rows();
                $month_cancel_data[$j]['label'] = $month_day_key;
                $j++;

            }
            /*print_r($signed_data);
            print_r($dataPoints2);exit();*/
            $this->data['deal_data'] = $deal_data;
            $this->data['signed_data'] = $signed_data;
            $this->data['reject_data'] = $reject_data;
            $this->data['cancel_data'] = $cancel_data;
            $this->data['month_deal_data'] = $month_deal_data;
            $this->data['month_signed_data'] = $month_signed_data;
            $this->data['month_reject_data'] = $month_reject_data;
            $this->data['month_cancel_data'] = $month_cancel_data;
        /*}*/
        $pieChart = $this->report->getPieReports('*', '', 0, 0, 'vl.rating_point DESC', '','');

		$this->data['title'] = 'Dropbox Report';
        $this->data['role'] = $role_id;
        $this->data['pieChart'] = $pieChart;
        if($role_id == 1){
            $query ="SELECT * FROM `admin` WHERE `admin_role_id` = 12 and deleted = 0";
        }else{
            $query ="SELECT * FROM `admin` WHERE `id`=$adminid";
        }

        $result = $this->db->query($query);
        $staff_name = $result->result();
        $this->data['staff_name'] = $staff_name;
		$this->data['content'] = $this->load->view('reports/dropbox_report',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
		
	public function reports_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'reports');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$params = $this->security->xss_clean($this->input->get());

		$search = '';
		$orderby = 'vl.id DESC';
		$start = 0;
		$limit = 0;
		$where = NULL;
        $count = 0;
        $data = array();
        if(isset($params['search2']) && $params['search2'] == 2) {
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

            $staff_id = null;
            $staff_name = 'all';
            if (isset($params['type']) && !empty($params['type'])) {
                $staff_id = $params['type'];
                if ($params['type'] == '-1') {
                    $where .= " AND vl.staff_id IS NULL";
                    $staff_name = 'WooGlobe';
                } else {
                    $where .= " AND vl.staff_id = $params[type]";
                    $check_query = 'select name from admin where id='.$params['type'];
                    $check_res = $this->db->query($check_query);
                    if($check_res->num_rows() > 0){
                        $staff_name = $check_res->row()->name;
                    }
                }
            }else{
                $where .= " AND vl.staff_id IS NULL";
                $staff_name = 'WooGlobe';
            }
            $date_time = 'All time';
            if (isset($params['date_period'])) {
                if ($params['date_period'] == 1) {
                    $date_to = date('Y-m-d');
                    $date_time = $date_to;
                    $where .= " AND DATE(vl.created_at) = '$date_to' ";
                } elseif ($params['date_period'] == 2) {
                    $date_to = date('Y-m-d', strtotime('-1 days'));
                    $date_time = $date_to;
                    $where .= " AND DATE(vl.created_at) = '$date_to' ";
                } elseif ($params['date_period'] == 3) {
                    $date_from = date('Y-m-d', strtotime('-7 days'));
                    $date_to = date('Y-m-d');
                    $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                    $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
                } elseif ($params['date_period'] == 4) {
                    $date_from = date('Y-m') . '-01';
                    $date_to = date('Y-m-d');
                    $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                    $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
                } elseif ($params['date_period'] == 5) {
                    $month = date('m') - 1;
                    $date_fro = date('Y-') . $month . '-01';
                    $date_from = date("Y-m-d", strtotime($date_fro));
                    $date_t = date('Y-') . $month . '-31';
                    $date_to = date("Y-m-d", strtotime($date_t));
                    $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                    $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
                }
            }


            if ((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))) {
                $date_from = date('Y-m-d', strtotime($params['date_from']));
                $date_to = date('Y-m-d', strtotime($params['date_to']));
                $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";

            } else if ((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && empty($params['date_to']))) {
                $date_from = date('Y-m-d', strtotime($params['date_from']));
                $date_time = $date_from;
                $where .= " AND DATE(vl.created_at) = '$date_from' ";
            } else if ((isset($params['date_from']) && empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))) {
                $date_to = date('Y-m-d', strtotime($params['date_to']));
                $date_time = $date_to;
                $where .= " AND DATE(vl.created_at) = '$date_to' ";
            }
            //1122
            $date_time = 'All time';
            if (isset($params['date_aqution'])) {
                if ($params['date_aqution'] == 1) {
                    $date_to = date('Y-m-d');
                    $date_time = $date_to;
                    $where .= " AND DATE(vl.created_at) = '$date_to' ";
                } elseif ($params['date_aqution'] == 2) {
                    $date_to = date('Y-m-d', strtotime('-1 days'));
                    $date_time = $date_to;
                    $where .= " AND DATE(vl.created_at) = '$date_to' ";
                } elseif ($params['date_aqution'] == 3) {
                    $date_from = date('Y-m-d', strtotime('-7 days'));
                    $date_to = date('Y-m-d');
                    $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                    $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
                } elseif ($params['date_aqution'] == 4) {
                    $date_from = date('Y-m') . '-01';
                    $date_to = date('Y-m-d');
                    $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                    $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
                } elseif ($params['date_aqution'] == 5) {
                    $month = date('m') - 1;
                    $date_fro = date('Y-') . $month . '-01';
                    $date_from = date("Y-m-d", strtotime($date_fro));
                    $date_t = date('Y-') . $month . '-31';
                    $date_to = date("Y-m-d", strtotime($date_t));
                    $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                    $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
                }
            }


            if ((isset($params['date_from_aqution']) && !empty($params['date_from_aqution'])) && (isset($params['date_to_aqution']) && !empty($params['date_to_aqution']))) {
                $date_from = date('Y-m-d', strtotime($params['date_from_aqution']));
                $date_to = date('Y-m-d', strtotime($params['date_to_aqution']));
                $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";

            } else if ((isset($params['date_from_aqution']) && !empty($params['date_from_aqution'])) && (isset($params['date_to_aqution']) && empty($params['date_to_aqution']))) {
                $date_from = date('Y-m-d', strtotime($params['date_from_aqution']));
                $date_time = $date_from;
                $where .= " AND DATE(vl.created_at) = '$date_from' ";
            } else if ((isset($params['date_from_aqution']) && empty($params['date_from_aqution'])) && (isset($params['date_to_aqution']) && !empty($params['date_to_aqution']))) {
                $date_to = date('Y-m-d', strtotime($params['date_to_aqution']));
                $date_time = $date_to;
                $where .= " AND DATE(vl.created_at) = '$date_to' ";
            }
            //1122

            /*if((isset($params['closing_date_from']) && !empty($params['closing_date_from'])) && (isset($params['closing_date_to']) && !empty($params['closing_date_to']))){
                $closing_date_from = date('Y-m-d',strtotime($params['closing_date_from']));
                $closing_date_to = date('Y-m-d',strtotime($params['closing_date_to']));
                $where .= " AND DATE(vl.closing_date) BETWEEN '$closing_date_from' AND '$closing_date_to' ";

            }else if((isset($params['closing_date_from']) && !empty($params['closing_date_from'])) && (isset($params['closing_date_to']) && empty($params['closing_date_to']))){
                $closing_date_from = date('Y-m-d',strtotime($params['closing_date_from']));
                $where .= " AND DATE(vl.closing_date) = '$closing_date_from' ";
            }else if((isset($params['closing_date_from']) && empty($params['closing_date_from'])) && (isset($params['closing_date_to']) && !empty($params['closing_date_to']))){
                $closing_date_to = date('Y-m-d',strtotime($params['closing_date_to']));
                $where .= " AND DATE(vl.closing_date) = '$closing_date_to' ";
            }*/

            if (isset($params['rating']) && !empty($params['rating'])) {
                if ($params['rating'] == 5) {
                    $where .= " AND vl.rating_point >= $params[rating] ";
                }
                if ($params['rating'] == 6) {
                    $where .= " AND vl.rating_point >= $params[rating] ";
                }
                if ($params['rating'] == 7) {
                    $where .= " AND vl.rating_point >= $params[rating] ";
                }
                if ($params['rating'] == 8) {
                    $where .= " AND vl.rating_point >= $params[rating] ";
                }
                if ($params['rating'] == 9) {
                    $where .= " AND vl.rating_point >= $params[rating] ";
                }
            }

            if (isset($params['published']) && !empty($params['published'])) {
                if ($params['published'] == 1) {
                    $where .= " AND vl.published_yt = 1";
                } else if ($params['published'] == 2) {
                    $where .= " AND vl.published_fb = 1";
                } else if ($params['published'] == 3) {
                    $where .= " AND vl.published_fb = 1";
                } else if ($params['published'] == 4) {
                    $where .= " AND vl.published_fb = 1";
                } else if ($params['published'] == 5) {
                    $where .= " AND vl.published_fb = 1";
                }

            }

            if (isset($params['mrss']) && !empty($params['mrss'])) {
                $where .= " AND v.mrss = $params[mrss] ";
            }

            if (isset($params['stage']) && !empty($params['stage'])) {
                if ($params['stage'] == 3) {
                    $where .= " AND vl.status = 3 ";
                    $where .= " AND vl.client_id = 0 ";
                } else if ($params['stage'] == 4) {
                    $where .= " AND vl.status = 3 ";
                    $where .= " AND vl.client_id != 0 ";
                    $where .= " AND u.password IS NULL ";
                } else if ($params['stage'] == 7) {
                    $where .= " AND vl.status = 3 ";
                    $where .= " AND vl.information_pending = 0 ";
                } else if ($params['stage'] == 13) {
                    $where .= " AND vl.status = 3 ";
                    $where .= " AND vl.information_pending = 1 
                AND vl.uploaded_edited_videos = 0
                AND ( #v.real_deciption_updated = 0
                    v.question_video_taken IS NOT NULL
                    AND v.question_when_video_taken IS NOT NULL
                    AND v.question_video_context IS NOT NULL
                    #AND v.question_video_information IS NOT NULL 
                 ) ";
                } else if ($params['stage'] == 6) {
                    $where .= " AND vl.status = 6 ";
                    $where .= " AND vl.information_pending = 1
                AND vl.uploaded_edited_videos = 0
                AND ( v.real_deciption_updated = 1 
                    AND v.question_video_taken IS NOT NULL
                    AND v.question_when_video_taken IS NOT NULL
                    AND v.question_video_context IS NOT NULL
                    #AND v.question_video_information IS NOT NULL 
                 ) ";
                } else if ($params['stage'] == 12) {
                    $where .= " AND vl.status = 6 ";
                    $where .= " AND vl.information_pending = 1
                AND vl.uploaded_edited_videos = 1
                AND ( v.real_deciption_updated = 1 
                    AND v.question_video_taken IS NOT NULL
                    AND v.real_deciption_updated IS NOT NULL
                    AND v.question_video_context IS NOT NULL
                    #AND v.question_video_information IS NOT NULL 
                 ) ";
                } else {
                    $where .= " AND vl.status = $params[stage] ";
                }

            }

            $result = $this->report->getReports(
                '
                vl.unique_key,
                CONCAT(vl.first_name," ",vl.last_name) as client_name,
                vl.first_name,
                vl.last_name,
                vl.phone,
                vl.email as client_email,
                vl.video_title,
                vl.video_url,
                vl.status,
                u.password,
                u.email,
                u.mobile,
                u.paypal_email,
                vl.client_id,
                vl.information_pending,
                v.video_verified,
                v.description,
                vl.uploaded_edited_videos,
                vl.message,
                v.tags,
                vl.confidence_level,
                u.full_name,
                vl.published_yt,
                vl.published_fb,
                vl.rating_point,
                vl.revenue_share,
                v.title,
                v.youtube_id,
                v.facebook_id,
                vl.id as lead_id,
                ev.portal_thumb,
                rv.url as raw_video_url,
                rv.s3_url,
                v.mrss,
                v.real_deciption_updated,
                DATE_FORMAT(vl.created_at,"%m/%d/%Y %H:%i %p") AS created_at,
                DATE_FORMAT(vl.updated_at,"%m/%d/%Y %H:%i %p") AS updated_at,
                DATE_FORMAT(vl.closing_date,"%m/%d/%Y %H:%i %p") AS closing_date
                ',
                $search, $start, $limit, $orderby, $params['columns'], $where);
            //echo $this->db->last_query();exit;
            $resultCount = $this->report->getReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where);
            $count = $resultCount->num_rows();
            $response = array();
            $data = array();
            $adminid = $this->sess->userdata('adminId');
            $check_query = 'select admin_role_id from admin where id=' . $adminid;
            $check_res = $this->db->query($check_query);
            $check_role_id = $check_res->result();
            $role_id = $check_role_id[0]->admin_role_id;


            //if($role_id == '11'){

            $signedCount = $this->report->getsignedReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where);
            $rateCount = $this->report->getNotRateReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where);
            $actionCount = $this->report->getTakeActionReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where);
            $signedRejectCount = $this->report->getsignedAndrejectReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where);
            $acquiredRejectCount = $this->report->getAcquiredReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where);

            $rejectedCount = $this->report->getrejectedReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where);
            $canceledCount = $this->report->getCancelReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where);
            $poorCount = $this->report->getPoorReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where);
            $notCount = $this->report->getNotInterestReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where);

            /*
            print_r($sale_data);
            print_r('out');exit();*/

            $r = array();

            $r[] = $staff_name;
            if($resultCount->num_rows() > 0){
                $r[] = '<a href="javascript:void(0);" data-type="total_lead" data-staff-id="'.$staff_id.'" class="individual-report">'.$resultCount->num_rows().'</a>';
            }else{
                $r[] = 0;
            }
            if($rateCount->num_rows() > 0){
                $r[] = '<a href="javascript:void(0);" data-type="total_rated" data-staff-id="'.$staff_id.'" class="individual-report">'.$rateCount->num_rows().'</a>';
            }else{
                $r[] = 0;
            }
            if($actionCount->num_rows() > 0){
                $r[] = '<a href="javascript:void(0);" data-type="action_required" data-staff-id="'.$staff_id.'" class="individual-report">'.$actionCount->num_rows().'</a>';
            }else{
                $r[] = 0;
            }
            if($signedCount->num_rows() > 0){
                $r[] = '<a href="javascript:void(0);" data-type="total_signed" data-staff-id="'.$staff_id.'" class="individual-report">'.$signedCount->num_rows().'</a>';
            }else{
                $r[] = 0;
            }
            if($signedRejectCount->num_rows() > 0){
                $r[] = '<a href="javascript:void(0);" data-type="total_signed_reject" data-staff-id="'.$staff_id.'" class="individual-report">'.$signedRejectCount->num_rows().'</a>';
            }else{
                $r[] = 0;
            }
            if($acquiredRejectCount->num_rows() > 0){
                $r[] = '<a href="javascript:void(0);" data-type="total_acquired" data-staff-id="'.$staff_id.'" class="individual-report">'.$acquiredRejectCount->num_rows().'</a>';
            }else{
                $r[] = 0;
            }
            if($canceledCount->num_rows() > 0){
                $r[] = '<a href="javascript:void(0);" data-type="total_canceled" data-staff-id="'.$staff_id.'" class="individual-report">'.$canceledCount->num_rows().'</a>';
            }else{
                $r[] = 0;
            }
            if($poorCount->num_rows() > 0){
                $r[] = '<a href="javascript:void(0);" data-type="total_poor" data-staff-id="'.$staff_id.'" class="individual-report">'.$poorCount->num_rows().'</a>';
            }else{
                $r[] = 0;
            }
            if($notCount->num_rows() > 0){
                $r[] = '<a href="javascript:void(0);" data-type="total_not_interested" data-staff-id="'.$staff_id.'" class="individual-report">'.$notCount->num_rows().'</a>';
            }else{
                $r[] = 0;
            }






            //$r[] = $rejectedCount->num_rows();



            if ($resultCount->num_rows() != 0) {
                $r[] = number_format($acquiredRejectCount->num_rows() / $resultCount->num_rows(), 2);
            } else {
                $r[] = '0';
            }


            $data[] = $r;
            foreach ($result->result() as $row) {

                $rr = array();


                $rr[] = $row->unique_key;
                $rr[] = $row->client_name . ' - ' . $row->video_title;
                $rr[] = $row->closing_date;
                $stage = 'Pending Lead';
                if ($row->status == 10) {
                    $stage = 'Lead Rated';
                } else if ($row->status == 5) {
                    $stage = 'Poor Rating Video';
                } else if ($row->status == 2) {
                    $stage = 'Contract Sent';
                } else if ($row->status == 3) {
                    $stage = 'Contract Signed';
                } else if ($row->status == 3 && (!is_null($row->client_id) && $row->client_id > 0) && is_null($row->password)) {
                    $stage = 'Account Created';
                } else if ($row->status == 3 && (!is_null($row->client_id) && $row->client_id > 0) && !is_null($row->password) && $row->information_pending == 0) {
                    $stage = 'Deal Information Pending';
                } else if ($row->status == 6 && (!is_null($row->client_id) && $row->client_id > 0) && !is_null($row->password) && $row->information_pending == 1 && $row->video_verified == 1 && $row->uploaded_edited_videos == 0) {
                    $stage = 'Upload Edited Videos';
                } else if ($row->status == 6 && (!is_null($row->client_id) && $row->client_id > 0) && !is_null($row->password) && $row->information_pending == 1 && $row->video_verified == 1 && $row->uploaded_edited_videos == 1) {
                    $stage = 'Distribute Edited Videos';
                } else if ($row->status == 8) {
                    $stage = 'Closed Won';
                } else if ($row->status == 9) {
                    $stage = 'Closed Lost';
                } else if ($row->status == 11) {
                    $stage = 'Not Interested';
                }
                $rr[] = $stage;
                $rr[] = $row->created_at;


                $datar[] = $rr;
            }


            /*}else{

                foreach($result->result() as $row){

                    $r = array();


                    $r[] = $row->unique_key;
                    $r[] = $row->client_name .' - '.$row->video_title;
                    $r[] = $row->closing_date;
                    $stage = 'Pending Lead';
                    if($row->status == 10){
                        $stage = 'Lead Rated';
                    }else if($row->status == 5){
                        $stage = 'Poor Rating Video';
                    }else if($row->status == 2){
                        $stage = 'Contract Sent';
                    }else if($row->status == 3){
                        $stage = 'Contract Signed';
                    }else if($row->status == 3 && (!is_null($row->client_id ) && $row->client_id > 0) && is_null($row->password)){
                        $stage = 'Account Created';
                    }else if($row->status == 3 && (!is_null($row->client_id ) && $row->client_id > 0) && !is_null($row->password) && $row->information_pending == 0){
                        $stage = 'Deal Information Pending';
                    }else if($row->status == 6 && (!is_null($row->client_id ) && $row->client_id > 0) && !is_null($row->password) && $row->information_pending == 1 && $row->video_verified == 1 && $row->uploaded_edited_videos == 0){
                        $stage = 'Upload Edited Videos';
                    }
                    else if($row->status == 6 && (!is_null($row->client_id ) && $row->client_id > 0) && !is_null($row->password) && $row->information_pending == 1 && $row->video_verified == 1 && $row->uploaded_edited_videos == 1){
                        $stage = 'Distribute Edited Videos';
                    }else if($row->status == 8){
                        $stage = 'Closed Won';
                    }else if($row->status == 9){
                        $stage = 'Closed Lost';
                    }else if($row->status == 11){
                        $stage = 'Not Interested';
                    }
                    $r[] = $stage;
                    $r[] = $row->created_at;
                    //$r[] = $row->updated_at;
                    //$r[] = 'N/A';
                    $title = $row->video_title;
                    if(!empty($row->title)){
                        $title = $row->title;
                    }
                    $r[] = $title;
                    $descrition = 'N/A';
                    if(!empty($row->description)){
                        $descrition = $row->description;
                    }else{
                        $descrition = $row->message;
                    }
                    $r[] = $descrition;
                    $tags = 'N/A';
                    if(!empty($row->tags)){
                        $tags = $row->tags;
                    }
                    $r[] = $tags;
                    $r[] = $row->confidence_level;
                    $r[] = (in_array($row->status, [3, 6, 7, 8]))?('<a href="'.base_url().'view_contract/'.$row->lead_id.'">View Contract</a>'):('N/A');
                    $r[] = $row->portal_thumb;
                    $r[] = $row->video_url;
                    $r[] = $row->raw_video_url;
                    $r[] = $row->s3_url;
                    $real_deciption_updated = 'NO';
                    if($row->real_deciption_updated == 1){
                        $real_deciption_updated = "Yes";
                    }
                    $r[] = $real_deciption_updated;
                    $ClientName = 'N/A';
                    if(!empty($row->full_name)){
                        $ClientName = $row->full_name;
                    }else{
                        $ClientName = $row->client_name;
                    }
                    $r[] = $ClientName;
                    //$r[] = 'N/A';
                    $r[] = 'N/A';
                    $r[] = 'N/A';
                    $publish = '';

                    if($row->published_yt == 1){
                        $publish .= 'YouTube,';
                    }
                    if($row->published_fb == 1){
                        $publish .= 'Facbook,';
                    }
                    $r[] = rtrim($publish,',');
                    $ratingPoint = 'N/A';
                    if($row->rating_point > 0){
                        $ratingPoint = $row->rating_point;
                    }
                    $r[] = $ratingPoint;
                    $revenueShare = 'N/A';
                    if($row->revenue_share > 0){
                        $revenueShare = $row->revenue_share.'%';
                    }
                    $r[] = $revenueShare;

                    //$r[] = 'N/A';
                    //$r[] = 'N/A';
                    //$r[] = 'N/A';
                    $email = $row->client_email;
                    if(!empty($row->email)){
                        $email = $row->email;
                    }
                    $r[] = $email;
                    $mobile = $row->phone;
                    if(!empty($row->mobile)){
                        $mobile = $row->mobile;
                    }
                    $r[] = $mobile;

                    $clientPaypalEmail = 'N/A';
                    if(!empty($row->paypal_email)){
                        $clientPaypalEmail = $row->paypal_email;
                    }
                    $r[] = $clientPaypalEmail;
                    $youtubeLink = 'N/A';
                    if(!empty($row->youtube_id)){
                        $youtubeLink = "https://www.youtube.com/watch?v=$row->youtube_id";
                    }
                    $r[] = $youtubeLink;
                    $facebookLink = 'N/A';
                    if(!empty($row->facebook_id)){
                        $facebookLink = "https://www.facebook.com/watch/?v==$row->facebook_id";
                    }
                    $r[] = $facebookLink;

                    $mrss = 'NO';
                    if($row->mrss == 1){
                        $mrss = "Yes";
                    }
                    $r[] = $mrss;

                    $data[] = $r;
                }
            }*/
        }




		$response['code'] = 200;
		$response['message'] = 'Listing';
		$response['error'] = '';
		$response['data'] = $data;
		$response['recordsTotal'] = $count;
		$response['recordsFiltered'] = $count;
		echo json_encode($response);
		exit;
	}

    public function reports_details(){

        $adminid = $this->sess->userdata('adminId');
        // $adminid = 53;
        $check_query = 'select admin_role_id from admin where id=' . $adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id = $check_res->result();
        $role_id = $check_role_id[0]->admin_role_id;
        //if($role_id == '11') {
            $auth_ajax = auth_ajax();
            if ($auth_ajax) {
                echo json_encode($auth_ajax);
                exit;
            }
            $role_permitted_ajax = role_permitted_ajax(false, 'reports');
            if ($role_permitted_ajax) {
                echo json_encode($role_permitted_ajax);
                exit;
            }
            $params = $this->security->xss_clean($this->input->get());




        $data = array();
        $count = 0;
        if(isset($params['search2']) && $params['search2'] == 2) {
            $search = '';
            $orderby = 'rv.dropbox_status DESC';
            $start = 0;
            $limit = 0;
            $where = NULL;

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
            if (isset($params['if_verified'])){
                if($params['if_verified'] == '1'){ // Verified
                    $where .= " AND vl.status >= 6 ";
                }
                elseif ($params['if_verified'] == '2') { // Non Verified
                    $where .= " AND vl.status < 6 ";
                }
            }

            // if (isset($params['type']) && !empty($params['type'])) {
            //     if ($params['type'] == '-1') {
            //         $where .= " AND vl.staff_id IS NULL";
            //     }else{
            //         $where .= " AND vl.staff_id = $params[type]";
            //     }

            // }else{
            //     if($adminid != 1){
            //         $where .= " AND vl.staff_id = $adminid ";
            //     }

            // }
            $date_time = 'All time';
            if (isset($params['date_period'])) {
                if ($params['date_period'] == 1) {
                    $date_to = date('Y-m-d');
                    $date_time = $date_to;
                    $where .= " AND DATE(vl.created_at) = '$date_to' ";
                } elseif ($params['date_period'] == 2) {
                    $date_to = date('Y-m-d', strtotime('-1 days'));
                    $date_time = $date_to;
                    $where .= " AND DATE(vl.created_at) = '$date_to' ";
                } elseif ($params['date_period'] == 3) {
                    $date_from = date('Y-m-d', strtotime('-7 days'));
                    $date_to = date('Y-m-d');
                    $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                    $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
                } elseif ($params['date_period'] == 4) {
                    $date_from = date('Y-m') . '-01';
                    $date_to = date('Y-m-d');
                    $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                    $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
                } elseif ($params['date_period'] == 5) {
                    $month = date('m') - 1;
                    $date_fro = date('Y-') . $month . '-01';
                    $date_from = date("Y-m-d", strtotime($date_fro));
                    $date_t = date('Y-') . $month . '-31';
                    $date_to = date("Y-m-d", strtotime($date_t));
                    $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                    $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
                }
            }


            if ((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))) {
                $date_from = date('Y-m-d', strtotime($params['date_from']));
                $date_to = date('Y-m-d', strtotime($params['date_to']));
                $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";

            } else if ((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && empty($params['date_to']))) {
                $date_from = date('Y-m-d', strtotime($params['date_from']));
                $date_time = $date_from;
                $where .= " AND DATE(vl.created_at) = '$date_from' ";
            } else if ((isset($params['date_from']) && empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))) {
                $date_to = date('Y-m-d', strtotime($params['date_to']));
                $date_time = $date_to;
                $where .= " AND DATE(vl.created_at) = '$date_to' ";
            }


            if ((isset($params['duration_from']) && !empty($params['duration_from'])) && (isset($params['duration_to']) && !empty($params['duration_to']))) {
                $duration_from = $params['duration_from']; //date('Y-m-d', strtotime($params['duration_from']));
                $duration_to = $params['duration_to']; //date('Y-m-d', strtotime($params['duration_to']));
                $duration_time = "BETWEEN $duration_from AND $duration_to ";
                $where .= " AND vl.lead_duration BETWEEN $duration_from AND $duration_to ";

            } else if ((isset($params['duration_from']) && !empty($params['duration_from'])) && (isset($params['duration_to']) && empty($params['duration_to']))) {
                $duration_from = $params['duration_from']; //date('Y-m-d', strtotime($params['duration_from']));
                $duration_time = $duration_from;
                $where .= " AND vl.lead_duration >= '$duration_from' ";
            } else if ((isset($params['duration_from']) && empty($params['duration_from'])) && (isset($params['duration_to']) && !empty($params['duration_to']))) {
                $duration_to = $params['duration_to']; // date('Y-m-d', strtotime($params['duration_to']));
                $duration_time = $duration_to;
                $where .= " AND vl.lead_duration <= '$duration_to' ";
            }

            if (isset($params['rating']) && !empty($params['rating'])) {
                if ($params['rating'] == 5) {
                    $where .= " AND vl.rating_point >= $params[rating] ";
                }
                if ($params['rating'] == 6) {
                    $where .= " AND vl.rating_point >= $params[rating] ";
                }
                if ($params['rating'] == 7) {
                    $where .= " AND vl.rating_point >= $params[rating] ";
                }
                if ($params['rating'] == 8) {
                    $where .= " AND vl.rating_point >= $params[rating] ";
                }
                if ($params['rating'] == 9) {
                    $where .= " AND vl.rating_point >= $params[rating] ";
                }
            }

            if (isset($params['published']) && !empty($params['published'])) {
                if ($params['published'] == 1) {
                    $where .= " AND vl.published_yt = 1";
                } else if ($params['published'] == 2) {
                    $where .= " AND vl.published_fb = 1";
                }

            }



            if (isset($params['mrss']) && !empty($params['mrss'])) {
                $where .= " AND v.mrss = $params[mrss] ";
            }

            if (isset($params['stage']) && !empty($params['stage'])) {
                if ($params['stage'] == 3) {
                    $where .= " AND vl.status = 3 ";
                    $where .= " AND vl.client_id = 0 ";
                } else if ($params['stage'] == 4) {
                    $where .= " AND vl.status = 3 ";
                    $where .= " AND vl.client_id != 0 ";
                    $where .= " AND u.password IS NULL ";
                } else if ($params['stage'] == 7) {
                    $where .= " AND vl.status = 3 ";
                    $where .= " AND vl.information_pending = 0 ";
                } else if ($params['stage'] == 13) {
                    $where .= " AND vl.status = 3 ";
                    $where .= " AND vl.information_pending = 1 
	        AND vl.uploaded_edited_videos = 0
	        AND ( #v.real_deciption_updated = 0
	            v.question_video_taken IS NOT NULL
	            AND v.question_when_video_taken IS NOT NULL
	            AND v.question_video_context IS NOT NULL
	            #AND v.question_video_information IS NOT NULL 
	         ) ";
                } else if ($params['stage'] == 6) {
                    $where .= " AND vl.status = 6 ";
                    $where .= " AND vl.information_pending = 1
	        AND vl.uploaded_edited_videos = 0
	        AND ( v.real_deciption_updated = 1 
	            AND v.question_video_taken IS NOT NULL
	            AND v.question_when_video_taken IS NOT NULL
	            AND v.question_video_context IS NOT NULL
	            #AND v.question_video_information IS NOT NULL 
	         ) ";
                } else if ($params['stage'] == 12) {
                    $where .= " AND vl.status = 6 ";
                    $where .= " AND vl.information_pending = 1
	        AND vl.uploaded_edited_videos = 1
	        AND ( v.real_deciption_updated = 1 
	            AND v.question_video_taken IS NOT NULL
	            AND v.real_deciption_updated IS NOT NULL
	            AND v.question_video_context IS NOT NULL
	            #AND v.question_video_information IS NOT NULL 
	         ) ";
                } else {
                    $where .= " AND vl.status = $params[stage] ";
                }

            }

            if (isset($params['lead_type']) && !empty($params['lead_type']) && $params['lead_type'] != 'total_lead') {
                if($params['lead_type'] == 'total_rated'){
                    $where .= " AND vl.deleted = 0 and vl.status = 1 ";
                }else if($params['lead_type'] == 'action_required'){
                    $where .= " AND vl.deleted = 0 and vl.status = 10 ";
                }else if($params['lead_type'] == 'total_signed'){
                    $where .= " AND vl.status in (3)
		    AND vl.load_view != 5 ";
                }else if($params['lead_type'] == 'total_signed_reject'){
                    $where .= " AND vl.status in (3)
		    AND vl.load_view = 5 ";
                }else if($params['lead_type'] == 'total_acquired'){
                    $where .= " AND vl.deleted = 0 AND vl.status in (6,7,8) ";
                }else if($params['lead_type'] == 'total_canceled'){
                    $where .= " AND vl.deleted = 1 ";
                }else if($params['lead_type'] == 'total_poor'){
                    $where .= " AND vl.deleted = 0 and vl.status = 5 ";
                }else if($params['lead_type'] == 'total_not_interested'){
                    $where .= " AND vl.deleted = 0 and vl.status = 11 ";
                }
            }


            // $result = $this->report->getReports(
            //     '
			// vl.unique_key,
			// CONCAT(vl.first_name," ",vl.last_name) as client_name,
			// vl.first_name,
			// vl.last_name,
			// vl.phone,
			// vl.email as client_email,
			// vl.video_title,
			// vl.video_url,
			// vl.status,
			// u.password,
			// u.email,
			// u.mobile,
			// u.paypal_email,
			// vl.client_id,
			// vl.information_pending,
			// v.video_verified,
			// v.description,
			// vl.uploaded_edited_videos,
			// vl.message,
			// v.tags,
			// vl.confidence_level,
			// u.full_name,
			// vl.published_yt,
			// vl.published_fb,
			// vl.rating_point,
			// vl.revenue_share,
			// v.title,
			// v.youtube_id,
			// v.facebook_id,
			// vl.id as lead_id,
			// ev.portal_thumb,
			// rv.url as raw_video_url,
			// rv.s3_url as s3_url,
            // rv.dropbox_status,
			// v.mrss,
			// v.real_deciption_updated,
			// DATE_FORMAT(vl.created_at,"%m/%d/%Y %H:%i %p") AS created_at,
			// DATE_FORMAT(vl.updated_at,"%m/%d/%Y %H:%i %p") AS updated_at,
			// DATE_FORMAT(vl.closing_date,"%m/%d/%Y %H:%i %p") AS closing_date
			// ',$search, $start, $limit, 'rv.dropbox_status DESC', $params['columns'], $where);
            
            $result = $this->report->getReports('
            rv.lead_id, 
            rv.s3_url,
            rv.dropbox_status, 
            rv.video_duration,
            vl.unique_key, 
            vl.video_title,
            vl.lead_duration
            vl.created_at,
            DATE_FORMAT(vl.created_at,"%m/%d/%Y %H:%i %p") AS created_at,
			DATE_FORMAT(vl.updated_at,"%m/%d/%Y %H:%i %p") AS updated_at,
			DATE_FORMAT(vl.closing_date,"%m/%d/%Y %H:%i %p") AS closing_date
            ',
            $search, $start, $limit, $orderby, $params['columns'], $where);

            $resultCount = $this->report->getReports('*', '', 0, 0, '', $params['columns'], $where);
            $count = $resultCount->num_rows();
            $response = array();
            $data = array();

            foreach ($result->result() as $row) {
                // print_r($row);
                // exit();

                $r = array();

                
                $r[] = $row->unique_key;//"<input type='checkbox' />";
                $r[] = $row->unique_key;
                $r[] = $row->video_title;
                $r[] = $row->dropbox_status;
                $r[] = $row->lead_duration;
                $r[] = $row->created_at;


                $data[] = $r;
            }


            //}
        }
        $response['data'] = $data;
        $response['recordsTotal'] = $count;
        $response['recordsFiltered'] = $count;
        $response['code'] = 200;
        $response['message'] = 'Listing';
        $response['error'] = '';

        echo json_encode($response);
        exit;
    }

    public function pie_report(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'reports');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->post());


        $search = '';
        $orderby = 'vl.id DESC';
        $start = 0;
        $limit = 0;
        $where = NULL;
        $count = 0;
        $data = array();

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
        if (isset($params['if_verified'])){
            if($params['if_verified'] == '1'){ // Verified
                $where .= " AND vl.status >= 6 ";
            }
            elseif ($params['if_verified'] == '2') { // Non Verified
                $where .= " AND vl.status < 6 ";
            }
        }

        $staff_name = 'All';
        if (isset($params['type']) && !empty($params['type'])) {
            if ($params['type'] == '-1') {
                $staff_name = 'WooGlobe';
                $where .= " AND vl.staff_id IS NULL";
            } else {
                $where .= " AND vl.staff_id = $params[type]";
                $check_query = 'select admin_role_id from admin where id='.$params['type'];
                $check_res = $this->db->query($check_query);
                if($check_res->num_rows() > 0){
                    $staff_name = $check_res->row()->name;
                }
                $check_role_id =$check_res->result();
            }
        }
        $date_time = 'All time';
        if (isset($params['date_period'])) {
            if ($params['date_period'] == 1) {
                $date_to = date('Y-m-d');
                $date_time = $date_to;
                $where .= " AND DATE(vl.created_at) = '$date_to' ";
            } elseif ($params['date_period'] == 2) {
                $date_to = date('Y-m-d', strtotime('-1 days'));
                $date_time = $date_to;
                $where .= " AND DATE(vl.created_at) = '$date_to' ";
            } elseif ($params['date_period'] == 3) {
                $date_from = date('Y-m-d', strtotime('-7 days'));
                $date_to = date('Y-m-d');
                $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
            } elseif ($params['date_period'] == 4) {
                $date_from = date('Y-m') . '-01';
                $date_to = date('Y-m-d');
                $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
            } elseif ($params['date_period'] == 5) {
                $month = date('m') - 1;
                $date_fro = date('Y-') . $month . '-01';
                $date_from = date("Y-m-d", strtotime($date_fro));
                $date_t = date('Y-') . $month . '-31';
                $date_to = date("Y-m-d", strtotime($date_t));
                $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
            }
        }


        if ((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))) {
            $date_from = date('Y-m-d', strtotime($params['date_from']));
            $date_to = date('Y-m-d', strtotime($params['date_to']));
            $date_time = "BETWEEN '$date_from' AND '$date_to' ";
            $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";

        } else if ((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && empty($params['date_to']))) {
            $date_from = date('Y-m-d', strtotime($params['date_from']));
            $date_time = $date_from;
            $where .= " AND DATE(vl.created_at) = '$date_from' ";
        } else if ((isset($params['date_from']) && empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))) {
            $date_to = date('Y-m-d', strtotime($params['date_to']));
            $date_time = $date_to;
            $where .= " AND DATE(vl.created_at) = '$date_to' ";
        }
        //1122
        $date_time = 'All time';
        if (isset($params['date_aqution'])) {
            if ($params['date_aqution'] == 1) {
                $date_to = date('Y-m-d');
                $date_time = $date_to;
                $where .= " AND DATE(vl.created_at) = '$date_to' ";
            } elseif ($params['date_aqution'] == 2) {
                $date_to = date('Y-m-d', strtotime('-1 days'));
                $date_time = $date_to;
                $where .= " AND DATE(vl.created_at) = '$date_to' ";
            } elseif ($params['date_aqution'] == 3) {
                $date_from = date('Y-m-d', strtotime('-7 days'));
                $date_to = date('Y-m-d');
                $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
            } elseif ($params['date_aqution'] == 4) {
                $date_from = date('Y-m') . '-01';
                $date_to = date('Y-m-d');
                $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
            } elseif ($params['date_aqution'] == 5) {
                $month = date('m') - 1;
                $date_fro = date('Y-') . $month . '-01';
                $date_from = date("Y-m-d", strtotime($date_fro));
                $date_t = date('Y-') . $month . '-31';
                $date_to = date("Y-m-d", strtotime($date_t));
                $date_time = "BETWEEN '$date_from' AND '$date_to' ";
                $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";
            }
        }


        if ((isset($params['date_from_aqution']) && !empty($params['date_from_aqution'])) && (isset($params['date_to_aqution']) && !empty($params['date_to_aqution']))) {
            $date_from = date('Y-m-d', strtotime($params['date_from_aqution']));
            $date_to = date('Y-m-d', strtotime($params['date_to_aqution']));
            $date_time = "BETWEEN '$date_from' AND '$date_to' ";
            $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";

        } else if ((isset($params['date_from_aqution']) && !empty($params['date_from_aqution'])) && (isset($params['date_to_aqution']) && empty($params['date_to_aqution']))) {
            $date_from = date('Y-m-d', strtotime($params['date_from_aqution']));
            $date_time = $date_from;
            $where .= " AND DATE(vl.created_at) = '$date_from' ";
        } else if ((isset($params['date_from_aqution']) && empty($params['date_from_aqution'])) && (isset($params['date_to_aqution']) && !empty($params['date_to_aqution']))) {
            $date_to = date('Y-m-d', strtotime($params['date_to_aqution']));
            $date_time = $date_to;
            $where .= " AND DATE(vl.created_at) = '$date_to' ";
        }


        if (isset($params['rating']) && !empty($params['rating'])) {
            if ($params['rating'] == 5) {
                $where .= " AND vl.rating_point >= $params[rating] ";
            }
            if ($params['rating'] == 6) {
                $where .= " AND vl.rating_point >= $params[rating] ";
            }
            if ($params['rating'] == 7) {
                $where .= " AND vl.rating_point >= $params[rating] ";
            }
            if ($params['rating'] == 8) {
                $where .= " AND vl.rating_point >= $params[rating] ";
            }
            if ($params['rating'] == 9) {
                $where .= " AND vl.rating_point >= $params[rating] ";
            }
        }

        if (isset($params['published']) && !empty($params['published'])) {
            if ($params['published'] == 1) {
                $where .= " AND vl.published_yt = 1";
            } else if ($params['published'] == 2) {
                $where .= " AND vl.published_fb = 1";
            } else if ($params['published'] == 3) {
                $where .= " AND vl.published_fb = 1";
            } else if ($params['published'] == 4) {
                $where .= " AND vl.published_fb = 1";
            } else if ($params['published'] == 5) {
                $where .= " AND vl.published_fb = 1";
            }

        }

        if (isset($params['mrss']) && !empty($params['mrss'])) {
            $where .= " AND v.mrss = $params[mrss] ";
        }

        if (isset($params['stage']) && !empty($params['stage'])) {
            if ($params['stage'] == 3) {
                $where .= " AND vl.status = 3 ";
                $where .= " AND vl.client_id = 0 ";
            } else if ($params['stage'] == 4) {
                $where .= " AND vl.status = 3 ";
                $where .= " AND vl.client_id != 0 ";
                $where .= " AND u.password IS NULL ";
            } else if ($params['stage'] == 7) {
                $where .= " AND vl.status = 3 ";
                $where .= " AND vl.information_pending = 0 ";
            } else if ($params['stage'] == 13) {
                $where .= " AND vl.status = 3 ";
                $where .= " AND vl.information_pending = 1 
            AND vl.uploaded_edited_videos = 0
            AND ( #v.real_deciption_updated = 0
                v.question_video_taken IS NOT NULL
                AND v.question_when_video_taken IS NOT NULL
                AND v.question_video_context IS NOT NULL
                #AND v.question_video_information IS NOT NULL 
             ) ";
            } else if ($params['stage'] == 6) {
                $where .= " AND vl.status = 6 ";
                $where .= " AND vl.information_pending = 1
            AND vl.uploaded_edited_videos = 0
            AND ( v.real_deciption_updated = 1 
                AND v.question_video_taken IS NOT NULL
                AND v.question_when_video_taken IS NOT NULL
                AND v.question_video_context IS NOT NULL
                #AND v.question_video_information IS NOT NULL 
             ) ";
            } else if ($params['stage'] == 12) {
                $where .= " AND vl.status = 6 ";
                $where .= " AND vl.information_pending = 1
                AND vl.uploaded_edited_videos = 1
                AND ( v.real_deciption_updated = 1 
                AND v.question_video_taken IS NOT NULL
                AND v.real_deciption_updated IS NOT NULL
                AND v.question_video_context IS NOT NULL
                #AND v.question_video_information IS NOT NULL 
             ) ";
            } else {
                $where .= " AND vl.status = $params[stage] ";
            }

        }
        $result = $this->report->getReports(
            '
            vl.unique_key,
            CONCAT(vl.first_name," ",vl.last_name) as client_name,
            vl.first_name,
            vl.last_name,
            vl.phone,
            vl.email as client_email,
            vl.video_title,
            vl.video_url,
            vl.status,
            u.password,
            u.email,
            u.mobile,
            u.paypal_email,
            vl.client_id,
            vl.information_pending,
            v.video_verified,
            v.description,
            vl.uploaded_edited_videos,
            vl.message,
            v.tags,
            vl.confidence_level,
            u.full_name,
            vl.published_yt,
            vl.published_fb,
            vl.rating_point,
            vl.revenue_share,
            v.title,
            v.youtube_id,
            v.facebook_id,
            vl.id as lead_id,
            ev.portal_thumb,
            rv.url as raw_video_url,
            rv.s3_url,
            v.mrss,
            v.real_deciption_updated,
            DATE_FORMAT(vl.created_at,"%m/%d/%Y %H:%i %p") AS created_at,
            DATE_FORMAT(vl.updated_at,"%m/%d/%Y %H:%i %p") AS updated_at,
            DATE_FORMAT(vl.closing_date,"%m/%d/%Y %H:%i %p") AS closing_date
            ',
            $search, $start, $limit, $orderby, $params['columns'], $where);
        //echo $this->db->last_query();exit;
        $resultCount = $this->report->getReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where);
        $count = $resultCount->num_rows();
        $response = array();
        $data = array();
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id=' . $adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id = $check_res->result();
        $role_id = $check_role_id[0]->admin_role_id;


        //if($role_id == '11'){
        $pieChart = $this->report->getPieReports('*', '', 0, 0, 'vl.rating_point DESC',$params['columns'], $where);

        $now = new DateTime("7 days ago", new DateTimeZone('America/New_York'));
        $interval = new DateInterval('P1D'); // 1 Day interval
        $period = new DatePeriod($now, $interval, 7); // 7 Days
        $i = 0;
        $gWhere = '';
        if (isset($params['type']) && !empty($params['type'])) {
            if ($params['type'] == '-1') {
                $gWhere .= " AND vl.staff_id IS NULL";
            } else {
                $gWhere .= " AND vl.staff_id = $params[type]";
            }
        }
        $weekly = array();
        foreach ($period as $day) {
            $key = $day->format('Y-m-d');
            $where_date = $gWhere." AND DATE(vl.created_at) = '$key'";
            $where_update_date = $gWhere." AND DATE(vl.updated_at) = '$key'";
            $where_lead = $gWhere." AND vl.status >= 1 AND DATE(vl.created_at) = '$key'";
            $day_key = date('l', strtotime($key));
            $day_date = date('Y-m-d', strtotime($key));

            $dealCount = $this->report->getleadReports('*', '', 0, 0, 'vl.id DESC', '', $where_date);
            $signedCount = $this->report->getsignedReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where_date);
            $rateCount = $this->report->getNotRateReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where_date);
            $actionCount = $this->report->getTakeActionReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where_date);
            $signedRejectCount = $this->report->getsignedAndrejectReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where_date);
            $acquiredRejectCount = $this->report->getAcquiredReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where_date);

            $rejectedCount = $this->report->getrejectedReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where_date);
            $canceledCount = $this->report->getCancelReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where_date);
            $poorCount = $this->report->getPoorReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where_date);
            $notCount = $this->report->getNotInterestReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $where_date);
            $weekly['deal'][$i]['y'] = $dealCount->num_rows();
            $weekly['deal'][$i]['label'] = $day_key . '-' . $day_date;
            $weekly['signed'][$i]['y'] = $signedCount->num_rows();
            $weekly['signed'][$i]['label'] = $day_key . '-' . $day_date;
            $weekly['not_rated'][$i]['y'] = $rateCount->num_rows();
            $weekly['not_rated'][$i]['label'] = $day_key . '-' . $day_date;
            $weekly['action_required'][$i]['y'] = $actionCount->num_rows();
            $weekly['action_required'][$i]['label'] = $day_key . '-' . $day_date;
            $weekly['signed_reject'][$i]['y'] = $signedRejectCount->num_rows();
            $weekly['signed_reject'][$i]['label'] = $day_key . '-' . $day_date;
            $weekly['acquired'][$i]['y'] = $acquiredRejectCount->num_rows();
            $weekly['acquired'][$i]['label'] = $day_key . '-' . $day_date;
            $weekly['rejected'][$i]['y'] = $rejectedCount->num_rows();
            $weekly['rejected'][$i]['label'] = $day_key . '-' . $day_date;
            $weekly['cancel'][$i]['y'] = $canceledCount->num_rows();
            $weekly['cancel'][$i]['label'] = $day_key . '-' . $day_date;
            $weekly['poor'][$i]['y'] = $poorCount->num_rows();
            $weekly['poor'][$i]['label'] = $day_key . '-' . $day_date;
            $weekly['interest'][$i]['y'] = $notCount->num_rows();
            $weekly['interest'][$i]['label'] = $day_key . '-' . $day_date;

            $i++;

        }
        /*   print_r($deal_data);
           exit();*/
        $month_now = new DateTime("30 days ago", new DateTimeZone('America/New_York'));
        $month_interval = new DateInterval('P1D'); // 1 Day interval
        $month_period = new DatePeriod($month_now, $month_interval, 30); // 7 Days
        $i = 0;
        $start_month = 1;
        $end_month = date('m');
        $y = date('Y');
        $month = array();
        for($start_month; $start_month <= $end_month; $start_month++){
            $first_date = date('Y-m-01',strtotime(date("$y-$start_month-01")));
            $last_date = date('Y-m-t',strtotime(date("$y-$start_month-01")));
            $m = date('F Y',strtotime(date("$y-$start_month-01")));
            //$month_key = $month_day->format('Y-m-d');
            $month_where_date = $gWhere." AND DATE(vl.created_at) BETWEEN '$first_date' AND '$last_date'";
            $month_where_update_date = $gWhere." AND DATE(vl.updated_at) BETWEEN '$first_date' AND '$last_date'";
            $month_where_lead = $gWhere." AND vl.status >= 1 AND DATE(vl.created_at) BETWEEN '$first_date' AND '$last_date'";
            $month_day_key = $m;

            $dealCount = $this->report->getleadReports('*', '', 0, 0, 'vl.id DESC', '', $month_where_date);
            $signedCount = $this->report->getsignedReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $month_where_date);
            $rateCount = $this->report->getNotRateReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $month_where_date);
            $actionCount = $this->report->getTakeActionReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $month_where_date);
            $signedRejectCount = $this->report->getsignedAndrejectReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $month_where_date);
            $acquiredRejectCount = $this->report->getAcquiredReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $month_where_date);

            $rejectedCount = $this->report->getrejectedReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $month_where_date);
            $canceledCount = $this->report->getCancelReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $month_where_date);
            $poorCount = $this->report->getPoorReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $month_where_date);
            $notCount = $this->report->getNotInterestReports('*', '', 0, 0, 'vl.id DESC', $params['columns'], $month_where_date);
            $month['deal'][$i]['y'] = $dealCount->num_rows();
            $month['deal'][$i]['label'] = $month_day_key;
            $month['signed'][$i]['y'] = $signedCount->num_rows();
            $month['signed'][$i]['label'] = $month_day_key;
            $month['not_rated'][$i]['y'] = $rateCount->num_rows();
            $month['not_rated'][$i]['label'] = $month_day_key;
            $month['action_required'][$i]['y'] = $actionCount->num_rows();
            $month['action_required'][$i]['label'] = $month_day_key;
            $month['signed_reject'][$i]['y'] = $signedRejectCount->num_rows();
            $month['signed_reject'][$i]['label'] = $month_day_key;
            $month['acquired'][$i]['y'] = $acquiredRejectCount->num_rows();
            $month['acquired'][$i]['label'] = $month_day_key;
            $month['rejected'][$i]['y'] = $rejectedCount->num_rows();
            $month['rejected'][$i]['label'] = $month_day_key;
            $month['cancel'][$i]['y'] = $canceledCount->num_rows();
            $month['cancel'][$i]['label'] = $month_day_key;
            $month['poor'][$i]['y'] = $poorCount->num_rows();
            $month['poor'][$i]['label'] = $month_day_key;
            $month['interest'][$i]['y'] = $notCount->num_rows();
            $month['interest'][$i]['label'] = $month_day_key;
            $i++;
        }


        $response['code'] = 200;
        $response['message'] = 'Pie Chart';
        $response['data'] = $pieChart;
        $response['week'] = $weekly;
        $response['month'] = $month;

        echo json_encode($response);
        exit;
    }
	
	public function exception_reports_listing(){
		$data = $this->db->select('*')->from('video_leads')->get();
		$response['data'] = $data->result_array();
		$response['code'] = 200;
		$response['message'] = 'Listing';
		$response['error'] = '';
		$response['recordsTotal'] = $data->num_rows();
		$response['recordsFiltered'] = $data->num_rows();
		echo json_encode($response);
		exit;	
	}
	
	public function _exception_reports_listing(){
		
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'reports');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$params = $this->security->xss_clean($this->input->get());
		$search = '';
		$orderby = 'vl.id DESC';
		$start = 0;
		$limit = 0;
		$where = NULL;
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

		if(isset($params['type']) && !empty($params['type'])){
			if($params['type'] == 1){
				$where .= " AND vl.status <= 3";
			}else if($params['type'] == 2){
				$where .= " AND vl.status > 3";
			}

		}

        if((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))){
            $date_from = date('Y-m-d',strtotime($params['date_from']));
            $date_to = date('Y-m-d',strtotime($params['date_to']));
            $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";

        }else if((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && empty($params['date_to']))){
            $date_from = date('Y-m-d',strtotime($params['date_from']));
            $where .= " AND DATE(vl.created_at) = '$date_from' ";
        }else if((isset($params['date_from']) && empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))){
            $date_to = date('Y-m-d',strtotime($params['date_to']));
            $where .= " AND DATE(vl.created_at) = '$date_to' ";
        }

        if((isset($params['closing_date_from']) && !empty($params['closing_date_from'])) && (isset($params['closing_date_to']) && !empty($params['closing_date_to']))){
            $closing_date_from = date('Y-m-d',strtotime($params['closing_date_from']));
            $closing_date_to = date('Y-m-d',strtotime($params['closing_date_to']));
            $where .= " AND DATE(vl.closing_date) BETWEEN '$closing_date_from' AND '$closing_date_to' ";

        }else if((isset($params['closing_date_from']) && !empty($params['closing_date_from'])) && (isset($params['closing_date_to']) && empty($params['closing_date_to']))){
            $closing_date_from = date('Y-m-d',strtotime($params['closing_date_from']));
            $where .= " AND DATE(vl.closing_date) = '$closing_date_from' ";
        }else if((isset($params['closing_date_from']) && empty($params['closing_date_from'])) && (isset($params['closing_date_to']) && !empty($params['closing_date_to']))){
            $closing_date_to = date('Y-m-d',strtotime($params['closing_date_to']));
            $where .= " AND DATE(vl.closing_date) = '$closing_date_to' ";
        }

        if(isset($params['rating']) && !empty($params['rating'])){
            $where .= " AND vl.rating_point = $params[rating] ";
        }

        if(isset($params['published']) && !empty($params['published'])){
            if($params['published'] == 1){
                $where .= " AND vl.published_yt = 1";
            }else if($params['published'] == 2){
                $where .= " AND vl.published_fb = 1";
            }

        }

        if(isset($params['mrss']) && !empty($params['mrss'])){
            $where .= " AND v.mrss = $params[mrss] ";
        }

        if(isset($params['stage']) && !empty($params['stage'])){
            if($params['stage'] == 3){
                $where .= " AND vl.status = 3 ";
                $where .= " AND vl.client_id = 0 ";
            }else if($params['stage'] == 4){
                $where .= " AND vl.status = 3 ";
                $where .= " AND vl.client_id != 0 ";
                $where .= " AND u.password IS NULL ";
            }else if($params['stage'] == 7){
                $where .= " AND vl.status = 3 ";
                $where .= " AND vl.information_pending = 0 ";
            }else if($params['stage'] == 13){
                $where .= " AND vl.status = 3 ";
                $where .= " AND vl.information_pending = 1 
	        AND vl.uploaded_edited_videos = 0
	        AND ( #v.real_deciption_updated = 0
	            v.question_video_taken IS NOT NULL
	            AND v.question_when_video_taken IS NOT NULL
	            AND v.question_video_context IS NOT NULL
	            #AND v.question_video_information IS NOT NULL 
	         ) ";
            }else if($params['stage'] == 6){
                $where .= " AND vl.status = 6 ";
                $where .= " AND vl.information_pending = 1
	        AND vl.uploaded_edited_videos = 0
	        AND ( v.real_deciption_updated = 1 
	            AND v.question_video_taken IS NOT NULL
	            AND v.question_when_video_taken IS NOT NULL
	            AND v.question_video_context IS NOT NULL
	            #AND v.question_video_information IS NOT NULL 
	         ) ";
            }else if($params['stage'] == 12){
                $where .= " AND vl.status = 6 ";
                $where .= " AND vl.information_pending = 1
	        AND vl.uploaded_edited_videos = 1
	        AND ( v.real_deciption_updated = 1 
	            AND v.question_video_taken IS NOT NULL
	            AND v.real_deciption_updated IS NOT NULL
	            AND v.question_video_context IS NOT NULL
	            #AND v.question_video_information IS NOT NULL 
	         ) ";
            }else{
                $where .= " AND vl.status = $params[stage] ";
            }

        }

		$result = $this->report->getExceptionReports(
			'
			vl.unique_key,
			CONCAT(vl.first_name," ",vl.last_name) as client_name,
			vl.first_name,
			vl.last_name,
			vl.phone,
			vl.email as client_email,
			vl.video_title,
			vl.video_url,
			vl.status,
			u.password,
			u.email,
			u.mobile,
			u.paypal_email,
			vl.client_id,
			vl.information_pending,
			v.video_verified,
			v.description,
			vl.uploaded_edited_videos,
			vl.message,
			v.tags,
			vl.confidence_level,
			u.full_name,
			vl.published_yt,
			vl.published_fb,
			vl.rating_point,
			vl.revenue_share,
			v.title,
			v.youtube_id,
			v.facebook_id,
			vl.id as lead_id,
			ev.portal_thumb,
			rv.url as raw_video_url,
			rv.s3_url,
			v.mrss,
			v.real_deciption_updated,
			DATE_FORMAT(vl.created_at,"%m/%d/%Y %H:%i %p") AS created_at,
			DATE_FORMAT(vl.updated_at,"%m/%d/%Y %H:%i %p") AS updated_at,
			DATE_FORMAT(vl.closing_date,"%m/%d/%Y %H:%i %p") AS closing_date
			',
			$search,$start,$limit,$orderby,$params['columns'],$where);
		//echo $this->db->last_query();exit;
		$resultCount = $this->report->getExceptionReports('*','',0,0,'vl.id DESC',$params['columns'],$where);
		$response = array();
		$data = array();

		foreach($result->result() as $row){

			$r = array();


            $r[] = $row->unique_key;
            $r[] = $row->client_name .' - '.$row->video_title;
            $r[] = $row->closing_date;
            $stage = 'Pending Lead';
            if($row->status == 10){
				$stage = 'Lead Rated';
			}else if($row->status == 5){
				$stage = 'Poor Rating Video';
			}else if($row->status == 2){
				$stage = 'Contract Sent';
			}else if($row->status == 3){
				$stage = 'Contract Signed';
			}else if($row->status == 3 && (!is_null($row->client_id ) && $row->client_id > 0) && is_null($row->password)){
				$stage = 'Account Created';
			}else if($row->status == 3 && (!is_null($row->client_id ) && $row->client_id > 0) && !is_null($row->password) && $row->information_pending == 0){
				$stage = 'Deal Information Pending';
			}else if($row->status == 6 && (!is_null($row->client_id ) && $row->client_id > 0) && !is_null($row->password) && $row->information_pending == 1 && $row->video_verified == 1 && $row->uploaded_edited_videos == 0){
				$stage = 'Upload Edited Videos';
			}
			else if($row->status == 6 && (!is_null($row->client_id ) && $row->client_id > 0) && !is_null($row->password) && $row->information_pending == 1 && $row->video_verified == 1 && $row->uploaded_edited_videos == 1){
				$stage = 'Distribute Edited Videos';
			}else if($row->status == 8){
				$stage = 'Closed Won';
			}else if($row->status == 9){
				$stage = 'Closed Lost';
			}else if($row->status == 11){
				$stage = 'Not Interested';
			}
			$r[] = $stage;
			$r[] = $row->created_at;
			/*$r[] = $row->updated_at;
			$r[] = 'N/A';*/
			$title = $row->video_title;
			if(!empty($row->title)){
				$title = $row->title;
			}
			$r[] = $title;
			$descrition = 'N/A';
			if(!empty($row->description)){
				$descrition = $row->description;
			}else{
				$descrition = $row->message;
			}
			$r[] = $descrition;
			$tags = 'N/A';
			if(!empty($row->tags)){
				$tags = $row->tags;
			}
			$r[] = $tags;
			$r[] = $row->confidence_level;
			$r[] = (in_array($row->status, [3, 6, 7, 8]))?('<a href="'.base_url().'view_contract/'.$row->lead_id.'">View Contract</a>'):('N/A');
			$r[] = $row->portal_thumb;			
			$r[] = $row->video_url;
			$r[] = $row->raw_video_url;			
			$r[] = $row->s3_url;						
			$real_deciption_updated = 'NO';
			if($row->real_deciption_updated == 1){
				$real_deciption_updated = "Yes";
			}
			$r[] = $real_deciption_updated;
			$ClientName = 'N/A';
			if(!empty($row->full_name)){
				$ClientName = $row->full_name;
			}else{
				$ClientName = $row->client_name;
			}
			$r[] = $ClientName;
			//$r[] = 'N/A';
			$r[] = 'N/A';
			$r[] = 'N/A';
			$publish = '';

			if($row->published_yt == 1){
				$publish .= 'YouTube,';
			}
			if($row->published_fb == 1){
				$publish .= 'Facbook,';
			}
			$r[] = rtrim($publish,',');
			$ratingPoint = 'N/A';
			if($row->rating_point > 0){
				$ratingPoint = $row->rating_point;
			}
			$r[] = $ratingPoint;
			$revenueShare = 'N/A';
			if($row->revenue_share > 0){
				$revenueShare = $row->revenue_share.'%';
			}
			$r[] = $revenueShare;

			/*$r[] = 'N/A';
			$r[] = 'N/A';
			$r[] = 'N/A';*/
			$email = $row->client_email;
			if(!empty($row->email)){
				$email = $row->email;
			}
			$r[] = $email;
			$mobile = $row->phone;
			if(!empty($row->mobile)){
				$mobile = $row->mobile;
			}
			$r[] = $mobile;

			$clientPaypalEmail = 'N/A';
			if(!empty($row->paypal_email)){
				$clientPaypalEmail = $row->paypal_email;
			}
			$r[] = $clientPaypalEmail;
			$youtubeLink = 'N/A';
			if(!empty($row->youtube_id)){
				$youtubeLink = "https://www.youtube.com/watch?v=$row->youtube_id";
			}
			$r[] = $youtubeLink;
			$facebookLink = 'N/A';
			if(!empty($row->facebook_id)){
				$facebookLink = "https://www.facebook.com/watch/?v==$row->facebook_id";
			}
			$r[] = $facebookLink;

			$mrss = 'NO';
			if($row->mrss == 1){
				$mrss = "Yes";
			}
			$r[] = $mrss;

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
    function array_to_csv_download() {
       $this->load->helper('download');
        // file name
        $filename = 'Reports_'.date('Ymd').'.csv';
        header('Pragma: public');     // required
        header('Expires: 0');         // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private',false);
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');

        // get data
        $usersData = $this->report->getFullDetails();

        // file creation
        $file = fopen('php://output', 'w');

        $header = array("first_name","last_name","email","video_title","status","description","tags","video_url","thumbnail","mrss","mrss_categories","unique_key","created_at","rating_point","revenue_share","shotVideo","haveOrignalVideo","confidence_level","reject_comments","video_comment","raw url","raw_video s3_url","raw_video s3_document_url","portal_url","portal_thumb","Youtube_id","Facebook_id");
        fputcsv($file, $header);
        foreach ($usersData as $key=>$line){
            fputcsv($file,$line);
        }
/*        force_download($filename, $file);*/
        fclose($file);
        exit;


    }

}
