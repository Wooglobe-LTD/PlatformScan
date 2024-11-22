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
$route['default_controller'] = 'auth';
$route['404_override'] = 'my_error';
$route['translate_uri_dashes'] = FALSE;

$route['testyoutube/(:any)'] = 'submit/url_check/$i';
$route['auth'] = 'auth/index';
$route['login'] = 'auth/login';
$route['reset'] = 'auth/reset_password';
$route['unblock-account/(:any)'] = 'auth/unblock_account/$1';
$route['logout'] = 'dashboard/logout';
$route['search'] = 'dashboard/search';
$route['dashboard'] = 'dashboard/index';
$route['change_password'] = 'ajax/change_password';
$route['settings'] = 'ajax/settings';
$route['settings_save'] = 'ajax/settings_save';

/************ Client Management ************/
$route['clients'] = 'clients/index';
$route['get_clients'] = 'clients/clients_listing';
$route['add_client'] = 'clients/add_client';
$route['add_client2'] = 'clients/add_client_deal';
$route['get_client'] = 'clients/get_client';
$route['update_client'] = 'clients/update_client';
$route['delete_client'] = 'clients/delete_client';
$route['client_reset_password'] = 'clients/client_reset_password';

/************ Patner Management ************/
$route['partners'] = 'partners/index';
$route['get_partners'] = 'partners/partners_listing';
$route['add_partner'] = 'partners/add_partner';
$route['get_partner'] = 'partners/get_partner';
$route['update_partner'] = 'partners/update_partner';
$route['delete_partner'] = 'partners/delete_partner';
$route['partner_reset_password'] = 'partners/partner_reset_password';

/************ Blocked User Management ************/
$route['block_users'] = 'Block_Users/index';
$route['get_block_users'] = 'Block_Users/users_listing';
$route['unblock_user'] = 'Block_Users/unblock_user';

/************ Cancel Contract Management ************/
$route['cancelcontract'] = 'CancelContract/index';
$route['cancelcontract_lead'] = 'CancelContract/users_listing';

/************ Not Interested Management ************/
$route['notinterested'] = 'NotInterested/index';
$route['notinterested_leads'] = 'NotInterested/users_listing';

/************ Close Won Management ************/
$route['closewon'] = 'Closewon/index';
$route['closewon_leads'] = 'Closewon/videos_listing';

/************ App User Management ************/
$route['users'] = 'users/index';
$route['get_users'] = 'users/users_listing';
$route['add_user'] = 'users/add_user';
$route['get_user'] = 'users/get_user';
$route['update_user'] = 'users/update_user';
$route['delete_user'] = 'users/delete_user';
$route['user_reset_password'] = 'users/user_reset_password';

/************ Category Management ************/
$route['categories'] = 'categories/index';
$route['get_categories'] = 'categories/categories_listing';
$route['add_category'] = 'categories/add_category';
$route['get_category'] = 'categories/get_category';
$route['update_category'] = 'categories/update_category';
$route['delete_category'] = 'categories/delete_category';

/************ MRSS Category Management ************/
$route['categories_mrss'] = 'categoriesMrss/index';
$route['get_categories_mrss'] = 'categoriesMrss/categories_listing';
$route['add_category_mrss'] = 'categoriesMrss/add_category';
$route['get_category_mrss'] = 'categoriesMrss/get_category';
$route['update_category_mrss'] = 'categoriesMrss/update_category';
$route['delete_category_mrss'] = 'categoriesMrss/delete_category';
$route['get-feed-data'] = 'categoriesMrss/get_feed_data';
$route['secure-feed-data'] = 'categoriesMrss/secure_feed_data';
$route['mrss/(:any)/(:any)/preview'] = 'categoriesMrss/show_partner_preview/$1/$2';
$route['mrss/(:any)/preview'] = 'categoriesMrss/show_preview/$1';
$route['update-vi-mrss-info'] = 'Categories/save_partners_and_categories_info';
$route['ajax-list-gen-mrss-categories'] = 'Categories/general_categories_listing';
$route['reset-vi-mrss/(:num)'] = 'Categories/clear_all_feeds/$1';
$route['remove_from_feed'] = 'categoriesMrss/remove_video_from_feed';
$route['add_to_feed'] = 'categoriesMrss/add_video_to_feed';
$route['publish_feed'] = 'categoriesMrss/publish_feed';

/************ Videos Management ************/
$route['videos'] = 'videos/index';
$route['get_videos'] = 'videos/video_listing';
$route['video_add'] = 'videos/video_add';
$route['get_video_sub_category'] = 'videos/get_video_sub_category';
$route['upload_video'] = 'videos/upload_video';
$route['watermar_upload_video'] = 'videos/watermark_upload_video';

$route['add_video'] = 'videos/add_video';
$route['edit_video/(:num)'] = 'videos/edit_video/$1';
$route['get_video'] = 'videos/get_video';
$route['update_video'] = 'videos/update_video';
$route['delete_video'] = 'videos/delete_video';
$route['delete_videolead'] = 'videos/delete_videolead';
$route['delete_video_raw_file'] = 'videos/delete_raw_file';
$route['original_files/(:num)'] = 'videos/original_files/$1';
$route['get_original_files/(:num)'] = 'videos/get_original_files/$1';
$route['get_file'] = 'videos/get_file';
$route['upload_edited_video/(:num)'] = 'videos/upload_edited_video/$1';
$route['edited_video_upload'] = 'videos/edited_video_upload';
$route['update_mrss'] = 'videos/update_mrss';
$route['instagram_url'] = 'videos/instagram_url';
$route['mrss_partner'] = 'videos/mrss_partner';


/************ Earnings Management ************/
$route['earnings'] = 'earnings/index';
$route['get_earnings'] = 'earnings/earning_listing';
$route['add_earning'] = 'earnings/add_earning';
$route['get_earning'] = 'earnings/get_earning';
$route['update_earning'] = 'earnings/update_earning';
$route['delete_earning'] = 'earnings/delete_earning';

/************ Video Expense Management ************/
$route['video_expenses'] = 'video_Expenses/index';
$route['get_video_expenses'] = 'video_Expenses/video_expense_listing';
$route['add_video_expense'] = 'video_Expenses/add_video_expense';
$route['get_video_expense'] = 'video_Expenses/get_video_expense';
$route['update_video_expense'] = 'video_Expenses/update_video_expense';
$route['delete_video_expense'] = 'video_Expenses/delete_video_expense';

/************ Content Management ************/
$route['content'] = 'content/index';
$route['get_content'] = 'content/conten_listing';
$route['content_add'] = 'content/content_add';
$route['add_content'] = 'content/add_content';
$route['edit_content/(:num)'] = 'content/edit_content/$1';
$route['get_content_single'] = 'content/get_video';
$route['update_content'] = 'content/update_content';
$route['delete_content'] = 'content/delete_content';
$route['upload_video_banner_mp4'] = 'content/upload_video_banner_mp4';
$route['upload_video_banner_webm'] = 'content/upload_video_banner_webm';
$route['update_home_banner'] = 'content/update_home_banner';

/************ Content Management ************/
$route['pages'] = 'pages/index';
$route['get_pages'] = 'pages/pages_listing';
$route['page_add'] = 'pages/page_add';
$route['add_page'] = 'pages/add_page';
$route['edit_page/(:num)'] = 'pages/edit_page/$1';
$route['get_page_single'] = 'pages/get_video';
$route['update_page'] = 'pages/update_page';
$route['delete_page'] = 'pages/delete_page';

/************ Channel Management ************/
$route['channel'] = 'channel/index';
$route['get_channels'] = 'channel/channel_listing';
$route['add_channel'] = 'channel/add_channel';
$route['get_channel'] = 'channel/get_channel';
$route['update_channel'] = 'channel/update_channel';
$route['delete_channel'] = 'channel/delete_channel';

/************ Menu Management ************/
$route['menus'] = 'menus/index';
$route['get_menus'] = 'menus/menus_listing';
$route['add_menu'] = 'menus/add_menu';
$route['get_menu'] = 'menus/get_menu';
$route['update_menu'] = 'menus/update_menu';
$route['delete_menu'] = 'menus/delete_menu';

/************ Permission Actions Management ************/
$route['menu_actions'] = 'menu_Actions/index';
$route['get_actions'] = 'menu_Actions/actions_listing';
$route['add_action'] = 'menu_Actions/add_action';
$route['get_action'] = 'menu_Actions/get_action';
$route['update_action'] = 'menu_Actions/update_action';
$route['delete_action'] = 'menu_Actions/delete_action';

/************ Roles Management ************/
$route['roles'] = 'roles/index';
$route['get_roles'] = 'roles/roles_listing';
$route['add_role'] = 'roles/add_role';
$route['get_role'] = 'roles/get_role';
$route['update_role'] = 'roles/update_role';
$route['delete_role'] = 'roles/delete_role';
$route['role_permissions/(:any)'] = 'roles/role_permissions/$1';
$route['save_permission/(:any)'] = 'roles/save_permission/$1';

/************ Staff Management ************/
$route['members'] = 'staff/index';
$route['get_members'] = 'staff/members_listing';
$route['add_member'] = 'staff/add_member';
$route['get_member'] = 'staff/get_member';
$route['update_member'] = 'staff/update_member';
$route['delete_member'] = 'staff/delete_member';
$route['member_reset_password'] = 'staff/member_reset_password';

/************ Third Party Staff Management ************/
$route['third_party_staff'] = 'Third_Party_Staff/index';
$route['get_third_party_members'] = 'Third_Party_Staff/members_listing';
$route['add_third_party_member'] = 'Third_Party_Staff/add_member';
$route['get_third_party_member'] = 'Third_Party_Staff/get_member';
$route['update_third_party_member'] = 'Third_Party_Staff/update_member';
$route['delete_third_party_member'] = 'Third_Party_Staff/delete_member';
$route['third_party_member_reset_password'] = 'Third_Party_Staff/member_reset_password';

/************ Video Leads Management ************/
$route['video_leads'] = 'video_leads/index';
$route['get_video_leads'] = 'video_leads/video_leads_listing';
$route['rate_lead'] = 'video_leads/rate_lead';
$route['get_video_lead'] = 'video_leads/get_lead';
$route['delete_lead'] = 'video_leads/delete_lead';
$route['delete_lead_per'] = 'video_leads/delete_lead_per';
$route['import_leads'] = 'video_leads/import_leads';
$route['update_details'] = 'video_leads/update_details';
$route['sr_callback'] = 'cronjobs/sr_callback';
$route['sr_signed'] = 'cronjobs/sr_signed';
$route['thank_you'] = 'cronjobs/thank_you';
$route['submit'] = 'Submit/index';
$route['check_email'] = 'Submit/check_email';
$route['submit_lead'] = 'Submit/submit_lead';

/************ Video Deals Management ************/
$route['video_deals'] = 'video_Deals/index';
$route['get_video_deals'] = 'video_Deals/video_deals_listing';
$route['get_video_deal'] = 'video_Deals/get_deal';
$route['delete_deal'] = 'video_Deals/delete_deal';
$route['delete_deal_per'] = 'video_Deals/delete_per';
$route['import_deals'] = 'video_Deals/import_deals';
$route['assign-deal-client'] = 'video_Deals/assign_deal_client';
$route['deal-rated-refresh'] = 'video_Deals/deal_rated_refresh';
$route['signed-refresh'] = 'video_Deals/signed_refresh';
$route['welcome-reminder'] = 'video_Deals/welcome_reminder';
$route['information-notification'] = 'video_Deals/pending_information_reminder';
$route['created-refresh'] = 'video_Deals/created_refresh';
$route['distribute-refresh'] = 'video_Deals/distribute_refresh';
$route['interested-refresh'] = 'video_Deals/interested_refresh';
$route['not-interested'] = 'video_Deals/not_interested';
$route['information-refresh'] = 'video_Deals/information_refresh';
$route['information-received-refresh'] = 'video_Deals/information_received_refresh';
$route['won-refresh'] = 'video_Deals/won_refresh';
$route['lost-refresh'] = 'video_Deals/lost_refresh';
$route['deal-detail/(:any)'] = 'video_Deals/deal_detail/$1';
$route['contract-sent-refresh'] = 'video_Deals/contract_sent_refresh';
$route['contract-sent-refresh'] = 'video_Deals/contract_sent_refresh';
$route['contract_cancel'] = 'video_Deals/contract_cancel';
$route['send-reminder-email'] = 'video_Deals/send_reminder_email';
$route['description-send-reminder-email'] = 'video_Deals/description_send_reminder_email';
$route['pending-refresh'] = 'video_Deals/pending_refresh';
$route['move-closewon'] = 'video_Deals/move_closewon';
$route['rejected-refresh'] = 'video_Deals/rejected_refresh';
$route['publish-youtube'] = 'video_Deals/publish_youtube';
$route['publish-facebook'] = 'video_Deals/publish_facebook';
$route['publish-portal/(:any)'] = 'video_Deals/publish_portal/$1';
$route['email-detail/(:any)'] = 'video_Deals/email_detail/$1';
$route['upload-refresh'] = 'video_Deals/upload_refresh';
$route['revenue_update'] = 'video_Deals/revenue_update';
$route['get-publishing-data'] = 'video_Deals/get_video_publlish_data';
$route['email_information'] = 'video_Deals/getEmailInformation';
$route['download-raw-files/(:num)'] = 'video_Deals/download_files/$1';
$route['send-zip-email/(:any)/(:any)'] = 'Pending_downloads/send_pending_download_links/$1/$2';
$route['check-download-filesize'] = 'Video_Deals/decide_download_category';
$route['view_contract/(:any)'] = 'video_Deals/view_contract/$1';
$route['update_closing'] = 'video_Deals/update_closing';
$route['update_q1'] = 'video_Deals/update_q1';
$route['update_q2'] = 'video_Deals/update_q2';
$route['update_q3'] = 'video_Deals/update_q3';
$route['update_revenue'] = 'video_Deals/update_revenue';
$route['update_title'] = 'video_Deals/update_title';
$route['update_des'] = 'video_Deals/update_des';
$route['update_tags'] = 'video_Deals/update_tags';
$route['update_message'] = 'video_Deals/update_message';
$route['update_ratings'] = 'video_Deals/update_ratings';
$route['update_ratings_comment'] = 'video_Deals/update_ratings_comment';
$route['update_facebook'] = 'video_Deals/update_facebook';
$route['update_youtube'] = 'video_Deals/update_youtube';
$route['update_raws3'] = 'video_Deals/update_raws3';
$route['update_docs3'] = 'video_Deals/update_docs3';
$route['update_editeds3'] = 'video_Deals/update_editeds3';
$route['update_thumbs3'] = 'video_Deals/update_thumbs3';
$route['update_deal_mrss'] = 'video_Deals/update_deal_mrss';
$route['update_confidence'] = 'video_Deals/update_confidence';
$route['update_video_comment'] = 'video_Deals/update_video_comment';
$route['file-upload/(:any)'] = 'Video_Deals/upload_file/$1';
$route['upload_user_submit'] = 'Video_Deals/upload_user_submit';
$route['story_information_submission'] = 'Video_Deals/story_information_submission';
$route['personal_information_submission'] = 'Video_Deals/personal_information_submission';

/************ Email Sending ************/

$route['communication-email/(:num)'] = 'communication/index/$1';
$route['send_email'] = 'communication/send_email';
$route['send_custom_email'] = 'communication/send_custom_email';

/************ Email Templates Actions Management ************/
$route['email_templates'] = 'email_Templates/index';
$route['get_email_templates'] = 'email_Templates/email_template_listing';
$route['email_template_add'] = 'email_Templates/email_template_add';
$route['add_email_template'] = 'email_Templates/add_email_template';
$route['edit_email_template/(:num)'] = 'email_Templates/edit_email_template/$1';
$route['get_email_template'] = 'email_Templates/get_email_template';
$route['update_email_template'] = 'email_Templates/email_template_action';
$route['delete_email_template'] = 'email_Templates/delete_email_template';


/************ Video Leads Management ************/
$route['video_license'] = 'video_License/index';
$route['get_video_licenses'] = 'video_License/video_licenses_listing';
//$route['rate_lead'] = 'video_leads/rate_lead';
$route['get_video_license'] = 'video_License/get_license';


/************ Social Media Management ************/
$route['social'] = 'social/index';
$route['get_socials'] = 'social/socials_listing';
$route['add_social'] = 'social/add_social';
$route['get_social'] = 'social/get_social';
$route['update_social'] = 'social/update_social';
$route['delete_social'] = 'social/delete_social';

/************ Social Media Management ************/
$route['earning_requests'] = 'earning_Requests/index';
$route['get_earning_requests'] = 'earning_Requests/earning_requests_listing';
$route['update_earning_request'] = 'earning_Requests/update_earning_request';


$route['gmail'] = 'Cronjobs/gmail';
$route['cb_gmail'] = 'Cronjobs/cb_gmail';
$route['gmail_push'] = 'Cronjobs/gmail_push';
$route['fullSyncGmail/(:any)/(:any)'] = 'Cronjobs/fullSyncGmail/$1/$2';

$route['facebook'] = 'Cronjobs/fb';
$route['cb_fb'] = 'Cronjobs/cb_fb';


$route['email_reply/(:any)'] = 'my_Imap/reply/$1';
$route['email_forword/(:any)'] = 'my_Imap/forword/$1';
$route['get_template_html'] = 'my_Imap/get_template';
$route['send_mail'] = 'my_Imap/send_mail';
$route['send_mail'] = 'my_Imap/send_mail';

$route['send_email_reminders'] = 'Cronjobs/send_email_reminders';
$route['check_feeds'] = 'Cronjobs/check_feeds';


/************ Bulk Uploading ************/
$route['bulk'] = 'Bulk/index';
$route['csv_upload'] = 'Bulk/upload_bulk';

/************ Reject ************/
$route['reject-videolead'] = 'videos/reject_videos';
$route['reject-staff-videolead'] = 'videos/reject_staff_video';
$route['reject-thirdparty-videolead'] = 'videos/reject_thirdparty_videos';

/************ Reports ************/
$route['reports'] = 'reports/index';
$route['csv-reports'] = 'reports/csv_reports';
$route['get_reports'] = 'reports/reports_listing';
$route['get_reports_details'] = 'reports/reports_details';
$route['array_to_csv_download'] = 'reports/array_to_csv_download';

/************ Mrss Reports ************/
$route['MrssReports'] = 'MrssReports/index';
$route['get_mrss_reports'] = 'MrssReports/reports_listing';

/*configurations*/
$route['configurations'] = 'ConfigurationsController/index';
$route['store_configurations'] = 'ConfigurationsController/store';

/*for mobile application*/
/*videos*/
$route['import-videos-view'] = 'mobile_application/ImportVideosController/importVideosView';
$route['import-videos'] = 'mobile_application/ImportVideosController/importVideos';
$route['save-video'] = 'mobile_application/ImportVideosController/saveVideo';

$route['mobile-app-videos'] = 'mobile_application/VideosManagementController/index';
$route['mobile-app-get_videos'] = 'mobile_application/VideosManagementController/video_listing';
$route['mobile-app-get_video'] = 'mobile_application/VideosManagementController/get_video';
$route['mobile-app-delete_video'] = 'mobile_application/VideosManagementController/delete_video';
$route['mobile-app-edit_video/(:num)'] = 'mobile_application/VideosManagementController/edit_video/$1';

$route['mobile-app-update_video'] = 'mobile_application/VideosManagementController/update_video';
/*video of day*/

$route['mobile-app-video-of-day'] = 'mobile_application/VideosManagementController/videoofday';
/*video complaints*/
$route['mobile-app-video-complaints-index'] = 'mobile_application/VideosManagementController/video_complaints_view';
$route['mobile-app-video-complaints-listing'] = 'mobile_application/VideosManagementController/video_complaints_listing';
$route['mobile-app-get_video_complaint'] = 'mobile_application/VideosManagementController/get_video_complaint';
$route['mobile-app-update_video_complaint'] = 'mobile_application/VideosManagementController/update_video_complaint';

$route['mobile-app-delete_video_complaint'] = 'mobile_application/VideosManagementController/delete';


/*promote video of day */

$route['mobile-app-promote-demote-video'] = 'mobile_application/VideosManagementController/promoteDemoteVideo';
/************ Mobile App Category Management ************/
$route['mobile-app-categories'] = 'mobile_application/CategoriesController/index';
$route['mobile-app-get_categories'] = 'mobile_application/CategoriesController/categories_listing';
$route['mobile-app-add_category'] = 'mobile_application/CategoriesController/add_category';
$route['mobile-app-get_category'] = 'mobile_application/CategoriesController/get_category';
$route['mobile-app-update_category'] = 'mobile_application/CategoriesController/update_category';
$route['mobile-app-delete_category'] = 'mobile_application/CategoriesController/delete_category';

/************ Mobile App Keyword Management ************/
$route['mobile-app-keywords'] = 'mobile_application/KeywordsController/index';
$route['mobile-app-get_keywords'] = 'mobile_application/KeywordsController/keywords_listing';
$route['mobile-app-add_keyword'] = 'mobile_application/KeywordsController/add_keyword';
$route['mobile-app-get_keyword'] = 'mobile_application/KeywordsController/get_keyword';
$route['mobile-app-update_keyword'] = 'mobile_application/KeywordsController/update_keyword';
$route['mobile-app-delete_keyword'] = 'mobile_application/KeywordsController/delete_keyword';
/************ Mobile App Task Management ************/
$route['mobile-app-tasks'] = 'mobile_application/TasksController/index';
$route['mobile-app-get_tasks'] = 'mobile_application/TasksController/tasks_listing';
$route['mobile-app-add_task'] = 'mobile_application/TasksController/add_task';
$route['mobile-app-get_task'] = 'mobile_application/TasksController/get_task';
$route['mobile-app-update_task'] = 'mobile_application/TasksController/update_task';
$route['mobile-app-delete_task'] = 'mobile_application/TasksController/delete_task';

/* Mobile App Users */
/************ Blocked User Management ************/
$route['block_users'] = 'Block_Users/index';
$route['get_block_users'] = 'Block_Users/users_listing';
$route['unblock_user'] = 'Block_Users/unblock_user';

/************ Mobile App User Management ************/
$route['mobile-app-users'] = 'mobile_application/UserController/index';
$route['mobile-app-get_users'] = 'mobile_application/UserController/users_listing';
$route['mobile-app-add_user'] = 'mobile_application/UserController/add_user';
$route['mobile-app-get_user'] = 'mobile_application/UserController/get_user';
$route['mobile-app-update_user'] = 'mobile_application/UserController/update_user';
$route['mobile-app-delete_user'] = 'mobile_application/UserController/delete_user';
$route['mobile-app-user_reset_password'] = 'mobile_application/UserController/user_reset_password';
$route['event-email-unsubscribe'] = 'mobile_application/UserController/event_email_unsubscribe';
/*send custom notification to specific user */
$route['mobile-app-user-send-custom-notification']='mobile_application/UserController/send_custom_notification';

/*Mobile app comments management*/

$route['mobile-app-comments'] = 'mobile_application/CommentsController/index';
$route['mobile-app-get_comments'] = 'mobile_application/CommentsController/comments_listing';
$route['mobile-app-get_comment'] = 'mobile_application/CommentsController/get_comment';
$route['mobile-app-comment-delete'] = 'mobile_application/CommentsController/delete_comment';
$route['mobile-app-edit/(:num)'] = 'mobile_application/CommentsController/edit';
$route['mobile-app-update'] = 'mobile_application/CommentsController/update';

/************ Mobile App Event Management ************/
$route['mobile-app-events'] = 'mobile_application/EventController/index';
$route['mobile-app-get_events'] = 'mobile_application/EventController/events_listing';
$route['mobile-app-add_event'] = 'mobile_application/EventController/add_event';
$route['mobile-app-get_event'] = 'mobile_application/EventController/get_event';
$route['mobile-app-update_event'] = 'mobile_application/EventController/update_event';
$route['mobile-app-delete_event'] = 'mobile_application/EventController/delete_event';
/************ Mobile App Assignment Management ************/
$route['mobile-app-assignments'] = 'mobile_application/AssignmentController/index';
$route['mobile-app-get_assignments'] = 'mobile_application/AssignmentController/assignments_listing';
$route['mobile-app-add_assignment'] = 'mobile_application/AssignmentController/add_assignment';
$route['mobile-app-get_assignment'] = 'mobile_application/AssignmentController/get_assignment';
$route['mobile-app-update_assignment'] = 'mobile_application/AssignmentController/update_assignment';
$route['mobile-app-delete_assignment'] = 'mobile_application/AssignmentController/delete_assignment';


/* MRSS feeds panel*/
$route['mobile-app-mrss'] = 'mobile_application/MRSSController/index';
$route['mobile-app-get_mrsss'] = 'mobile_application/MRSSController/mrss_listing';
$route['mobile-app-add_mrss'] = 'mobile_application/MRSSController/add_mrss';
$route['mobile-app-get_mrss'] = 'mobile_application/MRSSController/get_mrss';
$route['mobile-app-update_mrss'] = 'mobile_application/MRSSController/update_mrss';
$route['mobile-app-delete_mrss'] = 'mobile_application/MRSSController/delete_mrss';
/*mobile Youtube channel panel*/
$route['mobile-app-youtube_channel'] = 'mobile_application/YoutubeChannelController/index';
$route['mobile-app-get_youtube_channels'] = 'mobile_application/YoutubeChannelController/youtube_channel_listing';
$route['mobile-app-add_youtube_channel'] = 'mobile_application/YoutubeChannelController/add_youtube_channel';
$route['mobile-app-get_youtube_channel'] = 'mobile_application/YoutubeChannelController/get_youtube_channel';
$route['mobile-app-update_youtube_channel'] = 'mobile_application/YoutubeChannelController/update_youtube_channel';
$route['mobile-app-delete_youtube_channel'] = 'mobile_application/YoutubeChannelController/delete_youtube_channel';

/* comments filteration*/
$route['mobile-app-abusive-comments'] = 'mobile_application/AbusiveCommentsController/index';
$route['mobile-app-get_abusive_comment_words'] = 'mobile_application/AbusiveCommentsController/abusive_comment_words_listing';
$route['mobile-app-add_abusive_comment_word'] = 'mobile_application/AbusiveCommentsController/add_abusive_comment_word';
$route['mobile-app-get_abusive_comment_word'] = 'mobile_application/AbusiveCommentsController/get_abusive_comment_word';
$route['mobile-app-update_abusive_comment_word'] = 'mobile_application/AbusiveCommentsController/update_abusive_comment_words';
$route['mobile-app-delete_abusive_comment_word'] = 'mobile_application/AbusiveCommentsController/delete_abusive_comment_word';

/*get videos from mrss links*/
$route['get-mrss-videos']='mobile_application/VideoContentManagementController/mrss';
/*for mobile application feeds*/
$route['mobile-app-mrss/xml/(:any)'] = 'mobile_application/VideoContentManagementController/mrss_xml_feed/$1';

/*youtube api*/
$route['get-instagram-videos']='mobile_application/VideoContentManagementController/instagram_account';
$route['instagram-redirect-uri-for-token']='mobile_application/VideoContentManagementController/instagram_token';
$route['get-youtube-videos']='mobile_application/VideoContentManagementController/youtube_channel';
$route['get-facebook-videos']='mobile_application/VideoContentManagementController/facebook_page';


/************ Payments Management ************/
$route['payments'] = 'payments/index';
$route['get_payments'] = 'payments/payments_listing';
$route['add_payment'] = 'payments/add_payment';
$route['get_payment'] = 'payments/get_payment';
$route['update_payment'] = 'payments/update_payment';
$route['delete_payment'] = 'payments/delete_payment';
$route['make_payment'] = 'payments/make_payment';

