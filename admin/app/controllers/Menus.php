<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'menus';
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
            $js[] = 'assets/js/menus.js';
        }
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->load->model('Menu_Model','menu');
        
    }
	public function index()
	{
		auth();
        //role_permitted(false);
		$this->data['title'] = 'Menu Management';
		$this->data['content'] = $this->load->view('menus/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function menus_listing(){
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
		$orderby = 'm.sort_no ASC';
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
		
		$result = $this->menu->getAllMenus('m.id,m.menu_name,m.controller_uri,m.icon_code,m.active_class,m.sort_no,case when (m.status = 1) THEN "Active" ELSE "Inactive" END as status',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->menu->getAllMenus();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            if(role_permitted_html(false)) {
                $links = '<a title="Edit Menu" href="javascript:void(0);" class="edit-menu" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> | <a title="Delete Menu" href="javascript:void(0);" class="delete-menu" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
                $r[] = $links;
            }
			$r[] = $row->sort_no;
			$r[] = $row->menu_name;
			$r[] = $row->controller_uri;
			$r[] = '<i class="material-icons">'.$row->icon_code.'</i>';
			$r[] = $row->active_class;
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

	public function add_menu(){

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
		$response['message'] = 'New Menu Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('menu_name','Menu Name','trim|required|callback_validate_name');
		$this->validation->set_rules('controller_uri','Controller URI','trim|required|callback_validate_uri');
		$this->validation->set_rules('icon_code','Icon Code','trim|required');
		$this->validation->set_rules('active_class','Active Class','trim|required');
		$this->validation->set_rules('sort_no','Controller Name','trim|required|integer|is_unique[menus.sort_no]');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');
		$this->validation->set_message('is_unique','This sort no already exist.');
		$this->validation->set_message('integer','Only numbers.');

		if($this->validation->run() === false){
			
			$fields = array('menu_name','icon_code','active_class','controller_uri','sort_no','status');
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
			$this->db->insert('menus',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}


    public function validate_uri($uri)
    {

        $uri = $this->security->xss_clean($uri);


        if(!empty($uri)){
            if (preg_match('/^[a-zA-Z_]+$/', $uri)) {
               return true;
            }else{
                $this->validation->set_message('validate_uri','Only alphabet and underscore are allowed.');
                return false;
            }
        }else{
            $this->validation->set_message('validate_uri','This field is required.');
            return false;
        }

    }

	public function validate_name($name)
	{

        $name = $this->security->xss_clean($name);


		if(!empty($name)){
			if (preg_match('/^[a-zA-Z0-9 ]+$/', $name)) {
				$result = $this->menu->getMenuByName($name);
				if($result->num_rows() > 0){
					$this->validation->set_message('validate_name','This menu already exist!');
					return false;
				}else{
					return true;
				}
			}else{
				$this->validation->set_message('validate_name','Only alphabet and number are allowed.');
				return false;
			}
		}else{
			$this->validation->set_message('validate_name','This field is required.');
			return false;
		}
		
	}

	public function get_menu(){

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
		$result = $this->menu->getMenuById($id,'m.id,m.menu_name,m.status,m.icon_code,m.active_class,m.controller_uri,m.sort_no');
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No menu found!';
			$response['error'] = 'No menu found!';
			$response['url'] = '';

		}else{

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}

	public function update_menu(){

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
		$response['message'] = 'Menu Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->menu->getMenuById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No menu found!';
			$response['error'] = 'No menu found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
        $this->validation->set_rules('menu_name','Menu Name','trim|required|callback_validate_name_edit['.$id.']');
        $this->validation->set_rules('controller_uri','Controller URI','trim|required|callback_validate_uri');
        $this->validation->set_rules('icon_code','Icon Code','trim|required');
        $this->validation->set_rules('active_class','Active Class','trim|required');
        $this->validation->set_rules('sort_no','Controller Name','trim|required|callback_validate_sort_edit['.$id.']');
        $this->validation->set_rules('status','Status','trim|required');
        $this->validation->set_message('required','This field is required.');
        $this->validation->set_message('integer','Only numbers.');


		if($this->validation->run() === false){

            $fields = array('menu_name','icon_code','active_class','controller_uri','sort_no','status');
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
			$this->db->update('menus',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	public function validate_name_edit($name,$id)
	{

        $name = $this->security->xss_clean($name);


		if(!empty($name)){
			if (preg_match('/^[a-zA-Z0-9 ]+$/', $name)) {
                $result = $this->menu->getMenuByName($name);
				if($result->num_rows() > 0){
					$result = $result->row();
					if($result->id == $id){

						return true;

					}else{

						$this->validation->set_message('validate_name_edit','This menu already exist!');
						return false;

					}

				}else{
					return true;
				}
			}else{
				$this->validation->set_message('validate_name_edit','Only alphabets and numbers are allowed.');
				return false;
			}
		}else{
			$this->validation->set_message('validate_name_edit','This field is required.');
			return false;
		}

	}


    public function validate_sort_edit($sort,$id)
    {

        $sort = $this->security->xss_clean($sort);


        if(!empty($sort)){
            if (preg_match('/^[0-9]+$/', $sort)) {
                $result = $this->menu->getMenuBySort($sort);
                if($result->num_rows() > 0){
                    $result = $result->row();
                    if($result->id == $id){

                        return true;

                    }else{

                        $this->validation->set_message('validate_sort_edit','This sort no already exist!');
                        return false;

                    }

                }else{
                    return true;
                }
            }else{
                $this->validation->set_message('validate_sort_edit','Only numbers are allowed.');
                return false;
            }
        }else{
            $this->validation->set_message('validate_sort_edit','This field is required.');
            return false;
        }

    }

	public function delete_menu(){

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
		$response['message'] = 'Menu Deleted Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->menu->getMenuById($id);

		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No controller found!';
			$response['error'] = 'No controller found!';
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
		$this->db->update('menus',$dbData);
		
		echo json_encode($response);
		exit;

	}
	
}
