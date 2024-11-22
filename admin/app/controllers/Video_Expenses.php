<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_Expenses extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'expense';
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
			'assets/js/vexpense.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'video_expenses'),
            'can_add'=>role_permitted_html(false,'video_expenses','add_video_expense'),
            'can_edit'=>role_permitted_html(false,'video_expenses','update_video_expense'),
            'can_delete'=>role_permitted_html(false,'video_expenses','delete_video_expense'),

        );
		$this->load->model('Video_Expense_Model','expense');
		$this->load->model('Video_Model','video');
		$this->load->model('Earning_Type_Model','earning_type');
		$this->load->model('Social_Sources_Model','source');
		$this->load->model('User_Model','user');

    }
	public function index()
	{
		auth();
        role_permitted(false,'video_expenses');
        $params = $this->security->xss_clean($this->input->get());
        $video_id = 0;
        if(isset($params['video_id'])){
            $video_id = $params['video_id'];
        }
		$this->data['title'] = 'Video Expense Management';
		$this->data['video_id'] = $video_id;
        $this->data['videosAcitve'] = $this->video->getAllVideosActive(1,'v.id,v.title');

		$this->data['content'] = $this->load->view('video_expense/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function video_expense_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'video_expenses');
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
		
		$result = $this->expense->getAllExpense($video_id,'ve.id,v.title,ve.expense_amount,DATE_FORMAT(ve.expense_date,\'%M %d, %Y\') as expense_date,ve.expense_detail,ve.status',$search,$start,$limit,$orderby,$params['columns']);
		//echo $this->db->last_query();exit;
		$resultCount = $this->expense->getAllExpense($video_id);
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Video Expense" href="javascript:void(0);" class="edit-expense" data-id="' . $row->id . '" data-title="' . $row->title . '"><i class="material-icons">&#xE254;</i></a> ';

            }
            if($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete Video Expense" href="javascript:void(0);" class="delete-expense" data-title="' . $row->title . '" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';

            }
            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }
			if($video_id == 0){
                $r[] = $row->title;
            }
			$r[] = $row->expense_date;
			$r[] = '$'.$row->expense_amount;
			$r[] = $row->expense_detail;
			/*$status = 'Pending Approvel';
			if($row->status == 1){
                $status = 'Approved';
            }elseif ($row->status == 1){
                $status = 'Rejected';
            }
			$r[] = $status;*/




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


	

	public function get_video_expense(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'video_expenses');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Expense found!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->expense->getExpenseById($id,'ve.*');
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No expense found!';
			$response['error'] = 'No expense found!';
			$response['url'] = '';

		}else{

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}


    public function add_video_expense(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'video_expenses','add_video_expense');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video Expense Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $dbData = $this->security->xss_clean($this->input->post());
        $this->validation->set_rules('video_id','Video','trim|required');
        $this->validation->set_rules('expense_amount','Expense Amount','trim|required');
        $this->validation->set_rules('expense_date','Expense Date','trim|required');
        $this->validation->set_rules('expense_detail','Expense Detail','trim|required');
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('expense_amount','expense_date','expense_detail','video_id');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{

            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['created_by'] = $this->sess->userdata('adminId');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->insert('video_expense',$dbData);
        }

        echo json_encode($response);
        exit;


    }


	public function update_video_expense(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'video_expenses','update_video_expense');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Video Expense Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->expense->getExpenseById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No expense found!';
			$response['error'] = 'No expense found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
        $dbData = $this->security->xss_clean($this->input->post());
        $this->validation->set_rules('expense_amount','Expense Amount','trim|required');
        $this->validation->set_rules('expense_date','Expense Date','trim|required');
        $this->validation->set_rules('expense_detail','Expense Detail','trim|required');
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('expense_amount','expense_date','expense_detail');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{

			
			unset($dbData['id']);
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->where('id',$id);
			$this->db->update('video_expense',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	

	public function delete_video_expense(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'video_expenses','delete_video_expense');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Video Expense deleted Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->expense->getExpenseById($id);

		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No expense found!';
			$response['error'] = 'No expense found!';
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
		$this->db->update('video_expense',$dbData);
		
		echo json_encode($response);
		exit;

	}
	






}
