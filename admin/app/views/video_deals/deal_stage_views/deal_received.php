<?php $baseurl=base_url(); ?>
<?php foreach($deal_received->result() as $received){
    if(isset($activityTime[$received->id])){
        $last_activity = $activityTime[$received->id];
    }else{
        $last_activity = '1970-01-01 00:00:00';
    }
    foreach($rawVideos->result() as $raw) {
        if ($raw->lead_id == $received->id) {
            $rawurl = $raw->url;
        }
    }

	?>
    <style type="text/css">
        div#scrum_column_account_deal_information_received .scrum_task.critical {
                background: #e4b680;
        }
    </style>
    <div class="sub-scrum">
        <div class="scrum_task <?php if($received->load_view == 5){ echo 'critical';}else{ echo 'blocker';}?>" <?php if($received->simple_video == 1) { echo 'style="background: #C8C8C8;"'; } ?>>
            <div class="uk-grid" data-uk-grid-margin>

                <div class="uk-width-medium-4-4" >
                    <h3 class="scrum_task_title">

                        <a href="<?php echo $baseurl.'deal-detail/'.$received->id ?>" class="deal_detail" data-id="<?php echo $received->id;?>"><?php echo $received->video_title;?></a>
                    </h3>

                    <p class="scrum_task_title"><a href="<?php echo $baseurl.'deal-detail/'.$received->id ?>" class="deal_detail" data-id="<?php echo $received->id;?>"><?php echo $received->first_name.' '.$received->last_name;?></a></p>
                    <?php if(!empty($received->report_issue_type)){ 
                        $btn_class = 'uk-button uk-button-danger';
                        if($received->scout_resolved) { 
                            $btn_class = 'uk-button uk-button-success';
                        } ?>
                        <div style="width:100%; text-align:right"><p class="<?php echo $btn_class ?>" data-uk-tooltip title="<b>Types : <?php $types = explode(',',$received->report_issue_type); foreach ($types as $type){ echo $type.'<br>';} ?></b><br><?php echo $received->report_issue_desc;?>">Issue</p></div>
                    <?php } ?>
                    <div class="scrum_task_rating">
                        <p class="scrum_task_description rating"><?php echo $received->rating_point;?></p>
                    </div>
                    <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($received->closing_date));?>.</p>
                    <p class="scrum_task_description">In current state: <?php echo getStageTime($received->status, $received->id);?>.</p>
                    <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($last_activity);?>.</p>
                    <p class="scrum_task_description">WG ID: <?php echo $received->unique_key;?></p>
                    <p class="scrum_task_description">Assigned Staff: <?php echo (empty($received->staff_name))?'Wooglobe':$received->staff_name;?></p>

                    <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
                    <div class="open-grid drop-down-menu" style="display: none">
                        <?php if($assess['can_client_add']){
                            if($received->client_id == 0){
                                ?>

                                <div class="sub-grid">
                                    <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light add" data-id="<?php echo $received->id;?>" data-name="<?php echo $received->first_name.' '.$received->last_name;?>" data-email="<?php echo $received->email;?>" title="Account Create"><i class="material-icons">person_add</i></a></p>
                                </div>

                            <?php }
                        } ?>
                        <?php if($assess['verify']){    
								if ($received->client_id > 0 && $received->load_view == 4 && !empty($rawurl)) { ?>
                            <div class="sub-grid">
                                <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url?>edit_video/<?php echo $received->video_id;?>" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $received->video_id;?>" title="Verify Video"><i class="material-icons">verified_user</i></a></p>
                            </div>
                        <?php } 
						} ?>
                        <?php if($assess['can_delete_lead']){?>-
                            <div class="sub-grid">
                                <a title="Delete Deal Permantly" href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-videolead-per" data-id="<?php echo $received->id ?>"><i class="material-icons">delete_forever</i></a>
                            </div>
                        <?php } ?>
                        <?php if($assess['can_delete_lead']){?>
                            <div class="sub-grid">
                                <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-videolead" data-id="<?php echo $received->id;?>" title="Cancel Contract"><i class="material-icons">delete</i></a></p>
                            </div>
                        <?php } ?>

                        <?php /*if($assess['can_view_contract']){*/?><!--
                            <div class="sub-grid">
                                <p class="" style="text-align: right; margin-bottom: 0px;"><a target="_blank" href="<?php /*echo $url.'view_contract/'.$received->id;*/?>" class="md-btn md-btn-primary md-btn-small" data-id="" data-name="" data-email="" title="View Contract"><i class="material-icons">pageview</i></a></p>
                            </div>
                        --><?php /*} */?>

                    </div>

                </div>
            </div>

        </div>
    </div>
<?php } ?>
