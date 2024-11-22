<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'categories';
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
            'assets/js/custom/dropify/dist/js/dropify.min.js',
            'assets/js/pages/forms_file_input.min.js',
			'assets/js/categories.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'categories'),
            'can_add'=>role_permitted_html(false,'categories','add_category'),
            'can_edit'=>role_permitted_html(false,'categories','update_category'),
            'can_delete'=>role_permitted_html(false,'categories','delete_category')
        );
		$this->load->model('Category_Model','category');
        $this->load->model('Categories_Model', 'mrss');

    }
	public function index()
	{
		auth();
        role_permitted(false,'categories');
		$this->data['title'] = 'Categories Management';
		$this->data['parents'] = $this->category->getParentCategories('id,title');
		$this->data['content'] = $this->load->view('categories/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function categories_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'categories');
        if($role_permitted_ajax){
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
		
		$result = $this->category->getAllCategories('c.id,c.title,case when (c.status = 1) THEN "Active" ELSE "Inactive" END as status,cc.title as ptitle',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->category->getAllCategories();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Categeory" href="javascript:void(0);" class="edit-category" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';

            }
            if($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete Category" href="javascript:void(0);" class="delete-category" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';

            }
            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }
			$r[] = $row->title;
			$r[] = $row->ptitle;
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

	public function add_category(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'categories','add_category');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'New Category Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('parent_id','Parent Category','trim|required');
		$this->validation->set_rules('title','Category Title','trim|required|alpha_numeric_spaces|callback_validate_title');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');
		$this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
		
		if($this->validation->run() === false){
			
			$fields = array('title','status','parent_id');
			$errors = array();
			foreach($fields as $field){
				$errors[$field] = form_error($field);
			}
			$response['code'] = 201;
			$response['message'] = 'Validation Errors!';
			$response['error'] = $errors;
			$response['url'] = '';
			
		}else{
			
			$dbData = $this->security->xss_clean($this->input->post());
			$dbData['slug'] = slug($dbData['title'],'categories','slug');
			$dbData['created_at'] = date('Y-m-d H:i:s');
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['created_by'] = $this->sess->userdata('adminId');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->insert('categories',$dbData);
			//create mrss category
			$dbData['type'] = 1;
			$dbData['url'] = $dbData['slug'];
			$this->db->insert('mrss_feeds',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	public function validate_title($title)
	{

        $title = $this->security->xss_clean($title);

		$parent_id = $this->security->xss_clean($this->input->post('parent_id'));
		
		if(!empty($title)){
			if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
				$result = $this->category->getCategoryByTitle($title,$parent_id);
				if($result->num_rows() > 0){
					$this->validation->set_message('validate_title','This category already exist in this category!');
					return false;
				}else{
					return true;
				}
			}else{
				$this->validation->set_message('validate_title','Only alphabet and number are allowed.');
				return false;
			}
		}else{
			$this->validation->set_message('validate_title','This field is required.');
			return false;
		}
		
	}

	public function get_category(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}

        $role_permitted_ajax = role_permitted_ajax(false,'categories');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Record found!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->category->getCategoryById($id,'id,title,status,parent_id');
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No Category found!';
			$response['error'] = 'No category found!';
			$response['url'] = '';

		}else{

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}

	public function update_category(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'categories','update_category');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'User Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->category->getCategoryById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No category found!';
			$response['error'] = 'No category found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
		$this->validation->set_rules('parent_id','Parent Category','trim|required');
		$this->validation->set_rules('title','Category Title','trim|required|alpha_numeric_spaces|callback_validate_title_edit['.$id.']');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');
		$this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
		
		if($this->validation->run() === false){
			
			$fields = array('title','status','parent_id');
			$errors = array();
			foreach($fields as $field){
				$errors[$field] = form_error($field);
			}
			$response['code'] = 201;
			$response['message'] = 'Validation Errors!';
			$response['error'] = $errors;
			$response['url'] = '';
			
		}else{
			
			$dbData = $this->security->xss_clean($this->input->post());
			unset($dbData['id']);
            //$dbData['slug'] = slug($dbData['title'],'categories','slug');
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->where('id',$id);
			$this->db->update('categories',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	public function validate_title_edit($title,$id)
	{

        $title = $this->security->xss_clean($title);

		$parent_id = $this->security->xss_clean($this->input->post('parent_id'));
		
		if(!empty($title)){
			if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
				$result = $this->category->getCategoryByTitle($title,$parent_id);
				if($result->num_rows() > 0){
					$result = $result->row();
					if($result->id == $id){

						return true;

					}else{

						$this->validation->set_message('validate_title_edit','This category already exist in this category!');
						return false;

					}
					
				}else{
					return true;
				}
			}else{
				$this->validation->set_message('validate_title_edit','Only alphabet and number are allowed.');
				return false;
			}
		}else{
			$this->validation->set_message('validate_title_edit','This field is required.');
			return false;
		}
		
	}

	public function delete_category(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'categories','delete_category');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Catgeory Deleted Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->category->getCategoryById($id);

		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No category found!';
			$response['error'] = 'No category found!';
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
		$this->db->update('categories',$dbData);
		
		echo json_encode($response);
		exit;

	}
	function clear_all_feeds_from_queue($video_id){
		if (!empty($video_id)) {
            $query = 'DELETE FROM mrss_queue 
                  WHERE video_id = '.$video_id;

            return $this->db->query($query);
        }
	}  

	// ajax request for MRSS video insights
    public function save_partners_and_categories_info ()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        if ($this->input->post()) {

            $post_array = $this->input->post();
            $db_data['publication_date'] = date('Y-m-d H:i:s');
            $video_id              = $post_array['video_id'];
            $id = 0;
            
			// clear all partner related info
            $this->clear_all_feeds($video_id);

			$this->clear_all_feeds_from_queue($video_id);

            if (isset($post_array['is_exclusive']) && !empty($post_array['is_exclusive'])) {

                $mrss_video_categories = $post_array['mrss_video_categories'];
                $partner_id            = $post_array['exclusive_partners_list'];

                if ($post_array['is_exclusive'] == 1) {      // single partner selected

                    foreach ($mrss_video_categories as $key => $val) {
                        $this->db->insert('mrss_queue', ['video_id' => $video_id, 'feed_id' => $val, 'exclusive_to_partner' => $partner_id, 'publication_date' => $db_data['publication_date']]);
                    }
                }
                else if ($post_array['is_exclusive'] == 2) { // multiple partners selected

                    $partner_list = $post_array['exclusive_partners_list'];

                    foreach ($mrss_video_categories as $key => $val) {
                        $this->db->insert('mrss_queue', ['video_id' => $video_id, 'feed_id' => $val, 'exclusive_to_partner' => 0, 'publication_date' => $db_data['publication_date']]);
                    }
                }
            }

            if (isset($post_array['general_categories']) && !empty($post_array['general_categories'])) {

                $categories_list = $post_array['general_categories'];

                foreach ($categories_list as $key => $category_id) {
                    $this->db->insert('mrss_queue', ['video_id' => $video_id, 'feed_id' => $category_id, 'exclusive_to_partner' => 0, 'publication_date' => $db_data['publication_date']]);
                }
            }




            $response['code'] = '200';
            $response['msg']  = 'Information succesfully updated';

            echo json_encode($response);
            exit;

			
        }
    }

    public function clear_all_feeds ($video_id = '')
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        if (!empty($video_id)) {
            $this->mrss->clearGeneralFeedsByVideoId($video_id);
            $this->mrss->clearPartnersExclusiveFeedsByVideoId($video_id);
        }

    }

    public function save_general_categories ()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        if ($this->input->post()) {

            $post_array = $this->input->post();

            $video_id = $post_array['video_id'];
            $category_ids = $post_array['mrss_video_categories'];

            $this->clear_all_feeds($video_id);

            foreach ($category_ids as $key => $category_id) {
                $this->db->insert('feed_video', ['video_id' => $video_id, 'feed_id' => $category_id, 'exclusive_to_partner' => 0]);
            }

        }
    }

    public function general_categories_listing ()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $categories_list = $this->mrss->getGeneralCategories();

        $response = array();
        $response['code'] = 200;
        $response['list'] = $categories_list;
        $response['error'] = '';
        $response['url'] = '';

        echo json_encode($response);
        exit;
    }
	
}
