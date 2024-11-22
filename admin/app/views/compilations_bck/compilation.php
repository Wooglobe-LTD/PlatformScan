<?php $baseurl=base_url(); ?>
<?php foreach($compilation->result() as $dis){
    ?>

    <div class="sub-scrum">
        <div class="scrum_task" >
            <div class="uk-grid" data-uk-grid-margin>

                <div class="uk-width-medium-4-4" >
                    <h3 class="scrum_task_title">

                        <a href="<?php echo $baseurl.'video-compilation-detail/'.$dis->id ?>" class="deal_detail" data-id="<?php echo $dis->id;?>"><?php echo $dis->title;?></a>
                    </h3>


                    <p class="scrum_task_description">YT ID: <?php echo $dis->yt_id ;?></p>
                    <p class="scrum_task_description">Status: <?php echo $dis->status; ?>
                    <p class="scrum_task_description">Rating: <?php echo $dis->rating; ?>
                    <p class="scrum_task_description">Submitted date: <?php echo $dis->created_at; ?>
                    <div class="select-dropdown"><i class="material-icons drop-down">more_vert</i></div>
                    <div class="open-grid drop-down-menu" style="display: none">



                        <?php //if($assess['can_view_contract']){?>
                            <div class="sub-grid">
                                <p class="" style="text-align: right; margin-bottom: 0px;"><a href="<?php echo $url.'compilations_urls_info_edit/'.$dis->lead_group_id.'/'.$dis->id;?>" class="md-btn md-btn-primary md-btn-small" data-id="" data-name="" data-email="" title="Edit Lead"><i class="material-icons">&#xE254;</i></a></p>
                            </div>
                        <?php //} ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php } ?>
