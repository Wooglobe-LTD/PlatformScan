<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rss_Articles extends APP_Controller {

	public function __construct() {
        parent::__construct();
        
		$this->data['active'] = 'rss_article_feeds';
		$css = array(
			'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css',
            'assets/css/dataTables.checkboxes.css',
            'assets/css/rss_article_feeds.css',
            'assets/js/vid_up/jquery.fileuploader.min.css',
            'assets/js/vid_up/jquery.fileuploader-theme-dragdrop.css',
            'assets/js/vid_up/font/font-fileuploader.css',
            'assets/js/vid_up/font/font-fileuploader.ttf',
		);
		$js = array(
            'bower_components/datatables/media/js/jquery.dataTables.min.js',
            'bower_components/datatables-buttons/js/dataTables.buttons.js',
            'assets/js/vid_up/jquery.fileuploader.min.js',
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
            'assets/js/jquery.mousewheel.min.js',
			'assets/js/rss_articles.js',
            'assets/js/jquery-ui.min.js',
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->data['assess'] =  array(
            'can_act'=>role_permitted_html(true),
        );
        $this->load->model('Rss_Articles_Model', 'rss');
    }
    
	public function index()
	{
		auth();
        role_permitted(false,'categories_mrss');
		$this->data['title'] = 'RSS Article Feeds Management';
        $this->load->model('User_Model','user');
        $this->db->select(['id','full_name']);
		$this->data['categories'] = $this->rss->getAllCategories();
        $this->data['feeds'] = $this->rss->getAllArticleFeeds();
        $this->data['partners'] = $this->rss->getAllPartners();
        $query=$this->db->get('users');
        $query = $this->user->getAllUsers(2);
        $users=$query->result();
        $this->data['users']=$users;
		$this->data['content'] = $this->load->view('rss_article_feeds/article_feeds',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}

    public function rss_articles() 
    {
        auth();
        role_permitted(false,'categories_mrss');
		$this->data['title'] = 'RSS Article Management';
        $this->load->model('User_Model','user');
        $this->db->select(['id','full_name']);
		$this->data['categories'] = $this->rss->getAllCategories();
        $this->data['feeds'] = $this->rss->getAllArticleFeeds();
        $query=$this->db->get('users');
        $query = $this->user->getAllUsers(2);
        $users=$query->result();
        $this->data['users']=$users;
		$this->data['content'] = $this->load->view('rss_article_feeds/rss_articles',$this->data,true);
		$this->load->view('common_files/template',$this->data);
    }

    public function get_feeds()
    {
		$result = $this->rss->getAllArticleFeeds('raf.*',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->rss->getAllArticleFeeds();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_act']) {
                // $links .= '<a title="Edit Categeory" href="javascript:void(0);" class="edit-category" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
                // $links .= '| <a title="Preview Feed" href="' . $this->data['root'].'admin/rss/'.$row->feed_url . '/preview" class="preview-feed" data-url="' . $this->data['root'].'rss/'.$row->feed_url . '" data-id="' . $row->id . '"><i class="material-icons">&#xe417;</i></a> ';
                // $links .= '| <a title="Secure Feed" href="javascript:void(0);" class="secure-feed" id = "'. $row->id .'" data-id="' . $row->partner_id . '" data-url="' . $this->data['root'].'rss/'.$row->feed_url . '"><i class="material-icons">lock_open</i></a>';
                $links .= '| <a title="Delete Category" id="delete_feed_'. $row->id .'" href="javascript:void(0);" class="delete-category" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
            }
            if($this->data['assess']['can_act']) {
                $r[] = $links;
            }
            $partner_name = $this->rss->getNameByPartnerId($row->partner_id);
            $partner_name = $partner_name->full_name;
			$r[] = $row->feed_title;
            $r[] = $partner_name;
            $r[] = $row->feed_delay == NULL ? 0 : $row->feed_delay;
            $r[] = $row->feed_time == "" ? "00:00-00:00" : $row->feed_time;
            $r[] = '<a target="_blank" href="'.$this->data['root'].'rss/'.$row->feed_url.'" >'.$this->data['root'].'rss/'.$row->feed_url.'</a>';
			$r[] = '<a title="Add Article" id=add_article_tbl_btn_"' . $row->id . '" href="javascript:void(0);" class="add_article_tbl_btn" data-id="' . $row->id . '"><i class="material-icons">add</i></a> ';

			$data[] = $r;
		}

		$response['code'] = 200;
		$response['message'] = 'RSS Feeds';
		$response['error'] = '';
		$response['data'] = $data;

		$response['recordsTotal'] = $resultCount->num_rows();
		$response['recordsFiltered'] = $resultCount->num_rows();
		echo json_encode($response);
		exit;
	}
    
    public function get_articles()
    {    
		$result = $this->rss->getAllArticles('ra.*, raf.feed_url, COUNT(ras.id) num_of_slides',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->rss->getAllArticles();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_act']) {
                $links .= '<a title="Edit Article" id="edit_article_' . $row->id . '" href="javascript:void(0);" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
                // $links .= '| <a title="Preview Article" href="' . $this->data['root'].'admin/article/'.$row->id . '/preview" data-id="' . $row->id . '"><i class="material-icons">&#xe417;</i></a> ';
                $links .= '| <a title="Delete Article" id="delete_article_' . $row->id . '" href="javascript:void(0);" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
            }
            if($this->data['assess']['can_act']) {
                $r[] = $links;
            }
			$r[] = $row->title;
            $r[] = $row->credit;
            $r[] = $row->category;
            $r[] = $row->feed_url;
            $r[] = $row->num_of_slides;

			$data[] = $r;
		}

		$response['code'] = 200;
		$response['message'] = 'Articles';
		$response['error'] = '';
		$response['data'] = $data;

		$response['recordsTotal'] = $resultCount->num_rows();
		$response['recordsFiltered'] = $resultCount->num_rows();
		echo json_encode($response);
		exit;
	}
    
    public function save_article()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'categories_mrss');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $param = $this->security->xss_clean($this->input->post());

        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Feed saved successfully!';
        $response['error'] = '';
        $response['id'] = null;

        if(!isset($param['slide_ids']) || empty($param['slide_ids'])) {
            $response = array();
            $response['code'] = 202;
            $response['message'] = 'Validation Errors!';
            $response['error'] = 'Cannot create article without any slide.';
            echo json_encode($response);
            exit();
        }

        $slide_ids = $param['slide_ids'];

        $fields = array('article_title', 'article_description', 'article_category', 'article_keywords', 'article_credit');
        $this->validation->set_rules('article_title', 'article_title', 'trim|required');
        $this->validation->set_rules('article_description', 'article_description', 'trim|required|max_length[750]');
        $this->validation->set_rules('article_category', 'article_category', 'trim|required');
        $this->validation->set_rules('article_keywords', 'article_keywords', 'trim|required');
        $this->validation->set_rules('article_credit', 'article_credit', 'trim|required');
        foreach ($slide_ids as $i) {
            $this->validation->set_rules('slide_title_'.$i, 'slide_title_'.$i, 'trim|required');
            $this->validation->set_rules('slide_headline_'.$i, 'slide_headline_'.$i, 'trim|required');
            $this->validation->set_rules('slide_description_'.$i, 'slide_description_'.$i, 'trim|required|max_length[750]');
            $this->validation->set_rules('slide_credit_'.$i, 'slide_credit_'.$i, 'trim|required');
            $fields[] = 'slide_title_'.$i;
            $fields[] = 'slide_headline_'.$i;
            $fields[] = 'slide_description_'.$i;
            $fields[] = 'slide_credit_'.$i;
        }

        $this->validation->set_message('required', 'This field is required.');
        $this->validation->set_message('max_length', 'This field exceeds required length.');

        if ($this->validation->run() === false || !isset($param['article_feeds']) || empty($param['article_feeds'])) {
            $errors = array();
            $error_slides = "";
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            if (!isset($param['article_feeds']) || empty($param['article_feeds'])) {
                $errors['article_feeds'] = "<p>Select atleast one feed.</p>";
            }
            foreach ($slide_ids as $i) {
                if (!empty($errors['slide_title_'.$i]) || !empty($errors['slide_headline_'.$i]) || !empty($errors['slide_description_'.$i]) || !empty($errors['slide_credit_'.$i])) {
                    $error_slides .= " #".$i;
                }
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['data'] = $error_slides;
        }
        else {
            $file_inputs = array();
            foreach ($slide_ids as $i) {
                if(!isset($param['slide_db_id_'.$i]) || empty($param['slide_db_id_'.$i])) {
                    $file_inputs[] = $i;
                }
            }
            $this->validate_files($file_inputs);

            $slide_data = array();
            $article_data = array();
            $article_ids = array();
            $article_id = $this->rss->getNextArticleId();

            if(isset($param['article_edit']) && !empty($param['article_edit'])) {
                $article_data['id'] = $param['article_id'];
            }
            else {
                $article_data['id'] = $article_id;
                $article_data['created_at'] = date('Y-m-d H:i:s');
                $article_data['created_by'] = $this->sess->userdata('adminId');
            }

            $article_data['title'] = $param['article_title'];
            $article_data['description'] = $param['article_description'];
            $article_data['category'] = $param['article_category'];
            $article_data['language'] = $param['selected_language'];
            $article_data['keywords'] = $param['article_keywords'];
            $article_data['credit'] = $param['article_credit'];
			$article_data['updated_at'] = date('Y-m-d H:i:s');
			$article_data['updated_by'] = $this->sess->userdata('adminId');

            foreach ($param['article_feeds'] as $feed_id) {
                $article_data['feed_id'] = (int)$feed_id;
                if (!is_dir("../uploads/articles/article_" . $article_data['id'])) {
                    mkdir("../uploads/articles/article_" . $article_data['id'], 0777, true);
                }
                $path = "../uploads/articles/article_" . $article_data['id'];

                foreach ($slide_ids as $i) {
                    $slide_data['article_id'] = $article_data['id'];
                    $slide_data['file_title'] = $param['slide_title_'.$i];
                    $slide_data['headline'] = $param['slide_headline_'.$i];
                    $slide_data['description'] = $param['slide_description_'.$i];
                    $slide_data['credit'] = $param['slide_credit_'.$i];

                    if(isset($param['slide_db_id_'.$i]) && !empty($param['slide_db_id_'.$i])) {
                        $image_paths = $this->rss->getSlideImageURLsBySlideID($param['slide_db_id_'.$i]);
                        
                        $image = './' . $image_paths["image_url"];
                        if(isset($image) && !empty($image)) {
                            if (!file_exists($image)) {
                                $image = str_replace('https://wooglobe.s3.us-west-2.amazonaws.com/', '', $image_paths['image_s3_url']);
                                $this->download_file_s3($image, './..'.$image);
                                $this->delete_file($image);
                                $image = './..'.$image;
                            }

                            $file_extension = pathinfo($image, PATHINFO_EXTENSION);
                            $image_name =  str_replace(basename($image), "", $image) . $slide_data['file_title'] . "." . $file_extension;
                            rename($image, $image_name);
                            $image = str_replace('./../', '../', $image_name);
                            $slide_data['image_url'] = $image;
                            
                            $image = str_replace("..", "", $image);
                            $source_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $image;
                            $target_file_key = $image;
                            $urlp = $this->upload_file_s3_new('public-read', $source_file, $target_file_key, $file_extension, false);
                            $slide_data['image_s3_url'] = $urlp;

                            $this->db->where('id',$param['slide_db_id_'.$i]);
                            $this->db->update('rss_article_slides',$slide_data);
                            $response['slide_ids'][] = $i;
                        }
                    }
                    else {
                        $file = $param['slide_title_'.$i];
                        $tmp_path = $_FILES['image_upload_'.$i]['tmp_name'];
                        $file_extension = pathinfo($_FILES['image_upload_'.$i]['name'], PATHINFO_EXTENSION);
                        $slide = $path . '/' . $file . '.' . $file_extension;

                        move_uploaded_file($tmp_path, $slide);
                        $slide_data['image_url'] = $slide;

                        $slide = str_replace("..", "", $slide);
                        $source_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $slide;
                        $target_file_key = $slide;
                        $urlp = $this->upload_file_s3_new('public-read', $source_file, $target_file_key, $file_extension, false);
                        $slide_data['image_s3_url'] = $urlp;

                        $this->db->insert('rss_article_slides', $slide_data);
                        $response['slide_ids'][] = $this->db->insert_id();
                    }
                }

                if(isset($param['article_edit']) && !empty($param['article_edit'])) {
                    $this->db->where('id',$param['article_id']);
                    $this->db->update('rss_articles',$article_data);
                    $article_ids[] = $param['article_id'];
                    unset($param['article_edit']);
                    $article_data['id'] = $article_id;
                }
                else {
                    $this->db->insert('rss_articles', $article_data);
                    $article_ids[] = $this->db->insert_id();
                    $article_data['id']++;
                }
            }

            $response['article_ids'] = $article_ids;
        }

        echo json_encode($response);
        exit;
    }
    
	public function add_article_feed()
    {
        
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'New Feed Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		//$this->validation->set_rules('parent_id','Parent Category','trim|required');
		$this->validation->set_rules('feed_title','feed_title','trim|required|callback_validate_title');
        $this->validation->set_rules('feed_url','feed_url','trim|required|callback_validate_url');

		$this->validation->set_message('required','This field is required');

		if($this->validation->run() === false) {
			
			$fields = array('feed_title', 'feed_url');
			$errors = array();
			foreach($fields as $field){
				$errors[$field] = form_error($field);
			}
            if (!isset($param['article_feeds']) || empty($param['article_feeds'])) {
                $errors['feed_partner'] = form_error('feed_partner');
            }
			$response['code'] = 201;
			$response['message'] = 'Validation Errors!';
			$response['error'] = $errors;
			$response['url'] = '';
			
		}
        else {
			
			$dbData = $this->security->xss_clean($this->input->post());
            
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
            $partners = $dbData["feed_partner"];
            unset($dbData["desc_info"]);
            unset($dbData["feed_partner"]);

            foreach ($partners as $partner) {
                $this->db->select('full_name');
                $this->db->where('id',$partner);
                $query=$this->db->get('users');
                $result=$query->result();
                $title_slug =  $result[0]->full_name."/".$dbData['feed_title'];
                $dbData['partner_id'] = $partner;
                $dbData['slug'] = slug($title_slug,'rss_article_feeds','slug');
                $dbData['feed_url']=slug($result[0]->full_name,'rss_article_feeds','feed_url') . "/" . slug($dbData["feed_title"], 'rss_article_feeds', 'feed_url');
                $this->db->insert('rss_article_feeds',$dbData);
            }
            
            $feed_id = $this->db->insert_id();

            unset($dbData);

            $dbData["feed_id"] = $feed_id;
            
            foreach($desc_info as $info){
                $dbData[$info] = 1;
            }

            $this->db->insert('rss_article_info', $dbData);
		}
		
		echo json_encode($response);
		exit;
	}

    public function validate_files($num)
    {
        $allowed_extensions = array('png', 'jpeg');
        $errors = array();
        $error_slides = "";
        foreach ($num as $i) {
            if (isset($_FILES['image_upload_'.$i]['name']) && !empty($_FILES['image_upload_'.$i]['name'])) {
                $slide = "";
                $file = $_FILES['image_upload_'.$i]['name'];
                $type = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (!in_array($type, $allowed_extensions)) {
                    $errors['image_upload_'.$i] = "File type should be png or jpeg";
                    $slide = " #".$i;
                }
                if ($_FILES['image_upload_'.$i]['size'] > 15000000) {
                    $errors['image_upload_'.$i] = "File size should be no more than 15MB";
                    $slide = " #".$i;
                }
                $error_slides .= $slide;
            }
            else {
                $errors['image_upload_'.$i] = "File required!";
                $error_slides .= " #".$i;
            }
        }
        if (count($errors) > 0) {
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['data'] = $error_slides;

            echo json_encode($response);
            exit();
        }
    }
    
	public function validate_title($title)
	{
        $title = $this->security->xss_clean($title);
		
		if(!empty($title)){
			if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
				$result = $this->rss->getArticleFeedByTitle($title);
				if($result->num_rows() > 0){
					$this->validation->set_message('validate_title','This title already exist!');
					return false;
				}else{
					return true;
				}
			}
            else {
				$this->validation->set_message('validate_title','Only alphabet and number are allowed.');
				return false;
			}
		}
        else {
			$this->validation->set_message('validate_title','This field is required.');
			return false;
		}
	}
    
    public function validate_url($url)
    {
        $url = $this->security->xss_clean($url);

        if(!empty($url)) {
			if (preg_match('/^[a-zA-Z0-9-]+$/', $url)) {
                $result = $this->rss->getArticleFeedByURL($url);
                if($result->num_rows() > 0){
                    $this->validation->set_message('validate_url','This feed URL already exist!');
                    return false;
                }else{
                    return true;
                }
			}
            else {
				$this->validation->set_message('validate_url','Only alphabet, numbers and dashes are allowed.');
				return false;
			}
        }
        else {
            $this->validation->set_message('validate_url','This field is required.');
            return false;
        }

    }

    public function get_article_data()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'categories_mrss');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $param = $this->security->xss_clean($this->input->post());
        $article_id = $param['id'];
        $article_data = $this->rss->getArticleDataById($article_id);
        $slides = $this->rss->getArticleSlidesByID($article_id);
        $article_data["slides"] = $slides;

        $response = array();
        if(isset($article_data) && !empty($article_data)) {
            $response['code'] = 200;
            $response['message'] = 'Article Data Sent!';
            $response['data'] = $article_data;
        }
        else {
            $response = array();
            $response['code'] = 201;
            $response['message'] = 'Article Not Found!';
            $response['data'] = '';
        }

        echo json_encode($response);
        exit;
    }

    public function delete_article()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'categories_mrss');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $param = $this->security->xss_clean($this->input->post());
        $article_id = $param['id'];

        $dbData['deleted_at'] = date('Y-m-d H:i:s');
        $dbData['deleted_by'] = $this->sess->userdata('adminId');
        $dbData['deleted'] = 1;

        $this->db->where('id',$article_id);
        if ($this->db->update('rss_articles',$dbData)) {
            $response['code'] = 200;
            $response['message'] = 'Article Deleted!';
            $response['data'] = $dbData;
        }
        else {
            $response['code'] = 201;
            $response['message'] = 'Something is going wrong!';
            $response['data'] = '';
        }

        echo json_encode($response);
        exit();
    }

    public function delete_article_feed()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'categories_mrss');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $param = $this->security->xss_clean($this->input->post());
        $feed_id = $param['id'];

        $dbData['deleted_at'] = date('Y-m-d H:i:s');
        $dbData['deleted_by'] = $this->sess->userdata('adminId');
        $dbData['deleted'] = 1;

        $this->db->where('id',$feed_id);
        if ($this->db->update('rss_article_feeds',$dbData)) {
            $response['code'] = 200;
            $response['message'] = 'Feed Deleted!';
            $response['data'] = $dbData;
        }
        else {
            $response['code'] = 201;
            $response['message'] = 'Something is going wrong!';
            $response['data'] = '';
        }

        echo json_encode($response);
        exit();
    }

    public function get_languages_list()
    {
        $response = array();
        $file_path = "../admin/assets/data/languages_list.json";
        if (file_exists($file_path)) {
            $languages = json_decode(file_get_contents($file_path));
            if (isset($languages) && !empty($languages)) {
                $response['code'] = 200;
                $response['message'] = 'Languages List Loaded!';
                $response['data'] = $languages;
            }
            else {
                $response['code'] = 201;
                $response['message'] = 'Something is going wrong!';
                $response['data'] = '';
            }
        }
        else {
            $response['code'] = 201;
            $response['message'] = 'Something is going wrong!';
            $response['data'] = '';
        }

        echo json_encode($response);
        exit();
    }

    public function translate_article_form()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'categories_mrss');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->post());

        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Feed translated successfully!';
        $response['error'] = '';
        $response['data'] = null;

        $lang = $params['selected_language'];

        unset($params['selected_language']);
        unset($params['article_slide_num']);
        unset($params['article_id']);
        unset($params['article_edit']);
        unset($params['article_feeds']);
        unset($params['article_category']);
        $slide_ids = $params['slide_ids'];
        unset($params['slide_ids']);
        if(isset($slide_ids) && !empty($slide_ids)) {
            foreach($slide_ids as $i) {
                unset($params['image_upload_'.$i]);
            }
        }
        
        $ch = curl_init();
        foreach($params as $input => $value) {
            if(!empty($value)) {
                curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                $data = array(
                    'model' => 'gpt-3.5-turbo',
                    'messages' => array(
                        array(
                            'role' => 'user',
                            'content' => 'Translate the sentence into '. $lang .':'. $value .'. I want just the translated sentence.',
                        ),
                    ),
                    'temperature' => 0.1,
                );
                
                $jsonData = json_encode($data);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Authorization: Bearer ' . CHAT_GPT_KEY;
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (!curl_errno($ch)) {
                    $result = json_decode($result);
                    $result = $result->choices[0]->message->content;
                    $params[$input] = $result;
                }
            }
            else {
                unset($param[$input]);
            }
        }
        curl_close($ch);
        $response['data'] = $params;

        echo json_encode($response);
        exit();
    }
    
    public function generate_article()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'categories_mrss');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $param = $this->security->xss_clean($this->input->post());

        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Feed saved successfully!';
        $response['error'] = '';
        $response['data'] = null;

        $fields = array('article_topic', 'num_of_slides');
        $this->validation->set_rules('article_topic', 'article_topic', 'trim|required');
        $this->validation->set_rules('num_of_slides', 'num_of_slides', 'required|numeric|greater_than[1]');
        $this->validation->set_message('required', 'This field is required.');
        $this->validation->set_message('greater_than', 'Must be atleast 2.');

        if ($this->validation->run() === false) {
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
        }
        else {
            $topic = $param['article_topic'];
            $num_of_slides = $param['num_of_slides'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            $data = array(
                'model' => 'gpt-3.5-turbo',
                'messages' => array(
                    array(
                        'role' => 'user',
                        'content' => 'Write a clickbait article about '. $topic .'.
                        Your response should be in a JSON format. 
                        There should be a main title (article_title), a description of the article (article_description), and search keywords for the article (article_keywords). 
                        The article_keywords should be a single string of comma-separated words. 
                        There should be exactly '. $num_of_slides .' slides for that article that would be considered as subtopics of that article each slide should contain its title, headline, and description. 
                        Slides\' data should be numbered as slide_title_1, slide_headline_1, slide_description_1, and so on. 
                        Dont mention article or slide number in the values. Avoid use of numbers in titles. Slide title should be one or two words only. I need the json only.',
                    ),
                ),
                'temperature' => 0.1,
            );
            
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: Bearer ' . CHAT_GPT_KEY;
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (!curl_errno($ch)) {
                $result = json_decode($result);
                $result = $result->choices[0]->message->content;
                $response['data'] = json_decode($result);
                $response['slides'] = $num_of_slides;
            }
            else {
                $response['code'] = 202;
                $response['message'] = 'Query Error!';
                $response['error'] = '';
            }
            curl_close($ch);
        }

        echo json_encode($response);
        exit;
    }

}
