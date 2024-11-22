<?php

//use Api\App\Utils\JsonResponse;
require APPPATH . '/libraries/API_Controller.php';

/**
 * Created by PhpStorm.
 * User: shehzad.aslam
 * Date: 27/05/2019
 * Time: 9:39 PM
 */

class VideoViewController extends API_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('Json_Response');
        $this->load->model('VideoView');
    }

    public function store()
    {
        $formData = json_decode($this->input->raw_input_stream, true);
        $ip=$formData['ip_address'];
        $video_id=$formData['video_id'];
        $res = $this->db->query('SELECT COUNT(id) as msg_found FROM videos_views WHERE ip_address="'.$ip.'" AND video_id="'.$video_id.'"' )->result();
        $row_ip = $res[0];
        if($row_ip->msg_found == 0) {
            $videoComment = VideoView::create($formData);
        }
        if (isset($videoComment)) {
            $this->api_return(
                $this->json_response->JSONSuccessResult('Video view created successfully', $videoComment),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('Not created'),
                200
            );
        }
    }
}
