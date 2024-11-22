<?php
/**
 * Created by PhpStorm.
 * User: HP8300
 * Date: 3/26/2018
 * Time: 11:27 AM
 */
?>
<?php foreach($dealWon->result() as $won){?>
    <div class="sub-scrum">
        <div class="scrum_task <?php if($won->ustatus == 1){ echo 'critical';}else{ echo 'blocker';}?>">
            <h3 class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $won->id;?>"><?php echo $won->video_title;?></a></h3>
            <p class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $won->id;?>"><?php echo $won->first_name.' '.$won->last_name;?></a></p>
            <div class="scrum_task_rating">
                <p class="scrum_task_description rating"><?php echo $won->rating_point;?></p>
            </div>
            <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($won->closing_date));?>.</p>
            <p class="scrum_task_description">In current state: <?php echo getStageTime($won->status, $won->id);?>.</p>
            <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($won->last_activity);?>.</p>
            <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
            <div class="open-grid drop-down-menu" style="display: none">
                <div class="sub-grid">
                    <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url?>upload_edited_video/<?php echo $won->video_id;?>" class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $won->video_id;?>" title="Upload Video"><i class="material-icons">cloud_upload</i></a></p>
                </div>
                <?php if($won->youtube_repub == 1){?>
                    <div class="sub-grid" >
                        <p class="scrum_task_info"><a href="" style="width:100%; " class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light distribute" data-id="<?php echo $won->video_id;?>" data-url="publish-youtube"><i class="uk-icon-youtube-play uk-icon-medium"></i></a></p>
                    </div>
                <?php }?>
                <?php if($won->facebook_repub == 1){?>
                    <div class="sub-grid">
                        <p class="scrum_task_info"><a href="" style="width:100%; " class="md-btn md-btn-success md-btn-small md-btn-wave-light waves-effect waves-button waves-light distribute" data-id="<?php echo $won->video_id;?>" data-url="publish-facebook"><i class="uk-icon-facebook uk-icon-medium"></i></a></p>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
<?php } ?>
