<?php

//use Api\App\Utils\JsonResponse;
require APPPATH . '/libraries/API_Controller.php';

/**
 * Created by PhpStorm.
 * User: shehzad.aslam
 * Date: 27/05/2019
 * Time: 9:39 PM
 */

class VideoLikeViewController extends API_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('Json_Response');
        $this->load->model('VideoExpression');
    }

    public function likefetch()
    {
        $formData = json_decode($this->input->raw_input_stream, true);
        $user_id=$formData['user_id'];
        $video_id=$formData['video_id'];
        $res = VideoExpression::where(['user_id'=>$user_id, 'video_id'=>$video_id,'is_like'=>1])->get();
        $row = $res->count();
        if ($res) {
            $data = "False";
            if($row > 0){
                $data = "True";
                $this->api_return(
                    $this->json_response->JSONSuccessResult($data, $row),
                    200
                );
            }else{
                $this->api_return(
                    $this->json_response->JSONErrorResult($data, $row),
                    200
                );
            }

        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('Error', $row),
                200
            );
        }
    }
}
