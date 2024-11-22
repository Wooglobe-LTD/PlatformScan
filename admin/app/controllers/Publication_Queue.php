<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publication_Queue extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'publication_queue';
		$css = array(
			'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css',
            'assets/css/publishing_queue.css'
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
		);
		if(role_permitted_html(false)){
            $js[] = 'assets/js/publication_queue.js';
        }
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->load->model('Publication_Queue_Model', 'pub_model');
		$this->load->model('Categories_Model', 'category');
    }
	public function index()
	{
		auth();
		$this->data['title'] = 'Publication Queue';
		$this->data['content'] = $this->load->view('publication_queue/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function get_publication_queue()
    {
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$params = $this->security->xss_clean($this->input->get());
		$search = '';
		$orderby = '';
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
			if($params['columns'][$params['order'][0]['column']]['name'] == "vl.priority") {
				$orderby = 'CASE WHEN priority = "High" then 1 WHEN priority = "Medium" then 2 WHEN priority = "Low" then 3 END '.$params['order'][0]['dir'];
			}
			else {
				$orderby = $params['columns'][$params['order'][0]['column']]['name'].' '.$params['order'][0]['dir'];
			}
		}

		if((isset($params['date_type']) && !empty($params['date_type']))) {
			$date_type = $params['date_type'];
			if((isset($params['date_from']) && !empty($params['date_from'])) || (isset($params['date_to']) && !empty($params['date_to']))) {
				$where .= ' WHERE DATE('.$date_type.')';
				$date_from = date('Y-m-d',strtotime($params['date_from']));
				$date_to = date('Y-m-d',strtotime($params['date_to']));
			}
			if ((isset($params['date_from']) && !empty($params['date_from'])) && (isset($params['date_to']) && !empty($params['date_to']))) {
				$where .= " BETWEEN '$date_from' AND '$date_to'";
			}
			else if((isset($params['date_from']) && !empty($params['date_from']))) {
				$where .= " >= '$date_from'";
			}
			else if((isset($params['date_to']) && !empty($params['date_to']))) {
				$where .= " <= '$date_to'";
			}
		}
		if((isset($params['sort_by']) && !empty($params['sort_by']))) {
			if($params['sort_by'] == "editor") {
				$orderby = ' vl.uploaded_edited_videos ASC, vl.edited_datetime ASC, vl.verification_date ASC';
			}
			else {
				if(!empty($orderby)) {
					$orderby .= ', '.$params['sort_by'];
				}
				else {
					$orderby = ' '.$params['sort_by'];
				}
				$orderby .= (isset($params['sort_dir']) && !empty($params['sort_dir']))? ' '.$params['sort_dir']: ' DESC';
			}
		}
		
		$result = $this->pub_model->getPublicationQueue('
			vl.id as lead_id,
			vl.unique_key,
			vl.priority,
			vl.verification_date,
			vl.is_cn_updated,
			vl.cn_datetime,
			vl.uploaded_edited_videos,
			vl.edited_datetime,
			vl.published_yt,
			vps.publish_datetime,
			vps.publish_type,
			rv.dropbox_status,
			mp.video_id,
			mp.publication_date,
			vl.rating_point,
			vl.deleted,
			dc.researcher_comment,
			dc.manager_comment,
			vl.report_issue_type,
			vl.scout_resolved,
			vl.content_writer'
			,$search,$start,$limit,$where,$orderby,$params['columns']);
		// $resultCount = $this->pub_model->getPublicationQueue('*',$search,0,0,$where,$orderby,$params['columns']);
		$response = array();
		$data = array();
		
		$check = '<span class="material-icons" style="display:block; margin:auto; color:green;"> check_circle </span>';
		$uncheck = '<span class="material-icons" style="display:block; margin:auto; color:red;"> cancel </span>';

		foreach($result->result() as $row){
			$r = array();
			$temp = '<a href="'.base_url().'deal-detail/'.$row->lead_id.'" target="_blank">'.$row->unique_key.'</a>';
			$r[] = $temp;
			$r[] = $row->priority;
			$r[] = $row->verification_date;
			if ($row->is_cn_updated == 1) {
				$temp = '<span class="material-icons sheet_check_pos" title="'.$row->cn_datetime.'"> check_circle </span>';
			}
			else {
				$temp = '<span class="material-icons sheet_check_neg"> cancel </span>';
			}
			$r[] = $temp;
			if ($row->uploaded_edited_videos == 1) {
				$temp = '<span class="material-icons sheet_check_pos" title="'.$row->edited_datetime.'"> check_circle </span>';
			}
			else {
				$temp = '<span class="material-icons sheet_check_neg"> cancel </span>';
			}
			$r[] = $temp;
			if ($row->published_yt == 1) {
				$temp = '<span class="material-icons sheet_check_pos" title="'.$row->publish_datetime.'"> check_circle </span>';
			}
			else {
				$temp = '<span class="material-icons sheet_check_neg"> cancel </span>';
			}
			$r[] = $temp;
			// if ($row->publish_type == 'YouTube') {
			// 	$temp = '<span class="material-icons sheet_check_pos" title="'.$row->publish_datetime.'"> check_circle </span>';
			// }
			// else {
			// 	$temp = '<span class="material-icons sheet_check_neg"> cancel </span>';
			// }
			// $r[] = $temp;
			if ($row->dropbox_status == "success") {
				$temp = '<span class="material-icons sheet_check_pos sheet-dropbox-check" data-id="'.$row->unique_key.'"> check_circle </span>';
			}
			else {
				$temp = '<span class="material-icons sheet_check_neg"> cancel </span>';
			}
			$r[] = $temp;
			if ($row->publication_date != NULL) {
				$feeds = $this->pub_model->getMrssFeedsByVideoId($row->video_id);
				$pub_feed_titles = implode('<br>', array_column($feeds, 'title'));
				$feeds = $this->pub_model->getVideoEnqueuedFeedsByVideoId($row->video_id);
				$enq_feed_titles = implode('<br>', array_column($feeds, 'title'));
				$temp = '<div class="mrss_tooltip" data-position="above">
							<span class="material-icons sheet_check_pos">check_circle</span>
							<span class="tooltip_text">
								<h4>Pushed On</h4>'
								.$pub_feed_titles;
								if (!empty($enq_feed_titles)) {
									$temp .= '<h4>Enqueued On</h4>'.$enq_feed_titles;
								}
					$temp .= '</span>
						</div>';
			}
			else {
				$temp = '<span class="material-icons sheet_check_neg"> cancel </span>';
			}
			$r[] = $temp;
			$r[] = $row->rating_point;
			$temp = '<div class="parsley-row md-card-content">
						<select 
							id="content-writer-dpdn-'.$row->lead_id.'"
							data-id="'.$row->lead_id.'"
							name="content_writer_dpdn[]"
							class="content_writer_dpdn"
						>
							<option value="0">Select</option>
							<option value="45" ';
								$temp .= ($row->content_writer == 45)? 'selected="true"': '';
								$temp .= '>Ali Siddiqui
							</option>
							<option value="94" ';
								$temp .= ($row->content_writer == 94)? 'selected="true"': '';
								$temp .= '>Zaira Maryam
							</option>
						</select>
					</div>';
			$r[] = $temp;
			$temp = $this->pub_model->getCategoriesByLeadId($row->lead_id, 'mf.id, mf.title');
			$r[] = implode(', ', array_column($temp, 'title'));
			$r[] = ($row->deleted == 0)? "Active": "Cancelled";
			$r[] = $row->researcher_comment;
			$r[] = $row->manager_comment;

			$temp = array();
			$temp['is_deleted'] = $row->deleted;
			if ($row->report_issue_type != null && $row->scout_resolved == 0) {
				$temp['has_issue'] = true;
			}
			else {
				$temp['has_issue'] = false;
			}
			$r[] = $temp;

			$data[] = $r;
		}
		$response['code'] = 200;
		$response['message'] = 'Listing';
		$response['error'] = '';
		$response['data'] = $data;
		// $response['recordsTotal'] = $resultCount->num_rows();
		// $response['recordsFiltered'] = $resultCount->num_rows();
		echo json_encode($response);
		exit;
	}
	
	public function assign_content_writer()
	{
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false);
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$params = $this->security->xss_clean($this->input->post());

		$response['code'] = 200;
		$response['message'] = 'Lead Assigned Successfully';
		$response['error'] = '';
		
		if ($params['staff_id'] == 0) {
			$params['staff_id'] = NULL;
			$response['message'] = 'Lead Unassigned';
		}
		$this->db->set('content_writer', $params['staff_id']);
		$this->db->where('id',$params['lead_id']);
		$this->db->update('video_leads');
		
		echo json_encode($response);
		exit;
	}
}
