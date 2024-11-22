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
$default_controller = "home";
$GLOBALS['lang'] = array();
$route['404_override'] = 'errors/page_missing';
//$route['translate_uri_dashes'] = FALSE;
$route['login'] = 'auth/index';
$route['partner-login'] = 'auth/partner';
$route['login/(:any)'] = 'auth/index';
$route['ghost/(:any)'] = 'auth/ghost/$1';
$route['signup'] = 'auth/signup';
$route['check_email'] = 'auth/check_email';
$route['register'] = 'auth/register';
$route['verify_email/(:any)'] = 'auth/verify_email/$1';
$route['validate_user'] = 'auth/validate_user_ajax';
$route['user_block_request'] = 'User/index';
$route['signin'] = 'auth/login';
$route['partner-signin'] = 'auth/partner_login';
$route['logout'] = 'auth/logout';
$route['forgot-password'] = 'auth/forgot_password';
$route['forgot-password-submit'] = 'auth/reset_password';
$route['validate_email'] = 'auth/validate_email_ajax';
$route['verify_code/(:any)'] = 'auth/verify_code/$1';
$route['unblock-account/(:any)'] = 'auth/unblock_account/$1';
$route['unblock-account/(:any)/(:num)'] = 'auth/unblock_account/$1/$2';
$route['validate_otp'] = 'auth/validate_otp_ajax';
$route['verified_otp'] = 'auth/verified_otp';
$route['new-password/(:any)/(:num)'] = 'auth/new_password/$1/$2';
$route['update_password'] = 'auth/update_password';
$route['upload-video'] = 'profile/upload_video';
$route['gm_callback'] = 'auth/gm_callback';
$route['fb_callback'] = 'auth/fb_callback';
$route['get_states'] = 'ajax/get_states';
$route['get_cities'] = 'ajax/get_cities';
$route['get_child_categories'] = 'ajax/get_child_categories';
$route['profile'] = 'profile/index';
$route['update-profile'] = 'profile/update_profile';
$route['change-password'] = 'profile/change_password';
$route['validate_old_password'] = 'profile/validate_old_password_ajax';
$route['passwod_update'] = 'profile/passwod_update';
$route['about-us'] = 'home/about_us';
$route['faq'] = 'home/faq';
$route['terms_of_use'] = 'home/terms_of_use';
$route['terms_of_submission'] = 'home/terms_of_submission';
$route['terms_of_submission_video_sharing'] = 'home/terms_of_submission_video_sharing';
$route['contact-us'] = 'home/contact_us';
$route['privacy'] = 'home/privacy';
$route['video-upload'] = 'Video_upload/index';
$route['create-folder'] = 'Video_upload/create_folder';
$route['file-upload/(:any)'] = 'Video_upload/upload_file/$1';
$route['file-upload-2/(:any)'] = 'Video_upload/upload_files/$1';
$route['upload-videos'] = 'Video_upload/upload_videos';
// $route['search'] = 'home/search';
$route['password-new/(:num)/(:num)'] = 'Auth/password_new/$1/$2';
$route['pass-new'] = 'Auth/c_password';
$route['regenerate-OTP'] = 'Auth/regenerate_otp';
//$route['dashboard'] = 'Profile/dashboard';
$route['video-requests'] = 'Profile/detailVideoRequests';
$route['approved-videos'] = 'Profile/approved_videos';
$route['acquired-videos'] = 'Profile/acquired_videos';
$route['rejected-videos'] = 'Profile/rejected_videos';
$route['all-videos'] = 'Profile/all_videos';
$route['new-login/(:any)'] = 'Auth/new_login/$1';
$route['new-update-password'] = 'Auth/new_update_password';
$route['submit-video/(:any)'] = 'profile/submit_video/$1';
$route['submit-video'] = 'profile/submit_video';
$route['submit-success'] = 'profile/submit_video_success';
$route['submit-video-description/(:any)'] = 'profile/submit_video_description/$1';
//New form routes
$route['video-submission/(:any)'] = 'profile/submit_viral_video_form/$1';
$route['submit-video-to-win/(:any)'] = 'profile/simple_viral_video_form/$1';
$route['forms/jm_appearance_release'] = 'profile/jm_appearance_release';
$route['forms/guardian_appearance_release'] = 'profile/guardian_appearance_release';
$route['submit_viral_video'] = 'profile/submit_viral_video';
$route['simple_viral_video'] = 'profile/simple_viral_video';
//Old form routes
$route['video_signed_pdf'] = 'profile/video_signed_pdf';
$route['video-contract/(:any)'] = 'profile/video_contract/$1';
$route['video_signed_contract'] = 'profile/video_signed_contract';

$route['video_reject_upload/(:any)'] = 'Profile/acquired_client_videos/$1';
$route['acquired_client_video_submit'] = 'profile/acquired_client_video_submit';
$route['video-submission'] = 'profile/video_submission';
//$route['account-summary'] = 'profile/account_summary';
$route['dashboard'] = 'profile/account_summary';
$route['payment-history'] = 'profile/payment_history';
$route['monthly-account-summary'] = 'profile/monthly_account_summary';
$route['Licensing'] = 'profile/Licensing';
$route['Ad-Revenue'] = 'profile/earnings_breakdown';
$route['earnings-breakdown'] = 'profile/earnings_breakdown';
$route['send_contract/(:any)'] = 'Video_upload/send_contract/$1';
$route['contract_signed/(:any)'] = 'home/contract_signed/$1';
$route['remove_file'] = 'Video_upload/remove_file';
$route['categories'] = 'home/categories';
$route['partner'] = 'home/index';
$route['partner/sidebar'] = 'home/details_load_later';
$route['news_letter'] = 'ajax/mailchimp';
$route['get_list_mailchimp'] = 'ajax/get_list_mailchimp';
$route['partner/categories'] = 'home/categories_partner';
$route['categories/(:any)'] = 'home/categories/$1';
$route['partner/categories/(:any)'] = 'home/categories_partner/$1';
$route['load-more-cat-videos/(:any)'] = 'home/categories_partner_load/$1';
$route['load-more-search'] = 'home/search_load/';
$route['licensing-video'] = 'home/licensing_model';
$route['license-video'] = 'home/license_video';
$route['contact_lead'] = 'ajax/contact_lead';

$route['mrss/download/(:any)'] = 'home/mrss_download/$1';
$route['mrss_story/download/(:any)'] = 'home/mrss_download_story/$1';
$route['mrss_story/(:any)'] = 'home/mrss_story/$1';
$route['mrss_story/(:any)/(:any)'] = 'home/mrss_story/$1/$2';
$route['mrss_story/download/(:any)/(:any)'] = 'home/mrss_download_story/$1/$2';
$route['mrss/mrss_partner_download/(:any)/(:any)'] = 'home/mrss_partner_download/$1/$2';
$route['mrss/publication_date_video/(:any)/(:any)'] = 'home/publication_date_video/$1/$2';
$route['mrss/copy_feeds/(:any)/(:any)'] = 'home/copy_feeds/$1/$2';
$route['mrss/(:any)/(:any)/(:any)'] = 'home/mrss_partner_secure/$1/$2/$3';
$route['mrss/(:any)/(:any)'] = 'home/mrss_partner/$1/$2';
$route['mrss/(:any)'] = 'home/mrss/$1';

$route['check_fu'] = 'home/check_func';
$route['directories'] = 'home/createDirectories';
$route['appearancerelease'] = 'home/appearance';
$route['appearance_release/(:any)'] = 'profile/appearance_release/$1';
$route['appearance_release_ajax'] = 'profile/appearance_release_ajax';
$route['recorder/(:any)'] = 'profile/second_signer/$1';
$route['second_signer_ajax'] = 'profile/second_signer_ajax';

$route['rss/(:any)/(:any)'] = 'home/rss_article/$1/$2';

$route['brands_mrss/(:any)/(:any)'] = 'home/brands_mrss_download/$1/$2';


$controller_exceptions = array(
    '404_override',
    'login',
    'signup',
    'check_email',
    'register',
    'verify_email',
    'validate_user',
    'login',
    'logout',
    'forgot-password',
    'forgot-password-submit',
    'validate_email',
    'verify_code',
    'validate_otp',
    'verified_otp',
    'new-password',
    'update_password',
    'upload-video',
    'gm_callback',
    'partner_gm_callback',
    'fb_callback',
    'profile',
    'get_states',
    'get_cities',
    'change-password',
    'validate_old_password',
    'passwod_update',
    'video-upload',
    'file-upload',
    'upload-videos',
    'search',
    'password-new',
    'pass-new',
    'regenerate-OTP',
    'dashboard',
    'video-requests',
    'approved-videos',
    'acquired-videos',
    'rejected-videos',
    'all-videos',
    'new-login',
    'submit-video',
    'video-submission',
    'monthly-account-summary',
    'Licensing',
    'Ad-Revenue',
    'earnings-breakdown',
    'send_contract',
    'faq',
    'remove_file',
    'categories',
    'partner/categories',
    'licensing-video',
    'license-video',
    'contract_signed',
    'partner',
    'news_letter',
    'get_list_mailchimp',
    'terms_of_use',
    'terms_of_submission',
    'terms_of_submission_video_sharing',
    'contact_lead',
    'mrss',
    'directories',

);
$route['default_controller'] = $default_controller;
$route["^(".implode('|', $GLOBALS['lang']).")/(".implode('|', $controller_exceptions).")(.*)"] = '$2';
$route["^(".implode('|', $GLOBALS['lang']).")?/(.*)"] = $default_controller.'/$2';
$route["^((?!\b".implode('\b|\b', $controller_exceptions)."\b).*)$"] = $default_controller.'/index/$1';
foreach($GLOBALS['lang'] as $language)
    $route[$language] = $default_controller.'/index';
