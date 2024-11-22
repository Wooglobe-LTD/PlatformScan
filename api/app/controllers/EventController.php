<?php

//use Api\App\Utils\JsonResponse;
require APPPATH . '/libraries/API_Controller.php';

/**
 * Created by PhpStorm.
 * User: shehzad.aslam
 * Date: 27/05/2019
 * Time: 9:39 PM
 */
class EventController extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Json_Response');
        $this->load->model('Event');
        $this->load->model('Category');
    }

    /**
     * Listing of categories and subcategories
     */
    public function index()
    {


        $events = Event::with('category')->paginate(10);

        if ($events) {
            $this->api_return(
                $this->json_response->JSONSuccessResult('Events Records found', $events),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('No record found'),
                422
            );
        }
    }


}
