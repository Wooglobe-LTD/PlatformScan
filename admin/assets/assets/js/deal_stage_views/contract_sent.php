<?php $baseurl=base_url(); ?>
<?php foreach($contract_sent->result() as $sent){
    if(isset($activityTime[$sent->id])){
        $last_activity = $activityTime[$sent->id];
    }else{
        $last_activity = '1970-01-01 00:00:00';
    }?>
    <div class="sub-scrum">
        <div class="scrum_task critical <?php if($sent->reminder_sent > 0) {echo "reminder_".$sent->reminder_sent;}?>">
            <h3 class="scrum_task_title"><a href="<?php echo $baseurl.'deal-detail/'.$sent->id ?>" class="deal_detail" data-id="<?php echo $sent->id;?>"><?php echo $sent->video_title;?></a></h3>
            <p class="scrum_task_title"><a href="<?php echo $baseurl.'deal-detail/'.$sent->id ?>" class="deal_detail" data-id="<?php echo $sent->id;?>"><?php echo $sent->first_name.' '.$sent->last_name;?></a></p>
            <div class="scrum_task_rating">
                <p class="scrum_task_description rating"><?php echo $sent->rating_point;?></p>
            </div>
            <p class="scrum_task_description">Closing Time: <?php echo date('M d, Y',strtotime($sent->closing_date));?>.</p>
            <p class="scrum_task_description">Contract Sent Time: <?php echo date('M d, Y',strtotime($sent->contract_sent_date));?>.</p>
            <p class="scrum_task_description">In current state: <?php echo getStageTime($sent->status, $sent->id);?>.</p>
            <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($last_activity);//date('M d, Y H:i A',strtotime($sent->last_activity));?>.</p>
            <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
            <div class="open-grid drop-down-menu" style="display: none">



                <?php if($assess['not_interested']){?>
                    <div class="sub-grid">
                        <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small not-interested" data-title="Contract Sent" data-id="<?php echo $sent->id;?>" title="Move To Not Interested"><i class="material-icons">not_interested</i></a></p>
                    </div>
                <?php } ?>

                <?php if($assess['can_send_email'] and $sent->reminder_sent == 1){?>
                    <div class="sub-grid">
                        <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small send_reminder_email" id="" title="Send Reminder Email" data-email="<?php echo $sent->email;?>" data-id="<?php echo $sent->id;?>"><i class="material-icons">add_alert</i></a></p>
                    </div>
                <?php } ?>
                <?php /*if($assess['can_delete_lead']){*/?>
                <!--  <div class="sub-grid">
                                            <a title="Not Interested" href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-lead" data-id="<?php /*echo $sent->id */?>"><i class="material-icons">delete</i></a>
                                            </div>-->
                <?php /*}*/ ?>
                <?php if($assess['can_revenue_update']){?>
                    <div class="sub-grid">
                        <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small revenue-update" data-revenue="<?php echo $sent->revenue_share;?>" data-title="<?php echo $sent->video_title;?>" data-id="<?php echo $sent->id;?>" data-sent="1" title="Update Revenue"><i class="material-icons">edit</i></a></p>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
<?php } ?>
