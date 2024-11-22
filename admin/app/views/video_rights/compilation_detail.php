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
            <li><a href="<?php echo $url; ?>video_rights">Video Rights Management</a></li>
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
                            <span class="uk-text-small uk-text-muted">Unique Key</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val="<?php echo $dealData->wg_id; ?>"><?php echo $dealData->wg_id; ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Video Title</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val=""><?php echo $dealData->title ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Video URL</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val=""><?php echo $dealData->yt_url ?></span>
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
                            <span class="uk-text-small uk-text-muted">TikTok URL</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val=""><?php echo $dealData->tt_url ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">TikTok ID</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val=""><?php echo $dealData->tt_id ?></span>
                        </div>
                    </li>
                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">TikTok Handle</span>
                            <span id="edit-title-area" class="md-list-heading"
                                  data-val=""><?php echo $dealData->tt_handle ?></span>
                        </div>
                    </li>

                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Created Date</span>
                            <span class="md-list-heading"><?php echo $dealData->created_at; ?></span>
                        </div>
                    </li>

                    <li>
                        <div class="md-list-content">
                            <span class="uk-text-small uk-text-muted">Status</span>
                            <span class="md-list-heading"><?php echo $dealData->status; ?></span>
                        </div>
                    </li>












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
                            <span class="uk-text-small uk-text-muted">Claim Status</span>
                            <span class="md-list-heading">

                                <div class="uk-width-medium-1-1" style="margin-top: 18px;">
                                    <!--<span class="icheck-inline">
                                    <input <?php /*if(!empty($dealData->claim_status)){ if($dealData->claim_status == 'Not Claimed'){ echo 'checked'; } } else { echo 'checked'; }*/?> type="radio" name="claim_status" id="claim_status" value="Not Claimed" data-md-icheck />
                                    <label for="watermark" class="inline-label"><b>Not Claimed</b>
                                    </label>
                                    </span>-->
                                    <span class="icheck-inline">
                                    <input <?php if(!empty($dealData->claim_status)){ if($dealData->claim_status == 'Claimed'){ echo 'checked'; } }else { echo 'checked'; }?> type="radio" name="claim_status" id="claim_status_claimed" value="Claimed" data-md-icheck />
                                    <label for="watermark" class="inline-label"><b>Claimed</b>
                                    </label>
                                    </span>
                                    <!--<span class="icheck-inline">
                                    <input style="margin-left:15px;" <?php /*if($videoData->claimed_source == 'Raw'){ echo 'checked'; }*/?> type="radio" name="claimed_source" id="claimed_source" value="Raw" data-md-icheck />
                                    <label for="watermark" class="inline-label"><b>Raw Videos URL </b>
                                    </label>
                                    </span>-->
                                </div>
                            </span>
                        </div>
                    </li>
                    <li style="border: none;">
                        <div class="md-list-content">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="message" class="uk-form-label">Claim Comments</label>
                                    <textarea id="comments" name="comments" class="md-input" required><?php echo $dealData->comments;?></textarea>
                                    <div class="error"><?php echo form_error('comments');?></div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php if($dealData->claim_status == '') { ?>
                    <li>
                        <div class="md-list-content">

                            <span class="md-list-heading">

                                <div class="uk-width-1-1">
                                <button type="button" class="md-btn md-btn-primary" id="video_compilation_claimed" style="float: right;">Claimed</button>
                            </div>
                            </span>
                        </div>
                    </li>
                    <?php } ?>
                </ul>
                </form>
            </div>
        </div>
  </div>

</div>

<script>
    var uid = "<?php echo $dealData->client_id;?>";
    var watertype = "<?php echo $dealData->client_id;?>";
    var access = "<?php echo $dealData->client_id;?>";
</script>