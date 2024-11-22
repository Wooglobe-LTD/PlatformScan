<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Block_Users extends APP_Controller {

    public function __construct() {
        parent::__construct();
        $this->data['active'] = 'Block_Users';
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
            'assets/js/blockusers.js'
        );
        $this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->data['assess'] =  array(
            'list'=>role_permitted_html(false, 'users'),
            'can_add'=>role_permitted_html(false, 'users','add_user'),
            'can_edit'=>role_permitted_html(false, 'users','update_user'),
        );
        $this->load->model('User_Model','user');

    }
    public function index()
    {
        auth();
        role_permitted(false, 'users');
        $this->data['title'] = 'Block Users Management';
        $this->data['content'] = $this->load->view('block_users/listing',$this->data,true);
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


        $result = $this->user->getBlockUsers();

        $resultCount = $this->user->getBlockUsers();
        $response = array();
        $data = array();

        foreach($result->result() as $row){
            $r = array();
            $links = '';
            $links .= '<a title="Un-Block User" href="javascript:void(0);" class="unblock-user" data-id="' . $row->id . '"><i class="material-icons">&#xE873;</i></a> ';
            $r[] = $links;
            $r[] = $row->full_name;
            $r[] = $row->gender;
            $r[] = $row->email;
            $r[] = $row->mobile;



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
    public function unblock_user(){
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
        $id=$this->input->post('id');
        $result = $this->user->updateunBlockUsers($id);

        if($result){
            $result_user = $this->user->getUserById($id,'u.id,u.full_name,u.email');

            if(!$result_user){

                $response['code'] = 201;
                $response['message'] = 'No user found!';
                $response['error'] = 'No user found!';
                $response['url'] = '';

            }else{

                $this->load->helper('string');
                $password = random_string('alnum', 10);
                $dbData['password']   = sha1($password);
                $dbData['updated_at'] = date('Y-m-d H:i:s');
                $dbData['updated_by'] = $this->sess->userdata('adminId');
                $this->db->where('id',$id);
                $this->db->update('users',$dbData);
                $message = 'Dear '.$result_user->full_name.'<br> You are password change successfully.<br> <b>New Password : </b>'.$password;
                $this->email($result_user->email,$result_user->full_name,'noreply@wooglobe.com','WooGlobe','User is Unblock and Paasword Reset',$message);


                $response['data'] = $result_user;

            }

            $response['code'] = 200;
            $response['message'] = 'User is unblock';
            $response['error'] = '';
            echo json_encode($response);
            exit;
        }
    }
}
