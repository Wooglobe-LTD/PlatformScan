<?php use Carbon\Carbon;

defined('BASEPATH') OR exit ('No direct script access allowed');

require APPPATH . '/libraries/API_Controller.php';

class UserApi extends API_Controller
{
    /**
     * UserApi constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // Load Authorization Library or Load in autoload config file
        $this->load->library('Authorization_Token');
        $this->load->library('form_validation');
        $this->load->library('Json_Response');
        $this->load->model('User');
        $this->load->model('Video');
        $this->load->model('UserInterest');


        /*$is_valid=$this->authorization_token->validateToken();

        if ($is_valid['status'] != 1){
            print_r($is_valid);
            exit;
        }*/


    }

    /**
     * Simple api for testing of api
     */
    public function simple_api()
    {
        header("Access-Control-Allow-Origin: *");

        // API Configuration
        $this->_apiConfig([
            'methods' => ['GET'] // Request method GET only
        ]);
    }

    /**
     * Api limit
     */
    public function api_limit()
    {
        /**
         * API Limit
         * ----------------------------------
         * @param: {int} API limit Number
         * @param: {string} API limit Type (IP)
         * @param: {int} API limit Time [minute]
         */

        $this->_APIConfig([
            // number limit, type limit, time limit (last minute)
            'limit' => [10, 'ip', 5]
        ]);
    }

    /**
     * Api key without database
     */
    public function api_key()
    {
        /**
         * Use API Key without Database
         * ---------------------------------------------------------
         * @param: {string} Types
         * @param: {string} API Key
         */

        $this->_APIConfig([
            'methods' => ['POST'],
            //// Key without table
            // 'key' => ['header', '123456'],

            //// Key with table
            // 'key' => ['header'],
            'key' => ['header', $this->key()],
        ]);
    }

    /**
     * @return int
     */
    public function key()
    {
        return 1452;
    }

    /*
     * get email template
     * */
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
    /**
     * Register method for google and fb users
     */
    public function socialUserRegister()
    {
        $validationRules = [
            [
                'field' => 'social_account_id',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'ID field is required',
                    'min_length' => 'Minimum ID length is 3 characters'
                ],
            ]

        ];

        $formData = json_decode($this->input->raw_input_stream, true);
        $formData['verify_token'] = random_string('alnum', 32);
        $formData['token_expiry_time'] = Carbon::now()->format('Y-m-d H:i:s');
        $formData['status'] = 1;
        $this->form_validation->set_data($formData);
        $this->form_validation->set_rules($validationRules);


        if ($this->form_validation->run() == FALSE) {
            $this->api_return(
                $this->json_response->JSONErrorResult('Something bad happened', $this->form_validation->error_array()), 200
            );
        } else {
//echo "form data::".$formData['social_account_id'];
            $is_user = User::where('social_account_id',$formData['social_account_id'])->get();
/*print_r($is_user);exit;*/
            if (!empty($is_user)) {

                $user = User::create($formData);


                if ($user) {
                    $this->api_return(
                        $this->json_response->JSONSuccessResult('Registered successfully', $user),
                        200
                    );
                } else {
                    $this->api_return(
                        $this->json_response->JSONErrorResult('No record found'),
                        200
                    );
                }
            } else {
                $this->api_return(
                    $this->json_response->JSONErrorResult('ID already exists'),
                    200
                );
            }


        }
    }

    /**
     * Register method
     */
    public function register()
    {
        $validationRules = [
            [
                'field' => 'email',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Email field is required',
                    'min_length' => 'Minimum Username length is 3 characters'
                ],
            ],
            [
                'field' => 'password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'You must provide a Password.',
                    'min_length' => 'Minimum Password length is 6 characters',
                ],
            ],
            [
                'field' => 'username',
                'rules' => 'min_length[3]',
                'errors' => [
                    'min_length' => 'Minimum Password length is 6 characters',
                ],
            ],
        ];

        $formData = json_decode($this->input->raw_input_stream, true);
        $formData['verify_token'] = random_string('alnum', 32);
        $formData['token_expiry_time'] = Carbon::now()->addHour()->timezone('Asia/Karachi')->format('Y-m-d H:i:s');
        $this->form_validation->set_data($formData);
        $this->form_validation->set_rules($validationRules);


        if ($this->form_validation->run() == FALSE) {
            $this->api_return(
                $this->json_response->JSONErrorResult('Something bad happened', $this->form_validation->error_array()), 200
            );
        } else {

            $is_user = User::whereEmail($formData['email'])->first();

            if (!$is_user) {

                $user = User::create($formData);
                $emailTemplate = $this->getTemplateByShortCode("email_verification");
                $message = $emailTemplate->message;
                $message = str_replace("{USERS.FULL_NAME}",$user->username,$message);

                $verification_link = base_url() . "email-verification/?token=" . $formData['verify_token'];

                $message=str_replace("VERIFICATION_URL",$verification_link,$message);

                $status=$this->email($user->email, $user->full_name, 'norelpty@viralgreats.com', 'WooGlobe', 'Email Verification', $message);

                if ($user) {
                    $this->api_return(
                        $this->json_response->JSONSuccessResult('Registered successfully', $user),
                        200
                    );
                } else {
                    $this->api_return(
                        $this->json_response->JSONErrorResult('No record found'),
                        200
                    );
                }
            } else {
                $this->api_return(
                    $this->json_response->JSONErrorResult('Email already exists'),
                    200
                );
            }


        }
    }

    /*
     * Email verification
     */
    public function emailVerification()
    {
        $emailToken = $this->input->get('token', TRUE);
        $storedToken = User::where('verify_token', $emailToken)->first();
        if (isset($storedToken) && $storedToken->count() > 0) {
            $storedTokenExpirationTime = Carbon::parse($storedToken->token_expiry_time)->timezone('Asia/Karachi');
            $now = Carbon::now()->timezone('Asia/Karachi');
            /*$difference=$now->diff($storedTokenExpirationTime)->format("%H:%I:%S");*/


            if ($now < $storedTokenExpirationTime) {

                $storedToken->update([
                    'status'=>1
                ]);
               /*echo " token not expired";
                echo "token verified";*/
                $data['message']="Thanks for verification. Now you can login";
                $this->load->view('email_verification_message',$data);
            } else {
//                echo "token expired";
                $RefreshVerificationLink = base_url() . "email-verification-token-refresh/?token=" .$storedToken->verify_token;
                $link = anchor($RefreshVerificationLink, "Refresh Link");

                $data['message']="Token Expired! you can refresh the token again.".$link;
                $this->load->view('email_verification_message',$data);
            }

        } else {
//            echo "token not verified";
            $data['message']="Token not verified";
            $this->load->view('email_verification_message',$data);
        }

    }

    public function emailVerificationTokenRefresh(){
        $emailToken = $this->input->get('token', TRUE);
        $storedTokenObj = User::where('verify_token', $emailToken)->first();

        if (isset($storedTokenObj) && $storedTokenObj->count() > 0){

            $verify_token = random_string('alnum', 32);;
            $token_expiry_time= Carbon::now()->addHour()->format('Y-m-d H:i:s');
            $storedTokenObj->update([
                'verify_token'=>$verify_token,
                'token_expiry_time'=>$token_expiry_time
            ]);

            $emailTemplate = $this->getTemplateByShortCode("email_verification");
            if ($emailTemplate){


            $message = $emailTemplate->message;
            $message = str_replace("{USERS.FULL_NAME}",$storedTokenObj->username,$message);

            $verification_link = base_url() . "email-verification/?token=" . $verify_token;

            $message=str_replace("VERIFICATION_URL",$verification_link,$message);

            $this->email($storedTokenObj->email, $storedTokenObj->full_name, 'norelpty@viralgreats.com', 'WooGlobe', 'Email Verification', $message);
                $data['message']="Token has been sent to your email.";
                $this->load->view('email_verification_message',$data);
            }

        }
        else{
            $data['message']="Token not exists";
            $this->load->view('email_verification_message',$data);
        }
    }
    public function resentEmailforVerification(){
        $validationRules = [
            [
                'field' => 'email',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Email field is required',
                    'min_length' => 'Minimum Username length is 3 characters'
                ],
            ],

        ];

        $formData = json_decode($this->input->raw_input_stream, true);
        $formData['verify_token'] = random_string('alnum', 32);;
        $formData['token_expiry_time'] = Carbon::now()->addHour()->format('Y-m-d H:i:s');
        $this->form_validation->set_data($formData);
        $this->form_validation->set_rules($validationRules);


        if ($this->form_validation->run() == FALSE) {
            $this->api_return(
                $this->json_response->JSONErrorResult('Something bad happened', $this->form_validation->error_array()), 200
            );
        } else {

            $is_user = User::whereEmail($formData['email'])->first();

            if ($is_user) {


                $emailTemplate = $this->getTemplateByShortCode("email_verification");
                $message = $emailTemplate->message;
                $message = str_replace("{USERS.FULL_NAME}",$is_user->username,$message);

                $verification_link = base_url() . "email-verification/?token=" . $formData['verify_token'];

                $message=str_replace("{VERIFICATION_URL}",$verification_link,$message);

                $is_email_sent=$this->email($is_user->email, $is_user->full_name, 'norelpty@viralgreats.com', 'WooGlobe', 'Email Verification', $message);

                if ($is_email_sent) {
                    $this->api_return(
                        $this->json_response->JSONSuccessResult('Email Sent', $is_user),
                        200
                    );
                } else {
                    $this->api_return(
                        $this->json_response->JSONErrorResult('Email Sent'),
                        200
                    );
                }
            } else {
                $this->api_return(
                    $this->json_response->JSONErrorResult('Email not found'),
                    200
                );
            }


        }
    }

    /**
     * Social Login method
     */
    public function socialLogin()
    {
        $validationRules = [
            [
                'field' => 'social_account_id',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Please Provide Social Account ID',
                    'min_length' => 'Minimum Username length is 3 characters'
                ],
            ]
        ];


        header("Access-Control-Allow-Origin: *");

        // API Configuration
        $this->_apiConfig([
            'methods' => ['POST'],
        ]);

        $formData = json_decode($this->input->raw_input_stream, true);

        $this->form_validation->set_data($formData);
        $this->form_validation->set_rules($validationRules);


        if ($this->form_validation->run() == FALSE) {
            $this->api_return(
                $this->json_response->JSONErrorResult('Something bad happened', $this->form_validation->error_array()), 200
            );
        } else {
            //$formData['password'] = sha1($formData['password']);

            $user = User::where('social_account_id',$formData['social_account_id'])->first();



            // generates a token


            if (is_null($user)) {
                $this->api_return(
                    $this->json_response->JSONErrorResult('Invalid Social Account ID'), 200
                );

            } else {
                if ($user->status === 1 && $user->deleted === 0){
                    // you user authentication code will go here, you can compare the user with the database or whatever
                    $payload = [
                        'id' => $user->id,
                        'other' => $user
                    ];

                    // generates a token
                    $refreshed_token_array = $this->authorization_token->generateToken($payload);
                    $token = $refreshed_token_array['token'];
                    $token_expire_time = $refreshed_token_array['token_expire_time'];
                    // Add User Publish count
                    $publish_videos = Video::where('user_id', '=', $user->id)->get();
                    $publish_count = $publish_videos->count();
                    $publish_video_details = array();
                    foreach ($publish_videos as $publish_video) {
                        $publish_video_details[] = json_decode(json_encode($publish_video), true);

                    }


                    // Add User Interest data
                    $user_interest = UserInterest::where('user_id', '=', $user->id)->get();
                    $user_interest_category_selected = '';
                    if ($user_interest) {
                        foreach ($user_interest as $user_interest_category) {

                            $user_interest_category_selected = $user_interest_category->category_id;

                        }
                    }
                    // return data
                    $this->api_return(
                        $this->json_response->JSONSuccessResult('Login success', compact('token', 'user', 'publish_count', 'user_interest_category_selected', 'publish_video_details', 'token_expire_time')),
                        200);
                }
               /* elseif($user->deleted === 0){
                    $this->api_return(
                        $this->json_response->JSONErrorResult('Email Not Verified'), 200
                    );
                }*/
                elseif($user->deleted != 0){
                    $this->api_return(
                        $this->json_response->JSONErrorResult('User Blocked'), 200
                    );
                }


            }
        }

    }


    /**
     * Login method
     */
    public function login()
    {
        $validationRules = [
            [
                'field' => 'email',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'We need both email and password',
                    'min_length' => 'Minimum Username length is 3 characters'
                ],
            ],
            [
                'field' => 'password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'You must provide a Password.',
                    'min_length' => 'Minimum Password length is 6 characters',
                ],
            ],
        ];


        header("Access-Control-Allow-Origin: *");

        // API Configuration
        $this->_apiConfig([
            'methods' => ['POST'],
        ]);

        $formData = json_decode($this->input->raw_input_stream, true);

        $this->form_validation->set_data($formData);
        $this->form_validation->set_rules($validationRules);


        if ($this->form_validation->run() == FALSE) {
            $this->api_return(
                $this->json_response->JSONErrorResult('Something bad happened', $this->form_validation->error_array()), 200
            );
        } else {
            $formData['password'] = sha1($formData['password']);

            $user = User::whereEmail($formData['email'])->wherePassword($formData['password'])->first();


            // generates a token


            if (is_null($user)) {
                $this->api_return(
                    $this->json_response->JSONErrorResult('Invalid Email or Password'), 200
                );

            } else {
                if ($user->status === 1 && $user->deleted === 0){
                    // you user authentication code will go here, you can compare the user with the database or whatever
                    $payload = [
                        'id' => $user->id,
                        'other' => $user
                    ];

                    // generates a token
                    $refreshed_token_array = $this->authorization_token->generateToken($payload);
                    $token = $refreshed_token_array['token'];
                    $token_expire_time = $refreshed_token_array['token_expire_time'];
                    // Add User Publish count
                    $publish_videos = Video::where('user_id', '=', $user->id)->get();
                    $publish_count = $publish_videos->count();
                    $publish_video_details = array();
                    foreach ($publish_videos as $publish_video) {
                        $publish_video_details[] = json_decode(json_encode($publish_video), true);

                    }


                    // Add User Interest data
                    $user_interest = UserInterest::where('user_id', '=', $user->id)->get();
                    $user_interest_category_selected = '';
                    if ($user_interest) {
                        foreach ($user_interest as $user_interest_category) {

                            $user_interest_category_selected = $user_interest_category->category_id;

                        }
                    }
                    // return data
                    $this->api_return(
                        $this->json_response->JSONSuccessResult('Login success', compact('token', 'user', 'publish_count', 'user_interest_category_selected', 'publish_video_details', 'token_expire_time')),
                        200);
                }
                elseif($user->deleted === 0){
                    $this->api_return(
                        $this->json_response->JSONErrorResult('Email Not Verified'), 200
                    );
                }
                elseif($user->deleted != 0){
                    $this->api_return(
                        $this->json_response->JSONErrorResult('User Blocked'), 200
                    );
                }


            }
        }

    }

    /**
     * API token refresh
     */
    public function tokenRefresh()
    {
        $validationRules = [
            [
                'field' => 'email',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'We need both email and password',
                    'min_length' => 'Minimum Username length is 3 characters'
                ],
            ]
        ];
        header("Access-Control-Allow-Origin: *");

        // API Configuration
        $this->_apiConfig([
            'methods' => ['POST'],
        ]);


        $formData = json_decode($this->input->raw_input_stream, true);


        $this->form_validation->set_data($formData);
        $this->form_validation->set_rules($validationRules);

        if ($this->form_validation->run() == FALSE) {
            $this->api_return(
                $this->json_response->JSONErrorResult('Something bad happened', $this->form_validation->error_array()), 200
            );
        } else {


            $user = User::whereEmail($formData['email'])->first();

            // generates a token


            if (is_null($user)) {
                $this->api_return(
                    $this->json_response->JSONErrorResult('Invalid Email'), 200
                );

            } else {
                // you user authentication code will go here, you can compare the user with the database or whatever
                $payload = [
                    'id' => $user->id,
                    'other' => $user
                ];

                // generates a token
                $refreshed_token_array = $this->authorization_token->generateToken($payload);
                $token = $refreshed_token_array['token'];
                $token_expire_time = $refreshed_token_array['token_expire_time'];
                // return data
                $this->api_return(
                    $this->json_response->JSONSuccessResult('Token Refreshed', compact('token', 'token_expire_time')),
                    200);
            }
        }
    }

    /**
     * Change password api
     * URL
     */

    public function changePassword()
    {
        $validationRules = [
            [
                'field' => 'email',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Email field is required',
                    'min_length' => 'Minimum Username length is 3 characters'
                ],
            ],
            [
                'field' => 'new_password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'You must provide a new Password.',
                    'min_length' => 'Minimum Password length is 6 characters',
                ],
            ],
            [
                'field' => 'old_password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'You must provide a old Password.',
                    'min_length' => 'Minimum Password length is 6 characters',
                ],
            ],
        ];

        header("Access-Control-Allow-Origin: *");

        // API Configuration
        $this->_apiConfig([
            'methods' => ['POST'],
        ]);

        $formData = json_decode($this->input->raw_input_stream, true);

        $this->form_validation->set_data($formData);
        $this->form_validation->set_rules($validationRules);


        if ($this->form_validation->run() == FALSE) {
            $this->api_return(
                $this->json_response->JSONErrorResult('Something bad happened', $this->form_validation->error_array()), 200
            );
        } else {

            $formData['password'] = $formData['new_password'];
            $formData['old_password'] = sha1($formData['old_password']);

            $user = User::whereEmail($formData['email'])->wherePassword($formData['old_password'])->first();


            if (is_null($user)) {
                $this->api_return(
                    $this->json_response->JSONErrorResult('Invalid Email or Old Password'), 200
                );

            } else {

                $user->update($formData);

                // return data
                $this->api_return(
                    $this->json_response->JSONSuccessResult('Password Changed', compact('user')), 200);
            }
        }

    }

    /**
     * view User data
     *
     * @return Response|void
     * @link [api/user/view]
     * @method POST
     */
    public function view()
    {
        header("Access-Control-Allow-Origin: *");

        // API Configuration [Return Array: User Token Data]
        $user_data = $this->_apiConfig([
            'methods' => ['POST'],
            'requireAuthorization' => true,
        ]);

        $this->api_return($this->json_response->JSONSuccessResult('Login success', $user_data['token_data']), 200);
    }


    public function resetPassword()
    {
        $validationRules = [
            [
                'field' => 'email',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => 'Email field is required'
                ],
            ]
        ];

        $formData = json_decode($this->input->raw_input_stream, true);

        $this->form_validation->set_data($formData);
        $this->form_validation->set_rules($validationRules);


        if ($this->form_validation->run() == FALSE) {
            $this->api_return(
                $this->json_response->JSONErrorResult('Validation Error', $this->form_validation->error_array()), 200
            );
        } else {

            $user = User::whereEmail($formData['email'])->first();

            if ($user) {

                $password = random_string('alnum', 8);
                $updated = $user->update(['password' => $password]);
                $emailTemplate = $this->getTemplateByShortCode("forgot_password");
                $message = $emailTemplate->message;
                $message = str_replace("{USERS.FULL_NAME}",$user->username,$message);
                $message = str_replace("{USERS.PASSWORD}",$password,$message);
                //$message = 'Dear '.$user->full_name.'<br> Your new password is <b>'.$password.'</b> ';

                $this->email($user->email, $user->full_name, 'norelpty@viralgreats.com', 'WooGlobe', $emailTemplate->subject, $message);

                if ($updated) {
                    $this->api_return(
                        $this->json_response->JSONSuccessResult('Password reset successfully! Please check your mail inbox.', $updated),
                        200
                    );
                } else {
                    $this->api_return(
                        $this->json_response->JSONErrorResult('Password not updated'),
                        200
                    );
                }
            } else {
                $this->api_return(
                    $this->json_response->JSONErrorResult('Email not found'),
                    200
                );
            }


        }
    }
}
