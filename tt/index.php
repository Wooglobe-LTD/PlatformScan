<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//header("Content-Type: application/json");
include __DIR__."/vendor/autoload.php";
$api = new \Sovit\TikTok\Api();

try {
    //$result = file_get_contents("https://www.tiktok.com/@zachking/video/6932846417820126470?is_copy_url=0&is_from_webapp=v1&sender_device=pc&sender_web_id=6976885386518300165");
    $result = $api->getVideoByUrl("https://www.tiktok.com/@zachking/video/6932846417820126470?is_copy_url=0&is_from_webapp=v1&sender_device=pc&sender_web_id=6976885386518300165");
    echo '<pre>';
    print_r($result);
    exit;
}
catch (exception $e) {
    echo '<pre>';
    print_r($e);
    exit;
}

//echo json_encode($result,JSON_PRETTY_PRINT);
