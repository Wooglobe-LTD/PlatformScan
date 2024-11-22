<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_error extends APP_Controller {

	public function __construct() {
        parent::__construct();
        
    }
	public function index()
	{
		
		$this->data['title'] = '404';
		$this->load->view('404',$this->data);
	}
	
	
	
	
}
