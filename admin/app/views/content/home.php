
<div id="page_content">
    <div id="top_bar">
        <ul id="breadcrumbs">
            <li><a href="<?php echo $url;?>">Dashboard</a></li>
            <li><a href="<?php echo $url;?>content">Content Management</a></li>
            <li><span>Edit Page (<?php echo $data->title;?>)</span></li>
        </ul>
    </div>
    <div id="page_content_inner">

        <h3 class="heading_b uk-margin-bottom"><?php echo $title;?></h3>

        <div class="md-card">
            <div class="md-card-content large-padding">
                <form id="form_home_banner" class="uk-form-stacked">
                    <input type="hidden" name="id" value="<?php echo $data->id;?>" id="id" />
					<div class="uk-grid" data-uk-grid-margin>
						<h3>Banner Section</h3>
					</div>
					<div class="uk-grid" data-uk-grid-margin>

						<div class="uk-width-1-2">
							<div id="file_upload_banner_mp4_drop" class="uk-file-upload" style="height: 100%;">
								<p class="uk-text" style="margin-top: 5%;">Drop MP4 video file to upload</p>
								<p class="uk-text-muted uk-text-small uk-margin-small-bottom">or</p>
								<a class="uk-form-file md-btn">choose file<input id="file_upload_banner_mp4_select" type="file"></a>
							</div>
							<div id="file_upload_banner_mp4_progressbar" class="uk-progress uk-hidden" style="margin-top: -20px !important;">
								<div class="uk-progress-bar" style="width:0">0%</div>
							</div>
							<input type="hidden" class="banner_video_mp4" name="banner_video_mp4" id="banner_video_mp4" value="<?php echo $data->banner_video_mp4;?>">
							<div class="error"></div>
						</div>
						<div class="uk-width-1-2">
							<div id="file_upload_banner_webm_drop" class="uk-file-upload" style="height: 100%;">
								<p class="uk-text" style="margin-top: 5%;">Drop WEBM video file to upload</p>
								<p class="uk-text-muted uk-text-small uk-margin-small-bottom">or</p>
								<a class="uk-form-file md-btn">choose file<input id="file_upload_banner_webm_select" type="file"></a>
							</div>
							<div id="file_upload_banner_webm_progressbar" class="uk-progress uk-hidden" style="margin-top: -20px !important;">
								<div class="uk-progress-bar" style="width:0">0%</div>
							</div>
							<input type="hidden" class="banner_video_webm" name="banner_video_webm" id="banner_video_webm" value="<?php echo $data->banner_video_webm;?>">
							<div class="error"></div>
						</div>
					</div>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="banner_h1">Banner Top Heading<span class="req">*</span></label>
								<input value="<?php echo $data->banner_h1;?>" type="text" name="banner_h1" id="banner_h1" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." required class="md-input" />
								<div class="error"></div>
							</div>
						</div>
						<div class="uk-width-medium-1-2">
							<div class="parsley-row">
								<label for="banner_h2">Banner Bottom Heading<span class="req">*</span></label>
								<input value="<?php echo $data->banner_h2;?>" type="text" name="banner_h2" id="banner_h2" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." required class="md-input" />
								<div class="error"></div>
							</div>
						</div>

					</div>
					<div class="uk-grid">
						<div class="uk-width-1-1">
							<button type="submit" id="banner_btn" class="md-btn md-btn-primary" style="float: right;">Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		<div class="md-card">
			<div class="md-card-content large-padding">
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="title">Page Title<span class="req">*</span></label>
                                <input value="<?php echo $data->title;?>" type="text" name="title" id="title_e" pattern="[a-zA-Z0-9\s]+" data-parsley-pattern-message="Only alphabet and number are allowed." data-parsley-required-message="This field is required." required class="md-input" />
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="description" class="uk-form-label">Page Description<span class="req">*</span></label>
                                <textarea id="description" name="description" cols="30" rows="20" class="wysiwyg_tinymce"><?php echo $data->description;?></textarea>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="meta_keywords" class="uk-form-label">Meta Keywords</label>
                                <textarea id="meta_keywords_e" name="meta_keywords" class="md-input"><?php echo $data->meta_keywords;?></textarea>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>

                    <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <label for="meta_description_e" class="uk-form-label">Meta Description</label>
                                <textarea id="meta_description_e" name="meta_description" class="md-input"><?php echo $data->meta_description;?></textarea>
                                <div class="error"></div>
                            </div>
                        </div>

                    </div>
                    <div class="uk-grid" data-uk-grid-margin>

                        <div class="uk-width-medium-1-1">
                            <div class="parsley-row">
                                <select id="status_u" name="status_u" data-parsley-required-message="This field is required." required data-md-selectize>
                                    <option value="">Choose..</option>
                                    <?php $status = '';
                                    if($data->status == 0){
                                        $status = 'Inactive';
                                        ?>
                                        <option value="1">Active</option>
                                    <?php } ?>
                                    <?php if($data->status == 1){
                                        $status = 'Active';
                                        ?>
                                        <option value="0">Inactive</option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>




                    <div class="uk-grid">
                        <div class="uk-width-1-1">
                            <button type="submit" class="md-btn md-btn-primary">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var edit_data = '<?php echo $edit_data;?>';

    var sts = '<?php echo $status;?>';

    var assets = '<?php echo $asset;?>';
</script>
