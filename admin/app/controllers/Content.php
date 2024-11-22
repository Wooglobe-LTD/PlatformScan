<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Content extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'content';
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
            'bower_components/tinymce/tinymce.min.js',
			'assets/js/pages/forms_wysiwyg.js',
			'assets/js/pages/forms_file_upload.js',
			'assets/js/custom/dropify/dist/js/dropify.min.js',
			'assets/js/pages/forms_file_input.min.js',
			'assets/js/content.js',
			'assets/js/upload_edited_video.js',
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'content'),
            'can_add'=>role_permitted_html(false,'content','add_content'),
            'can_edit'=>role_permitted_html(false,'content','update_content'),
            'can_delete'=>role_permitted_html(false,'content','delete_content')
        );
		$this->load->model('Content_Model','content');
        
    }
	public function index()
	{
		auth();
        role_permitted(false,'content');
		$this->data['title'] = 'Content Management';
		$this->data['content'] = $this->load->view('content/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function conten_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'content');
        if($auth_ajax){
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
		
		$result = $this->content->getAllPages('c.id,c.title,case when (c.status = 1) THEN "Active" ELSE "Inactive" END as status',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->content->getAllPages();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Page" href="' . base_url('edit_content/' . $row->id) . '" class="edit-content" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
            }
            if($this->data['assess']['can_edit']) {
                //$links .= '| <a title="Delete Page" href="javascript:void(0);" class="delete-content" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
            }
            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }
			$r[] = $row->title;
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

	public function content_add()
	{
        auth();
        role_permitted(false,'content','add_content');
		$this->data['title'] = 'Add New Page';
		$this->data['content'] = $this->load->view('content/add',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}

	public function add_content(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'content','add_content');
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }

		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'New Page Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('title','Video Title','trim|required|alpha_numeric_spaces');
		//$this->validation->set_rules('description','Page Description','trim|required');
		$this->validation->set_rules('status_u','Status','trim|required');
		$this->validation->set_message('required','This field is required.');
		$this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
		if($this->validation->run() === false){
			
			$fields = array('title','status_u','description','meta_keywords','meta_description');
			$errors = array();
			foreach($fields as $field){
				$errors[$field] = form_error($field);
			}
			$response['code'] = 201;
			$response['message'] = 'Validation Errors!';
			$response['error'] = $errors;
			$response['url'] = '';
			
		}else{
			$response['url'] = $this->data['url'].'content';
			$dbData = $this->security->xss_clean($this->input->post());
			$status = $dbData['status_u'];
			$dbData['status'] = $status;
			unset($dbData['status_u']);


			$dbData['created_at'] = date('Y-m-d H:i:s');
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['created_by'] = $this->sess->userdata('adminId');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->insert('content',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	

	public function get_content(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'content');
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Page found!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->content->getPageById($id,'title');
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No page found!';
			$response['error'] = 'No page found!';
			$response['url'] = '';

		}else{

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}

	public function edit_content($id)
	{
		auth();
        role_permitted(false,'content','edit_content');
		$result = $this->content->getPageById($id,'c.*');
		if(!$result){

			redirect('content');

		}
        $dataArry = array();
        foreach($result as $i=>$v){
            if($i == 'status'){
                $dataArry[$i]=$v;
            }

        }


        $this->data['edit_data'] = json_encode($dataArry,true);
		$this->data['id'] = $id;
		$this->data['data'] = $result;
		/*if($id == 1){
			$this->data['title'] = 'Edit Home Page Content';
			$this->data['content'] = $this->load->view('content/home',$this->data,true);
		}else{*/
			$this->data['title'] = 'Edit Content';
			$this->data['content'] = $this->load->view('content/edit',$this->data,true);
		//}

		$this->load->view('common_files/template',$this->data);
	}

	public function update_content(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'content','edit_content');
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Page Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->content->getPageById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No page found!';
			$response['error'] = 'No page found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
        $this->validation->set_rules('title','Video Title','trim|required|alpha_numeric_spaces');
        //$this->validation->set_rules('description','Page Description','trim|required');
        $this->validation->set_rules('status_u','Status','trim|required');
        $this->validation->set_message('required','This field is required.');
        $this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
        if($this->validation->run() === false){

            $fields = array('title','status_u','description','meta_keywords','meta_description');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{
            $response['url'] = $this->data['url'].'content';
            $dbData = $this->security->xss_clean($this->input->post());
            $status = $dbData['status_u'];
            $dbData['status'] = $status;
            unset($dbData['status_u']);
			

			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->where('id',$id);
			$this->db->update('content',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	

	public function delete_content(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'content','delete_content');
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Page deleted Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->content->getPageById($id);

		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No page found!';
			$response['error'] = 'No page found!';
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
		$this->db->update('content',$dbData);
		
		echo json_encode($response);
		exit;

	}

	public function upload_video_banner_mp4(){

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

		$config['upload_path']          = './../uploads/home_banner/mp4/';
		$config['allowed_types']        = 'mp4|MP4';
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

			$response['url'] = 'uploads/home_banner/mp4/'.$data['file_name'];
		}


		echo json_encode($response);
		exit;

	}


	public function upload_video_banner_webm(){

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

		$config['upload_path']          = './../uploads/home_banner/webm/';
		$config['allowed_types']        = 'webm|WEBM';
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

			$response['url'] = 'uploads/home_banner/webm/'.$data['file_name'];
		}


		echo json_encode($response);
		exit;

	}

	public function update_home_banner(){
		$auth_ajax = auth_ajax();
		if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
		$role_permitted_ajax = role_permitted_ajax(false,'content','edit_content');
		if($auth_ajax){
			echo json_encode($role_permitted_ajax);
			exit;
		}
		$response = array();

		$response['code'] = 200;
		$response['message'] = 'Home Banner Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->content->getPageById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No page found!';
			$response['error'] = 'No page found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
		$this->validation->set_rules('banner_video_mp4','Banner MP4 Video','trim|required');
		$this->validation->set_rules('banner_video_webm','Banner WEBM Video','trim|required');
		$this->validation->set_rules('banner_h1','Banner Top Heading','trim|required');
		$this->validation->set_rules('banner_h2','Banner Bottom Heading','trim|required');
		$this->validation->set_message('required','This field is required.');
		if($this->validation->run() === false){

			$fields = array('banner_video_mp4','banner_video_webm','banner_h1','banner_h2','meta_description');
			$errors = array();
			foreach($fields as $field){
				$errors[$field] = form_error($field);
			}
			$response['code'] = 201;
			$response['message'] = 'Validation Errors!';
			$response['error'] = $errors;
			$response['url'] = '';

		}else{
			$this->load->helper('directory');
			$dbData = $this->security->xss_clean($this->input->post());

			unset($dbData['id']);
			$path1 = directory_map('./../uploads/home_banner/mp4/');
			$path2 = directory_map('./../uploads/home_banner/webm/');
			echo '<pre>';
			print_r($path1);
			print_r($path2);
			exit;

			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->where('id',$id);
			$this->db->update('content',$dbData);
		}

		echo json_encode($response);
		exit;
	}
	

}
