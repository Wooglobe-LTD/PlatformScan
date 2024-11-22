<?php $baseurl=base_url(); ?>
<?php foreach($distribute->result() as $dis){
    if(isset($activityTime[$dis->id])){
        $last_activity = $activityTime[$dis->id];
    }else{
        $last_activity = '1970-01-01 00:00:00';
    }?>

    <div class="sub-scrum">
        <div class="scrum_task <?php if($dis->ustatus == 1){ echo 'critical';}else{ echo 'blocker';}?>" <?php if($dis->simple_video == 1) { echo 'style="background: #C8C8C8;"'; } ?>>
            <div class="uk-grid" data-uk-grid-margin>

                <div class="uk-width-medium-4-4" >
                    <h3 class="scrum_task_title">

                        <a href="<?php echo $baseurl.'deal-detail/'.$dis->id ?>" class="deal_detail" data-id="<?php echo $dis->id;?>"><?php echo $dis->video_title;?></a>
                    </h3>

                    <p class="scrum_task_title"><a href="<?php echo $baseurl.'deal-detail/'.$dis->id ?>" class="deal_detail" data-id="<?php echo $dis->id;?>"><?php echo $dis->first_name.' '.$dis->last_name;?></a></p>
                    <?php if(!empty($dis->report_issue_type)){ ?>
                        <div style="width: 100%;text-align: right"><p class="uk-button uk-button-danger" data-uk-tooltip title="<b>Types : <?php $types = explode(',',$dis->report_issue_type); foreach ($types as $type){ echo $type.'<br>';} ?></b><br><?php echo $dis->report_issue_desc;?>">Issue</p></div>
                    <?php } ?>
                    <div class="scrum_task_rating">
                        <p class="scrum_task_description rating"><?php echo $dis->rating_point;?></p>
                    </div>
                    <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($dis->closing_date));?>.</p>
                    <p class="scrum_task_description">In current state: <?php echo getStageTime($dis->status, $dis->id);?>.</p>
                    <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($last_activity);?></p>
                    <p class="scrum_task_description">WG ID: <?php echo $dis->unique_key;?>.</p>
                    <p class="scrum_task_description">Assigned Staff: <?php echo (empty($dis->staff_name))?'Wooglobe':$dis->staff_name;?></p>
                    <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
                    <div class="open-grid drop-down-menu" style="display: none">
                        <?php if($assess['can_delete_lead']){?>-
                            <div class="sub-grid">
                                <a title="Delete Deal Permantly" href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-videolead-per" data-id="<?php echo $dis->id ?>"><i class="material-icons">delete_forever</i></a>
                            </div>
                        <?php } ?>
                        <?php if($assess['can_delete_lead']){?>
                            <div class="sub-grid">

                                <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-videolead" data-id="<?php echo $dis->id;?>" data-videoid="<?php echo $dis->video_id;?>" title="Cancel Contract"><i class="material-icons">delete</i></a></p>
                            </div>
                        <?php } ?>

                        <?php if($assess['can_view_contract']){?>
                            <div class="sub-grid">
                                <p class="" style="text-align: right; margin-bottom: 0px;"><a target="_blank" href="<?php echo $url.'view_contract/'.$dis->id;?>" class="md-btn md-btn-primary md-btn-small" data-id="" data-name="" data-email="" title="View Contract"><i class="material-icons">pageview</i></a></p>
                            </div>
                        <?php } ?>
						
						<div class="sub-grid">
                                <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url?>upload_edited_video/<?php echo $dis->video_id;?>" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $dis->video_id;?>" title="Re-Upload Video"><i class="material-icons">cloud_upload</i></a></p>
                            </div>
                    </div>
					
					
<div class="btm-social-icons">
                        <?php if($assess['verify'] ){ ?>

                            
                            <?php
                            // $data_url_yt = '';
                            // $color_code_yt = '';
                            // if(isset($editedVideos[$dis->video_id]) and $editedVideos[$dis->video_id]['yt_url'] == ''){
                            //     //echo "video not uploaded and show grey icon with link to upload";
                            //     $color_code_yt = 'edited_file_missing';
                            //     $yt_anchor_link = $url.'upload_edited_video/'.$dis->video_id;
                            //     $data_url_yt = '';
                            // }else{
                            //     if($dis->published_yt == 1 and $dis->youtube_repub == 0){
                            //         //echo "video published and not to republish. Show gren icon without link";
                            //         $color_code_yt = 'edited_video_published';
                            //         $data_url_yt = '';
                            //         $yt_anchor_link = '#';
                            //     }
                            //     else if($dis->published_yt == 0 || $dis->youtube_repub == 1){
                            //         //echo "video is either not published or needs to republish. Show blue icon with link to publish";
                            //         $color_code_yt = 'distribute';
                            //         $yt_anchor_link = '#';
                            //         $data_url_yt = 'publish-youtube';
                            //     }
                            // }
                            // $data_url_fb = 'publish-facebook';

                            // if(isset($editedVideos[$dis->video_id]) and $editedVideos[$dis->video_id]['fb_url'] == ''){
                            //     //echo "video not uploaded and show grey icon with link to upload";
                            //     $color_code_class = 'edited_file_missing';
                            //     $fb_anchor_link = $url.'upload_edited_video/'.$dis->video_id;
                            //     $data_url_fb = '';
                            // }else{
                            //     if($dis->published_fb == 1 and $dis->facebook_repub == 0){
                            //         //echo "video published and not to republish. Show gren icon without link";
                            //         $color_code_class = 'edited_video_published';
                            //         $data_url_fb = '';
                            //         $fb_anchor_link = '#';
                            //     }else if($dis->published_fb == 0 and $dis->facebook_repub == 0 and empty($dis->fb_url)){
                            //         //echo "video is either not published or needs to republish. Show blue icon with link to publish";
                            //         $color_code_class = 'edited_file_missing';
                            //         $fb_anchor_link = $url.'upload_edited_video/'.$dis->video_id;
                            //         $data_url_fb = '';
                            //     }
                            //     else if($dis->published_fb == 0 || $dis->facebook_repub == 1){
                            //         //echo "video is either not published or needs to republish. Show blue icon with link to publish";
                            //         $color_code_class = 'distribute';
                            //         $fb_anchor_link = '#';
                            //     }
                                
                            // }
                            ?>
                            <?php if($dis->published_yt != 1) { ?>
                                <div class="sub-grid btm-fx">
                                    <p class="scrum_task_info"><a style="width:100%; " class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light"><i class="uk-icon-youtube-play uk-icon-medium"><!--<img src="https://uat.technoventive.com/admin/assets/assets/icons/youtube_social_circle_red.png" />--></i></a></p>
                                </div>
                            <?php } ?>
                            <?php if($dis->mrss != 1 && $dis->mrss_date != NULL) { ?>
                                <div class="sub-grid btm-fx">
                                    <p class="scrum_task_info"><a style="width:100%; " class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light"><i class="uk-icon-rss uk-icon-medium"></i></a></p>
                                </div>
                            <?php } ?>
                            <?php if($dis->dropbox_status != 'success') { ?>
                                <div class="sub-grid btm-fx">
                                    <p class="scrum_task_info"><a style="width:100%; " class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light"><i class="uk-icon-dropbox uk-icon-medium"></i></a></p>
                                </div>
                            <?php } ?>
                            <!-- <div class="sub-grid btm-fx">
                                <p class="scrum_task_info"><a href="<?php //echo $fb_anchor_link;?>" style="width:100%; " class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light <?php //echo $color_code_class?>" data-id="<?php //echo $dis->video_id;?>" data-url="<?php //echo $data_url_fb;?>"><i class="uk-icon-facebook uk-icon-medium"></i></a></p>
                            </div> -->

                            <?php if($dis->published_portal == 0){?>
                                <!--<div class="sub-grid">
                                                                <p class="scrum_task_info" ><a href="" style="width:100%; " class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light distribute" data-id="<?php /*echo $dis->video_id;*/?>" data-url="publish-portal" title="Portal"><img src="<?php /*echo $asset;*/?>assets/img/portal.png" alt="Upload on portal"></a></p>
                                                            </div>-->

                            <?php }?>
                        <?php }?>



</div>					
					
					
                </div>
            </div>

        </div>
    </div>
<?php } ?>
