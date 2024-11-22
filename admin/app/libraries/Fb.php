<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 3/15/2018
 * Time: 11:26 AM
 */

class Fb
{

    

    private $fb;
    private $token;

    // Constructor
    function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->database();
        $this->fb = new Facebook\Facebook([
            'app_id' => $this->ci->config->config['fb_id'], // Replace {app-id} with your app id
            'app_secret' => $this->ci->config->config['fb_secret'],
            'default_graph_version' => 'v3.1',
        ]);
        $this->token = $this->ci->db->query('SELECT token FROM  fb_token WHERE id = 1')->row()->token;


    }
    function getProtectedValue($obj,$name) {
        $array = (array)$obj;
        $prefix = chr(0).'*'.chr(0);
        return $array[$prefix.$name];
    }

    function getPages()
    {


        try {
            $response = $this->fb->get('/me/accounts', $this->token);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        return $response->getGraphEdge()->asArray();
        // Print the labels in the user's account.
    }

    function uplaodVideo($data, $path,$thumbnail = null) {
       /* $data = [
            'title' => 'My Foo Video',
            'description' => 'This video is full of foo and bar action.',
        ];*/

        //$path = '/path/to/foo_bar.mp4';
        if(!is_null($path)){
            try{
                $data['source'] = $this->fb->videoToUpload($path);
            }catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error video: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        }

        if(!is_null($thumbnail)){

            try{
                $thumbnail = New \Facebook\FileUpload\FacebookFile($thumbnail);
                $data['thumb'] = $thumbnail;
            }catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error thumb: ' . $e->getMessage();
                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        }

        try {
            $response_accounts = $this->fb->get('/me/accounts', $this->token);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error accounts: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        $response_accounts = $response_accounts->getGraphEdge()->asArray();

        if(count($response_accounts) > 0){
            try {
                //$response = $this->fb->uploadVideo('140953096488193/videos', $path, $data, $response_accounts[0]['access_token']);
                //Get the tags names array
                $tag_names = $data['content_tags'];
                $tag_id= array();
                $counter= 1;
                if($tag_names){
                    foreach ($tag_names as $tag_name){
                        if($tag_name){
                            //Send tags names to facebook app to get related results of tags
                            $tag_response=$this->fb->get('search?type=adinterest&q='.$tag_name, $response_accounts[0]['access_token']);
                            $tags_jason=$this->getProtectedValue($tag_response, 'body');
                            $tag_arrs=json_decode($tags_jason, true);
                            foreach ($tag_arrs['data'] as $tag_arr){
                                //Get names of related tags
                                if(isset($tag_arr['name'])){
                                    //Check for same result
                                    if($tag_arr['name'] == $tag_name ){
                                        if(isset($tag_arr['id'])){
                                            array_push($tag_id,$tag_arr['id']);
                                        }
                                        break;
                                    }
                                }
                            }
                            // check if the counter is less then the tag id we get now in the loop
                            if(count($tag_id) < $counter){
                                //if No same result then add first element of the related tag id
                                if(isset($tag_arrs['data'][0]['id'])){
                                    array_push($tag_id,$tag_arrs['data'][0]['id']);
                                }
                            }
                            $counter++;
                        }
                    }
                }

                $data['content_tags']=$tag_id;
               /* echo '<pre>';
                print_r($data);
                exit;*/
                $response = $this->fb->post('140953096488193/videos', $data, $response_accounts[0]['access_token']);// original
                //$response = $this->fb->post('102778334413121/videos', $data, $response_accounts[0]['access_token']);// testing
                $response = $response->getGraphNode();
                /*echo '<pre>';
                print_r($response);
                echo '<pre>';
                print_r($response);
                exit;*/
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error post: ' . $e->getMessage();

                exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            //$graphNode = $response->getGraphEdge()->asArray();


            return $response;
        }else{
            return false;
        }

    }

    public function publishVideo($posting_data){
        /// publish_time time in timestamp;
        $data = array(
            'message'=>$posting_data['message'],
            'scheduled_publish_time'=>$posting_data['publish_time'],
            'published'=>false,
            );

        try {
            $response = $this->fb->post('/'.$posting_data['page_id'].'/feed',$data, $this->token);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        return $response;

    }

    public function getVideoById($id){
        try {
            $response = $this->fb->get('/'.$id, $this->token);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            return true;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        return false;
    }




}

