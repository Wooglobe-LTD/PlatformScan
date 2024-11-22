<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'pages';
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
			'assets/js/pages.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'pages'),
            'can_add'=>role_permitted_html(false,'pages','add_page'),
            'can_edit'=>role_permitted_html(false,'pages','update_page'),
            'can_delete'=>role_permitted_html(false,'pages','delete_page')
        );
		$this->load->model('Pages_Model','page');
        
    }
	public function index()
	{
		auth();
        role_permitted(false,'pages');
		$this->data['title'] = 'Pages Management';
		$this->data['content'] = $this->load->view('pages/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function pages_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'pages');
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
		
		$result = $this->page->getAllPages('ctp.id,ctp.title,case when (ctp.status = 1) THEN "Active" ELSE "Inactive" END as status,ctp.slug',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->page->getAllPages();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Page" href="' . base_url('edit_page/' . $row->id) . '" class="edit-page" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
            }
            if($this->data['assess']['can_edit']) {
                $links .= '| <a title="Delete Page" href="javascript:void(0);" class="delete-page" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
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

	public function page_add()
	{
        auth();
        role_permitted(false,'pages','add_page');
		$this->data['title'] = 'Add New Page';
		$this->data['content'] = $this->load->view('pages/add',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}

	public function add_page(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'page','add_page');
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
			$response['url'] = $this->data['url'].'pages';
			$dbData = $this->security->xss_clean($this->input->post());
			$status = $dbData['status_u'];
			$dbData['status'] = $status;
			unset($dbData['status_u']);

            $dbData['slug'] = slug($dbData['title'],'content_thanks_page','slug');;
			$dbData['created_at'] = date('Y-m-d H:i:s');
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['created_by'] = $this->sess->userdata('adminId');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->insert('content_thanks_page',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	

	public function get_pages(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'pages');
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
		$result = $this->page->getPageById($id,'title');
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

	public function edit_page($id)
	{
		auth();
        role_permitted(false,'pages','edit_pages');
		$result = $this->page->getPageById($id,'ctp.*');
		if(!$result){

			redirect('pages');

		}
        $dataArry = array();
        foreach($result as $i=>$v){
            if($i == 'status'){
                $dataArry[$i]=$v;
            }

        }


        $this->data['edit_data'] = json_encode($dataArry,true);
		$this->data['title'] = 'Edit Pages';
		$this->data['id'] = $id;
		$this->data['data'] = $result;
		$this->data['content'] = $this->load->view('pages/edit',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}

	public function update_page(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'pages','edit_page');
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
		$result = $this->page->getPageById($id);
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
            $response['url'] = $this->data['url'].'pages';
            $dbData = $this->security->xss_clean($this->input->post());
            $status = $dbData['status_u'];
            $dbData['status'] = $status;
            unset($dbData['status_u']);
			

			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->where('id',$id);
			$this->db->update('content_thanks_page',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	

	public function delete_page(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'pages','delete_page');
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
		$result = $this->page->getPageById($id);

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
		$this->db->update('content_thanks_page',$dbData);
		
		echo json_encode($response);
		exit;

	}
	

}
