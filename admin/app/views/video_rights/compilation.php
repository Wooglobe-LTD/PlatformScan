<?php $baseurl=base_url(); ?>
<?php foreach($compilation->result() as $dis){
    ?>

    <div class="sub-scrum">
        <div class="scrum_task <?php if($dis->status == 1){ echo 'critical';}else{ echo 'blocker';}?>" <?php if($dis->simple_video == 1) { echo 'style="background: #C8C8C8;"'; } ?>>
            <div class="uk-grid" data-uk-grid-margin>

                <div class="uk-width-medium-4-4" >
                    <h3 class="scrum_task_title">

                        <a href="<?php echo $baseurl.'video-compilation-detail-rights/'.$dis->cid.'/'.$dis->ciid ?>" class="deal_detail" data-id="<?php echo $dis->id;?>"><?php echo $dis->ctitle;?></a>
                    </h3>

                    <!--                    <p class="scrum_task_title"><a href="<?php /*echo $baseurl.'deal-detail/'.$dis->id */?>" class="deal_detail" data-id="<?php /*echo $dis->id;*/?>"><?php /*echo $dis->first_name.' '.$dis->last_name;*/?></a></p>
-->
                    <!--<p class="scrum_task_description"><?php /*echo date('M d, Y',strtotime($dis->closing_date));*/?>.</p>
                    <p class="scrum_task_description">In current state: <?php /*echo getStageTime($dis->status, $dis->id);*/?>.</p>
                    <p class="scrum_task_description">Last Activity: <?php /*echo getTimeInterval($last_activity);*/?>.</p>-->
                    <p class="scrum_task_description">YT ID: <?php echo $dis->yt_id ;?></p>
                    <p class="scrum_task_description">YT Views: <?php echo $dis->views ;?></p>
                    <p class="scrum_task_description">YT Category: <?php echo $dis->category ;?></p>
                    <p class="scrum_task_description">Submitted date: <?php echo $dis->created_at; ?>
                        <!-- <p class="scrum_task_description">Assigned Staff: <?php /*echo (empty($dis->staff_name))?'Wooglobe':$dis->staff_name;*/?></p>-->
                    <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
                    <div class="open-grid drop-down-menu" style="display: none">



                        <?php //if($assess['can_view_contract']){?>
                            <!--<div class="sub-grid">
                                <p class="" style="text-align: right; margin-bottom: 0px;"><a target="_blank" href="<?php /*echo $url.'compilations_urls_info/'.$dis->lead_group_id.'/'.$dis->id;*/?>" class="md-btn md-btn-primary md-btn-small" data-id="" data-name="" data-email="" title="View Contract"><i class="material-icons">&#xE254;</i></a></p>
                            </div>-->
                        <?php //} ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php } ?>
