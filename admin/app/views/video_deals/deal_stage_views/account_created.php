<?php $baseurl=base_url(); ?>
<?php foreach($account_created->result() as $created){
    if(isset($activityTime[$created->id])){
        $last_activity = $activityTime[$created->id];
    }else{
        $last_activity = '1970-01-01 00:00:00';

    }?>
    <div class="sub-scrum" >
        <div class="scrum_task <?php if($created->ustatus == 1){ echo 'critical';}else{ echo 'blocker';}?> <?php if($created->reminder_sent > 0) {echo "reminder_".$created->reminder_sent;}?>" <?php if($created->simple_video == 1) { echo 'style="background: #C8C8C8;"'; } ?>>
            <div class="uk-grid" data-uk-grid-margin>
                <?php if($assess['reminder']){?>
                    <div class="uk-width-medium-1-4">
                        <!--<input type="checkbox" name="created_reminder[]" class="created-reminder" value="<?php //echo $created->client_id;?>" data-md-icheck />-->
                    </div>
                <?php }?>
                <div class="<?php if($assess['reminder']){?>uk-width-medium-3-4<?php }else{?>uk-width-medium-4-4 <?php } ?>">
                    <h3 class="scrum_task_title">

                        <a href="<?php echo $baseurl.'deal-detail/'.$created->id ?>" class="deal_detail" data-id="<?php echo $created->id;?>"><?php echo $created->video_title;?></a>
                    </h3>

                    <p class="scrum_task_title"><a href="<?php echo $baseurl.'deal-detail/'.$created->id ?>" class="deal_detail" data-id="<?php echo $created->id;?>"><?php echo $created->first_name.' '.$created->last_name;?></a></p>
                    <div class="scrum_task_rating">
                        <p class="scrum_task_description rating"><?php echo $created->rating_point;?></p>
                    </div>
                    <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($created->closing_date));?>.</p>
                    <p class="scrum_task_description">In current state: <?php echo getStageTime($created->status, $created->id);?>.</p>
                    <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($last_activity);?>.</p>

                    <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
                    <div class="open-grid drop-down-menu" style="display: none">

                        <?php if($assess['reminder']){?>
                            <div class="sub-grid">
                                <?php if($created->reminder_sent == 1) {?>
                                    <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light send"   data-id="<?php echo $created->client_id;?>" data-lead="<?php echo $created->id;  ?>" title="Send Reminder"><i class="material-icons">add_alert</i></a></p>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if($assess['can_delete_lead']){?>
                            <div class="sub-grid">
                                <a title="Cancel Contract" href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-videolead" data-id="<?php echo $created->id ?>"><i class="material-icons">delete</i></a>
                            </div>
                        <?php } ?>
                        <?php if($assess['can_view_contract']){?>
                            <div class="sub-grid">
                                <p class="" style="text-align: right; margin-bottom: 0px;"><a target="_blank" href="<?php echo $url.'view_contract/'.$created->id;?>" class="md-btn md-btn-primary md-btn-small" data-id="" data-name="" data-email="" title="View Contract"><i class="material-icons">pageview</i></a></p>
                            </div>
                        <?php } ?>
                    </div>

                </div>
            </div>

        </div>
    </div>
<?php } ?>
