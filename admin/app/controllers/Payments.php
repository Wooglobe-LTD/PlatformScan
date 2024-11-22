<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'payments';
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
			'assets/js/payment.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'payments'),
            'can_add'=>role_permitted_html(false,'payments','add_payment'),
            'can_edit'=>role_permitted_html(false,'payments','update_payment'),
            'can_delete'=>role_permitted_html(false,'payments','delete_payment'),

        );
		$this->load->model('Payment_Model','payment');
		$this->load->model('User_Model','user');
        $this->load->model('Video_Deal_Model', 'deal');
    }

	public function index() {
		auth();
        role_permitted(false,'payments');
		$this->data['title'] = 'Payments Management';
		$this->data['content'] = $this->load->view('payments/listing',$this->data,true);
		$this->load->view('common_files/template',$this->data);
	}
	
	public function payments_listing() {
		$auth_ajax = auth_ajax();
        if($auth_ajax){
			echo json_encode($auth_ajax);
			exit;
		}
        $role_permitted_ajax = role_permitted_ajax(false,'payments');
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
			$start = $params['payables_only'] == "true"? 0: $params['start'];
		}
		if(isset($params['length'])){
			$limit = $params['payables_only'] == "true"? -1: $params['length'];
		}
        if(isset($params['video_id'])){
            $video_id = $params['video_id'];
        }
		if(isset($params['order'])){
			$orderby = $params['columns'][$params['order'][0]['column']]['name'].' '.$params['order'][0]['dir'];
		}
		
		$result = $this->payment->getAllPayments(0,'c.symbol,u.full_name,u.email,u.paypal_email,u.address,u.id user_id,e.paid, vl.unique_key',$search,$start,$limit,$orderby,$params['columns']);
		$resultCount = $this->payment->getAllPayments();
		$response = array();
		$data = array();

		foreach($result->result() as $row){
			$r = array();
            $next_payment = $this->deal->getNextPayment($row->user_id);
            if ($params['payables_only'] == "true" && $next_payment < 100) {
                continue;
            }
            $links = '';
            $default_tax = 0;
            $taxation = $this->payment->getTaxRateByUser($row->user_id);
            $country = (isset($taxation->country) && !empty($taxation->country))? $taxation->country: 'Default';
            $tax = (isset($taxation->tax_rate) && $taxation->tax_rate != null)? $taxation->tax_rate: 20;
            if($row->paid == 0){
                $links .= '<a 
                            title="Pay"
                            href="javascript:void(0);"
                            class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light edit-earning"
                            data-id="'.$row->user_id.'"
                            data-title="'.$row->full_name.'"
                            data-country="'.$country.'"
                            data-address="'.$row->address.'"
                            data-amount="'.$next_payment.'"
                            data-tax="'.$tax.'"
                            data-auth="0"
                        >
                            Pay
                        </a> ';
                $links .= '<a 
                            title="PayPal"
                            href="javascript:void(0);"
                            class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light edit-earning"
                            data-id="'.$row->user_id.'"
                            data-title="'.$row->full_name.'"
                            data-country="'.$country.'"
                            data-address="'.$row->address.'"
                            data-amount="'.$next_payment.'"
                            data-tax="'.$tax.'"
                            data-auth="1"
                        >
                            PayPal
                        </a> ';
            }else{
                $links .= '<a title="Pay" href="javascript:void(0);" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" data-id="'.$row->user_id.'" data-title="'.$row->full_name.'">Processed</a> ';
            }
            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }

			$r[] = $row->full_name;
            $r[] = $row->unique_key;
			$r[] = $row->email;
			if(empty($row->paypal_email)){
                $r[] = '<a title="Update" href="javascript:void(0);" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light edit-email" data-id="' . $row->user_id . '" data-title="' . $row->full_name . '">Update</a> ';
            }else{
                $r[] = $row->paypal_email;
            }

			$r[] = $row->address;
            if($row->paid == 0){
                $r[] = 'Pending';
            }else{
                $r[] = 'Payout Via PayPal';
            }
            $button = '';

			if($next_payment < $this->data['setting']->payment_threshold){
                $button = '<a class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)">'.getDefaultCurrencySymbol().$next_payment.'</a>';
            }else{
                $button = '<a class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)">'.getDefaultCurrencySymbol().$next_payment.'</a>';
            }
            $r[] = $button;
            $r['payment'] = $next_payment;

			$data[] = $r;
		}
        if ($params['columns'][$params['order'][0]['column']]['name'] == "e.client_net_earning") {
            usort($data, function ($a, $b) {
                if ($params['order'][0]['dir'] == "asc")
                    return $a['payment'] <=> $b['payment'];
                return $b['payment'] <=> $a['payment'];
            });
        }

		$response['code'] = 200;
		$response['message'] = 'Listing';
		$response['error'] = '';
		$response['data'] = $data;
		$response['recordsTotal'] = $params['payables_only'] == "true"? count($data): $resultCount->num_rows();
		$response['recordsFiltered'] = $params['payables_only'] == "true"? count($data): $resultCount->num_rows();
		echo json_encode($response);
		exit;
	}

	public function get_earning() {
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

    public function add_earning() {
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
        $this->validation->set_rules('earning_amount','Parent Category','trim|required');
        $this->validation->set_rules('earning_date','Category Title','trim|required');
        $this->validation->set_rules('earning_type_id','Status','trim|required');
        $this->validation->set_rules('currency_id','Currency','trim|required');
        $this->validation->set_rules('transaction_id','Transaction Id','trim|required');
        $this->validation->set_rules('transaction_detail','Transaction Detail','trim|required');
        if($dbData['earning_type_id'] == 1){
            $this->validation->set_rules('social_source_id','Status','trim|required');
        }else if ($dbData['earning_type_id'] == 2){
            $this->validation->set_rules('partner_id','Status','trim|required');
        }
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('earning_amount','earning_date','earning_type_id','social_source_id','partner_id','currency_id','transaction_id','transaction_detail');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{

            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['created_by'] = $this->sess->userdata('adminId');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->insert('earnings',$dbData);
        }

        echo json_encode($response);
        exit;


    }

	public function update_earning() {
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
        $this->validation->set_rules('earning_amount','Parent Category','trim|required');
        $this->validation->set_rules('earning_date','Category Title','trim|required');
        $this->validation->set_rules('earning_type_id','Status','trim|required');
        $this->validation->set_rules('currency_id','Currency','trim|required');
        $this->validation->set_rules('transaction_id','Transaction Id','trim|required');
        $this->validation->set_rules('transaction_detail','Transaction Detail','trim|required');
        if($dbData['earning_type_id'] == 1){
            $this->validation->set_rules('social_source_id','Status','trim|required');
        }else if ($dbData['earning_type_id'] == 2){
            $this->validation->set_rules('partner_id','Status','trim|required');
        }
        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('earning_amount','earning_date','earning_type_id','social_source_id','partner_id','currency_id','transaction_id','transaction_detail');
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
			$dbData['updated_at'] = date('Y-m-d H:i:s');
			$dbData['updated_by'] = $this->sess->userdata('adminId');
			$this->db->where('id',$id);
			$this->db->update('earnings',$dbData);
		}
		
		echo json_encode($response);
		exit;

	}

	public function delete_earning() {
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
		
		echo json_encode($response);
		exit;

	}

    public function make_payment() {

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Make Payment Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('user_id'));
        $result = $this->user->getUserById($id);
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No user found!';
            $response['error'] = 'No user found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $input = $this->security->xss_clean($this->input->post());
        $this->validation->set_rules('user_id','User Id','trim|required');
        $this->validation->set_rules('transaction_id','Transaction Id','trim|required');
        $this->validation->set_rules('transaction_detail','Transaction Detail','trim|required');
        $this->validation->set_rules('tax_rate','Tax Rate','trim|required');

        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('user_id', 'transaction_id', 'transaction_detail', 'tax_rate');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{

            $result = $this->payment->getAllPaymentsByUser($id,'e.conversion_rate, e.partner_currency from_id, e.currency_id to_id, e.client_net_earning payment,c.symbol,u.full_name,u.email,u.paypal_email,u.address,u.id user_id,e.id,e.video_id,v.lead_id,vl.unique_key,u.state_id,u.zip_code,u.city_id,c.code');
            $default_currency = getCurrencyByCode('USD');
            $unique_key_list = array_column($this->db->distinct()->select('inv_id')->get('earnings')->result_array(), 'inv_id');
            $this->load->helper('string');
            $random = random_string('numeric',6);
            while (in_array("RMT".$random, $unique_key_list))
            {
                $random = random_string('numeric',6);
            }
            $inv_id = "RMT".$random;
            $video_ids =[];
            $wg_ids =[];
            $earning_ids =[];
            $name = '';
            $address = '';
            $address2 = '';
            $country = '';
            $email = '';
            $paypal_email = '';
            $amount = 0;
            $currency = '';
            $currency_code = '';
            $method = 'PayPal';
            $methodEmail = 'PayPal';
            $data_log_query = '';
            if($result->num_rows()>0){
                // $earnings_data = array();
                // $payment_logs = array();
                $name = '';
                $email = '';
                $tax = $input['tax_rate'];
                foreach ($result->result() as $earning){
                    $name = $earning->full_name;
                    $address = $earning->address;
                    $address2 = $earning->city_id.', '.$earning->state_id.', '.$earning->zip_code;
                    $country = $earning->country_id;
                    $email = $earning->email;
                    $paypal_email = $earning->paypal_email;
                    $payment = $earning->payment - ($earning->payment * ($tax / 100));
                    
                    list($conved_payment, $conved_rate) = convertPaymentCurrency($earning->from_id, $earning->to_id, $payment, $earning->conversion_rate, $default_currency["id"]);
                    $amount = ($amount + $conved_payment);
                    
                    $currency = $default_currency['symbol'];
                    
                    $currency_code = $default_currency['code'];
                    $methodEmail = $earning->paypal_email;
                    if(!in_array($earning->video_id,$video_ids)){
                        $video_ids[] = $earning->video_id;
                    }
                    if(!in_array($earning->unique_key,$wg_ids)){
                        $wg_ids[] = $earning->unique_key;
                    }
                    if(!in_array($earning->id,$earning_ids)){
                        $earning_ids[] = $earning->id;
                    }
                    $data_log_query=$this->db->query('SELECT user_browser,user_ip_address,contract_signed_datetime,contract_view_datetime FROM `contract_logs` WHERE lead_id="'.$earning->lead_id.'"');
                    
                    $dbData = array();
                    $dbData['updated_at'] = date('Y-m-d H:i:s');
                    $dbData['updated_by'] = $this->sess->userdata('adminId');
                    $dbData['payment_transaction_id'] = $input['transaction_id'];
                    $dbData['payment_transaction_detail'] = $input['transaction_detail'];
                    $dbData['paid'] = 1;
                    $dbData['inv_id'] = $inv_id;
                    $dbData['paid_amount'] = $conved_payment;
                    $dbData['paid_conversion_rate'] = $conved_rate;

                    $insertData['video_id'] = $earning->video_id;
                    $insertData['lead_id'] = $earning->lead_id;
                    $insertData['user_id'] = $earning->user_id;
                    $insertData['created_at'] = date('Y-m-d H:i:s');
                    $insertData['created_by'] = $this->sess->userdata('adminId');
                    $insertData['inv_id'] = $inv_id;
                    $insertData['log_message'] = 'Payout Created';
                    
                    $this->db->where('id',$earning->id);
                    $this->db->update('earnings',$dbData);
                    $this->db->insert('payment_log',$insertData);
                }

                $payout = '';
                if($input['authenticate']) {
                    $payout = $this->paypalclient->test_payout($paypal_email,$amount,$currency_code,$inv_id);
                }
                else {
                    $this->load->library('PayPalClient');
                    $payout = $this->paypalclient->payout($paypal_email,$amount,$currency_code,$inv_id);
                }
                $payout = $payout->toArray();
                if(isset($payout['batch_header']['payout_batch_id'])){
                    $invData['invoice_id'] = $inv_id;
                    $invData['video_ids'] = implode(',',$video_ids);
                    $invData['wg_ids'] = implode(',',$wg_ids);
                    $invData['earning_ids'] = implode(',',$earning_ids);
                    $invData['amount'] = $amount;
                    $invData['currency'] = $currency_code;
                    $invData['paypal_id'] = $payout['batch_header']['payout_batch_id'];
                    $invData['paypal_response'] = json_encode($payout);
                    $invData['created_at'] = date('Y-m-d H:i:s');
                    $invData['created_by'] = $this->sess->userdata('adminId');
                    $invData['updated_at'] = date('Y-m-d H:i:s');
                    $invData['updated_by'] = $this->sess->userdata('adminId');
                    $this->db->insert('invoice',$invData);
                    // foreach($earnings_data as $dbData) {
                    //     $this->db->where('id', $earning->id);
                    //     $this->db->update('earnings', $dbData);
                    // }
                    // foreach($payment_logs as $dbData) {
                    //     $this->db->insert('payment_log',$dbData);
                    // }
                    if(isset($input['send_email_check']) && $input['send_email_check'] == 1) {
                        $template = $this->app->getEmailTemplateByCode('paypal_payment');
                        $subject = $template->subject;
                        $message = str_replace('@USER_NAME', $name, $template->message);
                        $sent = $this->email($email, $name, 'PartnerSupport@WooGlobe.com', 'WooGlobe Partner Support', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');
                    }
                }


            }else{
                $response['code'] = 201;
                $response['message'] = 'No Earnings found';
                $response['url'] = '';
            }

        }

        echo json_encode($response);
        exit;

    }

    public function payments_history() {
        auth();
        role_permitted(false,'payments');
        $this->data['title'] = 'Payments History';
        $this->data['content'] = $this->load->view('payments/listing_history',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function payments_history_listing() {
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'payments');
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

        $result = $this->payment->getAllPaymentsHistory('i.*',$search,$start,$limit,$orderby,$params['columns']);
        //echo $this->db->last_query();exit;
        $resultCount = $this->payment->getAllPaymentsHistory();
        $response = array();
        $data = array();

        foreach($result->result() as $row){
            $r = array();
            $links = '';
            //if($row->payment >= $this->data['setting']->payment_threshold){
            /*if($row->paid == 0){
                $links .= '<a title="Make Payment" href="javascript:void(0);" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light edit-earning" data-id="' . $row->user_id . '" data-title="' . $row->full_name . '">Make Payment</a> ';
            }*/


            //}
            /*if($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete Earning" href="javascript:void(0);" class="delete-earning" data-title="' . $row->title . '" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';

            }*/
            /*if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }*/

            $r[] = '<a title="Payment Details" href="javascript:void(0);" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light invoice-detail" data-id="' . $row->id . '" data-invoice-id="' . $row->invoice_id . '">'.$row->invoice_id.'</a>';
            $r[] = $row->wg_ids;
            $r[] = $row->currency;
            $r[] = $row->amount;
            if($row->status == 0){
                $r[] = 'In-Process';
            }else{
                $r[] = 'Paid';
            }
            /*$button = '';

            if($row->payment < $this->data['setting']->payment_threshold){
                $button = '<a class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)">'.$row->symbol.$row->payment.'</a>';
            }else{
                $button = '<a class="md-btn md-btn-danger md-btn-wave-light waves-effect waves-button waves-light" href="javascript:void(0)">'.$row->symbol.$row->payment.'</a>';
            }

            $r[] = $button;*/



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

    public function get_invoice_detail() {
	    $id = $this->input->post('id');
        $response['code'] = 200;
        $response['message'] = 'Detail';
        $response['error'] = '';
        if(empty($id)){
            $response['code'] = 201;
            $response['message'] = 'Invalid Id';
            echo json_encode($response);
            exit;
        }
        $invoice = $this->db->query('SELECT * FROM invoice WHERE id = '.$id)->row();
        if(empty($invoice)){
            $response['code'] = 201;
            $response['message'] = 'Invalid Id';
            echo json_encode($response);
            exit;
        }
        $result = $this->payment->getAllPaymentsByEarningsIdsHistory($invoice->earning_ids, 'e.client_net_earning payment,c.symbol,u.full_name,u.email,u.paypal_email,u.address,u.id user_id,e.id,e.video_id,v.lead_id,vl.unique_key,u.state_id,u.zip_code,u.city_id,c.code,vl.video_title,vl.video_url');
        /*echo '<pre>';
        print_r($invoice->earning_ids);
        exit;*/
        $logs = $this->db->query('SELECT  * FROM payment_log WHERE video_id in ('.$invoice->video_ids.') GROUP BY inv_id ORDER BY created_at DESC');
        $this->data['invoice'] = $invoice;
        $this->data['videos'] = $result;
        $this->data['logs'] = $logs;
        $html = $this->load->view('payments/detail',$this->data,true);;
        $response['html'] = $html;
        echo json_encode($response);
        exit;
    }

    public function update_user_paypal_email() {

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }

        $response = array();

        $response['code'] = 200;
        $response['message'] = 'User Paypal Email Updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('user_id'));
        $result = $this->user->getUserById($id);
        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No user found!';
            $response['error'] = 'No user found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $input = $this->security->xss_clean($this->input->post());
        $this->validation->set_rules('user_id','User Id','trim|required');
        $this->validation->set_rules('paypal_email','Paypal Email','trim|required|valid_email');


        $this->validation->set_message('required','This field is required.');

        if($this->validation->run() === false){

            $fields = array('user_id','paypal_email');
            $errors = array();
            foreach($fields as $field){
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        }else{
            $dbData['paypal_email'] = $input['paypal_email'];
            $this->db->where('id',$input['user_id']);
            $this->db->update('users',$dbData);
            //$response['q'] = $this->db->last_query();

        }

        echo json_encode($response);
        exit;

    }

    public function send_paypal_email() {
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
		$response = [
            'code' => 200,
		    'message' => "Email Sent Successfully!",
		    'error' => ""
        ];
        
        $user_id = $this->security->xss_clean($this->input->post('user_id'));
        $user = $this->user->getUserById($user_id, 'u.full_name, u.email');

        $template = $this->app->getEmailTemplateByCode('request_paypal_email');
        $subject = $template->subject;
        $message = str_replace('@USER_NAME', $user->full_name, $template->message);
        $sent = $this->email($user->email, $user->full_name, 'PartnerSupport@WooGlobe.com', 'WooGlobe Partner Support', $subject, $message, $cc = '', $bcc = '', $replyto = '', $replyto_name = '');

        if(!$sent) {
            $response = [
                'code' => 201,
                'message' => "Error in sending email!",
                'error' => ""
            ];
        }

        echo json_encode($response);
        exit;
    }
    
    public function payout($email, $amount, $currency, $transaction_id) {
        $this->load->library('PayPalClient');

        // Check if it's a POST request for payment creation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->paypalclient->paymentAuth($amount, $currency, $transaction_id);
        }
        // Check if it's a GET request for payment execution
        if (isset($_GET['paymentId']) && isset($_GET['PayerID'])) {
            $this->paypalclient->executePayment($email, $amount, $currency, $transaction_id);
        }
    }

}
