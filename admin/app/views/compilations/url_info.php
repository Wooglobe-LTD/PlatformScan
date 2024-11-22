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
                <form id="form_validation2" class="uk-form-stacked" action="<?php echo base_url('compilations_urls_info_save');?>" method="post" data-parsley-required-message="This field is required">

                    <?php foreach($leads as $i=>$lead){ ?>

                        <section style="border: 1px solid #000;padding: 10px;border-radius: 10px;margin-bottom: 10px;">
                         <?php if(count($leads) > 1){ ?>
                            <h3>Lead <?php echo ($i+1);?></h3>
                         <?php } ?>
                                <input type="hidden" name="leads[<?php echo $i;?>][lead_group_id]" value="<?php echo $lead->lead_group_id;?>">
                                <input type="hidden" name="leads[<?php echo $i;?>][id]" value="<?php echo $lead->id;?>">

                            <div class="uk-grid" data-uk-grid-margin="">
                                <div class="uk-width-medium-1-2 uk-row-first">
                                    <ul class="md-list">
                                        <li>
                                            <div class="md-list-content">
                                                <span class="uk-text-small uk-text-muted">Thumbnail</span>
                                                <span id="edit-title-area" class="md-list-heading" data-val=""><img src="<?php echo $lead->thumb;?>" alt="video thumb"></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-content">
                                                <span class="uk-text-small uk-text-muted">Video Title</span>
                                                <span id="edit-title-area" class="md-list-heading" data-val=""><?php echo $lead->title ?></span>
                                            </div>
                                        </li>


                                    </ul>
                                </div>
                                <div class="uk-width-medium-1-2 uk-row-first">
                                    <ul class="md-list">

                                        <li>
                                            <div class="md-list-content">
                                                <span class="uk-text-small uk-text-muted">Video URL</span>
                                                <span id="edit-title-area" class="md-list-heading" data-val=""><a href="<?php echo $lead->yt_url ?>" target="_blank"><?php echo $lead->yt_url ?></a></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-content">
                                                <span class="uk-text-small uk-text-muted">Youtube id</span>
                                                <span id="edit-title-area" class="md-list-heading" data-val=""><?php echo $lead->yt_id ?></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-content">
                                                <span class="uk-text-small uk-text-muted">Views</span>
                                                <span id="edit-title-area" class="md-list-heading"
                                                      data-val=""><?php echo $lead->views ?></span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="md-list-content">
                                                <span class="uk-text-small uk-text-muted">Category</span>
                                                <span id="edit-title-area" class="md-list-heading"
                                                      data-val=""><?php echo $lead->category ?></span>
                                            </div>
                                        </li>


                                    </ul>
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
                                <?php if(empty($id)){?>
                                    <div class="uk-width-medium-1-2">
                                        <div class="parsley-row">
                                            <select  name="leads[<?php echo $i;?>][rating]" data-md-selectize data-parsley-required-message="This field is required.">
                                                <option value=""> Select Rating</option>
                                                <?php for($j = 1; $j<=10; $j++){ ?>
                                                    <option <?php if($j == $lead->rating){ echo 'selected';} ?> value="<?php echo $j;?>"> <?php echo $j;?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </section>

                        <?php if(!empty($id) && $leads_infos->num_rows() == 0){ ?>
                        <section style="border: 1px solid #000;padding: 10px;border-radius: 10px;margin-bottom: 10px;">

                            <input type="hidden" name="leads_info[<?php echo $i;?>][lead_group_id]" value="<?php echo $lead->lead_group_id;?>">
                            <input type="hidden" name="leads_info[<?php echo $i;?>][lead_id]" value="<?php echo $lead->id;?>">
                            <div class="uk-grid" data-uk-grid-margin="">
                                <!--<div class="uk-width-medium-1-3">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label">TikTok ID</label>
                                        <span><?php /*echo $lead->tt_id;*/?></span>
                                    </div>
                                </div>-->
                                <div class="uk-width-medium-1-2">
                                    <div class="parsley-row">
                                        <label for="message" class="uk-form-label">TikTok URL</label>
                                        <input type="url"  name="leads_info[<?php echo $i;?>][tt_url]" value="<?php echo $lead->tt_url;?>" class="md-input" required data-parsley-required-message="This field is required." />

                                    </div>
                                </div>
                                <div class="uk-width-medium-1-2">
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
                                        <input type="text"  name="leads_info[<?php echo $i;?>][start_time]" value="<?php echo $lead->start_time;?>" class="md-input masked_input" data-parsley-required-message="This field is required." data-inputmask="'mask': '99:99'" data-inputmask-showmaskonhover="false" im-insert="true" />
                                    </div>
                                    <div class="parsley-row" style="width: 45%; float: left;">
                                        <label for="message" class="uk-form-label">End</label>
                                        <input type="text"  name="leads_info[<?php echo $i;?>][end_time]" value="<?php echo $lead->end_time;?>" data-parsley-required-message="This field is required." class="md-input masked_input" data-inputmask="'mask': '99:99'" data-inputmask-showmaskonhover="false" im-insert="true" />
                                    </div>
                                </div>
                            </div>

                        </section>
                        <?php } ?>


                            <?php } ?>
                    <?php
                    if(!empty($id)){

                        foreach($leads_infos->result() as $i=>$lead1){ ?>



                            <section style="border: 1px solid #000;padding: 10px;border-radius: 10px;margin-bottom: 10px;">

                                <input type="hidden" name="leads_info[<?php echo $i;?>][lead_group_id]" value="<?php echo $lead1->lead_group_id;?>">
                                <input type="hidden" name="leads_info[<?php echo $i;?>][lead_id]" value="<?php echo $lead1->lead_id;?>">
                                <div class="uk-grid" data-uk-grid-margin="">
                                    <!--<div class="uk-width-medium-1-3">
                                        <div class="parsley-row">
                                            <label for="message" class="uk-form-label">TikTok ID</label>
                                            <input type="text"  name="leads_info[<?php /*echo $i;*/?>][tt_id]" value="<?php /*echo $lead1->tt_id;*/?>" class="md-input" required data-parsley-required-message="This field is required." />

                                        </div>
                                    </div>-->
                                    <div class="uk-width-medium-1-2">
                                        <div class="parsley-row">
                                            <label for="message" class="uk-form-label">TikTok URL</label>
                                            <input type="url"  name="leads_info[<?php echo $i;?>][tt_url]" value="<?php echo $lead1->tt_url;?>" class="md-input" required data-parsley-required-message="This field is required." />

                                        </div>
                                    </div>
                                    <div class="uk-width-medium-1-2">
                                        <div class="parsley-row">
                                            <label for="message" class="uk-form-label">TikTok Handle</label>
                                            <input type="text"  name="leads_info[<?php echo $i;?>][tt_handle]" value="<?php echo $lead1->tt_handle;?>" class="md-input" required data-parsley-required-message="This field is required." />

                                        </div>
                                    </div>
                                </div>
                                <div class="uk-grid" data-uk-grid-margin="">
                                    <div class="uk-width-medium-1-2">
                                        <div class="parsley-row">
                                            <select  name="leads_info[<?php echo $i;?>][status]" data-md-selectize>
                                                <?php if($lead1->status == 'Acquired') { ?>
                                                    <option <?php if($lead1->status == 'Acquired'){ echo 'selected';} ?> value="Acquired"> Acquired</option>
                                                <?php }else{ ?>
                                                    <option value=""> Select Status</option>
                                                    <option <?php if($lead1->status == 'Approached'){ echo 'selected';} ?> value="Approached"> Approached </option>
                                                    <!--<option <?php /*if($lead->status == 'Acquired'){ echo 'selected';} */?> value="Acquired"> Acquired</option>-->
                                                    <option <?php if($lead1->status == 'Not Interested'){ echo 'selected';} ?> value="Not Interested"> Not Interested</option>
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
                                            <input type="text"  name="leads_info[<?php echo $i;?>][start_time]" value="<?php echo $lead1->start_time;?>" class="md-input" data-parsley-required-message="This field is required." data-inputmask="'mask': '99:99'" data-inputmask-showmaskonhover="false" im-insert="true" />
                                        </div>
                                        <div class="parsley-row" style="width: 45%; float: left;">
                                            <label for="message" class="uk-form-label">End</label>
                                            <input type="text"  name="leads_info[<?php echo $i;?>][end_time]" value="<?php echo $lead1->end_time;?>" data-parsley-required-message="This field is required." class="md-input" data-inputmask="'mask': '99:99'" data-inputmask-showmaskonhover="false" im-insert="true" />
                                        </div>
                                    </div>
                                </div>

                            </section>



                    <?php }
                    } ?>

                    <div id="more-leads">

                    </div>
                    <br/>

                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <button type="submit" class="md-btn md-btn-primary check">Save</button>
                            <?php if(!empty($id)){?>
                            <button type="button" id="leads-more" class="md-btn md-btn-primary check">Add More</button>
                            <?php } ?>
                        </div>
                    </div>
                </form>
			</div>

		</div>

	</div>

</div>
</div>
<div id="more-section" style="display: none;">
    <section style="border: 1px solid #000;padding: 10px;border-radius: 10px;margin-bottom: 10px;">
        <span class="delete-lead-info" style="float: right;cursor:pointer">X</span>
        <?php if(!empty($id)){?>
            <input type="hidden" name="leads_info[{VID}][lead_group_id]" value="<?php echo $lead->lead_group_id;?>">
            <input type="hidden" name="leads_info[{VID}][lead_id]" value="<?php echo $lead->id;?>">
            <div class="uk-grid" data-uk-grid-margin="">
                <!--<div class="uk-width-medium-1-3">
                    <div class="parsley-row">
                        <label for="message" class="uk-form-label">TikTok ID</label>
                        <input type="text"  name="leads_info[{VID}][tt_id]" value="" class="md-input" required data-parsley-required-message="This field is required." />

                    </div>
                </div>-->
                <div class="uk-width-medium-1-2">
                    <div class="parsley-row">
                        <label for="message" class="uk-form-label">TikTok URL</label>
                        <input type="url"  name="leads_info[{VID}][tt_url]" value="" class="md-input" required data-parsley-required-message="This field is required." />

                    </div>
                </div>
                <div class="uk-width-medium-1-2">
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
        <?php } ?>
    </section>
</div>
<script>
    var uid = '';
    var watertype  = '';
    var access   = '';
    var v_index   = "<?php echo $i;?>";
</script>

