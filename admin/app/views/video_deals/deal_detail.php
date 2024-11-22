
<?php

/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 4/24/2018
 * Time: 11:40 AM
 */

$res_directory = '../uploads/' . $dealData->unique_key . '/attachments/researcher';
$mgr_directory = '../uploads/' . $dealData->unique_key . '/attachments/manager';
$res_att = array();
if (is_dir($res_directory)) {
    if ($handle = opendir($res_directory)) {
        while (($file = readdir($handle)) !== FALSE) {
            $res_att[] = basename($file);
        }
        closedir($handle);
        $res_att = array_diff($res_att, array('.', '..'));
    }
}
$mgr_att = array();
if (is_dir($mgr_directory)) {
    if ($handle = opendir($mgr_directory)) {
        while (($file = readdir($handle)) !== FALSE) {
            $mgr_att[] = basename($file);
        }
        closedir($handle);
        $mgr_att = array_diff($mgr_att, array('.', '..'));
    }
}
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
<style>
    .uk-sticky-placeholder .uk-active {
        z-index: 0 !important;
    }

    .uk-tab.uk-active {
        z-index: 999 !important;
        height: 40px;
        border: 0px;
    }

    .border-black {
        border: thin solid #1976d2;
        border-radius: 7px;
        padding: 5px;
    }

    .mb-10p {
        margin-bottom: 10px;
    }

    .select-bs {
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border-color: #1976d2;
    }

    textarea {
        width: 96%;
        height: 150px;
        padding: 12px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
        background-color: #f8f8f8;
        resize: none;
    }

    .md-list-content {
        position: relative;
        min-height: 49px;
        overflow: initial !important;
    }

    .btn-save {
        position: absolute;
        right: 0;
        top: 0;
    }

    .btn-repaint {
        position: absolute;
        right: -5px;
        top: -16px;
        border: none;
    }

    .btn-cancel {
        position: absolute;
        right: 0;
        top: 29px;
    }

    .fileuploader-input-button {
        background: rgb(2, 0, 36);
        background: -moz-linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(33, 150, 243, 1) 0%, rgba(0, 255, 248, 0.8351541300113796) 100%) !important;
        background: -webkit-linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(33, 150, 243, 1) 0%, rgba(0, 255, 248, 0.8351541300113796) 100%) !important;
        background: linear-gradient(90deg, rgba(2, 0, 36, 1) 0%, rgba(33, 150, 243, 1) 0%, rgba(0, 255, 248, 0.8351541300113796) 100%) !important;
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#020024", endColorstr="#00fff8", GradientType=1) !important;
    }

    .fileuploader-input .fileuploader-main-icon:after {
        color: #39f !important;
    }

    .multi {
        z-index: 999999 !important;
    }

    .material-symbols-outlined {
        font-variation-settings:
            'FILL' 0,
            'wght' 300,
            'GRAD' 0,
            'opsz' 20
    }
</style>
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url; ?>">Dashboard</a></li>
            <li><a href="<?php echo $url; ?>video_deals">Video Deals Management</a></li>
            <li><span>Deal Detail</span></li>
        </ul>
    </div>
    <div id="page_content_inner">
        <div class="uk-grid" data-uk-grid-margin data-uk-grid-match id="user_profile">
            <div class="uk-width-large-10-10">

                <div class="md-card user_timeline">
                    <div class="user_heading" style="padding-bottom: 0px;">

                        <?php if (isset($dealData->deleted) && $dealData->deleted == 1) { ?>
                            <div class="deleted_deal">Contract Cancelled</div>
                        <?php } ?>

                        <div class="user_heading_avatar">

                        </div>
                        <div class="user_heading_content">
                            <h2 class="heading_b uk-margin-bottom"><span class="uk-text-truncate"><?php echo $dealData->video_title; ?></span><span class="sub-heading"><?php echo $dealData->first_name; ?><?php echo $dealData->last_name; ?></span>
                            </h2>
                        </div>
                        <style type="text/css">
                            .timeline-process ol {
                                position: relative;
                                display: inline-block;
                                margin: 0px;
                                height: 4px;
                                background: #ffffff;
                                padding: 0px;
                                width: 100%;
                            }

                            .timeline-process {
                                width: 160%;
                                float: left;
                                height: auto;
                                margin: 30px 1% 115px;
                            }

                            /* ---- Timeline elements ---- */
                            .timeline-process li {
                                position: relative;
                                display: inline-block;
                                float: left;
                                width: 8.33%;
                                font: bold 14px arial;
                                height: 10px;
                            }

                            .timeline-process li .diplome {
                                position: absolute;
                                top: -47px;
                                left: 36%;
                                color: #000000;
                            }

                            .timeline-process li .point {
                                content: "";
                                top: -10px;
                                left: 50%;
                                margin-left: -11px;
                                display: block;
                                width: 17px;
                                height: 17px;
                                border: 3px solid #ffffff;
                                border-radius: 50px;
                                background: #1976d2;
                                position: absolute;
                            }

                            .timeline-process li.active .point {
                                background-color: #FFF !important;
                            }

                            .timeline-process ol:after {
                                content: '';
                                width: 4px;
                                height: 15px;
                                background-color: #fff;
                                position: absolute;
                                top: -5px;
                                left: 0px;
                            }

                            .timeline-process ol:before {
                                content: '';
                                width: 4px;
                                height: 15px;
                                background-color: #fff;
                                position: absolute;
                                top: -5px;
                                right: 0px;
                            }

                            .description::before {
                                content: '';
                                width: 0;
                                height: 0;
                                border-left: 5px solid transparent;
                                border-right: 5px solid transparent;
                                border-bottom: 5px solid #f4f4f4;
                                position: absolute;
                                top: -5px;
                                left: 43%;
                            }

                            .timeline-process li .description {
                                display: none;
                                background-color: #f4f4f4;
                                padding: 10px;
                                margin-top: 25px;
                                position: relative !important;
                                font-weight: 600;
                                z-index: 1;
                                font-size: 12px;
                                left: 50%;
                                margin-left: -72px;
                                width: 135px;
                                text-align: center;
                                border-radius: 5px;
                                color: #444;
                            }

                            .active.last .description {
                                display: block !important;
                            }

                            .timeline-process li:nth-child(2n) .description {
                                /* margin-top: -71px !important; */
                                bottom: 85px !important;
                                /*margin-left: -79px !important;*/
                            }

                            .timeline-process li:nth-child(2n) .description::before {
                                width: 0;
                                height: 0;
                                border-left: 5px solid transparent !important;
                                border-right: 5px solid transparent !important;
                                border-top: 5px solid #f4f4f4;
                                border-bottom: unset !important;
                                top: unset !important;
                                bottom: -5px;
                            }

                            /* ---- Hover effects ---- */
                            .timeline-process li:hover {
                                cursor: pointer;
                                color: #48A4D2;
                            }

                            .timeline-process li:hover .description {
                                display: block;
                            }
                        </style>
                        <div class="timeline-process">
                            <ol class="p-st">
                                <li class="<?php if ($dealData->status == 10) {
                                                echo 'active last';
                                            } ?>">
                                    <span class="point"></span>
                                    <p class="description">Deal Converted</p>
                                </li>
                                <li class="<?php if ($dealData->status == 3 && $dealData->information_pending == 1) {
                                                echo 'active last';
                                            } ?>">
                                    <span class="point"></span>
                                    <p class="description">Information Received</p>
                                </li>
                                <li class="<?php if ($dealData->status == 6 && $videoData->video_verified == 1) {
                                                echo 'active last';
                                            } ?>">
                                    <span class="point"></span>
                                    <p class="description">Information Verified</p>
                                </li>
                                <li class="<?php if ($dealData->status == 6 && $dealData->uploaded_edited_videos == 1) {
                                                echo 'active last';
                                            } ?>">
                                    <span class="point"></span>
                                    <p class="description">Upload Edited Videos</p>
                                </li>
                                <!--published_portal-->

                                <li class="<?php if ($dealData->status == 6 && $dealData->published_yt == 1) {
                                                echo 'active last';
                                            } ?>">
                                    <span class="point"></span>
                                    <p class="description">Publish On YouTube</p>
                                </li>
                                <li class="<?php if ($dealData->status == 6 && $dealData->published_fb == 1) {
                                                echo 'active last';
                                            } ?>">
                                    <span class="point"></span>
                                    <p class="description">Publish On Facebook</p>
                                </li>
                                <li class="last <?php if ($dealData->status == 8) {
                                                    echo 'active last';
                                                } ?>">
                                    <span class="point"></span>
                                    <p class="description">Close Won</p>
                                </li>
                            </ol>
                        </div>
                        <style type="text/css">
                            .uk-tab>li.uk-active>a {
                                border-top: 4px solid #1976d2;
                                color: #1976d2;
                                border-bottom: 0px;
                            }

                            .uk-tab>li>a:focus,
                            .uk-tab>li>a:hover {
                                border-top: 4px solid #1976d2;
                                color: #1976d2;
                                border-bottom: 0px;
                            }

                            .uk-tab {
                                text-align: center;
                            }

                            .uk-tab>li {
                                margin-bottom: -1px;
                                float: none;
                                position: relative;
                                display: inline-block;
                                margin: -1px 20px;
                            }

                            .user_content {
                                padding: 30px 0px;
                            }

                            .uk-tab>li>a {
                                padding: 12px 0px !important;
                                position: relative;
                            }

                            .uk-tab>li.uk-active>a:after {
                                position: absolute;
                                left: 50%;
                                top: 0px;
                                margin-left: -5px;
                                content: '';
                                width: 0;
                                height: 0;
                                border-left: 10px solid transparent;
                                border-right: 10px solid transparent;
                                border-top: 7px solid #1976d2;
                            }

                            .uk-sticky-placeholder .uk-tab {
                                background: #fff;
                                padding-top: 0px;
                            }

                            #save-closing,
                            #cancel-closing {
                                display: none;
                            }

                            #save-revenue,
                            #cancel-revenue,
                            #cancel-des,
                            #save-des,
                            #cancel-title,
                            #save-title,
                            #cancel-title-2,
                            #save-title-2,
                            #cancel-message,
                            #save-message,
                            #cancel-ratings,
                            #save-ratings,
                            #cancel-rating-comments,
                            #save-rating-comments,
                            #cancel-facebook,
                            #save-facebook,
                            #cancel-youtube,
                            #save-youtube,
                            #cancel-tags,
                            #save-tags,
                            #cancel-raw,
                            #save-raw,
                            #cancel-editeds3,
                            #save-editeds3,
                            #cancel-thumbs3,
                            #save-thumbs3,
                            #cancel-con,
                            #save-con,
                            #save-video-comment,
                            #cancel-video-comment,
                            #save-s3-doc,
                            #cancel-s3-doc,
                            #save-q1,
                            #cancel-q1,
                            #save-q2,
                            #cancel-q2,
                            #save-q3,
                            #cancel-q3,
                            #save-seo-targets,
                            #cancel-seo-targets {
                                display: none;
                            }

                            .selectize-dropdown {
                                margin-top: 0px;
                            }

                            .selectize-control.plugin-remove_button [data-value] .remove:after {
                                content: '' !important;
                            }

                            .selectize-control.plugin-remove_button [data-value] .remove {
                                padding: 0px 10px 0 0 !important;
                                font-size: 10px;
                                top: -2px;
                            }

                            .selectize-control.plugin-remove_button [data-value] {
                                padding-right: 24px !important;
                            }

                            .user_content ul#user_profile_tabs_content .btn-edit {
                                position: absolute;
                                right: 0;
                                top: 10px;
                                border-radius: 50%;
                                width: 28px;
                                height: 28px;
                                border: 0;
                                cursor: pointer;
                            }

                            .btn-save {
                                border-radius: 50%;
                                border: 0;
                                width: 28px;
                                height: 28px;
                                background-color: #4CAF50;
                                cursor: pointer;
                            }

                            .btn-save i {
                                color: white;
                                width: 18px;
                                height: 18px;
                                margin-left: -1px;
                            }

                            .btn-cancel {
                                border-radius: 50%;
                                border: 0;
                                width: 28px;
                                height: 28px;
                                background-color: #f44336;
                                cursor: pointer;
                            }

                            .btn-cancel i {
                                color: white;
                                width: 18px;
                                height: 18px;
                                margin-left: -1px;
                            }

                            <?php if ((isset($videoData->id) && $videoData->id > 0)) { ?>.user_timeline #user_profile_tabs li {
                                width: calc(14% - 51px) !important;
                            }

                            <?php } ?>.check_box {
                                width: 13px;
                                height: 13px;
                                padding: 0;
                                margin: 0;
                                position: relative;
                                overflow: hidden;
                            }

                            .deal_checks {
                                display: flex;
                                justify-content: space-between;
                            }

                            .cn-ve-modal {
                                width: 400px;
                                margin: auto;
                            }

                            .comm-att-modal {
                                width: 50%;
                                height: 30%;
                                margin: auto;
                            }

                            .custom_selector {
                                width: 50%;
                                height: 30px;
                                border: none;
                                border-bottom: 1px solid gray;
                                background: transparent;
                                margin-bottom: 20px;
                                font-family: inherit;
                                color: #1976D2;
                                font-size: 11pt;
                            }

                            .custom_selector option {
                                padding: 20px !important;
                            }

                            .custom-file-input::-webkit-file-upload-button {
                                visibility: hidden;
                            }

                            .custom-file-input::before {
                                content: 'Click To Select Files';
                                display: inline-block;
                                background-color: #eee;
                                padding: 15% 0;
                                outline: none;
                                white-space: nowrap;
                                -webkit-user-select: none;
                                cursor: pointer;
                                text-shadow: 1px 1px #fff;
                                font-weight: 700;
                                font-size: 12pt;
                                width: 100%;
                                height: 100%;
                                text-align: center;
                            }

                            .custom-file-input:hover::before {
                                background-color: #fff;
                            }

                            .custom-file-input:active::before {
                                background: -webkit-linear-gradient(top, #e3e3e3, #f9f9f9);
                            }

                            .att_upload_btn {
                                width: 20%;
                                margin: 1% 40%;
                                height: 12%;
                                max-height: 12%;
                                position: absolute;
                                bottom: 24px;
                                left: 0;
                            }

                            .att_upload_form {
                                outline: 0;
                                text-decoration: none;
                                background: linear-gradient(#2196f3, #456);
                                box-shadow: 0 3px 6px rgba(0, 0, 0, .16), 0 3px 6px rgba(0, 0, 0, .23);
                                height: 100%;
                                width: 100%;
                                text-align: center;
                                text-shadow: none;
                                text-transform: uppercase;
                                -webkit-transition: all 280ms ease;
                                transition: all 280ms ease;
                                -webkit-box-sizing: border-box;
                                box-sizing: border-box;
                                cursor: pointer;
                                -webkit-appearance: none;
                                display: inline-block;
                                vertical-align: middle;
                                font: 500 14px/31px Roboto, sans-serif !important;
                                border-radius: 2px;
                            }

                            .comm_att {
                                width: 35%;
                                height: 150px;
                                margin-top: 62px;
                                margin-bottom: 56px;
                                overflow-y: auto;
                                float: right;
                                padding-left: 2%;
                            }

                            .comm_att table {
                                font-family: arial, sans-serif;
                                border-collapse: collapse;
                                max-height: 150px;
                                width: 100%;
                                text-align: left;
                            }

                            .comm_att table th {
                                background-color: #808080;
                                color: white;
                                padding: 8px;
                                position: sticky;
                                top: 0;
                                height: 20px;
                            }

                            .comm_att table td {
                                height: 15px;
                                text-indent: 10px;
                            }

                            .comm_att tr:nth-child(even) {
                                background-color: #eee;
                            }

                            .comm_att tr:nth-child(odd) {
                                background-color: #ddd;
                            }

                            .multi-selecter .selectize-input {
                                border-width: 0px 0px 1px;
                                padding: 8px 8px 9px;
                            }

                            /* RePaint css */
                            .chat-input-container {
                                display: flex;
                                align-items: center;
                                background-color: #f5f5f5;
                                padding: 10px;
                                width: 100%;
                            }

                            .chat-input-container input {
                                flex-grow: 1;
                                padding: 8px;
                                border: none;
                                border-radius: 5px;
                                font-size: 16px;
                                font-family: Söhne, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif, Helvetica Neue, Arial, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
                            }

                            #repaint-send-button {
                                margin-left: 10px;
                                padding: 8px 16px;
                                background-color: transparent;
                                border: none;
                                font-size: 16px;
                                font-family: Söhne, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif, Helvetica Neue, Arial, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
                                cursor: pointer;
                            }

                            #repaint-text {
                                height: 45px;
                                min-height: 45px;
                                resize: vertical;
                                overflow: auto;
                            }
                        </style>
                        <div class="sub-scrum">
                            <div class="open-grid" style="position:relative; z-index:11;">
                                <!-- view contract -->
                                <?php if ($assess['can_view_contract']) { ?>
                                    <?php if ($dealData->status >= 6 && $dealData->status <= 8) { ?>
                                        <?php if ($assess['can_view_contract']) { ?>
                                            <div class="sub-grid">
                                                <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url . 'view_contract/' . $dealData->id; ?>" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light" target="_blank" title="View Contract"><i class="material-icons">receipt</i></a>
                                                </p>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                <!-- view contract -->
                                <!--view dropbox button-->
                                <?php if ($assess['can_view_contract']) { ?>
                                    <?php if ($dealData->status >= 6 && $dealData->status <= 8 && $videoData->video_verified == 1) { ?>
                                        <?php if ($assess['can_view_contract']) { ?>
                                            <div class="sub-grid">
                                                <p class="scrum_task_info" style="text-align: right;">
                                                    <a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light upload_dropbox" data-id="<?php echo $dealData->unique_key ?>" data-title="<?php echo $dealData->video_title ?>" title="Upload to Dropbox">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="-100 -100 700 700">
                                                            <path style="fill:white" d="M264.4 116.3l-132 84.3 132 84.3-132 84.3L0 284.1l132.3-84.3L0 116.3 132.3 32l132.1 84.3zM131.6 395.7l132-84.3 132 84.3-132 84.3-132-84.3zm132.8-111.6l132-84.3-132-83.6L395.7 32 528 116.3l-132.3 84.3L528 284.8l-132.3 84.3-131.3-85z" />
                                                        </svg>
                                                    </a>
                                                </p>
                                            </div>
                                        <?php } ?>

                                    <?php } ?>
                                <?php } ?>
                                <!--view dropbox button-->


                                <?php if ($dealData->status == 11) { ?>
                                    <?php if ($assess['not_interested']) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;">
                                                <a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light undo-not-interested" data-title="Deals" data-id="<?php echo $dealData->id; ?>" title="Remove From Not Interested"><i class="material-icons">not_interested</i></a>
                                            </p>
                                        </div>
                                    <?php } ?>
                                <?php } else if ($dealData->status == 10) { ?>
                                    <?php if ($assess['can_send_email'] and $dealData->reminder_sent == 1) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;">
                                                <a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light send_reminder_email" id="" title="Send Reminder Email" data-email="<?php echo $dealData->email; ?>" data-id="<?php echo $dealData->id; ?>"><i class="material-icons">add_alert</i></a>
                                            </p>
                                        </div>
                                    <?php } ?>
                                    <?php if ($assess['not_interested']) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;">
                                                <a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light not-interested" data-title="Deals" data-id="<?php echo $dealData->id; ?>" title="Move To Not Interested"><i class="material-icons">not_interested</i></a>
                                            </p>
                                        </div>
                                    <?php } ?>
                                    <?php if ($assess['can_revenue_update']) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;">
                                                <a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light revenue-update" data-revenue="<?php echo $dealData->revenue_share; ?>" data-title="<?php echo $dealData->video_title; ?>" data-id="<?php echo $dealData->id; ?>" data-sent="1" title="Update Revenue"><i class="material-icons">edit</i></a>
                                            </p>
                                        </div>
                                    <?php } ?>
                                    <?php } else if ($dealData->status == 3 && $dealData->information_pending == 1) {
                                    if ($dealData->load_view == 4) {
                                        foreach ($rawVideos->result_array() as $raw) {
                                            if (isset($raw['url'])) {
                                                $raw_url = $raw['url'];
                                            }
                                        }
                                        if (!empty($raw['client_link']) && empty($raw_url) && $dealData->client_id != 0) {
                                    ?>
                                            <div class="sub-grid">
                                                <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light upload_user_video" data-id="<?php echo $dealData->id; ?>" title="Upload User video"><i class="material-icons">cloud_upload</i></a></p>
                                            </div>
                                            <div class="sub-grid">
                                                <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light reject-videolead" data-id="<?php echo $dealData->id; ?>" data-videoid="<?php echo $videoData->id; ?>" title="Reject Videos"><i class="material-icons">eject</i></a>
                                                </p>
                                            </div>
                                        <?php  } elseif ($dealData->client_id == 0) {

                                        ?>
                                            <?php if ($assess['can_client_add']) { ?>
                                                <div class="sub-grid">
                                                    <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light add" data-id="<?php echo $dealData->id; ?>" data-name="<?php echo $dealData->first_name . ' ' . $dealData->last_name; ?>" data-email="<?php echo $dealData->email; ?>" title="Account Create"><i class="material-icons">person_add</i></a>
                                                    </p>
                                                </div>
                                            <?php }
                                        } else {
                                            ?>
                                            <?php if ($assess['verify']) { ?>
                                                <div class="sub-grid">
                                                    <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url ?>edit_video/<?php echo $videoData->id; ?>" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $videoData->id; ?>" title="Verify Video"><i class="material-icons">verified_user</i></a></p>
                                                </div>
                                                <div class="sub-grid">
                                                    <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light reject-videolead" data-id="<?php echo $dealData->id; ?>" data-videoid="<?php echo $videoData->id; ?>" title="Reject Videos"><i class="material-icons">eject</i></a>
                                                    </p>
                                                </div>
                                    <?php
                                            }
                                        }
                                    }

                                    ?>
                                    <?php if ($assess['not_interested']) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light delete-videolead" data-title="Deal Information Received" data-id="<?php echo $dealData->id; ?>" title="Cancel Contract"><i class="material-icons">not_interested</i></a></p>
                                        </div>
                                    <?php } ?>
                                <?php } else if ($dealData->status == 6 && $videoData->video_verified == 1 && $dealData->uploaded_edited_videos == 0) { ?>
                                    <?php if ($assess['can_upload_edited_videos']) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url ?>upload_edited_video/<?php echo $videoData->id; ?>" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $videoData->id; ?>" title="Upload Video"><i class="material-icons">cloud_upload</i></a></p>
                                        </div>
                                    <?php } ?>
                                    <?php if ($assess['not_interested']) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light delete-videolead" data-title="Deal Information Received" data-id="<?php echo $dealData->id; ?>" title="Cancel Contract"><i class="material-icons">not_interested</i></a></p>
                                        </div>
                                    <?php } ?>

                                    <?php if ($assess['can_download_raw_files']) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url . 'download-raw-files/' . $videoData->id; ?>" vid="<?php echo $videoData->id; ?>" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light download-raw-files" title="Download Raw Files"><i class="material-icons">cloud_download</i></a>
                                            </p>
                                        </div>
                                    <?php } ?>
                                <?php } else if ($dealData->status == 6 && $dealData->uploaded_edited_videos == 1) { ?>
                                    <?php if ($assess['can_upload_edited_videos']) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url ?>upload_edited_video/<?php echo $videoData->id; ?>" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $videoData->id; ?>" title="Upload Video"><i class="material-icons">cloud_upload</i></a></p>
                                        </div>
                                    <?php } ?>
                                    <?php if ($assess['verify']) {
                                        $data_url_yt = '';
                                        $color_code_yt = '';
                                        $editedVideos = $editedVideo->result_array();
                                        /* if ($editedVideos[0]['yt_url'] == '') {
                                             //echo "video not uploaded and show grey icon with link to upload";
                                             $color_code_yt = 'edited_file_missing';
                                             $yt_anchor_link = $url . 'upload_edited_video/' . $videoData->id;
                                             $data_url_yt = '';
                                         } else {*/
                                        if ($dealData->published_yt == 1 and $videoData->youtube_repub == 0) {
                                            //echo "video published and not to republish. Show gren icon without link";
                                            $color_code_yt = 'edited_video_published';
                                            $data_url_yt = '';
                                            $yt_anchor_link = '#';
                                        } else if ($dealData->published_yt == 0 and $videoData->youtube_repub == 0 and (isset($editedVideos[0]) and $editedVideos[0]['yt_url'] == '')) {
                                            //echo "video not uploaded and not publish manually and show grey icon with link to upload";
                                            $color_code_yt = 'edited_file_missing';
                                            $yt_anchor_link = $url . 'upload_edited_video/' . $videoData->id;
                                            $data_url_yt = '';
                                        } else if ($dealData->published_yt == 0 || $videoData->youtube_repub == 1) {
                                            //echo "video is either not published or needs to republish. Show blue icon with link to publish";
                                            $color_code_yt = 'distribute';
                                            $yt_anchor_link = '#';
                                            $data_url_yt = 'publish-youtube';
                                        } else if ($editedVideos[0]['yt_url'] == '') {
                                            //echo "video not uploaded and show grey icon with link to upload";
                                            $color_code_yt = 'edited_file_missing';
                                            $yt_anchor_link = $url . 'upload_edited_video/' . $videoData->id;
                                            $data_url_yt = '';
                                        }
                                        /* }*/
                                        $data_url_fb = 'publish-facebook';
                                        /* if ($editedVideos[0]['fb_url'] == '') {
                                             //echo "video not uploaded and show grey icon with link to upload";
                                             $color_code_class = 'edited_file_missing';
                                             $fb_anchor_link = $url . 'upload_edited_video/' . $videoData->id;
                                             $data_url_fb = '';
                                         } else {*/
                                        if ($dealData->published_fb == 1 and $videoData->facebook_repub == 0) {
                                            //echo "video published and not to republish. Show gren icon without link";
                                            $color_code_class = 'edited_video_published';
                                            $data_url_fb = '';
                                            $fb_anchor_link = '#';
                                        } else if ($dealData->published_fb == 0 and $videoData->facebook_repub == 0 and (isset($editedVideos[0]) and $editedVideos[0]['fb_url'] == '')) {
                                            //echo "video not uploaded and not publish manually and show grey icon with link to upload";
                                            $color_code_class = 'edited_file_missing';
                                            $fb_anchor_link = $url . 'upload_edited_video/' . $videoData->id;
                                            $data_url_fb = '';
                                        } else if ($dealData->published_fb == 0 || $videoData->facebook_repub == 1) {
                                            //echo "video is either not published or needs to republish. Show blue icon with link to publish";
                                            $color_code_class = 'distribute';
                                            $fb_anchor_link = '#';
                                        } else if ($editedVideos[0]['fb_url'] == '') {
                                            //echo "video not uploaded and show grey icon with link to upload";
                                            $color_code_class = 'edited_file_missing';
                                            $fb_anchor_link = $url . 'upload_edited_video/' . $videoData->id;
                                            $data_url_fb = '';
                                        }
                                        /*}*/
                                    ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info"><a href="<?php echo $yt_anchor_link; ?>" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light <?php echo $color_code_yt ?>" data-id="<?php echo $videoData->id; ?>" data-url="<?php echo $data_url_yt; ?>" data-wgid="<?php echo $dealData->unique_key; ?>"><i class="uk-icon-medium"><img src="//uat.technoventive.com/admin/assets/assets/icons/youtube_social_circle_red.png" /></i></a></p>
                                        </div>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info"><a href="<?php echo $fb_anchor_link; ?>" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light <?php echo $color_code_class ?>" data-id="<?php echo $videoData->id; ?>" data-url="<?php echo $data_url_fb; ?>"><i class="uk-icon-facebook uk-icon-medium"></i></a></p>
                                        </div><?php

                                                /* if ($dealData->published_yt == 0 || $videoData->youtube_repub == 1) { */ ?><!--
                                            <div class="sub-grid">
                                                <p class="scrum_task_info"><a href=""
                                                                              class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light distribute"
                                                                              data-id="<?php /*echo $videoData->id; */ ?>"
                                                                              data-url="publish-youtube"><i
                                                                class="uk-icon-youtube uk-icon-medium"></i></a></p>
                                            </div>
                                        <?php /*} */ ?>
                                        <?php /*if ($dealData->published_fb == 0 || $videoData->facebook_repub == 1) { */ ?>
                                            <div class="sub-grid">
                                                <p class="scrum_task_info"><a href=""
                                                                              class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light distribute"
                                                                              data-id="<?php /*echo $videoData->id; */ ?>"
                                                                              data-url="publish-facebook"><i
                                                                class="uk-icon-facebook uk-icon-medium"></i></a></p>
                                            </div>
                                        --><?php /*} */ ?>
                                        <?php if ($assess['not_interested']) { ?>
                                            <div class="sub-grid">
                                                <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light delete-videolead" data-title="Deal Information Received" data-id="<?php echo $dealData->id; ?>" title="Cancel Contract"><i class="material-icons">not_interested</i></a>
                                                </p>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } else if ($dealData->status == 8) { ?>
                                    <div class="sub-grid">
                                        <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url ?>upload_edited_video/<?php echo $videoData->id; ?>" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $videoData->id; ?>" title="Upload Video"><i class="material-icons">cloud_upload</i></a></p>
                                    </div>
                                    <?php if ($videoData->youtube_repub == 1) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info"><a href="" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light distribute" data-id="<?php echo $videoData->id; ?>" data-url="publish-youtube"><i class="uk-icon-medium"><img src="//uat.technoventive.com/admin/assets/assets/icons/youtube_social_circle_red.png" /></i></a></p>
                                        </div>
                                    <?php } ?>
                                    <?php if ($videoData->facebook_repub == 1) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info"><a href="" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light distribute" data-id="<?php echo $videoData->id; ?>" data-url="publish-facebook"><i class="uk-icon-facebook uk-icon-medium"></i></a></p>
                                        </div>
                                    <?php } ?>
                                    <?php if ($assess['not_interested']) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light delete-videolead" data-title="Deal Information Received" data-id="<?php echo $dealData->id; ?>" title="Cancel Contract"><i class="material-icons">not_interested</i></a></p>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($assess['can_verify']) { ?>
                                    <?php if ((isset($videoData->id) && $videoData->id > 0)) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url ?>edit_video/<?php echo $videoData->id; ?>" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light" title="Edit Video">
                                                    <i class="material-icons">edit</i></a></p>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <?php if ($assess['can_info']) { ?>
                                    <?php if ((isset($dealData->status) && $dealData->status >= 3)) { ?>
                                        <div class="sub-grid">
                                            <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light video_info" title="Video Info">
                                                    <i class="material-icons">info</i></a></p>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                            <br />
                        </div>


                    </div>
                    <div>
                        <ul id="user_profile_tabs" class="uk-tab" data-uk-tab="{connect:'#user_profile_tabs_content', animation:'slide-horizontal'}" data-uk-sticky="{ top: 48, media: 960 }">
                            <?php if ($assess['can_deal_information']) { ?> <li class="uk-active"><a href="#">Deal<br>Info</a></li> <?php } ?>
                            <?php if ($assess['can_deal_information']) { ?> <li><a href="#">Deal<br>Comments</a></li> <?php } ?>
                            <?php if ($assess['can_deal_corresponding_email']) { ?> <li><a href="#">Deal<br>Emails</a></li> <?php } ?>
                            <?php if ($assess['can_overall_email']) { ?> <li><a href="#">Overall<br>Emails</a></li> <?php } ?>
                            <?php if ($assess['can_video_insights']) { ?> <li><a href="#">Video<br>Insights</a></li> <?php } ?>
                            <?php if ($assess['earnings_list'] || $assess['expense_list'] || $assess['can_deal_payment']) { ?>
                                <?php if (isset($videoData->id) && $videoData->id > 0) { ?>
                                    <li><a href="#">Deal<br>Payments</a></li>
                                <?php } ?>
                            <?php } ?>
                            <?php if ($assess['can_video_story']) { ?> <li><a href="#">Story<br>Content</a></li> <?php } ?>
                        </ul>
                    </div>

                </div>
                <div class="user-video-container">
                    <?php $finalUrl = '';
                    $instaUrl = 0;
                    $twitterUrl = 0;
                    $fbUrl = 0;

                    //it is FB video
                    $valid_url_match = true;
                    if (strpos($dealData->video_url, 'facebook.com/') !== false) {
                        $fbUrl = 1;

                        $valid_fb_video_url_match = preg_match("~/videos/(?:t\.\d+/)?(\d+)~i", $dealData->video_url);

                        //it is FB video
                        if (strpos(rawurlencode($dealData->video_url), 'posts') !== false) {
                            $finalUrl = $dealData->video_url . '&show_text=1&width=200';
                        } else {
                            $finalUrl = 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($dealData->video_url) . '&show_text=1&width=200';
                        }
                    } else if (strpos($dealData->video_url, 'vimeo.com/') !== false) {
                        //it is Vimeo video
                        $videoId = explode("vimeo.com/", $dealData->video_url);
                        $videoId = $videoId[1];
                        if (strpos($videoId, '&') !== false) {
                            $videoId = explode("&", $videoId);
                            $videoId = $videoId[0];
                        }
                        $finalUrl = 'https://player.vimeo.com/video/' . $videoId;
                    } else if (strpos($dealData->video_url, 'youtube.com/') !== false) {
                        //it is Youtube video
                        $videoId = explode("v=", $dealData->video_url);
                        $videoId = $videoId[1];
                        if (strpos($videoId, '&') !== false) {
                            $videoId = explode("&", $videoId);
                            $videoId = $videoId[0];
                        }
                        $finalUrl = 'https://www.youtube.com/embed/' . $videoId;
                    } else if (strpos($dealData->video_url, 'youtu.be/') !== false) {
                        //it is Youtube video
                        $videoId = explode("youtu.be/", $dealData->video_url);
                        $videoId = $videoId[1];
                        if (strpos($videoId, '&') !== false) {
                            $videoId = explode("&", $videoId);
                            $videoId = $videoId[0];
                        }
                        $finalUrl = 'https://www.youtube.com/embed/' . $videoId;
                    } else if (strpos($dealData->video_url, 'instagram.com/') !== false) {
                        $instaUrl = 1;
                        $code = explode("/", $dealData->video_url)[4];
                        $finalUrl = 'https://www.instagram.com/p/' . $code . '/';
                    } // twitter start
                    else if (strpos($dealData->video_url, 'twitter.com/') !== false) {
                        $twitterUrl = 1;
                        $finalUrl = $dealData->video_url;
                    } // twitter end
                    else {
                        $finalUrl = $dealData->video_url;
                    } ?>
                    <?php
                    if ($instaUrl == 0 && $twitterUrl == 0) {
                        if ($fbUrl) :
                            if (isset($valid_fb_video_url_match) && $valid_fb_video_url_match) : // valid fb url match
                    ?>
                                <iframe id="iframe-video" src="<?php echo $finalUrl; ?>" frameborder="0" allowfullscreen style="height: 42vh;width:100%;"></iframe>
                            <?php
                            else :
                            ?>
                                <div style="background-color: black; min-height: 393px; border: thin solid black;border-radius: 10px;">
                                    <span>
                                        <h2 style="color:white;margin-top:30%;margin-left:20px">The video link is either invlaid or could not be loaded</h2>
                                    </span>
                                </div>
                            <?php
                            endif; // if (isset($valid_fb_video_url_match) && $valid_fb_video_url_match)
                        else : // if ($fbUrl):
                            ?>
                            <iframe id="iframe-video" src="<?php echo $finalUrl; ?>" frameborder="0" allowfullscreen style="height: 42vh;width:100%;"></iframe>
                        <?php
                        endif;
                        ?>
                        <?php
                    } // if ($instaUrl == 0 && $twitterUrl == 0)
                    elseif ($twitterUrl == 1) {
                        $valid_twitter_url_match = preg_match("~/status/(?:t\.\d+/)?(\d+)~i", $dealData->video_url);

                        if ($valid_twitter_url_match) :
                        ?>
                            <blockquote class="twitter-tweet" data-lang="en">
                                <a href="<?php echo $finalUrl; ?>"></a>
                            </blockquote>
                            <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
                        <?php
                        else : //else if ($valid_twitter_url_match)
                        ?>
                            <div style="background-color: black; min-height: 393px; border: thin solid black;border-radius: 10px;">
                                <span>
                                    <h2 style="color:white;margin-top:30%;margin-left:20px">The video link is either invlaid or could not be loaded</h2>
                                </span>
                            </div>
                        <?php
                        endif; // endif ($valid_twitter_url_match)
                    } // ($twitterUrl == 1)
                    else { // else if ($instaUrl == 0 && $twitterUrl == 0)
                        ?>
                        <blockquote class="instagram-media" data-instgrm-captioned data-instgrm-permalink="<?php echo $finalUrl; ?>?utm_source=ig_embed&amp;utm_medium=loading" data-instgrm-version="12" style=" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:540px; min-width:326px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);">
                            <div style="padding:16px;"><a href="https://www.instagram.com/p/Bq_f_A0ASCV/?utm_source=ig_embed&amp;utm_medium=loading" style=" background:#FFFFFF; line-height:0; padding:0 0; text-align:center; text-decoration:none; width:100%;" target="_blank">
                                    <div style=" display: flex; flex-direction: row; align-items: center;">
                                        <div style="background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 40px; margin-right: 14px; width: 40px;"></div>
                                        <div style="display: flex; flex-direction: column; flex-grow: 1; justify-content: center;">
                                            <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; margin-bottom: 6px; width: 100px;"></div>
                                            <div style=" background-color: #F4F4F4; border-radius: 4px; flex-grow: 0; height: 14px; width: 60px;"></div>
                                        </div>
                                    </div>
                                    <div style="padding: 19% 0;"></div>
                                    <div style="display:block; height:50px; margin:0 auto 12px; width:50px;">
                                        <svg width="50px" height="50px" viewBox="0 0 60 60" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <g transform="translate(-511.000000, -20.000000)" fill="#000000">
                                                    <g>
                                                        <path d="M556.869,30.41 C554.814,30.41 553.148,32.076 553.148,34.131 C553.148,36.186 554.814,37.852 556.869,37.852 C558.924,37.852 560.59,36.186 560.59,34.131 C560.59,32.076 558.924,30.41 556.869,30.41 M541,60.657 C535.114,60.657 530.342,55.887 530.342,50 C530.342,44.114 535.114,39.342 541,39.342 C546.887,39.342 551.658,44.114 551.658,50 C551.658,55.887 546.887,60.657 541,60.657 M541,33.886 C532.1,33.886 524.886,41.1 524.886,50 C524.886,58.899 532.1,66.113 541,66.113 C549.9,66.113 557.115,58.899 557.115,50 C557.115,41.1 549.9,33.886 541,33.886 M565.378,62.101 C565.244,65.022 564.756,66.606 564.346,67.663 C563.803,69.06 563.154,70.057 562.106,71.106 C561.058,72.155 560.06,72.803 558.662,73.347 C557.607,73.757 556.021,74.244 553.102,74.378 C549.944,74.521 548.997,74.552 541,74.552 C533.003,74.552 532.056,74.521 528.898,74.378 C525.979,74.244 524.393,73.757 523.338,73.347 C521.94,72.803 520.942,72.155 519.894,71.106 C518.846,70.057 518.197,69.06 517.654,67.663 C517.244,66.606 516.755,65.022 516.623,62.101 C516.479,58.943 516.448,57.996 516.448,50 C516.448,42.003 516.479,41.056 516.623,37.899 C516.755,34.978 517.244,33.391 517.654,32.338 C518.197,30.938 518.846,29.942 519.894,28.894 C520.942,27.846 521.94,27.196 523.338,26.654 C524.393,26.244 525.979,25.756 528.898,25.623 C532.057,25.479 533.004,25.448 541,25.448 C548.997,25.448 549.943,25.479 553.102,25.623 C556.021,25.756 557.607,26.244 558.662,26.654 C560.06,27.196 561.058,27.846 562.106,28.894 C563.154,29.942 563.803,30.938 564.346,32.338 C564.756,33.391 565.244,34.978 565.378,37.899 C565.522,41.056 565.552,42.003 565.552,50 C565.552,57.996 565.522,58.943 565.378,62.101 M570.82,37.631 C570.674,34.438 570.167,32.258 569.425,30.349 C568.659,28.377 567.633,26.702 565.965,25.035 C564.297,23.368 562.623,22.342 560.652,21.575 C558.743,20.834 556.562,20.326 553.369,20.18 C550.169,20.033 549.148,20 541,20 C532.853,20 531.831,20.033 528.631,20.18 C525.438,20.326 523.257,20.834 521.349,21.575 C519.376,22.342 517.703,23.368 516.035,25.035 C514.368,26.702 513.342,28.377 512.574,30.349 C511.834,32.258 511.326,34.438 511.181,37.631 C511.035,40.831 511,41.851 511,50 C511,58.147 511.035,59.17 511.181,62.369 C511.326,65.562 511.834,67.743 512.574,69.651 C513.342,71.625 514.368,73.296 516.035,74.965 C517.703,76.634 519.376,77.658 521.349,78.425 C523.257,79.167 525.438,79.673 528.631,79.82 C531.831,79.965 532.853,80.001 541,80.001 C549.148,80.001 550.169,79.965 553.369,79.82 C556.562,79.673 558.743,79.167 560.652,78.425 C562.623,77.658 564.297,76.634 565.965,74.965 C567.633,73.296 568.659,71.625 569.425,69.651 C570.167,67.743 570.674,65.562 570.82,62.369 C570.966,59.17 571,58.147 571,50 C571,41.851 570.966,40.831 570.82,37.631"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </div>
                                    <div style="padding-top: 8px;">
                                        <div style=" color:#3897f0; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:550; line-height:18px;">
                                            View this post on Instagram
                                        </div>
                                    </div>
                                    <div style="padding: 12.5% 0;"></div>
                                    <div style="display: flex; flex-direction: row; margin-bottom: 14px; align-items: center;">
                                        <div>
                                            <div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(0px) translateY(7px);"></div>
                                            <div style="background-color: #F4F4F4; height: 12.5px; transform: rotate(-45deg) translateX(3px) translateY(1px); width: 12.5px; flex-grow: 0; margin-right: 14px; margin-left: 2px;"></div>
                                            <div style="background-color: #F4F4F4; border-radius: 50%; height: 12.5px; width: 12.5px; transform: translateX(9px) translateY(-18px);"></div>
                                        </div>
                                        <div style="margin-left: 8px;">
                                            <div style=" background-color: #F4F4F4; border-radius: 50%; flex-grow: 0; height: 20px; width: 20px;"></div>
                                            <div style=" width: 0; height: 0; border-top: 2px solid transparent; border-left: 6px solid #f4f4f4; border-bottom: 2px solid transparent; transform: translateX(16px) translateY(-4px) rotate(30deg)"></div>
                                        </div>
                                        <div style="margin-left: auto;">
                                            <div style=" width: 0px; border-top: 8px solid #F4F4F4; border-right: 8px solid transparent; transform: translateY(16px);"></div>
                                            <div style=" background-color: #F4F4F4; flex-grow: 0; height: 12px; width: 16px; transform: translateY(-4px);"></div>
                                            <div style=" width: 0; height: 0; border-top: 8px solid #F4F4F4; border-left: 8px solid transparent; transform: translateY(-4px) translateX(8px);"></div>
                                        </div>
                                    </div>
                                </a>
                                <p style=" margin:8px 0 0 0; padding:0 4px;"><a href="https://www.instagram.com/p/Bq_f_A0ASCV/?utm_source=ig_embed&amp;utm_medium=loading" style=" color:#000; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;" target="_blank">What a lucky man😂...#memes #meme #memesdaily #dankmemes
                                        #funny #memelord #memer #dank #lmao #funnymemes #memestagram #dankmeme
                                        #edgymemes #lol #memepage #memez #edgy #memed #memevideo #memestar #memesrlife
                                        #memeteam #haha #memeaccount #memebox #funnyvideos #memebase #memeo #memestgram
                                        #comedy</a></p>
                                <p style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;">
                                    A post shared by <a href="https://www.instagram.com/tubular.tv/?utm_source=ig_embed&amp;utm_medium=loading" style=" color:#c9c8cd; font-family:Arial,sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px;" target="_blank"> Memes</a> (@tubular.tv) on
                                    <time style=" font-family:Arial,sans-serif; font-size:14px; line-height:17px;" datetime="2018-12-05T04:26:58+00:00">Dec 4, 2018 at 8:26pm PST
                                    </time>
                                </p>
                            </div>
                        </blockquote>
                        <script async src="//www.instagram.com/embed.js"></script>
                    <?php } ?>
                </div>

                <div class="user_content">
                    <!-- Model -->
                    <div class="uk-modal" id="repaint_model">
                        <div class="uk-modal-dialog">
                            <div class="uk-modal-header">
                                <h2 class="uk-modal-title">Re paint text</h2>
                            </div>
                            <div class="uk-modal-body">
                                <div class="form-floating uk-flex uk-flex-center uk-flex-middle uk-width-1-1 uk-flex-column" style="gap:10px;">
                                    <div class="uk-width-1-1 uk-position-relative">
                                        <!-- Autocomplete input field -->
                                        <div class="uk-autocomplete" style="position:absolute;right:0px;top:-20px;">
                                            <input class="uk-width-1-1" id="repaint-language" type="text" value="English" placeholder="Search..." />
                                        </div>
                                        <div class="autocomplete-container">
                                            <textarea class="form-control" readonly id="repaint-reponse" placeholder="Type your text here..." style="min-height: 100px; resize: vertical; width: 100%; background-color: #f5f5f5; color: black; letter-spacing: 1px; font-family: Söhne, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, sans-serif, Helvetica Neue, Arial, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji"></textarea>
                                            <button class="uk-position-top-right" style="border:none; background-color:transparent; margin-top:4px">
                                                <span class="uk-icon-copy uk-icon-large" onclick="CopyPainText()"></span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="chat-input-container">
                                        <textarea id="repaint-text" type="text" placeholder="Type your message..."></textarea>
                                        <button id="repaint-send-button" onclick="RepaintWithGPT(this)"><i class="uk-icon-send"></i></button>
                                    </div>
                                </div>
                            </div>



                            <div class="uk-modal-footer uk-text-right" style="margin-top:20px; ">
                                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                            </div>
                        </div>
                    </div>
                    <ul id="user_profile_tabs_content" class="uk-switcher">
                        <?php if ($assess['can_deal_information']) { ?> <li>
                                <div class="md-card">
                                    <div class="md-card-content">
                                        <?php if ($assess['can_deal_report'] && !in_array($dealData->status, [6, 7, 8])) { ?>
                                            <div class="uk-modal" id="deal_reporting_model">
                                                <div class="uk-modal-dialog">
                                                    <div class="uk-modal-header">
                                                        <h3 class="uk-modal-title">Raise Issue</h3>
                                                    </div>
                                                    <div class="md-card-content large-padding">
                                                        <form id="form_validation30" class="uk-form-stacked">
                                                            <input type="hidden" name="lead_id" value="<?php echo $dealData->id; ?>">
                                                            <div class="uk-grid" data-uk-grid-margin>
                                                                <div class="uk-width-medium-1-1">
                                                                    <label for="partner_currency">Issue Type</label>
                                                                    <div class="parsley-row">
                                                                        <select id="report_issue_type " name="report_issue_type[]" required data-parsley-required-message="This field is required." data-md-selectize multiple>
                                                                            <option value="">Issue Type</option>
                                                                            <option <?php if (in_array('Incorrect Video File Submitted', explode(',', $dealData->report_issue_type))) {
                                                                                        echo "selected";
                                                                                    } ?> value="Incorrect Video File Submitted">Incorrect Video File Submitted</option>
                                                                            <option <?php if (in_array('Low Quality / Watermark / Caption Issue', explode(',', $dealData->report_issue_type))) {
                                                                                        echo "selected";
                                                                                    } ?> value="Low Quality / Watermark / Caption Issue">Low Quality / Watermark / Caption Issue</option>
                                                                            <option <?php if (in_array('Permission Required from Recorder', explode(',', $dealData->report_issue_type))) {
                                                                                        echo "selected";
                                                                                    } ?> value="Permission Required from Recorder">Permission Required from Recorder</option>
                                                                            <option <?php if (in_array('Appearance Release Required', explode(',', $dealData->report_issue_type))) {
                                                                                        echo "selected";
                                                                                    } ?> value="Appearance Release Required">Appearance Release Required</option>
                                                                        </select>
                                                                        <div id="report_issue_type_err" class="error"></div>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div class="uk-grid" data-uk-grid-margin>
                                                                <div class="uk-width-medium-1-1 uk-row-first">
                                                                    <div class="parsley-row">
                                                                        <div class="md-input-wrapper">
                                                                            <label for="report_issue_desc" class="uk-form-label">Detail</label>
                                                                            <textarea id="report_issue_desc" name="report_issue_desc" class="md-input" data-parsley-required-message="This field is required." required></textarea>
                                                                            <span class="md-input-bar "></span>
                                                                        </div>
                                                                        <div id="report_issue_desc_err" class="error"></div>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                        </form>
                                                    </div>
                                                    <div class="uk-modal-footer uk-text-right" style="margin-top:20px; ">
                                                        <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="report-bug-ajax" class="md-btn md-btn-flat md-btn-flat-primary">Raise</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="uk-grid uk-margin-medium-top uk-margin-large-bottom" data-uk-grid-margin>
                                                <?php if (empty($dealData->report_issue_type)) { ?>
                                                    <div class="uk-width-large-1-1">
                                                        <div style="text-align: right;"><button id="deal-reporting" data-type="release" class="md-btn md-btn-primary"> Raise Issue </button></div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="uk-width-large-1-1" style="border: 1px solid  #f06572 ; padding: 10px;margin-bottom: 10px; border-radius: 10px;background-color:  #f06572">
                                                        <p><span><strong>Issues : </strong></span><span></span><?php echo str_replace(',', ', ', $dealData->report_issue_type); ?></span></p>
                                                        <p><span><strong>Detail : </strong></span><span></span><?php echo $dealData->report_issue_desc; ?></span></p>
                                                        <div style="text-align: right;"><button data-id="<?php echo $dealData->id; ?>" id="scout-resolve-issue" data-type="release" class="md-btn md-btn-success"> Scout Resolve Issue </button><button data-id="<?php echo $dealData->id; ?>" id="deal-resolve-issue" data-type="release" class="md-btn md-btn-success"> Resolve Issue </button></div>
                                                    </div>
                                                <?php } ?>

                                            </div>
                                        <?php } ?>
                                        <div class="uk-grid uk-margin-medium-top uk-margin-large-bottom" data-uk-grid-margin>

                                            <div class="uk-width-large-1-2">
                                                <h4 class="heading_c uk-margin-medium-bottom">Deal Detail</h4>
                                                <ul class="md-list">
                                                    <li>
                                                        <div class="md-list-content deal_checks">
                                                            <?php if ($dealData->is_cn_updated == 1) {
                                                                $check_set = "checked";
                                                            } else {
                                                                $check_set = "";
                                                            } ?>
                                                            <div>
                                                                <input type="checkbox" name="cn_check" id="cn-check" class="check_box" value="" <?php echo $check_set ?> />
                                                                <label> Content / Newswire </label>
                                                            </div>
                                                            <?php if ($dealData->uploaded_edited_videos == 1) {
                                                                $check_set = "checked";
                                                            } else {
                                                                $check_set = "";
                                                            } ?>
                                                            <div>
                                                                <input type="checkbox" name="ve_check" id="ve-check" class="check_box" value="" <?php echo $check_set ?> />
                                                                <label> Video Edited </label>
                                                            </div>
                                                            <?php if ($dealData->trending == 1) {
                                                                $check_set = "checked";
                                                            } else {
                                                                $check_set = "";
                                                            } ?>
                                                            <div>
                                                                <input type="checkbox" name="trending-check" id="trending-check" class="check_box" value="" <?php echo $check_set ?> />
                                                                <label> Trending Video </label>
                                                            </div>
                                                            <?php if ($dealData->is_ai_based == 1) {
                                                                $check_set = "checked";
                                                            } else {
                                                                $check_set = "";
                                                            } ?>
                                                            <div>
                                                                <input type="checkbox" name="ai-based-check" id="ai-based-check" class="check_box" value="" <?php echo $check_set ?> />
                                                                <label> AI Based </label>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="md-list-content">
                                                            <span class="uk-text-small uk-text-muted">Priority</span>
                                                            <span>
                                                                <select id="priority" class="custom_selector" name="priority" data-id="<?php echo $lead_id; ?>">
                                                                    <option value="0" disabled selected hidden><?php echo $dealData->priority; ?><span class="caret"></span></button></option>
                                                                    <option value="3">Low</option>
                                                                    <option value="2">Medium</option>
                                                                    <option value="1">High</option>
                                                                </select>
                                                            </span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="md-list-content">
                                                            <span class="uk-text-small uk-text-muted">Closing Date</span>
                                                            <span id="edit-closing-area" class="md-list-heading closing-area" data-val="<?php echo $dealData->closing_date; ?>"><?php echo $dealData->closing_date; ?></span>
                                                            <?php if ($dealData->status < 3 || $dealData->status == 10) { ?>
                                                                <button class="btn btn-info btn-edit" id="edit-closing">
                                                                    <span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                                                <button class="btn btn-success btn-save" id="save-closing">
                                                                    <span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                                                <button class="btn btn-success btn-cancel" id="cancel-closing"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                                            <?php } ?>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="md-list-content">
                                                            <span class="uk-text-small uk-text-muted">Revenue Share</span>
                                                            <span id="edit-revenue-area" class="md-list-heading revenue-area" data-val="<?php echo $dealData->revenue_share; ?>"><?php echo $dealData->revenue_share; ?>%</span>
                                                            <?php /*if ($dealData->status < 3 || $dealData->status == 10) { */ ?>
                                                            <?php //if ($dealData->revenue_share >= 70) { ?>
                                                                <button class="btn btn-info btn-edit" id="edit-revenue">
                                                                    <span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                                                <button class="btn btn-success btn-save" id="save-revenue">
                                                                    <span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                                                <button class="btn btn-success btn-cancel" id="cancel-revenue"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                                            <?php //} ?>
                                                        </div>
                                                    </li>
                                                    <?php if ($dealData->status == 10) { ?>
                                                        <li>
                                                            <div class="md-list-content">
                                                                <span class="uk-text-small uk-text-muted">Deal Date</span>
                                                                <span class="md-list-heading"><?php echo $dealData->deal_date; ?></span>
                                                            </div>
                                                        </li>
                                                    <?php } else if ($dealData->status == 2) { ?>
                                                        <li>
                                                            <div class="md-list-content">
                                                                <span class="uk-text-small uk-text-muted">Contract Sent Date</span>
                                                                <span class="md-list-heading"><?php echo $dealData->sent_date; ?></span>
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                    <li>
                                                        <div class="md-list-content">
                                                            <span class="uk-text-small uk-text-muted">Created Date</span>
                                                            <span class="md-list-heading"><?php echo $dealData->created_at; ?></span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="md-list-content">
                                                            <span class="uk-text-small uk-text-muted">Last Activity</span>
                                                            <span class="md-list-heading"><?php
                                                                                            if ($activity) {
                                                                                                echo date('m/d/Y H:i:s', strtotime($activity->created_at)) . ' ( ' . $activity->action . ' )';
                                                                                            } ?></span>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="md-list-content">
                                                            <span class="uk-text-small uk-text-muted">Title</span>
                                                            <span id="edit-title-area" class="repaint_video_title md-list-heading title-area" data-val="<?php echo $dealData->video_title; ?>">
                                                                <?php echo $dealData->video_title; ?>
                                                            </span>
                                                            <?php if ($assess['can_edit_title']) { ?>
                                                                <button class="btn btn-info btn-repaint" onclick="Repaint('repaint_video_title')" id="re-paint">
                                                                    <img src="https://freelogopng.com/images/all_img/1681039084chatgpt-icon.png" alt="chat gpt" style="width: 22px;height:22px" />
                                                                    <!-- <span class="material-symbols-outlined">
                                                                        format_paint
                                                                    </span> -->
                                                                </button>


                                                                <button class="btn btn-info btn-edit" id="edit-title"><span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                                                <button class="btn btn-success btn-save" id="save-title"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                                                <button class="btn btn-success btn-cancel" id="cancel-title">
                                                                    <span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                                            <?php } ?>
                                                        </div>
                                                        </br>
                                                    </li>
                                                    <li>
                                                        <div class="md-list-content">
                                                            <span class="uk-text-small uk-text-muted">Title 2</span>
                                                            <span id="edit-title-area-2" class="repaint_video_title_2 md-list-heading title-area-2" data-val="<?php echo $dealData->video_title_2; ?>">
                                                                <?php echo $dealData->video_title_2; ?>
                                                            </span>
                                                            <?php if ($assess['can_edit_title']) { ?>
                                                                <button class="btn btn-info btn-edit" id="edit-title-2"><span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                                                <button class="btn btn-success btn-save" id="save-title-2"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                                                <button class="btn btn-success btn-cancel" id="cancel-title-2">
                                                                    <span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                                            <?php } ?>
                                                        </div>
                                                        </br>
                                                    </li>
                                                    <li>
                                                        <div class="md-list-content">
                                                            <span class="uk-text-small uk-text-muted">Seo Target Words</span>
                                                            <span
                                                                id="seo-targets-area"
                                                                class="md-list-heading seo_targets_area"
                                                                data-val="<?php if (isset($videoData->seo_keywords)) { echo $videoData->seo_keywords;} ?>"
                                                            ><?php if(isset($videoData->seo_keywords)) { echo $videoData->seo_keywords; } ?></span>
                                                            <?php if ($assess['can_edit_target_words']) { ?>
                                                                <button class="btn btn-info btn-edit" id="edit-seo-targets"><span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                                                <button class="btn btn-success btn-save" id="save-seo-targets"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                                                <button class="btn btn-success btn-cancel" id="cancel-seo-targets"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                                            <?php } ?>
                                                        </div>
                                                        </br></br>
                                                    </li>
                            </li>
                            <li>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Description</span>
                                    <span id="edit-des-area" class="repaint_video_description md-list-heading des-area" data-val="<?php if (isset($videoData->description)) {
                                                                                                                                        echo $videoData->description;
                                                                                                                                    } ?>"><?php
                                                                                                                                            if (isset($videoData->description)) {
                                                                                                                                                echo $videoData->description;
                                                                                                                                            ?></span>
                                    <?php if ($assess['can_edit_description']) { ?>
                                        <button class="btn btn-info btn-repaint" onclick="Repaint(`repaint_video_description`)" id="re-paint">
                                            <img src="https://freelogopng.com/images/all_img/1681039084chatgpt-icon.png" alt="chat gpt" style="width: 22px;height:22px" />
                                            <!-- <span class="material-symbols-outlined">
                                                format_paint
                                            </span> -->
                                        </button>
                                        <button class="btn btn-info btn-edit" id="edit-des"><span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                        <button class="btn btn-success btn-save" id="save-des"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-des"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                <?php } else { ?>
                                    <span></span>
                                <?php } ?>

                                </div>
                            </li>
                            <li>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Tags</span>
                                    <span id="edit-tags-area" class="md-list-heading tags-area" data-val="<?php if (isset($videoData->tags)) {
                                                                                                                echo $videoData->tags;
                                                                                                            } ?>"><?php
                                                                                                                    if (isset($videoData->tags)) {
                                                                                                                        echo $videoData->tags;
                                                                                                                    ?></span>
                                    <?php if ($assess['can_edit_tags']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-tags"><span class="glyphicon glyphicon-edit"></span><i class="material-icons">edit</i></button>
                                        <button class="btn btn-success btn-save" id="save-tags"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-tags"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                <?php } else { ?>
                                    <span></span>
                                <?php } ?>

                                </div>
                            </li>
                            <li>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Video Url</span>
                                    <span id="edit-video-url-area" class="md-list-heading edit-video-url-area" data-val="<?php echo $dealData->video_url; ?>">
                                        <a href="<?php echo $dealData->video_url; ?>" target="_blank"><?php echo $dealData->video_url; ?></a>
                                    </span>
                                    <?php if ($assess['can_edit_video_url']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-video-url"><span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                        <button class="btn btn-success btn-save" id="save-video-url" style="display:none;"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-video-url" style="display:none;">
                                            <span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </li>
                            <?php if (!empty($channelName)) { ?>
                                <li>
                                    <div class="md-list-content">
                                        <span class="uk-text-small uk-text-muted">YouTube Channel Name</span>
                                        <span id="edit-title-area" class="md-list-heading">
                                            <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px;">

                                                <div class="uk-width-medium-1-2">
                                                    <div class="parsley-row">
                                                        <?php echo $channelName; ?>
                                                    </div>
                                                </div>
                                                <?php if ($assess['can_add_to_white_list']) { ?>
                                                    <div class="uk-width-medium-1-2" style="text-align: right;">
                                                        <a title="Add To Whitelist" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light" id="add_to_white" data-name="<?php echo $channelName; ?>" data-link="<?php echo $channelLink; ?>" href="javascript:void(0);">
                                                            Add To Whitelist
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </span>
                                    </div>
                                </li>
                            <?php } ?>
                            <li>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Unique Key</span>
                                    <span id="edit-title-area" class="md-list-heading" data-val="<?php echo $dealData->unique_key; ?>" ><?php echo $dealData->unique_key; ?></span>
                                </div>
                            </li>

                            <li>
                                <div class="md-list-content" style="z-index:1;">
                                    <span class="uk-text-small uk-text-muted">Assigned Staff</span>
                                    <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px;">

                                        <div class="uk-width-medium-1-2">
                                            <div class="parsley-row">
                                                <select id="staff_id" name="staff_id" data-parsley-required-message="This field is required." required data-md-selectize>
                                                    <option value=""> Select Staff</option>
                                                    <?php foreach ($staffs->result() as $staff) { ?>
                                                        <option <?php if ($staff->id == $dealData->staff_id) {
                                                                    echo 'selected';
                                                                } ?> value="<?php echo $staff->id; ?>"> <?php echo $staff->name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php if ($assess['can_edit_staff']) { ?>
                                            <div class="uk-width-medium-1-2" style="text-align: right;">
                                                <a style="border-radius: 50%;" title="Save" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light" id="save_staff" href="javascript:void(0);">
                                                    <i class="material-icons">save</i>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>


                                </div>
                            </li>
                            <li>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Video Type</span>
                                    <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px;">

                                        <div class="uk-width-medium-1-2" style="z-index:1;">
                                            <div class="parsley-row">
                                                <select id="video_type" name="video_type" data-parsley-required-message="This field is required." required data-md-selectize>
                                                    <option <?php if ($dealData->lead_type == 'scout') {
                                                                echo 'selected';
                                                            } ?> value="scout">Scout Video</option>
                                                    <option <?php if ($dealData->lead_type == 'simple') {
                                                                echo 'selected';
                                                            } ?> value="simple">Simple Video</option>
                                                    <option <?php if ($dealData->lead_type == 'website') {
                                                                echo 'selected';
                                                            } ?> value="website">Website</option>


                                                </select>

                                            </div>
                                        </div>
                                        <?php if ($assess['can_edit_video_type']) { ?>
                                            <div class="uk-width-medium-1-2" style="text-align: right;">
                                                <a style="border-radius: 50%;" title="Save" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light" id="save_type" href="javascript:void(0);">
                                                    <i class="material-icons">save</i>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>


                                </div>
                            </li>
                            <li>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Message</span>
                                    <span id="edit-message-area" class="md-list-heading message-area" data-val="<?php echo $dealData->message; ?>"><?php echo $dealData->message; ?></span>
                                    <?php if ($assess['can_edit_message']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-message"><span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                        <button class="btn btn-success btn-save" id="save-message"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-message">
                                            <span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </li>

                            <li>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Rating Point</span>
                                    <span id="edit-ratings-area" class="md-list-heading ratings-area" data-val="<?php echo $dealData->rating_point; ?>"><?php echo $dealData->rating_point; ?></span>
                                    <?php if ($dealData->status < 3 || $dealData->status == 10) { ?>
                                        <?php if ($assess['can_edit_rating_point']) { ?>
                                            <button class="btn btn-info btn-edit" id="edit-ratings">
                                                <span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                            <button class="btn btn-success btn-save" id="save-ratings">
                                                <span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                            <button class="btn btn-success btn-cancel" id="cancel-ratings"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </li>
                            <li>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Rating Comments</span>
                                    <span id="edit-rating-comments-area" class="md-list-heading rating-comments-area" data-val="<?php echo $dealData->rating_comments; ?>"><?php echo $dealData->rating_comments; ?></span>
                                    <?php if ($assess['can_edit_rating_comment']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-rating-comments">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                        <button class="btn btn-success btn-save" id="save-rating-comments"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-rating-comments"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </li>

                            <!--<li>
                                                    <div class="md-list-content">
                                                        <span class="uk-text-small uk-text-muted">Scout Name</span>
                                                        <span id=""
                                                              class="md-list-heading rating-comments-area"
                                                              ><?php /*echo $party_name; */ ?></span>

                                                    </div>
                                                </li>-->
                    </ul>
                    <?php if ($dealData->status == 3 && $dealData->information_pending == 0 && $dealData->client_id != 0 && !empty($userData->password)) { ?>
                        <h4 class="heading_c uk-margin-small-bottom">User Submitted Details</h4>
                        <ul class="md-list">
                            <li>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Video Submitted</span>
                                    <span class="md-list-heading">
                                        <?php if ($dealData->load_view == 2 || $dealData->load_view > 2) { ?>
                                            Yes
                                        <?php } else { ?>
                                            No
                                        <?php } ?>
                                    </span>

                                </div>
                            </li>
                            <li>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Story Information Submitted</span>
                                    <span class="md-list-heading">
                                        <?php if ($dealData->load_view == 3 || $dealData->load_view > 3) { ?>
                                            Yes
                                        <?php } else { ?>
                                            No
                                        <?php } ?>
                                    </span>
                                    <?php if ($dealData->load_view == 2 || $dealData->load_view == 3 || $dealData->load_view == 4) { ?>
                                        <a href="javascript:void(0);" id="story_information_modal_trigger" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light " data-id="<?php echo $dealData->id; ?>" title="Upload Story Information"><i class="material-icons">cloud_upload</i></a>
                                    <?php } ?>

                                </div>
                            </li>
                            <li>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Personal Information Submitted</span>
                                    <span class="md-list-heading ">
                                        <?php if ($dealData->load_view == 4) { ?>
                                            Yes
                                        <?php } else { ?>
                                            No
                                        <?php } ?>
                                    </span>
                                    <?php if ($dealData->load_view == 3 || $dealData->load_view == 4) { ?>
                                        <a href="javascript:void(0);" id="personal_information_modal_trigger" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light " data-id="<?php echo $dealData->id; ?>" title="Upload Personal Information"><i class="material-icons">cloud_upload</i></a>
                                    <?php } ?>

                                </div>
                            </li>
                        </ul>
                    <?php } ?>
                </div>
                <div class="uk-width-large-1-2">
                    <h4 class="heading_c uk-margin-small-bottom">Contact Info</h4>
                    <ul class="md-list md-list-addon">
                        <li>
                            <div class="md-list-addon-element">
                                <i class="md-list-addon-icon material-icons">account_circle</i>
                            </div>
                            <div class="md-list-content">
                                <span class="uk-text-small uk-text-muted">Name</span>
                                <span class="md-list-heading"><?php echo $dealData->first_name . ' ' . $dealData->last_name; ?></span>
                            </div>
                        </li>
                        <li>
                            <div class="md-list-addon-element">
                                <i class="md-list-addon-icon material-icons">&#xE158;</i>
                            </div>
                            <div class="md-list-content">
                                <span class="uk-text-small uk-text-muted">Email</span>
                                <span id="edit-video-email-area" class="md-list-heading edit-video-email-area" data-val="<?php echo $dealData->email; ?>"><?php echo $dealData->email; ?>
                                </span>
                                <?php if ($assess['can_edit_video_email']) { ?>
                                    <button class="btn btn-info btn-edit" id="edit-video-email"><span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                    <button class="btn btn-success btn-save" id="save-video-email" style="display:none;"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                    <button class="btn btn-success btn-cancel" id="cancel-video-email" style="display:none;">
                                        <span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                <?php } ?>

                            </div>
                        </li>

                    </ul>
                    <?php if (in_array($dealData->status, array(6, 8))  && $videoData->video_verified == 1) { ?>

                        <h4 class="heading_c uk-margin-small-bottom">Publish Video Info</h4>
                        <ul class="md-list md-list-addon">
                            <?php if ($mrss_queue->result_array()) { ?>
                                <li style="margin-bottom:10px;">
                                    <div class="md-list-addon-element">
                                        <i class="uk-icon-rss uk-icon-medium"></i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="uk-text-small uk-text-muted">MRSS Publishing</span>
                                        <div>
                                            <span class="md-list-heading">
                                                <?php echo 'In Queue'; ?>
                                            </span>
                                            <button 
                                                class="md-btn md-btn-primary"
                                                id="publish-to-feeds"
                                                title="Pulish to all MRSS feeds (where enqueued)"
                                                data-id="<?php echo $videoData->id; ?>"
                                            >
                                                Publish To MRSS
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>

                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-facebook uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Facebook Publish Url (Please enter facebook video id)</span>
                                    <span class="md-list-heading">
                                        <?php if (!empty($videoData->facebook_id)) { ?>
                                            <a href="https://www.facebook.com/WooGlobe/videos/<?php echo $videoData->facebook_id ?>" target="_blank">https://www.facebook.com/WooGlobe/videos/<?php echo $videoData->facebook_id ?></a>
                                        <?php } ?>
                                    </span>
                                    <span id="facebook_edit" class="md-list-heading facebook_edit_area" data-val="">
                                    </span>
                                    <?php if ($assess['can_edit_facebook_publish_url']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-facebook">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons">edit</i></button>
                                        <button class="btn btn-success btn-save" id="save-facebook"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-facebook"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </li>

                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-medium"><img src="//uat.technoventive.com/admin/assets/assets/icons/youtube_social_circle_red.png" width="31px" /></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Youtube Publish Url (Please enter youtube video id)</span>
                                    <span class="md-list-heading" id="published-youtube-url">
                                        <?php if (!empty($videoData->youtube_id)) { ?>
                                            <a href="https://www.youtube.com/watch?v=<?php echo $videoData->youtube_id ?>" target="_blank">https://www.youtube.com/watch?v=<?php echo $videoData->youtube_id ?></a>
                                        <?php } ?>
                                    </span>
                                    </span>
                                    <span id="youtube_edit" class="md-list-heading youtube_edit_area" data-val=""></span>

                                    <?php if ($assess['can_edit_youtube_publish_url']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-youtube">
                                            <span class="glyphicon glyphicon-edit"></span>
                                            <i class="material-icons">edit</i>
                                        </button>

                                        <button class="md-btn md-btn-primary" id="btn-publish-youtube" data-id="<?php echo $videoData->id; ?>" data-wgid="<?php echo $dealData->unique_key; ?>" data-url="publish-youtube">
                                            <?php
                                            if ($videoData->youtube_id) {
                                                echo "Update Published Data";
                                            } else {
                                                echo "Publish on Youtube";
                                            }
                                            ?>
                                            <!-- <span class="glyphicon glyphicon-edit"></span>
                                                                    <i class="material-icons">edit</i> -->
                                        </button>
                                        <?php if ($videoData->youtube_id) { ?>
                                            <button class="md-btn md-btn-danger" id="btn-delete-youtube" data-youtube-id="<?php echo $videoData->youtube_id; ?>" data-id="<?php echo $videoData->id; ?>" data-wgid="<?php echo $dealData->unique_key; ?>" data-url="delete-youtube">
                                                <?php
                                                echo "Delete Youtube Video";
                                                ?>
                                            </button>
                                        <?php } ?>
                                        <button class="btn btn-success btn-save" id="save-youtube"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-youtube"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </li>
                        </ul>
                    <?php } ?>
                    <?php if (in_array($dealData->status, array(6, 8)) && $videoData->video_verified == 1) { ?>
                        <h4 class="heading_c uk-margin-small-bottom">S3 Video Info</h4>
                        <ul class="md-list md-list-addon">

                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-video-camera uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Edited Video Url</span>
                                    <span class="md-list-heading">
                                        <?php
                                        $edites3 = $editedVideo->result();
                                        if (isset($edites3[0])) {
                                            if (!empty($edites3[0]->portal_url)) { ?>
                                                <a href="<?php echo $edites3[0]->portal_url ?>" target="_blank"><?php echo $edites3[0]->portal_url ?></a>
                                        <?php }
                                        } ?>
                                    </span>
                                    <span id="editeds3_edit" class="md-list-heading editeds3_edit_area" data-val="">
                                    </span>
                                    <?php if ($assess['can_edit_edited_video_url']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-editeds3">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons">edit</i></button>
                                        <button class="btn btn-success btn-save" id="save-editeds3"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-editeds3"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>

                                </div>
                            </li>
                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-video-camera uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Landscape Converted Url</span>
                                    <span class="md-list-heading" >
                                        <?php 
                                        foreach ($rawVideos->result_array() as $raw) {
                                            if ( $raw['landscape_converted_url'] != NULL) {  
                                            ?>
                                                <a href="<?php echo $raw['landscape_converted_url'] ?>"><?php echo $raw['landscape_converted_url'] ?></a>
                                            <?php } 
                                        }
                                        // Show the Convert Manually button if no landscape URLs are present
                                        if (!empty($edites3[0]->portal_url) || !empty($videoData->s3_url)) { ?>
                                        <button class="uk-button uk-button-default" onclick="ShowLandscapDialoge()">
                                            <i class="uk-icon-plus"></i> Convert Manually
                                        </button>
                                        <?php } ?>
                                        <button class="uk-button uk-button-default" onclick="ShowReuploadLandscape()">Reupload File</button>
                                    </span>
                                </div>

                                <!-- Conver landscap Dialog HTML -->
                                <div  class="uk-modal" id="landscapconversionDialoge">
                                    <div class="uk-modal-dialog">
                                        <div class="uk-modal-header">
                                            <h2 class="uk-text-center">Convert URL</h2>
                                        </div>
                                        <div class="uk-modal-body">
                                            <form id="convertForm">
                                                <div class="uk-margin">
                                                    <label class="uk-form-label" for="convertUrl">Converting URL:</label>
                                                    <div class="uk-form-controls">
                                                        <input id="landscap-input" class="uk-input" disabled style="width:100%;height:32px;" type="url" placeholder="URL" aria-label="Input">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="uk-modal-footer uk-text-right">
                                            <button class="uk-button uk-button-primary"  onclick="RequestLandScapConversion()">Convert</button>
                                            <button class="uk-button uk-button-default uk-modal-close">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Reupload landscape Dialog -->
                                <div class="uk-modal" id="reupload-upload-modal">
                                    <div class="uk-modal-dialog">
                                        <div class="uk-modal-header">
                                            <h2 class="uk-text-center">Upload File</h2>
                                        </div>
                                        <div class="uk-modal-body">
                                            <div class="uk-form-row">
                                                <form  onsubmit="ReuploadLandscapeVideo(event)" method="POST" action="#" enctype="multipart/form-data">
                                                    <div class="uk-form-file" style="width: 100%; margin-bottom: 15px;">
                                                        <input  class="uk-input" type="file" id="landscape-file-input" name="file_url" style="display: none;">
                                                        <label for="landscape-file-input" class="uk-button uk-button-default" style="width: 100%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                                            <span class="uk-icon" uk-icon="icon: upload; ratio: 1.5" style="margin-right: 8px;"></span>
                                                            Choose File
                                                        </label>
                                                    </div>
                                                    
                                                    <!-- Display file information -->
                                                    <div id="file-info" class="uk-margin-bottom" style="font-size: 14px; color: #666;"></div>
                                                    
                                                    <!-- Progress container -->
                                                    <div id="progress-container" style="display: none; align-items: center; gap: 10px;">
                                                        <progress id="upload-progress" class="uk-progress" style="flex-grow: 1; height: 8px;" value="0" max="100"></progress>
                                                        <span id="progress-text" style="font-weight: bold;">0%</span>
                                                    </div>
                                                    
                                                    <!-- Submit button -->
                                                    <button class="uk-button uk-button-primary" id="reupload-btn" type="submit" style="display: flex; align-items: center;">
                                                        <span class="material-symbols-outlined">cloud_upload</span>
                                                        Upload
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="uk-modal-footer uk-text-right">
                                            <button class="uk-button uk-button-default uk-modal-close" id="cancel-reupload-btn">Cancel</button>
                                        </div>
                                    </div>
                                </div>

                            </li>
                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-video-camera uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Edited Video Thumbnail <i style="color:red">*</i></span>
                                    <span class="md-list-heading">
                                        <?php
                                        $edites3 = $editedVideo->result();
                                        if (isset($edites3[0])) {
                                            if (!empty($edites3[0]->portal_thumb)) { ?>
                                                <a id="lead_thumbnail_url" href="<?php echo $edites3[0]->portal_thumb ?>" target="_blank"><?php echo $edites3[0]->portal_thumb ?></a>
                                        <?php }
                                        } ?>
                                    </span>
                                    <span id="thumbs3_edit" class="md-list-heading thumbs3_edit_area" data-val="">
                                    </span>
                                    <?php if ($assess['can_edit_edited_video_thumbnail']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-thumbs3">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons">edit</i></button>
                                        <button class="btn btn-success btn-save" id="save-thumbs3"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-thumbs3"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </li>
                            <?php if ($videoData->watermark == 1) { ?>
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="uk-icon-video-camera uk-icon-medium"></i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="uk-text-small uk-text-muted">Watermark Video <i style="color:red">*</i></span>
                                        <span class="md-list-heading" id="w_s3_url">

                                            <a href="<?php echo $videoData->s3_url ?>" target="_blank"><?php echo $videoData->s3_url ?></a>

                                        </span>
                                        <?php if ($assess['can_edit']) { ?>
                                            <button class="btn btn-info btn-edit" id="edit-watermark">
                                                <span class="glyphicon glyphicon-edit"></span>
                                                <i class="material-icons">edit</i>
                                            </button>
                                        <?php } ?>

                                    </div>
                                </li>
                            <?php } ?>
                            <?php if (!empty($videoData->soical_url)) { ?>
                                <li>
                                    <div class="md-list-addon-element">
                                        <i class="uk-icon-video-camera uk-icon-medium"></i>
                                    </div>
                                    <div class="md-list-content">
                                        <span class="uk-text-small uk-text-muted">Soical Video <i style="color:red">*</i></span>
                                        <span class="md-list-heading" id="s_s3_url">

                                            <a href="<?php echo $videoData->soical_url ?>" target="_blank"><?php echo $videoData->soical_url ?></a>

                                        </span>
                                        <?php if ($assess['can_delete']) { ?>
                                            <button class="btn btn-info btn-edit" id="delete-soical">
                                                <span class="glyphicon glyphicon-delete"></span>
                                                <i class="material-icons">delete</i>
                                            </button>
                                        <?php } ?>
                                    </div>
                                </li>
                            <?php } ?>
                            <li></li>
                        </ul>
                    <?php }
                            foreach ($rawVideos->result_array() as $raw) {
                                if (isset($raw['url'])) {
                                    $raw_url = $raw['url'];
                                }
                            }
                            if (in_array($dealData->status, array(3, 6, 8)) && $dealData->information_pending == 1) {
                    ?>
                        <ul class="md-list md-list-addon">

                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-video-camera uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">

                                    <div style="text-align: right;margin-top: 50px; margin-bottom: 10px;"><a target="_blank" href="<?php echo $root . 'video_reject_upload/' . $videoData->slug; ?>"><?php echo $root . 'video_reject_upload/' . $videoData->slug; ?></a>
                                        <p>
                                            <button id="raw-video-upload-refresh" data-id="<?php echo $videoData->id; ?>" data-vid="<?php echo $dealData->id; ?>" class="md-btn md-btn-primary"> Refresh </button>
                                        </p>
                                    </div>
                                    <div style="text-align: right;"><button id="raw-video-upload" data-type="release" class="md-btn md-btn-primary"> Add Raw Video </button></div>
                                    <span class="uk-text-small uk-text-muted">S3 Raw Video Url</span>
                                    <span class="md-list-heading">
                                        <?php if ($dealData->status >= 6 && $videoData->video_verified == 1) { ?>
                                            <ul class="raw-list">
                                                <?php
                                                foreach ($rawVideos->result_array() as $raw) {

                                                ?>
                                                    <li> <?php if ($assess['can_delete_raw_videos']) { ?> <a href="javascript:void(0);" class="raw-s3-remove" data-id="<?php echo $raw['id']; ?>" style="margin-right: 10px;"><i class="material-icons">&#xE92B;</i></a> <?php } ?> <a href="<?php echo $raw['s3_url'] ?>" target="_blank"><?php echo $raw['s3_url'] ?></a> </li>
                                                <?php  } ?>
                                            </ul>
                                        <?php } ?>
                                    </span>
                                    <span id="raw_edit" class="md-list-heading raw_edit_area" data-val="">
                                    </span>
                                    <?php if ($assess['can_edit_raw_videos']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-raw">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons">edit</i></button>
                                        <button class="btn btn-success btn-save" id="save-raw"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-raw"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>

                                </div>
                            </li>
                        </ul>
                        <?php if ($assess['can_view_contract']) { ?>
                            <?php if ($dealData->status >= 6 && $dealData->status <= 8 && $videoData->video_verified == 1) { ?>
                                <ul class="md-list md-list-addon">

                                    <li>
                                        <div class="md-list-addon-element">
                                            <i class="uk-icon-video-camera uk-icon-medium"></i>
                                        </div>
                                        <div class="md-list-content">
                                            <span class="uk-text-small uk-text-muted">Dropbox Upload Status</span>
                                            <span class="md-list-heading">
                                                <ul class="raw-list">
                                                    <?php
                                                    $is_edited = FALSE;
                                                    $video_url = "";
                                                    $edites3 = $editedVideo->result();
                                                    if (isset($edites3[0])) {
                                                        if (!empty($edites3[0]->portal_url)) {
                                                            $video_url = $edites3[0]->portal_url;
                                                            $is_edited = TRUE;
                                                        }
                                                    }

                                                    $total = count($rawVideos->result());

                                                    foreach ($rawVideos->result_array() as $raw) {
                                                    ?>
                                                            <li data-lead-id="<?php echo $lead_id ?>" data-dropbox-status="<?php echo $raw['dropbox_status'] ?>">
                                                            <?php
                                                            $color = "green";
                                                            if (!$is_edited) {
                                                                $video_url = $raw["s3_url"];
                                                            }
                                                            if ($raw["dropbox_status"] == NULL) {
                                                                $color = "blue";
                                                                $status = "Not Uploaded Yet!";
                                                            } elseif ($raw["dropbox_status"] != 'success' && $raw["dropbox_status"] != 'failed') {
                                                                $color = "black";
                                                                $status = "Job Pending";
                                                            } else {
                                                                if ($raw["dropbox_status"] == 'failed') {
                                                                    $color = "red";
                                                                }
                                                                $status = $raw["dropbox_status"];
                                                            }
                                                            echo "<p id='dropbox_status_info' style='color:$color'>" . $status . " ==> " . $video_url . "</p>";
                                                            if ($is_edited) {
                                                                break;
                                                            }

                                                            ?>
                                                        </li>
                                                    <?php  } ?>
                                                </ul>
                                            </span>

                                        </div>
                                    </li>
                                </ul>
                            <?php } ?>
                        <?php } ?>

                        <ul class="md-list md-list-addon">

                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-video-camera uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Raw Video Url</span>
                                    <span class="md-list-heading">
                                        <ul class="raw-list">
                                            <?php
                                            $total = count($rawVideos->result());
                                            foreach ($rawVideos->result_array() as $raw) {

                                            ?>
                                                <li><?php if ($assess['can_delete_raw_videos']) { ?> <a href="javascript:void(0);" class="raw-s3-remove" data-id="<?php echo $raw['id']; ?>" style="margin-right: 10px;"><i class="material-icons">&#xE92B;</i></a> <?php } ?> <a href="<?php echo $root . $raw['url'] ?>" target="_blank"><?php echo $raw['url'] ?></a></li><!-- <button class="btn btn-success btn-cancel"
                                                                    id="delete-raw"><span
                                                                        class="glyphicon glyphicon-save"></span><i
                                                                        class="material-icons">close</i></button> -->
                                            <?php  } ?>
                                        </ul>
                                    </span>

                                </div>
                            </li>
                        </ul>
                        <ul class="md-list md-list-addon">
                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-video-camera uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">s3 document Url</span>
                                    <span class="md-list-heading">
                                        <ul class="raw-list">
                                            <?php
                                            foreach ($rawVideos->result_array() as $raw) {
                                                if (isset($raw['s3_document_url'])) {
                                                    $raw_document = $raw['s3_document_url'];
                                            ?>

                                            <?php }
                                            } ?>
                                            <li><a href="<?php echo $url . 'view_contract/' . $dealData->id; ?>" target="_blank"><?php echo $raw['s3_document_url'] ?></a></li>
                                        </ul>
                                    </span>
                                    <span id="s3_doc_edit" class="md-list-heading s3_doc_edit_area" data-val="">
                                    </span>
                                    <?php if ($assess['can_edit_aws_document_url']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-s3-doc">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons">edit</i></button>
                                        <?php if ($assess['can_click_email']) { ?>
                                            <button class="btn btn-email-send" id="email_send" data-lead-id="<?php echo $lead_id; ?>">
                                                <span class="glyphicon glyphicon-edit"></span><i class="material-icons">email</i></button>
                                        <?php } ?>
                                        <button class="btn btn-success btn-save" id="save-s3-doc"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-s3-doc"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>

                                </div>
                            </li>
                        </ul>
                    <?php
                            }
                            if (in_array($dealData->status, array(3, 6, 8)) && $dealData->information_pending == 1) {
                    ?>
                        <ul class="md-list md-list-addon">

                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-video-camera uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Client Video Url</span>
                                    <span class="md-list-heading">
                                        <ul class="raw-list">
                                            <?php
                                            $clint_link = '';
                                            foreach ($rawVideos->result_array() as $raw) {
                                                if (isset($raw['client_link'])) {
                                                    $clint_link = $raw['client_link'];
                                                } else {
                                                    $clint_link = '';
                                                }
                                            }
                                            ?>
                                            <li><a href="<?php echo $clint_link ?>" target="_blank"><?php echo $clint_link ?></a></li>
                                            <?php
                                            ?>
                                        </ul>
                                </div>
                            </li>
                        </ul>
                    <?php
                            } ?>
                    <?php if (in_array($dealData->status, array(3, 6, 8)) && $dealData->information_pending == 1) { ?>
                        <h4 class="heading_c uk-margin-small-bottom">Client Video Info</h4>
                        <ul class="md-list md-list-addon">
                            <li>
                                <?php
                                $filmed_by_fullname = '';
                                if (count($second_signer) > 0) {
                                    $filmed_by_fullname = $second_signer[0]->first_name . ' ' . $second_signer[0]->last_name;
                                } else {
                                    $filmed_by_fullname = $dealData->first_name . ' ' . $dealData->last_name;
                                }
                                ?>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-user uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Filmed By?</span>
                                    <span id="edit-q0-area" class="md-list-heading q0-area" data-val="<?php echo $filmed_by_fullname ?>"><?php echo $filmed_by_fullname ?></span>
                                    <?php if ($assess['can_edit_when_video_taken']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-q0">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                        <button class="btn btn-success btn-save" id="save-q0">
                                            <span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-q0">
                                            <span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </li>
                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-calendar uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">When Video Taken?</span>
                                    <span id="edit-q1-area" class="md-list-heading q1-area" data-val="<?php echo $videoData->question_when_video_taken; ?>"><?php echo $videoData->question_when_video_taken; ?></span>
                                    <?php if ($assess['can_edit_when_video_taken']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-q1">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons"></i></button>
                                        <button class="btn btn-success btn-save" id="save-q1">
                                            <span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-q1"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </li>
                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-home uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">Where Video Taken?</span>
                                    <span class="md-list-heading">
                                        <?php if (!empty($videoData->question_video_taken)) { ?>
                                            <?php echo $videoData->question_video_taken ?>
                                        <?php } ?>
                                    </span>
                                    <span id="q2_edit" class="md-list-heading q2_edit_area" data-val="">
                                    </span>
                                    <?php if ($assess['can_edit_where_video_taken']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-q2">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons">edit</i></button>
                                        <button class="btn btn-success btn-save" id="save-q2"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-q2"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </li>
                            <li>
                                <div class="md-list-addon-element">
                                    <i class="uk-icon-commenting-o uk-icon-medium"></i>
                                </div>
                                <div class="md-list-content">
                                    <span class="uk-text-small uk-text-muted">What is Video Context?</span>
                                    <span class="md-list-heading">
                                        <?php if (!empty($videoData->question_video_context)) { ?>
                                            <?php echo $videoData->question_video_context ?>
                                        <?php } ?>
                                    </span>
                                    <span id="q3_edit" class="md-list-heading q3_edit_area" data-val="">
                                    </span>
                                    <?php if ($assess['can_edit_video_context']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-q3">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons">edit</i></button>
                                        <button class="btn btn-success btn-save" id="save-q3"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-q3"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </li>

                        </ul>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php /*if($emailHistory->num_rows() > 0) { */ ?><!--
                                <div class="md-card">
                                  <div class="md-card-content">
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-medium-5-6">
                                            <h4 class="heading_c uk-margin-bottom">Emails Sent</h4>
                                            <div class="timeline">
                                                <?php /*foreach ($emailHistory->result() as $history) { */ ?>

                                                    <div class="timeline_item">
                                                        <div class="timeline_icon timeline_icon_success"><i
                                                                    class="material-icons">send</i></div>
                                                        <div class="timeline_date">
                                                            <?php /*echo $history->send_date;*/ ?>
                                                        </div>
                                                        <div class="timeline_content"><?php /*echo $history->email_title;*/ ?></div>
                                                    </div>
                                                <?php /*} */ ?>
                                            </div>
                                        </div>
                                        <div class="uk-width-medium-1-6">
                                            <a class="md-fab md-fab-small md-fab-accent hidden-print" href="<?php /*echo $url*/ ?>communication-email/<?php /*echo $lead_id*/ ?>">
                                                <i class="material-icons">&#xE150;</i>
                                            </a>
                                        </div>
                                    </div>
                                  </div>
                                </div>

                            --><?php /*}*/ ?>
    <div class="md-card">
        <div class="md-card-content">
            <h4 class="heading_c uk-margin-small-bottom">Appearance Release Form Management </h4>
            <div class="uk-grid uk-margin-medium-top uk-margin-large-bottom" data-uk-grid-margin>


                <div class="uk-width-large-1-4">
                    <div>
                        <label for="cars">Expire In:</label>
                        <select name="days-expire" id="days-expire" tabindex="-1" data-parsley-required-message="This field is required.">
                            <option value="10">10 Days</option>
                            <?php for ($i = 5; $i <= 30; $i += 5) { ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> Days</option>
                            <?php } ?>
                        </select>
                        <div class="error"></div>
                    </div>
                </div>
                <div class="uk-width-large-1-2">
                    <div>
                        <?php if ($assess['create_signer_link']) { ?>
                            <?php if (!isset($LeadRelaseLink['signer'])) { ?>
                                <button id="signer-link" data-type="signer" class="md-btn md-btn-primary generate-link "> Create Signer Link</button>
                            <?php } ?>
                        <?php } ?>

                        <?php if ($assess['create_appearance_link']) { ?>
                            <?php if (!isset($LeadRelaseLink['release'])) { ?>
                                <button id="appreance-relase-link" data-type="release" class="md-btn md-btn-primary generate-link"> Create Appearance Release Link </button>
                            <?php } ?>

                            <button id="gnrt-appearance-rls-btn" class="md-btn md-btn-primary gnrt_appearance_rls_btn"> Generate Appearance Release </button>
                            <div id ="gnrt-appearance-rls-div" class="gnrt_appearance_rls_div" style="display: none;">
                                <div class="parsley-row">
                                    <label for="appearance-rls-desc" class="uk-form-label">Identify The Person</label>
                                    <textarea id="appearance-rls-desc" name="appearance-rls-desc" class="appearance_rls_desc md-input" data-value=""></textarea>
                                    <div class="error"></div>
                                </div>
                                <button class="btn btn-success gnrt_apr_rls" id="gnrt-apr-rls" data-id="<?php echo $dealData->id; ?>"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                <button class="btn btn-success cancel_apr_rls" id="cancel-apr-rls"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="uk-width-large-1-4">
                    <div style="float: right">
                        <?php if ($assess['can_sign_release']) { ?>
                            <?php if (!empty($manualRelease)) { ?>
                                <button id="appreance-relase-munual" data-uid="<?php echo $dealData->unique_key; ?>" class="md-btn md-btn-success">Manual AR </button>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
                <div class="uk-width-large-1-1">
                    <div class="md-card-content">
                        <div class="dt_colVis_buttons"></div>
                        <table id="" class="" cellspacing="0" width="100%">
                            <thead>
                                <tr style="line-height: 40px;">

                                    <th data-name="" style="text-align: left;">Type</th>
                                    <th data-name="" style="text-align: left;">Link</th>
                                    <th data-name="" style="text-align: left;">Manual</th>
                                    <th data-name="" style="text-align: left;">Expire Date</th>
                                    <th data-name="" style="text-align: left;">Renew</th>


                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($LeadRelaseLink as $link) { ?>
                                    <tr style="line-height: 40px;">
                                        <td><?php echo ucfirst($link->link_type); ?></td>
                                        <?php if ($link->link_type == 'signer') { ?>
                                            <td><?php echo $root . 'recorder/' . $link->unique_key; ?></td>
                                        <?php } else { ?>
                                            <td><?php echo $root . 'appearance_release/' . $link->unique_key; ?></td>
                                        <?php } ?>
                                        <td><?php if ($link->manual == 1) {
                                                echo 'Yes';
                                            } else {
                                                echo 'No';
                                            } ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($link->created_at . " +$link->days_interval days ")); ?></td>
                                        <td>
                                            <a title="" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light update_link_date" data-id="<?php echo $link->id ?>" href="javascript:void(0);">
                                                <i class="material-icons">autorenew</i>
                                            </a>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            <tfoot>
                                <tr style="line-height: 40px;">
                                    <th data-name="" style="text-align: left;">Type</th>
                                    <th data-name="" style="text-align: left;">Link</th>
                                    <th data-name="" style="text-align: left;">Manual</th>
                                    <th data-name="" style="text-align: left;">Expire Date</th>
                                    <th data-name="" style="text-align: left;">Renew</th>

                                </tr>
                            </tfoot>




                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <?php if ($assess['can_view_second_signer']) { ?>
        <?php if (count($second_signer) > 0) { ?>
            <div class="md-card" style="margin-top: 70px;">
                <div class="md-card-content">
                    <h4 class="heading_c uk-margin-small-bottom">Second Signer PDFs </h4>
                    <div class="uk-width-large-1-1">
                        <div class="md-card-content">
                            <div class="dt_colVis_buttons"></div>
                            <table id="" class="" cellspacing="0" width="100%">
                                <thead>
                                    <tr style="line-height: 40px;">

                                        <th data-name="" style="text-align: left;">Name</th>
                                        <th data-name="" style="text-align: left;">Email</th>
                                        <th data-name="" style="text-align: left;">Sign Date</th>
                                        <th data-name="" style="text-align: left;">View Pdf</th>
                                        <th data-name="" style="text-align: left;">Sign Appreance Release</th>
                                        <th data-name="" style="text-align: left;">Action</th>



                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($second_signer as $link) { ?>
                                        <tr style="line-height: 40px;">
                                            <td><?php echo $link->first_name ?></td>

                                            <td><?php echo $link->email ?></td>

                                            <td><?php echo $link->created_at ?></td>

                                            <td>
                                                <a title="" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light " href="<?php echo $link->pdf_link; ?>" target="_blank">
                                                    <i class="material-icons">picture_as_pdf</i>
                                                </a>

                                            </td>
                                            <td>
                                                <a title="" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light sign_appearance_release" data-id="<?php echo $link->id; ?> " data-uid="<?php echo $link->uid; ?>">
                                                    <i class="material-icons">assignment</i>
                                                </a>
                                            </td>
                                            <td>
                                                <?php if ($assess['can_delete_second_signer']) { ?>
                                                    <a title="" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light second_signer_delete" data-id="<?php echo $link->id; ?> ">
                                                        <i class="material-icons">delete</i>
                                                    </a>
                                                <?php } ?>
                                            </td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr style="line-height: 40px;">
                                        <th data-name="" style="text-align: left;">Name</th>
                                        <th data-name="" style="text-align: left;">Email</th>
                                        <th data-name="" style="text-align: left;">Sign Date</th>
                                        <th data-name="" style="text-align: left;">View Pdf</th>
                                        <th data-name="" style="text-align: left;">Sign Appreance Release</th>
                                        <th data-name="" style="text-align: left;">Action</th>

                                    </tr>
                                </tfoot>




                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    <?php if ($assess['can_view_appearance_release']) { ?>
        <?php if (count($appreance_release) > 0) { ?>
            <div class="md-card" style="margin-top: 70px;">
                <div class="md-card-content">
                    <h4 class="heading_c uk-margin-small-bottom">Appearance Release PDFs </h4>
                    <div class="uk-width-large-1-1">
                        <div class="md-card-content">
                            <div class="dt_colVis_buttons"></div>
                            <table id="" class="" cellspacing="0" width="100%">
                                <thead>
                                    <tr style="line-height: 40px;">

                                        <th data-name="" style="text-align: left;">Name</th>
                                        <th data-name="" style="text-align: left;">Email</th>
                                        <th data-name="" style="text-align: left;">Sign Date</th>
                                        <th data-name="" style="text-align: left;">View Pdf</th>
                                        <th data-name="" style="text-align: left;">Action</th>


                                    </tr>
                                </thead>
                                <?php foreach ($appreance_release as $link) { ?>
                                    <tr style="line-height: 40px;">
                                        <td><?php echo $link->first_name ?></td>

                                        <td><?php echo $link->email ?></td>

                                        <td><?php echo $link->created_at ?></td>

                                        <td>
                                            <a title="" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light " href="<?php echo $link->pdf_link; ?>" target="_blank">
                                                <i class="material-icons">picture_as_pdf</i>
                                            </a>
                                        <td>
                                            <?php if ($assess['can_delete_appearance_release']) { ?>
                                                <a title="" class="md-btn md-btn-success md-btn-wave-light waves-effect waves-button waves-light appearance_release_delete" data-id="<?php echo $link->id ?>">
                                                    <i class="material-icons">delete</i>
                                                </a>
                                            <?php } ?>
                                        </td>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                                <tfoot>
                                    <tr style="line-height: 40px;">
                                        <th data-name="" style="text-align: left;">Name</th>
                                        <th data-name="" style="text-align: left;">Email</th>
                                        <th data-name="" style="text-align: left;">Sign Date</th>
                                        <th data-name="" style="text-align: left;">View Pdf</th>
                                        <th data-name="" style="text-align: left;">Action</th>

                                    </tr>
                                </tfoot>




                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>

    </li> <?php } ?>
<?php if ($assess['can_deal_information']) { ?>
    <li>
        <div class="md-card">
            <div class="md-card-content">
                <ul class="md-list">
                    <li>
                        <?php if ($dealData->researcher_comment != NULL) {
                            $comm = $dealData->researcher_comment;
                        } else {
                            $comm = "";
                        } ?>
                        <div class="md-input-wrapper">
                            <div style="margin: 25px 0; width: 65%; float: left;">
                                <h4> Researcher's Comment </h4>
                                <textarea id="researcher-comm" name="researcher_comm" style="width:100%;" placeholder="Type Here..."><?php echo $comm; ?></textarea>
                                <button id="save-res-comm" class="md-btn md-btn-flat md-btn-primary" style="margin: 0;" data-id="<?php echo $lead_id; ?>"><?php if ($dealData->researcher_comment != NULL) { ?> Update <?php } else { ?> Save <?php } ?></button>
                                <button id="add-res-att" class="md-btn md-btn-flat md-btn-primary" style="float: right;"> Add Attachments </button>
                            </div>
                            <div class="comm_att">
                                <table id="res-att-table">
                                    <tr>
                                        <th>Comment Attachments</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <?php foreach ($res_att as $rt) { ?>
                                        <tr>
                                            <td><?php echo $rt ?></td>
                                            <td><a href="<?php echo "../../" . $res_directory . "/" . $rt; ?>" download><span class="material-icons" style="color:#38f;">file_download</span></a></td>
                                            <td><a class="remove-att" data-path="<?php echo $res_directory; ?>" data-file="<?php echo $rt; ?>"><span class="material-icons" style="color:#38f;">delete</span></a></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="parsley-row">
                            <?php if ($dealData->manager_comment != NULL) {
                                $comm = $dealData->manager_comment;
                            } else {
                                $comm = "";
                            } ?>
                            <div class="md-input-wrapper">
                                <div style="margin: 25px 0; width: 65%; float: left;">
                                    <h4 class="uk-form-label">Manager's Comment </h4>
                                    <textarea id="manager-comm" name="manager_comm" class="comm" style="width:100%;" placeholder="Type Here..."><?php echo $comm; ?></textarea>
                                    <button id="save-mgr-comm" class="md-btn md-btn-flat md-btn-primary" style="margin: 0;" data-id="<?php echo $lead_id; ?>"><?php if ($dealData->manager_comment != NULL) { ?> Update <?php } else { ?> Save <?php } ?></button>
                                    <button id="add-mgr-att" class="md-btn md-btn-flat md-btn-primary" style="float: right;"> Add Attachments </button>
                                </div>
                                <div class="comm_att">
                                    <table id="mgr-att-table">
                                        <tr>
                                            <th>Comment Attachments</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <?php foreach ($mgr_att as $mt) { ?>
                                            <tr>
                                                <td><?php echo $mt ?></td>
                                                <td><a href="<?php echo "../../" . $mgr_directory . "/" . $mt; ?>" download><span class="material-icons" style="color:#38f;">file_download</span></a></td>
                                                <td><a class="remove-att" data-path="<?php echo $mgr_directory; ?>" data-file="<?php echo $mt; ?>"><span class="material-icons" style="color:#38f;">delete</span></a></td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </li>
<?php } ?>
<?php if ($assess['can_deal_corresponding_email']) { ?> <li>
        <div class="md-card">
            <div class="md-card-content">
                <?php
                if ($correspondingEmails) {
                    if ($correspondingEmails->num_rows() > 0) { ?>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-5-6">
                                <div class="timeline">
                                    <?php foreach ($correspondingEmails->result() as $email) { ?>
                                        <div class="timeline_item">
                                            <a class="email-detail" data-id="<?php echo $email->thread_id; ?>" href="<?php echo $url ?>email-detail/<?php echo $email->id; ?>">
                                                <?php if ($email->to_email == $dealData->email) { ?>
                                                    <div class="timeline_icon timeline_icon_success">
                                                        <i class="material-icons">send</i>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($email->from_email == $dealData->email) { ?>
                                                    <div class="timeline_icon timeline_icon_success">
                                                        <i class="material-icons">email</i>
                                                    </div>
                                                <?php } ?>
                                                <div class="timeline_date">
                                                    <?php echo $email->converted_date_time; ?>
                                                </div>
                                                <div class="timeline_content"><?php echo $email->subject; ?></div>
                                                <div class="email-count"><?php echo $email->email_count; ?></div>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                <?php }
                }
                ?>
            </div>
        </div>
    </li> <?php } ?>
<?php if ($assess['can_overall_email']) { ?> <li>
        <div class="md-card">
            <div class="md-card-content">
                <?php
                if ($allEmails) {
                    if ($allEmails->num_rows() > 0) { ?>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-5-6">
                                <div class="timeline">
                                    <?php foreach ($allEmails->result() as $emails) {
                                    ?>

                                        <div class="timeline_item">
                                            <a class="email-detail" data-id="<?php echo $emails->thread_id; ?>" href="<?php echo $url ?>email-detail/<?php echo $emails->id; ?>">
                                                <?php if ($emails->to_email == $dealData->email) { ?>
                                                    <div class="timeline_icon timeline_icon_success">
                                                        <i class="material-icons">send</i>
                                                    </div>
                                                <?php } ?>
                                                <?php if ($emails->from_email == $dealData->email) { ?>
                                                    <div class="timeline_icon timeline_icon_success">
                                                        <i class="material-icons">email</i>
                                                    </div>
                                                <?php } ?>
                                                <div class="timeline_date">
                                                    <?php echo $emails->converted_date_time; ?>
                                                </div>
                                                <div class="timeline_content"><?php echo $emails->subject; ?></div>
                                                <div class="email-count"><?php echo $emails->email_count; ?></div>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                <?php }
                }
                ?>
            </div>
        </div>

    </li> <?php } ?>
<?php if ($assess['can_video_insights']) { ?> <li>
        <div class="md-card">
            <div class="md-card-content">
                <h4 class="heading_c uk-margin-small-bottom">Video Insights Details</h4>
                <ul class="md-list">
                    <?php if (isset($dealData->video_elephant)) : ?>
                        <li>
                            <div class="md-list-content">
                                <span class="md-list-heading">Video Elephant:</span>
                                <span class="uk-text-muted"><?php echo $dealData->video_elephant; ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <li>
                        <div class="md-list-content">
                            <span class="md-list-heading">Raw Video:</span>
                            <span class="uk-text-muted"><?php echo $dealData->raw_video; ?></span>
                        </div>
                    </li>
                    <?php if (isset($dealData->description_updated)) : ?>
                        <li>
                            <div class="md-list-content">
                                <span class="md-list-heading">Description Updated:</span>
                                <span class="uk-text-muted"><?php echo $dealData->description_updated; ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if ($dealData->status >= 3 && $dealData->information_pending == 1) { ?>
                        <li>

                            <div class="md-list-content" style="position: relative">
                                <span class="md-list-heading">Confidence Level:</span>
                                <span class="uk-text-muted">
                                    <?php if (!empty($dealData->confidence_level)) { ?>
                                        <?php echo $dealData->confidence_level ?>
                                    <?php } ?>
                                </span>
                                <span id="con_edit" class="md-list-heading con_edit_area" data-val=""></span>
                                <div class="co-div" style="position: absolute;top: 0;left: 162px;">
                                    <?php if ($assess['can_edit_confidence_level']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-con">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons">edit</i></button>
                                        <button class="btn btn-success btn-save" id="save-con"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-con"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="md-list-content" style="position: relative;overflow: visible;">
                                <span class="md-list-heading">WooGlobe Internal Notes:</span>
                                <span class="uk-text-muted" id="video_comment_id" data-value="<?php echo $dealData->video_comment ?>">
                                    <?php if (!empty($dealData->video_comment)) { ?>
                                        <?php echo $dealData->video_comment ?>
                                    <?php } else { ?>
                                        None
                                    <?php } ?>
                                </span>
                                <span id="video_comment_edit" class="md-list-heading video_comment_edit_area" data-val=""></span>
                                <span style="font-size:10px">Total word limit is 100</span>
                                <div class="co-div" style="position: absolute;top: -17px;left: 162px;">
                                    <?php if ($assess['can_edit_internal_notes']) { ?>
                                        <button class="btn btn-info btn-edit" id="edit-video-comment" style="right: -23px !important">
                                            <span class="glyphicon glyphicon-edit"></span><i class="material-icons">edit</i></button>
                                        <button class="btn btn-success btn-save" id="save-video-comment"><span class="glyphicon glyphicon-save"></span><i class="material-icons">done</i></button>
                                        <button class="btn btn-success btn-cancel" id="cancel-video-comment"><span class="glyphicon glyphicon-save"></span><i class="material-icons">close</i></button>
                                    <?php } ?>
                                </div>
                            </div>
                        </li>
                    <?php } ?>
                    <li>
                        <div class="md-list-content md-card user_timeline border-black">
                            <span class="md-list-heading">Exclusive Status:</span>
                            <span class="uk-text-muted"><?php echo $dealData->exclusive_status; ?></span>
                            <span class="md-list-heading">General Categories:</span>
                            <span class="uk-text-muted" id="general-categories-preview">None</span>
                            <?php if (isset($dealData->status) && isset($videoData->id) && ($dealData->status >= 6 && $dealData->status <= 10)) :  ?>
                                <span class="md-list-heading">Partner(s):</span>
                                <span class="uk-text-muted" id="partners-name-preview">None</span>
                                <span class="md-list-heading">Partner Categories:</span>
                                <span class="uk-text-muted" id="partner-categories-preview">None</span>
                            <?php endif; ?>
                        </div>
                    </li>
                    <!-- exclusive status html -->
                    <?php if (isset($dealData->status) && isset($videoData->id) && ($dealData->status >= 6 && $dealData->status <= 10)) :  ?>
                        <li>
                            <div class="md-list-content" style="clear:both">
                                <div id="default-mrss-vals" style="display:none">
                                    <span id="allow-mrss-def-val" value="<?php echo $allow_mrss; ?>"></span>
                                    <span id="is-partner-exclusive-def-val" value="<?php echo $partnership_type; ?>"></span>
                                    <span id="partnership-type-def-val" value="<?php echo $partnership_type; ?>"></span>
                                    <span id="selected-general-categories-def-val" value='<?php if (isset($video_selected_categories) && !empty($video_selected_categories)) echo json_encode($video_selected_categories); ?>'></span>
                                    <span id="partners-def-val" value='<?php if (!empty($partners_info)) echo json_encode($partners_info); ?>'></span>
                                </div>
                                <span class="md-list-heading">MRSS</span>
                                <div id="allow-mrrs-container" class="mb-10p">
                                    <input type="hidden" id="lead-video-id" value="<?php echo $videoData->id; ?>">
                                    <select id="allow-mrss" class="uk-width-medium-1-5 select-bs">
                                        <option value="no">No</option>
                                        <option value="yes">Yes</option>
                                    </select>
                                </div>
                                <div id="mrss-container">
                                    <div id="mrss-partner-container">

                                        <div id="general-categories-container" class="mb-10p">
                                            <span class="md-list-heading">General Categories:</span>
                                            <select id="general-categories" class="uk-width-medium-1-5 select-bs" multiple>
                                                <option value="">Select Categories</option>
                                                <?php foreach ($general_categories as $key => $category) : ?>
                                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['title']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div id="partnership-type-container" class="mb-10p">
                                            <span class="md-list-heading">Select partnership type:</span>
                                            <select id="is-partner-exclusive" class="uk-width-medium-1-5 select-bs">
                                                <option value="">Neither</option>
                                                <option value="1">Exclusive</option>
                                                <option value="2">Non-Exclusive</option>
                                            </select>
                                        </div>

                                        <div id="exclusive-partners-container" class="mb-10p">
                                            <span class="md-list-heading">Partners and respective categories:</span>
                                            <select id="exclusive-partners-list" class="uk-width-medium-1-5 select-bs">
                                                <option value="">Select Partner(s)</option>
                                                <?php
                                                $partner_ids = [];
                                                foreach ($mrss_partners->result() as $mrss) :
                                                    if (in_array($mrss->partner_id, $partner_ids) === FALSE) :
                                                ?>
                                                        <option value="<?php echo $mrss->partner_id; ?>"><?php echo $mrss->full_name; ?></option>
                                                <?php
                                                        array_push($partner_ids, $mrss->partner_id);
                                                    endif;
                                                endforeach;
                                                ?>
                                            </select>
                                        </div>
                                        <!-- Hidden fields start  -->
                                        <?php
                                        if (isset($edites3[0]->portal_thumb)) {
                                            if (!empty($edites3[0]->portal_thumb)) {
                                        ?>
                                                <input type="hidden" value="<?php echo $edites3[0]->portal_thumb; ?>" name="portal_thumb" id="portal_thumb">
                                        <?php }
                                        } ?>
                                        <?php
                                        $total = count($rawVideos->result());
                                        if ($total > 1) {
                                            if (isset($edites3[0])) {
                                                if (!empty($edites3[0]->portal_url)) {
                                        ?>
                                                    <input type="hidden" value="<?php echo $edites3[0]->portal_url; ?>" name="portal_video" id="portal_video">
                                                <?php }
                                            }
                                        } else {
                                            $rawresult = $rawVideos->result();
                                            if (isset($rawresult[0]->s3_url)) {
                                                if (!empty($rawresult[0]->s3_url)) {
                                                ?>
                                                    <input type="hidden" value="<?php echo $rawresult[0]->s3_url; ?>" name="portal_video" id="portal_video">
                                        <?php }
                                            }
                                        } ?>
                                        <?php if (isset($videoData->category_id)) { ?>
                                            <input type="hidden" value="<?php echo $videoData->category_id; ?>" name="video_cats" id="video_cats">
                                        <?php  } ?>
                                        <?php if (isset($videoData->tags)) { ?>
                                            <input type="hidden" value="<?php echo $videoData->tags; ?>" name="video_tags" id="video_tags">
                                        <?php  } ?>
                                        <!-- partner categories selection -->
                                        <div id="partner-categories-container" class="mb-10p">
                                            <select id="mrss-video-categories" class="uk-width-medium-1-5 select-bs" id2="mrss-partner-categories" multiple>
                                                <option value="" disabled>Select Categories</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                                <?php if ($assess['can_update_exclusive_status']) { ?>

                                    <div id="save-mrss-container" class="mb-10p">
                                        <button id="update-exclusive-status" class="uk-button uk-button-primary tooltip">Update Exclusive Status
                                            <span class="tooltip_text">
                                                <div class="check">Landscape Converted<?php echo ($rawVideos->result_array()[0]['landscape_converted_url'] != NULL)? '<i class="material-icons checked">check_circle</i>': '<i class="material-icons unchecked">cancel</i>'?></div>
                                                <div class="check">Dropbox Url<?php echo (isset($edites3[0]) && !empty($edites3[0]->portal_url))? '<i class="material-icons checked">check_circle</i>': '<i class="material-icons unchecked">cancel</i>'?></div>
                                                <div class="check">Youtube Published<?php echo (!empty($videoData->youtube_id))? '<i class="material-icons checked">check_circle</i>': '<i class="material-icons unchecked">cancel</i>'?></div>
                                                <div class="check">Content/Newswire<?php echo $dealData->is_cn_updated? '<i class="material-icons checked">check_circle</i>': '<i class="material-icons unchecked">cancel</i>'?></div>
                                                <div class="check">Video Edited<?php echo $dealData->uploaded_edited_videos? '<i class="material-icons checked">check_circle</i>': '<i class="material-icons unchecked">cancel</i>'?></div>
                                            </span>
                                        </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </li>
                    <?php endif; ?>
                    <!-- exclusive status html -->
                    <?php if (isset($dealData->youtube)) : ?>
                        <li>
                            <div class="md-list-content" style="clear:both">
                                <span class="md-list-heading">Uploaded on YouTube:</span>
                                <span class="uk-text-muted"><?php echo $dealData->youtube; ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($dealData->facebook)) : ?>
                        <li>
                            <div class="md-list-content">
                                <span class="md-list-heading">Uploaded on Facebook:</span>
                                <span class="uk-text-muted"><?php echo $dealData->facebook; ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                    <?php if (isset($dealData->instagram)) : ?>
                        <li>
                            <div class="md-list-content">
                                <span class="md-list-heading">Uploaded on Instagram:</span>
                                <span class="uk-text-muted"><?php echo $dealData->instagram; ?></span>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </li> <?php } ?>
<?php if ($assess['earnings_list'] || $assess['expense_list'] || $assess['can_deal_payment']) { ?>
    <?php if (isset($videoData->id) && $videoData->id > 0) { ?>
        <li>
            <div class="md-card">
                <div class="md-card-content">
                    <h2 class="heading_c uk-margin-small-bottom">Earnings</h2>
                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-2">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-1">
                                    <h4>Amount Accumulated Towards Next Payment</h4>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-1">
                                    <h4 id="next_payment" style="margin-left: 0px;"><?php echo $currency; ?><?php if (empty($next_payment)) {
                                                                                                                echo 0;
                                                                                                            } else {
                                                                                                                echo $next_payment;
                                                                                                            } ?></h4>
                                </div>
                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-1">
                                    <h4>Lifetime Paid</h4>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-1">
                                    <h4 style="margin-left: 0px;"><?php echo $lifetime_paid_currency; ?><?php if (empty($paid)) {
                                                                                                            echo 0;
                                                                                                        } else {
                                                                                                            echo $paid;
                                                                                                        } ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="md-card uk-margin-medium-bottom">
                        <?php if ($assess['earnings_can_add'] || $assess['add_new_earning']) { ?>
                            <div id="save-mrss-container" class="mb-10p uk-align-right .uk-margin-large-top uk-margin-large-right" style="margin-top: 10px;">
                                <button title="Add Earning" id="" class="uk-button uk-button-primary add-earning" data-id="<?php echo $videoData->id; ?>" data-title="<?php echo $videoData->title; ?>">Add New Earning</button>
                            </div>
                            <div id="save-mrss-container-2" class="mb-10p uk-align-right .uk-margin-large-top" style="margin-top: 10px;">
                                <button title="Pay Advance" id="" class="uk-button uk-button-primary add-earning-advance" data-id="<?php echo $videoData->id; ?>" data-title="<?php echo $videoData->title; ?>">Pay Advance</button>
                            </div>
                        <?php } ?>

                        <div class="md-card-content">
                            <div class="dt_colVis_buttons"></div>
                            <table id="dt_tableExport" class="uk-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <?php if ($assess['earnings_can_edit'] || $assess['earnings_can_delete'] || $assess['earnings_delete'] || $assess['earnings_edit']) { ?>
                                            <th data-name="c.action">Actions</th>
                                        <?php } ?>
                                        <th data-name="e.earning_date">Earning Date</th>
                                        <th data-name="vl.uid">WGID</th>
                                        <th data-name="e.earning_amount">Earning</th>
                                        <th data-name="e.expense">Expense</th>
                                        <th data-name="e.client_net_earning">Client Net Earnings</th>
                                        <th data-name="e.wooglobe_total_share">WooGlobe's Total Share</th>
                                        <th data-name="e.paid">Earning Mode</th>
                                        <th data-name="et.earning_type">Earning Source</th>
                                        <th data-name="e.partner_currency">Partner Currency</th>
                                        <th data-name="e.currency_id ">Client Currency</th>
                                        <th data-name="e.actual_amount">Actual Amount</th>
                                        <th data-name="e.conversion_rate">Conversion Rate</th>
                                        <th data-name="e.status">Status</th>
                                        <!-- <th data-name="e.transaction_id">Partner Earning Id</th>
                                                             <th data-name="e.transaction_detail">Partner Earning Detail</th>-->

                                        <!--<th data-name="e.expense_detail">Expense Detail</th>-->

                                    </tr>
                                </thead>

                                <tfoot>
                                    <tr>
                                        <?php if ($assess['earnings_can_edit'] || $assess['earnings_can_delete']) { ?>
                                            <th data-name="c.action">Actions</th>
                                        <?php } ?>
                                        <th data-name="e.earning_date">Earning Date</th>
                                        <th data-name="vl.uid">WGID</th>
                                        <th data-name="e.earning_amount">Earning</th>
                                        <th data-name="e.expense">Expense</th>
                                        <th data-name="e.client_net_earning">Client Net Earnings</th>
                                        <th data-name="e.wooglobe_total_share">WooGlobe's Total Share</th>
                                        <th data-name="e.paid">Earning Mode</th>
                                        <th data-name="et.earning_type">Earning Source</th>
                                        <th data-name="e.partner_currency">Partner Currency</th>
                                        <th data-name="e.currency_id ">Client Currency</th>
                                        <th data-name="e.actual_amount">Actual Amount</th>
                                        <th data-name="e.conversion_rate">Conversion Rate</th>
                                        <th data-name="e.status">Status</th>

                                    </tr>
                                </tfoot>

                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </li>

    <?php } ?>
<?php } ?>
<?php if ($assess['can_video_story']) { ?>
    <li>
        <div class="md-card">
            <div class="md-card-content">
                <div class="mrss-row">
                    <?php foreach ($mrss_brand_feeds as $mbf) { ?>
                        <div id="<?php echo 'mrss-task-' . $mbf->id ?>" class="mrss-task">
                            <div class="mrss_thumb">
                                <img src="<?php echo (!empty($mbf->story_thumb_path)) ? $mbf->story_thumb_path : $edites3[0]->portal_thumb; ?>" alt="Thumbnail">
                            </div>
                            <p class="mrss_details"><b>Title: </b><?php echo $mbf->story_title ?></p>
                            <div class="mrss_desc"> <b>Description: </b><?php echo $mbf->story_description ?></div>
                            <p class="mrss_details"><b>Tags: </b><?php echo $mbf->story_tags ?></p>
                            <p class="mrss_details"><b>Partner: </b><?php echo $mbf->full_name ?></p>
                            <p class="mrss_details"><b>Brand: </b><?php echo $mbf->brand_name ?></p>
                            <p class="mrss_details"><b>Video URL: </b><?php echo ($mbf->story_video_s3_url) ? $mbf->story_video_s3_url : $mbf->story_video_path ?></p>
                            <p class="mrss_details"><b>Thumbnail URL: </b><?php echo ($mbf->story_thumb_s3_url) ? $mbf->story_thumb_s3_url : $mbf->story_thumb_path ?></p>
                            <div class="mrss_card_btn">
                                <button id="mrss-edit-btn" data-id="<?php echo $mbf->id ?>"><i class="material-icons" style="color:#ffffff;">edit</i></button>
                                <button id="mrss-delete-btn" data-id="<?php echo $mbf->id ?>"><i class="material-icons" style="color:#ffffff;">delete</i></button>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="add_mrss" id="add_mrss">
                        <button id="mrss-upload-btn" class="md-btn-info add_mrss_btn">
                            <i class="material-icons">add</i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </li>
<?php } ?>

<?php if ($assess['earnings_list'] || $assess['expense_list'] || $assess['can_deal_payment']) { ?>
    <?php if (isset($videoData->id) && $videoData->id > 0) { ?>
        <?php if ($assess['earnings_can_add']) { ?>
            <div class="uk-modal" id="earning_model">
                <div class="uk-modal-dialog">
                    <div class="uk-modal-header">
                        <h3 class="uk-modal-title">Add New Earning (<span id="video-title" style="display: inline;"></span>)</h3>
                    </div>
                    <div class="md-card-content large-padding">
                        <form id="form_validation15" class="uk-form-stacked">
                            <input type="hidden" name="expense_amount" id="expense_amount" value="">
                            <input type="hidden" name="wooglobe_net_earning" id="wooglobe_net_earning" value="">
                            <input type="hidden" name="wooglobe_total_share" id="wooglobe_total_share" value="">
                            <input type="hidden" name="revenue_share_amount" id="revenue_share_amount" value="">
                            <input type="hidden" name="client_net_earning" id="client_net_earning" value="">
                            <input type="hidden" id="ern_video_id" name="video_id" value="">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Client Country : <span class="uk-text-small uk-text-muted"><?php echo $country_name ?></span></label>

                                </div>

                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="partner_currency">Partner Currency</label>
                                    <div class="parsley-row">
                                        <select id="partner_currency" name="partner_currency" required data-parsley-required-message="This field is required." data-md-selectize>
                                            <option value="">Partner Currency*</option>

                                            <?php if ($currency_id > 0) { ?>
                                                <?php foreach ($currencies as $currency) {
                                                    if ($currency_id == $currency->id) {
                                                ?>
                                                        <option selected value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php   }
                                                } ?>
                                            <?php } else { ?>
                                                <?php foreach ($currencies as $currency) { ?>
                                                    <option <?php if ($currency->code == 'USD') {
                                                                echo "selected";
                                                            } ?> value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php
                                                } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label for="actual_amount">Actual Amount</label>
                                        <input type="text" id="actual_amount" name="actual_amount" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <label for="currency_id">Currency</label>
                                    <div class="parsley-row">
                                        <select id="currency_id" name="currency_id" required data-parsley-required-message="This field is required." data-md-selectize>
                                            <option value="">Currency*</option>

                                            <?php if ($currency_id > 0) { ?>
                                                <?php foreach ($currencies as $currency) {
                                                    if ($currency_id == $currency->id) {
                                                ?>
                                                        <option selected value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php   }
                                                } ?>
                                            <?php } else { ?>
                                                <?php foreach ($currencies as $currency) { ?>
                                                    <option <?php if ($currency->code == 'USD') {
                                                                echo "selected";
                                                            } ?> value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php
                                                } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label>Earning</label>
                                        <input type="text" id="earning_amount" name="earning_amount" readonly data-parsley-pattern="^\d*(\.\d{0,4})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-1" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label for="conversion_rate">Conversion Rate</label>
                                        <input type="text" id="conversion_rate" readonly name="conversion_rate" data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>


                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <div class="uk-input-group">
                                            <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                            <div class="md-input-wrapper">
                                                <label for="closing">Select Earning Date</label>
                                                <input class="md-input" id="earning_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" type="text" name="earning_date" data-parsley-required-message="This field is required." readonly required>
                                                <span class="md-input-bar "></span>
                                            </div>
                                            <div class="error"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="transaction_id">Transaction Id</label>
                                        <input type="text" id="transaction_id" name="transaction_id" required data-parsley-required-message="This field is required." class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-1 uk-row-first">
                                    <div class="parsley-row">
                                        <div class="md-input-wrapper">
                                            <label for="transaction_detail" class="uk-form-label">Transaction Detail</label>
                                            <textarea id="transaction_detail" name="transaction_detail" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;"></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select id="earning_type_id" name="earning_type_id" required data-parsley-required-message="This field is required." data-md-selectize>
                                            <option value="">Earning Type*</option>
                                            <?php foreach ($earning_type->result() as $type) {
                                                if ($type->id <= 2) {
                                            ?>
                                                    <option value="<?php echo $type->id; ?>"><?php echo $type->earning_type; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2 ms_a" id="ss_a" style="display: none;">
                                    <div class="parsley-row">
                                        <select id="social_source_id" name="social_source_id" data-parsley-required-message="This field is required." data-md-selectize>
                                            <option value="">Social Sources*</option>
                                            <?php foreach ($sources->result() as $source) { ?>
                                                <option value="<?php echo $source->id; ?>"><?php echo $source->sources; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2 ms_a" id="p_a" style="display: none;">
                                    <div class="parsley-row">
                                        <select id="partner_id" name="partner_id" data-parsley-required-message="This field is required." data-md-selectize>
                                            <option value="">Partner*</option>
                                            <?php foreach ($partners->result() as $partner) { ?>
                                                <option value="<?php echo $partner->id; ?>"><?php echo $partner->full_name . ' (' . $partner->email . ')'; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>


                            </div>

                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-1">
                                    <div class="parsley-row">
                                        <label for="expense">Expense %</label>
                                        <input type="text" id="expense" name="expense" data-parsley-required-message="This field is required." value="30" min="0" max="100" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Revenue Share % : <span class="uk-text-small uk-text-muted" id="revenue_share_earning"><?php echo $dealData->revenue_share; ?></span></label>

                                </div>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Expense Amount : <span class="uk-text-small uk-text-muted" id="expense_earning"></span></label>

                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Wooglobe Net Earning : <span class="uk-text-small uk-text-muted" id="wooglobe_net_earning_text"></span></label>

                                </div>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Client Net Earning : <span class="uk-text-small uk-text-muted" id="client_net_earning_text"></span></label>

                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-1">
                                    <label for="" class="uk-form-label">Wooglobe Total Share : <span class="uk-text-small uk-text-muted" id="wooglobe_total_earning"></span></label>

                                </div>

                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-1 uk-row-first">
                                    <div class="parsley-row">
                                        <div class="md-input-wrapper">
                                            <label for="expense_detail" class="uk-form-label">Expense Detail</label>
                                            <textarea id="expense_detail" name="expense_detail" class="md-input autosized" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;"></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>



                        </form>
                    </div>
                    <div class="uk-modal-footer uk-text-right" style="margin-top:20px; ">
                        <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="add_earning_from" class="md-btn md-btn-flat md-btn-flat-primary">Add</button>
                    </div>
                </div>
            </div>
            <div class="uk-modal" id="earning_model_advance">
                <div class="uk-modal-dialog">
                    <div class="uk-modal-header">
                        <h3 class="uk-modal-title">Pay Advance (<span id="video-title-advance" style="display: inline;"></span>)</h3>
                    </div>
                    <div class="md-card-content large-padding">
                        <form id="form_validation000" class="uk-form-stacked">
                            <input type="hidden" name="expense_amount" value="0">
                            <input type="hidden" name="wooglobe_net_earning" value="0">
                            <input type="hidden" name="wooglobe_total_share" value="0">
                            <input type="hidden" name="revenue_share_amount" value="0">
                            <input type="hidden" name="advanced" value="1">
                            <input type="hidden" name="client_net_earning" id="client_net_earning_advance" value="">
                            <input type="hidden" id="ern_video_id_advance" name="video_id" value="">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Client Country : <span class="uk-text-small uk-text-muted"><?php echo $country_name ?></span></label>

                                </div>

                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="partner_currency">Partner Currency</label>
                                    <div class="parsley-row">
                                        <select id="partner_currency_advance" name="partner_currency" required data-parsley-required-message="This field is required." data-md-selectize>
                                            <option value="">Partner Currency*</option>

                                            <?php if ($currency_id > 0) { ?>
                                                <?php foreach ($currencies as $currency) {
                                                    if ($currency_id == $currency->id) {
                                                ?>
                                                        <option selected value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php   }
                                                } ?>
                                            <?php } else { ?>
                                                <?php foreach ($currencies as $currency) { ?>
                                                    <option <?php if ($currency->code == 'USD') {
                                                                echo "selected";
                                                            } ?> value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php
                                                } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label for="actual_amount">Actual Amount</label>
                                        <input type="text" id="actual_amount" name="actual_amount" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-1" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label for="conversion_rate">Conversion Rate</label>
                                        <input type="text" id="conversion_rate_advance" name="conversion_rate" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <label for="currency_id">Currency</label>
                                    <div class="parsley-row">
                                        <select id="currency_id_advance" name="currency_id" required data-parsley-required-message="This field is required." data-md-selectize>
                                            <option value="">Currency*</option>

                                            <?php if ($currency_id > 0) { ?>
                                                <?php foreach ($currencies as $currency) {
                                                    if ($currency_id == $currency->id) {
                                                ?>
                                                        <option selected value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php   }
                                                } ?>
                                            <?php } else { ?>
                                                <?php foreach ($currencies as $currency) { ?>
                                                    <option <?php if ($currency->code == 'USD') {
                                                                echo "selected";
                                                            } ?> value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php
                                                } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label for="earning_amount">Advance</label>
                                        <input type="text" id="earning_amount_advance" name="earning_amount" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,4})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>


                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <div class="uk-input-group">
                                            <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                            <div class="md-input-wrapper">
                                                <label for="closing">Select Advance Date</label>
                                                <input class="md-input" id="earning_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" type="text" name="earning_date" data-parsley-required-message="This field is required." readonly required>
                                                <span class="md-input-bar "></span>
                                            </div>
                                            <div class="error"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="transaction_id">Transaction Id</label>
                                        <input type="text" id="transaction_id" name="transaction_id" required data-parsley-required-message="This field is required." class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-1 uk-row-first">
                                    <div class="parsley-row">
                                        <div class="md-input-wrapper">
                                            <label for="transaction_detail" class="uk-form-label">Transaction Detail</label>
                                            <textarea id="transaction_detail_advance" name="transaction_detail" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;"></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select id="earning_type_id_advance" name="earning_type_id" required data-parsley-required-message="This field is required." data-md-selectize>
                                            <option value="">Earning Type*</option>
                                            <?php foreach ($earning_type->result() as $type) {
                                                if ($type->id == 3) {
                                            ?>
                                                    <option value="<?php echo $type->id; ?>"><?php echo $type->earning_type; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>

                                <div class="uk-width-medium-1-2 ms_a" id="p_a" style="display: none;">
                                    <div class="parsley-row">
                                        <select id="partner_id_advance" name="partner_id" data-parsley-required-message="This field is required." data-md-selectize>
                                            <option value="">Partner*</option>
                                            <?php foreach ($partners->result() as $partner) { ?>
                                                <option value="<?php echo $partner->id; ?>"><?php echo $partner->full_name . ' (' . $partner->email . ')'; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>


                            </div>









                        </form>
                    </div>
                    <div class="uk-modal-footer uk-text-right" style="margin-top:20px; ">
                        <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="add_earning_from_advance" class="md-btn md-btn-flat md-btn-flat-primary">Pay</button>
                    </div>
                </div>
            </div>

        <?php } ?>
        <?php if ($assess['earnings_can_edit']) { ?>
            <div class="uk-modal" id="edit_model">
                <div class="uk-modal-dialog">
                    <div class="uk-modal-header">
                        <h3 class="uk-modal-title">Edit Earning (<span id="video-title-id" style="display: inline;"></span>)</h3>
                    </div>
                    <div class="md-card-content large-padding">
                        <form id="form_validation16" class="uk-form-stacked">
                            <input type="hidden" name="expense_amount" id="expense_amount_e" value="">
                            <input type="hidden" name="wooglobe_net_earning" id="wooglobe_net_earning_e" value="">
                            <input type="hidden" name="wooglobe_total_share" id="wooglobe_total_share_e" value="">
                            <input type="hidden" name="revenue_share_amount" id="revenue_share_amount_e" value="">
                            <input type="hidden" name="client_net_earning" id="client_net_earning_e" value="">
                            <input type="hidden" name="id" id="id_e" value="">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Client Country : <span class="uk-text-small uk-text-muted"><?php echo $country_name ?></span></label>

                                </div>

                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="partner_currency">Partner Currency</label>
                                    <div class="parsley-row">
                                        <select id="partner_currency_e" name="partner_currency" required data-parsley-required-message="This field is required." data-md-selectize>
                                            <option value="">Partner Currency*</option>

                                            <?php if ($currency_id > 0) { ?>
                                                <?php foreach ($currencies as $currency) {
                                                    if ($currency_id == $currency->id) {
                                                ?>
                                                        <option selected value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php   }
                                                } ?>
                                            <?php } else { ?>
                                                <?php foreach ($currencies as $currency) { ?>
                                                    <option <?php if ($currency->code == 'USD') {
                                                                echo "selected";
                                                            } ?> value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php
                                                } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label for="actual_amount">Actual Amount</label>
                                        <input type="text" id="actual_amount_e" name="actual_amount" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>

                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select id="currency_id_e" name="currency_id" required data-parsley-required-message="This field is required." data-md-selectize select4>
                                            <option value="">Currency*</option>
                                            <?php if ($currency_id > 0) { ?>
                                                <?php foreach ($currencies as $currency) {
                                                    if ($currency_id == $currency->id) {
                                                ?>
                                                        <option selected value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php   }
                                                } ?>
                                            <?php } else { ?>
                                                <?php foreach ($currencies as $currency) { ?>
                                                    <option <?php if ($currency->code == 'USD') {
                                                                echo "selected";
                                                            } ?> value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php
                                                } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label for="earning_amount">Earning</label>
                                        <input type="text" id="earning_amount_e" name="earning_amount" readonly data-parsley-pattern="^\d*(\.\d{0,4})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-1" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label for="conversion_rate">Conversion Rate</label>
                                        <input type="text" id="conversion_rate_e" name="conversion_rate" readonly data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>




                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <div class="uk-input-group">
                                            <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                            <div class="md-input-wrapper">
                                                <label for="closing">Select Earning Date</label>
                                                <input class="md-input" id="earning_date_e" data-uk-datepicker="{format:'YYYY-MM-DD'}" type="text" name="earning_date" data-parsley-required-message="This field is required." readonly required>
                                                <span class="md-input-bar "></span>
                                            </div>
                                            <div class="error"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="transaction_id_">Transaction Id</label>
                                        <input type="text" id="transaction_id_e" name="transaction_id" required data-parsley-required-message="This field is required." class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-1 uk-row-first">
                                    <div class="parsley-row">
                                        <div class="md-input-wrapper">
                                            <label for="transaction_detail_e" class="uk-form-label">Transaction Detail</label>
                                            <textarea id="transaction_detail_e" name="transaction_detail" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;"></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select id="earning_type_id_e" name="earning_type_id" required data-parsley-required-message="This field is required." data-md-selectize select1>
                                            <option value="">Earning Type*</option>
                                            <?php foreach ($earning_type->result() as $type) { ?>
                                                <option value="<?php echo $type->id; ?>"><?php echo $type->earning_type; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2 ms" id="ss" style="display: none;">
                                    <div class="parsley-row">
                                        <select id="social_source_id_e" name="social_source_id" data-parsley-required-message="This field is required." data-md-selectize select2>
                                            <option value="">Social Sources*</option>
                                            <?php foreach ($sources->result() as $source) { ?>
                                                <option value="<?php echo $source->id; ?>"><?php echo $source->sources; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2 ms" id="p" style="display: none;">
                                    <div class="parsley-row">
                                        <select id="partner_id_e" name="partner_id" data-parsley-required-message="This field is required." data-md-selectize select3>
                                            <option value="">Partner*</option>
                                            <?php foreach ($partners->result() as $partner) { ?>
                                                <option value="<?php echo $partner->id; ?>"><?php echo $partner->full_name . ' (' . $partner->email . ')'; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>


                            </div>

                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-1">
                                    <div class="parsley-row">
                                        <label for="expense_e">Expense %</label>
                                        <input type="text" id="expense_e" name="expense" data-parsley-required-message="This field is required." value="30" min="0" max="100" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Revenue Share % : <span class="uk-text-small uk-text-muted" id="revenue_share_earning_e"><?php echo $dealData->revenue_share; ?></span></label>

                                </div>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Expense Amount : <span class="uk-text-small uk-text-muted" id="expense_earning_e"></span></label>

                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Wooglobe Net Earning : <span class="uk-text-small uk-text-muted" id="wooglobe_net_earning_text_e"></span></label>

                                </div>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Client Net Earning : <span class="uk-text-small uk-text-muted" id="client_net_earning_text_e"></span></label>

                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-1">
                                    <label for="" class="uk-form-label">Wooglobe Total Share : <span class="uk-text-small uk-text-muted" id="wooglobe_total_earning_e"></span></label>

                                </div>

                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-1 uk-row-first">
                                    <div class="parsley-row">
                                        <div class="md-input-wrapper">
                                            <label for="expense_detail_e" class="uk-form-label">Expense Detail</label>
                                            <textarea id="expense_detail_e" name="expense_detail" class="md-input autosized" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;"></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>



                        </form>
                    </div>
                    <div class="uk-modal-footer uk-text-right" style="margin-top: 20px;">
                        <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="edit_from" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
                    </div>
                </div>
            </div>
            <div class="uk-modal" id="edit_model_advance">
                <div class="uk-modal-dialog">
                    <div class="uk-modal-header">
                        <h3 class="uk-modal-title">Edit Advance (<span id="video-title-id-advance" style="display: inline;"></span>)</h3>
                    </div>
                    <div class="md-card-content large-padding">
                        <form id="form_validation111" class="uk-form-stacked">
                            <input type="hidden" name="expense_amount" value="0">
                            <input type="hidden" name="wooglobe_net_earning" value="0">
                            <input type="hidden" name="wooglobe_total_share" value="0">
                            <input type="hidden" name="revenue_share_amount" value="0">
                            <input type="hidden" name="client_net_earning" id="client_net_earning_e_advance" value="">
                            <input type="hidden" name="id" id="id_e_advance" value="">
                            <input type="hidden" name="advanced" value="0">
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="" class="uk-form-label">Client Country : <span class="uk-text-small uk-text-muted"><?php echo $country_name ?></span></label>

                                </div>

                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <label for="partner_currency">Partner Currency</label>
                                    <div class="parsley-row">
                                        <select id="partner_currency_e_advance" name="partner_currency" required data-parsley-required-message="This field is required." data-md-selectize>
                                            <option value="">Partner Currency*</option>

                                            <?php if ($currency_id > 0) { ?>
                                                <?php foreach ($currencies as $currency) {
                                                    if ($currency_id == $currency->id) {
                                                ?>
                                                        <option selected value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php   }
                                                } ?>
                                            <?php } else { ?>
                                                <?php foreach ($currencies as $currency) { ?>
                                                    <option <?php if ($currency->code == 'USD') {
                                                                echo "selected";
                                                            } ?> value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php
                                                } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label for="actual_amount">Actual Amount</label>
                                        <input type="text" id="actual_amount_e_advance" name="actual_amount" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-1" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label for="conversion_rate">Conversion Rate</label>
                                        <input type="text" id="conversion_rate_e_advance" name="conversion_rate" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,2})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select id="currency_id_e_advance" name="currency_id" required data-parsley-required-message="This field is required." data-md-selectize select4 disabled>
                                            <option value="">Currency*</option>
                                            <?php if ($currency_id > 0) { ?>
                                                <?php foreach ($currencies as $currency) {
                                                    if ($currency_id == $currency->id) {
                                                ?>
                                                        <option selected value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php   }
                                                } ?>
                                            <?php } else { ?>
                                                <?php foreach ($currencies as $currency) { ?>
                                                    <option <?php if ($currency->code == 'USD') {
                                                                echo "selected";
                                                            } ?> value="<?php echo $currency->id; ?>"><?php echo $currency->code; ?></option>
                                                <?php
                                                } ?>
                                            <?php } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2" style="margin-top: 20px;">
                                    <div class="parsley-row">
                                        <label for="earning_amount">Advance</label>
                                        <input type="text" id="earning_amount_e_advance" name="earning_amount" required data-parsley-required-message="This field is required." data-parsley-pattern-message="Please enter the valid earning like 1 or 1.00 " data-parsley-pattern="^\d*(\.\d{0,4})?$" class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>




                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <div class="uk-input-group">
                                            <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                            <div class="md-input-wrapper">
                                                <label for="closing">Select Earning Date</label>
                                                <input class="md-input" id="earning_date_e_advance" data-uk-datepicker="{format:'YYYY-MM-DD'}" type="text" name="earning_date" data-parsley-required-message="This field is required." readonly required>
                                                <span class="md-input-bar "></span>
                                            </div>
                                            <div class="error"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="transaction_id_">Transaction Id</label>
                                        <input type="text" id="transaction_id_e_advance" name="transaction_id" required data-parsley-required-message="This field is required." class="md-input">

                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-1 uk-row-first">
                                    <div class="parsley-row">
                                        <div class="md-input-wrapper">
                                            <label for="transaction_detail_e" class="uk-form-label">Transaction Detail</label>
                                            <textarea id="transaction_detail_e_advance" name="transaction_detail" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;"></textarea>
                                            <span class="md-input-bar "></span>
                                        </div>
                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select id="earning_type_id_e" name="earning_type_id" required data-parsley-required-message="This field is required." data-md-selectize select1>
                                            <option value="">Earning Type*</option>
                                            <?php foreach ($earning_type->result() as $type) {
                                                if ($type->id == 3) {
                                            ?>
                                                    <option selected value="<?php echo $type->id; ?>"><?php echo $type->earning_type; ?></option>
                                            <?php }
                                            } ?>
                                        </select>
                                        <div class="error"></div>
                                    </div>
                                </div>



                            </div>






                        </form>
                    </div>
                    <div class="uk-modal-footer uk-text-right" style="margin-top: 20px;">
                        <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="edit_from_advance" class="md-btn md-btn-flat md-btn-flat-primary">Save</button>
                    </div>
                </div>
            </div>
        <?php } ?>

        <div class="uk-modal" id="transaction_model">
            <div class="uk-modal-dialog">
                <div class="uk-modal-header">
                    <h3 class="uk-modal-title">Transaction Detail</h3>
                </div>
                <div class="md-card-content large-padding">
                    <form id="form_validation16" class="uk-form-stacked">

                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-1 uk-row-first">

                                <label for="" class="uk-form-label">Transaction Date</label>
                                <span class="uk-text-small uk-text-muted" id="transaction_detail_date"></span>

                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-1 uk-row-first">
                                <label for="" class="uk-form-label">Transaction Id</label>
                                <span class="uk-text-small uk-text-muted" id="transaction_detail_id"></span>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin="">
                            <div class="uk-width-medium-1-1 uk-row-first">
                                <label for="" class="uk-form-label">Transaction Detail</label>
                                <span class="uk-text-small uk-text-muted" id="transaction_detail_detail"></span>
                            </div>


                    </form>
                </div>
                <div class="uk-modal-footer uk-text-right" style="margin-top: 20px;">
                    <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                </div>
            </div>
        </div>

    <?php } ?>
<?php } ?>
</ul>
</div>
</div>
</div>
<!-- <div class="uk-width-large-3-10 hidden-print">
                <div class="md-card">
                    <div class="md-card-content">
                        <div class="uk-margin-medium-bottom">
                            <h3 class="heading_c uk-margin-bottom">Raw Videos</h3>
                            <?php /*if($rawVideos->num_rows() > 0){*/ ?>
                                <ul class="md-list md-list-addon">
                                    <?php /*foreach ($rawVideos->result() as $video){*/ ?>
                                        <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-1">
                                                <div class="md-card-head md-bg-grey-900">
                                                    <div class="uk-cover uk-position-relative uk-height-1-1 transform-origin-50" >
                                                        <video style="width: 100%;height: 100%;" controls ><source src="<?php /*echo $root.$video->url;*/ ?>">Your browser does not support HTML5 video.</video>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php /*} */ ?>
                                </ul>
                            <?php /*} */ ?>
                        </div>
                    </div>
                </div>
            </div>-->
<?php if ($assess['can_client_add']) { ?>
    <div class="uk-modal" id="add_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Add New Client </h3>
            </div>
            <div class="md-card-content large-padding">
                <form id="form_validation2" class="uk-form-stacked">
                    <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $dealData->id; ?>">
                    <input type="hidden" name="client_id" id="client_id" value="<?php echo $dealData->client_id; ?>">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="full_name">Full Name<span class="req">*</span></label>
                                <input type="text" data-parsley-required-message="This field is required." name="full_name" id="full_name" required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>


                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="email">Email Address<span class="req">*</span></label>
                                <input type="email" data-parsley-required-message="This field is required." name="email" id="email" required class="md-input" data-parsley-type-message="Please enter the valid email address." />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">

                                <select id="status" name="status" required data-parsley-required-message="This field is required." class="md-input">
                                    <option value="">Status*</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                <button type="button" id="add_from" class="md-btn md-btn-flat md-btn-flat-primary">Add</button>
            </div>
        </div>
    </div>
<?php } ?>
</div>
</div>
</div>
<?php if ($assess['can_delete_lead']) { ?>
    <div class="uk-modal" id="cancelform">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Cancel Contract </h3>
            </div>

            <div class="md-card-content large-padding">
                <form id="form_validation6" class="uk-form-stacked">
                    <input type="hidden" id="cf_lead_id" name="lead_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="cancel_comments" class="uk-form-label">Cancel Contract Comments<span class="req">*</span></label>
                                <textarea id="cancel_comments" name="cancel_comments" class="md-input" data-parsley-required-message="Comments are required." value="" required>You have submitted the video to another party before submission to WooGlobe. That party is claiming ownership to the clip and hence we cannot proceed</textarea>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-1" style="margin-top:10px;margin-bottom:10px;">
                        <input type="checkbox" name="mail_partner" id="mail_partner" value="1" class="email_preview_check" data-id="<?php echo $dealData->id; ?>" />
                        <label for="mail_partner" class="inline-label"><b>Send Email To Partners</b></label>
                    </div>

                    <div class="uk-width-medium-1-1" style="margin-top:10px;margin-bottom:10px;">
                        <input type="checkbox" name="mail_owner" id="mail_owner" value="1" class="email_preview_check" data-id="<?php echo $dealData->id; ?>" />
                        <label for="mail_owner" class="inline-label"><b>Send Email To Owner</b></label>
                    </div>
                    <div class="uk-width-medium-1-1" style="margin-top:10px;margin-bottom:10px;">
                        <input <?php if ($dealData->delete_contract == 1) {
                                    echo 'checked';
                                } ?> type="checkbox" name="delete_contract" id="delete_contract" value="1" data-md-icheck />
                        <label for="delete_contract" class="inline-label"><b>Delete Contract</b>
                        </label>
                    </div>

                </form>

                <!-- Email Navigation -->
                <div class="uk-grid" data-uk-grid-margin style="margin-left:0px !important;">
                    <div class="uk-width-medium-1-1" style="padding: 0px !important;">
                        <div class="parsley-row" id="email_preview_btns">
                            
                        </div>
                    </div>
                </div>

                <!-- Email Preview -->
                <div class="uk-grid" data-uk-grid-margin style="margin: 0px !important;">
                    <div class="uk-width-medium-1-1" style="padding: 0px !important;">
                        <div class="parsley-row" id="cancellation_email_preview">

                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                <button type="button" id="cancel_submit" class="md-btn md-btn-flat md-btn-flat-primary">Delete</button>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($assess['can_revenue_update']) { ?>
    <div class="uk-modal" id="revenue_modal">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Deal Revenue Update (<label class="dt"></label> ) </h3>
            </div>

            <div class="md-card-content large-padding">
                <form id="form_validation4" class="uk-form-stacked">
                    <input type="hidden" id="ru_lead_id" name="lead_id" value="">
                    <input type="hidden" id="ru_sent" name="sent" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="revenue_share" class="uk-form-label">Revenue Share - %<span class="req">*</span></label>
                                <input id="revenue_share" name="revenue_share" class="md-input" data-parsley-required-message="Revenue Share is required." data-parsley-type="integer" data-parsley-type-message="Please enter the valid value." data-parsley-range="[0, 100]" data-parsley-range-message="Revenue Share must be between 0 to 100." value="" required />
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                <button type="button" id="revenue_submit" class="md-btn md-btn-flat md-btn-flat-primary">Update</button>
            </div>
        </div>
    </div>
<?php } ?>

<div class="uk-modal" id="cn-modal">
    <div class="uk-modal-dialog cn-ve-modal">
        <div class="uk-modal-header uk-text-center">
            <h3 class="uk-modal-title"> Confirm Changes </h3>
        </div>
        <div class="uk-modal-footer uk-text-center">
            <button type="button" id="cn_modal_no" class="md-btn md-btn-flat md-btn-danger uk-margin-medium-right uk-modal-close">No</button><button type="button" id="update-cn" data-id="<?php echo $lead_id; ?>" class="md-btn md-btn-flat md-btn-primary">Yes</button>
        </div>
    </div>
</div>
<div class="uk-modal" id="ve-modal">
    <div class="uk-modal-dialog cn-ve-modal">
        <div class="uk-modal-header uk-text-center">
            <h3 class="uk-modal-title"> Confirm Changes </h3>
        </div>
        <div class="uk-modal-footer uk-text-center">
            <button type="button" id="ve_modal_no" class="md-btn md-btn-flat md-btn-danger uk-margin-medium-right uk-modal-close">No</button><button type="button" id="update-ve" data-id="<?php echo $lead_id; ?>" class="md-btn md-btn-flat md-btn-primary">Yes</button>
        </div>
    </div>
</div>
<div class="uk-modal" id="trending-modal">
    <div class="uk-modal-dialog cn-ve-modal">
        <div class="uk-modal-header uk-text-center">
            <h3 class="uk-modal-title"> Confirm Changes </h3>
        </div>
        <div class="uk-modal-footer uk-text-center">
            <button type="button" id="trending_modal_no" class="md-btn md-btn-flat md-btn-danger uk-margin-medium-right uk-modal-close">No</button><button type="button" id="update-trending" data-id="<?php echo $lead_id; ?>" class="md-btn md-btn-flat md-btn-primary">Yes</button>
        </div>
    </div>
</div>
<div class="uk-modal" id="ai-based-modal">
    <div class="uk-modal-dialog cn-ve-modal">
        <div class="uk-modal-header uk-text-center">
            <h3 class="uk-modal-title"> Confirm Changes </h3>
        </div>
        <div class="uk-modal-footer uk-text-center">
            <button type="button" id="ai-based-modal-no" class="md-btn md-btn-flat md-btn-danger uk-margin-medium-right uk-modal-close">No</button><button type="button" id="update-ai-based" data-id="<?php echo $lead_id; ?>" class="md-btn md-btn-flat md-btn-primary">Yes</button>
        </div>
    </div>
</div>

<div class="uk-modal" id="mrss-reupload-upload-modal">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header uk-text-left">
            <h3 class="uk-modal-title"> Video Story Content </h3>
        </div>
        <div class="uk-width-large-1-1" id="story-feed-div" style="display:block" ;>
            <div class="md-list-content">
                <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px;">
                    <form id="story_content_form" style="width:100%; margin:0; padding:0;" enctype="multipart/file-data">
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1" style="padding-right:35px;">
                                <div class="parsley-row">
                                    <div class="md-input-wrapper">
                                        <label for="story_title" class=" uk-form-label uk-text-primary">Story Title</label>
                                        <input type="text" id="story_title" name="story_title" class="repaint_story_title md-input" value="<?php echo $dealData->video_title; ?>" data-parsley-required-message="This field is required." required />
                                        <span class="md-input-bar "></span>
                                        <button class="btn btn-info btn-repaint" onclick="Repaint('repaint_story_title')" id="re-paint">
                                            <img src="https://freelogopng.com/images/all_img/1681039084chatgpt-icon.png" alt="chat gpt" style="width: 22px;height:22px" />
                                            <!-- <span class="material-symbols-outlined">
                                                format_paint
                                            </span> -->
                                        </button>
                                    </div>
                                    <div id="storybased_error" class="error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1" style="padding-right:35px;">
                                <div class="parsley-row">
                                    <div class="md-input-wrapper">
                                        <label for="story_description" class="uk-form-label uk-text-primary">Story Decription</label>
                                        <textarea id="story_description" name="story_description" class="repaint_story_description md-input autosized" data-parsley-required-message="This field is required." style="max-height:150px; margin-top:10px;" required><?php echo $videoData->description; ?></textarea>
                                        <span class="md-input-bar "></span>
                                        <button class="btn btn-info btn-repaint" onclick="Repaint('repaint_story_description')" id="re-paint">
                                            <img src="https://freelogopng.com/images/all_img/1681039084chatgpt-icon.png" alt="chat gpt" style="width: 22px;height:22px" />
                                            <!-- <span class="material-symbols-outlined">
                                                format_paint
                                            </span> -->
                                        </button>
                                    </div>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1" style="padding-right:35px;">
                                <div class="parsley-row">
                                    <div class="md-input-wrapper">
                                        <label for="story_tags" class="uk-form-label uk-text-primary">Story Tags</label>
                                        <textarea id="story_tags" name="story_tags" class="md-input autosized" data-parsley-required-message="This field is required." style="max-height:150px; margin-top:10px;" required><?php echo $videoData->tags; ?></textarea>
                                        <span class="md-input-bar "></span>
                                    </div>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="lead_id" name="lead_id" value="<?php echo $dealData->id; ?>">
                        <input type="hidden" id="wgid" name="wgid" value="<?php echo $dealData->unique_key; ?>">
                        <input type="hidden" id="mrss_edit" name="mrss_edit" value="0">
                        <input type="hidden" id="mrss_id" name="mrss_id" value="">
                        <!-- Brands -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1" style="padding-right:35px;">
                                <div class="parsley-row">
                                    <label for="story_brand_id" class="uk-form-label uk-text-primary">Brands</label>
                                    <select id="story_brand_id" name="story_brand_id" class="uk-width-medium-1-1 select-bs" data-parsley-required-message="This field is required." data-md-selectize required>
                                        <option value="">Select Brand</option>
                                        <?php
                                        foreach ($mrss_brands->result() as $brand) { ?>
                                            <option value="<?php echo $brand->id; ?>"><?php echo $brand->brand_name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Partners -->
                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <div class="uk-width-medium-1-1" style="padding-right:35px;">
                                <label for="exclusive-partners-list-story" class="uk-form-label uk-text-primary uk-margin-small-bottom">Partner</label>
                                <div class="parsley-row" id="partner_list_story">
                                    <select id="exclusive-partners-list-story" disabled name="story_partner_id[]" class="uk-width-medium-1-1 select-bs multi-selecter" data-parsley-required-message="This field is required." data-md-selectize required multiple>
                                        <?php
                                        foreach ($mrss_brands_partners as $brand_id => $users) {
                                            foreach ($users as $user) {
                                        ?>
                                                <option value="<?php echo $user->id; ?>"><?php echo $user->full_name; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>



                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;">
                            <?php
                            if (!empty($dealData->s3_url_story_thumb)) { ?>
                                <!-- <div class="uk-width-medium-1-1" style="padding-right:35px;">
                                <div id="general-categories-container" class="mb-10p">
                                    <p class="md-list-heading" style="width: 100%;">Thumbnail:</p>
                                    <img src="<?php //echo $dealData->s3_url_story_thumb; ?>" width="150">
                                </div>
                            </div> -->
                            <?php } ?>
                        </div>

                        <div class="uk-grid" data-uk-grid-margin style="margin-left: 0px !important;"></div>

                        <div id="portal_video_div" class="uk-width-1-1" style="padding:10px 35px;">
                            <label for="story_feed_id" class="uk-form-label uk-text-primary">Upload Video</label>
                            <div id="file_upload-drop_mrss" class="uk-file-upload">
                                <input type="file" id="story_content" name="story_content" style="display: none;" data-parsley-required-message="This field is required." required>
                                <div class="cm-mrss" style="display: none;font-weight: bolder; margin-top:-29px"><i class="material-icons">done_all</i> Complete</div>
                                <div class="error"></div>
                            </div>
                            <div>
                                <input type="hidden" name="portal_video" class="portal_video" data-parsley-required-message="This field is required." id="portal_video" value="">
                            </div>
                        </div>

                        <div id="portal_video_div_thumb" class="uk-width-1-1" style="padding:10px 35px;">
                            <label for="story_feed_id" class="uk-form-label uk-text-primary">Upload Thumbnail</label>
                            <div id="file_upload-drop_mrss_thumb" class="uk-file-upload">
                                <input type="file" id="story_content_thumb" name="story_content_thumb" style="display: none;" data-parsley-required-message="This field is required.">
                                <div class="cm-mrss" style="display: none;font-weight: bolder; margin-top:-29px"><i class="material-icons">done_all</i> Complete</div>
                                <div class="error"></div>

                            </div>
                            <div>
                                <input type="hidden" name="portal_video_thumb" class="portal_video" data-parsley-required-message="This field is required." id="portal_video_thumb" value="">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div id="mrss-progress" style="display:none";>
            <p>Saving Feed Data...</p>
            <div class="mrss_card_progress_bar" id="mrss-card-progress-bar">
                <div class="mrss_card_progress" id="mrss-card-progress"></div>
            </div>
        </div>
        <div class="uk-modal-footer uk-text-right">
            <button type="button" id="mrss-upload-cancel" class="md-btn md-btn-flat md-btn-danger uk-margin-medium-right uk-modal-close">Cancel</button><button type="button" id="mrss-upload-save" class="md-btn md-btn-flat md-btn-primary">Save</button>
        </div>
    </div>
</div>

<div class="uk-modal" id="res-att-modal">
    <div class="uk-modal-dialog comm-att-modal">
        <form id="res-att-form" class="att_upload_form" method="post" enctype="multipart/form-data">
            <input id="res-att" class="custom-file-input" style="width:90%; height:75%; margin:0 5%; margin-top:2%; border:2px dashed #fff" type="file" name="res-file[]" multiple />
            <span class="material-icons md-48 primary" style="position:absolute; top:20%; left:46%; width:8%; color:#38f;">file_upload</span>
            <button type="submit" id="upload-res" data-id="<?php echo $lead_id; ?>" data-dir="<?php echo $res_directory ?>" data-files="<?php echo $res_att; ?>" class="md-btn md-btn-flat md-btn-primary att_upload_btn">Upload</button>
        </form>
    </div>
</div>
<div class="uk-modal" id="mgr-att-modal">
    <div class="uk-modal-dialog comm-att-modal">
        <form id="mgr-att-form" class="att_upload_form" method="post" enctype="multipart/form-data">
            <input id="mgr-att" class="custom-file-input" style="width:90%; height:75%; margin:0 5%; margin-top:2%; border:2px dashed #fff" type="file" name="mgr-file[]" multiple />
            <span class="material-icons md-48 primary" style="position:absolute; top:20%; left:46%; width:8%; color:#38f;">file_upload</span>
            <button type="submit" id="upload-mgr" data-id="<?php echo $lead_id; ?>" data-dir="<?php echo $mgr_directory ?>" data-files="<?php echo $mgr_att; ?>" class="md-btn md-btn-flat md-btn-primary att_upload_btn">Upload</button>
        </form>
    </div>
</div>

<div class="uk-modal" id="email_modal">
    <div class="uk-modal-dialog rg-modal-dialog" style="border-radius: 0px !important; padding: 0px !important;">

        <div class="cntrct-pop-hd" style="margin-bottom: 0px">
            <h3>Sending Contract Email</h3>
        </div>

        <div class="email_html_data" style="float: left;width: 100%;background: #fff;margin: 0;border-bottom: 1px solid #eee;">

        </div>
    </div>
</div>
<div class="uk-modal" style="z-index: 999999;" id="munual_ar_modal">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Sign Appearance Release</h3>
        </div>
        <div class="md-card-content large-padding">
            <form id="form_validation26" class="uk-form-stacked">
                <input type="hidden" id="wga_uid_mar" name="uid" value="">
                <div class="uk-grid" data-uk-grid-margin="">
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Identify The Person</label>
                        <textarea id="appearance_detail_map" name="appearance_detail" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;"></textarea>
                        <span class="uk-text-small uk-text-muted" id="appetance"></span>
                    </div>


            </form>
        </div>
        <div class="uk-modal-footer uk-text-right" style="margin-top: 20px;">
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
            <button type="button" id="manual_appearance_submit" class="md-btn md-btn-flat md-btn-flat-primary">
                Submit
            </button>
        </div>
    </div>
</div>
</div>
<div class="uk-modal" id="watermark-video">
    <div class="uk-modal-dialog rg-modal-dialog" style="border-radius: 0px !important; padding: 0px !important;top:20%;height:400px;">

        <div class="cntrct-pop-hd" style="margin-bottom: 0px">
            <h3>Watermark Video Update</h3>
        </div>

        <div class="email_html_data" style="float: left;width: 100%;background: #fff;margin: 0;border-bottom: 1px solid #eee;">
            <div id="portal_video_div" class="uk-width-1-1 padd-0" style="">

                <div id="file_upload-drop_mrss" class="uk-file-upload">

                    <input type="file" name="file-mrss" style="display: none;">
                    <div class="cm-mrss" style="display: none;font-weight: bolder; margin-top:-29px"><i class="material-icons">done_all</i> Complete</div>
                </div>

                <div>
                    <input type="hidden" name="portal_video" class="portal_video" data-parsley-required-message="This field is required." id="portal_video" value="">
                    <!--<p style="font-weight: bolder">Watermark Video <span style="color: red">*</span></p>-->
                    <div class="error"></div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>
<?php if ($assess['can_distribute']) { ?>
    <div class="uk-modal" id="dropbox-model">
        <div class="uk-modal-dialog" style="width: 70%">
            <div class="uk-modal-header uk-width-1-1">
                <h3 class="uk-modal-title">Upload to Dropbox </h3>
                <div class="uk-text-right">
                    <label>Video Duration: <span id="sum_duration"><?php echo $sum_duration; ?></span> seconds</label>
                </div>
                <div class="error" id="dropbox-error"></div>

            </div>

            <input id="dropbox_unique_key" hidden value="<?php echo $dealData->unique_key ?>" />

            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="parsley-row">
                        <label for="dropbox_title">Input Video Title</label>
                        <input id="dropbox_title" type="text" class="md-input" value="<?php echo $dealData->video_title ?>" />
                    </div>
                </div>
            </div>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="parsley-row">
                        <input type="radio" class="check_box" id="upload_only_dropbox" name="upload_dropbox_choice" value="upload_only_dropbox" />
                        <label for="upload_only_dropbox" class="uk-form-label">Upload to Dropbox</label>
                        <div class="error"></div>
                    </div>
                </div>
            </div>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="parsley-row">
                        <input type="radio" class="check_box" id="upload_dropbox_sheet" name="upload_dropbox_choice" value="upload_dropbox_sheet" />
                        <label for="upload_dropbox_sheet" class="uk-form-label">Manual Claiming for Shorts (Less than 20 Sec)</label>
                        <div class="error"></div>
                    </div>
                </div>
            </div>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="parsley-row">
                        <input type="radio" class="check_box" id="upload_only_sheet" name="upload_dropbox_choice" value="upload_only_sheet" />
                        <label for="upload_only_sheet" class="uk-form-label">Manual Claim (Claim Tracker Entry)</label>
                        <div class="error"></div>
                    </div>
                </div>
            </div>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="parsley-row">
                        <table id="dropbox_sheet_table" style="width: 100%">
                            <!-- <tr>
                                <td>Youtube-URL</td>
                                <td>Timestamp</td>
                                <td>WGID</td>
                                <td>Predefined-Text</td>
                                <td>Dropbox-URL</td>
                                <td>Delete</td>
                                <td>Add-New</td>
                            </tr> -->
                        </table>
                        <div class="error"></div>
                    </div>
                </div>
            </div>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-1">
                    <div class="parsley-row">
                        <h5 id="dropbox-search-status"></h5>
                        <ul id="dropbox-path-list"></ul>
                    </div>
                </div>
            </div>

            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                <button type="button" id="dropbox_model_submit" class="md-btn md-btn-flat md-btn-flat-primary">Submit</button>
            </div>
        </div>
    </div>

    <?php $this->load->view('deal_detail/youtube_publish'); ?>
    
    <div class="uk-modal" id="facebook">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Publish At FaceBook </h3>
            </div>

            <div class="md-card-content large-padding">
                <form id="distribute_fb" class="uk-form-stacked">
                    <input type="hidden" id="ed_video_id_fb" name="video_id" value="">

                    <div class="publish_fb_sch">
                        <label for="publish_now_facebook" class="radio">
                            <input type="radio" checked id="publish_now_facebook" name="publish_now_facebook" value="0" />
                            <span><b>Publish Now</b></span>
                        </label>
                        <label for="facebook_publish_date_radio" class="radio" style="margin-left: 10px;">
                            <input type="radio" id="facebook_publish_date_radio" name="publish_now_facebook" value="1">
                            <span><b>Scheduling</b></span>
                        </label>

                        <div id="dvPinNo" style="display: none;"><!-- style="display: none;"-->
                            <div class="uk-grid">

                                <div class="uk-width-large-1-3 uk-width-1-1">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                        <label for="facebook_publish_date">Select date</label>
                                        <input class="md-input" type="text" name="facebook_publish_date" id="facebook_publish_date" data-uk-datepicker="{minDate:'<?= date('Y-M-d'); ?>'format:'YYYY-MM-DD'}" required>
                                    </div>


                                </div>

                                <div class="uk-width-large-1-3 uk-width-1-1">
                                    <div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                        <label for="facebook_publish_time">Select time</label>
                                        <input class="md-input" type="text" name="facebook_publish_time" id="facebook_publish_time" data-uk-timepicker data-parsley-required-message="This field is required." required>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="uk-grid" data-uk-grid-margin style="display: none" ;>
                        <div class="uk-width-medium-1-1">
                            <!--<input type="checkbox" name="publish_now_facebook" id="publish_now_facebook" value="" data-md-icheck />-->
                            <!--<label for="publish_now_facebook" class="inline-label"><b>Publish Now</b></label>-->


                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin style="display: none">
                        <div class="uk-width-medium-1-2">

                            <!--<h3 class="heading_a"><b>Scheduling</b></h3>-->
                            <div class="uk-grid">
                                <div class="uk-width-large-2-3 uk-width-1-1">
                                    <!--<div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-calendar"></i></span>
                                        <label for="facebook_publish_date">Select date</label>
                                        <input class="md-input" type="text" name="facebook_publish_date" id="facebook_publish_date" data-uk-datepicker="{format:'YYYY-MM-DD'}" data-parsley-required-message="This field is required." required>
                                    </div>-->
                                </div>

                            </div>
                        </div>
                        <div class="uk-width-medium-1-2">
                            <h3 class="heading_a">&nbsp;</h3>
                            <div class="uk-grid">
                                <div class="uk-width-large-2-3 uk-width-1-1">
                                    <!--<div class="uk-input-group">
                                        <span class="uk-input-group-addon"><i class="uk-input-group-icon uk-icon-clock-o"></i></span>
                                        <label for="facebook_publish_time">Select time</label>
                                        <input class="md-input" type="text" name="facebook_publish_time" id="facebook_publish_time" data-uk-timepicker data-parsley-required-message="This field is required." required>
                                    </div>-->
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="facebook_publish_title">Video Title<span class="req">*</span></label>
                                <input type="text" name="facebook_publish_title" value="" id="facebook_publish_title" data-parsley-required-message="This field is required." required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="facebook_publish_description" class="uk-form-label">Video Description</label>
                                <textarea id="facebook_publish_description" name="facebook_publish_description" cols="30" rows="10" class="md-input" data-parsley-required-message="This field is required." required></textarea>
                                <div id="cont-p" style="float: right;font-size: 14px;font-weight: 200;">

                                    <span class="footer-desc"><a href="javascript:void(0);" id="add_footer1" data-footer="<?php echo settings()->description_footer; ?>">Add Footer</a> </span>

                                </div>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="facebook_publish_tags" class="uk-form-label">Video Tags</label>
                                <textarea id="facebook_publish_tags" name="facebook_publish_tags" class="md-input" data-parsley-required-message="This field is required." required></textarea>

                                <div id="cont-p" style="float: right;font-size: 14px;font-weight: 200;">

                                    <span class="cont-desc">Character Limit :</span>
                                    <span class="counter1"></span>
                                </div>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="distribute_from_fb" class="md-btn md-btn-flat md-btn-flat-primary">Publish</button>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($assess['not_interested']) { ?>
    <div class="uk-modal" id="uploadform">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Admin Video upload </h3>
            </div>

            <div class="md-card-content large-padding">
                <form id="form_validation7" class="uk-form-stacked">
                    <input type="hidden" id="up_lead_id" name="lead_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <input type="hidden" value="<?php echo $dealData->id; ?>" name="lead_id" id="lead_id">


                                <?php
                                //This is a general function for generating an embed link of an FB/Vimeo/Youtube Video.
                                $finalUrl = '';
                                $iframe = '';
                                if (strpos($dealData->video_url, 'facebook.com/') !== false) {
                                    //it is FB video
                                    $finalUrl = 'https://www.facebook.com/plugins/video.php?href=' . rawurlencode($dealData->video_url) . '&show_text=1&width=200';
                                    $iframe = '<iframe  src = "' . $finalUrl . '"  frameborder="0" allowfullscreen style="height: 30vh; width:100%;"></iframe>';
                                } else if (strpos($dealData->video_url, 'vimeo.com/') !== false) {
                                    //it is Vimeo video
                                    $videoId = explode("vimeo.com/", $dealData->video_url)[1];
                                    if (strpos($videoId, '&') !== false) {
                                        $videoId = explode("&", $videoId)[0];
                                    }
                                    $finalUrl = 'https://player.vimeo.com/video/' . $videoId;
                                    $iframe = '<iframe  src = "' . $finalUrl . '"  frameborder="0" allowfullscreen style="height: 30vh;width:100%;"></iframe>';
                                } else if (strpos($dealData->video_url, 'youtube.com/') !== false) {
                                    //it is Youtube video
                                    $videoId = explode("v=", $dealData->video_url)[1];
                                    if (strpos($videoId, '&') !== false) {
                                        $videoId = explode("&", $videoId)[0];
                                    }
                                    $finalUrl = 'https://img.youtube.com/vi/' . $videoId . '/mqdefault.jpg';
                                    $iframe = $finalUrl;
                                } else if (strpos($dealData->video_url, 'youtu.be/') !== false) {
                                    //it is Youtube video
                                    $videoId = explode("youtu.be/", $dealData->video_url)[1];
                                    if (strpos($videoId, '&') !== false) {
                                        $videoId = explode("&", $videoId)[0];
                                    }
                                    $finalUrl = 'https://img.youtube.com/vi/' . $videoId . '/mqdefault.jpg';
                                    $iframe = $finalUrl;
                                } /*twitter start*/ else if (strpos($dealData->video_url, 'twitter.com') !== false) {
                                } /*tiwtter end*/ else {
                                    //echo $finalUrl;
                                }

                                ?>

                                <div id="video-div">
                                    <input type="file" name="file" style="display: none;">
                                </div>

                                <!-- <span style="font-weight: bold;color: #444;float: left;padding-bottom: 10px;display: none;text-align: left;">VIDEO TITLE  :  <p style="display: inline;font-size: large;color: #444;text-transform: capitalize;">echo $videos['video_title']</p>-->

                                </span>
                                <span style="font-weight: bold;color: #444;float: left;padding-bottom: 10px;text-align: left;">Licensed file social media link</span>
                                <div>
                                    <img src="<?php echo $iframe; ?>" style="width: 100%;padding-bottom: 10px;" alt="Video Featured Image">
                                </div>


                                <div class="error"></div>
                                <!-- -->

                                <div class="form-input" style="display: none;" id="video_urls"></div>
                                <input type="hidden" class="form-control" name="view" id="view" value="submit_video2">

                                <div class="form-input">
                                    <input type="hidden" class="form-control" name="video_title" id="video_title" value="">
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                <button type="button" id="upload_user_submit" class="md-btn md-btn-flat md-btn-flat-primary">Submit
                </button>
            </div>
        </div>
    </div>
    <div class="uk-modal" id="xyzmodal">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Video Contract Information</h3>
            </div>
            <div class="md-card-content large-padding">
                <form id="form_validation24" class="uk-form-stacked">
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Video Title:</label>
                        <input disabled id="title" name="title" value="<?php echo $dealData->video_title; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Video URL:</label>
                        <input disabled id="url" name="url" value="<?php echo $dealData->video_url; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Share:</label>
                        <input disabled id="share" name="share" value="<?php echo $dealData->revenue_share; ?>%" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Where was this video filmed?</label>
                        <input disabled id="share" name="share" value="<?php echo $question_video_taken; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">When was this video filmed?</label>
                        <input disabled id="share" name="share" value="<?php echo $question_when_video_taken; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Full Name:</label>
                        <input disabled id="full_name" name="Full_name" value="<?php echo $userData->full_name; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Email:</label>
                        <input disabled id="email" name="email" value="<?php echo $userData->email; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Phone:</label>
                        <input disabled id="phone" name="phonr" value="<?php echo $userData->mobile; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Address:</label>
                        <input disabled id="address" name="address" value="<?php echo $userData->address . ' ' . $userData->address; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">City:</label>
                        <input disabled id="full_name" name="Full_name" value="<?php echo $userData->city_id; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">State:</label>
                        <input disabled id="city" name="city" value="<?php echo $userData->state_id; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Zip Code:</label>
                        <input disabled id="zip" name="zip" value="<?php echo $userData->zip_code; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Country:</label>
                        <input disabled id="country" name="country" value="<?php echo $userData->country_code; ?>" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Signature:</label>
                        <img disabled id="sign" name="sign" src="<?php echo $signature; ?> " height="200" width="200" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;">
                    </div>
                </form>
            </div>
            <div class="uk-modal-footer uk-text-right" style="margin-top: 20px;">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                <!-- <button type="button" id="appearance_submit" class="md-btn md-btn-flat md-btn-flat-primary">
                     Submit
                 </button>-->
            </div>
        </div>
    </div>

    <div class="uk-modal" id="raw-upload-modal" style="z-index: 999999">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Add New Raw Video</h3>
            </div>
            <div class="md-card-content large-padding">
                <div class="uk-grid " data-uk-grid-margin style="margin-left: 0px !important;">
                    <div id="portal_video_div_raw" class="uk-width-1-1 padd-0">
                        <h4 class="heading_c uk-margin-small-bottom">Upload Raw Video</h4>
                        <div id="file_upload-drop_raw_new" class="uk-file-upload">

                            <input type="file" name="raw_new" style="display: none;">
                            <div class="cm-mrss" style="display: none;font-weight: bolder; margin-top:-29px"><i class="material-icons">done_all</i> Complete</div>
                        </div>

                        <div>

                        </div>

                    </div>

                </div>
            </div>
            <div class="uk-modal-footer uk-text-right" style="margin-top: 20px;">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                <!-- <button type="button" id="appearance_submit" class="md-btn md-btn-flat md-btn-flat-primary">
                     Submit
                 </button>-->
            </div>
        </div>
    </div>

<?php } ?>
<?php if (isset($userData)) { ?>
    <div class="uk-modal" id="story_information_modal">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Story Information </h3>
            </div>

            <div class="md-card-content large-padding">

                <form action="#" class="" id="story_information_form">
                    <input type="hidden" value="<?php echo $dealData->id; ?>" name="lead_id" id="lead_id">

                    <div class="form-input">

                        <label class="ele-lbl">Where was this taken(Country/City)?<span class="mnd-lbl-str">*</span></label>
                        <input type="text" class="form-control" name="question_video_taken" id="question1" placeholder="" data-parsley-required-message="This field is Mandatory." pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." required value="<?php if (isset($videoData->question_video_taken)) {
                                                                                                                                                                                                                                                                                                        echo $videoData->question_video_taken;
                                                                                                                                                                                                                                                                                                    } ?>" tabindex="1">
                        <div class="error" id="question1_err"></div>
                    </div>

                    <div class="form-input" style="display: none;">
                        <label class="ele-lbl">What was the context?<span class="mnd-lbl-str">*</span></label>

                        <div class="relative">
                            <div class="tooltip-form">?
                                <span class="tooltiptext">Tooltip text</span>
                            </div>
                            <!-- <input type="text" class="form-control" name="question2" id="question2" placeholder=""
                               data-parsley-required-message="This field is Mandatory."
                               pattern="[a-zA-Z0-9\s]+"
                               data-parsley-pattern-message="Only alphabet and number are allowed."
                               required
                               tabindex="2"> -->
                        </div>
                        <div class="error" id="question2_err"></div>
                    </div>

                    <div class="form-input">

                        <label class="ele-lbl">When was this video Taken?<span class="mnd-lbl-str">*</span></label>
                        <div class="relative">
                            <div class="pick-ico"><i class="fa fa-calendar" aria-hidden="true"></i></div>
                            <input type="date" class="form-control" name="question_when_video_taken" id="question3" placeholder="" data-parsley-required-message="This field is Mandatory." data-maxdate="today" , value="<?php if (isset($videoData->question_when_video_taken)) {
                                                                                                                                                                                                                                echo date('d/m/Y', strtotime($videoData->question_when_video_taken));
                                                                                                                                                                                                                            } ?>" required tabindex="3"><!-- question3 -->
                        </div>
                        <!-- pattern="[a-zA-Z0-9\s]+"  data-parsley-pattern-message="Only alphabet and number are allowed."-->
                        <div class="error" id="question3_err"></div>
                    </div>
                    <!-- Any information such as names, locations, or any other interesting elements are important to us so that we can pitch your clip with as much information as possible. This way our clients are able to create something that allows your video to be viewed as a story and not just a clip!  -->
                    <div class="form-input">

                        <label class="ele-lbl">What was the context
                            <span class="story-tooltipbtn" data-tooltip="Any information such as names, locations, or any other interesting elements are important to us so that we can pitch your clip with as much information as possible. This way our clients are able to create something that allows your video to be viewed as a story and not just a clip!">?</span>
                            <span class="mnd-lbl-str">*</span>
                            <!--<span class="story-tooltiptext">Any information such as names, locations, or any other interesting elements are important to us so that we can pitch your clip with as much information as possible. This way our clients are able to create something that allows your video to be viewed as a story and not just a clip!</span>-->
                        </label>
                        <textarea type="text" class="form-control vid-sub-txt" name="question_video_context" id="question4" placeholder="" data-parsley-required-message="This field is Mandatory." style="margin: 0px 0px 20px; height: 165px; width: 100%;" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." required tabindex="4"><?php if (isset($videoData->question_video_context)) {
                                                                                                                                                                                                                                                                                                                                                                                        echo $videoData->question_video_context;
                                                                                                                                                                                                                                                                                                                                                                                    } ?></textarea>

                        <div class="error" id="question4_err"></div>
                    </div>


                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                <button type="button" id="story_information_submit" class="md-btn md-btn-flat md-btn-flat-primary">
                    Submit
                </button>
            </div>
        </div>
    </div>
    <div class="uk-modal" id="personal_information_modal">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Personal Information </h3>
            </div>

            <div class="md-card-content large-padding">

                <form action="#" class="" id="personal_information_form">

                    <input type="hidden" value="<?php echo $dealData->client_id; ?>" name="id" id="id">
                    <input type="hidden" value="<?php echo $dealData->id; ?>" name="lead_id" id="lead_id">

                    <div class="form-input">
                        <label class="ele-lbl">Mobile Number</label>
                        <div class="" style="width: calc(43% - 20px);padding-left: 0px; float: left;height: 47px;">
                            <select name="country_code" class="chosen form-control-chosen new-chosen" id="country_code" data-parsley-required-message="Country Code field is required." required tabindex="1">
                                <option value="">Select Country Code</option>
                                <?php foreach ($countriesData as $country) { ?>
                                    <option <?php if ($userData->country_code == $country->phonecode) {
                                                echo 'selected';
                                            } ?> value="<?php echo $country->phonecode; ?>">
                                        +<?php echo $country->phonecode . ' ' . $country->name; ?></option>
                                <?php } ?>


                            </select>
                            <div class="error" id="country_code_err">
                                <p></p>
                            </div>
                        </div>
                        <div class="" style="width: 57%; padding-right: 0px; float: left;">

                            <input type="text" name="mobile" id="mobile" class="mobile form-control" placeholder="" style="width: 100%;" data-parsley-required-message="Mobile Nmuber field is required." required tabindex="2" data-parsley-type="integer" maxlength="11" data-parsley-type-message="Please enter the valid mobile number." value="<?php echo $userData->mobile ?>">
                            <div class="error" id="mobile_err"></div>
                        </div>
                    </div>

                    <div class="form-input">
                        <label class="ele-lbl">PayPal Email Address<span class="mnd-lbl-str">*</span></label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="" data-parsley-required-message="Email Address field is required." data-parsley-type-message="Please enter the valid email address." required tabindex="5" value="<?php echo $userData->paypal_email ?>">
                        <div class="error" id="email_err"></div>
                    </div>

                    <div class="form-input">
                        <label class="ele-lbl">Address<span class="mnd-lbl-str">*</span></label>
                        <input type="text" class="form-control" name="address" id="address" placeholder="" data-parsley-required-message="This field is Mandatory." pattern="[a-zA-Z0-9\s]+" required tabindex="6" value="<?php echo $userData->address ?>" />
                        <div class="error" id="address_err"></div>
                    </div>

                    <div class="form-input">
                        <label class="ele-lbl">Address Line 2</label>
                        <input type="text" class="form-control" name="address2" id="address2" placeholder="" data-parsley-required-message="This field is Mandatory." pattern="[a-zA-Z0-9\s]+" tabindex="7" value="<?php echo $userData->address2 ?>" />
                        <div class="error" id="address2_err"></div>
                    </div>

                    <div class="form-input">
                        <label class="ele-lbl" for="Country">Country<span class="mnd-lbl-str">*</span></label>
                        <select name="country_id" class="country_id form-control" id="country_id" data-parsley-required-message="Country field is required." data-default="United States" required tabindex="8">
                            <option value="">Select Country</option>

                            <?php foreach ($countriesData as $country) { ?>

                                <option <?php if ($userData->country_id == $country->id) {
                                            echo 'selected';
                                        } ?> value="<?php echo $country->id; ?>"><?php echo $country->name; ?></option>
                            <?php } ?>

                        </select>
                        <div class="error" id="country_id_err"></div>
                    </div>

                    <div class="form-input">
                        <label class="ele-lbl">State<span class="mnd-lbl-str">*</span></label>
                        <!--<select name="state_id" class="state_id form-control" id="state_id"
                                data-parsley-required-message="State field is required."
                                required
                                tabindex="9"
                        >
                            <option value="">Select State</option>

                        </select>-->
                        <input type="text" class="form-control" name="state_id" id="state_id" placeholder="" data-parsley-required-message="This field is Mandatory." pattern="[a-zA-Z0-9\s]+" tabindex="9" value="" />
                        <div class="error" id="state_id_err"></div>
                    </div>
                    <div class="form-input">
                        <label class="ele-lbl">City<span class="mnd-lbl-str">*</span></label>
                        <!--<select name="city_id" class="city_id form-control" id="city_id"
                                data-parsley-required-message="City field is required."
                                required
                                tabindex="10"
                        >
                            <option value="">Select City</option>



                        </select>-->
                        <input type="text" class="form-control" name="city_id" id="city_id" placeholder="" data-parsley-required-message="This field is Mandatory." pattern="[a-zA-Z0-9\s]+" tabindex="10" value="" />
                        <div class="error" id="city_id_err"></div>
                    </div>
                    <div class="form-input">
                        <label class="ele-lbl">Postal Code<span class="mnd-lbl-str">*</span></label>
                        <input type="text" class="form-control" name="zip_code" id="zip_code" placeholder="" data-parsley-required-message="This field is Mandatory." pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." required tabindex="11" value="<?php echo $userData->zip_code ?>" />
                        <div class="error" id="zip_code_err"></div>
                    </div>


                </form>

            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                <button type="button" id="personal_information_submit" class="md-btn md-btn-flat md-btn-flat-primary">
                    Submit
                </button>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($assess['can_delete_lead']) { ?>
    <div class="uk-modal" id="rejectform">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Reject Video </h3>
            </div>

            <div class="md-card-content large-padding">
                <form id="form_validation8" class="uk-form-stacked">
                    <input type="hidden" id="rj_lead_id" name="lead_id" value="">
                    <input type="hidden" id="rj_video_id" name="video_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="cancel_comments" class="uk-form-label">Reject Video Message<span class="req">*</span></label>
                                <textarea id="cancel_comments" name="reject_comments" class="md-input" data-parsley-required-message="Comments are required." value="" required> </textarea>
                                <div class="error"></div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-3-5">
                            <span class="icheck-inline">
                                <input checked type="radio" name="email_send" id="email_send" value="send" data-md-icheck />
                                <label for="radio_demo_inline_1" class="inline-label">Email Send</label>
                            </span>
                            <span class="icheck-inline">
                                <input type="radio" name="email_send" id="email_not_send" value="not send" data-md-icheck />
                                <label for="radio_demo_inline_2" class="inline-label">Email Not Send</label>
                            </span>
                        </div>
                    </div>

                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
                <button type="button" id="reject_submit" class="md-btn md-btn-flat md-btn-flat-primary">Reject Videos
                </button>
            </div>
        </div>
    </div>
<?php } ?>
<div class="uk-modal" id="appreance-release">
    <div class="uk-modal-dialog">
        <div class="uk-modal-header">
            <h3 class="uk-modal-title">Sign Appearance Release</h3>
        </div>
        <div class="md-card-content large-padding">
            <form id="form_validation21" class="uk-form-stacked">
                <input type="hidden" id="wga_uid" name="uid" value="">
                <input type="hidden" id="second_signer_id" name="Second_Signer_Id" value="">
                <div class="uk-grid" data-uk-grid-margin="">
                    <div class="uk-width-medium-1-1 uk-row-first">
                        <label for="" class="uk-form-label">Identify The Person</label>
                        <textarea id="appearance_detail" name="appearance_detail" class="md-input autosized" required="" data-parsley-required-message="This field is required." style="overflow: hidden; overflow-wrap: break-word; height: 73.1167px;"></textarea>
                        <span class="uk-text-small uk-text-muted" id="appetance"></span>
                    </div>


            </form>
        </div>
        <div class="uk-modal-footer uk-text-right" style="margin-top: 20px;">
            <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button>
            <button type="button" id="appearance_submit" class="md-btn md-btn-flat md-btn-flat-primary">
                Submit
            </button>
        </div>
    </div>
</div>

<!-- Modal -->



<script src="//cdnjs.cloudflare.com/ajax/libs/tinymce/4.5.1/tinymce.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  
  function ShowLandscapDialoge() {
        var modal = UIkit.modal("#landscapconversionDialoge");
        let s3_url = "";
        
        // Make sure PHP output is valid JavaScript
        const watermark_url = <?php echo json_encode($videoData->s3_url); ?>;
        const edited_url = <?php echo json_encode(isset($edites3[0]->portal_url) ? $edites3[0]->portal_url : ''); ?>;

        if (watermark_url) {
            s3_url = watermark_url;
        } else {
            s3_url = edited_url;
        }

        console.log(s3_url);

        // Example usage in JavaScript
        if (s3_url) { 
            document.getElementById("landscap-input").value = s3_url;
            document.getElementById("landscap-input").disabled = false;
        }

        modal.bgclose = false;
        modal.show();   
    }

    function ShowReuploadLandscape() {
        var modal = UIkit.modal("#reupload-upload-modal");
        modal.bgclose = false;
        modal.show();   
    }

    function ReuploadLandscapeVideo(event) {
        event.preventDefault();
        const progressbar = document.getElementById("progress-container");
        progressbar.style.display = "flex";

        const form = event.target;
        const fileInput = document.getElementById('landscape-file-input');
        const unique_key = '<?php echo json_encode($dealData->unique_key); ?>';
        const lead_id = '<?php echo json_encode($lead_id); ?>';
        const file = fileInput.files[0];

        if (!file) {
            alert('Please choose a file to upload.');
            return;
        }

        const formData = new FormData();
        formData.append('file_url', file);
        formData.append('unique_key', unique_key);
        formData.append('lead_id', lead_id);
        // disbale buttons 
        const cancel_btn = document.getElementById('cancel-reupload-btn');
        const upload_btn = document.getElementById('reupload-btn');
        cancel_btn.disabled= true;
        upload_btn.disabled= true;
        fileInput.disabled= true;
        const xhr = new XMLHttpRequest();
        xhr.open('POST', base_url + 'reupload-landscapvideo', true);

        // Update the progress element as the file uploads
        xhr.upload.onprogress = function(event) {
            if (event.lengthComputable) {
                const percentComplete = Math.round((event.loaded / event.total) * 100);
                document.getElementById('upload-progress').value = percentComplete;
                document.getElementById('progress-text').textContent = `${percentComplete}%`;
            }
        };

        // Handle the response from the server
        xhr.onload = function() {
            console.log('Response status:', xhr.status);
            console.log('Response text:', xhr.responseText);

            let response;
            try {
                response = JSON.parse(xhr.responseText);
            } catch (error) {
                console.error('JSON parse error:', error);
            }

            if (xhr.status >= 200 && xhr.status < 300) {
               
                    // File uploaded successfully
                    alert('File uploaded successfully!');
                    // Reload the window after the user clicks OK
                    window.location.reload();
            } else {
                cancel_btn.disabled= false;
                upload_btn.disabled= false;
                fileInput.disabled= false;
                // Handle server error
                alert('An error occurred during file upload: ' + (response.error || 'Unknown error'));
            }
        };

        xhr.onerror = function() {
            cancel_btn.disabled = false;
            upload_btn.disabled= false;
            fileInput.disabled= false;
            // Handle network errors
            alert('A network error occurred.');
        };

        xhr.send(formData);
    }



    function RequestLandScapConversion() {
        // Get form data
        const url = $('#landscap-input').val();
        const unique_key = '<?php echo json_encode($dealData->unique_key); ?>';
        const lead_id = '<?php echo json_encode($lead_id); ?>';
        // Make AJAX POST request
        $.ajax({
            url: base_url + "landscap-conversion",
            type: 'POST',
            data: {
                url: url,
                unique_key: unique_key,
                lead_id: lead_id,
            },
            success: function(response) {
                console.log(response);
                // Handle success
                alert("Converted successfuly");
                window.location.reload();
                // You can handle successful responses here (e.g., update UI, show messages)
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error('Error:', status, error);
                // Handle errors here
            }
        });
    };
    function adjustTextareaHeight() {
        var textarea = document.getElementById('repaint-text');
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }
    document.getElementById('repaint-text').addEventListener('input', adjustTextareaHeight);

    function typeTextSlowly(text, elementId, delay) {
        var index = 0;
        var element = document.getElementById(elementId);
        element.value = "";

        function typeNextCharacter() {
            if (index < text.length) {
                var character = text.charAt(index);
                element.value += character;
                index++;
                setTimeout(typeNextCharacter, delay);
            }
        }

        typeNextCharacter();
    }

    function CopyPainText() {
        var text = document.getElementById("repaint-reponse").value;
        navigator.clipboard.writeText(text)
            .then(function() {
                alert("Text copied.")
            })
            .catch(function(error) {
                console.error('Unable to copy text to clipboard:', error);
            });
    }
    // Repaint
    function Repaint(className) {
        var text = document.getElementsByClassName(className);
        try {
            text = text[0].innerHTML
        } catch {
            console.log("Error while geting text")
        }
        if (text.length < 1) {
            text = document.getElementsByClassName(className);
            text = text[0].value
        }
        var textarea = document.getElementById("repaint-text");
        textarea.value = `Rewrite the sentence (${text})`;
        var modal = UIkit.modal("#repaint_model");
        modal.bgclose = false;
        modal.show();
    };

    function RepaintWithGPT() {
        var language = document.getElementById("repaint-language").value;
        if (language.length < 3) {
            alert("Please select some language");
            return;
        }
        var field = document.getElementById("repaint-text").value;
        var send_text = field + ` and convert the sentence into  ${language}  language`;
        $.ajax({
            type: "POST",
            url: base_url + "repaint-text",
            data: {
                data: JSON.stringify(send_text)
            },
            async: true,
            success: function(data) {
                const res = JSON.parse(data);
                if (res.status === 200) {

                    typeTextSlowly(res.message, "repaint-reponse", 20)

                } else {
                    alert(res.message)
                }
            },
        });
    };
    $(document).ready(function() {
        document.getElementById('landscape-file-input').addEventListener('change', function() {
            var fileInput = document.getElementById('landscape-file-input');
            var fileInfo = document.getElementById('file-info');
            if (fileInput.files.length > 0) {
                var file = fileInput.files[0];
                var fileName = file.name;
                var fileSizeMB = (file.size / (1024 * 1024)).toFixed(2) + ' MB'; // Convert to MB and round to 2 decimal places
                var fileExtension = fileName.split('.').pop();
                fileInfo.innerHTML = 'File: ' + fileName + '<br>Extension: ' + fileExtension + '<br>Size: ' + fileSizeMB;
            } else {
                fileInfo.innerHTML = '';
            }
        });

        $('#story_brand_id').on('change', function() {
            var selectedBrandId = $(this).val();
            var brandUsers = <?php echo json_encode($mrss_brands_partners); ?>;

            var parentElement = document.getElementById('partner_list_story');

            // Clear existing options
            parentElement.innerHTML = '';

            // Create the select element
            var selectElement = document.createElement('select');
            selectElement.id = 'exclusive-partners-list-story';
            selectElement.name = 'story_partner_id[]';
            selectElement.className = 'uk-width-medium-1-1 select-bs multi-selecter';
            selectElement.setAttribute('data-parsley-required-message', 'This field is required.');
            selectElement.setAttribute('data-md-selectize', '');
            selectElement.required = true;
            selectElement.multiple = true;


            if (selectedBrandId in brandUsers) {
                var users = brandUsers[selectedBrandId];
                users.forEach(function(user) {
                    let option = document.createElement('option');
                    option.value = user.id;
                    option.text = user.full_name;
                    selectElement.appendChild(option);
                });
            }
            parentElement.appendChild(selectElement);
        });
    });
    var access = '<?php echo json_encode($assess); ?>';
    access = JSON.parse(access);
    var uid = '<?php echo $dealData->unique_key; ?>';
    var watertype = 'watermark';
    <?php if (isset($videoData->id)) { ?>
        var video_id = '<?php echo $videoData->id; ?>';
    <?php } else { ?>
        var video_id = 0;
    <?php } ?>
    var assets = '<?php echo $asset; ?>';
    var revenue_share = '<?php echo $dealData->revenue_share; ?>';
    // tinymce.init({
    //     selector: '#youtube_publish_description',
    //     height: 300,
    //     theme: 'modern',
    //     plugins: [
    //         'advlist autolink lists link image charmap print preview hr anchor pagebreak',
    //         'searchreplace wordcount visualblocks visualchars code fullscreen',
    //         'insertdatetime media nonbreaking save table contextmenu directionality',
    //         'emoticons template paste textcolor colorpicker textpattern imagetools'
    //     ],
    //     toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    //     toolbar2: 'print preview media | forecolor backcolor emoticons',
    //     image_advtab: true
    // });
</script>



</body>

</html>