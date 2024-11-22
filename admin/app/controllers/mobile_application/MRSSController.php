<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MRSSController extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'MRSS Feeds Management';
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
            'assets/js/ma_mrss.js'
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);
        $this->data['assess'] = array(
            'list' => role_permitted_html(false, 'mrss'),
            'can_add' => role_permitted_html(false, 'mrss', 'add_mrss'),
            'can_edit' => role_permitted_html(false, 'mrss', 'update_mrss'),
            'can_delete' => role_permitted_html(false, 'mrss', 'delete_mrss')
        );
        $this->load->model('MobileAPPMRSS', 'ma_mrss');

    }

    public function index()
    {

        auth();
        role_permitted(false, 'mrss');

        $this->data['title'] = 'MRSS Feeds Management';



        $this->data['content'] = $this->load->view('mobile_app/mrss/listing', $this->data, true);


        $this->load->view('common_files/template', $this->data);
    }


    public function mrss_listing()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'mrss');
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

        $result = $this->ma_mrss->getAllMRSS('mr.id,mr.mrss_title,mr.mrss_link,case when (mr.status = 1) THEN "Active" ELSE "Inactive" END as status', $search, $start, $limit, $orderby, $params['columns']);
        $resultCount = $this->ma_mrss->getAllMRSS();
        $response = array();
        $data = array();

        foreach ($result->result() as $row) {
            $r = array();
            $links = '';
            if ($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit MRSS" href="javascript:void(0);" class="ma-edit-mrss" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';

            }
            if ($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete MRSS" href="javascript:void(0);" class="ma-delete-mrss" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';

            }
              $links .= '| <a title="Get Videos" href="'.base_url().'get-mrss-videos?mrss_link='.$row->mrss_link.'" target="_blank" class="ma-get-mrss-videos" data-id="' . $row->id . '"><i class="material-icons">play_arrow</i></a>';
            if ($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }
            $r[] = $row->mrss_title;
            $r[] = $row->mrss_link;

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

    public function add_mrss()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'mrss', 'add_mrss');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Mrss Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('mrss_title', 'MRSS Title', 'required');
        $this->validation->set_rules('mrss_link', 'MRSS Link', 'required|is_unique[ma_mrss_feeds.mrss_link]');
        $this->validation->set_rules('status', 'Status', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('mrss_title', 'mrss_link', 'status');
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
            $this->db->insert('ma_mrss_feeds', $dbData);
        }

        echo json_encode($response);
        exit;

    }

/*    public function validate_title($title)
    {

        $title = $this->security->xss_clean($title);

        $parent_id = $this->security->xss_clean($this->input->post('parent_id'));

        if (!empty($title)) {
            if (preg_match('/^[a-zA-Z0-9 ]+$/', $title)) {
                $result = $this->mp_category->getCategoryByTitle($title, $parent_id);
                if ($result->num_rows() > 0) {
                    $this->validation->set_message('validate_title', 'This category already exist in this category!');
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

    }*/

    public function get_mrss()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'mrss');
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
        $result = $this->ma_mrss->getMRSSById($id, 'id,mrss_title,mrss_link,status');
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No MRSS found!';
            $response['error'] = 'No MRSS found!';
            $response['url'] = '';

        } else {

            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }

    public function update_mrss()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'MRSS', 'update_mrss');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'MRSS Updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->ma_mrss->getMRSSById($id);
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No MRSS found!';
            $response['error'] = 'No MRSS found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $this->validation->set_rules('mrss_title', 'MRSS Title', 'required');
        $this->validation->set_rules('mrss_link', 'MRSS Link', 'required|is_unique[ma_mrss_feeds.mrss_link]');
        $this->validation->set_rules('status', 'Status', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('mrss_title', 'mrss_link', 'status');
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
            //$dbData['slug'] = slug($dbData['title'],'categories','slug');
            $dbData['updated_at'] = date('Y-m-d H:i:s');
            $dbData['updated_by'] = $this->sess->userdata('adminId');
            $this->db->where('id', $id);
            $this->db->update('ma_mrss_feeds', $dbData);
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
                $result = $this->ma_mrss->getCategoryByTitle($title, $parent_id);
                if ($result->num_rows() > 0) {
                    $result = $result->row();
                    if ($result->id == $id) {

                        return true;

                    } else {

                        $this->validation->set_message('validate_title_edit', 'This category already exist in this category!');
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

    public function delete_mrss()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'mrss', 'delete_mrss');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'MRSS Deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->ma_mrss->getMRSSById($id);

        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No MRSS found!';
            $response['error'] = 'No MRSS found!';
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
        $this->db->update('ma_mrss_feeds', $dbData);

        echo json_encode($response);
        exit;

    }

}
