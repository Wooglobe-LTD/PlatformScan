<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ConfigurationsController extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'configurations';
        $css = array(
            'bower_components/uikit/css/uikit.almost-flat.min.css',
        );
        $js = array(
            'assets/js/configurations.js',
        );
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);


        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = $this->data['commonJs'];

        //$this->load->model('Configuration','configuration');

    }

    public function index()
    {
        auth();
        //role_permitted(false, 'partners');

        $default_currency = $this->db->query("SELECT * FROM currency WHERE default_currency = 1 LIMIT 1")->row();
        $currencies = $this->db->query("SELECT code, id FROM currency WHERE status = 1")->result_array();

        $currencies_map = db_result_to_array_map($currencies);

        $this->data["currencies"] = $currencies;
        $this->data["default_currency"] = $default_currency;
        $this->data["usdtogbp"] = getConversionRate($currencies_map["USD"], $currencies_map["GBP"]);
        $this->data["gbptousd"] = getConversionRate($currencies_map["GBP"], $currencies_map["USD"]);;

        $this->data['title'] = 'Configurations';
        $query = $this->db->get('configurations');
        $data = $query->row();
        $this->data['data'] = $data;



        $this->data['content'] = $this->load->view('configuration/index', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }

    public function store()
    {
        $formData = $this->input->post();
        $status = $this->db->insert('configurations', $formData);
        $insert_id = $this->db->insert_id();
        if ($status) {
            $this->db->where("id!=", $insert_id);
            $this->db->delete('configurations');
        }
        $this->data['message'] = "Successfully change data";
        $this->data['content'] = $this->load->view('configuration/index', $this->data, true);
        redirect('configurations');
        // $this->load->view('common_files/template', $this->data);


    }
    public function get_configurations(){
     $query = $this->db->get('configurations');
     $data = $query->row();
     echo json_encode($data);exit;
     
    }
    public function update_default_currency(){
        $formData = $this->input->post();
        $dbData["default_currency"] = 0;
        $this->db->update("currency", $dbData);

        $this->db->where("id =", $formData["default_currency_id"]);
        $dbData["default_currency"] = 1;
        $this->db->update("currency", $dbData);

        $response['code'] = 200;
        $response['message'] = 'Updated default currency';
        echo json_encode($response);

    }

    public function update_conversion_rates(){
        $formData = $this->input->post();

        $currencies = $this->db->query("SELECT code, id FROM currency WHERE status = 1")->result_array();

        $currencies_map = db_result_to_array_map($currencies);

        $this->db->where(array("from_currency" => $currencies_map["USD"], "to_currency" => $currencies_map["GBP"]));
        $this->db->update("conversion_rates", array("conversion_rate" => $formData["usdtogbp"]));

        $this->db->where(array("from_currency" => $currencies_map["GBP"], "to_currency" => $currencies_map["USD"]));
        $this->db->update("conversion_rates", array("conversion_rate" => $formData["gbptousd"]));


        $response['code'] = 200;
        $response['message'] = 'Updated conversion rates';
        echo json_encode($response);
    }

}
