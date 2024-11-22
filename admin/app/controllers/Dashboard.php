<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends APP_Controller {

	public function __construct() {
        parent::__construct();
		auth();
		$this->data['active'] = 'dashboard';
		$css = array(
			'bower_components/weather-icons/css/weather-icons.min.css',
			'bower_components/metrics-graphics/dist/metricsgraphics.css',
			'bower_components/chartist/dist/chartist.min.css',
			'bower_components/uikit/css/uikit.almost-flat.min.css',
			'assets/css/style_switcher.min.css',
            'assets/skins/dropify/css/dropify.css'
		);
		$js = array(
			'bower_components/d3/d3.min.js',
			'bower_components/metrics-graphics/dist/metricsgraphics.min.js',
			//'bower_components/chartist/dist/chartist.min.js',
			'bower_components/peity/jquery.peity.min.js',
			'bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.min.js',
			'bower_components/countUp.js/dist/countUp.min.js',
			'bower_components/handlebars/handlebars.min.js',
			'assets/js/custom/handlebars_helpers.min.js',
			'bower_components/clndr/clndr.min.js',
			'assets/js/dashboard.js',
            'assets/js/custom/chart.js',
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);

		$this->load->model('Report_Model','report');
		$this->load->model('Dashboard_Model','dash');
    }
	public function index()
	{
		$this->data['title'] = 'Dashoard';
		$this->data['content'] = $this->load->view('dashboard',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}

    public function search()
    {

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
        $this->data['videos'] = $this->app->videosSearch($search,$start,$limit,$by,$sort);
        if(isset($this->data['videos']['detail'])){
            redirect('deal-detail/'.$this->data['videos']['single']->lead_id);
        }
        $this->data['content'] = $this->load->view('search',$this->data,true);
        $this->load->view('common_files/template',$this->data);

    }

    public function vl_report(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false);
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

        $staff_name = 'All';
        $pieWhere = '';

        $vl_chart1 = [];
        if ((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))) {
            $date_from = date('Y-m-d', strtotime($params['date_from']));
            $date_to = date('Y-m-d', strtotime($params['date_to']));
            $date_time = "BETWEEN '$date_from' AND '$date_to' ";
            $where .= " AND DATE(vl.created_at) BETWEEN '$date_from' AND '$date_to' ";

            if (isset($params['status']) && $params['status'] == 1) {
                $vl_chart1 = $this->dash->getLeadReportByTime($date_from, $date_to);
            }
            // else if (isset($params['status']) && $params['status'] == 2) {
            //     $vl_chart1 = $this->dash->getNotRateReports($date_from, $date_to);
            // }

        } else if ((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && empty($params['date_to']))) {
            $date_from = date('Y-m-d', strtotime($params['date_from']));
            $date_time = $date_from;
            $where .= " AND DATE(vl.created_at) = '$date_from' ";
        } else if ((isset($params['date_from']) && empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))) {
            $date_to = date('Y-m-d', strtotime($params['date_to']));
            $date_time = $date_to;
            $where .= " AND DATE(vl.created_at) = '$date_to' ";
        }

        $response = array();
        $data = array();
        $adminid = $this->sess->userdata('adminId');
        $check_query = 'select admin_role_id from admin where id=' . $adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id = $check_res->result();
        $role_id = $check_role_id[0]->admin_role_id;

        if (isset($params['status']) && $params['status'] == 1) {
            $vl_chart2 = $this->dash->getLeadsReport($where);
        }
        else if (isset($params['status']) && $params['status'] == 2) {
            $vl_chart2 = $this->dash->getNotRatedReports($where);
        }
        else if (isset($params['status']) && $params['status'] == 3) {
            $vl_chart2 = $this->dash->getsignedReports($where);
        }
        else if (isset($params['status']) && $params['status'] == 4) {
            $vl_chart2 = $this->dash->getAcquiredReports($where);
        }

        $response['code'] = 200;
        $response['message'] = 'Leads Charts';
        $response['vl_chart1'] = $vl_chart1;
        $response['vl_chart2'] = $vl_chart2;

        echo json_encode($response);
        exit;
    }
	
	public function logout()
	{
		$this->sess->sess_destroy();
		redirect($this->data['url']);
	}

	
	
}
