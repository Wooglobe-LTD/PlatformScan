<?php

//use Api\App\Utils\JsonResponse;
require APPPATH . '/libraries/API_Controller.php';

/**
 * Created by PhpStorm.
 * User: shehzad.aslam
 * Date: 27/05/2019
 * Time: 9:39 PM
 */

class VideoCommentController extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Json_Response');
        $this->load->model('VideoComment');
    }

    public function store()
    {
        $formData = json_decode($this->input->raw_input_stream, true);

        $videoComment = VideoComment::create($formData);
        $videoComment->load('user');

        if ($videoComment) {
            $this->api_return(
                $this->json_response->JSONSuccessResult('Video comment created', $videoComment),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('Not created'),
                200
            );
        }
    }

    public function update()
    {
        $formData = json_decode($this->input->raw_input_stream, true);
        $VideoCommentUpdate = VideoComment::where('id',$formData['comment_id'])->first();
       if (isset($VideoCommentUpdate)){
           $videoComment = $VideoCommentUpdate->update($formData);

       }




        if (isset($videoComment)) {
            $VideoCommentUpdated = VideoComment::where('id',$formData['comment_id'])->first();
            $VideoCommentUpdated->load('user');
            $this->api_return(
                $this->json_response->JSONSuccessResult('Video comment Update', $VideoCommentUpdated),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('Not Updated'),
                200
            );
        }
    }
    public function delete()
    {
        $formData = json_decode($this->input->raw_input_stream, true);

        $videoComment = VideoComment::where('id',$formData['comment_id'])->first();


        if ( $videoComment->delete()) {
            $this->api_return(
                $this->json_response->JSONSuccessResult('Video comment Deleted'),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('Not Deleted'),
                200
            );
        }
    }
}
