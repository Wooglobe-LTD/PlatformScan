<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CategoriesMrss extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'categories_mrss';
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
			'assets/js/categories_mrss3.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'categories_mrss'),
            'can_add'=>role_permitted_html(false,'categories_mrss','add_category'),
            'can_edit'=>role_permitted_html(false,'categories_mrss','update_category'),
            'can_delete'=>role_permitted_html(false,'categories_mrss','delete_category')
        );
		$this->load->model('Categories_Model','category');
        $this->load->model('MRSS_Queue_Model', 'mrss_queue');

        
    }
	public function index() {
		auth();
        role_permitted(false,'categories_mrss');
		$this->data['title'] = 'MRSS Feeds Management';
        $this->load->model('User_Model','user');
		$this->data['parents'] = $this->category->getParentCategories('id,title');
        $this->db->select(['id','full_name']);
        $query=$this->db->get('users');
        $query = $this->user->getAllUsers(2);
        $users=$query->result();
        $this->data['users']=$users;
		$this->data['content'] = $this->load->view('categories_mrss/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function categories_listing() {
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

	//case when (c.type = 1) THEN "Category" ELSE "Custom" as type2,
		$result = $this->category->getAllCategories('c.id,c.title,c.url,c.slug,c.feed_delay,c.feed_time,case when (c.type = 1) THEN "Category" when (c.type = 2) THEN "Story Content" ELSE "Partner" END as type,case when (c.status = 1) THEN "Active" ELSE "Inactive" END as status,cc.title as ptitle, c.partner_id,c.partner_type',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->category->getAllCategories();
        $feed_counts = $this->mrss_queue->getFeedCounts();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Categeory" href="javascript:void(0);" class="edit-category" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
            }
            if($this->data['assess']['can_edit']) {
                $links .= '| <a title="Preview Feed" href="' . $this->data['root'].'admin/mrss/'.$row->url . '/preview" class="preview-feed" data-url="' . $this->data['root'].'mrss/'.$row->url . '" data-id="' . $row->id . '"><i class="material-icons">&#xe417;</i></a> ';
            }
            if($this->data['assess']['can_delete'] and  $row->partner_id > 0) {
                $links .= '| <a title="Secure Feed" href="javascript:void(0);" class="secure-feed" id = "'. $row->id .'" data-id="' . $row->partner_id . '" data-url="' . $this->data['root'].'mrss/'.$row->url . '"><i class="material-icons">lock_open</i></a>';
            }
            if($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete Category" href="javascript:void(0);" class="delete-category" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
            }
            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }
            $partner = 'Category Feed';
            if($row->partner_id != 0){
                $partner = $this->category->getPartnerName($row->partner_id);
                $partner = $partner->full_name;
            }
			$r[] = $row->title;
			$r[] = $row->type;
            $r[] = $partner;
            $enqueued_videos = $feed_counts[$row->id];
            if($this->data['assess']['can_edit']) {
                $enqueued_videos .= '<a title="View Enqueued Videos" href="javascript:void(0);" class="view_enqueued_videos" data-id="' . $row->id . '"><i class="material-icons">&#xe417;</i></a>';
            }
            $r[] = $enqueued_videos;
            $r[] = $row->feed_delay == NULL ? 0 : $row->feed_delay;
            $r[] = $row->feed_time == "" ? "00:00-00:00" : $row->feed_time;

            if($row->partner_type == 2){
                $r[] = '<a target="_blank" href="'.$this->data['root'].'mrss_story/'.$row->url.'" >'.$this->data['root'].'mrss_story/'.$row->url.'</a>';
            }else{
                $r[] = '<a target="_blank" href="'.$this->data['root'].'mrss/'.$row->url.'" >'.$this->data['root'].'mrss/'.$row->url.'</a>';
            }

            if($row->partner_type == 2){
                $r[] = '<a target="_blank" href="'.$this->data['root'].'mrss_story/download/'.$row->url.'" >'.$this->data['root'].'mrss_story/download/'.$row->url.'</a>';
            }else{
                $r[] = '<a target="_blank" href="'.$this->data['root'].'mrss/mrss_partner_download/'.$row->url.'" >'.$this->data['root'].'mrss/mrss_partner_download/'.$row->url.'</a>';
            }

			$r[] = $row->status;

			$data[] = $r;
		}
        $_first_row = $result->row();
        $_id = $_first_row->id;

		$response['code'] = 200;
		$response['message'] = 'Listing';
		$response['error'] = '';
		$response['data'] = $data;
		$response['first_row_id'] = $_id;

		$response['recordsTotal'] = $resultCount->num_rows();
		$response['recordsFiltered'] = $resultCount->num_rows();
		echo json_encode($response);
		exit;
	}

	public function get_category() {

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
		$response['message'] = 'Record found!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->category->getCategoryById($id,'md.*,c.id,c.title,c.status,c.parent_id,c.partner_id as partner,c.url,c.type,c.pub_date,c.partner_type,c.feed_delay,c.feed_time');
        
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No Category found!';
			$response['error'] = 'No category found!';
			$response['url'] = '';

		}else{
            if(empty($result->feed_time)){
                $result->feed_time = "00:00-00:00";
            }

            $result->feed_time_from = explode("-", $result->feed_time)[0];
            $result->feed_time_to = explode("-", $result->feed_time)[1];

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}
	public function add_category() {
        
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'categories_mrss','add_category');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'New Category Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		//$this->validation->set_rules('parent_id','Parent Category','trim|required');
		$this->validation->set_rules('title','Category Title','trim|required|alpha_numeric_spaces|callback_validate_title');
        $type = $this->security->xss_clean($this->input->post('type'));
        if($type == 0){
            $this->validation->set_rules('partner_id','Partner','required');
        }

        $this->validation->set_rules('url','Category URL','trim|required|alpha_dash|callback_validate_url');

        //$this->validation->set_message('required','This field is required.');
		$this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
		$this->validation->set_message('is_unique','This category URL already exist');

		if($this->validation->run() === false){
			
			$fields = array('title','status','parent_id','url','partner_id');
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
            $title_slug = $dbData['title'];
			if($dbData['partner_id'] != ''){
                $this->db->select('full_name');
                $this->db->where('id',$dbData['partner_id']);
                $query=$this->db->get('users');
                $result=$query->result();
                $title_slug =  $result[0]->full_name."/".$dbData['title'];
            }


            
			$dbData['slug'] = slug($title_slug,'mrss_feeds','slug');
			$input_url = $dbData['url'] ;
            if($dbData['partner_id'] != ''){
                $dbData['url']=slug($result[0]->full_name,'mrss_feeds','url');
                $dbData['url']=$dbData['url']."/".slug($input_url,'mrss_feeds','url');
            }else{
                $dbData['url']=slug($input_url,'mrss_feeds','url');
            }
            
            $dbData['feed_time'] = $dbData['feed_time_from'].'-'.$dbData['feed_time_to'];
            if (strlen($dbData['feed_time']) != 11){
                $dbData['feed_time'] = "00:00-00:00";
            }
            
            unset($dbData['feed_time_from']);
            unset($dbData['feed_time_to']);

			$dbData['created_at'] = date('Y-m-d H:i:s');
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['created_by'] = $this->sess->userdata('adminId');
			$dbData['updated_by'] = $this->sess->userdata('adminId');

            $desc_info = $dbData["desc_info"];
            unset($dbData["desc_info"]);

            $this->db->insert('mrss_feeds',$dbData);
            
            $feed_id = $this->db->insert_id();

            unset($dbData);

            $dbData["feed_id"] = $feed_id;
            
            foreach($desc_info as $info){
                $dbData[$info] = 1;
            }

            $this->db->insert('mrss_info', $dbData);


		}
		
		echo json_encode($response);
		exit;

	}
	public function update_category() {

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'categories_mrss','update_category');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Category Updated Successfully!';
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
		//$this->validation->set_rules('parent_id','Parent Category','trim|required');
		$this->validation->set_rules('title','Category Title','trim|required|alpha_numeric_spaces|callback_validate_title_edit['.$id.']');
		$this->validation->set_rules('url','Category URL','trim|required|alpha_dash|callback_validate_url_edit['.$id.']');
		$this->validation->set_rules('status','Status','trim|required');


		$this->validation->set_message('required','This field is required.');
		$this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
		$this->validation->set_message('numeric','Only numbers are allowed.');
		
        
        ////// set message for time range 


		if($this->validation->run() === false){
			
			$fields = array('title','status','parent_id','url');
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
            $title_slug = $dbData['title'];
            if($dbData['partner_id'] != ''){
                $this->db->select('full_name');
                $this->db->where('id',$dbData['partner_id']);
                $query=$this->db->get('users');
                $result=$query->result();
                $title_slug =  $result[0]->full_name."/".$dbData['title'];
            }

            $dbData['slug'] = slug($title_slug,'mrss_feeds','slug');
            $input_url = $dbData['url'] ;
            if($dbData['partner_id'] != ''){
                $dbData['url']=slug($result[0]->full_name,'mrss_feeds','url');
                $dbData['url']=$dbData['url']."/".slug($input_url,'mrss_feeds','url');
            }else{
                $dbData['url']=slug($input_url,'mrss_feeds','url');
            }
			unset($dbData['id']);
            //$dbData['slug'] = slug($dbData['title'],'categories','slug');

            $dbData['feed_time'] = $dbData['feed_time_from'].'-'.$dbData['feed_time_to'];
            if (strlen($dbData['feed_time']) != 11){
                $dbData['feed_time'] = "00:00-00:00";
            }
            
            unset($dbData['feed_time_from']);
            unset($dbData['feed_time_to']);

			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['updated_by'] = $this->sess->userdata('adminId');

            $desc_info = $dbData["desc_info"];
            unset($dbData["desc_info"]);

			$this->db->where('id',$id);
			$this->db->update('mrss_feeds',$dbData);
            
            unset($dbData);
            $result = $this->db->query('SHOW COLUMNS FROM mrss_info')->result_array();

            foreach($result as $col){
                $field = $col["Field"];
                if($field == "id" || $field == "feed_id")
                    continue;

                $dbData[$field] = 0;
                if($desc_info != NULL && in_array($field, $desc_info)){
                    $dbData[$field] = 1;
                }
                
            }

			$res= $this->db->where('feed_id',$id)->get('mrss_info')->num_rows();
            if($res > 0){
                $this->db->where('feed_id',$id);
                $this->db->update('mrss_info', $dbData);
            }
            else{
                $dbData["feed_id"] = $id;
                $this->db->insert('mrss_info', $dbData);

            }

        }
		
		echo json_encode($response);
		exit;

	}
	public function delete_category() {

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'categories_mrss','delete_category');
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
		$this->db->update('mrss_feeds',$dbData);

        $this->db->where('feed_id',$id);
        $this->db->delete('feed_video');
		
		echo json_encode($response);
		exit;

	}

	public function validate_title($title) {

        $title = $this->security->xss_clean($title);

		$parent_id = $this->security->xss_clean($this->input->post('parent_id'));
        $partner_id = $this->security->xss_clean($this->input->post('partner_id'));
		
		if(!empty($title)){
			if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
				$result = $this->category->getCategoryByTitle($title, $partner_id);
				if($result->num_rows() > 0){
					$this->validation->set_message('validate_title','This category already exist!');
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
	public function validate_title_edit($title,$id) {

        $title = $this->security->xss_clean($title);

		$parent_id = $this->security->xss_clean($this->input->post('parent_id'));
        $partner_id = $this->security->xss_clean($this->input->post('partner_id'));
		
		if(!empty($title)){
			if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
				$result = $this->category->getCategoryByTitle($title, $partner_id);
				if($result->num_rows() > 0){
					$result = $result->row();
					if($result->id == $id){

						return true;

					}else{

						$this->validation->set_message('validate_title_edit','This category already exist!');
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
    public function validate_url($url) {

        $url = $this->security->xss_clean($url);

        $partner_id = $this->security->xss_clean($this->input->post('partner_id'));
        $mrss_feed_results='';
        if($partner_id){
            $mrss_feed_results = $this->db->query('SELECT full_name FROM users WHERE id = "'.$partner_id.'" AND deleted = 0')->result();
        }
        $partner_name='';
        if($mrss_feed_results) {
            if ($mrss_feed_results[0]) {
                $partner_name = $mrss_feed_results[0]->full_name;
            }
        }
        if($partner_name){
            $url =$partner_name.'/'.$url;
        }else{
            $url=$url;
        }
        if(!empty($url)){
            $result = $this->category->getCategoryByURL($url);
            if($result->num_rows() > 0){
                $this->validation->set_message('validate_url','This category URL already exist!');
                return false;

            }else{

                return true;

            }

        }else{
            $this->validation->set_message('validate_url','This field is required.');
            return false;
        }

    }
    public function validate_url_edit($url,$id) {

        $url = $this->security->xss_clean($url);

        $partner_id = $this->security->xss_clean($this->input->post('partner_id'));
        $mrss_feed_results='';
        if($partner_id){
            $mrss_feed_results = $this->db->query('SELECT full_name FROM users WHERE id = "'.$partner_id.'" AND deleted = 0')->result();
        }
        $partner_name='';
        if($mrss_feed_results) {
            if ($mrss_feed_results[0]) {
                $partner_name = $mrss_feed_results[0]->full_name;
            }
        }
        if($partner_name){
            $url =$partner_name.'/'.$url;
        }else{
            $url=$url;
        }
        if(!empty($url)){
            $result = $this->category->getCategoryByURL($url);
            if($result->num_rows() > 0){
                if ($id && $result->result_array()[0]["id"] == $id){
                    return true;
                }

                $this->validation->set_message('validate_url_edit','This category URL already exist!!!');
                return false;

            }else{

                return true;

            }

        }else{
            $this->validation->set_message('validate_url_edit','This field is required.');
            return false;
        }

    }

	public function get_feed_data() {
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
        $params = $this->security->xss_clean($this->input->post());
    }
    public function publish_feed() {
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
        $response['message'] = 'Feed published successfully!';
        $response['error'] = '';
        $param = $this->security->xss_clean($this->input->post());
        $status = $param['status'];
        $fid = $param['fid'];
        $res = $this->category->updateFeedStatus($fid, $status);
        echo json_encode($response);
        exit;
    }
    public function secure_feed_data() {
        $mrss_data = $this->security->xss_clean($this->input->post());
        $response = array();

        $response['code'] = 200;
        $response['error'] = '';
        $partner_id =$mrss_data['id'];
        $feed_url =$mrss_data['feed_url'];
        $feed_id =$mrss_data['feed_id'];
        $secure_result = $this->category->getSecureValueByPartnerid($partner_id,$feed_id);
        $secure_result =$secure_result->result();
        $secure = $secure_result[0]->secure;
        $url = $secure_result[0]->url;
        if($secure == 0){
            $result = $this->category->getUserDataByPartnerid($partner_id);
            $result =$result->result();
            $password = $result[0]->password;
            $url = $url.'/'.$password;
            $secure=1;
            $updatefeed=$this->category->updatePartnerFeed($partner_id,$secure,$url,$feed_id);
            $response['message'] = 'Feed Secure Successfully!';
        }else{
            $url = explode('/',$url);
            $join_url = $url[0].'/'.$url[1];
            $secure=0;
            $updatefeed=$this->category->updatePartnerFeed($partner_id,$secure,$join_url,$feed_id);
            $response['message'] = 'Feed Not Secure!';
        }
        echo json_encode($response);
        exit;
    }

    public function add_video_to_feed() {
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
        $response['message'] = 'Video added to feed successfully!';
        $response['error'] = '';
        $param = $this->security->xss_clean($this->input->post());
        $vid = $param['vid'];
        $fid = $param['fid'];
        $res = $this->category->addFeedVideo($vid, $fid);
        echo json_encode($response);
        exit;
    }
    public function remove_video_from_feed() {
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
        $response['message'] = 'Video removed from feed successfully!';
        $response['error'] = '';
        $param = $this->security->xss_clean($this->input->post());
        $vid = $param['vid'];
        $fid = $param['fid'];
        $res = $this->category->deleteFeedVideo($vid, $fid);
        echo json_encode($response);
        exit;


    }

    public function show_preview($feed_url) {
	    // get feed id by url
        $feed_id = $this->category->getFeedDataByUrl($feed_url);
        $result = $this->category->getFeedVideos($feed_id->id);//print_r($result);
        $exclusive = $this->category->getExclusiveVideos($feed_id->id);//print_r($result);
        $videos = $this->category->getVideosForFeed($result, $exclusive);

        $feed_data=$result->result();
        $this->data['feed']=$feed_data;
        $this->data['title']=$feed_id->title;
        $this->data['feed_id']=$feed_id->id;
        $this->data['feed_status'] = $feed_id->status;
        $this->data['partner_name'] = '';
        $this->data['videos']=$videos;
        $this->data['content'] = $this->load->view('categories_mrss/preview',$this->data,true);
        $this->load->view('common_files/template',$this->data);

    }
    public function show_partner_preview($partner, $feed_url) {
        // get feed id by url
        $feed_id = $this->category->getFeedDataByUrl($partner.'/'.$feed_url);
        $result = $this->category->getFeedVideos($feed_id->id, $feed_id->partner_id);//print_r($result);
        $exclusive = $this->category->getExclusiveVideos($feed_id->partner_id);//print_r($result);
        $videos = $this->category->getVideosForFeed($result, $exclusive);

        $feed_data=$result->result();
        $this->data['feed']=$feed_data;
        $this->data['title']=$feed_id->title;
        $this->data['feed_id']=$feed_id->id;
        $this->data['feed_status'] = $feed_id->status;
        $this->data['partner_name'] = $this->category->getPartnerName($feed_id->partner_id)->full_name;
        $this->data['videos']=$videos;
        $this->data['content'] = $this->load->view('categories_mrss/preview',$this->data,true);
        $this->load->view('common_files/template',$this->data);

    }

    public function publish_story_content() {
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
        $response['message'] = 'Feed published successfully!';
        $response['error'] = '';
        $param = $this->security->xss_clean($this->input->post());

        $this->db->query('DELETE FROM `feed_video_story` WHERE lead_id = '.$param['lead_id']);
        foreach ($param['story_feed_id'] as $fed){
            $dbData['lead_id'] = $param['lead_id'];
            $dbData['publish_story_title'] = $param['story_title'];
            $dbData['publish_story_content'] = $param['story_description'];
            $dbData['feed_id'] = $fed;
            $dbData['categories'] = implode(',',$param['categories']);
            $this->db->insert('feed_video_story',$dbData);
        }
        echo json_encode($response);
        exit;
    }

    public function get_enqueued_videos($feed_id) {
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

		$result = $this->mrss_queue->getQueueByFeedId("mq.id AS queue_id, mq.publication_date, vl.unique_key", $feed_id, $search, $start, $limit, $orderby, $params['columns']);
        $resultCount = $this->mrss_queue->getQueueByFeedId("mq.id AS queue_id, mq.publication_date, vl.unique_key", $feed_id);
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_edit']) {
                $links .= '<a title="Publish to MRSS" href="javascript:void(0);" class="queue_to_mrss_direct_btn" data-id="' . $row->queue_id . '"><i class="material-icons">&#xE255;</i></a> ';
            }
            if($this->data['assess']['can_delete']) {
                $links .= '| <a title="Remove Video From Queue" href="javascript:void(0);" class="remove_enqueued_video_btn" data-id="' . $row->queue_id . '"><i class="material-icons">&#xE92B;</i></a>';
            }
            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }
			$r[] = $row->unique_key;
			$r[] = $row->publication_date;
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
    public function queue_to_mrss_direct() {
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
        $response['message'] = 'Video pushed to MRSS successfully!';
        $response['error'] = '';
        
        $param = $this->security->xss_clean($this->input->post());
        $queue_id = $param['queue_id'];

        $queue_data = $this->db->query('SELECT * from mrss_queue WHERE id = ' . $queue_id . ' LIMIT 1')->result_array();
        $queue_data = $queue_data[0];
        $res = $this->db->query('SELECT id, feed_delay, feed_time, pub_date FROM mrss_feeds')->result_array();
        $feed_delays = db_result_to_array_map($res);
        
        // 1 videos
        $this->db->where('id', $queue_data['video_id']);
        $this->db->update('videos', array('mrss' => 1));

        // 2 feed_video
        $this->db->insert('feed_video', ['video_id' => $queue_data['video_id'], 'feed_id' => $queue_data['feed_id'], 'exclusive_to_partner' => $queue_data['exclusive_to_partner']]);

        // 3 mrss_publications
        $dbQuery = $this->db->query(
            "
            SELECT * FROM mrss_publication
            WHERE feed_id = " . $queue_data['feed_id'] . "
            AND video_id = " . $queue_data['video_id']
        );

        if ($feed_delays[$feed_id]["pub_date"] == "queue_to_mrss") {
            $db_data['publication_date'] = date('Y-m-d H:i:s');
        } else {
            $db_data['publication_date'] = $queue_data['publication_date'];
        }

        if ($dbQuery->num_rows() > 0) {
            $this->db->where('feed_id', $queue_data['feed_id']);
            $this->db->where('video_id', $queue_data['video_id']);
            $this->db->update('mrss_publication', $db_data);
        } else {
            $db_data['feed_id'] = $queue_data['feed_id'];
            $db_data['video_id'] = $queue_data['video_id'];
            $this->db->insert('mrss_publication', $db_data);
        }

        $this->db->query('DELETE FROM mrss_queue where video_id = ' . $queue_data['video_id'] . ' AND feed_id = ' . $queue_data['feed_id']);

        // Update time to takle 1 video per feed after delay
        $db_data = array();
        $db_data['publication_date'] = date('Y-m-d H:i:s');

        // echo "Update with date::";
        // print_r($db_data['publication_date']);

        $this->db->where('feed_id', $queue_data['feed_id']);
        $this->db->update('mrss_queue', $db_data);

        echo json_encode($response);
        exit;
    }
    public function remove_video_from_queue() {
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
        $response['message'] = 'Video removed from MRSS successfully!';
        $response['error'] = '';
        
        $param = $this->security->xss_clean($this->input->post());
        $queue_id = $param['queue_id'];

        $queue_data = $this->db->query('SELECT * from mrss_queue WHERE id = ' . $queue_id . ' LIMIT 1')->result_array();
        $queue_data = $queue_data[0];

        $this->db->query('DELETE FROM mrss_queue where video_id = ' . $queue_data['video_id'] . ' AND feed_id = ' . $queue_data['feed_id']);
        
        echo json_encode($response);
        exit;
    }
	
}
