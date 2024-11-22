<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Earnings extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'earnings';
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
            //'bower_components/tinymce/tinymce.min.js',
			//'assets/js/pages/forms_wysiwyg.js',
			'assets/js/pages/forms_file_upload.js',
			'assets/js/custom/dropify/dist/js/dropify.min.js',
			'assets/js/pages/forms_file_input.min.js',
			'assets/js/earnings.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'earnings'),
            'can_add'=>role_permitted_html(false,'earnings','add_earning'),
            'can_edit'=>role_permitted_html(false,'earnings','update_earning'),
            'can_delete'=>role_permitted_html(false,'earnings','delete_earning'),

        );
		$this->load->model('Earning_Model','earning');
		$this->load->model('Video_Model','video');
		$this->load->model('Earning_Type_Model','earning_type');
		$this->load->model('Social_Sources_Model','source');
		$this->load->model('User_Model','user');

    }
	public function index()
	{
		auth();
        role_permitted(false,'earnings');
        $params = $this->security->xss_clean($this->input->get());
        $video_id = 0;
        if(isset($params['video_id'])){
            $video_id = $params['video_id'];
        }
		$this->data['title'] = 'Earning Management';
		$this->data['video_id'] = $video_id;
		$this->data['earning_type'] = $this->earning_type->getAllEarningTypesActive('et.id,et.earning_type','',0,0,'et.earning_type ASC');
		$this->data['sources'] = $this->source->getAllSourcesActive('ss.id,ss.sources','',0,0,'ss.sources ASC');
		$this->data['partners'] = $this->user->getAllUsersActive(2,'u.id,u.full_name,u.email','',0,0,'u.full_name ASC');
		$this->data['content'] = $this->load->view('earnings/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	
	public function earning_listing(){
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'earnings');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$params = $this->security->xss_clean($this->input->get());
		$search = '';
		$orderby = '';
        $video_id = 0;
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
        if(isset($params['video_id'])){
            $video_id = $params['video_id'];
        }
		if(isset($params['order'])){
			$orderby = $params['columns'][$params['order'][0]['column']]['name'].' '.$params['order'][0]['dir'];
		}
		
		$result = $this->earning->getAllEarnings($video_id,'e.id,v.title,e.currency_id, e.conversion_rate,e.partner_currency,e.actual_amount,e.earning_amount,e.expense_amount,e.wooglobe_net_earning,e.wooglobe_total_share,e.revenue_share_amount,e.client_net_earning,DATE_FORMAT(e.earning_date,\'%M %d, %Y\') as earning_date,et.earning_type,e.status,case when (e.paid = 1) THEN "Paid" ELSE "Unpaid" END as payment_mode,ss.sources,u.full_name,c.symbol,e.transaction_id,e.transaction_detail,e.expense,e.expense_detail,e.payment_transaction_id,e.payment_transaction_detail,DATE_FORMAT(pl.created_at,\'%M %d, %Y\') as transaction_date, vl.unique_key,e.advanced',$search,$start,$limit,$orderby,$params['columns']);
		//echo $this->db->last_query();exit;
		$resultCount = $this->earning->getAllEarnings($video_id);
		$response = array();
		$data = array();
        $default_currency = getDefaultCurrency();
		foreach($result->result() as $row){
			$r = array();
            $links = '';
            if($this->data['assess']['can_edit']) {
                $class = "edit-earning";
                if($row->advanced == 1){
                    $class = "edit-earning-advance";
                }
                $links .= '<a title="Edit Earning" href="javascript:void(0);" class="'.$class.'" data-id="' . $row->id . '" data-title="' . $row->title . '"><i class="material-icons">&#xE254;</i></a> ';

            }
            if($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete Earning" href="javascript:void(0);" class="delete-earning" data-title="' . $row->title . '" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';

            }
            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }
            // $query = $this->db->query("SELECT u.currency_id  FROM video_leads vl INNER  JOIN  users u  ON  vl.client_id = u.id WHERE vl.`unique_key` = '$row->uid'");
            // $user_currency_id = $query->row();
            // $query = $this->db->query("SELECT `code` FROM `currency` WHERE `id` = $user_currency_id->currency_id");
            // $User_currency_name = $query->row();
            $query = $this->db->query("SELECT `code` FROM `currency` WHERE `id` = $row->partner_currency");
            $currency_name = $query->row();
            $query = $this->db->query("SELECT `code` FROM `currency` WHERE `id` = $row->currency_id");
            $to_currency_name = $query->row();
            
			$r[] = $row->earning_date;
			$r[] = $row->unique_key;

            // list($conved_payment, $conved_rate) = convertPaymentCurrency($row->partner_currency, $row->currency_id, $row->earning_amount, $row->conversion_rate, $default_currency["id"]);
			$r[] = $row->symbol.round($row->earning_amount,2);
			// $r[] = $row->symbol.round($conved_payment,2);

            // list($conved_payment, $conved_rate) = convertPaymentCurrency($row->partner_currency, $row->currency_id, $row->expense_amount, $row->conversion_rate, $row->currency_id);
            $r[] = $row->symbol.round($row->expense_amount,2);
            
            list($conved_payment, $conved_rate) = convertPaymentCurrency($row->partner_currency, $row->currency_id, $row->client_net_earning, $row->conversion_rate, $default_currency["id"]);


            $r[] = $row->symbol.round($row->client_net_earning,2);
            // $r[] = $row->symbol.round($conved_payment,2);

            // list($conved_payment, $conved_rate) = convertPaymentCurrency($row->partner_currency, $row->currency_id, $row->wooglobe_total_share, $row->conversion_rate, $row->currency_id);
            $r[] = $row->symbol.round($row->wooglobe_total_share,2);

			if($row->payment_mode == "Paid"){
                $r[] = '<a title="Transaction Detail" href="javascript:void(0);" class="transaction-detail" data-id="' . $row->payment_transaction_id . '" data-detail="' . $row->payment_transaction_detail . '" data-date="' . $row->transaction_date . '">Paid</a> ';
            }else{
                $r[] = $row->payment_mode;
            }
			$r[] = $row->earning_type;
            $r[] = $currency_name->code;
            $r[] = $to_currency_name->code;
            $r[] = $row->actual_amount;
            // if($currency_name->code == $User_currency_name->code ) {
            //     $r[] = "NULL";
            // }else{
                $r[] = $row->conversion_rate;

            // }
            // $r[] = $conved_rate;
			$status = 'Pending Approval';
			if($row->status == 1){
                $status = 'Approved';
            }elseif ($row->status == 2){
                $status = 'Rejected';
            }
			$r[] = $status;
            //$r[] = $row->transaction_id;
            //$r[] = $row->transaction_detail;

            //$r[] = $row->expense_detail;



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


	

	public function get_earning(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'earnings');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Earning found!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->earning->getEarningById($id,'e.*');
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No earning found!';
			$response['error'] = 'No earning found!';
			$response['url'] = '';

		}else{

			$response['data'] = $result;

		}
		
		
		echo json_encode($response);
		exit;

	}


    public function add_earning(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'videos','add_earning');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Earning Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $dbData = $this->security->xss_clean($this->input->post());
        // $this->validation->set_rules('earning_amount','Parent Category','trim|required');
        $this->validation->set_rules('partner_currency','Partner Currency','trim|required');
        $this->validation->set_rules('actual_amount','Actual Amount','trim|required');
        $this->validation->set_rules('earning_date','Category Title','trim|required');
        $this->validation->set_rules('earning_type_id','Status','trim|required');
        // $this->validation->set_rules('conversion_rate','Conversion rate','trim|required');
        //$this->validation->set_rules('currency_id','Currency','trim|required');
        $this->validation->set_rules('transaction_id','Transaction Id','trim|required');
        $this->validation->set_rules('transaction_detail','Transaction Detail','trim|required');
        if($dbData['earning_type_id'] == 1){
            $this->validation->set_rules('social_source_id','Status','trim|required');
        }else if ($dbData['earning_type_id'] == 2){
            $this->validation->set_rules('partner_id','Status','trim|required');
        }
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('conversion_rate','actual_amount','earning_amount','earning_date','earning_type_id','social_source_id','partner_id','currency_id','transaction_id','transaction_detail');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{

            $video = $this->video->getVideoById($dbData['video_id']);
            if(!empty($video)){
                $user = $this->user->getUserById($video->user_id);
                if(!empty($user)){
    
                    // Code to restrict currency to user's first earning's currrency

                    // if(!empty($user->currency_id)){
                    //     $dbData['currency_id'] = $user->currency_id;
                    // }else
                    {
                        $userData['currency_id'] =  $dbData['currency_id'];
                        // $this->db->where('id',$user->id);
                        // $this->db->update('users',$userData);
                    }
                }
            }
            $dbData["conversion_rate"] = getConversionRate($dbData["partner_currency"], $dbData["currency_id"]);
            $dbData["earning_amount"] = round($dbData["actual_amount"] * $dbData["conversion_rate"], 2);

            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['created_by'] = $this->sess->userdata('adminId');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->insert('earnings',$dbData);
        }

        echo json_encode($response);
        exit;


    }


	public function update_earning(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'earnings','update_earning');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Earning Updated Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->earning->getEarningById($id);
		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No earning found!';
			$response['error'] = 'No earning found!';
			$response['url'] = '';
			echo json_encode($response);
			exit;

		}
        $dbData = $this->security->xss_clean($this->input->post());
        // $this->validation->set_rules('earning_amount','Parent Category','trim|required');
        $this->validation->set_rules('earning_date','Category Title','trim|required');
        $this->validation->set_rules('earning_type_id','Status','trim|required');
        $this->validation->set_rules('actual_amount','Actual Amount','trim|required');
        // $this->validation->set_rules('conversion_rate','Conversion rate','trim|required');
        //$this->validation->set_rules('currency_id','Currency','trim|required');
        $this->validation->set_rules('transaction_id','Transaction Id','trim|required');
        $this->validation->set_rules('transaction_detail','Transaction Detail','trim|required');
        if($dbData['earning_type_id'] == 1){
            $this->validation->set_rules('social_source_id','Status','trim|required');
        }else if ($dbData['earning_type_id'] == 2){
            $this->validation->set_rules('partner_id','Status','trim|required');
        }
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('conversion_rate','actual_amount','earning_amount','earning_date','earning_type_id','social_source_id','partner_id','currency_id','transaction_id','transaction_detail');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{

			
			unset($dbData['id']);
            
            $dbData["conversion_rate"] = getConversionRate($dbData["partner_currency"], $dbData["currency_id"]);
            $dbData["earning_amount"] = round($dbData["actual_amount"] * $dbData["conversion_rate"], 2);
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->where('id',$id);
			$this->db->update('earnings',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	

	public function delete_earning(){

		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'earnings','delete_earning');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
		$response = array();
		
		$response['code'] = 200;
		$response['message'] = 'Earning deleted Successfully!';
		$response['error'] = '';
		$response['url'] = '';
		$id = $this->security->xss_clean($this->input->post('id'));
		$result = $this->earning->getEarningById($id);

		if(!$result){

			$response['code'] = 201;
			$response['message'] = 'No earning found!';
			$response['error'] = 'No earning found!';
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
		$this->db->update('earnings',$dbData);
		$query = "
		    SELECT vl.unique_key
		    FROM earnings e 
            INNER JOIN videos v
            v.id = e.video_id
            INNER JOIN video_leads vl
            ON vl.id = v.lead_id
            
		";
		$lead = $this->db->query($query)->row();
        $data['validation'] = 0;
        if($lead){
            $this->db->where('wg_id',$lead->unique_key);
            $this->db->update('raw_payments_uploads',$data);
        }

		echo json_encode($response);
		exit;

	}
	






}
