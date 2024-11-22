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

                        </div>

                        </section>
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

