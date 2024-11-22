<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NotInterested extends APP_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['active'] = 'NotInterested';
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
            'assets/js/notinterested.js'
        );
        $this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false, 'users'),
            'can_add'=>role_permitted_html(false, 'users','add_user'),
            'can_edit'=>role_permitted_html(false, 'users','update_user'),
        );
        $this->load->model('User_Model','user');
        $this->load->model('Video_Model','video');

    }
    public function index()
    {
        auth();
        role_permitted(false, 'users');
        $this->data['title'] = 'Not Interested Management';
        $this->data['content'] = $this->load->view('notinterested/listing',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }


    public function users_listing(){
        $auth_ajax = auth_ajax();
        if($auth_ajax){
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'users');
        if($role_permitted_ajax){
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->get());


        $result = $this->user->getNotInterested();

        $resultCount = $this->user->getNotInterested();
        $response = array();
        $data = array();
       /* print_r($result->result());
        exit();*/

        foreach($result->result() as $row){
            $r = array();
            $links = '';


            $links .= '<a href="javascript:void(0);" data-title="Deals" class="undo-not-interested" data-id="' . $row->id . '" title="Remove From Not Interested"><i class="material-icons">not_interested</i></a> ';
            $r[] = $links;
            $r[] = $row->first_name;
            $r[] = $row->unique_key;
            $r[] = $row->video_url;
            $r[] = $row->video_title;



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
}
