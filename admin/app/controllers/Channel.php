<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Channel extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'channel';
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
			'assets/js/channel.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->load->model('Channel_Model','channel');

    }
	public function index()
	{
		auth();
		$this->data['title'] = 'Channels Management';
		$this->data['Clients'] = $this->channel->getUsers('u.id,u.full_name');
		$userArray = array();
		foreach ($this->data['Clients']->result() as $users){

		    $userArray[] = array('text'=>$users->full_name,'value'=>$users->id);
        }
        $this->data['jUser'] = json_encode($userArray);
        $this->data['jStatus'] = json_encode(array(array('text'=>'Active','value'=>1),array('text'=>'Inactive','value'=>0)));
		$this->data['content'] = $this->load->view('channel/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function channel_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
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
		
		$result = $this->channel->getAllChannels('c.id,c.name,u.full_name,(case when (c.status = 1) THEN "Active" ELSE "Inactive" END) as status',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->channel->getAllChannels();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '<a title="Edit Channel" href="javascript:void(0);" class="edit-channel" data-id="'.$row->id.'"><i class="material-icons">&#xE254;</i></a> | <a title="Delete Channel" href="javascript:void(0);" class="delete-channel" data-id="'.$row->id.'"><i class="material-icons">&#xE92B;</i></a>';
            $r[] = $links;
			$r[] = $row->name;
			$r[] = $row->full_name;
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

	public function add_channel(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'New Channel Added Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		
		$this->validation->set_rules('name','Full Name','trim|required|alpha_numeric_spaces|is_unique[channels.name]');
		$this->validation->set_rules('user_id','Gender','trim|required');
		$this->validation->set_rules('status','Status','trim|required');
		$this->validation->set_message('required','This field is required.');
		$this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
		$this->validation->set_message('is_unique','This channel already in use!');

		if($this->validation->run() === false){
			
			$fields = array('name','user_id','status');
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
			$this->db->insert('channels',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}



	public function get_channel(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Record found!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->channel->getChannelById($id,'c.id,c.name,c.user_id,c.status');
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No Channel found!';
			$response['error'] = 'No Channel found!';
			$response['url'] = '';

		}else{

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}

	public function update_channel(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Channel Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->channel->getChannelById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No Channel found!';
			$response['error'] = 'No Channel found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
        $this->validation->set_rules('name','Full Name','trim|required|callback_validate_channel_edit['.$id.']');
        $this->validation->set_rules('user_id','Gender','trim|required');
        $this->validation->set_rules('status','Status','trim|required');
        $this->validation->set_message('required','This field is required.');
        $this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
        $this->validation->set_message('is_unique','This Channel already exist.');
		
		if($this->validation->run() === false){
			
			$fields = array('name','user_id','status');
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
			$this->db->update('channels',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	public function validate_channel_edit($name,$id)
	{

        $name = $this->security->xss_clean($name);
		
		if(!empty($name)){
			if (preg_match('/^[a-z0-9 ]+$/i',$name)) {
				$result = $this->channel->getChannelByName($name);
				if($result->num_rows() > 0){
					$result = $result->row();
					if($result->id == $id){

						return true;

					}else{

						$this->validation->set_message('validate_channel_edit','This channel already in use!');
						return false;

					}
					
				}else{
					return true;
				}
			}else{
				$this->validation->set_message('validate_channel_edit','Only alphabet and number are allowed.');
				return false;
			}
		}else{
			$this->validation->set_message('validate_channel_edit','This field is required.');
			return false;
		}
		
	}

	public function delete_channel(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Channel Deleted Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->channel->getChannelById($id);

		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No Channel found!';
			$response['error'] = 'No Channel found!';
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
		$this->db->update('channels',$dbData);
		
		echo json_encode($response);
		exit;

	}
	
}
