<?php

//use Api\App\Utils\JsonResponse;
require APPPATH . '/libraries/API_Controller.php';
/**
 * Created by PhpStorm.
 * User: shehzad.aslam
 * Date: 27/05/2019
 * Time: 9:39 PM
 */

class CategoryController extends API_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Json_Response');
        $this->load->model('Category');
    }

    /**
     * Listing of categories and subcategories
     */
    public function index()
    {


        $categories = Category::paginate(10);

        if ($categories) {
            $this->api_return(
                $this->json_response->JSONSuccessResult('Records found', $categories),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('No record found'),
                422
            );
        }
    }


    public function videos($params = '')
    {
        $category = Category::find($params);
        $category->load('videos');

        if ($category) {
            $this->api_return(
                $this->json_response->JSONSuccessResult('Records found', $category),
                200
            );
        } else {
            $this->api_return(
                $this->json_response->JSONErrorResult('No record found', $category),
                422
            );
        }
    }
}
