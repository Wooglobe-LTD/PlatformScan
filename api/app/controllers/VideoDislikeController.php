<?php

//use Api\App\Utils\JsonResponse;
require APPPATH . '/libraries/API_Controller.php';

/**
 * Created by PhpStorm.
 * User: shehzad.aslam
 * Date: 27/05/2019
 * Time: 9:39 PM
 */

class VideoDislikeController extends API_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('Json_Response');
        $this->load->model('VideoDislike');
    }

    public function store()
    {
        $formData = json_decode($this->input->raw_input_stream, true);

        $videoComment = VideoDislike::create($formData);

        if ($videoComment) {
            $this->api_return(
                $this->json_response->JSONSuccessResult('Video Disliked successfully', $videoComment),
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
