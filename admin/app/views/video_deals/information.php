<?php
/**
 * Created by PhpStorm.
 * User: HP8300
 * Date: 3/20/2018
 * Time: 4:39 PM
 */
?>
<?php foreach($dealInformation->result() as $dealInformation){
	if(isset($activityTime[$dealInformation->id])){
		$last_activity = $activityTime[$dealInformation->id];
	}else{
		$last_activity = '1970-01-01 00:00:00';
	}?>
    <div class="sub-scrum">
        <div class="scrum_task <?php if($dealInformation->ustatus == 1){ echo 'critical';}else{ echo 'blocker';}?> <?php if($dealInformation->reminder_sent > 0) {echo "reminder_".$sent->reminder_sent;}?>">
            <div class="uk-grid" data-uk-grid-margin>
               <!-- <?php /*if($assess['reminder'] ){*/?>
                    <div class="uk-width-medium-1-4">
                        <input type="checkbox" name="information_reminder[]" class="information-reminder" value="<?php /*echo $dealInformation->id;*/?>" data-md-icheck />
                    </div>
                --><?php /*}*/?>
                <div class="<?php if($assess['information_reminder'] ){?>uk-width-medium-3-4<?php }else{?>uk-width-medium-4-4 <?php } ?>" >
                    <h3 class="scrum_task_title">

                        <a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $dealInformation->id;?>"><?php echo $dealInformation->video_title;?></a>
                    </h3>

                    <p class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $dealInformation->id;?>"><?php echo $dealInformation->first_name.' '.$dealInformation->last_name;?></a></p>
                    <div class="scrum_task_rating">
                        <p class="scrum_task_description rating"><?php echo $dealInformation->rating_point;?></p>
                    </div>
                    <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($dealInformation->closing_date));?>.</p>
                    <p class="scrum_task_description">In current state: <?php echo getStageTime($dealInformation->status, $dealInformation->id);?>.</p>
                    <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($last_activity);//date('M d, Y H:i A',strtotime($dealInformation->last_activity));?>.</p>

                    <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
                    <div class="open-grid drop-down-menu" style="display: none">

                        <?php if($assess['information_reminder']){?>
                            <div class="sub-grid">
                                <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="delete_deal md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $dealInformation->id;?>" title="Deal Remove"><i class="material-icons">delete</i></a></p>
                            </div>
                        <?php } ?>
						<?php if($assess['can_delete_lead']){?>
							<div class="sub-grid">
								<?php if($dealInformation->reminder_sent == 1){?>
									<p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light send-notification" data-id="<?php echo $dealInformation->id;?>" title="Send Notification"><i class="material-icons">add_alert</i></a></p>
								<?php }?>
								<p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-videolead" data-id="<?php echo $dealInformation->id;?>" title="Cancel Contract"><i class="material-icons">delete</i></a></p>
							</div>
						<?php } ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
<?php } ?>
<script>
    $(document).on('ready', function() {
        $( ".material-icons.drop-down" ).click(function() {
            console.log('start')
            $(this).toggleClass('noborder');
            $(this).parent().siblings(".drop-down-menu").toggle();
            event.stopPropagation();
        });

        $(document).click(function(){
            if($('.material-icons.drop-down').hasClass('noborder')) {
                $('.material-icons.drop-down').removeClass('noborder');
                $(".drop-down-menu").css("display","none");
            }
        });

    });
</script>
