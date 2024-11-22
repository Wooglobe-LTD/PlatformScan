<?php $baseurl=base_url(); ?>
<?php foreach($deals_rated->result() as $rated){
    if(isset($activityTime[$rated->id])){
        $last_activity = $activityTime[$rated->id];
    }else{
        $last_activity = '1970-01-01 00:00:00';
    }?>
    <div class="sub-scrum">
        <div class="scrum_task critical <?php if($rated->reminder_sent > 0) {echo "reminder_".$rated->reminder_sent;}?>">
            <h3 class="scrum_task_title"><a href="<?php echo $baseurl.'deal-detail/'.$rated->id ?>" class="deal_detail" data-id="<?php echo $rated->id;?>"><?php echo $rated->video_title;?></a></h3>
            <p class="scrum_task_title"><a href="<?php echo $baseurl.'deal-detail/'.$rated->id ?>" class="deal_detail" data-id="<?php echo $rated->id;?>"><?php echo $rated->first_name.' '.$rated->last_name;?></a></p>
            <div class="scrum_task_rating">
                <p class="scrum_task_description rating"><?php echo $rated->rating_point;?></p>
            </div>
            <p class="scrum_task_description closing">Closing Time: <?php echo date('M d, Y',strtotime($rated->closing_date));?>.</p>
            <p class="scrum_task_description rated-time">Rated Time: <?php echo date('M d, Y',strtotime($rated->lead_rated_date));?>.</p>
            <p class="scrum_task_description state">In current state: <?php echo getStageTime($rated->status, $rated->id);?>.</p>
            <p class="scrum_task_description last-activity">Last Activity: <?php echo getTimeInterval($last_activity);// date('M d, Y H:i A',strtotime($rated->last_activity));?>.</p>
            <p class="scrum_task_description unique_key">WG ID: <?php echo $rated->unique_key;?></p>
            <p class="scrum_task_description staff_name">Assigned Staff: <?php echo (empty($rated->staff_name))?'Wooglobe':$rated->staff_name;?></p>

            <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
            <div class="open-grid drop-down-menu" style="display: none">
                <?php if($assess['can_send_email'] and $rated->reminder_sent == 1){?>
                    <div class="sub-grid">
                        <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small send_reminder_email" id="" title="Send Reminder Email" data-email="<?php echo $rated->email;?>" data-id="<?php echo $rated->id;?>"><i class="material-icons">add_alert</i></a></p>
                    </div>
                <?php } ?>
                <?php if($assess['not_interested']){?>
                    <div class="sub-grid">
                        <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small not-interested" data-title="Deals" data-id="<?php echo $rated->id;?>" title="Move To Not Interested"><i class="material-icons">not_interested</i></a></p>
                    </div>
                <?php } ?>
                <?php if($assess['can_delete_lead']){?>-
                    <div class="sub-grid">
                        <a title="Delete Deal Permantly" href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-videolead-per" data-id="<?php echo $rated->id ?>"><i class="material-icons">delete_forever</i></a>
                    </div>
                <?php } ?>
                <?php /*if($assess['can_delete_lead']){*/?>
                <!-- <div class="sub-grid">
                                            <a title="Not Interested" href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-lead" data-id="<?php /*echo $rated->id */?>"><i class="material-icons">delete</i></a>
                                        </div>-->
                <?php /*}*/ ?>
                <?php if($assess['can_revenue_update']){?>
                    <div class="sub-grid">
                        <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small deal_detail" data-id="<?php echo $rated->id;?>" title="Update Revenue"><i class="material-icons">edit</i></a></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>