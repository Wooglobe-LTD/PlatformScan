<?php
/**
 * Created by PhpStorm.
 * User: T3500
 * Date: 4/24/2018
 * Time: 11:40 AM
 */
?>
<style>

</style>
<style type="text/css">
    .timeline-process ol {
        position: relative;
        display: inline-block;
        margin: 0px;
        height: 4px;
        background: #ffffff;
        padding: 0px;
        width: 100%;
    }

    .timeline-process {
        width: 160%;
        float: left;
        height: auto;
        margin: 30px 1% 115px;
    }

    /* ---- Timeline elements ---- */
    .timeline-process li {
        position: relative;
        display: inline-block;
        float: left;
        width: 8.33%;
        font: bold 14px arial;
        height: 10px;
    }

    .timeline-process li .diplome {
        position: absolute;
        top: -47px;
        left: 36%;
        color: #000000;
    }

    .timeline-process li .point {
        content: "";
        top: -10px;
        left: 50%;
        margin-left: -11px;
        display: block;
        width: 17px;
        height: 17px;
        border: 3px solid #ffffff;
        border-radius: 50px;
        background: #1976d2;
        position: absolute;
    }

    .timeline-process li.active .point {
        background-color: #FFF !important;
    }

    .timeline-process ol:after {
        content: '';
        width: 4px;
        height: 15px;
        background-color: #fff;
        position: absolute;
        top: -5px;
        left: 0px;
    }

    .timeline-process ol:before {
        content: '';
        width: 4px;
        height: 15px;
        background-color: #fff;
        position: absolute;
        top: -5px;
        right: 0px;
    }

    .description::before {
        content: '';
        width: 0;
        height: 0;
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-bottom: 5px solid #f4f4f4;
        position: absolute;
        top: -5px;
        left: 43%;
    }

    .timeline-process li .description {
        display: none;
        background-color: #f4f4f4;
        padding: 10px;
        margin-top: 25px;
        position: relative !important;
        font-weight: 600;
        z-index: 1;
        font-size: 12px;
        left: 50%;
        margin-left: -72px;
        width: 135px;
        text-align: center;
        border-radius: 5px;
        color: #444;
    }

    .active.last .description {
        display: block !important;
    }

    .timeline-process li:nth-child(2n) .description {
        /* margin-top: -71px !important; */
        bottom: 85px !important;
        /*margin-left: -79px !important;*/
    }

    .timeline-process li:nth-child(2n) .description::before {
        width: 0;
        height: 0;
        border-left: 5px solid transparent !important;
        border-right: 5px solid transparent !important;
        border-top: 5px solid #f4f4f4;
        border-bottom: unset !important;
        top: unset !important;
        bottom: -5px;
    }

    /* ---- Hover effects ---- */
    .timeline-process li:hover {
        cursor: pointer;
        color: #48A4D2;
    }

    .timeline-process li:hover .description {
        display: block;
    }
</style>
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url; ?>">Dashboard</a></li>
            <li><a href="<?php echo $url; ?>youtube_compilations">Youtube Compilations</a></li>
            <li><span>Deal Detail</span></li>
        </ul>
    </div>
    <div id="page_content_inner">
        <div class="uk-grid" data-uk-grid-margin="">
            <div class="uk-width-medium-1-2 uk-row-first">
                <h4 class="heading_c uk-margin-small-bottom">Deal Detail</h4>
                <ul class="md-list">
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Video Thumbnail</span>
                            <span id="edit-title-area" class="md-list-heading" ><img src="<?php echo $dealData->thumb; ?>" alt="Thumbnail" /></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Video Title</span>
                            <span id="edit-title-area" class="md-list-heading"><?php echo $dealData->title; ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Status</span>
                            <span class="md-list-heading"><?php echo $dealData->status; ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Rating</span>
                            <span class="md-list-heading"><?php echo $dealData->rating; ?></span>
                        </div>
                    </li>
                    <?php if($videos->num_rows() > 0 && $dealData->status == 'In-Progress') { ?>
                        <li>
                            <div class="md-list-content">

                            <span class="md-list-heading">

                                <div class="uk-width-1-1">
                                <button type="button" class="md-btn md-btn-primary" id="video_compilation_claimed" >Completed</button>
                            </div>
                            </span>
                            </div>
                        </li>
                    <?php } ?>

                </ul>







                    <!--<li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Scout Name</span>
                            <span id=""
                                  class="md-list-heading rating-comments-area"
                                  ></span>

                        </div>
                    </li>-->
                </ul>
            </div>
            <div class="uk-width-medium-1-2 uk-row-first">
                <form id="video-compilation-form">
                    <input type="hidden" id="lead_id" name="lead_id" value="<?php echo $dealData->id;?>">
                <ul class="md-list">
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Video URL</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val=""><?php echo $dealData->yt_url; ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Youtube id</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val=""><?php echo $dealData->yt_id ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Views</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val=""><?php echo $dealData->views ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Category</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val=""><?php echo $dealData->category ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Submitted Date</span>
                            <span class="md-list-heading"><?php echo $dealData->created_at; ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Last Activity Date</span>
                            <span class="md-list-heading"><?php echo $dealData->updated_at; ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Unique Key</span>
                            <span id="edit-title-area" class="md-list-heading"><?php echo $dealData->wg_id; ?></span>
                        </div>
                    </li>
                    <!--<li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Claim Status</span>
                            <span class="md-list-heading">

                                <div class="uk-width-medium-1-1" style="margin-top: 18px;">
                                    <span class="icheck-inline">
                                    <input <?php /*if(!empty($dealData->claim_status)){ if($dealData->claim_status == 'Not Claimed'){ echo 'checked'; } } else { echo 'checked'; }*/?> type="radio" name="claim_status" id="claim_status" value="Not Claimed" data-md-icheck />
                                    <label for="watermark" class="inline-label"><b>Not Claimed</b>
                                    </label>
                                    </span>
                                    <span class="icheck-inline">
                                    <input <?php /*if(!empty($dealData->claim_status)){ if($dealData->claim_status == 'Claimed'){ echo 'checked'; } }*/?> type="radio" name="claim_status" id="claim_status_claimed" value="Claimed" data-md-icheck />
                                    <label for="watermark" class="inline-label"><b>Claimed</b>
                                    </label>
                                    </span>

                                </div>
                            </span>
                        </div>
                    </li>-->
                    <!--<li style="border: none;">
                        <div class="md-list-content">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="message" class="uk-form-label">Claim Comments</label>
                                    <textarea id="comments" name="comments" class="md-input" required><?php /*echo $dealData->comments;*/?></textarea>
                                    <div class="error"><?php /*echo form_error('comments');*/?></div>
                                </div>
                            </div>
                        </div>
                    </li>-->

                </ul>
                </form>
            </div>
        </div>
        <div class="uk-width-large-1-1">
            <h2>Videos</h2>
            <div class="md-card-content" style="display: none;">
                <div class="dt_colVis_buttons"></div>
                <table id="" class="" cellspacing="0" width="100%">
                    <thead>
                    <tr style="line-height: 40px;">

                        <th data-name="" style="text-align: left;">TitTok URL</th>
                        <th data-name="" style="text-align: left;">TitTok Handle</th>
                        <th data-name="" style="text-align: left;">TitTok Id</th>
                        <th data-name="" style="text-align: left;">Status</th>
                        <th data-name="" style="text-align: left;">Start Time</th>
                        <th data-name="" style="text-align: left;">End Time</th>



                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($videos->result() as $video){?>
                        <tr style="line-height: 40px;">
                            <td><?php echo $video->tt_url; ?></td>
                            <td><?php echo $video->tt_handle; ?></td>
                            <td><?php echo $video->tt_id; ?></td>
                            <td><?php echo $video->acquired_status; ?></td>
                            <td><?php echo $video->start_time; ?></td>
                            <td><?php echo $video->end_time; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                    <tr style="line-height: 40px;">
                        <th data-name="" style="text-align: left;">TitTok URL</th>
                        <th data-name="" style="text-align: left;">TitTok Handle</th>
                        <th data-name="" style="text-align: left;">TitTok Id</th>
                        <th data-name="" style="text-align: left;">Status</th>
                        <th data-name="" style="text-align: left;">Start Time</th>
                        <th data-name="" style="text-align: left;">End Time</th>

                    </tr>
                    </tfoot>




                </table>
            </div>
            <form id="form_validation2" class="uk-form-stacked" action="<?php echo base_url('compilations_urls_info_save');?>" method="post" data-parsley-required-message="This field is required">

                <?php

                    foreach($videos->result() as $i=>$lead){ ?>



                        <section style="border: 1px solid #000;padding: 10px;border-radius: 10px;margin-bottom: 10px;">

                            <input type="hidden" name="leads_info[<?php echo $i;?>][lead_group_id]" value="<?php echo $lead->lead_group_id;?>">
                            <input type="hidden" name="leads_info[<?php echo $i;?>][lead_id]" value="<?php echo $lead->lead_id;?>">
                            <div class="uk-grid" data-uk-grid-margin="">

                                <div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label">TikTok ID</label>
                                        <!--<input type="text"  name="leads_info[<?php /*echo $i;*/?>][tt_id]" value="<?php /*echo $lead->tt_id;*/?>" class="md-input" required data-parsley-required-message="This field is required." />-->
                                        <span><?php echo $lead->tt_id;?></span>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label">TikTok URL</label>
                                        <input type="url"  name="leads_info[<?php echo $i;?>][tt_url]" value="<?php echo $lead->tt_url;?>" class="md-input" required data-parsley-required-message="This field is required." />

                                    </div>
                                </div>
                                <div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label">TikTok Handle</label>
                                        <input type="text"  name="leads_info[<?php echo $i;?>][tt_handle]" value="<?php echo $lead->tt_handle;?>" class="md-input" required data-parsley-required-message="This field is required." />

                                    </div>
                                </div>
                            </div>
                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <select  name="leads_info[<?php echo $i;?>][status]" data-md-selectize>

                                            <?php if($lead->status == 'Acquired') { ?>
                                                <option <?php if($lead->status == 'Acquired'){ echo 'selected';} ?> value="Acquired"> Acquired</option>
                                            <?php }else{ ?>
                                                <option value=""> Select Status</option>
                                                <option <?php if($lead->status == 'Approached'){ echo 'selected';} ?> value="Approached"> Approached </option>
                                                <!--<option <?php /*if($lead->status == 'Acquired'){ echo 'selected';} */?> value="Acquired"> Acquired</option>-->
                                                <option <?php if($lead->status == 'Not Interested'){ echo 'selected';} ?> value="Not Interested"> Not Interested</option>
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
                                        <input type="text"  name="leads_info[<?php echo $i;?>][start_time]" value="<?php echo $lead->start_time;?>" class="md-input" data-parsley-required-message="This field is required." data-inputmask="'mask': '99:99'" data-inputmask-showmaskonhover="false" im-insert="true" />
                                    </div>
                                    <div class="parsley-row" style="width: 45%; float: left;">
                                        <label for="message" class="uk-form-label">End</label>
                                        <input type="text"  name="leads_info[<?php echo $i;?>][end_time]" value="<?php echo $lead->end_time;?>" data-parsley-required-message="This field is required." class="md-input" data-inputmask="'mask': '99:99'" data-inputmask-showmaskonhover="false" im-insert="true" />
                                    </div>
                                </div>
                            </div>

                        </section>



                    <?php }
                 ?>

                <div id="more-leads">

                </div>
                <br/>

                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <button type="submit" class="md-btn md-btn-primary check">Save</button>
                        <?php if($dealData->status == 'In-Progress'){?>
                            <button type="button" id="leads-more" class="md-btn md-btn-primary check">Add More</button>
                        <?php } ?>
                    </div>
                </div>
                <input type="hidden" id="detail_page" value="1" name="detail_page">
                <input type="hidden" name="lead_group_id" value="<?php echo $dealData->lead_group_id;?>">
                <input type="hidden" name="lead_id" value="<?php echo $dealData->id;?>">
            </form>

        </div>
  </div>

</div>

<div id="more-section" style="display: none;">
    <section style="border: 1px solid #000;padding: 10px;border-radius: 10px;margin-bottom: 10px;">
        <span class="delete-lead-info" style="float: right;cursor:pointer">X</span>

            <input type="hidden" name="leads_info[{VID}][lead_group_id]" value="<?php echo $dealData->lead_group_id;?>">
            <input type="hidden" name="leads_info[{VID}][lead_id]" value="<?php echo $dealData->id;?>">
            <div class="uk-grid" data-uk-grid-margin="">
                <div class="uk-width-medium-1-3">
                    <div class="parsley-row">
                        <label for="message" class="uk-form-label">TikTok ID</label>
                        <input type="text"  name="leads_info[{VID}][tt_id]" value="" class="md-input" required data-parsley-required-message="This field is required." />

                    </div>
                </div>
                <div class="uk-width-medium-1-3">
                    <div class="parsley-row">
                        <label for="message" class="uk-form-label">TikTok URL</label>
                        <input type="url"  name="leads_info[{VID}][tt_url]" value="" class="md-input" required data-parsley-required-message="This field is required." />

                    </div>
                </div>
                <div class="uk-width-medium-1-3">
                    <div class="parsley-row">
                        <label for="message" class="uk-form-label">TikTok Handle</label>
                        <input type="text"  name="leads_info[{VID}][tt_handle]" value="" class="md-input" required data-parsley-required-message="This field is required." />

                    </div>
                </div>
            </div>
            <div class="uk-grid" data-uk-grid-margin="">
                <div class="uk-width-medium-1-2">
                    <div class="parsley-row">
                        <select  name="leads_info[{VID}][status]" class="select2_{VID}">
                            <option value=""> Select Status</option>
                            <option value="Approached"> Approached </option>
                            <option value="Not Interested"> Not Interested</option>
                        </select>
                    </div>
                </div>
                <div class="uk-width-medium-1-2">
                    <div class="parsley-row">
                        <label for="message" class="uk-form-label">Timestamp</label>
                    </div>
                    <div class="parsley-row" style="width: 45%; float: left;margin-right: 10px;">
                        <label for="message" class="uk-form-label">Start</label>
                        <input type="text"  name="leads_info[{VID}][start_time]" value="" class="md-input masked_input" data-parsley-required-message="This field is required." data-inputmask="'mask': '99:99'" data-inputmask-showmaskonhover="false" im-insert="true" />
                    </div>
                    <div class="parsley-row" style="width: 45%; float: left;">
                        <label for="message" class="uk-form-label">End</label>
                        <input type="text"  name="leads_info[{VID}][end_time]" value="" data-parsley-required-message="This field is required." class="md-input masked_input" data-inputmask="'mask': '99:99'" data-inputmask-showmaskonhover="false" im-insert="true" />
                    </div>
                </div>
            </div>

    </section>
</div>
<script>
    var uid = "<?php echo $dealData->client_id;?>";
    var watertype = "<?php echo $dealData->client_id;?>";
    var access = "<?php echo $dealData->client_id;?>";
    var v_index   = "<?php echo $i;?>";
</script>