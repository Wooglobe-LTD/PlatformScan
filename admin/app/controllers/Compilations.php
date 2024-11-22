<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compilations extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'compilations';
		$css = array(
			'bower_components/uikit/css/uikit.almost-flat.min.css',
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
			'assets/js/compilations.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false, 'compilations'),
            'can_add'=>role_permitted_html(false, 'compilations','add_compilations'),
            'can_edit'=>role_permitted_html(false, 'compilations','update_compilations'),
            'can_delete'=>role_permitted_html(false, 'compilations','delete_compilations'),
        );
		$this->load->model('Compilation_Model','compilation');

    }
	public function index()
	{
		auth();
        role_permitted(false, 'compilations');
		$this->data['title'] = 'Compilations Management';
		$leadsQuery = "
		    SELECT v.id,v.video_title title,v.unique_key
		    FROM video_leads v 
            WHERE v.deleted = 0
            AND v.status in (6,7,8)
            ORDER BY v.created_at ASC
		";
        $leads = $this->db->query($leadsQuery);
        $this->data['leads'] = $leads;
		$this->data['content'] = $this->load->view('compilation/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
    public function compilations_add()
    {
        auth();
        role_permitted(false, 'compilations',"add_compilation");
        $this->data['title'] = 'Compilations Management';
        $this->data['content'] = $this->load->view('compilation/compilations_add',$this->data,true);

        $this->load->view('common_files/template',$this->data);
    }
	
	
	public function compilations_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'compilations');
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
		
		$result = $this->compilation->getAllCompilations('c.id,c.title,c.url,(case when (c.status = 1) THEN "Active" ELSE "Inactive" END) as status',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->compilation->getAllCompilations();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            /*if($this->data['assess']['can_edit']) {
                $links .= ' <a title="Edit Compilation" href="javascript:void(0);" class="edit-client" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
            }*/
            if($this->data['assess']['can_delete']) {
                $links .= '<a title="Delete Compilation" href="javascript:void(0);" class="delete-client" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
            }

            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete'] ) {
                $r[] = $links;
            }
			$r[] = '<a href="'.base_url('compilation_detail/'.$row->id).'" >'.$row->title.'</a>';
			$r[] = '<a href="'.$row->url.'" target="_blank">'.$row->url.'</a>';
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

	public function add_compilation(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}

        $role_permitted_ajax = role_permitted_ajax(false, 'compilations','add_compilation');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'New Compilation Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('title','Title','trim|required');
		//$this->validation->set_rules('url','URL','trim|required');
		//$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_rules('wg_ids','WooGlobe Video IDs','trim|required');
		$this->validation->set_message('required','This field is required.');
		
		if($this->validation->run() === false){
			
			//$fields = array('full_name','gender','email','mobile','address','status');
			$fields = array('title','url','status','videos','wg_ids');
			$errors = array();
			foreach($fields as $field){
			    if($field == 'videos[]'){
                    $errors['videos'] = form_error($field);
                }else{
                    $errors[$field] = form_error($field);
                }

			}
			$response['code'] = 201;
			$response['message'] = 'Validation Errors!';
			$response['error'] = $errors;
			$response['url'] = '';
			
		}else{
            $videos = [];
			$dbData = $this->security->xss_clean($this->input->post());
			$dbData['status'] =1;
			$dbData['created_at'] = date('Y-m-d H:i:s');
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['created_by'] = $this->sess->userdata('adminId');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			if(isset($dbData['wg_ids'])){

                $videos = explode(PHP_EOL, $dbData['wg_ids']);
                foreach ($videos as $i=>$v){
                    $videos[$i] = trim($v);
                }
                $videos = implode('","',$videos);
                $leadIdsQuery = $this->db->query('SELECT id FROM video_leads WHERE unique_key IN("'.$videos.'")');
                //echo $this->db->last_query();exit;
                $ids = [];
                foreach ($leadIdsQuery->result() as $row){
                    $ids[] = $row->id;
                }
                $videos = $ids;
                //$videos = implode(',',$ids);;
                unset($dbData['wg_ids']);
            }
            $dbData['videos_ids'] = implode(',',$videos);
			$this->db->insert('compilations',$dbData);
			$id = $this->db->insert_id();
			$response['id'] = $id;
			foreach ($videos as $video){
                $vData['compilation_id'] = $id;
                $vData['lead_id'] = $video;
                $this->db->insert('compilations_videos',$vData);
            }


		}
		
		echo json_encode($response);
		exit;

	}

    public function submint_compilation(){

        auth();
        role_permitted(false, 'compilations',add_compilation);
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Compilation Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('title','Title','trim|required');
        //$this->validation->set_rules('url','URL','trim|required');
        //$this->validation->set_rules('status','Status','trim|required');
        $this->validation->set_rules('videos[]','Status','trim|required');
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            //$fields = array('full_name','gender','email','mobile','address','status');
            $fields = array('title','url','status','videos','videos[]');
            $errors = array();
            foreach($fields as $field){
                if($field == 'videos[]'){
                    $errors['videos'] = form_error($field);
                }else{
                    $errors[$field] = form_error($field);
                }

            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{
            $videos = [];
            $dbData = $this->security->xss_clean($this->input->post());
            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['created_by'] = $this->sess->userdata('adminId');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            if(isset($dbData['videos'])){
                $videos = $dbData['videos'];
                unset($dbData['videos']);
            }
            $dbData['videos_ids'] = implode(',',$videos);
            $this->db->insert('compilations',$dbData);
            $id = $this->db->insert_id();
            $response['id'] = $id;
            foreach ($videos as $video){
                $vData['compilation_id'] = $id;
                $vData['lead_id'] = $video;
                $this->db->insert('compilations_videos',$vData);
            }


        }

        echo json_encode($response);
        exit;

    }


	public function get_compilation(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'clients');
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
		$result = $this->compilation->getCompilationById($id,'c.id,c.title,c.url,c.status,c.videos_ids');
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No Compilation found!';
			$response['error'] = 'No Compilation found!';
			$response['url'] = '';

		}else{

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}

	public function update_compilation(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'compilations','update_compilation');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }

		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Compilation Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->compilation->getCompilationById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No compilation found!';
			$response['error'] = 'No compilation found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
        $this->validation->set_rules('title','Title','trim|required');
        $this->validation->set_rules('url','URL','trim|required');
        $this->validation->set_rules('status','Status','trim|required');
        $this->validation->set_rules('videos[]','Status','trim|required');
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            //$fields = array('full_name','gender','email','mobile','address','status');
            $fields = array('title','url','status','videos','videos[]');
            $errors = array();
            foreach($fields as $field){
                if($field == 'videos[]'){
                    $errors['videos'] = form_error($field);
                }else{
                    $errors[$field] = form_error($field);
                }

            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{
			
			$dbData = $this->security->xss_clean($this->input->post());
			unset($dbData['id']);
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
            $videos = [];
            if(isset($dbData['videos'])){
                $videos = $dbData['videos'];
                unset($dbData['videos']);
            }
            $dbData['videos_ids'] = implode(',',$videos);
			$this->db->where('id',$id);
			$this->db->update('compilations',$dbData);
            $this->db->where('compilation_id',$id);
            $this->db->delete('compilations_videos');
            foreach ($videos as $video){
                $vData['compilation_id'] = $id;
                $vData['lead_id'] = $video;
                $this->db->insert('compilations_videos',$vData);
            }
		}
		
		echo json_encode($response);
		exit;

	}


	public function delete_compilation(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false, 'compilations','delete_compilation');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Compilation Deleted Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->compilation->getCompilationById($id);

		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No compilation found!';
			$response['error'] ='No compilation found!';
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
		$this->db->update('compilations',$dbData);
        $this->db->where('compilation_id',$id);
        $this->db->delete('compilations_videos');
		echo json_encode($response);
		exit;

	}

    public function compilation_detail($id)
    {
        auth();
        role_permitted(false, 'compilations');
        $this->data['title'] = 'Compilation Detail';
        $result = $this->compilation->getCompilationById($id);

        if(!$result){
            $this->sess->set_flashdata('err','Invalid URL.');
            redirect('/');
        }
        $leadsQuery = "
		    SELECT v.id,v.video_title title,v.unique_key wg_id,v.first_name,v.last_name,v.email
		    FROM video_leads v 
            WHERE v.deleted = 0
            AND id in ($result->videos_ids)  
            AND v.status in (6,7,8)
            ORDER BY v.created_at ASC
		";
        $leads = $this->db->query($leadsQuery);
        $useQuery = "
		    SELECT *
		    FROM compilations_use cu 
            WHERE cu.deleted = 0
            AND cu.compilation_id = $id 
            ORDER BY cu.created_at ASC
		";
        $uses = $this->db->query($useQuery);
        $this->data['leads'] = $leads;
        $this->data['uses'] = $uses;
        $this->data['data'] = $result;
        $this->data['content'] = $this->load->view('compilation/detail',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function compilation_use(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'compilations','update_compilation');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Compilation Use Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('compilation_id'));
        $result = $this->compilation->getCompilationById($id);
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No compilation use found!';
            $response['error'] = 'No compilation use found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $this->validation->set_rules('title','Title','trim|required');
        $this->validation->set_rules('url','URL','trim|required');
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('title','url');
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
            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $dbData['created_by'] = $this->sess->userdata('adminId');

            $this->db->insert('compilations_use',$dbData);
        }

        echo json_encode($response);
        exit;

    }

    public function search(){
        auth();
        role_permitted(false, 'compilations');
	    $key = $this->input->post('search');
	    $query = "
	        SELECT v.id as lid,v.unique_key,c.*
	        FROM video_leads v 
            INNER JOIN compilations_videos cv
            ON cv.lead_id = v.id
            INNER JOIN compilations c 
            ON c.id = cv.compilation_id
            WHERE v.unique_key = '$key'
	    ";
	    $searchResult = $this->db->query($query);
	    //echo $query;exit;

        $this->data['title'] = 'Compilations Video Search';
        $this->data['items'] = $searchResult;
        $this->data['key'] = $key;
        $this->data['content'] = $this->load->view('compilation/search',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

	
}
