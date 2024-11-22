<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 3/15/2018
 * Time: 11:26 AM
 */

class Youtube
{

    

    private $client;
    private $token;

    // Constructor
    function __construct() {
        define('SCOPES', implode(' ', array(
                Google\Service\Gmail::MAIL_GOOGLE_COM,
                Google\Service\Drive::DRIVE,
                Google\Service\YouTube::YOUTUBE,
                Google\Service\YouTube::YOUTUBE_READONLY,
                Google\Service\YouTube::YOUTUBE_UPLOAD,
                Google\Service\YouTube::YOUTUBEPARTNER,
                Google\Service\YouTube::YOUTUBEPARTNER_CHANNEL_AUDIT,

                Google_Service_Sheets::SPREADSHEETS,
                Google_Service_Sheets::SPREADSHEETS_READONLY,
                Google_Service_Sheets::DRIVE_READONLY,
                Google_Service_Sheets::DRIVE_FILE,
                Google_Service_Sheets::DRIVE,
            )
        ));
        $this->client = new Google_Client();
        $this->client->setApplicationName(APPLICATION_NAME_TUBE);
        $this->client->setScopes(SCOPES);
        $this->client->setAuthConfig(YOUTUBE_CLIENT_SECRET_PATH);
        $this->client->setAccessType('offline');
        $this->client->setApprovalPrompt('force');
        //$this->client->setIncludeGrantedScopes(true);   // incremental auth
        //$this->client->setRedirectUri('https://' . $_SERVER['HTTP_HOST'] .'/wgplatform/admin/index.php' . '/cb_gmail');
        //$auth_url = $this->client->createAuthUrl();
        //header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
        //exit();

        // $this->token = $this->client->getAccessToken();
        // var_dump($this->token);


        $credentialsPath = YOUTUBE_CREDENTIALS_PATH;

        if(!file_exists($credentialsPath)){
            redirect('cb_gmail');
        }

        $accessToken = json_decode(file_get_contents($credentialsPath), true);
        // var_dump($accessToken);
        $this->token = $accessToken['access_token'];

        $this->client->setAccessToken($accessToken);
        
        if ($this->client->isAccessTokenExpired()) {
            $refreshTokenSaved = $this->client->getRefreshToken();
            //echo $refreshTokenSaved;
            $this->client->fetchAccessTokenWithRefreshToken($refreshTokenSaved);
            //$client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($this->client->getAccessToken()));
        }
    }

    function initChannelCredentials($client_secret_path, $cred_file_path, $redirect_uri, $client_code){
        // if (!is_dir($auth_conf_path))
        //   throw new InvalidArgumentException(sprintf(
        //     'Auth config path "%s" does not exist', $auth_conf_path));
        
        define('SCOPES', implode(' ', array(
                    // Google\Service\Gmail::MAIL_GOOGLE_COM,
                    // Google\Service\Drive::DRIVE,
                    Google\Service\YouTube::YOUTUBE,
                    Google\Service\YouTube::YOUTUBE_READONLY,
                    Google\Service\YouTube::YOUTUBE_UPLOAD,
                    Google\Service\YouTube::YOUTUBEPARTNER,
                    Google\Service\YouTube::YOUTUBEPARTNER_CHANNEL_AUDIT,
                    Google\Service\YouTube::YOUTUBE_FORCE_SSL
                )
            )
        );
        $client = new Google_Client();
        $client->setAuthConfigFile($client_secret_path);
        $client->setRedirectUri($redirect_uri);
        $client->setScopes(SCOPES);
      
        $cred = $client->fetchAccessTokenWithAuthCode($client_code);
      
        $youtube = new Google_Service_YouTube($client);
        $response = $youtube->channels->listChannels('id', array(
          'mine' => 'true'
        ));
      
        $channel_id = $response[0]['id'];
        $cred_file = $cred_file_path;
        if (file_exists($cred_file))
          throw new InvalidArgumentException(sprintf(
            'Credentials file for channel "%s" already exists', $channel_id));
      
        file_put_contents($cred_file, json_encode($cred));
      
        return $channel_id;
    }

    function makeChannelClient($client_secret_path, $cred_file_path, $channel_id)
    {
        $cred_file = $cred_file_path;
    
        if (!$cred = json_decode(file_get_contents($cred_file), true))
            return NULL;

        $client = new Google_Client();
        $client->setApplicationName(APPLICATION_NAME_TUBE);
        $client->setAuthConfig($client_secret_path);
        $client->setAccessType('offline');
        $client->setScopes($cred['scope']);
        $client->setAccessToken($cred);
        $client->setApprovalPrompt('force');

        $accessToken = json_decode(file_get_contents($cred_file), true);
        $this->token = $accessToken['access_token'];
        $this->client->setAccessToken($accessToken);

        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($cred_file, json_encode($client->getAccessToken()));
        }

        return $client;
    }

    function getThumbnail($video_id = "rWvnZG2z7AU"){
        

        // $client = $this->makeChannelClient(YOUTUBE_MANAGMENT_CHANNEL_CLIENT_SECRET_PATH, YOUTUBE_MANAGMENT_CHANNEL_CREDENTIALS_PATH, 'UCbbtHuBeqqlRB9yNr_Hpc7w');
        // $service = new Google_Service_YouTube($client);

        // $response = $service->videos->listVideos('id,snippet,contentDetails', array(
        //     "id" => $video_id
        // ));

        // foreach($response->items as $video){
            // $video->snippet->thumbnails
        //     var_dump($video);
        // }

    }
    function getChannels()
    {

        $service = new Google_Service_YouTube($this->client);
        try{
            $listResponse = $service->channels->listChannels('snippet', array('mine' => true));
        } catch (Exception $e){
            return [];
        }
        // $listResponse = $service->channels->listChannels('snippet', array('managedByMe' => true));

        $channels = array();
        foreach ($listResponse->getItems() as $channel) {
            $cha = array();
            $cha['id'] = $channel->getId();
            $snippet = $channel->getSnippet();
            $cha['title'] = $snippet->getTitle();

            if ($cha['id'] == 'UCbbtHuBeqqlRB9yNr_Hpc7w'){
                $cha['callback'] = 'cb_mgmt_channel';
            }
            elseif ($cha['id'] == 'UCtfC0pUgtWzJuaecnm6l_ng'){
                $cha['callback'] = 'cb_gmail';
            }
            else {
                $cha['callback'] = '';
            }
            
            $channels[] = $cha;

        }

        return $channels;
        // Print the labels in the user's account.
    }

    public function getCategories(){
        $service = new Google_Service_YouTube($this->client);
        //var_dump($service);
        $categoriesResult = $this->videoCategoriesList($service,'snippet',array('regionCode' => 'US'));
        $categories = array();
        foreach ($categoriesResult->getItems() as $category) {
            $cat = array();
            $cat['id'] = $category->getId();
            $snippet = $category->getSnippet();
            $cat['title'] = $snippet->getTitle();
            $categories[] = $cat;

        }

        return $categories;

    }

    public function getCategoryById($id){
        $service = new Google_Service_YouTube($this->client);
        $categoriesResult = $this->videoCategoriesList($service,'snippet',array('id'=>$id));
        $categories = array();
        foreach ($categoriesResult->getItems() as $category) {
            $cat = array();
            $cat['id'] = $category->getId();
            $snippet = $category->getSnippet();
            $cat['title'] = $snippet->getTitle();
            $categories[] = $cat;

        }

        return $categories;

    }

    function videoCategoriesList($service, $part, $params) {
        $params = array_filter($params);
        $response = $service->videoCategories->listVideoCategories(
            $part,
            $params
        );

       return $response;
    }
    public function download_from_s3($url){
        set_time_limit(0);
        $file_name = basename($url);
        // $temp_file = tempnam(sys_get_temp_dir(), $file_name);
        if ($file_name){ 
            $temp_file =  sys_get_temp_dir() . DIRECTORY_SEPARATOR . $file_name;

            $fp = fopen($temp_file, 'w+');

            $ch = curl_init(str_replace(" ","%20",$url));


            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FILE, $fp); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 600);
            // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $data = curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            return $temp_file;
        }
        return "";
    }
    public function deleteVideo($video_id, $youtube_id, $channel_id){
        try{
            $client = $this->client;
            if($channel_id == 'UCbbtHuBeqqlRB9yNr_Hpc7w'){ // Management Channel
                $client = $this->makeChannelClient(YOUTUBE_MANAGMENT_CHANNEL_CLIENT_SECRET_PATH, YOUTUBE_MANAGMENT_CHANNEL_CREDENTIALS_PATH, $channel_id);
                if($client == NULL){
                    redirect('cb_mgmt_channel');
                }
            }
                $service = new Google_Service_YouTube($client);
                $service->videos->delete($youtube_id);
                $array['error'] = false;
                $array['msg'] = "Success";

                return $array;
        }
        catch(Exception $e){
            $array['error'] = true;
            $array['msg'] = json_decode($e->getMessage())->error->message;
            return $array;
        }
    }
    public function publishVideo($video_data,$video, $youtube_video_type = "1", $youtube_video_thumb = "", $is_update_video = false, $update_video_id = ""){
        $htmlBody  = '';
        try{
            if(!$is_update_video){
                if($youtube_video_type == "2"){
                    $videoPath = $this->download_from_s3($video_data->portal_url);
                } else {
                    $videoPath = $this->download_from_s3($video_data->s3_url);
                }
                $imagePath = $this->download_from_s3($youtube_video_thumb);

            }

            $client = $this->client;
            if($video->youtube_channel == 'UCbbtHuBeqqlRB9yNr_Hpc7w'){ // Management Channel
                $client = $this->makeChannelClient(YOUTUBE_MANAGMENT_CHANNEL_CLIENT_SECRET_PATH, YOUTUBE_MANAGMENT_CHANNEL_CREDENTIALS_PATH, $video->youtube_channel);
                if($client == NULL){
                    redirect('cb_mgmt_channel');
                }
            }

            $service = new Google_Service_YouTube($client);

            if ($is_update_video){
                $listResponse = $service->videos->listVideos("snippet", array('id' => $update_video_id));

                $videoList = $listResponse['items'];
                if (empty($videoList)) {
                    $array['error'] = true;
                    $array['msg'] = "Video not found on youtube";

                    // $array['id'] = $status['id'];
                    return $array;
                                       
                } else {
                    // Since a unique video id is given, it will only return 1 video.
                    $video_list_video = $videoList[0];
                    $update_snippet = $video_list_video['snippet'];
                    

                    // Construct the Google_Video with the updated tags, hence the snippet
                    $video_obj = new Google_Service_YouTube_Video();
                    $video_obj->setId($update_video_id);

                    $snippet = new Google_Service_YouTube_VideoSnippet();

                    $snippet->setTitle($video->video_title);
                    $snippet->setDescription($video->video_description);
                    $snippet->setTags(explode(',',$video->video_tags));
                    $snippet->setCategoryId($video->youtube_category);
                    
                    $status = new Google_Service_YouTube_VideoStatus();
                    $status->privacyStatus = $video->youtube_publish_status;
                    
                    $video_obj->setSnippet($snippet);
                    $video_obj->setStatus($status);

                    // Create a video update request
                    $updateResponse = $service->videos->update("status,snippet", $video_obj);

                    $array['error'] = false;
                    $array['msg'] = 'Suceess';
                    $array['id'] = $update_video_id;
                    return $array;
                }
            } 

            if($videoPath == NULL || strlen($videoPath) == 0){
                $array['error'] = true;
                $array['msg'] = "Video URL not found";
                return $array;
            }
            $snippet = new Google_Service_YouTube_VideoSnippet();

            $snippet->setTitle($video->video_title);
            $snippet->setDescription($video->video_description);
            $snippet->setTags(explode(',',$video->video_tags));
            $snippet->setCategoryId($video->youtube_category);
            $status = new Google_Service_YouTube_VideoStatus();
            $status->privacyStatus = $video->youtube_publish_status;
            if($video->yt_schedule == 1)
                $status->publishAt = date("c", strtotime('+60 minutes', strtotime($video->yt_schedule_time)));
            $video_obj = new Google_Service_YouTube_Video();
            $video_obj->setSnippet($snippet);
            $video_obj->setStatus($status);
            $chunkSizeBytes = 10 * 1024 * 1024;
            $client->setDefer(true);

            // $queryParams = [
            //     'onBehalfOfContentOwner' => 'sV9Ei2zQ5K5YZOI9hU_Jlw',
            //     'onBehalfOfContentOwnerChannel' => 'UCbbtHuBeqqlRB9yNr_Hpc7w'
            // ];
            
            // exit();

            $insertRequest = $service->videos->insert("status,snippet", $video_obj);

            $media = new Google_Http_MediaFileUpload(
                $client,
                $insertRequest,
                'video/*',
                null,
                true,
                $chunkSizeBytes
            );
            $media->setFileSize(filesize($videoPath));
            $status = false;
            $handle = fopen($videoPath, "rb");
            while (!$status && !feof($handle)) {
                $chunk = fread($handle, $chunkSizeBytes);
                $status = $media->nextChunk($chunk);
            }

            fclose($handle);
            $client->setDefer(false);
			$array['error'] = false;
			$array['msg'] = 'Suceess';
			$array['id'] = $status['id'];
            $videoId = $array['id'];
            if($youtube_video_thumb != ""){
                $client->setDefer(true);
                $setRequest = $service->thumbnails->set($videoId);
                $media = new Google_Http_MediaFileUpload(
                    $client,
                    $setRequest,
                    'image/png',
                    null,
                    true,
                    $chunkSizeBytes
                );
                $media->setFileSize(filesize($imagePath));
                $status = false;
                $handle = fopen($imagePath, "rb");
                while (!$status && !feof($handle)) {

                    $chunk = fread($handle, $chunkSizeBytes);
                    $status = $media->nextChunk($chunk);
                }

                fclose($handle);
                $client->setDefer(false);
                $array['thumb'] = $status;
            }
            
            return $array;
        } catch (Google_Service_Exception $e) {
            // var_dump($e);die();
        	$array['error'] = true;
			$array['msg'] = json_decode($e->getMessage())->error->message;

            return $array;
        } catch (Google_Exception $e) {
            // var_dump($e);die();

			$array['error'] = true;
			$array['msg'] = json_decode($e->getMessage())->error->message;

            return $array;
        }
         catch (Exception $e){
            // var_dump($e);die();
            $array['error'] = true;
			$array['msg'] = json_decode($e->getMessage())->error->message;

            return $array;
         }

    }


//$accessToken1 = $accessToken['access_token'];
//$gmail_email = 'usman.ali.sarwar.wg@gmail.com';
    /*$debug = false;
    $protocol = new \Anod\Gmail\Imap($debug);
    $gmail = new \Anod\Gmail\Gmail($protocol);
    $gmail->setId("WooGlobe","0.1","Wooglobe","usman.ali.sarwar.wg@gmail.com");

    $gmail->connect();
    $gmail->authenticate($gmail_email, $accessToken1);
    $gmail->sendId();
    $gmail->selectAllMail();
    $messages = new \Zend\Mail\Storage\Imap($protocol);
    echo '<pre>';
    print_r($messages->countMessages());
    echo '<pre>';
    print_r($messages->getMessage(1)->getHeaders());*/
    /*set_include_path('./app/third_party/gmail/');
    require_once 'Zend/Mail/Protocol/Imap.php';
    require_once 'Zend/Mail/Storage/Imap.php';
    $imap = $this->tryImapLogin($gmail_email,$accessToken1,self::MAILBOX_SENT);
    $messages = $this->showInbox($imap);
    echo '<pre>';
    print_r($messages->countMessages());
    for($i = 1; $i <=$messages->countMessages(); $i++ ){
        echo '<pre>';
        print_r($messages->getMessage($i));
    }

    exit;*/

    public function getVideos($ids){
        $service = new Google_Service_YouTube($this->client);
        $params = array('id' => $ids);
        $videos = $service->videos->listVideos(
            'snippet,contentDetails,statistics,status',
            $params
        );

        $categories_array = $this->getCategories();
		$categories = array();
		foreach ($categories_array as $cat){
			$categories[$cat['id']] = $cat['title'];
		}

        $output = array();

            foreach ($videos['items'] as $video){
				/*echo '<pre>';
				print_r($video['status']->privacyStatus);
				echo '<pre>';
				print_r($video['snippet']['categoryId']);
				exit;*/
                $string1 = array(
                    'videoId' => $video['id'],
                    'description' => $video['snippet']['description'],
                    'snippetTitle' => $video['snippet']['title'],
                    'thumnail' => $video['snippet']['thumbnails']['high']['url'],
                    'channelTitle' => $video['snippet']['channelTitle'],
                    'categoryId' => $video['snippet']['categoryId'],
                    //'categoryTitle' => $categories[$video['snippet']['categoryId']],
                    'status' => $video['status']->privacyStatus,
                );
                if(is_array($video['snippet']['tags'])){
                    $string1['tags'] = implode(',',$video['snippet']['tags']);
                }else{
                    $string1['tags'] = $video['snippet']['tags'];
                }
				if(isset($categories[$video['snippet']['categoryId']])){
					$string1['categoryTitle'] = $categories[$video['snippet']['categoryId']];
				}else{
					$string1['categoryTitle'] = '';
				}
                /*if($string1['status'] == 'public'){
                    $output[] = $string1;
                }*/
                $output[] = $string1;
            }



        return $output;
    }

    public function addVideosToSheet(){
        $service = new Google_Service_Sheets($this->client);

// Prints the names and majors of students in a sample spreadsheet:
// https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
        $spreadsheetId = '1zkhvScq518q0kugxa26Xigy7_oqbqycEwAprkyJugqk';
        $range = 'Class Data!A2:E';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
        echo '<pre>';
        print_r($values);
        exit;
    }

}
