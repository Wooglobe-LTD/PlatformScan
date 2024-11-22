<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KeywordsController extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'Mobile keywords Management';
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
            'assets/js/ma_keywords.js'
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);
        $this->data['assess'] = array(
            'list' => role_permitted_html(false, 'keywords'),
            'can_add' => role_permitted_html(false, 'keywords', 'add_Keyword'),
            'can_edit' => role_permitted_html(false, 'keywords', 'update_Keyword'),
            'can_delete' => role_permitted_html(false, 'keywords', 'delete_Keyword')
        );
        $this->load->model('MobileAPPKeyword', 'mp_keyword');

    }

    public function index()
    {

        auth();
        role_permitted(false, 'keywords');
        $this->data['title'] = 'Mobile keywords Management';



       $this->data['content'] = $this->load->view('mobile_app/keywords/listing', $this->data, true);



        $this->load->view('common_files/template', $this->data);
    }


    public function keywords_listing()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'keywords');
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

        $result = $this->mp_keyword->getAllkeywords('k.id,k.keyword,k.priority,k.start,k.end,case when (k.status = 1) THEN "Active" ELSE "Inactive" END as status', $search, $start, $limit, $orderby, $params['columns']);
        $resultCount = $this->mp_keyword->getAllkeywords();
        $response = array();
        $data = array();

        foreach ($result->result() as $row) {
            $r = array();
            $links = '';
            if ($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit Keyword" href="javascript:void(0);" class="ma-edit-keyword" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';

            }
            if ($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete Keyword" href="javascript:void(0);" class="ma-delete-keyword" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';

            }
            if ($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }

            $r[] = $row->keyword;
            $r[] = $row->priority;
            $r[] = $row->start;
            $r[] = $row->end;

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

    public function add_Keyword()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'keywords', 'add_Keyword');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Keyword Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';

        $this->validation->set_rules('keyword', 'Parent Keyword', 'trim|required');
        $this->validation->set_rules('priority', 'Keyword priority', 'required');
        $this->validation->set_rules('status', 'Status', 'required');
        $this->validation->set_rules('start', 'start', 'required');
        $this->validation->set_rules('end', 'end', 'required');
        $this->validation->set_message('required', 'This field is required.');
        $this->validation->set_message('alpha_numeric_spaces', 'Only alphabet and number are allowed.');

        if ($this->validation->run() === false) {

            $fields = array('keyword', 'priority', 'status','start','end');
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
            $this->db->insert('mobile_application_keywords', $dbData);
        }

        echo json_encode($response);
        exit;

    }

    public function validate_title($title)
    {

        $title = $this->security->xss_clean($title);

        $parent_id = $this->security->xss_clean($this->input->post('parent_id'));

        if (!empty($title)) {
            if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
                $result = $this->mp_keyword->getKeywordByTitle($title, $parent_id);
                if ($result->num_rows() > 0) {
                    $this->validation->set_message('validate_title', 'This Keyword already exist in this Keyword!');
                    return false;
                } else {
                    return true;
                }
            } else {
                $this->validation->set_message('validate_title', 'Only alphabet and number are allowed.');
                return false;
            }
        } else {
            $this->validation->set_message('validate_title', 'This field is required.');
            return false;
        }

    }

    public function get_Keyword()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'keywords');
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
        $result = $this->mp_keyword->getKeywordById($id, 'id,keyword,priority,start,end,status');
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

    public function update_Keyword()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'keywords', 'update_Keyword');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Keyword Updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->mp_keyword->getKeywordById($id);
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Keyword found!';
            $response['error'] = 'No Keyword found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $this->validation->set_rules('keyword', 'Parent Keyword', 'trim|required');
        $this->validation->set_rules('priority', 'Keyword priority', 'required');
        $this->validation->set_rules('status', 'Status', 'required');
        $this->validation->set_rules('start', 'start', 'required');
        $this->validation->set_rules('end', 'end', 'required');
        $this->validation->set_message('required', 'This field is required.');
        $this->validation->set_message('alpha_numeric_spaces', 'Only alphabet and number are allowed.');

        if ($this->validation->run() === false) {

            $fields = array('keyword', 'priority', 'status','start','end');
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
            //$dbData['slug'] = slug($dbData['title'],'keywords','slug');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->where('id', $id);
            $this->db->update('mobile_application_keywords', $dbData);
        }

        echo json_encode($response);
        exit;

    }

    public function validate_title_edit($title, $id)
    {

        $title = $this->security->xss_clean($title);

        $parent_id = $this->security->xss_clean($this->input->post('parent_id'));

        if (!empty($title)) {
            if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
                $result = $this->mp_keyword->getKeywordByTitle($title, $parent_id);
                if ($result->num_rows() > 0) {
                    $result = $result->row();
                    if ($result->id == $id) {

                        return true;

                    } else {

                        $this->validation->set_message('validate_title_edit', 'This Keyword already exist in this Keyword!');
                        return false;

                    }

                } else {
                    return true;
                }
            } else {
                $this->validation->set_message('validate_title_edit', 'Only alphabet and number are allowed.');
                return false;
            }
        } else {
            $this->validation->set_message('validate_title_edit', 'This field is required.');
            return false;
        }

    }

    public function delete_Keyword()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'keywords', 'delete_Keyword');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Keyword Deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->mp_keyword->getKeywordById($id);

        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Keyword found!';
            $response['error'] = 'No Keyword found!';
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
        $this->db->update('mobile_application_keywords', $dbData);

        echo json_encode($response);
        exit;

    }

}
