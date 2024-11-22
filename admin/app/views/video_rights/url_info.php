<style>
    textarea{
        scroll :none;
    }
    .selectize-input{
        border-width : 0 0 0px !important;
    }
    .selectize-dropdown {
        margin-top: 0px !important;
    }
    *+.uk-table {
        margin-top: 0px !important;
    }
    #video_url, #email{
        word-break: break-all;
    }
    .get_thumb{
        width: 100%;
        float: left;
        text-align: center;
    }
</style>

<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><span><?php echo $title;?></span></li>
        </ul>
    </div>
    <div id="page_content_inner">


        <div class="md-card uk-margin-medium-bottom">

            <div class="md-card-content" style="padding: 20px;">

                <div>

                    <h2><?php echo $title;?></h2>

                    <br/>
                    <form id="form_validation2" class="uk-form-stacked" action="<?php echo base_url('compilations_urls_info_save');?>" method="post">

                        <?php foreach($leads as $i=>$lead){ ?>
                            <?php if(count($leads) > 1){ ?>
                                <h3>Lead <?php echo ($i+1);?></h3>
                            <?php } ?>
                            <input type="hidden" name="leads[<?php echo $i;?>][lead_group_id]" value="<?php echo $lead->lead_group_id;?>">
                            <input type="hidden" name="leads[<?php echo $i;?>][id]" value="<?php echo $lead->id;?>">
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label"><strong>Video URL</strong></label>
                                        <p><?php echo $lead->yt_url;?></p>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label"><strong>Youtube Video ID</strong></label>
                                        <p><?php echo $lead->yt_id;?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label"><strong>Youtube Category</strong></label>
                                        <p><?php echo $lead->category;?></p>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label"><strong>Youtube Views</strong></label>
                                        <p><?php echo $lead->views;?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select  name="leads[<?php echo $i;?>][rating]" data-md-selectize>
                                            <option value=""> Select Rating</option>
                                            <?php for($j = 1; $j<=10; $j++){ ?>
                                                <option <?php if($j == $lead->rating){ echo 'selected';} ?> value="<?php echo $j;?>"> <?php echo $j;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label">Timestamp</label>
                                    </div>
                                    <div class="parsley-row" style="width: 45%; float: left;margin-right: 10px;">
                                        <label for="message" class="uk-form-label">Start</label>
                                        <input type="text"  name="leads[<?php echo $i;?>][start_time]" value="<?php echo $lead->start_time;?>" class="md-input" />
                                    </div>
                                    <div class="parsley-row" style="width: 45%; float: left;">
                                        <label for="message" class="uk-form-label">End</label>
                                        <input type="text"  name="leads[<?php echo $i;?>][end_time]" value="<?php echo $lead->end_time;?>" class="md-input" />
                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select  name="leads[<?php echo $i;?>][assign_to]" data-md-selectize>
                                            <option value=""> Select Staff</option>
                                            <?php foreach($staffs->result() as $staff){ ?>
                                                <option <?php if($staff->id == $lead->assign_to){ echo 'selected';} ?> value="<?php echo $staff->id;?>"> <?php echo $staff->name;?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label">TikTok URL</label>
                                        <input type="text"  name="leads[<?php echo $i;?>][tt_url]" value="<?php echo $lead->tt_url;?>" class="md-input" />

                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">

                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label">TikTok ID</label>
                                        <input type="text"  name="leads[<?php echo $i;?>][tt_id]" value="<?php echo $lead->tt_id;?>" class="md-input" />

                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label">TikTok Handle</label>
                                        <input type="text"  name="leads[<?php echo $i;?>][tt_handle]" value="<?php echo $lead->tt_handle;?>" class="md-input" />

                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select  name="leads[<?php echo $i;?>][status]" data-md-selectize>
                                            <option value=""> Select Status</option>
                                            <option <?php if($lead->status == 'Unassigned'){ echo 'selected';} ?> value="Unassigned"> Unassigned </option>
                                            <option <?php if($lead->status == 'Assigned'){ echo 'selected';} ?> value="Assigned"> Assigned</option>
                                            <option <?php if($lead->status == 'In-Progress'){ echo 'selected';} ?> value="In-Progress"> In-Progress</option>
                                            <option <?php if($lead->status == 'Completed'){ echo 'Completed';} ?> value="Completed"> In-Progress</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select  name="leads[<?php echo $i;?>][acquired_status]" data-md-selectize>
                                            <option value=""> Select Acquired Status</option>
                                            <option <?php if($lead->acquired_status == 'Status 1'){ echo 'selected';} ?> value="Status 1"> Status 1</option>
                                            <option <?php if($lead->acquired_status == 'Status 2'){ echo 'selected';} ?> value="Status 2"> Status 2</option>
                                            <option <?php if($lead->acquired_status == 'Status 3'){ echo 'selected';} ?> value="Status 3"> Status 3</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        <?php } ?>


                        <br/>

                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <button type="submit" class="md-btn md-btn-primary check">Save</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
    var uid = '';
    var watertype  = '';
    var access   = '';
</script>

