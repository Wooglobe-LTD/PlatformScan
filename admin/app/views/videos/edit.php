
<style type="text/css">
    .box-set{
        float: left;
    }
    .md-card .md-card-head {
        border-bottom: 0px solid rgba(0,0,0,.12) !important;
    }
    .box-set .md-bg-grey-900 {
        margin-right: 40px;
    }
    .selectize-dropdown {
        margin-top: 0px !important;
    }
    .selectize-control.plugin-remove_button [data-value] .remove:after{
        content: ''!important;
    }
    .chosen-select{
        width: 100% !important;
    }
    .selectize-control {
        z-index: 99 !important;
    }
    .selectize-control.plugin-remove_button [data-value] {
        padding-right: 24px!important;
    }

    .selectize-control.plugin-remove_button [data-value] .remove {
        padding: 0px 10px 0 0 !important;
        font-size: 10px;
        top: -2px;
    }
    span.mandatory{
        color: #f5544d;
        font-size: 12px;
    }
    .uk-close:after {
        content: '' !important;
    }
    a#description_reminder {
        padding: 4px 10px;
        margin: 5px 0;
        background-color: #1976d2;
        border: 0px;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        font-size: 12px;
        display: block;
        width: 160px;
        text-align: center;
    }
</style>
<div id="page_content" style="display: flex;">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><a href="<?php echo $url;?>videos">Videos Management</a></li>
            <li><span>Edit Video (<?php echo $data->title;?>)</span></li>
        </ul>
    </div>
    <div id="page_content_inner">

        <div class="md-card video-edit-content">

            <div class="md-card-content">


                <div class="md-card-content edit-video-form">
                    <form id="form_validation3" class="uk-form-stacked grid-half">
                        <p class="heading_b uk-margin-bottom"><?php echo $title;?></p>
                        <input type="hidden" name="id" value="<?php echo $data->id;?>" id="id" />
                        <div class="uk-grid" data-uk-grid-margin style="margin-bottom: 20px;">

                            <div class="uk-width-medium-1-1">
                                <input <?php if($data->is_real_file == 1){ echo 'checked'; }?> type="checkbox" name="is_real_file" id="is_real_file" value="1" data-md-icheck />
                                <label for="is_real_file" class="inline-label"><b>Is Video File correct? <span class="mandatory">*</span></b><br>
                                    <p>To verify video</p><a href="<?php echo$lead->video_url;?>" target="_blank">Click Here</a>
                                </label>
                            </div>
                            <div class="uk-width-medium-1-1">
                                <input <?php if($data->real_deciption_updated == 1){ echo 'checked'; }?> type="checkbox" name="real_deciption_updated" id="real_deciption_updated" value="1" data-md-icheck />
                                <label for="is_wooglobe_video" class="inline-label"><b>Is Real Video Description Updated? <span class="mandatory">*</span></b><br>
                                    <p>To verify video description</p> <a href="<?php echo$lead->video_url;?>" target="_blank">Click Here</a>
                                    <a href="#" id="description_reminder" data-id="<?php echo$lead->id; ?>" title="Send Reminder to client to update description">Update Description Reminder</a>
                                </label>
                            </div>

                            <div class="uk-width-medium-1-1">
                                <input <?php if($data->is_high_quality == 1){ echo 'checked'; }?> type="checkbox" name="is_high_quality" id="is_high_quality" value="1" data-md-icheck />
                                <label for="is_high_quality" class="inline-label"><b>HD quality</b>
                                </label>
                            </div>



                            <div class="uk-width-medium-1-1">
                                <input <?php if($data->is_complete_file == 1){ echo 'checked'; }?> type="checkbox" name="is_complete_file" id="is_complete_file" value="1" data-md-icheck />
                                <label for="is_complete_file" class="inline-label"><b>Have all videos been uploaded? <span class="mandatory">*</span></b>
                                </label>
                            </div>
                            <!--<div class="uk-width-medium-1-1">
                                <input <?php /*if($data->mrss == 1){ echo 'checked'; }*/?> type="checkbox" name="mrss" id="mrss" value="1" data-md-icheck />
                                <label for="mrss" class="inline-label"><b>Publish to MRSS</b>
                                </label>
                            </div>-->



                            <div class="uk-width-medium-1-1">
                                <input <?php if($data->second_signer == 1){ echo 'checked'; }?> type="checkbox" name="second_signer" id="Signer_Required" value="1" data-md-icheck />
                                <label for="watermark" class="inline-label"><b>Second Signer Required </b>
                                </label>
                            </div>
                            <div id ="value_div" class="uk-width-medium-1-1" style="display: none;margin-top: 18px;">
                                <span class="icheck-inline">
                                <input <?php if(!empty($data->second_signer_required)){ if($data->second_signer_required == 'Yes'){ echo 'checked'; } } else { echo 'checked'; }?> type="radio" name="second_signer_required" id="second_signer_required" value="Yes" data-md-icheck />
                                <label for="watermark" class="inline-label"><b> Yes</b>
                                </label>
                                </span>
                                <span class="icheck-inline">
                                <input style="margin-left:15px;" <?php if($data->second_signer_required == 'No'){ echo 'checked'; }?> type="radio" name="second_signer_required" id="second_signer_required" value="No" data-md-icheck />
                                <label for="watermark" class="inline-label"><b>No </b>
                                </label>
                                </span>
                            </div>

                            <div class="uk-width-medium-1-1">
                                <input  <?php if($data->sign_appreance == 1){ echo 'checked'; }?> type="checkbox" name="sign_appreance" id="sign_appreance" value="1" data-md-icheck />
                                <label for="is_high_quality" class="inline-label"><b>Sign appreance release</b>
                                </label>
                            </div>


                        </div>


                        <div id ="release_description_div" class="uk-grid" data-uk-grid-margin style="display: none;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="description" class="uk-form-label">Identify The Person</label>
                                    <textarea id="release_description" name="release_description" cols="30" rows="10" class="md-input"><?php echo $data->release_description;?></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>

                        </div>
                        <h4 style="color: black;">Youtube Publish</h4>
                        <div id ="" class="uk-width-medium-1-1" style="margin-top: 18px;">
                                <span class="icheck-inline">
                                    <input  <?php if(empty($data->is_youtube_publish) || $data->is_youtube_publish == 'Yes'){ echo 'checked'; }?> type="radio" name="is_youtube_publish" id="is_youtube_publish_yes" value="Yes" data-md-icheck />
                                    <label for="is_youtube_publish_yes" class="inline-label"><b>Yes</b></label>
                                </span>
                            <span class="icheck-inline">
                                    <input  <?php if($data->is_youtube_publish == 'No'){ echo 'checked'; }?> type="radio" name="is_youtube_publish" id="is_youtube_publish_no" value="No" data-md-icheck />
                                    <label for="is_youtube_publish_no" class="inline-label"><b>No</b></label>
                                </span>
                        </div>
                        <div class="uk-grid yt_publish" id="youtube_publish_reson_yes_div" data-uk-grid-margin style="display: <?php if(empty($data->is_youtube_publish) || $data->is_youtube_publish == 'Yes'){ echo 'block'; }else{ echo 'none';}?>;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="parent" class="uk-form-label">Publish Reason <span class="req">*</span></label>
                                    <select id="youtube_publish_reson_yes" name="youtube_publish_reson_yes" data-placeholder="Select" class="" data-parsley-required-message="This field is required." style="width:100%;">
                                        <option <?php if(empty($data->youtube_publish_reson_yes) || $data->youtube_publish_reson_yes == 'All Criteria Met') { echo 'selected';}?> value="All Criteria Met">All Criteria Met</option>
                                    </select>

                                    <div class="error"></div>
                                </div>
                            </div>


                        </div>
                        <div class="uk-grid yt_publish" id="youtube_publish_reson_no_div" data-uk-grid-margin style="display: <?php if($data->is_youtube_publish == 'No'){ echo 'block'; }else{ echo 'none';}?>;">
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="parent" class="uk-form-label">Not Publish Reason <span class="req">*</span></label>
                                    <select id="youtube_publish_reson_no" name="youtube_publish_reson_no" data-placeholder="Select" class="" data-parsley-required-message="This field is required." style="width:100%;">
                                        <option <?php if(empty($data->youtube_publish_reson_no) || $data->youtube_publish_reson_no == 'TikTok Compilation Content, To Be Claimed Through ContentID') { echo 'selected';}?> value="TikTok Compilation Content, To Be Claimed Through ContentID">TikTok Compilation Content, To Be Claimed Through ContentID</option>
                                        <option <?php if($data->youtube_publish_reson_no == 'Other Videos, To Be Claimed Through ContentID') { echo 'selected';}?> value="Other Videos, To Be Claimed Through ContentID">Other Videos, To Be Claimed Through ContentID</option>
                                        <option <?php if($data->youtube_publish_reson_no == 'Against Community Guidelines') { echo 'selected';}?> value="Against Community Guidelines">Against Community Guidelines</option>
                                        <option <?php if($data->youtube_publish_reson_no == 'Special Case') { echo 'selected';}?> value="Special Case">Special Case</option>

                                    </select>

                                    <div class="error"></div>
                                </div>
                            </div>


                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="title">Video Title<span class="req">*</span></label>
                                    <input type="text" name="title" value="<?php echo $data->title;?>" id="title"  data-parsley-required-message="This field is required." required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="description" class="uk-form-label">Description</label>
                                    <textarea id="description" name="description" cols="30" rows="10" class="md-input"><?php echo $data->description;?></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>

                        </div>


                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="parent" class="uk-form-label">Category<span class="req">*</span></label>
                                    <select id="category_id" name="category_id[]" data-placeholder="Select Category" class="selectize-control" data-parsley-required-message="This field is required." multiple style="width:100%;">
                                        <?php
                                        $categ = explode(',',$data->category_id);
                                        $catrory = '';
                                        foreach($categories->result() as $cat){
                                            if(in_array($cat->id,$categ)){
                                                ?>
                                                <?php //echo $catrory = $cat->title;;?>
                                                <option selected value="<?php echo $cat->id;?>"><?php echo $cat->title;?></option>
                                            <?php }else{?>
                                                //$catrory = $cat->title;
                                                <option value="<?php echo $cat->id;?>"><?php echo $cat->title;?></option>
                                            <?php }
                                        } ?>
                                    </select>

                                    <div class="error"></div>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="parent" class="uk-form-label">Reuters Category<span class="req">*</span></label>
                                    <select id="reuters_category_id" name="reuters_category_id[]" data-placeholder="Select Category" class="selectize-control" data-parsley-required-message="This field is required." multiple style="width:100%;">
                                        <?php
                                        $categ = explode(',',$data->reuters_category_id);
                                        $catrory = '';
                                        foreach($reuters_categories->result() as $cat){
                                            if(in_array($cat->id,$categ)){
                                                ?>
                                                <?php //echo $catrory = $cat->title;;?>
                                                <option selected value="<?php echo $cat->id;?>"><?php echo $cat->title;?></option>
                                            <?php }else{?>
                                                // //$catrory = $cat->title;
                                                <option value="<?php echo $cat->id;?>"><?php echo $cat->title;?></option>
                                            <?php }
                                        } ?>
                                    </select>

                                    <div class="error"></div>
                                </div>
                            </div>
                            <!--<div class="uk-width-medium-1-2">
                                <div class="parsley-row">
                                    <label for="category_id" class="uk-form-label">Sub Categories<span class="req">*</span></label>
                                    <select id="category_id" name="category_id" data-parsley-required-message="This field is required." required data-md-selectize>
                                        <option value="">Choose..</option>

                                    </select>
                                    <div class="error"></div>
                                </div>
                            </div>-->


                        </div>


                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="tags" class="uk-form-label">Tags<span class="req">*</span></label>
                                    <textarea id="tags" name="tags" class="md-input" data-parsley-required-message="This field is Mandatory."
                                              required ><?php echo $data->tags;?></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>


                        <div class="uk-grid" data-uk-grid-margin >
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="question_video_taken" class="uk-form-label">Where was this taken? Country/City etc.</label>
                                    <input type="text" name="question_video_taken" value="<?php echo $data->question_video_taken;?>" id="question_video_taken"  data-parsley-required-message="This field is required." required class="md-input" />
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin >
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="question_when_video_taken" class="uk-form-label">When was this video Taken?</label>
                                    <input data-uk-datepicker="{format:'YYYY-MM-DD'}" type="text" name="question_when_video_taken" value="<?php echo $data->question_when_video_taken;?>" id="question_when_video_taken"  data-parsley-required-message="This field is required." required class="md-input" readonly />
                                    <div class="error"></div>

                                </div>
                            </div>
                        </div>
                        <?php if(count($raw_video) == 1){ ?>
                            <section >
                                <p class="heading_b uk-margin-bottom" style="margin-top:10px;margin-bottom:10px;">Video Encoding</p>
                                <div class="uk-width-medium-1-1" style="margin-top:10px;margin-bottom:10px;">
                                    <input <?php if($data->watermark == 1){ echo 'checked'; }?> type="checkbox" name="watermark" id="watermark" value="1" data-md-icheck />
                                    <label for="watermark" class="inline-label"><b>Add Watermark? <span class="mandatory">*</span></b>
                                    </label>
                                </div>
                                <div  id ="watermark_div" class="uk-grid" data-uk-grid-margin style="display:<?php if($data->watermark == 1){ echo 'block'; }else{ echo 'none';}?>;">
                                    <div class="uk-width-medium-1-1">
                                        <div class="parsley-row">
                                            <label for="video_editing_description" class="uk-form-label">Video Editing Description</label>
                                            <textarea id="video_editing_description" name="video_editing_description" cols="30" rows="10" data-parsley-required-message="This field is required." class="md-input" ><?php echo $data->video_editing_description;?></textarea>
                                            <div class="error"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="uk-width-medium-1-1" style="margin-top:10px;margin-bottom:10px;">
                                    <input <?php if($data->outro_video == 1){ echo 'checked'; }?> type="checkbox" name="outro_video" id="outro_video" value="1" data-md-icheck />
                                    <label for="outro_video" class="inline-label"><b>Outro Video</b>
                                    </label>
                                </div>
                                <div id ="outro_video_div" class="uk-width-medium-1-1" style="display:<?php if($data->outro_video == 1){ echo 'block'; }else{ echo 'none';}?>;margin-top: 18px;">
                                <span class="icheck-inline">
                                <input <?php  if($data->outro_video_value == 'square'){ echo 'checked'; } ?> type="radio" name="outro_video_value" id="square" value="square" data-md-icheck />
                                <label for="watermark" class="inline-label"><b>Square</b>
                                </label>
                                </span>
                                    <span class="icheck-inline">
                                <input style="margin-left:15px;" <?php if($data->outro_video_value == 'vertical'){ echo 'checked'; }?> type="radio" name="outro_video_value" id="vertical" value="vertical" data-md-icheck />
                                <label for="watermark" class="inline-label"><b>Vertical</b>
                                </label>
                                </span>
                                    <span class="icheck-inline">
                                <input style="margin-left:15px;" <?php if($data->outro_video_value == 'horizantal'){ echo 'checked'; }?> type="radio" name="outro_video_value" id="Horizantal" value="horizantal" data-md-icheck />
                                <label for="watermark" class="inline-label"><b>Horizontal</b>
                                </label>
                                </span>
                                </div>
                                <div class="uk-width-medium-1-1" style="margin-top:10px;margin-bottom:10px;">
                                    <input <?php if($data->video_credits == 1){ echo 'checked'; }?> type="checkbox" name="video_credits" id="video_credits" value="1" data-md-icheck />
                                    <label for="video_credits" class="inline-label"><b>Video Credits</b>
                                    </label>
                                </div>
                                <div id ="video_credits_div" class="uk-width-medium-1-1" style="display: <?php if($data->video_credits == 1){ echo 'block'; }else{ echo 'none';}?>;margin-top: 18px;">
                                <span class="icheck-inline">
                                <input <?php if(!empty($data->video_credits_value)){ if($data->video_credits_value == 'facebook'){ echo 'checked'; } }?> type="radio" name="video_credits_value" id="facebook" value="facebook" data-md-icheck />
                                <label for="watermark" class="inline-label"><b>Facebook</b>
                                </label>
                                </span>
                                    <span class="icheck-inline">
                                <input style="margin-left:15px;" <?php if($data->video_credits_value == 'youtube'){ echo 'checked'; }?> type="radio" name="video_credits_value" id="youtube" value="youtube" data-md-icheck />
                                <label for="watermark" class="inline-label"><b>Youtube</b>
                                </label>
                                </span>
                                    <span class="icheck-inline">
                                <input style="margin-left:15px;" <?php if($data->video_credits_value == 'instagram'){ echo 'checked'; }?> type="radio" name="video_credits_value" id="instagram" value="instagram" data-md-icheck />
                                <label for="watermark" class="inline-label"><b>Instagram</b>
                                </label>
                                </span>
                                    <span class="icheck-inline">
                                <input style="margin-left:15px;" <?php if($data->video_credits_value == 'twitter'){ echo 'checked'; }?> type="radio" name="video_credits_value" id="twitter" value="twitter" data-md-icheck />
                                <label for="watermark" class="inline-label"><b>Twitter</b>
                                </label>
                                </span>
                                    <span class="icheck-inline">
                                <input style="margin-left:15px;" <?php if($data->video_credits_value == 'tiktok'){ echo 'checked'; }?> type="radio" name="video_credits_value" id="tiktok" value="tiktok" data-md-icheck />
                                <label for="watermark" class="inline-label"><b>Tiktok</b>
                                </label>
                                </span>
                                    <div class="uk-grid" data-uk-grid-margin>
                                        <div class="uk-width-medium-1-1">
                                            <div class="parsley-row">
                                                <label for="title">Credit User Name</label>
                                                <input type="text" name="credit_user_name" value="<?php echo $data->credit_user_name;?>" id="credit_user_name"  data-parsley-required-message="This field is required."  class="md-input" />
                                                <div class="error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="uk-width-medium-1-1" style="margin-top:10px;margin-bottom:10px;">
                                <input <?php /*if($data->social_icon == 1){ echo 'checked'; }*/?> type="checkbox" name="social_icon" id="social_icon" value="1" data-md-icheck />
                                <label for="social_icon" class="inline-label"><b>Social Icon</b>
                                </label>
                            </div>-->
                                <div class="uk-width-medium-1-1" style="margin-top:10px;margin-bottom:10px;">
                                    <input <?php if($data->sound_mute == 1){ echo 'checked'; }?> type="checkbox" name="sound_mute" id="sound_mute" value="1" data-md-icheck />
                                    <label for="sound_mute" class="inline-label"><b>Sound Mute</b>
                                    </label>
                                </div>

                            </section>
                        <?php } ?>
                        <!--<div class="uk-grid" data-uk-grid-margin >
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="question_video_information" class="uk-form-label">Any information such as names, locations, or any other interesting elements are important to us</label>
                                    <textarea id="question_video_information" name="question_video_information" class="md-input"><?php /*echo $data->question_video_information;*/?></textarea>
                                    <div class="error"></div>

                                </div>
                            </div>
                        </div>-->
                        <input type="hidden" name="is_category_verified" id="is_category_verified" value=""/>
                        <input type="hidden" name="is_tags_verified" id="is_tags_verified" value="" />
                        <input type="hidden" name="is_title_verified" id="is_title_verified" value="" />
                        <input type="hidden" name="is_description_verified" id="is_description_verified" value=""/>
                        <input type="hidden" name="video_verified" id="video_verified" value=""/>
                        <input type="hidden" name="is_orignal_video_verified" id="is_orignal_video_verified" value=""/>




                        <!--
                        <div class="uk-grid" data-uk-grid-margin>

                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="video_type_id" class="uk-form-label">Video Type<span class="req">*</span></label>
                                    <select id="video_type_id" name="video_type_id" data-parsley-required-message="This field is required." required data-md-selectize>
                                        <option value="">Choose..</option>
                                        <?php
                        /* $typ = '';
                         foreach($videoTypes->result() as $type){
                             if($data->video_type_id != $type->id){?>
                             ?>
                             <option value="<?php echo $type->id;?>"><?php echo $type->title;?></option>
                         <?php }else{
                                 $typ = $type->title;
                             }
                         }*/
                        ?>
                                    </select>
                                    <div class="error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="uk-grid" id="upload" data-uk-grid-margin style="display:none;">
                            <div class="uk-width-medium-1-1">
                                <div id="file_upload-drop" class="uk-file-upload">
                                <p class="uk-text">Drop file to upload</p>
                                <p class="uk-text-muted uk-text-small uk-margin-small-bottom">or</p>
                                <a class="uk-form-file md-btn">choose file<input id="file_upload-select" type="file"></a>
                            </div>
                            <div id="file_upload-progressbar" class="uk-progress uk-hidden">
                                <div class="uk-progress-bar" style="width:0">0%</div>
                            </div>
                                <div class="error"></div>
                            </div>

                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row">
                                    <label for="url" class="uk-form-label">Embed Code/URL</label>
                                    <textarea id="url" name="url" class="md-input" data-parsley-required-message="This field is required." required><?php echo $data->url;?></textarea>
                                    <div class="error"></div>
                                </div>
                            </div>

                        </div>
                        -->
                        <!-- <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="md-card">
                                    <div class="md-card-content">
                                        <h3 class="heading_a uk-margin-small-bottom">
                                            Video Thumbnail
                                        </h3>
                                        <input type="file" id="input-file-b" name="thumb" class="dropify" data-default-file="<?php /*echo $root.$data->thumbnail;*/?>"/>
                                        <div class="error"></div>
                                    </div>
                                </div>
                            </div>
                        </div>-->

                        <br/>
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <button type="submit" class="md-btn md-btn-primary check">Save</button>
                            </div>
                        </div>
                    </form>
                    <br>
                </div>



                <?php $total = count($raw_video);
                $i = 1;

                ?>
                <?php foreach ($raw_video as $video){
                    if($i == 1 || (($i%2) == 1)){
                        ?>
                        <!--<div class="uk-grid grid-half" data-uk-grid-margin>-->
                    <?php }
                    ?>
                    <div class="uk-width-medium-3-6 uk-grid edit-video-container">
                        <div class="uk-width-medium-3-6 box-set video-container">
                            <div class="md-card-head md-bg-grey-900">
                                <div class="uk-cover uk-position-relative uk-height-1-1 transform-origin-50" >
                                    <video style="width: 100%;height: 100%;" controls ><source src="<?php echo $root.$video['url'];?>">Your browser does not support HTML5 video.</video>
                                </div>
                            </div>
                        </div>

                        <div class="uk-width-medium-3-6 box-set video-detail">
                            <?php

                            if(file_exists('./'.$video['url'])){
                                $file_data = $getid3->analyze('./'.$video['url']);
                            }else if(file_exists('./../'.$video['url'])){
                                $file_data = $getid3->analyze('./../'.$video['url']);
                            }else{
                                $file_data = "";
                            }

                            if(is_array($file_data) && (!isset($file_data['error']))){
                                ?>

                                <div><b> Size :</b> <?php echo formatBytes($file_data['filesize']);?></div>
                                <div><b> Quality :</b> <?php
                                    if($file_data['video']['resolution_x'] < 1280 && $file_data['video']['resolution_y'] < 720){
                                        echo 'Low Quality';
                                    }else if(($file_data['video']['resolution_x'] >= 1280 && $file_data['video']['resolution_y'] >= 720) && ($file_data['video']['resolution_x'] <= 1920 && $file_data['video']['resolution_y'] <= 1080)){

                                        echo '720P';
                                    }else if(($file_data['video']['resolution_x'] >= 1920 && $file_data['video']['resolution_y'] >= 1080) && ($file_data['video']['resolution_x'] <= 2048 )){

                                        echo '1080P';
                                    }else if(($file_data['video']['resolution_x'] >= 2048) && ($file_data['video']['resolution_x'] <= 4096 )){

                                        echo '2K';
                                    }else if($file_data['video']['resolution_x'] >= 4096){

                                        echo '4K';
                                    }else{
                                        echo 'Low Quality';
                                    }

                                    ?>
                                </div>

                                <div><b> Dimension :</b> <?php echo $file_data['video']['resolution_x'].' X '.$file_data['video']['resolution_y'];?></div>
                                <div><b> Frame Rate :</b> <?php echo $file_data['video']['frame_rate'];?></div>
                                <div><b> Duration :</b> <?php echo $file_data['playtime_string'];?></div>
                            <?php } ?>
                            <div>
                                <a href="<?php echo $root.$video['url'];?>" target="_blank">Video url</a><br>
                                <a  href="<?php echo site_url('video_Deals/deal_detail/'.$video["lead_id"]) ?>">Deal Details</a>
                                <?php if($total > 1){ ?>
                                    <a  href="#" class="delete_btn" data-id="<?php echo $video["id"] ?>" data-url="<?php echo $video['url'];?>">Delete Video</a>
                                <?php } ?>

                            </div>


                        </div>


                    </div>





                    <?php if($i == $total || (($i%2) == 0)){?>
                        <!--</div>-->
                    <?php }
                    $i++;
                }?>


            </div>
        </div>

    </div>

    <div class="uk-modal" id="add_model">
        <div class="uk-modal-dialog">
            <div class="uk-modal-header">
                <h3 class="uk-modal-title">Checklist</h3>
            </div>

            <div class="md-card-content">
                <form id="" class="uk-form-stacked">
                    <input type="hidden" name="lead_id" id="lead_id" value="">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <input type="checkbox" name="category_verified" id="category_verified" value="1" data-md-icheck />
                            <label for="is_category_verified" class="inline-label"><b>Is Category Have been verified?</b></label>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <input type="checkbox" name="tags_verified" id="tags_verified" value="1" data-md-icheck />
                            <label for="is_tags_verified" class="inline-label"><b>Is Tags Have been verified?</b></label>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <input type="checkbox" name="title_verified" id="title_verified" value="1" data-md-icheck />
                            <label for="is_title_verified" class="inline-label"><b>Is Title have been verified?</b></label>
                        </div>
                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <input type="checkbox" name="description_verified" id="description_verified" value="1" data-md-icheck />
                            <label for="description_verified" class="inline-label"><b>Is Description have been verified?</b></label>
                        </div>
                    </div><div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <input type="checkbox" name="orignal_video_verified" id="orignal_video_verified" value="1" data-md-icheck />
                            <label for="orignal_video_verified" class="inline-label"><b>Is Orignal Video have been verified?</b></label>
                        </div>
                    </div>

                </form>
            </div>
            <div class="uk-modal-footer uk-text-right">
                <button type="button" class="md-btn md-btn-flat uk-modal-close">Close</button><button type="button" id="verified_form" class="md-btn md-btn-flat md-btn-flat-primary ">Vierified</button>
            </div>
        </div>
    </div>

    <script>
        var edit_data = '<?php echo $edit_data;?>';
        var caterory = '<?php echo $catrory;?>';
        var usr = '<?php //echo $usr;?>';
        var sts = '<?php //echo $status;?>';
        var ukey = '';
        //  var typ = '<?php //echo $typ;?>';
    </script>
