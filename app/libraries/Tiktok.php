<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 3/15/2018
 * Time: 11:26 AM
 */

include 'app/libraries/tik/Api.php';
use Sovit\TikTok\Api;
class Tiktok
{

    

    private $api_key;
    private $api;

    // Constructor
    function __construct() {
        $this->api = new Api();
    }

    public function getVideoByUrl($url){
        $result = $this->api->getVideoByUrl($url);
        if(isset($result->items[0])){
            return $result->items[0]->id;
        }
        return false;
        //echo json_encode($result,JSON_PRETTY_PRINT);
    }


}
