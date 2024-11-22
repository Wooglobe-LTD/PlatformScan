<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MrssBrands extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'categories_mrss';
        $css = array(
            'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css'
        );
        $js = array(
            'assets/js/custom/datatables/buttons.uikit.js',
            'bower_components/jszip/dist/jszip.min.js',
            'bower_components/pdfmake/build/pdfmake.min.js',
            'bower_components/pdfmake/build/vfs_fonts.js',
            'bower_components/datatables-buttons/js/buttons.colVis.js',
            'bower_components/datatables-buttons/js/buttons.html5.js',
            'bower_components/datatables-buttons/js/buttons.print.js',
            'assets/js/pages/forms_file_input.min.js',
            'assets/js/categories_mrss3.js',
            'assets/js/vid_up/jquery.fileuploader.min.js'
        );
        $this->load->model('MrssBrands_Model', 'mrss');
        $this->load->model('Video_Lead_Model', 'lead');
        $this->load->model('Brands_Categories_Model', 'b_cats');
    }

    public function mrss_brands()
    {
        $response = array();
        // $mrss_partner_id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->mrss->getMrssBrands()->result_array();

        if ($result) {
            $response['code'] = 200;
            $response['data'] = $result;
        } else {
            $response['code'] = 201;
            $response['message'] = 'No partner found!';
        }
        echo json_encode($response);
        exit;
    }

    public function save_story_content()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'categories_mrss');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $param = $this->security->xss_clean($this->input->post());

        $mrss_exit = $this->mrss->existsPartnerBrandLead($param["story_partner_id"], $param["story_brand_id"], $param["lead_id"]);
        if ($mrss_exit && $param['mrss_edit'] != 1) {
            $response['code'] = 409;
            $response['message'] = 'Story already exists in this MRSS Feed!';
            $response['error'] = 'Story already exists in this MRSS Feed!';
            $response['url'] = '';
            echo json_encode($response);
            exit;
        }

        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Feed saved successfully!';
        $response['error'] = '';
        $response['id'] = null;

        $this->validation->set_rules('story_title', 'Title', 'trim|required');
        $this->validation->set_rules('story_description', 'Description', 'trim|required');
        $this->validation->set_rules('story_partner_id', 'Partner ID', 'is_array');
        $this->validation->set_rules('story_brand_id', 'Brand ID', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($valid === false) {

            $fields = array('story_title', 'story_description', 'story_partner_id', 'story_brand_id');
            $errors = array();
            foreach ($fields as $field) {
                $errors[$field] = form_error($field);
            }
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['data'] = '';
            echo json_encode($response);
            exit;
        }
        $dbData = array();
        $lead_id = $param['lead_id'];
        $wgid = $this->lead->getUniquekey($lead_id)->unique_key;
        $thumb = '';
        $video = '';
        if ($param['mrss_edit'] === '1') {
            $previous_data = $this->mrss->getMrssBrandFeedById($param['mrss_id']);
            $thumb = $previous_data->story_thumb_path;
            $video = $previous_data->story_video_path;
        }
        if (!is_dir("../uploads/" . $wgid . "/story_based_mrss/thumbnails")) {
            mkdir("../uploads/" . $wgid . "/story_based_mrss/thumbnails", 0777, true);
        }
        if (!is_dir("../uploads/" . $wgid . "/story_based_mrss/videos")) {
            mkdir("../uploads/" . $wgid . "/story_based_mrss/videos", 0777, true);
        }
        if (isset($_FILES['story_content_thumb']['name'][0]) && !empty($_FILES['story_content_thumb']['name'][0])) {
            if ($param['mrss_edit'] === '1' && file_exists('..' . $thumb)) {
                unlink('..' . $thumb);
            }
            if ($handle = opendir('../uploads/' . $wgid . '/story_based_mrss/thumbnails')) {
                while (($file = readdir($handle)) !== FALSE) {
                    $thumbs[] = basename($file);
                }
                closedir($handle);
                $thumbs = array_diff($thumbs, array('.', '..'));
            }
            $path = "../uploads/" . $wgid . "/story_based_mrss/thumbnails/";
            $file = $_FILES['story_content_thumb']['name'][0];
            $tmp = $_FILES['story_content_thumb']['tmp_name'][0];
            $thumb = $path . $file;
            if (in_array($file, $thumbs)) {
                $name = pathinfo($file, PATHINFO_FILENAME);
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $increment = '';
                while (file_exists($path . $name . $increment . '.' . $extension)) {
                    $increment++;
                    $thumb = $path . $name . $increment . '.' . $extension;
                }
            }
            move_uploaded_file($tmp, $thumb);
            $thumb = str_replace("..", "", $thumb);

            $file_extension = pathinfo($thumb, PATHINFO_EXTENSION);
            $source_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $thumb;
            $target_file_key = $thumb;
            $urlp = $this->upload_file_s3_new('public-read', $source_file, $target_file_key, $file_extension, false);
            $dbData['story_thumb_s3_url'] = $urlp;
        }
        if (isset($_FILES['story_content']['name'][0]) && !empty($_FILES['story_content']['name'][0])) {
            if ($param['mrss_edit'] === '1' && file_exists('..' . $video)) {
                unlink('..' . $video);
            }
            if ($handle = opendir('../uploads/' . $wgid . '/story_based_mrss/videos')) {
                while (($file = readdir($handle)) !== FALSE) {
                    $videos[] = basename($file);
                }
                closedir($handle);
                $videos = array_diff($videos, array('.', '..'));
            }
            $path = "../uploads/" . $wgid . "/story_based_mrss/videos/";
            $file = $_FILES['story_content']['name'][0];
            $tmp = $_FILES['story_content']['tmp_name'][0];
            $video = $path . $file;
            if (in_array($file, $videos)) {
                $name = pathinfo($file, PATHINFO_FILENAME);
                $extension = pathinfo($file, PATHINFO_EXTENSION);
                $increment = '';
                while (file_exists($path . $name . $increment . '.' . $extension)) {
                    $increment++;
                    $video = $path . $name . $increment . '.' . $extension;
                }
            }
            move_uploaded_file($tmp, $video);
            $video =  str_replace("..", "", $video);

            $file_extension = pathinfo($video, PATHINFO_EXTENSION);
            $source_file = $_SERVER['DOCUMENT_ROOT'] . '/' . $video;
            $target_file_key = $video;
            $urlp = $this->upload_file_s3_new('public-read', $source_file, $target_file_key, $file_extension, false);
            $dbData['story_video_s3_url'] = $urlp;
        }

        if ($video == '') {
            $errors['story_content'] = "This field is required";
            $response['code'] = 201;
            $response['message'] = 'Validation Errors!';
            $response['error'] = $errors;
            $response['data'] = '';
            echo json_encode($response);
            exit;
        }


        $dbData['lead_id'] = $param['lead_id'];
        $dbData['story_title'] = $param['story_title'];
        $dbData['story_description'] = $param['story_description'];
        $dbData['story_tags'] = $param['story_tags'];
        $dbData['brand_id'] = $param['story_brand_id'];
        $dbData['story_video_path'] = $video;
        $dbData['story_thumb_path'] = $thumb;
        $partner_ids = $param['story_partner_id'];
        $response_data = array();
        $dbData['published'] = 0; //TODO MRSS Hotfix
        foreach ($partner_ids as $id) {
            $dbData['partner_id'] = $id;

            $dbData['brands_feed_id'] = $this->b_cats->getCategoryByPartnerIdBrandId($dbData['partner_id'], $dbData['brand_id'])->id;
            if ($param['mrss_edit'] === '1') {
                $this->db->where('id', $param['mrss_id']);
                $this->db->update('mrss_brand_feeds', $dbData);
            } else {
                $dbData['publication_date'] = date('Y-m-d H:i:s');

                if ($this->db->insert('mrss_brand_feeds', $dbData)) {
                    $response['id'] = $this->db->insert_id();

                    // $this->db->insert('brands_mrss_queue', ["brands_story_id" => $response['id'], "publication_date" => date('Y-m-d H:i:s')]);
                }
            }
            $response_data[] = $dbData;
        }
        $response['data'] = $response_data;

        echo json_encode($response);
        exit;
    }

    public function mrss_feed_edit()
    {
        $param = $this->security->xss_clean($this->input->post());
        $mrss_id = $param['mrss_id'];

        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Data Sent!';
        $response['data'] = $this->mrss->getMrssBrandFeedById($mrss_id);

        echo json_encode($response);
        exit;
    }

    public function mrss_feed_delete()
    {
        $param = $this->security->xss_clean($this->input->post());
        $mrss_id = $param['mrss_id'];

        $response = array();
        $response['code'] = 200;
        $response['message'] = 'Deleted Successfully!';

        $is_deleted = $this->mrss->remove_mrss_brand_feed($mrss_id);
        if(!$is_deleted) {
            $response = array();
            $response['code'] = 304;
            $response['message'] = 'Something is going wrong!';
        }

        echo json_encode($response);
        exit;
    }
}
