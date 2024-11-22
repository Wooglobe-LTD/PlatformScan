<?php

//use Api\App\Utils\JsonResponse;
require APPPATH . '/libraries/API_Controller.php';

/**
 * Created by PhpStorm.
 * User: shehzad.aslam
 * Date: 27/05/2019
 * Time: 9:39 PM
 */
class AssignmentController extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Json_Response');
        $this->load->model('Event');
        $this->load->model('Assignment');
        $this->load->model('User');
    }

    /**
     * Listing of categories and subcategories
     */
    public function index()
    {
        $formData = json_decode($this->input->raw_input_stream, true);


        $assignments = Assignment::with(['users', 'event'])->where('user_id', $formData['user_id'])->paginate(10);


        if ($assignments) {
            $this->api_return(
                $this->json_response->JSONSuccessResult('Assignment Records found', $assignments),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('No record found'),
                422
            );
        }
    }

    public function saveVideo()
    {
        $formData = $this->input->post();



        if (!file_exists( $_SERVER['DOCUMENT_ROOT'] . "/api/uploads/".$formData['user_id']."/".$formData['event_name']."/assignments/")) {
            mkdir( $_SERVER['DOCUMENT_ROOT'] . "/api/uploads/".$formData['user_id']."/".$formData['event_name']."/assignments/", 0777, true);
        }
        $configVideo['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . "/api/uploads/".$formData['user_id']."/".$formData['event_name']."/assignments/"; # check path is correct

        $configVideo['max_size'] = '102400';
        $configVideo['allowed_types'] = 'mp4'; # add video extenstion on here
        $configVideo['overwrite'] = FALSE;
        $configVideo['remove_spaces'] = TRUE;
        $video_name = random_string('numeric', 5);
        $configVideo['file_name'] = $video_name;

        $this->load->library('upload', $configVideo);
        $this->upload->initialize($configVideo);

        if (!$this->upload->do_upload('assignment_video')) # form input field attribute
        {
            # Upload Failed
            $this->api_return(
                $this->json_response->JSONErrorResult('Upload Failed',$this->upload->display_errors()),
                200
            );
        } else {
            # Upload Successfull
            $url = "api/uploads/".$formData['user_id']."/".$formData['event_name']."/assignments/".  $video_name.".mp4";
             $data['user_id']=$formData['user_id'];
            $data['event_id']=$formData['event_id'];
            $data['video']=$url;
            $assignment = Assignment::create($data);
            if ($assignment){
                $this->api_return(
                    $this->json_response->JSONSuccessResult('Video Submitted', $assignment),
                    200
                );
            }

        }
    }


}
