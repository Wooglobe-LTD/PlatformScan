<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 3/19/2018
 * Time: 1:02 PM
 */
?>
<?php foreach($signed->result() as $sign){
	if(isset($activityTime[$sign->id])){
		$last_activity = $activityTime[$sign->id];
	}else{
		$last_activity = '1970-01-01 00:00:00';
	}?>
    <div class="sub-scrum">
        <div class="scrum_task critical">
            <h3 class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $sign->id;?>"><?php echo $sign->video_title;?></a></h3>
            <p class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $sign->id;?>"><?php echo $sign->first_name.' '.$sign->last_name;?></a></p>
            <div class="scrum_task_rating">
                <p class="scrum_task_description rating"><?php echo $sign->rating_point;?></p>
            </div>
            <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($sign->closing_date));?>.</p>
            <p class="scrum_task_description">In current state: <?php echo getStageTime($sign->status, $sign->id);?>.</p>
            <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($last_activity);?>.</p>
            <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
            <div class="open-grid drop-down-menu" style="display: none">
                <?php if($assess['can_view_contract']){?>
                    <div class="sub-grid">
                        <p class="" style="text-align: right; margin-bottom: 0px;"><a target="_blank" href="<?php echo $url.'view_contract/'.$sign->id;?>" class="md-btn md-btn-primary md-btn-small" data-id="" data-name="" data-email="" title="View Contract"><i class="material-icons">pageview</i></a></p>
                    </div>
                <?php } ?>
				<?php if($assess['can_delete_lead']){?>
					<div class="sub-grid">
						<a title="Cancel Contract" href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-videolead" data-id="<?php echo $sign->id ?>"><i class="material-icons">delete</i></a>
					</div>
				<?php } ?>
                <?php if($assess['can_client_add']){?>

                    <div class="sub-grid">
                        <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light add" data-id="<?php echo $sign->id;?>" data-name="<?php echo $sign->first_name.' '.$sign->last_name;?>" data-email="<?php echo $sign->email;?>" title="Account Create"><i class="material-icons">person_add</i></a></p>
                    </div>

                <?php } ?>

            </div>
        </div>
    </div>
<?php } ?>
