<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Roles extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'roles';
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
            $js[] = 'assets/js/roles.js';
        }
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->load->model('Role_Model','role');
        $this->load->model('Menu_Model','menu');
        
    }
	public function index()
	{
		auth();
        role_permitted(false);
		$this->data['title'] = 'Staff Members Roles Management';
		$this->data['content'] = $this->load->view('roles/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function roles_listing(){
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
		
		$result = $this->role->getAllRoles('ar.id,ar.title,case when (ar.status = 1) THEN "Active" ELSE "Inactive" END as status',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->role->getAllRoles();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            if(role_permitted_html(false)) {
                $links = '<a title="Edit Role" href="javascript:void(0);" class="edit-role" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> | <a title="Delete Role" href="javascript:void(0);" class="delete-role" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>| <a title="Assign Permissions"  href="javascript:void(0);" class="role-permissions" data-id="' . $row->id . '"><i class="material-icons">&#xE85E;</i></a>';
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

	public function add_role(){

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
		$response['message'] = 'New Role Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('title','Role Title','trim|required|callback_validate_title');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');

		if($this->validation->run() === false){
			
			$fields = array('title','status');
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
			$dbData['role_type_id'] = 2;
			$dbData['created_at'] = date('Y-m-d H:i:s');
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['created_by'] = $this->sess->userdata('adminId');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->insert('admin_roles',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	public function validate_title($title)
	{

        $title = $this->security->xss_clean($title);


		if(!empty($title)){
			if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
				$result = $this->role->getRoleByTitle($title);
				if($result->num_rows() > 0){
					$this->validation->set_message('validate_title','This role already exist!');
					return false;
				}else{
					return true;
				}
			}else{
				$this->validation->set_message('validate_title','Only alphabet and numbers are allowed.');
				return false;
			}
		}else{
			$this->validation->set_message('validate_title','This field is required.');
			return false;
		}
		
	}

	public function get_role(){

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
		$result = $this->role->getRoleById($id,'ar.id,ar.title,ar.status');
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No role found!';
			$response['error'] = 'No role found!';
			$response['url'] = '';

		}else{

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}

	public function update_role(){

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
		$response['message'] = 'Role Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->role->getRoleById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No role found!';
			$response['error'] = 'No role found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
		$this->validation->set_rules('title','Role Title','trim|required|callback_validate_title_edit['.$id.']');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');

		if($this->validation->run() === false){
			
			$fields = array('title','status');
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
			$this->db->update('admin_roles',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	public function validate_title_edit($title,$id)
	{

        $title = $this->security->xss_clean($title);


		if(!empty($title)){
			if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
                $result = $this->role->getRoleByTitle($title);
				if($result->num_rows() > 0){
					$result = $result->row();
					if($result->id == $id){

						return true;

					}else{

						$this->validation->set_message('validate_title_edit','This role already exist!');
						return false;

					}
					
				}else{
					return true;
				}
			}else{
				$this->validation->set_message('validate_title_edit','Only alphabets and namubers are allowed.');
				return false;
			}
		}else{
			$this->validation->set_message('validate_title_edit','This field is required.');
			return false;
		}
		
	}

	public function delete_role(){

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
		$response['message'] = 'Role Deleted Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->role->getRoleById($id);

		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No role found!';
			$response['error'] = 'No role found!';
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
		$this->db->update('admin_roles',$dbData);
		
		echo json_encode($response);
		exit;

	}

    public function role_permissions($id)
    {
        auth();
        role_permitted();
        $this->data['title'] = 'Assign Permission To The Role';
        $result = $this->role->GetRoleById($id);
        if(!$result){
            redirect('roles');
        }
        $this->data['data'] = $result;
        $this->data['permissionts'] = $this->role->getRolePermissions($id);
        $this->data['menus'] = $this->menu->getActiveMenus('m.id,m.menu_name');
        /*echo '<pre>';
        print_r($this->data['permissionts']);
        exit;*/
        $this->data['content'] = $this->load->view('roles/permission',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function save_permission($id){
        auth();
        role_permitted(false);
        $permissions = $this->security->xss_clean($this->input->post());
        $this->db->where('admin_role_id',$id);
        $this->db->delete('admin_role_permissions');
        foreach ($permissions as $i=>$permission){
            foreach ($permission as $action){
                $dbData['admin_role_id'] = $id;
                $dbData['menu_id'] = $i;
                $dbData['menu_action_id'] = $action;
                $this->db->insert('admin_role_permissions',$dbData);
            }
        }
        $this->sess->set_flashdata('msg','Permission save successfully!');
        redirect('roles');
    }
	
}
