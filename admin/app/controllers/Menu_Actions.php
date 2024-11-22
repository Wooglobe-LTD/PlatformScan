<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_Actions extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'actions';
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
		);
		if(role_permitted_html(false)){
            $js[] = 'assets/js/actions.js';
        }
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->load->model('Menu_Action_Model','action');
		$this->load->model('Menu_Model','menu');

    }
	public function index()
	{
		auth();
        role_permitted(false);


		$this->data['title'] = 'Menu Actions Management';
		$this->data['menus'] = $this->menu->getActiveMenus('m.id,m.menu_name');
		$this->data['content'] = $this->load->view('menu_actions/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function actions_listing(){
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
		
		$result = $this->action->getAllActions('ma.id,ma.menu_id,ma.action_name,ma.action_uri,m.menu_name,case when (ma.status = 1) THEN "Active" ELSE "Inactive" END as status',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->action->getAllActions();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            if(role_permitted_html(false)) {
                $links = '<a title="Edit Menu Action" href="javascript:void(0);" class="edit-action" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> | <a title="Delete Menu Action" href="javascript:void(0);" class="delete-action" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
                $r[] = $links;
            }
			$r[] = $row->menu_name;
			$r[] = $row->action_name;
			$r[] = $row->action_uri;
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

	public function add_action(){

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
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'New Menu Action Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('menu_id','Controller','trim|required');
		$this->validation->set_rules('action_name','Action Name','trim|required|callback_validate_name');
		$this->validation->set_rules('action_uri','Action URI','trim|required|callback_validate_uri');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');

		if($this->validation->run() === false){
			
			$fields = array('menu_id','action_name','status','action_uri');
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
			$dbData['created_by'] = $this->sess->userdata('adminId');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->insert('menu_actions',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	public function validate_name($name)
	{

        $name = $this->security->xss_clean($name);
        $menu_id = $this->security->xss_clean($this->input->post('menu_id'));


		if(!empty($name)){
			if (preg_match('/^[a-zA-Z0-9 ]+$/', $name)) {
				$result = $this->action->getActionByNameAndMenu($name,$menu_id);
				if($result->num_rows() > 0){
					$this->validation->set_message('validate_name','This menu action already exist against this menu!');
					return false;
				}else{
					return true;
				}
			}else{
				$this->validation->set_message('validate_name','Only alphabet and namubers are allowed.');
				return false;
			}
		}else{
			$this->validation->set_message('validate_name','This field is required.');
			return false;
		}
		
	}

    public function validate_uri($uri)
    {

        $uri = $this->security->xss_clean($uri);
        $menu_id = $this->security->xss_clean($this->input->post('menu_id'));


        if(!empty($uri)){
            if (preg_match('/^[a-zA-Z_]+$/', $uri)) {
                $result = $this->action->getActionByUriAndMenu($uri,$menu_id);
                if($result->num_rows() > 0){
                    $this->validation->set_message('validate_name','This action uri already exist against this menu!');
                    return false;
                }else{
                    return true;
                }
            }else{
                $this->validation->set_message('validate_name','Only alphabet and underscore are allowed.');
                return false;
            }
        }else{
            $this->validation->set_message('validate_name','This field is required.');
            return false;
        }

    }

	public function get_action(){

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
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Record found!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->action->getActionById($id,'ma.id,ma.menu_id,ma.status,ma.action_name,ma.action_uri');
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No menu action found!';
			$response['error'] = 'No menu action found!';
			$response['url'] = '';

		}else{

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}

	public function update_action(){

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
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Menu Action Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->action->getActionById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No menu action found!';
			$response['error'] = 'No menu action found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
        $this->validation->set_rules('menu_id','Controller','trim|required');
		$this->validation->set_rules('action_name','Action Name','trim|required|callback_validate_name_edit['.$id.']');
		$this->validation->set_rules('action_uri','Action URI','trim|required|callback_validate_uri_edit['.$id.']');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');

		if($this->validation->run() === false){
			
			$fields = array('menu_id','action_name','status','action_uri');
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
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->where('id',$id);
			$this->db->update('menu_actions',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	public function validate_name_edit($name,$id)
	{

        $name = $this->security->xss_clean($name);

        $menu_id = $this->security->xss_clean($this->input->post('menu_id'));
		if(!empty($name)){
			if (preg_match('/^[a-zA-Z0-9 ]+$/', $name)) {
                $result = $this->action->getActionByNameAndMenu($name,$menu_id);
				if($result->num_rows() > 0){
					$result = $result->row();
					if($result->id == $id){

						return true;

					}else{

						$this->validation->set_message('validate_name_edit','This menu action already exist against this menu!');
						return false;

					}
					
				}else{
					return true;
				}
			}else{
				$this->validation->set_message('validate_name_edit','Only alphabets and underscore are allowed.');
				return false;
			}
		}else{
			$this->validation->set_message('validate_name_edit','This field is required.');
			return false;
		}
		
	}

    public function validate_uri_edit($uri,$id)
    {

        $uri = $this->security->xss_clean($uri);

        $menu_id = $this->security->xss_clean($this->input->post('menu_id'));
        if(!empty($uri)){
            if (preg_match('/^[a-zA-Z_]+$/', $uri)) {
                $result = $this->action->getActionByUriAndMenu($uri,$menu_id);
                if($result->num_rows() > 0){
                    $result = $result->row();
                    if($result->id == $id){

                        return true;

                    }else{

                        $this->validation->set_message('validate_uri_edit','This action uri already exist against this menu!');
                        return false;

                    }

                }else{
                    return true;
                }
            }else{
                $this->validation->set_message('validate_uri_edit','Only alphabets and underscore are allowed.');
                return false;
            }
        }else{
            $this->validation->set_message('validate_uri_edit','This field is required.');
            return false;
        }

    }

	public function delete_action(){

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
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Menu Action Deleted Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->action->getActionById($id);

		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No menu action found!';
			$response['error'] = 'No menu action found!';
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
		$this->db->update('menu_actions',$dbData);
		
		echo json_encode($response);
		exit;

	}
	
}
