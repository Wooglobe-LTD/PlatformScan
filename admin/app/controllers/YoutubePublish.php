<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class YoutubePublish extends APP_Controller {

	public function __construct() {
        parent::__construct();
		$this->data['active'] = 'youtube_publish';
		$css = array(
			'bower_components/uikit/css/uikit.almost-flat.min.css',
            'assets/skins/dropify/css/dropify.css',
		);
		$js = array(
            'bower_components/jszip/dist/jszip.min.js',
            'bower_components/pdfmake/build/pdfmake.min.js',
            'bower_components/pdfmake/build/vfs_fonts.js',
            'assets/js/custom/dropify/dist/js/dropify.min.js',
            'assets/js/pages/forms_file_input.min.js',
		);
		if(role_permitted_html(false)){
            $js[] = 'assets/js/youtube_publish.js';
        }
		$this->data['commomCss'] = array_merge($css,$this->data['commomCss']);
		$this->data['commonJs'] = array_merge($this->data['commonJs'],$js);
        $this->load->library('youtube');
		$this->load->model('Video_Deal_Model', 'deal');
        $this->load->model('Video_Model', 'video');
    }
	
    public function publish_youtube() {
        // Authentication and Role Validation
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals', 'can_distribute');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        
        $response = [
            'code' => 200,
            'message' => "Video Published on YouTube Successfully!",
            'error' => ""
        ];
        $request = $this->security->xss_clean($this->input->post());

        $dbData = [
            'publish_type' => 'YouTube',
            'video_title' => $request['title'],
            'video_description' => $request['description'].$request['desc_footer'],
            'video_tags' => $request['tags'],
            'youtube_channel' => $request['channel'],
            'youtube_category' => $request['category'],
            'youtube_publish_status' => $request['publish_status']
        ];

        // Handling dbData for immediate or scheduled publishing
        if (isset($request['publish_now_youtube']) && $request['publish_now_youtube'] == '1') {
            $dbData['publish_now'] = 1;
            $dbData['published'] = 1;
        } else {
            if($request['publish_status'] != 'private') {
                $response = [
                    'code' => 201,
                    'message' => "please set publish status to private"
                ];
                echo json_encode($response);
                exit();
            }
            $dbData['publish_now'] = 0;
            $dbData['publish_datetime'] = $request['publish_date'] . ' ' . $request['publish_time'] . ':00';
        }

        // Update or Insert Video Publishing Data
        $is_update_video = false;
        $publishData = $this->deal->getPublishData($request['video_id'], 'YouTube');
        if ($publishData) {
            $this->db->where('video_id', $request['video_id']);
            $this->db->where('publish_type', 'YouTube');
            $this->db->update('video_publishing_scheduling', $dbData);
            $is_update_video = true;
        } else {
            $dbData['video_id'] = $request['video_id'];
            $this->db->insert('video_publishing_scheduling', $dbData);
        }

        // Fetching the latest publishData and processing
        $publishData = $this->deal->getPublishData($request['video_id'], 'YouTube');
        if ($publishData) {
            if($request['publish_now_youtube'] == 0){
                $publishData->yt_schedule = 1;
                $publishData->yt_schedule_time = $request['publish_date'] . ' ' . $request['publish_time'] . ':00';
            }
            $youtube_video_type = isset($request['video_type']) && $request['video_type'] == "2" ? "2" : "1";
            $youtube_video_thumb = "";
            $video_data = "";
            
            // Edited Video
            if($youtube_video_type == "2") {
                $video_data = $this->deal->getPortalVideo($request['video_id']);
                if ($video_data)
                    $youtube_video_thumb = $video_data->portal_thumb;
                $msg = "Edited video URL not found";
            }
            // Watermark Video
            else {
                $video_data = $this->deal->getPortalVideo($request['video_id']);
                if ($video_data)
                    $youtube_video_thumb = $video_data->portal_thumb;
                $video_data = $this->deal->getWatermarkVideo($request['video_id']);
                $msg = "Watermark video URL not found";
            }

            // If no URL is found
            if ($video_data == false) {
                if(!$is_update_video){
                    $this->db->where('video_id', $request['video_id']);
                    $this->db->where('publish_type', 'YouTube');
                    $this->db->where('youtube_channel', $request['channel']);
                    $this->db->delete('video_publishing_scheduling');
                }
                $response = [
                    'code' => 201,
                    'message' => $msg,
                    'error' => array('youtube_video_type' => $msg)
                ];
                echo json_encode($response);
                exit;
            }

            // Publish the video
            $video = $this->video->getVideoById($request['video_id']);
            $result = $this->youtube->publishVideo($video_data, $publishData, $youtube_video_type, $youtube_video_thumb, $is_update_video, $video->youtube_id);
            if ($result['error'] == false) {
                $this->db->where('id', $request['video_id']);
                $this->db->update('videos', array('youtube_id' => $result['id'], 'youtube_repub' => 0));
                $this->db->where('video_id', $request['video_id']);
                $this->db->where('publish_type', 'YouTube');
                $this->db->update('video_publishing_scheduling', array('published' => 1));
                if ($video_data) {
                    $status = $this->deal->updateYoutubeStatus($video->lead_id);
                    $this->deal->dealStatusChangeFromDistributeToWon($video->lead_id);
                    // Cleanup after publishing
                    @unlink($video_data->yt_url); // delete youtube video from server after upload
                    $WGA = $this->db->select('unique_key')->from('video_leads')->join('videos', 'videos.lead_id = video_leads.id')->where('videos.id', $request['video_id'])->get()->row()->unique_key;
                    if (isset($WGA->unique_key) && !empty($WGA->unique_key)) {
                        $WGA_dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$WGA->unique_key;
                        $yt_thumbnails_path = $WGA_dir.'/edited_videos/youtube/thumbnail';
                        if (is_dir($yt_thumbnails_path)) {
                            $thumbnails_list = scandir($yt_thumbnails_path);
                            foreach ($thumbnails_list as $thumbnail) {
                                @unlink($yt_thumbnails_path.'/'.$thumbnail); // delete related thumbnails from server after upload
                            }
                        }
                    }
                }
                $response['data'] = $result;
            }
            else {
                $response = [
                    'code' => 206,
                    'message' => $result['msg'] != NULL? $result['msg']: "Something went wrong!"
                ];
                if(!$is_update_video){
                    $this->db->where('video_id', $request['video_id']);
                    $this->db->where('publish_type', 'YouTube');
                    $this->db->where('youtube_channel', $request['channel']);
                    $this->db->delete('video_publishing_scheduling');
                }
            }
        }
        else {
            $response = [
                'code' => 201,
                'message' => "Video not published!"
            ];
            echo json_encode($response);
            exit;

        }

        echo json_encode($response);
        exit;
    }

    public function seo_optimize_youtube_data()
    {
        $auth_ajax = auth_ajax();
        if ($auth_ajax) {
            echo json_encode($auth_ajax);
            exit;
        }
        $role_permitted_ajax = role_permitted_ajax(false, 'video_deals');
        if ($role_permitted_ajax) {
            echo json_encode($role_permitted_ajax);
            exit;
        }
        $params = $this->security->xss_clean($this->input->post());
        $title = $params['title'];
        $description = $params['description'];
        $target_words = $params['target_words'];

        $response = [
            'code' => 200,
            'message' => "SEO Data Fetched!",
            'error' => "",
            'data' => null
        ];
        if (!isset($params['target_words']) || empty($params['target_words'])) {
            $response = [
                'code' => 201,
                'message' => "No Target Words Found!",
                'error' => array('seo-optimized-check' => "target words feild is empty"),
            ];    
            echo json_encode($response);
            exit;
        }
        
        $query = $this->db->get('configurations');
        $data = $query->row_array();
        $prompt = $data['youtube_seo_prompt'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        $data = array(
            'model' => 'gpt-4o',
            'messages' => array(
                array(
                    'role' => 'user',
                    'content' => $prompt.'

                        My current title is : '.$title.'
                        Current description is : '.$description.'
                        The target keyword is: '.$target_words.'

                        Please draft the Title, Description, and Keywords for my video, following these guidelines.
                        Ensure all keywords are comma-separated, and make sure no steps are missed.
                        The response should be in JSON object having the following structure
                        {
                        "title": "<SEO-optimized title>",
                        "description": "<SEO-optimized description>",
                        "tags": "<Comma-separated keywords>"
                        }
                        Please return only this JSON object, with no leading or trailing characters.',
                ),
            ),
            'temperature' => 0.1,
        );
        
        $jsonData = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: Bearer ' . CHAT_GPT_KEY;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $gpt_response = curl_exec($ch);

        $fields = ['title', 'description', 'keywords'];
        if (!curl_errno($ch)) {
            $gpt_response = json_decode($gpt_response);
            $content = $gpt_response->choices[0]->message->content;
            $content = str_replace('`', '', $content);
            $content = substr($content, strpos($content, '{'), strpos($content, '}') + 1);
            $content = json_decode($content, true);
            if (isset($content['title'], $content['description'], $content['tags'])) {
                $response['data'] = $content;
            }
            else {
                $response = [
                    'code' => 202,
                    'message' => "GPT Response Error!",
                    'error' => "Expected fields are missing from the GPT response JSON."
                ];
            }
        }
        else {
            $response = [
                'code' => 202,
                'message' => "Query Error!",
                'error' => "Unable to execute GPT query."
            ];
        }
        curl_close($ch);

        echo json_encode($response);
        exit;
    }
}
