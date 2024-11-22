<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 5/10/2018
 * Time: 5:01 PM
 */
?>
<?php foreach($uploadVideo->result() as $upload){
	if(isset($activityTime[$upload->id])){
		$last_activity = $activityTime[$upload->id];
	}else{
		$last_activity = '1970-01-01 00:00:00';
	}?>
    <div class="sub-scrum">
        <div class="scrum_task <?php if($upload->ustatus == 1){ echo 'critical';}else{ echo 'blocker';}?>">
            <div class="uk-grid" data-uk-grid-margin>

                <div class="uk-width-medium-4-4" >
                    <h3 class="scrum_task_title">

                        <a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $upload->id;?>"><?php echo $upload->video_title;?></a>
                    </h3>

                    <p class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $upload->id;?>"><?php echo $upload->first_name.' '.$upload->last_name;?></a></p>
                    <div class="scrum_task_rating">
                        <p class="scrum_task_description rating"><?php echo $upload->rating_point;?></p>
                    </div>
                    <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($upload->closing_date));?>.</p>
                    <p class="scrum_task_description">In current state: <?php echo getStageTime($upload->status, $upload->id);?>.</p>
                    <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($last_activity);?>.</p>

                    <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
                    <div class="open-grid drop-down-menu" style="display: none">
						<?php if($assess['can_delete_lead']){?>
							<div class="sub-grid">
								<p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small delete-videolead" data-id="<?php echo $upload->id;?>" title="Cancel Contract"><i class="material-icons">delete</i></a></p>
							</div>
						<?php } ?>
						<?php if($assess['can_upload_edited_videos']){?>
							<div class="sub-grid">
								<p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url?>upload_edited_video/<?php echo $upload->video_id;?>" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $upload->video_id;?>" title="Upload Video"><i class="material-icons">cloud_upload</i></a></p>
							</div>
						<?php } ?>


						<?php if($assess['can_upload_edited_videos']){?>
							<div class="sub-grid">
								<p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url.'download-raw-files/'.$upload->video_id;?>" class="md-btn md-btn-primary md-btn-small" title="Download Raw Files"><i class="material-icons">cloud_download</i></a></p>
							</div>
						<?php } ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php } ?>
