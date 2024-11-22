<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/**
 * Authentication Routes
 */
$route['register'] = 'userApi/register';
$route['social-register'] = 'userApi/socialUserRegister';
$route['email-verification'] = 'userApi/emailVerification';
$route['email-verification-token-refresh'] = 'userApi/emailVerificationTokenRefresh';
$route['email-verification/refresh'] = 'userApi/resentEmailforVerification';
$route['login'] = 'userApi/login';
$route['social-login'] = 'userApi/socialLogin';

$route['user/view'] = 'userApi/view';
$route['change-password'] = 'userApi/changePassword';
$route['reset-password'] = 'userApi/resetPassword';


$route['users'] = 'userController';
$route['interest-create'] = 'userController/submitInterest';
$route['user/update'] = 'userController/update';

/**
*API token refresh
 */
$route['refresh-token'] = 'userApi/tokenRefresh';

/**
 * Invite Users
 */
$route['invite-user'] = 'userController/inviteUser';
/*get countries*/
$route['countries'] = 'userController/getCountries';


/**
 * Videos routes
 */
$route['videos'] = 'videoController';
$route['videos-trending'] = 'videoController/trending';
$route['videos-latest'] = 'videoController/latest';
$route['upload-video'] = 'videoController/uploadLead';



/**
 * Categories routes
 */
$route['categories'] = 'categoryController';
$route['categories/videos/(:any)'] = 'categoryController/videos/$1';


/**
 * Video Comments
 */
$route['video-comments/create'] = 'videoCommentController/store';
$route['video-comments/delete'] = 'videoCommentController/delete';
$route['video-comments/update'] = 'videoCommentController/update';

/*Video Expression*/
$route['video-expression/fetch'] = 'VideoExpressionController/fetchExpressions';
$route['video-expression/toggle'] = 'VideoExpressionController/store';

/**
 * Video Likes
 */
$route['video-like/create'] = 'videoLikeController/store';
$route['video-like/view'] = 'videoLikeViewController/likefetch';


/**
 * Video DisLikes
 */
$route['video-dislike/create'] = 'videoDislikeController/store';
$route['video-dislike/view'] = 'videoDislikeViewController/dislikefetch';

/**
 * Video Views
 */
$route['video-view/create'] = 'videoViewController/store';


/**
 * Define API Routes
 */
$route['simple'] = 'userApi/simple_api';
$route['limit'] = 'userApi/api_limit';
$route['api-key'] = 'userApi/api_key';

/*events*/
$route['events'] = 'EventController/index';
/*Assignments*/
$route['assignments'] = 'AssignmentController/index';
$route['save-video'] = 'AssignmentController/saveVideo';
