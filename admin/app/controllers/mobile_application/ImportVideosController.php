<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImportVideosController extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('csvimport');

        $this->data['active'] = 'App Import Videos';
        $css = array(
            'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css'
        );
        $js = array(

            'assets/js/pages/forms_file_upload.js',
            'assets/js/custom/dropify/dist/js/dropify.min.js',
            'assets/js/pages/forms_file_input.min.js',
            'assets/js/bulk.js'
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);
        $this->data['assess'] = array(
            'can_upload' => role_permitted_html(false, 'bulk'),

        );


    }


    public function importVideosView()
    {
        auth();
        role_permitted(false, 'import videos');
        $this->data['content'] = $this->load->view('mobile_app/videos/import_videos', $this->data, true);
        $this->load->view('common_files/template', $this->data);
    }

    function createSlug($str, $delimiter = '-')
    {

        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;

    }

    public function importVideos()
    {
//        check is user authorize to do it


        /*check is file exixts */


        if ($_FILES["csv"]["tmp_name"]) {
            $column_headers = array('category_id', 'title', 'description', 'tags', 'url', 'is_wooglobe_video','youtube_id','thumbnail');
            $file_data = $this->csvimport->get_array($_FILES["csv"]["tmp_name"], $column_headers);

            /*check is array not empty before inserting into db*/
            if (!empty($file_data)) {
                $ci = &get_instance();

                $adminId = $ci->sess->userdata('adminId');

                $import_status_message = "<h3>Duplicate Videos</h3><table><tbody><th>Title</th><th>URL</th>";
                foreach ($file_data as $row) {
                    $row['created_by'] = $adminId;
                    $row['created_at'] = date("Y-m-d H:i:s");


                    $row['slug'] = $this->createSlug($row['title']);

                    $this->db->select('url');
                    $this->db->where(array('url' => $row['url']));
                    $query = $this->db->get('videos');


                    if ($query->num_rows() > 0) {

                        $response['duplicate_rows'][] = array($row['title'], $row['url']);
                        $import_status_message .= "<tr><td>" . $row['title'] . "</td><td>" . $row['url'] . "</td></tr>";


                    } else {
                        $this->db->insert('videos', $row);

                    }


                }
                $import_status_message .= "</tbody></table>";


            }

            $response['code'] = 200;
            if (isset($response['duplicate_rows'])) {
                $response['import_status_message'] = $import_status_message;
                $response['message'] = 'Videos Imported Successfully but some rows rejected due to duplications!';
            } else {
                $response['import_status_message'] = "All Videos imported successfully";
                $response['message'] = 'Videos Import Successfully!';
            }


            $response['error'] = '';
            $response['url'] = '';

            echo json_encode($response);
            exit();

        } else {

            $response['message'] = 'File not exists';
            echo json_encode($response);
            exit();
        }


    }


    /* public function index(){
         auth();
         role_permitted(false,'bulk');
         $this->data['content'] = $this->load->view('bulk/upload_edited_video',$this->data,true);
         $this->load->view('common_files/template',$this->data);
     }*/


}
