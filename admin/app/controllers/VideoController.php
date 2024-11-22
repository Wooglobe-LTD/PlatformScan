<?php

//use Api\App\Utils\JsonResponse;
require APPPATH . '/libraries/API_Controller.php';

/**
 * Created by PhpStorm.
 * User: shehzad.aslam
 * Date: 27/05/2019
 * Time: 9:39 PM
 */
class VideoController extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Json_Response');
        $this->load->model('Video');
        $this->load->model('VideoLead');
        $this->load->model('UserInterest');
        $this->load->model('PromotedVideo');
        $this->load->model('VideoComplaint');

    }

    /**
     * Method to return videos list
     */
    public function index()
    {
        $params = $this->input->get();

        //If pagination limit set from request
        $limit = isset($params['limit']) ? $params['limit'] : $this->config->item('PAGINATION_DEFAULT_LIMIT');

        $videoQuery = Video::query();

        // Filter results using search filter
        if (isset($params['searchKey'])) {
            $videoQuery->where("title", 'LIKE', '%' . $params['searchKey'] . '%')
                ->orWhere("description", 'LIKE', '%' . $params['searchKey'] . '%');
        }

        //Final query
        $videoQuery->where('status', 1);
        $videoQuery->where('deleted', 0);
        $videoQuery->orderBy('id', 'desc');

        $videos = $videoQuery->paginate($limit, ['*'], 'page', isset($params['page']) ? $params['page'] : 1);

        $videos->load('videoComments', 'videoDislikes', 'videoLikes', 'videoViews');


        if ($videos) {
            $this->api_return(
                $this->json_response->JSONSuccessResult('Records found', $videos),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('No record found'),
                200
            );
        }

    }

    function moveElement(&$array, $a, $b)
    {
        $out = array_splice($array, $a, 1);
        array_splice($array, $b, 0, $out);
    }

    function search($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, $this->search($subarray, $key, $value));
            }
        }

        return $results;
    }

    public function trending()
    {


        $formData = $this->input->get();
        /*$userIntrests=UserInterest::where('user_id',isset($params['user_id'])?$params['user_id']:"")->first();
        if ($userIntrests){
            $userIntrests=explode(",",$userIntrests->category_id);

        }*/


        //If pagination limit set from request
        /*$limit = isset($params['limit']) ? $params['limit'] : $this->config->item('PAGINATION_DEFAULT_LIMIT');

        $getTrendingVideos = Video::where(function ($q) {
            $q->from('VideoView');
        })->where('status', 1)->where('deleted', 0)->where('is_wooglobe_video', 0);
        if (isset($params['skip']) && isset($params['take'])) {
            $getTrendingVideos = $getTrendingVideos->skip($params['skip'])->take($params['take'])->paginate($limit, ['*'], 'page', isset($params['page']) ? $params['page'] : 1);
        } else {
            $getTrendingVideos = $getTrendingVideos->paginate($limit, ['*'], 'page', isset($params['page']) ? $params['page'] : 1);
        }


        /*$sql = 'SELECT v.*,(SELECT COUNT(vv.id) FROM videos_views vv WHERE vv.video_id = v.id) as vcoun
            FROM videos v

            WHERE v.status = 1
             AND v.deleted = 0
             AND v.is_wooglobe_video = 0

             ORDER BY vcoun DESC ';
        if (isset($params['limit']) && isset($params['start']) && $params['limit'] > 0) {
            $sql .= " LIMIT " . $params['start'] . "," . $params['limit'] . "";
        }
        $query = $this->db->query($sql);
        $getTrendingVideos = $query->result();*/

        //return print_r(count($getTrendingVideos));


        /*$getTrendingVideos->load('videoComments', 'videoDislikes', 'videoLikes', 'videoViews', 'VideoExpressions');*/


        $days = $formData["weeks"] * 6;
        $timestamp = time();
        $from_timestamp = strtotime("$days day ago", $timestamp);
        $from = date('Y-m-d', $from_timestamp);
        $to_timestamp = strtotime("6 days", $from_timestamp);
        $to = date('Y-m-d', $to_timestamp);

        $getTrendingVideos = Video::where('created_at', ">", $from)
            ->where("status", 1)
            ->where('created_at', "<=", $to)
            ->inRandomOrder()
            ->paginate($this->config->item('PAGINATION_DEFAULT_LIMIT'));


        //$for_you = Video::With('Interest')->whereBetween('created_at', [$last_Week, $today])->paginate($this->config->item('PAGINATION_DEFAULT_LIMIT'));
        if ($getTrendingVideos) {


            // $for_you->load('videoComments', 'videoDislikes', 'videoLikes', 'videoViews', 'VideoExpressions');
            foreach ($getTrendingVideos as $video) {

                $comments_weightage = round(($this->config->item('COMMENTS_WEIGHTAGE') / 100) * $video->comments_count);
                $like_weightage = round(($this->config->item('LIKE_WEIGHTAGE') / 100) * $video->likes_count);
                $views_weightage = round(($this->config->item('VIEWS_WEIGHTAGE') / 100) * $video->views_count);
                $total_weightage = $comments_weightage + $like_weightage + $views_weightage;
                $video["total_weightage"] = $total_weightage;
                $video["week"] = $formData["weeks"];
                if ($video->source_type == "youtube") {
                    $video["url"] = "https://www.youtube.com/embed/" . $video->youtube_id;
                }


            }


            if ($getTrendingVideos) {

                //  print_r(json_encode($getTrendingVideos->toArray()));
                //  exit;
                $promoted_video_position_x = $this->config->item('PROMOTED_VIDEO_POSITION_X');
                $repeat_promoted_video_y = $this->config->item('REPEAT_PROMOTED_VIDEO_Y');
                $get_rondom_video_count = ceil($this->config->item('PAGINATION_DEFAULT_LIMIT') / $repeat_promoted_video_y);

                $random_promoted_video = PromotedVideo::with("videos")
                    ->get();


                $getTrendingVideos = $getTrendingVideos->toArray();

                if (!$random_promoted_video->isEmpty()&& count($getTrendingVideos["data"]) >= ($this->config->item('PROMOTED_VIDEO_POSITION_X')-1)) {


                    $random_promoted_video->random($get_rondom_video_count);
                    // foreach($getTrendingVideos as $video){


                    //     foreach($random_promoted_video as $promoted_video){

                    //         // if(array_search($promoted_video->video_id,$video,true)){
                    //         //     echo $promoted_video->video_id;
                    //         //     print_r($video);
                    //         // }
                    //         echo $promoted_video->video_id;
                    //         print_r($this->search($video, 'id', $promoted_video->video_id));

                    //     }
                    //  }

                    $i = 0;

                    foreach ($random_promoted_video as $promoted_video) {

                        //$promoted_video=Video::where("id",$promoted_video->video_id)->get();

                        $promoted_video["videos"]["is_promoted"] = 1;

                        $promoted_video["videos"]["promoted_video_position_x"] = ($this->config->item('PROMOTED_VIDEO_POSITION_X')-1) + ($i * $this->config->item('REPEAT_PROMOTED_VIDEO_Y'));


                        array_push($getTrendingVideos, $promoted_video);
                        //array_splice( $getTrendingVideos, $promoted_video_position_x, 0, $promoted_video );
                        //print_r(json_encode($promoted_video));
                        $i++;

                    }


                }

                //$this->moveElement($trending_videos, 0, 1);


                $this->api_return(
                    $this->json_response->JSONSuccessResult('Records found', $getTrendingVideos),

                    200
                );
            } else {
                $this->api_return(
                    $this->json_response->JSONErrorResult('No record found'),
                    200
                );
            }

        }
    }

    public
    function latest()
    {


        $params = $this->input->get();
        /*$userIntrests=UserInterest::where('user_id',isset($params['user_id'])?$params['user_id']:"")->first();
        if ($userIntrests){
            $userIntrests=explode(",",$userIntrests->category_id);

        }*/


        //If pagination limit set from request
        $limit = isset($params['limit']) ? $params['limit'] : $this->config->item('PAGINATION_DEFAULT_LIMIT');


        //Final query

        $getLatestVideos = Video::where('status', 1)->where('deleted', 0)->whereNotNull('youtube_id')->orderBy("created_at", "desc");

        if (isset($params['skip']) && isset($params['take'])) {
            $getLatestVideos = $getLatestVideos->skip($params['skip'])->take($params['take'])->paginate($limit, ['*'], 'page', isset($params['page']) ? $params['page'] : 1);
        } else {
            $getLatestVideos = $getLatestVideos->paginate($limit, ['*'], 'page', isset($params['page']) ? $params['page'] : 1);
        }
        foreach ($getLatestVideos as $video) {
            if ($video->source_type == "youtube") {
                $video["url"] = "https://www.youtube.com/embed/" . $video->youtube_id;
            }
        }

        $getLatestVideos->load('videoComments', 'videoDislikes', 'videoLikes', 'videoViews', 'VideoExpressions');

        if ($getLatestVideos) {
            $repeat_promoted_video_y = $this->config->item('REPEAT_PROMOTED_VIDEO_Y');
            $get_rondom_video_count = ceil($this->config->item('PAGINATION_DEFAULT_LIMIT') / $repeat_promoted_video_y);

            $random_promoted_video = PromotedVideo::with("videos")
                ->get();


            $getLatestVideos = $getLatestVideos->toArray();

            if (!$random_promoted_video->isEmpty()&& count($getLatestVideos["data"]) >= ($this->config->item('PROMOTED_VIDEO_POSITION_X')-1)) {


                $random_promoted_video->random($get_rondom_video_count);
                // foreach($getTrendingVideos as $video){


                //     foreach($random_promoted_video as $promoted_video){

                //         // if(array_search($promoted_video->video_id,$video,true)){
                //         //     echo $promoted_video->video_id;
                //         //     print_r($video);
                //         // }
                //         echo $promoted_video->video_id;
                //         print_r($this->search($video, 'id', $promoted_video->video_id));

                //     }
                //  }

                $i = 0;

                foreach ($random_promoted_video as $promoted_video) {

                    //$promoted_video=Video::where("id",$promoted_video->video_id)->get();

                    $promoted_video["videos"]["is_promoted"] = 1;

                    $promoted_video["videos"]["promoted_video_position_x"] = ($this->config->item('PROMOTED_VIDEO_POSITION_X')-1) + ($i * $this->config->item('REPEAT_PROMOTED_VIDEO_Y'));


                    array_push($getLatestVideos, $promoted_video);
                    //array_splice( $getTrendingVideos, $promoted_video_position_x, 0, $promoted_video );
                    //print_r(json_encode($promoted_video));
                    $i++;

                }


            }

            $this->api_return(
                $this->json_response->JSONSuccessResult('Records found', $getLatestVideos),

                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('No record found'),
                200
            );
        }

    }


    public
    function forYou()
    {


        $formData = $this->input->get();


        $user_id = $formData["user_id"];
        $days = $formData["weeks"] * 6;
        $timestamp = time();

        $from_timestamp = strtotime("$days day ago", $timestamp); //next day is used because wherebetween excludes TO value
        $from = date('Y-m-d', $from_timestamp);
        $to_timestamp = strtotime("6 days", $from_timestamp);
        $to = date('Y-m-d', $to_timestamp);
        // $for_you = Video::with(['videoComments', 'videoDislikes', 'videoLikes', 'videoViews', 'VideoExpressions'])
        // ->whereBetween('created_at', [$from, $to])
        // ->join('user_interests', 'user_interests.category_id', '=', 'ma_videos.ma_videos')
        // ->join('user_interests', function ($join) {
        //     $join->on('user_interests.category_id', '=', 'ma_videos.ma_videos')
        //          ->where('user_interests.user_id', $user_id);
        // })
        // //->where("user_interests.user_id",$user_id)
        // ->select("ma_videos.*")
        // ->inRandomOrder()->paginate($this->config->item('PAGINATION_DEFAULT_LIMIT'));
        $user_interests = UserInterest::where("user_id", $user_id)->pluck("category_id");

        $for_you = Video::whereIn("category_id", $user_interests)
            ->whereBetween('created_at', [$from, $to])
            ->where("status", 1)
            ->inRandomOrder()->paginate($this->config->item('PAGINATION_DEFAULT_LIMIT'));


        //$for_you = Video::With('Interest')->whereBetween('created_at', [$last_Week, $today])->paginate($this->config->item('PAGINATION_DEFAULT_LIMIT'));
        if ($for_you) {


            // $for_you->load('videoComments', 'videoDislikes', 'videoLikes', 'videoViews', 'VideoExpressions');
            foreach ($for_you as $video) {
                $comments_weightage = round((30 / 100) * $video->comments_count);
                $like_weightage = round((40 / 100) * $video->likes_count);
                $views_weightage = round((30 / 100) * $video->views_count);
                $total_weightage = $comments_weightage + $like_weightage + $views_weightage;
                $video["total_weightage"] = $total_weightage;
                $video["week"] = $formData["weeks"];
                if ($video->source_type == "youtube") {
                    $video["url"] = "https://www.youtube.com/embed/" . $video->youtube_id;
                }


            }


            /*sorting will be on app side*/
            // $for_you=$for_you->sortByDesc("total_weightage");

            $repeat_promoted_video_y = $this->config->item('REPEAT_PROMOTED_VIDEO_Y');
            $get_rondom_video_count = ceil($this->config->item('PAGINATION_DEFAULT_LIMIT') / $repeat_promoted_video_y);

            $random_promoted_video = PromotedVideo::with("videos")
                ->get();


            $for_you = $for_you->toArray();

            if (!$random_promoted_video->isEmpty() && count($for_you["data"]) >= ($this->config->item('PROMOTED_VIDEO_POSITION_X')-1)) {


                $random_promoted_video->random($get_rondom_video_count);
                // foreach($getTrendingVideos as $video){


                //     foreach($random_promoted_video as $promoted_video){

                //         // if(array_search($promoted_video->video_id,$video,true)){
                //         //     echo $promoted_video->video_id;
                //         //     print_r($video);
                //         // }
                //         echo $promoted_video->video_id;
                //         print_r($this->search($video, 'id', $promoted_video->video_id));

                //     }
                //  }

                $i = 0;

                foreach ($random_promoted_video as $promoted_video) {

                    //$promoted_video=Video::where("id",$promoted_video->video_id)->get();

                    $promoted_video["videos"]["is_promoted"] = 1;

                    $promoted_video["videos"]["promoted_video_position_x"] = ($this->config->item('PROMOTED_VIDEO_POSITION_X')-1) + ($i * $this->config->item('REPEAT_PROMOTED_VIDEO_Y'));


                    //array_splice( $getTrendingVideos, $promoted_video_position_x, 0, $promoted_video );
                    //print_r(json_encode($promoted_video));
                    $i++;

                }
                array_push($for_you, $promoted_video);
            }


            $this->api_return(
                $this->json_response->JSONSuccessResult('Record Found', $for_you),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('No record found'),
                200
            );
        }

    }


    public function getVideo()
    {
        $formData = json_decode($this->input->raw_input_stream, true);


        $video = Video::where('id', $formData['id'])->first();

        if (isset($video)) {
            if ($video->source_type == "youtube") {
                $video["url"] = "https://www.youtube.com/embed/" . $video->youtube_id;
            }
            $video->load('videoComments', 'videoDislikes', 'videoLikes', 'videoViews', 'VideoExpressions');
            $this->api_return(
                $this->json_response->JSONSuccessResult('Video result', $video),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('No record found', $formData),
                200
            );
        }

    }
    public function getTemplateByShortCode($code)
    {

        $query = "Select * FROM email_templates where short_code = '" . $code . "'";

        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return false;
        }

    }
    public function videoComplaint(){
        $formData = json_decode($this->input->raw_input_stream, true);

        $formData["ticket_number"]=uniqid();
        $complaint_registered=VideoComplaint::create($formData);
        if ($complaint_registered){
            $emailTemplate = $this->getTemplateByShortCode("video_complaint");
            $message = $emailTemplate->message;
            $message = str_replace("{TICKET-NUMBER}", $complaint_registered->ticket_number, $message);


            $this->email($this->config->item('ADMIN-EMAIL-FOR-VIDEO-COMPLAINT'),"Admin", 'norelpty@viralgreats.com', 'WooGlobe', $emailTemplate->subject, $message);


            $this->api_return(
                $this->json_response->JSONSuccessResult('Complaint Registered', $complaint_registered),
                200
            );
        }
        else{
            $this->api_return(
                $this->json_response->JSONErrorResult('No record found', $formData),
                200
            );
        }
    }

    /*
        to take encoded files as a parameter,decoded,save as a file,and return json message
    */
    function upload_file($encoded_string)
    {
        $result = [];
        $target_dir = FCPATH . "assets/"; // add the specific path to save the file
        $decoded_file = base64_decode($encoded_string); // decode the file
        $mime_type = finfo_buffer(finfo_open(), $decoded_file, FILEINFO_MIME_TYPE); // extract mime type
        $extension = $this->mime2ext($mime_type); // extract extension from mime type
        $file = uniqid() . '.' . $extension; // rename file as a unique name
        $file_dir = $target_dir . uniqid() . '.' . $extension;
        try {
            file_put_contents($file_dir, $decoded_file); // save
            $result['url'] = $file;
            $result['status'] = true;
            return $result;
        } catch (Exception $e) {
            $result['status'] = true;
            $result['message'] = $e->getMessage();
            return $result;
        }

    }

    /*
        to take mime type as a parameter and return the equivalent extension
    */
    function mime2ext($mime)
    {
        $all_mimes = '{"png":["image\/png","image\/x-png"],"bmp":["image\/bmp","image\/x-bmp",
    "image\/x-bitmap","image\/x-xbitmap","image\/x-win-bitmap","image\/x-windows-bmp",
    "image\/ms-bmp","image\/x-ms-bmp","application\/bmp","application\/x-bmp",
    "application\/x-win-bitmap"],"gif":["image\/gif"],"jpeg":["image\/jpeg",
    "image\/pjpeg"],"xspf":["application\/xspf+xml"],"vlc":["application\/videolan"],
    "wmv":["video\/x-ms-wmv","video\/x-ms-asf"],"au":["audio\/x-au"],
    "ac3":["audio\/ac3"],"flac":["audio\/x-flac"],"ogg":["audio\/ogg",
    "video\/ogg","application\/ogg"],"kmz":["application\/vnd.google-earth.kmz"],
    "kml":["application\/vnd.google-earth.kml+xml"],"rtx":["text\/richtext"],
    "rtf":["text\/rtf"],"jar":["application\/java-archive","application\/x-java-application",
    "application\/x-jar"],"zip":["application\/x-zip","application\/zip",
    "application\/x-zip-compressed","application\/s-compressed","multipart\/x-zip"],
    "7zip":["application\/x-compressed"],"xml":["application\/xml","text\/xml"],
    "svg":["image\/svg+xml"],"3g2":["video\/3gpp2"],"3gp":["video\/3gp","video\/3gpp"],
    "mp4":["video\/mp4"],"m4a":["audio\/x-m4a"],"f4v":["video\/x-f4v"],"flv":["video\/x-flv"],
    "webm":["video\/webm"],"aac":["audio\/x-acc"],"m4u":["application\/vnd.mpegurl"],
    "pdf":["application\/pdf","application\/octet-stream"],
    "pptx":["application\/vnd.openxmlformats-officedocument.presentationml.presentation"],
    "ppt":["application\/powerpoint","application\/vnd.ms-powerpoint","application\/vnd.ms-office",
    "application\/msword"],"docx":["application\/vnd.openxmlformats-officedocument.wordprocessingml.document"],
    "xlsx":["application\/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application\/vnd.ms-excel"],
    "xl":["application\/excel"],"xls":["application\/msexcel","application\/x-msexcel","application\/x-ms-excel",
    "application\/x-excel","application\/x-dos_ms_excel","application\/xls","application\/x-xls"],
    "xsl":["text\/xsl"],"mpeg":["video\/mpeg"],"mov":["video\/quicktime"],"avi":["video\/x-msvideo",
    "video\/msvideo","video\/avi","application\/x-troff-msvideo"],"movie":["video\/x-sgi-movie"],
    "log":["text\/x-log"],"txt":["text\/plain"],"css":["text\/css"],"html":["text\/html"],
    "wav":["audio\/x-wav","audio\/wave","audio\/wav"],"xhtml":["application\/xhtml+xml"],
    "tar":["application\/x-tar"],"tgz":["application\/x-gzip-compressed"],"psd":["application\/x-photoshop",
    "image\/vnd.adobe.photoshop"],"exe":["application\/x-msdownload"],"js":["application\/x-javascript"],
    "mp3":["audio\/mpeg","audio\/mpg","audio\/mpeg3","audio\/mp3"],"rar":["application\/x-rar","application\/rar",
    "application\/x-rar-compressed"],"gzip":["application\/x-gzip"],"hqx":["application\/mac-binhex40",
    "application\/mac-binhex","application\/x-binhex40","application\/x-mac-binhex40"],
    "cpt":["application\/mac-compactpro"],"bin":["application\/macbinary","application\/mac-binary",
    "application\/x-binary","application\/x-macbinary"],"oda":["application\/oda"],
    "ai":["application\/postscript"],"smil":["application\/smil"],"mif":["application\/vnd.mif"],
    "wbxml":["application\/wbxml"],"wmlc":["application\/wmlc"],"dcr":["application\/x-director"],
    "dvi":["application\/x-dvi"],"gtar":["application\/x-gtar"],"php":["application\/x-httpd-php",
    "application\/php","application\/x-php","text\/php","text\/x-php","application\/x-httpd-php-source"],
    "swf":["application\/x-shockwave-flash"],"sit":["application\/x-stuffit"],"z":["application\/x-compress"],
    "mid":["audio\/midi"],"aif":["audio\/x-aiff","audio\/aiff"],"ram":["audio\/x-pn-realaudio"],
    "rpm":["audio\/x-pn-realaudio-plugin"],"ra":["audio\/x-realaudio"],"rv":["video\/vnd.rn-realvideo"],
    "jp2":["image\/jp2","video\/mj2","image\/jpx","image\/jpm"],"tiff":["image\/tiff"],
    "eml":["message\/rfc822"],"pem":["application\/x-x509-user-cert","application\/x-pem-file"],
    "p10":["application\/x-pkcs10","application\/pkcs10"],"p12":["application\/x-pkcs12"],
    "p7a":["application\/x-pkcs7-signature"],"p7c":["application\/pkcs7-mime","application\/x-pkcs7-mime"],"p7r":["application\/x-pkcs7-certreqresp"],"p7s":["application\/pkcs7-signature"],"crt":["application\/x-x509-ca-cert","application\/pkix-cert"],"crl":["application\/pkix-crl","application\/pkcs-crl"],"pgp":["application\/pgp"],"gpg":["application\/gpg-keys"],"rsa":["application\/x-pkcs7"],"ics":["text\/calendar"],"zsh":["text\/x-scriptzsh"],"cdr":["application\/cdr","application\/coreldraw","application\/x-cdr","application\/x-coreldraw","image\/cdr","image\/x-cdr","zz-application\/zz-winassoc-cdr"],"wma":["audio\/x-ms-wma"],"vcf":["text\/x-vcard"],"srt":["text\/srt"],"vtt":["text\/vtt"],"ico":["image\/x-icon","image\/x-ico","image\/vnd.microsoft.icon"],"csv":["text\/x-comma-separated-values","text\/comma-separated-values","application\/vnd.msexcel"],"json":["application\/json","text\/json"]}';
        $all_mimes = json_decode($all_mimes, true);
        foreach ($all_mimes as $key => $value) {
            if (array_search($mime, $value) !== false) return $key;
        }
        return false;
    }
}
