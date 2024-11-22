<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 3/19/2018
 * Time: 2:16 PM
 */
?>
<?php foreach($accountCreated->result() as $created){?>
    <div class="sub-scrum" >
        <div class="scrum_task <?php if($created->ustatus == 1){ echo 'critical';}else{ echo 'blocker';}?> <?php if($created->reminder_sent > 0) {echo 'reminder_'.$created->reminder_sent;}?>">
            <div class="uk-grid" data-uk-grid-margin>
                <?php if($assess['reminder']){?>
                    <div class="uk-width-medium-1-4">
                        <input type="checkbox" name="created_reminder[]" class="created-reminder" value="<?php echo $created->client_id;?>" data-md-icheck />
                    </div>
                <?php }?>
                <div class="<?php if($assess['reminder']){?>uk-width-medium-3-4<?php }else{?>uk-width-medium-4-4 <?php } ?>">
                    <h3 class="scrum_task_title">

                        <a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $created->id;?>"><?php echo $created->video_title;?></a>
                    </h3>

                    <p class="scrum_task_title"><a href="javascript:void(0);" class="deal_detail" data-id="<?php echo $created->id;?>"><?php echo $created->first_name.' '.$created->last_name;?></a></p>
                    <div class="scrum_task_rating">
                        <p class="scrum_task_description rating"><?php echo $created->rating_point;?></p>
                    </div>
                    <p class="scrum_task_description"><?php echo date('M d, Y',strtotime($created->closing_date));?>.</p>
                    <p class="scrum_task_description">In current state: <?php echo getStageTime($created->status, $created->id);?>.</p>
                    <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($created->last_activity);?>.</p>

                    <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
                    <div class="open-grid drop-down-menu" style="display: none">

                        <?php if($assess['reminder']){?>
                            <?php if($created->reminder_sent == 1) {?>
                            <div class="sub-grid">

                                    <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light send" data-lead_id="<?php echo $created->id  ?>" data-id="<?php echo $created->client_id;?>" title="Send Reminder"><i class="material-icons">add_alert</i></a></p>

                            </div>
                            <?php } ?>
                        <?php } ?>

                        <?php /*if($assess['not_interested']){*/?><!--
                                                    <div class="sub-grid">
                                                        <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small not-interested" data-title="Account Created" data-id="<?php /*echo $created->id;*/?>" title="Move To Not Interested"><i class="material-icons">not_interested</i></a></p>
                                                    </div>
                                                --><?php /*} */?>
                    </div>

                </div>
            </div>

        </div>
    </div>
<?php } ?>
