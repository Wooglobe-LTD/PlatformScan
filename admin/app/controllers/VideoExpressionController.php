<?php

//use Api\App\Utils\JsonResponse;
require APPPATH . '/libraries/API_Controller.php';



class VideoExpressionController extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Json_Response');
        $this->load->model('VideoExpression');
        $this->load->model('UserTask');
        $this->load->model('User');
    }

    public function store()
    {
        $formData = json_decode($this->input->raw_input_stream, true);
        $alreadyMarkedExpression=VideoExpression::Where('user_id',$formData['user_id'])->where('video_id',$formData['video_id'])->first();

        if (isset($alreadyMarkedExpression) && $alreadyMarkedExpression->count() > 0){

           if (isset($formData['is_like'])){

               $formData['is_dislike']=Null;

               $videoExpression=$alreadyMarkedExpression->update($formData);
           }
           elseif(isset($formData['is_dislike'])){



               $formData['is_like']=Null;
               $videoExpression=$alreadyMarkedExpression->update($formData);
           }
           else{
               $alreadyMarkedExpression->delete();
           }
        }
        elseif(isset($formData['is_like']) || isset($formData['is_dislike'])){

            if (isset($formData['is_like'])){

                    $task=Task::where('task',"Like a Video")->first();

                    $user_task=UserTask::create(["user_id"=>$formData["user_id"],"task_id"=>$task->id]);
                    if (isset($user_task)){
                        $user=User::where("id",$formData["user_id"])->first();
                        $points=$user->points+$task->points;
                        $user->update(["points"=>$points]);

                    }

            }

            $videoExpression = VideoExpression::create($formData);


        }

        /*$videoExpression->load('user');
        $videoExpression->load('video');*/

        if (isset($videoExpression)) {
            $this->api_return(
                $this->json_response->JSONSuccessResult('Video Expression created', $videoExpression),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('Not created'),
                200
            );
        }
    }

   /* public function delete()
    {
        $formData = json_decode($this->input->raw_input_stream, true);

        $videoComment = VideoExpression::find($formData['video_comment_id']);
        $videoComment->delete();
    }*/
}