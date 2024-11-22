<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class YoutubeChannelController extends APP_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->data['active'] = 'Youtube Channel Management';
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
            'assets/js/ma_youtube.js'
        );
        $this->data['commomCss'] = array_merge($css, $this->data['commomCss']);
        $this->data['commonJs'] = array_merge($this->data['commonJs'], $js);
        $this->data['assess'] = array(
            'list' => role_permitted_html(false, 'youtube_channel'),
            'can_add' => role_permitted_html(false, 'youtube_channel', 'add_youtube_channel'),
            'can_edit' => role_permitted_html(false, 'youtube_channel', 'update_youtube_channel'),
            'can_delete' => role_permitted_html(false, 'youtube_channel', 'delete_youtube_channel')
        );
        $this->load->model('MobileAPPYoutubeChannel', 'ma_youtube_channel');

    }

    public function index()
    {

        auth();
        role_permitted(false, 'youtube_channel');

        $this->data['title'] = 'Youtube Channel Management';



        $this->data['content'] = $this->load->view('mobile_app/youtube/listing', $this->data, true);


        $this->load->view('common_files/template', $this->data);
    }


    public function youtube_channel_listing()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'youtube_channel');
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

        $result = $this->ma_youtube_channel->getAllYoutubeChannels('id,channel_title,channel_id,youtube_videos_views_limit,youtube_videos_comments_limit,youtube_videos_likes_limit,run_limit,case when (status = 1) THEN "Active" ELSE "Inactive" END as status', $search, $start, $limit, $orderby, $params['columns']);
        $resultCount = $this->ma_youtube_channel->getAllYoutubeChannels();
        $response = array();
        $data = array();

        foreach ($result->result() as $row) {
            $r = array();
            $links = '';
            if ($this->data['assess']['can_edit']) {
                $links .= '<a title="Edit YoutubeChannel" href="javascript:void(0);" class="ma-edit-youtube_channel" data-id="' . $row->id . '"><i class="material-icons">&#xE254;</i></a> ';

            }
            if ($this->data['assess']['can_delete']) {
                $links .= '| <a title="Delete YoutubeChannel" href="javascript:void(0);" class="ma-delete-youtube_channel" data-id="' . $row->id . '"><i class="material-icons">&#xE92B;</i></a>';

            }
             $links .= '| <a title="Get Videos" href="javascript:void(0);" class="ma-get-videos-youtube_channel" data-id="' . $row->id . '" data-run_limit="'.$row->run_limit.'" data-channel_id="'.$row->channel_id.'"><i class="material-icons">play_arrow</i></a>';
            if ($this->data['assess']['can_edit'] || $this->data['assess']['can_delete']) {
                $r[] = $links;
            }
            $r[] = $row->channel_title;
            $r[] = $row->channel_id;
            $r[] = $row->youtube_videos_views_limit;
            $r[] = $row->youtube_videos_comments_limit;
            $r[] = $row->youtube_videos_likes_limit;
             $r[] = $row->run_limit;

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

    public function add_youtube_channel()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'youtube_channel', 'add_youtube_channel');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'New Youtube Channel Added Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $this->validation->set_rules('channel_title', 'Channel Title', 'required');
        $this->validation->set_rules('channel_id', 'Channel ID', 'required');
        $this->validation->set_rules('youtube_videos_views_limit', 'Views Limit', 'required');
        $this->validation->set_rules('youtube_videos_comments_limit', 'Comments Limit', 'required');
        $this->validation->set_rules('youtube_videos_likes_limit', 'Likes Limit', 'required');
        $this->validation->set_rules('run_limit', 'Run Limit', 'required');
        //$this->validation->set_rules('api_key', 'API Key', 'required');
        
        $this->validation->set_rules('status', 'Status', 'trim|required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('channel_title','channel_id','youtube_videos_views_limit','youtube_videos_comments_limit','youtube_videos_likes_limit','run_limit', 'status');
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
            $this->db->insert('ma_youtube_channels', $dbData);
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

    public function get_youtube_channel()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }

        $role_permitted_ajax = role_permitted_ajax(false, 'youtube_channel');
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

        $result = $this->ma_youtube_channel->getYoutubeChannelById($id, 'id,channel_title,channel_id,youtube_videos_views_limit,youtube_videos_comments_limit,youtube_videos_likes_limit,run_limit,status');
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Youtube Channel found!';
            $response['error'] = 'No Youtube Channel found!';
            $response['url'] = '';

        } else {

            $response['data'] = $result;

        }


        echo json_encode($response);
        exit;

    }

    public function update_youtube_channel()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'YoutubeChannel', 'update_youtube_channel');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Youtube Channel Updated Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->ma_youtube_channel->getYoutubeChannelById($id);
        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Youtube Channel found!';
            $response['error'] = 'No Youtube Channel found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        $this->validation->set_rules('channel_id', 'Channel ID', 'required');
        $this->validation->set_rules('channel_title', 'Channel Title', 'required');
         $this->validation->set_rules('youtube_videos_views_limit', 'Views Limit', 'required');
        $this->validation->set_rules('youtube_videos_comments_limit', 'Comments Limit', 'required');
        $this->validation->set_rules('youtube_videos_likes_limit', 'Likes Limit', 'required');
         $this->validation->set_rules('run_limit', 'Run Limit', 'required');
        //$this->validation->set_rules('api_key', 'API Key', 'required');
        
        $this->validation->set_rules('status', 'Status', 'required');
        $this->validation->set_message('required', 'This field is required.');

        if ($this->validation->run() === false) {

            $fields = array('channel_title','channel_id','youtube_videos_views_limit','youtube_videos_comments_limit','youtube_videos_likes_limit','run_limit', 'status');
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
            $this->db->update('ma_youtube_channels', $dbData);
        }

        echo json_encode($response);
        exit;

    }

  

    public function delete_youtube_channel()
    {

        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'youtube_channel', 'delete_youtube_channel');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $response = array();

        $response['code'] = 200;
        $response['message'] = 'Youtube Channel Deleted Successfully!';
        $response['error'] = '';
        $response['url'] = '';
        $id = $this->security->xss_clean($this->input->post('id'));
        $result = $this->ma_youtube_channel->getYoutubeChannelById($id);

        if (!$result) {

            $response['code'] = 201;
            $response['message'] = 'No Youtube Channel found!';
            $response['error'] = 'No Youtube Channel found!';
            $response['url'] = '';
            echo json_encode($response);
            exit;

        }
        // $dbData['updated_at'] = date('Y-m-d H:i:s');
        // $dbData['updated_by'] = $this->sess->userdata('adminId');
        // $dbData['deleted_at'] = date('Y-m-d H:i:s');
        // $dbData['deleted_by'] = $this->sess->userdata('adminId');
        // $dbData['deleted'] = 1;
        // $this->db->where('id', $id);
        // $this->db->update('ma_youtube_channels', $dbData);
        $this->db->where('id', $id);
 $this->db->delete('ma_youtube_channels');
        echo json_encode($response);
        exit;

    }
    public function getChannelId(){
        //  $auth_ajax = auth_ajax();
        // if ($auth_ajax) {
        //     echo json_encode($auth_ajax);
        //     exit;
        // }
        $channel_title = $this->security->xss_clean($this->input->post('channel_title'));
        $channel_title=str_replace(' ', '', $channel_title);

      
        $API_key = $this->config->item('YOUTUBE_API_KEY');

        $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.googleapis.com/youtube/v3/search?part=snippet&format=json&maxResults=50&q=".$channel_title."&type=channel&key=".$API_key,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
));

$response = curl_exec($curl);

curl_close($curl);
$response = json_decode($response, true);

if (!isset($response["error"])) {
    $channels_list=array();
    foreach ($response["items"] as $channel) {
        $channel_id=$channel["id"]["channelId"];
        $channel_title=$channel["snippet"]["title"];
        $channel_thumbnail_url=$channel["snippet"]["thumbnails"]["default"]["url"];
        $channels_list[] = array('channel_id' =>$channel_id ,'channel_title'=>$channel_title,'channel_thumbnail_url'=>$channel_thumbnail_url );        
        
    }
    


}
else{
    echo "Some Errors In Youtube API Call";
}
        $channels_response['code'] = 200;
        $channels_response['message'] = 'Record found!';
        $channels_response['error'] = '';
        $channels_response['url'] = '';
         $channels_response['data'] = $channels_list;
echo json_encode($channels_response);
exit;
    }

}
