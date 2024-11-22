<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 3/29/2018
 * Time: 9:28 AM
 */
?>
<?php foreach($dealReceived->result() as $received){
	if(isset($activityTime[$received->id])){
		$last_activity = $activityTime[$received->id];
	}else{
		$last_activity = '1970-01-01 00:00:00';
	}?>
      <style type="text/css">
        div#scrum_column_account_deal_information_received .scrum_task.critical {
                background: #e4b680;
        }
    </style>
    <div class="sub-scrum">
        <div class="scrum_task <?php if($received->load_view == 5){ echo 'critical';}else{ echo 'blocker';}?>">
            <div class="uk-grid" data-uk-grid-margin>

                <div class="uk-width-medium-4-4" >
                    <h3 class="scrum_task_title">

                        <a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $received->id;?>"><?php echo $received->video_title;?></a>
                    </h3>

                    <p class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $received->id;?>"><?php echo $received->first_name.' '.$received->last_name;?></a></p>
                    <div class="scrum_task_rating">
                        <p class="scrum_task_description rating"><?php echo $received->rating_point;?></p>
                    </div>
                    <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($received->closing_date));?>.</p>
                    <p class="scrum_task_description">In current state: <?php echo getStageTime($received->status, $received->id);?>.</p>
                    <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($last_activity);?>.</p>

                    <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
                    <div class="open-grid drop-down-menu" style="display: none">

                        <?php if($assess['verify']){?>
                            <div class="sub-grid">
                                <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url?>edit_video/<?php echo $received->video_id;?>" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $received->video_id;?>" title="Verify Video"><i class="material-icons">verified_user</i></a></p>
                            </div>
                        <?php } ?>
						<?php if($assess['can_delete_lead']){?>
							<div class="sub-grid">
								<p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-videolead" data-id="<?php echo $received->id;?>" title="Cancel Contract"><i class="material-icons">delete</i></a></p>
							</div>
						<?php } ?>

                    </div>

                </div>
            </div>

        </div>
    </div>
<?php } ?>
