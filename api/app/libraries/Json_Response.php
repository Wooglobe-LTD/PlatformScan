<?php
/**
 * Created by PhpStorm.
 * User: soft
 * Date: 9/26/2017
 * Time: 6:18 PM
 */

class Json_Response
{

    private $result;

    /**
     * JsonResult constructor.
     * @param null $message
     * @param null $data
     */
    public function __construct($message =null, $data=null)
    {
        $this->result = ['success' => true, 'error' => false, 'message' => $message, 'data' => $data];
    }

    public function JSONSuccessResult($message=null, $data=null)
    {
        $result = ['status' => true, 'message' => $message, 'data'=> $data];
        return $result;
    }


    public function JSONErrorResult($message=null, $errors=null, $response_code = 422)
    {
        return [
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ];
    }

}
