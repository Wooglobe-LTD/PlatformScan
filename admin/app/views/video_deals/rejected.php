<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 5/3/2018
 * Time: 12:05 PM
 */
?>
<?php foreach($dealsRejected->result() as $rejected){?>
    <div class="sub-scrum">
        <div class="scrum_task <?php if($rejected->status == 1){ echo 'critical';}else{ echo 'blocker';}?>">
            <h3 class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $rejected->id;?>"><?php echo $rejected->video_title;?></a></h3>
            <p class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $rejected->id;?>"><?php echo $rejected->first_name.' '.$rejected->last_name;?></a></p>
            <div class="scrum_task_rating">
                <p class="scrum_task_description rating"><?php echo $rejected->rating_point;?></p>
            </div>
            <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($rejected->created_at));?>.</p>
            <p class="scrum_task_description">In current state: <?php echo getStageTime($rejected->status, $rejected->id);?>.</p>
            <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($rejected->last_activity);?>.</p>
        </div>
    </div>
<?php } ?>
