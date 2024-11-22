<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AbusiveCommentsController extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'Abusive Comment Words';
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
            'assets/js/ma_mobile_abusive_comment_word.js'
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);
        $this->data['assess'] = array(
            'list' => role_permitted_html(false, 'ma_mobile_abusive_comment_word'),
            'can_add' => role_permitted_html(false, 'ma_mobile_abusive_comment_word', 'ma_mobile_abusive_comment_word'),
            'can_edit' => role_permitted_html(false, 'ma_mobile_abusive_comment_word', 'ma_mobile_abusive_comment_word'),
            'can_delete' => role_permitted_html(false, 'ma_mobile_abusive_comment_word', 'ma_mobile_abusive_comment_word')
        );
        $this->load->model('MobileAppAbusiveCommentWord', 'mp_acw');

    }

    public function index()
    {

        auth();
        role_permitted(false, 'ma_mobile_abusive_comment_word');
        $this->data['title'] = 'Mobile Abusive Comment Words Management';



       $this->data['content'] = $this->load->view('mobile_app/abusive_comment_word/listing', $this->data, true);



        $this->load->view('common_files/template', $this->data);
    }


    public function abusive_comment_words_listing()
    {
       

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'ma_mobile_abusive_comment_word');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->get());
        $search = '';
        $orderby = '';
        $start = 0;
        $limit = 0;
        if (isset($params['search'])) {
            $search = $params['search']['value'];
        }
        if (isset($params['start'])) {
            $start = $params['start'];
        }
        if (isset($params['length'])) {
            $limit = $params['length'];
        }
        if (isset($params['order'])) {
            $orderby = $params['columns'][$params['order'][0]['column']]['name'] . ' ' . $params['order'][0]['dir'];
        }

        $result = $this->mp_acw->getAllAbusiveCommentWords('acw.id,acw.word,case when (acw.status = 1) THEN "Active" ELSE "Inactive" END as status', $search, $start, $limit, $orderby, $params['columns']);
        $resultCount = $this->mp_acw->getAllAbusiveCommentWords();
        $response = array();
        $data = array();

        foreach ($result->result() as $row) {
            $r = array();
            $links = '';
            if ($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Word" href="javascript:void(0);" class="ma-edit-acw" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';

            }
            if ($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete Word" href="javascript:void(0);" class="ma-delete-acw" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';

            }
            if ($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }

            $r[] = $row->word;
          

            $r[] = $row->status;

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

    public function add_abusive_comment_word()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'ma_mobile_abusive_comment_word', 'add_comment_abusive_word');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Word Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('word', 'Word', 'required');
        $this->validation->set_rules('status', 'Status', 'required');
      
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('word', 'status');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {

            $dbData = $this->security->xss_clean($this->input->post());

            $dbData['created_at'] = date('Y-m-d H:i:s');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['created_by'] = $this->sess->userdata('adminId');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->insert('ma_abusive_comment_words', $dbData);
        }

        echo json_encode($response);
        exit;

    }

  

    public function get_abusive_comment_word()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'get_comment_abusive_word');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Record found!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->mp_acw->getWordById($id, 'id,word');
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Keyword found!';
            $response['error'] = 'No Keyword found!';
            $response['url'] = '';

        } else {

            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }

    public function update_abusive_comment_words()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'comment_abusive_word', 'update_comment_abusive_word');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Word Updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->mp_acw->getWordById($id);
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Word found!';
            $response['error'] = 'No Word found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
      $this->validation->set_rules('word', 'Word', 'required');
        $this->validation->set_rules('status', 'Status', 'required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('word', 'status');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['url'] = '';

        } else {


            $dbData = $this->security->xss_clean($this->input->post());
            unset($dbData['id']);
           
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->where('id', $id);
            $this->db->update('ma_abusive_comment_words', $dbData);
        }

        echo json_encode($response);
        exit;

    }

   

    public function delete_abusive_comment_word()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'abusive_comment_word', 'delete_abusive_comment_word');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Word Deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->mp_acw->getWordById($id);

        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Word found!';
            $response['error'] = 'No Word found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $dbData['updated_at'] = date('Y-m-d H:i:s');
        $dbData['updated_by'] = $this->sess->userdata('adminId');
        $dbData['deleted_at'] = date('Y-m-d H:i:s');
        $dbData['deleted_by'] = $this->sess->userdata('adminId');
        $dbData['deleted'] = 1;
        $this->db->where('id', $id);
        $this->db->update('ma_abusive_comment_words', $dbData);

        echo json_encode($response);
        exit;

    }

}
