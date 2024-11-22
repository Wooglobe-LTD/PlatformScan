<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Closewon extends APP_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['active'] = 'Closewon';
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
            'assets/js/closewon.js'
        );
        $this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false, 'users'),
            'can_add'=>role_permitted_html(false, 'users','add_user'),
            'can_edit'=>role_permitted_html(false, 'users','update_user'),
        );
        $this->load->model('User_Model','user');
        $this->load->model('Video_Deal_Model','deal');

    }
    public function index()
    {
        auth();
        role_permitted(false, 'users');
        $this->data['title'] = 'Close Won Management';
        $this->data['content'] = $this->load->view('closewon/listing',$this->data,true);
        $this->load->view('common_files/template',$this->data);
    }


    public function videos_listing(){
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

        $result = $this->deal->getDealWon($search,$start,$limit,$orderby,$params['columns']);

        $resultCount = $this->deal->getDealWon();
        $response = array();
        $data = array();

        foreach($result->result() as $row){
            $publish_yt=$row->published_yt;
            if($publish_yt == 1){
                $pub_yt = 'published';
            }else{
                $pub_yt = 'unpublished';
            }

            $publish_fb=$row->published_fb;
            if($publish_fb == 1){
                $pub_fb = 'published';
            }else{
                $pub_fb = 'unpublished';
            }
            $link = '<a href='.base_url().'deal-detail/'.$row->id.'>'.$row->unique_key.'</a>';
            $video_title =$row->video_title;
            $video_title=wordwrap($video_title, 40, "<wbr>", true);
            $status =$row->status;


            if($status == 3){
                $status = 'Information Received';
            }else if($status == 6){
                $status = 'Video Verified';
            }else if($status == 7){
                $status = 'Distribution';
            }else if($status == 8){
                $status = 'All Done';
            }

            $r = array();
            $r[] = $link;
            $r[] = $row->created_at;
            $r[] = $row->video_url;
            $r[] = $status;
            $r[] = $video_title;
            $r[] = $row->email;
            $r[] = $pub_yt;
            $r[] = $row->youtube_id;
            $r[] = $pub_fb;
            $r[] = $row->facebook_id;


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
