<?php $baseurl=base_url(); ?>
<?php foreach($edited_videos->result() as $edited){
    if(isset($activityTime[$edited->id])){
        $last_activity = $activityTime[$edited->id];
    }else{
        $last_activity = '1970-01-01 00:00:00';
    }?>
    <div class="sub-scrum">
        <div class="scrum_task <?php if($edited->ustatus == 1){ echo 'critical';}else{ echo 'blocker';}?>" <?php if($edited->simple_video == 1) { echo 'style="background: #C8C8C8;"'; } ?>>
            <div class="uk-grid" data-uk-grid-margin>

                <div class="uk-width-medium-4-4" >
                    <h3 class="scrum_task_title">

                        <a href="<?php echo $baseurl.'deal-detail/'.$edited->id ?>" class="deal_detail" data-id="<?php echo $edited->id;?>"><?php echo $edited->video_title;?></a>
                    </h3>

                    <p class="scrum_task_title"><a href="<?php echo $baseurl.'deal-detail/'.$edited->id ?>" class="deal_detail" data-id="<?php echo $edited->id;?>"><?php echo $edited->first_name.' '.$edited->last_name;?></a></p>
                    <?php if(!empty($edited->report_issue_type)){ ?>
                        <div style="width: 100%;text-align: right"><p class="uk-button uk-button-danger" data-uk-tooltip title="<b>Types : <?php $types = explode(',',$edited->report_issue_type); foreach ($types as $type){ echo $type.'<br>';} ?></b><br><?php echo $edited->report_issue_desc;?>">Issue</p></div>
                    <?php } ?>
                    <div class="scrum_task_rating">
                        <p class="scrum_task_description rating"><?php echo $edited->rating_point;?></p>
                    </div>
                    <?php if($edited->watermark == 0){ ?>
                        <div class="scrum_task_rating_editing_water">
                            <p class="scrum_task_description editing-tags-watermark">&nbsp;</p>
                        </div>
                    <?php }else{ ?>
                        <div class="scrum_task_rating_editing">
                            <p class="scrum_task_description editing-tags">&nbsp;</p>
                        </div>
                    <?php } ?>
                    <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($edited->closing_date));?>.</p>
                    <p class="scrum_task_description">In current state: <?php echo getStageTime($edited->status, $edited->id);?>.</p>
                    <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($last_activity);?>.</p>
                    <p class="scrum_task_description">WG ID: <?php echo $edited->unique_key;?></p>
                    <p class="scrum_task_description">Assigned Staff: <?php echo (empty($edited->staff_name))?'Wooglobe':$edited->staff_name;?></p>
                    <p class="scrum_task_description">Priority: <?php echo $edited->priority?></p>
                    <?php if ($edited->is_cn_updated == 1) { ?>
                        <status-indicator class="positive"></status-indicator>
                    <?php }
                    else { ?>
                        <status-indicator class="negative"></status-indicator>
                    <?php } ?>
                    <label> C/N </label>
                    <?php if ($edited->uploaded_edited_videos == 1) { ?>
                        <status-indicator class="positive"></status-indicator>
                    <?php }
                    else { ?>
                        <status-indicator class="negative"></status-indicator>
                    <?php } ?>
                    <label> V.E </label>

                    <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
                    <div class="open-grid drop-down-menu" style="display: none">
                        <?php if($assess['can_delete_lead']){?>
                            <div class="sub-grid">
                                <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-videolead" data-id="<?php echo $edited->id;?>" title="Cancel Contract"><i class="material-icons">delete</i></a></p>
                            </div>
                        <?php } ?>
                        <?php if($assess['can_upload_edited_videos']){?>
                            <div class="sub-grid">
                                <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url?>upload_edited_video/<?php echo $edited->video_id;?>" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $edited->video_id;?>" title="Upload Video"><i class="material-icons">cloud_upload</i></a></p>
                            </div>
                        <?php } ?>


                        <?php if($assess['can_upload_edited_videos']){?>
                            <div class="sub-grid">
                                <!--                                                            <p class="scrum_task_info" style="text-align: right;"><a href="--><?php //echo $url.'download-raw-files/'.$upload->video_id;?><!--" class="md-btn md-btn-primary md-btn-small" title="Download Raw Files"><i class="material-icons">cloud_download</i></a></p>-->
                                <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url.'download-raw-files/'.$edited->video_id;?>" vid="<?php echo $edited->video_id; ?>" class="md-btn md-btn-primary md-btn-small download-raw-files" title="Download Raw Files"><i class="material-icons">cloud_download</i></a></p>
                            </div>
                        <?php } ?>

                        <?php if($assess['can_view_contract']){?>
                            <div class="sub-grid">
                                <p class="" style="text-align: right; margin-bottom: 0px;"><a target="_blank" href="<?php echo $url.'view_contract/'.$edited->id;?>" class="md-btn md-btn-primary md-btn-small" data-id="" data-name="" data-email="" title="View Contract"><i class="material-icons">pageview</i></a></p>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
<?php } ?>
