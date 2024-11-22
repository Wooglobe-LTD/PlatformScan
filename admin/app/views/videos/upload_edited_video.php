<?php
/**
 * Created by PhpStorm.
 */
?>

<style>
    /* fileuploader */
    .fileuploader-action-remove {
        background: #dc4910eb;
        outline: 0;
        text-decoration: none;
        color: #ffffff;
        -webkit-box-shadow: 0 3px 6px rgba(0,0,0,.16), 0 3px 6px rgba(0,0,0,.23);
        box-shadow: 0 3px 6px rgba(0,0,0,.16), 0 3px 6px rgba(0,0,0,.23);
        min-height: 31px;
        min-width: 70px;
        padding: 2px 16px;
        text-align: center;
        text-shadow: none;
        text-transform: uppercase;
        -webkit-transition: all 280ms ease;
        transition: all 280ms ease;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        cursor: pointer;
        -webkit-appearance: none;
        display: inline-block;
        vertical-align: middle;
        font: 500 14px/31px Roboto,sans-serif!important;
        border: none;
        border-radius: 2px;
    }

    .progress-bar2 {
        margin-bottom:10px;
    }
    /* fileuploader */

	.dropify-wrapper.has-error .dropify-message .dropify-error {
		top: -100px;
		position: relative;
	}
	.dropify-errors-container ul li{
		color: #e53935;
		list-style: none;
	}
	.dropify-errors-container{
		margin-top: 7px;
	}
	.dropify-errors-container ul{
		padding: 0px;
	}
    .grd-bdr{
        width: 100%;
        height: auto;
        border: 1px solid #d8d8d8;
        margin-left: 0px;
    }
    .uk-file-upload {
        margin: 18px 0px !important;
        height: 166px;
    }
    .dropify-wrapper {
        margin: 18px 0px;
        height: 166px;
        position: relative;
    }
    .padd-0{
        padding: 0px 15px !important;
    }
    .s_msg{
        display: none;
    }
    .uk-hidden .s_msg{
        display: block !important;
        margin-top: 10px;
    }
    #file_upload-drop_yt{
        position: relative;
    }
    #file_upload-drop_fb{
        position: relative;
    }
    #file_upload-progressbar_yt{
        position: absolute;
        width: 100%;
        top: 0px;
        left: 0px;
    }
    #file_upload-drop_portal{
        position: relative;
    }
    #file_upload-progressbar_portal{
        position: absolute;
        width: 100%;
        top: 0px;
        left: 0px;
    }
    #file_upload-progressbar_fb{
        position: absolute;
        width: 100%;
        top: 0px;
        left: 0px;
    }
    .uk-hidden .uk-progress-bar{
        display: none;
    }
    .box-set .md-bg-grey-900 {
        margin-right: 40px;
    }
	.selectize-dropdown {
		margin-top: 0px;
	}
    .box-set {
        float: left;
    }
    .uk-hidden{
        display: block !important;
        visibility: visible !important;
    }
    .uk-progress {
        overflow: visible !important;
    }
	.selectize-control.plugin-remove_button [data-value] .remove:after {
		content: ''!important;
	}
	.selectize-control.plugin-remove_button [data-value] .remove {
		padding: 0px 10px 0 0 !important;
		font-size: 10px;
		top: -2px;
	}
	.selectize-control.plugin-remove_button [data-value] {
		padding-right: 24px!important;
	}

</style>
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><a href="<?php echo $url;?>video_deals">Video Deals Management</a></li>
            <li><span>Upload Edited Videos</span></li>
        </ul>
    </div>
    <?php $total = count($raw_video);
    ?>
    <div id="page_content_inner">
        <div class="md-card">
            <div class="md-card-content large-padding">
                <form id="edited_video" class="uk-form-stacked">
                    <?php if(isset($edited_video_details)){
                    if(empty($edited_video_details->portal_thumb)){?>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-1-1">
                            <h3>
                                Orginal Video
                            </h3>

                            <?php $total = count($raw_video);
                            $i = 1;
                            ?>
                            <?php foreach ($raw_video as $video){
                                if($i == 1 || (($i%2) == 1)){
                                    ?>
                                    <div class="uk-grid" data-uk-grid-margin>
                                <?php }
                                ?>
                                <div class="uk-width-medium-3-6">
                                    <div class="uk-width-medium-3-6 box-set">
                                        <div class="md-card-head md-bg-grey-900">
                                            <div class="uk-cover uk-position-relative uk-height-1-1 transform-origin-50" >
                                                <video style="width: 100%;height: 100%;" controls ><source src="<?php echo $root.$video['url'];?>">Your browser does not support HTML5 video.</video>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $file_data = $getid3->analyze('./../'.$video['url']);
                                    if(isset($file_data['filesize'])){?>
                                    <div class="uk-width-medium-3-6 box-set">
                                        <?php
                                        if(isset($file_data['filesize'])){
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
                                            }
                                            ?>
                                        </div>
                                        <?php if(isset($file_data['video'])){ ?>

                                        <div><b> Dimension :</b> <?php echo $file_data['video']['resolution_x'].' X '.$file_data['video']['resolution_y'];?></div>
                                        <div><b> Frame Rate :</b> <?php echo $file_data['video']['frame_rate'];?></div>
                                        <div><b> Duration :</b> <?php echo $file_data['playtime_string'];?></div>
										<?php }
                                        ?>
                                    </div>
                                    <?php }?>
                                </div>





                                <?php if($i == $total || (($i%2) == 0)){?>
                                    </div>
                                <?php }
                                $i++;
                            }?>




                            <div class="uk-width-1-2" style="display: none;">
                                <?php //if($video){?>
                                    <iframe  src = "<?php //echo $video;?>"  frameborder="0" allowfullscreen style="height: 60vh;width:80%;"></iframe>
                                <?php //} ?>
                            </div>
                        </div>
                    </div>
                    <br/><br/>
                    <?php }
                    } ?>

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-1-1">
                            <!--<label for="mrss" class="inline-label"><b>Editing Description</b></label>-->
                            <p style="color: #e42e06;padding: 5px 25px;"><?php echo $dealData->video_editing_description; ?></p>
                        </div>
                        <div class="uk-width-1-4">
                            <div class="uk-width-medium-1-1" style="padding: 15px 0px;">
                                <?php if($selected_mrss_categories) {
                                    $checkedgernalmrss = 'checked';

                                }else{
                                    $checkedgernalmrss = '';
                                }
                                ?>
                                <input type="checkbox" name="is_mrss" id="is_mrss" value="" <?php echo $checkedgernalmrss ?> data-md-icheck />
                                <label for="mrss" class="inline-label"><b>Publish to MRSS</b>

                                </label>
                            </div>
                        </div>

                        <div class="uk-width-2-4" id="mrss_id" style="display:block;">
                            <div class="parsley-row" data-uk-grid-margin>

                                <select id="mrss_categories" name="mrss_categories[]" class="selectize-control" data-parsley-required-message="Rating point is required." multiple>
                                    <?php
                                    foreach ($mrss_categories as $mrss) {

                                        $selected = array();

                                        foreach ($selected_mrss_categories as $video_cat) {

                                            if ($mrss['title'] === $video_cat['title']) {

                                                $selected = 'selected="selected"';
                                            }
                                        }
                                        ?>
                                        <option value="<?php echo $mrss['id']; ?>" <?php print_r($selected); ?>><?php echo $mrss['title']; ?></option>
                                    <?php }?>
                                </select>


                                <div class="error"></div>
                            </div>

                        </div>



                    </div>
                    <br/><br/>
                   <?php $excheckstyle='';
                   if($non_exclusive_partner_data){
                            $excheckstyle='display:none;';
                    } ?>
                    <div class="uk-grid ex_top_area" style="<?php echo $excheckstyle?>" data-uk-grid-margin>
                        <div class="uk-width-1-4">
                            <div class="uk-width-medium-1-1" style="padding: 15px 0px;">
                                <?php
                                $style='display:none;';
                                $checked='';
                                $ex_feed_id= '';
                                $ex_partner_id='';
                                $noncheckstyle ='';

                                if($mrss_feed_data){
                                    foreach($mrss_feed_data as $mrss_feed_video){
                                        if($mrss_feed_video['exclusive_to_partner'] > 0){
                                            $checked ='checked';
                                            $style='display:block;';
                                            $noncheckstyle='display:none;';

                                        }
                                    }
                                }else{
                                    $style='display:none;';
                                    $checked ='';
                                }
                                ?>
                                <input type="checkbox" name="is_mrss_partner" id="is_mrss_partner" value="" data-md-icheck <?php echo $checked ;?> />
                                <label for="mrss" class="inline-label"><b>Exclusive to a partner MRSS feed</b>
                                </label>

                            </div>
                        </div>
                        <div class="uk-width-1-4" id="mrss_partner_id" style="<?php echo $style ?>">
                            <div class="parsley-row" data-uk-grid-margin>
                                <select id="mrss_partner" name="mrss_partner" class="selectize-control" data-parsley-required-message="MRSS partner is required." data-md-selectize>
                                    <?php

                                    foreach($mrss_partners->result() as $mrss){
                                        $selectedex='';
                                        if($mrss_feed_data){
                                            $exstyle='display:none;';
                                            foreach($mrss_feed_data as $mrss_feed_video){
                                                if($mrss_feed_video['exclusive_to_partner'] == $mrss->partner_id){
                                                    $selectedex = 'selected="selected"';
                                                    $ex_feed_id = $mrss_feed_video['feed_id'];
                                                    $ex_partner_id = $mrss_feed_video['exclusive_to_partner'];
                                                    $mrss_id=$mrss->partner_id;
                                                  /*  print 'new mrss';
                                                    print_r($mrss->partner_id);
                                                   */
                                                }
                                            }?>
                                    <option value="<?php echo $mrss->partner_id;?>" <?php echo $selectedex;?>><?php echo $mrss->full_name;?></option>
                                        <?php } else { ?>

                                        <option disabled="disabled" selected="selected" ></option>
                                        <option value="<?php echo $mrss->partner_id;?>" <?php echo $selectedex;?>><?php echo $mrss->full_name;?></option>
                                    <?php }
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>
                        <div class="uk-width-1-4" id="mrss_partner_cat" style="<?php echo $style ?>">
                            <div class="parsley-row" data-uk-grid-margin>
                                <select id="mrss_partner_cat_opt" name="mrss_partner_cat_opt[]" class="selectize-control" data-parsley-required-message="Rating point is required." multiple>
                                </select>
                            </div>

                        </div>
                    </div>
                    <br/><br/>
                    <div class="uk-grid in_ex_top_area" style="<?php echo $noncheckstyle?>" data-uk-grid-margin>
                        <div class="uk-width-1-4">
                            <div class="uk-width-medium-1-1" style="padding: 15px 0px;">
                                <?php
                                $nonstyle='display:none;';
                                $nonchecked='';
                                if($mrss_feed_data){
                                    if($non_exclusive_partner_data){
                                        foreach($non_exclusive_partner_data as $non_ex_feed){
                                            if($non_ex_feed){
                                                $nonchecked ='checked';
                                                $nonstyle='display:block;';

                                            }
                                        }
                                    }
                                }else{
                                    $nonstyle='display:none;';
                                    $nonchecked ='';
                                }
                                ?>
                                <input type="checkbox" name="not_mrss_partner" id="not_mrss_partner" value="" data-md-icheck <?php echo $nonchecked ?> />
                                <label for="mrss" class="inline-label"><b>Non-Exclusive to a partner MRSS feed</b>
                                </label>
                            </div>
                        </div>

                        <div class="uk-width-1-4" id="all_mrss_partner_id" style="<?php echo $nonstyle ?>">
                            <div class="parsley-row" data-uk-grid-margin>
                                <select id="all_mrss_partners" name="all_mrss_partners[]" class="selectize-control" data-parsley-required-message="Rating point is required." multiple>
                                    <?php
                                    foreach($mrss_partners->result() as $mrss){
                                        $selectednon='';
                                        if($mrss_feed_data){
                                            foreach($non_exclusive_partner_data as $non_ex_feed){
                                                if($non_ex_feed['partner_id'] == $mrss->partner_id){
                                                    $selectednon = 'selected="selected"';
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                        <option value="<?php echo $mrss->partner_id;?>" <?php print_r($selectednon);?>><?php echo $mrss->full_name;?></option>
                                    <?php } ?>

                                </select>
                            </div>

                        </div>
                        <div class="uk-width-1-4" id="all_mrss_partner_cat" style="<?php echo $nonstyle ?>">
                            <div class="parsley-row" data-uk-grid-margin>
                                <select id="all_mrss_cat" name="all_mrss_cat[]" class="selectize-control" data-parsley-required-message="Rating point is required." multiple>
                                    <?php
                                    foreach($mrss_partners->result() as $mrss){
                                        $allselectednon = '';
                                        if($mrss_feed_data){
                                            foreach($non_exclusive_partner_data as $non_ex_feed){
                                                if($non_ex_feed['id'] == $mrss->id){
                                                    $allselectednon = 'selected="selected"';
                                                    break;
                                                }
                                            }
                                        }
                                        ?>
                                        <option value="<?php echo $mrss->id;?>" <?php echo ($allselectednon);?>><?php echo $mrss->url;?></option>
                                    <?php } ?>

                                </select>
                            </div>

                        </div>
                    </div>
                    <br/><br/>

                    <h3 class="heading_a" style="margin-bottom: 15px;">
                        Upload Youtube Video and Thumbnail
                        <span class="sub-heading">Allow users to upload files through a file input form element or a placeholder area</span>
                    </h3>
                    <label>
                        <input type="checkbox" class="filled-in" name="yt_thumbnail_provided"/>
                        <span>Upload Thumbnail</span>
                    </label>
                    <?php if(isset($edited_video_details)){
                    if(!empty($edited_video_details->yt_url)){?>
                    <label>
                        <input type="checkbox" class="delete_youtube_video" name="yt_delete_video"/>
                        <span>Delete Youtube Video</span>
                    </label>
                    <?php }
                    } ?>
                    <div class="uk-grid grd-bdr" data-uk-grid-margin>


                        <div class="uk-width-1-2 padd-0 youtube_main_edited" <?php if(!empty($edited_video_details->yt_url)){echo 'style="pointer-events:none;"';}?>>

                            <div id="file_upload-drop_yt" class="uk-file-upload">

                                <input type="file" name="file-yt" style="display: none;">
                                <div class="cm-yt" style="display: none;font-weight: bolder; margin-top:-29px"><i class="material-icons">done_all</i> Complete</div>
                            </div>
                            <input type="hidden" class="yt_video" name="yt_video" id="yt_video" value=""><div class="error"></div>

                        </div>
                        <div class="uk-width-1-2 padd-0 youtube_main_edited" <?php if(!empty($edited_video_details->yt_url)){echo 'style="pointer-events:none;"';}?>>
                            <input type="file" id="input-file-a" name="yt_thumb" class="dropify"/>
                            <div class="error"></div>
                        </div>
                    </div>

                    <br/><br/>
                    <h3 class="heading_a" style="margin-bottom: 15px;">
                        Upload Facebook Video and Thumbnail
                        <span class="sub-heading">Allow users to upload files through a file input form element or a placeholder area.</span>
                    </h3>
                    <label>
                        <input type="checkbox" class="filled-in"  name="fb_thumbnail_provided"/>
                        <span>Upload Thumbnail</span>
                    </label>
                    <?php if(isset($edited_video_details)){
                    if(!empty($edited_video_details->fb_url)){
                        ?>
                    <label>
                        <input type="checkbox" class="delete_facebook_video" name="fb_delete_video"/>
                        <span>Delete Facebook Video</span>
                    </label>
                    <?php }
                    } ?>
                    <div class="uk-grid grd-bdr" data-uk-grid-margin>

                        <div class="uk-width-1-2 padd-0 facebook_main_edited" <?php if(!empty($edited_video_details->fb_url)){echo 'style="pointer-events:none;"';}?>>
                            <div id="file_upload-drop_fb" class="uk-file-upload">

                                <input type="file" name="file-fb" style="display: none;">
                                <div class="cm-fb" style="display: none;font-weight: bolder; margin-top:-29px"><i class="material-icons">done_all</i> Complete</div>
                            </div>
                            <input type="hidden" class="fb_video" name="fb_video" id="fb_video" value=""><div class="error"></div>

                        </div>
                        <div class="uk-width-1-2 padd-0 facebook_main_edited" <?php if(!empty($edited_video_details->fb_url)){echo 'style="pointer-events:none;"';}?>>
                            <input type="file" id="input-file-b" name="fb_thumb" class="dropify"/>
                            <div class="error"></div>
                        </div>
                    </div>


                    <br/><br/>
                    <h3 class="heading_a" style="margin-bottom: 15px;">
						<?php if ($total > 1): ?>
                        Upload MRSS Video and Thumbnail
						<?php else: ?>
						Upload Thumbnail
						<?php endif; ?>
                        <span class="sub-heading">Allow users to upload files through a file input form element or a placeholder area.</span>
                    </h3>
                   <!-- <label>
                        <input type="checkbox" class="filled-in"  name="mrss_thumbnail_provided"/>
                        <span>Upload Thumbnail</span>
                    </label>-->
					<!--

					-->
					<?php if ($total == 1): ?>
					<div class="uk-width-1-4">
						<div class="uk-width-medium-1-1" style="padding: 15px 0px;">
							<input type="checkbox" name="chkbox-upload-single-video" id="chkbox-upload-single-video" value="1"  data-md-icheck / >
							<label for="mrss" class="inline-label"><b>Upload Video</b>
							</label>
						</div>
					</div>
					<?php endif; ?>

                    <div class="uk-grid grd-bdr" data-uk-grid-margin>
                        <?php if($total > 1){//if($total > 1){?>
                        <div id="portal_video_div" class="uk-width-1-2 padd-0" style="display:<?php echo ($total == 1)?("none"):("block"); ?>">

                            <div id="file_upload-drop_mrss" class="uk-file-upload">

                                <input type="file" name="file-mrss" style="display: none;">
                                <div class="cm-mrss" style="display: none;font-weight: bolder; margin-top:-29px"><i class="material-icons">done_all</i> Complete</div>
                            </div>

							<div>
                                <input type="hidden" name="portal_video_check">
								<input type="hidden" name="portal_video" class="portal_video" data-parsley-required-message="This field is required." id="portal_video" value="">
								<p style="font-weight: bolder">MRSS Video <span style="color: red">*</span></p>
								<div class="error"></div>
							</div>

                        </div>
                        <?php } else {?>
                        <div id="portal_video_div" class="uk-width-1-2 padd-0" style="display:<?php echo ($total == 1)?("none"):("block"); ?>">

                            <div id="file_upload-drop_mrss" class="uk-file-upload">

                                <input type="file" name="file-mrss" style="display: none;">
                                <div class="cm-mrss" style="display: none;font-weight: bolder; margin-top:-29px"><i class="material-icons">done_all</i> Complete</div>
                            </div>

                            <div>
                                <input type="hidden" name="portal_video" class="portal_video" data-parsley-required-message="This field is required." id="portal_video" value="">
                                <p style="font-weight: bolder">MRSS Video <span style="color: red">*</span></p>
                                <div class="error"></div>
                            </div>

                        </div>
                        <?php } ?>
                        <div class="uk-width-1-2 padd-0">

                            <!--<input type="file" id="input-file-c" name="portal_thumb" data-parsley-required-message="This field is required." class="dropify"
                                   data-min-height="719"
                                   data-min-width="1279"
                                   data-max-height="721"
                                   data-max-width="1281"
                            />-->
							<div>
								<input type="file" id="portal_thumb" name="portal_thumb" data-parsley-required-message="This field is required." class="dropify"
									   data-min-height="719"
									   data-min-width="1279"
									   data-max-height="721"
									   data-max-width="1281"
								/>

								<p style="font-weight: bolder">MRSS Thumbnail <span style="color: red">*</span></p>
								<div class="error"></div>
							</div>
                        </div>

                    </div>
                    <br/>
                    <input type="hidden" name="video_id" id="video_id" value="<?php echo $video_id;?>">
                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <button type="submit" class="md-btn md-btn-primary">Save</button>
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>

<script>
    var ukey = '<?php echo trim($dealData->unique_key);?>';
    var exfedid='<?php echo $ex_feed_id ?>';
    var expartnerid='<?php echo $ex_partner_id ?>';
</script>