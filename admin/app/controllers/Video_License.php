<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_License extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'video_license';
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
			'assets/js/vlicense.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'video_license'),
            'play_video'=>role_permitted_html(false,'videos'),
        );
		$this->load->model('Video_License_Model','license');
        
    }
	public function index()
	{
		auth();
        role_permitted(false,'video_license');
		$this->data['title'] = 'Video License Management';
		$this->data['content'] = $this->load->view('video_license/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function video_licenses_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'video_license');
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
		
		$result = $this->license->getAllLicenseLeads('u.full_name,u.email as uemail,v.title,v.url,vl.id,vl.partner_id,vl.video_id,vl.country_id,vl.license_type_id,vl.duration,vl.programme_or_publication as description,vl.social_media,vl.url as surl,c.name,lt.type, case when (vl.status = 1) THEN "Active" ELSE "Inactive" END as status,vl.email,vl.country_code,vl.mobile',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->license->getAllLicenseLeads();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $links = '<a href="javascript:void(0);" class="license_detail" data-id="'.$row->id.'"><i class="material-icons">&#xE873;</i></a> ';
            if($this->data['assess']['play_video']) {
                $links .= '| <a href="javascript:void(0);" class="play-video" data-id="'.$row->video_id.'"><i class="material-icons">&#xE04A;</i></a>';

            }

            $r[] = $links;
			$r[] = '+'.$row->country_code.$row->mobile;
			$r[] = $row->email;
			$r[] = $row->title;
			$r[] = $row->type;
			//$r[] = $row->status;

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





	public function get_license(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}

        $role_permitted_ajax = role_permitted_ajax(false,'video_license');
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
		$result = $this->license->getLicenseLeadById($id,'u.full_name,u.email as uemail,v.title,v.url,v.description as vdescription,vl.programme_or_publication,vl.id,vl.partner_id,vl.video_id,vl.country_id,vl.license_type_id,vl.duration,vl.social_media,vl.url as surl,c.name,lt.type, case when (vl.status = 1) THEN "Active" ELSE "Inactive" END as status,vl.territory,vl.exclusivity,vl.email,vl.country_code,vl.mobile');
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




	
}
