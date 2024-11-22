<?php
/**
 * Created by PhpStorm.
 * User: HP8300
 * Date: 3/26/2018
 * Time: 11:26 AM
 */
?>
<?php foreach($dealLost->result() as $lost){?>
    <div class="sub-scrum">
        <div class="scrum_task <?php if($lost->ustatus == 1){ echo 'critical';}else{ echo 'blocker';}?>">
            <h3 class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $lost->id;?>"><?php echo $lost->video_title;?></a></h3>
            <p class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $lost->id;?>"><?php echo $lost->first_name.' '.$lost->last_name;?></a></p>
            <div class="scrum_task_rating">
                <p class="scrum_task_description rating"><?php echo $lost->rating_point;?></p>
            </div>
            <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($lost->closing_date));?>.</p>

        </div>
    </div>
<?php } ?>
