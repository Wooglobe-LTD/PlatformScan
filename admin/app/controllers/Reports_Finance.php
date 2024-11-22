<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_Finance extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'finance_report';
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
			'assets/js/finance.js',
            'assets/js/canvasjs.min.js'
		);
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
		$this->data['assess'] =  array(
            'list'=>role_permitted_html(false,'reports'),
        );
		$this->load->model('Report_Finance_Model','report');

    }
	public function index()
	{
		auth();
        role_permitted(false,'reports_report');
        $filters = $this->input->post();
        $date = 1;
        $dateFrom = date("Y-m-d", strtotime("first day of previous month"));
        $dateTo = date("Y-m-d", strtotime("last day of previous month"));
        if(isset($filters['date']) && !empty($filters['date'])){
            $date = $filters['date'];
        }
        if(isset($filters['date_from']) && !empty($filters['date_from'])){
            $dateFrom = $filters['date_from'];
        }
        if(isset($filters['date_to']) && !empty($filters['date_to'])){
            $dateTo = $filters['date_to'];
        }
        if($date == 2){
            $dateFrom = date("Y-m-d", strtotime("-3 month"));
            $dateTo = date("Y-m-d");
        }else if($date == 3){
            $dateFrom = date("Y-m-d", strtotime("-1 year"));
            $dateTo = date("Y-m-d");
        }
        //echo "$date >>> $dateFrom >>> $dateTo";exit;
        $result = $this->report->getAllSums();
        $result2 = $this->report->getAllSumsByDates($dateFrom,$dateTo);
        $report = array();
        $settings = settings();
        $time = date('Y-m-d H:i:s',strtotime("- $settings->payment_yearly_duration year "));
        $payable = 0;
        $payableP = 0;
        $imgPayable = 0;
        $imgPayableP = 0;
        $reverse = 0;
        $reverseP = 0;
        $sales = 0;
        $salesP = 0;
        $clientEarnings = 0;
        $clientEarningsP = 0;
        $wooglobeEarnings = 0;
        $wooglobeEarningsP = 0;
        $paid = 0;
        $paidP = 0;
        $unpaid = 0;
        $unpaidP = 0;
        $expense = 0;
        $expenseP = 0;
        $this->data['title'] = 'finance_report';
        foreach ($result->result() as $row){
            if($row->payment < $settings->minimum_payment_yearly_threshhold && $row->created_at < $time){

                if($row->currency_id == 19){
                    $reverseP = ($reverseP+$row->payment);
                }else{
                    $reverse = ($reverse+$row->payment);
                }
            }else{
                if($row->currency_id == 19) {
                    $payableP = ($payableP+$row->payment);
                }else{
                    $payable = ($payable+$row->payment);
                }


                if($row->payment >= $settings->payment_threshold){
                    if($row->currency_id == 19){
                        $imgPayableP = ($imgPayableP+$row->payment);
                    }else{
                        $imgPayable = ($imgPayable+$row->payment);
                    }

                }

            }

        }
        $this->data['payable'] = $payable;
        $this->data['payableP'] = $payableP;
        $this->data['imgPayable'] = $imgPayable;
        $this->data['imgPayableP'] = $imgPayableP;
        $this->data['reverse'] = $reverse;
        $this->data['reverseP'] = $reverseP;
        $this->data['leads'] = $result;
        $this->data['time'] = $time;
        $this->data['date'] = $date;
        $this->data['dateFrom'] = $dateFrom;
        $this->data['dateTo'] = $dateTo;
        foreach ($result2->result() as $lead){
            $data = array();
            $data['data'] = $lead;
            if(isset($report[$lead->wid])){
                if($lead->currency_id == 19){
                    $data['earning_amount'] = ($report[$lead->wid]['earning_amount']);
                    $data['earning_amountP'] = ($report[$lead->wid]['earning_amountP'] + $lead->earning_amount);
                    $data['wooglobe_net_earning'] = ($report[$lead->wid]['wooglobe_net_earning']);
                    $data['wooglobe_net_earningP'] = ($report[$lead->wid]['wooglobe_net_earningP'] + $lead->wooglobe_net_earning);
                    $data['client_net_earning'] = ($report[$lead->wid]['client_net_earning']);
                    $data['client_net_earningP'] = ($report[$lead->wid]['client_net_earningP'] + $lead->client_net_earning);
                    $data['expense_amount'] = ($report[$lead->wid]['expense_amount'] + $lead->expense_amount);
                    $data['expense_amountP'] = ($report[$lead->wid]['expense_amountP'] + $lead->expense_amount);
                    if($lead->paid == 1){
                        $data['paid'] = ($report[$lead->wid]['paid']);
                        $data['paidP'] = ($report[$lead->wid]['paidP'] + $lead->client_net_earning);
                        $data['unpaid'] = $report[$lead->wid]['unpaid'];
                        $data['unpaidP'] = $report[$lead->wid]['unpaidP'];
                    }else{
                        $data['unpaid'] = ($report[$lead->wid]['unpaid']);
                        $data['unpaidP'] = ($report[$lead->wid]['unpaidP'] + $lead->client_net_earning);
                        $data['paid'] = $report[$lead->wid]['paid'];
                        $data['paidP'] = $report[$lead->wid]['paidP'];
                    }
                }else{
                    $data['earning_amount'] = ($report[$lead->wid]['earning_amount'] + $lead->earning_amount);
                    $data['earning_amountP'] = ($report[$lead->wid]['earning_amountP']);
                    $data['wooglobe_net_earning'] = ($report[$lead->wid]['wooglobe_net_earning'] + $lead->wooglobe_net_earning);
                    $data['wooglobe_net_earningP'] = ($report[$lead->wid]['wooglobe_net_earningP']);
                    $data['client_net_earning'] = ($report[$lead->wid]['client_net_earning'] + $lead->client_net_earning);
                    $data['client_net_earningP'] = ($report[$lead->wid]['client_net_earningP']);
                    $data['expense_amount'] = ($report[$lead->wid]['expense_amount'] + $lead->expense_amount);
                    $data['expense_amountP'] = ($report[$lead->wid]['expense_amountP']);
                    if($lead->paid == 1){
                        $data['paid'] = ($report[$lead->wid]['paid'] + $lead->client_net_earning);
                        $data['paidP'] = ($report[$lead->wid]['paidP']);
                        $data['unpaid'] = $report[$lead->wid]['unpaid'];
                        $data['unpaidP'] = $report[$lead->wid]['unpaidP'];
                    }else{
                        $data['unpaid'] = ($report[$lead->wid]['unpaid'] + $lead->client_net_earning);
                        $data['unpaidP'] = ($report[$lead->wid]['unpaidP']);
                        $data['paid'] = $report[$lead->wid]['paid'];
                        $data['paidP'] = $report[$lead->wid]['paidP'];
                    }
                }

            }else{
                if($lead->currency_id == 19){
                    $data['earning_amount'] = 0;
                    $data['earning_amountP'] = $lead->earning_amount;
                    $data['wooglobe_net_earning'] = 0;
                    $data['wooglobe_net_earningP'] = $lead->wooglobe_net_earning;
                    $data['client_net_earning'] = 0;
                    $data['client_net_earningP'] = $lead->client_net_earning;
                    $data['expense_amount'] = 0;
                    $data['expense_amountP'] = $lead->expense_amount;
                    if($lead->paid == 1){
                        $data['paid'] = 0;
                        $data['paidP'] = $lead->client_net_earning;
                        $data['unpaid'] = 0;
                        $data['unpaidP'] = 0;
                    }else{
                        $data['paid'] = 0;
                        $data['paidP'] = 0;
                        $data['unpaid'] = 0;
                        $data['unpaidP'] = $lead->client_net_earning;
                    }
                }else{
                    $data['earning_amount'] = $lead->earning_amount;
                    $data['earning_amountP'] = 0;
                    $data['wooglobe_net_earning'] = $lead->wooglobe_net_earning;
                    $data['wooglobe_net_earningP'] = 0;
                    $data['client_net_earning'] = $lead->client_net_earning;
                    $data['client_net_earningP'] = 0;
                    $data['expense_amount'] = $lead->expense_amount;
                    $data['expense_amountP'] = 0;
                    if($lead->paid == 1){
                        $data['paid'] = $lead->client_net_earning;
                        $data['paidP'] = 0;
                        $data['unpaid'] = 0;
                        $data['unpaidP'] = 0;
                    }else{
                        $data['paid'] = 0;
                        $data['paidP'] = 0;
                        $data['unpaid'] = $lead->client_net_earning;
                        $data['unpaidP'] = 0;
                    }
                }

            }


            $report[$lead->wid] = $data;
        }
        foreach ($report as $sums){
            $sales = ($sales+$sums['earning_amount']);
            $salesP = ($salesP+$sums['earning_amountP']);
            $clientEarnings = ($clientEarnings+$sums['client_net_earning']);
            $clientEarningsP = ($clientEarningsP+$sums['client_net_earningP']);
            $wooglobeEarnings = ($wooglobeEarnings+$sums['wooglobe_net_earning']);
            $wooglobeEarningsP = ($wooglobeEarningsP+$sums['wooglobe_net_earningP']);
            $paid = ($paid+$sums['paid']);
            $paidP = ($paidP+$sums['paidP']);
            $unpaid = ($unpaid+$sums['unpaid']);
            $unpaidP = ($unpaidP+$sums['unpaidP']);
            $expense = ($expense+$sums['expense_amount']);
            $expenseP = ($expense+$sums['expense_amountP']);
        }

        $this->data['reports'] = $report;
        $this->data['sales'] = $sales;
        $this->data['salesP'] = $salesP;
        $this->data['clientEarningsP'] = $clientEarningsP;
        $this->data['clientEarnings'] = $clientEarnings;
        $this->data['wooglobeEarnings'] = $wooglobeEarnings;
        $this->data['wooglobeEarningsP'] = $wooglobeEarningsP;
        $this->data['paid'] = $paid;
        $this->data['paidP'] = $paidP;
        $this->data['unpaid'] = $unpaid;
        $this->data['unpaidP'] = $unpaidP;
        $this->data['expense'] = $expense;
        $this->data['expenseP'] = $expenseP;

        $this->data['content'] = $this->load->view('finance/index',$this->data,true);
        $this->load->view('common_files/template',$this->data);
	}


}
