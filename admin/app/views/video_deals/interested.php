<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 5/29/2018
 * Time: 2:46 PM
 */
?>
<?php foreach($notInterested->result() as $interested){?>
    <div class="sub-scrum">
        <div class="scrum_task <?php if($interested->ustatus == 1){ echo 'critical';}else{ echo 'blocker';}?>">
            <h3 class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $interested->id;?>"><?php echo $interested->video_title;?></a></h3>
            <p class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $interested->id;?>"><?php echo $interested->first_name.' '.$interested->last_name;?></a></p>
            <div class="scrum_task_rating">
                <p class="scrum_task_description rating"><?php echo $interested->rating_point;?></p>
            </div>
            <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($interested->closing_date));?>.</p>
            <p class="scrum_task_description">In current state: <?php echo getStageTime($interested->status, $interested->id);?>.</p>
            <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($interested->last_activity);?>.</p>
        </div>
    </div>
<?php } ?>
