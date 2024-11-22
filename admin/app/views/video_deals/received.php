<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rehman Aziz
 * Date: 5/3/2018
 * Time: 11:19 AM
 */?>
<?php foreach($dealReceived->result() as $received){?>
    <div class="sub-scrum">
        <div class="scrum_task <?php if($received->ustatus == 1){ echo 'critical';}else{ echo 'blocker';}?>">
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
                    <p class="scrum_task_description">Last Activity: <?php echo getTimeInterval($received->last_activity);?>.</p>

                    <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
                    <div class="open-grid drop-down-menu" style="display: none">

                        <?php if($assess['verify']){?>
                            <div class="sub-grid">
                                <p class="scrum_task_info" style="text-align: right;"><a href="<?php echo $url?>edit_video/<?php echo $received->video_id;?>" class="md-btn md-btn-primary md-btn-small md-btn-wave-light waves-effect waves-button waves-light" data-id="<?php echo $received->video_id;?>" title="Verify Video"><i class="material-icons">verified_user</i></a></p>
                            </div>
                        <?php } ?>

                        <?php /*if($assess['not_interested']){*/?><!--
                                                            <div class="sub-grid">
                                                                <p class="scrum_task_info" style="text-align: right;"><a href="javascript:void(0);" class="md-btn md-btn-primary md-btn-small not-interested" data-title="Deal Information Received" data-id="<?php /*echo $received->id;*/?>" title="Move To Not Interested"><i class="material-icons">not_interested</i></a></p>
                                                            </div>
                                                        --><?php /*} */?>
                    </div>

                </div>
            </div>

        </div>
    </div>
<?php } ?>
