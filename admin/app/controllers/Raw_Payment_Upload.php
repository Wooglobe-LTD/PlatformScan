<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Raw_Payment_Upload extends APP_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['active'] = 'categories_mrss';
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
            'assets/js/rawpaymentupload2.js'
        );
        $this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'rawpaymentupload'),
            'can_add'=>role_permitted_html(false,'rawpaymentupload','add_rawpaymentupload'),
            'can_edit'=>role_permitted_html(false,'rawpaymentupload','update_rawpaymentupload'),
            'can_delete'=>role_permitted_html(false,'rawpaymentupload','delete_rawpaymentupload')
        );
        $this->load->model('RawPaymentUpload_Model','rawpayment');
        $this->load->model('Categories_Model', 'mrss');
        $this->load->model('User_Model','user');
        $this->load->model('Video_Lead_Model', 'lead');

    }
    public function index()
    {
        auth();
        role_permitted(false,'rawpaymentupload');
        $this->data['title'] = 'Bulk Payments Management';
        $this->load->model('User_Model','user');
        $query = $this->user->getAllUsers(2);
        $users=$query->result();
        $this->data['users']=$users;
        $this->data['content'] = $this->load->view('rawpaymentuploads/listing',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }

    public function rawpaymentupload_listing(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'rawpaymentupload');
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

        //case when (c.type = 1) THEN "Category" ELSE "Custom" as type2,
        $result = $this->rawpayment->getAllRawPayment('pbu.id,pbu.lable,pbu.partner_id,pbu.created_at,u.full_name,
        (SELECT COUNT(p.id) FROM raw_payments_uploads p WHERE p.payment_bulk_upload_id = pbu.id) total_rows,
        (SELECT COUNT(pp.id) FROM raw_payments_uploads pp WHERE pp.payment_bulk_upload_id = pbu.id AND pp.import = 1) total_rows_import,
        (SELECT COUNT(pp.id) FROM raw_payments_uploads pp WHERE pp.payment_bulk_upload_id = pbu.id AND pp.import = 0) total_rows_not_import,
        (SELECT COUNT(pp.id) FROM raw_payments_uploads pp WHERE pp.payment_bulk_upload_id = pbu.id AND pp.validation = 1) total_rows_errors
        
        ',$search,$start,$limit,$orderby,$params['columns']);
        $resultCount = $this->rawpayment->getAllRawPayment();
        $response = array();
        $data = array();

        foreach($result->result() as $row){
            $r = array();
            $links = '';
            /*if($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Bulk Payment Upload" href="'.base_url('payment_bulk_upload_edit/'.$row->id).'" class="" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';

            }*/

            if($this->data['assess']['can_delete']) {
                $links .= '<a title="Delete Bulk Payment Upload" href="javascript:void(0);" class="delete-category" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';

            }
            $checkQuery = $this->db->query("SELECT * FROM raw_payments_uploads WHERE payment_bulk_upload_id = $row->id");
            if($checkQuery->num_rows() > 0) {
                $links .= '| <a title="View Data" href="' . base_url('import_payments/' . $row->id) . '" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light " data-id="' . $row->id . '">View Data</a>';
            }
            if($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }
            $partner = 'Category Feed';
            if($row->partner_id != 0){
                $partner = $this->rawpayment->getPartnerName($row->partner_id);
                $partner = $partner->full_name;
            }
            $r[] = $row->lable;
            $r[] = $row->full_name;
            $r[] = date('F d, Y',strtotime($row->created_at));
            $r[] = $row->total_rows;
            $r[] = $row->total_rows_import;
            $r[] = $row->total_rows_not_import;
            $r[] = $row->total_rows_errors;

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
    public function rawpaymentupload_export(){
        auth();
        role_permitted(false,'rawpaymentupload');



        //case when (c.type = 1) THEN "Category" ELSE "Custom" as type2,
        $result = $this->rawpayment->getAllRawPayment('pbu.id,pbu.lable,pbu.partner_id,pbu.created_at,u.full_name,
        (SELECT COUNT(p.id) FROM raw_payments_uploads p WHERE p.payment_bulk_upload_id = pbu.id) total_rows,
        (SELECT COUNT(pp.id) FROM raw_payments_uploads pp WHERE pp.payment_bulk_upload_id = pbu.id AND pp.import = 1) total_rows_import,
        (SELECT COUNT(pp.id) FROM raw_payments_uploads pp WHERE pp.payment_bulk_upload_id = pbu.id AND pp.import = 0) total_rows_not_import,
        (SELECT COUNT(pp.id) FROM raw_payments_uploads pp WHERE pp.payment_bulk_upload_id = pbu.id AND pp.validation = 1) total_rows_errors
        
        ');

        $data = array();
        $data[] = ['Label','Partner','Created At','Total Record','Total Record Imported','Total Record Not Imported','Faulty Records'];
        foreach($result->result() as $row){
            $r = array();
            $links = '';


            $r[] = $row->lable;
            $r[] = $row->full_name;
            $r[] = date('F d, Y',strtotime($row->created_at));
            $r[] = $row->total_rows;
            $r[] = $row->total_rows_import;
            $r[] = $row->total_rows_not_import;
            $r[] = $row->total_rows_errors;

            $data[] = $r;
        }
        $now = date('m_d_Y');
        $filename = './report/'.$now.'_bulk_payments_upload.csv';
        $url = base_url('report/'.$now.'_bulk_payments_upload.csv');
        $file = fopen($filename,"w");

        foreach ($data as $line) {
            fputcsv($file, $line);
        }

        fclose($file);
        redirect($url);
    }
    public function payment_bulk_upload()
    {
        auth();
        role_permitted(false, 'rawpaymentupload','add_rawpaymentupload');
        $currencies  = $this->lead->getCurencies();
        $this->data['currencies'] = $currencies;
        $this->data['mrss_partners'] = $this->mrss->getMrssPartners();
        $this->data['title'] = 'Video Leads Submission';
        $this->data['content'] = $this->load->view('rawpaymentuploads/add', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }

    public function bulk_payment_submit(){


        auth();
        role_permitted(false, 'rawpaymentupload','add_rawpaymentupload');


        //$this->validation->set_rules('parent_id','Parent Category','trim|required');
        $this->validation->set_rules('partner_id','Partner','trim|required');
        $this->validation->set_rules('lable','Label','trim|required');
        $this->validation->set_rules('file_currency_id','File currency id','trim|required');
        $this->validation->set_rules('conversion_currency_id','Conversion currency id','trim|required');
        // $this->validation->set_rules('conversion_rate','Conversion rate','trim|required');
        //$this->validation->set_rules('csv_file','CSV','trim|required');


        //$this->validation->set_message('required','This field is required.');
        $this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
        $this->validation->set_message('is_unique','This category URL already exist');

        if($this->validation->run() === false){
            auth();
            role_permitted(false, 'rawpaymentupload','add_rawpaymentupload');
            $this->data['mrss_partners'] = $this->mrss->getMrssPartners();
            $this->data['title'] = 'Video Leads Submission';
            $this->data['content'] = $this->load->view('rawpaymentuploads/add', $this->data, true);
            $this->load->view('common_files/template', $this->data);

        }else{

            $dbData = $this->security->xss_clean($this->input->post());
            $config['upload_path'] = './bulk_payments/';
            $config['allowed_types'] = 'csv';
            $config['encrypt_name'] = true;


            $this->load->library('upload', $config);
            
            // $this->upload->do_upload();
            // print_r($this->upload->display_errors());exit();
            if ( ! $this->upload->do_upload())
            {

                auth();
                role_permitted(false, 'rawpaymentupload','add_rawpaymentupload');
                $this->data['mrss_partners'] = $this->mrss->getMrssPartners();
                $this->data['title'] = 'Video Leads Submission';
                $this->data['content'] = $this->load->view('rawpaymentuploads/add', $this->data, true);
                $this->load->view('common_files/template', $this->data);
            }
            else
            {
                $data =$this->upload->data();
                $dbData['csv_file_path'] = $data['file_name'];
            }
            
            $query = $this->db->query('select conversion_rate from conversion_rates where 
            from_currency = '.$dbData['file_currency_id'].' AND
            to_currency = '.$dbData['conversion_currency_id'].' LIMIT 1');

            $dbData["conversion_rate"] = $query->row()->conversion_rate;

            $this->db->insert('payment_bulk_upload',$dbData);
            $id = $this->db->insert_id();
            $this->load->library('Csvimport');
            $csvData = $this->csvimport->get_array('./bulk_payments/'.$dbData['csv_file_path']);
            foreach ($csvData as $row){
                $rowdata['wg_id'] = $row['WG ID'];
                $rowdata['earning'] = $row['Earning'];
                $rowdata['earning_date'] = $row['Earning Date'];
                $rowdata['transaction_id'] = $row['Transaction Id'];
                $rowdata['transaction_detail'] = $row['Transaction Details'];
                $rowdata['earning_type'] = $row['Earning Type (Licensing or Socail_media)'];
                $rowdata['expense'] = $row['Expense %'];
                $rowdata['expense_detail'] = $row['Expense Detail'];
                $rowdata['partner_id'] = $dbData['partner_id'];
                $rowdata['csv_lable'] = $dbData['lable'];
                $rowdata['created_at'] = date('Y-m-d H:i:s');
                $rowdata['payment_bulk_upload_id'] = $id;
                $this->db->insert('raw_payments_uploads',$rowdata);
            }
            $this->sess->set_flashdata('msg','File Import Successfully.');
            redirect('bulk_payment_upload');

        }


    }

    public function payment_bulk_upload_update($id){

    auth();
    role_permitted(false, 'rawpaymentupload','update_rawpaymentupload');

    $file = $this->rawpayment->getFileById($id);
    if(empty($file)){
        redirect('dashboard');
    }
    $this->data['file'] = $file;
    //$this->validation->set_rules('parent_id','Parent Category','trim|required');
    $this->validation->set_rules('partner_id','Partner','trim|required');
    $this->validation->set_rules('lable','Label','trim|required');
    //$this->validation->set_rules('csv_file','CSV','trim|required');


    //$this->validation->set_message('required','This field is required.');
    $this->validation->set_message('alpha_numeric_spaces','Only alphabet and number are allowed.');
    $this->validation->set_message('is_unique','This category URL already exist');

    if($this->validation->run() === false){

        auth();
        role_permitted(false, 'rawpaymentupload','update_rawpaymentupload');
        $this->data['mrss_partners'] = $this->mrss->getMrssPartners();
        $this->data['title'] = 'Video Leads Submission';
        $this->data['content'] = $this->load->view('rawpaymentuploads/edit', $this->data, true);
        $this->load->view('common_files/template', $this->data);

    }else{

        $dbData = $this->security->xss_clean($this->input->post());
        $this->db->where('id',$id);
        $this->db->update('payment_bulk_upload',$dbData);
        redirect('rawpaymentupload');

    }


}

    public function delete_raw_payment($id,$file_id){

        auth();
        role_permitted(false, 'rawpaymentupload','delete_rawpaymentupload');

        $file = $this->rawpayment->getFileById($file_id);
        if(empty($file)){
            redirect('dashboard');
        }

        $this->db->where('id',$id);
        $this->db->delete('raw_payments_uploads');
        $this->sess->set_flashdata('msg','Record Deleted Successfully.');
        redirect('import_payments/'.$file_id);




    }
    public function import_payments($id){

        auth();
        role_permitted(false, 'rawpaymentupload','update_rawpaymentupload');

        $file = $this->rawpayment->getFileById($id);
        if(empty($file)){
            redirect('dashboard');
        }
        $this->data['file'] = $file;


        $payments = $this->rawpayment->getRawPaymentsById($id);
        $this->data['payments'] = $payments;
        /*$query = $this->db->query('SELECT id  FROM `videos` WHERE `lead_id` = "' . $lead_id . '"');
        $videoId = $query->row();
        $this->data['videoId'] = $videoId;*/
        $this->data['content'] = $this->load->view('rawpaymentuploads/payments', $this->data, true);
        $this->load->view('common_files/template', $this->data);



    }
    public function import_payments_csv($id){

        auth();
        role_permitted(false, 'rawpaymentupload','update_rawpaymentupload');

        $file = $this->rawpayment->getFileById($id);
        if(empty($file)){
            redirect('dashboard');
        }

        $payments = $this->rawpayment->getRawPaymentsById($id);
        $data = array();
        $data[] = ['WG ID','Earning','Earning Date','Transaction Id','Transaction Detail','Earning Type','Expense %','Expense Detail','Revenue Share','Actual Amout'];
        foreach ($payments as $i => $payment) {
            $checkValidation = 0;
            $query = $this->db->query('SELECT *  FROM `video_leads` WHERE `unique_key` = "' . $payment->wg_id . '"');
            if($query->num_rows() > 0){
                $leaddata = $query->row();
                $lead_id = $leaddata->id;
                $query = $this->db->query('SELECT *  FROM `videos` WHERE `lead_id` = "' . $lead_id . '"');
                if($query->num_rows() > 0) {
                    $videodata = $query->row();
                    $videoId = $videodata->id;
                    $checkQuery = $this->db->query(" SELECT * FROM earnings WHERE video_id = $videoId AND partner_id = $payment->partner_id AND transaction_id = '$payment->transaction_id' AND deleted = 0");
                    if($checkQuery->num_rows() > 0) {
                        $checkValidation = 1;
                    }
                }else{
                    $checkValidation = 1;
                }
            }else{
                $checkValidation = 1;
            }
            $query = $this->db->query('SELECT *  FROM `users` WHERE `id` = "' . $leaddata->client_id . '"');
            if($query->num_rows() > 0){
                $user_data = $query->row();
            }
            if($user_data->currency_id != $file->file_currency_id && $user_data->currency_id == $file->conversion_currency_id){
                $currency_symbol = getUserCurrencySymbolById($user_data->currency_id);
                $earning = (filter_var($payment->earning,FILTER_SANITIZE_NUMBER_INT) * $file->conversion_rate);
            }else{
                $currency_symbol = getUserCurrencySymbolById(2);
                $earning = filter_var($payment->earning,FILTER_SANITIZE_NUMBER_INT);

            }

            $r = array();
            $r[] = $payment->wg_id;
            $r[] = $payment->earning;
            $r[] = $payment->earning_date;
            $r[] = $payment->transaction_id;
            $r[] = $payment->transaction_detail;
            $r[] = $payment->earning_type;
            $r[] = $payment->expense;
            $r[] = $payment->expense_detail;
            $r[] = '%'.$leaddata->revenue_share;
            $r[] = $currency_symbol.$earning;
            $data[] = $r;
        }
        $now = date('m_d_Y');
        $filename = './report/'.$now.'_bulk_payments_records.csv';
        $url = base_url('report/'.$now.'_bulk_payments_records.csv');
        $file = fopen($filename,"w");

        foreach ($data as $line) {
            fputcsv($file, $line);
        }

        fclose($file);
        redirect($url);


    }

    public function import_in_payments(){
        auth();
        role_permitted(false, 'rawpaymentupload','update_rawpaymentupload');
        $dbData = $this->security->xss_clean($this->input->post());
        $file_id = $dbData['file_id'];
        $file = $this->rawpayment->getFileById($file_id);
        unset($dbData['file_id']);
        $payment_bulk_upload_id = 0;
        $imported = 0;
        $notImported = 0;
        foreach ($dbData['payments'] as $i=>$row) {
            $unique_key = $row['wg_id'];


            $payment_bulk_upload_id = $row['payment_bulk_upload_id'];
            $earning_type = $row['earning_type'];
            $dataValidation = 0;
            $dataValidationMessages = [];
            $query = $this->db->query('SELECT id  FROM `earning_types` WHERE `earning_type` = "' . $earning_type . '"');
            if ($query->num_rows() > 0){
                $earning_id = $query->row()->id;
            }else{
                $dataValidation = 1;
                $dataValidationMessages[] = 'Wrong Earning Type.';
                $earning_id = 0;
            }

            $query = $this->db->query('SELECT *  FROM `video_leads` WHERE `unique_key` = "' . $unique_key . '"');
            if($query->num_rows() > 0){
                $leaddata = $query->row();

                $lead_id = $leaddata->id;

            }else{
                $dataValidation = 1;
                $dataValidationMessages[] = 'Wrong WG Id.';
                $leaddata = null;
                $lead_id = 0;
            }

            $videoId = 0;
            if($lead_id > 0){
                $query = $this->db->query('SELECT *  FROM `videos` WHERE `lead_id` = "' . $lead_id . '"');
                if($query->num_rows() > 0) {
                    $videodata = $query->row();

                    $videoId = $videodata->id;
                    $user = $this->user->getUserById($videodata->user_id);
                    if (!empty($user)) {
                        // if (!empty($user->currency_id)) {
                        //     $Data['currency_id'] = $user->currency_id;
                        // }
                    }else{
                        $dataValidation = 1;
                        $dataValidationMessages[] = 'User not found.';
                    }
                }else{
                    $videodata = null;
                    $user = null;
                    $dataValidation = 1;
                    $dataValidationMessages[] = 'Video not found.';
                    $Data['currency_id'] = 0;
                }
            }

            /* echo '<pre>';
             print_r($row['earning_date']);
             exit;*/
            $checkQuery = $this->db->query(" SELECT * FROM earnings WHERE video_id = $videoId AND partner_id = $row[partner_id] AND transaction_id = '$row[transaction_id]' AND deleted = 0");
            if($checkQuery->num_rows() > 0){
                $dataValidation = 1;
                $dataValidationMessages[] = 'This transaction already exist.';
            }
            if($dataValidation == 0){
                // if($Data['currency_id'] != $file->file_currency_id && $Data['currency_id'] == $file->conversion_currency_id){
                //     $expense = 0;
                // }else{
                //     $this->db->where('id',$user->id);
                //     $this->db->update('users',['currency_id'=>2]);
                // }
                $expense = (double) $row['expense'];
                $earning = ltrim($row['actual_amount'], '$');
                $earning = (double) $earning;
                $Data['earning_type_id'] = $earning_id;
                $Data['earning_amount'] = $row['earning'];
                $Data['earning_date '] = date('Y-m-d', strtotime($row['earning_date']));
                $Data['status'] = 0;
                $Data['paid'] = 0;
                $Data['social_source_id'] = 0;
                $Data['currency_id'] = $file->conversion_currency_id;
                $Data['partner_currency'] = $file->file_currency_id;
                $Data['conversion_rate'] = $file->conversion_rate;
                $Data['video_id'] = $videodata->id;
                $Data['partner_id'] = $row['partner_id'];
                $Data['expense'] = $row['expense'];
                $Data['expense_detail'] = $row['expense_detail'];
                $Data['transaction_id'] = $row['transaction_id'];
                $Data['transaction_detail'] = $row['transaction_detail'];
                $Data['created_at'] = date('Y-m-d H:i:s');
                $Data['updated_at'] = date('Y-m-d H:i:s');
                $Data['created_by'] = $this->sess->userdata('adminId');
                $Data['updated_by'] = $this->sess->userdata('adminId');
                //$Data['revenue_share_amount'] = $row['revenue_share'];
                //$Data['actual_amount'] = $row['actual_amout'];
                $Data['actual_amount'] =  $earning;
                //$earning = filter_var($row['earning'],FILTER_SANITIZE_NUMBER_INT);
                //$expense = filter_var($row['expense'],FILTER_SANITIZE_NUMBER_INT);
                if (!empty($earning) && $earning > 0) {
                    if ((!empty($expense) && $expense > 0)) {
                        $expense_amount = $this->percentage($earning, $expense);
                        $after_expense = ($earning - $expense_amount);
                        $wooglobe_amount = $this->percentage($after_expense, $leaddata->revenue_share);
                        $Data['expense_amount'] = $expense_amount;
                        $Data['wooglobe_net_earning'] = ($after_expense - $wooglobe_amount);
                        $Data['wooglobe_total_share'] = ($after_expense - $wooglobe_amount) + ($expense_amount);
                        $Data['revenue_share_amount'] = $wooglobe_amount;
                        $Data['client_net_earning'] = $wooglobe_amount;
                    } else {
                        $revenue_amount = $this->percentage($earning, $leaddata->revenue_share);
                        $Data['expense_amount'] = 0;
                        $Data['wooglobe_net_earning'] = ($earning - $revenue_amount);
                        $Data['wooglobe_total_share'] = ($earning - $revenue_amount);
                        $Data['revenue_share_amount'] = $revenue_amount;
                        $Data['client_net_earning'] = $revenue_amount;
                    }
                } else {
                    $Data['expense_amount'] = 0;
                    $Data['wooglobe_net_earning'] = 0;
                    $Data['wooglobe_total_share'] = 0;
                    $Data['revenue_share_amount'] = 0;
                    $Data['client_net_earning'] = 0;
                }


                $this->db->insert('earnings', $Data);
                $this->db->where('id',$i);
                $this->db->update('raw_payments_uploads',['import'=>1]);
                $imported = ($imported + 1);
            }else{
                $this->db->where('id',$i);
                $this->db->update('raw_payments_uploads',['validation'=>1,'errors'=>json_encode($dataValidationMessages),'import'=>0]);
                $notImported = ($notImported + 1);
            }

        }
        //$this->db->where('id',$payment_bulk_upload_id);
        //$this->db->delete('payment_bulk_upload');

        $this->sess->set_flashdata('msg',"$imported record import successfully and $notImported record failed to import");

        redirect('rawpaymentupload');

    }


    public function get_rawpayments(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false,'categories_mrss');
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
        $result = $this->rawpayment->getCategoryById($id,'id,title,status,parent_id,partner_id as partner,url,type,pub_date');
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



    public function delete_payment(){

        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'categories_mrss','delete_category');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'File Deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->rawpayment->getFileById($id);

        if(!$result){

            $response['code'] = 201;
            $response['message'] = 'No file found!';
            $response['error'] = 'No file found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }



        $this->db->where('id',$id);
        $this->db->delete('payment_bulk_upload');
        $this->db->where('payment_bulk_upload_id',$id);
        $this->db->delete('raw_payments_uploads');
        $this->sess->set_flashdata('msg','File Deleted Successfully.');
        echo json_encode($response);
        exit;

    }

    public function get_feed_data(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'categories_mrss');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->post());
    }

    public function show_preview($feed_url){
        // get feed id by url
        $feed_id = $this->rawpayment->getFeedDataByUrl($feed_url);
        $result = $this->rawpayment->getFeedVideos($feed_id->id);//print_r($result);
        $exclusive = $this->rawpayment->getExclusiveVideos($feed_id->id);//print_r($result);
        $videos = $this->rawpayment->getVideosForFeed($result, $exclusive);

        $feed_data=$result->result();
        $this->data['feed']=$feed_data;
        $this->data['title']=$feed_id->title;
        $this->data['feed_id']=$feed_id->id;
        $this->data['feed_status'] = $feed_id->status;
        $this->data['partner_name'] = '';
        $this->data['videos']=$videos;
        $this->data['content'] = $this->load->view('categories_mrss/preview',$this->data,true);
        $this->load->view('common_files/template',$this->data);

    }

    public function show_partner_preview($partner, $feed_url){
        // get feed id by url
        $feed_id = $this->rawpayment->getFeedDataByUrl($partner.'/'.$feed_url);
        $result = $this->rawpayment->getFeedVideos($feed_id->id, $feed_id->partner_id);//print_r($result);
        $exclusive = $this->rawpayment->getExclusiveVideos($feed_id->partner_id);//print_r($result);
        $videos = $this->category->getVideosForFeed($result, $exclusive);

        $feed_data=$result->result();
        $this->data['feed']=$feed_data;
        $this->data['title']=$feed_id->title;
        $this->data['feed_id']=$feed_id->id;
        $this->data['feed_status'] = $feed_id->status;
        $this->data['partner_name'] = $this->category->getPartnerName($feed_id->partner_id)->full_name;
        $this->data['videos']=$videos;
        $this->data['content'] = $this->load->view('categories_mrss/preview',$this->data,true);
        $this->load->view('common_files/template',$this->data);

    }

    public function remove_video_from_feed(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'categories_mrss');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video removed from feed successfully!';
        $response['error'] = '';
        $param = $this->security->xss_clean($this->input->post());
        $vid = $param['vid'];
        $fid = $param['fid'];
        $res = $this->category->deleteFeedVideo($vid, $fid);
        echo json_encode($response);
        exit;


    }

    public function add_video_to_feed(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'categories_mrss');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Video added to feed successfully!';
        $response['error'] = '';
        $param = $this->security->xss_clean($this->input->post());
        $vid = $param['vid'];
        $fid = $param['fid'];
        $res = $this->category->addFeedVideo($vid, $fid);
        echo json_encode($response);
        exit;
    }

    public function publish_feed(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false,'categories_mrss');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Feed published successfully!';
        $response['error'] = '';
        $param = $this->security->xss_clean($this->input->post());
        $status = $param['status'];
        $fid = $param['fid'];
        $res = $this->category->updateFeedStatus($fid, $status);
        echo json_encode($response);
        exit;
    }
    public function secure_feed_data(){
        $mrss_data = $this->security->xss_clean($this->input->post());
        $response = array();

        $response['code'] = 200;
        $response['error'] = '';
        $partner_id =$mrss_data['id'];
        $feed_url =$mrss_data['feed_url'];
        $feed_id =$mrss_data['feed_id'];
        $secure_result = $this->category->getSecureValueByPartnerid($partner_id,$feed_id);
        $secure_result =$secure_result->result();
        $secure = $secure_result[0]->secure;
        $url = $secure_result[0]->url;
        if($secure == 0){
            $result = $this->category->getUserDataByPartnerid($partner_id);
            $result =$result->result();
            $password = $result[0]->password;
            $url = $url.'/'.$password;
            $secure=1;
            $updatefeed=$this->category->updatePartnerFeed($partner_id,$secure,$url,$feed_id);
            $response['message'] = 'Feed Secure Successfully!';
        }else{
            $url = explode('/',$url);
            $join_url = $url[0].'/'.$url[1];
            $secure=0;
            $updatefeed=$this->category->updatePartnerFeed($partner_id,$secure,$join_url,$feed_id);
            $response['message'] = 'Feed Not Secure!';
        }
        echo json_encode($response);
        exit;
    }

    public function percentage($num, $per)
    {
        return ($num/100)*$per;
    }

}
