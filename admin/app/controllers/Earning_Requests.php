<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Earning_Requests extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'earning_requests';
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
			'assets/js/earning_request.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'earning_requests'),
            'can_edit'=>role_permitted_html(false,'earning_requests','update_earning_request'),

        );
		$this->load->model('Earning_Model','earning');
		$this->load->model('Video_Model','video');
		$this->load->model('Earning_Type_Model','earning_type');
		$this->load->model('Social_Sources_Model','source');
		$this->load->model('User_Model','user');

    }
	public function index()
	{
		auth();
        role_permitted(false,'earning_requests');
        $this->data['title'] = 'Earning Request Management';
		$this->data['content'] = $this->load->view('earning_requests/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function earning_requests_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'earning_requests');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$params = $this->security->xss_clean($this->input->get());
		$search = '';
		$orderby = '';
        $video_id = 0;
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
        if(isset($params['video_id'])){
            $video_id = $params['video_id'];
        }
		if(isset($params['order'])){
			$orderby = $params['columns'][$params['order'][0]['column']]['name'].' '.$params['order'][0]['dir'];
		}
		
		$result = $this->earning->getAllEarningRequests('e.id,v.id AS vid,v.title,e.earning_amount,e.expense_amount,e.wooglobe_net_earning,e.actual_amount,e.wooglobe_total_share,e.revenue_share_amount,e.client_net_earning,DATE_FORMAT(e.earning_date,\'%M %d, %Y\') as earning_date,et.earning_type,case when (e.status = 1) THEN "Approved" ELSE "Unapproved" END as status,case when (e.paid = 1) THEN "Paid" ELSE "Unpaid" END as payment_mode,ss.sources,u.full_name,c.symbol,c1.symbol as symbolu,e.transaction_id,e.transaction_detail,e.expense,e.expense_detail,v.lead_id, vl.unique_key',$search,$start,$limit,$orderby,$params['columns']);
		//echo $this->db->last_query();exit;
		$resultCount = $this->earning->getAllEarningRequests();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
			$links = '<input type="checkbox" class="select_earning_row" data-id="' . $row->id . '" value="1" />';
            $links .= '| <a title="Play Video" href="javascript:void(0);" class="play-video" data-id="'.$row->vid.'"><i class="material-icons">&#xE04A;</i></a> ';
            /*if($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Earning" href="javascript:void(0);" class="edit-earning" data-id="' . $row->id . '" data-title="' . $row->title . '"><i class="material-icons">&#xE254;</i></a> ';

            }*/
            if($this->data['assess']['can_edit']) {
                $links .= '| <a title="Approve Earning" href="javascript:void(0);" class="status-earning" data-status="1" data-id="' . $row->id . '"><i class="material-icons">&#xE5CA;</i></a> | <a title="Reject Earning" href="javascript:void(0);" class="status-earning" data-status="2" data-id="' . $row->id . '"><i class="material-icons">block</i></a>';

            }
            /* if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                 $r[] = $links;
             }*/


            $r[] = $links;
			$r[] = '<a target="_blank" href="'.base_url('deal-detail/'.$row->lead_id).'">'.$row->title.'</a>';
			$r[] = $row->unique_key;
			$r[] = $row->earning_date;
			$r[] = $row->symbol.round($row->earning_amount,2);
			$r[] = $row->symbol.round($row->client_net_earning,2);
			$r[] = $row->symbol.round($row->wooglobe_total_share,2);
			$r[] = $row->symbol.round($row->actual_amount,2);
            $r[] = $row->symbol.round($row->expense_amount,2);
            $r[] = $row->expense_detail;
			$r[] = $row->earning_type;
            $r[] = $row->transaction_id;
            $r[] = $row->transaction_detail;


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

	public function update_earning_request(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'earning_requests','update_earning_request');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Earning Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->earning->getEarningById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No earning found!';
			$response['error'] = 'No earning found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
        $dbData = $this->security->xss_clean($this->input->post());


			
        unset($dbData['id']);
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->sess->userdata('adminId');
        $this->db->where('id',$id);
        $this->db->update('earnings',$dbData);

		
		echo json_encode($response);
		exit;

	}
	
	public function update_earning_request_bulk(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'earning_requests','update_earning_request');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Earnings Updated Successfully!';
		$response['error'] = '';
		
		$ids = $this->security->xss_clean($this->input->post('ids'));
		$dbData = $this->security->xss_clean($this->input->post());
		unset($dbData['ids']);
		$dbData['updated_at'] = date('Y-m-d H:i:s');
		$dbData['updated_by'] = $this->sess->userdata('adminId');

		foreach ($ids as $id) {
			$result = $this->earning->getEarningById($id);
			if(!$result){
				$response['code'] = 201;
				$response['message'] = 'Earning not found!';
				$response['error'][] = $id;
			}
			
			$this->db->where('id',$id);
			$this->db->update('earnings',$dbData);
		}

		echo json_encode($response);
		exit;
	}

}
