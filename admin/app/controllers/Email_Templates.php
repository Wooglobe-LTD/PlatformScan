<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_Templates extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'email_templates';
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
			'assets/js/email.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'email_templates'),
            'can_add'=>role_permitted_html(false,'email_templates','add_email_template'),
            'can_edit'=>role_permitted_html(false,'email_templates','update_email_template'),
            'can_delete'=>role_permitted_html(false,'email_templates','delete_email_template')
        );
		$this->load->model('Email_Template_Model','template');
        
    }
	public function index()
	{
		auth();
        role_permitted(false,'email_templates');
		$this->data['title'] = 'Email Templates Management';
		$this->data['content'] = $this->load->view('email_templates/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function email_template_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'email_templates');
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
		
		$result = $this->template->getAllEmailTemplates('et.id,et.title,et.short_code,et.subject,et.message,case when (et.status = 1) THEN "Active" ELSE "Inactive" END as status',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->template->getAllEmailTemplates();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Email Template" href="' . base_url('edit_email_template/' . $row->id) . '" class="edit-email-template" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';
            }
            if($this->data['assess']['can_edit']) {
                $links .= '| <a title="Delete Email Template" href="javascript:void(0);" class="delete-email-template" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';
            }
            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }
			$r[] = $row->title;
			$r[] = $row->short_code;
			$r[] = $row->subject;
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

	public function email_template_add()
	{
        auth();
        role_permitted(false,'email_templates','add_email_template');
		$this->data['title'] = 'Add New Email Template';
		$this->data['content'] = $this->load->view('email_templates/add',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}

	public function add_email_template(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'email_templates','add_email_template');
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }

		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'New Email Template Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('title','Email Template Title','trim|required|callback_validate_title');
		$this->validation->set_rules('short_code','Email Template Short Code','trim|required');
		$this->validation->set_rules('subject','Email Subject','trim|required');
		$this->validation->set_rules('message','Email Content','trim|required');
		$this->validation->set_rules('status_u','Status','trim|required');
		$this->validation->set_message('required','This field is required.');
		$this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
		if($this->validation->run() === false){
			
			$fields = array('title','short_code','subject','message','status_u');
			$errors = array();
			foreach($fields as $field){
				$errors[$field] = form_error($field);
			}
			$response['code'] = 201;
			$response['message'] = 'Validation Errors!';
			$response['error'] = $errors;
			$response['url'] = '';
			
		}else{
			$response['url'] = $this->data['url'].'email_templates';
			$dbData = $this->input->post();
			$status = $dbData['status_u'];
			$dbData['status'] = $status;
			unset($dbData['status_u']);


			$dbData['created_at'] = date('Y-m-d H:i:s');
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['created_by'] = $this->sess->userdata('adminId');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->insert('email_templates',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

    public function validate_title($title)
    {

        $title = $this->security->xss_clean($title);


        if(!empty($title)){
            if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
                $result = $this->template->getEmailTemplateByTitle($title);
                if($result->num_rows() > 0){
                    $this->validation->set_message('validate_title','This email template already exist!');
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

	

	public function get_email_template(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'email_templates');
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Email template found!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->template->getEmailTemplateById($id,'title');
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No email template found!';
			$response['error'] = 'No email template found!';
			$response['url'] = '';

		}else{

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}

	public function edit_email_template($id)
	{
		auth();
        role_permitted(false,'email_templates','update_email_template');
		$result = $this->template->getEmailTemplateById($id,'et.*');
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
		$this->data['title'] = 'Edit Email Template';
		$this->data['id'] = $id;
		$this->data['data'] = $result;
		$this->data['content'] = $this->load->view('email_templates/edit',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}

	public function email_template_action(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'email_templates','update_email_template');
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Email Template Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->template->getEmailTemplateById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No email template found!';
			$response['error'] = 'No email template found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
        $this->validation->set_rules('title','Email Template Title','trim|required|callback_validate_title_edit['.$id.']');
        $this->validation->set_rules('short_code','Email Template Short Code','trim|required');
        $this->validation->set_rules('subject','Email Subject','trim|required');
        $this->validation->set_rules('message','Email Content','trim|required');
        $this->validation->set_rules('status_u','Status','trim|required');
        $this->validation->set_message('required','This field is required.');
        $this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
        if($this->validation->run() === false){

            $fields = array('title','short_code','subject','message','status_u');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{
            $response['url'] = $this->data['url'].'email_templates';
            $dbData = $this->input->post();
            $status = $dbData['status_u'];
            $dbData['status'] = $status;
            unset($dbData['status_u']);
			

			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->where('id',$id);
			$this->db->update('email_templates',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

    public function validate_title_edit($title,$id)
    {

        $email = $this->security->xss_clean($title);


        if(!empty($title)){
            if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
                $result = $this->template->getEmailTemplateByTitle($title);
                if($result->num_rows() > 0){
                    $result = $result->row();
                    if($result->id == $id){

                        return true;

                    }else{

                        $this->validation->set_message('validate_title_edit','This email template already exist!');
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

	

	public function delete_email_template(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'email_templates','delete_email_template');
        if($auth_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Email Template deleted Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->template->getEmailTemplateById($id);

		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No email template found!';
			$response['error'] = 'No email template found!';
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
		$this->db->update('email_templates',$dbData);
		
		echo json_encode($response);
		exit;

	}
	

}
